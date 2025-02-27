<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} // Iniciar la sesión al principio

// Detectar entorno (local o producción)
$isLocal = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']) || strpos($_SERVER['HTTP_HOST'], 'local') !== false;

if ($isLocal) {
    // Configuración para entorno local
    $server = 'localhost:3307';
    $username = 'root';
    $password = '';
    $database = 'clovertecno';
} else {
    // Configuración para entorno de producción
    $server = 'localhost'; // Ajusta según sea necesario
    $username = 'u978865485_clover';
    $password = 'Ramcc202323@';
    $database = 'u978865485_clover';
}

// Crear conexión con MySQL
$db = new mysqli($server, $username, $password, $database);

// Verificar la conexión
if ($db->connect_error) {
    die("❌ Error de conexión: " . $db->connect_error);
} else {
}

// Configurar conjunto de caracteres para evitar problemas con acentos y caracteres especiales
$db->set_charset("utf8mb4");
?>
