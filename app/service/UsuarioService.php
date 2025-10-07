<?php 
    require_once __DIR__.'/../DAO/UsuarioDAO.php';
    require_once __DIR__ . '/../models/UsuarioModelo.php';
    require_once __DIR__ . '/validate/Validation.php';

    class UsuarioService {

        private $usuarioDAO;

        public function __construct( UsuarioDAO $usuarioDAO ) {
            $this->usuarioDAO = $usuarioDAO;
        }

        // -------------------- LOGIN --------------------

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

        // -------------------- ACTUALIZAR DATOS --------------------
        public function actualizarDatos(int $id, string $userName, string $nombre, string $apellido, ?string $dni, ?string $email, ?string $telefono, ?string $direccion, ?string $fnacimiento): bool {
            // Validaciones simples
            if (!Validation::noEmpty($nombre) || !Validation::noEmpty($apellido)) {
                throw new Exception("Nombre y apellido son obligatorios");
            }

            if ($email !== null && $email !== '' && !Validation::validEmail($email)) {
                throw new Exception("Email con formato inválido");
            }

            if ($dni !== null && $dni !== '' && !Validation::validDni($dni)) {
                throw new Exception("El DNI debe tener exactamente 8 números");
            }

            if ($fnacimiento !== null && $fnacimiento !== '' && !Validation::validFecha($fnacimiento)) {
                throw new Exception("La fecha de nacimiento no es válida");
            }

            // Llama al DAO para actualizar
            return $this->usuarioDAO->updateProfile(
                $id, $userName, $nombre, $apellido, $dni, $email, $telefono, $direccion, $fnacimiento
            );
        }

        // -------------------- CAMBIAR CONTRASEÑA --------------------
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