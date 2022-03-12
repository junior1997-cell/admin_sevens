var tabla_principal; var tabla_ingreso_pagos;

var id_trabajdor_x_proyecto_r = "", tipo_pago_r = "", nombre_trabajador_r = "", cuenta_bancaria_r = "";
          
//Función que se ejecuta al inicio
function init() {
  
  //Activamos el "aside"
  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#bloc_PagosTrabajador").addClass("menu-open");

  $("#mContableFinanciero").addClass("active");

  $("#mPagosTrabajador").addClass("active bg-primary");

  $("#lPagosObrero").addClass("active");

  listar_tbla_principal(localStorage.getItem('nube_idproyecto'));  

  // efectuamos SUBMIT  registro de: PAGOS POR MES
  $("#guardar_registro").on("click", function (e) { $("#submit-form-pagos-x-mes").submit(); });

  // efectuamos SUBMIT  registro de: RECIBOS POR HONORARIOS
  $("#guardar_registro_2").on("click", function (e) { $("#submit-form-recibo-x-honorario").submit(); });
  $("#form-recibos_x_honorarios").on("submit", function (e) { guardar_y_editar_recibos_x_honorarios(e); });  

  //Initialize Select2 unidad
  $("#forma_pago").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar una forma de pago",
    allowClear: true,
  });

  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addDocs(e,$("#doc1").attr("id")) });

$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addDocs(e,$("#doc2").attr("id")) });

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

//Función limpiar
function limpiar_pago_q_s() {

  $("#idpagos_q_s_obrero").val("");

  $("#monto").val("");
  $("#forma_pago").val("").trigger("change"); 
  $("#descripcion").val(""); 

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

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

  $('#descargar_rh').attr('href', ''); 
  $('#ver_completo').attr('href', '');
  $("#doc2_nombre").html("");

  $("#doc2").val("");
  $("#doc_old_2").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

function table_show_hide(flag) {
  if (flag == 1) {
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();
    $("#btn-agregar").hide(); 
    $("#btn-nombre-mes").hide();

    $(".nombre-trabajador").html("Pagos de Obreros");

    $("#tbl-principal").show();
    $("#tbl-fechas").hide();
    $("#tbl-ingreso-pagos").hide();
  } else {
    if (flag == 2) {
      $("#btn-regresar").show();
      $("#btn-regresar-todo").hide();
      $("#btn-regresar-bloque").hide();
      $("#btn-agregar").hide();
      $("#btn-nombre-mes").hide();

      $("#tbl-principal").hide();
      $("#tbl-fechas").show();
      $("#tbl-ingreso-pagos").hide();
    }else{
      if (flag == 3) {
        $("#btn-regresar").hide();
        $("#btn-regresar-todo").show();
        $("#btn-regresar-bloque").show();
        $("#btn-agregar").show();
        $("#btn-nombre-mes").show();

        $("#tbl-principal").hide();
        $("#tbl-fechas").hide();
        $("#tbl-ingreso-pagos").show();
        
      }
    }
  }
}

// LISTAR TABLA PRINCIPAL
function listar_tbla_principal(id_proyecto) {

  var sabatical_total = 0, pago_acumulado_total = 0, saldo_total = 0, cant_q_s_total = 0;

  tabla_principal=$('#tabla-principal').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
      url: `../ajax/pago_obrero.php?op=listar_tbla_principal&nube_idproyecto=${id_proyecto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {          
      // columna:# 0
      if (data[0] != '') {
        $("td", row).eq(0).css({
          "text-align": "center"
        });         
      }  
      // columna: Horas Normal/Extra
      if (data[2] != '') {
        $("td", row).eq(2).css({
          "text-align": "center"
        });         
      }   
      
      // columna: Sabaticales  
      if (data[3] != '') {
        sabatical_total += parseFloat(data[3]);
      }     
      $("td", row).eq(3).css({
        "text-align": "center"
      });       

      // columna: Sueldo Mensual
      if (data[4] != '') {
        $("td", row).eq(4).css({
          "text-align": "right"
        });
      }      

      // columna: Pago acumulado Semana/Quincena
      if (data[5] != '') {

        var split = data[5].split(' '); console.log(split);
        var quitar_format_mil = quitar_formato_miles( split[1]);
        pago_acumulado_total += parseFloat(quitar_format_mil);
          
        $("td", row).eq(5).css({
          "text-align": "right"
        });
      }

      // columna: Depositos
      if (data[6] != '') {
        $("td", row).eq(6).addClass('justify-content-between');
      }

      // columna: Saldo
      if (data[7] != '') {

        var split = data[7].split(' '); console.log(split);
        var quitar_format_mil = quitar_formato_miles( split[1]);
        saldo_total += parseFloat(quitar_format_mil);

        if (parseFloat(quitar_format_mil) < 0) {
          $("td", row).eq(7).css({
            "text-align": "right"            
          }).addClass('bg-danger');
        }else{
          $("td", row).eq(7).css({
            "text-align": "right"
          });
        }        
      }

      // columna: Cantidad Semana/Quincena
      if (data[8] != '') {
        cant_q_s_total += parseFloat(data[8]);
        $("td", row).eq(8).css({
          "text-align": "center"
        });
      }
      
    },
    "language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10,//Paginación
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();

  // suma totales x proyecto
  $.post("../ajax/pago_obrero.php?op=mostrar_deposito_total_tbla_principal", { 'id_proyecto': id_proyecto }, function (data, status) {

    data = JSON.parse(data); console.log(data); 

    $(".deposito_total_tbla_principal").html(`<sup>S/.</sup> <b>${data.total_deposito_x_proyecto}</b>`);
    $(".sueldo_total_tbla_principal").html(`<sup>S/.</sup> <b>${data.sueldo_mesual_x_proyecto}</b>`);

    $('.sabatical_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(sabatical_total)}</b>`);
    $('.pago_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(pago_acumulado_total)}</b>`);
    $('.saldo_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(saldo_total)}</b>`);  
    $('.cant_s_q_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(cant_q_s_total)}</b>`);
  });
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

  if (tipo_pago == "quincenal") {
    $(".nombre-bloque-asistencia").html(`<b> Quincena </b>`);
  } else {
    if (tipo_pago == "semanal") {
      $(".nombre-bloque-asistencia").html(`<b> Semana </b>`);
    }
  }
  
  table_show_hide(2);

  $.post("../ajax/pago_obrero.php?op=listar_tbla_q_s", { 'id_trabajdor_x_proyecto': id_trabajdor_x_proyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);

    if (data.length === 0) {
      
    } else {

      var data_s_q = ""; var total_hn = 0, total_he = 0, total_monto_hn = 0, total_monto_he = 0, total_descuento = 0;
      var total_quincena = 0, total_saldo = 0, total_deposito = 0, rh_total = 0, total_sabatical = 0;

      data.forEach((element, indice) => {

        var saldo = 0; var btn_tipo = "", bg_saldo = "", btn_tipo_deposito = "";

        // Validamos el tipo de boton para los "recibos por honorarios"
        if (element.recibos_x_honorarios == '' || element.recibos_x_honorarios == null) { btn_tipo = 'btn-outline-info'; } else { btn_tipo = 'btn-info'; rh_total += 1; }
        
        saldo = parseFloat(element.pago_quincenal) - parseFloat(element.deposito);

        // background-color al saldo
        if (saldo < 0) { bg_saldo = 'bg-danger'; } else { bg_saldo = ''; }

        // background-color btn depositos
        if ( parseFloat(element.deposito) == 0 ) {
          btn_tipo_deposito = "btn-danger";
        } else {
          if ( parseFloat(element.deposito) > 0 &&  parseFloat(element.deposito) < parseFloat(element.pago_quincenal) ) {
            btn_tipo_deposito = "btn-warning";
          } else {
            if ( parseFloat(element.deposito) >= parseFloat(element.pago_quincenal) ) {
              btn_tipo_deposito = "btn-success";
            }
          }              
        }

        data_s_q = data_s_q.concat(`<tr>
          <td>${indice + 1}</td>
          <td> ${element.numero_q_s}</td>
          <td>${format_d_m_a(element.fecha_q_s_inicio)}</td>
          <td>${format_d_m_a(element.fecha_q_s_fin)}</td>
          <td><sup>S/. </sup>${element.sueldo_hora}</td>
          <td>${formato_miles(element.total_hn)}<b> / </b>${formato_miles(element.total_he)}</td>
          <td>${element.sabatical}</td>          
          <td><sup>S/. </sup>${formato_miles(element.pago_parcial_hn)}<b> / </b><sup>S/. </sup>${formato_miles(element.pago_parcial_he)}</td>
          <td style="text-align: right !important;"><sup>S/. </sup>${formato_miles(element.adicional_descuento)}</td>
          <td style="text-align: right !important;"><sup>S/. </sup>${formato_miles(element.pago_quincenal)}</td>
          <td>
            <button class="btn btn-info btn-sm" onclick="listar_tbla_pagos_x_q_s('${element.idresumen_q_s_asistencia}', '${format_d_m_a(element.fecha_q_s_inicio)}', '${format_d_m_a(element.fecha_q_s_fin)}', '${element.pago_quincenal}', '${element.numero_q_s}', '${tipo_pago}', '${nombre_trabajador}','${cuenta_bancaria}', '${saldo}' );"><i class="fas fa-dollar-sign"></i> Pagar</button>
            <button style="font-size: 14px;" class="btn ${btn_tipo_deposito} btn-sm">${formato_miles(element.deposito)}</button></div>
          </td>
          <td style="text-align: right !important;" class="${bg_saldo}"><sup>S/. </sup>${formato_miles(saldo)}</td>
          <td> 
            <button class="btn ${btn_tipo} btn-sm"  onclick="modal_recibos_x_honorarios('${element.idresumen_q_s_asistencia}', '${element.fecha_q_s_inicio}', '${element.fecha_q_s_fin}', '${element.numero_q_s}', '${element.recibos_x_honorarios}', '${tipo_pago}', '${nombre_trabajador}');">
              <i class="fas fa-file-invoice fa-lg"></i>
            </button> 
          </td>
        </tr>`);
        
        total_hn += parseFloat(element.total_hn);
        total_he += parseFloat(element.total_he);

        total_sabatical += parseFloat(element.sabatical);

        total_monto_hn += parseFloat(element.pago_parcial_hn);
        total_monto_he += parseFloat(element.pago_parcial_he);
        total_descuento += parseFloat(element.adicional_descuento);
        total_quincena += parseFloat(element.pago_quincenal);
        total_deposito += parseFloat(element.deposito);
        total_saldo += parseFloat(saldo);
      });

      $('.data-q-s').html(data_s_q);
      $('.total_hn_he').html(`${formato_miles(total_hn)} / ${formato_miles(total_he)}`);
      $('.total_sabatical').html(`${formato_miles(total_sabatical)} `);
      $('.total_monto_hn_he').html(`<sup>S/. </sup>${formato_miles(total_monto_hn)} / <sup>S/. </sup>${formato_miles(total_monto_he)}`);
      $('.total_descuento').html(`<sup>S/. </sup>${formato_miles(total_descuento)}`);
      $('.total_quincena').html(`<sup>S/. </sup>${formato_miles(total_quincena)}`);
      $('.total_deposito').html(`<sup>S/. </sup>${formato_miles(total_deposito)}`); 
      $('.total_saldo').html(`<sup>S/. </sup>${formato_miles(total_saldo)}`); 
      $('.rh_total').html(`${rh_total} <small class="text-gray">(docs.)</small>`);
    }     
  });   
}

// Listar: los PAGOS de un QUINCENA O SEMANA
function listar_tbla_pagos_x_q_s(idresumen_q_s_asistencia, fecha_inicio, fecha_final, pago_q_s, numero_q_s, tipo_pago, nombre_trabajador, cuenta_bancaria, saldo_q_s ) {

  table_show_hide(3);

  $('#btn-nombre-mes').html(`&nbsp; &nbsp; <i class="fas fa-calendar-check text-gray-50"></i> <b>${fecha_inicio}  <i class="fas fa-arrow-right"></i>  ${fecha_final}</b> - <sup>S/.</sup><b>${formato_miles(pago_q_s)}</b>`);
  
  if ( parseFloat(saldo_q_s) < 0) {
    $('.faltante_mes_modal').css({'background-color' : 'red', 'color':'white'});
    $('.faltante_mes_modal').html(`<sup>S/. </sup>${formato_miles(saldo_q_s)}`);
  } else {
    if (parseFloat(saldo_q_s) == 0) {
      $('.faltante_mes_modal').css({'background-color' : 'green', 'color':'white'});
      $('.faltante_mes_modal').html(`<sup>S/. </sup><b>${formato_miles(saldo_q_s)}</b>`);  
    } else {
      $('.faltante_mes_modal').css({'background-color' : '#ffc107', 'color':'black'});
      $('.faltante_mes_modal').html(`<sup>S/. </sup><b>${formato_miles(saldo_q_s)}</b>`);
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
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
      url: '../ajax/pago_obrero.php?op=listar_tbla_pagos_x_q_s&idresumen_q_s_asistencia='+idresumen_q_s_asistencia,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: opciones
      if (data[0] != '') {
        $("td", row).eq(0).addClass('text-center');
      }
      // columna: opciones
      if (data[1] != '') {
        $("td", row).eq(1).addClass('text-nowrap');
      }
      // columna: cuenta deposito
      if (data[2] != '') {
        $("td", row).eq(2).addClass('text-nowrap');
      }
      // columna: deposito
      if (data[4] != '') {
        $("td", row).eq(4).addClass('text-right text-nowrap');
      }
    },
    "language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10,//Paginación
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

// mostrar recibos por honorarios
function modal_recibos_x_honorarios(idresumen_q_s_asistencia, fecha_inicial, fecha_final, numero_q_s, recibos_x_honorarios, tipo_pago, nombre_trabajador) {
  
  // borramos los campos cargados con anterioridad
  limpiar_form_recibos_x_honorarios();

  $('#modal-recibos-x-honorarios').modal('show');
  
  $("#doc2_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

  $('#idresumen_q_s_asistencia_rh').val(idresumen_q_s_asistencia);
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

    if ( extrae_extencion(recibos_x_honorarios) == "pdf" ) {
      $("#doc2_ver").html(`<iframe src="../dist/docs/pago_obrero/recibos_x_honorarios/${recibos_x_honorarios}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
    } else {
      if ( extrae_extencion(recibos_x_honorarios) == "jpeg" || extrae_extencion(recibos_x_honorarios) == "jpg" || extrae_extencion(recibos_x_honorarios) == "jpe" ||
        extrae_extencion(recibos_x_honorarios) == "jfif" || extrae_extencion(recibos_x_honorarios) == "gif" || extrae_extencion(recibos_x_honorarios) == "png" ||
        extrae_extencion(recibos_x_honorarios) == "tiff" || extrae_extencion(recibos_x_honorarios) == "tif" || extrae_extencion(recibos_x_honorarios) == "webp" ||
        extrae_extencion(recibos_x_honorarios) == "bmp" || extrae_extencion(recibos_x_honorarios) == "svg" ) {

        $("#doc2_ver").html(`<img src="../dist/docs/pago_obrero/recibos_x_honorarios/${recibos_x_honorarios}" alt="" width="50%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
        
      } else {
        $("#doc2_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
      }      
    }
  }
}

//Guardar o editar - R H
function guardar_y_editar_recibos_x_honorarios(e) {

  e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-recibos_x_honorarios")[0]);

  $.ajax({
    url: "../ajax/pago_obrero.php?op=guardar_y_editar_recibo_x_honorario",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

        detalle_q_s_trabajador(id_trabajdor_x_proyecto_r, tipo_pago_r, nombre_trabajador_r, cuenta_bancaria_r);

        tabla_principal.ajax.reload();

        Swal.fire("Correcto!", "Recibo por honorario guardado correctamente", "success");	      
         
				limpiar_form_recibos_x_honorarios();

        $("#modal-recibos-x-honorarios").modal("hide");        

			}else{

        Swal.fire("Error!", datos, "error");				 
			}
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {

          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});

          $("#barra_progress").text(percentComplete.toFixed(2)+" %");

          if (percentComplete === 100) {

            setTimeout(l_m, 600);
          }
        }
      }, false);
      return xhr;
    }
  });
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

    success: function (datos) {       

      if (datos == 'ok') {

        tabla_ingreso_pagos.ajax.reload(); 
          
        listar_tbla_principal(localStorage.getItem('nube_idproyecto'));    

        Swal.fire("Correcto!", "Pago guardado correctamente", "success");	      
         
				limpiar_pago_q_s();

        $("#modal-agregar-pago-trabajdor").modal("hide");        

			}else{

        Swal.fire("Error!", datos, "error");				 
			}
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {

          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});

          $("#barra_progress").text(percentComplete.toFixed(2)+" %");

          if (percentComplete === 100) {

            setTimeout(l_m, 600);
          }
        }
      }, false);
      return xhr;
    }
  });
}

function l_m(){
  
  // limpiar();
  $("#barra_progress").css({"width":'0%'});
  $("#barra_progress").text("0%");

  $("#barra_progress2").css({"width":'0%'});
  $("#barra_progress2").text("0%");  
}

// Mostramos "PAGOS Q S" para editar
function mostrar_pagos_x_q_s(id) {

  limpiar_pago_q_s();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  $("#modal-agregar-pago-trabajdor").modal('show');

  $.post("../ajax/pago_obrero.php?op=mostrar_pagos_x_q_s", { 'idpagos_q_s_obrero': id }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 
    
    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $('#idpagos_q_s_obrero').val(data.idpagos_q_s_obrero);
    $("#monto").val(data.monto_deposito);
    $("#cuenta_deposito").val(data.cuenta_deposito);
    $("#forma_pago").val(data.forma_de_pago).trigger("change"); 
    $("#descripcion").val(data.descripcion); 

    //validamoos BAUCHER - DOC 1
    if (data.baucher == "" || data.baucher == null  ) {

      $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

      $("#doc1_nombre").html('');

      $("#doc_old_1").val(""); $("#doc1").val("");

    } else {

      $("#doc_old_1").val(data.baucher); 

      $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(data.baucher)}</i></div></div>`);
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.baucher) == "pdf" ) {

        $("#doc1_ver").html('<iframe src="../dist/pago_obrero/baucher_deposito/'+data.baucher+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

      }else{
        if (
          extrae_extencion(data.baucher) == "jpeg" || extrae_extencion(data.baucher) == "jpg" || extrae_extencion(data.baucher) == "jpe" ||
          extrae_extencion(data.baucher) == "jfif" || extrae_extencion(data.baucher) == "gif" || extrae_extencion(data.baucher) == "png" ||
          extrae_extencion(data.baucher) == "tiff" || extrae_extencion(data.baucher) == "tif" || extrae_extencion(data.baucher) == "webp" ||
          extrae_extencion(data.baucher) == "bmp" || extrae_extencion(data.baucher) == "svg" ) {

          $("#doc1_ver").html(`<img src="../dist/pago_obrero/baucher_deposito/${data.baucher}" alt="" width="50%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
    }     
  });
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

        if (e == "ok") {

          tabla_ingreso_pagos.ajax.reload(); 

          listar_tbla_principal(localStorage.getItem('nube_idproyecto')); 

          Swal.fire("Anulado!", "Tu registro ha sido Anulado.", "success");
        } else {

          Swal.fire("Error!", e, "error");
        }        
      });      
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

        if (e == "ok") {

          tabla_ingreso_pagos.ajax.reload(); 

          listar_tbla_principal(localStorage.getItem('nube_idproyecto'));

          Swal.fire("ReActivado!", "Tu registro ha sido ReActivado.", "success");
        } else {

          Swal.fire("Error!", e, "error");
        }        
      });      
    }
  });
}

function reload_table_detalle_x_q_s() {
  detalle_q_s_trabajador(id_trabajdor_x_proyecto_r, tipo_pago_r, nombre_trabajador_r, cuenta_bancaria_r);
}

init();

$(function () {

  $.validator.setDefaults({ submitHandler: function (e) { guardar_y_editar_pagos_x_q_s(e); }, });

  $("#form-pagos-x-q-s").validate({
    rules: {
      forma_pago: { required: true},
      monto: {required: true, minlength: 1 },
      descripcion: { minlength: 4 },
    },
    messages: {
      forma_pago: {
        required: "Campo requerido."
      },
      monto: {
        required: "Campo requerido.",
        minlength: "MINIMO 1 dígito.",
      },
      descripcion: {
        minlength: "MINIMO 4 caracteres.",
      },
    },
    
    errorElement: "span",

    errorPlacement: function (error, element) {

      error.addClass("invalid-feedback");

      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {

      $(element).addClass("is-invalid");
    },

    unhighlight: function (element, errorClass, validClass) {

      $(element).removeClass("is-invalid").addClass("is-valid");
             
    },
  });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


// Función que suma o resta días a la fecha indicada
sumaFecha = function(d, fecha){
  var Fecha = new Date();
  var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
  var sep = sFecha.indexOf('/') != -1 ? '/' : '-';
  var aFecha = sFecha.split(sep);
  var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];
  fecha= new Date(fecha);
  fecha.setDate(fecha.getDate()+parseInt(d));
  var anno=fecha.getFullYear();
  var mes= fecha.getMonth()+1;
  var dia= fecha.getDate();
  mes = (mes < 10) ? ("0" + mes) : mes;
  dia = (dia < 10) ? ("0" + dia) : dia;
  var fechaFinal = dia+sep+mes+sep+anno;
  return (fechaFinal);
}

// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {

  var format = "";

  if (fecha == '' || fecha == null || fecha == '0000-00-00') {
    format = "-";
  } else {
    let splits = fecha.split("-"); //console.log(splits);
    format = splits[2]+'-'+splits[1]+'-'+splits[0];
  } 

  return format;
}

// convierte de una fecha(aa-mm-dd): 23-12-2021 a una fecha(dd-mm-aa): 2021-12-23
function format_a_m_d(fecha) {

  var format = "";

  if (fecha == '' || fecha == null || fecha == '00-00-0000') {
    format = "-";
  } else {
    let splits = fecha.split("-"); //console.log(splits);
    format = splits[2]+'-'+splits[1]+'-'+splits[0];
  } 

  return format;
}

// quitamos las comas de miles de un numero
function quitar_formato_miles(numero) {
  let inVal = numero.replace(/,/g, '');
  return inVal;
}

// damos formato de miles a un numero
function formato_miles(num) {
  if (!num || num == "NaN") return "0.00";
  if (num == "Infinity") return "&#x221e;";
  num = num.toString().replace(/\$|\,/g, "");
  if (isNaN(num)) num = "0";
  sign = num == (num = Math.abs(num));
  num = Math.floor(num * 100 + 0.50000000001);
  cents = num % 100;
  num = Math.floor(num / 100).toString();
  if (cents < 10) cents = "0" + cents;
  for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) num = num.substring(0, num.length - (4 * i + 3)) + "," + num.substring(num.length - (4 * i + 3));
  return (sign ? "" : "-") + num + "." + cents;
}

/* PREVISUALIZAR LOS DOCUMENTOS */
function addDocs(e,id) {

  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');	console.log(id);

	var file = e.target.files[0], archivoType = /image.*|application.*/;
	
	if (e.target.files[0]) {
    
		var sizeByte = file.size; console.log(file.type);

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(archivoType) ){
			// return;
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Este tipo de ARCHIVO no esta permitido elija formato: .pdf, .png. .jpeg, .jpg, .jpe, .webp, .svg',
        showConfirmButton: false,
        timer: 1500
      });

      $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >'); 

		}else{

			if (sizekiloBytes <= 40960) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;

          // cargamos la imagen adecuada par el archivo
				  if ( extrae_extencion(file.name) == "doc") {
            $("#"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
          } else {
            if ( extrae_extencion(file.name) == "docx" ) {
              $("#"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
            }else{
              if ( extrae_extencion(file.name) == "pdf" ) {
                $("#"+id+"_ver").html(`<iframe src="${result}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
              }else{
                if ( extrae_extencion(file.name) == "csv" ) {
                  $("#"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
                } else {
                  if ( extrae_extencion(file.name) == "xls" ) {
                    $("#"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
                  } else {
                    if ( extrae_extencion(file.name) == "xlsx" ) {
                      $("#"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                    } else {
                      if ( extrae_extencion(file.name) == "xlsm" ) {
                        $("#"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                      } else {
                        if (
                          extrae_extencion(file.name) == "jpeg" || extrae_extencion(file.name) == "jpg" || extrae_extencion(file.name) == "jpe" ||
                          extrae_extencion(file.name) == "jfif" || extrae_extencion(file.name) == "gif" || extrae_extencion(file.name) == "png" ||
                          extrae_extencion(file.name) == "tiff" || extrae_extencion(file.name) == "tif" || extrae_extencion(file.name) == "webp" ||
                          extrae_extencion(file.name) == "bmp" || extrae_extencion(file.name) == "svg" ) {

                          $("#"+id+"_ver").html(`<img src="${result}" alt="" width="50%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
                          
                        } else {
                          $("#"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                        }
                        
                      }
                    }
                  }
                }
              }
            }
          } 
					$("#"+id+"_nombre").html(`<div class="row">
            <div class="col-md-12">
              <i> ${file.name} </i>
            </div>
            <div class="col-md-12">
              <button class="btn btn-danger btn-block btn-xs" onclick="${id}_eliminar();" type="button" ><i class="far fa-trash-alt"></i></button>
            </div>
          </div>`);

          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `El documento: ${file.name.toUpperCase()} es aceptado.`,
            showConfirmButton: false,
            timer: 1500
          });
				}

				reader.readAsDataURL(file);

			} else {
        Swal.fire({
          position: 'top-end',
          icon: 'warning',
          title: `El documento: ${file.name.toUpperCase()} es muy pesado.`,
          showConfirmButton: false,
          timer: 1500
        })

        $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
        $("#"+id+"_nombre").html("");
				$("#"+id).val("");
			}
		}
	}else{
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Seleccione un documento',
      showConfirmButton: false,
      timer: 1500
    })
		 
    $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
		$("#"+id+"_nombre").html("");
    $("#"+id).val("");
	}	
}

// recargar un doc para ver
function re_visualizacion(id, carpeta) {

  $("#doc"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>'); console.log(id);

  pdffile     = document.getElementById("doc"+id+"").files[0];

  var antiguopdf  = $("#doc_old_"+id+"").val();

  if(pdffile === undefined){

    if (antiguopdf == "") {

      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Seleccione un documento',
        showConfirmButton: false,
        timer: 1500
      })

      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

		  $("#doc"+id+"_nombre").html("");

    } else {
      if ( extrae_extencion(antiguopdf) == "doc") {
        $("#doc"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
        toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
      } else {
        if ( extrae_extencion(antiguopdf) == "docx" ) {
          $("#doc"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
          toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
        } else {
          if ( extrae_extencion(antiguopdf) == "pdf" ) {
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/pago_obrero/${carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
            toastr.success('Documento vizualizado correctamente!!!')
          } else {
            if ( extrae_extencion(antiguopdf) == "csv" ) {
              $("#doc"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
              toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
            } else {
              if ( extrae_extencion(antiguopdf) == "xls" ) {
                $("#doc"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
                toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
              } else {
                if ( extrae_extencion(antiguopdf) == "xlsx" ) {
                  $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                  toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                } else {
                  if ( extrae_extencion(antiguopdf) == "xlsm" ) {
                    $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                    toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                  } else {
                    if (
                      extrae_extencion(antiguopdf) == "jpeg" || extrae_extencion(antiguopdf) == "jpg" || extrae_extencion(antiguopdf) == "jpe" ||
                      extrae_extencion(antiguopdf) == "jfif" || extrae_extencion(antiguopdf) == "gif" || extrae_extencion(antiguopdf) == "png" ||
                      extrae_extencion(antiguopdf) == "tiff" || extrae_extencion(antiguopdf) == "tif" || extrae_extencion(antiguopdf) == "webp" ||
                      extrae_extencion(antiguopdf) == "bmp" || extrae_extencion(antiguopdf) == "svg" ) {
  
                      $("#doc"+id+"_ver").html(`<img src="../dist/docs/pago_obrero/${carpeta}/${antiguopdf}" alt="" onerror="this.src='../dist/svg/error-404-x.svg';" width="50%" >`);
                      toastr.success('Documento vizualizado correctamente!!!');
                    } else {
                      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                      toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                    }                    
                  }
                }
              }
            }
          }
        }
      }      
    }
    // console.log('hola'+dr);
  }else{

    pdffile_url=URL.createObjectURL(pdffile);

    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(pdffile.name) == "doc") {
      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
      toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
    } else {
      if ( extrae_extencion(pdffile.name) == "docx" ) {
        $("#doc"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
        toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
      }else{
        if ( extrae_extencion(pdffile.name) == "pdf" ) {
          $("#doc"+id+"_ver").html('<iframe src="'+pdffile_url+'" frameborder="0" scrolling="no" width="100%" height="310"> </iframe>');
          toastr.success('Documento vizualizado correctamente!!!');
        }else{
          if ( extrae_extencion(pdffile.name) == "csv" ) {
            $("#doc"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
            toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
          } else {
            if ( extrae_extencion(pdffile.name) == "xls" ) {
              $("#doc"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
              toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
            } else {
              if ( extrae_extencion(pdffile.name) == "xlsx" ) {
                $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
              } else {
                if ( extrae_extencion(pdffile.name) == "xlsm" ) {
                  $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                  toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
                } else {
                  if (
                    extrae_extencion(pdffile.name) == "jpeg" || extrae_extencion(pdffile.name) == "jpg" || extrae_extencion(pdffile.name) == "jpe" ||
                    extrae_extencion(pdffile.name) == "jfif" || extrae_extencion(pdffile.name) == "gif" || extrae_extencion(pdffile.name) == "png" ||
                    extrae_extencion(pdffile.name) == "tiff" || extrae_extencion(pdffile.name) == "tif" || extrae_extencion(pdffile.name) == "webp" ||
                    extrae_extencion(pdffile.name) == "bmp" || extrae_extencion(pdffile.name) == "svg" ) {

                    $("#doc"+id+"_ver").html(`<img src="${pdffile_url}" alt="" width="50%" >`);
                    toastr.success('Documento vizualizado correctamente!!!');
                  } else {
                    $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                    toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
                  }                  
                }
              }
            }
          }
        }
      }
    }     	
    console.log(pdffile);
  }
}

function extrae_extencion(filename) {
  return filename.split('.').pop();
}