<?php
require_once '../includes/db_connection.php';

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
        
        echo json_encode([
            'success' => true,
            'message' => '¡Inicio de sesión exitoso!'
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

