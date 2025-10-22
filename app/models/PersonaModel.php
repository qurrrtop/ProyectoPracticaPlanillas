<?php 

  declare( strict_types = 1 );

  namespace app\models;

  use app\utilities\DateTimeValidator;
  use app\utilities\IntFieldType;
  use app\utilities\StringFieldType;
  use InvalidArgumentException;


  abstract class PersonaModel {

    protected ?int $idPersona;
    protected ?string $nombre;
    protected ?string $apellido;
    protected ?string $dni;
    protected ?string $email;
    protected ?string $telefono;
    protected ?string $direccion;
    protected ?string $fnacimiento;

    public function __construct($idPersona = null, $nombre = null, $apellido = null, $dni = null, $email = null, $telefono = null, $direccion = null, $fnacimiento = null, $rol = null) {
      
      $this -> idPersona = $idPersona;
      $this -> nombre = $nombre;
      $this -> apellido = $apellido;
      $this -> dni = $dni;
      $this -> email = $email;
      $this -> telefono = $telefono;
      $this -> direccion = $direccion;        
      $this -> fnacimiento = $fnacimiento;

    }



    public function getIdPersona(): int {
      return $this->idPersona;
    }

    public function getNombre(): string {
      return $this->nombre;
    }

    public function getApellido(): string {
      return $this->apellido;
    }

    public function getDni(): string {
      return $this->dni;
    }

    public function getEmail(): string {
      return $this->email;
    }

    public function getTelefono(): string {
      return $this->telefono;
    }

    public function getDireccion(): string {
      return $this->direccion;
    }
    //lo cambie ya que se guarda como string, si lo guardamos como datetime lo cambiamos
    public function getFnacimiento(): string {
      return $this->fnacimiento;
    }

    public function setNombre( string $nombre ): void {

      if( !StringFieldType::stringToValidate( $nombre, StringFieldType::NAME ) ) {
        throw new InvalidArgumentException( "Nombre ingresado no valido." );
      }
      $this->nombre = $nombre;
    }

    public function setApellido( string $apellido ): void {

      if( !StringFieldType::stringToValidate( $apellido, StringFieldType::SURNAME ) ) {
        throw new InvalidArgumentException( "Apellido ingresado no valido." );
      }
    
      $this -> apellido = $apellido;
    }
    //los enteros no se si es necesario hacer enum
    public function setDni( int $dni ): void {

      if( !IntFieldType::intToValidate( $dni, IntFieldType::DNI ) ) {
        throw new InvalidArgumentException( "DNI ingresado no valido." );
      }
    }

    public function setEmail( string $email ): void {

      if( !StringFieldType::stringToValidate( $email, StringFieldType::EMAIL ) ) {
        throw new InvalidArgumentException( "Email ingresado no valido." );
      }

    }

    public function setTelefono( int $telefono ): void {

      if( !IntFieldType::intToValidate( $telefono, IntFieldType::PHONE ) ) {
        throw new InvalidArgumentException( "Telefono ingresado no valido." );
      }

    }

    public function setDireccion( string $direccion ) {

      if ( !StringFieldType::stringToValidate( $direccion, StringFieldType::ADDRESS ) ) {
        throw new InvalidArgumentException( "Dirección ingresada no valida." );
      }

    }

    public function setFNacimiento( string $fecha ) {

      $this->fnacimiento = DateTimeValidator::ValidateFNacimiento( $fecha );

    }

  }

?>