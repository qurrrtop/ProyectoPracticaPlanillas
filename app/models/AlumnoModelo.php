<?php 

    declare( strict_types = 1 );

    namespace app\models;

    use InvalidArgumentException;
    use app\utilities\StringFieldType;
    use app\utilities\IntFieldType;
    
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
            $this -> dni = $dni; 
            $this -> legajo = $legajo;
            $this -> libreta = $libreta;
            $this -> cohorte = $cohorte;
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
            if( !StringFieldType::stringToValidate( $nombre, StringFieldType::NAME ) ) {
            throw new InvalidArgumentException( "Nombre ingresado no valido." );
        }
        $this->nombre = $nombre;
        }

        public function setApellido($apellido) {
            if( !StringFieldType::stringToValidate( $apellido, StringFieldType::SURNAME ) ) {
            throw new InvalidArgumentException( "Apellido ingresado no valido." );
        }
    
        $this -> apellido = $apellido;
        }

        public function setDni($dni) {

            if( !IntFieldType::intToValidate( $dni, IntFieldType::DNI ) ) {
            throw new InvalidArgumentException( "DNI ingresado no valido." );
        }
        $this->dni = $dni;
        }

        public function setLibreta($libreta) {
            if (!is_numeric($libreta) || intval($libreta) != $libreta || $libreta <= 0) {
                throw new InvalidArgumentException("El nro de libreta es inválido, debe ser un número entero");
            }
            $this->libreta = intval($libreta);
        }

        public function setCohorte($cohorte) {
            if (empty($cohorte) || !is_numeric($cohorte) || $cohorte <= 0) {
                throw new InvalidArgumentException("El cohorte es inválido, debe ser un año válido");
            }
            $this->cohorte = intval($cohorte);

        }

        public function setLegajo($legajo) {
            $this->legajo = $legajo;
        }

    }

?>