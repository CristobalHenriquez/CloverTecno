<div class="checkout-form active" data-form="1">
    <div class="form-header">
        <h3>Información del Cliente</h3>
        <p>Por favor ingresa tus datos de contacto</p>
    </div>
    <form class="checkout-form-element">
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="nombre">Nombre y Apellido</label>
                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Tu nombre completo" value="<?php echo htmlspecialchars($cliente['nombre_apellido'] ?? ''); ?>" required>
            </div>
        </div>
        <div class="form-group mt-3">
            <label for="email">Correo Electrónico</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Tu correo electrónico" value="<?php echo htmlspecialchars($cliente['correo'] ?? ''); ?>" required>
        </div>
        <div class="form-group mt-3">
            <label for="dnicuit">DNI</label>
            <input type="number" class="form-control" name="dnicuit" id="dnicuit" placeholder="Tu DNI" required>
        </div>
        <div class="form-group mt-3">
            <label for="telefono">Número de Teléfono</label>
            <input type="tel" class="form-control" name="telefono" id="telefono" placeholder="Tu número de teléfono" required>
        </div>
        <div class="text-end mt-4">
            <button type="button" class="btn btn-primary next-step" data-next="2">Continuar a Envío</button>
        </div>
    </form>
</div>
<div class="checkout-form" data-form="2">
    <div class="form-header">
        <h3>Dirección de Envío</h3>
        <p>¿Dónde quieres recibir tu pedido?</p>
    </div>
    <form class="checkout-form-element">
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Calle y número" required>
        </div>
        <div class="form-group mt-3">
            <label for="apartamento">Apartamento, Suite, etc. (opcional)</label>
            <input type="text" class="form-control" name="apartamento" id="apartamento" placeholder="Apartamento, Piso, etc.">
        </div>
        <div class="row mt-3">
            <div class="col-md-4 form-group">
                <label for="ciudad">Ciudad</label>
                <input type="text" name="ciudad" class="form-control" id="ciudad" placeholder="Ciudad" required>
            </div>
            <div class="col-md-4 form-group mt-3 mt-md-0">
                <label for="provincia">Provincia</label>
                <input type="text" name="provincia" class="form-control" id="provincia" placeholder="Provincia" required>
            </div>
            <div class="col-md-4 form-group mt-3 mt-md-0">
                <label for="codigo_postal">Código Postal</label>
                <input type="text" name="codigo_postal" class="form-control" id="codigo_postal" placeholder="Código Postal" required>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary prev-step" data-prev="1">Volver a Información</button>
            <button type="button" class="btn btn-primary next-step" data-next="3">Continuar a Pago</button>
        </div>
    </form>
</div>
<div class="checkout-form" data-form="3">
    <div class="form-header">
        <h3>Método de Pago</h3>
        <p>Elige cómo quieres pagar</p>
    </div>
    <form class="checkout-form-element">
        <div class="payment-methods">
            <div class="payment-method active">
                <div class="payment-method-header">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="transferencia" value="Transferencia Bancaria" checked>
                        <label class="form-check-label" for="transferencia">
                            Transferencia Bancaria
                        </label>
                    </div>
                    <div class="payment-icons">
                        <i class="bi bi-bank"></i>
                    </div>
                </div>
                <div class="payment-method-body">
                    <p>Al pagar con transferencia obtendrás un 20% de descuento. Tu pedido se procesará una vez confirmado el pago.</p>
                </div>
            </div>

            <div class="payment-method mt-3">
                <div class="payment-method-header">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="efectivo" value="Efectivo">
                        <label class="form-check-label" for="efectivo">
                            Efectivo
                        </label>
                    </div>
                    <div class="payment-icons">
                        <i class="bi bi-cash"></i>
                    </div>
                </div>
                <div class="payment-method-body d-none">
                    <p>Paga en efectivo al momento de la entrega. Disponible solo para envíos locales.</p>
                </div>
            </div>

            <div class="payment-method mt-3">
                <div class="payment-method-header">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metodo_pago" id="mercado-pago" value="Mercado Pago">
                        <label class="form-check-label" for="mercado-pago">
                            Mercado Pago
                        </label>
                    </div>
                    <div class="payment-icons">
                        <i class="bi bi-credit-card-2-front"></i>
                    </div>
                </div>
                <div class="payment-method-body d-none">
                    <p>Próximamente disponible. Podrás pagar con tarjetas de crédito, débito o saldo de Mercado Pago.</p>
                </div>
            </div>
        </div>
        <div class="form-group mt-3">
            <label for="notas">Notas adicionales (opcional)</label>
            <textarea class="form-control" name="notas" id="notas" rows="3" placeholder="Instrucciones especiales para la entrega, etc."></textarea>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2">Volver a Envío</button>
            <button type="button" class="btn btn-primary next-step" data-next="4">Revisar Pedido</button>
        </div>
    </form>
</div>
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