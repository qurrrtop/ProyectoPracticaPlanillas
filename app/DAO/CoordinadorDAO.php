<?php

  declare( strict_types = 1 );

  namespace app\dao;
  
  use app\config\ConnectionDB;
  use app\models\CoordinadorModel;
  use Exception;
  use PDOException;
  use PDO;

    class CoordinadorDAO {
      private $connectionDB = null;

      const TBL_NAME_USERS = "usuarios"; #nombre de la tabla en la BD user es pa ponerle alguno, dsp vemos

      const TBL_NAME_USER_MATERIA = "usuario_materia";

      // ------------- CONSTRUCTOR CON INYECCIÓN DE DEPENDENCIAS --------------

      public function __CONSTRUCT( ConnectionDB $connectionDB ) {
        $this->connectionDB = $connectionDB;
      }

      // ------------------------- CREATE A NEW COORDINADOR -------------------------

      public function createANewCoordinador( CoordinadorModel $coordinador): CoordinadorModel {
        $sql = "INSERT INTO ".self::TBL_NAME_USERS." (userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento) VALUES (:userName, :passwordHash, :nombre, :apellido, :dni, :email, :telefono, :direccion, :fnacimiento)";
        
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
          $newID = ( int ) $conn->lastInsertId();
         
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

        $sql = "SELECT idPersona, userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME_USERS." WHERE dni = :dni";

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
        $sql = "SELECT idPersona, userName, passwordHash, nombre, apellido, dni, telefono, email, direccion, fnacimiento FROM ".self::TBL_NAME_USERS." WHERE idPersona = :idPersona";
        
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
        $sql = "SELECT idPersona, userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME_USERS." ORDER BY idPersona";

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

        $sql = "UPDATE ". self::TBL_NAME_USERS. " SET userName = :userName, passwordHash = :passwordHash, nombre = :nombre, apellido = :apellido, dni = :dni, email = :email, telefono = :telefono, direccion = :direccion, fnacimiento = :fnacimiento WHERE idPersona = :idPersona";

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
        $sql = "DELETE FROM ".self::TBL_NAME_USERS." WHERE idPersona = :idPersona";

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

      // ---- método que cuenta la cantidad de docentes que hay en el sistema ----

      public function countDocentes() {
        $sql = "SELECT COUNT(*) AS total FROM ".self::TBL_NAME_USERS." WHERE rol = 'DOCENTE'";

        try {
          $conn = $this->connectionDB->getConnection();
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          // ------ operador ternario ---------
          // si encontró algo que lo devuelva, si no encontró nada devuelve 0.
          return $result ? $result['total'] : 0;

        } catch(PDOException $e) {
          error_log("No se puede traer la cantidad de docentes de la base de datos". $e->getMessage());
          throw new Exception("error al contar los docentes en la base de datos");

        } catch (Exception $e) {
          error_log("error al contar los docentes en la BD");
          throw $e;
        }
      }

      // método dinámico que sirve para asignar materias a usuarios nuevos y
      // a usuarios ya existentes
      public function asignarMaterias(int $idPersona, array $materias): bool {
        // consulta SQL que verifica si el usuario es nuevo (sin materias asignadas)
        // o uno ya existente (con materias asignadas)
        $sqlCheck = "SELECT COUNT(*) FROM ".self::TBL_NAME_USER_MATERIA. " WHERE idPersona = :idPersona";

        // consulta SQL que elimina las materias que ya tiene asignado, para asignar las nuevas
        $sqlDelete = "DELETE FROM ".self::TBL_NAME_USER_MATERIA." WHERE idPersona = :idPersona";

        // consulta SQL que inserta las materias (o nuevas materias) al docente
        $sqlInsert = "INSERT INTO ".self::TBL_NAME_USER_MATERIA." (idPersona, idMateria) VALUES
                      (:idPersona, :idMateria)";
        try {
          $conn = $this->connectionDB->getConnection();

          // verificamos si ya tiene materias asignadas
          $stmtCheck = $conn->prepare( $sqlCheck );
          $stmtCheck->bindParam( ":idPersona", $idPersona, PDO::PARAM_INT );
          $stmtCheck->execute();

          $tieneMaterias = $stmtCheck->fetchColumn() > 0;

          // si tiene materias asignadas se eliminan
          if ( $tieneMaterias ) {
            $stmtDelete = $conn->prepare( $sqlDelete );
            $stmtDelete->bindParam( ":idPersona", $idPersona, PDO::PARAM_INT );

            $stmtDelete->execute();
          }

          // se inserta(n) la(s) materia(s) al docente
          $stmtInsert = $conn->prepare( $sqlInsert );

          $stmtInsert->bindParam( ":idPersona", $idPersona, PDO::PARAM_INT );
          $stmtInsert->bindParam( ":idMateria", $idMateria, PDO::PARAM_INT );

          foreach ($materias as $idMateria) {
            $stmtInsert->execute();
          }

          return true;

        } catch (PDOException $e) {
          error_log("Error en asignarMaterias: " . $e->getMessage());
          return false;
        } catch (Exception $e) {
          error_log("Error inesperado en asignarMaterias: " . $e->getMessage());
          return false;
        }
      }

      public function getMateriasOcupadas() {
        $sql = "SELECT idMateria FROM ".self::TBL_NAME_USER_MATERIA;

        try {
          $conn = $this->connectionDB->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->execute();

          return $stmt->fetchAll(PDO::FETCH_COLUMN);

        } catch (PDOException $e) {
          error_log("No se pudo traer las materias ocupadas". $e->getMessage());
          throw new Exception("error al traer las materias ocupadas");
        } catch (Exception $e) {
          error_log("error al traer las materias ocupadas desde la BD");
          throw $e;
        }
      }

      public function getAllMateriasByUsers(): array {
        $sql = "SELECT
                    u.idPersona,
                    m.idMateria,
                    m.nombre AS nombreMateria
                  FROM ".self::TBL_NAME_USERS. " u
                  LEFT JOIN ".self::TBL_NAME_USER_MATERIA. " um ON um.idPersona = u.idPersona
                  LEFT JOIN materias m ON m.idMateria = um.idMateria";
        
        try {
          $conn = $this->connectionDB->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->execute();

          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

          // Agrupar materias por idPersona
          $materiasPorUsuario = [];

          foreach ($rows as $row) {
              $idPersona = $row['idPersona'];

              // inicializar si no existe
              if (!isset($materiasPorUsuario[$idPersona])) {
                  $materiasPorUsuario[$idPersona] = [];
              }

              // Si NO tiene materias (LEFT JOIN) → no agregamos nada
              if ($row['idMateria'] !== null) {
                  $materiasPorUsuario[$idPersona][] = [
                      'idMateria' => $row['idMateria'],
                      'nombreMateria' => $row['nombreMateria']
                  ];
              }
          }

          return $materiasPorUsuario;

        } catch (PDOException $e) {
            error_log("No se pudo traer las materias de los docentes". $e->getMessage());
            throw new Exception("error al traer las materias de los docentes");
        } catch (Exception $e) {
            error_log("error al traer las materias de docentes desde la BD");
            throw $e;
        }
      }

    }
    ?>