<?php
require_once "../Conexion/Conexion.php";

$id_t = (int)($_POST['id_t'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$puesto = trim($_POST['puesto'] ?? '');
$estado = trim($_POST['estado'] ?? '');

if ($id_t <= 0 || $nombre == '' || $puesto == '' || $estado == '') {
    echo json_encode(['ok' => false, 'msg' => 'Datos inválidos']);
    exit;
}

$row = $Connection->prepare("UPDATE trabajadores SET nombre=?, puesto=?, estado=? WHERE id_t=?");
$row->bind_param("sssi", $nombre, $puesto, $estado, $id_t);
$row->execute();

echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
?>