<?php
// Obtener las 8 categorías
$sql_categorias = "SELECT id_categoria, nombre_categoria FROM categorias LIMIT 8";
$result_categorias = $db->query($sql_categorias);

$categorias = [];
if ($result_categorias->num_rows > 0) {
    while ($row = $result_categorias->fetch_assoc()) {
        $categorias[] = $row;
    }
}

// Función para obtener una imagen aleatoria de un producto por categoría
function obtenerImagenAleatoria($db, $id_categoria)
{
    $sql = "SELECT ip.imagen_path 
            FROM imagenes_productos ip
            JOIN productos p ON ip.id_producto = p.id_producto
            WHERE p.id_categoria = ?
            ORDER BY RAND()
            LIMIT 1";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['imagen_path'];
    }

    return "assets/img/placeholder.jpg"; // Imagen por defecto si no se encuentra ninguna
}
?>

<main class="main">
    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
        <!-- ... (mantener el contenido existente) ... -->
    </div>

    <!-- Gallery Section -->
    <section id="gallery" class="gallery section">
        <div class="container-fluid col-10" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4 justify-content-center">
                <?php foreach ($categorias as $categoria): ?>
                    <?php $imagen = obtenerImagenAleatoria($db, $categoria['id_categoria']); ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="gallery-item h-100">
                            <a href="Categorias_<?php echo urlencode(str_replace(' ', '_', $categoria['nombre_categoria'])); ?>" class="details-link">
                                <img src="<?php echo htmlspecialchars($imagen); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>">
                                <div class="gallery-links d-flex align-items-center justify-content-center">
                                </div>
                                <div class="category-name">
                                    <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<style>
    .gallery-item {
        position: relative;
        overflow: hidden;
    }

    .category-name {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        text-align: center;
        padding: 10px;
        transition: opacity 0.3s ease;
    }

    .gallery-item:hover .category-name {
        opacity: 0;
    }
</style>