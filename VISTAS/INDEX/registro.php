<?php
require '../../controlador/index/validacion_registro.php';
include_once '../../controlador/index/funciones_registro.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SORAG</title>
    <link rel="stylesheet" href="../../public/css/index/estilo_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
</head>
<body>

    <form  method="post" class="form-register">
        <h2 class="form-titulo">Registro SORAG</h2>
        <div class="contenedor-inputs">
            <input type="text" placeholder="Ingresa cédula de identidad" name="cedula" class="input-100 <?php echo isset($errors['cedula']) ? 'input-error' : ''; ?>" required value="<?php echo htmlspecialchars($values['cedula'] ?? ''); ?>">

            <input type="text" placeholder="Nombres" name="nombres" oninput="this.value = this.value.toUpperCase()" class="input-48 <?php echo isset($errors['nombres']) ? 'input-error' : ''; ?>" required value="<?php echo htmlspecialchars($values['nombres'] ?? ''); ?>">

            <input type="text" placeholder="Apellidos" name="apellidos" oninput="this.value = this.value.toUpperCase()" class="input-48 <?php echo isset($errors['apellidos']) ? 'input-error' : ''; ?>" required value="<?php echo htmlspecialchars($values['apellidos'] ?? ''); ?>">

            <select id="genero" name="genero" class="input-48 <?php echo isset($errors['genero']) ? 'input-error' : ''; ?>" required>
                <option value="None" <?php echo (!isset($values['genero']) || $values['genero'] == "None") ? "selected" : ""; ?>>--Género--</option>
                <!-- Aquí agrega opciones de sexo -->
                <?php
                    $generoSeleccionado = isset($_POST['genero']) ? $_POST['genero'] : ''; 
                    obtenerGenero($generoSeleccionado);
                ?>
            </select>

            <input type="text" placeholder="Teléfono" name="telefono" class="input-48 <?php echo isset($errors['telefono']) ? 'input-error' : ''; ?>" required value="<?php echo htmlspecialchars($values['telefono'] ?? ''); ?>">

            <input type="text" placeholder="Correo" name="correo" class="input-100 <?php echo isset($errors['correo']) ? 'input-error' : ''; ?>" required value="<?php echo htmlspecialchars($values['correo'] ?? ''); ?>">

            <input type="text" placeholder="Ingresa dirección domicilio" name="direccion" oninput="this.value = this.value.toUpperCase()" class="input-100 <?php echo isset($errors['direccion']) ? 'input-error' : ''; ?>" required value="<?php echo htmlspecialchars($values['direccion'] ?? ''); ?>">

            <label class="input-48">Fecha de nacimiento:</label>
            <input type="date" name="fecha_nacimiento" min="1924-08-19" max="2006-08-19" class="input-48 <?php echo isset($errors['fecha_nacimiento']) ? 'input-error' : ''; ?>" required value="<?php echo htmlspecialchars($values['fecha_nacimiento'] ?? ''); ?>">

            <select id="tipoSeguro" name="tipoSeguro" class="input-48 <?php echo isset($errors['tipoSeguro']) ? 'input-error' : ''; ?>" required>
                <option value="None" <?php echo (!isset($values['tipoSeguro']) || $values['tipoSeguro'] == "None") ? "selected" : ""; ?>>--Seguro--</option>
                <!-- Aquí agrega opciones de seguro -->
                <?php
                      $tipoSeguroSeleccionado = isset($_POST['tipoSeguro']) ? $_POST['tipoSeguro'] : ''; 
                      obtenerTipoSeguro($tipoSeguroSeleccionado);
                ?>
            </select>

            <select id="id_provincia" name="id_provincia" class="input-48 <?php echo isset($errors['id_provincia']) ? 'input-error' : ''; ?>"  onchange="cargarCantones()" required>
                <option value="None" <?php echo (!isset($values['id_provincia']) || $values['id_provincia'] == "None") ? "selected" : ""; ?>>--Provincia--</option>
                <!-- Aquí agrega opciones de provincia -->
                <?php
                      $provinciaSeleccionado = isset($_POST['id_provincia']) ? $_POST['id_provincia'] : ''; 
                      obtenerProvincia($provinciaSeleccionado);
                ?>
            </select>

            <select id="id_canton" name="id_canton" class="input-48 <?php echo isset($errors['id_canton']) ? 'input-error' : ''; ?>" onchange="cargarParroquias()" required>
                <option value="None" <?php echo (!isset($values['id_canton']) || $values['id_canton'] == "None") ? "selected" : ""; ?>>--Cantón--</option>
                <!-- Aquí agrega opciones de cantón -->
                <?php
                    if ($id_provincia) {
                        obtenerCantones($id_provincia, $id_canton);
                    }
                ?>
            </select>

            <select id="id_parroquia" name="id_parroquia" class="input-48 <?php echo isset($errors['id_parroquia']) ? 'input-error' : ''; ?>" required>
                <option value="None" <?php echo (!isset($values['id_parroquia']) || $values['id_parroquia'] == "None") ? "selected" : ""; ?>>--Parroquia--</option>
                <!-- Aquí agrega opciones de parroquia -->
                <?php
                    if ($id_canton) {
                        obtenerParroquias($id_canton, $id_parroquia);
                    }
                ?>
            </select>

            <input type="submit" value="REGISTRAR" class="btn-enviar" name="btnregistro">
            <p class="form-link">¿Ya tienes una cuenta? <a href="../../index.php">Inicia sesión aquí</a></p>
        </div>
        
    </form>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    <?php if (!empty($errors)): ?>
        $(document).ready(function() {
            <?php foreach ($errors as $error): ?>
                toastr.error(<?php echo json_encode($error); ?>);
            <?php endforeach; ?>
        });
    <?php endif; ?>
</script>

<script> 
function cargarCantones() {
    var id_provincia = document.getElementById('id_provincia').value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('id_canton').innerHTML = this.responseText;
        }
    };
    xhttp.open("POST", "../../controlador/index/obtener_cantones.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id_provincia=" + id_provincia);
}

function cargarParroquias() {
    var id_canton = document.getElementById('id_canton').value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('id_parroquia').innerHTML = this.responseText;
        }
    };
    xhttp.open("POST", "../../controlador/index/obtener_parroquia.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id_canton=" + id_canton);
}
</script>
</body>
</html>
