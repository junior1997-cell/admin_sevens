var tabla, tabla_secundaria;
var editando=false;
var editando2=false;

//Función que se ejecuta al inicio
function init() {  

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lTrabajador").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  tbla_principal( localStorage.getItem('nube_idproyecto') ); 
  tbla_secundaria( localStorage.getItem('nube_idproyecto') ); 
  
  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Trabajador", '#trabajador', null);
  lista_select2("../ajax/ajax_general.php?op=select2TipoTrabajador", '#tipo_trabajador', null);

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#trabajador").select2({ templateResult: templateTrabajador, theme: "bootstrap4", placeholder: "Selecione trabajador", allowClear: true, });  

  $("#tipo_trabajador").select2({ theme: "bootstrap4", placeholder: "Selecione tipo trabajador", allowClear: true, });

  $("#cargo").select2({ theme: "bootstrap4", placeholder: "Selecione cargo", allowClear: true, });
  
  // ══════════════════════════════════════ INITIALIZE datetimepicker ══════════════════════════════════════

  $('#fecha_inicio').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
 
  $('#fecha_inicio').datetimepicker({ locale: 'es',  /* format: 'L',*/  format: 'DD-MM-YYYY', daysOfWeekDisabled: [6],  /*defaultDate: "", */ });

  $('#fecha_fin').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
 
  $('#fecha_fin').datetimepicker({ locale: 'es', /*format: 'L',*/ format: 'DD-MM-YYYY', daysOfWeekDisabled: [6], /*defaultDate: "",*/ });

  // Formato para telefono
  $("[data-mask]").inputmask();
  
}

function templateTrabajador(state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/all_trabajador/perfil/${state.title}`: '../dist/svg/user_default.svg'; 
  var onerror = `onerror="this.src='../dist/svg/user_default.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

//captura id del trabajador
function capture_idtrabajador() {
  console.log('editando2: ' + editando2);
  if (editando2==false) {
    var idtrabajador= $("#trabajador").select2("val");
    if (idtrabajador == null || idtrabajador == '' ) {  }else{

      $("#tipo_trabajador").val("null").trigger("change");
        
      $.post("../ajax/trabajador.php?op=m_datos_trabajador", { idtrabajador: idtrabajador }, function (e, status) {

        e = JSON.parse(e);  console.log(e);   
        $("#tipo_trabajador").val(e.data.idtipo_trabajador).trigger("change");
        $("#ocupacion").val(e.data.nombre_ocupacion);   

      }).fail( function(e) { ver_errores(e); } );
    }
  }
  
}

//captura id del tipo
function captura_idtipo() {
  console.log('editando: ' + editando);
  if (editando==false) {
    var idtipo= $("#tipo_trabajador").select2("val");
    if (idtipo != null || idtipo != ' ' ) {
      lista_select2(`../ajax/ajax_general.php?op=select2CargoTrabajdorId&idtipo=${idtipo}`, '#cargo', null);
    }
  }
  
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

  // tabla principal
	if (flag == 1)	{		
		$("#mostrar-tabla").show();
    $("#mostrar-form").hide();
    editando=false; editando2=false;
	}	else	{
    // formulario		    
		$("#mostrar-tabla").hide();
    $("#mostrar-form").show();    
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
  $("#idtrabajador_por_proyecto").val("");   
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

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
  
  calcular_dias_trabajo();
}

//Función Listar
function tbla_principal( nube_idproyecto ) {

  tabla=$('#tabla-trabajador').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'pdf', "colvis"],
    ajax:{
      url: `../ajax/trabajador.php?op=tbla_principal&nube_idproyecto=${nube_idproyecto}&estado=1`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: 
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap" ); }
      // columna: sueldo mensual
      if (data[5] != '') { $("td", row).eq(5).addClass("text-right" ); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función Listar
function tbla_secundaria( nube_idproyecto ) {

  tabla_secundaria=$('#tabla-trabajador-suspendido').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'pdf', "colvis"],
    ajax:{
      url: `../ajax/trabajador.php?op=tbla_principal&nube_idproyecto=${nube_idproyecto}&estado=0`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: 
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap" ); }
      // columna: sueldo mensual
      if (data[5] != '') { $("td", row).eq(5).addClass("text-right" ); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
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
    success: function (e) {
      e = JSON.parse(e);
      try {
        if (e.status == true) {
          Swal.fire("Correcto!", "Trabajador registrado correctamente", "success");
          if (tabla) { tabla.ajax.reload(null, false); } 
          if (tabla) { tabla_secundaria.ajax.reload(null, false); }
                   
          show_hide_form(1);
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }       

      $("#guardar_registro_trabajador").html('Guardar Cambios').removeClass('disabled');
      editando=false; editando2=false;
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_trabajador").css({"width": percentComplete+'%'});
          $("#barra_progress_trabajador").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_trabajador").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_trabajador").css({ width: "0%",  });
      $("#barra_progress_trabajador").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_trabajador").css({ width: "0%", });
      $("#barra_progress_trabajador").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); editando=false; editando2=false; },
  });
}

function verdatos(idtrabajador){

  console.log('id_verdatos'+idtrabajador);  
  
  $('#datostrabajador').html(`<div class="row" > <div class="col-lg-12 text-center"> <i class="fas fa-spinner fa-pulse fa-6x"></i><br/> <br/> <h4>Cargando...</h4> </div> </div>`);

  var verdatos='';
  var imagen_perfil =''; btn_imagen_perfil=''; 

  $("#modal-ver-trabajador").modal("show")

  $.post("../ajax/trabajador.php?op=ver_datos_trabajador", { idtrabajador_por_proyecto: idtrabajador }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    if (e.status == true) {
      
    
      if (e.data.trabajador.imagen_perfil != '') {

        imagen_perfil=`<img src="../dist/docs/all_trabajador/perfil/${e.data.trabajador.imagen_perfil}" alt="" class="img-thumbnail">`
        
        btn_imagen_perfil=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/perfil/${e.data.trabajador.imagen_perfil}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/perfil/${e.data.trabajador.imagen_perfil}" download="PERFIL ${e.data.trabajador.nombres}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {
        imagen_perfil='No hay imagen';
        btn_imagen_perfil='';
      }
    
      var banco ="";

      e.data.bancos.forEach((valor, index) => {
        banco = banco.concat( `<tr data-widget="expandable-table" aria-expanded="false">
          <th>Banco <br> Cta. <br> CCI </th>
          <td> 
            <div class="user-block">
              ${(valor.banco_seleccionado == 1? '<img class="img-circle" src="../dist/svg/check-mark.svg" >': '<img class="img-circle" src="../dist/svg/close-mark.svg" >' )}
              <span class="username">${valor.banco}</span>
              <span class="description">${valor.cuenta_bancaria}</span>    
              <span class="description">${valor.cci}</span>              
            </div>
          </td>
        </tr>`);
      });

      verdatos=`                                                                           
        <div class="col-12">
          <div class="card">
            <div class="card-body ">
              <table class="table table-hover table-bordered">         
                <tbody>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th rowspan="2">${imagen_perfil}<br>${btn_imagen_perfil}</th>
                    <td><b>Nombre: </b>${e.data.trabajador.nombres}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <td><b>DNI: </b> ${e.data.trabajador.numero_documento}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Dirección</th>
                    <td>${e.data.trabajador.direccion}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Correo</th>
                    <td><a href="mailto:${e.data.trabajador.email}" data-toggle="tooltip" data-original-title="Llamar al trabajador."> ${e.data.trabajador.email} </a></td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Teléfono</th>
                    <td> <a href="tel:+51${quitar_guion(e.data.trabajador.telefono)}" data-toggle="tooltip" data-original-title="Llamar al trabajador."> ${e.data.trabajador.telefono} </a></td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Nacimiento</th>
                    <td>${format_d_m_a(e.data.trabajador.fecha_nacimiento)}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Tipo trabajador</th>
                    <td>${e.data.trabajador.tipo_trabajador}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Cargo</th>
                    <td>${e.data.trabajador.cargo_trabajador}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Desempeño</th>
                    <td>${e.data.trabajador.desempeno}</td>
                  </tr>
                  ${banco}
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Titular cuenta </th>
                    <td>${e.data.trabajador.titular_cuenta}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Sueldo Mes</th>
                    <td>S/. ${formato_miles(e.data.trabajador.sueldo_mensual)}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Sueldo Día</th>
                    <td>S/. ${formato_miles(e.data.trabajador.sueldo_diario)}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Sueldo Hora</th>
                    <td>S/. ${formato_miles(e.data.trabajador.sueldo_hora)}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>`;
    
      $("#datostrabajador").html(verdatos);

    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function mostrar(idtrabajador,idtipo) {
  estado_editar(true); 

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar();

  show_hide_form(2);  

  $.post("../ajax/trabajador.php?op=mostrar", { idtrabajador_por_proyecto: idtrabajador }, function (e, status) {

    e = JSON.parse(e);  console.log(e);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idtrabajador_por_proyecto").val(e.data.idtrabajador_por_proyecto);
    $("#trabajador").val(e.data.idtrabajador).trigger("change");

    $("#tipo_trabajador").val(e.data.idtipo_trabajador).trigger("change");
    $("#ocupacion").val(e.data.nombre_ocupacion);
    //$("#cargo").val(e.data.idcargo_trabajador).trigger("change");
    lista_select2(`../ajax/ajax_general.php?op=select2CargoTrabajdorId&idtipo=${idtipo}`, '#cargo', e.data.idcargo_trabajador);
    $("#desempenio").val(e.data.desempenio);
  
    $("#sueldo_mensual").val(e.data.sueldo_mensual);   
    $("#sueldo_diario").val(e.data.sueldo_diario);   
    $("#sueldo_hora").val(e.data.sueldo_hora);

    $("#fecha_inicio").val(format_d_m_a(e.data.fecha_inicio));
    $("#fecha_fin").val(format_d_m_a(e.data.fecha_fin)); 
    $("#cantidad_dias").val(e.data.cantidad_dias);
  }).fail( function(e) { ver_errores(e); } );
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
    
        if (tabla) { tabla.ajax.reload(null, false); } 
        if (tabla) { tabla_secundaria.ajax.reload(null, false); }
      }).fail( function(e) { ver_errores(e); } );   
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

        if (tabla) { tabla.ajax.reload(null, false); } 
        if (tabla) { tabla_secundaria.ajax.reload(null, false); }
      }).fail( function(e) { ver_errores(e); } );
      
    }
  });      
}

function calcular_dias_trabajo() {
  var fecha_i = $('#fecha_inicio').val();
  var fecha_f = $('#fecha_fin').val();

  //console.log(fecha_i, fecha_f);

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

  $("#trabajador").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_trabajador").on('change', function() { $(this).trigger('blur'); });
  $("#cargo").on('change', function() { $(this).trigger('blur'); });

  $("#form-trabajador-proyecto").validate({
    rules: {
      trabajador:     { required: true},
      tipo_trabajador:{ required: true},
      cargo:          { required: true},
      desempenio:     { minlength: 4, maxlength: 100},
      sueldo_mensual: { required: true, minlength: 1},
      sueldo_diario:  { required: true, minlength: 1},
      sueldo_hora:    { required: true, minlength: 1},      
      // terms: { required: true },
    },
    messages: {
      trabajador:     { required: "Campo requerido.", },
      tipo_trabajador:{ required: "Campo requerido.", },
      cargo:          { required: "Campo requerido.", },
      desempenio:     { minlength: "MÍNIMO 4 letras.", maxlength: "MÁXIMO 100 letras.", },
      sueldo_mensual: { required: "Campo requerido.", },
      sueldo_diario:  { required: "Campo requerido.", },
      sueldo_hora:    { required: "Campo requerido.", },
    },
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
      if ($("#trabajador").select2("val") == null && $("#trabajador_old").val() == "") {         
        $("#trabajador_validar").show(); //console.log($("#trabajador").select2("val") + ", "+ $("#trabajador_old").val());
      } else {
        $("#trabajador_validar").hide();
      }

    },
    submitHandler: function (e) {     
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página  
      guardaryeditar(e);
    },
  });

  $("#trabajador").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_trabajador").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#cargo").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});
