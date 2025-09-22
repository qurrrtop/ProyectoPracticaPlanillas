<?php
    require_once __DIR__."/../config/ConexionBD.php";
    require_once __DIR__."/../DAO/DocenteDAO.php";
    require_once __DIR__."/../service/DocenteService.php";

    class DocenteController {
        public function home() {
            include __DIR__ . '/../views/coordinador/home.php';
        }
    }
?>