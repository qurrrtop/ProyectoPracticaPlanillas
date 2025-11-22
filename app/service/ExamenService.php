<?php 

  declare( strict_types = 1 );

  namespace app\service;

  use app\dao\ExamenDAO;

  class ExamenService {
    
    private ExamenDAO $examenDAO;

    public function __construct( ExamenDAO $examen ) {
      $this->examenDAO = $examen;
    }

    public function obtenerExamenByAlumno( int $idAlumno, int $idMateria ) {
      return $this->examenDAO->readExamenByAlumno( $idAlumno, $idMateria );
      echo "service";
    }

  }

?>