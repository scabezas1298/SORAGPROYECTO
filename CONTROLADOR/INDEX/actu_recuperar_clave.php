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
    $clave_temp=md5($_POST['clave_temp']);
    $clave_nueva=$_POST['clave_nueva'];
    $clave_conf=$_POST['clave_conf'];

    $configuracion = new Configuracion();
    $conexion = $configuracion->conectarBD();

                // Preparar consulta usando un prepared statement para evitar SQL injection
                $query = "SELECT * FROM usuario WHERE nombre_usuario = ? AND clave_usuario_final= ?";
                $uini = $conexion->prepare($query);
                $uini->bind_param('ss', $usuario, $clave_temp);
                $uini->execute();
                $uini->store_result();
                // Verificar si se encontró un usuario
                if ($uini->num_rows == 1) {
                    $_SESSION['nombre_usuario'] = $usuario;
                    if($clave_nueva==$clave_conf){
                        $clave_md5=md5($clave_nueva);
                        $usuario=new Usuario(null,$usuario,$clave_md5,null);
                        $consultaUsuario = $usuario->actualizarClave($con);
                        if($consultaUsuario){
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
                        } else{
                            echo "<script>
                                    toastr.error('No se pudo cambiar la contraseña.', 'ERROR');
                                    setTimeout(function() {
                                }, 2000); 
                                </script>";
                        }
                    } else{
                        echo "<script>
                        toastr.error('Las contraseñas no coinciden.', 'ERROR');
                        setTimeout(function() {
                        }, 2000); 
                        </script>";
                    }
                } else{
                    echo "<script>
                        toastr.error('La clave temporal no coincide con la base de datos.', 'ERROR');
                        setTimeout(function() {
                        }, 2000); 
                        </script>";
                }

    
        
        
    
       
       
}
?>