<?php
// Controlador para manejar las operaciones de la cuenta de usuario

// Función para obtener los detalles del usuario
function getUserDetails($db, $cliente_id) {
    // Primero, verificamos si el usuario ya tiene detalles en la tabla
    $query = "SELECT * FROM detalle_users WHERE id_cliente = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        // Si no tiene detalles, devolvemos un array vacío
        return [
            'dni' => '',
            'telefono' => '',
            'direccion' => '',
            'departamento' => '',
            'ciudad' => '',
            'provincia' => '',
            'codigo_postal' => ''
        ];
    }
}

// Función para actualizar los detalles del usuario
function updateUserDetails($db, $cliente_id, $datos) {
    // Verificamos si el usuario ya tiene un registro en la tabla detalle_users
    $check_query = "SELECT id FROM detalle_users WHERE id_cliente = ?";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bind_param("i", $cliente_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Si ya existe, actualizamos
        $query = "UPDATE detalle_users SET 
                  dni = ?, 
                  telefono = ?, 
                  direccion = ?, 
                  departamento = ?, 
                  ciudad = ?, 
                  provincia = ?, 
                  codigo_postal = ?, 
                  updated_at = NOW() 
                  WHERE id_cliente = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param(
            "sssssssi",
            $datos['dni'],
            $datos['telefono'],
            $datos['direccion'],
            $datos['departamento'],
            $datos['ciudad'],
            $datos['provincia'],
            $datos['codigo_postal'],
            $cliente_id
        );
    } else {
        // Si no existe, insertamos
        $query = "INSERT INTO detalle_users 
                  (id_cliente, dni, telefono, direccion, departamento, ciudad, provincia, codigo_postal) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param(
            "isssssss",
            $cliente_id,
            $datos['dni'],
            $datos['telefono'],
            $datos['direccion'],
            $datos['departamento'],
            $datos['ciudad'],
            $datos['provincia'],
            $datos['codigo_postal']
        );
    }
    
    // Ejecutamos la consulta
    if ($stmt->execute()) {
        return [
            'success' => true,
            'message' => 'Datos actualizados correctamente'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Error al actualizar los datos: ' . $db->error
        ];
    }
}

// Función para sanitizar texto (reemplazo de FILTER_SANITIZE_STRING)
function sanitizeString($string) {
    // Eliminar etiquetas HTML y PHP
    $string = strip_tags($string);
    // Convertir caracteres especiales a entidades HTML
    $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    return $string;
}
