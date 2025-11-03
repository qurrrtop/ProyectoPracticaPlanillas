<?php

  declare( strict_types = 1 );

  namespace app\models;

  class CoordinadorModel extends UsuarioModelo {
    
    public function __construct(
        $idPersona = null, 
        $userName = null,
        $passwordHash = null, 
        $nombre = null, 
        $apellido = null, 
        $dni = null, 
        $email = null, 
        $telefono = null, 
        $direccion = null, 
        $fnacimiento = null, 
        $rol = null
    ) {
        parent::__construct($idPersona, $userName, $passwordHash, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento, $rol);
    }

    public function darDeAltaDocente() {

    }

    public function verPlanilla() {

    }

    public function consultarInformeGeneral() {
      
    }

  }

?>