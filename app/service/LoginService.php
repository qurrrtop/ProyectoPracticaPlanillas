<?php 

  declare( strict_types = 1 );

  namespace app\service;

  use app\dao\UsuarioDAO;
  use app\models\UsuarioModelo;
  use app\utilities\StringFieldType;
  use InvalidArgumentException;
  use Exception;

  class LoginService {

    private $usuarioDAO;

    public function __construct( UsuarioDAO $usuarioDAO ) {
      $this->usuarioDAO = $usuarioDAO;
    }

    public function login(string $userName, string $password): UsuarioModelo {
      try {
      // 1. Validaciones
        if (!StringFieldType::stringToValidate( $userName, StringFieldType::USER_NAME ) || !StringFieldType::stringToValidate( $password, StringFieldType::PASSWORD ) ) {
          throw new InvalidArgumentException("El usuario y la contraseña son obligatorios");
        }

        // 2. Buscar usuario
      $user = $this->usuarioDAO->findByUserName($userName);

      if (!$user) {
        throw new InvalidArgumentException("El usuario no existe");
      }

      // 3. Verificar contraseña
      if (!password_verify($password, $user->getPasswordHash())) {
          throw new InvalidArgumentException("Contraseña incorrecta");
      }

      // 4. Si todo está bien → devolver el usuario
      return $user;

      } catch (Exception $e) {
        throw $e;
      }
    }

  }

?>