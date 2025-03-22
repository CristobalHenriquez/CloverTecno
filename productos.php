<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db_connection.php';
include_once 'includes/inc.head.php';

// Redirigir URLs antiguas a URLs limpias usando JavaScript
if (isset($_GET['categoria']) || isset($_GET['page'])) {
    echo '<script>
    // Ocultar parámetros de URL sin cambiar la página
    if (window.history && window.history.replaceState) {
        var cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
    </script>';
}

include_once 'templates/product-list.php';
include_once 'includes/inc.footer.php';
?>