<?php
session_start();
require_once '../Conexion/Conexion.php';

$id_u = (int)$_SESSION['id_u'];
$producto = $_POST['producto'];
$campo = $_POST['nombre'];
$trabajador = $_POST['cortador'];
$cantidad = $_POST['cantidad'];
$valor = $_POST['valor'];
$total = $cantidad * $valor;

if($id_u <=0 || $producto == '' || $campo == '' || $trabajador == '' || $cantidad <= 0){
    echo json_encode(['ok' => false, 'msg' => 'datos incompletos']);
    exit;

}
$corta = $Connection->prepare("INSERT INTO cortes (id_u, producto, campo, trabajador, cantidad, valor, total) VALUES (?,?,?,?,?,?,?)");
$corta->bind_param("isssddd",$id_u,$producto,$campo,$trabajador,$cantidad,$valor,$total);
$corta->execute();

echo json_encode(['ok' => true, 'msg' => 'Corte guardado correctamente']);
exit;

?>