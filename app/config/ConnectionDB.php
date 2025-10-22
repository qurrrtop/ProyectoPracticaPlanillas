<?php 

    declare( strict_types = 1 );

    namespace app\config;
    use PDO;
    use PDOException;

    class ConnectionDB {
        // Atributo que guarda la instancia única del singleton;
        private static $instancia = null;

        // Atributo que guarda la conexion PDO;
        private $connection;
    
        // Constructor privado para evitar que se creen instancias fuera de la clase;
        public function __construct() {
            $dsn = "mysql:dbname=appwebplanilla;host=localhost;charset=utf8mb4";
            $user = "root";
            $pass = "";

            try {
                $this ->connection = new PDO(
                    $dsn, 
                    $user, 
                    $pass,
                    [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
                );
                // Manejo de errores: lanza excepciones en caso de fallos;
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                error_log("error en la conexion a la base de datos ". $e->getMessage());
                die("No se pudo conectar a la base de datos.");
            }
        }

        // Método estático que devuelve la única instancia de la clase;
        public static function getInstancia(): ConnectionDB {
            if (self::$instancia === null) {
                self::$instancia = new ConnectionDB();
            }
            return self::$instancia;
        }

        
        // Método público para obtener el objeto PDO y ejecutar consultas;
        public function getConnection(): PDO {
            return $this->connection;
        }
    }
?>