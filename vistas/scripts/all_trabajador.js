var tabla;

//Función que se ejecuta al inicio
function init() {

  listar( );

  //Mostramos los BANCOS
  $.post("../ajax/all_trabajador.php?op=select2Banco", function (r) { $("#banco").html(r); });

  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  $("#lAllTrabajador").addClass("active");

  $("#guardar_registro").on("click", function (e) {  $("#submit-form-trabajador").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();

  $("#foto1_i").click(function() { $('#foto1').trigger('click'); });
  $("#foto1").change(function(e) { addImage(e,$("#foto1").attr("id")) });

  $("#foto2_i").click(function() { $('#foto2').trigger('click'); });
  $("#foto2").change(function(e) { addImage(e,$("#foto2").attr("id")) });

  $("#foto3_i").click(function() { $('#foto3').trigger('click'); });
  $("#foto3").change(function(e) { addImage(e,$("#foto3").attr("id")) });

  //Initialize Select2 Elements
  $("#banco").select2({
    theme: "bootstrap4",
    placeholder: "Selecione banco",
    allowClear: true,
  });
}

/* PREVISUALIZAR LAS IMAGENES */
function addImage(e,id) {
  // colocamos cargando hasta que se vizualice
  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

	console.log(id);

	var file = e.target.files[0], imageType = /image.*/;
	
	if (e.target.files[0]) {

		var sizeByte = file.size;

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(imageType)){
			// return;
			toastr.error('Este tipo de ARCHIVO no esta permitido <br> elija formato: <b>.png .jpeg .jpg .webp etc... </b>');

			if (id == 'foto1' ) {
        $("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");
      } else {
        if (id == 'foto2' ) {
          $("#"+id+"_i").attr("src", "../dist/img/default/dni_anverso.webp");
        } else {
          $("#"+id+"_i").attr("src", "../dist/img/default/dni_reverso.webp");
        } 
      }

		}else{

			if (sizekiloBytes <= 10240) {

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

				toastr.warning('La imagen: '+file.name.toUpperCase()+' es muy pesada. Tamaño máximo 10mb')

				$("#"+id+"_i").attr("src", "../dist/img/default/img_error.png");

				$("#"+id).val("");
			}
		}

	}else{

		toastr.error('Seleccione una Imagen');

    if (id == 'foto1' ) {
      $("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");
    } else {
      if (id == 'foto2' ) {
        $("#"+id+"_i").attr("src", "../dist/img/default/dni_anverso.webp");
      } else {
        $("#"+id+"_i").attr("src", "../dist/img/default/dni_reverso.webp");
      } 
    }

		$("#"+id+"_nombre").html("");
	}
}

function foto1_eliminar() {

	$("#foto1").val("");

	$("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");

	$("#foto1_nombre").html("");
}

function foto2_eliminar() {

	$("#foto2").val("");

	$("#foto2_i").attr("src", "../dist/img/default/dni_anverso.webp");

	$("#foto2_nombre").html("");
}

function foto3_eliminar() {

	$("#foto3").val("");

	$("#foto3_i").attr("src", "../dist/img/default/dni_reverso.webp");

	$("#foto3_nombre").html("");
}

function sueld_mensual(){

  var sueldo_mensual = $('#sueldo_mensual').val()

  var sueldo_diario=(sueldo_mensual/30).toFixed(1);

  var sueldo_horas=(sueldo_diario/8).toFixed(1);

  $("#sueldo_diario").val(sueldo_diario);

  $("#sueldo_hora").val(sueldo_horas);
}


//Función limpiar
function limpiar() {
  $("#idtrabajador").val("");
  $("#tipo_documento option[value='DNI']").attr("selected", true);
  $("#nombre").val(""); 
  $("#num_documento").val(""); 
  $("#direccion").val(""); 
  $("#telefono").val(""); 
  $("#email").val(""); 
  $("#nacimiento").val("");
  $("#edad").val("0");  $("#p_edad").html("0");    
  $("#c_bancaria").val("");  
  $("#banco").val("").trigger("change");
  $("#titular_cuenta").val("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1").val("");
	$("#foto1_actual").val("");  
  $("#foto1_nombre").html(""); 

  $("#foto2_i").attr("src", "../dist/img/default/dni_anverso.webp");
	$("#foto2").val("");
	$("#foto2_actual").val("");  
  $("#foto2_nombre").html("");  

  $("#foto3_i").attr("src", "../dist/img/default/dni_reverso.webp");
	$("#foto3").val("");
	$("#foto3_actual").val("");  
  $("#foto3_nombre").html(""); 
  
}

//Función Listar
function listar() {

  tabla=$('#tabla-trabajador').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/all_trabajador.php?op=listar',
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
    url: "../ajax/all_trabajador.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {	

        Swal.fire("Correcto!", "Trabajador guardado correctamente", "success");			 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-trabajador").modal("hide");

			}else{

        Swal.fire("Error!", datos, "error");

			}
    },
  });
}

// ver detallles del registro
function verdatos(idtrabajador){

  console.log('id_verdatos'+idtrabajador);  
  
  $('#datostrabajador').html(''+
  '<div class="row" >'+
    '<div class="col-lg-12 text-center">'+
      '<i class="fas fa-spinner fa-pulse fa-6x"></i><br />'+
      '<br />'+
      '<h4>Cargando...</h4>'+
    '</div>'+
  '</div>');

  var verdatos=''; var imagenver='';

  $("#modal-ver-trabajador").modal("show")

  $.post("../ajax/all_trabajador.php?op=verdatos", { idtrabajador: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  //console.log(data); 

    var imagen_perfil =data.imagen_perfil != '' || data.imagen_perfil != null ? '<img src="../dist/img/usuarios/'+data.imagen_perfil+'" alt="" class="img-thumbnail">' : '<img src="../dist/svg/user_default.svg" alt="" style="width: 90px;">';
    var imagen_dni_anverso =data.imagen_dni_anverso != '' ? '<img src="../dist/img/usuarios/'+data.imagen_dni_anverso+'" alt="" class="img-thumbnail">' : 'No hay imagen';
    var imagen_dni_reverso =data.imagen_dni_reverso != '' ? '<img src="../dist/img/usuarios/'+data.imagen_dni_reverso+'" alt="" class="img-thumbnail">' : 'No hay imagen';
    
    verdatos=''+                                                                            
    '<div class="col-12">'+
      '<div class="card">'+
          '<div class="card-body ">'+
              '<table class="table table-hover table-bordered">'+          
                  '<tbody>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th rowspan="2">'+imagen_perfil+'</th>'+
                          '<td> <b>Nombre: </b> '+data.nombres+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<td> <b>DNI: </b>  '+data.numero_documento+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Dirección</th>'+
                          '<td>'+data.direccion+'</td>'+ 
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Correo</th>'+
                          '<td>'+data.email+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Teléfono</th>'+
                          '<td>'+data.telefono+'</td>'+ 
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Fecha nacimiento</th>'+
                          '<td>'+data.fecha_nacimiento+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Cuenta bancaria</th>'+
                          '<td>'+data.cuenta_bancaria+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Banco</th>'+
                          '<td>'+data.banco+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Titular cuenta </th>'+
                          '<td>'+data.titular_cuenta+'</td>'+
                      '</tr>'+

                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>DNI anverso</th>'+
                          '<td> '+imagen_dni_anverso+' </td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>DNI reverso</th>'+
                          '<td> '+imagen_dni_reverso+' </td>'+
                      '</tr>'+
                  '</tbody>'+
              '</table>'+
          '</div>'+
      '</div>'+
    '</div>';
  
    $("#datostrabajador").html(verdatos);

  });
}

// mostramos los datos para editar
function mostrar(idtrabajador) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-trabajador").modal("show")

  $.post("../ajax/all_trabajador.php?op=mostrar", { idtrabajador: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();


    $("#tipo_documento option[value='"+data.tipo_documento+"']").attr("selected", true);
    $("#nombre").val(data.nombres);
    $("#num_documento").val(data.numero_documento);
    $("#direccion").val(data.direccion);
    $("#telefono").val(data.telefono);
    $("#email").val(data.email);
    $("#nacimiento").val(data.fecha_nacimiento);
    $("#c_bancaria").val(data.cuenta_bancaria);
    $("#banco").val(data.idbancos).trigger("change");
    $("#titular_cuenta").val(data.titular_cuenta);
    $("#idtrabajador").val(data.idtrabajador);

    if (data.imagen_perfil != "") {

			$("#foto1_i").attr("src", "../dist/img/usuarios/" + data.imagen_perfil);

			$("#foto1_actual").val(data.imagen_perfil);
		}

    if (data.imagen_dni_anverso != "") {

			$("#foto2_i").attr("src", "../dist/img/usuarios/" + data.imagen_dni_anverso);

			$("#foto2_actual").val(data.imagen_dni_anverso);
		}

    if (data.imagen_dni_reverso != "") {

			$("#foto3_i").attr("src", "../dist/img/usuarios/" + data.imagen_dni_reverso);

			$("#foto3_actual").val(data.imagen_dni_reverso);
		}

    edades();
  });
}

//Función para desactivar registros
function desactivar(idtrabajador) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  el trabajador?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/all_trabajador.php?op=desactivar", { idtrabajador: idtrabajador }, function (e) {

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
      $.post("../ajax/all_trabajador.php?op=activar", { idtrabajador: idtrabajador }, function (e) {

        Swal.fire("Activado!", "Tu trabajador ha sido activado.", "success");

        tabla.ajax.reload();
      });
      
    }
  });      
}

init();

$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {

      guardaryeditar(e);

    },
  });

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
      banco: { required: true},

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
        maxlength: "El número documento debe tener maximo 14 caracteres.",
      },
      banco: {
        required: "Este campo es requerido",
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

    },



  });
});


/*Validación Fecha de Nacimiento Mayoria de edad del usuario*/
function edades() {

  var fechaUsuario = $("#nacimiento").val();

  if (fechaUsuario) {         
  
    //El siguiente fragmento de codigo lo uso para igualar la fecha de nacimiento con la fecha de hoy del usuario
    let d = new Date(),    month = '' + (d.getMonth() + 1),    day = '' + d.getDate(),   year = d.getFullYear();
    
    if (month.length < 2) 
      month = '0' + month;
    if (day.length < 2) 
      day = '0' + day;
    d=[year, month, day].join('-')

    /*------------*/
    var hoy = new Date(d);//fecha del sistema con el mismo formato que "fechaUsuario"

    var cumpleanos = new Date(fechaUsuario);
    
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

// restringimos la fecha para no elegir mañana
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
          $("#titular_cuenta").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

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

              data.razonSocial == null ? $("#nombre").val("-") : $("#nombre").val(data.razonSocial);

              data.razonSocial == null ? $("#titular_cuenta").val("-") : $("#titular_cuenta").val(data.razonSocial);
              
              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);

              toastr.success("Cliente encontrado");

            } else {

              toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.razonSocial == null ? $("#nombre").val("-") : $("#nombre").val(data.razonSocial);

              data.razonSocial == null ? $("#titular_cuenta").val("-") : $("#titular_cuenta").val(data.razonSocial);
              
              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);

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
