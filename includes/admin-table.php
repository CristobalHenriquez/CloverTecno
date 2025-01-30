<?php
require_once 'db_connection.php';

// Código de depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar la conexión a la base de datos
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Imprimir la consulta SQL para verificar
$sql = "SELECT p.id_producto, p.nombre_producto, p.descripcion_producto, p.valor_producto, 
               c.nombre_categoria, MIN(ip.imagen_path) as imagen_path
        FROM productos p
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
        LEFT JOIN imagenes_productos ip ON p.id_producto = ip.id_producto
        GROUP BY p.id_producto";

$result = $db->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Query failed: " . $db->error);
}

// Imprimir el número de filas obtenidas
echo "<p>Número de filas: " . $result->num_rows . "</p>";
?>

<table id="productTable" class="table table-striped table-bordered">
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
                         style="width: 50px; height: 50px; object-fit: cover;">
                </td>
                <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                <td><?php echo htmlspecialchars($row['descripcion_producto']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre_categoria']); ?></td>
                <td>$<?php echo number_format($row['valor_producto'], 2); ?></td>
                <td>
                    <a href="editar_producto.php?id=<?php echo $row['id_producto']; ?>" 
                       class="btn btn-sm btn-primary">Editar</a>
                    <a href="eliminar_producto.php?id=<?php echo $row['id_producto']; ?>" 
                       class="btn btn-sm btn-danger" 
                       onclick="return confirm('¿Está seguro de que desea eliminar este producto?');">
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php 
            endwhile; 
        else:
        ?>
            <tr>
                <td colspan="6">No se encontraron productos.</td>
            </tr>
        <?php
        endif;
        ?>
    </tbody>
</table>