<?php
// requiere la conexion para pora poder insertar 
require_once '../Conexion/Conexion.php';
// creamos las variaboes
$nombre = $_POST['nombre'];
$estado = $_POST['estado'];

// verificamos si ya existe el trabajador
$stmt = $Connection->prepare("SELECT id_t FROM trabajadores WHERE nombre = ?");
$stmt->bind_param("s", $nombre);
$result = $stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    
    echo json_encode(['ok' => false, 'msg' => 'El trabajador ya existe']);
    exit;
}


// prepara la insercion par insertar los productos
$sql = $Connection->prepare("INSERT INTO trabajadores (nombre,estado) VALUES (?,?)");
$sql->bind_param("ss",$nombre,$estado);
$sql->execute();
echo json_encode(['ok' => true, 'msg' => 'Trabajador registrado correctamente']);

exit;

?>