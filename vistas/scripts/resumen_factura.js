var tabla_fact_compras; 
var tabla_fact_maquinaria; 
var tabla_fact_equipos; 

//Función que se ejecuta al inicio
function init() {

  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#mContableFinanciero").addClass("active");

  $("#lResumenFacura").addClass("active bg-primary");
  
  listar_tbla_compras(localStorage.getItem('nube_idproyecto'));
  listar_tbla_maquinaria(localStorage.getItem('nube_idproyecto'));
  listar_tbla_equipos(localStorage.getItem('nube_idproyecto'));

  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

//Función Listar - tabla compras
function listar_tbla_compras(nube_idproyecto) {

  $('.monto-total-compras').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  tabla_fact_compras=$('#tabla-r-f-compras').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    "ajax":	{
      url: '../ajax/resumen_facturas.php?op=listar_facturas_compras&id_proyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
		},
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') {
        $("td", row).eq(0).css({ "text-align": "center" });
      }   
      // columna: sub total
      if (data[4] != '') {
        $("td", row).eq(4).css({ "text-align": "right" });
      }     

      // columna: igv
      if (data[5] != '') {
        $("td", row).eq(5).css({ "text-align": "right" });
      }  
       // columna: total
      if (data[6] != '') {
        $("td", row).eq(6).css({ "text-align": "right" });
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

  $.post("../ajax/resumen_facturas.php?op=total_facturas_compras", { 'id_proyecto': nube_idproyecto }, function (data, status) {
    data = JSON.parse(data);  console.log(data); 
    $('.monto-total-compras').html('S/. '+formato_miles(parseFloat(data.monto_total).toFixed(2)));

  }); 

  
}
//Función Listar - tabla compras
function listar_tbla_maquinaria(nube_idproyecto) {

  $('.monto-total-maquinaria').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  tabla_fact_maquinaria=$('#tabla-r-f-maquinaria').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    "ajax":	{
      url: '../ajax/resumen_facturas.php?op=listar_facturas_maquinaria&id_proyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
		},
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') {
        $("td", row).eq(0).css({ "text-align": "center" });
      }   
      // columna: sub total
      if (data[4] != '') {
        $("td", row).eq(4).css({ "text-align": "right" });
      }     

      // columna: igv
      if (data[5] != '') {
        $("td", row).eq(5).css({ "text-align": "right" });
      }  
       // columna: total
      if (data[6] != '') {
        $("td", row).eq(6).css({ "text-align": "right" });
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

  $.post("../ajax/resumen_facturas.php?op=total_facturas_maquinaria", { 'id_proyecto': nube_idproyecto }, function (data, status) {
    $('.monto-total-maquinaria').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
    data = JSON.parse(data);  console.log(data); 
    $('.monto-total-maquinaria').html('S/. '+formato_miles(parseFloat(data.monto_total).toFixed(2)));

  }); 

  
}
//Función Listar - tabla compras
function listar_tbla_equipos(nube_idproyecto) {

  $('.monto-total-equipos').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  tabla_fact_equipos=$('#tabla-r-f-equipos').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    "ajax":	{
      url: '../ajax/resumen_facturas.php?op=listar_facturas_equipos&id_proyecto='+nube_idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
		},
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') {
        $("td", row).eq(0).css({ "text-align": "center" });
      }   
      // columna: sub total
      if (data[4] != '') {
        $("td", row).eq(4).css({ "text-align": "right" });
      }     

      // columna: igv
      if (data[5] != '') {
        $("td", row).eq(5).css({ "text-align": "right" });
      }  
       // columna: total
      if (data[6] != '') {
        $("td", row).eq(6).css({ "text-align": "right" });
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

  $.post("../ajax/resumen_facturas.php?op=total_facturas_equipos", { 'id_proyecto': nube_idproyecto }, function (data, status) {
    data = JSON.parse(data);  console.log(data); 
    $('.monto-total-equipos').html('S/. '+formato_miles(parseFloat(data.monto_total).toFixed(2)));
  }); 

  
}

init();


// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..



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


