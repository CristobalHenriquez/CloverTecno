<?php
// Iniciar sesión
session_start();

// Incluir conexión a la base de datos y funciones de autenticación
require_once '../includes/db_connection.php';
require_once '../includes/auth.php';

// Preparar respuesta
$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($correo) || empty($password)) {
        $response['message'] = 'Por favor, complete todos los campos.';
    } else {
        // Consultar la base de datos
        $query = "SELECT id, nombre_apellido, correo, password FROM users_clientes WHERE correo = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verificar la contraseña
            if (password_verify($password, $user['password'])) {
                // Iniciar sesión
                $_SESSION['cliente_id'] = $user['id'];
                $_SESSION['cliente_nombre'] = $user['nombre_apellido'];
                $_SESSION['cliente_correo'] = $user['correo'];
                
                // Redirigir a la página después del login o a la página principal
                $redirect = $_SESSION['redirect_after_login'] ?? 'Cliente';
                unset($_SESSION['redirect_after_login']); // Limpiar la variable de sesión
                
                // Preparar respuesta exitosa
                $response['success'] = true;
                $response['message'] = '¡Inicio de sesión exitoso!';
                $response['redirect'] = $redirect;
            } else {
                $response['message'] = 'Correo electrónico o contraseña incorrectos.';
            }
        } else {
            $response['message'] = 'Correo electrónico o contraseña incorrectos.';
        }
    }
}

// Enviar respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;