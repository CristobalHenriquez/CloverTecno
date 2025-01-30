<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db_connection.php';

// Verificar si se recibió un ID de categoría
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id_categoria = $_GET['id'];

// Obtener información de la categoría
$sql_categoria = "SELECT nombre_categoria FROM categorias WHERE id_categoria = ?";
$stmt = $db->prepare($sql_categoria);
$stmt->bind_param("i", $id_categoria);
$stmt->execute();
$categoria = $stmt->get_result()->fetch_assoc();

// Obtener productos de la categoría con sus imágenes
$sql_productos = "SELECT p.*, GROUP_CONCAT(ip.imagen_path) as imagenes 
                 FROM productos p 
                 LEFT JOIN imagenes_productos ip ON p.id_producto = ip.id_producto 
                 WHERE p.id_categoria = ? 
                 GROUP BY p.id_producto";
$stmt = $db->prepare($sql_productos);
$stmt->bind_param("i", $id_categoria);
$stmt->execute();
$productos = $stmt->get_result();

include_once 'includes/inc.head.php';
?>

<main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8 text-center" data-aos="fade-up" data-aos-delay="100">
                    <h2><span class="underlight"><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></span></h2>
                    <p>Explora nuestra selección de productos en esta categoría.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="products section">
        <div class="container" data-aos="fade-up">
            <div class="row gy-4">
                <?php while ($producto = $productos->fetch_assoc()): ?>
                    <?php $imagenes = explode(',', $producto['imagenes']); ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="product-item">
                            <div class="product-img">
                                <div class="swiper">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($imagenes as $imagen): ?>
                                            <div class="swiper-slide">
                                                <img src="<?php echo htmlspecialchars($imagen); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
                                <p><?php echo htmlspecialchars($producto['descripcion_producto']); ?></p>
                                <div class="price-contact">
                                    <div class="price"><b>$<?php echo number_format($producto['valor_producto'], 0, ',', '.'); ?></b></div>
                                    <a href="https://wa.me/5493416578661?text=<?php echo urlencode('Hola, estoy interesado en ' . $producto['nombre_producto']); ?>" class="btn-buy whatsapp-btn" target="_blank">
                                        <i class="bi bi-whatsapp"></i> Contactar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <?php include_once 'includes/inc.footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swipers = document.querySelectorAll('.swiper');
            swipers.forEach(swiperElement => {
                new Swiper(swiperElement, {
                    loop: true,
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    }
                });
            });
        });
    </script>
</main>