<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si hay un usuario administrador autenticado
 * @return bool True si hay un administrador autenticado, false en caso contrario
 */
function isAdmin() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Verifica si hay un cliente autenticado
 * @return bool True si hay un cliente autenticado, false en caso contrario
 */
function isCliente() {
    return isset($_SESSION['cliente_id']) && !empty($_SESSION['cliente_id']);
}

/**
 * Requiere que el usuario sea administrador para acceder a la página
 * Si no es administrador, redirige al login de administradores
 */
function requireAdmin() {
    if (!isAdmin()) {
        // Guardar la URL actual para redirigir después del login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: InicioDeSesion');
        exit;
    }
}

/**
 * Requiere que el usuario sea cliente para acceder a la página
 * Si no es cliente, redirige al login de clientes
 */
function requireCliente() {
    if (!isCliente()) {
        // Guardar la URL actual para redirigir después del login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: Registro');
        exit;
    }
}

/**
 * Obtiene el ID del administrador actual
 * @return int|null ID del administrador o null si no hay sesión
 */
function getAdminId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Obtiene el email del administrador actual
 * @return string|null Email del administrador o null si no hay sesión
 */
function getAdminEmail() {
    return $_SESSION['user_email'] ?? null;
}

/**
 * Obtiene el ID del cliente actual
 * @return int|null ID del cliente o null si no hay sesión
 */
function getClienteId() {
    return $_SESSION['cliente_id'] ?? null;
}

/**
 * Obtiene el nombre y apellido del cliente actual
 * @return string|null Nombre y apellido del cliente o null si no hay sesión
 */
function getClienteNombre() {
    return $_SESSION['cliente_nombre'] ?? null;
}

/**
 * Obtiene el email del cliente actual
 * @return string|null Email del cliente o null si no hay sesión
 */
function getClienteEmail() {
    return $_SESSION['cliente_correo'] ?? null;
}

/**
 * Verifica si el usuario actual tiene acceso a una página específica
 * @param string $tipo Tipo de página ('admin' o 'cliente')
 * @return bool True si tiene acceso, false en caso contrario
 */
function tieneAcceso($tipo) {
    if ($tipo === 'admin') {
        return isAdmin();
    } elseif ($tipo === 'cliente') {
        return isCliente() || isAdmin(); // Los administradores pueden acceder a páginas de clientes
    }
    return false;
}

/**
 * Cierra la sesión del usuario actual (administrador o cliente)
 */
function cerrarSesion() {
    // Destruir todas las variables de sesión
    $_SESSION = array();
    
    // Si se desea destruir la sesión completamente, borrar también la cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Finalmente, destruir la sesión
    session_destroy();
}