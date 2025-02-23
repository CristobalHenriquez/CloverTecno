<?php
// Obtener las 8 categorías
$sql_categorias = "SELECT id_categoria, nombre_categoria FROM categorias";
$result_categorias = $db->query($sql_categorias);

$categorias = [];
if ($result_categorias->num_rows > 0) {
    while ($row = $result_categorias->fetch_assoc()) {
        $categorias[] = $row;
    }
}

// Función para obtener una imagen aleatoria de un producto por categoría
function obtenerImagenAleatoria($db, $id_categoria)
{
    $sql = "SELECT ip.imagen_path 
            FROM imagenes_productos ip
            JOIN productos p ON ip.id_producto = p.id_producto
            WHERE p.id_categoria = ?
            ORDER BY RAND()
            LIMIT 1";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['imagen_path'];
    }

    return "assets/img/placeholder.jpg"; // Imagen por defecto si no se encuentra ninguna
}
?>

<main class="main">
    <section id="gallery" class="gallery section">
        <div class="container-fluid col-10" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4 justify-content-center">
                <?php foreach ($categorias as $categoria): ?>
                    <?php $imagen = obtenerImagenAleatoria($db, $categoria['id_categoria']); ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="gallery-card">
                            <a href="Categorias_<?php echo urlencode(str_replace(' ', '_', $categoria['nombre_categoria'])); ?>" class="card-link">
                                <img src="<?php echo htmlspecialchars($imagen); ?>" class="card-image" alt="<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>">
                                <div class="card-overlay">
                                    <h3 class="card-title"><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></h3>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<style>
.gallery-card {
    box-sizing: border-box;
    width: 100%;
    height: 254px;
    background: rgba(217, 217, 217, 0.58);
    border: 1px solid white;
    box-shadow: 12px 17px 51px rgba(0, 0, 0, 0.22);
    backdrop-filter: blur(6px);
    border-radius: 17px;
    text-align: center;
    cursor: pointer;
    transition: all 0.5s;
    position: relative;
    overflow: hidden;
}

.gallery-card:hover {
    border: 1px solid black;
    transform: scale(1.05);
}

.gallery-card:active {
    transform: scale(0.95) rotateZ(1.7deg);
}

.card-link {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none;
}

.card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 15px;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.gallery-card:hover .card-overlay {
    transform: translateY(0);
}

.card-title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .gallery-card {
        height: 200px;
    }
}
</style>

