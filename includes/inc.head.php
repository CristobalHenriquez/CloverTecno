<?php
include_once 'db_connection.php';

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
          <li><a href="Servicios" class="<?= ($current_page == 'Servicios') ? 'active' : '' ?>">Servicios</a></li>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#categoriasModal" class="<?= (strpos($current_page, 'categoria') !== false) ? 'active' : '' ?>">Categorías</a></li>
          <li><a href="Contacto" class="<?= ($current_page == 'Contacto') ? 'active' : '' ?>">Contacto</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="header-social-links">
        <a href="https://www.instagram.com/clovertecno" class="instagram"><i class="bi bi-instagram"></i></a>
      </div>

    </div>
    <div class="header-border"></div>
  </header>

  <!-- Modal de Categorías -->
  <div class="modal fade" id="categoriasModal" tabindex="-1" aria-labelledby="categoriasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="categoriasModalLabel">Categorías</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php foreach ($categorias as $categoria): ?>
              <div class="col">
                <a href="Categorias_<?= urlencode(str_replace(' ', '_', $categoria['nombre_categoria'])) ?>" class="text-decoration-none">
                  <div class="card h-100 categoria-card">
                    <div class="card-body d-flex align-items-center justify-content-center">
                      <h3 class="card-title text-center mb-0"><?= htmlspecialchars($categoria['nombre_categoria']) ?></h3>
                    </div>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .categoria-card {
      background-color: #104D43;
      transition: all 0.3s ease;
      cursor: pointer;
      min-height: 120px;
    }

    .categoria-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .categoria-card .card-title {
      color: white;
      font-size: 1.5rem;
      font-weight: 500;
    }

    .modal-content {
      background-color: #f8f9fa;
    }

    .modal-header {
      border-bottom: 2px solid #104D43;
    }

    .modal-header .modal-title {
      color: #104D43;
      font-weight: 600;
    }

    @media (max-width: 768px) {
      .categoria-card {
        min-height: 100px;
      }

      .categoria-card .card-title {
        font-size: 1.2rem;
      }
    }
  </style>

  <script>
    // Activar el enlace de Categorías si estamos en una página de categoría
    document.addEventListener('DOMContentLoaded', function() {
      const currentPage = '<?= $current_page ?>';
      if (currentPage.startsWith('categoria')) {
        document.querySelector('#navmenu a[data-bs-target="#categoriasModal"]').classList.add('active');
      }
    });
  </script>