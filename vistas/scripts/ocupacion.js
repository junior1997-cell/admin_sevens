var tabla_ocupacion;

//Función que se ejecuta al inicio
function init() {
  listar_ocupacion();
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  //$("#lAllMateriales").addClass("active");

  $("#guardar_registro_ocupacion").on("click", function (e) {
    
    $("#submit-form-ocupacion").submit();
  });

  // Formato para telefono
  $("[data-mask]").inputmask();

}
//Función limpiar
function limpiar_ocupacion() {
  //Mostramos los Materiales
  $("#idocupacion").val("");
  $("#nombre_ocupacion").val(""); 
}

//Función Listar
function listar_ocupacion() {

  tabla_ocupacion=$('#tabla-ocupacion').dataTable({
    "responsive": true,
    "lengthChange": false,
    //"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['excelHtml5'],
    "ajax":{
        url: '../ajax/ocupacion.php?op=listar_ocupacion',
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
        "copySuccess": {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      }
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar

function guardaryeditar_ocupacion(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-ocupacion")[0]);
 
  $.ajax({
    url: "../ajax/ocupacion.php?op=guardaryeditar_ocupacion",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla_ocupacion.ajax.reload();
         
				limpiar();

        $("#modal-agregar-ocupacion").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar_ocupacion(idocupacion) {
  console.log(idocupacion);

  $("#modal-agregar-ocupacion").modal("show")

  $.post("../ajax/ocupacion.php?op=mostrar_ocupacion", { idocupacion: idocupacion }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idocupacion").val(data.idocupacion);
    $("#nombre_ocupacion").val(data.nombre_ocupacion);
  });
}

//Función para desactivar registros
function desactivar_ocupacion(idocupacion) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar el registro?",
    text: "Ocupación",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/ocupacion.php?op=desactivar_ocupacion", { idocupacion: idocupacion }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla_ocupacion.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar_ocupacion(idocupacion) {
  Swal.fire({
    title: "¿Está Seguro de  Activar el registro?",
    text: "Ocupación",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/ocupacion.php?op=activar_ocupacion", { idocupacion: idocupacion }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla_ocupacion.ajax.reload();
      });
      
    }
  });      
}


init();

$(function () {

  
  $.validator.setDefaults({

    submitHandler: function (e) {
        guardaryeditar_ocupacion(e);
      
    },
  });

  $("#form-ocupacion").validate({
    rules: {
      nombre_ocupacion: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre_ocupacion: {
        required: "Por favor ingrese nombre.", 
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


  });
});
