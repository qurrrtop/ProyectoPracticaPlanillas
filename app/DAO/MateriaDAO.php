<?php 

  declare( strict_types = 1 );

  namespace app\dao;

  use app\config\ConnectionDB;
  use app\models\MateriaModel;
  use Exception;
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
        ":idFormato" => $materia->getFormato(),
        ":idDuracion" => $materia->getDuracion()
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
        $stmt->bindParam( ":idMateria", $idMateria, PDO::PARAM_INT );
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

        $queryResult = $stmt->fetchAll( PDO::FETCH_ASSOC );

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

    public function readMateriasByIds( array $materiasId): array {
      // si el array viene vacío, evitamos la consulta
      if (empty($materiasId)) {
          return [];
      }

      // se arma los placeholders (?, ?, ?, ...)
      $placeholders = implode(',', array_fill(0, count($materiasId), '?'));

      $sql = "SELECT idMateria, nombre 
              FROM " . self::TBL_NAME . " 
              WHERE idMateria IN ($placeholders)";
      
      try {
        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare($sql);

        // ejecutamos con los IDs como parámetros
        $stmt->execute($materiasId);

        // devuelve arreglo de materias con sus campos
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {

        error_log("MateriaDAO -> Error al obtener materias por IDs: " . $e->getMessage());
        throw new Exception("No se pudieron obtener las materias seleccionadas.");

      } catch (Exception $e) {
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
        error_log("no se pudo eliminar la materia ". $e->getMessage());
        throw new Exception("no se pudo eliminar la materia");
          
      } catch ( Exception $e ) {
        error_log("no se pudo eliminar la materia");
        throw $e;

      }
    }

    public function getDataMateria( int $idMateria ): array {
      $sql = "SELECT 
                m.nombre AS materia,
                u.nombre AS docente_nombre,
                u.apellido AS docente_apellido,
                d.nombre AS duracion,
                f.nombre AS formato,
                r.nombre AS regimen
            FROM " . self::TBL_NAME . " m
            INNER JOIN usuario_materia um ON um.idMateria = m.idMateria
            INNER JOIN usuarios u ON u.idPersona = um.idPersona
            INNER JOIN duracion d ON d.idDuracion = m.idDuracion
            INNER JOIN formato f ON f.idFormato = m.idFormato
            INNER JOIN regimen r ON r.idRegimen = m.idRegimen
            WHERE m.idMateria = :idMateria";

      try {
        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":idMateria", $idMateria, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: [];

      } catch (PDOException $e) {
          error_log("Error al traer los datos de la materia ". $e->getMessage());
          throw new Exception("no se pudo trare los datos de la materia");
      } catch (Exception $e) {
          error_log("no se pudo traer la información de la materia");
          throw $e;
      }
    }

    // método que obtiene todos los años de las materias
    // para trabajar con el select (soluciona un bug)
    public function getAllAnioMateria(): array {
      $sql = "SELECT DISTINCT anio FROM " . self::TBL_NAME . " ORDER BY anio";

      try {
        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $anios = $stmt->fetchAll(PDO::FETCH_COLUMN); // devuelve solo los valores de la columna "anio"
        return $anios;

      } catch (PDOException $e) {
          error_log("Error al obtener los años de materias: " . $e->getMessage());
          throw new Exception("No se pudieron obtener los años de las materias.");
      } catch (Exception $e) {
          throw $e;
      }
    }

    public function countMateriasTotal() : int {
      $sql = "SELECT COUNT(*) AS totalMaterias FROM ".self::TBL_NAME;

      try {
        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->execute();

        $queryResult = $stmt->fetch(PDO::FETCH_ASSOC);

        return $queryResult ? $queryResult['totalMaterias'] : 0;

      } catch (PDOException $e) {
        error_log("Error al obtener todas las materias: " . $e->getMessage());
        throw new Exception("No se pudieron obtener todas las materias.");
      } catch (Exception $e) {
        throw $e;
      }
    }

  }


?>