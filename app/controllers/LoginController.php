<?php

    declare( strict_types = 1 );

    namespace app\controllers;

    use app\config\ConnectionDB;
    use app\dao\UsuarioDAO;
    use app\service\LoginService;
    use Exception;


    class LoginController {
        private $LoginService;

        public function __construct() {
            $conexionBD = ConnectionDB::getInstancia();
            $usuarioDAO = new UsuarioDAO($conexionBD);
            $this->LoginService = new LoginService($usuarioDAO);
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
                    $usuario = $this->LoginService->login($userName, $password);

                    $_SESSION['usuario'] = [
                        'idPersona' => $usuario->getIdPersona(),
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