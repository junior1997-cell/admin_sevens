var tabla_prestamos; 
var tabla_pago_prestamos;
var tabla_creditos; 
var tabla_pago_creditos;
var tbla_resumen_p_c;

//Función que se ejecuta al inicio
function init() {

  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#mContableFinanciero").addClass("active");

  $("#lPrestamo").addClass("active bg-primary");
  
  listar_tbla_principal_prestamo(localStorage.getItem('nube_idproyecto'));
  $("#id_proyecto_prestamo").val(localStorage.getItem('nube_idproyecto'));

  listar_tbla_principal_creditos(localStorage.getItem('nube_idproyecto'));
  $("#id_proyecto_credito").val(localStorage.getItem('nube_idproyecto'));

  tbla_resumen_prest_credit(localStorage.getItem('nube_idproyecto'));

  // ════════════════════════════ G U A R D A R   F O R M  P R E S T A M O S ═════════════════════════════
  $("#guardar_registro_prestamo").on("click", function (e) { $("#submit-form-prestamo").submit();  });
  $("#guardar_registro_pago_prestamo").on("click", function (e) { $("#submit-form-pago-prestamo").submit();  });

  // ════════════════════════════ G U A R D A R   F O R M  C R É D I T O S ═════════════════════════════
  $("#guardar_registro_credito").on("click", function (e) { $("#submit-form-credito").submit();  });
  $("#guardar_registro_pago_credito").on("click", function (e) { $("#submit-form-pago-credito").submit();  });
  
  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

// ========= ============= ================== ============
 //:::: S E C C I Ó N   D E   P R É S T A M O S ::::::
// ========= ============= ================== ============
no_select_tomorrow("#fecha_inicio_prestamo");
function restrigir_fecha_input() { restrigir_fecha_ant("#fecha_fin_prestamo",$("#fecha_inicio_prestamo").val());}

function table_show_hide_prestamos(estado) { 
  if (estado == 1) {
         
    $("#btn-regresar").hide(); 
    $("#btn-agregar").show();
    $("#btn-pagar").hide();

    $("#div-tabla-prestamos").show();
    $("#div-tabla-pagos-prestamos").hide();

  }  else if (estado == 2) { 

    $("#btn-regresar").show(); 
    $("#btn-agregar").hide();
    $("#btn-pagar").show();

    $("#div-tabla-prestamos").hide();
    $("#div-tabla-pagos-prestamos").show();
  }

} 

function limpiar_prestamos() {  

  $("#idprestamo").val("");
  $("#entidad_prestamo").val(""); 
  $("#fecha_inicio_prestamo").val(""); 
  $("#fecha_fin_prestamo").val(""); 
  $("#monto_prestamo").val(""); 
  $("#descripcion_prestamo").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function listar_tbla_principal_prestamo(nube_idproyecto) {

  $('.sueldo_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.deposito_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  tabla_prestamos=$('#tbla-prestamos').dataTable({
    "responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    "ajax":{
      url: '../ajax/prestamo.php?op=tbla_prestamos&idproyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[4] != '') { $("td", row).eq(4).css({"text-align": "right"}); }     
      // columna: sueldo mensual
      if (data[6] != '') { $("td", row).eq(6).css({ "text-align": "right" }); }      
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();


  $.post("../ajax/prestamo.php?op=mostrar_total_tbla_prestamo", { nube_idproyecto: nube_idproyecto }, function (e, status) {
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {
      
      $(".suma_total_de_monto_prestamo").html(formato_miles(e.data.total_monto_prestamos));      
      $(".suma_total_de_paagos_prestamos").html(formato_miles(e.data.total_pagos_prestamos));      
      $(".suma_total_de_deudas_prestamos").html(formato_miles(e.data.deuda));      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );  

  
}

function guardar_y_editar_prestamo(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-prestamo")[0]);

  $.ajax({
    url: "../ajax/prestamo.php?op=guardar_y_editar_prestamo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Prestamo guardado correctamente", "success");

          tabla_prestamos.ajax.reload(null, false);

          listar_tbla_principal_prestamo(localStorage.getItem('nube_idproyecto'));

          tbla_resumen_prest_credit(localStorage.getItem('nube_idproyecto'));

          limpiar_prestamos();

          $("#modal-agregar-prestamo").modal("hide");
          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_prestamo").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});
          $("#barra_progress").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_prestamo").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  });
      $("#barra_progress").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function editar_prestamo(id) {

  limpiar_prestamos();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-prestamo").modal('show');

  $.post("../ajax/prestamo.php?op=mostrar_prestamo", { 'idprestamo': id }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 

    if (e.status) {

      $("#id_proyecto_prestamo").val(e.data.idproyecto);
      $("#idprestamo").val(e.data.idprestamo);
      $("#entidad_prestamo").val(e.data.entidad); 
      $("#fecha_inicio_prestamo").val(e.data.fecha_inicio); 
      $("#fecha_fin_prestamo").val(e.data.fecha_fin); 
      $("#monto_prestamo").val(e.data.monto); 
      $("#descripcion_prestamo").val(e.data.descripcion); 

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// ========= ============= ================== ============
 //:::: S E C C I Ó N   P A G O   P R É S T A M O S ::::::
// ========= ============= ================== ============
no_select_tomorrow("#fecha_pago_p");
// comprobante
$("#doc1_i").click(function () { $("#doc1").trigger("click"); });
$("#doc1").change(function (e) { addImageApplication(e, $("#doc1").attr("id"), ".../dist/svg/doc_uploads.svg"); });


function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
} 

function limpiar_form_pago_prestamos() {
  $("#idpago_prestamo").val("");
  $("#fecha_pago_p").val(""); 
  $("#monto_pago_p").val(""); 
  $("#descripcion_pago_p").val(""); 
}

function listar_pagos_prestamos(idprestamo,entidad,monto,deuda) {  

  localStorage.setItem('idprestamo', idprestamo); localStorage.setItem('entidad_prestamo', entidad); localStorage.setItem('monto_prestamo', monto);

  table_show_hide_prestamos(2);

  $("#idprestamo_p").val(idprestamo);

  $(".estado_saldo").html(""); $(".entidad").html(""); $(".total_empres").html(""); $(".total_deuda").html(""); 

  $(".entidad").html(entidad);   $(".total_empres").html(formato_miles(monto));

  tabla_pago_prestamos=$('#tbla-pago-prestamos').dataTable({
    "responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    "ajax":{
      url: '../ajax/prestamo.php?op=listar_pagos_prestamos&idprestamo='+idprestamo,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[3] != '') { $("td", row).eq(3).css({"text-align": "right"}); }   
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();
  
  $.post("../ajax/prestamo.php?op=mostrar_total_tbla_pago_prestamo", { idprestamo: idprestamo }, function (e, status) {
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {

      $(".suma_total_pago_prestamo").html('S/ '+formato_miles(e.data.pago_total_prestamo));  

      var deuda_actual = monto-e.data.pago_total_prestamo;
    
      if (deuda_actual>0 ) {

        $('.estado_saldo').html("Tiene una deuda de : ").removeClass('text-primary').addClass('text-red'); 
        $(".total_deuda").html(formato_miles(deuda_actual)).removeClass('text-primary').addClass('text-red');
      }else{

        var mont_positivo =deuda_actual*-1;
        $(".estado_saldo").html("Tiene a favor : ").removeClass('text-red').addClass('text-primary font-weight-bold');
        $(".total_deuda").html(formato_miles(mont_positivo)).removeClass('text-red').addClass('text-primary font-weight-bold');
    
      }
      
      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );  

}

function guardar_y_editar_pago_prestamo(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-pago-prestamo")[0]);

  $.ajax({
    url: "../ajax/prestamo.php?op=guardar_y_editar_pago_prestamo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Pago guardado correctamente", "success");

          tabla_prestamos.ajax.reload(null, false); 
          listar_tbla_principal_prestamo(localStorage.getItem('nube_idproyecto'));

          tabla_pago_prestamos.ajax.reload(null, false); 

          listar_pagos_prestamos(localStorage.getItem('idprestamo'),localStorage.getItem('entidad_prestamo'),localStorage.getItem('monto_prestamo'));

          tbla_resumen_prest_credit(localStorage.getItem('nube_idproyecto'));

          limpiar_form_pago_prestamos();

          $("#modal-agregar-pagar-prestamo").modal("hide");
          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_pago_prestamo").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#div_barra_progress_pag").css({"width": percentComplete+'%'});
          $("#div_barra_progress_pag").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_pago_prestamo").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#div_barra_progress_pag").css({ width: "0%",  });
      $("#div_barra_progress_pag").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#div_barra_progress_pag").css({ width: "0%", });
      $("#div_barra_progress_pag").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function editar_pago_prest(idpago_prestamo,idprestamo,fecha,monto,descripcion,comprobante) {
  //console.log(idpago_prestamo,idprestamo,fecha,monto,descripcion,comprobante);
  console.log("ssdd");
  limpiar_form_pago_prestamos();

  $("#modal-agregar-pagar-prestamo").modal("show");
  //$("#modal-agregar-pagar-prestamo").modal("hide"); 
  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();  

  $("#idpago_prestamo").val(idpago_prestamo);
  $("#idprestamo_p").val(idprestamo);
  $("#fecha_pago_p").val(fecha); 
  $("#monto_pago_p").val(monto); 
  $("#descripcion_pago_p").val(descripcion); 

  if (comprobante == "" || comprobante == null  ) {

    $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

    $("#doc1_nombre").html('');

    $("#doc_old_1").val(""); $("#doc1").val("");

  } else {

    $("#doc_old_1").val(comprobante); 
    $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(comprobante)}</i></div></div>`);
    // cargamos la imagen adecuada par el archivo
    $("#doc1_ver").html(doc_view_extencion(comprobante, 'pago_prestamo', '', '100%'));      
        
  }

  $("#cargando-3-fomulario").show();
  $("#cargando-4-fomulario").hide();


}

function eliminar_pago_prestamos(idpago,monto,fun_ajax) { 
  console.log(idpago,monto,fun_ajax);
  console.log(`../ajax/prestamo.php?op=desactivar${fun_ajax}`);
  crud_eliminar_papelera(
    `../ajax/prestamo.php?op=desactivar${fun_ajax}`,
    `../ajax/prestamo.php?op=eliminar${fun_ajax}`, 
    idpago, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>Pago con monto de ${monto}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_prestamos.ajax.reload(null, false); listar_tbla_principal_prestamo(localStorage.getItem('nube_idproyecto')); },
    function(){ if (tabla_pago_prestamos) { tabla_pago_prestamos.ajax.reload(null, false);}; listar_pagos_prestamos(localStorage.getItem('idcredito'),localStorage.getItem('entidad_prestamo'),localStorage.getItem('monto_prestamo')); },
    function(){ tbla_resumen_prest_credit(localStorage.getItem('nube_idproyecto'));},
    false,
    false
  );
}

//--------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------


// ========= ============= ================== ============
 //:::: S E C C I Ó N    C R É D I  T O S ::::::
// ========= ============= ================== ============ 
no_select_tomorrow("#fecha_inicio_credito");
function fecha_input_credito() { restrigir_fecha_ant("#fecha_fin_credito",$("#fecha_inicio_credito").val()); }

function table_show_hide_creditos(estado) { 
  if (estado == 1) {
         
    $("#btn-regresar-credito").hide(); 
    $("#btn-agregar-credito").show();
    $("#btn-pagar-credito").hide();

    $("#div-tabla-creditos").show();
    $("#div-tabla-pagos-creditos").hide();

  }  else if (estado == 2) { 

    $("#btn-regresar-credito").show(); 
    $("#btn-agregar-credito").hide();
    $("#btn-pagar-credito").show();

    $("#div-tabla-creditos").hide();
    $("#div-tabla-pagos-creditos").show();
  }

} 

function limpiar_creditos() {  

  $("#idcredito").val("");
  $("#entidad_credito").val(""); 
  $("#fecha_inicio_credito").val(""); 
  $("#fecha_fin_credito").val(""); 
  $("#monto_credito").val(""); 
  $("#descripcion_credito").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function listar_tbla_principal_creditos(nube_idproyecto) {

  // $('.sueldo_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  // $('.deposito_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  tabla_creditos=$('#tbla-creditos').dataTable({
    "responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    "ajax":{
      url: '../ajax/prestamo.php?op=tbla_creditos&idproyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[4] != '') { $("td", row).eq(4).css({"text-align": "right"}); }     
      // columna: sueldo mensual
      if (data[6] != '') { $("td", row).eq(6).css({ "text-align": "right" }); }      
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();


  $.post("../ajax/prestamo.php?op=mostrar_total_tbla_credito", { nube_idproyecto: nube_idproyecto }, function (e, status) {
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {
      
      $(".suma_total_de_monto_creditos").html(formato_miles(e.data.total_monto_creditos));      
      $(".suma_total_de_paagos_creditos").html(formato_miles(e.data.total_pagos_creditos));      
      $(".suma_total_de_deudas_creditos").html(formato_miles(e.data.deuda));      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );  

  
}

function guardar_y_editar_credito(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-credito")[0]);

  $.ajax({
    url: "../ajax/prestamo.php?op=guardar_y_editar_credito",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Crédito guardado correctamente", "success");

          tabla_creditos.ajax.reload(null, false);
          listar_tbla_principal_creditos(localStorage.getItem('nube_idproyecto'));
          tbla_resumen_prest_credit(localStorage.getItem('nube_idproyecto'));
          limpiar_creditos();

          $("#modal-agregar-credito").modal("hide");
          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_credito").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});
          $("#barra_progress").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_credito").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  });
      $("#barra_progress").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function editar_credito(id) {

  limpiar_creditos();

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#modal-agregar-credito").modal('show');

  $.post("../ajax/prestamo.php?op=mostrar_credito", { 'idcredito': id }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 

    if (e.status) {

      $("#id_proyecto_credito").val(e.data.idproyecto);
      $("#idcredito").val(e.data.idcredito);
      $("#entidad_credito").val(e.data.entidad); 
      $("#fecha_inicio_credito").val(e.data.fecha_inicio); 
      $("#fecha_fin_credito").val(e.data.fecha_fin); 
      $("#monto_credito").val(e.data.monto); 
      $("#descripcion_credito").val(e.data.descripcion); 

      $("#cargando-5-fomulario").show();
      $("#cargando-6-fomulario").hide();

    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// ========= ============= ================== ============
 //:::: S E C C I Ó N   P A G O   C R É D I  T O S ::::::
// ========= ============= ================== ============ 
no_select_tomorrow("#fecha_pago_c");
$("#doc2_i").click(function () { $("#doc2").trigger("click"); });
$("#doc2").change(function (e) { addImageApplication(e, $("#doc2").attr("id"), ".../dist/svg/doc_uploads.svg"); });

// Eliminamos comprobante
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
} 

function limpiar_form_pago_creditos() {
  $("#idpago_credito").val("");
  $("#fecha_pago_c").val(""); 
  $("#monto_pago_c").val(""); 
  $("#descripcion_pago_c").val(""); 
}

function listar_pagos_creditos(idcredito,entidad,monto,deuda) {  

  localStorage.setItem('idcredito', idcredito); localStorage.setItem('entidad_credito', entidad); localStorage.setItem('monto_credito', monto);

  table_show_hide_creditos(2);

  $("#idcredito_c").val(idcredito);

  $(".estado_saldo_credito").html(""); $(".entidad_credito").html(""); $(".total_empres_credito").html(""); $(".total_deuda_credito").html(""); 

  $(".entidad_credito").html(entidad);   $(".total_empres_credito").html(formato_miles(monto));

  tabla_pago_creditos=$('#tbla-pago-creditos').dataTable({
    "responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    "ajax":{
      url: '../ajax/prestamo.php?op=listar_pagos_creditos&idcredito='+idcredito,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[3] != '') { $("td", row).eq(3).css({"text-align": "right"}); }   
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();
  
  $.post("../ajax/prestamo.php?op=mostrar_total_tbla_pago_credito", { idcredito: idcredito }, function (e, status) {
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {

      $(".suma_total_pago_credito").html('S/ '+formato_miles(e.data.pago_total_credito));  

      var deuda_actual = monto-e.data.pago_total_credito;
    
      if (deuda_actual>0 ) {

        $('.estado_saldo_credito').html("Tiene una deuda de : ").removeClass('text-primary').addClass('text-red'); 
        $(".total_deuda_credito").html(formato_miles(deuda_actual)).removeClass('text-primary').addClass('text-red');
      }else{

        var mont_positivo =deuda_actual*-1;
        $(".estado_saldo_credito").html("Tiene a favor : ").removeClass('text-red').addClass('text-primary font-weight-bold');
        $(".total_deuda_credito").html(formato_miles(mont_positivo)).removeClass('text-red').addClass('text-primary font-weight-bold');
    
      }
      
      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );  

}

function guardar_y_editar_pago_credito(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-pago-credito")[0]);

  $.ajax({
    url: "../ajax/prestamo.php?op=guardar_y_editar_pago_credito",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Pago guardado correctamente", "success");

          tabla_creditos.ajax.reload(null, false); listar_tbla_principal_creditos(localStorage.getItem('nube_idproyecto'));
          tabla_pago_creditos.ajax.reload(null, false); listar_pagos_creditos(localStorage.getItem('idcredito'),localStorage.getItem('entidad_credito'),localStorage.getItem('monto_credito'));
          tbla_resumen_prest_credit(localStorage.getItem('nube_idproyecto'));
          limpiar_form_pago_creditos();

          $("#modal-agregar-pagar-credito").modal("hide");
          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_pago_credito").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#div_barra_progress_pag").css({"width": percentComplete+'%'});
          $("#div_barra_progress_pag").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_pago_credito").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#div_barra_progress_pag").css({ width: "0%",  });
      $("#div_barra_progress_pag").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#div_barra_progress_pag").css({ width: "0%", });
      $("#div_barra_progress_pag").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function editar_pago_prest(idpago_credito,idcredito,fecha,monto,descripcion,comprobante) {
  //console.log(idpago_credito,idcredito,fecha,monto,descripcion,comprobante);
  console.log("ssdd");
  limpiar_form_pago_creditos();

  $("#modal-agregar-pagar-credito").modal("show");
  //$("#modal-agregar-pagar-credito").modal("hide"); 
  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();  

  $("#idpago_credito").val(idpago_credito);
  $("#idcredito_c").val(idcredito);
  $("#fecha_pago_c").val(fecha); 
  $("#monto_pago_c").val(monto); 
  $("#descripcion_pago_c").val(descripcion); 

  if (comprobante == "" || comprobante == null  ) {

    $("#doc2_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

    $("#doc2_nombre").html('');

    $("#doc_old_2").val(""); $("#doc1").val("");

  } else {

    $("#doc_old_2").val(comprobante); 
    $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(comprobante)}</i></div></div>`);
    // cargamos la imagen adecuada par el archivo
    $("#doc2_ver").html(doc_view_extencion(comprobante, 'pago_credito', '', '100%'));      
        
  }

  $("#cargando-7-fomulario").show();
  $("#cargando-8-fomulario").hide();


}

function eliminar_pago_creditos(idpago,monto,fun_ajax) { 
  console.log(idpago,monto,fun_ajax);
  console.log(`../ajax/prestamo.php?op=desactivar${fun_ajax}`);
  crud_eliminar_papelera(
    `../ajax/prestamo.php?op=desactivar${fun_ajax}`,
    `../ajax/prestamo.php?op=eliminar${fun_ajax}`, 
    idpago, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>Pago con monto de ${monto}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_creditos.ajax.reload(null, false); listar_tbla_principal_creditos(localStorage.getItem('nube_idproyecto')); },
    function(){ if (tabla_pago_creditos) { tabla_pago_creditos.ajax.reload(null, false);}; listar_pagos_creditos(localStorage.getItem('idcredito'),localStorage.getItem('entidad_credito'),localStorage.getItem('monto_credito')); },
    function(){ tbla_resumen_prest_credit(localStorage.getItem('nube_idproyecto'));},
    false,
    false,
  );
}

//----------------eliminar_prestamo_credito------------
function eliminar_prestamo_credito(id,entidad,ajax_pres_cred) { 
  console.log(`ajax/prestamo.php?op=eliminar${ajax_pres_cred}`);
  crud_eliminar_papelera(
    `../ajax/prestamo.php?op=desactivar${ajax_pres_cred}`,
    `../ajax/prestamo.php?op=eliminar${ajax_pres_cred}`, 
    id, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${entidad}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_prestamos.ajax.reload(null, false); listar_tbla_principal_prestamo(localStorage.getItem('nube_idproyecto')); },
    function(){ tabla_creditos.ajax.reload(null, false); listar_tbla_principal_creditos(localStorage.getItem('nube_idproyecto')); },
    function(){ tbla_resumen_prest_credit(localStorage.getItem('nube_idproyecto'));},
    false,
    false
  );
}

//funciones compartidas

function modal_comprobante(comprobante,carpeta){

  $('#ver_fact_pdf').html(''); 

  $('#modal-ver-comprobante').modal("show");

  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, carpeta, '','100%','300'));
  $("#iddescargar").html(`<a class="btn btn-warning btn-block btn-xs" href="../dist/docs/${carpeta}/${comprobante}"  download="${comprobante}"  type="button"><i class="fas fa-download"></i></a>`);
  $(".view_comprobante_pago").html(`<a class="btn btn-info btn-block btn-xs" href="../dist/docs/${carpeta}/${comprobante}" target="_blank" rel="noopener noreferrer" >  Ver completo. </a>`);

}

function tbla_resumen_prest_credit(nube_idproyecto) { 

  $.post("../ajax/prestamo.php?op=tbla_resumen_prest_credit", { nube_idproyecto: nube_idproyecto }, function (e, status) {
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {   
      
      $(".total_prestamo").html( 'S/ '+formato_miles( e.data.total_prestamo));
      $(".pago_prestamo").html( 'S/ '+formato_miles( e.data.total_deposito_prestamo));    
      $(".deuda_prestamo").html( 'S/ '+formato_miles( e.data.deuda_prestamo));
  
      $(".total_credito").html( 'S/ '+formato_miles( e.data.total_credito));     
      $(".pago_credito").html( 'S/ '+formato_miles( e.data.total_deposito_credito));    
      $(".deuda_credito").html( 'S/ '+formato_miles( e.data.deuda_credito));
  
      $(".monto_total_prestamo").html( 'S/ '+formato_miles( e.data.monto_total_prestamo_credito));
      $(".monto_total_pago").html( 'S/ '+formato_miles( e.data.monto_total_deposito ));
      $(".monto_total_deuda").html( 'S/ '+formato_miles( e.data.monto_total_deuda)); 
      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );  
 
}

function l_m(){
  
  // limpiar();
  $("#barra_progress").css({"width":'0%'});
  $("#barra_progress").text("0%"); 
} 

init();

$(function () {

  $("#form-prestamo").validate({
    ignore: '.select2-input, .select2-focusser',

    rules: {
      entidad_prestamo: { required: true},
      fecha_inicio_prestamo: { required: true},
      fecha_fin_prestamo: { required: true},
      monto_prestamo: {required: true, minlength: 1 },
      descripcion: { minlength: 4 },
    },
    messages: {
      entidad_prestamo: { required: "Campo requerido." },
      fecha_inicio_prestamo: { required: "Campo requerido." },
      fecha_fin_prestamo: { required: "Campo requerido." },
      monto_prestamo: {required: "Campo requerido.", minlength: "MINIMO 1 dígito.", },
      descripcion: { minlength: "MINIMO 4 caracteres.", },
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
      guardar_y_editar_prestamo(e);    
    }
  });

  $("#form-pago-prestamo").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      fecha_pago_p:{required: true},
      monto_pago_p:{required: true},
    },
    messages: {
      fecha_pago_p: {required: "Campo requerido",},
      monto_pago_p: {required: "Campo requerido",},
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
      guardar_y_editar_pago_prestamo(e);    
    }
  });

  //------------credito-------------

  $("#form-credito").validate({
    ignore: '.select2-input, .select2-focusser',
  
    rules: {
      entidad_credito: { required: true},
      fecha_inicio_credito: { required: true},
      fecha_fin_credito: { required: true},
      monto_credito: {required: true, minlength: 1 },
      descripcion: { minlength: 4 },
    },
    messages: {
      entidad_credito: { required: "Campo requerido." },
      fecha_inicio_credito: { required: "Campo requerido." },
      fecha_fin_credito: { required: "Campo requerido." },
      monto_credito: {required: "Campo requerido.", minlength: "MINIMO 1 dígito.", },
      descripcion: { minlength: "MINIMO 4 caracteres.", },
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
      guardar_y_editar_credito(e);    
    }
  });

  $("#form-pago-credito").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      fecha_pago_c:{required: true},
      monto_pago_c:{required: true},
    },
    messages: {
      fecha_pago_c: {required: "Campo requerido",},
      monto_pago_c: {required: "Campo requerido",},
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
      guardar_y_editar_pago_credito(e);    
    }
  });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


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


// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {

  let splits = fecha.split("-"); //console.log(splits);

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}

// convierte de una fecha(aa-mm-dd): 23-12-2021 a una fecha(dd-mm-aa): 2021-12-23
function format_a_m_d(fecha) {

  let splits = fecha.split("-"); //console.log(splits);

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}


