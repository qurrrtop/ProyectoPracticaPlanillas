<?php

  require_once __DIR__."/../model/CursadaModelo.php";
  require_once __DIR__."/../config/ConnectionDB.php";


    class CursadaDAO {
      private $connectionDB = null;

      const TBL_NAME = "cursada"; 

      public function __CONSTRUCT( ConnectionDB $connectionDB ) {
        $this->connectionDB = $connectionDB;
      }

      public function createANewCursada( CursadaModelo $cursada): CursadaModelo {

        $sql = "INSERT INTO ".self::TBL_NAME."(, añocursada, fechainicio, fechafin) VALUES (:añocursada, :fechainicio, :fechafin)";
        
        $cursadaData = [
          ":añocursada" => $cursada->getAñoCursada(),
          ":fechainicio" => $cursada->getFechaIni(),
          ":fechafin" => $cursada->getFechafin(),
          
        ];

        try {

          $conn = $this->connectionDB->getConnection();
          $stmt = $conn->prepare( $sql );
          $cursada = $stmt->execute( $cursadaData );
          $newID = $conn->lastInsertId();
         
          return $this->readACursadaByID( $newID ); 

        } catch(PDOException $e) {
          error_log("error al intentar agregar a la BD a una nueva cursada".$e->getMessage());
          throw new Exception("error al intentar agregar a la BD a una nueva cursada");
          
        } catch(Exception $e) {
          error_log("error al intentar guardar una nueva cursada");
          throw $e; 
        }

      }
      
      public function readACursadaByID ( int $idCursada ): ?CursadaModelo {

      $sql = "SELECT idCursada, añocursada, fechainicio, fechafin FROM ". self::TBL_NAME . " WHERE idCursada = :idCursada";

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->bindParam( ":idCursada", $idCursada, pdo::FETCH_ASSOC );
        $stmt->execute();

        $queryResult = $stmt->fetch( PDO::FETCH_ASSOC );

        if ( !$queryResult ) {
          throw new Exception("no existe una cursada con el id ingresado");
        }

        return new CursadaModelo (
          $queryResult["idCursada"],
          $queryResult["añocursada"],
          $queryResult["fechainicio"],
          $queryResult["fechafin"]
        );

      } catch ( PDOException $e ) {
        error_log("error al buscar la cursada por id ". $e->getMessage());
        throw new Exception("error al buscar la cursada por id");

      } catch ( Exception $e ) {
        error_log(" error al buscar la cursada por el id ");
        throw $e;

      }

    }

    public function readAllMateria(): array {
      $sql = "SELECT idCursada, añocursada, fechainicio, fechafin FROM ". self::TBL_NAME . " ORDER BY idCursada";

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->execute();

        $queryResult = $stmt->fetchAll( pdo::FETCH_ASSOC );

        $allCursada = [];

        foreach ( $queryResult as $row ) {

          $allCursada[] = new CursadaModelo(
            $row["idCursada"],
            $row["añocursada"],
            $row["fechainicio"],
            $row["fechafin"]
          );

        }

        return $allCursada;

      } catch ( PDOException $e ) {
        error_log(" error al listar todas las cursadas ". $e->getMessage());
        throw new Exception("error al listar todas las cursadas");

      } catch ( Exception $e ) {
        error_log("error al listar todas las cursadas");
        throw $e;
      }
    }

    public function updateACursada( CursadaModelo $cursada ): CursadaModelo {

      $sql = "UPDATE ". self::TBL_NAME . " SET nombre = :nombre, año = :año, duracion = :duracion, formato = :formato WHERE idMateria = :idMateria";

      $cursadaData = [
        ":idMateria" => $cursada->getCursada(),
        ":nombre" => $cursada->getAñoCursada(),
        ":año" => $cursada->getFechaIni(),
        ":duracion" => $cursada->getFechafin()
      ];

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->execute( $cursadaData );

        if ( $stmt->rowCount() === 0 ) {
          throw new Exception("la modificacion no fue exitosa ");
        }

        return $this->readACursadaByID( $cursada->getCursada() );

      } catch ( PDOException $e ) {
        error_log("error al actualizar la cursada ". $e->getMessage());
        throw new Exception("erorr al actualizar la cursada");

      } catch ( Exception $e ) {
        error_log("error al actualizar la cursada");
        throw $e;

      }

    }

    public function deleteACursada( int $idCursada ): bool {

      $sql = "DELETE FROM ". self::TBL_NAME . " WHERE idCursada = :idCursada";

      try {

        $conn = $this->connectionDB->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->bindParam( ":idCursada", $idCursada, PDO::PARAM_INT );
        $stmt->execute();

        return $stmt->rowCount() > 0;

      } catch ( PDOException $e ) {
        error_log("no se pudo eliminar la cursada ". $e->getMessage() );
        throw new Exception("no se pudo eliminar la cursada");
          
      } catch ( Exception $e ) {
        error_log("no se pudo eliminar la cursada");
        throw $e;

      }
    }

  }


?>