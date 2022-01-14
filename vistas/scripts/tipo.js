var tabla_tipo;

//Función que se ejecuta al inicio
function init() {
  listar_tipo();
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  //$("#lAllMateriales").addClass("active");

  $("#guardar_registro_tipo").on("click", function (e) {
    
    $("#submit-form-tipo").submit();
  });

  // Formato para telefono
  $("[data-mask]").inputmask();

}
//Función limpiar
function limpiar_tipo() {
  //Mostramos los Materiales
  $("#idtipo").val("");
  $("#nombre_tipo").val(""); 
}

//Función Listar
function listar_tipo() {

  tabla_tipo=$('#tabla-tipo').dataTable({
    "responsive": true,
    "lengthChange": false,
    //"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['excelHtml5'],
    "ajax":{
        url: '../ajax/tipo.php?op=listar_tipo',
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

function guardaryeditar_tipo(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-tipo")[0]);
 
  $.ajax({
    url: "../ajax/tipo.php?op=guardaryeditar_tipo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla_tipo.ajax.reload();
         
				limpiar();

        $("#modal-agregar-tipo").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar_tipo(idtipo) {
  console.log(idtipo);

  $("#modal-agregar-tipo").modal("show")

  $.post("../ajax/tipo.php?op=mostrar_tipo", { idtipo: idtipo }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idtipo").val(data.idtipo);
    $("#nombre_tipo").val(data.nombre_tipo);
  });
}

//Función para desactivar registros
function desactivar_tipo(idtipo) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar el registro?",
    text: "Tipo",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/tipo.php?op=desactivar_tipo", { idtipo: idtipo }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla_tipo.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar_tipo(idtipo) {
  Swal.fire({
    title: "¿Está Seguro de  Activar el registro?",
    text: "Tipo",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/tipo.php?op=activar_tipo", { idtipo: idtipo }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla_tipo.ajax.reload();
      });
      
    }
  });      
}


init();

$(function () {

  
  $.validator.setDefaults({

    submitHandler: function (e) {
        guardaryeditar_tipo(e);
      
    },
  });

  $("#form-tipo").validate({
    rules: {
      nombre_tipo: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre_tipo: {
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

