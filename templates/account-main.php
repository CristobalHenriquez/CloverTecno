<?php
// Incluir la conexión a la base de datos y el controlador de cuenta
include_once 'includes/db_connection.php';
include_once 'controllers/account_controller.php';

// Obtener información del cliente actual
$cliente_id = getClienteId();
$cliente_nombre = getClienteNombre();
$cliente_email = getClienteEmail();

// Consultar las órdenes del cliente
$query = "SELECT v.*, COUNT(dv.id_detalle) as cantidad_items 
          FROM ventas v 
          LEFT JOIN detalle_ventas dv ON v.id_venta = dv.id_venta 
          WHERE v.id_cliente = ? 
          GROUP BY v.id_venta 
          ORDER BY v.fecha_venta DESC";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();

// Contar el número total de órdenes
$total_ordenes = $result->num_rows;

// Obtener los detalles del usuario
$user_details = getUserDetails($db, $cliente_id);

// Variable para almacenar el resultado de la actualización
$update_result = null;

// Procesar el formulario de actualización de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account_details'])) {
    // Sanitizar los datos recibidos
    $datos = [
        'dni' => sanitizeString($_POST['dni'] ?? ''),
        'telefono' => sanitizeString($_POST['telefono'] ?? ''),
        'direccion' => sanitizeString($_POST['direccion'] ?? ''),
        'departamento' => sanitizeString($_POST['departamento'] ?? ''),
        'ciudad' => sanitizeString($_POST['ciudad'] ?? ''),
        'provincia' => sanitizeString($_POST['provincia'] ?? ''),
        'codigo_postal' => sanitizeString($_POST['codigo_postal'] ?? '')
    ];

    // Actualizar los datos del usuario
    $update_result = updateUserDetails($db, $cliente_id, $datos);

    // Si la actualización fue exitosa, actualizar los detalles del usuario
    if ($update_result['success']) {
        $user_details = getUserDetails($db, $cliente_id);
    }
}
?>

<!-- Account Section -->
<section id="account" class="account section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <!-- Mobile Menu Toggle -->
        <div class="mobile-menu d-lg-none mb-4">
            <button class="mobile-menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#profileMenu">
                <i class="bi bi-grid"></i>
                <span>Menu</span>
            </button>
        </div>

        <div class="row g-4">
            <!-- Profile Menu -->
            <div class="col-lg-3">
                <div class="profile-menu collapse d-lg-block" id="profileMenu">
                    <!-- User Info -->
                    <div class="user-info" data-aos="fade-right">
                        <h4><?php echo htmlspecialchars($cliente_nombre); ?></h4>
                        <div class="user-status">
                            <i class="bi bi-envelope"></i>
                            <span><?php echo htmlspecialchars($cliente_email); ?></span>
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <nav class="menu-nav">
                        <ul class="nav flex-column" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#account-details">
                                    <i class="bi bi-person"></i>
                                    <span>Datos de Cuenta</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#orders">
                                    <i class="bi bi-box-seam"></i>
                                    <span>Mis Órdenes</span>
                                    <?php if ($total_ordenes > 0): ?>
                                        <span class="badge"><?php echo $total_ordenes; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        </ul>

                        <div class="menu-footer">
                            <a href="logout.php" class="logout-link">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Cerrar Sesión</span>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-9">
                <div class="content-area">
                    <div class="tab-content">
                        <!-- Account Details Tab (Ahora es la pestaña activa por defecto) -->
                        <div class="tab-pane fade show active" id="account-details">
                            <div class="section-header" data-aos="fade-up">
                                <h2>Datos de Cuenta</h2>
                                <p>Actualiza tu información personal y dirección de envío</p>
                            </div>

                            <form class="account-details-form" method="POST" action="" id="accountDetailsForm" data-aos="fade-up">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="dni">DNI / CUIT <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="dni" name="dni" placeholder="Ingresa tu DNI o CUIT" value="<?php echo htmlspecialchars($user_details['dni']); ?>" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="telefono">Número de Teléfono <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ingresa tu número de teléfono" value="<?php echo htmlspecialchars($user_details['telefono']); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="direccion">Dirección <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Calle y número" value="<?php echo htmlspecialchars($user_details['direccion']); ?>" required>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="departamento">Departamento/Casa (opcional)</label>
                                    <input type="text" class="form-control" id="departamento" name="departamento" placeholder="Apartamento, Piso, etc." value="<?php echo htmlspecialchars($user_details['departamento']); ?>">
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4 form-group">
                                        <label for="ciudad">Ciudad <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ciudad" value="<?php echo htmlspecialchars($user_details['ciudad']); ?>" required>
                                    </div>
                                    <div class="col-md-4 form-group mt-3 mt-md-0">
                                        <label for="provincia">Provincia <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="provincia" name="provincia" placeholder="Provincia" value="<?php echo htmlspecialchars($user_details['provincia']); ?>" required>
                                    </div>
                                    <div class="col-md-4 form-group mt-3 mt-md-0">
                                        <label for="codigo_postal">Código Postal <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" placeholder="Código Postal" value="<?php echo htmlspecialchars($user_details['codigo_postal']); ?>" required>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" name="update_account_details" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Orders Tab -->
                        <div class="tab-pane fade" id="orders">
                            <div class="section-header" data-aos="fade-up">
                                <h2>Mis Órdenes</h2>
                                <div class="header-actions">
                                    <div class="search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="searchOrders" placeholder="Buscar órdenes...">
                                    </div>
                                    <div class="dropdown">
                                        <button class="filter-btn" data-bs-toggle="dropdown">
                                            <i class="bi bi-funnel"></i>
                                            <span>Filtrar</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" data-filter="all">Todas las Órdenes</a></li>
                                            <li><a class="dropdown-item" href="#" data-filter="Pendiente">Pendiente</a></li>
                                            <li><a class="dropdown-item" href="#" data-filter="En Proceso">En Proceso</a></li>
                                            <li><a class="dropdown-item" href="#" data-filter="Enviado">Enviado</a></li>
                                            <li><a class="dropdown-item" href="#" data-filter="Entregado">Entregado</a></li>
                                            <li><a class="dropdown-item" href="#" data-filter="Cancelado">Cancelado</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="orders-grid">
                                <?php
                                if ($result->num_rows > 0):
                                    $delay = 100;
                                    while ($orden = $result->fetch_assoc()):
                                        // Formatear fecha
                                        $fecha = new DateTime($orden['fecha_venta']);
                                        $fechaFormateada = $fecha->format('d M, Y');

                                        // Determinar clase de estado
                                        $estadoClass = '';
                                        switch ($orden['estado']) {
                                            case 'Pendiente':
                                                $estadoClass = 'pending';
                                                break;
                                            case 'En Proceso':
                                                $estadoClass = 'processing';
                                                break;
                                            case 'Enviado':
                                                $estadoClass = 'shipped';
                                                break;
                                            case 'Entregado':
                                                $estadoClass = 'delivered';
                                                break;
                                            case 'Cancelado':
                                                $estadoClass = 'cancelled';
                                                break;
                                            default:
                                                $estadoClass = 'pending';
                                                break;
                                        }

                                        // Obtener productos de la orden
                                        $query_productos = "SELECT p.id_producto, p.nombre_producto, ip.imagen_path 
                                                          FROM detalle_ventas dv 
                                                          JOIN productos p ON dv.id_producto = p.id_producto 
                                                          LEFT JOIN (
                                                              SELECT id_producto, MIN(imagen_path) as imagen_path 
                                                              FROM imagenes_productos 
                                                              GROUP BY id_producto
                                                          ) ip ON p.id_producto = ip.id_producto 
                                                          WHERE dv.id_venta = ? 
                                                          LIMIT 3";
                                        $stmt_productos = $db->prepare($query_productos);
                                        $stmt_productos->bind_param("i", $orden['id_venta']);
                                        $stmt_productos->execute();
                                        $result_productos = $stmt_productos->get_result();

                                        // Contar total de productos
                                        $total_productos = $orden['cantidad_items'];
                                ?>
                                        <!-- Order Card -->
                                        <div class="order-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>" data-status="<?php echo $orden['estado']; ?>">
                                            <div class="order-header">
                                                <div class="order-id">
                                                    <span class="label">Orden ID:</span>
                                                    <span class="value">#ORD-<?php echo $orden['id_venta']; ?></span>
                                                </div>
                                                <div class="order-date"><?php echo $fechaFormateada; ?></div>
                                            </div>
                                            <div class="order-content">
                                                <div class="product-grid">
                                                    <?php
                                                    $count = 0;
                                                    while ($producto = $result_productos->fetch_assoc()):
                                                        $imagen = !empty($producto['imagen_path']) ? $producto['imagen_path'] : 'assets/img/no-image.jpg';
                                                        $count++;
                                                    ?>
                                                        <img src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" loading="lazy">
                                                    <?php endwhile; ?>

                                                    <?php if ($total_productos > 3): ?>
                                                        <span class="more-items">+<?php echo $total_productos - 3; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="order-info">
                                                    <div class="info-row">
                                                        <span>Estado</span>
                                                        <span class="status <?php echo $estadoClass; ?>"><?php echo $orden['estado']; ?></span>
                                                    </div>
                                                    <div class="info-row">
                                                        <span>Productos</span>
                                                        <span><?php echo $total_productos; ?> item<?php echo $total_productos != 1 ? 's' : ''; ?></span>
                                                    </div>
                                                    <div class="info-row">
                                                        <span>Total</span>
                                                        <span class="price">$<?php echo number_format($orden['total_venta'], 0, ',', '.'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-footer">
                                                <?php if ($orden['estado'] == 'Pendiente' || $orden['estado'] == 'En Proceso' || $orden['estado'] == 'Enviado'): ?>
                                                    <button type="button" class="btn-track" data-bs-toggle="collapse" data-bs-target="#tracking<?php echo $orden['id_venta']; ?>" aria-expanded="false">Seguir Orden</button>
                                                <?php elseif ($orden['estado'] == 'Entregado'): ?>
                                                    <button type="button" class="btn-review">Escribir Reseña</button>
                                                <?php elseif ($orden['estado'] == 'Cancelado'): ?>
                                                    <button type="button" class="btn-reorder">Reordenar</button>
                                                <?php endif; ?>
                                                <button type="button" class="btn-details" data-bs-toggle="collapse" data-bs-target="#details<?php echo $orden['id_venta']; ?>" aria-expanded="false">Ver Detalles</button>
                                            </div>

                                            <!-- Order Tracking -->
                                            <?php if ($orden['estado'] == 'Pendiente' || $orden['estado'] == 'En Proceso' || $orden['estado'] == 'Enviado'): ?>
                                                <div class="collapse tracking-info" id="tracking<?php echo $orden['id_venta']; ?>">
                                                    <div class="tracking-timeline" style="background-color: black;">
                                                        <div class="timeline-item <?php echo ($orden['estado'] != 'Pendiente') ? 'completed' : 'active'; ?>">
                                                            <div class="timeline-icon">
                                                                <i class="bi bi-<?php echo ($orden['estado'] != 'Pendiente') ? 'check-circle-fill' : 'hourglass-split'; ?>"></i>
                                                            </div>
                                                            <div class="timeline-content">
                                                                <h5>Orden Confirmada</h5>
                                                                <p>Tu orden ha sido recibida y confirmada</p>
                                                                <span class="timeline-date"><?php echo $fechaFormateada; ?></span>
                                                            </div>
                                                        </div>

                                                        <div class="timeline-item <?php echo ($orden['estado'] == 'En Proceso' || $orden['estado'] == 'Enviado') ? 'completed' : ($orden['estado'] == 'Pendiente' ? '' : 'active'); ?>">
                                                            <div class="timeline-icon">
                                                                <i class="bi bi-<?php echo ($orden['estado'] == 'En Proceso' || $orden['estado'] == 'Enviado') ? 'check-circle-fill' : 'gear'; ?>"></i>
                                                            </div>
                                                            <div class="timeline-content">
                                                                <h5>Procesando</h5>
                                                                <p>Tu orden está siendo preparada para envío</p>
                                                                <?php if ($orden['estado'] == 'En Proceso' || $orden['estado'] == 'Enviado'): ?>
                                                                    <span class="timeline-date"><?php echo $fechaFormateada; ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                        <div class="timeline-item <?php echo ($orden['estado'] == 'Enviado') ? 'completed' : ($orden['estado'] == 'En Proceso' ? 'active' : ''); ?>">
                                                            <div class="timeline-icon">
                                                                <i class="bi bi-<?php echo ($orden['estado'] == 'Enviado') ? 'check-circle-fill' : 'box-seam'; ?>"></i>
                                                            </div>
                                                            <div class="timeline-content">
                                                                <h5>Empaquetado</h5>
                                                                <p>Tus productos están siendo empaquetados para envío</p>
                                                                <?php if ($orden['estado'] == 'Enviado'): ?>
                                                                    <span class="timeline-date"><?php echo $fechaFormateada; ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                        <div class="timeline-item <?php echo ($orden['estado'] == 'Enviado') ? 'active' : ''; ?>">
                                                            <div class="timeline-icon">
                                                                <i class="bi bi-truck"></i>
                                                            </div>
                                                            <div class="timeline-content">
                                                                <h5>En Tránsito</h5>
                                                                <p><?php echo ($orden['estado'] == 'Enviado') ? 'Paquete en tránsito con el transportista' : 'Se espera enviar en las próximas 24 horas'; ?></p>
                                                                <?php if ($orden['estado'] == 'Enviado' && !empty($orden['numero_seguimiento'])): ?>
                                                                    <div class="shipping-info">
                                                                        <span>Número de Seguimiento: </span>
                                                                        <span class="tracking-number"><?php echo $orden['numero_seguimiento']; ?></span>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                        <div class="timeline-item">
                                                            <div class="timeline-icon">
                                                                <i class="bi bi-house-door"></i>
                                                            </div>
                                                            <div class="timeline-content">
                                                                <h5>Entrega</h5>
                                                                <p>Entrega estimada: <?php
                                                                                        $fechaEntrega = clone $fecha;
                                                                                        $fechaEntrega->modify('+3 days');
                                                                                        echo $fechaEntrega->format('d M, Y');
                                                                                        ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Order Details -->
                                            <div class="collapse order-details" id="details<?php echo $orden['id_venta']; ?>">
                                                <div class="details-content" style="background-color: black;">
                                                    <div class="detail-section">
                                                        <h5>Información de la Orden</h5>
                                                        <div class="info-grid">
                                                            <div class="info-item">
                                                                <span class="label">Método de Pago</span>
                                                                <span class="value"><?php echo !empty($orden['metodo_pago']) ? $orden['metodo_pago'] : 'No especificado'; ?></span>
                                                            </div>
                                                            <div class="info-item">
                                                                <span class="label">Notas</span>
                                                                <span class="value"><?php echo !empty($orden['notas']) ? $orden['notas'] : 'Sin notas'; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="detail-section">
                                                        <h5>Productos (<?php echo $total_productos; ?>)</h5>
                                                        <div class="order-items ">
                                                            <?php
                                                            // Obtener detalles completos de los productos
                                                            $query_detalles = "SELECT dv.*, p.nombre_producto, ip.imagen_path 
                                                                      FROM detalle_ventas dv 
                                                                      JOIN productos p ON dv.id_producto = p.id_producto 
                                                                      LEFT JOIN (
                                                                          SELECT id_producto, MIN(imagen_path) as imagen_path 
                                                                          FROM imagenes_productos 
                                                                          GROUP BY id_producto
                                                                      ) ip ON p.id_producto = ip.id_producto 
                                                                      WHERE dv.id_venta = ?";
                                                            $stmt_detalles = $db->prepare($query_detalles);
                                                            $stmt_detalles->bind_param("i", $orden['id_venta']);
                                                            $stmt_detalles->execute();
                                                            $result_detalles = $stmt_detalles->get_result();

                                                            while ($detalle = $result_detalles->fetch_assoc()):
                                                                $imagen = !empty($detalle['imagen_path']) ? $detalle['imagen_path'] : 'assets/img/no-image.jpg';
                                                            ?>
                                                                <div class="item" style="background-color: black;">
                                                                    <img src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars($detalle['nombre_producto']); ?>" loading="lazy">
                                                                    <div class="item-info">
                                                                        <h6><?php echo htmlspecialchars($detalle['nombre_producto']); ?></h6>
                                                                        <div class="item-meta">
                                                                            <span class="sku">SKU: PRD-<?php echo $detalle['id_producto']; ?></span>
                                                                            <span class="qty">Cant: <?php echo $detalle['cantidad']; ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="item-price">$<?php echo number_format($detalle['precio_unitario'], 0, ',', '.'); ?></div>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    </div>

                                                    <div class="detail-section">
                                                        <h5>Detalles de Precio</h5>
                                                        <div class="price-breakdown" style="background-color: black;">
                                                            <div class="price-row">
                                                                <span>Subtotal</span>
                                                                <span>$<?php echo number_format($orden['total_venta'], 0, ',', '.'); ?></span>
                                                            </div>
                                                            <div class="price-row total">
                                                                <span>Total</span>
                                                                <span>$<?php echo number_format($orden['total_venta'], 0, ',', '.'); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php if (!empty($orden['domicilio_cliente'])): ?>
                                                        <div class="detail-section">
                                                            <h5>Dirección de Envío</h5>
                                                            <div class="address-info" style="background-color: black;">
                                                                <p><?php echo htmlspecialchars($orden['nombreyapellido_cliente']); ?><br>
                                                                    <?php echo htmlspecialchars($orden['domicilio_cliente']); ?></p>
                                                                <?php if (!empty($orden['telefono_cliente'])): ?>
                                                                    <p class="contact"><?php echo htmlspecialchars($orden['telefono_cliente']); ?></p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        $delay += 100;
                                    endwhile;
                                else:
                                    ?>
                                    <div class="no-orders" data-aos="fade-up">
                                        <div class="no-orders-icon">
                                            <i class="bi bi-bag-x"></i>
                                        </div>
                                        <h3>No tienes órdenes todavía</h3>
                                        <p>Explora nuestra tienda y realiza tu primera compra</p>
                                        <a href="Productos" class="btn-shop-now">Ir a Comprar</a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($total_ordenes > 10): ?>
                                <!-- Pagination -->
                                <div class="pagination-wrapper" data-aos="fade-up">
                                    <button type="button" class="btn-prev" disabled>
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <div class="page-numbers">
                                        <button type="button" class="active">1</button>
                                        <button type="button">2</button>
                                        <button type="button">3</button>
                                        <?php if ($total_ordenes > 30): ?>
                                            <span>...</span>
                                            <button type="button"><?php echo ceil($total_ordenes / 10); ?></button>
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="btn-next">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- /Account Section -->

<style>
    /* Estilos para la pestaña de Datos de Cuenta */
    .account-details-form {
        background-color: #222;
        border-radius: 16px;
        padding: 24px;
        margin-top: 20px;
    }

    .account-details-form .form-group {
        margin-bottom: 20px;
    }

    .account-details-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #fff;
    }

    .account-details-form label .text-danger {
        color: #ef4444;
    }

    .account-details-form .form-control {
        background-color: #333;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: #fff;
        padding: 12px 16px;
        height: auto;
        transition: all 0.3s ease;
    }

    .account-details-form .form-control:focus {
        border-color: #104d43;
        box-shadow: 0 0 0 3px rgba(16, 77, 67, 0.2);
        outline: none;
    }

    .account-details-form .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .account-details-form .form-control.is-invalid {
        border-color: #ef4444;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ef4444' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 24px 24px;
        padding-right: 40px;
    }

    .account-details-form .btn-primary {
        background-color: #104d43;
        border-color: #104d43;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .account-details-form .btn-primary:hover {
        background-color: #0e443b;
        border-color: #0e443b;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 77, 67, 0.2);
    }

    .account-details-form .btn-primary:active {
        transform: translateY(0);
    }

    .account-details-form .btn-primary i {
        margin-right: 8px;
    }

    .account .section-header p {
        color: #aaa;
        margin-top: 5px;
    }

    /* Animaciones para los mensajes de alerta */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Estilos para SweetAlert personalizado */
    .swal2-popup {
        background-color: #222 !important;
        border-radius: 16px !important;
        color: #fff !important;
    }

    .swal2-title {
        color: #fff !important;
    }

    .swal2-html-container {
        color: #ccc !important;
    }

    .swal2-confirm {
        background-color: #104d43 !important;
    }

    .swal2-styled.swal2-confirm:focus {
        box-shadow: 0 0 0 3px rgba(16, 77, 67, 0.5) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si hay un resultado de actualización para mostrar notificación
        <?php if ($update_result): ?>
            <?php if ($update_result['success']): ?>
                // Mostrar SweetAlert de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Datos actualizados!',
                    text: '<?php echo $update_result['message']; ?>',
                    confirmButtonText: 'Aceptar',
                    timer: 3000,
                    timerProgressBar: true
                });
            <?php else: ?>
                // Mostrar SweetAlert de error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo $update_result['message']; ?>',
                    confirmButtonText: 'Entendido'
                });
            <?php endif; ?>
        <?php endif; ?>

        // Filtrado de órdenes
        const filterLinks = document.querySelectorAll('.dropdown-item[data-filter]');
        const orderCards = document.querySelectorAll('.order-card');

        filterLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const filter = this.getAttribute('data-filter');

                orderCards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-status') === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Búsqueda de órdenes
        const searchInput = document.getElementById('searchOrders');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                orderCards.forEach(card => {
                    const orderText = card.textContent.toLowerCase();
                    if (orderText.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Manejo de pestañas
        const tabLinks = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]');
        const tabContents = document.querySelectorAll('.tab-pane');

        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Remover clase active de todos los enlaces
                tabLinks.forEach(l => l.classList.remove('active'));

                // Añadir clase active al enlace actual
                this.classList.add('active');

                // Obtener el id del contenido de la pestaña
                const tabId = this.getAttribute('href');

                // Ocultar todos los contenidos de pestañas
                tabContents.forEach(content => {
                    content.classList.remove('show', 'active');
                });

                // Mostrar el contenido de la pestaña seleccionada
                document.querySelector(tabId).classList.add('show', 'active');
            });
        });

        // Validación de formulario
        const accountForm = document.getElementById('accountDetailsForm');
        if (accountForm) {
            accountForm.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredFields = ['dni', 'telefono', 'direccion', 'ciudad', 'provincia', 'codigo_postal'];

                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();

                    // Mostrar SweetAlert de error de validación
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, completa todos los campos obligatorios marcados con *',
                        confirmButtonText: 'Entendido'
                    });
                } else {
                    // Mostrar indicador de carga mientras se procesa el formulario
                    Swal.fire({
                        title: 'Guardando cambios...',
                        text: 'Por favor espera mientras actualizamos tus datos',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
            });
        }
    });
</script>