<?php 
    require_once __DIR__."/../config/ConnectionDB.php";
    require_once __DIR__."/../DAO/CoordinadorDAO.php";
    require_once __DIR__."/../service/CoordinadorService.php";

    class CoordinadorController {

        public function home() {
            include __DIR__ . '/../views/coordinador/home.php';
        }

        public function panelCoord() {
            include __DIR__ . '/../views/coordinador/panelCoord.php';
        }

        public function verPlanillas() {
            include __DIR__ . '/../views/coordinador/verPlanillas.php';
        }

    }

?>