var tabla, tabla_secundaria;

var cant_banco_multimple = 1; cant_sueldo_multimple = 1;

var iddesempeño_r='';

//Función que se ejecuta al inicio
function init() {  

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lTrabajador").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  tbla_principal( localStorage.getItem('nube_idproyecto') ); 
  tbla_secundaria( localStorage.getItem('nube_idproyecto') ); 
  
  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2(`../ajax/ajax_general.php?op=select2TrabajadorPorProyecto&id_proyecto=${localStorage.getItem('nube_idproyecto')}`, '#trabajador', null);

  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_0', null);

  lista_select2("../ajax/ajax_general.php?op=select2TipoTrabajador", '#tipo_all', null);
  lista_select2("../ajax/ajax_general.php?op=select2OcupacionTrabajador", '#ocupacion_all', null);
  lista_select2("../ajax/ajax_general.php?op=select2DesempenioTrabajador", '#desempenio_all', null); 

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_all_trabajador").on("click", function (e) {  $("#submit-form-all-trabajador").submit(); }); 

  $("#guardar_registro_orden_trabajador").on("click", function (e) {  $("#submit-form-orden-trabajador").submit(); }); 
  $("#form-orden-trabajador").on("submit",function(e) { guardar_y_editar_orden_trabajador(e);	});

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#trabajador").select2({ templateResult: templateTrabajador, theme: "bootstrap4", placeholder: "Selecione trabajador", allowClear: true, });
  $("#desempenio").select2({ theme: "bootstrap4", placeholder: "Selecione desempeño", allowClear: true, });

  $("#banco_0").select2({templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione banco", allowClear: true, });
  $("#tipo_all").select2({ theme: "bootstrap4", placeholder: "Selecione tipo", allowClear: true, });
  $("#ocupacion_all").select2({ theme: "bootstrap4", placeholder: "Selecione desempeño", allowClear: true, });
  $("#desempenio_all").select2({ theme: "bootstrap4", placeholder: "Selecione desempeño", allowClear: true, });
  $("#talla_ropa_all").select2({ theme: "bootstrap4", placeholder: "Selecione Talla", allowClear: true, });
  
  // ══════════════════════════════════════ INITIALIZE datetimepicker ══════════════════════════════════════

  $('#fecha_inicio').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
  $('#fecha_inicio').datetimepicker({ locale: 'es',  /* format: 'L',*/  format: 'DD-MM-YYYY', daysOfWeekDisabled: [6],  /*defaultDate: "", */ });

  $('#fecha_fin').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
  $('#fecha_fin').datetimepicker({ locale: 'es', /*format: 'L',*/ format: 'DD-MM-YYYY', daysOfWeekDisabled: [6], /*defaultDate: "",*/ });
  
  $('#nacimiento_all').datetimepicker({ locale: 'es', /*format: 'L',*/ format: 'DD-MM-YYYY', /*defaultDate: "",*/ });
  //$('#nacimiento_all').datepicker({ format: "dd-mm-yyyy", language: "es", autoclose: true, endDate: moment().format('DD/MM/YYYY'), clearBtn: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $('.sueldo_mensual_0').number( true, 3 );
  $('.sueldo_semanal_0').number( true, 3 );
  $('.sueldo_diario_0').number( true, 3 );
  $('.sueldo_hora_0').number( true, 3 );
  
  $("[data-mask]").inputmask();  
}

// click input group para habilitar: datepiker
// $('.click-btn-nacimiento_all').on('click', function (e) {$('#nacimiento_all').focus().select(); });

function templateBanco (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/banco/logo/${state.title}`: '../dist/docs/banco/logo/logo-sin-banco.svg'; 
  var onerror = `onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
}

function templateTrabajador(state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/all_trabajador/perfil/${state.title}`: '../dist/svg/user_default.svg'; 
  var onerror = `onerror="this.src='../dist/svg/user_default.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
}

function show_hide_form(flag) {

  // tabla principal
	if (flag == 1)	{		
    $('.btn-agregar-trabajador').show();
		$("#mostrar-tabla").show();
    $("#mostrar-form").hide();
	}	else	{
    $('.btn-agregar-trabajador').hide();
    // formulario		    
		$("#mostrar-tabla").hide();
    $("#mostrar-form").show();    
	}
}

function trabajador_no_usado() {
  lista_select2(`../ajax/ajax_general.php?op=select2TrabajadorPorProyecto&id_proyecto=${localStorage.getItem('nube_idproyecto')}`, '#trabajador', null);
}

//Función limpiar
function limpiar_form_trabajador() {  

  cant_sueldo_multimple = 1;
  iddesempeño_r = null;

  var fecha_incial_proyecto = "", fecha_final_proyecto = "" ;  

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

  $("#tipo_trabajador").html("Selecione un trabajador");
  $("#ocupacion").html("Selecione un trabajador");
  $("#desempenio").val("");  

  $(".sueldo_mensual_0").val("");  
  $(".sueldo_semanal_0").val("");  
  $(".sueldo_diario_0").val("");   
  $(".sueldo_hora_0").val("");
  $("#lista_sueldo").html("");

  $("#fecha_inicio").val(fecha_incial_proyecto);  $("#fecha_fin").val(fecha_final_proyecto); $('#cantidad_dias').val('')
  $('#cantidad_dias').removeClass('input-no-valido input-valido');
  $(`.fecha_inicial`).attr({ "min" : format_a_m_d(fecha_incial_proyecto),"max" : format_a_m_d(fecha_final_proyecto) });
  $(`.fecha_final`).attr({ "min" : format_a_m_d(fecha_incial_proyecto),"max" : format_a_m_d(fecha_final_proyecto) });

  $(`.fecha_desde_0`).rules("add", {
    required: true,   
    messages: {required: "Campo requerido", min: "Fecha minima {0}",  max: "Fecha maxima {0}"  }
  });

  $(`.fecha_hasta_0`).rules("add", {
    required: true,    
    messages: {required: "Campo requerido", min: "Fecha minima {0}",  max: "Fecha maxima {0}"  }
  });

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
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,8,9,10,11,12,13,4,5,14,15,16,17,18], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,8,9,10,11,12,13,4,5,14,15,16,17,18], } }, 
      { extend: 'pdfHtml5', footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,8,9,10,11,12,13,4,5,14,15,16,17,18], } }
    ],
    ajax:{
      url: `../ajax/trabajador_por_proyecto.php?op=tbla_principal&nube_idproyecto=${nube_idproyecto}&estado=1`,
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
      //if (data[5] != '') { $("td", row).eq(5).addClass("text-right" ); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [ 
      // { targets: [5], render: $.fn.dataTable.render.moment('YYYY-MM-DD', `DD/MM/YYYY ${this.data}`) , },     
      //{ targets: [8,9,10], render: $.fn.dataTable.render.number(',', '.', 2) },
      { targets: [8,9,10,11,12,13,14,15,16,17,18], visible: false, searchable: false, }, 
    ]
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
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,11,12,13,14,4,5,6,7,8,9,15,16], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,11,12,13,14,4,5,6,7,8,9,15,16], } }, 
      { extend: 'pdfHtml5', footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,11,12,13,14,4,5,6,7,8,9,15,16], } }
    ],
    ajax:{
      url: `../ajax/trabajador_por_proyecto.php?op=tbla_principal&nube_idproyecto=${nube_idproyecto}&estado=0`,
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
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [ 
      { targets: [6], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      //{ targets: [8,9,10], render: $.fn.dataTable.render.number(',', '.', 2) },
      { targets: [10,11,12,13,14,15], visible: false, searchable: false, }, 
    ]
  }).DataTable();
}

//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-trabajador-proyecto")[0]);

  $.ajax({
    url: "../ajax/trabajador_por_proyecto.php?op=guardaryeditar",
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
          lista_select2(`../ajax/ajax_general.php?op=select2TrabajadorPorProyecto&id_proyecto=${localStorage.getItem('nube_idproyecto')}`, '#trabajador', null);
                   
          show_hide_form(1);
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }       

      $("#guardar_registro_trabajador").html('Guardar Cambios').removeClass('disabled');
      
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_trabajador").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_trabajador").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_trabajador").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
      $("#barra_progress_trabajador_div").show();
    },
    complete: function () {
      $("#barra_progress_trabajador").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
      $("#barra_progress_trabajador_div").hide();
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function verdatos(idtrabajador){

  console.log('id_verdatos'+idtrabajador);  
  
  $('#datostrabajador').html(`<div class="row" > <div class="col-lg-12 text-center"> <i class="fas fa-spinner fa-pulse fa-6x"></i><br/> <br/> <h4>Cargando...</h4> </div> </div>`);

  var verdatos='';
  var imagen_perfil =''; btn_imagen_perfil=''; 

  $("#modal-ver-trabajador").modal("show")

  $.post("../ajax/trabajador_por_proyecto.php?op=ver_datos_trabajador", { idtrabajador_por_proyecto: idtrabajador }, function (e, status) {

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
                    <td>${e.data.trabajador.nombre_tipo_trabajador}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Desempeño</th>
                    <td>${e.data.trabajador.nombre_ocupacion}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Desempenio</th>
                    <td>${e.data.html_desempenio}</td>
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

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_form_trabajador();
  show_hide_form(2);

  $.post("../ajax/trabajador_por_proyecto.php?op=mostrar", { idtrabajador_por_proyecto: idtrabajador }, function (e, status) {

    e = JSON.parse(e); console.log(e); 
    if (e.status == true) {
      lista_select2(`../ajax/ajax_general.php?op=select2Trabajador`,'#trabajador', e.data.idtrabajador, null);

      $("#idtrabajador_por_proyecto").val(e.data.idtrabajador_por_proyecto);  
      
      iddesempeño_r = e.data.iddesempenio;

      $("#ocupacion").html(e.data.nombre_ocupacion);    
      $("#tipo_trabajador").html(e.data.nombre_tipo);  

      $("#sueldo_mensual").val(e.data.sueldo_mensual);   
      $("#sueldo_diario").val(e.data.sueldo_diario);   
      $("#sueldo_hora").val(e.data.sueldo_hora);

      $("#fecha_inicio").val(format_d_m_a(e.data.fecha_inicio));
      $("#fecha_fin").val(format_d_m_a(e.data.fecha_fin)); 
      $("#cantidad_dias").val(e.data.cantidad_dias);
    
      e.data.detalle_sueldo.forEach(function(val, index){         
        validar_fecha_rango( e.data.fecha_inicio, e.data.fecha_fin)
        if ( index > 0 ) { 
          add_sueldo();
          $(`.sueldo_mensual_${index}`).val(val.sueldo_mensual);  
          $(`.sueldo_semanal_${index}`).val(val.sueldo_semanal);  
          $(`.sueldo_diario_${index}`).val(val.sueldo_diario);   
          $(`.sueldo_hora_${index}`).val(val.sueldo_hora);
          $(`.fecha_desde_${index}`).val(val.fecha_desde);   
          $(`.fecha_hasta_${index}`).val(val.fecha_hasta);
          if (val.sueldo_actual == '1') { $(`#sueldo_seleccionado_${index}`).prop('checked', true); replicar_sueldo_actual(index) } 
        } else {          
          $(".sueldo_mensual_0").val(val.sueldo_mensual);  
          $(".sueldo_semanal_0").val(val.sueldo_semanal);  
          $(".sueldo_diario_0").val(val.sueldo_diario);   
          $(".sueldo_hora_0").val(val.sueldo_hora);
          $(".fecha_desde_0").val(val.fecha_desde);   
          $(".fecha_hasta_0").val(val.fecha_hasta);
          if (val.sueldo_actual == '1') { $(`#sueldo_seleccionado_${index}`).prop('checked', true); replicar_sueldo_actual(0) } 
        }              
          
      }); 

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }     
  }).fail( function(e) { ver_errores(e); } );

}

//captura id del trabajador
function capture_idtrabajador() { 

  var idtrabajador= $("#trabajador").select2("val");
  lista_select2(`../ajax/ajax_general.php?op=select2DesempenioPorTrabajdor&id_trabajador=${idtrabajador}`, '#desempenio', iddesempeño_r, '#desempenio_charge');

  $("#tipo_trabajador").html(`<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>`); 
  $("#ocupacion").html(`<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>`); 
  
  if (idtrabajador == null || idtrabajador == '' ) { 
    $("#tipo_trabajador").html("Selecione un trabajador");   $("#ocupacion").html("Selecione un trabajador");
  }else{     
      
    $.post("../ajax/trabajador_por_proyecto.php?op=m_datos_trabajador", { idtrabajador: idtrabajador }, function (e, status) {

      e = JSON.parse(e); // console.log(e);   
      if (e.status == true) {
        $("#tipo_trabajador").html(e.data.trabajador.nombre_tipo);
        $("#ocupacion").html(e.data.trabajador.nombre_ocupacion);
      } else {
        ver_errores(e);
      }
    }).fail( function(e) { ver_errores(e); } );
  }    

  if ($('#trabajador').select2("val") == null || $('#trabajador').select2("val") == '') { 
    $('.btn-editar-trabajador').addClass('disabled').attr('data-original-title','Seleciona un trabajador');
  } else {     
    var name_trabajador = $('#trabajador').select2('data')[0].text;
    $('.btn-editar-trabajador').removeClass('disabled').attr('data-original-title',`Editar: ${recorte_text(name_trabajador, 15)}`);     
  }
  $('[data-toggle="tooltip"]').tooltip();  
}

//Función para desactivar registros
function eliminar_trabajador_proyecto(idtrabajador_por_proyecto, nombre) {
  crud_eliminar_papelera(
    "../ajax/trabajador_por_proyecto.php?op=desactivar",
    "../ajax/trabajador_por_proyecto.php?op=eliminar", 
    idtrabajador_por_proyecto, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  tabla.ajax.reload(null, false);tabla_secundaria.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );

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

  validar_fecha_rango(format_a_m_d( fecha_i), format_a_m_d(fecha_f));
}

// .....::::::::::::::::::::::::::::::::::::: S U E L D O   M U L T I P L E  :::::::::::::::::::::::::::::::::::::::..

function add_sueldo() {
  // FECHAS DEFINIDAS DEL CONTRATO
  var fecha_inicio = format_a_m_d($("#fecha_inicio").val());  
  var fecha_fin = format_a_m_d($("#fecha_fin").val());

  $('#lista_sueldo').append(` 
    <!-- Sueldo(Semanal) -->
    <div class="col-lg-3 delete_sueldo_multiple_${cant_sueldo_multimple}">
      <div class="form-group">
        <label for="sueldo_semanal">Sueldo(Semanal)</label>
        <input type="text" step="any" name="sueldo_semanal[]" class="form-control sueldo_semanal_${cant_sueldo_multimple}" id="sueldo_semanal" readonly  />
        <input type="hidden" step="any" name="sueldo_mensual[]" class="form-control sueldo_mensual_${cant_sueldo_multimple}" readonly />
      </div>
    </div>

    <!-- Sueldo(Diario) -->
    <div class="col-lg-2 delete_sueldo_multiple_${cant_sueldo_multimple}">
      <div class="form-group">
        <label for="sueldo_diario">Sueldo(Diario)</label>
        <input type="text" step="any" name="sueldo_diario[]" class="form-control sueldo_diario_${cant_sueldo_multimple}" id="sueldo_diario" onchange="salary_semanal(${cant_sueldo_multimple});" onkeyup="salary_semanal(${cant_sueldo_multimple});" onclick="this.select();" />
      </div>
    </div>

    <!-- Sueldo(Hora) -->
    <div class="col-lg-2 delete_sueldo_multiple_${cant_sueldo_multimple}">
      <div class="form-group">
        <label for="sueldo_hora">Sueldo(8 Hora)</label>
        <input type="text" step="any" name="sueldo_hora[]" class="form-control sueldo_hora_${cant_sueldo_multimple}" id="sueldo_hora" readonly />
      </div>
    </div>

    <!-- Fecha inicial fecha_inicial fecha_final-->
    <div class="col-lg-2 delete_sueldo_multiple_${cant_sueldo_multimple}"">
      <div class="form-group">
        <label for="fecha_desde">Desde</label>
        <input type="date" name="fecha_desde[]" class="form-control fecha_inicial fecha_desde_${cant_sueldo_multimple}" min="${fecha_inicio}" max="${fecha_fin}" placeholder="Fecha" />
      </div>
    </div>

    <!-- Fecha final -->
    <div class="col-lg-2 delete_sueldo_multiple_${cant_sueldo_multimple}"">
      <div class="form-group">
        <label for="fecha_hasta">Hasta</label>
        <input type="date" name="fecha_hasta[]" class="form-control fecha_final fecha_hasta_${cant_sueldo_multimple}" min="${fecha_inicio}" max="${fecha_fin}" placeholder="Fecha" />
      </div>
    </div>

    <div class="col-12 col-sm-12 col-md-6 col-lg-1 delete_sueldo_multiple_${cant_sueldo_multimple}">
      <div class="form-group mb-2">
        <div class="custom-control custom-radio">
          <input class="custom-control-input custom-control-input-danger" type="radio" id="sueldo_seleccionado_${cant_sueldo_multimple}" name="sueldo_seleccionado" value="${cant_sueldo_multimple}" onclick="replicar_sueldo_actual(${cant_sueldo_multimple});">
          <label for="sueldo_seleccionado_${cant_sueldo_multimple}" class="custom-control-label">Usar</label>
          <input type="hidden" name="sueldo_actual[]" class="sueldo_actual" id="sueldo_actual_${cant_sueldo_multimple}" value="0" >
        </div>
      </div>
      <button type="button" class="btn bg-gradient-danger btn-sm"  onclick="remove_sueldo(${cant_sueldo_multimple});"><i class="far fa-trash-alt"></i></button>      
  </div>`);

  $(`.sueldo_mensual_${cant_sueldo_multimple}`).number( true, 3 );
  $(`.sueldo_semanal_${cant_sueldo_multimple}`).number( true, 3 );
  $(`.sueldo_diario_${cant_sueldo_multimple}`).number( true, 3 );
  $(`.sueldo_hora_${cant_sueldo_multimple}`).number( true, 3 );

  $(`.fecha_inicial.fecha_desde_${cant_sueldo_multimple}`).rules("add", {
    required: true,    
    messages: {required: "Campo requerido", min: "Fecha minima {0}",  max: "Fecha maxima {0}"  }
  });

  $(`.fecha_final.fecha_hasta_${cant_sueldo_multimple}`).rules("add", {
    required: true,    
    messages: {required: "Campo requerido", min: "Fecha minima {0}",  max: "Fecha maxima {0}"  }
  });

  $('[data-toggle="tooltip"]').tooltip();
  cant_sueldo_multimple++;    
}

function remove_sueldo(id) { $(`.delete_sueldo_multiple_${id}`).remove(); }

function salary_semanal(id){
  console.log('---id  '+id);
  var val_diario  = $(`.sueldo_diario_${id}`).val();

  var val_mensual = (val_diario*30).toFixed(3);
  var val_semanal = (val_diario*6).toFixed(3);
  var val_horas   = (val_diario/8).toFixed(3);

  $(`.sueldo_mensual_${id}`).val(val_mensual);
  $(`.sueldo_semanal_${id}`).val(val_semanal);
  $(`.sueldo_hora_${id}`).val(val_horas);
}

function validar_fecha_rango( fecha_inicio, fecha_fin) {
  $(`.fecha_inicial`).attr({ "min" : fecha_inicio,"max" : fecha_fin });
  $(`.fecha_final`).attr({ "min" : fecha_inicio,"max" : fecha_fin });  
}

function replicar_sueldo_actual(id) { console.log('entramos a replicar');
  $(`.sueldo_actual`).val(0);
  $(`#sueldo_actual_${id}`).val(1);
}

// .....::::::::::::::::::::::::::::::::::::: T R A B A J A D O R  :::::::::::::::::::::::::::::::::::::::..
// abrimos el navegador de archivos
$("#foto1_i").click(function() { $('#foto1').trigger('click'); });
$("#foto1").change(function(e) { addImage(e,$("#foto1").attr("id")) });

$("#foto2_i").click(function() { $('#foto2').trigger('click'); });
$("#foto2").change(function(e) { addImage(e,$("#foto2").attr("id")) });

$("#foto3_i").click(function() { $('#foto3').trigger('click'); });
$("#foto3").change(function(e) { addImage(e,$("#foto3").attr("id")) });

$("#doc4_i").click(function() {  $('#doc4').trigger('click'); });
$("#doc4").change(function(e) {  addImageApplication(e,$("#doc4").attr("id")) });

$("#doc5_i").click(function() {  $('#doc5').trigger('click'); });
$("#doc5").change(function(e) {  addImageApplication(e,$("#doc5").attr("id")) });

function foto1_eliminar() {
	$("#foto1").val(""); $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png"); $("#foto1_nombre").html("");
}

function foto2_eliminar() {
	$("#foto2").val(""); $("#foto2_i").attr("src", "../dist/img/default/dni_anverso.webp"); $("#foto2_nombre").html("");
}

function foto3_eliminar() {
	$("#foto3").val(""); $("#foto3_i").attr("src", "../dist/img/default/dni_reverso.webp");	$("#foto3_nombre").html("");
}

// Eliminamos el doc 4
function doc4_eliminar() {
	$("#doc4").val("");	$("#doc4_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >'); $("#doc4_nombre").html("");
}

// Eliminamos el doc 5
function doc5_eliminar() {
	$("#doc5").val("");	$("#doc5_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');	$("#doc5_nombre").html("");
}

//Función limpiar
function limpiar_form_all_trabajador() {
  cant_banco_multimple = 1;
  $("#guardar_registro_all_trabajador").html('Guardar Cambios').removeClass('disabled');

  $(".modal-title-all-trabajador").html('Agregar All-Trabajador');

  $("#idtrabajador_all").val("");
  $("#tipo_documento_all option[value='DNI']").attr("selected", true);
  $("#nombre_all").val(""); 
  $("#num_documento_all").val(""); 
  $("#direccion_all").val(""); 
  $("#telefono_all").val(""); 
  $("#email_all").val(""); 
  $("#nacimiento_all").val("");
  $("#input_edad").val("0");  $("#span_edad").html("0");    
  $("#cta_bancaria").val("");  
  $("#cci").val("");  
  $("#banco_0").val("").trigger("change"); $("#lista_bancos").html("");

  $("#tipo_all").val("").trigger("change");
  $("#ocupacion_all").val("").trigger("change");
  $("#desempenio_all").val("").trigger("change");
  $("#talla_ropa_all").val("").trigger("change");
  $("#talla_zapato").val("").trigger("change");
  $("#titular_cuenta_all").val("");

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

  $("#doc4").val("");
  $("#doc_old_4").val("");
  $("#doc4_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
  $("#doc4_nombre").html('');
  
  $("#doc5").val("");
  $("#doc_old_5").val("");
  $("#doc5_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
  $("#doc5_nombre").html('');
  
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function mostrar_editar_trabajador() {
  limpiar_form_all_trabajador();
  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();

  $(".modal-title-all-trabajador").html('Editar All-Trabajador');

  $('#modal-agregar-all-trabajador').modal('show');
  $(".tooltip").remove();

  $.post("../ajax/trabajador_por_proyecto.php?op=mostrar_editar_trabajador", { 'idtrabajador': $('#trabajador').select2("val") }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {       

      $("#idtrabajador_all").val(e.data.trabajador.idtrabajador);
      $("#tipo_documento_all option[value='"+e.data.trabajador.tipo_documento+"']").attr("selected", true);
      $("#nombre_all").val(e.data.trabajador.nombres);
      $("#num_documento_all").val(e.data.trabajador.numero_documento);
      $("#direccion_all").val(e.data.trabajador.direccion);
      $("#telefono_all").val(e.data.trabajador.telefono);
      $("#email_all").val(e.data.trabajador.email);
      $("#nacimiento_all").val(format_d_m_a(e.data.trabajador.fecha_nacimiento));             
      $("#titular_cuenta_all").val(e.data.trabajador.titular_cuenta);
      $("#ruc_all").val(e.data.trabajador.ruc); 
      $("#talla_ropa_all").val(e.data.trabajador.talla_ropa).trigger('change');
      $("#talla_zapato_all").val(e.data.trabajador.talla_zapato); 
      

      $("#tipo_all").val(e.data.trabajador.idtipo_trabajador).trigger('change');
      $("#ocupacion_all").val(e.data.trabajador.idocupacion).trigger('change');
      $("#desempenio_all").val(e.data.detalle_desempenio).trigger('change');
      
      e.data.bancos.forEach(function(valor, index){ 
        
        if ( index > 0 ) { 
          add_bancos(valor.idbancos); 
          $(`.cta_bancaria_${index}`).val(valor.cuenta_bancaria);
          $(`.cci_${index}`).val(valor.cci);
          if (valor.banco_seleccionado == '1') { $(`#banco_seleccionado_${index}`).prop('checked', true); } 
          console.log('crear - banco: ' + valor.idbancos + ' index: '+ index + ' select: '+ valor.banco_seleccionado);
        } else {
          $(`.cta_bancaria_${index}`).val(valor.cuenta_bancaria);
          $(`.cci_${index}`).val(valor.cci);
          $(`#banco_${index}`).val(valor.idbancos).trigger("change");
          if (valor.banco_seleccionado == '1') { $(`#banco_seleccionado_${index}`).prop('checked', true); } 
          //console.log('editar - banco: ' + valor.idbancos + ' index: '+ index + ' select: '+ valor.banco_seleccionado);
        }         
      }); 
      
      if (e.data.trabajador.imagen_perfil!="") {
        $("#foto1_i").attr("src", "../dist/docs/all_trabajador/perfil/" + e.data.trabajador.imagen_perfil);
        $("#foto1_actual").val(e.data.trabajador.imagen_perfil);
      }

      if (e.data.trabajador.imagen_dni_anverso != "") {
        $("#foto2_i").attr("src", "../dist/docs/all_trabajador/dni_anverso/" + e.data.trabajador.imagen_dni_anverso);
        $("#foto2_actual").val(e.data.trabajador.imagen_dni_anverso);
      }

      if (e.data.trabajador.imagen_dni_reverso != "") {
        $("#foto3_i").attr("src", "../dist/docs/all_trabajador/dni_reverso/" + e.data.trabajador.imagen_dni_reverso);
        $("#foto3_actual").val(e.data.trabajador.imagen_dni_reverso);
      }

      //validamoos DOC-4
      if (e.data.trabajador.cv_documentado != "" ) {
        $("#doc_old_4").val(e.data.trabajador.cv_documentado);
        $("#doc4_nombre").html('CV documentado.' + extrae_extencion(e.data.trabajador.cv_documentado));
        var doc_html = doc_view_extencion(e.data.trabajador.cv_documentado, 'all_trabajador', 'cv_documentado', '100%', '210' );
        $("#doc4_ver").html(doc_html);         
      } else {
        $("#doc4_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');
        $("#doc4_nombre").html('');
        $("#doc_old_4").val("");
      }

      //validamoos DOC-5
      if (e.data.trabajador.cv_no_documentado != "" ) {
        $("#doc_old_5").val(e.data.trabajador.cv_no_documentado);
        $("#doc5_nombre").html('CV no documentado.' + extrae_extencion(e.data.trabajador.cv_no_documentado));
        var doc_html = doc_view_extencion(e.data.trabajador.cv_no_documentado, 'all_trabajador', 'cv_no_documentado', '100%', '210' );
        $("#doc5_ver").html(doc_html);        
      } else {
        $("#doc5_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');
        $("#doc5_nombre").html('');
        $("#doc_old_5").val("");
      }

      calcular_edad('#nacimiento_all','#input_edad','#span_edad');      

      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();

    } else {
      ver_errores(e);
    }   
  }).fail( function(e) { ver_errores(e); });
}

//Función para guardar o editar
function guardar_y_editar_all_trabajador(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-all-trabajador")[0]);

  $.ajax({
    url: "../ajax/trabajador_por_proyecto.php?op=guardar_y_editar_all_trabajador",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {	
          Swal.fire("Correcto!", "All-Trabajador guardado correctamente", "success");
          lista_select2("../ajax/ajax_general.php?op=select2Trabajador", '#trabajador', e.data);          
          $("#modal-agregar-all-trabajador").modal("hide"); 
          lista_select2(`../ajax/ajax_general.php?op=select2DesempenioPorTrabajdor&id_trabajador=${e.data}`, '#desempenio', $(`#desempenio`).select2("val"));
          cant_banco_multimple = 1; 

        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_all_trabajador").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});
          $("#barra_progress").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_all_trabajador").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  });
      $("#barra_progress").text("0%");
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// .....::::::::::::::::::::::::::::::::::::: ORDEN TRABAJADOR  :::::::::::::::::::::::::::::::::::::::..
function ver_lista_orden() {
  $('#modal-order-trabajador').modal('show');
  $('#html_order_trabajador_1').html(`<tr><td colspan="11"><div class="row" ><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-4x"></i><br/><br/><h4>Cargando...</h4></div></div></td></tr>`)
  $('#html_order_trabajador_2').html(`<tr><td colspan="11"><div class="row" ><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-4x"></i><br/><br/><h4>Cargando...</h4></div></div></td></tr>`)
  
  $.post("../ajax/trabajador_por_proyecto.php?op=ver_lista_orden", {'idproyecto': localStorage.getItem('nube_idproyecto')},  function (e, status) {
    e = JSON.parse(e);  console.log(e);
    if (e.status == true) {
        
      var html_data_td_1 ='' ; var html_data_td_2 ='' ;       
      var i_2 = parseInt(e.data.length/2); console.log(i_2);        

      e.data.forEach((val, key) => {   
        
        var imagen = (val.imagen_perfil == '' ? '../dist/svg/user_default.svg' : `../dist/docs/all_trabajador/perfil/${val.imagen_perfil}`) ;        
        
        if ((key + 1) <= i_2) {
          html_data_td_1 = html_data_td_1.concat(`<tr class="cursor-pointer"> 
            <td class="py-1 text-center">${key + 1}</td> 
            <td class="py-1">
              <div class="user-block">
                <img class="img-circle cursor-pointer" src="../dist/docs/all_trabajador/perfil/${val.imagen_perfil}" alt="User Image" onerror="this.src='../dist/svg/user_default.svg'" onclick="ver_perfil('${ imagen }', '${encodeHtml(val.trabajador)}');">
                <span class="username"><p class="text-primary m-b-02rem" >  ${val.trabajador}</p></span>
                <span class="description">${val.nombre_tipo} | ${val.tipo_documento}: ${val.numero_documento}</span>
              </div>
              <input type="hidden" name="td_order_trabajador[]" value="${val.idtrabajador_por_proyecto}">
            </td>              
          </tr>`);            
        } else {
          html_data_td_2 = html_data_td_2.concat(`<tr class="cursor-pointer"> 
            <td class="py-1 text-center">${key + 1}</td> 
            <td class="py-1">
              <div class="user-block">
                <img class="img-circle cursor-pointer" src="../dist/docs/all_trabajador/perfil/${val.imagen_perfil}" alt="User Image" onerror="this.src='../dist/svg/user_default.svg'" onclick="ver_perfil('${ imagen }', '${encodeHtml(val.trabajador)}');">
                <span class="username"><p class="text-primary m-b-02rem" >  ${val.trabajador}</p></span>
                <span class="description">${val.nombre_tipo} | ${val.tipo_documento}: ${val.numero_documento}</span>
              </div>
              <input type="hidden" name="td_order_trabajador[]" value="${val.idtrabajador_por_proyecto}">
            </td>              
          </tr>`);
        }                   
                  
      });
      $('#html_order_trabajador_1').html(`${html_data_td_1} `);
      $('#html_order_trabajador_2').html(`${html_data_td_2} `);
      $(".orden_trabajador_1").sortable({connectWith: '#html_order_trabajador_2'}).disableSelection();
      $(".orden_trabajador_2").sortable({connectWith: '#html_order_trabajador_1'}).disableSelection();
    }else{
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función para guardar o editar
function guardar_y_editar_orden_trabajador(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-orden-trabajador")[0]);

  $.ajax({
    url: "../ajax/trabajador_por_proyecto.php?op=guardar_y_editar_orden_trabajador",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {	          
          Swal.fire("Correcto!", "Orden guardado correctamente", "success");          
          $("#modal-order-trabajador").modal("hide"); 
          if (tabla) { tabla.ajax.reload(null, false); } 
          if (tabla) { tabla_secundaria.ajax.reload(null, false); }
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_all_trabajador").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});
          $("#barra_progress").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_all_trabajador").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  });
      $("#barra_progress").text("0%");
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// .....::::::::::::::::::::::::::::::::::::: B A N C O   M U L T I P L E  :::::::::::::::::::::::::::::::::::::::..
// damos formato a: Cta, CCI
function formato_banco(id) {

  if ($(`#banco_${id}`).select2("val") == null || $(`#banco_${id}`).select2("val") == "" || $(`#banco_${id}`).select2("val") == '1') {
    $(`#banco_array_${id}`).val(1);
    $(`.cta_bancaria_${id}`).prop("readonly",true);   $(`.cci_${id}`).prop("readonly",true); 
  } else {
    
    $(`.${id}_chargue-format-1`).html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>'); $(`.${id}_chargue-format-2`).html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');

    $(`#banco_array_${id}`).val($(`#banco_${id}`).select2("val"));

    $(`.cta_bancaria_${id}`).prop("readonly",false);   $(`.cci_${id}`).prop("readonly",false);

    $.post("../ajax/ajax_general.php?op=formato_banco", { idbanco: $(`#banco_${id}`).select2("val") }, function (data, status) {

      data = JSON.parse(data);  //console.log(data); 

      $(`.${id}_chargue-format-1`).html('Cuenta Bancaria'); $(`.${id}_chargue-format-2`).html('CCI');

      var format_cta = decifrar_format_banco(data.data.formato_cta); var format_cci = decifrar_format_banco(data.data.formato_cci);

      $(`.cta_bancaria_${id}`).inputmask(`${format_cta}`);

      $(`.cci_${id}`).inputmask(`${format_cci}`);
    }).fail( function(e) { ver_errores(e); } );  
  }  
}

function add_bancos(id_select_banco = null) {
  $('#lista_bancos').append(`   
    <!-- banco -->
    <div class="col-12 col-sm-12 col-md-6 col-lg-3 delete_multiple_${cant_banco_multimple}">
      <div class="form-group">
        <label for="banco">Banco</label>
        <select name="banco_${cant_banco_multimple}" id="banco_${cant_banco_multimple}" class="form-control select2 banco_${cant_banco_multimple}" style="width: 100%;" onchange="formato_banco(${cant_banco_multimple});">
          <!-- Aqui listamos los bancos -->
        </select>
        <input type="hidden" name="banco_array[]" id="banco_array_${cant_banco_multimple}">
      </div>
    </div>

    <!-- Cuenta bancaria -->
    <div class="col-12 col-sm-12 col-md-6 col-lg-4 delete_multiple_${cant_banco_multimple}">
      <div class="form-group">
        <label for="cta_bancaria" class="${cant_banco_multimple}_chargue-format-1">Cuenta Bancaria</label>
        <input type="text" name="cta_bancaria[]" class="form-control cta_bancaria_${cant_banco_multimple}" id="cta_bancaria" placeholder="Cuenta Bancaria" data-inputmask="" data-mask />
      </div>
    </div>

    <!-- CCI -->
    <div class="col-12 col-sm-12 col-md-6 col-lg-4 delete_multiple_${cant_banco_multimple}">
      <div class="form-group">
        <label for="cta_bancaria" class="${cant_banco_multimple}_chargue-format-2">CCI</label>
        <input type="text" name="cci[]" class="form-control cci_${cant_banco_multimple}" id="cci" placeholder="CCI" data-inputmask="" data-mask />
      </div>
    </div>

    <div class="col-12 col-sm-12 col-md-6 col-lg-1 delete_multiple_${cant_banco_multimple}">
      <div class="form-group mb-2">
        <div class="custom-control custom-radio">
          <input class="custom-control-input custom-control-input-danger" type="radio" id="banco_seleccionado_${cant_banco_multimple}" name="banco_seleccionado" value="${cant_banco_multimple}">
          <label for="banco_seleccionado_${cant_banco_multimple}" class="custom-control-label">Usar</label>
        </div>
      </div>
      <button type="button" class="btn bg-gradient-danger btn-sm"  onclick="remove_bancos(${cant_banco_multimple});"><i class="far fa-trash-alt"></i></button>        
      
  </div>`);

  lista_select2("../ajax/ajax_general.php?op=select2Banco", `#banco_${cant_banco_multimple}`, id_select_banco);

  $(`#banco_${cant_banco_multimple}`).select2({templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione banco", allowClear: true, });
  
  $(`#banco_${cant_banco_multimple}`).on('change', function() { $(this).trigger('blur'); });
  $(`#banco_${cant_banco_multimple}`).rules('add', { required: true, messages: {  required: "Campo requerido" } });
  cant_banco_multimple++;
    
}

function remove_bancos(id) { $(`.delete_multiple_${id}`).remove(); }

init();

// .....::::::::::::::::::::::::::::::::::::: F O R M    V A L I D A T E  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  $("#trabajador").on('change', function() { $(this).trigger('blur'); });
  $("#desempenio").on('change', function() { $(this).trigger('blur'); });

  $("#banco_0").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_all").on('change', function() { $(this).trigger('blur'); });
  $("#ocupacion_all").on('change', function() { $(this).trigger('blur'); });
  $("#desempenio_all").on('change', function() { $(this).trigger('blur'); });
  $("#talla_ropa_all").on('change', function() { $(this).trigger('blur'); });

  $("#form-trabajador-proyecto").validate({
    rules: {
      trabajador:     { required: true},
      desempenio:     { required: true},
      sueldo_mensual: { required: true, minlength: 1},
      sueldo_diario:  { required: true, minlength: 1},
      sueldo_hora:    { required: true, },      
      // terms: { required: true },
    },
    messages: {
      trabajador:     { required: "Campo requerido.", },
      desempenio:     { required: "Campo requerido.",},
      sueldo_mensual: { required: "Campo requerido.", },
      sueldo_diario:  { required: "Campo requerido.", },
      sueldo_hora:    { required: "Campo requerido.", },
      'fecha_hasta[]': { min: "Fecha minima: {0}", max: "Fecha maxima: {0}", },
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
    },
    submitHandler: function (e) {     
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página  
      guardaryeditar(e);
    },
  });   

  $("#form-all-trabajador").validate({
    rules: {
      tipo_documento_all: { required: true },
      num_documento_all:  { required: true, minlength: 6, maxlength: 20 },
      nombre_all:         { required: true, minlength: 6, maxlength: 100 },
      email_all:          { email: true, minlength: 10, maxlength: 50 },
      direccion_all:      { minlength: 5, maxlength: 70 },
      telefono_all:       { minlength: 8 },
      cta_bancaria:       { minlength: 10,},
      banco_0:            { required: true},
      banco_seleccionado: { required: true},
      tipo_all:           { required: true},
      ocupacion_all:      { required: true},
      ocupacion_all:      { required: true},
      desempenio_all:     { minlength: 11, maxlength: 11},
    },
    messages: {
      tipo_documento_all: { required: "Campo requerido.", },
      num_documento_all:  { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre_all:         { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      email_all:          { required: "Campo requerido.", email: "Ingrese un coreo electronico válido.", minlength: "MÍNIMO 10 caracteres.", maxlength: "MÁXIMO 50 caracteres.", },
      direccion_all:      { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 70 caracteres.", },
      telefono_all:       { minlength: "MÍNIMO 8 caracteres.", },
      cta_bancaria:       { minlength: "MÍNIMO 10 caracteres.", },
      tipo_all:           { required: "Campo requerido.", },
      ocupacion_all:      { required: "Campo requerido.", },
      banco_0:            { required: "Campo requerido.", },
      banco_seleccionado: { required: "Requerido.", },
      ocupacion_all:      { required: true},
      desempenio_all:     { minlength: "MÍNIMO 11 caracteres.", maxlength: "MÁXIMO 11 caracteres.", },
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
    },
    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_y_editar_all_trabajador(e);

    },
  });

  $("#trabajador").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#desempenio").rules('add', { required: true, messages: {  required: "Campo requerido" } });

  $("#banco_0").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_all").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#ocupacion_all").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#desempenio_all").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function ver_perfil(file, nombre) {
  $('.modal-title-perfil-trabajador').html(nombre);
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#modal-ver-perfil-trabajador").modal("show");
  $('#html-perfil-trabajador').html(`<span class="jq_image_zoom"><img class="img-thumbnail" src="${file}" onerror="this.src='../dist/svg/user_default.svg';" alt="Perfil" width="100%"></span>`);
  $('.jq_image_zoom').zoom({ on:'grab' });
}