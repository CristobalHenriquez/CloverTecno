<?php
include_once '../includes/auth.php';
requireAdmin();
include_once '../includes/db_connection.php';

// Verificar si se recibieron los filtros
$categoriaId = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$nivelStock = isset($_GET['nivel_stock']) ? $_GET['nivel_stock'] : '';

// Construir la consulta SQL base
$sql = "SELECT p.id_producto, p.nombre_producto, p.stock, c.nombre_categoria
        FROM productos p
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
        WHERE 1=1";

// Aplicar filtro de categoría si está seleccionado
if (!empty($categoriaId)) {
    $sql .= " AND p.id_categoria = " . intval($categoriaId);
}

// Aplicar filtro de nivel de stock si está seleccionado
if (!empty($nivelStock)) {
    switch ($nivelStock) {
        case 'alto':
            $sql .= " AND p.stock > 10";
            break;
        case 'medio':
            $sql .= " AND p.stock BETWEEN 6 AND 10";
            break;
        case 'bajo':
            $sql .= " AND p.stock BETWEEN 1 AND 5";
            break;
        case 'sin-stock':
            $sql .= " AND (p.stock IS NULL OR p.stock = 0)";
            break;
    }
}

// Ordenar por nombre de producto
$sql .= " ORDER BY p.nombre_producto";

// Ejecutar la consulta
$result = $db->query($sql);

if (!$result) {
    die("Error en la consulta: " . $db->error);
}

// Obtener el nombre de la categoría si se ha filtrado
$nombreCategoria = "Todas";
if (!empty($categoriaId)) {
    $stmtCategoria = $db->prepare("SELECT nombre_categoria FROM categorias WHERE id_categoria = ?");
    $stmtCategoria->bind_param("i", $categoriaId);
    $stmtCategoria->execute();
    $resultCategoria = $stmtCategoria->get_result();
    if ($resultCategoria->num_rows > 0) {
        $rowCategoria = $resultCategoria->fetch_assoc();
        $nombreCategoria = $rowCategoria['nombre_categoria'];
    }
    $stmtCategoria->close();
}

// Obtener el nombre del nivel de stock si se ha filtrado
$nombreNivelStock = "Todos";
if (!empty($nivelStock)) {
    switch ($nivelStock) {
        case 'alto':
            $nombreNivelStock = "Alto";
            break;
        case 'medio':
            $nombreNivelStock = "Medio";
            break;
        case 'bajo':
            $nombreNivelStock = "Bajo";
            break;
        case 'sin-stock':
            $nombreNivelStock = "Sin stock";
            break;
    }
}

// Generar el nombre del archivo
$fecha = date('Y-m-d');
$filtrosTexto = "Cat_" . str_replace(' ', '_', $nombreCategoria) . "_Nivel_" . str_replace(' ', '_', $nombreNivelStock);
$nombreArchivo = "InformeDeStock_" . $fecha . "_" . $filtrosTexto . ".xlsx";

// Crear el archivo Excel usando PhpSpreadsheet
require '../vendor/autoload.php'; // Asegúrate de tener PhpSpreadsheet instalado

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Informe de Stock');

// Establecer el título del informe
$sheet->setCellValue('A1', 'INFORME DE STOCK - ' . $fecha);
$sheet->mergeCells('A1:D1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Establecer los filtros aplicados
$sheet->setCellValue('A2', 'Categoría: ' . $nombreCategoria);
$sheet->setCellValue('C2', 'Nivel de Stock: ' . $nombreNivelStock);
$sheet->mergeCells('A2:B2');
$sheet->mergeCells('C2:D2');
$sheet->getStyle('A2:D2')->getFont()->setBold(true);

// Establecer los encabezados de la tabla
$sheet->setCellValue('A4', 'ID');
$sheet->setCellValue('B4', 'Producto');
$sheet->setCellValue('C4', 'Categoría');
$sheet->setCellValue('D4', 'Stock');

// Estilo para los encabezados
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '104D43'],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
$sheet->getStyle('A4:D4')->applyFromArray($headerStyle);

// Llenar la tabla con los datos
$row = 5;
while ($producto = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $producto['id_producto']);
    $sheet->setCellValue('B' . $row, $producto['nombre_producto']);
    $sheet->setCellValue('C' . $row, $producto['nombre_categoria'] ?? 'Sin categoría');
    $sheet->setCellValue('D' . $row, $producto['stock'] !== null ? $producto['stock'] : 'No disponible');
    
    // Aplicar estilo según el nivel de stock
    if ($producto['stock'] !== null) {
        $stockStyle = [];
        if ($producto['stock'] > 10) {
            $stockStyle = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D4EDDA'],
                ],
            ];
        } elseif ($producto['stock'] > 5) {
            $stockStyle = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFF3CD'],
                ],
            ];
        } else {
            $stockStyle = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F8D7DA'],
                ],
            ];
        }
        $sheet->getStyle('D' . $row)->applyFromArray($stockStyle);
    } else {
        $stockStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E3E5'],
            ],
        ];
        $sheet->getStyle('D' . $row)->applyFromArray($stockStyle);
    }
    
    $row++;
}

// Aplicar bordes a toda la tabla
$tableBorderStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];
$sheet->getStyle('A4:D' . ($row - 1))->applyFromArray($tableBorderStyle);

// Ajustar el ancho de las columnas
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(50);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(15);

// Configurar la salida del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombreArchivo . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;