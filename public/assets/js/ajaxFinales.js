document.addEventListener("DOMContentLoaded", function () {
  const tabla = document.getElementById("tablaAlumnos");
  if (!tabla) return;

  tabla.addEventListener("click", async function (ev) {
    const fila = ev.target.closest(".fila-alumno");
    if (!fila) return;

    const idAlumno = fila.getAttribute("data-id");
    if (!idAlumno) return;

    const idMateria = window.SELECTED_IDMATERIA || "";
    const anio = window.SELECTED_ANIO || "";
    const csrf = window.CSRF_TOKEN || "";

    // fila donde se mostrarán los detalles
    const detallesRow = document.getElementById("finales-" + idAlumno);
    const detallesContainer = detallesRow
      ? detallesRow.querySelector("[data-container-for]")
      : null;

    // si ya está visible → ocultar
    if (detallesRow && detallesRow.style.display !== "none") {
      detallesRow.style.display = "none";
      return;
    }

    // mostrar loader
    if (detallesContainer) {
      detallesContainer.innerHTML = "<em>Cargando finales...</em>";
      detallesRow.style.display = "";
    }

    try {
      // armo el POST
      const form = new FormData();
      form.append("idAlumno", idAlumno);
      form.append("idMateria", idMateria);
      form.append("anio", anio);
      form.append("csrf_token", csrf);

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

      if (!resp.ok) throw new Error("Error HTTP " + resp.status);

      const data = await resp.json();

      if (!data || !data.success) {
        const msg = data && data.message ? data.message : "Sin datos.";
        detallesContainer.innerHTML =
          '<div class="no-data">' + escapeHtml(msg) + "</div>";
        return;
      }

      // render tabla de finales
      detallesContainer.innerHTML = renderFinalesTable(data.finales, fila);
    } catch (err) {
      console.error(err);
      if (detallesContainer)
        detallesContainer.innerHTML =
          '<div class="no-data">Error al cargar los finales.</div>';
    }
  });

  function renderFinalesTable(finales, fila) {
    const nombre = fila.getAttribute("data-nombre") || "";
    const apellido = fila.getAttribute("data-apellido") || "";

    if (!finales || finales.length === 0) {
      return `
        <strong>Detalles de exámenes finales - ${escapeHtml(
          nombre
        )} ${escapeHtml(apellido)}</strong>
        <table class="finales-table">
          <thead><tr><th>Intento</th><th>Nota</th><th>Fecha</th></tr></thead>
          <tbody><tr><td colspan="3" class="no-data">No hay exámenes finales registrados</td></tr></tbody>
        </table>
      `;
    }

    let rows = finales
      .map((f) => {
        return `
        <tr>
          <td>${escapeHtml(f.oportunidad)}</td>
          <td>${escapeHtml(f.nota)}</td>
          <td>${escapeHtml(f.fechaExamen)}</td>
        </tr>`;
      })
      .join("");

    return `
      <strong>Detalles de exámenes finales - ${escapeHtml(
        nombre
      )} ${escapeHtml(apellido)}</strong>
      <table class="finales-table">
        <thead><tr><th>Intento</th><th>Nota</th><th>Fecha</th></tr></thead>
        <tbody>${rows}</tbody>
      </table>
    `;
  }

  function escapeHtml(str) {
    if (str === null || str === undefined) return "";
    return String(str).replace(/[&<>"'`=\/]/g, function (s) {
      return {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;",
        "/": "&#x2F;",
        "`": "&#x60;",
        "=": "&#x3D;",
      }[s];
    });
  }
});
