<?php
require 'upload.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capturar y Guardar Foto</title>
    <link rel="stylesheet" href="../../../public/css/ciudadano/estilo_foto.css">
</head>
<body>
    <h1>Agregar evidencia</h1>
    <video id="video" width="320" height="240" autoplay></video>
    <button id="capturar">Capturar Foto</button>
    <canvas id="canvas" width="320" height="240"></canvas>
    <form id="form" method="post" enctype="multipart/form-data" action="upload.php">
        <input type="hidden" name="image" id="image">
        <button id="ingresar" type="submit">Guardar evidencia</button>
    </form>
    <button id="volver" onclick="window.history.back()">Volver</button>
    <script>
        // Acceder a la cámara
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    // Solicitar acceso a la cámara trasera
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
      .then(function(stream) {
        var video = document.getElementById('video');
        video.srcObject = stream;
        video.play();
      })
      .catch(function(err) {
        console.log("Ocurrió un error: " + err);
      });
  } else {
    console.log("La API de MediaDevices no es compatible con este navegador.");
  }

        // Capturar la imagen
        document.getElementById('capturar').addEventListener('click', function() {
            var canvas = document.getElementById('canvas');
            var video = document.getElementById('video');
            var context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            var dataURL = canvas.toDataURL('image/png');
            document.getElementById('image').value = dataURL;
        });
    </script>
</body>
</html>
