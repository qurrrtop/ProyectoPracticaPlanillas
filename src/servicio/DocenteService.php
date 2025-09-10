<?php

  require_once __DIR__ . "/../dao/DocenteDAO.php";

  class DocenteService {

    private $docenteDAO;

    public function __construct( DocenteDAO $docenteDAO ) {
      $this->docenteDAO = $docenteDAO;
    }

    

  }

?>