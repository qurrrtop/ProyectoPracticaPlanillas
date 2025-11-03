<?php 

  declare( strict_types = 1 );

  namespace app\service;

  use app\dao\UsuarioDAO;
  use app\utilities\DateTimeValidator;
  use app\utilities\StringFieldType;
  use app\utilities\IntFieldType;
  use InvalidArgumentException;

  class ActualizarDatosService {

    private $usuarioDAO;

    public function __construct( UsuarioDAO $usuarioDAO ) {
      $this->usuarioDAO = $usuarioDAO;
    }

    public function actualizarDatos( int $id, string $userName, string $nombre, string $apellido, ?string $dni, ?string $email, ?string $telefono, ?string $direccion, ?string $fnacimiento): bool {
      if (!StringFieldType::stringToValidate( $nombre, StringFieldType::NAME ) || !StringFieldType::stringToValidate( $apellido, StringFieldType::SURNAME ) ) {
        throw new InvalidArgumentException("Nombre y apellido son obligatorios");
      }

      if ( !StringFieldType::stringToValidate( $email, StringFieldType::EMAIL ) ) {
        throw new InvalidArgumentException("Email con formato inválido");
      }

      if ( !IntFieldType::intToValidate( ( int ) $dni, IntFieldType::DNI ) ) {
        throw new InvalidArgumentException("El DNI debe tener exactamente 8 números");
      }

      if ( !DateTimeValidator::ValidateFNacimiento( $fnacimiento ) ) {
        throw new InvalidArgumentException("La fecha de nacimiento no es válida");
      }

      // Llama al DAO para actualizar
      return $this->usuarioDAO->updateProfile(
        $id, $userName, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento
      );
    }

  }

?>