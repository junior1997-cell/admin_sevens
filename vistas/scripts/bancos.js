var tabla_bancos;

//Función que se ejecuta al inicio
function init() {
  listar_bancos();
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  //$("#lAllMateriales").addClass("active");


  $("#guardar_registro").on("click", function (e) {
    
    $("#submit-form-bancos").submit();
  });

  // Formato para telefono
  $("[data-mask]").inputmask();


}
//Función limpiar
function limpiar() {
  //Mostramos los Materiales
  $("#idbancos").val("");
  $("#nombre").val(""); 
}

//Función Listar
function listar_bancos() {

  tabla_bancos=$('#tabla-bancos').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5','pdf'],
    "ajax":{
        url: '../ajax/bancos.php?op=listar',
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

function guardaryeditar_bancos(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-bancos")[0]);
 
  $.ajax({
    url: "../ajax/bancos.php?op=guardaryeditar_bancos",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla_bancos.ajax.reload();
         
				limpiar();

        $("#modal-agregar-bancos").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar_bancos(idbancos) {
  console.log(idbancos);

  $("#modal-agregar-bancos").modal("show")

  $.post("../ajax/bancos.php?op=mostrar_bancos", { idbancos: idbancos }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idbancos").val(data.idbancos);
    $("#nombre").val(data.nombre); 
  });
}

//Función para desactivar registros
function desactivar_bancos(idbancos) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar el registro?",
    text: "Banco",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/bancos.php?op=desactivar_bancos", { idbancos: idbancos }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla_bancos.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar_bancos(idbancos) {
  Swal.fire({
    title: "¿Está Seguro de  Activar el registro?",
    text: "Banco",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/bancos.php?op=activar_bancos", { idbancos: idbancos }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla_bancos.ajax.reload();
      });
      
    }
  });      
}


init();

$(function () {

  
  $.validator.setDefaults({

    submitHandler: function (e) {
        guardaryeditar_bancos(e);
      
    },
  });

  $("#form-bancos").validate({
    rules: {
      nombre: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre: {
        required: "Por favor ingrese nombre ", 
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
