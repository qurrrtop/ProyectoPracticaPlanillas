<?php 

  declare( strict_types = 1 );

  namespace app\service;

  use app\dao\UsuarioDAO;
  use app\models\DocenteModel;
  use app\utilities\StringFieldType;
  use Exception;

  class CreateUserService {

    private $usuarioDAO;

    public function __construct( UsuarioDAO $usuarioDAO ) {
      $this->usuarioDAO = $usuarioDAO;
    }

    public function createUser(string $userName, string $password, string $nombre, string $apellido, string $email) {
      try {
        if ( !StringFieldType::stringToValidate( $email, StringFieldType::EMAIL ) ) {
          throw new Exception("El email es invalido.");
        }

        if ($this->usuarioDAO->existsUserName($userName)) {
          throw new Exception("El nombre de usuario '$userName' ya está en uso.");
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $usuario = new DocenteModel(
          null,
          $userName,
          $passwordHash,
          $nombre,
          $apellido,
          $email,
          null,
          null,
          null,
          null,
          'DOCENTE'
        );

        $nuevoUsuario = $this->usuarioDAO->createANewUser($usuario);
        return $nuevoUsuario;
      
      } catch(Exception $e) {
        error_log("Error al crear usuario: " . $e->getMessage());
        throw $e;
      }
    }

  }

?>