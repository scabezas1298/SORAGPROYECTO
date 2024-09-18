<?php
require '../../controlador/index/olvidar_clave.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SORAG</title>
    <link rel="stylesheet" href="../../public/css/index/estilo_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    
</head>
<body>

    <form  method="post" class="form-register">
    
        <h2 class="form-titulo">
            <button class="back-button" onclick="goBack()">
            <i class="fa-solid fa-arrow-left"></i>
        </button>  
        SISTEMA SORAG</h2>

        <div class="contenedor-inputs">


            <input type="text" placeholder="Ingresa tu número de cédula" name="cedula" class="input-100" required>

            <input type="submit" value="Recuperar clave" class="btn-enviar" name="btnrecuperar">
        </div>
    </form>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>