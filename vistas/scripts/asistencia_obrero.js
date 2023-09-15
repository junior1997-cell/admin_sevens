var tabla_principal, tabla_horas, tabla_qs;

var array_asistencia = []; var array_trabajador = [];

var array_sabatical_1 = []; var array_sabatical_2 = [];

var array_pago_contador = []; var array_agregar_horas = [];

var ids_q_asistencia_r = ''; f1_r = 0, f2_r = 0, i_r = 0, cant_dias_asistencia_r = 0; var estado_editar_asistencia = false;

var idtrabajador_por_proyecto_r = 0;

var n_f_i_p = localStorage.getItem('nube_fecha_inicial_proyecto');
var n_f_f_p = localStorage.getItem('nube_fecha_final_proyecto');

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lAsistencia").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));
  $("#idproyecto_horario").val(localStorage.getItem('nube_idproyecto'));

  tbla_principal(localStorage.getItem('nube_idproyecto'));
  listar_botones_q_s(localStorage.getItem('nube_idproyecto')); 
  mostrar_horario(); 

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_adicional_descuento").on("click", function (e) { $("#submit-form-adicional-descuento").submit(); });
  $("#guardar_registro_justificacion").on("click", function (e) { $("#submit-form-justificacion").submit(); });
  $("#guardar_registro_fechas_actividades").on("click", function (e) { $("#submit-form-fechas-actividades").submit(); });
  $("#guardar_registro_horario").on("click", function (e) { $("#submit-form-horario-proyecto").submit(); });

  $(".horas-multiples").on("click", function (e) { $("#form-horas-multiples").submit(); }); 
  $(".horas-por-dia-multiples").on("click", function (e) { $("#submit-form-horas-por-dia-multiples").submit(); });  

  // ══════════════════════════════════════ TIMEPIKER  ══════════════════════════════════════
  //Timepicker
  // $('#timepicker').datetimepicker({ /*format: 'LT',*/ format:'HH:mm ', lang:'ru' })

  $('#fecha_inicio_actividad').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
  $('#fecha_inicio_actividad').datepicker({ format: "dd-mm-yyyy", language: "es", autoclose: true, clearBtn: true, daysOfWeekDisabled: [6], weekStart: 0, orientation: "bottom auto", todayBtn: true });

  $('#fecha_fin_actividad').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
  $('#fecha_fin_actividad').datepicker({ format: "dd-mm-yyyy", language: "es", autoclose: true, clearBtn: true, daysOfWeekDisabled: [6], weekStart: 0, orientation: "bottom auto", todayBtn: true });

  $('#fecha_pago_obrero_f').select2({ theme: "bootstrap4", placeholder: "Selecione", allowClear: true });

  // Formato para telefono
  $("[data-mask]").inputmask();
  
}

// click input group para habilitar: datepiker
$('.click-btn-fecha-inicio-actividad').on('click', function (e) {$('#fecha_inicio_actividad').focus().select(); });
$('.click-btn-fecha-fin-actividad').on('click', function (e) {$('#fecha_fin_actividad').focus().select(); });

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {
	$("#doc1").val("");
	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
	$("#doc1_nombre").html("");
}

function mostrar_form_table(estados) {

  if (estados == 1 ) { // TABLA PRINCIPAL
    $("#btn-registrar").show();
    $("#btn-regresar").hide();
    $("#btn-editar").hide();
    $("#btn-guardar").hide();

    $(".btn_mostrar_hne").hide();
    $(".btn_mostrar_hn").hide();
    $(".btn_mostrar_he").hide();

    $("#tabla-asistencia-trab").show();
    $("#ver_asistencia").hide();
    $("#detalle_asistencia").hide();
    $("#detalle_qs").hide();

    $('#btn-export-qs').attr(`href`, `#`).attr(`onclick`, `toastr_error('No hay datos!!', 'Seleccione una quincena o semana para exportar.');`);

    estado_editar_asistencia = false;
  } else if (estados == 2) { // TABLA AGREGAR ASISTENCIA
    
    $("#btn-registrar").hide();
    $("#btn-regresar").show();
    $("#btn-editar").show();
    $("#btn-guardar").hide();

    $(".btn_mostrar_hne").show();
    $(".btn_mostrar_hn").show();
    $(".btn_mostrar_he").show();

    $("#tabla-asistencia-trab").hide();
    $("#ver_asistencia").show();
    $("#detalle_asistencia").hide();
    $("#detalle_qs").hide();

    estado_editar_asistencia = false;      
  } else if (estados == 3) { // ASISTENCIA POR DIA
      
    $("#btn-registrar").hide();
    $("#btn-regresar").show();
    $("#btn-editar").hide();
    $("#btn-guardar").hide();

    $("#tabla-asistencia-trab").hide();
    $("#ver_asistencia").hide();
    $("#detalle_asistencia").show();
    $("#detalle_qs").hide();

    estado_editar_asistencia = false;
  } else if (estados == 4) { // RESUMEN POR SEMANA O QUINCENA
        
    $("#btn-registrar").hide();
    $("#btn-regresar").show();
    $("#btn-editar").hide();
    $("#btn-guardar").hide();

    $("#tabla-asistencia-trab").hide();
    $("#ver_asistencia").hide();
    $("#detalle_asistencia").hide();
    $("#detalle_qs").show();

    estado_editar_asistencia = false;     
  }
}

function show_hide_span_input(flag){

  if (flag == 1) {
    // ocultamos los span
    $(".span_asist").show();
    // mostramos los inputs
    $(".input_asist").hide();

    // ocultamos el boton editar
    $("#btn-editar").show();
    // mostramos el boton guardar
    $("#btn-guardar-hn").hide();
    $("#btn-guardar-he").hide();  

    $(".checkbox_visible").removeAttr("disabled");

    estado_editar_asistencia = false;
  } else if (flag == 2) {
    
    // ocultamos los span
    $(".span_asist").hide();
    // mostramos los inputs
    $(".input_asist").show();

    // ocultamos el boton editar
    $("#btn-editar").hide();
    // mostramos el boton guardar
    if ($('#tipo_hora').val() == 'HN') { $("#btn-guardar-hn").show();  } else  if ($('#tipo_hora').val() == 'HE') { $("#btn-guardar-he").show();  }   

    $(".checkbox_visible").attr("disabled", true);

    estado_editar_asistencia = true;    
  }  
}

//TBLA - PRINCIPAL
function tbla_principal(nube_idproyecto) {  

  tabla_principal = $('#tabla-asistencia').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,11,12,13,3,4,5,6,8,9,10], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,11,12,13,3,4,5,6,8,9,10], } }, 
      { extend: 'pdfHtml5', footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,11,12,13,3,4,5,6,8,9,10], }  } ,      
    ],
    ajax:{
      url: '../ajax/asistencia_obrero.php?op=tbla_principal&nube_idproyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
      // columna: opciones
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
      // columna: Sueldo diario
      if (data[5] != '') { $("td", row).eq(5).addClass('text-nowrap text-right'); }
      // columna: Sueldo diario
      if (data[6] != '') { $("td", row).eq(6).addClass('text-nowrap text-right'); }
      // columna: Sueldo mensual
      if (data[7] != '') { $("td", row).eq(7).addClass('text-nowrap text-right'); }
      // columna: Adicional descuento
      if (data[9] != '') { $("td", row).eq(9).addClass('text-nowrap text-right'); }
      // columna: Pago acumulado
      if (data[10] != '') { $("td", row).eq(10).addClass('text-nowrap text-right'); }      
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 9 ).data().reduce( function ( a, b ) { return parseFloat(a) + parseFloat(b); }, 0 );      
      $( api1.column( 9 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total1)}</span>` );
      var api2 = this.api(); var total2 = api2.column( 10 ).data().reduce( function ( a, b ) { return parseFloat(a) + parseFloat(b); }, 0 );      
      $( api2.column( 10 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total2)}</span>` );      
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [11,12,13], visible: false, searchable: false, },
      { targets: [5,6,7,9,10], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();

  //Suma ACUMULADO
  // $.post("../ajax/asistencia_obrero.php?op=suma_total_acumulado", { 'nube_idproyecto': nube_idproyecto }, function (e, status) {

  //   e =JSON.parse(e); //console.log(e);

  //   if (e.status == true) {       
  //     $(".total_acumulado_trabjadores").html(`S/ ${formato_miles(e.data.pago_quincenal)}`);
  //   } else {
  //     $(".total_acumulado_trabjadores").html("S/ 0.00");
  //   }
  // }).fail( function(e) { ver_errores(e); } );  
}

function listar_botones_q_s(nube_idproyecto) {

  $('#lista_quincenas').html('<div class="my-3" ><i class="fas fa-spinner fa-pulse fa-2x"></i>&nbsp;&nbsp;&nbsp;Cargando...</div>');

  //Listar quincenas(botones)
  $.post("../ajax/asistencia_obrero.php?op=listar_s_q_botones", { nube_idproyecto: nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); //console.log(e);
    var id_proyecto = localStorage.getItem('nube_idproyecto');
    var nube_fecha_pago_obrero = localStorage.getItem('nube_fecha_pago_obrero');

    var q_s_btn = "", q_s_dias = '' ;
    if (nube_fecha_pago_obrero == "quincenal") { q_s_btn = 'Quincena'; q_s_dias ='14'; } else if (nube_fecha_pago_obrero == "semanal") {  q_s_btn = 'Semana'; q_s_dias ='7' }

    if (e.status == true) {      
      
      if ( id_proyecto == null || id_proyecto == '' || id_proyecto == '0' ) { // validamos si abrio el proyecto
        $('#lista_quincenas').html(`<div class="alert alert-danger alert-dismissible w-450px">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
          <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
          Lo mas probable es que no hayas selecionado un proyecto. <br>Clic en el <span class="bg-color-8eff27 p-1 rounded-lg text-dark"> <i class="fa-solid fa-screwdriver-wrench"></i> boton verde</span> para seleccionar alguno.
        </div>`);        
      } else {
        var fecha_inicio = e.data.proyecto.fecha_inicio_actividad;  
        var fecha_fin    = e.data.proyecto.fecha_fin_actividad;
        if ( fecha_inicio == null || fecha_inicio == '' ||  fecha_fin == null || fecha_fin == '') {  // validamos si tiene las fechas
          $('#lista_quincenas').html(`<div class="alert alert-danger alert-dismissible w-450px">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
            <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
            No has definido las de <b>fechas de actividad</b> d del proyecto. <br>Clic en el <span class="bg-green p-1 rounded-lg"> <i class="far fa-calendar-alt"></i> boton verde</span> para actualizar las fechas de actividad.
          </div>`);
        } else {        
          
          $('#lista_quincenas').html('');
          e.data.btn_asistencia.forEach((val, key) => {

            $('#lista_quincenas').append(` <button type="button" id="boton-${key}" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="datos_quincena('${val.ids_q_asistencia}', '${format_d_m_a(val.fecha_q_s_inicio)}', '${format_d_m_a(val.fecha_q_s_fin)}', '${key}', ${q_s_dias});"><i class="far fa-calendar-alt"></i> ${q_s_btn} ${val.numero_q_s}<br>${format_d_m_a(val.fecha_q_s_inicio)} // ${format_d_m_a(val.fecha_q_s_fin)}</button>`)
            
          });        
        }
      }        
    } else {
      ver_errores(e);
    }
        
    //console.log(fecha);
  }).fail( function(e) { ver_errores(e); } );
}

$('.btn_mostrar_hne').on('click', function () {
  
  $('.btn_mostrar_hne').addClass('bg-gradient-primary activado_hne').removeClass('btn-outline-primary').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.btn_mostrar_hn').addClass('btn-outline-primary').removeClass('bg-gradient-primary activado_hne');
  $('.btn_mostrar_he').addClass('btn-outline-primary').removeClass('bg-gradient-primary activado_hne');
  $('#btn-editar').hide(); $('.btn-horas-multiples').hide(); $('.table_title_hne').html(`Horas<br>HN/HE`);
  mostrar_hne(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r, '.btn_mostrar_hne', 'HNE');
  $('#tipo_hora').val(`HNE`);
  
});

$('.btn_mostrar_hn').on('click', function () {
  $('.btn_mostrar_hne').addClass('btn-outline-primary').removeClass('bg-gradient-primary activado_hne');
  $('.btn_mostrar_hn').addClass('bg-gradient-primary activado_hne').removeClass('btn-outline-primary').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.btn_mostrar_he').addClass('btn-outline-primary').removeClass('bg-gradient-primary activado_hne');
  $('#btn-editar').show(); $('.btn-horas-multiples').show(); $('.table_title_hne').html(`Total <br> Horas`);
  mostrar_hn(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r, '.btn_mostrar_hn', 'HN');
  $('#tipo_hora').val(`HN`);
 
});

$('.btn_mostrar_he').on('click', function () {
  $('.btn_mostrar_hne').addClass('btn-outline-primary').removeClass('bg-gradient-primary activado_hne');
  $('.btn_mostrar_hn').addClass('btn-outline-primary').removeClass('bg-gradient-primary activado_hne');
  $('.btn_mostrar_he').addClass('bg-gradient-primary activado_hne').removeClass('btn-outline-primary').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('#btn-editar').show(); $('.btn-horas-multiples').show(); $('.table_title_hne').html(`Total <br> Horas`);
  mostrar_he(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r, '.btn_mostrar_he', 'HE');
  $('#tipo_hora').val(`HE`);
  
});

// listamos la data de una quincena selecionada
function datos_quincena(ids_q_asistencia, f1, f2, i, cant_dias_asistencia) {  

  ids_q_asistencia_r = ids_q_asistencia; f1_r = f1; f2_r = f2; i_r = i; cant_dias_asistencia_r = cant_dias_asistencia;

  if ( $('.btn_mostrar_hne').hasClass('activado_hne') == true ) {
    $('.btn-horas-multiples').hide(); $('.table_title_hne').html(`Horas<br>HN/HE`); $('#tipo_hora').val(`HNE`);
    mostrar_hne(ids_q_asistencia, f1, f2, i, cant_dias_asistencia, '.btn_mostrar_hne', 'HNE');    
  } else if ( $('.btn_mostrar_hn').hasClass('activado_hne') == true ) {
    $('.btn-horas-multiples').show(); $('.table_title_hne').html(`Total <br> Horas`); $('#tipo_hora').val(`HN`);
    mostrar_hn(ids_q_asistencia, f1, f2, i, cant_dias_asistencia, '.btn_mostrar_hn', 'HN');    
  } else if ( $('.btn_mostrar_he').hasClass('activado_hne') == true ) {
    $('.btn-horas-multiples').show(); $('.table_title_hne').html(`Total <br> Horas`); $('#tipo_hora').val(`HE`);
    mostrar_he(ids_q_asistencia, f1, f2, i, cant_dias_asistencia, '.btn_mostrar_he', 'HE');    
  }
}

function mostrar_hne(ids_q_asistencia, f1, f2, i, cant_dias_asistencia, class_btn, tipo_hora) {
  console.log(f1, f2, i, cant_dias_asistencia);
  mostrar_form_table(2); 
  select_dia_multiple(f1, f2);
  var nube_idproyect =localStorage.getItem('nube_idproyecto');

  var data_export = `?idproyecto=${nube_idproyect}&ids_q_asistencia=${ids_q_asistencia}&f1=${format_a_m_d(f1)}&f2=${format_a_m_d(f2)}&n_f_i_p=${n_f_i_p}&n_f_f_p=${n_f_f_p}&i=${i}&cant_dias_asistencia=${cant_dias_asistencia}`;
  $('#btn-export-qs').attr(`href`, `../reportes/export_xlsx_format_asistencia_hne.php${data_export}`).attr(`onclick`, ``);

  // ocultamos las tablas  
  $("#ver_asistencia").hide();
  $('#cargando-registro-asistencia').show().html(`<div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div>`);

  // cambiamos el valor del colspan
  $("#dias_asistidos_s_q").attr("colspan", cant_dias_asistencia);

  // cambiamos el TABLE-HEAD tipo de pago
  if (cant_dias_asistencia == 7) { $(".head_pago_q_s").html("Pago <br> semanal"); }else{ $(".head_pago_q_s").html("Pago <br> quincenal"); }

  $("#btn-editar").hide(); $("#btn-guardar-hn").hide(); $("#btn-guardar-he").hide();  

  // vaciamos el array
  array_asistencia = []; array_trabajador = [];
  array_sabatical_1 = []; array_sabatical_2 = [];
  array_pago_contador = []; array_agregar_horas = []

  // pintamos el botón
  pintar_boton_selecionado(i);
  
  var table_dia_semana = ""; 

  var count_bloque_q_s = 1; var total_pago = 0;  
  
  $.post("../ajax/asistencia_obrero.php?op=ver_datos_quincena", {'ids_q_asistencia':ids_q_asistencia, f1:format_a_m_d(f1),f2:format_a_m_d(f2),'nube_idproyect':nube_idproyect, 'n_f_i_p': n_f_i_p, 'n_f_f_p': n_f_f_p}, function (e, status) {
        
    e =JSON.parse(e); console.log(e);   

    $(".data_table_body").html('');  

    var count_sabatical_1_total = 0;
    var count_sabatical_2_total = 0;

    var count_pago_contador_total = 0;
    
    if (e.status == true) {
      if (e.data.length === 0) {
        $('#cargando-registro-asistencia').html(`<div class="col-lg-12 text-center"><h4>─ No hay OBREROS asignado a este proyecto ─</h4></div>`);
      }else{        
        e.data[0].asistencia.forEach((val2, key2) => {
          table_dia_semana = table_dia_semana.concat(`<th class="p-x-12px py-0"> ${val2.fecha_asistencia.substr(8,2)} <br> ${extraer_dia_semana(val2.fecha_asistencia)} </th>`);          
        });

        e.data.forEach((val, key) => {

          var estado_dentro_de_obra = true;
          count_bloque_q_s = 1;
          var count_dias_asistidos = 0; var horas_total = 0; var horas_nomr_total = 0; var horas_extr_total = 0; var sabatical = 0;
          
          var tabla_bloc_HN_asistencia_3=""; var tabla_bloc_HE_asistencia_2 =""; var estado_hallando_sabado = true;

          val.asistencia.forEach((val2, key2) => {

            var weekday = extraer_dia_semana(val2.fecha_asistencia); //console.log(weekday);
            var class_val_x_dia = `${extraer_dia_semana(val2.fecha_asistencia)}_${extraer_dia_mes(val2.fecha_asistencia)}_${extraer_mes_number(val2.fecha_asistencia)}`;

            horas_total      = horas_total + parseFloat(val2.horas_normal_dia) + parseFloat(val2.horas_extras_dia);
            horas_nomr_total = horas_nomr_total + parseFloat(val2.horas_normal_dia);
            horas_extr_total = horas_extr_total + parseFloat(val2.horas_extras_dia);

            var data_che = `'${format_d_m_a(val2.fecha_asistencia)}', '${val.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${val.sueldo_diario}', '${val.sueldo_hora}', '${e.data.length}', '${val.sabatical_manual_1}', '${val.sabatical_manual_2}', '${tipo_hora}'`;
            var class_val_x_dia = `${extraer_dia_semana(val2.fecha_asistencia)}_${extraer_dia_mes(val2.fecha_asistencia)}_${extraer_mes_number(val2.fecha_asistencia)}`;
            var color_hn_td = val2.horas_normal_dia >= 8 ? 'bg-color-28a74540': (val2.horas_normal_dia < 8 && val2.horas_normal_dia > 0  ? 'bg-color-28a74540' : 'bg-color-ff000040' ) ; 
            var color_he_td = val2.horas_extras_dia >= 8 ? 'bg-color-28a74540': (val2.horas_extras_dia < 8 && val2.horas_extras_dia > 0  ? 'bg-color-28a74540' : 'bg-color-ff000040' ) ; 

            if (weekday != 'sa') {
              if (val2.dia_regular == true) {
                tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center bg-color-acc3c7"> <span class="span_asist " >-</span> </td>`);                
                tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center bg-color-acc3c7"> <span class=" " >-</span> </td>`);
              } else {
                tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center ${color_hn_td}" > 
                  <span class="span_asist span_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" >${val2.horas_normal_dia}</span>                  
                </td>`);
                
                tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center ${color_he_td}" > 
                  <span class="span_asist span_HE_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" >${val2.horas_extras_dia}</span>                  
                </td>`);               
              }
            } else {
              // SABATICALES
              var error_edit_sab = `toastr_error('No puede editar!!', 'Esta seccion no es editable, p or favor ingrese a la siguiente sección.');`;
              if (estado_hallando_sabado) { 
                if (val2.sabatical_manual_1 == "0") {
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical">
                  <!-- <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${val.idtrabajador_por_proyecto}_1" onclick="${error_edit_sab}">  -->
                  <img src="../dist/svg/no_check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                  <input class="input_HN_${val.idtrabajador_por_proyecto}_${i} input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)} desglose_q_s_${val.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" type="text" value="0" > </td>`);                  
                  count_dias_asistidos -= 1;
                } else {
                  if (val2.sabatical_manual_1 == "1") {
                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> 
                    <!-- <input class="w-xy-20px" type="checkbox" checked id="checkbox_sabatical_${val.idtrabajador_por_proyecto}_1" onclick="${error_edit_sab}"> -->
                    <img src="../dist/svg/check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                    <input class="input_HN_${val.idtrabajador_por_proyecto}_${i} input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)} desglose_q_s_${val.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" type="text" value="8" > </td>`);                      
                    sabatical = 1; count_sabatical_1_total++;
                  } else {
                    if (val2.horas_normal_dia == 8 ) {
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${val.idtrabajador_por_proyecto}_${count_bloque_q_s}"> 
                      <!-- <input class="w-xy-20px" type="checkbox" checked id="checkbox_sabatical_${val.idtrabajador_por_proyecto}_1" onclick="${error_edit_sab}"> -->
                      <img src="../dist/svg/check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                      <input class="input_HN_${val.idtrabajador_por_proyecto}_${i} input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)} desglose_q_s_${val.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" type="text" value="8" > </td>`);                                              
                      sabatical = 1; count_sabatical_1_total++;
                    } else {
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${val.idtrabajador_por_proyecto}_${count_bloque_q_s}"> 
                      <!-- <input class="w-xy-20px" type="checkbox"  id="checkbox_sabatical_${val.idtrabajador_por_proyecto}_1" onclick="${error_edit_sab}"> -->
                      <img src="../dist/svg/no_check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                      <input class="input_HN_${val.idtrabajador_por_proyecto}_${i} input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)} desglose_q_s_${val.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" type="text" value="0" > </td>`);                      
                      count_dias_asistidos -= 1; //horas_nomr_total -= 8;          
                    }
                  }
                }

                array_sabatical_1.push({ 
                  'idresumen_q_s_asistencia':val.idresumen_q_s_asistencia,
                  'id_trabajador':val.idtrabajador_por_proyecto, 
                  'fecha_asistida':val2.fecha_asistencia,
                  'sueldo_hora':val2.sueldo_hora,
                  'numero_sabado':'1',
                  // 'fecha_q_s_inicio': format_a_m_d(f1_r), 
                  // 'fecha_q_s_fin': format_a_m_d(f2_r), 
                  // 'numero_q_s':(parseInt(i_r) + 1),
                });
                estado_hallando_sabado = false; // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
              } else {
                if (val.sabatical_manual_2 == "0") {
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> 
                  <!-- <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${val.idtrabajador_por_proyecto}_2" onclick="${error_edit_sab}"> --> 
                  <img src="../dist/svg/no_check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                  <input class="input_HN_${val.idtrabajador_por_proyecto}_${i} input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)} hidden" id="input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" type="text" value="0" > </td>`);                  
                } else {
                  if (val.sabatical_manual_2 == "1") {
                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> 
                    <!-- <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${val.idtrabajador_por_proyecto}_2" onclick="${error_edit_sab}"> --> 
                    <img src="../dist/svg/check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                    <input class="input_HN_${val.idtrabajador_por_proyecto}_${i} input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)} desglose_q_s_${val.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" type="text" value="8" > </td>`); 
                    sabatical += 1; count_sabatical_2_total++;
                  } else {
                    if (val2.horas_normal_dia == 8) { 
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${val.idtrabajador_por_proyecto}_${count_bloque_q_s}"> 
                      <!-- <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${val.idtrabajador_por_proyecto}_2" onclick="${error_edit_sab}"> --> 
                      <img src="../dist/svg/check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                      <input class="input_HN_${val.idtrabajador_por_proyecto}_${i} input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)} desglose_q_s_${val.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" type="text" value="8" > </td>`);                                                
                      sabatical += 1; count_sabatical_2_total++;                      
                    } else {                       
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${val.idtrabajador_por_proyecto}_${count_bloque_q_s}"> 
                      <!-- <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${val.idtrabajador_por_proyecto}_2" onclick="${error_edit_sab}"> -->  
                      <img src="../dist/svg/no_check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                      <input class="input_HN_${val.idtrabajador_por_proyecto}_${i} input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)} desglose_q_s_${val.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" type="text" value="0" > </td>`);                                          
                      count_dias_asistidos -= 1; //horas_nomr_total -= 8;
                    }                  
                  }
                } 

                array_sabatical_2.push({ 
                  'idresumen_q_s_asistencia':val.idresumen_q_s_asistencia,
                  'id_trabajador':val.idtrabajador_por_proyecto, 
                  'fecha_asistida':val2.fecha_asistencia, 
                  'sueldo_hora':val2.sueldo_hora,
                  'numero_sabado':'2',
                  // 'fecha_q_s_inicio': format_a_m_d(f1_r), 
                  // 'fecha_q_s_fin': format_a_m_d(f2_r), 
                  // 'numero_q_s':(parseInt(i_r) + 1),
                });                              
              }            
            }   

            // recoge sabados
            array_asistencia.push( { 
              'idasistencia_trabajador': val2.idasistencia_trabajador,
              'id_trabajador'     : val.idtrabajador_por_proyecto, 
              'fecha_asistida'    : format_d_m_a(val2.fecha_asistencia), 
              'class_input_hn'    :`input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`, 
              'class_input_he'    :`input_HE_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`,
              'cant_dias'         : cant_dias_asistencia,   
              'cant_trabajador'   : e.data.length,
              'sueldo_hora'       : val.sueldo_hora,
              'sabatical_manual_1': val.sabatical_manual_1,
              'sabatical_manual_2': val.sabatical_manual_2
            } );
            // no recoge sabados
            array_agregar_horas.push( { 
              'idasistencia_trabajador': val2.idasistencia_trabajador,
              'id_trabajador'     : val.idtrabajador_por_proyecto, 
              'fecha_asistida'    : format_d_m_a(val2.fecha_asistencia), 
              'class_input_hn'    :`input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`, 
              'class_input_he'    :`input_HE_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`,
              'cant_dias'         : cant_dias_asistencia,   
              'cant_trabajador'   : e.data.length,
              'sueldo_diario'     : val.sueldo_diario,
              'sueldo_hora'       : val.sueldo_hora,
              'sabatical_manual_1': val.sabatical_manual_1,
              'sabatical_manual_2': val.sabatical_manual_2
            } );

            count_bloque_q_s++;
          }); /* Fin Foreach ASISTENCIA */

          // asignamos lo trabajadores a un "array"
          array_trabajador.push({ 
            'id_trabajador':val.idtrabajador_por_proyecto, 
            'idresumen_q_s_asistencia': val.idresumen_q_s_asistencia,
            'nombre_trabjador':val.nombres,
            'sueldo_hora':val.sueldo_hora,
            'array_asistencia': array_asistencia
          });   

          array_asistencia = [];

          var tabla_bloc_HN_trabaj_2 =  `<td rowspan="2" class="center-vertical">${val.nombres}</td> 
            <td rowspan="2" class="center-vertical">${val.nombre_ocupacion} 
            <br> ${ (val.estado_trabajador==1?(estado_dentro_de_obra==false?'<span class="text-center badge badge-danger">Fecha terminada</span>':'<span class="text-center badge badge-success">Activado</span>'):'<span class="text-center badge badge-danger">Desactivado</span>')} 
          </td>`;    

          var tabla_bloc_HN_total_hora_4 =  `<td class="text-center center-vertical"> 
            <span  class="total_HN_${val.idtrabajador_por_proyecto}" >${val.total_hn}</span>             
          </td>`;

          var tabla_bloc_HN_total_dia_5 = `<td class="text-center center-vertical" >
            <span  class="dias_asistidos_${val.idtrabajador_por_proyecto}">${val.total_dias_asistidos_hn} </span>
          </td>`;

          var tabla_bloc_HN_sueldos_6 = `<td class="text-center center-vertical" rowspan="2">${formato_miles(val.sueldo_semanal)}</td>
          <td class="text-center center-vertical" rowspan="2">${val.sueldo_diario}</td>
          <td class="text-center center-vertical" rowspan="2">${val.sueldo_hora}</td>`;

          var tabla_bloc_HN_sabatical_7 =  `<td class="text-center center-vertical" rowspan="2">
            <span  class="sabatical_${val.idtrabajador_por_proyecto}">${sabatical}</span>
          </td>`;
          var pago_parcial_qs = parseFloat(val.pago_parcial_hn) + parseFloat(val.pago_parcial_he);
          var tabla_bloc_HN_pago_parcial_8 = `<td class="text-center center-vertical" rowspan="2"> 
            <span  class="pago_parcial_HNE_${val.idtrabajador_por_proyecto}"> ${formato_miles(pago_parcial_qs)}</span>            
          </td>`;
          
          var fechas_adicional = "";
          
          // validamos si existe una suma_adicional 
          if (val.idresumen_q_s_asistencia == "") { fechas_adicional = format_a_m_d(f1); } else { fechas_adicional = val.fecha_registro; }

          var tabla_bloc_HN_descuent_9 = `<td rowspan="2" class="text-center center-vertical"> 
            <span class="span_asist" >${(val.adicional_descuento_hn + val.adicional_descuento_he)}</span> 
            <span class="badge badge-info float-right cursor-pointer shadow-1px06rem09rem-rgb-52-174-193-77" data-toggle="tooltip" data-original-title="Por descuento" onclick="modal_adicional_descuento_hne( '${val.idresumen_q_s_asistencia}', '${val.idtrabajador_por_proyecto}');"><i class="far fa-eye"></i></span>
          </td>`;
          var pago_total_qs = parseFloat(val.pago_quincenal_hn) + parseFloat(val.pago_quincenal_he); //console.log(pago_total_qs);
          var tabla_bloc_HN_pago_total_10 = `<td rowspan="2" class="text-center center-vertical"> 
            <span  class="val_pago_quincenal_${key+1} pago_quincenal_${val.idtrabajador_por_proyecto}"> ${formato_miles( pago_total_qs )} </span> 
          </td>`;

          var tabla_bloc_envio_contador_11 = "";

          // validamos el el envio al contador
          if (val.estado_envio_contador == "") {
            tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox"  id="checkbox_asignar_pago_contador_${val.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${val.idtrabajador_por_proyecto}', '${val.nombres}', '${val.idresumen_q_s_asistencia}', '${(val.pago_quincenal_hn + val.pago_quincenal_he)}');"> </td>`;        
          } else if (val.estado_envio_contador == "0") {            
            tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox"  id="checkbox_asignar_pago_contador_${val.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${val.idtrabajador_por_proyecto}', '${val.nombres}', '${val.idresumen_q_s_asistencia}', '${(val.pago_quincenal_hn + val.pago_quincenal_he)}');"> </td>`;                  
          } else if (val.estado_envio_contador == "1") {  
            tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox" checked id="checkbox_asignar_pago_contador_${val.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${val.idtrabajador_por_proyecto}', '${val.nombres}', '${val.idresumen_q_s_asistencia}', '${(val.pago_quincenal_hn + val.pago_quincenal_he)}');"> </td>`;                  
            count_pago_contador_total++;            
          }

          // acumulamos el total de pagos
          total_pago = total_pago + parseFloat( pago_total_qs );
          
          var tabla_bloc_HN_1 = `<tr>
            <td class="" rowspan="2"><b>${key+1}</b></td>
            <td class="" >H/N</td>
            ${tabla_bloc_HN_trabaj_2}
            ${tabla_bloc_HN_asistencia_3 }
            ${tabla_bloc_HN_total_hora_4}
            ${tabla_bloc_HN_total_dia_5} 
            ${tabla_bloc_HN_sueldos_6} 
            ${tabla_bloc_HN_sabatical_7} 
            ${tabla_bloc_HN_pago_parcial_8} 
            ${tabla_bloc_HN_descuent_9}
            ${tabla_bloc_HN_pago_total_10}
            ${tabla_bloc_envio_contador_11}
          </tr>`;      
        
          var tabla_bloc_HE_total_hora_3 = `<td class="text-center"> <span  class="total_HE_${val.idtrabajador_por_proyecto}">${val.total_he}</span> </td>`;
        
          var tabla_bloc_HE_total_dia_4 =`<td class="text-center"><span  class="total_dia_HE_${val.idtrabajador_por_proyecto}"> ${val.total_dias_asistidos_he}</span> </td>`;           

          var tabla_bloc_HE_1 = `<tr> 
            <td class="" >H/E</td>
            ${tabla_bloc_HE_asistencia_2}
            ${tabla_bloc_HE_total_hora_3}
            ${tabla_bloc_HE_total_dia_4}
          </tr>`;

          //Unimos y mostramos los bloques separados
          $(".data_table_body").append(tabla_bloc_HN_1 + tabla_bloc_HE_1);

          // asignamos pago al contador a un "array"
          if (parseFloat(val.pago_quincenal_hn + val.pago_quincenal_he) > 0) {
            array_pago_contador.push({           
              'fechas_adicional'          : fechas_adicional,
              'idtrabajador_por_proyecto' : val.idtrabajador_por_proyecto,
              'nombres'                   : val.nombres,
              'idresumen_q_s_asistencia'  : val.idresumen_q_s_asistencia,
              'pago_quincenal_hn'         : val.pago_quincenal_hn,
              'pago_quincenal_he'         : val.pago_quincenal_he,
              'pago_quincenal_hne'        : (val.pago_quincenal_hn + val.pago_quincenal_he)
            });
          } 

        });

        var tabla_bloc_TOTAL_1 = '';

        var input_check_sab_1 = "";
        var input_check_sab_2 = "";

        var input_check_pago_contador = "";
        var error_check_sab = `toastr_error('No puede editar!!', 'Esta seccion no es editable, p or favor ingrese a la siguiente sección.');`;

        if (e.data.length === 0) {           
          input_check_sab_1 = `<input class="w-xy-20px" type="checkbox" disabled="disabled">`;
          input_check_sab_2 = `<input class="w-xy-20px" type="checkbox" disabled="disabled">`;
        } else {

          if (count_sabatical_1_total == e.data.length) {
            input_check_sab_1 = `<img src="../dist/svg/check_input.svg" alt="" width="20px" onclick="${error_check_sab}" >`;
          } else {
            input_check_sab_1 = `<img src="../dist/svg/no_check_input.svg" alt="" width="20px" onclick="${error_check_sab}" >`;
          }
          if (count_sabatical_2_total == e.data.length) {
            input_check_sab_2 = `<img src="../dist/svg/check_input.svg" alt="" width="20px" onclick="${error_check_sab}" >`;
          } else {
            input_check_sab_2 = `<img src="../dist/svg/no_check_input.svg" alt="" width="20px" onclick="${error_check_sab}" >`;
          }

          if (count_pago_contador_total == e.data.length) {
            input_check_pago_contador = `<input class="w-xy-20px checkbox_visible" type="checkbox" checked id="checkbox_asignar_pago_contador_todos" onclick="asignar_todos_pago_al_contador( );">`;
          }else{
            input_check_pago_contador = `<input class="w-xy-20px checkbox_visible" type="checkbox" id="checkbox_asignar_pago_contador_todos" onclick="asignar_todos_pago_al_contador( );">`;
          }
        }

        if (cant_dias_asistencia == 14) {
          $('.ir_a_right').show();
          $('.ir_a_bottom').show();
          $('.ir_a_left').show();
          
          tabla_bloc_TOTAL_1 = `<tr> 
            <td class="text-center" colspan="10"></td> 
            <td class="text-center" >
              ${input_check_sab_1}
            </td>
            <td class="text-center" colspan="6"></td> 
            <td class="text-center" >
              ${input_check_sab_2}
            </td>
            <td class="text-center" colspan="7"></td>
            <td class="text-center"> <b>TOTAL</b> </td> 
            <td class="text-center"><span class="pago_total_quincenal font-weight-bold"> ${formato_miles(total_pago)}</span> </td> 
            <td class="text-center" >
              ${input_check_pago_contador}
            </td>
          </tr>`;
          
        } else { 

          if (cant_dias_asistencia == 7) {
            $('.ir_a_right').hide();
            $('.ir_a_bottom').show();
            $('.ir_a_left').hide();
            tabla_bloc_TOTAL_1 = `<tr> 
              <td class="text-center" colspan="10"></td> 
              <td class="text-center" >
                ${input_check_sab_1} 
              </td> 
              <td class="text-center" colspan="7"></td>           
              <td class="text-center"> <b>TOTAL</b> </td> 
              <td class="text-center"><span class="pago_total_quincenal font-weight-bold"> ${formato_miles(total_pago.toFixed(2))}</span> </td>
              <td class="text-center" >
                ${input_check_pago_contador}
              </td> 
            </tr>`;
            
          } else {

            tabla_bloc_TOTAL_1 = `<tr> <td class="text-center" colspan="25"></td> <td class="text-center"> <b>TOTAL</b> </td> <td class="text-center"><span  class="pago_total_quincenal font-weight-bold"> ${formato_miles(total_pago.toFixed(2))}</span> </td> </tr>`;
            
          }
        }

        $('.data-dia-semana').html(table_dia_semana); 
        $(".data_table_body").append(tabla_bloc_TOTAL_1);

        $("#ver_asistencia").show();   
        $('#cargando-registro-asistencia').hide();        
       
      }           
    } else {
      ver_errores(e);
    }   
    
    // VALIDAR LA CARGA
    if (class_btn == '.btn_mostrar_hne') {
      $(class_btn).html('HNE');
    } else if (class_btn == '.btn_mostrar_hn') {
      $(class_btn).html('HN');
    } else if (class_btn == '.btn_mostrar_he') {
      $(class_btn).html('HE');
    }    

    //scroll segun tamaño
    scroll_tabla_asistencia();

  }).fail( function(e) { ver_errores(e); } ); //end post -
  
  $('[data-toggle="tooltip"]').tooltip();

  count_dias_asistidos = 0;  horas_nomr_total = 0;   horas_extr_total = 0; 
}

function mostrar_hn(ids_q_asistencia, f1, f2, i, cant_dias_asistencia, class_btn, tipo_hora) {
  console.log(f1, f2, i, cant_dias_asistencia);
  mostrar_form_table(2); 
  select_dia_multiple(f1, f2);
  var nube_idproyect =localStorage.getItem('nube_idproyecto');

  var data_export = `?idproyecto=${nube_idproyect}&ids_q_asistencia=${ids_q_asistencia}&f1=${format_a_m_d(f1)}&f2=${format_a_m_d(f2)}&n_f_i_p=${n_f_i_p}&n_f_f_p=${n_f_f_p}&i=${i}&cant_dias_asistencia=${cant_dias_asistencia}`;
  $('#btn-export-qs').attr(`href`, `../reportes/export_xlsx_format_asistencia_hn.php${data_export}`).attr(`onclick`, ``);

  // ocultamos las tablas  
  $("#ver_asistencia").hide();
  $('#cargando-registro-asistencia').show().html(`<div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div>`);

  // cambiamos el valor del colspan
  $("#dias_asistidos_s_q").attr("colspan", cant_dias_asistencia);

  // cambiamos el TABLE-HEAD tipo de pago
  if (cant_dias_asistencia == 7) { $(".head_pago_q_s").html("Pago <br> semanal"); }else{ $(".head_pago_q_s").html("Pago <br> quincenal"); }

  $("#btn-editar").show(); $("#btn-guardar-hn").hide(); $("#btn-guardar-he").hide(); 

  // vaciamos el array
  array_asistencia = []; array_trabajador = [];
  array_sabatical_1 = []; array_sabatical_2 = [];
  array_pago_contador = []; array_agregar_horas = []

  // pintamos el botón
  pintar_boton_selecionado(i);
  
  var table_dia_semana = ""; 

  var count_bloque_q_s = 1; var total_pago = 0;  
  
  $.post("../ajax/asistencia_obrero.php?op=ver_datos_quincena", {'ids_q_asistencia':ids_q_asistencia, f1:format_a_m_d(f1),f2:format_a_m_d(f2),'nube_idproyect':nube_idproyect, 'n_f_i_p': n_f_i_p, 'n_f_f_p': n_f_f_p}, function (e, status) {
        
    e =JSON.parse(e); console.log(e);   

    $(".data_table_body").html('');  

    var count_sabatical_1_total = 0;
    var count_sabatical_2_total = 0;

    var count_pago_contador_total = 0;
    
    if (e.status == true) {
      if (e.data.length === 0) {
        $('#cargando-registro-asistencia').html(`<div class="col-lg-12 text-center"><h4>─ No hay OBREROS asignado a este proyecto ─</h4></div>`);
      }else{        
        e.data[0].asistencia.forEach((val2, key2) => {
          table_dia_semana = table_dia_semana.concat(`<th class="p-x-12px py-0"> ${val2.fecha_asistencia.substr(8,2)} <br> ${extraer_dia_semana(val2.fecha_asistencia)} </th>`);          
        });

        e.data.forEach((val, key) => {

          var estado_dentro_de_obra = true;
          count_bloque_q_s = 1;
          var count_dias_asistidos = 0; var horas_total = 0; var horas_nomr_total = 0; var horas_extr_total = 0; var sabatical = 0;
          
          var tabla_bloc_HN_asistencia_3=""; var tabla_bloc_HE_asistencia_2 =""; var estado_hallando_sabado = true;

          val.asistencia.forEach((val2, key2) => {

            var weekday = extraer_dia_semana(val2.fecha_asistencia); //console.log(weekday);
            var class_val_x_dia = `${extraer_dia_semana(val2.fecha_asistencia)}_${extraer_dia_mes(val2.fecha_asistencia)}_${extraer_mes_number(val2.fecha_asistencia)}`;            

            var data_che        = `'${format_d_m_a(val2.fecha_asistencia)}', '${val.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${val.sueldo_diario}', '${val.sueldo_hora}', '${e.data.length}', '${val.sabatical_manual_1}', '${val.sabatical_manual_2}', '${tipo_hora}'`;
            var class_val_x_dia = `${extraer_dia_semana(val2.fecha_asistencia)}_${extraer_dia_mes(val2.fecha_asistencia)}_${extraer_mes_number(val2.fecha_asistencia)}`;
            var class_desglose  = `desglose_q_s_${val.idtrabajador_por_proyecto}_${count_bloque_q_s}`;
            var class_unico     = `input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`;
            var class_x_trab    = `input_HN_${val.idtrabajador_por_proyecto}_${key2}`;
            var class_sueldo_d  = `input_PD_${val.idtrabajador_por_proyecto}_${key2}`;
            var color_td        = val2.horas_normal_dia >= 8 ? 'bg-color-28a74540': (val2.horas_normal_dia < 8 && val2.horas_normal_dia > 0  ? 'bg-color-28a74540' : 'bg-color-ff000040' ) ; 
            
            if (weekday != 'sa') {
              if (val2.dia_regular == true) {
                tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center bg-color-acc3c7" data-toggle="tooltip" data-original-title="${val2.sueldo_diario}"> 
                  <span class="" >-</span> 
                  <input class="${class_x_trab} ${class_unico}"  type="hidden" value="0"> 
                  <input type="hidden" class="${class_sueldo_d}" value="0">
                </td>`);                
                tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(``);
              } else {
                tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center ${color_td}" data-toggle="tooltip" data-original-title="${val2.sueldo_diario}" > 
                  <span class="span_asist span_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" >${val2.horas_normal_dia}</span> 
                  <input class="input_asist w-35px hn_multiple ${class_val_x_dia} ${class_x_trab} ${class_unico} ${class_desglose} hidden" id="${class_unico}" onkeyup="calcular_hn(${data_che});"  type="text" value="${val2.horas_normal_dia}" autocomplete="off">
                  <input type="hidden" class="${class_sueldo_d}" value="${val2.sueldo_diario}">
                </td>`);
                
                tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(``);               
              }
            } else {
              var error_edit_sab = `toastr_error('No puede editar!!', 'Click en el boton editar para asignar un sabatical porfavor.');`;
              // SABATICALES
              if (estado_hallando_sabado) { 

                // var cal_sab_1 = `'${format_d_m_a(val2.fecha_asistencia)}', '${val.sueldo_hora}', '${val.idtrabajador_por_proyecto}', '${val.nombres}', '${val.idresumen_q_s_asistencia}', 1`;
                var cal_sab_1 = `${data_che}, '1'`
                var id_sab_1 = `checkbox_sabatical_${val.idtrabajador_por_proyecto}_1`;
                var sab_todos_1 = `sab_1`;

                if (val2.sabatical_manual_1 == "0") {
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> 
                  <img class="span_asist" src="../dist/svg/no_check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                  <input class="input_asist w-xy-20px hidden ${sab_todos_1}" type="checkbox" id="${id_sab_1}" onclick="calcular_sabatical(${cal_sab_1})"> 
                  <input class="${class_x_trab} ${class_unico} ${class_desglose} hidden" id="${class_unico}" type="text" value="0" > 
                  <input type="hidden" class="${class_sueldo_d}" value="${val2.sueldo_diario}">
                  </td>`);                  
                  count_dias_asistidos -= 1;
                } else if (val2.sabatical_manual_1 == "1") {                  
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> 
                  <img class="span_asist" src="../dist/svg/check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                  <input class="input_asist w-xy-20px hidden ${sab_todos_1}" type="checkbox" checked  id="${id_sab_1}" onclick="calcular_sabatical(${cal_sab_1})"> 
                  <input class="${class_x_trab} ${class_unico} ${class_desglose} hidden" id="${class_unico}" type="text" value="8" > 
                  <input type="hidden" class="${class_sueldo_d}" value="${val2.sueldo_diario}">
                  </td>`);                      
                  sabatical = 1; count_sabatical_1_total++;
                } else if (val2.horas_normal_dia == 8 ) {                  
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${val.idtrabajador_por_proyecto}_${count_bloque_q_s}"> 
                  <img class="span_asist" src="../dist/svg/check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                  <input class="input_asist w-xy-20px hidden ${sab_todos_1}" type="checkbox" checked  id="${id_sab_1}" onclick="calcular_sabatical(${cal_sab_1})"> 
                  <input class="${class_x_trab} ${class_unico} ${class_desglose} hidden" id="${class_unico}" type="text" value="8" > 
                  <input type="hidden" class="${class_sueldo_d}" value="${val2.sueldo_diario}">
                  </td>`);                                              
                  sabatical = 1; count_sabatical_1_total++;
                } else {
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${val.idtrabajador_por_proyecto}_${count_bloque_q_s}"> 
                  <img class="span_asist" src="../dist/svg/no_check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                  <input class="input_asist w-xy-20px hidden ${sab_todos_1}" type="checkbox"  id="${id_sab_1}" onclick="calcular_sabatical(${cal_sab_1})"> 
                  <input class="${class_x_trab} ${class_unico} ${class_desglose} hidden" id="${class_unico}" type="text" value="0" > 
                  <input type="hidden" class="${class_sueldo_d}" value="${val2.sueldo_diario}">
                  </td>`);                      
                  count_dias_asistidos -= 1;                  
                } 

                array_sabatical_1.push({ 
                  'idresumen_q_s_asistencia':val.idresumen_q_s_asistencia,
                  'id_trabajador':val.idtrabajador_por_proyecto, 
                  'fecha_asistida':val2.fecha_asistencia,
                  'sueldo_hora':val2.sueldo_hora,
                  'numero_sabado':'1',
                  // 'fecha_q_s_inicio': format_a_m_d(f1_r), 
                  // 'fecha_q_s_fin': format_a_m_d(f2_r), 
                  // 'numero_q_s':(parseInt(i_r) + 1),
                });
                estado_hallando_sabado = false; // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
              } else {
                // var cal_sab_2 = `'${format_d_m_a(val2.fecha_asistencia)}', '${val.sueldo_hora}', '${val.idtrabajador_por_proyecto}', '${val.nombres}', '${val.idresumen_q_s_asistencia}', 2`;
                var cal_sab_2 = `${data_che}, '2'`
                var id_sab_2 = `checkbox_sabatical_${val.idtrabajador_por_proyecto}_2`;
                var sab_todos_2 = `sab_2`;

                if (val.sabatical_manual_2 == "0") {
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> 
                  <img class="span_asist" src="../dist/svg/no_check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                  <input class="input_asist w-xy-20px hidden ${sab_todos_2}" type="checkbox" id="${id_sab_2}" onclick="calcular_sabatical(${cal_sab_2});"> 
                  <input class="${class_x_trab} ${class_unico} ${class_desglose} hidden" id="${class_unico}" type="text" value="0" > 
                  <input type="hidden" class="${class_sueldo_d}" value="${val2.sueldo_diario}"></input>
                  </td>`);                  
                  
                } else if (val.sabatical_manual_2 == "1") { 
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical">
                  <img class="span_asist" src="../dist/svg/check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" > 
                  <input class="input_asist w-xy-20px hidden ${sab_todos_2}" type="checkbox" checked  id="${id_sab_2}" onclick="calcular_sabatical(${cal_sab_2}); "> 
                  <input class="${class_x_trab} ${class_unico} ${class_desglose} hidden" id="${class_unico}" type="text" value="8" > 
                  <input type="hidden" class="${class_sueldo_d}" value="${val2.sueldo_diario}">
                  </td>`); 
                  sabatical += 1; count_sabatical_2_total++;
                } else if (val2.horas_normal_dia == 8) {
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center  center-vertical bg-color-28a745 sabatical_auto_${val.idtrabajador_por_proyecto}_${count_bloque_q_s}"> 
                  <img class="span_asist" src="../dist/svg/check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                  <input class="input_asist w-xy-20px hidden ${sab_todos_2}" type="checkbox" checked  id="${id_sab_2}" onclick="calcular_sabatical(${cal_sab_2});"> 
                  <input class="${class_x_trab} ${class_unico} ${class_desglose} hidden" id="${class_unico}" type="text" value="8" > 
                  <input type="hidden" class="${class_sueldo_d}" value="${val2.sueldo_diario}">
                  </td>`);                                                
                  sabatical += 1; count_sabatical_2_total++;                      
                } else {                       
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical  sabatical_auto_${val.idtrabajador_por_proyecto}_${count_bloque_q_s}"> 
                  <img class="span_asist" src="../dist/svg/no_check_input.svg" alt="" width="20px" onclick="${error_edit_sab}" >
                  <input class="input_asist w-xy-20px hidden ${sab_todos_2}" type="checkbox" id="${id_sab_2}" onclick="calcular_sabatical(${cal_sab_2});"> 
                  <input class="${class_x_trab} ${class_unico} ${class_desglose} hidden" id="${class_unico}" type="text" value="0" > 
                  <input type="hidden" class="${class_sueldo_d}" value="${val2.sueldo_diario}">
                  </td>`);                       
                  count_dias_asistidos -= 1; //horas_nomr_total -= 8;                  
                } 

                array_sabatical_2.push({ 
                  'idresumen_q_s_asistencia':val.idresumen_q_s_asistencia,
                  'id_trabajador':val.idtrabajador_por_proyecto, 
                  'fecha_asistida':val2.fecha_asistencia, 
                  'sueldo_hora':val2.sueldo_hora,
                  'numero_sabado':'2',
                  // 'fecha_q_s_inicio': format_a_m_d(f1_r), 
                  // 'fecha_q_s_fin': format_a_m_d(f2_r), 
                  // 'numero_q_s':(parseInt(i_r) + 1),
                });                              
              }            
            }   

            // recoge sabados
            array_asistencia.push( { 
              'idasistencia_trabajador': val2.idasistencia_trabajador,
              'id_trabajador'     : val.idtrabajador_por_proyecto, 
              'fecha_asistida'    : format_d_m_a(val2.fecha_asistencia), 
              'class_input_hn'    :`input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`, 
              'class_input_he'    :`input_HE_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`,
              'cant_dias'         : cant_dias_asistencia,   
              'cant_trabajador'   : e.data.length,
              'sueldo_hora'       : val.sueldo_hora,
              'sabatical_manual_1': val.sabatical_manual_1,
              'sabatical_manual_2': val.sabatical_manual_2,
              'dia_regular'       : val2.dia_regular,
              'sueldo_diario'     : val2.sueldo_diario
            } );
            // no recoge sabados
            array_agregar_horas.push( { 
              'idasistencia_trabajador': val2.idasistencia_trabajador,
              'id_trabajador'     : val.idtrabajador_por_proyecto, 
              'fecha_asistida'    : format_d_m_a(val2.fecha_asistencia), 
              'class_input_hn'    :`input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`, 
              'class_input_he'    :`input_HE_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`,
              'cant_dias'         : cant_dias_asistencia,   
              'cant_trabajador'   : e.data.length,
              'sueldo_diario'     : val.sueldo_diario,
              'sueldo_hora'       : val.sueldo_hora,
              'sabatical_manual_1': val.sabatical_manual_1,
              'sabatical_manual_2': val.sabatical_manual_2
            } );

            count_bloque_q_s++;
          }); /* Fin Foreach ASISTENCIA */

          // asignamos lo trabajadores a un "array"
          array_trabajador.push({ 
            'id_trabajador':val.idtrabajador_por_proyecto, 
            'idresumen_q_s_asistencia': val.idresumen_q_s_asistencia,
            'nombre_trabjador':val.nombres,
            'sueldo_hora':val.sueldo_hora,
            'array_asistencia': array_asistencia
          });   

          array_asistencia = [];

          var tabla_bloc_HN_trabaj_2 =  `<td rowspan="2" class="center-vertical">${val.nombres}</td> 
            <td rowspan="2" class="center-vertical">${val.nombre_ocupacion} 
            <!-- <br> ${ (val.estado_trabajador==1?(estado_dentro_de_obra==false?'<span class="text-center badge badge-danger">Fecha terminada</span>':'<span class="text-center badge badge-success">Activado</span>'):'<span class="text-center badge badge-danger">Desactivado</span>')} -->
          </td>`;    

          var tabla_bloc_HN_total_hora_4 =  `<td class="text-center center-vertical"> 
            <span  class="total_HN_${val.idtrabajador_por_proyecto}" >${val.total_hn}</span> 
          </td>`;
          
          var tabla_bloc_HN_total_dia_5 = `<td class="text-center center-vertical" rowspan="2" >
            <span  class="dias_asistidos_${val.idtrabajador_por_proyecto}">${val.total_dias_asistidos_hn}</span> 
          </td>`;

          var tabla_bloc_HN_sueldos_6 = `<td class="text-center center-vertical" rowspan="2">${formato_miles(val.sueldo_semanal)}</td>
          <td class="text-center center-vertical" rowspan="2">${val.sueldo_diario}</td>
          <td class="text-center center-vertical" rowspan="2">${val.sueldo_hora}</td>`;

          var tabla_bloc_HN_sabatical_7 =  `<td class="text-center center-vertical" rowspan="2">
            <span  class="sabatical_${val.idtrabajador_por_proyecto}">${sabatical}</span>
          </td>`;
          
          var tabla_bloc_HN_pago_parcial_8 = `<td class="text-center center-vertical" rowspan="2"> 
            <span class="pago_parcial_HN_${val.idtrabajador_por_proyecto}"> ${formato_miles(val.pago_parcial_hn) }</span>             
          </td>`;
          
          // validamos si existe una suma_adicional 
          var fechas_adicional = "";          
          if (val.idresumen_q_s_asistencia == "") { fechas_adicional = format_a_m_d(f1); } else { fechas_adicional = val.fecha_registro; }

          var tabla_bloc_HN_descuent_9 = `<td rowspan="2" class="text-center center-vertical"> 
            <span class="span_asist" >${val.adicional_descuento_hn}</span> <input class="w-45px input_asist hidden adicional_descuento_${val.idtrabajador_por_proyecto}" onkeyup="adicional_descuento('${e.data.length}', '${val.idtrabajador_por_proyecto}', 'hn');" type="text" value="${val.adicional_descuento_hn}" autocomplete="off" > 
            <span class="badge badge-info float-right cursor-pointer shadow-1px06rem09rem-rgb-52-174-193-77" data-toggle="tooltip" data-original-title="Por descuento" onclick="modal_adicional_descuento( '${val.idresumen_q_s_asistencia}', '${val.idtrabajador_por_proyecto}', 'hn');"><i class="far fa-eye"></i></span>
          </td>`;
          
          var tabla_bloc_HN_pago_total_10 = `<td rowspan="2" class="text-center center-vertical"> 
            <span  class="val_pago_quincenal_${key+1} pago_quincenal_${val.idtrabajador_por_proyecto}"> ${formato_miles( val.pago_quincenal_hn )} </span> 
          </td>`;

          var tabla_bloc_envio_contador_11 = "";

          // validamos el el envio al contador
          if (val.estado_envio_contador == "") {
            tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox"  id="checkbox_asignar_pago_contador_${val.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${val.idtrabajador_por_proyecto}', '${val.nombres}', '${val.idresumen_q_s_asistencia}', '${val.pago_quincenal_hn}');"> </td>`;        
          } else if (val.estado_envio_contador == "0") {            
            tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox"  id="checkbox_asignar_pago_contador_${val.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${val.idtrabajador_por_proyecto}', '${val.nombres}', '${val.idresumen_q_s_asistencia}', '${val.pago_quincenal_hn}');"> </td>`;                  
          } else if (val.estado_envio_contador == "1") {  
            tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox" checked id="checkbox_asignar_pago_contador_${val.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${val.idtrabajador_por_proyecto}', '${val.nombres}', '${val.idresumen_q_s_asistencia}', '${val.pago_quincenal_hn}');"> </td>`;                  
            count_pago_contador_total++;            
          }

          // acumulamos el total de pagos
          total_pago = total_pago +  parseFloat(val.pago_quincenal_hn)  ; 
          
          var tabla_bloc_HN_1 = `<tr>
          <td class="" rowspan="2"><b>${key+1}</b></td>
          <td class="" rowspan="2">H/N</td>
          ${tabla_bloc_HN_trabaj_2}
          ${tabla_bloc_HN_asistencia_3 }
          ${tabla_bloc_HN_total_hora_4}
          ${tabla_bloc_HN_total_dia_5} 
          ${tabla_bloc_HN_sueldos_6} 
          ${tabla_bloc_HN_sabatical_7} 
          ${tabla_bloc_HN_pago_parcial_8} 
          ${tabla_bloc_HN_descuent_9}
          ${tabla_bloc_HN_pago_total_10}
          ${tabla_bloc_envio_contador_11}
        </tr>`;      
      
        var tabla_bloc_HE_total_hora_3 = `<td class="text-center"> <span ></span> </td>`;
      
        // var tabla_bloc_HE_pago_parcial_4 =`<td class="text-center"><span  class="pago_parcial_HE_${val.idtrabajador_por_proyecto}"> ${(parseFloat(val.sueldo_hora) * parseFloat(horas_extr_total)).toFixed(2)}</span> </td>`;        
        // ${tabla_bloc_HE_pago_parcial_4 }

        var tabla_bloc_HE_1 = `<tr> </tr>`;

          //Unimos y mostramos los bloques separados
          $(".data_table_body").append(tabla_bloc_HN_1 + tabla_bloc_HE_1);

          // asignamos pago al contador a un "array"
          if (parseFloat(val.pago_quincenal_hn + val.pago_quincenal_he) > 0) {
            array_pago_contador.push({           
              'fechas_adicional'          : fechas_adicional,
              'idtrabajador_por_proyecto' : val.idtrabajador_por_proyecto,
              'nombres'                   : val.nombres,
              'idresumen_q_s_asistencia'  : val.idresumen_q_s_asistencia,
              'pago_quincenal_hn'         : val.pago_quincenal_hn,
              'pago_quincenal_he'         : val.pago_quincenal_he,
              'pago_quincenal_hne'        : (val.pago_quincenal_hn + val.pago_quincenal_he)
            });
          } 

        });

        var tabla_bloc_TOTAL_1 = '';

        var input_check_sab_1 = "";
        var input_check_sab_2 = "";

        var input_check_pago_contador = "";

        if (e.data.length === 0) {           
          input_check_sab_1 = `<input class="w-xy-20px" type="checkbox" disabled="disabled">`;
          input_check_sab_2 = `<input class="w-xy-20px" type="checkbox" disabled="disabled">`;
        } else {

          if (count_sabatical_1_total == e.data.length) {
            input_check_sab_1 = `<input class="w-xy-20px" type="checkbox" checked id="checkbox_sabatical_todos_1" onclick="calcular_todos_sabatical_1( );">`;
          } else {
            input_check_sab_1 = `<input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_todos_1" onclick="calcular_todos_sabatical_1( );">`;
          }
          if (count_sabatical_2_total == e.data.length) {
            input_check_sab_2 = `<input class="w-xy-20px" type="checkbox" checked id="checkbox_sabatical_todos_2" onclick="calcular_todos_sabatical_2( );">`;
          } else {
            input_check_sab_2 = `<input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_todos_2" onclick="calcular_todos_sabatical_2( );">`;
          }

          if (count_pago_contador_total == e.data.length) {
            input_check_pago_contador = `<input class="w-xy-20px checkbox_visible" type="checkbox" checked id="checkbox_asignar_pago_contador_todos" onclick="asignar_todos_pago_al_contador( );">`;
          }else{
            input_check_pago_contador = `<input class="w-xy-20px checkbox_visible" type="checkbox" id="checkbox_asignar_pago_contador_todos" onclick="asignar_todos_pago_al_contador( );">`;
          }
        }

        if (cant_dias_asistencia == 14) {
          $('.ir_a_right').show();
          $('.ir_a_bottom').show();
          $('.ir_a_left').show();
          
          tabla_bloc_TOTAL_1 = `<tr> 
            <td class="text-center" colspan="10"></td> 
            <td class="text-center" >
              ${input_check_sab_1}
            </td>
            <td class="text-center" colspan="6"></td> 
            <td class="text-center" >
              ${input_check_sab_2}
            </td>
            <td class="text-center" colspan="7"></td>
            <td class="text-center"> <b>TOTAL</b> </td> 
            <td class="text-center"><span class="pago_total_quincenal font-weight-bold"> ${formato_miles(total_pago.toFixed(2))}</span> </td> 
            <td class="text-center" >
              ${input_check_pago_contador}
            </td>
          </tr>`;
          
        } else { 

          if (cant_dias_asistencia == 7) {
            $('.ir_a_right').hide();
            $('.ir_a_bottom').show();
            $('.ir_a_left').hide();
            tabla_bloc_TOTAL_1 = `<tr> 
              <td class="text-center" colspan="10"></td> 
              <td class="text-center" >
                ${input_check_sab_1} 
              </td> 
              <td class="text-center" colspan="7"></td>           
              <td class="text-center"> <b>TOTAL</b> </td> 
              <td class="text-center"><span class="pago_total_quincenal font-weight-bold"> ${formato_miles(total_pago.toFixed(2))}</span> </td>
              <td class="text-center" >
                ${input_check_pago_contador}
              </td> 
            </tr>`;
            
          } else {

            tabla_bloc_TOTAL_1 = `<tr> <td class="text-center" colspan="25"></td> <td class="text-center"> <b>TOTAL</b> </td> <td class="text-center"><span  class="pago_total_quincenal font-weight-bold"> ${formato_miles(total_pago.toFixed(2))}</span> </td> </tr>`;
            
          }
        }

        $('.data-dia-semana').html(table_dia_semana); 
        $(".data_table_body").append(tabla_bloc_TOTAL_1);

        $("#ver_asistencia").show();   
        $('#cargando-registro-asistencia').hide();        
       
      }           
    } else {
      ver_errores(e);
    }   
    
    // VALIDAR LA CARGA
    if (class_btn == '.btn_mostrar_hne') {
      $(class_btn).html('HNE');
    } else if (class_btn == '.btn_mostrar_hn') {
      $(class_btn).html('HN');
    } else if (class_btn == '.btn_mostrar_he') {
      $(class_btn).html('HE');
    }    
    $('[data-toggle="tooltip"]').tooltip();
    //scroll segun tamaño
    scroll_tabla_asistencia();

  }).fail( function(e) { ver_errores(e); } ); //end post -
  
  $('[data-toggle="tooltip"]').tooltip();

  count_dias_asistidos = 0;  horas_nomr_total = 0;   horas_extr_total = 0;  
}

function mostrar_he(ids_q_asistencia, f1, f2, i, cant_dias_asistencia, class_btn, tipo_hora) {  
  console.log(f1, f2, i, cant_dias_asistencia);
  mostrar_form_table(2); 
  select_dia_multiple(f1, f2);
  var nube_idproyect =localStorage.getItem('nube_idproyecto');

  var data_export = `?idproyecto=${nube_idproyect}&ids_q_asistencia=${ids_q_asistencia}&f1=${format_a_m_d(f1)}&f2=${format_a_m_d(f2)}&n_f_i_p=${n_f_i_p}&n_f_f_p=${n_f_f_p}&i=${i}&cant_dias_asistencia=${cant_dias_asistencia}`;
  $('#btn-export-qs').attr(`href`, `../reportes/export_xlsx_format_asistencia_he.php${data_export}`).attr(`onclick`, ``);

  // ocultamos las tablas  
  $("#ver_asistencia").hide();
  $('#cargando-registro-asistencia').show().html(`<div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div>`);

  // cambiamos el valor del colspan
  $("#dias_asistidos_s_q").attr("colspan", cant_dias_asistencia);

  // cambiamos el TABLE-HEAD tipo de pago
  if (cant_dias_asistencia == 7) { $(".head_pago_q_s").html("Pago <br> semanal"); }else{ $(".head_pago_q_s").html("Pago <br> quincenal"); }

  $("#btn-editar").show(); $("#btn-guardar-hn").hide(); $("#btn-guardar-he").hide();  

  // vaciamos el array
  array_asistencia = []; array_trabajador = [];
  array_sabatical_1 = []; array_sabatical_2 = [];
  array_pago_contador = []; array_agregar_horas = []

  // pintamos el botón
  pintar_boton_selecionado(i);
  
  var table_dia_semana = ""; 

  var count_bloque_q_s = 1; var total_pago = 0;  
  
  $.post("../ajax/asistencia_obrero.php?op=ver_datos_quincena", {'ids_q_asistencia':ids_q_asistencia, f1:format_a_m_d(f1),f2:format_a_m_d(f2),'nube_idproyect':nube_idproyect, 'n_f_i_p': n_f_i_p, 'n_f_f_p': n_f_f_p}, function (e, status) {
        
    e =JSON.parse(e); console.log(e);   

    $(".data_table_body").html('');  

    var count_sabatical_1_total = 0;
    var count_sabatical_2_total = 0;

    var count_pago_contador_total = 0;
    
    if (e.status == true) {
      if (e.data.length === 0) {
        $('#cargando-registro-asistencia').html(`<div class="col-lg-12 text-center"><h4>─ No hay OBREROS asignado a este proyecto ─</h4></div>`);
      }else{        
        e.data[0].asistencia.forEach((val2, key2) => {
          table_dia_semana = table_dia_semana.concat(`<th class="p-x-12px py-0"> ${val2.fecha_asistencia.substr(8,2)} <br> ${extraer_dia_semana(val2.fecha_asistencia)} </th>`);          
        });

        e.data.forEach((val, key) => {

          var estado_dentro_de_obra = true;
          count_bloque_q_s = 1;
          var count_dias_asistidos = 0; var horas_total = 0; var horas_nomr_total = 0; var horas_extr_total = 0; var sabatical = 0;
          
          var tabla_bloc_HN_asistencia_3=""; var tabla_bloc_HE_asistencia_2 =""; var estado_hallando_sabado = true;

          val.asistencia.forEach((val2, key2) => {

            var weekday = extraer_dia_semana(val2.fecha_asistencia); //console.log(weekday);
            var class_val_x_dia = `${extraer_dia_semana(val2.fecha_asistencia)}_${extraer_dia_mes(val2.fecha_asistencia)}_${extraer_mes_number(val2.fecha_asistencia)}`;

            horas_total      = horas_total + parseFloat(val2.horas_normal_dia) + parseFloat(val2.horas_extras_dia);
            horas_nomr_total = horas_nomr_total + parseFloat(val2.horas_normal_dia);
            horas_extr_total = horas_extr_total + parseFloat(val2.horas_extras_dia);

            var data_che = `'${format_d_m_a(val2.fecha_asistencia)}', '${val.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${val.sueldo_diario}', '${val.sueldo_hora}', '${e.data.length}', '${val.sabatical_manual_1}', '${val.sabatical_manual_2}', '${tipo_hora}'`;
            var class_val_x_dia = `${extraer_dia_semana(val2.fecha_asistencia)}_${extraer_dia_mes(val2.fecha_asistencia)}_${extraer_mes_number(val2.fecha_asistencia)}`;
            var class_unico     = `input_HE_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`;
            var class_x_trab    = `input_HE_${val.idtrabajador_por_proyecto}_${key2}`;
            var class_sueldo_d  = `input_PD_${val.idtrabajador_por_proyecto}_${key2}`;
            var color_td = val2.horas_extras_dia >= 8 ? 'bg-color-28a74540': (val2.horas_extras_dia < 8 && val2.horas_extras_dia > 0  ? 'bg-color-28a74540' : 'bg-color-ff000040' ) ; 

            if (weekday != 'sa') {
              if (val2.dia_regular == true) {
                tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center bg-color-acc3c7" data-toggle="tooltip" data-original-title="${val2.sueldo_diario}"> 
                  <span class=" " >-</span> 
                  <input class="${class_unico}" type="hidden" value="0"> 
                  <input type="hidden" class="${class_sueldo_d}" value="0">
                </td>`);                
                tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(``);
              } else {
                tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center ${color_td}" rowspan="2" data-toggle="tooltip" data-original-title="${val2.sueldo_diario}"> 
                  <span class="span_asist span_HE_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}" >${val2.horas_extras_dia}</span> 
                  <input class="input_asist w-35px he_multiple ${class_val_x_dia} ${class_x_trab} ${class_unico} hidden" type="text"  onkeyup="calcular_he(${data_che});" value="${val2.horas_extras_dia}">
                  <input type="hidden" class="${class_sueldo_d}" value="${val2.sueldo_diario}">
                </td>`);
                
                tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(``);               
              }
            } else {
              // SABATICALES
              tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical">  </td>`);                      
            }   

            // recoge sabados
            array_asistencia.push( { 
              'idasistencia_trabajador': val2.idasistencia_trabajador,
              'id_trabajador'     : val.idtrabajador_por_proyecto, 
              'fecha_asistida'    : format_d_m_a(val2.fecha_asistencia), 
              'class_input_hn'    :`input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`, 
              'class_input_he'    :`input_HE_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`,
              'cant_dias'         : cant_dias_asistencia,   
              'cant_trabajador'   : e.data.length,
              'sueldo_hora'       : val.sueldo_hora,
              'sabatical_manual_1': val.sabatical_manual_1,
              'sabatical_manual_2': val.sabatical_manual_2,
              'dia_regular'       : val2.dia_regular,
              'sueldo_diario'     : val2.sueldo_diario
            } );
            // no recoge sabados
            array_agregar_horas.push( { 
              'idasistencia_trabajador': val2.idasistencia_trabajador,
              'id_trabajador'     : val.idtrabajador_por_proyecto, 
              'fecha_asistida'    : format_d_m_a(val2.fecha_asistencia), 
              'class_input_hn'    :`input_HN_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`, 
              'class_input_he'    :`input_HE_${val.idtrabajador_por_proyecto}_${format_d_m_a(val2.fecha_asistencia)}`,
              'cant_dias'         : cant_dias_asistencia,   
              'cant_trabajador'   : e.data.length,
              'sueldo_diario'     : val.sueldo_diario,
              'sueldo_hora'       : val.sueldo_hora,
              'sabatical_manual_1': val.sabatical_manual_1,
              'sabatical_manual_2': val.sabatical_manual_2
            } );
          });

          // asignamos lo trabajadores a un "array"
          array_trabajador.push({ 
            'id_trabajador':val.idtrabajador_por_proyecto, 
            'idresumen_q_s_asistencia': val.idresumen_q_s_asistencia,
            'nombre_trabjador':val.nombres,
            'sueldo_hora':val.sueldo_hora,
            'array_asistencia': array_asistencia
          });   

          array_asistencia = [];

          var tabla_bloc_HN_trabaj_2 =  `<td rowspan="2" class="center-vertical">${val.nombres}</td> 
            <td rowspan="2" class="center-vertical">${val.nombre_ocupacion} 
            <!-- <br> ${ (val.estado_trabajador==1?(estado_dentro_de_obra==false?'<span class="text-center badge badge-danger">Fecha terminada</span>':'<span class="text-center badge badge-success">Activado</span>'):'<span class="text-center badge badge-danger">Desactivado</span>')} -->
          </td>`;    

          var tabla_bloc_HN_total_hora_4 =  `<td class="text-center center-vertical"> 
            <span  class="total_HE_${val.idtrabajador_por_proyecto}">${val.total_he}</span> 
          </td>`;
          
          var tabla_bloc_HN_total_dia_5 = `<td class="text-center center-vertical" rowspan="2" >
            <span  class="dias_asistidos_${val.idtrabajador_por_proyecto}">${val.total_dias_asistidos_he } </span>
          </td>`;

          var tabla_bloc_HN_sueldos_6 = `<td class="text-center center-vertical" rowspan="2">${formato_miles(val.sueldo_semanal)}</td>
          <td class="text-center center-vertical" rowspan="2">${val.sueldo_diario}</td>
          <td class="text-center center-vertical" rowspan="2">${val.sueldo_hora}</td>`;

          var tabla_bloc_HN_sabatical_7 =  `<td class="text-center center-vertical" rowspan="2">
            <span  class="sabatical_${val.idtrabajador_por_proyecto}">${sabatical}</span>
          </td>`;
          
          var tabla_bloc_HN_pago_parcial_8 = `<td class="text-center center-vertical" rowspan="2"> 
            <span  class="pago_parcial_HE_${val.idtrabajador_por_proyecto}"> ${formato_miles(val.pago_parcial_he)}</span>
          </td>`;
          
          var fechas_adicional = "";
          
          // validamos si existe una suma_adicional 
          if (val.idresumen_q_s_asistencia == "") { fechas_adicional = format_a_m_d(f1); } else { fechas_adicional = val.fecha_registro; }

          var tabla_bloc_HN_descuent_9 = `<td rowspan="2" class="text-center center-vertical">
            <span class="span_asist" >${val.adicional_descuento_he}</span> 
            <input class="w-45px input_asist hidden adicional_descuento_${val.idtrabajador_por_proyecto}" onkeyup="adicional_descuento('${e.data.length}', '${val.idtrabajador_por_proyecto}', 'he');" type="text" value="${val.adicional_descuento_he}" autocomplete="off" > 
            <span class="badge badge-info float-right cursor-pointer shadow-1px06rem09rem-rgb-52-174-193-77" data-toggle="tooltip" data-original-title="Por descuento" onclick="modal_adicional_descuento( '${val.idresumen_q_s_asistencia}', '${val.idtrabajador_por_proyecto}', 'he');"><i class="far fa-eye"></i></span>
          </td>`;
          
          var tabla_bloc_HN_pago_total_10 = `<td rowspan="2" class="text-center center-vertical"> 
            <span  class="val_pago_quincenal_${key+1} pago_quincenal_${val.idtrabajador_por_proyecto}"> ${formato_miles( val.pago_quincenal_he )} </span> 
          </td>`;

          var tabla_bloc_envio_contador_11 = "";

          // validamos el el envio al contador
          if (val.estado_envio_contador == "") {
            tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox"  id="checkbox_asignar_pago_contador_${val.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${val.idtrabajador_por_proyecto}', '${val.nombres}', '${val.idresumen_q_s_asistencia}', '${val.pago_quincenal_he}');"> </td>`;        
          } else if (val.estado_envio_contador == "0") {            
            tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox"  id="checkbox_asignar_pago_contador_${val.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${val.idtrabajador_por_proyecto}', '${val.nombres}', '${val.idresumen_q_s_asistencia}', '${val.pago_quincenal_he}');"> </td>`;                  
          } else if (val.estado_envio_contador == "1") {  
            tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox" checked id="checkbox_asignar_pago_contador_${val.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${val.idtrabajador_por_proyecto}', '${val.nombres}', '${val.idresumen_q_s_asistencia}', '${val.pago_quincenal_he}');"> </td>`;                  
            count_pago_contador_total++;            
          }

          // acumulamos el total de pagos
          total_pago = total_pago + parseFloat( val.pago_quincenal_he );
          
          var tabla_bloc_HN_1 = `<tr>
            <td class="" rowspan="2"><b>${key+1}</b></td>
            <td class="" rowspan="2">H/E</td>
            ${tabla_bloc_HN_trabaj_2}
            ${tabla_bloc_HN_asistencia_3 }
            ${tabla_bloc_HN_total_hora_4}
            ${tabla_bloc_HN_total_dia_5} 
            ${tabla_bloc_HN_sueldos_6} 
            ${tabla_bloc_HN_sabatical_7} 
            ${tabla_bloc_HN_pago_parcial_8} 
            ${tabla_bloc_HN_descuent_9}
            ${tabla_bloc_HN_pago_total_10}
            ${tabla_bloc_envio_contador_11}
          </tr>`;  

          var tabla_bloc_HE_1 = `<tr> </tr>`;

          //Unimos y mostramos los bloques separados
          $(".data_table_body").append(tabla_bloc_HN_1 + tabla_bloc_HE_1);

          // asignamos pago al contador a un "array"
          if (parseFloat(val.pago_quincenal_hn + val.pago_quincenal_he) > 0) {
            array_pago_contador.push({           
              'fechas_adicional'          : fechas_adicional,
              'idtrabajador_por_proyecto' : val.idtrabajador_por_proyecto,
              'nombres'                   : val.nombres,
              'idresumen_q_s_asistencia'  : val.idresumen_q_s_asistencia,
              'pago_quincenal_hn'         : val.pago_quincenal_hn,
              'pago_quincenal_he'         : val.pago_quincenal_he,
              'pago_quincenal_hne'        : (val.pago_quincenal_hn + val.pago_quincenal_he)
            });
          } 

        });

        var tabla_bloc_TOTAL_1 = '';

        var input_check_sab_1 = "";
        var input_check_sab_2 = "";

        var input_check_pago_contador = "";

        if (e.data.length === 0) {           
          input_check_sab_1 = `<input class="w-xy-20px" type="checkbox" disabled="disabled">`;
          input_check_sab_2 = `<input class="w-xy-20px" type="checkbox" disabled="disabled">`;
        } else {

          if (count_sabatical_1_total == e.data.length) {
            input_check_sab_1 = `<input class="w-xy-20px" type="checkbox" checked id="checkbox_sabatical_todos_1" onclick="calcular_todos_sabatical_1( );">`;
          } else {
            input_check_sab_1 = `<input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_todos_1" onclick="calcular_todos_sabatical_1( );">`;
          }
          if (count_sabatical_2_total == e.data.length) {
            input_check_sab_2 = `<input class="w-xy-20px" type="checkbox" checked id="checkbox_sabatical_todos_2" onclick="calcular_todos_sabatical_2( );">`;
          } else {
            input_check_sab_2 = `<input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_todos_2" onclick="calcular_todos_sabatical_2( );">`;
          }

          if (count_pago_contador_total == e.data.length) {
            input_check_pago_contador = `<input class="w-xy-20px checkbox_visible" type="checkbox" checked id="checkbox_asignar_pago_contador_todos" onclick="asignar_todos_pago_al_contador( );">`;
          }else{
            input_check_pago_contador = `<input class="w-xy-20px checkbox_visible" type="checkbox" id="checkbox_asignar_pago_contador_todos" onclick="asignar_todos_pago_al_contador( );">`;
          }
        }

        if (cant_dias_asistencia == 14) {
          $('.ir_a_right').show();
          $('.ir_a_bottom').show();
          $('.ir_a_left').show();
          
          tabla_bloc_TOTAL_1 = `<tr> 
            <td class="text-center" colspan="10"></td> 
            <td class="text-center" >
              ${input_check_sab_1}
            </td>
            <td class="text-center" colspan="6"></td> 
            <td class="text-center" >
              ${input_check_sab_2}
            </td>
            <td class="text-center" colspan="7"></td>
            <td class="text-center"> <b>TOTAL</b> </td> 
            <td class="text-center"><span class="pago_total_quincenal font-weight-bold"> ${formato_miles(total_pago.toFixed(2))}</span> </td> 
            <td class="text-center" >
              ${input_check_pago_contador}
            </td>
          </tr>`;
          
        } else { 

          if (cant_dias_asistencia == 7) {
            $('.ir_a_right').hide();
            $('.ir_a_bottom').show();
            $('.ir_a_left').hide();
            tabla_bloc_TOTAL_1 = `<tr> 
              <td class="text-center" colspan="10"></td> 
              <td class="text-center" >
                ${input_check_sab_1} 
              </td> 
              <td class="text-center" colspan="7"></td>           
              <td class="text-center"> <b>TOTAL</b> </td> 
              <td class="text-center"><span class="pago_total_quincenal font-weight-bold"> ${formato_miles(total_pago.toFixed(2))}</span> </td>
              <td class="text-center" >
                ${input_check_pago_contador}
              </td> 
            </tr>`;
            
          } else {

            tabla_bloc_TOTAL_1 = `<tr> <td class="text-center" colspan="25"></td> <td class="text-center"> <b>TOTAL</b> </td> <td class="text-center"><span  class="pago_total_quincenal font-weight-bold"> ${formato_miles(total_pago.toFixed(2))}</span> </td> </tr>`;
            
          }
        }

        $('.data-dia-semana').html(table_dia_semana); 
        $(".data_table_body").append(tabla_bloc_TOTAL_1);

        $("#ver_asistencia").show();   
        $('#cargando-registro-asistencia').hide();        
       
      }           
    } else {
      ver_errores(e);
    }   
    
    // VALIDAR LA CARGA
    if (class_btn == '.btn_mostrar_hne') {
      $(class_btn).html('HNE');
    } else if (class_btn == '.btn_mostrar_hn') {
      $(class_btn).html('HN');
    } else if (class_btn == '.btn_mostrar_he') {
      $(class_btn).html('HE');
    }    
    $('[data-toggle="tooltip"]').tooltip();
    //scroll segun tamaño
    scroll_tabla_asistencia();

  }).fail( function(e) { ver_errores(e); } ); //end post -
  
  $('[data-toggle="tooltip"]').tooltip();

  count_dias_asistidos = 0;  horas_nomr_total = 0;   horas_extr_total = 0;  
}

// Calculamos las: Horas normal/extras,	Días asistidos,	Sueldo Mensual,	Jornal,	Sueldo hora,	Sabatical,	Pago parcial,	Adicional/descuento,	Pago quincenal
function calcular_hn( fecha, id_trabajador, cant_dias_asistencia, sueldo_diario, sueldo_hora, cant_trabajador , sabatical_manual_1, sabatical_manual_2, tipo_hora) {
 
  var suma_hn = 0; var dias_asistidos = 0, sueldo_ant = 0 ; var dias_1_sueldo = 0, dias_2_sueldo = 0; var adicional_descuento = 0;  var pago_parcial_v2 = 0;
  var sueldo_1 = 0, sueldo_2 = 0;
  // validamos el adicional descuento
  if (parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) >= 0 || parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) <= 0 ) {
    adicional_descuento =   parseFloat($(`.adicional_descuento_${id_trabajador}`).val()); 
  } else {
    adicional_descuento = 0;
    toastr.error(`El dato adicional/descuento:: <h3 class=""> ${$(`.adicional_descuento_${id_trabajador}`).val()} </h3> no es NUMÉRICO, ingrese un número cero o un positivo o un negativo.`);    
  }  

  // Val domingo
  var domingo_1 = $(`.input_HN_${id_trabajador}_0`).val(); 
  // calcular pago quincenal
  for (let index = 0; index < parseInt(cant_dias_asistencia); index++) { 
    var val_input_hn = $(`.input_HN_${id_trabajador}_${index}`).val() == 0 || $(`.input_HN_${id_trabajador}_${index}`).val() == '' || $(`.input_HN_${id_trabajador}_${index}`).val() == null ? 0 : parseFloat($(`.input_HN_${id_trabajador}_${index}`).val());
    var val_sueldo = $(`.input_PD_${id_trabajador}_${index}`).val() == 0 || $(`.input_PD_${id_trabajador}_${index}`).val() == '' || $(`.input_PD_${id_trabajador}_${index}`).val() == null ? 0 : parseFloat($(`.input_PD_${id_trabajador}_${index}`).val());
    if (val_input_hn > 0 ) { 
      suma_hn = suma_hn + val_input_hn;      
      if (index == 0) {  sueldo_ant = val_sueldo;  }    
      if (sueldo_ant == val_sueldo) { dias_1_sueldo++; sueldo_ant = val_sueldo; sueldo_1 = val_sueldo; }  else{ sueldo_2 = val_sueldo; } 
    }
    console.log(`dias_asistidos: ${dias_asistidos}  | suma_hn: ${suma_hn} sueldo_ant: ${sueldo_ant} | sueldo_ant: ${val_sueldo} | dias_1_sueldo: ${dias_1_sueldo}`);  
  }  
  
  if (domingo_1 >=4 && suma_hn>=30 ) { dias_asistidos = redondear_mas((suma_hn / 8)); } else if (suma_hn>=36 ) { dias_asistidos = roundToHalf((suma_hn / 8)) -0.5; }else{ dias_asistidos = roundToHalf((suma_hn / 8)); }  
  
  dias_2_sueldo = dias_asistidos - dias_1_sueldo; console.log(`${dias_asistidos} - ${dias_1_sueldo} = dias_2_sueldo: ${dias_2_sueldo}`);
  if ( dias_asistidos >= 0.5 && dias_asistidos <= 1) {
    pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_asistidos) * sueldo_1);
  } else if (dias_asistidos >= 1.5 && dias_asistidos <= 3.5 ) {
    pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_asistidos) * sueldo_1); console.log(` pago_parcial_v2: ${pago_parcial_v2} + ( ${dias_asistidos} * ${sueldo_1} )`);
  } else {
    pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_1_sueldo) * sueldo_1); console.log(` pago_parcial_v2: ${pago_parcial_v2} + ( ${dias_1_sueldo} * ${sueldo_1} )`);
  }    
  
  pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_2_sueldo) * sueldo_2); console.log(` pago_parcial_v2: ${pago_parcial_v2} + ( ${dias_2_sueldo} * ${sueldo_2} )`);
  //  pago_parcial_HN_1
  $(`.total_HN_${id_trabajador}`).html(suma_hn.toFixed(1));  
  
  $(`.dias_asistidos_${id_trabajador}`).html( `${dias_asistidos}`);  

  // asignamos los pagos parciales  
  $(`.pago_parcial_HN_${id_trabajador}`).html(formato_miles(pago_parcial_v2));

  // calculamos el pago quincenal con: Pago parcial,	Adicional/descuento
  var pago_quincenal_v2 = pago_parcial_v2 + adicional_descuento;
  $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(pago_quincenal_v2));
  console.log(pago_quincenal_v2);


  var suma_total_quincena = 0;

  for (let k = 1; k <= parseInt(cant_trabajador); k++) { suma_total_quincena = suma_total_quincena + parseFloat(quitar_formato_miles($(`.val_pago_quincenal_${k}`).text())); }

  $(`.pago_total_quincenal`).html(formato_miles(suma_total_quincena));
}

function calcular_he( fecha, id_trabajador, cant_dias_asistencia, sueldo_diario, sueldo_hora, cant_trabajador , sabatical_manual_1, sabatical_manual_2, tipo_hora) {
 
  var suma_he = 0; var dias_asistidos = 0, sueldo_ant = 0 ; var dias_1_sueldo = 0, dias_2_sueldo = 0; var adicional_descuento = 0;  var pago_parcial_v2 = 0;
  var sueldo_1 = 0, sueldo_2 = 0;

  // Val domingo
  var domingo_1 = $(`.input_HE_${id_trabajador}_0`).val(); 
  // calcular pago quincenal
  for (let index = 0; index < parseInt(cant_dias_asistencia); index++) { 
    var val_input_he = $(`.input_HE_${id_trabajador}_${index}`).val() == 0 || $(`.input_HE_${id_trabajador}_${index}`).val() == '' || $(`.input_HE_${id_trabajador}_${index}`).val() == null ? 0 : parseFloat($(`.input_HE_${id_trabajador}_${index}`).val());
    var val_sueldo = $(`.input_PD_${id_trabajador}_${index}`).val() == 0 || $(`.input_PD_${id_trabajador}_${index}`).val() == '' || $(`.input_PD_${id_trabajador}_${index}`).val() == null ? 0 : parseFloat($(`.input_PD_${id_trabajador}_${index}`).val());
    if (val_input_he > 0 ) { 
      suma_he = suma_he + val_input_he;
      // console.log(`dias_asistidos: ${dias_asistidos} | dias_asistidos_ant:${dias_asistidos_ant} | suma_he: ${suma_he} | dias_separados: ${dias_separados} | pago_parcial_v2:${pago_parcial_v2}`);   
      if (index == 0) {  sueldo_ant = val_sueldo;  }    
      if (sueldo_ant == val_sueldo) { dias_1_sueldo++; sueldo_ant = val_sueldo; sueldo_1 = val_sueldo; }  else{ sueldo_2 = val_sueldo; } 
    }
    //console.log(`sueldo_ant: ${sueldo_ant} | sueldo_ant: ${val_sueldo} | dias_1_sueldo: ${dias_1_sueldo}`);  
  }  
  
  if (domingo_1 >=4 && suma_he>=30 ) { dias_asistidos = redondear_mas((suma_he / 8)); } else if (suma_he>=36 ) { dias_asistidos = roundToHalf((suma_he / 8)) -0.5; }else{ dias_asistidos = roundToHalf((suma_he / 8)); }  

  dias_2_sueldo = dias_asistidos - dias_1_sueldo;
  if (dias_asistidos >= 1.5 && dias_asistidos <= 3.5 ) {
    pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_asistidos) * sueldo_1);
  } else {
    pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_1_sueldo) * sueldo_1);
  }  
  pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_2_sueldo) * sueldo_2);

  //  pago_parcial_HN_1  
  $(`.total_HE_${id_trabajador}`).html(suma_he.toFixed(1));

  $(`.dias_asistidos_${id_trabajador}`).html( `${dias_asistidos}`);

  // asignamos los pagos parciales
  $(`.pago_parcial_HE_${id_trabajador}`).html(formato_miles(pago_parcial_v2));  

  // calculamos el pago quincenal con: Pago parcial,	Adicional/descuento
  var pago_quincenal_v2 = pago_parcial_v2 ;
  $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(pago_quincenal_v2));

  var suma_total_quincena = 0;

  for (let k = 1; k <= parseInt(cant_trabajador); k++) {    
    suma_total_quincena = suma_total_quincena + parseFloat(quitar_formato_miles($(`.val_pago_quincenal_${k}`).text())); 
  }

  $(`.pago_total_quincenal`).html(formato_miles(suma_total_quincena));
}

function pintar_boton_selecionado(i) {
  localStorage.setItem('i', i); //enviamos el ID-BOTON al localStorage
  // validamos el id para pintar el boton
  if (localStorage.getItem('boton_id')) {

    let id = localStorage.getItem('boton_id'); //console.log('id-nube-boton '+id); 
    
    $("#boton-" + id).removeClass('click-boton');

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  } else {

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  }
}

function despintar_btn_select() {  
  if (localStorage.getItem('boton_id')) { let id = localStorage.getItem('boton_id'); $("#boton-" + id).removeClass('click-boton'); }
}

// GUARDAR - FECHAS
function guardar_fechas_asistencia_hn() {  

  var array_datos_asistencia = []; var array_resumen_qs = [];
  // console.log(array_asistencia); console.log(array_trabajador); 

  // rellenamos el array EXTRAS para la bd "RESUMEN Q S ASISTENCIA"
  array_trabajador.forEach((val,key) => {    

    // rellenamos el array ASISTENCIA para la bd "ASISTENCIA TRABAJADOR"
    val.array_asistencia.forEach((val2,key2) => {
      
      array_datos_asistencia.push({ 
        'idasistencia_trabajador': val2.idasistencia_trabajador,
        'id_trabajador'   :val2.id_trabajador,        
        'fecha_asistida'  :format_a_m_d(val2.fecha_asistida),
        'nombre_dia'      :extraer_dia_semana_completo(format_a_m_d(val2.fecha_asistida)),
        'horas_normal_dia':$(`.input_HN_${val2.id_trabajador}_${val2.fecha_asistida}`).val(),
        'pago_normal_dia' :(parseFloat($(`.input_HN_${val2.id_trabajador}_${val2.fecha_asistida}`).val()) * val2.sueldo_hora).toFixed(2) ,    
        'dia_regular'     :val2.dia_regular ,   
        'sueldo_diario'   :val2.sueldo_diario ,      
      });
    });

    array_resumen_qs.push({
      'id_trabajador'       :val.id_trabajador,
      'idresumen_q_s_asistencia': val.idresumen_q_s_asistencia,
      'ids_q_asistencia'    :ids_q_asistencia_r,
      'fecha_q_s_inicio'    :format_a_m_d(f1_r),
      'fecha_q_s_fin'       :format_a_m_d(f2_r),
      'num_semana'          : (parseInt(i_r) + 1),
      'total_hn'            :quitar_formato_miles($(`.total_HN_${val.id_trabajador}`).text()),
      'dias_asistidos_hn'   :$(`.dias_asistidos_${val.id_trabajador}`).text(),
      'sabatical'           :$(`.sabatical_${val.id_trabajador}`).text(),
      'pago_parcial_hn'     :quitar_formato_miles($(`.pago_parcial_HN_${val.id_trabajador}`).text()),
      'pago_parcial_he'     :quitar_formato_miles($(`.pago_parcial_HE_${val.id_trabajador}`).text()),
      'pago_parcial_hne'    :quitar_formato_miles($(`.pago_parcial_HNE_${val.id_trabajador}`).text()),
      'adicional_descuento' :$(`.adicional_descuento_${val.id_trabajador}`).val(),
      'pago_quincenal_hn'   :quitar_formato_miles($(`.pago_quincenal_${val.id_trabajador}`).text()),
      'array_datos_asistencia':array_datos_asistencia
    });
    array_datos_asistencia=[];
  }); 

  // console.log(array_trabajador);
  console.log(array_resumen_qs);
  console.log(array_datos_asistencia);

  show_hide_span_input(2);  

  // abrimos el modal cargando
  $("#modal-cargando").modal("show");

  $.ajax({
    url: "../ajax/asistencia_obrero.php?op=guardar_y_editar_asistencia_hn",
    type: "POST",
    data:  {
      // 'asistencia': JSON.stringify(array_datos_asistencia), 
      'resumen_qs':JSON.stringify(array_resumen_qs),
      'fecha_inicial':format_a_m_d(f1_r), 
      'fecha_final':format_a_m_d(f2_r)
    },
    // contentType: false, processData: false,
    success: function (e) {              
      try {
        e = JSON.parse(e);  console.log(e);
        if (e.status == true) {
          datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r); tbla_principal(localStorage.getItem('nube_idproyecto'));           
          $("#icono-respuesta").html(`<div class="swal2-icon swal2-success swal2-icon-show" style="display: flex;"> <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div> <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span> <div class="swal2-success-ring"></div> <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div> <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div> </div>  <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Correcto!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">Asistencia registrada correctamente</div> </div>` );
          // Swal.fire("Correcto!", "Asistencia registrada correctamente", "success");          
          $(".progress-bar").addClass("bg-success"); $("#barra_progress").text("100% Completado!");          
        }else{
          $("#icono-respuesta").html(`<div class="swal2-icon swal2-error swal2-icon-show" style="display: flex;"> <span class="swal2-x-mark"> <span class="swal2-x-mark-line-left"></span> <span class="swal2-x-mark-line-right"></span> </span> </div> <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Error!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">${e.data}</div> </div>`);
          $(".progress-bar").addClass("bg-danger"); $("#barra_progress").text("100% Error!");
          // Swal.fire("Error!", datos, "error");
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});
          $("#barra_progress").text(percentComplete.toFixed(2)+" %");
          if (percentComplete === 100) {  setTimeout(l_m, 600); }
        }
      }, false);
      return xhr;
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
  
}

function guardar_fechas_asistencia_he() {  

  var array_datos_asistencia = []; var array_resumen_qs = []; var horas_extras_dia = 0; var pago_horas_extras = 0;
  // console.log(array_asistencia); console.log(array_trabajador); 

  // rellenamos el array EXTRAS para la bd "RESUMEN Q S ASISTENCIA"
  array_trabajador.forEach((val,key) => {    

    // rellenamos el array ASISTENCIA para la bd "ASISTENCIA TRABAJADOR"
    val.array_asistencia.forEach((val2,key2) => {
      horas_extras_dia = 0; pago_horas_extras = 0; 
      if ($(`.input_HE_${val2.id_trabajador}_${val2.fecha_asistida}`).val() == undefined) {
        horas_extras_dia = 0;  pago_horas_extras = 0;
      } else {
        horas_extras_dia = $(`.input_HE_${val2.id_trabajador}_${val2.fecha_asistida}`).val(); 
        pago_horas_extras = (parseFloat($(`.input_HE_${val2.id_trabajador}_${val2.fecha_asistida}`).val()) * val2.sueldo_hora).toFixed(2);
      }
      array_datos_asistencia.push({ 
        'idasistencia_trabajador': val2.idasistencia_trabajador,
        'id_trabajador'   :val2.id_trabajador,        
        'fecha_asistida'  :format_a_m_d(val2.fecha_asistida),
        'nombre_dia'      :extraer_dia_semana_completo(format_a_m_d(val2.fecha_asistida)),
        'horas_extras_dia':horas_extras_dia,
        'pago_horas_extras':pago_horas_extras,
        'dia_regular'     :val2.dia_regular ,   
        'sueldo_diario'   :val2.sueldo_diario ,
      });
    });

    array_resumen_qs.push({
      'id_trabajador'         :val.id_trabajador,
      'idresumen_q_s_asistencia': val.idresumen_q_s_asistencia,
      'ids_q_asistencia'      :ids_q_asistencia_r,
      'fecha_q_s_inicio'      :format_a_m_d(f1_r),
      'fecha_q_s_fin'         :format_a_m_d(f2_r),
      'num_semana'            : (parseInt(i_r) + 1),      
      'total_he'              :quitar_formato_miles($(`.total_HE_${val.id_trabajador}`).text()),
      'dias_asistidos_he'     :$(`.dias_asistidos_${val.id_trabajador}`).text(),
      'pago_parcial_he'       :quitar_formato_miles($(`.pago_parcial_HE_${val.id_trabajador}`).text()),     
      'adicional_descuento'   :$(`.adicional_descuento_${val.id_trabajador}`).val(), 
      'pago_quincenal_he'     :quitar_formato_miles($(`.pago_quincenal_${val.id_trabajador}`).text()),
      'array_datos_asistencia':array_datos_asistencia
    });
    array_datos_asistencia=[];
  }); 

  // console.log(array_trabajador);
  console.log(array_resumen_qs);
  console.log(array_datos_asistencia);

  show_hide_span_input(2);  

  // abrimos el modal cargando
  $("#modal-cargando").modal("show");

  $.ajax({
    url: "../ajax/asistencia_obrero.php?op=guardar_y_editar_asistencia_he",
    type: "POST",
    data:  {
      // 'asistencia': JSON.stringify(array_datos_asistencia), 
      'resumen_qs':JSON.stringify(array_resumen_qs),
      'fecha_inicial':format_a_m_d(f1_r), 
      'fecha_final':format_a_m_d(f2_r)
    },
    // contentType: false, processData: false,
    success: function (e) {              
      try {
        e = JSON.parse(e);  console.log(e);
        if (e.status == true) {

          datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r); tbla_principal(localStorage.getItem('nube_idproyecto')); 
          
          $("#icono-respuesta").html(`<div class="swal2-icon swal2-success swal2-icon-show" style="display: flex;"> <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div> <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span> <div class="swal2-success-ring"></div> <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div> <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div> </div>  <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Correcto!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">Asistencia registrada correctamente</div> </div>` );

          // Swal.fire("Correcto!", "Asistencia registrada correctamente", "success");
          
          $(".progress-bar").addClass("bg-success"); $("#barra_progress").text("100% Completado!");
          
        }else{

          $("#icono-respuesta").html(`<div class="swal2-icon swal2-error swal2-icon-show" style="display: flex;"> <span class="swal2-x-mark"> <span class="swal2-x-mark-line-left"></span> <span class="swal2-x-mark-line-right"></span> </span> </div> <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Error!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">${e.data}</div> </div>`);

          $(".progress-bar").addClass("bg-danger"); $("#barra_progress").text("100% Error!");

          // Swal.fire("Error!", datos, "error");
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});
          $("#barra_progress").text(percentComplete.toFixed(2)+" %");
          if (percentComplete === 100) {  setTimeout(l_m, 600); }
        }
      }, false);
      return xhr;
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
  
}

function cerrar_modal_cargando() {
  $("#modal-cargando").modal("hide");
  $(".progress-bar").removeClass("bg-success bg-danger");
  $(".progress-bar").addClass("progress-bar-striped");
}

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N  P A G O S   C O N T A D O R   M U L T I P L E S  :::::::::::::::::::::::::::::::::::::::..
// GUARDAR - PAGO AL CONTADOR
function asignar_pago_al_contador(fecha_q_s_inicio, id_trabajador_x_proyecto, nombre_trabajador, idresumen_q_s_asistencia, pago_quincenal) {
 
  if (idresumen_q_s_asistencia !== "" && parseFloat(pago_quincenal) > 0) {
     
    if ($(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).is(':checked')) {

      Swal.fire({
        title: "¿Está Seguro de enviar el pago al contador?",
        html:`Se enviara datos de: <b>${nombre_trabajador}</b> al contador, este podra hacer el pago del trabajdor de esta "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, enviar!",
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch(`../ajax/asistencia_obrero.php?op=agregar_quitar_pago_al_contador&idresumen_q_s_asistencia=${idresumen_q_s_asistencia}&estado_envio_contador=1`).then(response => {
            if (!response.ok) { throw new Error(response.statusText) }
            return response.json()
          }).catch(error => { Swal.showValidationMessage(`Request failed: ${error}`); })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value.status) {
            datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r);
            Swal.fire("Enviado!", `El pago de: <b>${nombre_trabajador}</b> a sido enviado con éxito.`, "success");
          } else {
            ver_errores(result.value);
            $(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).prop('checked', false);
          }
        }else{
          $(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).prop('checked', false);
        }
      });  
  
    } else {
  
      Swal.fire({
        title: "¿Está Seguro de ANULAR el pago al contador?",
        html: `Al ANULAR a: <b>${nombre_trabajador}</b>, el contador NO podra hacer el pago del trabajdor de esta "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, anular!",
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch(`../ajax/asistencia_obrero.php?op=agregar_quitar_pago_al_contador&idresumen_q_s_asistencia=${idresumen_q_s_asistencia}&estado_envio_contador=0`).then(response => {
            if (!response.ok) { throw new Error(response.statusText) }
            return response.json()
          }).catch(error => { Swal.showValidationMessage(`Request failed: ${error}`); })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value.status) {
            datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r);
            Swal.fire("Quitado!", `El pago de: <b>${nombre_trabajador}</b> a sido ANULADO con éxito.`, "success");
          } else {
            ver_errores(result.value);
            $(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).prop('checked', true);
          }
        }else{
          $(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).prop('checked', true);
        }
      });
    }
  }else{
    toastr.error(`El trabajador no tiene ningun MONTO registrado, <h5>registre alguno.</h5>`);
    $(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).prop('checked', false);
  }  
}

// GUARDAR - PAGO AL CONTADOR - MULTIPLE
function asignar_todos_pago_al_contador() {

  console.log(array_pago_contador);

  if (array_pago_contador.length === 0) {
    toastr.error(`Los trabajadores no tiene ningun MONTO registrado, <h5>registre alguno.</h5>`);
    $(`#checkbox_asignar_pago_contador_todos`).prop('checked', false);
  } else {   

    var trabajdor = "";

    array_pago_contador.forEach(element => {
      trabajdor = trabajdor.concat(`<li class="text-left font-size-13px">${element.nombres} ─ <b>${formato_miles(element.pago_quincenal_hne)}</b></li>`);
    });

    trabajdor = `<ul>${trabajdor}</ul>`;

    if ($(`#checkbox_asignar_pago_contador_todos`).is(':checked')) {

      Swal.fire({
        title: "¿Está Seguro de enviar el pago al contador?",
        html:`<div class="h-200px border b-radio-9px" style="overflow-y: auto;">${trabajdor}</div> Se enviara <b class="text-success">TODOS</b> datos al contador, este podra hacer el pago del trabajdor de esta "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, enviar!",
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch(`../ajax/asistencia_obrero.php?op=agregar_quitar_pago_al_contador_todos`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({'array_pago_contador': array_pago_contador, 'estado_envio_contador':"1"}) ,
          }).then(response => {
            if (!response.ok) { throw new Error(response.statusText) }
            return response.json()
          }).catch(error => { Swal.showValidationMessage(`Request failed: ${error}`); })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value.status) {
            datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r); 
            tbla_principal(localStorage.getItem('nube_idproyecto'));
            Swal.fire("Asignado!", `Datos enviados al contador con éxito.`, "success");
          } else {
            ver_errores(result.value);
            Swal.fire("Error!", datos, "error");
          }
        }else{
          $(`#checkbox_asignar_pago_contador_todos`).prop('checked', false);
        }
      });  

    } else {

      Swal.fire({
        title: "¿Está Seguro de ANULAR el pago al contador?",
        html: `${trabajdor} Al <b class="text-danger">ANULAR a TODOS</b>, el contador NO podra hacer el pago del trabajdor de esta "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, anular!",
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch(`../ajax/asistencia_obrero.php?op=agregar_quitar_pago_al_contador_todos`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({'array_pago_contador': array_pago_contador, 'estado_envio_contador':"0"}) ,
          }).then(response => {
            if (!response.ok) { throw new Error(response.statusText) }
            return response.json()
          }).catch(error => { Swal.showValidationMessage(`Request failed: ${error}`); })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value.status) {
            datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r); 
            tbla_principal(localStorage.getItem('nube_idproyecto'));
            Swal.fire("Correcto!", `Datos quitados.`, "success");
          } else {
            ver_errores(result.value);
            Swal.fire("Error!", datos, "error");
          }
        }else{
          $(`#checkbox_asignar_pago_contador_todos`).prop('checked', true);
        }
      });
    }
  }
}

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   S A B A T I C A L   M U L T I P L E S  :::::::::::::::::::::::::::::::::::::::..
// GUARDAR - SABATICAL
function calcular_sabatical(fecha, id_trabajador, cant_dias_asistencia, sueldo_diario, sueldo_hora, cant_trabajador , sabatical_manual_1, sabatical_manual_2, tipo_hora, numero_sabado) {
  
  if (estado_editar_asistencia == true) {    
    if ($(`#checkbox_sabatical_${id_trabajador}_${numero_sabado}`).is(':checked')) {
      $(`.input_HN_${id_trabajador}_6`).val('8');  console.log('activar sab');
    } else {  
      $(`.input_HN_${id_trabajador}_6`).val('0'); console.log('desactivar sab');
    }
    calcular_hn( fecha, id_trabajador, cant_dias_asistencia, sueldo_diario, sueldo_hora, cant_trabajador , sabatical_manual_1, sabatical_manual_2, tipo_hora);
  }   
}

// GUARDAR - SABATICAL 1 MULTIPLE
function calcular_todos_sabatical_1() {  
  
  if (estado_editar_asistencia == true) {
    if ($(`#checkbox_sabatical_todos_1`).is(':checked')) {      
      $(`.sab_1`).prop('checked', true);
    } else {  
      $(`.sab_1`).prop('checked', false);
    }    
  }
}

// GUARDAR - SABATICAL 2 MULTIPLE
function calcular_todos_sabatical_2() {
  
  if (estado_editar_asistencia == true) {
    if ($(`#checkbox_sabatical_todos_1`).is(':checked')) {      
      $(`.sab_2`).prop('checked', true);
    } else {  
      $(`.sab_2`).prop('checked', false);
    }    
  }
}

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   H O R A S   M U L T I P L E S  :::::::::::::::::::::::::::::::::::::::..

function modal_horas_multiples() {  
  show_hide_span_input(2)
  $('#modal-agregar-horas-multiples').modal('show');
}

function agregar_horas_multiples(e) {  

  $(".btn-guardar-asistencia").html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`).attr("disabled", true);
  $(".horas-multiples").html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`).attr("disabled", true);

  var horas = $('#horas_multiples').val(); 

  var tipo_hora = $('#tipo_hora').val();
  if (tipo_hora == 'HN') { $('.hn_multiple').val(horas); } else if (tipo_hora == 'HE'){ $('.he_multiple').val(horas); }
  
  var total_progres = array_agregar_horas.length;

  delay(function(){ $("#barra_progress_h_multiple").css({"width": 10+'%'}); $("#barra_progress_h_multiple").text(10+" %"); }, 100 );
  delay(function(){ $("#barra_progress_h_multiple").css({"width": 20+'%'}); $("#barra_progress_h_multiple").text(20+" %"); }, 200 );
  
  array_agregar_horas.forEach((key, indice) => {

    var percentComplete = ((indice+1) / total_progres)*100;
    // $("#barra_progress_h_multiple").css({"width": percentComplete+'%'});  $("#barra_progress_h_multiple").text(percentComplete.toFixed(2)+" %");    
    var intro = document.getElementById('barra_progress_h_multiple'); intro.style.width = percentComplete.toFixed(2)+'%'; intro.innerText = percentComplete.toFixed(2)+'%';
    if (tipo_hora == 'HN') { 
      var datta_he = calcular_todos_hn(horas, key.fecha_asistida, key.id_trabajador,  key.cant_dias, key.sueldo_diario, key.sueldo_hora, key.cant_trabajador , key.sabatical_manual_1, key.sabatical_manual_2, tipo_hora);
    } else if (tipo_hora == 'HE'){ 
      var datta_he = calcular_todos_he(horas, key.fecha_asistida, key.id_trabajador,  key.cant_dias, key.sueldo_diario, key.sueldo_hora, key.cant_trabajador , key.sabatical_manual_1, key.sabatical_manual_2, tipo_hora);
    }
    console.log(percentComplete.toFixed(2) + '%');
  });

  toastr.success(`<h5>${horas} Horas.</h5> Se agregaron a todos los trabajadores.`);
  $(".progress-bar").addClass("bg-success"); $("#barra_progress_h_multiple").text("100% Completado!");

  $(".btn-guardar-asistencia").removeAttr("disabled").html(`<i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar ${convert_minuscula_v2(tipo_hora)}</span>`);
  $(".horas-multiples").removeAttr("disabled").html(`Asignar horas`);

  // $('#modal-agregar-horas-multiples').modal('hide');
  delay(function(){ l_m() }, 2000 );
}

function agregar_horas_por_dia_multiples(e) {  

  $(".btn-guardar-asistencia").html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`).attr("disabled", true);
  $(".horas-por-dia-multiples").html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`).attr("disabled", true);
  var tipo_hora = $('#tipo_hora').val();
  var horas = $('#horas_xd').val(); var dia = $('#dia_xd').val();
  $(`.${dia}`).val(horas);
  var total_progres = array_agregar_horas.length;

  delay(function(){ $("#barra_progress_h_por_dia").css({"width": 10+'%'}).text(10+" %"); }, 100 );
  delay(function(){ $("#barra_progress_h_por_dia").css({"width": 20+'%'}).text(20+" %"); }, 200 );
  
  array_agregar_horas.forEach((key, indice) => {
    var percentComplete = ((indice+1) / total_progres)*100;
    // $("#barra_progress_h_por_dia").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");    
    var intro = document.getElementById('barra_progress_h_por_dia'); intro.style.width = percentComplete.toFixed(2)+'%'; intro.innerText = percentComplete.toFixed(2)+'%';
    if (tipo_hora == 'HN') {
      var datta_hn = calcular_todos_hn(horas, key.fecha_asistida, key.id_trabajador,  key.cant_dias, key.sueldo_diario, key.sueldo_hora, key.cant_trabajador , key.sabatical_manual_1, key.sabatical_manual_2, tipo_hora);      
    } else if (tipo_hora == 'HE'){ 
      var datta_he = calcular_todos_he(horas, key.fecha_asistida, key.id_trabajador,  key.cant_dias, key.sueldo_diario, key.sueldo_hora, key.cant_trabajador , key.sabatical_manual_1, key.sabatical_manual_2, tipo_hora);      
    }
    console.log(percentComplete.toFixed(2) + '%');
  });

  toastr.success(`<h5>${horas} Horas.</h5> Se agregaron a todos los trabajadores.`);
  $(".progress-bar").addClass("bg-success"); $("#barra_progress_h_por_dia").text("100% Completado!");

  $(".btn-guardar-asistencia").removeAttr("disabled").html(`<i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar ${convert_minuscula_v2(tipo_hora)}</span>`);
  $(".horas-por-dia-multiples").removeAttr("disabled").html(`Asignar horas x dia`);

  // $('#modal-agregar-horas-multiples').modal('hide');
  delay(function(){ l_m() }, 2000 );
}

function calcular_todos_hn(horas, fecha, id_trabajador, cant_dias_asistencia, sueldo_diario, sueldo_hora, cant_trabajador , sabatical_manual_1, sabatical_manual_2, tipo_hora) {
  /* para que corra una bala */ 
  var suma_hn = 0; var dias_asistidos = 0, sueldo_ant = 0 ; var dias_1_sueldo = 0, dias_2_sueldo = 0; var adicional_descuento = 0;  var pago_parcial_v2 = 0; var sueldo_1 = 0, sueldo_2 = 0; if (parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) >= 0 || parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) <= 0 ) { adicional_descuento =   parseFloat($(`.adicional_descuento_${id_trabajador}`).val()); } else { adicional_descuento = 0; toastr.error(`El dato adicional/descuento:: <h3 class=""> ${$(`.adicional_descuento_${id_trabajador}`).val()} </h3> no es NUMÉRICO, ingrese un número cero o un positivo o un negativo.`); } var domingo_1 = $(`.input_HN_${id_trabajador}_0`).val(); for (let index = 0; index < parseInt(cant_dias_asistencia); index++) { var val_input_hn = $(`.input_HN_${id_trabajador}_${index}`).val() == 0 || $(`.input_HN_${id_trabajador}_${index}`).val() == '' || $(`.input_HN_${id_trabajador}_${index}`).val() == null ? 0 : parseFloat($(`.input_HN_${id_trabajador}_${index}`).val()); var val_sueldo = $(`.input_PD_${id_trabajador}_${index}`).val() == 0 || $(`.input_PD_${id_trabajador}_${index}`).val() == '' || $(`.input_PD_${id_trabajador}_${index}`).val() == null ? 0 : parseFloat($(`.input_PD_${id_trabajador}_${index}`).val()); if (val_input_hn > 0 ) { suma_hn = suma_hn + val_input_hn; if (index == 0) {  sueldo_ant = val_sueldo;  } if (sueldo_ant == val_sueldo) { dias_1_sueldo++; sueldo_ant = val_sueldo; sueldo_1 = val_sueldo; }  else{ sueldo_2 = val_sueldo; } } } if (domingo_1 >=4 && suma_hn>=30 ) { dias_asistidos = redondear_mas((suma_hn / 8)); } else if (suma_hn>=36 ) { dias_asistidos = roundToHalf((suma_hn / 8)) -0.5; }else{ dias_asistidos = roundToHalf((suma_hn / 8)); } dias_2_sueldo = dias_asistidos - dias_1_sueldo; if (dias_asistidos >= 1.5 && dias_asistidos <= 3.5 ) { pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_asistidos) * sueldo_1); } else { pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_1_sueldo) * sueldo_1); } pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_2_sueldo) * sueldo_2); $(`.total_HN_${id_trabajador}`).html(suma_hn.toFixed(1)); $(`.dias_asistidos_${id_trabajador}`).html( `${dias_asistidos}`); $(`.pago_parcial_HN_${id_trabajador}`).html(formato_miles(pago_parcial_v2)); var pago_quincenal_v2 = pago_parcial_v2 + adicional_descuento; $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(pago_quincenal_v2)); var suma_total_quincena = 0; for (let k = 1; k <= parseInt(cant_trabajador); k++) { suma_total_quincena = suma_total_quincena + parseFloat(quitar_formato_miles($(`.val_pago_quincenal_${k}`).text())); } $(`.pago_total_quincenal`).html(formato_miles(suma_total_quincena)); return true;
}

function calcular_todos_he( horas, fecha, id_trabajador, cant_dias_asistencia, sueldo_diario, sueldo_hora, cant_trabajador , sabatical_manual_1, sabatical_manual_2, tipo_hora) {
  /* para que corra una bala */
  var suma_he = 0; var dias_asistidos = 0, sueldo_ant = 0 ; var dias_1_sueldo = 0, dias_2_sueldo = 0; var adicional_descuento = 0;  var pago_parcial_v2 = 0; var sueldo_1 = 0, sueldo_2 = 0; var domingo_1 = $(`.input_HE_${id_trabajador}_0`).val(); for (let index = 0; index < parseInt(cant_dias_asistencia); index++) { var val_input_he = $(`.input_HE_${id_trabajador}_${index}`).val() == 0 || $(`.input_HE_${id_trabajador}_${index}`).val() == '' || $(`.input_HE_${id_trabajador}_${index}`).val() == null ? 0 : parseFloat($(`.input_HE_${id_trabajador}_${index}`).val()); var val_sueldo = $(`.input_PD_${id_trabajador}_${index}`).val() == 0 || $(`.input_PD_${id_trabajador}_${index}`).val() == '' || $(`.input_PD_${id_trabajador}_${index}`).val() == null ? 0 : parseFloat($(`.input_PD_${id_trabajador}_${index}`).val()); if (val_input_he > 0 ) { suma_he = suma_he + val_input_he; if (index == 0) {  sueldo_ant = val_sueldo;  } if (sueldo_ant == val_sueldo) { dias_1_sueldo++; sueldo_ant = val_sueldo; sueldo_1 = val_sueldo; }  else{ sueldo_2 = val_sueldo; } } } if (domingo_1 >=4 && suma_he>=30 ) { dias_asistidos = redondear_mas((suma_he / 8)); } else if (suma_he>=36 ) { dias_asistidos = roundToHalf((suma_he / 8)) -0.5; }else{ dias_asistidos = roundToHalf((suma_he / 8)); } dias_2_sueldo = dias_asistidos - dias_1_sueldo; if (dias_asistidos >= 1.5 && dias_asistidos <= 3.5 ) { pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_asistidos) * sueldo_1); } else { pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_1_sueldo) * sueldo_1); } pago_parcial_v2 = pago_parcial_v2 + (parseFloat(dias_2_sueldo) * sueldo_2); $(`.total_HE_${id_trabajador}`).html(suma_he.toFixed(1)); $(`.dias_asistidos_${id_trabajador}`).html( `${dias_asistidos}`); $(`.pago_parcial_HE_${id_trabajador}`).html(formato_miles(pago_parcial_v2));  var pago_quincenal_v2 = pago_parcial_v2 ; $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(pago_quincenal_v2)); var suma_total_quincena = 0; for (let k = 1; k <= parseInt(cant_trabajador); k++) { suma_total_quincena = suma_total_quincena + parseFloat(quitar_formato_miles($(`.val_pago_quincenal_${k}`).text())); } $(`.pago_total_quincenal`).html(formato_miles(suma_total_quincena)); return true;
}

function select_dia_multiple(fecha_i, fecha_f) {
  var flag = false,html_option = '', i=0; 
  var f_i = format_d_m_a(fecha_i), f_f = format_d_m_a(fecha_f);

  while (flag == false) { 
    html_option = html_option.concat(`<option value="${extraer_dia_semana(f_i)}_${extraer_dia_mes(f_i)}_${extraer_mes_number(f_i)}">${extraer_dia_semana_completo(f_i)} - ${format_d_m_a(f_i)}</option>`);
    f_i = sumar_dias_moment(1, f_i );     
    if (f_i == f_f ) { flag = true; }   
  }
  $('#dia_xd').html(html_option);
}

function btn_guardar_horas_multiple(flag) {
  if (flag == 1) {
    $('.horas-multiples').show(); $('.horas-por-dia-multiples').hide();
  } else if (flag == 2) {
    $('.horas-multiples').hide(); $('.horas-por-dia-multiples').show();
  }
}

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   A D I C I O N A L   D E S C U E N T O  :::::::::::::::::::::::::::::::::::::::..
function adicional_descuento(cant_trabajador, id_trabajador, hne) {

  var suma_resta = 0; var pago_parcial_HN = 0; pago_parcial_HE = 0;

  // capturamos los pgos parciales
  pago_parcial_HN = parseFloat( quitar_formato_miles( $(`.pago_parcial_HN_${id_trabajador}`).text())); 
  pago_parcial_HE = parseFloat( quitar_formato_miles($(`.pago_parcial_HE_${id_trabajador}`).text()));

  if (parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) >= 0 || parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) <= 0 ) {
    if (hne == 'hn') {
      suma_resta = (pago_parcial_HN ) + parseFloat($(`.adicional_descuento_${id_trabajador}`).val());
    } else if (hne == 'he') {
      suma_resta = (pago_parcial_HE ) + parseFloat($(`.adicional_descuento_${id_trabajador}`).val());
    }    

    $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(suma_resta.toFixed(2)));

    var suma_total_quincena = 0;

    // acumulamos todos los pagos quicenales
    for (let k = 1; k <= parseInt(cant_trabajador); k++) { suma_total_quincena = suma_total_quincena + parseFloat(quitar_formato_miles($(`.val_pago_quincenal_${k}`).text())); }

    $(`.pago_total_quincenal`).html(formato_miles(suma_total_quincena.toFixed(2)));

  } else {
    toastr.error(`El dato de adicional/descuento: <h3 class=""> ${$(`.adicional_descuento_${id_trabajador}`).val()} </h3> no es NUMÉRICO, ingrese un numero cero o un positivo o un negativo.`);    
  }  
}

function guardaryeditar_adicional_descuento(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-adicional-descuento")[0]);

  $.ajax({
    url: `../ajax/asistencia_obrero.php?op=guardar_y_editar_adicional_descuento`,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);
        if (e.status == true) {

          datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r);
  
          Swal.fire("Correcto!", "Descripción registrada correctamente", "success");
  
          $("#modal-adicional-descuento").modal("hide");          
  
        }else{
  
          Swal.fire("Error!", datos, "error");
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }      
      
      $("#guardar_adicional_descuento").html('Guardar Cambios').removeClass('disabled');
    },
    beforeSend: function () {
      $("#guardar_adicional_descuento").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
    }
  });
}

function modal_adicional_descuento( id_adicional, id_trabjador, hne) {

  $("#cargando-5-fomulario").hide(); 
  $("#cargando-6-fomulario").show();

  $("#idresumen_q_s_asistencia").val(id_adicional);
  $("#idtrabajador_por_proyecto").val(id_trabjador);
  $("#ad_hne").val(hne);
  $("#detalle_adicional").val("");

  $("#modal-adicional-descuento").modal("show");

  $.post("../ajax/asistencia_obrero.php?op=descripcion_adicional_descuento",{"id_adicional":id_adicional}, function(e){
    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      if (hne == 'hn') { 
        $("#detalle_adicional").val(e.data.descripcion_descuento_hn);
      } else if (hne == 'he') { 
        $("#detalle_adicional").val(e.data.descripcion_descuento_he);
      }
    }else{
      ver_errores(e);
    }     
    
    $("#cargando-5-fomulario").show(); 
    $("#cargando-6-fomulario").hide();

  }).fail( function(e) { ver_errores(e); } );
}

function modal_adicional_descuento_hne( id_adicional, id_trabjador) {

  $("#html-ad-hne").html(`<div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /> <h4>Cargando...</h4> </div>`);   

  $("#modal-adicional-descuento-hne").modal("show");

  $.post("../ajax/asistencia_obrero.php?op=descripcion_adicional_descuento",{"id_adicional":id_adicional}, function(e){
    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      $("#html-ad-hne").html(`
      <div class="col-md-12 col-lg-12 text-center mb-2"><h4>${e.data.nombres}</h4></div>
      <div class="col-md-12 col-lg-12">
        <div class="form-group">
          <label for="nombre">Adicional/descuento de: Hora Normal</label>
          <p class="form-control" > ${e.data.adicional_descuento_hn == '' || e.data.adicional_descuento_hn == null ? '-' : e.data.adicional_descuento_hn} </p>          
        </div>
      </div> 
      <div class="col-md-12 col-lg-12"> 
        <div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 62px;" > ${e.data.descripcion_descuento_hn == '' || e.data.descripcion_descuento_hn == null ? '-' : e.data.descripcion_descuento_hn } </div>
      </div>
      <div class="col-md-12 col-lg-12"><div class="divider"></div></div>
      <div class="col-md-12 col-lg-12 mt-3">
        <div class="form-group">
          <label for="nombre">Adicional/descuento: Hora Extra</label>
          <p class="form-control" > ${e.data.adicional_descuento_he == '' || e.data.adicional_descuento_he == null ? '-' : e.data.adicional_descuento_he} </p>
        </div>
      </div> 
      <div class="col-md-12 col-lg-12"> 
        <div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 62px;" > ${e.data.descripcion_descuento_he == '' || e.data.descripcion_descuento_he == null ? '-' : e.data.descripcion_descuento_he} </div>
      </div>
      `);
    }else{
      ver_errores(e);
    }   

  }).fail( function(e) { ver_errores(e); } );
}
// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   A S I S T E N C I A   I N D I V I D U A L  :::::::::::::::::::::::::::::::::::::::..

// TBLA - ASISTENCIA INDIVIDUAL
function ver_asistencias_individual(idtrabajador_por_proyecto, fecha_inicio_proyect) {

  console.log(idtrabajador_por_proyecto,fecha_inicio_proyect);
  
  mostrar_form_table(3);

  tabla_horas = $('#tabla-detalle-asistencia-individual').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'pdf', "colvis"],
    ajax:{
      url: '../ajax/asistencia_obrero.php?op=listar_asis_individual&idtrabajadorproyecto='+idtrabajador_por_proyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) { 

      // columna: Horas normal
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
      // columna: Horas normal
      if (data[2] != '') { $("td", row).eq(2).addClass('text-center'); }
      // columna: Pago por horas normal
      if (data[3] != '') { $("td", row).eq(3).addClass('text-nowrap text-right'); }
      // columna: Horas normal
      if (data[4] != '') { $("td", row).eq(4).addClass('text-center'); }
      // columna: Pago por horas extras
      if (data[5] != '') { $("td", row).eq(5).addClass('text-right'); } 
      // columna: Pago por horas normal
      if (data[6] != '') {  $("td", row).eq(6).addClass('text-nowrap'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 2 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 2 ).footer() ).html( `<span class="float-center">${formato_miles(total1)}</span>` );
      var api2 = this.api(); var total2 = api2.column( 3 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api2.column( 3 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total2)}</span>` );
      var api3 = this.api(); var total3 = api3.column( 4 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api3.column( 4 ).footer() ).html( `<span class="float-center">${formato_miles(total3)}</span>` );
      var api4 = this.api(); var total4 = api4.column( 5 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api4.column( 5 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total4)}</span>` );
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "desc" ]],//Ordenar (columna,orden)
    columnDefs: [
      // { targets: [8,9,10,11,12],  visible: false,  searchable: false,  },
      { targets: [3,5], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      // { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
    ],
  }).DataTable();   
}

//Función para desactivar registros
function justificar(trabajador, idasistencia, horas, estado) {
  $('#idasistencia_trabajador_j').val(idasistencia);

  $('.descargar').hide();
  $('.ver_completo').hide();
  $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc1_nombre").html('');
  $('#doc_old_1').val("");
  $('#doc1').val("");
  $('#detalle_j').val('');

  if (estado == "0") {

    Swal.fire("Activa este registro!", "Para usar esta opcion, active este registro.", "info");

  } else {

    if (horas >= 8) {

      Swal.fire("No puedes Justificar!", `<b class="text-blue">${trabajador}</b> tiene <b>8 horas completas</b>, las justificación es para compensar horas perdidas.`, "info");
    
    } else {

      $("#modal-justificar-asistencia").modal("show");

      $.post("../ajax/asistencia_obrero.php?op=mostrar_justificacion", { 'idasistencia_trabajador': idasistencia }, function (e, status) {
        
        e = JSON.parse(e);  console.log(e);

        $('#detalle_j').val(e.data.descripcion_justificacion);

        if (e.data.doc_justificacion == '' || e.data.doc_justificacion == null || e.data.doc_justificacion == 'null') {
          $('.descargar').hide();
          $('.ver_completo').hide();
          $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
          $("#doc1_nombre").html('');
          $('#doc_old_1').val("");
          $('#doc1').val("");
          
        } else {
      
          $('.descargar').show();
          $('.ver_completo').show();
      
          $('#descargar_rh').attr('href', `../dist/docs/asistencia_obrero/justificacion/${e.data.doc_justificacion}`);
                  
          $('#descargar_rh').attr('download', `Justificacion`);              

          $('#ver_completo').attr('href', `../dist/docs/asistencia_obrero/justificacion/${e.data.doc_justificacion}`);
          $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Recibo-por-honorario.${extrae_extencion(e.data.doc_justificacion)}</i></div></div>`);
      
          $('#doc_old_1').val(e.data.doc_justificacion);
          $('#doc1').val('');

          $("#doc1_ver").html(doc_view_extencion(e.data.doc_justificacion, 'asistencia_obrero', 'justificacion', '100%', '310'));
      
        }

      }).fail( function(e) { ver_errores(e); } );
    }
  } 
}

function guardar_y_editar_justificar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-justificar-asistencia")[0]);

  $.ajax({
    url: "../ajax/asistencia_obrero.php?op=guardar_y_editar_justificacion",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {        

          Swal.fire("Correcto!", "Descripción registrada correctamente", "success");

          $("#modal-justificar-asistencia").modal("hide");

          tabla_horas.ajax.reload(null, false);

        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }      
      $("#guardar_registro_justificacion").html('Guardar Cambios').removeClass('disabled');
      
    },
    beforeSend: function () {
      $("#guardar_registro_justificacion").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
    }
  });
}

//Función para desactivar registros
function desactivar_dia_asistencia(idasistencia_trabajador) {
  $(".tooltip").removeClass("show").addClass("hidde");
  Swal.fire({
    title: "¿Está Seguro de  Desactivar la Asistencia?",
    text: "Al desactivar, las horas de este registro no seran contado.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/asistencia_obrero.php?op=desactivar_dia", { idasistencia_trabajador: idasistencia_trabajador }, function (e) {

        Swal.fire("Desactivado!", "La asistencia ha sido desactivado.", "success");
    
        tbla_principal(localStorage.getItem('nube_idproyecto'));
      }).fail( function(e) { ver_errores(e); } );     
    }
  });   
}

//Función para activar registros
function activar_dia_asistencia(idasistencia_trabajador) {
  $(".tooltip").removeClass("show").addClass("hidde");
  Swal.fire({
    title: "¿Está Seguro de  Activar  la Asistencia?",
    text: "Al activar, las horas de este registro seran contados",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/asistencia_obrero.php?op=activar_dia", { idasistencia_trabajador: idasistencia_trabajador }, function (e) {

        Swal.fire("Activado!", "La asistencia ha sido activado.", "success");

        tbla_principal(localStorage.getItem('nube_idproyecto'));
      }).fail( function(e) { ver_errores(e); } );      
    }
  });      
}

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   Q U I N C E N A S   O   S E M A N A S  :::::::::::::::::::::::::::::::::::::::..
// TBLA - QUINCENA SEMANA INDIVIDUAL
function tabla_qs_individual(idtrabajador_por_proyecto) {

  idtrabajador_por_proyecto_r = idtrabajador_por_proyecto;

  mostrar_form_table(4);  

  tabla_qs = $('#tabla-detalle-qs-individual').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [10,11,12,1,2,3,4,5,6,7], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [10,11,12,1,2,3,4,5,6,7], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [10,11,12,1,2,3,4,5,6,7], } }, {extend: "colvis"} ,
    ],
    ajax:{
      url: '../ajax/asistencia_obrero.php?op=tabla_qs_individual&idtrabajadorproyecto='+idtrabajador_por_proyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: HN y HE
      if (data[2] != '') { $("td", row).eq(2).addClass('text-nowrap text-center'); } 
      // columna: Dias
      if (data[3] != '') { $("td", row).eq(3).addClass('text-nowrap text-center'); }      
      // columna: Pago por horas normal
      if (data[4] != '') { $("td", row).eq(4).addClass('text-nowrap text-center'); }      
      // columna: Adicional
      if (data[5] != '') { $("td", row).eq(5).addClass('text-nowrap text-right'); }
      // columna: Sabado
      if (data[6] != '') { $("td", row).eq(6).addClass('text-nowrap text-center'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 3 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 3 ).footer() ).html( `<span class="float-center">${formato_miles(total1)}</span>` );

      var api2 = this.api(); var total2 = api2.column( 5 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api2.column( 5 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total2)}</span>` );

      var api1 = this.api(); var total1 = api1.column( 6 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 6 ).footer() ).html( `<span class="float-right">${formato_miles(total1)}</span>` );

      var api1 = this.api(); var total1 = api1.column( 7 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 7 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(total1)}</span>` );
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "desc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [10,11,12],  visible: false,  searchable: false,  },
      { targets: [5,7], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();

  //Suma ACUMULADO
  $.post("../ajax/asistencia_obrero.php?op=suma_qs_individual", { 'idtrabajadorproyecto': idtrabajador_por_proyecto }, function (e, status) {

    e =JSON.parse(e); console.log(e);
    if (e.status == true) {
      if (e.data == null) {
        $(".suma_qs_dias_asistidos").html(`0.00`);
        $(".suma_qs_adicional").html(`S/ 0.00`);
        $(".suma_qs_sabatical").html(`0.00`);
        $(".suma_qs_pago_quincenal").html(`S/ 0.00`);
      } else {
        $(".thead_num").html(`Num. ${e.data.fecha_pago_obrero}`);
        $(".thead_fecha").html(`Fechas ${e.data.fecha_pago_obrero}`);
        $(".thead_pago").html(`Pago ${e.data.fecha_pago_obrero}`);
        // $(".suma_qs_dias_asistidos").html(`<b>${formato_miles(e.data.total_dias_asistidos)}</b> `);
        // $(".suma_qs_adicional").html(`S/ <b>${formato_miles(e.data.adicional_descuento)}</b> `);
        // $(".suma_qs_sabatical").html(`<b>${formato_miles(e.data.sabatical)}</b> `);
        // $(".suma_qs_pago_quincenal").html(`S/ <b>${formato_miles(e.data.pago_quincenal)}</b> `);        
      }
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
  
}

//Función para desactivar registros
function desactivar_qs(id, tipo_pago) {
  
  Swal.fire({
    title: `¿Está Seguro de  Desactivar la ${tipo_pago} ?`,
    text: "Al desactivar, este registro no sera contado.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
    showLoaderOnConfirm: true,
    preConfirm: (input) => {       
      return fetch(`../ajax/asistencia_obrero.php?op=desactivar_qs&idresumen_q_s_asistencia=${id}`).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    if (result.isConfirmed) {
      if (result.value.status) {
        Swal.fire("Desactivado!", `La ${tipo_pago} ha sido desactivado.`, "success");
        tbla_principal(localStorage.getItem('nube_idproyecto')); 
        tabla_qs_individual(idtrabajador_por_proyecto_r);
      }else{
        ver_errores(result.value);
      }    
    }
  });  
  $(".tooltip").removeClass("show").addClass("hidde"); 
}

//Función para activar registros
function activar_qs(id, tipo_pago) {
   
  Swal.fire({
    title: `¿Está Seguro de  Activar  la ${tipo_pago}?`,
    text: "Al activar, este registro sera contado",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
    showLoaderOnConfirm: true,
    preConfirm: (input) => {       
      return fetch(`../ajax/asistencia_obrero.php?op=activar_qs&idresumen_q_s_asistencia=${id}`).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    if (result.isConfirmed) {
      if (result.value.status) {
        Swal.fire("Activado!", `La ${tipo_pago} ha sido activado.`, "success");
        tbla_principal(localStorage.getItem('nube_idproyecto')); 
        tabla_qs_individual(idtrabajador_por_proyecto_r);
      }else{
        ver_errores(result.value);
      }  
    }
  });     
  $(".tooltip").removeClass("show").addClass("hidde"); 
}

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   F E C H A S   D E   A C T I V I D A D E S  :::::::::::::::::::::::::::::::::::::::..

function limpiar_form_fechas_actividades(params) {
  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();

  $('#id_proyecto_f').val(localStorage.getItem('nube_idproyecto'));

  $('#fecha_inicio_actividad').datepicker("setDate" ,'');
  $('#fecha_fin_actividad').datepicker("setDate" ,'');
  $('#plazo_actividad').val("");

  $.post("../ajax/asistencia_obrero.php?op=fechas_actividad", { 'id_proyecto': localStorage.getItem('nube_idproyecto') }, function (e, status) {
    
    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {      
      var f_1 = e.data.fecha_inicio_actividad == '' || e.data.fecha_inicio_actividad == null ? '' : format_d_m_a(e.data.fecha_inicio_actividad) ;
      var f_2 = e.data.fecha_fin_actividad == '' || e.data.fecha_fin_actividad == null ? '' : format_d_m_a(e.data.fecha_fin_actividad) ;

      $('#fecha_inicio_actividad').datepicker("setDate" , f_1);
      $('#fecha_fin_actividad').datepicker("setDate" , f_2);
      $('#plazo_actividad').val(e.data.plazo_actividad);
      $('.plazo_actividad').html(e.data.plazo_actividad);

      $("#fecha_pago_obrero_f").val(e.data.fecha_pago_obrero).trigger("change");

      if (e.data.permanente_pago_obrero == '1') {      
        $(".show_hide_select_1").hide(); 
        $(".show_hide_select_2").show();
        $(".show_hide_select_2").html(`<label for="">Pago de obreros <sup class="text-danger">*</sup></label> <span class="form-control" > ${e.data.fecha_pago_obrero} </span>`);
  
        $('.show_hide_switch_1').hide();
        $('.show_hide_switch_2').show();
  
        $("#definiendo").prop('checked', true);        
      } else {      
        $(".show_hide_select_1").show(); 
        $(".show_hide_select_2").hide();
        $(".show_hide_select_2").html("");
  
        $('.show_hide_switch_1').show();
        $('.show_hide_switch_2').hide();
        $("#definiendo").prop('checked', false);       
      }
  
      $("#permanente_pago_obrero").val(e.data.permanente_pago_obrero);
    } else {
      ver_errores(e);
    }


    $("#cargando-7-fomulario").show();
    $("#cargando-8-fomulario").hide();
  }).fail( function(e) { ver_errores(e); } );
}

function validar_permanent() { if ($("#fecha_pago_obrero_f").select2('val') == null) {  $("#definiendo").prop('checked', false); } }

function permanente_pago_obrero() {

  if ($("#fecha_pago_obrero_f").select2('val') == null) {
    toastr_error('Selecione un pago obrero:',`<ul class="mb-1"> <li>Quincenal</li> <li>Semanal</li> </ul>`, 700);
    //toastr.error(`Selecione un pago obrero: <ul> <li>Quincenal</li> <li>Semanal</li> </ul>`);

    if($('#definiendo').is(':checked')){ 
      $("#definiendo").prop('checked', false); 
    }else{ 
      $("#definiendo").prop('checked', true); 
    }
  
  } else {
    if($('#definiendo').is(':checked')){ 
      if ($('#fecha_pago_obrero_f').is(':disabled')) {
        $("#permanente_pago_obrero").val(1);
      }else{
        $("#permanente_pago_obrero").val(0);
      }
       
    }else{ 
      if ($('#fecha_pago_obrero_f').is(':disabled')) {
        $("#permanente_pago_obrero").val(1);
      }else{
        $("#permanente_pago_obrero").val(1);
      } 
    }
  }
}

function calcular_plazo_actividad() {

  var plazo = 0;  

  if ($('#fecha_inicio_actividad').val() != "" && $('#fecha_fin_actividad').val() != "") {

    var fecha1 = moment( format_a_m_d($('#fecha_inicio_actividad').val()) );

    var fecha2 = moment( format_a_m_d($('#fecha_fin_actividad').val()) );

    plazo = fecha2.diff(fecha1, 'days') + 1;
  } 

  $('.plazo_actividad').html(plazo);
  $('#plazo_actividad').val(plazo);
}

function guardar_y_editar_fechas_actividades(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-fechas-actividades")[0]);

  var id_proyecto_f           = $('#id_proyecto_f').val();
  var fecha_inicio_actividad  = $('#fecha_inicio_actividad').val();
  var fecha_fin_actividad     = $('#fecha_fin_actividad').val();
  var plazo_actividad         = $('#plazo_actividad').val();
  var fecha_pago_obrero       = $('#fecha_pago_obrero_f').val();
  var permanente_pago_obrero  = $('#permanente_pago_obrero').val();

  $.ajax({
    url: "../ajax/asistencia_obrero.php?op=guardar_y_editar_fechas_actividad",
    type: "POST",
    //data: formData,
    data: {
      'id_proyecto_f'         : id_proyecto_f, 
      'fecha_inicio_actividad': fecha_inicio_actividad, 
      'fecha_fin_actividad'   : fecha_fin_actividad, 
      'plazo_actividad'       : plazo_actividad,      
      'fecha_pago_obrero'     : fecha_pago_obrero,       
      'permanente_pago_obrero': permanente_pago_obrero,       
    },
    //contentType: 'application/json; charset=utf-8',
    //processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);
             
        if (e.status == true) {        

          Swal.fire("Correcto!", "Fechas registrada correctamente", "success");
          localStorage.setItem('nube_fecha_pago_obrero', fecha_pago_obrero);

          $("#modal-agregar-fechas-actividades").modal("hide");
          listar_botones_q_s(localStorage.getItem('nube_idproyecto')); 
          tbla_principal(localStorage.getItem('nube_idproyecto'));

          mostrar_form_table(1);

        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }      
      $("#guardar_registro_fechas_actividades").html('Guardar Cambios').removeClass('disabled');
    },
    beforeSend: function () {
      $("#guardar_registro_fechas_actividades").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
    }
  });
}

init();

// .....::::::::::::::::::::::::::::::::::::: S E C C I O N   D E   H O R A R I O  :::::::::::::::::::::::::::::::::::::::..

function mostrar_horario() {
  $('.title-horario').html(`Horario: <b>${localStorage.getItem('nube_nombre_proyecto')}</b>`);
  $.post("../ajax/asistencia_obrero.php?op=mostrar_horario", { 'id_proyecto' : localStorage.getItem('nube_idproyecto') }, function (e, textStatus, jqXHR) {
    e = JSON.parse(e);  console.log(e);
    if (e.status == true){
      if (e.data.h_normal.length === 0) {
        console.log('no hay horario'); 
      
        $('#tabla-hora-normal').html(`
          <thead>
            <tr class="text-center">
              <th colspan="7" class="py-1" >HORARIO NORMAL 
                <input type="hidden" name="nombre_horario[]" value="HORARIO NORMAL"> <input type="hidden" name="nombre_horario[]" value="HORARIO NORMAL"> 
                <input type="hidden" name="nombre_horario[]" value="HORARIO NORMAL"> <input type="hidden" name="nombre_horario[]" value="HORARIO NORMAL"> 
              </th>
            </tr>
            <tr>
              <th ></th>
              <th class="text-center">DOMINGO</th>  <th >LUNES</th> <th >MARTES</th> <th >MIÉRCOLES</th>  <th >JUEVES</th>  <th >VIERNES</th>
            </tr>
          </thead>
          <tbody>
            <tr class="text-nowrap">
              <th class="py-1"><span >MAÑANA</span> 
                <input type="text" name="turno_h[]" style="display: none;" value="MAÑANA">
              </th>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="domingo_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="lunes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="martes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="miercoles_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="jueves_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="viernes_h[]" style="display: none;" onClick="this.select();" ></td>
            </tr>
            <tr  class="text-nowrap">
              <th class="py-1"><span >ALMUERZO</span> 
                <input type="text" name="turno_h[]" style="display: none;" value="ALMUERZO">               
              </th>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="domingo_h[]" style="display: none;" onClick="this.select();" > </td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="lunes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="martes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="miercoles_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="jueves_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="viernes_h[]" style="display: none;" onClick="this.select();" ></td>
            </tr>
            <tr class="text-nowrap">
              <th class="py-1"><span >TARDE</span> 
                <input type="text" name="turno_h[]" style="display: none;" value="TARDE">
              </th>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="domingo_h[]" style="display: none;" onClick="this.select();" > </td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="lunes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="martes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="miercoles_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="jueves_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="viernes_h[]" style="display: none;" onClick="this.select();" ></td>
            </tr>
            <tr  >
              <th rowspan="2" ><span >HORAS ACUMULADAS</span>  
                <input type="text" name="turno_h[]" style="display: none;" value="HORAS ACUMULADAS">              
              </th>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_hn" name="domingo_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" onClick="this.select();" min="0"> </td>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_hn" name="lunes_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" onClick="this.select();" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_hn" name="martes_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" onClick="this.select();" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_hn" name="miercoles_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" onClick="this.select();" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_hn" name="jueves_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" onClick="this.select();" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_hn" name="viernes_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" onClick="this.select();" min="0"></td>
            </tr>
            <tr >                                  
              <td colspan="6" class="py-1 text-center" > <span class="total_horario_hn" >-</span> </td>                                  
            </tr>
          </tbody>
        `);

        $('#tabla-hora-extra').html(`
          <thead>
            <tr class="text-center">
              <th colspan="7" class="py-1" >HORARIO EXTRA 
              <input type="hidden" name="nombre_horario[]" value="HORARIO EXTRA"> <input type="hidden" name="nombre_horario[]" value="HORARIO EXTRA"> 
              <input type="hidden" name="nombre_horario[]" value="HORARIO EXTRA"> <input type="hidden" name="nombre_horario[]" value="HORARIO EXTRA"> 
              </th>
            </tr>
            <tr>
              <th ></th>
              <th class="text-center">DOMINGO</th>  <th >LUNES</th> <th >MARTES</th> <th >MIÉRCOLES</th>  <th >JUEVES</th>  <th >VIERNES</th>
            </tr>
          </thead>
          <tbody>
            <tr class="text-nowrap">
              <th class="py-1"><span >TARDE</span> 
                <input type="text" name="turno_h[]" style="display: none;" value="TARDE">
              </th>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="domingo_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="lunes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="martes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="miercoles_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="jueves_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="viernes_h[]" style="display: none;" onClick="this.select();" ></td>
            </tr>
            <tr  class="text-nowrap">
              <th class="py-1"><span >CENA</span> 
                <input type="text" name="turno_h[]" style="display: none;" value="CENA">
              </th>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="domingo_h[]" style="display: none;" onClick="this.select();" > </td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="lunes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="martes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="miercoles_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="jueves_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="viernes_h[]" style="display: none;" onClick="this.select();" ></td>
            </tr>
            <tr class="text-nowrap">
              <th class="py-1"><span >NOCHE</span> 
                <input type="text" name="turno_h[]" style="display: none;" value="NOCHE">
              </th>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="domingo_h[]" style="display: none;" onClick="this.select();" > </td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="lunes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="martes_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="miercoles_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="jueves_h[]" style="display: none;" onClick="this.select();" ></td>
              <td class="p-1 text-center"><span class="span_horario" >-</span><input type="text" class="form-control w-140px input_horario" name="viernes_h[]" style="display: none;" onClick="this.select();" ></td>
            </tr>
            <tr  >
              <th rowspan="2" ><span >HORAS ACUMULADAS</span>  
                <input type="text" name="turno_h[]" style="display: none;" value="HORAS ACUMULADAS">
              </th>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_he" name="domingo_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" onClick="this.select();" min="0"> </td>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_he" name="lunes_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" onClick="this.select();" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_he" name="martes_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" onClick="this.select();" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_he" name="miercoles_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" onClick="this.select();" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_he" name="jueves_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" onClick="this.select();" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >-</span><input type="number" class="form-control w-140px input_horario ha_he" name="viernes_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" onClick="this.select();" min="0"></td>
            </tr>
            <tr >                                  
              <td colspan="6" class="py-1 text-center" > <span class="total_horario_he" >-</span>  </td>                                  
            </tr>
          </tbody>
        `);
      }else{
        var input_dia_html = ''; var input_nombre_html = ''; var nombre_html = ''; var total_horas = 0;
        e.data.h_normal.forEach((val, key) => {           
          
          input_nombre_html += `<input type="hidden" name="nombre_horario[]" value="${val.nombre}">`;
          nombre_html =  val.nombre;

          if (val.turno == 'HORAS ACUMULADAS') {
            input_dia_html += `<tr  >
              <th rowspan="2" ><span >HORAS ACUMULADAS</span>  
                <input type="text" name="turno_h[]" style="display: none;" value="HORAS ACUMULADAS">
              </th>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.domingo}</span><input value="${val.domingo}" type="number" class="form-control w-120px input_horario ha_hn" name="domingo_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" min="0"> </td>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.lunes}</span><input value="${val.lunes}" type="number" class="form-control w-120px input_horario ha_hn" name="lunes_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.martes}</span><input value="${val.martes}" type="number" class="form-control w-120px input_horario ha_hn" name="martes_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.miercoles}</span><input value="${val.miercoles}" type="number" class="form-control w-120px input_horario ha_hn" name="miercoles_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.jueves}</span><input value="${val.jueves}" type="number" class="form-control w-120px input_horario ha_hn" name="jueves_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.viernes}</span><input value="${val.viernes}" type="number" class="form-control w-120px input_horario ha_hn" name="viernes_h[]" onkeyup="sumar_hn_horario();" onchange="sumar_hn_horario();" style="display: none;" min="0"></td>
            </tr>`;
            total_horas = parseFloat(val.domingo) + parseFloat(val.lunes) + parseFloat(val.martes) + parseFloat(val.miercoles) + parseFloat(val.jueves) + parseFloat(val.viernes);
          }else{
            input_dia_html += `<tr class="text-nowrap">
              <th class="py-1"  ><span >${val.turno}</span> 
                <input type="text" name="turno_h[]" style="display: none;" value="${val.turno}">
              </th>
              <td class="p-1 text-center"><span class="span_horario" >${val.domingo}</span><input value="${val.domingo}" type="text" class="form-control w-120px input_horario" name="domingo_h[]" style="display: none;"> </td>
              <td class="p-1 text-center"><span class="span_horario" >${val.lunes}</span><input value="${val.lunes}" type="text" class="form-control w-120px input_horario" name="lunes_h[]" style="display: none;"></td>
              <td class="p-1 text-center"><span class="span_horario" >${val.martes}</span><input value="${val.martes}" type="text" class="form-control w-120px input_horario" name="martes_h[]" style="display: none;"></td>
              <td class="p-1 text-center"><span class="span_horario" >${val.miercoles}</span><input value="${val.miercoles}" type="text" class="form-control w-120px input_horario" name="miercoles_h[]" style="display: none;"></td>
              <td class="p-1 text-center"><span class="span_horario" >${val.jueves}</span><input value="${val.jueves}" type="text" class="form-control w-120px input_horario" name="jueves_h[]" style="display: none;"></td>
              <td class="p-1 text-center"><span class="span_horario" >${val.viernes}</span><input value="${val.viernes}" type="text" class="form-control w-120px input_horario" name="viernes_h[]" style="display: none;"></td>
            </tr>`;
          }
        });

        $('#tabla-hora-normal').html(`
          <thead>
            <tr class="text-center">
              <th colspan="7" class="py-1" > ${nombre_html} ${input_nombre_html} </th>
            </tr>
            <tr>
              <th ></th>
              <th class="text-center">DOMINGO</th>  <th >LUNES</th> <th >MARTES</th> <th >MIÉRCOLES</th>  <th >JUEVES</th>  <th >VIERNES</th>
            </tr>
          </thead>
          <tbody>             
            ${input_dia_html}
            <tr >                                  
              <td colspan="6" class="py-1 text-center" > <span class="total_horario_hn" >${total_horas}</span> </td>                                  
            </tr>
          </tbody>
        `);

        var input_dia_html_2 = ''; var input_nombre_html_2 = ''; var nombre_html_2 = ''; var total_horas_2 = 0; 
        e.data.h_extra.forEach((val, key) => {           
          
          input_nombre_html_2 += `<input type="hidden" name="nombre_horario[]" value="${val.nombre}">`;
          nombre_html_2 =  val.nombre;

          if (val.turno == 'HORAS ACUMULADAS') {
            input_dia_html_2 += `<tr  >
              <th rowspan="2" ><span >HORAS ACUMULADAS</span>  
                <input type="text" name="turno_h[]" style="display: none;" value="HORAS ACUMULADAS">
              </th>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.domingo}</span><input value="${val.domingo}" type="number" class="form-control w-120px input_horario ha_he" name="domingo_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" min="0"> </td>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.lunes}</span><input value="${val.lunes}" type="number" class="form-control w-120px input_horario ha_he" name="lunes_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.martes}</span><input value="${val.martes}" type="number" class="form-control w-120px input_horario ha_he" name="martes_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.miercoles}</span><input value="${val.miercoles}" type="number" class="form-control w-120px input_horario ha_he" name="miercoles_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.jueves}</span><input value="${val.jueves}" type="number" class="form-control w-120px input_horario ha_he" name="jueves_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" min="0"></td>
              <td class="p-1 text-center form-group"><span class="span_horario" >${val.viernes}</span><input value="${val.viernes}" type="number" class="form-control w-120px input_horario ha_he" name="viernes_h[]" onkeyup="sumar_he_horario();" onchange="sumar_he_horario();" style="display: none;" min="0"></td>
            </tr>`;
            total_horas_2 = parseFloat(val.domingo) + parseFloat(val.lunes) + parseFloat(val.martes) + parseFloat(val.miercoles) + parseFloat(val.jueves) + parseFloat(val.viernes);
          }else{
            input_dia_html_2 += `<tr class="text-nowrap">
              <th class="py-1"  ><span >${val.turno}</span> 
                <input type="text" name="turno_h[]" style="display: none;" value="${val.turno}">
              </th>
              <td class="p-1 text-center"><span class="span_horario" >${val.domingo}</span><input value="${val.domingo}" type="text" class="form-control w-120px input_horario" name="domingo_h[]" style="display: none;"> </td>
              <td class="p-1 text-center"><span class="span_horario" >${val.lunes}</span><input value="${val.lunes}" type="text" class="form-control w-120px input_horario" name="lunes_h[]" style="display: none;"></td>
              <td class="p-1 text-center"><span class="span_horario" >${val.martes}</span><input value="${val.martes}" type="text" class="form-control w-120px input_horario" name="martes_h[]" style="display: none;"></td>
              <td class="p-1 text-center"><span class="span_horario" >${val.miercoles}</span><input value="${val.miercoles}" type="text" class="form-control w-120px input_horario" name="miercoles_h[]" style="display: none;"></td>
              <td class="p-1 text-center"><span class="span_horario" >${val.jueves}</span><input value="${val.jueves}" type="text" class="form-control w-120px input_horario" name="jueves_h[]" style="display: none;"></td>
              <td class="p-1 text-center"><span class="span_horario" >${val.viernes}</span><input value="${val.viernes}" type="text" class="form-control w-120px input_horario" name="viernes_h[]" style="display: none;"></td>
            </tr>`;
          }
        });

        $('#tabla-hora-extra').html(`
          <thead>
            <tr class="text-center">
              <th colspan="7" class="py-1" > ${nombre_html_2} ${input_nombre_html_2} </th>
            </tr>
            <tr>
              <th ></th>
              <th class="text-center">DOMINGO</th>  <th >LUNES</th> <th >MARTES</th> <th >MIÉRCOLES</th>  <th >JUEVES</th>  <th >VIERNES</th>
            </tr>
          </thead>
          <tbody>             
            ${input_dia_html_2}
            <tr >                                  
              <td colspan="6" class="py-1 text-center" > <span class="total_horario_he" >${total_horas_2}</span> </td>                                  
            </tr>
          </tbody>
        `);
      }

      $('.input_horario').inputmask('99:99 - 99:99', { 'placeholder': 'hh:mm - hh:mm' });
    }else{
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function sumar_hn_horario() {
  var data_h = $('.ha_hn'); var hora = 0;
  data_h.each(function(val, key) { 
    hora +=  $(this).val() == null || $(this).val() == '' || $(this).val() == 0 ? 0 : parseFloat($(this).val());    
    console.log( hora );     
  });
  $('.total_horario_hn').html(hora);
}

function sumar_he_horario() {
  var data_h = $('.ha_he'); var hora = 0;
  data_h.each(function(val, key) { 
    hora +=  $(this).val() == null || $(this).val() == '' || $(this).val() == 0 ? 0 : parseFloat($(this).val());    
    console.log( hora );     
  });
  $('.total_horario_he').html(hora);
}

function show_hide_form_horario(flag) {
  if (flag == 1) { // tabla
    $('.input_horario').hide();
    $('.span_horario').show();

    $('#bnt-exportar-horario').show();
    $('#btn-editar-horario').show();
    $('#guardar_registro_horario').hide();
  } else if (flag == 2) { // formulario
    $('.input_horario').show();
    $('.span_horario').hide();

    $('#bnt-exportar-horario').hide();
    $('#btn-editar-horario').hide();
    $('#guardar_registro_horario').show();
  }
}

function guardar_y_editar_horario(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-horario-proyecto")[0]);

  $.ajax({
    url: "../ajax/asistencia_obrero.php?op=guardar_y_editar_horario",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) { 
          Swal.fire("Correcto!", "Horario guardado correctamente", "success");
          show_hide_form_horario(1);
          mostrar_horario(); 
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_horario").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_horario").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_horario").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_horario").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_horario").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M S  :::::::::::::::::::::::::::::::::::::::..

$(function () {    

  $("#form-adicional-descuento").validate({
    
    rules: {      
      detalle_adicional: { required: true, minlength: 4},
    },

    messages: {
      detalle_adicional: {
        required: "Este campo es requerido",
        min:"Escriba almenos 4 letras"
      },
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

    submitHandler: function (form) {
      guardaryeditar_adicional_descuento(form);
    },
  });

  $("#form-justificar-asistencia").validate({
    
    rules: {      
      detalle_j: { required: true, minlength: 4},
    },

    messages: {
      detalle_j: {
        required: "Este campo es requerido",
        min:"Escriba almenos 4 caracteres."
      },
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

    submitHandler: function (form) {
      guardar_y_editar_justificar(form);
    },
  });

  $("#form-fechas-actividades").validate({    
    rules: {      
      fecha_inicio_actividad: { required: true, minlength: 4},
      fecha_fin_actividad:    { required: true, minlength: 4},
      plazo_actividad:        { required: true,},
    },
    messages: {
      fecha_inicio_actividad: { required: "Este campo es requerido", min:"Escriba almenos 4 caracteres." },
      fecha_fin_actividad:    { required: "Este campo es requerido", min:"Escriba almenos 4 caracteres."  },
      plazo_actividad:        {  required: "Este campo es requerido", },
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

    submitHandler: function (form) {
      guardar_y_editar_fechas_actividades(form);
    },
  });

  $("#form-horas-multiples").validate({
    
    rules: {      
      horas_multiples: { required: true, number: true, min:0, max:12},
    },

    messages: {
      horas_multiples: { required: "Este campo es requerido", min:"Escriba almenos 1 digito positivo.", max:"No explote a sus obreros." },
    },  
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").addClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
    },

    submitHandler: function (form) {      
      agregar_horas_multiples(form);
    },
  });

  $("#form-horas-por-dia-multiples").validate({
    
    rules: {      
      dia_xd:   { required: true,},
      horas_xd: { required: true, number: true, min:0, max:12},
    },

    messages: {
      dia_xd:   { required: "Este campo es requerido", },
      horas_xd: { required: "Este campo es requerido",  min:"Escriba almenos 1 digito positivo.", max:"No explote a sus obreros." },
    },  
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").addClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
    },

    submitHandler: function (form) {      
      agregar_horas_por_dia_multiples(form);
    },
  });

  $("#form-horario-proyecto").validate({
    
    rules: {      
      'idproyecto_horario':  { required: true,},
      // horas_xd: { required: true, number: true, min:0, max:12},
    },

    messages: {
      'idproyecto_horario':  { required: "Campo requerido", min: "Mayor a 0."},
      // horas_xd: { required: "Este campo es requerido",  min:"Escriba almenos 1 digito positivo.", max:"No explote a sus obreros." },
    },  
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").addClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
    },

    submitHandler: function (form) {      
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_y_editar_horario(form);
    },
  });
});

function l_m(){     
  $(".progress-bar").removeClass("bg-success bg-danger progress-bar-striped");
  $("#barra_progress_h_multiple").css({"width":'0%'}).text("0%");
  $("#barra_progress_h_por_dia").css({"width":'0%'}).text("0%");  
}

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

// SCROLL - IR AL INICIO
$('.ir_a_top').on('click', function (e) { $('html, body').animate({ scrollTop: '0px' }, 600); /*Scrollea hasta abajo de la página*/ });

// SCROLL - IR AL LA DERECHA
$('.ir_a_right').on('click', function (e) { var posicion = $("#ver_asistencia").width(); $("#ver_asistencia").animate({ scrollLeft:posicion }, 600); });

// SCROLL - IR AL LA IZQUIERDA
$('.ir_a_left').on('click', function (e) { $("#ver_asistencia").animate({ scrollLeft: '0px' }, 600); });

// SCROLL - IR AL FINAL
$('.ir_a_bottom').on('click', function (e) { $('html, body').animate({ scrollTop: $(document).height() }, 600); /*Scrollea hasta abajo de la página*/ });

// SCROLL - IR AL CENTRO
function pocision_scroll_btn() {
  var posicion = parseFloat($("#lista_quincenas").width())/2;
  console.log(posicion);
  $("#lista_quincenas").animate({ scrollLeft:posicion }, 600); 
}

// ══════════════════════════════════════ tamaño de tabla - asistencia  ══════════════════════════════════════

function scroll_tabla_asistencia() {
  var height_tabla = $('.tabla_sistencia_obrero').height(); console.log('Alto pantalla: '+height_tabla);
  var width_tabla = $('.tabla_sistencia_obrero').width(); console.log('Ancho pantalla: '+width_tabla);
  if (height_tabla <= 600) {
    $('#ver_asistencia').css({'height':`${redondearExp((height_tabla+50),0)}px`});
  } else {
    var alto_real = (width_tabla/2) - 100;
    $('#ver_asistencia').css({'height':`${redondearExp(alto_real,0)}px`});
  }
}

// voy a eliminar esta funcion cuando no lo NECESITE -----------------------
function convertir_a_hora(hora_n) {

  var convertido; var suma; var min; var hora; console.log('h:' + hora_n );
      
  var recortado_suma = hora_n.split('.').pop();

  min = Math.round((parseFloat(recortado_suma)*60)/100);
  
  if (hora_n >=10) {  hora = hora_n.substr(0,2); } else {  hora = '0'+hora_n.substr(0,1); }

  if (min >= 10) { convertido = hora + ':' + min; } else { convertido = hora + ':0' + min; }    
  
  return convertido;
}

// voy a eliminar esta funcion cuando no lo NECESITE -----------------------
function agregar_hora_all() {
  var hora_all = $("#hora_all").val();
  $('input[type=time][name="horas_trabajo[]"]').val(hora_all);
}

// voy a eliminar esta funcion cuando no lo NECESITE -----------------------
function mostrar(idasistencia_trabajador) {
  $('#modal-editar-asistencia').modal('show')
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $.post("../ajax/asistencia_obrero.php?op=mostrar_editar", { idasistencia_trabajador: idasistencia_trabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data);
    
    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#fecha2").val(data.fecha_asistencia);      
    var suma = (parseFloat(data.horas_normal_dia) + parseFloat(data.horas_extras_dia)).toFixed(2).toString();
    var hr_total_c =  convertir_a_hora(suma);

    console.log(hr_total_c);

    var img =data.imagen_perfil != '' ? '<img src="../dist/img/usuarios/'+data.imagen_perfil+'" alt="" >' : '<img src="../dist/svg/user_default.svg" alt="" >';
    
    $("#lista-de-trabajadores2").html(
      '<!-- Trabajador -->'+                         
      '<div class="col-lg-12">'+
        '<label >Trabajador</label> <br>'+
        '<div class="user-block">'+
          img+
          '<span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'+data.nombres+'</p></span>'+
          '<span class="description">'+data.documento+': '+data.numero_documento+'</span>'+
        '</div>'+                         
        '<input type="hidden" name="trabajador2[]" value="'+data.idtrabajador_por_proyecto+'" />'+
      '</div>'+

      '<!-- Horas de trabajo -->'+
      '<div class="col-lg-12 mt-3">'+
        '<label for="fecha">Horas</label>'+
        '<div class="form-group">'+
          '<input id="horas_trabajo" name="horas_trabajo2[]" type="time"   class="form-control" value="'+hr_total_c+'" />'+             
        '</div>'+
      '</div> '+
      '<div class="col-lg-12 borde-arriba-negro borde-arriba-verde mt-1 mb-3"> </div>'
    );

  }).fail( function(e) { ver_errores(e); } );
}

// voy a eliminar esta funcion cuando no lo NECESITE -----------------------
function lista_trabajadores(nube_idproyecto) {

  $("#lista-de-trabajadores").html(
    '<div class="col-lg-12 text-center">'+  
      '<i class="fas fa-spinner fa-pulse fa-6x"></i><br />'+
      '<br />'+
      '<h4>Cargando...</h4>'+
    '</div>'
  );

  $.post("../ajax/asistencia_obrero.php?op=lista_trabajador", { nube_idproyecto: nube_idproyecto }, function (data, status) {

    data = JSON.parse(data);  //console.log(data); 

    $("#lista-de-trabajadores").html("");

    $.each(data, function (index, value) {
      // console.log(value.idtrabajador_por_proyecto);
      var img =value.imagen_perfil != '' ? '<img src="../dist/img/usuarios/'+value.imagen_perfil+'" alt="" >' : '<img src="../dist/svg/user_default.svg" alt="" >';
      
      $("#lista-de-trabajadores").append(
        '<!-- Trabajador -->'+                         
        '<div class="col-lg-6">'+
          '<div class="user-block">'+
            img+
            '<span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'+value.nombres+'</p></span>'+
            '<span class="description">'+value.documento+': '+value.numero_documento+'</span>'+
          '</div>'+                         
          '<input type="hidden" name="trabajador[]" value="'+value.idtrabajador_por_proyecto+'" />'+
        '</div>'+

        '<!-- Horas de trabajo -->'+
        '<div class="col-lg-6 mt-2">'+
          '<div class="form-group">'+
            '<input id="horas_trabajo" name="horas_trabajo[]" type="time"   class="form-control" value="00:00" />'+             
          '</div>'+
        '</div> '+
        '<div class="col-lg-12 borde-arriba-negro borde-arriba-verde mt-1 mb-3"> </div>'
      );
    });
  }).fail( function(e) { ver_errores(e); } );
}

// voy a eliminar esta funcion cuando no lo NECESITE -----------------------
function sabatical_no_usado() {
  // calculamos los sabaticales automáticos
  var horas_1_sabado = 0; var horas_2_sabado = 0; var sabatical = 0;

  for (let x = 1; x <= parseInt(cant_dias_asistencia); x++) {     
    // acumulamos las horas para el "primer" sabatical
    if (sabatical_manual_1 == '-') {
      if ( x < 7 ) { if ($(`.desglose_q_s_${id_trabajador}_${x}`).val() > 0) { horas_1_sabado += parseFloat($(`.desglose_q_s_${id_trabajador}_${x}`).val()); } }      
    } 
    // acumulamos las horas para el "segundo" sabatical
    if (sabatical_manual_2 == '-') {
      if ( x > 7 && x < 14 ) { if ($(`.desglose_q_s_${id_trabajador}_${x}`).val()  > 0) { horas_2_sabado += parseFloat($(`.desglose_q_s_${id_trabajador}_${x}`).val()); } }
    }
  }

  if (sabatical_manual_1 == '-') {
    if (horas_1_sabado >= 44 ) {
      $(`.desglose_q_s_${id_trabajador}_7`).val('8');
      $(`#checkbox_sabatical_${id_trabajador}_1`).prop('checked', true); suma_hn += 8; sabatical += 1; 
      $(`.sabatical_auto_${id_trabajador}_7`).removeClass('bg-color-acc3c7').addClass('bg-color-28a745');
    } else {
      $(`.desglose_q_s_${id_trabajador}_7`).val('0');       
      $(`#checkbox_sabatical_${id_trabajador}_1`).prop('checked', false);
      $(`.sabatical_auto_${id_trabajador}_7`).removeClass('bg-color-28a745').addClass('bg-color-acc3c7');
    }     
    $(`.sabatical_${id_trabajador}`).html(sabatical);    
  }

  if (sabatical_manual_2 == '-') {
    if (horas_2_sabado >= 44) {
      $(`.desglose_q_s_${id_trabajador}_14`).val('8');
      $(`#checkbox_sabatical_${id_trabajador}_2`).prop('checked', true); suma_hn += 8; sabatical += 1;
      $(`.sabatical_auto_${id_trabajador}_14`).removeClass('bg-color-acc3c7').addClass('bg-color-28a745');
    } else {
      $(`.desglose_q_s_${id_trabajador}_14`).val('0'); 
      $(`#checkbox_sabatical_${id_trabajador}_2`).prop('checked', false);
      $(`.sabatical_auto_${id_trabajador}_14`).removeClass('bg-color-28a745').addClass('bg-color-acc3c7');
    }
    $(`.sabatical_${id_trabajador}`).html(sabatical);
  }

  if (sabatical_manual_1 == '1') { sabatical += 1; $(`.sabatical_${id_trabajador}`).html(sabatical);}
  if (sabatical_manual_2 == '1') { sabatical += 1; $(`.sabatical_${id_trabajador}`).html(sabatical);}
}

// GUARDAR - SABATICAL - // voy a eliminar esta funcion cuando no lo NECESITE -----------------------
function calcular_sabatical_no_usado(fecha, sueldo_x_hora, id_trabajador_x_proyecto, nombre_trabajador, idresumen_q_s_asistencia, numero_sabado) {
  
  if (estado_editar_asistencia) {
    // Asignamos un val:8 al sabatical
    if ($(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).is(':checked')) {

      $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', false);
      toastr.error(`<h5>Guarda las horas</h5> guarda estas horas para "asignar o quitar" un sabatical.`);

    } else { // Asignamos un val:0 al sabatical

      $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', true);
      toastr.error(`<h5>Guarda las horas</h5> guarda estas horas para "asignar o quitar" un sabatical.`);
    }
    var suma_sabatical = 0;
    
  } else {
    
    if ($(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).is(':checked')) {

      Swal.fire({
        title: "¿Está seguro asignar un sabatical manualmente?",
        html:`El trabajador: <b>${nombre_trabajador}</b> tendra un sabatical para su "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, asignar!",
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch(`../ajax/asistencia_obrero.php?op=agregar_quitar_sabatical_manual`, {
            method: 'POST',
            headers: {'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({
              'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 
              'fecha_asist': format_a_m_d(fecha), 
              'sueldo_x_hora':sueldo_x_hora, 
              'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 
              'fecha_q_s_inicio': format_a_m_d(f1_r), 
              'fecha_q_s_fin': format_a_m_d(f2_r), 
              'numero_q_s':(parseInt(i_r) + 1), 
              'id_trabajador_x_proyecto': id_trabajador_x_proyecto, 
              'numero_sabado':numero_sabado, 
              'estado_sabatical_manual':'1'
            }) ,
          }).then(response => {
            if (!response.ok) { throw new Error(response.statusText) }
            return response.json()
          }).catch(error => { Swal.showValidationMessage(`Request failed: ${error}`); })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value.status) {
            datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r);
            tbla_principal(localStorage.getItem('nube_idproyecto'));
            Swal.fire("Asignado!", `El sabatical manual de: <b>${nombre_trabajador}</b> a sido guardado con éxito.`, "success");
          } else {
            ver_errores(result.value);
            $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', false);
          }          
        }else{
          $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', false);
        }
      });  
  
    } else {
  
      Swal.fire({
        title: "¿Está seguro ANULAR el sabatical manualmente?",
        html: `Al trabajador: <b>${nombre_trabajador}</b> se le anulará un sabatical para su "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, anular!",
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch(`../ajax/asistencia_obrero.php?op=agregar_quitar_sabatical_manual`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 
              'fecha_asist': format_a_m_d(fecha), 
              'sueldo_x_hora':sueldo_x_hora, 
              'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 
              'fecha_q_s_inicio': format_a_m_d(f1_r), 
              'fecha_q_s_fin': format_a_m_d(f2_r), 
              'numero_q_s':(parseInt(i_r) + 1), 
              'id_trabajador_x_proyecto': id_trabajador_x_proyecto, 
              'numero_sabado':numero_sabado, 
              'estado_sabatical_manual':'0'
            }) ,
          }).then(response => {
            if (!response.ok) { throw new Error(response.statusText) }
            return response.json()
          }).catch(error => { Swal.showValidationMessage(`Request failed: ${error}`); })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value.status) {
            datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r);
            tbla_principal(localStorage.getItem('nube_idproyecto'));
            Swal.fire("Quitado!", `El sabatical de: <b>${nombre_trabajador}</b> a sido QUITADO con éxito.`, "success");
          } else {
            ver_errores(result.value);
            $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', true);
          }
        }else{
          $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', true);
        }
      });
    }
  }
  
}

// GUARDAR - SABATICAL 1 MULTIPLE - // voy a eliminar esta funcion cuando no lo NECESITE -----------------------
function calcular_todos_sabatical_1_no_usado() {

  console.log(array_sabatical_1);
  
  if (estado_editar_asistencia) {
    // Asignamos un val:8 al sabatical
    if ($(`#checkbox_sabatical_todos_1`).is(':checked')) {
      
      $(`#checkbox_sabatical_todos_1`).prop('checked', false);
      toastr.error(`<h5>Guarda las horas</h5> Guarda estas horas para "asignar o quitar" un sabatical.`);

    } else { // Asignamos un val:0 al sabatical
      
      $(`#checkbox_sabatical_todos_1`).prop('checked', true);
      toastr.error(`<h5>Guarda las horas</h5> Guarda estas horas para "asignar o quitar" un sabatical.`);
    }
    
  } else {
    
    if ($(`#checkbox_sabatical_todos_1`).is(':checked')) {

      Swal.fire({
        title: "¿Está seguro de ASIGNAR TODOS los sabaticales manualmente?",
        html:`A <b>TODOS</b> los trabajadores <b class="text-success">asignará</b> un sabatical para su "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, asignar!",
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch(`../ajax/asistencia_obrero.php?op=agregar_quitar_sabatical_manual_todos`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              'sabatical_trabajador': array_sabatical_1, 
              'estado_sabatical_manual':'1',
            }) ,
          }).then(response => {
            if (!response.ok) { throw new Error(response.statusText) }
            return response.json()
          }).catch(error => { Swal.showValidationMessage(`Request failed: ${error}`); })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value.status) {
            datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r); 
            tbla_principal(localStorage.getItem('nube_idproyecto'));
            Swal.fire("Asignado!", `Todos los sabaticales manuales a sido guardado con éxito.`, "success");
          } else {
            ver_errores(result.value);
            $(`#checkbox_sabatical_todos_1`).prop('checked', false);
          }
          
        }else{
          $(`#checkbox_sabatical_todos_1`).prop('checked', false);
        }
      });  
  
    } else {
  
      Swal.fire({
        title: "¿Está seguro ANULAR TODOS los sabaticales manualmente?",
        html: `A <b>TODOS</b> los trabajadores se le <b class="text-danger">anulará</b> un sabatical para su "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, anular!",
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch(`../ajax/asistencia_obrero.php?op=agregar_quitar_sabatical_manual_todos`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              'sabatical_trabajador': array_sabatical_1, 
              'estado_sabatical_manual':'0',
            }) ,
          }).then(response => {
            if (!response.ok) { throw new Error(response.statusText) }
            return response.json()
          }).catch(error => { Swal.showValidationMessage(`Request failed: ${error}`); })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value.status) {
            datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r); 
            tbla_principal(localStorage.getItem('nube_idproyecto'));
            Swal.fire("Anulado!", `Todos los sabaticales manuales a sido guardado con éxito.`, "success");
          } else {
            ver_errores(result.value);
            $(`#checkbox_sabatical_todos_1`).prop('checked', true);
          }
                 
        }else{
          $(`#checkbox_sabatical_todos_1`).prop('checked', true);
        }
      });
    }
  }
}

// GUARDAR - SABATICAL 2 MULTIPLE - // voy a eliminar esta funcion cuando no lo NECESITE -----------------------
function calcular_todos_sabatical_2_no_usado() {
  
  if (estado_editar_asistencia) {
    // Asignamos un val:8 al sabatical
    if ($(`#checkbox_sabatical_todos_2`).is(':checked')) {
      
      $(`#checkbox_sabatical_todos_2`).prop('checked', false);
      toastr.error(`<h5>Guarda las horas</h5> Guarda estas horas para "asignar o quitar" un sabatical.`);

    } else { // Asignamos un val:0 al sabatical
      
      $(`#checkbox_sabatical_todos_2`).prop('checked', true);
      toastr.error(`<h5>Guarda las horas</h5> Guarda estas horas para "asignar o quitar" un sabatical.`);
    }
    
  } else {
    
    if ($(`#checkbox_sabatical_todos_2`).is(':checked')) {

      Swal.fire({
        title: "¿Está seguro de ASIGNAR TODOS los sabaticales manualmente?",
        html:`A <b>TODOS</b> los trabajadores <b class="text-success">asignará</b> un sabatical para su "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, asignar!",
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch(`../ajax/asistencia_obrero.php?op=agregar_quitar_sabatical_manual_todos`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              'sabatical_trabajador': array_sabatical_2, 
              'estado_sabatical_manual':'1',
            }) ,
          }).then(response => {
            if (!response.ok) { throw new Error(response.statusText) }
            return response.json()
          }).catch(error => { Swal.showValidationMessage(`Request failed: ${error}`); })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value.status) {
            datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r); 
            tbla_principal(localStorage.getItem('nube_idproyecto')); 
            Swal.fire("Asignado!", `Todos los sabaticales manuales a sido guardado con éxito.`, "success");
          } else {
            ver_errores(result.value);
            $(`#checkbox_sabatical_todos_2`).prop('checked', false);
          }
          
        }else{
          $(`#checkbox_sabatical_todos_2`).prop('checked', false);
        }
      });  
  
    } else {
  
      Swal.fire({
        title: "¿Está seguro ANULAR TODOS los sabaticales manualmente?",
        html: `A <b>TODOS</b> los trabajadores se le <b class="text-danger">anulará</b> un sabatical para su "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, anular!",
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch(`../ajax/asistencia_obrero.php?op=agregar_quitar_sabatical_manual_todos`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              'sabatical_trabajador': array_sabatical_2, 
              'estado_sabatical_manual':'0',
            }) ,
          }).then(response => {
            if (!response.ok) { throw new Error(response.statusText) }
            return response.json()
          }).catch(error => { Swal.showValidationMessage(`Request failed: ${error}`); })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value.status) {
            datos_quincena(ids_q_asistencia_r, f1_r, f2_r, i_r, cant_dias_asistencia_r); 
            tbla_principal(localStorage.getItem('nube_idproyecto')); 
            Swal.fire("Anulado!", `Todos los sabaticales manuales a sido guardado con éxito.`, "success");
          } else {
            ver_errores(result.value);
            $(`#checkbox_sabatical_todos_2`).prop('checked', true);
          }
                 
        }else{
          $(`#checkbox_sabatical_todos_2`).prop('checked', true);
        }
      });
    }
  }
}