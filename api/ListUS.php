<?php
require_once '../Conexion/Conexion.php';

$res = $Connection->prepare("SELECT id_u, usuario FROM usuarios WHERE rol = 'ANOTADOR'");
$res->execute();
$result = $res->get_result();

$data = [];
while($row = $result->fetch_assoc()){
    $row['id_u'] = (int)$row['id_u'];
    $data[] = $row;
}

echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
?>