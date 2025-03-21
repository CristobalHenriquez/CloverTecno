<!-- Modal para Paso 3: Confirmación de Venta -->
<div class="modal fade" id="paso3Modal" tabindex="-1" aria-labelledby="paso3ModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paso3ModalLabel" style="color: #000;">Nueva Venta - Confirmación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Pasos de la venta -->
                <div class="position-relative mb-4">
                    <div class="steps-container">
                        <div class="step completed" id="step1">
                            <div class="step-number">1</div>
                            <div class="step-title">Datos del Cliente</div>
                        </div>
                        <div class="step completed" id="step2">
                            <div class="step-number">2</div>
                            <div class="step-title">Productos</div>
                        </div>
                        <div class="step active" id="step3">
                            <div class="step-number">3</div>
                            <div class="step-title">Confirmación</div>
                        </div>
                    </div>
                    <div class="steps-line"></div>
                    <div class="steps-progress" style="width: 100%"></div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Revise los datos de la venta antes de confirmar.
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Datos del Cliente</h5>
                            </div>
                            <div class="card-body" id="resumen_cliente">
                                <!-- Se cargará dinámicamente -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Resumen de la Venta</h5>
                            </div>
                            <div class="card-body">
                                <div id="resumen_productos">
                                    <!-- Se cargará dinámicamente -->
                                </div>
                                <div class="resumen-total mt-3">
                                    <p><strong>Total:</strong> <span id="resumen_total">$0</span></p>
                                    <div id="resumen_descuento" style="display: none;"></div>
                                    <div id="resumen_total_con_descuento" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="volverPaso2Btn">
                    <i class="bi bi-arrow-left"></i> Volver
                </button>
                <button type="button" class="btn btn-success" id="finalizarVentaBtn">
                    <i class="bi bi-check-circle"></i> Finalizar Venta
                </button>
            </div>
        </div>
    </div>
</div>