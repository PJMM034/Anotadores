<?php
    require_once '../Conexion/Conexion.php';
    $id_t = $_GET['id_t'];
    $sql =$Connection->query("SELECT * FROM trabajadores WHERE id_t = $id_t");
    $fila = $sql->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Trabajador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body class="container mt-4">
    <div>
        <h1>Editar Trabajador</h1>
        <form action="../PHP/ActualizarT.php" method="POST">
            <input type="hidden" name="id_t" value="<?php echo $fila['id_t']; ?>">
            <div class="mb-3">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $fila['nombre']; ?>">
            </div>
             <div class="mb-3">
                <label for="puesto" class="form-label">Puesto</label>
                <select class="form-select" name="puesto" id="puesto " required>
                    <!-- opciones de puesto si es le hace un selectd para macarlo -->
                    <option value="a"       <?php if($fila['puesto'] == 'a')       echo 'selected'; ?>>Administrador</option>
                    <option value="b"     <?php if($fila['puesto'] == 'b')     echo 'selected'; ?>>Supervisor</option>
                 </select>
             </div>
            <div class="mb-3">
                <label for="estado">Estado:</label>
                <select class="form-select" name="estado" id="estado" required>
                    <option value="Activo" <?php if($fila['estado'] == 'Activo') echo 'selected'; ?>>Activo</option>
                    <option value="Inactivo" <?php if($fila['estado'] == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</body>
</html>