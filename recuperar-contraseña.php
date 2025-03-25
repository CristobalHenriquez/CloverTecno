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

// Incluir el header
include_once 'includes/inc.head.php';
?>

<main class="main">
    <?php include_once 'templates/recuperar-contraseña-main.php'; ?>
</main>

<?php include_once 'includes/inc.footer.php'; ?>