var tabla_principal; 

//Función que se ejecuta al inicio
function init() {

  // $("#bloc_PagosTrabajador").addClass("menu-open");

  $("#mPapelera").addClass("active");

  // $("#lPagosAdministrador").addClass("active");

  listar_tbla_principal(localStorage.getItem('nube_idproyecto'));

} 

//Función Listar - tabla principal
function listar_tbla_principal(nube_idproyecto) {

  tabla_principal = $('#tabla-principal').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    "ajax":{
      url: `../ajax/papelera.php?op=listar_tbla_principal&nube_idproyecto=${nube_idproyecto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[0] != '') {
        $("td", row).eq(0).addClass('text-nowrap');
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


function eliminar_permanente(nombre_tabla, nombre_id_tabla, id_tabla) {  

  Swal.fire({
    title: "¿Está Seguro de Eliminar Permanente?",
    text: "Al Eliminarlo, no podra recuperarlo.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Eliminar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/papelera.php?op=eliminar_permanente", { 
        'nombre_tabla': nombre_tabla,
        'nombre_id_tabla': nombre_id_tabla,
        'id_tabla': id_tabla
      }, function (e) {

        if (e == "ok") {          
          Swal.fire("Anulado!", "Tu registro ha sido ELIMINADO PERMANENTEMENTE.", "success");
          tabla_principal.ajax.reload();
        } else {
          Swal.fire("Error!", e, "error");
        }        
      });      
    }
  });  
}

function recuperar(nombre_tabla, nombre_id_tabla, id_tabla) {

  Swal.fire({
    title: "¿Está Seguro de Recuperar este registro?",
    text: "Al Recuperarlo, podras ver este registro en tu modulo correspondiente.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Recuperar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/papelera.php?op=recuperar", { 
        'nombre_tabla': nombre_tabla,
        'nombre_id_tabla': nombre_id_tabla,
        'id_tabla': id_tabla
      }, function (e) {

        if (e == "ok") {          
          Swal.fire("ReActivado!", "Tu registro ha sido RECUPERADO.", "success");
          tabla_principal.ajax.reload();
        } else {
          Swal.fire("Error!", e, "error");
        }        
      });      
    }
  });
}

init();
// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {

  let splits = fecha.split("-"); //console.log(splits);

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}

// convierte de una fecha(aa-mm-dd): 23-12-2021 a una fecha(dd-mm-aa): 2021-12-23
function format_a_m_d(fecha) {

  let splits = fecha.split("-"); //console.log(splits);

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}


