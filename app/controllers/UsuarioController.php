<?php
require_once __DIR__ . "/../config/ConenctionDB.php";
require_once __DIR__ . "/../DAO/UsuarioDAO.php";
require_once __DIR__ . "/../service/ActualizarDatosService.php";
require_once __DIR__ . "/../service/CambiarPassService.php";
require_once __DIR__ . "/../service/validate/Validation.php";

class UsuarioController {
    private $actualizarDatosService;
    private $cambiarPassService;
    private $usuarioDAO;

    public function __construct() {
        $ConnectionDB = ConnectionDB::getInstancia();
        $this->usuarioDAO = new UsuarioDAO($ConnectionDB);
        $this->actualizarDatosService = new ActualizarDatosService($this->usuarioDAO);
        $this->cambiarPassService = new CambiarPassService($this->usuarioDAO);
    }

    // ---- Método que le permite actualizar sus datos personales ----
    // ---- a cualquier usuario que necesite modificarlo ----

    public function perfil() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // usuario logueado (desde LoginController) con idPersona
        if (empty($_SESSION['usuario']['idPersona'])) {
            header("Location: index.php?controller=Login&action=login");
            exit;
        }
        $id = (int) $_SESSION['usuario']['idPersona'];

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
                    $this->actualizarDatosService->actualizarDatos($id, $userName, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento);
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
                        $this->cambiarPassService->cambiarPassword($id, $passwordActual, $passwordNuevo, $passwordNuevoConfirm);
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
