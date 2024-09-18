<?php
require '../../config/sweet.php';
require_once '../../modelo/classUsuario.php';
require_once '../../config/conexion.php';

$con = new Configuracion();
$con->conectarBD();  
session_start();
$usuario=$_SESSION['nombre_usuario'];

function validarClave($clave) {
    return preg_match('/[A-Z]/', $clave) && // al menos una mayúscula
           preg_match('/[0-9]/', $clave) && // al menos un número
           strlen($clave) >= 8 &&           // mínimo 8 caracteres
           strlen($clave) <= 16;             // máximo 16 caracteres
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $clave_nueva=$_POST['clave_nueva'];
    $clave_conf=$_POST['clave_conf'];

    if (!validarClave($clave_nueva)) {
        echo "<script>
        toastr.error('La contraseña debe tener entre 8 y 16 caracteres, incluir al menos una mayúscula y un número.', 'ERROR');
        </script>";
    } elseif ($clave_nueva === $clave_conf) {
        $clave_md5=md5($clave_nueva);
        $usuario = new Usuario(null, $usuario, $clave_md5, null);
        $consultaUsuario = $usuario->updateClave($con);
        
        if ($consultaUsuario) {
            echo "<script>
            Swal.fire({
            title: 'Aviso',
            text: 'Su contraseña se cambió exitosamente',
            icon: 'success',
            confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../../index.php';
                }
            });
            </script>";
        } else {
            echo "<script>
            toastr.error('No se pudo cambiar la contraseña', 'ERROR');
            </script>";
        }
    } else {
        echo "<script>
        toastr.error('Las contraseñas no coinciden', 'ERROR');
        </script>";
    }
}
?>