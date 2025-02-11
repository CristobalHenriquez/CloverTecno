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

    #productTable_wrapper {
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
                <h1 class="mb-4 text-center"><b>Panel de Administración</b></h1>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#agregarProductoModal">
                        <i class="bi bi-plus-circle"></i> Agregar Producto
                    </button>
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#categoriasModal">
                        <i class="bi bi-folder"></i> Categorías
                    </button>

                </div>

                <?php include_once 'includes/admin-table.php'; ?>

                <a href="logout.php" class="btn btn-danger mt-3">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</main>

<?php
include_once 'templates/agregar_producto_modal.php';
include_once 'templates/editar_producto_modal.php';
include_once 'templates/categorias_modal.php';
include_once 'includes/inc.footer.php';
?>

<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('#productTable').DataTable({
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
                    "targets": [0, 5]
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
                },
                {
                    "width": "100px",
                    "targets": 4
                },
                {
                    "width": "180px",
                    "targets": 5
                }
            ]
        });

        // Manejar clic en botón editar
        $('.editar-producto').on('click', function() {
            var id_producto = $(this).data('id');

            // Obtener datos del producto
            $.get('controllers/obtener_producto.php', {
                id: id_producto
            }, function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    var producto = data.producto;

                    // Llenar el formulario
                    $('#edit_id_producto').val(producto.id_producto);
                    $('#edit_nombre').val(producto.nombre_producto);
                    $('#edit_descripcion').val(producto.descripcion_producto);
                    $('#edit_categoria').val(producto.id_categoria);
                    $('#edit_precio').val(producto.valor_producto);

                    // Mostrar imágenes actuales
                    var imagenes = producto.imagenes ? producto.imagenes.split(',') : [];
                    var imagenesHtml = '';
                    imagenes.forEach(function(imagen, index) {
                        imagenesHtml += `
                        <div class="position-relative">
                            <img src="${imagen}" alt="Imagen ${index + 1}" style="width: 100px; height: 100px; object-fit: cover;">
                            <div class="form-check position-absolute top-0 start-0">
                                <input class="form-check-input" type="checkbox" name="eliminar_imagen[]" value="${imagen}" id="eliminar_${index}">
                                <label class="form-check-label" for="eliminar_${index}">
                                    Eliminar
                                </label>
                            </div>
                        </div>
                    `;
                    });
                    $('#imagenes_actuales').html(imagenesHtml);

                    // Mostrar el modal
                    $('#editarProductoModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            });
        });

        // Manejar el envío del formulario de editar producto
        $('#editarProductoForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: 'controllers/procesar_editar_producto.php',
                type: 'POST',
                data: formData,
                success: function(response) {
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
                    $('#editarProductoModal').modal('hide');
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
</script>

</body>

</html>