<?php
require_once '../Conexion/Conexion.php';
//la paginacion
$por_pagina = 7;
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$inicio = ($pagina - 1) * $por_pagina;

$tol = $Connection->query("SELECT COUNT(*) AS total FROM producto")->fetch_assoc()['total'];
$total_P = ceil($tol / $por_pagina);

$res = $Connection->prepare("SELECT * FROM producto order by id ASC LIMIT ? OFFSET ?");
$res->bind_param("ii", $por_pagina, $inicio);
$res->execute();
$result = $res->get_result();
$data = [];
// recorremos los resultados y los almacenamos en un array
while($row = $result->fetch_assoc()){
    $row['id'] = (int)$row['id'];
    $data[] = $row;
}
echo json_encode(['ok'=> true , 'data'=>$data, 'pagina' => $pagina, 'total_P' => $total_P], JSON_UNESCAPED_UNICODE);

?>