var tabla;
var tabla_pension;
var tabla_detalle_s;
var editando=false;
var editando2=false;
////////////////////////////
var array_class=[];

var array_datosPost=[];
var array_fi_ff=[];
var f1_reload=''; var f2_reload=''; var i_reload  = ''; var cont_reload  = '';
var total_semanas=0;
var array_guardar_fi_ff = [];

var fecha_inicial_1="";
var fecha_inicial_2="";
var id_pension="";
var i_inicial="";
var cont_inial="";

//Función que se ejecuta al inicio
function init() {  

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Viaticos").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mViatico").addClass("active bg-primary");

  $("#sub_bloc_comidas").addClass("menu-open bg-color-191f24");

  $("#sub_mComidas").addClass("active bg-primary");

  $("#lPension").addClass("active");

  $("#idproyecto_p").val(localStorage.getItem('nube_idproyecto'));

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar_botoness( localStorage.getItem('nube_idproyecto') );

  tbla_principal( localStorage.getItem('nube_idproyecto')); 

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#proveedor', null);
    
  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_pension").on("click", function (e) {$("#submit-form-pension").submit();});
  $("#guardar_registro_comprobaante").on("click", function (e) {$("#submit-form-comprobante").submit();});

  
  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  //Initialize Select2 Elements
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione tipo comprobante", allowClear: true, });
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Selecione una forma de pago", allowClear: true, });  
  $("#proveedor").select2({ theme: "bootstrap4", placeholder: "Seleccionar", allowClear: true, });
  $("#servicio_p").select2();

  // Bloquemos las fechas has hoy
  no_select_tomorrow('#fecha_emision');

  // Formato para telefono
  $("[data-mask]").inputmask();  
}

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
    $("#nomb_pension_head").html("");
    $("#mostrar-tabla").show();
    $("#guardar_pension").show();

    $("#tabla-registro").hide();
    $("#List_smnas_pen").hide();

    $("#card-regresar").hide();
    $("#card-editar").hide();
    $("#card-guardar").hide();

  } else {
    if (estados == 2) {
      $("#card-registrar").hide();
      $("#card-regresar").show();
      $("#card-editar").show();

      $("#List_smnas_pen").show();

      $("#guardar_pension").hide();
      $("#mostrar-tabla").hide();
      $("#tabla-registro").show();
      
    } else {
      $("#card-registrar").hide();
      $("#card-regresar").show();
      $("#card-editar").hide();
      $("#card-guardar").hide();
      $("#tabla-asistencia-trab").hide();
      $("#tabla-registro").hide();
      $("#detalle_asistencia").show();
      $("#tabla-comprobantes").hide();      
    }
  }
}

//Función Listar
function listar_botoness( nube_idproyecto ) {
  var estado_fecha_1 = true;
  //array_fi_ff=[];
  //Listar semanas(botones)
  $.post("../ajax/pension.php?op=listar_semana_botones", { nube_idproyecto: nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); //console.log(data);

    // validamos la existencia de DATOS
    if (e.data) {

      var dia_regular = 0; var weekday_regular = extraer_dia_semana(e.data.fecha_inicio); var estado_regular = false;

      if (weekday_regular == "Do") { dia_regular = -1; } else { if (weekday_regular == "Lu") { dia_regular = -2; } else { if (weekday_regular == "Ma") { dia_regular = -3; } else { if (weekday_regular == "Mi") { dia_regular = -4; } else { if (weekday_regular == "Ju") { dia_regular = -5; } else { if (weekday_regular == "Vi") { dia_regular = -6; } else { if (weekday_regular == "Sa") { dia_regular = -7; } } } } } } }
      // console.log(e.data.fecha_inicio, dia_regular, weekday_regular);

          $('#List_smnas_pen').html('');

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
            array_fi_ff.push({'fecha_in':format_a_m_d(fecha_i),'fecha_fi':format_a_m_d(fecha_f), 'num_semana':cont });
            //array_data_fi_ff.push()

            //asignamos los datos del primer boton
            if (estado_fecha_1) { fecha_inicial_1=fecha_i; fecha_inicial_2=fecha_f;  i_inicial=i;  cont_inial=cont; estado_fecha_1=false;}

            $('#List_smnas_pen').append(` <button id="boton-${i}" type="button" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="datos_semana('${fecha_i}', '${fecha_f}', '${i}', '${cont}');"><i class="far fa-calendar-alt"></i> Semana ${cont}<br>${fecha_i} // ${fecha_f}</button>`)
            
            if (val_fecha_f.getTime() >= val_fecha_proyecto.getTime()) { cal_mes = true; }else{ cal_mes = false;}

            fecha = sumaFecha(1,fecha_f);

            i++;

          } 
        
    } else {
      $('#List_smnas_pen').html(`<div class="info-box shadow-lg w-600px"> 
        <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span> 
        <div class="info-box-content"> 
          <span class="info-box-text">Alerta</span> 
          <span class="info-box-number">No has definido los bloques de fechas del proyecto. <br>Ingresa al ESCRITORIO y EDITA tu proyecto selecionado.</span> 
        </div> 
      </div>`);
    }
    //console.log(array_fi_ff);
  }).fail( function(e) { ver_errores(e); } );
}
//funcion para ingresar la fecha para rellenar los días de las pensiones
function ingresar_a_pension(idpension,idproyecto,razon_social) {
  $("#nomb_pension_head").html(razon_social);
  id_pension=idpension;
  mostrar_form_table(2);
  
  datos_semana(fecha_inicial_1, fecha_inicial_2, i_inicial, cont_inial, id_pen=id_pension)
}
//Función para guardar o editar

function guardaryeditar_semana_pension() {
  $("#modal-cargando").modal("show");
  var array_detalle_pen= [];
  var array_semana_pen= [];
  array_class.forEach(element => {
    var precio_x_comida =parseFloat($(`.input_precio_${element.idservicio_pension}`).val())*parseFloat($(`.input_dia_${element.idservicio_pension}_${element.fecha_asist}`).val());
    //value.adicional_descuento=='' ? adicional_descuento='0.00' : adicional_descuento=value.adicional_descuento;
    array_detalle_pen.push(
      {
        "iddetalle_pension" :element.iddetalle_pension,
        "idservicio_pension" :element.idservicio_pension,
        "fecha_pension":element.fecha_asist,
        "dia_semana":extraer_dia_semana(element.fecha_asist),
        "cantidad_platos":$(`.input_dia_${element.idservicio_pension}_${element.fecha_asist}`).val(),
        "precio_plato":$(`.input_precio_${element.idservicio_pension}`).val()=='' ?'0.00' : $(`.input_precio_${element.idservicio_pension}`).val(),
        "precio_parcial":precio_x_comida.toFixed(2)=='' ?'0.00' : precio_x_comida.toFixed(2),
      }
    );
  });
  
  array_servicio.forEach(element => {
   
    array_semana_pen.push({
      "idsemana_pension":element.idsemana_pension,
      "idservicio_pension":element.idservicio_pension,
      "fecha_inicio":format_a_m_d(f1_reload),
      "fecha_fin":format_a_m_d(f2_reload),
      "numero_semana":cont_reload,
      "precio_comida":$(`.input_precio_${element.idservicio_pension}`).val()=='' ?'0.00' : $(`.input_precio_${element.idservicio_pension}`).val(),
      "cantidad_total_platos":$(`.span_cantidad_${element.idservicio_pension}`).text(),
      "adicional_descuento":$(`.input_adicional_${element.idservicio_pension}`).val()=='' ?'0.00' : $(`.input_adicional_${element.idservicio_pension}`).val(),
      "total":$(`.span_parcial_${element.idservicio_pension}`).text()=='' ?'0.00' : quitar_formato_miles($(`.span_parcial_${element.idservicio_pension}`).text()),
      "presupuesto":$(`.input_presupuesto_${element.idservicio_pension}`).val()=='' ?'0.00' : $(`.input_presupuesto_${element.idservicio_pension}`).val(),
      "descripcion":$(`.textarea_descrip_${element.idservicio_pension}`).val(),

    });
  });

  $.ajax({
    url: "../ajax/pension.php?op=guardaryeditar",
    type: "POST",
    data: {
      'array_detalle_pen': JSON.stringify(array_detalle_pen),
      'array_semana_pen': JSON.stringify(array_semana_pen),
    },
    // contentType: false,
    // processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  

        if (e.status == true) {

          datos_semana( f1_reload, f2_reload ,cont_reload, i_reload,id_pen=id_pension);
         
          tbla_principal( localStorage.getItem('nube_idproyecto'));
          
          $("#icono-respuesta").html(`<div class="swal2-icon swal2-success swal2-icon-show" style="display: flex;"> <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div> <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span> <div class="swal2-success-ring"></div> <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div> <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div> </div>  <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Correcto!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">Asistencia registrada correctamente</div> </div>` );
  
          // Swal.fire("Correcto!", "Asistencia registrada correctamente", "success");
          
         $(".progress-bar").addClass("bg-success"); $("#barra_progress").text("100% Completado!");
              
        }else{
  
              $("#icono-respuesta").html(`<div class="swal2-icon swal2-error swal2-icon-show" style="display: flex;"> <span class="swal2-x-mark"> <span class="swal2-x-mark-line-left"></span> <span class="swal2-x-mark-line-right"></span> </span> </div> <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Error!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">${datos}</div> </div>`);
  
              $(".progress-bar").addClass("bg-danger"); $("#barra_progress").text("100% Error!");
  
          // Swal.fire("Error!", datos, "error");
        }
      } catch (err) {
        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
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

function l_m(){ $(".progress-bar").removeClass("progress-bar-striped")}

function cerrar_modal() {
  $("#modal-cargando").modal("hide");
  $(".progress-bar").removeClass("bg-success bg-danger");
  $(".progress-bar").addClass("progress-bar-striped");
}

// .....:::::::::::::::::::::::::::::::::::::  S E M A N A   P E N S I O N  :::::::::::::::::::::::::::::::::::::::..
function datos_semana(f1, f2, i, cont,id_pen=id_pension) {

  f1_reload=f1;  f2_reload=f2;  i_reload  = i;  cont_reload  = cont;

  // ocultamos las tablas
  mostrar_form_table(2);
  $("#tabla-registro").hide();
  $('#cargando-registro-pension').show();

  $("#card-editar").show(); $("#card-guardar").hide();  

  // vaciamos el array
  array_class = []; array_servicio = []

  // pintamos el botón
  pintar_boton_selecionado(i);

  var nube_idproyect =localStorage.getItem('nube_idproyecto');  //console.log('Quicena: '+f1 + ' al ' +f2 + ' proyect-id: '+nube_idproyect);
  
  var fecha_inicial_semana = f1; var table_numero_semana = ""; var table_dia_semana = ""; 

  var dia_regular = 0; var total_pago = 0;

  var weekday_regular = extraer_dia_semana(format_a_m_d(fecha_inicial_semana));
  
  // asignamos un numero para restar y llegar al dia DOMIGO
  if (weekday_regular == "Do") { dia_regular = -0; } else { if (weekday_regular == "Lu") { dia_regular = -1; } else { if (weekday_regular == "Ma") { dia_regular = -2; } else { if (weekday_regular == "Mi") { dia_regular = -3; } else { if (weekday_regular == "Ju") { dia_regular = -4; } else { if (weekday_regular == "Vi") { dia_regular = -5; } else { if (weekday_regular == "Sa") { dia_regular = -6; } } } } } } }

  var fecha_inicial_semana_regular = sumaFecha(dia_regular, fecha_inicial_semana);

  // Asignamos: dia semana, numero semana. Para regular la semana
  for ( var j = 1; j<=dia_regular*-1; j++ ) {

    var weekday = extraer_dia_semana(format_a_m_d(fecha_inicial_semana_regular));  
    //<th class="text-center clas_pading"> ${weekday} <br> ${fecha_inicial_semana_regular.substr(0,2)} </th>
    table_dia_semana = table_dia_semana.concat(`<th class="text-center clas_pading bg-color-408c98"> ${weekday} <br> ${fecha_inicial_semana_regular.substr(0,2)} </th>`);

    // table_numero_semana = table_numero_semana.concat(`<th class="p-x-10px bg-color-acc3c7"> ${count_dias_de_asistencias} </th>`);

    // aumentamos mas un dia hasta llegar al dia "dia_regular"
    fecha_inicial_semana_regular = sumaFecha(1,fecha_inicial_semana_regular); //console.log(count_dias_de_asistencias);

    //count_dias_de_asistencias++;
  }

  // asignamos: dia semana, numero semana. Con respecto al trabajo
  for (i = 1; i <=7 + dia_regular; i++) { 

    var weekday = extraer_dia_semana(format_a_m_d(fecha_inicial_semana));  

    if (weekday != 'sa') {
      //`
      table_dia_semana = table_dia_semana.concat(`<th class="text-center clas_pading "> ${weekday} <br> ${fecha_inicial_semana.substr(0,2)} </th>`);

      // table_numero_semana = table_numero_semana.concat(`<th class="p-x-10px"> ${count_dias_de_asistencias} </th>`);

    } else {

      table_dia_semana = table_dia_semana.concat(`<th class="text-center clas_pading"> ${weekday} <br> ${fecha_inicial_semana.substr(0,2)} </th>`);
      
      // table_numero_semana = table_numero_semana.concat(`<td class="p-x-10px bg-color-acc3c7"> ${count_dias_de_asistencias} </td>`);
    }

    // aumentamos mas un dia hasta llegar al dia 15
    fecha_inicial_semana = sumaFecha(1,fecha_inicial_semana); //console.log(count_dias_de_asistencias);
    //count_dias_de_asistencias++
  } //end for

  $('#bloque_fechas').html(table_dia_semana);

  // $('.data-numero-semana').html(table_numero_semana
  var total_monto_x_semana=0;
  $.post("../ajax/pension.php?op=ver_datos_semana", {f1:format_a_m_d(f1),f2:format_a_m_d(f2),nube_idproyect:nube_idproyect,id_pen:id_pen}, function (data, status) {
        
    data =JSON.parse(data); console.log(data);   
    $("#data_table_body").html('');   
     
    $.each(data, function (index, value) {
      if (value.total!='') {

        total_monto_x_semana+=parseFloat(value.total);

      } else {

        total_monto_x_semana='0.00';
      }
      
      count_bloque_q_s = 1;
      var count_dias_asistidos = 0; var platos_x_servicio = 0; var horas_nomr_total = 0; var horas_extr_total = 0; var sabatical = 0;
      
      var tabla_bloc_dia_3=""; var tabla_bloc_HE_asistencia_2 =""; var estado_hallando_sabado = true;

      // existe algun dia_q_comieron -------
      if (value.dias_q_comieron.length != 0) {

        var i;  var fecha = f1; //console.log("tiene data");
        
        // renellamos hasta el dia inicial
        for ( var j = 1; j<=dia_regular*-1; j++ ) {
          
          tabla_bloc_dia_3 = tabla_bloc_dia_3.concat(`<td class="text-center bg-color-acc3c7"> <span class="span_asist" >-</span> </td>`);
          
          //console.log(count_bloque_q_s);
          count_bloque_q_s++; 
        }

        for (i = 1; i <=7+dia_regular; i++) {
          //console.log('i');
          var estado_fecha = false; var fecha_asist = "";  var platos_x_dia=0; var iddetalle_pension='';

          // buscamos las fechas asistidas
          for (let i = 0; i < value.dias_q_comieron.length; i++) {
            
            
            let split_f = format_d_m_a( value.dias_q_comieron[i]['fecha_pension'] ) ; 
             
            let fecha_semana = new Date( format_a_m_d(fecha) ); let fecha_pension = new Date(format_a_m_d(split_f));
             
            if ( fecha_semana.getTime() == fecha_pension.getTime() ) { 

              platos_x_servicio = platos_x_servicio + parseFloat(value.dias_q_comieron[i]['cantidad_platos']);

              estado_fecha = true; fecha_asist = value.dias_q_comieron[i]['fecha_pension'];  
              
              platos_x_dia = value.dias_q_comieron[i]['cantidad_platos'];

              iddetalle_pension = value.dias_q_comieron[i]['iddetalle_pension'];

              count_dias_asistidos++;                          
            }
          } //end for

          // imprimimos la fecha de asistencia: "encontrada" 
          if (estado_fecha) {

            var weekday = extraer_dia_semana(fecha_asist); //console.log(weekday);

              tabla_bloc_dia_3 = tabla_bloc_dia_3.concat(`<td> <span class="text-center span-visible">${platos_x_dia}</span> <input type="number" value="${platos_x_dia}" class="hidden input-visible w-45px input_dia_${value.idservicio_pension}_${i} input_dia_${value.idservicio_pension}_${fecha_asist}" onchange="calcular_platos(${value.idservicio_pension},'${fecha_asist}',${data.length})" onkeyup="calcular_platos(${value.idservicio_pension},'${fecha_asist}',${data.length})"> </td>`);
              
              array_class.push( { 
                'idservicio_pension':value.idservicio_pension, 
                'iddetalle_pension':iddetalle_pension,
                'fecha_asist':fecha_asist,

              } );

          } else { // imprimimos la fecha de asistencia: "No encontrada"

            var weekday = extraer_dia_semana(format_a_m_d(fecha)); //console.log(weekday);

            tabla_bloc_dia_3 = tabla_bloc_dia_3.concat(`<td> <span class="text-center span-visible">-</span> <input type="number" value="" class="hidden input-visible w-45px input_dia_${value.idservicio_pension}_${i} input_dia_${value.idservicio_pension}_${format_a_m_d(fecha)}" onchange="calcular_platos(${value.idservicio_pension},'${format_a_m_d(fecha)}',${data.length})" onkeyup="calcular_platos(${value.idservicio_pension},'${format_a_m_d(fecha)}',${data.length})"> </td>`);

              array_class.push( { 
                'idservicio_pension':value.idservicio_pension, 
                'iddetalle_pension':'',
                'fecha_asist':format_a_m_d(fecha),

              } );

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

          tabla_bloc_dia_3 = tabla_bloc_dia_3.concat(`<td class="text-center bg-color-acc3c7"> <span class="span_asist" >-</span> </td>`);
          
          //.log(count_bloque_q_s);
          count_bloque_q_s++; 
        }

        for (i = 1; i <=7+dia_regular; i++) { 

          var weekday = extraer_dia_semana(format_a_m_d(fecha));

          tabla_bloc_dia_3 = tabla_bloc_dia_3.concat(`<td> <span class="text-center span-visible">-</span> <input type="number" value="" class="hidden input-visible w-45px input_dia_${value.idservicio_pension}_${i} input_dia_${value.idservicio_pension}_${format_a_m_d(fecha)}" onchange="calcular_platos(${value.idservicio_pension},'${format_a_m_d(fecha)}',${data.length})" onkeyup="calcular_platos(${value.idservicio_pension},'${format_a_m_d(fecha)}',${data.length})"> </td>`);
           
            array_class.push( { 
              'idservicio_pension':value.idservicio_pension, 
              'iddetalle_pension':'',
              'fecha_asist':format_a_m_d(fecha),

            } );

            // aumentamos mas un dia hasta llegar al dia 15
          fecha = sumaFecha(1,fecha);

          // console.log(count_bloque_q_s);
          count_bloque_q_s++; 
        } //end for
        // console.log('-----------------------------------------------------------');
      }
      

      // asignamos lo trabajadores a un "array"
      array_servicio.push( {
        'idservicio_pension':value.idservicio_pension, 
        'idsemana_pension':value.idsemana_pension

      } );
    
      var adicional_descuento=0;  var presupuesto_pension = 0;
      value.adicional_descuento=='' ? adicional_descuento='0.00' : adicional_descuento=value.adicional_descuento;
      value.presupuesto=='' || value.presupuesto==null ? presupuesto_pension='0.00' : presupuesto_pension=value.presupuesto;
      
      var total=0;
      value.total=='' ? total='0.00' : total=value.total;
      var definir_precio_actual=0;
      if (value.precio_t_semana_p=="") {
        definir_precio_actual=value.precio_t_servicio_p;
      }else{
        definir_precio_actual=value.precio_t_semana_p;
      }

      
      var tabla_bloc_descrip_comida_1 =`<td><b>${value.nombre_servicio}</b></td>`;
      var tabla_bloc_precio_2 =`<td><span class="text-center span-visible" >s/ <b>${ parseFloat(definir_precio_actual).toFixed(2)}</b></span> <input type="number" value="${parseFloat(definir_precio_actual).toFixed(2)}" onchange="calcular_precios(${value.idservicio_pension},${data.length})" onkeyup="calcular_precios(${value.idservicio_pension},${data.length})" class="hidden input-visible w-70px input_precio_${value.idservicio_pension}"></td>`;

     // var tabla_bloc_dia_3 =`<td> <span class="text-center span-visible">6</span> <input type="number" class="hidden input-visible w-px-30" > </td>`;
      var tabla_bloc_cantidad_4 =`<td class="text-center"> <span class="span_cantidad_${value.idservicio_pension}">${value.cantidad_total_platos}</span> </td>`;
      var tabla_bloc_adicional_5=`<td> <span class="span-visible">${parseFloat(adicional_descuento).toFixed(2)}</span> <input type="number" value="${parseFloat(adicional_descuento).toFixed(2)}" onchange="calcular_adicional(${value.idservicio_pension},${data.length})" onkeyup="calcular_adicional(${value.idservicio_pension},${data.length})" class="hidden input-visible w-70px input_adicional_${value.idservicio_pension}"> </td>`;
      var tabla_bloc_parcial_6 =`<td> <span class="span_parcial_${value.idservicio_pension} calcular_total_parcial_${index+1}">${formato_miles(parseFloat(total).toFixed(2))}</span></td>`;
      var tabla_bloc_presupuesto_7 =`<td> <span class="span-visible">${parseFloat(presupuesto_pension).toFixed(2)}</span> <input type="number" value="${parseFloat(presupuesto_pension).toFixed(2)}" class="hidden input-visible w-70px input_presupuesto_${value.idservicio_pension}"> </td>`;
      var tabla_bloc_descripcion_8 =`<td><textarea  class="text-center textarea-visible textarea_descrip_${value.idservicio_pension} h-auto" cols="30" rows="1" style="width: 400px;" readonly >${value.descripcion}</textarea></td>`;

      var tabla_bloc_HN_1 = `<tr>
              ${tabla_bloc_descrip_comida_1} 
              ${tabla_bloc_precio_2} 
              ${tabla_bloc_dia_3} 
              ${tabla_bloc_cantidad_4}
              ${tabla_bloc_adicional_5} 
              ${tabla_bloc_parcial_6}
              ${tabla_bloc_presupuesto_7} 
              ${tabla_bloc_descripcion_8} 
            </tr>`;      
    
      //Unimos y mostramos los bloques separados
      $("#data_table_body").append(tabla_bloc_HN_1);

    }); // end foreach
    $("#parcial_total_x_semana").html(formato_miles(total_monto_x_semana));

    $("#tabla-registro").show();
    $('#cargando-registro-pension').hide();
    
  }).fail( function(e) { ver_errores(e); } ); //end post - ver_datos_semana


  $('[data-toggle="tooltip"]').tooltip();  

  count_dias_asistidos = 0;  horas_nomr_total = 0;   horas_extr_total = 0;
}

// Calculamos las: Horas normal/extras,	Días asistidos,	Sueldo Mensual,	Jornal,	Sueldo hora,	Sabatical,	Pago parcial,	Adicional/descuento,	Pago quincenal
function calcular_platos(idservicio_pension,fecha_asist,can_servicios) {

  //variables
  var platos_x_servicio = 0; var parcial_x_servicio=0; var total_parcial=0; var adicional_descuento=0; var precio=0;

  // calcular pago quincenal
  for (let index = 1; index <= 7; index++) {
    // console.log( $(`.input_HN_${id_trabajador}_${index}`).val());    console.log( $(`.input_HE_${id_trabajador}_${index}`).val());
    if (parseFloat($(`.input_dia_${idservicio_pension}_${index}`).val()) > 0 ) {
      platos_x_servicio = platos_x_servicio + parseFloat($(`.input_dia_${idservicio_pension}_${index}`).val());
    }
  }

  // validamos el adicional descuento 
  if (parseFloat($(`.input_adicional_${idservicio_pension}`).val()) >= 0 || parseFloat($(`.input_adicional_${idservicio_pension}`).val()) <= 0 ) {
    adicional_descuento =   parseFloat($(`.input_adicional_${idservicio_pension}`).val()); 
  } else {
    adicional_descuento = 0;
    toastr.error(`El dato adicional:: <h3 class=""> ${$(`.input_adicional_${idservicio_pension}`).val()} </h3> no es NUMÉRICO, ingrese un número cero o un positivo o un negativo.`);    
  }

  //capturamos el precio
  if (parseFloat($(`.input_precio_${idservicio_pension}`).val()) >= 0) {
    precio =   parseFloat($(`.input_precio_${idservicio_pension}`).val());
  }

  parcial_x_servicio= (precio*platos_x_servicio)+adicional_descuento; 

  //  platos_x_servicio
  $(`.span_cantidad_${idservicio_pension}`).html(platos_x_servicio);

  $(`.span_parcial_${idservicio_pension}`).html(formato_miles(parcial_x_servicio.toFixed(2))); 

  for (let k = 1; k <= parseInt(can_servicios); k++) {    
    //console.log($(`.val_pago_quincenal_${k}`).text(), k); 
    total_parcial = total_parcial + parseFloat(quitar_formato_miles($(`.calcular_total_parcial_${k}`).text())); 
  }

  // console.log(suma_total_quincena);

  $(`#parcial_total_x_semana`).html(formato_miles(total_parcial.toFixed(2)));
}

function calcular_adicional(idservicio_pension,can_servicios) {

  var parcial_x_servicio = 0; var reg_precio_actual = 0; can_platos = 0; var total_parcial=0;

  // capturamos precio actual y cantidad de platos
  var reg_precio_actual  =  parseFloat( $(`.input_precio_${idservicio_pension}`).val());
  var can_platos =  parseFloat($(`.span_cantidad_${idservicio_pension}`).text());

  if (reg_precio_actual<0) {reg_precio_actual=0;}else{reg_precio_actual=parseFloat(reg_precio_actual);}

  if (parseFloat($(`.input_adicional_${idservicio_pension}`).val()) >= 0 || parseFloat($(`.input_adicional_${idservicio_pension}`).val()) <= 0 ) {

    parcial_x_servicio = (reg_precio_actual*can_platos) + parseFloat($(`.input_adicional_${idservicio_pension}`).val());

  } else {

    parcial_x_servicio = 0;

    toastr.error(`El dato adicional:: <h3 class=""> ${$(`.input_adicional_${idservicio_pension}`).val()} </h3> no es NUMÉRICO, ingrese un número cero o un positivo o un negativo.`);    
  }

  $(`.span_parcial_${idservicio_pension}`).html(parcial_x_servicio); 

  for (let k = 1; k <= parseInt(can_servicios); k++) {    
    //console.log($(`.val_pago_quincenal_${k}`).text(), k); 
    total_parcial = total_parcial + parseFloat(quitar_formato_miles($(`.calcular_total_parcial_${k}`).text()));
  }

  $(`#parcial_total_x_semana`).html(formato_miles(total_parcial.toFixed(2)));
}

function calcular_precios(idservicio_pension,can_servicios) {

 var adicional_descuento=0; var parcial_actual=0; var total_parcial=0;
 var reg_precio_actual  = $(`.input_precio_${idservicio_pension}`).val();
 var can_platos = $(`.span_cantidad_${idservicio_pension}`).text();

  if (reg_precio_actual<0) {reg_precio_actual=0;}else{reg_precio_actual=parseFloat(reg_precio_actual);}

  if (parseFloat($(`.input_adicional_${idservicio_pension}`).val()) >= 0 || parseFloat($(`.input_adicional_${idservicio_pension}`).val()) <= 0 ) {

    adicional_descuento =   parseFloat($(`.input_adicional_${idservicio_pension}`).val());
  } else {
    adicional_descuento = 0;
    toastr.error(`El dato adicional:: <h3 class=""> ${$(`.input_adicional_${idservicio_pension}`).val()} </h3> no es NUMÉRICO, ingrese un número cero o un positivo o un negativo.`);    
  }

  parcial_actual= (reg_precio_actual*can_platos)+adicional_descuento;

  $(`.span_parcial_${idservicio_pension}`).html(parcial_actual); 

  for (let k = 1; k <= parseInt(can_servicios); k++) {    
    //console.log($(`.val_pago_quincenal_${k}`).text(), k); 
    total_parcial = total_parcial + parseFloat(quitar_formato_miles($(`.calcular_total_parcial_${k}`).text())); 
  }
  $(`#parcial_total_x_semana`).html(formato_miles(total_parcial.toFixed(2)));
}
// .....:::::::::::::::::::::::::::::::::::::  P E N S I O N  :::::::::::::::::::::::::::::::::::::::..
function limpiar_pension() {

  $("#idpension").val("");
  $("#p_desayuno").val("");
  $("#p_almuerzo").val("");
  $("#p_cena").val("");
  $("#descripcion_pension").val("");
  $("#proveedor").val("null").trigger("change"); 
  $("#servicio_p").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();

}

//Guardar y editar
function guardaryeditar_pension(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-agregar-pension")[0]);
 
  $.ajax({
    url: "../ajax/pension.php?op=guardaryeditar_pension",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e); 
        if (e.status == true) {
          toastr.success('servicio registrado correctamente');  
          tabla.ajax.reload(null, false);  
          $("#modal-agregar-pension").modal("hide");  
         limpiar_pension();
        }else{  
          ver_errores(e);
        } 
      } catch (err) {
        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      }     
      $("#guardar_registro_pension").html('Guardar Cambios').removeClass('disabled');         
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_pension").css({"width": percentComplete+'%'});
          $("#barra_progress_pension").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_pension").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_pension").css({ width: "0%",  });
      $("#barra_progress_pension").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_pension").css({ width: "0%", });
      $("#barra_progress_pension").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

//Función Listar
function tbla_principal(nube_idproyecto) {

  var sumatotal=0; var totalsaldo=0; 

  tabla=$('#tabla-resumen-break-semanal').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,6,7], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,6,7], } }, { extend: 'pdfHtml5', footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,4,6,7], } }, {extend: "colvis"} ,
    ],
    ajax:{
      url: '../ajax/pension.php?op=tabla_principal&nube_idproyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center text-nowrap');  }
      if (data[1] != '') { $("td", row).eq(1).addClass('text-center text-nowrap'); }
      if (data[3]!="") { $("td", row).eq(4).addClass('text-right');  sumatotal += parseFloat(data[4]);  } else { sumatotal +=0; }
      if (data[5]) { $("td", row).eq(5).addClass('text-nowrap'); $("td", row).eq(6).addClass('text-nowrap');  }
      if (data[7]!="") {$("td", row).eq(7).addClass('text-right');}
      //console.log(data);
      if (quitar_formato_miles(data[7]) > 0) {
        $("td", row).eq(7).css({ "background-color": "#ffc107", color: "black", });          
      } else if (quitar_formato_miles(data[7]) == 0) {
        $("td", row).eq(7).css({ "background-color": "#28a745", color: "white",  });
      } else {
        $("td", row).eq(7).css({ "background-color": "#ff5252", color: "white", });          
      }
      if (data[7]!="") {  var saldo=quitar_formato_miles(data[7]); }
      totalsaldo += parseFloat(saldo);
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: {
        copyTitle: "Tabla Copiada",
        copySuccess: {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();

  $.post("../ajax/pension.php?op=total_pension", { idproyecto: nube_idproyecto }, function (e, status) {
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {
      $("#total_pension").html(formato_miles(e.data.total));
      $("#total_deposito").html(formato_miles(e.data.total_deposito));
      if (es_numero(e.data.total) && es_numero(e.data.total_deposito) ) {
        var saldo = e.data.total - e.data.total_deposito;
        $("#total_saldo").html(`${formato_miles(saldo)}`);
      } else {
        $("#total_saldo").html(`0`);
      }
      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );  
}

//Función ver detalles Detalles
function ver_detalle_x_servicio(idpension) {

  $("#modal-ver-detalle-semana").modal("show");
  tabla_detalle_s=$('#tabla-detalles-semanal').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'pdf', ],
    ajax:{
      url: '../ajax/pension.php?op=ver_detalle_x_servicio&idpension='+idpension,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: {
        copyTitle: "Tabla Copiada",
        copySuccess: {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//mostrar
function mostrar_pension(idpension) {
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_pension();
  var array_datosselect=[];
  $("#modal-agregar-pension").modal("show");

  $.post("../ajax/pension.php?op=mostrar_pension", { idpension: idpension }, function (e, status) {

    e = JSON.parse(e); console.log(e);   

    $("#proveedor").val(e.idproveedor).trigger("change"); 
    $("#idproyecto_p").val(e.idproyecto);
    $("#idpension").val(e.idpension);
    $("#descripcion_pension").val(e.descripcion);

    e.servicio_pension['data'].forEach( (value, item )=> {

      console.log(value.precio, value.nombre_servicio);
      if (value.nombre_servicio=="Desayuno") {
        $("#p_desayuno").val(value.precio);
        array_datosselect.push(value.nombre_servicio);
      }

      if (value.nombre_servicio=="Almuerzo") {  
        $("#p_almuerzo").val(value.precio);
        array_datosselect.push(value.nombre_servicio);
      }

      if (value.nombre_servicio=="Cena") {     
        $("#p_cena").val(value.precio);
        array_datosselect.push(value.nombre_servicio);
      }

    });

    $("#servicio_p").val(array_datosselect).trigger("change");

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

  }).fail( function(e) { ver_errores(e); } );
}

// .....:::::::::::::::::::::::::::::::::::::  C O M P R O B A N T E    P E N S I O N  :::::::::::::::::::::::::::::::::::::::..
function ocultar() {

  $("#regresar_aprincipal").show();
  $("#Lista_breaks").hide();
  $("#mostrar-tabla").hide();
  $("#tabla-registro").hide();
  $("#tabla-comprobantes").show();
  $("#guardar").show();
  $("#guardar_pension").hide();

}

function regresar() {

  $("#regresar_aprincipal").hide();
  $("#Lista_breaks").show();
  $("#mostrar-tabla").show();
  $("#tabla-registro").hide();
  $("#tabla-comprobantes").hide();
  $("#guardar").hide();
  $("#guardar_pension").show();
}


//Función limpiar-factura
function limpiar_comprobante() {
  //idpension_f,idfactura_pension
  $("#nro_comprobante").val("");
  $("#idfactura_pension").val("");
  $("#fecha_emision").val("");
  $("#descripcion").val("");

  $("#subtotal").val("");

  $("#igv").val("");

  $("#monto").val("");

  $("#val_igv").val(""); 

  $("#tipo_gravada").val("");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

    // Limpiamos las validaciones
    $(".form-control").removeClass('is-valid');
    $(".is-invalid").removeClass("error is-invalid");

}

//Guardar y editar
function guardaryeditar_factura(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-agregar-comprobante")[0]);
 
  $.ajax({
    url: "../ajax/pension.php?op=guardaryeditar_Comprobante",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);                       
        if (e.status ==true) {
          toastr.success('servicio registrado correctamente');
          tabla.ajax.reload(null, false);
          $("#modal-agregar-comprobante").modal("hide");
          listar_comprobantes(localStorage.getItem('idpension_f_nube'));
          total_monto(localStorage.getItem('idpension_f_nube'));
          tbla_principal( localStorage.getItem('nube_idproyecto'));
          limpiar_comprobante();
        }else{
          ver_errores(e);
        }        
      } catch (err) {
        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      }
      $("#guardar_registro_comprobaante").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_comprobante_pension").css({"width": percentComplete+'%'});
          $("#barra_progress_comprobante_pension").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_comprobaante").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_comprobante_pension").css({ width: "0%",  });
      $("#barra_progress_comprobante_pension").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_comprobante_pension").css({ width: "0%", });
      $("#barra_progress_comprobante_pension").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function listar_comprobantes(idpension) {
  localStorage.setItem('idpension_f_nube',idpension);

  ocultar();
  $("#idpension_f").val(idpension);
  
  tabla=$('#t-comprobantes').dataTable({  
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    ajax:{
      url: '../ajax/pension.php?op=listar_comprobantes&idpension='+idpension,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);		ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
      // columna: sub total
      if (data[5] != '') { $("td", row).eq(5).addClass('text-nowrap text-right'); }
      // columna: igv
      if (data[6] != '') { $("td", row).eq(6).addClass('text-nowrap text-right'); }
      // columna: total
      if (data[7] != '') { $("td", row).eq(7).addClass('text-nowrap text-right'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: {
        copyTitle: "Tabla Copiada",
        copySuccess: {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
    ],
  }).DataTable();
  total_monto(localStorage.getItem('idpension_f_nube'));
}

function comprob_factura() {

  var monto = parseFloat($('#monto').val());

  if ($("#tipo_comprobante").select2("val")=="" || $("#tipo_comprobante").select2("val")==null) {
    $("#subtotal").val("");
    $("#igv").val(""); 
    $("#val_igv").val("0"); 
    $("#tipo_gravada").val("NO GRAVADA"); 
    $("#val_igv").prop("readonly",true);
  }else{
    if ($("#tipo_comprobante").select2("val") =="Factura") {
      $("#tipo_gravada").val("GRAVADA");
      calculandototales_fact();
    } else {
      if ($("#tipo_comprobante").select2("val")!="Factura") {
        $("#subtotal").val(monto.toFixed(2));
        $("#igv").val("0.00");
        $("#val_igv").val("0"); 
        $("#tipo_gravada").val("NO GRAVADA"); 
        $("#val_igv").prop("readonly",true);
      } else {
        $("#subtotal").val('0.00');
        $("#igv").val("0.00");
        $("#val_igv").val("0"); 
        $("#tipo_gravada").val("NO GRAVADA"); 
        $("#val_igv").prop("readonly",true);
      }
    }
  }  
}

function validando_igv() {
  if ($("#tipo_comprobante").select2("val") == "Factura") {
    $("#val_igv").prop("readonly",false);
    $("#val_igv").val(0.18); 
  }else {
    $("#val_igv").val(0); 
  }  
}

function calculandototales_fact() {

  var precio_parcial =  $("#monto").val();

  var val_igv = $('#val_igv').val();

  if (precio_parcial == null || precio_parcial == "") {
    $("#subtotal").val(0);
    $("#igv").val(0); 
  } else {
 
    var subtotal = 0;
    var igv = 0;

    if (val_igv == null || val_igv == "") {
      $("#subtotal").val(parseFloat(precio_parcial));
      $("#igv").val(0);
    }else{
      $("subtotal").val("");
      $("#igv").val("");

      subtotal = quitar_igv_del_precio(precio_parcial, val_igv, 'decimal');
      igv = precio_parcial - subtotal;

      $("#subtotal").val(parseFloat(subtotal).toFixed(2));
      $("#igv").val(parseFloat(igv).toFixed(2));
    }
  }
}

function quitar_igv_del_precio(precio , igv, tipo ) {
  console.log(precio , igv, tipo);
  var precio_sin_igv = 0;

  switch (tipo) {
    case 'decimal':
      if (parseFloat(precio) != NaN && igv > 0 && igv <= 1 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( ( parseFloat(igv) * 100 ) + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;

    case 'entero':
      if (parseFloat(precio) != NaN && igv > 0 && igv <= 100 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( parseFloat(igv)  + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;
  
    default:
      $(".val_igv").html('IGV (0%)');
      toastr.success('No has difinido un tipo de calculo de IGV.')
    break;
  } 
  
  return precio_sin_igv; 
}

//mostrar
function mostrar_comprobante(idfactura_pension ) {

  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();

  limpiar_comprobante();
  $("#modal-agregar-comprobante").modal("show");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/pension.php?op=mostrar_comprobante", { idfactura_pension : idfactura_pension  }, function (e, status) {

    e = JSON.parse(e);  //console.log(data); 

    if (e.status) {
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
      $("#idfactura_pension  ").val(e.data.idfactura_pension);
      $("#nro_comprobante").val(e.data.nro_comprobante);
      $("#monto").val(parseFloat(e.data.monto).toFixed(2));
      $("#fecha_emision").val(e.data.fecha_emision);
      $("#descripcion").val(e.data.descripcion);
      $("#subtotal").val(parseFloat(e.data.subtotal).toFixed(2));
      $("#igv").val(parseFloat(e.data.igv).toFixed(2));
      $("#val_igv").val(e.data.val_igv); 
      $("#tipo_gravada").val(e.data.tipo_gravada);
      $("#forma_pago").val(e.data.forma_de_pago).trigger("change");

      if (e.data.comprobante == "" || e.data.comprobante == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#doc1_nombre").html('');

        $("#doc_old_1").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_1").val(e.data.comprobante); 

        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante,'pension', 'comprobante', '100%', '210' ));       
             
      }
      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();

    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_comprobante(idfactura_pension, nombre) {

  crud_eliminar_papelera(
    "../ajax/pension.php?op=desactivar_comprobante",
    "../ajax/pension.php?op=eliminar_comprobante", 
    idfactura_pension, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ total_monto(localStorage.getItem('idpension_f_nube')); tabla.ajax.reload(null, false); },
    function(){ tbla_principal( localStorage.getItem('nube_idproyecto')); },
    false, 
    false,
    false
  );

}

function activar_comprobante(idfactura_pension ) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  comprobante?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/pension.php?op=activar_comprobante", { idfactura_pension : idfactura_pension  }, function (e) {

        Swal.fire("Activado!", "Comprobante ha sido activado.", "success");
        total_monto(localStorage.getItem('idpension_f_nube'));
        tabla.ajax.reload(null, false);
        tbla_principal(localStorage.getItem('nube_idproyecto'));
      }).fail( function(e) { ver_errores(e); } );      
    }
  });  
}

function ver_modal_comprobante(comprobante){
  var comprobante = comprobante;
  var extencion = comprobante.substr(comprobante.length - 3); // => "1"
  //console.log(extencion);
  $('#ver_fact_pdf').html('');
  $('#img-factura').attr("src", "");
  $('#modal-ver-comprobante').modal("show");

  if (extencion=='jpeg' || extencion=='jpg' || extencion=='png' || extencion=='webp') {
    $('#ver_fact_pdf').hide();
    $('#img-factura').show();
    $('#img-factura').attr("src", "../dist/docs/pension/comprobante/" +comprobante);
    $("#iddescargar").attr("href","../dist/docs/pension/comprobante/" +comprobante);
  }else{
    $('#img-factura').hide();
    $('#ver_fact_pdf').show();
    $('#ver_fact_pdf').html('<iframe src="../dist/docs/pension/comprobante/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');
    $("#iddescargar").attr("href","../dist/docs/pension/comprobante/" +comprobante);
  } 
 $(".tooltip").removeClass("show").addClass("hidde");
}

//-total Pagos
function total_monto(idpension) {
  $("#monto_total_f").html("00.0");
  $.post("../ajax/pension.php?op=total_monto", { idpension:idpension }, function (e, status) {    
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      $("#monto_total_f").html('S/ '+ formato_miles(e.data.total));
    } else {
      ver_errores(e);
    } 
  }).fail( function(e) { ver_errores(e); } );
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#proveedor").on("change", function () { $(this).trigger("blur"); });
  $("#servicio_p").on("change", function () { $(this).trigger("blur"); });

  // Aplicando la validacion del select cada vez que cambie
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });

  $("#form-agregar-pension").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      proveedor:{required: true},
      'servicio_p[]':{required: true}
    },
    messages: {
      proveedor: {
        required: "Campo requerido", 
      },
      'servicio_p[]': {
        required: "Campo requerido", 
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
    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardaryeditar_pension(e);
    }

  });

  $("#form-agregar-comprobante").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      forma_pago:     {required: true},
      tipo_comprobante:{required: true},
      monto:          {required: true},
      fecha_emision:  {required: true},
      descripcion:    {minlength: 1},
      foto2_i:        {required: true},
      val_igv:        { required: true, number: true, min:0, max:1 },
    },
    messages: {
      forma_pago:     { required: "Seleccionar una forma de pago", },
      tipo_comprobante:{ required: "Seleccionar un tipo de comprobante", },
      monto:          { required: "Por favor ingresar el monto", },
      fecha_emision:  { required: "Por favor ingresar la fecha de emisión", },
      val_igv:        { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
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
      guardaryeditar_factura(e);      
    }
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#proveedor").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#servicio_p").rules("add", { required: true, messages: { required: "Campo requerido" } });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


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

//despintar_btn_select
function despintar_btn_select() {  
  if (localStorage.getItem('boton_id')) { let id = localStorage.getItem('boton_id'); $("#boton-" + id).removeClass('click-boton'); }
}

function editarbreak() {
  // ocultamos los span
  $(".span-visible").hide();
  // mostramos los inputs
  $(".input-visible").show();
  $(".textarea-visible").attr("readonly", false);

  $("#card-editar").hide();
  $("#card-guardar").show();  
}

