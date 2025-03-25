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

// Función para obtener la URL base del proyecto
function getBaseUrl() {
    // Determinar si estamos en entorno local o producción
    $is_local = in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1', '::1']) || 
                strpos($_SERVER['HTTP_HOST'], 'localhost') !== false;
    
    if ($is_local) {
        // En entorno local, obtener la ruta del proyecto
        $project_path = '';
        
        // Obtener la ruta del script actual
        $current_path = $_SERVER['SCRIPT_NAME'];
        
        // Extraer la ruta del proyecto
        $path_parts = explode('/', $current_path);
        
        // Si estamos en una subcarpeta, construir la ruta del proyecto
        if (count($path_parts) > 2) {
            // Eliminar el nombre del script y 'controllers' de la ruta
            array_pop($path_parts); // Eliminar el nombre del script
            array_pop($path_parts); // Eliminar 'controllers'
            
            $project_path = implode('/', $path_parts);
        }
        
        return "http://{$_SERVER['HTTP_HOST']}{$project_path}";
    } else {
        // En producción, usar la URL del host
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $_SERVER['HTTP_HOST'];
    }
}

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar entrada
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);

    if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Por favor, ingrese un correo electrónico válido.';
    } else {
        // Verificar si el correo existe en la base de datos
        $query = "SELECT id, nombre_apellido FROM users_clientes WHERE correo = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $response['message'] = 'No se encontró una cuenta con ese correo electrónico.';
        } else {
            // El correo existe, generar token y enviar correo
            $user = $result->fetch_assoc();
            $user_id = $user['id'];
            $nombre = $user['nombre_apellido'];
            
            // Generar token único
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // El token expira en 1 hora
            
            // Guardar token en la base de datos
            $query = "UPDATE users_clientes SET reset_token = ?, token_expiration = ? WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssi", $token, $expires, $user_id);
            
            if ($stmt->execute()) {
                // Obtener la URL base del proyecto
                $base_url = getBaseUrl();
                
                // Enviar correo con el token
                $reset_link = "{$base_url}/CambiarContraseña?token=$token";
                
                // Contenido del correo
                $subject = "Recuperación de contraseña - CloverTecno";
                $message = "
                <html>
                <head>
                    <title>Recuperación de contraseña - CloverTecno</title>
                </head>
                <body>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;'>
                        <div style='background-color: #f8f9fa; padding: 20px; border-radius: 10px;'>
                            <h2 style='color: #333; text-align: center;'>Recuperación de Contraseña</h2>
                            <p>Hola <strong>$nombre</strong>,</p>
                            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
                            <p style='text-align: center;'>
                                <a href='$reset_link' style='display: inline-block; background-color: #2A564F; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Restablecer Contraseña</a>
                            </p>
                            <p>Este enlace expirará en 1 hora.</p>
                            <p>Si no solicitaste este cambio, puedes ignorar este correo y tu contraseña seguirá siendo la misma.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                // Usar PHPMailer para enviar el correo
                try {
                    // Cargar PHPMailer desde la ubicación correcta (vendor)
                    require_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
                    require_once '../vendor/phpmailer/phpmailer/src/SMTP.php';
                    require_once '../vendor/phpmailer/phpmailer/src/Exception.php';
                    
                    // Crear instancia de PHPMailer
                    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                    
                    // Configurar para usar SMTP
                    $mail->isSMTP();
                    $mail->Host = 'localhost';
                    $mail->Port = 1025; // Puerto estándar de MailHog
                    $mail->SMTPAuth = false;
                    $mail->SMTPAutoTLS = false; // Desactivar TLS automático
                    $mail->CharSet = 'UTF-8'; // Establecer codificación UTF-8
                    
                    // Configurar remitente y destinatario
                    $mail->setFrom('clovertecno@gmail.com', 'CloverTecno');
                    $mail->addAddress($correo, $nombre);
                    
                    // Configurar contenido del correo
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $message;
                    
                    // Enviar el correo
                    $mail->send();
                    
                    $response['success'] = true;
                    $response['message'] = 'Se han enviado instrucciones a su correo electrónico para restablecer su contraseña.';
                } catch (Exception $e) {
                    $response['message'] = 'Error al enviar el correo: ' . $e->getMessage();
                }
            } else {
                $response['message'] = 'Error al procesar la solicitud. Por favor, inténtelo de nuevo.';
            }
        }
    }
}

// Enviar respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;