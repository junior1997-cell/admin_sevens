var tabla;

//Función que se ejecuta al inicio
function init() {
  listar();
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

 // $("#lBancoColor").addClass("active");


  $("#guardar_registro_color").on("click", function (e) {
    //console.log('jjjjjjjjjjjjjjjjjjjj');
    $("#submit-form-color").submit();
  });

  // Formato para telefono
  $("[data-mask]").inputmask();


}
//Función limpiar
function limpiar() {
  //Mostramos los Materiales
  $("#idcolor").val("");
  $("#nombre_color").val(""); 
}

//Función Listar
function listar() {

  tabla=$('#tabla-colores').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5','pdf'],
    "ajax":{
        url: '../ajax/color.php?op=listar',
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
    "iDisplayLength": 10,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar

function guardaryeditar_color(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-color")[0]);
 
  $.ajax({
    url: "../ajax/color.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-color").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idcolor) {
  console.log(idcolor);

  $("#modal-agregar-color").modal("show")

  $.post("../ajax/color.php?op=mostrar", { idcolor: idcolor }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idcolor").val(data.idcolor);
    $("#nombre_color").val(data.nombre_color); 
  });
}

//Función para desactivar registros
function desactivar(idcolor) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar el registro?",
    text: "Color",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/color.php?op=desactivar", { idcolor: idcolor }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar(idcolor) {
  Swal.fire({
    title: "¿Está Seguro de  Activar el registro?",
    text: "Color",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/color.php?op=activar", { idcolor: idcolor }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla.ajax.reload();
      });
      
    }
  });      
}


init();

$(function () {

  
  $.validator.setDefaults({

    submitHandler: function (e) {
      console.log('kkkkkk');
      guardaryeditar_color(e);
      
    },
  });

  $("#form-color").validate({
    rules: {
      nombre_color: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre_color: {
        required: "Por favor ingrese nombre", 
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

