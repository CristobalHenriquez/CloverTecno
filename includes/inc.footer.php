</main>

    <footer id="footer" class="footer">
      <div class="footer-border"></div>
      <a href="https://wa.me/5493416578661?text=Hola,%20necesito%20información%20sobre%20..." class="whatsapp-float" target="_blank">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
      </a>

      <div class="container">
        <!-- Redes sociales -->
        <div class="social-links d-flex justify-content-center mb-3">
          <a href=""><i class="bi bi-twitter-x"></i></a>
          <a href=""><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-linkedin"></i></a>
        </div>

        <!-- Logo con enlace -->
        <div class="credits text-center">
          <a href="#" target="_blank">
            <img src="assets/img/Logo_Artisans_.png" alt="Logo Artisans" class="footer-logo">
          </a>
        </div>
      </div>



    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <?php
    // Obtener el nombre del archivo actual
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // Verificar si NO estamos en las páginas de administración y si no es una navegación interna
    if ($current_page !== 'admin.php' && $current_page !== 'admin-categorias.php' && !isset($_GET['no_preload'])):
    ?>
    <div id="preloader">
      <div class="line"></div>
    </div>
    <?php endif; ?>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
      const lightbox = GLightbox();
      
      // Scroll suave al cambiar de categoría o página
      document.addEventListener('DOMContentLoaded', function() {
        // Verificar si es una navegación interna
        if (window.location.search.includes('no_preload=1')) {
          // Scroll suave hacia la sección de productos
          document.getElementById('product-list').scrollIntoView({behavior: 'smooth'});
        }
      });
    </script>


    </body>

    </html>