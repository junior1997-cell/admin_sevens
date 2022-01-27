var tabla;
var tabla2;

//Función que se ejecuta al inicio
function init(){
	
	listar_tbla_principal();

	$("#bloc_Compras").addClass("menu-open");

	$("#mCompra").addClass("active");

	$("#lResumenInsumos").addClass("active");
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
      url: '../ajax/resumen_insumos.php?op=listar_tbla_principal',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
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
		"iDisplayLength": 5,//Paginación
	  "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

function mostrar_detalle( id ) {

	$("#modal-ver-usuarios").modal("show");

	tabla2 = $('#tabla-usuarios').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
		buttons: [	],
		"ajax":	{
      url: '../ajax/permiso.php?op=listar_usuario&id='+id,
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


init();