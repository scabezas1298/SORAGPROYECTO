<?php
require 'header.php';

// Configuración de la conexión a la base de datos
$host = 'localhost';
$db = 'sorag';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

// Obtener el ID de la ambulancia usando la sesión del usuario
$id_usuario = $_SESSION['id_usuario']; // Asumiendo que el ID del usuario está en la sesión
$query = "SELECT id_ambulancia FROM ambulancias WHERE conductor = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$ambulancia = $result->fetch_assoc();
$ambulanceId = $ambulancia ? $ambulancia['id_ambulancia'] : null; // Obtén el ID de la ambulancia

$conn->close(); // Cierra la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorag</title>
    <style>
        /* Estilo para el contenedor del mapa */
        #map {
            height: 500px;
            width: 100%;
        }

        /* Estilo para el formulario */
        .form-container {
            max-width: 400px; /* Ancho máximo del contenedor */
            margin: 20px auto; /* Centra el contenedor horizontalmente */
            padding: 15px; /* Espaciado interno */
            background-color: #f2f2f2;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra para profundidad */
        }

        fieldset {
            border: none;
            padding: 0;
            margin-bottom: 5px;
        }

        legend {
            font-weight: bold;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="radio"] {
            margin-right: 5px;
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

        @media (max-width: 480px) {
            #map {
                height: 350px;
                width: 100%;
            }

            .form-container {
                max-width: 100%;
                padding: 10px;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Contenedor del mapa -->
    <div id="map"></div>

    <!-- Contenedor del formulario -->
    <div class="form-container">
        <form id="insuranceForm">
            <fieldset class="form-group">
                <legend>Tipo de Seguro</legend>
                <label><input type="radio" name="insurance" value="1" checked> Sin asegurar</label>
                <label><input type="radio" name="insurance" value="2"> Seguro público</label>
                <label><input type="radio" name="insurance" value="3"> Seguro privado</label>
            </fieldset>
            <button type="button" onclick="redirectToPage()">Obtener hospital</button>
        </form>
    </div>

    <!-- Incluir el JS de Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDtBAMj3t5VyKAOJnw3_B8IilblDlFXa6c&loading=async&callback=initMap" async defer></script>
    <script>
        let map;
        let directionsService;
        let directionsRenderer;
        let userMarker;
        let closestNodeMarker;
        let updateInterval = 10000; // 10 segundos
        const ambulanceId = <?php echo json_encode($ambulanceId); ?>; // Obtener el ID de la ambulancia desde PHP

        function initMap() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userLat = position.coords.latitude;
                    const userLon = position.coords.longitude;

                    // Inicializa el mapa
                    map = new google.maps.Map(document.getElementById('map'), {
                        center: { lat: userLat, lng: userLon },
                        zoom: 13,
                        styles: [
                            {
                                featureType: 'poi',
                                elementType: 'labels',
                                stylers: [{ visibility: 'off' }] // Ocultar etiquetas de puntos de interés
                            },
                        ]
                    });

                    const userIcon = '../../public/img/ambulancia.png'; // Reemplaza con la URL de tu PNG

                    userMarker = new google.maps.Marker({
                        position: { lat: userLat, lng: userLon },
                        map: map,
                        icon: {
                            url: userIcon,
                            scaledSize: new google.maps.Size(32, 32) // Escalar el ícono
                        },
                        title: 'Tu ubicación'
                    });
                    directionsService = new google.maps.DirectionsService();
                    directionsRenderer = new google.maps.DirectionsRenderer({
                        map: map,
                        suppressMarkers: true 
                    });

                    // Actualiza la ubicación de la ambulancia
                    updateAmbulanceLocation(userLat, userLon);
                    
                    setInterval(updateRoute, updateInterval);
                }, function(error) {
                    console.error("Error al obtener la ubicación: " + error.message);
                });
            } else {
                console.error("Geolocalización no es soportada por este navegador.");
            }
        }

        function updateAmbulanceLocation(lat, lon) {
            fetch('../../controlador/conductor/update_ambulance_location.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lon
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al actualizar la ubicación de la ambulancia');
                }
                return response.json();
            })
            .then(data => {
                console.log('Ubicación de la ambulancia actualizada:', data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function redirectToPage() {
            const insuranceType = document.querySelector('input[name="insurance"]:checked').value;
            let targetUrl = '';

            switch (insuranceType) {
                case '1':
                    targetUrl = 'hospitalessa.php';
                    break;
                case '2':
                    targetUrl = 'hospitalesspu.php';
                    break;
                case '3':
                    targetUrl = 'hospitalesspr.php'; // Agrega más casos si tienes más tipos
                    break;
                default:
                    console.error('Tipo de seguro no válido');
                    return;
            }

            // Redirige a la URL correspondiente
            window.location.href = targetUrl;
        }

        function updateRoute() {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLat = position.coords.latitude;
                const userLon = position.coords.longitude;
                map.setCenter({ lat: userLat, lng: userLon });

                fetch('../../controlador/conductor/get_nodes.php')
                    .then(response => response.json())
                    .then(nodes => {
                        if (nodes.length > 0) {
                            const destinations = nodes.map(node => `${node.latitude},${node.longitude}`);
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
                                        const nodeLat = parseFloat(closestNode.latitude);
                                        const nodeLon = parseFloat(closestNode.longitude);

                                        const nodeIcon = '../../public/img/alerta.png'; // Reemplaza con la URL de tu PNG

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

                                        // Actualizar la tabla de solicitudes con los datos de la ambulancia
                                        updateRequestWithAmbulance(closestNode.id_solicitud, userLat, userLon);
                                        updateAmbulanceLocation(userLat, userLon);
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

        function updateRequestWithAmbulance(requestId, lat, lon) {
            const data = {
                request_id: requestId,
                ambulance_id: ambulanceId,
                latitude: lat,
                longitude: lon
            };
            console.log(data); // Agrega esto para verificar los datos

            fetch('../../controlador/conductor/update_request.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al actualizar la solicitud');
                }
                return response.json();
            })
            .then(data => {
                console.log('Solicitud actualizada con la ambulancia:', data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
<?php
require 'footer.php';
?>