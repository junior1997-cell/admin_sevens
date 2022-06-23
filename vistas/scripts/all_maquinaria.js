var tabla; var tabla2;

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");

  $("#mRecurso").addClass("active");

  $("#lAllMaquinas").addClass("active");

  listar();
  listar2();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#proveedor', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-maquinaria").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════ 
  $("#proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });
  $("#tipo").select2({ theme: "bootstrap4", placeholder: "Selecione tipo", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();

}

//Función limpiar
function limpiar_form_maquinaria() {

  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');

  $("#idmaquinaria").val("");
  $("#nombre_maquina").val(""); 
  $("#codigo_m").val(""); 
  $("#proveedor").val("null").trigger("change");
  $("#tipo").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
  
}

//Función Listar
function listar() {

  tabla=$('#tabla-maquinas').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,6,4], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,6,4], } }, { extend: 'pdfHtml5', footer: false,  exportOptions: { columns: [0,2,3,6,4], } }, {extend: "colvis"} ,
    ],
    ajax:{
      url: '../ajax/all_maquinaria.php?op=listar_maquinas',
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
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [6], visible: false, searchable: false, },            
    ],
  }).DataTable();
}

//Función Listar22222
function listar2() {

  tabla2=$('#tabla-equipos').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,6,4], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,6,4], } }, { extend: 'pdfHtml5', footer: false,  exportOptions: { columns: [0,2,3,6,4], } }, {extend: "colvis"} ,
    ],
    ajax:{
      url: '../ajax/all_maquinaria.php?op=listar_equipos',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: #1
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [6], visible: false, searchable: false, },            
    ],
  }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-maquinaria")[0]);

  $.ajax({
    url: "../ajax/all_maquinaria.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);  console.log(e);   
      if (e.status) {
				Swal.fire("Correcto!", "Guardado correctamente", "success");
	      tabla.ajax.reload(null, false);
	      tabla2.ajax.reload(null, false);
         
				limpiar_form_maquinaria();

        $("#modal-agregar-maquinaria").modal("hide");

        $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
			}else{
				ver_errores(e);
			}
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idmaquinaria) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_form_maquinaria();   

  $("#modal-agregar-maquinaria").modal("show")

  $.post("../ajax/all_maquinaria.php?op=mostrar", { idmaquinaria: idmaquinaria }, function (e, status) {

    e = JSON.parse(e);  console.log(e);   

    if (e.status) {
      $("#proveedor").val(e.data.idproveedor).trigger("change"); 
      $("#tipo").val(e.data.tipo).trigger("change"); 
      $("#idmaquinaria").val(e.data.idmaquinaria);
      $("#nombre_maquina").val(e.data.nombre); 
      $("#codigo_m").val(e.data.modelo);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); });
}

//Función para desactivar registros
function desactivar(idmaquinaria) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar Máquina o Equipo?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/all_maquinaria.php?op=desactivar", { idmaquinaria: idmaquinaria }, function (e) {

        Swal.fire("Desactivado!", "Tu máquinas o equipo ha sido desactivada.", "success");
    
        tabla.ajax.reload(null, false);
	      tabla2.ajax.reload(null, false);
      }).fail( function(e) { ver_errores(e); } );    
    }
  });   
}

//Función para desactivar registros
function eliminar(idmaquinaria, nombre) {
  
  crud_eliminar_papelera(
    "../ajax/all_maquinaria.php?op=desactivar",
    "../ajax/all_maquinaria.php?op=eliminar", 
    idmaquinaria, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false); tabla2.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );
}

init();

$(function () {

  $("#proveedor").on('change', function() { $(this).trigger('blur'); });
  $("#tipo").on('change', function() { $(this).trigger('blur'); });

  $("#form-maquinaria").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      nombre_maquina: { required: true },
      proveedor:      { required: true },
      tipo:           { required: true }
    },
    messages: {
      nombre_maquina: { required: "Campo requerido.", },
      proveedor:      { required: "Campo requerido.", },
      tipo:           { required: "Campo requerido.", },
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
      guardaryeditar(e);
    }
  });

  $("#proveedor").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});
