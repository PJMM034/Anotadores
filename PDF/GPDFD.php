<?php
ob_start();
require '../Conexion/Conexion.php';
require 'fpdf/fpdf.php';
// valido que el usaurios este logeeado para cada quiem tenga si pfd
require_once '../logins/check.php';
session_start();
$id_u = $_SESSION['id_u'];


define('MONEDA', '$');

// recibo la fecha, si no viene uso hoy
date_default_timezone_set('America/Mazatlan');
$fecha_param = $_GET['fecha'] ?? date('Y-m-d');

// se valida en  formato mes dia y año, si viene raro uso hoy
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_param)){
    $fecha_param = date('Y-m-d');
}

$stmt = $Connection->prepare("SELECT cortes.*, producto.producto AS nom_producto, producto.unidad AS unidad,
    DATE_FORMAT(create_at, '%d/%m/%Y') AS fecha_corte, DATE_FORMAT(create_at, '%H:%i:%s') AS hora 
    FROM cortes INNER JOIN producto ON cortes.producto = producto.id  WHERE DATE(create_at) = ? AND cortes.id_u = ?  ORDER BY cortes.trabajador ASC, create_at ASC");
$stmt->bind_param("si", $fecha_param, $id_u);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows == 0){
    echo 'No hay cortes para esta fecha';
    exit;
}

$cortes = $resultado->fetch_all(MYSQLI_ASSOC);
$fecha  = $cortes[0]['fecha_corte'];

$trabajadores = [];
foreach($cortes as $c){
    $nom = $c['trabajador'];
    if(!isset($trabajadores[$nom])){
        $trabajadores[$nom] = ['cortes' => [], 'total' => 0, 'cantidad' => 0];
    }
    $trabajadores[$nom]['cortes'][]  = $c;
    $trabajadores[$nom]['total']    += (float)$c['total'];
    $trabajadores[$nom]['cantidad'] += (float)$c['cantidad'];
}

ob_end_clean();

$pdf = new FPDF('P', 'mm', array(80, 297));
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Ln(5);
$pdf->MultiCell(70, 5, 'Cortes del dia', 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 4, 'Fecha: ' . $fecha, 0, 1, 'C');
$pdf->Ln(2);

$totalGlobalGeneral  = 0;
$totalGlobalCantidad = 0;

foreach($trabajadores as $nombre => $datos){
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(70, 5, mb_convert_encoding('Trabajador: ' . strtoupper($nombre), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
    $pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');

    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(12, 4, 'Cant.', 0, 0, 'L');
    $pdf->Cell(23, 4, 'Producto', 0, 0, 'L');
    $pdf->Cell(20, 4, 'Valor/u', 0, 0, 'C');
    $pdf->Cell(15, 4, 'Total', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    foreach($datos['cortes'] as $c){
        $pdf->Cell(16, 4, number_format($c['cantidad'], 2) . ' ' . $c['unidad'], 0, 0, 'L');
        $yInicio = $pdf->GetY();
        $pdf->MultiCell(23, 4, mb_convert_encoding($c['nom_producto'], 'ISO-8859-1', 'UTF-8'), 0, 'L');
        $yFin = $pdf->GetY();
        $pdf->SetXY(40, $yInicio);
        $pdf->Cell(20, 4, MONEDA . ' ' . number_format($c['valor'], 2, '.', ','), 0, 0, 'C');
        $pdf->Cell(15, 4, MONEDA . ' ' . number_format($c['total'], 2, '.', ','), 0, 1, 'R');
        $pdf->SetY($yFin);
    }

    $pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(35, 4, mb_convert_encoding('Total cant: ' . number_format($datos['cantidad'], 2), 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
    $pdf->Cell(35, 4, 'Total: ' . MONEDA . ' ' . number_format($datos['total'], 2, '.', ','), 0, 1, 'R');
    $pdf->Ln(3);

    $totalGlobalGeneral  += $datos['total'];
    $totalGlobalCantidad += $datos['cantidad'];
}

$pdf->Cell(70, 2, '=========================================================================', 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(35, 5, mb_convert_encoding('Total general cant: ' . number_format($totalGlobalCantidad, 2), 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->Cell(35, 5, 'TOTAL: ' . MONEDA . ' ' . number_format($totalGlobalGeneral, 2, '.', ','), 0, 1, 'R');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(70, 5, mb_convert_encoding('¡Gracias!', 'ISO-8859-1', 'UTF-8'), 0, 'C');

$stmt->close();
$Connection->close();
$pdf->Output();
?>