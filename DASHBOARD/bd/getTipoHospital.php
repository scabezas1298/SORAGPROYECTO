<?php
// Configura tus credenciales de base de datos
$host = 'localhost'; // Cambia según tu configuración
$db = 'sorag'; // Cambia por tu base de datos
$user = 'root'; // Cambia por tu usuario
$pass = ''; // Cambia por tu contraseña

// Conexión a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los tipos de hospital
$sql = "SELECT id_tipo_nodo,nombre_nodo from tipo_nodo where codigo_nodo!='SLC'";
$result = $conn->query($sql);

$tiposHospital = [];

if ($result->num_rows > 0) {
    // Almacena los resultados en un array
    while($row = $result->fetch_assoc()) {
        $tiposHospital[] = $row;
    }
}

// Cierra la conexión
$conn->close();

// Establece el tipo de contenido a JSON
header('Content-Type: application/json');

// Devuelve los resultados en formato JSON
echo json_encode($tiposHospital);
?>