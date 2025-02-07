<?php
include_once 'includes/inc.head.php';
?>

<style>
    body {
        color: #333;
    }
    .main {
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-top: 20px;
    }
    h1 {
        color: #104D43;
    }
    .dataTables_wrapper {
        margin-top: 20px;
    }
    table.dataTable thead th {
        background-color: #104D43;
        color: white;
    }
    table.dataTable tbody tr:nth-of-type(odd) {
        background-color: #f1f3f5;
    }
    table.dataTable tbody tr:hover {
        background-color: #e9ecef;
    }
    .btn-primary, .btn-danger {
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
    #productTable {
        width: 100% !important;
    }
    #productTable_wrapper {
        overflow-x: auto;
    }
</style>

<main class="main">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-10">
                <h1 class="mb-4">Panel de Administración</h1>
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#agregarProductoModal">
                    <i class="bi bi-plus-circle"></i> Agregar Producto
                </button>
                
                <?php include_once 'includes/admin-table.php'; ?>
                
                <a href="logout.php" class="btn btn-danger mt-3">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</main>

<?php include_once 'templates/agregar_producto_modal.php'; ?>
<?php include_once 'includes/inc.footer.php'; ?>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#productTable').DataTable({
        "responsive": true,
        "scrollX": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json",
            "lengthMenu": "Mostrar _MENU_ productos por página",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ productos",
            "infoEmpty": "Mostrando 0 a 0 de 0 productos",
            "infoFiltered": "(filtrado de _MAX_ productos totales)",
            "search": "Buscar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": [0, 5] }
        ],
        "order": [[1, 'asc']]
    });

    // Manejar el envío del formulario de agregar producto
    $('#agregarProductoForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'controllers/procesar_agregar_producto.php',
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
                $('#agregarProductoModal').modal('hide');
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

