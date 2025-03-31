<?php
// Evitar cualquier salida antes del JSON
ob_start();

// Incluir la conexión a la base de datos
require_once '../includes/db_connection.php';

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se recibieron los datos necesarios
    if (isset($_POST['id_venta']) && isset($_POST['nuevo_estado'])) {
        $id_venta = intval($_POST['id_venta']);
        $nuevo_estado = $_POST['nuevo_estado'];
        
        // Validar que el estado sea uno de los permitidos
        $estados_permitidos = ['Pendiente', 'En Proceso', 'Enviado', 'Entregado', 'Cancelado'];
        
        if (in_array($nuevo_estado, $estados_permitidos)) {
            // Preparar la consulta SQL para actualizar el estado
            $sql = "UPDATE ventas SET estado = ? WHERE id_venta = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("si", $nuevo_estado, $id_venta);
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Limpiar cualquier salida anterior
                ob_clean();
                
                // Éxito
                echo json_encode([
                    'success' => true,
                    'message' => 'Estado actualizado correctamente',
                    'nuevo_estado' => $nuevo_estado
                ]);
            } else {
                // Limpiar cualquier salida anterior
                ob_clean();
                
                // Error en la ejecución
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al actualizar el estado: ' . $db->error
                ]);
            }
            
            $stmt->close();
        } else {
            // Limpiar cualquier salida anterior
            ob_clean();
            
            // Estado no válido
            echo json_encode([
                'success' => false,
                'message' => 'Estado no válido'
            ]);
        }
    } else {
        // Limpiar cualquier salida anterior
        ob_clean();
        
        // Datos incompletos
        echo json_encode([
            'success' => false,
            'message' => 'Faltan datos requeridos'
        ]);
    }
} else {
    // Limpiar cualquier salida anterior
    ob_clean();
    
    // Método no permitido
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}

// Finalizar la salida y evitar cualquier salida adicional
exit;
?>