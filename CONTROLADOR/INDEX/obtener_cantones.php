<?php
include '../../config/conexion.php';
$id_provincia = filter_input(INPUT_POST, 'id_provincia');
$id_canton = filter_input(INPUT_POST, 'id_canton');
$conf = new Configuracion();
$conf->conectarBD();

$consulta = "SELECT * FROM canton WHERE codigo_provincia = $id_provincia ORDER BY nombre_canton ASC";
$datos = $conf->consulta($consulta);

$conf->desconectarDB();
if (count($datos) == 0) {
    echo '<option value="None">No hay registros en esta provincia</option>';
} else {
    foreach ($datos as $dato) {
        $selected = $dato['codigo_canton'] == $id_canton ? 'selected' : '';
        echo '<option value="' . $dato['codigo_canton'] . '" ' . $selected . '>' . $dato['nombre_canton'] . '</option>';
    }
}
?>


