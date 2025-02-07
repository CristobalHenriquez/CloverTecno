<!-- Modal para agregar producto -->
<div class="modal fade" id="agregarProductoModal" tabindex="-1" aria-labelledby="agregarProductoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarProductoModalLabel">Agregar Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agregarProductoForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre*</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría*</label>
                        <select class="form-select" id="categoria" name="categoria" required>
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
                        <label for="precio" class="form-label">Precio*</label>
                        <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="imagenes" class="form-label">Imágenes (máximo 3)*</label>
                        <input type="file" class="form-control" id="imagenes" name="imagenes[]" multiple accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Producto</button>
                </form>
            </div>
        </div>
    </div>
</div>

