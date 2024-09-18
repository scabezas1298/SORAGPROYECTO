<?php
header('Content-Type: application/json');
session_start();
// Conexión a la base de datos MySQL
$host = 'localhost';
$db = 'sorag';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}


// Verifica que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$userId = $_SESSION['id_usuario'];

$sqlAmbulance = "SELECT id_ambulancia,capacidad FROM ambulancias WHERE conductor = ?";
$stmtAmbulance = $conn->prepare($sqlAmbulance);
$stmtAmbulance->bind_param('i', $userId);
$stmtAmbulance->execute();
$resultAmbulance = $stmtAmbulance->get_result();

if ($resultAmbulance->num_rows > 0) {
    $ambulance = $resultAmbulance->fetch_assoc();
    $capacity = $ambulance['capacidad'];
    $id_ambulancia = $ambulance['id_ambulancia'];
} else {
    echo json_encode(['error' => 'Ambulancia no encontrada']);
    exit;
}

// Obtiene los datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['request_id']) || !isset($data['latitude']) || !isset($data['longitude'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$requestId = $data['request_id'];
$ambulanceId = $id_ambulancia;
$latitude = $data['latitude'];
$longitude = $data['longitude'];

// Actualiza la solicitud en la base de datos
$sql = "UPDATE solicitudes SET ambulancia_asignada = ?, x_ambulancia = ?, y_ambulancia = ? ,estado='EN CAMINO' WHERE id_solicitud = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iddi', $ambulanceId, $latitude, $longitude, $requestId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al actualizar la solicitud']);
}

$stmt->close();
$conn->close();
?>