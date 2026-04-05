<?php
// requiere la conexion para pora poder insertar 
require_once '../Conexion/Conexion.php';
// creamos las variaboes
$nombre = $_POST['nombre'];
$id_producto = $_POST['id_producto'];
$tipo = $_POST['tipo'];
$numero = $_POST['numero'];
$estado = $_POST['estado'];
// prepara la insercion par insertar los productos
$sql = $Connection->prepare("INSERT INTO campos (nombre,id_producto,tipo,numero,estado) VALUES (?,?,?,?,?)");
$sql->bind_param("sisis",$nombre,$id_producto,$tipo,$numero,$estado);
$sql->execute();
header('Location: ../ADMIN/Gestion_Camp.php');
exit;
?>