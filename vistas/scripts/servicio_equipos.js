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

  $("#lEquipo").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  //Mostramos los BANCOS
  $.post("../ajax/servicio_equipos.php?op=select2Banco", function (r) { $("#banco_pago").html(r); });

  //Mostramos los maquinariaes
  $.post("../ajax/servicio_equipos.php?op=select2_servicio", function (r) {  $("#maquinaria").html(r); });  

  listar(localStorage.getItem("nube_idproyecto"));
  
  //=====Guardar Servicio=============
  $("#guardar_registro").on("click", function (e) { $("#submit-form-servicios").submit(); });

  //=====Guardar pago=============
  $("#guardar_registro_pago").on("click", function (e) { $("#submit-form-pago").submit(); });

  //=====Guardar factura=============
  $("#guardar_registro_factura").on("click", function (e) { $("#submit-form-factura").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();

  //============SERVICIO================
  //Initialize Select2 Elements
  $("#maquinaria").select2({
    theme: "bootstrap4",
    placeholder: "Selecione maquinaria",
    allowClear: true,
  });

  //Initialize Select2 Elements
  $("#unidad_m").select2({
    theme: "bootstrap4",
    placeholder: "Selecione una unidad de medida",
    allowClear: false,
  });

  //============pagoo================
  //Initialize Select2 Elements
  $("#forma_pago").select2({
    theme: "bootstrap4",
    placeholder: "Selecione una forma de pago",
    allowClear: true,
  });

  //Initialize Select2 Elements
  $("#tipo_pago").select2({
    theme: "bootstrap4",
    placeholder: "Selecione un tipo de pago",
    allowClear: true,
  });

  //Initialize Select2 Elements
  $("#banco_pago").select2({
    theme: "bootstrap4",
    placeholder: "Selecione un banco",
    allowClear: true,
  });
}

// abrimos el navegador de archivos --vaucher
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addDocs(e,$("#doc1").attr("id")) });

// abrimos el navegador de archivos -- factura
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


function seleccion() {
  if ($("#maquinaria").select2("val") == null) {
    $("#maquinaria_validar").show(); //console.log($("#maquinaria").select2("val") + ", "+ $("#maquinaria_old").val());
  } else {
    $("#maquinaria_validar").hide();
  }
}

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
    $("#fecha-i-tutulo").html('Fecha: <b style="color: red;"> - </b>');
    $("#cantidad").val("0");
    // $("#fecha_inicio").val("");

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
    //$("#costo_parcial").removeAttr("readonly");
    $("#fecha_fin").attr("readonly", true);
    /**======= */

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
    $("#fecha-i-tutulo").html('Fecha: <b style="color: red;"> - </b>');
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
    // $("#costo_parcial").removeAttr("readonly").focus().val();
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
    $("#fecha-i-tutulo").html('Fecha: <b style="color: red;"> - </b>');
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
      /*console.log('horas '+horas);
      console.log('costo_unitario '+costo_unitario);
      console.log('costo_parcial '+costo_parcial);*/
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
      //var x = document.getElementById("fecha");
      let date = new Date(x.replace(/-+/g, "/"));

      let options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
      };
      $("#fecha-i-tutulo").html('Fecha: <b style="color: red;">' + date.toLocaleDateString("es-MX", options) + "</b>");
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

        $("#fecha-i-tutulo").html('Fecha: <b style="color: red;">' + nombrefecha1 + "</b>");

        $("#fecha_fi").html('Fecha Fin: <b style="color: red;">' + nombrefecha2 + "</b>");
      } else {
        toastr.error("Seleccionar la fecha inicial o la cantidad!!!");

        $("#fecha_fin").val("");

        $("#fecha-i-tutulo").html("");
        $("#fecha_fi").html("");
      }
    }
  } else {
    $("#fecha-i-tutulo").html('Fecha: <b style="color: red;"> - </b>');
  }
}

//Funcion para los nombres de la día mes y año
function diaSemana(fecha) {
  if (fecha != "") {
    let date = new Date(fecha.replace(/-+/g, "/"));

    let options = {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric",
    };

    return date.toLocaleDateString("es-MX", options);
  } else {
    return "";
  }
}

//Función limpiar
function limpiar() {
  //Mostramos los proveedores
  $.post("../ajax/servicio_equipos.php?op=select2_servicio", function (r) {
    $("#maquinaria").html(r);
  });

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
  $("#sssss").val("");
  $("#nomb_maq").val("");
  $("#descripcion").val("");
  $("#sssss").show();
  $("#nomb_maq").hide();
  $("#cantidad").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

//Función limpiar
function limpiar_c_pagos() {

  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");
  $("#monto_pago").val("");
  $("#numero_op_pago").val("");
  $("#cuenta_destino_pago").val("");
  $("#descripcion_pago").val("");
  $("#idpago_servicio").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
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

/**
 * ================================================
                  SECCION SERVICIOS 
  =================================================
 */
//Función Listar
function listar(nube_idproyecto) {
  tabla = $("#tabla-servicio").dataTable({
    responsive: true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax: {
      url: "../ajax/servicio_equipos.php?op=listar&nube_idproyecto=" + nube_idproyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      //console.log(data);
      // columna: total
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
      lengthMenu: "Mostrar : _MENU_ registros",
      buttons: {
        copyTitle: "Tabla Copiada",
        copySuccess: {
          _: "%d líneas copiadas",
          1: "1 línea copiada",
        },
      },
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();
}

//Función detalles po maquina
function listar_detalle(idmaquinaria, idproyecto, unidad_medida) {
  var hideen_colums;
  //console.log('lis_deta '+idproyecto,idmaquinaria,unidad_medida);
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
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax: {
      url: "../ajax/servicio_equipos.php?op=ver_detalle_maquina&idmaquinaria=" + idmaquinaria + "&idproyecto=" + idproyecto,
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
      lengthMenu: "Mostrar : _MENU_ registros",
      buttons: {
        copyTitle: "Tabla Copiada",
        copySuccess: {
          _: "%d líneas copiadas",
          1: "1 línea copiada",
        },
      },
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: hideen_colums,
  }).DataTable();

  suma_horas_costoparcial(idmaquinaria, localStorage.getItem("nube_idproyecto"));
}

//Mostrar datos
function mostrar_datos_pago(idmaquinaria, idproyecto) {
  // console.log('qqqqqq  '+idmaquinaria,idproyecto);
  $.post("../ajax/servicio_equipos.php?op=mostrar", { idmaquinaria: idmaquinaria }, function (data, status) {
    data = JSON.parse(data);
    console.log(data);
  });
}

//Función para guardar o editar
function suma_horas_costoparcial(idmaquinaria, idproyecto) {
  console.log("..." + idmaquinaria, idproyecto);
  //suma
  $.post("../ajax/servicio_equipos.php?op=suma_horas_costoparcial", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (data, status) {
    // $("#horas-total").html("");
    $("#costo-parcial").html("");
    data = JSON.parse(data);
    //console.log(data);
    // tabla.ajax.reload();
    // $("#horas-total").html(data.horas);
    num = data.costo_parcial;
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
    costo_parcial = (sign ? "" : "-") + num + "." + cents;
    $("#costo-parcial").html('S/. '+costo_parcial);
  });
}

//Guardar y editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-servicios")[0]);

  $.ajax({
    url: "../ajax/servicio_equipos.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      if (datos == "ok") {
        toastr.success("servicio registrado correctamente");

        tabla.ajax.reload();

        $("#modal-agregar-servicio").modal("hide");
        // console.log(tabla2);
        tabla2.ajax.reload();
        var idmaquinaria = $("#maquinaria").val();
        if (idmaquinaria != "") {
          suma_horas_costoparcial(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        }
        limpiar();
      } else {
        toastr.error(datos);
      }
    },
  });
}

//mostrar
function mostrar(idservicio) {
  limpiar();

  $("#maquinaria").val("").trigger("change");
  $("#unidad_m").val("").trigger("change");
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  $("#cantidad").val("");
  $("#modal-agregar-servicio").modal("show");
  $("#sssss").hide();
  $("#nomb_maq").show();
  $("#costo_unitario").attr("readonly", false);
  $.post("../ajax/servicio_equipos.php?op=mostrar", { idservicio: idservicio }, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();
    $("#nomb_maq").val(data.nombre_maquina + " - " + data.codigo_maquina + " --> " + data.razon_social);
    $("#idservicio").val(data.idservicio);
    $("#maquinaria").val(data.idmaquinaria).trigger("change");
    $("#unidad_m").val(data.unidad_medida).trigger("change");
    $("#fecha_inicio").val(data.fecha_entrega);
    $("#fecha_fin").val(data.fecha_recojo);
    $("#horometro_inicial").val(data.horometro_inicial);
    $("#horometro_final").val(data.horometro_final);
    $("#horas").val(data.horas);
    $("#cantidad").val(data.cantidad);
    $("#descripcion").val(data.descripcion);
    $("#dias").val(data.dias_uso);
    $("#mes").val(data.meses_uso);
    $("#costo_unitario").val(data.costo_unitario);
    $("#costo_adicional").val(data.costo_adicional);
    $("#costo_parcial").val(data.costo_parcial);
  });
}

//Función para desactivar registros
function eliminar(idservicio, idmaquinaria) {

  Swal.fire({

    title: "!Elija una opción¡",
    html: "En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!",
    icon: "warning",
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonColor: "#17a2b8",
    denyButtonColor: "#d33",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `<i class="fas fa-times"></i> Papelera`,
    denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,

  }).then((result) => {

    if (result.isConfirmed) {
       //op=desactivar
      $.post("../ajax/servicio_equipos.php?op=desactivar", { idservicio: idservicio }, function (e) {
        Swal.fire("Desactivado!", "Servicio ha sido desactivado.", "success");
        suma_horas_costoparcial(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        tabla.ajax.reload();
        tabla2.ajax.reload();
      });

    }else if (result.isDenied) {
      //op=eliminar

      $.post("../ajax/servicio_equipos.php?op=eliminar", { idservicio: idservicio }, function (e) {
        Swal.fire("Eliminado!", "Servicio ha sido Eliminado.", "success");
        suma_horas_costoparcial(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        tabla.ajax.reload();
        tabla2.ajax.reload();
      });


    }

  });

}

//Función para activar registros
function activar(idservicio, idmaquinaria) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  Servicio?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/servicio_equipos.php?op=activar", { idservicio: idservicio }, function (e) {
        Swal.fire("Activado!", "Servicio ha sido activado.", "success");
        suma_horas_costoparcial(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        tabla.ajax.reload();
        tabla2.ajax.reload();
      });
    }
  });
}

/**
 * ================================================
            SECCION PAGOS SERVICIOS
  =================================================
 */
//Guardar y editar
function guardaryeditar_pago(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-servicios-pago")[0]);

  $.ajax({
    url: "../ajax/servicio_equipos.php?op=guardaryeditar_pago",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      if (datos == "ok") {
        toastr.success("servicio registrado correctamente");

        tabla.ajax.reload();

        $("#modal-agregar-pago").modal("hide");
        // console.log(tabla2);
        //tabla2.ajax.reload();
        tabla3.ajax.reload();
        tabladetrecc.ajax.reload();
        /**================================================== */
        total_pagos(localStorage.getItem("nubeidmaquinaria"), localStorage.getItem("nube_idproyecto"));
        total_costo_secc_pagoss(localStorage.getItem("nubeidmaquinaria"), localStorage.getItem("nube_idproyecto"));
        limpiar_c_pagos();
      } else {
        toastr.error(datos);
      }
    },
  });
}

//Listar pagos.
function listar_pagos(idmaquinaria, idproyecto, costo_parcial, monto) {
  //console.log(idmaquinaria,idproyecto,costo_parcial);
  localStorage.setItem("monto_total_p", costo_parcial);
  localStorage.setItem("monto_total_dep", monto);
  //console.log('::::->'+idmaquinaria,idproyecto);
  //most_datos_prov_pago(idmaquinaria);
  var proveedor = "Proveedor";
  var detraccion = "Detraccion";
  $("#tabla_principal").hide();
  $("#tabla_pagos").show();
  $("#btn-agregar").hide();
  $("#btn-regresar").show();
  $("#btn-pagar").show();

  tabla3 = $("#tabla-pagos-proveedor").dataTable({
    responsive: true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax: {
      url: "../ajax/servicio_equipos.php?op=listar_pagos_proveedor&idmaquinaria=" + idmaquinaria + "&idproyecto=" + idproyecto,
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
      lengthMenu: "Mostrar : _MENU_ registros",
      buttons: {
        copyTitle: "Tabla Copiada",
        copySuccess: {
          _: "%d líneas copiadas",
          1: "1 línea copiada",
        },
      },
    },
    bDestroy: true,
    iDisplayLength: 5, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();

  tabladetrecc = $("#tabla-pagos-detrecciones").dataTable({
    responsive: true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax: {
      url: "../ajax/servicio_equipos.php?op=listar_pagos_detraccion&idmaquinaria=" + idmaquinaria + "&idproyecto=" + idproyecto,
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
      lengthMenu: "Mostrar : _MENU_ registros",
      buttons: {
        copyTitle: "Tabla Copiada",
        copySuccess: {
          _: "%d líneas copiadas",
          1: "1 línea copiada",
        },
      },
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
  $("#total_costo_secc_pagos").html("");
  $.post("../ajax/servicio_equipos.php?op=total_costo_parcial_pago", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (data, status) {
    data = JSON.parse(data); //console.log(data);
    num = data.costo_parcial;
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
    costo_parcial = (sign ? "" : "-") + num + "." + cents;
    $("#total_costo_secc_pagos").html('S/. '+formato_miles(costo_parcial));
  });
  // monto_total_p=1000;
}

//-total Pagos
function total_pagos(idmaquinaria, idproyecto) {
  var totattotal = localStorage.getItem("monto_total_p");
  console.log(typeof totattotal);
  console.log(totattotal);

  //------

  $.post("../ajax/servicio_equipos.php?op=suma_total_pagos_proveedor", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (data, status) {
    var porcen_sal = 0;
    var porcen_sal_ocult = 0;
    var saldo = 0;
    var t_proveedor_p = 0;
    $("#t_proveedor").html("");
    $("#t_provee_porc").html("");

    $("#porcnt_sald_p").html("");
    $("#saldo_p").html("");

    $("#monto_total_prob").html("");

    data = JSON.parse(data);
    //console.log(data);
    $("#monto_total_prob").html(formato_miles(data.total_monto));
    $("#porcnt_prove").html(((data.total_monto * 100) / totattotal).toFixed(2) + " %");

    porcen_sal = (90 - (data.total_monto * 100) / totattotal).toFixed(2);
    porcen_sal_ocult = (90 - (data.total_monto * 100) / totattotal).toFixed(4);

    saldo = (data.total_monto * porcen_sal_ocult) / ((data.total_monto * 100) / totattotal);
    var saldoxmiles_p = formato_miles(saldo);
    $("#saldo_p").html(saldoxmiles_p);
    console.log("saldooooo " + saldoxmiles_p);
    //console.log('saldo---'+typeof((data.total_monto*porcen_sal).toFixed(2)/((data.total_monto*100)/totattotal).toFixed(2)));
    $("#porcnt_sald_p").html(porcen_sal + " %");

    t_proveedor_p = (totattotal * 90) / 100;
    var totalxmiles_p = formato_miles(t_proveedor_p);
    $("#t_proveedor").html(totalxmiles_p);
    $("#t_provee_porc").html("90");
  });
  // console.log('idmaquinaria: '+idmaquinaria,'idproyecto '+idproyecto);
  $.post("../ajax/servicio_equipos.php?op=suma_total_pagos_detracc", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (data, status) {
    var porcen_sal_d = 0;
    var porcen_sal_oclt = 0;
    var saldo_d = 0;
    var t_detaccion_miles = 0;
    t_mont_d = 0;
    $("#monto_total_detracc").html("");
    $("#porcnt_detrcc").html("");

    $("#porcnt_sald_d").html("");
    $("#saldo_d").html("");

    $("#t_detaccion").html("");
    $("#t_detacc_porc").html("");

    data = JSON.parse(data);
    //console.log(data);
    t_mont_d = formato_miles(data.total_monto);
    $("#monto_total_detracc").html(t_mont_d);
    $("#porcnt_detrcc").html(((data.total_monto * 100) / totattotal).toFixed(2) + " %");

    porcen_sal_d = (10 - (data.total_monto * 100) / totattotal).toFixed(2);
    porcen_sal_oclt = (10 - (data.total_monto * 100) / totattotal).toFixed(4);
    //console.log('porcen_sal_oclt '+porcen_sal_oclt);
    saldo_d = (data.total_monto * porcen_sal_oclt) / ((data.total_monto * 100) / totattotal);
    var saldoxmiles = formato_miles(saldo_d);
    //console.log('saldoxmiles '+saldoxmiles);

    $("#saldo_d").html(saldoxmiles);
    $("#porcnt_sald_d").html(porcen_sal_d + " %");

    t_detaccion_miles = (totattotal * 10) / 100;
    var t_detaccion_t = formato_miles(t_detaccion_miles);

    $("#t_detaccion").html(t_detaccion_t);
    $("#t_detacc_porc").html("10");
  });
  //totattotal=0;
}

//mostrar datos proveedor pago
function most_datos_prov_pago(idmaquinaria, idproyecto) {
  localStorage.setItem("nubeidmaquinaria", idmaquinaria);

  $("#h4_mostrar_beneficiario").html("");
  $("#id_maquinaria_pago").html("");
  $("#idproyecto_pago").val("");

  $("#banco_pago").val("").trigger("change");
  $.post("../ajax/servicio_equipos.php?op=most_datos_prov_pago", { idmaquinaria: idmaquinaria }, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

    $("#idproyecto_pago").val(idproyecto);
    $("#id_maquinaria_pago").val(data.idmaquinaria);
    $("#maquinaria_pago").html(data.nombre);
    $("#beneficiario_pago").val(data.razon_social);
    $("#h4_mostrar_beneficiario").html(data.razon_social);
    $("#banco_pago").val(data.idbancos).trigger("change");
    $("#titular_cuenta_pago").val(data.titular_cuenta);
    localStorage.setItem("nube_c_b", data.cuenta_bancaria);
    localStorage.setItem("nube_c_d", data.cuenta_detracciones);
  });
}

//captura_opicion tipopago
function captura_op() {
  cuenta_bancaria = localStorage.getItem("nube_c_b");
  cuenta_detracciones = localStorage.getItem("nube_c_d");
  //console.log(cuenta_bancaria,cuenta_detracciones);
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

//mostrar
function mostrar_pagos(idpago_servicio, id_maquinaria) {
  limpiar_c_pagos();
  $("#h4_mostrar_beneficiario").html("");
  $("#id_maquinaria_pago").html("");
  $("#maquinaria_pago").html("");
  $("#idproyecto_pago").val("");
  $("#modal-agregar-pago").modal("show");
  $("#banco_pago").val("").trigger("change");
  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");

  $.post("../ajax/servicio_equipos.php?op=mostrar_pagos", { idpago_servicio: idpago_servicio }, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

    $("#idproyecto_pago").val(data.idproyecto);
    $("#id_maquinaria_pago").val(data.id_maquinaria);
    $("#maquinaria_pago").html(data.nombre_maquina);
    $("#beneficiario_pago").val(data.beneficiario);
    $("#h4_mostrar_beneficiario").html(data.beneficiario);
    $("#cuenta_destino_pago").val(data.cuenta_destino);
    $("#banco_pago").val(data.id_banco).trigger("change");
    $("#titular_cuenta_pago").val(data.titular_cuenta);
    $("#forma_pago").val(data.forma_pago).trigger("change");
    $("#tipo_pago").val(data.tipo_pago).trigger("change");
    $("#fecha_pago").val(data.fecha_pago);
    $("#monto_pago").val(data.monto);
    $("#numero_op_pago").val(data.numero_operacion);
    $("#descripcion_pago").val(data.descripcion);
    $("#idpago_servicio").val(data.idpago_servicio);

    /**-------------------------*/
    if (data.imagen == "" || data.imagen == null  ) {

      $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

      $("#doc1_nombre").html('');

      $("#doc_old_1").val(""); $("#doc1").val("");

    } else {

      $("#doc_old_1").val(data.imagen); 

      $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(data.imagen)}</i></div></div>`);
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.imagen) == "pdf" ) {

        $("#doc1_ver").html('<iframe src="../dist/docs/servicio_equipo/comprobante_pago/'+data.imagen+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

      }else{
        if (
          extrae_extencion(data.imagen) == "jpeg" || extrae_extencion(data.imagen) == "jpg" || extrae_extencion(data.imagen) == "jpe" ||
          extrae_extencion(data.imagen) == "jfif" || extrae_extencion(data.imagen) == "gif" || extrae_extencion(data.imagen) == "png" ||
          extrae_extencion(data.imagen) == "tiff" || extrae_extencion(data.imagen) == "tif" || extrae_extencion(data.imagen) == "webp" ||
          extrae_extencion(data.imagen) == "bmp" || extrae_extencion(data.imagen) == "svg" ) {

          $("#doc1_ver").html(`<img src="../dist/docs/servicio_equipo/comprobante_pago/${data.imagen}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
    }
    

  });
}

//Función para desactivar registros
function desactivar_pagos(idpago_servicio, idmaquinaria) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  el servicio?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/servicio_equipos.php?op=desactivar_pagos", { idpago_servicio: idpago_servicio }, function (e) {
        Swal.fire("Desactivado!", "Servicio ha sido desactivado.", "success");
        suma_horas_costoparcial(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        //Función para activar registros
        total_pagos(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        tabla.ajax.reload();
        tabla3.ajax.reload();
        tabladetrecc.ajax.reload();
      });
    }
  });
}

function activar_pagos(idpago_servicio, idmaquinaria) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  Servicio?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/servicio_equipos.php?op=activar_pagos", { idpago_servicio: idpago_servicio }, function (e) {
        Swal.fire("Activado!", "Servicio ha sido activado.", "success");
        suma_horas_costoparcial(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        //Función para activar registros
        total_pagos(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        tabla.ajax.reload();
        tabla3.ajax.reload();
        tabladetrecc.ajax.reload();
      });
    }
  });
}

//Función para desactivar registros
function eliminar_pagos(idpago_servicio, idmaquinaria) {

  Swal.fire({

    title: "!Elija una opción¡",
    html: "En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!",
    icon: "warning",
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonColor: "#17a2b8",
    denyButtonColor: "#d33",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `<i class="fas fa-times"></i> Papelera`,
    denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,

  }).then((result) => {

    if (result.isConfirmed) {

       //op=desactivar_pagos

      $.post("../ajax/servicio_equipos.php?op=desactivar_pagos", { idpago_servicio: idpago_servicio }, function (e) {
        Swal.fire("Desactivado!", "Servicio ha sido desactivado.", "success");
        suma_horas_costoparcial(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        //Función para activar registros
        total_pagos(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        tabla.ajax.reload();
        tabla3.ajax.reload();
        tabladetrecc.ajax.reload();
      });

    }else if (result.isDenied) {
      //op=eliminar_pagos

      $.post("../ajax/servicio_equipos.php?op=eliminar_pagos", { idpago_servicio: idpago_servicio }, function (e) {
        Swal.fire("Eliminado!", "Pago servicio ha sido Eliminado.", "success");
        suma_horas_costoparcial(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        //Función para activar registros
        total_pagos(idmaquinaria, localStorage.getItem("nube_idproyecto"));
        tabla.ajax.reload();
        tabla3.ajax.reload();
        tabladetrecc.ajax.reload();
      });

    }

  });
}

function ver_modal_vaucher(imagen) {
  $("#img-vaucher").attr("src", "");
  $("#modal-ver-vaucher").modal("show");
  $("#img-vaucher").attr("src", "../dist/docs/servicio_equipo/comprobante_pago/" + imagen);
  $("#descargar").attr("href", "../dist/docs/servicio_equipo/comprobante_pago/" + imagen);

  // $(".tooltip").removeClass('show');
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

/**
 * =================================
 *         SECCIÒN FACTURAS
 * ================================
 */
//Guardar y editar
function guardaryeditar_factura(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-agregar-factura")[0]);

  $.ajax({
    url: "../ajax/servicio_equipos.php?op=guardaryeditar_factura",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      if (datos == "ok") {
        toastr.success("servicio registrado correctamente");

        tabla4.ajax.reload();
        tabla.ajax.reload();

        $("#modal-agregar-factura").modal("hide");
        total_monto_f(localStorage.getItem("nubeidmaquif"), localStorage.getItem("nubeidproyectf"));
        limpiar_factura();
      } else {
        toastr.error(datos);
      }
    },
  });
}

function listar_facturas(idmaquinaria, idproyecto) {
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
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax: {
      url: "../ajax/servicio_equipos.php?op=listar_facturas&idmaquinaria=" + idmaquinaria + "&idproyecto=" + idproyecto,
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
      lengthMenu: "Mostrar : _MENU_ registros",
      buttons: {
        copyTitle: "Tabla Copiada",
        copySuccess: {
          _: "%d líneas copiadas",
          1: "1 línea copiada",
        },
      },
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
  var subtotal = 0;
  var igv = 0;
  $("#subtotal").val("");
  $("#igv").val("");
  var monto = parseFloat($("#monto").val());

  subtotal = monto / 1.18;
  igv = monto - subtotal;
  $("#subtotal").val(subtotal.toFixed(2));
  $("#igv").val(igv.toFixed(2));
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
  $("#nota").val("");
  
  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");
}

//mostrar
function mostrar_factura(idfactura) {
  limpiar_factura();
  $("#modal-agregar-factura").modal("show");

  $.post("../ajax/servicio_equipos.php?op=mostrar_factura", { idfactura: idfactura }, function (data, status) {
    data = JSON.parse(data); //console.log(data);

    $("#idfactura").val(data.idfactura);
    $("#codigo").val(data.codigo);
    $("#monto").val(parseFloat(data.monto).toFixed(2));
    $("#fecha_emision").val(data.fecha_emision);
    $("#descripcion_f").val(data.descripcion);
    $("#subtotal").val(parseFloat(data.subtotal).toFixed(2));
    $("#igv").val(parseFloat(data.igv).toFixed(2));
    $("#nota").val(data.nota);

    /**-------------------------*/
    if (data.imagen == "" || data.imagen == null  ) {

      $("#doc2_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

      $("#doc2_nombre").html('');

      $("#doc_old_2").val(""); $("#doc1").val("");

    } else {

      $("#doc_old_2").val(data.imagen); 

      $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(data.imagen)}</i></div></div>`);
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.imagen) == "pdf" ) {

        $("#doc2_ver").html('<iframe src="../dist/docs/servicio_equipo/comprobante_servicio/'+data.imagen+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

      }else{
        if (
          extrae_extencion(data.imagen) == "jpeg" || extrae_extencion(data.imagen) == "jpg" || extrae_extencion(data.imagen) == "jpe" ||
          extrae_extencion(data.imagen) == "jfif" || extrae_extencion(data.imagen) == "gif" || extrae_extencion(data.imagen) == "png" ||
          extrae_extencion(data.imagen) == "tiff" || extrae_extencion(data.imagen) == "tif" || extrae_extencion(data.imagen) == "webp" ||
          extrae_extencion(data.imagen) == "bmp" || extrae_extencion(data.imagen) == "svg" ) {

          $("#doc2_ver").html(`<img src="../dist/docs/servicio_equipo/comprobante_servicio/${data.imagen}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc2_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
    }
  });
}

//Función para desactivar registros
function eliminar_factura(idfactura) {

  Swal.fire({

    title: "!Elija una opción¡",
    html: "En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!",
    icon: "warning",
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonColor: "#17a2b8",
    denyButtonColor: "#d33",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `<i class="fas fa-times"></i> Papelera`,
    denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,

  }).then((result) => {

    if (result.isConfirmed) {
       //op=desactivar_factura
       $.post("../ajax/servicio_equipos.php?op=desactivar_factura", { idfactura: idfactura }, function (e) {
        Swal.fire("Desactivado!", "Servicio ha sido desactivado.", "success");
        // total_pagos(idmaquinaria,localStorage.getItem('nube_idproyecto'));
        total_monto_f(localStorage.getItem("nubeidmaquif"), localStorage.getItem("nubeidproyectf"));
        tabla4.ajax.reload();
      });

    }else if (result.isDenied) {
      //op=eliminar_factura
      $.post("../ajax/servicio_equipos.php?op=eliminar_factura", { idfactura: idfactura }, function (e) {
        Swal.fire("Eliminado!", "Servicio ha sido Eliminado.", "success");
        // total_pagos(idmaquinaria,localStorage.getItem('nube_idproyecto'));
        total_monto_f(localStorage.getItem("nubeidmaquif"), localStorage.getItem("nubeidproyectf"));
        tabla4.ajax.reload();
      });

    }

  });

}

function activar_factura(idfactura) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  Servicio?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/servicio_equipos.php?op=activar_factura", { idfactura: idfactura }, function (e) {
        Swal.fire("Activado!", "Servicio ha sido activado.", "success");

        //total_pagos(idmaquinaria,localStorage.getItem('nube_idproyecto'));
        total_monto_f(localStorage.getItem("nubeidmaquif"), localStorage.getItem("nubeidproyectf"));
        tabla4.ajax.reload();
      });
    }
  });
}

function ver_modal_factura(imagen) {
  var img = imagen;
  var extencion = img.substr(img.length - 3); // => "1"
  //console.log(extencion);
  $("#ver_fact_pdf").html("");
  $("#img-factura").attr("src", "");
  $("#modal-ver-factura").modal("show");

  if (extencion == "jpeg" || extencion == "jpg" || extencion == "png" || extencion == "webp") {
    $("#ver_fact_pdf").hide();
    $("#img-factura").show();
    $("#img-factura").attr("src", "../dist/docs/servicio_equipo/comprobante_servicio/" + img);

    $("#iddescargar").attr("href", "../dist/docs/servicio_equipo/comprobante_servicio/" + img);
  } else {
    $("#img-factura").hide();
    $("#ver_fact_pdf").show();
    $("#ver_fact_pdf").html('<iframe src="../dist/docs/servicio_equipo/comprobante_servicio/' + img + '" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');
    $("#iddescargar").attr("href", "../dist/docs/servicio_equipo/comprobante_servicio/" + img);
  }

  // $(".tooltip").removeClass('show');
}

//-total Pagos
function total_monto_f(idmaquinaria, idproyecto) {

  $("#monto_total_f").html("00.0");

  $.post("../ajax/servicio_equipos.php?op=total_monto_f", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (data, status) {
    
    data = JSON.parse(data);  // console.log(data);     

    $("#monto_total_f").html('S/. '+formato_miles(data.total_mont_f));
  });
}

//-Mostral total monto
function total_costo_parcial(idmaquinaria, idproyecto) {
  
  $("#total_costo").html("00.0");

  $.post("../ajax/servicio_equipos.php?op=total_costo_parcial", { idmaquinaria: idmaquinaria, idproyecto: idproyecto }, function (data, status) {
   
    data = JSON.parse(data); // console.log(data);   
     
    $("#total_costo").html('S/. '+formato_miles(data.costo_parcial));
  });
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

$(function () {
  $.validator.setDefaults({
    submitHandler: function (e) {
      if ($("#maquinaria").select2("val") == null) {
        $("#maquinaria_validar").show(); //console.log($("#proveedor").select2("val") + ", "+ $("#proveedor_old").val());
        // console.log('holaaa""2222');
      } else {
        $("#maquinaria_validar").hide();

        guardaryeditar(e);
      }
    },
  });

  $("#form-servicios").validate({
    rules: {
      maquinaria: { required: true },
      fecha_inicio: { required: true },
      fecha_fin: { minlength: 1 },
      horometro_inicial: { required: true, minlength: 1 },
      horometro_final: { minlength: 1 },
      costo_unitario: { minlength: 1 },
      unidad_m: { required: true },
      descripcion: { minlength: 1 },
      //=======SECCION PAGO=========
      // terms: { required: true },
    },
    messages: {
      maquinaria: {
        required: "Por favor selecione una maquina",
      },

      fecha_inicio: {
        required: "Por favor ingrese fecha inicial",
      },
      horometro_inicial: {
        required: "Por favor ingrese horometro inicial",
      },
      unidad_m: {
        required: "Por favor seleccione un tipo de unidad",
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

      if ($("#maquinaria").select2("val") == null) {
        $("#maquinaria_validar").show(); //console.log($("#maquinaria").select2("val") + ", "+ $("#maquinaria_old").val());
      } else {
        $("#maquinaria_validar").hide();
      }
    },
  });

  /**=======pagos */
  $.validator.setDefaults({
    submitHandler: function (e) {
      guardaryeditar_pago(e);
    },
  });

  $("#form-servicios-pago").validate({
    rules: {
      //=======SECCION PAGO=========
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
      forma_pago: {
        required: "Por favor selecione una forma de pago",
      },
      tipo_pago: {
        required: "Por favor selecione un tipo de pago",
      },
      banco_pago: {
        required: "Por favor selecione un banco",
      },
      fecha_pago: {
        required: "Por favor ingresar una fecha",
      },
      monto_pago: {
        required: "Por favor ingresar el monto a pagar",
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
  /**=======Factura */
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

      // terms: { required: true },
    },
    messages: {
      //====================
      forma_pago: {
        codigo: "Por favor ingresar el código",
      },
      monto: {
        required: "Por favor ingresar el monto",
      },
      fecha_emision: {
        required: "Por favor ingresar la fecha de emisión",
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
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/servicio_equipo/${carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
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
  
                      $("#doc"+id+"_ver").html(`<img src="../dist/docs/servicio_equipo/${carpeta}/${antiguopdf}" alt="" onerror="this.src='../dist/svg/error-404-x.svg';" width="100%" >`);
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
