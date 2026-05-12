<?php
require_once '../Conexion/Conexion.php';

$res = $Connection->query("SELECT campos.*, producto.producto AS nombre_producto FROM campos
 INNER JOIN producto  ON campos.id_producto = producto.id ORDER BY id_c DESC");
$data = [];
// recorremos los resultados y los almacenamos en un array
while($row = $res->fetch_assoc()){
    $row['id_c'] = (int)$row['id_c'];
    $row['id_producto'] = (int)$row['id_producto'];
    $data[] = $row;
}
echo json_encode(['ok'=> true , 'data'=>$data, JSON_UNESCAPED_UNICODE]);

?>