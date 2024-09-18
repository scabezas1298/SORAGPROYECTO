$(document).ready(function(){
    tablaSolicitudes = $("#tablaSolicitudes").DataTable({
       "columnDefs":[{
        "targets": -1,
        "data":null,
        "defaultContent": "<div class='text-center'><div class='btn-group'><button class='btn btn-success btnAprobar'>Aprobar</button><button class='btn btn-danger btnCancelar'>Cancelar</button></div></div>"  
       }],
        
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
    
var fila; //capturar la fila para editar o borrar el registro
 
$(document).on("click", ".btnAprobar", function(){
    fila = $(this);
    id = parseInt($(this).closest("tr").find('td:eq(0)').text());
    opcion = 2; //editar

    Swal.fire({
        title: '¿Está seguro?',
        text: `¿Está seguro de aprobar la solicitud: ${id}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, aprobar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "bd/crudSolicitud.php",
                type: "POST",
                dataType: "json",
                data: {opcion:opcion, id:id},
                success: function(){
                    tablaSolicitudes.row(fila.parents('tr')).remove().draw();
                }
            });
        }
    });
});

//botón BORRAR
$(document).on("click", ".btnCancelar", function(){    
    fila = $(this);
    id = parseInt($(this).closest("tr").find('td:eq(0)').text());
    var cedula = $(this).closest("tr").find('td:eq(1)').text(); 
    var fecha = $(this).closest("tr").find('td:eq(2)').text();
    opcion = 3; //borrar

    Swal.fire({
        title: '¿Está seguro?',
        text: `¿Está seguro de cancelar la solicitud: ${id}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "bd/crudSolicitud.php",
                type: "POST",
                dataType: "json",
                data: {opcion:opcion, id:id,cedula: cedula,fecha: fecha},
                success: function(){
                    tablaSolicitudes.row(fila.parents('tr')).remove().draw();
                }
            });
        }
    });
});
});









