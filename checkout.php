<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'includes/auth.php';
requireCliente();
// Incluir el header
include_once 'includes/inc.head.php';
?>

<main class="main">
    <?php include_once 'templates/checkout-main.php';?>
</main>

<?php include_once 'includes/inc.footer.php'; ?>