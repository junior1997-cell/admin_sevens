            //            $parametros_ver_doc='\'' . $value['doc_valorizacion'] .'\', \'' . $value['indice'] .'\', \'' . $value['nombre'] .'\', \'' . $value['numero_q_s'] .'\'';
            //ver documento
function modal_comprobante(doc_valorizacion,indice,nombre,numero_q_s,) {
    
    $("#modal-ver-comprobante").modal("show");

    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(doc_valorizacion) == "xls") {

      $('#ver-documento').html(
        '<div class="col-lg-4">'+
        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+doc_valorizacion+'" download="'+indice+' '+nombre+' - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+numero_q_s+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
            '<i class="fas fa-download"></i> Descargar'+
        '</a>'+
        '</div>'+
        '<div class="col-lg-4 mb-4">'+
        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
            '<i class="fas fa-expand"></i> Ver completo'+
        '</a>'+
        '</div>'+
        '<div class="col-lg-12 ">'+
        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
            '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
        '</div>'+
        '</div>'
      );

    } else {

      if ( extrae_extencion(doc_valorizacion) == "xlsx" ) {
          
        $('#ver-documento').html(
          '<div class="col-lg-4">'+
          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+doc_valorizacion+'" download="'+indice+' '+nombre+' - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+numero_q_s+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
              '<i class="fas fa-download"></i> Descargar'+
          '</a>'+
          '</div>'+
          '<div class="col-lg-4 mb-4">'+
              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
              '<i class="fas fa-expand"></i> Ver completo'+
              '</a>'+
          '</div>'+
          '<div class="col-lg-12 ">'+
              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
              '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
              '</div>'+
          '</div>'
        );

      }else{

        if ( extrae_extencion(doc_valorizacion) == "csv" ) {
            
          $('#ver-documento').html(
            '<div class="col-lg-4">'+
            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+doc_valorizacion+'" download="'+indice+' '+nombre+' - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+numero_q_s+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                '<i class="fas fa-download"></i> Descargar'+
            '</a>'+
            '</div>'+
            '<div class="col-lg-4 mb-4">'+
            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                '<i class="fas fa-expand"></i> Ver completo'+
            '</a>'+
            '</div>'+
            '<div class="col-lg-12 ">'+
            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
            '</div>'+
            '</div>'
          );

        }else{

          if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

            $('#ver-documento').html(
              '<div class="col-lg-4">'+
              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+doc_valorizacion+'" download="'+indice+' '+nombre+' - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+numero_q_s+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                  '<i class="fas fa-download"></i> Descargar'+
              '</a>'+
              '</div>'+
              '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                  '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
              '</div>'+
              '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                  '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                  '</div>'+
              '</div>'
            );

          }else{

            if ( extrae_extencion(doc_valorizacion) == "pdf" ) {

              $('#ver-documento').html(
                '<div class="col-lg-4">'+
                '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+doc_valorizacion+'" download="'+indice+' '+nombre+' - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+numero_q_s+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+doc_valorizacion+'"  target="_blank"  type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                    '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                '</div>'+
                '</div>'
              );      
            }else{
              
              if ( extrae_extencion(doc_valorizacion) == "doc" ) {

                  $('#ver-documento').html(
                    '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+doc_valorizacion+'" download="'+indice+' '+nombre+' - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+numero_q_s+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                        '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                    '</div>'
                  );     
              }else{

                if ( extrae_extencion(doc_valorizacion) == "docx" ) {

                  $('#ver-documento').html(
                    '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+doc_valorizacion+'" download="'+indice+' '+nombre+' - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+numero_q_s+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                        '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                    '</div>'+
                    '</div>'
                  ); 

                }else{

                  $('#ver-documento').html(
                    '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+doc_valorizacion+'" download="'+indice+' '+nombre+' - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+numero_q_s+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                        '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                    '</div>'+
                    '</div>'
                  );
                }
              }
            }
          }
        }
      }
    }

}

if (doc != "") {
  $("#doc_old_7").val(doc);

  // cargamos la imagen adecuada par el archivo
  if (extrae_extencion(doc) == "xls") {
    $("#doc7_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
  } else {
    if (extrae_extencion(doc) == "xlsx") {
      $("#doc7_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
    } else {
      if (extrae_extencion(doc) == "csv") {
        $("#doc7_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
      } else {
        if (extrae_extencion(doc) == "xlsm") {
          $("#doc7_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
        } else {
          if (extrae_extencion(doc) == "pdf") {
            $("#doc7_ver").html('<iframe src="../dist/docs/valorizacion/' + doc + '" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');
          } else {
            if (extrae_extencion(doc) == "dwg") {
              $("#doc7_ver").html('<img src="../dist/svg/dwg.svg" alt="" width="50%" >');
            } else {
              if (extrae_extencion(doc) == "zip" || extrae_extencion(doc) == "rar" || extrae_extencion(doc) == "iso") {
                $("#doc7_ver").html('<img src="../dist/img/default/zip.png" alt="" width="50%" >');
              } else {
                if ( extrae_extencion(doc) == "jpeg" || extrae_extencion(doc) == "jpg" || extrae_extencion(doc) == "jpe" ||
                  extrae_extencion(doc) == "jfif" || extrae_extencion(doc) == "gif" || extrae_extencion(doc) == "png" ||
                  extrae_extencion(doc) == "tiff" || extrae_extencion(doc) == "tif" || extrae_extencion(doc) == "webp" ||
                  extrae_extencion(doc) == "svg" ||  extrae_extencion(doc) == "bmp"  ) {
                  $("#doc7_ver").html('<img src=".../dist/docs/valorizacion/' + doc + '" alt="" width="50%" >');
                } else {
                  if (extrae_extencion(doc) == "docx" || extrae_extencion(doc) == "docm" || extrae_extencion(doc) == "dotx" || extrae_extencion(doc) == "dotm" || extrae_extencion(doc) == "doc" || extrae_extencion(doc) == "dot") {
                    $("#doc7_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
                  } else {
                    $("#doc7_ver").html('<img src="../dist/svg/doc_default.svg" alt="" width="50%" >');
                  }
                }
              }
            }
          }
        }
      }
    }
  }

}