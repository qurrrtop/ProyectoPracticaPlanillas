<?php

    declare( strict_types = 1 );

    namespace app\service;

    use app\dao\MateriaDAO;
    use Exception;



    class MateriaService {

        private $materiaDAO;

        public function __construct(MateriaDAO $materiaDAO) {
            $this->materiaDAO = $materiaDAO;
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

        public function getAllAnioMateria() {
            try {
                return $this->materiaDAO->getAllAnioMateria();
            } catch (Exception $e) {
                error_log("Error en MateriaService->getAllAnioMateria: " . $e->getMessage());
                throw new Exception("No se pudieron obtener los años de las materias.");
            }
        }

    }