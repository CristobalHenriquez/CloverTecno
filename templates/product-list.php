<?php
// Configuración de paginación
$items_per_page = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Obtener categorías para los filtros
$sql_categorias = "SELECT id_categoria, nombre_categoria FROM categorias";
$result_categorias = $db->query($sql_categorias);

$categorias = [];
if ($result_categorias->num_rows > 0) {
    while ($row = $result_categorias->fetch_assoc()) {
        $categorias[] = $row;
    }
}

// Filtro de categoría
$categoria_filter = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
$where_clause = $categoria_filter > 0 ? "WHERE p.id_categoria = $categoria_filter" : "";

// Obtener total de productos para la paginación
$sql_total = "SELECT COUNT(*) as total FROM productos p $where_clause";
$result_total = $db->query($sql_total);
$total_products = $result_total->fetch_assoc()['total'];
$total_pages = ceil($total_products / $items_per_page);

// Obtener productos con sus imágenes (máximo 2 por producto)
$sql_productos = "
    SELECT 
        p.*, 
        c.nombre_categoria,
        (
            SELECT GROUP_CONCAT(imagen_path ORDER BY id_imagen LIMIT 2)
            FROM imagenes_productos 
            WHERE id_producto = p.id_producto
        ) as imagenes
    FROM productos p
    LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
    $where_clause
    ORDER BY p.id_producto DESC
    LIMIT $offset, $items_per_page
";

$result_productos = $db->query($sql_productos);
?>

<!-- Product List Section -->
<section id="product-list" class="product-list section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>NUESTROS PRODUCTOS</h2>
        <p>Encuentra lo que buscas en nuestro catálogo</p>
    </div>

    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <!-- Filtros de categoría -->
        <div class="row">
            <div class="col-12">
                <div class="product-filters mb-5 d-flex justify-content-center" data-aos="fade-up">
                    <ul class="d-flex flex-wrap gap-2 list-unstyled" id="category-filters">
                        <li class="<?php echo $categoria_filter == 0 ? 'filter-active' : ''; ?>">
                            <a href="?categoria=0&no_preload=1" class="filter-link">Todos</a>
                        </li>
                        <?php foreach ($categorias as $categoria): ?>
                            <li class="<?php echo $categoria_filter == $categoria['id_categoria'] ? 'filter-active' : ''; ?>">
                                <a href="?categoria=<?php echo $categoria['id_categoria']; ?>&no_preload=1" class="filter-link">
                                    <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Lista de productos -->
        <div class="row product-container" data-aos="fade-up" data-aos-delay="200">
            <?php if ($result_productos->num_rows > 0): ?>
                <?php while ($producto = $result_productos->fetch_assoc()):
                    // Procesar imágenes
                    $imagenes = explode(',', $producto['imagenes']);
                    $imagen_principal = $imagenes[0] ?? 'assets/img/placeholder.jpg';
                    $imagen_hover = $imagenes[1] ?? $imagen_principal; // Usar la misma imagen si no hay segunda
                ?>
                    <div class="col-md-6 col-lg-3 product-item">
                        <div class="product-card">
                            <div class="product-image">
                                <?php if ($producto['valor_producto'] > 0): ?>
                                    <span class="badge">$<?php echo number_format($producto['valor_producto'], 0, ',', '.'); ?></span>
                                <?php endif; ?>

                                <img src="<?php echo htmlspecialchars($imagen_principal); ?>"
                                    alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                    class="img-fluid main-img">

                                <img src="<?php echo htmlspecialchars($imagen_hover); ?>"
                                    alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                    class="img-fluid hover-img">

                                <div class="product-overlay">
                                    <a href="detalle-producto.php?id=<?php echo $producto['id_producto']; ?>" class="btn-cart">
                                        <i class="bi bi-eye"></i> Ver Detalles
                                    </a>
                                </div>
                            </div>
                            <div class="product-info">
                                <h5 class="product-title">
                                    <a href="detalle-producto.php?id=<?php echo $producto['id_producto']; ?>">
                                        <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                                    </a>
                                </h5>
                                <div class="product-category">
                                    <span><?php echo htmlspecialchars($producto['nombre_categoria']); ?></span>
                                </div>
                                <div class="product-description">
                                    <?php
                                    $descripcion = $producto['descripcion_producto'];
                                    if (strlen($descripcion) > 100) {
                                        $descripcion = substr($descripcion, 0, 97) . '...';
                                    }
                                    echo htmlspecialchars($descripcion);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No se encontraron productos.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Paginación -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination-container mt-5 d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo ($page - 1); ?>&categoria=<?php echo $categoria_filter; ?>&no_preload=1">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&categoria=<?php echo $categoria_filter; ?>&no_preload=1">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo ($page + 1); ?>&categoria=<?php echo $categoria_filter; ?>&no_preload=1">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Script para resaltar la categoría activa -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener la categoría actual de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const currentCategoria = parseInt(urlParams.get('categoria') || 0);

        // Resaltar la categoría activa
        const categoryLinks = document.querySelectorAll('#category-filters li');
        categoryLinks.forEach(li => {
            const link = li.querySelector('a');
            const linkCategoria = parseInt(new URLSearchParams(link.href.split('?')[1]).get('categoria'));

            if (linkCategoria === currentCategoria) {
                li.classList.add('filter-active');
            } else {
                li.classList.remove('filter-active');
            }
        });
    });
</script>