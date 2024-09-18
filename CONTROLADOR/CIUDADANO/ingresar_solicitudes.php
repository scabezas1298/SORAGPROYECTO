<?php
require '../../config/sweet.php';
require '../../modelo/classSolicitud.php';
$con = new Configuracion();
$con->conectarBD();  

if (isset($_POST['btningresar'])) {
    $cedula = $_POST["cedula_usuario"];
    $emerg_x = $_POST["cx"];
    $emerg_y = $_POST["cy"];
    $num_pacientes = $_POST["grupo1"];
    $tipo_emergencia = $_POST["grupo2"];
    $foto = $_SESSION['current_image'];
    
    // Crear la solicitud
    $solicitud = new Solicitud(null, $cedula, $emerg_x, $emerg_y, $num_pacientes, $tipo_emergencia, $foto);
    
    // Insertar la solicitud y obtener el resultado
    $consultaSolicitud = $solicitud->insertarSolicitud($con);
    
    if ($consultaSolicitud) {
        // Obtener el ID de la solicitud recién ingresada
        $id_solicitud = $consultaSolicitud; // Asumiendo que insertarSolicitud devuelve el ID
        
        // Insertar la solicitud nodo usando el ID de la solicitud en formato string
        $consultaSolicitudNodo = $solicitud->insertarSolicitudNodo($con, $id_solicitud);
        
        echo "<script>
        Swal.fire({
            title: 'Aviso',
            text: 'Solicitud ingresada con éxito',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'ciudadano.php'; 
            }
        });
        </script>";
    } else {
        echo "<script>
        Swal.fire({
            title: 'Error',
            text: 'Error al ingresar solicitud',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        </script>";
    }
}
?>