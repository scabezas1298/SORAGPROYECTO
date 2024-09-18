<?php

class Usuario{
    private $id_usuario;
    private $nombre_usuario;
    private $clave_usuario;
    private $rol;


    public function __construct($id_usuario, $nombre_usuario, $clave_usuario, $rol) {
        $this->id_usuario = $id_usuario;
        $this->nombre_usuario = $nombre_usuario;
        $this->clave_usuario = $clave_usuario;
        $this->rol = $rol;
    }

    // Getters
    public function getId_usuario() {
        return $this->id_usuario;
    }

    public function getNombre_usuario() {
        return $this->nombre_usuario;
    }

    public function getClave_usuario() {
        return $this->clave_usuario;
    }

    public function getRol() {
        return $this->rol;
    }

   

    // Setters
 
    public function setId_usuario() {
        return $this->id_usuario;
    }

    public function setNombre_usuario() {
        return $this->nombre_usuario;
    }

    public function setClave_usuario() {
        return $this->clave_usuario;
    }

    public function setRol() {
        return $this->rol;
    }

    
    public function insertarUsuario($conexion) {
       
            $nombre_usuario = $this->getNombre_usuario();
            $clave_usuario = $this->getClave_usuario();
            $rol = $this->getRol();


        $sql = "INSERT INTO usuario (nombre_usuario,clave_usuario_inicio, rol,estado)
        VALUES ('$nombre_usuario','$clave_usuario','$rol',0)";

        $stmt = $conexion->ejecutar($sql);

        if ($stmt) {
            $id_usuario = $conexion->IdUsuario($conexion);
            $resultado = array('id_usuario' => $id_usuario);
            return $resultado;
        } else {
            return false;
        }
    }

    public function updateClave($conexion) {
            $nombre_usuario = $this->getNombre_usuario();
            $clave_usuario = $this->getClave_usuario();

        $sql = "UPDATE usuario SET clave_usuario_final='$clave_usuario', clave_usuario_inicio=null,estado=1 WHERE nombre_usuario='$nombre_usuario' and estado=0";

        $stmt = $conexion->ejecutar($sql);

        if ($stmt) {
            $id_usuario = $conexion->IdUsuario($conexion);
            $resultado = array('id_usuario' => $id_usuario);
            return $resultado;
        } else {
            return false;
        }
    }

    public function actualizarClave($conexion) {
        $nombre_usuario = $this->getNombre_usuario();
        $clave_usuario = $this->getClave_usuario();

    $sql = "UPDATE usuario SET clave_usuario_final='$clave_usuario' WHERE nombre_usuario='$nombre_usuario' and estado=1";

    $stmt = $conexion->ejecutar($sql);

    if ($stmt) {
        $id_usuario = $conexion->IdUsuario($conexion);
        $resultado = array('id_usuario' => $id_usuario);
        return $resultado;
    } else {
        return false;
    }
}

    public function recuperarClave($conexion) {
        $nombre_usuario = $this->getNombre_usuario();
        $clave_usuario = $this->getClave_usuario();

        $sql = "UPDATE usuario SET clave_usuario_final='$clave_usuario' WHERE (nombre_usuario='$nombre_usuario') and (estado=1)";

        $stmt = $conexion->ejecutar($sql);

        if ($stmt) {
            $id_usuario = $conexion->IdUsuario($conexion);
            $resultado = array('id_usuario' => $id_usuario);
            return $resultado;
        } else {
            return false;
        }
    }


    public function updateUsuario($conexion) {
       
        $nombre_usuario = $this->getNombre_usuario();
        $clave_usuario = $this->getClave_usuario();


    $sql = "UPDATE usuario SET clave_usuario_final='$clave_usuario' WHERE nombre_usuario='$nombre_usuario'";

    $stmt = $conexion->ejecutar($sql);

    if ($stmt) {
        $id_usuario = $conexion->IdUsuario($conexion);
        $resultado = array('id_usuario' => $id_usuario);
        return $resultado;
    } else {
        return false;
    }
}

public function obtenerEstado($conexion) {
    $cedula = $this->getNombre_usuario();
    $sql = "SELECT estado
        FROM usuario
        WHERE nombre_usuario=$cedula";
    $result = $conexion->consulta($sql);
    return $result[0]['estado'];
}

}

?>