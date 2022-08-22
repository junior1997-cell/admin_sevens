var tabla_fecha_proyeccion; 

var idproyecto_r='', idproyeccion_r='', fecha_r='', caja_r='';

//Función que se ejecuta al inicio
function init() {

  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#mContableFinanciero").addClass("active");

  $("#lEstadoFinanciero").addClass("active bg-primary");
  
  tbla_estado_financiero(localStorage.getItem('nube_idproyecto'));
  listar_fechas_proyeccion(localStorage.getItem('nube_idproyecto'));
  // tbla_principal_fecha_proyeccion(localStorage.getItem('nube_idproyecto'))

  // efectuamos SUBMIT  registro de: RECIBOS POR HONORARIOS
  $("#guardar_registro_proyecciones").on("click", function (e) { $("#submit-form-proyecciones").submit();  });

  //Initialize Select2 unidad
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar una forma de pago", allowClear: true, });

  $('#fecha_p').datepicker({ format: "dd-mm-yyyy", language: "es", autoclose: true, clearBtn: true,  weekStart: 0, orientation: "bottom auto", todayBtn: true });

  //formato_miles_input_negativo('.input_ef');
  //formato_miles_input(`.caja_pry`);

  // Insertamos el ID del proyecto actual
  $("#idproyecto_p").val(localStorage.getItem('nube_idproyecto'));

  // Formato para telefono
  $("[data-mask]").inputmask();    
} 

// click input group para habilitar: datepiker
$('.click-btn-fecha-p').on('click', function (e) {$('#fecha_p').focus().select(); });

// ══════════════════════════════════════ ESTADO FINANCIERO ══════════════════════════════════════ 

function show_hide_span_input_ef(flag){

  if (flag == 1) {
    // ocultamos los span
    $(".span_ef").show();
    // mostramos los inputs
    $(".input_ef").hide();

    // ocultamos el boton editar
    $("#btn-editar-ef").show();
    // mostramos el boton guardar
    $("#btn-guardar-ef").hide();
  } else if (flag == 2) {
    
    // ocultamos los span
    $(".span_ef").hide();
    // mostramos los inputs
    $(".input_ef").show();

    // ocultamos el boton editar
    $("#btn-editar-ef").hide();
    // mostramos el boton guardar
    $("#btn-guardar-ef").show();
  }  
}

//Función limpiar
function limpiar_form_estado_financiero() {  

  $("#monto").val("");
  $("#forma_pago").val("").trigger("change"); 
  $("#descripcion").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar - tabla principal
function tbla_estado_financiero(nube_idproyecto) {

  $('.sueldo_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  var total_pago_acumulado_hoy = 0, pago_total_x_proyecto = 0, saldo_total = 0;

  $.post("../ajax/estado_financiero.php?op=estado_financiero", { 'nube_idproyecto': nube_idproyecto }, function (e, status) {
    e = JSON.parse(e);  console.log(e); 
    if (e.status == true) {
      $('#idestado_financiero').val(e.data.idestado_financiero);
      $('#caja_ef').val(formato_miles(e.data.caja));
      $('.caja_ef').html(formato_miles(e.data.caja));
      $('.prestamo_y_credito_ef').html(formato_miles(e.data.prestamo_y_credito));
      $('.gastos_actuales_ef').html(formato_miles(e.data.gasto_de_modulos));
      $('.valorizacion_cobrada_ef').html(formato_miles(e.data.valorizacion_cobrada.val_cobrada));     
      $('.cant_cobradas').html(e.data.valorizacion_cobrada.cant_val_cobrada);
      $('.valorizacion_por_cobrar_ef').html(formato_miles(e.data.valorizacion_por_cobrada.val_por_cobrar));  
      $('.cant_por_cobrar').html(e.data.valorizacion_por_cobrada.cant_val_por_cobrar);
      $('.garantia_ef').html(formato_miles(e.data.garantia));
      $('.monto_de_obra_ef').html(formato_miles(e.data.monto_de_obra));

      var interes_pagado =  e.data.prestamo_y_credito + e.data.valorizacion_cobrada.val_cobrada - e.data.gasto_de_modulos - e.data.caja;
      var ganacia_actual =  e.data.valorizacion_cobrada.val_cobrada - e.data.gasto_de_modulos - interes_pagado;
      var ganacia_actual_porcentaje = ( ganacia_actual / e.data.monto_de_obra) * 100;

      $('.interes_pagado').html(formato_miles(interes_pagado));
      $('.ganacia_actual').html(formato_miles(ganacia_actual));
      $('.ganacia_actual_porcentaje').html(formato_miles(ganacia_actual_porcentaje) + '%');
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//Función para guardar o editar
function guardar_y_editar_estado_financiero(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var caja_ef = quitar_formato_miles($('#caja_ef').val());
  var idestado_financiero = $('#idestado_financiero').val();

  Swal.fire({
    title: "¿Está seguro que deseas guardar?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
    preConfirm: (input) => {
      return fetch(`../ajax/estado_financiero.php?op=guardar_y_editar_estado_financiero&idestado_financiero=${idestado_financiero}&nube_idproyecto=${localStorage.getItem('nube_idproyecto')}&caja=${caja_ef}`).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); });
    },
    showLoaderOnConfirm: true,
  }).then((result) => {
    if (result.isConfirmed) {
      if (result.value.status == true){        
        Swal.fire("Correcto!", "Estado Financiero guardada correctamente", "success");

        tbla_estado_financiero(localStorage.getItem('nube_idproyecto'));
        show_hide_span_input_ef(1);
      } else {
        ver_errores(result);
      }      
    }
  });

}

// mostramos loa datos para editar: "pagos por mes"
function update_interes_y_ganancia_ef() { 
  var caja                = quitar_formato_miles($('#caja_ef').val()); console.log(caja);
  var prestamo_y_credito  = quitar_formato_miles($('.prestamo_y_credito_ef').text());
  var gasto_de_modulos    = quitar_formato_miles($('.gastos_actuales_ef').text());
  var val_cobrada         = quitar_formato_miles($('.valorizacion_cobrada_ef').text());  
  var monto_de_obra       = quitar_formato_miles($('.monto_de_obra_ef').text());

  var interes_pagado      =  prestamo_y_credito + val_cobrada - gasto_de_modulos - caja;
  var ganacia_actual      =  val_cobrada - gasto_de_modulos - interes_pagado;
  var ganacia_actual_porcentaje = ( ganacia_actual / monto_de_obra) * 100;

  $('.interes_pagado').html(formato_miles(interes_pagado));
  $('.ganacia_actual').html(formato_miles(ganacia_actual));
  $('.ganacia_actual_porcentaje').html(formato_miles(ganacia_actual_porcentaje) + '%');
}

// ══════════════════════════════════════ P R O Y E C C I O N E S ══════════════════════════════════════ 
function show_hide_span_input_p(flag, id_span) {
  if (flag == 1) {
    // ocultamos los span
    $(`.span_p_${id_span}`).show();
    // mostramos los inputs
    $(`.input_p_${id_span}`).hide();

    // ocultamos el boton editar
    $(`.btn-editar-p`).show();
    // mostramos el boton guardar
    $(`.btn-guardar-p`).hide();

    // ocultamos el boton delete-detalle-proyeccion
    $(`.btn-delete-sdp`).hide();
  } else if (flag == 2) {
    
    // ocultamos los span
    $(`.span_p_${id_span}`).hide();
    // mostramos los inputs
    $(`.input_p_${id_span}`).show();

    // ocultamos el boton editar
    $(`.btn-editar-p`).hide();
    // mostramos el boton guardar
    $(`.btn-guardar-p`).show();
     // mostramos el boton delete-detalle-proyeccion
     $(`.btn-delete-sdp`).show();
  } 
}

function listar_fechas_proyeccion(idproyecto) { 

  $(".lista-fechas-proyeccion").html(`<li class="nav-item"><a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a></li>`); 

  $.post("../ajax/estado_financiero.php?op=listar_fechas_proyeccion", { 'idproyecto': idproyecto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);
    // e.data.idtipo_tierra
    if (e.status) {
      var data_html = '';

      e.data.forEach((val, index) => {
        data_html = data_html.concat(`
        <li class="nav-item">
          <a class="nav-link" onclick="tbla_principal_detalle_proyeccion('${val.idproyecto}', '${val.idproyeccion}', '${val.fecha}', '${val.caja}');" id="tabs-for-detalle-proyeccion-tab" data-toggle="pill" href="#tabs-for-detalle-proyeccion" role="tab" aria-controls="tabs-for-detalle-proyeccion" aria-selected="false">${ format_a_m_d(val.fecha)}</a>
        </li>`);
      });

      $(".lista-fechas-proyeccion").html(`
        <li class="nav-item">
          <a class="nav-link" id="tabs-for-fecha-proyeccion-tab" data-toggle="pill" href="#tabs-for-fecha-proyeccion" role="tab" aria-controls="tabs-for-fecha-proyeccion" aria-selected="true" onclick="tbla_principal_fecha_proyeccion(${localStorage.getItem('nube_idproyecto')})">Fechas</a>
        </li>
        ${data_html}
      `); 
      //delay(function(){$('#tabs-for-resumen-tab').click();}, 100 );
      
      $('#tabs-for-fecha-proyeccion-tab').click();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función limpiar
function limpiar_form_proyecciones() {  

  $("#idproyeccion_p").val("");
  $("#fecha_p").val(""); 
  $("#caja_p").val( quitar_formato_miles($(".caja_ef").text()) ); 
  $("#descripcion_p").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function tbla_principal_fecha_proyeccion(idproyecto) {
  tabla_fecha_proyeccion = $("#tabla-fecha-proyeccion").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3,4,5], }, orientation: 'landscape', pageSize: 'LEGAL', },       
    ],
    ajax: {
      url: `../ajax/estado_financiero.php?op=tbla_principal_fecha_proyeccion&idproyecto=${idproyecto}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {    
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [3,4], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      //{ targets: [11,12,13], visible: false, searchable: false, },  
    ],
  }).DataTable();
}

function guardar_y_editar_proyecciones(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proyecciones")[0]);

  $.ajax({
    url: "../ajax/estado_financiero.php?op=guardar_y_editar_proyecciones",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Insumo guardado correctamente", "success");
          listar_fechas_proyeccion(localStorage.getItem('nube_idproyecto'));
          limpiar_form_proyecciones(); 

          $("#modal-agregar-proyecciones").modal("hide");
          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_proyecciones").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_proyeccion").css({"width": percentComplete+'%'});
          $("#barra_progress_proyeccion").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_proyecciones").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_proyeccion").css({ width: "0%",  });
      $("#barra_progress_proyeccion").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_proyeccion").css({ width: "0%", });
      $("#barra_progress_proyeccion").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_fecha_proyeccion(idproyeccion) {
  limpiar_form_proyecciones(); //console.log(idproducto);

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-proyecciones").modal("show");

  $.post("../ajax/estado_financiero.php?op=mostrar_fecha_proyeccion", { 'idproyeccion': idproyeccion }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {

      $("#idproyeccion_p").val(e.data.idproyeccion);  
      $("#idproyecto_p").val(e.data.idproyecto);      
      $("#fecha_p").datepicker("setDate" , format_d_m_a(e.data.fecha));
      $("#caja_p").val(e.data.caja);  
      $("#descripcion_p").val(e.data.descripcion);      
      
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_fechas_proyeccion(idproyeccion, nombre) {

  crud_eliminar_papelera(
    "../ajax/estado_financiero.php?op=desactivar_fechas_proyeccion",
    "../ajax/estado_financiero.php?op=eliminar_fechas_proyeccion", 
    idproyeccion, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ listar_fechas_proyeccion(localStorage.getItem('nube_idproyecto')); },
    false,
    false,
    false,
    false
  );
}

// ══════════════════════════════════════ D E T A L L E   P R O Y E C I O N E S ══════════════════════════════════════ 

function tbla_principal_detalle_proyeccion(idproyecto, idproyeccion, fecha, caja) {

  idproyecto_r=idproyecto; idproyeccion_r=idproyeccion; fecha_r=fecha; caja_r=caja;

  $(".tbody_proyeccion").html(`<tr>
    <td colspan="4">
      <div class="row" ><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-3x mb-2"></i><br/><h5>Cargando<span class="texto-parpadeante">...</span></h5></div></div>
    </td>                                                  
  </tr>`);
  
  show_hide_span_input_p(1, idproyeccion);

  $(".fecha_pd").html('<i class="fas fa-spinner fa-pulse"></i>');  
  $(".detalle_pd").html('<i class="fas fa-spinner fa-pulse"></i>');  

  $(".btn-guardar-p").attr('onclick',`guardar_y_editar_detalle_proyeccion(${idproyeccion});`); 
  $(".btn-editar-p").attr('onclick',`show_hide_span_input_p(2,${idproyeccion});`); 

  $.post("../ajax/estado_financiero.php?op=tbla_principal_detalle_proyeccion", { 'idproyecto':idproyecto, 'idproyeccion': idproyeccion }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {
      
      var html_data = ''; var total_proyeccion = 0;

      if (e.data.detalle.length === 0) {
        $(".tbody_proyeccion").html(`<tr>
          <td colspan="4">
            <div class="row" ><div class="col-lg-12 text-center"><h5>​😟​​ Sin datos</h5></div></div>
          </td>                                                  
        </tr>`);
      } else {      
        e.data.detalle.forEach((val, indice) => {

          var icon_acordion = val.sub_detalle.length === 0 ? '' : '<i class="expandable-table-caret fas fa-caret-right fa-fw"></i>';
          var input_readonly = val.sub_detalle.length === 0 ? '' : 'readonly' ;
          var input_no_border = val.sub_detalle.length === 0 ? '' : 'input-no-border-center-bold' ;

          var color_number_d = val.monto >= 0 ? 'numero_positivos' : 'numero_negativos' ;

          html_data = html_data.concat(`
          <tr class="data_${val.idproyeccion} data_bloque_${indice+1} detalle_tr_${indice+1} sub_${indice+1}_0 ${val.sub_detalle.length}">
            <td class="py-1 text-center detalle_td_num_${indice+1}" data-widget="expandable-table" aria-expanded="true" onclick="delay(function(){show_hide_tr('.detalle_td_num_${indice+1}','.sub_detalle_tr_${indice+1}')}, 200 );">${icon_acordion} ${indice+1}</td>
            <td class="py-1">
              <span class="span_p_${val.idproyeccion}">${val.nombre_proyeccion}</span> 
              <input type="text" id="" class="hidden input_p_${val.idproyeccion} input_n_dp_${val.idproyeccion}_${indice+1} w-100" value="${val.nombre_proyeccion}">
            </td>
            <td class="py-1">
            </td>                           
            <td class="py-1">
              <div class="formato-numero-conta span_p_${val.idproyeccion}">
                <span>S/</span> <span class="${color_number_d}">${formato_miles(val.monto)}</span> 
              </div> 
              <input type="hidden"  class="input_id_dp_${val.idproyeccion}_${indice+1}" value="${val.iddetalle_proyeccion}">
              <input type="text" id="" class="numberIndistintoFixed ${color_number_d} hidden input_p_${val.idproyeccion} input_dp_${val.idproyeccion}_${indice+1} w-100 ${input_no_border}" ${input_readonly} value="${formato_miles(val.monto)}" onkeyup="delay(function(){calc_total_proyeccion(${val.idproyeccion}, ${indice+1})}, 100 );" onfocus="this.select();">
            </td> 
            <td class="py-1">
              <button type="button" class="btn btn-xs bg-gradient-success detalle_btn_${indice+1} " onclick="add_tr_sub_detalle(${val.idproyeccion},${indice+1}, ${val.sub_detalle.length})" data-toggle="tooltip" data-original-title="Agregar Sub-Item" ><i class="fas fa-plus"></i> </button>
              <button type="button" class="btn btn-xs bg-gradient-danger btn-delete-sdp hidden" onclick="remove_tr_detalle(${val.idproyeccion},${indice+1},0)" data-toggle="tooltip" data-original-title="Eliminar Item"><i class="far fa-trash-alt"></i> </button>
              <input type="hidden" name="" id="cant_sub_detalle_${idproyeccion}_${indice+1}" value="${val.sub_detalle.length}">
            </td>
          </tr>`);

          total_proyeccion += parseFloat(val.monto);

          val.sub_detalle.forEach((val2, indice2) => {

            var color_number_sd = val2.monto >= 0 ? 'numero_positivos' : 'numero_negativos' ;

            html_data = html_data.concat(`
            <tr class="data_bloque_${indice+1} sub_detalle_tr_${indice+1} sub_${indice+1}_${indice2+1}">
              <td class="py-1 text-center"></td>
              <td class="py-1 text-right"> 
                <span class="span_p_${val.idproyeccion}">${val2.nombre}</span> 
                <input type="text" id="" class="hidden input_p_${val.idproyeccion} input_n_sdp_${val.idproyeccion}_${indice+1}_${indice2+1} w-75 float-right " value="${val2.nombre}">
              </td>                                                            
              <td class="py-1">
                <div class="formato-numero-conta span_p_${val.idproyeccion}">
                  <span>S/</span> <span class="${color_number_sd}">${formato_miles(val2.monto)}</span>
                </div> 
                <input type="hidden"  class="input_id_sdp_${val.idproyeccion}_${indice+1}_${indice2+1}" value="${val2.idsub_detalle_proyeccion}">
                <input type="text" id="" class="numberIndistintoFixed ${color_number_sd} hidden input_p_${val.idproyeccion} w-100 input_sdp_${val.idproyeccion}_${indice+1}_${indice2+1}" value="${formato_miles(val2.monto)}" onkeyup="delay(function(){calc_total_proyeccion(${val.idproyeccion}, ${indice+1})}, 100 );" onfocus="this.select();">
              </td> 
              <td class="py-1"> </td> 
              <td class="py-1">
                <button type="button" class="btn bg-gradient-danger btn-xs btn-delete-sdp hidden" onclick="remove_tr_sub_detalle(${val.idproyeccion},${indice+1}, ${indice2+1})" data-toggle="tooltip" data-original-title="Eliminar Sub-Item" ><i class="far fa-trash-alt"></i> </button>
              </td>
            </tr>`);
            // total_proyeccion += es_numero(val2.monto) == true ? parseFloat(val2.monto) : 0;
          });
        });

        $(".tbody_proyeccion").html(html_data);
      }

      $(".fecha_pd").html(format_d_m_a(e.data.fecha)); 
      $(".btn-add-detalle").addClass(`btn_th_${e.data.idproyeccion}`).attr('onclick', `add_tr_detalle(${e.data.idproyeccion}, ${e.data.detalle.length})`); 
      $(".detalle_pd").html(e.data.descripcion);

      // caja
      $(".caja_pry").html(formato_miles(e.data.caja + total_proyeccion));
      $('.prestamo_credito_pry').html(formato_miles(e.data.prestamo_y_credito));
      $('.gasto_actualizado_pry').html(formato_miles(e.data.gasto_de_modulos));
      $('.valorizacion_cobrada_pry').html(formato_miles(e.data.valorizacion_cobrada.val_cobrada));     
      $('.cant_cobradas_pry').html(e.data.valorizacion_cobrada.cant_val_cobrada);
      $('.valorizacion_por_cobrar_pry').html(formato_miles(e.data.valorizacion_por_cobrada.val_por_cobrar));  
      $('.cant_por_cobrar_pry').html(e.data.valorizacion_por_cobrada.cant_val_por_cobrar);
      $('.garantia_pry').html(formato_miles(e.data.garantia));
      $('.monto_obra_pry').html(formato_miles(e.data.monto_de_obra));   
      
      var interes_pagado =  e.data.prestamo_y_credito + e.data.valorizacion_cobrada.val_cobrada - e.data.gasto_de_modulos - (e.data.caja + total_proyeccion);
      var ganacia_actual =  e.data.valorizacion_cobrada.val_cobrada - e.data.gasto_de_modulos - interes_pagado;
      var ganacia_actual_porcentaje = ( ganacia_actual / e.data.monto_de_obra) * 100;

      $('.interes_pagado_pry').html(formato_miles(interes_pagado));
      $('.ganancia_actual_pry').html(formato_miles(ganacia_actual));
      $('.porcentaje_pry').html(formato_miles(ganacia_actual_porcentaje) + '%');
      
      $("#cant_detalle").val(e.data.detalle.length);
      $(".gasto_proyectado").html(formato_miles(total_proyeccion)); 
      total_proyeccion >= 0 ? $(".gasto_proyectado").removeClass('numero_negativos').addClass('numero_positivos') : $(".gasto_proyectado").removeClass('numero_positivos').addClass('numero_negativos') ;

      // Formato miles - input
      //formato_miles_input(`.input_miles`);      
      document.querySelectorAll(".numberIndistintoFixed").forEach((el) => el.addEventListener("keyup", numberFormatIndistintoFixed));
      // acticar tooltip
      $('[data-toggle="tooltip"]').tooltip();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function calc_total_proyeccion(idproyeccion, cont ='0') {

  var cant_detalle_class = $(`.data_${idproyeccion}`).toArray().length; 
  var cant_detalle = $(`#cant_detalle`).val(); 
  var total_detalle = 0;  

  for (let index = 1; index <= cant_detalle; index++) {

    var total_subdetalle = 0;

    var cant_sub_detalle_class = $(`.data_bloque_${index}`).toArray().length == 0 ? 0 : $(`.data_bloque_${index}`).toArray().length - 1;
    var cant_sub_detalle = $(`#cant_sub_detalle_${idproyeccion}_${index}`).val(); 
    console.log(`${index}. cant: ${cant_detalle} ${cant_sub_detalle} ${cant_sub_detalle_class}`);

    // subdetalle
    for (let index2 = 1; index2 <= cant_sub_detalle; index2++) {
      var input_subdetalle = es_numero(quitar_formato_miles($(`.input_sdp_${idproyeccion}_${index}_${index2}`).val())) == true ? parseFloat(quitar_formato_miles($(`.input_sdp_${idproyeccion}_${index}_${index2}`).val())) : 0 ;
      console.log(`subtotal: ${index2} - ${index} = ` + input_subdetalle);  
      total_subdetalle += input_subdetalle;
    }

    // reemplazamos el total sumado
    if (cant_sub_detalle_class > 0) {
      $(`.input_dp_${idproyeccion}_${index}`).val(formato_miles(total_subdetalle));
    }
    
    var input_detalle = es_numero(quitar_formato_miles( $(`.input_dp_${idproyeccion}_${index}`).val())) == true ? parseFloat(quitar_formato_miles($(`.input_dp_${idproyeccion}_${index}`).val())) : 0 ;
    console.log(`total: ${idproyeccion} - ${index} = ` + input_detalle);  
    total_detalle += input_detalle;
    console.log(`acumulado: ${total_detalle}`);
  }
  //console.log(total_detalle);
  //console.log(total_subdetalle);
  $(".gasto_proyectado").html(formato_miles(total_detalle)); 
  total_detalle >= 0 ? $(".gasto_proyectado").removeClass('numero_negativos').addClass('numero_positivos') : $(".gasto_proyectado").removeClass('numero_positivos').addClass('numero_negativos') ;

  var caja_ef = quitar_formato_miles($(".caja_ef").text());
  $(".caja_pry").html(formato_miles(total_detalle + caja_ef));  

  $(".tooltip").remove();

  // actualizamos los montos de Est. Finan.
  update_interes_y_ganancia_por_proyeccion();
}

// mostramos loa datos para editar: "pagos por mes"
function update_interes_y_ganancia_por_proyeccion() { 
  var caja                = quitar_formato_miles($('.caja_pry').text()); console.log(caja);
  var prestamo_y_credito  = quitar_formato_miles($('.prestamo_credito_pry').text());
  var gasto_de_modulos    = quitar_formato_miles($('.gasto_actualizado_pry').text());
  var val_cobrada         = quitar_formato_miles($('.valorizacion_cobrada_pry').text());  
  var monto_de_obra       = quitar_formato_miles($('.monto_obra_pry').text());

  var interes_pagado      =  prestamo_y_credito + val_cobrada - gasto_de_modulos - caja;
  var ganacia_actual      =  val_cobrada - gasto_de_modulos - interes_pagado;
  var ganacia_actual_porcentaje = ( ganacia_actual / monto_de_obra) * 100;

  $('.interes_pagado_pry').html(formato_miles(interes_pagado));
  $('.ganancia_actual_pry').html(formato_miles(ganacia_actual));
  $('.porcentaje_pry').html(formato_miles(ganacia_actual_porcentaje) + '%');
}

function guardar_y_editar_detalle_proyeccion(idproyeccion) {

  var cant_detalle = $(`#cant_detalle`).val(); 
  var total_detalle = 0;  

  var data_array = [];
  var data_detalle = [] ;  

  for (let index = 1; index <= cant_detalle; index++) {

    var total_subdetalle = 0;

    var cant_sub_detalle_class = $(`.data_bloque_${index}`).toArray().length == 0 ? 0 : $(`.data_bloque_${index}`).toArray().length - 1;
    var cant_sub_detalle = $(`#cant_sub_detalle_${idproyeccion}_${index}`).val(); 
    console.log(`${index}. cant: ${cant_detalle} ${cant_sub_detalle} ${cant_sub_detalle_class}`);
    
    var data_subdetalle = [] ;  

    // subdetalle
    for ( let index2 = 1; index2 <= cant_sub_detalle; index2++ ) {
      var input_subdetalle = es_numero(quitar_formato_miles($(`.input_sdp_${idproyeccion}_${index}_${index2}`).val())) == true ? parseFloat(quitar_formato_miles($(`.input_sdp_${idproyeccion}_${index}_${index2}`).val())) : 0 ;
      console.log(`subdetalle: ${index2} - ${index} = ` + input_subdetalle);  
      total_subdetalle += input_subdetalle;

      if ( $(`.input_n_sdp_${idproyeccion}_${index}_${index2}`).val() === undefined ) { }else{
        data_subdetalle.push({ 
          'idsub_detalle_proyeccion': $(`.input_id_sdp_${idproyeccion}_${index}_${index2}`).val()=== undefined ? '': $(`.input_id_sdp_${idproyeccion}_${index}_${index2}`).val() ,
          'nombre': $(`.input_n_sdp_${idproyeccion}_${index}_${index2}`).val(), 
          'total': input_subdetalle,
        });
      }      
    }

    var input_detalle = es_numero(quitar_formato_miles( $(`.input_dp_${idproyeccion}_${index}`).val())) == true ? parseFloat(quitar_formato_miles($(`.input_dp_${idproyeccion}_${index}`).val())) : 0 ;

    if ( $(`.input_n_dp_${idproyeccion}_${index}`).val() === undefined ) { }else{
      data_detalle.push({   
        'iddetalle_proyeccion': $(`.input_id_dp_${idproyeccion}_${index}`).val() === undefined ? '': $(`.input_id_dp_${idproyeccion}_${index}`).val(),   
        'nombre': $(`.input_n_dp_${idproyeccion}_${index}`).val(),
        'total': input_detalle,
        'subdetalle': data_subdetalle ,      
      });
    }

    console.log(`total: ${idproyeccion} - ${index}   = ` + input_detalle);  
    total_detalle += input_detalle;
    console.log(`acumulado: ${total_detalle}`);
  }

  data_array = {
    'idproyeccion': idproyeccion,
    'gasto_proyectado': quitar_formato_miles($(`.gasto_proyectado`).text()),
    'caja': quitar_formato_miles($(`.caja_pry`).text()),      
    'detalle': data_detalle
  };

  console.log(data_array);

  // alerta antes de guardar
  Swal.fire({
    title: '¿Está seguro que deseas guardar esta proyección?',
    html: "Verifica que todos lo <b>campos</b> esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
    preConfirm: (input) => {
      return fetch("../ajax/estado_financiero.php?op=guardar_y_editar_detalle_proyecciones", {
        method: 'POST', // or 'PUT'
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ 'data_array': data_array}),// data can be `string` or {object}!        
      }).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); });
    },
    showLoaderOnConfirm: true,
  }).then((result) => {
    if (result.isConfirmed) {
      if (result.value.status == true){       
        tbla_principal_detalle_proyeccion(idproyecto_r, idproyeccion_r, fecha_r, caja_r);
        Swal.fire("Correcto!", "Proyección guardado correctamente", "success");
      } else {
        ver_errores(result.value);
      }      
    }
  });  
}

// ══════════════════════════════════════ S U B   D E T A L L E   P R O Y E C I O N E S ══════════════════════════════════════ 

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  $("#form-proyecciones").validate({
    rules: {
      fecha_p:      { required: true},
      descripcion_p:{required: true, minlength: 3 },
    },
    messages: {
      fecha_p:      { required: "Campo requerido."  },
      descripcion_p:{ required: "Campo requerido.", minlength: "MINIMO 3 caracteres.",   },
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
      guardar_y_editar_proyecciones(e); 
    },
  });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function html_table_to_excel(name_id_tabla, type = 'xlsx', name_file = 'detalle excel', name_hoja = 'hoja 1')  {
  var data = document.getElementById(name_id_tabla);

  var file = XLSX.utils.table_to_book(data, {sheet: name_hoja});

  XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

  XLSX.writeFile(file, name_file + '.' + type);
}

function show_hide_tr(tr, sub_tr) {
  console.log($(tr).attr("aria-expanded"));
  if ($(tr).attr("aria-expanded") == 'true') {
    $(sub_tr).show();
  }else{
    $(sub_tr).hide();
  }  
}

function add_tr_detalle(id_all, count) {
  // eliminamos el mensaje VACIO
  // extraemos la cantidad de SUB-DETALLES
  var cant = $(`.data_${id_all}`).toArray().length; console.log(cant);
  // si es 0 VACIOAMOS EL BODY
  if (cant == 0) {  $(`.tbody_proyeccion`).html('') }

  // AGREGAMOS EL DETALLE --
  $(`.tbody_proyeccion`).append(`
  <tr class="data_${id_all} data_bloque_${count + 1} detalle_tr_${count + 1} sub_${count + 1}_0 0 ultimo_${count + 1}">
    <td class="py-1 text-center detalle_td_num_${count + 1}" data-widget="expandable-table" aria-expanded="true" onclick="delay(function(){show_hide_tr('.detalle_td_num_${count + 1}','.sub_detalle_tr_${count + 1}')}, 200 );">${count + 1}</td>
    <td class="py-1">
      <span class="span_p_${id_all}"></span> 
      <input type="text" id="" class="hidden input_p_${id_all} input_n_dp_${id_all}_${count + 1} w-100" value="">
    </td>
    <td class="py-1"> </td>                           
    <td class="py-1">
      <div class="formato-numero-conta span_p_${id_all}">
        <span>S/</span> <span >0.00</span> 
      </div> 
      <input type="text" id="" class="numberIndistintoFixed hidden input_p_${id_all} input_dp_${id_all}_${count + 1} w-100" value="0.00" onkeyup="delay(function(){calc_total_proyeccion(${id_all}, ${count + 1})}, 100 );" onfocus="this.select();">
    </td> 
    <td class="py-1">
      <button type="button" class="btn btn-xs bg-gradient-success detalle_btn_${count + 1} " onclick="add_tr_sub_detalle(${id_all}, ${count + 1}, 0)" data-toggle="tooltip" data-original-title="Agregar Item" ><i class="fas fa-plus"></i> </button>
      <button type="button" class="btn btn-xs bg-gradient-danger btn-delete-sdp" onclick="remove_tr_detalle(${id_all}, ${count + 1},0)" data-toggle="tooltip" data-original-title="Eliminar Item" ><i class="far fa-trash-alt"></i> </button>
      <input type="hidden" name="" id="cant_sub_detalle_${id_all}_${count + 1}">
    </td>
  </tr>
  <!-- /.tr -->
  `);
  $(`.btn_th_${id_all}`).attr('onclick', `add_tr_detalle(${id_all}, ${count + 1})`);
  show_hide_span_input_p(2, id_all);

  // Formato miles - input
  //formato_miles_input(`.input_miles`);
  document.querySelectorAll(".numberIndistintoFixed").forEach((el) => el.addEventListener("keyup", numberFormatIndistintoFixed));
  // mensaje ok
  toastr_success('Item agregado', 'Se agrego un nueva fila', 700);
  // removemos la ultima clase ||  agregamos la catidad de SUBDETALLES
  $("#cant_detalle").val(count + 1); 

  $(".tooltip").remove();
  $('[data-toggle="tooltip"]').tooltip();
}

function add_tr_sub_detalle(id_all, id, count) {  

  $(`.sub_${id}_${count}`).after( `
  <tr class="data_bloque_${id} sub_detalle_tr_${id} sub_${id}_${count + 1} ultimo_${id}">
    <td class="py-1 text-center"></td>
    <td class="py-1 text-right">
      <span class="span_p_${id_all}"></span> 
      <input type="text" id="" class="hidden input_p_${id_all} input_n_sdp_${id_all}_${id}_${count + 1} w-75 float-right" value="">
    </td>                                                            
    <td class="py-1">
      <div class="formato-numero-conta span_p_${id_all}">
        <span>S/</span>0.00
      </div> 
      <input type="text" id="" class="numberIndistintoFixed hidden input_p_${id_all} w-100 input_sdp_${id_all}_${id}_${count + 1}" value="0.00" onkeyup="delay(function(){calc_total_proyeccion(${id_all}, ${id})}, 100 );" onfocus="this.select();">
    </td> 
    <td class="py-1"> </td> 
    <td class="py-1">
      <button type="button" class="btn btn-xs bg-gradient-danger btn-delete-sdp" onclick="remove_tr_sub_detalle(${id_all},${id}, ${count + 1})" data-toggle="tooltip" data-original-title="Eliminar Sub-Item" ><i class="far fa-trash-alt"></i> </button>
    </td>
  </tr>
  ` );

  // removemos la clase: ultimo_
  $(`.detalle_tr_${id}`).removeClass(`ultimo_${id}`);
  $(`.sub_${id}_${count}`).removeClass(`ultimo_${id}`);
  var cant = $(`.sub_detalle_tr_${id}`).toArray().length; //console.log(cant);
  if (cant > 0) {
    // Agregamos icono
    $(`.detalle_td_num_${id}`).html(`<i class="expandable-table-caret fas fa-caret-right fa-fw"></i> ${id}`).attr('aria-expanded','true');
    // agregamos readonly
    $(`.input_dp_${id_all}_${id}`).attr('readonly', 'readonly').addClass('input-no-border-center-bold');
  }

  // Formato miles - input
  //formato_miles_input(`.input_miles`);
  document.querySelectorAll(".numberIndistintoFixed").forEach((el) => el.addEventListener("keyup", numberFormatIndistintoFixed));
  // mensaje ok
  toastr_success('Sub-Item agregado', 'Se agrego un nueva fila', 700);
  // removemos la ultima clase || agregamos la catidad de SUBDETALLES
  $(`#cant_sub_detalle_${id_all}_${id}`).val(count + 1);

  // actualizamos la funcion
  $(`.detalle_btn_${id}`).attr('onclick', `add_tr_sub_detalle(${id_all},${id}, ${count + 1})`);
  show_hide_span_input_p(2, id_all);
  $(".tooltip").remove();
  $('[data-toggle="tooltip"]').tooltip();
}

function remove_tr_detalle(id_all, id, count) {
  // borramos el tr
  $(`.data_bloque_${id}`).remove();
  // extraemos la cantidad de SUB-DETALLES
  var cant = $(`.data_${id_all}`).toArray().length; console.log(cant);
  // si es 0 reiniciamos el ultimo
  if (cant == 0) {  
    $(`.btn_th_${id_all}`).attr('onclick', `add_tr_detalle(${id_all}, 0)`);
  }
  calc_total_proyeccion(id_all, id);  
  toastr_warning('Item REMOVIDO', 'Se removio la fila.', 700);
}

function remove_tr_sub_detalle( id_all, id, count) {
  // validamos si exite el ultimo tr
  if ($(`.sub_${id}_${count}`).hasClass(`ultimo_${id}`)) { $(`.detalle_btn_${id}`).attr('onclick', `add_tr_sub_detalle(${id_all},${id}, ${count-1})`); } 

  // borramos el tr
  $(`.sub_${id}_${count}`).remove();

  // extraemos la cantidad de SUB-DETALLES
  var cant = $(`.sub_detalle_tr_${id}`).toArray().length; //console.log(cant);
  // si es 0 reiniciamos el ultimo
  if (cant == 0) {  
    $(`.detalle_btn_${id}`).attr('onclick', `add_tr_sub_detalle(${id_all}, ${id}, 0)`);
    $(`.detalle_tr_${id}`).addClass(`ultimo_${id}`);
    $(`.detalle_td_num_${id}`).html(`${id}`);
    $(`.input_dp_${id_all}_${id}`).removeAttr('readonly').removeClass('input-no-border-center-bold');
  }
  calc_total_proyeccion(id_all, id);
  toastr_warning('Sub-Item REMOVIDO', 'Se removio la fila.', 700);
}

