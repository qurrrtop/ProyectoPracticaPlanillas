<?php
require_once __DIR__ . '/../DAO/MateriaDAO.php';

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
        $materias = $this->getTodasLasMaterias(); // devuelve array de objetos MateriaModel

        $materiasPorAnio = [
            1 => [],
            2 => [],
            3 => [],
            4 => []
        ];

        foreach ($materias as $materia) {
            $anio = $materia->getAnio(); // llamá al getter que tengas en tu MateriaModel
            if (isset($materiasPorAnio[$anio])) {
                $materiasPorAnio[$anio][] = $materia;
            }
        }

        return $materiasPorAnio;
    }
}