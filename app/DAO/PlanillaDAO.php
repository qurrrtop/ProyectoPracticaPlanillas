<?php 

  class PlanillaDAO {

    private $connectionBD = null;

    const TBL_NAME = "planilla";

    public function __construct( ConnectionBD $connectionBD) {
      $this->connectionBD = $connectionBD;
    }

    public function createANewPlanilla( PlanillaModelo $planilla ): PlanillaModelo {
      
      $sql = "INSERT INTO ". self::TBL_NAME . "(asistencia, promedio, condicion) VALUES (:asistencia, :promedio, :condicion)";

      $planillaData = [
        ":asistencia" => $planilla->getAsistencia(),
        ":promedio" => $planilla->getPromedio(),
        ":condicion" => $planilla->getCondicion()
      ];

      try {

        $conn = $this->connectionBD->getConnection();
        $stmt = $conn->prepare( $sql );
        $planilla = $stmt->execute( $planillaData );
        $newID = $conn->lastInsertId();

        return $this->readAPlanillaByID( $newID );

      } catch ( PDOException $e ) {
        error_log("error al cargar una nueva planilla ". $e->getMessage() );
        throw new Exception("error al cargar una nueva planilla");

      } catch ( Exception $e ) {
        error_log("error al cargar una nueva planilla");
        throw $e;

      }

    }

    public function readAPlanillaByID( int $idPlanilla ): ?PlanillaModelo {

      $sql = "SELECT idPlanilla, asistencia, promedio, condicion FROM ". self::TBL_NAME . " WHERE idPlanilla = :idPlanilla";

      try {

        $conn = $this->connectionBD->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->bindParam( ":idPlanilla", $idPlanilla, PDO::PARAM_INT );
        $stmt->execute();

        $queryResult = $stmt->fetch( PDO::FETCH_ASSOC );

        if ( !$queryResult ) {
          throw new Exception("no existe planilla con el id ingresado");
        }

        return new PlanillaModelo(
          $queryResult["idPlanilla"],
          $queryResult["asistencia"],
          $queryResult["promedio"],
          $queryResult["condicion"]
        );

      } catch ( PDOException $e ) {
        error_log("error al buscar la planilla por ID ". $e->getMessage() );
        throw new Exception("error al buscar la planilla por ID");

      } catch ( Exception $e ) {
        error_log("error al buscar la planilla por ID");
        throw $e;

      }
    }

    public function readAllPlanilla(): array {
      $sql = "SELECT idPlanilla, asistencia, promedio, condicion FROM ". self::TBL_NAME . " ORDER BY idPlanilla";

      try {

        $conn = $this->connectionBD->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->execute();

        $queryResult = $stmt->fetchAll( pdo::FETCH_ASSOC );

        $allPlanilla = [];

        foreach ( $queryResult as $row ) {

          $allPlanilla[] = new PlanillaModelo(
            $row["idPlanilla"],
            $row["asistencia"],
            $row["promedio"],
            $row["condicion"]
          );

        }

        return $allPlanilla;

      } catch ( PDOException $e ) {
        error_log(" error al listar todas las planillas ". $e->getMessage());
        throw new Exception("error al listar todas las planillas");

      } catch ( Exception $e ) {
        error_log("error al listar todas las planillas");
        throw $e;
      }
    }

    public function updateAPlanilla( PlanillaModelo $planilla ): PlanillaModelo {

      $sql = "UPDATE ". self::TBL_NAME . " SET asistencia = :asistencia, promedio = :promedio, condicion = :condicion WHERE idPlanilla = :idPlanilla";

      $planillaData = [
      ":asistencia" => $planilla->getAsistencia(),
      ":promedio" => $planilla->getPromedio(),
      ":condicion" => $planilla->getCondicion()
      ];

      try {

        $conn = $this->connectionBD->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->execute( $planillaData );

        if ( $stmt->rowCount() === 0 ) {
          throw new Exception("la modificacion no fue exitosa ");
        }

        return $this->readAPlanillaByID( $planilla->getIDPlanilla() );

      } catch ( PDOException $e ) {
        error_log("error al actualizar la planilla ". $e->getMessage());
        throw new Exception("erorr al actualizar la planilla");

      } catch ( Exception $e ) {
        error_log("error al actualizar la planilla");
        throw $e;

      }

    }

    public function deleteAPlanilla( int $idPlanilla ): bool {

      $sql = "DELETE FROM ". self::TBL_NAME . " WHERE idPlanilla = :idPlanilla";

      try {

        $conn = $this->connectionBD->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->bindParam( ":idPlanilla", $idPlanilla, PDO::PARAM_INT );
        $stmt->execute();

        return $stmt->rowCount() > 0;

      } catch ( PDOException $e ) {
        error_log("no se pudo eliminar la planilla ". $e->getMessage() );
        throw new Exception("no se pudo eliminar la planilla");
          
      } catch ( Exception $e ) {
        error_log("no se pudo eliminar la planilla");
        throw $e;

      }
    }

  }

?>