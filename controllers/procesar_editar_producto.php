<?php
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = intval($_POST['id_producto']);
    $nombre = $db->real_escape_string($_POST['nombre']);
    $descripcion = $db->real_escape_string($_POST['descripcion']);
    $categoria = intval($_POST['categoria']);
    $precio = floatval($_POST['precio']);

    // Actualizar el producto
    $sql = "UPDATE productos SET nombre_producto = ?, descripcion_producto = ?, id_categoria = ?, valor_producto = ? WHERE id_producto = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssidi", $nombre, $descripcion, $categoria, $precio, $id_producto);
    
    if ($stmt->execute()) {
        // Procesar imágenes eliminadas
        if (isset($_POST['eliminar_imagen'])) {
            foreach ($_POST['eliminar_imagen'] as $imagen_eliminar) {
                $sql_eliminar = "DELETE FROM imagenes_productos WHERE id_producto = ? AND imagen_path = ?";
                $stmt_eliminar = $db->prepare($sql_eliminar);
                $stmt_eliminar->bind_param("is", $id_producto, $imagen_eliminar);
                $stmt_eliminar->execute();
                
                // Eliminar el archivo físico
                $ruta_fisica = dirname(dirname(__FILE__)) . $imagen_eliminar;
                if (file_exists($ruta_fisica)) {
                    unlink($ruta_fisica);
                }
            }
        }

        // Procesar nuevas imágenes
        if (isset($_FILES['nuevas_imagenes']) && $_FILES['nuevas_imagenes']['name'][0] !== '') {
            $imagenes = $_FILES['nuevas_imagenes'];
            $total_imagenes = count($imagenes['name']);
            $imagenes_actuales = $db->query("SELECT COUNT(*) as total FROM imagenes_productos WHERE id_producto = $id_producto")->fetch_assoc()['total'];
            $imagenes_disponibles = 3 - $imagenes_actuales;

            for ($i = 0; $i < $total_imagenes && $imagenes_disponibles > 0; $i++) {
                if ($imagenes['error'][$i] === UPLOAD_ERR_OK) {
                    $extension = pathinfo($imagenes['name'][$i], PATHINFO_EXTENSION);
                    $nombre_archivo = 'SD' . str_pad($id_producto, 6, '0', STR_PAD_LEFT) . '_' . (time() + $i) . '.' . $extension;
                    $ruta_destino = dirname(dirname(__FILE__)) . '/uploads/productos/' . $nombre_archivo;
                    $ruta_db = './uploads/productos/' . $nombre_archivo;

                    if (move_uploaded_file($imagenes['tmp_name'][$i], $ruta_destino)) {
                        $sql_imagen = "INSERT INTO imagenes_productos (id_producto, imagen_path) VALUES (?, ?)";
                        $stmt_imagen = $db->prepare($sql_imagen);
                        $stmt_imagen->bind_param("is", $id_producto, $ruta_db);
                        $stmt_imagen->execute();
                        $imagenes_disponibles--;
                    }
                }
            }
        }

        echo json_encode(['success' => true, 'message' => 'Producto actualizado con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

