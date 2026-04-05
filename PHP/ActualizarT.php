<?php
// requiere la conexion para pora poder insertar 
require_once '../Conexion/Conexion.php';
// creamos las variaboes
$id_t = $_POST['id_t'];
$nombre = $_POST['nombre'];
$puesto = $_POST['puesto'];
$valor = $_POST['estado'];
// prepara la insercion par insertar los productos
$sql = $Connection->prepare("UPDATE trabajadores SET nombre = ?, puesto = ?, estado = ? WHERE id_t = ?");
$sql->bind_param("ssss", $nombre, $puesto, $valor, $id_t);
$sql->execute();
header('Location: ../ADMIN/Admin.php');
exit;
?>