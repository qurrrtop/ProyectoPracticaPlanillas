<?php

  declare(strict_types=1);

  namespace app\service;

  use app\dao\PlanillaDAO;

  class VerPlanillaService {

    private PlanillaDAO $planillaDAO;

    public function __construct(PlanillaDAO $planillaDAO) {
        $this->planillaDAO = $planillaDAO;
    }

    public function obtenerPlanillasPorMateria(int $idMateria): array {
        return $this->planillaDAO->obtenerPlanillasPorMateria($idMateria);
    }
  }
  
?>