const passwordInput = document.querySelector('.password-container input[name="clave"]');
  const passwordToggleIcon = document.querySelector('.password-container .password-toggle-icon i');

  // Añadir un evento de clic al icono
  passwordToggleIcon.addEventListener('click', () => {
    // Obtener el tipo actual del campo de contraseña
    const currentType = passwordInput.type;

    // Alternar entre 'password' y 'text' para mostrar u ocultar la contraseña
    passwordInput.type = currentType === 'password' ? 'text' : 'password';

    // Alternar la clase del icono entre 'fa-eye' y 'fa-eye-slash'
    passwordToggleIcon.classList.toggle('fa-eye');
    passwordToggleIcon.classList.toggle('fa-eye-slash');
  });