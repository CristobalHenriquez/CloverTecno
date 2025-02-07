<?php
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $db->real_escape_string($_POST['nombre']);
    $descripcion = $db->real_escape_string($_POST['descripcion']);
    $categoria = intval($_POST['categoria']);
    $precio = floatval($_POST['precio']);

    // Insertar el producto
    $sql = "INSERT INTO productos (nombre_producto, descripcion_producto, id_categoria, valor_producto) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssid", $nombre, $descripcion, $categoria, $precio);
    
    if ($stmt->execute()) {
        $id_producto = $stmt->insert_id;

        // Procesar las imágenes
        $imagenes = $_FILES['imagenes'];
        $total_imagenes = count($imagenes['name']);
        $imagenes_guardadas = 0;

        for ($i = 0; $i < $total_imagenes && $imagenes_guardadas < 3; $i++) {
            if ($imagenes['error'][$i] === UPLOAD_ERR_OK) {
                $extension = pathinfo($imagenes['name'][$i], PATHINFO_EXTENSION);
                $nombre_archivo = 'SD' . str_pad($id_producto, 6, '0', STR_PAD_LEFT) . '_' . ($imagenes_guardadas + 1) . '.' . $extension;
                $ruta_destino = dirname(dirname(__FILE__)) . '/uploads/productos/' . $nombre_archivo;
                $ruta_db = './uploads/productos/' . $nombre_archivo;

                if (move_uploaded_file($imagenes['tmp_name'][$i], $ruta_destino)) {
                    $sql_imagen = "INSERT INTO imagenes_productos (id_producto, imagen_path) VALUES (?, ?)";
                    $stmt_imagen = $db->prepare($sql_imagen);
                    $stmt_imagen->bind_param("is", $id_producto, $ruta_db);
                    $stmt_imagen->execute();
                    $imagenes_guardadas++;
                }
            }
        }

        echo json_encode(['success' => true, 'message' => 'Producto agregado con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar el producto']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

