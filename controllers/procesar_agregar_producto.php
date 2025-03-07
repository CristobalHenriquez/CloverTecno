<?php
require_once '../includes/db_connection.php';
require_once '../includes/image_utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $db->real_escape_string($_POST['nombre']);
    $descripcion = $db->real_escape_string($_POST['descripcion']);
    $categoria = intval($_POST['categoria']);
    $precio = floatval($_POST['precio']);

    // Iniciar transacción para asegurar la integridad de los datos
    $db->begin_transaction();

    try {
        // Insertar el producto
        $sql = "INSERT INTO productos (nombre_producto, descripcion_producto, id_categoria, valor_producto) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssid", $nombre, $descripcion, $categoria, $precio);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al insertar el producto: " . $stmt->error);
        }
        
        $id_producto = $stmt->insert_id;

        // Procesar las imágenes
        $imagenes = $_FILES['imagenes'];
        $total_imagenes = count($imagenes['name']);
        $imagenes_guardadas = 0;

        if ($total_imagenes > 3) {
            throw new Exception('No se pueden subir más de 3 imágenes');
        }

        // Asegurar que el directorio existe
        $directorio_destino = dirname(dirname(__FILE__)) . '/uploads/productos/';
        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0755, true);
        }

        for ($i = 0; $i < $total_imagenes; $i++) {
            if ($imagenes['error'][$i] === UPLOAD_ERR_OK) {
                $extension = strtolower(pathinfo($imagenes['name'][$i], PATHINFO_EXTENSION));
                $nombre_archivo = 'SD' . str_pad($id_producto, 6, '0', STR_PAD_LEFT) . '_' . ($imagenes_guardadas + 1) . '.' . $extension;
                $ruta_destino = $directorio_destino . $nombre_archivo;
                $ruta_db = './uploads/productos/' . $nombre_archivo;

                // Comprimir y guardar la imagen
                if (comprimirImagen($imagenes['tmp_name'][$i], $ruta_destino)) {
                    $sql_imagen = "INSERT INTO imagenes_productos (id_producto, imagen_path) VALUES (?, ?)";
                    $stmt_imagen = $db->prepare($sql_imagen);
                    $stmt_imagen->bind_param("is", $id_producto, $ruta_db);
                    
                    if (!$stmt_imagen->execute()) {
                        throw new Exception("Error al guardar la imagen: " . $stmt_imagen->error);
                    }
                    
                    $imagenes_guardadas++;
                } else {
                    throw new Exception("Error al procesar la imagen " . ($i + 1));
                }
            }
        }

        // Confirmar transacción
        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Producto agregado con éxito']);
    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $db->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>

