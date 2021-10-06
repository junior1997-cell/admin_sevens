var tabla;

//Función que se ejecuta al inicio
function init() {
  
  listar();

  //Mostramos los permisos
  $.post("../ajax/usuario.php?op=permisos&id=", function (r) {

    $("#permisos").html(r);
  });

  $("#bloc_Accesos").addClass("menu-open");

  $("#mAccesos").addClass("active");

  $("#lUsuario").addClass("active");

  $("#guardar_registro").on("click", function (e) {

    $("#submit-form-usuario").submit();
  });

  //Initialize Select2 Elements
  $("#cargo").select2({
    theme: "bootstrap4",
    placeholder: "Selecione cargo",
    allowClear: true,
  });

  $("#cargo").val("").trigger("change");

  // Formato para telefono
  $("[data-mask]").inputmask();

  $("#foto2_i").click(function() {

		$('#foto2').trigger('click');
	});

  $("#foto2").change(function(e) {

		addImage(e,$("#foto2").attr("id"))
	});
}
/* PREVISUALIZAR LAS IMAGENES */
function addImage(e,id) {

	console.log(id);

	var file = e.target.files[0], imageType = /image.*/;
	
	if (e.target.files[0]) {

		var sizeByte = file.size;

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(imageType)){
			// return;
			toastr.error('Este tipo de ARCHIVO no esta permitido <br> elija formato: <b>foto-ejemplo.webp </b>');

			$("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");

		}else{

			if (sizekiloBytes <= 2048) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {
					var result = e.target.result;
					$("#"+id+"_i").attr("src", result);
					$("#"+id+"_nombre").html(''+
						'<div class="row">'+
                '<div class="col-md-12">'+
							  file.name +
                '</div>'+
                '<div class="col-md-12">'+
                '<button  class="btn btn-danger  btn-block" onclick="'+id+'_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>'+
                '</div>'+
              '</div>'+
						'');
					toastr.success('Imagen aceptada.')
				}

				reader.readAsDataURL(file);

			} else {

				toastr.warning('La imagen: '+file.name.toUpperCase()+' es muy pesada')

				$("#"+id+"_i").attr("src", "../dist/img/default/img_error.png");

				$("#"+id).val("");
			}
		}
	}else{

		toastr.error('Seleccione una Imagen');$("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");

		$("#"+id+"_nombre").html("");
	}	
	
}

function foto2_eliminar() {

	$("#foto2").val("");

	$("#foto2_i").attr("src", "../dist/img/default/img_defecto.png");

	$("#foto2_nombre").html("");
}

function seleccion() {

  if ($("#cargo").select2("val") == null) {

    $("#cargo_validar").show();

  } else {

    $("#cargo_validar").hide();
  }
}

//Función limpiar
function limpiar() {
  $("#idusuario").val("");
  $("#nombre").val("");
  $("#num_documento").val("");
  $("#direccion").val("");
  $("#telefono").val("");
  $("#email").val("");
  $("#cargo").val("").trigger("change"); 
  $("#login").val("");
  $("#password").val("");
  $("#password-old").val("");

  $("#foto2_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto2").val("");
	$("#foto2_actual").val("");  
  $("#foto2_nombre").html("");  
  
  //Mostramos los permisos
  $.post("../ajax/usuario.php?op=permisos&id=", function (r) {

    $("#permisos").html(r);
  });
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

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-usuario").modal("show")

  $.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#nombre").val(data.nombre);     
    $("#tipo_documento").val(data.tipo_documento).trigger("change");
    $("#num_documento").val(data.num_documento);
    $("#direccion").val(data.direccion);
    $("#telefono").val(data.telefono);
    $("#email").val(data.email);
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

        Swal.fire("Desactivado!", "Tu usuario ha sido desactivado.", "success");
    
        tabla.ajax.reload();
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

        Swal.fire("Activado!", "Tu usuario ha sido activado.", "success");

        tabla.ajax.reload();
      });
      
    }
  });      
}

init();

$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {

      if ($("#cargo").select2("val") == null) {

        $("#cargo_validar").show();

      } else {

        $("#cargo_validar").hide();

        guardaryeditar(e);
      }
    },
  });

  $("#form-usuario").validate({
    rules: {
      tipo_documento: { required: true },
      num_documento: { required: true, minlength: 6, maxlength: 20 },
      nombre: { required: true, minlength: 6, maxlength: 100 },
      email: { email: true, minlength: 10, maxlength: 50 },
      direccion: { minlength: 5, maxlength: 70 },
      telefono: { minlength: 8 },
      cargo2: { required: true, minlength: 3, maxlength: 20 },
      login: { required: true, minlength: 3, maxlength: 20 },
      password: {minlength: 4, maxlength: 20 },
      // terms: { required: true },
    },
    messages: {
      tipo_documento: {
        required: "Por favor selecione un tipo de documento", 
      },
      num_documento: {
        required: "Ingrese un numero de documento",
        minlength: "El numero documento debe tener MÍNIMO 6 caracteres.",
        maxlength: "El numero documento debe tener como MÁXIMO 20 caracteres.",
      },
      nombre: {
        required: "Por favor ingrese los nombres y apellidos",
        minlength: "El numero documento debe tener MÍNIMO 6 caracteres.",
        maxlength: "El numero documento debe tener como MÁXIMO 100 caracteres.",
      },
      email: {
        required: "Por favor ingrese un correo electronico.",
        email: "Por favor ingrese un coreo electronico válido.",
        minlength: "El correo electronico debe tener MÍNIMO 10 caracteres.",
        maxlength: "El correo electronico debe tener como MÁXIMO 50 caracteres.",
      },
      direccion: {
        minlength: "La dirección debe tener MÍNIMO 5 caracteres.",
        maxlength: "La dirección debe tener como MÁXIMO 70 caracteres.",
      },
      telefono: {
        minlength: "El teléfono debe tener MÍNIMO 8 caracteres.",
      },
      cargo2: {
        required: "Por favor seleccione un cargo.",
        minlength: "El cargo debe tener MÍNIMO 3 caracteres.",
        maxlength: "El cargo debe tener como MÁXIMO 20 caracteres.",
      },
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

      if ($("#cargo").select2("val") == null) {

        $("#cargo_validar").show();

      } else {

        $("#cargo_validar").hide();
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
