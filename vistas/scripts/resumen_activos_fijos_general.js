//tbl maaquinaria
var tabla_maaquinaria1;
var tabla_maaquinaria2;
//tbl equipo
var tabla_equipo1;
var tabla_equipo2;
// tbl herramientas
var tabla_herramientas1;
var tabla_herramientas2;
// tbl oficina
var tabla_oficina1;
var tabla_oficina2;


//Función que se ejecuta al inicio
function init(){
	
	listar_tbla_principal_maq();
	listar_tbla_principal_equip();
  listar_tbla_principal_herra();
  listar_tbla_principal_oficina();

	//$("#mResumenActivosFijosGeneral").addClass("menu-open");

	$("#mResumenActivosFijosGeneral").addClass("active");

	//$("#lResumenInsumos").addClass("active");
}

/**tipo clasificacion MAQUINARIA */
function listar_tbla_principal_maq()
{

	tabla_maaquinaria1=$('#tabla-resumen-maquinarias').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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

      // columna: Cantidad
      if (data[0] != '') {
        $("td", row).eq(0).addClass("text-nowrap");   
          
      }

      // columna: Cantidad
      if (data[2] != '') {
        $("td", row).eq(2).addClass("text-center");   
         
      }

      // columna: Precio promedio
      if (data[3] != '') {
        $("td", row).eq(3).addClass("modal-footer justify-content-between");         
      }

      // columna: Precio actual
      if (data[4] != '') {
        $("td", row).eq(4).addClass("text-right");         
      }
      // columna: Suma Total
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-right");         
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
	  "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();

  $.post("../ajax/resumen_activos_fijos_general.php?op=suma_total_compras_maq", {}, function (data, status) {

    data = JSON.parse(data); //console.log('-------'); console.log(data); 

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

  $(".nombre-producto-modal-titel").html('Producto: <b>'+ nombre_producto +'</b>');
	$("#modal-ver-precios-maquinarias").modal("show");
  $(".precio_promedio").html(precio_promedio);
  $(".subtotal_x_producto").html(subtotal_x_producto);

	tabla_maaquinaria2 = $('#tabla-precios-maquinarias').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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
		"iDisplayLength": 5,//Paginación
		"order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();

  
}
/**tipo clasificacion EQUIPOS */
function listar_tbla_principal_equip()
{

	tabla_equipo1=$('#tabla-resumen-equipos').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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

      // columna: Cantidad
      if (data[0] != '') {
        $("td", row).eq(0).addClass("text-nowrap");   
          
      }

      // columna: Cantidad
      if (data[2] != '') {
        $("td", row).eq(2).addClass("text-center");   
         
      }

      // columna: Precio promedio
      if (data[3] != '') {
        $("td", row).eq(3).addClass("modal-footer justify-content-between");         
      }

      // columna: Precio actual
      if (data[4] != '') {
        $("td", row).eq(4).addClass("text-right");         
      }
      // columna: Suma Total
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-right");         
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
	  "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
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

  $(".nombre-equipos-modal-titel").html('Producto: <b>'+ nombre_producto +'</b>');
	$("#modal-ver-precios-equipos").modal("show");
  $(".precio_promedio_equipos").html(precio_promedio);
  $(".subtotal_x_producto_equipos").html(subtotal_x_producto);

	tabla_equipo2 = $('#tabla-precios-equipos').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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
		"iDisplayLength": 5,//Paginación
		"order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();

  
}

/**tipo clasificacion HERRAMIENTAS */
function listar_tbla_principal_herra()
{

	tabla_herramientas1=$('#tabla-resumen-herramientas').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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

      // columna: Cantidad
      if (data[0] != '') {
        $("td", row).eq(0).addClass("text-nowrap");   
          
      }

      // columna: Cantidad
      if (data[2] != '') {
        $("td", row).eq(2).addClass("text-center");   
         
      }

      // columna: Precio promedio
      if (data[3] != '') {
        $("td", row).eq(3).addClass("modal-footer justify-content-between");         
      }

      // columna: Precio actual
      if (data[4] != '') {
        $("td", row).eq(4).addClass("text-right");         
      }
      // columna: Suma Total
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-right");         
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
	  "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
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

  $(".nombre-herramientas-modal-titel").html('Producto: <b>'+ nombre_producto +'</b>');
	$("#modal-ver-precios-herramientas").modal("show");
  $(".precio_promedio_herramientas").html(precio_promedio);
  $(".subtotal_x_producto_herramientas").html(subtotal_x_producto);

	tabla_herramientas2 = $('#tabla-precios-herramientas').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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
		"iDisplayLength": 5,//Paginación
		"order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();

  
}
/**tipo clasificacion OFICINA */
function listar_tbla_principal_oficina()
{

	tabla_oficina1=$('#tabla-resumen-oficina').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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

      // columna: Cantidad
      if (data[0] != '') {
        $("td", row).eq(0).addClass("text-nowrap");   
          
      }

      // columna: Cantidad
      if (data[2] != '') {
        $("td", row).eq(2).addClass("text-center");   
         
      }

      // columna: Precio promedio
      if (data[3] != '') {
        $("td", row).eq(3).addClass("modal-footer justify-content-between");         
      }

      // columna: Precio actual
      if (data[4] != '') {
        $("td", row).eq(4).addClass("text-right");         
      }
      // columna: Suma Total
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-right");         
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
	  "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
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
        $('.suma_total_de_oficina').html( 'S/. '+ formato_miles(data.total_monto));
      }
    }    
  });
}
function ver_precios_y_mas_oficina(idproducto, nombre_producto, precio_promedio, subtotal_x_producto ) 
{

  $(".nombre-oficina-modal-titel").html('Producto: <b>'+ nombre_producto +'</b>');
	$("#modal-ver-precios-oficina").modal("show");
  $(".precio_promedio_oficina").html(precio_promedio);
  $(".subtotal_x_producto_oficina").html(subtotal_x_producto);

	tabla_oficina2 = $('#tabla-precios-oficina').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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
		"iDisplayLength": 5,//Paginación
		"order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();

  
}

/** ==========FIN CLASIFICACIONES==============0 */
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

init();