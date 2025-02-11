<!-- Modal para gestionar categorías -->
<div class="modal fade" id="categoriasModal" tabindex="-1" aria-labelledby="categoriasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoriasModalLabel">Gestión de Categorías</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para agregar categoría -->
                <form id="agregarCategoriaForm" class="mb-4">
                    <div class="mb-3">
                        <label for="nombre_categoria" class="form-label">Nueva Categoría</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="nombre_categoria" name="nombre_categoria" required>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Agregar
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Lista de categorías existentes -->
                <h6 class="mb-3">Categorías Existentes</h6>
                <div id="listaCategorias" class="list-group">
                    <!-- Las categorías se cargarán dinámicamente aquí -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar categoría -->
<div class="modal fade" id="editarCategoriaModal" tabindex="-1" aria-labelledby="editarCategoriaModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarCategoriaModalLabel">Editar Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarCategoriaForm">
                    <input type="hidden" id="edit_categoria_id" name="id_categoria">
                    <div class="mb-3">
                        <label for="edit_nombre_categoria" class="form-label">Nombre de la Categoría</label>
                        <input type="text" class="form-control" id="edit_nombre_categoria" name="nombre_categoria" required autocomplete="off">
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para cargar las categorías
    function cargarCategorias() {
        $.get('controllers/obtener_categorias.php', function(response) {
            const categorias = JSON.parse(response);
            let html = '';

            categorias.forEach(function(categoria) {
                html += `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    ${categoria.nombre_categoria}
                    <div class="btn-group">
                        <button class="btn btn-sm btn-warning editar-categoria" 
                                data-id="${categoria.id_categoria}" 
                                data-nombre="${categoria.nombre_categoria}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger eliminar-categoria" 
                                data-id="${categoria.id_categoria}" 
                                data-nombre="${categoria.nombre_categoria}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            });

            $('#listaCategorias').html(html);
        });
    }

    // Cargar categorías cuando se abre el modal
    $('#categoriasModal').on('show.bs.modal', function() {
        cargarCategorias();
    });

    // Agregar categoría
    $('#agregarCategoriaForm').on('submit', function(e) {
        e.preventDefault();
        const nombreCategoria = $('#nombre_categoria').val();

        $.ajax({
            url: 'controllers/procesar_agregar_categoria.php',
            type: 'POST',
            data: {
                nombre_categoria: nombreCategoria
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: result.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload(); // Recargar la página después de agregar la categoría
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message
                    });
                }
            }
        });
    });

    // Modificar el manejo de modales
    $(document).ready(function() {
        // Configurar el modal de edición para que se pueda abrir sobre el modal principal
        const editarCategoriaModal = document.getElementById('editarCategoriaModal');
        editarCategoriaModal.addEventListener('show.bs.modal', function(event) {
            // Asegurarse de que el modal principal mantenga su scroll
            $('#categoriasModal').modal('hide');
        });

        // Cuando se cierra el modal de edición, mostrar el modal principal nuevamente
        editarCategoriaModal.addEventListener('hidden.bs.modal', function(event) {
            $('#categoriasModal').modal('show');
        });

        // Abrir modal de edición
        $(document).on('click', '.editar-categoria', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');

            $('#edit_categoria_id').val(id);
            $('#edit_nombre_categoria').val(nombre);

            const editModal = new bootstrap.Modal(document.getElementById('editarCategoriaModal'));
            editModal.show();
        });

        // Editar categoría
        $('#editarCategoriaForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: 'controllers/procesar_editar_categoria.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        $('#editarCategoriaModal').modal('hide');
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
                }
            });
        });
    });

    // Eliminar categoría
    $(document).on('click', '.eliminar-categoria', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');

        Swal.fire({
            title: '¿Está seguro?',
            text: `¿Desea eliminar la categoría "${nombre}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Segunda confirmación
                Swal.fire({
                    title: 'Confirmación adicional',
                    text: `Esta acción no se puede deshacer. ¿Realmente desea eliminar la categoría "${nombre}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar definitivamente',
                    cancelButtonText: 'Cancelar'
                }).then((secondResult) => {
                    if (secondResult.isConfirmed) {
                        $.ajax({
                            url: 'controllers/procesar_eliminar_categoria.php',
                            type: 'POST',
                            data: {
                                id_categoria: id
                            },
                            success: function(response) {
                                const result = JSON.parse(response);
                                if (result.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        text: result.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        location.reload(); // Recargar la página después de agregar la categoría
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: result.message
                                    });
                                }
                            }
                        });
                    }
                });
            }
        });
    });
</script>