<?php
session_start();
require_once "../Conexion/Conexion.php";

$id_u = (int)($_SESSION['id_u'] ?? 0);
if ($id_u <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'Usuario no válido']);
    exit;
}
$mo = $Connection->prepare("SELECT campos.id_c, campos.nombre as ubicacion, producto.id As id_producto, producto.producto, producto.valor, producto.unidad FROM campos JOIN producto ON campos.id_producto = producto.id  WHERE campos.id_u = ? AND campos.estado = 'Activo' LIMIT 1");
$mo->bind_param("i", $id_u);
$mo->execute();
$res = $mo->get_result();
$row = $res->fetch_assoc();

if (!$row){
    echo json_encode(['ok' => false, 'msg' => 'Registro no encontrado']);
    exit;
}
echo json_encode(['ok'=> true, 'data'=>$row, JSON_UNESCAPED_UNICODE]);

?>