<?php
include_once 'includes/inc.head.php';
?>

<main class="main">
    <div class="container">
        <h1 class="mt-5">Panel de Administración</h1>
        <!-- Botón para agregar producto -->
        <a href="agregar_producto.php" class="btn btn-primary mb-3">Agregar Producto</a>
        
        <?php include_once 'includes/admin-table.php'; ?>
        
        <a href="logout.php" class="btn btn-danger mt-3">Cerrar Sesión</a>
    </div>
    <?php include_once 'includes/inc.footer.php'; ?>
</main>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#productTable').DataTable({
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
        },
        "columnDefs": [
            { "orderable": false, "targets": 0 },
            { "orderable": false, "targets": -1 }
        ],
        "order": [[1, 'asc']]
    });
});
</script>

</body>

