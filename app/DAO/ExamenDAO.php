<?php 

  declare( strict_types = 1 );
  
  namespace app\dao;

  use app\config\ConnectionDB;
  use app\models\ExamenModel;

  use PDOException;
  use Exception;
  use PDO;

  class ExamenDAO {

    private $connectionDB = null;

    const TBL_NAME = "examen";

    public function __construct( ConnectionDB $connectionDB) {
        $this->connectionDB = $connectionDB;
    }

    public function createANewExamen( ExamenModel $examen ): ExamenModel {

      $sql = "INSERT INTO ". self::TBL_NAME ."(fecha, idMateria) VALUES (:fecha, :idMateria)";

      $examenData = [
        ":fecha" => $examen->getFecha(),
        ":idMateria" => $examen->getMateria()
      ];

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $examen = $stmt->execute( $examenData );
        $newID = ( int ) $conn->lastInsertId(); 

        return $this->ReadAnExamenByID( $newID );

      } catch( PDOException $e ) {
        error_log("error al cargar un nuevo examen" .$e->getMessage() );
        throw new Exception("error al cargar un nuevo examen");
    } catch ( Exception $e ) {
        error_log("error al cargar un nuevo examen");
        throw $e;
      }

    }

    public function readAnExamenByID ( int $idExamen ): ?ExamenModel {

      $sql = "SELECT idExamen, fecha, idMateria FROM ". self::TBL_NAME . " WHERE idExamen = :idExamen";

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->bindParam( ":idExamen", $idExamen, PDO::PARAM_INT );
        $stmt->execute();

        $examenData = $stmt->fetch();

        if ( ! $examenData ) {
          return null;
        }

        return new ExamenModel(
          ( int ) $examenData["idExamen"],
          $examenData["fecha"],
          $examenData["idMateria"]
        );

      } catch( PDOException $e ) {
        error_log("error al leer un examen por ID" .$e->getMessage() );
        throw new Exception("error al leer un examen por ID");

      } catch ( Exception $e ) {
        error_log("error al leer un examen por ID");
        throw $e;

      }
    
    }

    public function readAllExamen(): array {

      $sql = "SELECT idExamen, fecha, idMateria FROM ". self::TBL_NAME . "ORDER BY idMateria";

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->execute();

        $queryResult = $stmt->fetchAll( PDO::FETCH_ASSOC );
        $allExamen = [];

        foreach( $queryResult as $row ) {

          $allMateria[] = new ExamenModel(
            ( int ) $row["idExamen"],
            $row["fecha"],
            $row["idMateria"]
          );

        }

        return $allExamen;

      } catch ( PDOException $e ) {
        error_log("error al leer todos los examenes ". $e->getMessage());
        throw new Exception("error al leer todos los examenes");

      } catch ( Exception $e ) {
        error_log(" error al leer todos los examenes ");
        throw $e;

      }

    }

    public function updateAnExamen( ExamenModel $examen ): ExamenModel {

      $sql = "UPDATE ". self::TBL_NAME . " SET fecha = :fecha, idMateria = :idMateria WHERE idExamen = :idExamen";

      $examenData = [
        ":fecha" => $examen->getFecha(),
        ":idMateria" => $examen->getMateria(),
        ":idExamen" => $examen->getIDExamen()
      ];

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->execute( $examenData );

        if( $stmt->rowCount() === 0 ) {
          throw new Exception( "No se pudo actualizar el examen" );
        }

        return $this->readAnExamenByID( $examen->getIDExamen() );

      } catch ( PDOException $e ) {
        error_log("error al actualizar un examen ". $e->getMessage() );
        throw new Exception("error al actualizar un examen");

      } catch ( Exception $e ) {
        error_log("error al actualizar un examen");
        throw $e;
      }

    }

    public function deleteAnExamen( int $idExamen ): void {

      $sql = "DELETE FROM ". self::TBL_NAME . " WHERE idExamen = :idExamen";

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->bindParam( ":idExamen", $idExamen, PDO::PARAM_INT );
        $stmt->execute();

      } catch ( PDOException $e ) {
        error_log("error al eliminar un examen ". $e->getMessage() );
        throw new Exception("error al eliminar un examen");

      } catch ( Exception $e ) {
        error_log("error al eliminar un examen");
        throw $e;
      }

    }

    public function readExamenByAlumno( int $idAlumno, int $idMateria ): array {
      $sql = "SELECT e.oportunidad, e.nota, e.fechaExamen
              FROM examen e
              JOIN cursada c ON e.idCursada = c.idCursada
              WHERE c.idAlumno = :idAlumno
              AND c.idMateria = :idMateria
              AND anioCursada = 2025
              ORDER BY e.oportunidad ASC";

      try {
        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->bindParam( ':idAlumno', $idAlumno, PDO::PARAM_INT );
        $stmt->bindParam( ':idMateria', $idMateria, PDO::PARAM_INT );
        $stmt->execute();

        $rows = $stmt->fetchAll( PDO::FETCH_ASSOC ) ?: [];
        return $rows;
        echo "dao";

      } catch ( PDOException $e ) {
        error_log("ERROR AlumnoDAO: " . $e->getMessage());
        throw new Exception("Error al obtener examenes del alumno");

      } catch ( Exception $e ) {
        error_log("Error al obtener examenes del alumno");
        throw $e;
      }
    }

  }

?>