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
        // Validar que los datos necesarios estén presentes
        if (!isset($datos['cliente_id']) || !isset($datos['nombre']) || !isset($datos['total_venta']) || !isset($datos['productos']) || !is_array($datos['productos'])) {
            throw new Exception("Faltan datos obligatorios para procesar la compra");
        }
        
        $cliente_id = $datos['cliente_id'];
        $nombre = sanitizeString($datos['nombre']);
        $email = isset($datos['email']) ? sanitizeString($datos['email']) : '';
        $telefono = isset($datos['telefono']) ? sanitizeString($datos['telefono']) : '';
        $dnicuit = isset($datos['dnicuit']) ? sanitizeString($datos['dnicuit']) : '';
        $direccion_completa = isset($datos['direccion_completa']) ? sanitizeString($datos['direccion_completa']) : '';
        $total_venta = floatval($datos['total_venta']);
        $metodo_pago = isset($datos['metodo_pago']) ? sanitizeString($datos['metodo_pago']) : 'No especificado';
        $notas = isset($datos['notas']) ? sanitizeString($datos['notas']) : '';
        $origen_venta = 'cliente';
        
        // Calcular el total original y el descuento
        $total_original = $total_venta;
        $descuento_valor = 0;
        
        // Aplicar descuento del 20% si el método de pago es transferencia bancaria
        if ($metodo_pago === 'Transferencia Bancaria') {
            $descuento_valor = round($total_venta * 0.20, 2); // 20% de descuento, redondeado a 2 decimales
            $total_venta = $total_venta - $descuento_valor;
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
        
        if (!$stmt_venta->execute()) {
            throw new Exception("Error al insertar la venta: " . $stmt_venta->error);
        }
        
        // Obtener el ID de la venta insertada
        $id_venta = $db->insert_id;
        
        if ($id_venta <= 0) {
            throw new Exception("No se pudo obtener el ID de la venta");
        }
        
        // Procesar los productos del carrito
        $productos = $datos['productos'];
        
        if (empty($productos)) {
            throw new Exception("No hay productos en el carrito");
        }

        // Verificar si la columna indicaciones existe en la tabla
        $check_column = $db->query("SHOW COLUMNS FROM detalle_ventas LIKE 'indicaciones'");
        $has_indications_column = $check_column->num_rows > 0;
        
        foreach ($productos as $producto) {
            if (!isset($producto['id']) || !isset($producto['quantity']) || !isset($producto['price'])) {
                continue; // Saltar productos con datos incompletos
            }
            
            $id_producto = intval($producto['id']);
            $cantidad = intval($producto['quantity']);
            $precio = floatval($producto['price']);
            $subtotal = $precio * $cantidad;
            
            // Verificar si hay indicaciones y sanitizarlas
            $indicaciones = '';
            if (isset($producto['indications'])) {
                $indicaciones = sanitizeString($producto['indications']);
            }
            
            // Verificar que el producto exista
            $check_producto = $db->prepare("SELECT id_producto FROM productos WHERE id_producto = ?");
            $check_producto->bind_param("i", $id_producto);
            $check_producto->execute();
            $result_producto = $check_producto->get_result();
            
            if ($result_producto->num_rows === 0) {
                throw new Exception("El producto con ID $id_producto no existe");
            }
            
            // Insertar detalle de venta
            if ($has_indications_column) {
                $query_detalle = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario, subtotal, indicaciones) 
                                VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_detalle = $db->prepare($query_detalle);
                $stmt_detalle->bind_param("iiidds", $id_venta, $id_producto, $cantidad, $precio, $subtotal, $indicaciones);
            } else {
                $query_detalle = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario, subtotal) 
                                VALUES (?, ?, ?, ?, ?)";
                $stmt_detalle = $db->prepare($query_detalle);
                $stmt_detalle->bind_param("iiidd", $id_venta, $id_producto, $cantidad, $precio, $subtotal);
            }
            
            if (!$stmt_detalle->execute()) {
                throw new Exception("Error al insertar el detalle de venta: " . $stmt_detalle->error);
            }
            
            // Actualizar stock del producto solo si tiene stock definido
            $query_check_stock = "SELECT stock FROM productos WHERE id_producto = ?";
            $stmt_check_stock = $db->prepare($query_check_stock);
            $stmt_check_stock->bind_param("i", $id_producto);
            $stmt_check_stock->execute();
            $result_stock = $stmt_check_stock->get_result();
            $producto_stock = $result_stock->fetch_assoc();
            
            // Solo actualizar si el stock no es NULL
            if ($producto_stock && $producto_stock['stock'] !== null) {
                $query_stock = "UPDATE productos SET stock = stock - ? WHERE id_producto = ?";
                $stmt_stock = $db->prepare($query_stock);
                $stmt_stock->bind_param("ii", $cantidad, $id_producto);
                
                if (!$stmt_stock->execute()) {
                    throw new Exception("Error al actualizar el stock: " . $stmt_stock->error);
                }
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
        
        // Registrar el error en un archivo de log
        error_log("Error en procesarCompra: " . $e->getMessage());
        
        return [
            'success' => false,
            'message' => "Error al procesar la compra: " . $e->getMessage()
        ];
    }
}