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

    .label-filtro{
        color: #fff;
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

    #stockTable_wrapper {
        margin: 0 auto;
    }

    .dataTables_filter {
        margin-bottom: 1rem;
    }

    .dataTables_length {
        margin-bottom: 1rem;
    }
    
    .stock-badge {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
    }
    
    .stock-high {
        background-color: #d4edda;
        color: #155724;
    }
    
    .stock-medium {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .stock-low {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .stock-none {
        background-color: #e2e3e5;
        color: #383d41;
    }
    
    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }
    
    .btn-stock {
        width: 40px;
        height: 40px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 3px;
    }
</style>

<main class="main">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-10">
                <h1 class="mb-4 text-center"><b>Administración de Stock</b></h1>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="Administrador" class="btn btn-outline-secondary mb-3">
                        <i class="bi bi-box-arrow-left"></i> Volver a Productos
                    </a>
                </div>

                <?php include_once 'includes/admin-table-stock.php'; ?>

                <a href="logout.php" class="btn btn-danger mt-3">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Modal para Agregar Stock -->
<div class="modal fade" id="agregarStockModal" tabindex="-1" aria-labelledby="agregarStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarStockModalLabel" style="color: #000;">Agregar Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregarStockForm" action="controllers/procesar_agregar_stock.php" method="POST">
                    <input type="hidden" id="agregar_id_producto" name="id_producto">
                    <div class="mb-3">
                        <label for="nombre_producto_agregar" class="form-label">Producto</label>
                        <input type="text" class="form-control" id="nombre_producto_agregar" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="stock_actual_agregar" class="form-label">Stock Actual</label>
                        <input type="text" class="form-control" id="stock_actual_agregar" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad_agregar" class="form-label">Cantidad a Agregar</label>
                        <input type="number" class="form-control" id="cantidad_agregar" name="cantidad" min="1" value="1" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Agregar Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Quitar Stock -->
<div class="modal fade" id="quitarStockModal" tabindex="-1" aria-labelledby="quitarStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quitarStockModalLabel" style="color: #000;">Quitar Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="quitarStockForm" action="controllers/procesar_quitar_stock.php" method="POST">
                    <input type="hidden" id="quitar_id_producto" name="id_producto">
                    <div class="mb-3">
                        <label for="nombre_producto_quitar" class="form-label">Producto</label>
                        <input type="text" class="form-control" id="nombre_producto_quitar" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="stock_actual_quitar" class="form-label">Stock Actual</label>
                        <input type="text" class="form-control" id="stock_actual_quitar" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad_quitar" class="form-label">Cantidad a Quitar</label>
                        <input type="number" class="form-control" id="cantidad_quitar" name="cantidad" min="1" value="1" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Quitar Stock</button>
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
        var stockTable = $('#stockTable').DataTable({
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
                    "width": "300px",
                    "targets": 1
                },
                {
                    "width": "200px",
                    "targets": 2
                },
                {
                    "width": "100px",
                    "targets": 3
                },
                {
                    "width": "150px",
                    "targets": 4
                }
            ]
        });
        
        // Variables para almacenar los filtros actuales
        var categoriaSeleccionada = '';
        var nivelStockSeleccionado = '';
        
        // Función para aplicar filtros
        function aplicarFiltros() {
            categoriaSeleccionada = $('#filtroCategoria').val();
            nivelStockSeleccionado = $('#filtroStock').val();
            
            // Limpiar filtros previos
            stockTable.search('').columns().search('').draw();
            $.fn.dataTable.ext.search.pop();
            
            // Crear función de filtrado personalizada
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var row = stockTable.row(dataIndex).node();
                    var rowCategoriaId = $(row).data('categoria');
                    var rowStockLevel = $(row).data('stock-level');
                    
                    // Verificar filtro de categoría
                    var pasaFiltroCategoria = categoriaSeleccionada === '' || rowCategoriaId == categoriaSeleccionada;
                    
                    // Verificar filtro de nivel de stock
                    var pasaFiltroStock = nivelStockSeleccionado === '' || rowStockLevel === nivelStockSeleccionado;
                    
                    // El producto debe pasar ambos filtros
                    return pasaFiltroCategoria && pasaFiltroStock;
                }
            );
            
            // Aplicar filtros
            stockTable.draw();
            
            // Eliminar el filtro personalizado después de aplicarlo
            $.fn.dataTable.ext.search.pop();
        }
        
        // Manejar el filtro por categoría
        $('#filtroCategoria').on('change', function() {
            aplicarFiltros();
        });
        
        // Manejar el filtro por nivel de stock
        $('#filtroStock').on('change', function() {
            aplicarFiltros();
        });
        
        // Manejar el botón de exportar a Excel
        $('#exportarExcel').on('click', function() {
            // Construir la URL con los filtros actuales
            var url = 'controllers/exportar_stock_excel.php?';
            
            // Agregar filtro de categoría si está seleccionado
            if (categoriaSeleccionada !== '') {
                url += 'categoria=' + categoriaSeleccionada + '&';
            }
            
            // Agregar filtro de nivel de stock si está seleccionado
            if (nivelStockSeleccionado !== '') {
                url += 'nivel_stock=' + nivelStockSeleccionado;
            }
            
            // Redirigir a la URL para descargar el archivo
            window.location.href = url;
        });

        // Manejar clic en botón agregar stock
        $(document).on('click', '.agregar-stock', function() {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');
            var stock = $(this).data('stock');
            
            // Llenar el formulario
            $('#agregar_id_producto').val(id);
            $('#nombre_producto_agregar').val(nombre);
            $('#stock_actual_agregar').val(stock !== null ? stock : 'No disponible');
            
            // Mostrar el modal
            $('#agregarStockModal').modal('show');
        });
        
        // Manejar clic en botón quitar stock
        $(document).on('click', '.quitar-stock', function() {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');
            var stock = $(this).data('stock');
            
            // Llenar el formulario
            $('#quitar_id_producto').val(id);
            $('#nombre_producto_quitar').val(nombre);
            $('#stock_actual_quitar').val(stock !== null ? stock : 'No disponible');
            
            // Establecer el máximo que se puede quitar
            $('#cantidad_quitar').attr('max', stock);
            
            // Mostrar el modal
            $('#quitarStockModal').modal('show');
        });

        // Manejar envío del formulario para agregar stock
        $('#agregarStockForm').on('submit', function(e) {
            e.preventDefault(); // Prevenir el envío normal del formulario
            
            var formData = new FormData(this);
            
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                text: 'Actualizando el stock',
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
                            $('#agregarStockModal').modal('hide');
                            
                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Stock actualizado con éxito!',
                                text: result.message,
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
                                text: result.message || 'Ocurrió un error al actualizar el stock.',
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
        
        // Manejar envío del formulario para quitar stock
        $('#quitarStockForm').on('submit', function(e) {
            e.preventDefault(); // Prevenir el envío normal del formulario
            
            var formData = new FormData(this);
            var stockActual = parseInt($('#stock_actual_quitar').val()) || 0;
            var cantidadQuitar = parseInt($('#cantidad_quitar').val()) || 0;
            
            // Validar que no se quite más stock del disponible
            if (cantidadQuitar > stockActual) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se puede quitar más stock del disponible.',
                    confirmButtonColor: '#104D43'
                });
                return;
            }
            
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                text: 'Actualizando el stock',
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
                            $('#quitarStockModal').modal('hide');
                            
                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Stock actualizado con éxito!',
                                text: result.message,
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
                                text: result.message || 'Ocurrió un error al actualizar el stock.',
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
        
        // Validar cantidad a quitar
        $('#cantidad_quitar').on('change', function() {
            var stockActual = parseInt($('#stock_actual_quitar').val()) || 0;
            var cantidadQuitar = parseInt($(this).val()) || 0;
            
            if (cantidadQuitar > stockActual) {
                $(this).val(stockActual);
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'No se puede quitar más stock del disponible.',
                    confirmButtonColor: '#104D43'
                });
            }
        });
    });
</script>

</body>

</html>