<?php
// Consultar productos destacados - limitado a 2
$query = "SELECT * FROM productos_destacados ORDER BY id_destacado LIMIT 2";
$result = $db->query($query);

// Verificar si hay productos destacados
$productos_destacados = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos_destacados[] = $row;
    }
}

// Asegurarse de que haya exactamente 2 productos destacados
$total_productos = count($productos_destacados);
if ($total_productos < 2) {
    // Productos por defecto en caso de que no haya suficientes en la base de datos
    $productos_default = [
        [
            'nombre_destacado' => 'AirPods Pro',
            'precio_destacado' => 89990,
            'imagen_destacado' => 'assets/img/airpods.png'
        ],
        [
            'nombre_destacado' => 'Funda MagSafe',
            'precio_destacado' => 59990,
            'imagen_destacado' => 'assets/img/product/product-3.webp'
        ]
    ];
    
    // Agregar productos por defecto si es necesario
    for ($i = $total_productos; $i < 2; $i++) {
        $productos_destacados[] = $productos_default[$i - $total_productos];
    }
}

// Limitar a exactamente 2 productos
if (count($productos_destacados) > 2) {
    $productos_destacados = array_slice($productos_destacados, 0, 2);
}

// Formatear precios con separador de miles
function formatear_precio($precio) {
    return '$' . number_format($precio, 0, ',', '.');
}
?>

<!-- Hero Section -->
<section class="ecommerce-hero-1 hero section" id="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 content-col" data-aos="fade-right" data-aos-delay="100">
                <div class="content">
                    <span class="promo-badge">Clover Tecno</span>
                    <h1>Accesorios de <span>Tecnología</span> Para Tu Dispositivo</h1>
                    <p>
                        En Clover Tecno, nos apasiona la tecnología y nos dedicamos a ofrecerte los mejores accesorios para tu dispositivo móvil. Desde fundas protectoras hasta cargadores de alta velocidad, contamos con una amplia gama de productos diseñados para mejorar tu experiencia digital.
                    </p>
                    <div class="hero-cta">
                        <a href="Productos" class="btn btn-shop">Ver Productos<i class="bi bi-arrow-right"></i></a>
                        <!-- <a href="categorias.php" class="btn btn-collection"></a> -->
                    </div>
                    <div class="hero-features">
                        <div class="feature-item">
                            <i class="bi bi-truck"></i>
                            <span>Envío a todo el pais</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-shield-check"></i>
                            <span>Pago Seguro</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Devoluciones Fáciles</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 image-col" data-aos="fade-left" data-aos-delay="200">
                <div class="hero-image">
                    <!-- Imagen principal (dispositivo) -->
                    <img src="assets/img/iphone.png" alt="iPhone" class="main-product" loading="lazy">
                    
                    <?php 
                    // Posiciones para los productos destacados
                    $positions = [
                        ['class' => 'product-1', 'delay' => 300],
                        ['class' => 'product-2', 'delay' => 400]
                    ];
                    
                    // Mostrar exactamente 2 productos destacados
                    for ($i = 0; $i < 2; $i++): 
                        $producto = $productos_destacados[$i];
                        $position = $positions[$i];
                    ?>
                    <div class="floating-product <?php echo $position['class']; ?>" data-aos="fade-up" data-aos-delay="<?php echo $position['delay']; ?>">
                        <img src="<?php echo htmlspecialchars($producto['imagen_destacado']); ?>" alt="<?php echo htmlspecialchars($producto['nombre_destacado']); ?>">
                        <div class="product-info">
                            <h4><?php echo htmlspecialchars($producto['nombre_destacado']); ?></h4>
                            <span class="price"><?php echo formatear_precio($producto['precio_destacado']); ?></span>
                        </div>
                    </div>
                    <?php endfor; ?>
                    
                    <!-- <div class="discount-badge" data-aos="zoom-in" data-aos-delay="500">
                        <span class="percent">30%</span>
                        <span class="text">OFF</span>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Hero Section -->