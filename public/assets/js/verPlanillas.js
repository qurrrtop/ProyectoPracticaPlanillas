// Script para manejar select año -> materia en la vista verPlanillas
// recibe los datos mandados desde la vista a traves de los window.
// llena el select de materias segun el año seleccionado y mantiene las selecciones previas al recargar.
(function () {
  'use strict';

  // ejecuta cuando el dom este listo
  document.addEventListener( 'DOMContentLoaded', function () {
    //datos recibidos desde la vista
    const materiasPorAnio = window.MATERIAS_POR_ANIO || {};
    // selects de la vista
    const selectAnio = document.getElementById( 'anio' );
    const selectMateria = document.getElementById( 'materia' );
    //limpia las opciones del select materia
    function clearMateriaOptions() {
      if ( !selectMateria ) return;
      selectMateria.innerHTML = '<option value="">-- Seleccione una materia --</option>';
    }
    //rellena el select de materias segun el año seleccionado
    //selectid sirve para marca una materia como seleccionada
    function populateMaterias(anio, selectedId) {
      if (!selectMateria) return;
      clearMateriaOptions();

      const anioKey = String(anio);

      if (!anioKey || !materiasPorAnio[anioKey]) return;

      materiasPorAnio[anioKey].forEach(function (m) {
        const opt = document.createElement('option');
        opt.value = m.idMateria ?? m.id ?? '';
        opt.textContent = m.nombre ?? m.materia ?? 'Materia';
        if (String(opt.value) === String(selectedId)) opt.selected = true;
        selectMateria.appendChild(opt);
      });
    }
    //si se cambia el año seleccionado, recarga las materias
    if ( selectAnio ) {
      selectAnio.addEventListener( 'change', function () {
        populateMaterias( this.value, null );
      } );
    }

    // variables traidas de la vista 
    const selectedAnio = window.SELECTED_ANIO ?? null;
    const selectedIdMateria = window.SELECTED_IDMATERIA ?? null;

    //si hay año seleccionado, cargar materias
    if ( selectedAnio ) {
      if ( selectAnio ) selectAnio.value = selectedAnio;
      populateMaterias( selectedAnio, selectedIdMateria );
    } else if ( selectedIdMateria ) {
      // si hay idMateria pero no año, buscar el año que contiene esa materia
      for ( const anioKey in materiasPorAnio ) {
        if (!Object.prototype.hasOwnProperty.call( materiasPorAnio, anioKey ) ) continue;
        const found = materiasPorAnio[anioKey].some( function ( m ) {
          return String(m.idMateria ?? m.id ?? '') === String(selectedIdMateria);
        } );
        if ( found ) {
          if ( selectAnio ) selectAnio.value = anioKey;
          populateMaterias( anioKey, selectedIdMateria );
          break;
        }
      }
    } else {
      // nada seleccionado, limpiar posibles opciones
      clearMateriaOptions();
    }
  } );
} ) ( );