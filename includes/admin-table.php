<?php
require_once 'db_connection.php';

$sql = "SELECT p.id_producto, p.nombre_producto, p.descripcion_producto, p.valor_producto, 
               c.nombre_categoria, MIN(ip.imagen_path) as imagen_path
        FROM productos p
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
        LEFT JOIN imagenes_productos ip ON p.id_producto = ip.id_producto
        GROUP BY p.id_producto";

$result = $db->query($sql);

if (!$result) {
    die("Error en la consulta: " . $db->error);
}
?>

<div class="table-responsive">
    <table id="productTable" class="table table-striped table-hover">
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
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                    </td>
                    <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                    <td><?php echo htmlspecialchars(substr($row['descripcion_producto'], 0, 100)) . '...'; ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_categoria']); ?></td>
                    <td>$<?php echo number_format($row['valor_producto'], 2); ?></td>
                    <td>
                        <button type="button" 
                                class="btn btn-sm btn-primary editar-producto" 
                                data-id="<?php echo $row['id_producto']; ?>">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                        <button type="button" 
                                class="btn btn-sm btn-danger eliminar-producto" 
                                data-id="<?php echo $row['id_producto']; ?>"
                                data-nombre="<?php echo htmlspecialchars($row['nombre_producto']); ?>">
                            <i class="bi bi-trash"></i> Eliminar
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
$(document).ready(function() {
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
                        // Proceder con la eliminación
                        $.ajax({
                            url: 'controllers/eliminar_producto.php',
                            type: 'POST',
                            data: { id: id },
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
});
</script>

