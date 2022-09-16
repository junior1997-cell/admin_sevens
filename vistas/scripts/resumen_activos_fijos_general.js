//tbl maaquinaria
var tabla_principal_maquinaria;
var tabla_principal_equipo;
var tabla_principal_herramienta;
var tabla_principal_oficina;

var tabla_factura;
var tabla_materiales;

var array_class_trabajador = [];
var cont = 0;
var detalles = 0;

var  idproducto_r = "", nombre_producto_r = "", precio_promedio_r = "", subtotal_x_producto_r = "";

var op_guardar_compras = "";

function init(){

  $("#mResumenActivosFijosGeneral").addClass("active");
	
	tbla_principal_maquinaria();
	tbla_principal_equipo();
  tbla_principal_herramienta();
  tbla_principal_oficina();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);

  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_prov', null);

  lista_select2("../ajax/ajax_general.php?op=select2Categoria", '#categoria_insumos_af_p', null);

  lista_select2("../ajax/ajax_general.php?op=select2Color", '#color_p', null);

  lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida_p', null);

  lista_select2("../ajax/ajax_general.php?op=select2marcas_activos", '#marca_p', null);


  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════

  $("#guardar_registro_compras").on("click", function (e) {  $("#submit-form-compras").submit(); });

  $("#guardar_registro_compras_p").on("click", function (e) { $("#submit-form-compra-activos-p").submit(); });

  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });

  $("#guardar_registro_material").on("click", function (e) {  $("#submit-form-materiales").submit(); });  

  // ══════════════════════════════════════ INITIALIZE SELECT2 - COMPRAS ══════════════════════════════════════

  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione Proveedor", allowClear: true, });

  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione Comprobante", allowClear: true, });

  $("#glosa").select2({ templateResult: templateGlosa, theme: "bootstrap4", placeholder: "Selecione Glosa", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - PROVEEDOR ══════════════════════════════════════

  $("#banco_prov").select2({templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });
  
  // ══════════════════════════════════════ INITIALIZE SELECT2 - MATERIAL ══════════════════════════════════════

  $("#categoria_insumos_af_p").select2({  theme: "bootstrap4", placeholder: "Seleccinar color", allowClear: true, });

  $("#color_p").select2({templateResult: templateColor, theme: "bootstrap4",  placeholder: "Seleccinar color", allowClear: true, });

  $("#unidad_medida_p").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });
  
  $("#marca_p").select2({ theme: "bootstrap4", placeholder: "Seleccinar marca", allowClear: true, });
  

  // Formato para telefono
  $("[data-mask]").inputmask();
}

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
  if (!state.id) { return state.text; }  
  var $state = $(`<span ><b class="mr-2"><i class="${state.title}"></i></b>${state.text}</span>`);
  return $state;
}

// Perfil material
$("#foto2_i").click(function () {  $("#foto2").trigger("click"); });
$("#foto2").change(function (e) { addImage(e, $("#foto2").attr("id")); });

//ficha tecnica
$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e,$("#doc2").attr("id")) });

// OCULTAR MOSTRAR - TABLAS
function table_show_hide(flag) {
  if (flag == 1) {
    $(".mensaje-tbla-principal").show();
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();    
    $("#guardar_registro_compras").hide();

    $(".nombre-activo").html('<i class="fas fa-tasks"></i> Resumen activos según <b>Clasificación</b>');

    $("#tabla-principal").show();
    $('.card-2').hide();
    $("#tabla-factura").hide();
    $("#tabla-editar-factura").hide();
  } else {
    if (flag == 2) {
      $(".mensaje-tbla-principal").hide();
      $("#btn-regresar").show();
      $("#btn-regresar-todo").hide();
      $("#btn-regresar-bloque").hide();
      $("#guardar_registro_compras").hide();
       

      $("#tabla-principal").hide();
      $('.card-2').show();
      $("#tabla-factura").show();
      $("#tabla-editar-factura").hide();
    }else{
      if (flag == 3) {
        $(".mensaje-tbla-principal").hide();
        $("#btn-regresar").hide();
        $("#btn-regresar-todo").show();
        $("#btn-regresar-bloque").show();  
        $("#guardar_registro_compras").hide();       

        $("#tabla-principal").hide();
        $('.card-2').show();
        $("#tabla-factura").hide();
        $("#tabla-editar-factura").show();        
      }
    }
  }
}

// TABLA - PRINCIPAL MAQUINARIA 
function tbla_principal_maquinaria() {
  $(".suma_total_cant_maquinarias").html('<i class="far fa-frown fa-lg text-danger"></i>');
  $('.suma_total_de_maquinarias').html('<i class="far fa-frown fa-lg text-danger"></i>');

	tabla_principal_maquinaria=$('#tabla-resumen-maquinarias').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } } ,
    ],
    ajax:	{
      url: `../ajax/resumen_activos_fijos_general.php?op=tbla_principal&id_categoria=2`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {    
      // columna: Compra
      if (data[6] != '') { $("td", row).eq(6).addClass("text-center"); }    
      // columna: Precio promedio
      if (data[7] != '') { $("td", row).eq(7).addClass("text-right"); }
      // columna: Precio actual
      if (data[8] != '') { $("td", row).eq(8).addClass("text-right"); }
      // columna: Suma Total
      if (data[9] != '') { $("td", row).eq(9).addClass("text-right"); }
    },
		language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
		bDestroy: true,
		iDisplayLength: 10,//Paginación
	  order: [[ 0, "asc" ]],//Ordenar (columna,orden)
  columnDefs:[  
    { "targets": [ 10,11, ], "visible": false, "searchable": false },
    { targets: [7,8,9], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
  ]
	}).DataTable();

  $.post("../ajax/resumen_activos_fijos_general.php?op=suma_total_compras", {'id_categoria_suma':2}, function (e, status) {

    e = JSON.parse(e); //console.log(data); 

    if (e.status == true) {
      if (e.data.total_cantidad == null || e.data.total_cantidad == '') {
        $(".suma_total_cant_maquinarias").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".suma_total_cant_maquinarias").html( formato_miles(e.data.total_cantidad));
      }

      if (e.data.total_monto == null || e.data.total_monto == '') {
        $('.suma_total_de_maquinarias').html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $('.suma_total_de_maquinarias').html( formato_miles(e.data.total_monto));
      }     

    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// TABLA - PRINCIPAL EQUIPOS 
function tbla_principal_equipo() {

  $(".suma_total_cant_equipos").html('<i class="far fa-frown fa-lg text-danger"></i>');
  $('.suma_total_de_equipos').html('<i class="far fa-frown fa-lg text-danger"></i>');

	tabla_principal_equipo=$('#tabla-resumen-equipos').dataTable({
		responsive: true,
	  lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
		aProcessing: true,//Activamos el procesamiento del datatables
	  aServerSide: true,//Paginación y filtrado realizados por el servidor
	  dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	  buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } } ,
    ],
		ajax:	{
      url: '../ajax/resumen_activos_fijos_general.php?op=tbla_principal&id_categoria=3',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
		},
    createdRow: function (row, data, ixdex) {  
      // columna: #0
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }  
      // columna: Cantidad
      if (data[5] != '') { $("td", row).eq(5).addClass("text-center"); }
      // columna: compras
      if (data[6] != '') { $("td", row).eq(6).addClass("text-center");  }    
      // columna: Precio promedio
      if (data[7] != '') { $("td", row).eq(7).addClass("text-right"); }
      // columna: Precio actual
      if (data[8] != '') { $("td", row).eq(8).addClass("text-right"); }
      // columna: Suma Total
      if (data[9] != '') { $("td", row).eq(9).addClass("text-right"); }
    },
		language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
		bDestroy: true,
		iDisplayLength: 10,//Paginación
	  order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs:[ 
      { "targets": [ 10,11 ], "visible": false, "searchable": false }, 
      { targets: [7,8,9], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ]
	}).DataTable();

  $.post("../ajax/resumen_activos_fijos_general.php?op=suma_total_compras", {'id_categoria_suma':3}, function (e, status) {

    e = JSON.parse(e); // console.log(e); 

    if (e.status == true) {
      if (e.data.total_cantidad == null || e.data.total_cantidad == '') {
        $(".suma_total_cant_equipos").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".suma_total_cant_equipos").html( formato_miles(e.data.total_cantidad));
      }

      if (e.data.total_monto == null || e.data.total_monto == '') {
        $('.suma_total_de_equipos').html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $('.suma_total_de_equipos').html( formato_miles(e.data.total_monto));
      }     

    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// TABLA - PRINCIPAL HERRAMIENTAS 
function tbla_principal_herramienta() {

  $(".suma_total_herramientas").html('<i class="far fa-frown fa-lg text-danger"></i>');
  $('.suma_total_de_herramientas').html('<i class="far fa-frown fa-lg text-danger"></i>');

	tabla_principal_herramienta=$('#tabla-resumen-herramientas').dataTable({
		responsive: true,
	  lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
		aProcessing: true,//Activamos el procesamiento del datatables
	  aServerSide: true,//Paginación y filtrado realizados por el servidor
	  dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	  buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } } ,
    ],
		ajax:	{
      url: '../ajax/resumen_activos_fijos_general.php?op=tbla_principal&id_categoria=4',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
		},
    createdRow: function (row, data, ixdex) {    
      // columna: #0
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: Cantidad
      if (data[5] != '') { $("td", row).eq(5).addClass("text-center"); }
      // columna: compras
      if (data[6] != '') { $("td", row).eq(6).addClass("text-center"); }    
      // columna: Precio promedio
      if (data[7] != '') { $("td", row).eq(7).addClass("text-right"); }
      // columna: Precio actual
      if (data[8] != '') { $("td", row).eq(8).addClass("text-right"); }
      // columna: Suma Total
      if (data[9] != '') { $("td", row).eq(9).addClass("text-right"); }
    },
		language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
		bDestroy: true,
		iDisplayLength: 10,//Paginación
	  order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs:[       
      { "targets": [ 10,11 ], "visible": false, "searchable": false }, 
      { targets: [7,8,9], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ]
	}).DataTable();

  $.post("../ajax/resumen_activos_fijos_general.php?op=suma_total_compras", {'id_categoria_suma':4}, function (e, status) {

    e = JSON.parse(e); //console.log(e); 

    if (e.status == true) {
      if (e.data.total_cantidad == null || e.data.total_cantidad == '') {
        $(".suma_total_herramientas").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".suma_total_herramientas").html( formato_miles(e.data.total_cantidad));
      }

      if (e.data.total_monto == null || e.data.total_monto == '') {
        $('.suma_total_de_herramientas').html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $('.suma_total_de_herramientas').html( formato_miles(e.data.total_monto));
      }     

    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// TABLA - PRINCIPAL OFICINA 
function tbla_principal_oficina(){
  $(".suma_total_oficina").html('<i class="far fa-frown fa-lg text-danger"></i>');
  $('.suma_total_de_oficina').html('<i class="far fa-frown fa-lg text-danger"></i>');

	tabla_principal_oficina=$('#tabla-resumen-oficina').dataTable({
		responsive: true,
	  lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
		aProcessing: true,//Activamos el procesamiento del datatables
	  aServerSide: true,//Paginación y filtrado realizados por el servidor
	  dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	  buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,10,11,3,4,5,7,8,9], } } ,
    ],
		ajax:	{
      url: '../ajax/resumen_activos_fijos_general.php?op=tbla_principal&id_categoria=5',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
		},
    createdRow: function (row, data, ixdex) {    
      // columna: #0
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: Cantidad
      if (data[5] != '') { $("td", row).eq(5).addClass("text-center"); }
      // columna: compras
      if (data[6] != '') { $("td", row).eq(6).addClass("text-center"); }    
      // columna: Precio promedio
      if (data[7] != '') { $("td", row).eq(7).addClass("text-right"); }
      // columna: Precio actual
      if (data[8] != '') { $("td", row).eq(8).addClass("text-right"); }
      // columna: Suma Total
      if (data[9] != '') { $("td", row).eq(9).addClass("text-right"); }
    },
		language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
		bDestroy: true,
		iDisplayLength: 10,//Paginación
	  order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs:[ 
      { "targets": [ 10,11 ], "visible": false, "searchable": false }, 
      { targets: [7,8,9], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ]
	}).DataTable();

  $.post("../ajax/resumen_activos_fijos_general.php?op=suma_total_compras", {'id_categoria_suma':5}, function (e, status) {

    e = JSON.parse(e); // console.log(e); 

    if (e.status == true) {
      if (e.data.total_cantidad == null || e.data.total_cantidad == '') {
        $(".suma_total_oficina").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".suma_total_oficina").html( formato_miles(e.data.total_cantidad));
      }

      if (e.data.total_monto == null || e.data.total_monto == '') {
        $('.suma_total_de_oficina').html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $('.suma_total_de_oficina').html( formato_miles((e.data.total_monto).toFixed(2)));
      }     

    } else {      
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  :::::::::::::::::::::::::::::::::::::::::::::
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

        var format_cta = decifrar_format_banco(data.formato_cta);
        var format_cci = decifrar_format_banco(data.formato_cci);
        var formato_detracciones = decifrar_format_banco(data.formato_detracciones);
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
// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R E S :::::::::::::::::::::::::::::::::::::::::::::


//guardar proveedor
function guardar_proveedor(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);

  crud_guardar_editar_modal_select2_xhr( 
    "../ajax/resumen_activos_fijos_general.php?op=guardar_proveedor", 
    formData,
    '#barra_progress_proveeedor', 
    "../ajax/ajax_general.php?op=select2Proveedor", 
    '#idproveedor',
    function(){ limpiar_form_proveedor(); $("#modal-agregar-proveedor").modal("hide"); }, 
    function(){ sw_success('Correcto!', "Proveedor guardado correctamente." ); }, 
  );

}

function mostrar_para_editar_proveedor() {
  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();
  limpiar_form_proveedor();
  $('#modal-agregar-proveedor').modal('show');
  $(".tooltip").remove();

  $.post("../ajax/resumen_activos_fijos_general.php?op=mostrar_editar_proveedor", { 'idproveedor': $('#idproveedor').select2("val") }, function (e, status) {

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

      $("#cargando-7-fomulario").show();
      $("#cargando-8-fomulario").hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); });
}


// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   P R O D U C T O S :::::::::::::::::::::::::::::::::::::::::::::

// TABLA - MATERIALES
function tbla_materiales(op) {

  tabla_materiales = $("#tblamateriales").dataTable({
    responsive: true,
    lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [],
    ajax: {
      url: `../ajax/ajax_general.php?op=tblaInsumosYActivosFijos`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, index) {
      // columna: sueldo mensual
      if (data[3] != '') {  $("td", row).eq(3).addClass('text-right'); }  
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    // order: [[0, "desc"]], //Ordenar (columna,orden)
  }).DataTable();
}

//Función limpiar
function limpiar_materiales() {
  $("#idproducto_p").val("");  
  $("#nombre_p").val("");
  $("#modelo_p").val("");
  $("#serie_p").val("");
  $("#marca_p").val("").trigger("change");
  $("#descripcion_p").val("");

  $("#precio_unitario_p").val("");
  $("#precio_sin_igv_p").val("");
  $("#precio_igv_p").val("");
  $("#precio_total_p").val("");

  $("#foto2_i").attr("src", "../dist/img/default/img_defecto_activo_fijo_material.png");
  $("#foto2").val("");
  $("#foto2_actual").val("");
  $("#foto2_nombre").html("");   

  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");

  $("#unidad_medida_p").val("").trigger("change");
  $("#color_p").val(1).trigger("change");
  $("#categoria_insumos_af_p").val("").trigger("change");

  $("#my-switch_igv").prop("checked", true);
  $("#estado_igv_p").val("1");

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
    url: "../ajax/resumen_activos_fijos_general.php?op=guardar_y_editar_materiales",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {

          Swal.fire("Correcto!", "Producto creado correctamente", "success");      
        
          if (tabla_materiales) { tabla_materiales.ajax.reload(null, false); }

          if (tabla_principal_maquinaria) { tabla_principal_maquinaria.ajax.reload(null, false); }
          if (tabla_principal_equipo) { tabla_principal_equipo.ajax.reload(null, false); } 
          if (tabla_principal_herramienta) { tabla_principal_herramienta.ajax.reload(null, false); }
          if (tabla_principal_oficina) { tabla_principal_oficina.ajax.reload(null, false); } 

          actualizar_producto();
          
          $("#modal-agregar-material-activos-fijos").modal("hide");

        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {

          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_activo_fijo").css({"width": percentComplete+'%'});

          $("#barra_progress_activo_fijo").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#barra_progress_activo_fijo").css({ width: "0%",  });
      $("#barra_progress_activo_fijo").text("0%");
    },
    complete: function () {
      $("#barra_progress_activo_fijo").css({ width: "0%", });
      $("#barra_progress_activo_fijo").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// MOSTRAR PARA EDITAR
function mostrar_material(idproducto, cont) { 

  $(".tooltip").removeClass("show").addClass("hidde");
  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();
  
  limpiar_materiales();  

  $("#modal-agregar-material-activos-fijos").modal("show");

  $.post("../ajax/resumen_activos_fijos_general.php?op=mostrar_producto", { 'idproducto_p': idproducto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e); 
    
    if (e.status == true) {
      $("#idproducto_p").val(e.data.idproducto);
      $("#cont").val(cont);

      $("#nombre_p").val(e.data.nombre);
      $("#modelo_p").val(e.data.modelo);
      $("#serie_p").val(e.data.serie);
      $("#marca_p").val(e.data.marca).trigger("change");
      $("#descripcion_p").val(e.data.descripcion);

      $('#precio_unitario_p').val(parseFloat(e.data.precio_unitario).toFixed(2));
      $("#estado_igv_p").val(parseFloat(e.data.estado_igv).toFixed(2));
      $("#precio_sin_igv_p").val(parseFloat(e.data.precio_sin_igv).toFixed(2));
      $("#precio_igv_p").val(parseFloat(e.data.precio_igv).toFixed(2));
      $("#precio_total_p").val(parseFloat(e.data.precio_total).toFixed(2));
      
      $("#unidad_medida_p").val(e.data.idunidad_medida).trigger("change");
      $("#color_p").val(e.data.idcolor).trigger("change");  
      $("#categoria_insumos_af_p").val(e.data.idcategoria_insumos_af).trigger("change");    
      $("#idtipo_tierra_concreto").val(e.data.idtipo_tierra_concreto)    

      if (e.data.estado_igv == "1") {
        $("#my-switch_igv").prop("checked", true);
      } else {
        $("#my-switch_igv").prop("checked", false);
      }
      
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
        
        $("#doc2_ver").html(doc_view_extencion(e.data.ficha_tecnica, 'material', 'ficha_tecnica', '100%'));
              
      } 

      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

// DETALLE DEL MATERIAL
function mostrar_detalle_material(idproducto) { 

  $(".tooltip").removeClass("show").addClass("hidde");

  $('#datosproductos').html(`<div class="row" ><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div></div>`);

  var imagen_perfil =''; var btn_imagen_perfil = '';
  
  var ficha_tecnica=''; var btn_ficha_tecnica = '';

  $("#modal-ver-detalle-material-activo-fijo").modal("show")

  $.post("../ajax/resumen_activos_fijos_general.php?op=mostrar_detalle_material", { 'idproducto_p': idproducto }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 

    if (e.status == true) {
      if (e.data.imagen == '' || e.data.imagen == null ) {

        imagen_perfil=`<img src="../dist/docs/material/img_perfil/producto-sin-foto.svg" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`;
        btn_imagen_perfil='';
      
      } else {
        imagen_perfil=`<img src="../dist/docs/material/img_perfil/${e.data.imagen}" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`
        
        btn_imagen_perfil=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/material/img_perfil/${e.data.imagen}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/material/img_perfil/${e.data.imagen}" download="PERFIL - ${removeCaracterEspecial(e.data.nombre)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;        

      }     

      if (e.data.ficha_tecnica == '' || e.data.ficha_tecnica == null) {
        
        ficha_tecnica='Sin Ficha Técnica';
        btn_ficha_tecnica='';
      
      } else {
        ficha_tecnica =  doc_view_extencion(e.data.ficha_tecnica, 'material', 'ficha_tecnica', '100%');
        
        btn_ficha_tecnica=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/material/ficha_tecnica/${e.data.ficha_tecnica}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/material/ficha_tecnica/${e.data.ficha_tecnica}" download="Ficha Tecnica - ${removeCaracterEspecial(e.data.nombre)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;        

      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th rowspan="2">${imagen_perfil}<br>${btn_imagen_perfil}</th>
                  <td> <b>Nombre: </b> ${e.data.nombre}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td> <b>Color: </b> ${e.data.nombre_color}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Categoria</th>
                  <td>${e.data.categoria}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>U.M.</th>
                  <td>${e.data.nombre_medida}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Marca</th>
                    <td>${e.data.nombre_marca}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Con IGV</th>
                  <td>${(e.data.estado_igv==1? '<div class="myestilo-switch ml-2"><div class="switch-toggle"><input type="checkbox" id="my-switch-igv-2" checked disabled /><label for="my-switch-igv-2"></label></div></div>' : '<div class="myestilo-switch ml-3"><div class="switch-toggle"><input type="checkbox" id="my-switch-igv-2" disabled/><label for="my-switch-igv-2"></label></div></div>')}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Precio  </th>
                  <td>${e.data.precio_unitario}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Sub Total</th>
                  <td>${e.data.precio_sin_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${e.data.precio_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total </th>
                  <td>${e.data.precio_total}</td>
                </tr> 
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Modelo</th>
                  <td>${e.data.modelo}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Serie</th>
                  <td>${e.data.serie}</td>
                </tr>               
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Ficha Técnica</th>
                  <td> ${ficha_tecnica} <br>${btn_ficha_tecnica}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;

      $("#datosproductos").html(retorno_html);
    } else {
      ver_errores(e);
    }
  
  }).fail( function(e) { ver_errores(e); } );

}

function precio_con_igv() {
  var precio_ingresado = $("#precio_unitario_p").val()=='' ? 0 : parseFloat($("#precio_unitario_p").val());

  var input_precio_con_igv = 0;
  var igv = 0;
  var input_precio_sin_igv = 0;

  if ($("#my-switch_igv").is(":checked")) {
    input_precio_sin_igv = precio_ingresado / 1.18;
    igv = precio_ingresado - input_precio_sin_igv;
    input_precio_con_igv = precio_ingresado;
    
    $("#precio_sin_igv_p").val(input_precio_sin_igv.toFixed(2));
    $("#precio_igv_p").val(igv.toFixed(2));    
    $("#precio_total_p").val(input_precio_con_igv.toFixed(2));

    $("#estado_igv_p").val("1");
  } else {
    input_precio_con_igv = precio_ingresado * 1.18;
    igv = input_precio_con_igv - precio_ingresado;
    input_precio_sin_igv = parseFloat(precio_ingresado);

    $("#precio_sin_igv_p").val( input_precio_sin_igv.toFixed(2));
    $("#precio_igv_p").val(igv.toFixed(2));    
    $("#precio_total_p").val(input_precio_con_igv.toFixed(2));

    $("#estado_igv_p").val("0");
  }
}

$("#my-switch_igv").on("click ", function (e) {

  var precio_ingresado = $("#precio_unitario_p").val()=='' ? 0 : parseFloat($("#precio_unitario_p").val());

  var input_precio_con_igv = 0;
  var igv = 0;
  var input_precio_sin_igv = 0;

  if ($("#my-switch_igv").is(":checked")) {
    input_precio_sin_igv = precio_ingresado / 1.18;
    igv = precio_ingresado - input_precio_sin_igv;
    input_precio_con_igv = precio_ingresado;  

    $("#precio_sin_igv_p").val(redondearExp(input_precio_sin_igv, 2));
    $("#precio_igv_p").val(redondearExp(igv, 2));   
    $("#precio_total_p").val(redondearExp(input_precio_con_igv, 2)) ;

    $("#estado_igv_p").val("1");
  } else {
    input_precio_con_igv = precio_ingresado * 1.18;     
    igv = input_precio_con_igv - precio_ingresado;
    input_precio_sin_igv = parseFloat(precio_ingresado);  

    $("#precio_sin_igv_p").val(redondearExp(input_precio_sin_igv, 2));
    $("#precio_igv_p").val(redondearExp(igv, 2));
    $("#precio_total_p").val(redondearExp(input_precio_con_igv, 2) );

    $("#estado_igv_p").val("0");
  }
});

function actualizar_producto() {

  var idproducto = $("#idproducto_p").val(); 
  var cont = $("#cont").val(); console.log(idproducto, cont);

  var nombre_p = $("#nombre_p").val();  
  var precio_total_p = $("#precio_total_p").val();
  var unidad_medida_p = $("#unidad_medida_p").find(':selected').text();
  var color_p = $("#color_p").find(':selected').text();  

  if (idproducto == "" || idproducto == null) {
     
  } else {
    $(`.nombre_producto_${cont}`).html(nombre_p); 
    $(`.color_${cont}`).html(`<b>Color: </b>${color_p}`);
    $(`.color_${cont}`).val(color_p); 
    $(`.unidad_medida_${cont}`).html(unidad_medida_p); 
    $(`.unidad_medida_${cont}`).val(unidad_medida_p);
    $(`.precio_con_igv_${cont}`).val(precio_total_p);   

    if ($('#foto2').val()) {
      var src_img = $(`#foto2_i`).attr("src");
      $(`.img_perfil_${cont}`).attr("src", src_img);
    }    
  } 
  
  modificarSubtotales();
}

function ver_perfil(file, nombre) {
  $('.foto-insumo').html(nombre);
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#modal-ver-perfil-insumo").modal("show");
  $('#perfil-insumo').html(`<center><img class="img-thumbnail" src="${file}" onerror="this.src='../dist/svg/404-v2.svg';" alt="Perfil" width="100%"></center>`);
}

// :::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A S -  G E N E R A L   Y/O   P R O Y E C T O  ::::::::::::::::::::::::::::::::::

// TABLA - FACTURAS
function tbla_facuras(  idproducto, nombre_producto, precio_promedio, subtotal_x_producto ) {

  idproducto_r = idproducto; nombre_producto_r = nombre_producto; 
  precio_promedio_r = precio_promedio; subtotal_x_producto_r = subtotal_x_producto;

  $(".cantidad_x_producto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.precio_promedio').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $(".descuento_x_producto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.subtotal_x_producto').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $(".nombre-activo").html(`Activo fijo: <b>${nombre_producto}</b>`);

  table_show_hide(2);     

	tabla_factura = $('#tbla-facura').dataTable({
		responsive: true,
		lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
		aProcessing: true,//Activamos el procesamiento del datatables
		aServerSide: true,//Paginación y filtrado realizados por el servidor
		dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
		buttons: [	],
		ajax:	{
      url: `../ajax/resumen_activos_fijos_general.php?op=tbla_facturas&idproducto=${idproducto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e)
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: Cantidad
      if (data[6] != '') { $("td", row).eq(6).addClass("text-center"); }
      // columna: Precio promedio
      if (data[7] != '') { $("td", row).eq(7).addClass("text-right h5"); }
      // columna: Precio actual
      if (data[8] != '') { $("td", row).eq(8).addClass("text-right"); }      
      if (data[9] != '') { $("td", row).eq(9).addClass("text-right"); }
    },
		language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
		bDestroy: true,
		iDisplayLength: 10,//Paginación
		order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs:[       
      { targets: [5], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [8,9], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },

    ]
	}).DataTable();  

  $.post("../ajax/resumen_activos_fijos_general.php?op=sumas_factura_x_material", { 'idproducto': idproducto }, function (e, status) {

    e = JSON.parse(e);  //console.log(e); 

    if (e.status) {

      if (e.data.cantidad == null || e.data.cantidad == '') {
        $(".cantidad_x_producto").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".cantidad_x_producto").html( formato_miles(e.data.cantidad));
      }

      if (e.data.precio_promedio == null || e.data.precio_promedio == '') {
        $(".precio_promedio").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".precio_promedio").html( formato_miles(e.data.precio_promedio));
      }

      if (e.data.descuento == null || e.data.descuento == '') {
        $(".descuento_x_producto").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".descuento_x_producto").html(  formato_miles(e.data.descuento));
      }

      if (e.data.subtotal == null || e.data.subtotal == '') {
        $('.subtotal_x_producto').html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $('.subtotal_x_producto').html(formato_miles(e.data.subtotal));
      }

    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );

}

// LIMPIAR FORM
function limpiar_form_compra() {
  $(".tooltip").removeClass("show").addClass("hidde");

  //Mostramos los select2Proveedor
  //$.post("../ajax/compra_insumos.php?op=select2Proveedor", function (r) { $("#idproveedor").html(r);  });

  $("#idcompra_proyecto").val();
  $("#idproyecto").val();
  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprobante").val("Ninguno").trigger("change");
  $("#glosa").val("null").trigger("change");

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

// EDITAR - PRODUCTOS COMPRA
function editar_detalle_compras( id, op) {
  op_guardar_compras = op;
  if (op == 'ActivosFijos') {
    $("#detalles thead").removeClass('bg-color-ff6c046b').addClass('bg-color-127ab6ba');    
  } else {
    $("#detalles thead").removeClass('bg-color-127ab6ba').addClass('bg-color-ff6c046b');
  }  

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  table_show_hide(3);

  limpiar_form_compra();

  array_class_trabajador = [];

  cont = 0;  detalles = 0;

  tbla_materiales(op)

  $.post(`../ajax/resumen_activos_fijos_general.php?op=ver_compra_editar_${op}`, { 'idcompra': id }, function (e, status) {
    
    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {

      $(".subtotal").html("");   $(".igv_comp").html("");  $(".total").html("");

      if (e.data.tipo_comprobante == "Factura") {
        $(".content-igv").show();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        $(".content-serie-comprobante").show();
      } else if (e.data.tipo_comprobante == "Boleta" || e.data.tipo_comprobante == "Nota de venta") {
        $(".content-serie-comprobante").show();
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      } else if (e.data.tipo_comprobante == "Ninguno") {
        $(".content-serie-comprobante").hide();
        $(".content-serie-comprobante").val("");
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
        $(".content-serie-comprobante").show();
      }      
      
      $("#idproveedor").val(e.data.idproveedor).trigger("change");
      $("#fecha_compra").val(e.data.fecha_compra);
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
      $("#serie_comprobante").val(e.data.serie_comprobante).trigger("change");
      $("#val_igv").val(e.data.val_igv);
      $("#descripcion").val(e.data.descripcion);
      $("#glosa").val(e.data.glosa).trigger("change");

      if (e.data.idproyecto == null || e.data.idproyecto == "") {
        $("#idcompra_af_general").val(e.data.idcompra_af_general);
        $("#idcompra_proyecto").val("");
        $("#idproyecto").val("");
        $('.detraccion_visible').hide();
      } else {
        $("#idcompra_proyecto").val(e.data.idcompra_x_proyecto);
        $("#idcompra_af_general").val("");
        $("#idproyecto").val(e.data.idproyecto);

        $('.detraccion_visible').show();

        if (e.data.estado_detraccion == 0) {
          $("#estado_detraccion").val("0");
          $('#my-switch_detracc').prop('checked', false); 
        } else {
          $("#estado_detraccion").val("1");
          $('#my-switch_detracc').prop('checked', true); 
        }
      }            

      if (e.data.producto.length === 0) {
        toastr.error("<h3>Sin productos.</h3> <br> Este registro no tiene productos para mostrar");
        $(".subtotal").html("S/ 0.00");
        $(".igv_comp").html("S/ 0.00");
        $(".total").html("S/ 0.00");
      } else {

        e.data.producto.forEach((element, index) => {

          var img = "";

          if (element.imagen == "" || element.imagen == null) {
            img = "../dist/docs/material/img_perfil/producto-sin-foto.svg";
          } else {
            img = `../dist/docs/material/img_perfil/${element.imagen}`;
          }

          var fila = `
          <tr class="filas" id="fila${cont}">
            <td>
              <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_material(${element.idproducto}, ${cont})" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>
              <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})" data-toggle="tooltip" data-original-title="Eliminar"><i class="fas fa-times"></i></button></td>
            </td>
            <td>
              <input type="hidden" name="idproducto[]" value="${element.idproducto}">
              <input type="hidden" name="ficha_tecnica_producto[]" value="${element.ficha_tecnica_producto}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_material('${img}', '${encodeHtml(element.nombre_producto)}', ${cont})" data-toggle="tooltip" data-original-title="Ver imagen">
                <span class="username"><p class="mb-0 nombre_producto_${cont}" >${element.nombre_producto}</p></span>
                <span class="description color_${cont}"><b>Color: </b>${element.color}</span>
              </div>
            </td>
            <td> <span class="unidad_medida_${cont}">${element.unidad_medida}</span> <input class="unidad_medida_${cont}" type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${element.unidad_medida}"> <input class="color_${cont}" type="hidden" name="nombre_color[]" id="nombre_color[]" value="${element.color}"></td>
            <td class="form-group"><input class="producto_${element.idproducto} producto_selecionado w-100px cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" value="${element.cantidad}" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="hidden"><input class="w-135px input-no-border precio_sin_igv_${cont}" type="number" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${element.precio_sin_igv}" readonly ></td>
            <td class="hidden"><input class="w-135px input-no-border precio_igv_${cont}" type="number"  name="precio_igv[]" id="precio_igv[]" value="${element.igv}" readonly ></td>
            <td class="form-group"><input type="number" class="w-135px precio_con_igv_${cont} form-control" type="number"  name="precio_con_igv[]" id="precio_con_igv[]" value="${parseFloat(element.precio_con_igv).toFixed(2)}" min="0.01" required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
            <td><input type="number" class="w-135px descuento_${cont}" name="descuento[]" value="${element.descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">0.00</span></td>
            <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm" data-toggle="tooltip" data-original-title="Actualizar precios"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles").append(fila);

          array_class_trabajador.push({ id_cont: cont });

          cont++;
          evaluar();
          $('[data-toggle="tooltip"]').tooltip();
        });

        modificarSubtotales();
      }
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

// AGREGAR - PRODUCTOS COMPRA
function agregarDetalleComprobante(idproducto, nombre, unidad_medida, nombre_color, precio_sin_igv, precio_igv, precio_total, img, ficha_tecnica_producto) {
  var stock = 5;
  var cantidad = 1;
  var descuento = 0;

  if (idproducto != "") {
    // $('.producto_'+idproducto).addClass('producto_selecionado');
    if ($(".producto_" + idproducto).hasClass("producto_selecionado")) {
      toastr.success("Material: " + nombre + " agregado !!");

      var cant_producto = $(".producto_" + idproducto).val();

      var sub_total = parseInt(cant_producto, 10) + 1;

      $(".producto_" + idproducto).val(sub_total);

      modificarSubtotales();
    } else {

      if ($("#tipo_comprobante").select2("val") == "Factura") {
        var subtotal = cantidad * precio_total;
      } else {
        var subtotal = cantidad * precio_sin_igv;
      }

      var img_p = "";

      if (img == "" || img == null) {
        img_p = "../dist/docs/material/img_perfil/producto-sin-foto.svg";
      } else {
        img_p = `../dist/docs/material/img_perfil/${img}`;
      }

      var fila = `
      <tr class="filas" id="fila${cont}">         
        <td class="">
          <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_material(${idproducto}, ${cont})"><i class="fas fa-pencil-alt"></i></button>
          <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})"><i class="fas fa-times"></i></button>
        </td>
        <td class="">         
          <input type="hidden" name="idproducto[]" value="${idproducto}">
          <input type="hidden" name="ficha_tecnica_producto[]" value="${ficha_tecnica_producto}">
          <div class="user-block text-nowrap">
            <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img_p}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_material('${img_p}', '${encodeHtml(nombre)}', ${cont})" data-toggle="tooltip" data-original-title="Ver imagen">
            <span class="username"><p class="mb-0 nombre_producto_${cont}">${nombre}</p></span>
            <span class="description color_${cont}"><b>Color: </b>${nombre_color}</span>
          </div>
        </td>
        <td class=""><span class="unidad_medida_${cont}">${unidad_medida}</span> <input class="unidad_medida_${cont}" type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${unidad_medida}"><input class="color_${cont}" type="hidden" name="nombre_color[]" id="nombre_color[]" value="${nombre_color}"></td>
        <td class=" form-group"><input class="producto_${idproducto} producto_selecionado w-100px cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" value="${cantidad}" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
        <td class=" hidden"><input type="number" class="w-135px input-no-border precio_sin_igv_${cont}" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${parseFloat(precio_sin_igv).toFixed(2)}" readonly min="0" ></td>
        <td class=" hidden"><input class="w-135px input-no-border precio_igv_${cont}" type="number" name="precio_igv[]" id="precio_igv[]" value="${parseFloat(precio_igv).toFixed(2)}" readonly  ></td>
        <td class="form-group"><input class="w-135px precio_con_igv_${cont} form-control" type="number" name="precio_con_igv[]" id="precio_con_igv[]" value="${parseFloat(precio_total).toFixed(2)}" min="0.01" required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
        <td class=""><input type="number" class="w-135px descuento_${cont}" name="descuento[]" value="${descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
        <td class=" text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">${subtotal}</span></td>
        <td class=""><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm"><i class="fas fa-sync"></i></button></td>
      </tr>`;

      detalles = detalles + 1;

      $("#detalles").append(fila);

      array_class_trabajador.push({ id_cont: cont });

      modificarSubtotales();

      toastr.success("Material: " + nombre + " agregado !!");

      cont++;
      evaluar();
      $('[data-toggle="tooltip"]').tooltip();
    }
  } else {
    // alert("Error al ingresar el detalle, revisar los datos del artículo");
    toastr.error("Error al ingresar el detalle, revisar los datos del material.");
  }
}

function evaluar() {
  if (detalles > 0) {
    $("#guardar_registro_compras").show();
  } else {
    $("#guardar_registro_compras").hide();
    cont = 0;
    $(".subtotal_compra").html("S/ 0.00");
    $("#subtotal_compra").val(0);

    $(".igv_compra").html("S/ 0.00");
    $("#igv_compra").val(0);

    $(".total_venta").html("S/ 0.00");
    $("#total_compra").val(0);

  }
}

function default_val_igv() { if ($("#tipo_comprobante").select2("val") == "Factura") { $("#val_igv").val(0.18); } }


function modificarSubtotales() {  

  var val_igv = $('#val_igv').val(); //console.log(array_class_trabajador);

  if ($("#tipo_comprobante").select2("val") == null) {

    $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

    $("#colspan_subtotal").attr("colspan", 5); //cambiamos el: colspan

    $("#val_igv").val(0);
    $("#val_igv").prop("readonly",true);
    $(".val_igv").html('IGV (0%)');

    $("#tipo_gravada").val('NO GRAVADA');
    $(".tipo_gravada").html('NO GRAVADA');

    if (array_class_trabajador.length === 0) {
    } else {
      array_class_trabajador.forEach((element, index) => {
        var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
        var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
        var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
        var subtotal_producto = 0;

        // Calculamos: IGV
        var precio_sin_igv = precio_con_igv;
        $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

        // Calculamos: precio + IGV
        var igv = 0;
        $(`.precio_igv_${element.id_cont}`).val(igv);

        // Calculamos: Subtotal de cada producto
        subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
        $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(4)));
      });
      calcularTotalesSinIgv();
    }
  } else {
    if ($("#tipo_comprobante").select2("val") == "Factura") {

      $(".hidden").show(); //Mostramos: IGV, PRECIO SIN IGV

      $("#colspan_subtotal").attr("colspan", 7); //cambiamos el: colspan
      
      $("#val_igv").prop("readonly",false);

      if (array_class_trabajador.length === 0) {
        if (val_igv == '' || val_igv <= 0) {
          $("#tipo_gravada").val('NO GRAVADA');
          $(".tipo_gravada").html('NO GRAVADA');
          $(".val_igv").html(`IGV (0%)`);
        } else {
          $("#tipo_gravada").val('GRAVADA');
          $(".tipo_gravada").html('GRAVADA');
          $(".val_igv").html(`IGV (${(parseFloat(val_igv) * 100).toFixed(2)}%)`);
        }
        
      } else {
        // validamos el valor del igv ingresado        

        array_class_trabajador.forEach((element, index) => {
          var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
          var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
          var subtotal_producto = 0;

          // Calculamos: Precio sin IGV
          var precio_sin_igv = ( quitar_igv_del_precio(precio_con_igv, val_igv, 'decimal')).toFixed(2);
          $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

          // Calculamos: IGV
          var igv = (parseFloat(precio_con_igv) - parseFloat(precio_sin_igv)).toFixed(2);
          $(`.precio_igv_${element.id_cont}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
          $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(2)));
        });

        calcularTotalesConIgv();
      }
    } else {

      $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

      $("#colspan_subtotal").attr("colspan", 5); //cambiamos el: colspan

      $("#val_igv").val(0);
      $("#val_igv").prop("readonly",true);
      $(".val_igv").html('IGV (0%)');

      $("#tipo_gravada").val('NO GRAVADA');
      $(".tipo_gravada").html('NO GRAVADA');

      if (array_class_trabajador.length === 0) {
      } else {
        array_class_trabajador.forEach((element, index) => {
          var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
          var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
          var subtotal_producto = 0;

          // Calculamos: IGV
          var precio_sin_igv = precio_con_igv;
          $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

          // Calculamos: precio + IGV
          var igv = 0;
          $(`.precio_igv_${element.id_cont}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
          $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(4)));
        });

        calcularTotalesSinIgv();
      }
    }
  }
  toastr.success("Precio Actualizado !!!");
}

function calcularTotalesSinIgv() {
  var total = 0.0;
  var igv = 0;
  var mtotal = 0;

  if (array_class_trabajador.length === 0) {
  } else {
    array_class_trabajador.forEach((element, index) => {
      total += parseFloat(quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()));
    });

    $(".subtotal_compra").html("S/ " + formato_miles(total));
    $("#subtotal_compra").val(redondearExp(total, 4));

    $(".igv_compra").html("S/ 0.00");
    $("#igv_compra").val(0.0);
    $(".val_igv").html('IGV (0%)');

    $(".total_venta").html("S/ " + formato_miles(total.toFixed(2)));
    $("#total_venta").val(redondearExp(total, 4));
  }
}

function calcularTotalesConIgv() {
  var val_igv = $('#val_igv').val();
  var igv = 0;
  var total = 0.0;

  var subotal_sin_igv = 0;

  array_class_trabajador.forEach((element, index) => {
    total += parseFloat(quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()));
  });

  //console.log(total); 

  subotal_sin_igv = quitar_igv_del_precio(total, val_igv, 'decimal').toFixed(2);
  igv = (parseFloat(total) - parseFloat(subotal_sin_igv)).toFixed(2);

  $(".subtotal_compra").html(`S/ ${formato_miles(subotal_sin_igv)}`);
  $("#subtotal_compra").val(redondearExp(subotal_sin_igv, 4));

  $(".igv_compra").html("S/ " + formato_miles(igv));
  $("#igv_compra").val(igv);

  $(".total_venta").html("S/ " + formato_miles(total.toFixed(2)));
  $("#total_venta").val(redondearExp(total, 4));

  total = 0.0;
}

function quitar_igv_del_precio(precio , igv, tipo ) {
  
  var precio_sin_igv = 0;

  switch (tipo) {
    case 'decimal':

      // validamos el valor del igv ingresado
      if (igv > 0 && igv <= 1) { 
        $("#tipo_gravada").val('GRAVADA');
        $(".tipo_gravada").html('GRAVADA');
        $(".val_igv").html(`IGV (${(parseFloat(igv) * 100).toFixed(2)}%)`); 
      } else { 
        igv = 0; 
        $(".val_igv").html('IGV (0%)'); 
        $("#tipo_gravada").val('NO GRAVADA');
        $(".tipo_gravada").html('NO GRAVADA');
      }

      if (parseFloat(precio) != NaN && igv > 0 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( ( parseFloat(igv) * 100 ) + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;

    case 'entero':
      
      // validamos el valor del igv ingresado
      if (igv > 0 && igv <= 100) { 
        $("#tipo_gravada").val('GRAVADA');
        $(".tipo_gravada").html('GRAVADA');
        $(".val_igv").html(`IGV (${parseFloat(igv)}%)`); 
      } else { 
        igv = 0; 
        $(".val_igv").html('IGV (0%)'); 
        $("#tipo_gravada").val('NO GRAVADA');
        $(".tipo_gravada").html('NO GRAVADA');
      }

      if (parseFloat(precio) != NaN && igv > 0 ) {
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

function ocultar_comprob() {
  if ($("#tipo_comprobante").select2("val") == "Ninguno") {
    $("#content-serie-comprobante").hide();

    $("#content-serie-comprobante").val("");

    $("#content-descripcion").removeClass("col-lg-5").addClass("col-lg-7");
  } else {
    $("#content-serie-comprobante").show();

    $("#content-descripcion").removeClass("col-lg-7").addClass("col-lg-5");
  }
}

function eliminarDetalle(indice) {
  $("#fila" + indice).remove();

  array_class_trabajador.forEach(function (car, index, object) {
    if (car.id_cont === indice) {
      object.splice(index, 1);
    }
  });

  modificarSubtotales();

  detalles = detalles - 1;

  evaluar();

  toastr.warning("Material removido.");
}

// MOSTRAR - IMAGEN GRANDE PRODUCTO
function ver_img_material(img_url, nombre, cont = null) {  
  $("#modal-ver-img-material").modal("show");

  $(".tooltip").removeClass("show").addClass("hidde");
  if (cont == null || cont == "") {
    $("#ver_img_material").attr("src", img_url);
    $(".nombre-img-material").html(nombre);        
  } else {
    var img_peril = $(`.img_perfil_${cont}`).attr("src");
    $("#ver_img_material").attr("src", `${img_peril}`);
    $(".nombre-img-material").html(nombre);
  }
}

//GUARDAR - COMPRAS
function guardar_y_editar_compras(e) {
   
  var formData = new FormData($("#form-compras")[0]);

  var swal2_header = `<img class="swal2-image bg-color-252e38 b-radio-7px p-15px m-10px" src="../dist/gif/cargando.gif">`;

  var swal2_content = `<div class="row sweet_loader" >    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
      <div class="progress" id="div_barra_progress">
        <div id="barra_progress" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%;">
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
        url: `../ajax/resumen_activos_fijos_general.php?op=guardar_y_editar_compra_${op_guardar_compras}`,
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
        },
        success: function (e) {
          try {
            e = JSON.parse(e);
            if (e.status == true) {
              // toastr.success("Usuario registrado correctamente");
              Swal.fire("Correcto!", "Compra guardada correctamente", "success");

              tbla_facuras( idproducto_r, nombre_producto_r, precio_promedio_r, subtotal_x_producto_r );

              tbla_principal_maquinaria();
              tbla_principal_equipo();
              tbla_principal_herramienta();
              tbla_principal_oficina();

              limpiar_form_compra();

              table_show_hide(2);  cont = 0;

              l_m();

            } else {
              l_m();
              ver_errores(e)
            }
          } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }
        },
        xhr: function () {

          var xhr = new window.XMLHttpRequest();
    
          xhr.upload.addEventListener("progress", function (evt) {
    
            if (evt.lengthComputable) {
    
              var percentComplete = (evt.loaded / evt.total)*100; console.log(percentComplete + '%');
              
              $("#barra_progress").css({"width": percentComplete+'%'});
    
              $("#barra_progress").text(percentComplete.toFixed(2)+" %");
            }
          }, false);
           
          return xhr;
        }
      });
    }
  });
}

//MOSTRAMOS - DETALLE DE LA COMPRA
function ver_detalle_compras(idcompra_proyecto, op) {

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-compras").modal("show");

  $.post(`../ajax/resumen_activos_fijos_general.php?op=ver_detalle_compras_${op}&id_compra=${idcompra_proyecto}`, function (r) {
    $(".detalle_de_compra").html(r); 
    $('[data-toggle="tooltip"]').tooltip();

    $("#cargando-5-fomulario").show();
    $("#cargando-6-fomulario").hide();   

    $("#print_pdf_compra").removeClass('disabled');    
    $("#excel_compra").removeClass('disabled');
    if (op == 'Insumos') {
      $("#print_pdf_compra").attr('href', `../reportes/pdf_compra_activos_fijos.php?id=${idcompra_proyecto}&op=insumo` );
    } else {
      $("#print_pdf_compra").attr('href', `../reportes/pdf_compra_activos_fijos.php?id=${idcompra_proyecto}&op=activo_fijo` );
    }
    
  });
}

//Detraccion
$("#my-switch_detracc").on("click ", function (e) {
  if ($("#my-switch_detracc").is(":checked")) { $("#estado_detraccion").val("1"); } else { $("#estado_detraccion").val("0"); }
});
// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION COMPROBANTES FACTURAS ::::::::::::::::::::::::::::::::::::::::::::::::::::


function comprobantes_compras(idcompra_proyecto,num_orden, num_comprobante,fecha) {
  // limpiar_form_comprobante();
  tbla_comprobantes_compras(idcompra_proyecto, num_orden);

  $("#id_compra_proyecto").val(idcompra_proyecto);

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
      url: `../ajax/resumen_activos_fijos.php?op=tbla_comprobantes_compra&id_compra=${id_compra}&num_orden=${num_orden}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    }, 
    createdRow: function (row, data, ixdex) {
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center"); }
      if (data[2] != '') { $("td", row).eq(2).addClass("text-center"); }
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
function comprobante_unico(imagen, num_orden,num_comprobante,fecha) {
  $("#detalle_comprobante").html(num_comprobante+'  '+fecha)
  $("#img-vaucher").attr("src", "");
  $("#modal-ver-vaucher").modal("show");
  $("#img-vaucher").attr("src", "../dist/docs/compra_activo_fijo/comprobante_compra/" + imagen);
  $("#descargar_voucher_pago").attr("href", "../dist/docs/compra_activo_fijo/comprobante_compra/" + imagen);
  $("#descargar_voucher_pago").attr("download", `Vaucher pago: activo fijo - ${num_comprobante} - ${fecha}`);

  $("#ver_completo_voucher_pago").attr("href", "../dist/docs/compra_activo_fijo/comprobante_compra/" + imagen);

  $(".tooltip").remove();
}




// ::::::::::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::::::::

$(function () {
  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").on('change', function() { $(this).trigger('blur'); });
  $("#glosa").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_comprobante").on('change', function() { $(this).trigger('blur'); });
  $("#banco_prov").on('change', function() { $(this).trigger('blur'); });
  $("#categoria_insumos_af_p").on('change', function() { $(this).trigger('blur'); });
  $("#color_p").on('change', function() { $(this).trigger('blur'); });
  $("#unidad_medida_p").on('change', function() { $(this).trigger('blur'); });
  $('#marca_p').on('change', function() { $(this).trigger('blur'); });

  // validando form compras 
  $("#form-compras").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproveedor:      { required: true },
      tipo_comprobante: { required: true },
      serie_comprobante:{ minlength: 2 },
      descripcion:      { minlength: 4 },
      fecha_compra:     { required: true },
      glosa:            { required: true },
      val_igv:          { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idproveedor:      { required: "Campo requerido", },
      tipo_comprobante: { required: "Campo requerido", },
      serie_comprobante:{ minlength: "mayor a 2 caracteres", },
      descripcion:      { minlength: "mayor a 4 caracteres", },
      fecha_compra:     { required: "Campo requerido", },
      glosa:            { required: "Campo requerido", },
      val_igv:          { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
      'cantidad[]':     { min: "Mínimo 0.01", required: "Campo requerido"},
      'precio_con_igv[]':{ min: "Mínimo 0.01", required: "Campo requerido"}
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
  
  //Validar formulario PROVEEDOR
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
      tipo_documento_prov:  { required: "Campo requerido", },
      num_documento_prov:   { required: "Campo requerido", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre_prov:          { required: "Campo requerido", minlength: "MÍNIMO 3 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      direccion_prov:       { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 150 caracteres.", },
      telefono_prov:        { minlength: "MÍNIMO 9 caracteres.", },
      c_bancaria_prov:      { minlength: "MÍNIMO 6 caracteres.", },
      cci_prov:             { minlength: "MÍNIMO 6 caracteres.",  },
      c_detracciones_prov:  { minlength: "MÍNIMO 6 caracteres.", },      
      banco_prov:           { required: "Por favor  seleccione un banco",  },
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

  $("#form-materiales").validate({
    rules: {
      nombre_p: { required: true, minlength:3, maxlength:200},
      categoria_insumos_af_p: { required: true },
      color_p: { required: true },
      unidad_medida_p: { required: true },
      marca_p: { required: true },
      modelo_p: { minlength: 3 },  
      precio_unitario_p: { required: true },
      descripcion_p: { minlength: 3 },
    },
    messages: {
      nombre_p: { required: "Por favor ingrese nombre", minlength:"Minimo 3 caracteres", maxlength:"Maximo 200 caracteres" },
      categoria_insumos_af_p: { required: "Campo requerido", },
      color_p: { required: "Campo requerido" },
      unidad_medida_p: { required: "Campo requerido" },
      marca_p: { required: "Campo requerido" },
      modelo_p: { minlength: "Minimo 3 caracteres", },
      precio_unitario_p: { required: "Ingresar precio compra", },      
      descripcion_p: { minlength: "Minimo 3 caracteres" },
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

  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#glosa").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_comprobante").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco_prov").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#categoria_insumos_af_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#color_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#unidad_medida_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $('#marca_p').rules('add', { required: true, messages: {  required: "Campo requerido" } });

});

function l_m() {

  $("#barra_progress").css({ width: "0%" });

  $("#barra_progress").text("0%");

  $("#barra_progress2").css({ width: "0%" });

  $("#barra_progress2").text("0%");
}

// ::::::::::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::::::::

function dowload_pdf() {
  toastr.success("El documento se descargara en breve!!");
}

// ver imagen grande del producto agregado a la compra
function ver_img_activo(img, nombre) {
  console.log(img, nombre);
  $("#ver_img_activo").attr("src", `${img}`);
  $(".nombre-img-activo").html(nombre);
  $("#modal-ver-img-activo").modal("show");
}

function export_excel_detalle_factura() {
  $tabla = document.querySelector("#tabla_detalle_factura");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Detalle comprobante", //Nombre del archivo de Excel
    sheetname: "detalle factura", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.tabla_detalle_factura.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}

function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { 
    $('.btn-editar-proveedor').addClass('disabled').attr('data-original-title','Seleciona un proveedor');
    $('.btn-editar-proveedor').removeAttr('onclick');

  } else { 
    if ($('#idproveedor').select2("val") == 1) {
      $('.btn-editar-proveedor').addClass('disabled').attr('data-original-title','No editable');
    $('.btn-editar-proveedor').removeAttr('onclick');

      var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
      $('#ruc_proveedor').val(ruc);

    } else{
      var name_proveedor = $('#idproveedor').select2('data')[0].text;
      $('.btn-editar-proveedor').removeClass('disabled').attr('data-original-title',`Editar: ${recorte_text(name_proveedor, 15)}`);   
      var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
      $('#ruc_proveedor').val(ruc);
    $('.btn-editar-proveedor').attr('onclick', 'mostrar_para_editar_proveedor(this);');

    }
  }
  $('[data-toggle="tooltip"]').tooltip();
}

init();