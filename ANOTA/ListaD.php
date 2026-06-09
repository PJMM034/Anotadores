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
    let filtroActual  = 'hoy';
    let fechaElegida  = '';
    let semanaElegida = '';

    function showAlert(type, msg) {
        $("#alertBox").html(`
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`);
    }

    // aqui actualiza el pdf para obtenr informacion
    function actualizarBtnPdf(){
        let urlDia    = '../PDF/GPDFD.php';
        let urlSemana = '../PDF/GPDFSemana.php';

        if(filtroActual === 'hoy'){
            $("#btnPdfDia").attr('href', urlDia);
            $("#btnPdfSemana").attr('href', urlSemana);

        } else if(filtroActual === 'dia' && fechaElegida !== ''){
            // manda la fecha elegidop al pdf del dia
            $("#btnPdfDia").attr('href', urlDia + '?fecha=' + fechaElegida);
            $("#btnPdfSemana").attr('href', urlSemana);

        } else if(filtroActual === 'semana' && semanaElegida !== ''){
            // manda la semana eelgido al pdf de semana
            $("#btnPdfDia").attr('href', urlDia);
            $("#btnPdfSemana").attr('href', urlSemana + '?semana=' + semanaElegida);
        }
    }

    function cargaCortes(){
        let params = { filtro: filtroActual };
        if(filtroActual === 'dia')    params.fecha   = fechaElegida;
        if(filtroActual === 'semana') params.semana  = semanaElegida;

        actualizarBtnPdf();

        $.getJSON("ListCorte.php", params, function(resp){
            if(!resp.ok){
                showAlert('danger', resp.msg || 'Error al cargar cortes');
                return;
            }
            if(resp.data.length === 0){
                $("#acordiondecortes").html('<p class="text-center text-secondary">No hay cortes en este período.</p>');
                return;
            }

            let trabaja = {};
            for(let i = 0; i < resp.data.length; i++){
                let c = resp.data[i];
                if(!trabaja[c.trabajador]){
                    trabaja[c.trabajador] = { cortes: [], total: 0 };
                }
                trabaja[c.trabajador].cortes.push(c);
                trabaja[c.trabajador].total += parseFloat(c.total);
            }

            let mm   = '';
            let inte = 0;
            for(let trabajador in trabaja){
                let t = trabaja[trabajador];

                let cortelist = '';
                for(let hola = 0; hola < t.cortes.length; hola++){
                    let c      = t.cortes[hola];
                    let lahora = c.create_at.split(' ')[1];
                    cortelist += '<div class="border-bottom py-2">';
                    cortelist += '<div class="fw-semibold">' + c.nombre_p + '</div>';
                    cortelist += '<div class="text-secondary small text-light">' + c.cantidad + ' - ' +c.unidad + ' — ' + lahora + '</div>';
                    cortelist += '</div>';
                }

                let bnt    = inte > 0   ? 'collapsed' : '';
                let cerrar = inte === 0 ? ' show'      : '';

                mm += '<div class="accordion-item acordeon-vidrio">';
                mm += '<h2 class="accordion-header">';
                mm += '<button class="accordion-button acordeon-vidrio-cabeza ' + bnt + '" type="button" data-bs-toggle="collapse" data-bs-target="#corte' + inte + '">';
                mm += trabajador.toUpperCase() + ' — Total: ' + t.total.toFixed(1);
                mm += '</button></h2>';
                mm += '<div id="corte' + inte + '" class="accordion-collapse collapse' + cerrar + '" data-bs-parent="#acordiondecortes">';
                let pdfBtn = '<a href="../PDF/GPDF.php?trabajador=' + trabajador + '" class="btn btn-sm btn-outline-danger mb-2 imp" target="_blank">Ver PDF</a>';
                mm += '<div class="accordion-body p-2">' + pdfBtn + cortelist + '</div>';
                mm += '</div></div>';
                inte++;
            }

            $("#acordiondecortes").html(mm);
            // hac el calculo de del dia del trabajaodr
            let totalDia = 0;
            for(let trabajador in trabaja){
                // aquei ase la suma 
                totalDia += trabaja[trabajador].total;
            }
            $("#totalDia").text('Total del día: $' + totalDia.toFixed(2));
        });
    }

    $("#btnHoy").on('click', function(){
        filtroActual  = 'hoy';
        fechaElegida  = '';
        semanaElegida = '';
        $("#inputDia").val('');
        $("#inputSemana").val('');
        cargaCortes();
    });

    $("#inputDia").on('change', function(){
        filtroActual  = 'dia';
        fechaElegida  = $(this).val();
        semanaElegida = '';
        $("#inputSemana").val('');
        cargaCortes();
    });

    $("#inputSemana").on('change', function(){
        filtroActual  = 'semana';
        semanaElegida = $(this).val();
        fechaElegida  = '';
        $("#inputDia").val('');
        cargaCortes();
    });

    cargaCortes();
});
</script>
</head>
<body class="bg-lightm">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <img src="../imagenes/agrobitacora-logo.png" alt="AgroBitacora" style="height:100px;">
            <h1 class="h4 mb-0 textosolo">Cortes del día</h1>
            <a class="btn btn-salir" href="Anotadores.php">Regresar</a>
        </div>

        <div class="d-flex gap-2 mb-3 flex-wrap align-items-center">
            <button id="btnHoy" class="btn btn-danger btn-sm edi">Hoy</button>
            <input type="date" id="inputDia"    class="form-control form-control-sm edi" style="width:auto;">
            <input type="week" id="inputSemana" class="form-control form-control-sm edi" style="width:auto;">
        </div>
        <div class="accordion " id="acordiondecortes">
            <p class="text-center text-secondary">Cargando...</p>
        </div>
       <div class="mt-2 d-flex gap-2 justify-content-center">
        <a id="btnPdfDia" href="../PDF/GPDFD.php" class="btn btn-outline-danger btn-sm imp" target="_blank">PDF del día</a>
        <a id="btnPdfSemana" href="../PDF/GPDFSemana.php" class="btn btn-outline-danger btn-sm imp" target="_blank">PDF semana</a>
       </div>
       <div class="mt-3 text-center">
        <span id="totalDia" class="fw-bold fs-5 textosolo"></span>
      </div>
    </div>
</body>
</html>