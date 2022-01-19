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
}

//Función listar_cargo
function listar_cargo() {

  tabla_cargos=$('#tabla-cargo').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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
         
				limpiar();

        $("#modal-agregar-cargo").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar_cargo(idcargo_trabajador) {
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
      nombre_color: { required: true },      // terms: { required: true },
      idtipo_trabjador: { required: true }
    },
    messages: {

      nombre_color: {
        required: "Por favor ingrese nombre", 
      },
      idtipo_trabjador: {
        required: "Por favor selecione un tipo trabajador", 
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

