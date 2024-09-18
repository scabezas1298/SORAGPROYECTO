<?php

class Solicitud{
    private $id_solicitud;
    private $cedulaSolicitante;
    private $ex;
    private $ey;
    private $numeroPacientes;
    private $tipoEmergencia;
    private $evidencia;

    public function __construct($id_solicitud, $cedulaSolicitante,$ex,$ey,$numeroPacientes,$tipoEmergencia,$evidencia) {
        $this->id_solicitud = $id_solicitud;
        $this->cedulaSolicitante = $cedulaSolicitante;
        $this->ex = $ex;
        $this->ey = $ey;
        $this->numeroPacientes = $numeroPacientes;
        $this->tipoEmergencia = $tipoEmergencia;
        $this->evidencia = $evidencia;
    }

    // Getters
    public function getId_solicitud() {
        return $this->id_solicitud;
    }

    public function getCedulaSolicitante() {
        return $this->cedulaSolicitante;
    }

    public function getEx() {
        return $this->ex;
    }

    public function getEy() {
        return $this->ey;
    }

    public function getNumeroPacientes() {
        return $this->numeroPacientes;
    }

    public function getTipoEmergencia() {
        return $this->tipoEmergencia;
    }

    public function getEvidencia() {
        return $this->evidencia;
    }

    // Setters
    public function setId_solicitud() {
        return $this->id_solicitud;
    }

    public function setCedulaSolicitante() {
        return $this->cedulaSolicitante;
    }

    public function setEx() {
        return $this->ex;
    }

    public function setEy() {
        return $this->ey;
    }

    public function setNumeroPacientes() {
        return $this->numeroPacientes;
    }

    public function setTipoEmergencia() {
        return $this->tipoEmergencia;
    }

    public function setEvidencia() {
        return $this->evidencia;
    }

    
    public function insertarSolicitud($conexion) {

            $cedulaSolicitante = $this->getCedulaSolicitante();
            date_default_timezone_set('America/Guayaquil');
            $fecha_solicitud=date('Y-m-d H:i:s');
            $ex= $this->getEx();
            $ey = $this->getEy();
            $numeroPacientes = $this->getNumeroPacientes();
            $tipoEmergencia = $this->getTipoEmergencia();
            $evidencia = $this->getEvidencia();
           

        $sql = "INSERT INTO solicitudes (cedula_solicitante, fecha_solicitud, latitude, longitude, numero_pacientes, tipo_emergencia, evidencia,estado)
            VALUES ('$cedulaSolicitante', '$fecha_solicitud', '$ex', '$ey', '$numeroPacientes', '$tipoEmergencia', '$evidencia','GENERADO')";


        $stmt = $conexion->ejecutar($sql);

        if ($stmt) {
            $id_solicitud = $conexion->IdSolicitud($conexion);
            return strval($id_solicitud);
        } else {
            return false;
        }
    }

    public function insertarSolicitudNodo($conexion,$idsolicitud) {
        $ex= $this->getEx();
        $ey = $this->getEy();
       

    $sql = "INSERT INTO nodos (nombre,latitude,longitude,tipo_nodo,estado)
        VALUES ('$idsolicitud', '$ex', '$ey','SLC','INACTIVO')";


    $stmt = $conexion->ejecutar($sql);

    if ($stmt) {
        $id_solicitud= $conexion->IdSolicitud($conexion);
        $resultado = array('id_solicitud' => $id_solicitud);
        return $resultado;
    } else {
        return false;
    }
}

    

}

?>