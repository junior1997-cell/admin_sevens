var visitorsChart ;
var chart_barra_curva_s;
var salesChart;

var cant_valorizacion = 0;
var array_fechas_valorizacion = [];
//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lChartValorizacion").addClass("active bg-primary");

  //chart_linea_barra(localStorage.getItem("nube_idproyecto"));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  listar_btn_q_s(localStorage.getItem("nube_idproyecto"));
  
  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════

  $("#valorizacion_filtro").select2({ theme: "bootstrap4", placeholder: "Filtro Año", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

// ver las echas de quincenas
function listar_btn_q_s(nube_idproyecto) {

  $('.cargando_filtro_valorizacion').html('<i class="fas fa-spinner fa-pulse"></i>'); //console.log(nube_idproyecto);
  $('#valorizacion_filtro').html(''); //console.log(nube_idproyecto);

  $.post("../ajax/chart_valorizacion.php?op=listar_btn_q_s", { nube_idproyecto: nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); //console.log(e);

    // VALIDAMOS LAS FECHAS DE QUINCENA
    if (e.data) {     
        
      if (e.data.fecha_valorizacion == "quincenal") {

        $(".h1-titulo").html("Reportes Valorización - <b>Quincenal</b>");
        $("#valorizacion_filtro").append(`<option value="0" >Todos</option>`);

        var fechas_btn = fechas_valorizacion_quincena(e.data.fecha_inicio, e.data.fecha_fin); 
        //console.log(fechas_btn);  

        fechas_btn.forEach((key, indice) => {
          cant_valorizacion = key.num_q_s;
          $('#lista_quincenas').append(` <button id="boton-${key.num_q_s}" type="button" class="mb-2 btn bg-gradient-info text-center btn-sm" onclick="fecha_quincena('${format_a_m_d(key.fecha_inicio)}', '${format_a_m_d(key.fecha_fin)}', '${key.num_q_s}');"><i class="far fa-calendar-alt"></i> Valorización ${key.num_q_s}<br>${key.fecha_inicio} // ${key.fecha_fin}</button>`)
          $("#valorizacion_filtro").append(`<option value="${key.num_q_s} ${format_a_m_d(key.fecha_inicio)} ${format_a_m_d(key.fecha_fin)}" >Val ${key.num_q_s} ─ ${key.fecha_inicio} - ${key.fecha_fin}</option>`);
          array_fechas_valorizacion.push({ 'fecha_i':format_a_m_d(key.fecha_inicio), 'fecha_f':format_a_m_d(key.fecha_fin), 'num_val': key.num_q_s, });
        });

        chart_linea_barra();

      } else {

        if (e.data.fecha_valorizacion == "mensual") {

          $(".h1-titulo").html("Reportes Valorización - <b>Mensual</b>");
          $("#valorizacion_filtro").append(`<option value="0" >Todos</option>`);

          var fechas_btn = fechas_valorizacion_mensual(e.data.fecha_inicio, e.data.fecha_fin); 
          //console.log(fechas_btn);  

          fechas_btn.forEach((key, indice) => {
            cant_valorizacion = key.num_q_s;
            $('#lista_quincenas').append(` <button id="boton-${key.num_q_s}" type="button" class="mb-2 btn bg-gradient-info text-center btn-sm" onclick="fecha_quincena('${format_a_m_d(key.fecha_inicio)}', '${format_a_m_d(key.fecha_fin)}', '${key.num_q_s}');"><i class="far fa-calendar-alt"></i> Valorización ${key.num_q_s}<br>${key.fecha_inicio} // ${key.fecha_fin}</button>`)
            $("#valorizacion_filtro").append(`<option value="${key.num_q_s} ${format_a_m_d(key.fecha_inicio)} ${format_a_m_d(key.fecha_fin)}" >Val ${key.num_q_s} ─ ${key.fecha_inicio} - ${key.fecha_fin}</option>`);
            array_fechas_valorizacion.push({ 'fecha_i':format_a_m_d(key.fecha_inicio), 'fecha_f':format_a_m_d(key.fecha_fin), 'num_val': key.num_q_s, });
          });

          chart_linea_barra();

        } else {

          if (e.data.fecha_valorizacion == "al finalizar") {

            $(".h1-titulo").html("Reportes Valorización - <b>Al finalizar</b>");
            $("#valorizacion_filtro").append(`<option value="0" >Todos</option>`);

            $('#lista_quincenas').append(` <button id="boton-0" type="button" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="fecha_quincena('${e.data.fecha_inicio}', '${e.data.fecha_fin}', '0');"><i class="far fa-calendar-alt"></i> Valorización 1<br>${format_d_m_a(e.data.fecha_inicio)} // ${format_d_m_a(e.data.fecha_fin)}</button>`)
            $("#valorizacion_filtro").append(`<option value="${i+1} ${fecha_ii} ${fecha_ff}" >Val ${i+1} ─ ${format_d_m_a(fecha_ii)} - ${format_d_m_a(fecha_ff)}</option>`);
            cant_valorizacion = 1;
            array_fechas_valorizacion.push({'fecha_i':fecha_ii, 'fecha_f':fecha_ff, 'num_val':i+1,});

            chart_linea_barra();

          } else {
            $('#valorizacion_filtro').html(`<option value="" >No hay fechas, no has selecionado tipo pago.</option>`);
          }
        }
      }   
      
      $('.cargando_filtro_valorizacion').html('<i class="far fa-calendar-alt"></i>');

    } else {
      $('#lista_quincenas').html('<option value="" >No hay fechas, editalas en su modulo correspondiente.</option>');
    }    
    //console.log(fecha);
  });
}

// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C H A R T :::::::::::::::::::::::::::::::::::::::::::::

function chart_linea_barra() {
  'use strict'

  $('.monto_programado_box').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $('.monto_valorizado_box').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $('.monto_gastado_box').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $('.progress_utilidad_total').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);

  var ticksStyle = { fontColor: '#495057', fontStyle: 'bold' };

  var mode = 'index'; var intersect = true;

  var idnubeproyecto = localStorage.getItem("nube_idproyecto");
  var valorizacion_filtro = $("#valorizacion_filtro").select2("val");

  var fecha_inicial = ''; var fecha_final = ''; var num_val = '';
  if ( valorizacion_filtro == '0'|| valorizacion_filtro == ''|| valorizacion_filtro == null ) {  }else{
    var val_split = valorizacion_filtro.split(' ');
    num_val = val_split[0];
    fecha_inicial = val_split[1];
    fecha_final = val_split[2];
  }

  //console.log(array_fechas_valorizacion);
  
  $.post("../ajax/chart_valorizacion.php?op=chart_linea", { 'idnubeproyecto': idnubeproyecto , 'valorizacion_filtro':valorizacion_filtro, 'array_fechas_valorizacion':JSON.stringify(array_fechas_valorizacion), 'fecha_inicial': fecha_inicial, 'fecha_final':fecha_final, 'num_val':num_val,  'cant_valorizacion':cant_valorizacion }, function (e, status) {
    e = JSON.parse(e);   console.log(e);
    if (e.status == true) {
      // :::::::::::::::::::::::::::::::::::::::::::: C H A R T    P R O G R E S ::::::::::::::::::::::::::::::::::::
      $('.monto_programado_box').html(`S/. ${formato_miles(e.data.total_monto_programado)}`);
      $('.monto_valorizado_box').html(`S/. ${formato_miles(e.data.total_monto_valorizado)}`);
      $('.monto_gastado_box').html(`S/. ${formato_miles(e.data.total_monto_gastado)}`);
      $('.progress_utilidad_total').html(`S/. ${formato_miles(e.data.total_utilidad)}`);

      // :::::::::::::::::::::::::::::::::::::::::::: C H A R T   L I N E A S  -  C U R V A  S ::::::::::::::::::::::::::::::::::::
      
      var $visitorsChart = $('#chart-line-curva-s');
      if (visitorsChart) {  visitorsChart.destroy();  } 
      // eslint-disable-next-line no-unused-vars
      visitorsChart = new Chart($visitorsChart, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.monto_programado_acumulado, 
              backgroundColor: 'transparent', borderColor: '#000000',
              pointBorderColor: '#000000', pointBackgroundColor: '#000000',
              fill: false, label: 'Programado',
              // pointHoverBackgroundColor: '#000000',
              // pointHoverBorderColor    : '#000000'
            },
            {
              type: 'line',
              data: e.data.monto_valorizado_acumulado,
              backgroundColor: 'tansparent', borderColor: '#ffc107',
              pointBorderColor: '#ffc107', pointBackgroundColor: '#ffc107',
              fill: false, label: 'Valorizado',
              // pointHoverBackgroundColor: '#ffc107',
              // pointHoverBorderColor    : '#ffc107'
            },
            {
              type: 'line',
              data: e.data.monto_gastado_acumulado,
              backgroundColor: 'tansparent', borderColor: '#FF0000',
              pointBorderColor: '#FF0000', pointBackgroundColor: '#FF0000',
              fill: false, label: 'Gastado',
              // pointHoverBackgroundColor: '#FF0000',
              // pointHoverBorderColor    : '#FF0000'
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

      // ::::::::::::::::::::::::::::::::::::::::::::  C H A R T  B A R R A S  -  C U R V A  S  ::::::::::::::::::::::::::::::::::::
      var $chart_barra_curva_s = $('#chart-barra-curva-s');
      if (chart_barra_curva_s) {  chart_barra_curva_s.destroy();  }
      // eslint-disable-next-line no-unused-vars
      chart_barra_curva_s = new Chart($chart_barra_curva_s, {
        type: 'bar',
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            { backgroundColor: '#000000', borderColor: '#000000', data: e.data.monto_programado_acumulado, label: 'Programado', },
            { backgroundColor: '#ffc107', borderColor: '#ffc107', data: e.data.monto_valorizado_acumulado, label: 'Valorizado', },
            { backgroundColor: '#FF0000', borderColor: '#FF0000', data: e.data.monto_gastado_acumulado, label: 'Gastado', }
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

      // ::::::::::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  U T I L I D A D ::::::::::::::::::::::::::::::::::::
      var $salesChart = $('#chart-line-utilidad');
      if (salesChart) {  salesChart.destroy();  }
      // eslint-disable-next-line no-unused-vars
      salesChart = new Chart($salesChart, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.monto_utilidad_acumulado, 
              backgroundColor: 'transparent', borderColor: '#008000',
              pointBorderColor: '#008000', pointBackgroundColor: '#008000',
              fill: false, label: 'Utilidad',
              // pointHoverBackgroundColor: '#008000',
              // pointHoverBorderColor    : '#008000'
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

      // :::::::::::::::::::::::::::::::::::::::::::: P R O D U C T O S   M A S   V E N D I D O S ::::::::::::::::::::::::::::::::::::
      
    } else {
      ver_errores(e);
    }
  });  
}


init();

function valorizacion_x(valorizacion_filtro, cant_valorizacion) {
  
  if (valorizacion_filtro == null || valorizacion_filtro == '' || valorizacion_filtro == '0' || cant_valorizacion == null || cant_valorizacion == "") {
    var array_cant_val = [];
    for (let val = 1; val <= cant_valorizacion; val++) { array_cant_val.push(`Val. ${val}`); }
    return array_cant_val;
  } else {
    var array_cant_val = [];

    var numero_f1_f2 = valorizacion_filtro.split(" ");
    var n_q_s = numero_f1_f2[0];
    var fecha_inicial = numero_f1_f2[1];
    var fecha_final = numero_f1_f2[2];

    var fecha_iterativa = format_d_m_a(numero_f1_f2[1]);
    
    //console.log('inicio----------'+ fecha_iterativa);
    while (true) {
      
      if (validarFechaEnRango(fecha_inicial, fecha_final, format_a_m_d(fecha_iterativa)) == true) {
        array_cant_val.push(fecha_iterativa); //console.log(fecha_iterativa);
      } else {
        break;
      }      
      fecha_iterativa = sumaFecha(1, fecha_iterativa);      
    }
    //console.log(array_cant_val);    
    return array_cant_val;
  } 
}

function ver_modulos() {
  $('#modal-modulos-incluidos').modal('show');
}