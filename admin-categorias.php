<?php
include_once 'includes/auth.php';
requireAuth();
include_once 'includes/inc.head.php';
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

    #categoriasTable_wrapper {
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
                <h1 class="mb-4 text-center"><b>Administración de Categorías</b></h1>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="Administrador" class="btn btn-outline-secondary mb-3">
                        <i class="bi bi-box-arrow-left"></i> Volver a Productos
                    </a>
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#agregarCategoriaModal">
                        <i class="bi bi-plus-circle"></i> Agregar Categoría
                    </button>
                </div>

                <?php include_once 'includes/admin-table-categorias.php'; ?>

                <a href="logout.php" class="btn btn-danger mt-3">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Modal para Agregar Categoría -->
<div class="modal fade" id="agregarCategoriaModal" tabindex="-1" aria-labelledby="agregarCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarCategoriaModalLabel">Agregar Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregarCategoriaForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nombre_categoria" class="form-label">Nombre de la Categoría</label>
                        <input type="text" class="form-control" id="nombre_categoria" name="nombre_categoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_categoria" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion_categoria" name="descripcion_categoria" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagen_categoria" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="imagen_categoria" name="imagen_categoria" accept="image/*" required>
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

<!-- Modal para Editar Categoría -->
<div class="modal fade" id="editarCategoriaModal" tabindex="-1" aria-labelledby="editarCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarCategoriaModalLabel">Editar Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarCategoriaForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id_categoria" name="id_categoria">
                    <div class="mb-3">
                        <label for="edit_nombre_categoria" class="form-label">Nombre de la Categoría</label>
                        <input type="text" class="form-control" id="edit_nombre_categoria" name="nombre_categoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion_categoria" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion_categoria" name="descripcion_categoria" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imagen Actual</label>
                        <div id="imagen_actual" class="mb-2"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_imagen_categoria" class="form-label">Nueva Imagen (opcional)</label>
                        <input type="file" class="form-control" id="edit_imagen_categoria" name="imagen_categoria" accept="image/*">
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
        $('#categoriasTable').DataTable({
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
                    "targets": [0, 3]
                },
                {
                    "width": "80px",
                    "targets": 0
                },
                {
                    "width": "200px",
                    "targets": 1
                },
                {
                    "width": "300px",
                    "targets": 2
                },
                {
                    "width": "150px",
                    "targets": 3
                }
            ]
        });

        // Manejar envío del formulario para agregar categoría
        $('#agregarCategoriaForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: 'controllers/procesar_agregar_categoria.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    try {
                        var result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: result.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la respuesta'
                        });
                    }
                    $('#agregarCategoriaModal').modal('hide');
                },
                cache: false,
                contentType: false,
                processData: false,
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la solicitud'
                    });
                }
            });
        });

        // Manejar clic en botón editar
        $(document).on('click', '.editar-categoria', function() {
            var id_categoria = $(this).data('id');

            // Obtener datos de la categoría
            $.get('controllers/obtener_categoria.php', {
                id: id_categoria
            }, function(response) {
                try {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var categoria = data.categoria;

                        // Llenar el formulario
                        $('#edit_id_categoria').val(categoria.id_categoria);
                        $('#edit_nombre_categoria').val(categoria.nombre_categoria);
                        $('#edit_descripcion_categoria').val(categoria.descripcion_categoria);

                        // Mostrar imagen actual
                        var imagenHtml = `
                            <img src="${categoria.imagen_categoria}" alt="${categoria.nombre_categoria}" 
                                 style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
                        `;
                        $('#imagen_actual').html(imagenHtml);

                        // Mostrar el modal
                        $('#editarCategoriaModal').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la respuesta'
                    });
                }
            });
        });

        // Manejar el envío del formulario de editar categoría
        $('#editarCategoriaForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: 'controllers/procesar_editar_categoria.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    try {
                        var result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: result.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la respuesta'
                        });
                    }
                    $('#editarCategoriaModal').modal('hide');
                },
                cache: false,
                contentType: false,
                processData: false,
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la solicitud'
                    });
                }
            });
        });
    });
</script>

</body>

</html>