<?php
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_categoria = intval($_GET['id']);
    
    $sql = "SELECT id_categoria, nombre_categoria, descripcion_categoria, imagen_categoria 
            FROM categorias 
            WHERE id_categoria = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $categoria = $resultado->fetch_assoc();
        echo json_encode(['success' => true, 'categoria' => $categoria]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Categoría no encontrada']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);
}
?>