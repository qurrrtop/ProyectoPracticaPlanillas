<?php

    declare( strict_types = 1 );

    namespace app\service;

    use app\dao\CoordinadorDAO;
    use app\dao\UsuarioDAO;
    use Exception;
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
    }
?>
