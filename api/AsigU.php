<?php
require_once "../Conexion/Conexion.php";

$id_c = (int)($_POST['id_c'] ?? 0);
$id_u = !empty($_POST['id_u']) ? (int)$_POST['id_u'] : null;

if($id_c <= 0){
    echo json_encode(['ok' => false, 'msg' => 'Campo inválido']);
    exit;
}
// la validacion que solo aparaenca solo anotador 
if($id_u != null){
    $roll = $Connection->prepare("SELECT id_u FROM usuarios WHERE id_u = ? AND rol = 'ANOTADOR'");
    $roll->bind_param("i", $id_u);
    $roll->execute();
    $roll->store_result();

    if($roll->num_rows === 0){
        echo json_encode(['ok' => false, 'msg' => 'Anotador no encontrado']);
        exit;
    }
    $roll->close();
    //  este es para validar que anotadoe no este asignado a un cammpo

    $vali = $Connection->prepare("SELECT id_c FROM campos WHERE id_u = ? AND id_c != ?");
    $vali->bind_param("ii", $id_u, $id_c);
    $vali->execute();
    // aqui uso el num_rows para que me regrese algo si es mayor a 0
    if($vali->get_result()->num_rows > 0){
        echo json_encode(['ok' => false, 'msg' => 'El anotador ya esta en un campo']);
        exit;
    }
}

$mis = $Connection->prepare("UPDATE campos SET id_u = ? WHERE id_c = ?");
$mis->bind_param("ii", $id_u, $id_c);
$mis->execute();

echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
?>