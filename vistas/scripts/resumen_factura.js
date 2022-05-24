var tabla_principal;

var zip = new JSZip();

//Función que se ejecuta al inicio
function init() {

  $("#lResumenFacura").addClass("active bg-primary");
  
  $.get("../ajax/resumen_facturas.php?op=select2Proveedor", function (r) { $("#proveedor_filtro").html(r); $(".cargando_proveedor").html('Proveedor'); });

  //Initialize: Select2 PROVEEDOR
  $("#proveedor_filtro").select2({ theme: "bootstrap4", placeholder: "Selecionar proveedor", allowClear: true, });

  //Initialize: Select2 PROVEEDOR
  $("#tipo_comprobante_filtro").select2({ theme: "bootstrap4", placeholder: "Selecionar comprobante", allowClear: true, });
  
  // Formato para telefono
  $("[data-mask]").inputmask();  
  
  filtros();
} 

//Función Listar - tabla compras
function tbla_principal(nube_idproyecto, fecha_1, fecha_2, id_proveedor, comprobante) {
   
  $('.total-subtotal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.total-igv').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.total-total').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  tabla_principal = $('#tabla-principal').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9], } }, { extend: 'pdfHtml5', footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9], } }, "colvis"],
    ajax:	{
      url: `../ajax/resumen_facturas.php?op=listar_facturas_compras&id_proyecto=${nube_idproyecto}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);	
      }
		},
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[1] != '') { $("td", row).eq(1).addClass('text-center text-nowrap'); }   
      // columna: sub total
      if (data[5] != '') { $("td", row).eq(5).addClass('text-right'); }
      // columna: igv
      if (data[6] != '') { $("td", row).eq(6).addClass('text-right'); }  
      // columna: total
      if (data[7] != '') { $("td", row).eq(7).addClass('text-right');  }      
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
      { targets: [1], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      { targets: [11], visible: false, searchable: false, }, 
    ],
  }).DataTable();
  
  $( tabla_principal ).ready(function() {
    sumas_totales(nube_idproyecto, fecha_1, fecha_2, id_proveedor, comprobante);
  });
  
}

function sumas_totales(nube_idproyecto, fecha_1, fecha_2, id_proveedor, comprobante) {
  $('.btn-zip').addClass('disabled');
  $.post("../ajax/resumen_facturas.php?op=suma_totales", { 'id_proyecto': nube_idproyecto, 'fecha_1': fecha_1, 'fecha_2': fecha_2, 'id_proveedor': id_proveedor, 'comprobante': comprobante, }, function (e, status) {
    
    e = JSON.parse(e);  console.log(e); 

    if (e.status == true) {
      $('.total-total').html(`S/ ${formato_miles(parseFloat(e.data.total).toFixed(2))}`);
      $('.total-subtotal').html(`S/ ${formato_miles(parseFloat(e.data.subtotal).toFixed(2))}`);
      $('.total-igv').html(`S/ ${formato_miles(parseFloat(e.data.igv).toFixed(2))}`);

      $('.cargando').hide();
      $('.btn-zip').removeClass('disabled');
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } ); 
}

//ver ficha tecnica
function modal_comprobante(comprobante, fecha, tipo_comprobante, serie_comprobante, ruta, carpeta, subcarpeta) {

  var data_comprobante = ""; var url = ""; var nombre_download = "Comprobante"; 
  
  $("#modal-ver-comprobante").modal("show");

  if (comprobante == '' || comprobante == null) {       
    $(".ver-comprobante").html(`<div class="alert alert-warning alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
      <h3><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h3>
      No hay un documento para ver. Edite este registro en su modulo correspondiente.
    </div>`);
  }else{
    var host = window.location.host == 'localhost'? `http://localhost/admin_sevens/dist/docs/${carpeta}/${subcarpeta}/${comprobante}` : `${window.location.origin}/dist/docs/${carpeta}/${subcarpeta}/${comprobante}` ;
    
    if ( UrlExists(host) == 200 ) {
      nombre_download = `${format_d_m_a(fecha)} ─ ${tipo_comprobante} - ${serie_comprobante}`;
      data_comprobante =  `<div class="col-md-12 mt-2 text-center"><i>${nombre_download}.${extrae_extencion(comprobante)}</i></div> <div class="col-md-12 mt-2"> ${doc_view_extencion(comprobante, carpeta, subcarpeta, width='100%' )} </div>`;
      url = `../${ruta}${comprobante}`;    

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
    } else {     
      $(".ver-comprobante").html(`<div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
        <h3><i class="icon fas fa-exclamation-triangle"></i> Documento no encontrado!</h3>
        Hubo un error al encontrar este archivo, los mas probable es que se haya eliminado, o se haya movido a otro lugar, se recomiendar editar en su modulo correspodiente.
      </div>`);      
    }    
  }

  $(".tooltip").removeClass("show").addClass("hidde");
}

function filtros() {  

  var fecha_1       = $("#fecha_filtro_1").val();
  var fecha_2       = $("#fecha_filtro_2").val();  
  var id_proveedor  = $("#proveedor_filtro").select2('val');
  var comprobante   = $("#tipo_comprobante_filtro").select2('val');   
  
  var nombre_proveedor = $('#proveedor_filtro').find(':selected').text();
  var nombre_comprobante = ' ─ ' + $('#tipo_comprobante_filtro').find(':selected').text();

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; }
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; }  

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == 0 || id_proveedor == null) { id_proveedor = ""; nombre_proveedor = ""; }

  // filtro de trabajdor
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_proveedor} ${nombre_comprobante}...`);
  console.log(fecha_1, fecha_2, id_proveedor, comprobante);

  tbla_principal(localStorage.getItem("nube_idproyecto"), fecha_1, fecha_2, id_proveedor, comprobante)
}

function desccargar_zip_comprobantes() {   
  var fecha_1       = $("#fecha_filtro_1").val();
  var fecha_2       = $("#fecha_filtro_2").val();  
  var id_proveedor  = $("#proveedor_filtro").select2('val');
  var comprobante   = $("#tipo_comprobante_filtro").select2('val');  

  $('.btn-zip').addClass('disabled btn-danger').removeClass('btn-success');
  $('.btn-zip').html('<i class="fas fa-spinner fa-pulse fa-sm"></i> Comprimiendo datos');

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; }
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; }  

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == 0 || id_proveedor == null) { id_proveedor = "";  }

  // filtro de trabajdor
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; }

  $.post("../ajax/resumen_facturas.php?op=data_comprobantes", { 'id_proyecto': localStorage.getItem("nube_idproyecto"), 'fecha_1': fecha_1, 'fecha_2': fecha_2, 'id_proveedor': id_proveedor, 'comprobante': comprobante, }, function (e, status) {
    
    e = JSON.parse(e);  console.log(e);    
    
    const zip = new JSZip();  let count = 0; const zipFilename = "comprobantes.zip";
    
    if (e.status == true) {
      if (e.data.data_comprobante.length === 0) {
        $('.btn-zip').removeClass('disabled btn-danger').addClass('btn-success');
        $('.btn-zip').html('<i class="far fa-file-archive fa-lg"></i> Comprobantes .zip');
        toastr.error("No hay docs para descargar!!!");
      }else{
        e.data.data_comprobante.forEach(async function (value){
           
          const urlArr = value.ruta_file.split('/');
          const filename = urlArr[urlArr.length - 1];
    
          try {   
             
            const file = await JSZipUtils.getBinaryContent(value.ruta_file)
            zip.file(filename, file, { binary: true});
            count++;
            if(count === e.data.data_comprobante.length) {
              zip.generateAsync({type:'blob'}).then(function(content) {
                var download_zip = saveAs(content, zipFilename);
                $( download_zip ).ready(function() { toastr.success('Descarga exitosa'); });
                $('.btn-zip').removeClass('disabled btn-danger').addClass('btn-success');
                $('.btn-zip').html('<i class="far fa-file-archive fa-lg"></i> Comprobantes .zip');
              });
            }
          } catch (err) {
            console.log(err); toastr.error('Error al descargar');
            $('.btn-zip').removeClass('disabled btn-danger').addClass('btn-success');
            $('.btn-zip').html('<i class="far fa-file-archive fa-lg"></i> Comprobantes .zip');
          }
        });
      }
    } else {
      ver_errores(e);
    }       

  }).fail( function(e) { ver_errores(e); } ); 
}

init();

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


