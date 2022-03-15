var tabla; var tabla2;

//Función que se ejecuta al inicio
function init() {

  listar( );

  //Mostramos los BANCOS
  $.post("../ajax/all_trabajador.php?op=select2Banco", function (r) { $("#banco").html(r); });

  //Mostramos los tipo
  $.post("../ajax/tipo.php?op=selecttipo_tipo", function (r) { $("#tipo").html(r); });

  //Mostramos los ocupación
  $.post("../ajax/ocupacion.php?op=selectocupacion", function (r) { $("#ocupacion").html(r); });

  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  $("#lAllTrabajador").addClass("active");

  $("#guardar_registro").on("click", function (e) {  $("#submit-form-trabajador").submit(); });

  // Formato para telefono
  $("[data-mask]").inputmask();

  // abrimos el navegador de archivos
  $("#foto1_i").click(function() { $('#foto1').trigger('click'); });
  $("#foto1").change(function(e) { addImage(e,$("#foto1").attr("id")) });

  $("#foto2_i").click(function() { $('#foto2').trigger('click'); });
  $("#foto2").change(function(e) { addImage(e,$("#foto2").attr("id")) });

  $("#foto3_i").click(function() { $('#foto3').trigger('click'); });
  $("#foto3").change(function(e) { addImage(e,$("#foto3").attr("id")) });

  $("#doc4_i").click(function() {  $('#doc4').trigger('click'); });
  $("#doc4").change(function(e) {  addDocs(e,$("#doc4").attr("id")) });

  $("#doc5_i").click(function() {  $('#doc5').trigger('click'); });
  $("#doc5").change(function(e) {  addDocs(e,$("#doc5").attr("id")) });

  //Initialize Select2 Elements
  $("#banco").select2({
    theme: "bootstrap4",
    placeholder: "Selecione banco",
    allowClear: true,
  });
  //Initialize Select2 Elements
  $("#tipo").select2({
    theme: "bootstrap4",
    placeholder: "Selecione tipo",
    allowClear: true,
  });
  //Initialize Select2 Elements
  $("#ocupacion").select2({
    theme: "bootstrap4",
    placeholder: "Selecione Ocupación",
    allowClear: true,
  });
}

/* PREVISUALIZAR LAS IMAGENES */
function addImage(e,id) {
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

			if (id == 'foto1' ) {
        $("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");
      } else {
        if (id == 'foto2' ) {
          $("#"+id+"_i").attr("src", "../dist/img/default/dni_anverso.webp");
        } else {
          $("#"+id+"_i").attr("src", "../dist/img/default/dni_reverso.webp");
        } 
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

    if (id == 'foto1' ) {
      $("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");
    } else {
      if (id == 'foto2' ) {
        $("#"+id+"_i").attr("src", "../dist/img/default/dni_anverso.webp");
      } else {
        $("#"+id+"_i").attr("src", "../dist/img/default/dni_reverso.webp");
      } 
    }

		$("#"+id+"_nombre").html("");
	}
}

/* PREVISUALIZAR LOS DOCUMENTOS */
function addDocs(e,id) {

  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');	console.log(id);

	var file = e.target.files[0], imageType = /application.*/;
	
	if (e.target.files[0]) {
    
		var sizeByte = file.size; console.log(file.type);

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(imageType)){
			// return;
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Este tipo de ARCHIVO no esta permitido elija formato: mi-documento.pdf',
        showConfirmButton: false,
        timer: 1500
      });			 
      $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
			$("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");

		}else{

			if (sizekiloBytes <= 40960) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;
				 
          // $("#"+id+"_ver").html('<iframe src="'+result+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');

          // cargamos la imagen adecuada par el archivo
				  if ( extrae_extencion(file.name) == "doc") {
            $("#"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
          } else {
            if ( extrae_extencion(file.name) == "docx" ) {
              $("#"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
            }else{
              if ( extrae_extencion(file.name) == "pdf" ) {
                $("#"+id+"_ver").html('<iframe src="'+result+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
              }else{
                if ( extrae_extencion(file.name) == "csv" ) {
                  $("#"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
                } else {
                  if ( extrae_extencion(file.name) == "xls" ) {
                    $("#"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
                  } else {
                    if ( extrae_extencion(file.name) == "xlsx" ) {
                      $("#"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                    } else {
                      if ( extrae_extencion(file.name) == "xlsm" ) {
                        $("#"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
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
                '<i>' + file.name + '</i>' +
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
          title: 'El documento: '+file.name.toUpperCase()+' es muy pesado.',
          showConfirmButton: false,
          timer: 1500
        })

        $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

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
		 
    $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

		$("#"+id+"_nombre").html("");
	}	
}

// recargar un doc para ver
function re_visualizacion(id,carpeta) {

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

      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

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
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/all_trabajador/${carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="210"></iframe>`);
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
          $("#doc"+id+"_ver").html('<iframe src="'+pdffile_url+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');
          toastr.success('Documento vizualizado correctamente!!!')
        }else{
          if ( extrae_extencion(pdffile.name) == "csv" ) {
            $("#doc"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
            toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
          } else {
            if ( extrae_extencion(pdffile.name) == "xls" ) {
              $("#doc"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
              toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
            } else {
              if ( extrae_extencion(pdffile.name) == "xlsx" ) {
                $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
              } else {
                if ( extrae_extencion(pdffile.name) == "xlsm" ) {
                  $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                  toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
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
    	
    console.log(pdffile);

  }
}

function foto1_eliminar() {

	$("#foto1").val("");

	$("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");

	$("#foto1_nombre").html("");
}

function foto2_eliminar() {

	$("#foto2").val("");

	$("#foto2_i").attr("src", "../dist/img/default/dni_anverso.webp");

	$("#foto2_nombre").html("");
}

function foto3_eliminar() {

	$("#foto3").val("");

	$("#foto3_i").attr("src", "../dist/img/default/dni_reverso.webp");

	$("#foto3_nombre").html("");
}

// Eliminamos el doc 4
function doc4_eliminar() {

	$("#doc4").val("");

	$("#doc4_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc4_nombre").html("");
}

// Eliminamos el doc 5
function doc5_eliminar() {

	$("#doc5").val("");

	$("#doc5_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc5_nombre").html("");
}

function no_pdf() {
  toastr.error("No hay DOC disponible, suba un DOC en el apartado de editar!!")
}

function dowload_pdf() {
  toastr.success("El documento se descargara en breve!!")
}

function extrae_extencion(filename) {
  return filename.split('.').pop();
}

function sueld_mensual(){

  var sueldo_mensual = $('#sueldo_mensual').val()

  var sueldo_diario=(sueldo_mensual/30).toFixed(1);

  var sueldo_horas=(sueldo_diario/8).toFixed(1);

  $("#sueldo_diario").val(sueldo_diario);

  $("#sueldo_hora").val(sueldo_horas);
}

//Función limpiar
function limpiar() {
  $("#idtrabajador").val("");
  $("#tipo_documento option[value='DNI']").attr("selected", true);
  $("#nombre").val(""); 
  $("#num_documento").val(""); 
  $("#direccion").val(""); 
  $("#telefono").val(""); 
  $("#email").val(""); 
  $("#nacimiento").val("");
  $("#edad").val("0");  $("#p_edad").html("0");    
  $("#c_bancaria").val("");  
  $("#cci").val("");  
  $("#banco").val("").trigger("change");
  $("#tipo").val("").trigger("change");
  $("#ocupacion").val("").trigger("change");
  $("#titular_cuenta").val("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1").val("");
	$("#foto1_actual").val("");  
  $("#foto1_nombre").html(""); 

  $("#foto2_i").attr("src", "../dist/img/default/dni_anverso.webp");
	$("#foto2").val("");
	$("#foto2_actual").val("");  
  $("#foto2_nombre").html("");  

  $("#foto3_i").attr("src", "../dist/img/default/dni_reverso.webp");
	$("#foto3").val("");
	$("#foto3_actual").val("");  
  $("#foto3_nombre").html(""); 

  $("#doc4").val("");
  $("#doc_old_4").val("");
  
  $("#doc5").val("");
  $("#doc_old_5").val("");
  
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

//Función Listar
function listar() {

  tabla=$('#tabla-trabajador').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
      url: '../ajax/all_trabajador.php?op=listar',
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {          

      // columna: #
      if (data[0] != '') {
        $("td", row).eq(0).addClass('text-center');         
      } 
      // columna: 1
      if (data[1] != '') {
        $("td", row).eq(1).addClass('text-nowrap');         
      }          
      
    },
    "language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10,//Paginación
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();

  // listamos al trabajadores expulsados
  tabla2=$('#tabla-trabajador-expulsado').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/all_trabajador.php?op=listar_expulsado',
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
        }
      },
      createdRow: function (row, data, ixdex) {          

        // columna: #
        if (data[0] != '') {
          $("td", row).eq(0).addClass('text-center');         
        }         
      },
    "language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10,//Paginación
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-trabajador")[0]);

  $.ajax({
    url: "../ajax/all_trabajador.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {	

        Swal.fire("Correcto!", "Trabajador guardado correctamente", "success");			 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-trabajador").modal("hide");

			}else{

        Swal.fire("Error!", datos, "error");

			}
    },
  });
}

// ver detallles del registro
function verdatos(idtrabajador){

  $('#datostrabajador').html(''+
  '<div class="row" >'+
    '<div class="col-lg-12 text-center">'+
      '<i class="fas fa-spinner fa-pulse fa-6x"></i><br />'+
      '<br />'+
      '<h4>Cargando...</h4>'+
    '</div>'+
  '</div>');

  var verdatos=''; 

  var imagen_perfil =''; btn_imagen_perfil=''; 

  var imagen_dni_anverso =''; var btn_imagen_dni_anverso=''; 
  var imagen_dni_reverso =''; var btn_imagen_dni_reverso=''; 
  
  var cv_documentado=''; var btn_cv_documentado=''; 
  var cv_no_documentado ='';  var btn_cv_no_documentado='';

  $("#modal-ver-trabajador").modal("show")

  $.post("../ajax/all_trabajador.php?op=verdatos", { idtrabajador: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  //console.log(data); 
   
    if (data.imagen_perfil != '') {

      imagen_perfil=`<img src="../dist/docs/all_trabajador/perfil/${data.imagen_perfil}" alt="" class="img-thumbnail">`
      
      btn_imagen_perfil=`
      <div class="row">
        <div class="col-6"">
           <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/perfil/${data.imagen_perfil}"> <i class="fas fa-expand"></i></a>
        </div>
        <div class="col-6"">
           <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/perfil/${data.imagen_perfil}" download="PERFIL ${data.nombres}"> <i class="fas fa-download"></i></a>
        </div>
      </div>`;
    
    } else {

      imagen_perfil='No hay imagen';
      btn_imagen_perfil='';

    }

    if (data.imagen_dni_anverso != '') {

      imagen_dni_anverso=`<img src="../dist/docs/all_trabajador/dni_anverso/${data.imagen_dni_anverso}" alt="" class="img-thumbnail">`
      
      btn_imagen_dni_anverso=`
      <div class="row">
        <div class="col-6"">
           <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/dni_anverso/${data.imagen_dni_anverso}"> <i class="fas fa-expand"></i></a>
        </div>
        <div class="col-6"">
           <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/dni_anverso/${data.imagen_dni_anverso}" download="DNI ${data.nombres}"> <i class="fas fa-download"></i></a>
        </div>
      </div>`;
    
    } else {

      imagen_dni_anverso='No hay imagen';
      btn_imagen_dni_anverso='';

    }

    if (data.imagen_dni_reverso != '') {

      imagen_dni_reverso=`<img src="../dist/docs/all_trabajador/dni_reverso/${data.imagen_dni_reverso}" alt="" class="img-thumbnail">`
      
      btn_imagen_dni_reverso=`
      <div class="row">
        <div class="col-6"">
           <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/dni_reverso/${data.imagen_dni_reverso}"> <i class="fas fa-expand"></i></a>
        </div>
        <div class="col-6"">
           <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/dni_reverso/${data.imagen_dni_reverso}" download="DNI ${data.nombres}"> <i class="fas fa-download"></i></a>
        </div>
      </div>`;
    
    } else {

      imagen_dni_reverso='No hay imagen';
      btn_imagen_dni_reverso='';

    }

    if (data.cv_documentado != '') {

      cv_documentado=`<iframe src="../dist/docs/all_trabajador/cv_documentado/${data.cv_documentado}" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>`
      
      btn_cv_documentado=`
      <div class="row">
        <div class="col-6"">
           <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/cv_documentado/${data.cv_documentado}"> <i class="fas fa-expand"></i></a>
        </div>
        <div class="col-6"">
           <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/cv_documentado/${data.cv_documentado}" download="CV DOCUMENTADO ${data.nombres}"> <i class="fas fa-download"></i></a>
        </div>
      </div>`;
    
    } else {

      cv_documentado='Sin CV documentado';
      btn_cv_documentado='';

    }

    if (data.cv_no_documentado != '') {

      cv_no_documentado=`<iframe src="../dist/docs/all_trabajador/cv_no_documentado/${data.cv_no_documentado}" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>`
      
      btn_cv_no_documentado=`
      <div class="row">
        <div class="col-6"">
           <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/all_trabajador/cv_no_documentado/${data.cv_no_documentado}"> <i class="fas fa-expand"></i> </a>
        </div>
        <div class="col-6"">
           <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/all_trabajador/cv_no_documentado/${data.cv_no_documentado}" download="CV NO DOCUMENTADO ${data.nombres}"> <i class="fas fa-download"></i></a>
        </div>
      </div>`;
    
    } else {

      cv_no_documentado='Sin CV no documentado';
      btn_cv_no_documentado='';

    }

    verdatos=`                                                                            
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-hover table-bordered">        
            <tbody>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th rowspan="2">${imagen_perfil}<br>${btn_imagen_perfil}
                
                </th>
                <td> <b>Nombre: </b> ${data.nombres}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <td> <b>DNI: </b>  ${data.numero_documento}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Dirección</th>
                <td>${data.direccion}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Correo</th>
                <td>${data.email}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Teléfono</th>
                <td>${data.telefono}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Fecha nacimiento</th>
                  <td>${data.fecha_nacimiento}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Cuenta bancaria</th>
                <td>${data.cuenta_bancaria_format}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>CCI </th>
                <td>${data.cci_format}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Banco</th>
                <td>${data.banco}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Titular cuenta </th>
                <td>${data.titular_cuenta}</td>
              </tr>
              
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>DNI anverso</th>
                <td> ${imagen_dni_anverso} <br>${btn_imagen_dni_anverso}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>DNI reverso</th>
                <td> ${imagen_dni_reverso}<br>${btn_imagen_dni_reverso}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>CV documentado</th>
                <td> ${cv_documentado} <br>${btn_cv_documentado}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>CV no documentado</th>
                <td> ${cv_no_documentado} <br>${btn_cv_no_documentado}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>`;
  
    $("#datostrabajador").html(verdatos);

  });
}

// mostramos los datos para editar
function mostrar(idtrabajador) {

  limpiar();  

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-trabajador").modal("show")

  $.post("../ajax/all_trabajador.php?op=mostrar", { idtrabajador: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);   

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
    $("#cci").val(data.cci);
    $("#banco").val(data.idbancos).trigger("change");
    $("#tipo").val(data.idtipo_trabajador).trigger("change");
    $("#ocupacion").val(data.idocupacion).trigger("change");
    $("#titular_cuenta").val(data.titular_cuenta);
    $("#idtrabajador").val(data.idtrabajador);
    $("#ruc").val(data.ruc);
    //cci, idtipo, idocupacion, ruc, cv_documentado, cv_no_documentado
    if (data.imagen_perfil!="") {

			$("#foto1_i").attr("src", "../dist/docs/all_trabajador/perfil/" + data.imagen_perfil);

			$("#foto1_actual").val(data.imagen_perfil);
		}

    if (data.imagen_dni_anverso != "") {

			$("#foto2_i").attr("src", "../dist/docs/all_trabajador/dni_anverso/" + data.imagen_dni_anverso);

			$("#foto2_actual").val(data.imagen_dni_anverso);
		}

    if (data.imagen_dni_reverso != "") {

			$("#foto3_i").attr("src", "../dist/docs/all_trabajador/dni_reverso/" + data.imagen_dni_reverso);

			$("#foto3_actual").val(data.imagen_dni_reverso);
		}
    //cvs
    //validamoos DOC-4
    if (data.cv_documentado != "" ) {

      $("#doc_old_4").val(data.cv_documentado);

      $("#doc4_nombre").html('CV.' + extrae_extencion(data.cv_documentado));
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.cv_documentado) == "xls") {

        $("#doc4_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

      } else {

        if ( extrae_extencion(data.cv_documentado) == "xlsx" ) {

          $("#doc4_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

        }else{

          if ( extrae_extencion(data.cv_documentado) == "csv" ) {

            $("#doc4_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

          }else{

            if ( extrae_extencion(data.cv_documentado) == "xlsm" ) {

              $("#doc4_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

            }else{

              if ( extrae_extencion(data.cv_documentado) == "doc" || extrae_extencion(data.cv_documentado) == "docx" ) {

                $("#doc4_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
  
              }else{

                if ( extrae_extencion(data.cv_documentado) == "pdf" ) {

                  $("#doc4_ver").html('<iframe src="../dist/docs/all_trabajador/cv_documentado/'+data.cv_documentado+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

                }else{

                  $("#doc4_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                }
              }
            }
          }
        }
      }
    } else {

      $("#doc4_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');

      $("#doc4_nombre").html('');

      $("#doc_old_4").val("");
    }

    //validamoos DOC-5
    if (data.cv_no_documentado != "" ) {

      $("#doc_old_5").val(data.cv_no_documentado);

      $("#doc5_nombre").html('Analisis de costos unitarios.' + extrae_extencion(data.cv_no_documentado));

      // $("#doc5_ver").html('<iframe src="../dist/pdf/'+data.cv_no_documentado+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.cv_no_documentado) == "xls") {

        $("#doc5_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

      } else {

        if ( extrae_extencion(data.cv_no_documentado) == "xlsx" ) {

          $("#doc5_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

        }else{

          if ( extrae_extencion(data.cv_no_documentado) == "csv" ) {

            $("#doc5_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

          }else{

            if ( extrae_extencion(data.cv_no_documentado) == "xlsm" ) {

              $("#doc5_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

            }else{

              if ( extrae_extencion(data.cv_no_documentado) == "doc" || extrae_extencion(data.cv_no_documentado) == "docx" ) {

                $("#doc5_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
  
              }else{

                if ( extrae_extencion(data.cv_no_documentado) == "pdf" ) {

                  $("#doc5_ver").html('<iframe src="../dist/docs/all_trabajador/cv_no_documentado/'+data.cv_no_documentado+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

                }else{

                  $("#doc5_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                }
              }
            }
          }
        }
      }
    } else {

      $("#doc5_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');

      $("#doc5_nombre").html('');

      $("#doc_old_5").val("");
    }

    edades();
  });
}

//Función para desactivar registros
function desactivar(idtrabajador) {

  Swal.fire({
    icon: "warning",
    title: 'Antes de expulsar ingrese una descripción',
    input: 'text',
    inputAttributes: {
      autocapitalize: 'off'
    },
    showCancelButton: true,
    cancelButtonColor: "#d33",
    confirmButtonText: 'Si, expulsar!',
    confirmButtonColor: "#28a745",
    showLoaderOnConfirm: true,
    preConfirm: (login) => {
      // console.log(login);
      return fetch(`../ajax/all_trabajador.php?op=desactivar&idtrabajador=${idtrabajador}&descripcion=${login}`)
        .then(response => {
          console.log(response);
          if (!response.ok) {
            throw new Error(response.statusText)
          }
          return response.json()
        })
        .catch(error => {
          Swal.showValidationMessage(
            `Request failed: ${error}`
          )
        })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    console.log(result );
    if (result.isConfirmed) {
      if (result.value.ok) {
        Swal.fire("Expulsado!", "Tu trabajador ha sido expulsado.", "success");
        tabla.ajax.reload(); tabla2.ajax.reload();
      }else{
        Swal.fire("Error!", "No se pudo realizar la petición.", "error");
      }     
    }
  })

  // Swal.fire({
  //   title: "¿Está Seguro de  Desactivar  el trabajador?",
  //   text: "",
  //   icon: "warning",
  //   showCancelButton: true,
  //   confirmButtonColor: "#28a745",
  //   cancelButtonColor: "#d33",
  //   confirmButtonText: "Si, desactivar!",
  // }).then((result) => {
  //   if (result.isConfirmed) {
  //     $.post("../ajax/all_trabajador.php?op=desactivar", { idtrabajador: idtrabajador }, function (e) {

  //       Swal.fire("Desactivado!", "Tu trabajador ha sido desactivado.", "success");
    
  //       tabla.ajax.reload(); tabla2.ajax.reload();
  //     });      
  //   }
  // });   
}

//Función para activar registros
function activar(idtrabajador) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  el trabajador?",
    text: "Este trabajador tendra acceso al sistema",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/all_trabajador.php?op=activar", { idtrabajador: idtrabajador }, function (e) {

        Swal.fire("Activado!", "Tu trabajador ha sido activado.", "success");

        tabla.ajax.reload(); tabla2.ajax.reload();
      });
      
    }
  });      
}


//Función para desactivar registros
function eliminar(idtrabajador) {
   //----------------------------
 Swal.fire({

  title: "!Elija una opción¡",
  html: "Al <b>Expulsar</b> Padrá encontrar el registro en la tabla inferior! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!",
  icon: "warning",
  showCancelButton: true,
  showDenyButton: true,
  confirmButtonColor: "#17a2b8",
  denyButtonColor: "#d33",
  cancelButtonColor: "#6c757d",    
  confirmButtonText: `<i class="fas fa-times"></i> Expulsar`,
  denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,

}).then((result) => {

  if (result.isConfirmed) {
  
    Swal.fire({
      icon: "warning",
      title: 'Antes de expulsar ingrese una descripción',
      input: 'text',
      inputAttributes: {
        autocapitalize: 'off'
      },
      showCancelButton: true,
      cancelButtonColor: "#d33",
      confirmButtonText: 'Si, expulsar!',
      confirmButtonColor: "#28a745",
      showLoaderOnConfirm: true,
      preConfirm: (login) => {
        // console.log(login);
        return fetch(`../ajax/all_trabajador.php?op=desactivar&idtrabajador=${idtrabajador}&descripcion=${login}`)
          .then(response => {
            console.log(response);
            if (!response.ok) {
              throw new Error(response.statusText)
            }
            return response.json()
          })
          .catch(error => {
            Swal.showValidationMessage(
              `Request failed: ${error}`
            )
          })
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      console.log(result );
      if (result.isConfirmed) {
        if (result.value.ok) {
          Swal.fire("Expulsado!", "Tu trabajador ha sido expulsado.", "success");
          tabla.ajax.reload(); tabla2.ajax.reload();
        }else{
          Swal.fire("Error!", "No se pudo realizar la petición.", "error");
        }     
      }
    })

  }else if (result.isDenied) {
   //op=eliminar
    $.post("../ajax/all_trabajador.php?op=eliminar", { idtrabajador: idtrabajador }, function (e) {
      if (e == 'ok') {

        Swal.fire("Eliminado!", "Tu trabajador ha sido Eliminado.", "success");		 

        tabla.ajax.reload(); tabla2.ajax.reload();
        
      }else{

        Swal.fire("Error!", e, "error");
      }
    }); 

  }

});
}


init();

$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {

      guardaryeditar(e);

    },
  });

  $("#form-trabajador").validate({
    rules: {
      tipo_documento: { required: true },
      num_documento: { required: true, minlength: 6, maxlength: 20 },
      nombre: { required: true, minlength: 6, maxlength: 100 },
      email: { email: true, minlength: 10, maxlength: 50 },
      direccion: { minlength: 5, maxlength: 70 },
      telefono: { minlength: 8 },
      tipo_trabajador: { required: true},
      cargo: { required: true},
      c_bancaria: { minlength: 10,},
      banco: { required: true},
      tipo: { required: true},
      ocupacion: { required: true},
      ruc: { minlength: 11, maxlength: 11},
      // terms: { required: true },
    },
    messages: {
      tipo_documento: {
        required: "Por favor selecione un tipo de documento", 
      },
      num_documento: {
        required: "Ingrese un número de documento",
        minlength: "El número documento debe tener MÍNIMO 6 caracteres.",
        maxlength: "El número documento debe tener como MÁXIMO 20 caracteres.",
      },
      nombre: {
        required: "Por favor ingrese los nombres y apellidos",
        minlength: "El número documento debe tener MÍNIMO 6 caracteres.",
        maxlength: "El número documento debe tener como MÁXIMO 100 caracteres.",
      },
      email: {
        required: "Por favor ingrese un correo electronico.",
        email: "Por favor ingrese un coreo electronico válido.",
        minlength: "El correo electronico debe tener MÍNIMO 10 caracteres.",
        maxlength: "El correo electronico debe tener como MÁXIMO 50 caracteres.",
      },
      direccion: {
        minlength: "La dirección debe tener MÍNIMO 5 caracteres.",
        maxlength: "La dirección debe tener como MÁXIMO 70 caracteres.",
      },
      telefono: {
        minlength: "El teléfono debe tener MÍNIMO 8 caracteres.",
      },
      tipo_trabajador: {
        required: "Por favor  seleccione un tipo trabajador.",
      },
      cargo: {
        required: "Por favor  un cargo.",
      },
      c_bancaria: {
        minlength: "El número documento debe tener 10 caracteres."
      },
      tipo: {
        required: "Este campo es requerido",
      },
      ocupacion: {
        required: "Este campo es requerido",
      },
      banco: {
        required: "Este campo es requerido",
      },
      ruc: {
        minlength: "El número documento debe tener 11 caracteres.",
        maxlength: "El número documento debe tener maximo 11 caracteres.",
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

/*Validación Fecha de Nacimiento Mayoria de edad del usuario*/
function edades() {

  var fechaUsuario = $("#nacimiento").val();

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
    $("#edad").val(edad);

    $("#p_edad").html(`${edad} años`);
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
      if(mesesF > 0) {
          edadF --;
      }
      let diasF = 30 - dias;
      // console.log(`Te faltan ${edadF} años, ${mesesF} meses, ${diasF} días para ser adulto`);
    }

  } else {

    $("#edad").val("");

    $("#p_edad").html(`0 años`); 
  }
}

// restringimos la fecha para no elegir mañana
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

today = yyyy+'-'+mm+'-'+dd;
document.getElementById("nacimiento").setAttribute("max", today);

function validacion_form() {
  if ($('#nombre').val() == '' || $('#tipo_documento').val() == '' ) {
    $('.validar_nombre').addClass('has-error');
    console.log("vacio");
    return false;
  }else{
    $('.validar_nombre').removeClass('has-error');
    console.log("no vacio");
    return true;
  }

}

// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_documento").val();

  let dni_ruc = $("#num_documento").val(); 
   
  if (tipo_doc == "DNI") {

    if (dni_ruc.length == "8") {

      $.post("../ajax/persona.php?op=reniec", { dni: dni_ruc }, function (data, status) {

        data = JSON.parse(data);  console.log(data);

        if (data == null) {

          $("#search").show();
  
          $("#charge").hide();
  
          toastr.error("Verifique su conexion a internet o el sistema de BUSQUEDA esta en mantenimiento.");
          
        } else {
          if (data.success == false) {

            $("#search").show();

            $("#charge").hide();

            toastr.error("Es probable que el sistema de busqueda esta en mantenimiento o los datos no existe en la RENIEC!!!");

          } else {

            $("#search").show();

            $("#charge").hide();

            $("#nombre").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);
            $("#titular_cuenta").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

            toastr.success("Persona encontrada!!!!");
          }
        }
        
      });
    } else {

      $("#search").show();

      $("#charge").hide();

      toastr.info("Asegurese de que el DNI tenga 8 dígitos!!!");
    }
  } else {
    if (tipo_doc == "RUC") {

      if (dni_ruc.length == "11") {
        $.post("../ajax/persona.php?op=sunat", { ruc: dni_ruc }, function (data, status) {

          data = JSON.parse(data);    console.log(data);

          if (data == null) {
            $("#search").show();
    
            $("#charge").hide();
    
            toastr.error("Verifique su conexion a internet o el sistema de BUSQUEDA esta en mantenimiento.");
            
          } else {

            if (data.success == false) {

              $("#search").show();

              $("#charge").hide();

              toastr.error("Datos no encontrados en la SUNAT!!!");
              
            } else {

              if (data.estado == "ACTIVO") {

                $("#search").show();

                $("#charge").hide();

                data.razonSocial == null ? $("#nombre").val(data.nombreComercial) : $("#nombre").val(data.razonSocial);

                data.razonSocial == null ? $("#titular_cuenta").val(data.nombreComercial) : $("#titular_cuenta").val(data.razonSocial);

                var departamento = (data.departamento == null ? "" : data.departamento); 
                var provincia = (data.provincia == null ? "" : data.provincia);
                var distrito = (data.distrito == null ? "" : data.distrito);                

                data.direccion == null ? $("#direccion").val(`${departamento} - ${provincia} - ${distrito}`) : $("#direccion").val(data.direccion);

                toastr.success("Persona encontrada!!");

              } else {

                toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

                $("#search").show();

                $("#charge").hide();

                $("#nombre").val(data.razonSocial);

                data.razonSocial == null ? $("#nombre").val(data.nombreComercial) : $("#nombre").val(data.razonSocial);

                data.razonSocial == null ? $("#titular_cuenta").val(data.nombreComercial) : $("#titular_cuenta").val(data.razonSocial);
                
                data.direccion == null ? $("#direccion").val(`${data.departamento} - ${data.provincia} - ${data.distrito}`) : $("#direccion").val(data.direccion);

              }
            }
          }          
        });
      } else {
        $("#search").show();

        $("#charge").hide();

        toastr.info("Asegurese de que el RUC tenga 11 dígitos!!!");
      }
    } else {
      if (tipo_doc == "CEDULA" || tipo_doc == "OTRO") {

        $("#search").show();

        $("#charge").hide();

        toastr.info("No necesita hacer consulta");

      } else {

        $("#tipo_doc").addClass("is-invalid");

        $("#search").show();

        $("#charge").hide();

        toastr.error("Selecione un tipo de documento");
      }
    }
  }
}

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco").select2("val") == null || $("#banco").select2("val") == "") {

    $("#c_bancaria").prop("readonly",true);   $("#cci").prop("readonly",true);
  } else {
    
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>'); $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');

    $("#c_bancaria").prop("readonly",false);   $("#cci").prop("readonly",false);

    $.post("../ajax/all_trabajador.php?op=formato_banco", { idbanco: $("#banco").select2("val") }, function (data, status) {

      data = JSON.parse(data);  console.log(data); 

      $(".chargue-format-1").html('Cuenta Bancaria'); $(".chargue-format-2").html('CCI');

      var format_cta = decifrar_format_banco(data.formato_cta); var format_cci = decifrar_format_banco(data.formato_cci);

      $("#c_bancaria").inputmask(`${format_cta}`);

      $("#cci").inputmask(`${format_cci}`);
    });    
  }  
}

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

function convert_minuscula(e) {
  e.value = e.value.toLowerCase();
}