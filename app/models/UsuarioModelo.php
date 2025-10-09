<?php
    
  class UsuarioModelo extends PersonaModel {

    protected string $passwordHash;
    protected string $userName;

    
    // se coloca null los atributos por que se heredara
    public function __CONSTRUCT($idPersona = null, $nombre = null, $apellido = null, $dni = null, $email = null, $telefono = null, $direccion = null, $fnacimiento = null, $passwordHash = null, $userName = null, $rol = null) {
      parent::__construct($idPersona, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento);
      $this->userName = $userName;
      $this->passwordHash = $passwordHash;
      $this->rol = $rol;
    }

    public function getPasswordHash() {
      return $this->passwordHash;
    }

    public function getUserName() {
      return $this->userName;
    }

    // MÉTODO SET USUARIO - CONTRASEÑA;
    //validación para el user y password (que no esté vacio, que sea string y que no sea nulo);
    //la validación de longitud, caracteres, números, minúsculas y mayúsculas en otra capa.

    public function setUserName( string $userName ): void {
      if ( empty( $userName ) || $userName === null ) {
        throw new InvalidArgumentException("El nombre de user es obligatorio y debe ser una cadena de texto");
      }
      $this->userName = trim( $userName );
    }

    public function setPassword( string $plaintext ): void {
      if ( empty( $plaintext ) || $plaintext === null ) {
        throw new InvalidArgumentException("La password no es valida y debe ser una cadena de texto");
      }
      $this->passwordHash = password_hash( $plaintext, PASSWORD_DEFAULT );
    }

    public function verifyPassword( string $plaintext ): bool {
      return password_verify( $plaintext, $this->passwordHash );
    }

  }
?>