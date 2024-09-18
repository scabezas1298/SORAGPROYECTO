<?php require_once "header.php"?>

<!-- Incluir Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<?php
// Obtener datos para el select
require_once '../../config/conexion.php';
require_once '../../controlador/consultas_base.php';

$id_usuario = $_SESSION['id_usuario'];
$cedula = $_SESSION['cedula'];
$solicitudData = obtenerSolicitudes($cedula);
?>
<div id="map" style="display: none; position: relative; height: 400px; width: 100%;"></div>
<button id="closeMap" onclick="cerrarMapa()" style="display: none; position: absolute; top: 10px; right: 10px; z-index: 1000; background: none; border: none; cursor: pointer; color: red;">
    <i class="fas fa-times"></i> <!-- Ícono de cerrar -->
</button>
<table>
    <thead>
        <tr>
            <th>ID Solicitud</th>
            <th>Fecha Solicitud</th>
            <th>Número de Pacientes</th>
            <th>Tipo de emergencia</th>
            <th>Estado</th>
            <th>Ver</th> <!-- Nueva columna para el ícono -->
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($solicitudData) > 0) {
            foreach ($solicitudData as $solicitud) {
                echo "<tr>";
                echo "<td>" . $solicitud["id_solicitud"] . "</td>";
                echo "<td>" . $solicitud["fecha_solicitud"] . "</td>";
                echo "<td>" . $solicitud["numero_pacientes"] . " Paciente(s)</td>";
                echo "<td>" . $solicitud["nmb_tipo_emergencia"] . "</td>";
                echo "<td>" . $solicitud["estado"] . "</td>";

                // Verificar si hay ambulancia asignada
                if (!empty($solicitud["ambulancia_asignada"]) && $solicitud['estado']=='APROBADO' or $solicitud['estado']=='EN CAMINO') {
                    $latSolicitud = $solicitud["latitude"]; // Suponiendo que tienes latitud
                    $lngSolicitud = $solicitud["longitude"]; // Suponiendo que tienes longitud
                    $latAmbulancia = $solicitud["x_ambulancia"]; // Latitud de la ambulancia
                    $lngAmbulancia = $solicitud["y_ambulancia"]; // Longitud de la ambulancia
                    echo "<td><a href='javascript:void(0);' onclick='verRuta($latSolicitud, $lngSolicitud, $latAmbulancia, $lngAmbulancia)'><i class='fas fa-eye'></i></a></td>";
                } else {
                    echo "<td>No disponible</td>";
                }

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No se encontraron solicitudes</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Mapa para mostrar la ruta -->

<script>
    let map;
    let directionsService;
    let directionsRenderer;
    let intervalId;

    function initMap() {
        // Inicializar el mapa y los servicios
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            styles: [
                            {
                                featureType: 'poi',
                                elementType: 'labels',
                                stylers: [{ visibility: 'off' }] // Ocultar etiquetas de puntos de interés
                            },
                    ]// Cambia esto a la ubicación inicial deseada
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
                        map: map,
                        suppressMarkers: true 
                    });
        directionsRenderer.setMap(map);
    }

    function verRuta(latSolicitud, lngSolicitud, latAmbulancia, lngAmbulancia) {
        document.getElementById("map").style.display = 'block'; // Mostrar el mapa
        document.getElementById("closeMap").style.display = 'block'; // Mostrar el botón de cerrar

        // Reiniciar el mapa cada 10 segundos
        intervalId = setInterval(() => {
            recargarMapa(latSolicitud, lngSolicitud, latAmbulancia, lngAmbulancia);
        }, 10000); // 10000 ms = 10 segundos;

        // Cargar inicialmente la ruta
        recargarMapa(latSolicitud, lngSolicitud, latAmbulancia, lngAmbulancia);
    }

    function recargarMapa(latSolicitud, lngSolicitud, latAmbulancia, lngAmbulancia) {
        // Reinicializar el mapa
        map.setCenter({ lat: latSolicitud, lng: lngSolicitud });
        const userIcon = '../../public/img/alerta.png';
        const markerSolicitud = new google.maps.Marker({
            position: { lat: latSolicitud, lng: lngSolicitud },
            map: map,
            title: "Solicitud",
            icon: {
                            url: userIcon,
                            scaledSize: new google.maps.Size(32, 32) // Escalar el ícono
                        },
        });
        const ambulanceIcon = '../../public/img/ambulancia.png';
        const markerAmbulancia = new google.maps.Marker({
            position: { lat: latAmbulancia, lng: lngAmbulancia },
            map: map,
            title: "Ambulancia",
            icon: {
                            url: ambulanceIcon,
                            scaledSize: new google.maps.Size(32, 32) // Escalar el ícono
                        },
        });

        // Trazar la ruta entre la solicitud y la ambulancia
        const request = {
            origin: { lat: latSolicitud, lng: lngSolicitud },
            destination: { lat: latAmbulancia, lng: lngAmbulancia },
            travelMode: 'DRIVING'
        };

        directionsService.route(request, (result, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(result);
            } else {
                alert('No se pudo trazar la ruta: ' + status);
            }
        });
    }

    function cerrarMapa() {
        document.getElementById("map").style.display = 'none'; // Ocultar el mapa
        document.getElementById("closeMap").style.display = 'none'; // Ocultar el botón de cerrar
        clearInterval(intervalId); // Detener la recarga del mapa
    }
    </script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDtBAMj3t5VyKAOJnw3_B8IilblDlFXa6c&callback=initMap"></script> <!-- Reemplaza YOUR_API_KEY -->

<style>
    /* Estilos adicionales para responsividad */
    #map {
        height: 300px; /* Altura del mapa en pantallas pequeñas */
    }

    @media (min-width: 600px) {
        #map {
            height: 400px; /* Altura del mapa en pantallas grandes */
        }
    }
</style>

<?php require_once "footer.php"?>