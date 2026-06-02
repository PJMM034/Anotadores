<?php
require_once '../Conexion/Conexion.php';
session_start();

$sql = "SELECT trabajador, producto, cantidad, valor,
            (cantidad * valor) AS total, create_at
        FROM cortes
        ORDER BY trabajador ASC,
                 DATE(create_at) DESC,
                 create_at ASC";

$resultado = mysqli_query($Connection, $sql);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($Connection));
}

$trabajador_actual = "";
$fecha_actual = "";
$total_dia = 0;

function mostrarTotalDia($total_dia) {
    echo "
        <tr>
            <td colspan='5' class='text-end fw-bold textobar mb-4'>
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
    <title>Historial de Cortes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/EstiloAdmin.css">
    <script src="../js/jquery-4.0.0.js"></script>
</head>

<body>

<div class="d-flex">

    <div class="sidebar d-flex flex-column bg-dark" style="width: 200px; min-height: 100vh;">
        <div class="text-white p-3 border-bottom border-secondary fw-bold">NADA</div>

        <a href="Admin.php">Gestionar Trabajadores</a>
        <a href="Gestion_Usua.php">Gestionar Usuarios</a>
        <a href="Gestion_Camp.php">Gestionar Campos</a>
        <a href="Productos.php">Configurar Valores y Productos</a>
        <a href="#">Reportes Generales</a>
        <a href="Historial.php">Historial de Registro</a>
    </div>

    <div class="container mt-4">

        <h1 class="textobarsup fw-bold mb-4">
            Historial de Cortes por Trabajador
        </h1>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>

            <?php
            $fecha = date("Y-m-d", strtotime($fila['create_at']));

            if ($trabajador_actual != $fila['trabajador']) {

                if ($trabajador_actual != "") {
                    mostrarTotalDia($total_dia);
                    echo "</tbody></table>";
                }

                $trabajador_actual = $fila['trabajador'];
                $fecha_actual = "";
                $total_dia = 0;
            ?>

                <h3 class="mt-5 mb-3 textobarsup">
                    <i class="bi bi-person-circle"></i>
                    <?php echo htmlspecialchars($trabajador_actual); ?>
                </h3>

            <?php } ?>

            <?php
            if ($fecha_actual != $fecha) {

                if ($fecha_actual != "") {
                    mostrarTotalDia($total_dia);
                    echo "</tbody></table>";
                }
                $fecha_actual = $fecha;
                $total_dia = 0;
            ?>

                <h5 class="mt-3 mb-2 textobar">
                    Fecha:
                    <?php echo date("d/m/Y", strtotime($fecha_actual)); ?>
                </h5>

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
            <div class='alert alert-warning'>No hay cortes registrados.</div>";
        }
        ?>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>