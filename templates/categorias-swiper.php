<?php
// Obtener las categorías
$sql_categorias = "SELECT id_categoria, nombre_categoria, descripcion_categoria, imagen_categoria FROM categorias";
$result_categorias = $db->query($sql_categorias);

$categorias = [];
if ($result_categorias->num_rows > 0) {
    while ($row = $result_categorias->fetch_assoc()) {
        $categorias[] = $row;
    }
}

// Función para obtener una imagen aleatoria de un producto por categoría (se usará como respaldo)
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

<!-- Sección de Tarjetas de Categorías -->
<section id="category-cards" class="category-cards section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="section-title">
            <h2>Productos</h2>
            <p>Ordenados por categorías</p>
        </div>

        <div class="category-cards-slider swiper init-swiper">
            <script type="application/json" class="swiper-config">
                {
                    "loop": true,
                    "speed": 800,
                    "autoplay": {
                        "delay": 5000
                    },
                    "slidesPerView": 2,
                    "spaceBetween": 10,
                    "breakpoints": {
                        "320": {
                            "slidesPerView": 2,
                            "spaceBetween": 10
                        },
                        "576": {
                            "slidesPerView": 2,
                            "spaceBetween": 15
                        },
                        "768": {
                            "slidesPerView": 2,
                            "spaceBetween": 20
                        },
                        "992": {
                            "slidesPerView": 3,
                            "spaceBetween": 20
                        }
                    }
                }
            </script>
            <div class="swiper-wrapper">
                <?php foreach ($categorias as $index => $categoria): ?>
                    <?php 
                        // Si imagen_categoria está vacía, obtener una imagen aleatoria como respaldo
                        $imagen = !empty($categoria['imagen_categoria']) ? $categoria['imagen_categoria'] : obtenerImagenAleatoria($db, $categoria['id_categoria']);
                        $delay = $index * 100;
                        $categoria_url = "Categorias_" . urlencode(str_replace(' ', '_', $categoria['nombre_categoria']));
                        
                        // Limitar la descripción a 100 caracteres y agregar puntos suspensivos
                        $descripcion = !empty($categoria['descripcion_categoria']) ? $categoria['descripcion_categoria'] : 'Sin descripción disponible';
                        if (strlen($descripcion) > 100) {
                            $descripcion = substr($descripcion, 0, 97) . '...';
                        }
                    ?>
                    <div class="swiper-slide">
                        <div class="category-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                            <div class="category-content">
                                <h3 class="category-title">
                                    <a href="<?php echo $categoria_url; ?>"><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></a>
                                </h3>
                                
                                <div class="category-description">
                                    <p><?php echo htmlspecialchars($descripcion); ?></p>
                                </div>
                                
                                <a href="<?php echo $categoria_url; ?>" class="view-all">
                                    Ver todo <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                            <div class="category-image">
                                <img src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>" class="img-fluid" loading="lazy">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Agregar controles de navegación -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

