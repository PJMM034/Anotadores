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
                        <th>Trabajadores</th>
                        <th>Estado</th>
                        <th>Editar</th>
                        <th>Puesto</th>
                    </tr>
                </thead>
                    <tbody>
                        <?php
                        // qui hago la consulta para la tabla
                        $sql = $Connection->query("SELECT * from trabajadores");
                        // aqui lo convierto en arreglos para las comunas con el fetch_assoc
                        while($fila = $sql->fetch_assoc()){
                            echo "<tr>";
                            echo "<td>" . $fila['nombre'] . "</td>";
                            echo "<td>" . $fila['estado'] . "</td>";
                            echo "<td><a href='EditarT.php?id_t=" . $fila['id_t'] . "' class='btn btn-warning btn-sm'>Editar</a></td>";
                            echo "<td>" . $fila['puesto'] . "</td>";
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
                    <h1 class="modal-title fs-5" id="modalt">Agregar Trabajador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../PHP/GuardarT.php" method="post" id="form">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Trabajador</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Introduce el nombre del trabajador Completo">
                        </div>
                        <div class="md-form mb-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="estado " required>
                            <option value="Activo">Activo</option>
                            <option value="Inativo">Inativo</option>
                            </select>
                        </div>
                         <div class="md-form mb-4">
                            <label for="puesto" class="form-label">Puesto</label>
                            <select class="form-select" name="puesto" id="puesto " required>
                            <option value="">Ingresar el puesto</option>
                            <option value="a">Activo</option>
                            <option value="b">Inativo</option>
                            </select>
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