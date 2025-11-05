<?php 

  declare( strict_types = 1 );

  namespace app\models;

  use DateTime;

  class ExamenModel {

    private int $idExamen;
    private DateTime $fecha;
    private MateriaModel $materia;

    public function __construct( int $idExamen, DateTime $fecha, MateriaModel $materia ) {

      $this->idExamen = $idExamen;
      $this->fecha = $fecha;
      $this->materia = $materia;

    }

    public function getIDExamen(): int {
      return $this->idExamen;
    }

    public function getFecha(): DateTime {
      return $this->fecha;
    }

    public function getMateria(): MateriaModel {
      return $this->materia;
    }

    public function setFecha( DateTime $fecha ): void {
      $this->fecha = $fecha;
    }
    
    public function setMateria(  MateriaModel $materia ): void {
      $this->materia = $materia;
    }

  }

?>