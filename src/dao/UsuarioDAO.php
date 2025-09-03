<?php
  require_once __DIR__."/../model/UsuarioModelo.php";
  require_once __DIR__."/../config/ConnectionBD.php";


    class UsuarioDAO {
      private $connectionBD = null;

      const TBL_NAME = "user"; #nombre de la tabla en la BD user es pa ponerle alguno, dsp vemos

      // ------------- CONSTRUCTOR CON INYECCIÓN DE DEPENDENCIAS --------------

      public function __CONSTRUCT( ConnectionBD $connectionBD ) {
        $this->connectionBD = $connectionBD;
      }

      // ------------------------- CREATE A NEW USER -------------------------

      public function createANewUser( UsuarioModelo $usuario): UsuarioModelo {
        $sql = "INSERT INTO ".self::TBL_NAME." (userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento) VALUES (:userName, :passwordHash, :nombre, :apellido, :dni, :email, :telefono, :direccion, :fnacimiento)";
        
        $userData = [
          ":userName" => $usuario->getUserName(),
          ":passwordHash" => $usuario->getPasswordHash(),
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
          $newID = $conn->lastInsertId();
         
          return $this->readAUserByID( $newID );

        } catch(PDOException $e) {

          error_log("error al intentar agregar a la BD a un nuevo usuario".$e->getMessage());
          throw new Exception("error al intentar agregar a la BD a un nuevo usuario");
          
        } catch(Exception $e) {

          error_log("error al intentar guardar un nuevo usuario".$e->getMessage());
          throw $e; #cualquier problema que haya ocurrido se transfiere a la variable "e" y con throw lo muestra
        }

      }
      
      // ------------------------- READ A USER BY DNI -------------------------

      public function readAUserByDNI( int $dni ): ?UsuarioModelo #el ? significa que puede no encontrarlo pero si lo encuentra retorna el objeto (usuario modelo)
      { 

        $sql = "SELECT idUsuario, userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME." WHERE dni = :dni";

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam( ":dni", $dni, PDO::PARAM_INT );
          $stmt->execute();

          $queryResult = $stmt->fetch( PDO::FETCH_ASSOC );

          if( !$queryResult ) {
            throw new Exception("porque te tatuatis");
          }

          return new UsuarioModelo(
            $queryResult["idUsuario"],
            $queryResult["userName"],
            $queryResult["passwordHash"],
            $queryResult["nombre"],
            $queryResult["apellido"],
            $queryResult["dni"],
            $queryResult["email"],
            $queryResult["telefono"],
            $queryResult["direccion"],
            $queryResult["fnacimiento"]
          ); #queryresult retorna todos los campos de un registro de la bd

        } catch( PDOException $e) {
          error_log("error al buscar tal usuario por su dni en la BD".$e->getMessage());
          throw new Exception("no se encontro a ningun usuario en la BD con el dni suministrado");
        } catch( Exception $e) {
          error_log("error al buscar por dni al usuario");
          throw $e;
        }

      }
      
      // ------------------------- READ A USER BY ID -------------------------

      public function readAUserByID( int $idUsuario ): ?UsuarioModelo {
        $sql = "SELECT idUsuario, userName, passwordHash, nombre, apellido, dni, telefono, email, direccion, fnacimiento FROM ".self::TBL_NAME." WHERE idUsuario = :idUsuario";
        
        try {
          #conn seria un objeto de clase PDO
          $conn = $this->connectionBD->getConnection(); #se inyecta la BD para entrar a sus metodos
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam( ":idUsuario", $idUsuario, PDO::PARAM_INT );
          $stmt->execute();

          $queryResult = $stmt->fetch( PDO::FETCH_ASSOC );

          if ( !$queryResult ) {
            throw new Exception("no existe ningun usuario con el id ingresado");
          } #si no recupera nada no hace nada de lo que sigue abajo
          #si encuentra un resultado en queryresult pasa esto=
          return new UsuarioModelo(
            $queryResult["idUsuario"],
            $queryResult["userName"],
            $queryResult["passwordHash"],
            $queryResult["nombre"],
            $queryResult["apellido"],
            $queryResult["dni"],
            $queryResult["email"],
            $queryResult["telefono"],
            $queryResult["direccion"],
            $queryResult["fnacimiento"],
          );

        } catch(PDOException $e) {
            error_log("no existe un usuario con ese id en la BD");
            throw new Exception("no existe un usuario con esa ID en la BD");

        } catch( Exception $e) {
            error_log("no se encontro un usuario con esa ID");
            throw $e;
        }
      }
      
      // --------------------------- READ ALL USER ---------------------------

      public function readAllUser(): array { #sera una coleccion de objetos lo que devuelve
        $sql = "SELECT idUsuario, userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME." ORDER BY idUsuario";

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->execute();

          $queryResult = $stmt->fetchAll( PDO::FETCH_ASSOC ); #fetchAll devuelve todos los registros

          $AllUser = [];

          foreach( $queryResult as $row ) { #se usa para asignar a cada fila (resultado) al arreglo de alluser

            $AllUser[] = new UsuarioModelo(
              $row["idUsuario"],
              $row["userName"],
              $row["passwordHash"],
              $row["nombre"],
              $row["apellido"],
              $row["dni"],
              $row["email"],
              $row["telefono"],
              $row["direccion"],
              $row["fnacimiento"],
            );

          }

          return $AllUser;

        } catch( PDOException $e ) {
          error_log("error al intentar listar todos los usuarios". $e->getMessage());
          throw new Exception("error al intentar listar todos los usuarios");

        } catch( Exception $e ) {
          error_log("error al intentar listar todos los usuarios");
          throw $e;
        }
      }

      // -------------------------- UPDATE A USER ---------------------------

      public function updateAUser( UsuarioModelo $usuario ): UsuarioModelo {

        $sql = "UPDATE ". self::TBL_NAME. " SET userName = :userName, passwordHash = :passwordHash, nombre = :nombre, apellido = :apellido, dni = :dni, email = :email, telefono = :telefono, direccion = :direccion, fnacimiento = :fnacimiento WHERE idUsuario = idUsuario";

        $userData = [
          ":nombre" => $usuario->getNombre(),
          ":apellido" => $usuario->getApellido(),
          ":dni" => $usuario->getDni(),
          ":email" => $usuario->getEmail(),
          ":telefono" => $usuario->getTelefono(),
          ":direccion" => $usuario->getDireccion(),
          ":fnacimiento" => $usuario->getFnacimiento(),
          "idUsuario" => $usuario->getIdUsuario()
        ];

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->execute( $userData );
         
          if( $stmt->rowCount() === 0 ) {
            throw new Exception("la modificacion no fue exitosa");
          }

          return $this->readAUserByDNI( $usuario->getIdUsuario() );

        } catch( PDOException $e ) {
          error_log("No se puede actualizar ese usuario en la base de datos". $e->getMessage());
          throw new Exception("error  al actualizar en la BD");

        } catch(Exception $e) {
          error_log("error al actualizar el usuario en la BD");
          throw $e;
        }

      }

      // --------------------------- DELETE A USER --------------------------

      public function deleteAUser( int $idUsuario): bool {
        $sql = "DELETE FROM ".self::TBL_NAME." WHERE idUsuario = :idUsuario";

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
          $stmt->execute();
          
          return $stmt->rowCount() > 0;

        } catch( PDOException $e ) {
          error_log("error al borrar el registro en la BD". $e->getMessage());
          throw new Exception("error al borrar el registro en la BD");

        } catch( Exception $e ) {
         error_log("error al borrar el registro de la BD");
         throw $e;
        }
      }

    }
    // capas¿? de como funciona de arriba a abajo en profundidad¿
    // vista 
    // controlador 
    // servicio 
    // modelo | dao

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

