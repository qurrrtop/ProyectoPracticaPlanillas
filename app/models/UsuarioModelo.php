<?php

  require_once __DIR__ . "/PersonaModel.php";
    
  class UsuarioModelo extends PersonaModel {

    private string $passwordHash;
    private string $userName;
    private ?string $rol;

    
    // se coloca null los atributos por que se heredara
    public function __CONSTRUCT($idPersona = null, $nombre = null, $apellido = null, $dni = null, $email = null, $telefono = null, $direccion = null, $fnacimiento = null, $passwordHash = null, $userName = null, $rol = null) {
      parent::__construct($idPersona, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento);
      $this->userName = $userName;
      $this->passwordHash = $passwordHash;
      $this->rol = $rol;
    }

    public function getPasswordHash(): string {
      return $this->passwordHash;
    }

    public function getUserName(): string {
      return $this->userName;
    }

    public function getRol(): string {
      return $this->rol;
    }

    public function setUserName( string $username ): void {

      if( !StringFieldType::stringToValidate( $username, StringFieldType::USER_NAME ) ) {
        throw new InvalidArgumentException( "Nombre de suario ingresado no valido" );
      }

      $this->userName = $username;
    }

    public function setPassword( string $plaintext ): void {

      if( !StringFieldType::stringToValidate( $plaintext, StringFieldType::PASSWORD ) ) {
        throw new InvalidArgumentException( "Contraseña ingresada no valida" );
      }

      $this->passwordHash = password_hash( $plaintext, PASSWORD_DEFAULT );
    }

    public function setRol( string $rol ): void {

      if( !StringFieldType::stringToValidate( $rol, StringFieldType::ROL ) ) {
        throw new InvalidArgumentException( "Rol ingresado no valido" );
      }

      $this->rol = $rol;
    }

    public function verifyPassword( string $plaintext ): bool {
      return password_verify( $plaintext, $this->passwordHash );
    }

  }
?>