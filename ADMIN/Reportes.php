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
    <title>Document</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../CSS/EstiloAdmin.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../js/jquery-4.0.0.js"></script>

     <script>
     $.when(
    $.getJSON("../api/ListT.php"),
    $.getJSON("../api/ListC.php"),
    $.getJSON("../api/ListP.php"),
    $.getJSON("../api/ListU.php")
).done(function (t, c, p, u) {
    
    
    var tData = t[0].data || [];
    var cData = c[0].data || [];
    var pData = p[0].data || [];
    var uData = u[0].data || [];
   

    $("#totalTrabajadores").text(tData.length);
    $("#totalCampos").text(cData.length);
    $("#totalProductos").text(pData.length);
    $("#totalUsuarios").text(uData.length);
    

        
        
});

        </script>
</head>
<body>
      <div class="d-flex">
        <div class="sidebar vidrio-sidebar d-flex flex-column bg-dark" style="width: 200px; height: 100vh;">
            <img src="../imagenes/AgroBitacora-logo.png" alt="AgroBitacora"></img>
            <a class="textobar" href="Admin.php">Gestionar Trabajadores</Tr></a>
            <a class="textobar" href="Gestion_Usua.php">Gestionar Usuarios</a>
            <a class="textobar" href="Gestion_Camp.php">Gestionar Campos</a>
            <a class="textobar" href="Productos.php">Configurar Valores y Productos</a>
            <a class="textobar" href="Reportes.php">Reportes Generales</a>
            <a class="textobar" href="Historial.php">Historial de Resgistro</a>
            <a class="a-barra-salir" href="../logins/logout.php">Cerrar Sesión</a>
        </div>
        <div class="container mt-4">
           
             <div id="alertBox">
             
            <div class="container-fluid py-4 px-4">
                <h4 class="mb-4"><i class="bi bi-graph-up-arrow me-2"></i>Reportes Generales</h4>
            
            <div class="row g-2 mb-3" id="cardResumen">
                <div class="col-md-3">
                    <div class="card text-center p-3 modal-vidrio tarjeta-cuadrada">
                        <i class="bi bi-person-badge-fill text-white" ></i>
                            <h3 class="mt-2 mb-0" id="totalTrabajadores">0</h3>
                            <p class="text-muted mb-o texto-reportes">Trabajadores</p>
            </div>
            </div>
                
                <div class="col-md-3">
                    <div class="card text-center p-3 modal-vidrio tarjeta-cuadrada">
                        <i class="bi bi-house-fill text-white" ></i>
                            <h3 class="mt-2 mb-0" id="totalCampos">0</h3>
                            <p class="text-muted mb-o texto-reportes">Campos</p>
            </div>
            </div>
           
                <div class="col-md-3">
                    <div class="card text-center p-3 modal-vidrio tarjeta-cuadrada">
                        <i class="bi bi-box-seam-fill text-white" ></i>
                            <h3 class="mt-2 mb-0" id="totalProductos">0</h3>
                            <p class="text-muted mb-o texto-reportes">Productos</p>
            </div>
            </div>

             <div class="col-md-3">
                    <div class="card text-center p-3 modal-vidrio tarjeta-cuadrada">
                        <i class="bi bi-people-fill text-white" ></i>
                            <h3 class="mt-2 mb-0" id="totalUsuarios">0</h3>
                            <p class="text-muted mb-o texto-reportes">Usuarios</p>
</div>
</div>
</div>






    
</body>
</html>