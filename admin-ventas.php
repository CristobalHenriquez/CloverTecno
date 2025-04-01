<?php
include_once 'includes/auth.php';
requireAdmin();
include_once 'includes/inc.head.admin.php';
include_once 'includes/db_connection.php';
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
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap 5 Theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

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

    /* Mejoras para DataTables */
    .dataTables_length {
        margin-bottom: 1rem;
    }
    
    .dataTables_length select {
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    
    .dataTables_filter {
        margin-bottom: 1rem;
    }
    
    .dataTables_filter input {
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .dataTables_info {
        padding-top: 0.85em;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .dataTables_paginate {
        padding-top: 0.5em;
    }
    
    .dataTables_paginate .paginate_button {
        margin-left: 3px;
        border-radius: 0.25rem;
        color: #104D43 !important;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }
    
    .dataTables_paginate .paginate_button.current {
        color: #fff !important;
        background-color: #104D43 !important;
        border-color: #104D43 !important;
    }
    
    .dataTables_paginate .paginate_button:hover {
        color: #fff !important;
        background-color: #156658 !important;
        border-color: #156658 !important;
    }

    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }

    .cart-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .cart-item-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 10px;
    }

    .cart-item-details {
        flex-grow: 1;
    }

    .cart-item-actions {
        display: flex;
        align-items: center;
    }

    .cart-item-quantity {
        width: 60px;
        text-align: center;
        margin: 0 10px;
    }

    .cart-summary {
        margin-top: 20px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
    }

    .estado-completada {
        background-color: #d4edda;
        color: #155724;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: 500;
    }

    .estado-pendiente {
        background-color: #fff3cd;
        color: #856404;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: 500;
    }

    .estado-cancelada {
        background-color: #f8d7da;
        color: #721c24;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: 500;
    }

    /* Estilos para el select2 personalizado */
    .select2-container--bootstrap-5 .select2-selection--single {
        height: auto;
        padding: 0.375rem 0.75rem;
    }

    .product-option {
        display: flex;
        align-items: center;
        padding: 8px;
    }

    .product-option-image {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 10px;
    }

    .product-option-details {
        flex-grow: 1;
    }

    .product-option-name {
        font-weight: bold;
    }

    .product-option-price {
        color: #104D43;
    }

    .product-option-stock {
        font-size: 0.85em;
        color: #6c757d;
    }

    .product-option-stock.low {
        color: #dc3545;
    }

    .product-option-stock.medium {
        color: #fd7e14;
    }

    .product-option-stock.high {
        color: #198754;
    }

    /* Estilos para los pasos de venta */
    .steps-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        padding: 0 10px;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .step.active .step-number {
        background-color: #104D43;
        color: white;
    }

    .step.completed .step-number {
        background-color: #28a745;
        color: white;
    }

    .step-title {
        font-size: 0.85rem;
        color: #6c757d;
        text-align: center;
    }

    .step.active .step-title {
        color: #104D43;
        font-weight: bold;
    }

    .step.completed .step-title {
        color: #28a745;
    }

    .steps-line {
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e9ecef;
        z-index: 0;
    }

    .steps-progress {
        position: absolute;
        top: 15px;
        left: 0;
        height: 2px;
        background-color: #28a745;
        z-index: 0;
        transition: width 0.3s ease;
    }

    /* Estilos para el resumen de venta */
    .resumen-venta {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .resumen-venta h5 {
        color: #104D43;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }

    .resumen-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .resumen-total {
        font-weight: bold;
        font-size: 1.1rem;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #dee2e6;
    }

    /* Estilos para el selector de estado */
    .estado-actual {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: 500;
    }

    /* Estilos para los diferentes estados */
    .estado-pendiente {
        background-color: #fff3cd;
        color: #856404;
    }

    .estado-en-proceso {
        background-color: #cce5ff;
        color: #004085;
    }

    .estado-enviado {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .estado-entregado {
        background-color: #d4edda;
        color: #155724;
    }

    .estado-cancelado {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Estilos para las opciones de estado en el modal */
    .estado-option {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        text-align: center;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .estado-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .estado-option.selected {
        border: 2px solid #104D43;
    }
</style>

<main class="main">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-10">
                <h1 class="mb-4 text-center"><b>Gestión de Ventas</b></h1>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="Administrador" class="btn btn-outline-secondary mb-3">
                        <i class="bi bi-box-arrow-left"></i> Volver a Productos
                    </a>
                    <button type="button" class="btn btn-success mb-3" id="iniciarNuevaVenta">
                        <i class="bi bi-plus-circle"></i> Nueva Venta
                    </button>
                </div>

                <?php include_once 'includes/admin-table-ventas.php'; ?>

                <a href="logout.php" class="btn btn-danger mt-3">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Incluir los modales de los pasos -->
<?php include_once 'templates/modal-paso1.php'; ?>
<?php include_once 'templates/modal-paso2.php'; ?>
<?php include_once 'templates/modal-paso3.php'; ?>

<!-- Modal para Ver Detalles de Venta -->
<div class="modal fade" id="detalleVentaModal" tabindex="-1" aria-labelledby="detalleVentaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleVentaModalLabel" style="color: #000;">Detalle de Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleVentaBody">
                <!-- El contenido se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <!-- <button type="button" class="btn btn-success" id="imprimirDetalleBtn">
                    <i class="bi bi-printer"></i> Imprimir
                </button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Cambiar Estado -->
<div class="modal fade" id="cambiarEstadoModal" tabindex="-1" aria-labelledby="cambiarEstadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cambiarEstadoModalLabel">Cambiar Estado de Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Selecciona el nuevo estado para la venta <strong id="ventaIdText"></strong>:</p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="estado-option estado-pendiente" data-estado="Pendiente">
                            <i class="bi bi-hourglass-split me-2"></i> Pendiente
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="estado-option estado-en-proceso" data-estado="En Proceso">
                            <i class="bi bi-gear-fill me-2"></i> En Proceso
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="estado-option estado-enviado" data-estado="Enviado">
                            <i class="bi bi-truck me-2"></i> Enviado
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="estado-option estado-entregado" data-estado="Entregado">
                            <i class="bi bi-check-circle-fill me-2"></i> Entregado
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="estado-option estado-cancelado" data-estado="Cancelado">
                            <i class="bi bi-x-circle-fill me-2"></i> Cancelado
                        </div>
                    </div>
                </div>

                <input type="hidden" id="ventaId" value="">
                <input type="hidden" id="estadoSeleccionado" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarEstadoBtn" disabled>Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'includes/inc.footer.php';
?>

<!-- Script personalizado para ventas (al final para asegurar que todos los elementos estén cargados) -->
<script src="js/venta-main.js"></script>

<script>
    $(document).ready(function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Inicializar DataTable con configuración mejorada
        if ($('#tabla-ventas').length > 0) {
            $('#tabla-ventas').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    zeroRecords: "No se encontraron resultados",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando 0 a 0 de 0 registros",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    search: "Buscar:",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                responsive: true,
                pageLength: 10, // Mostrar 10 registros por página por defecto
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]], // Opciones de registros por página
                order: [[0, 'desc']], // Ordenar por ID (columna 0) de forma descendente
                columnDefs: [
                    { orderable: false, targets: [7] } // Desactivar ordenamiento en la columna de acciones
                ],
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip' // Personalizar la disposición de los elementos
            });
        }

        // Modal de cambio de estado
        var cambiarEstadoModal = new bootstrap.Modal(document.getElementById('cambiarEstadoModal'));

        // Mostrar modal al hacer clic en el estado
        $(document).on('click', '.estado-actual', function() {
            var id_venta = $(this).data('id');
            var estado_actual = $(this).data('estado');

            // Actualizar el modal con la información de la venta
            $('#ventaIdText').text('#' + id_venta);
            $('#ventaId').val(id_venta);

            // Quitar la selección de todas las opciones
            $('.estado-option').removeClass('selected');

            // Seleccionar la opción correspondiente al estado actual
            $('.estado-option[data-estado="' + estado_actual + '"]').addClass('selected');

            // Actualizar el estado seleccionado
            $('#estadoSeleccionado').val(estado_actual);

            // Deshabilitar el botón de guardar (ya que no ha habido cambios)
            $('#guardarEstadoBtn').prop('disabled', true);

            // Mostrar el modal
            cambiarEstadoModal.show();
        });

        // Seleccionar un estado en el modal
        $(document).on('click', '.estado-option', function() {
            var estado = $(this).data('estado');

            // Quitar la selección de todas las opciones
            $('.estado-option').removeClass('selected');

            // Seleccionar esta opción
            $(this).addClass('selected');

            // Actualizar el estado seleccionado
            $('#estadoSeleccionado').val(estado);

            // Habilitar el botón de guardar si el estado ha cambiado
            var estado_actual = $('.estado-actual[data-id="' + $('#ventaId').val() + '"]').data('estado');
            $('#guardarEstadoBtn').prop('disabled', estado === estado_actual);
        });

        // Guardar cambios de estado
        $('#guardarEstadoBtn').on('click', function() {
            var id_venta = $('#ventaId').val();
            var nuevo_estado = $('#estadoSeleccionado').val();

            // Mostrar indicador de carga
            Swal.fire({
                title: 'Actualizando estado...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Enviar solicitud AJAX para cambiar el estado
            $.ajax({
                url: 'controllers/cambiar_estado.php',
                type: 'POST',
                data: {
                    id_venta: id_venta,
                    nuevo_estado: nuevo_estado
                },
                success: function(response) {
                    try {
                        // Intentar parsear la respuesta como JSON
                        var data = JSON.parse(response);

                        if (data.success) {
                            // Actualizar la interfaz
                            var estadoActual = $('.estado-actual[data-id="' + id_venta + '"]');
                            estadoActual.text(nuevo_estado);
                            estadoActual.data('estado', nuevo_estado);

                            // Actualizar la clase del estado
                            estadoActual.removeClass('estado-pendiente estado-en-proceso estado-enviado estado-entregado estado-cancelado');

                            // Asignar la clase correcta según el nuevo estado
                            switch (nuevo_estado) {
                                case 'Pendiente':
                                    estadoActual.addClass('estado-pendiente');
                                    break;
                                case 'En Proceso':
                                    estadoActual.addClass('estado-en-proceso');
                                    break;
                                case 'Enviado':
                                    estadoActual.addClass('estado-enviado');
                                    break;
                                case 'Entregado':
                                    estadoActual.addClass('estado-entregado');
                                    break;
                                case 'Cancelado':
                                    estadoActual.addClass('estado-cancelado');
                                    break;
                            }

                            // Cerrar el modal
                            cambiarEstadoModal.hide();

                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Estado actualizado!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            // Mostrar mensaje de error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Ocurrió un error al actualizar el estado'
                            });
                        }
                    } catch (e) {
                        console.error("Error al parsear la respuesta:", e, "Respuesta:", response);
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la respuesta del servidor'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error, "Respuesta:", xhr.responseText);
                    // Mostrar mensaje de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la solicitud: ' + error
                    });
                }
            });
        });
    });
</script>

</body>
</html>