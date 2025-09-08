<?php
class PlanillaModelo {
        private $idPlanilla;
        private $asistencia;
        private $promedio;
        private $condicion;
      

        // -------------------- CONSTRUCTOR --------------------

        public function __construct($idPlanilla, $asistencia, $promedio,  $condicion) {
            $this -> idPlanilla = $idPlanilla;
            $this -> asistencia = $asistencia;
            $this->promedio = $promedio; 
            $this -> condicion = $condicion;
           
      
        }

        // -------------------- MÉTODOS GET --------------------

        public function getPlanilla() {
            return $this -> idPlanilla;
        }

        public function getAsistencia() {
            return $this -> asistencia;
        }

        public function getPromedio() {
            return $this -> promedio;
        }

        public function getCondicion() {
            return $this -> condicion;
        }



        // -------------------- MÉTODOS SET --------------------

        public function setPlanilla($idPlanilla) {
            if (empty($idPlanilla) || !is_string($idPlanilla) || $idPlanilla === null) {
                throw new Exception("El idPlanilla es invalido, debe ser una cadena de texto");
            }
            $this -> idPlanilla = trim($idPlanilla);
        }

        public function setAsistencia($asistencia) {
            if (empty($asistencia) || !is_string($asistencia) || $asistencia === null) {
                throw new Exception("La asistencia es invalido, debe ser una cadena de texto");
            }
            $this -> asistencia = trim($asistencia);
        }

        public function setPromedio($promedio) {
            if (empty($promedio) || !is_string($promedio) || $promedio <= 0) {
                throw new Exception("El Promedio es invalido, debe ser un número entero");
            }
            $this -> promedio = trim($promedio);
        }

        public function setCondicion($condicion) {
            $this->condicion=$condicion;
        }

    }
?>