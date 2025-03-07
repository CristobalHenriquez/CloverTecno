<?php
require_once 'db_connection.php';

$sql = "SELECT id_categoria, nombre_categoria, descripcion_categoria, imagen_categoria 
        FROM categorias 
        ORDER BY nombre_categoria";

$result = $db->query($sql);

if (!$result) {
    die("Error en la consulta: " . $db->error);
}
?>

<div class="">
    <table id="categoriasTable" class="table table-striped table-hover table-bordered table-responsive">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Descripción</th>
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
                            <img src="<?php echo htmlspecialchars($row['imagen_categoria']); ?>"
                                alt="<?php echo htmlspecialchars($row['nombre_categoria']); ?>"
                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td><?php echo htmlspecialchars($row['nombre_categoria']); ?></td>
                        <td><?php echo htmlspecialchars(substr($row['descripcion_categoria'], 0, 100)) . (strlen($row['descripcion_categoria']) > 100 ? '...' : ''); ?></td>
                        <td>
                            <button type="button"
                                class="btn btn-sm btn-outline-warning editar-categoria"
                                data-id="<?php echo $row['id_categoria']; ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button"
                                class="btn btn-sm btn-outline-danger eliminar-categoria"
                                data-id="<?php echo $row['id_categoria']; ?>"
                                data-nombre="<?php echo htmlspecialchars($row['nombre_categoria']); ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                endwhile;
            else:
                ?>
                <tr>
                    <td colspan="4" class="text-center">No se encontraron categorías.</td>
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
        $(document).on('click', '.eliminar-categoria', function() {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');

            Swal.fire({
                title: '¿Está seguro?',
                text: `¿Desea eliminar la categoría "${nombre}"?`,
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
                        text: `Esta acción no se puede deshacer. ¿Realmente desea eliminar la categoría "${nombre}"?`,
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
                                url: 'controllers/eliminar_categoria.php',
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
    });
</script>