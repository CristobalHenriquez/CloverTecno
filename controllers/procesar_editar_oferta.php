<?php
include_once '../includes/auth.php';
requireAdmin();
include_once '../includes/db_connection.php';

// Verificar si se recibieron los datos necesarios
if (!isset($_POST['id']) || !isset($_POST['dia_semana']) || !isset($_POST['titulo']) || 
    empty($_POST['id']) || empty($_POST['dia_semana']) || empty($_POST['titulo'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos obligatorios'
    ]);
    exit;
}

// Obtener los datos del formulario
$id = (int)$_POST['id'];
$dia_semana = $_POST['dia_semana'];
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'] ?? '';
$visible = isset($_POST['visible']) ? (int)$_POST['visible'] : 1;

// Obtener la información actual de la oferta
$stmt = $db->prepare("SELECT imagen FROM ofertas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Oferta no encontrada'
    ]);
    exit;
}

$oferta = $result->fetch_assoc();
$currentImage = $oferta['imagen'];

// Verificar si se subió una nueva imagen
$dbImagePath = $currentImage; // Por defecto, mantener la imagen actual

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK && $_FILES['imagen']['size'] > 0) {
    // Procesar la nueva imagen
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
    if (file_exists($uploadPath) && $uploadPath != '../' . $currentImage) {
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

    // Eliminar la imagen anterior si existe y no es la imagen por defecto
    if ($currentImage && file_exists('../' . $currentImage) && $dbImagePath != $currentImage) {
        @unlink('../' . $currentImage);
    }
}

// Actualizar en la base de datos
$stmt = $db->prepare("UPDATE ofertas SET dia_semana = ?, titulo = ?, descripcion = ?, imagen = ?, visible = ? WHERE id = ?");
$stmt->bind_param("ssssii", $dia_semana, $titulo, $descripcion, $dbImagePath, $visible, $id);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Oferta actualizada correctamente'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar la oferta: ' . $stmt->error
    ]);
}

$stmt->close();
$db->close();