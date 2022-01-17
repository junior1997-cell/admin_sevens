var tabla;

//Función que se ejecuta al inicio
function init() {  

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar( localStorage.getItem('nube_idproyecto') );

  // $("#bloc_Accesos").addClass("menu-open");

  $("#mTrabajador").addClass("active");

  // $("#ltrabajador").addClass("active"); 

  // Formato para telefono
  $("[data-mask]").inputmask();

  //Mostramos los trabajadores
  $.post("../ajax/trabajador.php?op=select2Trabajador&id=", function (r) { $("#trabajador").html(r); });

    //Mostramos los tipo
    $.post("../ajax/tipo.php?op=selecttipo_tipo", function (r) { $("#tipo_trabajador").html(r); });

  //Initialize Select2 Elements
  $("#trabajador").select2({
    theme: "bootstrap4",
    placeholder: "Selecione trabajador",
    allowClear: true,
  });  

  //Initialize Select2 Elements
  $("#tipo_trabajador").select2({
    theme: "bootstrap4",
    placeholder: "Selecione tipo trabajador",
    allowClear: true,
  });

  //Initialize Select2 Elements
  $("#cargo").select2({
    theme: "bootstrap4",
    placeholder: "Selecione cargo",
    allowClear: true,
  });
  
}

function capture_trabajador() {
 var idtrabajador= $("#trabajador").select2("val");
 if (idtrabajador=='null' || idtrabajador=='' ) {
   
 }else{
  $("#tipo_trabajador").val("null").trigger("change");
  
  $.post("../ajax/trabajador.php?op=m_datos_trabajador", { idtrabajador: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $("#tipo_trabajador").val(data.idtipo_trabajador).trigger("change");

    $("#ocupacion").val(data.nombre_ocupacion);   
  });
}

}

function sueld_mensual(){

  var sueldo_mensual = $('#sueldo_mensual').val();

  var sueldo_diario=(sueldo_mensual/30).toFixed(2);

  var sueldo_horas=(sueldo_diario/8).toFixed(2);

  $("#sueldo_diario").val(sueldo_diario);

  $("#sueldo_hora").val(sueldo_horas);
}

function show_hide_form(flag) {

  limpiar();

	if (flag)	{

		$("#mostrar-form").show();
		$("#mostrar-tabla").hide();

	}	else	{

		$("#mostrar-form").hide();
		$("#mostrar-tabla").show();
	}
}

function disable_cargo() {
  $('#cargo option[value="Operario"]').prop('disabled',true);
  $('#cargo option[value="Oficial"]').prop('disabled',true);
  $('#cargo option[value="Peón"]').prop('disabled',true);

  $('#cargo option[value="Ingeniero Residente"]').prop('disabled',true);
  $('#cargo option[value="Asitente Técnico"]').prop('disabled',true);
  $('#cargo option[value="Asistente Administrativo"]').prop('disabled',true);
  $('#cargo option[value="Almacenero"]').prop('disabled',true);

  if ($("#tipo_trabajador").select2("val") == "Técnico") {    
    $('#cargo option[value="Operario"]').prop('disabled',true);
    $('#cargo option[value="Oficial"]').prop('disabled',true);
    $('#cargo option[value="Peón"]').prop('disabled',true);

    $('#cargo option[value="Ingeniero Residente"]').prop('disabled',false);
    $('#cargo option[value="Asitente Técnico"]').prop('disabled',false);
    $('#cargo option[value="Asistente Administrativo"]').prop('disabled',false);
    $('#cargo option[value="Almacenero"]').prop('disabled',false);      

  } else {

    if ($("#tipo_trabajador").select2("val") == "Obrero") {      

      $('#cargo option[value="Operario"]').prop('disabled',false);
      $('#cargo option[value="Oficial"]').prop('disabled',false);
      $('#cargo option[value="Peón"]').prop('disabled',false);

      $('#cargo option[value="Ingeniero Residente"]').prop('disabled',true);
      $('#cargo option[value="Asitente Técnico"]').prop('disabled',true);
      $('#cargo option[value="Asistente Administrativo"]').prop('disabled',true);
      $('#cargo option[value="Almacenero"]').prop('disabled',true);
    }
  }   
}

//Función limpiar
function limpiar() {  

  $("#trabajador").val("").trigger("change");

  $("#tipo_trabajador").val("").trigger("change");
  $("#cargo").val("").trigger("change");
  $("#desempenio").val("");

  $("#sueldo_mensual").val("");   
  $("#sueldo_diario").val("");   
  $("#sueldo_hora").val("");
}

//Función Listar
function listar( nube_idproyecto ) {

  tabla=$('#tabla-trabajadors').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/trabajador.php?op=listar&nube_idproyecto='+nube_idproyecto,
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
  var formData = new FormData($("#form-trabajador-proyecto")[0]);

  $.ajax({
    url: "../ajax/trabajador.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

        Swal.fire("Correcto!", "Trabajador registrado correctamente", "success");

	      tabla.ajax.reload();
         
				show_hide_form(false)

			}else{

				Swal.fire("Error!", datos, "error");
			}
    },
  });
}

function verdatos(idtrabajador){

  console.log('id_verdatos'+idtrabajador);
  
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $('#datostrabajador').html('');
  var verdatos='';
  var imagenver='';

  $("#modal-ver-trabajador").modal("show")

  $.post("../ajax/trabajador.php?op=verdatos", { idtrabajador_por_proyecto: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 

    var img =data.imagen != '' ? '<img src="../dist/img/usuarios/'+data.imagen+'" alt="" style="width: 90px;border-radius: 10px;">' : '<img src="../dist/svg/user_default.svg" alt="" style="width: 90px;">';
     verdatos=''+                                                                            
    '<div class="col-12">'+
      '<div class="card">'+
          '<div class="card-body ">'+
              '<table class="table table-hover table-bordered">'+          
                  '<tbody>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th rowspan="2">'+img+'</th>'+
                          '<td>'+data.nombres+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<td>'+data.numero_documento+'</td>'+
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
                          '<th>Tipo trabajador</th>'+
                          '<td>'+data.tipo_trabajador+'</td>'+ 
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Cargo</th>'+
                          '<td>'+data.cargo+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Desempeño</th>'+
                          '<td>'+data.desempeno+'</td>'+ 
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
                          '<th>Sueldo por mes</th>'+
                          '<td>'+data.sueldo_mensual+'</td>'+ 
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Sueldo por día</th>'+
                          '<td>'+data.sueldo_diario+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Sueldo por hora</th>'+
                          '<td>'+data.sueldo_hora+'</td>'+
                      '</tr>'+
                  '</tbody>'+
              '</table>'+
          '</div>'+
      '</div>'+
    '</div>';
  
  $("#datostrabajador").append(verdatos);

});

}

function mostrar(idtrabajador) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  show_hide_form(true);

  $.post("../ajax/trabajador.php?op=mostrar", { idtrabajador_por_proyecto: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idtrabajador_por_proyecto").val(data.idtrabajador_por_proyecto);
    $("#trabajador").val(data.idtrabajador).trigger("change");

    $("#tipo_trabajador").val(data.tipo_trabajador).trigger("change");
    $("#cargo").val(data.cargo).trigger("change");
    $("#desempenio").val(data.desempenio);
  
    $("#sueldo_mensual").val(data.sueldo_mensual);   
    $("#sueldo_diario").val(data.sueldo_diario);   
    $("#sueldo_hora").val(data.sueldo_hora);
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
      $.post("../ajax/trabajador.php?op=desactivar", { idtrabajador_por_proyecto: idtrabajador }, function (e) {

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
      $.post("../ajax/trabajador.php?op=activar", { idtrabajador_por_proyecto: idtrabajador }, function (e) {

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

  $("#form-trabajador-proyecto").validate({
    rules: {
      trabajador: { required: true},
      tipo_trabajador: { required: true},
      cargo: { required: true},
      desempenio: { minlength: 4, maxlength: 100},
      sueldo_mensual: { required: true, minlength: 1},
      sueldo_diario: { required: true, minlength: 1},
      sueldo_hora: { required: true, minlength: 1},
      
      // terms: { required: true },
    },
    messages: {
      trabajador: {
        required: "Por favor  seleccione un trabajador.",
      },

      tipo_trabajador: {
        required: "Por favor  seleccione un tipo trabajador.",
      },
      cargo: {
        required: "Por favor  un cargo.",
      },
      desempenio: {
        minlength: "Escriba como minimo 4 letras.",
        maxlength: "Escriba como máximo 100 letras.",
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

