<?php
include_once 'conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
// Recepción de los datos enviados mediante POST desde el JS   
$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$cedula = isset($_POST['cedula']) ? $_POST['cedula'] : '';
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$identificador = $cedula . ' ' . $fecha;

switch($opcion){
    case 2: //modificación
        $consulta = "UPDATE solicitudes SET estado='APROBADO' WHERE id_solicitud='$id' ";		
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();        
        
        $consulta = "SELECT * FROM solicitudes WHERE id_solicitud='$id' ";       
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
        break;        
    case 3://baja
        $consulta = "UPDATE solicitudes SET estado='CANCELADO' WHERE id_solicitud='$id' ";		
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();   

        $consulta2 = "UPDATE nodos SET estado='INACTIVO' WHERE nombre='$identificador' ";		
        $resultado2 = $conexion->prepare($consulta2);
        $resultado2->execute();   
        
        $consulta = "SELECT * FROM solicitudes WHERE id_solicitud='$id' ";       
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data=$resultado->fetchAll(PDO::FETCH_ASSOC);                           
        break;        
}

print json_encode($data, JSON_UNESCAPED_UNICODE); //enviar el array final en formato json a JS
$conexion = NULL;
