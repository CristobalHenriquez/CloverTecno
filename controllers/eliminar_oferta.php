<?php
include_once '../includes/auth.php';
requireAdmin();
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

// Obtener la información de la oferta para eliminar la imagen
$stmt = $db->prepare("SELECT imagen FROM ofertas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Oferta no encontrada'
    ]);
    exit;
}

$oferta = $result->fetch_assoc();
$imagePath = $oferta['imagen'];

// Eliminar la oferta de la base de datos
$stmt = $db->prepare("DELETE FROM ofertas WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Eliminar la imagen si existe
    if ($imagePath && file_exists('../' . $imagePath)) {
        @unlink('../' . $imagePath);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Oferta eliminada correctamente'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar la oferta: ' . $stmt->error
    ]);
}

$stmt->close();
$db->close();