<?php 
    require_once __DIR__.'/../DAO/UsuarioDAO.php';
    require_once __DIR__ . '/../models/UsuarioModelo.php';
    require_once __DIR__ . '/validate/Validation.php';

    class UsuarioService {

        private $usuarioDAO;

        public function __construct( UsuarioDAO $usuarioDAO ) {
            $this->usuarioDAO = $usuarioDAO;
        }


        
    }


?>