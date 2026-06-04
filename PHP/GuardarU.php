<?php
require_once '../Conexion/Conexion.php';

$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';
$rol = trim($_POST['rol'] ?? '');
$estado = trim($_POST['estado'] ?? '');

// Verificar si el usuario ya existe
$stmt = $Connection->prepare("SELECT id_u FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$result = $stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['ok' => false, 'msg' => 'El usuario ya existe']);
    exit;
}

if (strlen($password) < 10) {
    echo json_encode(['ok' => false, 'msg' => 'La contraseña debe tener mínimo 10 caracteres']);
    exit;
}

$sql = $Connection->prepare("INSERT INTO usuarios (usuario, password, rol, estado) VALUES (?,?,?,?)");
$sql->bind_param("ssss", $usuario, $password, $rol, $estado);
$sql->execute();

echo json_encode(['ok' => true, 'msg' => 'Usuario registrado correctamente']);
exit;
?>