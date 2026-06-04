<?php
session_start();
require_once '../logins/check.php';
//mando a llamar la funcion para validar el inicio de sesion
require_role('ADMIN');
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

    function cargaUsuarios(pagina = 1){
            paginaActual = pagina;
            $.getJSON("../api/ListU.php", {pagina: pagina},function(resp){
                if(!resp.ok) {
                    showAlert('danger', resp.msg || 'Error al cargar usuarios');
                    return;
                }
                const row = resp.data.map(s =>`
                    <tr>
                        <td>${s.id_u}</td>
                        <td>${s.usuario}</td>
                        <td>${s.rol}</td>
                        <td>${s.estado}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-1 btn-edit" data-id="${s.id_u}" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </td>
                    </tr>
                `);
                $("#tblusuarios tbody").html(row);

                 let mmds = '<ul class="pagination">';
                     for(let i = 1; i <= resp.total_P; i++){
                       mmds += `<li class="page-item ${i === pagina ? 'active' : ''}">
                                <a class="page-link" href="#" onclick="cargaUsuarios(${i})">${i}</a>
                            </li>`;
                            }
                            mmds += '</ul>';
                            $("#pagination").html(mmds);

            });
        }

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

        

        $(document).on("click", ".btn-edit", function(){
            const id = $(this).data("id");
            $.getJSON("../api/GetU.php", {id_u: id}, function(resp){
                if(!resp.ok){
                    showAlert('danger', resp.msg || 'ERROR ID NO ENCONTRADO');
                    return;
                }
                const data = resp.data;
                $("#edit-id_u").val(data.id_u);
                $("#edit-usuario").val(data.usuario);
                $("#edit-rol").val(data.rol);
                $("#edit-estado").val(data.estado);

                $("#modale").modal('show');
            });
        });
        // para guardar esta cosa
        $("#formadd").on("submit", function(e){
            e.preventDefault();
            $.post("../PHP/GuardarU.php", $(this).serialize(), function(resp){
                try{ resp = JSON.parse(resp); } catch(e){ resp = {ok:false, msg:'Error al guardar'}; }
                if(!resp.ok){
                    showAlert('danger', resp.msg || 'Este usuario ya existe');
                    $("#formadd")[0].reset();
                    return;
                }
                $("#modalt").modal('hide');
                showAlert('success', 'Usuario registrado correctamente');
                $("#formadd")[0].reset();
                cargaUsuarios();
            });
        });

        $("#formedit").on("submit", function(e){
            e.preventDefault();
            $.post("../api/EditarU.php", $(this).serialize(), function(resp){
                try{ resp = JSON.parse(resp); } catch(e){ resp = {ok:false, msg:'Error al editar'}; }
                if(!resp.ok){
                    showAlert('danger', resp.msg || 'Error al editar');
                    return;
                }
                $("#modale").modal('hide');
                showAlert('success', 'Usuario editado correctamente');
                cargaUsuarios();
            });
        });
        cargaUsuarios();
    });
</script>

</head>
<body>
    <div class="d-flex">
        <div class="sidebar vidrio-sidebar d-flex flex-column bg-dark" style="width: 200px; height: 100vh;">
            <div class="textobarsup">NADA</div>
            <a class="textobar" href="Admin.php">Gestionar Trabajadores</a>
            <a class="textobar" href="Gestion_Usua.php">Gestionar Usuarios</a>
            <a class="textobar" href="Gestion_Camp.php">Gestionar Campos</a>
            <a class="textobar" href="Productos.php">Configurar Valores y Productos</a>
            <a class="textobar" href="Reportes.php">Reportes Generales</a>
            <a class="textobar" href="Historial.php">Historial de Registro</a>
            <a class="a-barra-salir" href="../logins/logout.php">Cerrar Sesión</a>
        </div>
        <div class="container mt-4">
            <div id="alertBox"></div>
            <table class="table tabla-transparente" id="tblusuarios">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="text-center text-secondary p-4">Cargando...</td>
                    </tr>
                </tbody>
            </table>
            <div id="pagination" class="d-flex justify-content-center mt-3"></div> 
            <div class="container mt-3 text-center">
                <button class="btn pulse-effect" data-bs-toggle="modal" data-bs-target="#modalt">Añadir Usuario</button>
            </div>
            <div class="modal fade" id="modalt" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content modal-vidrio">
                        <div class="modal-header">
                            <h5 class="modal-title">Agregar Usuario</h5>
                            <button type="button" class="btn-close equis" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../PHP/GuardarU.php" method="post" id="formadd">
                                <div class="mb-3">
                                    <label for="usuario" class="form-label">Usuario</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" required placeholder="Nombre de usuario">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required placeholder="Contraseña" minlength="10">
                                </div>
                                <div class="mb-3">
                                    <label for="rol" class="form-label">Rol</label>
                                    <select class="form-select" name="rol" id="rol" required>
                                        <option value="">Seleccionar Rol</option>
                                        <option value="ADMIN">ADMIN</option>
                                        <option value="ANOTADOR">ANOTADOR</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select" name="estado" id="estado" required>
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                            <button type="submit" form="formadd" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="modale" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formedit">
                        <div class="modal-body">
                            <input type="hidden" id="edit-id_u" name="id_u">
                            <div class="mb-3">
                                <label for="edit-usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="edit-usuario" name="usuario" required placeholder="Nombre de usuario">
                            </div>
                            <div class="mb-3">
                                <label for="edit-password" clsass="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="edit-password" name="password" placeholder="Nueva contraseña o dejar en vacio ">
                            </div>
                            <div class="mb-3">
                                <label for="edit-rol" class="form-label">Rol</label>
                                <select class="form-select" id="edit-rol" name="rol" required>
                                    <option value="ADMIN">ADMIN</option>
                                    <option value="ANOTADOR">ANOTADOR</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit-estado" class="form-label">Estado</label>
                                <select class="form-select" id="edit-estado" name="estado" required>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
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