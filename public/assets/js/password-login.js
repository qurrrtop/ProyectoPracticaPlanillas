const togglePassword = document.querySelector('#togglePassword');
const passwordInput = document.querySelector('.inp-pass');

 togglePassword.addEventListener('click', function() {
        // Cambiar tipo de input
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Cambiar icono dentro del botón
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
  
});