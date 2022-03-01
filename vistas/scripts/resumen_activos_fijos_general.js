//tbl maaquinaria
var tabla_maaquinaria1;
var tabla_equipo1;
var tabla_herramientas1;
var tabla_oficina1;
var tabla_factura;

var tablamateriales;
var array_class_trabajador = [];
array_class_activo_p = [];

//Función que se ejecuta al inicio
var selec_op;

function init(){
	
	listar_tbla_principal_maq();
	listar_tbla_principal_equip();
  listar_tbla_principal_herra();
  listar_tbla_principal_oficina();

	//$("#mResumenActivosFijosGeneral").addClass("menu-open");

	$("#mResumenActivosFijosGeneral").addClass("active");

  //Mostramos los proveedores
  $.post("../ajax/compra.php?op=select2Proveedor", function (r) { $("#idproveedor").html(r);  $("#idproveedor_proy").html(r); });
  // Mostra,ps los bancos
  $.post("../ajax/compra.php?op=select2Banco", function (r) {  $("#banco").html(r); });
  //Mostramos las categorias del producto
  $.post("../ajax/compra.php?op=select2Categoria", function (r) { $("#categoria_insumos_af_p").html(r); });
  //Mostramos colores
  $.post("../ajax/compra.php?op=select2Color", function (r) { $("#color_p").html(r); });

  //Mostramos las unidades de medida
  $.post("../ajax/compra.php?op=select2UnidaMedida", function (r) { $("#unidad_medida_p").html(r); });

	//$("#lResumenInsumos").addClass("active");

  // Guardar el registro de la compra
  $("#guardar_registro_compras").on("click", function (e) {  $("#submit-form-compras").submit(); });
    // guardar el registro de la compra por proyecto
    $("#guardar_registro_compras_p").on("click", function (e) { $("#submit-form-compra-activos-p").submit(); });

  //Guardar registro proveedor
  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });
  
  //Guardar Material
  $("#guardar_registro_material").on("click", function (e) {  $("#submit-form-materiales").submit(); });  


  //Initialize Select2 PROVEEDOR
  $("#idproveedor").select2({
    theme: "bootstrap4",
    placeholder: "Selecione trabajador",
    allowClear: true,
  });
  //Initialize Select2 Elements
  $("#idproveedor_proy").select2({
    theme: "bootstrap4",
    placeholder: "Selecione trabajador",
    allowClear: true,
  });
  //Initialize Select2 proyecto
  $("#tipo_comprobante_proy").select2({
    theme: "bootstrap4",
    placeholder: "Selecione Comprobante",
    allowClear: true,
  });
  //Initialize Select2 BANCO
  $("#banco").select2({
    theme: "bootstrap4",
    placeholder: "Selecione un banco",
    allowClear: true,
  });

  //Initialize Select2 BANCO
  $("#tipo_documento").select2({
    theme: "bootstrap4",
    placeholder: "Selecione tipo documento",
    allowClear: true,
  });

  //Initialize Select2 TIPO DE COMPROBANTE
  $("#tipo_comprovante").select2({
    theme: "bootstrap4",
    placeholder: "Selecione Comprobante",
    allowClear: true,
  });

  //Initialize Select2 CATEGORIA
  $("#categoria_insumos_af_p").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar color",
    allowClear: true,
  });

  //Initialize Select2 COLOR
  $("#color_p").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar color",
    allowClear: true,
  });

  //Initialize Select2 UNIDAD DE MEDIDA
  $("#unidad_medida_p").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar una unidad",
    allowClear: true,
  });
// Perfil material
$("#foto2_i").click(function () {  $("#foto2").trigger("click"); });
$("#foto2").change(function (e) { addImage(e, $("#foto2").attr("id")); });

//ficha tecnica
$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addDocs(e,$("#doc2").attr("id")) });

}

function ingresar_segundo_div() {

  $(".primer-div").hide();
  $(".segundo-div").show();
  $(".tercer-div").hide();
}
function regresar(){
  $(".primer-div").show();
  $(".segundo-div").hide();
  $(".tercer-div").hide();
  //tbl maaquinaria
   tabla_maaquinaria1.ajax.reload();
   tabla_equipo1.ajax.reload();
   tabla_herramientas1.ajax.reload();
   tabla_oficina1.ajax.reload();

}
function ingresar_tercer_div() {

  $(".primer-div").hide();
  $(".segundo-div").hide();
  $(".tercer-div").show();
  listarmateriales();

}
function regresar_div2() {

  $(".primer-div").hide();
  $(".segundo-div").show();
  $(".tercer-div").hide();
  selec_op="";

}

/**tipo clasificacion MAQUINARIA */
function listar_tbla_principal_maq()
{

	tabla_maaquinaria1=$('#tabla-resumen-maquinarias').dataTable({
		"responsive": true,
	lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
		"ajax":	{
      url: '../ajax/resumen_activos_fijos_general.php?op=listar_tbla_principal_maq',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
		},
    createdRow: function (row, data, ixdex) {    
      // columna: #0
      if (data[0] != '') {
        $("td", row).eq(0).addClass("text-center");   
         
      }
      // columna: Cantidad
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-center");   
         
      }
      // columna: compras
      if (data[6] != '') {
        $("td", row).eq(6).addClass("text-center");   
          
      }    
      // columna: Precio promedio
      if (data[7] != '') {
        $("td", row).eq(7).addClass("text-right");         
      }
      // columna: Precio actual
      if (data[8] != '') {
        $("td", row).eq(8).addClass("text-right");         
      }
      // columna: Suma Total
      if (data[9] != '') {
        $("td", row).eq(9).addClass("text-right");         
      }
    },
		"language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": { _: '%d líneas copiadas',  1: '1 línea copiada' }
      }
    },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	  "order": [[ 0, "asc" ]],//Ordenar (columna,orden)
    "columnDefs":[ { "targets": [ 3 ], "visible": false, "searchable": false }, ]
	}).DataTable();

  $.post("../ajax/resumen_activos_fijos_general.php?op=suma_total_compras_maq", {}, function (data, status) {

    data = JSON.parse(data); console.log('---maq----'); console.log(data.length); 

    if (data.length === 0) {

      $(".suma_total_cant_maquinarias").html('<i class="far fa-frown fa-lg text-danger"></i>');

      $('.suma_total_de_maquinarias').html('<i class="far fa-frown fa-lg text-danger"></i>');

    } else {
      if (data.total_cantidad == null || data.total_cantidad == '') {
        $(".suma_total_cant_maquinarias").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".suma_total_cant_maquinarias").html( 'S/. '+ formato_miles(data.total_cantidad));
      }

      if (data.total_monto == null || data.total_monto == '') {
        $('.suma_total_de_maquinarias').html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $('.suma_total_de_maquinarias').html( 'S/. '+ formato_miles(data.total_monto));
      }
    }    
  });
}

function ver_precios_y_mas_maq(idproducto, nombre_producto, precio_promedio, subtotal_x_producto ) 
{
  ingresar_segundo_div();
  var precio_prom=quitar_formato_miles(precio_promedio);
  var subtotal_x_prod=quitar_formato_miles(subtotal_x_producto);

  $(".nombre-producto-modal-titel").html('Producto: <b>'+ nombre_producto +'</b>');
  
  $(".precio_promedio").html(formato_miles(parseFloat(precio_prom).toFixed(2)));
  $(".subtotal_x_producto").html( formato_miles(parseFloat(subtotal_x_prod).toFixed(2)));

	tabla_factura = $('#tabla-precios').dataTable({
		"responsive": true,
	lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
		buttons: [	],
		"ajax":	{
      url: `../ajax/resumen_activos_fijos_general.php?op=ver_precios_y_mas_maq&idproducto=${idproducto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
		"language": {"lengthMenu": "Mostrar : _MENU_ registros", },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
		"order": [[ 3, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();

  
}
/**tipo clasificacion EQUIPOS */
function listar_tbla_principal_equip()
{

	tabla_equipo1=$('#tabla-resumen-equipos').dataTable({
		"responsive": true,
	lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
		"ajax":	{
      url: '../ajax/resumen_activos_fijos_general.php?op=listar_tbla_principal_equip',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
		},
    createdRow: function (row, data, ixdex) {  
      // columna: #0
      if (data[0] != '') {
        $("td", row).eq(0).addClass("text-center");   
          
      }  
      // columna: Cantidad
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-center");   
         
      }
      // columna: compras
      if (data[6] != '') {
        $("td", row).eq(6).addClass("text-center");   
          
      }    
      // columna: Precio promedio
      if (data[7] != '') {
        $("td", row).eq(7).addClass("text-right");         
      }
      // columna: Precio actual
      if (data[8] != '') {
        $("td", row).eq(8).addClass("text-right");         
      }
      // columna: Suma Total
      if (data[9] != '') {
        $("td", row).eq(9).addClass("text-right");         
      }
    },
		"language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": { _: '%d líneas copiadas',  1: '1 línea copiada' }
      }
    },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	  "order": [[ 0, "asc" ]],//Ordenar (columna,orden)
    "columnDefs":[ { "targets": [ 3 ], "visible": false, "searchable": false }, ]
	}).DataTable();

  $.post("../ajax/resumen_activos_fijos_general.php?op=suma_total_compras_equip", {}, function (data, status) {

    data = JSON.parse(data); //console.log('-------'); console.log(data); 

    if (data.length === 0) {

      $(".suma_total_cant_equipos").html('<i class="far fa-frown fa-lg text-danger"></i>');

      $('.suma_total_de_equipos').html('<i class="far fa-frown fa-lg text-danger"></i>');

    } else {
      if (data.total_cantidad == null || data.total_cantidad == '') {
        $(".suma_total_cant_equipos").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".suma_total_cant_equipos").html( 'S/. '+ formato_miles(data.total_cantidad));
      }

      if (data.total_monto == null || data.total_monto == '') {
        $('.suma_total_de_equipos').html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $('.suma_total_de_equipos').html( 'S/. '+ formato_miles(data.total_monto));
      }
    }    
  });
}

function ver_precios_y_mas_equip(idproducto, nombre_producto, precio_promedio, subtotal_x_producto ) 
{
  ingresar_segundo_div();
  var precio_prom=quitar_formato_miles(precio_promedio);
  var subtotal_x_prod=quitar_formato_miles(subtotal_x_producto);

  $(".nombre-producto-modal-titel").html('Producto: <b>'+ nombre_producto +'</b>');

  $(".precio_promedio").html(formato_miles(parseFloat(precio_prom).toFixed(2)));
  $(".subtotal_x_producto").html( formato_miles(parseFloat(subtotal_x_prod).toFixed(2)));

	tabla_factura = $('#tabla-precios').dataTable({
		"responsive": true,
	lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
		buttons: [	],
		"ajax":	{
      url: `../ajax/resumen_activos_fijos_general.php?op=ver_precios_y_mas_equip&idproducto=${idproducto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
		"language": {"lengthMenu": "Mostrar : _MENU_ registros", },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
		"order": [[ 3, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();

  
}

/**tipo clasificacion HERRAMIENTAS */
function listar_tbla_principal_herra()
{

	tabla_herramientas1=$('#tabla-resumen-herramientas').dataTable({
		"responsive": true,
	lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
		"ajax":	{
      url: '../ajax/resumen_activos_fijos_general.php?op=listar_tbla_principal_herra',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
		},
    createdRow: function (row, data, ixdex) {    
      // columna: #0
      if (data[0] != '') {
        $("td", row).eq(0).addClass("text-center");   
         
      }
      // columna: Cantidad
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-center");   
         
      }
      // columna: compras
      if (data[6] != '') {
        $("td", row).eq(6).addClass("text-center");   
          
      }    
      // columna: Precio promedio
      if (data[7] != '') {
        $("td", row).eq(7).addClass("text-right");         
      }
      // columna: Precio actual
      if (data[8] != '') {
        $("td", row).eq(8).addClass("text-right");         
      }
      // columna: Suma Total
      if (data[9] != '') {
        $("td", row).eq(9).addClass("text-right");         
      }
    },
		"language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": { _: '%d líneas copiadas',  1: '1 línea copiada' }
      }
    },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	  "order": [[ 0, "asc" ]],//Ordenar (columna,orden)
    "columnDefs":[ { "targets": [ 3 ], "visible": false, "searchable": false }, ]
	}).DataTable();

  $.post("../ajax/resumen_activos_fijos_general.php?op=suma_total_compras_herra", {}, function (data, status) {

    data = JSON.parse(data); //console.log('-------'); console.log(data); 

    if (data.length === 0) {

      $(".suma_total_herramientas").html('<i class="far fa-frown fa-lg text-danger"></i>');

      $('.suma_total_de_herramientas').html('<i class="far fa-frown fa-lg text-danger"></i>');

    } else {
      if (data.total_cantidad == null || data.total_cantidad == '') {
        $(".suma_total_herramientas").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".suma_total_herramientas").html( 'S/. '+ formato_miles(data.total_cantidad));
      }

      if (data.total_monto == null || data.total_monto == '') {
        $('.suma_total_de_herramientas').html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $('.suma_total_de_herramientas').html( 'S/. '+ formato_miles(data.total_monto));
      }
    }    
  });
}

function ver_precios_y_mas_herra(idproducto, nombre_producto, precio_promedio, subtotal_x_producto ) 
{
  ingresar_segundo_div();
  var precio_prom=quitar_formato_miles(precio_promedio);
  var subtotal_x_prod=quitar_formato_miles(subtotal_x_producto);

  $(".nombre-producto-modal-titel").html('Producto: <b>'+ nombre_producto +'</b>');

  $(".precio_promedio").html(formato_miles(parseFloat(precio_prom).toFixed(2)));
  $(".subtotal_x_producto").html( formato_miles(parseFloat(subtotal_x_prod).toFixed(2)));

	tabla_factura = $('#tabla-precios').dataTable({
		"responsive": true,
	lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
		buttons: [	],
		"ajax":	{
      url: `../ajax/resumen_activos_fijos_general.php?op=ver_precios_y_mas_herra&idproducto=${idproducto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
		"language": {"lengthMenu": "Mostrar : _MENU_ registros", },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
		"order": [[ 3, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();

  
}
/**tipo clasificacion OFICINA */
function listar_tbla_principal_oficina()
{

	tabla_oficina1=$('#tabla-resumen-oficina').dataTable({
		"responsive": true,
	lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
		"ajax":	{
      url: '../ajax/resumen_activos_fijos_general.php?op=listar_tbla_principal_oficina',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
		},
    createdRow: function (row, data, ixdex) {    
      // columna: #0
      if (data[0] != '') {
        $("td", row).eq(0).addClass("text-center");   
         
      }
      // columna: Cantidad
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-center");   
         
      }
      // columna: compras
      if (data[6] != '') {
        $("td", row).eq(6).addClass("text-center");   
          
      }    
      // columna: Precio promedio
      if (data[7] != '') {
        $("td", row).eq(7).addClass("text-right");         
      }
      // columna: Precio actual
      if (data[8] != '') {
        $("td", row).eq(8).addClass("text-right");         
      }
      // columna: Suma Total
      if (data[9] != '') {
        $("td", row).eq(9).addClass("text-right");         
      }
    },
		"language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": { _: '%d líneas copiadas',  1: '1 línea copiada' }
      }
    },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	  "order": [[ 0, "asc" ]],//Ordenar (columna,orden)
    "columnDefs":[ { "targets": [ 3 ], "visible": false, "searchable": false }, ]
	}).DataTable();

  $.post("../ajax/resumen_activos_fijos_general.php?op=suma_total_compras_oficina", {}, function (data, status) {

    data = JSON.parse(data); //console.log('-------'); console.log(data); 

    if (data.length === 0) {

      $(".suma_total_oficina").html('<i class="far fa-frown fa-lg text-danger"></i>');

      $('.suma_total_de_oficina').html('<i class="far fa-frown fa-lg text-danger"></i>');

    } else {
      if (data.total_cantidad == null || data.total_cantidad == '') {
        $(".suma_total_oficina").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".suma_total_oficina").html( 'S/. '+ formato_miles(data.total_cantidad));
      }

      if (data.total_monto == null || data.total_monto == '') {
        $('.suma_total_de_oficina').html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $('.suma_total_de_oficina').html( 'S/. '+ formato_miles((data.total_monto).toFixed(2)));
      }
    }    
  });
}

function ver_precios_y_mas_oficina(idproducto, nombre_producto, precio_promedio, subtotal_x_producto ) 
{
  ingresar_segundo_div();
  var precio_prom=quitar_formato_miles(precio_promedio);
  var subtotal_x_prod=quitar_formato_miles(subtotal_x_producto);
  
  $(".nombre-producto-modal-titel").html('Producto: <b>'+ nombre_producto +'</b>');

  $(".precio_promedio").html(formato_miles(parseFloat(precio_prom).toFixed(2)));
  $(".subtotal_x_producto").html( formato_miles(parseFloat(subtotal_x_prod).toFixed(2)));

	tabla_factura = $('#tabla-precios').dataTable({
		"responsive": true,
	lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
		buttons: [	],
		"ajax":	{
      url: `../ajax/resumen_activos_fijos_general.php?op=ver_precios_y_mas_oficina&idproducto=${idproducto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
		"language": {"lengthMenu": "Mostrar : _MENU_ registros", },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
		"order": [[ 3, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();

  
}
//:::::::::::::::A G R E G A R  P R O V E E D O R:::::::::::::::

function guardarproveedor(e) {

  var formData = new FormData($("#form-proveedor")[0]);

  $.ajax({
    url: "../ajax/resumen_activos_fijos_general.php?op=guardaryeditar_proveedor",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      if (datos == "ok") {
        // toastr.success("proveedor registrado correctamente");
        Swal.fire("Correcto!", "Proveedor guardado correctamente.", "success");
        //tabla.ajax.reload();

        limpiardatosproveedor();

        $("#modal-agregar-proveedor").modal("hide");

        //Cargamos los items al select cliente
        $.post("../ajax/compra.php?op=select2Proveedor", function (r) { $("#idproveedor").html(r); });
      } else {
        // toastr.error(datos);
        Swal.fire("Error!", datos, "error");
      }
    },
  });
}
//Función limpiar
function limpiardatosproveedor() {
  $(".tooltip").removeClass('show');

  $("#idproveedor").val("");
  $("#nombre").val("");
  $("#num_documento").val("");
  $("#direccion").val("");
  $("#telefono").val("");
  $("#c_bancaria").val("");
  $("#c_detracciones").val("");
  //$("#banco").val("");
  $("#banco").val("null").trigger("change");
  $("#tipo_documento").val("RUC").trigger("change");
  $("#titular_cuenta").val("");

  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}
//:::::::::::::::F I N  A G R E G A R  P R O V E E D O R:::::::::::::::

//:::::::::::::::A G R E G A R  P R O D U C T O S:::::::::::::::

//Función limpiar
function limpiar_materiales() {
  $("#idproducto_p").val("");  
  $("#nombre_p").val("");
  $("#modelo_p").val("");
  $("#serie_p").val("");
  $("#marca_p").val("");
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

  $("#unid_medida_p").val(4).trigger("change");
  $("#color_p").val(1).trigger("change");
  $("#categoria_insumos_af_p").val("").trigger("change");

  $("#my-switch_igv").prop("checked", true);
  $("#estado_igv_p").val("1");

  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
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

    success: function (datos) {
      if (datos == "ok") {

        Swal.fire("Correcto!", "Producto creado correctamente", "success");      
       
        if (tablamateriales) {tablamateriales.ajax.reload(); }

        if (tabla_maaquinaria1) {tabla_maaquinaria1.ajax.reload(); }
        if (tabla_equipo1) {tabla_equipo1.ajax.reload(); }
        if (tabla_herramientas1) {tabla_herramientas1.ajax.reload(); }
        if (tabla_oficina1) {tabla_oficina1.ajax.reload(); }

        limpiar_materiales();
        
        $("#modal-agregar-material-activos-fijos").modal("hide");

      } else {
        Swal.fire("Error!", datos, "error");
      }
    },
  });
}

function precio_con_igv() {
  var precio_total = 0;
  var mont_igv = 0.0;

  var precio_base = 0;
  var igv = 0;
  var precio_re = 0;

  //var precio_r=0;
  precio_total = $("#precio_unitario_p").val();

  $("#precio_igv_p").val(mont_igv.toFixed(2));
  $("#precio_sin_igv_p").val(precio_total);

  if ($("#my-switch_igv").is(":checked")) {
    precio_base = precio_total / 1.18;
    igv = precio_total - precio_base;
    precio_re = parseFloat(precio_total) - igv;
    
    $("#precio_igv_p").val(igv.toFixed(2));
    $("#precio_sin_igv_p").val(precio_re.toFixed(2));
    $("#precio_total_p").val((precio_re + igv).toFixed(2));

    $("#estado_igv_p").val("1");
  } else {
    precio_base = precio_total * 1.18;

    igv = precio_base - precio_total;
    precio_re = parseFloat(precio_total) - igv;

    $("#precio_igv_p").val(igv.toFixed(2));
    $("#precio_sin_igv_p").val( parseFloat(precio_total).toFixed(2));
    $("#precio_total_p").val(precio_base.toFixed(2));

    $("#estado_igv_p").val("0");
  }
}

$("#my-switch_igv").on("click ", function (e) {

  var precio_ingresado = 0;
  var precio_sin_igv = 0;
  var igv = 0;
  var precio_total = 0;

  precio_ingresado = $("#precio_unitario_p").val(); 

  if ($("#my-switch_igv").is(":checked")) {
    precio_sin_igv = precio_ingresado / 1.18;
    igv = precio_ingresado - precio_sin_igv;
    precio_total = parseFloat(precio_sin_igv) + igv;   
    console.log(precio_sin_igv, igv, precio_total);
    $("#precio_sin_igv_p").val(redondearExp(precio_sin_igv, 2));

    $("#precio_igv_p").val(redondearExp(igv, 2));   

    $("#precio_total_p").val(redondearExp(precio_total, 2)) ;

    $("#estado_igv_p").val("1");
  } else {
    precio_sin_igv = precio_ingresado * 1.18;     
    igv = precio_sin_igv - precio_ingresado;
    precio_total = parseFloat(precio_ingresado) + igv;    
    console.log(precio_sin_igv, igv, precio_total);
    $("#precio_sin_igv_p").val(redondearExp(precio_ingresado, 2));

    $("#precio_igv_p").val(redondearExp(igv, 2));

    $("#precio_total_p").val(redondearExp(precio_total, 2) );

    $("#estado_igv_p").val("0");
  }
});

$(function () {
  $.validator.setDefaults({
    submitHandler: function (e) {
      guardar_y_editar_materiales(e);
    },
  });

  $("#form-materiales").validate({
    rules: {
      nombre_p: { required: true, minlength:3, maxlength:200},
      categoria_insumos_af_p: { required: true },
      color_p: { required: true },
      unid_medida_p: { required: true },
      modelo_p: { required: true },
      precio_unitario_p: { required: true },
      descripcion_p: { minlength: 3 },
    },
    messages: {
      nombre_p: { required: "Por favor ingrese nombre", minlength:"Minimo 3 caracteres", maxlength:"Maximo 200 caracteres" },
      categoria_insumos_af_p: { required: "Campo requerido", },
      color_p: { required: "Campo requerido" },
      unid_medida_p: { required: "Campo requerido" },
      modelo_p: { required: "Por favor ingrese modelo", },
      precio_unitario_p: { required: "Ingresar precio compra", },      
      descripcion_p: { minlength: "Minimo 3 caracteres" },
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
  });
});

// MOSTRAR PARA EDITAR
function mostrar_insumo(idproducto) { 

  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();
  
  limpiar_materiales();  

  $("#modal-agregar-material-activos-fijos").modal("show");

  $.post("../ajax/resumen_activos_fijos_general.php?op=mostrar_productos", { 'idproducto_p': idproducto }, function (data, status) {
    
    data = JSON.parse(data); //console.log(data);    

    $("#idproducto_p").val(data.idproducto);
    $("#cont").val(cont);

    $("#nombre_p").val(data.nombre);
    $("#modelo_p").val(data.modelo);
    $("#serie_p").val(data.serie);
    $("#marca_p").val(data.marca);
    $("#descripcion_p").val(data.descripcion);

    $('#precio_unitario_p').val(parseFloat(data.precio_unitario).toFixed(2));
    $("#estado_igv_p").val(parseFloat(data.estado_igv).toFixed(2));
    $("#precio_sin_igv_p").val(parseFloat(data.precio_sin_igv).toFixed(2));
    $("#precio_igv_p").val(parseFloat(data.precio_igv).toFixed(2));
    $("#precio_total_p").val(parseFloat(data.precio_total).toFixed(2));
     
    $("#unid_medida_p").val(data.idunidad_medida).trigger("change");
    $("#color_p").val(data.idcolor).trigger("change");  
    $("#categoria_insumos_af_p").val(data.idcategoria_insumos_af).trigger("change");    

    if (data.estado_igv == "1") {
      $("#my-switch_igv").prop("checked", true);
    } else {
      $("#my-switch_igv").prop("checked", false);
    }
     
    if (data.imagen != "") {
      
      $("#foto2_i").attr("src", "../dist/docs/material/img_perfil/" + data.imagen);

      $("#foto2_actual").val(data.imagen);
    }

    // FICHA TECNICA
    if (data.ficha_tecnica == "" || data.ficha_tecnica == null  ) {

      $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

      $("#doc2_nombre").html('');

      $("#doc_old_2").val(""); $("#doc2").val("");

    } else {

      $("#doc_old_2").val(data.ficha_tecnica); 

      $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Ficha-tecnica.${extrae_extencion(data.ficha_tecnica)}</i></div></div>`);
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.ficha_tecnica) == "pdf" ) {

        $("#doc2_ver").html('<iframe src="../dist/docs/material/ficha_tecnica/'+data.ficha_tecnica+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

      }else{
        if (
          extrae_extencion(data.ficha_tecnica) == "jpeg" || extrae_extencion(data.ficha_tecnica) == "jpg" || extrae_extencion(data.ficha_tecnica) == "jpe" ||
          extrae_extencion(data.ficha_tecnica) == "jfif" || extrae_extencion(data.ficha_tecnica) == "gif" || extrae_extencion(data.ficha_tecnica) == "png" ||
          extrae_extencion(data.ficha_tecnica) == "tiff" || extrae_extencion(data.ficha_tecnica) == "tif" || extrae_extencion(data.ficha_tecnica) == "webp" ||
          extrae_extencion(data.ficha_tecnica) == "bmp" || extrae_extencion(data.ficha_tecnica) == "svg" ) {

          $("#doc2_ver").html(`<img src="../dist/docs/material/ficha_tecnica/${data.ficha_tecnica}" alt="" width="50%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc2_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
    } 

    $("#cargando-3-fomulario").show();
    $("#cargando-4-fomulario").hide();

  });
}

// DETALLE DEL MATERIAL
function mostrar_detalle_material(idproducto) {  
  
  $('#datosproductos').html(''+
  '<div class="row" >'+
    '<div class="col-lg-12 text-center">'+
      '<i class="fas fa-spinner fa-pulse fa-6x"></i><br />'+
      '<br />'+
      '<h4>Cargando...</h4>'+
    '</div>'+
  '</div>');

  var verdatos=''; var imagenver='';

  $("#modal-ver-detalle-material-activo-fijo").modal("show")

  $.post("../ajax/resumen_activos_fijos.php?op=mostrar_materiales", { 'idproducto_p': idproducto }, function (data, status) {

    data = JSON.parse(data);  //console.log(data); 

    var imagen_perfil =data.imagen == '' || data.imagen == null ? '<img src="../dist/svg/default_producto.svg" alt="" width="90px">' : `<img src="../dist/docs/material/img_perfil/${data.imagen}" alt="" class="img-thumbnail" width="150px">`;
    var ficha_tecnica =data.ficha_tecnica == '' || data.ficha_tecnica == null ? '<center><i class="far fa-file-pdf fa-2x text-gray-50"></i></center>' : `<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/${data.ficha_tecnica}"><i class="far fa-file-pdf fa-2x text-danger" ></i></a></center>`;

    verdatos=`                                                                            
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-hover table-bordered">        
            <tbody>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th rowspan="2">${imagen_perfil}</th>
                <td> <b>Nombre: </b> ${data.nombre}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <td> <b>Color: </b>  ${data.nombre_color}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Unidad Medida</th>
                <td>${data.nombre_medida}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Clasificación</th>
                <td>${data.categoria}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Modelo</th>
                <td>${data.modelo}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Serie</th>
                  <td>${data.serie}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Marca</th>
                <td>${data.marca}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Precio Unitario</th>
                <td>${ formato_miles(parseFloat(data.precio_unitario).toFixed(2))}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>IGV</th>
                <td>${ formato_miles(parseFloat(data.precio_igv).toFixed(2))}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Precio sin IGV</th>
                <td>${ formato_miles(parseFloat(data.precio_sin_igv).toFixed(2))}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Precio con IGV</th>
                <td>${ formato_miles(parseFloat(data.precio_total).toFixed(2))}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Descripción</th>
                <td><textarea cols="30" rows="1" class="text_area_clss" readonly >${data.descripcion}</textarea></td>
              </tr>              
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Ficha tecnica</th>
                <td> ${ficha_tecnica} </td>
              </tr>               
            </tbody>
          </table>
        </div>
      </div>
    </div>`;
  
    $("#datosproductos").html(verdatos);

  });

}

//:::::::::::::::F I N  A G R E G A R  P R O D U C T O S:::::::::::::::

// :::::::::::::: S E C C I O N   E D I T A R  C O M P R A S  G E N E R A L  :::::::::::::::

var impuesto = 18;
var cont = 0;
var detalles = 0;

//mostramos para editar el datalle del comprobante de la compras

//Función para guardar o editar - COMPRAS
function guardaryeditar_compras(e) {
  
  var formData = new FormData($("#form-compras")[0]);

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
        url: "../ajax/resumen_activos_fijos_general.php?op=guardaryeditarcomprageneral",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
          if (datos == "ok") {
            // toastr.success("Usuario registrado correctamente");
            Swal.fire("Correcto!", "Compra guardada correctamente", "success");

            tabla_maaquinaria1.ajax.reload();
            tabla_equipo1.ajax.reload();
            tabla_herramientas1.ajax.reload();
            tabla_oficina1.ajax.reload();

            listar_tbla_principal_maq();
            listar_tbla_principal_equip();
            listar_tbla_principal_herra();
            listar_tbla_principal_oficina();

            limpiar();
            regresar_div2()
            cont = 0;
            $("#modal-agregar-usuario").modal("hide");

          } else {
            // toastr.error(datos);
            Swal.fire("Error!", datos, "error");
          }
        },
      });
    }
  });
}

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

      if ($("#tipo_comprovante").select2("val") == "Factura") {
        var subtotal = cantidad * precio_total;
      } else {
        var subtotal = cantidad * precio_sin_igv;
      }

      var img_p = "";

      if (img == "" || img == null) {
        img_p = "../dist/svg/default_producto.svg";
      } else {
        img_p = `../dist/docs/material/img_perfil/${img}`;
      }

      var fila = `
      <tr class="filas" id="fila${cont}">
        <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(${cont})">X</button></td>
        <td>
          <input type="hidden" name="idproducto[]" value="${idproducto}">
          <input type="hidden" name="ficha_tecnica_producto[]" value="${ficha_tecnica_producto}">
          <div class="user-block text-nowrap">
            <img class="profile-user-img img-responsive img-circle cursor-pointer" src="${img_p}" alt="user image" onerror="this.src='../dist/svg/default_producto.svg';" onclick="ver_img_activo('${img_p}', '${nombre}')">
            <span class="username"><p style="margin-bottom: 0px !important;">${nombre}</p></span>
            <span class="description"><b>Color: </b>${nombre_color}</span>
          </div>
        </td>
        <td><span class="">${unidad_medida}</span> <input type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${unidad_medida}"><input type="hidden" name="nombre_color[]" id="nombre_color[]" value="${nombre_color}"></td>
        <td class="form-group"><input class="producto_${idproducto} producto_selecionado w-px-100 cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" min="1" value="${cantidad}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
        <td class="hidden"><input type="number" class="w-px-135 input-no-border precio_sin_igv_${cont}" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${parseFloat(precio_sin_igv).toFixed(2)}" readonly min="0" ></td>
        <td class="hidden"><input class="w-px-135 input-no-border precio_igv_${cont}" type="number" name="precio_igv[]" id="precio_igv[]" value="${parseFloat(precio_igv).toFixed(2)}" readonly  ></td>
        <td ><input class="w-px-135 precio_con_igv_${cont}" type="number" name="precio_con_igv[]" id="precio_con_igv[]" value="${parseFloat(precio_total).toFixed(2)}" onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
        <td><input type="number" class="w-px-135 descuento_${cont}" name="descuento[]" value="${descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
        <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">${subtotal}</span></td>
        <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fas fa-sync"></i></button></td>
      </tr>`;

      detalles = detalles + 1;

      $("#detalles").append(fila);

      array_class_trabajador.push({ id_cont: cont });

      modificarSubtotales();

      toastr.success("Material: " + nombre + " agregado !!");

      cont++;
      evaluar();
    }
  } else {
    // alert("Error al ingresar el detalle, revisar los datos del artículo");
    toastr.error("Error al ingresar el detalle, revisar los datos del material.");
  }
}

function editar_detalle_compras_general(idcompra_general,selec_op_pametro){
  selec_op=selec_op_pametro
  ingresar_tercer_div();
  limpiar();
  array_class_trabajador = [];
  $('.compra_general').show();
  $('.compra_proyecto').hide();
  cont = 0;
  detalles = 0;

  $.post("../ajax/resumen_activos_fijos_general.php?op=ver_compra_editar_general", { idcompra_general: idcompra_general }, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

    if (data) {
      $(".subtotal").html("");
      $(".igv_comp").html("");
      $(".total").html("");

      if (data.tipo_comprobante == "Factura") {
        $(".igv").val("0.18");
        $(".content-igv").show();
        $(".content-t-comprob").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descrp").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        $(".content-comprob").show();
      } else if (data.tipo_comprobante == "Boleta" || data.tipo_comprobante == "Nota_de_venta") {
        $(".igv").val("");
        $(".content-comprob").show();
        $(".content-igv").hide();
        $(".content-t-comprob").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");

        $(".content-descrp").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      } else if (data.tipo_comprobante == "Ninguno") {
        $(".content-comprob").hide();
        $(".content-comprob").val("");
        $(".content-igv").hide();
        $(".content-t-comprob").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descrp").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
        $(".content-comprob").show();
        //$(".content-descrp").removeClass("col-lg-7").addClass("col-lg-4");
      }
      $("#idcompra_general").val(data.idcompra_af_general);
      $("#idproveedor").val(data.idproveedor).trigger("change");
      $("#fecha_compra").val(data.fecha_compra);
      $("#tipo_comprovante").val(data.tipo_comprobante).trigger("change");
      $("#serie_comprovante").val(data.serie_comprobante).trigger("change");
      $("#descripcion").val(data.descripcion);

      if (data.activos) {
        data.activos.forEach((element, index) => {
          var img = "";

          if (element.imagen == "" || element.imagen == null) {
            img = "img_material_defect.jpg";
          } else {
            img = element.imagen;
          }

          var fila = `
          <tr class="filas" id="fila${cont}">
            <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(${cont})">X</button></td>
            <td>
              <input type="hidden" name="idproducto[]" value="${element.idactivos_fijos}">
              <input type="hidden" name="ficha_tecnica_producto[]" value="${element.ficha_tecnica}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="../docs/material/img_perfil/${img}" alt="user image" onerror="this.src='../dist/svg/default_producto.svg';" onclick="ver_img_activo('${img}', '${element.nombre}')">
                <span class="username"><p style="margin-bottom: 0px !important;">${element.nombre}</p></span>
                <span class="description"><b>Color: </b>${element.color}</span>
              </div>
            </td>
            <td> <span class="">${element.unidad_medida}</span> <input type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${element.unidad_medida}"> <input type="hidden" name="nombre_color[]" id="nombre_color[]" value="${element.color}"></td>
            <td class="form-group"><input class="producto_${element.idactivos_fijos} producto_selecionado w-px-100 cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" min="1" value="${element.cantidad}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="hidden"><input class="w-px-135 input-no-border precio_sin_igv_${cont}" type="number" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${element.precio_sin_igv}" readonly ></td>
            <td class="hidden"><input class="w-px-135 input-no-border precio_igv_${cont}" type="number"  name="precio_igv[]" id="precio_igv[]" value="${element.igv}" readonly ></td>
            <td ><input type="number" class="w-px-135 precio_con_igv_${cont}" type="number"  name="precio_con_igv[]" id="precio_con_igv[]" value="${parseFloat(element.precio_con_igv).toFixed(2)}" onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
            <td><input type="number" class="w-px-135 descuento_${cont}" name="descuento[]" value="${element.descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">0.00</span></td>
            <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles").append(fila);

          array_class_trabajador.push({ id_cont: cont });

          cont++;
          evaluar();
        });

        modificarSubtotales();
      } else {
        toastr.error("<h3>Sin productos.</h3> <br> Este registro no tiene productos para mostrar");
        $(".subtotal").html("S/. 0.00");
        $(".igv_comp").html("S/. 0.00");
        $(".total").html("S/. 0.00");
      }
    } else {
      toastr.error("<h3>Error.</h3> <br> Este registro tiene errores, o esta vacio");
    }
  });
}

function modificarSubtotales() {
  console.log(array_class_trabajador);

  if ($("#tipo_comprovante").select2("val") == null) {
    $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

    $("#colspan_subtotal").attr("colspan", 5); //cambiamos el: colspan

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
    if ($("#tipo_comprovante").select2("val") == "Factura") {
      $(".hidden").show(); //Mostramos: IGV, PRECIO SIN IGV

      $("#colspan_subtotal").attr("colspan", 7); //cambiamos el: colspan

      if (array_class_trabajador.length === 0) {
      } else {
        array_class_trabajador.forEach((element, index) => {
          var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
          var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
          var subtotal_producto = 0;

          // Calculamos: IGV
          var precio_sin_igv = (precio_con_igv / 1.18).toFixed(2);
          $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

          // Calculamos: precio + IGV
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

    $("#subtotal").html("S/. " + formato_miles(total));
    $("#subtotal_compra").val(total.toFixed(2));

    $("#igv_comp").html("S/. 0.00");
    $("#igv_compra").val(0.0);

    $("#total").html("S/. " + formato_miles(total.toFixed(2)));
    $("#total_venta").val(total.toFixed(2));
  }
}

function calcularTotalesConIgv() {
  var igv = 0;
  var total = 0.0;

  var subotal_sin_igv = 0;

  array_class_trabajador.forEach((element, index) => {
    total += parseFloat(quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()));
  });

  console.log(total);
  subotal_sin_igv = (parseFloat(total) / 1.18).toFixed(2);
  igv = (parseFloat(total) - parseFloat(subotal_sin_igv)).toFixed(2);

  $("#subtotal").html(`S/. ${formato_miles(subotal_sin_igv)}`);
  $("#subtotal_compra").val( parseFloat(subotal_sin_igv).toFixed(2));

  $("#igv_comp").html("S/. " + formato_miles(igv));
  $("#igv_compra").val(igv);

  $("#total").html("S/. " + formato_miles(total.toFixed(2)));
  $("#total_venta").val(parseFloat(total).toFixed(2));

  total = 0.0;
}

function ocultar_comprob() {
  if ($("#tipo_comprovante").select2("val") == "Ninguno") {
    $(".content-comprob").hide();

    $(".content-comprob").val("");

    $(".content-descrp").removeClass("col-lg-5").addClass("col-lg-7");
  } else {
    $(".content-comprob").show();

    $(".content-descrp").removeClass("col-lg-7").addClass("col-lg-5");
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

function evaluar() {
  if (detalles > 0) {
    $("#guardar_registro_compras").show();
  } else {
    $("#guardar_registro_compras").hide();
    cont = 0;
  }
}

//Detraccion
$("#my-switch_detracc").on("click ", function (e) {
  if ($("#my-switch_detracc").is(":checked")) {
    $("#estado_detraccion").val("1");
  } else {
    $("#estado_detraccion").val("0");
  }
});

//Función limpiar
function limpiar() {

  $(".tooltip").removeClass('show');

  //Mostramos los selectProveedor
  $.post("../ajax/compra.php?op=selectProveedor", function (r) {
    $("#idproveedor").html(r);
  });

  $("#idcompra_general").val();
  $("#idproyecto").val();

  $("#idusuario").val("");
  $("#trabajador_c").html("Trabajador");
  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprovante").val("Ninguno").trigger("change");

  // $("#fecha_compra").val("");
  $("#serie_comprovante").val("");
  $("#descripcion").val("");

  $("#total_venta").val("");
  $(".filas").remove();
  $("#total").html("0");
  $("#subtotal").html("");
  $("#subtotal_compra").val("");

  $("#igv_comp").html("");
  $("#igv_compra").val("");

  $("#total").html("");
  $("#total_venta").val("");

  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

// :::::::::::::: F I N  S E C C I O N   E D I T A R  C O M P R A S  G E N E R A L  :::::::::::::::

// :::::::::::::: F I N  S E C C I O N   E D I T A R  C O M P R A S  P O R  P R O Y E C T O  :::::::::::::::
var cont_p = 0;
var detalles_p = 0;
function agregarDetalleCompra_proy(idproducto , nombre, unidad_medida, nombre_color, precio_sin_igv, precio_igv, precio_total, img, ficha_tecnica_activo) {

  var cantidad = 1;
  var descuento = 0;

  if (idproducto != "") {
    // $('.producto_'+idactivos_fijos).addClass('producto_selecionado');
    if ($(".producto_p_" + idproducto).hasClass("producto_selecionado")) {
      toastr.success("Activo: " + nombre + " agregado !!");

      var cant_producto = $(".producto_p_" + idproducto).val();

      var sub_total = parseInt(cant_producto, 10) + 1;

      $(".producto_p_" + idproducto).val(sub_total);

      modificarSubtotales_p();
    } else {

      if ($("#tipo_comprobante_proy").select2("val") == "Factura") {
        var subtotal = cantidad * precio_total;
      } else {
        var subtotal = cantidad * precio_sin_igv;
      }

      var fila = `
      <tr class="filas" id="fila${cont_p}">
        <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle_p(${cont_p})">X</button></td>
        <td>
          <input type="hidden" name="idactivos_proyecto_p[]" value="${idproducto}">
          <input type="hidden" name="ficha_tecnica_activo_p[]" value="${ficha_tecnica_activo}">
          <div class="user-block text-nowrap">
            <img class="profile-user-img img-responsive img-circle cursor-pointer" src="${img}" alt="user image" onerror="this.src='../dist/svg/default_producto.svg';" onclick="ver_img_activo('${img}', '${nombre}')">
            <span class="username"><p style="margin-bottom: 0px !important;">${nombre}</p></span>
            <span class="description"><b>Color: </b>${nombre_color}</span>
          </div>
        </td>
        <td> 
          <span >${unidad_medida}</span> 
          <input type="hidden" name="unidad_medida_compra_p[]" id="unidad_medida_compra_p[]" value="${unidad_medida}"> 
          <input type="hidden" name="nombre_color_p[]" id="nombre_color_p[]" value="${nombre_color}">
        </td>
        <td class="form-group">
          <input class="producto_p_${idproducto} producto_selecionado w-px-100 cantidad_p_${cont_p} form-control " type="number" name="cantidad_p[]" id="cantidad_p[]" min="1" value="${cantidad}" onkeyup="modificarSubtotales_p()" onchange="modificarSubtotales_p()">
        </td>
        <td class="hidden">
          <input class="w-px-135 input-no-border precio_sin_igv_p_${cont_p}" type="number" name="precio_sin_igv_compra_p[]" id="precio_sin_igv_compra_p[]" value="${precio_sin_igv}" readonly >
        </td>
          <td class="hidden"><input class="w-px-135 input-no-border precio_igv_p_${cont_p}" type="number"  name="precio_igv_compra_p[]" id="precio_igv_compra_p[]" value="${precio_igv}" readonly >
        </td>
        <td >
          <input type="number" class="w-px-135 precio_con_igv_p_${cont_p}" type="number"  name="precio_con_igv_p[]" id="precio_con_igv_p[]" value="${parseFloat(precio_total).toFixed(2)}" onkeyup="modificarSubtotales_p();" onchange="modificarSubtotales_p();">
        </td>
        <td>
          <input type="number" class="w-px-135 descuento_p_${cont_p}" name="descuento_p[]" value="${descuento}" onkeyup="modificarSubtotales_p()" onchange="modificarSubtotales_p()">
        </td>
        <td class="text-right">
          <span class="text-right subtotal_producto_p_${cont_p}" name="subtotal_producto_p" id="subtotal_producto_p">${subtotal}</span>
        </td>
        <td>
          <button type="button" onclick="modificarSubtotales_p()" class="btn btn-info"><i class="fas fa-sync"></i></button>
          </td>
      </tr>`;

      detalles_p = detalles_p + 1;

      $("#detalles_af_proyecto").append(fila);

      array_class_activo_p.push({ id_cont_p: cont_p });
      modificarSubtotales_p();

      cont_p++;
      evaluar();

    }
  } else {
    // alert("Error al ingresar el detalle, revisar los datos del artículo");
    toastr.error("Error al ingresar el detalle, revisar los datos del material.");
  }
}
function editar_detalle_compras_proyecto(idcompra_proyecto,selec_op_pametro) {
  selec_op=selec_op_pametro
  ingresar_tercer_div();
  //limpiar_p();
  $('.compra_general').hide();
  $('.compra_proyecto').show();
  array_class_activo_p = [];

  cont_p = 0;
  detalles_p = 0;

  $.post("../ajax/resumen_activos_fijos_general.php?op=ver_compra_editar_proyecto", { idcompra_proyecto: idcompra_proyecto }, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

    if (data) {
      $(".subtotal_proy").html("");
      $(".igv_comp_proy").html("");
      $(".total_proy").html("");
      $("#idproveedor_proy").val("").trigger("change");

      if (data.tipo_comprobante == "Factura") {
        $("#igv_proy").val("0.18");
        $("#content-igv-p").show();
        $("#content-t-comprob-p").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $("#content-descrp-p").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        $("#content-comprob-p").show();
      } else if (data.tipo_comprobante == "Boleta" || data.tipo_comprobante == "Nota_de_venta") {
        $("#igv_proy").val("");
        $("#content-comprob-p").show();
        $("#content-igv-p").hide();
        $("#content-t-comprob-p").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");

        $("#content-descrp-p").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");

      } else if (data.tipo_comprobante == "Ninguno") {
        $("#igv_proy").val("");
        $("#content-comprob-p").hide();
        $("#content-comprob-p").val("");
        $("#content-igv-p").hide();
        $("#content-t-comprob-p").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $("#content-descrp-p").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
        $("#content-comprob-p").show();
        //$(".content-descrp").removeClass("col-lg-7").addClass("col-lg-4");
      }

      $("#idproyecto_proy").val(data.idproyecto);
      $("#idcompra_af_proy").val(data.idcompra_x_proyecto);
      $("#idproveedor_proy").val(data.idproveedor).trigger("change");
      $("#fecha_compra_proy").val(data.fecha_compra);
      $("#tipo_comprobante_proy").val(data.tipo_comprobante).trigger("change");
      $("#serie_comprobante_proy").val(data.serie_comprobante);
      $("#descripcion_proy").val(data.descripcion);

      if (data.producto) {
        data.producto.forEach((element, index) => {
          var img = "";

          if (element.imagen == "" || element.imagen == null) {
            img = `../dist/img/default/img_defecto_activo_fijo.png`;
          } else {
            img =`../dist/docs/material/img_perfil/${element.imagen}`;
          }

          var fila = `
          <tr class="filas" id="fila${cont_p}">
            <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle_p(${cont_p})">X</button></td>
            <td>
              <input type="hidden" name="idactivos_proyecto_p[]" value="${element.idproducto}">
              <input type="hidden" name="ficha_tecnica_activo_p[]" value="${element.ficha_tecnica}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="${img}" alt="user image" onerror="this.src='../dist/svg/default_producto.svg';" onclick="ver_img_activo('${img}', '${element.nombre_producto}')">
                <span class="username"><p style="margin-bottom: 0px !important;">${element.nombre_producto}</p></span>
                <span class="description"><b>Color: </b>${element.color}</span>
              </div>
            </td>
            <td> 
              <span >${element.unidad_medida}</span> 
              <input type="hidden" name="unidad_medida_compra_p[]" id="unidad_medida_compra_p[]" value="${element.unidad_medida}"> 
              <input type="hidden" name="nombre_color_p[]" id="nombre_color_p[]" value="${element.color}">
            </td>
            <td class="form-group">
              <input class="producto_p_${element.idproducto} producto_selecionado w-px-100 cantidad_p_${cont_p} form-control " type="number" name="cantidad_p[]" id="cantidad_p[]" min="1" value="${element.cantidad}" onkeyup="modificarSubtotales_p()" onchange="modificarSubtotales_p()">
            </td>
            <td class="hidden">
              <input class="w-px-135 input-no-border precio_sin_igv_p_${cont_p}" type="number" name="precio_sin_igv_compra_p[]" id="precio_sin_igv_compra_p[]" value="${element.precio_venta}" readonly >
            </td>
              <td class="hidden"><input class="w-px-135 input-no-border precio_igv_p_${cont_p}" type="number"  name="precio_igv_compra_p[]" id="precio_igv_compra_p[]" value="${element.igv}" readonly >
            </td>
            <td >
              <input type="number" class="w-px-135 precio_con_igv_p_${cont_p}" type="number"  name="precio_con_igv_p[]" id="precio_con_igv_p[]" value="${parseFloat(element.precio_igv).toFixed(2)}" onkeyup="modificarSubtotales_p();" onchange="modificarSubtotales_p();">
            </td>
            <td>
              <input type="number" class="w-px-135 descuento_p_${cont_p}" name="descuento_p[]" value="${element.descuento}" onkeyup="modificarSubtotales_p()" onchange="modificarSubtotales_p()">
            </td>
            <td class="text-right">
              <span class="text-right subtotal_producto_p_${cont_p}" name="subtotal_producto_p" id="subtotal_producto_p">0.00</span>
            </td>
            <td>
              <button type="button" onclick="modificarSubtotales_p()" class="btn btn-info"><i class="fas fa-sync"></i></button>
              </td>
          </tr>`;

          detalles_p = detalles_p + 1;

          $("#detalles_af_proyecto").append(fila);

          array_class_activo_p.push({ id_cont_p: cont_p });

          cont_p++;
          evaluar();
        });

        modificarSubtotales_p();
      } else {
        toastr.error("<h3>Sin Activos.</h3> <br> Este registro no tiene Activos para mostrar");
        $("#igv_comp_proy").html("S/. 0.00");
        $("#igv_comp_proy").html("S/. 0.00");
        $("#total_proy").html("S/. 0.00");
      }
    } else {
      toastr.error("<h3>Error.</h3> <br> Este registro tiene errores, o esta vacio");
    }
  });
}

function modificarSubtotales_p() {
  console.log(array_class_activo_p);
  if ($("#tipo_comprobante_proy").select2("val") == null) {
    $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

    $("#colspan_subtotal_p").attr("colspan", 5); //cambiamos el: colspan

    if (array_class_activo_p.length === 0) {
    } else {
      array_class_activo_p.forEach((element, index) => {

        var cantidad = parseFloat($(`.cantidad_p_${element.id_cont_p}`).val());
        var precio_con_igv = parseFloat($(`.precio_con_igv_p_${element.id_cont_p}`).val());
        var descuento = parseFloat($(`.descuento_p_${element.id_cont_p}`).val());
        var subtotal_producto = 0;

        // Calculamos: IGV
        var precio_sin_igv = precio_con_igv;
        $(`.precio_sin_igv_p_${element.id_cont_p}`).val(precio_sin_igv);

        // Calculamos: precio + IGV
        var igv = 0;
        $(`.precio_igv_p_${element.id_cont_p}`).val(igv);

        // Calculamos: Subtotal de cada producto
        subtotal_producto = cantidad * parseFloat(precio_con_igv) - descuento;
        $(`.subtotal_producto_p_${element.id_cont_p}`).html(formato_miles(subtotal_producto.toFixed(4)));
      });
      calcularTotalesSinIgv_p();
    }
  } else {
    if ($("#tipo_comprobante_proy").select2("val") == "Factura") {
      $(".hidden").show(); //Mostramos: IGV, PRECIO SIN IGV

      $("#colspan_subtotal_p").attr("colspan", 7); //cambiamos el: colspan

      if (array_class_activo_p.length === 0) {
      } else {
          array_class_activo_p.forEach((element, index) => {

          var cantidad = parseFloat($(`.cantidad_p_${element.id_cont_p}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_p_${element.id_cont_p}`).val());
          var descuento = parseFloat($(`.descuento_p_${element.id_cont_p}`).val());
          var subtotal_producto = 0;

          // Calculamos: IGV
          var precio_sin_igv = (precio_con_igv / 1.18).toFixed(2);
          $(`.precio_sin_igv_p_${element.id_cont_p}`).val(precio_sin_igv);

          // Calculamos: precio + IGV
          var igv = (parseFloat(precio_con_igv) - parseFloat(precio_sin_igv)).toFixed(2);
          $(`.precio_igv_p_${element.id_cont_p}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - descuento;
          $(`.subtotal_producto_p_${element.id_cont_p}`).html(formato_miles(subtotal_producto.toFixed(2)));
        });

        calcularTotalesConIgv_p();
      }
    } else {
      $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

      $("#colspan_subtotal_p").attr("colspan", 5); //cambiamos el: colspan

      if (array_class_activo_p.length === 0) {
      } else {
          array_class_activo_p.forEach((element, index) => {

          var cantidad = parseFloat($(`.cantidad_p_${element.id_cont_p}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_p_${element.id_cont_p}`).val());
          var descuento = parseFloat($(`.descuento_p_${element.id_cont_p}`).val());
          var subtotal_producto = 0;

          // Calculamos: IGV
          var precio_sin_igv = precio_con_igv;
          $(`.precio_sin_igv_p_${element.id_cont_p}`).val(precio_sin_igv);

          // Calculamos: precio + IGV
          var igv = 0;
          $(`.precio_igv_p_${element.id_cont_p}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - descuento;
          $(`.subtotal_producto_p_${element.id_cont_p}`).html(formato_miles(subtotal_producto.toFixed(4)));
        });

        calcularTotalesSinIgv_p();
      }
    }
  }
  toastr.success("Precio Actualizado !!!");
}

function calcularTotalesSinIgv_p() {
  var total = 0.0;
  var igv = 0;
  var mtotal = 0;

  if (array_class_activo_p.length === 0) {
  } else {
    array_class_activo_p.forEach((element, index) => {
      total += parseFloat(quitar_formato_miles($(`.subtotal_producto_p_${element.id_cont_p}`).text()));
    });

    $("#subtotal_proy").html("S/. " + formato_miles(total));
    $("#subtotal_compra_proy").val(parseFloat(total).toFixed(2));

    $("#igv_comp_proy").html("S/. 0.00");
    $("#igv_compra_proy").val(0.0);

    $("#total_proy").html("S/. " + formato_miles(total.toFixed(2)));
    $("#total_compra_proy").val(parseFloat(total).toFixed(2));
  }
} 

function calcularTotalesConIgv_p() {
var igv = 0;
var total = 0.0;

var subotal_sin_igv = 0;

array_class_activo_p.forEach((element, index) => {
  total += parseFloat(quitar_formato_miles($(`.subtotal_producto_p_${element.id_cont_p}`).text()));
});

subotal_sin_igv = (parseFloat(total) / 1.18).toFixed(2);
igv = (parseFloat(total) - parseFloat(subotal_sin_igv)).toFixed(2);

$("#subtotal_proy").html(`S/. ${formato_miles(subotal_sin_igv)}`);
$("#subtotal_compra_proy").val(parseFloat(subotal_sin_igv).toFixed(2));

$("#igv_comp_proy").html("S/. " + formato_miles(igv));
$("#igv_compra_proy").val(igv);

$("#total_proy").html("S/. " + formato_miles(total.toFixed(2)));
$("#total_compra_proy").val(parseFloat(total).toFixed(2));

total = 0.0;
}

function eliminarDetalle_p(indice) {
  $("#fila" + indice).remove();

  array_class_activo_p.forEach(function (car, index, object) {
    if (car.id_cont_p === indice) {
      object.splice(index, 1);
    }
  });

  modificarSubtotales_p();

  detalles_p = detalles_p - 1;

  evaluar();

  toastr.warning("Activo removido.");
}

function guardaryeditar_compras_af_p(e) {

  var formData = new FormData($("#form-compra-activos-p")[0]);

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
        url: "../ajax/resumen_activos_fijos_general.php?op=guardaryeditarcompra_por_proyecto",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
          if (datos == "ok") {
            // toastr.success("Usuario registrado correctamente");
            Swal.fire("Correcto!", "Compra guardada correctamente", "success");

            tabla_maaquinaria1.ajax.reload();
            tabla_equipo1.ajax.reload();
            tabla_herramientas1.ajax.reload();
            tabla_oficina1.ajax.reload();
            
            listar_tbla_principal_maq();
            listar_tbla_principal_equip();
            listar_tbla_principal_herra();
            listar_tbla_principal_oficina();
            
            regresar_div2();
            limpiar_p()
            cont_p = 0;
          } else {
            // toastr.error(datos);
            Swal.fire("Error!", datos, "error");
          }
        },
      });
    }
  });
}

//Función limpiar
function limpiar_p() {
  $(".tooltip").removeClass('show');

  //Mostramos los selectProveedor
  $.post("../ajax/all_activos_fijos.php?op=selectProveedor", function (r) {
    $("#idproveedor_proy").html(r);
  });

  $("#idcompra_af_proy").val();
  $("#idproyecto_proy").val();

  $("#idproveedor_proy").val("null").trigger("change");
  $("#tipo_comprobante_proy").val("Ninguno").trigger("change");

  $("#serie_comprobante_proy").val("");
  $("#descripcion_proy").val("");

  $(".filas").remove();
  $("#total").html("0");

  $("#subtotal_proy").html("");
  $("#subtotal_compra_proy").val("");

  $("#igv_comp_proy").html("");
  $("#igv_compra_proy").val("");

  $("#total_proy").html("");
  $("#total_compra_proy").val("");

  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

// :::::::::::::: F I N  S E C C I O N   E D I T A R  C O M P R A S  P O R  P R O Y E C T O  :::::::::::::::
//Función Listarmateriales para general y proyecto segun la funcion
function listarmateriales() {
  console.log(selec_op);
  tablamateriales = $("#tblamateriales").dataTable({
    responsive: true,
    lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [],
    ajax: {
      url: `../ajax/resumen_activos_fijos_general.php?op=${selec_op}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[3] != '') {
        $("td", row).eq(3).addClass('text-right');
      }  
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    // order: [[0, "desc"]], //Ordenar (columna,orden)
  }).DataTable();
}
$(function () {
  // validando form compras 
  $("#form-compras").validate({
    rules: {
      idproveedor: { required: true },
      tipo_comprovante: { required: true },
      serie_comprovante: { minlength: 2 },
      descripcion: { minlength: 4 },
      fecha_compra: { required: true },
    },
    messages: {
      idproveedor: {
        required: "Por favor debe seleccionar un proveedor.",
      },
      tipo_comprovante: {
        required: "Por favor debe seleccionar tipo de comprobante.",
      },
      serie_comprovante: {
        minlength: "mayor a 2 caracteres",
      },
      descripcion: {
        minlength: "mayor a 4 caracteres",
      },
      fecha_compra: {
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

    submitHandler: function (form) {
      guardaryeditar_compras(form);
    },
  });
  //compras por proyecto
  $("#form-compra-activos-p").validate({
    rules: {
      idproveedor_proy: { required: true },
      tipo_comprobante_proy: { required: true },
      serie_comprobante_proy: { minlength: 2 },
      descripcion_proy: { minlength: 4 },
      fecha_compra_proy: { required: true },
    },
    messages: {
      idproveedor_proy: {
        required: "Por favor debe seleccionar un proveedor.",
      },
      tipo_comprobante_proy: {
        required: "Por favor debe seleccionar tipo de comprobante.",
      },
      serie_comprobante_proy: {
        minlength: "mayor a 2 caracteres",
      },
      descripcion_proy: {
        minlength: "mayor a 4 caracteres",
      },
      fecha_compra_proy: {
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

    submitHandler: function (form) {
      guardaryeditar_compras_af_p(form);
    },
  });
  
  //Validar formulario PROVEEDOR
  $("#form-proveedor").validate({
    rules: {
      tipo_documento: { required: true },
      num_documento: { required: true, minlength: 6, maxlength: 20 },
      nombre: { required: true, minlength: 6, maxlength: 100 },
      direccion: { minlength: 5, maxlength: 70 },
      telefono: { minlength: 8 },
      c_detracciones: { minlength: 14, maxlength: 14 },
      c_bancaria: { minlength: 14, maxlength: 14 },
      banco: { required: true },
      titular_cuenta: { minlength: 4 },
    },
    messages: {
      tipo_documento: {
        required: "Por favor selecione un tipo de documento",
      },
      num_documento: {
        required: "Ingrese un número de documento",
        minlength: "El número documento debe tener MÍNIMO 6 caracteres.",
        maxlength: "El número documento debe tener como MÁXIMO 20 caracteres.",
      },
      nombre: {
        required: "Por favor ingrese los nombres y apellidos",
        minlength: "El número documento debe tener MÍNIMO 6 caracteres.",
        maxlength: "El número documento debe tener como MÁXIMO 100 caracteres.",
      },
      direccion: {
        minlength: "La dirección debe tener MÍNIMO 5 caracteres.",
        maxlength: "La dirección debe tener como MÁXIMO 70 caracteres.",
      },
      telefono: {
        minlength: "El teléfono debe tener  9 caracteres.",
      },
      c_detracciones: {
        minlength: "El número documento debe tener 14 caracteres.",
      },
      c_bancaria: {
        minlength: "El número documento debe tener 14 caracteres.",
      },
      banco: {
        required: "Por favor  seleccione un banco",
      },
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
      guardarproveedor(e);
    },
  });


});

/* PREVISUALIZAR LAS IMAGENES */
function addImage(e, id) {
  // colocamos cargando hasta que se vizualice
  $("#" + id + "_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

  console.log(id);

  var file = e.target.files[0], imageType = /image.*/;

  if (e.target.files[0]) {
    var sizeByte = file.size;

    var sizekiloBytes = parseInt(sizeByte / 1024);

    var sizemegaBytes = sizeByte / 1000000; 

    if (!file.type.match(imageType)) {
       
      // toastr.error("Este tipo de ARCHIVO no esta permitido <br> elija formato: <b>.png .jpeg .jpg .webp etc... </b>");
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Este tipo de ARCHIVO no esta permitido elija formato: .png .jpeg .jpg .webp etc...',
        showConfirmButton: false,
        timer: 1500
      });

      $("#" + id + "_i").attr("src", "../dist/img/default/img_defecto_activo_fijo_material.png");

    } else {

      if (sizekiloBytes <= 10240) {

        var reader = new FileReader();

        reader.onload = fileOnload;

        function fileOnload(e) {

          var result = e.target.result;

          $(`#${id}_i`).attr("src", result);

          $(`#${id}_nombre`).html(
            
            `<div class="row">
              <div class="col-md-12"> <i> ${file.name} </i></div>
              <div class="col-md-12">                
                <button class="btn btn-danger btn-block btn-xs" onclick="${id}_eliminar();" type="button" >
                  <i class="far fa-trash-alt"></i>
                </button>
              </div>               
            </div>`               
          );

          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `El documento: ${file.name.toUpperCase()} es aceptado.`,
            showConfirmButton: false,
            timer: 1500
          });
        }

        reader.readAsDataURL(file);
      } else {
         
        Swal.fire({
          position: 'top-end',
          icon: 'warning',
          title: `El documento: ${file.name.toUpperCase()} es muy pesado. Tamaño máximo 10mb`,
          showConfirmButton: false,
          timer: 1500
        })
        $("#" + id + "_i").attr("src", "../dist/img/default/img_error.png");

        $("#" + id).val("");
      }
    }
  } else {
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Seleccione un documento',
      showConfirmButton: false,
      timer: 1500
    })

    $("#" + id + "_i").attr("src", "../dist/img/default/img_defecto_activo_fijo_material.png");

    $("#" + id + "_nombre").html("");
  }
}

/* PREVISUALIZAR LOS DOCUMENTOS */
function addDocs(e,id) {

  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');	console.log(id);

	var file = e.target.files[0], archivoType = /image.*|application.*/;
	
	if (e.target.files[0]) {
    
		var sizeByte = file.size; console.log(file.type);

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(archivoType) ){
			// return;
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Este tipo de ARCHIVO no esta permitido elija formato: .pdf, .png. .jpeg, .jpg, .jpe, .webp, .svg',
        showConfirmButton: false,
        timer: 1500
      });

      $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >'); 

		}else{

			if (sizekiloBytes <= 40960) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;

          // cargamos la imagen adecuada par el archivo
				  if ( extrae_extencion(file.name) == "doc") {
            $("#"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
          } else {
            if ( extrae_extencion(file.name) == "docx" ) {
              $("#"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
            }else{
              if ( extrae_extencion(file.name) == "pdf" ) {
                $("#"+id+"_ver").html(`<iframe src="${result}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
              }else{
                if ( extrae_extencion(file.name) == "csv" ) {
                  $("#"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
                } else {
                  if ( extrae_extencion(file.name) == "xls" ) {
                    $("#"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
                  } else {
                    if ( extrae_extencion(file.name) == "xlsx" ) {
                      $("#"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                    } else {
                      if ( extrae_extencion(file.name) == "xlsm" ) {
                        $("#"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                      } else {
                        if (
                          extrae_extencion(file.name) == "jpeg" || extrae_extencion(file.name) == "jpg" || extrae_extencion(file.name) == "jpe" ||
                          extrae_extencion(file.name) == "jfif" || extrae_extencion(file.name) == "gif" || extrae_extencion(file.name) == "png" ||
                          extrae_extencion(file.name) == "tiff" || extrae_extencion(file.name) == "tif" || extrae_extencion(file.name) == "webp" ||
                          extrae_extencion(file.name) == "bmp" || extrae_extencion(file.name) == "svg" ) {

                          $("#"+id+"_ver").html(`<img src="${result}" alt="" width="50%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
                          
                        } else {
                          $("#"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                        }
                        
                      }
                    }
                  }
                }
              }
            }
          } 
					$("#"+id+"_nombre").html(`<div class="row">
            <div class="col-md-12">
              <i> ${file.name} </i>
            </div>
            <div class="col-md-12">
              <button class="btn btn-danger btn-block btn-xs" onclick="${id}_eliminar();" type="button" ><i class="far fa-trash-alt"></i></button>
            </div>
          </div>`);

          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `El documento: ${file.name.toUpperCase()} es aceptado.`,
            showConfirmButton: false,
            timer: 1500
          });
				}

				reader.readAsDataURL(file);

			} else {
        Swal.fire({
          position: 'top-end',
          icon: 'warning',
          title: `El documento: ${file.name.toUpperCase()} es muy pesado.`,
          showConfirmButton: false,
          timer: 1500
        });

        $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
        $("#"+id+"_nombre").html("");
				$("#"+id).val("");
			}
		}
	}else{
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Seleccione un documento',
      showConfirmButton: false,
      timer: 1500
    });
		 
    $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
		$("#"+id+"_nombre").html("");
    $("#"+id).val("");
	}	
}

// recargar un doc para ver
function re_visualizacion(id, carpeta) {

  $("#doc"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>'); console.log(id);

  pdffile     = document.getElementById("doc"+id+"").files[0];

  var antiguopdf  = $("#doc_old_"+id+"").val();

  if(pdffile === undefined){

    if (antiguopdf == "") {

      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Seleccione un documento',
        showConfirmButton: false,
        timer: 1500
      })

      $("#doc"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

		  $("#doc"+id+"_nombre").html("");

    } else {
      if ( extrae_extencion(antiguopdf) == "doc") {
        $("#doc"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
        toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
      } else {
        if ( extrae_extencion(antiguopdf) == "docx" ) {
          $("#doc"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
          toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
        } else {
          if ( extrae_extencion(antiguopdf) == "pdf" ) {
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/compra/${carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
            toastr.success('Documento vizualizado correctamente!!!')
          } else {
            if ( extrae_extencion(antiguopdf) == "csv" ) {
              $("#doc"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
              toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
            } else {
              if ( extrae_extencion(antiguopdf) == "xls" ) {
                $("#doc"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
                toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
              } else {
                if ( extrae_extencion(antiguopdf) == "xlsx" ) {
                  $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                  toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                } else {
                  if ( extrae_extencion(antiguopdf) == "xlsm" ) {
                    $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                    toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                  } else {
                    if (
                      extrae_extencion(antiguopdf) == "jpeg" || extrae_extencion(antiguopdf) == "jpg" || extrae_extencion(antiguopdf) == "jpe" ||
                      extrae_extencion(antiguopdf) == "jfif" || extrae_extencion(antiguopdf) == "gif" || extrae_extencion(antiguopdf) == "png" ||
                      extrae_extencion(antiguopdf) == "tiff" || extrae_extencion(antiguopdf) == "tif" || extrae_extencion(antiguopdf) == "webp" ||
                      extrae_extencion(antiguopdf) == "bmp" || extrae_extencion(antiguopdf) == "svg" ) {
  
                      $("#doc"+id+"_ver").html(`<img src="../dist/docs/compra/${carpeta}/${antiguopdf}" alt="" onerror="this.src='../dist/svg/error-404-x.svg';" width="50%" >`);
                      toastr.success('Documento vizualizado correctamente!!!');
                    } else {
                      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                      toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                    }                    
                  }
                }
              }
            }
          }
        }
      }      
    }
    // console.log('hola'+dr);
  }else{

    pdffile_url=URL.createObjectURL(pdffile);

    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(pdffile.name) == "doc") {
      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
      toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
    } else {
      if ( extrae_extencion(pdffile.name) == "docx" ) {
        $("#doc"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
        toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
      }else{
        if ( extrae_extencion(pdffile.name) == "pdf" ) {
          $("#doc"+id+"_ver").html('<iframe src="'+pdffile_url+'" frameborder="0" scrolling="no" width="100%" height="310"> </iframe>');
          toastr.success('Documento vizualizado correctamente!!!');
        }else{
          if ( extrae_extencion(pdffile.name) == "csv" ) {
            $("#doc"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
            toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
          } else {
            if ( extrae_extencion(pdffile.name) == "xls" ) {
              $("#doc"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
              toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
            } else {
              if ( extrae_extencion(pdffile.name) == "xlsx" ) {
                $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
              } else {
                if ( extrae_extencion(pdffile.name) == "xlsm" ) {
                  $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                  toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
                } else {
                  if (
                    extrae_extencion(pdffile.name) == "jpeg" || extrae_extencion(pdffile.name) == "jpg" || extrae_extencion(pdffile.name) == "jpe" ||
                    extrae_extencion(pdffile.name) == "jfif" || extrae_extencion(pdffile.name) == "gif" || extrae_extencion(pdffile.name) == "png" ||
                    extrae_extencion(pdffile.name) == "tiff" || extrae_extencion(pdffile.name) == "tif" || extrae_extencion(pdffile.name) == "webp" ||
                    extrae_extencion(pdffile.name) == "bmp" || extrae_extencion(pdffile.name) == "svg" ) {

                    $("#doc"+id+"_ver").html(`<img src="${pdffile_url}" alt="" width="50%" >`);
                    toastr.success('Documento vizualizado correctamente!!!');
                  } else {
                    $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                    toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
                  }                  
                }
              }
            }
          }
        }
      }
    }     	
    console.log(pdffile);
  }
}

function dowload_pdf() {
  toastr.success("El documento se descargara en breve!!");
}

function extrae_extencion(filename) {
  return filename.split(".").pop();
}


/**formato_miles */
function formato_miles(num) {
  if (!num || num == "NaN") return "0.0";
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

function quitar_formato_miles(numero) {
  let inVal = numero.replace(/,/g, "");
  return inVal;
}


// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_documento").val();

  let dni_ruc = $("#num_documento").val();

  if (tipo_doc == "DNI") {
    if (dni_ruc.length == "8") {
      $.post("../ajax/compra.php?op=reniec", { dni: dni_ruc }, function (data, status) {
        data = JSON.parse(data);

        console.log(data);

        if (data.success == false) {
          $("#search").show();

          $("#charge").hide();

          toastr.error("Es probable que el sistema de busqueda esta en mantenimiento o los datos no existe en la RENIEC!!!");
        } else {
          $("#search").show();

          $("#charge").hide();

          $("#nombre").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

          toastr.success("Cliente encontrado!!!!");
        }
      });
    } else {
      $("#search").show();

      $("#charge").hide();

      toastr.info("Asegurese de que el DNI tenga 8 dígitos!!!");
    }
  } else {
    if (tipo_doc == "RUC") {
      if (dni_ruc.length == "11") {
        $.post("../ajax/compra.php?op=sunat", { ruc: dni_ruc }, function (data, status) {
          data = JSON.parse(data);

          console.log(data);
          if (data.success == false) {
            $("#search").show();

            $("#charge").hide();

            toastr.error("Datos no encontrados en la SUNAT!!!");
          } else {
            if (data.estado == "ACTIVO") {
              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);

              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);
              // $("#direccion").val(data.direccion);
              toastr.success("Cliente encontrado");
            } else {
              toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);

              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);

              // $("#direccion").val(data.direccion);
            }
          }
        });
      } else {
        $("#search").show();

        $("#charge").hide();

        toastr.info("Asegurese de que el RUC tenga 11 dígitos!!!");
      }
    } else {
      if (tipo_doc == "CEDULA" || tipo_doc == "OTRO") {
        $("#search").show();

        $("#charge").hide();

        toastr.info("No necesita hacer consulta");
      } else {
        $("#tipo_doc").addClass("is-invalid");

        $("#search").show();

        $("#charge").hide();

        toastr.error("Selecione un tipo de documento");
      }
    }
  }
}

/**Redondear */
function redondearExp(numero, digitos) {
  function toExp(numero, digitos) {
    let arr = numero.toString().split("e");
    let mantisa = arr[0],
      exponente = digitos;
    if (arr[1]) exponente = Number(arr[1]) + digitos;
    return Number(mantisa + "e" + exponente.toString());
  }
  let entero = Math.round(toExp(Math.abs(numero), digitos));
  return Math.sign(numero) * toExp(entero, -digitos);
}

// ver imagen grande del producto agregado a la compra
function ver_img_activo(img, nombre) {
  console.log(img, nombre);
  $("#ver_img_activo").attr("src", `${img}`);
  $(".nombre-img-activo").html(nombre);
  $("#modal-ver-img-activo").modal("show");
}

init();