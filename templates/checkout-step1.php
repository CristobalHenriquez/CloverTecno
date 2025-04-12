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
            <label for="dnicuit">DNI / CUIT</label>
            <input type="text" class="form-control" name="dnicuit" id="dnicuit" placeholder="Tu DNI o CUIT" required>
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