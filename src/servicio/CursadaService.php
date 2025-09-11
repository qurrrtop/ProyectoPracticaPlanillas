<?php 

  require_once __DIR__ . "/../dao/CursadaDAO.php";

  class CursadaService {

    private $cursadaDAO;

    public function __construct( CursadaDAO $cursadaDAO ) {
      $this->cursadaDAO = $cursadaDAO;
    }

    

  }

?>