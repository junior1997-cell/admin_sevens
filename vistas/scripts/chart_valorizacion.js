var chart_linea_curva_s ;
var chart_barra_curva_s;
var char_linea_utilidad;

var char_linea_compra_insumo;
var char_linea_maquina_y_equipo;
var char_linea_subcontrato;
var char_linea_mano_de_obra;
var char_linea_planilla_seguro;
var char_linea_otro_gasto;
var char_linea_transporte;
var char_linea_hospedaje;
var char_linea_pension;
var char_linea_breack;
var char_linea_comida_extra;
var char_linea_pago_administrador;
var char_linea_pago_obrero;

var chart_barra_resumen_modulos;

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

    e =JSON.parse(e); console.log(e);

    // VALIDAMOS LAS FECHAS DE QUINCENA
    if (e.data.fechas_val.length === 0) { 
      $('#lista_quincenas').html(`<div class="info-box shadow-lg w-300px">
        <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Alerta</span>
          <span class="info-box-number">Las fechas del proyecto <br> es menor de 1 día.</span>
        </div>
      </div>`); 
    } else {

      var html_select_filtro = '', f_i_o = e.data.fechas_val[0], f_f_o = e.data.fechas_val.slice(-1).pop();
      
      if ( e.data.fecha_valorizacion == "quincenal") {
        $(".h1-titulo").html(`Valorización - <b>Quincenal</b> (${format_d_m_a(e.data.fecha_inicio)} al ${format_d_m_a(e.data.fecha_fin)})`);
      }  else if ( e.data.fecha_valorizacion == "mensual") {
        $(".h1-titulo").html(`Valorización - <b>Mensual</b> (${format_d_m_a(e.data.fecha_inicio)} al ${format_d_m_a(e.data.fecha_fin)})`);
      }  else if ( e.data.fecha_valorizacion == "al finalizar") {
        $(".h1-titulo").html(`Valorización - <b>Al finalizar</b> (${format_d_m_a(e.data.fecha_inicio)} al ${format_d_m_a(e.data.fecha_fin)})`);
      }

      e.data.fechas_val.forEach((val, key) => {       

        cant_valorizacion = val.numero_q_s;
        $('#lista_quincenas').append(` <button id="boton-${val.numero_q_s}" type="button" class="mb-2 btn bg-gradient-info text-center btn-sm" onclick="fecha_quincena('${val.fecha_inicio}', '${val.fecha_fin}', '${val.numero_q_s}');"><i class="far fa-calendar-alt"></i> Valorización ${val.numero_q_s}<br>${val.fecha_inicio} // ${val.fecha_fin}</button>`)
        html_select_filtro = html_select_filtro.concat(`<option value="${val.idresumen_q_s_valorizacion}" numero_q_s="${val.numero_q_s}" fecha_inicio_oculto="${val.fecha_inicio_oculto}" fecha_fin_oculto="${val.fecha_fin_oculto}" >Val ${val.numero_q_s} ─ ${format_d_m_a(val.fecha_inicio)} - ${format_d_m_a(val.fecha_fin)}</option>`);
        array_fechas_valorizacion.push({  
          'fecha_inicio':       val.fecha_inicio, 
          'fecha_fin':          val.fecha_fin, 
          'fecha_inicio_oculto':val.fecha_inicio_oculto, 
          'fecha_fin_oculto':   val.fecha_fin_oculto, 
          'num_val':            val.numero_q_s, }
        );
        // cant_valorizaciones = val.numero_q_s;         
      });

      $("#valorizacion_filtro").html(`<option value="0" numero_q_s="0" fecha_inicio_oculto="${f_i_o.fecha_inicio_oculto}" fecha_fin_oculto="${f_f_o.fecha_fin_oculto}" >Todos ─ ${format_d_m_a(e.data.fecha_inicio)} - ${format_d_m_a(e.data.fecha_fin)}</option> ${html_select_filtro}`);
      $('.cargando_filtro_valorizacion').html('<i class="far fa-calendar-alt"></i>');
  
      chart_linea_barra();
    }
    
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

  if ( valorizacion_filtro == ''|| valorizacion_filtro == null ) {  }else{     
    num_val = $('#valorizacion_filtro').select2('data')[0].element.attributes.numero_q_s.value;
    fecha_inicial = $('#valorizacion_filtro').select2('data')[0].element.attributes.fecha_inicio_oculto.value;
    fecha_final = $('#valorizacion_filtro').select2('data')[0].element.attributes.fecha_fin_oculto.value;
  }

  console.log(array_fechas_valorizacion); console.log(num_val); console.log(fecha_inicial);console.log(fecha_final);
  
  $.post("../ajax/chart_valorizacion.php?op=chart_linea", { 'idnubeproyecto': idnubeproyecto , 'valorizacion_filtro':valorizacion_filtro, 'array_fechas_valorizacion':JSON.stringify(array_fechas_valorizacion), 'fecha_inicial': fecha_inicial, 'fecha_final':fecha_final, 'num_val':num_val,  'cant_valorizacion':cant_valorizacion }, function (e, status) {
    e = JSON.parse(e);   //console.log(e);
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
        $('.leyenda_utilidad_compra_insumo').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_compra_insumos = "#008000";
      } else {
        $('.progress_total_utilidad_compra_insumo').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_compra_insumos)}`);
        $('.leyenda_utilidad_compra_insumo').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_compra_insumos = "#dc3545";
      }
      $('.progress_total_compra_insumo').html(`S/. ${formato_miles(e.data.total_monto_compra_insumos)}`);

      // maquinas_y_equipos
      var  color_linea_utilidad_maquina_y_equipo = "";
      if (e.data.total_utilidad_maquina_y_equipo > 0) {
        $('.progress_total_utilidad_maquina_y_equipo').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_maquina_y_equipo)}`);
        $('.leyenda_utilidad_maquina_y_equipo').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_maquina_y_equipo = "#008000";
      } else {
        $('.progress_total_utilidad_maquina_y_equipo').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_maquina_y_equipo)}`);
        $('.leyenda_utilidad_maquina_y_equipo').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_maquina_y_equipo = "#dc3545";
      }
      $('.progress_total_maquina_y_equipo').html(`S/. ${formato_miles(e.data.total_monto_maquina_y_equipo)}`);

      // subcontrato
      var  color_linea_utilidad_subcontrato = "";
      if (e.data.total_utilidad_subcontrato > 0) {
        $('.progress_total_utilidad_subcontrato').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_subcontrato)}`);
        $('.leyenda_utilidad_subcontrato').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_subcontrato = "#008000";
      } else {
        $('.progress_total_utilidad_subcontrato').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_subcontrato)}`);
        $('.leyenda_utilidad_subcontrato').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_subcontrato = "#dc3545";
      }
      $('.progress_total_subcontrato').html(`S/. ${formato_miles(e.data.total_monto_subcontrato)}`);

      // mano_de_obra
      var  color_linea_utilidad_mano_de_obra = "";
      if (e.data.total_utilidad_mano_de_obra > 0) {
        $('.progress_total_utilidad_mano_de_obra').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_mano_de_obra)}`);
        $('.leyenda_utilidad_mano_de_obra').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_mano_de_obra = "#008000";
      } else {
        $('.progress_total_utilidad_mano_de_obra').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_mano_de_obra)}`);
        $('.leyenda_utilidad_mano_de_obra').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_mano_de_obra = "#dc3545";
      }
      $('.progress_total_mano_de_obra').html(`S/. ${formato_miles(e.data.total_monto_mano_de_obra)}`);

      // planilla_seguro
      var  color_linea_utilidad_planilla_seguro = "";
      if (e.data.total_utilidad_planilla_seguro > 0) {
        $('.progress_total_utilidad_planilla_seguro').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_planilla_seguro)}`);
        $('.leyenda_utilidad_planilla_seguro').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_planilla_seguro = "#008000";
      } else {
        $('.progress_total_utilidad_planilla_seguro').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_planilla_seguro)}`);
        $('.leyenda_utilidad_planilla_seguro').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_planilla_seguro = "#dc3545";
      }
      $('.progress_total_planilla_seguro').html(`S/. ${formato_miles(e.data.total_monto_planilla_seguro)}`);

      // otro_gasto
      var  color_linea_utilidad_otro_gasto = "";
      if (e.data.total_utilidad_otro_gasto > 0) {
        $('.progress_total_utilidad_otro_gasto').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_otro_gasto)}`);
        $('.leyenda_utilidad_otro_gasto').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_otro_gasto = "#008000";
      } else {
        $('.progress_total_utilidad_otro_gasto').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_otro_gasto)}`);
        $('.leyenda_utilidad_otro_gasto').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_otro_gasto = "#dc3545";
      }
      $('.progress_total_otro_gasto').html(`S/. ${formato_miles(e.data.total_monto_otro_gasto)}`);

      // transporte
      var  color_linea_utilidad_transporte = "";
      if (e.data.total_utilidad_transporte > 0) {
        $('.progress_total_utilidad_transporte').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_transporte)}`);
        $('.leyenda_utilidad_transporte').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_transporte = "#008000";
      } else {
        $('.progress_total_utilidad_transporte').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_transporte)}`);
        $('.leyenda_utilidad_transporte').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_transporte = "#dc3545";
      }
      $('.progress_total_transporte').html(`S/. ${formato_miles(e.data.total_monto_transporte)}`);

      // hospedaje
      var  color_linea_utilidad_hospedaje = "";
      if (e.data.total_utilidad_hospedaje > 0) {
        $('.progress_total_utilidad_hospedaje').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_hospedaje)}`);
        $('.leyenda_utilidad_hospedaje').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_hospedaje = "#008000";
      } else {
        $('.progress_total_utilidad_hospedaje').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_hospedaje)}`);
        $('.leyenda_utilidad_hospedaje').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_hospedaje = "#dc3545";
      }
      $('.progress_total_hospedaje').html(`S/. ${formato_miles(e.data.total_monto_hospedaje)}`);

      // pension
      var  color_linea_utilidad_pension = "";
      if (e.data.total_utilidad_pension > 0) {
        $('.progress_total_utilidad_pension').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_pension)}`);
        $('.leyenda_utilidad_pension').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_pension = "#008000";
      } else {
        $('.progress_total_utilidad_pension').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_pension)}`);
        $('.leyenda_utilidad_pension').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_pension = "#dc3545";
      }
      $('.progress_total_pension').html(`S/. ${formato_miles(e.data.total_monto_pension)}`);

      // breack
      var  color_linea_utilidad_breack = "";
      if (e.data.total_utilidad_breack > 0) {
        $('.progress_total_utilidad_breack').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_breack)}`);
        $('.leyenda_utilidad_breack').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_breack = "#008000";
      } else {
        $('.progress_total_utilidad_breack').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_breack)}`);
        $('.leyenda_utilidad_breack').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_breack = "#dc3545";
      }
      $('.progress_total_breack').html(`S/. ${formato_miles(e.data.total_monto_breack)}`);

      // comida_extra
      var  color_linea_utilidad_comida_extra = "";
      if (e.data.total_utilidad_comida_extra > 0) {
        $('.progress_total_utilidad_comida_extra').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_comida_extra)}`);
        $('.leyenda_utilidad_comida_extra').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_comida_extra = "#008000";
      } else {
        $('.progress_total_utilidad_comida_extra').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_comida_extra)}`);
        $('.leyenda_utilidad_comida_extra').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_comida_extra = "#dc3545";
      }
      $('.progress_total_comida_extra').html(`S/. ${formato_miles(e.data.total_monto_comida_extra)}`);

      // pago_administrador
      var  color_linea_utilidad_pago_administrador = "";
      if (e.data.total_utilidad_pago_administrador > 0) {
        $('.progress_total_utilidad_pago_administrador').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_pago_administrador)}`);
        $('.leyenda_utilidad_pago_administrador').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_pago_administrador = "#008000";
      } else {
        $('.progress_total_utilidad_pago_administrador').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_pago_administrador)}`);
        $('.leyenda_utilidad_pago_administrador').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_pago_administrador = "#dc3545";
      }
      $('.progress_total_pago_administrador').html(`S/. ${formato_miles(e.data.total_monto_pago_administrador)}`);

      // pago_obrero
      var  color_linea_utilidad_pago_obrero = "";
      if (e.data.total_utilidad_pago_obrero > 0) {
        $('.progress_total_utilidad_pago_obrero').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_pago_obrero)}`);
        $('.leyenda_utilidad_pago_obrero').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_pago_obrero = "#008000";
      } else {
        $('.progress_total_utilidad_pago_obrero').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad_pago_obrero)}`);
        $('.leyenda_utilidad_pago_obrero').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_pago_obrero = "#dc3545";
      }
      $('.progress_total_pago_obrero').html(`S/. ${formato_miles(e.data.total_monto_pago_obrero)}`);

      // resumen_modulos ----------------------------------------------
      var  color_linea_utilidad_resumen_modulos = "";
      if (e.data.total_utilidad > 0) {
        $('.progress_total_utilidad_resumen_modulos').removeClass('text-danger').addClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad)}`);
        $('.leyenda_utilidad_resumen_modulos').removeClass('text-danger').addClass('text-success');
        color_linea_utilidad_resumen_modulos = "#008000";
      } else {
        $('.progress_total_utilidad_resumen_modulos').addClass('text-danger').removeClass('text-success').html(`S/. ${formato_miles(e.data.total_utilidad)}`);
        $('.leyenda_utilidad_resumen_modulos').addClass('text-danger').removeClass('text-success');
        color_linea_utilidad_resumen_modulos = "#dc3545";
      }
      $('.progress_total_resumen_modulos').html(`S/. ${formato_miles(e.data.total_monto_gastado)}`);
      

      // :::::::::::::::::::::::::::::::::::::::::::: C H A R T   L I N E A S  -  C U R V A  S ::::::::::::::::::::::::::::::::::::
      
      var $chart_linea_curva_s = $('#chart-line-curva-s');
      if (chart_linea_curva_s) {  chart_linea_curva_s.destroy();  } 
      // eslint-disable-next-line no-unused-vars
      chart_linea_curva_s = new Chart($chart_linea_curva_s, {
        data: {
          labels: e.data.label_valorizacion_x,
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
          labels: e.data.label_valorizacion_x,
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
          labels: e.data.label_valorizacion_x,
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
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_compra_insumos, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_compra_insumos,
              pointBorderColor: color_linea_utilidad_compra_insumos, pointBackgroundColor: color_linea_utilidad_compra_insumos,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_compra_insumos,
              // pointHoverBorderColor    : color_linea_utilidad_compra_insumos
            },
            {              
              type: 'line', data: e.data.monto_compra_insumos, 
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
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_maquina_y_equipo, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_maquina_y_equipo,
              pointBorderColor: color_linea_utilidad_maquina_y_equipo, pointBackgroundColor: color_linea_utilidad_maquina_y_equipo,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_maquina_y_equipo,
              // pointHoverBorderColor    : color_linea_utilidad_maquina_y_equipo
            },
            {              
              type: 'line', data: e.data.monto_maquina_y_equipo, 
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
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_subcontrato, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_subcontrato,
              pointBorderColor: color_linea_utilidad_subcontrato, pointBackgroundColor: color_linea_utilidad_subcontrato,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_subcontrato,
              // pointHoverBorderColor    : color_linea_utilidad_subcontrato
            },
            {              
              type: 'line', data: e.data.monto_subcontrato, 
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

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  M A N O   D E   O B R A  ::::::::::::::::::::::::
      var $char_linea_mano_de_obra = $('#chart-line-mano_de_obra');
      if (char_linea_mano_de_obra) {  char_linea_mano_de_obra.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_mano_de_obra = new Chart($char_linea_mano_de_obra, {
        data: {
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_mano_de_obra, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_mano_de_obra,
              pointBorderColor: color_linea_utilidad_mano_de_obra, pointBackgroundColor: color_linea_utilidad_mano_de_obra,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_mano_de_obra,
              // pointHoverBorderColor    : color_linea_utilidad_mano_de_obra
            },
            {              
              type: 'line', data: e.data.monto_mano_de_obra, 
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
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_planilla_seguro, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_planilla_seguro,
              pointBorderColor: color_linea_utilidad_planilla_seguro, pointBackgroundColor: color_linea_utilidad_planilla_seguro,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_planilla_seguro,
              // pointHoverBorderColor    : color_linea_utilidad_planilla_seguro
            },
            {              
              type: 'line', data: e.data.monto_planilla_seguro, 
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
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_otro_gasto, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_otro_gasto,
              pointBorderColor: color_linea_utilidad_otro_gasto, pointBackgroundColor: color_linea_utilidad_otro_gasto,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_otro_gasto,
              // pointHoverBorderColor    : color_linea_utilidad_otro_gasto
            },
            {              
              type: 'line', data: e.data.monto_otro_gasto, 
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

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  T R A N S P O R T E  ::::::::::::::::::::::::
      var $char_linea_transporte = $('#chart-line-transporte');
      if (char_linea_transporte) {  char_linea_transporte.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_transporte = new Chart($char_linea_transporte, {
        data: {
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_transporte, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_transporte,
              pointBorderColor: color_linea_utilidad_transporte, pointBackgroundColor: color_linea_utilidad_transporte,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_transporte,
              // pointHoverBorderColor    : color_linea_utilidad_transporte
            },
            {              
              type: 'line', data: e.data.monto_transporte, 
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
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_hospedaje, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_hospedaje,
              pointBorderColor: color_linea_utilidad_hospedaje, pointBackgroundColor: color_linea_utilidad_hospedaje,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_hospedaje,
              // pointHoverBorderColor    : color_linea_utilidad_hospedaje
            },
            {              
              type: 'line', data: e.data.monto_hospedaje, 
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
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_pension, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_pension,
              pointBorderColor: color_linea_utilidad_pension, pointBackgroundColor: color_linea_utilidad_pension,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_pension,
              // pointHoverBorderColor    : color_linea_utilidad_pension
            },
            {              
              type: 'line', data: e.data.monto_pension, 
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
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_breack, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_breack,
              pointBorderColor: color_linea_utilidad_breack, pointBackgroundColor: color_linea_utilidad_breack,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_breack,
              // pointHoverBorderColor    : color_linea_utilidad_breack
            },
            {              
              type: 'line', data: e.data.monto_breack, 
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
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_comida_extra, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_comida_extra,
              pointBorderColor: color_linea_utilidad_comida_extra, pointBackgroundColor: color_linea_utilidad_comida_extra,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_comida_extra,
              // pointHoverBorderColor    : color_linea_utilidad_comida_extra
            },
            {              
              type: 'line', data: e.data.monto_comida_extra, 
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

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  P A G O   A D M I N I S T R A D O R  ::::::::::::::::::::::::
      var $char_linea_pago_administrador = $('#chart-line-pago-administrador');
      if (char_linea_pago_administrador) {  char_linea_pago_administrador.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_pago_administrador = new Chart($char_linea_pago_administrador, {
        data: {
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_pago_administrador, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_pago_administrador,
              pointBorderColor: color_linea_utilidad_pago_administrador, pointBackgroundColor: color_linea_utilidad_pago_administrador,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_pago_administrador,
              // pointHoverBorderColor    : color_linea_utilidad_pago_administrador
            },
            {              
              type: 'line', data: e.data.monto_pago_administrador, 
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

      // :::::::::::::::::::::::::::::::::::::  C H A R T   L I N E A  -  P A G O   O B R E R O  ::::::::::::::::::::::::
      var $char_linea_pago_obrero = $('#chart-line-pago-obrero');
      if (char_linea_pago_obrero) {  char_linea_pago_obrero.destroy();  }
      // eslint-disable-next-line no-unused-vars
      char_linea_pago_obrero = new Chart($char_linea_pago_obrero, {
        data: {
          labels: e.data.label_valorizacion_x,
          datasets: [
            {
              type: 'line', data: e.data.utilidad_pago_obrero, 
              backgroundColor: 'transparent', borderColor: color_linea_utilidad_pago_obrero,
              pointBorderColor: color_linea_utilidad_pago_obrero, pointBackgroundColor: color_linea_utilidad_pago_obrero,
              fill: false, label: 'Utilidad',  hidden: true,
              // pointHoverBackgroundColor: color_linea_utilidad_pago_obrero,
              // pointHoverBorderColor    : color_linea_utilidad_pago_obrero
            },
            {              
              type: 'line', data: e.data.monto_pago_obrero, 
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

      // :::::::::::::::::::::::::::::::::::::::::::: T A B L A   -  C O M P R A S   D E   I N S U M O S ::::::::::::::::::::::::::::::::::::
      var html_tabla_compra_insumos = "";
      var suma_total_gasto_compra_insumos = 0; var suma_total_utilidad_compra_insumos = 0; 
      e.data.tabla_compra_insumos.forEach((key, indice) => {
        var class_color_gci_po = key.gasto < 0 ? 'text-red' : '' ; var class_color_uci_po = key.utilidad < 0 ? 'text-red' : '' ; 
        var class_color_gcia_po = key.suma_total_gasto_pago_obrero < 0 ? 'text-red' : '' ; var class_color_ucia_po = key.suma_total_utilidad_pago_obrero < 0 ? 'text-red' : '' ;
        html_tabla_compra_insumos = html_tabla_compra_insumos.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right ${class_color_gci_po}" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right ${class_color_gcia_po}"><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_compra_insumos)} </div></td>
            <td class="py-1 text-right ${class_color_uci_po}" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right ${class_color_ucia_po}" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_compra_insumos)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_compra_insumos += key.gasto;
        suma_total_utilidad_compra_insumos += key.utilidad;
      });

      $('#body_modulo_compra_insumos').html(html_tabla_compra_insumos);
      $('.foot_total_gasto_compra_insumos').html(formato_miles(suma_total_gasto_compra_insumos));
      $('.foot_total_utilidad_compra_insumos').html(formato_miles(suma_total_utilidad_compra_insumos));

      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  M A Q U I N A S   Y   E Q U I P O S  ::::::::::::::::::::::::
      var html_tabla_maquina_y_equipo = "";
      var suma_total_gasto_maquina_y_equipo = 0; var suma_total_utilidad_maquina_y_equipo = 0; 
      e.data.tabla_maquina_y_equipo.forEach((key, indice) => {
        
        html_tabla_maquina_y_equipo = html_tabla_maquina_y_equipo.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_maquina_y_equipo)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_maquina_y_equipo)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_maquina_y_equipo += key.gasto;
        suma_total_utilidad_maquina_y_equipo += key.utilidad;
      });

      $('#body_modulo_maquina_y_equipo').html(html_tabla_maquina_y_equipo);
      $('.foot_total_gasto_maquina_y_equipo').html(formato_miles(suma_total_gasto_maquina_y_equipo));
      $('.foot_total_utilidad_maquina_y_equipo').html(formato_miles(suma_total_utilidad_maquina_y_equipo));

      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  S U B C O N T R A T O  ::::::::::::::::::::::::::::::::::::::
      var html_tabla_subcontrato = "";
      var suma_total_gasto_subcontrato = 0; var suma_total_utilidad_subcontrato = 0; 
      e.data.tabla_subcontrato.forEach((key, indice) => {
        html_tabla_subcontrato = html_tabla_subcontrato.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_subcontrato)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_subcontrato)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_subcontrato += key.gasto;
        suma_total_utilidad_subcontrato += key.utilidad;
      });

      $('#body_modulo_subcontrato').html(html_tabla_subcontrato);
      $('.foot_total_gasto_subcontrato').html(formato_miles(suma_total_gasto_subcontrato));
      $('.foot_total_utilidad_subcontrato').html(formato_miles(suma_total_utilidad_subcontrato));

      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  M A N O   D E   O B R A  ::::::::::::::::::::::::::::::::::::::
      var html_tabla_mano_de_obra = "";
      var suma_total_gasto_mano_de_obra = 0; var suma_total_utilidad_mano_de_obra = 0; 
      e.data.tabla_mano_de_obra.forEach((key, indice) => {
        html_tabla_mano_de_obra = html_tabla_mano_de_obra.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_mano_de_obra)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_mano_de_obra)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_mano_de_obra += key.gasto;
        suma_total_utilidad_mano_de_obra += key.utilidad;
      });

      $('#body_modulo_mano_de_obra').html(html_tabla_mano_de_obra);
      $('.foot_total_gasto_mano_de_obra').html(formato_miles(suma_total_gasto_mano_de_obra));
      $('.foot_total_utilidad_mano_de_obra').html(formato_miles(suma_total_utilidad_mano_de_obra));

      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  P L A N I L L A   S E G U R O  ::::::::::::::::::::::::::::::
      var html_tabla_planilla_seguro = "";
      var suma_total_gasto_planilla_seguro = 0; var suma_total_utilidad_planilla_seguro = 0; 
      e.data.tabla_planilla_seguro.forEach((key, indice) => {
        html_tabla_planilla_seguro = html_tabla_planilla_seguro.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_planilla_seguro)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_planilla_seguro)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_planilla_seguro += key.gasto;
        suma_total_utilidad_planilla_seguro += key.utilidad;
      });

      $('#body_modulo_planilla_seguro').html(html_tabla_planilla_seguro);
      $('.foot_total_gasto_planilla_seguro').html(formato_miles(suma_total_gasto_planilla_seguro));
      $('.foot_total_utilidad_planilla_seguro').html(formato_miles(suma_total_utilidad_planilla_seguro));

      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  O T R O   G A S T O  ::::::::::::::::::::::::::::::::::::::::
      var html_tabla_otro_gasto = "";
      var suma_total_gasto_otro_gasto = 0; var suma_total_utilidad_otro_gasto = 0; 
      e.data.tabla_otro_gasto.forEach((key, indice) => {
        html_tabla_otro_gasto = html_tabla_otro_gasto.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_otro_gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_otro_gasto)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_otro_gasto += key.gasto;
        suma_total_utilidad_otro_gasto += key.utilidad;
      });

      $('#body_modulo_otro_gasto').html(html_tabla_otro_gasto);
      $('.foot_total_gasto_otro_gasto').html(formato_miles(suma_total_gasto_otro_gasto));
      $('.foot_total_utilidad_otro_gasto').html(formato_miles(suma_total_utilidad_otro_gasto));

      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  T R A N S P O R T E  ::::::::::::::::::::::::::::::::::::::::
      var html_tabla_transporte = "";
      var suma_total_gasto_transporte = 0; var suma_total_utilidad_transporte = 0; 
      e.data.tabla_transporte.forEach((key, indice) => {
        html_tabla_transporte = html_tabla_transporte.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_transporte)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_transporte)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_transporte += key.gasto;
        suma_total_utilidad_transporte += key.utilidad;
      });

      $('#body_modulo_transporte').html(html_tabla_transporte);
      $('.foot_total_gasto_transporte').html(formato_miles(suma_total_gasto_transporte));
      $('.foot_total_utilidad_transporte').html(formato_miles(suma_total_utilidad_transporte));

      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  H O S P E D A J E  ::::::::::::::::::::::::::::::::::::::::::
      var html_tabla_hospedaje = "";
      var suma_total_gasto_hospedaje = 0; var suma_total_utilidad_hospedaje = 0; 
      e.data.tabla_hospedaje.forEach((key, indice) => {
        html_tabla_hospedaje = html_tabla_hospedaje.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_hospedaje)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_hospedaje)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_hospedaje += key.gasto;
        suma_total_utilidad_hospedaje += key.utilidad;
      });

      $('#body_modulo_hospedaje').html(html_tabla_hospedaje);
      $('.foot_total_gasto_hospedaje').html(formato_miles(suma_total_gasto_hospedaje));
      $('.foot_total_utilidad_hospedaje').html(formato_miles(suma_total_utilidad_hospedaje));

      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  P E N S I O N  ::::::::::::::::::::::::::::::::::::::::::::::
      var html_tabla_pension = "";
      var suma_total_gasto_pension = 0; var suma_total_utilidad_pension = 0; 
      e.data.tabla_pension.forEach((key, indice) => {
        html_tabla_pension = html_tabla_pension.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_pension)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_pension)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_pension += key.gasto;
        suma_total_utilidad_pension += key.utilidad;
      });

      $('#body_modulo_pension').html(html_tabla_pension);
      $('.foot_total_gasto_pension').html(formato_miles(suma_total_gasto_pension));
      $('.foot_total_utilidad_pension').html(formato_miles(suma_total_utilidad_pension));

      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  B R E A C K  ::::::::::::::::::::::::::::::::::::::::::::::::
      var html_tabla_breack = "";
      var suma_total_gasto_breack = 0; var suma_total_utilidad_breack = 0; 
      e.data.tabla_breack.forEach((key, indice) => {
        html_tabla_breack = html_tabla_breack.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_breack)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_breack)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_breack += key.gasto;
        suma_total_utilidad_breack += key.utilidad;
      });

      $('#body_modulo_breack').html(html_tabla_breack);
      $('.foot_total_gasto_breack').html(formato_miles(suma_total_gasto_breack));
      $('.foot_total_utilidad_breack').html(formato_miles(suma_total_utilidad_breack));

      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  C O M I D A   E X T R A  ::::::::::::::::::::::::::::::::::::
      var html_tabla_comida_extra = "";
      var suma_total_gasto_comida_extra = 0; var suma_total_utilidad_comida_extra = 0; 
      e.data.tabla_comida_extra.forEach((key, indice) => {
        html_tabla_comida_extra = html_tabla_comida_extra.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_comida_extra)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_comida_extra)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_comida_extra += key.gasto;
        suma_total_utilidad_comida_extra += key.utilidad;
      });

      $('#body_modulo_comida_extra').html(html_tabla_comida_extra);
      $('.foot_total_gasto_comida_extra').html(formato_miles(suma_total_gasto_comida_extra));
      $('.foot_total_utilidad_comida_extra').html(formato_miles(suma_total_utilidad_comida_extra));
      
      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  P A G O   A D M I N I S T R A D O R  ::::::::::::::::::::::::::::::::::::
      var html_tabla_pago_administrador = "";
      var suma_total_gasto_pago_administrador = 0; var suma_total_utilidad_pago_administrador = 0; 
      e.data.tabla_pago_administrador.forEach((key, indice) => {
        html_tabla_pago_administrador = html_tabla_pago_administrador.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_pago_administrador)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_pago_administrador)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_pago_administrador += key.gasto;
        suma_total_utilidad_pago_administrador += key.utilidad;
      });

      $('#body_modulo_pago_administrador').html(html_tabla_pago_administrador);
      $('.foot_total_gasto_pago_administrador').html(formato_miles(suma_total_gasto_pago_administrador));
      $('.foot_total_utilidad_pago_administrador').html(formato_miles(suma_total_utilidad_pago_administrador));

      // :::::::::::::::::::::::::::::::::::::  T A B L A  -  P A G O   O B R E R O  ::::::::::::::::::::::::::::::::::::
      var html_tabla_pago_obrero = "";
      var suma_total_gasto_pago_obrero = 0; var suma_total_utilidad_pago_obrero = 0; 
      e.data.tabla_pago_obrero.forEach((key, indice) => {
        var class_color_g_po = key.gasto < 0 ? 'text-red' : '' ; var class_color_u_po = key.utilidad < 0 ? 'text-red' : '' ; 
        var class_color_ga_po = key.suma_total_gasto_pago_obrero < 0 ? 'text-red' : '' ; var class_color_ua_po = key.suma_total_utilidad_pago_obrero < 0 ? 'text-red' : '' ;
        html_tabla_pago_obrero = html_tabla_pago_obrero.concat(`
          <tr>
            <td class="py-1 text-center " >${key.val}</td>
            <td class="py-1 text-right ${class_color_g_po}" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-1 text-right ${class_color_ga_po}" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_gasto_pago_obrero)} </div></td>
            <td class="py-1 text-right ${class_color_u_po}" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-1 text-right ${class_color_ua_po}" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(suma_total_utilidad_pago_obrero)} </div></td>
            <td class="py-1 text-center"> <a href="${key.ver_mas}" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_gasto_pago_obrero += key.gasto;
        suma_total_utilidad_pago_obrero += key.utilidad;
      });

      $('#body_modulo_pago_obrero').html(html_tabla_pago_obrero);
      $('.foot_total_gasto_pago_obrero').html(formato_miles(suma_total_gasto_pago_obrero));
      $('.foot_total_utilidad_pago_obrero').html(formato_miles(suma_total_utilidad_pago_obrero));

      // :::::::::::::::::::::::::::::::::::::::::::: T A B L A   -   R E S U M E N   D E   M O D U L O S ::::::::::::::::::::::::::::::::::::
      var html_tabla_modulos = "";
      var suma_total_modulo_gasto = 0; var suma_total_modulo_utilidad = 0; 
      e.data.tabla_resumen_modulos.forEach((key, indice) => {
        var class_color_g_rm = key.gasto < 0 ? 'text-red' : '' ; var class_color_u_rm = key.utilidad < 0 ? 'text-red' : '' ;
        html_tabla_modulos = html_tabla_modulos.concat(`
          <tr>
            <td class="py-2 text-center " >${indice+1}</td>
            <td class="py-2 text-left " >${key.modulo}</td>
            <td class="py-2 text-right ${class_color_g_rm}" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.gasto)} </div></td>
            <td class="py-2 text-right ${class_color_u_rm}" ><div class="formato-numero-conta"><span>S/</span> ${formato_miles(key.utilidad)} </div></td>
            <td class="py-2 text-center"> <a href="${key.ver_mas}" target="_blank" class="text-muted" data-toggle="tooltip" data-original-title="Ir a: ${key.modulo}"> <i class="fas fa-search"></i> </a> </td>
          </tr>
        `);
        suma_total_modulo_gasto += key.gasto;
        suma_total_modulo_utilidad += key.utilidad;
      });

      $('#body_resumen_modulos').html(html_tabla_modulos);
      $('.foot_total_gasto_resumen_modulos').html(formato_miles(suma_total_modulo_gasto));
      $('.foot_total_utilidad_resumen_modulos').html(formato_miles(suma_total_modulo_utilidad));
      $('[data-toggle="tooltip"]').tooltip();

      // ::::::::::::::::::::::::::::::::::::::::::::  C H A R T  B A R R A S  -  R E S U M E N   D E   M O D U L O S  ::::::::::::::::::::::::::::::::::::
      var $chart_barra_resumen_modulos = $('#chart-barra-resumen-modulos');
      if (chart_barra_resumen_modulos) {  chart_barra_resumen_modulos.destroy();  }
      // eslint-disable-next-line no-unused-vars
      chart_barra_resumen_modulos = new Chart($chart_barra_resumen_modulos, {
        type: 'bar',
        data: {
          labels: ['Compra de insumos','Maquinas y Equipos','Subcontrato', 'Mano de Obra', 'Planilla Seguro','Otro Gasto','Transporte','Hospedaje','Pensión','Breack','Comida Extra', 'Pago Adm.', 'Pago Obrero'],
          datasets: [
            { backgroundColor: '#008080', borderColor: '#008080', data: e.data.monto_resumen_modulos, label: 'Módulos', },
            { backgroundColor: color_linea_utilidad_resumen_modulos, borderColor: color_linea_utilidad_resumen_modulos, data: e.data.utilidad_resumen_modulos, label: 'Utilidad', }
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


function export_excel(name_tabla, nombre_excel_file = $('title').text(), nombre_excel_hoja = 'Detalle') {
   
  $tabla = document.querySelector(name_tabla);
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: nombre_excel_file, //Nombre del archivo de Excel
    sheetname: nombre_excel_hoja, //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);

  if (name_tabla == '#tabla_resumen_modulos') {    
    let preferenciasDocumento = datos.tabla_resumen_modulos.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  } else if (name_tabla == '#tabla_modulo_compra_insumos') {    
    let preferenciasDocumento = datos.tabla_modulo_compra_insumos.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  } else if (name_tabla == '#tabla_modulo_maquina_y_equipo') {    
    let preferenciasDocumento = datos.tabla_modulo_maquina_y_equipo.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  } else if (name_tabla == '#tabla_modulo_subcontrato') {    
    let preferenciasDocumento = datos.tabla_modulo_subcontrato.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  } else if (name_tabla == '#tabla_modulo_mano_de_obra') {    
    let preferenciasDocumento = datos.tabla_modulo_mano_de_obra.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  } else if (name_tabla == '#tabla_modulo_planilla_seguro') {    
    let preferenciasDocumento = datos.tabla_modulo_planilla_seguro.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  } else if (name_tabla == '#tabla_modulo_otro_gasto') {    
    let preferenciasDocumento = datos.tabla_modulo_otro_gasto.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  } else if (name_tabla == '#tabla_modulo_transporte') {    
    let preferenciasDocumento = datos.tabla_modulo_transporte.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  
  } else if (name_tabla == '#tabla_modulo_hospedaje') {    
    let preferenciasDocumento = datos.tabla_modulo_hospedaje.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  } else if (name_tabla == '#tabla_modulo_pension') {    
    let preferenciasDocumento = datos.tabla_modulo_pension.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  } else if (name_tabla == '#tabla_modulo_breack') {    
    let preferenciasDocumento = datos.tabla_modulo_breack.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  } else if (name_tabla == '#tabla_modulo_comida_extra') {    
    let preferenciasDocumento = datos.tabla_modulo_comida_extra.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  }
  
}