<?php
include_once 'includes/auth.php';
requireAdmin();
include_once 'includes/inc.head.admin.php';
?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    body {
        color: #333;
    }

    .main {
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 20px;
    }

    h1 {
        color: #104D43;
    }

    .dataTables_wrapper {
        margin-top: 20px;
    }

    table.dataTable {
        border-collapse: separate;
        border-spacing: 0;
        width: 100% !important;
    }

    table.dataTable thead th {
        background-color: #104D43 !important;
        color: white !important;
        border: none;
        padding: 12px 10px;
        font-weight: 600;
    }

    table.dataTable tbody td {
        padding: 12px 10px;
        vertical-align: middle;
    }

    table.dataTable tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa;
    }

    table.dataTable tbody tr:hover {
        background-color: #e9ecef;
    }

    .btn-primary,
    .btn-danger {
        margin-right: 5px;
    }

    .btn-success {
        background-color: #104D43;
        border-color: #104D43;
    }

    .btn-success:hover {
        background-color: #0d3c34;
        border-color: #0d3c34;
    }

    #ofertasTable_wrapper {
        margin: 0 auto;
    }

    .dataTables_filter {
        margin-bottom: 1rem;
    }

    .dataTables_length {
        margin-bottom: 1rem;
    }
</style>

<main class="main">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-10">
                <h1 class="mb-4 text-center"><b>Administración de Ofertas</b></h1>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="Administrador" class="btn btn-outline-secondary mb-3">
                        <i class="bi bi-box-arrow-left"></i> Volver a Productos
                    </a>
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#agregarOfertaModal">
                        <i class="bi bi-plus-circle"></i> Agregar Oferta
                    </button>
                </div>

                <?php include_once 'includes/admin-table-ofertas.php'; ?>

                <a href="logout.php" class="btn btn-danger mt-3">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Modal para Agregar Oferta -->
<div class="modal fade" id="agregarOfertaModal" tabindex="-1" aria-labelledby="agregarOfertaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarOfertaModalLabel" style="color: #000;">Agregar Nueva Oferta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregarOfertaForm" action="controllers/procesar_agregar_oferta.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="dia_semana" class="form-label">Día de la Semana</label>
                        <select class="form-select" id="dia_semana" name="dia_semana" required>
                            <option value="">Seleccione un día</option>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                            <option value="Todos los días">Todos los días</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título de la Oferta</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="visible" class="form-label">Visible</label>
                        <select class="form-select" id="visible" name="visible">
                            <option value="1" selected>Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Oferta -->
<div class="modal fade" id="editarOfertaModal" tabindex="-1" aria-labelledby="editarOfertaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarOfertaModalLabel" style="color: #000;">Editar Oferta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarOfertaForm" action="controllers/procesar_editar_oferta.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_dia_semana" class="form-label">Día de la Semana</label>
                        <select class="form-select" id="edit_dia_semana" name="dia_semana" required>
                            <option value="">Seleccione un día</option>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                            <option value="Todos los días">Todos los días</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_titulo" class="form-label">Título de la Oferta</label>
                        <input type="text" class="form-control" id="edit_titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imagen Actual</label>
                        <div id="imagen_actual" class="mb-2"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_imagen" class="form-label">Nueva Imagen (opcional)</label>
                        <input type="file" class="form-control" id="edit_imagen" name="imagen" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="edit_visible" class="form-label">Visible</label>
                        <select class="form-select" id="edit_visible" name="visible">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'includes/inc.footer.php';
?>

<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('#ofertasTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "js/dataTables.es-ES.json"
            },
            "order": [
                [1, 'asc']
            ],
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 4]
                },
                {
                    "width": "80px",
                    "targets": 0
                },
                {
                    "width": "150px",
                    "targets": 1
                },
                {
                    "width": "200px",
                    "targets": 2
                },
                {
                    "width": "300px",
                    "targets": 3
                },
                {
                    "width": "150px",
                    "targets": 4
                }
            ]
        });

        // Manejar envío del formulario para agregar oferta
        $('#agregarOfertaForm').on('submit', function(e) {
            e.preventDefault(); // Prevenir el envío normal del formulario
            
            var formData = new FormData(this);
            
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                text: 'Guardando la nueva oferta',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log("Respuesta recibida:", response);
                    try {
                        var result = JSON.parse(response);
                        if (result.success) {
                            // Cerrar el modal primero
                            $('#agregarOfertaModal').modal('hide');
                            
                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Oferta agregada con éxito!',
                                text: 'La oferta ha sido agregada correctamente.',
                                showConfirmButton: true,
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#104D43'
                            }).then(() => {
                                // Recargar la página para mostrar la nueva oferta
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Ocurrió un error al agregar la oferta.',
                                confirmButtonColor: '#104D43'
                            });
                        }
                    } catch (e) {
                        console.error("Error parsing JSON:", response, e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la respuesta: ' + e.message,
                            confirmButtonColor: '#104D43'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la solicitud: ' + error,
                        confirmButtonColor: '#104D43'
                    });
                }
            });
        });

        // Manejar clic en botón editar
        $(document).on('click', '.editar-oferta', function() {
            var id = $(this).data('id');

            // Mostrar indicador de carga
            Swal.fire({
                title: 'Cargando...',
                text: 'Obteniendo información de la oferta',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Obtener datos de la oferta
            $.get('controllers/obtener_oferta.php', {
                id: id
            }, function(response) {
                // Cerrar el indicador de carga
                Swal.close();
                
                try {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var oferta = data.oferta;

                        // Llenar el formulario
                        $('#edit_id').val(oferta.id);
                        $('#edit_dia_semana').val(oferta.dia_semana);
                        $('#edit_titulo').val(oferta.titulo);
                        $('#edit_descripcion').val(oferta.descripcion);
                        $('#edit_visible').val(oferta.visible);

                        // Mostrar imagen actual
                        var imagenHtml = `
                            <img src="${oferta.imagen}" alt="${oferta.titulo}" 
                                 style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
                        `;
                        $('#imagen_actual').html(imagenHtml);

                        // Mostrar el modal
                        $('#editarOfertaModal').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'No se pudo obtener la información de la oferta.',
                            confirmButtonColor: '#104D43'
                        });
                    }
                } catch (e) {
                    console.error("Error parsing JSON:", response, e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la respuesta: ' + e.message,
                        confirmButtonColor: '#104D43'
                    });
                }
            });
        });

        // Manejar el envío del formulario de editar oferta
        $('#editarOfertaForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                text: 'Actualizando la oferta',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log("Respuesta recibida:", response);
                    try {
                        var result = JSON.parse(response);
                        if (result.success) {
                            // Cerrar el modal primero
                            $('#editarOfertaModal').modal('hide');
                            
                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Oferta actualizada con éxito!',
                                text: 'La oferta ha sido actualizada correctamente.',
                                showConfirmButton: true,
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#104D43'
                            }).then(() => {
                                // Recargar la página para mostrar los cambios
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Ocurrió un error al actualizar la oferta.',
                                confirmButtonColor: '#104D43'
                            });
                        }
                    } catch (e) {
                        console.error("Error parsing JSON:", response, e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la respuesta: ' + e.message,
                            confirmButtonColor: '#104D43'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la solicitud: ' + error,
                        confirmButtonColor: '#104D43'
                    });
                }
            });
        });
        
        // Manejar clic en botón eliminar
        $(document).on('click', '.eliminar-oferta', function() {
            var id = $(this).data('id');
            var titulo = $(this).data('titulo');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar la oferta "${titulo}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar indicador de carga
                    Swal.fire({
                        title: 'Procesando...',
                        text: 'Eliminando la oferta',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    $.post('controllers/eliminar_oferta.php', {
                        id: id
                    }, function(response) {
                        try {
                            var data = JSON.parse(response);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: 'La oferta ha sido eliminada correctamente.',
                                    showConfirmButton: true,
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: '#104D43'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'No se pudo eliminar la oferta.',
                                    confirmButtonColor: '#104D43'
                                });
                            }
                        } catch (e) {
                            console.error("Error parsing JSON:", response, e);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al procesar la respuesta: ' + e.message,
                                confirmButtonColor: '#104D43'
                            });
                        }
                    });
                }
            });
        });
    });
</script>

</body>

</html>