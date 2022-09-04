var tabla_grupo;  
var tabla_tierra;

var id_proyecto_r = '', idtipo_tierra_r = '', columna_bombeado_r = '', nombre_item_r = '', fecha_1_r = '', fecha_2_r = '', id_proveedor_r = '', comprobante_r = '';

function init() {
  
  //Activamos el "aside"
  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lMovientoTierras").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  tbla_principal_grupo(localStorage.getItem("nube_idproyecto"));
  listar_de_grupo(localStorage.getItem("nube_idproyecto"));

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


function limpiar_form_grupo() {

  $("#guardar_registro_item").html('Guardar Cambios').removeClass('disabled');

  $("#idtipo_tierra").val("");
  $("#nombre_item").val("");
  $("#descripcion_item").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function tbla_principal_grupo(nube_idproyecto) {

  tabla_grupo = $("#tabla_item").dataTable({
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
      url: `../ajax/movimiento_tierra.php?op=tbla_principal_grupo&id_proyecto=${nube_idproyecto}`,
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

function guardar_y_editar_grupo(e) {

  var formData = new FormData($("#form-item")[0]);

  $.ajax({
    url: "../ajax/movimiento_tierra.php?op=guardar_y_editar_grupo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Registro guardado correctamente", "success");

          tabla_grupo.ajax.reload(null, false);

          limpiar_form_grupo();

          $("#modal-agregar-items").modal("hide");
          listar_de_grupo(localStorage.getItem("nube_idproyecto"));
          
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

function mostrar_grupo(idtipo_tierra) {

  limpiar_form_grupo(); 

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-items").modal("show");

  $.post("../ajax/movimiento_tierra.php?op=mostrar_grupo", { 'idtipo_tierra': idtipo_tierra }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {

      $("#idtipo_tierra").val(e.data.idtipo_tierra_concreto);      
      $("#modulo").val(e.data.modulo);
      $("#nombre_item").val(e.data.nombre);
      $("#descripcion_item").val(e.data.descripcion);            

      $('.jq_image_zoom').zoom({ on:'grab' });
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function eliminar_grupo(idtipo_tierra, nombre) {

  crud_eliminar_papelera(
    "../ajax/movimiento_tierra.php?op=desactivar_grupo",
    "../ajax/movimiento_tierra.php?op=eliminar_grupo", 
    idtipo_tierra, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_grupo.ajax.reload(null, false);listar_de_grupo(localStorage.getItem("nube_idproyecto")); },
    false, 
    false, 
    false,
    false
  );
}

//-----------------------------------------------------------------------------------------
//----------------------------------- Tabs -----------------------------------------------
//-----------------------------------------------------------------------------------------

function listar_de_grupo(proyecto_nube) {

  $.post("../ajax/movimiento_tierra.php?op=listar_de_grupo", { 'proyecto_nube': proyecto_nube }, function (e, status) {

    e = JSON.parse(e); console.log(e);

    if (e.status) {

      var data_html = '';

      e.data.forEach((val, index) => {

        data_html = data_html.concat(`
        <li class="nav-item">
          <a class="nav-link" onclick="tbla_principal_tierra('${proyecto_nube}', '${val.idtipo_tierra_concreto}', '${val.columna_servicio_bombeado}', '${val.nombre}', '', '', '', ''); show_hide_filtro();" id="tabs-for-tierra-tab" data-toggle="pill" href="#tabs-for-tierra" role="tab" aria-controls="tabs-for-tierra" aria-selected="false">${val.nombre}</a>
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
//-----------------------  S E C C I O N    T I E R R A  ----------------------------------
//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------

// <!-- idmovimiento_tierra,idtipo_tierra,fecha,fechanombre_dia,cantidad,precio_unitario,total,descripcion_tierra -->

function limpiar_form_tierra() { 

  $("#idmovimiento_tierra").val("");
  $("#fecha").val("");
  $("#nombre_dia").val("");
  $("#cantidad").val("");
  $("#precio_unitario").val("");
  $("#total").val("");
  $("#descripcion_tierra").val("");

}

function tbla_principal_tierra(id_proyecto, idtipo_tierra, columna_bombeado, nombre_item, fecha_1, fecha_2, id_proveedor, comprobante) {

  id_proyecto_r = id_proyecto; idtipo_tierra_r = idtipo_tierra; columna_bombeado_r = columna_bombeado;  nombre_item_r = nombre_item; 
  fecha_1_r = fecha_1; fecha_2_r = fecha_2; id_proveedor_r = id_proveedor; comprobante_r = comprobante;
  
  var bombeado_columna = columna_bombeado=='1' ?  { targets: [7], visible: true, searchable: true, }: { targets: [7], visible: false, searchable: false, } ;
  var bombeado_export = columna_bombeado=='1' ?  [0,2,3,4,5,6,7,8,9,10]: [0,2,3,4,5,6,8,9,10] ;
  
  $('.modal-title-detalle-items').html(nombre_item);
  $("#idtipo_tierra_c").val(idtipo_tierra);

  limpiar_form_tierra();

  var cantidad = 0, subtotal = 0, bombeado = 0, descuento = 0, total_compra = 0;

  $(".total_concreto_cantidad").html('0.00');  
  $(".total_concreto_subtotal").html('0.00');  
  $(".total_concreto_bombeado").html('0.00');
  $(".total_concreto_descuento").html('0.00');
  $(".total_concreto").html('0.00');

  tabla_tierra = $("#tabla-tierra").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: bombeado_export },title: removeCaracterEspecial(nombre_item), }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: bombeado_export },title: removeCaracterEspecial(nombre_item),}, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: bombeado_export }, title: removeCaracterEspecial(nombre_item), orientation: 'landscape', pageSize: 'LEGAL', },       
    ],
    ajax: {
      url: `../ajax/movimiento_tierra.php?op=tbla_principal_tierra&id_proyecto=${id_proyecto}&idtipo_tierra=${idtipo_tierra}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
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
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center text-nowrap"); }
      // columna: cantidad 
      if (data[5] != '') { $("td", row).eq(5).addClass("text-center"); $(".total_concreto_cantidad").html( formato_miles( cantidad += parseFloat(data[5]) ) ); }
      // columna: subtotal
      if (data[6] != '') { $(".total_concreto_subtotal").html(formato_miles( subtotal += parseFloat(data[6]) ) ); }
      // columna: bombeado
      if (data[7] != '') { $(".total_concreto_bombeado").html(formato_miles( bombeado += parseFloat(data[7]) ) ); }
      // columna: descuento
      if (data[8] != '') { $(".total_concreto_descuento").html(formato_miles( descuento += parseFloat(data[8]) )); }
      // columna: total compra
      if (data[9] != '') { $(".total_concreto").html(formato_miles( total_compra += parseFloat(data[9]) )); }      
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
      bombeado_columna,
      { targets: [4], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [6,7,8, 9], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },

    ],
  }).DataTable();

  $(tabla_tierra).ready(function () { 
    $('.cargando_concreto').hide(); 
    // var elementsArray = document.getElementById("reload-all");
    // elementsArray.style.display = 'none'; 
  });

}

function guardar_y_editar_tierra(e) {

  var formData = new FormData($("#form-detalle-item")[0]);

  $.ajax({
    url: "../ajax/movimiento_tierra.php?op=guardar_y_editar_tierra",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Registro guardado correctamente", "success");

          tabla_tierra.ajax.reload(null, false);

          limpiar_form_detalle_item()

          $("#modal-agregar-detalle-items").modal("hide");
          //  listar_de_grupo(localStorage.getItem("nube_idproyecto"));

           tbla_principal_tierra(id_proyecto_r, idtipo_tierra_r, columna_bombeado_r, nombre_item_r, fecha_1, fecha_2, id_proveedor, comprobante);
          
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


function mostrar_tierra(idmovimiento_tierra) {


  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();

  $("#modal-agregar-detalle-items").modal("show");
  $("#idproveedor").val("").trigger("change");

  $.post("../ajax/movimiento_tierra.php?op=mostrar_tierra", { 'idmovimiento_tierra': idmovimiento_tierra }, function (e, status) {
    
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

function eliminar_tierra(idmovimiento_tierra, nombre, fecha) {

  crud_eliminar_papelera(
    "../ajax/movimiento_tierra.php?op=desactivar_tierra",
    "../ajax/movimiento_tierra.php?op=eliminar_tierra", 
    idmovimiento_tierra, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>Registro de ${nombre} con fecha ${fecha} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_tierra.ajax.reload(null, false); tbla_principal_tierra(id_proyecto_r, idtipo_tierra_r, columna_bombeado_r, nombre_item_r, fecha_1, fecha_2, id_proveedor, comprobante); },
    false, 
    false, 
    false,
    false
  );
}


function ver_detalle_compras(idcompra_proyecto) {

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');
  $(".tooltip").remove();
  $("#modal-ver-compras").modal("show");

  $.post(`../ajax/ajax_general.php?op=detalle_compra_de_insumo&id_compra=${idcompra_proyecto}`, function (r) {
    r = JSON.parse(r);
    if (r.status == true) {
      $(".detalle_de_compra").html(r.data); 
      $("#cargando-5-fomulario").show();
      $("#cargando-6-fomulario").hide();

      $("#print_pdf_compra").removeClass('disabled');
      $("#print_pdf_compra").attr('href', `../reportes/pdf_compra_activos_fijos.php?id=${idcompra_proyecto}&op=insumo` );
      $("#excel_compra").removeClass('disabled');
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// ver imagen grande del producto agregado a la compra
function ver_img_material(img, nombre) {
  $("#ver_img_insumo_o_activo_fijo").html(doc_view_extencion(img, '', '', '100%'));
  $(".nombre-img-material").html(nombre);
  $('.jq_image_zoom').zoom({ on:'grab' });
  $("#modal-ver-img-material").modal("show");
}

function export_excel_detalle_factura() {
  $tabla = document.querySelector("#tabla_detalle_compra_de_insumo");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Detalle comprobante", //Nombre del archivo de Excel
    sheetname: "detalle factura", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.tabla_detalle_compra_de_insumo.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
}

// :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E ::::::::::::::::::::::::::

function comprobante_compras( num_orden, idcompra_proyecto, num_comprobante, proveedor, fecha) {
   
  tbla_comprobantes_compras(idcompra_proyecto, num_orden);   

  $('.titulo-comprobante-compra').html(`Comprobante: <b>${num_orden}. ${num_comprobante} - ${fecha}</b>`);
  $("#modal-tabla-comprobantes-compra").modal("show"); 
}

function tbla_comprobantes_compras(id_compra, num_orden) {
  tabla_comprobantes = $("#tabla-comprobantes-compra").dataTable({
    responsive: true, 
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [ ],
    ajax: {
      url: `../ajax/movimiento_tierra.php?op=tbla_comprobantes_compra&id_compra=${id_compra}&num_orden=${num_orden}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    }, 
    createdRow: function (row, data, ixdex) {
      // columna: 1
      if (data[3] != '') { $("td", row).eq(3).addClass("text-nowrap"); }
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
      { targets: [3], render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY hh:mm:ss a'), },
      //{ targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();
}

//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------
//----------------------------- T A B L A   R E S U M E N ---------------------------------
//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------

//Función Listar
function tbla_principal_resumen(idproyecto) {

  $('.filtros-inputs').hide();

  var cantidad = 0, descuento = 0, precio_total = 0;

  $(".total_resumen_cantidad").html('0.00');  
  $(".total_resumen_descuento").html('0.00');      
  $(".total_resumen").html('0.00');  

  tabla_resumen = $("#tabla-resumen").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,1,2,3,4,5,6], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,1,2,3,4,5,6], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,1,2,3,4,5,6], }, orientation: 'landscape', pageSize: 'LEGAL', },       
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
      // columna: cantidad
      if (data[3] != '') { $("td", row).eq(3).addClass("text-center"); $(".total_resumen_cantidad").html( formato_miles( cantidad += parseFloat(data[3]) ));   }
      // columna: descuento
      if (data[5] != '') { $(".total_resumen_descuento").html(formato_miles( descuento += parseFloat(data[5]) )); }
      // columna: total
      if (data[6] != '') { $(".total_resumen").html(formato_miles( precio_total += parseFloat(data[6]) ));    }
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
      { targets: [3], render: $.fn.dataTable.render.number( ',', '.', 2) },
      { targets: [4,5,6], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },      
      //{ targets: [11,12,13], visible: false, searchable: false, },  
    ],
  }).DataTable();

  // total_tierra_resumen(idproyecto);
}

function total_tierra_resumen(idproyecto) {

  $(".total_resumen_cantidad").html('<i class="fas fa-spinner fa-pulse"></i>');  
  $(".total_resumen_descuento").html('<i class="fas fa-spinner fa-pulse"></i>');      
  $(".total_resumen").html('<i class="fas fa-spinner fa-pulse"></i>');  

  $.post("../ajax/movimiento_tierra.php?op=total_resumen", { 'idproyecto': idproyecto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {

      // $(".total_resumen_cantidad").html( formato_miles(e.data.cantidad));  
      // $(".total_resumen_precio_unitario").html(formato_miles(e.data.precio_unitario));  
      // $(".total_resumen_descuento").html(formato_miles(e.data.descuento));       
      // $(".total_resumen").html(formato_miles(e.data.total));    

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
      guardar_y_editar_grupo(e);
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
      guardar_y_editar_tierra(e);
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

  tbla_principal_tierra(id_proyecto_r, idtipo_tierra_r, columna_bombeado_r, nombre_item_r, fecha_1, fecha_2, id_proveedor, comprobante);
  //fecha_i_r=fecha_1, fecha_f_r=fecha_2, proveedor_r, comprobante_r;
}

function show_hide_filtro() {
  $('.filtros-inputs').show();
}