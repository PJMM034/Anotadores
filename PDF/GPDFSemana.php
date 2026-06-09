<?php
ob_start();
require '../Conexion/Conexion.php';
require 'fpdf/fpdf.php';
// valido que el usaurios este logeeado para cada quiem tenga si pfd
require_once '../logins/check.php';
session_start();
$id_u = $_SESSION['id_u'];

define('MONEDA', '$');

// aquie recibo en semasna 
$semana_param = $_GET['semana'] ?? '';

// valido formato YYYY-Wnn
if(!preg_match('/^\d{4}-W\d{2}$/', $semana_param)){
    $semana_param = date('Y') . '-W' . date('W');
}

// aqui saco el año y la semana para hacer la consulta
$partes  = explode('-W', $semana_param);
$anio    = (int)$partes[0];
$nsemana = (int)$partes[1];

$stmt = $Connection->prepare("SELECT cortes.trabajador, SUM(cortes.cantidad) AS total_cantidad, SUM(cortes.total) AS total_trabajador,
    DATE_FORMAT(MIN(cortes.create_at), '%d/%m/%Y') AS fecha_corte, DATE(cortes.create_at) AS fecha_orden
    FROM cortes WHERE YEAR(cortes.create_at) = ? AND WEEK(cortes.create_at, 1) = ? AND cortes.id_u = ?
    GROUP BY fecha_orden, cortes.trabajador ORDER BY cortes.trabajador ASC, fecha_orden ASC");
$stmt->bind_param("iii", $anio, $nsemana, $id_u);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows == 0){
    echo 'No hay cortes esta semana';
    exit;
}

$cortes = $resultado->fetch_all(MYSQLI_ASSOC);

// agrupar por trabajador y luego por fecha
$porTrabajador = [];
foreach($cortes as $c){
    $nom = $c['trabajador'];
    if(!isset($porTrabajador[$nom])){
        $porTrabajador[$nom] = ['fechas' => [], 'total_trabajador' => 0, 'cantidad_trabajador' => 0];
    }
    $porTrabajador[$nom]['fechas'][$c['fecha_corte']] = [
        'total'    => (float)$c['total_trabajador'],
        'cantidad' => (float)$c['total_cantidad']
    ];
    $porTrabajador[$nom]['total_trabajador']    += (float)$c['total_trabajador'];
    $porTrabajador[$nom]['cantidad_trabajador'] += (float)$c['total_cantidad'];
}

$totalSemanaGeneral  = 0;
$cantidadSemanaTotal = 0;

ob_end_clean();

$pdf = new FPDF('P', 'mm', array(80, 297));
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);

// titulo con el rango de la semana
$pdf->SetFont('Arial', 'B', 10);
$pdf->Ln(5);
$pdf->MultiCell(70, 5, 'Cortes de la semana', 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 4, mb_convert_encoding("Semana $nsemana — $anio", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Ln(2);

foreach($porTrabajador as $nombre => $datos){
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, 5, mb_convert_encoding('Trabajador: ' . strtoupper($nombre), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
    $pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(35, 4, mb_convert_encoding('Total cant: ' . number_format($datos['cantidad_trabajador'], 2), 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
    $pdf->Cell(35, 4, 'Total: ' . MONEDA . ' ' . number_format($datos['total_trabajador'], 2, '.', ','), 0, 1, 'R');
    $pdf->Ln(4);

    $totalSemanaGeneral  += $datos['total_trabajador'];
    $cantidadSemanaTotal += $datos['cantidad_trabajador'];
}

$pdf->Cell(70, 2, '=========================================================================', 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(35, 5, mb_convert_encoding('Total semana cant: ' . number_format($cantidadSemanaTotal, 2), 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->Cell(35, 5, 'TOTAL: ' . MONEDA . ' ' . number_format($totalSemanaGeneral, 2, '.', ','), 0, 1, 'R');
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(70, 5, mb_convert_encoding('¡Gracias!', 'ISO-8859-1', 'UTF-8'), 0, 'C');

$stmt->close();
$Connection->close();
$pdf->Output();
?>