<?php
    require_once '../../vendor/autoload.php';
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    use Firebase\JWT\ExpiredException;
    $key = 'Chelseafc12';
    session_start();

    // Verificar si hay una sesión activa
    if (!isset($_SESSION['id_usuario']) || !isset($_COOKIE['session_token'])) {
        // Si no hay sesión activa, redirigir al formulario de login o a una página de logout
        header('Location: ../../controlador/ciudadano/logout.php');
        exit;
    }

    // Verificar si la cookie de sesión ha expirado
  
    try {
      // Decodificar el token JWT
    $token = $_COOKIE['session_token'];
    $decoded = JWT::decode($token, new key($key, 'HS256'));
    } catch (ExpiredException $e) {
        // Manejo específico para token expirado
        echo 'Token JWT expirado: ' . $e->getMessage();
        // Redirigir a la página de login, por ejemplo
        header('Location: ../../controlador/ciudadano/logout.php');
        exit;
    
    } catch (Exception $e) {
        // Otro tipo de errores al decodificar el token
        echo 'Error al decodificar el token: ' . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SORAG</title>
    <link rel="stylesheet" href="../../public/css/ciudadano/estilo_ciudadano.css">
    <link rel="stylesheet" href="../../public/css/ciudadano/solicitud.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
</head>
<body>

<header>
    <div class="logo"><img src="../../public/img/logo.png"></div>
    <div class="bars">
       <div class="line"></div>
       <div class="line"></div>  
       <div class="line"></div>  
       <div class="line"></div>       
    </div>

    <nav class="nav-bar">
        <ul>
            <li>
                <a href="../../controlador/ciudadano/logout.php" > <i class="fa-solid fa-arrow-right-from-bracket" style="color:red"></i></a>
            </li>
        </ul>
    </nav>
</header>
