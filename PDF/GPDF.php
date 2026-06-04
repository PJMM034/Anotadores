<?php
ob_start();
require '../Conexion/Conexion.php';
require 'fpdf/fpdf.php';

define('MONEDA', '$');

$trabajador = isset($_GET['trabajador']) ? $_GET['trabajador'] : '';

if(empty($trabajador)){
    echo 'Trabajador no especificado';
    exit;
}

// Traer todos los cortes del trabajador del día
$stmt = $Connection->prepare("SELECT cortes.*, producto.producto AS nom_producto, DATE_FORMAT(create_at, '%d/%m/%Y') AS fecha_corte, DATE_FORMAT(create_at, '%H:%i:%s') AS hora
    FROM cortes INNER JOIN producto ON cortes.producto = producto.id WHERE cortes.trabajador = ? AND DATE(create_at) = CURDATE() ORDER BY create_at ASC");
$stmt->bind_param("s", $trabajador);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows == 0){
    echo 'No hay cortes hoy para este trabajador';
    exit;
}

$cortes = $resultado->fetch_all(MYSQLI_ASSOC);
$fecha  = $cortes[0]['fecha_corte'];

// Total general
$totalGeneral   = 0;
$totalKg        = 0;
foreach($cortes as $c){
    $totalGeneral += (float)$c['total'];
    $totalKg      += (float)$c['cantidad'];
}

ob_end_clean();

$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);

// Título
$pdf->SetFont('Arial', 'B', 10);
$pdf->Ln(5);
$pdf->MultiCell(70, 5, 'El Corte del dia', 0, 'C');
$pdf->Ln(1);

// Trabajador
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(22, 5, 'Trabajador:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(48, 5, mb_convert_encoding($trabajador, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

// Fecha
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(22, 5, 'Fecha:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(48, 5, $fecha, 0, 1, 'L');

// Línea
$pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');

// Encabezados
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10, 4, 'Cant.', 0, 0, 'L');
$pdf->Cell(25, 4, mb_convert_encoding('Producto', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->Cell(20, 4, 'Valor/kg', 0, 0, 'C');
$pdf->Cell(15, 4, 'Total', 0, 1, 'R');

// Línea
$pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');

// Filas de cortes
$pdf->SetFont('Arial', '', 7);
foreach($cortes as $c){
    $pdf->Cell(10, 4, number_format($c['cantidad'], 2), 0, 0, 'L');

    $yInicio = $pdf->GetY();
    $pdf->MultiCell(25, 4, mb_convert_encoding($c['nom_producto'], 'ISO-8859-1', 'UTF-8'), 0, 'L');
    $yFin = $pdf->GetY();

    $pdf->SetXY(40, $yInicio);
    $pdf->Cell(20, 4, MONEDA . ' ' . number_format($c['valor'], 2, '.', ','), 0, 0, 'C');
    $pdf->Cell(15, 4, MONEDA . ' ' . number_format($c['total'], 2, '.', ','), 0, 1, 'R');
    $pdf->SetY($yFin);
}

// Línea
$pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');

// Totales
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 4, mb_convert_encoding('Total kg: ' . number_format($totalKg, 2), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(70, 5, sprintf('Total: %s %s', MONEDA, number_format($totalGeneral, 2, '.', ',')), 0, 1, 'R');

$pdf->Ln(2);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(35, 5, 'Fecha: ' . $fecha, 0, 0, 'L');
$pdf->Cell(35, 5, 'Hora: ' . $cortes[0]['hora'], 0, 1, 'R');

$pdf->Ln(3);
$pdf->MultiCell(70, 5, mb_convert_encoding('¡Gracias!', 'ISO-8859-1', 'UTF-8'), 0, 'C');

$stmt->close();
$Connection->close();
$pdf->Output();
?>