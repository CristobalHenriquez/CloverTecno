<?php
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_producto = intval($_GET['id']);
    
    $sql = "SELECT p.*, c.nombre_categoria, 
            GROUP_CONCAT(ip.imagen_path) as imagenes
            FROM productos p
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            LEFT JOIN imagenes_productos ip ON p.id_producto = ip.id_producto
            WHERE p.id_producto = ?
            GROUP BY p.id_producto";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        echo json_encode(['success' => true, 'producto' => $producto]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);
}

