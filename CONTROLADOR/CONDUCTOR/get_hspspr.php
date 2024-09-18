<?php
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

// Consulta para obtener todos los nodos
$sql = "SELECT * FROM hospitales WHERE tipo_hospital='HSP SPR' and estado='ACTIVO'";
$result = $conn->query($sql);

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
