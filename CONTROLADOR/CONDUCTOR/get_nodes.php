<?php
session_start();
header('Content-Type: application/json');

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

// Lógica para filtrar solicitudes según la capacidad de la ambulancia
if ($capacity == 2) {
    $sql = "SELECT * FROM solicitudes WHERE estado='APROBADO' AND numero_pacientes <= 2";
} elseif ($capacity == 4) {
    $sql = "SELECT * FROM solicitudes WHERE estado='APROBADO' AND numero_pacientes >= 3";
} else {
    echo json_encode(['error' => 'Capacidad de ambulancia no válida']);
    exit;
}

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$nodes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nodes[] = $row;
    }
}

echo json_encode($nodes);

$result->close();
$conn->close();
?>
