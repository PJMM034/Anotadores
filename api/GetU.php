<?php
require_once "../Conexion/Conexion.php";

$id_u = (int)($_GET['id_u'] ?? 0);
$hola = $Connection->prepare("SELECT * FROM usuarios WHERE id_u = ? LIMIT 1");
$hola->bind_param("i", $id_u);
$hola->execute();
$res = $hola->get_result();
$row = $res->fetch_assoc();
if (!$row){
    echo json_encode(['ok' => false, 'msg' => 'Registro no encontrado']);
    exit;
}
$row['id_u'] = (int)$row['id_u'];
echo json_encode(['ok'=> true, 'data'=>$row, JSON_UNESCAPED_UNICODE]);
?>