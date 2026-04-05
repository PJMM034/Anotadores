<?php
require_once '../Conexion/Conexion.php';

$id = $_GET['id'];

// Traemos el campo que queremos editar
$sql = $Connection->query("SELECT * FROM campos WHERE id_c = $id");
$fila = $sql->fetch_assoc();

// Traemos todos los productos para el select
$sqlProductos = $Connection->query("SELECT * FROM producto");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Campo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="container mt-4">
<div>
    <h1>Editar Campo</h1>
    <form action="../PHP/ActualizarC.php" method="POST">
        <input type="hidden" name="id_c" value="<?php echo $fila['id_c']; ?>">

        <div class="mb-3">
            <label>Nombre del Campo:</label>
            <input type="text" class="form-control" name="nombre"
                   value="<?php echo $fila['nombre']; ?>">
        </div>

        <div class="mb-3">
            <label>Producto:</label>
            <select class="form-select" name="id_producto" required>
                <?php
                // recorremos todos los productos para mostrarlos en el select, y si el id del es igual
                while($p = $sqlProductos->fetch_assoc()){
                    // Si el id del producto es igual al que tiene el campo, lo marcamos
                    if($p['id'] == $fila['id_producto']){
                        echo "<option value='" . $p['id'] . "' selected>" . $p['producto'] . "</option>";
                    } else {
                        echo "<option value='" . $p['id'] . "'>" . $p['producto'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Tipo:</label>
            <select class="form-select" name="tipo" required>
                <!-- si el tipo guardado es igual al option lo marca con selected -->
                <option value="malla"        <?php if($fila['tipo'] == 'malla')        echo 'selected'; ?>>Malla</option>
                <option value="invernadero"  <?php if($fila['tipo'] == 'invernadero')  echo 'selected'; ?>>Invernadero</option>
                <option value="cuadro"       <?php if($fila['tipo'] == 'cuadro')       echo 'selected'; ?>>Cuadro</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Número del Campo:</label>
            <input type="number" class="form-control" name="numero"
                   value="<?php echo $fila['numero']; ?>">
        </div>

        <div class="mb-3">
            <label>Estado:</label>
            <select class="form-select" name="estado" required>
                <option value="Activo"   <?php if($fila['estado'] == 'Activo')   echo 'selected'; ?>>Activo</option>
                <option value="Inactivo" <?php if($fila['estado'] == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="../ADMIN/Gestion_Camp.php" class="btn btn-secondary">Cancelar</a>

    </form>
</div>
</body>
</html>