<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <script>
        // Borrar localStorage
        localStorage.removeItem('activePage');
        
        // Redirigir a la página de inicio o de login después de borrar localStorage
        window.location.href = '../../index.php';  // Cambia esto a la URL de tu página de inicio o login
    </script>
</body>
</html>
