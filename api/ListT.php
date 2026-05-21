<?php
require_once '../Conexion/Conexion.php';

$res = $Connection->query("SELECT * FROM trabajadores order by id_t desc");
$data = [];
// recorremos los resultados y los almacenamos en un array
while($row = $res->fetch_assoc()){
    $row['id_t'] = (int)$row['id_t'];
    $data[] = $row;
}
echo json_encode(['ok'=> true , 'data'=>$data, JSON_UNESCAPED_UNICODE]);

?>