<?php
  require_once __DIR__."/../model/CoordinadorModelo.php";
  require_once __DIR__."/../config/ConnectionBD.php";


    class CoordinadorDAO {
      private $connectionBD = null;

      const TBL_NAME = "user"; #nombre de la tabla en la BD user es pa ponerle alguno, dsp vemos

      // ------------- CONSTRUCTOR CON INYECCIÓN DE DEPENDENCIAS --------------

      public function __CONSTRUCT( ConnectionBD $connectionBD ) {
        $this->connectionBD = $connectionBD;
      }

      // ------------------------- CREATE A NEW COORDINADOR -------------------------

      public function createANewCoordinador( CoordinadorModel $coordinador): CoordinadorModel {
        $sql = "INSERT INTO ".self::TBL_NAME." (userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento) VALUES (:userName, :passwordHash, :nombre, :apellido, :dni, :email, :telefono, :direccion, :fnacimiento)";
        
        $coordinadorData = [
          ":userName" => $coordinador->getUserName(),
          ":passwordHash" => $coordinador->getPasswordHash(),
          ":nombre" => $coordinador->getNombre(),
          ":apellido" => $coordinador->getApellido(),
          ":dni" => $coordinador->getDni(),
          ":email" => $coordinador->getEmail(),
          ":telefono" => $coordinador->getTelefono(),
          ":direccion" => $coordinador->getDireccion(),
          ":fnacimiento" => $coordinador->getFnacimiento()
        ];

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $coordinador = $stmt->execute( $coordinadorData );
          $newID = $conn->lastInsertId();
         
          return $this->readACoordinadorByID( $newID ); 

        } catch(PDOException $e) {

          error_log("error al intentar agregar a la BD a un nuevo coordinador".$e->getMessage());
          throw new Exception("error al intentar agregar a la BD a un nuevo coordinador");
          
        } catch(Exception $e) {

          error_log("error al intentar guardar un nuevo coordinador");
          throw $e; #cualquier problema que haya ocurrido se transfiere a la variable "e" y con throw lo muestra
        }

      }
      
      // ------------------------- READ A COORDINADOR BY DNI -------------------------

      public function readACoordinadorByDNI( int $dni ): ?CoordinadorModel #el ? significa que puede no encontrarlo pero si lo encuentra retorna el objeto (usuario modelo)
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

          return new CoordinadorModel(
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
          error_log("error al buscar tal coordinador por su dni en la BD".$e->getMessage());
          throw new Exception("no se encontro a ningun coordinador en la BD con el dni suministrado");
        } catch( Exception $e) {
          error_log("error al buscar por dni al coordinador");
          throw $e;
        }

      }
      
      // ------------------------- READ A COORDINADOR BY ID -------------------------

      public function readACoordinadorByID( int $idUsuario ): ?CoordinadorModel {
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
          return new CoordinadorModel(
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
            error_log("no existe un coordinador con ese id en la BD");
            throw new Exception("no existe un coordinador con esa ID en la BD");

        } catch( Exception $e) {
            error_log("no se encontro un coordinador con esa ID");
            throw $e;
        }
      }
      
      // --------------------------- READ ALL COORDINADOR ---------------------------

      public function readAllCoordinador(): array { #sera una coleccion de objetos lo que devuelve
        $sql = "SELECT idUsuario, userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME." ORDER BY idUsuario";

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->execute();

          $queryResult = $stmt->fetchAll( PDO::FETCH_ASSOC ); #fetchAll devuelve todos los registros

          $allCoordinador = [];

          foreach( $queryResult as $row ) { #se usa para asignar a cada fila (resultado) al arreglo de alluser

            $allCoordinador[] = new CoordinadorModel(
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

          return $allCoordinador;

        } catch( PDOException $e ) {
          error_log("error al intentar listar todos los coordinadores". $e->getMessage());
          throw new Exception("error al intentar listar todos los coordinadores");

        } catch( Exception $e ) {
          error_log("error al intentar listar todos los coordinadores");
          throw $e;
        }
      }

      // -------------------------- UPDATE A USER ---------------------------

      public function updateACoordinador( CoordinadorModel $coordinador ): CoordinadorModel {

        $sql = "UPDATE ". self::TBL_NAME. " SET userName = :userName, passwordHash = :passwordHash, nombre = :nombre, apellido = :apellido, dni = :dni, email = :email, telefono = :telefono, direccion = :direccion, fnacimiento = :fnacimiento WHERE idUsuario = :idUsuario";

        $coordinadorData = [
          ":nombre" => $coordinador->getNombre(),
          ":apellido" => $coordinador->getApellido(),
          ":dni" => $coordinador->getDni(),
          ":email" => $coordinador->getEmail(),
          ":telefono" => $coordinador->getTelefono(),
          ":direccion" => $coordinador->getDireccion(),
          ":fnacimiento" => $coordinador->getFnacimiento(),
          ":idUsuario" => $coordinador->getIdUsuario()
        ];

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->execute( $coordinadorData );
         
          if( $stmt->rowCount() === 0 ) {
            throw new Exception("la modificacion no fue exitosa");
          }

          return $this->readACoordinadorByDNI( $coordinador->getIdUsuario() );

        } catch( PDOException $e ) {
          error_log("No se puede actualizar ese coordiandor en la base de datos". $e->getMessage());
          throw new Exception("error al actualizar el coordinador en la BD");

        } catch(Exception $e) {
          error_log("error al actualizar el coordinador en la BD");
          throw $e;
        }

      }

      // --------------------------- DELETE A COORDINADOR --------------------------

      public function deleteACoordinador( int $idUsuario ): bool {
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
    ?>