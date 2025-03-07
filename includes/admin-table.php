<?php
require_once 'db_connection.php';

// Consulta optimizada para obtener solo la primera imagen de cada producto
$sql = "SELECT p.id_producto, p.nombre_producto, p.descripcion_producto, p.valor_producto, 
               c.nombre_categoria, 
               (SELECT imagen_path FROM imagenes_productos WHERE id_producto = p.id_producto LIMIT 1) as imagen_path
        FROM productos p
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
        ORDER BY p.nombre_producto";

// Crear índices si no existen para mejorar el rendimiento
$db->query("CREATE INDEX IF NOT EXISTS idx_productos_categoria ON productos(id_categoria)");
$db->query("CREATE INDEX IF NOT EXISTS idx_imagenes_producto ON imagenes_productos(id_producto)");

$result = $db->query($sql);

if (!$result) {
    die("Error en la consulta: " . $db->error);
}
?>

<div class="">
    <table id="productTable" class="table table-striped table-hover table-bordered table-responsive">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($row['imagen_path']); ?>"
                                alt="<?php echo htmlspecialchars($row['nombre_producto']); ?>"
                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"
                                loading="lazy"> <!-- Añadido lazy loading para mejorar rendimiento -->
                        </td>
                        <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                        <td><?php echo htmlspecialchars(substr($row['descripcion_producto'], 0, 100)) . (strlen($row['descripcion_producto']) > 100 ? '...' : ''); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_categoria']); ?></td>
                        <td>$<?php echo number_format($row['valor_producto'], 0, ',', '.'); ?></td>
                        <td>
                            <button type="button"
                                class="btn btn-sm btn-outline-warning editar-producto"
                                data-id="<?php echo $row['id_producto']; ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button"
                                class="btn btn-sm btn-outline-danger eliminar-producto"
                                data-id="<?php echo $row['id_producto']; ?>"
                                data-nombre="<?php echo htmlspecialchars($row['nombre_producto']); ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                endwhile;
            else:
                ?>
                <tr>
                    <td colspan="6" class="text-center">No se encontraron productos.</td>
                </tr>
            <?php
            endif;
            ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Destruir la instancia existente si existe
        if ($.fn.dataTable.isDataTable('#productTable')) {
            $('#productTable').DataTable().destroy();
        }

        // Inicializar DataTable con opciones optimizadas
        if ($.fn.dataTable) {
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
                        "width": "120px",
                        "targets": 5
                    }
                ],
                // Optimizaciones de rendimiento
                "deferRender": true,
                "processing": true,
                "stateSave": true
            });
        }

        // Manejador para el botón de eliminar
        $(document).on('click', '.eliminar-producto', function() {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');

            Swal.fire({
                title: '¿Está seguro?',
                text: `¿Desea eliminar el producto "${nombre}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Segunda confirmación
                    Swal.fire({
                        title: 'Confirmación adicional',
                        text: `Esta acción no se puede deshacer. ¿Realmente desea eliminar el producto "${nombre}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar definitivamente',
                        cancelButtonText: 'Cancelar'
                    }).then((secondResult) => {
                        if (secondResult.isConfirmed) {
                            // Mostrar indicador de carga
                            Swal.fire({
                                title: 'Procesando...',
                                text: 'Eliminando producto',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Proceder con la eliminación
                            $.ajax({
                                url: 'controllers/eliminar_producto.php',
                                type: 'POST',
                                data: {
                                    id: id
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: '¡Éxito!',
                                            text: response.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: response.message
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Ocurrió un error al procesar la solicitud'
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });

        // Manejador para el botón de editar
        $(document).on('click', '.editar-producto', function() {
            const id = $(this).data('id');

            // Mostrar indicador de carga
            Swal.fire({
                title: 'Cargando...',
                text: 'Obteniendo información del producto',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Obtener datos del producto
            $.ajax({
                url: 'controllers/obtener_producto.php',
                type: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        // Aquí se maneja la apertura del modal de edición
                        // Este código depende de cómo esté implementado el modal en tu aplicación
                        if (typeof mostrarModalEdicion === 'function') {
                            mostrarModalEdicion(response.producto);
                        } else if ($('#editarProductoModal').length) {
                            // Llenar el formulario del modal
                            $('#edit_id_producto').val(response.producto.id_producto);
                            $('#edit_nombre').val(response.producto.nombre_producto);
                            $('#edit_descripcion').val(response.producto.descripcion_producto);
                            $('#edit_categoria').val(response.producto.id_categoria);
                            $('#edit_precio').val(response.producto.valor_producto);

                            // Mostrar imágenes actuales si existe el contenedor
                            if ($('#imagenes_actuales').length && response.producto.imagenes) {
                                var imagenes = response.producto.imagenes.split(',');
                                var imagenesHtml = '';
                                imagenes.forEach(function(imagen, index) {
                                    imagenesHtml += `
                                <div class="position-relative mb-2">
                                    <img src="${imagen}" alt="Imagen ${index + 1}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    <div class="form-check mt-1">
                                        <input class="form-check-input eliminar-imagen-check" type="checkbox" name="eliminar_imagen[]" value="${imagen}" id="eliminar_${index}">
                                        <label class="form-check-label" for="eliminar_${index}">
                                            Eliminar
                                        </label>
                                    </div>
                                </div>
                                `;
                                });
                                $('#imagenes_actuales').html(imagenesHtml);
                            }

                            // Mostrar el modal
                            $('#editarProductoModal').modal('show');
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al obtener la información del producto'
                    });
                }
            });
        });
    });
</script>