<?php
// Incluye el archivo de verificación de sesión para asegurar que el usuario ha iniciado sesión antes de acceder a esta página.
session_start();
require_once '../logins/check.php';
//mando a llamar la funcion para validar el inicio de sesion
require_role('ANOTADOR');

$aguas = $_GET['aguas'] ?? '';


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cortes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../js/jquery-4.0.0.js"></script>
    <link rel="stylesheet" href="../CSS/EstiloAdmin.css">


    
    <script>
        $(document).ready(function(){
             function showAlert(type, msg) {
                $("#alertBox").html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                `);
 }    
            $.getJSON("../api/GetUb.php", function(resp){
                if(!resp.ok) {
                     showAlert('danger', resp.msg || 'ERROR ID NO ENCONTRADO');
                     return;
                }
                const data = resp.data;
                $('#producto').val(data.producto); 
                $('#nombre').val(data.ubicacion);    
                $('#valor').val(data.valor);         
                $('#id_producto').val(data.id_producto); 
                $('#id_c').val(data.id_c);            
                
                $('#cantidad').text('cantidad por '+ data.unidad + '*');

            });

            // aqui cargo los tarabajadores para el datalist de para autocompletado
            
                 let cortadoresData = [];
                $.getJSON("../api/ListT.php", function(resp){
                    if(!resp.ok){ showAlert('danger', resp.msg || 'Error'); return; }
                    cortadoresData = resp.data;
                });

                $('#cortador').on('input', function(){
                    let q = $(this).val().toLowerCase();
                    let lista = $('#cortadoresList');
                    if(!q){ lista.hide(); return; }

                    let f = cortadoresData.filter(c => c.nombre.toLowerCase().includes(q));
                    if(!f.length){ lista.hide(); return; }

                    lista.html(f.map(c => '<div class="autocomplete-item" data-nombre="' + c.nombre + '">' + c.nombre + '</div>').join('')).show();
                });

                $(document).on('click', '.autocomplete-item', function(){
                    $('#cortador').val($(this).data('nombre'));
                    $('#cortadoresList').hide();
                });

                $(document).on('click', function(e){
                    if(!$(e.target).closest('#cortador, #cortadoresList').length)
                        $('#cortadoresList').hide();
                });


               $("#formcorte").on("submit", function(e){
                // el preventDefault es para evitar que mande el formulario    
                 e.preventDefault();
                 $.post("../PHP/GuardarCorte.php",$(this).serialize(), function(resp){ 
                 try{resp = JSON.parse(resp); } catch(e){resp = {ok:false, msg:'Error al guardar'};}
                  if(!resp.ok) {
                     showAlert('danger', resp.msg || 'ERROR AL GUARDAR CORTE');
                     return;
                   }
                   showAlert('success', resp.msg || 'CORTE GUARDADO CON EXITO');
                   $('#cortador').val('');
                   $('#cantidad').val('');

                 });

            });
        });
    </script>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row g-0">
                <div class="container py-4 modal-vidrio">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <img src="../imagenes/agrobitacora-logo.png" alt="AgroBitacora" style="height: 100px;"></img>
                        <h1 class="h4 mb-0 text-dark">Registros de Cortes</h1>
                        <div class="d-flex gap-2">
                            <a class="pulse-effect" href="ListaD.php">Cortes del día</a>

                            <a class="btn-salir" href="../logins/logout.php">Cerrar sesion</a>
                        </div>
                    </div>
                    <div id="alertBox">
        
                    </div>
                    <div class="card border-0 shadow-sm modal-vidrio">
                        <div class="card-body p-4">
                            <form id="formcorte">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="producto">Producto *</label>
                                    <input class="form-control" type="hidden" id="id_producto" name="id_producto"readonly>
                                    <input class="form-control" type="text" id="producto" name="producto"readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="nombre">Ubicación *</label>
                                    <input class="form-control" type="hidden" id="id_c" name="id_c" readonly>
                                    <input class="form-control" type="text" id="nombre" name="nombre" readonly>
                                </div> 

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="cortador">Cortador *</label>
                                    <input class="form-control" type="text" id="cortador" name="cortador" required placeholder="Escriba el nombre del empleado"
                                     autocomplete="off">
                                    <div id="cortadoresList" class="autocomplete-items acordeon-vidrio"></div>

                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold" for="valor">Precio del producto *</label>
                                    <input class="form-control" type="number" id="valor" name="valor" required placeholder="0" min="0" style="max-width: 150px;" readonly>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold" id="cantidad"  for="cantidad">Cantidad*</label>
                                    <input class="form-control" type="number" id="cantidad"  name="cantidad" required placeholder="0" min="0" style="max-width: 150px;">
                                </div>
                                <div class="text-center">
                                <button class="pulse-effect" type="submit">Guardar Corte</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        
        </div>
    </div>
</body>
</html>