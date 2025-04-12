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
                    <p>Realiza una transferencia bancaria a nuestra cuenta. Tu pedido se procesará una vez confirmado el pago.</p>
                    <div class="alert alert-info mt-2">
                        <strong>Datos bancarios:</strong><br>
                        Banco: Banco Nación<br>
                        Titular: Clover Tecno S.A.<br>
                        CBU: 0110000000000000000000<br>
                        CUIT: 30-12345678-9
                    </div>
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