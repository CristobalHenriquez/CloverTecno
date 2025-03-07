<?php
require_once '../includes/db_connection.php';
require_once '../includes/image_utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = intval($_POST['id_producto']);
    $nombre = $db->real_escape_string($_POST['nombre']);
    $descripcion = $db->real_escape_string($_POST['descripcion']);
    $categoria = intval($_POST['categoria']);
    $precio = floatval($_POST['precio']);

    // Iniciar transacción
    $db->begin_transaction();

    try {
        // Actualizar el producto
        $sql = "UPDATE productos SET nombre_producto = ?, descripcion_producto = ?, id_categoria = ?, valor_producto = ? WHERE id_producto = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssidi", $nombre, $descripcion, $categoria, $precio, $id_producto);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el producto: " . $stmt->error);
        }

        // Procesar imágenes eliminadas
        if (isset($_POST['eliminar_imagen'])) {
            $imagenes_eliminar = json_decode($_POST['eliminar_imagen'], true);
            foreach ($imagenes_eliminar as $imagen_eliminar) {
                // Eliminar de la base de datos
                $sql_eliminar = "DELETE FROM imagenes_productos WHERE id_producto = ? AND imagen_path = ?";
                $stmt_eliminar = $db->prepare($sql_eliminar);
                $stmt_eliminar->bind_param("is", $id_producto, $imagen_eliminar);
                
                if (!$stmt_eliminar->execute()) {
                    throw new Exception("Error al eliminar la imagen de la base de datos: " . $stmt_eliminar->error);
                }
                
                // Eliminar el archivo físico
                $ruta_fisica = dirname(dirname(__FILE__)) . '/' . ltrim($imagen_eliminar, './');
                if (file_exists($ruta_fisica)) {
                    unlink($ruta_fisica);
                }
            }
        }

        // Verificar cuántas imágenes tiene actualmente el producto
        $sql_contar = "SELECT COUNT(*) as total FROM imagenes_productos WHERE id_producto = ?";
        $stmt_contar = $db->prepare($sql_contar);
        $stmt_contar->bind_param("i", $id_producto);
        $stmt_contar->execute();
        $imagenes_actuales = $stmt_contar->get_result()->fetch_assoc()['total'];

        // Procesar nuevas imágenes
        if (isset($_FILES['nuevas_imagenes']) && $_FILES['nuevas_imagenes']['name'][0] !== '') {
            $imagenes = $_FILES['nuevas_imagenes'];
            $total_nuevas_imagenes = count($imagenes['name']);
            
            if ($imagenes_actuales + $total_nuevas_imagenes > 3) {
                throw new Exception('No se pueden tener más de 3 imágenes en total');
            }

            // Asegurar que el directorio existe
            $directorio_destino = dirname(dirname(__FILE__)) . '/uploads/productos/';
            if (!file_exists($directorio_destino)) {
                mkdir($directorio_destino, 0755, true);
            }

            for ($i = 0; $i < $total_nuevas_imagenes; $i++) {
                if ($imagenes['error'][$i] === UPLOAD_ERR_OK) {
                    $extension = strtolower(pathinfo($imagenes['name'][$i], PATHINFO_EXTENSION));
                    $nombre_archivo = 'SD' . str_pad($id_producto, 6, '0', STR_PAD_LEFT) . '_' . (time() + $i) . '.' . $extension;
                    $ruta_destino = $directorio_destino . $nombre_archivo;
                    $ruta_db = './uploads/productos/' . $nombre_archivo;

                    // Comprimir y guardar la imagen
                    if (comprimirImagen($imagenes['tmp_name'][$i], $ruta_destino)) {
                        $sql_imagen = "INSERT INTO imagenes_productos (id_producto, imagen_path) VALUES (?, ?)";
                        $stmt_imagen = $db->prepare($sql_imagen);
                        $stmt_imagen->bind_param("is", $id_producto, $ruta_db);
                        
                        if (!$stmt_imagen->execute()) {
                            throw new Exception("Error al guardar la nueva imagen: " . $stmt_imagen->error);
                        }
                    } else {
                        throw new Exception("Error al procesar la nueva imagen " . ($i + 1));
                    }
                }
            }
        }

        // Confirmar transacción
        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Producto actualizado con éxito']);
    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $db->rollback();
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>

