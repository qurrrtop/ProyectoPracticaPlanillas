<?php
require_once __DIR__ . "/../config/ConexionBD.php";
require_once __DIR__ . "/../DAO/UsuarioDAO.php";
require_once __DIR__ . "/../service/UsuarioService.php";
require_once __DIR__ . "/../service/validate/Validation.php";

class UsuarioController {
    private $usuarioService;
    private $usuarioDAO;

    public function __construct() {
        $conexionBD = ConexionBD::getInstancia();
        $this->usuarioDAO = new UsuarioDAO($conexionBD);
        $this->usuarioService = new UsuarioService($this->usuarioDAO);
    }

    public function perfil() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // usuario logueado (desde LoginController) con idUsuario
        if (empty($_SESSION['usuario']['idUsuario'])) {
            header("Location: index.php?controller=Login&action=login");
            exit;
        }
        $id = (int) $_SESSION['usuario']['idUsuario'];

        // CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $mensaje = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // validar CSRF
            $csrf = $_POST['csrf_token'] ?? '';
            if (!hash_equals($_SESSION['csrf_token'], $csrf)) {
                $mensaje = "Token inválido.";
            } else {
                // recoger campos del perfil
                $userName = trim($_POST['userName'] ?? '');
                $nombre = trim($_POST['nombre'] ?? '');
                $apellido = trim($_POST['apellido'] ?? '');
                $dni = trim($_POST['dni'] ?? '') ?: null;
                $email = trim($_POST['email'] ?? '') ?: null;
                $telefono = trim($_POST['telefono'] ?? '') ?: null;
                $direccion = trim($_POST['direccion'] ?? '') ?: null;
                $fnacimiento = trim($_POST['fnacimiento'] ?? '') ?: null;

                try {
                    $this->usuarioService->actualizarDatos($id, $userName, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento);
                    $mensaje = "Datos actualizados correctamente.";
                } catch (Exception $e) {
                    $mensaje = "Error al actualizar datos: " . $e->getMessage();
                }

                // Si vinieron campos de contraseña (no vacíos), intentamos cambiar
                $passwordActual = $_POST['password_actual'] ?? '';
                $passwordNuevo = $_POST['password_nuevo'] ?? '';
                $passwordNuevoConfirm = $_POST['password_nuevo_confirm'] ?? '';

                if ($passwordActual !== '' || $passwordNuevo !== '' || $passwordNuevoConfirm !== '') {
                    try {
                        $this->usuarioService->cambiarPassword($id, $passwordActual, $passwordNuevo, $passwordNuevoConfirm);
                        $mensaje .= " Contraseña cambiada correctamente.";
                    } catch (Exception $e) {
                        $mensaje .= " Error al cambiar contraseña: " . $e->getMessage();
                    }
                }
            }
        }

        // Obtener datos actualizados
        $usuarioDatos = $this->usuarioDAO->getUserById($id);

        // En la vista se usa $usuarioDatos para rellenar los inputs y $mensaje para mostrar feedback
        require __DIR__ . "/../views/perfil.php";
    }
}
