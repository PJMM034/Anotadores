<?php
session_start();
require_once '../logins/check.php';
require_role('ADMIN');


$queryA = "SELECT * FROM trabajadores";
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
    <script src="../js/jquery-4.0.0.js"></script>

<script>
      
      $(document).ready(function(){
        const modaled = new bootstrap.Modal(document.getElementById('modaled'));
        
        function showAlert(type, msg) {
                $("#alertBox").html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                `);

            } 

            function cargaTrabajadores(){
                $.getJSON ("../api/ListT.php", function(resp){
                    // Procesar los datos recibidos
                   if(!resp.ok) {
                     showAlert('danger', resp.msg || 'datos');
                     //return;
                   }
                        const row = resp.data.map(s =>`
                          <tr> 
                            <td>${s.id_t}</td>
                            <td>${s.nombre}</td>
                            <td>${s.puesto}</td>
                            <td>${s.estado}</td>
                            <td class="text-end">
                              <button class="btn btn-sm btn-outline-primary me-1 btn-edit" data-id_t="${s.id_t}" title="Editar">
                                <i class="bi bi-pencil"></i>
                              </button>
                          </td>
                          </tr>
                     `);
                    //aqui se muestra la informacion de los alumnos en la tabla
                    $("#tbltrabajadores tbody").html(row);
                     
                });
            }

             $(document).on("click",".btn-edit", function(){
                  //alert("diste click en editar");
                  //aqui se obtiene el id de para mostrarl o jalar la informaion 
                  const id_t = $(this).data("id_t");
                
                  $.getJSON("../api/GetT.php", {id_t:id_t}, function(resp){
                 
                     if(!resp.ok) {
                     showAlert('danger', resp.msg || 'ERROR ID NO ENCONTRADO');
                     return;
                   }

                   const data = resp.data;
                    $("#edit-id_t").val(data.id_t);
                    $("#edit-nombre").val(data.nombre);
                    $("#edit-puesto").val(data.puesto);
                    $("#edit-estado").val(data.estado);

                    $("#modaled").modal('show');
                  });
                  
            });

            $("#formT").on("submit", function(e){
                e.preventDefault();
                $.post("../api/EditarT.php",$(this).serialize(), function(resp){
                try{resp = JSON.parse(resp);} catch(e){resp={ok:false, msg:'Error al editar'};}
                if(!resp.ok) {
                     showAlert('danger', resp.msg || 'Error al editar');
                     return;
                   }
                   $("#modaled").modal('hide');
                   showAlert('success', 'Trabajador editado correctamente');
                   cargaTrabajadores();
                });
            });
                cargaTrabajadores();
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
            <a href="../logins/Logout.php">Cerrar Sesion</a>
        </div>
        <div class="container mt-4">
            <!-- la tabla de los trabajadores -->
            <div id="alertBox">
             
            </div>
            <table class="table table-striped table-bordered" id="tbltrabajadores">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Trabajadores</th>
                        <th>Estado</th>
                        <th>Puesto</th>
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

             <div class="modal fade" id="modaled" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Agregar Trabajador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formT">
                        <input type="hidden" id="edit-id_t" name="id_t">
                        <div class="mb-3">
                            <label for="edit-nombre" class="form-label">Nombre del Trabajador</label>
                            <input type="text" class="form-control" id="edit-nombre" name="nombre" required placeholder="Introduce el nombre del trabajador Completo">
                        </div>
                        <div class="md-form mb-4">
                            <label for="edit-estado" class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="edit-estado" required>
                            <option value="Activo">Activo</option>
                            <option value="Inativo">Inativo</option>
                            </select>
                        </div>
                         <div class="md-form mb-4">
                            <label for="edit-puesto" class="form-label">Puesto</label>
                            <select class="form-select" name="puesto" id="edit-puesto" required>
                            <option value="">Ingresar el puesto</option>
                            <option value="a">Activo</option>
                            <option value="b">Inativo</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                        <!-- uso el tipo submit para que envie el formulario -->
                        <button type="submit" form="formT" class="btn btn-primary">Guardar</button>
                        
                         </div>
                    </form>
                </div>
                </div>
            </div>
            </div>
    </div>
</body>
</html>