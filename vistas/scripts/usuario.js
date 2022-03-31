var tabla;  

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Accesos").addClass("menu-open bg-color-191f24");

  $("#mAccesos").addClass("active");

  $("#lUsuario").addClass("active");

  tbla_principal();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  

  lista_select2("../ajax/usuario.php?op=select2Trabajador", '#trabajador', null);
  
  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════

  $("#guardar_registro").on("click", function (e) { $("#submit-form-usuario").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#trabajador").select2({ theme: "bootstrap4",  placeholder: "Selecione trabajador", allowClear: true, });  

  $("#cargo").select2({ theme: "bootstrap4",  placeholder: "Selecione cargo", allowClear: true, });
  
  // Formato para telefono
  $("[data-mask]").inputmask();   
}
 

//Función limpiar
function limpiar_form_usuario() {

  $("#permisos").html('<i class="fas fa-spinner fa-pulse fa-2x"></i>');

  // Agregamos la validacion
  $("#trabajador").rules('add', { required: true, messages: {  required: "Campo requerido" } });  

  //Select2 trabajador
  lista_select2("../ajax/usuario.php?op=select2Trabajador", '#trabajador', null);

  //Permiso
  $.post(`../ajax/usuario.php?op=permisos&id=${idusuario}`, function (r) {

    r = JSON.parse(r); //console.log(r);

    if (r.status) { $("#permisos").html(r.data); } else { ver_errores(e); }
    
  }).fail( function(e) { console.log(e); ver_errores(e); } );

  $("#idusuario").val("");
  $("#trabajador_c").html("Trabajador");   
  $("#trabajador_old").val(""); 
  $("#cargo").val("").trigger("change"); 
  $("#login").val("");
  $("#password").val("");
  $("#password-old").val(""); 
  
  $(".modal-title").html("Agregar usuario");    

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function tbla_principal() {

  tabla = $('#tabla-usuarios').dataTable({
    "responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'pdf', "colvis"],
    "ajax":{
      url: '../ajax/usuario.php?op=tbla_principal',
      type : "get",
      dataType : "json",						
      error: function(e){        
        console.log(e.responseText); ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {    

      // columna: 0
      if (data[0] != '') {
        $("td", row).eq(0).addClass("text-center");   
          
      }
      // columna: 1
      if (data[1] != '') {
        $("td", row).eq(1).addClass("text-center");   
          
      }
    },
    "language": {
      "lengthMenu": "Mostrar: _MENU_ registros",
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
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar
function guardar_y_editar_usuario(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-usuario")[0]);

  /*event, url, formData, nombre_modal, callback_limpiar, callback_true, select2_reload, name_progress, table_reload_1, 
  table_reload_2, table_reload_3, table_reload_4,table_reload_5*/
  
  crud_guardar_editar_modal_xhr(e, 
    "../ajax/usuario.php?op=guardar_y_editar_usuario", 
    formData, 
    '#modal-agregar-usuario', 
    function(){ limpiar_form_usuario(); },
    function(){ sw_success('Correcto!', "Usuario guardado correctamente." ) }, 
    false, 
    'usuario', 
    function(){ tabla.ajax.reload(); }, 
    false, 
    false, 
    false,
    false
  );
}

function mostrar(idusuario) {

  limpiar_form_usuario();  

  $(".modal-title").html("Editar usuario");
  $("#trabajador").val("").trigger("change"); 
  $("#trabajador_c").html(`Trabajador <b class="text-danger">(Selecione nuevo) </b>`);
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  // Removemos la validacion
  $("#trabajador").rules('remove', 'required');

  $("#modal-agregar-usuario").modal("show");  

  $.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 

    $(".modal-title").html(`Editar usuario: <i class="fas fa-users-cog text-primary"></i> <b>${data.data.nombres}</b> `);    
    
    $("#trabajador_old").val(data.data.idtrabajador); 
    $("#cargo").val(data.data.cargo).trigger("change"); 
    $("#login").val(data.data.login);
    $("#password-old").val(data.data.password);
    $("#idusuario").val(data.data.idusuario);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();    

  }).fail( function(e) { console.log(e); ver_errores(e); } );

  //Permiso
  $.post("../ajax/usuario.php?op=permisos&id=" + idusuario, function (r) {

    r = JSON.parse(r); console.log(r);

    if (r.status) { $("#permisos").html(r.data); } else { ver_errores(e); }
    
  }).fail( function(e) { console.log(e); ver_errores(e); } );
}

//Función para desactivar registros
function eliminar(idusuario) {
  crud_eliminar_papelera(
    "../ajax/usuario.php?op=desactivar",
    "../ajax/usuario.php?op=eliminar", 
    idusuario, 
    "!Elija una opción¡", 
    "En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!", 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload() },
    false, 
    false, 
    false,
    false
  );
}

init();

$(function () {

  $("#cargo").on('change', function() { $(this).trigger('blur'); });
  $("#trabajador").on('change', function() { $(this).trigger('blur'); });

  $("#form-usuario").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      login: { required: true, minlength: 3, maxlength: 20 },
      password: { minlength: 4, maxlength: 20 },
      cargo: { required: true }
    },
    messages: {
      login: { required: "Este campo es requerido.", minlength: "MÍNIMO 4 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      password: { minlength: "MÍNIMO 4 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      cargo: { required: "Campo requerido." },
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
      guardar_y_editar_usuario(e);
    },
  });

  $("#cargo").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#trabajador").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function marcar_todos_permiso() {
   
  if ($(`#marcar_todo`).is(':checked')) {
    $('.permiso').each(function(){ this.checked = true; });
    $('.marcar_todo').html('Desmarcar Todo');
  } else {
    $('.permiso').each(function(){ this.checked = false; });
    $('.marcar_todo').html('Marcar Todo');
  }  
}