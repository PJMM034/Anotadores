<?php
ob_start();
require '../Conexion/Conexion.php';
require 'fpdf/fpdf.php';

define('MONEDA', '$');

// Recibimos el nombre del rancho por la URL
$rancho_param = $_GET['rancho'] ?? '';

if(empty($rancho_param)){
    echo 'Rancho no especificado';
    exit;
}

/* * CONSULTA: 
 * Unimos cortes, producto y campos. 
 * OJO: Verifica que la columna en tu tabla 'cortes' que se conecta con la tabla campos 
 * se llame 'id_campo'. Si se llama distinto, cámbialo en la línea de INNER JOIN campos.
 */
$stmt = $Connection->prepare("SELECT cortes.*, producto.producto AS nom_producto, producto.unidad AS unidad, 
DATE_FORMAT(cortes.create_at, '%d/%m/%Y') AS fecha_corte, campos.numero AS nombre_campo FROM cortes 
INNER JOIN producto ON cortes.producto = producto.id INNER JOIN campos ON cortes.campo = campos.id_c 
WHERE campos.nombre = ? ORDER BY campos.numero ASC, cortes.create_at ASC");
$stmt->bind_param("s", $rancho_param);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows == 0){
    echo 'No hay cortes registrados para este rancho';
    exit;
}

$cortes = $resultado->fetch_all(MYSQLI_ASSOC);

// Agrupamos por el nombre/número del campo para estructurar el ticket
$campos_rancho = [];
foreach($cortes as $c){
    $campo = $c['nombre_campo'];
    if(!isset($campos_rancho[$campo])){
        $campos_rancho[$campo] = ['cortes' => [], 'total' => 0, 'cantidad' => 0];
    }
    $campos_rancho[$campo]['cortes'][]  = $c;
    $campos_rancho[$campo]['total']    += (float)$c['total'];
    $campos_rancho[$campo]['cantidad'] += (float)$c['cantidad'];
}

ob_end_clean();

$pdf = new FPDF('P', 'mm', array(80, 297));
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);

// Encabezado principal
$pdf->SetFont('Arial', 'B', 10);
$pdf->Ln(5);
$pdf->MultiCell(70, 5, mb_convert_encoding('Reporte de Rancho', 'ISO-8859-1', 'UTF-8'), 0, 'C');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(70, 5, mb_convert_encoding(strtoupper($rancho_param), 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Ln(2);

$totalGlobalGeneral  = 0;
$totalGlobalCantidad = 0;

// Iterar cada campo que pertenece a este rancho
foreach($campos_rancho as $nombre_campo => $datos){
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(70, 5, mb_convert_encoding('Campo: ' . $nombre_campo, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
    $pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');

    // Cabeceras de la mini-tabla
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(15, 4, 'Fecha', 0, 0, 'L');
    $pdf->Cell(12, 4, 'Cant.', 0, 0, 'L');
    $pdf->Cell(20, 4, 'Producto', 0, 0, 'L');
    $pdf->Cell(18, 4, 'Total', 0, 1, 'R');

    // Filas de cortes
    $pdf->SetFont('Arial', '', 7);
    foreach($datos['cortes'] as $c){
        $pdf->Cell(15, 4, $c['fecha_corte'], 0, 0, 'L');
        $pdf->Cell(12, 4, number_format($c['cantidad'], 2), 0, 0, 'L');
        
        $yInicio = $pdf->GetY();
        $pdf->MultiCell(20, 4, mb_convert_encoding($c['nom_producto'], 'ISO-8859-1', 'UTF-8'), 0, 'L');
        $yFin = $pdf->GetY();
        
        // Regresamos el cursor a la derecha para imprimir el total
        $pdf->SetXY(52, $yInicio);
        $pdf->Cell(18, 4, MONEDA . ' ' . number_format($c['total'], 2, '.', ','), 0, 1, 'R');
        $pdf->SetY($yFin);
    }

    $pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(35, 4, mb_convert_encoding('Subtotal cant: ' . number_format($datos['cantidad'], 2), 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
    $pdf->Cell(35, 4, 'Subtotal: ' . MONEDA . ' ' . number_format($datos['total'], 2, '.', ','), 0, 1, 'R');
    $pdf->Ln(3);

    $totalGlobalGeneral  += $datos['total'];
    $totalGlobalCantidad += $datos['cantidad'];
}

// Totales Finales del Rancho
$pdf->Cell(70, 2, '=========================================================================', 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(35, 5, mb_convert_encoding('Total Rancho Cant: ' . number_format($totalGlobalCantidad, 2), 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->Cell(35, 5, 'TOTAL: ' . MONEDA . ' ' . number_format($totalGlobalGeneral, 2, '.', ','), 0, 1, 'R');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(70, 5, mb_convert_encoding('¡Gracias!', 'ISO-8859-1', 'UTF-8'), 0, 'C');

$stmt->close();
$Connection->close();
$pdf->Output();
?>