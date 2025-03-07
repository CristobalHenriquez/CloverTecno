<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_categoria = intval($_POST['id']);
    
    // Verificar si hay productos asociados a esta categoría
    $sql_productos = "SELECT COUNT(*) as total FROM productos WHERE id_categoria = ?";
    $stmt_productos = $db->prepare($sql_productos);
    $stmt_productos->bind_param("i", $id_categoria);
    $stmt_productos->execute();
    $resultado_productos = $stmt_productos->get_result();
    $total_productos = $resultado_productos->fetch_assoc()['total'];
    
    if ($total_productos > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'No se puede eliminar la categoría porque hay productos asociados a ella. Primero debe reasignar o eliminar esos productos.'
        ]);
        exit;
    }
    
    // Obtener la ruta de la imagen
    $sql_imagen = "SELECT imagen_categoria FROM categorias WHERE id_categoria = ?";
    $stmt_imagen = $db->prepare($sql_imagen);
    $stmt_imagen->bind_param("i", $id_categoria);
    $stmt_imagen->execute();
    $resultado_imagen = $stmt_imagen->get_result();
    
    if ($resultado_imagen->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'La categoría no existe'
        ]);
        exit;
    }
    
    $imagen_path = $resultado_imagen->fetch_assoc()['imagen_categoria'];
    
    // Iniciar transacción
    $db->begin_transaction();
    
    try {
        // Eliminar la categoría
        $sql_delete = "DELETE FROM categorias WHERE id_categoria = ?";
        $stmt_delete = $db->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id_categoria);
        $stmt_delete->execute();
        
        // Confirmar transacción
        $db->commit();
        
        // Eliminar la imagen física
        if (!empty($imagen_path)) {
            $ruta_fisica = dirname(dirname(__FILE__)) . '/' . ltrim($imagen_path, './');
            if (file_exists($ruta_fisica)) {
                unlink($ruta_fisica);
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Categoría eliminada con éxito'
        ]);
        
    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $db->rollback();
        
        echo json_encode([
            'success' => false,
            'message' => 'Error al eliminar la categoría: ' . $e->getMessage()
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Solicitud inválida'
    ]);
}
?>