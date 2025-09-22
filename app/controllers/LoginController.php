<?php
    require_once __DIR__."/../config/ConexionBD.php";
    require_once __DIR__."/../DAO/UsuarioDAO.php";
    require_once __DIR__."/../service/UsuarioService.php";

    class LoginController {
        private $usuarioService;

        public function __construct() {
            $conexionBD = ConexionBD::getInstancia();
            $usuarioDAO = new UsuarioDAO($conexionBD);
            $this->usuarioService = new UsuarioService($usuarioDAO);
        }

        // -------------------- INICIAR SESIÓN --------------------

        public function login() {
            $error = null;
            session_start();

            // Si no existe token aún, lo creamos
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userName = $_POST['userName'] ?? '';
                $password = $_POST['password'] ?? '';
                $csrf     = $_POST['csrf_token'] ?? '';

                // Validar CSRF
                if ($csrf !== $_SESSION['csrf_token']) {
                    die("Error de seguridad: token inválido.");
                }

                try {
                    $usuario = $this->usuarioService->login($userName, $password);

                    $_SESSION['usuario'] = [
                        'idUsuario' => $usuario->getIdUsuario(),
                        'userName'  => $usuario->getUserName(),
                        'rol'       => $usuario->getRol()
                    ];

                    if ($usuario->getRol() === 'COORDINADOR') {
                        header("Location: index.php?controller=Coordinador&action=home");
                    } else {
                        header("Location: index.php?controller=Docente&action=home");
                    }
                    exit;

                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }

            include __DIR__."/../views/login.php";
        }

        // -------------------- CERRAR SESIÓN --------------------

        public function logout() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // limpiar variables de sesión
            $_SESSION = [];

            // destruir sesión
            session_destroy();

            // redirigir al login
            header("Location: index.php?controller=Login&action=login");
            exit;
        }
    }
?>