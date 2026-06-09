<?php
session_start();
require_once '../Conexion/Conexion.php';

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

$login = $Connection->prepare("SELECT * FROM usuarios WHERE usuario = ? AND estado = 'Activo' LIMIT 1");
$login->bind_param("s",$usuario);
$login->execute();
$res = $login->get_result();
$us = $res->fetch_assoc();

if (!$us) {
    header('Location: ../index.php?error=1');
    exit();
}
if($password !== $us['password']){
    header('Location: ../index.php?error=1');
    exit();
}

$_SESSION['id_u'] = $us['id_u'];
$_SESSION['usuario'] = $us['usuario'];
$_SESSION['rol'] = $us['rol'];


if ($us['rol'] == 'ADMIN') {
    header('Location: ../ADMIN/Admin.php');
    exit();
} if ($us['rol'] == 'ANOTADOR') {
    header('Location: ../ANOTA/Anotadores.php');
    exit();
} 
  



?>