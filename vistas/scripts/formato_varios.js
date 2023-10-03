$("#bloc_Tecnico").addClass("menu-open");

$("#mTecnico").addClass("active");

$("#lformatos_varios").addClass("active bg-primary");

function data_format_ats(nube_idproyecto) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $('#btn-export-ats').attr('href', `toastr_error('Espera', 'Los datos aun se esta cargando', 700);`);

  $.post("../ajax/formatos_varios.php?op=data_format_ats", {'nube_idproyecto': nube_idproyecto}, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      // ==== ATS ======
      $('#ubicacion').val("")
      $('#ubicacion').val(e.proyecto.ubicacion)
      // ==== TEMPERATURA ======
      $('#proyec_temperatura').html("")
      $('#ubic_temperatura').html("")
      $('#proyec_temperatura').html(e.proyecto.nombre_proyecto)
      $('#ubic_temperatura').html(e.proyecto.ubicacion)
      
      // ==== CHECK LIST EPPS ======

      $('#proyec_check_list').html("")
      $('#proyec_check_list').html(e.proyecto.nombre_proyecto)
        
      
      var cant_t = e.data.length;
      var cant_mitad_t = parseInt(e.data.length/2); console.log('la mitad es:' + cant_mitad_t);
      // console.log('Cant_t '+cant_t); 
      // console.log('cant_mitad_t '+cant_mitad_t);
      var html_ats = '', html_temperatura = '', html_check_lit_epps = '';
      
      e.data.forEach((val, key) => {

        // console.log(key, cant_mitad_t);
        // data TAS
        if (cant_mitad_t < cant_t) {

          cant_mitad_t++;
          var data_mitad = e.data[cant_mitad_t]; //console.log(data_mitad);

          var trabajador = ''; console.log(val.orden);

          if (data_mitad === undefined) {  } else {
            // console.log(data_mitad.trabajador);
            trabajador = `<td class="p-y-2px">${data_mitad.orden}</td><td colspan="2" class="p-y-2px">${data_mitad.trabajador}</td> <td class="p-y-2px">---</td>`;
          }           

          html_ats = html_ats.concat(`
            <tr>
              <td class="p-y-2px w-10px">${val.orden}</td> <td colspan="2" class="p-y-2px">${val.trabajador}</td> <td colspan="2" class="p-y-2px">---</td> ${trabajador}
            </tr>
          `);           
        }      
        
        // DATA TEMPERATURA
        html_temperatura = html_temperatura.concat(`
          <tr>
            <td class="p-y-2px">${key+1}</td>
            <td class="p-y-2px" >${val.trabajador}</td>
            <td class="p-y-2px"> --- </td>
            <td class="p-y-2px"> --- </td>
            <td class="p-y-2px"> --- </td>
            <td class="p-y-2px"> --- </td>
            <td class="p-y-2px"> --- </td>
            <td class="p-y-2px"> --- </td>
            <td class="p-y-2px"> --- </td>
            <td class="p-y-2px"> --- </td>
          </tr>
        `);

        // DATA CHECK LIST 
        html_check_lit_epps = html_check_lit_epps.concat(`
          <tr>
            <td class="p-y-2px">${key+1}</td>
            <td class="p-y-2px text-nowrap">${val.trabajador}</td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
            <td class="p-y-2px"> -- </td>
          </tr>
        `);
      });

      $(`#formato_ats>tbody`).html(html_ats);
      $(`#formato_temperatura>tbody`).html(html_temperatura);
      $(`#formato_check_list_epps>tbody`).html(html_check_lit_epps);

      $('#btn-export-ats').attr('href', `../reportes/export_xlsx_format_ats.php?id_proyecto=${nube_idproyecto}`);
      $('#btn-export-temperatura').attr('href', `../reportes/export_xlsx_format_temperatura.php?id_proyecto=${nube_idproyecto}`);
      $('#btn-export-check-list-epps').attr('href', `../reportes/export_xlsx_format_check_list.php?id_proyecto=${nube_idproyecto}`);
      $('#btn-export-asistencia_trabajador').attr('href', `../reportes/export_xlsx_format_asistencia_plantilla.php?id_proyecto=${nube_idproyecto}`);
      $('#btn-export-controlEPP_trabajador').attr('href', `../reportes/export_xlsx_format_controlepp.php?id_proyecto=${nube_idproyecto}`);

      $('#btn-export-ats').attr('target', `_blank`);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

data_format_ats(localStorage.getItem('nube_idproyecto'));

function export_excel_ats() {
  
  $tabla = document.querySelector("#formato_ats_v2");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Formato ats", //Nombre del archivo de Excel
    sheetname: "Temperatura", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.formato_ats_v2.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

  // $.post("../ajax/formatos_varios.php?op=dowload_format_ats", {'nube_idproyecto': localStorage.getItem('nube_idproyecto')}, function (e, status) {
  //   e = JSON.parse(e);  console.log(e);  
  //   if (e.status == true) {
      
  //   } else {
  //     ver_errores(e);
  //   }
  // }).fail( function(e) { ver_errores(e); } );
}

function export_excel_detalle_temperatura() {
  $tabla = document.querySelector("#formato_temperatura");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Formato temperatura", //Nombre del archivo de Excel
    sheetname: "Temperatura", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.formato_temperatura.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}

function export_excel_control_equipos() {
  $tabla = document.querySelector("#formato_check_list_epps");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Formato check list epps", //Nombre del archivo de Excel
    sheetname: "Check list", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.formato_check_list_epps.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}

  
