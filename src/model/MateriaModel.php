<?php

  class MateriaModel {

    private $idMateria;
    private $nombre;
    private $año;
    private $duracion;
    private $formato;

    public function getIDMateria() {
      return $this->idMateria;
    }

    public function getNombre() {
      return $this->nombre;
    }

    public function getAño() {
      return $this->año;
    }
  
    public function getDuracion() {
      return $this->duracion;
    }

    public function getFormato() {
      return $this->formato;
    }
    
  }

?>