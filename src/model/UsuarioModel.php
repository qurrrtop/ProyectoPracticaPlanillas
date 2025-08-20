<?php

  class UsuarioModel {

    protected $idUsuario;
    protected $nombre;
    protected $apellido;
    protected $dni;
    protected $tel;
    protected $email;
    protected $user;
    protected $pass;
    protected $fechanac;

    public function __construct($nombre = null, $apellido = null, $dni = null, $tel = null, $email = null, $user, $pass, $fechanac = null) {

      $this->nombre = $nombre;
      $this->apellido = $apellido;
      $this->dni = $dni;
      $this->tel = $tel;
      $this->email = $email;
      $this->user = $user;
      $this->pass = $pass;
      $this->fechanac = $fechanac;
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
    public function getTel() {
      return $this->tel;
    }
    public function getEmail() {
      return $this->email;
    }
    public function getUser() {
      return $this->user;
    }
    public function getPass() {
      return $this->pass;
    }

    public function getFechaNac() {
    return $this->fechanac;
    }

    public function setNombre($nombre) {
      $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
      $this->apellido = $apellido;
    }

    public function setDni($dni) {
      $this->dni = $dni;
    }

    public function setTel($tel) {
      $this->tel = $tel;
    }

    public function setEmail($email) {
      $this->email = $email;
    }

    public function setUser($user) {
      $this->user = $user;
    }

    public function setPass($pass) {
      $this->pass = $pass;
    }

    public function setFechaNac($fechanac) {
      $this->fechanac = $fechanac;
    }

    //no se q es
    // if (!is_string($nombre) || !empty($nombre) || $nombre === null){

    //   throw new Exception("el nombre es una cadena de texto y no puede estar vacio")
    // }effeveveveve

  }

?>