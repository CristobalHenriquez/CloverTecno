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
                        <a href="editar_producto.php?id=<?php echo $row['id_producto']; ?>" 
                           class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="eliminar_producto.php?id=<?php echo $row['id_producto']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('¿Está seguro de que desea eliminar este producto?');">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
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

