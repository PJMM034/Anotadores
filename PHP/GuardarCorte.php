<?php
session_start();
require_once '../Conexion/Conexion.php';

$id_u = (int)$_SESSION['id_u'];
$producto =(int)$_POST['id_producto'];
$campo = (int)$_POST['id_c'];
$trabajador = $_POST['cortador'];
$cantidad = (float)$_POST['cantidad'];
$valor = (float)$_POST['valor'];
$total = $cantidad * $valor;

if($id_u <=0 || $producto <= 0 || $campo <= 0 || $trabajador == '' || $cantidad <= 0){
    echo json_encode(['ok' => false, 'msg' => 'datos incompletos']);
    exit;

}
$corta = $Connection->prepare("INSERT INTO cortes (id_u, producto, campo, trabajador, cantidad, valor, total) VALUES (?,?,?,?,?,?,?)");
$corta->bind_param("iiisddd", $id_u, $producto, $campo, $trabajador, $cantidad, $valor, $total);
$corta->execute();
echo json_encode(['ok' => true, 'msg' => 'Corte guardado correctamente']);
exit;

?>