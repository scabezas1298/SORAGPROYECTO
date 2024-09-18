<?php
require 'header.php';
require '../../config/sweet.php';
// Configuración de la conexión a la base de datos
$host = 'localhost';
$db = 'sorag';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

$id_usuario = $_SESSION['id_usuario']; // Asumiendo que el ID del usuario está en la sesión
$query = "SELECT id_ambulancia FROM ambulancias WHERE conductor = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$ambulancia = $result->fetch_assoc();
$ambulanceId = $ambulancia ? $ambulancia['id_ambulancia'] : null;

// Obtener el ID de la solicitud usando la sesión del usuario
$id_usuario = $_SESSION['id_usuario']; // Asumiendo que el ID del usuario está en la sesión
$query = "SELECT id_solicitud FROM solicitudes WHERE ambulancia_asignada = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ambulanceId);
$stmt->execute();
$result = $stmt->get_result();
$solicitud = $result->fetch_assoc();
$solicitudId = $solicitud ? $solicitud['id_solicitud'] : null; // Obtén el ID de la solicitud

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital</title>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
        .back-button {
            display: block;
            font-size: 16px;
            cursor: pointer;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        .complete-button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .complete-button:hover {
            background-color: #218838;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <!-- Botón para volver -->
    <button class="back-button" onclick="goBack()">Volver</button>

    <!-- Contenedor del mapa -->
    <div id="map"></div>
    <button class="complete-button" onclick="completeService()">Finalizar Emergencia</button>
    
    <!-- Incluir el JS de Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDtBAMj3t5VyKAOJnw3_B8IilblDlFXa6c&loading=async&callback=initMap" async defer></script>
    <script>
        let map;
        let directionsService;
        let directionsRenderer;
        let userMarker;
        let closestNodeMarker;
        let infoWindow;
        const solicitudId = <?php echo json_encode($solicitudId); ?>; // Obtener el ID de solicitud desde PHP

        function initMap() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userLat = position.coords.latitude;
                    const userLon = position.coords.longitude;

                    map = new google.maps.Map(document.getElementById('map'), {
                        center: { lat: userLat, lng: userLon },
                        zoom: 13,
                        styles: [
                            {
                                featureType: 'poi',
                                elementType: 'labels',
                                stylers: [{ visibility: 'off' }]
                            },
                        ]
                    });

                    const userIcon = '../../public/img/ambulancia.png';

                    userMarker = new google.maps.Marker({
                        position: { lat: userLat, lng: userLon },
                        map: map,
                        icon: {
                            url: userIcon,
                            scaledSize: new google.maps.Size(32, 32)
                        },
                        title: 'Tu ubicación'
                    });

                    directionsService = new google.maps.DirectionsService();
                    directionsRenderer = new google.maps.DirectionsRenderer({
                        map: map,
                        suppressMarkers: true
                    });

                    infoWindow = new google.maps.InfoWindow();

                    function updateRoute() {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const userLat = position.coords.latitude;
                            const userLon = position.coords.longitude;
                            map.setCenter({ lat: userLat, lng: userLon });

                            fetch('../../controlador/conductor/get_hspspr.php')
                                .then(response => response.json())
                                .then(nodes => {
                                    if (nodes.length > 0) {
                                        const destinations = nodes.map(node => `${node.latitud},${node.longitud}`);
                                        const service = new google.maps.DistanceMatrixService();

                                        service.getDistanceMatrix({
                                            origins: [{ lat: userLat, lng: userLon }],
                                            destinations: destinations,
                                            travelMode: 'DRIVING',
                                            unitSystem: google.maps.UnitSystem.METRIC
                                        }, function(response, status) {
                                            if (status === 'OK') {
                                                const results = response.rows[0].elements;
                                                let minTime = Infinity;
                                                let closestNode = null;

                                                results.forEach((result, index) => {
                                                    if (result.duration.value < minTime) {
                                                        minTime = result.duration.value;
                                                        closestNode = nodes[index];
                                                    }
                                                });

                                                if (closestNode) {
                                                    const nodeLat = parseFloat(closestNode.latitud);
                                                    const nodeLon = parseFloat(closestNode.longitud);
                                                    const nodeIcon = '../../public/img/hospital.png';

                                                    if (closestNodeMarker) {
                                                        closestNodeMarker.setPosition({ lat: nodeLat, lng: nodeLon });
                                                    } else {
                                                        closestNodeMarker = new google.maps.Marker({
                                                            position: { lat: nodeLat, lng: nodeLon },
                                                            map: map,
                                                            icon: {
                                                                url: nodeIcon,
                                                                scaledSize: new google.maps.Size(32, 32)
                                                            },
                                                            title: 'Nodo más cercano'
                                                        });

                                                        const contentString = `
                                                            <div>
                                                                <p>Nombre del Hospital: ${closestNode.nombre_hospital}</p>                                  
                                                            </div>
                                                        `;

                                                        infoWindow.setContent(contentString);
                                                        infoWindow.open(map, closestNodeMarker);
                                                    }

                                                    const request = {
                                                        origin: { lat: userLat, lng: userLon },
                                                        destination: { lat: nodeLat, lng: nodeLon },
                                                        travelMode: 'DRIVING'
                                                    };

                                                    directionsService.route(request, (result, status) => {
                                                        if (status === 'OK') {
                                                            directionsRenderer.setDirections(result);
                                                        } else {
                                                            console.error('Error al trazar la ruta:', status);
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                })
                                .catch(error => console.error('Error al obtener los nodos:', error));
                        }, function(error) {
                            console.error("Error al obtener la ubicación: " + error.message);
                        });
                    }

                    setInterval(updateRoute, 10000);
                }, function(error) {
                    console.error("Error al obtener la ubicación: " + error.message);
                });
            } else {
                console.error("Geolocalización no es soportada por este navegador.");
            }
        }

        function goBack() {
            window.history.back();
        }

        function completeService() {
            if (solicitudId) {
                fetch('../../controlador/conductor/update_solicitud.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: solicitudId, estado: 'ATENDIDO' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                            Swal.fire({
                            title: 'SOLICITUD ATENDIDA',
                            text: 'La solicitud ha sido atendida',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'conductor.php'; 
                            }
                            });
                    } else {
                        alert("Error al actualizar el estado de la solicitud.");
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                alert("No se pudo obtener el ID de la solicitud.");
            }
        }
    </script>
</body>
</html>
<?php
require 'footer.php';
?>