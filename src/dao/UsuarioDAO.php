<?php
  require_once __DIR__."/../model/UsuarioModelo.php";
  require_once __DIR__."/../config/ConnectionBD.php";


    class UsuarioDAO {
      private $connectionBD = null;

      const TBL_NAME = "user"; #nombre de la tabla en la BD user es pa ponerle alguno, dsp vemos

      public function __CONSTRUCT( ConnectionBD $connectionBD ) {
        $this->connectionBD = $connectionBD;
      }
              #CREAR UN NUEVO USUARIO
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
          $newID = $conn->lastInsertId();
         
          return $this->readAUserByDNI( $newID );

        } catch(PDOException $e) {

          error_log("error al intentar agregar a la BD a un nuevo usuario".$e->getMessage());
          throw new Exception("error al intentar agregar a la BD a un nuevo usuario");
          
        } catch(Exception $e) {

          error_log("error al intentar guardar un nuevo usuario".$e->getMessage());
          throw $e; #cualquier problema que haya ocurrido se transfiere a la variable "e" y con throw lo muestra
        }

      }
                      #LEER USUARIO POR DNI
      public function readAUserByDNI( int $dni ): ?UsuarioModelo #el ? significa que puede no encontrarlo pero si lo encuentra retorna el objeto (usuario modelo)
      { 

        $sql = "SELECT idPerson, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME. " WHERE dni = :dni";

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
            $queryResult["idPersona"],
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
                #LEER UN USUARIO POR ID
      public function readAUserByID( int $id ): ?UsuarioModelo {
        $sql = "SELECT idPersona, nombre, apellido, dni, telefono, email, direccion, fnacimiento FROM " .self::TBL_NAME. " WHERE idPersona = :idPersona";
        
        try {
          #conn seria un objeto de clase PDO
          $conn = $this->connectionBD->getConnection(); #se inyecta la BD para entrar a sus metodos
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam( ":idPersona", $id, PDO::PARAM_INT );
          $stmt->execute();

          $queryResult = $stmt->fetch( PDO::FETCH_ASSOC );

          if ( !$queryResult ) {
            throw new Exception("no existe ningun usuario con el id ingresado");
          } #si no recupera nada no hace nada de lo que sigue abajo
          #si encuentra un resultado en queryresult pasa esto=
          return new UsuarioModelo(
            $queryResult["idPersona"],
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
                #LISTAR TODOS LOS USUARIOS
      public function readAllUser(): array { #sera una coleccion de objetos lo que devuelve
        $sql = "SELECT idPersona, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ". self::TBL_NAME. " ORDER BY idPersona";

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->execute();

          $queryResult = $stmt->fetchAll( PDO::FETCH_ASSOC ); #fetchAll devuelve todos los registros

          $AllUser = [];

          foreach( $queryResult as $row ) { #se usa para asignar a cada fila (resultado) al arreglo de alluser

            $AllUser[] = new UsuarioModelo(
              $row["idPersona"],
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

      public function updateAUser( UsuarioModelo $usuario ): UsuarioModelo {

        $sql = "UPDATE ". self::TBL_NAME. " SET nombre = :nombre, apellido = :apellido, dni = :dni, email = :email, telefono = :telefono, direccion = :direccion, fnacimiento = :fnacimiento WHERE idPersona = idPersona";

        $userData = [
          ":nombre" => $usuario->getNombre(),
          ":apellido" => $usuario->getApellido(),
          ":dni" => $usuario->getDni(),
          ":email" => $usuario->getEmail(),
          ":telefono" => $usuario->getTelefono(),
          ":direccion" => $usuario->getDireccion(),
          ":fnacimiento" => $usuario->getFnacimiento(),
          "idPersona" => $usuario->getIdUsuario()
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

      public function deleteAUser( int $idPersona): bool {
        $sql = "DELETE FROM ". self::TBL_NAME . " WHERE idPersona = :idPersona";

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam(":idPersona", $idPersona, PDO::PARAM_INT);
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

