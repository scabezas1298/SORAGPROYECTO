<?php
require 'controlador/index/login.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SORAG</title>
    <link rel="stylesheet" href="public/css/index/estilo_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    
</head>
<body>

    <form  method="post" class="form-register">
        <h2 class="form-titulo">SISTEMA SORAG</h2>
        <div class="contenedor-inputs">

            <input type="text" placeholder="Ingresa tu usuario" name="usuario" class="input-100" required >

            <div class="password-container">
            <input type="password" placeholder="Ingresa tu contraseña" name="clave"  required>
            <span class="password-toggle-icon">
                <i class="fas fa-eye-slash"></i>
            </span>
            </div>


            <input type="submit" value="Iniciar Sesión" class="btn-enviar" name="btnlogin">
            <p class="form-link"><a href="vistas/index/recuperar_clave.php">¿Olvidaste tu contraseña?</a></p>
            <p class="form-link">¿Aún no tienes una cuenta? <a href="vistas/index/registro.php">Regístrate aquí</a></p>
        </div>
    </form>
<script src="public/js/index/index.js"></script>
</body>
</html>
