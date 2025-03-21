<!-- Modal para Paso 1: Datos del Cliente -->
<div class="modal fade" id="paso1Modal" tabindex="-1" aria-labelledby="paso1ModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paso1ModalLabel" style="color: #000;">Nueva Venta - Datos del Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Pasos de la venta -->
                <div class="position-relative mb-4">
                    <div class="steps-container">
                        <div class="step active" id="step1">
                            <div class="step-number">1</div>
                            <div class="step-title">Datos del Cliente</div>
                        </div>
                        <div class="step" id="step2">
                            <div class="step-number">2</div>
                            <div class="step-title">Productos</div>
                        </div>
                        <div class="step" id="step3">
                            <div class="step-number">3</div>
                            <div class="step-title">Confirmación</div>
                        </div>
                    </div>
                    <div class="steps-line"></div>
                    <div class="steps-progress" style="width: 0%"></div>
                </div>
                
                <form id="datosClienteForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombreyapellido_cliente" class="form-label">Nombre y Apellido *</label>
                                <input type="text" class="form-control" id="nombreyapellido_cliente" name="nombreyapellido_cliente" required>
                            </div>
                            <div class="mb-3">
                                <label for="email_cliente" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email_cliente" name="email_cliente">
                            </div>
                            <div class="mb-3">
                                <label for="dnicuit_cliente" class="form-label">DNI/CUIT</label>
                                <input type="text" class="form-control" id="dnicuit_cliente" name="dnicuit_cliente">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono_cliente" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono_cliente" name="telefono_cliente">
                            </div>
                            <div class="mb-3">
                                <label for="domicilio_cliente" class="form-label">Domicilio</label>
                                <input type="text" class="form-control" id="domicilio_cliente" name="domicilio_cliente">
                            </div>
                            <div class="mb-3">
                                <label for="notas" class="form-label">Notas adicionales</label>
                                <textarea class="form-control" id="notas" name="notas" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="continuarPaso2Btn">
                    Continuar <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>