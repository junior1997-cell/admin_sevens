var tabla;
var tabla2;

//Función que se ejecuta al inicio
function init(){
	
	listar_tbla_principal(localStorage.getItem('nube_idproyecto'));

	$("#mResumenActivosFijosGeneral").addClass("menu-open");

	/*$("#mResumenActivosFijosGeneral").addClass("active");

	$("#lResumenInsumos").addClass("active")*/;
}

//Función Listar
function listar_tbla_principal()
{

	tabla=$('#tabla-resumen-insumos').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
		"ajax":	{
      url: '../ajax/resumen_activos_fijos_general.php?op=listar_tbla_principal',
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

  $.post("../ajax/resumen_activos_fijos_general.php?op=suma_total_compras", {}, function (data, status) {

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

function ver_precios_y_mas(idproducto, nombre_producto, precio_promedio, subtotal_x_producto ) {

  $(".nombre-producto-modal-titel").html('Producto: <b>'+ nombre_producto +'</b>');
	$("#modal-ver-precios").modal("show");
  $(".precio_promedio").html(precio_promedio);
  $(".subtotal_x_producto").html(subtotal_x_producto);

	tabla2 = $('#tabla-precios').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
		buttons: [	],
		"ajax":	{
      url: `../ajax/resumen_activos_fijos_general.php?op=ver_precios_y_mas&idproducto=${idproducto}`,
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