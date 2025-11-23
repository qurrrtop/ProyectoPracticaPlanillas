const modal = document.getElementById("modalMensaje");
const btnCerrar = document.getElementById("cerrarModal");

if (modal) {
    btnCerrar.addEventListener("click", () => {
        modal.style.display = "none";
    });
}