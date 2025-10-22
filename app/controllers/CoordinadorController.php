<?php

    declare( strict_types = 1 );

    namespace app\controllers;

    use app\config\ConnectionDB;
    use app\dao\UsuarioDAO;
    use app\dao\CoordinadorDAO;
    use app\dao\MateriaDAO;
    use app\service\CoordinadorService;
    use app\service\MateriaService;
    use app\service\CreateUserService;
    use Exception;

    class CoordinadorController {

        private $coordinadorService;
        private $materiaService;
        private $createUserService;

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

            $this->coordinadorService = new CoordinadorService($usuarioDAO, $coordinadorDAO);
            $this->materiaService = new MateriaService($materiaDAO);
            $this->createUserService = new CreateUserService($usuarioDAO);
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

            $data = $this->coordinadorService->getDataForHome($idPersona);

            include __DIR__ . '/../views/coordinador/home.php';
        }

        // -------- Método que redirecciona al panel panel del coordinadir y
        // ---------- muestra las materias del coordinador ---------
        
        public function panelCoord() {
            $this -> verificarLogin();

            $idPersona = $_SESSION['usuario']['idPersona'];

            $materiasAsignadas = $this->coordinadorService->getMateriasDelUsuario($idPersona);

            $materiasAsignadasIds = array_column($materiasAsignadas, 'idMateria');

            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            $mensaje = $_SESSION['mensaje'] ?? '';
            unset($_SESSION['mensaje']);

            require __DIR__ . '/../views/coordinador/panelCoord.php';
        }

        // ---- Método que redirecciona a una vista que muestra las materias del coordinador ----
        // ---- y marca con un check los ya asignados a él ------

        public function misMaterias() {
            $this -> verificarLogin();
            
            $idPersona = $_SESSION['usuario']['idPersona'];

            // Trae todas las materias agrupadas por año
            $materiasPorAnio = $this->materiaService->getMateriasAgrupadasPorAnio();

            // Convierte a array de IDs para marcar los checkboxes
            $materiasAsignadas = array_map(
                fn($m) => $m['idMateria'], 
                $this->coordinadorService->getMateriasDelUsuario($idPersona)
            );

            // CSRF
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            // Mensaje de feedback
            $mensaje = $_SESSION['mensaje'] ?? '';
            unset($_SESSION['mensaje']);

            require __DIR__ . '/../views/coordinador/misMaterias.php';
        }

        // --------- (Método relacionado con la anterior) -----------
        // ----- Método que guarda los cambios al seleccioanar o quitar una materia ----
        // --------- (marcar o desmarcar checkbox's) ---------------

        public function guardarMisMaterias() {
            $this->verificarLogin();
            $idPersona = $_SESSION['usuario']['idPersona'];

            // CSRF
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['mensaje'] = "Error: token CSRF inválido.";
                header("Location: index.php?controller=Coordinador&action=misMaterias");
                exit;
            }

            $materiasSeleccionadas = $_POST['materias'] ?? [];

            try {
                $this->coordinadorService->actualizarMateriasDelUsuario($idPersona, $materiasSeleccionadas);

                $_SESSION['mensaje'] = "Materias actualizadas correctamente.";
            } catch (Exception $e) {
                $_SESSION['mensaje'] = "Error al actualizar materias: " . $e->getMessage();
            }

            header("Location: index.php?controller=Coordinador&action=misMaterias");
            exit;
        }

        // ---- Método que le permite al coordinador dar de alta a nuevos usuarios ----

        public function darAltaUsuario() {
            $this->verificarLogin();
            
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['mensaje'] = "Error: token CSRF inválido.";
                header("Location: index.php?controller=Coordinador&action=panelCoord");
                exit;
            }
            
            try {
                $nuevoUsuario = $this->createUserService->createUser(
                    $_POST['userName'],
                    $_POST['password'],
                    $_POST['nombre'],
                    $_POST['email']
                );
                
                $_SESSION['mensaje'] = "Usuario creado correctamente.";
                header("Location: index.php?controller=Coordinador&action=asignarMaterias&idPersona=" . $nuevoUsuario->getidPersona());
                exit;
            } catch (Exception $e) {
                $_SESSION['mensaje'] = "Error al crear usuario: " . $e->getMessage();
                header("Location: index.php?controller=Coordinador&action=panelCoord");
                exit;
            }
        }

        // --------- (Método relacionado con la anterior) -----------
        // ----- Luego de dar de alta un usuario, el método redirecciona a una ----
        // ----- vista con la lista de materias, para asignarle al nuevo usuario ----

        public function asignarMaterias() {
            $this->verificarLogin();

            if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); }

            // obtener y validar idPersona desde request
            $idPersonaRaw = $_POST['idPersona'] ?? $_GET['idPersona'] ?? null;
            if ($idPersonaRaw === null) {
                throw new \Exception('idPersona no proporcionado');
            }
            if (!filter_var($idPersonaRaw, FILTER_VALIDATE_INT)) {
                throw new \Exception('idPersona inválido');
            }
            $idPersona = (int) $idPersonaRaw;
    
            // obtener materias y asignadas
            $materiasPorAnio = $this->materiaService->getMateriasAgrupadasPorAnio();
            
            $materiasAsignadas = array_map(
                fn($m) => $m['idMateria'], 
                $this->coordinadorService->getMateriasDelUsuario($idPersona)
            );
    
            require __DIR__ . '/../views/coordinador/asignarMaterias.php';
        }

        // --------- (Método relacionado con la anterior) -----------
        // ----- Método que guarda los cambios al asignarle las materias ----
        // ----------- al usuario recien dado de alta ---------
            
        public function guardarAsignacionMaterias() {
            $this->verificarLogin();

            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['mensaje'] = "Error: token CSRF inválido.";
                header("Location: index.php?controller=Coordinador&action=panelCoord");
                exit;
            }

            $idPersona = isset($_POST['idPersona']) ? intval($_POST['idPersona']) : null;
            $materiasSeleccionadas = $_POST['materias'] ?? [];

            if ($idPersona === null) {
                $_SESSION['mensaje'] = "Error: usuario inválido.";
                header("Location: index.php?controller=Coordinador&action=panelCoord");
                exit;
            }

            try {
                $this->coordinadorService->actualizarMateriasDelUsuario($idPersona, $materiasSeleccionadas);

                $_SESSION['mensaje'] = "Materias asignadas correctamente.";
                header("Location: index.php?controller=Coordinador&action=asignarMaterias&idPersona=" . $idPersona);
                exit;
            } catch (Exception $e) {
                $_SESSION['mensaje'] = "Error al asignar materias: " . $e->getMessage();
                header("Location: index.php?controller=Coordinador&action=asignarMaterias&idPersona=" . $idPersona);
                exit;
            }
        }

        // ------------- Método incompleto ----------------

        public function verPlanillas() {
            $this->verificarLogin();
            include __DIR__ . '/../views/coordinador/verPlanillas.php';
        }


    }

?>