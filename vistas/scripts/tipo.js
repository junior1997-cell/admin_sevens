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
  $("#idtipo_trabajador").val("");
  $("#nombre_tipo").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

//Función Listar
function listar_tipo() {

  tabla_tipo=$('#tabla-tipo').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'pdf'],
    "ajax":{
        url: '../ajax/tipo.php?op=listar_tipo',
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
        $.post("../ajax/tipo.php?op=selecttipo_tipo", function (r) { $("#idtipo_trabjador_c").html(r); });

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar_tipo(idtipo_trabajador) {
  limpiar_tipo();

  $("#modal-agregar-tipo").modal("show")

  $.post("../ajax/tipo.php?op=mostrar_tipo", { idtipo_trabajador: idtipo_trabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idtipo_trabajador").val(data.idtipo_trabajador);
    $("#nombre_tipo").val(data.nombre);
  });
}

//Función para desactivar registros
function desactivar_tipo(idtipo_trabajador) {
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
      $.post("../ajax/tipo.php?op=desactivar_tipo", { idtipo_trabajador: idtipo_trabajador }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla_tipo.ajax.reload();
        
        $.post("../ajax/tipo.php?op=selecttipo_tipo", function (r) { $("#idtipo_trabjador_c").html(r); });
      });      
    }
  });   
}

//Función para activar registros
function activar_tipo(idtipo_trabajador) {
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
      $.post("../ajax/tipo.php?op=activar_tipo", { idtipo_trabajador: idtipo_trabajador }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla_tipo.ajax.reload();
        
        $.post("../ajax/tipo.php?op=selecttipo_tipo", function (r) { $("#idtipo_trabjador_c").html(r); });
      });
      
    }
  });      
}

//Función para eliminar registros
function eliminar_tipo(idtipo_trabajador) {
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
    $.post("../ajax/tipo.php?op=desactivar_tipo", { idtipo_trabajador: idtipo_trabajador }, function (e) {

      Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");

      tabla_tipo.ajax.reload();
      
      $.post("../ajax/tipo.php?op=selecttipo_tipo", function (r) { $("#idtipo_trabjador_c").html(r); });
    });  

  }else if (result.isDenied) {
   //op=eliminar

   $.post("../ajax/tipo.php?op=eliminar_tipo", { idtipo_trabajador: idtipo_trabajador }, function (e) {

    Swal.fire("Eliminado!", "Tu registro ha sido Eliminado.", "success");

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

