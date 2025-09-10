<?php 

  require_once __DIR__ . "/../dao/AlumnoDAO.php";

  class AlumnoService {

    private $alumnoDAO;

    public function __construct( AlumnoDAO $alumnoDAO ) {
      $this->alumnoDAO = $alumnoDAO;
    }

    

  }

?>