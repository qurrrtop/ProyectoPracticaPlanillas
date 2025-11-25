document.addEventListener("DOMContentLoaded", function () {
    const anioSelect = document.getElementById("anio");
    const materiaSelect = document.getElementById("materia");

    const materiasPorAnio = window.MATERIAS_POR_ANIO || {};

    function actualizarMaterias() {
      const anioSeleccionado = anioSelect.value;
      const materias = materiasPorAnio[anioSeleccionado] || [];

      // Limpiar opciones
      materiaSelect.innerHTML = '<option value="">-- Seleccione una materia --</option>';

      // Agregar materias correspondientes
      materias.forEach(m => {
          const val = m.idMateria || m.id || '';
          const label = m.nombre || m.materia || 'Materia';
          const option = document.createElement("option");
          option.value = val;
          option.textContent = label;
          materiaSelect.appendChild(option);
      });

      // Restaurar la materia previamente seleccionada si existe y está disponible
      const selectedIdMateria = window.SELECTED_IDMATERIA || "";

      if (selectedIdMateria && materias.some(m => (m.idMateria || m.id) == selectedIdMateria)) {
          materiaSelect.value = selectedIdMateria;
      } else if (materias.length > 0) {
          // Si no coincide, seleccionar la primera materia del año
          materiaSelect.value = materias[0].idMateria || materias[0].id;
      } else {
          // Si no hay materias, dejar el placeholder
          materiaSelect.value = "";
      }
  }

    if (anioSelect) {
        // actualizar al cargar la página
        actualizarMaterias();

        // actualizar al cambiar año
        anioSelect.addEventListener("change", actualizarMaterias);
    }
});
