/**
 * Archivo principal de JavaScript para el sistema de ventas
 * Contiene toda la lógica para el proceso de venta en 3 pasos
 */

// Variables globales para el carrito y datos del cliente
let carrito = [];
let total = 0;
let totalConDescuento = 0;
let descuentoPorcentaje = 0;
let datosCliente = {};
let pasoActual = 1;
let ventasTable = null;

// Esperar a que el documento esté listo
$(document).ready(function() {
    console.log("Documento listo - Inicializando sistema de ventas");
    
    // Inicializar eventos de modales primero
    inicializarEventosModales();
    
    // Luego inicializar DataTable (con manejo de errores)
    try {
        inicializarDataTable();
    } catch (error) {
        console.error("Error al inicializar DataTable:", error);
        // No detener la ejecución si falla DataTable
    }
    
    // Inicializar el resto de eventos
    inicializarEventosRestantes();
});

/**
 * Inicializa los eventos relacionados con los modales
 */
function inicializarEventosModales() {
    console.log("Inicializando eventos de modales");
    
    // Evento para iniciar nueva venta
    $('#iniciarNuevaVenta').on('click', function() {
        console.log("Botón Nueva Venta clickeado");
        iniciarNuevaVenta();
    });
    
    // Eventos para los botones de navegación entre pasos
    $('#continuarPaso2Btn').on('click', continuarPaso2);
    $('#volverPaso1Btn').on('click', volverPaso1);
    $('#continuarPaso3Btn').on('click', continuarPaso3);
    $('#volverPaso2Btn').on('click', volverPaso2);
    
    // Evento para agregar producto al carrito
    $('#agregarProductoBtn').on('click', agregarProducto);
    
    // Evento para finalizar venta
    $('#finalizarVentaBtn').on('click', finalizarVenta);
    
    // Evento para imprimir detalle de venta
    $('#imprimirDetalleBtn').on('click', imprimirDetalle);
    
    // Evento para aplicar descuento
    $(document).on('change', '#descuento_porcentaje', function() {
        aplicarDescuento();
    });
}

/**
 * Inicializa la tabla DataTable
 */
function inicializarDataTable() {
    console.log("Inicializando DataTable");
    
    // Verificar si la tabla existe antes de inicializarla
    if ($('#ventasTable').length > 0) {
        ventasTable = $('#ventasTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "js/dataTables.es-ES.json"
            },
            "order": [
                [0, 'desc']
            ],
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ]
        });
    } else {
        console.log("La tabla #ventasTable no existe en el DOM");
    }
}

/**
 * Inicializa el resto de eventos y componentes
 */
function inicializarEventosRestantes() {
    console.log("Inicializando eventos restantes");
    
    // Inicializar Select2 para el selector de productos
    if ($('.producto-select').length > 0) {
        $('.producto-select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Seleccione un producto',
            allowClear: true,
            templateResult: formatProductOption,
            templateSelection: formatProductSelection,
            dropdownParent: $('#paso2Modal')
        });
    }
    
    // Eventos delegados para botones dinámicos
    $(document).on('click', '.ver-detalle', function() {
        const idVenta = $(this).data('id');
        verDetalleVenta(idVenta);
    });
    
    $(document).on('click', '.cancelar-venta', function() {
        const idVenta = $(this).data('id');
        cancelarVenta(idVenta);
    });
    
    $(document).on('click', '.quitar-item', function() {
        const index = $(this).data('index');
        quitarItemCarrito(index);
    });
    
    $(document).on('change', '.cart-item-quantity', function() {
        const index = $(this).data('index');
        const nuevaCantidad = parseInt($(this).val());
        cambiarCantidadCarrito(index, nuevaCantidad);
    });
}

/**
 * Formatea las opciones del select de productos (sin imágenes)
 */
function formatProductOption(product) {
    if (!product.id) {
        return product.text;
    }
    
    var $option = $(product.element);
    var nombre = $option.data('nombre');
    var precio = $option.data('precio');
    var stock = $option.data('stock');
    var stockClass = '';
    
    if (stock !== null) {
        if (stock > 10) {
            stockClass = 'high';
        } else if (stock > 5) {
            stockClass = 'medium';
        } else {
            stockClass = 'low';
        }
    }
    
    var stockText = stock !== null ? "Stock: " + stock : "Stock: No disponible";
    
    var $container = $(
        '<div class="product-option">' +
            '<div class="product-option-details">' +
                '<div class="product-option-name">' + nombre + '</div>' +
                '<div class="product-option-price">$' + precio.toLocaleString('es-AR') + '</div>' +
                '<div class="product-option-stock ' + stockClass + '">' + stockText + '</div>' +
            '</div>' +
        '</div>'
    );
    
    return $container;
}

/**
 * Formatea la selección del select de productos
 */
function formatProductSelection(product) {
    if (!product.id) {
        return product.text;
    }
    
    var $option = $(product.element);
    var nombre = $option.data('nombre');
    var precio = $option.data('precio');
    
    return nombre + ' - $' + precio.toLocaleString('es-AR');
}

/**
 * Aplica el descuento al total de la venta
 */
function aplicarDescuento() {
    descuentoPorcentaje = parseFloat($('#descuento_porcentaje').val()) || 0;
    
    // Limitar el descuento a un máximo de 100%
    if (descuentoPorcentaje > 100) {
        descuentoPorcentaje = 100;
        $('#descuento_porcentaje').val(100);
    }
    
    // Calcular el total con descuento
    const descuentoMonto = (total * descuentoPorcentaje) / 100;
    totalConDescuento = total - descuentoMonto;
    
    // Actualizar la interfaz
    $('#descuento_monto').text('$' + descuentoMonto.toLocaleString('es-AR'));
    $('#total_con_descuento').text('$' + totalConDescuento.toLocaleString('es-AR'));
    $('#total_venta_input').val(totalConDescuento);
    
    // Mostrar u ocultar la sección de descuento según corresponda
    if (descuentoPorcentaje > 0) {
        $('.descuento-aplicado').show();
    } else {
        $('.descuento-aplicado').hide();
    }
}

/**
 * Actualiza el carrito de compras
 */
function actualizarCarrito() {
    const itemsCarrito = $('#items_carrito');
    
    if (carrito.length === 0) {
        itemsCarrito.html('<div class="alert alert-info">No hay productos agregados</div>');
        $('#total_venta').text('$0');
        $('#total_venta_input').val(0);
        $('#descuento_monto').text('$0');
        $('#total_con_descuento').text('$0');
        $('.descuento-aplicado').hide();
        return;
    }
    
    let html = '';
    total = 0;
    
    carrito.forEach((item, index) => {
        const subtotal = item.precio * item.cantidad;
        total += subtotal;
        
        html += `
        <div class="cart-item">
            <div class="cart-item-details">
                <div><strong>${item.nombre}</strong></div>
                <div>Precio: $${item.precio.toLocaleString('es-AR')}</div>
                <div>Subtotal: $${subtotal.toLocaleString('es-AR')}</div>
            </div>
            <div class="cart-item-actions">
                <button type="button" class="btn btn-sm btn-outline-danger quitar-item" data-index="${index}">
                    <i class="bi bi-trash"></i>
                </button>
                <input type="number" class="form-control cart-item-quantity" value="${item.cantidad}" min="1" max="${item.stock === null ? 999 : item.stock}" data-index="${index}">
            </div>
        </div>
        `;
    });
    
    itemsCarrito.html(html);
    $('#total_venta').text('$' + total.toLocaleString('es-AR'));
    
    // Aplicar descuento si existe
    aplicarDescuento();
}

/**
 * Actualiza el resumen de la venta en el paso 3
 */
function actualizarResumenVenta() {
    const resumenCliente = $('#resumen_cliente');
    const resumenProductos = $('#resumen_productos');
    const resumenTotal = $('#resumen_total');
    const resumenDescuento = $('#resumen_descuento');
    const resumenTotalConDescuento = $('#resumen_total_con_descuento');
    
    // Actualizar datos del cliente
    let htmlCliente = `
        <p><strong>Nombre y Apellido:</strong> ${datosCliente.nombreyapellido || ''}</p>
    `;
    
    if (datosCliente.email) {
        htmlCliente += `<p><strong>Email:</strong> ${datosCliente.email}</p>`;
    }
    
    if (datosCliente.dnicuit) {
        htmlCliente += `<p><strong>DNI/CUIT:</strong> ${datosCliente.dnicuit}</p>`;
    }
    
    if (datosCliente.telefono) {
        htmlCliente += `<p><strong>Teléfono:</strong> ${datosCliente.telefono}</p>`;
    }
    
    if (datosCliente.domicilio) {
        htmlCliente += `<p><strong>Domicilio:</strong> ${datosCliente.domicilio}</p>`;
    }
    
    if (datosCliente.notas) {
        htmlCliente += `<p><strong>Notas:</strong> ${datosCliente.notas}</p>`;
    }
    
    resumenCliente.html(htmlCliente);
    
    // Actualizar productos
    let htmlProductos = '';
    
    if (carrito.length === 0) {
        htmlProductos = '<div class="alert alert-info">No hay productos agregados</div>';
    } else {
        htmlProductos = '<div class="table-responsive"><table class="table table-sm table-striped">';
        htmlProductos += '<thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th></tr></thead><tbody>';
        
        carrito.forEach(item => {
            const subtotal = item.precio * item.cantidad;
            htmlProductos += `
                <tr>
                    <td>${item.nombre}</td>
                    <td>$${item.precio.toLocaleString('es-AR')}</td>
                    <td>${item.cantidad}</td>
                    <td>$${subtotal.toLocaleString('es-AR')}</td>
                </tr>
            `;
        });
        
        htmlProductos += '</tbody></table></div>';
    }
    
    resumenProductos.html(htmlProductos);
    
    // Actualizar total y descuento
    resumenTotal.text('$' + total.toLocaleString('es-AR'));
    
    if (descuentoPorcentaje > 0) {
        const descuentoMonto = (total * descuentoPorcentaje) / 100;
        resumenDescuento.html(`<p><strong>Descuento (${descuentoPorcentaje}%):</strong> -$${descuentoMonto.toLocaleString('es-AR')}</p>`);
        resumenTotalConDescuento.html(`<p><strong>Total con descuento:</strong> $${totalConDescuento.toLocaleString('es-AR')}</p>`);
        resumenDescuento.show();
        resumenTotalConDescuento.show();
    } else {
        resumenDescuento.hide();
        resumenTotalConDescuento.hide();
    }
}

/**
 * Actualiza los pasos de la venta
 */
function actualizarPasos(paso) {
    pasoActual = paso;
    
    // Actualizar clases de los pasos
    $('.step').removeClass('active completed');
    
    for (let i = 1; i <= 3; i++) {
        if (i < paso) {
            $(`#step${i}`).addClass('completed');
        } else if (i === paso) {
            $(`#step${i}`).addClass('active');
        }
    }
    
    // Actualizar barra de progreso
    let progreso = 0;
    if (paso === 1) progreso = 0;
    else if (paso === 2) progreso = 50;
    else if (paso === 3) progreso = 100;
    
    $('.steps-progress').css('width', `${progreso}%`);
}

/**
 * Inicia una nueva venta
 */
function iniciarNuevaVenta() {
    console.log("Iniciando nueva venta");
    
    // Reiniciar variables
    carrito = [];
    total = 0;
    totalConDescuento = 0;
    descuentoPorcentaje = 0;
    datosCliente = {};
    
    // Reiniciar formularios
    $('#datosClienteForm')[0].reset();
    
    // Reiniciar select de productos si existe
    if ($('#producto_select').length > 0) {
        $('#producto_select').val('').trigger('change');
    }
    
    // Reiniciar cantidad y descuento
    if ($('#cantidad_producto').length > 0) {
        $('#cantidad_producto').val(1);
    }
    
    if ($('#descuento_porcentaje').length > 0) {
        $('#descuento_porcentaje').val(0);
    }
    
    // Actualizar carrito
    actualizarCarrito();
    
    // Actualizar pasos
    actualizarPasos(1);
    
    // Mostrar modal del paso 1
    console.log("Abriendo modal paso 1");
    
    // Verificar si el modal existe
    if ($('#paso1Modal').length > 0) {
        // Usar setTimeout para asegurar que el DOM esté listo
        setTimeout(function() {
            try {
                var modal = new bootstrap.Modal(document.getElementById('paso1Modal'));
                modal.show();
            } catch (error) {
                console.error("Error al mostrar el modal:", error);
                // Alternativa si falla la inicialización de Bootstrap
                $('#paso1Modal').modal('show');
            }
        }, 100);
    } else {
        console.error("El modal #paso1Modal no existe en el DOM");
        alert("Error: No se pudo abrir el formulario de venta. Por favor, recargue la página e intente nuevamente.");
    }
}

/**
 * Continúa al paso 2 de la venta
 */
function continuarPaso2() {
    // Validar datos del cliente
    const nombreyapellido = $('#nombreyapellido_cliente').val().trim();
    
    if (!nombreyapellido) {
        Swal.fire({
            icon: 'warning',
            title: 'Datos incompletos',
            text: 'Debe ingresar el nombre y apellido del cliente.',
            confirmButtonColor: '#104D43'
        });
        return;
    }
    
    // Guardar datos del cliente
    datosCliente = {
        nombreyapellido: nombreyapellido,
        email: $('#email_cliente').val().trim(),
        dnicuit: $('#dnicuit_cliente').val().trim(),
        telefono: $('#telefono_cliente').val().trim(),
        domicilio: $('#domicilio_cliente').val().trim(),
        notas: $('#notas').val().trim()
    };
    
    // Cerrar modal del paso 1
    $('#paso1Modal').modal('hide');
    
    // Actualizar pasos
    actualizarPasos(2);
    
    // Mostrar modal del paso 2
    setTimeout(function() {
        $('#paso2Modal').modal('show');
    }, 500);
}

/**
 * Vuelve al paso 1 de la venta
 */
function volverPaso1() {
    // Cerrar modal del paso 2
    $('#paso2Modal').modal('hide');
    
    // Actualizar pasos
    actualizarPasos(1);
    
    // Mostrar modal del paso 1
    setTimeout(function() {
        $('#paso1Modal').modal('show');
    }, 500);
}

/**
 * Continúa al paso 3 de la venta
 */
function continuarPaso3() {
    // Validar que haya productos en el carrito
    if (carrito.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Carrito vacío',
            text: 'Debe agregar al menos un producto a la venta.',
            confirmButtonColor: '#104D43'
        });
        return;
    }
    
    // Cerrar modal del paso 2
    $('#paso2Modal').modal('hide');
    
    // Actualizar resumen de la venta
    actualizarResumenVenta();
    
    // Actualizar pasos
    actualizarPasos(3);
    
    // Mostrar modal del paso 3
    setTimeout(function() {
        $('#paso3Modal').modal('show');
    }, 500);
}

/**
 * Vuelve al paso 2 de la venta
 */
function volverPaso2() {
    // Cerrar modal del paso 3
    $('#paso3Modal').modal('hide');
    
    // Actualizar pasos
    actualizarPasos(2);
    
    // Mostrar modal del paso 2
    setTimeout(function() {
        $('#paso2Modal').modal('show');
    }, 500);
}

/**
 * Agrega un producto al carrito
 */
function agregarProducto() {
    const productoSelect = $('#producto_select');
    const productoId = productoSelect.val();
    
    if (!productoId) {
        Swal.fire({
            icon: 'warning',
            title: 'Seleccione un producto',
            text: 'Debe seleccionar un producto para agregarlo a la venta.',
            confirmButtonColor: '#104D43'
        });
        return;
    }
    
    const cantidad = parseInt($('#cantidad_producto').val());
    if (cantidad <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Cantidad inválida',
            text: 'La cantidad debe ser mayor a 0.',
            confirmButtonColor: '#104D43'
        });
        return;
    }
    
    const selectedOption = productoSelect.find('option:selected');
    const nombre = selectedOption.data('nombre');
    const precio = selectedOption.data('precio');
    const stock = selectedOption.data('stock');
    
    // Verificar si hay suficiente stock
    if (stock !== null && cantidad > stock) {
        Swal.fire({
            icon: 'warning',
            title: 'Stock insuficiente',
            text: `Solo hay ${stock} unidades disponibles de este producto.`,
            confirmButtonColor: '#104D43'
        });
        return;
    }
    
    // Verificar si el producto ya está en el carrito
    const existingIndex = carrito.findIndex(item => item.id === productoId);
    if (existingIndex !== -1) {
        // Sumar la cantidad si ya existe
        const nuevaCantidad = carrito[existingIndex].cantidad + cantidad;
        
        // Verificar stock nuevamente
        if (stock !== null && nuevaCantidad > stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock insuficiente',
                text: `Ya tiene ${carrito[existingIndex].cantidad} unidades de este producto en el carrito. No puede agregar ${cantidad} más porque excedería el stock disponible.`,
                confirmButtonColor: '#104D43'
            });
            return;
        }
        
        carrito[existingIndex].cantidad = nuevaCantidad;
    } else {
        // Agregar nuevo producto al carrito
        carrito.push({
            id: productoId,
            nombre: nombre,
            precio: precio,
            cantidad: cantidad,
            stock: stock
        });
    }
    
    // Actualizar carrito
    actualizarCarrito();
    
    // Mostrar mensaje de éxito
    Swal.fire({
        icon: 'success',
        title: 'Producto agregado',
        text: `Se agregó ${cantidad} unidad(es) de ${nombre} al carrito.`,
        confirmButtonColor: '#104D43',
        timer: 1500,
        showConfirmButton: false
    });
    
    // Limpiar selección
    productoSelect.val('').trigger('change');
    $('#cantidad_producto').val(1);
}

/**
 * Quita un item del carrito
 */
function quitarItemCarrito(index) {
    carrito.splice(index, 1);
    actualizarCarrito();
}

/**
 * Cambia la cantidad de un producto en el carrito
 */
function cambiarCantidadCarrito(index, nuevaCantidad) {
    if (nuevaCantidad > 0 && (carrito[index].stock === null || nuevaCantidad <= carrito[index].stock)) {
        carrito[index].cantidad = nuevaCantidad;
        actualizarCarrito();
    } else {
        // Restaurar valor anterior
        $(`.cart-item-quantity[data-index="${index}"]`).val(carrito[index].cantidad);
        Swal.fire({
            icon: 'warning',
            title: 'Cantidad inválida',
            text: 'La cantidad debe ser mayor a 0 y no puede exceder el stock disponible.',
            confirmButtonColor: '#104D43'
        });
    }
}

/**
 * Finaliza la venta
 */
function finalizarVenta() {
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Procesando...',
        text: 'Registrando la venta',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Crear objeto FormData
    const formData = new FormData();
    
    // Agregar datos del cliente
    formData.append('nombreyapellido_cliente', datosCliente.nombreyapellido);
    formData.append('email_cliente', datosCliente.email);
    formData.append('dnicuit_cliente', datosCliente.dnicuit);
    formData.append('telefono_cliente', datosCliente.telefono);
    formData.append('domicilio_cliente', datosCliente.domicilio);
    formData.append('notas', datosCliente.notas);
    
    // Agregar total de la venta y descuento
    formData.append('total_venta', totalConDescuento);
    formData.append('descuento_porcentaje', descuentoPorcentaje);
    formData.append('total_sin_descuento', total);
    
    // Agregar productos del carrito
    carrito.forEach((item, index) => {
        formData.append(`productos[${index}][id]`, item.id);
        formData.append(`productos[${index}][cantidad]`, item.cantidad);
        formData.append(`productos[${index}][precio]`, item.precio);
    });
    
    // Enviar formulario
    $.ajax({
        url: 'controllers/procesar_nueva_venta.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log("Respuesta recibida:", response);
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    // Cerrar el modal
                    $('#paso3Modal').modal('hide');
                    
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: '¡Venta registrada con éxito!',
                        text: result.message,
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#104D43'
                    }).then(() => {
                        // Recargar la página
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Ocurrió un error al registrar la venta.',
                        confirmButtonColor: '#104D43'
                    });
                }
            } catch (e) {
                console.error("Error parsing JSON:", response, e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la respuesta: ' + e.message,
                    confirmButtonColor: '#104D43'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error, xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud: ' + error,
                confirmButtonColor: '#104D43'
            });
        }
    });
}

/**
 * Ver detalles de una venta
 */
function verDetalleVenta(idVenta) {
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Cargando...',
        text: 'Obteniendo detalles de la venta',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Cargar detalles de la venta
    $.ajax({
        url: 'controllers/obtener_detalle_venta.php',
        type: 'GET',
        data: { id: idVenta },
        success: function(response) {
            Swal.close();
            
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    // Mostrar detalles en el modal
                    $('#detalleVentaBody').html(result.html);
                    $('#detalleVentaModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'No se pudieron obtener los detalles de la venta.',
                        confirmButtonColor: '#104D43'
                    });
                }
            } catch (e) {
                console.error("Error parsing JSON:", response, e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la respuesta: ' + e.message,
                    confirmButtonColor: '#104D43'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            console.error("AJAX Error:", status, error, xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud: ' + error,
                confirmButtonColor: '#104D43'
            });
        }
    });
}

/**
 * Imprime los detalles de una venta
 */
function imprimirDetalle() {
    const contenido = document.getElementById('detalleVentaBody').innerHTML;
    const ventanaImpresion = window.open('', '_blank');
    ventanaImpresion.document.write(`
        <html>
        <head>
            <title>Detalle de Venta</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .footer {
                    text-align: center;
                    margin-top: 30px;
                    font-size: 12px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    padding: 8px;
                    border: 1px solid #ddd;
                }
                th {
                    background-color: #f2f2f2;
                }
                .total {
                    font-weight: bold;
                    text-align: right;
                }
                @media print {
                    .no-print {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>CloverTecno</h2>
                <p>Detalle de Venta</p>
            </div>
            ${contenido}
            <div class="footer">
                <p>Gracias por su compra</p>
            </div>
            <div class="no-print">
                <button onclick="window.print()" class="btn btn-primary mt-3">Imprimir</button>
            </div>
        </body>
        </html>
    `);
    ventanaImpresion.document.close();
}

/**
 * Cancela una venta
 */
function cancelarVenta(idVenta) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción cancelará la venta y restaurará el stock de los productos.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, cancelar venta',
        cancelButtonText: 'No, mantener venta'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                text: 'Cancelando la venta',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar solicitud para cancelar venta
            $.ajax({
                url: 'controllers/cancelar_venta.php',
                type: 'POST',
                data: { id: idVenta },
                success: function(response) {
                    try {
                        const result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Venta cancelada',
                                text: result.message,
                                showConfirmButton: true,
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#104D43'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'No se pudo cancelar la venta.',
                                confirmButtonColor: '#104D43'
                            });
                        }
                    } catch (e) {
                        console.error("Error parsing JSON:", response, e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la respuesta: ' + e.message,
                            confirmButtonColor: '#104D43'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la solicitud: ' + error,
                        confirmButtonColor: '#104D43'
                    });
                }
            });
        }
    });
}