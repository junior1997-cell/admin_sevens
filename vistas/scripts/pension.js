var tabla_comprobantes;
var tabla_pension;
var tabla_detalle_pension;

var editando=false;
var editando2=false;
////////////////////////////
var array_class=[];

var array_datosPost=[];
var array_fi_ff=[];
var f1_reload=''; var f2_reload=''; var i_reload  = ''; var cont_reload  = '';
var total_semanas=0;
var array_guardar_fi_ff = [];

var fecha_inicial_1="";
var fecha_inicial_2="";
var id_pension="";
var i_inicial="";
var cont_inial="";
//Función que se ejecuta al inicio
function init() {  

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Viaticos").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mViatico").addClass("active bg-primary");

  $("#sub_bloc_comidas").addClass("menu-open bg-color-191f24");

  $("#sub_mComidas").addClass("active bg-primary");

  $("#lPension").addClass("active");

  $("#idproyecto_p").val(localStorage.getItem('nube_idproyecto'));

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));


  tbla_principal( localStorage.getItem('nube_idproyecto')); 

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#proveedor', null);
    
  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_pension").on("click", function (e) {$("#submit-form-pension").submit();});
  $("#guardar_registro_comprobaante").on("click", function (e) {$("#submit-form-comprobante").submit();});

  
  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  //Initialize Select2 Elements
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione tipo comprobante", allowClear: true, });
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Selecione una forma de pago", allowClear: true, });  
  $("#proveedor").select2({ theme: "bootstrap4", placeholder: "Seleccionar", allowClear: true, });
  $("#servicio_p").select2();

  // Bloquemos las fechas has hoy
  no_select_tomorrow('#fecha_emision');

  // Formato para telefono
  $("[data-mask]").inputmask();  
}

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

function mostrar_form_table(estados) {
  // principal
  if (estados == 1 ) {

    $("#nomb_pension_head").html(`<i class="fas fa-utensils nav-icon"></i> Pensión`);

    $("#btn_guardar_pension").show();
    $("#btn_regresar").hide();
    $("#btn_guardar_detalle_pension").hide();
    $("#btn_guardar_comprobante").hide();
    
    $("#div-tabla-principal").show();
    $("#div-tabla-detalle").hide();
    $("#div-tabla-comprobantes").hide();

  // detalle pension
  } else if (estados == 2) {
    
    $("#btn_guardar_pension").hide();
    $("#btn_regresar").show();
    $("#btn_guardar_detalle_pension").show();
    $("#btn_guardar_comprobante").hide();

    $("#div-tabla-principal").hide();
    $("#div-tabla-detalle").show();
    $("#div-tabla-comprobantes").hide();   
  
  // pagos pension
  } else if (estados == 3) {
    $("#btn_guardar_pension").hide();
    $("#btn_regresar").show();
    $("#btn_guardar_detalle_pension").hide();
    $("#btn_guardar_comprobante").show();

    $("#div-tabla-principal").hide();
    $("#div-tabla-detalle").hide();
    $("#div-tabla-comprobantes").show();     
    
  }
}


//Función para guardar o editar

function l_m(){ $(".progress-bar").removeClass("progress-bar-striped")}


// .....:::::::::::::::::::::::::::::::::::::  P E N S I O N  :::::::::::::::::::::::::::::::::::::::..
function limpiar_pension() {
  $(".edit").html('Agregar nueva pensión')
  $("#idpension").val("");
  $("#p_desayuno").val("");
  $("#p_almuerzo").val("");
  $("#p_cena").val("");
  $("#descripcion_pension").val("");
  $("#proveedor").val("null").trigger("change"); 
  $("#servicio_p").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();

}

//Guardar y editar
function guardaryeditar_pension(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-agregar-pension")[0]);
 
  $.ajax({
    url: "../ajax/pension.php?op=guardaryeditar_pension",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);
        console.log(e); 
        if (e.status == true) {
          toastr.success('servicio registrado correctamente');  
          tabla_pension.ajax.reload(null, false);  
          $("#modal-agregar-pension").modal("hide");  
          limpiar_pension();
        }else{  
          ver_errores(e);
        } 
      } catch (err) {
        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      }     
      $("#guardar_registro_pension").html('Guardar Cambios').removeClass('disabled');         
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_pension").css({"width": percentComplete+'%'});
          $("#barra_progress_pension").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_pension").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_pension").css({ width: "0%",  });
      $("#barra_progress_pension").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_pension").css({ width: "0%", });
      $("#barra_progress_pension").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

//Función Listar
function tbla_principal(nube_idproyecto) {

  var sumatotal=0; var totalsaldo=0; 

  tabla_pension = $('#tabla-pension').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,6,7], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,6,7], } }, { extend: 'pdfHtml5', footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,4,6,7], } }, {extend: "colvis"} ,
    ],
    ajax:{
      url: '../ajax/pension.php?op=tabla_principal&nube_idproyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center text-nowrap');  }
      if (data[1] != '') { $("td", row).eq(1).addClass('text-center text-nowrap'); }
      if (data[3]!="") { $("td", row).eq(4).addClass('text-right');  sumatotal += parseFloat(data[4]);  } else { sumatotal +=0; }
      if (data[5]) { $("td", row).eq(5).addClass('text-nowrap'); $("td", row).eq(6).addClass('text-nowrap');  }
      if (data[7]!="") {$("td", row).eq(7).addClass('text-right');}
      //console.log(data);
      if (quitar_formato_miles(data[7]) > 0) {
        $("td", row).eq(7).css({ "background-color": "#ffc107", color: "black", });          
      } else if (quitar_formato_miles(data[7]) == 0) {
        $("td", row).eq(7).css({ "background-color": "#28a745", color: "white",  });
      } else {
        $("td", row).eq(7).css({ "background-color": "#ff5252", color: "white", });          
      }
      if (data[7]!="") {  var saldo=quitar_formato_miles(data[7]); }
      totalsaldo += parseFloat(saldo);
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();

  $.post("../ajax/pension.php?op=total_pension", { idproyecto: nube_idproyecto }, function (e, status) {
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {
      $("#total_pension").html(formato_miles(e.data.total));
      $("#total_deposito").html(formato_miles(e.data.total_deposito));
      if (es_numero(e.data.total) && es_numero(e.data.total_deposito) ) {
        var saldo = e.data.total - e.data.total_deposito;
        $("#total_saldo").html(`${formato_miles(saldo)}`);
      } else {
        $("#total_saldo").html(`0`);
      }
      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );  
}

//mostrar
function mostrar_pension(idpension) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_pension();
  $(".edit").html('Editar pensión')
  $("#modal-agregar-pension").modal("show");

  $.post("../ajax/pension.php?op=mostrar_pension", { idpension: idpension }, function (e, status) {

    e = JSON.parse(e); console.log('jjjjjjjjjjjjjjjjjjjj'); console.log(e);   

    $("#proveedor").val(e.data.idproveedor).trigger("change"); 
    $("#idproyecto_p").val(e.data.idproyecto);
    $("#idpension").val(e.data.idpension);
    $("#descripcion_pension").val(e.data.descripcion);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

  }).fail( function(e) { ver_errores(e); } );
}
// .....:::::::::::::::::::::::::::::::::::::  D E T A L L E   P E N S I O N  :::::::::::::::::::::::::::::::::::..
function limpiar_form_detalle_pension() {

  $("#idpension").val("");
  $("#p_desayuno").val("");
  $("#p_almuerzo").val("");
  $("#p_cena").val("");
  $("#descripcion_pension").val("");
  $("#proveedor").val("null").trigger("change"); 
  $("#servicio_p").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();

}

//funcion para ingresar la fecha para rellenar los días de las pensiones
function ingresar_a_pension(idpension,idproyecto,razon_social) {
  $("#nomb_pension_head").html(`<i class="fas fa-utensils nav-icon"></i> Pensión - <b>${razon_social}</b>`);
  id_pension=idpension;
  mostrar_form_table(2); 

  tabla_detalle_pension = $('#tabla-detalle-pension').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,6,7], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,6,7], } }, { extend: 'pdfHtml5', footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,4,6,7], } }, {extend: "colvis"} ,
    ],
    ajax:{
      url: '../ajax/pension.php?op=tbla_detalle_comprobante&id_pension='+idpension,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0]) { $("td", row).eq(0).addClass('text-center text-nowrap');  }
      if (data[3]) { $("td", row).eq(3).addClass('text-nowrap');   }
      if (data[4]) {$("td", row).eq(4).addClass('text-right');} 
      if (data[5]) {$("td", row).eq(6).addClass('text-right');}      
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
      { targets: [6], visible: false, searchable: false, },   
    ],
  }).DataTable();
  
}

function mostar_editar_detalle_pension(id_detalle) {
  $("#modal-agregar-detalle-pension").modal("show");
}

// .....:::::::::::::::::::::::::::::::::::::  C O M P R O B A N T E    P E N S I O N  :::::::::::::::::::::::::::::::::::::::..

//Función limpiar-factura
function limpiar_comprobante() {
  //idpension_f,idfactura_pension
  $("#nro_comprobante").val("");
  $("#idfactura_pension").val("");
  $("#fecha_emision").val("");
  $("#descripcion").val("");

  $("#subtotal").val("");

  $("#igv").val("");

  $("#monto").val("");

  $("#val_igv").val(""); 

  $("#tipo_gravada").val("");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

    // Limpiamos las validaciones
    $(".form-control").removeClass('is-valid');
    $(".is-invalid").removeClass("error is-invalid");

}

//Guardar y editar
function guardaryeditar_factura(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-agregar-comprobante")[0]);
 
  $.ajax({
    url: "../ajax/pension.php?op=guardaryeditar_Comprobante",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);                       
        if (e.status ==true) {
          toastr.success('servicio registrado correctamente');
          tabla_comprobantes.ajax.reload(null, false);
          $("#modal-agregar-comprobante").modal("hide");
          tbla_comprobante(localStorage.getItem('idpension_f_nube'));
          total_monto(localStorage.getItem('idpension_f_nube'));
          tbla_principal( localStorage.getItem('nube_idproyecto'));
          limpiar_comprobante();
        }else{
          ver_errores(e);
        }        
      } catch (err) {
        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      }
      $("#guardar_registro_comprobaante").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_comprobante_pension").css({"width": percentComplete+'%'});
          $("#barra_progress_comprobante_pension").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_comprobaante").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_comprobante_pension").css({ width: "0%",  });
      $("#barra_progress_comprobante_pension").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_comprobante_pension").css({ width: "0%", });
      $("#barra_progress_comprobante_pension").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function tbla_comprobante(idpension, razon_social) {
  localStorage.setItem('idpension_f_nube',idpension);
  $("#nomb_pension_head").html(`<i class="fas fa-utensils nav-icon"></i> Pensión - <b>${razon_social}</b>`);
  mostrar_form_table(3)
  $("#idpension_f").val(idpension);
  
  tabla_comprobantes =$('#tabla-comprobantes').dataTable({  
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    ajax:{
      url: '../ajax/pension.php?op=tbla_comprobante&idpension='+idpension,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);		ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
      // columna: sub total
      if (data[5] != '') { $("td", row).eq(5).addClass('text-nowrap text-right'); }
      // columna: igv
      if (data[6] != '') { $("td", row).eq(6).addClass('text-nowrap text-right'); }
      // columna: total
      if (data[7] != '') { $("td", row).eq(7).addClass('text-nowrap text-right'); }
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
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
    ],
  }).DataTable();
  total_monto(localStorage.getItem('idpension_f_nube'));
}

function comprob_factura() {

  var monto = parseFloat($('#monto').val());

  if ($("#tipo_comprobante").select2("val")=="" || $("#tipo_comprobante").select2("val")==null) {
    $("#subtotal").val("");
    $("#igv").val(""); 
    $("#val_igv").val("0"); 
    $("#tipo_gravada").val("NO GRAVADA"); 
    $("#val_igv").prop("readonly",true);
  }else{
    if ($("#tipo_comprobante").select2("val") =="Factura") {
      $("#tipo_gravada").val("GRAVADA");
      calculandototales_fact();
    } else {
      if ($("#tipo_comprobante").select2("val")!="Factura") {
        $("#subtotal").val(monto.toFixed(2));
        $("#igv").val("0.00");
        $("#val_igv").val("0"); 
        $("#tipo_gravada").val("NO GRAVADA"); 
        $("#val_igv").prop("readonly",true);
      } else {
        $("#subtotal").val('0.00');
        $("#igv").val("0.00");
        $("#val_igv").val("0"); 
        $("#tipo_gravada").val("NO GRAVADA"); 
        $("#val_igv").prop("readonly",true);
      }
    }
  }  
}

function validando_igv() {
  if ($("#tipo_comprobante").select2("val") == "Factura") {
    $("#val_igv").prop("readonly",false);
    $("#val_igv").val(0.18); 
  }else {
    $("#val_igv").val(0); 
  }  
}

function calculandototales_fact() {

  var precio_parcial =  $("#monto").val();

  var val_igv = $('#val_igv').val();

  if (precio_parcial == null || precio_parcial == "") {
    $("#subtotal").val(0);
    $("#igv").val(0); 
  } else {
 
    var subtotal = 0;
    var igv = 0;

    if (val_igv == null || val_igv == "") {
      $("#subtotal").val(parseFloat(precio_parcial));
      $("#igv").val(0);
    }else{
      $("subtotal").val("");
      $("#igv").val("");

      subtotal = quitar_igv_del_precio(precio_parcial, val_igv, 'decimal');
      igv = precio_parcial - subtotal;

      $("#subtotal").val(parseFloat(subtotal).toFixed(2));
      $("#igv").val(parseFloat(igv).toFixed(2));
    }
  }
}

function quitar_igv_del_precio(precio , igv, tipo ) {
  console.log(precio , igv, tipo);
  var precio_sin_igv = 0;

  switch (tipo) {
    case 'decimal':
      if (parseFloat(precio) != NaN && igv > 0 && igv <= 1 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( ( parseFloat(igv) * 100 ) + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;

    case 'entero':
      if (parseFloat(precio) != NaN && igv > 0 && igv <= 100 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( parseFloat(igv)  + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;
  
    default:
      $(".val_igv").html('IGV (0%)');
      toastr.success('No has difinido un tipo de calculo de IGV.')
    break;
  } 
  
  return precio_sin_igv; 
}

//mostrar
function mostrar_comprobante(idfactura_pension ) {

  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();

  limpiar_comprobante();
  $("#modal-agregar-comprobante").modal("show");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/pension.php?op=mostrar_comprobante", { idfactura_pension : idfactura_pension  }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 

    if (e.status) {
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
      $("#idfactura_pension  ").val(e.data.idfactura_pension);
      $("#nro_comprobante").val(e.data.nro_comprobante);
      $("#monto").val(parseFloat(e.data.monto).toFixed(2));
      $("#fecha_emision").val(e.data.fecha_emision);
      $("#descripcion").val(e.data.descripcion);
      $("#subtotal").val(parseFloat(e.data.subtotal).toFixed(2));
      $("#igv").val(parseFloat(e.data.igv).toFixed(2));
      $("#val_igv").val(e.data.val_igv); 
      $("#tipo_gravada").val(e.data.tipo_gravada);
      $("#forma_pago").val(e.data.forma_de_pago).trigger("change");

      if (e.data.comprobante == "" || e.data.comprobante == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#doc1_nombre").html('');

        $("#doc_old_1").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_1").val(e.data.comprobante); 

        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante,'pension', 'comprobante', '100%', '210' ));       
             
      }
      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();

    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_comprobante(idfactura_pension, nombre) {

  crud_eliminar_papelera(
    "../ajax/pension.php?op=desactivar_comprobante",
    "../ajax/pension.php?op=eliminar_comprobante", 
    idfactura_pension, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ total_monto(localStorage.getItem('idpension_f_nube')); tabla_comprobantes.ajax.reload(null, false); },
    function(){ tbla_principal( localStorage.getItem('nube_idproyecto')); },
    false, 
    false,
    false
  );

}

function activar_comprobante(idfactura_pension ) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  comprobante?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/pension.php?op=activar_comprobante", { idfactura_pension : idfactura_pension  }, function (e) {

        Swal.fire("Activado!", "Comprobante ha sido activado.", "success");
        total_monto(localStorage.getItem('idpension_f_nube'));
        tabla_comprobantes.ajax.reload(null, false);
        tbla_principal(localStorage.getItem('nube_idproyecto'));
      }).fail( function(e) { ver_errores(e); } );      
    }
  });  
}

function ver_modal_comprobante(comprobante){
  var comprobante = comprobante;
  var extencion = comprobante.substr(comprobante.length - 3); // => "1"
  //console.log(extencion);
  $('#ver_fact_pdf').html('');
  $('#img-factura').attr("src", "");
  $('#modal-ver-comprobante').modal("show");

  if (extencion=='jpeg' || extencion=='jpg' || extencion=='png' || extencion=='webp') {
    $('#ver_fact_pdf').hide();
    $('#img-factura').show();
    $('#img-factura').attr("src", "../dist/docs/pension/comprobante/" +comprobante);
    $("#iddescargar").attr("href","../dist/docs/pension/comprobante/" +comprobante);
  }else{
    $('#img-factura').hide();
    $('#ver_fact_pdf').show();
    $('#ver_fact_pdf').html('<iframe src="../dist/docs/pension/comprobante/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');
    $("#iddescargar").attr("href","../dist/docs/pension/comprobante/" +comprobante);
  } 
 $(".tooltip").removeClass("show").addClass("hidde");
}

//-total Pagos
function total_monto(idpension) {
  $("#monto_total_f").html("00.0");
  $.post("../ajax/pension.php?op=total_monto", { idpension:idpension }, function (e, status) {    
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      $("#monto_total_f").html('S/ '+ formato_miles(e.data.total));
    } else {
      ver_errores(e);
    } 
  }).fail( function(e) { ver_errores(e); } );
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#proveedor").on("change", function () { $(this).trigger("blur"); });
  $("#servicio_p").on("change", function () { $(this).trigger("blur"); });

  // Aplicando la validacion del select cada vez que cambie
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });

  $("#form-agregar-pension").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      proveedor:{required: true},
    },
    messages: {
      proveedor: {
        required: "Campo requerido", 
      },
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
      guardaryeditar_pension(e);
    }

  });

  $("#form-agregar-comprobante").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      forma_pago:     {required: true},
      tipo_comprobante:{required: true},
      monto:          {required: true},
      fecha_emision:  {required: true},
      descripcion:    {minlength: 1},
      foto2_i:        {required: true},
      val_igv:        { required: true, number: true, min:0, max:1 },
    },
    messages: {
      forma_pago:     { required: "Seleccionar una forma de pago", },
      tipo_comprobante:{ required: "Seleccionar un tipo de comprobante", },
      monto:          { required: "Por favor ingresar el monto", },
      fecha_emision:  { required: "Por favor ingresar la fecha de emisión", },
      val_igv:        { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
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
      guardaryeditar_factura(e);      
    }
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#proveedor").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#servicio_p").rules("add", { required: true, messages: { required: "Campo requerido" } });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


function pintar_boton_selecionado(i) {
  localStorage.setItem('i', i); //enviamos el ID-BOTON al localStorage
  // validamos el id para pintar el boton
  if (localStorage.getItem('boton_id')) {

    let id = localStorage.getItem('boton_id'); //console.log('id-nube-boton '+id); 
    
    $("#boton-" + id).removeClass('click-boton');

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  } else {

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  }
}

//despintar_btn_select
function despintar_btn_select() {  
  if (localStorage.getItem('boton_id')) { let id = localStorage.getItem('boton_id'); $("#boton-" + id).removeClass('click-boton'); }
}


