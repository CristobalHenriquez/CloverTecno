<?php
include_once 'includes/auth.php';
requireAuth();
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

    #destacadosTable_wrapper {
        margin: 0 auto;
    }

    .dataTables_filter {
        margin-bottom: 1rem;
    }

    .dataTables_length {
        margin-bottom: 1rem;
    }
    
    .price-display {
        font-weight: bold;
        color: #104D43;
    }
    
    .alert-info {
        background-color: #e8f4f8;
        border-color: #b8e2ef;
        color: #0c5460;
        margin-bottom: 20px;
    }
</style>

<main class="main">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-10">
                <h1 class="mb-4 text-center"><b>Administración de Productos Destacados</b></h1>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Nota:</strong> Los productos destacados aparecen en la sección principal (Hero) de la página de inicio. Se mostrarán un máximo de 2 productos.
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <a href="Administrador" class="btn btn-outline-secondary mb-3">
                        <i class="bi bi-box-arrow-left"></i> Volver a Productos
                    </a>
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#agregarDestacadoModal">
                        <i class="bi bi-plus-circle"></i> Agregar Producto Destacado
                    </button>
                </div>

                <?php include_once 'includes/admin-table-destacados.php'; ?>

                <a href="logout.php" class="btn btn-danger mt-3">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Modal para Agregar Producto Destacado -->
<div class="modal fade" id="agregarDestacadoModal" tabindex="-1" aria-labelledby="agregarDestacadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarDestacadoModalLabel" style="color: #000;">Agregar Nuevo Producto Destacado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregarDestacadoForm" action="controllers/procesar_agregar_destacado.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nombre_destacado" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre_destacado" name="nombre_destacado" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio_destacado" class="form-label">Precio</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="precio_destacado" name="precio_destacado" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="imagen_destacado" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="imagen_destacado" name="imagen_destacado" accept="image/*" required>
                        <small class="form-text text-muted">Recomendado: Imagen con fondo transparente (PNG) para mejor visualización.</small>
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

<!-- Modal para Editar Producto Destacado -->
<div class="modal fade" id="editarDestacadoModal" tabindex="-1" aria-labelledby="editarDestacadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarDestacadoModalLabel" style="color: #000;">Editar Producto Destacado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarDestacadoForm" action="controllers/procesar_editar_destacado.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id_destacado" name="id_destacado">
                    <div class="mb-3">
                        <label for="edit_nombre_destacado" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="edit_nombre_destacado" name="nombre_destacado" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_precio_destacado" class="form-label">Precio</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="edit_precio_destacado" name="precio_destacado" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imagen Actual</label>
                        <div id="imagen_actual" class="mb-2"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_imagen_destacado" class="form-label">Nueva Imagen (opcional)</label>
                        <input type="file" class="form-control" id="edit_imagen_destacado" name="imagen_destacado" accept="image/*">
                        <small class="form-text text-muted">Recomendado: Imagen con fondo transparente (PNG) para mejor visualización.</small>
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
        $('#destacadosTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "js/dataTables.es-ES.json"
            },
            "order": [
                [0, 'asc']
            ],
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            "columnDefs": [{
                    "orderable": false,
                    "targets": [2, 3]
                },
                {
                    "width": "150px",
                    "targets": 0
                },
                {
                    "width": "100px",
                    "targets": 1
                },
                {
                    "width": "150px",
                    "targets": 2
                },
                {
                    "width": "100px",
                    "targets": 3
                }
            ]
        });

        // Formatear precio
        function formatearPrecio(precio) {
            return '$' + new Intl.NumberFormat('es-CL').format(precio);
        }

        // Manejar envío del formulario para agregar producto destacado
        $('#agregarDestacadoForm').on('submit', function(e) {
            e.preventDefault(); // Prevenir el envío normal del formulario
            
            var formData = new FormData(this);
            
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                text: 'Guardando el nuevo producto destacado',
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
                            $('#agregarDestacadoModal').modal('hide');
                            
                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Producto destacado agregado con éxito!',
                                text: 'El producto ha sido agregado correctamente a la sección destacada.',
                                showConfirmButton: true,
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#104D43'
                            }).then(() => {
                                // Recargar la página para mostrar el nuevo producto destacado
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Ocurrió un error al agregar el producto destacado.',
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
        $(document).on('click', '.editar-destacado', function() {
            var id = $(this).data('id');

            // Mostrar indicador de carga
            Swal.fire({
                title: 'Cargando...',
                text: 'Obteniendo información del producto destacado',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Obtener datos del producto destacado
            $.get('controllers/obtener_destacado.php', {
                id: id
            }, function(response) {
                // Cerrar el indicador de carga
                Swal.close();
                
                try {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var destacado = data.destacado;

                        // Llenar el formulario
                        $('#edit_id_destacado').val(destacado.id_destacado);
                        $('#edit_nombre_destacado').val(destacado.nombre_destacado);
                        $('#edit_precio_destacado').val(destacado.precio_destacado);

                        // Mostrar imagen actual
                        var imagenHtml = `
                            <img src="${destacado.imagen_destacado}" alt="${destacado.nombre_destacado}" 
                                 style="width: 100px; height: 100px; object-fit: contain; border-radius: 4px;">
                        `;
                        $('#imagen_actual').html(imagenHtml);

                        // Mostrar el modal
                        $('#editarDestacadoModal').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'No se pudo obtener la información del producto destacado.',
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

        // Manejar el envío del formulario de editar producto destacado
        $('#editarDestacadoForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                text: 'Actualizando el producto destacado',
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
                            $('#editarDestacadoModal').modal('hide');
                            
                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Producto destacado actualizado con éxito!',
                                text: 'El producto destacado ha sido actualizado correctamente.',
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
                                text: result.message || 'Ocurrió un error al actualizar el producto destacado.',
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
        $(document).on('click', '.eliminar-destacado', function() {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar el producto destacado "${nombre}"?`,
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
                        text: 'Eliminando el producto destacado',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    $.post('controllers/eliminar_destacado.php', {
                        id: id
                    }, function(response) {
                        try {
                            var data = JSON.parse(response);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: 'El producto destacado ha sido eliminado correctamente.',
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
                                    text: data.message || 'No se pudo eliminar el producto destacado.',
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