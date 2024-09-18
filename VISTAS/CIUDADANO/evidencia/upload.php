<?php
require '../../../config/sweet.php';
session_start(); // Inicia la sesión para usar variables de sesión

if (isset($_POST['image'])) {
    $image = $_POST['image'];

    // Validar que la imagen tenga el formato esperado
    if (preg_match('/^data:image\/png;base64,/', $image)) {
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $data = base64_decode($image);

        // Generar un nombre de archivo único y establecer el path
        $file = 'img/' . uniqid() . '.png';
        
        // Verificar si el directorio existe
        if (!file_exists('img')) {
            mkdir('img', 0777, true);
        }

        // Si ya hay una imagen temporal guardada, eliminarla
        if (isset($_SESSION['temp_image']) && file_exists($_SESSION['temp_image'])) {
            unlink($_SESSION['temp_image']);
        }

        // Guardar la imagen temporal en el servidor
        if (file_put_contents($file, $data)) {
            // Guardar el nombre del archivo temporal en la sesión
            $_SESSION['temp_image'] = $file;

            header('Location: save_permanent.php');
          
        } else {
            echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar la imagen.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            </script>";
        }
    } else {
        echo "<script>
        Swal.fire({
            title: 'Error',
            text: 'El formato de la imagen no es válido.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        </script>";
    }
}
?>
