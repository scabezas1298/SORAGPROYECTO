<?php

function obtenerGenero($generoSeleccionado = '') {
    $conf = new Configuracion();
			$conf->conectarBD();                                                                             
            $consulta = "SELECT * FROM genero ORDER BY nombre_genero ASC";
            $rst1 = $conf->consulta($consulta);
            foreach ($rst1 as $genero) 
            {                                                   
                $selected = ($genero['codigo_genero'] == $generoSeleccionado) ? 'selected' : '';
                echo '<option value="'.$genero['codigo_genero'].'" '.$selected.'>'.$genero['nombre_genero'].'</option>';                                                 
            }
             $conf->desconectarDB();
}

function obtenerTipoSeguro($tipoSeguroSeleccionado = '') {
    $conf = new Configuracion();
			$conf->conectarBD();                                                                             
            $consulta = "SELECT * FROM tipo_seguro ORDER BY nombre_tipo_seguro ASC";
            $rst1 = $conf->consulta($consulta);
            foreach ($rst1 as $tipoSeguro)
            {                                                   
                $selected = ($tipoSeguro['codigo_tipo_seguro'] == $tipoSeguroSeleccionado) ? 'selected' : '';
                echo '<option value="'.$tipoSeguro['codigo_tipo_seguro'].'" '.$selected.'>'.$tipoSeguro['nombre_tipo_seguro'].'</option>';                                                            
            }
             $conf->desconectarDB();
}

function obtenerProvincia($provinciaSeleccionado = '') {
    $conf = new Configuracion();
			$conf->conectarBD();                                                                             
            $consulta = "SELECT * FROM provincia ORDER BY nombre_provincia ASC";
            $rst1 = $conf->consulta($consulta);
            foreach ($rst1 as $provincia)
            {                                                   
                $selected = ($provincia['codigo_provincia'] == $provinciaSeleccionado) ? 'selected' : '';
                echo '<option value="'.$provincia['codigo_provincia'].'" '.$selected.'>'.$provincia['nombre_provincia'].'</option>';                                                       
            }
             $conf->desconectarDB();
}

function obtenerTipoEmergencia() {
    $conf = new Configuracion();
    $conf->conectarBD();                                                                             
    $consulta = "SELECT * FROM tipo_emergencia ORDER BY nmb_tipo_emergencia ASC";
    $rst1 = $conf->consulta($consulta);
    for($i = 0; $i < count($rst1); $i++) {                                                   
        echo '<label>';
        echo '<input type="radio" name="grupo2" class="grupo2" value="' . $rst1[$i]["id_tipo_emergencia"] . '"> ' . $rst1[$i]["nmb_tipo_emergencia"];
        echo '</label>';
    }
    $conf->desconectarDB();
}


function obtenerCiudadano($cedula) {
    $conf = new Configuracion();
    $conf->conectarBD();
    
    $consulta = "SELECT * FROM ciudadano AS c 
    JOIN genero AS s ON c.genero=s.codigo_genero
    JOIN tipo_seguro AS ts ON c.tipo_seguro = ts.codigo_tipo_seguro
    JOIN provincia AS p ON c.provincia = p.codigo_provincia
    JOIN canton AS ca ON c.canton = ca.codigo_canton
    JOIN parroquia AS pa ON c.parroquia = pa.codigo_parroquia
    WHERE cedula = '$cedula'";
    $resultado = $conf->consulta($consulta);
    
    $conf->desconectarDB();
    return $resultado;
}

function obtenerSolicitudes($cedula) {
    $conf = new Configuracion();
    $conf->conectarBD();
    
    $consulta = "SELECT * FROM solicitudes s
    JOIN  tipo_emergencia te ON te.id_tipo_emergencia=s.tipo_emergencia
     WHERE cedula_solicitante = '$cedula' ORDER BY s.id_solicitud ASC";
    $resultado = $conf->consulta($consulta);
    
    $conf->desconectarDB();
    return $resultado;
}

function obtenerEstadoPaciente() {
    $conf = new Configuracion();
			$conf->conectarBD();                                                                             
            $consulta = "SELECT * FROM estado_paciente ";
            $rst1 = $conf->consulta($consulta);
            for($i = 0; $i < count($rst1); $i++)
            {                                                   
                echo '<option value="'.$rst1[$i]["id_estado_paciente"].'">'.$rst1[$i]["nmb_estado_paciente"].'</option>';                                                    
            }
             $conf->desconectarDB();
}



function obtenerRol($usuario) {
    $conf = new Configuracion();
	$conf->conectarBD();                                                                             
    $consulta = "SELECT * FROM usuario WHERE nombre_usuario=$usuario ASC";
    $rst1 = $conf->consulta($consulta);
    return $rst1;
    $conf->desconectarDB();
}
?>