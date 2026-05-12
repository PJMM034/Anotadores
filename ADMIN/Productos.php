<?php
require_once '../Conexion/Conexion.php';

$queryA = "SELECT * FROM producto";
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
        const modale = new bootstrap.Modal(document.getElementById('modale'));
        
        function showAlert(type, msg) {
                $("#alertBox").html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                `);

            } 

            function cargaProductos(){
                $.getJSON ("../api/ListP.php", function(resp){
                    // Procesar los datos recibidos
                   if(!resp.ok) {
                     showAlert('danger', resp.msg || 'datos');
                     //return;
                   }
                        const row = resp.data.map(s =>`
                          <tr> 
                            <td>${s.id}</td>
                            <td>${s.producto}</td>
                            <td>${s.unidad}</td>
                            <td>${s.valor}</td>
                            <td class="text-end">
                              <button class="btn btn-sm btn-outline-primary me-1 btn-edit" data-id="${s.id}" title="Editar">
                                <i class="bi bi-pencil"></i>
                              </button>
                          </td>
                          </tr>
                     `);
                    //aqui se muestra la informacion de los alumnos en la tabla
                    $("#tblproducts tbody").html(row);
                     
                });
            }

             $(document).on("click",".btn-edit", function(){
                  //alert("diste click en editar");
                  //aqui se obtiene el id de para mostrarl o jalar la informaion 
                  const id = $(this).data("id");
                
                  $.getJSON("../api/GetP.php", {id:id}, function(resp){
                    console.log(resp); 
                     if(!resp.ok) {
                     showAlert('danger', resp.msg || 'ERROR ID NO ENCONTRADO');
                     return;
                   }

                   const data = resp.data;
                    $("#edit-id").val(data.id);
                    $("#edit-producto").val(data.producto);
                    $("#edit-unidad").val(data.unidad);
                    $("#edit-valor").val(data.valor);

                    $("#modale").modal('show');
                  });
                  
            });

            $("#proform").on("submit", function(e){
                e.preventDefault();
                $.post("../api/EditarP.php",$(this).serialize(), function(resp){
                try{resp = JSON.parse(resp);} catch(e){resp={ok:false, msg:'Error al editar'};}
                if(!resp.ok) {
                     showAlert('danger', resp.msg || 'Error al editar');
                     return;
                   }
                   $("#modale").modal('hide');
                   showAlert('success', 'Producto editado correctamente');
                   cargaProductos();
                });
            });
                cargaProductos();
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
            <!-- la tabla de los trabajadores -->
             <div id="alertBox">
             
            </div>
            <table class="table table-striped table-bordered" id="tblproducts">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Produtos</th>
                        <th>Unidad</th>
                        <th>Valor</th>
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
        
            <div class="modal fade" id="modale" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="proform">
                            <div class="modal-body">
                                <input type="hidden" id="edit-id" name="id">
                                <div class="mb-3">
                                    <label for="edit-producto" class="form-label">Nombre del Producto</label>
                                    <input type="text" class="form-control" id="edit-producto" name="producto" required placeholder="Introduce el producto">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-unidad" class="form-label">Unidad</label>
                                    <select class="form-select" id="edit-unidad" name="unidad" required>
                                        <option value="">Seleccionar Unidad</option>
                                        <option value="Kilo">Kilo</option>
                                        <option value="Cubeta">Cubeta</option>
                                        <option value="Costal">Costal</option>
                                        <option value="Canastilla">Canastilla</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-valor" class="form-label">Valor por Unidad</label>
                                    <input type="text" class="form-control" id="edit-valor" name="valor" required placeholder="Ingresar Precio">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
</body>
</html>