<?php
include_once '../includes/auth.php';
requireAuth();
include_once '../includes/db_connection.php';

// Verificar si se recibió el ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID no proporcionado'
    ]);
    exit;
}

$id = (int)$_GET['id'];

// Consultar el producto destacado
$stmt = $db->prepare("SELECT id_destacado, nombre_destacado, precio_destacado, imagen_destacado FROM productos_destacados WHERE id_destacado = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Producto destacado no encontrado'
    ]);
    exit;
}

$destacado = $result->fetch_assoc();

echo json_encode([
    'success' => true,
    'destacado' => $destacado
]);

$stmt->close();
$db->close();