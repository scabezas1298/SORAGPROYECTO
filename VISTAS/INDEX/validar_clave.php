<?php
require '../../controlador/index/validacion_clave.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SORAG</title>
    <link rel="stylesheet" href="../../public/css/index/estilo_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    
</head>
<body>

    <form  method="post" class="form-register">
        <h2 class="form-titulo">SISTEMA SORAG</h2>
        <div class="contenedor-inputs">

            <div class="password-container">
            <input type="password" placeholder="Ingresa tu nueva contraseña" name="clave_nueva" class="input-100" required>
            <span class="password-toggle-icon">
                <i class="fas fa-eye-slash"></i>
            </span>
            </div>

            <div class="password-container">
            <input type="password" placeholder="Confirma tu nueva contraseña" name="clave_conf" class="input-100" required>
            <span class="password-toggle-icon">
                <i class="fas fa-eye-slash"></i>
            </span>
            </div>

            <input type="submit" value="Cambiar clave" class="btn-enviar" name="btnnclave">
        </div>
    </form>
    <script src="../../public/js/INDEX/val_registro.js"></script>
</body>
</html>
