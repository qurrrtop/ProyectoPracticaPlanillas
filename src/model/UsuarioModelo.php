<?php
    
    class UsuarioModelo extends PersonaModelo {
        protected $password;
        protected $user;
    }

    public function __CONSTRUCT($id = null, $nombre = null, $apellido = null, $dni = null, $email = null, $telefono = null, $direccion = null, $fnacimiento = null, $password = null, $user = null) {

        parent::__CONSTRUCT($id, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento) {
            $this -> password = $password;
            $this -> user = $user;
        }
    }

    public function getPassword() {
        return $this -> $password;
    }

    public function getUser() {
        return $this -> $user;
    }

    // MÉTODO SET USUARIO - CONTRASEÑA;
    //validación para el user y password (que no esté vacio, que sea string y que no sea nulo);
    //la validación de longitud, caracteres, números, minúsculas y mayúsculas en otra capa.

    public function setUser($user) {
        if (empty($user) || !is_string($user) || $user === null) {
            throw new Exception("El nombre de user es obligatorio y debe ser una cadena de texto");
        }
        $this -> user = trim($user);
    }

    public function setPassword($password) {
        if (empty($password) || !is_string($password) || $password === null) {
            throw new Exception("La password no es valida y debe ser una cadena de texto");
        }
        $this -> password = trim($password);
    }
?>