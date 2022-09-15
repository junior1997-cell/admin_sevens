var tabla_principal, tabla_horas, tabla_qs;

var array_asistencia = []; var array_trabajador = [];

var array_sabatical_1 = []; var array_sabatical_2 = [];

var array_pago_contador = []; var array_agregar_horas = [];

var f1_r = 0, f2_r = 0, i_r = 0, cant_dias_asistencia_r = 0; var estado_editar_asistencia = false;

var idtrabajador_por_proyecto_r = 0;

var array_btn_fechas = [];

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lAsistencia").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  tbla_principal(localStorage.getItem('nube_idproyecto'));
  listar_botones_q_s(localStorage.getItem('nube_idproyecto')); 

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_adicional_descuento").on("click", function (e) { $("#submit-form-adicional-descuento").submit(); });

  $("#guardar_registro_justificacion").on("click", function (e) { $("#submit-form-justificacion").submit(); });

  $("#guardar_registro_fechas_actividades").on("click", function (e) { $("#submit-form-fechas-actividades").submit(); });

  $(".horas-multiples").on("click", function (e) { $("#form-horas-multiples").submit(); });  

  // ══════════════════════════════════════ TIMEPIKER  ══════════════════════════════════════
  //Timepicker
  // $('#timepicker').datetimepicker({ /*format: 'LT',*/ format:'HH:mm ', lang:'ru' })

  $('#fecha_inicio_actividad').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
  $('#fecha_inicio_actividad').datepicker({ format: "dd-mm-yyyy", language: "es", autoclose: true, clearBtn: true, daysOfWeekDisabled: [6], weekStart: 0, orientation: "bottom auto", todayBtn: true });

  $('#fecha_fin_actividad').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
  $('#fecha_fin_actividad').datepicker({ format: "dd-mm-yyyy", language: "es", autoclose: true, clearBtn: true, daysOfWeekDisabled: [6], weekStart: 0, orientation: "bottom auto", todayBtn: true });

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

  if (estados == 1 ) {
    $("#btn-registrar").show();
    $("#btn-regresar").hide();
    $("#btn-editar").hide();
    $("#btn-guardar").hide();

    $("#tabla-asistencia-trab").show();
    $("#ver_asistencia").hide();
    $("#detalle_asistencia").hide();
    $("#detalle_qs").hide();

    estado_editar_asistencia = false;
  } else if (estados == 2) {
    
    $("#btn-registrar").hide();
    $("#btn-regresar").show();
    $("#btn-editar").show();
    $("#btn-guardar").hide();

    $("#tabla-asistencia-trab").hide();
    $("#ver_asistencia").show();
    $("#detalle_asistencia").hide();
    $("#detalle_qs").hide();

    estado_editar_asistencia = false;      
  } else if (estados == 3) {
      
    $("#btn-registrar").hide();
    $("#btn-regresar").show();
    $("#btn-editar").hide();
    $("#btn-guardar").hide();

    $("#tabla-asistencia-trab").hide();
    $("#ver_asistencia").hide();
    $("#detalle_asistencia").show();
    $("#detalle_qs").hide();

    estado_editar_asistencia = false;
  } else if (estados == 4) {
        
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
    $("#btn-guardar").hide();

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
    $("#btn-guardar").show();

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
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,11,12,13,3,4,5,6,8,9,10], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,11,12,13,3,4,5,6,8,9,10], } }, { extend: 'pdfHtml5', footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,11,12,13,3,4,5,6,8,9,10], }  } , {extend: "colvis"} ,
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
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [11,12,13], visible: false, searchable: false, },
    ],
  }).DataTable();

  //Suma ACUMULADO
  $.post("../ajax/asistencia_obrero.php?op=suma_total_acumulado", { 'nube_idproyecto': nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); //console.log(e);

    if (e.status == true) {       
      $(".total_acumulado_trabjadores").html(`S/ ${formato_miles(e.data.pago_quincenal)}`);
    } else {
      $(".total_acumulado_trabjadores").html("S/ 0.00");
    }
  }).fail( function(e) { ver_errores(e); } );  
}

function listar_botones_q_s(nube_idproyecto) {
  array_btn_fechas =[];
  $('#lista_quincenas').html('<div class="my-3" ><i class="fas fa-spinner fa-pulse fa-2x"></i>&nbsp;&nbsp;&nbsp;Cargando...</div>');

  //Listar quincenas(botones)
  $.post("../ajax/asistencia_obrero.php?op=listarquincenas_botones", { nube_idproyecto: nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); console.log(e);

    if (e.status == true) {
      // validamos la existencia de DATOS
      if ( e.data == null ) {
        $('#lista_quincenas').html(`<div class="alert alert-danger alert-dismissible w-450px">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
          <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
          Lo mas probable es que no hayas selecionado un proyecto. <br>Clic en el <span class="bg-color-8eff27 p-1 rounded-lg text-dark"> <i class="fa-solid fa-screwdriver-wrench"></i> boton verde</span> para seleccionar alguno.
        </div>`);        
      } else {
        if (e.data.fecha_inicio == '0000-00-00' || e.data.fecha_inicio == null || e.data.fecha_inicio == '' || e.data.fecha_fin == '0000-00-00' || e.data.fecha_fin == null || e.data.fecha_fin == '') {
          $('#lista_quincenas').html(`<div class="alert alert-danger alert-dismissible w-450px">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
            <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
            No has definido las de fechas del proyecto. <br>Clic en el <span class="bg-green p-1 rounded-lg"> <i class="far fa-calendar-alt"></i> boton verde</span> para actualizar las fechas de actividades.
          </div>`);
        } else {        
        
          var dia_regular = 0; var weekday_regular = extraer_dia_semana(e.data.fecha_inicio); var estado_regular = false;

          if (weekday_regular == "do") { dia_regular = -1; } else { if (weekday_regular == "lu") { dia_regular = -2; } else { if (weekday_regular == "ma") { dia_regular = -3; } else { if (weekday_regular == "mi") { dia_regular = -4; } else { if (weekday_regular == "ju") { dia_regular = -5; } else { if (weekday_regular == "vi") { dia_regular = -6; } else { if (weekday_regular == "sa") { dia_regular = -7; } } } } } } }
          // console.log(e.data.fecha_inicio, dia_regular, weekday_regular);
          if (e.data.fecha_pago_obrero == "quincenal") {

            $('#lista_quincenas').html('');

            var fecha = format_d_m_a(e.data.fecha_inicio); //console.log(fecha);

            var fecha_i = sumaFecha(0,fecha);   var cal_quincena  = e.data.plazo/14;

            var i=0; var cont=0; 

            while (i <= cal_quincena) {

              cont=cont+1; var fecha_inicio = fecha_i;

              if (estado_regular) {
                fecha=sumaFecha(13,fecha_inicio);     //console.log(fecha_inicio+'-'+fecha);
              } else {
                fecha=sumaFecha(14+dia_regular,fecha_inicio); estado_regular = true;     //console.log(fecha_inicio+'-'+fecha);
              }           

              $('#lista_quincenas').append(` <button type="button" id="boton-${i}" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="datos_quincena('${fecha_inicio}', '${fecha}', '${i}', 14);"><i class="far fa-calendar-alt"></i> Quincena ${cont}<br>${fecha_inicio} // ${fecha}</button>`)
              array_btn_fechas.push({'num_q_s': cont, 'f_inicial': fecha_inicio, 'f_final': fecha, });

              fecha_i =sumaFecha(1,fecha);

              i++;
            }
          } else {
            if (e.data.fecha_pago_obrero == "semanal") {

              $('#lista_quincenas').html('');

              var fecha = format_d_m_a(e.data.fecha_inicio);  var fecha_f = ""; var fecha_i = ""; //e.data.fecha_inicio

              var cal_mes  = false; var i=0;  var cont=0;

              while (cal_mes == false) {

                cont = cont+1; fecha_i = fecha;

                if (estado_regular) {
                  fecha_f = sumaFecha(6, fecha_i);
                } else {
                  fecha_f = sumaFecha(7+dia_regular, fecha_i); estado_regular = true;
                }            

                let val_fecha_f = new Date( format_a_m_d(fecha_f) ); let val_fecha_proyecto = new Date(e.data.fecha_fin);
                
                // console.log(fecha_f + ' - '+e.data.fecha_fin);

                $('#lista_quincenas').append(` <button id="boton-${i}" type="button" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="datos_quincena('${fecha_i}', '${fecha_f}', '${i}', 7);"><i class="far fa-calendar-alt"></i> Semana ${cont}<br>${fecha_i} // ${fecha_f}</button>`)
                
                array_btn_fechas.push({'num_q_s': cont, 'f_inicial': fecha_i, 'f_final': fecha_f, });

                if (val_fecha_f.getTime() >= val_fecha_proyecto.getTime()) { cal_mes = true; }else{ cal_mes = false;}

                fecha = sumaFecha(1,fecha_f);

                i++;
              } 
            } else { 
              $('#lista_quincenas').html(`<div class="info-box shadow-lg w-600px"> 
                <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span> 
                <div class="info-box-content"> 
                  <span class="info-box-text">Alerta</span> 
                  <span class="info-box-number">No has definido los bloques de fechas del proyecto. <br>Ingresa al ESCRITORIO y EDITA tu proyecto selecionado.</span> 
                </div> 
              </div>`);
            }
          }
        }
      }
    } else {
      ver_errores(e);
    }
        
    //console.log(fecha);
  }).fail( function(e) { ver_errores(e); } );
}

// listamos la data de una quincena selecionada
function datos_quincena(f1, f2, i, cant_dias_asistencia) {
  console.log(f1, f2, i, cant_dias_asistencia);

  //pocision_scroll_btn();
  
  mostrar_form_table(2);

  f1_r = f1; f2_r = f2; i_r = i; cant_dias_asistencia_r = cant_dias_asistencia;

  // ocultamos las tablas  
  $("#ver_asistencia").hide();
  $('#cargando-registro-asistencia').show();

  // cambiamos el valor del colspan
  $("#dias_asistidos_s_q").attr("colspan", cant_dias_asistencia);

  // cambiamos el TABLE-HEAD tipo de pago
  if (cant_dias_asistencia == 7) { $(".head_pago_q_s").html("Pago semanal"); }else{ $(".head_pago_q_s").html("Pago quincenal"); }

  $("#btn-editar").show(); $("#btn-guardar").hide();  

  // vaciamos el array
  array_asistencia = []; array_trabajador = [];
  array_sabatical_1 = []; array_sabatical_2 = [];
  array_pago_contador = []; array_agregar_horas = []

  // pintamos el botón
  pintar_boton_selecionado(i);

  var nube_idproyect =localStorage.getItem('nube_idproyecto');  //console.log('Quicena: '+f1 + ' al ' +f2 + ' proyect-id: '+nube_idproyect);
  
  var fecha_inicial_quincena = f1; var table_numero_semana = ""; var table_dia_semana = ""; 

  var dia_regular = 0; var count_bloque_q_s = 1; var total_pago = 0;

  var weekday_regular = extraer_dia_semana(format_a_m_d(fecha_inicial_quincena));
  
  // asignamos un numero para restar y llegar al dia DOMIGO
  if (weekday_regular == "do") { dia_regular = -0; } else { if (weekday_regular == "lu") { dia_regular = -1; } else { if (weekday_regular == "ma") { dia_regular = -2; } else { if (weekday_regular == "mi") { dia_regular = -3; } else { if (weekday_regular == "ju") { dia_regular = -4; } else { if (weekday_regular == "vi") { dia_regular = -5; } else { if (weekday_regular == "sa") { dia_regular = -6; } } } } } } }

  var fecha_inicial_quincena_regular = sumaFecha(dia_regular, fecha_inicial_quincena);

  // Asignamos: dia semana, numero semana. Para regular la semana
  for ( var j = 1; j<=dia_regular*-1; j++ ) {

    var weekday = extraer_dia_semana(format_a_m_d(fecha_inicial_quincena_regular));  

    table_dia_semana = table_dia_semana.concat(`<th class="p-x-12px py-0 bg-color-acc3c7"> ${fecha_inicial_quincena_regular.substr(0,2)} <br> ${weekday} </th>`);

    // table_numero_semana = table_numero_semana.concat(`<th class="p-x-12px bg-color-acc3c7"> ${count_dias_de_asistencias} </th>`);

    // aumentamos mas un dia hasta llegar al dia "dia_regular"
    fecha_inicial_quincena_regular = sumaFecha(1,fecha_inicial_quincena_regular); //console.log(count_dias_de_asistencias);

    //count_dias_de_asistencias++;
  }

  // asignamos: dia semana, numero semana. Con respecto al trabajo
  for (i = 1; i <=cant_dias_asistencia + dia_regular; i++) { 

    var weekday = extraer_dia_semana(format_a_m_d(fecha_inicial_quincena));  

    if (weekday != 'sa') {

      table_dia_semana = table_dia_semana.concat(`<th class="p-x-12px py-0"> ${fecha_inicial_quincena.substr(0,2)} <br> ${weekday} </th>`);

      // table_numero_semana = table_numero_semana.concat(`<th class="p-x-12px"> ${count_dias_de_asistencias} </th>`);

    } else {

      table_dia_semana = table_dia_semana.concat(`<th class="p-x-12px py-0 bg-color-acc3c7">${fecha_inicial_quincena.substr(0,2)} <br> ${weekday} </th>`);
      
      // table_numero_semana = table_numero_semana.concat(`<td class="p-x-12px bg-color-acc3c7"> ${count_dias_de_asistencias} </td>`);
    }

    // aumentamos mas un dia hasta llegar al dia 15
    fecha_inicial_quincena = sumaFecha(1,fecha_inicial_quincena); //console.log(count_dias_de_asistencias);
    //count_dias_de_asistencias++
  } //end for

  $('.data-dia-semana').html(table_dia_semana);

  // $('.data-numero-semana').html(table_numero_semana);
  
  $.post("../ajax/asistencia_obrero.php?op=ver_datos_quincena", {f1:format_a_m_d(f1),f2:format_a_m_d(f2),nube_idproyect:nube_idproyect}, function (e, status) {
        
    e =JSON.parse(e); console.log(e);   

    $(".data_table_body").html('');  

    var count_sabatical_1_total = 0;
    var count_sabatical_2_total = 0;

    var count_pago_contador_total = 0;
    
    if (e.status == true) {
      $.each(e.data, function (index, value) {
        count_bloque_q_s = 1;
        var count_dias_asistidos = 0; var horas_total = 0; var horas_nomr_total = 0; var horas_extr_total = 0; var sabatical = 0;
        
        var tabla_bloc_HN_asistencia_3=""; var tabla_bloc_HE_asistencia_2 =""; var estado_hallando_sabado = true;

        // SI hay alguna asistencia en la BD ═════════════════════════════════════════════════════════════════════════════════════════════════════════════
        if (value.asistencia.length != 0) {

          var i;  var fecha = f1; //console.log("tiene data");
          
          // renellamos hasta el dia inicial
          for ( var j = 1; j<=dia_regular*-1; j++ ) {

            tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center bg-color-acc3c7"> <span class="span_asist " >-</span> </td>`);
                
            tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center bg-color-acc3c7"> <span class=" " >-</span> </td>`);
            
            //console.log(count_bloque_q_s);
            count_bloque_q_s++; 
          }

          for (i = 1; i <=cant_dias_asistencia+dia_regular; i++) {

            var estado_fecha = false; var fecha_asist = ""; var hora_n = 0; var hora_e = 0;

            // buscamos las fechas asistidas
            for (let i = 0; i < value.asistencia.length; i++) { 
              
              let split_f = format_d_m_a( value.asistencia[i]['fecha_asistencia'] ) ; 
              
              let fecha_semana = new Date( format_a_m_d(fecha) ); let fecha_asistencia = new Date(format_a_m_d(split_f));
              
              if ( fecha_semana.getTime() == fecha_asistencia.getTime() ) { 

                horas_total = horas_total + parseFloat(value.asistencia[i]['horas_normal_dia']);

                estado_fecha = true; fecha_asist = value.asistencia[i]['fecha_asistencia'];  hora_n = value.asistencia[i]['horas_normal_dia'];  hora_e = value.asistencia[i]['horas_extras_dia'];

                horas_total = horas_total + value.asistencia[i]['horas_normal_dia'] + value.asistencia[i]['horas_extras_dia'];

                horas_nomr_total = horas_nomr_total + parseFloat(value.asistencia[i]['horas_normal_dia']);

                horas_extr_total = horas_extr_total + parseFloat(value.asistencia[i]['horas_extras_dia']);

                count_dias_asistidos++;                          
              }
            } //end for

            // imprimimos la fecha de asistencia: "encontrada" ════════════════════════════════════════════════════════════════════════════════════════
            if (estado_fecha) {

              var weekday = extraer_dia_semana(fecha_asist); //console.log(weekday);

              if (weekday != 'sa') {

                tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center"> 
                  <span class="span_asist span_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" >${hora_n}</span> 
                  <input class="w-35px input_asist hr_multiple input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" onkeyup="delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 100 );"  type="text" value="${hora_n}" autocomplete="off" >
                </td>`);
                
                tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center"> 
                  <span class=" span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" >${hora_e}</span> 
                  <input class="w-35px input_HE_${value.idtrabajador_por_proyecto}_${i} input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} hidden" id="input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="${hora_e}" >
                </td>`);
                // recoge sabados
                array_asistencia.push( { 
                  'id_trabajador':value.idtrabajador_por_proyecto, 
                  'fecha_asistida':format_d_m_a(fecha_asist), 
                  'cant_dias': cant_dias_asistencia,   
                  'cant_trabajador': e.data.length,
                  'sueldo_hora':value.sueldo_hora,
                  'sabatical_manual_1': value.sabatical_manual_1,
                  'sabatical_manual_2':value.sabatical_manual_2
                } );
                // no recoge sabados
                array_agregar_horas.push( { 
                  'id_trabajador':value.idtrabajador_por_proyecto, 
                  'fecha_asistida':format_d_m_a(fecha_asist), 
                  'cant_dias': cant_dias_asistencia,   
                  'cant_trabajador': e.data.length,
                  'sueldo_hora':value.sueldo_hora,
                  'sabatical_manual_1': value.sabatical_manual_1,
                  'sabatical_manual_2':value.sabatical_manual_2
                } );

              } else {
                // SABATICALES
                if (estado_hallando_sabado) { 
                  if (value.sabatical_manual_1 == "0") {

                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="0" > </td>`);
                    
                    count_dias_asistidos -= 1;
                  } else {
                    if (value.sabatical_manual_1 == "1") {

                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="8" > </td>`);
                        
                      sabatical = 1; count_sabatical_1_total++;
                    } else {
    
                      if (hora_n == 8 ) {
    
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="8" > </td>`);
                                                
                        sabatical = 1; count_sabatical_1_total++;
                      } else {
    
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox"  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="0" > </td>`);
                        
                        count_dias_asistidos -= 1; //horas_nomr_total -= 8;          
                      }
                    }
                  }

                  array_sabatical_1.push({ 
                    'idresumen_q_s_asistencia':value.idresumen_q_s_asistencia,
                    'id_trabajador':value.idtrabajador_por_proyecto, 
                    'fecha_asistida':fecha_asist,
                    'sueldo_hora':value.sueldo_hora,
                    'numero_sabado':'1',
                    'fecha_q_s_inicio': format_a_m_d(f1_r), 
                    'fecha_q_s_fin': format_a_m_d(f2_r), 
                    'numero_q_s':(parseInt(i_r) + 1),
                  });
                  estado_hallando_sabado = false; // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                } else {
                  if (value.sabatical_manual_2 == "0") {

                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="0" > </td>`);
                    
                  } else {
                    if (value.sabatical_manual_2 == "1") {

                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="8" > </td>`);
                      
                      // count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;                    
    
                      sabatical += 1; count_sabatical_2_total++;
                    } else {
    
                      if (hora_n == 8) {                      
    
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center  center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="8" > </td>`);
                                                  
                        sabatical += 1; count_sabatical_2_total++;
                        
                      } else { 
                        
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical  sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="0" > </td>`);                    
                        
                        count_dias_asistidos -= 1; //horas_nomr_total -= 8;
                      }                  
                    }
                  } 

                  array_sabatical_2.push({ 
                    'idresumen_q_s_asistencia':value.idresumen_q_s_asistencia,
                    'id_trabajador':value.idtrabajador_por_proyecto, 
                    'fecha_asistida':fecha_asist, 
                    'sueldo_hora':value.sueldo_hora,
                    'numero_sabado':'2',
                    'fecha_q_s_inicio': format_a_m_d(f1_r), 
                    'fecha_q_s_fin': format_a_m_d(f2_r), 
                    'numero_q_s':(parseInt(i_r) + 1),
                  });                              
                }

                array_asistencia.push({ 
                  'id_trabajador':value.idtrabajador_por_proyecto, 
                  'fecha_asistida':format_d_m_a(fecha_asist), 
                  'class_input_hn':`input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}`, 
                  'class_input_he':`input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}`,
                  'sueldo_hora':value.sueldo_hora
                });
              }             
              
            } else { 

              // imprimimos la fecha de asistencia: "No encontrada" ═══════════════════════════════════════════════════════════════════════════════════

              var weekday = extraer_dia_semana(format_a_m_d(fecha)); //console.log(weekday);
              
              if (weekday != 'sa') {

                tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center">
                  <span class="span_asist  span_HN_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> 
                  <input class="w-35px input_asist hr_multiple input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" onkeyup="delay(function(){ calcular_he('${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 100 );"  type="text" value="" autocomplete="off" >
                </td>`);
                
                tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center"> 
                  <span class=" span_HE_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> 
                  <input class="w-35px input_HE_${value.idtrabajador_por_proyecto}_${i} input_HE_${value.idtrabajador_por_proyecto}_${fecha} hidden" type="text" value="" >
                </td>`);

                // recoge sabados
                array_asistencia.push( { 
                  'id_trabajador':value.idtrabajador_por_proyecto, 
                  'fecha_asistida':fecha, 
                  'cant_dias': cant_dias_asistencia,   
                  'cant_trabajador': e.data.length,
                  'sueldo_hora':value.sueldo_hora,
                  'sabatical_manual_1': value.sabatical_manual_1,
                  'sabatical_manual_2':value.sabatical_manual_2
                } );

                // no recoge sabados
                array_agregar_horas.push( { 
                  'id_trabajador':value.idtrabajador_por_proyecto, 
                  'fecha_asistida':fecha, 
                  'cant_dias': cant_dias_asistencia,   
                  'cant_trabajador': e.data.length,
                  'sueldo_hora':value.sueldo_hora,
                  'sabatical_manual_1': value.sabatical_manual_1,
                  'sabatical_manual_2':value.sabatical_manual_2
                } );

              } else {
                // SABATICALES
                if (estado_hallando_sabado) { 
                  if (value.sabatical_manual_1 == "0") {
    
                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                                    
                  } else {
                    if (value.sabatical_manual_1 == "1") {
    
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                      
                      count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
      
                      sabatical = 1;
                    } else {
                      // El sistema calcula el "sabatical" conforme a las horas del trabajador
                      if (horas_nomr_total >= 44 ) {
      
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                        
                        count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
      
                        sabatical = 1;
                      } else {
      
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                      
                      }
                    }
                  }    
                  
                  array_sabatical_1.push({ 
                    'idresumen_q_s_asistencia':value.idresumen_q_s_asistencia,
                    'id_trabajador':value.idtrabajador_por_proyecto, 
                    'fecha_asistida':format_a_m_d(fecha), 
                    'sueldo_hora':value.sueldo_hora,
                    'numero_sabado':'1',
                    'fecha_q_s_inicio': format_a_m_d(f1_r), 
                    'fecha_q_s_fin': format_a_m_d(f2_r), 
                    'numero_q_s':(parseInt(i_r) + 1),
                  });
    
                  estado_hallando_sabado = false; // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                } else {
                  if (value.sabatical_manual_2 == "0") {
    
                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                    
                  } else {
                    if (value.sabatical_manual_2 == "1") {
    
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                      
                      count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
      
                      sabatical = sabatical + 1;
                    } else {
      
                      if (sabatical == "1") {
                        // El sistema calcula el "sabatical" conforme a las horas del trabajador
                        if ((horas_nomr_total - 52) >= 44 ) {
      
                          tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                          
                          count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
      
                          sabatical = sabatical + 1;
                        } else {
      
                          tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                                                
                        }
                      } else {
      
                        if ((horas_nomr_total - 44) >= 44 ) {
      
                          tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
      
                          count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
      
                          sabatical = sabatical + 1;
                        } else {
      
                          tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                                                
                        }
                      }                  
                    }
                  }    
                  
                  array_sabatical_2.push({ 
                    'idresumen_q_s_asistencia':value.idresumen_q_s_asistencia,
                    'id_trabajador':value.idtrabajador_por_proyecto, 
                    'fecha_asistida':format_a_m_d(fecha), 
                    'sueldo_hora':value.sueldo_hora,
                    'numero_sabado':'2',
                    'fecha_q_s_inicio': format_a_m_d(f1_r), 
                    'fecha_q_s_fin': format_a_m_d(f2_r), 
                    'numero_q_s':(parseInt(i_r) + 1),
                  });
                }  
                
                array_asistencia.push( { 
                  'id_trabajador':value.idtrabajador_por_proyecto, 
                  'fecha_asistida':fecha, 
                  'class_input_hn':`input_HN_${value.idtrabajador_por_proyecto}_${fecha}`,
                  'class_input_he':`input_HE_${value.idtrabajador_por_proyecto}_${fecha}`,
                  'sueldo_hora':value.sueldo_hora
                } );
              }
            }

            // aumentamos mas un dia hasta llegar al dia 15
            fecha = sumaFecha(1,fecha);
            
            count_bloque_q_s++; // console.log(count_bloque_q_s);
          } //end for
          // console.log('-----------------------------------------------------------');
        
        } else {

          // NO hay alguna asistencia en la BD ════════════════════════════════════════════════════════════════════════════════════════════════════════
          var fecha = f1;  

          // renellamos hasta el dia inicial
          for ( var j = 1; j<=dia_regular*-1; j++ ) {

            tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center bg-color-acc3c7"> <span class="span_asist " >-</span> </td>`);
                
            tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center bg-color-acc3c7"> <span class=" " >-</span> </td>`);
            
            //.log(count_bloque_q_s);
            count_bloque_q_s++; 
          }

          for (i = 1; i <=cant_dias_asistencia+dia_regular; i++) { 

            var weekday = extraer_dia_semana(format_a_m_d(fecha));

            if (weekday != 'sa') {

              tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center"> 
                <span class="span_asist span_HN_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> 
                <input class="w-35px input_asist hr_multiple input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" onkeyup="delay(function(){ calcular_he('${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 100 ); "  type="text" value="" autocomplete="off">
              </td>`);
              
              tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center"> 
                <span class=" span_HE_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> 
                <input class="w-35px input_HE_${value.idtrabajador_por_proyecto}_${i} input_HE_${value.idtrabajador_por_proyecto}_${fecha} hidden" type="text" value="" >
              </td>`);

              // recoge sabados
              array_asistencia.push({ 
                'id_trabajador':value.idtrabajador_por_proyecto, 
                'fecha_asistida':fecha, 
                'cant_dias': cant_dias_asistencia,   
                'cant_trabajador': e.data.length,
                'sueldo_hora':value.sueldo_hora,
                'sabatical_manual_1': value.sabatical_manual_1,
                'sabatical_manual_2':value.sabatical_manual_2
              });

              // no recoge sabados
              array_agregar_horas.push({ 
                'id_trabajador':value.idtrabajador_por_proyecto, 
                'fecha_asistida':fecha, 
                'cant_dias': cant_dias_asistencia,   
                'cant_trabajador': e.data.length,
                'sueldo_hora':value.sueldo_hora,
                'sabatical_manual_1': value.sabatical_manual_1,
                'sabatical_manual_2':value.sabatical_manual_2
              });

            } else {
              // SABATICALES
              if (estado_hallando_sabado) { 
                if (value.sabatical_manual_1 == "0") {

                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                                  
                } else {
                  if (value.sabatical_manual_1 == "1") {

                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                    
                    count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
    
                    sabatical = 1;
                  } else {
                    // El sistema calcula el "sabatical" conforme a las horas del trabajador
                    if (horas_nomr_total >= 44 ) {
    
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                      
                      count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
    
                      sabatical = 1;
                    } else {
    
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                    
                    }
                  }
                }     
                
                array_sabatical_1.push({ 
                  'idresumen_q_s_asistencia':value.idresumen_q_s_asistencia,
                  'id_trabajador':value.idtrabajador_por_proyecto, 
                  'fecha_asistida':format_a_m_d(fecha), 
                  'sueldo_hora':value.sueldo_hora,
                  'numero_sabado':'1',
                  'fecha_q_s_inicio': format_a_m_d(f1_r), 
                  'fecha_q_s_fin': format_a_m_d(f2_r), 
                  'numero_q_s':(parseInt(i_r) + 1),
                });

                estado_hallando_sabado = false; // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
              } else {
                if (value.sabatical_manual_2 == "0") {

                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                  
                } else {
                  if (value.sabatical_manual_2 == "1") {

                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                    
                    count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
    
                    sabatical = sabatical + 1;
                  } else {
    
                    if (sabatical == "1") {
                      // El sistema calcula el "sabatical" conforme a las horas del trabajador
                      if ((horas_nomr_total - 52) >= 44 ) {
    
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                        
                        count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
    
                        sabatical = sabatical + 1;
                      } else {
    
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                                              
                      }
                    } else {
    
                      if ((horas_nomr_total - 44) >= 44 ) {
    
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
    
                        count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
    
                        sabatical = sabatical + 1;
                      } else {
    
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20px" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}',   '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${e.data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                                              
                      }
                    }                  
                  }
                } 
                
                array_sabatical_2.push({ 
                  'idresumen_q_s_asistencia':value.idresumen_q_s_asistencia,
                  'id_trabajador':value.idtrabajador_por_proyecto, 
                  'fecha_asistida':format_a_m_d(fecha), 
                  'sueldo_hora':value.sueldo_hora,
                  'numero_sabado':'2',
                  'fecha_q_s_inicio': format_a_m_d(f1_r), 
                  'fecha_q_s_fin': format_a_m_d(f2_r), 
                  'numero_q_s':(parseInt(i_r) + 1),
                });
              }    
              
              array_asistencia.push( { 
                'id_trabajador':value.idtrabajador_por_proyecto, 
                'fecha_asistida':fecha, 
                'class_input_hn':`input_HN_${value.idtrabajador_por_proyecto}_${fecha}`,   
                'class_input_he':`input_HE_${value.idtrabajador_por_proyecto}_${fecha}`,
                'sueldo_hora':value.sueldo_hora
              } );
            }
            // aumentamos mas un dia hasta llegar al dia 15
            fecha = sumaFecha(1,fecha);

            // console.log(count_bloque_q_s);
            count_bloque_q_s++; 
          } //end for
          // console.log('-----------------------------------------------------------');
        }      

        // asignamos lo trabajadores a un "array"
        array_trabajador.push({ 
          'id_trabajador':value.idtrabajador_por_proyecto, 
          'nombre_trabjador':value.nombres,
          'sueldo_hora':value.sueldo_hora
        });
        
        var tabla_bloc_HN_trabaj_2 =  `<td rowspan="2" class="center-vertical">${value.nombres}</td> <td rowspan="2" class="center-vertical">${value.cargo} <br> ${(value.estado_trabajador==1?'<span class="text-center badge badge-success">Activado</span>':'<span class="text-center badge badge-danger">Desactivado</span>')} </td>`;       

        var tabla_bloc_HN_total_hora_4 =  `<td class="text-center center-vertical"> 
          <span  class="total_HN_${value.idtrabajador_por_proyecto}">${horas_nomr_total}</span> 
        </td>`;
        /// ${count_dias_asistidos}
        var tabla_bloc_HN_total_dia_5 = `<td class="text-center center-vertical" rowspan="2" >
          <span  class="dias_asistidos_${value.idtrabajador_por_proyecto}_percent">${((horas_nomr_total+horas_extr_total)/8).toFixed(2)} </span>
          <input class="dias_asistidos_${value.idtrabajador_por_proyecto}" type="hidden" value="${(horas_nomr_total/8).toFixed(2)}" > 
        </td>`;

        var tabla_bloc_HN_sueldos_6 = `<td class="text-center center-vertical" rowspan="2">${formato_miles(value.sueldo_mensual)}</td>
        <td class="text-center center-vertical" rowspan="2">${value.sueldo_diario}</td>
        <td class="text-center center-vertical" rowspan="2">${value.sueldo_hora}</td>`;
    
        var tabla_bloc_HN_sabatical_7 =  `<td class="text-center center-vertical" rowspan="2">
          <span  class="sabatical_${value.idtrabajador_por_proyecto}">${sabatical}</span>
        </td>`;

        var tabla_bloc_HN_pago_parcial_8 = `<td class="text-center center-vertical"> 
          <span  class="pago_parcial_HN_${value.idtrabajador_por_proyecto}"> ${formato_miles((parseFloat(value.sueldo_hora) * parseFloat(horas_nomr_total)).toFixed(2))}</span> 
        </td>`;
        
        var fechas_adicional = "";
        
        // validamos si existe una suma_adicional 
        if (value.idresumen_q_s_asistencia == "") { fechas_adicional = format_a_m_d(f1); } else { fechas_adicional = value.fecha_registro; }

        var tabla_bloc_HN_descuent_9 = `<td rowspan="2" class="text-center center-vertical"> 
          <span class="span_asist" >${value.adicional_descuento}</span> <input class="w-45px input_asist hidden adicional_descuento_${value.idtrabajador_por_proyecto}" onkeyup="delay(function(){ adicional_descuento('${e.data.length}', '${value.idtrabajador_por_proyecto}') }, 100 );" type="text" value="${value.adicional_descuento}" autocomplete="off" > 
          <span class="badge badge-info float-right cursor-pointer shadow-1px06rem09rem-rgb-52-174-193-77" data-toggle="tooltip" data-original-title="Por descuento" onclick="modal_adicional_descuento( '${value.idresumen_q_s_asistencia}', '${value.idtrabajador_por_proyecto}', '${fechas_adicional}');"><i class="far fa-eye"></i></span>
        </td>`;

        var tabla_bloc_HN_pago_total_10 = `<td rowspan="2" class="text-center center-vertical"> 
          <span  class="val_pago_quincenal_${index+1} pago_quincenal_${value.idtrabajador_por_proyecto}"> ${formato_miles(((parseFloat((parseFloat(value.sueldo_hora) * parseFloat(horas_nomr_total)).toFixed(2)) + parseFloat((parseFloat(value.sueldo_hora) * parseFloat(horas_extr_total)).toFixed(2))) + parseFloat(value.adicional_descuento) ).toFixed(2))} </span> 
        </td>`;

        var tabla_bloc_envio_contador_11 = "";

        // validamos el el envio al contador
        if (value.estado_envio_contador == "") {
          tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox"  id="checkbox_asignar_pago_contador_${value.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', '${value.pago_quincenal}');"> </td>`;        
        } else {
          if (value.estado_envio_contador == "0") {
            tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox"  id="checkbox_asignar_pago_contador_${value.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', '${value.pago_quincenal}');"> </td>`;                  
          } else {
            tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20px checkbox_visible" type="checkbox" checked id="checkbox_asignar_pago_contador_${value.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', '${value.pago_quincenal}');"> </td>`;                  
            count_pago_contador_total++;
          }
        }

        // asignamos pago al contador a un "array"
        if (parseFloat(value.pago_quincenal) > 0) {
          array_pago_contador.push({           
            'fechas_adicional':fechas_adicional,
            'idtrabajador_por_proyecto':value.idtrabajador_por_proyecto,
            'nombres':value.nombres,
            'idresumen_q_s_asistencia':value.idresumen_q_s_asistencia,
            'pago_quincenal':value.pago_quincenal
          });
        } 

        // acumulamos el total de pagos
        total_pago = total_pago + parseFloat( (  (parseFloat((parseFloat(value.sueldo_hora) * parseFloat(horas_nomr_total)).toFixed(2)) + parseFloat( (parseFloat(value.sueldo_hora) * parseFloat(horas_extr_total)).toFixed(2) ) ) + parseFloat(value.adicional_descuento)  ).toFixed(2) );
        
        var tabla_bloc_HN_1 = `<tr>
          <td class="" rowspan="2"><b>${index+1}</b></td>
          <td class="">H/N</td>
          ${tabla_bloc_HN_trabaj_2}
          ${tabla_bloc_HN_asistencia_3} 
          ${tabla_bloc_HN_total_hora_4}
          ${tabla_bloc_HN_total_dia_5} 
          ${tabla_bloc_HN_sueldos_6} 
          ${tabla_bloc_HN_sabatical_7} 
          ${tabla_bloc_HN_pago_parcial_8} 
          ${tabla_bloc_HN_descuent_9}
          ${tabla_bloc_HN_pago_total_10}
          ${tabla_bloc_envio_contador_11}
        </tr>`;      
      
        var tabla_bloc_HE_total_hora_3 = `<td class="text-center"> <span  class="total_HE_${value.idtrabajador_por_proyecto}">${horas_extr_total}</span> </td>`;
      
        var tabla_bloc_HE_pago_parcial_4 =`<td class="text-center"><span  class="pago_parcial_HE_${value.idtrabajador_por_proyecto}"> ${(parseFloat(value.sueldo_hora) * parseFloat(horas_extr_total)).toFixed(2)}</span> </td>`;
        
      
        var tabla_bloc_HE_1 = `<tr>            
          <td class="">H/E</td>
          ${tabla_bloc_HE_asistencia_2 }
          ${tabla_bloc_HE_total_hora_3 }
          ${tabla_bloc_HE_pago_parcial_4 }
        </tr>`;

        //Unimos y mostramos los bloques separados
        $(".data_table_body").append(tabla_bloc_HN_1 + tabla_bloc_HE_1);

      }); // end foreach
    } else {
      ver_errores(e);
    }    

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

    $(".data_table_body").append(tabla_bloc_TOTAL_1);

    $("#ver_asistencia").show();
    $('#cargando-registro-asistencia').hide();    

  }).fail( function(e) { ver_errores(e); } ); //end post - ver_datos_quincena
  
  $('[data-toggle="tooltip"]').tooltip();

  count_dias_asistidos = 0;  horas_nomr_total = 0;   horas_extr_total = 0;
}

// Calculamos las: Horas normal/extras,	Días asistidos,	Sueldo Mensual,	Jornal,	Sueldo hora,	Sabatical,	Pago parcial,	Adicional/descuento,	Pago quincenal
function calcular_he(fecha, id_trabajador, cant_dias_asistencia, sueldo_hora, cant_trabajador , sabatical_manual_1, sabatical_manual_2) {
  //console.log(fecha,  id_trabajador, cant_dias_asistencia, sueldo_hora, cant_trabajador , sabatical_manual_1, sabatical_manual_2);
  //limpiamos los sabaticales
  if (sabatical_manual_1 == '-') { $(`.desglose_q_s_${id_trabajador}_7`).val(''); }
 
  if (sabatical_manual_2 == '-') { $(`.desglose_q_s_${id_trabajador}_14`).val(''); }

  var hora_extr = 0; var hora_norm = 0; var capturar_val_input = $(`#input_HN_${id_trabajador}_${fecha}`).val();

  // console.log(capturar_val_input);

  if ( parseFloat(capturar_val_input) > 8) {

    hora_extr = parseFloat(capturar_val_input) - 8;
    hora_norm = 8;

    $(`.input_HE_${id_trabajador}_${fecha}`).val(hora_extr.toFixed(1)); 
    $(`.span_HE_${id_trabajador}_${fecha}`).html(hora_extr.toFixed(1));   
    $(`#input_HN_${id_trabajador}_${fecha}`).val(hora_norm.toFixed(1));     

  }else{ 

    $(`.span_HE_${id_trabajador}_${fecha}`).html('0.0'); // hora_norm = parseFloat(input_val.value);
    $(`.input_HE_${id_trabajador}_${fecha}`).val(0);
  }

  var suma_hn = 0; var suma_he = 0; var dias_asistidos = 0; var pago_parcial_hn = 0; var pago_parcial_he = 0; var adicional_descuento = 0;

  // calcular pago quincenal
  for (let index = 1; index <= parseInt(cant_dias_asistencia); index++) {

    // console.log( $(`.input_HN_${id_trabajador}_${index}`).val());    console.log( $(`.input_HE_${id_trabajador}_${index}`).val());

    if (parseFloat($(`.input_HN_${id_trabajador}_${index}`).val()) > 0 ) {

      suma_hn = suma_hn + parseFloat($(`.input_HN_${id_trabajador}_${index}`).val());

      dias_asistidos++;
    }

    if (parseFloat($(`.input_HE_${id_trabajador}_${index}`).val()) > 0 ) {

      suma_he = suma_he + parseFloat($(`.input_HE_${id_trabajador}_${index}`).val());
    }

  }

  // calculamos los sabaticales automáticos
  var horas_1_sabado = 0; var horas_2_sabado = 0; var sabatical = 0;

  for (let x = 1; x <= parseInt(cant_dias_asistencia); x++) {
     
    // acumulamos las horas para el "primer" sabatical
    if (sabatical_manual_1 == '-') {
      if ( x < 7 ) {
        if ($(`.desglose_q_s_${id_trabajador}_${x}`).val() > 0) {
          horas_1_sabado += parseFloat($(`.desglose_q_s_${id_trabajador}_${x}`).val());
        }        
      }      
    } 

    // acumulamos las horas para el "segundo" sabatical
    if (sabatical_manual_2 == '-') {
      if ( x > 7 && x < 14 ) {
        if ($(`.desglose_q_s_${id_trabajador}_${x}`).val()  > 0) {
          horas_2_sabado += parseFloat($(`.desglose_q_s_${id_trabajador}_${x}`).val());
        }        
      }
    }
  }

  if (sabatical_manual_1 == '-') {
    if (horas_1_sabado >= 44 ) {
      $(`.desglose_q_s_${id_trabajador}_7`).val('8');
      $(`#checkbox_sabatical_${id_trabajador}_1`).prop('checked', true); suma_hn += 8; dias_asistidos +=1; sabatical += 1; 
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
      $(`#checkbox_sabatical_${id_trabajador}_2`).prop('checked', true); suma_hn += 8; dias_asistidos +=1; sabatical += 1;
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

  // console.log( horas_1_sabado , horas_2_sabado );

  // validamos el adicional descuento
  if (parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) >= 0 || parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) <= 0 ) {

    adicional_descuento =   parseFloat($(`.adicional_descuento_${id_trabajador}`).val());     

  } else {

    adicional_descuento = 0;

    toastr.error(`El dato adicional/descuento:: <h3 class=""> ${$(`.adicional_descuento_${id_trabajador}`).val()} </h3> no es NUMÉRICO, ingrese un número cero o un positivo o un negativo.`);    
  }

  //  pago_parcial_HN_1
  $(`.total_HN_${id_trabajador}`).html(suma_hn.toFixed(1));

  $(`.total_HE_${id_trabajador}`).html(suma_he.toFixed(1));

  $(`.dias_asistidos_${id_trabajador}`).val( `${ ((suma_hn + suma_he) / 8).toFixed(2) }`);
  $(`.dias_asistidos_${id_trabajador}_percent`).html( `${ ((suma_hn + suma_he) / 8).toFixed(2) }`);  

  // asignamos los pagos parciales
  $(`.pago_parcial_HN_${id_trabajador}`).html(formato_miles((suma_hn * parseFloat(sueldo_hora)).toFixed(2)));

  $(`.pago_parcial_HE_${id_trabajador}`).html(formato_miles((suma_he * parseFloat(sueldo_hora)).toFixed(2)));

  // calculamos el pago quincenal con: Pago parcial,	Adicional/descuento
  var pago_quincenal = ( (parseFloat((suma_hn * parseFloat(sueldo_hora)).toFixed(2)) + parseFloat((suma_he * parseFloat(sueldo_hora)).toFixed(2))) + adicional_descuento ).toFixed(2)

  $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(pago_quincenal));

  var suma_total_quincena = 0;

  for (let k = 1; k <= parseInt(cant_trabajador); k++) {    
    //console.log($(`.val_pago_quincenal_${k}`).text(), k); 
    suma_total_quincena = suma_total_quincena + parseFloat(quitar_formato_miles($(`.val_pago_quincenal_${k}`).text())); 
  }

  // console.log(suma_total_quincena);

  $(`.pago_total_quincenal`).html(formato_miles(suma_total_quincena.toFixed(2)));
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
function guardar_fechas_asistencia() {  

  var array_datos_asistencia = []; var array_resumen_qs = []; var horas_extras_dia = 0; var pago_horas_extras = 0;

  // rellenamos el array ASISTENCIA para la bd "ASISTENCIA TRABAJADOR"
  array_asistencia.forEach((key,index) => {

    horas_extras_dia = 0; pago_horas_extras = 0;

    if ( parseFloat($(`.input_HN_${key.id_trabajador}_${key.fecha_asistida}`).val()) >= 0) {

      if ($(`.input_HE_${key.id_trabajador}_${key.fecha_asistida}`).val() == undefined) {
        horas_extras_dia = 0;  pago_horas_extras = 0;
      } else {
        horas_extras_dia = $(`.input_HE_${key.id_trabajador}_${key.fecha_asistida}`).val(); 
        pago_horas_extras = (parseFloat($(`.input_HE_${key.id_trabajador}_${key.fecha_asistida}`).val()) * key.sueldo_hora).toFixed(2);
      }

      array_datos_asistencia.push({ 
        'id_trabajador':key.id_trabajador,        
        'fecha_asistida':format_a_m_d(key.fecha_asistida),
        'nombre_dia':extraer_dia_semana_completo(format_a_m_d(key.fecha_asistida)),
        'horas_normal_dia':$(`.input_HN_${key.id_trabajador}_${key.fecha_asistida}`).val(),
        'pago_normal_dia':(parseFloat($(`.input_HN_${key.id_trabajador}_${key.fecha_asistida}`).val()) * key.sueldo_hora).toFixed(2) ,
        'horas_extras_dia':horas_extras_dia,
        'pago_horas_extras':pago_horas_extras
      });
    }    
  }); 

  // rellenamos el array EXTRAS para la bd "RESUMEN Q S ASISTENCIA"
  array_trabajador.forEach((element,index) => {    
    array_resumen_qs.push({
      'id_trabajador':element.id_trabajador,
      'fecha_q_s_inicio':format_a_m_d(f1_r),
      'fecha_q_s_fin':format_a_m_d(f2_r),
      'num_semana': (parseInt(i_r) + 1),
      'total_hn':quitar_formato_miles($(`.total_HN_${element.id_trabajador}`).text()),
      'total_he':quitar_formato_miles($(`.total_HE_${element.id_trabajador}`).text()),
      'dias_asistidos':$(`.dias_asistidos_${element.id_trabajador}`).val(),
      'sabatical':$(`.sabatical_${element.id_trabajador}`).text(),
      'pago_parcial_hn':quitar_formato_miles($(`.pago_parcial_HN_${element.id_trabajador}`).text()),
      'pago_parcial_he':quitar_formato_miles($(`.pago_parcial_HE_${element.id_trabajador}`).text()),
      'adicional_descuento':$(`.adicional_descuento_${element.id_trabajador}`).val(),
      'pago_quincenal':quitar_formato_miles($(`.pago_quincenal_${element.id_trabajador}`).text())
    });
  }); 

  // console.log(array_trabajador);
  console.log(array_resumen_qs);
  console.log(array_datos_asistencia);

  show_hide_span_input(2);

  if (array_datos_asistencia.length === 0) {
    toastr_error('Sin datos!', ' No hay datos para guardar', 700);    
  } else {

    // abrimos el modal cargando
    $("#modal-cargando").modal("show");

    $.ajax({
      url: "../ajax/asistencia_obrero.php?op=guardaryeditar",
      type: "POST",
      data:  {
        'asistencia': JSON.stringify(array_datos_asistencia), 
        'resumen_qs':JSON.stringify(array_resumen_qs),
        'fecha_inicial':format_a_m_d(f1_r), 
        'fecha_final':format_a_m_d(f2_r)
      },
      // contentType: false, processData: false,
      success: function (e) {              
        try {
          e = JSON.parse(e);  console.log(e);
          if (e.status == true) {

            datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r); tbla_principal(localStorage.getItem('nube_idproyecto')); 
            
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
            datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r);
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
            datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r);
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
      trabajdor = trabajdor.concat(`<li class="text-left font-size-13px">${element.nombres} ─ <b>${formato_miles(element.pago_quincenal)}</b></li>`);
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
            datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r); 
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
            datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r); 
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
function calcular_sabatical(fecha, sueldo_x_hora, id_trabajador_x_proyecto, nombre_trabajador, idresumen_q_s_asistencia, numero_sabado) {
  
  if (estado_editar_asistencia) {
    // Asignamos un val:8 al sabatical
    if ($(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).is(':checked')) {

      $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', false);
      toastr.error(`<h5>Guarda estos datos</h5> guarda estos datos para "asignar o quitar" un sabatical.`);

    } else { // Asignamos un val:0 al sabatical

      $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', true);
      toastr.error(`<h5>Guarda estos datos</h5> guarda estos datos para "asignar o quitar" un sabatical.`);
    }
    var suma_sabatical = 0;
    // verificamos los checks de: "sabado 1"
    // if ($(`#checkbox_sabatical_${id_trabajador_x_proyecto}_1`).is(':checked')){ suma_sabatical +=1; } else { suma_sabatical +=0; }
    // verificamos los checks de: "sabado 2"
    // if ($(`#checkbox_sabatical_${id_trabajador_x_proyecto}_2`).is(':checked')) { suma_sabatical +=1; } else { suma_sabatical +=0; }

    // enviamos la suma de sabatical
    // $(`.sabatical_${id_trabajador_x_proyecto}`).html(suma_sabatical);
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
            datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r);
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
            datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r);
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

// GUARDAR - SABATICAL 1 MULTIPLE
function calcular_todos_sabatical_1() {

  console.log(array_sabatical_1);
  
  if (estado_editar_asistencia) {
    // Asignamos un val:8 al sabatical
    if ($(`#checkbox_sabatical_todos_1`).is(':checked')) {
      
      $(`#checkbox_sabatical_todos_1`).prop('checked', false);
      toastr.error(`<h5>Guarda estos datos</h5> Guarda estos datos para "asignar o quitar" un sabatical.`);

    } else { // Asignamos un val:0 al sabatical
      
      $(`#checkbox_sabatical_todos_1`).prop('checked', true);
      toastr.error(`<h5>Guarda estos datos</h5> Guarda estos datos para "asignar o quitar" un sabatical.`);
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
            datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r); 
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
            datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r); 
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

// GUARDAR - SABATICAL 2 MULTIPLE
function calcular_todos_sabatical_2() {
  console.log(array_sabatical_2);
  if (estado_editar_asistencia) {
    // Asignamos un val:8 al sabatical
    if ($(`#checkbox_sabatical_todos_2`).is(':checked')) {
      
      $(`#checkbox_sabatical_todos_2`).prop('checked', false);
      toastr.error(`<h5>Guarda estos datos</h5> Guarda estos datos para "asignar o quitar" un sabatical.`);

    } else { // Asignamos un val:0 al sabatical
      
      $(`#checkbox_sabatical_todos_2`).prop('checked', true);
      toastr.error(`<h5>Guarda estos datos</h5> Guarda estos datos para "asignar o quitar" un sabatical.`);
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
            datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r); 
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
            datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r); 
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

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   H O R A S   M U L T I P L E S  :::::::::::::::::::::::::::::::::::::::..

function modal_horas_multiples() {
  console.log(array_agregar_horas);
  show_hide_span_input(2)
  $('#modal-agregar-horas-multiples').modal('show');
}

function agregar_horas_multiples(e) {  

  $(".btn-guardar-asistencia").html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $(".btn-guardar-asistencia").attr("disabled", true);

  var horas = $('#horas_multiples').val(); $('.hr_multiple').val(horas);
  var total_progres = array_agregar_horas.length;

  delay(function(){ $("#barra_progress_h_multiple").css({"width": 10+'%'}); $("#barra_progress_h_multiple").text(10+" %"); }, 100 );
  delay(function(){ $("#barra_progress_h_multiple").css({"width": 20+'%'}); $("#barra_progress_h_multiple").text(20+" %"); }, 200 );

  
  array_agregar_horas.forEach((key, indice) => {

    var percentComplete = ((indice+1) / total_progres)*100;  console.log(percentComplete.toFixed(2) + '%');

    $("#barra_progress_h_multiple").css({"width": percentComplete+'%'});

    $("#barra_progress_h_multiple").text(percentComplete.toFixed(2)+" %");    
    
    calcular_todos_he(horas, key.fecha_asistida, key.id_trabajador,  key.cant_dias, key.sueldo_hora, key.cant_trabajador , key.sabatical_manual_1, key.sabatical_manual_2);

  });

  toastr.success(`<h5>${horas} Horas.</h5> Se agregaron a todos los trabajadores.`);
  $(".progress-bar").addClass("bg-success"); $("#barra_progress_h_multiple").text("100% Completado!");

  $(".btn-guardar-asistencia").removeAttr("disabled");
  $(".btn-guardar-asistencia").html(`<i class="far fa-save"></i> <span class="d-none d-sm-inline-block"> Guardar </span>`);

  // $('#modal-agregar-horas-multiples').modal('hide');
  delay(function(){ l_m() }, 2000 );
}

function calcular_todos_he(horas, fecha, id_trabajador, cant_dias_asistencia, sueldo_hora, cant_trabajador , sabatical_manual_1, sabatical_manual_2) {
  /* para que corra una bala */ 
  if (sabatical_manual_1 == '-') { $(`.desglose_q_s_${id_trabajador}_7`).val(''); } if (sabatical_manual_2 == '-') { $(`.desglose_q_s_${id_trabajador}_14`).val(''); } var hora_extr = 0; var hora_norm = 0; var capturar_val_input = horas; if ( parseFloat(capturar_val_input) > 8) { hora_extr = parseFloat(capturar_val_input) - 8; hora_norm = 8; $(`.input_HE_${id_trabajador}_${fecha}`).val(hora_extr.toFixed(1)); $(`.span_HE_${id_trabajador}_${fecha}`).html(hora_extr.toFixed(1));  $(`#input_HN_${id_trabajador}_${fecha}`).val(hora_norm.toFixed(1)); }else{ $(`.span_HE_${id_trabajador}_${fecha}`).html('0.0'); $(`.input_HE_${id_trabajador}_${fecha}`).val(0); } var suma_hn = 0; var suma_he = 0; var dias_asistidos = 0; var pago_parcial_hn = 0; var pago_parcial_he = 0; var adicional_descuento = 0; for (let index = 1; index <= parseInt(cant_dias_asistencia); index++) { if (parseFloat($(`.input_HN_${id_trabajador}_${index}`).val()) > 0 ) { suma_hn = suma_hn + parseFloat($(`.input_HN_${id_trabajador}_${index}`).val()); dias_asistidos++; } if (parseFloat($(`.input_HE_${id_trabajador}_${index}`).val()) > 0 ) {suma_he = suma_he + parseFloat($(`.input_HE_${id_trabajador}_${index}`).val());}} var horas_1_sabado = 0; var horas_2_sabado = 0; var sabatical = 0;for (let x = 1; x <= parseInt(cant_dias_asistencia); x++) {if (sabatical_manual_1 == '-') {if ( x < 7 ) {if ($(`.desglose_q_s_${id_trabajador}_${x}`).val() > 0) {horas_1_sabado += parseFloat($(`.desglose_q_s_${id_trabajador}_${x}`).val());}}  } if (sabatical_manual_2 == '-') { if ( x > 7 && x < 14 ) { if ($(`.desglose_q_s_${id_trabajador}_${x}`).val()  > 0) { horas_2_sabado += parseFloat($(`.desglose_q_s_${id_trabajador}_${x}`).val()); }  }}}if (sabatical_manual_1 == '-') { if (horas_1_sabado >= 44 ) { $(`.desglose_q_s_${id_trabajador}_7`).val('8');  $(`#checkbox_sabatical_${id_trabajador}_1`).prop('checked', true); suma_hn += 8; dias_asistidos +=1; sabatical += 1;   $(`.sabatical_auto_${id_trabajador}_7`).removeClass('bg-color-acc3c7').addClass('bg-color-28a745');} else {  $(`.desglose_q_s_${id_trabajador}_7`).val('0');   $(`#checkbox_sabatical_${id_trabajador}_1`).prop('checked', false); $(`.sabatical_auto_${id_trabajador}_7`).removeClass('bg-color-28a745').addClass('bg-color-acc3c7'); }  $(`.sabatical_${id_trabajador}`).html(sabatical);  } if (sabatical_manual_2 == '-') { if (horas_2_sabado >= 44) { $(`.desglose_q_s_${id_trabajador}_14`).val('8'); $(`#checkbox_sabatical_${id_trabajador}_2`).prop('checked', true); suma_hn += 8; dias_asistidos +=1; sabatical += 1; $(`.sabatical_auto_${id_trabajador}_14`).removeClass('bg-color-acc3c7').addClass('bg-color-28a745'); } else { $(`.desglose_q_s_${id_trabajador}_14`).val('0'); $(`#checkbox_sabatical_${id_trabajador}_2`).prop('checked', false);  $(`.sabatical_auto_${id_trabajador}_14`).removeClass('bg-color-28a745').addClass('bg-color-acc3c7'); } $(`.sabatical_${id_trabajador}`).html(sabatical);  }  if (sabatical_manual_1 == '1') { sabatical += 1; $(`.sabatical_${id_trabajador}`).html(sabatical);} if (sabatical_manual_2 == '1') { sabatical += 1; $(`.sabatical_${id_trabajador}`).html(sabatical);} if (parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) >= 0 || parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) <= 0 ) { adicional_descuento =   parseFloat($(`.adicional_descuento_${id_trabajador}`).val()); } else { adicional_descuento = 0; toastr.error(`El dato adicional/descuento:: <h3 class=""> ${$(`.adicional_descuento_${id_trabajador}`).val()} </h3> no es NUMÉRICO, ingrese un número cero o un positivo o un negativo.`);     }  $(`.total_HN_${id_trabajador}`).html(suma_hn.toFixed(1));  $(`.total_HE_${id_trabajador}`).html(suma_he.toFixed(1));  $(`.dias_asistidos_${id_trabajador}`).val( `${ ((suma_hn + suma_he) / 8).toFixed(2) }`);  $(`.dias_asistidos_${id_trabajador}_percent`).html( `${ ((suma_hn + suma_he) / 8).toFixed(2) }`);  $(`.pago_parcial_HN_${id_trabajador}`).html(formato_miles((suma_hn * parseFloat(sueldo_hora)).toFixed(2)));  $(`.pago_parcial_HE_${id_trabajador}`).html(formato_miles((suma_he * parseFloat(sueldo_hora)).toFixed(2))); var pago_quincenal = ( (parseFloat((suma_hn * parseFloat(sueldo_hora)).toFixed(2)) + parseFloat((suma_he * parseFloat(sueldo_hora)).toFixed(2))) + adicional_descuento ).toFixed(2); $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(pago_quincenal)); var suma_total_quincena = 0;  for (let k = 1; k <= parseInt(cant_trabajador); k++) { suma_total_quincena = suma_total_quincena + parseFloat(quitar_formato_miles($(`.val_pago_quincenal_${k}`).text())); }  $(`.pago_total_quincenal`).html(formato_miles(suma_total_quincena.toFixed(2)));

  return true;
}

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   A D I C I O N A L   D E S C U E N T O  :::::::::::::::::::::::::::::::::::::::..
function adicional_descuento(cant_trabajador, id_trabajador) {

  var suma_resta = 0; var pago_parcial_HN = 0; pago_parcial_HE = 0;

  //console.log($(`.pago_quincenal_${id_trabajador}`).text());   console.log($(`.adicional_descuento_${id_trabajador}`).val());

  // capturamos los pgos parciales
  pago_parcial_HN = parseFloat( quitar_formato_miles( $(`.pago_parcial_HN_${id_trabajador}`).text())); pago_parcial_HE = parseFloat( quitar_formato_miles($(`.pago_parcial_HE_${id_trabajador}`).text()));

  if (parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) >= 0 || parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) <= 0 ) {

    suma_resta = (pago_parcial_HN + pago_parcial_HE) + parseFloat($(`.adicional_descuento_${id_trabajador}`).val());

    $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(suma_resta.toFixed(2)));

    var suma_total_quincena = 0;

    // acumulamos todos los pagos quicenales
    for (let k = 1; k <= parseInt(cant_trabajador); k++) {    
      console.log($(`.val_pago_quincenal_${k}`).text()); 
      suma_total_quincena = suma_total_quincena + parseFloat(quitar_formato_miles($(`.val_pago_quincenal_${k}`).text())); 
    }

    $(`.pago_total_quincenal`).html(formato_miles(suma_total_quincena.toFixed(2)));

  } else {

    toastr.error(`El dato de adicional/descuento: <h3 class=""> ${$(`.adicional_descuento_${id_trabajador}`).val()} </h3> no es NUMÉRICO, ingrese un numero cero o un positivo o un negativo.`);    
  }  
}

function guardaryeditar_adicional_descuento(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-adicional-descuento")[0]);

  $.ajax({
    url: "../ajax/asistencia_obrero.php?op=guardaryeditar_adicional_descuento",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);
        if (e.status == true) {

          datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r);
  
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

function modal_adicional_descuento( id_adicional, id_trabjador, fecha_q_s) {

  $("#cargando-5-fomulario").hide(); 
  $("#cargando-6-fomulario").show();

  $("#idresumen_q_s_asistencia").val(id_adicional);
  $("#idtrabajador_por_proyecto").val(id_trabjador);
  $("#fecha_q_s").val(fecha_q_s);
  $("#detalle_adicional").val("");

  $("#modal-adicional-descuento").modal("show");

  $.post("../ajax/asistencia_obrero.php?op=descripcion_adicional_descuento",{"id_adicional":id_adicional}, function(e){
    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      if (e.data.length === 0) { } else {
        $("#detalle_adicional").val(e.data.descripcion_descuento);
      }
    }else{
      ver_errores(e);
    }     
    
    $("#cargando-5-fomulario").show(); 
    $("#cargando-6-fomulario").hide();

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
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "desc" ]]//Ordenar (columna,orden)
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
      // columna: Pago por horas normal
      if (data[5] != '') { $("td", row).eq(5).addClass('text-nowrap text-right'); }
      // columna: Adicional
      if (data[7] != '') { $("td", row).eq(7).addClass('text-right'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "desc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [10,11,12],  visible: false,  searchable: false,  },
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
        $(".suma_qs_dias_asistidos").html(`<b>${formato_miles(e.data.total_dias_asistidos)}</b> `);
        $(".suma_qs_adicional").html(`S/ <b>${formato_miles(e.data.adicional_descuento)}</b> `);
        $(".suma_qs_sabatical").html(`<b>${formato_miles(e.data.sabatical)}</b> `);
        $(".suma_qs_pago_quincenal").html(`S/ <b>${formato_miles(e.data.pago_quincenal)}</b> `);        
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

  $('#fecha_inicio_actividad').val("");
  $('#fecha_fin_actividad').val("");
  $('#plazo_actividad').val("");

  $.post("../ajax/asistencia_obrero.php?op=fechas_actividad", { 'id_proyecto': localStorage.getItem('nube_idproyecto') }, function (e, status) {
    
    e = JSON.parse(e);  //console.log(e);

    $('#fecha_inicio_actividad').datepicker("setDate" , format_d_m_a(e.data.fecha_inicio_actividad));
    $('#fecha_fin_actividad').datepicker("setDate" ,format_d_m_a(e.data.fecha_fin_actividad));
    $('#plazo_actividad').val(e.data.plazo_actividad);
    $('.plazo_actividad').html(e.data.plazo_actividad);

    $("#cargando-7-fomulario").show();
    $("#cargando-8-fomulario").hide();
  }).fail( function(e) { ver_errores(e); } );
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

  console.log(array_btn_fechas);

  var id_proyecto_f           = $('#id_proyecto_f').val();
  var fecha_inicio_actividad  = $('#fecha_inicio_actividad').val();
  var fecha_fin_actividad     = $('#fecha_fin_actividad').val();
  var plazo_actividad         = $('#plazo_actividad').val();

  $.ajax({
    url: "../ajax/asistencia_obrero.php?op=guardar_y_editar_fechas_actividad",
    type: "POST",
    //data: formData,
    data: {
      'id_proyecto_f':          id_proyecto_f, 
      'fecha_inicio_actividad': fecha_inicio_actividad, 
      'fecha_fin_actividad':    fecha_fin_actividad, 
      'plazo_actividad':        plazo_actividad, 
      'fechas':                 JSON.stringify(array_btn_fechas) 
    },
    //contentType: 'application/json; charset=utf-8',
    //processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);
             
        if (e.status == true) {        

          Swal.fire("Correcto!", "Fechas registrada correctamente", "success");

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
      fecha_fin_actividad: { required: true, minlength: 4},
      plazo_actividad: { required: true,},
    },

    messages: {
      fecha_inicio_actividad: {
        required: "Este campo es requerido",
        min:"Escriba almenos 4 caracteres."
      },
      fecha_fin_actividad: {
        required: "Este campo es requerido",
        min:"Escriba almenos 4 caracteres."
      },
      plazo_actividad: {
        required: "Este campo es requerido",
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
      guardar_y_editar_fechas_actividades(form);
    },
  });

  $("#form-horas-multiples").validate({
    
    rules: {      
      horas_multiples: { required: true, number: true, min:0, max:12},
    },

    messages: {
      horas_multiples: {
        required: "Este campo es requerido",
        min:"Escriba almenos 1 digito positivo.",
        max:"No explote a sus obreros."
      },
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
});

function l_m(){  
   
  // $("#barra_progress").css({"width":'0%'});
  // $("#barra_progress").text("100% completado");
  $(".progress-bar").removeClass("progress-bar-striped");

  $("#barra_progress_h_multiple").css({"width":'0%'});
  $("#barra_progress_h_multiple").text("0%");
  $(".progress-bar").removeClass("bg-success bg-danger");
  
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