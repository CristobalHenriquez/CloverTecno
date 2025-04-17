<div class="order-summary" data-aos="fade-left" data-aos-delay="200">
    <div class="order-summary-header">
        <h3>Resumen del Pedido</h3>
        <button type="button" class="btn-toggle-summary d-lg-none">
            <i class="bi bi-chevron-down"></i>
        </button>
    </div>

    <div class="order-summary-content">
        <div class="order-items" id="order-items">
            <!-- Los items del carrito se cargarán dinámicamente aquí -->
        </div>

        <div class="order-totals">
            <div class="order-subtotal d-flex justify-content-between">
                <span>Subtotal</span>
                <span id="order-subtotal">$0.00</span>
            </div>
            <div class="order-shipping d-flex justify-content-between">
                <span>Envío</span>
                <span id="order-shipping">Gratis</span>
            </div>
            <div class="order-discount d-flex justify-content-between d-none">
                <span>Descuento (20%)</span>
                <span id="order-discount">-$0.00</span>
            </div>
            <div class="order-total d-flex justify-content-between">
                <span>Total</span>
                <span id="order-total">$0.00</span>
            </div>
        </div>

        <div class="promo-code mt-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Código Promocional" aria-label="Código Promocional">
                <button class="btn btn-outline-primary" type="button">Aplicar</button>
            </div>
        </div>

        <div class="secure-checkout mt-4">
            <div class="secure-checkout-header">
                <i class="bi bi-shield-lock"></i>
                <span>Pago Seguro</span>
            </div>
            <div class="payment-icons mt-2">
                <i class="bi bi-credit-card-2-front"></i>
                <i class="bi bi-credit-card"></i>
                <i class="bi bi-bank"></i>
                <i class="bi bi-cash"></i>
            </div>
        </div>
    </div>
</div>