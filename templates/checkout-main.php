<?php
// Incluir el controlador
include_once 'controllers/procesar_checkout.php';

// Verificar que el usuario esté logueado
requireCliente();

// Obtener información del cliente
$cliente_id = $_SESSION['cliente_id'];
$cliente = getClienteInfo($db, $cliente_id);

// Variable para almacenar el resultado del procesamiento
$resultado_compra = null;
$error_message = null;

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_compra'])) {
    // Recuperar datos del formulario usando nuestra función personalizada
    $nombre = sanitizeString($_POST['nombre'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefono = sanitizeString($_POST['telefono'] ?? '');
    $dnicuit = sanitizeString($_POST['dnicuit'] ?? '');
    $direccion = sanitizeString($_POST['direccion'] ?? '');
    $apartamento = sanitizeString($_POST['apartamento'] ?? '');
    $ciudad = sanitizeString($_POST['ciudad'] ?? '');
    $provincia = sanitizeString($_POST['provincia'] ?? '');
    $codigo_postal = sanitizeString($_POST['codigo_postal'] ?? '');
    $metodo_pago = sanitizeString($_POST['metodo_pago'] ?? '');
    $notas = sanitizeString($_POST['notas'] ?? '');

    // Validar que los campos requeridos no estén vacíos
    if (empty($nombre) || empty($email) || empty($telefono) || empty($dnicuit) || empty($direccion) || empty($ciudad) || empty($provincia) || empty($codigo_postal)) {
        $error_message = "Por favor, complete todos los campos obligatorios.";
    } else {
        // Crear la dirección completa
        $direccion_completa = $direccion;
        if (!empty($apartamento)) {
            $direccion_completa .= ", " . $apartamento;
        }
        $direccion_completa .= ", " . $ciudad . ", " . $provincia . ", CP: " . $codigo_postal;

        // Obtener el total desde JavaScript (se enviará como campo oculto)
        $total_venta = filter_input(INPUT_POST, 'total_venta', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // Procesar los productos del carrito (se enviarán como JSON desde JavaScript)
        $productos_json = $_POST['productos_carrito'] ?? '[]';
        $productos = json_decode($productos_json, true);

        // Preparar datos para procesar la compra
        $datos = [
            'cliente_id' => $cliente_id,
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'dnicuit' => $dnicuit,
            'direccion_completa' => $direccion_completa,
            'total_venta' => $total_venta,
            'metodo_pago' => $metodo_pago,
            'notas' => $notas,
            'productos' => $productos
        ];

        // Procesar la compra
        $resultado_compra = procesarCompra($db, $datos);
    }
}

// Incluir los estilos del checkout
include_once 'templates/checkout-styles.php';
?>

<!-- Checkout Section -->
<section id="checkout" class="checkout-section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <?php if ($resultado_compra && $resultado_compra['success']): ?>
            <!-- Mostrar mensaje de éxito -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="success-message text-center p-5 bg-light rounded">
                        <i class="bi bi-check-circle-fill" style="font-size: 4rem; color: #104D43;"></i>
                        <h2 class="mt-4">¡Compra realizada con éxito!</h2>
                        <p class="lead">Tu pedido #ORD-<?php echo $resultado_compra['id_venta']; ?> ha sido procesado correctamente.</p>

                        <?php if ($resultado_compra['metodo_pago'] === 'Transferencia Bancaria'): ?>
                            <div class="alert alert-info mt-4" style="background-color: rgba(16, 77, 67, 0.1); border-color: rgba(16, 77, 67, 0.2); color: #104D43;">
                                <h4>Datos para la transferencia</h4>
                                <p>Banco: <?php echo $resultado_compra['datos_bancarios']['banco']; ?><br>
                                    Titular: <?php echo $resultado_compra['datos_bancarios']['titular']; ?><br>
                                    CBU: <?php echo $resultado_compra['datos_bancarios']['cbu']; ?><br>
                                    CUIT: <?php echo $resultado_compra['datos_bancarios']['cuit']; ?></p>

                                <p class="mt-3">Monto a transferir: <strong>$<?php echo number_format($resultado_compra['total_venta'], 2, ',', '.'); ?></strong></p>

                                <?php if (isset($resultado_compra['descuento']) && $resultado_compra['descuento'] > 0): ?>
                                    <p class="text-success">¡Has obtenido un descuento del 20% ($<?php echo number_format($resultado_compra['descuento'], 2, ',', '.'); ?>) por pagar con transferencia!</p>
                                <?php endif; ?>

                                <p class="mt-3">Una vez realizada la transferencia, envíanos el comprobante por WhatsApp:</p>
                                <a href="https://wa.me/<?php echo $resultado_compra['datos_bancarios']['whatsapp']; ?>?text=Hola,%20adjunto%20comprobante%20de%20pago%20para%20el%20pedido%20#ORD-<?php echo $resultado_compra['id_venta']; ?>" class="btn btn-success mt-2" target="_blank" style="background-color: #25D366; border-color: #25D366;">
                                    <i class="bi bi-whatsapp"></i> Enviar Comprobante
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="mt-4">
                            <a href="Cliente" class="btn btn-primary" style="background-color: #104D43; border-color: #104D43;">Ver mis pedidos</a>
                            <a href="Productos" class="btn btn-outline-secondary ms-2" style="color: #104D43; border-color: #104D43;">Seguir comprando</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Script para limpiar el carrito -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    localStorage.removeItem('carrito');
                });
            </script>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="checkout-steps mb-4" data-aos="fade-up">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-title">Información</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-title">Envío</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-title">Pago</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-title">Revisión</div>
                        </div>
                    </div>

                    <!-- Formularios del Checkout -->
                    <div class="checkout-forms" data-aos="fade-up" data-aos-delay="150">
                        <!-- Pasos-->
                        <?php include_once 'templates/checkout-all-steps.php'; ?>
                    </div>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger mt-3">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($resultado_compra && !$resultado_compra['success']): ?>
                        <div class="alert alert-danger mt-3">
                            <?php echo $resultado_compra['message']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4">
                    <!-- Resumen del Pedido -->
                    <?php include_once 'templates/checkout-summary.php'; ?>
                </div>
            </div>

            <!-- Modales de Términos y Privacidad -->
            <?php include_once 'templates/checkout-modals.php'; ?>
        <?php endif; ?>
    </div>
</section><!-- /Checkout Section -->

<!-- Script para el checkout -->
<?php if (!$resultado_compra || !$resultado_compra['success']): ?>
    <?php include_once 'templates/checkout-scripts.php'; ?>
<?php endif; ?>