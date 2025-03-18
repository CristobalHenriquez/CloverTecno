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

// Consultar la oferta
$stmt = $db->prepare("SELECT id, dia_semana, titulo, descripcion, imagen, visible FROM ofertas WHERE id = ?");
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

echo json_encode([
    'success' => true,
    'oferta' => $oferta
]);

$stmt->close();
$db->close();