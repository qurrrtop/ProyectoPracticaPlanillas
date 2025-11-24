const modal = document.getElementById("modalMensaje");
const btnCerrar = document.getElementById("cerrarModal");

if (modal) {
    btnCerrar.addEventListener("click", () => {
        modal.style.display = "none";
    });
}

const toggles = document.querySelectorAll('.toggle');

toggles.forEach(toggle => {

    toggle.addEventListener('click', () => {

        const id = toggle.dataset.id;
        const fila = document.getElementById('materias-' + id);
        const content = fila.querySelector('.fila-content');

        // Alternar clase del contenido
        const isOpen = content.classList.contains('open');
        content.classList.toggle('open');

        // Obtener el icono
        const arrow = toggle.querySelector('.fa-angle-right');

        // Rotar el icono según el estado
        if (!isOpen) {
            arrow.classList.add('open');   // rota 90°
        } else {
            arrow.classList.remove('open'); // vuelve a la posición original
        }

    });

});