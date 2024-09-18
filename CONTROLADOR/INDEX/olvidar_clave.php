<?php
require '../../config/sweet.php';
require_once '../../modelo/classCiudadano.php';
require_once '../../modelo/classUsuario.php';
require_once '../../config/conexion.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$validador = new Tavo\ValidadorEc;
$con = new Configuracion();
$con->conectarBD();  
$errors = [];
$values = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $val_cedula=test_input($_POST["cedula"]);
    if (empty($_POST['cedula'])) {
        $errors[] = "La cédula es obligatoria.";
    } else {
        if ($validador->validarCedula($_POST["cedula"])) {
            $stmt = $con->consulta("SELECT COUNT(*) AS count FROM ciudadano WHERE cedula = '$val_cedula' and estado=1");
            if ($stmt[0]["count"] == 0) {
                $errors['cedula'] = "El número de cédula no se encuentra registrado";
            }
        } else {
            $errors['cedula'] = "Cédula incorrecta: ".$validador->getError();
        }
        $values['cedula'] = $_POST['cedula'];
    }

    // Si no hay errores, procesar el formulario
    if (empty($errors)) {
        // Aquí puedes agregar el código para guardar los datos en la base de datos
        $cedula = $_POST["cedula"];
        session_start();
        $_SESSION['nombre_usuario']=$cedula;
        $clave=generarClave(10);
        $clave_md5=md5($clave);

        $ciudadano = new Ciudadano(null,$cedula,null,null,null,null,null,null,null,null,null,null,null);
        $correoCiudadano = $ciudadano->obtenerCorreo($con);
        $usuario=new Usuario(null,$cedula,$clave_md5,null);
        $consultaUsuario = $usuario->recuperarClave($con);
        $estado=$usuario->obtenerEstado($con);
        
        if($estado==1){
            if($consultaUsuario){
                $mail = new PHPMailer(true);
                try {
                    // Configuración del servidor SMTP de Gmail
                    $mail->SMTPDebug=0;
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'soragecu@gmail.com'; // Cambia esto a tu dirección de correo de Gmail
                    $mail->Password = 'sywe fjcd npvu zibx'; // Cambia esto a tu contraseña de Gmail o App Password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
        
                    // Remitente y destinatario
                    $mail->setFrom('soragecu@gmail.com', 'SORAG'); // Cambia esto a tu dirección de correo de Gmail
                    $mail->addAddress($correoCiudadano);
        
                    // Adjuntar imagen del logo
                    $mail->addEmbeddedImage('../../public/img/logo.png', 'logo_img'); // Cambia esto a la ruta de tu logo
        
                    // Contenido del correo
                    $mail->isHTML(true);
                    $mail->Subject = 'Recuperacion Exitosa en SORAG';
                    $mail->Body    = '<img src="cid:logo_img" alt="SORAG Logo"><br>Hola,<br><br>Ha recuperado su clave en SORAG.<br><br>Tu clave temporal es :
                    <br>Clave: ' . $clave . '<br><br>Saludos,<br>Equipo SORAG';
        
                    $mail->send();
                    
                    echo "<script>
                    Swal.fire({
                    title: 'Clave temporal',
                    text: 'Tu clave temporal ha sido enviado al correo',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../../vistas/index/actualizar_clave.php'; 
                    }
                    });
                    </script>";
                    
            
                } catch (Exception $e) {
                    echo "<script>
                    Swal.fire({
                    title: 'Credenciales',
                    text: 'Recuperación exitosa pero no se pudo enviar el correo. Mailer Error: {$mail->ErrorInfo}',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../../index.php'; 
                    }
                    });
                    </script>";
                }
        
            }  
        
        } else{
            echo "<script>
                toastr.error('La cuenta debe estar activa. Revise en su correo las credenciales de inicio.', 'ERROR');
                setTimeout(function() {
                    window.location.href = '../../index.php';
                }, 3000); 
                </script>";
        }

    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generarClave($length)
{
    $key = "";
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
    $max = strlen($pattern)-1;
    for($i = 0; $i < $length; $i++){
        $key .= substr($pattern, mt_rand(0,$max), 1);
    }
    return $key;
}

?>