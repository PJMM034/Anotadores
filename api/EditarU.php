<?php
require_once "../Conexion/Conexion.php";

$id_u = (int)($_POST['id_u'] ?? 0);
$usuario = trim($_POST['usuario']  ?? '');
$password = trim($_POST['password'] ?? '');
$rol = trim($_POST['rol']      ?? '');
$estado = trim($_POST['estado']   ?? '');

if ($id_u <= 0 || $usuario == '' || $rol == '' || $estado == '') {
    echo json_encode(['ok' => false, 'msg' => 'Datos inválidos']);
    exit;
}

// Si viene la contraseña lo actualiza, si no lo deja  como esta 
if ($password != '') {
    if (strlen($password) < 10) {
        echo json_encode(['ok' => false, 'msg' => 'La contraseña debe tener mínimo 10 caracteres :O']);
        exit;
    }
    $contra = $Connection->prepare("UPDATE usuarios SET usuario=?, password=?, rol=?, estado=? WHERE id_u=?");
    $contra->bind_param("ssssi", $usuario, $password, $rol, $estado, $id_u);
} else {
    $contra = $Connection->prepare("UPDATE usuarios SET usuario=?, rol=?, estado=? WHERE id_u=?");
    $contra->bind_param("sssi", $usuario, $rol, $estado, $id_u);
}
$contra->execute();
echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
?>