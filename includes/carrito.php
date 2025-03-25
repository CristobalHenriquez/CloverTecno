<?php
// Verificar si hay una sesión activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// La función esta_logueado() ya está definida en inc.head.php
// No es necesario declararla nuevamente aquí
?>

<!-- Estilos del carrito -->
<style>
    .cart-icon {
        position: fixed;
        bottom: 80px;
        right: 20px;
        background: linear-gradient(135deg, #104D43, #187766);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        z-index: 999;
        transition: all 0.3s ease;
    }

    .cart-icon:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
    }

    .cart-count {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #ff4757;
        color: white;
        font-size: 12px;
        font-weight: bold;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        overflow: auto;
    }

    .cart-content {
        position: fixed;
        top: 0;
        right: -400px;
        width: 100%;
        max-width: 400px;
        height: 100%;
        background-color: white;
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .cart-header {
        padding: 20px;
        background: linear-gradient(135deg, #104D43, #187766);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-header h3 {
        margin: 0;
        font-size: 1.2rem;
    }

    .close-cart {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }

    .cart-item {
        display: flex;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .cart-item-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
        margin-right: 15px;
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-title {
        font-weight: 500;
        margin-bottom: 5px;
        color: #000;
    }

    .cart-item-price {
        color: #104D43;
        font-weight: 600;
    }

    .cart-item-actions {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .quantity-btn {
        background: #f1f1f1;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
    }

    .quantity-input {
        width: 40px;
        height: 30px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 0 5px;
    }

    .remove-item {
        margin-left: auto;
        background: none;
        border: none;
        color: #ff4757;
        cursor: pointer;
    }

    .cart-footer {
        padding: 20px;
        background-color: #f9f9f9;
        border-top: 1px solid #eee;
    }

    .cart-total {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-weight: 600;
        color: #000;
    }

    .checkout-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #104D43, #187766);
        color: white;
        border: none;
        border-radius: 5px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .checkout-btn:hover {
        background: linear-gradient(135deg, #0E443B, #156658);
    }

    .checkout-btn:disabled {
        background: #cccccc;
        cursor: not-allowed;
    }

    .empty-cart {
        text-align: center;
        padding: 30px;
        color: #777;
    }

    .empty-cart i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #ddd;
    }

    @media (max-width: 576px) {
        .cart-content {
            max-width: 100%;
        }
    }
</style>

<!-- Icono del carrito -->
<div class="cart-icon" id="cartIcon">
    <i class="bi bi-cart3"></i>
    <span class="cart-count" id="cartCount">0</span>
</div>

<!-- Modal del carrito -->
<div class="cart-modal" id="cartModal">
    <div class="cart-content" id="cartContent">
        <div class="cart-header">
            <h3>Tu Carrito</h3>
            <button class="close-cart" id="closeCart">&times;</button>
        </div>
        <div class="cart-items" id="cartItems">
            <!-- Los items del carrito se cargarán dinámicamente aquí -->
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span id="cartTotal">$0.00</span>
            </div>
            <button class="checkout-btn" id="checkoutBtn">Proceder al Pago</button>
        </div>
    </div>
</div>

<!-- Script del carrito -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos del DOM
        const cartIcon = document.getElementById('cartIcon');
        const cartModal = document.getElementById('cartModal');
        const cartContent = document.getElementById('cartContent');
        const closeCart = document.getElementById('closeCart');
        const cartItems = document.getElementById('cartItems');
        const cartCount = document.getElementById('cartCount');
        const cartTotal = document.getElementById('cartTotal');
        const checkoutBtn = document.getElementById('checkoutBtn');

        // Estado del carrito
        let cart = [];
        
        // Cargar carrito desde localStorage
        function loadCart() {
            const savedCart = localStorage.getItem('carrito');
            if (savedCart) {
                cart = JSON.parse(savedCart);
                updateCartUI();
            }
        }
        
        // Guardar carrito en localStorage
        function saveCart() {
            localStorage.setItem('carrito', JSON.stringify(cart));
            updateCartUI();
        }
        
        // Actualizar la interfaz del carrito
        function updateCartUI() {
            // Actualizar contador
            cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);
            
            // Actualizar lista de items
            if (cart.length === 0) {
                cartItems.innerHTML = `
                    <div class="empty-cart">
                        <i class="bi bi-cart-x"></i>
                        <p>Tu carrito está vacío</p>
                        <p>Agrega productos para comenzar a comprar</p>
                    </div>
                `;
                checkoutBtn.disabled = true;
            } else {
                cartItems.innerHTML = '';
                let total = 0;
                
                cart.forEach((item, index) => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;
                    
                    const itemElement = document.createElement('div');
                    itemElement.className = 'cart-item';
                    itemElement.innerHTML = `
                        <img src="${item.image}" alt="${item.name}" class="cart-item-img">
                        <div class="cart-item-details">
                            <div class="cart-item-title">${item.name}</div>
                            <div class="cart-item-price">$${item.price.toFixed(2)}</div>
                            <div class="cart-item-actions">
                                <button class="quantity-btn decrease-btn" data-index="${index}">-</button>
                                <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="${item.maxStock || 99}" data-index="${index}" readonly>
                                <button class="quantity-btn increase-btn" data-index="${index}">+</button>
                                <button class="remove-item" data-index="${index}"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    `;
                    cartItems.appendChild(itemElement);
                });
                
                // Actualizar total
                cartTotal.textContent = `$${total.toFixed(2)}`;
                checkoutBtn.disabled = false;
            }
            
            // Agregar event listeners a los botones
            document.querySelectorAll('.decrease-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    if (cart[index].quantity > 1) {
                        cart[index].quantity--;
                        saveCart();
                    }
                });
            });
            
            document.querySelectorAll('.increase-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const maxStock = cart[index].maxStock || 99;
                    
                    if (cart[index].quantity < maxStock) {
                        cart[index].quantity++;
                        saveCart();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Stock limitado',
                            text: `Solo hay ${maxStock} unidades disponibles de este producto.`,
                            confirmButtonColor: '#104D43'
                        });
                    }
                });
            });
            
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    cart.splice(index, 1);
                    saveCart();
                });
            });
        }
        
        // Abrir carrito
        cartIcon.addEventListener('click', function() {
            cartModal.style.display = 'block';
            setTimeout(() => {
                cartContent.style.right = '0';
            }, 10);
        });
        
        // Cerrar carrito
        closeCart.addEventListener('click', function() {
            cartContent.style.right = '-400px';
            setTimeout(() => {
                cartModal.style.display = 'none';
            }, 300);
        });
        
        // Cerrar carrito al hacer clic fuera
        cartModal.addEventListener('click', function(e) {
            if (e.target === cartModal) {
                cartContent.style.right = '-400px';
                setTimeout(() => {
                    cartModal.style.display = 'none';
                }, 300);
            }
        });
        
        // Proceder al pago
        checkoutBtn.addEventListener('click', function() {
            const isLoggedIn = <?php echo esta_logueado() ? 'true' : 'false'; ?>;
            
            if (!isLoggedIn) {
                Swal.fire({
                    title: 'Inicia sesión',
                    text: 'Debes iniciar sesión para completar tu compra',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Iniciar sesión',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#104D43'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'Registro';
                    }
                });
            } else {
                // Redirigir a la página de checkout
                window.location.href = 'checkout.php';
            }
        });
        
        // Función para agregar productos al carrito (para usar desde otras páginas)
        window.addToCart = function(product) {
            // Verificar que el producto tenga todos los datos necesarios
            if (!product.id || !product.name || !product.price || !product.image) {
                console.error('Datos de producto incompletos', product);
                return;
            }
            
            // Asegurarse de que maxStock esté definido
            const maxStock = product.maxStock || 99;
            
            // Buscar si el producto ya está en el carrito
            const existingItemIndex = cart.findIndex(item => item.id === product.id);
            
            if (existingItemIndex !== -1) {
                // El producto ya está en el carrito
                const newQuantity = cart[existingItemIndex].quantity + (product.quantity || 1);
                
                // Verificar si hay suficiente stock
                if (newQuantity > maxStock) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stock limitado',
                        text: `Solo hay ${maxStock} unidades disponibles. Ya tienes ${cart[existingItemIndex].quantity} en tu carrito.`,
                        confirmButtonColor: '#104D43'
                    });
                    return;
                }
                
                // Actualizar cantidad
                cart[existingItemIndex].quantity = newQuantity;
            } else {
                // Agregar nuevo producto al carrito
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    image: product.image,
                    quantity: product.quantity || 1,
                    maxStock: maxStock
                });
            }
            
            saveCart();
            
            // Mostrar notificación
            Swal.fire({
                title: '¡Producto agregado!',
                text: `${product.name} se ha agregado a tu carrito`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        };
        
        // Cargar carrito al iniciar
        loadCart();
    });
</script>