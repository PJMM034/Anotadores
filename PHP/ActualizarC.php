<?php
require_once '../Conexion/Conexion.php';

$id_c          = $_POST['id_c'];
$nombre      = $_POST['nombre'];
$id_producto = $_POST['id_producto'];
$tipo        = $_POST['tipo'];
$numero      = $_POST['numero'];
$estado      = $_POST['estado'];

$sql = $Connection->prepare("UPDATE campos SET nombre=?, id_producto=?, tipo=?, numero=?, estado=? WHERE id_c=?");
$sql->bind_param("sisisi", $nombre, $id_producto, $tipo, $numero, $estado, $id_c);
$sql->execute();

header('Location: ../ADMIN/Gestion_Camp.php');
exit;
?>