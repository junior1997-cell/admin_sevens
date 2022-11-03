function export_excel_detalle_factura() {
  $tabla = document.querySelector("#formato_ats");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Formato_Ats", //Nombre del archivo de Excel
    sheetname: "ATS", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.formato_ats.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}

function export_excel_detalle_temperatura() {
  $tabla = document.querySelector("#formato_temperatura");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "formato_temperatura", //Nombre del archivo de Excel
    sheetname: "Temperatura", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.formato_temperatura.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}

function export_excel_control_equipos() {
  $tabla = document.querySelector("#formato_control_equipos");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "formato_control_equipos", //Nombre del archivo de Excel
    sheetname: "Temperatura", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.formato_control_equipos.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}

function data_format_ats(nube_idproyecto) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $.post("../ajax/formatos_varios.php?op=data_format_ats", {'nube_idproyecto': nube_idproyecto}, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      
      var cant_t = e.data.length;
      var cant_mitad_t = e.data.length/2; console.log('la mitad es:' + cant_mitad_t);
      var html_trabajador = '';
      
      e.data.forEach((val, key) => {
        if (cant_mitad_t < cant_t) {
          var data_mitad = e.data[cant_mitad_t];
          console.log(val.trabajador); 
          if (data_mitad === undefined) {  } else {
            console.log(data_mitad.trabajador);
          }
          console.log(key, cant_mitad_t);

          

          html_trabajador = html_trabajador.concat(`
            <tr>
              <td class="p-y-2px">${key+1}</td>
              <td class="p-y-2px">${val.trabajador}</td>
              <td class="p-y-2px"></td>
              <td class="p-y-2px">${cant_mitad_t}</td>
              <td class="p-y-2px">${data_mitad.trabajador}</td>
              <td class="p-y-2px"></td>
            </tr>
          `);

          
          
          cant_mitad_t++;
        } 
       
      });

      $(`#formato_ats>tbody`).html(html_trabajador);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}


data_format_ats(localStorage.getItem('nube_idproyecto'));