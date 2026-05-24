<?php
require_once '../Conexion/Conexion.php';

$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';
$rol = trim($_POST['rol'] ?? '');
$estado = trim($_POST['estado'] ?? '');

if (strlen($password) < 10) {
    header('Location: ../ADMIN/Gestion_Usua.php?error=La contraseña debe tener mínimo 10 caracteres');
    exit;
}
$sql = $Connection->prepare("INSERT INTO usuarios (usuario, password, rol, estado) VALUES (?,?,?,?)");
$sql->bind_param("ssss", $usuario, $password, $rol, $estado);
$sql->execute();

header('Location: ../ADMIN/Gestion_Usua.php');
exit;
?>