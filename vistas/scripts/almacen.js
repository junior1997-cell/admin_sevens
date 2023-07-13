
var array_doc = [];

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");
  $("#mTecnico").addClass("active");
  $("#lAlmacen").addClass("active bg-primary");

  var idproyecto =  localStorage.getItem("nube_idproyecto");
  listar_botones_q_s(idproyecto);

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#producto").select2({theme: "bootstrap4", placeholder: "Selecione producto", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();

}

function limpiar_form_almacen() {
  
}

function listar_botones_q_s(nube_idproyecto) {

  $('#lista_quincenas').html('<div class="my-3" ><i class="fas fa-spinner fa-pulse fa-2x"></i>&nbsp;&nbsp;&nbsp;Cargando...</div>');

  //Listar quincenas(botones)
  $.post("../ajax/asistencia_obrero.php?op=listar_s_q_botones", { nube_idproyecto: nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); console.log(e);
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
           
          var htm_btn = '';          
          e.data.btn_asistencia.forEach((val, key) => {
            htm_btn = htm_btn.concat(` <button type="button" id="boton-${(key+1)}" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="por_fecha('${val.ids_q_asistencia}', '${format_d_m_a(val.fecha_q_s_inicio)}', '${format_d_m_a(val.fecha_q_s_fin)}', '${(key+1)}', ${q_s_dias});"><i class="far fa-calendar-alt"></i> ${q_s_btn} ${val.numero_q_s}<br>${format_d_m_a(val.fecha_q_s_inicio)} // ${format_d_m_a(val.fecha_q_s_fin)}</button>`);             
          });   

          $('#lista_quincenas').html(`<button type="button" id="boton-0" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="todos_almacen();"><i class="far fa-calendar-alt"></i><br> Todos</button> 
          ${htm_btn}`);  
          todos_almacen();   
        }
      }        
    } else {
      ver_errores(e);
    }
    
    //console.log(fecha);
  }).fail( function(e) { ver_errores(e); } );
}

function todos_almacen() {
  $('.data_tbody_almacen').html('');
  pintar_boton_selecionado(0);
  var idproyecto =  localStorage.getItem("nube_idproyecto");
  var fip =  localStorage.getItem("nube_fecha_inicial_actividad");
  var ffp =  localStorage.getItem("nube_fecha_final_actividad");
  var fpo =  localStorage.getItem("nube_fecha_pago_obrero");

  $('#div_tabla_almacen').hide();
  $('#cargando-table-almacen').show().html(`<div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div>`);
  
  $.post("../ajax/almacen.php?op=tabla_almacen", { 'id_proyecto': idproyecto, 'fip': fip, 'ffp':ffp, 'fpo': fpo }, function (e, status) {

    e = JSON.parse(e); console.log(e);

    if (e.status == true) {
      var codigoHTMLbody="", codigoHTMLbodyProducto ='', codigoHTMLbodyDias="", html_dias = ''; 
      var codigoHTMLhead1="", codigoHTMLhead2="", codigoHTMLhead3="", codigoHTMLhead4="" ;
      var fpo =  localStorage.getItem("nube_fecha_pago_obrero"), nombre_sq = '', cant_sq = ''; 

      e.data.fechas.forEach((val1, key1) => {
        codigoHTMLhead1 = codigoHTMLhead1.concat(`<th colspan="${val1.cantidad_dias}">${extraer_nombre_mes(val1.mes)}</th>`);
        val1.dia.forEach((val2, key2) => {
          codigoHTMLhead2 = codigoHTMLhead2.concat(`<th class="style-head">${extraer_dia_mes(val2)}</th>`);
          codigoHTMLhead4 = codigoHTMLhead4.concat(`<th class="style-head">${extraer_dia_semana(val2)}</th>`);          
        });        
      });
      e.data.data_sq.forEach((val1, key1) => {
        codigoHTMLhead3 = codigoHTMLhead3.concat(`<th colspan="${val1.colspan}">${val1.nombre_sq} ${val1.num_sq}</th>`);
      });

      // Validamos la quincena o semana
      // if (fpo == 'semanal') { nombre_sq = 'Semana'; cant_sq = 7 ;
      // } else if (fpo == 'quincenal') { nombre_sq = 'Quincena'; cant_sq = 14 ; }

      // var cant_dias = e.data.cant_dias, sumando = e.data.num_dia_regular, estado = true, count_sq = 1, colspan = e.data.num_dia_regular;

      // while (estado == true) {        
      //   if ( sumando <= cant_dias ) {    
      //     codigoHTMLhead3 = codigoHTMLhead3.concat(`<th colspan="${colspan}">${nombre_sq} ${count_sq}</th>`);
      //   } else {   
      //     codigoHTMLhead3 = codigoHTMLhead3.concat(`<th colspan="${(cant_dias - (sumando - cant_sq) )}">${nombre_sq} ${count_sq}</th>`);           
      //     estado = false;
      //   }
      //   count_sq += 1;  colspan = cant_sq;
      //   if (cant_sq == 7) { sumando += 7;  } else if (cant_sq == 14) { sumando += 14; }            
      // }      

      $('.thead-f1').html(`<th rowspan="4">#</th> <th rowspan="4">Code</th> <th rowspan="4">Producto</th>
      <th rowspan="4">UND</th> <th rowspan="4">SALDO <br> ANTERIOR</th> ${codigoHTMLhead1}
      <th rowspan="4">INGRESO /<br> SALIDA</th> <th rowspan="4">SALDO</th>`);

      $('.thead-f2').html(`${codigoHTMLhead2}`);
      $('.thead-f3').html(`${codigoHTMLhead3}`);
      $('.thead-f4').html(`${codigoHTMLhead4}`);

      e.data.producto.forEach((val1, key1) => {
        
        val1.almacen.forEach((val2, key2) => {
          html_dias = html_dias.concat(`<td>1</td>`);
        });

        codigoHTMLbodyProducto = `
        <tr>
          <td rowspan="2">${(key1 +1)}</td>
          <td rowspan="2">${val1.idproducto}</td>
          <td class="text_producto" rowspan="2">${val1.nombre_producto} <br> <small><b>Clasf:</b> ${val1.clasificacion} </small></td>
          <td rowspan="2">${val1.abreviacion}</td>
          <td rowspan="2">${formato_miles(val1.cantidad)}</td>
          ${html_dias}
          <td rowspan="2">100</td>
          <td rowspan="2">200</td>
        </tr>`;

        $('.data_tbody_almacen').append(`${codigoHTMLbodyProducto} <tr>${html_dias} </tr>`); // Agregar el contenido
        html_dias ='';
      });
       

      $('#div_tabla_almacen').show();
      $('#cargando-table-almacen').hide();
      scroll_tabla_asistencia();
    } else {
      ver_errores(e);
    }
  }).fail(function (e) { ver_errores(e); });
  
}

function por_fecha(ids_q_asistencia, fecha_q_s_inicio, fecha_q_s_fin, i, q_s_dias ) {
  $('.data_tbody_almacen').html('');
  pintar_boton_selecionado(i);
  var idproyecto =  localStorage.getItem("nube_idproyecto");
  var fip =  fecha_q_s_inicio
  var ffp =  fecha_q_s_fin
  var fpo =  localStorage.getItem("nube_fecha_pago_obrero");

  $('#div_tabla_almacen').hide();

  $('#cargando-table-almacen').show().html(`<div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div>`);
  
  $.post("../ajax/almacen.php?op=tabla_almacen", { 'id_proyecto': idproyecto, 'fip': fip, 'ffp':ffp, 'fpo': fpo }, function (e, status) {

    e = JSON.parse(e); console.log(e);

    if (e.status == true) {
      var codigoHTMLbody="", codigoHTMLbodyProducto ='', codigoHTMLbodyDias="", html_dias = ''; 
      var codigoHTMLhead1="", codigoHTMLhead2="", codigoHTMLhead3="", codigoHTMLhead4="" ;
      

      e.data.fechas.forEach((val1, key1) => {
        codigoHTMLhead1 = codigoHTMLhead1.concat(`<th colspan="${val1.cantidad_dias}">${extraer_nombre_mes(val1.mes)}</th>`);
        val1.dia.forEach((val2, key2) => {
          codigoHTMLhead2 = codigoHTMLhead2.concat(`<th class="style-head">${extraer_dia_mes(val2)}</th>`);
          codigoHTMLhead4 = codigoHTMLhead4.concat(`<th class="style-head">${extraer_dia_semana(val2)}</th>`);          
        });        
      });
      e.data.data_sq.forEach((val1, key1) => {
        codigoHTMLhead3 = codigoHTMLhead3.concat(`<th colspan="${val1.colspan}">${val1.nombre_sq} ${val1.num_sq}</th>`);
      });

      // Validamos la quincena o semana
      // if (fpo == 'semanal') { nombre_sq = 'Semana'; cant_sq = 7 ;
      // } else if (fpo == 'quincenal') { nombre_sq = 'Quincena'; cant_sq = 14 ; }

      // var cant_dias = e.data.cant_dias, sumando = e.data.num_dia_regular, estado = true, count_sq = 1, colspan = e.data.num_dia_regular;

      // while (estado == true) {        
      //   if ( sumando <= cant_dias ) {    
      //     codigoHTMLhead3 = codigoHTMLhead3.concat(`<th colspan="${colspan}">${nombre_sq} ${count_sq}</th>`);
      //   } else {   
      //     codigoHTMLhead3 = codigoHTMLhead3.concat(`<th colspan="${(cant_dias - (sumando - cant_sq) )}">${nombre_sq} ${count_sq}</th>`);           
      //     estado = false;
      //   }
      //   count_sq += 1;  colspan = cant_sq;
      //   if (cant_sq == 7) { sumando += 7;  } else if (cant_sq == 14) { sumando += 14; }            
      // }      

      $('.thead-f1').html(`<th rowspan="4">#</th> <th rowspan="4">Code</th> <th rowspan="4">Producto</th>
      <th rowspan="4">UND</th> <th rowspan="4">SALDO <br> ANTERIOR</th> ${codigoHTMLhead1}
      <th rowspan="4">INGRESO /<br> SALIDA</th> <th rowspan="4">SALDO</th>`);

      $('.thead-f2').html(`${codigoHTMLhead2}`);
      $('.thead-f3').html(`${codigoHTMLhead3}`);
      $('.thead-f4').html(`${codigoHTMLhead4}`);

      e.data.producto.forEach((val1, key1) => {
        
        val1.almacen.forEach((val2, key2) => {
          html_dias = html_dias.concat(`<td>1</td>`);
        });

        codigoHTMLbodyProducto = `
        <tr>
          <td rowspan="2">${(key1 +1)}</td>
          <td rowspan="2">${val1.idproducto}</td>
          <td class="text_producto" rowspan="2">${val1.nombre_producto} <br> <small><b>Clasf:</b> ${val1.clasificacion} </small></td>
          <td rowspan="2">${val1.abreviacion}</td>
          <td rowspan="2">${formato_miles(val1.cantidad)}</td>
          ${html_dias}
          <td rowspan="2">100</td>
          <td rowspan="2">200</td>
        </tr>`;

        $('.data_tbody_almacen').append(`${codigoHTMLbodyProducto} <tr>${html_dias} </tr>`); // Agregar el contenido
        html_dias ='';
      });
       

      $('#div_tabla_almacen').show();
      $('#cargando-table-almacen').hide();
      scroll_tabla_asistencia();
    } else {
      ver_errores(e);
    }
  }).fail(function (e) { ver_errores(e); });
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..


// .....::::::::::::::::::::::::::::::::::::: O T R A S   F U N C I O N E S  :::::::::::::::::::::::::::::::::::::::..

function scroll_tabla_asistencia() {
  var height_tabla = $('.tabla_sistencia_obrero').height(); console.log('Alto pantalla: '+height_tabla);
  var width_tabla = $('.tabla_sistencia_obrero').width(); console.log('Ancho pantalla: '+width_tabla);
  if (height_tabla <= 600) {
    $('#div_tabla_almacen').css({'height':`${redondearExp((height_tabla+50),0)}px`});
  } else {
    var alto_real = (width_tabla/2) - 100;
    $('#div_tabla_almacen').css({'height':`${redondearExp(alto_real,0)}px`});
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