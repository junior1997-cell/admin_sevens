var tabla_ocupacion;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  listar_ocupacion();

  $("#guardar_registro_ocupacion").on("click", function (e) { $("#submit-form-ocupacion").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar_ocupacion() {
  $("#guardar_registro_ocupacion").html('Guardar Cambios').removeClass('disabled');
  //Mostramos los Materiales
  $("#idocupacion").val("");
  $("#nombre_ocupacion").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar_ocupacion() {

  tabla_ocupacion=$('#tabla-ocupacion').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2], } }, { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2], } } ,
    ],
    ajax:{
      url: '../ajax/ocupacion.php?op=listar_ocupacion',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText); ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: #
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
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
    success: function (e) {
      e = JSON.parse(e);  console.log(e);  
      if (e.status == true) {

				Swal.fire("Correcto!", "Ocupacion registrado correctamente.", "success");

	      tabla_ocupacion.ajax.reload(null, false);
         
				limpiar_ocupacion();

        $("#modal-agregar-ocupacion").modal("hide");
        $("#guardar_registro_ocupacion").html('Guardar Cambios').removeClass('disabled');

			}else{
				ver_errores(e);	
			}
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {

          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_ocupacion").css({"width": percentComplete+'%'});

          $("#barra_progress_ocupacion").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_ocupacion").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_ocupacion").css({ width: "0%",  });
      $("#barra_progress_ocupacion").text("0%");
    },
    complete: function () {
      $("#barra_progress_ocupacion").css({ width: "0%", });
      $("#barra_progress_ocupacion").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_ocupacion(idocupacion) {
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  limpiar_ocupacion(); //console.log(idocupacion);

  $("#modal-agregar-ocupacion").modal("show")

  $.post("../ajax/ocupacion.php?op=mostrar_ocupacion", { idocupacion: idocupacion }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status) {
      $("#idocupacion").val(e.data.idocupacion);
      $("#nombre_ocupacion").val(e.data.nombre_ocupacion);
  
      $("#cargando-5-fomulario").show();
      $("#cargando-6-fomulario").hide();
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_ocupacion(idocupacion, nombre) {

  crud_eliminar_papelera(
    "../ajax/ocupacion.php?op=desactivar_ocupacion",
    "../ajax/ocupacion.php?op=eliminar_ocupacion", 
    idocupacion, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  tabla_ocupacion.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );
  
}

init();

$(function () {

  $("#form-ocupacion").validate({
    rules: {
      nombre_ocupacion: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre_ocupacion: { required: "Campo requerido.", },
    },
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");   
    },
    submitHandler: function (e) {
      guardaryeditar_ocupacion(e);      
    },
  });
});

