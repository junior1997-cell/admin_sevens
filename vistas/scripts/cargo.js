var tabla_cargos;

//Función que se ejecuta al inicio
function init() {
  listar_cargo();
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  // $("#lBancoColor").addClass("active");
  //Mostramos idtipo_trabjador
  $.post("../ajax/tipo.php?op=selecttipo_tipo", function (r) { $("#idtipo_trabjador_c").html(r); });

  //Guardar  
  $("#guardar_registro_cargo").on("click", function (e) {$("#submit-form-cargo").submit(); });

  //Initialize Select2 Elements
  $("#idtipo_trabjador_c").select2({
    theme: "bootstrap4",
    placeholder: "Selecione un tipo",
    allowClear: true,
  });

  // $("#idtipo_trabjador").val("").trigger("change");
    $("#idtipo_trabjador_c").val("null").trigger("change");
  
  // Formato para telefono
  $("[data-mask]").inputmask();
}
//Función limpiar
function limpiar_cargo() {
  $("#idcargo_trabajador").val("");
  $("#nombre_cargo").val(""); 
  $("#idtipo_trabjador_c").val("null").trigger("change");

  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

//Función listar_cargo
function listar_cargo() {

  tabla_cargos=$('#tabla-cargo').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5','pdf'],
    "ajax":{
        url: '../ajax/cargo.php?op=listar_cargo',
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

function guardaryeditar_cargo(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-cargo")[0]);
 
  $.ajax({
    url: "../ajax/cargo.php?op=guardaryeditar_cargo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla_cargos.ajax.reload();
         
				limpiar_cargo();

        $("#modal-agregar-cargo").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar_cargo(idcargo_trabajador) {
  limpiar_cargo();
  console.log(idcargo_trabajador);

  $("#modal-agregar-cargo").modal("show")
  $("#idtipo_trabjador_c").val("null").trigger("change");

  $.post("../ajax/cargo.php?op=mostrar", {idcargo_trabajador: idcargo_trabajador}, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idcargo_trabajador").val(data.idcargo_trabajador);
    $("#nombre_cargo").val(data.nombre); 
    $("#idtipo_trabjador_c").val(data.idtipo_trabjador).trigger("change");

  });

}

//Función para desactivar registros
function desactivar_cargo(idcargo_trabajador) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar el registro?",
    text: "Cargo",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/cargo.php?op=desactivar", { idcargo_trabajador: idcargo_trabajador }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla_cargos.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar_cargo(idcargo_trabajador) {
  Swal.fire({
    title: "¿Está Seguro de  Activar el registro?",
    text: "Cargo",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/cargo.php?op=activar", { idcargo_trabajador: idcargo_trabajador }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla_cargos.ajax.reload();
      });
      
    }
  });      
}

//Función para desactivar registros
function eliminar_cargo(idcargo_trabajador) {
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
    $.post("../ajax/cargo.php?op=desactivar", { idcargo_trabajador: idcargo_trabajador }, function (e) {

      Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");

      tabla_cargos.ajax.reload();
    });  

  }else if (result.isDenied) {
   //op=eliminar
    $.post("../ajax/cargo.php?op=eliminar", { idcargo_trabajador: idcargo_trabajador }, function (e) {

      Swal.fire("Eliminado!", "Tu registro ha sido Eliminado.", "success");

      tabla_cargos.ajax.reload();
    });  

  }

});   
}

init();

$(function () {

  
  $.validator.setDefaults({

    submitHandler: function (e) {
    //  console.log('kkkkkk');
    guardaryeditar_cargo(e);
      
    },
  });

  $("#form-cargo").validate({
    rules: {
      idtipo_trabjador_c: { required: true },      // terms: { required: true },
      nombre_cargo: { required: true }
    },
    messages: {

      idtipo_trabjador_c: {
        required: "Campo requerido", 
      },
      nombre_cargo: {
        required: "Campo requerido", 
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

