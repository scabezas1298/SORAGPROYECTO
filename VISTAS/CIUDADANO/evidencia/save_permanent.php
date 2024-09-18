<?php
require '../../../config/sweet.php';
session_start();

if (isset($_SESSION['temp_image'])) {
    $tempImage = $_SESSION['temp_image'];
    
    // Generar un nombre de archivo único para la imagen permanente
    $permanentFile = 'img/permanent/' . basename($tempImage);
    
    // Crear el directorio de imágenes permanentes si no existe
    if (!file_exists('img/permanent')) {
        mkdir('img/permanent', 0777, true);
    }

    // Mover la imagen temporal a la ubicación permanente
    if (rename($tempImage, $permanentFile)) {
        // Limpiar la sesión
        unset($_SESSION['temp_image']);
        $_SESSION['current_image']=$permanentFile;
        echo "<script>
        Swal.fire({
            title: 'Éxito',
            text: 'La imagen ha sido guardada permanentemente.',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../ciudadano.php'; 
            }
        });
        </script>";
    } else {
        echo "<script>
        Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al guardar la imagen permanentemente.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        </script>";
    }
} else {
    echo "<script>
    Swal.fire({
        title: 'Error',
        text: 'No hay imagen para guardar.',
        icon: 'error',
        confirmButtonText: 'Aceptar'
    });
    </script>";
}
?>
