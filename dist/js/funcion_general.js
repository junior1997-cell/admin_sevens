/*  ══════════════════════════════════════════ - F E C H A S - ══════════════════════════════════════════ */

// Función que suma o resta días a la fecha indicada
sumaFecha = function(d, fecha){
  var Fecha = new Date();
  var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
  var sep = sFecha.indexOf('/') != -1 ? '/' : '-';
  var aFecha = sFecha.split(sep);
  var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];
  fecha= new Date(fecha);
  fecha.setDate(fecha.getDate()+parseInt(d));
  var anno=fecha.getFullYear();
  var mes= fecha.getMonth()+1;
  var dia= fecha.getDate();
  mes = (mes < 10) ? ("0" + mes) : mes;
  dia = (dia < 10) ? ("0" + dia) : dia;
  var fechaFinal = dia+sep+mes+sep+anno;
  return (fechaFinal);
}

// extrae los nombres de dias de semana "Completo"
function extraer_dia_semana_completo(fecha) {

  var nombreDia = "";

  if (fecha == '' || fecha == null || fecha == '0000-00-00') { nombreDia = "-"; } else {
    const fechaComoCadena = fecha; // día fecha
    const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']; //
    const numeroDia = new Date(fechaComoCadena).getDay();
    nombreDia = dias[numeroDia];
  }
  return nombreDia;
}

function extraer_nombre_mes(fecha) {

  var nombre_completo = "";

  if (fecha == '' || fecha == null || fecha == '0000-00-00') { nombre_completo = "-"; } else {
    const array_mes = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    
    let date = new Date(fecha.replace(/-+/g, '/'));
      
    var mes_indice = date.getMonth();

    nombre_completo = array_mes[mes_indice];
  }

  return nombre_completo;
}

// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {
  var format = "";
  if (fecha == '' || fecha == null || fecha == '0000-00-00') { format = "-"; } else {
    let splits = fecha.split("-"); //console.log(splits);
    format = `${splits[2]}-${splits[1]}-${splits[0]}`;
  } 
  return format;
}

// convierte de una fecha(aa-mm-dd): 23-12-2021 a una fecha(dd-mm-aa): 2021-12-23
function format_a_m_d(fecha) {
  var format = "";
  if (fecha == '' || fecha == null || fecha == '00-00-0000') { format = "-"; } else {
    let splits = fecha.split("-"); //console.log(splits);
    format = `${splits[2]}-${splits[1]}-${splits[0]}`;
  } 
  return format;
}

// convierte de una fecha(mm-dd-aa): 23-12-2021 a una fecha(mm-dd-aa): 12-23-2021
function format_m_d_a(fecha) {
  var format = "";
  if (fecha == '' || fecha == null || fecha == '00-00-0000') { format = "-"; } else {
    let splits = fecha.split("-"); //console.log(splits);
    format = `${splits[1]}-${splits[0]}-${splits[2]}`;
  } 
  return format;
}

// restringimos la fecha para no elegir mañana
function no_select_tomorrow(nombre_input) {  
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!
  var yyyy = today.getFullYear();
  if(dd<10){ dd='0'+dd } 
  if(mm<10){ mm='0'+mm } 

  today = `${yyyy}-${mm}-${dd}`;
  
  $(nombre_input).attr('max',today);
}

/*  ══════════════════════════════════════════ - N U M E R I C O S - ══════════════════════════════════════════ */

// Formato de miles
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

// Quitar formato de miles
function quitar_formato_miles(num) {
  let inVal = 0;
  if (!num || num == "NaN" || num == "" || num == null || num == "Infinity" || num === undefined) { } else {
    inVal = parseFloat( num.replace(/,/g, "") );
  }
  return inVal;
}

// Redondear a un exponente
function redondearExp(numero, digitos) {
  function toExp(numero, digitos) {
    let arr = numero.toString().split("e");
    let mantisa = arr[0], exponente = digitos;
    if (arr[1]) exponente = Number(arr[1]) + digitos;
    return Number(mantisa + "e" + exponente.toString());
  }
  let entero = Math.round(toExp(Math.abs(numero), digitos));
  return Math.sign(numero) * toExp(entero, -digitos);
}

//Redondear 2 decimales (1.56 = 1.60, 1.52 = 1.50)
function roundTwo(num) { return Number(+(Math.round(num + "e+1") + "e-1")).toFixed(2); }

// Unico ID
function unique_id() { return parseInt(Math.round(new Date().getTime() + Math.random() * 100)); }

/*  ══════════════════════════════════════════ - S T R I N G - ══════════════════════════════════════════ */

// Codificamos los caracteres: &, <, >, ", '
function encodeHtml(str) {
  var encode = "";
  if (str == "" || str == null || str === undefined) { } else {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    encode = str.replace(/[&<>"']/g, function(m) {return map[m];}); //console.log(encode);
  }
  return encode;
}

// Decodificamos los caracteres: &amp; &lt; &gt; &quot; &#039;
function decodeHtml(str) {
  var decode = "";
  if (str == "" || str == null || str === undefined) { } else {
    var map = {
      '&amp;': '&',
      '&lt;': '<',
      '&gt;': '>',
      '&quot;': '"',
      '&#039;': "'"
    };
    decode = str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(m) {return map[m];}); //console.log(decode);
  }
  return decode;
}

function removeHtml(str) {
  if ((str===null) || (str==='')){
    return '';
  }else{
    str = str.toString();
    return str.replace( /(<([^>]+)>)/ig, '');
  }
}

function removeCaracterEspecial(str) {
  var string = "";
  if (str == "" || str == null || str === undefined) { } else {     
    string = str.replace(/[`~!@#$%^&*()_|+\-=?;:°'",.<>\{\}\[\]\\\/]/g, '');
  }
  return string;
}

function preservarNumeroLetra(str) {
  var string = "";
  if (str == "" || str == null || str === undefined) { } else {     
   string = str.replace(/[^a-zA-Z 0-9.]/g, '');    
  }
  return string;
}

// to miniscula
function convert_minuscula(e) { e.value = e.value.toLowerCase(); }

function quitar_punto(string){ 
  return string.replace(/\./g,'');
}

function replace_punto_a_guion(params) {
  return string.replace(/\./g,'-');
}

function quitar_guion(str) {

  if (str == '' || str == null ) {
    return "-";
  } else {
    return str.replace("-", "");
  }  
}

/*  ══════════════════════════════════════════ - S U B I R   D O C S  - ══════════════════════════════════════════ */

/* PREVISUALIZAR: img */
function addImage(e, id, img_default='') {
  // colocamos cargando hasta que se vizualice
  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

	console.log(id);

	var file = e.target.files[0], imageType = /image.*/;
	
	if (e.target.files[0]) {

		var sizeByte = file.size;

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(imageType)){
			// return;
			toastr.error('Este tipo de ARCHIVO no esta permitido <br> elija formato: <b>.png .jpeg .jpg .webp etc... </b>');

			if (img_default == '' || img_default == null || img_default == false || img_default == true ) {
        $("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");
      } else {
        $("#"+id+"_i").attr("src", img_default);
      }      

		}else{

			if (sizekiloBytes <= 10240) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;

					$("#"+id+"_i").attr("src", result);

					$("#"+id+"_nombre").html(''+
						'<div class="row">'+
              '<div class="col-md-12">'+
              file.name +
              '</div>'+
              '<div class="col-md-12">'+
              '<button  class="btn btn-danger  btn-block" onclick="'+id+'_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>'+
              '</div>'+
            '</div>'+
					'');

					toastr.success('Imagen aceptada.')
				}

				reader.readAsDataURL(file);

			} else {

				toastr.warning('La imagen: '+file.name.toUpperCase()+' es muy pesada. Tamaño máximo 10mb')

				$("#"+id+"_i").attr("src", "../dist/img/default/img_error.png");

				$("#"+id).val("");
			}
		}

	}else{

		toastr.error('Seleccione una Imagen');

    if (img_default == '' || img_default == null || img_default == false || img_default == true ) {
      $("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");
    } else {
      $("#"+id+"_i").attr("src", img_default);
    }  

		$("#"+id+"_nombre").html("");
	}
}

/* PREVISUALIZA: img, pdf, doc, excel,  */
function addImageApplication(e, id, img_default='') {

  $(`#${id}_ver`).html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');	console.log(id);

	var file = e.target.files[0], archivoType = /image.*|application.*/;
	
	if (e.target.files[0]) {
    
		var sizeByte = file.size; console.log(file.type);

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(archivoType) ){
			// return;
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Este tipo de ARCHIVO no esta permitido elija formato: .pdf, .png. .jpeg, .jpg, .jpe, .webp, .svg',
        showConfirmButton: false,
        timer: 1500
      });

      if (img_default == '' || img_default == null || img_default == false || img_default == true ) {
        $(`#${id}_ver`).html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >'); 
      } else {
        $(`#${id}_ver`).html(`<img src="${img_default}" alt="" width="50%" >`); 
      }
      

		}else{

			if (sizekiloBytes <= 40960) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;

          // cargamos la imagen adecuada par el archivo
				  if ( extrae_extencion(file.name) == "doc") {
            $(`#${id}_ver`).html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
          } else if ( extrae_extencion(file.name) == "docx" ) {             
            $(`#${id}_ver`).html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
          }else if ( extrae_extencion(file.name) == "pdf" ) {              
            $(`#${id}_ver`).html(`<iframe src="${result}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
          }else if ( extrae_extencion(file.name) == "csv" ) {              
            $(`#${id}_ver`).html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
          } else if ( extrae_extencion(file.name) == "xls" ) {             
            $(`#${id}_ver`).html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
          } else if ( extrae_extencion(file.name) == "xlsx" ) {             
            $(`#${id}_ver`).html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
          } else if ( extrae_extencion(file.name) == "xlsm" ) {             
            $(`#${id}_ver`).html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
          } else if (
            extrae_extencion(file.name) == "jpeg" || extrae_extencion(file.name) == "jpg" || extrae_extencion(file.name) == "jpe" ||
            extrae_extencion(file.name) == "jfif" || extrae_extencion(file.name) == "gif" || extrae_extencion(file.name) == "png" ||
            extrae_extencion(file.name) == "tiff" || extrae_extencion(file.name) == "tif" || extrae_extencion(file.name) == "webp" ||
            extrae_extencion(file.name) == "bmp" || extrae_extencion(file.name) == "svg" ) {

            $(`#${id}_ver`).html(`<img src="${result}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
              
          } else {
            $(`#${id}_ver`).html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
          }
           
					$("#"+id+"_nombre").html(`<div class="row">
            <div class="col-md-12">
              <i> ${file.name} </i>
            </div>
            <div class="col-md-12">
              <button class="btn btn-danger btn-block btn-xs" onclick="${id}_eliminar();" type="button" ><i class="far fa-trash-alt"></i></button>
            </div>
          </div>`);

          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `El documento: ${file.name.toUpperCase()} es aceptado.`,
            showConfirmButton: false,
            timer: 1500
          });
				}

				reader.readAsDataURL(file);

			} else {
        Swal.fire({
          position: 'top-end',
          icon: 'warning',
          title: `El documento: ${file.name.toUpperCase()} es muy pesado.`,
          showConfirmButton: false,
          timer: 1500
        })

        if (img_default == '' || img_default == null || img_default == false || img_default == true ) {
          $(`#${id}_ver`).html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >'); 
        } else {
          $(`#${id}_ver`).html(`<img src="${img_default}" alt="" width="50%" >`); 
        }
        
        $("#"+id+"_nombre").html("");
				$("#"+id).val("");
			}
		}
	}else{
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Seleccione un documento',
      showConfirmButton: false,
      timer: 1500
    });

    if (img_default == '' || img_default == null || img_default == false || img_default == true ) {
      $(`#${id}_ver`).html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >'); 
    } else {
      $(`#${id}_ver`).html(`<img src="${img_default}" alt="" width="50%" >`); 
    }		 
    
		$("#"+id+"_nombre").html("");
    $("#"+id).val("");
	}	
}

// recargar un doc para ver
function re_visualizacion(id, carpeta, sub_carpeta) {

  $("#doc"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>'); console.log(id);

  pdffile     = document.getElementById("doc"+id+"").files[0];

  var antiguopdf  = $("#doc_old_"+id+"").val();

  if(pdffile === undefined){

    if (antiguopdf == "") {

      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Seleccione un documento',
        showConfirmButton: false,
        timer: 1500
      })

      $("#doc"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

		  $("#doc"+id+"_nombre").html("");

    } else {
      if ( extrae_extencion(antiguopdf) == "doc") {
        $("#doc"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
        toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
      } else {
        if ( extrae_extencion(antiguopdf) == "docx" ) {
          $("#doc"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
          toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
        } else {
          if ( extrae_extencion(antiguopdf) == "pdf" ) {
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/${carpeta}/${sub_carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
            toastr.success('Documento vizualizado correctamente!!!')
          } else {
            if ( extrae_extencion(antiguopdf) == "csv" ) {
              $("#doc"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
              toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
            } else {
              if ( extrae_extencion(antiguopdf) == "xls" ) {
                $("#doc"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
                toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
              } else {
                if ( extrae_extencion(antiguopdf) == "xlsx" ) {
                  $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                  toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                } else {
                  if ( extrae_extencion(antiguopdf) == "xlsm" ) {
                    $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                    toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                  } else {
                    if (
                      extrae_extencion(antiguopdf) == "jpeg" || extrae_extencion(antiguopdf) == "jpg" || extrae_extencion(antiguopdf) == "jpe" ||
                      extrae_extencion(antiguopdf) == "jfif" || extrae_extencion(antiguopdf) == "gif" || extrae_extencion(antiguopdf) == "png" ||
                      extrae_extencion(antiguopdf) == "tiff" || extrae_extencion(antiguopdf) == "tif" || extrae_extencion(antiguopdf) == "webp" ||
                      extrae_extencion(antiguopdf) == "bmp" || extrae_extencion(antiguopdf) == "svg" ) {
  
                      $("#doc"+id+"_ver").html(`<img src="../dist/docs/${carpeta}/${sub_carpeta}/${antiguopdf}" alt="" onerror="this.src='../dist/svg/error-404-x.svg';" width="100%" >`);
                      toastr.success('Documento vizualizado correctamente!!!');
                    } else {
                      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                      toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                    }                    
                  }
                }
              }
            }
          }
        }
      }      
    }
    // console.log('hola'+dr);
  }else{

    pdffile_url=URL.createObjectURL(pdffile);

    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(pdffile.name) == "doc") {
      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
      toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
    } else {
      if ( extrae_extencion(pdffile.name) == "docx" ) {
        $("#doc"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
        toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
      }else{
        if ( extrae_extencion(pdffile.name) == "pdf" ) {
          $("#doc"+id+"_ver").html('<iframe src="'+pdffile_url+'" frameborder="0" scrolling="no" width="100%" height="310"> </iframe>');
          toastr.success('Documento vizualizado correctamente!!!');
        }else{
          if ( extrae_extencion(pdffile.name) == "csv" ) {
            $("#doc"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
            toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
          } else {
            if ( extrae_extencion(pdffile.name) == "xls" ) {
              $("#doc"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
              toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
            } else {
              if ( extrae_extencion(pdffile.name) == "xlsx" ) {
                $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
              } else {
                if ( extrae_extencion(pdffile.name) == "xlsm" ) {
                  $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                  toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
                } else {
                  if (
                    extrae_extencion(pdffile.name) == "jpeg" || extrae_extencion(pdffile.name) == "jpg" || extrae_extencion(pdffile.name) == "jpe" ||
                    extrae_extencion(pdffile.name) == "jfif" || extrae_extencion(pdffile.name) == "gif" || extrae_extencion(pdffile.name) == "png" ||
                    extrae_extencion(pdffile.name) == "tiff" || extrae_extencion(pdffile.name) == "tif" || extrae_extencion(pdffile.name) == "webp" ||
                    extrae_extencion(pdffile.name) == "bmp" || extrae_extencion(pdffile.name) == "svg" ) {

                    $("#doc"+id+"_ver").html(`<img src="${pdffile_url}" alt="" width="100%" >`);
                    toastr.success('Documento vizualizado correctamente!!!');
                  } else {
                    $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                    toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
                  }                  
                }
              }
            }
          }
        }
      }
    }     	
    console.log(pdffile);
  }
}

function doc_view_extencion(filename, carpeta, sub_carpeta='', width='50%', height='auto') {

  var html = ''; var ruta = sub_carpeta==''?  `../dist/docs/${carpeta}/${sub_carpeta}/${filename}`: `../dist/docs/${carpeta}/${sub_carpeta}/${filename}` ;
  var extencion = '';

  // cargamos la imagen adecuada par el archivo
  if ( extrae_extencion(filename) == "xls") {

    html = `<img src="../dist/svg/xls.svg" alt="" width="${width}" height="${height}" >`;
    extencion = extrae_extencion(filename);

  } else if ( extrae_extencion(filename) == "xlsx" ) {    

    html = `<img src="../dist/svg/xlsx.svg" alt="" width="${width}" height="${height}" >`;
    extencion = extrae_extencion(filename);

  }else if ( extrae_extencion(filename) == "csv" ) {

    html = `<img src="../dist/svg/csv.svg" alt="" width="${width}" height="${height}" >`;
    extencion = extrae_extencion(filename);

  }else if ( extrae_extencion(filename) == "xlsm" ) {

    html = `<img src="../dist/svg/xlsm.svg" alt="" width="${width}" height="${height}" >`;
    extencion = extrae_extencion(filename);

  }else if ( extrae_extencion(filename) == "docx" ) {

    html = `<img src="../dist/svg/docx.svg" alt="" width="${width}" height="${height}" >`;
    extencion = extrae_extencion(filename);

  }else if ( extrae_extencion(filename) == "doc") {

    html = `<img src="../dist/svg/doc.svg" alt="" width="${width}" height="${height}" >`;
    extencion = extrae_extencion(filename);

  }else if ( extrae_extencion(filename) == "pdf" ) {
    //recomendado - height="210" 
    html = `<iframe src="${ruta}" onerror="this.src='../dist/svg/404-v2.svg';" frameborder="0" scrolling="no" width="${width}" height="${height}"> </iframe>`;
    extencion = extrae_extencion(filename);
  
  } else if (
    extrae_extencion(filename) == "jpeg" || extrae_extencion(filename) == "jpg" || extrae_extencion(filename) == "jpe" ||
    extrae_extencion(filename) == "jfif" || extrae_extencion(filename) == "gif" || extrae_extencion(filename) == "png" ||
    extrae_extencion(filename) == "tiff" || extrae_extencion(filename) == "tif" || extrae_extencion(filename) == "webp" ||
    extrae_extencion(filename) == "bmp" || extrae_extencion(filename) == "svg" ) {

    html = `<img src="${ruta}" alt="" width="${width}" height="${height}" onerror="this.src='../dist/svg/404-v2.svg';" >`;
    extencion = extrae_extencion(filename);
    
  }else{
    //height="${height}"
    html = `<img src="../dist/svg/doc_si_extencion.svg" alt="" width="${width}" >`;
    extencion = extrae_extencion(filename);
    
  }

  return html;
}

function doc_view_icon(filename, color_class='', font_size_class='' ) {

  // cargamos la imagen adecuada par el archivo
  if ( extrae_extencion(filename) == "xls") {

    html = `<i class="far fa-file-excel ${(color_class==''? 'text-success': color_class)} ${font_size_class}"></i>`;

  } else if ( extrae_extencion(filename) == "xlsx" ) {    

    html = `<i class="far fa-file-excel ${(color_class==''? 'text-success': color_class)} ${font_size_class}"></i>`;

  }else if ( extrae_extencion(filename) == "csv" ) {

    html = `<i class="fas fa-file-csv ${(color_class==''? 'text-success': color_class)} ${font_size_class}"></i>`;

  }else if ( extrae_extencion(filename) == "xlsm" ) {

    html = `<i class="far fa-file-excel ${(color_class==''? 'text-success': color_class)} ${font_size_class}"></i>`;

  }else if ( extrae_extencion(filename) == "docx" ) {

    html = `<i class="fas fa-file-word ${(color_class==''? 'text-primary': color_class)} ${font_size_class}"></i>`;

  }else if ( extrae_extencion(filename) == "doc") {

    html = `<i class="fas fa-file-word ${(color_class==''? 'text-primary': color_class)} ${font_size_class}"></i>`;


  }else if ( extrae_extencion(filename) == "pdf" ) {
    
    html = `<i class="far fa-file-pdf ${(color_class==''? 'text-danger': color_class)} ${font_size_class}"></i>`;
  
  } else if (
    extrae_extencion(filename) == "jpeg" || extrae_extencion(filename) == "jpg" || extrae_extencion(filename) == "jpe" ||
    extrae_extencion(filename) == "jfif" || extrae_extencion(filename) == "gif" || extrae_extencion(filename) == "png" ||
    extrae_extencion(filename) == "tiff" || extrae_extencion(filename) == "tif" || extrae_extencion(filename) == "webp" ||
    extrae_extencion(filename) == "bmp" || extrae_extencion(filename) == "svg" ) {

    html = `<i class="fas fa-file-image ${(color_class==''? 'text-primary': color_class)} ${font_size_class}"></i>`;
    
  }else{

    html = `<i class="fas fa-file-alt ${color_class} ${font_size_class}"></i>`;
    
  }

  return html;
}

function extrae_extencion(filename) { 
  var exten = "";
  if (filename == "" || filename == null || filename === undefined) {  }else{
    exten = filename.split(".").pop();
  }
  return exten; 
}

function pdf_o_img(filename) {
  data = false;
  if ( extrae_extencion(filename) == "pdf" ) {
    //recomendado - height="210" 
    data = true;

  } else if (
    extrae_extencion(filename) == "jpeg" || extrae_extencion(filename) == "jpg" || extrae_extencion(filename) == "jpe" ||
    extrae_extencion(filename) == "jfif" || extrae_extencion(filename) == "gif" || extrae_extencion(filename) == "png" ||
    extrae_extencion(filename) == "tiff" || extrae_extencion(filename) == "tif" || extrae_extencion(filename) == "webp" ||
    extrae_extencion(filename) == "bmp" || extrae_extencion(filename) == "svg" ) {

    data = true;
    
  }
  return data;
}

/*  ══════════════════════════════════════════ - A P I S - ══════════════════════════════════════════ */
// Buscar Reniec SUNAT
function buscar_sunat_reniec(input='') {
  console.log(input);

  $(`#search${input}`).hide(); $(`#charge${input}`).show();

  let tipo_doc = $(`#tipo_documento${input}`).val();

  let dni_ruc = $(`#num_documento${input}`).val(); 
   
  if (tipo_doc == "DNI") {

    if (dni_ruc.length == "8") {

      $.post("../ajax/ajax_general.php?op=reniec", { dni: dni_ruc }, function (data, status) {

        data = JSON.parse(data);  console.log(data);

        if (data == null) {

          $(`#search${input}`).show();
  
          $(`#charge${input}`).hide();
  
          toastr.error("Verifique su conexion a internet o el sistema de BUSQUEDA esta en mantenimiento.");
          
        } else {
          if (data.success == false) {

            $(`#search${input}`).show();

            $(`#charge${input}`).hide();

            toastr.error("Es probable que el sistema de busqueda esta en mantenimiento o los datos no existe en la RENIEC!!!");

          } else {

            $(`#search${input}`).show();

            $(`#charge${input}`).hide();

            $(`#nombre${input}`).val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);
            $(`#titular_cuenta${input}`).val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

            toastr.success("Persona encontrada!!!!");
          }
        }
        
      });
    } else {

      $(`#num_documento${input}`).addClass("is-invalid");

      $(`#search${input}`).show();

      $(`#charge${input}`).hide();

      toastr.info("Asegurese de que el DNI tenga 8 dígitos!!!");
    }
  } else {
    if (tipo_doc == "RUC") {

      if (dni_ruc.length == "11") {
        $.post("../ajax/ajax_general.php?op=sunat", { ruc: dni_ruc }, function (data, status) {

          data = JSON.parse(data);    console.log(data);

          if (data == null) {
            $(`#search${input}`).show();
    
            $(`#charge${input}`).hide();
    
            toastr.error("Verifique su conexion a internet o el sistema de BUSQUEDA esta en mantenimiento.");
            
          } else {

            if (data.success == false) {

              $(`#search${input}`).show();

              $(`#charge${input}`).hide();

              toastr.error("Datos no encontrados en la SUNAT!!!");
              
            } else {

              if (data.estado == "ACTIVO") {

                $(`#search${input}`).show();

                $(`#charge${input}`).hide();

                data.razonSocial == null ? $(`#nombre${input}`).val(data.nombreComercial) : $(`#nombre${input}`).val(data.razonSocial);
                data.razonSocial == null ? $(`#empresa${input}`).val(data.empresaComercial) : $(`#empresa${input}`).val(data.razonSocial);

                data.razonSocial == null ? $(`#titular_cuenta${input}`).val(data.nombreComercial) : $(`#titular_cuenta${input}`).val(data.razonSocial);

                var departamento = (data.departamento == null ? "" : data.departamento); 
                var provincia = (data.provincia == null ? "" : data.provincia);
                var distrito = (data.distrito == null ? "" : data.distrito);                

                data.direccion == null ? $(`#direccion${input}`).val(`${departamento} - ${provincia} - ${distrito}`) : $(`#direccion${input}`).val(data.direccion);
                data.direccion == null ? $(`#ubicacion${input}`).val(`${departamento} - ${provincia} - ${distrito}`) : $(`#ubicacion${input}`).val(data.direccion);

                toastr.success("Datos encontrados!!");

              } else {

                toastr.info("Se recomienda NO generar FACTURAS ó BOLETAS!!!");

                $(`#search${input}`).show();

                $(`#charge${input}`).hide();

                data.razonSocial == null ? $(`#nombre${input}`).val(data.nombreComercial) : $(`#nombre${input}`).val(data.razonSocial);
                data.razonSocial == null ? $(`#empresa${input}`).val(data.empresaComercial) : $(`#empresa${input}`).val(data.razonSocial);

                data.razonSocial == null ? $(`#titular_cuenta${input}`).val(data.nombreComercial) : $(`#titular_cuenta${input}`).val(data.razonSocial);
                
                var departamento = (data.departamento == null ? "" : data.departamento); 
                var provincia = (data.provincia == null ? "" : data.provincia);
                var distrito = (data.distrito == null ? "" : data.distrito);

                data.direccion == null ? $(`#direccion${input}`).val(`${data.departamento} - ${data.provincia} - ${data.distrito}`) : $(`#direccion${input}`).val(data.direccion);
                data.direccion == null ? $(`#ubicacion${input}`).val(`${departamento} - ${provincia} - ${distrito}`) : $(`#ubicacion${input}`).val(data.direccion);

              }
            }
          }          
        });
      } else {
        $(`#num_documento${input}`).addClass("is-invalid");

        $(`#search${input}`).show();

        $(`#charge${input}`).hide();

        toastr.info("Asegurese de que el RUC tenga 11 dígitos!!!");
      }
    } else {
      if (tipo_doc == "CEDULA" || tipo_doc == "OTRO") {

        $(`#search${input}`).show();

        $(`#charge${input}`).hide();

        toastr.info("No necesita hacer consulta");

      } else {

        $(`#tipo_documento${input}`).addClass("is-invalid");

        $(`#search${input}`).show();

        $(`#charge${input}`).hide();

        toastr.error("Selecione un tipo de documento");
      }
    }
  }
}

/*  ══════════════════════════════════════════ - M E N S A J E S - ══════════════════════════════════════════ */

function ok_dowload_doc() { toastr.success("El documento se descargara en breve!!"); }

function error_dowload_doc() { toastr.success("Hubo un ERROR en la descarga, reintente nuevamente!!"); }

function no_doc() { toastr.error("No hay DOC disponible, suba un DOC en el apartado de editar!!") }

/*  ══════════════════════════════════════════ - O T R O S - ══════════════════════════════════════════ */

function decifrar_format_banco(format) {

  var array_format =  format.split("-"); var format_final = "";
  
  array_format.forEach((item, index)=>{

    for (let index = 0; index < parseInt(item); index++) { format_final = format_final.concat("9"); }   

    if (parseInt(item) != 0) { format_final = format_final.concat("-"); }
  });

  var ultima_letra = format_final.slice(-1);
   
  if (ultima_letra == "-") { format_final = format_final.slice(0, (format_final.length-1)); }

  return format_final;
}

/*Validación Fecha de Nacimiento Mayoria de edad del usuario*/
function calcular_edad(input_fecha_nacimiento='', input_edad, span_edad='') {

  var fechaUsuario = $(input_fecha_nacimiento).val();

  if (fechaUsuario) {         
  
    //El siguiente fragmento de codigo lo uso para igualar la fecha de nacimiento con la fecha de hoy del usuario
    let d = new Date(),    month = '' + (d.getMonth() + 1),    day = '' + d.getDate(),   year = d.getFullYear();
    
    if (month.length < 2) 
      month = '0' + month;
    if (day.length < 2) 
      day = '0' + day;
    d=[year, month, day].join('-')

    /*------------*/
    var hoy = new Date(d);//fecha del sistema con el mismo formato que "fechaUsuario"

    var cumpleanos = new Date(fechaUsuario);
    
    //Calculamos años
    var edad = hoy.getFullYear() - cumpleanos.getFullYear();

    var m = hoy.getMonth() - cumpleanos.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {

      edad--;
    }

    // calculamos los meses
    var meses=0;

    if(hoy.getMonth()>cumpleanos.getMonth()){

      meses=hoy.getMonth()-cumpleanos.getMonth();

    }else if(hoy.getMonth()<cumpleanos.getMonth()){

      meses=12-(cumpleanos.getMonth()-hoy.getMonth());

    }else if(hoy.getMonth()==cumpleanos.getMonth() && hoy.getDate()>cumpleanos.getDate() ){

      if(hoy.getMonth()-cumpleanos.getMonth()==0){

        meses=0;
      }else{

        meses=11;
      }            
    }

    // Obtener días: día actual - día de cumpleaños
    let dias  = hoy.getDate() - cumpleanos.getDate();

    if(dias < 0) {
      // Si días es negativo, día actual es mayor al de cumpleaños,
      // hay que restar 1 mes, si resulta menor que cero, poner en 11
      meses = (meses - 1 < 0) ? 11 : meses - 1;
      // Y obtener días faltantes
      dias = 30 + dias;
    }

    // console.log(`Tu edad es de ${edad} años, ${meses} meses, ${dias} días`);
    $(input_edad).val(edad);

    $(span_edad).html(`${edad} años`);
    // calcular mayor de 18 años
    if(edad>=18){

      console.log("Eres un adulto");

    }else{
      // Calcular faltante con base en edad actual
      // 18 menos años actuales
      let edadF = 18 - edad;
      // El mes solo puede ser 0 a 11, se debe restar (mes actual + 1)
      let mesesF = 12 - (meses + 1);
      // Si el mes es mayor que cero, se debe restar 1 año
      if(mesesF > 0) { edadF --;  }
      let diasF = 30 - dias;
      // console.log(`Te faltan ${edadF} años, ${mesesF} meses, ${diasF} días para ser adulto`);
    }

  } else {

    $(input_edad).val("");

    $(span_edad).html(`0 años`); 
  }
}


function UrlExists(url) {  
  var http = new XMLHttpRequest();
  http.open("HEAD", url, false);
  http.send(); //console.log(http.status);
  return http.status;
}

