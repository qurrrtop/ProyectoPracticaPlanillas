<?php 

  require_once __DIR__.'/../DAO/UsuarioDAO.php';
  require_once __DIR__ . '/../models/UsuarioModelo.php';
  require_once __DIR__ . '/validate/Validation.php';

  class ActualizarDatosService {

    private $usuarioDAO;

    public function __construct( UsuarioDAO $usuarioDAO ) {
      $this->usuarioDAO = $usuarioDAO;
    }

    public function actualizarDatos(int $id, string $userName, string $nombre, string $apellido, ?string $dni, ?string $email, ?string $telefono, ?string $direccion, ?string $fnacimiento): bool {
      // Validaciones simples
      if (!Validation::noEmpty($nombre) || !Validation::noEmpty($apellido)) {
        throw new Exception("Nombre y apellido son obligatorios");
      }

      if ($email !== null && $email !== '' && !Validation::validEmail($email)) {
        throw new Exception("Email con formato inválido");
      }

      if ($dni !== null && $dni !== '' && !Validation::validDni($dni)) {
        throw new Exception("El DNI debe tener exactamente 8 números");
      }

      if ($fnacimiento !== null && $fnacimiento !== '' && !Validation::validFecha($fnacimiento)) {
        throw new Exception("La fecha de nacimiento no es válida");
      }

      // Llama al DAO para actualizar
      return $this->usuarioDAO->updateProfile(
        $id, $userName, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento
      );
    }

  }

?>