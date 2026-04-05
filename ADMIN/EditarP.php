<?php
    require_once '../Conexion/Conexion.php';
    $id = $_GET['id'];
    $sql =$Connection->query("SELECT * FROM producto WHERE id = $id ");
    $fila = $sql->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body class="container mt-4">
    <div>
        <h1>Editar Producto</h1>
        <form action="../PHP/ActualizarP.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
            <div class="mb-3">
                <label for="producto">Producto:</label>
                <input type="text" class="form-control" id="producto" name="producto" value="<?php echo $fila['producto']; ?>">
            </div>
             <div class="mb-3">
                <label for="unidad" class="form-label">Unidad</label>
                <select class="form-select" name="unidad" id="unidad " required>
                    <!-- opciones de unidad si es le hace un selectd para macarlo -->
                    <option value="Kilo"       <?php if($fila['unidad'] == 'Kilo')       echo 'selected'; ?>>Kilo</option>
                    <option value="Cubeta"     <?php if($fila['unidad'] == 'Cubeta')     echo 'selected'; ?>>Cubeta</option>
                    <option value="Costal"     <?php if($fila['unidad'] == 'Costal')     echo 'selected'; ?>>Costal</option>
                    <option value="Canastilla" <?php if($fila['unidad'] == 'Canastilla') echo 'selected'; ?>>Canastilla</option>
                 </select>
             </div>
            <div class="mb-3">
                <label for="valor">Valor:</label>
                <input type="number" class="form-control" id="valor" name="valor" value="<?php echo $fila['valor']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</body>
</html>