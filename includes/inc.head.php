<?php
include_once 'db_connection.php';

// Verificar si hay una sesión activa
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Función para verificar si el usuario está logueado
function esta_logueado()
{
  return isset($_SESSION['cliente_id']);
}

// Consulta para obtener las categorías
$query = "SELECT id_categoria, nombre_categoria FROM categorias";
$result = mysqli_query($db, $query);
$categorias = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Clover Tecno</title>

  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/favicon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Cardo:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <!-- SWIPER -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    #navmenu ul {
      list-style: none;
      display: flex;
      justify-content: center;
    }

    #navmenu li {
      margin-right: 30px;
    }

    #navmenu a {
      position: relative;
      display: block;
      padding: 5px;
      color: var(--text-color);
      text-decoration: none;
    }

    #navmenu a::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background: linear-gradient(to right, #104D43, #156658, #006658);
      z-index: 1;
      transform: scaleX(0);
      transform-origin: left;
      transition: transform 0.5s ease-in-out;
    }

    #navmenu a:hover::before {
      transform: scaleX(1);
    }

    #navmenu[data-animation="to-left"] a::before {
      transform-origin: right;
    }

    #navmenu[data-animation="center"] a::before {
      transform-origin: center;
    }

    #navmenu[data-animation="bonus"] a::before {
      transform-origin: right;
    }

    #navmenu[data-animation="bonus"] a:hover::before {
      transform-origin: left;
      transform: scaleX(1);
      transition-timing-function: cubic-bezier(0.2, 1, 0.82, 0.94);
    }

    #header {
      position: relative;
      overflow: hidden;
      /* Para ocultar la parte de la línea que se sale durante la animación */
    }

    .header-border {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 3px;
      background: linear-gradient(to right, #0E443B, #104D43, #187766);
      background-size: 200% 100%;
      animation: borderAnimation 3s linear infinite;
    }

    .footer-border {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 3px;
      background: linear-gradient(to right, #0E443B, #104D43, #187766);
      background-size: 200% 100%;
      animation: borderAnimation 3s linear infinite;
    }

    @keyframes borderAnimation {
      0% {
        background-position: 100% 0;
      }

      100% {
        background-position: -100% 0;
      }
    }

    /* Estilos para el botón de cerrar sesión */
    .logout-btn {
      display: flex;
      align-items: center;
      gap: 5px;
      padding: 6px 12px;
      background: linear-gradient(135deg, #104D43, #187766);
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 14px;
    }

    .logout-btn:hover {
      background: linear-gradient(135deg, #0E443B, #156658);
      transform: translateY(-2px);
    }

    .logout-btn i {
      font-size: 16px;
    }

    .user-actions {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    @media (max-width: 768px) {
      .logout-btn span {
        display: none;
      }

      .logout-btn {
        padding: 6px;
      }
    }
  </style>
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">
      <a href="Inicio" class="logo d-flex align-items-center me-auto me-xl-0">
        <img src="assets/img/logo.png" alt="">
      </a>

      <nav id="navmenu" class="navmenu" data-animation="bonus">
        <?php
        $current_page = basename($_SERVER['REQUEST_URI'], ".php");
        ?>
        <ul>
          <li><a href="Inicio" class="<?= ($current_page == 'Inicio' || $current_page == '') ? 'active' : '' ?>">Inicio</a></li>
          <li><a href="Nosotros" class="<?= ($current_page == 'Nosotros') ? 'active' : '' ?>">Nosotros</a></li>
          <li><a href="Productos" class="<?= ($current_page == 'Productos') ? 'active' : '' ?>">Productos</a></li>
          <li><a href="Contacto" class="<?= ($current_page == 'Contacto') ? 'active' : '' ?>">Contacto</a></li>
          <?php if (esta_logueado()): ?>
            <li><a href="Cliente" class="<?= ($current_page == 'Cliente') ? 'active' : '' ?>">Mi Cuenta</a></li>
          <?php endif; ?>
          <?php if (!esta_logueado()): ?>
            <li><a href="Registro" class="<?= ($current_page == 'Registro') ? 'active' : '' ?>">Iniciar Sesión</a></li>
          <?php endif; ?>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="user-actions">
        <div class="header-social-links">
          <a href="https://www.instagram.com/clovertecno" class="instagram"><i class="bi bi-instagram"></i></a>
        </div>
        <?php if (esta_logueado()): ?>
          <button id="logout-btn" class="logout-btn">
            <i class="bi bi-box-arrow-right"></i>
            <span>Cerrar Sesión</span>
          </button>
        <?php endif; ?>
      </div>

    </div>
    <div class="header-border"></div>
  </header>

  <!-- INCLUYO CARRITO -->
  <?php include_once 'includes/carrito.php';?>

  <!-- Script para cerrar sesión -->
  <?php if (esta_logueado()): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const logoutBtn = document.getElementById('logout-btn');

        if (logoutBtn) {
          logoutBtn.addEventListener('click', function() {
            Swal.fire({
              title: '¿Cerrar sesión?',
              text: '¿Estás seguro que deseas cerrar tu sesión?',
              icon: 'question',
              showCancelButton: true,
              confirmButtonColor: '#104D43',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Sí, cerrar sesión',
              cancelButtonText: 'Cancelar'
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = 'logout.php';
              }
            });
          });
        }
      });
    </script>
  <?php endif; ?>