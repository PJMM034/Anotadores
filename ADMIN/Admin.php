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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../CSS/EstiloAdmin.css">
    <script src="../js/jquery-4.0.0.js"></script>

<script>

    let paginaActual = 1;

     function cargaTrabajadores(pagina = 1){
                paginaActual = pagina;
                $.getJSON ("../api/ListT.php",{pagina:pagina} ,function(resp){
                    // Procesar los datos recibidos
                   if(!resp.ok) {
                     showAlert('danger', resp.msg || 'datos');
                     //return;
                   }
                        const row = resp.data.map(s =>`
                          <tr class="tabladiseño"> 
                            <td>${s.id_t}</td>
                            <td>${s.nombre}</td>
                            
                            <td>${s.estado}</td>
                            <td class="text-end text-center">
                              <button class="btn justify-center-content btn-sm btn-outline-primary me-1 btn-edit" data-id_t="${s.id_t}" title="Editar">
                             <i class="bi bi-pencil"></i>
                              </button>
                          </td>
                          </tr>
                     `);
                    //aqui se muestra la informacion de los alumnos en la tabla
                    $("#tbltrabajadores tbody").html(row);
                     //aqui empieza la recontruccion de la paginacion
                     let mmds = '<ul class="pagination">';
                     for(let i = 1; i <= resp.total_P; i++){
                       mmds += `<li class="page-item  ${i === pagina ? 'active' : ''}">
                                <a class="page-link paginador" href="#" onclick="cargaTrabajadores(${i})">${i}</a>
                            </li>`;
                            }
                            mmds += '</ul>';
                            $("#pagination").html(mmds);
                });
            }
      
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
                    
                    $("#edit-estado").val(data.estado);

                    $("#modaled").modal('show');
                  });
                  
            });

             $("#form").on("submit", function(e){
                e.preventDefault();
                $.post("../PHP/GuardarT.php",$(this).serialize(), function(resp){
                try{resp = JSON.parse(resp);} catch(e){resp={ok:false, msg:'Error al editar'};}
                if(!resp.ok) {
                     showAlert('danger', resp.msg || 'Este trabajador ya existe');
                     $("#form")[0].reset();
                     return;
                   }
                   $("#modalt").modal('hide');
                   showAlert('success', 'Trabajador registrado correctamente');
                    $("#form")[0].reset();
                   cargaTrabajadores();
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
        <div class="sidebar vidrio-sidebar d-flex flex-column bg-dark" style="width: 200px; height: 100vh;">
            <img src="../imagenes/AgroBitacora-logo.png" alt="AgroBitacora"></img>
            <a class="textobar" href="Admin.php">Gestionar Trabajadores</a>
            <a class="textobar" href="Gestion_Usua.php">Gestionar Usuarios</a>
            <a class="textobar" href="Gestion_Camp.php">Gestionar Campos</a>
            <a class="textobar" href="Productos.php">Configurar Valores y Productos</a>
            <a class="textobar" href="Reportes.php">Reportes Generales</a>
            <a class="textobar" href="Historial.php">Historial de Resgistro</a>
            <a class="a-barra-salir" href="../logins/logout.php">Cerrar Sesión</a>
        </div>
        <div class="container mt-4">
            <!-- la tabla de los trabajadores -->
            <div id="alertBox">
             
            </div>
            <table class="table tabla-transparente" id="tbltrabajadores">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Trabajadores</th>
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
            <div id="pagination" class="d-flex justify-content-center mt-3"></div> 
            <div class="container mt-3 text-center">
                <button class="btn pulse-effect btn-primary px-5" data-bs-toggle="modal" data-bs-target="#modalt">Añadir Trabajador</button>
            </div>
            <div class="modal fade" id="modalt" tabindex="-1" aria-labelledby="modalt" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modal-vidrio">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalt">Agregar Trabajador</h1>
                    <button type="button" class="btn-close equis" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="form">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Trabajador</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Introduce el nombre del trabajador Completo">
                        </div>
                        <div class="md-form mb-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="estado " required>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button " class="btn btn-salir" data-bs-dismiss="modal">Salir</button>
                    <!-- uso el tipo submit para que envie el formulario -->
                    <button type="submit " form="form" class="btn pulse-effect" data-bs-dismiss="modal">Guardar</button>
                    
                </div>
                </div>
            </div>
            </div>
        </div>


             <div class="modal fade" id="modaled" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modal-vidrio">
                <div class="modal-header">
                    <h1 class="modal-title ">Agregar Trabajador</h1>
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
                            <option value="Inactivo">Inactivo</option>
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