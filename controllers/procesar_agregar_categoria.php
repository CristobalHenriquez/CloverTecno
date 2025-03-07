<?php
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_categoria = $db->real_escape_string($_POST['nombre_categoria']);
    $descripcion_categoria = $db->real_escape_string($_POST['descripcion_categoria']);

    // Validar que el nombre no esté vacío
    if (empty($nombre_categoria)) {
        echo json_encode(['success' => false, 'message' => 'El nombre de la categoría es obligatorio']);
        exit;
    }

    // Iniciar transacción
    $db->begin_transaction();

    try {
        // Insertar la categoría
        $sql = "INSERT INTO categorias (nombre_categoria, descripcion_categoria, imagen_categoria) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        
        // Valor temporal para imagen_categoria
        $imagen_path = '';
        $stmt->bind_param("sss", $nombre_categoria, $descripcion_categoria, $imagen_path);
        
        if ($stmt->execute()) {
            $id_categoria = $stmt->insert_id;

            // Procesar la imagen
            if (isset($_FILES['imagen_categoria']) && $_FILES['imagen_categoria']['error'] === UPLOAD_ERR_OK) {
                $extension = pathinfo($_FILES['imagen_categoria']['name'], PATHINFO_EXTENSION);
                $nombre_archivo = 'categoria-' . strtolower(str_replace(' ', '-', $nombre_categoria)) . '.' . $extension;
                $ruta_destino = dirname(dirname(__FILE__)) . '/uploads/categorias/';
                
                // Crear directorio si no existe
                if (!is_dir($ruta_destino)) {
                    mkdir($ruta_destino, 0755, true);
                }
                
                $ruta_destino .= $nombre_archivo;
                $ruta_db = './uploads/categorias/' . $nombre_archivo;

                // Comprimir y redimensionar la imagen
                $imagen_comprimida = comprimirImagen($_FILES['imagen_categoria']['tmp_name'], $ruta_destino, 800, 800, 80);
                
                if ($imagen_comprimida) {
                    // Actualizar la ruta de la imagen en la base de datos
                    $sql_update = "UPDATE categorias SET imagen_categoria = ? WHERE id_categoria = ?";
                    $stmt_update = $db->prepare($sql_update);
                    $stmt_update->bind_param("si", $ruta_db, $id_categoria);
                    $stmt_update->execute();
                } else {
                    throw new Exception('Error al procesar la imagen');
                }
            } else {
                throw new Exception('La imagen es obligatoria');
            }

            // Confirmar transacción
            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Categoría agregada con éxito']);
        } else {
            throw new Exception('Error al agregar la categoría');
        }
    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $db->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

/**
 * Comprime y redimensiona una imagen
 * 
 * @param string $ruta_origen Ruta del archivo temporal
 * @param string $ruta_destino Ruta donde se guardará la imagen
 * @param int $ancho_max Ancho máximo de la imagen
 * @param int $alto_max Alto máximo de la imagen
 * @param int $calidad Calidad de la imagen (0-100)
 * @return bool True si se comprimió correctamente, false en caso contrario
 */
function comprimirImagen($ruta_origen, $ruta_destino, $ancho_max = 800, $alto_max = 800, $calidad = 80) {
    // Obtener información de la imagen
    $info = getimagesize($ruta_origen);
    if ($info === false) return false;
    
    // Crear imagen según el tipo
    switch ($info[2]) {
        case IMAGETYPE_JPEG:
            $imagen = imagecreatefromjpeg($ruta_origen);
            break;
        case IMAGETYPE_PNG:
            $imagen = imagecreatefrompng($ruta_origen);
            break;
        case IMAGETYPE_GIF:
            $imagen = imagecreatefromgif($ruta_origen);
            break;
        default:
            return false;
    }
    
    if (!$imagen) return false;
    
    // Calcular nuevas dimensiones manteniendo la proporción
    $ancho_original = $info[0];
    $alto_original = $info[1];
    
    if ($ancho_original <= $ancho_max && $alto_original <= $alto_max) {
        // Si la imagen es más pequeña que las dimensiones máximas, no se redimensiona
        $ancho_nuevo = $ancho_original;
        $alto_nuevo = $alto_original;
    } else {
        if ($ancho_original > $alto_original) {
            // Imagen horizontal
            $ancho_nuevo = $ancho_max;
            $alto_nuevo = intval($alto_original * ($ancho_max / $ancho_original));
        } else {
            // Imagen vertical o cuadrada
            $alto_nuevo = $alto_max;
            $ancho_nuevo = intval($ancho_original * ($alto_max / $alto_original));
        }
    }
    
    // Crear imagen redimensionada
    $imagen_nueva = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);
    
    // Preservar transparencia para PNG
    if ($info[2] === IMAGETYPE_PNG) {
        imagealphablending($imagen_nueva, false);
        imagesavealpha($imagen_nueva, true);
        $transparent = imagecolorallocatealpha($imagen_nueva, 255, 255, 255, 127);
        imagefilledrectangle($imagen_nueva, 0, 0, $ancho_nuevo, $alto_nuevo, $transparent);
    }
    
    // Redimensionar
    imagecopyresampled($imagen_nueva, $imagen, 0, 0, 0, 0, $ancho_nuevo, $alto_nuevo, $ancho_original, $alto_original);
    
    // Guardar imagen
    $resultado = false;
    switch ($info[2]) {
        case IMAGETYPE_JPEG:
            $resultado = imagejpeg($imagen_nueva, $ruta_destino, $calidad);
            break;
        case IMAGETYPE_PNG:
            // Para PNG, la calidad va de 0 a 9 (invertida)
            $calidad_png = 9 - round(($calidad / 100) * 9);
            $resultado = imagepng($imagen_nueva, $ruta_destino, $calidad_png);
            break;
        case IMAGETYPE_GIF:
            $resultado = imagegif($imagen_nueva, $ruta_destino);
            break;
    }
    
    // Liberar memoria
    imagedestroy($imagen);
    imagedestroy($imagen_nueva);
    
    return $resultado;
}
?>