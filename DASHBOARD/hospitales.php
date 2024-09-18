<?php require_once "vistas/parte_superior.php" ?>
<?php require_once "../config/sweet.php" ?>
<!--INICIO del cont principal-->
<div class="container">
    <h1>Hospitales</h1>
    <?php
    include_once 'bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Obtener la lista de hospitales
    $consulta = "SELECT h.id_hospital, h.nombre_hospital, h.direccion_hospital, h.latitud, h.longitud, h.tipo_hospital, tn.nombre_tipo_hospital 
    FROM hospitales h
    INNER JOIN tipo_hospital tn ON h.tipo_hospital = tn.codigo_tipo_hospital
    WHERE estado='ACTIVO'";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener tipos de hospitales para el select
    $consultaTipos = "SELECT codigo_tipo_hospital, nombre_tipo_hospital FROM tipo_hospital";
    $resultadoTipos = $conexion->prepare($consultaTipos);
    $resultadoTipos->execute();
    $tipos = $resultadoTipos->fetchAll(PDO::FETCH_ASSOC);
    ?>
    
    <div class="container">
        <div class="row mb-3">
            <div class="col-lg-12">
                <button class="btn btn-success" onclick="abrirModal()">Añadir Hospital</button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="tablaHospitales" class="table table-striped table-bordered table-condensed" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Latitud</th>
                        <th>Longitud</th>
                        <th>Tipo de Hospital</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $dat) { ?>
                        <tr>
                            <td><?php echo $dat['id_hospital'] ?></td>
                            <td><?php echo $dat['nombre_hospital'] ?></td>
                            <td><?php echo $dat['direccion_hospital'] ?></td>
                            <td><?php echo $dat['latitud'] ?></td>
                            <td><?php echo $dat['longitud'] ?></td>
                            <td><?php echo $dat['nombre_tipo_hospital'] ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-primary" onclick="editarHospital(<?php echo $dat['id_hospital'] ?>, '<?php echo $dat['nombre_hospital'] ?>', '<?php echo $dat['direccion_hospital'] ?>', '<?php echo $dat['latitud'] ?>', '<?php echo $dat['longitud'] ?>', '<?php echo $dat['tipo_hospital'] ?>')">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-danger" onclick="confirmarEliminar(<?php echo $dat['id_hospital'] ?>)">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--FIN del cont principal-->
<?php require_once "vistas/parte_inferior.php" ?>

<!-- Modal para añadir y editar hospital -->
<div class="modal fade" id="modalHospital" tabindex="-1" role="dialog" aria-labelledby="modalHospitalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHospitalLabel">Añadir Hospital</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formHospital">
                    <input type="hidden" id="hospitalId" name="hospitalId" value="">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre_hospital" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion_hospital" required>
                    </div>
                    <div class="form-group">
                        <label for="latitud">Latitud</label>
                        <input type="text" class="form-control" id="latitud" name="latitud" required>
                    </div>
                    <div class="form-group">
                        <label for="longitud">Longitud</label>
                        <input type="text" class="form-control" id="longitud" name="longitud" required>
                    </div>
                    <div class="form-group">
                        <label for="tipoHospital">Tipo de Hospital</label>
                        <select class="form-control" id="tipoHospital" name="codigo_tipo_hospital" required>
                            <option value="" disabled selected>---Selecciona un tipo---</option>
                            <?php foreach ($tipos as $tipo) { ?>
                                <option value="<?php echo $tipo['codigo_tipo_hospital'] ?>"><?php echo $tipo['nombre_tipo_hospital'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Font Awesome y SweetAlert -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
function abrirModal() {
    document.getElementById('formHospital').reset();
    document.getElementById('modalHospitalLabel').innerText = 'Añadir Hospital';
    document.getElementById('tipoHospital').value = ""; // Resetear el select
    $('#modalHospital').modal('show');
}

function editarHospital(id, nombre, direccion, latitud, longitud, tipo) {
    document.getElementById('hospitalId').value = id;
    document.getElementById('modalHospitalLabel').innerText = 'Editar Hospital';
    document.getElementById('nombre').value = nombre;
    document.getElementById('direccion').value = direccion;
    document.getElementById('latitud').value = latitud;
    document.getElementById('longitud').value = longitud;
    document.getElementById('tipoHospital').value = tipo; // Seleccionar el tipo correspondiente
    $('#modalHospital').modal('show');
}

function confirmarEliminar(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar!'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarHospital(id);
        }
    });
}

function eliminarHospital(id) {

        // Llamada AJAX para eliminar el hospital de la base de datos
        $.ajax({
            url: 'bd/crudHospitales.php?id=' + id,
            type: 'DELETE',
            success: function(response) {
                location.reload(); // O puedes actualizar solo la tabla
            },
            error: function(xhr, status, error) {
                console.error('Error al eliminar el hospital:', error);
            }
        });
}

// Manejo del envío del formulario (ejemplo)
document.getElementById('formHospital').onsubmit = function(event) {
    event.preventDefault();
    
    // Recoger datos del formulario
    const formData = $(this).serialize(); // Usando jQuery para serializar el formulario

    // Lógica para guardar o actualizar el hospital
    $.ajax({
        url: 'bd/crudHospitales.php',
        type: 'POST', // O 'PUT' si es una actualización
        data: formData,
        success: function(response) {
            $('#modalHospital').modal('hide');
            location.reload(); // O actualizar la tabla
        },
        error: function(xhr, status, error) {
            console.error('Error al guardar/actualizar el hospital:', error);
        }
    });
};

$(document).ready(function(){
    tablaHospitales = $("#tablaHospitales").DataTable({       
        //Para cambiar el lenguaje a español
    "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
             },
             "sProcessing":"Procesando...",
        }
    });
});
</script>