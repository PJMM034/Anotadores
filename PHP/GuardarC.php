<?php
// requiere la conexion para pora poder insertar 
require_once '../Conexion/Conexion.php';

// creamos las variaboes
$nombre = $_POST['nombre'];
$id_producto = $_POST['id_producto'];
$tipo = $_POST['tipo'];
$numero = $_POST['numero'];
$estado = $_POST['estado'];
$id_u = $_POST['id_u'] ?? null;

// verificamos si ya exite el numero exite
$stmt = $Connection->prepare("SELECT id_c FROM campos WHERE numero = ? AND nombre = ?");
$stmt->bind_param("ss", $numero, $nombre);
$result = $stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    
    echo json_encode(['ok' => false, 'msg' => 'El campo $numero ya existe en el rancho $nombre']);
    exit;
}
    
// prepara la insercion par insertar los productos
$sql = $Connection->prepare("INSERT INTO campos (nombre,id_producto,tipo,numero,estado,id_u) VALUES (?,?,?,?,?,?)");
$sql->bind_param("sisssi",$nombre,$id_producto,$tipo,$numero,$estado,$id_u);
$sql->execute();
echo json_encode(['ok' => true, 'msg' => 'Campo registrado correctamente']);
exit;
?>