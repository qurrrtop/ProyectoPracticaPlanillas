<?php

    declare( strict_types = 1 );

    namespace app\models;
    
    use Exception;

    class CursadaModelo {
        private $idCursada;
        private $añoCursada;
        private $FechaIni;
        private $FechaFin;
      
        // -------------------- CONSTRUCTOR --------------------

        public function __construct($idCursada, $añoCursada, $FechaIni, $FechaFin ) {
            $this -> idCursada = $idCursada;
            $this -> añoCursada = $añoCursada;
            $this->FechaIni = $FechaIni; 
            $this -> FechaFin = $FechaFin;
         
            }

        // -------------------- MÉTODOS GET --------------------

        public function getCursada() {
            return $this -> idCursada;
        }

        public function getAñoCursada() {
            return $this -> añoCursada;
        }

        public function getFechaIni() {
            return $this -> FechaIni;
        }

        public function getFechafin() {
            return $this -> FechaFin;
        }

       

    

        // -------------------- MÉTODOS SET --------------------

        public function setCursada($idCursada) {
            if (empty($idCursada) || !is_string($idCursada) || $idCursada === null) {
                throw new Exception("El idCursada es invalido, debe ser una cadena de texto");
            }
            $this -> idCursada = trim($idCursada);
        }

        public function setAñoCursada($añoCursada) {
            if (empty($añoCursada) || !is_string($añoCursada) || $añoCursada === null) {
                throw new Exception("El añoCursada es invalido, debe ser una cadena de texto");
            }
            $this -> añoCursada = trim($añoCursada);
        }

        public function setFechaIni($FechaIni) {
            if (empty($FechaIni) || !is_string($FechaIni) || $FechaIni <= 0) {
                throw new Exception("La FechaIni es invalido, debe ser un número entero");
            }
            $this -> FechaIni = trim($FechaIni);
        }

        public function setFechaFin($FechaFin) {
            if (!is_numeric($FechaFin) || intval($FechaFin) != $FechaFin || $FechaFin <= 0) {
                throw new Exception("La FechaFin es inválido, debe ser un número entero");
            }
            $this->FechaFin = intval($FechaFin);
        }

    
    }


?>