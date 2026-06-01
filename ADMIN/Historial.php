<?php
require_once '../Conexion/Conexion.php';
session_start();

$sql = "SELECT 
            trabajador,producto,cantidad,valor,
            (cantidad * valor) AS total,create_at
        FROM cortes
        ORDER BY create_at DESC";
$resultado = mysqli_query($Connection, $sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Cortes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../CSS/EstiloAdmin.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../js/jquery-4.0.0.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
<div class="container mt-4">
    <h1 Class="textobarsup mb-4">Historial de Cortes</h1>
    <table class="table tabla-transparente" id="tblhistorial">

        <thead class="table-dark">
            <tr>
                <th>Trabajador</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Fecha y Hora</th>
            </tr>
        </thead>

        <tbody>

        <?php while($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo $fila['trabajador']; ?></td>
                <td><?php echo $fila['producto']; ?></td>
                <td><?php echo $fila['cantidad']; ?> Kg</td>
                <td>$<?php echo $fila['total']; ?></td>
                <td><?php echo $fila['create_at']; ?></td>
            </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

</body>
</html>