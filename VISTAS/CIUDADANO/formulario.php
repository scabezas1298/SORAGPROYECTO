<?php
        // Incluir archivos
        
        require_once '../../config/conexion.php'; 
        require_once '../../controlador/ciudadano/ingresar_solicitudes.php';
        require_once '../../controlador/consultas_base.php';
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $grupo1 = isset($_POST['grupo1']) ? htmlspecialchars($_POST['grupo1']) : 'No seleccionado';
        $grupo2 = isset($_POST['grupo2']) ? htmlspecialchars($_POST['grupo2']) : 'No seleccionado';
    }
    ?>

<form action="#" method="post">
        <input type="hidden" id="cx" name="cx">

        <input type="hidden" id="cy" name="cy">

        <input type="hidden" id="id_usuario" name="cedula_usuario" value="<?php echo $_SESSION['cedula']; ?>">
        <div class="container">
            <div class="column">
                <h3>Número de pacientes</h3>
                <label>
                    <input type="radio" name="grupo1" class="grupo1" value="1"> Un paciente
                </label>
                <label>
                    <input type="radio" name="grupo1" class="grupo1" value="2"> Dos pacientes
                </label>
                <label>
                    <input type="radio" name="grupo1" class="grupo1" value="3"> Tres pacientes
                </label>
                <label>
                    <input type="radio" name="grupo1" class="grupo1" value="4"> 4 o más pacientes
                </label>

            </div>


            <div class="column">
            <h3>Tipo de emergencia</h3>
            <?php obtenerTipoEmergencia(); // Llamar a la función definida en ingresar_solicitudes.php ?>
            </div>


            <div class="columnb">
            <div class="capturar">
            <ul>
                <li>
                    <a href="evidencia/evidencia.php" class="active">TOMAR EVIDENCIA</a>
                </li>
            </ul>
            </div>
            </div>


        </div>

        <div class="centro">
        <input id="ingresar" name="btningresar" class="btn" type="submit" value="ENVIAR">
        <input name="btnborrar" class="btnborrar" type="submit" value="CANCELAR">
        </div>
</form>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Restaurar las selecciones de los grupos de radio desde localStorage
            var grupo1Seleccionado = localStorage.getItem('grupo1Seleccionado');
            if (grupo1Seleccionado) {
                var radiosGrupo1 = document.getElementsByClassName('grupo1');
                for (var i = 0; i < radiosGrupo1.length; i++) {
                    if (radiosGrupo1[i].value === grupo1Seleccionado) {
                        radiosGrupo1[i].checked = true;
                        break;
                    }
                }
            }

            var grupo2Seleccionado = localStorage.getItem('grupo2Seleccionado');
            if (grupo2Seleccionado) {
                var radiosGrupo2 = document.getElementsByClassName('grupo2');
                for (var i = 0; i < radiosGrupo2.length; i++) {
                    if (radiosGrupo2[i].value === grupo2Seleccionado) {
                        radiosGrupo2[i].checked = true;
                        break;
                    }
                }
            }

            // Guardar el valor seleccionado en localStorage cuando se selecciona una opción
            var radiosGrupo1 = document.getElementsByClassName('grupo1');
            for (var i = 0; i < radiosGrupo1.length; i++) {
                radiosGrupo1[i].addEventListener('change', function() {
                    localStorage.setItem('grupo1Seleccionado', this.value);
                });
            }

            var radiosGrupo2 = document.getElementsByClassName('grupo2');
            for (var i = 0; i < radiosGrupo2.length; i++) {
                radiosGrupo2[i].addEventListener('change', function() {
                    localStorage.setItem('grupo2Seleccionado', this.value);
                });
            }
        });

        // Limpiar localStorage al enviar el formulario
        document.getElementById('formulario').addEventListener('submit', function() {
            localStorage.removeItem('grupo1Seleccionado');
            localStorage.removeItem('grupo2Seleccionado');
        });
    </script>
               
            