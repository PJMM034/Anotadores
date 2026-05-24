<?php
// requiere la conexion para pora poder insertar 
require_once '../Conexion/Conexion.php';
// creamos las variaboes
$nombre = $_POST['nombre'];
$id_producto = $_POST['id_producto'];
$tipo = $_POST['tipo'];
$numero = $_POST['numero'];
$estado = $_POST['estado'];
$id_u = $_POST['id_u'];



// prepara la insercion par insertar los productos
$sql = $Connection->prepare("INSERT INTO campos (nombre,id_producto,tipo,numero,estado,id_u) VALUES (?,?,?,?,?,?)");
$sql->bind_param("sisisi",$nombre,$id_producto,$tipo,$numero,$estado,$id_u);
$sql->execute();
header('Location: ../ADMIN/Gestion_Camp.php');
exit;
?>