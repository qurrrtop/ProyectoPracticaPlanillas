<?php 
    class ConnectionBD {
      
      private static $instance = null;
      private $pdo;
            
      #el constructor es privado para evitar instanciacion directa ¿?
      private function __construct() {

        #dsn tiene el tipo de BD, el nombre de la misma, el host y la categoria de caracteres utilizados (charset), no es obligatorio el charset, tampoco importa el orden
        $dsn = "mysql:dbname=nombredelaBD;host=localhost;charset=utf8mb4";
        $user = "root";
        $pass = "mysql"; #en xampp es vacio
        #user y pass varian segun la BD 

        try {
          $this->pdo = new PDO ( $dsn, $user, $pass, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
          ] );

        } catch( PDOException $e ) {
          error_log("error en la conexion a la base de datos" .$e->getMessage());
          throw $e;
        }
      }

      public static function getInstance(): DBConnection {
        if( self::$instance === null ) {
          self::$instance = new DBConnection(); #esto solo pasa en la primera conexion a la BD
        }

        return self::$instance;
      }

      public function getConnection(): PDO {
        return $this->pdo;
      }

      

    }

?>