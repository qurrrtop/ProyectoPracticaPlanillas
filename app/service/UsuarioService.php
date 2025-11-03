<?php 

    declare( strict_types = 1 );

    namespace app\service;

    use app\dao\UsuarioDAO;


    class UsuarioService {

        private $usuarioDAO;

        public function __construct( UsuarioDAO $usuarioDAO ) {
            $this->usuarioDAO = $usuarioDAO;
        }


        
    }


?>