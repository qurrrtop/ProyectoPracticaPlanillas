<?php

    declare( strict_types = 1 );

    namespace app\controllers;

    use app\config\ConnectionDB;

    use app\dao\UsuarioDAO;
    use app\dao\CoordinadorDAO;
    use app\dao\MateriaDAO;
    use app\dao\PlanillaDAO;
    use app\dao\AlumnoDAO;
    use app\dao\ExamenDAO;

    use app\service\CoordinadorService;
    use app\service\MateriaService;
    use app\service\CreateUserService;
    use app\service\VerPlanillaService;
    use app\service\AlumnoService;
    use app\service\ExamenService;

    use Exception;

    class CoordinadorController {

        private $coordinadorService;
        private $materiaService;
        private $createUserService;
        private $verPlanillaService;
        private $MateriaDAO;
        private $alumnoService;
        private $examenService;

        // Constructor del controller:
        // 1. Se asegura de que la sesión esté iniciada.
        // 2. Instancia los DAOs necesarios para acceder a la base de datos.
        // 3. Crea el CoordinadorService pasando los DAOs, para separar la lógica de negocio del controller.

        public function __construct() {
            // Iniciar sesión si no está iniciada
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Instanciar DAOs y Service
            $connectionDB = ConnectionDB::getInstancia();
            $usuarioDAO = new UsuarioDAO($connectionDB);
            $coordinadorDAO = new CoordinadorDAO($connectionDB);
            $materiaDAO = new MateriaDAO($connectionDB);
            $planillaDAO = new PlanillaDAO($connectionDB);
            $alumnoDAO = new AlumnoDAO($connectionDB);
            $examenDAO = new ExamenDAO( $connectionDB );

            $this->alumnoService = new AlumnoService($alumnoDAO);
            $this->MateriaDAO = $materiaDAO;
            $this->coordinadorService = new CoordinadorService($usuarioDAO, $coordinadorDAO, $alumnoDAO, $materiaDAO);
            $this->materiaService = new MateriaService($materiaDAO, $coordinadorDAO);
            $this->createUserService = new CreateUserService($usuarioDAO);
            $this->verPlanillaService = new VerPlanillaService($planillaDAO);
            $this->examenService = new ExamenService( $examenDAO );

            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        }

        // ----- Método que verifica si hay un usuario logueado en la sesión ------
        // ----- si no lo hay, lo redirige automáticamente al login -------

        private function verificarLogin() {
            if (session_status() === PHP_SESSION_NONE) session_start();

            if (empty($_SESSION['usuario'])) {
                header("Location: index.php?controller=Login&action=login");
                exit;
            }
        }
        
        // ---------- Método que redirecciona al home del coordinador, luego ----------
        // ---------- de que este se haya logueado ----------------------------

        public function home() {
            // estos dos if verifican la session para evitar errores
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['usuario']['idPersona'])) {
                header("Location: index.php?controller=Login&action=login");
                exit;
            }
            
            $idPersona = $_SESSION['usuario']['idPersona'];

            $data = $this->coordinadorService->getDataForHome();

            include __DIR__ . '/../views/coordinador/home.php';
        }

        // -------- Método que redirecciona al panel del coordinador --------
        
        public function panelCoord() {
            $this->verificarLogin();

            // se guardan mensajes que estan en la sesión (si vienen)
            $mensaje = $_SESSION['mensaje'] ?? '';
            $mensaje_error = $_SESSION['mensaje_error'] ?? '';
            // eliminamos los mensajes despues de leerlos para que no se repitan al refrescar
            unset($_SESSION['mensaje'], $_SESSION['mensaje_error']);

            // obtenemos los IDs de las materias seleccionadas desde la sesión (array)
            $materiasSeleccionadasIds = $_SESSION['materias_seleccionadas'] ?? [];

            $nombreMateriasSeleccionadas = [];
            // pedimos al service los nombres de dichas materias por media de sus ID's
            if (!empty($materiasSeleccionadasIds)) {
                try {
                    // getMateriasByIds debe devolver un array de arrays con idMateria y nombre de las materias
                    $nombreMateriasSeleccionadas = $this->materiaService->getMateriasByIds($materiasSeleccionadasIds);
                } catch (Exception $e) {
                    error_log("Error al obtener materias por IDs en panelCoord: " . $e->getMessage());
                    $mensaje_error = "No se pudieron cargar las materias seleccionadas.";
                }
            }

            // con esto se cuenta las cantidad de materias que se seleccionaron,
            // útil para el el formulario de dar de alta docentes
            $cantidadMateriasSeleccionadas = count($materiasSeleccionadasIds);

            // CSRF (si no existe, crearlo)
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            // ahora require de la vista (la vista usará $materiasSeleccionadasInfo)
            require __DIR__ . '/../views/coordinador/panelCoord.php';
        }

        // ---- Método que redirecciona a una vista que muestra todas las materias de la carrera ----
        // ---- para asignarle al docente a dar de alta ------

        public function materias() {
            $this -> verificarLogin();
            
            $idPersona = $_SESSION['usuario']['idPersona'];

            // Trae todas las materias agrupadas por año
            $materiasPorAnio = $this->materiaService->getMateriasAgrupadasPorAnio();
            // trae todas las materias que ya estén ocupadas
            $materiasOcupadas = $this->materiaService->getMateriasOcupadas();

            $materiasSeleccionadasIds = $_SESSION['materias_seleccionadas'] ?? [];

            // CSRF
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            // Mensaje de feedback
            $mensaje = $_SESSION['mensaje'] ?? '';
            unset($_SESSION['mensaje']);

            require __DIR__ . '/../views/coordinador/materias.php';
        }

        // --------- (Método relacionado con la anterior) -----------
        // ----- Método que guarda los cambios al seleccioanar o quitar una materia ----
        // --------- (marcar o desmarcar checkbox's) ---------------

        public function seleccionarMaterias() {
            $this->verificarLogin();
            
            try {
                // verifica método
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    throw new Exception("Acceso inválido al formulario.");
                }

                // valida el token CSRF
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    throw new Exception("Token CSRF inválido.");
                }

                // recibe las materias seleccionadas de los checkbox's
                $materiasSeleccionadas = isset($_POST['materias']) ? $_POST['materias'] : [];

                // se guardan en sesión
                $_SESSION['materias_seleccionadas'] = $materiasSeleccionadas;
                // pequeño mensaje
                $_SESSION['mensaje'] = "Materias seleccionadas correctamente.";

                // redirige
                header("Location: index.php?controller=Coordinador&action=panelCoord");
                exit;

            } catch (Exception $e) {

                error_log("Error en seleccionarMaterias: " . $e->getMessage());

                // guarda el mensaje de error en sesión para luego mostrarlo
                $_SESSION['mensaje_error'] = $e->getMessage();

                // redirige igual a la vista
                header("Location: index.php?controller=Coordinador&action=panelCoord");
                exit;
            }
        }

        // ---- Método que le permite al coordinador dar de alta a nuevos usuarios ----

        public function darAltaUsuario() {
            $this->verificarLogin();
            
            // validamos el token CSRF como siempre
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['mensaje'] = "Error: token CSRF inválido.";
                header("Location: index.php?controller=Coordinador&action=panelCoord");
                exit;
            }
            
            try {
                // se crea el usuario nuevo
                $nuevoUsuario = $this->createUserService->createUser(
                    $_POST['userName'],
                    $_POST['password'],
                    $_POST['nombre'],
                    $_POST['apellido'],
                    $_POST['email']
                );

                // obtenemos las materias seleccionadas desde la sesión
                $materiasSeleccionadas = $_SESSION['materias_seleccionadas'] ?? [];

                if (!empty($materiasSeleccionadas)) {
                    $this->coordinadorService->asignarMaterias(
                        $nuevoUsuario->getIdPersona(),
                        $materiasSeleccionadas
                    );
                }

                // limpiamos la sesión para evitar que queden materias viejas
                unset($_SESSION['materias_seleccionadas']);

                $_SESSION['mensaje_exito'] = "Docente creado y materias asignadas correctamente.";
                header("Location: index.php?controller=Coordinador&action=panelCoord");
                exit;
            } catch (Exception $e) {
                $_SESSION['mensaje_error'] = "Error al crear usuario o al asignar materias: " . $e->getMessage();
                header("Location: index.php?controller=Coordinador&action=panelCoord");
                exit;
            }
        }

        public function verPlanillas() {
            $this->verificarLogin();

            try {
                $anios = $this->materiaService->getAllAnioMateria();
                $materiasPorAnio = $this->materiaService->getMateriasAgrupadasPorAnio();
            } catch (Exception $e) {
                error_log("Error al obtener datos para verPlanillas: " . $e->getMessage());
                $_SESSION['mensaje'] = "No se pudieron cargar los datos de las materias.";
                $anios = [];
                $materiasPorAnio = [];
            }

            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            $anio = null;
            $idMateria = null;

            require __DIR__ . '/../views/coordinador/verPlanillas.php';
        }

        // método que toma los datos de los select y trae toda la información
        // de la materia conrrespondiente y lo muestra
        public function getDataPlanilla() {
            $this->verificarLogin();

            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['mensaje'] = "Error: token CSRF inválido.";
                header("Location: index.php?controller=Coordinador&action=verPlanillas");
                exit;
            }

            // toma los datos de los select
            $anio = $_POST['anio'] ?? null;
            $idMateria = $_POST['idMateria'] ?? null;

            // verificamos que no estén vacios
            if (empty($anio) || empty($idMateria)) {
                $_SESSION['mensaje'] = "Debe seleccionar un año y una materia.";
                header("Location: index.php?controller=coordinador&action=verPlanillas");
                exit();
            }



            try {
                // datos para selects y encabezado
                $datosMateria = $this->materiaService->getDataMateria((int)$idMateria);
                $anios = $this->materiaService->getAllAnioMateria();
                $materiasPorAnio = $this->materiaService->getMateriasAgrupadasPorAnio();

                // datos de los alumnos de la materia seleccionada
                $alumnos = $this->alumnoService->obtenerAlumnosByMateria((int)$idMateria);

                // pasar variables esperadas a la vista
                require __DIR__ . '/../views/coordinador/verPlanillas.php';
            } catch (Exception $e) {
                error_log("Error al obtener datos de la materia en getDataPlanilla: " . $e->getMessage());
                $_SESSION['mensaje'] = "Ocurrió un error al cargar los datos de la materia.";
                header("Location: index.php?controller=Coordinador&action=verPlanillas");
                exit();
            }
        }

  public function getExamen() {
    $this->verificarLogin();

    $idAlumno = (int)$_POST['idAlumno'];
    $idMateria = (int)$_POST['idMateria'];
    $anio = (int)$_POST['anio'];

    if (!$idAlumno || !$idMateria) {
        $_SESSION['mensaje'] = "Datos incompletos.";
        header("Location: index.php?controller=Coordinador&action=verPlanillas");
        exit;
    }

    try {
        // 1) Traer exámenes
        $examenes = $this->examenService->obtenerExamenByAlumno($idAlumno, $idMateria);

        // 2) Cargar todo como en getDataPlanilla
        $datosMateria   = $this->materiaService->getDataMateria($idMateria);
        $anios          = $this->materiaService->getAllAnioMateria();
        $materiasPorAnio = $this->materiaService->getMateriasAgrupadasPorAnio();
        $alumnos        = $this->alumnoService->obtenerAlumnosByMateria($idMateria);

        // 3) Marcar el alumno seleccionado
        foreach ($alumnos as &$a) {
            if ((int)$a['idAlumno'] === $idAlumno) {
                $a['mostrar_detalles'] = true;
                $a['examenes'] = $examenes;
            }
        }
        unset($a);

        include __DIR__ . '/../views/coordinador/verPlanillas.php';

    } catch (Exception $e) {
        error_log("Error al obtener finales: " . $e->getMessage());
        $_SESSION['mensaje'] = "Ocurrió un error.";
        header("Location: index.php?controller=Coordinador&action=verPlanillas");
        exit;
    }
  }

}

?>