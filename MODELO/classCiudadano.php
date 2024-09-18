<?php

class Ciudadano{
    private $id_persona;
    private $cedula;
    private $nombre;
    private $apellido;
    private $email;
    private $telefono;
    private $direccion;
    private $fecha_nacimiento;
    private $sexo;
    private $tipo_seguro;
    private $provincia;
    private $canton;
    private $parroquia;


    public function __construct($id_persona, $cedula, $nombre, $apellido , $email ,$telefono, 
    $direccion , $fecha_nacimiento , $sexo, $tipo_seguro, $provincia, $canton, $parroquia) {
        $this->id_persona = $id_persona;
        $this->cedula = $cedula;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->sexo = $sexo;
        $this->tipo_seguro = $tipo_seguro;
        $this->provincia = $provincia;
        $this->canton = $canton;
        $this->parroquia = $parroquia;
    }

    // Getters
    public function getId_persona() {
        return $this->id_persona;
    }

    public function getCedula() {
        return $this->cedula;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getFechaNacimiento() {
        return $this->fecha_nacimiento;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function getTipoSeguro() {
        return $this->tipo_seguro;
    }

    public function getProvincia() {
        return $this->provincia;
    }

    public function getCanton() {
        return $this->canton;
    }

    public function getParroquia() {
        return $this->parroquia;
    }

    // Setters
    public function setId_persona() {
        return $this->id_persona;
    }

    public function setCedula() {
        return $this->cedula;
    }

    public function setNombre() {
        return $this->nombre;
    }

    public function setApellido() {
        return $this->apellido;
    }

    public function setEmail() {
        return $this->email;
    }

    public function setTelefono() {
        return $this->telefono;
    }

    public function setDireccion() {
        return $this->direccion;
    }

    public function setFechaNacimiento() {
        return $this->fecha_nacimiento;
    }

    public function setSexo() {
        return $this->sexo;
    }

    public function setTipoSeguro() {
        return $this->tipo_seguro;
    }

    public function setProvincia() {
        return $this->provincia;
    }

    public function setCanton() {
        return $this->canton;
    }

    public function setParroquia() {
        return $this->parroquia;
    }


    
    public function insertarCiudadano($conexion) {
       
            $cedula = $this->getCedula();
            $nombre = $this->getNombre();
            $apellido = $this->getApellido();
            $email = $this->getEmail();
            $telefono = $this->getTelefono();
            $direccion = $this->getDireccion();
            $fecha_nacimiento = $this->getFechaNacimiento();
            $sexo = $this->getSexo();
            $tipo_seguro = $this->getTipoSeguro();
            $provincia = $this->getProvincia();
            $canton = $this->getCanton();
            $parroquia = $this->getParroquia();

        $sql = "INSERT INTO ciudadano (cedula, nombres, apellidos, email,telefono, fecha_nacimiento,direccion, genero, tipo_seguro, provincia, canton, parroquia,estado)
        VALUES ('$cedula','$nombre','$apellido','$email','$telefono','$fecha_nacimiento','$direccion','$sexo','$tipo_seguro','$provincia','$canton','$parroquia',1)";

        $stmt = $conexion->ejecutar($sql);

        if ($stmt) {
            $id_ciudadano = $conexion->IdCiudadano($conexion);
            $resultado = array('id_ciudadano' => $id_ciudadano);
            return $resultado;
        } else {
            return false;
        }
    }
    
    public function updateCiudadano($conexion) {
       
            $cedula = $this->getCedula();
            $email = $this->getEmail();
            $telefono = $this->getTelefono();
            $direccion = $this->getDireccion();
            $tipo_seguro = $this->getTipoSeguro();

        $sql = "UPDATE ciudadano SET email='$email', telefono='$telefono', direccion='$direccion', tipo_seguro='$tipo_seguro' WHERE cedula='$cedula'";

        $stmt = $conexion->ejecutar($sql);

        if ($stmt) {
            $id_ciudadano = $conexion->IdCiudadano($conexion);
            $resultado = array('id_ciudadano' => $id_ciudadano);
            return $resultado;
        } else {
            return false;
        }
    }

    public function obtenerCorreo($conexion) {
        $cedula = $this->getCedula();
        $sql = "SELECT email
            FROM ciudadano
            WHERE cedula=$cedula";
        $result = $conexion->consulta($sql);
        return $result[0]['email'];
    }

}

?>