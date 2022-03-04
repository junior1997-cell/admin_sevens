var tabla;

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lValorizacion").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  ver_quincenas(localStorage.getItem('nube_idproyecto'));  

  $("#guardar_registro").on("click", function (e) {  $("#submit-form-trabajador").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();

  $("#doc7_i").click(function() {  $('#doc7').trigger('click'); });
  $("#doc7").change(function(e) {  addDocs2(e,$("#doc7").attr("id")) });

}

// ver las echas de quincenas
function ver_quincenas(nube_idproyecto) {

  $('#lista_quincenas').html('<i class="fas fa-spinner fa-pulse fa-2x"></i>'); //console.log(nube_idproyecto);

  $.post("../ajax/valorizacion.php?op=listarquincenas", { nube_idproyecto: nube_idproyecto }, function (data, status) {

    data =JSON.parse(data); console.log(data);    

    $('#lista_quincenas').html('');

    // VALIDAMOS LAS FECHAS DE QUINCENA
    if (data) {     
        
      if (data.fecha_valorizacion == "quincenal") {

        $(".h1-titulo").html("Valorización - Quincenal");

        var fecha = format_d_m_a(data.fecha_inicio);  
        
        var fecha_i = sumaFecha(0,fecha);
  
        var cal_quincena  =data.plazo/15; var i=0;  var cont=0;

        while (i <= cal_quincena) {

          cont = cont+1;
    
          var fecha_inicio = fecha_i;
          
          fecha = sumaFecha(14,fecha_inicio); 
  
          let fecha_ii = format_a_m_d(fecha_inicio); let fecha_ff = format_a_m_d(fecha);
          
          $('#lista_quincenas').append(` <button id="boton-${i}" type="button" class="btn bg-gradient-info text-center" onclick="fecha_quincena('${fecha_ii}', '${fecha_ff}', '${i}');"><i class="far fa-calendar-alt"></i> Valorización ${cont}<br>${fecha_inicio} // ${fecha}</button>`)
          
          fecha_i = sumaFecha(1,fecha);
    
          i++;
        }
      } else {

        if (data.fecha_valorizacion == "mensual") {

          $(".h1-titulo").html("Valorización - Mensual");

          var fecha = format_d_m_a(data.fecha_inicio);  var fecha_f = ""; var fecha_i = ""; //data.fecha_inicio

          var cal_mes  = false; var i=0;  var cont=0;

          while (cal_mes == false) {

            cont = cont+1;

            fecha_i = fecha;

            fecha_f = sumaFecha(29, fecha_i);

            let val_fecha_f = new Date( format_a_m_d(fecha_f) ); let val_fecha_proyecto = new Date(data.fecha_fin);
            
            // console.log(fecha_f + ' - '+data.fecha_fin);

            $('#lista_quincenas').append(` <button id="boton-${i}" type="button" class="btn bg-gradient-info text-center" onclick="fecha_quincena('${format_a_m_d(fecha_i)}', '${format_a_m_d(fecha_f)}', '${i}');"><i class="far fa-calendar-alt"></i> Valorización ${cont}<br>${fecha_i} // ${fecha_f}</button>`)
            
            if (val_fecha_f.getTime() >= val_fecha_proyecto.getTime()) { cal_mes = true; }else{ cal_mes = false;}

            fecha = sumaFecha(1,fecha_f);

            i++;
          }          

        } else {

          if (data.fecha_valorizacion == "al finalizar") {

            $(".h1-titulo").html("Valorización - Al finalizar");

            $('#lista_quincenas').append(` <button id="boton-0" type="button" class="btn bg-gradient-info text-center" onclick="fecha_quincena('${data.fecha_inicio}', '${data.fecha_fin}', '0');"><i class="far fa-calendar-alt"></i> Valorización 1<br>${format_d_m_a(data.fecha_inicio)} // ${format_d_m_a(data.fecha_fin)}</button>`)

          } else {
            $('#lista_quincenas').html(`<div class="info-box shadow-lg w-px-600"> 
              <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span> 
              <div class="info-box-content"> 
                <span class="info-box-text">Alerta</span> 
                <span class="info-box-number">No has definido los bloques de fechas del proyecto. <br>Ingresa al ESCRITORIO y EDITA tu proyecto selecionado.</span> 
              </div> 
            </div>`);
          }
        }
      }
      

    } else {
      $('#lista_quincenas').html('<div class="info-box shadow-lg w-px-300">'+
        '<span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>'+
        '<div class="info-box-content">'+
          '<span class="info-box-text">Alerta</span>'+
          '<span class="info-box-number">Las fechas del proyecto <br> es menor de 1 día.</span>'+
        '</div>'+
      '</div>');
    }
    
    //console.log(fecha);
  });
}

// funcinoa para sumar dias
sumaFecha = function(d, fecha)
{
  var Fecha = new Date();
  var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
  // console.log(sFecha);
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

/* PREVISUALIZAR LAS IMAGENES */
function addDocs2(e,id) {

  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

	var file = e.target.files[0], imageType = /application.*/;
	
	if (e.target.files[0]) {
    // console.log(extrae_extencion(file.name));
		var sizeByte = file.size;

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 10000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(imageType)){
			// return;
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Este tipo de ARCHIVO no esta permitido elija formato: mi-documento.xlsx',
        showConfirmButton: false,
        timer: 1500
      });

      $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

			$("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");

		}else{

			if (sizekiloBytes <= 40960) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;

          // cargamos la imagen adecuada par el archivo
				  if ( extrae_extencion(file.name) == "xls") {
            $("#"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
          } else {
            if ( extrae_extencion(file.name) == "xlsx" ) {
              $("#"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
            }else{
              if ( extrae_extencion(file.name) == "csv" ) {
                $("#"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
              }else{
                if ( extrae_extencion(file.name) == "xlsm" ) {
                  $("#"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');  
                }else{  
                  if ( extrae_extencion(file.name) == "pdf" ) {
                    $("#"+id+"_ver").html('<iframe src="'+result+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');    
                  }else{    
                    if ( extrae_extencion(file.name) == "doc" ) {
                      $("#"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
                    } else {
                      if ( extrae_extencion(file.name) == "docx" ) {
                        $("#"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
                      } else {
                        $("#"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                      }
                    }
                  }
                }
              }
            }
          }          

					$("#"+id+"_nombre").html(''+
						'<div class="row">'+
              '<div class="col-md-12">'+
              '<i>' + file.name + '</i>'+
              '</div>'+
              '<div class="col-md-12">'+
                '<button  class="btn btn-danger  btn-block" onclick="'+id+'_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>'+
              '</div>'+
            '</div>'+
					'');

          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'El documento: '+file.name.toUpperCase()+' es aceptado.',
            showConfirmButton: false,
            timer: 1500
          });
				}

				reader.readAsDataURL(file);

			} else {
        Swal.fire({
          position: 'top-end',
          icon: 'warning',
          title: 'El documento: '+file.name.toUpperCase()+' es muy pesado. Tamaño máximo 40mb',
          showConfirmButton: false,
          timer: 1500
        })

        $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

				$("#"+id+"_i").attr("src", "../dist/img/default/img_error.png");

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
    })
		 
    $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

		$("#"+id+"_nombre").html("");
	}	
}

// Eliminamos el doc 6
function doc7_eliminar() {

	$("#doc7").val("");

	$("#doc7_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

	$("#doc7_nombre").html("");
}

//Función limpiar
function limpiar() {
  $("#doc7_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc7_nombre").html('');
  $("#doc_old_7").val(""); 
  $("#doc7").val(""); 
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-valorizacion")[0]);

  $.ajax({
    url: "../ajax/valorizacion.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {	

        Swal.fire("Correcto!", "Documento guardado correctamente", "success");			 
         
				limpiar();

        $("#modal-agregar-valorizacion").modal("hide");

        fecha_quincena(localStorage.getItem('fecha_i'), localStorage.getItem('fecha_f'), localStorage.getItem('i'))

			}else{

        Swal.fire("Error!", datos, "error");

			}
    },

    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {

          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});

          $("#barra_progress").text(percentComplete.toFixed(2)+" %");

          if (percentComplete === 100) {

            l_m();
          }
        }
      }, false);
      return xhr;
    }
  });
}

function l_m(){  

  $("#barra_progress").css({"width":'0%'});
  
  $("#barra_progress").text("0%");  
}

// mostramos los datos para editar
function mostrar(idtrabajador) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-trabajador").modal("show")

  $.post("../ajax/all_trabajador.php?op=mostrar", { idtrabajador: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();


    $("#tipo_documento option[value='"+data.tipo_documento+"']").attr("selected", true);
    $("#nombre").val(data.nombres);
    $("#num_documento").val(data.numero_documento);
    $("#direccion").val(data.direccion);
    $("#telefono").val(data.telefono);
    $("#email").val(data.email);
    $("#nacimiento").val(data.fecha_nacimiento);
    $("#c_bancaria").val(data.cuenta_bancaria);
    $("#banco").val(data.idbancos).trigger("change");
    $("#titular_cuenta").val(data.titular_cuenta);
    $("#idtrabajador").val(data.idtrabajador);

    if (data.imagen_perfil != "") {

			$("#foto1_i").attr("src", "../dist/img/usuarios/" + data.imagen_perfil);

			$("#foto1_actual").val(data.imagen_perfil);
		}

    if (data.imagen_dni_anverso != "") {

			$("#foto2_i").attr("src", "../dist/img/usuarios/" + data.imagen_dni_anverso);

			$("#foto2_actual").val(data.imagen_dni_anverso);
		}

    if (data.imagen_dni_reverso != "") {

			$("#foto3_i").attr("src", "../dist/img/usuarios/" + data.imagen_dni_reverso);

			$("#foto3_actual").val(data.imagen_dni_reverso);
		}

    edades();
  });
}

init();

$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {

      guardaryeditar(e);

    },
  });

  $("#form-valorizacion").validate({

    rules: {
      nombre: { required: true },
    },

    messages: {
      nombre: {
        required: "Por favor selecione un tipo de documento", 
      },       
    },
        
    errorElement: "span",

    errorPlacement: function (error, element) {

      error.addClass("invalid-feedback");

      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {

      $(element).addClass("is-invalid");
    },

   unhighlight: function (element, errorClass, validClass) {

      $(element).removeClass("is-invalid").addClass("is-valid");

    },



  });
});

function extrae_extencion(filename) {
  return filename.split('.').pop();
}

// recargar un doc para ver
function re_visualizacion() {

  $("#doc7_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

  pdffile=document.getElementById("doc7").files[0];

  antiguopdf=$("#doc_old_7").val();

  if(pdffile === undefined){

    var dr = antiguopdf;

    if (dr == "") {

      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Seleccione un documento',
        showConfirmButton: false,
        timer: 1500
      })

      $("#doc7_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

		  $("#doc7_nombre").html("");

    } else {

      $("#doc7_ver").html('<iframe src="'+dr+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
    }

    // console.log('hola'+dr);
  }else{

    pdffile_url=URL.createObjectURL(pdffile);

    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(pdffile.name) == "xls") {

      $("#doc7_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

      toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')

    } else {

      if ( extrae_extencion(pdffile.name) == "xlsx" ) {

        $("#doc7_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

        toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')

      }else{

        if ( extrae_extencion(pdffile.name) == "csv" ) {

          $("#doc7_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

          toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')

        }else{

          if ( extrae_extencion(pdffile.name) == "xlsm" ) {

            $("#doc7_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

            toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')

          }else{

            if ( extrae_extencion(pdffile.name) == "pdf" ) {

              $("#doc7_ver").html('<iframe src="'+pdffile_url+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

              toastr.success('Documento vizualizado correctamente!!!')

            }else{

              $("#doc7_ver").html('<img src="../dist/svg/logo-excel.svg" alt="" width="50%" >');

              toastr.success('Documento vizualizado correctamente!!!')
            }
          }
        }
      }
    }   
    	
    console.log(pdffile);

  }
}

// captura las fechas de quincenas y trae los datos
function fecha_quincena(fecha_i, fecha_f, i) {
  var cont_valor = parseInt(i) + 1;
  //console.log(cont_valor);
  $("#nombre_titulo").html("Valorización " + cont_valor);

  localStorage.setItem('fecha_i', fecha_i);  localStorage.setItem('fecha_f', fecha_f); localStorage.setItem('i', i);

  let nube_idproyecto = localStorage.getItem('nube_idproyecto');

  var respuestadoc5_2 = false;

  $('#tab-seleccione').show(); $('#tab-contenido').show(); $('#tab-info').hide();

  $("#fecha_inicio").val(fecha_i);
  $("#fecha_fin").val(fecha_f);  //console.log(fecha_i, fecha_f, i);
  $("#numero_q_s").val(cont_valor);

  // validamos el id para pintar el boton
  if (localStorage.getItem('boton_id')) {

    let id = localStorage.getItem('boton_id'); //console.log('id-nube-boton'+id); 
    
    $("#boton-" + id).removeClass('click-boton');

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  } else {

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  }

  // traemos loa documentos por fechas de la quincena
  $.post("../ajax/valorizacion.php?op=mostrar-docs-quincena", { nube_idproyecto: nube_idproyecto, fecha_i: fecha_i, fecha_f: fecha_f }, function (data, status) {

    data =JSON.parse(data); console.log(data);  
    
    var vacio = "''";   var count_data2 = 0;

    // validamos la data total
    if (data) {
      if (data.data2.doc1 == "") { count_data2  = count_data2 + 0  } else { count_data2  = count_data2 + 1 }
      if (data.data2.doc4 == "") { count_data2  = count_data2 + 0  } else { count_data2  = count_data2 + 1 }
      if (data.data2.doc81 == "") { count_data2  = count_data2 + 0  } else { count_data2  = count_data2 + 1 }
      if (data.data2.doc82 == "") { count_data2  = count_data2 + 0  } else { count_data2  = count_data2 + 1 }
      if (data.data2.doc83 == "") { count_data2  = count_data2 + 0  } else { count_data2  = count_data2 + 1 }
      
      var docs_total = count_data2 + parseInt(data.count_data1);
      var porcent = (docs_total * 100 )/18;
      // mostramos el resumen
      $("#tabs-resumen").html(
        '<div class="info-box bg-warning">'+
          '<span class="info-box-icon"><i class="far fa-bookmark"></i></span>'+
          '<div class="info-box-content">'+
            '<span class="info-box-text">Documentos Subidos</span>'+
            '<span class="info-box-number">Total ' + docs_total + '/18</span>'+
            '<div class="progress" style="height: 10px !important;"> '+
              '<div class="progress-bar" style="width: '+porcent.toFixed(1)+'%"></div>'+
            '</div>'+
            '<span class="progress-description">'+
              'Tienes un <b> '+porcent.toFixed(1)+'%</b> de documentos subidos!!!'+
            '</span>'+
          '</div>'+
        '</div>'
      );

      // exraemos la fecha de HOY
      var tiempoTranscurrido = Date.now();
      var hoy = new Date(tiempoTranscurrido);
      var format = hoy.toLocaleDateString().split("/"); //console.log(format);
      
      // validamos la data1
      if (data.data1.length === 0) {
        //console.log('data 1 no existe');
        // pintamos rojos los que no tienen docs
        if ($("#tabs-2-tab").hasClass("si-doc") == false || $("#tabs-2-tab").hasClass("si-doc") == true) { $("#tabs-2-tab").addClass('no-doc').removeClass('si-doc'); }   
        if ($("#tabs-3-1-tab").hasClass("si-doc") == false || $("#tabs-3-1-tab").hasClass("si-doc") == true ) { $("#tabs-3-1-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-2-tab").hasClass("si-doc") == false || $("#tabs-3-2-tab").hasClass("si-doc") == true ) { $("#tabs-3-2-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-3-tab").hasClass("si-doc") == false || $("#tabs-3-3-tab").hasClass("si-doc") == true ) { $("#tabs-3-3-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-4-tab").hasClass("si-doc") == false || $("#tabs-3-4-tab").hasClass("si-doc") == true ) { $("#tabs-3-4-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-5-1-tab").hasClass("si-doc") == false || $("#tabs-5-1-tab").hasClass("si-doc") == true ) { $("#tabs-5-1-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-5-2-tab").hasClass("si-doc") == false || $("#tabs-5-2-tab").hasClass("si-doc") == true ) { $("#tabs-5-2-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-6-tab").hasClass("si-doc") == false || $("#tabs-6-tab").hasClass("si-doc") == true ) { $("#tabs-6-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-7-tab").hasClass("si-doc") == false || $("#tabs-7-tab").hasClass("si-doc") == true ) { $("#tabs-7-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-4-tab").hasClass("si-doc") == false || $("#tabs-8-4-tab").hasClass("si-doc") == true ) { $("#tabs-8-4-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-5-tab").hasClass("si-doc") == false || $("#tabs-8-5-tab").hasClass("si-doc") == true ) { $("#tabs-8-5-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-6-tab").hasClass("si-doc") == false || $("#tabs-8-6-tab").hasClass("si-doc") == true ) { $("#tabs-8-6-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-7-tab").hasClass("si-doc") == false || $("#tabs-8-7-tab").hasClass("si-doc") == true ) { $("#tabs-8-7-tab").addClass('no-doc').removeClass('si-doc'); }
        
        $('#documento2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento3-1').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento3-2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento3-3').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento3-4').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento5-1').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento5-2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+vacio + ','+ "'" + 'doc5.2' + "'" + ');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:30%" > No hay documento para mostrar </div> </div>' );
        $('#documento5-2-1').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+vacio + ','+ "'" + 'doc5.2.1' + "'" + ');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:30%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento6').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento7').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento8-4').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento8-5').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento8-6').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento8-7').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 

      } else {
        // Borradmos las clases
        if ($("#tabs-2-tab").hasClass("si-doc") == false || $("#tabs-2-tab").hasClass("si-doc") == true) { $("#tabs-2-tab").addClass('no-doc').removeClass('si-doc'); }   
        if ($("#tabs-3-1-tab").hasClass("si-doc") == false || $("#tabs-3-1-tab").hasClass("si-doc") == true ) { $("#tabs-3-1-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-2-tab").hasClass("si-doc") == false || $("#tabs-3-2-tab").hasClass("si-doc") == true ) { $("#tabs-3-2-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-3-tab").hasClass("si-doc") == false || $("#tabs-3-3-tab").hasClass("si-doc") == true ) { $("#tabs-3-3-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-4-tab").hasClass("si-doc") == false || $("#tabs-3-4-tab").hasClass("si-doc") == true ) { $("#tabs-3-4-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-5-1-tab").hasClass("si-doc") == false || $("#tabs-5-1-tab").hasClass("si-doc") == true ) { $("#tabs-5-1-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-5-2-tab").hasClass("si-doc") == false || $("#tabs-5-2-tab").hasClass("si-doc") == true ) { $("#tabs-5-2-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-6-tab").hasClass("si-doc") == false || $("#tabs-6-tab").hasClass("si-doc") == true ) { $("#tabs-6-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-7-tab").hasClass("si-doc") == false || $("#tabs-7-tab").hasClass("si-doc") == true ) { $("#tabs-7-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-4-tab").hasClass("si-doc") == false || $("#tabs-8-4-tab").hasClass("si-doc") == true ) { $("#tabs-8-4-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-5-tab").hasClass("si-doc") == false || $("#tabs-8-5-tab").hasClass("si-doc") == true ) { $("#tabs-8-5-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-6-tab").hasClass("si-doc") == false || $("#tabs-8-6-tab").hasClass("si-doc") == true ) { $("#tabs-8-6-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-7-tab").hasClass("si-doc") == false || $("#tabs-8-7-tab").hasClass("si-doc") == true ) { $("#tabs-8-7-tab").addClass('no-doc').removeClass('si-doc'); }
        
        $.each(data.data1, function (index, value) {

          if (value.nombre == "doc2") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-2-tab").hasClass("no-doc") == false || $("#tabs-2-tab").hasClass("no-doc") == true) { $("#tabs-2-tab").removeClass('no-doc').addClass("si-doc"); }          
            
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento2').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                 
                $('#documento2').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                   
                  $('#documento2').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                    $('#documento2').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento2').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento2').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                          $('#documento2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

          if (value.nombre == "doc3.1") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-3-1-tab").hasClass("no-doc") == false || $("#tabs-3-1-tab").hasClass("no-doc") == true) { $("#tabs-3-1-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento3-1').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento3-1').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento3-1').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                    $('#documento3-1').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento3-1').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento3-1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento3-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                          $('#documento3-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

          if (value.nombre == "doc3.2") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-3-2-tab").hasClass("no-doc") == false || $("#tabs-3-2-tab").hasClass("no-doc") == true) { $("#tabs-3-2-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento3-2').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento3-2').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento3-2').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                    $('#documento3-2').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento3-2').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento3-2').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento3-2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                          $('#documento3-2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

          if (value.nombre == "doc3.3") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-3-3-tab").hasClass("no-doc") == false || $("#tabs-3-3-tab").hasClass("no-doc") == true) { $("#tabs-3-3-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento3-3').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento3-3').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento3-3').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                    $('#documento3-3').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento3-3').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento3-3').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento3-3').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                          $('#documento3-3').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

          if (value.nombre == "doc3.4") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-3-4-tab").hasClass("no-doc") == false || $("#tabs-3-4-tab").hasClass("no-doc") == true) { $("#tabs-3-4-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento3-4').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento3-4').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento3-4').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                    $('#documento3-4').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento3-4').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento3-4').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento3-4').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                          $('#documento3-4').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

          if (value.nombre == "doc5.1") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-5-1-tab").hasClass("no-doc") == false || $("#tabs-5-1-tab").hasClass("no-doc") == true) { $("#tabs-5-1-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento5-1').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento5-1').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento5-1').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

                    $('#documento5-1').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento5-1').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento5-1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento5-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                          $('#documento5-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

          if (value.nombre == "doc5.2") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-5-2-tab").hasClass("no-doc") == false || $("#tabs-5-2-tab").hasClass("no-doc") == true) { $("#tabs-5-2-tab").removeClass('no-doc').addClass("si-doc"); }          
             
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {
              
              $('#documento5-2').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + 'doc5.2' + "'" + ');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
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

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {                
                
                $('#documento5-2').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + 'doc5.2' + "'" + ');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
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

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {                  
                  
                  $('#documento5-2').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + 'doc5.2' + "'" + ');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
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
                    
                    $('#documento5-2').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + 'doc5.2' + "'" + ');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
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
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento5-2').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + 'doc5.2' + "'" + ');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:30%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento5-2').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + 'doc5.2' + "'" + ');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento5-2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + 'doc5.2' + "'" + ');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                          $('#documento5-2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + 'doc5.2' + "'" + ');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

          if (value.nombre == "doc5.2.1") {  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {
              
              $('#documento5-2-1').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + 'doc5.2.1' + "'" + ');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
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

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {                
                
                $('#documento5-2-1').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + 'doc5.2.1' + "'" + ');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
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

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {                  
                  
                  $('#documento5-2-1').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + 'doc5.2.1' + "'" + ');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
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
                    
                    $('#documento5-2-1').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' +  "'" + 'doc5.2.1' + "'" + ');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
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
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento5-2-1').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' +  "'" + 'doc5.2.1' + "'" + ');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:30%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento5-2-1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' +  "'" + 'doc5.2.1' + "'" + ');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento5-2-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' +  "'" + 'doc5.2.1' + "'" + ');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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
                          $('#documento5-2-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' +  "'" + 'doc5.2.1' + "'" + ');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
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

            respuestadoc5_2 = true;
          }

          if (value.nombre == "doc6") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-6-tab").hasClass("no-doc") == false || $("#tabs-6-tab").hasClass("no-doc") == true) { $("#tabs-6-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento6').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento6').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento6').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento6').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento6').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento6').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento6').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento6').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
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

          if (value.nombre == "doc7") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-7-tab").hasClass("no-doc") == false || $("#tabs-7-tab").hasClass("no-doc") == true) { $("#tabs-7-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento7').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento7').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento7').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento7').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento7').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento7').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento7').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento7').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
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

          if (value.nombre == "doc8.4") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-8-4-tab").hasClass("no-doc") == false || $("#tabs-8-4-tab").hasClass("no-doc") == true) { $("#tabs-8-4-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento8-4').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento8-4').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento8-4').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento8-4').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento8-4').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento8-4').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento8-4').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento8-4').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
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

          if (value.nombre == "doc8.5") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-8-5-tab").hasClass("no-doc") == false || $("#tabs-8-5-tab").hasClass("no-doc") == true) { $("#tabs-8-5-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento8-5').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento8-5').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento8-5').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento8-5').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento8-5').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento8-5').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento8-5').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento8-5').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
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

          if (value.nombre == "doc8.6") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-8-6-tab").hasClass("no-doc") == false || $("#tabs-8-6-tab").hasClass("no-doc") == true) { $("#tabs-8-6-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento8-6').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento8-6').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento8-6').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento8-6').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento8-6').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento8-6').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico  - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento8-6').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico  - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento8-6').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico  - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
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

          if (value.nombre == "doc8.7") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-8-7-tab").hasClass("no-doc") == false || $("#tabs-8-7-tab").hasClass("no-doc") == true) { $("#tabs-8-7-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento8-7').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento8-7').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento8-7').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento8-7').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento8-7').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento8-7').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento8-7').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento8-7').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
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
          
        });

        // pintamos rojos los que no tienen docs
        if ($("#tabs-2-tab").hasClass("si-doc") == false) { $("#tabs-2-tab").addClass('no-doc').removeClass('si-doc'); $('#documento2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); } 
        if ($("#tabs-3-1-tab").hasClass("si-doc") == false ) { $("#tabs-3-1-tab").addClass('no-doc').removeClass('si-doc'); $('#documento3-1').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-3-2-tab").hasClass("si-doc") == false ) { $("#tabs-3-2-tab").addClass('no-doc').removeClass('si-doc'); $('#documento3-2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-3-3-tab").hasClass("si-doc") == false ) { $("#tabs-3-3-tab").addClass('no-doc').removeClass('si-doc'); $('#documento3-3').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-3-4-tab").hasClass("si-doc") == false ) { $("#tabs-3-4-tab").addClass('no-doc').removeClass('si-doc'); $('#documento3-4').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-5-1-tab").hasClass("si-doc") == false ) { $("#tabs-5-1-tab").addClass('no-doc').removeClass('si-doc'); $('#documento5-1').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-5-2-tab").hasClass("si-doc") == false ) { $("#tabs-5-2-tab").addClass('no-doc').removeClass('si-doc'); $('#documento5-2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+vacio + ','+ "'" + 'doc5.2' + "'" + ');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:30%" > No hay documento para mostrar </div> </div>' ); }
        if (respuestadoc5_2 == false) { $('#documento5-2-1').html('<div class="col-lg-4 "> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+vacio + ','+ "'" + 'doc5.2.1' + "'" + ');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:30%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-6-tab").hasClass("si-doc") == false ) { $("#tabs-6-tab").addClass('no-doc').removeClass('si-doc'); $('#documento6').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-7-tab").hasClass("si-doc") == false ) { $("#tabs-7-tab").addClass('no-doc').removeClass('si-doc'); $('#documento7').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-8-4-tab").hasClass("si-doc") == false ) { $("#tabs-8-4-tab").addClass('no-doc').removeClass('si-doc'); $('#documento8-4').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-8-5-tab").hasClass("si-doc") == false ) { $("#tabs-8-4-tab").addClass('no-doc').removeClass('si-doc'); $('#documento8-5').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-8-6-tab").hasClass("si-doc") == false ) { $("#tabs-8-6-tab").addClass('no-doc').removeClass('si-doc'); $('#documento8-6').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-8-7-tab").hasClass("si-doc") == false ) { $("#tabs-8-7-tab").addClass('no-doc').removeClass('si-doc'); $('#documento8-7').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
      }

      // validamos la data2
      if (data.data2.length === 0) {
        console.log('data 2 no existe');
      } else {

        if (data.data2.doc1 != "") {

          if ($("#tabs-1-tab").hasClass("no-doc")) { $("#tabs-1-tab").removeClass('no-doc'); }          

          // cargamos la imagen adecuada par el archivo
          if ( extrae_extencion(data.data2.doc1) == "xls") {

            $('#documento1').html(
              '<div class="col-lg-4">'+
                '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                  '<i class="fas fa-file-upload"></i> Subir'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4">'+
                '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                  '<i class="fas fa-download"></i> Descargar'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4 mb-4">'+
                '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                  '<i class="fas fa-expand"></i> Ver completo'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-12 ">'+
                '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                  '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                '</div>'+
              '</div>'
            );

          } else {

            if ( extrae_extencion(data.data2.doc1) == "xlsx" ) {
              
              $('#documento1').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            }else{

              if ( extrae_extencion(data.data2.doc1) == "csv" ) {
                
                $('#documento1').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(data.data2.doc1) == "xlsm" ) {

                  $('#documento1').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                      '</div>'+
                    '</div>'
                  );
  
                }else{
  
                  if ( extrae_extencion(data.data2.doc1) == "pdf" ) {

                    $('#documento1').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+data.data2.doc1+'"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                          '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+data.data2.doc1+'" type="application/pdf" width="100%" height="100%" />'+
                        '</div>'+
                      '</div>'
                    );      
                  }else{
                    if ( extrae_extencion(data.data2.doc1) == "doc" ) {

                      $('#documento1').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                            '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(data.data2.doc1) == "docx" ) {

                        $('#documento1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );      
                      }else{
                        $('#documento1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
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
        
        } else {

          if ($("#tabs-1-tab").hasClass("no-doc") == false) { $("#tabs-1-tab").addClass('no-doc'); }

          $('#documento1').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (data.data2.doc4 != "") {

          if ($("#tabs-4-tab").hasClass("no-doc")) { $("#tabs-4-tab").removeClass('no-doc'); }           

          // cargamos la imagen adecuada par el archivo
          if ( extrae_extencion(data.data2.doc4) == "xls") {

            $('#documento4').html(
              '<div class="col-lg-4">'+
                '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                  '<i class="fas fa-file-upload"></i> Subir'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4">'+
                '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                  '<i class="fas fa-download"></i> Descargar'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4 mb-4">'+
                '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                  '<i class="fas fa-expand"></i> Ver completo'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-12 ">'+
                '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                  '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                '</div>'+
              '</div>'
            );

          } else {

            if ( extrae_extencion(data.data2.doc4) == "xlsx" ) {
              
              $('#documento4').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            }else{

              if ( extrae_extencion(data.data2.doc4) == "csv" ) {
                
                $('#documento4').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(data.data2.doc4) == "xlsm" ) {

                  $('#documento4').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                      '</div>'+
                    '</div>'
                  );
  
                }else{
  
                  if ( extrae_extencion(data.data2.doc4) == "pdf" ) {

                    $('#documento4').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+data.data2.doc4+'"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                          '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+data.data2.doc4+'" type="application/pdf" width="100%" height="100%" />'+
                        '</div>'+
                      '</div>'
                    );      
                  }else{
                    if ( extrae_extencion(data.data2.doc4) == "doc" ) {

                      $('#documento4').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                            '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(data.data2.doc4) == "docx" ) {

                        $('#documento4').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );      
                      }else{
                        $('#documento4').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
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
        } else {

          if ($("#tabs-4-tab").hasClass("no-doc") == false) { $("#tabs-4-tab").addClass('no-doc'); }

          $('#documento4').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (data.data2.doc81 != "") {

          if ($("#tabs-8-1-tab").hasClass("no-doc")) { $("#tabs-8-1-tab").removeClass('no-doc'); }

          // cargamos la imagen adecuada par el archivo
          if ( extrae_extencion(data.data2.doc81) == "xls") {

            $('#documento8-1').html(
              '<div class="col-lg-4">'+
                '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                  '<i class="fas fa-file-upload"></i> Subir'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4">'+
                '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                  '<i class="fas fa-download"></i> Descargar'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4 mb-4">'+
                '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                  '<i class="fas fa-expand"></i> Ver completo'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-12 ">'+
                '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                  '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                '</div>'+
              '</div>'
            );

          } else {

            if ( extrae_extencion(data.data2.doc81) == "xlsx" ) {
              
              $('#documento8-1').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            }else{

              if ( extrae_extencion(data.data2.doc81) == "csv" ) {
                
                $('#documento8-1').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(data.data2.doc81) == "xlsm" ) {

                  $('#documento8-1').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                      '</div>'+
                    '</div>'
                  );
  
                }else{
  
                  if ( extrae_extencion(data.data2.doc81) == "pdf" ) {

                    $('#documento8-1').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+data.data2.doc81+'"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                          '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+data.data2.doc81+'" type="application/pdf" width="100%" height="100%" />'+
                        '</div>'+
                      '</div>'
                    );      
                  }else{
                    if ( extrae_extencion(data.data2.doc81) == "doc" ) {

                      $('#documento8-1').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                            '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(data.data2.doc81) == "docx" ) {

                        $('#documento8-1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );      
                      }else{
                        $('#documento8-1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
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
        } else {

          if ($("#tabs-8-1-tab").hasClass("no-doc") == false) { $("#tabs-8-1-tab").addClass('no-doc'); }

          $('#documento8-1').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (data.data2.doc82 != "") {

          if ($("#tabs-8-2-tab").hasClass("no-doc")) { $("#tabs-8-2-tab").removeClass('no-doc'); }

          // cargamos la imagen adecuada par el archivo
          if ( extrae_extencion(data.data2.doc82) == "xls") {

            $('#documento8-2').html(
              '<div class="col-lg-4">'+
                '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                  '<i class="fas fa-file-upload"></i> Subir'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4">'+
                '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                  '<i class="fas fa-download"></i> Descargar'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4 mb-4">'+
                '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                  '<i class="fas fa-expand"></i> Ver completo'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-12 ">'+
                '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                  '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                '</div>'+
              '</div>'
            );

          } else {

            if ( extrae_extencion(data.data2.doc82) == "xlsx" ) {
              
              $('#documento8-2').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            }else{

              if ( extrae_extencion(data.data2.doc82) == "csv" ) {
                
                $('#documento8-2').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(data.data2.doc82) == "xlsm" ) {

                  $('#documento8-2').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                      '</div>'+
                    '</div>'
                  );
  
                }else{
  
                  if ( extrae_extencion(data.data2.doc82) == "pdf" ) {

                    $('#documento8-2').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+data.data2.doc82+'"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                          '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+data.data2.doc82+'" type="application/pdf" width="100%" height="100%" />'+
                        '</div>'+
                      '</div>'
                    );      
                  }else{
                    if ( extrae_extencion(data.data2.doc82) == "doc" ) {

                      $('#documento8-2').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                            '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(data.data2.doc82) == "docx" ) {

                        $('#documento8-2').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );      
                      }else{
                        $('#documento8-2').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
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
        } else {

          if ($("#tabs-8-2-tab").hasClass("no-doc") == false) { $("#tabs-8-2-tab").addClass('no-doc'); }

          $('#documento8-2').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (data.data2.doc83 != "") {

          if ($("#tabs-8-3-tab").hasClass("no-doc")) { $("#tabs-8-3-tab").removeClass('no-doc'); }

          // cargamos la imagen adecuada par el archivo
          if ( extrae_extencion(data.data2.doc83) == "xls") {

            $('#documento8-3').html(
              '<div class="col-lg-4">'+
                '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                  '<i class="fas fa-file-upload"></i> Subir'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4">'+
                '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                  '<i class="fas fa-download"></i> Descargar'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4 mb-4">'+
                '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                  '<i class="fas fa-expand"></i> Ver completo'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-12 ">'+
                '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                  '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                '</div>'+
              '</div>'
            );

          } else {

            if ( extrae_extencion(data.data2.doc83) == "xlsx" ) {
              
              $('#documento8-3').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            }else{

              if ( extrae_extencion(data.data2.doc83) == "csv" ) {
                
                $('#documento8-3').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(data.data2.doc83) == "xlsm" ) {

                  $('#documento8-3').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                      '</div>'+
                    '</div>'
                  );
  
                }else{
  
                  if ( extrae_extencion(data.data2.doc83) == "pdf" ) {

                    $('#documento8-3').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+data.data2.doc83+'"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                          '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+data.data2.doc83+'" type="application/pdf" width="100%" height="100%" />'+
                        '</div>'+
                      '</div>'
                    );      
                  }else{
                    if ( extrae_extencion(data.data2.doc83) == "doc" ) {

                      $('#documento8-3').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                            '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(data.data2.doc83) == "docx" ) {

                        $('#documento8-3').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );      
                      }else{
                        $('#documento8-3').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
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
        } else {

          if ($("#tabs-8-3-tab").hasClass("no-doc") == false) { $("#tabs-8-3-tab").addClass('no-doc'); }

          $('#documento8-3').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }
      }

    } else {
      
    }
    // $('#lista_quincenas').html('');

  });
}

function add_data_form(nombredoc,nom_title) {

  $("#nombre").val(nombredoc); $('#title-modal-1').html(nom_title);

  //console.log(nombredoc);  
}

//Función para desactivar registros
function subir_doc(idvalorizacion) {

  //console.log('idvalorizacion: ' + idvalorizacion);

  $("#idvalorizacion").val(idvalorizacion);

  $("#modal-agregar-valorizacion").modal('show'); 
}

function subir_doc_respuesta(idvalorizacion, nombre) {

  $("#idvalorizacion").val(idvalorizacion);

  $("#nombre").val(nombre);
  
  $("#modal-agregar-valorizacion").modal('show'); 
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

function sumar_mes(fecha) {

  var split_fecha =  fecha.split("-");

  // var format_fecha = format_d_m_a(fecha);

  var dias_total_mes = cantDiasEnUnMes( parseInt(split_fecha[1]), parseInt(split_fecha[0]) );  

  var mes_next =  sumaFecha(dias_total_mes-1, fecha); 

  // console.log(`🚀 ${fecha} + ${dias_total_mes-1} =  fecha_f:${mes_next}`);  

  return mes_next;
}

function cantDiasEnUnMes(mes, año) {
   
  var diasMes = new Date(año, mes, 0).getDate(); // console.log('mes:' + mes+ ' cant:' + diasMes);

  return diasMes; 
}