<?php
session_start();
require_once '../Conexion/Conexion.php';

$id_u = (int)($_SESSION['id_u'] ?? 0);

if($id_u <= 0){
    echo json_encode(['ok' => false, 'msg' => 'Usuario no válido']);
    exit;
}

$filtro   = $_GET['filtro']  ?? 'hoy';
$fecha    = $_GET['fecha']   ?? '';
$semana   = $_GET['semana']  ?? '';

// aqui decido que fecha buscar segun el filtro
if($filtro == 'dia' && $fecha != ''){

    $where_fecha = "AND DATE(c.create_at) = '$fecha'";

} else if($filtro == 'semana' && $semana != ''){
    $partes = explode('-W', $semana);
    $anio   = (int)$partes[0];
    $nsemana = (int)$partes[1];
    $where_fecha = "AND YEAR(c.create_at) = $anio AND WEEK(c.create_at, 1) = $nsemana";

} else {
    
    $where_fecha = "AND DATE(c.create_at) = CURDATE()";
}
$stmt = $Connection->prepare("SELECT c.*, producto.producto AS nombre_p, producto.unidad AS unidad ,campos.nombre AS camp 
FROM cortes c JOIN producto ON c.producto = producto.id JOIN campos ON c.campo = campos.id_c WHERE c.id_u = ? $where_fecha 
ORDER BY c.create_at DESC");
$stmt->bind_param("i", $id_u);
$stmt->execute();
$res  = $stmt->get_result();
$data = $res->fetch_all(MYSQLI_ASSOC);

echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
?>