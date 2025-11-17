<?php
declare(strict_types=1);

namespace app\service;

use app\dao\AlumnoDAO;
use Exception;

class AlumnoService {
    private AlumnoDAO $alumnoDAO;

    public function __construct(AlumnoDAO $alumnoDAO) {
        $this->alumnoDAO = $alumnoDAO;
    }

    public function obtenerAlumnosByMateria( int $idMateria ): array {
        return $this->alumnoDAO->readAlumnosByMateria( $idMateria );
    }

}
?>