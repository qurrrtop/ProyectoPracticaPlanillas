<?php 

  require_once __DIR__ . "/../dao/CoordinadorDAO.php";

  class CoordinadorService {

    private $coordinadorDAO;

    public function __construct( CoordinadorDAO $coordinadorDAO ) {
      $this->coordinadorDAO = $coordinadorDAO;
    }

    

  }

?>