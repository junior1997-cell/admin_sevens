var tabla;
var tabla2;

//Función que se ejecuta al inicio
function init(){
	
	listar();

	$("#bloc_Accesos").addClass("menu-open");

	$("#mAccesos").addClass("active");

	$("#lPermiso").addClass("active");
}

//Función Listar
function listar()
{
	tabla=$('#tabla-permiso').dataTable({
		"responsive": true,
		lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: [		          
		            'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/permiso.php?op=listar',
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
				  },
		"language": {
            "lengthMenu": "Mostrar : _MENU_ registros",
            "buttons": {
            "copyTitle": "Tabla Copiada",
            "copySuccess": {
                    _: '%d líneas copiadas',
                    1: '1 línea copiada'
                }
            }
        },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

function mostrar_usuarios( id ) {

	$("#modal-ver-usuarios").modal("show");

	tabla2 = $('#tabla-usuarios').dataTable({
		"responsive": true,
		lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
		buttons: [		          
					 
				],
		"ajax":
				{
					url: '../ajax/permiso.php?op=listar_usuario&id='+id,
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
				  },
		"language": {
			"lengthMenu": "Mostrar : _MENU_ registros",
			 
		},
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
		"order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


init();