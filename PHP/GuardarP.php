<?php
// requiere la conexion para pora poder insertar 
require_once '../Conexion/Conexion.php';
// creamos las variaboes
$producto = $_POST['producto'];
$unidad = $_POST['unidad'];
$valor = $_POST['valor'];
// prepara la insercion par insertar los productos
$sql = $Connection->prepare("INSERT INTO producto (producto,unidad,valor) VALUES (?,?,?)");
$sql->bind_param("ssd",$producto,$unidad,$valor);
$sql->execute();
header('Location: ../ADMIN/Productos.php');
exit;

?>