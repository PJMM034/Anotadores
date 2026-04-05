<?php
// requiere la conexion para pora poder insertar 
require_once '../Conexion/Conexion.php';
// creamos las variaboes
$nombre = $_POST['nombre'];
$puesto = $_POST['puesto'];
$estado = $_POST['estado'];
// prepara la insercion par insertar los productos
$sql = $Connection->prepare("INSERT INTO trabajadores (nombre,puesto,estado) VALUES (?,?,?)");
$sql->bind_param("sss",$nombre,$puesto,$estado);
$sql->execute();
header('Location: ../ADMIN/Admin.php');
exit;
?>