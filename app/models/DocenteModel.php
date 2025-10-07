<?php

  require_once __DIR__ . '/UsuarioModelo.php';

  class DocenteModel extends UsuarioModelo {
    
    public function __construct(
        $idUsuario = null, 
        $userName = null,
        $passwordHash = null, 
        $nombre = null, 
        $apellido = null, 
        $email = null, 
        $dni = null, 
        $telefono = null, 
        $direccion = null, 
        $fnacimiento = null, 
        $rol = null
    ) {
        parent::__construct($idUsuario, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento, $passwordHash, $userName, $rol);
    }

    public function cargarNota() {

    }

    public function editarNota() {

    }

  }
?>
<!-- asi se hace para heredar de un modelo a otro -->