<?php
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_categoria'])) {
    $nombre = $db->real_escape_string(trim($_POST['nombre_categoria']));
    
    // Verificar si la categoría ya existe
    $sql_check = "SELECT id_categoria FROM categorias WHERE nombre_categoria = ?";
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bind_param("s", $nombre);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe una categoría con ese nombre'
        ]);
        exit;
    }
    
    // Insertar nueva categoría
    $sql = "INSERT INTO categorias (nombre_categoria) VALUES (?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $nombre);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Categoría agregada con éxito'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al agregar la categoría'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos'
    ]);
}

