<?php
require_once "../Conexion/Conexion.php";

$id = (int)$_GET['id'] ?? 0;
$const = $Connection->prepare("SELECT * FROM producto WHERE id = ? LIMIT 1");
$const->bind_param("i", $id);
$const->execute();
$res = $const->get_result();
$row = $res->fetch_assoc();

if (!$row){
    echo json_encode(['ok'=> false, 'msg'=>'Resgistro no encontrado']);
    exit;
}
// qui row lo covertinomas a int 
$row['id'] = (int)$row['id'];
// en esta parte vamos a conertir en json cuando se verda que ahi un dato en forma json
echo json_encode(['ok'=> true, 'data'=>$row, JSON_UNESCAPED_UNICODE]);

?>