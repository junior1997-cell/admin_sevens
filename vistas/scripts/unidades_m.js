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

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

//Función Listar
function listar_unidades_m() {

  tabla_unidades_m=$('#tabla-unidades-m').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
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
      createdRow: function (row, data, ixdex) {    
  
        // columna: #
        if (data[0] != '') {
          $("td", row).eq(0).addClass("text-center");   
           
        }
        // columna: #
        if (data[1] != '') {
          $("td", row).eq(1).addClass("text-nowrap");   
            
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
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
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
         
				limpiar_unidades_m();

        $("#modal-agregar-unidad-m").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar_unidades_m(idunidad_medida) {
  limpiar_unidades_m();

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

//Función para desactivar registros
function eliminar_unidades_m(idunidad_medida) {
  //----------------------------
 Swal.fire({

  title: "!Elija una opción¡",
  html: "En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!",
  icon: "warning",
  showCancelButton: true,
  showDenyButton: true,
  confirmButtonColor: "#17a2b8",
  denyButtonColor: "#d33",
  cancelButtonColor: "#6c757d",    
  confirmButtonText: `<i class="fas fa-times"></i> Papelera`,
  denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,

}).then((result) => {

  if (result.isConfirmed) {
   //op=desactivar
    $.post("../ajax/unidades_m.php?op=desactivar_unidades_m", { idunidad_medida: idunidad_medida }, function (e) {

      Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");

      tabla_unidades_m.ajax.reload();
    }); 

  }else if (result.isDenied) {
   //op=eliminar
    $.post("../ajax/unidades_m.php?op=eliminar_unidades_m", { idunidad_medida: idunidad_medida }, function (e) {

      Swal.fire("Eliminado!", "Tu registro ha sido Eliminado.", "success");

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

