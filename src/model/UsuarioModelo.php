<?php
    
    class UsuarioModelo extends PersonaModelo {
        protected $contraseña;
        protected $usuario;
    }

    public function __CONSTRUCT($id = null, $nombre = null, $apellido = null, $dni = null, $email = null, $telefono = null, $direccion = null, $fnacimiento = null, $contraseña = null, $usuario = null) {

        parent::__CONSTRUCT($id, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento) {
            $this -> contraseña = $contraseña;
            $this -> usuario = $usuario;
        }
    }

    public function getContraseña() {
        return $this -> $contraseña;
    }

    public function getUsuario() {
        return $this -> $usuario;
    }

    // MÉTODO SET USUARIO - CONTRASEÑA;
    //validación para el usuario y contraseña (que no esté vacio, que sea string y que no sea nulo);
    //la validación de longitud, caracteres, números, minúsculas y mayúsculas en otra capa.

    public function setUsuario($usuario) {
        if (empty($usuario) || !is_string($usuario) || $usuario === null) {
            throw new Exception("El nombre de usuario es obligatorio y debe ser una cadena de texto");
        }
        $this -> usuario = trim($usuario);
    }

    public function setContraseña($contraseña) {
        if (empty($contraseña) || !is_string($contraseña) || $contraseña === null) {
            throw new Exception("La contraseña no es valida y debe ser una cadena de texto");
        }
        $this -> contraseña = trim($contraseña);
    }
?>