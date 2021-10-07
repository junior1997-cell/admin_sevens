var tabla;

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
  $("#trabajador").val("null").trigger("change");
  $("#cargo").val("Administrador").trigger("change");

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
  //Mostramos los trabajadores
  $.post("../ajax/usuario.php?op=select2Trabajador&id=", function (r) {   $("#trabajador").html(r);  });

  $("#idusuario").val("");
  $("#trabajador_c").html("Trabajador");
  $("#trabajador").val("null").trigger("change"); 
  $("#trabajador_old").val(""); 
  $("#cargo").val("Administrador").trigger("change"); 
  $("#login").val("");
  $("#password").val("");
  $("#password-old").val("");   
  
  //Mostramos los permisos
  $.post("../ajax/usuario.php?op=permisos&id=", function (r) { $("#permisos").html(r); });
}

//Función Listar
function listar() {

  tabla=$('#tabla-usuarios').dataTable({
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

// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_documento").val();

  let dni_ruc = $("#num_documento").val(); 
   
  if (tipo_doc == "DNI") {

    if (dni_ruc.length == "8") {

      $.post("../ajax/persona.php?op=reniec", { dni: dni_ruc }, function (data, status) {

        data = JSON.parse(data);

        console.log(data);

        if (data.success == false) {

          $("#search").show();

          $("#charge").hide();

          toastr.error("Es probable que el sistema de busqueda esta en mantenimiento o los datos no existe en la RENIEC!!!");

        } else {

          $("#search").show();

          $("#charge").hide();

          $("#nombre").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

          toastr.success("Cliente encontrado!!!!");
        }
      });
    } else {

      $("#search").show();

      $("#charge").hide();

      toastr.info("Asegurese de que el DNI tenga 8 dígitos!!!");
    }
  } else {
    if (tipo_doc == "RUC") {

      if (dni_ruc.length == "11") {
        $.post("../ajax/persona.php?op=sunat", { ruc: dni_ruc }, function (data, status) {

          data = JSON.parse(data);

          console.log(data);
          if (data.success == false) {

            $("#search").show();

            $("#charge").hide();

            toastr.error("Datos no encontrados en la SUNAT!!!");
            
          } else {

            if (data.estado == "ACTIVO") {

              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);
              
              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);
              // $("#direccion").val(data.direccion);
              toastr.success("Cliente encontrado");
            } else {

              toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);
              
              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);

              // $("#direccion").val(data.direccion);
            }
          }
        });
      } else {
        $("#search").show();

        $("#charge").hide();

        toastr.info("Asegurese de que el RUC tenga 11 dígitos!!!");
      }
    } else {
      if (tipo_doc == "CEDULA" || tipo_doc == "OTRO") {

        $("#search").show();

        $("#charge").hide();

        toastr.info("No necesita hacer consulta");

      } else {

        $("#tipo_doc").addClass("is-invalid");

        $("#search").show();

        $("#charge").hide();

        toastr.error("Selecione un tipo de documento");
      }
    }
  }
}
