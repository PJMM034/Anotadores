<?php
require_once '../Conexion/Conexion.php';

$res = $Connection->query("SELECT * FROM producto order by id desc");
$data = [];
// recorremos los resultados y los almacenamos en un array
while($row = $res->fetch_assoc()){
    $row['id'] = (int)$row['id'];
    $data[] = $row;
}
echo json_encode(['ok'=> true , 'data'=>$data, JSON_UNESCAPED_UNICODE]);

?>