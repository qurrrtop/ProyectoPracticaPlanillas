<?php
    
    abstract class UsuarioModelo {
        protected $idUsuario;
        protected $passwordHash;
        protected $userName;
        protected $nombre;
        protected $apellido;
        protected $dni;
        protected $email;
        protected $telefono;
        protected $direccion;
        protected $fnacimiento;
    

    // se coloca null los atributos por que se heredara
    public function __CONSTRUCT($idUsuario = null, $nombre = null, $apellido = null, $dni = null, $email = null, $telefono = null, $direccion = null, $fnacimiento = null, $passwordHash = null, $userName = null) {
        $this -> idUsuario = $idUsuario;
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
        $this -> dni = $dni;
        $this -> email = $email;
        $this -> telefono = $telefono;
        $this -> direccion = $direccion;
        $this -> fnacimiento = $fnacimiento;
        $this->userName = $userName;
        $this->passwordHash = $passwordHash;
    }

    public function getPasswordHash() {
        return $this->passwordHash;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getDni() {
        return $this->dni;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getFnacimiento() {
        return $this->fnacimiento;
    }

    public function setNombre($nombre) {
        if (empty($nombre) || !is_string($nombre) || $nombre === null) {
            throw new Exception('El nombre de la persona es una cadena de texto y no puede estar vacia');
        }
        $this -> nombre = trim($nombre);
    }

    public function setApellido($apeliido) {
        if (empty($apeliido) || !is_string($apeliido) || $apeliido === null) {
            throw new Exception('El apellido de la persona es una cadena de texto y no puede estar vacia');
        }
        $this -> apellido = trim($apeliido);

    }

    public function setDni($dni) {
        if (!is_numeric($dni) || $dni <= 0) {
            throw new Exception('El dni solo puede contener números cuyo valor siempre será mayor a 0');
        } 
        $this -> dni = trim($dni);
    }

    public function setEmail($email) {
        if (empty($email) || filter_var($email, '', FILTER_VALIDATE_EMAIL) !== false) {
            throw new Exception("El correo electrónico ingresado no tiene un formato valido o está vacio");
        }
        $this -> email = trim($email);
    }

    public function setTelefono($telefono) {
        if (filter_var($telefono, '', FILTER_VALIDATE_INT) !== false) {
            throw new Exception("El número de teléfono ingresado no es valido");
        }
        $this -> telefono = trim($telefono);
    }

    public function setDireccion($direccion) {
        if (empty($direccion) || !is_string($direccion) || $direccion === false) {
            throw new Exception("La dirección es una cadena de texto, no puede estar vacia");
        }
        $this -> direccion = trim($direccion);
    }

    //filter var(filter_validate_email), verifica si tiene un formato valido para email

    //no es una forma apropiada de validar fechas;
    //se puede mejorar haciendolo de otra manera;haciendo
    //un objeto DataTime.
    //el 'error.log' solo lo vé el desarrollador;
    public function setFnacimiento($fnacimiento) {
        try {
            $date = new Datetime($fnacimiento);
        }catch(Exception $e) {
            error_log("El valor ingresado de la fecha de nacimiento no es valido". $e);
            throw new Exception('El valor ingresado para la fecha de nacimiento no tiene un formato valido');
        }

        // $now = new DateTime();
        // if ($date < $now) {
        //     throw new Exception('la fecha de vencimiento no puede ser menor que la actual');
        // }
        //esto solo se usa para fecha de vencimientos

    }



    // MÉTODO SET USUARIO - CONTRASEÑA;
    //validación para el user y password (que no esté vacio, que sea string y que no sea nulo);
    //la validación de longitud, caracteres, números, minúsculas y mayúsculas en otra capa.

        public function setUserName($userName) {
            if (empty($userName) || !is_string($userName) || $userName === null) {
                throw new Exception("El nombre de user es obligatorio y debe ser una cadena de texto");
            }
            $this->userName = trim($userName);
        }

        public function setPasswordHash($passwordHash) {
            if (empty($passwordHash) || !is_string($passwordHash) || $passwordHash === null) {
                throw new Exception("La password no es valida y debe ser una cadena de texto");
            }
            $this->passwordHash = trim($passwordHash);
        }
    }
        // la clase persona es abstracta; porque las demás clases heredarán de allí los atributos 
    //los controles se harán en diferentes capas, con diferentes validaciones;
    // en capas de 'servicio' o 'controller'
    //el try catch lo usaremos en todas las capas.
?>