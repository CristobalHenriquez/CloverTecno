<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_producto = intval($_POST['id']);
    
    // Primero obtenemos las rutas de las imágenes
    $sql_imagenes = "SELECT imagen_path FROM imagenes_productos WHERE id_producto = ?";
    $stmt_imagenes = $db->prepare($sql_imagenes);
    $stmt_imagenes->bind_param("i", $id_producto);
    $stmt_imagenes->execute();
    $resultado_imagenes = $stmt_imagenes->get_result();
    
    // Almacenamos las rutas de las imágenes
    $rutas_imagenes = [];
    while ($row = $resultado_imagenes->fetch_assoc()) {
        $rutas_imagenes[] = $row['imagen_path'];
    }
    
    // Iniciamos una transacción
    $db->begin_transaction();
    
    try {
        // Eliminamos primero las imágenes de la base de datos
        $sql_delete_imagenes = "DELETE FROM imagenes_productos WHERE id_producto = ?";
        $stmt_delete_imagenes = $db->prepare($sql_delete_imagenes);
        $stmt_delete_imagenes->bind_param("i", $id_producto);
        
        if (!$stmt_delete_imagenes->execute()) {
            throw new Exception("Error al eliminar las imágenes: " . $stmt_delete_imagenes->error);
        }
        
        // Luego eliminamos el producto
        $sql_delete_producto = "DELETE FROM productos WHERE id_producto = ?";
        $stmt_delete_producto = $db->prepare($sql_delete_producto);
        $stmt_delete_producto->bind_param("i", $id_producto);
        
        if (!$stmt_delete_producto->execute()) {
            throw new Exception("Error al eliminar el producto: " . $stmt_delete_producto->error);
        }
        
        // Si todo salió bien, confirmamos la transacción
        $db->commit();
        
        // Eliminamos los archivos físicos
        foreach ($rutas_imagenes as $ruta) {
            $ruta_fisica = dirname(dirname(__FILE__)) . '/' . ltrim($ruta, './');
            if (file_exists($ruta_fisica)) {
                unlink($ruta_fisica);
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Producto eliminado con éxito'
        ]);
        
    } catch (Exception $e) {
        // Si algo salió mal, revertimos la transacción
        $db->rollback();
        
        echo json_encode([
            'success' => false,
            'message' => 'Error al eliminar el producto: ' . $e->getMessage()
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Solicitud inválida'
    ]);
}
?>

