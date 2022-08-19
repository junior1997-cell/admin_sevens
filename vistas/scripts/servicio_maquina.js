var tabla;
var tabla2;
var tabla3;
var tabla4;
var tabladetrecc;
var idmaquina;

//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lMaquina").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  listar(localStorage.getItem("nube_idproyecto"));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_pago', null);

  lista_select2("../ajax/ajax_general.php?op=select2_servicio_maquina", '#maquinaria', null);
  
  // ══════════════════════════════════════ G U A R D A R  S E R V I C I O ═════════════════════

  $("#guardar_registro").on("click", function (e) { $("#submit-form-servicios").submit(); });

  // ══════════════════════════════════════ G U A R D A R  P A G O ══════════════════════════════

  $("#guardar_registro_pago").on("click", function (e) { $("#submit-form-pago").submit(); });

  // ══════════════════════════════════════ G U A R D A R  F A C T U R A ══════════════════════════

  $("#guardar_registro_factura").on("click", function (e) { $("#submit-form-factura").submit(); });

  // ══════════════════════════════════════ INICIALIZE SELECT2 ELEMENTS ══════════════════════════

  //============SERVICIO================

  $("#maquinaria").select2({ theme: "bootstrap4", placeholder: "Selecione maquinaria", allowClear: true, });

  $("#unidad_m").select2({ theme: "bootstrap4", placeholder: "Selecione una unidad de medida", allowClear: false, });

  //============pagoo================

  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Selecione una forma de pago", allowClear: true, });

  $("#tipo_pago").select2({ theme: "bootstrap4", placeholder: "Selecione un tipo de pago", allowClear: true, });

  $("#banco_pago").select2({ theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

// abrimos el navegador de archivos --vaucher
$("#doc1_i").click(function () {  $("#doc1").trigger("click"); });
$("#doc1").change(function (e) { addImageApplication(e, $("#doc1").attr("id"),'', '100%', '320'); });

// abrimos el navegador de archivos -- factura
$("#doc2_i").click(function () {  $("#doc2").trigger("click"); });
$("#doc2").change(function (e) { addImageApplication(e, $("#doc2").attr("id")); });

// Eliminamos el COMPROBANTE - vaucher pagos
function doc1_eliminar() {
  $("#doc1").val("");
  $("#doc_old_1").val("");
  $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc1_nombre").html("");
}

// Eliminamos el doc FOTO PERFIL - factura
function doc2_eliminar() {
  $("#doc2").val("");
  $("#doc_old_2").val("");
  $("#doc2_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc2_nombre").html("");
}

//regresar_principal
function regresar_principal() {
  $("#tabla_principal").show();
  $("#btn-agregar").show();

  $("#tabla_detalles").hide();
  $("#btn-regresar").hide();

  $("#tabla_pagos").hide();
  $("#btn-pagar").hide();

  $("#tabla_facturas_h").hide();
  $("#btn-factura").hide();
  limpiar();
  limpiar_c_pagos();
  $("#t_proveedor").html("");
  $("#t_provee_porc").html("");
  //-------------
  $("#t_detaccion").html("");
  $("#t_detacc_porc").html("");
}

//---------------------------------------------------------------------------------
//----------------------T A B L A   P R I N C I P A L------------------------------
//---------------------------------------------------------------------------------

function listar(nube_idproyecto) {

  tabla = $("#tabla-servicio").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax: {
      url: "../ajax/servicio_maquina.php?op=listar&nube_idproyecto=" + nube_idproyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {

      if (data[6] != '') {
        $("td", row).eq(6).addClass('text-nowrap text-right');
      }
      if (data[8] != "") {

        var num = parseFloat(quitar_formato_miles(data[8])); console.log(num);

        if (num > 0) {
          $("td", row).eq(8).addClass('bg-warning text-right');
        } else if (num == 0) {
          $("td", row).eq(8).addClass('bg-success text-right');            
        } else if (num < 0) {
          $("td", row).eq(8).addClass('bg-danger text-right');
        }
      }

    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();

}

//--------------------------------------------------------------------------
//--------------S E C C I O N  S E R V I C I O S----------------------------
//--------------------------------------------------------------------------

//Función detalles po maquina
function listar_detalle(idmaquinaria, idproyecto, unidad_medida) {
  var hideen_colums;

  $("#tabla_principal").hide();
  $("#tabla_detalles").show();
  $("#btn-agregar").hide();
  $("#btn-regresar").show();
  $("#btn-pagar").hide();
  if (unidad_medida == "Hora") {
    hideen_colums = [];
  } else {
    hideen_colums = [
      {targets: [3],visible: false,searchable: false,},
      {targets: [4],visible: false,searchable: false,},
      {targets: [5],visible: false,searchable: false,}
    ];
  }
  // console.log(hideen_colums);
  tabla2 = $("#tabla-detalle-m").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax: {
      url: "../ajax/servicio_maquina.php?op=ver_detalle_maquina&idmaquinaria=" + idmaquinaria + "&idproyecto=" + idproyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      // columna: P:U
      if (data[3] != '') {
        $("td", row).eq(3).addClass('text-nowrap text-right');
      }
      // columna: total
      if (data[6] != '') {
        $("td", row).eq(6).addClass('text-nowrap text-right');
      }

    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: hideen_colums,
  }).DataTable();

  total_costo_parcial_detalle(idmaquinaria, localStorage.getItem("nube_idproyecto"));

}

//función capturar unidad select (hora-dia-mes)
function capture_unidad() {
  //Hora
  if ($("#unidad_m").select2("val") == "Hora") {
    $("#dias_head").hide();
    $("#meses_head").hide();
    $("#fecha_i").show();
    $("#fecha_f").hide();
    $("#cantidad_ii").hide();
    $("#horometro_i").show();
    $("#horometro_f").show();
    $("#costo_unit").show();
    $("#horas_head").show();
    $("#unidad").addClass("col-lg-6");

    $("#dias").val("");
    $("#mes").val("");
    $("#fecha_fin").val("");
    $("#fecha_inicio").val("");
    $("#fecha-i-titulo").html('Fecha: <b style="color: red;"> - </b>');
    $("#cantidad").val("0");

    //Dia
  } else if ($("#unidad_m").select2("val") == "Dia") {

    $("#horas_head").hide();
    $("#dias_head").hide();
    $("#meses_head").hide();
    $("#fecha_i").show();
    $("#unidad").removeClass("col-lg-6").addClass("col-lg-3");
    $("#cantidad_ii").show();
    $("#fecha_f").hide();
    $("#horometro_i").hide();
    $("#horometro_f").hide();
    $("#costo_unit").show();
    $("#dias").hide();
    $("#fecha_fin").attr("readonly", true);

    $("#fecha_fi").html("Fecha Fin :");
    $("#mes").val("");
    $("#dias").val("");
    $("#horas").val("");
    $("#fecha_fin").val("");
    $("#fecha_inicio").val("");
    $("#horometro_inicial").val("");
    $("#horometro_final").val("");
    $("#costo_unitario").val("");
    $("#costo_parcial").val("");
    $("#fecha-i-titulo").html('Fecha: <b style="color: red;"> - </b>');
    $("#cantidad").val("1");
    $("#costo_partcial").val("");

    //Mes
  } else if ($("#unidad_m").select2("val") == "Mes") {
    $("#horas_head").hide();
    $("#dias_head").hide();
    $("#meses_head").hide();
    $("#costo_unit").show();
    $("#horometro_i").hide();
    $("#horometro_f").hide();
    $("#unidad").removeClass("col-lg-6").addClass("col-lg-3");
    $("#cantidad_ii").show();
    $("#fecha_i").show();
    $("#fecha_f").show();
    $("#fecha_fin").attr("readonly", true);
    /**======= */
    $("#fecha_fi").html("Fecha Fin :");

    $("#dias").val("");
    $("#horas").val("");
    $("#mes").val("");
    $("#horometro_inicial").val("");
    $("#horometro_final").val("");
    $("#costo_unitario").val("");
    $("#horas").val("");
    $("#fecha-i-titulo").html('Fecha: <b style="color: red;"> - </b>');
    $("#cantidad").val("1");
    $("#costo_partcial").val("");
    $("#fecha_inicio").val("");
    $("#fecha_fin").val("");
  }
}

//Calculamos costo parcial.
function costo_partcial() {

  var horometro_inicial = $("#horometro_inicial").val();
  var horometro_final = $("#horometro_final").val();
  var costo_unitario = $("#costo_unitario").val();
  var costo_adicional = $("#costo_adicional").val();
  var cantidad = $("#cantidad").val();
  var costo_parcial = 0;

  if (cantidad == 0) {
    if (horometro_final != 0) {
      var horas = (horometro_final - horometro_inicial).toFixed(2);
      costo_parcial = parseFloat(horas * costo_unitario).toFixed(2);

    } else {
      var horas = (horometro_inicial - horometro_inicial).toFixed(2);
      costo_parcial = parseFloat(costo_unitario).toFixed(2);
    }
  } else {
    if (cantidad != 0) {
      costo_parcial = (cantidad * costo_unitario).toFixed(2);
    } else {
      costo_parcial = parseFloat(costo_unitario).toFixed(2);
    }
  }

  if (costo_adicional != "") {
    costo_parcial = (parseFloat(costo_parcial) + parseFloat(costo_adicional)).toFixed(2);
  }

  $("#horas").val(horas);
  $("#costo_parcial").val(costo_parcial);

}

//funcion calcular dias
function calculardia() {

  if ($("#fecha_inicio").val().length > 0) {

    if ($("#unidad_m").select2("val") == "Hora" || $("#unidad_m").select2("val") == "Dia") {

      $("#fecha_fin").val("");
      $("#fecha_fi").html("Fecha final");
      var x = $("#fecha_inicio").val(); // día lunes
      let date = new Date(x.replace(/-+/g, "/"));

      let options = { weekday: "long", year: "numeric", month: "long", day: "numeric", };

      $("#fecha-i-titulo").html('Fecha: <b style="color: red;">' + date.toLocaleDateString("es-MX", options) + "</b>");

    } else {

      //Recogemos las fechas del input  fecha_inicio

      var y = $("#fecha_inicio").val();

      //Recogemos la fecha inical y la cantidas de meses y calculamos la fecha final
      var cantidad_meses = $("#cantidad").val();

      if ($("#fecha_inicio").val() != "" && $("#cantidad").val() != "") {
        var x = $("#fecha_inicio").val();

        var calculando_fecha = moment(x).add(cantidad_meses, "months").format("YYYY-MM-DD");

        $("#fecha_fin").val(calculando_fecha);

        var nombrefecha1 = diaSemana(x);
        var nombrefecha2 = diaSemana(calculando_fecha);

        $("#fecha-i-titulo").html('Fecha: <b style="color: red;">' + nombrefecha1 + "</b>");

        $("#fecha_fi").html('Fecha Fin: <b style="color: red;">' + nombrefecha2 + "</b>");
      } else {

        toastr.error("Seleccionar la fecha inicial o la cantidad!!!");

        $("#fecha_fin").val("");

        $("#fecha-i-titulo").html("");
        $("#fecha_fi").html("");

      }
    }
  } else {

    $("#fecha-i-titulo").html('Fecha: <b style="color: red;"> - </b>');

  }
}

//Funcion para los nombres de la día mes y año
function diaSemana(fecha) {

  if (fecha != "") {

    let date = new Date(fecha.replace(/-+/g, "/"));

    let options = { weekday: "long", year: "numeric", month: "long", day: "numeric", };

    return date.toLocaleDateString("es-MX", options);

  } else {

    return "";

  }
}
//limpiar
function limpiar() {

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));
  $("#idservicio").val("");
  $("#maquinaria").val("null").trigger("change");
  $("#unidad_m").val("Hora").trigger("change");
  $("#fecha_inicio").val("");
  $("#fecha_fin").val("");
  $("#horometro_inicial").val("");
  $("#horometro_final").val("");
  $("#horas").val("");
  $("#dias").val("");
  $("#mes").val("");
  $("#costo_unitario").val("");
  $("#costo_parcial").val("");
  $("#costo_adicional").val("");
  $("#costo_unitario").attr("readonly", false);
  $("#nomb_maq").val("");
  $("#descripcion").val("");
  $("#ocultar_select").show();
  $("#nomb_maq").hide();
  $("#cantidad").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función total_costo_parcial_detalle
function total_costo_parcial_detalle(idmaquinaria, idproyecto) {

  $("#costo-parcial").html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);

  $.post("../ajax/servicio_maquina.php?op=total_costo_parcial_detalle", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (e, status) {

    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {

      $("#costo-parcial").html('S/ '+ formato_miles(e.data.costo_parcial));

    } else {

      ver_errores(e);

    }

  }).fail( function(e) { ver_errores(e); } );

}

//Guardar y editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-servicios")[0]);

  $.ajax({
    url: "../ajax/servicio_maquina.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (e) {

      try {
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {

          Swal.fire("Correcto!", "El registro se guardo correctamente.", "success");

          tabla.ajax.reload(null, false);

          $("#modal-agregar-servicio").modal("hide");
  
          tabla2.ajax.reload(null, false);
  
          var idmaquinaria = $("#maquinaria").val();
          if (idmaquinaria != "") {
            total_costo_parcial_detalle(idmaquinaria, localStorage.getItem("nube_idproyecto"));
          }
          limpiar();

        }else{  

          ver_errores(e);

        } 
      } catch (err) {

        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      } 

    },
  });
}

//mostrar datos para editar 
function mostrar(idservicio) {
  limpiar();

  $("#maquinaria").val("").trigger("change");
  $("#unidad_m").val("").trigger("change");
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  $("#cantidad").val("");
  $("#modal-agregar-servicio").modal("show");
  $("#ocultar_select").hide();
  $("#nomb_maq").show();
  $("#costo_unitario").attr("readonly", false);

  $.post("../ajax/servicio_maquina.php?op=mostrar", { idservicio: idservicio }, function (e, status) {
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {

        $("#cargando-1-fomulario").show();
        $("#cargando-2-fomulario").hide();
        $("#nomb_maq").val(e.data.nombre_maquina + " - " + e.data.codigo_maquina + " --> " + e.data.razon_social);
        $("#idservicio").val(e.data.idservicio);
        $("#maquinaria").val(e.data.idmaquinaria).trigger("change");
        $("#unidad_m").val(e.data.unidad_medida).trigger("change");
        $("#fecha_inicio").val(e.data.fecha_entrega);
        $("#fecha_fin").val(e.data.fecha_recojo);
        $("#horometro_inicial").val(e.data.horometro_inicial);
        $("#horometro_final").val(e.data.horometro_final);
        $("#horas").val(e.data.horas);
        $("#cantidad").val(e.data.cantidad);
        $("#descripcion").val(e.data.descripcion);
        $("#dias").val(e.data.dias_uso);
        $("#mes").val(e.data.meses_uso);
        $("#costo_unitario").val(e.data.costo_unitario);
        $("#costo_adicional").val(e.data.costo_adicional);
        $("#costo_parcial").val(e.data.costo_parcial);

        calculardia();
        
      } else {

        ver_errores(e);

      }

    }).fail( function(e) { ver_errores(e); } );
}

function eliminar(idservicio, idmaquinaria, fecha_entreg, fecha_recojo) {
  var fecha="";
if (fecha_recojo=="" || fecha_recojo==null) { fecha=fecha_entreg;}else{ fecha=fecha_entreg+' - '+fecha_recojo;}

  crud_eliminar_papelera(
    "../ajax/servicio_maquina.php?op=desactivar",
    "../ajax/servicio_maquina.php?op=eliminar", 
    idservicio, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del> Registro con fecha - ${fecha} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ total_costo_parcial_detalle(idmaquinaria, localStorage.getItem("nube_idproyecto")); },
    function(){ tabla.ajax.reload(null, false); tabla2.ajax.reload(null, false); },
    false, 
    false,
    false
  );

}

//-------------------------------------------------------------------------------
//----------------------S E C C   P A G O  P O R  S E R V------------------------
//-------------------------------------------------------------------------------

  //Función limpiar
function limpiar_c_pagos() {

  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");
  $("#monto_pago").val("");
  $("#numero_op_pago").val("");
  $("#cuenta_destino_pago").val("");
  $("#descripcion_pago").val("");
  $("#idpago_servicio").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();

}

//Guardar y editar
function guardaryeditar_pago(e) {

  var formData = new FormData($("#form-servicios-pago")[0]);

  $.ajax({
    url: "../ajax/servicio_maquina.php?op=guardaryeditar_pago",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e); 

        if (e.status == true) {

          Swal.fire("Correcto!", "El registro se guardo correctamente.", "success");

          tabla.ajax.reload(null, false);
  
          $("#modal-agregar-pago").modal("hide");
  
          tabla3.ajax.reload(null, false); 
          
          tabladetrecc.ajax.reload(null, false);
          
          total_pagos(localStorage.getItem("nubeidmaquinaria"), localStorage.getItem("nube_idproyecto"));
  
          total_costo_secc_pagoss(localStorage.getItem("nubeidmaquinaria"), localStorage.getItem("nube_idproyecto"));
  
          limpiar_c_pagos();

        }else{  

          ver_errores(e);

        } 
      } catch (err) {

        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      
      } 
    },
  });
}

//Listar pagos.
function listar_pagos(idmaquinaria, idproyecto, costo_parcial, monto) {

  localStorage.setItem("monto_total_p", costo_parcial);
  localStorage.setItem("monto_total_dep", monto);

  // var proveedor = "Proveedor";
  // var detraccion = "Detraccion";
  $("#tabla_principal").hide();
  $("#tabla_pagos").show();
  $("#btn-agregar").hide();
  $("#btn-regresar").show();
  $("#btn-pagar").show();
  //_____________________________________________
  //_____________tabla-pagos-proveedor___________
  //_____________________________________________
  tabla3 = $("#tabla-pagos-proveedor").dataTable({

    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax: {
      url: "../ajax/servicio_maquina.php?op=listar_pagos_proveedor&idmaquinaria=" + idmaquinaria + "&idproyecto=" + idproyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: P:U
      if (data[7] != '') { $("td", row).eq(7).addClass('text-nowrap text-right'); }
    },

    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },

    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)

  }).DataTable();

  //_____________________________________________
  //__________tabla-pagos-detrecciones___________
  //_____________________________________________

  tabladetrecc = $("#tabla-pagos-detrecciones").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax: {
      url: "../ajax/servicio_maquina.php?op=listar_pagos_detraccion&idmaquinaria=" + idmaquinaria + "&idproyecto=" + idproyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
      /* success:function(data){
        console.log(data);	
      },*/
    },   
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      // columna: P:U
      if (data[7] != '') {
        $("td", row).eq(7).addClass('text-nowrap text-right');
      }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();

  total_pagos(idmaquinaria, idproyecto);
  most_datos_prov_pago(idmaquinaria, idproyecto);
  total_costo_secc_pagoss(idmaquinaria, idproyecto);

}

//total_costo_secc_pagoss
function total_costo_secc_pagoss(idmaquinaria, idproyecto) {

  $("#total_costo_secc_pagos").html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);

  $.post("../ajax/servicio_maquina.php?op=total_costo_parcial_pago", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (e, status) {

    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {
      //mostramos toral total deuda
      $("#total_costo_secc_pagos").html('S/ '+formato_miles(e.data.costo_parcial));

    } else {

      ver_errores(e);

    }

  }).fail( function(e) { ver_errores(e); } );
}

//-mostramos los totales con sus pocentajes en la secc de pagos
function total_pagos(idmaquinaria, idproyecto) {

  //_____________________________________________________________________________________________________
  //_____________________________________________________________________________________________________

  var totattotal = localStorage.getItem("monto_total_p");

  var porcen_sal = 0; var porcen_sal_ocult = 0; var saldo = 0; var t_proveedor_p = 0;
      
  $("#t_proveedor").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  $("#t_provee_porc").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  $("#porcnt_sald_p").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);
  
  $("#saldo_p").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  $("#monto_total_prob").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  //_____________________________________________________________________________________________________
  //_____________________________________________________________________________________________________

  var porcen_sal_d = 0; var porcen_sal_oclt = 0; var saldo_d = 0; var t_detaccion_miles = 0; t_mont_d = 0;

  $("#monto_total_detracc").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  $("#porcnt_detrcc").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  $("#porcnt_sald_d").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  $("#saldo_d").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  $("#t_detaccion").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  $("#t_detacc_porc").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  //_____________________________________________________________________________________________________
  //_____________________________________________________________________________________________________

  $.post("../ajax/servicio_maquina.php?op=suma_total_pagos_proveedor", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (e, status) {

    e = JSON.parse(e); console.log(e);   

    if (e.status == true) {

      $("#monto_total_prob").html(formato_miles(e.data.total_monto));

      $("#porcnt_prove").html(((e.data.total_monto * 100) / totattotal).toFixed(2) + " %");

      porcen_sal = (90 - (e.data.total_monto * 100) / totattotal).toFixed(2);

      porcen_sal_ocult = (90 - (e.data.total_monto * 100) / totattotal).toFixed(4);

      saldo = (e.data.total_monto * porcen_sal_ocult) / ((e.data.total_monto * 100) / totattotal);

      var saldoxmiles_p = formato_miles(saldo);

      $("#saldo_p").html(saldoxmiles_p);

      console.log("saldooooo " + saldoxmiles_p);

      $("#porcnt_sald_p").html(porcen_sal + " %");

      t_proveedor_p = (totattotal * 90) / 100;

      var totalxmiles_p = formato_miles(t_proveedor_p);

      $("#t_proveedor").html(totalxmiles_p);

      $("#t_provee_porc").html("90");

    } else {

      ver_errores(e);

    }

  }).fail( function(e) { ver_errores(e); } );

  $.post("../ajax/servicio_maquina.php?op=suma_total_pagos_detracc", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (e, status) {

    e = JSON.parse(e); console.log(e);   

    if (e.status == true) {

      t_mont_d = formato_miles(e.data.total_monto);

      $("#monto_total_detracc").html(t_mont_d);

      $("#porcnt_detrcc").html(((e.data.total_monto * 100) / totattotal).toFixed(2) + " %");

      porcen_sal_d = (10 - (e.data.total_monto * 100) / totattotal).toFixed(2);

      porcen_sal_oclt = (10 - (e.data.total_monto * 100) / totattotal).toFixed(4);

      saldo_d = (e.data.total_monto * porcen_sal_oclt) / ((e.data.total_monto * 100) / totattotal);

      var saldoxmiles = formato_miles(saldo_d);

      $("#saldo_d").html(saldoxmiles);

      $("#porcnt_sald_d").html(porcen_sal_d + " %");

      t_detaccion_miles = (totattotal * 10) / 100;

      var t_detaccion_t = formato_miles(t_detaccion_miles);

      $("#t_detaccion").html(t_detaccion_t);

      $("#t_detacc_porc").html("10");

    } else {

      ver_errores(e);

    }

 }).fail( function(e) { ver_errores(e); } );

}

//mostrar datos proveedor pago
function most_datos_prov_pago(idmaquinaria, idproyecto) {

  localStorage.setItem("nubeidmaquinaria", idmaquinaria);

  $("#h4_mostrar_beneficiario").html("");
  $("#id_maquinaria_pago").html("");
  $("#idproyecto_pago").val("");

  $("#banco_pago").val("").trigger("change");

  $.post("../ajax/servicio_maquina.php?op=most_datos_prov_pago", { idmaquinaria: idmaquinaria }, function (e, status) {

    e = JSON.parse(e); console.log(e);   

    if (e.status == true) {

      $("#banco_pago").val(e.data.idbancos).trigger("change");
      $("#idproyecto_pago").val(idproyecto);
      $("#id_maquinaria_pago").val(e.data.idmaquinaria);
      $("#maquinaria_pago").html(e.data.nombre);
      $("#beneficiario_pago").val(e.data.razon_social);
      $("#h4_mostrar_beneficiario").html(e.data.razon_social);
      $("#titular_cuenta_pago").val(e.data.titular_cuenta);
      localStorage.setItem("nube_c_b", e.data.cuenta_bancaria);
      localStorage.setItem("nube_c_d", e.data.cuenta_detracciones);

    } else {

      ver_errores(e);

    }

  }).fail( function(e) { ver_errores(e); } );
}

//captura_opicion tipopago
function captura_op() {

  cuenta_bancaria = localStorage.getItem("nube_c_b");

  cuenta_detracciones = localStorage.getItem("nube_c_d");

  $("#cuenta_destino_pago").val("");

  if ($("#tipo_pago").select2("val") == "Proveedor") {
    $("#cuenta_destino_pago").val("");
    $("#cuenta_destino_pago").val(cuenta_bancaria);
  }

  if ($("#tipo_pago").select2("val") == "Detraccion") {
    $("#cuenta_destino_pago").val("");
    $("#cuenta_destino_pago").val(cuenta_detracciones);
  }
}

//validando excedentes
function validando_excedentes() {

  var totattotal = localStorage.getItem("monto_total_p");

  var monto_total_dep = localStorage.getItem("monto_total_dep");

  var monto_entrada = $("#monto_pago").val();

  var total_suma = parseFloat(monto_total_dep) + parseFloat(monto_entrada);

  var debe = totattotal - monto_total_dep;

  //console.log(typeof total_suma);

  if (total_suma > totattotal) {

    toastr.error("ERROR monto excedido al total del monto a pagar!");

  } else {

    toastr.success("Monto Aceptado.");
  }
}

function mostrar_pagos(idpago_servicio, id_maquinaria) {
  limpiar_c_pagos();
  $("#h4_mostrar_beneficiario").html("");
  $("#id_maquinaria_pago").html("");
  $("#maquinaria_pago").html("");
  $("#idproyecto_pago").val("");

  $("#banco_pago").val("").trigger("change");
  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");

  $("#modal-agregar-pago").modal("show");

  $.post("../ajax/servicio_maquina.php?op=mostrar_pagos", { idpago_servicio: idpago_servicio }, function (data, status) {

    e = JSON.parse(e); console.log(e);   

    if (e.status == true) {

      $("#idproyecto_pago").val(e.data.idproyecto);
      $("#id_maquinaria_pago").val(e.data.id_maquinaria);
      $("#maquinaria_pago").html(e.data.nombre_maquina);
      $("#beneficiario_pago").val(e.data.beneficiario);
      $("#h4_mostrar_beneficiario").html(e.data.beneficiario);
      $("#cuenta_destino_pago").val(e.data.cuenta_destino);
      $("#banco_pago").val(e.data.id_banco).trigger("change");
      $("#titular_cuenta_pago").val(e.data.titular_cuenta);
      $("#forma_pago").val(e.data.forma_pago).trigger("change");
      $("#tipo_pago").val(e.data.tipo_pago).trigger("change");
      $("#fecha_pago").val(e.data.fecha_pago);
      $("#monto_pago").val(e.data.monto);
      $("#numero_op_pago").val(e.data.numero_operacion);
      $("#descripcion_pago").val(e.data.descripcion);
      $("#idpago_servicio").val(e.data.idpago_servicio);

      if (e.data.imagen == "" || e.data.imagen == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#doc1_nombre").html('');

        $("#doc_old_1").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_1").val(e.data.imagen); 

        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.imagen)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.imagen,'servicio_maquina', 'comprobante_pago', '100%', '210' ));       
            
      }

      $('.jq_image_zoom').zoom({ on:'grab' }); 

    } else {

      ver_errores(e);

    }

  }).fail( function(e) { ver_errores(e); } );
}

function eliminar_pagos(idservicio, idmaquinaria, numero_operacion) {

  crud_eliminar_papelera(
    "../ajax/servicio_maquina.php?op=desactivar_pagos",
    "../ajax/servicio_maquina.php?op=eliminar_pagos", 
    idservicio, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del> N° Operación-${numero_operacion} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ total_costo_parcial_detalle(idmaquinaria, localStorage.getItem("nube_idproyecto")); total_pagos(idmaquinaria, localStorage.getItem("nube_idproyecto"));  },
    function(){ tabla.ajax.reload(null, false); tabla3.ajax.reload(null, false); tabladetrecc.ajax.reload(null, false); },
    false, 
    false,
    false
  );

}

function ver_modal_vaucher(comprobante,numero_operacion) {

  $("#modal-ver-vaucher").modal("show");

  var dia_actual = moment().format('DD-MM-YYYY');

  $(".nombre_comprobante").html(`N° Operación-${numero_operacion}`);

  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'servicio_maquina', 'comprobante_pago', '100%', '550'));

  if (DocExist(`dist/docs/servicio_maquina/comprobante_pago/${comprobante}`) == 200) {
    $("#iddescargar").attr("href","../dist/docs/servicio_maquina/comprobante_pago/"+comprobante).attr("download", `${tipo}-${numero_comprobante}  - ${dia_actual}`).removeClass("disabled");
    $("#ver_completo").attr("href","../dist/docs/servicio_maquina/comprobante_pago/"+comprobante).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }
  $('.jq_image_zoom').zoom({ on:'grab' }); 
  $(".tooltip").removeClass("show").addClass("hidde");

}

function validar_forma_de_pago() {

  var forma_pago = $("#forma_pago").select2("val");

  if (forma_pago == null || forma_pago == "") {
    // no ejecutamos nada
    $(".validar_fp").show();
  } else {
    if (forma_pago == "Efectivo") {
      $(".validar_fp").hide();
      $("#tipo_pago").val("Proveedor").trigger("change");
      $("#banco_pago").val("1").trigger("change");
      $("#cuenta_destino_pago").val("");
      $("#titular_cuenta_pago").val("");
    } else {
      $(".validar_fp").show();
    }
  }
}

//-------------------------------------------------------------------------------
//----------------------S E C C   F A C T U R A S--------------------------------
//-------------------------------------------------------------------------------

function guardaryeditar_factura(e) {

  var formData = new FormData($("#form-agregar-factura")[0]);

  $.ajax({
    url: "../ajax/servicio_maquina.php?op=guardaryeditar_factura",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (e) {

      try {

        e = JSON.parse(e);  console.log(e); 

        if (e.status == true) {

          Swal.fire("Correcto!", "El registro se guardo correctamente.", "success");

          tabla4.ajax.reload(null, false);

          tabla.ajax.reload(null, false);
  
          $("#modal-agregar-factura").modal("hide");

          total_monto_f(localStorage.getItem("nubeidmaquif"), localStorage.getItem("nubeidproyectf"));

          limpiar_factura();

        }else{  

          ver_errores(e);

        } 
      } catch (err) {

        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      
      } 

    },

  });
}

function listar_facturas(idmaquinaria, idproyecto) {
  console.log(idmaquinaria, idproyecto);

  localStorage.setItem("nubeidmaquif", idmaquinaria);
  localStorage.setItem("nubeidproyectf", idproyecto);

  $("#tabla_principal").hide();
  $("#tabla_pagos").hide();
  $("#tabla_facturas_h").show();
  $("#btn-agregar").hide();
  $("#btn-regresar").show();
  $("#btn-pagar").hide();
  $("#btn-factura").show();

  tabla4 = $("#tabla_facturas").dataTable({

    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax: {
      url: "../ajax/servicio_maquina.php?op=listar_facturas&idmaquinaria=" + idmaquinaria + "&idproyecto=" + idproyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      // columna: P:U
      if (data[5] != '') {
        $("td", row).eq(5).addClass('text-nowrap text-right');
      }
      // columna: P:U
      if (data[6] != '') {
        $("td", row).eq(6).addClass('text-nowrap text-right');
      }
      // columna: P:U
      if (data[7] != '') {
        $("td", row).eq(7).addClass('text-nowrap text-right');
      }
    },
    language: {

      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();

  $("#idmaquina").val(idmaquinaria);
  $("#idproyectof").val(idproyecto);

  total_monto_f(idmaquinaria, idproyecto);
  total_costo_parcial(idmaquinaria, idproyecto);
}

//Calcular Igv y subtotal
function calcula_igv_subt() {

  var subtotal = 0;  var igv = 0;
  
  $("#subtotal").val("");

  $("#igv").val("");

  var val_igv = $('#val_igv').val();

  var monto = parseFloat($("#monto").val());

  if (monto=="" || monto==null) {

    $("#val_igv").val(""); 

    $("#tipo_gravada").val(""); 

    $("#subtotal").val("");

    $("#igv").val("");

  } else {

    subtotal =quitar_igv_del_precio(monto, val_igv, 'decimal');

    igv = monto - subtotal;

    $("#subtotal").val(subtotal.toFixed(2));

    $("#igv").val(igv.toFixed(2));

  }
}

function quitar_igv_del_precio(precio , igv, tipo ) {

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

//Función limpiar-factura
function limpiar_factura() {

  $("#codigo").val("");
  $("#monto").val("");
  $("#idfactura").val("");
  $("#fecha_emision").val("");
  $("#descripcion_f").val("Por concepto de alquiler de maquinaria");
  $("#subtotal").val("");
  $("#igv").val("");
  $("#tipo_gravada").val("");
  $("#nota").val("");
  
  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//mostrar
function mostrar_factura(idfactura) {

  limpiar_factura();

  $("#modal-agregar-factura").modal("show");

  $.post("../ajax/servicio_maquina.php?op=mostrar_factura", { idfactura: idfactura }, function (data, status) {

    e = JSON.parse(e); console.log(e);   

    if (e.status == true) {

      $("#idfactura").val(data.idfactura);
      $("#codigo").val(data.codigo);
      $("#monto").val(parseFloat(data.monto).toFixed(2));
      $("#fecha_emision").val(data.fecha_emision);
      $("#descripcion_f").val(data.descripcion);
      $("#subtotal").val(parseFloat(data.subtotal).toFixed(2));
      $("#igv").val(parseFloat(data.igv).toFixed(2));
      $("#val_igv").val(data.val_igv); 
      $("#tipo_gravada").val(data.tipo_gravada);
      $("#nota").val( data.nota);
      
      if (e.data.imagen == "" || e.data.imagen == null  ) {

        $("#doc2_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#doc2_nombre").html('');

        $("#doc_old_2").val(""); $("#doc2").val("");

      } else {

        $("#doc_old_2").val(e.data.imagen); 

        $("#doc2_nombre").html(`<div class="row"> <div class="col-md-22"><i>Baucher.${extrae_extencion(e.data.imagen)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc2_ver").html(doc_view_extencion(e.data.imagen,'servicio_maquina', 'comprobante_servicio', '100%', '210' ));       
            
      }

      $('.jq_image_zoom').zoom({ on:'grab' }); 

    } else {

      ver_errores(e);

    }

  }).fail( function(e) { ver_errores(e); } );
}

function eliminar_factura(idfactura, numero_fac) {

  crud_eliminar_papelera(
    "../ajax/servicio_maquina.php?op=desactivar_factura",
    "../ajax/servicio_maquina.php?op=eliminar_factura", 
    idfactura, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del> N° Fectura - ${numero_fac} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){  total_monto_f(localStorage.getItem("nubeidmaquif"),localStorage.getItem("nubeidproyectf")); },
    function(){ tabla.ajax.reload(null, false); tabla4.ajax.reload(null, false);},
    false, 
    false,
    false
  );

}

function ver_modal_factura(comprobante,numero_operacion) {

  $("#modal-ver-factura").modal("show");

  var dia_actual = moment().format('DD-MM-YYYY');

  $(".nombre_comprobante_f").html(`N° Operación-${numero_operacion}`);

  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'servicio_maquina', 'comprobante_servicio', '100%', '550'));

  if (DocExist(`dist/docs/servicio_maquina/comprobante_servicio/${comprobante}`) == 200) {
    $("#iddescargar_f").attr("href","../dist/docs/servicio_maquina/comprobante_servicio/"+comprobante).attr("download", `${tipo}-${numero_comprobante}  - ${dia_actual}`).removeClass("disabled");
    $("#ver_completo_f").attr("href","../dist/docs/servicio_maquina/comprobante_servicio/"+comprobante).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }
  $('.jq_image_zoom').zoom({ on:'grab' }); 
  $(".tooltip").removeClass("show").addClass("hidde");
}

//-total Pagos
function total_monto_f(idmaquinaria, idproyecto) {

  $("#monto_total_f").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  $.post("../ajax/servicio_maquina.php?op=total_monto_f", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (e, status) {

    e = JSON.parse(e); console.log(e);   

    if (e.status == true) {     

      $("#monto_total_f").html('S/ '+formato_miles(e.data.total_mont_f));

    } else {

      ver_errores(e);

    }

  }).fail( function(e) { ver_errores(e); } );
}

//-Mostral total monto
function total_costo_parcial(idmaquinaria, idproyecto) {
  
  $("#total_costo").html(`<i class="fas fa-spinner fa-pulse fa-sm"></i>`);

  $.post("../ajax/servicio_maquina.php?op=total_costo_parcial", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (e, status) {
   
    e = JSON.parse(e); console.log(e);   

    if (e.status == true) {  

    $("#total_costo").html('S/ '+formato_miles(e.data.costo_parcial));

    } else {

      ver_errores(e);

    }

  }).fail( function(e) { ver_errores(e); } );
}

//========FIN=================
init();

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
/**=======form-servicios============ */
$(function () {

  $.validator.setDefaults({ submitHandler: function (e) { guardaryeditar(e); }, });

  // Aplicando la validacion del select cada vez que cambie
  $("#maquinaria").on("change", function () { $(this).trigger("blur"); });
  $("#unidad_m").on("change", function () { $(this).trigger("blur"); });

  $("#form-servicios").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      maquinaria: { required: true },
      fecha_inicio: { required: true },
      fecha_fin: { minlength: 1 },
      horometro_inicial: { required: true, minlength: 1 },
      horometro_final: { minlength: 1 },
      costo_unitario: { minlength: 1 },
      unidad_m: { required: true },
      descripcion: { minlength: 1 },
      // terms: { required: true },
    },
    messages: {
      maquinaria: { required: "Por favor selecione una maquina",},
      fecha_inicio: { required: "Por favor ingrese fecha inicial", },
      horometro_inicial: { required: "Por favor ingrese horometro inicial", },
      unidad_m: { required: "Por favor seleccione un tipo de unidad", },
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
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#maquinaria").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#unidad_m").rules("add", { required: true, messages: { required: "Campo requerido" } });

});

/**=======form-servicios-pago============ */
$(function () {

  $.validator.setDefaults({
    submitHandler: function (e) {
      guardaryeditar_pago(e);
    },
  });

  // Aplicando la validacion del select cada vez que cambie
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_pago").on("change", function () { $(this).trigger("blur"); });
  $("#banco_pago").on("change", function () { $(this).trigger("blur"); });

  $("#form-servicios-pago").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {      
      forma_pago: { required: true },
      tipo_pago: { required: true },
      banco_pago: { required: true },
      fecha_pago: { required: true },
      monto_pago: { required: true },
      numero_op_pago: { minlength: 1 },
      descripcion_pago: { minlength: 1 },
      titular_cuenta_pago: { minlength: 1 },
      // terms: { required: true },
    },
    messages: {
      //====================
      forma_pago: { required: "Por favor selecione una forma de pago", },
      tipo_pago: { required: "Por favor selecione un tipo de pago", },
      banco_pago: { required: "Por favor selecione un banco", },
      fecha_pago: { required: "Por favor ingresar una fecha", },
      monto_pago: { required: "Por favor ingresar el monto a pagar", },
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
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#banco_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });

});

/**=======form-agregar-factura============*/
$(function () {

  $.validator.setDefaults({
    submitHandler: function (e) {
      guardaryeditar_factura(e);
      //console.log('factura 22222');
    },
  });

  $("#form-agregar-factura").validate({
    
    rules: {
      codigo: { required: true },
      monto: { required: true },
      fecha_emision: { required: true },
      descripcion_f: { minlength: 1 },
      foto2_i: { required: true },
      val_igv: { required: true, number: true, min:0, max:1 },
      // terms: { required: true },
    },
    messages: {
      //====================
      forma_pago: { codigo: "Por favor ingresar el código", },
      monto: { required: "Por favor ingresar el monto", },
      fecha_emision: { required: "Por favor ingresar la fecha de emisión", },
      val_igv: { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
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
  });
  
});

// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_documento").val();

  let dni_ruc = $("#num_documento").val();

  if (tipo_doc == "DNI") {
    if (dni_ruc.length == "8") {
      $.post("../ajax/persona.php?op=reniec", { dni: dni_ruc }, function (data, status) {
        data = JSON.parse(data);

        console.log(data);

        if (data.success == false) {
          $("#search").show();

          $("#charge").hide();

          toastr.error("Es probable que el sistema de busqueda esta en mantenimiento o los datos no existe en la RENIEC!!!");
        } else {
          $("#search").show();

          $("#charge").hide();

          $("#nombre").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

          toastr.success("Cliente encontrado!!!!");
        }
      });
    } else {
      $("#search").show();

      $("#charge").hide();

      toastr.info("Asegurese de que el DNI tenga 8 dígitos!!!");
    }
  } else {
    if (tipo_doc == "RUC") {
      if (dni_ruc.length == "11") {
        $.post("../ajax/persona.php?op=sunat", { ruc: dni_ruc }, function (data, status) {
          data = JSON.parse(data);

          console.log(data);
          if (data.success == false) {
            $("#search").show();

            $("#charge").hide();

            toastr.error("Datos no encontrados en la SUNAT!!!");
          } else {
            if (data.estado == "ACTIVO") {
              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);

              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);
              // $("#direccion").val(data.direccion);
              toastr.success("Cliente encontrado");
            } else {
              toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);

              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);

              // $("#direccion").val(data.direccion);
            }
          }
        });
      } else {
        $("#search").show();

        $("#charge").hide();

        toastr.info("Asegurese de que el RUC tenga 11 dígitos!!!");
      }
    } else {
      if (tipo_doc == "CEDULA" || tipo_doc == "OTRO") {
        $("#search").show();

        $("#charge").hide();

        toastr.info("No necesita hacer consulta");
      } else {
        $("#tipo_doc").addClass("is-invalid");

        $("#search").show();

        $("#charge").hide();

        toastr.error("Selecione un tipo de documento");
      }
    }
  }
}

function extrae_extencion(filename) {
  return filename.split(".").pop();
}
//quietar formato
function quitar_formato_miles(numero) {
  let inVal = numero.replace(/,/g, "");
  return inVal;
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
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/servicio_maquina/${carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
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
  
                      $("#doc"+id+"_ver").html(`<img src="../dist/docs/servicio_maquina/${carpeta}/${antiguopdf}" alt="" onerror="this.src='../dist/svg/error-404-x.svg';" width="100%" >`);
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

                    $("#doc"+id+"_ver").html(`<img src="${pdffile_url}" alt="" width="100%" >`);
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
