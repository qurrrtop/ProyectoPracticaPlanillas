<?php 

    declare( strict_types = 1 );

    namespace app\service;

    use app\dao\UsuarioDAO;
    use Exception;

  class CambiarPassService {

    private $usuarioDAO;

    public function __construct( UsuarioDAO $usuarioDAO ) {
      $this->usuarioDAO = $usuarioDAO;
    }

    public function cambiarPassword(int $id, string $passwordActual, string $passwordNuevo, string $passwordNuevoConfirm): bool {
        // validaciones básicas
        if (empty($passwordActual) || empty($passwordNuevo) || empty($passwordNuevoConfirm)) {
            throw new Exception("Todos los campos de contraseña son obligatorios.");
        }

        if ($passwordNuevo !== $passwordNuevoConfirm) {
            throw new Exception("La nueva contraseña y su confirmación no coinciden.");
        }

        // pequeña validación de longitud
        if (strlen($passwordNuevo) < 8) {
            throw new Exception("La nueva contraseña debe tener al menos 8 caracteres.");
        }

        // busca el usuario
        $usuario = $this->usuarioDAO->getUserById($id);
        if (!$usuario) {
            throw new Exception("Usuario no encontrado.");
        }

        // verifica la contraseña actual
        if (!password_verify($passwordActual, $usuario->getPasswordHash())) {
            throw new Exception("La contraseña actual no es correcta.");
        }

        // verifica que la nueva contraseña no sea igual a la anterior
        if (password_verify($passwordNuevo, $usuario->getPasswordHash())) {
            throw new Exception("La nueva contraseña no puede ser igual a la actual.");
        }

        // se hashea la nueva contraseña y se guarda
        $nuevoHash = password_hash($passwordNuevo, PASSWORD_BCRYPT);
        $actualizado = $this->usuarioDAO->updatePassword($id, $nuevoHash);

        if (!$actualizado) {
            throw new Exception("No se pudo actualizar la contraseña en la base de datos.");
        }

        return true;
    }

  }

?>