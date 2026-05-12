<?php
require_once "../Conexion/Conexion.php";

$id_c = (int)($_POST['id_c'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$id_producto = trim($_POST['id_producto'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$numero = trim($_POST['numero'] ?? '');
$estado = trim($_POST['estado'] ?? '');

if ($id_c <= 0 || $nombre == '' || $id_producto == '' || $tipo == '' || $numero == '' || $estado == '') {
    echo json_encode(['ok' => false, 'msg' => 'Datos inválidos']);
    exit;
}

$row = $Connection->prepare("UPDATE campos SET nombre=?, id_producto=?, tipo=?, numero=?, estado=? WHERE id_c=?");
$row->bind_param("ssssii", $nombre, $id_producto, $tipo, $numero, $estado, $id_c);
$row->execute();

echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
?>