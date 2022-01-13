var tabla; var tabla2; var array_asistencia = []; var array_trabajador = [];

var f1_r = 0, f2_r = 0, i_r = 0, cant_dias_asistencia_r = 0;

//Función que se ejecuta al inicio
function init() {

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar(localStorage.getItem('nube_idproyecto'));  

  // $("#bloc_Accesos").addClass("menu-open");

  $("#mAsistencia").addClass("active");

  // $("#lasistencia").addClass("active");

  // submnit a adicional descuento
  $("#guardar_adicional_descuento").on("click", function (e) { $("#submit-form-adicional-descuento").submit(); });
  // $("#modal-agregar-asistencia").on("submit",function(e) { guardaryeditar(e);	})
  
  // Formato para telefono
  $("[data-mask]").inputmask();

  //Timepicker
  $('#timepicker').datetimepicker({
    // format: 'LT',
    format:'HH:mm ',
    lang:'ru'
  })

  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1; //January is 0!
  var yyyy = today.getFullYear();

  if (dd < 10) {  dd = '0' + dd; }

  if (mm < 10) {  mm = '0' + mm;  }

  today = yyyy + '-' + mm + '-' + dd;
  $("#fecha").val(today);
}

// retrazamos la ejecuccion de una funcion
var delay = (function(){
  var timer = 0;
  return function(callback, ms){
      clearTimeout (timer);
      timer = setTimeout(callback, ms);
  };
})();

function mostrar_form_table(estados) {

  if (estados == 1 ) {
    $("#card-registrar").show();
    $("#card-regresar").hide();
    $("#card-editar").hide();
    $("#card-guardar").hide();
    $("#tabla-asistencia-trab").show();
    $("#ver_asistencia").hide();
    $("#detalle_asistencia").hide();
  } else {
    if (estados == 2) {
      $("#card-registrar").hide();
      $("#card-regresar").show();
      $("#card-editar").show();
      $("#tabla-asistencia-trab").hide();
      $("#ver_asistencia").show();
      $("#detalle_asistencia").hide();
      
    } else {
      $("#card-registrar").hide();
      $("#card-regresar").show();
      $("#card-editar").hide();
      $("#card-guardar").hide();
      $("#tabla-asistencia-trab").hide();
      $("#ver_asistencia").hide();
      $("#detalle_asistencia").show();
      
    }
  }
}

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

$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) { 

      guardaryeditar_adicional_descuento(e);         
         
    },
  });  

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
  });
});

//Función limpiar
function limpiar() {
  $("#idasistencia_trabajador").val(""); 
  $("#trabajador").val("");
  $("#horas_trabajo").val("");
  
  lista_trabajadores(localStorage.getItem('nube_idproyecto'));
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

//Función Listar
function listar(nube_idproyecto) {

  $('#Lista_quincenas').html('<i class="fas fa-spinner fa-pulse fa-2x"></i>');

  tabla=$('#tabla-asistencia').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/registro_asistencia.php?op=listar&nube_idproyecto='+nube_idproyecto,
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

  //Listar quincenas(botones)
  $.post("../ajax/registro_asistencia.php?op=listarquincenas", { nube_idproyecto: nube_idproyecto }, function (data, status) {

    data =JSON.parse(data); //console.log(data);

    // validamos la existencia de DATOS
    if (data) {

      var dia_regular = 0; var weekday_regular = extraer_dia_semana(format_a_m_d(data.fecha_inicio)); var estado_regular = false;

      if (weekday_regular == "do") { dia_regular = -1; } else { if (weekday_regular == "lu") { dia_regular = -2; } else { if (weekday_regular == "ma") { dia_regular = -3; } else { if (weekday_regular == "mi") { dia_regular = -4; } else { if (weekday_regular == "ju") { dia_regular = -5; } else { if (weekday_regular == "vi") { dia_regular = -6; } else { if (weekday_regular == "sa") { dia_regular = -7; } } } } } } }
       //console.log(dia_regular, weekday_regular);
      if (data.fecha_pago_obrero == "quincenal") {

        $('#Lista_quincenas').html('');

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

          $('#Lista_quincenas').append(` <button type="button" id="boton-${i}" class="mb-2 btn bg-gradient-info text-center" onclick="datos_quincena('${fecha_inicio}', '${fecha}', '${i}', 14);"><i class="far fa-calendar-alt"></i> Quincena ${cont}<br>${fecha_inicio} // ${fecha}</button>`)
          
          fecha_i =sumaFecha(1,fecha);

          i++;
        }
      } else {
        if (data.fecha_pago_obrero == "semanal") {

          $('#Lista_quincenas').html('');

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

            $('#Lista_quincenas').append(` <button id="boton-${i}" type="button" class="mb-2 btn bg-gradient-info text-center" onclick="datos_quincena('${fecha_i}', '${fecha_f}', '${i}', 7);"><i class="far fa-calendar-alt"></i> Semana ${cont}<br>${fecha_i} // ${fecha_f}</button>`)
            
            if (val_fecha_f.getTime() >= val_fecha_proyecto.getTime()) { cal_mes = true; }else{ cal_mes = false;}

            fecha = sumaFecha(1,fecha_f);

            i++;
          } 
        } else { 
          $('#Lista_quincenas').html(`<div class="info-box shadow-lg w-px-600"> 
              <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span> 
              <div class="info-box-content"> 
                <span class="info-box-text">Alerta</span> 
                <span class="info-box-number">No has definido los bloques de fechas del proyecto. <br>Ingresa al ESCRITORIO y EDITA tu proyecto selecionado.</span> 
              </div> 
            </div>`);
        }
      }
    } else {
      
    }
    
    //console.log(fecha);
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

// voy a eliminar esta funcion cuando no lo NECESITE -----------------------
function agregar_hora_all() {
  var hora_all = $("#hora_all").val();
  $('input[type=time][name="horas_trabajo[]"]').val(hora_all);
}

// listamos la data de una quincena selecionada
function datos_quincena(f1, f2, i, cant_dias_asistencia) {

  f1_r = f1; f2_r = f2; i_r = i; cant_dias_asistencia_r = cant_dias_asistencia;

  // cambiamos el valor del colspan
  $("#dias_asistidos_s_q").attr("colspan", cant_dias_asistencia);

  $("#card-editar").show();
  $("#card-guardar").hide();  

  // vaciamos el array
  array_asistencia = []; array_trabajador = []

  // pintamos el botón
  pintar_boton_selecionado(i);

  var nube_idproyect =localStorage.getItem('nube_idproyecto');  //console.log('Quicena: '+f1 + ' al ' +f2 + ' proyect-id: '+nube_idproyect);
  
  var fecha_inicial_quincena = f1; var table_numero_semana = ""; var table_dia_semana = ""; 

  var dia_regular = 0; var count_dias_de_asistencias = 1; var total_pago = 0;

  var weekday_regular = extraer_dia_semana(format_a_m_d(fecha_inicial_quincena));

  // asignamos un numero para restar y llegar al dia DOMIGO
  if (weekday_regular == "do") { dia_regular = -0; } else { if (weekday_regular == "lu") { dia_regular = -1; } else { if (weekday_regular == "ma") { dia_regular = -2; } else { if (weekday_regular == "mi") { dia_regular = -3; } else { if (weekday_regular == "ju") { dia_regular = -4; } else { if (weekday_regular == "vi") { dia_regular = -5; } else { if (weekday_regular == "sa") { dia_regular = -6; } } } } } } }

  var fecha_inicial_quincena_regular = sumaFecha(dia_regular, fecha_inicial_quincena);

  for ( var j = 1; j<=dia_regular*-1; j++ ) {

    var weekday = extraer_dia_semana(format_a_m_d(fecha_inicial_quincena_regular));  

    table_dia_semana = table_dia_semana.concat(`<th class="p-x-10px bg-color-acc3c7"> ${fecha_inicial_quincena_regular.substr(0,2)} <br> ${weekday} </th>`);

    table_numero_semana = table_numero_semana.concat(`<th class="p-x-10px bg-color-acc3c7"> ${count_dias_de_asistencias} </th>`);

    // aumentamos mas un dia hasta llegar al dia "dia_regular"
    fecha_inicial_quincena_regular = sumaFecha(1,fecha_inicial_quincena_regular);

    count_dias_de_asistencias++;
  }

  for (i = 1; i <=cant_dias_asistencia + dia_regular; i++) {    
    //console.log('fecha-dia-number: ' + fecha_inicial_quincena );  

    var weekday = extraer_dia_semana(format_a_m_d(fecha_inicial_quincena));  

    if (weekday != 'sa') {

      table_dia_semana = table_dia_semana.concat(`<th class="p-x-10px"> ${fecha_inicial_quincena.substr(0,2)} <br> ${weekday} </th>`);

      table_numero_semana = table_numero_semana.concat(`<th class="p-x-10px"> ${count_dias_de_asistencias} </th>`);

    } else {

      table_dia_semana = table_dia_semana.concat(`<th class="p-x-10px bg-color-acc3c7">${fecha_inicial_quincena.substr(0,2)} <br> ${weekday} </th>`);
      
      table_numero_semana = table_numero_semana.concat(`<td class="p-x-10px bg-color-acc3c7"> ${count_dias_de_asistencias} </td>`);
    }

    // aumentamos mas un dia hasta llegar al dia 15
    fecha_inicial_quincena = sumaFecha(1,fecha_inicial_quincena);
    count_dias_de_asistencias++
  } //end for

  $('.data-dia-semana').html(table_dia_semana);

  $('.data-numero-semana').html(table_numero_semana);

  // ocultamos las tablas
  mostrar_form_table(2)

  $.post("../ajax/registro_asistencia.php?op=ver_datos_quincena", {f1:format_a_m_d(f1),f2:format_a_m_d(f2),nube_idproyect:nube_idproyect}, function (data, status) {
        
    data =JSON.parse(data); console.log(data);   

    $(".data_table_body").html('');   
     
    $.each(data, function (index, value) {

      var count_dias_asistidos = 0; var horas_total = 0; var horas_nomr_total = 0; var horas_extr_total = 0; var sabatical = 0;
      
      var tabla_bloc_HN_asistencia_3=""; var tabla_bloc_HE_asistencia_2 =""; var estado_hallando_sabado = true;

      // existe alguna asistencia -------
      if (value.asistencia.length != 0) {

        var i;  var fecha = f1; //console.log("tiene data");
        
        // renellamos hasta el dia inicial
        for ( var j = 1; j<=dia_regular*-1; j++ ) {

          tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center bg-color-acc3c7"> <span class="span_asist " >-</span> </td>`);
              
          tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center bg-color-acc3c7"> <span class=" " >-</span> </td>`);
          
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

          // imprimimos la fecha de asistencia encontrada 
          if (estado_fecha) {

            var weekday = extraer_dia_semana(fecha_asist); //console.log(weekday);

            if (weekday != 'sa') {

              tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center"> <span class="span_asist  span_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" >${hora_n}</span> <input class="w-px-30 input_asist input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" onkeyup="delay(function(){ calcular_he('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}')}, 300 );" type="text" value="${hora_n}" autocomplete="off" ></td>`);
              
              tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center"> <span class=" span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" >${hora_e}</span> <input class="w-px-30 input_HE_${value.idtrabajador_por_proyecto}_${i} input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} hidden" id="input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="${hora_e}" ></td>`);
              
              var input_asistncia = { 
                'id_trabajador':value.idtrabajador_por_proyecto, 
                'fecha_asistida':format_d_m_a(fecha_asist), 
                'class_input_hn':`input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}`, 
                'class_input_he':`input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}`,
                'sueldo_hora':value.sueldo_hora
              };
  
              array_asistencia.push( input_asistncia );

            } else {

              tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20" type="checkbox" id="checkbox_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" onclick="delay(function(){ calcular_sabatical('${format_d_m_a(fecha_asist)}', 'span_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', 'input_HN_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', 'checkbox_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}')}, 300 );"> <input class="input_HE_${value.idtrabajador_por_proyecto}_${i} input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)} hidden" id="input_HE_${value.idtrabajador_por_proyecto}_${format_d_m_a(fecha_asist)}" type="text" value="0" > </td>`);
              // tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat('<td class="text-center bg-color-acc3c7"> </td>');
            }
             
          } else {

            var weekday = extraer_dia_semana(format_a_m_d(fecha)); //console.log(weekday);

            if (weekday != 'sa') {

              tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center"> <span class="span_asist span_HN_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> <input class="w-px-30 input_asist input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" onkeyup="delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}')}, 300 );" type="text" value="" autocomplete="off" ></td>`);
              
              tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center"> <span class=" span_HE_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> <input class="w-px-30 input_HE_${value.idtrabajador_por_proyecto}_${i} input_HE_${value.idtrabajador_por_proyecto}_${fecha} hidden" type="text" value="" ></td>`);
              
              var input_asistncia = { 
                'id_trabajador':value.idtrabajador_por_proyecto, 
                'fecha_asistida':fecha, 
                'class_input_hn':`input_HN_${value.idtrabajador_por_proyecto}_${fecha}`,
                'class_input_he':`input_HE_${value.idtrabajador_por_proyecto}_${fecha}`,
                'sueldo_hora':value.sueldo_hora
              };
  
              array_asistencia.push( input_asistncia );

            } else {

              if (estado_hallando_sabado) {

                if (value.sabatical_manual_1 == "1") {

                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}" onclick="delay(function(){ calcular_sabatical('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', 'checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                  
                  count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;

                  sabatical = 1;
                } else {

                  if (horas_nomr_total >= 44 ) {

                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}" onclick="delay(function(){ calcular_sabatical('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', 'checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                    
                    count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;

                    sabatical = 1;
                  } else {

                    tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20" type="checkbox"  id="checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}" onclick="delay(function(){ calcular_sabatical('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', 'checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                    
                     
                  }
                }

                estado_hallando_sabado = false;
              } else {

                if (value.sabatical_manual_2 == "1") {

                  tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}" onclick="delay(function(){ calcular_sabatical('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', 'checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                  
                  count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;

                  sabatical = sabatical + 1;
                } else {

                  if (sabatical == "1") {
                    if ((horas_nomr_total - 52) >= 44 ) {

                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}" onclick="delay(function(){ calcular_sabatical('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', 'checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                      
                      count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
  
                      sabatical = sabatical + 1;
                    } else {
  
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20" type="checkbox"  id="checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}" onclick="delay(function(){ calcular_sabatical('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', 'checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                      
                       
                    }
                  } else {

                    if ((horas_nomr_total - 44) >= 44 ) {

                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20" type="checkbox" checked  id="checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}" onclick="delay(function(){ calcular_sabatical('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', 'checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                      
                      count_dias_asistidos = count_dias_asistidos + 1; horas_nomr_total = horas_nomr_total + 8;
  
                      sabatical = sabatical + 1;
                    } else {
  
                      tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20" type="checkbox"  id="checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}" onclick="delay(function(){ calcular_sabatical('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}', 'checkbox_HN_${value.idtrabajador_por_proyecto}_${fecha}')}, 300 );"> <input class="input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" type="text" value="0" > </td>`);
                      
                       
                    }
                  }                  
                }                
              }
              // tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat('<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20 " type="checkbox"> </td>');
              
              // tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat('<td class="text-center bg-color-acc3c7"> <input class="w-xy-20" type="checkbox"> </td>');
            }
          }

          // aumentamos mas un dia hasta llegar al dia 15
          fecha = sumaFecha(1,fecha);
        } //end for
      
      // no existe ninguna asistencia ------- 
      } else {

        var fecha = f1; //console.log("no ninguna fecha asistida");  

        // renellamos hasta el dia inicial
        for ( var j = 1; j<=dia_regular*-1; j++ ) {

          tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center bg-color-acc3c7"> <span class="span_asist " >-</span> </td>`);
              
          tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center bg-color-acc3c7"> <span class=" " >-</span> </td>`);
          
        }

        for (i = 1; i <=cant_dias_asistencia+dia_regular; i++) { 

          var weekday = extraer_dia_semana(format_a_m_d(fecha));

          if (weekday != 'sa') {

            tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat(`<td class="text-center"> <span class="span_asist span_HN_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> <input class="w-px-30 input_asist input_HN_${value.idtrabajador_por_proyecto}_${i} input_HN_${value.idtrabajador_por_proyecto}_${fecha} hidden" id="input_HN_${value.idtrabajador_por_proyecto}_${fecha}" onkeyup="delay(function(){ calcular_he('${fecha}', 'span_HE_${value.idtrabajador_por_proyecto}_${fecha}', 'input_HN_${value.idtrabajador_por_proyecto}_${fecha}', '${value.idtrabajador_por_proyecto}', '${cant_dias_asistencia}', '${value.sueldo_hora}', '${data.length}') }, 300 );" type="text" value="" autocomplete="off"></td>`);
            
            tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat(`<td class="text-center"> <span class=" span_HE_${value.idtrabajador_por_proyecto}_${fecha}" >-</span> <input class="w-px-30 input_HE_${value.idtrabajador_por_proyecto}_${i} input_HE_${value.idtrabajador_por_proyecto}_${fecha} hidden" type="text" value="" ></td>`);
            
            var input_asistncia = { 
              'id_trabajador':value.idtrabajador_por_proyecto, 
              'fecha_asistida':fecha, 
              'class_input_hn':`input_HN_${value.idtrabajador_por_proyecto}_${fecha}`,   
              'class_input_he':`input_HE_${value.idtrabajador_por_proyecto}_${fecha}`,
              'sueldo_hora':value.sueldo_hora
            };

            array_asistencia.push( input_asistncia );

          } else {

            tabla_bloc_HN_asistencia_3 = tabla_bloc_HN_asistencia_3.concat('<td rowspan="2" class="text-center bg-color-acc3c7 center-vertical"> <input class="w-xy-20" type="checkbox"> </td>');
            // tabla_bloc_HE_asistencia_2 = tabla_bloc_HE_asistencia_2.concat('<td class="text-center bg-color-acc3c7"> <input class="w-xy-20" type="checkbox"> </td>');
          }
          // aumentamos mas un dia hasta llegar al dia 15
          fecha = sumaFecha(1,fecha);
        } //end for
      }
      //console.log(count_dias_asistidos);
      // validamos el sabatical
      // if (horas_total >= 44 && horas_total < 88) {

      //   sabatical = 1;

      // } else {

      //   if (horas_total >= 88) {
          
      //     sabatical = 2;
      //   }
      // }

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

      var tabla_bloc_HN_pago_parcial_8 = `<td class="text-center center-vertical"> <span  class="pago_parcial_HN_${value.idtrabajador_por_proyecto}"> ${(parseFloat(value.sueldo_hora) * parseFloat(horas_nomr_total)).toFixed(2)}</span> </td>`;
      
      var fechas_adicional = "";
      
      if (value.idsumas_adicionales == "") {

        fechas_adicional = format_a_m_d(f1);

      } else {

        fechas_adicional = value.fecha_registro;
      }
      var tabla_bloc_HN_descuent_9 = `<td rowspan="2" class="text-center center-vertical"> <span class="span_asist" >${value.adicional_descuento}</span> <input class="w-px-45 input_asist hidden adicional_descuento_${value.idtrabajador_por_proyecto}" onkeyup="delay(function(){ adicional_descuento('${data.length}', '${value.idtrabajador_por_proyecto}') }, 300 );" type="text" value="${value.adicional_descuento}" autocomplete="off" > <span class="badge badge-info float-right cursor-pointer" data-toggle="tooltip" data-original-title="Por descuento" onclick="modal_adicional_descuento( '${value.idsumas_adicionales}', '${value.idtrabajador_por_proyecto}', '${fechas_adicional}', '${value.descripcion_descuento}');"><i class="far fa-eye"></i></span></td>`;

      var tabla_bloc_HN_pago_total_10 = `<td rowspan="2" class="text-center center-vertical"> <span  class="val_pago_quincenal_${index+1} pago_quincenal_${value.idtrabajador_por_proyecto}"> ${((parseFloat((parseFloat(value.sueldo_hora) * parseFloat(horas_nomr_total)).toFixed(2)) + parseFloat((parseFloat(value.sueldo_hora) * parseFloat(horas_extr_total)).toFixed(2))) + parseFloat(value.adicional_descuento) ).toFixed(2)} </span> </td>`;

      // acumulamos el total de pagos
      total_pago = total_pago + parseFloat( (  (parseFloat((parseFloat(value.sueldo_hora) * parseFloat(horas_nomr_total)).toFixed(2)) + parseFloat( (parseFloat(value.sueldo_hora) * parseFloat(horas_extr_total)).toFixed(2) ) ) + parseFloat(value.adicional_descuento)  ).toFixed(2) );
      
      var tabla_bloc_HN_1 = '<tr>'+
              '<td>H/N</td>'+
              tabla_bloc_HN_trabaj_2 +
              tabla_bloc_HN_asistencia_3 +
              tabla_bloc_HN_total_hora_4 +
              tabla_bloc_HN_total_dia_5 +
              tabla_bloc_HN_sueldos_6 +
              tabla_bloc_HN_sabatical_7 +
              tabla_bloc_HN_pago_parcial_8 +
              tabla_bloc_HN_descuent_9 +
              tabla_bloc_HN_pago_total_10 +
            '</tr>';      
    
      var tabla_bloc_HE_total_hora_3 = `<td class="text-center"> <span  class="total_HE_${value.idtrabajador_por_proyecto}">${horas_extr_total}</span> </td>`;
    
      var tabla_bloc_HE_pago_parcial_4 =`<td class="text-center"><span  class="pago_parcial_HE_${value.idtrabajador_por_proyecto}"> ${(parseFloat(value.sueldo_hora) * parseFloat(horas_extr_total)).toFixed(2)}</span> </td>`;
    
      var tabla_bloc_HE_1 = '<tr>'+
            '<td>H/E</td>'+
            tabla_bloc_HE_asistencia_2 +
            tabla_bloc_HE_total_hora_3 +
            tabla_bloc_HE_pago_parcial_4 +      	       
          '</tr>';

      //Unimos y mostramos los bloques separados
      $(".data_table_body").append(tabla_bloc_HN_1 + tabla_bloc_HE_1);

    }); // end foreach

    var tabla_bloc_TOTAL_1 = '';

    if (cant_dias_asistencia == 14) {

      tabla_bloc_TOTAL_1 = `<tr> <td class="text-center" colspan="24"></td> <td class="text-center"> <b>TOTAL</b> </td> <td class="text-center"><span  class="pago_total_quincenal"> ${formato_miles(total_pago.toFixed(2))}</span> </td> </tr>`;
      
    } else { 

      if (cant_dias_asistencia == 7) {

        tabla_bloc_TOTAL_1 = `<tr> <td class="text-center" colspan="17"></td> <td class="text-center"> <b>TOTAL</b> </td> <td class="text-center"><span  class="pago_total_quincenal"> ${formato_miles(total_pago.toFixed(2))}</span> </td> </tr>`;
        
      } else {

        tabla_bloc_TOTAL_1 = `<tr> <td class="text-center" colspan="24"></td> <td class="text-center"> <b>TOTAL</b> </td> <td class="text-center"><span  class="pago_total_quincenal"> ${formato_miles(total_pago.toFixed(2))}</span> </td> </tr>`;
        
      }
    }

    $(".data_table_body").append(tabla_bloc_TOTAL_1);

  }); //end post - ver_datos_quincena

  $("#cargando-1-fomulario").show();
  $("#cargando-2-fomulario").hide();
  $('[data-toggle="tooltip"]').tooltip();  

  count_dias_asistidos = 0;  horas_nomr_total = 0;   horas_extr_total = 0;
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

//Función para desactivar registros
function justificar(idasistencia,horas, estado) {

  if (estado == "0") {

    Swal.fire("Activa este registro!", "Para usar esta opcion, active este registro.", "info");

  } else {

    if (horas >= 8) {

      Swal.fire("No puedes Justificar!", "Este trabajador tiene 8 horas completas, las justificación es para compensar horas perdidas.", "info");
    
    } else {
      $("#modal-justificar-asistencia").modal("show")
    }
  } 
}

// ver_asistencias
function ver_asistencias_individual(idtrabajador_por_proyecto,fecha_inicio_proyect) {

  console.log(idtrabajador_por_proyecto,fecha_inicio_proyect);
  
  mostrar_form_table(3);

  tabla2=$('#tabla-detalle-asistencia-individual').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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

init();

// voy a eliminar esta funcion cuando no lo NECESITE -----------------------
function convertir_a_hora(hora_n) {

  var convertido; var suma; var min; var hora; console.log('h:' + hora_n );
      
  var recortado_suma = hora_n.split('.').pop();

  min = Math.round((parseFloat(recortado_suma)*60)/100);
  
  if (hora_n >=10) {

    hora = hora_n.substr(0,2)

  } else {

    hora = '0'+hora_n.substr(0,1)

  }

  if (min >= 10) {

    convertido = hora + ':' + min;

  } else {

    convertido = hora + ':0' + min;

  }    
  
  return convertido;
}

//Función para desactivar registros
function desactivar(idasistencia_trabajador) {
  $(".tooltip").hide();
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
    
        tabla.ajax.reload(); tabla2.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar(idasistencia_trabajador) {
  $(".tooltip").hide();
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

        tabla.ajax.reload(); tabla2.ajax.reload();
      });
      
    }
  });      
}

// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {

  let splits = fecha.split("-"); //console.log(splits);

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}

// convierte de una fecha(aa-mm-dd): 23-12-2021 a una fecha(dd-mm-aa): 2021-12-23
function format_a_m_d(fecha) {

  let splits = fecha.split("-"); //console.log(splits);

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}

function modal_adicional_descuento( id_adicional, id_trabjador, fecha_q_s, descripcion) {

  $("#idsumas_adicionales").val(id_adicional);
  $("#idtrabajador_por_proyecto").val(id_trabjador);
  $("#fecha_q_s").val(fecha_q_s);
  $("#detalle_adicional").val(descripcion);

  $("#modal-adicional-descuento").modal("show");
}


// function edit(element) {
  
//   var hora_Acual = $("#id2").text()
//   $("#h_exra2").show();
//   $("#h_exra1").hide();
//    console.log( hora_Acual );
// }

// $(document).click(function(){
//   $("#h_exra2").blur(function(){
//     $(this).css("background-color", "#FFFFCC");
//   });
//   // $("#h_exra2").hide();
//   // $("#h_exra1").show();
//   console.log("click en cual quier parte");
//   // alert("has pulsado en botón");

//   // // si lo deseamos podemos eliminar el evento click
//   // // una vez utilizado por primera vez
//   // $(document).unbind("click");
// })

function extraer_dia_semana(fecha) {
  const fechaComoCadena = fecha; // día fecha
  const dias = ['lu', 'ma', 'mi', 'ju', 'vi', 'sa', 'do']; //
  const numeroDia = new Date(fechaComoCadena).getDay();
  const nombreDia = dias[numeroDia];
  return nombreDia;
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

// Calculamos las: Horas normal/extras,	Días asistidos,	Sueldo Mensual,	Jornal,	Sueldo hora,	Sabatical,	Pago parcial,	Adicional/descuento,	Pago quincenal
function calcular_he(fecha, span_class_he, input_class_hn, id_trabajador, cant_dias_asistencia, sueldo_hora, cant_trabajador) {

  //console.log(span_class_he, input_class_hn);

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
  for (let index = 1; index < parseInt(cant_dias_asistencia); index++) {

    // console.log( $(`.input_HN_${id_trabajador}_${index}`).val());    console.log( $(`.input_HE_${id_trabajador}_${index}`).val());

    if (parseFloat($(`.input_HN_${id_trabajador}_${index}`).val()) >= 0 ) {

      suma_hn = suma_hn + parseFloat($(`.input_HN_${id_trabajador}_${index}`).val());

      dias_asistidos++;
    }

    if (parseFloat($(`.input_HE_${id_trabajador}_${index}`).val()) >= 0 ) {

      suma_he = suma_he + parseFloat($(`.input_HE_${id_trabajador}_${index}`).val());
    }

  }

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
  var pago_quincenal = ( (parseFloat((suma_hn * parseFloat(sueldo_hora)).toFixed(2)) + parseFloat((suma_he * parseFloat(sueldo_hora)).toFixed(2))) + adicional_descuento ).toFixed(2)

  $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(pago_quincenal));

  var suma_total_quincena = 0;

  for (let k = 1; k < parseInt(cant_trabajador); k++) {    
    // console.log($(`.val_pago_quincenal_${k}`).text()); 
    suma_total_quincena = suma_total_quincena + parseFloat($(`.val_pago_quincenal_${k}`).text()); 
  }

  // console.log(suma_total_quincena);

  $(`.pago_total_quincenal`).html(formato_miles(suma_total_quincena.toFixed(2)));
}

function adicional_descuento(cant_trabajador, id_trabajador) {

  var suma_resta = 0; var pago_parcial_HN = 0; pago_parcial_HE = 0;

  //console.log($(`.pago_quincenal_${id_trabajador}`).text());   console.log($(`.adicional_descuento_${id_trabajador}`).val());

  // capturamos los pgos parciales
  pago_parcial_HN = parseFloat( $(`.pago_parcial_HN_${id_trabajador}`).text()); pago_parcial_HE = parseFloat( $(`.pago_parcial_HE_${id_trabajador}`).text());

  if (parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) >= 0 || parseFloat($(`.adicional_descuento_${id_trabajador}`).val()) <= 0 ) {

    suma_resta = (pago_parcial_HN + pago_parcial_HE) + parseFloat($(`.adicional_descuento_${id_trabajador}`).val());

    $(`.pago_quincenal_${id_trabajador}`).html(formato_miles(suma_resta.toFixed(2)));

    var suma_total_quincena = 0;

    // acumulamos todos los pagos quicenales
    for (let k = 1; k < parseInt(cant_trabajador); k++) {    
      // console.log($(`.val_pago_quincenal_${k}`).text()); 
      suma_total_quincena = suma_total_quincena + parseFloat($(`.val_pago_quincenal_${k}`).text()); 
    }

    $(`.pago_total_quincenal`).html(formato_miles(suma_total_quincena.toFixed(2)));

  } else {

    toastr.error(`El dato de adicional/descuento: <h3 class=""> ${$(`.adicional_descuento_${id_trabajador}`).val()} </h3> no es NUMÉRICO, ingrese un numero cero o un positivo o un negativo.`);    
  }  
}

function editar_fechas_asistencia(){

  // ocultamos los span
  $(".span_asist").hide();
  // mostramos los inputs
  $(".input_asist").show();

  $("#card-editar").hide();
  $("#card-guardar").show();
}

function guardar_fechas_asistencia() {

  // abrimos el modal cargando
  $("#modal-cargando").modal("show");

  var array_datos_asistencia = []; var array_extras = [];

  // rellenamos el array ASISTENCIA para la bd "ASISTENCIA TRABAJADOR"
  array_asistencia.forEach((element,index) => {

    if ( parseFloat($(`.${element.class_input_hn}`).val()) >= 0) {

      var input_asistencia = { 
        'id_trabajador':element.id_trabajador, 
        'fecha_asistida':format_a_m_d(element.fecha_asistida),
        'horas_normal_dia':$(`.${element.class_input_hn}`).val(),
        'pago_normal_dia':(parseFloat($(`.${element.class_input_hn}`).val()) * element.sueldo_hora).toFixed(2) ,
        'horas_extras_dia':$(`.${element.class_input_he}`).val(),
        'pago_horas_extras':(parseFloat($(`.${element.class_input_he}`).val()) * element.sueldo_hora).toFixed(2)
      }

      array_datos_asistencia.push( input_asistencia );
    }    
  }); 

  // rellenamos el array EXTRAS para la bd "SUMAS ADICIONALES"
  array_trabajador.forEach((element,index) => {

    var data_array_extras = {
      'id_trabajador':element.id_trabajador,
      'fecha_q_s':format_a_m_d(f1_r),
      'total_hn':$(`.total_HN_${element.id_trabajador}`).text(),
      'total_he':$(`.total_HE_${element.id_trabajador}`).text(),
      'dias_asistidos':$(`.dias_asistidos_${element.id_trabajador}`).text(),
      'sabatical':$(`.sabatical_${element.id_trabajador}`).text(),
      'pago_parcial_hn':$(`.pago_parcial_HN_${element.id_trabajador}`).text(),
      'pago_parcial_he':$(`.pago_parcial_HE_${element.id_trabajador}`).text(),
      'adicional_descuento':$(`.adicional_descuento_${element.id_trabajador}`).val(),
      'pago_quincenal':$(`.pago_quincenal_${element.id_trabajador}`).text()
    }
    array_extras.push( data_array_extras );
  }); 

  // console.log(array_trabajador);
  console.log(array_extras);
  console.log(array_datos_asistencia);
  // mostramos los span
  $(".span_asist").show();
  // ocultamos los inputs
  $(".input_asist").hide();

  $("#card-editar").show();
  $("#card-guardar").hide();


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

        datos_quincena(f1_r, f2_r, i_r, cant_dias_asistencia_r);
        
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

/**formato_miles */
function formato_miles(num) {
  if (!num || num == "NaN") return "-";
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

function cerrar_modal() {
  $("#modal-cargando").modal("hide");
  $(".progress-bar").removeClass("bg-success bg-danger");
  $(".progress-bar").addClass("progress-bar-striped");
}

function calcular_sabatical(fecha, span_class_he, input_class_hn, id_trabajador, cant_dias_asistencia, sueldo_hora, cant_trabajador, checkbox_input_class) {

  if ($(`#${checkbox_input_class}`).is(':checked')) {

    $(`#${input_class_hn}`).val('8'); console.log("tienen 8");
  } else {
    $(`#${input_class_hn}`).val('0'); console.log("tienen 0");
  }
  //calcular_he(fecha, span_class_he, input_class_hn, id_trabajador, cant_dias_asistencia, sueldo_hora, cant_trabajador);

}