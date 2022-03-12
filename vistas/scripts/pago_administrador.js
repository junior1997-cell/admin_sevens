var tabla_principal; var tabla_ingreso_pagos;

var id_tabajador_x_proyecto_r = "", nombre_trabajador_r = "", fecha_inicial_r = "", fecha_hoy_r = "", fecha_final_r = "", sueldo_mensual_r = "", cuenta_bancaria_r = "", cant_dias_trabajando_r = "";

//Función que se ejecuta al inicio
function init() {

  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#bloc_PagosTrabajador").addClass("menu-open");

  $("#mContableFinanciero").addClass("active");

  $("#mPagosTrabajador").addClass("active bg-primary");

  $("#lPagosAdministrador").addClass("active");
  
  listar_tbla_principal(localStorage.getItem('nube_idproyecto'));

  //Mostramos los trabajadores
  $.post("../ajax/usuario.php?op=select2Trabajador&id=", function (r) { $("#trabajador").html(r); });  

  // efectuamos SUBMIT  registro de: PAGOS POR MES
  $("#guardar_registro").on("click", function (e) { $("#submit-form-pagos-x-mes").submit(); });

  // efectuamos SUBMIT  registro de: RECIBOS POR HONORARIOS
  $("#guardar_registro_2").on("click", function (e) { $("#submit-form-recibo-x-honorario").submit(); });
  $("#form-recibos_x_honorarios").on("submit", function (e) { guardar_y_editar_recibos_x_honorarios(e); });


  //Initialize Select2 unidad
  $("#forma_pago").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar una forma de pago",
    allowClear: true,
  });
   
  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addDocs(e,$("#doc1").attr("id")) });

$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addDocs(e,$("#doc2").attr("id")) });

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

//Función limpiar
function limpiar_pago_x_mes() {  

  $("#idpagos_x_mes_administrador").val("");

  $("#monto").val("");
  $("#forma_pago").val("").trigger("change"); 
  $("#descripcion").val(""); 

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

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

  $('#descargar_rh').attr('href', ''); 
  $('#ver_completo').attr('href', '');
  $("#doc2_nombre").html("");

  $("#doc2").val("");
  $("#doc_old_2").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

//Función Listar - tabla principal
function listar_tbla_principal(nube_idproyecto) {

  $('.sueldo_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.deposito_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  var total_pago_acumulado_hoy = 0, pago_total_x_proyecto = 0, saldo_total = 0;

  tabla_principal=$('#tabla-principal').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
      url: '../ajax/pago_administrador.php?op=listar_tbla_principal&nube_idproyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') {
        $("td", row).eq(0).css({
          "text-align": "center"
        });
      }  
      // columna: sueldo mensual
      if (data[5] != '') {
        $("td", row).eq(5).css({
          "text-align": "center"
        });
      }      

      // columna: sueldo mensual
      if (data[6] != '') {
        $("td", row).eq(6).css({
          "text-align": "right"
        });
      }

      // columna: pago total
      if (data[7] != '') {
        $("td", row).eq(7).css({
          "text-align": "right"
        });
        // acumulamos el PAGO TOTAL
        var split = data[7].split(' '); console.log(split);
        var quitar_format_mil = quitar_formato_miles( split[1]); console.log(quitar_format_mil);
        pago_total_x_proyecto += parseFloat(quitar_format_mil);
      }

      // columna: pago acumuldo       
      if (data[8] != '') {
        $("td", row).eq(8).css({
          "text-align": "right"
        });
        // acumulamos el PAGO acumulado hasta hoy
        var split = data[8].split(' '); console.log(split);
        var quitar_format_mil = quitar_formato_miles( split[1]); console.log(quitar_format_mil);
        total_pago_acumulado_hoy += parseFloat(quitar_format_mil);
      }

      // columna: saldo
      if (data[10] != '') {
        $("td", row).eq(10).css({
          "text-align": "right"
        });
        // acumulamos el SALDO
        var split = data[10].split(' '); console.log(split);
        var quitar_format_mil = quitar_formato_miles( split[1]); console.log(quitar_format_mil);
        saldo_total += parseFloat(quitar_format_mil);
      }

      // Validamos la comlumna: "Anterior pago"
      if (data[11] == "En espera...") {
        $("td", row).eq(11).css({
          "background-color": "#ffffff00",
          "color": "black",
        });
      }else if (data[11] == "Terminó") {        
        // $("td", row).eq(5).addClass('bg-success bg-gradient').css({ "color": "white",  "font-size": "18px" });        
      } else {
        $("td", row).eq(11).css({
          "background-color": "#28a745",
          "color": "white",
        });
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
        $("td", row).eq(12).css({
          "background-color": "#ffffff00",
          "color": "black",
        });
      } else if (data[12] == "Terminó") {        
        // $("td", row).eq(6).addClass('bg-success bg-gradient').css({ "color": "white", "font-size": "18px" });        
      } else{
        $("td", row).eq(12).css({
          "background-color": "#ffc107",
          "color": "black",
        });
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

  $.post("../ajax/pago_administrador.php?op=mostrar_total_tbla_principal", { 'nube_idproyecto': nube_idproyecto }, function (data, status) {
    data = JSON.parse(data);  console.log(data); 
    $('.sueldo_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(data.sueldo_mesual_x_proyecto)}</b>`);
    $('.pago_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(pago_total_x_proyecto)}</b>`);
    $('.pago_hoy_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(total_pago_acumulado_hoy)}</b>`);
    $('.deposito_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(data.monto_total_depositado_x_proyecto)}</b>`);  
    $('.saldo_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(saldo_total)}</b>`);   
  }); 

  
}
  
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

      var cant_total_mes = 0; console.log(array_fechas_mes);

      if (data.length === 0) {
        array_fechas_mes.forEach((element, indice) => {

          monto_total += (sueldo_mensual/element.dias_mes)*element.dias_regular;
  
          monto_x_mes = (sueldo_mensual/element.dias_mes)*element.dias_regular;
          
          dias_regular_total += element.dias_regular;

          deposito_total += 0; 
          saldo_total += parseFloat(monto_x_mes);

          var bg_siguiente_pago = "";

          if ( fecha_dentro_de_rango(fecha_hoy_actual, format_a_m_d(element.fecha_i), format_a_m_d(element.fecha_f)) ) {
            bg_siguiente_pago = "bg-success";
          } else {
            bg_siguiente_pago = "";
          }
  
          $('.data-fechas-mes').append(`<tr>
            <td>${indice + 1}</td>
            <td>${element.mes_nombre} </td>
            <td>${element.fecha_i}</td>
            <td class="${bg_siguiente_pago}" >${element.fecha_f}</td>
            <td>${element.dias_regular}/${element.dias_mes}</td>
            <td> S/. ${formato_miles(sueldo_mensual)}</td>
            <td> S/. ${formato_miles(monto_x_mes)}</td>
            <td class="justify-content-between">
              <button class="btn btn-info btn-sm" ${btn_disabled} onclick="listar_tbla_pagos_x_mes('', '${id_tabajador_x_proyecto}', '${element.fecha_i}', '${element.fecha_f}', '${element.mes_nombre}', '${element.dias_mes}', '${element.dias_regular}', '${sueldo_mensual}', '${monto_x_mes}', '${nombre_trabajador}', '${cuenta_bancaria}','${monto_x_mes}' );"><i class="fas fa-dollar-sign"></i> Pagar</button>
              <button style="font-size: 14px;" class="btn btn-danger btn-sm">S/. 0.00</button></div>
            </td>
            <td> S/. ${formato_miles(monto_x_mes)}</td>
            <td> 
              <button class="btn btn-outline-info btn-sm" ${btn_disabled} onclick="modal_recibos_x_honorarios('', '${id_tabajador_x_proyecto}', '${element.fecha_i}', '${element.fecha_f}', '${element.mes_nombre}', '${element.dias_mes}', '${element.dias_regular}', '${sueldo_mensual}', '${monto_x_mes}', '', '${nombre_trabajador}', '${cuenta_bancaria}');">
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
          var monto_x_mes_e = 0; var nombre_mes_e = ""; var sueldo_mensual_e = 0; var recibos_x_honorarios_e = ""; var btn_tipo = ""; 
          var suma_monto_depositado_e = 0; var btn_tipo_deposito = ""; var bg_saldo = ""

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
              recibos_x_honorarios_e = value.recibos_x_honorarios;
              estado_e = value.estado;

              suma_monto_depositado_e = value.suma_monto_depositado

              // Validamos el tipo de boton para los "recibos por honorarios"
              if (value.recibos_x_honorarios == '' || value.recibos_x_honorarios == null) { btn_tipo = 'btn-outline-info'; } else { btn_tipo = 'btn-info'; rh_total += 1; }
            } 
            // console.log(`${nombre_mes_e} - fecha encontrada: ${fecha_inicial_e} == ${format_a_m_d(element.fecha_i)} ---- ${fecha_final_e} == ${format_a_m_d(element.fecha_f)}`);
          });
          
          // validamos si encontramos las fechas
          if (fechas_mes_estado) { 
            console.log('entreee');
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

            var bg_siguiente_pago = "";

            if ( fecha_dentro_de_rango(fecha_hoy_actual, fecha_inicial_e, fecha_final_e) ) {
              bg_siguiente_pago = "bg-success";
            } else {
              bg_siguiente_pago = "";
            }
    
            $('.data-fechas-mes').append(`<tr>
              <td>${indice + 1}</td>
              <td>${nombre_mes_e} </td>
              <td>${format_d_m_a(fecha_inicial_e)}</td>
              <td class="${bg_siguiente_pago}" >${format_d_m_a(fecha_final_e)}</td>
              <td>${cant_dias_laborables_e}/${cant_dias_mes_e}</td>
              <td> S/. ${formato_miles(sueldo_mensual_e)}</td>
              <td> S/. ${formato_miles(monto_x_mes_e)}</td>
              <td >
                <div class="justify-content-between">
                  <button class="btn btn-info btn-sm" ${btn_disabled} onclick="listar_tbla_pagos_x_mes('${idfechas_mes_pagos_administrador_e}', '${idtrabajador_por_proyecto_e}', '${format_d_m_a(fecha_inicial_e)}', '${format_d_m_a(fecha_final_e)}', '${nombre_mes_e}', '${cant_dias_mes_e}', '${cant_dias_laborables_e}', '${sueldo_mensual_e}', '${monto_x_mes_e}', '${nombre_trabajador}', '${cuenta_bancaria}', '${saldo_x_mes}' );"><i class="fas fa-dollar-sign"></i> Pagar</button>
                  <button style="font-size: 14px;" class="btn ${btn_tipo_deposito} btn-sm">S/. ${formato_miles(suma_monto_depositado_e)}</button>
                </div>
              </td>
              <td class="${bg_saldo}"> S/. ${formato_miles(saldo_x_mes)}</td>
              <td> 
                <button class="btn ${btn_tipo} btn-sm" ${btn_disabled} onclick="modal_recibos_x_honorarios('${idfechas_mes_pagos_administrador_e}', '${idtrabajador_por_proyecto_e}', '${format_d_m_a(fecha_inicial_e)}', '${format_d_m_a(fecha_final_e)}', '${nombre_mes_e}', '${cant_dias_mes_e}', '${cant_dias_laborables_e}', '${sueldo_mensual_e}', '${monto_x_mes_e}', '${recibos_x_honorarios_e}', '${nombre_trabajador}', '${cuenta_bancaria}');">
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
            
            var bg_siguiente_pago = "";

            if ( fecha_dentro_de_rango(fecha_hoy_actual, format_a_m_d(element.fecha_i), format_a_m_d(element.fecha_f)) ) {
              bg_siguiente_pago = "bg-success";
            } else {
              bg_siguiente_pago = "";
            }

            $('.data-fechas-mes').append(`<tr>
              <td>${indice + 1}</td>
              <td>${element.mes_nombre} </td>
              <td>${element.fecha_i}</td>
              <td class="${bg_siguiente_pago}" >${element.fecha_f}</td>
              <td>${element.dias_regular}/${element.dias_mes}</td>
              <td> S/. ${formato_miles(sueldo_mensual)}</td>
              <td> S/. ${formato_miles(monto_x_mes)}</td>
              <td >
                <div class="justify-content-between">
                  <button class="btn btn-info btn-sm" ${btn_disabled} onclick="listar_tbla_pagos_x_mes('', '${id_tabajador_x_proyecto}', '${element.fecha_i}', '${element.fecha_f}', '${element.mes_nombre}', '${element.dias_mes}', '${element.dias_regular}', '${sueldo_mensual}', '${monto_x_mes}', '${nombre_trabajador}', '${cuenta_bancaria}', '${monto_x_mes}' );"><i class="fas fa-dollar-sign"></i> Pagar</button>
                  <button style="font-size: 14px;" class="btn btn-danger btn-sm">S/. 0.00</button>
                </div>
              </td>
              <td> S/. ${formato_miles(monto_x_mes)}</td>
              <td> 
                <button class="btn btn-outline-info btn-sm" ${btn_disabled} onclick="modal_recibos_x_honorarios('', '${id_tabajador_x_proyecto}', '${element.fecha_i}', '${element.fecha_f}', '${element.mes_nombre}', '${element.dias_mes}', '${element.dias_regular}', '${sueldo_mensual}', '${monto_x_mes}', '', '${nombre_trabajador}', '${cuenta_bancaria}');">
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

      $('.monto_x_mes_total').html(`S/. ${formato_miles(monto_total)}`);

      $('.monto_x_mes_pagado_total').html(`S/. ${formato_miles(deposito_total)}`);

      $('.saldo_total').html(`S/. ${formato_miles(saldo_total)}`); 

      $('.rh_total').html(`${rh_total} <small class="text-gray">(docs.)</small>`);
    });    
  }    
}

// Listar - TABLA INGRESO DE PAGOS
function listar_tbla_pagos_x_mes(idfechas_mes_pagos_administrador, id_tabajador_x_proyecto, fecha_inicial, fecha_final, mes_nombre, dias_mes, dias_regular, sueldo_mensual, monto_x_mes, nombre_trabajador, cuenta_bancaria, saldo_x_mes ) {

  table_show_hide(3);

  $('#btn-nombre-mes').html(`&nbsp; &nbsp; <b>${mes_nombre}</b> - <sup>S/.</sup><b>${formato_miles(monto_x_mes)}</b>`);

  $('.faltante_mes_modal').html(`<sup>S/.</sup><b>${formato_miles(saldo_x_mes)}</b>`);

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
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/pago_administrador.php?op=listar_tbla_pagos_x_mes&idfechas_mes_pagos='+idfechas_mes_pagos_administrador,
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
        }
      },
      createdRow: function (row, data, ixdex) {    
  
        // columna: #0
        if (data[0] != '') {
          $("td", row).eq(0).addClass("text-center");   
           
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

// MODAL- AGREGAR RECIBO X HONORARIO
function modal_recibos_x_honorarios(idfechas_mes_pagos_administrador, id_tabajador_x_proyecto, fecha_inicial, fecha_final, mes_nombre, dias_mes, dias_regular, sueldo_mensual, monto_x_mes, recibos_x_honorarios, nombre_trabajador, cuenta_bancaria) {
  
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

    if ( extrae_extencion(recibos_x_honorarios) == "pdf" ) {
      $("#doc2_ver").html(`<iframe src="../dist/docs/pago_administrador/recibos_x_honorarios/${recibos_x_honorarios}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
    } else {
      if ( extrae_extencion(recibos_x_honorarios) == "jpeg" || extrae_extencion(recibos_x_honorarios) == "jpg" || extrae_extencion(recibos_x_honorarios) == "jpe" ||
        extrae_extencion(recibos_x_honorarios) == "jfif" || extrae_extencion(recibos_x_honorarios) == "gif" || extrae_extencion(recibos_x_honorarios) == "png" ||
        extrae_extencion(recibos_x_honorarios) == "tiff" || extrae_extencion(recibos_x_honorarios) == "tif" || extrae_extencion(recibos_x_honorarios) == "webp" ||
        extrae_extencion(recibos_x_honorarios) == "bmp" || extrae_extencion(recibos_x_honorarios) == "svg" ) {

        $("#doc2_ver").html(`<img src="../dist/docs/pago_administrador/recibos_x_honorarios/${recibos_x_honorarios}" alt="" width="50%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
        
      } else {
        $("#doc2_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
      }      
    }
  }
}

//Función para guardar o editar
function guardar_y_editar_recibos_x_honorarios(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
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

        tabla_principal.ajax.reload();

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
      datos = JSON.parse(datos); console.log(datos);

      if (datos.estado) {

        // tabla_ingreso_pagos.ajax.reload(); 
        $('#idfechas_mes_pagos_administrador_pxm').val(datos.id_tabla);
        reload_table_pagos_x_mes(datos.id_tabla);        

        // tabla_principal.ajax.reload();     
        listar_tbla_principal(localStorage.getItem('nube_idproyecto'));    

        Swal.fire("Correcto!", "Pago guardado correctamente", "success");	      
         
				limpiar_pago_x_mes();

        $("#modal-agregar-pago-trabajdor").modal("hide");        

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
      if ( extrae_extencion(data.baucher) == "pdf" ) {

        $("#doc1_ver").html('<iframe src="../dist/pago_administrador/baucher_deposito/'+data.baucher+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

      }else{
        if (
          extrae_extencion(data.baucher) == "jpeg" || extrae_extencion(data.baucher) == "jpg" || extrae_extencion(data.baucher) == "jpe" ||
          extrae_extencion(data.baucher) == "jfif" || extrae_extencion(data.baucher) == "gif" || extrae_extencion(data.baucher) == "png" ||
          extrae_extencion(data.baucher) == "tiff" || extrae_extencion(data.baucher) == "tif" || extrae_extencion(data.baucher) == "webp" ||
          extrae_extencion(data.baucher) == "bmp" || extrae_extencion(data.baucher) == "svg" ) {

          $("#doc1_ver").html(`<img src="../dist/pago_administrador/baucher_deposito/${data.baucher}" alt="" width="50%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
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
          listar_tbla_principal(localStorage.getItem('nube_idproyecto')); 
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
          listar_tbla_principal(localStorage.getItem('nube_idproyecto')); 
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
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/pago_administrador.php?op=listar_tbla_pagos_x_mes&idfechas_mes_pagos='+id,
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
        }
      },
      createdRow: function (row, data, ixdex) {    
  
        // columna: #0
        if (data[0] != '') {
          $("td", row).eq(0).addClass("text-center");   
           
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

function l_m(){
  
  // limpiar();
  $("#barra_progress").css({"width":'0%'});
  $("#barra_progress").text("0%");

  $("#barra_progress2").css({"width":'0%'});
  $("#barra_progress2").text("0%");  
}

init();

$(function () {

  $.validator.setDefaults({ submitHandler: function (e) { guardar_y_editar_pagos_x_mes(e); }, });

  $("#form-pagos-x-mes").validate({
    rules: {
      forma_pago: { required: true},
      monto: {required: true, minlength: 1 },
      descripcion: { minlength: 4 },
    },
    messages: {
      forma_pago: {
        required: "Campo requerido."
      },
      monto: {
        required: "Campo requerido.",
        minlength: "MINIMO 1 dígito.",
      },
      descripcion: {
        minlength: "MINIMO 4 caracteres.",
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


// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


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

// calulamos la cantidad de dias de una mes especifico
function cantidad_dias_mes(year, month) {

  var diasMes = new Date(year, month, 0).getDate();
  return diasMes;
}

// quitamos las comas de miles de un numero
function quitar_formato_miles(numero) {
  let inVal = numero.replace(/,/g, '');
  return inVal;
}

// damos formato de miles a un numero
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

// extrae los nombres de dias de semana "Completo"
function extraer_dia_semana_complet(fecha) {
  const fechaComoCadena = fecha; // día fecha
  const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']; //
  const numeroDia = new Date(fechaComoCadena).getDay();
  const nombreDia = dias[numeroDia];
  return nombreDia;
}

function extraer_nombre_mes(fecha_entrada) {

  const array_mes = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
  
  let date = new Date(fecha_entrada.replace(/-+/g, '/'));
    
  var mes_indice = date.getMonth();

  var nombre_completo = array_mes[mes_indice];

  return nombre_completo;
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

function valida_fecha_menor_que(fecha_menor, fecha_mayor) {
  var f1 = new Date(fecha_menor); //fecha "fecha_menor" parseado a "Date()"
  var f2 = new Date(fecha_mayor); //fecha "fecha_mayor" parseado a "Date()"

  var estado;

  //nos aseguramos que no tengan hora
  f1.setHours(0,0,0,0);
  f2.setHours(0,0,0,0);

  // validamos las fechas con un IF
  if (f1.getTime() < f2.getTime()){
    estado = true;
  }else{
    estado = false;
  }

  return estado;
}

function fecha_dentro_de_rango(fecha, rango_inicial, rango_final) {

  var fechar_validar = new Date(fecha);
  var f1 = new Date(rango_inicial);
  var f2 = new Date(rango_final);

  //nos aseguramos que no tengan hora
  fechar_validar.setHours(0,0,0,0);
  f1.setHours(0,0,0,0);
  f2.setHours(0,0,0,0);
 
  // validamos las fechas con un IF
  if (fechar_validar.getTime() >= f1.getTime() && fechar_validar.getTime() <= f2.getTime() ){
    return true;
  }

  return false;
}

function calcular_siguiente_pago(params) {
  
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

                          $("#"+id+"_ver").html(`<img src="${result}" alt="" width="50%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
                          
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
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/pago_administrador/${carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
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
  
                      $("#doc"+id+"_ver").html(`<img src="../dist/docs/pago_administrador/${carpeta}/${antiguopdf}" alt="" onerror="this.src='../dist/svg/error-404-x.svg';" width="50%" >`);
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