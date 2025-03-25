<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión antes de cualquier salida
session_start();

// Verificar si ya hay una sesión activa y redirigir
if (isset($_SESSION['cliente_id'])) {
    header('Location: Cliente');
    exit();
}

// Verificar si se proporcionó un token
$token = isset($_GET['token']) ? $_GET['token'] : '';
$token_valido = false;
$token_error = '';
$user_data = [];

if (!empty($token)) {
    // Incluir conexión a la base de datos
    require_once 'includes/db_connection.php';
    
    // Verificar si el token es válido y no ha expirado
    $query = "SELECT id, nombre_apellido FROM users_clientes 
              WHERE reset_token = ? AND token_expiration > NOW()";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $token_valido = true;
        $user_data = $result->fetch_assoc();
    } else {
        // Verificar si el token ha expirado
        $query = "SELECT token_expiration FROM users_clientes WHERE reset_token = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $token_data = $result->fetch_assoc();
            if (strtotime($token_data['token_expiration']) < time()) {
                $token_error = 'El enlace ha expirado. Por favor, solicite un nuevo enlace de recuperación.';
            } else {
                $token_error = 'El enlace no es válido.';
            }
        } else {
            $token_error = 'El enlace no es válido.';
        }
    }
}

// Incluir el header
include_once 'includes/inc.head.php';
?>

<main class="main">
    <?php if ($token_valido): ?>
        <?php include_once 'templates/cambiar-contraseña-main.php'; ?>
    <?php else: ?>
        <section class="section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="password-recovery">
                            <h2 class="recovery-title">Error de Recuperación</h2>
                            <p class="recovery-subtitle"><?php echo $token_error ?: 'No se proporcionó un token válido.'; ?></p>
                            <div class="text-center mt-4">
                                <a href="recuperar-contraseña.php" class="btn btn-primary">Solicitar Nuevo Enlace</a>
                            </div>
                            <div class="back-to-login mt-4">
                                <a href="login-clientes.php"><i class="bi bi-arrow-left me-1"></i> Volver a Iniciar Sesión</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php include_once 'includes/inc.footer.php'; ?>