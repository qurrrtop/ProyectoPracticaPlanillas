<?php 
    class AlumnoModelo {
        private $nombre;
        private $apellido;
        private $legajo;
        private $libreta;
        private $cohorte;
        private $legajo;

        // -------------------- CONSTRUCTOR --------------------

        public function __construct($nombre, $apellido, $legajo, $libreta, $cohorte, $legajo) {
            $this -> nombre = $nombre;
            $this -> apellido = $apellido;
            $this -> legajo = $legajo;
            $this -> libreta = $libreta;
            $this -> cohorte = $cohorte;
            $this -> legajo = $legajo;
        }

        // -------------------- MÉTODOS GET --------------------

        public function getNombre() {
            return $this -> nombre;
        }

        public function getApellido() {
            return $this -> apellido;
        }

        public function getDni() {
            return $this -> legajo;
        }

        public function getLibreta() {
            return $this -> libreta;
        }

        public function getCohorte() {
            return $this -> cohorte;
        }

        public function getLegajo() {
            return $this -> legajo;
        }

        // -------------------- MÉTODOS SET --------------------

        public function setNombre($nombre) {
            if (empty($nombre) || !is_string($nombre) || $nombre === null) {
                throw new Exception("El nombre es invalido, debe ser una cadena de texto");
            }
            $this -> nombre = trim($nombre);
        }

        public function setApellido($apellido) {
            if (empty($apellido) || !is_string($apellido) || $apellido === null) {
                throw new Exception("El apellido es invalido, debe ser una cadena de texto");
            }
            $this -> apellido = trim($apellido);
        }

        public function setDni($legajo) {
            if (empty($legajo) || !is_string($legajo) || $legajo <= 0) {
                throw new Exception("El DNI es invalido, debe ser un número entero");
            }
            $this -> legajo = trim($legajo);
        }

        public function setLegajo($legajo) {
            if (empty($legajo) || !is_string($legajo) || $legajo <= 0) {
                throw new Exception("El legajo es invalido, debe ser un número entero");
            }
            $this -> legajo = trim($legajo);
        }

    }

?>