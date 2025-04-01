<?php
include_once '../includes/auth.php';
requireAdmin();
include_once '../includes/db_connection.php';

// Verificar si se recibieron los datos necesarios
if (!isset($_POST['nombre_destacado']) || !isset($_POST['precio_destacado']) || 
    empty($_POST['nombre_destacado']) || empty($_POST['precio_destacado'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos obligatorios'
    ]);
    exit;
}

// Obtener los datos del formulario
$nombre_destacado = $_POST['nombre_destacado'];
$precio_destacado = (int)$_POST['precio_destacado'];

// Verificar si se subió una imagen
if (!isset($_FILES['imagen_destacado']) || $_FILES['imagen_destacado']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'success' => false,
        'message' => 'Debe subir una imagen'
    ]);
    exit;
}

// Procesar la imagen
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

// Insertar en la base de datos
$stmt = $db->prepare("INSERT INTO productos_destacados (nombre_destacado, precio_destacado, imagen_destacado) VALUES (?, ?, ?)");
$stmt->bind_param("sis", $nombre_destacado, $precio_destacado, $dbImagePath);

if ($stmt->execute()) {
    // Preparar la respuesta JSON
    $jsonResponse = json_encode([
        'success' => true,
        'message' => 'Producto destacado agregado correctamente'
    ]);
    
    // Enviar la respuesta JSON
    echo $jsonResponse;
} else {
    // Si hay un error, eliminar la imagen subida
    @unlink($uploadPath);
    
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar el producto destacado: ' . $stmt->error
    ]);
}

$stmt->close();
$db->close();