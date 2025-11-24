<?php

    declare( strict_types = 1 );

    namespace app\service;

    use app\dao\MateriaDAO;
    use app\dao\CoordinadorDAO;
    use Exception;



    class MateriaService {

        private $materiaDAO;
        private $coordinadorDAO;

        public function __construct(MateriaDAO $materiaDAO, CoordinadorDAO $coordinadorDAO) {
            $this->materiaDAO = $materiaDAO;
            $this->coordinadorDAO = $coordinadorDAO;
        }

        public function getMateriasByIds(array $materiasId): array {
            // pequeña validación
            if (!is_array($materiasId) || empty($materiasId)) {
                return [];
            }

            return $this->materiaDAO->readMateriasByIds($materiasId);
        }

        public function getTodasLasMaterias(): array {
            return $this->materiaDAO->readAllMateria();
        }

        // Devuelve todas las materias agrupadas por año
        public function getMateriasAgrupadasPorAnio(): array {
            try {
                $materias = $this->materiaDAO->readAllMateria();
                $materiasPorAnio = [];

                foreach ($materias as $m) {
                    // si es objeto
                    $anio = is_object($m) ? $m->getAnio() : $m['anio'];
                    $id = is_object($m) ? $m->getIDMateria() : $m['idMateria'];
                    $nombre = is_object($m) ? $m->getNombre() : $m['nombre'];

                    if (!isset($materiasPorAnio[$anio])) {
                        $materiasPorAnio[$anio] = [];
                    }

                    $materiasPorAnio[$anio][] = [
                        'idMateria' => $id,
                        'nombre' => $nombre
                    ];
                }

                return $materiasPorAnio;

            } catch (Exception $e) {
                error_log("Error en getMateriasAgrupadasPorAnio: " . $e->getMessage());
                throw $e;
            }
        }

        // método que se comunica con el DAO para traer toda la información
        // de la materia
        public function getDataMateria(int $idMateria): array {
            try {
                $data = $this->materiaDAO->getDataMateria($idMateria);

                if (empty($data)) {
                    throw new Exception("No se encontraron datos para la materia seleccionada.");
                }

                return $data;

            } catch (Exception $e) {
                error_log("MateriaService -> Error al obtener datos de la materia: " . $e->getMessage());
                throw $e;
            }
        }

        // este método soluciona un bug de los select's de años.
        public function getAllAnioMateria() {
            try {
                return $this->materiaDAO->getAllAnioMateria();
            } catch (Exception $e) {
                error_log("Error en MateriaService->getAllAnioMateria: " . $e->getMessage());
                throw new Exception("No se pudieron obtener los años de las materias.");
            }
        }

        // método que controla qué materías están ocupadas por un docente
        public function getMateriasOcupadas() {
            try {
                return $this->coordinadorDAO->getMateriasOcupadas();
            } catch (Exception $e) {
                error_log("MateriaService -> Error al obtener las materias ocupadas: " . $e->getMessage());
                throw $e;
            }
        }

        public function getAllMateriasByUsers(): array {
            try {
                return $this->coordinadorDAO->getAllMateriasByUsers();

            } catch (Exception $e) {
                error_log("MateriaService -> Error al obtener las materias por usuario: ".$e->getMessage());
                throw $e;
            }
        }

    }