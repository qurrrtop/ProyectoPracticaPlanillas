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

    // fila del alumno donde se mostrarán los finales
    const filaFinales = document.getElementById("finales-" + idAlumno);
    const contenedor = filaFinales.querySelector(".finales-container");

    // toggle abrir/cerrar
    if (contenedor.classList.contains("open")) {
        contenedor.classList.remove("open"); // cierra
        return; // sale del click
    } else {
        contenedor.classList.add("open"); // abre
    }

    // --- fetch y carga de finales ---
    const form = new FormData();
    form.append("idAlumno", idAlumno);
    form.append("idMateria", idMateria);
    form.append("anio", anio);

    contenedor.innerHTML = "<em>Cargando...</em>";

    try {
        const resp = await fetch(
            "index.php?controller=Coordinador&action=getExamen",
            { method: "POST", body: form, headers: { "X-Requested-With": "XMLHttpRequest" } }
        );
        const data = await resp.json();

        if (!data.success) {
            contenedor.innerHTML = `<p class="no-data">${data.message}</p>`;
            return;
        }

        const finales = data.finales;
        let html = `<strong>Exámenes finales - ${fila.dataset.nombre} ${fila.dataset.apellido}</strong>
                    <table class="finales-table">
                        <thead><tr><th>Intento</th><th>Nota</th><th>Fecha</th></tr></thead>
                        <tbody>`;

        if (finales.length === 0) {
            html += `<tr><td colspan="3" class="no-data">Sin registros</td></tr>`;
        } else {
            finales.forEach(f => {
                html += `<tr>
                            <td>${f.oportunidad}</td>
                            <td>${f.nota}</td>
                            <td>${f.fechaExamen}</td>
                         </tr>`;
            });
        }

        html += "</tbody></table>";
        contenedor.innerHTML = html;

    } catch (err) {
        console.error(err);
        contenedor.innerHTML = "<p class='no-data'>Error al cargar los finales.</p>";
    }
  });
});
