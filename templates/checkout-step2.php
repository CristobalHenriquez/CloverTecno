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