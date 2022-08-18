var tabla;  
var tabla_detalle_items;

var id_proyecto_r="",idtipo_tierra_r="",nombre_item_r="",fecha_i_r="",fecha_f_r="",proveedor_r="",comprobante_r="";

function init() {
  
  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lMovientoTierras").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  tbla_principal_item(localStorage.getItem("nube_idproyecto"));
  listar_items(localStorage.getItem("nube_idproyecto"));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#filtro_proveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_item").on("click", function (e) { $("#submit-form-item").submit(); });
  $("#guardar_registro_detalle_item").on("click", function (e) { $("#submit-form-detalle-item").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });

  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
 
  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Seleccionar", allowClear: true, });

  $('.jq_image_zoom').zoom({ on:'grab' });
  // Formato para telefono
  $("[data-mask]").inputmask();
}

$('.click-btn-fecha-inicio').on('click', function (e) {$('#filtro_fecha_inicio').focus().select(); });
$('.click-btn-fecha-fin').on('click', function (e) {$('#filtro_fecha_fin').focus().select(); });


function limpiar_form_item() {

  $("#guardar_registro_item").html('Guardar Cambios').removeClass('disabled');

  $("#idtipo_tierra").val("");
  $("#nombre").val("");
  $("#descripcion").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function tbla_principal_item(nube_idproyecto) {

  tabla = $("#tabla_item").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,1,2,3], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,1,2,3], } }, 
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,1,2,3], } }, 
    ],
    ajax: {
      url: `../ajax/movimiento_tierra.php?op=tbla_principal&proyecto=${nube_idproyecto}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {    
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
      // { targets: [11,12,13], visible: false, searchable: false, },  
    ],
  }).DataTable();
}

function guardaryeditar(e) {

  var formData = new FormData($("#form-item")[0]);

  $.ajax({
    url: "../ajax/movimiento_tierra.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Registro guardado correctamente", "success");

          tabla.ajax.reload(null, false);

          limpiar_form_item();

          $("#modal-agregar-items").modal("hide");
          listar_items(localStorage.getItem("nube_idproyecto"));
          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_item").html('Guardar Cambios').removeClass('disabled');
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
      $("#guardar_registro_item").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
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

function mostrar(idtipo_tierra) {

  limpiar_form_item(); 

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-items").modal("show");

  $.post("../ajax/movimiento_tierra.php?op=mostrar", { 'idtipo_tierra': idtipo_tierra }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {

      $("#idtipo_tierra").val(e.data.idtipo_tierra);
      $("#idproyecto").val(e.data.idproyecto );
      $("#modulo").val(e.data.modulo);
      $("#nombre").val(e.data.nombre);
      $("#descripcion").val(e.data.descripcion);            

      $('.jq_image_zoom').zoom({ on:'grab' });
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function eliminar(idtipo_tierra, nombre) {

  crud_eliminar_papelera(
    "../ajax/movimiento_tierra.php?op=desactivar",
    "../ajax/movimiento_tierra.php?op=eliminar", 
    idtipo_tierra, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false);listar_items(localStorage.getItem("nube_idproyecto")); },
    false, 
    false, 
    false,
    false
  );
}

//-----------------------------------------------------------------------------------------
//----------------------------------- Tabs -----------------------------------------------
//-----------------------------------------------------------------------------------------

// , '${val.idtipo_tierra}', '${val.columna_calidad}', '${val.columna_descripcion}', '${val.nombre}'

function listar_items(proyecto_nube) {

  $.post("../ajax/movimiento_tierra.php?op=listar_items", { 'proyecto_nube': proyecto_nube }, function (e, status) {

    e = JSON.parse(e); console.log(e);

    if (e.status) {

      var data_html = '';

      e.data.forEach((val, index) => {

        data_html = data_html.concat(`
        <li class="nav-item">
          <a class="nav-link" onclick="tbla_principal_tierra('${val.idproyecto}', '${val.idtipo_tierra}', '${val.nombre}', '', '', '', ''); show_hide_filtro();" id="tabs-for-tierra-tab" data-toggle="pill" href="#tabs-for-tierra" role="tab" aria-controls="tabs-for-tierra" aria-selected="false">${val.nombre}</a>
        </li>`);
      });

      $(".lista-items").html(`
        <li class="nav-item">
          <a class="nav-link" id="tabs-for-resumen-tab" data-toggle="pill" href="#tabs-for-resumen" role="tab" aria-controls="tabs-for-resumen" aria-selected="true" onclick="tbla_principal_resumen(${localStorage.getItem('nube_idproyecto')})">Resumen</a>
        </li>
        ${data_html}
        
      `); 

      // delay(function(){$('#tabs-for-resumen-tab').click();}, 100 );
      $('#tabs-for-resumen-tab').click();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------
//----------------------- S E C C I O N  S E G Ú N  I T E M -------------------------------
//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------

// <!-- idmovimiento_tierra,idtipo_tierra,fecha,fechanombre_dia,cantidad,precio_unitario,total,descripcion_tierra -->

function limpiar_form_detalle_item() { 

  $("#idmovimiento_tierra").val("");
  $("#fecha").val("");
  $("#nombre_dia").val("");
  $("#cantidad").val("");
  $("#precio_unitario").val("");
  $("#total").val("");
  $("#descripcion_tierra").val("");

}

function tbla_principal_tierra(id_proyecto, idtipo_tierra,nombre_item,fecha_i, fecha_f, proveedor, comprobante) {

  id_proyecto_r=id_proyecto; idtipo_tierra_r=idtipo_tierra; nombre_item_r=nombre_item; fecha_i_r=fecha_i; fecha_f_r=fecha_f; proveedor_r=proveedor; comprobante_r=comprobante;

  $('.modal-title-detalle-items').html(nombre_item);
  $("#idtipo_tierra_det").val(idtipo_tierra);

  tabla_detalle_items = $("#tabla-tierra").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,7], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,7], } }, 
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,4,5,6,7], } }, 
    ],
    ajax: {
      url: `../ajax/movimiento_tierra.php?op=tbla_principal_tierra&id_proyecto=${id_proyecto}&idtipo_tierra=${idtipo_tierra}&fecha_i=${fecha_i}&fecha_f=${fecha_f}&proveedor=${proveedor}&comprobante=${comprobante}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {    
      // columna: #
      if (data[0] != "") { $("td", row).eq(0).addClass("text-center"); }
      // columna: sub acciones
      if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: sub fecha
      if (data[2] != "") { $("td", row).eq(2).addClass("text-nowrap"); }
      // columna: sub total
      if (data[4] != "") { $("td", row).eq(4).addClass("text-nowrap text-center"); }
      // columna: igv
      if (data[5] != "") { $("td", row).eq(5).addClass("text-nowrap text-right"); }
      // columna: total
      if (data[6] != "") { $("td", row).eq(6).addClass("text-nowrap text-right"); }
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
      // { targets: [11,12,13], visible: false, searchable: false, },  
    ],
  }).DataTable();

// idtipo_tierra,nombre_item,fecha_i,fecha_f,proveedor,comprobante
  $.post("../ajax/movimiento_tierra.php?op=mostrar_total_det_item", {'idtipo_tierra': idtipo_tierra,'fecha_i': fecha_i,'fecha_f': fecha_f,'proveedor': proveedor,'comprobante': comprobante }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) { 

      $("#total_cantidad").html(e.data.t_cantidad);
      $("#total_monto").html(formato_miles(e.data.total) );
      $('.cargando').hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );


}

function guardaryeditar_tierra(e) {

  var formData = new FormData($("#form-detalle-item")[0]);

  $.ajax({
    url: "../ajax/movimiento_tierra.php?op=guardaryeditar_tierra",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Registro guardado correctamente", "success");

          tabla_detalle_items.ajax.reload(null, false);

          limpiar_form_detalle_item()

          $("#modal-agregar-detalle-items").modal("hide");
          //  listar_items(localStorage.getItem("nube_idproyecto"));

           tbla_principal_tierra(id_proyecto_r, idtipo_tierra_r,nombre_item_r,fecha_i_r, fecha_f_r, proveedor_r, comprobante_r);
          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_item").html('Guardar Cambios').removeClass('disabled');
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
      $("#guardar_registro_item").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
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

function optener_dia_de_semana() {

  var nombre_dia = extraer_dia_semana_completo($("#fecha").val());
  $("#nombre_dia").val(nombre_dia);

}

function calcular_total() {

  var cantidad         = es_numero($('#cantidad').val()) == true? parseFloat($('#cantidad').val()) : 0;
  var precio_parcial   = es_numero($('#precio_unitario').val()) == true? parseFloat($('#precio_unitario').val()) : 0;

  var total = cantidad*precio_parcial;

  $("#total").val(formato_miles(total) );

}

no_select_tomorrow($("#fecha"));


function mostrar_detalle_item(idmovimiento_tierra) {

  limpiar_form_item(); 

  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();

  $("#modal-agregar-detalle-items").modal("show");
  $("#idproveedor").val("").trigger("change");

  $.post("../ajax/movimiento_tierra.php?op=mostrar_detalle_item", { 'idmovimiento_tierra': idmovimiento_tierra }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) { 

      $("#idproveedor").val(e.data.idproveedor).trigger("change");
      $("#idmovimiento_tierra").val(e.data.idmovimiento_tierra);
      $("#idtipo_tierra_det").val(e.data.idtipo_tierra);
      $("#fecha").val(e.data.fecha);
      $("#nombre_dia").val(e.data.nombre_dia);
      $("#cantidad").val(e.data.cantidad);
      $("#precio_unitario").val(e.data.precio_unitario);
      $("#total").val(formato_miles(e.data.total));
           

      $('.jq_image_zoom').zoom({ on:'grab' });
      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function eliminar_detalle_item(idmovimiento_tierra, nombre, fecha) {

  crud_eliminar_papelera(
    "../ajax/movimiento_tierra.php?op=desactivar_detalle_item",
    "../ajax/movimiento_tierra.php?op=eliminar_detalle_item", 
    idmovimiento_tierra, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>Registro de ${nombre} con fecha ${fecha} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_detalle_items.ajax.reload(null, false); tbla_principal_tierra(id_proyecto_r, idtipo_tierra_r,nombre_item_r,fecha_i_r, fecha_f_r, proveedor_r, comprobante_r); },
    false, 
    false, 
    false,
    false
  );
}

//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------
//----------------------------- T A B L A   R E S U M E N ---------------------------------
//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------

//Función Listar
function tbla_principal_resumen(idproyecto) {

  $('.filtros-inputs').hide();

  tabla_resumen = $("#tabla-resumen").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,1,2,3,4,5], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,1,2,3,4,5], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,1,2,3,4,5], }, orientation: 'landscape', pageSize: 'LEGAL', },       
    ],
    ajax: {
      url: `../ajax/movimiento_tierra.php?op=tbla_principal_resumen&idproyecto=${idproyecto}`,
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
      { targets: [3,4,5], render: $.fn.dataTable.render.number( ',', '.', 2, '<div class="formato-numero-conta"><span>S/</span>' ) },
      //{ targets: [11,12,13], visible: false, searchable: false, },  
    ],
  }).DataTable();

  total_tierra_resumen(idproyecto);
}

function total_tierra_resumen(idproyecto) {

  $(".total_cantidad_resumen").html('<i class="fas fa-spinner fa-pulse"></i>');  
  $(".total_precio_unitario_resumen").html('<i class="fas fa-spinner fa-pulse"></i>');      
  $(".total_resumen").html('<i class="fas fa-spinner fa-pulse"></i>');  

  $.post("../ajax/movimiento_tierra.php?op=total_resumen", { 'idproyecto': idproyecto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {

      $(".total_resumen_cantidad").html( formato_miles(e.data.cantidad));  
      $(".total_resumen_precio_unitario").html(formato_miles(e.data.precio_unitario));      
      $(".total_resumen").html(formato_miles(e.data.total));    

    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}


init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {   

  $('#idproveedor').on('change', function() { $(this).trigger('blur'); });
  // $('#color').on('change', function() { $(this).trigger('blur'); });

  $("#form-item").validate({
    rules: {
      nombre: { required: true },
    },
    messages: { nombre: { required: "Campo requerido.", },
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
      guardaryeditar(e);
    },
  });

  $("#form-detalle-item").validate({
    rules: {
      // <!-- idmovimiento_tierra,idtipo_tierra,fecha,fechanombre_dia,cantidad,precio_unitario,total,descripcion_tierra -->
      fecha: { required: true },
      cantidad: { required: true },
      precio_unitario: { required: true },
    },
    messages: { 
      fecha: { required: "Campo requerido.", },
      cantidad: { required: "Campo requerido.", },
      precio_unitario: { required: "Campo requerido.", },
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
      guardaryeditar_tierra(e);
    },
  });

  $('#idproveedor').rules('add', { required: true, messages: {  required: "Campo requerido" } });
  // $('#color').rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function cargando_search() {
  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
}

function filtros() {  

  var fecha_1       = $("#filtro_fecha_inicio").val();
  var fecha_2       = $("#filtro_fecha_fin").val();  
  var id_proveedor  = $("#filtro_proveedor").select2('val');
  var comprobante   = $("#filtro_tipo_comprobante").select2('val');   
  
  var nombre_proveedor = $('#filtro_proveedor').find(':selected').text();
  var nombre_comprobante = ' ─ ' + $('#filtro_tipo_comprobante').find(':selected').text();

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; } else{ fecha_1 = format_a_m_d(fecha_1) == '-'? '': format_a_m_d(fecha_1);}
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; } else{ fecha_2 = format_a_m_d(fecha_2) == '-'? '': format_a_m_d(fecha_2);} 

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == 0 || id_proveedor == null) { id_proveedor = ""; nombre_proveedor = ""; }

  // filtro de trabajdor
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_proveedor} ${nombre_comprobante}...`);

  tbla_principal_tierra(id_proyecto_r, idtipo_tierra_r,nombre_item_r, fecha_1, fecha_2, id_proveedor, comprobante);
  //fecha_i_r=fecha_1, fecha_f_r=fecha_2, proveedor_r, comprobante_r;
}

function show_hide_filtro() {
  $('.filtros-inputs').show();
}