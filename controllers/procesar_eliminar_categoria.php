<?php
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_categoria'])) {
    $id = intval($_POST['id_categoria']);
    
    // Verificar si hay productos usando esta categoría
    $sql_check = "SELECT COUNT(*) as total FROM productos WHERE id_categoria = ?";
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row = $result_check->fetch_assoc();
    
    if ($row['total'] > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'No se puede eliminar la categoría porque hay productos asociados a ella'
        ]);
        exit;
    }
    
    // Eliminar categoría
    $sql = "DELETE FROM categorias WHERE id_categoria = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Categoría eliminada con éxito'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al eliminar la categoría'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos'
    ]);
}

