<?php
include_once '../includes/auth.php';
requireAuth();
include_once '../includes/db_connection.php';

// Verificar si se recibió el ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID de venta no proporcionado'
    ]);
    exit;
}

$id_venta = intval($_POST['id']);

// Iniciar transacción
$db->begin_transaction();

try {
    // Verificar que la venta exista y no esté ya cancelada
    $stmt = $db->prepare("SELECT estado FROM ventas WHERE id_venta = ?");
    $stmt->bind_param("i", $id_venta);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Venta no encontrada");
    }
    
    $venta = $result->fetch_assoc();
    
    if ($venta['estado'] === 'Cancelada') {
        throw new Exception("La venta ya está cancelada");
    }
    
    // Obtener detalles de la venta para restaurar stock
    $stmt = $db->prepare("SELECT id_producto, cantidad FROM detalle_ventas WHERE id_venta = ?");
    $stmt->bind_param("i", $id_venta);
    $stmt->execute();
    $result_detalles = $stmt->get_result();
    
    while ($detalle = $result_detalles->fetch_assoc()) {
        $id_producto = $detalle['id_producto'];
        $cantidad = $detalle['cantidad'];
        
        // Obtener stock actual
        $stmt = $db->prepare("SELECT stock FROM productos WHERE id_producto = ?");
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $result_producto = $stmt->get_result();
        
        if ($result_producto->num_rows > 0) {
            $producto = $result_producto->fetch_assoc();
            $stock_actual = $producto['stock'];
            
            // Si el stock es NULL, no actualizarlo
            if ($stock_actual !== null) {
                $nuevo_stock = $stock_actual + $cantidad;
                
                // Actualizar stock
                $stmt = $db->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
                $stmt->bind_param("ii", $nuevo_stock, $id_producto);
                
                if (!$stmt->execute()) {
                    throw new Exception("Error al restaurar el stock del producto con ID $id_producto: " . $stmt->error);
                }
            }
        }
    }
    
    // Actualizar estado de la venta a Cancelada
    $stmt = $db->prepare("UPDATE ventas SET estado = 'Cancelada' WHERE id_venta = ?");
    $stmt->bind_param("i", $id_venta);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al cancelar la venta: " . $stmt->error);
    }
    
    // Confirmar transacción
    $db->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Venta cancelada correctamente'
    ]);
    
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $db->rollback();
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$db->close();