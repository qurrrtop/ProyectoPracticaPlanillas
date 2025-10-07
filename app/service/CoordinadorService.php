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
        public function getMateriasDelUsuario(int $idUsuario): array {
            return $this->coordinadorDAO->traerMateriasPorUsuario($idUsuario); // array de int
        }

        public function actualizarMateriasDelUsuario($idUsuario, $materiasSeleccionadas): bool {
            // materias actuales en BD
            $materiasDeUsuario = $this->coordinadorDAO->traerMateriasPorUsuario($idUsuario);
            $materiasActuales = array_column($materiasDeUsuario, 'idMateria');

            // calcular diferencias
            $materiasAAgregar = array_diff($materiasSeleccionadas, $materiasActuales);
            $materiasAQuitar  = array_diff($materiasActuales, $materiasSeleccionadas);

            try {
                // asignar nuevas
                foreach ($materiasAAgregar as $idMateria) {
                    $this->coordinadorDAO->asignarMateria($idUsuario, $idMateria);
                }

                // quitar las desmarcadas
                foreach ($materiasAQuitar as $idMateria) {
                    $this->coordinadorDAO->quitarMateria($idUsuario, $idMateria);
                }

                return true;

            } catch (Exception $e) {
                throw new Exception("Error al actualizar materias: " . $e->getMessage());
            }
        }
    }
?>
