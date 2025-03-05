<section id="reels-hero" class="text-center py-5">
    <h1 class="display-4">
        Clover Tecno <span class="color-typed">Reels</span><br>
        <span class="typed" data-typed-items="Últimas novedades tech 🔌, Innovación en tecnología 🤖, Productos y Reels actualizados 🚀"></span>
    </h1>
</section>

<section id="reels" class="container-fluid py-5">
    <div class="row justify-content-center g-4" id="reels-container">
        <?php
        $reels = [
            ["permalink" => "https://www.instagram.com/reel/xxxx/", "caption" => "Explorando la tecnología del futuro 🚀"],
            ["permalink" => "https://www.instagram.com/reel/yyyy/", "caption" => "Últimas novedades tech 🔌"],
            ["permalink" => "https://www.instagram.com/reel/zzzz/", "caption" => "Innovación sin límites 🤖"]
        ];

        foreach ($reels as $reel) {
            echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex flex-column align-items-center">';
            echo '<div class="reel-card">';
            echo '<div class="reel-frame">';
            echo '<iframe src="' . $reel['permalink'] . 'embed" allowfullscreen></iframe>';
            echo '</div>';
            echo '</div>';
            echo '<p class="reel-caption">' . $reel['caption'] . '</p>';
            echo '</div>';
        }
        ?>
    </div>
</section>

<style>
    /* HERO (NO TOCAR) */
    #reels-hero {
        background-color: var(--background-color);
        color: var(--heading-color);
        padding: 100px 20px;
    }

    #reels-hero h1 {
        font-family: var(--heading-font);
        font-size: 3rem;
        font-weight: 400;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
    }

    #reels-hero .color-typed {
        color: var(--accent-color);
    }

    #reels-hero .typed {
        font-family: var(--heading-font);
        font-size: 1.5rem;
        color: var(--default-color);
        margin-left: 10px;
        display: inline;
    }

    /* REELS */
    .reel-card {
        background-color: #111;
        border-radius: 30px;
        padding: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
        width: 100%;
        max-width: 300px;
        aspect-ratio: 9 / 16;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .reel-frame {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        border-radius: 24px;
        background-color: #000;
    }

    .reel-frame iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .reel-caption {
        margin-top: 10px;
        font-size: 0.95rem;
        color: var(--default-color);
        text-align: center;
        max-width: 300px;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .reel-card {
            max-width: 220px;
        }
        .reel-caption {
            max-width: 220px;
        }
    }

    @media (max-width: 576px) {
        .reel-card {
            max-width: 180px;
        }
        .reel-caption {
            max-width: 180px;
        }
    }
</style>

<!-- Typed.js para animación -->
<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var typed_items = document.querySelector('.typed').getAttribute('data-typed-items').split(',');
        new Typed('.typed', {
            strings: typed_items,
            typeSpeed: 50,
            backSpeed: 30,
            backDelay: 2000,
            loop: true
        });
    });
</script>
