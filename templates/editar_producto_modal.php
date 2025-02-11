<!-- Modal para editar producto -->
<div class="modal fade" id="editarProductoModal" tabindex="-1" aria-labelledby="editarProductoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarProductoModalLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarProductoForm" enctype="multipart/form-data">
                    <input type="hidden" name="id_producto" id="edit_id_producto">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="edit_categoria" name="categoria" required>
                            <?php
                            $sql_categorias = "SELECT id_categoria, nombre_categoria FROM categorias";
                            $result_categorias = $db->query($sql_categorias);
                            while ($categoria = $result_categorias->fetch_assoc()) {
                                echo "<option value='" . $categoria['id_categoria'] . "'>" . htmlspecialchars($categoria['nombre_categoria']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="edit_precio" name="precio" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imágenes actuales</label>
                        <div id="imagenes_actuales" class="d-flex flex-wrap gap-2"></div>
                    </div>
                    <div class="mb-3">
                        <label for="nuevas_imagenes" class="form-label">Agregar nuevas imágenes (máximo 3 en total)</label>
                        <input type="file" class="form-control" id="nuevas_imagenes" name="nuevas_imagenes[]" multiple accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Validar número de imágenes nuevas
document.getElementById('nuevas_imagenes').addEventListener('change', function(e) {
    const imagenesActuales = document.querySelectorAll('#imagenes_actuales input[type="checkbox"]:not(:checked)').length;
    const nuevasImagenes = this.files.length;
    const totalImagenes = imagenesActuales + nuevasImagenes;

    if (totalImagenes > 3) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Solo puedes tener un máximo de 3 imágenes en total. Actualmente tienes ${imagenesActuales} imagen(es).`
        });
        this.value = ''; // Limpiar la selección
    }
});

// Manejar el envío del formulario de editar producto
$('#editarProductoForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    
    // Validar número total de imágenes
    const imagenesActuales = document.querySelectorAll('#imagenes_actuales input[type="checkbox"]:not(:checked)').length;
    const nuevasImagenes = $('#nuevas_imagenes')[0].files.length;
    const totalImagenes = imagenesActuales + nuevasImagenes;

    if (totalImagenes === 0 || totalImagenes > 3) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debes tener entre 1 y 3 imágenes en total'
        });
        return;
    }

    // Agregar las rutas de las imágenes a eliminar
    const imagenesEliminar = [];
    document.querySelectorAll('#imagenes_actuales input[type="checkbox"]:checked').forEach(checkbox => {
        imagenesEliminar.push(checkbox.value);
    });
    formData.append('eliminar_imagen', JSON.stringify(imagenesEliminar));

    $.ajax({
        url: 'controllers/procesar_editar_producto.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            var result = JSON.parse(response);
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: result.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message
                });
            }
            $('#editarProductoModal').modal('hide');
        },
        cache: false,
        contentType: false,
        processData: false
    });
});
</script>

