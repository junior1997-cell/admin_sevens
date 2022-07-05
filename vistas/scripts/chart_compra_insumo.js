
//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

	$("#mCompra").addClass("active bg-primary");

  $("#lChartCompraInsumo").addClass("active");

  box_content_reporte(localStorage.getItem("nube_idproyecto"));
  chart_linea_barra(localStorage.getItem("nube_idproyecto"));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);

  // ══════════════════════════════════════ INITIALIZE SELECT2 - COMPRAS ══════════════════════════════════════

  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });


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

  var ticksStyle = { fontColor: '#495057', fontStyle: 'bold' };

  var mode = 'index'; var intersect = true;

  
  $.post("../ajax/chart_compra_insumo.php?op=chart_linea", { 'idnubeproyecto': idnubeproyecto }, function (e, status) {
    e = JSON.parse(e);   console.log(e);
    if (e.status == true) {
      // :::::::::::::::::::::::::::::::::::::::::::: C H A R T   B A R R A S ::::::::::::::::::::::::::::::::::::
      console.log(e.data.total_gasto);
      console.log(e.data.total_deposito);
      var $visitorsChart = $('#visitors-chart')
      // eslint-disable-next-line no-unused-vars
      var visitorsChart = new Chart($visitorsChart, {
        data: {
          labels: [ 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          datasets: [
            {
              type: 'line',
              data: e.data.total_gasto,
              backgroundColor: 'transparent',
              borderColor: '#007bff',
              pointBorderColor: '#007bff',
              pointBackgroundColor: '#007bff',
              fill: false
              // pointHoverBackgroundColor: '#007bff',
              // pointHoverBorderColor    : '#007bff'
            },
            {
              type: 'line',
              data: e.data.total_deposito,
              backgroundColor: 'tansparent',
              borderColor: '#ced4da',
              pointBorderColor: '#ced4da',
              pointBackgroundColor: '#ced4da',
              fill: false
              // pointHoverBackgroundColor: '#ced4da',
              // pointHoverBorderColor    : '#ced4da'
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect },
          hover: { mode: mode, intersect: intersect },
          legend: { display: false },
          scales: {
            yAxes: [{
              // display: false,
              gridLines: {
                display: true,
                lineWidth: '4px',
                color: 'rgba(0, 0, 0, .2)',
                zeroLineColor: 'transparent'
              },
              ticks: $.extend({
                beginAtZero: true,
                suggestedMax: 200
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
      // ::::::::::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  ::::::::::::::::::::::::::::::::::::
      var $salesChart = $('#sales-chart');
      // eslint-disable-next-line no-unused-vars
      var salesChart = new Chart($salesChart, {
        type: 'bar',
        data: {
          labels: [ 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          datasets: [
            {
              backgroundColor: '#007bff',
              borderColor: '#007bff',
              data: e.data.total_gasto,
            },
            {
              backgroundColor: '#ced4da',
              borderColor: '#ced4da',
              data: e.data.total_deposito,
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {  mode: mode, intersect: intersect },
          hover: { mode: mode, intersect: intersect },
          legend: { display: false  },
          scales: {
            yAxes: [{
              // display: false,
              gridLines: { display: true, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({
                beginAtZero: true,

                // Include a dollar sign in the ticks
                callback: function (value) {
                  if (value >= 1000) {
                    value /= 1000
                    value += 'k'
                  }

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
    } else {
      
    }
  });
  

  
}


init();

$(function () {
  
})