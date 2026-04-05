<?php
require_once '../Conexion/Conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../CSS/EstiloAdmin.css">
</head>
<body>
    <div class="d-flex">
        <div class="sidebar d-flex flex-column bg-dark" style="width: 200px; height: 100vh;">
            <div Class="text-white p-3 border-bottom border-secondary fw-bold">NADA</div>
            <a href="Admin.php">Gestionar Trabajadores</Tr></a>
            <a href="Gestion_Usua.php">Gestionar Usuarios</a>
            <a href="#">Gestionar Campos</a>
            <a href="Productos.php">Configurar Valores y Productos</a>
            <a href="#">Reportes Generales</a>
            <a href="#">Historial de Resgistro</a>
        </div>
        <div class="container mt-4">
            <!-- la tabla de los trabajadores -->
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Produtos</th>
                        <th>Unidad</th>
                        <th>Valor</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                    <tbody>
                        <?php
                        // qui hago la consulta para la tabla
                        $sql = $Connection->query("SELECT * from producto");
                        // aqui lo convierto en arreglos para las comunas con el fetch_assoc
                        while($fila = $sql->fetch_assoc()){
                            echo "<tr>";
                            echo "<td>" . $fila['producto'] . "</td>";
                            echo "<td>" . $fila['unidad'] . "</td>";
                            echo "<td>" . $fila['valor'] . "</td>";
                            echo "<td><a href='EditarP.php?id=" . $fila['id'] . "' class='btn btn-warning btn-sm'>Editar</a></td>";
                            echo "</tr>";
                        }                      
                        ?>
                    </tbody>
            </table>
            <div class="container mt-3 text-center">
                <button class="btn btn-primary px-5" data-bs-toggle="modal" data-bs-target="#modalt">Añadir Producto</button>
            </div>
            <div class="modal fade" id="modalt" tabindex="-1" aria-labelledby="modalt" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalt">Agregar Producto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../PHP/GuardarP.php" method="post" id="form">
                        <div class="mb-3">
                            <label for="producto" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" id="producto" name="producto" required placeholder="Introduce el producto">
                        </div>
                        <div class="md-form mb-4">
                            <label for="unidad" class="form-label">Unidad</label>
                            <select class="form-select" name="unidad" id="unidad " required>
                            <option value="">Ingresar Unidad</option>
                            <option value="Kilo">Kilo</option>
                            <option value="Cubeta">Cubeta</option>
                            <option value="Costal">Costal</option>
                            <option value="Canastilla">Canastilla</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor por Unidad</label>
                            <input type="text" class="form-control" id="valor" name="valor" required placeholder="Ingresar Precio">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                    <!-- uso el tipo submit para que envie el formulario -->
                    <button type="submit" form="form" class="btn btn-primary">Guardar</button>
                    
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</body>
</html>