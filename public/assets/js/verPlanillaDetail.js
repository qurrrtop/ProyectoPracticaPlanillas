// Script para expandir/contraer filas de finales y cargar exámenes dinámicamente

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    // Todas las filas que representen alumnos
    const filas = document.querySelectorAll('#tablaAlumnos tr.fila-alumno');

    filas.forEach(fila => {
      fila.addEventListener('click', function () {
        const idCursada = this.getAttribute('data-id');
        const filaFinales = document.getElementById('finales-' + idCursada);

        if (filaFinales) {
          // toggle: mostrar/ocultar
          const isVisible = filaFinales.style.display !== 'none';
          filaFinales.style.display = isVisible ? 'none' : 'table-row';

          // si se abre y aún no tiene datos, cargar los exámenes
          if (!isVisible) {
            cargarExamenes(idCursada);
          }
        }
      });

      // cambiar cursor a pointer para indicar que es clickeable
      fila.style.cursor = 'pointer';
    });

    // Cargar exámenes vía AJAX
    function cargarExamenes(idCursada) {
      const tbody = document.querySelector('#finales-' + idCursada + ' .tabla-finales tbody');
      if (!tbody) return;

      fetch('index.php?controller=Coordinador&action=getExamenesCursada&idCursada=' + idCursada)
        .then(r => r.json())
        .then(data => {
          if (data.exitos && data.examenes && data.examenes.length > 0) {
            tbody.innerHTML = '';
            data.examenes.forEach(ex => {
              const row = document.createElement('tr');
              row.innerHTML = `<td>${ex.oportunidad}</td><td>${ex.nota}</td><td>${ex.fechaExamen}</td>`;
              tbody.appendChild(row);
            });
          } else {
            tbody.innerHTML = '<tr><td colspan="3"><em>Sin exámenes registrados</em></td></tr>';
          }
        })
        .catch(err => {
          console.error('Error cargando exámenes:', err);
          tbody.innerHTML = '<tr><td colspan="3"><em style="color: red;">Error al cargar</em></td></tr>';
        });
    }
  });
})();