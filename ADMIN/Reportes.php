<?php
session_start();
require_once '../logins/check.php';
//mando a llamar la funcion para validar el inicio de sesion
require_role('ADMIN');

$aguas = $_GET['aguas'] ?? '';


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
    
    // 1. EL CAMBIO DEL [0]:
    // Como ahorita $.when() solo está ejecutando UNA petición (ListT), 
    // jQuery te entrega el JSON directo, así que le quitamos el [0]
    var tData = t[0].data || [];
    var cData = c[0].data || [];
    var pData = p[0].data || [];
    var uData = u[0].data || [];
   

    $("#totalTrabajadores").text(tData.length);
    
    // 2. EL ERROR DE VARIABLES FANTASMA:
    // Tienes que comentar estas líneas también. Si JavaScript intenta leer
    // cData.length pero cData está comentado arriba, el script explota.
    $("#totalCampos").text(cData.length);
     $("#totalProductos").text(pData.length);
        $("#totalUsuarios").text(uData.length);
    

        
        
});

        </script>
</head>
<body>
      <div class="d-flex">
        <div class="sidebar vidrio-sidebar d-flex flex-column bg-dark" style="width: 200px; height: 100vh;">
            <div Class="textobarsup">NADA</div>
            <a class="textobar" href="Admin.php">Gestionar Trabajadores</Tr></a>
            <a class="textobar" href="Gestion_Usua.php">Gestionar Usuarios</a>
            <a class="textobar" href="Gestion_Camp.php">Gestionar Campos</a>
            <a class="textobar" href="Productos.php">Configurar Valores y Productos</a>
            <a class="textobar" href="Reportes.php">Reportes Generales</a>
            <a class="textobar" href="Historial.php">Historial de Resgistro</a>
            <a class="a-barra-salir" href="../logins/logout.php">Salir</a> 
        </div>
        <div class="container mt-4">
            <!-- la tabla de los trabajadores -->
             <div id="alertBox">
             
            <div class="container-fluid py-4 px-4">
                <h4 class="mb-4"><i class="bi bi-graph-up-arrow me-2"></i>Reportes Generales</h4>
            
            <div class="row g-3 mb-4" id="cardResumen">
                <div class="col-md-3">
                    <div class="card text-center p-3 modal-vidrio">
                        <i class="bi bi-people-fill" ></i>
                            <h3 class="mt-2 mb-0" id="totalTrabajadores">0</h3>
                            <p class="text-muted mb-o texto-reportes">Trabajadores</p>
            </div>
            </div>
                
                <div class="col-md-3">
                    <div class="card text-center p-3 modal-vidrio">
                        <i class="bi bi-people-fill" ></i>
                            <h3 class="mt-2 mb-0" id="totalCampos">0</h3>
                            <p class="text-muted mb-o texto-reportes">Campos</p>
            </div>
            </div>

                <div class="col-md-3">
                    <div class="card text-center p-3 modal-vidrio">
                        <i class="bi bi-people-fill" ></i>
                            <h3 class="mt-2 mb-0" id="totalProductos">0</h3>
                            <p class="text-muted mb-o texto-reportes">Productos</p>
            </div>
            </div>

             <div class="col-md-3">
                    <div class="card text-center p-3 modal-vidrio">
                        <i class="bi bi-people-fill" ></i>
                            <h3 class="mt-2 mb-0" id="totalUsuarios">0</h3>
                            <p class="text-muted mb-o texto-reportes">Usuarios</p>
</div>
</div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
            <div class="card modal-vidrio p-3">
                <h5><i class="bi bi-boxes me-2"></i>Productos con Stock Bajo</h5>
                <div id="totalStockBajo">0</div>
            </div>
    </div>
    <div class="col-md-6">
            <div class="card modal-vidrio p-3">
                <h5><i class="bi bi-boxes me-2"></i>Productos con Stock Alto</h5>
                <div id="totalStockAlto">0</div>
            </div>
    </div>
</div>



    
</body>
</html>