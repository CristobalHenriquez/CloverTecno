<div class="checkout-form" data-form="4">
    <div class="form-header">
        <h3>Revisa tu Pedido</h3>
        <p>Por favor revisa tu información antes de finalizar la compra</p>
    </div>
    <form class="checkout-form-element" id="checkout-form" method="POST">
        <div class="review-sections">
            <div class="review-section">
                <div class="review-section-header">
                    <h4>Información de Contacto</h4>
                    <button type="button" class="btn-edit" data-edit="1">Editar</button>
                </div>
                <div class="review-section-content">
                    <p class="review-name" id="review-name"></p>
                    <p class="review-email" id="review-email"></p>
                    <p class="review-dnicuit" id="review-dnicuit"></p>
                    <p class="review-phone" id="review-phone"></p>
                </div>
            </div>

            <div class="review-section mt-3">
                <div class="review-section-header">
                    <h4>Dirección de Envío</h4>
                    <button type="button" class="btn-edit" data-edit="2">Editar</button>
                </div>
                <div class="review-section-content">
                    <p id="review-address-line1"></p>
                    <p id="review-address-line2"></p>
                </div>
            </div>

            <div class="review-section mt-3">
                <div class="review-section-header">
                    <h4>Método de Pago</h4>
                    <button type="button" class="btn-edit" data-edit="3">Editar</button>
                </div>
                <div class="review-section-content">
                    <p id="review-payment-method"></p>
                    <p id="review-notes" class="text-muted"></p>
                </div>
            </div>
        </div>

        <!-- Campos ocultos para enviar al servidor -->
        <input type="hidden" name="nombre" id="hidden-nombre">
        <input type="hidden" name="email" id="hidden-email">
        <input type="hidden" name="dnicuit" id="hidden-dnicuit">
        <input type="hidden" name="telefono" id="hidden-telefono">
        <input type="hidden" name="direccion" id="hidden-direccion">
        <input type="hidden" name="apartamento" id="hidden-apartamento">
        <input type="hidden" name="ciudad" id="hidden-ciudad">
        <input type="hidden" name="provincia" id="hidden-provincia">
        <input type="hidden" name="codigo_postal" id="hidden-codigo-postal">
        <input type="hidden" name="metodo_pago" id="hidden-metodo-pago">
        <input type="hidden" name="notas" id="hidden-notas">
        <input type="hidden" name="total_venta" id="hidden-total">
        <input type="hidden" name="productos_carrito" id="hidden-productos">

        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" id="terminos" name="terminos" required>
            <label class="form-check-label" for="terminos">
                Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#terminosModal">Términos y Condiciones</a> y la <a href="#" data-bs-toggle="modal" data-bs-target="#privacidadModal">Política de Privacidad</a>
            </label>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary prev-step" data-prev="3">Volver a Pago</button>
            <button type="submit" name="finalizar_compra" class="btn btn-success" id="finalizar-compra-btn">Finalizar Compra</button>
        </div>
    </form>
</div>