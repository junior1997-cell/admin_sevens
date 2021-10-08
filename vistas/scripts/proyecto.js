var tabla;
//Función que se ejecuta al inicio
function init(){ 
  
  listar();

  $("#guardar_registro").on("click", function (e) { $("#submit-form-proyecto").submit(); });

  $('#mEscritorio').addClass("active");
}

//Función limpiar
function limpiar() {
  $("#idproyecto").val("");  
  $("#tipo_documento option[value='RUC']").attr("selected", true);
  $("#numero_documento").val(""); 
  $("#empresa").val(""); 
  $("#nombre_proyecto").val("");
  $("#ubicacion").val(""); 
  $("#actividad_trabajo").val("");  
  $("#fecha_inicio_fin").val("");    
  $("#plazo").val(""); 
  $("#costo").val(""); 
  $("#empresa_acargo").val(""); 

  $("#doc1").val(""); 
  $("#doc_old_1").val(""); 

  $("#doc2").val(""); 
  $("#doc_old_2").val(""); 

  $("#doc3").val(""); 
  $("#doc_old_3").val(""); 

}

//Función Listar
function listar() {

  tabla=$('#tabla-proyectos').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/proyecto.php?op=listar',
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

  //Date range picker
  $('#fecha_inicio_fin').daterangepicker();
  // $('input[name="fecha_inicio_fin"]').on('apply.daterangepicker', function(ev, picker) {
  //     $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  // });

  // $('input[name="fecha_inicio_fin"]').on('cancel.daterangepicker', function(ev, picker) {
  //     $(this).val('');
  // });

  // validamo el formulario
  $.validator.setDefaults({

    submitHandler: function (e) {

      guardaryeditar(e);       
    },
  });

  $("#form-proyecto").validate({
    rules: {
      tipo_documento: { maxlength: 45 },
      numero_documento: { required: true, minlength: 6, maxlength: 20 },
      empresa: { required: true, minlength: 6, maxlength: 200 },
      nombre_proyecto: { required: true, minlength: 6, maxlength: 200},
      ubicacion: {minlength: 6, maxlength: 300},
      actividad_trabajo: {minlength: 6},
      empresa_acargo: {minlength: 6, maxlength: 200},
      costo:{minlength: 1, maxlength: 11},
      fecha_inicio_fin:{required: true,minlength: 1, maxlength: 25}
    },
    messages: {
      numero_documento: {
        required: "Este campo es requerido.",
        minlength: "El login debe tener MÍNIMO 6 caracteres.",
        maxlength: "El login debe tener como MÁXIMO 20 caracteres.",
      },
      empresa: {
        required: "Este campo es requerido.",
        minlength: "La contraseña debe tener MÍNIMO 6 caracteres.",
        maxlength: "La contraseña debe tener como MÁXIMO 200 caracteres.",
      },
      nombre_proyecto: {
        required: "Este campo es requerido.",
        minlength: "La contraseña debe tener MÍNIMO 6 caracteres.",
        maxlength: "La contraseña debe tener como MÁXIMO 200 caracteres.",
      },
      ubicacion: {
        minlength: "La contraseña debe tener MÍNIMO 6 caracteres.",
        maxlength: "La contraseña debe tener como MÁXIMO 300 caracteres.",
      },
      actividad_trabajo: {
        minlength: "La contraseña debe tener MÍNIMO 6 caracteres.",
      },
      empresa_acargo: {
        minlength: "La contraseña debe tener MÍNIMO 6 caracteres.",
        maxlength: "La contraseña debe tener como MÁXIMO 200 caracteres.",
      },
      costo: {
        minlength: "La contraseña debe tener MÍNIMO 1 caracteres.",
        maxlength: "La contraseña debe tener como MÁXIMO 11 caracteres.",
      },
      fecha_inicio_fin: {
        required: "Este campo es requerido.",
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

      // if ($("#trabajador").select2("val") == null && $("#trabajador_old").val() == "") {
         
      //   $("#trabajador_validar").show(); //console.log($("#trabajador").select2("val") + ", "+ $("#trabajador_old").val());

      // } else {

      //   $("#trabajador_validar").hide();
      // }       
    },
  });
});

// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_documento").val();

  let dni_ruc = $("#numero_documento").val(); 
   
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

          $("#empresa").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

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

              $("#empresa").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);
              
              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);
              // $("#direccion").val(data.direccion);
              toastr.success("Cliente encontrado");
            } else {

              toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

              $("#search").show();

              $("#charge").hide();

              $("#empresa").val(data.razonSocial);

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

// caculamos el plazo
function calcular_palzo() {

  $("#fecha_inicio_fin").on("apply.daterangepicker", function (ev, picker) { 

    var plazo_dia = picker.endDate.diff(picker.startDate, "days");

    $("#plazo").val( plazo_dia +' dias');    
  });
}

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImage(e,$("#doc1").attr("id")) });

$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImage(e,$("#doc2").attr("id")) });

$("#doc3_i").click(function() {  $('#doc3').trigger('click'); });
$("#doc3").change(function(e) {  addImage(e,$("#doc3").attr("id")) });

/* PREVISUALIZAR LAS IMAGENES */
function addImage(e,id) {
  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');
	console.log(id);

	var file = e.target.files[0], imageType = /application.pdf/;
	
	if (e.target.files[0]) {

		var sizeByte = file.size;

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(imageType)){
			// return;
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Este tipo de ARCHIVO no esta permitido elija formato: mi-documento.pdf',
        showConfirmButton: false,
        timer: 1500
      });			 
      $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
			$("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");

		}else{

			if (sizekiloBytes <= 10000) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;
				 
          $("#"+id+"_ver").html('<iframe src="'+result+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');

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

          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'El documento: '+file.name.toUpperCase()+' es aceptado.',
            showConfirmButton: false,
            timer: 1500
          });
				}

				reader.readAsDataURL(file);

			} else {
        Swal.fire({
          position: 'top-end',
          icon: 'warning',
          title: 'El documento: '+file.name.toUpperCase()+' es muy pesado.',
          showConfirmButton: false,
          timer: 1500
        })

        $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

				$("#"+id+"_i").attr("src", "../dist/img/default/img_error.png");

				$("#"+id).val("");
			}
		}
	}else{
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Seleccione un documento',
      showConfirmButton: false,
      timer: 1500
    })
		 
    $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

		$("#"+id+"_nombre").html("");
	}	
}
// Eliminamos el doc
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

// Eliminamos el doc
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

// Eliminamos el doc
function doc3_eliminar() {

	$("#doc3").val("");

	$("#doc3_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc3_nombre").html("");
}