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
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar cédula
    $val_cedula=test_input($_POST["cedula"]);
    if (empty($_POST['cedula'])) {
        $errors[] = "La cédula es obligatoria.";
    } else {
        if ($validador->validarCedula($_POST["cedula"])) {
            $stmt = $con->consulta("SELECT COUNT(*) AS count FROM ciudadano WHERE cedula = '$val_cedula' and estado=1");
            if ($stmt[0]["count"] > 0) {
                $errors['cedula'] = "El número de cédula ya está asociado a una cuenta";
            }
        } else {
            $errors['cedula'] = "Cédula incorrecta: ".$validador->getError();
        }
        $values['cedula'] = $_POST['cedula'];
    }

    // Validar nombres
    if (empty($_POST["nombres"]) || !preg_match("/^[A-ZÁÉÍÓÚ\s]+$/", $_POST["nombres"])) {
        $errors['nombres'] = "Los nombres son requeridos y solo deben contener letras y espacios.";
    } else {
        $values['nombres'] = $_POST['nombres'];
    }

    // Validar apellidos
    if (empty($_POST["apellidos"]) || !preg_match("/^[A-ZÁÉÍÓÚ\s]+$/", $_POST["apellidos"])) {
        $errors['apellidos'] = "Los apellidos son requeridos y solo deben contener letras y espacios.";
    } else {
        $values['apellidos'] = $_POST['apellidos'];
    }

    // Validar sexo
    if (empty($_POST["genero"]) || $_POST["genero"] == "None") {
        $errors['genero'] = "El género es requerido.";
    } else {
        $values['genero'] = $_POST['genero'];
    }

    // Validar teléfono
    $val_telefono=$_POST["telefono"];
    if (empty($_POST["telefono"]) || !preg_match("/^\d{7,10}$/", $_POST["telefono"])) {
        $errors['telefono'] = "El teléfono es requerido y solo debe contener entre 7 y 10 dígitos.";
    } else {
        // Verificar si la cédula ya está registrada
        $stmt = $con->consulta("SELECT COUNT(*) AS count FROM ciudadano WHERE telefono = '$val_telefono' and estado=1");
        if ($stmt[0]["count"] > 0) {
            $errors['telefono'] = "El número de teléfono ya se encuentra registrado";
        }
        $values['telefono'] = $_POST['telefono'];
    }

    $val_correo=$_POST["correo"];
    // Validar correo
    if (empty($_POST["correo"]) || !filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL)) {
        $errors['correo'] = "El correo electrónico es requerido y debe tener un formato válido.";
    } else {
        // Verificar si la cédula ya está registrada
        $stmt = $con->consulta("SELECT COUNT(*) AS count FROM ciudadano WHERE email = '$val_correo' and estado=1");
        if ($stmt[0]["count"] > 0) {
            $errors['correo'] = "El correo electrónico ya se encuentra registrado";
        }
        $values['correo'] = $_POST['correo'];
    }

    // Validar domicilio
    if (empty($_POST["direccion"]) || !preg_match("/^[A-Za-z0-9\s.]+$/", $_POST["direccion"])) {
        $errors['direccion'] = "La direccion son requeridos y solo deben contener letras y espacios.";
    } else {
        $values['direccion'] = $_POST['direccion'];
    }

    // Validar fecha de nacimiento
    if (empty($_POST["fecha_nacimiento"])) {
        $errors['fecha_nacimiento'] = "La fecha de nacimiento es requerida.";
    } else {
        $min_date = new DateTime("1907-03-04");
        $max_date = new DateTime("2006-07-12");
        $fecha_nacimiento = new DateTime($_POST["fecha_nacimiento"]);
        if ($fecha_nacimiento < $min_date || $fecha_nacimiento > $max_date) {
            $errors['fecha_nacimiento'] = "La fecha de nacimiento debe estar entre 1907-03-04 y 2006-07-12.";
        }
        $values['fecha_nacimiento'] = $_POST['fecha_nacimiento'];
    }

    // Validar tipo de seguro
    if (empty($_POST["tipoSeguro"]) || $_POST["tipoSeguro"] == "None") {
        $errors['tipoSeguro'] = "El tipo de seguro es requerido.";
    } else {
        $values['tipoSeguro'] = $_POST['tipoSeguro'];
    }

    // Validar provincia
    if (empty($_POST["id_provincia"]) || $_POST["id_provincia"] == "None") {
        $errors['id_provincia'] = "La provincia es requerida.";
    } else {
        $values['id_provincia'] = $_POST['id_provincia'];
    }

    // Validar cantón
    if (empty($_POST["id_canton"]) || $_POST["id_canton"] == "None") {
        $errors['id_canton'] = "El cantón es requerido.";
    } else {
        $values['id_canton'] = $_POST['id_canton'];
    }

    // Validar parroquia
    if (empty($_POST["id_parroquia"]) || $_POST["id_parroquia"] == "None") {
        $errors['id_parroquia'] = "La parroquia es requerida.";
    } else {
        $values['id_parroquia'] = $_POST['id_parroquia'];
    }

    // Si no hay errores, procesar el formulario
    if (empty($errors)) {
        // Aquí puedes agregar el código para guardar los datos en la base de datos
        $cedula = $_POST["cedula"];
        $nombres = $_POST["nombres"];
        $apellidos = $_POST["apellidos"];
        $correo = $_POST["correo"];
        $telefono = $_POST["telefono"];
        $direccion = $_POST["direccion"];
        $fecha_nacimiento = $_POST["fecha_nacimiento"];
        $sexo = $_POST["genero"];
        $tipoSeguro = $_POST["tipoSeguro"];
        $provincia = $_POST["id_provincia"];
        $canton = $_POST["id_canton"];
        $parroquia = $_POST["id_parroquia"];
        $clave=generarClave(10);

        $ciudadano = new Ciudadano(null,$cedula,$nombres,$apellidos,$correo,$telefono,$direccion,$fecha_nacimiento,$sexo,$tipoSeguro,$provincia,$canton,$parroquia);
        $consultaCiudadano = $ciudadano->insertarCiudadano($con);
        $usuario=new Usuario(null,$cedula,$clave,1);
        $consultaUsuario = $usuario->insertarUsuario($con);
        
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
            $mail->addAddress($correo);

            // Adjuntar imagen del logo
            $mail->addEmbeddedImage('../../public/img/logo.png', 'logo_img'); // Cambia esto a la ruta de tu logo

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Registro Exitoso en SORAG';
            $mail->Body    = '<img src="cid:logo_img" alt="SORAG Logo"><br>Hola ' . $values['nombres'] . ' ' . $values['apellidos'] . ',<br><br>Gracias por registrarte en SORAG.<br><br>Tus credenciales son:<br>Usuario: ' . $cedula . '
            <br>Clave: ' . $clave . '<br><br>Saludos,<br>Equipo SORAG';

            $mail->send();
            
            echo "<script>
            Swal.fire({
            title: 'Credenciales',
            text: 'Sus credenciales han sido enviadas al correo',
            icon: 'success',
            confirmButtonText: 'Aceptar'
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../../index.php'; 
            }
            });
            </script>";
            
    
        } catch (Exception $e) {
            echo "<script>
            Swal.fire({
            title: 'Credenciales',
            text: 'Registro exitoso pero no se pudo enviar el correo. Mailer Error: {$mail->ErrorInfo}',
            icon: 'success',
            confirmButtonText: 'Aceptar'
            });
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
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $max = strlen($pattern)-1;
    for($i = 0; $i < $length; $i++){
        $key .= substr($pattern, mt_rand(0,$max), 1);
    }
    return $key;
}
?>