<?php
// Incluir la conexión a la base de datos
include_once 'db_connection.php';

// Consulta para obtener todas las ventas - Ordenadas por ID descendente
$sql = "SELECT v.id_venta, v.nombreyapellido_cliente, v.email_cliente, v.telefono_cliente, 
        v.fecha_venta, v.total_venta, v.estado, 
        COUNT(dv.id_detalle) as cantidad_productos
        FROM ventas v
        LEFT JOIN detalle_ventas dv ON v.id_venta = dv.id_venta
        GROUP BY v.id_venta
        ORDER BY v.id_venta DESC"; // Cambiado a ordenar por ID descendente

$result = $db->query($sql);

if (!$result) {
    die("Error en la consulta: " . $db->error);
}
?>

<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered" id="tabla-ventas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Contacto</th>
                <th>Productos</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    // Formatear fecha
                    $fecha = new DateTime($row['fecha_venta']);
                    $fechaFormateada = $fecha->format('d/m/Y H:i');

                    // Determinar clase de estado
                    $estadoClass = '';
                    switch ($row['estado']) {
                        case 'Pendiente':
                            $estadoClass = 'estado-pendiente';
                            break;
                        case 'En Proceso':
                            $estadoClass = 'estado-en-proceso';
                            break;
                        case 'Enviado':
                            $estadoClass = 'estado-enviado';
                            break;
                        case 'Entregado':
                            $estadoClass = 'estado-entregado';
                            break;
                        case 'Cancelado':
                            $estadoClass = 'estado-cancelado';
                            break;
                        default:
                            $estadoClass = 'estado-pendiente';
                            break;
                    }
            ?>
                    <tr>
                        <td><?php echo $row['id_venta']; ?></td>
                        <td><?php echo $fechaFormateada; ?></td>
                        <td><?php echo htmlspecialchars($row['nombreyapellido_cliente']); ?></td>
                        <td>
                            <?php if ($row['email_cliente']): ?>
                                <div><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($row['email_cliente']); ?></div>
                            <?php endif; ?>
                            <?php if ($row['telefono_cliente']): ?>
                                <div><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($row['telefono_cliente']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?php echo $row['cantidad_productos']; ?></td>
                        <td class="text-end">$<?php echo number_format($row['total_venta'], 0, ',', '.'); ?></td>
                        <td class="text-center">
                            <span class="estado-actual <?php echo $estadoClass; ?>"
                                data-bs-toggle="tooltip"
                                title="Clic para cambiar estado"
                                data-id="<?php echo $row['id_venta']; ?>"
                                data-estado="<?php echo $row['estado']; ?>"
                                style="cursor: pointer;">
                                <?php echo $row['estado']; ?>
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info ver-detalle" data-id="<?php echo $row['id_venta']; ?>">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                endwhile;
            else:
                ?>
                <tr>
                    <td colspan="8" class="text-center">No se encontraron ventas registradas.</td>
                </tr>
            <?php
            endif;
            ?>
        </tbody>
    </table>
</div>