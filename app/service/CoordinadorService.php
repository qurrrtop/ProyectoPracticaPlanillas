<?php

    declare( strict_types = 1 );

    namespace app\service;

    use app\dao\CoordinadorDAO;
    use app\dao\AlumnoDAO;
    use app\dao\UsuarioDAO;
    use app\dao\MateriaDAO;
    use Exception;
    class CoordinadorService {

        private $usuarioDAO;
        private $coordinadorDAO;
        private $alumnoDAO;
        private $materiaDAO;

        public function __construct(UsuarioDAO $usuarioDAO, CoordinadorDAO $coordinadorDAO, AlumnoDAO $alumnoDAO, MateriaDAO $materiaDAO) {
            $this->usuarioDAO = $usuarioDAO;
            $this->coordinadorDAO = $coordinadorDAO;
            $this->alumnoDAO = $alumnoDAO;
            $this->materiaDAO = $materiaDAO;
        }
    
        // Devuelve IDs de materias que tiene asignadas un usuario
        public function getMateriasDelUsuario(int $idPersona): array {
            return $this->coordinadorDAO->traerMateriasPorUsuario($idPersona); // array de int
        }

        // método trae los datos del coordinadorDAO, los almacena en un array, y
        // lo pasa al controlador para su posterior uso en la vista home.
        public function getDataForHome() {
            return [
                'materias' => $this->materiaDAO->countMateriasTotal(),
                'alumnos' => $this->alumnoDAO->countAlumnos(),
                'docentes' => $this->coordinadorDAO->countDocentes()
            ];
        }

        public function asignarMaterias(int $idPersona, array $materias): bool {
            try {
                $result = $this->coordinadorDAO->asignarMaterias($idPersona, $materias);
                return $result;
  
            } catch (Exception $e) {
                error_log("CoordinadorService -> Error general al asignar materias: " . $e->getMessage());
                throw $e;
            }
        }
    }
?>
