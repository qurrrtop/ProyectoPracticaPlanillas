<?php 

  require_once __DIR__ . "/../dao/MateriaDAO.php";

  class MateriaService {

    private $materiaDAO;

    public function __construct( MateriaDAO $materiaDAO ) {
      $this->materiaDAO = $materiaDAO;
    }

    

  }

?>