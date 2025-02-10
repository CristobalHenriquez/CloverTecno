<?php
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_categoria']) && isset($_POST['nombre_categoria'])) {
    $id = intval($_POST['id_categoria']);
    $nombre = $db->real_escape_string(trim($_POST['nombre_categoria']));
    
    // Verificar si el nuevo nombre ya existe en otra categoría
    $sql_check = "SELECT id_categoria FROM categorias WHERE nombre_categoria = ? AND id_categoria != ?";
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bind_param("si", $nombre, $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe una categoría con ese nombre'
        ]);
        exit;
    }
    
    // Actualizar categoría
    $sql = "UPDATE categorias SET nombre_categoria = ? WHERE id_categoria = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $nombre, $id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Categoría actualizada con éxito'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar la categoría'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos'
    ]);
}

