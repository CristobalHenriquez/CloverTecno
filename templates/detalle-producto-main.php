<?php
// Verificar si se recibió el ID del producto
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id_producto = $_GET['id'];

// Consultar el producto y su categoría
$query = "SELECT p.*, c.nombre_categoria, c.id_categoria 
          FROM productos p 
          LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
          WHERE p.id_producto = ?";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit();
}

$producto = $result->fetch_assoc();

// Obtener las imágenes del producto
$query_imagenes = "SELECT imagen_path FROM imagenes_productos WHERE id_producto = ? ORDER BY id_imagen";
$stmt_imagenes = $db->prepare($query_imagenes);
$stmt_imagenes->bind_param("i", $id_producto);
$stmt_imagenes->execute();
$result_imagenes = $stmt_imagenes->get_result();

$imagenes = [];
while ($row = $result_imagenes->fetch_assoc()) {
    $imagenes[] = $row['imagen_path'];
}

// Si no hay imágenes, usar una imagen por defecto
if (empty($imagenes)) {
    $imagenes[] = 'assets/img/no-image.jpg';
}

// Formatear precio
function formatear_precio($precio)
{
    return '$' . number_format($precio, 0, ',', '.');
}

// Verificar si hay stock disponible
$tiene_stock = isset($producto['stock']) && $producto['stock'] > 0;
?>

<!-- Ecommerce Product Details Section -->
<section id="ecommerce-product-details" class="ecommerce-product-details section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-5">
            <!-- Product Images Column -->
            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right" data-aos-delay="200">
                <div class="product-gallery">
                    <!-- Vertical Thumbnails -->
                    <div class="thumbnails-vertical">
                        <div class="thumbnails-container">
                            <?php foreach ($imagenes as $index => $imagen): ?>
                                <div class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>" data-image="<?php echo htmlspecialchars($imagen); ?>">
                                    <img src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" class="img-fluid">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Main Image -->
                    <div class="main-image-wrapper">
                        <div class="image-zoom-container">
                            <img src="<?php echo htmlspecialchars($imagenes[0]); ?>"
                                alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                class="img-fluid main-image zoomable-image"
                                id="main-product-image"
                                data-zoomable>
                            <div class="zoom-overlay">
                                <i class="bi bi-zoom-in"></i>
                            </div>
                        </div>
                        <div class="image-nav">
                            <button class="image-nav-btn prev-image">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="image-nav-btn next-image">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Info Column -->
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="product-info-wrapper" id="product-info-sticky">
                    <!-- Product Meta -->
                    <div class="product-meta">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <!-- Enlace a la categoría con URL amigable -->
                            <a href="Productos?categoria=<?php echo $producto['id_categoria']; ?>" class="product-category">
                                <?php echo htmlspecialchars($producto['nombre_categoria']); ?>
                            </a>
                            <div class="product-share">
                                <button class="share-btn" aria-label="Compartir producto">
                                    <i class="bi bi-share"></i>
                                </button>
                                <div class="share-dropdown">
                                    <button class="share-whatsapp" aria-label="Compartir por WhatsApp">
                                        <i class="bi bi-whatsapp"></i>
                                    </button>
                                    <button class="share-copy" aria-label="Copiar enlace">
                                        <i class="bi bi-link-45deg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <h1 class="product-title"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>
                    </div>

                    <!-- Product Price -->
                    <div class="product-price-container">
                        <div class="price-wrapper">
                            <span class="current-price"><?php echo formatear_precio($producto['valor_producto']); ?></span>
                        </div>
                        <?php if ($tiene_stock): ?>
                            <div class="stock-info stock-available">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>En Stock</span>
                                <span class="stock-count">(<?php echo $producto['stock']; ?> unidades disponibles)</span>
                            </div>
                        <?php else: ?>
                            <div class="stock-info stock-unavailable">
                                <i class="bi bi-x-circle-fill"></i>
                                <span>Sin Stock Disponible</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Product Description -->
                    <div class="product-short-description">
                        <p><?php echo nl2br(htmlspecialchars($producto['descripcion_producto'])); ?></p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="product-actions">
                        <?php if ($tiene_stock): ?>
                            <div class="quantity-selector">
                                <button class="quantity-btn decrease-quantity" type="button">-</button>
                                <input type="number" id="product-quantity" class="quantity-input" value="1" min="1" max="<?php echo $producto['stock']; ?>" readonly>
                                <button class="quantity-btn increase-quantity" type="button">+</button>
                            </div>
                            <button id="add-to-cart-btn" class="btn btn-primary add-to-cart-btn">
                                <i class="bi bi-cart-plus"></i> Agregar al Carrito
                            </button>
                        <?php else: ?>
                            <a href="https://wa.me/+5493416578661?text=Hola, me interesa el producto: <?php echo urlencode($producto['nombre_producto']); ?> (<?php echo formatear_precio($producto['valor_producto']); ?>). ¿Cuándo tendrán stock disponible?"
                                class="btn btn-success consultar-btn w-100"
                                target="_blank">
                                <i class="bi bi-whatsapp"></i> Consultar Disponibilidad
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Delivery Options -->
                    <div class="delivery-options">
                        <div class="delivery-option">
                            <i class="bi bi-truck"></i>
                            <div>
                                <h6>Envío Gratis</h6>
                                <p>En compras mayores a $50.000</p>
                            </div>
                        </div>
                        <div class="delivery-option">
                            <i class="bi bi-arrow-repeat"></i>
                            <div>
                                <h6>Devoluciones Fáciles</h6>
                                <p>30 días de garantía</p>
                            </div>
                        </div>
                        <div class="delivery-option">
                            <i class="bi bi-shield-check"></i>
                            <div>
                                <h6>Compra Segura</h6>
                                <p>100% garantizado</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Zoom Modal -->
<div id="zoom-modal" class="zoom-modal" style="display: none;">
    <div class="zoom-modal-content">
        <button class="zoom-close">&times;</button>
        <img id="zoom-image" src="/placeholder.svg" alt="">
        <!-- Botones de navegación en el modal de zoom -->
        <button class="zoom-nav-btn zoom-prev">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button class="zoom-nav-btn zoom-next">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>

<style>
    /* Estilos para el zoom modal */
    .zoom-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .zoom-modal-content {
        position: relative;
        max-width: 90%;
        max-height: 90vh;
    }

    .zoom-modal-content img {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
    }

    .zoom-close {
        position: absolute;
        top: -40px;
        right: 0;
        background: none;
        border: none;
        color: white;
        font-size: 30px;
        cursor: pointer;
    }

    /* Botones de navegación en el zoom */
    .zoom-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background-color: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .zoom-nav-btn:hover {
        background-color: rgba(255, 255, 255, 0.4);
    }

    .zoom-nav-btn i {
        color: white;
        font-size: 24px;
    }

    .zoom-prev {
        left: 20px;
    }

    .zoom-next {
        right: 20px;
    }

    /* Estilos para el tooltip de copiado */
    .copy-tooltip {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        margin-bottom: 5px;
    }

    .copy-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-width: 5px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }

    /* Ajustes para los botones de compartir */
    .share-dropdown button {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #f3f4f6;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .share-dropdown button:hover {
        background-color: var(--agua-color);
    }

    .share-dropdown button:hover i {
        color: #fff;
    }

    .share-dropdown button.share-whatsapp:hover {
        background-color: var(--whatsapp-color);
    }

    .share-dropdown button.share-copy:hover {
        background-color: #6366f1;
    }

    .share-dropdown button i {
        color: #4b5563;
        font-size: 0.875rem;
    }

    /* Estilo para el enlace de categoría */
    a.product-category {
        font-size: 0.875rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    a.product-category:hover {
        color: var(--agua-color);
        text-decoration: underline;
    }

    /* Cursor de zoom para la imagen principal */
    .zoomable-image {
        cursor: zoom-in;
    }

    /* Estilos para el selector de cantidad */
    .product-actions {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        height: 45px;
    }

    .quantity-btn {
        width: 40px;
        height: 45px;
        background-color: #f9fafb;
        border: none;
        font-size: 1.2rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-btn:hover {
        background-color: #f3f4f6;
    }

    .quantity-btn:disabled {
        color: #d1d5db;
        cursor: not-allowed;
    }

    .quantity-input {
        width: 50px;
        height: 45px;
        border: none;
        text-align: center;
        font-size: 1rem;
        font-weight: 500;
    }

    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .add-to-cart-btn {
        flex: 1;
        height: 45px;
        background: linear-gradient(135deg, #104D43, #187766);
        border: none;
        transition: all 0.3s ease;
    }

    .add-to-cart-btn:hover {
        background: linear-gradient(135deg, #0E443B, #156658);
        transform: translateY(-2px);
    }

    .add-to-cart-btn:active {
        transform: translateY(0);
    }

    .add-to-cart-btn i {
        margin-right: 8px;
    }

    /* Estilos para el indicador de stock */
    .stock-info {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 8px;
        font-size: 0.9rem;
    }

    .stock-available {
        color: #10b981;
    }

    .stock-unavailable {
        color: #ef4444;
        font-weight: 500;
    }

    .stock-count {
        color: #6b7280;
        font-size: 0.85rem;
    }

    /* Estilos para el botón de consultar disponibilidad */
    .consultar-btn {
        height: 45px;
        background-color: #25D366 !important;
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .consultar-btn:hover {
        background-color: #128C7E !important;
        transform: translateY(-2px);
    }

    .consultar-btn:active {
        transform: translateY(0);
    }

    .consultar-btn i {
        font-size: 1.1rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos de la galería
        const mainImage = document.getElementById('main-product-image');
        const thumbnails = document.querySelectorAll('.thumbnail-item');
        const prevButton = document.querySelector('.prev-image');
        const nextButton = document.querySelector('.next-image');

        // Elementos del zoom modal
        const zoomModal = document.getElementById('zoom-modal');
        const zoomImage = document.getElementById('zoom-image');
        const zoomClose = document.querySelector('.zoom-close');
        const zoomPrev = document.querySelector('.zoom-prev');
        const zoomNext = document.querySelector('.zoom-next');

        // Elementos del selector de cantidad
        const quantityInput = document.getElementById('product-quantity');
        const decreaseBtn = document.querySelector('.decrease-quantity');
        const increaseBtn = document.querySelector('.increase-quantity');
        const addToCartBtn = document.getElementById('add-to-cart-btn');

        // Índice de la imagen actual
        let currentIndex = 0;
        const totalImages = thumbnails.length;

        // Variable para detectar doble clic
        let clickCount = 0;
        let clickTimer = null;
        const clickDelay = 300; // Tiempo en ms para considerar doble clic

        // Función para cambiar la imagen principal
        function changeMainImage(index) {
            // Desactivar todas las miniaturas
            thumbnails.forEach(thumb => thumb.classList.remove('active'));

            // Activar la miniatura seleccionada
            thumbnails[index].classList.add('active');

            // Cambiar la imagen principal
            const newImageSrc = thumbnails[index].getAttribute('data-image');
            mainImage.src = newImageSrc;

            // Actualizar el índice actual
            currentIndex = index;
        }

        // Evento para las miniaturas
        thumbnails.forEach((thumb, index) => {
            thumb.addEventListener('click', () => {
                changeMainImage(index);
            });
        });

        // Evento para el botón anterior
        prevButton.addEventListener('click', () => {
            let newIndex = currentIndex - 1;
            if (newIndex < 0) newIndex = totalImages - 1;
            changeMainImage(newIndex);
        });

        // Evento para el botón siguiente
        nextButton.addEventListener('click', () => {
            let newIndex = currentIndex + 1;
            if (newIndex >= totalImages) newIndex = 0;
            changeMainImage(newIndex);
        });

        // Soporte para gestos táctiles (swipe)
        let touchStartX = 0;
        let touchEndX = 0;

        const imageContainer = document.querySelector('.image-zoom-container');

        imageContainer.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, false);

        imageContainer.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);

        function handleSwipe() {
            const swipeThreshold = 50; // Umbral mínimo para considerar un swipe

            if (touchEndX < touchStartX - swipeThreshold) {
                // Swipe izquierda (siguiente imagen)
                let newIndex = currentIndex + 1;
                if (newIndex >= totalImages) newIndex = 0;
                changeMainImage(newIndex);
            }

            if (touchEndX > touchStartX + swipeThreshold) {
                // Swipe derecha (imagen anterior)
                let newIndex = currentIndex - 1;
                if (newIndex < 0) newIndex = totalImages - 1;
                changeMainImage(newIndex);
            }
        }

        // Zoom de imagen con doble clic
        mainImage.addEventListener('click', function(e) {
            e.preventDefault();

            clickCount++;

            if (clickCount === 1) {
                clickTimer = setTimeout(function() {
                    clickCount = 0;
                    // Acción para un solo clic (ninguna en este caso)
                }, clickDelay);
            } else if (clickCount === 2) {
                clearTimeout(clickTimer);
                clickCount = 0;

                // Acción para doble clic (abrir zoom)
                zoomImage.src = this.src;
                zoomImage.alt = this.alt;
                zoomModal.style.display = 'flex';
            }
        });

        // Cerrar zoom modal
        zoomClose.addEventListener('click', () => {
            zoomModal.style.display = 'none';
        });

        zoomModal.addEventListener('click', (e) => {
            if (e.target === zoomModal) {
                zoomModal.style.display = 'none';
            }
        });

        // Navegación en el zoom modal con botones
        zoomPrev.addEventListener('click', (e) => {
            e.stopPropagation(); // Evitar que el clic se propague al modal
            let newIndex = currentIndex - 1;
            if (newIndex < 0) newIndex = totalImages - 1;
            changeMainImage(newIndex);
            zoomImage.src = mainImage.src;
        });

        zoomNext.addEventListener('click', (e) => {
            e.stopPropagation(); // Evitar que el clic se propague al modal
            let newIndex = currentIndex + 1;
            if (newIndex >= totalImages) newIndex = 0;
            changeMainImage(newIndex);
            zoomImage.src = mainImage.src;
        });

        // Navegación en el zoom modal con clic en la imagen
        zoomImage.addEventListener('click', (e) => {
            e.stopPropagation(); // Evitar que el clic se propague al modal
            const rect = zoomImage.getBoundingClientRect();
            const x = e.clientX - rect.left;

            // Si se hace clic en la mitad izquierda, ir a la imagen anterior
            if (x < rect.width / 2) {
                let newIndex = currentIndex - 1;
                if (newIndex < 0) newIndex = totalImages - 1;
                changeMainImage(newIndex);
                zoomImage.src = mainImage.src;
            }

            // Si se hace clic en la mitad derecha, ir a la siguiente imagen
            if (x >= rect.width / 2) {
                let newIndex = currentIndex + 1;
                if (newIndex >= totalImages) newIndex = 0;
                changeMainImage(newIndex);
                zoomImage.src = mainImage.src;
            }
        });

        // Compartir por WhatsApp
        const shareWhatsApp = document.querySelector('.share-whatsapp');
        shareWhatsApp.addEventListener('click', function(e) {
            e.preventDefault();
            const text = `¡Mira este producto! ${document.querySelector('.product-title').textContent}`;
            const url = window.location.href;
            const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text + ' - ' + url)}`;
            window.open(whatsappUrl, '_blank');
        });

        // Copiar enlace
        const shareCopy = document.querySelector('.share-copy');
        shareCopy.addEventListener('click', async function(e) {
            e.preventDefault();
            const url = window.location.href;

            try {
                // Intentar usar la API moderna del portapapeles
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    await navigator.clipboard.writeText(url);
                    showCopyTooltip(this);
                } else {
                    // Método alternativo para navegadores más antiguos
                    const tempInput = document.createElement('input');
                    tempInput.value = url;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);
                    showCopyTooltip(this);
                }
            } catch (err) {
                console.error('Error al copiar: ', err);
                alert('Error al copiar el enlace');
            }
        });

        function showCopyTooltip(element) {
            // Mostrar tooltip de éxito
            const tooltip = document.createElement('div');
            tooltip.className = 'copy-tooltip';
            tooltip.textContent = '¡Enlace copiado!';
            element.appendChild(tooltip);

            // Cambiar ícono temporalmente
            const originalIcon = element.innerHTML;
            element.innerHTML = '<i class="bi bi-check-lg"></i>';

            // Restaurar después de 2 segundos
            setTimeout(() => {
                element.innerHTML = originalIcon;
                if (tooltip.parentNode) {
                    tooltip.remove();
                }
            }, 2000);
        }

        // Teclas de navegación para el zoom modal
        document.addEventListener('keydown', (e) => {
            if (zoomModal.style.display === 'flex') {
                if (e.key === 'Escape') {
                    zoomModal.style.display = 'none';
                } else if (e.key === 'ArrowLeft') {
                    let newIndex = currentIndex - 1;
                    if (newIndex < 0) newIndex = totalImages - 1;
                    changeMainImage(newIndex);
                    zoomImage.src = mainImage.src;
                } else if (e.key === 'ArrowRight') {
                    let newIndex = currentIndex + 1;
                    if (newIndex >= totalImages) newIndex = 0;
                    changeMainImage(newIndex);
                    zoomImage.src = mainImage.src;
                }
            }
        });

        // Funcionalidad del selector de cantidad
        if (quantityInput) {
            const maxStock = parseInt(quantityInput.getAttribute('max'));

            // Actualizar estado de los botones
            function updateQuantityButtons() {
                const currentValue = parseInt(quantityInput.value);
                decreaseBtn.disabled = currentValue <= 1;
                increaseBtn.disabled = currentValue >= maxStock;
            }

            // Inicializar
            updateQuantityButtons();

            // Evento para disminuir cantidad
            decreaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                    updateQuantityButtons();
                }
            });

            // Evento para aumentar cantidad
            increaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue < maxStock) {
                    quantityInput.value = currentValue + 1;
                    updateQuantityButtons();
                }
            });

            // Evento para agregar al carrito
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', () => {
                    const quantity = parseInt(quantityInput.value);

                    // Verificar stock disponible
                    if (quantity > maxStock) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Stock insuficiente',
                            text: `Solo hay ${maxStock} unidades disponibles.`,
                            confirmButtonColor: '#104D43'
                        });
                        return;
                    }

                    // Obtener datos del producto
                    const productData = {
                        id: <?php echo $id_producto; ?>,
                        name: '<?php echo addslashes(htmlspecialchars($producto['nombre_producto'])); ?>',
                        price: <?php echo floatval($producto['valor_producto']); ?>,
                        image: '<?php echo !empty($imagenes) ? addslashes(htmlspecialchars($imagenes[0])) : 'assets/img/no-image.jpg'; ?>',
                        maxStock: maxStock
                    };

                    // Llamar a la función global addToCart con la cantidad seleccionada
                    if (typeof window.addToCart === 'function') {
                        // Agregar la cantidad seleccionada al objeto del producto
                        productData.quantity = quantity;

                        // Llamar a la función de agregar al carrito
                        window.addToCart(productData);
                    } else {
                        console.error('La función addToCart no está disponible');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo agregar el producto al carrito.',
                            confirmButtonColor: '#104D43'
                        });
                    }
                });
            }
        }
    });
</script>