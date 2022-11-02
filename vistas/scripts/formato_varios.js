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