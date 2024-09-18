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
        // Leer ubicaciones
        $result = $conn->query("SELECT * FROM ubicaciones");
        $ubicaciones = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($ubicaciones);
        break;

    case 'POST':
        // Crear o actualizar ubicación
        $nombre = $_POST['nombre_ubicacion'];
        $direccion = $_POST['direccion_ubicacion'];
        $latitud = $_POST['latitud'];
        $longitud = $_POST['longitud'];

        // Si hay un ID, se actualiza; si no, se crea uno nuevo
        if (isset($_POST['ubicacionId']) && !empty($_POST['ubicacionId'])) {
            $id = $_POST['ubicacionId'];
            $stmt = $conn->prepare("UPDATE ubicaciones SET nombre_ubicacion = ?, direccion_ubicacion = ?, latitud = ?, longitud = ? WHERE id_ubicaciones = ?");
            $stmt->bind_param("ssssi", $nombre, $direccion, $latitud, $longitud, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO ubicaciones (nombre_ubicacion, direccion_ubicacion, latitud, longitud,estado) VALUES (?, ?, ?, ?,'ACTIVO')");
            $stmt->bind_param("ssss", $nombre, $direccion, $latitud, $longitud);
        }
        $stmt->execute();
        echo json_encode(["success" => true, "id" => $stmt->insert_id]);
        break;

    case 'DELETE':
        // Eliminar ubicación
        $id = $_GET['id'];
        $stmt = $conn->prepare("UPDATE ubicaciones SET estado='INACTIVO' WHERE id_ubicaciones = ?");
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