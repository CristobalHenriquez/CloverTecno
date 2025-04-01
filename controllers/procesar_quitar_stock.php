<?php
include_once '../includes/auth.php';
requireAdmin();
include_once '../includes/db_connection.php';

// Verificar si se recibieron los datos necesarios
if (!isset($_POST['id_producto']) || !isset($_POST['cantidad']) || 
    empty($_POST['id_producto']) || empty($_POST['cantidad'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos obligatorios'
    ]);
    exit;
}

// Obtener los datos del formulario
$id_producto = (int)$_POST['id_producto'];
$cantidad = (int)$_POST['cantidad'];

// Validar que la cantidad sea positiva
if ($cantidad <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'La cantidad debe ser mayor a cero'
    ]);
    exit;
}

// Obtener el stock actual
$stmt = $db->prepare("SELECT stock, nombre_producto FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Producto no encontrado'
    ]);
    exit;
}

$producto = $result->fetch_assoc();
$nombre_producto = $producto['nombre_producto'];
$stock_actual = $producto['stock'] !== null ? (int)$producto['stock'] : 0;

// Validar que haya suficiente stock
if ($stock_actual < $cantidad) {
    echo json_encode([
        'success' => false,
        'message' => "No hay suficiente stock. Stock actual: $stock_actual"
    ]);
    exit;
}

$nuevo_stock = $stock_actual - $cantidad;

// Actualizar el stock
$stmt = $db->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
$stmt->bind_param("ii", $nuevo_stock, $id_producto);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => "Se quitaron $cantidad unidades del stock de \"$nombre_producto\". Stock actual: $nuevo_stock"
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar el stock: ' . $stmt->error
    ]);
}

$stmt->close();
$db->close();