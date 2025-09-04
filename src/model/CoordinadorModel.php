<?php

  class CoordinadorModel extends UsuarioModelo {

    public function __construct( $idUsuario = null, $passwordHash = null, $userName = null,  $nombre = null, $apellido = null, $dni = null, $email = null, $telefono = null, $direccion = null, $fnacimiento = null ) {
      
      parent ::__construct( $idUsuario, $passwordHash, $userName, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento );

    }

    public function darDeAltaDocente() {

    }

    public function verPlanilla() {

    }

    public function consultarInformeGeneral() {
      
    }

  }

?>