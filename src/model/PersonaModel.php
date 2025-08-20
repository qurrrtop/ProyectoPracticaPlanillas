<?php

abstract class PersonaModel
{

  protected $idPersona;
  protected $nombre;
  protected $apellido;
  protected $dni;
  protected $tel;
  protected $email;

  protected $fechanac;

  public function __construct($idPersona = null, $nombre = null, $apellido = null, $dni = null, $tel = null, $email = null, $fechanac = null)
  {

    $this->idPersona = $idPersona;
    $this->nombre = $nombre;
    $this->apellido = $apellido;
    $this->dni = $dni;
    $this->tel = $tel;
    $this->email = $email;
    $this->fechanac = $fechanac;
  }

  public function getIdPersona()
  {
    return $this->idPersona;
  }

  public function getNombre()
  {
    return $this->nombre;
  }
  public function getApellido()
  {
    return $this->apellido;
  }
  public function getDni()
  {
    return $this->dni;
  }
  public function getTel()
  {
    return $this->tel;
  }
  public function getEmail()
  {
    return $this->email;
  }
  public function getFechaNac()
  {
    return $this->fechanac;
  }

  public function setNombre($nombre){
    if(empty($nombre) || !is_string($nombre) || $nombre=== null) {
      throw new Exception("el nombre de la persona es una cadena de texto y no ");
    }
    $this->nombre = trim($nombre);
  }

  public function setApellido($apellido)
  {
    $this->apellido = $apellido;
  }

  public function setDni($dni)
  {
    $this->dni = $dni;
  }

  public function setTel($tel)
  {
    $this->tel = $tel;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function setFechaNac($fechanac){
    try {
      $date = new DateTime($fechanac);
    } catch (Exception $e) {
      throw new Exception("el valor ingresado para la fecha de nacimiento no es compatible");
    }
    $now = new DateTime();
    if($date < $now) {
      throw new Exception("la fecha de vecimiento no puede ser menor que la actual");
    }

  }
}
    //no se q es
    // if (!is_string($nombre) || !empty($nombre) || $nombre === null){

    //   throw new Exception("el nombre es una cadena de texto y no puede estar vacio")
    // }effeveveveve


?>