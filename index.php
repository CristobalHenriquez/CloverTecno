<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php include_once 'includes/inc.head.php'; ?>
<?php include 'includes/db_connection.php'; ?>
  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6 text-center" data-aos="fade-up" data-aos-delay="100">
            <h2><span>En</span><span class="underlight">Clover Tecno</span>, nos apasiona la tecnología y nos dedicamos a ofrecerte<span> los mejores accesorios para tu dispositivo móvil.</span></h2>
            <p>Desde fundas protectoras hasta cargadores de alta velocidad, contamos con una amplia gama de productos diseñados para mejorar tu experiencia digital.</p>
            <a href="contact.html" class="btn-get-started">Contacto<br></a>
          </div>
        </div>
      </div>

    </section>
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
            <a href="Contacto" class="btn-get-started">Contacto</a>
          </div>
          
          <!-- Columna Derecha: Imagen de AirPods -->
          <div class="col-md-3 text-center" data-aos="fade-left">
            <img src="assets/img/airpods.png" alt="AirPods" class="img-fluid" style="max-height: 300px;">
          </div>
    
        </div>
      </div>
    </section>
    

    <!-- Gallery Section -->
    <section id="gallery" class="gallery section">
        <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

            <?php
            // Consulta para obtener las categorías únicas
            $sqlCategorias = "SELECT DISTINCT categoria FROM galerias";
            $resultCategorias = $conn->query($sqlCategorias);

            if ($resultCategorias->num_rows > 0):
                while ($categoria = $resultCategorias->fetch_assoc()):
                    $categoriaNombre = $categoria['categoria'];
            ?>
            <div class="gallery-category">
                <h2 class="text-center"><?php echo ucfirst($categoriaNombre); ?></h2>
                <div class="row gy-4 justify-content-center">
                    <?php
                    // Consulta para obtener las imágenes de la categoría
                    $sqlImagenes = "SELECT * FROM galerias WHERE categoria = '$categoriaNombre'";
                    $resultImagenes = $conn->query($sqlImagenes);
                
                    if ($resultImagenes->num_rows > 0):
                        while ($imagen = $resultImagenes->fetch_assoc()):
                    ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="gallery-item h-100">
                            <img src="<?php echo $imagen['ruta_imagen']; ?>" class="img-fluid" alt="<?php echo $imagen['descripcion']; ?>">
                            <div class="gallery-links d-flex align-items-center justify-content-center">
                                <a href="<?php echo $imagen['ruta_imagen']; ?>" title="<?php echo $imagen['descripcion']; ?>" class="glightbox preview-link">
                                    <i class="bi bi-arrows-angle-expand"></i>
                                </a>
                                <span class="price-tag">$<?php echo number_format($imagen['precio'], 2); ?></span>
                            </div>
                        </div>
                    </div><!-- End Gallery Item -->
                    <?php endwhile; endif; ?>
                </div>
            </div>
            <?php endwhile; endif; ?>
                        
        </div>
    </section><!-- /Gallery Section -->

  <?php $conn->close(); // Cierra la conexión a la base de datos ?>
    <!-- Botón flotante de WhatsApp -->
  <?php include_once 'includes/inc.footer.php'; ?>