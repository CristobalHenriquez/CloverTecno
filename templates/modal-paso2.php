<!-- Modal para Paso 2: Selección de Productos -->
<div class="modal fade" id="paso2Modal" tabindex="-1" aria-labelledby="paso2ModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paso2ModalLabel" style="color: #000;">Nueva Venta - Selección de Productos</h5>
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
                        <div class="step active" id="step2">
                            <div class="step-number">2</div>
                            <div class="step-title">Productos</div>
                        </div>
                        <div class="step" id="step3">
                            <div class="step-number">3</div>
                            <div class="step-title">Confirmación</div>
                        </div>
                    </div>
                    <div class="steps-line"></div>
                    <div class="steps-progress" style="width: 50%"></div>
                </div>
                
                <div class="row">
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Agregar Productos</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="producto_select" class="form-label">Seleccionar Producto</label>
                                    <select class="form-select producto-select" id="producto_select">
                                        <option value="">Seleccione un producto</option>
                                        <?php
                                        // Consulta para obtener todos los productos con stock
                                        $sqlProductos = "SELECT p.id_producto, p.nombre_producto, p.valor_producto, p.stock
                                                        FROM productos p
                                                        WHERE p.stock > 0 OR p.stock IS NULL
                                                        ORDER BY p.nombre_producto";
                                        $resultProductos = $db->query($sqlProductos);
                                        
                                        if ($resultProductos && $resultProductos->num_rows > 0) {
                                            while ($producto = $resultProductos->fetch_assoc()) {
                                                $stockText = $producto['stock'] !== null ? "Stock: " . $producto['stock'] : "Stock: No disponible";
                                                $stockClass = '';
                                                if ($producto['stock'] !== null) {
                                                    if ($producto['stock'] > 10) {
                                                        $stockClass = 'high';
                                                    } elseif ($producto['stock'] > 5) {
                                                        $stockClass = 'medium';
                                                    } else {
                                                        $stockClass = 'low';
                                                    }
                                                }
                                                
                                                echo '<option value="' . $producto['id_producto'] . '" 
                                                        data-nombre="' . htmlspecialchars($producto['nombre_producto']) . '" 
                                                        data-precio="' . $producto['valor_producto'] . '" 
                                                        data-stock="' . $producto['stock'] . '" 
                                                        data-stock-class="' . $stockClass . '">
                                                        ' . htmlspecialchars($producto['nombre_producto']) . ' - $' . number_format($producto['valor_producto'], 0, ',', '.') . ' (' . $stockText . ')
                                                      </option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="cantidad_producto" class="form-label">Cantidad</label>
                                    <input type="number" class="form-control" id="cantidad_producto" min="1" value="1">
                                </div>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary w-100" id="agregarProductoBtn">
                                        <i class="bi bi-plus-circle"></i> Agregar Producto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Productos en la venta</h5>
                            </div>
                            <div class="card-body">
                                <div id="items_carrito">
                                    <div class="alert alert-info">No hay productos agregados</div>
                                </div>
                                <div class="cart-summary">
                                    <div class="mb-3">
                                        <label for="descuento_porcentaje" class="form-label">Descuento (%)</label>
                                        <input type="number" class="form-control" id="descuento_porcentaje" min="0" max="100" value="0" step="1">
                                        <small class="text-muted">Ingrese el porcentaje de descuento a aplicar</small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <h5>Total:</h5>
                                        <h5 id="total_venta">$0</h5>
                                        <input type="hidden" id="total_venta_input" value="0">
                                    </div>
                                    <div class="d-flex justify-content-between descuento-aplicado" style="display: none !important;">
                                        <h6>Descuento:</h6>
                                        <h6 id="descuento_monto">$0</h6>
                                    </div>
                                    <div class="d-flex justify-content-between descuento-aplicado" style="display: none !important;">
                                        <h5 class="text-success">Total con descuento:</h5>
                                        <h5 class="text-success" id="total_con_descuento">$0</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="volverPaso1Btn">
                    <i class="bi bi-arrow-left"></i> Volver
                </button>
                <button type="button" class="btn btn-success" id="continuarPaso3Btn">
                    Continuar <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>