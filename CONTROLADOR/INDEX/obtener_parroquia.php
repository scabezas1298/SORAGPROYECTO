<?php
include '../../config/conexion.php';

$id_canton = filter_input(INPUT_POST, 'id_canton');
$id_parroquia = filter_input(INPUT_POST, 'id_parroquia');

$conf = new Configuracion();
$conf->conectarBD();

$consulta = "SELECT * FROM parroquia WHERE codigo_canton = $id_canton ORDER BY nombre_parroquia ASC";
$datos = $conf->consulta($consulta);

$conf->desconectarDB();

if (count($datos) == 0) {
    echo '<option value="None">No hay registros en este canton</option>';
} else {
    foreach ($datos as $dato) {
        $selected = $dato['codigo_parroquia'] == $id_parroquia ? 'selected' : '';
        echo '<option value="' . $dato['codigo_parroquia'] . '" ' . $selected . '>' . $dato['nombre_parroquia'] . '</option>';
    }
}
?>
