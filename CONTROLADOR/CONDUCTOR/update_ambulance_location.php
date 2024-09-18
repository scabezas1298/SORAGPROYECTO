<?php
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

// Obtén los datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['latitude']) || !isset($data['longitude'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$userId = $_SESSION['id_usuario'];
$latitude = $data['latitude'];
$longitude = $data['longitude'];

// Actualiza la ubicación de la ambulancia en la base de datos
$sql = "UPDATE ambulancias SET latitud = ?, longitud = ? WHERE conductor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ddi', $latitude, $longitude, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al actualizar la ubicación']);
}

$stmt->close();
$conn->close();
?>