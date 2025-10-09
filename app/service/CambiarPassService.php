<?php 

  require_once __DIR__.'/../DAO/UsuarioDAO.php';
  require_once __DIR__ . '/../models/UsuarioModelo.php';
  require_once __DIR__ . '/validate/Validation.php';

  class CambiarPassService {

    private $usuarioDAO;

    public function __construct( UsuarioDAO $usuarioDAO ) {
      $this->usuarioDAO = $usuarioDAO;
    }

    public function cambiarPassword(int $id, string $passwordActual, string $passwordNuevo, string $passwordNuevoConfirm): bool {
      if ($passwordNuevo !== $passwordNuevoConfirm) {
        throw new Exception("La nueva contraseña y su confirmación no coinciden");
      }

      $usuario = $this->usuarioDAO->getUserById($id);
      if (!$usuario) throw new Exception("Usuario no encontrado");

      if (!password_verify($passwordActual, $usuario->getPasswordHash())) {
        throw new Exception("La contraseña actual no es correcta");
      }

      $nuevoHash = password_hash($passwordNuevo, PASSWORD_BCRYPT);
      return $this->usuarioDAO->updatePassword($id, $nuevoHash);
    }

  }

?>