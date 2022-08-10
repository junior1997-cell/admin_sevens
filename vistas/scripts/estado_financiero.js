var tabla_principal; 

//Función que se ejecuta al inicio
function init() {

  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#mContableFinanciero").addClass("active");

  $("#lEstadoFinanciero").addClass("active bg-primary");
  
  tbla_estado_financiero(localStorage.getItem('nube_idproyecto'));

  // efectuamos SUBMIT  registro de: RECIBOS POR HONORARIOS
  $("#guardar_registro_proyecciones").on("click", function (e) { $("#submit-form-proyecciones").submit();  });

  //Initialize Select2 unidad
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar una forma de pago", allowClear: true, });

  $('#fecha_p').datepicker({ format: "dd-mm-yyyy", language: "es", autoclose: true, clearBtn: true,  weekStart: 0, orientation: "bottom auto", todayBtn: true });

  formato_miles_input('.input_ef');

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

// ══════════════════════════════════════ PROYECIONES ══════════════════════════════════════ 
function show_hide_span_input_p(flag, id_span) {
  if (flag == 1) {
    // ocultamos los span
    $(`.span_p_${id_span}`).show();
    // mostramos los inputs
    $(`.input_p_${id_span}`).hide();

    // ocultamos el boton editar
    $(`.btn-editar-p-${id_span}`).show();
    // mostramos el boton guardar
    $(`.btn-guardar-p-${id_span}`).hide();
  } else if (flag == 2) {
    
    // ocultamos los span
    $(`.span_p_${id_span}`).hide();
    // mostramos los inputs
    $(`.input_p_${id_span}`).show();

    // ocultamos el boton editar
    $(`.btn-editar-p-${id_span}`).hide();
    // mostramos el boton guardar
    $(`.btn-guardar-p-${id_span}`).show();
  } 
}
//Función limpiar
function limpiar_form_proyecciones() {  

  $("#idproyeccion").val("");
  $("#fecha_proyeccion").val(""); 
  $("#caja_proyeccion").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
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

function desactivar_pago_x_mes(id) {

  var id_fechas_mes = $('#idfechas_mes_pagos_administrador_pxm').val();

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
      $.post("../ajax/pago_administrador.php?op=desactivar_pago_x_mes", { 'idpagos_x_mes_administrador': id }, function (e) {

        if (e == "ok") {
          listar_tbla_principal(localStorage.getItem('nube_idproyecto')); 
          reload_table_pagos_x_mes(id_fechas_mes);
          Swal.fire("Anulado!", "Tu registro ha sido Anulado.", "success");
        } else {
          Swal.fire("Error!", e, "error");
        }        
      });      
    }
  });  
}

function activar_pago_x_mes(id) {

  var id_fechas_mes = $('#idfechas_mes_pagos_administrador_pxm').val();

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
      $.post("../ajax/pago_administrador.php?op=activar_pago_x_mes", { 'idpagos_x_mes_administrador': id }, function (e) {

        if (e == "ok") {
          listar_tbla_principal(localStorage.getItem('nube_idproyecto')); 
          reload_table_pagos_x_mes(id_fechas_mes);
          Swal.fire("ReActivado!", "Tu registro ha sido ReActivado.", "success");
        } else {
          Swal.fire("Error!", e, "error");
        }        
      });      
    }
  });
}

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
  $(`.detalle_tbody_${id_all}`).append(`
  <tr class="data_${id_all} data_bloque_${count + 1} detalle_tr_${count + 1} sub_${count + 1}_0 ultimo_${count + 1}">
    <td class="py-1 text-center detalle_td_num_${count + 1}" data-widget="expandable-table" aria-expanded="true" onclick="delay(function(){show_hide_tr('.detalle_td_num_${count + 1}','.sub_detalle_tr_${count + 1}')}, 200 );">${count + 1}</td>
    <td class="py-1">
      <span class="span_p_1"></span> 
      <input type="text" id="" class="hidden input_p_1 w-100" value="">
    </td>
    <td class="py-1">
    </td>                           
    <td class="py-1">
      <div class="formato-numero-conta span_p_${id_all}">
        <span>S/</span> <span >100</span> 
      </div> 
      <input type="text" id="" class="hidden input_p_${id_all} w-100" value="100">
    </td> 
    <td class="py-1">
      <button type="button" class="btn btn-xs bg-gradient-success detalle_btn_${count + 1} " onclick="add_tr_sub_detalle(${id_all}, ${count + 1}, 0)" ><i class="fas fa-plus"></i> </button>
      <button type="button" class="btn btn-xs bg-gradient-danger "onclick="remove_tr_detalle(${id_all}, ${count + 1},0)" ><i class="far fa-trash-alt"></i> </button>
    </td>
  </tr>
  <!-- /.tr -->
  `);
  $(`.btn_th_${id_all}`).attr('onclick', `add_tr_detalle(${id_all}, ${count + 1})`);
  show_hide_span_input_p(2, id_all);
}

function add_tr_sub_detalle(id_all, id, count) {  

  $(`.sub_${id}_${count}`).after( `
  <tr class="data_bloque_${id} sub_detalle_tr_${id} sub_${id}_${count + 1} ultimo_${id}">
    <td class="py-1 text-center"></td>
    <td class="py-1 text-right">
      <span class="span_p_${id_all}"></span> 
      <input type="text" id="" class="hidden input_p_${id_all} w-100" value="">
    </td>                                                            
    <td class="py-1">
      <div class="formato-numero-conta span_p_${id_all}">
        <span>S/</span>0.00
      </div> 
      <input type="text" id="" class="hidden input_p_${id_all} w-100" value="0.00">
    </td> 
    <td class="py-1"> </td> 
    <td class="py-1">
      <button type="button" class="btn btn-xs bg-gradient-danger " onclick="remove_tr_sub_detalle(${id_all},${id}, ${count + 1})" ><i class="far fa-trash-alt"></i> </button>
    </td>
  </tr>
  ` );

  $(`.detalle_tr_${id}`).removeClass(`ultimo_${id}`);
  $(`.sub_${id}_${count}`).removeClass(`ultimo_${id}`);
  var cant = $(`.sub_detalle_tr_${id}`).toArray().length; console.log(cant);
  if (cant > 0) {
    $(`.detalle_td_num_${id}`).html(`<i class="expandable-table-caret fas fa-caret-right fa-fw"></i> ${id}`).attr('aria-expanded','true');
  }

  $(`.detalle_btn_${id}`).attr('onclick', `add_tr_sub_detalle(${id_all},${id}, ${count + 1})`);
  show_hide_span_input_p(2, id);
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
}

function remove_tr_sub_detalle( id_all, id, count) {
  // validamos si exite el ultimo tr
  if ($(`.sub_${id}_${count}`).hasClass(`ultimo_${id}`)) { $(`.detalle_btn_${id}`).attr('onclick', `add_tr_sub_detalle(${id_all},${id}, ${count-1})`); } 

  // borramos el tr
  $(`.sub_${id}_${count}`).remove();

  // extraemos la cantidad de SUB-DETALLES
  var cant = $(`.sub_detalle_tr_${id}`).toArray().length; console.log(cant);
  // si es 0 reiniciamos el ultimo
  if (cant == 0) {  
    $(`.detalle_btn_${id}`).attr('onclick', `add_tr_sub_detalle(${id_all}, ${id}, 0)`);
    $(`.detalle_tr_${id}`).addClass(`ultimo_${id}`);
    $(`.detalle_td_num_${id}`).html(`${id}`);
  }
}