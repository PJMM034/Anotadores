<?php
// requiere la conexion para pora poder insertar 
require_once '../Conexion/Conexion.php';
// creamos las variaboes
$id = $_POST['id'];
$producto = $_POST['producto'];
$unidad = $_POST['unidad'];
$valor = $_POST['valor'];
// prepara la insercion par insertar los productos
$sql = $Connection->prepare("UPDATE producto SET producto = ?, unidad = ?, valor = ? WHERE id = ?");
$sql->bind_param("ssdi", $producto, $unidad, $valor, $id);
$sql->execute();
header('Location: ../ADMIN/Productos.php');
exit;
?>