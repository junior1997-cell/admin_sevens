var tabla_marca;

//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  tabla_principal_marca();

  $("#guardar_registro_marca").on("click", function (e) { $("#submit-form-marca").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar() {
  $("#guardar_registro_marca").html('Guardar Cambios').removeClass('disabled');
  //Mostramos los Materiales
  $("#idmarca").val("");
  $("#nombre_marca").val("");
  $("#descripcion_marca").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function tabla_principal_marca() {

  tabla_marca = $('#tabla-marca').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 6, 10, 25, 75, 100, 200,], ["Todos", 6, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3], } }, { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3], } } ,
    ],
    ajax:{
      url: '../ajax/marca.php?op=tabla_principal_marca',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 6,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar
function guardar_y_editar_marca(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-marca")[0]);
 
  $.ajax({
    url: "../ajax/marca.php?op=guardar_y_editar_marca",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);  console.log(e);  
      if (e.status == true) {
        Swal.fire("Correcto!", "Color registrado correctamente.", "success");

	      tabla_marca.ajax.reload(null, false);
         
				limpiar();

        $("#modal-agregar-marca").modal("hide");
        $("#guardar_registro_marca").html('Guardar Cambios').removeClass('disabled');
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
          $("#barra_progress_marca").css({"width": percentComplete+'%'});
          $("#barra_progress_marca").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_marca").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_marca").css({ width: "0%",  });
      $("#barra_progress_marca").text("0%");
    },
    complete: function () {
      $("#barra_progress_marca").css({ width: "0%", });
      $("#barra_progress_marca").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_marca(idmarca) {
  $(".tooltip").remove();
  $("#cargando-13-fomulario").hide();
  $("#cargando-14-fomulario").show();
  
  limpiar();

  $("#modal-agregar-marca").modal("show")

  $.post("../ajax/marca.php?op=mostrar_marca", { idmarca: idmarca }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status) {
      $("#idmarca").val(e.data.idmarca);
      $("#nombre_marca").val(e.data.nombre_marca);        
      $("#descripcion_marca").val(e.data.descripcion).trigger('change');     

      $("#cargando-13-fomulario").show();
      $("#cargando-14-fomulario").hide();
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_marca(idmarca, nombre) {

  crud_eliminar_papelera(
    "../ajax/marca.php?op=desactivar",
    "../ajax/marca.php?op=eliminar", 
    idmarca, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_marca.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

}

init();

$(function () {

  $("#form-marca").validate({
    rules: {
      nombre_marca: { required: true }      // terms: { required: true },
    },
    messages: {
      nombre_marca: {  required: "Campo requerido.", },
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
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_y_editar_marca(e);      
    },

  });
});

