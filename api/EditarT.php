<?php
require_once "../Conexion/Conexion.php";

$id_t = (int)($_POST['id_t'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$estado = trim($_POST['estado'] ?? '');

if ($id_t <= 0 || $nombre == '' || $estado == '') {
    echo json_encode(['ok' => false, 'msg' => 'Datos inválidos']);
    exit;
}

$row = $Connection->prepare("UPDATE trabajadores SET nombre=?, estado=? WHERE id_t=?");
$row->bind_param("ssi", $nombre, $estado, $id_t);
$row->execute();

echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
?>