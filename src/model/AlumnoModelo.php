<?php 
    class AlumnoModelo {
        private $nombre;
        private $apellido;
        private $dni;
        private $libreta;
        private $cohorte;
        private $legajo;

        // -------------------- CONSTRUCTOR --------------------

        public function __construct($nombre, $apellido, $dni, $legajo, $libreta, $cohorte ) {
            $this -> nombre = $nombre;
            $this -> apellido = $apellido;
            $this->dni = $dni; 
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
            return $this -> dni;
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

        public function setDni($dni) {
            if (empty($dni) || !is_string($dni) || $dni <= 0) {
                throw new Exception("El DNI es invalido, debe ser un número entero");
            }
            $this -> dni = trim($dni);
        }

        public function setLibreta($libreta) {
            if (!is_numeric($libreta) || intval($libreta) != $libreta || $libreta <= 0) {
                throw new Exception("El nro de libreta es inválido, debe ser un número entero");
            }
            $this->libreta = intval($libreta);
        }

        public function setCohorte($cohorte) {
            if (empty($cohorte) || !is_numeric($cohorte) || $cohorte <= 0) {
                throw new Exception("El cohorte es inválido, debe ser un año válido");
            }
            $this->cohorte = intval($cohorte);

        }

        public function setLegajo($legajo) {
            $this->legajo = $legajo;
        }

    }

?>