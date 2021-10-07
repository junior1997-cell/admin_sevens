var tabla;

//Función que se ejecuta al inicio
function init() {
  
  listar();

  $("#bloc_Accesos").addClass("menu-open");

  $("#mAccesos").addClass("active");

  $("#ltrabajador").addClass("active");

  $("#guardar_registro").on("click", function (e) {

    $("#submit-form-trabajador").submit();
  });

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

function selecciontrab() {

  if ($("#tipo_trabajador").select2("val") == null) {

    $("#tipo_trab_validar").show();

  } else {

    $("#tipo_trab_validar").hide();
  }
}

function seleccioncargo() {

  if ($("#cargo").select2("val") == null) {

    $("#cargo_validar").show();

  } else {

    $("#cargo_validar").hide();
  }
}

/**
 tipo_documento
 nombre
 num_documento
 direccion
 telefono
 email
 nacimiento
 tipo_trabajador
 desempenio
 c_bancaria
 banco
 tutular_cuenta
 sueldo_mensual
 sueldo_diario
 sueldo_hora
 */


//Función limpiar
function limpiar() {
  $("#idtrabajador").val(""); 
  $("#tipo_documento").val(""); 
  $("#nombre").val(""); 
  $("#num_documento").val(""); 
  $("#direccion").val(""); 
  $("#telefono").val(""); 
  $("#email").val(""); 
  $("#nacimiento").val(""); 
  $("#tipo_trabajador").val(""); 
  $("#desempenio").val("");  
  $("#c_bancaria").val("");  
  $("#banco").val("");  
  $("#tutular_cuenta").val("");   
  $("#sueldo_diario").val("");   
  $("#sueldo_hora").val("");   

  $("#foto2_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto2").val("");
	$("#foto2_actual").val("");  
  $("#foto2_nombre").html("");  
  
}

//Función Listar
function listar() {

  tabla=$('#tabla-trabajadors').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/trabajador.php?op=listar',
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
  var formData = new FormData($("#form-trabajador")[0]);

  $.ajax({
    url: "../ajax/trabajador.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('trabajador registrado correctamente')				 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-trabajador").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idtrabajador) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-trabajador").modal("show")

  $.post("../ajax/trabajador.php?op=mostrar", { idtrabajador: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#nombre").val(data.nombre);     
    $("#tipo_documento").val(data.tipo_documento).trigger("change");
    $("#num_documento").val(data.num_documento);
    $("#direccion").val(data.direccion);
    $("#telefono").val(data.telefono);
    $("#email").val(data.email);
    $("#login").val(data.login);
    $("#password-old").val(data.password);
    $("#idtrabajador").val(data.idtrabajador);

    if (data.imagen != "") {

			$("#foto2_i").attr("src", "../dist/img/trabajadors/" + data.imagen);

			$("#foto2_actual").val(data.imagen);
		}
  });
}

//Función para desactivar registros
function desactivar(idtrabajador) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  el trabajador?",
    text: "Este trabajador no podrá ingresar al sistema!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/trabajador.php?op=desactivar", { idtrabajador: idtrabajador }, function (e) {

        Swal.fire("Desactivado!", "Tu trabajador ha sido desactivado.", "success");
    
        tabla.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar(idtrabajador) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  el trabajador?",
    text: "Este trabajador tendra acceso al sistema",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/trabajador.php?op=activar", { idtrabajador: idtrabajador }, function (e) {

        Swal.fire("Activado!", "Tu trabajador ha sido activado.", "success");

        tabla.ajax.reload();
      });
      
    }
  });      
}

init();

$(function () {

 /* $.validator.setDefaults({

   submitHandler: function (e) {

      if ($("#cargo").select2("val") == null) {

        $("#cargo_validar").show();

      } else {

        $("#cargo_validar").hide();

        guardaryeditar(e);
      }
    },
  });*/

  $("#form-trabajador").validate({
    rules: {
      tipo_documento: { required: true },
      num_documento: { required: true, minlength: 6, maxlength: 20 },
      nombre: { required: true, minlength: 6, maxlength: 100 },
      email: { email: true, minlength: 10, maxlength: 50 },
      direccion: { minlength: 5, maxlength: 70 },
      telefono: { minlength: 8 },
      tipo_trabajador: { required: true},
      cargo: { required: true},
      c_bancaria: { minlength: 14, maxlength: 14},
      sueldo_mensual: { required: true, minlength: 1},
      sueldo_diario: { required: true, minlength: 1},
      sueldo_hora: { required: true, minlength: 1}


      // terms: { required: true },
    },
    messages: {
      tipo_documento: {
        required: "Por favor selecione un tipo de documento", 
      },
      num_documento: {
        required: "Ingrese un número de documento",
        minlength: "El número documento debe tener MÍNIMO 6 caracteres.",
        maxlength: "El número documento debe tener como MÁXIMO 20 caracteres.",
      },
      nombre: {
        required: "Por favor ingrese los nombres y apellidos",
        minlength: "El número documento debe tener MÍNIMO 6 caracteres.",
        maxlength: "El número documento debe tener como MÁXIMO 100 caracteres.",
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
      tipo_trabajador: {
        required: "Por favor  seleccione un tipo trabajador.",
      },
      cargo: {
        required: "Por favor  un cargo.",
      },
      c_bancaria: {
        minlength: "El número documento debe tener 14 caracteres.",
      },
      sueldo_mensual: {
        required: "Por favor  ingrese sueldo por mes.",
      },
      sueldo_diario: {
        required: "Por favor  ingrese sueldo por día.",
      },
      sueldo_hora: {
        required: "Por favor ingrese sueldo por hora.",
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

   /* unhighlight: function (element, errorClass, validClass) {

      $(element).removeClass("is-invalid").addClass("is-valid");

      if ($("#cargo").select2("val") == null) {

        $("#cargo_validar").show();

      } else {

        $("#cargo_validar").hide();
      }       
    },*/



  });
});


/*Validación Fecha de Nacimiento Mayoria de edad del usuario*/
function edades() {
  var fechaUsuario = $("#nacimiento").val();
  if (fechaUsuario) {         
  
      //El siguiente fragmento de codigo lo uso para igualar la fecha de nacimiento con la fecha de hoy del usuario
      let d = new Date(),
      month = '' + (d.getMonth() + 1),
      day = '' + d.getDate(),
      year = d.getFullYear();
      if (month.length < 2) 
          month = '0' + month;
      if (day.length < 2) 
          day = '0' + day;
      d=[year, month, day].join('-')
      /*------------*/
      var hoy = new Date(d);//fecha del sistema con el mismo formato que "fechaUsuario"
      var cumpleanos = new Date(fechaUsuario);
      //alert(cumpleanos+" "+hoy);
      //Calculamos años
      var edad = hoy.getFullYear() - cumpleanos.getFullYear();
      var m = hoy.getMonth() - cumpleanos.getMonth();
      if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
          edad--;
      }
      // calculamos los meses
      var meses=0;
      if(hoy.getMonth()>cumpleanos.getMonth()){
          meses=hoy.getMonth()-cumpleanos.getMonth();
      }else if(hoy.getMonth()<cumpleanos.getMonth()){
          meses=12-(cumpleanos.getMonth()-hoy.getMonth());
      }else if(hoy.getMonth()==cumpleanos.getMonth() && hoy.getDate()>cumpleanos.getDate() ){
          if(hoy.getMonth()-cumpleanos.getMonth()==0){
              meses=0;
          }else{
              meses=11;
          }            
      }
      // Obtener días: día actual - día de cumpleaños
      let dias  = hoy.getDate() - cumpleanos.getDate();
      if(dias < 0) {
          // Si días es negativo, día actual es mayor al de cumpleaños,
          // hay que restar 1 mes, si resulta menor que cero, poner en 11
          meses = (meses - 1 < 0) ? 11 : meses - 1;
          // Y obtener días faltantes
          dias = 30 + dias;
      }
      // console.log(`Tu edad es de ${edad} años, ${meses} meses, ${dias} días`);
      $("#edad").val(edad);
      $("#p_edad").html(`${edad} años`);
      // calcular mayor de 18 años
      if(edad>=18){
          console.log("Eres un adulto");
      }else{
          // Calcular faltante con base en edad actual
          // 18 menos años actuales
          let edadF = 18 - edad;
          // El mes solo puede ser 0 a 11, se debe restar (mes actual + 1)
          let mesesF = 12 - (meses + 1);
          // Si el mes es mayor que cero, se debe restar 1 año
          if(mesesF > 0) {
              edadF --;
          }
          let diasF = 30 - dias;
          // console.log(`Te faltan ${edadF} años, ${mesesF} meses, ${diasF} días para ser adulto`);
      }
  } else {
      $("#edad").val("");
      $("#p_edad").html(`0 años`); 
  }
}

var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

today = yyyy+'-'+mm+'-'+dd;
document.getElementById("nacimiento").setAttribute("max", today);

function validacion_form() {
    if ($('#nombre').val() == '' || $('#tipo_documento').val() == '' ) {
        $('.validar_nombre').addClass('has-error');
        console.log("vacio");
        return false;
    }else{
        $('.validar_nombre').removeClass('has-error');
        console.log("no vacio");
        return true;
    }

}




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
