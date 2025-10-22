<?php
    require_once __DIR__.'/../DAO/CoordinadorDAO.php';
    require_once __DIR__ . '/../DAO/UsuarioDAO.php';
    require_once __DIR__ . '/../models/CoordinadorModel.php';
    require_once __DIR__ . '/validate/Validation.php';

    class CoordinadorService {

        private $usuarioDAO;
        private $coordinadorDAO;

        public function __construct(UsuarioDAO $usuarioDAO, CoordinadorDAO $coordinadorDAO) {
            $this->usuarioDAO = $usuarioDAO;
            $this->coordinadorDAO = $coordinadorDAO;
        }
    
        // Devuelve IDs de materias que tiene asignadas un usuario
        public function getMateriasDelUsuario(int $idPersona): array {
            return $this->coordinadorDAO->traerMateriasPorUsuario($idPersona); // array de int
        }

        public function actualizarMateriasDelUsuario($idPersona, $materiasSeleccionadas): bool {
            // materias actuales en BD
            $materiasDeUsuario = $this->coordinadorDAO->traerMateriasPorUsuario($idPersona);
            $materiasActuales = array_column($materiasDeUsuario, 'idMateria');

            // calcular diferencias
            $materiasAAgregar = array_diff($materiasSeleccionadas, $materiasActuales);
            $materiasAQuitar  = array_diff($materiasActuales, $materiasSeleccionadas);

            try {
                // asignar nuevas
                foreach ($materiasAAgregar as $idMateria) {
                    $this->coordinadorDAO->asignarMateria($idPersona, $idMateria);
                }

                // quitar las desmarcadas
                foreach ($materiasAQuitar as $idMateria) {
                    $this->coordinadorDAO->quitarMateria($idPersona, $idMateria);
                }

                return true;

            } catch (Exception $e) {
                throw new Exception("Error al actualizar materias: " . $e->getMessage());
            }
        }

        // método trae los datos del coordinadorDAO, los almacena en un array, y
        // lo pasa al controlador para su posterior uso en la vista home.

        public function getDataForHome(int $idPersona) {
            return [
                'materias' => $this->coordinadorDAO->countMateriasCoord($idPersona),
                'alumnos' => $this->coordinadorDAO->countAlumnnos(),
                'docentes' => $this->coordinadorDAO->countDocentes()
            ];
        }
    }
?>
