<?php
// Iniciar sesión
session_start();

// Incluir conexión a la base de datos
require_once '../includes/db_connection.php';

// Preparar respuesta
$response = [
    'success' => false,
    'message' => ''
];

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Sanitizar entradas
    $nombre_apellido = htmlspecialchars(trim($_POST['nombre_apellido'] ?? ''), ENT_QUOTES, 'UTF-8');
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($nombre_apellido) || empty($correo) || empty($password) || empty($confirm_password)) {
        $response['message'] = 'Por favor, complete todos los campos.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Por favor, ingrese un correo electrónico válido.';
    } elseif ($password !== $confirm_password) {
        $response['message'] = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $response['message'] = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        // Verificar si el correo ya está registrado
        $query = "SELECT id FROM users_clientes WHERE correo = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $response['message'] = 'Este correo electrónico ya está registrado.';
        } else {
            // Encriptar la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar el nuevo usuario
            $query = "INSERT INTO users_clientes (nombre_apellido, correo, password) VALUES (?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("sss", $nombre_apellido, $correo, $hashed_password);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Cuenta creada exitosamente. Ahora puede iniciar sesión.';
            } else {
                $response['message'] = 'Error al crear la cuenta. Por favor, inténtelo de nuevo.';
            }
        }
    }
}

// Enviar respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;