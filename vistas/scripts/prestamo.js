var tabla_prestamos; 
var tabla_pago_prestamos
var tabla_creditos; 

//Función que se ejecuta al inicio
function init() {

  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#mContableFinanciero").addClass("active");

  $("#lPrestamo").addClass("active bg-primary");
  
  listar_tbla_principal(localStorage.getItem('nube_idproyecto'));
  $("#id_proyecto_prestamo").val(localStorage.getItem('nube_idproyecto'));
  // efectuamos SUBMIT  registro de: RECIBOS POR HONORARIOS
  $("#guardar_registro_prestamo").on("click", function (e) { $("#submit-form-prestamo").submit();  });
  $("#guardar_registro_pago_prestamo").on("click", function (e) { $("#submit-form-pago-prestamo").submit();  });

  
  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

function table_show_hide(estado) { 
  if (estado == 1) {
         
    $("#btn-regresar").hide(); 
    $("#btn-agregar").show();
    $("#btn-pagar").hide();

   //$("#guardar_registro_compras").hide();

    $("#div-tabla-prestamos").show();
    $("#div-tabla-pagos-prestamos").hide();

    // $("#div-tabla-detalle-compra-proveedor").hide();
    // $("#div-pago-compras").hide();

    // $("#formulario-agregar-compra").hide();
    // $(".nombre-title-page").html(`<i class="fas fa-hand-holding-usd"></i> Compras de Activos Fijos`);

  }  else if (estado == 2) { 

    $("#btn-regresar").show(); 
    $("#btn-agregar").hide();
    $("#btn-pagar").show();

    $("#div-tabla-prestamos").hide();
    $("#div-tabla-pagos-prestamos").show();
  }

}


// ========= ============= ================== ============

 //:::: S E C C I Ó N   D E   P R É S T A M O S ::::::

// ========= ============= ================== ============

//Función limpiar préstamos
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

//Función Listar - tabla principal listar_tbla_principal(nube_idproyecto)
function listar_tbla_principal(nube_idproyecto) {

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
//Función para guardar o editar prestamo
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

// mostramos loa datos para editar: "pagos por mes"
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
// comprobante
$("#doc1_i").click(function () { $("#doc1").trigger("click"); });
$("#doc1").change(function (e) { addImageApplication(e, $("#doc1").attr("id"), ".../dist/svg/doc_uploads.svg"); });

// Eliminamos comprobante
function doc2_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
} 

function limpiar_form_pago_prestamo() {
  $("#idpago_prestamo").val("");
  $("#fecha_pago_p").val(""); 
  $("#monto_pago_p").val(""); 
  $("#descripcion_pago_p").val(""); 
}

function listar_pagos_prestamos(idprestamo,entidad,monto,deuda) {  

  localStorage.setItem('idprestamo', idprestamo); localStorage.setItem('entidad', entidad); localStorage.setItem('monto', monto);

  table_show_hide(2);

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

     // localStorage.setItem('pago_total', e.data.pago_total)

      $(".suma_total_pago_prestamo").html('S/ '+formato_miles(e.data.pago_total));  

      //var pago_total_actual = localStorage.getItem('pago_total');

      var deuda_actual = monto-e.data.pago_total;
    
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

//Función para guardar o editar pagos prestamos

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

          tabla_prestamos.ajax.reload(null, false); listar_tbla_principal(localStorage.getItem('nube_idproyecto'));
          tabla_pago_prestamos.ajax.reload(null, false); listar_pagos_prestamos(localStorage.getItem('idprestamo'),localStorage.getItem('entidad'),localStorage.getItem('monto'));
          
          limpiar_form_pago_prestamo();

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
  limpiar_form_pago_prestamo();

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

//funciones compartidas

function eliminar(idpago_prestamo,monto,fun_ajax) { 
console.log(`../ajax/prestamo.php?op=desactivar${fun_ajax}`);
  crud_eliminar_papelera(
    `../ajax/prestamo.php?op=desactivar${fun_ajax}`,
    `../ajax/prestamo.php?op=eliminar${fun_ajax}`, 
    idpago_prestamo, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>Pago con monto de ${monto}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_prestamos.ajax.reload(null, false); listar_tbla_principal(localStorage.getItem('nube_idproyecto')); },
    function(){ tabla_pago_prestamos.ajax.reload(null, false); listar_pagos_prestamos(localStorage.getItem('idprestamo'),localStorage.getItem('entidad'),localStorage.getItem('monto')) },
    false, 
    false,
    false
  );
}

function modal_comprobante(comprobante,fecha_emision){

  $('#ver_fact_pdf').html(''); 

  $('#modal-ver-comprobante').modal("show");

  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'pago_prestamo', '','100%','300'));
  $("#iddescargar").html(`<a class="btn btn-warning btn-block btn-xs" href="../dist/docs/pago_prestamo/${comprobante}"  download="${comprobante}"  type="button"><i class="fas fa-download"></i></a>`);
  $(".view_comprobante_pago").html(`<a class="btn btn-info btn-block btn-xs" href="../dist/docs/pago_prestamo/${comprobante}" target="_blank" rel="noopener noreferrer" >  Ver completo. </a>`);

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


