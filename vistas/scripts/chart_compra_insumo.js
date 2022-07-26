var visitorsChart ;
var salesChart;
//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

	$("#mCompra").addClass("active bg-primary");

  $("#lChartCompraInsumo").addClass("active");

  box_content_reporte(localStorage.getItem("nube_idproyecto"));
  //chart_linea_barra(localStorage.getItem("nube_idproyecto"));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  var anio_actual = moment().format('YYYY');
  lista_select2(`../ajax/chart_compra_insumo.php?op=anios_select2&nube_idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#year_filtro', anio_actual);

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════

  $("#year_filtro").select2({ theme: "bootstrap4", placeholder: "Filtro Año", allowClear: false, });
  $("#month_filtro").select2({ theme: "bootstrap4", placeholder: "Filtro Mes", allowClear: true, });

  $("#month_filtro").val("null").trigger("change");

  // Formato para telefono
  $("[data-mask]").inputmask();
}

// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C H A R T :::::::::::::::::::::::::::::::::::::::::::::

//mostrar datos proveedor pago
function box_content_reporte(idnubeproyecto) {

  $(".cant_proveedores_box").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>');
  $(".cant_producto_box").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>');
  $(".cant_insumos_box").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>');
  $(".cant_activo_fijo_box").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>');

  $.post("../ajax/chart_compra_insumo.php?op=box_content_reporte", { 'idnubeproyecto': idnubeproyecto }, function (e, status) {

    e = JSON.parse(e);   //console.log(e);

    if (e.status == true) {
      $(".cant_proveedores_box").html(e.data.cant_proveedores);
      $(".cant_producto_box").html(e.data.cant_producto);
      $(".cant_insumos_box").html(e.data.cant_insumo);
      $(".cant_activo_fijo_box").html(e.data.cant_activo_fijo);
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

function chart_linea_barra(idnubeproyecto) {
  'use strict'

  $('.cant_ft_aceptadas').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $('.cant_ft_rechazadas').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $('.cant_ft_eliminadas').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $('.cant_ft_rechazadas_eliminadas').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);

  $('.progress_ft_aceptadas').css({ width: `0%`, });
  $('.progress_ft_rechazadas').css({ width: `0%`, });
  $('.progress_ft_eliminadas').css({ width: `0%`, });
  $('.progress_ft_rechazadas_eliminadas').css({ width: `0%`, });
  $('.progress_monto_pagado').css({ width: `0%`, });
  $('.progress_monto_no_pagado').css({ width: `0%`, });

  var ticksStyle = { fontColor: '#495057', fontStyle: 'bold' };

  var mode = 'index'; var intersect = true;

  var idnubeproyecto = localStorage.getItem("nube_idproyecto");
  var year_filtro = $("#year_filtro").select2("val");
  var month_filtro = $("#month_filtro").select2("val");
  var dias_por_mes =cant_dias_mes(year_filtro, month_filtro);
  
  $.post("../ajax/chart_compra_insumo.php?op=chart_linea", { 'idnubeproyecto': idnubeproyecto , 'year_filtro': year_filtro, 'month_filtro':month_filtro, 'dias_por_mes':dias_por_mes }, function (e, status) {
    e = JSON.parse(e);   console.log(e);
    if (e.status == true) {
      // :::::::::::::::::::::::::::::::::::::::::::: C H A R T    P R O G R E S ::::::::::::::::::::::::::::::::::::
      $('.cant_ft_aceptadas').html(`<b>${e.data.factura_aceptadas}</b>/${e.data.factura_total}`);
      $('.cant_ft_rechazadas').html(`<b>${e.data.factura_rechazadas}</b>/${e.data.factura_total}`);
      $('.cant_ft_eliminadas').html(`<b>${e.data.factura_eliminadas}</b>/${e.data.factura_total}`);
      $('.cant_ft_rechazadas_eliminadas').html(`<b>${e.data.factura_rechazadas_eliminadas}</b>/${e.data.factura_total}`);
      var aceptadas = (e.data.factura_aceptadas/e.data.factura_total)*100;
      var rechazadas = (e.data.factura_rechazadas/e.data.factura_total)*100;
      var eliminadas = (e.data.factura_eliminadas/e.data.factura_total)*100;
      var rechazadas_eliminadas = (e.data.factura_rechazadas_eliminadas/e.data.factura_total)*100;
      $('.progress_ft_aceptadas').css({ width: `${aceptadas.toFixed(2)}%`, });
      $('.progress_ft_rechazadas').css({ width: `${rechazadas.toFixed(2)}%`, });
      $('.progress_ft_eliminadas').css({ width: `${eliminadas.toFixed(2)}%`, });
      $('.progress_ft_rechazadas_eliminadas').css({ width: `${rechazadas_eliminadas.toFixed(2)}%`, });

      $('.monto_pagado').html(`<b><small>S/.</small> ${formato_miles(e.data.factura_total_pago)}</b>/ <small>S/.</small> ${formato_miles(e.data.factura_total_gasto)}`);
      var no_pagado = e.data.factura_total_gasto - e.data.factura_total_pago;
      $('.monto_no_pagado').html(`<b><small>S/.</small> ${ formato_miles(no_pagado)}</b>/ <small>S/.</small> ${formato_miles(e.data.factura_total_gasto)}`);
      var monto_pagado = (e.data.factura_total_pago/e.data.factura_total_gasto)*100;
      var monto_no_pagado = (no_pagado/e.data.factura_total_gasto)*100;
      $('.progress_monto_pagado').css({ width: `${monto_pagado.toFixed(2)}%`, });
      $('.progress_monto_no_pagado').css({ width: `${monto_no_pagado.toFixed(2)}%`, });

      // :::::::::::::::::::::::::::::::::::::::::::: C H A R T   L I N E A ::::::::::::::::::::::::::::::::::::
      
      var $visitorsChart = $('#visitors-chart');
      if (visitorsChart) {  visitorsChart.destroy();  } 
      // eslint-disable-next-line no-unused-vars
      visitorsChart = new Chart($visitorsChart, {
        data: {
          labels: mes_o_dia(year_filtro, month_filtro),
          datasets: [
            {
              type: 'line', data: e.data.total_gasto, 
              backgroundColor: 'transparent', borderColor: '#007bff',
              pointBorderColor: '#007bff', pointBackgroundColor: '#007bff',
              fill: false, label: 'Compras',
              // pointHoverBackgroundColor: '#007bff',
              // pointHoverBorderColor    : '#007bff'
            },
            {
              type: 'line',
              data: e.data.total_deposito,
              backgroundColor: 'tansparent', borderColor: '#ced4da',
              pointBorderColor: '#ced4da', pointBackgroundColor: '#ced4da',
              fill: false, label: 'Pago',
              // pointHoverBackgroundColor: '#ced4da',
              // pointHoverBorderColor    : '#ced4da'
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ beginAtZero: true, suggestedMax: 200 }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // ::::::::::::::::::::::::::::::::::::::::::::  C H A R T  B A R R A S   ::::::::::::::::::::::::::::::::::::
      var $salesChart = $('#sales-chart');
      if (salesChart) {  salesChart.destroy();  }
      // eslint-disable-next-line no-unused-vars
      salesChart = new Chart($salesChart, {
        type: 'bar',
        data: {
          labels: mes_o_dia(year_filtro, month_filtro),
          datasets: [
            { backgroundColor: '#007bff', borderColor: '#007bff', data: e.data.total_gasto, label: 'Compras', },
            { backgroundColor: '#ced4da', borderColor: '#ced4da', data: e.data.total_deposito, label: 'Pago', }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {  mode: mode, intersect: intersect },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true  },
          scales: {
            yAxes: [{
              // display: false,
              gridLines: { display: true, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({
                beginAtZero: true,
                // Include a dollar sign in the ticks
                callback: function (value) {
                  if (value >= 1000) { value /= 1000; value += 'k'; }
                  return '$' + value;
                }
              }, ticksStyle)
            }],
            xAxes: [{
              display: true,
              gridLines: { display: false },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::::::::: P R O D U C T O S   M A S   V E N D I D O S ::::::::::::::::::::::::::::::::::::
      var productos_mas_vendidos = "";
      e.data.productos_mas_vendidos.forEach((key, indice) => {
        productos_mas_vendidos = productos_mas_vendidos.concat(`
          <tr>
            <td>
              <img src="../dist/docs/material/img_perfil/${key.imagen}" alt="Product 1" onerror="this.src='../dist/svg/404-v2.svg';" class="img-thumbnail img-circle img-size-32 mr-2">
              ${key.producto}
            </td>
            <td class="text-right">S/ ${formato_miles(key.precio_referencial)}</td>
            <td>              
              ${formato_miles(key.cantidad_vendida)}
            </td>
            <td class="text-right">
              <a href="resumen_insumos.php" class="text-muted"> <i class="fas fa-search"></i> </a>
            </td>
          </tr>
        `);
      });

      $('#tbla_productos_mas_vendidos').html(productos_mas_vendidos);
    } else {
      ver_errores(e);
    }
  });  
}


init();

function mes_o_dia(data_anio, data_mes) {
  
  if (data_anio == null || data_anio == '' || data_mes == null || data_mes == "") {
    return [ 'ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AUG', 'SEP.', 'OCT.', 'NOV.', 'DIC.'];
  } else {
    var array_cant_dias = [];
    var cant_dias = cant_dias_mes(data_anio, data_mes);
    for (var dia = 1; dia <= cant_dias; dia++) {
      array_cant_dias.push(dia);
      
    }
    return array_cant_dias;
  } 
}