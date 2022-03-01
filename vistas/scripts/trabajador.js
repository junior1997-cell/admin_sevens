var tabla;
var editando=false;
var editando2=false;

//Función que se ejecuta al inicio
function init() {  

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lTrabajador").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar( localStorage.getItem('nube_idproyecto') );   

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
  
  $("#cargo").val('null').trigger("change");

  $('#fecha_inicio').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
  // Inicializar - Date picker  
  $('#fecha_inicio').datetimepicker({
    locale: 'es',
    // format: 'L',
    format: 'DD-MM-YYYY',
    daysOfWeekDisabled: [6],     
    //defaultDate: "",
  });

  $('#fecha_fin').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
  // Inicializar - Date picker  
  $('#fecha_fin').datetimepicker({
    locale: 'es',
    // format: 'L',
    format: 'DD-MM-YYYY',
    daysOfWeekDisabled: [6],     
    //defaultDate: "",
  });
  
}
//captura id del trabajador
function capture_idtrabajador() {
  if (editando2==false) {
      var idtrabajador= $("#trabajador").select2("val");
      if (idtrabajador == null || idtrabajador == '' ) {

      }else{

        $("#tipo_trabajador").val("null").trigger("change");
          
        $.post("../ajax/trabajador.php?op=m_datos_trabajador", { idtrabajador: idtrabajador }, function (data, status) {

          data = JSON.parse(data);  console.log(data);   

          $("#tipo_trabajador").val(data.idtipo_trabajador).trigger("change");

          $("#ocupacion").val(data.nombre_ocupacion);   
        });

      }
  }
  editando2=false;

}
//captura id del tipo

function captura_idtipo() {
  if (editando==false) {

    var idtipo= $("#tipo_trabajador").select2("val");
    if (idtipo != null || idtipo != ' ' ) {
       //console.log(idtipo);
       $.post('../ajax/trabajador.php?op=select_cargo&id_tipo='+idtipo+'', function (r) { $("#cargo").html(r); });
     }else{
   
     }

  }
  editando=false;

}

function estado_editar(estado) {
  editando=estado;
  editando2=estado;
  
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

  var fecha_incial_proyecto = "" ;
  
  var fecha_final_proyecto = "" ;

  if (localStorage.getItem('nube_fecha_inicial_proyecto') == "" || localStorage.getItem('nube_fecha_inicial_proyecto') === undefined || localStorage.getItem('nube_fecha_inicial_proyecto') == null) {
    fecha_incial_proyecto = ""
  } else {
    fecha_incial_proyecto = format_d_m_a(localStorage.getItem('nube_fecha_inicial_proyecto'));
  }

  if (localStorage.getItem('nube_fecha_final_proyecto') == "" || localStorage.getItem('nube_fecha_final_proyecto') === undefined || localStorage.getItem('nube_fecha_final_proyecto') == null) {
    fecha_final_proyecto = ""
  } else {
    fecha_final_proyecto = format_d_m_a(localStorage.getItem('nube_fecha_final_proyecto')) ;
  }

  $("#trabajador").val("").trigger("change");

  $("#tipo_trabajador").val("").trigger("change");
  $("#cargo").val("").trigger("change");
  $("#desempenio").val("");
  $("#ocupacion").val("");

  $("#sueldo_mensual").val("");   
  $("#sueldo_diario").val("");   
  $("#sueldo_hora").val("");

  $("#fecha_inicio").val(fecha_incial_proyecto);  $("#fecha_fin").val(fecha_final_proyecto); $('#cantidad_dias').val('')
  $('#cantidad_dias').removeClass('input-no-valido input-valido');

  // limpiamos las validaciones del FORM
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
  calcular_dias_trabajo();
}

//Función Listar
function listar( nube_idproyecto ) {

  tabla=$('#tabla-trabajadors').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
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
    createdRow: function (row, data, ixdex) {  

      // columna: 
      if (data[1] != '') {
        $("td", row).eq(1).addClass("text-nowrap" );
      }
      // columna: sueldo mensual
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-right" );
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
         
				show_hide_form(false);

			}else{

				Swal.fire("Error!", datos, "error");
			}
    },
  });
}

function verdatos(idtrabajador){

  console.log('id_verdatos'+idtrabajador);  
  
  $('#datostrabajador').html(`<div class="row" >
    <div class="col-lg-12 text-center">
      <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
      <br />
      <h4>Cargando...</h4>
    </div>
  </div>`);

  var verdatos='';
  var imagenver='';

  $("#modal-ver-trabajador").modal("show")

  $.post("../ajax/trabajador.php?op=verdatos", { idtrabajador_por_proyecto: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 

    var img =data.imagen != '' ? `<img src="../dist/img/usuarios/${data.imagen}" alt="" style="width: 90px;border-radius: 10px;" onerror="this.src='../dist/svg/user_default.svg';">` : '<img src="../dist/svg/user_default.svg" alt="" style="width: 90px;">';
    
    verdatos=`                                                                           
      <div class="col-12">
        <div class="card">
          <div class="card-body ">
            <table class="table table-hover table-bordered">         
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th rowspan="2">${img}</th>
                  <td>${data.nombres}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>${data.numero_documento}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Dirección</th>
                  <td>${data.direccion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Correo</th>
                  <td><a href="mailto:${data.email}" data-toggle="tooltip" data-original-title="Llamar al trabajador."> ${data.email} </a></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Teléfono</th>
                  <td> <a href="tel:+51${quitar_guion(data.telefono)}" data-toggle="tooltip" data-original-title="Llamar al trabajador."> ${data.telefono} </a></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha nacimiento</th>
                  <td>${format_d_m_a(data.fecha_nacimiento)}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo trabajador</th>
                  <td>${data.tipo_trabajador}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Cargo</th>
                  <td>${data.cargo_trabajador}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Desempeño</th>
                  <td>${data.desempeno}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Cuenta bancaria</th>
                  <td>${data.cuenta_bancaria}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Banco</th>
                  <td>${data.banco}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Titular cuenta </th>
                  <td>${data.titular_cuenta}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Sueldo por mes</th>
                  <td>${data.sueldo_mensual}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Sueldo por día</th>
                  <td>${data.sueldo_diario}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Sueldo por hora</th>
                  <td>${data.sueldo_hora}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
  
    $("#datostrabajador").html(verdatos);

  });
}

function mostrar(idtrabajador,idtipo) {
  limpiar();
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
 // $("#tipo_trabajador").val('null').trigger("change");
 // $("#cargo").val('null').trigger("change");
  show_hide_form(true);
  $.post('../ajax/trabajador.php?op=select_cargo&id_tipo='+idtipo+'', function (r) { $("#cargo").html(r); });
  estado_editar(true);
  $.post("../ajax/trabajador.php?op=mostrar", { idtrabajador_por_proyecto: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idtrabajador_por_proyecto").val(data.idtrabajador_por_proyecto);
    $("#trabajador").val(data.idtrabajador).trigger("change");

    $("#tipo_trabajador").val(data.idtipo_trabajador).trigger("change");
    $("#ocupacion").val(data.nombre_ocupacion);
    $("#cargo").val(data.idcargo_trabajador).trigger("change");
    $("#desempenio").val(data.desempenio);
  
    $("#sueldo_mensual").val(data.sueldo_mensual);   
    $("#sueldo_diario").val(data.sueldo_diario);   
    $("#sueldo_hora").val(data.sueldo_hora);

    $("#fecha_inicio").val(format_d_m_a(data.fecha_inicio));
    $("#fecha_fin").val(format_d_m_a(data.fecha_fin)); 
    $("#cantidad_dias").val(data.cantidad_dias);
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

function calcular_dias_trabajo() {
  var fecha_i = $('#fecha_inicio').val();
  var fecha_f = $('#fecha_fin').val();

  console.log(fecha_i, fecha_f);

  if (fecha_i != '' && fecha_f != '') {

    cantida_dias = diferencia_de_dias(format_a_m_d( fecha_i), format_a_m_d(fecha_f) );

    $('#cantidad_dias').addClass('input-valido').removeClass('input-no-valido');
    $('#cantidad_dias').val((cantida_dias + 1));

  } else {
    $('#cantidad_dias').removeClass('input-valido').addClass('input-no-valido');
    $('#cantidad_dias').val(0);
  }  
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

// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {

  var format = "";

  if (fecha == '' || fecha == null) {
    format = "-";
  } else {
    let splits = fecha.split("-"); //console.log(splits);
    format = splits[2]+'-'+splits[1]+'-'+splits[0];
  } 

  return format;
}

// convierte de una fecha(aa-mm-dd): 23-12-2021 a una fecha(dd-mm-aa): 2021-12-23
function format_a_m_d(fecha) {

  var format = "";

  if (fecha == '' || fecha == null) {
    format = "-";
  } else {
    let splits = fecha.split("-"); //console.log(splits);
    format = splits[2]+'-'+splits[1]+'-'+splits[0];
  } 

  return format;
}

function diferencia_de_dias(fecha_i, fecha_f) {
  var fecha1 = moment(fecha_i);
  var fecha2 = moment(fecha_f); 
  return fecha2.diff(fecha1, 'days');
}

function quitar_guion(numero){ 
  return numero.replace("-", ""); 
} 