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
  tabla = $("#tabla_resumen_rh")
    .dataTable({
      responsive: true,
      lengthMenu: [
        [5, 10, 25, 75, 100, 200, -1],
        [5, 10, 25, 75, 100, 200, "Todos"],
      ], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
      ajax: {
        url: "../ajax/resumen_rh.php?op=listar_resumen_rh",
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
      },
      createdRow: function (row, data, ixdex) {
        // columna: #
        if (data[0] != "") {
          $("td", row).eq(0).addClass("text-center");
        }
        // columna: sub total
        if (data[3] != "") {
          $("td", row).eq(3).addClass("text-nowrap text-right");
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
    })
    .DataTable();

}

//ver ficha tecnica
function modal_comprobante(comprobante,ruta, proveedor) {

  var data_comprobante = ""; var url = ""; var nombre_download = `RH_${proveedor}`; 
  
  $("#modal-ver-comprobante").modal("show");

  if (comprobante == '' || comprobante == null) {

    data_comprobante = `<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="Alerta" aria-hidden="true">&times;</button><h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>No hay un documento para ver. Edite este registro en su modulo correspondiente.</div>`;

  }else{

    if ( extrae_extencion(comprobante) == "jpeg" || extrae_extencion(comprobante) == "jpg" || extrae_extencion(comprobante) == "jpe" ||
      extrae_extencion(comprobante) == "jfif" || extrae_extencion(comprobante) == "gif" || extrae_extencion(comprobante) == "png" ||
      extrae_extencion(comprobante) == "tiff" || extrae_extencion(comprobante) == "tif" || extrae_extencion(comprobante) == "webp" ||
      extrae_extencion(comprobante) == "bmp" || extrae_extencion(comprobante) == "svg" ) {
      
      url = `${ruta}${comprobante}`;

      data_comprobante = `<div class="col-md-12 mt-2 text-center"><i>${nombre_download}.${extrae_extencion(comprobante)}</i></div> <div class="col-md-12 mt-2"><img src="${url}" alt="img" class="img-thumbnail" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" ></div>`;         
      
    } else { 

      if (extrae_extencion(comprobante) == "pdf") {

        url = `${ruta}${comprobante}`;

        data_comprobante = `<div class="col-md-12 mt-2 text-center"><i>${nombre_download}.${extrae_extencion(comprobante)}</i></div> <div class="col-md-12 mt-2"><iframe src="${url}" frameborder="0" scrolling="no" width="100%" height="410"> </iframe></div>`;      

      } else {
        
        url = `${ruta}${comprobante}`;

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

function desccargar_zip_recibos_honorarios() {   

  $('.btn-zip').addClass('disabled btn-danger').removeClass('btn-success');
  $('.btn-zip').html('<i class="fas fa-spinner fa-pulse fa-sm"></i> Comprimiendo datos');


  $.post("../ajax/resumen_rh.php?op=data_recibos_honorarios", { }, function (data, status) {
    
    data = JSON.parse(data);  //console.log(data);    
    
    const zip = new JSZip();  let count = 0; const zipFilename = "Recibos_honorario.zip";
    
    if (data.length === 0) {
      $('.btn-zip').removeClass('disabled btn-danger').addClass('btn-success');
      $('.btn-zip').html('<i class="far fa-file-archive fa-lg"></i> Recibos honorario .zip');
      toastr.error("No hay docs para descargar!!!");
    }else{
      data.forEach(async function (value){
         
        const urlArr = value.ruta_nube.split('/');
        const filename = urlArr[urlArr.length - 1];
  
        try {   
           
          const file = await JSZipUtils.getBinaryContent(value.ruta_nube)
          zip.file(filename, file, { binary: true});
          count++;
          if(count === data.length) {
            zip.generateAsync({type:'blob'}).then(function(content) {
              saveAs(content, zipFilename);
              $('.btn-zip').removeClass('disabled btn-danger').addClass('btn-success');
              $('.btn-zip').html('<i class="far fa-file-archive fa-lg"></i> Recibos honorario .zip');
            });
          }           
          
        } catch (err) {
          console.log(err);
        }
      });
    }    

  });  
}




init();

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function UrlExists(url) {  
  var http = new XMLHttpRequest();
  http.open("HEAD", url, false);
  http.send();
  console.log(http.status);
  return http.status;
}

function formato_miles(num) {
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
  return (sign ? "" : "-") + num + "." + cents;
}

function extrae_extencion(filename) {
  return filename.split(".").pop();
}

