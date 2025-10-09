<?php 

  require_once __DIR__.'/../DAO/UsuarioDAO.php';
  require_once __DIR__ . '/../models/UsuarioModelo.php';
  require_once __DIR__ . '/validate/Validation.php';

class LoginService {

    private $usuarioDAO;

    public function __construct( UsuarioDAO $usuarioDAO ) {
      $this->usuarioDAO = $usuarioDAO;
    }

    public function login(string $userName, string $password): UsuarioModelo {
      try {
      // 1. Validaciones
        if (!Validation::noEmpty($userName) || !Validation::noEmpty($password)) {
          throw new Exception("El usuario y la contraseña son obligatorios");
        }

        // 2. Buscar usuario
      $user = $this->usuarioDAO->findByUserName($userName);

      if (!$user) {
        throw new Exception("El usuario no existe");
      }

      // 3. Verificar contraseña
      if (!password_verify($password, $user->getPasswordHash())) {
          throw new Exception("Contraseña incorrecta");
      }

      // 4. Si todo está bien → devolver el usuario
      return $user;

      } catch (Exception $e) {
        throw $e;
      }
    }

  }

?>