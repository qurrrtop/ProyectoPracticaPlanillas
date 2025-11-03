<?php

  declare( strict_types = 1 );

  namespace app\models;

  class MateriaModel {
    private $idMateria;
    private $nombre;
    private $anio;
    private $duracion;
    private $formato;

    public function __construct($idMateria, $nombre, $anio, $duracion, $formato) {
        $this->idMateria = $idMateria;
        $this->nombre = $nombre;
        $this->anio = $anio;
        $this->duracion = $duracion;
        $this->formato = $formato;
    }

    public function getIdMateria() {
      return $this->idMateria;
    }

    public function getNombre() {
      return $this->nombre;
    }

    public function getAnio() {
      return $this->anio;
    }
  
    public function getDuracion() {
      return $this->duracion;
    }

    public function getFormato() {
      return $this->formato;
    }
    
  }

?>