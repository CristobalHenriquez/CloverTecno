<?php
require_once 'db_connection.php';

$sql = "SELECT id, dia_semana, titulo, descripcion, imagen, visible, orden 
        FROM ofertas 
        ORDER BY dia_semana, orden";

$result = $db->query($sql);

if (!$result) {
    die("Error en la consulta: " . $db->error);
}
?>

<div class="">
    <table id="ofertasTable" class="table table-striped table-hover table-bordered table-responsive">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Día</th>
                <th>Título</th>
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
                            <img src="<?php echo htmlspecialchars($row['imagen']); ?>"
                                alt="<?php echo htmlspecialchars($row['titulo']); ?>"
                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td><?php echo htmlspecialchars($row['dia_semana']); ?></td>
                        <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                        <td><?php echo htmlspecialchars(substr($row['descripcion'], 0, 100)) . (strlen($row['descripcion']) > 100 ? '...' : ''); ?></td>
                        <td>
                            <button type="button"
                                class="btn btn-sm btn-outline-warning editar-oferta"
                                data-id="<?php echo $row['id']; ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button"
                                class="btn btn-sm btn-outline-danger eliminar-oferta"
                                data-id="<?php echo $row['id']; ?>"
                                data-titulo="<?php echo htmlspecialchars($row['titulo']); ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                endwhile;
            else:
                ?>
                <tr>
                    <td colspan="5" class="text-center">No se encontraron ofertas.</td>
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
        $(document).on('click', '.eliminar-oferta', function() {
            const id = $(this).data('id');
            const titulo = $(this).data('titulo');

            Swal.fire({
                title: '¿Está seguro?',
                text: `¿Desea eliminar la oferta "${titulo}"?`,
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
                        text: `Esta acción no se puede deshacer. ¿Realmente desea eliminar la oferta "${titulo}"?`,
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
                                url: 'controllers/eliminar_oferta.php',
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