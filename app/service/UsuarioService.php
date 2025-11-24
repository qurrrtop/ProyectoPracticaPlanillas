<?php 

    declare( strict_types = 1 );

    namespace app\service;

    use app\dao\UsuarioDAO;
    use Exception;

    class UsuarioService {

        private $usuarioDAO;

        public function __construct( UsuarioDAO $usuarioDAO ) {
            $this->usuarioDAO = $usuarioDAO;
        }
        
        public function getAllUsers(): array {
            try {

                $usuarios = $this->usuarioDAO->readAllUser();

                if (empty($usuarios)) {
                    return [];
                }

                return $usuarios;

            } catch (Exception $e) {
                error_log("Error en getAllUsers: " . $e->getMessage());
                throw $e;
            }
        }
    }


?>