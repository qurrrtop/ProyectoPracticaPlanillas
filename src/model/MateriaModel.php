<?php

  class MateriaModel {

    private $idMateria;
    private $nombre;
    private $curso;
    private $año;

    public function getIDMateria() {
      return $this->idMateria;
    }

    public function getNombre() {
      return $this->nombre;
    }

    public function getCurso() {
      return $this->curso;
    }

    public function getAño() {
      return $this->año;
    }
  
    
  }

?>