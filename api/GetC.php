<?php
require_once "../Conexion/Conexion.php";

$id_c = (int)$_GET['id_c'] ?? 0;
$const = $Connection->prepare("SELECT * FROM campos WHERE id_c = ? LIMIT 1");
$const->bind_param("i", $id_c);
$const->execute();
$res = $const->get_result();
$row = $res->fetch_assoc();

if (!$row){
    echo json_encode(['ok'=> false, 'msg'=>'Resgistro no encontrado']);
    exit;
}
// qui row lo covertinomas a int 
$row['id_c'] = (int)$row['id_c'];
// en esta parte vamos a conertir en json cuando se verda que ahi un dato en forma json
echo json_encode(['ok'=> true, 'data'=>$row, JSON_UNESCAPED_UNICODE]);

?>