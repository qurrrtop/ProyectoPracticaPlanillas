<?php
  require_once __DIR__."/../model/UsuarioModelo.php";
  require_once __DIR__."/../config/ConnectionBD.php";


    class UsuarioDAO {
      private $connectionBD = null;

      const TBL_NAME = "user";

      public function __CONSTRUCT( ConnectionBD $connectionBD ) {
        $this->connectionBD = $connectionBD;
      }

      public function createANewUser( UsuarioModelo $usuario): UsuarioModelo {
        $sql = "INSER INTO ".self::TBL_NAME."(nombre, apellido, dni, email, telefono, direccion, fnacimiento) VALUES (:nombre, :apellido, :dni, :email, :telefono, :direccion, :fnacimiento)";
        
        $userData = [
          ":nombre" => $usuario->getNombre(),
          ":apellido" => $usuario->getApellido(),
          ":dni" => $usuario->getDni(),
          ":email" => $usuario->getEmail(),
          ":telefono" => $usuario->getTelefono(),
          ":direccion" => $usuario->getDireccion(),
          ":fnacimiento" => $usuario->getFnacimiento()
        ];

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $usuario = $stmt->execute( $userData );
          $newID = $conn->lasInsertId();
         
          #falta metodo que retorna objetos (userdata)

        } catch(PDOException $e) {

          error_log("error al intentar agregar a la BD a un nuevo usuario".$e->getMessage());
          throw new Exception("error al intentar agregar a la BD a un nuevo usuario");
          
        } catch(Exception $e) {

          error_log("error al intentar guardar un nuevo usuario".$e->getMessage());
          throw $e; #cualquier problema que haya ocurrido se transfiere a la variable "e" y con throw lo muestra
        }

      }
      
      public function readAUserByDNI( int $dni ): ?UsuarioModelo #el ? es si no lo encuentra devuelve una exception
      { 

        $sql = "SELECT idPerson, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME. "WHERE dni = :dni";

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam( ":dni", $dni, PDO::PARAM_INT );
          $stmt->execute();

          $usuario = $stmt->fecth( PDO::FETCH_ASSOC );

          if( !$queryResult ) {
            throw new Exception("porque te tatuatis");
          }

          return new UsuarioModelo(
            $queryResult["idPersona"], 
            $queryResult["nombre"],
            $queryResult["apellido"],
            $queryResult["dni"],
            $queryResult["email"],
            $queryResult["telefono"],
            $queryResult["direccion"],
            $queryResult["fnacimiento"]
          );

        } catch( PDOException $e) {
          error_log("error al buscar tal usuario por su dni en la BD".$e->getMessage());
          throw new Exception("no se encontro a ningun usuario en la BD con el dni suministrado");
        } catch( Exception $e) {
          error_log("error al buscar por dni al usuario");
          throw $e;
        }

      }

      public function readAUserByID( int $idPersona ): ?UsuarioModelo {
        $sql = "SELECT idPersona, nombre, apellido, dni, telefono, email, direccion, fnacimiento"
      }

    }

    //inyectamos la conexion
    // en la capa DAO inyectaré MODELO
    //PUBLIC FUNCTION CREATENEWCUSTOMER(CustomerModel $CUSTOMER): CustomerModel {}
    // en la capa controller inyectaré la capa SERVICIO
    //CREATE
    //READ BY DNI
    //READ BY ID
    //READ BY NOMBRE
    //para no usar tantos BindParam se hace un arreglo | en el create pa;
?>

