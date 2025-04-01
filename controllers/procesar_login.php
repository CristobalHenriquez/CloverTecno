<?php
// Iniciar sesión
session_start();

// Incluir la conexión a la base de datos y funciones de autenticación
require_once '../includes/db_connection.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, complete todos los campos'
        ]);
        exit;
    }
    
    $sql = "SELECT id, email, password FROM users WHERE email = ? AND password = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        
        // Redirigir a la página después del login o a la página principal
        $redirect = $_SESSION['redirect_after_login'] ?? 'Administrador';
        unset($_SESSION['redirect_after_login']); // Limpiar la variable de sesión
        
        echo json_encode([
            'success' => true,
            'message' => '¡Inicio de sesión exitoso!',
            'redirect' => $redirect
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Credenciales incorrectas'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}