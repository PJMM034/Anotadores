<?php
// requiere la conexion para pora poder insertar 
require_once '../Conexion/Conexion.php';
// creamos las variaboes
$producto = $_POST['producto'];
$unidad = $_POST['unidad'];
$valor = $_POST['valor'];

// verificamos si ya exite el producto
$stmt = $Connection->prepare("SELECT id FROM producto WHERE producto = ?");
$stmt->bind_param("s", $producto);
$result = $stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    
    echo json_encode(['ok' => false, 'msg' => 'El producto ya existe']);
    exit;
}
// prepara la insercion par insertar los productos
$sql = $Connection->prepare("INSERT INTO producto (producto,unidad,valor) VALUES (?,?,?)");
$sql->bind_param("ssd",$producto,$unidad,$valor);
$sql->execute();
echo json_encode(['ok' => true, 'msg' => 'Producto guardado correctamente']);
exit;
?>