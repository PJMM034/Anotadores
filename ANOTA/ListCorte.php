<?php
session_start();
require_once '../Conexion/Conexion.php';

$id_u = (int)($_SESSION['id_u'] ?? 0);

if($id_u <= 0){
    echo json_encode(['ok' => false, 'msg' => 'Usuario no válido']);
    exit;
}

$stmt = $Connection->prepare("SELECT c.*, producto.producto AS nombre_p, campos.nombre AS camp FROM cortes c JOIN producto ON c.producto = producto.id JOIN campos ON c.campo = campos.id_c WHERE c.id_u = ? AND DATE(c.create_at) = CURDATE() ORDER BY c.create_at DESC");
$stmt->bind_param("i", $id_u);
$stmt->execute();
$res  = $stmt->get_result();
$data = $res->fetch_all(MYSQLI_ASSOC);

echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
?>