<?php
  require_once __DIR__."/../model/DocenteModel.php";
  require_once __DIR__."/../config/ConnectionBD.php";


    class DocenteDAO {
      private $connectionBD = null;

      const TBL_NAME = "user"; #nombre de la tabla en la BD user es pa ponerle alguno, dsp vemos

      // ------------- CONSTRUCTOR CON INYECCIÓN DE DEPENDENCIAS --------------

      public function __CONSTRUCT( ConnectionBD $connectionBD ) {
        $this->connectionBD = $connectionBD;
      }

      // ------------------------- CREATE A NEW COORDINADOR -------------------------

      public function createANewCoordinador( DocenteModel $usuario): DocenteModel {
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
         
          return $this->readADocenteByID( $newID );

        } catch(PDOException $e) {

          error_log("error al intentar agregar a la BD a un nuevo docente".$e->getMessage());
          throw new Exception("error al intentar agregar a la BD a un nuevo docente");
          
        } catch(Exception $e) {

          error_log("error al intentar guardar un nuevo docente".$e->getMessage());
          throw $e; #cualquier problema que haya ocurrido se transfiere a la variable "e" y con throw lo muestra
        }

      }
      
      // ------------------------- READ A DOCENTE BY DNI -------------------------

      public function readADocenteByDNI( int $dni ): ?DocenteModel #el ? significa que puede no encontrarlo pero si lo encuentra retorna el objeto (usuario modelo)
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

          return new DocenteModel(
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
          error_log("error al buscar tal docente por su dni en la BD".$e->getMessage());
          throw new Exception("no se encontro a ningun docente en la BD con el dni suministrado");
        } catch( Exception $e) {
          error_log("error al buscar por dni al docente");
          throw $e;
        }

      }
      
      // ------------------------- READ A DOCENTE BY ID -------------------------

      public function readADocenteByID( int $idUsuario ): ?DocenteModel {
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
          return new DocenteModel(
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
            error_log("no existe un docente con ese id en la BD");
            throw new Exception("no existe un docente con esa ID en la BD");

        } catch( Exception $e) {
            error_log("no se encontro un docente con esa ID");
            throw $e;
        }
      }
      
      // --------------------------- READ ALL DOCENTES ---------------------------

      public function readAllDocente(): array { #sera una coleccion de objetos lo que devuelve
        $sql = "SELECT idUsuario, userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME." ORDER BY idUsuario";

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->execute();

          $queryResult = $stmt->fetchAll( PDO::FETCH_ASSOC ); #fetchAll devuelve todos los registros

          $allUser = [];

          foreach( $queryResult as $row ) { #se usa para asignar a cada fila (resultado) al arreglo de alluser

            $allUser[] = new DocenteModel(
              $row["idUsuario"],
              $row["userName"],
              $row["passwordHash"],
              $row["nombre"],
              $row["apellido"],
              $row["dni"],
              $row["email"],
              $row["telefono"],
              $row["direccion"],
              $row["fnacimiento"]
            );

          }

          return $allUser;

        } catch( PDOException $e ) {
          error_log("error al intentar listar todos los docentes". $e->getMessage());
          throw new Exception("error al intentar listar todos los docentes");

        } catch( Exception $e ) {
          error_log("error al intentar listar todos los docentes");
          throw $e;
        }
      }

      // -------------------------- UPDATE A DOCENTE ---------------------------

      public function updateADocente( DocenteModel $docente ): DocenteModel {

        $sql = "UPDATE ". self::TBL_NAME. " SET userName = :userName, passwordHash = :passwordHash, nombre = :nombre, apellido = :apellido, dni = :dni, email = :email, telefono = :telefono, direccion = :direccion, fnacimiento = :fnacimiento WHERE idUsuario = idUsuario";

        $docenteData = [
          ":nombre" => $docente->getNombre(),
          ":apellido" => $docente->getApellido(),
          ":dni" => $docente->getDni(),
          ":email" => $docente->getEmail(),
          ":telefono" => $docente->getTelefono(),
          ":direccion" => $docente->getDireccion(),
          ":fnacimiento" => $docente->getFnacimiento(),
          "idUsuario" => $docente->getIdUsuario()
        ];

        try {

          $conn = $this->connectionBD->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->execute( $docenteData );
         
          if( $stmt->rowCount() === 0 ) {
            throw new Exception("la modificacion no fue exitosa");
          }

          return $this->readADocenteByDNI( $docente->getIdUsuario() );

        } catch( PDOException $e ) {
          error_log("No se puede actualizar ese docente en la base de datos". $e->getMessage());
          throw new Exception("error al actualizar el docente en la BD");

        } catch(Exception $e) {
          error_log("error al actualizar el docente en la BD");
          throw $e;
        }

      }

      // --------------------------- DELETE A DOCENTE --------------------------

      public function deleteADocente( int $idUsuario): bool {
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

