<?php 

  require_once __DIR__.'/../DAO/UsuarioDAO.php';
  require_once __DIR__ . '/../models/UsuarioModelo.php';
  require_once __DIR__ . '/validate/Validation.php';

  class CreateUserService {

    private $usuarioDAO;

    public function __construct( UsuarioDAO $usuarioDAO ) {
      $this->usuarioDAO = $usuarioDAO;
    }

    public function createUser(string $userName, string $password, string $nombre, string $email) {
      try {
        if (!Validation::validEmail($email)) {
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
          null,
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