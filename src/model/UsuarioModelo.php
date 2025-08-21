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

    public function setContraseña($contraseña) {

    }
?>