<?php

// Obtener información del cliente
function getClienteInfo($db, $cliente_id) {
    $query = "SELECT nombre_apellido, correo FROM users_clientes WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Obtener detalles completos del cliente (incluyendo dirección y datos personales)
function getClienteDetalles($db, $cliente_id) {
    // Primero obtenemos la información básica del cliente
    $cliente_info = getClienteInfo($db, $cliente_id);
    
    // Luego obtenemos los detalles adicionales
    $query = "SELECT * FROM detalle_users WHERE id_cliente = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Si existen detalles, los combinamos con la información básica
    if ($result->num_rows > 0) {
        $detalles = $result->fetch_assoc();
        return array_merge($cliente_info, $detalles);
    }
    
    // Si no hay detalles, devolvemos solo la información básica
    return $cliente_info;
}

// Función para sanitizar texto (reemplazo de FILTER_SANITIZE_STRING)
function sanitizeString($string) {
    // Eliminar etiquetas HTML y PHP
    $string = strip_tags($string);
    // Convertir caracteres especiales a entidades HTML
    $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    return $string;
}

// Procesar la compra
function procesarCompra($db, $datos) {
    // Iniciar transacción
    $db->begin_transaction();
    
    try {
        $cliente_id = $datos['cliente_id'];
        $nombre = $datos['nombre'];
        $email = $datos['email'];
        $telefono = $datos['telefono'];
        $dnicuit = $datos['dnicuit'];
        $direccion_completa = $datos['direccion_completa'];
        $total_venta = $datos['total_venta'];
        $metodo_pago = $datos['metodo_pago'];
        $notas = $datos['notas'];
        $origen_venta = 'cliente'; // Definimos esto como variable
        
        // Aplicar descuento del 20% si el método de pago es transferencia bancaria
        $total_original = $total_venta;
        
        if ($metodo_pago === 'Transferencia Bancaria') {
            $descuento_valor = $total_venta * 0.20; // 20% de descuento
            $total_venta = $total_venta - $descuento_valor;
        } else {
            $descuento_valor = 0;
        }
        
        // Verificar la estructura de la tabla ventas
        $check_table = $db->query("SHOW COLUMNS FROM ventas LIKE 'descuento'");
        $has_descuento_column = $check_table->num_rows > 0;
        
        $check_table = $db->query("SHOW COLUMNS FROM ventas LIKE 'total_original'");
        $has_total_original_column = $check_table->num_rows > 0;
        
        // Preparar la consulta SQL según las columnas disponibles
        if ($has_descuento_column && $has_total_original_column) {
            // Si existen ambas columnas
            $query_venta = "INSERT INTO ventas (id_cliente, origen_venta, nombreyapellido_cliente, email_cliente, telefono_cliente, dnicuit_cliente, domicilio_cliente, total_venta, metodo_pago, notas, descuento, total_original) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_venta = $db->prepare($query_venta);
            $stmt_venta->bind_param("issssssdssdd", 
                $cliente_id, 
                $origen_venta,
                $nombre, 
                $email, 
                $telefono, 
                $dnicuit, 
                $direccion_completa, 
                $total_venta, 
                $metodo_pago, 
                $notas,
                $descuento_valor,
                $total_original
            );
        } else {
            // Si no existen las columnas de descuento y total_original
            $query_venta = "INSERT INTO ventas (id_cliente, origen_venta, nombreyapellido_cliente, email_cliente, telefono_cliente, dnicuit_cliente, domicilio_cliente, total_venta, metodo_pago, notas) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_venta = $db->prepare($query_venta);
            $stmt_venta->bind_param("issssssdss", 
                $cliente_id, 
                $origen_venta,
                $nombre, 
                $email, 
                $telefono, 
                $dnicuit, 
                $direccion_completa, 
                $total_venta, 
                $metodo_pago, 
                $notas
            );
        }
        
        $stmt_venta->execute();
        
        // Obtener el ID de la venta insertada
        $id_venta = $db->insert_id;
        
        // Procesar los productos del carrito
        $productos = $datos['productos'];
        
        if (is_array($productos) && count($productos) > 0) {
            foreach ($productos as $producto) {
                $id_producto = $producto['id'];
                $cantidad = $producto['quantity'];
                $precio = $producto['price'];
                $subtotal = $precio * $cantidad;
                
                // Insertar detalle de venta
                $query_detalle = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario, subtotal) 
                                 VALUES (?, ?, ?, ?, ?)";
                $stmt_detalle = $db->prepare($query_detalle);
                $stmt_detalle->bind_param("iiddd", $id_venta, $id_producto, $cantidad, $precio, $subtotal);
                $stmt_detalle->execute();
                
                // Actualizar stock del producto
                $query_stock = "UPDATE productos SET stock = stock - ? WHERE id_producto = ?";
                $stmt_stock = $db->prepare($query_stock);
                $stmt_stock->bind_param("ii", $cantidad, $id_producto);
                $stmt_stock->execute();
            }
        }
        
        // Confirmar la transacción
        $db->commit();
        
        // Datos bancarios para transferencia
        $datos_bancarios = [
            'banco' => 'Banco Nación',
            'titular' => 'Clover Tecno S.A.',
            'cbu' => '0110000000000000000000',
            'cuit' => '30-12345678-9',
            'whatsapp' => '5493416578661'
        ];
        
        return [
            'success' => true,
            'id_venta' => $id_venta,
            'metodo_pago' => $metodo_pago,
            'datos_bancarios' => $datos_bancarios,
            'total_venta' => $total_venta,
            'descuento' => $descuento_valor,
            'total_original' => $total_original
        ];
        
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $db->rollback();
        return [
            'success' => false,
            'message' => "Error al procesar la compra: " . $e->getMessage()
        ];
    }
}