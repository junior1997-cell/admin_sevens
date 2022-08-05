var tabla_principal; var tabla_ingreso_pagos;

var tabla_recibo_por_honorario;

var id_tabajador_x_proyecto_r = "", nombre_trabajador_r = "", fecha_inicial_r = "", fecha_hoy_r = "", fecha_final_r = "", sueldo_mensual_r = "", cuenta_bancaria_r = "", cant_dias_trabajando_r = "";

//Función que se ejecuta al inicio
function init() {

  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#bloc_PagosTrabajador").addClass("menu-open bg-color-191f24");

  $("#mContableFinanciero").addClass("active");

  $("#mPagosTrabajador").addClass("active bg-primary");

  $("#lPagosAdministrador").addClass("active");
  
  sumas_totales_tabla_principal(localStorage.getItem('nube_idproyecto'));

  // efectuamos SUBMIT  registro de: PAGOS POR MES
  $("#guardar_registro").on("click", function (e) { $("#submit-form-pagos-x-mes").submit(); });

  // efectuamos SUBMIT  registro de: RECIBOS POR HONORARIOS
  $("#guardar_registro_recibo-x-honorario").on("click", function (e) { $("#submit-form-recibo-x-honorario").submit(); });

  //Initialize Select2 unidad
  $("#forma_pago").select2({theme: "bootstrap4", placeholder: "Seleccinar una forma de pago", allowClear: true, });
   
  no_select_tomorrow('#fecha_pago');

  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e,$("#doc2").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

// Eliminamos el doc 2
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

function table_show_hide(flag) {
  if (flag == 1) {
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();
    $("#btn-agregar").hide(); 
    $("#btn-nombre-mes").hide();

    $(".nombre-trabajador").html("Pagos de Administradores");

    $("#tbl-principal").show();
    $("#tbl-fechas").hide();
    $("#tbl-ingreso-pagos").hide();
  } else {
    if (flag == 2) {
      $("#btn-regresar").show();
      $("#btn-regresar-todo").hide();
      $("#btn-regresar-bloque").hide();
      $("#btn-agregar").hide();
      $("#btn-nombre-mes").hide();

      $("#tbl-principal").hide();
      $("#tbl-fechas").show();
      $("#tbl-ingreso-pagos").hide();
    }else{
      if (flag == 3) {
        $("#btn-regresar").hide();
        $("#btn-regresar-todo").show();
        $("#btn-regresar-bloque").show();
        $("#btn-agregar").show();
        $("#btn-nombre-mes").show();

        $("#tbl-principal").hide();
        $("#tbl-fechas").hide();
        $("#tbl-ingreso-pagos").show();
        
      }
    }
  }
}
// ══════════════════════════════════════ PRINCIPAL ══════════════════════════════════════
function sumas_totales_tabla_principal(id_proyecto) {

  $('.sueldo_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.deposito_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  if (tabla_principal) {
    tabla_principal.destroy(); // Destruye las tablas de datos en el contexto actual.
    $('#tbody-tabla-principal').empty(); // Vacía en caso de que las columnas cambien
  }

  $.post("../ajax/pago_administrador.php?op=mostrar_total_tbla_principal", { 'nube_idproyecto': id_proyecto }, function (data, status) {
    data = JSON.parse(data);  console.log(data); 
    $('.sueldo_total_tbla_principal').html(`<span>S/</span> <b>${formato_miles(data.sueldo_mesual_x_proyecto)}</b>`);
    $('.deposito_total_tbla_principal').html(`<span>S/</span> <b>${formato_miles(data.monto_total_depositado_x_proyecto)}</b>`);

    listar_tbla_principal(id_proyecto);    
  }); 
}

//Función Listar - tabla principal
function listar_tbla_principal(nube_idproyecto) {  

  var total_pago_acumulado_hoy = 0, pago_total_x_proyecto = 0, saldo_total = 0;

  tabla_principal=$('#tabla-principal').dataTable({
    //"responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,13,14,15,16,17,2,3,4,5,6,7,8,18,10,11,12], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,13,14,15,16,17,2,3,4,5,6,7,8,18,10,11,12], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,13,14,15,16,17,2,3,4,5,6,7,8,18,10,11,12], } } ,            
    ],
    ajax:{
      url: '../ajax/pago_administrador.php?op=listar_tbla_principal&nube_idproyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') {  $("td", row).eq(0).css('text-center'); }  
      // columna: sueldo mensual
      if (data[5] != '') { $("td", row).eq(5).addClass('text-center');  }
      // columna: sueldo mensual
      if (data[6] != '') { $("td", row).eq(6).addClass('text-right'); }
      // columna: pago total
      if (data[7] != '') {
        $("td", row).eq(7).addClass('text-right');
        // acumulamos el PAGO TOTAL
        var split = data[7].split(' '); //console.log(split);
        var quitar_format_mil = quitar_formato_miles( split[1]); //console.log(quitar_format_mil);
        pago_total_x_proyecto += parseFloat(quitar_format_mil);
        $('.pago_total_tbla_principal').html(`<span>S/</span> <b>${formato_miles(pago_total_x_proyecto)}</b>`);
      }

      // columna: pago acumuldo       
      if (data[8] != '') {
        $("td", row).eq(8).addClass('text-right');
        // acumulamos el PAGO acumulado hasta hoy
        var split = data[8].split(' '); //console.log(split);
        var quitar_format_mil = quitar_formato_miles( split[1]); //console.log(quitar_format_mil);
        total_pago_acumulado_hoy += parseFloat(quitar_format_mil);
        $('.pago_hoy_total_tbla_principal').html(`<span>S/</span> <b>${formato_miles(total_pago_acumulado_hoy)}</b>`);
      }

      // columna: saldo
      if (data[10] != '') {
        $("td", row).eq(10).addClass('text-right');
        // acumulamos el SALDO
        var split = data[10].split(' '); //console.log(split);
        var quitar_format_mil = quitar_formato_miles( split[1]); //console.log(quitar_format_mil);
        saldo_total += parseFloat(quitar_format_mil);
        $('.saldo_total_tbla_principal').html(`<span>S/</span> <b>${formato_miles(saldo_total)}</b>`);
      }

      // Validamos la comlumna: "Anterior pago"
      if (data[11] == "En espera...") {
        $("td", row).eq(11).css({ "background-color": "#ffffff00", "color": "black", });
      }else if (data[11] == "Terminó") {        
        // $("td", row).eq(5).addClass('bg-success bg-gradient').css({ "color": "white",  "font-size": "18px" });        
      } else {
        $("td", row).eq(11).css({ "background-color": "#28a745", "color": "white", });
      } 

      // validamos si el trbajdor temino sus dias de trabajo #6e00e77a
      if ( data[11] == "Terminó" && data[12] == "Terminó" ) {
        $("td", row).eq(0).css({ "background-color": "#58955a7a"});
        $("td", row).eq(1).css({ "background-color": "#58955a7a"});
        $("td", row).eq(2).css({ "background-color": "#58955a7a"});
        $("td", row).eq(3).css({ "background-color": "#58955a7a"});
        $("td", row).eq(4).css({ "background-color": "#58955a7a"});
        $("td", row).eq(5).css({ "background-color": "#58955a7a"});
        $("td", row).eq(6).css({ "background-color": "#58955a7a"});
        $("td", row).eq(7).css({ "background-color": "#58955a7a"});
        $("td", row).eq(8).css({ "background-color": "#58955a7a"});
        $("td", row).eq(9).css({ "background-color": "#58955a7a"});
        $("td", row).eq(10).css({ "background-color": "#58955a7a"});
        $("td", row).eq(11).css({ "background-color": "#58955a7a"});
        $("td", row).eq(12).css({ "background-color": "#58955a7a"});
      }

      // Validamos la comlumna: "Siguiente pago"
      if (data[12] == "En espera...") {
        $("td", row).eq(12).css({ "background-color": "#ffffff00", "color": "black",  });
      } else if (data[12] == "Terminó") {        
        // $("td", row).eq(6).addClass('bg-success bg-gradient').css({ "color": "white", "font-size": "18px" });        
      } else{
        $("td", row).eq(12).css({ "background-color": "#ffc107", "color": "black", });
      }
      
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
      //{ targets: [11,13], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      { targets: [13,14,15,16,17,18], visible: false, searchable: false, },    
    ],
  }).DataTable();

  

  
}

//Función Listar - tabla recibo por honorario
function listar_tbla_recibo_por_honorario(id_mes) {  
  $('#modal-tabla-recibo-por-honorario').modal('show');

  tabla_recibo_por_honorario=$('#tabla-recibo-por-honorario').dataTable({
    //"responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [ ],
    ajax:{
      url: '../ajax/pago_administrador.php?op=listar_tbla_recibo_por_honorario&id_mes='+id_mes,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') {  $("td", row).eq(0).css('text-center'); }  
      // columna: sueldo mensual
      if (data[5] != '') { $("td", row).eq(5).addClass('text-center');  }
      // columna: sueldo mensual
      if (data[6] != '') { $("td", row).eq(6).addClass('text-right'); }      
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
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      //{ targets: [13,14,15,16,17,18], visible: false, searchable: false, },    
    ],
  }).DataTable();
  
}

// ══════════════════════════════════════ TABLA MES ══════════════════════════════════════
//Función Listar - TABLA DETALLE MES
function detalle_fechas_mes_trabajador(id_tabajador_x_proyecto, nombre_trabajador, fecha_inicial, fecha_hoy, fecha_final, sueldo_mensual, cuenta_bancaria, cant_dias_trabajando) {

  id_tabajador_x_proyecto_r = id_tabajador_x_proyecto; nombre_trabajador_r = nombre_trabajador; fecha_inicial_r = fecha_inicial; fecha_hoy_r = fecha_hoy; 
  fecha_final_r = fecha_final; sueldo_mensual_r = sueldo_mensual; cuenta_bancaria_r = cuenta_bancaria; cant_dias_trabajando_r = cant_dias_trabajando;

  table_show_hide(2);

  var btn_disabled = '';

  // validamos si permitira ingresar: "pagos mensuales" o "recibos por honorarios"
  if (cant_dias_trabajando == "En espera...") { btn_disabled = 'disabled'; } else {  btn_disabled = ''; }

  var array_fechas_mes = []; var dias_mes = 0; var estado_fin_bucle = false;  
  
  var fecha_i = fecha_inicial; var fecha_f = fecha_final;

  var monto_total = 0;  var monto_total_pagado = 0; var dias_regular_total = 0; var deposito_total = 0; var saldo_total = 0; var rh_total = 0;

  $(".nombre-trabajador").html(`Pagos - <b> ${nombre_trabajador} </b>`);

  if (fecha_inicial == '- - -' || fecha_hoy == '- - -') {

    $('.data-fechas-mes').html(`<tr> <td colspan="8"> <div class="alert alert-warning alert-dismissible text-left"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h5><i class="icon fas fa-ban"></i> Alerta!</h5> Las fechas: <ul> <li> <b>Inicial</b></li> <li> <b>Final</b></li> </ul> No estan definidas corectamente, <b>EDITE las fechas</b> de trabajo de este trabajdor, para realizar sus pagos correctamente. </div> </td> </tr>`);
    $('.monto_x_mes_total').html('<i class="far fa-frown fa-2x text-danger"></i>');
    $('.monto_x_mes_pagado_total').html('<i class="far fa-frown fa-2x text-danger"></i>');

  } else {    

    var fecha_hoy_actual = moment().format('YYYY-MM-DD');

    // creamos un array con la fechas de PAGOS
    while (estado_fin_bucle == false) {

      var fecha_desglose =  fecha_i.split('-');
    
      dias_mes = cantidad_dias_mes(fecha_desglose[2], fecha_desglose[1]);

      var dias_regular = (parseFloat(dias_mes) - parseFloat((fecha_desglose[0])))+1;
      
      fecha_f = sumaFecha(dias_regular-1,fecha_i );

      if (valida_fecha_menor_que( format_a_m_d(fecha_f), format_a_m_d(fecha_final) ) == false) {
        fecha_f = fecha_final;
        dias_regular = parseFloat(fecha_final.substr(0,2));
        estado_fin_bucle = true;         
      }     

      array_fechas_mes.push({
        'fecha_i':fecha_i, 
        'dias_regular':dias_regular, 
        'fecha_f':fecha_f, 
        'mes_nombre':extraer_nombre_mes(format_a_m_d(fecha_i)),
        'dias_mes': dias_mes
      });

      fecha_i = sumaFecha(1,fecha_f );     
    }

    $('.data-fechas-mes').html('');

    $.post("../ajax/pago_administrador.php?op=mostrar_fechas_mes", { 'id_tabajador_x_proyecto': id_tabajador_x_proyecto }, function (data, status) {
    
      data = JSON.parse(data);   console.log(data);

      var cant_total_mes = 0; //console.log(array_fechas_mes);

      if (data.length === 0) {
        array_fechas_mes.forEach((element, indice) => {

          monto_total += (sueldo_mensual/element.dias_mes)*element.dias_regular;
  
          monto_x_mes = (sueldo_mensual/element.dias_mes)*element.dias_regular;
          
          dias_regular_total += element.dias_regular;

          deposito_total += 0; 
          saldo_total += parseFloat(monto_x_mes);

          var bg_siguiente_pago = "";  var bg_siguiente_pago_2 = '';

          if ( fecha_dentro_de_rango(fecha_hoy_actual, format_a_m_d(element.fecha_i), format_a_m_d(element.fecha_f)) ) {
            bg_siguiente_pago = "bg-success"; bg_siguiente_pago_2 = 'bg-color-28a74533';
          } else {
            bg_siguiente_pago = ""; bg_siguiente_pago_2 = '';
          }
  
          $('.data-fechas-mes').append(`<tr>
            <td class="text-center py-1 ${bg_siguiente_pago_2}">${indice + 1}</td>
            <td class="text-center py-1 ${bg_siguiente_pago_2}">${element.mes_nombre} </td>
            <td class="text-center py-1 ${bg_siguiente_pago_2}">${element.fecha_i}</td>
            <td class="text-center py-1 ${bg_siguiente_pago}" >${element.fecha_f}</td>
            <td class="text-center py-1 ${bg_siguiente_pago_2}">${element.dias_regular}/${element.dias_mes}</td>
            <td class="py-1 ${bg_siguiente_pago_2}"><div class="formato-numero-conta"> <span>S/</span>${formato_miles(sueldo_mensual)}</div></td>
            <td class="py-1 ${bg_siguiente_pago_2}"><div class="formato-numero-conta"> <span>S/</span>${formato_miles(monto_x_mes)}</div></td>
            <td class="py-1 ${bg_siguiente_pago_2}">
              <div class="formato-numero-conta">
                <button class="btn btn-danger btn-sm mr-1" ${btn_disabled} onclick="listar_tbla_pagos_x_mes('', '${id_tabajador_x_proyecto}', '${element.fecha_i}', '${element.fecha_f}', '${element.mes_nombre}', '${element.dias_mes}', '${element.dias_regular}', '${sueldo_mensual}', '${monto_x_mes}', '${nombre_trabajador}', '${cuenta_bancaria}','${monto_x_mes}' );"><i class="fas fa-dollar-sign"></i> Pagar</button>
                <button style="font-size: 14px;" class="btn btn-danger btn-sm">S/ 0.00</button>
              </div>
            </td>
            <td class="py-1 ${bg_siguiente_pago_2}"><div class="formato-numero-conta"> <span>S/</span>${formato_miles(monto_x_mes)}</div></td>
            <td class="text-center py-1 ${bg_siguiente_pago_2}"> 
              <button class="btn btn-outline-info btn-sm" ${btn_disabled} onclick="listar_tbla_recibo_por_honorario('');">
                <i class="fas fa-file-invoice fa-lg"></i>
              </button> 
            </td>
          </tr>`);
  
          cant_total_mes = indice + 1;        
        });
      } else {
        
        array_fechas_mes.forEach((element, indice) => {

          var monto_x_mes = 0; var saldo_x_mes = 0;

          var cant_dias_laborables_e = 0; var cant_dias_mes_e = 0; var estado_e = ""; var fecha_final_e = "";    
          var fecha_inicial_e = ""; var idfechas_mes_pagos_administrador_e = ""; var idtrabajador_por_proyecto_e = ""; 
          var monto_x_mes_e = 0; var nombre_mes_e = ""; var sueldo_mensual_e = 0; var numero_comprobante_e = ""; var recibos_x_honorarios_e = "";
           var btn_tipo = ""; var suma_monto_depositado_e = 0; var btn_tipo_deposito = ""; var bg_saldo = ""

          var fechas_mes_estado = false;

          // buscamos las fechas           
          data.forEach(value => {
            if (value.fecha_inicial == format_a_m_d(element.fecha_i) && value.fecha_final == format_a_m_d(element.fecha_f) ) {

              fechas_mes_estado = true;

              idfechas_mes_pagos_administrador_e = value.idfechas_mes_pagos_administrador; 
              idtrabajador_por_proyecto_e = value.idtrabajador_por_proyecto;
              fecha_inicial_e = value.fecha_inicial;
              fecha_final_e = value.fecha_final;
              nombre_mes_e = value.nombre_mes;
              cant_dias_mes_e = value.cant_dias_mes;
              cant_dias_laborables_e = value.cant_dias_laborables; 
              sueldo_mensual_e = value.sueldo_mensual;
              monto_x_mes_e = value.monto_x_mes;
              numero_comprobante_e = value.numero_comprobante;
              recibos_x_honorarios_e = value.recibos_x_honorarios;
              estado_e = value.estado;

              suma_monto_depositado_e = value.suma_monto_depositado

              // Validamos el tipo de boton para los "recibos por honorarios"
              if (value.cant_rh == '' || value.cant_rh == null || value.cant_rh == 0) { btn_tipo = 'btn-outline-info'; } else { btn_tipo = 'btn-info'; rh_total += value.cant_rh; }
            } 
            // console.log(`${nombre_mes_e} - fecha encontrada: ${fecha_inicial_e} == ${format_a_m_d(element.fecha_i)} ---- ${fecha_final_e} == ${format_a_m_d(element.fecha_f)}`);
          });
          
          // validamos si encontramos las fechas
          if (fechas_mes_estado) { 
            //console.log('entreee');
            monto_total += (parseFloat(sueldo_mensual_e)/parseFloat(cant_dias_mes_e))*parseInt(cant_dias_laborables_e);
            saldo_x_mes = parseFloat(monto_x_mes_e) - parseFloat(suma_monto_depositado_e);
            dias_regular_total += parseInt(cant_dias_laborables_e);

            deposito_total += parseFloat(suma_monto_depositado_e); 
            saldo_total += saldo_x_mes;

            if ( parseFloat(suma_monto_depositado_e) == 0 ) {
              btn_tipo_deposito = "btn-danger";
            } else {
              if ( parseFloat(suma_monto_depositado_e) > 0 &&  parseFloat(suma_monto_depositado_e) < parseFloat(monto_x_mes_e) ) {
                btn_tipo_deposito = "btn-warning";
              } else {
                if ( parseFloat(suma_monto_depositado_e) >= parseFloat(monto_x_mes_e) ) {
                  btn_tipo_deposito = "btn-success";
                }
              }              
            }

            if (saldo_x_mes < 0) {
              bg_saldo = "bg-red";
            } else {
              bg_saldo = "";
            }

            var bg_siguiente_pago = ""; var bg_siguiente_pago_2 = '';

            if ( fecha_dentro_de_rango(fecha_hoy_actual, fecha_inicial_e, fecha_final_e) ) {
              bg_siguiente_pago = "bg-success"; bg_siguiente_pago_2 = 'bg-color-28a74533';
            } else {
              bg_siguiente_pago = "";
            }
    
            $('.data-fechas-mes').append(`<tr>
              <td class="text-center py-1 ${bg_siguiente_pago_2}">${indice + 1}</td>
              <td class="text-center py-1 ${bg_siguiente_pago_2}">${nombre_mes_e} </td>
              <td class="text-center py-1 ${bg_siguiente_pago_2}">${format_d_m_a(fecha_inicial_e)}</td>
              <td class="text-center py-1 ${bg_siguiente_pago}" >${format_d_m_a(fecha_final_e)}</td>
              <td class="text-center py-1 ${bg_siguiente_pago_2}">${cant_dias_laborables_e}/${cant_dias_mes_e}</td>
              <td class="py-1 ${bg_siguiente_pago_2}"><div class="formato-numero-conta"> <span>S/</span>${formato_miles(sueldo_mensual_e)}</div></td>
              <td class="py-1 ${bg_siguiente_pago_2}"><div class="formato-numero-conta"> <span>S/</span>${formato_miles(monto_x_mes_e)}</div></td>
              <td class="py-1 ${bg_siguiente_pago_2}">
                <div class="formato-numero-conta">
                  <button class="btn ${btn_tipo_deposito} btn-sm mr-1" ${btn_disabled} onclick="listar_tbla_pagos_x_mes('${idfechas_mes_pagos_administrador_e}', '${idtrabajador_por_proyecto_e}', '${format_d_m_a(fecha_inicial_e)}', '${format_d_m_a(fecha_final_e)}', '${nombre_mes_e}', '${cant_dias_mes_e}', '${cant_dias_laborables_e}', '${sueldo_mensual_e}', '${monto_x_mes_e}', '${nombre_trabajador}', '${cuenta_bancaria}', '${saldo_x_mes}' );"><i class="fas fa-dollar-sign"></i> Pagar</button>
                  <button style="font-size: 14px;" class="btn ${btn_tipo_deposito} btn-sm">S/ ${formato_miles(suma_monto_depositado_e)}</button>
                </div>
              </td>
              <td class="py-1 ${(bg_saldo==''?bg_siguiente_pago_2:bg_saldo)}"><div class="formato-numero-conta"> <span>S/</span>${formato_miles(saldo_x_mes)}</div></td>
              <td class="text-center py-1 ${bg_siguiente_pago_2}"> 
                <button class="btn ${btn_tipo} btn-sm" ${btn_disabled} onclick="listar_tbla_recibo_por_honorario('${idfechas_mes_pagos_administrador_e}');">
                  <i class="fas fa-file-invoice fa-lg"></i>
                </button> 
              </td>
            </tr>`);
          } else {
            monto_total += (parseFloat(sueldo_mensual)/parseFloat(element.dias_mes))*parseInt(element.dias_regular);
  
            monto_x_mes = (parseFloat(sueldo_mensual)/parseFloat(element.dias_mes))*parseInt(element.dias_regular);
            
            dias_regular_total += parseInt(element.dias_regular);

            deposito_total += 0; 
            saldo_total += parseFloat(monto_x_mes);
            
            var bg_siguiente_pago = ""; var bg_siguiente_pago_2 = '';

            if ( fecha_dentro_de_rango(fecha_hoy_actual, format_a_m_d(element.fecha_i), format_a_m_d(element.fecha_f)) ) {
              bg_siguiente_pago = "bg-success"; bg_siguiente_pago_2 = 'bg-color-28a74533';
            } else {
              bg_siguiente_pago = "";
            }

            $('.data-fechas-mes').append(`<tr>
              <td class="text-center py-1 ${bg_siguiente_pago_2}" >${indice + 1}</td>
              <td class="text-center py-1 ${bg_siguiente_pago_2}">${element.mes_nombre} </td>
              <td class="text-center py-1 ${bg_siguiente_pago_2}">${element.fecha_i}</td>
              <td class="text-center py-1 ${bg_siguiente_pago}" >${element.fecha_f}</td>
              <td class="text-center py-1 ${bg_siguiente_pago_2}">${element.dias_regular}/${element.dias_mes}</td>
              <td class="py-1 ${bg_siguiente_pago_2}"><div class="formato-numero-conta"> <span>S/</span>${formato_miles(sueldo_mensual)}</div></td>
              <td class="py-1 ${bg_siguiente_pago_2}"><div class="formato-numero-conta"> <span>S/</span>${formato_miles(monto_x_mes)}</div></td>
              <td class="text-center py-1 ${bg_siguiente_pago_2}">
                <div class="formato-numero-conta">
                  <button class="btn btn-danger btn-sm mr-1" ${btn_disabled} onclick="listar_tbla_pagos_x_mes('', '${id_tabajador_x_proyecto}', '${element.fecha_i}', '${element.fecha_f}', '${element.mes_nombre}', '${element.dias_mes}', '${element.dias_regular}', '${sueldo_mensual}', '${monto_x_mes}', '${nombre_trabajador}', '${cuenta_bancaria}', '${monto_x_mes}' );"><i class="fas fa-dollar-sign"></i> Pagar</button>
                  <button style="font-size: 14px;" class="btn btn-danger btn-sm">S/ 0.00</button>
                </div>
              </td>
              <td class="py-1 ${bg_siguiente_pago_2}"><div class="formato-numero-conta"> <span>S/</span>${formato_miles(monto_x_mes)}</div></td>
              <td class="text-center py-1 ${bg_siguiente_pago_2}"> 
                <button class="btn btn-outline-info btn-sm" ${btn_disabled} onclick="listar_tbla_recibo_por_honorario('');">
                  <i class="fas fa-file-invoice fa-lg"></i>
                </button> 
              </td>
            </tr>`);
          }         
  
          cant_total_mes = indice + 1;        
        });
      }      

      if (cant_total_mes > 1 ) {  $('.cant_meses_total').html(`${cant_total_mes} meses`); } else { $('.cant_meses_total').html(`${cant_total_mes} mes`); } 

      if (dias_regular_total > 1) {
        $('.dias_x_mes_total').html(`${dias_regular_total} días`);
      } else {
        $('.dias_x_mes_total').html(`${dias_regular_total} día`);
      }      

      $('.monto_x_mes_total').html(`<span>S/</span> ${formato_miles(monto_total)}`);

      $('.monto_x_mes_pagado_total').html(`<span>S/</span> ${formato_miles(deposito_total)}`);

      $('.saldo_total').html(`<span>S/</span> ${formato_miles(saldo_total)}`); 

      $('.rh_total').html(`${rh_total} <small class="text-gray">(docs.)</small>`);
    });    
  }    
}

// ══════════════════════════════════════ PAGOS MES ══════════════════════════════════════
//Función limpiar
function limpiar_pago_x_mes() {  

  $("#idpagos_x_mes_administrador").val("");

  $("#monto").val("");
  $("#fecha_pago").val("");
  $("#forma_pago").val("").trigger("change"); 
  $("#descripcion").val(""); 

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

// Listar - TABLA INGRESO DE PAGOS
function listar_tbla_pagos_x_mes(idfechas_mes_pagos_administrador, id_tabajador_x_proyecto, fecha_inicial, fecha_final, mes_nombre, dias_mes, dias_regular, sueldo_mensual, monto_x_mes, nombre_trabajador, cuenta_bancaria, saldo_x_mes ) {

  table_show_hide(3);

  $('#btn-nombre-mes').html(`&nbsp; &nbsp; <b>${mes_nombre}</b> - <sup>S/</sup><b>${formato_miles(monto_x_mes)}</b>`);

  $('.faltante_mes_modal').html(`<sup>S/</sup><b>${formato_miles(saldo_x_mes)}</b>`);

  $('.nombre_de_trabajador_modal').html(`${nombre_trabajador}` );

  $('#cuenta_deposito').val(cuenta_bancaria);

  $('.nombre_mes_modal').html(`<b>${mes_nombre}</b>`);

  $('#idfechas_mes_pagos_administrador_pxm').val(idfechas_mes_pagos_administrador);
  $('#id_tabajador_x_proyecto_pxm').val(id_tabajador_x_proyecto);
  $('#fecha_inicial_pxm').val(format_a_m_d(fecha_inicial));
  $('#fecha_final_pxm').val(format_a_m_d(fecha_final));
  $('#mes_nombre_pxm').val(mes_nombre);
  $('#dias_mes_pxm').val(dias_mes);
  $('#dias_regular_pxm').val(dias_regular);
  $('#sueldo_mensual_pxm').val(sueldo_mensual);
  $('#monto_x_mes_pxm').val(parseFloat(monto_x_mes).toFixed(2));   

  tabla_ingreso_pagos=$('#tabla-ingreso-pagos').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    ajax:{
      url: '../ajax/pago_administrador.php?op=listar_tbla_pagos_x_mes&idfechas_mes_pagos='+idfechas_mes_pagos_administrador,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {   
      // columna: #0
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
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
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      //{ targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();  
}

//Función para guardar o editar
function guardar_y_editar_pagos_x_mes(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-pagos-x-mes")[0]);

  $.ajax({
    url: "../ajax/pago_administrador.php?op=guardar_y_editar_pagos_x_mes",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      try {
        datos = JSON.parse(datos); console.log(datos);
        if (datos.estado) {

          // tabla_ingreso_pagos.ajax.reload(null, false); 
          $('#idfechas_mes_pagos_administrador_pxm').val(datos.id_tabla);
          reload_table_pagos_x_mes(datos.id_tabla);        

          // tabla_principal.ajax.reload(null, false);     
          sumas_totales_tabla_principal(localStorage.getItem('nube_idproyecto'));    

          Swal.fire("Correcto!", "Pago guardado correctamente", "success");	      
          
          limpiar_pago_x_mes();

          $("#modal-agregar-pago-trabajdor").modal("hide");        

        }else{

          Swal.fire("Error!", datos, "error");				 
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      
      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
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
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// mostramos loa datos para editar: "pagos por mes"
function mostrar_pagos_x_mes(id) {

  limpiar_pago_x_mes();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  $("#modal-agregar-pago-trabajdor").modal('show');

  $.post("../ajax/pago_administrador.php?op=mostrar_pagos_x_mes", { 'idpagos_x_mes_administrador': id }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 
    
    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $('#idpagos_x_mes_administrador').val(data.idpagos_x_mes_administrador);
    $("#monto").val(data.monto);
    $("#fecha_pago").val(data.fecha_pago);
    $("#forma_pago").val(data.forma_de_pago).trigger("change"); 
    $("#descripcion").val(data.descripcion); 

    //validamoos BAUCHER - DOC 1
    if (data.baucher == "" || data.baucher == null  ) {

      $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

      $("#doc1_nombre").html('');

      $("#doc_old_1").val(""); $("#doc1").val("");

    } else {

      $("#doc_old_1").val(data.baucher); 

      $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(data.baucher)}</i></div></div>`);
      // cargamos la imagen adecuada par el archivo
      $("#doc1_ver").html(doc_view_extencion(data.baucher, 'pago_administrador', 'baucher_deposito', width='100%', height='210'));     
          
    }     
  });
}

function desactivar_pago_x_mes(id) {

  var id_fechas_mes = $('#idfechas_mes_pagos_administrador_pxm').val();

  Swal.fire({
    title: "¿Está Seguro de ANULAR el pago?",
    text: "Al anularlo este pago, el monto NO se contara como un deposito realizado.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/pago_administrador.php?op=desactivar_pago_x_mes", { 'idpagos_x_mes_administrador': id }, function (e) {

        if (e == "ok") {
          sumas_totales_tabla_principal(localStorage.getItem('nube_idproyecto')); 
          reload_table_pagos_x_mes(id_fechas_mes);
          Swal.fire("Anulado!", "Tu registro ha sido Anulado.", "success");
        } else {
          Swal.fire("Error!", e, "error");
        }        
      });      
    }
  });  
}

function activar_pago_x_mes(id) {

  var id_fechas_mes = $('#idfechas_mes_pagos_administrador_pxm').val();

  Swal.fire({
    title: "¿Está Seguro de ReActivar el pago?",
    text: "Al ReActivarlo este pago, el monto contara como un deposito realizado.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/pago_administrador.php?op=activar_pago_x_mes", { 'idpagos_x_mes_administrador': id }, function (e) {

        if (e == "ok") {
          sumas_totales_tabla_principal(localStorage.getItem('nube_idproyecto')); 
          reload_table_pagos_x_mes(id_fechas_mes);
          Swal.fire("ReActivado!", "Tu registro ha sido ReActivado.", "success");
        } else {
          Swal.fire("Error!", e, "error");
        }        
      });      
    }
  });
}

function reload_table_fechas_mes() {
  detalle_fechas_mes_trabajador(id_tabajador_x_proyecto_r, nombre_trabajador_r, fecha_inicial_r, fecha_hoy_r, fecha_final_r, sueldo_mensual_r, cuenta_bancaria_r, cant_dias_trabajando_r);
}

function reload_table_pagos_x_mes(id) {
  tabla_ingreso_pagos=$('#tabla-ingreso-pagos').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'pdf', "colvis"],
    ajax:{
      url: '../ajax/pago_administrador.php?op=listar_tbla_pagos_x_mes&idfechas_mes_pagos='+id,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {  
      // columna: #0
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center");   }
      // columna: fecha
      if (data[2] != '') {$("td", row).eq(2).addClass('text-nowrap');}
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
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      //{ targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();
}

// ══════════════════════════════════════ OTROS ══════════════════════════════════════
//Función limpiar
function limpiar_form_recibos_x_honorarios() {  
  // $("#form-recibos_x_honorarios").trigger("reset"); $  
  $('#idpagos_x_mes_administrador').val("");

  $('#idfechas_mes_pagos_administrador_rh').val("");
  $('#id_tabajador_x_proyecto_rh').val("");
  $('#fecha_inicial_rh').val("");
  $('#fecha_final_rh').val("");
  $('#mes_nombre_rh').val("");
  $('#dias_mes_rh').val("");
  $('#dias_regular_rh').val("");
  $('#sueldo_mensual_rh').val("");
  $('#monto_x_mes_rh').val("");

  $('#numero_comprobante').val("");

  $('#descargar_rh').attr('href', ''); 
  $('#ver_completo').attr('href', '');
  $("#doc2_nombre").html("");

  $("#doc2").val("");
  $("#doc_old_2").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

//Función para guardar o editar
function guardar_y_editar_recibos_x_honorarios(e) {
  //e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-recibos_x_honorarios")[0]);

  $.ajax({
    url: "../ajax/pago_administrador.php?op=guardar_y_editar_recibo_x_honorario",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
             
      if (datos == 'ok') {

        // console.log(id_tabajador_x_proyecto_r, nombre_trabajador_r, fecha_inicial_r, fecha_hoy_r, fecha_final_r, sueldo_mensual_r, cuenta_bancaria_r, cant_dias_trabajando_r);

        detalle_fechas_mes_trabajador(id_tabajador_x_proyecto_r, nombre_trabajador_r, fecha_inicial_r, fecha_hoy_r, fecha_final_r, sueldo_mensual_r, cuenta_bancaria_r, cant_dias_trabajando_r);

        sumas_totales_tabla_principal(localStorage.getItem('nube_idproyecto'));

        Swal.fire("Correcto!", "Recibo por honorario guardado correctamente", "success");	      
         
				limpiar_form_recibos_x_honorarios();

        $("#modal-recibos-x-honorarios").modal("hide");        

			}else{

        Swal.fire("Error!", datos, "error");				 
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

// MODAL- AGREGAR RECIBO X HONORARIO
function modal_recibos_x_honorarios(idfechas_mes_pagos_administrador, id_tabajador_x_proyecto, fecha_inicial, fecha_final, mes_nombre, dias_mes, dias_regular, sueldo_mensual, monto_x_mes, numero_comprobante, recibos_x_honorarios, nombre_trabajador, cuenta_bancaria) {
  
  // borramos los campos cargados con anterioridad
  limpiar_form_recibos_x_honorarios();

  $("#doc2_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

  $('#idfechas_mes_pagos_administrador_rh').val(idfechas_mes_pagos_administrador);
  $('#id_tabajador_x_proyecto_rh').val(id_tabajador_x_proyecto);
  $('#fecha_inicial_rh').val(format_a_m_d(fecha_inicial));
  $('#fecha_final_rh').val(format_a_m_d(fecha_final));
  $('#mes_nombre_rh').val(mes_nombre);
  $('#dias_mes_rh').val(dias_mes);
  $('#dias_regular_rh').val(dias_regular);
  $('#sueldo_mensual_rh').val(sueldo_mensual);
  $('#monto_x_mes_rh').val(parseFloat(monto_x_mes).toFixed(2));

  $('#numero_comprobante').val(numero_comprobante);

  $('.titulo_modal_recibo_x_honorarios').html(`Recibo por Honorario: <b>${mes_nombre}</b>`);

  $('#modal-recibos-x-honorarios').modal('show');

  if (recibos_x_honorarios == '' || recibos_x_honorarios == null) {
    $('.descargar').hide();
    $('.ver_completo').hide();
    $("#doc2_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
    $("#doc2_nombre").html('');
    $('#doc_old_2').val("");
    $('#doc2').val("");
    
  } else {

    $('.descargar').show();
    $('.ver_completo').show();

    $('#descargar_rh').attr('href', `../dist/docs/pago_administrador/recibos_x_honorarios/${recibos_x_honorarios}`);
    $('#descargar_rh').attr('download', `Recibo-por-honorario - ${mes_nombre} - ${nombre_trabajador}`); 
    $('#ver_completo').attr('href', `../dist/docs/pago_administrador/recibos_x_honorarios/${recibos_x_honorarios}`);
    $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Recibo-por-honorario.${extrae_extencion(recibos_x_honorarios)}</i></div></div>`);

    $('#doc_old_2').val(recibos_x_honorarios);
    $('#doc2').val('');
    $("#doc2_ver").html(doc_view_extencion(recibos_x_honorarios, 'pago_administrador', 'recibos_x_honorarios', width='100%', height='310'));
    
  }
}


init();

$(function () {  

  $("#form-pagos-x-mes").validate({
    rules: {
      forma_pago: { required: true},
      monto:      {required: true, min: 0.01 },
      fecha_pago: {required: true, },
      numero_comprobante: { minlength: 3, maxlength:45 },
      descripcion:{ minlength: 4 },
    },
    messages: {
      forma_pago: {required: "Campo requerido." },
      monto:      { required: "Campo requerido.", min: "MINIMO 0.01 dígito.", },
      fecha_pago: { required: "Campo requerido.", },
      numero_comprobante: {  minlength: "MINIMO 3 dígito.", maxlength: "MINIMO 45 dígito.", },
      descripcion:{ minlength: "MINIMO 4 caracteres.",  },
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
      guardar_y_editar_pagos_x_mes(e); 
    },
  });

  // $("#form-recibos_x_honorarios").validate({
  //   rules: {
  //     numero_comprobante: {required: true, minlength: 3, maxlength:45 },
  //   },
  //   messages: {
  //     numero_comprobante: { required: "Campo requerido.", minlength: "MINIMO 3 dígito.", maxlength: "MINIMO 45 dígito.", },
  //   },
    
  //   errorElement: "span",

  //   errorPlacement: function (error, element) {
  //     error.addClass("invalid-feedback");
  //     element.closest(".form-group").append(error);
  //   },

  //   highlight: function (element, errorClass, validClass) {
  //     $(element).addClass("is-invalid").removeClass("is-valid");
  //   },

  //   unhighlight: function (element, errorClass, validClass) {
  //     $(element).removeClass("is-invalid").addClass("is-valid");             
  //   },
    
  //   submitHandler: function (e) { 
  //     guardar_y_editar_recibos_x_honorarios(e);
  //   },
  // });
});


// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..
function l_m(){
  
  // limpiar();
  $("#barra_progress").css({"width":'0%'});
  $("#barra_progress").text("0%");

  $("#barra_progress2").css({"width":'0%'});
  $("#barra_progress2").text("0%");  
}