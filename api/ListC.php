<?php
require_once '../Conexion/Conexion.php';

$por_pagina = 10;
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$inicio = ($pagina - 1) * $por_pagina;

$tol = $Connection->query("SELECT COUNT(*) AS total FROM campos")->fetch_assoc()['total'];
$total_P = ceil($tol / $por_pagina);


$res = $Connection->prepare("SELECT campos.*, producto.producto AS nombre_producto, usuarios.usuario AS nombre_usuario FROM campos
 INNER JOIN producto  ON campos.id_producto = producto.id LEFT JOIN usuarios on campos.id_u = usuarios.id_u ORDER BY id_c ASC LIMIT ? OFFSET ? ");
$res->bind_param("ii", $por_pagina, $inicio);
$res->execute();
$result = $res->get_result();
$data = [];
// recorremos los resultados y los almacenamos en un array
while($row = $result->fetch_assoc()){
    $row['id_c'] = (int)$row['id_c'];
    $row['id_producto'] = (int)$row['id_producto'];
    $row['id_u'] = (int)$row['id_u'];
    $data[] = $row;
}
echo json_encode(['ok'=> true , 'data'=>$data, 'pagina' => $pagina, 'total_P' => $total_P], JSON_UNESCAPED_UNICODE);

?>