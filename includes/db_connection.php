<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} // Iniciar la sesión al principio

// Configuración de la conexión según el entorno
if ($_SERVER['HTTP_HOST'] === 'localhost') { // O comprueba alguna otra variable de entorno
    $server = 'localhost:3307';
    $username = 'root';
    $password = '';
    $database = 'clovertecno';
} else {
    $server = '';
    $username = '';
    $password = '';
    $database = '';
}

$db = mysqli_connect($server, $username, $password, $database);

// Verificar la conexión
if (!$db) {
    die("Error de conexión: " . mysqli_connect_error()); // O maneja el error de otra forma
}

mysqli_query($db, "SET NAMES 'utf8'"); 