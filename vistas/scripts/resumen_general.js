var tabla;
var tabla1;
var tabla2;


//Función que se ejecuta al inicio
function init() {

  // $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));
  listar_r_compras(localStorage.getItem('nube_idproyecto'));
  listar_r_serv_maquinaria(localStorage.getItem('nube_idproyecto'));
  listar_r_serv_equipos(localStorage.getItem('nube_idproyecto'));
  listar_r_transportes(localStorage.getItem('nube_idproyecto'));
  listar_r_hospedajes(localStorage.getItem('nube_idproyecto'));
  listar_r_comidas_extras(localStorage.getItem('nube_idproyecto'));
  listar_r_breaks(localStorage.getItem('nube_idproyecto'));
  listar_r_pensiones(localStorage.getItem('nube_idproyecto'));
  listar_r_trab_administrativo(localStorage.getItem('nube_idproyecto'));
  //Activamos el "aside"
  $("#mresumen_general").addClass("active");

  
    //Mostramos los trabajadores
    $.post('../ajax/resumen_general.php?op=selecct_trabajadores&idproyecto='+localStorage.getItem('nube_idproyecto'), function (r) { $("#trabajador").html(r); });

    //Mostramos los proveedores
    $.post("../ajax/resumen_general.php?op=select_proveedores", function (r) { $("#proveedor").html(r); });

    //Initialize Select2 filtrar_por
    $("#filtrar_por").select2({
      theme: "bootstrap4",
      placeholder: "Selecionar",
      allowClear: true,
    });

    //Initialize Select2 trabajador
    $("#trabajador").select2({
      theme: "bootstrap4",
      placeholder: "Selecionar trabajador",
      allowClear: true,
    });
    
    //Initialize Select2 proveedor
    $("#proveedor").select2({
      theme: "bootstrap4",
      placeholder: "Selecionar proveedor",
      allowClear: true,
    });
  
    //============borramos los valores================
    $("#filtrar_por").val("null").trigger("change");
    $("#trabajador").val("null").trigger("change");
    $("#proveedor").val("null").trigger("change");

  // Formato para telefono
  $("[data-mask]").inputmask();

}

function listar_r_compras(idproyecto) {
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0;

  $("#compras").html("");
  $("#monto_compras").html("");  
  $("#pago_compras").html("");  
  $("#saldo_compras").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_compras", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  //console.log(data); console.log('........................');  

    data.forEach((value,index)=>{

      if (value.monto_pago_total!=null) {
        calculando_sldo=parseFloat(value.monto_total)-parseFloat(value.monto_pago_total);
        validando_pago=parseFloat(value.monto_pago_total);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }

      compras=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${value.proveedor}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${format_d_m_a(value.fecha_compra)}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading">         
          <textarea cols="30" rows="1" class="text_area_clss" readonly > ${value.descripcion==""?'---':value.descripcion}</textarea>
          </td>
          <td class="bg-color-b4bdbe47 text-center clas_pading"><button class="btn btn-info btn-xs" onclick="ver_detalle_compras(${value.idcompra_proyecto})"><i class="fa fa-eye"></i></button></td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(parseFloat(value.monto_total).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto=t_monto+parseFloat(value.monto_total);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#compras").append(compras);

    });

      $("#monto_compras").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_compras").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_compras").html(formato_miles(t_saldo.toFixed(2)));  

      $('#tabla1_compras').dataTable({  
        "responsive": true,
        "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
        buttons: ['copyHtml5','excelHtml5','pdf'],
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
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
      }).DataTable();
     
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
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0;

  $("#serv_maquinas").html("");
  $("#monto_serv_maq").html("");  
  $("#pago_serv_maq").html("");  
  $("#saldo_serv_maq").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_serv_maquinaria", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);  

    data.forEach((value,index)=>{

      if (value.monto_pag_ser_maq!=null) {
        calculando_sldo=parseFloat(value.costo_parcial)-parseFloat(value.monto_pag_ser_maq);
        validando_pago=parseFloat(value.monto_pag_ser_maq);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }

      compras=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${value.proveedor}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading">--</td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >--</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading"><button class="btn btn-info btn-xs" onclick="ver_detalle(${value.idmaquinaria},${value.idproyecto})"><i class="fa fa-eye"></i></button></td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(parseFloat(value.costo_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto=t_monto+parseFloat(value.costo_parcial);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#serv_maquinas").append(compras);

    });

      $("#monto_serv_maq").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_serv_maq").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_serv_maq").html(formato_miles(t_saldo.toFixed(2)));  

      $('#tabla2_maquinaria').dataTable({  
        "responsive": true,
        "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
        buttons: ['copyHtml5','excelHtml5','pdf'],
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
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
      }).DataTable();

     
  });
}

function listar_r_serv_equipos(idproyecto) {
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0;

  $("#serv_equipos").html("");
  $("#monto_serv_equi").html("");  
  $("#pago_serv_equi").html("");  
  $("#saldo_serv_equi").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_serv_equipos", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);  

    data.forEach((value,index)=>{

      if (value.monto_pag_ser_maq!=null) {
        calculando_sldo=parseFloat(value.costo_parcial)-parseFloat(value.monto_pag_ser_maq);
        validando_pago=parseFloat(value.monto_pag_ser_maq);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }

      compras=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${value.proveedor}</td>
          <td class="bg-color-b4bdbe47  clas_pading">--</td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >--</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading"><button class="btn btn-info btn-xs" onclick="ver_detalle(${value.idmaquinaria},${value.idproyecto})"><i class="fa fa-eye"></i></button></td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(parseFloat(value.costo_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto=t_monto+parseFloat(value.costo_parcial);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#serv_equipos").append(compras);

    });

      $("#monto_serv_equi").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_serv_equi").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_serv_equi").html(formato_miles(t_saldo.toFixed(2))); 
      
      $('#tabla3_equipo').dataTable({  
        "responsive": true,
        "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
        buttons: ['copyHtml5','excelHtml5','pdf'],
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
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
      }).DataTable();
     
  });
}
//Función detalles por maquina-equipo
function ver_detalle(idmaquinaria,idproyecto,unidad_medida) {

  $("#modal_ver_detalle_maq_equ").modal('show');
  var hideen_colums;
  //console.log('lis_deta '+idproyecto,idmaquinaria,unidad_medida);
  $("#tabla_principal").hide();
  $("#tabla_detalles").show();
  $("#btn-agregar").hide();
  $("#btn-regresar").show();
  $("#btn-pagar").hide();
  if (unidad_medida=='Hora') {
    hideen_colums=[];
    
  }else{
    hideen_colums=[
      {
          "targets": [ 3 ],
          "visible": false,
          "searchable": false
      },
      {
          "targets": [ 4 ],
          "visible": false,
          "searchable": false
      },
      {
          "targets": [ 5 ],
          "visible": false,
          "searchable": false
      }
  ]

  }
 // console.log(hideen_colums);
  tabla2=$('#tabla-detalle-m').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/resumen_general.php?op=ver_detalle_maquina&idmaquinaria='+idmaquinaria+'&idproyecto='+idproyecto,
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
    "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
    "columnDefs": hideen_colums
  }).DataTable();
  
}

function listar_r_transportes(idproyecto) {
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0; var comprobante="";

  $("#transportes").html("");
  $("#monto_transp").html("");  
  $("#pago_transp").html("");  
  $("#saldo_transp").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_transportes", { idproyecto: idproyecto }, function (data, status) {
   
    data = JSON.parse(data);  console.log(data);  

    data.forEach((value,index)=>{

      if (value.precio_parcial!=null) {
        calculando_sldo=parseFloat(value.precio_parcial)-parseFloat(value.precio_parcial);
        validando_pago=parseFloat(value.precio_parcial);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }
      if (value.comprobante!="") {
        comprobante=`<a target="_blank"  href="../dist/img/comprob_transporte/${value.comprobante}"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>`;
      }else{
        comprobante=`<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>`;
      }

      compras=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  text-center clas_pading">--</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${format_d_m_a(value.fecha_viaje)}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >${value.descripcion==""?'---':value.descripcion}</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">${comprobante}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(parseFloat(value.precio_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto=t_monto+parseFloat(value.precio_parcial);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#transportes").append(compras);

    });

      $("#monto_transp").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_transp").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_transp").html(formato_miles(t_saldo.toFixed(2)));  

      $('#tabla4_transporte').dataTable({  
        "responsive": true,
        "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
        buttons: ['copyHtml5','excelHtml5','pdf'],
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
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
      }).DataTable();
     
  });
}

function listar_r_hospedajes(idproyecto) {  
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0; var comprobante="";

  $("#hospedaje").html("");
  $("#monto_hosped").html("");  
  $("#pago_hosped").html("");  
  $("#saldo_hosped").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_hospedajes", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);  

    data.forEach((value,index)=>{

      if (value.precio_parcial!=null) {
        calculando_sldo=parseFloat(value.precio_parcial)-parseFloat(value.precio_parcial);
        validando_pago=parseFloat(value.precio_parcial);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }
      if (value.comprobante!="") {
        comprobante=`<a target="_blank"  href="../dist/img/comprob_hospedajes/${value.comprobante}"> <i class="far fa-file-pdf"  style="font-size: 23px;"></i></a>`;
      }else{
        comprobante=`<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>`;
      }

      compras=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  text-center clas_pading">--</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${format_d_m_a(value.fecha_comprobante)}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >${value.descripcion==""?'---':value.descripcion}</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">${comprobante}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(parseFloat(value.precio_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto=t_monto+parseFloat(value.precio_parcial);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#hospedaje").append(compras);

    });

      $("#monto_hosped").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_hosped").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_hosped").html(formato_miles(t_saldo.toFixed(2)));  

      $('#tabla5_hospedaje').dataTable({  
        "responsive": true,
        "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
        buttons: ['copyHtml5','excelHtml5','pdf'],
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
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
      }).DataTable();
     
  });
}

function listar_r_comidas_extras(idproyecto) {  
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0; var comprobante="";

  $("#comida_extra").html("");
  $("#monto_cextra").html("");  
  $("#pago_cextra").html("");  
  $("#saldo_cextra").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_comidas_extras", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);  

    data.forEach((value,index)=>{

      if (value.costo_parcial!=null) {
        calculando_sldo=parseFloat(value.costo_parcial)-parseFloat(value.costo_parcial);
        validando_pago=parseFloat(value.costo_parcial);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }
      if (value.comprobante!="") {
        comprobante=`<a target="_blank"  href="../dist/img/comidas_extras/${value.comprobante}"> <i class="far fa-file-pdf" style="font-size: 23px;"></i></a>`;
      }else{
        comprobante=`<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>`;
      }

      compras=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  text-center clas_pading">--</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${format_d_m_a(value.fecha_comida)}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading">
            <textarea cols="30" rows="1" class="text_area_clss" readonly >${value.descripcion==""?'---':value.descripcion}</textarea> 
          </td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">${comprobante}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(parseFloat(value.costo_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      
      t_monto=t_monto+parseFloat(value.costo_parcial);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#comida_extra").append(compras);

    });

      $("#monto_cextra").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_cextra").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_cextra").html(formato_miles(t_saldo.toFixed(2)));  
     
      $('#tabla6_comidas_ex').dataTable({  
        "responsive": true,
        "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
        buttons: ['copyHtml5','excelHtml5','pdf'],
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
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
      }).DataTable();

  });
}

function listar_r_breaks(idproyecto) {  
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0; var comprobante="";

  $("#breaks").html("");
  $("#monto_break").html("");  
  $("#pago_break").html("");  
  $("#saldo_break").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_breaks", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    data.forEach((value,index)=>{

      if (value.total!=null) {
        calculando_sldo=parseFloat(value.total)-parseFloat(value.total);
        validando_pago=parseFloat(value.total);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }
      if (value.idsemana_break!="") {
        comprobante=`<a target="_blank"  href="../dist/img/comidas_extras/${value.idsemana_break}"> <i class="far fa-file-pdf" style="font-size: 23px;"></i></a>`;
      }else{
        comprobante=`<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>`;
      }

      breaks=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>--</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${format_d_m_a(value.fecha_inicial)} - <br> ${format_d_m_a(value.fecha_final)} </span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >--</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">
            <button class="btn btn-info btn-sm" onclick="listar_comprobantes_breaks(${value.idsemana_break})"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
          </td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(parseFloat(value.total).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      
      t_monto=t_monto+parseFloat(value.total);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#breaks").append(breaks);

    });

      $("#monto_break").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_break").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_break").html(formato_miles(t_saldo.toFixed(2))); 
      
           
      $('#tabla7_breaks').dataTable({  
        "responsive": true,
        "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
        buttons: ['copyHtml5','excelHtml5','pdf'],
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
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
      }).DataTable();
     
  });
}

function listar_comprobantes_breaks(idsemana_break) {
  $("#modal_ver_breaks").modal("show");

  tabla1=$('#t-comprobantes').dataTable({  
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/resumen_general.php?op=listar_comprobantes_breaks&idsemana_break='+idsemana_break,
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
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

function listar_r_pensiones(idproyecto) {  
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0; var comprobante="";

  $("#pension").html("");
  $("#monto_pension").html("");  
  $("#pago_pension").html("");  
  $("#saldo_pension").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_pensiones", { idproyecto: idproyecto }, function (data, status) {
    console.log('.^^.');
    data = JSON.parse(data);  console.log(data);  

    data.forEach((value,index)=>{

      if (value.monto_total_pension!=null) {
        calculando_sldo=parseFloat(value.monto_total_pension)-parseFloat(value.pago_total_pension);
        validando_pago=parseFloat(value.pago_total_pension);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }
      if (value.idsemana_break!="") {
        comprobante=`<a target="_blank"  href="../dist/img/comidas_extras/${value.idsemana_break}"> <i class="fas fa-file-invoice-dollar" style="font-size: 23px;"></i></a>`;
      }else{
        comprobante=`<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>`;
      }

      pension=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>Semana ${value.proveedor}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>--</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >--</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">
            <button class="btn btn-info btn-sm" onclick="ver_detalle_x_servicio_p(${value.idpension})"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
            <button class="btn btn-info btn-sm" onclick="listar_comprobantes_pension(${value.idpension})"><i class="far fa-file-pdf fa-lg btn-info nav-icon"></i></button>
          </td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(parseFloat(value.monto_total_pension).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      
      t_monto=t_monto+parseFloat(value.monto_total_pension);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#pension").append(pension);

    });

      $("#monto_pension").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_pension").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_pension").html(formato_miles(t_saldo.toFixed(2)));  

      $('#tabla8_pension').dataTable({  
        "responsive": true,
        "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
        buttons: ['copyHtml5','excelHtml5','pdf'],
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
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
      }).DataTable();
     
  });
}
//Función ver detalles Detalles
function ver_detalle_x_servicio_p(idpension) {
  //console.log(numero_semana,nube_idproyecto);
  $("#modal-ver-detalle-semana").modal("show");
  tabla_detalle_s=$('#tabla-detalles-semanal').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/resumen_general.php?op=ver_detalle_x_servicio&idpension='+idpension,
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
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

function listar_comprobantes_pension(idpension) {
  $("#modal-ver-comprobantes_pension").modal("show");
  
  tabla2=$('#t-comprobantes-pension').dataTable({  
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/resumen_general.php?op=listar_comprobantes_pension&idpension='+idpension,
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

function listar_r_trab_administrativo(idproyecto) {  
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0; var comprobante="";

  $("#administrativo").html("");
  $("#monto_adm").html("");  
  $("#pago_adm").html("");  
  $("#saldo_adm").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_trab_administrativo", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data); // console.log(data);  

    data.forEach((value,index)=>{

      if (value.total_montos_x_meses!=null) {
        calculando_sldo=parseFloat(value.total_montos_x_meses)-parseFloat(value.pago_total_adm);
        validando_pago=parseFloat(value.pago_total_adm);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }
      if (value.idsemana_break!="") {
        comprobante=`<a target="_blank"  href="../dist/img/comidas_extras/${value.idsemana_break}"> <i class="fas fa-file-invoice-dollar" style="font-size: 23px;"></i></a>`;
      }else{
        comprobante=`<a> <i class="far fa-times-circle"  style="font-size: 23px;"></i></a>`;
      }

      administrativo=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${value.nombres}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>--</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><textarea cols="30" rows="1" class="text_area_clss" readonly >--</textarea></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading">
            <button class="btn btn-info btn-sm" onclick="ver_detalle_pagos_x_trab_adm(${value.idtrabajador_por_proyecto})"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button>
          </td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(parseFloat(value.total_montos_x_meses).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47 text-right clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      
      t_monto=t_monto+parseFloat(value.total_montos_x_meses);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#administrativo").append(administrativo);

    });

      $("#monto_adm").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_adm").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_adm").html(formato_miles(t_saldo.toFixed(2)));

      $('#tabla9_per_adm').dataTable({  
        "responsive": true,
        "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
        buttons: ['copyHtml5','excelHtml5','pdf'],
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
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
      }).DataTable();
  });
}
//DETALLES DE PAGOS ADMINISTRADORES
function ver_detalle_pagos_x_trab_adm(idtrabajador_por_proyecto) {

  $("#modal-ver-detalle-t-administ").modal('show');

  $(".data-detalle-pagos-administador").html("");

  $.post("../ajax/resumen_general.php?op=ver_detalle_pagos_x_trab_adms", { idtrabajador_por_proyecto: idtrabajador_por_proyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  
    
    data.forEach((value,index)=>{

      detalle=`<tr>
                <td>${index+1}</td>
                <td>${value.nombre_mes}</td>
                <td>${format_d_m_a(value.fecha_inicial)}</td>
                <td>${format_d_m_a(value.fecha_final)}</td>
                <td>${value.cant_dias_laborables}</td>
                <td>S/. ${ formato_miles(parseFloat(value.monto_x_mes).toFixed(2))}</td>
                <td>S/. ${ formato_miles(parseFloat(value.return_monto_pago).toFixed(2))}</td>
            </tr>`;

      $(".data-detalle-pagos-administador").append(detalle);

    });

  });
  
}

init();

function formato_miles(num) {
  if (!num || num == 'NaN') return '-';
  if (num == 'Infinity') return '&#x221e;';
  num = num.toString().replace(/\$|\,/g, '');
  if (isNaN(num))
      num = "0";
  sign = (num == (num = Math.abs(num)));
  num = Math.floor(num * 100 + 0.50000000001);
  cents = num % 100;
  num = Math.floor(num / 100).toString();
  if (cents < 10)
      cents = "0" + cents;
  for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
      num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
  return (((sign) ? '' : '-') + num + '.' + cents);
}
// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {

  let splits = fecha.split("-"); //console.log(splits);

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}


