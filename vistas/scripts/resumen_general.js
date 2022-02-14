var tabla;
var tabla1;
var tabla2;

//Función que se ejecuta al inicio
function init() {

  // Tablas de resumen
  listar_r_compras(localStorage.getItem("nube_idproyecto"));
  listar_r_serv_maquinaria(localStorage.getItem("nube_idproyecto"));
  listar_r_serv_equipos(localStorage.getItem("nube_idproyecto"));
  listar_r_transportes(localStorage.getItem("nube_idproyecto"));
  listar_r_hospedajes(localStorage.getItem("nube_idproyecto"));
  listar_r_comidas_extras(localStorage.getItem("nube_idproyecto"));
  listar_r_breaks(localStorage.getItem("nube_idproyecto"));
  listar_r_pensiones(localStorage.getItem("nube_idproyecto"));
  listar_r_trab_administrativo(localStorage.getItem("nube_idproyecto"));
  listar_r_trabajador_obrero(localStorage.getItem("nube_idproyecto"));

  //Activamos el "aside"
  $("#mresumen_general").addClass("active");

  //Mostramos los trabajadores
  $.post("../ajax/resumen_general.php?op=select2_trabajadores&idproyecto=" + localStorage.getItem("nube_idproyecto"), function (r) {
    $("#trabajador_filtro").html(r);
  });

  //Mostramos los proveedores
  $.post("../ajax/resumen_general.php?op=select2_proveedores", function (r) {
    $("#proveedor_filtro").html(r);
  });

  //Initialize Select2 filtrar_por
  $("#deuda_filtro").select2({
    theme: "bootstrap4",
    placeholder: "Selecionar",
    allowClear: true,
  });

  //Initialize Select2 trabajador
  $("#trabajador_filtro").select2({
    theme: "bootstrap4",
    placeholder: "Selecionar trabajador",
    allowClear: true,
  });

  //Initialize Select2 proveedor
  $("#proveedor_filtro").select2({
    theme: "bootstrap4",
    placeholder: "Selecionar proveedor",
    allowClear: true,
  });

  //============borramos los valores================
  // $("#filtrar_por").val("null").trigger("change");
  // $("#trabajador").val("null").trigger("change");
  // $("#proveedor").val("null").trigger("change");

  // Formato para telefono
  // $("[data-mask]").inputmask();
}

function listar_r_compras(idproyecto) {
  var compras = "";
  var t_monto = 0;
  var t_pagos = 0;
  var t_saldo = 0;
  var calculando_sldo = 0;
  var validando_pago = 0;
  var pintar_celda = "";
  $("#compras").html("");
  $("#monto_compras").html("");
  $("#pago_compras").html("");
  $("#saldo_compras").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_compras", { idproyecto: idproyecto }, function (data, status) {
    data = JSON.parse(data); //console.log(data); console.log('........................');

    data.forEach((value, index) => {
      if (value.monto_pago_total != null) {
        calculando_sldo = parseFloat(value.monto_total) - parseFloat(value.monto_pago_total);
        validando_pago = parseFloat(value.monto_pago_total);
      } else {
        calculando_sldo = 0;
        validando_pago = 0;
      }

      if (calculando_sldo == 0) {
        pintar_celda = "";
      } else {
        pintar_celda = "bg-red-resumen";
      }

      compras = `<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index + 1}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${value.proveedor}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${format_d_m_a(value.fecha_compra)}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading">         
          <textarea cols="30" rows="1" class="text_area_clss" readonly > ${value.descripcion == "" ? "---" : value.descripcion}</textarea>
          </td>
          <td class="bg-color-b4bdbe47 text-center clas_pading"><button class="btn btn-info btn-xs" onclick="ver_detalle_compras(${value.idcompra_proyecto})"><i class="fa fa-eye"></i></button></td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(parseFloat(value.monto_total).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading ${pintar_celda}">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto = t_monto + parseFloat(value.monto_total);
      t_pagos = t_pagos + parseFloat(validando_pago);
      t_saldo = t_saldo + parseFloat(calculando_sldo);

      $("#compras").append(compras);
    });

    $("#monto_compras").html(formato_miles(t_monto.toFixed(2)));
    $("#pago_compras").html(formato_miles(t_pagos.toFixed(2)));
    $("#saldo_compras").html(formato_miles(t_saldo.toFixed(2)));

    $("#tabla1_compras")
      .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "pdf"],
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
      })
      .DataTable();
  });
}

//mostramos el detalle del comprobante de la compras
function ver_detalle_compras(idcompra_proyecto) {
  $("#modal-ver-compras").modal("show");

  $.post("../ajax/compra.php?op=ver_compra", { idcompra_proyecto: idcompra_proyecto }, function (data, status) {
    data = JSON.parse(data); //  console.log(data);
    $(".idproveedor").html("");
    $(".fecha_compra").val("");
    $(".tipo_comprovante").html("");
    $(".serie_comprovante").val("");
    $(".descripcion").val("");

    $(".subtotal").html("");
    $(".igv_comp").html("");
    $(".total").html("");

    if (data.tipo_comprovante == "Factura") {
      $(".igv").val("0.18");
      $(".content-igv").show();
      $(".content-t-comprob").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
      $(".content-descrp").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      $(".content-comprob").show();
    } else if (data.tipo_comprovante == "Boleta" || data.tipo_comprovante == "Nota_de_venta") {
      $(".igv").val("");
      $(".content-comprob").show();
      $(".content-igv").hide();
      $(".content-t-comprob").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");

      $(".content-descrp").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
    } else if (data.tipo_comprovante == "Ninguno") {
      $(".content-comprob").hide();
      $(".content-comprob").val("");
      $(".content-igv").hide();
      $(".content-t-comprob").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
      $(".content-descrp").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
    } else {
      $(".content-comprob").show();
      //$(".content-descrp").removeClass("col-lg-7").addClass("col-lg-4");
    }

    //<!--idproveedor,fecha_compra,tipo_comprovante,serie_comprovante,igv,descripcion, igv_comp, total-->
    $(".idproveedor").html(data.razon_social);
    $(".fecha_compra").val(format_d_m_a(data.fecha_compra));
    $(".tipo_comprovante").html(data.tipo_comprovante);
    $(".serie_comprovante").val(data.serie_comprovante);
    //$(".igv").val(data.descripcion);
    $(".descripcion").val(data.descripcion);

    $(".subtotal").html(data.subtotal_compras);
    $(".igv_comp").html(data.igv_compras_proyect);
    $(".total").html(data.monto_total);
  });

  $.post("../ajax/compra.php?op=ver_detalle_compras&id_compra=" + idcompra_proyecto, function (r) {
    $("#detalles_compra").html(r);
  });
}

function listar_r_serv_maquinaria(idproyecto) {
  var serv_maquinaria = "";
  var t_monto = 0;
  var t_pagos = 0;
  var t_saldo = 0;
  var calculando_sldo = 0;
  var validando_pago = 0;
  var pintar_celda = "";
  $("#serv_maquinas").html("");
  $("#monto_serv_maq").html("");
  $("#pago_serv_maq").html("");
  $("#saldo_serv_maq").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_serv_maquinaria", { idproyecto: idproyecto }, function (data, status) {
    data = JSON.parse(data); //console.log(data);

    data.forEach((value, index) => {
      if (value.monto_pag_ser_maq != null) {
        calculando_sldo = parseFloat(value.costo_parcial) - parseFloat(value.monto_pag_ser_maq);
        validando_pago = parseFloat(value.monto_pag_ser_maq);
      } else {
        calculando_sldo = 0;
        validando_pago = 0;
      }

      if (calculando_sldo == 0) {
        pintar_celda = "";
      } else {
        pintar_celda = "bg-red-resumen";
      }

      serv_maquinaria = `<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index + 1}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${value.proveedor}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading">--</td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >--</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading"><button class="btn btn-info btn-xs" onclick="ver_detalle(${value.idmaquinaria},${value.idproyecto},'Servicio Maquinaria:','${
        value.proveedor
      }')"><i class="fa fa-eye"></i></button></td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(parseFloat(value.costo_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading ${pintar_celda}">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto = t_monto + parseFloat(value.costo_parcial);
      t_pagos = t_pagos + parseFloat(validando_pago);
      t_saldo = t_saldo + parseFloat(calculando_sldo);

      $("#serv_maquinas").append(serv_maquinaria);
    });

    $("#monto_serv_maq").html(formato_miles(t_monto.toFixed(2)));
    $("#pago_serv_maq").html(formato_miles(t_pagos.toFixed(2)));
    $("#saldo_serv_maq").html(formato_miles(t_saldo.toFixed(2)));

    $("#tabla2_maquinaria")
      .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "pdf"],
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
      })
      .DataTable();
  });
}

function listar_r_serv_equipos(idproyecto) {
  var serv_equipos = "";
  var t_monto = 0;
  var t_pagos = 0;
  var t_saldo = 0;
  var calculando_sldo = 0;
  var validando_pago = 0;
  var pintar_celda = "";
  $("#serv_equipos").html("");
  $("#monto_serv_equi").html("");
  $("#pago_serv_equi").html("");
  $("#saldo_serv_equi").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_serv_equipos", { idproyecto: idproyecto }, function (data, status) {
    data = JSON.parse(data); //console.log(data);

    data.forEach((value, index) => {
      if (value.monto_pag_ser_maq != null) {
        calculando_sldo = parseFloat(value.costo_parcial) - parseFloat(value.monto_pag_ser_maq);
        validando_pago = parseFloat(value.monto_pag_ser_maq);
      } else {
        calculando_sldo = 0;
        validando_pago = 0;
      }
      if (calculando_sldo == 0) {
        pintar_celda = "";
      } else {
        pintar_celda = "bg-red-resumen";
      }

      serv_equipos = `<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index + 1}</td>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${value.proveedor}</td>
          <td class="bg-color-b4bdbe47  clas_pading">--</td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >--</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading"><button class="btn btn-info btn-xs" onclick="ver_detalle(${value.idmaquinaria},${value.idproyecto},'Servicio Equipo:','${
        value.proveedor
      }')"><i class="fa fa-eye"></i></button></td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(parseFloat(value.costo_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading  ${pintar_celda}">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto = t_monto + parseFloat(value.costo_parcial);
      t_pagos = t_pagos + parseFloat(validando_pago);
      t_saldo = t_saldo + parseFloat(calculando_sldo);

      $("#serv_equipos").append(serv_equipos);
    });

    $("#monto_serv_equi").html(formato_miles(t_monto.toFixed(2)));
    $("#pago_serv_equi").html(formato_miles(t_pagos.toFixed(2)));
    $("#saldo_serv_equi").html(formato_miles(t_saldo.toFixed(2)));

    $("#tabla3_equipo")
      .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "pdf"],
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
      })
      .DataTable();
  });
}
//Función detalles por maquina-equipo
function ver_detalle(idmaquinaria, idproyecto, servicio, proveedor) {
  $("#nombre_proveedor_").html("");

  $("#modal_ver_detalle_maq_equ").modal("show");

  $("#detalle_").html(servicio);
  $("#nombre_proveedor_").html(proveedor);

  tabla2 = $("#tabla-detalle-m")
    .dataTable({
      responsive: true,
      lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
      ajax: {
        url: "../ajax/resumen_general.php?op=ver_detalle_maquina&idmaquinaria=" + idmaquinaria + "&idproyecto=" + idproyecto,
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
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
      order: [[0, "desc"]], //Ordenar (columna,orden)
    })
    .DataTable();
}

function listar_r_transportes(idproyecto) {
  var transportes = "";
  var t_monto = 0;
  var t_pagos = 0;
  var t_saldo = 0;
  var calculando_sldo = 0;
  var validando_pago = 0;
  var comprobante = "";
  var pintar_celda = "";
  $("#transportes").html("");
  $("#monto_transp").html("");
  $("#pago_transp").html("");
  $("#saldo_transp").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_transportes", { idproyecto: idproyecto }, function (data, status) {
    data = JSON.parse(data); // console.log(data);

    data.forEach((value, index) => {
      if (value.precio_parcial != null) {
        calculando_sldo = parseFloat(value.precio_parcial) - parseFloat(value.precio_parcial);
        validando_pago = parseFloat(value.precio_parcial);
      } else {
        calculando_sldo = 0;
        validando_pago = 0;
      }

      if (calculando_sldo == 0) {
        pintar_celda = "";
      } else {
        pintar_celda = "bg-red-resumen";
      }

      if (value.comprobante != "") {
        comprobante = `<a target="_blank"  href="../dist/img/comprob_transporte/${value.comprobante}"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>`;
      } else {
        comprobante = `<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>`;
      }

      transportes = `<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index + 1}</td>
          <td class="bg-color-b4bdbe47  text-center clas_pading">--</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${format_d_m_a(value.fecha_viaje)}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >${value.descripcion == "" ? "---" : value.descripcion}</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">${comprobante}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(parseFloat(value.precio_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading  ${pintar_celda}">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto = t_monto + parseFloat(value.precio_parcial);
      t_pagos = t_pagos + parseFloat(validando_pago);
      t_saldo = t_saldo + parseFloat(calculando_sldo);

      $("#transportes").append(transportes);
    });

    $("#monto_transp").html(formato_miles(t_monto.toFixed(2)));
    $("#pago_transp").html(formato_miles(t_pagos.toFixed(2)));
    $("#saldo_transp").html(formato_miles(t_saldo.toFixed(2)));

    $("#tabla4_transporte")
      .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "pdf"],
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
      })
      .DataTable();
  });
}

function listar_r_hospedajes(idproyecto) {
  var hospedajes = "";
  var t_monto = 0;
  var t_pagos = 0;
  var t_saldo = 0;
  var calculando_sldo = 0;
  var validando_pago = 0;
  var comprobante = "";
  var pintar_celda = "";
  $("#hospedaje").html("");
  $("#monto_hosped").html("");
  $("#pago_hosped").html("");
  $("#saldo_hosped").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_hospedajes", { idproyecto: idproyecto }, function (data, status) {
    data = JSON.parse(data); //console.log(data);

    data.forEach((value, index) => {
      if (value.precio_parcial != null) {
        calculando_sldo = parseFloat(value.precio_parcial) - parseFloat(value.precio_parcial);
        validando_pago = parseFloat(value.precio_parcial);
      } else {
        calculando_sldo = 0;
        validando_pago = 0;
      }

      if (calculando_sldo == 0) {
        pintar_celda = "";
      } else {
        pintar_celda = "bg-red-resumen";
      }

      if (value.comprobante != "") {
        comprobante = `<a target="_blank"  href="../dist/img/comprob_hospedajes/${value.comprobante}"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>`;
      } else {
        comprobante = `<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>`;
      }

      hospedajes = `<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index + 1}</td>
          <td class="bg-color-b4bdbe47  text-center clas_pading">--</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${format_d_m_a(value.fecha_comprobante)}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >${value.descripcion == "" ? "---" : value.descripcion}</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">${comprobante}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(parseFloat(value.precio_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading  ${pintar_celda}">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto = t_monto + parseFloat(value.precio_parcial);
      t_pagos = t_pagos + parseFloat(validando_pago);
      t_saldo = t_saldo + parseFloat(calculando_sldo);

      $("#hospedaje").append(hospedajes);
    });

    $("#monto_hosped").html(formato_miles(t_monto.toFixed(2)));
    $("#pago_hosped").html(formato_miles(t_pagos.toFixed(2)));
    $("#saldo_hosped").html(formato_miles(t_saldo.toFixed(2)));

    $("#tabla5_hospedaje")
      .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "pdf"],
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
      })
      .DataTable();
  });
}

function listar_r_comidas_extras(idproyecto) {
  var comidas_extras = "";
  var t_monto = 0;
  var t_pagos = 0;
  var t_saldo = 0;
  var calculando_sldo = 0;
  var validando_pago = 0;
  var comprobante = "";
  var pintar_celda = "";
  $("#comida_extra").html("");
  $("#monto_cextra").html("");
  $("#pago_cextra").html("");
  $("#saldo_cextra").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_comidas_extras", { idproyecto: idproyecto }, function (data, status) {
    data = JSON.parse(data); //console.log(data);

    data.forEach((value, index) => {
      if (value.costo_parcial != null) {
        calculando_sldo = parseFloat(value.costo_parcial) - parseFloat(value.costo_parcial);
        validando_pago = parseFloat(value.costo_parcial);
      } else {
        calculando_sldo = 0;
        validando_pago = 0;
      }

      if (calculando_sldo == 0) {
        pintar_celda = "";
      } else {
        pintar_celda = "bg-red-resumen";
      }

      if (value.comprobante != "") {
        comprobante = `<a target="_blank"  href="../dist/img/comidas_extras/${value.comprobante}"> <i class="far fa-file-pdf" style="font-size: 23px;"></i></a>`;
      } else {
        comprobante = `<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>`;
      }

      comidas_extras = `<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index + 1}</td>
          <td class="bg-color-b4bdbe47  text-center clas_pading">--</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${format_d_m_a(value.fecha_comida)}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading">
            <textarea cols="30" rows="1" class="text_area_clss" readonly >${value.descripcion == "" ? "---" : value.descripcion}</textarea> 
          </td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">${comprobante}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(parseFloat(value.costo_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading ${pintar_celda}">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;

      t_monto = t_monto + parseFloat(value.costo_parcial);
      t_pagos = t_pagos + parseFloat(validando_pago);
      t_saldo = t_saldo + parseFloat(calculando_sldo);

      $("#comida_extra").append(comidas_extras);
    });

    $("#monto_cextra").html(formato_miles(t_monto.toFixed(2)));
    $("#pago_cextra").html(formato_miles(t_pagos.toFixed(2)));
    $("#saldo_cextra").html(formato_miles(t_saldo.toFixed(2)));

    $("#tabla6_comidas_ex")
      .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "pdf"],
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
      })
      .DataTable();
  });
}

function listar_r_breaks(idproyecto) {
  var compras = "";
  var t_monto = 0;
  var t_pagos = 0;
  var t_saldo = 0;
  var calculando_sldo = 0;
  var validando_pago = 0;
  var comprobante = "";
  var pintar_celda = "";
  $("#breaks").html("");
  $("#monto_break").html("");
  $("#pago_break").html("");
  $("#saldo_break").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_breaks", { idproyecto: idproyecto }, function (data, status) {
    data = JSON.parse(data); //console.log(data);

    data.forEach((value, index) => {
      if (value.total != null) {
        calculando_sldo = parseFloat(value.total) - parseFloat(value.total);
        validando_pago = parseFloat(value.total);
      } else {
        calculando_sldo = 0;
        validando_pago = 0;
      }
      if (calculando_sldo == 0) {
        pintar_celda = "";
      } else {
        pintar_celda = "bg-red-resumen";
      }

      if (value.idsemana_break != "") {
        comprobante = `<a target="_blank"  href="../dist/img/comidas_extras/${value.idsemana_break}"> <i class="far fa-file-pdf" style="font-size: 23px;"></i></a>`;
      } else {
        comprobante = `<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>`;
      }

      breaks = `<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index + 1}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>--</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${format_d_m_a(value.fecha_inicial)} - <br> ${format_d_m_a(value.fecha_final)} </span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >--</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">
            <button class="btn btn-info btn-sm" onclick="listar_comprobantes_breaks(${value.idsemana_break})"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
          </td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(parseFloat(value.total).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading ${pintar_celda}">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;

      t_monto = t_monto + parseFloat(value.total);
      t_pagos = t_pagos + parseFloat(validando_pago);
      t_saldo = t_saldo + parseFloat(calculando_sldo);

      $("#breaks").append(breaks);
    });

    $("#monto_break").html(formato_miles(t_monto.toFixed(2)));
    $("#pago_break").html(formato_miles(t_pagos.toFixed(2)));
    $("#saldo_break").html(formato_miles(t_saldo.toFixed(2)));

    $("#tabla7_breaks")
      .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "pdf"],
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
      })
      .DataTable();
  });
}

function listar_comprobantes_breaks(idsemana_break) {
  $("#modal_ver_breaks").modal("show");

  tabla1 = $("#t-comprobantes")
    .dataTable({
      responsive: true,
      lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
      ajax: {
        url: "../ajax/resumen_general.php?op=listar_comprobantes_breaks&idsemana_break=" + idsemana_break,
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
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
    })
    .DataTable();
}

function listar_r_pensiones(idproyecto) {
  var compras = "";
  var t_monto = 0;
  var t_pagos = 0;
  var t_saldo = 0;
  var calculando_sldo = 0;
  var validando_pago = 0;
  var comprobante = "";
  var pintar_celda = "";
  $("#pension").html("");
  $("#monto_pension").html("");
  $("#pago_pension").html("");
  $("#saldo_pension").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_pensiones", { idproyecto: idproyecto }, function (data, status) {
    data = JSON.parse(data); //console.log(data);

    data.forEach((value, index) => {
      if (value.monto_total_pension != null) {
        calculando_sldo = parseFloat(value.monto_total_pension) - parseFloat(value.pago_total_pension);
        validando_pago = parseFloat(value.pago_total_pension);
      } else {
        calculando_sldo = 0;
        validando_pago = 0;
      }
      if (calculando_sldo == 0) {
        pintar_celda = "";
      } else {
        pintar_celda = "bg-red-resumen";
      }

      pension = `<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index + 1}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>Semana ${value.proveedor}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>--</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >--</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">
            <button class="btn btn-info btn-sm" onclick="ver_detalle_x_servicio_p(${value.idpension})"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
            <button class="btn btn-info btn-sm" onclick="listar_comprobantes_pension(${value.idpension})"><i class="far fa-file-pdf fa-lg btn-info nav-icon"></i></button>
          </td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(parseFloat(value.monto_total_pension).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading ${pintar_celda}">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;

      t_monto = t_monto + parseFloat(value.monto_total_pension);
      t_pagos = t_pagos + parseFloat(validando_pago);
      t_saldo = t_saldo + parseFloat(calculando_sldo);

      $("#pension").append(pension);
    });

    $("#monto_pension").html(formato_miles(t_monto.toFixed(2)));
    $("#pago_pension").html(formato_miles(t_pagos.toFixed(2)));
    $("#saldo_pension").html(formato_miles(t_saldo.toFixed(2)));

    $("#tabla8_pension")
      .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "pdf"],
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
      })
      .DataTable();
  });
}
//Función ver detalles Detalles
function ver_detalle_x_servicio_p(idpension) {
  //console.log(numero_semana,nube_idproyecto);
  $("#modal-ver-detalle-semana").modal("show");
  tabla_detalle_s = $("#tabla-detalles-semanal")
    .dataTable({
      responsive: true,
      lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
      ajax: {
        url: "../ajax/resumen_general.php?op=ver_detalle_x_servicio&idpension=" + idpension,
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
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
    })
    .DataTable();
}

function listar_comprobantes_pension(idpension) {
  $("#modal-ver-comprobantes_pension").modal("show");

  tabla2 = $("#t-comprobantes-pension")
    .dataTable({
      responsive: true,
      lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
      ajax: {
        url: "../ajax/resumen_general.php?op=listar_comprobantes_pension&idpension=" + idpension,
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
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
      order: [[0, "desc"]], //Ordenar (columna,orden)
    })
    .DataTable();
}

function listar_r_trab_administrativo(idproyecto) {
  var compras = "";
  var t_monto = 0;
  var t_pagos = 0;
  var t_saldo = 0;
  var calculando_sldo = 0;
  var validando_pago = 0;
  var total_montos_x_meses = "";
  var pintar_celda = "";
  $("#administrativo").html("");
  $("#monto_adm").html("");
  $("#pago_adm").html("");
  $("#saldo_adm").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_trab_administrativo", { idproyecto: idproyecto }, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

    data.forEach((value, index) => {
      if (value.total_montos_x_meses != null) {
        calculando_sldo = parseFloat(value.total_montos_x_meses) - parseFloat(value.pago_total_adm);
        validando_pago = parseFloat(value.pago_total_adm);
        total_montos_x_meses = parseFloat(value.total_montos_x_meses);
      } else {
        calculando_sldo = 0;
        validando_pago = 0;
        total_montos_x_meses = 0;
      }
      if (calculando_sldo == 0) {
        pintar_celda = "";
      } else {
        pintar_celda = "bg-red-resumen";
      }

      administrativo = `<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index + 1}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${value.nombres}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>--</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >--</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">
            <button class="btn btn-info btn-sm" onclick="ver_detalle_pagos_x_trab_adm(${value.idtrabajador_por_proyecto},'${value.nombres}')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
          </td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(total_montos_x_meses.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading ${pintar_celda}">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;

      t_monto = t_monto + parseFloat(total_montos_x_meses);
      t_pagos = t_pagos + parseFloat(validando_pago);
      t_saldo = t_saldo + parseFloat(calculando_sldo);

      $("#administrativo").append(administrativo);
    });

    $("#monto_adm").html(formato_miles(t_monto.toFixed(2)));
    $("#pago_adm").html(formato_miles(t_pagos.toFixed(2)));
    $("#saldo_adm").html(formato_miles(t_saldo.toFixed(2)));

    $("#tabla9_per_adm")
      .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "pdf"],
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
      })
      .DataTable();
  });
}
//DETALLES DE PAGOS ADMINISTRADORES
function ver_detalle_pagos_x_trab_adm(idtrabajador_por_proyecto, nombres) {
  detalle = "";
  var sueldo_estimado = 0;
  var depositos = 0;

  $("#modal-ver-detalle-t-administ").modal("show");

  $(".data-detalle-pagos-administador").html("");
  $("#nombre_trabajador_detalle").html(nombres);

  $.post("../ajax/resumen_general.php?op=ver_detalle_pagos_x_trab_adms", { idtrabajador_por_proyecto: idtrabajador_por_proyecto }, function (data, status) {
    data = JSON.parse(data); //console.log(data);
    $(".sueldo_estimado").html("");
    $(".depositos").html("");

    if (data.length != 0) {
      $(".alerta").hide();
      $(".tabla").show();
      data.forEach((value, index) => {
        detalle = `<tr>
                  <td>${index + 1}</td>
                  <td>${value.nombre_mes}</td>
                  <td>${format_d_m_a(value.fecha_inicial)}</td>
                  <td>${format_d_m_a(value.fecha_final)}</td>
                  <td>${value.cant_dias_laborables}</td>
                  <td style="text-align: end !important;">S/. ${formato_miles(parseFloat(value.monto_x_mes).toFixed(2))}</td>
                  <td style="text-align: end !important;">S/. ${formato_miles(parseFloat(value.return_monto_pago).toFixed(2))}</td>
              </tr>`;

        $(".data-detalle-pagos-administador").append(detalle);
        sueldo_estimado += parseFloat(value.monto_x_mes);
        console.log(value.return_monto_pago);
        depositos += parseFloat(value.return_monto_pago);
      });

      $(".sueldo_estimado").html("S/. " + formato_miles(sueldo_estimado));
      $(".depositos").html("S/. " + formato_miles(depositos));
    } else {
      $(".tabla").hide();
      $(".alerta").show();
    }
  });
}

function listar_r_trabajador_obrero(idproyecto) {
  var obrero = "";
  var t_monto = 0;
  var t_pagos = 0;
  var t_saldo = 0;
  var calculando_sldo = 0;
  var validando_pago = 0;
  var pago_quincenal = "";
  var pintar_celda = "";
  $("#obrero").html("");
  $("#monto_obrero").html("");
  $("#pago_obrero").html("");
  $("#saldo_obrero").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_trabajador_obrero", { idproyecto: idproyecto }, function (data, status) {
    console.log("obrero");
    data = JSON.parse(data);
    console.log(data);

    data.forEach((value, index) => {
      if (value.pago_quincenal != null) {
        calculando_sldo = parseFloat(value.pago_quincenal) - parseFloat(value.total_deposito_obrero);
        validando_pago = parseFloat(value.total_deposito_obrero);
        pago_quincenal = parseFloat(value.pago_quincenal);
      } else {
        calculando_sldo = 0;
        validando_pago = 0;
        pago_quincenal = 0;
      }
      if (calculando_sldo == 0) {
        pintar_celda = "";
      } else {
        pintar_celda = "bg-red-resumen";
      }

      obrero = `<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index + 1}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${value.nombres}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>--</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >--</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">
            <button class="btn btn-info btn-sm" onclick="ver_detalle_pagos_x_trab_obrero(${value.idtrabajador_por_proyecto},'${value.nombres}')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
          </td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(pago_quincenal.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading ${pintar_celda}">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;

      t_monto = t_monto + parseFloat(pago_quincenal);
      t_pagos = t_pagos + parseFloat(validando_pago);
      t_saldo = t_saldo + parseFloat(calculando_sldo);

      $("#obrero").append(obrero);
    });

    $("#monto_obrero").html(formato_miles(t_monto.toFixed(2)));
    $("#pago_obrero").html(formato_miles(t_pagos.toFixed(2)));
    $("#saldo_obrero").html(formato_miles(t_saldo.toFixed(2)));

    $("#tabla10_per_obr")
      .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "pdf"],
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
      })
      .DataTable();
  });
}

//DETALLES DE PAGOS OBRERO
function ver_detalle_pagos_x_trab_obrero(idtrabajador_por_proyecto, nombres) {
  detalle = "";
  var pago_parcial_hn = 0;
  var pago_parcial_he = 0;
  var saldo = 0;
  var sabatical = 0;
  var pago_quincenal = 0;
  var adicional_descuento = 0;
  var deposito = 0;
  var total_hn_he = 0;
  $("#modal-ver-detalle-t-obrero").modal("show");

  $(".detalle-data-q-s").html("");
  $("#nombre_trabajador_ob_detalle").html(nombres);

  $.post("../ajax/resumen_general.php?op=ver_detalle_pagos_x_trab_obrero", { idtrabajador_por_proyecto: idtrabajador_por_proyecto }, function (data, status) {
    //obrero
    data = JSON.parse(data);
    console.log(data);

    $(".total_hn_he").html("");
    $(".total_sabatical").html("");
    $(".total_monto_hn_he").html("");
    $(".total_descuento").html("");
    $(".total_quincena").html("");
    $(".total_deposito").html("");
    $(".total_saldo").html("");

    if (data.length != 0) {
      $(".alerta_obrero").hide();
      $(".tabla_obrero").show();
      data.forEach((value, index) => {
        detalle = `<tr>
                  <td>${index + 1}</td>
                  <td>${value.numero_q_s}</td>
                  <td>${format_d_m_a(value.fecha_q_s_inicio)}</td>
                  <td>${format_d_m_a(value.fecha_q_s_fin)}</td>
                  <td><sup>S/. </sup>${value.sueldo_hora}</td>
                  <td>${value.total_hn}<b> / </b>${value.total_he}</td>
                  <td>${value.sabatical}</td>          
                  <td><sup>S/. </sup>${formato_miles(value.pago_parcial_hn)}<b> / </b><sup>S/. </sup>${formato_miles(value.pago_parcial_he)}</td>
                  <td style="text-align: right !important;"><sup>S/. </sup>${formato_miles(value.adicional_descuento)}</td>
                  <td style="text-align: right !important;"><sup>S/. </sup>${formato_miles(value.pago_quincenal)}</td>
                  <td style="text-align: right !important;"><sup>S/. </sup>${formato_miles(value.deposito)}</td>
                  <td style="text-align: right !important;"><sup>S/. </sup>${formato_miles(parseFloat(value.pago_quincenal) - parseFloat(value.deposito))}</td>
              </tr>`;

        $(".detalle-data-q-s").append(detalle);

        total_hn_he += parseFloat(value.total_hn) + parseFloat(value.total_he);
        sabatical += parseFloat(value.sabatical);
        pago_parcial_hn += parseFloat(value.pago_parcial_hn);
        pago_parcial_he += parseFloat(value.pago_parcial_he);
        adicional_descuento += parseFloat(value.adicional_descuento);
        pago_quincenal += parseFloat(value.pago_quincenal);
        deposito += parseFloat(value.deposito);
        saldo += parseFloat(value.pago_quincenal) - parseFloat(value.deposito);
      });

      $(".total_hn_he").html(total_hn_he);
      $(".total_sabatical").html(sabatical);
      $(".total_monto_hn_he").html("<sup>S/. </sup>" + formato_miles(pago_parcial_hn + pago_parcial_he));
      $(".total_descuento").html("<sup>S/. </sup>" + formato_miles(adicional_descuento));
      $(".total_quincena").html("<sup>S/. </sup>" + formato_miles(pago_quincenal));
      $(".total_deposito").html("<sup>S/. </sup>" + formato_miles(deposito));
      $(".total_saldo").html("<sup>S/. </sup>" + formato_miles(saldo));
    } else {
      $(".tabla_obrero").hide();
      $(".alerta_obrero").show();
    }
  });
}

function filtros() {
 var id_trabajador =  $("#trabajador_filtro").select2('val');
 console.log(id_trabajador);
}

init();


// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


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

// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {

  var format = "";

  if (fecha == '' || fecha == null) {
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

  if (fecha == '' || fecha == null) {
    format = "-";
  } else {
    let splits = fecha.split("-"); //console.log(splits);
    format = splits[2]+'-'+splits[1]+'-'+splits[0];
  } 

  return format;
}
