<?php
    require_once __DIR__ . "/../config/ConenctionDB.php"; // referencia a la base de datos;
    require_once __DIR__ . "/../models/UsuarioModelo.php"; // referencia al Usuario Modelo;
    require_once __DIR__ . "/../models/DocenteModel.php"; // referencia al Usuario Modelo;
    require_once __DIR__ . "/../models/CoordinadorModel.php"; // referencia al Usuario Modelo;


    class UsuarioDAO {
        private $connectionDB = null;

        const TBL_NAME = 'usuarios';

        public function __CONSTRUCT(ConnectionDB $connectionDB){
            $this->connectionDB = $connectionDB;
        }

        // ------------------------- CREATE NEW USER -------------------------

        public function createANewUser(UsuarioModelo $user): UsuarioModelo {
            $sql = "INSERT INTO " . self::TBL_NAME . " 
                    (userName, passwordHash, nombre, email, rol)
                    VALUES (:userName, :passwordHash, :nombre, :email, :rol)";

            try {
                $conn = $this->connectionBD->getConexion();
                $stmt = $conn->prepare($sql);

                $stmt->execute([
                    ':userName'     => $user->getUserName(),
                    ':passwordHash' => $user->getPasswordHash(),
                    ':nombre'       => $user->getNombre(),
                    ':email'        => $user->getEmail(),
                    ':rol'          => 'DOCENTE'
                ]);

                $newID = $conn->lastInsertId();
                return $this->readUserById($newID);

            } catch (PDOException $e) {
                error_log("Error al insertar usuario: " . $e->getMessage());
                die("Error real al insertar usuario: " . $e->getMessage());
                throw new Exception("No se pudo crear el usuario");
            }
        }

        // ------------------------- READ USER BY DNI -------------------------

        public function readUserByDni(int $dni): ?UsuarioModelo {
            $sql = "SELECT * FROM " . self::TBL_NAME . " WHERE dni = :dni LIMIT 1";

            try {
                $conn = $this->connectionBD->getConexion();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':dni', $dni, PDO::PARAM_INT);
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row) {
                    return null; // no encontró usuario
                }

                // devolver según el rol.
                if ($row['rol'] === 'DOCENTE') {
                    return new DocenteModel(
                        $row['idUsuario'],
                        $row['nombre'],
                        $row['apellido'],
                        $row['dni'],
                        $row['email'],
                        $row['telefono'],
                        $row['direccion'],
                        $row['fnacimiento'],
                        $row['passwordHash'],
                        $row['userName'],
                        $row['rol']
                    );
                } elseif ($row['rol'] === 'COORDINADOR') {
                    return new CoordinadorModel(
                        $row['idUsuario'],
                        $row['nombre'],
                        $row['apellido'],
                        $row['dni'],
                        $row['email'],
                        $row['telefono'],
                        $row['direccion'],
                        $row['fnacimiento'],
                        $row['passwordHash'],
                        $row['userName'],
                        $row['rol']
                    );
                } else {
                    return new UsuarioModelo(
                        $row['idUsuario'],
                        $row['nombre'],
                        $row['apellido'],
                        $row['dni'],
                        $row['email'],
                        $row['telefono'],
                        $row['direccion'],
                        $row['fnacimiento'],
                        $row['passwordHash'],
                        $row['userName'],
                        $row['rol']
                    );
                }

            } catch (PDOException $e) {
                error_log("Error DB al buscar usuario por DNI: " . $e->getMessage());
                throw new Exception("No se pudo buscar usuario por DNI");
            } catch (Exception $e) {
                error_log("Error general al buscar usuario por DNI: " . $e->getMessage());
                throw $e;
            }
        }

        // ------------------------- READ USER BY ID -------------------------

        public function readUserById(int $id): ?UsuarioModelo {
            $sql = "SELECT idUsuario, userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento, rol FROM ".self::TBL_NAME." WHERE idUsuario = :idUsuario LIMIT 1";

            try {
                $conn = $this->connectionBD->getConexion();
                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':idUsuario', $id, PDO::PARAM_INT);
                $stmt->execute();

                $queryResult = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$queryResult) {
                    throw new Exception("No existe ningún usuario con el ID ingresado");
                }

                if ($queryResult['rol'] === 'DOCENTE') {
                    return new DocenteModel(
                        $queryResult['idUsuario'],
                        $queryResult['userName'],
                        $queryResult['passwordHash'],
                        $queryResult['nombre'],
                        $queryResult['apellido'],
                        $queryResult['dni'],
                        $queryResult['email'],
                        $queryResult['telefono'],
                        $queryResult['direccion'],
                        $queryResult['fnacimiento'],
                        $queryResult['rol']
                    );
                }

            } catch(PDOException $e) {
                error_log("No existe un usuario en la base de datos con el ID deseado");
                throw new Exception("No existe el usuario en la base de datos con ese ID");
            } catch (Exception $e) {
                error_log("No se encontró el usuario");
                throw $e;
            }
        }
        
        // ------------------------- READ ALL USER -------------------------
    
        public function readAllUser(): array {
            $sql = "SELECT idUsuario, userName, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME." ORDER BY apellido";
    
            try {
                $conn = $this->connectionBD->getConexion();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
    
    
                $queryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                $allUsers = [];
                 
                foreach($queryResult as $row) {
                    $allUsers[] = new UsuarioModelo(
                        $row['idUsuario'],
                        $row['userName'],
                        $row['nombre'],
                        $row['apellido'],
                        $row['dni'],
                        $row['email'],
                        $row['telefono'],
                        $row['direccion'],
                        $row['fnacimiento']
                    );
                }
    
                return $allUsers;
    
            } catch(PDOException $e) {
                error_log("Error al intentar listar todos los usuarios de la tabla".$e->getMessage());
                throw new Exception("Error al listar los datos de todos los usuarios");
            } catch (Exception $e) {
                error_log("Error al listar los usuarios");
                throw $e;
            }
        }
    
        // ------------------------- UPDATE USER -------------------------
    
        public function updateUser(UsuarioModelo $user): UsuarioModelo {
            $sql = "UPDATE ".self::TBL_NAME." SET user = :user, passwordHash = :passwordHash WHERE id = :id";
    
            $userData = [
                ':user' => $user->getUser(),
                ':passwordHash' => $user->getPassword(),
                ':id' => $user->getId()
            ];
    
            try {
                $conn = $this ->$connectionBD->getConexion();
                $stmt = $conn->prepare($sql);
                $stmt->execute($userData);
    
                // este tipo de validación para saber si se realizó o no la modificación se podría hacer en la capa 'SERVICE';

                if ($stmt-> rowCount() === 0) {
                    throw new Exception("No pudo ser posible la edición del usuario");
                }
                
                return $this->readUserById($user->getId());

            } catch (PDOException $e) {
                throw new Exception("Error al intentar modificar datos del usuario por su ID".$e->getMessage());
            } catch (Exception $e) {
                throw $e;
            }
        }
    
        // ------------------------- DELETE USER -------------------------
    
        public function deleteUser(int $id): bool {
            $sql = "DELETE FROM ".self::TBL_USER." WHERE id = :id";
    
            try {
                $conn = $this->connectionBD->getConexion();
                $stmt = $conn->prepare($sql);
                $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
    
    
                return $stmt->rowCount() > 0;
    
            } catch (PDOException $e) {
                throw new Exception("No se pudo borrar el registro de la tabla".$e->getMessage());
    
            } catch (Exception $e) {
                throw $e;
            }
        }

        // ------------------------- FIND BY USER NAME -------------------------

        public function findByUserName(string $userName): ?UsuarioModelo {
            $sql = "SELECT * FROM ".self::TBL_NAME." WHERE userName = :userName LIMIT 1";
        
            try {
                $conn = $this->connectionBD->getConexion();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
                $stmt->execute();

                $queryResult = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$queryResult) {
                    throw new Exception("No existe ningún usuario con el usuario ingresado");
                }

                if ($queryResult['rol'] === 'DOCENTE') {
                    return new DocenteModel(
                        $queryResult['idUsuario'],   // idUsuario
                        $queryResult['nombre'],      // nombre
                        $queryResult['apellido'],    // apellido
                        null,                        // dni
                        null,                        // email
                        null,                        // telefono
                        null,                        // direccion
                        null,                        // fnacimiento
                        $queryResult['passwordHash'],// passwordHash
                        $queryResult['userName'],     // userName
                        $queryResult['rol']          // Rol
                    );

                } elseif ($queryResult['rol'] === 'COORDINADOR') {
                    return new CoordinadorModel(
                        $queryResult['idUsuario'],   // idUsuario
                        $queryResult['nombre'],      // nombre
                        $queryResult['apellido'],    // apellido
                        null,                        // dni
                        null,                        // email
                        null,                        // telefono
                        null,                        // direccion
                        null,                        // fnacimiento
                        $queryResult['passwordHash'],// passwordHash
                        $queryResult['userName'],    // userName
                        $queryResult['rol']          // Rol
                    );
                } else {
                    throw new Exception("Rol de usuario desconocido");
                }

            } catch (PDOException $e) {
                throw new Exception("Error en la base de datos".$e->getMessage());
            } catch (Exception $e) {
                throw $e;
            }

        }

        // ------------------------- GET USER BY ID -------------------------

        public function getUserById(int $id): ?UsuarioModelo {
            $sql = "SELECT idUsuario, userName, passwordHash, nombre, apellido, dni, email, 
            telefono, direccion, fnacimiento, rol FROM " . self::TBL_NAME . " WHERE idUsuario = :id LIMIT 1";

            try {
                $conn = $this->connectionBD->getConexion();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row) {
                    return null;
                }

                if ($row['rol'] === 'DOCENTE') {
                    return new DocenteModel(
                        $row['idUsuario'],
                        $row['userName'],
                        $row['passwordHash'],
                        $row['nombre'],
                        $row['apellido'],
                        $row['dni'],
                        $row['email'],
                        $row['telefono'],
                        $row['direccion'],
                        $row['fnacimiento'],
                        $row['rol']
                    );
                } elseif ($row['rol'] === 'COORDINADOR') {
                    return new CoordinadorModel(
                        $row['idUsuario'],
                        $row['userName'],
                        $row['passwordHash'],
                        $row['nombre'],
                        $row['apellido'],
                        $row['dni'],
                        $row['email'],
                        $row['telefono'],
                        $row['direccion'],
                        $row['fnacimiento'],
                        $row['rol']
                    );
                }

                } catch (PDOException $e) {
                throw new Exception("Error DB: " . $e->getMessage());
            }
        }

        // ------------------------- UPDATE PROFILE -------------------------


        public function updateProfile(int $id, string $userName, string $nombre, string $apellido, ?string $dni, ?string $email, ?string $telefono, ?string $direccion, ?string $fnacimiento): bool {
            $sql = "UPDATE " . self::TBL_NAME . " SET userName = :userName, nombre = :nombre, apellido = :apellido, dni = :dni, email = :email, telefono = :telefono, direccion = :direccion, fnacimiento = :fnacimiento WHERE idUsuario = :id";
            try {
                $conn = $this->connectionBD->getConexion();
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':userName' => $userName,
                    ':nombre' => $nombre,
                    ':apellido' => $apellido,
                    ':dni' => $dni ?: null,
                    ':email' => $email ?: null,
                    ':telefono' => $telefono ?: null,
                    ':direccion' => $direccion ?: null,
                    ':fnacimiento' => $fnacimiento ?: null,
                    ':id' => $id
                ]);
                return $stmt->rowCount() >= 0; // true si se ejecutó
            } catch (PDOException $e) {
                throw new Exception("Error al actualizar perfil: " . $e->getMessage());
            }
        }

        // ------------------------- UPDATE PASSWORD -------------------------

        // updatePassword: guarda el nuevo hash
        public function updatePassword(int $id, string $newHash): bool {
            $sql = "UPDATE " . self::TBL_NAME . " SET passwordHash = :passwordHash WHERE idUsuario = :id";
            try {
                $conn = $this->connectionBD->getConexion();
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':passwordHash' => $newHash,
                    ':id' => $id
                ]);
                return $stmt->rowCount() > 0;
            } catch (PDOException $e) {
                throw new Exception("Error al actualizar contraseña: " . $e->getMessage());
            }
        }

        public function existsUserName(string $userName): bool {
            $sql = "SELECT COUNT(*) FROM " . self::TBL_NAME . " WHERE userName = :userName";
            $stmt = $this->connectionBD->getConexion()->prepare($sql);
            $stmt->execute([':userName' => $userName]);
            return $stmt->fetchColumn() > 0;
        }

        public function getLastInsertId(): int {
            return $this->connectionBD->getConexion()->lastInsertId();
        }
    }
?>

