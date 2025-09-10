<?php 

  require_once __DIR__ . "/../dao/PlanillaDAO.php";

  class PlanillaService {

    private $planillaDAO;

    public function __construct( PlanillaDAO $planillaDAO ) {
      $this->planillaDAO = $planillaDAO;
    }

    

  }

?>