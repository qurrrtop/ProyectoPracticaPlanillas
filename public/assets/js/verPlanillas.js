// Script para manejar select año -> materia en la vista verPlanillas
// recibe los datos mandados desde la vista a traves de los window.
// llena el select de materias segun el año seleccionado y mantiene las selecciones previas al recargar.
(function () {
  "use strict";

  // ejecuta cuando el dom este listo
  document.addEventListener("DOMContentLoaded", function () {
    //datos recibidos desde la vista
    const materiasPorAnio = window.MATERIAS_POR_ANIO || {};
    // selects de la vista
    const selectAnio = document.getElementById("anio");
    const selectMateria = document.getElementById("materia");
    //limpia las opciones del select materia
    function clearMateriaOptions() {
      if (!selectMateria) return;
      selectMateria.innerHTML =
        '<option value="">-- Seleccione una materia --</option>';
    }
    //rellena el select de materias segun el año seleccionado
    //selectid sirve para marca una materia como seleccionada
    function populateMaterias(anio, selectedId) {
      if (!selectMateria) return;
      clearMateriaOptions();

      const anioKey = String(anio);

      if (!anioKey || !materiasPorAnio[anioKey]) return;

      materiasPorAnio[anioKey].forEach(function (m) {
        const opt = document.createElement("option");
        opt.value = m.idMateria ?? m.id ?? "";
        opt.textContent = m.nombre ?? m.materia ?? "Materia";
        if (String(opt.value) === String(selectedId)) opt.selected = true;
        selectMateria.appendChild(opt);
      });
    }
    //si se cambia el año seleccionado, recarga las materias
    if (selectAnio) {
      selectAnio.addEventListener("change", function () {
        populateMaterias(this.value, null);
      });
    }

    // variables traidas de la vista
    const selectedAnio = window.SELECTED_ANIO ?? null;
    const selectedIdMateria = window.SELECTED_IDMATERIA ?? null;

    //si hay año seleccionado, cargar materias
    if (selectedAnio) {
      if (selectAnio) selectAnio.value = selectedAnio;
      populateMaterias(selectedAnio, selectedIdMateria);
    } else if (selectedIdMateria) {
      // si hay idMateria pero no año, buscar el año que contiene esa materia
      for (const anioKey in materiasPorAnio) {
        if (!Object.prototype.hasOwnProperty.call(materiasPorAnio, anioKey))
          continue;
        const found = materiasPorAnio[anioKey].some(function (m) {
          return (
            String(m.idMateria ?? m.id ?? "") === String(selectedIdMateria)
          );
        });
        if (found) {
          if (selectAnio) selectAnio.value = anioKey;
          populateMaterias(anioKey, selectedIdMateria);
          break;
        }
      }
    } else {
      // nada seleccionado, limpiar posibles opciones
      clearMateriaOptions();
    }
  });
})();

document.addEventListener("DOMContentLoaded", function () {
  const tabla = document.getElementById("tablaAlumnos");
  if (!tabla) return;

  tabla.addEventListener("click", async function (ev) {
    const fila = ev.target.closest(".fila-alumno");
    if (!fila) return;

    const idAlumno = fila.dataset.id;
    const nombre = fila.dataset.nombre;
    const apellido = fila.dataset.apellido;

    const idMateria = "<?= $idMateria ?>";
    const anio = "<?= $anio ?>";

    const filaDetalles = document.getElementById("finales-" + idAlumno);
    const contenedor = filaDetalles.querySelector("[data-container-for]");

    // toggle si ya estaba visible
    if (filaDetalles.style.display !== "none") {
      filaDetalles.style.display = "none";
      return;
    }

    // mostrar loader
    filaDetalles.style.display = "";
    contenedor.innerHTML = "<em>Cargando...</em>";

    // AJAX POST → controlador
    const form = new FormData();
    form.append("idAlumno", idAlumno);
    form.append("idMateria", idMateria);
    form.append("anio", anio);

    const resp = await fetch(
      "index.php?controller=Coordinador&action=getExamen",
      {
        method: "POST",
        body: form,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      }
    );

    const data = await resp.json();

    if (!data.success) {
      contenedor.innerHTML = `<p class='no-data'>${data.message}</p>`;
      return;
    }

    const finales = data.finales;

    // render tabla
    let html = `
        <strong>Detalles de exámenes finales - ${nombre} ${apellido}</strong>
        <table class="finales-table">
            <thead>
                <tr><th>Intento</th><th>Nota</th><th>Fecha</th></tr>
            </thead>
            <tbody>
        `;

    if (finales.length === 0) {
      html += `<tr><td colspan="3" class="no-data">No hay exámenes finales registrados</td></tr>`;
    } else {
      finales.forEach((f) => {
        html += `
                <tr>
                    <td>${f.oportunidad}</td>
                    <td>${f.nota}</td>
                    <td>${f.fechaExamen}</td>
                </tr>`;
      });
    }

    html += `</tbody></table>`;
    contenedor.innerHTML = html;
  });
});
