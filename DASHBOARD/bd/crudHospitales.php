<?php
// Configuración de la base de datos
$host = 'localhost'; // Cambia según tu configuración
$user = 'root'; // Cambia según tu configuración
$password = ''; // Cambia según tu configuración
$database = 'sorag'; // Cambia según tu configuración

// Crear conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Leer hospitales
        $result = $conn->query("SELECT * FROM hospitales");
        $hospitales = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($hospitales);
        break;

    case 'POST':
        // Crear o actualizar hospital
        $nombre = $_POST['nombre_hospital'];
        $direccion = $_POST['direccion_hospital'];
        $latitud = $_POST['latitud'];
        $longitud = $_POST['longitud'];
        $tipoHospital = $_POST['codigo_tipo_hospital'];

        // Si hay un ID, se actualiza; si no, se crea uno nuevo
        if (isset($_POST['hospitalId']) && !empty($_POST['hospitalId'])) {
            $id = $_POST['hospitalId'];
            $stmt = $conn->prepare("UPDATE hospitales SET nombre_hospital = ?, direccion_hospital = ?, latitud = ?, longitud = ?, tipo_hospital = ? WHERE id_hospital = ?");
            $stmt->bind_param("sssssi", $nombre, $direccion, $latitud, $longitud, $tipoHospital, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO hospitales (nombre_hospital, direccion_hospital, latitud, longitud, tipo_hospital,estado) VALUES (?, ?, ?, ?, ?,'ACTIVO')");
            $stmt->bind_param("sssss", $nombre, $direccion, $latitud, $longitud, $tipoHospital);
        }
        $stmt->execute();
        echo json_encode(["success" => true, "id" => $stmt->insert_id]);
        break;

    case 'DELETE':
        // Eliminar hospital
        $id = $_GET['id'];
        $stmt = $conn->prepare("UPDATE hospitales SET estado='INACTIVO' WHERE id_hospital = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["success" => true]);
        break;

    default:
        echo json_encode(["error" => "Método no soportado"]);
        break;
}

// Cerrar conexión
$conn->close();
?>