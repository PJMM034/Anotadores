<?php
// Incluye el archivo de verificación de sesión para asegurar que el usuario ha iniciado sesión antes de acceder a esta página.
session_start();
require_once '../logins/check.php';
require_once '../Conexion/Conexion.php';
//mando a llamar la funcion para validar el inicio de sesion
require_role('ANOTADOR');

$sql = "SELECT 
            cortes.trabajador,
            producto.producto AS producto,
            cortes.cantidad,
            cortes.valor,
            (cortes.cantidad * cortes.valor) AS total,
            cortes.create_at
        FROM cortes
        INNER JOIN producto
        ON cortes.producto = producto.id
        WHERE DATE(cortes.create_at) = CURDATE()
        ORDER BY cortes.trabajador ASC,
                 cortes.create_at ASC";

$resultado = mysqli_query($Connection, $sql);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($Connection));
}

$trabajador_actual = "";
$total_dia = 0;

function mostrarTotalDia($total_dia) {
    echo "
        <tr>
            <td colspan='5' class='text-end fw-bold'>
                Total del día: $" . number_format($total_dia, 2) . "
            </td>
        </tr>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cortes del Día</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 text-dark text-center">Cortes del Día</h1>
        <a href="Anotadores.php" class="btn btn-outline-dark">Regresar</a>
    </div>

    <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>

        <?php
        if ($trabajador_actual != $fila['trabajador']) {

            if ($trabajador_actual != "") {
                mostrarTotalDia($total_dia);
                echo "</tbody></table>";
            }

            $trabajador_actual = $fila['trabajador'];
            $total_dia = 0;
        ?>

            <h3 class="mt-4 mb-3">
                <i class="bi bi-person-circle"></i>
                <?php echo htmlspecialchars($trabajador_actual); ?>
                <a href="../PDF/GPDF.php?trabajador=<?php echo urlencode($trabajador_actual); ?>" target="_blank" class="btn btn-sm ms-3"
                style="background-color: #070707; color: white; border: none;">
                Imprimir PDF</a>
            </h3>

            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Valor</th>
                        <th>Total</th>
                        <th>Hora</th>
                    </tr>
                </thead>
                <tbody>

        <?php } ?>

        <?php $total_dia += $fila['total']; ?>

        <tr>
            <td><?php echo htmlspecialchars($fila['producto']); ?></td>
            <td><?php echo $fila['cantidad']; ?> Kg</td>
            <td>$<?php echo number_format($fila['valor'], 2); ?></td>
            <td>$<?php echo number_format($fila['total'], 2); ?></td>
            <td><?php echo date("H:i:s", strtotime($fila['create_at'])); ?></td>
        </tr>

    <?php } ?>

    <?php
    if ($trabajador_actual != "") {
        mostrarTotalDia($total_dia);
        echo "</tbody></table>";
    } else {
        echo "
        <div class='alert alert-warning'>
            No hay cortes registrados el día de hoy.
        </div>";
    }
    ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>