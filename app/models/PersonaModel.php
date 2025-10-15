<?php 
  //require el enum de cadenas
  require_once __DIR__."/../utilities/StringFieldType.php";

  abstract class PersonaModel {

    protected ?int $idPersona;
    protected ?string $nombre;
    protected ?string $apellido;
    protected ?int $dni;
    protected ?string $email;
    protected ?int $telefono;
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

    public function getDni(): int {
      return $this->dni;
    }

    public function getEmail(): string {
      return $this->email;
    }

    public function getTelefono(): int {
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
        throw new InvalidArgumentException( "Nombre ingresado no valido" );
      }
      //ya no es necesario el trim porq lo hace el enum
      $this->nombre = $nombre;
    }

    public function setApellido( string $apellido ): void {

      if( !StringFieldType::stringToValidate( $apellido, StringFieldType::SURNAME ) ) {
        throw new InvalidArgumentException( "Apellido ingresado no valido" );
      }
    
      $this -> apellido = $apellido;
    }
    //los enteros no se si es necesario hacer enum
    public function setDni( int $dni ): void {
      if (!is_numeric($dni) || $dni <= 0) {
        throw new InvalidArgumentException('El dni solo puede contener números cuyo valor siempre será mayor a 0');
      } 
      $this -> dni = $dni;
    }

    public function setEmail( string $email ): void {

      if( !StringFieldType::stringToValidate( $email, StringFieldType::EMAIL ) ) {
        throw new InvalidArgumentException( "Email ingresado no valido" );
      }

    }

    public function setTelefono( int $telefono ): void {
      if (filter_var($telefono, '', FILTER_VALIDATE_INT) !== false) {
        throw new InvalidArgumentException("El número de teléfono ingresado no es valido");
      }
      $this -> telefono = trim($telefono);
    }

    public function setDireccion( string $direccion ) {

      if ( !StringFieldType::stringToValidate( $direccion, StringFieldType::ADDRESS ) ) {
        throw new InvalidArgumentException( "Dirección ingresada no valida" );
      }

    }

    public function setFNacimiento( string $fecha ) {
        $fecha = trim( $fecha );

        //valida el formato dia-mes-año
        $date = DateTime::createFromFormat( "d-m-Y", $fecha );
        if ( !$date || $date->format( 'd-m-Y' ) !== $fecha ) {
            throw new InvalidArgumentException( "Fecha de nacimiento ingresada con formato invalido (DD-MM-YYYY)" );
        }

        //no puede ser futura
        $today = new DateTime();
        if ( $date > $today ) {
            throw new InvalidArgumentException( "Fecha de nacimiento no puede ser futura" );
        }

        //se guarda en formato año-mes-dia por si acaso
        $this->fnacimiento = $date->format('Y-m-d');
    }

  }

?>