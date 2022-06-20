
//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

	$("#mCompra").addClass("active bg-primary");

  $("#lChartCompraInsumo").addClass("active");

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);

  // ══════════════════════════════════════ INITIALIZE SELECT2 - COMPRAS ══════════════════════════════════════

  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });


  // Formato para telefono
  $("[data-mask]").inputmask();
}

// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C H A R T :::::::::::::::::::::::::::::::::::::::::::::

//mostrar datos proveedor pago
function most_datos_prov_pago(idcompra_proyecto) {
  // limpiar_form_pago_compra();
  $("#h4_mostrar_beneficiario").html("");
  $("#idproyecto_pago").val("");

  $("#banco_pago").val("").trigger("change");

  $.post("../ajax/compra_insumos.php?op=most_datos_prov_pago", { idcompra_proyecto: idcompra_proyecto }, function (e, status) {

    e = JSON.parse(e);   //console.log(e);

    if (e.status == true) {
      $("#idproyecto_pago").val(e.data.idproyecto);
      $("#idcompra_proyecto_p").val(e.data.idcompra_proyecto);
      $("#idproveedor_pago").val(e.data.idproveedor);
      $("#beneficiario_pago").val(e.data.razon_social);
      $("#h4_mostrar_beneficiario").html(e.data.razon_social);
      $("#banco_pago").val(e.data.idbancos).trigger("change");
      $("#tipo_pago").val('Proveedor').trigger("change");
      $("#titular_cuenta_pago").val(e.data.titular_cuenta);
      localStorage.setItem("nubecompra_c_b", e.data.cuenta_bancaria);
      localStorage.setItem("nubecompra_c_d", e.data.cuenta_detracciones);

      if ($("#tipo_pago").select2("val") == "Proveedor") {$("#cuenta_destino_pago").val(e.data.cuenta_bancaria);}
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}


init();

$(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  var $salesChart = $('#sales-chart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: [ 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor: '#007bff',
          data: [700, 100, 600, 800, 1000, 2000, 3000, 5000, 2500, 9700, 2500, 3000]
        },
        {
          backgroundColor: '#ced4da',
          borderColor: '#ced4da',
          data: [700, 800, 200, 1700, 2700, 2000, 1800, 1500, 2000, 600, 800, 1000,]
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
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

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return '$' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })

  var $visitorsChart = $('#visitors-chart')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels: [ 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      datasets: [
        {
          type: 'line',
          data: [700, 100, 600, 800, 1000, 2000, 3000, 5000, 2500, 9700, 2500, 3000],
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
          data: [700, 800, 200, 1700, 2700, 2000, 1800, 1500, 2000, 600, 800, 1000,],
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
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
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
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
})