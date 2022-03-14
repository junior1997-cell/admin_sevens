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
function limpiar_banco() {
  //Mostramos los Materiales
  $("#idbancos").val("");
  $("#nombre").val(""); 
  $("#alias").val("");
  $("#formato_cta").val("00000000"); 
  $("#formato_cci").val("00000000"); 
  $("#formato_detracciones").val("00000000");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

//Función Listar
function listar_bancos() {

  tabla_bancos=$('#tabla-bancos').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
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
  limpiar(); //console.log(idbancos);

  $("#modal-agregar-bancos").modal("show")

  $.post("../ajax/bancos.php?op=mostrar_bancos", { idbancos: idbancos }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idbancos").val(data.idbancos);
    $("#nombre").val(data.nombre); 
    $("#alias").val(data.alias);
    $("#formato_cta").val(data.formato_cta); 
    $("#formato_cci").val(data.formato_cci); 
    $("#formato_detracciones").val(data.formato_detracciones); 
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

//Función para desactivar registros
function eliminar_bancos(idbancos) {
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
    $.post("../ajax/bancos.php?op=desactivar_bancos", { idbancos: idbancos }, function (e) {

      Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");

      tabla_bancos.ajax.reload();
    }); 

  }else if (result.isDenied) {
   //op=eliminar
    $.post("../ajax/bancos.php?op=eliminar_bancos", { idbancos: idbancos }, function (e) {

      Swal.fire("Eliminado!", "Tu registro ha sido Eliminado.", "success");

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
      nombre: { required: true, minlength:2, maxlength:65},    
      alias: { minlength:2, maxlength:65 },    
      formato_cta: { required: true, minlength:8 },
      formato_cci: { required: true, minlength:8 },
      formato_detracciones: { required: true, minlength:8 },
    },
    messages: {
      nombre: {
        required: "Por favor ingrese nombre ", minlength:"Ingrese almenos 2 carecteres", maxlength: "Máximo 65 carecteres"
      },
      alias: {
        minlength:"Ingrese almenos 2 carecteres", maxlength: "Máximo 65 carecteres"
      },
      formato_cta: {
        required: "Campo requerido", minlength:"Ingrese almenos 8 dígitos"
      },
      formato_cci: {
        required: "Campo requerido", minlength:"Ingrese almenos 8 dígitos"
      },
      formato_detracciones: {
        required: "Campo requerido", minlength:"Ingrese almenos 8 dígitos"
      }
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

