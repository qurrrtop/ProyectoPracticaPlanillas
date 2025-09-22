<?php
  require_once __DIR__ . '/UsuarioModelo.php';


  class CoordinadorModel extends UsuarioModelo {
    
    public function __construct(
        $idUsuario = null, 
        $nombre = null, 
        $apellido = null, 
        $dni = null, 
        $email = null, 
        $telefono = null, 
        $direccion = null, 
        $fnacimiento = null, 
        $passwordHash = null, 
        $userName = null,
        $rol = null
    ) {
        parent::__construct($idUsuario, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento, $passwordHash, $userName, $rol);
    }

    public function darDeAltaDocente() {

    }

    public function verPlanilla() {

    }

    public function consultarInformeGeneral() {
      
    }

  }

?>