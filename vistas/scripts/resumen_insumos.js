var dt_tabla_principal;
var tabla_factura;
var tabla_materiales;
var tabla_comprobantes;

var array_class_trabajador = [];
var cont = 0;
var detalles = 0;

var idproyecto_r = "", idproducto_r = "", nombre_producto_r = "", precio_promedio_r = "", subtotal_x_producto_r = "";

//Función que se ejecuta al inicio
function init(){

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

	$("#mCompra").addClass("active bg-primary");

	$("#lResumenInsumos").addClass("active");
	
	tbla_principal(localStorage.getItem('nube_idproyecto'));
  tbla_materiales();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_prov', null);
  lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2Categoria_all", '#categoria_insumos_af_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2Marcas", '#marcas_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2ClasificacionGrupo", '#idclasificacion_grupo_g', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_compras").on("click", function (e) {  $("#submit-form-compras").submit(); });
  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });
  $("#guardar_registro_material").on("click", function (e) {  $("#submit-form-materiales").submit(); });
  $("#guardar_registro_grupos").on("click", function (e) {  $("#submit-form-grupos").submit(); });

  // ═══════════════════ SELECT2 - COMPRAS ═══════════════════
  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione trabajador", allowClear: true, });
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione Comprobante", allowClear: true, });
  $("#glosa").select2({ templateResult: templateGlosa, theme: "bootstrap4", placeholder: "Selecione Glosa", allowClear: true, });

  // ═══════════════════ SELECT2 - PROVEEDOR ═══════════════════
  //Initialize Select2 BANCO PROVEEDOR
  $("#banco_prov").select2({ templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione banco", allowClear: true, });

  // ═══════════════════ SELECT2 - MATERIAL ═══════════════════
  $("#categoria_insumos_af_p").select2({ theme: "bootstrap4", placeholder: "Seleccinar Clasificación", allowClear: true, });
  $("#marcas_p").select2({placeholder: "Seleccinar marcas"});
  $("#unidad_medida_p").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });  

  $("#idclasificacion_grupo_g").select2({ theme: "bootstrap4", placeholder: "Seleccinar una Grupo", allowClear: true, });

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

// Eliminamos el doc FOTO PERFIL MATERIAL
function foto2_eliminar() {
  $("#foto2").val("");
  $("#ver_pdf").html("");

  $("#foto2_i").attr("src", "../dist/img/default/img_defecto_activo_fijo_material.png");

  $("#foto2_nombre").html("");
  $("#foto2_i").show();
}

// Eliminamos el doc FICHA TECNICA
function doc2_eliminar() {
  $("#doc2").val("");

  $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  $("#doc2_nombre").html("");
}

// OCULTAR MOSTRAR - TABLAS
function table_show_hide(flag) {
  if (flag == 1) {
    // tabla principal
    $(".mensaje-tbla-principal").show();
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();
    $("#guardar_registro_compras").hide();    

    $(".nombre-insumo").html(`<img src="../dist/svg/negro-palana-ico.svg" class="nav-icon" alt="" style="width: 21px !important;"> Resumen de Insumos`);

    $("#tabla-principal").show();
    $("#tabla-factura").hide();
    $("#tabla-editar-factura").hide();
  } else if (flag == 2) {
    // ver editar compra insumos
    $(".mensaje-tbla-principal").hide();
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").show();
    $("#btn-regresar-bloque").show();   
    $("#guardar_registro_compras").hide();      

    $("#tabla-principal").hide();
    $("#tabla-factura").hide();
    $("#tabla-editar-factura").show(); 
  }else if (flag == 3) {
    // tabla factura
    $(".mensaje-tbla-principal").hide();
    $("#btn-regresar").show();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();
    $("#guardar_registro_compras").hide();

    $("#tabla-principal").hide();
    $("#tabla-factura").show();
    $("#tabla-editar-factura").hide();
  }
}

// TABLA - PRINCIPAL
function tbla_principal(id_proyecto) {
	dt_tabla_principal =$('#tbla-resumen-insumos').dataTable({
		responsive: true,
		lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
		aProcessing: true,//Activamos el procesamiento del datatables
	  aServerSide: true,//Paginación y filtrado realizados por el servidor
	  dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	  buttons: [ 
      { extend: 'copyHtml5', footer: true,exportOptions: { columns: [0,2,3,4,5,6,9,10,11], }  }, 
      { extend: 'excelHtml5', footer: true,exportOptions: { columns: [0,2,3,4,5,6,9,10,11], } }, 
      { extend: 'pdfHtml5', footer: true,exportOptions: { columns: [0,2,3,4,5,6,9,10,11], }, orientation: 'landscape', pageSize: 'LEGAL', }
    ],
		ajax:	{
      url: '../ajax/resumen_insumos.php?op=tbla_principal&id_proyecto='+id_proyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	 ver_errores(e);
      }
		},
    createdRow: function (row, data, ixdex) {  
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: op
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: UM
      if (data[6] != '') { $("td", row).eq(6).addClass("text-center"); }
     //columna:cantidad
      if (data[7] != '') { $("td", row).eq(7).addClass("text-center");   }
      // columna: Compra
      if (data[8] != '') { $("td", row).eq(8).addClass("text-center");  }
      // columna: Precio promedio
      if (data[9] != '') { $("td", row).eq(9).addClass("text-right"); }
      // columna: Precio actual
      if (data[10] != '') { $("td", row).eq(10).addClass("text-right");  }
      // columna: Suma Total
      if (data[11] != '') { $("td", row).eq(11).addClass("text-right");  }

    },
		language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
		bDestroy: true,
		iDisplayLength: 10,//Paginación
	  //order: [[ 0, "desc" ]]//Ordenar (columna,orden)
    columnDefs:[ 
      // { "targets": [ 3 ], "visible": false, "searchable": false }, 
      { targets: [9,10,11], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ]
	}).DataTable();

  $.post("../ajax/resumen_insumos.php?op=suma_total_compras", { 'idproyecto': id_proyecto }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 

    if (e.status == true) {
      if (e.data.length === 0) {

        $(".suma_total_de_compras").html('<i class="far fa-frown fa-lg text-danger"></i>');
        $('.suma_total_productos').html('<i class="far fa-frown fa-lg text-danger"></i>');

      } else {
        if (e.data.suma_total_compras == null || e.data.suma_total_compras == '') {
          $(".suma_total_de_compras").html('<i class="far fa-frown fa-lg text-danger"></i>');
        } else {
          $(".suma_total_de_compras").html(formato_miles(e.data.suma_total_compras));
        }

        if (e.data.suma_total_productos == null || e.data.suma_total_productos == '') {
          $('.suma_total_productos').html('<i class="far fa-frown fa-lg text-danger"></i>');
        } else {
          $('.suma_total_productos').html(formato_miles(e.data.suma_total_productos));
        }
      }    
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } ); 
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION COMPRAS ::::::::::::::::::::::::::::::::::::::::::::::::::::

// TABLA - FACTURAS
function tbla_facuras( idproyecto, idproducto, nombre_producto, precio_promedio, subtotal_x_producto ) {

  idproyecto_r = idproyecto; idproducto_r = idproducto; nombre_producto_r = nombre_producto; 
  precio_promedio_r = precio_promedio; subtotal_x_producto_r = subtotal_x_producto;

  $(".cantidad_x_producto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.precio_promedio').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $(".descuento_x_producto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.subtotal_x_producto').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $(".nombre-insumo").html(`Producto: <b>${nombre_producto}</b>`);

  table_show_hide(3);	

	tabla_factura = $('#tbla-facura').dataTable({
		responsive: true,
		lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
		aProcessing: true,//Activamos el procesamiento del datatables
		aServerSide: true,//Paginación y filtrado realizados por el servidor
		dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
		buttons: [ 
      { extend: 'copyHtml5', footer: true,exportOptions: { columns: [0,2,10,11,4,5,6,8]} }, 
      { extend: 'excelHtml5', footer: true,exportOptions: { columns: [0,2,10,11,4,5,6,8]} }, 
      { extend: 'pdfHtml5', footer: true,exportOptions: { columns: [0,2,10,11,4,5,6,8]}, orientation: 'landscape', pageSize: 'LEGAL', }
    ],
		ajax:	{
      url: `../ajax/resumen_insumos.php?op=tbla_facturas&idproyecto=${idproyecto}&idproducto=${idproducto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: Cantidad
      if (data[6] != '') { $("td", row).eq(6).addClass("text-center"); }
      // columna: Precio promedio
      if (data[7] != '') { $("td", row).eq(7).addClass("text-bold h5"); }
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
      { targets: [5], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [7,8,9], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      { targets: [11,12], visible: false, searchable: false, },
    ],
	}).DataTable();  

  $.post("../ajax/resumen_insumos.php?op=sumas_factura_x_material", { 'idproyecto': idproyecto, 'idproducto': idproducto }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 

    if (e.status == true) {
      if (e.data.length === 0) {

        $(".cantidad_x_producto").html('<i class="far fa-frown fa-lg text-danger"></i>');
        $('.precio_promedio').html('<i class="far fa-frown fa-lg text-danger"></i>');
        $(".descuento_x_producto").html('<i class="far fa-frown fa-lg text-danger"></i>');
        $('.subtotal_x_producto').html('<i class="far fa-frown fa-lg text-danger"></i>');

      } else {
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
          $(".descuento_x_producto").html( formato_miles(e.data.descuento));
        }

        if (e.data.subtotal == null || e.data.subtotal == '') {
          $('.subtotal_x_producto').html('<i class="far fa-frown fa-lg text-danger"></i>');
        } else {
          $('.subtotal_x_producto').html(formato_miles(e.data.subtotal));
        }
      }    
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } ); 
}

// LIMPIAR FORM
function limpiar_form_compra() {
  $(".tooltip").removeClass("show").addClass("hidde");

  array_class_compra = [];

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
  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

//Función para guardar o editar - COMPRAS
function guardar_y_editar_compras(e) {
   
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
      return fetch("../ajax/resumen_insumos.php?op=guardar_y_editar_compra", {
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
        tbla_facuras( idproyecto_r, idproducto_r, nombre_producto_r, precio_promedio_r, subtotal_x_producto_r );
        tbla_principal(localStorage.getItem('nube_idproyecto'));
        limpiar_form_compra();
        table_show_hide(3);  cont = 0;
      } else {
        ver_errores(result.value);
      }      
    }    
  });
}

//mostramos el detalle del comprobante de la compras
function ver_detalle_compras(idcompra_proyecto, id_insumo) {

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-compras").modal("show");

  $.post(`../ajax/ajax_general.php?op=detalle_compra_de_insumo&id_compra=${idcompra_proyecto}&id_insumo=${id_insumo}`, function (e) {
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      $(".detalle_de_compra").html(e.data); 
      $("#cargando-5-fomulario").show();
      $("#cargando-6-fomulario").hide();

      $("#print_pdf_compra").removeClass('disabled');    
      $("#excel_compra").removeClass('disabled');
      $("#print_pdf_compra").attr('href', `../reportes/pdf_compra.php?id=${idcompra_proyecto}&op=insumo` );
    } else {
      ver_errores(e);
    }   
  }).fail( function(e) { ver_errores(e); } ); 
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION COMPROBANTES FACTURAS ::::::::::::::::::::::::::::::::::::::::::::::::::::

function comprobante_compras(idcompra_proyecto,num_orden, num_comprobante,fecha) {
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
      url: `../ajax/resumen_insumos.php?op=tbla_comprobantes_compra&id_compra=${id_compra}&num_orden=${num_orden}`,
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

// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION AGREGAR PRODUCTO ::::::::::::::::::::::::::::::::::::::::::::::::::::
// TABLA - MATERIALES
function tbla_materiales() {

  tabla_materiales = $("#tblamateriales").dataTable({
    responsive: true,
    lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [],
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
      if (data[3] != '') { $("td", row).eq(3).addClass('text-right'); }  
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

  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

//Función para guardar o editar
function guardar_materiales(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-materiales")[0]);

  $.ajax({
    url: "../ajax/resumen_insumos.php?op=guardar_materiales",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Producto guardado correctamente", "success");        
         
          dt_tabla_principal.ajax.reload(null, false);
  
          if (tabla_materiales) { tabla_materiales.ajax.reload(null, false); }
  
          actualizar_producto();
  
          $("#modal-agregar-material-activos-fijos").modal("hide");
        } else {
         ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }      

      $("#guardar_registro_material").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_insumo").css({"width": percentComplete+'%'});
          $("#barra_progress_insumo").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_material").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_insumo").css({ width: "0%",  });
      $("#barra_progress_insumo").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_insumo").css({ width: "0%", });
      $("#barra_progress_insumo").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// MOSTRAR PARA EDITAR
function mostrar_material(idproducto, cont) { 

  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();
  
  limpiar_materiales();  

  $("#modal-agregar-material-activos-fijos").modal("show");

  $.post("../ajax/resumen_insumos.php?op=mostrar_materiales", { 'idproducto_p': idproducto }, function (e, status) {
    
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
        // cargamos la imagen adecuada par el archivo
        $("#doc2_ver").html(doc_view_extencion(e.data.ficha_tecnica, 'material', 'ficha_tecnica', '100%','210') );
      } 

      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();
    } else {
      ver_errores(e);
    }      
    
  }).fail( function(e) { ver_errores(e); } ); 
}

function grupo_no_select() {
  if ($('#categoria_insumos_af_p').select2("val") == null || $('#idproveedor').select2("val") == '') { 
    $("#idtipo_tierra_concreto").attr("readonly", false);
  } else if ($('#categoria_insumos_af_p').select2("val") == '1') {     
    $("#idtipo_tierra_concreto").attr("readonly", false);   
  } else { 
    $("#idtipo_tierra_concreto").val("1").trigger("change");   
    $("#idtipo_tierra_concreto").attr("readonly", true);
  }
}

// DETALLE DEL MATERIAL
function mostrar_detalle_material(idproducto) {  
  
  $('#datosproductos').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div></div>`);

  var verdatos=''; var imagenver='';

  $("#modal-ver-detalle-material-activo-fijo").modal("show")

  $.post("../ajax/resumen_insumos.php?op=mostrar_materiales", { 'idproducto_p': idproducto }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    if (e.status) {     
    
      if (e.data.imagen != '') {

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
      
      } else {

        imagen_perfil=`<img src="../dist/docs/material/img_perfil/producto-sin-foto.svg" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`;
        btn_imagen_perfil='';

      }     

      if (e.data.ficha_tecnica != '') {
        
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
      
      } else {

        ficha_tecnica='Sin Ficha Técnica';
        btn_ficha_tecnica='';

      }     

      var marca =""; cont=0;
      e.data.marcas.forEach((valor, index) => {
        cont=cont+1;
        marca = marca.concat( `<span class="username">${cont } ${valor}</span> </br>`);
      });

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
                  <td> <b>U.M: </b> ${e.data.nombre_medida}</td>
                </tr>    
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Clasificación</th>
                  <td>${e.data.categoria}</td>
                </tr>                       
                   
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Marcas</th>
                  <td>${marca}</td>
                </tr>         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td><textarea cols="30" rows="2" class="textarea_datatable" readonly="">${e.data.descripcion}</textarea></td>
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

// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION AGREGAR GRUPOS ::::::::::::::::::::::::::::::::::::::::::::::::::::

function limpiar_form_grupos() {
  $("#idclasificacion_grupo_g").val('').trigger('change');
}

function agregar_grupos(id_producto, id_grupo) {
  $("#idproducto_g").val(id_producto);
  $("#idclasificacion_grupo_g").val(id_grupo).trigger('change');
  $('#modal-agregar-grupos').modal('show');
}

//Función para guardar o editar
function guardar_grupos(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-grupos")[0]);

  $.ajax({
    url: "../ajax/resumen_insumos.php?op=actualizar_grupo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Grupo guardado correctamente", "success");   
          if (dt_tabla_principal) { dt_tabla_principal.ajax.reload(null, false); }  
          $("#modal-agregar-grupos").modal("hide");
        } else {
         ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }      

      $("#guardar_registro_grupos").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_grupos").css({"width": percentComplete+'%'});
          $("#barra_progress_grupos").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_grupos").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_grupos").css({ width: "0%",  });
      $("#barra_progress_grupos").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_grupos").css({ width: "0%", });
      $("#barra_progress_grupos").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}
// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION AGREGAR PROVEEDOR ::::::::::::::::::::::::::::::::::::::::::::::::::::
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
  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");

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

        $(".chargue-format-1").html("Cuenta Bancaria");
        $(".chargue-format-2").html("CCI");
        $(".chargue-format-3").html("Cuenta Detracciones");
      } else {
        ver_errores(e);
      }
      
    }).fail( function(e) { ver_errores(e); } ); 
  }
}

//guardar proveedor
function guardar_proveedor(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);
  crud_guardar_editar_modal_select2_xhr( 
    "../ajax/resumen_insumos.php?op=guardar_proveedor", 
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

  $.post("../ajax/resumen_insumos.php?op=mostrar_editar_proveedor", { 'idproveedor': $('#idproveedor').select2("val") }, function (e, status) {

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

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").on('change', function() { $(this).trigger('blur'); });
  $("#glosa").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_comprobante").on('change', function() { $(this).trigger('blur'); });
  $("#banco_prov").on('change', function() { $(this).trigger('blur'); });
  $("#unidad_medida_p").on('change', function() { $(this).trigger('blur'); });
  $("#idclasificacion_grupo_g").on('change', function() { $(this).trigger('blur'); });
  $("#marcas_p").on('change', function() { $(this).trigger('blur'); });

  $("#form-compras").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproveedor:    { required: true },
      tipo_comprobante:{ required: true },
      serie_comprobante:{ minlength: 2 },
      descripcion:    { minlength: 4 },
      fecha_compra:   { required: true },
      glosa:          { required: true },
      val_igv:        { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idproveedor:    { required: "Campo requerido", },
      tipo_comprobante:{ required: "Campo requerido", },
      serie_comprobante:{ minlength: "mayor a 2 caracteres", },
      descripcion:    { minlength: "mayor a 4 caracteres", },
      fecha_compra:   { required: "Campo requerido", },
      glosa:          { required: "Campo requerido", },
      val_igv:        { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
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

  $("#form-proveedor").validate({
    //ignore: '.select2-input, .select2-focusser',
    rules: {
      tipo_documento_prov:{ required: true },
      num_documento_prov: { required: true, minlength: 6, maxlength: 20 },
      nombre_prov:      { required: true, minlength: 3, maxlength: 100 },
      direccion_prov:   { minlength: 5, maxlength: 150 },
      telefono_prov:    { minlength: 8 },
      c_bancaria_prov:  { minlength: 6,  },
      cci_prov:         { minlength: 6,  },
      c_detracciones_prov:{ minlength: 6,  },      
      banco_prov:       { required: true },
      titular_cuenta_prov:{ minlength: 4 },
    },
    messages: {
      tipo_documento_prov:{ required: "Por favor selecione un tipo de documento", },
      num_documento_prov: { required: "Campo requerido", minlength: "MÍNIMO 6 caracteres.",maxlength: "MÁXIMO 20 caracteres.", },
      nombre_prov:      { required: "Campo requerido", minlength: "MÍNIMO 3 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      direccion_prov:   { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 150 caracteres.", },
      telefono_prov:    { minlength: "MÍNIMO 9 caracteres.", },
      c_bancaria_prov:  { minlength: "MÍNIMO 6 caracteres.", },
      cci_prov:         { minlength: "MÍNIMO 6 caracteres.",  },
      c_detracciones_prov:{ minlength: "MÍNIMO 6 caracteres.", },      
      banco_prov:       { required: "Campo requerido",  },
      titular_cuenta_prov:{ minlength: 'MÍNIMO 4 caracteres.' },
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

    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_proveedor(e);
    },
  });   

  $("#form-materiales").validate({
    rules: {
      nombre_p:               { required: true, minlength:3, maxlength:200},     
      categoria_insumos_af_p: { required: true },
      unidad_medida_p:        { required: true },
      marcas_p:               { required: true },      
      descripcion_p:          { minlength: 3 },
    },
    messages: {
      nombre_p:               { required: "Por favor ingrese nombre", minlength:"Minimo 3 caracteres", maxlength:"Maximo 200 caracteres" }, 
      categoria_insumos_af_p: { required: "Campo requerido", },
      unidad_medida_p:        { required: "Campo requerido" },  
      marcas_p:               { required: "Campo requerido", },      
      descripcion_p:          { minlength: "Minimo 3 caracteres" },
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

    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_materiales(e);
    },
  });

  $("#form-grupos").validate({
    rules: {
      idclasificacion_grupo_g: { required: true },
    },
    messages: {
      idclasificacion_grupo_g: { required: "Campo requerido", },
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

    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_grupos(e);
    },
  });

  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#glosa").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_comprobante").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco_prov").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#unidad_medida_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#idclasificacion_grupo_g").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#marcas_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });

});

function l_m() {

  $("#barra_progress").css({ width: "0%" });

  $("#barra_progress").text("0%");

  $("#barra_progress2").css({ width: "0%" });

  $("#barra_progress2").text("0%");
}

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function dowload_pdf() {
  toastr.success("El documento se descargara en breve!!");
}

// ver imagen grande del producto agregado a la compra
function ver_img_material(img, nombre) {
  $("#ver_img_material").attr("src", `${ img}`);
  $(".nombre-img-material").html(nombre);
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