var tabla_unidades_m;

//Función que se ejecuta al inicio
function init() {
  listar_unidades_m();
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  //$("#lAllMateriales").addClass("active");


  $("#guardar_registro_unidad_m").on("click", function (e) {
    
    $("#submit-form-unidad-m").submit();
  });

  // Formato para telefono
  $("[data-mask]").inputmask();


}
//Función limpiar
function limpiar_unidades_m() {
  //Mostramos los Materiales
  $("#idunidad_medida").val("");
  $("#nombre_medida").val(""); 
  $("#abreviacion").val(""); 
}

//Función Listar
function listar_unidades_m() {

  tabla_unidades_m=$('#tabla-unidades-m').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5','pdf'],
    "ajax":{
        url: '../ajax/unidades_m.php?op=listar__unidades_m',
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

function guardaryeditar_unidades_m(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-unidad-m")[0]);
 
  $.ajax({
    url: "../ajax/unidades_m.php?op=guardaryeditar_unidades_m",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla_unidades_m.ajax.reload();
         
				limpiar();

        $("#modal-agregar-unidad-m").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar_unidades_m(idunidad_medida) {
  console.log(idunidad_medida);

  $("#modal-agregar-unidad-m").modal("show")

  $.post("../ajax/unidades_m.php?op=mostrar_unidades_m", { idunidad_medida: idunidad_medida }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idunidad_medida").val(data.idunidad_medida);
    $("#nombre_medida").val(data.nombre_medida); 
    $("#abreviacion").val(data.abreviacion); 
  });
}

//Función para desactivar registros
function desactivar_unidades_m(idunidad_medida) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar el registro?",
    text: "Unidad de medida",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/unidades_m.php?op=desactivar_unidades_m", { idunidad_medida: idunidad_medida }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla_unidades_m.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar_unidades_m(idunidad_medida) {
  Swal.fire({
    title: "¿Está Seguro de  Activar el registro?",
    text: "Unidad de medida",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/unidades_m.php?op=activar_unidades_m", { idunidad_medida: idunidad_medida }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla_unidades_m.ajax.reload();
      });
      
    }
  });      
}


init();

$(function () {

  
  $.validator.setDefaults({

    submitHandler: function (e) {
        guardaryeditar_unidades_m(e);
      
    },
  });

  $("#form-unidad-m").validate({
    rules: {
      nombre_medida: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre_medida: {
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
