<?php 

  declare( strict_types = 1 );

  namespace app\dao;

  use app\config\ConnectionDB;
  use app\models\MateriaModel;
  use  Exception;
  use PDOException;
  use PDO;


  class MateriaDAO {

    private $connectionDB = null;

    const TBL_NAME = "materias";

    public function __construct( ConnectionDB $connectionDB) {
        $this->connectionDB = $connectionDB;
    }

    public function createANewMateria( MateriaModel $materia ): MateriaModel {
      
      $sql = "INSERT INTO " . self::TBL_NAME ."(nombre, anio, idFormato, idDuracion) VALUES (:nombre, :anio, :idFormato, :idDuracion)";

      $materiaData = [
        ":nombre" => $materia->getNombre(),
        ":anio" => $materia->getAnio(),
        ":idFormato" => $materia->getDuracion(),
        ":idDuracion" => $materia->getFormato()
      ];

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $materia = $stmt->execute( $materiaData );
        $newID = ( int ) $conn->lastInsertId();

        return $this->readAMateriaByID( $newID );

      } catch( PDOException $e ) {
        error_log("error al cargar una nueva materia" .$e->getMessage() );
        throw new Exception("error al cargar una nueva materia");

      } catch ( Exception $e ) {
        error_log("error al cargar una nueva materia");
        throw $e;

      }

    }

    public function readAMateriaByID ( int $idMateria ): ?MateriaModel {

      $sql = "SELECT idMateria, nombre, anio, idFormato, idDuracion FROM ". self::TBL_NAME . " WHERE idMateria = :idMateria";

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->bindParam( ":idMateria", $idMateria, pdo::FETCH_ASSOC );
        $stmt->execute();

        $queryResult = $stmt->fetch( PDO::FETCH_ASSOC );

        if ( !$queryResult ) {
          throw new Exception("no existe materia con el id ingresado");
        }

        return new MateriaModel (
          $queryResult["idMateria"],
          $queryResult["nombre"],
          $queryResult["anio"],
          $queryResult["idFormato"],
          $queryResult["idDuracion"]
        );

      } catch ( PDOException $e ) {
        error_log("error al buscar la materia por id ". $e->getMessage());
        throw new Exception("error al buscar la materia por id");

      } catch ( Exception $e ) {
        error_log(" error al buscar la materia por el id ");
        throw $e;

      }

    }

    public function readAllMateria(): array {
      $sql = "SELECT idMateria, nombre, anio, idFormato, idDuracion FROM ". self::TBL_NAME . " ORDER BY idMateria";

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->execute();

        $queryResult = $stmt->fetchAll( pdo::FETCH_ASSOC );

        $allMateria = [];

        foreach ( $queryResult as $row ) {

          $allMateria[] = new MateriaModel(
            $row["idMateria"],
            $row["nombre"],
            $row["anio"],
            $row["idFormato"],
            $row["idDuracion"]
          );

        }

        return $allMateria;

      } catch ( PDOException $e ) {
        error_log(" error al listar todas las materias ". $e->getMessage());
        throw new Exception("error al listar todas las materias");

      } catch ( Exception $e ) {
        error_log("error al listar todas las materias");
        throw $e;
      }
    }

    public function updateAMateria( MateriaModel $materia ): MateriaModel {

      $sql = "UPDATE ". self::TBL_NAME . " SET nombre = :nombre, anio = :anio, idFormato = :idFormato, idDuracion = :idDuracion WHERE idMateria = :idMateria";

      $materiaData = [
        ":idMateria" => $materia->getIDMateria(),
        ":nombre" => $materia->getNombre(),
        ":anio" => $materia->getAnio(),
        ":idFormato" => $materia->getDuracion(),
        ":idDuracion" => $materia->getFormato()
      ];

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->execute( $materiaData );

        if ( $stmt->rowCount() === 0 ) {
          throw new Exception("la modificacion no fue exitosa ");
        }

        return $this->readAMateriaByID( $materia->getIDMateria() );

      } catch ( PDOException $e ) {
        error_log("error al actualizar la materia ". $e->getMessage());
        throw new Exception("erorr al actualizar la materia");

      } catch ( Exception $e ) {
        error_log("error al actualizar la materia");
        throw $e;

      }

    }

    public function deleteAMateria( int $idMateria ): bool {

      $sql = "DELETE FROM ". self::TBL_NAME . " WHERE idMateria = :idMateria";

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->bindParam( ":idMateria", $idMateria, PDO::PARAM_INT );
        $stmt->execute();

        return $stmt->rowCount() > 0;

      } catch ( PDOException $e ) {
        error_log("no se pudo eliminar la materia ". $e->getMessage() );
        throw new Exception("no se pudo eliminar la materia");
          
      } catch ( Exception $e ) {
        error_log("no se pudo eliminar la materia");
        throw $e;

      }
    }

  }


?>