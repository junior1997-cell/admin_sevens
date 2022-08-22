var tabla_principal; var tabla_ingreso_pagos; var tabla_pagos_modal;

var id_trabajdor_x_proyecto_r = "", tipo_pago_r = "", nombre_trabajador_r = "", cuenta_bancaria_r = "";

var f1_load = "" , f2_load = "" , i_load = "" , cant_dias_asistencia_load = "";

//Función que se ejecuta al inicio
function init() {
  
  //Activamos el "aside"
  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#bloc_PagosTrabajador").addClass("menu-open bg-color-191f24");

  $("#mContableFinanciero").addClass("active");

  $("#mPagosTrabajador").addClass("active bg-primary");

  $("#lPagosObrero").addClass("active");

  listar_botones_q_s(localStorage.getItem('nube_idproyecto')) ; 
  sumas_totales_tabla_principal(localStorage.getItem('nube_idproyecto'));

  // efectuamos SUBMIT  registro de: PAGOS POR MES
  $("#guardar_registro_pagos_x_mes").on("click", function (e) { $("#submit-form-pagos-x-mes").submit(); });

  // efectuamos SUBMIT  registro de: RECIBOS POR HONORARIOS
  $("#guardar_registro_recibo_x_honorario").on("click", function (e) { $("#submit-form-recibo-x-honorario").submit(); });

  //Initialize Select2 unidad
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar una forma de pago", allowClear: true, });

  no_select_tomorrow('#fecha_pago');

  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e,$("#doc2").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

// Eliminamos el doc 2
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

function table_show_hide(flag) {
  if (flag == 1) {
    //location.reload();
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();
    $("#btn-agregar").hide(); 
    $("#btn-nombre-mes").hide();
    $("#div_btn_quincenas_semanas").show();

    $("#btn_q_s_actual button").removeClass('click-boton-success');
    $("#btn_quincenas_semanas button").removeClass('click-boton');
    $(".nombre-trabajador").html("Pagos de Obreros");

    $("#tbl-principal").show();
    $("#tbl-fechas").hide();
    $("#tbl-ingreso-pagos").hide();
    $("#tbl-pago-multiple_obrero").hide();    

    // detalle pago quincena semana
  } else if (flag == 2) {
    
    $("#btn-regresar").show();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();
    $("#btn-agregar").hide();
    $("#btn-nombre-mes").hide();
    $("#div_btn_quincenas_semanas").hide();

    $("#tbl-principal").hide();
    $("#tbl-fechas").show();
    $("#tbl-ingreso-pagos").hide();
    $("#tbl-pago-multiple_obrero").hide();

    // pago un solo obrero
  }else if (flag == 3) {
    
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").show();
    $("#btn-regresar-bloque").show();
    $("#btn-agregar").show();
    $("#btn-nombre-mes").show();
    $("#div_btn_quincenas_semanas").hide();

    $("#tbl-principal").hide();
    $("#tbl-fechas").hide();
    $("#tbl-ingreso-pagos").show();
    $("#tbl-pago-multiple_obrero").hide();
  
    // pago multiple obrero
  }else if (flag == 4) {
    
    $("#btn-regresar").show();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();
    $("#btn-agregar").hide();
    $("#btn-nombre-mes").hide();
    $("#div_btn_quincenas_semanas").show();

    $("#tbl-principal").hide();
    $("#tbl-fechas").hide();
    $("#tbl-ingreso-pagos").hide();
    $("#tbl-pago-multiple_obrero").show();
  }
}

function sumas_totales_tabla_principal(id_proyecto) {
  if (tabla_principal) {
    tabla_principal.destroy(); // Destruye las tablas de datos en el contexto actual.
    $('#tbody-tabla-principal').empty(); // Vacía en caso de que las columnas cambien
  }  

  // suma totales x proyecto
  $.post("../ajax/pago_obrero.php?op=mostrar_sumas_totales_tbla_principal", { 'id_proyecto': id_proyecto }, function (e, status) {

    e = JSON.parse(e); console.log(e); 
    if (e.status == true) {
      $('.total_tbla_principal_sabatical').html(`${e.data.total_sabatical}`);
      $(".total_tbla_principal_pago").html(`${formato_miles(e.data.total_pago_quincenal)}`);
      $(".total_tbla_principal_deposito").html(`${formato_miles(e.data.total_deposito)}`);
      $('.total_tbla_principal_saldo').html(`${formato_miles(e.data.total_pago_quincenal - e.data.total_deposito)}`);
      $('.total_tbla_principal_cant_s_q').html(`${e.data.total_envio_contador}`);

      listar_tbla_principal(id_proyecto);
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// LISTAR TABLA PRINCIPAL
function listar_tbla_principal(id_proyecto) {   
  
  tabla_principal=$('#tabla-principal').dataTable({
    // "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,14,15,16,17,18,2,3,19,20,5,6,7,21,9,10,11,12,13], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,14,15,16,17,18,2,3,19,20,5,6,7,21,9,10,11,12,13], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,14,15,16,17,18,2,3,19,20,5,6,7,21,9,10,11,12,13], } } ,      
    ],
    ajax:{
      url: `../ajax/pago_obrero.php?op=listar_tbla_principal&nube_idproyecto=${id_proyecto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {          
      // columna:# 0
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }  
      // columna: banco
      if (data[2] != '') { $("td", row).eq(2).addClass('text-center'); }         
      // columna: cuyenta bancaria  
      if (data[3] != '') { $("td", row).eq(3).addClass('text-center'); }
      // columna: total hn / total he
      if (data[4] != '') { $("td", row).eq(4).addClass('text-center');  }
      // columna: Psabatical
      if (data[5] != '') { $("td", row).eq(5).addClass('text-center'); }
      // columna: sueldo
      if (data[6] != '') { $("td", row).eq(6).addClass('text-right'); }
      // columna: pago a realizar
      if (data[7] != '') {
        var split = data[7].split(' '); //console.log(split);
        var quitar_format_mil = quitar_formato_miles( split[1]);
        if (parseFloat(quitar_format_mil) < 0) {
          $("td", row).eq(7).addClass('text-right bg-danger');
        }else{
          $("td", row).eq(7).addClass('text-right');
        }        
      }
      // columna: pago acumulado
      if (data[8] != '') { $("td", row).eq(8).addClass('text-center'); }  
      // columna: saldo
      if (data[9] != '') { $("td", row).eq(9).addClass('text-right'); }
      // columna: cantidad de pago al contador
      if (data[10] != '') { $("td", row).eq(10).addClass('text-center'); } 
      // columna: fecha inicio
      if (data[11] != '') { $("td", row).eq(11).addClass('text-center'); }
      // columna: fecha hoy
      if (data[12] != '') { $("td", row).eq(12).addClass('text-center'); }
      // columna: fecha fin
      if (data[13] != '') { $("td", row).eq(13).addClass('text-center'); }   
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [11,13], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [14,15,16,17,18,19,20,21], visible: false, searchable: false, },    
    ],
  }).DataTable(); 
  
}

// :::::::::::::::::::::::::: R E C I B O S   P O R   H O N O R A R I O ::::::::::::::::::::::::::::::::::::::::::::::
//Función limpiar
function limpiar_form_recibos_x_honorarios() {  
  // $("#form-recibos_x_honorarios").trigger("reset"); $  
  $('#idpagos_x_mes_administrador').val("");

  $('#idfechas_mes_pagos_administrador_rh').val("");
  $('#id_tabajador_x_proyecto_rh').val("");
  $('#fecha_inicial_rh').val("");
  $('#fecha_final_rh').val("");
  $('#mes_nombre_rh').val("");
  $('#dias_mes_rh').val("");
  $('#dias_regular_rh').val("");
  $('#sueldo_mensual_rh').val("");
  $('#monto_x_mes_rh').val("");
  $('#numero_comprobante_rh').val("");

  $('#descargar_rh').attr('href', ''); 
  $('#ver_completo').attr('href', '');
  $("#doc2_nombre").html("");

  $("#doc2").val("");
  $("#doc_old_2").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

// mostrar recibos por honorarios
function modal_recibos_x_honorarios(idresumen_q_s_asistencia, fecha_inicial, fecha_final, numero_q_s, numero_comprobante_rh, recibos_x_honorarios, tipo_pago, nombre_trabajador) {
  
  // borramos los campos cargados con anterioridad
  limpiar_form_recibos_x_honorarios();

  $('#modal-recibos-x-honorarios').modal('show');
  
  $("#doc2_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

  $('#idresumen_q_s_asistencia_rh').val(idresumen_q_s_asistencia);
  $('#numero_comprobante_rh').val(numero_comprobante_rh);
  $('.fecha_incial_modal').html(`<i class="fas fa-calendar-check text-gray-50"></i>&nbsp;&nbsp; ${format_d_m_a(fecha_inicial)}`);
  $('.fecha_final_modal').html(`<i class="fas fa-calendar-check text-gray-50"></i>&nbsp;&nbsp; ${format_d_m_a(fecha_final)}`);
  // &nbsp;&nbsp;<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp; <i class="fas fa-calendar-check text-gray-50"></i> ${format_d_m_a(fecha_final)}
  if (tipo_pago == 'quicenal') {
    $('.nombre_tipo_pago_modal').html(`N° de Quicena`);
    $('.numero_q_s_modal').html(`Quicena <b>${numero_q_s}</b>`);  
  } else {
    $('.nombre_tipo_pago_modal').html(`N° de Semana`);
    $('.numero_q_s_modal').html(`Semana <b>${numero_q_s}</b>`);  
  }   

  $('.titulo_modal_recibo_x_honorarios').html(`Recibo por Honorario: <b>${nombre_trabajador}</b>`);  

  if (recibos_x_honorarios == '' || recibos_x_honorarios == null || recibos_x_honorarios == 'null') {
    $('.descargar').hide();
    $('.ver_completo').hide();
    $("#doc2_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
    $("#doc2_nombre").html('');
    $('#doc_old_2').val("");
    $('#doc2').val("");
    
  } else {

    $('.descargar').show();
    $('.ver_completo').show();

    $('#descargar_rh').attr('href', `../dist/docs/pago_obrero/recibos_x_honorarios/${recibos_x_honorarios}`);
    if (tipo_pago == 'quicenal') {        
      $('#descargar_rh').attr('download', `Recibo-por-honorario - Quincena ${numero_q_s} - ${nombre_trabajador}`); 
    } else {        
      $('#descargar_rh').attr('download', `Recibo-por-honorario - Semana ${numero_q_s} - ${nombre_trabajador}`); 
    }    
    $('#ver_completo').attr('href', `../dist/docs/pago_obrero/recibos_x_honorarios/${recibos_x_honorarios}`);
    $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Recibo-por-honorario.${extrae_extencion(recibos_x_honorarios)}</i></div></div>`);

    $('#doc_old_2').val(recibos_x_honorarios);
    $('#doc2').val('');
    $("#doc2_ver").html(doc_view_extencion(recibos_x_honorarios, 'pago_obrero', 'recibos_x_honorarios', '100%', '310'));
    
  }
}

//Guardar o editar - R H
function guardar_y_editar_recibos_x_honorarios(e) {
  
  //e.preventDefault(); //No se activará la acción predeterminada del evento

  $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
  var formData = new FormData($("#form-recibos_x_honorarios")[0]);

  $.ajax({
    url: "../ajax/pago_obrero.php?op=guardar_y_editar_recibo_x_honorario",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {  
      try {
        e = JSON.parse(e);
        if (e.status == true) {

          detalle_q_s_trabajador(id_trabajdor_x_proyecto_r, tipo_pago_r, nombre_trabajador_r, cuenta_bancaria_r);
          trabajador_deuda_q_s(f1_load, f2_load, i_load, cant_dias_asistencia_load);
          sumas_totales_tabla_principal(localStorage.getItem('nube_idproyecto'));
          Swal.fire("Correcto!", "Recibo por honorario guardado correctamente", "success");         
          limpiar_form_recibos_x_honorarios();
          $("#modal-recibos-x-honorarios").modal("hide");        

        }else{
          ver_errores(e);			 
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); } 
      
      $("#guardar_registro_recibo_x_honorario").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_r_h").css({"width": percentComplete+'%'});
          $("#barra_progress_r_h").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_recibo_x_honorario").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_r_h").css({ width: "0%",  });
      $("#barra_progress_r_h").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_r_h").css({ width: "0%", });
      $("#barra_progress_r_h").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// :::::::::::::::::::::::::: P A G O S  U N   S O L O   O B R E R O S ::::::::::::::::::::::::::::::::::::::::::::::

//Función limpiar
function limpiar_pago_q_s() {

  $("#idpagos_q_s_obrero").val("");

  $("#monto").val("");
  $("#fecha_pago").val("");
  $("#forma_pago").val("").trigger("change"); 
  $("#descripcion").val(""); 
  $("#numero_comprobante").val(""); 

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

// Listar: QUINCENAS O SEMANAS
function detalle_q_s_trabajador(id_trabajdor_x_proyecto, tipo_pago, nombre_trabajador, cuenta_bancaria) {

  id_trabajdor_x_proyecto_r = id_trabajdor_x_proyecto; tipo_pago_r = tipo_pago; 
  nombre_trabajador_r =nombre_trabajador; cuenta_bancaria_r = cuenta_bancaria;
  
  $('.data-q-s').html(`<tr>
    <td colspan="13" >
      <div class="row">
        <div class="col-lg-12 text-center">
          <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
          <h4>Cargando...</h4>
        </div>
      </div>
    </td>                                   
  </tr>`);

  $(".nombre-trabajador").html(`Pagos - <b> ${nombre_trabajador} </b>`);

  var tipopagar = '';
  if (tipo_pago == "quincenal") {  tipopagar = 'Quincena'; } else if (tipo_pago == "semanal") { tipopagar = 'Semana'; } 
  $(".nombre-bloque-asistencia").html(`<b> ${tipopagar} </b>`);

  $.post("../ajax/pago_obrero.php?op=listar_tbla_q_s", { 'id_trabajdor_x_proyecto': id_trabajdor_x_proyecto }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {
      if (e.data.length === 0) {
        console.log('vacio');
      } else {
  
        var data_s_q = ""; var total_hn = 0, total_he = 0, total_monto_hn = 0, total_monto_he = 0, total_descuento = 0;
        var total_quincena = 0, total_saldo = 0, total_deposito = 0, rh_total = 0, total_sabatical = 0;
  
        e.data.forEach((key, indice) => {
  
          var saldo = 0; var btn_tipo = "", bg_saldo = "", btn_tipo_deposito = "";
  
          // Validamos el tipo de boton para los "recibos por honorarios"
          if (key.cant_rh == '' || key.cant_rh == null || key.cant_rh == 0) { btn_tipo = 'btn-outline-info'; } else { btn_tipo = 'btn-info'; rh_total += key.cant_rh; }
          
          saldo = parseFloat(key.pago_quincenal) - parseFloat(key.deposito);
  
          // background-color al saldo
          if (saldo < 0) { bg_saldo = 'bg-danger'; } else { bg_saldo = ''; }
  
          // background-color btn depositos
          if ( parseFloat(key.deposito) == 0 ) {
            btn_tipo_deposito = "btn-danger";
          } else {
            if ( parseFloat(key.deposito) > 0 &&  parseFloat(key.deposito) < parseFloat(key.pago_quincenal) ) {
              btn_tipo_deposito = "btn-warning";
            } else {
              if ( parseFloat(key.deposito) >= parseFloat(key.pago_quincenal) ) {
                btn_tipo_deposito = "btn-success";
              }
            }              
          }
  
          data_s_q = data_s_q.concat(`<tr>
            <td class="text-center py-1">${indice + 1}</td>
            <td class="text-center py-1"> ${key.numero_q_s}</td>
            <td class="text-center py-1">${format_d_m_a(key.fecha_q_s_inicio)}</td>
            <td class="text-center py-1">${format_d_m_a(key.fecha_q_s_fin)}</td>
            <td class="py-1"><div class="formato-numero-conta"><span>S/ </span>${key.sueldo_hora}</div></td>
            <td class="text-center py-1">${formato_miles(key.total_hn)}<b> / </b>${formato_miles(key.total_he)}</td>
            <td class="text-center py-1">${key.sabatical}</td>          
            <td class="text-center py-1"><div class="formato-numero-conta"> <span>S/ </span>${formato_miles(key.pago_parcial_hn)}<b> / </b><span>S/ </span>${formato_miles(key.pago_parcial_he)}</div></td>
            <td class="py-1"><div class="formato-numero-conta"><span>S/ </span>${formato_miles(key.adicional_descuento)}</div></td>
            <td class="py-1"><div class="formato-numero-conta"><span>S/ </span>${formato_miles(key.pago_quincenal)}</div></td>
            <td class="py-1">
              <div class="formato-numero-conta">
                <button class="btn ${btn_tipo_deposito} btn-sm mr-1" onclick="listar_tbla_pagos_x_q_s('${key.idresumen_q_s_asistencia}', '${format_d_m_a(key.fecha_q_s_inicio)}', '${format_d_m_a(key.fecha_q_s_fin)}', '${key.pago_quincenal}', '${key.numero_q_s}', '${tipo_pago}', '${nombre_trabajador}','${cuenta_bancaria}', '${saldo}' );"><i class="fas fa-dollar-sign"></i> Pagar</button>
                <button style="font-size: 14px;" class="btn ${btn_tipo_deposito} btn-sm">${formato_miles(key.deposito)}</button></div>
              </div>
            </td>
            <td class="py-1 ${bg_saldo}"><div class="formato-numero-conta"><span>S/ </span>${formato_miles(saldo)}</div></td>
            <td class="text-center py-1"> 
              <button class="btn ${btn_tipo} btn-sm"  onclick="tabla_recibos_por_honorarios('${key.idresumen_q_s_asistencia}', '${tipopagar} ${key.numero_q_s}');">
                <i class="fas fa-file-invoice fa-lg"></i>
              </button> 
            </td>
          </tr>`);
          
          total_hn += parseFloat(key.total_hn);
          total_he += parseFloat(key.total_he);
  
          total_sabatical += parseFloat(key.sabatical);
  
          total_monto_hn += parseFloat(key.pago_parcial_hn);
          total_monto_he += parseFloat(key.pago_parcial_he);
          total_descuento += parseFloat(key.adicional_descuento);
          total_quincena += parseFloat(key.pago_quincenal);
          total_deposito += parseFloat(key.deposito);
          total_saldo += parseFloat(saldo);
        });
  
        $('.data-q-s').html(data_s_q);
        $('.total_hn_he').html(`${formato_miles(total_hn)}<b> / </b>${formato_miles(total_he)}`);
        $('.total_sabatical').html(`${total_sabatical} `);
        $('.total_monto_hn_he').html(`<span>S/ </span>${formato_miles(total_monto_hn)} <b> / </b> <span>S/ </span>${formato_miles(total_monto_he)}`);
        $('.total_descuento').html(`${formato_miles(total_descuento)}`);
        $('.total_quincena').html(`${formato_miles(total_quincena)}`);
        $('.total_deposito').html(`${formato_miles(total_deposito)}`); 
        $('.total_saldo').html(`${formato_miles(total_saldo)}`); 
        $('.rh_total').html(`${rh_total} <small class="text-gray">(docs.)</small>`);
      }
    } else {
      ver_errores(e);
    }
         
  }).fail( function(e) { ver_errores(e); } ); 
}

// Listar: los PAGOS de un QUINCENA O SEMANA
function listar_tbla_pagos_x_q_s(idresumen_q_s_asistencia, fecha_inicio, fecha_final, pago_q_s, numero_q_s, tipo_pago, nombre_trabajador, cuenta_bancaria, saldo_q_s ) {

  table_show_hide(3);

  $('#btn-nombre-mes').html(`&nbsp; &nbsp; <i class="fas fa-calendar-check text-gray-50"></i> <b>${fecha_inicio}  <i class="fas fa-arrow-right"></i>  ${fecha_final}</b> - <sup>S/</sup><b>${formato_miles(pago_q_s)}</b>`);
  
  if ( parseFloat(saldo_q_s) < 0) {
    $('.faltante_mes_modal').css({'background-color' : 'red', 'color':'white'});
    $('.faltante_mes_modal').html(`<sup>S/ </sup>${formato_miles(saldo_q_s)}`);
  } else {
    if (parseFloat(saldo_q_s) == 0) {
      $('.faltante_mes_modal').css({'background-color' : 'green', 'color':'white'});
      $('.faltante_mes_modal').html(`<sup>S/ </sup><b>${formato_miles(saldo_q_s)}</b>`);  
    } else {
      $('.faltante_mes_modal').css({'background-color' : '#ffc107', 'color':'black'});
      $('.faltante_mes_modal').html(`<sup>S/ </sup><b>${formato_miles(saldo_q_s)}</b>`);
    }    
  }  

  $('.nombre_de_trabajador_modal').html(`${nombre_trabajador}` );

  $('#cuenta_deposito').val(cuenta_bancaria);
  
  if (tipo_pago == 'quincenal') {
    $('.nombre_q_s').html(`<b>Quincena</b>`);
    $('.numero_q_s').html(`<b>${numero_q_s}</b>`);
  } else {
    $('.nombre_q_s').html(`<b>Semana</b>`);
    $('.numero_q_s').html(`<b>${numero_q_s}</b>`);
  }  

  $('#idresumen_q_s_asistencia').val(idresumen_q_s_asistencia);

  tabla_ingreso_pagos=$('#tabla-ingreso-pagos').dataTable({
    responsive: true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'pdf', "colvis"],
    ajax:{
      url: '../ajax/pago_obrero.php?op=listar_tbla_pagos_x_q_s&idresumen_q_s_asistencia='+idresumen_q_s_asistencia,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: opciones
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
      // columna: opciones
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
      // columna: fecha
      if (data[2] != '') {$("td", row).eq(2).addClass('text-nowrap');}
      // columna: deposito
      if (data[4] != '') { $("td", row).eq(4).addClass('text-right text-nowrap'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      //{ targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();
}

//Guardar o editar - PAGOS Q S
function guardar_y_editar_pagos_x_q_s(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-pagos-x-q-s")[0]);

  $.ajax({
    url: "../ajax/pago_obrero.php?op=guardar_y_editar_pagos_x_q_s",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {       
      try {
        e = JSON.parse(e);
        if (e.status == true ) {
          if (tabla_ingreso_pagos) {  tabla_ingreso_pagos.ajax.reload(null, false);  }
          if (tabla_pagos_modal) {  tabla_pagos_modal.ajax.reload(null, false); trabajador_deuda_q_s(f1_load, f2_load, i_load, cant_dias_asistencia_load);  }

          sumas_totales_tabla_principal(localStorage.getItem('nube_idproyecto'));
          Swal.fire("Correcto!", "Pago guardado correctamente", "success");         
          limpiar_pago_q_s();
          $("#modal-agregar-pago-trabajdor").modal("hide");        
  
        }else{
          ver_errores(e);			 
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      
      $("#guardar_registro_pagos_x_mes").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_pagos_x_mes").css({"width": percentComplete+'%'});
          $("#barra_progress_pagos_x_mes").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_pagos_x_mes").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_pagos_x_mes").css({ width: "0%",  });
      $("#barra_progress_pagos_x_mes").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_pagos_x_mes").css({ width: "0%", });
      $("#barra_progress_pagos_x_mes").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// Mostramos "PAGOS Q S" para editar
function mostrar_pagos_x_q_s(id) {

  limpiar_pago_q_s();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  $("#modal-agregar-pago-trabajdor").modal('show');

  $.post("../ajax/pago_obrero.php?op=mostrar_pagos_x_q_s", { 'idpagos_q_s_obrero': id }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    if (e.status == true) {
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $('#idpagos_q_s_obrero').val(e.data.idpagos_q_s_obrero);
      $("#monto").val(e.data.monto_deposito);
      $("#fecha_pago").val(e.data.fecha_pago);
      $("#cuenta_deposito").val(e.data.cuenta_deposito);
      $("#forma_pago").val(e.data.forma_de_pago).trigger("change"); 
      $("#descripcion").val(e.data.descripcion); 

      //validamoos BAUCHER - DOC 1
      if (e.data.baucher == "" || e.data.baucher == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#doc1_nombre").html('');

        $("#doc_old_1").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_1").val(e.data.baucher); 

        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.baucher)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.baucher, 'pago_obrero', 'baucher_deposito', '100%', '210'));        
              
      }     
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } ); 
}

function desactivar_pago_x_q_s(id) {  

  Swal.fire({
    title: "¿Está Seguro de ANULAR el pago?",
    text: "Al anularlo este pago, el monto NO se contara como un deposito realizado.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/pago_obrero.php?op=desactivar_pago_x_q_s", { 'idpagos_q_s_obrero': id }, function (e) {
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {
          tabla_ingreso_pagos.ajax.reload(null, false); 
          sumas_totales_tabla_principal(localStorage.getItem('nube_idproyecto'));
          Swal.fire("Anulado!", "Tu registro ha sido Anulado.", "success");
        } else {
          ver_errores(e);
        }        
      }).fail( function(e) { ver_errores(e); } );      
    }
  });  
}

function activar_pago_x_q_s(id) {

  Swal.fire({
    title: "¿Está Seguro de ReActivar el pago?",
    text: "Al ReActivarlo este pago, el monto contara como un deposito realizado.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/pago_obrero.php?op=activar_pago_x_q_s", { 'idpagos_q_s_obrero': id }, function (e) {
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {
          tabla_ingreso_pagos.ajax.reload(null, false); 
          sumas_totales_tabla_principal(localStorage.getItem('nube_idproyecto'));
          Swal.fire("ReActivado!", "Tu registro ha sido ReActivado.", "success");
        } else {
          ver_errores(e);
        }        
      }).fail( function(e) { ver_errores(e); } );         
    }
  });
}

function reload_table_detalle_x_q_s() {
  detalle_q_s_trabajador(id_trabajdor_x_proyecto_r, tipo_pago_r, nombre_trabajador_r, cuenta_bancaria_r);
}

function tabla_recibos_por_honorarios(id_q_s, modal_title) {
  $('.titulo-tabla-rh').html(`Lista de RH - <b>${modal_title}</b>` );
  $('#modal-tabla-recibo-por-honorario').modal('show');

  tabla_recibo_por_honorario=$('#tabla-recibo-por-honorario').dataTable({
    //"responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [ ],
    ajax:{
      url: '../ajax/pago_obrero.php?op=listar_tbla_recibo_por_honorario&id_q_s='+id_q_s,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') {  $("td", row).eq(0).css('text-center'); }  
      // columna: monto
      if (data[3] != '') { $("td", row).eq(3).addClass('text-right');  } 
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      //{ targets: [13,14,15,16,17,18], visible: false, searchable: false, },    
    ],
  }).DataTable();
}

// :::::::::::::::::::::::::: P A G O S  M U L T P L E S   O B R E R O S ::::::::::::::::::::::::::::::::::::::::::::::

function listar_botones_q_s(nube_idproyecto) {

  $('#div_btn_quincenas_semanas').hide();
  $('.btn_cargando_s_q').show();

  //Listar quincenas(botones)
  $.post("../ajax/pago_obrero.php?op=listarquincenas_botones", { nube_idproyecto: nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); //console.log(e);

    if (e.status == true) {
      // validamos la existencia de DATOS
      if ( e.data.length === 0 ) {
        $('#btn_quincenas_semanas').html(`<div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
          <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
          No has definido las de fechas del proyecto. <br>Clic en el <span class="bg-green p-1 rounded-lg"> <i class="far fa-calendar-alt"></i> boton verde</span> para actualizar las fechas de actividades.
        </div>`);        
      } else {
        if (e.data.fecha_inicio == '0000-00-00' || e.data.fecha_inicio == null || e.data.fecha_fin == '0000-00-00' || e.data.fecha_fin == null) {
          $('#btn_quincenas_semanas').html(`<div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
            <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
            No has definido las de fechas del proyecto. <br>Clic en el <span class="bg-green p-1 rounded-lg"> <i class="far fa-calendar-alt"></i> boton verde</span> para actualizar las fechas de actividades.
          </div>`);
        } else {        
        
          var dia_regular = 0; var weekday_regular = extraer_dia_semana(e.data.fecha_inicio); var estado_regular = false;

          if (weekday_regular == "do") { dia_regular = -1; } else { if (weekday_regular == "lu") { dia_regular = -2; } else { if (weekday_regular == "ma") { dia_regular = -3; } else { if (weekday_regular == "mi") { dia_regular = -4; } else { if (weekday_regular == "ju") { dia_regular = -5; } else { if (weekday_regular == "vi") { dia_regular = -6; } else { if (weekday_regular == "sa") { dia_regular = -7; } } } } } } }
          // console.log(e.data.fecha_inicio, dia_regular, weekday_regular);
          if (e.data.fecha_pago_obrero == "quincenal") {

            $('#btn_quincenas_semanas').html('');

            var fecha = format_d_m_a(e.data.fecha_inicio); //console.log(fecha);

            var fecha_i = sumaFecha(0,fecha);   var cal_quincena  = e.data.plazo/14;

            var i=0; var cont=0; 

            while (i <= cal_quincena) {

              cont=cont+1; var fecha_inicio = fecha_i;

              if (estado_regular) {
                fecha=sumaFecha(13,fecha_inicio);     //console.log(fecha_inicio+'-'+fecha);
              } else {
                fecha=sumaFecha(14+dia_regular,fecha_inicio); estado_regular = true;     //console.log(fecha_inicio+'-'+fecha);
              }
               
              if (validarFechaEnRango(format_a_m_d( fecha_inicio), format_a_m_d(fecha), moment().format('YYYY-MM-DD')) == true) {
                $('#btn_q_s_actual').html(` <button type="button" id="boton-${i}" class="mb-2 btn bg-gradient-success btn-sm text-center " onclick="trabajador_deuda_q_s('${fecha_inicio}', '${fecha}', ${i}, 14); table_show_hide(4); pintar_boton_selecionado_succes(${i});"><i class="far fa-calendar-alt"></i> Quincena ${cont}<br>${fecha_inicio} // ${fecha}</button>`);                
              } else {
                $('#btn_quincenas_semanas').append(` <button type="button" id="boton-${i}" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="trabajador_deuda_q_s('${fecha_inicio}', '${fecha}', ${i}, 14); table_show_hide(4); pintar_boton_selecionado(${i});"><i class="far fa-calendar-alt"></i> Quincena ${cont}<br>${fecha_inicio} // ${fecha}</button>`);
              }

              fecha_i =sumaFecha(1,fecha);
              i++;
            }
          } else {
            if (e.data.fecha_pago_obrero == "semanal") {

              $('#btn_quincenas_semanas').html('');

              var fecha = format_d_m_a(e.data.fecha_inicio);  var fecha_f = ""; var fecha_i = ""; //e.data.fecha_inicio

              var cal_mes  = false; var i=0;  var cont=0;

              while (cal_mes == false) {

                cont = cont+1; fecha_i = fecha;

                if (estado_regular) {
                  fecha_f = sumaFecha(6, fecha_i);
                } else {
                  fecha_f = sumaFecha(7+dia_regular, fecha_i); estado_regular = true;
                }            

                let val_fecha_f = new Date( format_a_m_d(fecha_f) ); let val_fecha_proyecto = new Date(e.data.fecha_fin);
                
                // console.log(fecha_f + ' - '+e.data.fecha_fin);

                
                if (validarFechaEnRango(format_a_m_d( fecha_i), format_a_m_d(fecha_f), moment().format('YYYY-MM-DD')) == true) {
                  $('#btn_q_s_actual').html(` <button type="button" id="boton-${i}" class="mb-2 btn bg-gradient-success btn-sm text-center " onclick="trabajador_deuda_q_s('${fecha_i}', '${fecha_f}', ${i}, 14); table_show_hide(4); pintar_boton_selecionado_succes(${i});"><i class="far fa-calendar-alt"></i> Quincena ${cont}<br>${fecha_i} // ${fecha_f}</button>`);                
                } else{
                  $('#btn_quincenas_semanas').append(` <button id="boton-${i}" type="button" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="trabajador_deuda_q_s('${fecha_i}', '${fecha_f}', ${i}, 7); table_show_hide(4); pintar_boton_selecionado(${i});"><i class="far fa-calendar-alt"></i> Semana ${cont}<br>${fecha_i} // ${fecha_f}</button>`);
                }

                if (val_fecha_f.getTime() >= val_fecha_proyecto.getTime()) { cal_mes = true; }else{ cal_mes = false;}
                fecha = sumaFecha(1,fecha_f);
                i++;
              } 
            } else { 
              $('#btn_quincenas_semanas').html(`<div class="info-box shadow-lg w-600px"> 
                <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span> 
                <div class="info-box-content"> 
                  <span class="info-box-text">Alerta</span> 
                  <span class="info-box-number">No has definido los bloques de fechas del proyecto. <br>Ingresa al ESCRITORIO y EDITA tu proyecto selecionado.</span> 
                </div> 
              </div>`);
            }
          }
        }
      }
    } else {
      ver_errores(e);
    }

    $('#div_btn_quincenas_semanas').show();
    $('.btn_cargando_s_q').hide();
    //console.log(fecha);
  }).fail( function(e) { ver_errores(e); } );
}

function trabajador_deuda_q_s(f1, f2, i, cant_dias_asistencia) {
  f1_load = f1; f2_load = f2; i_load = i; cant_dias_asistencia_load = cant_dias_asistencia;
  
  var s_q_pago = (cant_dias_asistencia==14 ? 'quicenal':'semanal');
  $('.nombre_q_s_obrero').html( capitalizeWords(s_q_pago.slice(0, -1)) );

  $('.data-trabajadores-q-s').html(`<tr>  
    <td colspan="10" >
      <div class="row">
        <div class="col-lg-12 text-center"> <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br><h4>Cargando...</h4> </div>
      </div>
    </td>                                   
  </tr>`);

  $.post("../ajax/pago_obrero.php?op=tabla_obreros_pago", { 'id_proyecto': localStorage.getItem('nube_idproyecto'), 'num_quincena': i+1 }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {
      if (e.data.length === 0) {
        $('.data-trabajadores-q-s').html(`<td></td> <td></td> <td colspan="6" class="text-center" ><div class="callout callout-danger text-left mb-0"> <h5 ><b>Vacío!</b></h5><p>Asigne pagos dando click en enviar pagos al contador, en el módulo de <a href="asistencia_obrero.php" class="text-primary" target="_blank" rel="noopener noreferrer"><b>asistencia de obreros</b></a>.</p> </div></td> <td></td> <td></td>`);
        $('.multiple_total_hn_he').html(`0.00 / 0.00`);
        $('.multiple_total_deuda').html(`0.00`);
        $('.multiple_total_deposito').html(`0.00`); 
        $('.multiple_total_saldo').html(`0.00`); 
        $('.multiple_rh_total').html(`0 <small class="text-gray">(docs.)</small>`);
        console.log('vacio');
      } else {
  
        var data_trabajadores_q_s = ""; var total_hn = 0, total_he = 0, total_monto_hn = 0, total_monto_he = 0, total_descuento = 0;
        var total_quincena = 0, total_saldo = 0, total_deposito = 0, rh_total = 0, total_sabatical = 0;
  
        e.data.forEach((key, indice) => {
  
          var saldo = 0; var btn_tipo = "", bg_saldo = "", btn_tipo_deposito = "";
  
          // Validamos el tipo de boton para los "recibos por honorarios"
          if (key.cant_rh == '' || key.cant_rh == null || key.cant_rh == 0) { btn_tipo = 'btn-outline-info'; } else { btn_tipo = 'btn-info'; rh_total += key.cant_rh; }
          saldo = parseFloat(key.pago_quincenal) - parseFloat(key.deposito);
          // background-color al saldo
          if (saldo < 0) { bg_saldo = 'bg-danger'; }
          // background-color btn depositos
          if ( parseFloat(key.deposito) == 0 ) {
            btn_tipo_deposito = "btn-danger";
          } else {
            if ( parseFloat(key.deposito) > 0 &&  parseFloat(key.deposito) < parseFloat(key.pago_quincenal) ) {
              btn_tipo_deposito = "btn-warning";
            } else {
              if ( parseFloat(key.deposito) >= parseFloat(key.pago_quincenal) ) {
                btn_tipo_deposito = "btn-success";
              }
            }              
          }

          data_trabajadores_q_s = data_trabajadores_q_s.concat(`<tr>
            <td class="pt-1 pb-1 text-center" >${indice + 1}</td>
            <td class="pt-1 pb-1 text-center" > ${key.numero_q_s}</td>
            <td class="pt-1 pb-1"> 
              <div class="user-block">
                <img class="img-circle" src="../dist/docs/all_trabajador/perfil/${key.imagen_perfil}" alt="User Image" onerror="this.src='../dist/svg/user_default.svg'">
                <span class="username"><p class="text-primary m-b-02rem" >${key.trabajador}</p></span>
                <span class="description text-left" >${key.tipo_trabajador} / ${key.cargo_trabajador} ─ ${key.tipo_documento}: ${key.numero_documento} </span>                  
              </div>
            </td>
            <td class="pt-1 pb-1">${key.banco}</td>
            <td class="pt-1 pb-1">${key.cuenta_bancaria}</td>
            <td class="pt-1 pb-1 text-center">${formato_miles(key.total_hn)}<b> / </b>${formato_miles(key.total_he)}</td>
            <td class="pt-1 pb-1"><div class="formato-numero-conta"><span>S/</span>${key.pago_quincenal}</div></td>
            <td class="pt-1 pb-1">
              <div class="formato-numero-conta">
                <button class="btn ${btn_tipo_deposito} btn-sm mr-1" onclick="modal_pago_obrero('${key.idresumen_q_s_asistencia}', '${format_d_m_a(key.fecha_q_s_inicio)}', '${format_d_m_a(key.fecha_q_s_fin)}', '${key.pago_quincenal}', '${key.numero_q_s}', '${s_q_pago}', '${key.trabajador}','${key.cuenta_bancaria}',${saldo} );"><i class="fas fa-dollar-sign"></i> Pagar</button>
                <button style="font-size: 14px;" class="btn ${btn_tipo_deposito} btn-sm">${formato_miles(key.deposito)}</button></div>
              </div>
            </td>
            <td class="pt-1 pb-1"><div class="formato-numero-conta"><span>S/</span>${formato_miles(saldo)}</div></td>
            <td class="pt-1 pb-1 text-center"> 
              <button class="btn ${btn_tipo} btn-sm"  onclick="tabla_recibos_por_honorarios('${key.idresumen_q_s_asistencia}', '${capitalizeWords(s_q_pago.slice(0, -1))} ${key.numero_q_s}');">
                <i class="fas fa-file-invoice fa-lg"></i>
              </button> 
            </td>
          </tr>`);
          
          total_hn += parseFloat(key.total_hn);
          total_he += parseFloat(key.total_he);
  
          total_sabatical += parseFloat(key.sabatical);
  
          total_monto_hn += parseFloat(key.pago_parcial_hn);
          total_quincena += parseFloat(key.pago_quincenal);
          total_deposito += parseFloat(key.deposito);
          total_saldo += parseFloat(saldo);
        });
  
        $('.data-trabajadores-q-s').html(data_trabajadores_q_s);
        $('.multiple_total_hn_he').html(`${formato_miles(total_hn)} / ${formato_miles(total_he)}`);
        $('.multiple_total_deuda').html(`${formato_miles(total_quincena)}`);
        $('.multiple_total_deposito').html(`${formato_miles(total_deposito)}`); 
        $('.multiple_total_saldo').html(`${formato_miles(total_saldo)}`); 
        $('.multiple_rh_total').html(`${rh_total} <small class="text-gray">(docs.)</small>`);
      }
    } else {
      ver_errores(e);
    }
         
  }).fail( function(e) { ver_errores(e); } ); 
}

function modal_pago_obrero(idresumen_q_s_asistencia, fecha_i, fecha_f, pago_quincenal, numero_q_s, s_q_pago, nombre_trabajador, cuenta_bancaria, saldo_q_s) {
  
  idresumen_q_s_asistencia_r = idresumen_q_s_asistencia, fecha_i_r = fecha_i, fecha_f_r = fecha_f, pago_quincenal_r = pago_quincenal, 
  numero_q_s_r = numero_q_s, s_q_pago_r = s_q_pago, nombre_trabajador_r = nombre_trabajador, cuenta_bancaria_r = cuenta_bancaria, saldo_q_s_r = saldo_q_s;
  $('#modal-tabla-pagos').modal('show');
  $('#idresumen_q_s_asistencia').val(idresumen_q_s_asistencia);
  $('.nombre_de_trabajador_modal').html(`${nombre_trabajador}` );
  $('#cuenta_deposito').val(cuenta_bancaria);

  if (s_q_pago == 'quincenal') {
    $('.nombre_q_s').html(`<b>Quincena</b>`);
    $('.numero_q_s').html(`<b>${numero_q_s}</b>`);
  } else {
    $('.nombre_q_s').html(`<b>Semana</b>`);
    $('.numero_q_s').html(`<b>${numero_q_s}</b>`);
  }

  if ( parseFloat(saldo_q_s) < 0) {
    $('.faltante_mes_modal').css({'background-color' : 'red', 'color':'white'});
    $('.faltante_mes_modal').html(`<sup>S/ </sup>${formato_miles(saldo_q_s)}`);
  } else {
    if (parseFloat(saldo_q_s) == 0) {
      $('.faltante_mes_modal').css({'background-color' : 'green', 'color':'white'});
      $('.faltante_mes_modal').html(`<sup>S/ </sup><b>${formato_miles(saldo_q_s)}</b>`);  
    } else {
      $('.faltante_mes_modal').css({'background-color' : '#ffc107', 'color':'black'});
      $('.faltante_mes_modal').html(`<sup>S/ </sup><b>${formato_miles(saldo_q_s)}</b>`);
    }    
  } 

  tabla_pagos_modal=$('#tabla-ingreso-pagos-modal').dataTable({
    responsive: true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'pdf'],
    ajax:{
      url: '../ajax/pago_obrero.php?op=tbla_pagos_por_obrero&idresumen_q_s_asistencia='+idresumen_q_s_asistencia,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: opciones
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
      // columna: opciones
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
      // columna: cuenta deposito
      if (data[2] != '') {$("td", row).eq(2).addClass('text-nowrap');}
      // columna: deposito
      if (data[4] != '') { $("td", row).eq(4).addClass('text-right text-nowrap'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      //{ targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();
}

init();

$(function () {

  $('#forma_pago').on('change', function() { $(this).trigger('blur'); });

  $("#form-pagos-x-q-s").validate({
    rules: {
      forma_pago: { required: true},
      monto:      {required: true, minlength: 1 },
      fecha_pago: {required: true, },
      numero_comprobante: { minlength: 3, maxlength:45 },
      descripcion:{ minlength: 4 },
    },
    messages: {
      forma_pago: { required: "Campo requerido." },
      monto:      { required: "Campo requerido.",   minlength: "MINIMO 1 dígito.", },
      fecha_pago: { required: "Campo requerido.", },
      numero_comprobante: { minlength: "MINIMO 3 dígito.", maxlength: "MINIMO 45 dígito.", },
      descripcion:{ minlength: "MINIMO 4 caracteres.", },
    },
    
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");             
    },

    submitHandler: function (e) { 
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_y_editar_pagos_x_q_s(e); 
    },
  });

  $("#form-recibos_x_honorarios").validate({
    rules: {
      
    },
    messages: {
      
    },
    
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");             
    },

    submitHandler: function (e) { 
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_y_editar_recibos_x_honorarios(e);
    },
  });

  $('#forma_pago').rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..
function pintar_boton_selecionado(i) {
  localStorage.setItem('i', i); //enviamos el ID-BOTON al localStorage
  // validamos el id para pintar el boton
  
  if (localStorage.getItem('boton_id')) {

    let id = localStorage.getItem('boton_id'); //console.log('id-nube-boton '+id); 
    
    $("#boton-" + id).removeClass('click-boton');
    $("#boton-" + id).removeClass('click-boton-success');

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  } else {

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  }
}

function pintar_boton_selecionado_succes(i) {
  localStorage.setItem('i', i); //enviamos el ID-BOTON al localStorage
  
  // validamos el id para pintar el boton
  if (localStorage.getItem('boton_id')) {

    let id = localStorage.getItem('boton_id'); //console.log('id-nube-boton '+id); 
    
    $("#boton-" + id).removeClass('click-boton-success');
    $("#boton-" + id).removeClass('click-boton');

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton-success');
  } else {

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton-success');
  }
}