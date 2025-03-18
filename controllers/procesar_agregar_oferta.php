<?php
include_once '../includes/auth.php';
requireAuth();
include_once '../includes/db_connection.php';

// Verificar si se recibieron los datos necesarios
if (!isset($_POST['dia_semana']) || !isset($_POST['titulo']) || empty($_POST['dia_semana']) || empty($_POST['titulo'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos obligatorios'
    ]);
    exit;
}

// Obtener los datos del formulario
$dia_semana = $_POST['dia_semana'];
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'] ?? '';
$visible = isset($_POST['visible']) ? (int)$_POST['visible'] : 1;

// Verificar si se subió una imagen
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'success' => false,
        'message' => 'Debe subir una imagen'
    ]);
    exit;
}

// Procesar la imagen
$file = $_FILES['imagen'];
$fileName = $file['name'];
$fileTmpName = $file['tmp_name'];
$fileSize = $file['size'];
$fileError = $file['error'];
$fileType = $file['type'];

// Obtener la extensión del archivo
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

// Extensiones permitidas
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

// Verificar la extensión
if (!in_array($fileExt, $allowedExtensions)) {
    echo json_encode([
        'success' => false,
        'message' => 'Tipo de archivo no permitido. Solo se permiten imágenes (jpg, jpeg, png, gif)'
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
$uploadDir = '../uploads/ofertas/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generar un nombre único para el archivo
$newFileName = $dia_semana . '_' . date('Y-m-d') . '.' . $fileExt;
$uploadPath = $uploadDir . $newFileName;

// Si ya existe un archivo con ese nombre, agregar un identificador único
if (file_exists($uploadPath)) {
    $newFileName = $dia_semana . '_' . date('Y-m-d') . '_' . uniqid() . '.' . $fileExt;
    $uploadPath = $uploadDir . $newFileName;
}

// Mover el archivo
if (!move_uploaded_file($fileTmpName, $uploadPath)) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al subir la imagen'
    ]);
    exit;
}

// Ruta relativa para guardar en la base de datos
$dbImagePath = 'uploads/ofertas/' . $newFileName;

// Insertar en la base de datos (sin el campo orden)
$stmt = $db->prepare("INSERT INTO ofertas (dia_semana, titulo, descripcion, imagen, visible) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $dia_semana, $titulo, $descripcion, $dbImagePath, $visible);

if ($stmt->execute()) {
    // Preparar la respuesta JSON
    $jsonResponse = json_encode([
        'success' => true,
        'message' => 'Oferta agregada correctamente'
    ]);
    
    // Enviar la respuesta JSON
    echo $jsonResponse;
} else {
    // Si hay un error, eliminar la imagen subida
    @unlink($uploadPath);
    
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar la oferta: ' . $stmt->error
    ]);
}

$stmt->close();
$db->close();