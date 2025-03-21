<?php
include_once '../includes/auth.php';
requireAuth();
include_once '../includes/db_connection.php';

// Verificar si se recibieron los datos necesarios
if (!isset($_POST['nombreyapellido_cliente']) || empty($_POST['nombreyapellido_cliente']) || 
    !isset($_POST['productos']) || empty($_POST['productos']) || 
    !isset($_POST['total_venta']) || empty($_POST['total_venta'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos obligatorios para registrar la venta'
    ]);
    exit;
}

// Iniciar transacción
$db->begin_transaction();

try {
    // Obtener datos del cliente
    $nombreyapellido = $db->real_escape_string($_POST['nombreyapellido_cliente']);
    $email = isset($_POST['email_cliente']) ? $db->real_escape_string($_POST['email_cliente']) : null;
    $dnicuit = isset($_POST['dnicuit_cliente']) ? $db->real_escape_string($_POST['dnicuit_cliente']) : null;
    $telefono = isset($_POST['telefono_cliente']) ? $db->real_escape_string($_POST['telefono_cliente']) : null;
    $domicilio = isset($_POST['domicilio_cliente']) ? $db->real_escape_string($_POST['domicilio_cliente']) : null;
    $notas = isset($_POST['notas']) ? $db->real_escape_string($_POST['notas']) : null;
    $total_venta = floatval($_POST['total_venta']);
    
    // Insertar venta
    $stmt = $db->prepare("INSERT INTO ventas (nombreyapellido_cliente, email_cliente, dnicuit_cliente, telefono_cliente, domicilio_cliente, total_venta, notas) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssds", $nombreyapellido, $email, $dnicuit, $telefono, $domicilio, $total_venta, $notas);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al registrar la venta: " . $stmt->error);
    }
    
    $id_venta = $db->insert_id;
    $stmt->close();
    
    // Procesar productos
    $productos = $_POST['productos'];
    
    foreach ($productos as $producto) {
        $id_producto = intval($producto['id']);
        $cantidad = intval($producto['cantidad']);
        $precio = floatval($producto['precio']);
        $subtotal = $precio * $cantidad;
        
        // Verificar stock antes de registrar
        $stmt = $db->prepare("SELECT stock FROM productos WHERE id_producto = ?");
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("El producto con ID $id_producto no existe");
        }
        
        $producto_db = $result->fetch_assoc();
        $stock_actual = $producto_db['stock'];
        
        // Si el stock es NULL, permitir la venta sin verificar
        if ($stock_actual !== null) {
            if ($stock_actual < $cantidad) {
                throw new Exception("Stock insuficiente para el producto con ID $id_producto. Stock actual: $stock_actual, Cantidad solicitada: $cantidad");
            }
            
            // Actualizar stock
            $nuevo_stock = $stock_actual - $cantidad;
            $stmt = $db->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
            $stmt->bind_param("ii", $nuevo_stock, $id_producto);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar el stock del producto con ID $id_producto: " . $stmt->error);
            }
        }
        
        // Registrar detalle de venta
        $stmt = $db->prepare("INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario, subtotal) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidi", $id_venta, $id_producto, $cantidad, $precio, $subtotal);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al registrar el detalle de la venta: " . $stmt->error);
        }
    }
    
    // Confirmar transacción
    $db->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Venta registrada correctamente',
        'id_venta' => $id_venta
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