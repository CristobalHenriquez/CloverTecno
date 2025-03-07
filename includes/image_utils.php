<?php
/**
 * Comprime y redimensiona una imagen manteniendo su proporción
 * 
 * @param string $ruta_origen Ruta del archivo temporal
 * @param string $ruta_destino Ruta donde se guardará la imagen comprimida
 * @param int $ancho_maximo Ancho máximo de la imagen (por defecto 800px)
 * @param int $alto_maximo Alto máximo de la imagen (por defecto 800px)
 * @param int $calidad Calidad de compresión (0-100, por defecto 80)
 * @return bool True si la compresión fue exitosa, False en caso contrario
 */
function comprimirImagen($ruta_origen, $ruta_destino, $ancho_maximo = 800, $alto_maximo = 800, $calidad = 80) {
    // Obtener información de la imagen
    $info = getimagesize($ruta_origen);
    if ($info === false) return false;
    
    list($ancho, $alto, $tipo) = $info;
    
    // Si la imagen ya es más pequeña que las dimensiones máximas, solo comprimimos
    if ($ancho <= $ancho_maximo && $alto <= $alto_maximo) {
        $nuevo_ancho = $ancho;
        $nuevo_alto = $alto;
    } else {
        // Calcular nuevas dimensiones manteniendo la proporción
        if ($ancho > $alto) {
            $nuevo_ancho = $ancho_maximo;
            $nuevo_alto = intval($alto * $ancho_maximo / $ancho);
        } else {
            $nuevo_alto = $alto_maximo;
            $nuevo_ancho = intval($ancho * $alto_maximo / $alto);
        }
    }
    
    // Crear imagen desde el origen según su tipo
    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $imagen = imagecreatefromjpeg($ruta_origen);
            break;
        case IMAGETYPE_PNG:
            $imagen = imagecreatefrompng($ruta_origen);
            break;
        case IMAGETYPE_GIF:
            $imagen = imagecreatefromgif($ruta_origen);
            break;
        case IMAGETYPE_WEBP:
            $imagen = imagecreatefromwebp($ruta_origen);
            break;
        default:
            return false;
    }
    
    if (!$imagen) return false;
    
    // Crear lienzo para la nueva imagen
    $nueva_imagen = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
    
    // Preservar transparencia para PNG
    if ($tipo === IMAGETYPE_PNG) {
        imagealphablending($nueva_imagen, false);
        imagesavealpha($nueva_imagen, true);
        $transparent = imagecolorallocatealpha($nueva_imagen, 255, 255, 255, 127);
        imagefilledrectangle($nueva_imagen, 0, 0, $nuevo_ancho, $nuevo_alto, $transparent);
    }
    
    // Redimensionar
    imagecopyresampled(
        $nueva_imagen, $imagen,
        0, 0, 0, 0,
        $nuevo_ancho, $nuevo_alto, $ancho, $alto
    );
    
    // Guardar la imagen según su tipo
    $resultado = false;
    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $resultado = imagejpeg($nueva_imagen, $ruta_destino, $calidad);
            break;
        case IMAGETYPE_PNG:
            // Para PNG, la calidad va de 0 a 9 (invertida)
            $png_calidad = 9 - round(($calidad / 100) * 9);
            $resultado = imagepng($nueva_imagen, $ruta_destino, $png_calidad);
            break;
        case IMAGETYPE_GIF:
            $resultado = imagegif($nueva_imagen, $ruta_destino);
            break;
        case IMAGETYPE_WEBP:
            $resultado = imagewebp($nueva_imagen, $ruta_destino, $calidad);
            break;
    }
    
    // Liberar memoria
    imagedestroy($imagen);
    imagedestroy($nueva_imagen);
    
    return $resultado;
}
?>

