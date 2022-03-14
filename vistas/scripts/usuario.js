var tabla; var estado_usuario_requerido = true;

//Función que se ejecuta al inicio
function init() {
  
  listar();

  //Mostramos los permisos
  $.post("../ajax/usuario.php?op=permisos&id=", function (r) {  $("#permisos").html(r); });

  //Mostramos los trabajadores
  $.post("../ajax/usuario.php?op=select2Trabajador&id=", function (r) { $("#trabajador").html(r); });

  $("#bloc_Accesos").addClass("menu-open");

  $("#mAccesos").addClass("active");

  $("#lUsuario").addClass("active");

  $("#guardar_registro").on("click", function (e) { $("#submit-form-usuario").submit(); });

  //Initialize Select2 Elements
  $("#trabajador").select2({
    theme: "bootstrap4",
    placeholder: "Selecione trabajador",
    allowClear: true,
  });
  

  //Initialize Select2 Elements
  $("#cargo").select2({
    theme: "bootstrap4",
    placeholder: "Selecione cargo",
    allowClear: true,
  });
  
  // $("#trabajador").val("").trigger("change");
  // $("#cargo").val("").trigger("change");

  // Formato para telefono
  $("[data-mask]").inputmask();   
}
 

function seleccion() {

  if ($("#trabajador").select2("val") == null && $("#trabajador_old").val() == null) {

    $("#trabajador_validar").show(); //console.log($("#trabajador").select2("val") + ", "+ $("#trabajador_old").val());

  } else {

    $("#trabajador_validar").hide();
  }
}

//Función limpiar
function limpiar() {
  estado_usuario_requerido = true;

  $.post("../ajax/usuario.php?op=select2Trabajador&id=", function (r) { $("#trabajador").html(r); $("#trabajador").val("").trigger("change"); });

  $("#idusuario").val("");
  $("#trabajador_c").html("Trabajador");
   
  $("#trabajador_old").val(""); 
  $("#cargo").val("").trigger("change"); 
  $("#login").val("");
  $("#password").val("");
  $("#password-old").val(""); 
  
  $(".modal-title").html("Agregar usuario");  

  //Mostramos los permisos
  $.post("../ajax/usuario.php?op=permisos&id=", function (r) { $("#permisos").html(r); });
}

//Función Listar
function listar() {

  tabla=$('#tabla-usuarios').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/usuario.php?op=listar',
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
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
    "iDisplayLength": 10,//Paginación
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-usuario")[0]);

  $.ajax({
    url: "../ajax/usuario.php?op=guardaryeditar",
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
  estado_usuario_requerido = false;
  $(".modal-title").html("Editar usuario");
  $("#trabajador").val("").trigger("change"); 
  $("#trabajador_c").html(`Trabajador <b class="text-danger">(Selecione nuevo) </b>`);
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-usuario").modal("show")

  $.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario }, function (data, status) {

    data = JSON.parse(data);  //console.log(data); 

    $(".modal-title").html(`Editar usuario: <b> ${data.nombres} </b> `);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();
    
    $("#trabajador_old").val(data.idtrabajador); 
    $("#cargo").val(data.cargo).trigger("change"); 
    $("#login").val(data.login);
    $("#password-old").val(data.password);
    $("#idusuario").val(data.idusuario);
    
  });

  $.post("../ajax/usuario.php?op=permisos&id=" + idusuario, function (r) {

    $("#permisos").html(r);
  });
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
      });      
    }
  });   
}

//Función para activar registros ::: sin usar::::
function activar(idusuario) {

  Swal.fire({

    title: "¿Está Seguro de  Activar  el Usuario?",
    text: "Este usuario tendra acceso al sistema",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",

  }).then((result) => {

    if (result.isConfirmed) {

      $.post("../ajax/usuario.php?op=activar", { idusuario: idusuario }, function (e) {

        if (e == 'ok') {

          Swal.fire("Activado!", "Tu usuario ha sido activado.", "success");		 
  
          tabla.ajax.reload();
          
        }else{
  
          Swal.fire("Error!", e, "error");
        }
      });      
    }
  });      
}

//Función para desactivar registros
function eliminar(idusuario) {
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
    $.post("../ajax/usuario.php?op=desactivar", { idusuario: idusuario }, function (e) {
      if (e == 'ok') {

        Swal.fire("Desactivado!", "Tu usuario ha sido Desactivado.", "success");		 

        tabla.ajax.reload();
        
      }else{

        Swal.fire("Error!", e, "error");
      }
    });

  }else if (result.isDenied) {
   //op=eliminar
   $.post("../ajax/usuario.php?op=eliminar", { idusuario: idusuario }, function (e) {
    if (e == 'ok') {

      Swal.fire("Eliminado!", "Tu usuario ha sido Eliminado.", "success");		 

      tabla.ajax.reload();
      
    }else{

      Swal.fire("Error!", e, "error");
    }
    
  }); 


  }

});  
}

init();

$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {

      if ($("#trabajador").select2("val") == null && $("#trabajador_old").val() == "") {
        
        $("#trabajador_validar").show(); //console.log($("#trabajador").select2("val") + ", "+ $("#trabajador_old").val());

      } else {

        $("#trabajador_validar").hide();

        guardaryeditar(e);
      }
    },
  });

  $("#form-usuario").validate({
    rules: {
      login: { required: true, minlength: 3, maxlength: 20 },
      password: { minlength: 4, maxlength: 20 },
      // estado_usuario_requerido? trabajador: { required: estado_usuario_requerido},:
      cargo: { required: true }
      // terms: { required: true },
    },
    messages: {
      login: {
        required: "Este campo es requerido.",
        minlength: "El login debe tener MÍNIMO 4 caracteres.",
        maxlength: "El login debe tener como MÁXIMO 20 caracteres.",
      },
      password: {
        minlength: "La contraseña debe tener MÍNIMO 4 caracteres.",
        maxlength: "La contraseña debe tener como MÁXIMO 20 caracteres.",
      },
      trabajador: {
        required: "Este campo es requerido."
      },
      cargo: {
        required: "Este campo es requerido."
      },
    },
    
    errorElement: "span",

    errorPlacement: function (error, element) {

      error.addClass("invalid-feedback");

      element.closest(".form-group").append(error); 
    },

    highlight: function (element, errorClass, validClass) {

      $(element).addClass("is-invalid"); //console.log(estado_usuario_requerido);
    },

    unhighlight: function (element, errorClass, validClass) {

      $(element).removeClass("is-invalid").addClass("is-valid");

      if ($("#trabajador").select2("val") == null && $("#trabajador_old").val() == "") {
         
        $("#trabajador_validar").show(); //console.log($("#trabajador").select2("val") + ", "+ $("#trabajador_old").val());

      } else {

        $("#trabajador_validar").hide();
      }       
    },
  });
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