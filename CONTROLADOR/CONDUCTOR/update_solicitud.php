<?php

// Configuración de la conexión a la base de datos
$host = 'localhost';
$db = 'sorag';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

header('Content-Type: application/json'); // Establecer el tipo de contenido a JSON

// Obtener los datos POST
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['estado'])) {
    $id_solicitud = $data['id'];
    $nuevo_estado = $data['estado'];

    // Validar el ID de la solicitud
    if (!is_numeric($id_solicitud)) {
        echo json_encode(['success' => false, 'message' => 'ID de solicitud no válido.']);
        exit;
    }

    // Preparar la consulta SQL
    $query = "UPDATE solicitudes SET estado = ? WHERE id_solicitud = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("si", $nuevo_estado, $id_solicitud);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Estado de la solicitud actualizado.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado.']);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
}

$conn->close(); // Cerrar la conexión a la base de datos
?>