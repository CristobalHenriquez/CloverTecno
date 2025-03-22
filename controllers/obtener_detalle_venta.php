<?php
include_once '../includes/auth.php';
requireAuth();
include_once '../includes/db_connection.php';

// Verificar si se recibió el ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID de venta no proporcionado'
    ]);
    exit;
}

$id_venta = intval($_GET['id']);

// Obtener datos de la venta
$stmt = $db->prepare("SELECT v.*, DATE_FORMAT(v.fecha_venta, '%d/%m/%Y %H:%i') as fecha_formateada 
                      FROM ventas v 
                      WHERE v.id_venta = ?");
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Venta no encontrada'
    ]);
    exit;
}

$venta = $result->fetch_assoc();

// Obtener detalles de la venta
$stmt = $db->prepare("SELECT dv.*, p.nombre_producto, 
                     (SELECT imagen_path FROM imagenes_productos WHERE id_producto = dv.id_producto LIMIT 1) as imagen 
                     FROM detalle_ventas dv 
                     JOIN productos p ON dv.id_producto = p.id_producto 
                     WHERE dv.id_venta = ?");
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$result_detalles = $stmt->get_result();

$detalles = [];
while ($detalle = $result_detalles->fetch_assoc()) {
    $detalles[] = $detalle;
}

// Generar HTML para mostrar en el modal
$html = '
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h5>Datos de la Venta</h5>
            <p><strong>ID:</strong> ' . $venta['id_venta'] . '</p>
            <p><strong>Fecha:</strong> ' . $venta['fecha_formateada'] . '</p>
            <p><strong>Estado:</strong> <span class="estado-' . strtolower($venta['estado']) . '">' . $venta['estado'] . '</span></p>
        </div>
        <div class="col-md-6">
            <h5>Datos del Cliente</h5>
            <p><strong>Nombre y Apellido:</strong> ' . htmlspecialchars($venta['nombreyapellido_cliente']) . '</p>';

if (!empty($venta['email_cliente'])) {
    $html .= '<p><strong>Email:</strong> ' . htmlspecialchars($venta['email_cliente']) . '</p>';
}

if (!empty($venta['dnicuit_cliente'])) {
    $html .= '<p><strong>DNI/CUIT:</strong> ' . htmlspecialchars($venta['dnicuit_cliente']) . '</p>';
}

if (!empty($venta['telefono_cliente'])) {
    $html .= '<p><strong>Teléfono:</strong> ' . htmlspecialchars($venta['telefono_cliente']) . '</p>';
}

if (!empty($venta['domicilio_cliente'])) {
    $html .= '<p><strong>Domicilio:</strong> ' . htmlspecialchars($venta['domicilio_cliente']) . '</p>';
}

$html .= '
        </div>
    </div>';

if (!empty($venta['notas'])) {
    $html .= '
    <div class="row mb-4">
        <div class="col-12">
            <h5>Notas</h5>
            <p>' . nl2br(htmlspecialchars($venta['notas'])) . '</p>
        </div>
    </div>';
}

$html .= '
    <div class="row">
        <div class="col-12">
            <h5>Productos</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>';

foreach ($detalles as $detalle) {
    $html .= '
        <tr>
            <td>';
    
    if (!empty($detalle['imagen'])) {
        $html .= '<img src="' . htmlspecialchars($detalle['imagen']) . '" alt="' . htmlspecialchars($detalle['nombre_producto']) . '" class="product-image">';
    } else {
        $html .= '<div class="product-image bg-light d-flex align-items-center justify-content-center"><i class="bi bi-image text-muted"></i></div>';
    }
    
    $html .= '</td>
            <td>' . htmlspecialchars($detalle['nombre_producto']) . '</td>
            <td class="text-end">$' . number_format($detalle['precio_unitario'], 0, ',', '.') . '</td>
            <td class="text-center">' . $detalle['cantidad'] . '</td>
            <td class="text-end">$' . number_format($detalle['subtotal'], 0, ',', '.') . '</td>
        </tr>';
}

$html .= '
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                            <td class="text-end"><strong>$' . number_format($venta['total_venta'], 0, ',', '.') . '</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>';

echo json_encode([
    'success' => true,
    'html' => $html,
    'venta' => $venta,
    'detalles' => $detalles
]);

$db->close();