<?php
require_once '../Conexion/Conexion.php';

$res = $Connection->query("SELECT * FROM usuarios ORDER BY id_u DESC");
$data = [];
// recorremos los resultados y los almacenamos en un array
while($row = $res->fetch_assoc()){
    $row['id_u'] = (int)$row['id_u'];
    $data[] = $row;
}
echo json_encode(['ok'=> true , 'data'=>$data, JSON_UNESCAPED_UNICODE]);
?>