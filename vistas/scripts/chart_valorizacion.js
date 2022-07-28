var chart_linea_curva_s ;
var chart_barra_curva_s;
var char_linea_utilidad;

var char_linea_compra_insumo;
var char_linea_maquina_y_equipo;
var char_linea_subcontrato;
var char_linea_planilla_seguro;
var char_linea_otro_gasto;
var char_linea_transporte;
var char_linea_hospedaje;
var char_linea_pension;
var char_linea_breack;
var char_linea_comida_extra;


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

  $("#valorizacion_filtro").select2({ theme: "bootstrap4", placeholder: "Filtro valorizacion", allowClear: true, });

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
    num_val = val_split[0]; fecha_inicial = val_split[1]; fecha_final = val_split[2];
  }

  //console.log(array_fechas_valorizacion);
  
  $.post("../ajax/chart_valorizacion.php?op=chart_linea", { 'idnubeproyecto': idnubeproyecto , 'valorizacion_filtro':valorizacion_filtro, 'array_fechas_valorizacion':JSON.stringify(array_fechas_valorizacion), 'fecha_inicial': fecha_inicial, 'fecha_final':fecha_final, 'num_val':num_val,  'cant_valorizacion':cant_valorizacion }, function (e, status) {
    e = JSON.parse(e);   console.log(e);
    if (e.status == true) {
      // :::::::::::::::::::::::::::::::::::::::::::: C H A R T    P R O G R E S ::::::::::::::::::::::::::::::::::::
      // valorizacion
      $('.monto_programado_box').html(`S/. ${formato_miles(e.data.total_monto_programado)}`);
      $('.monto_valorizado_box').html(`S/. ${formato_miles(e.data.total_monto_valorizado)}`);
      $('.monto_gastado_box').html(`S/. ${formato_miles(e.data.total_monto_gastado)}`);
      $('.progress_utilidad_total').html(`S/. ${formato_miles(e.data.total_utilidad)}`);
      var  color_linea_utilidad = "";
      if (e.data.total_utilidad > 0) {
        $('.progress_utilidad_total').removeClass('text-danger').addClass('text-success');
        $('.leyenda_utilidad').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad = "#008000";
      } else {
        $('.progress_utilidad_total').addClass('text-danger').removeClass('text-success');
        $('.leyenda_utilidad').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad = "#dc3545";
      }

      // modulos----
      // compras_insumos      
      var  color_linea_utilidad_compra_insumos = "";
      if (e.data.total_utilidad_compra_insumos > 0) {
        $('.progress_total_utilidad_compra_insumo').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_compra_insumos)}`);
        $('.leyenda_utilidad_compra_insumos').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_compra_insumos = "#008000";
      } else {
        $('.progress_total_utilidad_compra_insumo').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_compra_insumos)}`);
        $('.leyenda_utilidad_compra_insumos').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_compra_insumos = "#dc3545";
      }
      $('.progress_total_compra_insumo').html(`S/. ${formato_miles(e.data.total_monto_compra_insumos)}`);

      // maquinas_y_equipos
      var  color_linea_utilidad_maquina_y_equipo = "";
      if (e.data.total_utilidad_maquina_y_equipo > 0) {
        $('.progress_total_utilidad_maquina_y_equipo').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_maquina_y_equipo)}`);
        $('.leyenda_utilidad_maquina_y_equipos').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_maquina_y_equipo = "#008000";
      } else {
        $('.progress_total_utilidad_maquina_y_equipo').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_maquina_y_equipo)}`);
        $('.leyenda_utilidad_maquina_y_equipos').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_maquina_y_equipo = "#dc3545";
      }
      $('.progress_total_maquina_y_equipo').html(`S/. ${formato_miles(e.data.total_monto_maquina_y_equipo)}`);

      // subcontrato
      var  color_linea_utilidad_subcontrato = "";
      if (e.data.total_utilidad_subcontrato > 0) {
        $('.progress_total_utilidad_subcontrato').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_subcontrato)}`);
        $('.leyenda_utilidad_subcontratos').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_subcontrato = "#008000";
      } else {
        $('.progress_total_utilidad_subcontrato').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_subcontrato)}`);
        $('.leyenda_utilidad_subcontratos').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_subcontrato = "#dc3545";
      }
      $('.progress_total_subcontrato').html(`S/. ${formato_miles(e.data.total_monto_subcontrato)}`);

      // planilla_seguro
      var  color_linea_utilidad_planilla_seguro = "";
      if (e.data.total_utilidad_planilla_seguro > 0) {
        $('.progress_total_utilidad_planilla_seguro').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_planilla_seguro)}`);
        $('.leyenda_utilidad_planilla_seguros').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_planilla_seguro = "#008000";
      } else {
        $('.progress_total_utilidad_planilla_seguro').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_planilla_seguro)}`);
        $('.leyenda_utilidad_planilla_seguros').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_planilla_seguro = "#dc3545";
      }
      $('.progress_total_planilla_seguro').html(`S/. ${formato_miles(e.data.total_monto_planilla_seguro)}`);

      // otro_gasto
      var  color_linea_utilidad_otro_gasto = "";
      if (e.data.total_utilidad_otro_gasto > 0) {
        $('.progress_total_utilidad_otro_gasto').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_otro_gasto)}`);
        $('.leyenda_utilidad_otro_gastos').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_otro_gasto = "#008000";
      } else {
        $('.progress_total_utilidad_otro_gasto').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_otro_gasto)}`);
        $('.leyenda_utilidad_otro_gastos').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_otro_gasto = "#dc3545";
      }
      $('.progress_total_otro_gasto').html(`S/. ${formato_miles(e.data.total_monto_otro_gasto)}`);

      // transporte
      var  color_linea_utilidad_transporte = "";
      if (e.data.total_utilidad_transporte > 0) {
        $('.progress_total_utilidad_transporte').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_transporte)}`);
        $('.leyenda_utilidad_transportes').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_transporte = "#008000";
      } else {
        $('.progress_total_utilidad_transporte').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_transporte)}`);
        $('.leyenda_utilidad_transportes').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_transporte = "#dc3545";
      }
      $('.progress_total_transporte').html(`S/. ${formato_miles(e.data.total_monto_transporte)}`);

      // hospedaje
      var  color_linea_utilidad_hospedaje = "";
      if (e.data.total_utilidad_hospedaje > 0) {
        $('.progress_total_utilidad_hospedaje').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_hospedaje)}`);
        $('.leyenda_utilidad_hospedajes').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_hospedaje = "#008000";
      } else {
        $('.progress_total_utilidad_hospedaje').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_hospedaje)}`);
        $('.leyenda_utilidad_hospedajes').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_hospedaje = "#dc3545";
      }
      $('.progress_total_hospedaje').html(`S/. ${formato_miles(e.data.total_monto_hospedaje)}`);

      // pension
      var  color_linea_utilidad_pension = "";
      if (e.data.total_utilidad_pension > 0) {
        $('.progress_total_utilidad_pension').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_pension)}`);
        $('.leyenda_utilidad_pensions').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_pension = "#008000";
      } else {
        $('.progress_total_utilidad_pension').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_pension)}`);
        $('.leyenda_utilidad_pensions').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_pension = "#dc3545";
      }
      $('.progress_total_pension').html(`S/. ${formato_miles(e.data.total_monto_pension)}`);

      // breack
      var  color_linea_utilidad_breack = "";
      if (e.data.total_utilidad_breack > 0) {
        $('.progress_total_utilidad_breack').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_breack)}`);
        $('.leyenda_utilidad_breacks').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_breack = "#008000";
      } else {
        $('.progress_total_utilidad_breack').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_breack)}`);
        $('.leyenda_utilidad_breacks').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_breack = "#dc3545";
      }
      $('.progress_total_breack').html(`S/. ${formato_miles(e.data.total_monto_breack)}`);

      // comida_extra
      var  color_linea_utilidad_comida_extra = "";
      if (e.data.total_utilidad_comida_extra > 0) {
        $('.progress_total_utilidad_comida_extra').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_comida_extra)}`);
        $('.leyenda_utilidad_comida_extras').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_comida_extra = "#008000";
      } else {
        $('.progress_total_utilidad_comida_extra').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_comida_extra)}`);
        $('.leyenda_utilidad_comida_extras').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_comida_extra = "#dc3545";
      }
      $('.progress_total_comida_extra').html(`S/. ${formato_miles(e.data.total_monto_comida_extra)}`);

      // :::::::::::::::::::::::::::::::::::::::::::: C H A R T   L I N E A S  -  C U R V A  S ::::::::::::::::::::::::::::::::::::
      
      var $chart_linea_curva_s = $('#chart-line-curva-s');
      if (chart_linea_curva_s) {  chart_linea_curva_s.destroy();  } 
      // eslint-disable-next-line no-unused-vars
      chart_linea_curva_s = new Chart($chart_linea_curva_s, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.monto_acumulado_programado, 
              backgroundColor: 'transparent', borderColor: '#000000',
              pointBorderColor: '#000000', pointBackgroundColor: '#000000',
              fill: false, label: 'Programado',
              // pointHoverBackgroundColor: '#000000',
              // pointHoverBorderColor    : '#000000'
            },
            {
              type: 'line',
              data: e.data.monto_acumulado_valorizado,
              backgroundColor: 'tansparent', borderColor: '#ffc107',
              pointBorderColor: '#ffc107', pointBackgroundColor: '#ffc107',
              fill: false, label: 'Valorizado',
              // pointHoverBackgroundColor: '#ffc107',
              // pointHoverBorderColor    : '#ffc107'
            },
            {
              type: 'line',
              data: e.data.monto_acumulado_gastado,
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
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            } 
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) {
                  return 'S/ ' + Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
              }, ticksStyle),              
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
            { backgroundColor: '#000000', borderColor: '#000000', data: e.data.monto_programado, label: 'Programado', },
            { backgroundColor: '#ffc107', borderColor: '#ffc107', data: e.data.monto_valorizado, label: 'Valorizado', },
            { backgroundColor: '#FF0000', borderColor: '#FF0000', data: e.data.monto_gastado, label: 'Gastado', }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {  mode: mode, intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            } 
          },
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
      var $char_linea_utilidad = $('#chart-line-utilidad');
      if (char_linea_utilidad) {  char_linea_utilidad.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_utilidad = new Chart($char_linea_utilidad, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.monto_acumulado_utilidad, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad,
              pointBorderColor: color_linea_utilidad, pointBackgroundColor: color_linea_utilidad,
              fill: false, label: 'Utilidad',
              // pointHoverBackgroundColor: color_linea_utilidad,
              // pointHoverBorderColor    : color_linea_utilidad
            },
            {              
              type: 'line', data: e.data.monto_acumulado_gastado, 
              backgroundColor: 'transparent', borderColor: '#008080',
              pointBorderColor: '#008080', pointBackgroundColor: '#008080',
              fill: false, label: 'Gasto', hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad,
              // pointHoverBorderColor    : color_linea_utilidad
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) {
                  return 'S/ '+ Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
              }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  C O M P R A S   D E   I N S U M O S ::::::::::::::::::::::::
      var $char_linea_compra_insumo = $('#chart-line-compra-de-insumos');
      if (char_linea_compra_insumo) {  char_linea_compra_insumo.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_compra_insumo = new Chart($char_linea_compra_insumo, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.utilidad_acumulado_compra_insumos, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_compra_insumos,
              pointBorderColor: color_linea_utilidad_compra_insumos, pointBackgroundColor: color_linea_utilidad_compra_insumos,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_compra_insumos,
              // pointHoverBorderColor    : color_linea_utilidad_compra_insumos
            },
            {              
              type: 'line', data: e.data.monto_acumulado_compra_insumos, 
              backgroundColor: 'transparent', borderColor: '#008080',
              pointBorderColor: '#008080', pointBackgroundColor: '#008080',
              fill: false, label: 'Compra de insumos',
              // pointHoverBackgroundColor: color_linea_utilidad,
              // pointHoverBorderColor    : color_linea_utilidad
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) {
                  return 'S/ '+ Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
              }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  M A Q U I N A S   Y   E Q U I P O S  ::::::::::::::::::::::::
      var $char_linea_maquina_y_equipo = $('#chart-line-maquina-y-equipo');
      if (char_linea_maquina_y_equipo) {  char_linea_maquina_y_equipo.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_maquina_y_equipo = new Chart($char_linea_maquina_y_equipo, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.utilidad_acumulado_maquina_y_equipo, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_maquina_y_equipo,
              pointBorderColor: color_linea_utilidad_maquina_y_equipo, pointBackgroundColor: color_linea_utilidad_maquina_y_equipo,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_maquina_y_equipo,
              // pointHoverBorderColor    : color_linea_utilidad_maquina_y_equipo
            },
            {              
              type: 'line', data: e.data.monto_acumulado_maquina_y_equipo, 
              backgroundColor: 'transparent', borderColor: '#008080',
              pointBorderColor: '#008080', pointBackgroundColor: '#008080',
              fill: false, label: 'Maquinas y Equipos',
              // pointHoverBackgroundColor: color_linea_utilidad,
              // pointHoverBorderColor    : color_linea_utilidad
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) {
                  return 'S/ '+ Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
              }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  S U B C O N T R A T O  ::::::::::::::::::::::::
      var $char_linea_subcontrato = $('#chart-line-subcontrato');
      if (char_linea_subcontrato) {  char_linea_subcontrato.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_subcontrato = new Chart($char_linea_subcontrato, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.utilidad_acumulado_subcontrato, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_subcontrato,
              pointBorderColor: color_linea_utilidad_subcontrato, pointBackgroundColor: color_linea_utilidad_subcontrato,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_subcontrato,
              // pointHoverBorderColor    : color_linea_utilidad_subcontrato
            },
            {              
              type: 'line', data: e.data.monto_acumulado_subcontrato, 
              backgroundColor: 'transparent', borderColor: '#008080',
              pointBorderColor: '#008080', pointBackgroundColor: '#008080',
              fill: false, label: 'Subcontrato',
              // pointHoverBackgroundColor: color_linea_utilidad,
              // pointHoverBorderColor    : color_linea_utilidad
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) {
                  return 'S/ '+ Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
              }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  P L A N I L L A   S E G U R O  ::::::::::::::::::::::::
      var $char_linea_planilla_seguro = $('#chart-line-planilla-seguro');
      if (char_linea_planilla_seguro) {  char_linea_planilla_seguro.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_planilla_seguro = new Chart($char_linea_planilla_seguro, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.utilidad_acumulado_planilla_seguro, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_planilla_seguro,
              pointBorderColor: color_linea_utilidad_planilla_seguro, pointBackgroundColor: color_linea_utilidad_planilla_seguro,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_planilla_seguro,
              // pointHoverBorderColor    : color_linea_utilidad_planilla_seguro
            },
            {              
              type: 'line', data: e.data.monto_acumulado_planilla_seguro, 
              backgroundColor: 'transparent', borderColor: '#008080',
              pointBorderColor: '#008080', pointBackgroundColor: '#008080',
              fill: false, label: 'Panilla Seguro',
              // pointHoverBackgroundColor: color_linea_utilidad,
              // pointHoverBorderColor    : color_linea_utilidad
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) {
                  return 'S/ '+ Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
              }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  O T R O   G A S T O  ::::::::::::::::::::::::
      var $char_linea_otro_gasto = $('#chart-line-otro-gasto');
      if (char_linea_otro_gasto) {  char_linea_otro_gasto.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_otro_gasto = new Chart($char_linea_otro_gasto, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.utilidad_acumulado_otro_gasto, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_otro_gasto,
              pointBorderColor: color_linea_utilidad_otro_gasto, pointBackgroundColor: color_linea_utilidad_otro_gasto,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_otro_gasto,
              // pointHoverBorderColor    : color_linea_utilidad_otro_gasto
            },
            {              
              type: 'line', data: e.data.monto_acumulado_otro_gasto, 
              backgroundColor: 'transparent', borderColor: '#008080',
              pointBorderColor: '#008080', pointBackgroundColor: '#008080',
              fill: false, label: 'Otro Gasto',
              // pointHoverBackgroundColor: color_linea_utilidad,
              // pointHoverBorderColor    : color_linea_utilidad
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) {
                  return 'S/ '+ Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
              }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  T R A S N P O R T E  ::::::::::::::::::::::::
      var $char_linea_transporte = $('#chart-line-transporte');
      if (char_linea_transporte) {  char_linea_transporte.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_transporte = new Chart($char_linea_transporte, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.utilidad_acumulado_transporte, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_transporte,
              pointBorderColor: color_linea_utilidad_transporte, pointBackgroundColor: color_linea_utilidad_transporte,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_transporte,
              // pointHoverBorderColor    : color_linea_utilidad_transporte
            },
            {              
              type: 'line', data: e.data.monto_acumulado_transporte, 
              backgroundColor: 'transparent', borderColor: '#008080',
              pointBorderColor: '#008080', pointBackgroundColor: '#008080',
              fill: false, label: 'Transporte',
              // pointHoverBackgroundColor: color_linea_utilidad_transporte,
              // pointHoverBorderColor    : color_linea_utilidad_transporte
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) {
                  return 'S/ '+ Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
              }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  H O S P E D A J E  ::::::::::::::::::::::::
      var $char_linea_hospedaje = $('#chart-line-hospedaje');
      if (char_linea_hospedaje) {  char_linea_hospedaje.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_hospedaje = new Chart($char_linea_hospedaje, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.utilidad_acumulado_hospedaje, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_hospedaje,
              pointBorderColor: color_linea_utilidad_hospedaje, pointBackgroundColor: color_linea_utilidad_hospedaje,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_hospedaje,
              // pointHoverBorderColor    : color_linea_utilidad_hospedaje
            },
            {              
              type: 'line', data: e.data.monto_acumulado_hospedaje, 
              backgroundColor: 'transparent', borderColor: '#008080',
              pointBorderColor: '#008080', pointBackgroundColor: '#008080',
              fill: false, label: 'Hospedaje',
              // pointHoverBackgroundColor: color_linea_utilidad,
              // pointHoverBorderColor    : color_linea_utilidad
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) {
                  return 'S/ '+ Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
              }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  P E N S I O N  ::::::::::::::::::::::::
      var $char_linea_pension = $('#chart-line-pension');
      if (char_linea_pension) {  char_linea_pension.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_pension = new Chart($char_linea_pension, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.utilidad_acumulado_pension, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_pension,
              pointBorderColor: color_linea_utilidad_pension, pointBackgroundColor: color_linea_utilidad_pension,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_pension,
              // pointHoverBorderColor    : color_linea_utilidad_pension
            },
            {              
              type: 'line', data: e.data.monto_acumulado_pension, 
              backgroundColor: 'transparent', borderColor: '#008080',
              pointBorderColor: '#008080', pointBackgroundColor: '#008080',
              fill: false, label: 'Pension',
              // pointHoverBackgroundColor: color_linea_utilidad,
              // pointHoverBorderColor    : color_linea_utilidad
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) {
                  return 'S/ '+ Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
              }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  B R E A C K  ::::::::::::::::::::::::
      var $char_linea_breack = $('#chart-line-breack');
      if (char_linea_breack) {  char_linea_breack.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_breack = new Chart($char_linea_breack, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.utilidad_acumulado_breack, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_breack,
              pointBorderColor: color_linea_utilidad_breack, pointBackgroundColor: color_linea_utilidad_breack,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_breack,
              // pointHoverBorderColor    : color_linea_utilidad_breack
            },
            {              
              type: 'line', data: e.data.monto_acumulado_breack, 
              backgroundColor: 'transparent', borderColor: '#008080',
              pointBorderColor: '#008080', pointBackgroundColor: '#008080',
              fill: false, label: 'Breack',
              // pointHoverBackgroundColor: color_linea_utilidad,
              // pointHoverBorderColor    : color_linea_utilidad
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { 
                return "" + data.labels[tooltipItem[0].index]; 
              },
              label: function(tooltipItems, data) {
                return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },
              footer: function (tooltipItem, data) { return "..."; }
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) {
                  return 'S/ '+ Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                },
              }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  C O M I D A   E X T R A  ::::::::::::::::::::::::
      var $char_linea_comida_extra = $('#chart-line-comida-extra');
      if (char_linea_comida_extra) {  char_linea_comida_extra.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_comida_extra = new Chart($char_linea_comida_extra, {
        data: {
          labels: valorizacion_x(valorizacion_filtro, cant_valorizacion),
          datasets: [
            {
              type: 'line', data: e.data.utilidad_acumulado_comida_extra, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_comida_extra,
              pointBorderColor: color_linea_utilidad_comida_extra, pointBackgroundColor: color_linea_utilidad_comida_extra,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_comida_extra,
              // pointHoverBorderColor    : color_linea_utilidad_comida_extra
            },
            {              
              type: 'line', data: e.data.monto_acumulado_comida_extra, 
              backgroundColor: 'transparent', borderColor: '#008080',
              pointBorderColor: '#008080', pointBackgroundColor: '#008080',
              fill: false, label: 'Comida Extra',
              // pointHoverBackgroundColor: color_linea_utilidad,
              // pointHoverBorderColor    : color_linea_utilidad
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: { mode: mode,  intersect: intersect,
            callbacks: {
              title: function (tooltipItem, data) { return "" + data.labels[tooltipItem[0].index]; },
              label: function(tooltipItems, data) { return "Total: S/ " +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ; },
              footer: function (tooltipItem, data) { return "..."; }
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true,  },
          scales: {
            yAxes: [{
              display: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ 
                beginAtZero: true, suggestedMax: 200,
                callback: function (value, index, values) { return 'S/ '+ Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); },
              }, ticksStyle)
            }],
            xAxes: [{ 
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }]
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::::::::: T A B L A   -   T O T A L   D E   M O D U L O S ::::::::::::::::::::::::::::::::::::
      var html_tabla_modulos = "";
      var suma_total_modulo_gasto = 0; var suma_total_modulo_utilidad = 0; 
      e.data.tabla_resumen.forEach((key, indice) => {
        html_tabla_modulos = html_tabla_modulos.concat(`
          <tr>
            <td class="py-2 text-center " >${indice+1}</td>
            <td class="py-2 text-left " >${key.modulo}</td>
            <td class="py-2 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-2 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-2 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_modulo_gasto += key.gasto;
        suma_total_modulo_utilidad += key.utilidad;
      });

      $('#tbla_modulos_total_gasto').html(html_tabla_modulos);
      $('.suma_total_modulo_gasto').html(formato_miles(suma_total_modulo_gasto));
      $('.suma_total_modulo_utilidad').html(formato_miles(suma_total_modulo_utilidad));
      $('[data-toggle="tooltip"]').tooltip();
    } else {
      ver_errores(e);
    }
  });  
}


init();

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

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

function export_excel_resumen() {
  $tabla = document.querySelector("#tabla_resumen_gastos_modulos");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Detalle Resumen Modulos", //Nombre del archivo de Excel
    sheetname: "Detalle", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.tabla_resumen_gastos_modulos.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}