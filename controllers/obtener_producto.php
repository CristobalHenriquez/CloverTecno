<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_producto = intval($_GET['id']);
    
    // Optimizamos la consulta para obtener solo los datos necesarios
    $sql = "SELECT p.id_producto, p.nombre_producto, p.descripcion_producto, 
                   p.valor_producto, p.id_categoria, c.nombre_categoria
            FROM productos p
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE p.id_producto = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        
        // Obtenemos las imágenes en una consulta separada
        $sql_imagenes = "SELECT imagen_path FROM imagenes_productos WHERE id_producto = ?";
        $stmt_imagenes = $db->prepare($sql_imagenes);
        $stmt_imagenes->bind_param("i", $id_producto);
        $stmt_imagenes->execute();
        $resultado_imagenes = $stmt_imagenes->get_result();
        
        $imagenes = [];
        while ($row = $resultado_imagenes->fetch_assoc()) {
            $imagenes[] = $row['imagen_path'];
        }
        
        $producto['imagenes'] = implode(',', $imagenes);
        
        echo json_encode(['success' => true, 'producto' => $producto]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);
}
?>

