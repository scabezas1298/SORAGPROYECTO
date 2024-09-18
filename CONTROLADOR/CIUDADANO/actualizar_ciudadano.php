<?php
require_once '../../config/sweet.php';
require_once '../../modelo/classCiudadano.php';
require_once '../../modelo/classUsuario.php';
require_once '../../config/conexion.php';


$con = new Configuracion();
$con->conectarBD();   

if (isset($_POST['btnactualizar'])) {
    $cedula = $_SESSION['cedula'];

    $telefonov = test_input($_POST["telefono"]);
    if ((strlen($telefonov) < 6 && strlen($telefonov) > 10)|| !is_numeric($telefonov)) {
        $telefonoErr = "El número de telefono debe tener 10 dígitos numéricos";
    }

    // Validar el correo electrónico
    $correov = test_input($_POST["correo"]);
    if (!filter_var($correov, FILTER_VALIDATE_EMAIL)) {
        $correoErr = "Correo electrónico inválido";
    } else {
        // Verificar si la cédula ya está registrada
        $stmt = $con->consulta("SELECT COUNT(*) AS count FROM ciudadano WHERE email = '$correov' and cedula!='$cedula'");
        if ($stmt[0]["count"] > 0) {
            $correoErr = "El correo ya se encuentra registrado";
        }
    }

    if (!isset($correoErr) && !isset($telefonoErr)) {

       
        $correo = $_POST["correo"];
        $telefono = $_POST["telefono"];
        $direccion = $_POST["direccion"];
        $tipoSeguro = $_POST["tipoSeguro"];
        $clave=$_POST["clave"];
        $clave_md5=md5($clave);

        $ciudadano = new Ciudadano(null,$cedula,null,null,$correo,$telefono,$direccion,null,null,$tipoSeguro,null,null,null);
        $consultaCiudadano = $ciudadano->updateCiudadano($con);
        if($clave!=null)
        {
            $usuario=new Usuario(null,$cedula,$clave_md5,null);
            $consultaUsuario = $usuario->updateUsuario($con);
        }
        
        if($consultaCiudadano){
            echo "<script>
            Swal.fire({
            title: 'Aviso',
            text: 'Se ha actualizado sus datos',
            icon: 'success',
            confirmButtonText: 'Aceptar'
            });
            </script>";
        }
    }else{
        echo "<script>
        var messages = '';
        ";
        if (isset($correoErr)) {
            echo "messages += '{$correoErr}<br>'; ";
        }
        if (isset($telefonoErr)) {
            echo "messages += '{$telefonoErr}<br>'; ";
        }
        echo "
        if (messages) {
            toastr.error(messages, 'Error en el registro', {
                closeButton: true,
                progressBar: true
            });
        }
        </script>";
    }      
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>