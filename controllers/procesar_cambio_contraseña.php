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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar entradas
    $token = htmlspecialchars(trim($_POST['token'] ?? ''), ENT_QUOTES, 'UTF-8');
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validar entradas
    if (empty($token) || empty($user_id) || empty($password) || empty($confirm_password)) {
        $response['message'] = 'Por favor, complete todos los campos.';
    } elseif ($password !== $confirm_password) {
        $response['message'] = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $response['message'] = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        // Verificar si el token es válido y no ha expirado
        $query = "SELECT * FROM users_clientes WHERE id = ? AND reset_token = ? AND token_expiration > NOW()";
        $stmt = $db->prepare($query);
        $stmt->bind_param("is", $user_id, $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $response['message'] = 'El enlace ha expirado o no es válido. Por favor, solicite un nuevo enlace.';
        } else {
            // Encriptar la nueva contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Actualizar la contraseña del usuario y limpiar el token
            $query = "UPDATE users_clientes SET password = ?, reset_token = NULL, token_expiration = NULL WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("si", $hashed_password, $user_id);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Su contraseña ha sido actualizada exitosamente. Ahora puede iniciar sesión con su nueva contraseña.';
            } else {
                $response['message'] = 'Error al actualizar la contraseña. Por favor, inténtelo de nuevo.';
            }
        }
    }
}

// Enviar respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;