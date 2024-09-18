<?php
	class Constantes {
		const HOST = "us-cluster-east-01.k8s.cleardb.net";
		const USER = "bb655980924c7e";
		const PASSWORD = "e2b5273d";
		const DB = "heroku_ea96dea1e08d5a3";
	}
	
	class Configuracion extends Constantes{

		public $mysql = NULL;	

		public function conectarBD($bd = Constantes::DB)
		{
			date_default_timezone_set("America/Guayaquil");
			$this->mysql = new mysqli(Constantes::HOST, Constantes::USER, Constantes::PASSWORD, $bd);	
			mysqli_set_charset($this->mysql, "utf8");	
			return $this->mysql;

		}

		
		public function consulta($query)
		{			
			$i = 0;
			$contenedor = array();	
			$result = mysqli_query($this->mysql, $query) or die("Error en la consulta: $query ".mysqli_error($this->mysql));
			
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$contenedor[$i] = $row;
				$i++;
			}
			
			return $contenedor;
		}

		public function ejecutar($query){
			$result = mysqli_query($this->mysql, $query);
			if ($result === false) {
				$errorMessage = "Error en la consulta SQL: " . mysqli_error($this->mysql);
				throw new Exception($errorMessage);
			} else {
				return $result;
			}
		}

		public function actualizacion($query)
		{
			mysqli_query($this->mysql, $query) or die("Error en la consulta: $query ".mysqli_error($this->mysql));
		}

		public function desconectarDB()
		{
				mysqli_close($this->mysql);
		}

		public function IdCiudadano($conexion) {
			$sql = "SELECT id_ciudadano 
				FROM ciudadano
				ORDER BY id_ciudadano DESC
				LIMIT 1";
			$result = $conexion->consulta($sql);
			return $result[0]['id_ciudadano'];
		}

		public function IdUsuario($conexion) {
			$sql = "SELECT id_usuario
				FROM usuario
				ORDER BY id_usuario DESC
				LIMIT 1";
			$result = $conexion->consulta($sql);
			return $result[0]['id_usuario'];
		}

		public function IdSolicitud($conexion) {
			$sql = "SELECT id_solicitud
				FROM solicitudes
				ORDER BY id_solicitud DESC
				LIMIT 1";
			$result = $conexion->consulta($sql);
			return $result[0]['id_solicitud'];
		}
	}
?>