<?php
session_start();
require_once '../logins/check.php';
require_role('ANOTADOR');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cortes del día</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
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

    function cargaCortes(){
        $.getJSON("ListCorte.php", function(resp){
            if(!resp.ok){
                showAlert('danger', resp.msg || 'Error al cargar cortes');
                return;
            }

            if(resp.data.length === 0){
                $("#acordiondecortes").html('<p class="text-center text-secondary">No hay cortes registrados hoy.</p>');
                return;
            }
            
            // aqui se agrupa los cortes por cada trabajador    
            let trabaja= {};
            for(let i = 0; i < resp.data.length; i++){
                let c = resp.data[i];
                if(!trabaja[c.trabajador]){
                    trabaja[c.trabajador] = { cortes: [], total: 0 };
                }
                trabaja[c.trabajador].cortes.push(c);
                trabaja[c.trabajador].total += parseFloat(c.total);
            }

            // aqui se genra el html de acordion de los cortes e agrupado por el trabajor
            let mm = '';
            let inte = 0;
            for(let trabajador in trabaja){
                // en esta parte guarda el como t los datos del ttabajador atual de corte y total
                let t = trabaja[trabajador];

                let cortelist = '';
                for(let hola = 0; hola < t.cortes.length; hola++){
                    let c = t.cortes[hola];

                    let lahora =  c.create_at.split(' ')[1];
                        cortelist += '<div class="border-bottom py-2">';
                        // que muestra el nombre nombre del producto  y lo mando llamar con nombre_p solo para mostrar el nombre 
                        cortelist += '<div class="fw-semibold">' + c.nombre_p + '</div>';
                        // qui se muestra las candidas y hora de cada corte 
                        cortelist += '<div class="text-secondary small">' + c.cantidad + ' Kg — ' + lahora + '</div>';
                        cortelist += '<a href="../PDF/GPDF.php?trabajador=' + c.trabajador + '" class="btn btn-sm btn-outline-primary mt-2">Ver PDF</a>';
                        
                        cortelist += '</div>';
                    
                }

            let bnt = '';
            if(inte > 0 ){
                bnt = 'collapsed';
            }
            let cerrar = '';
            if(inte == 0){
                cerrar = 'show';
            }
            mm += '<div class="accordion-item">';
            mm += '<h2 class="accordion-header">';
            mm += '<button class="accordion-button ' + bnt + '" type="button" data-bs-toggle="collapse" data-bs-target="#corte' + inte + '">';
            mm += trabajador.toUpperCase() + ' — Total: ' + t.total.toFixed(1);
            mm += '</button></h2>';
            mm += '<div id="corte' + inte + '" class="accordion-collapse collapse ' + cerrar + '" data-bs-parent="#acordiondecortes">';
            mm += '<div class="accordion-body p-2">' + cortelist + '</div>';
            mm += '</div></div>';
            
            inte++;
            }
           

            $("#acordiondecortes").html(mm);
        });
    }

    cargaCortes();
});
</script>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 mb-0 text-dark">Cortes del día</h1>
            <a class="btn btn-outline-danger" href="Anotadores.php">Regresar</a>
        </div>

        <div class="accordion" id="acordiondecortes">
            <p class="text-center text-secondary">Cargando...</p>
        </div>
    </div>
</body>
</html>