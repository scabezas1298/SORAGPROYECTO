<?php require_once "vistas/parte_superior.php"?>
<!--INICIO del cont principal-->
<div class="container">
    <h1>Solicitudes</h1>
    <?php
    include_once 'bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    $consulta = "SELECT id_solicitud, cedula_solicitante, fecha_solicitud, evidencia FROM solicitudes where estado='GENERADO'";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="tablaSolicitudes" class="table table-striped table-bordered table-condensed" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th>ID</th>
                                <th>Cédula</th>
                                <th>Fecha</th>
                                <th>Evidencia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data as $dat) { ?>
                                <tr>
                                    <td><?php echo $dat['id_solicitud'] ?></td>
                                    <td><?php echo $dat['cedula_solicitante'] ?></td>
                                    <td><?php echo $dat['fecha_solicitud'] ?></td>
                                    <td>
                                        <?php if (!empty($dat['evidencia'])) { ?>
                                            <button class="btn btn-info btn-sm" onclick="verEvidencia('<?php echo $dat['evidencia'] ?>')">Ver Imagen</button>
                                        <?php } else { ?>
                                            No hay evidencia
                                        <?php } ?>
                                    </td>
                                    <td></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--FIN del cont principal-->
<?php require_once "vistas/parte_inferior.php"?>

<!-- Modal para mostrar la imagen -->
<div class="modal fade" id="modalEvidencia" tabindex="-1" role="dialog" aria-labelledby="modalEvidenciaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEvidenciaLabel">Evidencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="imagenEvidencia" src="" alt="Evidencia" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function verEvidencia(ruta) {
    const basePath = '../vistas/ciudadano/evidencia/';
    document.getElementById('imagenEvidencia').src = basePath + ruta;
    $('#modalEvidencia').modal('show');
}
</script>