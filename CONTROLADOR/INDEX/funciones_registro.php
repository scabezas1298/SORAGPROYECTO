<?php
        // Incluir archivos
        include_once '../../config/conexion.php'; 
        include_once '../../controlador/consultas_base.php';
        $id_provincia = isset($_POST['id_provincia']) ? $_POST['id_provincia'] : '';
        $id_canton = isset($_POST['id_canton']) ? $_POST['id_canton'] : '';
        $id_parroquia = isset($_POST['id_parroquia']) ? $_POST['id_parroquia'] : '';
        
        function obtenerCantones($id_provincia, $id_cantonSeleccionado) {
            $conf = new Configuracion();
            $conf->conectarBD();
            $consulta = "SELECT * FROM canton WHERE codigo_provincia = $id_provincia ORDER BY nombre_canton ASC";
            $datos = $conf->consulta($consulta);
            $conf->desconectarDB();
        
            if (count($datos) == 0) {
                echo '<option value="None">No hay registros en esta provincia</option>';
            } else {
                foreach ($datos as $dato) {
                    $selected = $dato['codigo_canton'] == $id_cantonSeleccionado ? 'selected' : '';
                    echo '<option value="' . $dato['codigo_canton'] . '" ' . $selected . '>' . $dato['nombre_canton'] . '</option>';
                }
            }
        }
        
        function obtenerParroquias($id_canton, $id_parroquiaSeleccionado) {
            $conf = new Configuracion();
            $conf->conectarBD();
            $consulta = "SELECT * FROM parroquia WHERE codigo_canton = $id_canton ORDER BY nombre_parroquia ASC";
            $datos = $conf->consulta($consulta);
            $conf->desconectarDB();
        
            if (count($datos) == 0) {
                echo '<option value="None">No hay registros en este canton</option>';
            } else {
                foreach ($datos as $dato) {
                    $selected = $dato['codigo_parroquia'] == $id_parroquiaSeleccionado ? 'selected' : '';
                    echo '<option value="' . $dato['codigo_parroquia'] . '" ' . $selected . '>' . $dato['nombre_parroquia'] . '</option>';
                }
            }
        }

    ?>