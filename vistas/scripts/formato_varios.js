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