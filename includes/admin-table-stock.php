<?php
require_once 'db_connection.php';

// Consulta para obtener todas las categorías para el filtro
$sqlCategorias = "SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria";
$resultCategorias = $db->query($sqlCategorias);

if (!$resultCategorias) {
    die("Error en la consulta de categorías: " . $db->error);
}

// Consulta para obtener productos con su stock y categoría
$sql = "SELECT p.id_producto, p.nombre_producto, p.stock, c.nombre_categoria, c.id_categoria,
        (SELECT imagen_path FROM imagenes_productos WHERE id_producto = p.id_producto LIMIT 1) AS imagen
        FROM productos p
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
        ORDER BY p.nombre_producto";

$result = $db->query($sql);

if (!$result) {
    die("Error en la consulta: " . $db->error);
}
?>

<div class="mb-4">
    <div class="row align-items-center">
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="filtroCategoria" class="form-label">Filtrar por categoría:</label>
                <select id="filtroCategoria" class="form-select">
                    <option value="">Todas las categorías</option>
                    <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                        <option value="<?php echo $categoria['id_categoria']; ?>">
                            <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="filtroStock" class="form-label">Filtrar por nivel de stock:</label>
                <select id="filtroStock" class="form-select">
                    <option value="">Todos los niveles</option>
                    <option value="alto">Alto (más de 10 unidades)</option>
                    <option value="medio">Medio (6-10 unidades)</option>
                    <option value="bajo">Bajo (1-5 unidades)</option>
                    <option value="sin-stock">Sin stock</option>
                </select>
            </div>
        </div>
        <div class="col-md-4 mb-3 d-flex align-items-end justify-content-end">
            <button id="exportarExcel" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
            </button>
        </div>
    </div>
</div>

<div class="">
    <table id="stockTable" class="table table-striped table-hover table-bordered table-responsive">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    // Determinar la clase de stock
                    $stockClass = 'stock-none';
                    $stockText = 'No disponible';
                    $stockLevel = 'sin-stock';
                    
                    if ($row['stock'] !== null) {
                        $stockText = $row['stock'];
                        if ($row['stock'] > 10) {
                            $stockClass = 'stock-high';
                            $stockLevel = 'alto';
                        } elseif ($row['stock'] > 5) {
                            $stockClass = 'stock-medium';
                            $stockLevel = 'medio';
                        } else {
                            $stockClass = 'stock-low';
                            $stockLevel = 'bajo';
                        }
                    }
            ?>
                    <tr data-categoria="<?php echo $row['id_categoria'] ?? ''; ?>" data-stock-level="<?php echo $stockLevel; ?>">
                        <td>
                            <?php if ($row['imagen']): ?>
                                <img src="<?php echo htmlspecialchars($row['imagen']); ?>"
                                    alt="<?php echo htmlspecialchars($row['nombre_producto']); ?>"
                                    class="product-image">
                            <?php else: ?>
                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_categoria'] ?? 'Sin categoría'); ?></td>
                        <td>
                            <span class="stock-badge <?php echo $stockClass; ?>">
                                <?php echo $stockText; ?>
                            </span>
                        </td>
                        <td>
                            <button type="button"
                                class="btn btn-sm btn-success btn-stock agregar-stock"
                                data-id="<?php echo $row['id_producto']; ?>"
                                data-nombre="<?php echo htmlspecialchars($row['nombre_producto']); ?>"
                                data-stock="<?php echo $row['stock']; ?>">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                            <button type="button"
                                class="btn btn-sm btn-danger btn-stock quitar-stock"
                                data-id="<?php echo $row['id_producto']; ?>"
                                data-nombre="<?php echo htmlspecialchars($row['nombre_producto']); ?>"
                                data-stock="<?php echo $row['stock']; ?>"
                                <?php echo ($row['stock'] === null || $row['stock'] <= 0) ? 'disabled' : ''; ?>>
                                <i class="bi bi-dash-lg"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                endwhile;
            else:
                ?>
                <tr>
                    <td colspan="5" class="text-center">No se encontraron productos.</td>
                </tr>
            <?php
            endif;
            ?>
        </tbody>
    </table>
</div>