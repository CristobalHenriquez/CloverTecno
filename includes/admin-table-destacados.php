<?php
require_once 'db_connection.php';

$sql = "SELECT id_destacado, nombre_destacado, precio_destacado, imagen_destacado 
        FROM productos_destacados 
        ORDER BY id_destacado";

$result = $db->query($sql);

if (!$result) {
    die("Error en la consulta: " . $db->error);
}

// Función para formatear precio
function formatear_precio($precio) {
    return '$' . number_format($precio, 0, ',', '.');
}
?>

<div class="">
    <table id="destacadosTable" class="table table-striped table-hover table-bordered table-responsive">
        <thead>
            <tr>
                <th>Nombre del Producto</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre_destacado']); ?></td>
                        <td class="price-display"><?php echo formatear_precio($row['precio_destacado']); ?></td>
                        <td>
                            <img src="<?php echo htmlspecialchars($row['imagen_destacado']); ?>"
                                alt="<?php echo htmlspecialchars($row['nombre_destacado']); ?>"
                                style="width: 80px; height: 80px; object-fit: contain; border-radius: 4px;">
                        </td>
                        <td>
                            <button type="button"
                                class="btn btn-sm btn-outline-warning editar-destacado"
                                data-id="<?php echo $row['id_destacado']; ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button"
                                class="btn btn-sm btn-outline-danger eliminar-destacado"
                                data-id="<?php echo $row['id_destacado']; ?>"
                                data-nombre="<?php echo htmlspecialchars($row['nombre_destacado']); ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                endwhile;
            else:
                ?>
                <tr>
                    <td colspan="4" class="text-center">No se encontraron productos destacados.</td>
                </tr>
            <?php
            endif;
            ?>
        </tbody>
    </table>
</div>