var tabla_principal;

//Función que se ejecuta al inicio
function init() {

  // $("#bloc_ContableFinanciero").addClass("menu-open");

  // $("#mContableFinanciero").addClass("active");

  $("#lResumenFacura").addClass("active bg-primary");
  
  tbla_principal(localStorage.getItem('nube_idproyecto'));
  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

//Función Listar - tabla compras
function tbla_principal(nube_idproyecto) {

  $('.total-subtotal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.total-igv').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.total-total').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  tabla_principal = $('#tabla-r-f-compras').dataTable({
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
      if (data[1] != '') {
        $("td", row).eq(1).addClass('text-center text-nowrap');
      }   
      // columna: sub total
      if (data[5] != '') {
        $("td", row).eq(5).addClass('text-right');
      }     

      // columna: igv
      if (data[6] != '') {
        $("td", row).eq(6).addClass('text-right');
      }  
       // columna: total
      if (data[7] != '') {
        $("td", row).eq(7).addClass('text-right');
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

  $.post("../ajax/resumen_facturas.php?op=suma_totales", { 'id_proyecto': nube_idproyecto }, function (data, status) {
    
    data = JSON.parse(data);  console.log(data);     
    
    $('.total-total').html(`S/. ${formato_miles(parseFloat(data.total).toFixed(2))}`);
    $('.total-subtotal').html(`S/. ${formato_miles(parseFloat(data.subtotal).toFixed(2))}`);
    $('.total-igv').html(`S/. ${formato_miles(parseFloat(data.igv).toFixed(2))}`);
  }); 
  
}

//ver ficha tecnica
function modal_comprobante(comprobante, fecha, tipo_comprobante, serie_comprobante, ruta) {

  var data_comprobante = ""; var url = ""; var nombre_download = "Comprobante"; 
  
  $("#modal-ver-comprobante").modal("show");

  if (comprobante == '' || comprobante == null) {

    data_comprobante = `<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="Alerta" aria-hidden="true">&times;</button><h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>No hay un documento para ver. Edite este registro en su modulo correspondiente.</div>`;

  }else{

    if ( extrae_extencion(comprobante) == "jpeg" || extrae_extencion(comprobante) == "jpg" || extrae_extencion(comprobante) == "jpe" ||
      extrae_extencion(comprobante) == "jfif" || extrae_extencion(comprobante) == "gif" || extrae_extencion(comprobante) == "png" ||
      extrae_extencion(comprobante) == "tiff" || extrae_extencion(comprobante) == "tif" || extrae_extencion(comprobante) == "webp" ||
      extrae_extencion(comprobante) == "bmp" || extrae_extencion(comprobante) == "svg" ) {
      
      url = `${ruta}${comprobante}`;

      nombre_download = `${format_d_m_a(fecha)} ─ ${tipo_comprobante} - ${serie_comprobante}`;

      data_comprobante = `<div class="col-md-12 mt-2 text-center"><i>${nombre_download}.${extrae_extencion(comprobante)}</i></div> <div class="col-md-12 mt-2"><img src="${url}" alt="img" class="img-thumbnail" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" ></div>`;         
      
    } else { 

      if (extrae_extencion(comprobante) == "pdf") {

        url = `${ruta}${comprobante}`;

        nombre_download = `${format_d_m_a(fecha)} ─ ${tipo_comprobante} - ${serie_comprobante}`;

        data_comprobante = `<div class="col-md-12 mt-2 text-center"><i>${nombre_download}.${extrae_extencion(comprobante)}</i></div> <div class="col-md-12 mt-2"><iframe src="${url}" frameborder="0" scrolling="no" width="100%" height="410"> </iframe></div>`;      

      } else {
        
        url = `${ruta}${comprobante}`;

        nombre_download = `${format_d_m_a(fecha)} ─ ${tipo_comprobante} - ${serie_comprobante}`;

        data_comprobante = `<div class="col-md-12 mt-2 text-center"><i>${nombre_download}.${extrae_extencion(comprobante)}</i></div> <div class="col-md-12 mt-2"><img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" ></div>`;
        
      }      
    }
  } 
  
  $(".ver-comprobante").html(`<div class="row" >
    <div class="col-md-6 text-center">
      <a type="button" class="btn btn-warning btn-block btn-xs" href="${url}" download="${nombre_download}"> <i class="fas fa-download"></i> Descargar. </a>
    </div>
    <div class="col-md-6 text-center">
      <a type="button" class="btn btn-info btn-block btn-xs" href="${url}" target="_blank" <i class="fas fa-expand"></i> Ver completo. </a>
    </div>
    <div class="col-md-12 mt-3">     
      ${data_comprobante}
    </div>
  </div>`);

  // $(".tooltip").hide();
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

function extrae_extencion(filename) {
  return filename.split(".").pop();
}

