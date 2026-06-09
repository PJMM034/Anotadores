<?php
require_once '../Conexion/Conexion.php';
session_start();
$limite = 1;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if ($pagina < 1) {
    $pagina = 1;
}

$inicio = ($pagina - 1) * $limite;

$sql_total = "SELECT COUNT(DISTINCT trabajador) AS total FROM cortes";
$res_total = mysqli_query($Connection, $sql_total);

if (!$res_total) {
    die("Error al contar trabajadores: " . mysqli_error($Connection));
}

$total_trabajadores = mysqli_fetch_assoc($res_total)['total'];
$total_paginas = ceil($total_trabajadores / $limite);

$sql_trabajadores = "SELECT DISTINCT trabajador
                     FROM cortes
                     ORDER BY trabajador ASC
                     LIMIT $inicio, $limite";

$res_trabajadores = mysqli_query($Connection, $sql_trabajadores);

if (!$res_trabajadores) {
    die("Error al obtener trabajadores: " . mysqli_error($Connection));
}

$trabajadores = [];

while ($t = mysqli_fetch_assoc($res_trabajadores)) {
    $trabajadores[] = "'" . mysqli_real_escape_string($Connection, $t['trabajador']) . "'";
}

if (count($trabajadores) > 0) {
    $lista_trabajadores = implode(",", $trabajadores);

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
            WHERE cortes.trabajador IN ($lista_trabajadores)
            ORDER BY cortes.trabajador ASC,
                     DATE(cortes.create_at) DESC,
                     cortes.create_at ASC";
} else {
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
            WHERE 1 = 0";
}

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
    <div class="sidebar vidrio-sidebar d-flex flex-column bg-dark" style="width: 200px; height: 100vh;">
        <img src="../imagenes/agrobitacora-logo.png" alt="AgroBitacora">
        <a class="textobar" href="Admin.php">Gestionar Trabajadores</a>
        <a class="textobar" href="Gestion_Usua.php">Gestionar Usuarios</a>
        <a class="textobar" href="Gestion_Camp.php">Gestionar Campos</a>
        <a class="textobar" href="Productos.php">Configurar Valores y Productos</a>
        <a class="textobar" href="Reportes.php">Reportes Generales</a>
        <a class="textobar" href="Historial.php">Historial de Resgistro</a>
        <a class="a-barra-salir" href="../logins/logout.php">Cerrar Sesión</a>
    </div>

    <div class="container mt-4">
        <div id="alertBox"></div>

        <h1 class="titulo-historial">
            <i class="bi bi-clock-history text-center"></i>
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

                    <a href="../PDF/GPDF.php?trabajador=<?php echo urlencode($trabajador_actual); ?>"
                       target="_blank"
                       class="btn btn-sm btn-primary ms-3">
                        Imprimir PDF
                    </a>
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

                <table class="table table-striped table-bordered tabla-transparente">
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
                No hay cortes registrados.
            </div>";
        }
        ?>

        <?php if ($total_paginas > 1) { ?>
            <div id="pagination" class="d-flex justify-content-center mt-3">
                <ul class="pagination">

                    <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>

                        <li class="page-item <?php echo ($i == $pagina) ? 'active' : ''; ?>">

                            <a class="page-link paginador"
                               href="?pagina=<?php echo $i; ?>">

                                <?php echo $i; ?>

                            </a>

                        </li>

                    <?php } ?>

                </ul>
            </div>
        <?php } ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>