<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php include_once 'includes/inc.head.php'; ?>

  <main class="main">

    <!-- Hero Section -->
    <!--<section id="hero" class="hero section">

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6 text-center" data-aos="fade-up" data-aos-delay="100">
            <h2><span>En</span><span class="underlight">Clover Tecno</span>, nos apasiona la tecnología y nos dedicamos a ofrecerte<span> los mejores accesorios para tu dispositivo móvil.</span></h2>
            <p>Desde fundas protectoras hasta cargadores de alta velocidad, contamos con una amplia gama de productos diseñados para mejorar tu experiencia digital.</p>
            <a href="contact.html" class="btn-get-started">Contacto<br></a>
          </div>
        </div>
      </div>

    </section>-->
    <section id="hero" class="hero section">
      <div class="container">
        <div class="row align-items-center justify-content-center">
          
          <!-- Columna Izquierda: Imagen del iPhone -->
          <div class="col-md-3 text-center" data-aos="fade-right">
            <img src="assets/img/iphone.png" alt="iPhone" class="img-fluid" style="max-height: 300px;">
          </div>
          
          <!-- Columna Central: Texto principal -->
          <div class="col-md-6 text-center" data-aos="fade-up" data-aos-delay="100">
            <h2>
              <span>En</span><span class="underlight">Clover Tecno</span>, nos apasiona la tecnología y nos dedicamos a ofrecerte
              <span> los mejores accesorios para tu dispositivo móvil.</span>
            </h2>
            <p>
              Desde fundas protectoras hasta cargadores de alta velocidad, contamos con una amplia gama de productos diseñados para mejorar tu experiencia digital.
            </p>
            <!--<a href="Contacto" class="btn-get-started">Contacto</a>-->
          </div>
          
          <!-- Columna Derecha: Imagen de AirPods -->
          <div class="col-md-3 text-center" data-aos="fade-left">
            <img src="assets/img/airpods.png" alt="AirPods" class="img-fluid" style="max-height: 300px;">
          </div>
    
        </div>
      </div>
    </section>
    <?php include_once 'reels.php'; ?>
    <?php //include_once 'includes/gallery.php'; ?>
    <?php include_once 'templates/categorias-swiper.php';?>
    <?php include_once 'includes/testimonials.php'; ?>
    <!-- Gallery Section -->
    
    <!-- Botón flotante de WhatsApp -->
  <?php include_once 'includes/inc.footer.php'; ?>