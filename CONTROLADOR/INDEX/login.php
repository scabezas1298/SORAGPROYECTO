<?php
    // Incluir archivo de conexión
    require 'config/sweet.php';
    require_once 'config/conexion.php';
    require_once 'vendor/autoload.php';
    use Firebase\JWT\JWT;
    
    // Iniciar sesión (si no está iniciada)
    session_start();

    // Verificar si ya hay una sesión activa, redirigir si es así
    if (isset($_SESSION['id_usuario'])) {
        $rol=$_SESSION['rol'];
        switch ($rol) {
            case 1:
                header('Location: vistas/ciudadano/ciudadano.php');
                break;
            case 2:
                header('Location: dashboard/index.php');
                break;
            case 3:
                header('Location: vistas/conductor/conductor.php');
                break;
            default:
                header('Location: index.php'); // Redirigir a una página por defecto
                break;
        } // Redirigir a la página de dashboard u otra página de inicio
        exit;
    }

    // Variables para almacenar mensajes de error y datos del formulario
    $error = '';
    $usuario = '';

    // Verificar si se envió el formulario
    if (isset($_POST['btnlogin'])) {
        // Obtener datos del formulario
        $usuario = $_POST['usuario'];
        $clave = $_POST['clave'];

        // Validar el formulario (ejemplo básico)
        if (empty($usuario) || empty($clave)) {
            $error = 'Por favor ingrese usuario y contraseña.';
            echo "<script>
                toastr.error('$error', 'Error');
            </script>";
        } else {
            // Realizar consulta para verificar usuario y contraseña
            try {
                // Crear instancia de Configuracion para conectarse a la base de datos
                $configuracion = new Configuracion();
                $conexion = $configuracion->conectarBD();

                // Preparar consulta usando un prepared statement para evitar SQL injection
                $query = "SELECT id_usuario, nombre_usuario, rol FROM usuario WHERE nombre_usuario = ? AND clave_usuario_inicio = ?";
                $uini = $conexion->prepare($query);
                $uini->bind_param('ss', $usuario, $clave);
                $uini->execute();
                $uini->store_result();
                // Verificar si se encontró un usuario
                if ($uini->num_rows == 1) {
                    $_SESSION['nombre_usuario'] = $usuario;
                    header('Location: vistas/index/validar_clave.php');
                } 

                $clave_md5=md5($clave);
                $query2 = "SELECT id_usuario, nombre_usuario, rol FROM usuario WHERE nombre_usuario = ? AND clave_usuario_final = ?";
                $ufin = $conexion->prepare($query2);
                $ufin->bind_param('ss', $usuario, $clave_md5);
                $ufin->execute();
                $ufin->store_result();
    
                $query3 = "SELECT id_ciudadano,  nombres FROM ciudadano WHERE cedula = ? ";
                $stmt = $conexion->prepare($query3);
                $stmt->bind_param('s', $usuario);
                $stmt->execute();
                $stmt->store_result();

                if ($ufin->num_rows == 1) {
                        // Obtener resultados
                        $ufin->bind_result($id_usuario, $nombre_usuario, $rol);
                        $ufin->fetch();
    
                        if($stmt->num_rows ==1){
                            $stmt->bind_result($id_ciudadano, $nombres);
                            $stmt->fetch();
                        }
    
                        $key = 'Chelseafc12';
                        $payload = array(
                            "user_id" => $id_usuario,
                            "username" => $nombres,
                            "user_roles" => $rol,
                            "exp" => time() + (4 * 60 * 60) 
                        );
                        $jwt = JWT::encode($payload, $key, 'HS256');
    
                        // Almacenar el token en una cookie
                        setcookie('session_token', $jwt, time() + (4 * 60 * 60), '/',);
    
                        // Guardar variables de sesión
                        $_SESSION['id_usuario'] = $id_usuario;
                        $_SESSION['nombre_usuario'] = $nombres;
                        $_SESSION['cedula']=$nombre_usuario;
                        $_SESSION['rol'] = $rol;
    
                        // Redireccionar según el rol del usuario
                        switch ($rol) {
                            case 1:
                                header('Location: vistas/ciudadano/ciudadano.php');
                                break;

                            case 2:
                                header('Location: dashboard/index.php');
                                break;

                            case 3:
                                    header('Location: vistas/conductor/conductor.php');
                                    break;
                            default:
                                header('Location: index.php'); // Redirigir a una página por defecto
                                break;
                        }
                        exit;
                    }else{
                        $error = 'Usuario o contraseña incorrecta.';
                        echo "<script>
                            toastr.error('$error', 'Error');
                        </script>";
                    }
                // Cerrar statement y conexión
                $stmt->close();
                $configuracion->desconectarDB();       
                
            } catch (Exception $e) {
                $error = 'Error al conectar con la base de datos.';
                echo "<script>
                    toastr.error('$error', 'Error');
                </script>";
        }
    }
}
?>