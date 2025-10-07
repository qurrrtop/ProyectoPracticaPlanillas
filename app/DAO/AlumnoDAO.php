<?php
    require_once __DIR__."/../model/AlumnoModelo.php";
    require_once __DIR__."/../config/ConnectionDB.php";

    class AlumnoDAO {
        private $connectionDB = null;

        const TBL_NAME = 'alumno';

      // ------------- CONSTRUCTOR CON INYECCIÓN DE DEPENDENCIAS --------------

      public function __CONSTRUCT( ConnectionDB $connectionDB ) {
        $this->connectionDB = $connectionDB;
      }

      // ------------------------- CREATE A NEW ALUMNO -------------------------

      public function createANewAlumno( AlumnoModelo $alumno): AlumnoModelo {
        $sql = "INSERT INTO ".self::TBL_NAME." (nombre, apellido, dni, libreta, cohorte, legajo) VALUES (:nombre, :apellido, :dni, :libreta, :cohorte, :legajo)";
        
        $alumnoData = [
          ":nombre" => $alumno->getNombre(),
          ":apellido" => $alumno->getApellido(),
          ":dni" => $alumno->getDni(),
          ":libreta" => $alumno->getLibreta(),
          ":cohorte" => $alumno->getCohorte(),
          ":legajo" => $alumno->getLegajo()
        ];

        try {

          $conn = $this->connectionDB->getConnection();
          $stmt = $conn->prepare( $sql );
          $alumno = $stmt->execute( $alumnoData );
          $newID = $conn->lastInsertId();
         
          return $this->readAAlumnoByDNI( $newID );

        } catch(PDOException $e) {

          error_log("error al intentar agregar a la BD a un nuevo alumno".$e->getMessage());
          throw new Exception("error al intentar agregar a la BD a un nuevo alumno");
          
        } catch(Exception $e) {

          error_log("error al intentar guardar un nuevo alumno".$e->getMessage());
          throw $e; #cualquier problema que haya ocurrido se transfiere a la variable "e" y con throw lo muestra
        }

      }


      // ------------------------- READ A ALUMNO BY DNI -------------------------

      public function readAAlumnoByDNI( int $dni ): ?AlumnoModelo { 

        $sql = "SELECT nombre, apellido, dni, libreta, cohorte, legajo FROM ".self::TBL_NAME." WHERE dni = :dni";

        try {

          $conn = $this->connectionDB->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam( ":dni", $dni, PDO::PARAM_INT );
          $stmt->execute();

          $queryResult = $stmt->fetch( PDO::FETCH_ASSOC );

          if( !$queryResult ) {
            throw new Exception("No existe el alumno con el DNI ingresado");
          }

          return new AlumnoModelo(
            $queryResult["nombre"],
            $queryResult["apellido"],
            $queryResult["dni"],
            $queryResult["libreta"],
            $queryResult["cohorte"],
            $queryResult["legajo"]
          ); #queryresult retorna todos los campos de un registro de la bd

        } catch( PDOException $e) {
          error_log("error al buscar tal alumno por su dni en la BD".$e->getMessage());
          throw new Exception("no se encontro a ningun alumno en la BD con el dni suministrado");
        } catch( Exception $e) {
          error_log("error al buscar por dni al alumno");
          throw $e;
        }

      }

      // --------------------------- READ ALL ALUMNO X INCOMPLETO X---------------------------

      public function readAllAlumno(): array { #sera una coleccion de objetos lo que devuelve
        $sql = "SELECT idUsuario, userName, passwordHash, nombre, apellido, dni, email, telefono, direccion, fnacimiento FROM ".self::TBL_NAME." ORDER BY idUsuario";

        try {

          $conn = $this->connectionDB->getConnection();
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
              $row["fnacimiento"],
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

      // -------------------------- UPDATE A ALUMNO ---------------------------
  
        public function updateAAlumno( AlumnoModelo $alumno ): AlumnoModelo {
  
          $sql = "UPDATE ".self::TBL_NAME." SET nombre = :nombre, apellido = :apellido, dni = :dni, libreta = :libreta, cohorte = :cohorte, legajo = :legajo WHERE dni = :dni";
  
          $alumnoData = [
            ":nombre" => $alumno->getNombre(),
            ":apellido" => $alumno->getApellido(),
            ":dni" => $alumno->getDni(),
            ":libreta" => $alumno->getLibreta(),
            ":cohorte" => $alumno->getCohorte(),
            ":legajo" => $alumno->getLegajo(),
          ];
  
          try {
  
            $conn = $this->connectionDB->getConnection();
            $stmt = $conn->prepare( $sql );
            $stmt->execute( $alumnoData );
           
            if( $stmt->rowCount() === 0 ) {
              throw new Exception("la modificacion no fue exitosa");
            }
  
            return $this->readAAlumnoByDNI( $alumno->getDni() );
  
          } catch( PDOException $e ) {
            error_log("No se puede actualizar ese alumno en la base de datos". $e->getMessage());
            throw new Exception("error al actualizar el alumno en la BD");
  
          } catch(Exception $e) {
            error_log("error al actualizar el alumno en la BD");
            throw $e;
          }
  
        }

        // --------------------------- DELETE A ALUMNO --------------------------

      public function deleteAAlumno( int $dni): bool {
        $sql = "DELETE FROM ".self::TBL_NAME." WHERE dni = :dni";

        try {

          $conn = $this->connectionDB->getConnection();
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam(":dni", $dni, PDO::PARAM_INT);
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