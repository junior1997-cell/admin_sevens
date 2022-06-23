var tabla;

var zip = new JSZip();

//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#lResumenRH").addClass("active bg-primary");

  listar();

  // Formato para telefono
  $("[data-mask]").inputmask();

}
//Función Listar
function listar() {

  $(".total_monto").html( `<i class="fas fa-spinner fa-pulse fa-sm"></i>`);
 
  tabla = $("#tabla_resumen_rh").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,1,2,3], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,1,2,3], } }, { extend: 'pdfHtml5', footer: true,  exportOptions: { columns: [0,1,2,3], } }, "colvis"
    ],
    ajax: {
      url: "../ajax/resumen_rh.php?op=listar_resumen_rh",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);	
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != "") { $("td", row).eq(0).addClass("text-center"); }
      // columna: sub total
      if (data[3] != "") { $("td", row).eq(3).addClass("text-nowrap text-right"); }
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

  $.post("../ajax/resumen_rh.php?op=monto_total_rh", {}, function (e, status) {

    e = JSON.parse(e); // console.log(e);
    $(".total_monto").html('S/ '+ formato_miles(e.data.monto_total_rh));
  }).fail( function(e) { ver_errores(e); } ); 

}

//ver rh
function modal_comprobante(comprobante, ruta, carpeta, subcarpeta, proveedor) {

  var data_comprobante = ""; var url = ""; var nombre_download = ""; 
  
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
      nombre_download = `RH ─ ${proveedor}`;
      data_comprobante =  `<div class="col-md-12 mt-2 text-center"><i>${nombre_download}.${extrae_extencion(comprobante)}</i></div> <div class="col-md-12 mt-2"> ${doc_view_extencion(comprobante, carpeta, subcarpeta, width='100%' )} </div>`;
      url = `${ruta}${comprobante}`;
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
        Hubo un error al encontrar este archivo, los mas probable es que se haya eliminado, o se haya movido a otro lugar, se recomienda editar en su módulo correspodiente.
      </div>`);      
    }
  } 

  $(".tooltip").removeClass("show").addClass("hidde");
}

function desccargar_zip_recibos_honorarios() {   

  $('.btn-zip').addClass('disabled btn-danger').removeClass('btn-success');
  $('.btn-zip').html('<i class="fas fa-spinner fa-pulse fa-sm"></i> Comprimiendo datos');

  $.post("../ajax/resumen_rh.php?op=data_recibos_honorarios", { }, function (e, status) {
    
    e = JSON.parse(e);  //console.log(e);    
    
    const zip = new JSZip();  let count = 0; const zipFilename = "Recibos_x_honorario.zip";
    
    if (e.data.data_recibos_honorarios.length === 0) {
      $('.btn-zip').removeClass('disabled btn-danger').addClass('btn-success');
      $('.btn-zip').html('<i class="far fa-file-archive fa-lg"></i> Recibos honorario .zip');
      toastr.error("No hay docs para descargar!!!");
    }else{
      e.data.data_recibos_honorarios.forEach(async function (value){

        const urlArr = value.ruta_file.split('/');
        const filename = urlArr[urlArr.length - 1];
  
        try {
          const file = await JSZipUtils.getBinaryContent(value.ruta_file)
          zip.file(filename, file, { binary: true});
          count++;
          if(count === e.data.data_recibos_honorarios.length) {
            zip.generateAsync({type:'blob'}).then(function(content) {
              var download_zip = saveAs(content, zipFilename);
              $( download_zip ).ready(function() { toastr.success('Descarga exitosa'); });
              $('.btn-zip').removeClass('disabled btn-danger').addClass('btn-success');
              $('.btn-zip').html('<i class="far fa-file-archive fa-lg"></i> Recibos honorario .zip');
            });
          }
        } catch (err) {
          console.log(err); toastr.error('Error al descargar');
          $('.btn-zip').removeClass('disabled btn-danger').addClass('btn-success');
          $('.btn-zip').html('<i class="far fa-file-archive fa-lg"></i> Recibos honorario .zip');
        }
      });
    }
  });  
}


init();

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..



