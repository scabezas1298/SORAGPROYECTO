<?php require_once "header.php"?>
<?php
    // Obtener datos para el select
    require_once '../../config/conexion.php'; 
    require_once '../../controlador/consultas_base.php';
    $id_usuario=$_SESSION['id_usuario'];
    $cedula=$_SESSION['cedula'];
    $ciudadanoData = obtenerCiudadano($cedula);                                                                                                 
    ?>

<div class="formulario">
<form action="" method="POST" class="formulario__ciudadano">
<div class="contenedor">
        <div class="columnas">
            <div class="columna">
            
                    <label for="cedula">Cédula:</label>
                    <input type="text" id="cedula_usuario" name="cedula" value="<?php echo $ciudadanoData[0]['cedula'];?>" disabled>

                    <label for="nombres">Nombre Completo:</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo $ciudadanoData[0]['nombres'].' '.$ciudadanoData[0]['apellidos'];?>" disabled>

                    <label for="correo">Correo electrónico:</label>
                    <input type="text" id="correo" name="correo" value="<?php echo $ciudadanoData[0]['email'];?>">

                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo $ciudadanoData[0]['telefono'];?>">

                    <label for="fecha_nac">Fecha Nacimiento:</label>
                    <input type="text" id="fecha_nac" name="fecha_nac" value="<?php echo $ciudadanoData[0]['fecha_nacimiento'];?>" disabled>

                    <label for="direccion">Dirección de domicilio:</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo $ciudadanoData[0]['direccion'];?>" >


            </div>

            <div class="columna">
            
                    

                    <label for="sexo">Sexo:</label>
                    <input type="text" id="sexo" name="sexo" value="<?php echo $ciudadanoData[0]['nombre_genero'];?>" disabled>

                    <label for="tipo_seguro">Tipo de Seguro:</label>
                    <select id="tipoSeguro" name="tipoSeguro" required>
                    <option value="">--Seleccione el tipo de seguro del paciente--</option>
                    <?php
                      obtenerTipoSeguro();
                    ?>
                    </select>

                    <label for="provinciad">Provincia:</label>
                    <input type="text" id="provinciad" name="provinciad" value="<?php echo $ciudadanoData[0]['nombre_provincia'];?>" disabled>

                    <label for="cantond">Cantón:</label>
                    <input type="text" id="cantond" name="cantond" value="<?php echo $ciudadanoData[0]['nombre_canton'];?>" disabled>

                    <label for="parroquiad">Parroquia:</label>
                    <input type="text" id="parroquiad" name="parroquiad" value="<?php echo $ciudadanoData[0]['nombre_parroquia'];?>" disabled>

                    <label for="clave">Nueva contraseña:</label>
                    <input type="password" id="clave" name="clave" value="">

            </div>
            
        </div>
        
    </div>
                    <div class="botones">
                    <input name="btnactualizar" class="btn" type="submit" value="REALIZAR CAMBIOS">
                    <input name="btnborrar" class="btnborrar" type="reset" value="LIMPIAR DATOS">
                    </div>
</form>

</div>
<?php require_once "footer.php"?>