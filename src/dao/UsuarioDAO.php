<?php
    require_once __DIR__."config" // referencia a la base de datos;
    require_once __DIR__."" // referencia al Usuario Modelo;


    class UsuarioDAO {
        private $connectionBD = null;

        public function __CONSTRUCT(ConexionDB )
        
    }

    //inyectamos la conexion
    // en la capa DAO inyectaré MODELO
    //PUBLIC FUNCTION CREATENEWCUSTOMER(CustomerModel $CUSTOMER): CustomerModel {}
    // en la capa controller inyectaré la capa SERVICIO
    //CREATE
    //READ BY DNI
    //READ BY ID
    //READ BY NOMBRE
    //para no usar tantos BindParam se hace un arreglo;
?>

