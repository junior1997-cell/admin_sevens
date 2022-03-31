var tabla; var estado_usuario_requerido = true;

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Accesos").addClass("menu-open bg-color-191f24");

  $("#mAccesos").addClass("active");

  $("#lUsuario").addClass("active");

  tbla_principal();

  //Mostramos los permisos
  $.post("../ajax/uusuario.php?op=permisos&id=", function (r) { 
    r = JSON.parse(r); $("#permisos").html(r.data); 
  }).fail(
    function(e) { 
      console.log(e);
      if (e.status == 404) {
        Swal.fire(`Error 404 😅!`, `<h5>Archivo no encontrado</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
      } else if(e.status == 500) {
        Swal.fire(`Error 500 😅!`, `<h5>Error Interno del Servidor</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
      }       
    }
  );

  //Mostramos los trabajadores
  $.post("../ajax/usuario.php?op=select2Trabajador&id=", function (r) { r = JSON.parse(r); $("#trabajador").html(r.data); });

  $("#guardar_registro").on("click", function (e) { $("#submit-form-usuario").submit(); });

  //Initialize Select2 Elements
  $("#trabajador").select2({ theme: "bootstrap4",  placeholder: "Selecione trabajador", allowClear: true, });  

  //Initialize Select2 Elements
  $("#cargo").select2({ theme: "bootstrap4",  placeholder: "Selecione cargo", allowClear: true, });
  
  // Formato para telefono
  $("[data-mask]").inputmask();   
}
 

//Función limpiar
function limpiar() {

  // Agregamos la validacion
  $("#trabajador").rules('add', { required: true, messages: {  required: "Campo requerido" } });

  $("#permisos").html('<i class="fas fa-spinner fa-pulse fa-2x"></i>');
  estado_usuario_requerido = true;

  $.post("../ajax/usuario.php?op=select2Trabajador&id=", function (r) { r = JSON.parse(r); $("#trabajador").html(r.data); $("#trabajador").val("").trigger("change"); });

  $("#idusuario").val("");
  $("#trabajador_c").html("Trabajador");
   
  $("#trabajador_old").val(""); 
  $("#cargo").val("").trigger("change"); 
  $("#login").val("");
  $("#password").val("");
  $("#password-old").val(""); 
  
  $(".modal-title").html("Agregar usuario");  

  //Mostramos los permisos
  $.post("../ajax/usuario.php?op=permisos&id=", function (r) { r = JSON.parse(r); $("#permisos").html(r.data); });

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
      url: '../ajax/uusuario.php?op=tbla_principal',
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

  $.ajax({
    url: "../ajax/usuario.php?op=guardar_y_editar_usuario",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {
				 
        Swal.fire("Correcto!", "Usuario guardado correctamente", "success");

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-usuario").modal("hide");

			}else{

				Swal.fire("Error!", datos, "error");
			}
    },
  });
}

function mostrar(idusuario) {

  limpiar();  

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

  $.post("../ajax/usuario.php?op=permisos&id=" + idusuario, function (r) {
    r = JSON.parse(r);
    $("#permisos").html(r.data);
  }).fail( function(e) { console.log(e); ver_errores(e); } );
}

//Función para desactivar registros
function desactivar(idusuario) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  el Usuario?",
    text: "Este usuario no podrá ingresar al sistema!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/usuario.php?op=desactivar", { idusuario: idusuario }, function (e) {
        if (e == 'ok') {

          Swal.fire("Desactivado!", "Tu usuario ha sido Desactivado.", "success");		 
  
          tabla.ajax.reload();
          
        }else{
  
          Swal.fire("Error!", e, "error");
        }
      }).fail( function(e) { console.log(e); ver_errores(e); } );      
    }
  });   
}

//Función para activar registros ::: sin usar::::
crud_activar(
  "../ajax/usuario.php?op=activar", 
  idusuario, 
  "¿Está Seguro de  Activar  el Usuario?", 
  "Este usuario tendra acceso al sistema", 
  callback_true, 
  callback_false
)

//Función para desactivar registros
function eliminar(idusuario) {

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
      $.post("../ajax/usuario.php?op=desactivar", { idusuario: idusuario }, function (e) {
        if (e == 'ok') {

          Swal.fire("Desactivado!", "Tu usuario ha sido Desactivado.", "success");		 

          tabla.ajax.reload();
          
        }else{

          Swal.fire("Error!", e, "error");
        }
      }).fail( function(e) { console.log(e); ver_errores(e); } );

    }else if (result.isDenied) {
      //op=eliminar
      $.post("../ajax/usuario.php?op=eliminar", { idusuario: idusuario }, function (e) {
        if (e == 'ok') {

          Swal.fire("Eliminado!", "Tu usuario ha sido Eliminado.", "success");		 

          tabla.ajax.reload();
          
        }else{

          Swal.fire("Error!", e, "error");
        }
        
      }).fail( function(e) { console.log(e); ver_errores(e); } );
    }
  });  
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

      $(element).addClass("is-invalid").removeClass("is-valid"); //console.log(estado_usuario_requerido);
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

function marcar_todos_permiso() {
   
  if ($(`#marcar_todo`).is(':checked')) {
    $('.permiso').each(function(){ this.checked = true; });
    $('.marcar_todo').html('Desmarcar Todo');
  } else {
    $('.permiso').each(function(){ this.checked = false; });
    $('.marcar_todo').html('Marcar Todo');
  }  
}