<?php
require_once '../includes/db_connection.php';

$sql = "SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria";
$result = $db->query($sql);

$categorias = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
}

echo json_encode($categorias);

