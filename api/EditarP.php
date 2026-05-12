<?php
require_once "../Conexion/Conexion.php";

$id = (int)($_POST['id'] ?? 0);
$producto  = trim($_POST['producto']  ?? '');
$unidad = trim($_POST['unidad'] ?? '');
$valor      = trim($_POST['valor']      ?? '');

if ($id <= 0 || $producto == '' || $unidad == '' || $valor == '') {
    echo json_encode(['ok' => false, 'msg' => 'Datos inválidos']);
    exit;
}

$row = $Connection->prepare("UPDATE producto SET producto=?, unidad=?, valor=? WHERE id=?");
$row->bind_param("sssi", $producto, $unidad, $valor, $id);
$row->execute();

echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
?>