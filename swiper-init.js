// Import Swiper JS
import Swiper from "swiper"

// Inicialización personalizada de Swiper para asegurar que funcione en móvil
document.addEventListener("DOMContentLoaded", () => {
  // Inicializar todos los sliders con la clase init-swiper
  document.querySelectorAll(".init-swiper").forEach((swiperContainer) => {
    const configElement = swiperContainer.querySelector(".swiper-config")
    let config = {}

    if (configElement) {
      try {
        config = JSON.parse(configElement.textContent)
      } catch (e) {
        console.error("Error parsing Swiper config:", e)
      }
    }

    // Agregar paginación si existe el elemento
    if (swiperContainer.querySelector(".swiper-pagination")) {
      config.pagination = {
        el: swiperContainer.querySelector(".swiper-pagination"),
        clickable: true,
      }
    }

    // Asegurar que siempre haya al menos 2 slides visibles en móvil
    if (!config.breakpoints) {
      config.breakpoints = {}
    }

    if (!config.breakpoints["320"]) {
      config.breakpoints["320"] = {
        slidesPerView: 2,
        spaceBetween: 10,
      }
    }

    // Inicializar Swiper con la configuración
    new Swiper(swiperContainer, config)
  })
})

