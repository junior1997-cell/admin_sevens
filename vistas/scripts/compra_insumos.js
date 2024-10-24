//Requejo99@
var reload_detraccion = "";

var tabla_compra_insumo;
var tabla_comprobantes;
var tabla_bitacora_compra;

var tabla_compra_x_proveedor;
var tabla_detalle_compra_x_proveedor;

var tablamateriales;

var tabla_pagos1;
var tabla_pagos2;
var tabla_pagos3;

var array_doc = [];
var host = window.location.host == 'localhost'? `http://localhost/admin_sevens/dist/docs/compra_insumo/comprobante_compra/` : `${window.location.origin}/dist/docs/compra_insumo/comprobante_compra/` ;

//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mCompra").addClass("active  bg-primary");

  $("#lCompras").addClass("active");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2ProveedorFiltroCompra", '#filtro_proveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_pago', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_prov', null);
  lista_select2("../ajax/ajax_general.php?op=select2Marcas", '#marcas_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2Categoria_all", '#categoria_insumos_af_p', null);  
  lista_select2(`../ajax/compra_insumos.php?op=select2_serie_comprobante&idproyecto=${localStorage.getItem('nube_idproyecto')}`, '#slt2_serie_comprobante', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════  

  $("#guardar_registro_compras").on("click", function (e) {  $("#submit-form-compras").submit(); });
  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });
  $("#guardar_registro_pago").on("click", function (e) {  $("#submit-form-pago").submit(); });
  $("#guardar_registro_comprobante_compra").on("click", function (e) {  $("#submit-form-comprobante-compra").submit();  }); 
  $("#guardar_registro_material").on("click", function (e) {  $("#submit-form-materiales").submit(); });  
  $("#guardar_registro_marca").on("click", function(e){if($(this).hasClass('send-data')==false){$("#submit-form-marca").submit();}});

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - COMPRAS ══════════════════════════════════════

  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });
  $("#glosa").select2({templateResult: templateGlosa, theme: "bootstrap4", placeholder: "Selecione Glosa", allowClear: true, });
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione Comprobante", allowClear: true, });
  $("#slt2_serie_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecionar", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - PAGO COMPRAS ══════════════════════════════════════

  $("#banco_pago").select2({ templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Selecione una forma de pago", allowClear: true, });
  $("#tipo_pago").select2({ theme: "bootstrap4", placeholder: "Selecione un tipo de pago", allowClear: true, });

  no_select_tomorrow("#fecha_compra");

  // ══════════════════════════════════════ INITIALIZE SELECT2 - PROVEEDOR ══════════════════════════════════════

  $("#banco_prov").select2({templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });
  
  // ══════════════════════════════════════ INITIALIZE SELECT2 - MATERIAL ══════════════════════════════════════

  $("#categoria_insumos_af_p").select2({ theme: "bootstrap4", placeholder: "Seleccinar Clasificación", allowClear: true, });
  $("#marcas_p").select2({placeholder: "Seleccinar marcas"});
  $("#unidad_medida_p").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });  

  // ══════════════════════════════════════ INITIALIZE FILTROS ══════════════════════════════════════
  
  // Inicializar - Date picker  
  // $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  // $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });

  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $('#precio_unitario_p').number( true, 2 );
  $('#precio_sin_igv_p').number( true, 2 );
  $('#precio_igv_p').number( true, 2 );
  $('#precio_total_p').number( true, 2 );

  // Formato para telefono
  $("[data-mask]").inputmask();

  //filtros();
}

// $('.click-btn-fecha-inicio').on('click', function (e) {$('#filtro_fecha_inicio').focus().select(); });
// $('.click-btn-fecha-fin').on('click', function (e) {$('#filtro_fecha_fin').focus().select(); });

$('.click-btn-fecha-inicio').on('click', function (e) {$('#filtro_fecha_inicio').click(); });
$('.click-btn-fecha-fin').on('click', function (e) {$('#filtro_fecha_fin').click(); });

function templateBanco (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/banco/logo/${state.title}`: '../dist/docs/banco/logo/logo-sin-banco.svg'; 
  var onerror = `onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

function templateColor (state) {
  if (!state.id) { return state.text; }
  var color_bg = state.title != '' ? `${state.title}`: '#ffffff00';   
  var $state = $(`<span ><b style="background-color: ${color_bg}; color: ${color_bg};" class="mr-2"><i class="fas fa-square"></i><i class="fas fa-square"></i></b>${state.text}</span>`);
  return $state;
}

function templateGlosa (state) { 
  if (!state.id) { return state.text; }  console.log(state.element.attributes.icono.value );
  var $state = $(`<span ><b class="mr-2"><i class="${state.element.attributes.icono.value}"></i></b>${state.text}</span>`);
  return $state;
}

function ver_incono_glosa() {
  if ($("#glosa").val() == null || $("#glosa").val() == '' ) {  } else {
    var icon = $('#glosa').select2('data')[0].element.attributes.icono.value; console.log(icon);
    $("#select2-glosa-container").prepend(`<i class="${icon} mr-1"></i>`);
  }
}

//vaucher - pago
$("#doc3_i").click(function () { $("#doc3").trigger("click"); });
$("#doc3").change(function (e) { addImageApplication(e, $("#doc3").attr("id")); });

//comprobante - compra
$("#doc1_i").click(function () {  $("#doc1").trigger("click"); });
$("#doc1").change(function (e) { addImageApplication(e, $("#doc1").attr("id"),'', '100%', '320'); });

// Perfil - material
$("#foto2_i").click(function () {  $("#foto2").trigger("click"); });
$("#foto2").change(function (e) { addImage(e, $("#foto2").attr("id")); });

//ficha tecnica - material
$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e,$("#doc2").attr("id")) });

// Eliminamos el COMPROBANTE - PAGO
function doc3_eliminar() {
  $("#doc3").val("");
  $("#doc_old_3").val("");  
  $("#doc3_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc3_nombre").html("");
}

// Eliminamos el COMPROBANTE - COMPRA
function doc1_eliminar() {
  $("#doc1").val("");
  $("#doc_old_1").val("");
  $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc1_nombre").html("");
  $("#idfactura_compra_insumo").val("");
}

// Eliminamos el doc FOTO PERFIL - MATERIAL
function foto2_eliminar() {
  $("#foto2").val("");
  $("#ver_pdf").html("");
  $("#foto2_i").attr("src", "../dist/img/default/img_defecto_activo_fijo_material.png");
  $("#foto2_nombre").html("");
  $("#foto2_i").show();
}

// Eliminamos el doc FICHA TECNICA - MATERIAL
function doc2_eliminar() {
  $("#doc2").val("");
  $("#doc_old_2").val("");
  $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
  $("#doc2_nombre").html("");
}

// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A S :::::::::::::::::::::::::::::::::::::::::::::

//Función limpiar
function limpiar_form_compra() {
  $(".tooltip").remove();
  $('.ui-tooltip').remove();

  $("#idcompra_proyecto").val("");
  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprobante").val("Ninguno").trigger("change");
  $("#glosa").val("null").trigger("change");

  $("#slt2_serie_comprobante").val("null").trigger("change");
  $("#serie_comprobante").val("");
  $("#val_igv").val(0);
  $("#descripcion").val("");
  
  $("#total_venta").val("");  
  $(".total_venta").html("0");

  $(".subtotal_compra").html("S/ 0.00");
  $("#subtotal_compra").val("");

  $(".igv_compra").html("S/ 0.00");
  $("#igv_compra").val("");

  $(".total_venta").html("S/ 0.00");
  $("#total_venta").val("");

  $("#estado_detraccion").val("0");
  $('#my-switch_detracc').prop('checked', false); 

  $(".filas").remove();

  cont = 0;

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function table_show_hide(flag) {
  array_class_compra = [];

  if (flag == 1) {
    // tabla principal
    $("#regresar").hide();
    $("#tabla-compra").show();
    $("#tabla-compra-proveedor").show();
    $("#agregar_compras").hide();
    $("#btn_agregar").show();
    $("#div_tabla_compra").show();
    $("#div_tabla_compra_proveedor").hide();
    //----
    $("#factura_compras").hide();
    $("#btn-factura").hide();
    //-----
    $("#pago_compras").hide();
    $("#btn-pagar").hide();
  
    // $(".leyecnda_pagos").show();
    // $(".leyecnda_saldos").show();
  
    $("#monto_total").html("");
    $("#ttl_monto_pgs_detracc").html("");
    $("#pagos_con_detraccion").hide();
    limpiar_form_compra();
    limpiar_form_proveedor();
    
  } else if (flag == 2) {
    // ver editar compra insumos   
    $("#btn_agregar").hide();
    $("#regresar").show();

    $("#agregar_compras").show();
    $("#guardar_registro_compras").hide();

    $("#tabla-compra").hide();
    $("#tabla-compra-proveedor").hide(); 
    $("#div_tabla_compra").hide();
    $("#factura_compras").hide();
    listarmateriales();
  } else if (flag == 3) {
  } else if (flag == 4) {
  }
  
  
}

//TABLA - COMPRAS
function tbla_principal(nube_idproyecto, fecha_1, fecha_2, id_proveedor, comprobante) {
  //console.log(idproyecto);
  tabla_compra_insumo = $("#tabla-compra").dataTable({
    responsive: true, 
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,8], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,8], } }, 
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,4,5,6,8], } }, {extend: "colvis"} ,        
    ],
    ajax: {
      url: `../ajax/compra_insumos.php?op=tbla_principal&nube_idproyecto=${nube_idproyecto}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },     
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
      if (data[5] != '') { $("td", row).eq(5).addClass('text-center'); }
      if (data[6] != '') { $("td", row).eq(6).addClass('text-nowrap'); }
  
      if (data[9] != '') { $("td", row).eq(1).addClass('text-nowrap'); }   
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 6 ).data().reduce( function ( a, b ) { return parseFloat(a) + parseFloat(b); }, 0 );      
      $( api1.column( 6 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total1)}</span>` );      
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [6], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [8],  visible: false,  searchable: false,  },
    ],
  }).DataTable();

  $(tabla_compra_insumo).ready(function () {  $('.cargando').hide(); });

  //console.log(idproyecto);
  tabla_compra_x_proveedor = $("#tabla-compra-proveedor").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
    ajax: {
      url: "../ajax/compra_insumos.php?op=listar_compraxporvee&nube_idproyecto=" + nube_idproyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      if (data[5] != '') {
        $("td", row).eq(5).addClass('text-right');
      }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();
}

//facturas agrupadas por proveedor.
function listar_facuras_proveedor(idproveedor, idproyecto) {
  console.log('idproyecto '+idproyecto, 'idproveedor '+idproveedor);
  $("#div_tabla_compra").hide();
  $("#agregar_compras").hide();
  $("#btn_agregar").hide();
  $("#regresar").show();
  $("#div_tabla_compra_proveedor").show();

  tabla_detalle_compra_x_proveedor = $("#detalles-tabla-compra-prov").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
    ajax: {
      url: "../ajax/compra_insumos.php?op=listar_detalle_compraxporvee&idproyecto=" + idproyecto + "&idproveedor=" + idproveedor,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api = this.api(); var total = api.column( 5 ).data().reduce( function ( a, b ) { return parseFloat(a) + parseFloat(b); }, 0 )
      $( api.column( 5 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total)}</span>` );
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [5], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();
}

//Función para guardar o editar - COMPRAS
function guardar_y_editar_compras(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-compras")[0]);  

  Swal.fire({
    title: "¿Está seguro que deseas guardar esta compra?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
    preConfirm: (input) => {
      return fetch("../ajax/compra_insumos.php?op=guardar_y_editar_compra", {
        method: 'POST', // or 'PUT'
        body: formData, // data can be `string` or {object}!        
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
        Swal.fire("Correcto!", "Compra guardada correctamente", "success");

        tabla_compra_insumo.ajax.reload(null, false);
        tabla_compra_x_proveedor.ajax.reload(null, false);

        limpiar_form_compra(); table_show_hide(1);
        
        $("#modal-agregar-usuario").modal("hide");        
      } else {
        ver_errores(result.value);
      }      
    }
  });  
}

//Función para eliminar registros
function eliminar_compra(idcompra_proyecto, nombre) {

  $(".tooltip").removeClass("show").addClass("hidde");

  crud_eliminar_papelera(
    "../ajax/compra_insumos.php?op=anular",
    "../ajax/compra_insumos.php?op=eliminar_compra", 
    idcompra_proyecto, 
    "!Elija una opción¡", 
    `<b class="text-danger">${nombre}</b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu compra ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu compra ha sido Eliminado.' ) }, 
    function(){ tabla_compra_insumo.ajax.reload(null, false); tabla_compra_x_proveedor.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}

//facturas agrupadas por proveedor.
function ver_bitacora(id) {  
  $('#modal-ver-bitacora').modal('show');
  tabla_bitacora_compra = $("#tabla-bitacora").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "colvis"],
    ajax: {
      url: `../ajax/ajax_general.php?op=tabla_bitacora&nombre_tabla=compra_por_proyecto&id_tabla=${id}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      //var api = this.api(); var total = api.column( 5 ).data().reduce( function ( a, b ) { return parseFloat(a) + parseFloat(b); }, 0 )
      //$( api.column( 5 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total)}</span>` );
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      //{ targets: [5], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();
}

// .......::::::::::::::::::::::::::::::::::::::::: AGREGAR FACURAS, BOLETAS, NOTA DE VENTA, ETC :::::::::::::::::::::::::::::::::::.......

// js_compra_insumos.js --- se esta usando este archivo

//mostramos el detalle del comprobante de la compras
function ver_detalle_compras(idcompra_proyecto) {

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-compras").modal("show");

  $.post(`../ajax/ajax_general.php?op=detalle_compra_de_insumo&id_compra=${idcompra_proyecto}`, function (e) {
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      $(".detalle_de_compra").html(e.data); 
      $("#cargando-5-fomulario").show();
      $("#cargando-6-fomulario").hide();

      $("#print_pdf_compra").removeClass('disabled');    
      $("#excel_compra").removeClass('disabled').attr('href', `../reportes/export_xlsx_compra_insumo.php?id=${idcompra_proyecto}&op=insumo`);
      $("#print_pdf_compra").attr('href', `../reportes/comprobante_compra_insumo.php?id=${idcompra_proyecto}&op=insumo` );
    } else {
      ver_errores(e);
    }   
  }).fail( function(e) { ver_errores(e); } );
}

// :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E   C O M P R A  ::::::::::::::::::::::::::

function limpiar_form_comprobante() {
  $("#doc1_nombre").html("");
  $("#doc_old_1").val("");
  $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

  //$("#id_compra_proyecto").val("");
  $("#idfactura_compra_insumo").val("");
  $("#barra_progress_comprobante_div").hide();
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
      url: `../ajax/compra_insumos.php?op=tbla_comprobantes_compra&id_compra=${id_compra}&num_orden=${num_orden}`,
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

function guardaryeditar_comprobante(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-comprobante")[0]);

  $.ajax({
    url: "../ajax/compra_insumos.php?op=guardaryeditar_comprobante",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);
        if (e.status == true) {
          // reiniciamos el array para descargar
          array_doc = [];

          Swal.fire("Correcto!", "Documento guardado correctamente", "success");
          tabla_compra_insumo.ajax.reload(null, false);
          tabla_comprobantes.ajax.reload(null, false);
          limpiar_form_comprobante();          

          $("#modal-comprobantes-compra").modal("hide");
          $("#barra_progress_comprobante_div").hide();
        } else {

          ver_errores(e);
        } 
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_comprobante_compra").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener(
        "progress",
        function (evt) {
          if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            /*console.log(percentComplete + '%');*/
            $("#barra_progress_comprobante").css({ width: percentComplete + "%" });
            $("#barra_progress_comprobante").text(percentComplete.toFixed(2) + " %");
          }
        },
        false
      );
      return xhr;
    },
    beforeSend: function () {
      $("#barra_progress_comprobante_div").show();
      $("#guardar_registro_comprobante_compra").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_comprobante").css({ width: "0%",  });
      $("#barra_progress_comprobante").text("0%");
    },
    complete: function () {
      $("#barra_progress_comprobante").css({ width: "0%", });
      $("#barra_progress_comprobante").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function comprobante_compras(idcompra_proyecto, doc, num_orden, num_comprobante, proveedor, fecha) {
  limpiar_form_comprobante();
  tbla_comprobantes_compras(idcompra_proyecto, num_orden);

  $("#id_compra_proyecto").val(idcompra_proyecto);

  $('.titulo-comprobante-compra').html(`Comprobante: <b>${num_orden}. ${num_comprobante} - ${fecha}</b>`);
  $("#modal-tabla-comprobantes-compra").modal("show"); 
}

function mostrar_editar_comprobante(idcomprobante, id_compra, comprobante, nombre_comprobante) {
  limpiar_form_comprobante();
  $("#modal-comprobantes-compra").modal("show");  
  $("#idfactura_compra_insumo").val(idcomprobante);
  $("#id_compra_proyecto").val(id_compra);
  $("#doc_old_1").val(comprobante);   
  $("#doc1_ver").html(doc_view_extencion(comprobante, 'compra_insumo', 'comprobante_compra','100%', '320' ));
  $(`#doc1_ver`).append(`<div class="col-md-12 mt-2"><i> ${nombre_comprobante} </i></div><div class="col-md-12"><button class="btn btn-danger btn-block btn-xs" onclick="doc1_eliminar();" type="button" ><i class="far fa-trash-alt"></i></button></div>`);
  $('.jq_image_zoom').zoom({ on:'grab' });
}

function eliminar_comprobante_insumo(id_compra, nombre) {
  crud_eliminar_papelera(
    "../ajax/compra_insumos.php?op=desactivar_comprobante",
    "../ajax/compra_insumos.php?op=eliminar_comprobante", 
    id_compra, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_compra_insumo.ajax.reload(null, false);},
    function(){ tabla_comprobantes.ajax.reload(null, false); },
    false, 
    false,
    false
  );
}

// :::::::::::::::::::::::::: - S E C C I O N   D E S C A R G A S -  ::::::::::::::::::::::::::

function download_no_multimple(id_compra, cont, nombre_doc) {
  $(`.descarga_compra_${id_compra}`).html('<i class="fas fa-spinner fa-pulse"></i>');
  //console.log(id_compra, nombre_doc);
  var cant_download_ok = 0; var cant_download_error = 0;
  $.post("../ajax/compra_insumos.php?op=ver_comprobante_compra", { 'id_compra': id_compra }, function (e, textStatus, jqXHR) {
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      e.data.forEach((val, index) => {
        if ( UrlExists(`${host}${val.comprobante}`) == 200 ) {
          download_file(host, val.comprobante, `${cont}·${index+1} ${nombre_doc}`);
          cant_download_ok++;
        } else {
          cant_download_error++;
        }      
      });

      if (cant_download_ok == 0 && cant_download_error == 0) { toastr_error('Vacio!!', 'No hay documentos para descargar.', 700); }
      if (cant_download_ok > 0 ) { toastr_success('Exito!!', `${cant_download_ok} Descargas con exito`, 700); }
      if (cant_download_error > 0 ) { toastr_error('No existe!!', `Hay ${cant_download_error} docs que problabe que este eliminado o se haya movido el documento.`, 700); }

      $(`.descarga_compra_${id_compra}`).html('<i class="fas fa-cloud-download-alt"></i>');
    } else {
      ver_errores(e);
    } 
  }).fail( function(e) { ver_errores(e); } );
  
}

function add_remove_comprobante(id_compra, doc, factura_name) {
  
  $('.check_add_doc').addClass('hidden');
  $('.custom-control').addClass('pl-0');
  $('.cargando_check').removeClass('hidden');

  if ($(`#check_descarga_${id_compra}`).is(':checked')) {
    $.post("../ajax/compra_insumos.php?op=ver_comprobante_compra", { 'id_compra': id_compra }, function (e, textStatus, jqXHR) {
      e = JSON.parse(e); console.log(e);
      if (e.status == true) {
        var cont_docs_ok = 0; var cont_docs_error = 0;
        e.data.forEach((val, index) => {
          if (UrlExists(`${host}${val.comprobante}`) == 200) {
            array_doc.push({ 
              'id_compra': id_compra,
              'id_factura_compra': val.idfactura_compra_insumo,
              'doc_ruta': `${host}${val.comprobante}`,
            });
            cont_docs_ok++;
          } else {          
            cont_docs_error++;
          }         
        });

        if (cont_docs_ok == 0 && cont_docs_error == 0) {
          toastr_success("Vacio!!",`No hay Documentos para agregar `, 700);
        } else if (cont_docs_ok > 0) {
          toastr_success("Agregado!!",`${cont_docs_ok} Documentos agregado <p class="h5">${factura_name}</p>`, 700);
        } else if (cont_docs_error > 0) {
          toastr_error("Error!!",`${cont_docs_error} Documentos no encontrados <p class="h5">${factura_name}</p>`, 700);
          $(`#check_descarga_${id_compra}`).prop('checked', false);
        }   
        if (cont_docs_error > 0) { toastr_error("Error!!",`${cont_docs_error} Documentos no encontrados <p class="h5">${factura_name}</p>`, 700); }   
        
        $('.check_add_doc').removeClass('hidden');
        $('.custom-control').removeClass('pl-0');   
        $('.cargando_check').addClass('hidden');
      } else {
        ver_errores(e);
      }      
      console.log(array_doc);
    }).fail( function(e) { ver_errores(e); } );
    
  } else {
    $.post("../ajax/compra_insumos.php?op=ver_comprobante_compra", { 'id_compra': id_compra }, function (e, textStatus, jqXHR) {
      e = JSON.parse(e); console.log(e);
      if (e.status == true) {
        var cont_doc = 0;
        e.data.forEach((val, index) => {
          // eliminamos el indice elegido
          array_doc.forEach(function (car, index, object) {
            if (car.id_factura_compra === val.idfactura_compra_insumo) {
              object.splice(index, 1); cont_doc++;
            }
          });     
        });  
        toastr_info("Quitado!!",`${cont_doc} Documento quitado <p class="h5">${factura_name}</p>`, 700);  
        
        $('.check_add_doc').removeClass('hidden');
        $('.custom-control').removeClass('pl-0');   
        $('.cargando_check').addClass('hidden');
      } else {
        ver_errores(e);
      }      
      console.log(array_doc);   
    }).fail( function(e) { ver_errores(e); } );     
  }  
}

function download_multimple() {
  //toastr.info(`Aun estamos en desarrollo`);
  $('.btn-descarga-multiple').html('<i class="fas fa-spinner fa-pulse "></i>').addClass('disabled btn-danger').removeClass('btn-success');
  $('.btn-descarga-multiple').attr('onclick', `toastr_error('Espera!!', 'Espera la descarga que esta en curso.', 700);`);
  if (array_doc.length === 0) {
    toastr_error("Vacío!!","Selecciona algún documento", 700);
    $('.btn-descarga-multiple').html('<i class="fas fa-cloud-download-alt"></i>').removeClass('disabled btn-danger').addClass('btn-info');
    $('.btn-descarga-multiple').attr('onclick', 'download_multimple();');
  } else {
    const zip = new JSZip();  let count = 0; const zipFilename = "Comprobantes-de-insumos.zip";
    array_doc.forEach(async function (value){

      const urlArr = value.doc_ruta.split('/');
      const filename = urlArr[urlArr.length - 1];

      try {
        const file = await JSZipUtils.getBinaryContent(value.doc_ruta)
        zip.file(filename, file, { binary: true});
        count++;
        if(count === array_doc.length) {
          zip.generateAsync({type:'blob'}).then(function(content) {
            var download_zip = saveAs(content, zipFilename);
            $( download_zip ).ready(function() { toastr_success("Exito!!","Descarga exitosa", 700); });
            $('.btn-descarga-multiple').html('<i class="fas fa-cloud-download-alt"></i>').removeClass('disabled btn-danger').addClass('btn-info');
            $('.btn-descarga-multiple').attr('onclick', 'download_multimple();');
          });
        }
      } catch (err) {
        console.log(err); toastr_success("Error!!","Error al descargar", 700);
        $('.btn-descarga-multiple').html('<i class="fas fa-cloud-download-alt"></i>').removeClass('disabled btn-danger').addClass('btn-info');
        $('.btn-descarga-multiple').attr('onclick', 'download_multimple();');
      }
    });
  }  
}

// :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
//Función limpiar
function limpiar_form_proveedor() {
  $("#idproveedor_prov").val("");
  $("#tipo_documento_prov option[value='RUC']").attr("selected", true);
  $("#nombre_prov").val("");
  $("#num_documento_prov").val("");
  $("#direccion_prov").val("");
  $("#telefono_prov").val("");
  $("#c_bancaria_prov").val("");
  $("#cci_prov").val("");
  $("#c_detracciones_prov").val("");
  $("#banco_prov").val("").trigger("change");
  $("#titular_cuenta_prov").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();

  $(".tooltip").removeClass("show").addClass("hidde");
}

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco_prov").select2("val") == null || $("#banco_prov").select2("val") == "" || $("#banco_prov").select2("val") == "1" ) {

    $("#c_bancaria_prov").prop("readonly", true);
    $("#cci_prov").prop("readonly", true);
    $("#c_detracciones_prov").prop("readonly", true);

  } else {
    
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-3").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');    

    $.post("../ajax/ajax_general.php?op=formato_banco", { 'idbanco': $("#banco_prov").select2("val") }, function (e, status) {
      
      e = JSON.parse(e);  // console.log(e);

      if (e.status == true) {
        $(".chargue-format-1").html("Cuenta Bancaria");
        $(".chargue-format-2").html("CCI");
        $(".chargue-format-3").html("Cuenta Detracciones");

        $("#c_bancaria_prov").prop("readonly", false);
        $("#cci_prov").prop("readonly", false);
        $("#c_detracciones_prov").prop("readonly", false);

        var format_cta = decifrar_format_banco(e.data.formato_cta);
        var format_cci = decifrar_format_banco(e.data.formato_cci);
        var formato_detracciones = decifrar_format_banco(e.data.formato_detracciones);
        // console.log(format_cta, formato_detracciones);

        $("#c_bancaria_prov").inputmask(`${format_cta}`);
        $("#cci_prov").inputmask(`${format_cci}`);
        $("#c_detracciones_prov").inputmask(`${formato_detracciones}`);
      } else {
        ver_errores(e);
      }      
    }).fail( function(e) { ver_errores(e); } );
  }
}

function decifrar_format_banco(format) {

  var array_format =  format.split("-"); var format_final = "";

  array_format.forEach((item, index)=>{

    for (let index = 0; index < parseInt(item); index++) { format_final = format_final.concat("9"); }   

    if (parseInt(item) != 0) { format_final = format_final.concat("-"); }
  });

  var ultima_letra = format_final.slice(-1);
   
  if (ultima_letra == "-") { format_final = format_final.slice(0, (format_final.length-1)); }

  return format_final;
}

//guardar proveedor
function guardar_proveedor(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);

  $.ajax({
    url: "../ajax/compra_insumos.php?op=guardar_proveedor",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);
      try {
        if (e.status == true) {
          // toastr.success("proveedor registrado correctamente");
          Swal.fire("Correcto!", "Proveedor guardado correctamente.", "success");          
          limpiar_form_proveedor();
          $("#modal-agregar-proveedor").modal("hide");
          //Cargamos los items al select cliente
          lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', e.data);
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }       
      
      $("#guardar_registro_proveedor").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_proveedor").css({"width": percentComplete+'%'});
          $("#barra_progress_proveedor").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_proveedor").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_proveedor").css({ width: "0%",  });
      $("#barra_progress_proveedor").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_proveedor").css({ width: "0%", });
      $("#barra_progress_proveedor").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_para_editar_proveedor() {
  $("#cargando-11-fomulario").hide();
  $("#cargando-12-fomulario").show();

  $('#modal-agregar-proveedor').modal('show');
  $(".tooltip").remove();

  $.post("../ajax/compra_insumos.php?op=mostrar_editar_proveedor", { 'idproveedor': $('#idproveedor').select2("val") }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {     
      $("#idproveedor_prov").val(e.data.idproveedor);
      $("#tipo_documento_prov option[value='" + e.data.tipo_documento + "']").attr("selected", true);
      $("#nombre_prov").val(e.data.razon_social);
      $("#num_documento_prov").val(e.data.ruc);
      $("#direccion_prov").val(e.data.direccion);
      $("#telefono_prov").val(e.data.telefono);
      $("#banco_prov").val(e.data.idbancos).trigger("change");
      $("#c_bancaria_prov").val(e.data.cuenta_bancaria);
      $("#cci_prov").val(e.data.cci);
      $("#c_detracciones_prov").val(e.data.cuenta_detracciones);
      $("#titular_cuenta_prov").val(e.data.titular_cuenta);      

      $("#cargando-11-fomulario").show();
      $("#cargando-12-fomulario").hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); });
}

function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { 
    $('.btn-editar-proveedor').addClass('disabled').attr('data-original-title','Seleciona un proveedor').attr('title','Seleciona un proveedor');
  } else { 
    if ($('#idproveedor').select2("val") == 1) {
      $('.btn-editar-proveedor').addClass('disabled').attr('data-original-title','No editable').attr('title','No editable');      
    } else{
      var name_proveedor = $('#idproveedor').select2('data')[0].text;
      $('.btn-editar-proveedor').removeClass('disabled').attr('data-original-title',`Editar: ${recorte_text(name_proveedor, 15)}`).attr('title',`Editar: ${recorte_text(name_proveedor, 15)}`);      
    }
  }
  $('[data-toggle="tooltip"]').tooltip();
}

// :::::::::::::::::::::::::: S E C C I O N   P A G O   C O M P R A S  ::::::::::::::::::::::::::

function listar_pagos(idcompra_proyecto, idproyecto, monto_total, total_deposito) {
  reload_detraccion = "no";
  most_datos_prov_pago(idcompra_proyecto);
  localStorage.setItem("idcompra_pago_comp_nube", idcompra_proyecto);

  localStorage.setItem("monto_total_p", monto_total);
  localStorage.setItem("monto_total_dep", total_deposito);

  $("#total_compra").html(formato_miles(monto_total));

  $("#tabla-compra").hide();
  $("#tabla-compra-proveedor").hide();
  // $("#agregar_compras").show();
  $("#regresar").show();
  $("#btn_agregar").hide();
  $("#guardar_registro_compras").hide();
  $("#div_tabla_compra").hide();
  // $(".leyecnda_pagos").hide();
  // $(".leyecnda_saldos").hide();

  $("#pago_compras").show();
  $("#btn-pagar").show();
  $("#pagos_con_detraccion").hide();

  tabla_pagos1 = $("#tabla-pagos-proveedor").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
    ajax: {
      url: "../ajax/compra_insumos.php?op=listar_pagos_proveedor&idcompra_proyecto=" + idcompra_proyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      if (data[3] != '') { $("td", row).eq(3).addClass('text-left'); } 
      if (data[7] != '') { $("td", row).eq(7).addClass('text-right'); }  
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();

  total_pagos(idcompra_proyecto);
}

function listar_pagos_detraccion(idcompra_proyecto, idproyecto, monto_total, deposito_Actual) {
  var total = 0;
  reload_detraccion = "si";
  total_pagos_detracc(idcompra_proyecto);

  localStorage.setItem("idcompra_pago_detracc_nub", idcompra_proyecto);

  localStorage.setItem("monto_total_p", monto_total);
  localStorage.setItem("monto_total_dep", deposito_Actual);

  most_datos_prov_pago(idcompra_proyecto);
  $("#ttl_monto_pgs_detracc").html(formato_miles(monto_total));
  //mostramos los montos del 90 y 10 %
  $("#t_proveedor").html(formato_miles(monto_total * 0.9));
  $(".t_proveedor").val(formato_miles(monto_total * 0.9));
  $("#t_provee_porc").html("90");
  $("#t_detaccion").html(formato_miles(monto_total * 0.1));
  $(".t_detaccion").val(formato_miles(monto_total * 0.1));
  $("#t_detacc_porc").html("10");
  // t_proveedor, t_provee_porc,t_detaccion, t_detacc_porc
  $("#tabla-compra").hide();
  $("#tabla-compra-proveedor").hide();
  // $("#agregar_compras").show();
  $("#regresar").show();
  $("#btn_agregar").hide();
  $("#guardar_registro_compras").hide();
  $("#div_tabla_compra").hide();

  $("#pagos_con_detraccion").show();

  $("#btn-pagar").show();

  tabla_pagos2 = $("#tbl-pgs-detrac-prov-cmprs").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
    ajax: {
      url: "../ajax/compra_insumos.php?op=listar_pagos_compra_prov_con_dtracc&idcompra_proyecto=" + idcompra_proyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();
  //Tabla 3
  tabla_pagos3 = $("#tbl-pgs-detrac-detracc-cmprs").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
    ajax: {
      url: "../ajax/compra_insumos.php?op=listar_pgs_detrac_detracc_cmprs&idcompra_proyecto=" + idcompra_proyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();
}

//Función limpiar FORM
function limpiar_form_pago_compra() {
  
  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");
  $("#monto_pago").val("");
  $("#numero_op_pago").val("");
  $("#idpago_compras").val("");   
  $("#descripcion_pago").val("");
  $("#idpago_compra").val("");

  no_select_tomorrow("#fecha_pago");

  $("#doc_old_3").val("");
  $("#doc3").val("");  
  $('#doc3_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc3_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//mostrar datos proveedor pago
function most_datos_prov_pago(idcompra_proyecto) {
  // limpiar_form_pago_compra();
  $("#h4_mostrar_beneficiario").html("");
  $("#idproyecto_pago").val("");

  $("#banco_pago").val("").trigger("change");

  $.post("../ajax/compra_insumos.php?op=most_datos_prov_pago", { idcompra_proyecto: idcompra_proyecto }, function (e, status) {

    e = JSON.parse(e);   //console.log(e);

    if (e.status == true) {
      $("#idproyecto_pago").val(e.data.idproyecto);
      $("#idcompra_proyecto_p").val(e.data.idcompra_proyecto);
      $("#idproveedor_pago").val(e.data.idproveedor);
      $("#beneficiario_pago").val(e.data.razon_social);
      $("#h4_mostrar_beneficiario").html(e.data.razon_social);
      $("#banco_pago").val(e.data.idbancos).trigger("change");
      $("#tipo_pago").val('Proveedor').trigger("change");
      $("#titular_cuenta_pago").val(e.data.titular_cuenta);
      localStorage.setItem("nubecompra_c_b", e.data.cuenta_bancaria);
      localStorage.setItem("nubecompra_c_d", e.data.cuenta_detracciones);

      if ($("#tipo_pago").select2("val") == "Proveedor") {$("#cuenta_destino_pago").val(e.data.cuenta_bancaria);}
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

//captura_opicion tipopago
function captura_op() {
  cuenta_bancaria = localStorage.getItem("nubecompra_c_b");
  cuenta_detracciones = localStorage.getItem("nubecompra_c_d");
  //console.log(cuenta_bancaria,cuenta_detracciones);

  $("#cuenta_destino_pago").val("");

  if ($("#tipo_pago").select2("val") == "Proveedor") {
    $("#cuenta_destino_pago").val("");
    $("#cuenta_destino_pago").val(cuenta_bancaria);
  }

  if ($("#tipo_pago").select2("val") == "Detraccion") {
    $("#cuenta_destino_pago").val("");
    $("#cuenta_destino_pago").val(cuenta_detracciones);
  }
}

//Guardar y editar PAGOS
function guardaryeditar_pago(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-pago-compra")[0]);

  $.ajax({
    url: "../ajax/compra_insumos.php?op=guardaryeditar_pago",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {      
      try {
        e = JSON.parse(e);
        if (e.status == true) {
          
          Swal.fire("Correcto!", "Pago guardado correctamente", "success");
          tabla_compra_insumo.ajax.reload(null, false);
          $("#modal-agregar-pago").modal("hide");

          if (reload_detraccion == "si") {
            if (tabla_pagos2) { tabla_pagos2.ajax.reload(null, false); }
            if (tabla_pagos3) { tabla_pagos3.ajax.reload(null, false); }
          } else {
            if (tabla_pagos1) { tabla_pagos1.ajax.reload(null, false); }
          }

          /**================================================== */
          total_pagos(localStorage.getItem("idcompra_pago_comp_nube"));
          total_pagos_detracc(localStorage.getItem("idcompra_pago_detracc_nub"));
          limpiar_form_pago_compra();
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); } 
      
      $("#guardar_registro_pago").html('Guardar Cambios').removeClass('disabled');
    },
    beforeSend: function () {
      $("#guardar_registro_pago").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

//-total Pagos-sin detraccion
function total_pagos(idcompra_proyecto) {

  $("#monto_total").html("");

  $.post("../ajax/compra_insumos.php?op=suma_total_pagos", { idcompra_proyecto: idcompra_proyecto }, function (e, status) {    

    e = JSON.parse(e);  //console.log(e);

    if (e.status == true) {
      $("#monto_total").html(formato_miles(e.data.total_monto));
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

//-total pagos con detraccion
function total_pagos_detracc(idcompra_proyecto) {

  $("#monto_total_prov").html("");

  //tabla 2 proveedor
  $.post("../ajax/compra_insumos.php?op=suma_total_pagos_prov", { idcompra_proyecto: idcompra_proyecto }, function (e, status) {   

    e = JSON.parse(e); //console.log(e);
    if (e.status == true) {
      var inputValue = 0;
      var x = 0;
      var x_saldo = 0;
      var diferencia = 0;

      inputValue = parseFloat(quitar_formato_miles($(".t_proveedor").val()));

      $("#monto_total_prov").html(formato_miles(e.data.total_montoo));
      x = (e.data.total_montoo * 90) / inputValue;
      $("#porcnt_prove").html(redondearExp(x, 2) + " %");

      diferencia = 90 - x; console.log(inputValue+'xxxxxxxxxxxxxxxxxxxxx');

      x_saldo = (diferencia * e.data.total_montoo) / x;

      if (x_saldo == 0) {
        $("#saldo_p").html("0.00");
        $("#porcnt_sald_p").html("0.00" + " %");
      } else {
        $("#saldo_p").html(formato_miles(redondearExp(x_saldo, 2)));
        $("#porcnt_sald_p").html(redondearExp(diferencia, 2) + " %");
      }
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );

  $("#monto_total_detracc").html("");
  //tabla 2 detracion
  $.post("../ajax/compra_insumos.php?op=suma_total_pagos_detracc", { idcompra_proyecto: idcompra_proyecto }, function (e, status) {
    
    e = JSON.parse(e); //  console.log(e);

    if (e.status == true) {
      var valor_tt_detrcc = 0;
      var x_detrcc = 0;
      var x_saldo_detrcc = 0;
      var diferencia_detrcc = 0;

      valor_tt_detrcc = parseFloat(quitar_formato_miles($(".t_detaccion").val()));

      $("#monto_total_detracc").html(formato_miles(e.data.total_montoo));

      x_detrcc = (e.data.total_montoo * 10) / valor_tt_detrcc;
      $("#porcnt_detrcc").html(redondearExp(x_detrcc, 2) + " %");

      diferencia_detrcc = 10 - x_detrcc;

      x_saldo_detrcc = (diferencia_detrcc * e.data.total_montoo) / x_detrcc;

      if (x_saldo_detrcc == 0) {
        $("#saldo_d").html("0.00");
        $("#porcnt_sald_d").html("0.00" + " %");
      } else {
        $("#saldo_d").html(formato_miles(redondearExp(x_saldo_detrcc, 2)));
        $("#porcnt_sald_d").html(redondearExp(diferencia_detrcc, 2) + " %");
      }
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

//mostrar
function mostrar_pagos(idpago_compras) {

  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();

  limpiar_form_pago_compra();
  $("#h4_mostrar_beneficiario").html("");
  $("#idproveedor_pago").val("");
  $("#modal-agregar-pago").modal("show");
  $("#banco_pago").val("").trigger("change");
  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");

  $.post("../ajax/compra_insumos.php?op=mostrar_pagos", { idpago_compras: idpago_compras }, function (e, status) {
    
    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {
      $("#idproveedor_pago").val(e.data.idproveedor);
      $("#idcompra_proyecto_p").val(e.data.idcompra_proyecto);
      // $("#maquinaria_pago").html(e.data.nombre_maquina);
      $("#beneficiario_pago").val(e.data.beneficiario);
      $("#h4_mostrar_beneficiario").html(e.data.beneficiario);
      $("#cuenta_destino_pago").val(e.data.cuenta_destino);
      $("#banco_pago").val(e.data.id_banco).trigger("change");
      $("#titular_cuenta_pago").val(e.data.titular_cuenta);
      $("#forma_pago").val(e.data.forma_pago).trigger("change");
      $("#tipo_pago").val(e.data.tipo_pago).trigger("change");
      $("#fecha_pago").val(e.data.fecha_pago);
      $("#monto_pago").val(e.data.monto);
      $("#numero_op_pago").val(e.data.numero_operacion);
      $("#descripcion_pago").val(e.data.descripcion);
      $("#idpago_compras").val(e.data.idpago_compras);
      
      // COMPROBANTE COMPRA
      if (e.data.imagen == "" || e.data.imagen == null  ) {

        $("#doc3_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
        $("#doc3_nombre").html('');
        $("#doc_old_3").val(""); $("#doc3").val("");

      } else {

        $("#doc_old_3").val(e.data.imagen);
        $("#doc3_nombre").html(`<div class="row"> <div class="col-md-12"><i>Ficha-tecnica.${extrae_extencion(e.data.imagen)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc3_ver").html( doc_view_extencion(e.data.imagen, 'compra_insumo', 'comprobante_pago', '100%', '210') ); 
      }
      $('.jq_image_zoom').zoom({ on:'grab' });
      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function eliminar_pago_compra(idpago_compras, nombre) {

  Swal.fire({
    title: "!Elija una opción¡",
    html: `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`,
    icon: "warning",
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonColor: "#17a2b8",
    denyButtonColor: "#d33",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `<i class="fas fa-times"></i> Papelera`,
    denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,
    showLoaderOnConfirm: true,
    preConfirm: (input) => {       
      return fetch(`../ajax/compra_insumos.php?op=desactivar_pagos&idpago_compras=${idpago_compras}`).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); })
    },
    showLoaderOnDeny: true,
    preDeny: (input) => {       
      return fetch(`../ajax/compra_insumos.php?op=eliminar_pago_compra&idpago_compras=${idpago_compras}`).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    if (result.isConfirmed) {
      if (result.value.status) {
        Swal.fire("Papelera!", "Tu Pago sido enviado a la <b>PAPELERA</b>.", "success");
        total_pagos(localStorage.getItem("idcompra_pago_comp_nube"));
        total_pagos_detracc(localStorage.getItem("idcompra_pago_detracc_nub"));
        if (reload_detraccion == "si") {
          if (tabla_pagos2) { tabla_pagos2.ajax.reload(null, false); }
          if (tabla_pagos3) { tabla_pagos3.ajax.reload(null, false); }
        } else {
          if (tabla_pagos1) { tabla_pagos1.ajax.reload(null, false); }
        }
        if (tabla_compra_x_proveedor) { tabla_compra_x_proveedor.ajax.reload(null, false); }
        $(".tooltip").removeClass("show").addClass("hidde");
      }else{
        ver_errores(result.value);
      }
    }else if (result.isDenied) {
      if (result.value.status) {
        Swal.fire("ELIMINADO!", "Tu Pago a sido <b>ELIMINADO</b> permanentemente.", "success");
        total_pagos(localStorage.getItem("idcompra_pago_comp_nube"));
        total_pagos_detracc(localStorage.getItem("idcompra_pago_detracc_nub"));
        if (reload_detraccion == "si") {
          if (tabla_pagos2) { tabla_pagos2.ajax.reload(null, false); }
          if (tabla_pagos3) { tabla_pagos3.ajax.reload(null, false); }
        } else {
          if (tabla_pagos1) { tabla_pagos1.ajax.reload(null, false); }
        }
        if (tabla_compra_x_proveedor) { tabla_compra_x_proveedor.ajax.reload(null, false); }
        $(".tooltip").removeClass("show").addClass("hidde");
      }else{
        ver_errores(result.value);
      }
    }
  });
}


function ver_modal_vaucher(imagen, fecha_pago) {

  var data_comprobante = ""; var url = ""; var nombre_download = "Comprobante";

  $("#modal-ver-vaucher").modal("show");  

  if (imagen == "" || imagen == null  ) {
    data_comprobante = `<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="Alerta" aria-hidden="true">&times;</button><h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>No hay un documento para ver. Edite este registre para subir un comprobante de pago.</div>`;

  } else {    
    
    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(imagen) == "pdf" ) {       
      data_comprobante = `<div class="col-md-12 mt-4"><iframe src="../dist/docs/compra_insumo/comprobante_pago/${imagen}" frameborder="0" scrolling="no" width="100%" height="210"> </iframe></div><div class="col-md-12 mt-2"><i>Voucher.${extrae_extencion(imagen)}</i></div>`;      
      url = `../dist/docs/compra_insumo/comprobante_pago/${imagen}`;
      nombre_download = `${format_d_m_a(fecha_pago)} - Comprobante`;
    }else{
      if (
        extrae_extencion(imagen) == "jpeg" || extrae_extencion(imagen) == "jpg" || extrae_extencion(imagen) == "jpe" ||
        extrae_extencion(imagen) == "jfif" || extrae_extencion(imagen) == "gif" || extrae_extencion(imagen) == "png" ||
        extrae_extencion(imagen) == "tiff" || extrae_extencion(imagen) == "tif" || extrae_extencion(imagen) == "webp" ||
        extrae_extencion(imagen) == "bmp" || extrae_extencion(imagen) == "svg" ) {
         
        data_comprobante = `<div class="col-md-12 mt-4"><img src="../dist/docs/compra_insumo/comprobante_pago/${imagen}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" ></div><div class="col-md-12 mt-2"><i>Voucher.${extrae_extencion(imagen)}</i></div>`;         
        url = `../dist/docs/compra_insumo/comprobante_pago/${imagen}`;
        nombre_download = `${format_d_m_a(fecha_pago)} - Comprobante`;
      } else {
        data_comprobante = `<div class="col-md-12 mt-4"><img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" ></div><div class="col-md-12 mt-2"><i>Voucher.${extrae_extencion(imagen)}</i></div>`;
        url = `../dist/docs/compra_insumo/comprobante_pago/${imagen}`;
        nombre_download = `${format_d_m_a(fecha_pago)} - Comprobante`;
      }        
    }      
  }

  $(".ver-comprobante-pago").html(`<div class="row" >
    <div class="col-md-6 text-center">
      <a type="button" class="btn btn-warning btn-block btn-xs" href="${url}" download="${nombre_download}"> <i class="fas fa-download"></i> Descargar. </a>
    </div>
    <div class="col-md-6 text-center">
      <a type="button" class="btn btn-info btn-block btn-xs" href="${url}" target="_blank" <i class="fas fa-expand"></i> Ver completo. </a>
    </div>
    <div class="col-md-12 mt-4">     
      ${data_comprobante}
    </div>
  </div>`);

  $(".tooltip").removeClass("show").addClass("hidde");
}

function validar_forma_de_pago() {
  var forma_pago = $('#forma_pago').select2('val');

  if (forma_pago == null || forma_pago == "") {
    // no ejecutamos nada
    $('.validar_fp').show();
  } else {
    if (forma_pago == "Efectivo") {
      $('.validar_fp').hide();
      $("#tipo_pago").val("Proveedor").trigger("change");
      $("#banco_pago").val("1").trigger("change");
      $("#cuenta_destino_pago").val("");
      $("#titular_cuenta_pago").val("");
    } else {
      $('.validar_fp').show();
    }    
  }
}

// :::::::::::::::::::::::::: S E C C I O N   M A T E R I A L E S  ::::::::::::::::::::::::::
//Función ListarArticulos
function listarmateriales() {
  tablamateriales = $("#tblamateriales").dataTable({
    // responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i>', className: "btn ", action: function ( e, dt, node, config ) { tablamateriales.ajax.reload(null, false); toastr_success('Exito!!', 'Actualizando tabla', 400); } },],
    ajax: {
      url: "../ajax/ajax_general.php?op=tblaInsumosYActivosFijos",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[3] != '' || data[3] == 0) { $("td", row).eq(3).addClass('text-right'); }  
      if (data[5] != '' ) { $("td", row).eq(5).addClass('text-center'); }  
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    // order: [[0, "desc"]], //Ordenar (columna,orden)
    columnDefs: [ 
      //{ targets: [6], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [3], render: $.fn.dataTable.render.number(',', '.', 2) },
      //{ targets: [3], visible: false, searchable: false, }, 
    ]
  }).DataTable();
}

function mostrar_material(idproducto, cont) { 

  $("#cargando-9-fomulario").hide();
  $("#cargando-10-fomulario").show();
  
  limpiar_materiales();  

  $("#modal-agregar-material-activos-fijos").modal("show");

  $.post("../ajax/compra_insumos.php?op=mostrar_materiales", { 'idproducto_p': idproducto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);    

    if (e.status == true) {
      
      $("#cont").val(cont);     
      
      // input no usados
      $("#modelo_p").val(e.data.modelo);
      $("#serie_p").val(e.data.serie);
      $('#precio_unitario_p').val(e.data.precio_unitario);      
      $("#precio_sin_igv_p").val(e.data.precio_sin_igv);
      $("#precio_igv_p").val(e.data.precio_igv);
      $("#precio_total_p").val(e.data.precio_total);
      $("#color_p").val(e.data.idcolor);  
      $("#estado_igv_p").val(e.data.estado_igv);      

      // input usados
      $("#idproducto_p").val(e.data.idproducto);
      $("#nombre_p").val(e.data.nombre);      
      $("#categoria_insumos_af_p").val(e.data.idcategoria_insumos_af).trigger("change");
      $("#unidad_medida_p").val(e.data.idunidad_medida).trigger("change");
      $("#marcas_p").val(e.data.id_marca).trigger("change");  
      $("#descripcion_p").val(e.data.descripcion);  

      if (e.data.imagen != "") {        
        $("#foto2_i").attr("src", "../dist/docs/material/img_perfil/" + e.data.imagen);
        $("#foto2_actual").val(e.data.imagen);
      }

      // FICHA TECNICA
      if (e.data.ficha_tecnica == "" || e.data.ficha_tecnica == null  ) {

        $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

        $("#doc2_nombre").html('');

        $("#doc_old_2").val(""); $("#doc2").val("");

      } else {

        $("#doc_old_2").val(e.data.ficha_tecnica); 

        $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Ficha-tecnica.${extrae_extencion(e.data.ficha_tecnica)}</i></div></div>`);
        
        $("#doc2_ver").html(doc_view_extencion(e.data.ficha_tecnica, 'material', 'ficha_tecnica', '100%', '210'));
        
      } 
      $('.jq_image_zoom').zoom({ on:'grab' });
      $("#cargando-9-fomulario").show();
      $("#cargando-10-fomulario").hide();
    } else {
      ver_errores(e);
    }      
  }).fail( function(e) { ver_errores(e); } );
}

//Función limpiar
function limpiar_materiales() {
  // no usados
  $("#precio_unitario_p").val("0");
  $("#precio_sin_igv_p").val("0");
  $("#precio_igv_p").val("0");
  $("#precio_total_p").val("0");
  $("#color_p").val(1);
  $("#modelo_p").val("");
  $("#serie_p").val("");
  $("#estado_igv_p").val("1");

  //input usados
  $("#idproducto_p").val("");  
  $("#nombre_p").val("");  
  $("#categoria_insumos_af_p").val("").trigger("change");
  $("#unidad_medida_p").val("").trigger("change");  
  $("#marcas_p").val("").trigger("change");  
  $("#descripcion_p").val("");
 
  $("#foto2_i").attr("src", "../dist/img/default/img_defecto_activo_fijo_material.png");
  $("#foto2").val("");
  $("#foto2_actual").val("");
  $("#foto2_nombre").html("");   

  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función para guardar o editar
function guardar_y_editar_materiales(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-materiales")[0]);

  $.ajax({
    url: "../ajax/compra_insumos.php?op=guardar_y_editar_materiales",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);
        if (e.status == true) {
          Swal.fire("Correcto!", "Producto creado correctamente", "success");
          tablamateriales.ajax.reload(null, false);
          actualizar_producto();
          $("#modal-agregar-material-activos-fijos").modal("hide");

        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); } 
      $("#guardar_registro_material").html('Guardar Cambios').removeClass('disabled');
    },
    beforeSend: function () {
      $("#guardar_registro_material").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
    }
  });
}

function actualizar_producto() {

  var idproducto = $("#idproducto_p").val(); 
  var cont = $("#cont").val(); 

  var nombre_p        = $("#nombre_p").val();  
  var unidad_medida_p = $("#unidad_medida_p").find(':selected').text();
  var categoria       = $("#categoria_insumos_af_p").find(':selected').text();

  if (idproducto == "" || idproducto == null) {  } else {
    $(`.nombre_producto_${cont}`).html(nombre_p);     
    $(`.clasificacion_${cont}`).html(`<b>Clasificación: </b>${categoria}`);
    $(`.unidad_medida_${cont}`).html(unidad_medida_p); 
    $(`.unidad_medida_${cont}`).val(unidad_medida_p);    

    if ($('#foto2').val()) {
      var src_img = $(`#foto2_i`).attr("src");
      $(`.img_perfil_${cont}`).attr("src", src_img);
    }  
  }   
}

// :::::::::::::::::::::::::: S E C C I O N   M A R C A  ::::::::::::::::::::::::::
function add_new_marca() {
  limpiar_form_marca();
  $("#modal-agregar-marca").modal("show");
}
//Función limpiar
function limpiar_form_marca() {
  $("#guardar_registro_marca").html('Guardar Cambios').removeClass('disabled send-data');
  //Mostramos los Materiales
  $("m_idmarca").val("");
  $("m_nombre_marca").val("");
  $("m_descripcion_marca").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función para guardar o editar
function guardar_y_editar_marca(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-marca")[0]);
 
  $.ajax({
    url: "../ajax/compra_insumos.php?op=guardar_y_editar_marca",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Color registrado correctamente.", "success");	
          limpiar_form_marca();
          $("#modal-agregar-marca").modal("hide");     
          lista_select2("../ajax/ajax_general.php?op=select2Marcas", '#marcas_p', null);   
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); } 
      $("#guardar_registro_marca").html('Guardar Cambios').removeClass('disabled send-data');      
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_marca").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_marca").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
      $("#barra_progress_marca").css({ width: "0%",  }).text("0%");
    },
    complete: function () {
      $("#barra_progress_marca").css({ width: "0%", }).text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function name(params) {
  
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..
$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").on('change', function() { $(this).trigger('blur'); });
  $("#glosa").on('change', function() { $(this).trigger('blur'); });
  $("#banco_pago").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_comprobante").on('change', function() { $(this).trigger('blur'); });
  $("#forma_pago").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_pago").on('change', function() { $(this).trigger('blur'); });
  $("#banco_prov").on('change', function() { $(this).trigger('blur'); });
  $("#categoria_insumos_af_p").on('change', function() { $(this).trigger('blur'); });
  // $("#color_p").on('change', function() { $(this).trigger('blur'); });
  $("#unidad_medida_p").on('change', function() { $(this).trigger('blur'); });

  $("#form-compras").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproveedor:        { required: true },
      tipo_comprobante:   { required: true },
      serie_comprobante:  { minlength: 2 },
      descripcion:        { minlength: 4 },
      fecha_compra:       { required: true },
      glosa:              { required: true },
      val_igv:            { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idproveedor:        { required: "Campo requerido", },
      tipo_comprobante:   { required: "Campo requerido", },
      serie_comprobante:  { minlength: "Minimo 2 caracteres", },
      descripcion:        { minlength: "Minimo 4 caracteres", },
      fecha_compra:       { required: "Campo requerido", },
      glosa:              { required: "Campo requerido", },
      val_igv:            { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
      'cantidad[]':       { min: "Mínimo 0.01", required: "Campo requerido"},
      'precio_con_igv[]': { min: "Mínimo 0.01", required: "Campo requerido"}
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

    submitHandler: function (form) {
      guardar_y_editar_compras(form);
    },
  });  

  $("#form-proveedor").validate({
    rules: {
      tipo_documento_prov:  { required: true },
      num_documento_prov:   { required: true, minlength: 6, maxlength: 20 },
      nombre_prov:          { required: true, minlength: 3, maxlength: 100 },
      direccion_prov:       { minlength: 5, maxlength: 150 },
      telefono_prov:        { minlength: 8 },
      c_bancaria_prov:      { minlength: 6,  },
      cci_prov:             { minlength: 6,  },
      c_detracciones_prov:  { minlength: 6,  },      
      banco_prov:           { required: true },
      titular_cuenta_prov:  { minlength: 4 },
    },
    messages: {
      tipo_documento_prov:  { required: "Campo requerido.", },
      num_documento_prov:   { required: "Campo requerido.",  minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre_prov:          { required: "Campo requerido.", minlength: "MÍNIMO 3 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      direccion_prov:       { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 150 caracteres.", },
      telefono_prov:        { minlength: "MÍNIMO 9 caracteres.", },
      c_bancaria_prov:      { minlength: "MÍNIMO 6 caracteres.", },
      cci_prov:             { minlength: "MÍNIMO 6 caracteres.",  },
      c_detracciones_prov:  { minlength: "MÍNIMO 6 caracteres.", },      
      banco_prov:           { required: "Campo requerido.",  },
      titular_cuenta_prov:  { minlength: 'MÍNIMO 4 caracteres.' },
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
      guardar_proveedor(e);
    },
  });

  $("#form-pago-compra").validate({
    rules: {
      forma_pago:         { required: true },
      tipo_pago:          { required: true },
      banco_pago:         { required: true },
      fecha_pago:         { required: true },
      monto_pago:         { required: true },
      numero_op_pago:     { minlength: 3 },
      descripcion_pago:   { minlength: 3 },
      titular_cuenta_pago:{ minlength: 3 },
    },
    messages: {
      forma_pago:         { required: "Campo requerido.", },
      tipo_pago:          { required: "Campo requerido.", },
      banco_pago:         { required: "Campo requerido.", },
      fecha_pago:         { required: "Campo requerido.", },
      monto_pago:         { required: "Campo requerido.", },
      numero_op_pago:     { minlength: 'MÍNIMO 3 caracteres.' },
      descripcion_pago:   { minlength: 'MÍNIMO 3 caracteres.' },
      titular_cuenta_pago:{ minlength: 'MÍNIMO 3 caracteres.' },
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
      guardaryeditar_pago(e);
    },
  });

  $("#form-comprobante").validate({
    rules: {
      nombre: { required: true },
    },

    messages: {
      nombre: {  required: "Este campo es requerido", },
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
      guardaryeditar_comprobante(e);
    },
  });

  $("#form-materiales").validate({
    rules: {
      nombre_p:               { required: true, minlength:3, maxlength:200},     
      descripcion_p:          { minlength: 3 },
      unidad_medida_p:        { required: true },     
      categoria_insumos_af_p: { required: true },
    },
    messages: {
      nombre_p:               { required: "Por favor ingrese nombre", minlength:"Minimo 3 caracteres", maxlength:"Maximo 200 caracteres" }, 
      descripcion_p:          { minlength: "Minimo 3 caracteres" },
      unidad_medida_p:        { required: "Campo requerido" },  
      categoria_insumos_af_p: { required: "Campo requerido", },
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
      guardar_y_editar_materiales(e);
    },

  });

  $("#form-marca").validate({
    rules: {
      m_nombre_marca: { required: true }      // terms: { required: true },
    },
    messages: {
      m_nombre_marca: {  required: "Campo requerido.", },
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
      guardar_y_editar_marca(e);      
    },

  });

  $("#idproveedor").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#glosa").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_comprobante").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#forma_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_pago").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco_prov").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#categoria_insumos_af_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  // $("#color_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#unidad_medida_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

function l_m() {
  // limpiar_form_compra();
  $("#barra_progress").css({ width: "0%" });

  $("#barra_progress").text("0%");

  $("#barra_progress2").css({ width: "0%" });

  $("#barra_progress2").text("0%");
}

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
  //if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; } else{ fecha_1 = format_a_m_d(fecha_1) == '-'? '': format_a_m_d(fecha_1);}
  //if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; } else{ fecha_2 = format_a_m_d(fecha_2) == '-'? '': format_a_m_d(fecha_2);} 

  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; } else{ fecha_1 = fecha_1 == '-'? '': fecha_1;}
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; } else{ fecha_2 = fecha_2 == '-'? '': fecha_2;} 

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == 0 || id_proveedor == null) { id_proveedor = ""; nombre_proveedor = ""; }

  // filtro de trabajdor
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_proveedor} ${nombre_comprobante}...`);
  //console.log(fecha_1, fecha_2, id_proveedor, comprobante);

  tbla_principal(localStorage.getItem("nube_idproyecto"), fecha_1, fecha_2, id_proveedor, comprobante);
}

//validando excedentes
function validando_excedentes() {
  var totattotal = quitar_formato_miles(localStorage.getItem("monto_total_p"));
  var monto_total_dep = quitar_formato_miles(localStorage.getItem("monto_total_dep"));
  var monto_entrada = $("#monto_pago").val();
  var total_suma = parseFloat(monto_total_dep) + parseFloat(monto_entrada);
  var debe = parseFloat(totattotal) - monto_total_dep;

  //console.log(typeof total_suma);

  if (total_suma > totattotal) {
    toastr_error("Exedente!!",`Monto excedido al total del monto a pagar!`, 700);
  } else {
    toastr_success("Aceptado!!",`Monto Aceptado.`, 700);
  }
}

// ver imagen grande del producto agregado a la compra
function ver_img_material(img, nombre) {
  $("#ver_img_material").attr("src", `${img}`);
  $(".nombre-img-material").html(nombre);
  $("#modal-ver-img-material").modal("show");
  $(`.jq_image_zoom`).zoom({ on:'grab' });
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

//Función para guardar o editar - COMPRAS
function guardar_y_editar_compras____________plantilla_cargando_POST(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-compras")[0]);

  var swal2_header = `<img class="swal2-image bg-color-252e38 b-radio-7px p-15px m-10px" src="../dist/gif/cargando.gif">`;

  var swal2_content = `<div class="row sweet_loader" >    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
      <div class="progress" id="barra_progress_compra_div">
        <div id="barra_progress_compra" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
          0%
        </div>
      </div>
    </div>
  </div>`;

  Swal.fire({
    title: "¿Está seguro que deseas guardar esta compra?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../ajax/compra_insumos.php?op=guardar_y_editar_compra",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function() {
          Swal.fire({
            title: "Guardando...",
            html: 'Tu <b>información</b> se esta guradando en la <b>base de datos</b>.',
            showConfirmButton: false,
            didRender: function() { 
              /* solo habrá un swal2 abierta.*/               
              $('.swal2-header').prepend(swal2_header); 
              $('.swal2-content').prepend(swal2_content);
            }
          });
          $("#barra_progress_compra").addClass('progress-bar-striped progress-bar-animated');
        },
        success: function (e) {
          try {
            e = JSON.parse(e);
            if (e.status == true ) {
              // toastr.success("Usuario registrado correctamente");
              Swal.fire("Correcto!", "Compra guardada correctamente", "success");

              tabla_compra_insumo.ajax.reload(null, false);
              tabla_compra_x_proveedor.ajax.reload(null, false);

              limpiar_form_compra(); table_show_hide(1);
              
              $("#modal-agregar-usuario").modal("hide");
              l_m();
              
            } else {
              // toastr.error(datos);
              Swal.fire("Error!", datos, "error");
              l_m();
            }
          } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); } 

        },
        xhr: function () {
          var xhr = new window.XMLHttpRequest();    
          xhr.upload.addEventListener("progress", function (evt) {    
            if (evt.lengthComputable) {    
              var percentComplete = (evt.loaded / evt.total)*100;
              /*console.log(percentComplete + '%');*/
              $("#barra_progress_compra").css({"width": percentComplete+'%'});    
              $("#barra_progress_compra").text(percentComplete.toFixed(2)+" %");
            }
          }, false);
          return xhr;
        }
      });
    }
  });  
}

function sincronizar_comprobante() {
  $('#btn_sincronizar').html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
  $.post("../ajax/compra_insumos.php?op=sincronizar_comprobante",  function (e, textStatus, jqXHR) {
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      tabla_compra_insumo.ajax.reload(null, false);
      $('#btn_sincronizar').html('<i class="fas fa-plus-circle"></i> sincronizar comprobante').removeClass('bg-gradient-danger').addClass('bg-gradient-success');
    } else {
      ver_errores(e);
    } 
  }).fail( function(e) { ver_errores(e); } );
}