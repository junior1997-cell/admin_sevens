var tabla;

window.top == window && window.console && (setTimeout(console.log.bind(console, "%c%s", "color: red; background: yellow; font-size: 24px;", "ADVERTENCIA")),
setTimeout(console.log.bind(console, "%c%s", "font-size: 18px;", "Si utilizas esta consola, otras personas podr\u00edan hacerse pasar por ti y robarte datos mediante un ataque Self-XSS.\nNo escribas ni pegues ning\u00fan c\u00f3digo que no entiendas.")))
            
//Función que se ejecuta al inicio
function init() {
  
  listar();

  //Activamos el "aside"
  $("#bloc_PagosTrabajador").addClass("menu-open");

  $("#mPagosTrabajador").addClass("active");

  $("#lPagosObrero").addClass("active");

  // ejecutamos el "FORM"
  $("#guardar_registro").on("click", function (e) { $("#submit-form-usuario").submit(); });

  //Initialize Select2 Elements
  $("#trabajadores").select2({
    theme: "bootstrap4",
    placeholder: "Selecione trabajador",
    allowClear: true,
  });

  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

//Función limpiar
function limpiar() {

  $("#idusuario").val("");
  $("#trabajador_c").html("Trabajador");
  $("#trabajador").val("null").trigger("change"); 
  $("#trabajador_old").val(""); 
  $("#cargo").val("Administrador").trigger("change"); 
  $("#login").val("");
  $("#password").val("");
  $("#password-old").val("");  
}

function show_hide_tablas(flag) {
  if (flag == 1) {
    $("#button-regresar").hide();
    $("#button-agregar-pago").hide();
    $("#card-tabla-pago").show();
    $("#card-tabla-detalle-pago").hide();
  } else {
    if (flag == 2) {
      $("#button-regresar").show();
      $("#button-agregar-pago").show();
      $("#card-tabla-pago").hide();
      $("#card-tabla-detalle-pago").show();
    }
  }
}

function listar() {
  
}

// ver detalle de todos los pagos de un trabajador
function ver_detalle_pagos(params) {

  show_hide_tablas(2);

  tabla=$('#tabla-detalle-pago').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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

function ver_detalle_quincena_semana(params) {
  $("#modal-ver-quincena-semana").modal("show")
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

				toastr.success('Usuario registrado correctamente')				 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-usuario").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idusuario) {
  limpiar();
  $("#trabajador").val("").trigger("change"); 
  $("#trabajador_c").html("(Nuevo) Trabajador");
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-usuario").modal("show")

  $.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();
    
    $("#trabajador_old").val(data.idtrabajador); 
    $("#cargo").val(data.cargo).trigger("change"); 
    $("#login").val(data.login);
    $("#password-old").val(data.password);
    $("#idusuario").val(data.idusuario);

    if (data.imagen != "") {

			$("#foto2_i").attr("src", "../dist/img/usuarios/" + data.imagen);

			$("#foto2_actual").val(data.imagen);
		}
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

//Función para activar registros
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

// Agregar pago: semana, quincena
function modal_agregar_pago() {
  $("#modal-agregar-pago").modal('show');
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
      password: {minlength: 4, maxlength: 20 },
      // terms: { required: true },
    },
    messages: {
      login: {
        required: "Por favor ingrese un login.",
        minlength: "El login debe tener MÍNIMO 4 caracteres.",
        maxlength: "El login debe tener como MÁXIMO 20 caracteres.",
      },
      password: {
        minlength: "La contraseña debe tener MÍNIMO 4 caracteres.",
        maxlength: "La contraseña debe tener como MÁXIMO 20 caracteres.",
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

      if ($("#trabajador").select2("val") == null && $("#trabajador_old").val() == "") {
         
        $("#trabajador_validar").show(); //console.log($("#trabajador").select2("val") + ", "+ $("#trabajador_old").val());

      } else {

        $("#trabajador_validar").hide();
      }       
    },
  });
});

