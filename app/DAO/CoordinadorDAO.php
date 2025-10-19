<?php
  require_once __DIR__."/../models/CoordinadorModel.php";
  require_once __DIR__."/../config/ConnectionDB.php";


    class CoordinadorDAO {
      private $connectionDB = null;

      const TBL_NAME = "usuarios"; #nombre de la tabla en la BD user es pa ponerle alguno, dsp vemos

      const TBL_NAME_USER_MATERIA = "usuario_materia";

      // ------------- CONSTRUCTOR CON INYECCIÓN DE DEPENDENCIAS --------------

      public function __CONSTRUCT( ConnectionDB $connectionDB ) {
        $this->connectionDB = $connectionDB;
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

          $conn = $this->connectionDB->getConnection();
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

        $sql = "SELECT idPersona, userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME." WHERE dni = :dni";

        try {

          $conn = $this->connectionDB->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam( ":dni", $dni, PDO::PARAM_INT );
          $stmt->execute();

          $queryResult = $stmt->fetch( PDO::FETCH_ASSOC );

          if( !$queryResult ) {
            throw new Exception("porque te tatuatis");
          }

          return new CoordinadorModel(
            $queryResult["idPersona"],
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

      public function readACoordinadorByID( int $idPersona ): ?CoordinadorModel {
        $sql = "SELECT idPersona, userName, passwordHash, nombre, apellido, dni, telefono, email, direccion, fnacimiento FROM ".self::TBL_NAME." WHERE idPersona = :idPersona";
        
        try {
          #conn seria un objeto de clase PDO
          $conn = $this->connectionDB->getConnection(); #se inyecta la BD para entrar a sus metodos
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam( ":idPersona", $idPersona, PDO::PARAM_INT );
          $stmt->execute();

          $queryResult = $stmt->fetch( PDO::FETCH_ASSOC );

          if ( !$queryResult ) {
            throw new Exception("no existe ningun usuario con el id ingresado");
          } #si no recupera nada no hace nada de lo que sigue abajo
          #si encuentra un resultado en queryresult pasa esto=
          return new CoordinadorModel(
            $queryResult["idPersona"],
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
        $sql = "SELECT idPersona, userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME." ORDER BY idPersona";

        try {

          $conn = $this->connectionDB->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->execute();

          $queryResult = $stmt->fetchAll( PDO::FETCH_ASSOC ); #fetchAll devuelve todos los registros

          $allCoordinador = [];

          foreach( $queryResult as $row ) { #se usa para asignar a cada fila (resultado) al arreglo de alluser

            $allCoordinador[] = new CoordinadorModel(
              $row["idPersona"],
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

        $sql = "UPDATE ". self::TBL_NAME. " SET userName = :userName, passwordHash = :passwordHash, nombre = :nombre, apellido = :apellido, dni = :dni, email = :email, telefono = :telefono, direccion = :direccion, fnacimiento = :fnacimiento WHERE idPersona = :idPersona";

        $coordinadorData = [
          ":nombre" => $coordinador->getNombre(),
          ":apellido" => $coordinador->getApellido(),
          ":dni" => $coordinador->getDni(),
          ":email" => $coordinador->getEmail(),
          ":telefono" => $coordinador->getTelefono(),
          ":direccion" => $coordinador->getDireccion(),
          ":fnacimiento" => $coordinador->getFnacimiento(),
          ":idPersona" => $coordinador->getIdPersona()
        ];

        try {

          $conn = $this->connectionDB->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->execute( $coordinadorData );
         
          if( $stmt->rowCount() === 0 ) {
            throw new Exception("la modificacion no fue exitosa");
          }

          return $this->readACoordinadorByDNI( $coordinador->getIdPersona() );

        } catch( PDOException $e ) {
          error_log("No se puede actualizar ese coordiandor en la base de datos". $e->getMessage());
          throw new Exception("error al actualizar el coordinador en la BD");

        } catch(Exception $e) {
          error_log("error al actualizar el coordinador en la BD");
          throw $e;
        }

      }

      // --------------------------- DELETE A COORDINADOR --------------------------

      public function deleteACoordinador( int $idPersona ): bool {
        $sql = "DELETE FROM ".self::TBL_NAME." WHERE idPersona = :idPersona";

        try {

          $conn = $this->connectionDB->getConnection();
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

      // Métodos relacionados con la asignación de materias a usuarios (solo coordinador puede hacerlo).
      // Aunque corresponden a la tabla intermedia usuario_materia, se mantienen aquí porque forman
      // parte exclusiva de las funciones del coordinador.

      // Asignar materia a un usuario
      public function asignarMateria(int $idPersona, int $idMateria): bool {
          $sql = "INSERT INTO " . self::TBL_NAME_USER_MATERIA . " (idPersona, idMateria) VALUES (:idPersona, :idMateria)";
          
          try {
              $conn = $this->connectionDB->getConnection();
              $stmt = $conn->prepare($sql);
              $stmt->bindParam(':idPersona', $idPersona, PDO::PARAM_INT);
              $stmt->bindParam(':idMateria', $idMateria, PDO::PARAM_INT);
              $stmt->execute();

              return $stmt->rowCount() > 0;

          } catch (PDOException $e) {
              throw new Exception("No se pudo asignar la(s) materia(s)".$e->getMessage());
          }
      }

      // Quitar materia de un usuario
      public function quitarMateria(int $idPersona, int $idMateria): bool {
          $sql = "DELETE FROM " . self::TBL_NAME_USER_MATERIA . " WHERE idPersona = :idPersona AND idMateria = :idMateria";
          
          try {
              $conn = $this->connectionDB->getConnection();
              $stmt = $conn->prepare($sql);
              $stmt->bindParam(':idPersona', $idPersona, PDO::PARAM_INT);
              $stmt->bindParam(':idMateria', $idMateria, PDO::PARAM_INT);
              $stmt->execute();

              return $stmt->rowCount() > 0;

          } catch (PDOException $e) {
              throw new Exception("No se pudo quitar la(s) materia(s)".$e->getMessage());
          }
      }

      // Traer IDs de materias asignadas a un usuario
      public function traerMateriasPorUsuario(int $idPersona): array {
          $sql = "SELECT m.idMateria, m.nombre 
                  FROM " . self::TBL_NAME_USER_MATERIA . " um
                  JOIN materias m ON um.idMateria = m.idMateria
                  WHERE um.idPersona = :idPersona";
          
          try {
              $conn = $this->connectionDB->getConnection();
              $stmt = $conn->prepare($sql);
              $stmt->bindParam(':idPersona', $idPersona, PDO::PARAM_INT);
              $stmt->execute();

              $queryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

              $materiasDeUsuario = [];
              foreach($queryResult as $row) {
                  $materiasDeUsuario[] = [
                      'idMateria' => $row['idMateria'],
                      'nombre' => $row['nombre']
                  ];
              }

              return $materiasDeUsuario;

          } catch(PDOException $e) {
              throw new Exception("Error al listar las materias del usuario: " . $e->getMessage());
          }
      }

      // traer todos los registros de usuario-materia
      public function getAll(): array {
          $sql = "SELECT um.idPersona, m.idMateria, m.nombre AS nombreMateria
                  FROM " . self::TBL_NAME_USER_MATERIA . " um
                  JOIN materias m ON um.idMateria = m.idMateria
                  ORDER BY um.idPersona, m.nombre";

          try {
              $conn = $this->connectionDB->getConnection();
              $stmt = $conn->prepare($sql);
              $stmt->execute();

              $queryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

              $materiasDeTodosLosUsuarios = [];

              foreach ($queryResult as $row) {
                  $materiasDeTodosLosUsuarios[] = [
                      'idPersona' => $row['idPersona'],
                      'idMateria' => $row['idMateria'],
                      'nombreMateria' => $row['nombreMateria']
                  ];
              }

              return $materiasDeTodosLosUsuarios;

          } catch (PDOException $e) {
              throw new Exception("Error al listar todas las materias de los usuarios: " . $e->getMessage());
          }
      }

    }
    ?>