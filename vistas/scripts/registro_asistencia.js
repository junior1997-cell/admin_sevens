var tabla_principal, tabla_horas, tabla_qs;

var array_asistencia = []; var array_trabajador = [];

var f1_r = 0, f2_r = 0, i_r = 0, cant_dias_asistencia_r = 0; var estado_editar_asistencia = false;

var idtrabajador_por_proyecto_r = 0;

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lAsistencia").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  tbla_principal(localStorage.getItem('nube_idproyecto')); 

  // submnit a adicional descuento
  $("#guardar_adicional_descuento").on("click", function (e) { $("#submit-form-adicional-descuento").submit(); });

  // submnit a JUSTIFICAR
  $("#guardar_registro_justificacion").on("click", function (e) { $("#submit-form-justificacion").submit(); });

  // submnit a FECHAS DE ACTIVIDADES
  $("#guardar_registro_fechas_actividades").on("click", function (e) { $("#submit-form-fechas-actividades").submit(); });

  // submnit a HORAS MULTIPLES
  $(".horas-multiples").on("click", function (e) { $("#form-horas-multiples").submit(); });
  
  // Formato para telefono
  $("[data-mask]").inputmask();

  //Timepicker
  $('#timepicker').datetimepicker({
    // format: 'LT',
    format:'HH:mm ',
    lang:'ru'
  })

  $('#fecha_inicio_actividad').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
  // Inicializar - Date picker  
  $('#fecha_inicio_actividad').datetimepicker({
    locale: 'es',
    format: 'DD-MM-YYYY',
    daysOfWeekDisabled: [6]
  });

  $('#fecha_fin_actividad').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
  // Inicializar - Date picker  
  $('#fecha_fin_actividad').datetimepicker({
    locale: 'es',
    format: 'DD-MM-YYYY',
    daysOfWeekDisabled: [6],
  });

}

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addDocs(e,$("#doc1").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

function mostrar_form_table(estados) {

  if (estados == 1 ) {
    $("#card-registrar").show();
    $("#card-regresar").hide();
    $("#card-editar").hide();
    $("#card-guardar").hide();
    $("#tabla-asistencia-trab").show();
    $("#ver_asistencia").hide();
    $("#detalle_asistencia").hide();
    $("#detalle_qs").hide();
    estado_editar_asistencia = false;
  } else {
    if (estados == 2) {
      $("#card-registrar").hide();
      $("#card-regresar").show();
      $("#card-editar").show();
      $("#card-guardar").hide();
      $("#tabla-asistencia-trab").hide();
      $("#ver_asistencia").show();
      $("#detalle_asistencia").hide();
      $("#detalle_qs").hide();
      estado_editar_asistencia = false;
      
    } else {
      if (estados == 3) {
        $("#card-registrar").hide();
        $("#card-regresar").show();
        $("#card-editar").hide();
        $("#card-guardar").hide();
        $("#tabla-asistencia-trab").hide();
        $("#ver_asistencia").hide();
        $("#detalle_asistencia").show();
        $("#detalle_qs").hide();
        estado_editar_asistencia = false;
      } else {
        if (estados == 4) {
          $("#card-registrar").hide();
          $("#card-regresar").show();
          $("#card-editar").hide();
          $("#card-guardar").hide();
          $("#tabla-asistencia-trab").hide();
          $("#ver_asistencia").hide();
          $("#detalle_asistencia").hide();
          $("#detalle_qs").show();
          estado_editar_asistencia = false;
        }
      }
    }
  }
}

function editar_fechas_asistencia(flag){

  if (flag == 1) {
    // ocultamos los span
    $(".span_asist").show();
    // mostramos los inputs
    $(".input_asist").hide();

    // ocultamos el boton editar
    $("#card-editar").show();
    // mostramos el boton guardar
    $("#card-guardar").hide();

    $(".checkbox_visible").removeAttr("disabled");

    estado_editar_asistencia = false;
  } else {
    if (flag == 2) {
      // ocultamos los span
      $(".span_asist").hide();
      // mostramos los inputs
      $(".input_asist").show();

      // ocultamos el boton editar
      $("#card-editar").hide();
      // mostramos el boton guardar
      $("#card-guardar").show();

      $(".checkbox_visible").attr("disabled", true);

      estado_editar_asistencia = true;
    }
  }  
}

//TBLA - PRINCIPAL
function tbla_principal(nube_idproyecto) {

  $('#lista_quincenas').html('<div class="my-3" ><i class="fas fa-spinner fa-pulse fa-2x"></i>&nbsp;&nbsp;&nbsp;Cargando...</div>');

  tabla_principal = $('#tabla-asistencia').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'csvHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
    "ajax":{
      url: '../ajax/registro_asistencia.php?op=tbla_principal&nube_idproyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {          

      // columna: #
      if (data[0] != '') {
        $("td", row).eq(0).addClass('text-center');         
      }

      // columna: opciones
      if (data[1] != '') {
        $("td", row).eq(1).addClass('text-nowrap');         
      }

      // columna: total Horas
      if (data[3] != '') {
        $("td", row).eq(3).addClass('text-center');         
      }

      // columna: total Días
      if (data[4] != '') {
        $("td", row).eq(4).addClass('text-center');         
      }

      // columna: Sueldo diario
      if (data[5] != '') {
        $("td", row).eq(5).addClass('text-nowrap text-right');         
      }

      // columna: Sueldo diario
      if (data[6] != '') {
        $("td", row).eq(6).addClass('text-nowrap text-right');         
      }

      // columna: Sueldo mensual
      if (data[7] != '') {
        $("td", row).eq(7).addClass('text-nowrap text-right');         
      }

      // columna: sabatical
      if (data[8] != '') {
        $("td", row).eq(8).addClass('text-center');         
      }

      // columna: Pago acumulado
      if (data[9] != '') {
        $("td", row).eq(9).addClass('text-nowrap text-right');         
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
    // "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();

  //Suma ACUMULADO
  $.post("../ajax/registro_asistencia.php?op=suma_total_acumulado", { 'nube_idproyecto': nube_idproyecto }, function (data, status) {

    data =JSON.parse(data); //console.log(data);

    if (data) {
      var normal = parseFloat(data.pagos_normal_dias); var extras = parseFloat(data.pagos_horas_extras);
      var total = normal + extras;
      $("#total_acumulado_trabjadores").html(`S/. <b>${formato_miles(total)}</b> `);
    } else {
      $("#total_acumulado_trabjadores").html("S/. 0.00");
    }
  });

  //Listar quincenas(botones)
  $.post("../ajax/registro_asistencia.php?op=listarquincenas_botones", { nube_idproyecto: nube_idproyecto }, function (data, status) {

    data =JSON.parse(data); console.log(data);

    // validamos la existencia de DATOS
    if (data) {
      if (data.fecha_inicio == '0000-00-00' || data.fecha_inicio == null || data.fecha_fin == '0000-00-00' || data.fecha_fin == null) {
        $('#lista_quincenas').html(`<div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
          <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
          No has definido las de fechas del proyecto. <br>Clic en el <span class="bg-green p-1 rounded-lg"> <i class="far fa-calendar-alt"></i> boton verde</span> para actualizar las fechas de actividades.
        </div>`);
      } else {        
      
        var dia_regular = 0; var weekday_regular = extraer_dia_semana(data.fecha_inicio); var estado_regular = false;

        if (weekday_regular == "do") { dia_regular = -1; } else { if (weekday_regular == "lu") { dia_regular = -2; } else { if (weekday_regular == "ma") { dia_regular = -3; } else { if (weekday_regular == "mi") { dia_regular = -4; } else { if (weekday_regular == "ju") { dia_regular = -5; } else { if (weekday_regular == "vi") { dia_regular = -6; } else { if (weekday_regular == "sa") { dia_regular = -7; } } } } } } }
        // console.log(data.fecha_inicio, dia_regular, weekday_regular);
        if (data.fecha_pago_obrero == "quincenal") {

          $('#lista_quincenas').html('');

          var fecha = format_d_m_a(data.fecha_inicio); //console.log(fecha);

          var fecha_i = sumaFecha(0,fecha);   var cal_quincena  = data.plazo/14;

          var i=0; var cont=0; 

          while (i <= cal_quincena) {

            cont=cont+1; var fecha_inicio = fecha_i;

            if (estado_regular) {

              fecha=sumaFecha(13,fecha_inicio);     //console.log(fecha_inicio+'-'+fecha);

            } else {

              fecha=sumaFecha(14+dia_regular,fecha_inicio); estado_regular = true;     //console.log(fecha_inicio+'-'+fecha);
            }           

            $('#lista_quincenas').append(` <button type="button" id="boton-${i}" class="mb-2 btn bg-gradient-info text-center" onclick="datos_quincena('${fecha_inicio}', '${fecha}', '${i}', 14);"><i class="far fa-calendar-alt"></i> Quincena ${cont}<br>${fecha_inicio} // ${fecha}</button>`)
            
            fecha_i =sumaFecha(1,fecha);

            i++;
          }
        } else {
          if (data.fecha_pago_obrero == "semanal") {

            $('#lista_quincenas').html('');

            var fecha = format_d_m_a(data.fecha_inicio);  var fecha_f = ""; var fecha_i = ""; //data.fecha_inicio

            var cal_mes  = false; var i=0;  var cont=0;

            while (cal_mes == false) {

              cont = cont+1; fecha_i = fecha;

              if (estado_regular) {

                fecha_f = sumaFecha(6, fecha_i);

              } else {

                fecha_f = sumaFecha(7+dia_regular, fecha_i); estado_regular = true;
              }            

              let val_fecha_f = new Date( format_a_m_d(fecha_f) ); let val_fecha_proyecto = new Date(data.fecha_fin);
              
              // console.log(fecha_f + ' - '+data.fecha_fin);

              $('#lista_quincenas').append(` <button id="boton-${i}" type="button" class="mb-2 btn bg-gradient-info text-center" onclick="datos_quincena('${fecha_i}', '${fecha_f}', '${i}', 7);"><i class="far fa-calendar-alt"></i> Semana ${cont}<br>${fecha_i} // ${fecha_f}</button>`)
              
              if (val_fecha_f.getTime() >= val_fecha_proyecto.getTime()) { cal_mes = true; }else{ cal_mes = false;}

              fecha = sumaFecha(1,fecha_f);

              i++;
            } 
          } else { 
            $('#lista_quincenas').html(`<div class="info-box shadow-lg w-px-600"> 
              <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span> 
              <div class="info-box-content"> 
                <span class="info-box-text">Alerta</span> 
                <span class="info-box-number">No has definido los bloques de fechas del proyecto. <br>Ingresa al ESCRITORIO y EDITA tu proyecto selecionado.</span> 
              </div> 
            </div>`);
          }
        }
      }
    } else {
      $('#lista_quincenas').html(`<div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
        <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
        No has definido las de fechas del proyecto. <br>Clic en el <span class="bg-green p-1 rounded-lg"> <i class="far fa-calendar-alt"></i> boton verde</span> para actualizar las fechas de actividades.
      </div>`);
    }    
    //console.log(fecha);
  });
}

// listamos la data de una quincena selecionada
function datos_quincena(f1, f2, i, cant_dias_asistencia) {

  mostrar_form_table(2);

  f1_r = f1; f2_r = f2; i_r = i; cant_dias_asistencia_r = cant_dias_asistencia;

  // ocultamos las tablas  
  $("#ver_asistencia").hide();
  $('#cargando-registro-asistencia').show();

  // cambiamos el valor del colspan
  $("#dias_asistidos_s_q").attr("colspan", cant_dias_asistencia);

  // cambiamos el TABLE-HEAD tipo de pago
  if (cant_dias_asistencia == 7) { $(".head_pago_q_s").html("Pago semanal"); }else{ $(".head_pago_q_s").html("Pago quincenal"); }

  $("#card-editar").show(); $("#card-guardar").hide();  

  // vaciamos el array
  array_asistencia = []; array_trabajador = []

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

    table_dia_semana = table_dia_semana.concat(`<th class="p-x-10px bg-color-acc3c7"> ${fecha_inicial_quincena_regular.substr(0,2)} <br> ${weekday} </th>`);

    // table_numero_semana = table_numero_semana.concat(`<th class="p-x-10px bg-color-acc3c7"> ${count_dias_de_asistencias} </th>`);

    // aumentamos mas un dia hasta llegar al dia "dia_regular"
    fecha_inicial_quincena_regular = sumaFecha(1,fecha_inicial_quincena_regular); //console.log(count_dias_de_asistencias);

    //count_dias_de_asistencias++;
  }

  // asignamos: dia semana, numero semana. Con respecto al trabajo
  for (i = 1; i <=cant_dias_asistencia + dia_regular; i++) { 

    var weekday = extraer_dia_semana(format_a_m_d(fecha_inicial_quincena));  

    if (weekday != 'sa') {

      table_dia_semana = table_dia_semana.concat(`<th class="p-x-10px"> ${fecha_inicial_quincena.substr(0,2)} <br> ${weekday} </th>`);

      // table_numero_semana = table_numero_semana.concat(`<th class="p-x-10px"> ${count_dias_de_asistencias} </th>`);

    } else {

      table_dia_semana = table_dia_semana.concat(`<th class="p-x-10px bg-color-acc3c7">${fecha_inicial_quincena.substr(0,2)} <br> ${weekday} </th>`);
      
      // table_numero_semana = table_numero_semana.concat(`<td class="p-x-10px bg-color-acc3c7"> ${count_dias_de_asistencias} </td>`);
    }

    // aumentamos mas un dia hasta llegar al dia 15
    fecha_inicial_quincena = sumaFecha(1,fecha_inicial_quincena); //console.log(count_dias_de_asistencias);
    //count_dias_de_asistencias++
  } //end for

  $('.data-dia-semana').html(table_dia_semana);

  // $('.data-numero-semana').html(table_numero_semana);
  
  $.post("../ajax/registro_asistencia.php?op=ver_datos_quincena", {f1:format_a_m_d(f1),f2:format_a_m_d(f2),nube_idproyect:nube_idproyect}, function (data, status) {
        
    data =JSON.parse(data); console.log(data);   

    $(".data_table_body").html('');   
     
    $.each(data, function (index, value) {
      count_bloque_q_s = 1;
      var count_dias_asistidos = 0; var horas_total = 0; var horas_nomr_total = 0; var horas_extr_total = 0; var sabatical = 0;
      
      var tabla_bloc_HN_asistencia_3=""; var tabla_bloc_HE_asistencia_2 =""; var estado_hallando_sabado = true;

      // existe alguna asistencia -------
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

          // imprimimos la fecha de asistencia: "encontrada" 
          if (estado_fecha) {

            var weekday = extraer_dia_semana(fecha_asist); //console.log(weekday);

            if (weekday != 'sa') {

              tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center"> <span class="span_asist span_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" >${hora_n}</span> <input class="w-px-30 input_asist hr_multiple input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" onkeyup="delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );" onchange="delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );" type="text" value="${hora_n}" autocomplete="off" ></td>`);
              
              tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center"> <span class=" span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" >${hora_e}</span> <input class="w-px-30 input_HE_${value.idtrabajador_por_proyecto}_${i} input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} hidden" id="input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="${hora_e}" ></td>`);
             
              array_asistencia.push( { 
                'id_trabajador':value.idtrabajador_por_proyecto, 
                'fecha_asistida':format_d_m_a(fecha_asist), 
                'class_input_hn':`input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}`, 
                'class_input_he':`input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}`,
                'sueldo_hora':value.sueldo_hora
              } );

            } else {
              // SABATICALES
              if (estado_hallando_sabado) { 
                if (value.sabatical_manual_1 == "0") {

                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="0" > </td>`);
                  
                  count_dias_asistidos -= 1;
                } else {
                  if (value.sabatical_manual_1 == "1") {

                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="8" > </td>`);
                      
                    sabatical = 1;
                  } else {
  
                    if (hora_n == 8 ) {
  
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="8" > </td>`);
                                              
                      sabatical = 1;
                    } else {
  
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox"  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="0" > </td>`);
                      
                      count_dias_asistidos -= 1; //horas_nomr_total -= 8;          
                    }
                  }
                }

                estado_hallando_sabado = false; // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
              } else {
                if (value.sabatical_manual_2 == "0") {

                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="0" > </td>`);
                  
                } else {
                  if (value.sabatical_manual_2 == "1") {

                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="8" > </td>`);
                    
                    // count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
  
                    sabatical += 1;
                  } else {
  
                    if (hora_n == 8) {                      
  
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center  center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="8" > </td>`);
                                                
                      sabatical += 1;
                      
                    } else { 
                      
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical  sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${format_d_m_a(fecha_asist)}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="0" > </td>`);                    
                      
                      count_dias_asistidos -= 1; //horas_nomr_total -= 8;
                    }                  
                  }
                }                                
              }

              array_asistencia.push( { 
                'id_trabajador':value.idtrabajador_por_proyecto, 
                'fecha_asistida':format_d_m_a(fecha_asist), 
                'class_input_hn':`input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}`, 
                'class_input_he':`input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}`,
                'sueldo_hora':value.sueldo_hora
              } );
            }             
            
          } else { // imprimimos la fecha de asistencia: "No encontrada"

            var weekday = extraer_dia_semana(format_a_m_d(fecha)); //console.log(weekday);

            if (weekday != 'sa') {

              tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center"> <span class="span_asist  span_HN_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> <input class="w-px-30 input_asist hr_multiple input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" onkeyup="delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );" onchange="delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}')}, 300 );" type="text" value="" autocomplete="off" ></td>`);
              
              tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center"> <span class=" span_HE_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> <input class="w-px-30 input_HE_${value.idtrabajador_por_proyecto}_${i} input_HE_${value.idtrabajador_por_proyecto}_${fecha} hidden" type="text" value="" ></td>`);
                
              array_asistencia.push( { 
                'id_trabajador':value.idtrabajador_por_proyecto, 
                'fecha_asistida':fecha, 
                'class_input_hn':`input_HN_${value.idtrabajador_por_proyecto}_${fecha}`,
                'class_input_he':`input_HE_${value.idtrabajador_por_proyecto}_${fecha}`,
                'sueldo_hora':value.sueldo_hora
              } );

            } else {
              // SABATICALES
              if (estado_hallando_sabado) { 
                if (value.sabatical_manual_1 == "0") {
  
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                                   
                } else {
                  if (value.sabatical_manual_1 == "1") {
  
                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                    
                    count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
    
                    sabatical = 1;
                  } else {
                    // El sistema calcula el "sabatical" conforme a las horas del trabajador
                    if (horas_nomr_total >= 44 ) {
    
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                      
                      count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
    
                      sabatical = 1;
                    } else {
    
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                     
                    }
                  }
                }            
  
                estado_hallando_sabado = false; // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
              } else {
                if (value.sabatical_manual_2 == "0") {
  
                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                  
                } else {
                  if (value.sabatical_manual_2 == "1") {
  
                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                    
                    count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
    
                    sabatical = sabatical + 1;
                  } else {
    
                    if (sabatical == "1") {
                      // El sistema calcula el "sabatical" conforme a las horas del trabajador
                      if ((horas_nomr_total - 52) >= 44 ) {
    
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                        
                        count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
    
                        sabatical = sabatical + 1;
                      } else {
    
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                                               
                      }
                    } else {
    
                      if ((horas_nomr_total - 44) >= 44 ) {
    
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
    
                        count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
    
                        sabatical = sabatical + 1;
                      } else {
    
                        tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                                               
                      }
                    }                  
                  }
                }                              
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

          // console.log(count_bloque_q_s);
          count_bloque_q_s++; 
        } //end for
        // console.log('-----------------------------------------------------------');
      // no existe ninguna asistencia -------  
      } else {

        var fecha = f1; //console.log("no ninguna fecha asistida");  

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

            tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center"> <span class="span_asist span_HN_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> <input class="w-px-30 input_asist hr_multiple input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" onkeyup="delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 ); " oninput="delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );" type="text" value="" autocomplete="off"></td>`);
            
            tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center"> <span class=" span_HE_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> <input class="w-px-30 input_HE_${value.idtrabajador_por_proyecto}_${i} input_HE_${value.idtrabajador_por_proyecto}_${fecha} hidden" type="text" value="" ></td>`);
            
            array_asistencia.push( { 
              'id_trabajador':value.idtrabajador_por_proyecto, 
              'fecha_asistida':fecha, 
              'class_input_hn':`input_HN_${value.idtrabajador_por_proyecto}_${fecha}`,   
              'class_input_he':`input_HE_${value.idtrabajador_por_proyecto}_${fecha}`,
              'sueldo_hora':value.sueldo_hora
            } );

          } else {
            // SABATICALES
            if (estado_hallando_sabado) { 
              if (value.sabatical_manual_1 == "0") {

                tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                                 
              } else {
                if (value.sabatical_manual_1 == "1") {

                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                  
                  count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
  
                  sabatical = 1;
                } else {
                  // El sistema calcula el "sabatical" conforme a las horas del trabajador
                  if (horas_nomr_total >= 44 ) {
  
                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                    
                    count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
  
                    sabatical = 1;
                  } else {
  
                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_1" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 1); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                   
                  }
                }
              }            

              estado_hallando_sabado = false; // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            } else {
              if (value.sabatical_manual_2 == "0") {

                tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                
              } else {
                if (value.sabatical_manual_2 == "1") {

                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-eb0202 center-vertical"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                  
                  count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
  
                  sabatical = sabatical + 1;
                } else {
  
                  if (sabatical == "1") {
                    // El sistema calcula el "sabatical" conforme a las horas del trabajador
                    if ((horas_nomr_total - 52) >= 44 ) {
  
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
                      
                      count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
  
                      sabatical = sabatical + 1;
                    } else {
  
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                                             
                    }
                  } else {
  
                    if ((horas_nomr_total - 44) >= 44 ) {
  
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center center-vertical bg-color-28a745 sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="8" > </td>`);
  
                      count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
  
                      sabatical = sabatical + 1;
                    } else {
  
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical sabatical_auto_${value.idtrabajador_por_proyecto}_${count_bloque_q_s}"> <input class="w-xy-20" type="checkbox" id="checkbox_sabatical_${value.idtrabajador_por_proyecto}_2" onclick="calcular_sabatical('${fecha}', '${value.sueldo_hora}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', 2); delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', '${value.sabatical_manual_1}', '${value.sabatical_manual_2}') }, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} desglose_q_s_${value.idtrabajador_por_proyecto}_${count_bloque_q_s} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="" > </td>`);
                                             
                    }
                  }                  
                }
              }                              
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
      var data_trabajador = { 
        'id_trabajador':value.idtrabajador_por_proyecto, 
        'fecha_asistida':value.nombres,
        'sueldo_hora':value.sueldo_hora
      };
      array_trabajador.push( data_trabajador );
      
      var tabla_bloc_HN_trabaj_2 =  `<td rowspan="2" class="center-vertical">${value.nombres}</td> <td rowspan="2" class="center-vertical">${value.cargo}</td>`;       

      var tabla_bloc_HN_total_hora_4 =  `<td class="text-center center-vertical"> <span  class="total_HN_${value.idtrabajador_por_proyecto}">${horas_nomr_total}</span> </td>`;

      var tabla_bloc_HN_total_dia_5 = `<td class="text-center center-vertical" rowspan="2" ><span  class="dias_asistidos_${value.idtrabajador_por_proyecto}">${count_dias_asistidos}</span> </td>`;

      var tabla_bloc_HN_sueldos_6 = `<td class="text-center center-vertical" rowspan="2">${formato_miles(value.sueldo_mensual)}</td>`+
              `<td class="text-center center-vertical" rowspan="2">${value.sueldo_diario}</td>`+
              `<td class="text-center center-vertical" rowspan="2">${value.sueldo_hora}</td>`;
   
      var tabla_bloc_HN_sabatical_7 =  `<td class="text-center center-vertical" rowspan="2"><span  class="sabatical_${value.idtrabajador_por_proyecto}">${sabatical}</span></td>`;

      var tabla_bloc_HN_pago_parcial_8 = `<td class="text-center center-vertical"> <span  class="pago_parcial_HN_${value.idtrabajador_por_proyecto}"> ${formato_miles((parseFloat(value.sueldo_hora) * parseFloat(horas_nomr_total)).toFixed(2))}</span> </td>`;
      
      var fechas_adicional = "";
      
      // validamos si existe una suma_adicional 
      if (value.idresumen_q_s_asistencia == "") { fechas_adicional = format_a_m_d(f1); } else { fechas_adicional = value.fecha_registro; }

      var tabla_bloc_HN_descuent_9 = `<td rowspan="2" class="text-center center-vertical"> <span class="span_asist" >${value.adicional_descuento}</span> <input class="w-px-45 input_asist hidden adicional_descuento_${value.idtrabajador_por_proyecto}" onkeyup="delay(function(){ adicional_descuento('${data.length}', '${value.idtrabajador_por_proyecto}') }, 300 );" type="text" value="${value.adicional_descuento}" autocomplete="off" > <span class="badge badge-info float-right cursor-pointer" data-toggle="tooltip" data-original-title="Por descuento" onclick="modal_adicional_descuento( '${value.idresumen_q_s_asistencia}', '${value.idtrabajador_por_proyecto}', '${fechas_adicional}');"><i class="far fa-eye"></i></span></td>`;

      var tabla_bloc_HN_pago_total_10 = `<td rowspan="2" class="text-center center-vertical"> <span  class="val_pago_quincenal_${index+1} pago_quincenal_${value.idtrabajador_por_proyecto}"> ${formato_miles(((parseFloat((parseFloat(value.sueldo_hora) * parseFloat(horas_nomr_total)).toFixed(2)) + parseFloat((parseFloat(value.sueldo_hora) * parseFloat(horas_extr_total)).toFixed(2))) + parseFloat(value.adicional_descuento) ).toFixed(2))} </span> </td>`;

      var tabla_bloc_envio_contador_11 = "";

      // validamos el el envio al contador
      if (value.estado_envio_contador == "") {
        tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20 checkbox_visible" type="checkbox"  id="checkbox_asignar_pago_contador_${value.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', '${value.pago_quincenal}');"> </td>`;        
      } else {
        if (value.estado_envio_contador == "0") {
          tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20 checkbox_visible" type="checkbox"  id="checkbox_asignar_pago_contador_${value.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', '${value.pago_quincenal}');"> </td>`;                  
        } else {
          tabla_bloc_envio_contador_11 = `<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20 checkbox_visible" type="checkbox" checked id="checkbox_asignar_pago_contador_${value.idtrabajador_por_proyecto}" onclick="asignar_pago_al_contador('${fechas_adicional}', '${value.idtrabajador_por_proyecto}', '${value.nombres}', '${value.idresumen_q_s_asistencia}', '${value.pago_quincenal}');"> </td>`;                  
        }
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

    var tabla_bloc_TOTAL_1 = '';

    if (cant_dias_asistencia == 14) {

      tabla_bloc_TOTAL_1 = `<tr> <td class="text-center" colspan="25"></td> <td class="text-center"> <b>TOTAL</b> </td> <td class="text-center"><span  class="pago_total_quincenal"> ${formato_miles(total_pago.toFixed(2))}</span> </td> </tr>`;
      
    } else { 

      if (cant_dias_asistencia == 7) {

        tabla_bloc_TOTAL_1 = `<tr> <td class="text-center" colspan="18"></td> <td class="text-center"> <b>TOTAL</b> </td> <td class="text-center"><span  class="pago_total_quincenal"> ${formato_miles(total_pago.toFixed(2))}</span> </td> </tr>`;
        
      } else {

        tabla_bloc_TOTAL_1 = `<tr> <td class="text-center" colspan="25"></td> <td class="text-center"> <b>TOTAL</b> </td> <td class="text-center"><span  class="pago_total_quincenal"> ${formato_miles(total_pago.toFixed(2))}</span> </td> </tr>`;
        
      }
    }

    $(".data_table_body").append(tabla_bloc_TOTAL_1);

    $("#ver_asistencia").show();
    $('#cargando-registro-asistencia').hide();    

  }); //end post - ver_datos_quincena
  
  $('[data-toggle="tooltip"]').tooltip();

  count_dias_asistidos = 0;  horas_nomr_total = 0;   horas_extr_total = 0;
}

// Calculamos las: Horas normal/extras,	Días asistidos,	Sueldo Mensual,	Jornal,	Sueldo hora,	Sabatical,	Pago parcial,	Adicional/descuento,	Pago quincenal
function calcular_he(fecha, span_class_he, input_class_hn, id_trabajador, cant_dias_asistencia, sueldo_hora, cant_trabajador , sabatical_manual_1, sabatical_manual_2) {
  console.log(fecha, span_class_he, input_class_hn, id_trabajador, cant_dias_asistencia, sueldo_hora, cant_trabajador , sabatical_manual_1, sabatical_manual_2);
  //limpiamos los sabaticales
  if (sabatical_manual_1 == '-') { $(`.desglose_q_s_${id_trabajador}_7`).val(''); }
 
  if (sabatical_manual_2 == '-') { $(`.desglose_q_s_${id_trabajador}_14`).val(''); }

  var hora_extr = 0; var hora_norm = 0; var capturar_val_input = document.getElementById(input_class_hn).value; //$(`.${input_class_hn}`).val();

  // console.log(capturar_val_input);

  if ( parseFloat(capturar_val_input) > 8) {

    hora_extr = parseFloat(capturar_val_input) - 8;

    hora_norm = 8;

    $(`.input_HE_${id_trabajador}_${fecha}`).val(hora_extr); $(`.${span_class_he}`).html(hora_extr);   $(`.${input_class_hn}`).val(hora_norm);

  }else{ 

    $(`.${span_class_he}`).html('0.0'); // hora_norm = parseFloat(input_val.value); 

    $(`.input_HE_${id_trabajador}_${fecha}`).val(0.00);
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
  $(`.total_HN_${id_trabajador}`).html(suma_hn);

  $(`.total_HE_${id_trabajador}`).html(suma_he);

  $(`.dias_asistidos_${id_trabajador}`).html(dias_asistidos);  

  // asignamos los pagos parciales
  $(`.pago_parcial_HN_${id_trabajador}`).html(formato_miles((suma_hn * parseFloat(sueldo_hora)).toFixed(2)));

  $(`.pago_parcial_HE_${id_trabajador}`).html(formato_miles((suma_he * parseFloat(sueldo_hora)).toFixed(2)));

  // calculamos el pago quincenal con: Pago parcial,	Adicional/descuento
  var pago_quincenal = ( (parseFloat((suma_hn * parseFloat(sueldo_hora)).toFixed(2)) + parseFloat((suma_he * parseFloat(sueldo_hora)).toFixed(2))) + adicional_descuento ).toFixed(1)

  $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(pago_quincenal));

  var suma_total_quincena = 0;

  for (let k = 1; k <= parseInt(cant_trabajador); k++) {    
    //console.log($(`.val_pago_quincenal_${k}`).text(), k); 
    suma_total_quincena = suma_total_quincena + parseFloat(quitar_formato_miles($(`.val_pago_quincenal_${k}`).text())); 
  }

  // console.log(suma_total_quincena);

  $(`.pago_total_quincenal`).html(formato_miles(suma_total_quincena.toFixed(2)));
}

function adicional_descuento(cant_trabajador, id_trabajador) {

  var suma_resta = 0; var pago_parcial_HN = 0; pago_parcial_HE = 0;

  //console.log($(`.pago_quincenal_${id_trabajador}`).text());   console.log($(`.adicional_descuento_${id_trabajador}`).val());

  // capturamos los pgos parciales
  pago_parcial_HN = parseFloat( quitar_formato_miles( $(`.pago_parcial_HN_${id_trabajador}`).text())); pago_parcial_HE = parseFloat( quitar_formato_miles($(`.pago_parcial_HE_${id_trabajador}`).text()));

  if (parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) >= 0 || parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) <= 0 ) {

    suma_resta = (pago_parcial_HN + pago_parcial_HE) + parseFloat($(`.adicional_descuento_${id_trabajador}`).val());

    $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(suma_resta.toFixed(1)));

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

function calcular_sabatical(fecha, sueldo_x_hora, id_trabajador_x_proyecto, nombre_trabajador, idresumen_q_s_asistencia, numero_sabado) {
  
  if (estado_editar_asistencia) {
    // Asignamos un val:8 al sabatical
    if ($(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).is(':checked')) {

      // $(`#input_HN_${id_trabajador_x_proyecto}_${fecha}`).val('8'); console.log("tienen 8");

      // $.post("../ajax/registro_asistencia.php?op=agregar_quitar_sabatical_manual", {'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 'fecha_asist': format_a_m_d(fecha), 'sueldo_x_hora':sueldo_x_hora, 'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 'fecha_q_s_inicio': format_a_m_d(f1_r), 'fecha_q_s_fin': format_a_m_d(f2_r), 'numero_q_s':(parseInt(i_r) + 1), 'id_trabajador_x_proyecto': id_trabajador_x_proyecto, 'numero_sabado':numero_sabado, 'estado_sabatical_manual':'1' }, function (e) {
        
      //   if (e == 'ok') {
      //     tabla_principal.ajax.reload();
      //     toastr.success(`<h5>Asignado</h5> El sabatical manual de: <b> ${nombre_trabajador} </b>  a sido ASIGNADO con éxito.`);
      //   } else {
      //     $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', false);
      //     toastr.error(`<h5>Error</h5> Sabatical manual, no se registro correctamente.`);
      //   }
      // }); 
      $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', false);
      toastr.error(`<h5>Guarda estos datos</h5> guarda estos datos para "asignar o quitar" un sabatical.`);

    } else { // Asignamos un val:0 al sabatical
      // $(`#input_HN_${id_trabajador_x_proyecto}_${fecha}`).val('0'); console.log("tienen 0");
      // $.post("../ajax/registro_asistencia.php?op=agregar_quitar_sabatical_manual", {'idresumen_q_s_asistencia': idresumen_q_s_asistencia,  'fecha_asist': format_a_m_d(fecha), 'sueldo_x_hora':sueldo_x_hora, 'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 'fecha_q_s_inicio': format_a_m_d(f1_r), 'fecha_q_s_fin': format_a_m_d(f2_r), 'numero_q_s':(parseInt(i_r) + 1), 'id_trabajador_x_proyecto': id_trabajador_x_proyecto, 'numero_sabado':numero_sabado, 'estado_sabatical_manual':'0' }, function (e) {
        
      //   if (e == 'ok') { 
      //     tabla_principal.ajax.reload();
      //     toastr.success(`<h5>Anulado</h5> El sabatical manual de: <b> ${nombre_trabajador} </b>  a sido ANULADO con éxito.`);
      //   } else {
      //     $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', true);
      //     toastr.error(`<h5>Error</h5> Sabatical manual, no a sido ANULADO correctamente.`);
      //   }
      // });
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
        text: ``,
        html:`El trabajador: <b>${nombre_trabajador}</b> tendra un sabatical para su "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, enviar!",
      }).then((result) => {
        if (result.isConfirmed) {
          $.post("../ajax/registro_asistencia.php?op=agregar_quitar_sabatical_manual", {'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 'fecha_asist': format_a_m_d(fecha), 'sueldo_x_hora':sueldo_x_hora, 'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 'fecha_q_s_inicio': format_a_m_d(f1_r), 'fecha_q_s_fin': format_a_m_d(f2_r), 'numero_q_s':(parseInt(i_r) + 1), 'id_trabajador_x_proyecto': id_trabajador_x_proyecto, 'numero_sabado':numero_sabado, 'estado_sabatical_manual':'1' }, function (e) {
            if (e == 'ok') {
              datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r);
              tabla_principal.ajax.reload();
              Swal.fire("Asignado!", `El sabatical manual de: ${nombre_trabajador} a sido guardado con éxito.`, "success"); 
            } else {
              Swal.fire("Error!", e, "error");
              $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', false);
            }
          });    
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
        confirmButtonText: "Si, enviar!",
      }).then((result) => {
        if (result.isConfirmed) {
          $.post("../ajax/registro_asistencia.php?op=agregar_quitar_sabatical_manual", {'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 'fecha_asist': format_a_m_d(fecha), 'sueldo_x_hora':sueldo_x_hora, 'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 'fecha_q_s_inicio': format_a_m_d(f1_r), 'fecha_q_s_fin': format_a_m_d(f2_r), 'numero_q_s':(parseInt(i_r) + 1), 'id_trabajador_x_proyecto': id_trabajador_x_proyecto, 'numero_sabado':numero_sabado, 'estado_sabatical_manual':'0' }, function (e) {
            if (e == 'ok') {
              datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r);
              tabla_principal.ajax.reload();
              Swal.fire("Quitado!", `El sabatical de: ${nombre_trabajador} a sido QUITADO con éxito.`, "success");
            } else {
              Swal.fire("Error!", e, "error");
              $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', true);
            }            
          });    
        }else{
          $(`#checkbox_sabatical_${id_trabajador_x_proyecto}_${numero_sabado}`).prop('checked', true);
        }
      });
    }
  }
  
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

function guardar_fechas_asistencia() {

  // abrimos el modal cargando
  $("#modal-cargando").modal("show");

  var array_datos_asistencia = []; var array_extras = []; var horas_extras_dia = 0; var pago_horas_extras = 0;

  // rellenamos el array ASISTENCIA para la bd "ASISTENCIA TRABAJADOR"
  array_asistencia.forEach((element,index) => {

    horas_extras_dia = 0; pago_horas_extras = 0;

    if ( parseFloat($(`.${element.class_input_hn}`).val()) >= 0) {

      if ($(`.${element.class_input_he}`).val() == undefined) {
        horas_extras_dia = 0;  pago_horas_extras = 0;
      } else {
        horas_extras_dia = $(`.${element.class_input_he}`).val(); 
        pago_horas_extras = (parseFloat($(`.${element.class_input_he}`).val()) * element.sueldo_hora).toFixed(2);
      }
      var input_asistencia = { 
        'id_trabajador':element.id_trabajador,        
        'fecha_asistida':format_a_m_d(element.fecha_asistida),
        'nombre_dia':extraer_dia_semana_complet(format_a_m_d(element.fecha_asistida)),
        'horas_normal_dia':$(`.${element.class_input_hn}`).val(),
        'pago_normal_dia':(parseFloat($(`.${element.class_input_hn}`).val()) * element.sueldo_hora).toFixed(2) ,
        'horas_extras_dia':horas_extras_dia,
        'pago_horas_extras':pago_horas_extras
      }

      array_datos_asistencia.push( input_asistencia );
    }    
  }); 

  // rellenamos el array EXTRAS para la bd "RESUMEN Q S ASISTENCIA"
  array_trabajador.forEach((element,index) => {

    var data_array_extras = {
      'id_trabajador':element.id_trabajador,
      'fecha_q_s_inicio':format_a_m_d(f1_r),
      'fecha_q_s_fin':format_a_m_d(f2_r),
      'num_semana': (parseInt(i_r) + 1),
      'total_hn':quitar_formato_miles($(`.total_HN_${element.id_trabajador}`).text()),
      'total_he':quitar_formato_miles($(`.total_HE_${element.id_trabajador}`).text()),
      'dias_asistidos':$(`.dias_asistidos_${element.id_trabajador}`).text(),
      'sabatical':$(`.sabatical_${element.id_trabajador}`).text(),
      'pago_parcial_hn':quitar_formato_miles($(`.pago_parcial_HN_${element.id_trabajador}`).text()),
      'pago_parcial_he':quitar_formato_miles($(`.pago_parcial_HE_${element.id_trabajador}`).text()),
      'adicional_descuento':$(`.adicional_descuento_${element.id_trabajador}`).val(),
      'pago_quincenal':quitar_formato_miles($(`.pago_quincenal_${element.id_trabajador}`).text())
    }
    array_extras.push( data_array_extras );
  }); 

  // console.log(array_trabajador);
  console.log(array_extras);
  //console.log(array_datos_asistencia);

  editar_fechas_asistencia(2);

  $.ajax({
    url: "../ajax/registro_asistencia.php?op=guardaryeditar",
    type: "POST",
    data:  {
      'asistencia': JSON.stringify(array_datos_asistencia), 
      'extras':JSON.stringify(array_extras),
      'fecha_inicial':format_a_m_d(f1_r), 
      'fecha_final':format_a_m_d(f2_r)
    },
    // contentType: false,
    // processData: false,
    success: function (datos) {
             
      if (datos == 'ok') {

        datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r); tabla_principal.ajax.reload();
        
        $("#icono-respuesta").html(`<div class="swal2-icon swal2-success swal2-icon-show" style="display: flex;"> <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div> <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span> <div class="swal2-success-ring"></div> <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div> <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div> </div>  <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Correcto!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">Asistencia registrada correctamente</div> </div>` );

        // Swal.fire("Correcto!", "Asistencia registrada correctamente", "success");
        
	      $(".progress-bar").addClass("bg-success"); $("#barra_progress").text("100% Completado!");
        
			}else{

        $("#icono-respuesta").html(`<div class="swal2-icon swal2-error swal2-icon-show" style="display: flex;"> <span class="swal2-x-mark"> <span class="swal2-x-mark-line-left"></span> <span class="swal2-x-mark-line-right"></span> </span> </div> <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Error!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">${datos}</div> </div>`);

        $(".progress-bar").addClass("bg-danger"); $("#barra_progress").text("100% Error!");

				// Swal.fire("Error!", datos, "error");
			}
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {

          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});

          $("#barra_progress").text(percentComplete.toFixed(2)+" %");

          if (percentComplete === 100) {

            setTimeout(l_m, 600);
          }
        }
      }, false);
      return xhr;
    }
  });
}

function l_m(){  
   
  // $("#barra_progress").css({"width":'0%'});
  // $("#barra_progress").text("100% completado");
  $(".progress-bar").removeClass("progress-bar-striped")
  
}

function cerrar_modal_cargando() {
  $("#modal-cargando").modal("hide");
  $(".progress-bar").removeClass("bg-success bg-danger");
  $(".progress-bar").addClass("progress-bar-striped");
}

function asignar_pago_al_contador(fecha_q_s_inicio, id_trabajador_x_proyecto, nombre_trabajador, idresumen_q_s_asistencia, pago_quincenal) {
 
  if (idresumen_q_s_asistencia !== "" && parseFloat(pago_quincenal) > 0) {
     
    if ($(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).is(':checked')) {

      Swal.fire({
        title: "¿Está Seguro de enviar el pago al contador?",
        text: ``,
        html:`Se enviara datos de: <b>${nombre_trabajador}</b> al contador, este podra hacer el pago del trabajdor de esta "quincena" o "semana".`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, enviar!",
      }).then((result) => {
        if (result.isConfirmed) {

          $.post("../ajax/registro_asistencia.php?op=guardaryeditar_pago_al_contador", { 'fecha_q_s_inicio': fecha_q_s_inicio, 'id_trabajador_x_proyecto': id_trabajador_x_proyecto, 'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 'estado_envio_contador':"1"  }, function (e) {
            if (e == 'ok') {
              datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r);
              Swal.fire("Enviado!", `El pago de: ${nombre_trabajador} a sido enviado con éxito.`, "success");
              // $(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).prop('checked', true);
            } else {
              Swal.fire("Error!", e, "error");
              $(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).prop('checked', false);
            }
          });  

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
        confirmButtonText: "Si, enviar!",
      }).then((result) => {
        if (result.isConfirmed) {

          $.post("../ajax/registro_asistencia.php?op=guardaryeditar_pago_al_contador", { 'fecha_q_s_inicio': fecha_q_s_inicio, 'id_trabajador_x_proyecto': id_trabajador_x_proyecto, 'idresumen_q_s_asistencia': idresumen_q_s_asistencia, 'estado_envio_contador':"0" }, function (e) {
            if (e == 'ok') {
              datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r);
              Swal.fire("Quitado!", `El pago de: ${nombre_trabajador} a sido ANULADO con éxito.`, "success");
              // $(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).prop('checked', false);
            } else {
              Swal.fire("Error!", e, "error");
              $(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).prop('checked', true);
            }            
          });            
    
        }else{
          $(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).prop('checked', true);
        }
      });
    }
  }else{

    toastr.error(`El trabajador no tiene ningun pago registrado, <h5>registre alguno.</h5>`);

    $(`#checkbox_asignar_pago_contador_${id_trabajador_x_proyecto}`).prop('checked', false);
  }  
}

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   H O R A S   M U L  T I P L E S  :::::::::::::::::::::::::::::::::::::::..

function modal_horas_multiples() {
  editar_fechas_asistencia(2)
  $('#modal-agregar-horas-multiples').modal('show');
}

function agregar_horas_multiples(e) {
  var horas = $('#horas_multiples').val();
  $('.hr_multiple').val(horas);
  $('#modal-agregar-horas-multiples').modal('hide');
  toastr.success(`<h5>${horas} Horas.</h5> Se agregaron a todos los trabajadores.`);

}
// $('.horas-multiples').on('click', function (e) {
//   console.log('guardando valores');
//   $('.input_asist').val();
// });

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   A D I C I O N A L   D E S C U E N T O  :::::::::::::::::::::::::::::::::::::::..
function guardaryeditar_adicional_descuento(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-adicional-descuento")[0]);

  $.ajax({
    url: "../ajax/registro_asistencia.php?op=guardaryeditar_adicional_descuento",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

        datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r);

        Swal.fire("Correcto!", "Descripción registrada correctamente", "success");

        $("#modal-adicional-descuento").modal("hide");

			}else{

				Swal.fire("Error!", datos, "error");
			}
    },
  });
}

function modal_adicional_descuento( id_adicional, id_trabjador, fecha_q_s) {

  $("#cargando-5-fomulario").hide(); 
  $("#cargando-6-fomulario").show();

  $("#idresumen_q_s_asistencia").val(id_adicional);
  $("#idtrabajador_por_proyecto").val(id_trabjador);
  $("#fecha_q_s").val(fecha_q_s);
  // $("#detalle_adicional").val(descripcion);

  $("#modal-adicional-descuento").modal("show");

  $.post("../ajax/registro_asistencia.php?op=descripcion_adicional_descuento",{"id_adicional":id_adicional}, function(data){
    data = JSON.parse(data);  console.log(data);  

    if (data != null) {
      if (data.length === 0 ) {  }else{

        $("#detalle_adicional").val(data.descripcion_descuento);
      }
    }     
    
    $("#cargando-5-fomulario").show(); 
    $("#cargando-6-fomulario").hide();

  });
}

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   A S I S T E N C I A   I N D I V I D U A L  :::::::::::::::::::::::::::::::::::::::..

// TBLA - ASISTENCIA INDIVIDUAL
function ver_asistencias_individual(idtrabajador_por_proyecto, fecha_inicio_proyect) {

  console.log(idtrabajador_por_proyecto,fecha_inicio_proyect);
  
  mostrar_form_table(3);

  tabla_horas = $('#tabla-detalle-asistencia-individual').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
      url: '../ajax/registro_asistencia.php?op=listar_asis_individual&idtrabajadorproyecto='+idtrabajador_por_proyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) { 

      // columna: Horas normal
      if (data[0] != '') {
        $("td", row).eq(0).addClass('text-center');         
      }

      // columna: Horas normal
      if (data[2] != '') {
        $("td", row).eq(2).addClass('text-center');         
      }

      // columna: Pago por horas normal
      if (data[3] != '') {
        $("td", row).eq(3).addClass('text-nowrap text-right');         
      }

      // columna: Horas normal
      if (data[4] != '') {
        $("td", row).eq(4).addClass('text-center');         
      }

      // columna: Pago por horas extras
      if (data[5] != '') {
        $("td", row).eq(5).addClass('text-right');         
      }   

      // columna: Pago por horas normal
      if (data[6] != '') {
        $("td", row).eq(6).addClass('text-nowrap');         
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
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();   
}

//Función para desactivar registros
function justificar(idasistencia,horas, estado) {
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

      Swal.fire("No puedes Justificar!", "Este trabajador tiene 8 horas completas, las justificación es para compensar horas perdidas.", "info");
    
    } else {

      $("#modal-justificar-asistencia").modal("show");

      $.post("../ajax/registro_asistencia.php?op=mostrar_justificacion", { 'idasistencia_trabajador': idasistencia }, function (data, status) {
        
        data = JSON.parse(data);  console.log(data);

        $('#detalle_j').val(data.descripcion_justificacion);

        if (data.doc_justificacion == '' || data.doc_justificacion == null || data.doc_justificacion == 'null') {
          $('.descargar').hide();
          $('.ver_completo').hide();
          $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
          $("#doc1_nombre").html('');
          $('#doc_old_1').val("");
          $('#doc1').val("");
          
        } else {
      
          $('.descargar').show();
          $('.ver_completo').show();
      
          $('#descargar_rh').attr('href', `../dist/docs/asistencia_obrero/justificacion/${data.doc_justificacion}`);
                  
          $('#descargar_rh').attr('download', `Justificacion`); 
             

          $('#ver_completo').attr('href', `../dist/docs/asistencia_obrero/justificacion/${data.doc_justificacion}`);
          $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Recibo-por-honorario.${extrae_extencion(data.doc_justificacion)}</i></div></div>`);
      
          $('#doc_old_1').val(data.doc_justificacion);
          $('#doc1').val('');
      
          if ( extrae_extencion(data.doc_justificacion) == "pdf" ) {
            $("#doc1_ver").html(`<iframe src="../dist/docs/asistencia_obrero/justificacion/${data.doc_justificacion}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
          } else {
            if ( extrae_extencion(data.doc_justificacion) == "jpeg" || extrae_extencion(data.doc_justificacion) == "jpg" || extrae_extencion(data.doc_justificacion) == "jpe" ||
              extrae_extencion(data.doc_justificacion) == "jfif" || extrae_extencion(data.doc_justificacion) == "gif" || extrae_extencion(data.doc_justificacion) == "png" ||
              extrae_extencion(data.doc_justificacion) == "tiff" || extrae_extencion(data.doc_justificacion) == "tif" || extrae_extencion(data.doc_justificacion) == "webp" ||
              extrae_extencion(data.doc_justificacion) == "bmp" || extrae_extencion(data.doc_justificacion) == "svg" ) {
      
              $("#doc1_ver").html(`<img src="../dist/docs/asistencia_obrero/justificacion/${data.doc_justificacion}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
              
            } else {
              $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
            }      
          }
        }

      });
    }
  } 
}

function guardar_y_editar_justificar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-justificar-asistencia")[0]);

  $.ajax({
    url: "../ajax/registro_asistencia.php?op=guardar_y_editar_justificacion",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {        

        Swal.fire("Correcto!", "Descripción registrada correctamente", "success");

        $("#modal-justificar-asistencia").modal("hide");

        tabla_horas.ajax.reload();

			}else{

				Swal.fire("Error!", datos, "error");
			}
    },
  });
}

//Función para desactivar registros
function desactivar_dia_asistencia(idasistencia_trabajador) {
  $(".tooltip").removeClass('show');
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
      $.post("../ajax/registro_asistencia.php?op=desactivar", { idasistencia_trabajador: idasistencia_trabajador }, function (e) {

        Swal.fire("Desactivado!", "La asistencia ha sido desactivado.", "success");
    
        tabla_principal.ajax.reload(); tabla_horas.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar_dia_asistencia(idasistencia_trabajador) {
  $(".tooltip").removeClass('show');
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
      $.post("../ajax/registro_asistencia.php?op=activar", { idasistencia_trabajador: idasistencia_trabajador }, function (e) {

        Swal.fire("Activado!", "La asistencia ha sido activado.", "success");

        tabla_principal.ajax.reload(); tabla_horas.ajax.reload();
      });
      
    }
  });      
}

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   Q U I N C E N A S   O   S E M A N A S  :::::::::::::::::::::::::::::::::::::::..
// TBLA - QUINCENA SEMANA INDIVIDUAL
function ver_q_s_individual(idtrabajador_por_proyecto) {

  idtrabajador_por_proyecto_r = idtrabajador_por_proyecto;

  mostrar_form_table(4);

  tabla_qs = $('#tabla-detalle-qs-individual').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
      url: '../ajax/registro_asistencia.php?op=listar_qs_individual&idtrabajadorproyecto='+idtrabajador_por_proyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) { 
      
      // columna: Horas normal
      if (data[1] != '') {
        $("td", row).eq(1).addClass('text-center');         
      }      

      // columna: Pago por horas normal
      if (data[3] != '') {
        $("td", row).eq(3).addClass('text-nowrap text-right');         
      }

      // columna: Adicional
      if (data[4] != '') {
        $("td", row).eq(4).addClass('text-right');         
      }

      // columna: Sabatical
      if (data[5] != '') {
        $("td", row).eq(5).addClass('text-center');         
      }

      // columna: Pago quincenal o semanal
      if (data[6] != '') {
        $("td", row).eq(6).addClass('text-right');         
      }   

      // columna: Contador
      if (data[7] != '') {
        $("td", row).eq(7).addClass('text-center');         
      }
      // columna: Estado
      if (data[8] != '') {
        $("td", row).eq(8).addClass('text-center');         
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
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();

  //Suma ACUMULADO
  $.post("../ajax/registro_asistencia.php?op=suma_qs_individual", { 'idtrabajadorproyecto': idtrabajador_por_proyecto }, function (data, status) {

    data =JSON.parse(data); //console.log(data);

    if (data) {
      $(".thead_num").html(`Num. ${data.fecha_pago_obrero}`);
      $(".thead_fecha").html(`Fechas ${data.fecha_pago_obrero}`);
      $(".thead_pago").html(`Pago ${data.fecha_pago_obrero}`);
      $("#suma_qs_individual").html(`S/. <b>${formato_miles(data.pago_quincenal)}</b> `);
    } else {
      $("#suma_qs_individual").html("S/. 0.00");
    }
  });
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
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/registro_asistencia.php?op=desactivar_qs", { 'idresumen_q_s_asistencia': id }, function (e) {
        
        if (e == 'ok') {
          Swal.fire("Desactivado!", `La ${tipo_pago} ha sido desactivado.`, "success");
          tabla_qs.ajax.reload(); tabla_principal.ajax.reload();
          ver_q_s_individual(idtrabajador_por_proyecto_r);
        } else {
          Swal.fire("Error!", e, "error");
        }        
      });      
    }
  });  
  $(".tooltip").removeClass('show'); 
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
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/registro_asistencia.php?op=activar_qs", { 'idresumen_q_s_asistencia': id }, function (e) {

        if (e == 'ok') {
          Swal.fire("Activado!", `La ${tipo_pago} ha sido activado.`, "success");
          tabla_qs.ajax.reload(); tabla_principal.ajax.reload();
          ver_q_s_individual(idtrabajador_por_proyecto_r);
        } else {
          Swal.fire("Error!", e, "error");
        }        
      });      
    }
  });     
  $(".tooltip").removeClass('show'); 
}

// .....::::::::::::::::::::::::::::::::::::: S E C C I Ó N   F E C H A S   D E   A C T I V I D A D E S  :::::::::::::::::::::::::::::::::::::::..

function limpiar_form_fechas_actividades(params) {
  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();

  $('#id_proyecto_f').val(localStorage.getItem('nube_idproyecto'));

  $('#fecha_inicio_actividad').val("");
  $('#fecha_fin_actividad').val("");
  $('#plazo_actividad').val("");

  $.post("../ajax/registro_asistencia.php?op=fechas_actividad", { 'id_proyecto': localStorage.getItem('nube_idproyecto') }, function (data, status) {
    
    data = JSON.parse(data);  console.log(data);

    $('#fecha_inicio_actividad').val(format_d_m_a(data.fecha_inicio_actividad));
    $('#fecha_fin_actividad').val(format_d_m_a(data.fecha_fin_actividad));
    $('#plazo_actividad').val(data.plazo_actividad);
    $('.plazo_actividad').html(data.plazo_actividad);

    $("#cargando-7-fomulario").show();
    $("#cargando-8-fomulario").hide();
  });
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

  $.ajax({
    url: "../ajax/registro_asistencia.php?op=guardar_y_editar_fechas_actividad",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
             
      if (datos == 'ok') {        

        Swal.fire("Correcto!", "Fechas registrada correctamente", "success");

        $("#modal-agregar-fechas-actividades").modal("hide");

        tbla_principal(localStorage.getItem('nube_idproyecto'));

        mostrar_form_table(1);

			}else{

				Swal.fire("Error!", datos, "error");
			}
    },
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

      $(element).addClass("is-invalid");
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

      $(element).addClass("is-invalid");
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

      $(element).addClass("is-invalid");
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

      $(element).addClass("is-invalid");
    },

    unhighlight: function (element, errorClass, validClass) {

      $(element).removeClass("is-invalid").addClass("is-valid");
    },

    submitHandler: function (form) {
      agregar_horas_multiples(form);
    },
  });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

// retrazamos la ejecuccion de una funcion
var delay = (function(){
  var timer = 0;
  return function(callback, ms){ clearTimeout (timer); timer = setTimeout(callback, ms); };
})();

/**formato_miles */
function formato_miles(num) {
  if (!num || num == "NaN") return "0.00";
  if (num == "Infinity") return "&#x221e;";
  num = num.toString().replace(/\$|\,/g, "");
  if (isNaN(num)) num = "0";
  sign = num == (num = Math.abs(num));
  num = Math.floor(num * 100 + 0.50000000001);
  cents = num % 100;
  num = Math.floor(num / 100).toString();
  if (cents < 10) cents = "0" + cents;
  for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) num = num.substring(0, num.length - (4 * i + 3)) + "," + num.substring(num.length - (4 * i + 3));
  return (sign ? "" : "-") + num + "." + cents;
}

function quitar_formato_miles(numero) {
  let inVal = numero.replace(/,/g, '');
  return inVal;
}

// Función que suma o resta días a la fecha indicada
sumaFecha = function(d, fecha){
  var Fecha = new Date();
  var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
  var sep = sFecha.indexOf('/') != -1 ? '/' : '-';
  var aFecha = sFecha.split(sep);
  var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];
  fecha= new Date(fecha);
  fecha.setDate(fecha.getDate()+parseInt(d));
  var anno=fecha.getFullYear();
  var mes= fecha.getMonth()+1;
  var dia= fecha.getDate();
  mes = (mes < 10) ? ("0" + mes) : mes;
  dia = (dia < 10) ? ("0" + dia) : dia;
  var fechaFinal = dia+sep+mes+sep+anno;
  return (fechaFinal);
}

// Extrae los nombres de dias de semana "Abreviado"
function extraer_dia_semana(fecha) {
  const fechaComoCadena = fecha; // día fecha
  const dias = ['lu', 'ma', 'mi', 'ju', 'vi', 'sa', 'do']; //
  const numeroDia = new Date(fechaComoCadena).getDay();
  const nombreDia = dias[numeroDia];
  return nombreDia;
}

// extrae los nombres de dias de semana "Completo"
function extraer_dia_semana_complet(fecha) {
  const fechaComoCadena = fecha; // día fecha
  const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']; //
  const numeroDia = new Date(fechaComoCadena).getDay();
  const nombreDia = dias[numeroDia];
  return nombreDia;
}

// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {

  var format = "";

  if (fecha == '' || fecha == null || fecha == '0000-00-00') {
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

  if (fecha == '' || fecha == null || fecha == '00-00-0000') {
    format = "-";
  } else {
    let splits = fecha.split("-"); //console.log(splits);
    format = splits[2]+'-'+splits[1]+'-'+splits[0];
  } 

  return format;
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


/* PREVISUALIZAR LOS DOCUMENTOS */
function addDocs(e,id) {

  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');	console.log(id);

	var file = e.target.files[0], archivoType = /image.*|application.*/;
	
	if (e.target.files[0]) {
    
		var sizeByte = file.size; console.log(file.type);

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(archivoType) ){
			// return;
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Este tipo de ARCHIVO no esta permitido elija formato: .pdf, .png. .jpeg, .jpg, .jpe, .webp, .svg',
        showConfirmButton: false,
        timer: 1500
      });

      $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >'); 

		}else{

			if (sizekiloBytes <= 40960) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;

          // cargamos la imagen adecuada par el archivo
				  if ( extrae_extencion(file.name) == "doc") {
            $("#"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
          } else {
            if ( extrae_extencion(file.name) == "docx" ) {
              $("#"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
            }else{
              if ( extrae_extencion(file.name) == "pdf" ) {
                $("#"+id+"_ver").html(`<iframe src="${result}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
              }else{
                if ( extrae_extencion(file.name) == "csv" ) {
                  $("#"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
                } else {
                  if ( extrae_extencion(file.name) == "xls" ) {
                    $("#"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
                  } else {
                    if ( extrae_extencion(file.name) == "xlsx" ) {
                      $("#"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                    } else {
                      if ( extrae_extencion(file.name) == "xlsm" ) {
                        $("#"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                      } else {
                        if (
                          extrae_extencion(file.name) == "jpeg" || extrae_extencion(file.name) == "jpg" || extrae_extencion(file.name) == "jpe" ||
                          extrae_extencion(file.name) == "jfif" || extrae_extencion(file.name) == "gif" || extrae_extencion(file.name) == "png" ||
                          extrae_extencion(file.name) == "tiff" || extrae_extencion(file.name) == "tif" || extrae_extencion(file.name) == "webp" ||
                          extrae_extencion(file.name) == "bmp" || extrae_extencion(file.name) == "svg" ) {

                          $("#"+id+"_ver").html(`<img src="${result}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
                          
                        } else {
                          $("#"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                        }
                        
                      }
                    }
                  }
                }
              }
            }
          } 
					$("#"+id+"_nombre").html(`<div class="row">
            <div class="col-md-12">
              <i> ${file.name} </i>
            </div>
            <div class="col-md-12">
              <button class="btn btn-danger btn-block btn-xs" onclick="${id}_eliminar();" type="button" ><i class="far fa-trash-alt"></i></button>
            </div>
          </div>`);

          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `El documento: ${file.name.toUpperCase()} es aceptado.`,
            showConfirmButton: false,
            timer: 1500
          });
				}

				reader.readAsDataURL(file);

			} else {
        Swal.fire({
          position: 'top-end',
          icon: 'warning',
          title: `El documento: ${file.name.toUpperCase()} es muy pesado.`,
          showConfirmButton: false,
          timer: 1500
        })

        $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
        $("#"+id+"_nombre").html("");
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
		 
    $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
		$("#"+id+"_nombre").html("");
    $("#"+id).val("");
	}	
}

// recargar un doc para ver
function re_visualizacion(id, carpeta) {

  $("#doc"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>'); console.log(id);

  pdffile     = document.getElementById("doc"+id+"").files[0];

  var antiguopdf  = $("#doc_old_"+id+"").val();

  if(pdffile === undefined){

    if (antiguopdf == "") {

      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Seleccione un documento',
        showConfirmButton: false,
        timer: 1500
      })

      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

		  $("#doc"+id+"_nombre").html("");

    } else {
      if ( extrae_extencion(antiguopdf) == "doc") {
        $("#doc"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
        toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
      } else {
        if ( extrae_extencion(antiguopdf) == "docx" ) {
          $("#doc"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
          toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
        } else {
          if ( extrae_extencion(antiguopdf) == "pdf" ) {
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/asistencia_obrero/${carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
            toastr.success('Documento vizualizado correctamente!!!')
          } else {
            if ( extrae_extencion(antiguopdf) == "csv" ) {
              $("#doc"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
              toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
            } else {
              if ( extrae_extencion(antiguopdf) == "xls" ) {
                $("#doc"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
                toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
              } else {
                if ( extrae_extencion(antiguopdf) == "xlsx" ) {
                  $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                  toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                } else {
                  if ( extrae_extencion(antiguopdf) == "xlsm" ) {
                    $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                    toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                  } else {
                    if (
                      extrae_extencion(antiguopdf) == "jpeg" || extrae_extencion(antiguopdf) == "jpg" || extrae_extencion(antiguopdf) == "jpe" ||
                      extrae_extencion(antiguopdf) == "jfif" || extrae_extencion(antiguopdf) == "gif" || extrae_extencion(antiguopdf) == "png" ||
                      extrae_extencion(antiguopdf) == "tiff" || extrae_extencion(antiguopdf) == "tif" || extrae_extencion(antiguopdf) == "webp" ||
                      extrae_extencion(antiguopdf) == "bmp" || extrae_extencion(antiguopdf) == "svg" ) {
  
                      $("#doc"+id+"_ver").html(`<img src="../dist/docs/asistencia_obrero/${carpeta}/${antiguopdf}" alt="" onerror="this.src='../dist/svg/error-404-x.svg';" width="100%" >`);
                      toastr.success('Documento vizualizado correctamente!!!');
                    } else {
                      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                      toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                    }                    
                  }
                }
              }
            }
          }
        }
      }      
    }
    // console.log('hola'+dr);
  }else{

    pdffile_url=URL.createObjectURL(pdffile);

    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(pdffile.name) == "doc") {
      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
      toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
    } else {
      if ( extrae_extencion(pdffile.name) == "docx" ) {
        $("#doc"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
        toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
      }else{
        if ( extrae_extencion(pdffile.name) == "pdf" ) {
          $("#doc"+id+"_ver").html('<iframe src="'+pdffile_url+'" frameborder="0" scrolling="no" width="100%" height="310"> </iframe>');
          toastr.success('Documento vizualizado correctamente!!!');
        }else{
          if ( extrae_extencion(pdffile.name) == "csv" ) {
            $("#doc"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
            toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
          } else {
            if ( extrae_extencion(pdffile.name) == "xls" ) {
              $("#doc"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
              toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
            } else {
              if ( extrae_extencion(pdffile.name) == "xlsx" ) {
                $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
              } else {
                if ( extrae_extencion(pdffile.name) == "xlsm" ) {
                  $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                  toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
                } else {
                  if (
                    extrae_extencion(pdffile.name) == "jpeg" || extrae_extencion(pdffile.name) == "jpg" || extrae_extencion(pdffile.name) == "jpe" ||
                    extrae_extencion(pdffile.name) == "jfif" || extrae_extencion(pdffile.name) == "gif" || extrae_extencion(pdffile.name) == "png" ||
                    extrae_extencion(pdffile.name) == "tiff" || extrae_extencion(pdffile.name) == "tif" || extrae_extencion(pdffile.name) == "webp" ||
                    extrae_extencion(pdffile.name) == "bmp" || extrae_extencion(pdffile.name) == "svg" ) {

                    $("#doc"+id+"_ver").html(`<img src="${pdffile_url}" alt="" width="50%" >`);
                    toastr.success('Documento vizualizado correctamente!!!');
                  } else {
                    $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                    toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
                  }                  
                }
              }
            }
          }
        }
      }
    }     	
    console.log(pdffile);
  }
}

function extrae_extencion(filename) {
  return filename.split('.').pop();
}

// voy a eliminar esta funcion cuando no lo NECESITE -----------------------
function mostrar(idasistencia_trabajador) {
  $('#modal-editar-asistencia').modal('show')
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $.post("../ajax/registro_asistencia.php?op=mostrar_editar", { idasistencia_trabajador: idasistencia_trabajador }, function (data, status) {

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

  });
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

  $.post("../ajax/registro_asistencia.php?op=lista_trabajador", { nube_idproyecto: nube_idproyecto }, function (data, status) {

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
  });
}