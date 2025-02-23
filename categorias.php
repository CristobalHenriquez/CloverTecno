<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db_connection.php';

// Verificar si se recibió un nombre de categoría
if (!isset($_GET['nombre'])) {
    header('Location: Inicio');
    exit;
}

$nombre_categoria = str_replace('_', ' ', $_GET['nombre']);

// Obtener información de la categoría
$sql_categoria = "SELECT id_categoria, nombre_categoria FROM categorias WHERE nombre_categoria = ?";
$stmt = $db->prepare($sql_categoria);
$stmt->bind_param("s", $nombre_categoria);
$stmt->execute();
$categoria = $stmt->get_result()->fetch_assoc();

if (!$categoria) {
    // Manejar el caso cuando la categoría no se encuentra
    header("HTTP/1.0 404 Not Found");
    echo "Categoría no encontrada";
    exit;
}

$id_categoria = $categoria['id_categoria'];

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
    <section id="hero" class="hero section pb-1">
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
                        <div class="card">
                            <div class="card-overlay"></div>
                            <div class="card-inner">
                                <div class="product-content">
                                    <div class="product-image">
                                        <div class="swiper">
                                            <div class="swiper-wrapper">
                                                <?php foreach ($imagenes as $imagen): ?>
                                                    <div class="swiper-slide">
                                                        <img src="<?php echo htmlspecialchars($imagen); ?>"
                                                            alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="swiper-pagination"></div>
                                        </div>
                                    </div>
                                    <div class="product-details">
                                        <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
                                        <p><?php echo htmlspecialchars($producto['descripcion_producto']); ?></p>
                                        <div class="price-action">
                                            <span class="price">$<?php echo number_format($producto['valor_producto'], 0, ',', '.'); ?></span>
                                            <a href="https://wa.me/5493416578661?text=<?php echo urlencode('Hola, estoy interesado en ' . $producto['nombre_producto']); ?>"
                                                class="whatsapp-link"
                                                target="_blank">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <?php include_once 'includes/inc.footer.php'; ?>

    <style>
        .card {
            --bg: #e8e8e8;
            --contrast: #e2e0e0;
            --grey: #93a1a1;
            position: relative;
            padding: 9px;
            background-color: var(--bg);
            border-radius: 35px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px,
                rgba(0, 0, 0, 0.3) 0px 30px 60px -30px,
                rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
            margin-bottom: 30px;
        }

        .card-overlay {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: repeating-conic-gradient(var(--bg) 0.0000001%, var(--grey) 0.000104%) 60% 60%/600% 600%;
            filter: opacity(10%) contrast(105%);
            border-radius: 35px;
        }

        .card-inner {
            background-color: var(--contrast);
            border-radius: 30px;
            overflow: hidden;
            height: auto;
            /* Cambiado de 100% a auto */
            display: flex;
            flex-direction: column;
        }

        .product-content {
            height: auto;
            /* Cambiado de 100% a auto */
            display: flex;
            flex-direction: column;
        }

        .product-image {
            height: 300px;
            min-height: 300px;
            /* Añadido para mantener la altura mínima */
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-details {
            padding: 2rem;
            /* Aumentado de 1.5rem */
            background: var(--contrast);
        }

        .product-details h3 {
            font-size: 1.4rem;
            /* Aumentado de 1.2rem */
            margin-bottom: 1rem;
            /* Aumentado de 0.5rem */
            color: #333;
        }

        .product-details p {
            font-size: 1rem;
            /* Aumentado de 0.9rem */
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .price-action {
            padding-top: 0.5rem;
            /* Añadido espacio superior */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            font-size: 1.5rem;
            /* Aumentado de 1.25rem */
            font-weight: bold;
            color: #333;
        }

        .whatsapp-link {
            background: #25D366;
            color: white;
            padding: 10px 14px;
            /* Aumentado de 8px 12px */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        .whatsapp-link:hover {
            transform: scale(1.1);
            color: white;
        }

        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @media (max-width: 768px) {

            .product-image {
        height: 250px;
        min-height: 250px;
    }

            .card {
                margin-bottom: 20px;
            }

            .product-details {
                padding: 1.5rem;
            }
        }
    </style>

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