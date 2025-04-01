<?php
include_once '../includes/auth.php';
requireAdmin();
include_once '../includes/db_connection.php';

// Verificar si se recibieron los datos necesarios
if (!isset($_POST['id_destacado']) || !isset($_POST['nombre_destacado']) || !isset($_POST['precio_destacado']) || 
    empty($_POST['id_destacado']) || empty($_POST['nombre_destacado']) || empty($_POST['precio_destacado'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos obligatorios'
    ]);
    exit;
}

// Obtener los datos del formulario
$id_destacado = (int)$_POST['id_destacado'];
$nombre_destacado = $_POST['nombre_destacado'];
$precio_destacado = (int)$_POST['precio_destacado'];

// Obtener la información actual del producto destacado
$stmt = $db->prepare("SELECT imagen_destacado FROM productos_destacados WHERE id_destacado = ?");
$stmt->bind_param("i", $id_destacado);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Producto destacado no encontrado'
    ]);
    exit;
}

$destacado = $result->fetch_assoc();
$currentImage = $destacado['imagen_destacado'];

// Verificar si se subió una nueva imagen
$dbImagePath = $currentImage; // Por defecto, mantener la imagen actual

if (isset($_FILES['imagen_destacado']) && $_FILES['imagen_destacado']['error'] === UPLOAD_ERR_OK && $_FILES['imagen_destacado']['size'] > 0) {
    // Procesar la nueva imagen
    $file = $_FILES['imagen_destacado'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    // Obtener la extensión del archivo
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Extensiones permitidas
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    // Verificar la extensión
    if (!in_array($fileExt, $allowedExtensions)) {
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de archivo no permitido. Solo se permiten imágenes (jpg, jpeg, png, gif, webp)'
        ]);
        exit;
    }

    // Verificar el tamaño (5MB máximo)
    if ($fileSize > 5000000) {
        echo json_encode([
            'success' => false,
            'message' => 'La imagen es demasiado grande. El tamaño máximo es 5MB'
        ]);
        exit;
    }

    // Crear directorio si no existe
    $uploadDir = '../uploads/destacados/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generar un nombre único para el archivo
    $newFileName = 'producto_destacado_' . date('Y-m-d') . '_' . uniqid() . '.' . $fileExt;
    $uploadPath = $uploadDir . $newFileName;

    // Mover el archivo
    if (!move_uploaded_file($fileTmpName, $uploadPath)) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al subir la imagen'
        ]);
        exit;
    }

    // Ruta relativa para guardar en la base de datos
    $dbImagePath = 'uploads/destacados/' . $newFileName;

    // Eliminar la imagen anterior si existe y no es la imagen por defecto
    if ($currentImage && file_exists('../' . $currentImage) && $dbImagePath != $currentImage) {
        @unlink('../' . $currentImage);
    }
}

// Actualizar en la base de datos
$stmt = $db->prepare("UPDATE productos_destacados SET nombre_destacado = ?, precio_destacado = ?, imagen_destacado = ? WHERE id_destacado = ?");
$stmt->bind_param("sisi", $nombre_destacado, $precio_destacado, $dbImagePath, $id_destacado);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Producto destacado actualizado correctamente'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar el producto destacado: ' . $stmt->error
    ]);
}

$stmt->close();
$db->close();