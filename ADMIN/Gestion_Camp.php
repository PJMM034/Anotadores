<?php
require_once '../Conexion/Conexion.php';

$queryA = "SELECT * FROM campos";
$resultA = $Connection->query($queryA);

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../js/jquery-4.0.0.js"></script>
<script>
      
      $(document).ready(function(){
        const modaedit = new bootstrap.Modal(document.getElementById('modaedit'));
        
        function showAlert(type, msg) {
                $("#alertBox").html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                `);

            } 

            function cargaCampos(){
                $.getJSON ("../api/ListC.php", function(resp){
                    // Procesar los datos recibidos
                   if(!resp.ok) {
                     showAlert('danger', resp.msg || 'datos');
                     //return;
                   }
                        const row = resp.data.map(s =>`
                          <tr> 
                            <td>${s.id_c}</td>
                            <td>${s.nombre}</td>
                            <td>${s.nombre_producto}</td>
                            <td>${s.tipo}</td>
                            <td>${s.numero}</td>
                            <td>${s.estado}</td>
                            <td class="text-end">
                              <button class="btn btn-sm btn-outline-primary me-1 btn-edit" data-id_c="${s.id_c}" title="Editar">
                                <i class="bi bi-pencil"></i>
                              </button>
                          </td>
                          </tr>
                     `);
                    //aqui se muestra la informacion de los alumnos en la tabla
                    $("#tblcampos tbody").html(row);
                     
                });
            }

             $(document).on("click",".btn-edit", function(){
                  //alert("diste click en editar");
                  //aqui se obtiene el id de para mostrarl o jalar la informaion 
                  const id_c = $(this).data("id_c");
                
                  $.getJSON("../api/GetC.php", {id_c:id_c}, function(resp){
                    console.log(resp); 
                     if(!resp.ok) {
                     showAlert('danger', resp.msg || 'ERROR ID NO ENCONTRADO');
                     return;
                   }

                   const data = resp.data;
                    $("#edit-id_c").val(data.id_c);
                    $("#edit-nombre").val(data.nombre);
                    $("#edit-id_producto").val(data.id_producto);
                    $("#edit-tipo").val(data.tipo);
                    $("#edit-numero").val(data.numero);
                    $("#edit-estado").val(data.estado);
                    $("#modaedit").modal('show');
                  });
                  
            });

            $("#formCamp").on("submit", function(e){
                e.preventDefault();
                $.post("../api/EditarC.php",$(this).serialize(), function(resp){
                try{resp = JSON.parse(resp);} catch(e){resp={ok:false, msg:'Error al editar'};}
                if(!resp.ok) {
                     showAlert('danger', resp.msg || 'Error al editar');
                     return;
                   }
                   $("#modaedit").modal('hide');
                   showAlert('success', 'Campo editado correctamente');
                   cargaCampos();
                });
            });
                cargaCampos();
      });

</script>

</head>
<body>
    
    <div class="d-flex">
        <div class="sidebar d-flex flex-column bg-dark" style="width: 200px; height: 100vh;">
            <div Class="text-white p-3 border-bottom border-secondary fw-bold">NADA</div>
            <a href="Admin.php">Gestionar Trabajadores</Tr></a>
            <a href="Gestion_Usua.php">Gestionar Usuarios</a>
            <a href="Gestion_Camp.php">Gestionar Campos</a>
            <a href="Productos.php">Configurar Valores y Productos</a>
            <a href="#">Reportes Generales</a>
            <a href="#">Historial de Resgistro</a>
        </div>
        <div class="container mt-4">
            <div id="alertBox">
             
            </div>
            <!-- la tabla de los trabajadores -->
            <table class="table table-striped table-bordered" id="tblcampos">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Rancho</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Número</th>
                        <th>Estado</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                    <tbody>
                         <tr>
                            <!-- aqui uso el colspan para unir las celdas y para usar usar ajax  -->
                            <td colspan="7" class="text-center" text-secondary p-4>Cargando...</td>
                            
                        </tr>
                    </tbody>
            </table>
            <div class="container mt-3 text-center">
                <button class="btn btn-primary px-5" data-bs-toggle="modal" data-bs-target="#modalt">Añadir Campo</button>
            </div>
            <div class="modal fade" id="modalt" tabindex="-1" aria-labelledby="modalt" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalt">Agregar Campos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../PHP/GuardarC.php" method="post" id="form">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Campo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Introduce el nombre del campo">
                        </div>
                        <div class="md-form mb-4">
                            <label for="id_producto" class="form-label">Producto</label>
                            <select class="form-select" name="id_producto" id="id_producto" required>

                            <?php
                            // aqui hago la consulta para mostrar los productos en el select del modal para que el admin pueda elegir el producto que va a tener el campo
                            $sqlproducto = $Connection->query("SELECT * FROM producto");
                            while($fila = $sqlproducto->fetch_assoc()){
                                echo "<option value='" . $fila['id'] . "'>" . $fila['producto'] . "</option>";
                            }
                            ?>   
                            </select>
                        </div>
                        <div class="md-form mb-4">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select class="form-select" name="tipo" id="tipo" required>
                            <option value="">Ingresar Tipo</option>
                            <option value="malla">Malla</option>
                            <option value="invernadero">Invernadero</option>
                            <option value="cuadro">Cuadro</option>
                            </select>
                        </div>
                         <div class="mb-3">
                            <label for="numero" class="form-label">Número del Campo</label>
                            <input type="text" class="form-control" id="numero" name="numero" required placeholder="Introduce el número del campo">
                        </div>
                         <div class="md-form mb-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="estado " required>
                            <option value="Activo">Activo</option>
                            <option value="Inativo">Inativo</option>
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

           <div class="modal fade" id="modaedit" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    ]<h5 class="modal-title">Editar Campo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCamp">
                        <input type="hidden" id="edit-id_c" name="id_c">
                        <div class="mb-3">
                            <label for="edit-nombre" class="form-label">Nombre del Campo</label>
                            <input type="text" class="form-control" id="edit-nombre" name="nombre" required placeholder="Introduce el nombre del campo">
                        </div>
                        <div class="md-form mb-4">
                            <label for="edit-id_producto" class="form-label">Producto</label>
                            <select class="form-select" name="id_producto" id="edit-id_producto" required>
                               <?php

                                $sqlproducto = $Connection->query("SELECT * FROM producto");

                                while($fila = $sqlproducto->fetch_assoc()){
                                    echo "<option value='" . $fila['id'] . "'>" . $fila['producto'] . "</option>";
                                }
                                ?>

                           </select>
                        </div>
                        <div class="md-form mb-4">
                            <label for="edit-tipo" class="form-label">Tipo</label>
                            <select class="form-select" name="tipo" id="edit-tipo" required>
                            <option value="">Ingresar Tipo</option>
                            <option value="malla">Malla</option>
                            <option value="invernadero">Invernadero</option>
                            <option value="cuadro">Cuadro</option>
                            </select>
                        </div>
                         <div class="mb-3">
                            <label for="edit-numero" class="form-label">Número del Campo</label>
                            <input type="text" class="form-control" id="edit-numero" name="numero" required placeholder="Introduce el número del campo">
                        </div>
                         <div class="md-form mb-4">
                            <label for="edit-estado" class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="edit-estado" required>
                            <option value="Activo">Activo</option>
                            <option value="Inativo">Inativo</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                    <!-- uso el tipo submit para que envie el formulario -->
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</body>
</html>