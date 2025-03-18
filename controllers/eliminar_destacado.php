<?php
include_once '../includes/auth.php';
requireAuth();
include_once '../includes/db_connection.php';

// Verificar si se recibió el ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID no proporcionado'
    ]);
    exit;
}

$id = (int)$_POST['id'];

// Obtener la información del producto destacado para eliminar la imagen
$stmt = $db->prepare("SELECT imagen_destacado FROM productos_destacados WHERE id_destacado = ?");
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
$imagePath = $destacado['imagen_destacado'];

// Eliminar el producto destacado de la base de datos
$stmt = $db->prepare("DELETE FROM productos_destacados WHERE id_destacado = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Eliminar la imagen si existe
    if ($imagePath && file_exists('../' . $imagePath)) {
        @unlink('../' . $imagePath);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Producto destacado eliminado correctamente'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar el producto destacado: ' . $stmt->error
    ]);
}

$stmt->close();
$db->close();