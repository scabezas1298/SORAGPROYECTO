<?php
session_start();

// Eliminar todas las variables de sesión
$_SESSION = array();

// Invalidar la cookie de sesión
if (isset($_COOKIE['session_token'])) {
    unset($_COOKIE['session_token']);
    setcookie('session_token', '', time() - 3600, '/');
}

// Finalizar la sesión
unset($_SESSION['nombre_usuario']);
header('Location: ../../controlador/ciudadano/clear-localstorage.php');
session_destroy();

// Redirigir al formulario de login o a la página principal

exit;
?>
