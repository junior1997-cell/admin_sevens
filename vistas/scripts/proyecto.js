var tabla;
var tabla2;
//Función que se ejecuta al inicio
function init(){ 

  tablero();

  listar();
  listar2();

  $("#guardar_registro").on("click", function (e) { $("#submit-form-proyecto").submit(); });

  $("#guardar_registro_valorizaciones").on("click", function (e) { $("#form-valorizaciones").submit(); });
  $("#form-valorizaciones").on("submit", function (e) { guardar_editar_valorizacion(e); });

  $('#mEscritorio').addClass("active");
}

//Función limpiar
function limpiar() {
  $("#idproyecto").val("");  
  $("#tipo_documento option[value='RUC']").attr("selected", true);
  $("#numero_documento").val(""); 
  $("#empresa").val(""); 
  $("#nombre_proyecto").val(""); $("#nombre_codigo").val("");
  $("#ubicacion").val(""); 
  $("#actividad_trabajo").val("");  
  $("#fecha_inicio_fin").val("");    
  $("#plazo").val(""); 
  $("#costo").val(""); 
  $("#empresa_acargo").val("Seven's Ingenieros SAC"); 

  $("#doc1").val(""); 
  $("#doc_old_1").val(""); 
  $("#doc1_nombre").html('');
  $("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  $("#doc2").val(""); 
  $("#doc_old_2").val("");
  $("#doc2_nombre").html('');
  $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  $("#doc3").val(""); 
  $("#doc_old_3").val("");
  $("#doc3_nombre").html('');
  $("#doc3_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >'); 

  $("#doc4").val(""); 
  $("#doc_old_4").val("");
  $("#doc4_nombre").html('');
  $("#doc4_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  $("#doc5").val(""); 
  $("#doc_old_5").val("");
  $("#doc5_nombre").html('');
  $("#doc5_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  $("#doc6").val(""); 
  $("#doc_old_6").val("");
  $("#doc6_nombre").html('');
  $("#doc6_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

}

//Función Listar en curso o no empezados
function listar() {

  tabla=$('#tabla-proyectos').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/proyecto.php?op=listar',
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
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
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();

   
  
}

//Función Listar todos lo proyectos terminados
function listar2() {

  tabla2=$('#tabla-proyectos-terminados').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/proyecto.php?op=listar-proyectos-terminados',
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
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
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();

   
  
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proyecto")[0]);

  $.ajax({
    url: "../ajax/proyecto.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

        tabla.ajax.reload();	

        Swal.fire("Correcto!", "Proyecto guardado correctamente", "success");	      
         
				limpiar();

        $("#modal-agregar-proyecto").modal("hide");        

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

            setTimeout(l_m, 600);
          }
        }
      }, false);
      return xhr;
    }
  });
}

//Función para guardar o editar
function guardar_editar_valorizacion(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-valorizaciones")[0]);

  $.ajax({
    url: "../ajax/proyecto.php?op=editar_doc_valorizaciones",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

        tabla.ajax.reload();	

        Swal.fire("Correcto!", "Documento guardado correctamente", "success");	      
         
				limpiar();

        $("#modal-agregar-valorizaciones").modal("hide");        

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
          $("#barra_progress2").css({"width": percentComplete+'%'});

          $("#barra_progress2").text(percentComplete.toFixed(2)+" %");

          if (percentComplete === 100) {

            setTimeout(l_m, 600);
          }
        }
      }, false);
      return xhr;
    }
  });
}

function l_m(){
  
  // limpiar();
  $("#barra_progress").css({"width":'0%'});
  $("#barra_progress").text("0%");

  $("#barra_progress2").css({"width":'0%'});
  $("#barra_progress2").text("0%");
  
}

//Función para desactivar registros
function empezar_proyecto(idproyecto) {
  $(".tooltip").hide();
  Swal.fire({
    title: "¿Está Seguro de  Empezar  el proyecto ?",
    text: "Tendras acceso a agregar o editar: provedores, trabajadores!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Empezar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/proyecto.php?op=empezar_proyecto", { idproyecto: idproyecto }, function (e) {
        if (e == 'ok') {

          Swal.fire("En curso!", "Tu proyecto esta en curso.", "success");		 
  
          tabla.ajax.reload();
          tabla2.ajax.reload();
          
        }else{
  
          Swal.fire("Error!", e, "error");
        }
      });      
    }
  });   
}

//Función para activar registros
function terminar_proyecto(idproyecto) {
  $(".tooltip").hide();
  Swal.fire({

    title: "¿Está Seguro de  Terminar  el Proyecto?",
    text: "No tendras acceso a editar o agregar: proveedores o trabajadores!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Terminar!",

  }).then((result) => {

    if (result.isConfirmed) {

      $.post("../ajax/proyecto.php?op=terminar_proyecto", { idproyecto: idproyecto }, function (e) {

        if (e == 'ok') {

          Swal.fire("Terminado!", "Tu Proyecto ha sido terminado.", "success");		 
  
          tabla.ajax.reload();
          tabla2.ajax.reload();
          
        }else{
  
          Swal.fire("Error!", e, "error");
        }
      });      
    }
  });      
}

//Función para activar registros
function reiniciar_proyecto(idproyecto) {
  $(".tooltip").hide();
  Swal.fire({

    title: "¿Está Seguro de  Reactivar  el Proyecto?",
    text: "Despues de esto tendrás que empezar el proyecto!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Reactivar!",

  }).then((result) => {

    if (result.isConfirmed) {

      $.post("../ajax/proyecto.php?op=reiniciar_proyecto", { idproyecto: idproyecto }, function (e) {

        if (e == 'ok') {

          Swal.fire("Reactivado!", "Tu Proyecto ha sido Reactivado.", "success");		 
  
          tabla.ajax.reload();
          tabla2.ajax.reload();
          
        }else{
  
          Swal.fire("Error!", e, "error");
        }
      });      
    }
  });      
}

init();


$(function () {
  

  //Date range picker
  $('#fecha_inicio_fin').daterangepicker({
    dateFormat: 'YYYY/MM/DD',
    autoUpdateInput: false,
    inline: true,
    locale: {
      cancelLabel: 'Clear'
    },
    isInvalidDate: function(date) {
      if (date.day() == 0 || date.day() == 1 || date.day() == 2 || date.day() == 3 || date.day() == 4 || date.day() == 5)
        return false;
      return true;
    },

    
  });
  $('input[name="fecha_inicio_fin"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
  });

  $('input[name="fecha_inicio_fin"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });
  

  // validamo el formulario
  $.validator.setDefaults({

    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      // $("#chat").animate({ scrollTop: $(this).prop("scrollHeight")}, 1000);
      guardaryeditar(e);       
    },
  });

  $("#form-proyecto").validate({
    rules: {
      tipo_documento: { maxlength: 45 },
      numero_documento: { required: true, minlength: 6, maxlength: 20 },
      empresa: { required: true, minlength: 6, maxlength: 200 },
      nombre_proyecto: { required: true, minlength: 6 },
      nombre_codigo: {minlength: 4 },
      ubicacion: {minlength: 6, maxlength: 300},
      actividad_trabajo: {minlength: 6},
      empresa_acargo: {minlength: 6, maxlength: 200},
      
      fecha_inicio_fin:{required: true,minlength: 1, maxlength: 25}
    },
    messages: {
      numero_documento: {
        required: "Este campo es requerido.",
        minlength: "El login debe tener MÍNIMO 6 caracteres.",
        maxlength: "El login debe tener como MÁXIMO 20 caracteres.",
      },
      empresa: {
        required: "Este campo es requerido.",
        minlength: "La Empresa debe tener MÍNIMO 6 caracteres.",
        maxlength: "La Empresa debe tener como MÁXIMO 200 caracteres.",
      },
      nombre_proyecto: {
        required: "Este campo es requerido.",
        minlength: "El nombre de proyecto debe tener MÍNIMO 6 caracteres.",
        maxlength: "La nombre de proyecto debe tener como MÁXIMO 200 caracteres.",
      },
      nombre_codigo: {
        minlength: "El nombre de proyecto debe tener MÍNIMO 4 caracteres.",
      },
      ubicacion: {
        minlength: "La ubicación debe tener MÍNIMO 6 caracteres.",
        maxlength: "La ubicación debe tener como MÁXIMO 300 caracteres.",
      },
      actividad_trabajo: {
        minlength: "La actividad de trabajo debe tener MÍNIMO 6 caracteres.",
      },
      empresa_acargo: {
        minlength: "La Empresa a cargo debe tener MÍNIMO 6 caracteres.",
        maxlength: "La Empresa a cargo debe tener como MÁXIMO 200 caracteres.",
      },
     
      fecha_inicio_fin: {
        required: "Este campo es requerido.",
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

      // if ($("#trabajador").select2("val") == null && $("#trabajador_old").val() == "") {
         
      //   $("#trabajador_validar").show(); //console.log($("#trabajador").select2("val") + ", "+ $("#trabajador_old").val());

      // } else {

      //   $("#trabajador_validar").hide();
      // }       
    },
  });
});

// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_documento").val();

  let dni_ruc = $("#numero_documento").val(); 
   
  if (tipo_doc == "DNI") {

    if (dni_ruc.length == "8") {

      $.post("../ajax/persona.php?op=reniec", { dni: dni_ruc }, function (data, status) {

        data = JSON.parse(data);

        console.log(data);

        if (data.success == false) {

          $("#search").show();

          $("#charge").hide();

          toastr.error("Es probable que el sistema de busqueda esta en mantenimiento o los datos no existe en la RENIEC!!!");

        } else {

          $("#search").show();

          $("#charge").hide();

          $("#empresa").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

          toastr.success("Cliente encontrado!!!!");
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

          data = JSON.parse(data);

          console.log(data);
          if (data.success == false) {

            $("#search").show();

            $("#charge").hide();

            toastr.error("Datos no encontrados en la SUNAT!!!");
            
          } else {

            if (data.estado == "ACTIVO") {

              $("#search").show();

              $("#charge").hide();

              $("#empresa").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);
              
              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);
              // $("#direccion").val(data.direccion);
              toastr.success("Cliente encontrado");
            } else {

              toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

              $("#search").show();

              $("#charge").hide();

              $("#empresa").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);
              
              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);

              // $("#direccion").val(data.direccion);
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

// caculamos el plazo
function calcular_palzo() {

  $("#fecha_inicio_fin").on("apply.daterangepicker", function (ev, picker) { 

    var plazo_dia = picker.endDate.diff(picker.startDate, "days");

    $("#plazo").val( plazo_dia +' dias');    
  });
}

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addDocs(e,$("#doc1").attr("id")) });

$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addDocs(e,$("#doc2").attr("id")) });

$("#doc3_i").click(function() {  $('#doc3').trigger('click'); });
$("#doc3").change(function(e) {  addDocs(e,$("#doc3").attr("id")) });

$("#doc4_i").click(function() {  $('#doc4').trigger('click'); });
$("#doc4").change(function(e) {  addDocs(e,$("#doc4").attr("id")) });

$("#doc5_i").click(function() {  $('#doc5').trigger('click'); });
$("#doc5").change(function(e) {  addDocs(e,$("#doc5").attr("id")) });

$("#doc6_i").click(function() {  $('#doc6').trigger('click'); });
$("#doc6").change(function(e) {  addDocs(e,$("#doc6").attr("id")) });

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
function re_visualizacion(id) {

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
            $("#doc"+id+"_ver").html('<iframe src="../dist/pdf/'+antiguopdf+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
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

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

// Eliminamos el doc 2
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

// Eliminamos el doc 3
function doc3_eliminar() {

	$("#doc3").val("");

	$("#doc3_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc3_nombre").html("");
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

// Eliminamos el doc 6
function doc6_eliminar() {

	$("#doc6").val("");

	$("#doc6_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc6_nombre").html("");
} 

function ver_modal_docs(verdoc1, verdoc2, verdoc3, verdoc4, verdoc5, verdoc6) {
  console.log(verdoc1, verdoc2, verdoc3,verdoc4, verdoc5, verdoc6);
  $('#modal-ver-docs').modal("show");

  if (verdoc1 == "") {

    $('#verdoc1').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc1_nombre").html("Contratro de obra"+
      '<div class="col-md-12 row mt-2">'+
        '<div class="col-md-6">'+
          '<a class="btn btn-warning  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            '<i class="fas fa-download"></i>'+
          '</a>'+
          '</div>'+

          '<div class="col-md-6">'+
          '<a class="btn btn-info  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            'Ver completo <i class="fas fa-expand"></i>'+
          '</a>'+
        '</div>'+
      '</div>'+
    '');
  } else {
    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(verdoc1) == "xls") {

      $("#verdoc1").html('<img src="../dist/svg/xls.svg" alt="" width="auto" height="206" >');
      $("#verdoc1_nombre").html("Contratro de obra."+ extrae_extencion(verdoc1) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc1+'"  download="Contratro de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );
    } else {

      if ( extrae_extencion(verdoc1) == "xlsx" ) {

        $("#verdoc1").html('<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="206" >');
        $("#verdoc1_nombre").html("Contratro de obra."+ extrae_extencion(verdoc1) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc1+'"  download="Contratro de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

      }else{

        if ( extrae_extencion(verdoc1) == "csv" ) {

          $("#verdoc1").html('<img src="../dist/svg/csv.svg" alt="" width="auto" height="206" >');
          $("#verdoc1_nombre").html("Contratro de obra."+ extrae_extencion(verdoc1) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc1+'"  download="Contratro de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

        }else{

          if ( extrae_extencion(verdoc1) == "xlsm" ) {

            $("#verdoc1").html('<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="206" >');
            $("#verdoc1_nombre").html("Contratro de obra."+ extrae_extencion(verdoc1) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc1+'"  download="Contratro de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

          }else{

            if ( extrae_extencion(verdoc1) == "doc" || extrae_extencion(verdoc1) == "docx" ) {

              $("#verdoc1").html('<img src="../dist/svg/docx.svg" alt="" width="auto" height="206" >');
              $("#verdoc1_nombre").html("Contratro de obra."+ extrae_extencion(verdoc1) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc1+'"  download="Contratro de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

            }else{

              if ( extrae_extencion(verdoc1) == "pdf" ) {

                $("#verdoc1").html('<iframe src="../dist/pdf/'+verdoc1+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');
                $("#verdoc1_nombre").html("Contratro de obra."+ extrae_extencion(verdoc1) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc1+'"  download="Contratro de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block " href="../dist/pdf/'+ verdoc1 +'" target="_blank"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }else{

                $("#verdoc1").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="206" >');
                $("#verdoc1_nombre").html("Contratro de obra."+ extrae_extencion(verdoc1) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc1+'"  download="Contratro de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }
            }
          }
        }
      }
    }
    // $('#verdoc1').html('<embed src="../dist/pdf/'+verdoc1+'" type="application/pdf" width="100%" height="200px" />');
    
  }
  
  if (verdoc2 == "") {

    $('#verdoc2').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc2_nombre").html("Entrega de terreno"+
      '<div class="col-md-12 row mt-2">'+
        '<div class="col-md-6">'+
          '<a class="btn btn-warning  btn-block" href="#"  onclick="no_pdf();" style="padding:0px 12px 0px 12px !important;" type="button" >'+
            '<i class="fas fa-download"></i>'+
          '</a>'+
        '</div>'+

        '<div class="col-md-6">'+
          '<a class="btn btn-info  btn-block" href="#"  onclick="no_pdf();" style="padding:0px 12px 0px 12px !important;" type="button" >'+
            'Ver completo <i class="fas fa-expand"></i>'+
          '</a>'+
        '</div>'+
      '</div>'+
    '');

  } else {
     
    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(verdoc2) == "xls") {

      $("#verdoc2").html('<img src="../dist/svg/xls.svg" alt="" width="auto" height="206" >');
      $("#verdoc2_nombre").html("Entrega de terreno."+ extrae_extencion(verdoc2) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc2+'"  download="Entrega de terreno" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );
    } else {

      if ( extrae_extencion(verdoc2) == "xlsx" ) {

        $("#verdoc2").html('<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="206" >');
        $("#verdoc2_nombre").html("Entrega de terreno."+ extrae_extencion(verdoc2) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc2+'"  download="Entrega de terreno" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

      }else{

        if ( extrae_extencion(verdoc2) == "csv" ) {

          $("#verdoc2").html('<img src="../dist/svg/csv.svg" alt="" width="auto" height="206" >');
          $("#verdoc2_nombre").html("Entrega de terreno."+ extrae_extencion(verdoc2) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc2+'"  download="Entrega de terreno" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

        }else{

          if ( extrae_extencion(verdoc2) == "xlsm" ) {

            $("#verdoc2").html('<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="206" >');
            $("#verdoc2_nombre").html("Entrega de terreno."+ extrae_extencion(verdoc2) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc2+'"  download="Entrega de terreno" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

          }else{

            if ( extrae_extencion(verdoc2) == "doc" || extrae_extencion(verdoc2) == "docx" ) {

              $("#verdoc2").html('<img src="../dist/svg/docx.svg" alt="" width="auto" height="206" >');
              $("#verdoc2_nombre").html("Entrega de terreno."+ extrae_extencion(verdoc2) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc2+'"  download="Entrega de terreno" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

            }else{

              if ( extrae_extencion(verdoc2) == "pdf" ) {

                $("#verdoc2").html('<iframe src="../dist/pdf/'+verdoc2+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');
                $("#verdoc2_nombre").html("Entrega de terreno."+ extrae_extencion(verdoc2) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc2+'"  download="Entrega de terreno" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block " href="../dist/pdf/'+ verdoc2 +'" target="_blank"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }else{

                $("#verdoc2").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="206" >');
                $("#verdoc2_nombre").html("Entrega de terreno."+ extrae_extencion(verdoc2) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc2+'"  download="Entrega de terreno" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }
            }
          }
        }
      }
    }
  }

  if (verdoc3 == "") {

    $('#verdoc3').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc3_nombre").html("Inicio de obra"+
      '<div class="col-md-12 row mt-2">'+
        '<div class="col-md-6">'+
          '<a class="btn btn-warning  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            '<i class="fas fa-download"></i>'+
          '</a>'+
          '</div>'+

          '<div class="col-md-6">'+
          '<a class="btn btn-info  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            'Ver completo <i class="fas fa-expand"></i>'+
          '</a>'+
        '</div>'+
      '</div>'+
    '');

  } else {

    
    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(verdoc3) == "xls") {

      $("#verdoc3").html('<img src="../dist/svg/xls.svg" alt="" width="auto" height="206" >');
      $("#verdoc3_nombre").html("Inicio de obra."+ extrae_extencion(verdoc3) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc3+'"  download="Inicio de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );
    } else {

      if ( extrae_extencion(verdoc3) == "xlsx" ) {

        $("#verdoc3").html('<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="206" >');
        $("#verdoc3_nombre").html("Inicio de obra."+ extrae_extencion(verdoc3) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc3+'"  download="Inicio de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

      }else{

        if ( extrae_extencion(verdoc3) == "csv" ) {

          $("#verdoc3").html('<img src="../dist/svg/csv.svg" alt="" width="auto" height="206" >');
          $("#verdoc3_nombre").html("Inicio de obra."+ extrae_extencion(verdoc3) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc3+'"  download="Inicio de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

        }else{

          if ( extrae_extencion(verdoc3) == "xlsm" ) {

            $("#verdoc3").html('<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="206" >');
            $("#verdoc3_nombre").html("Inicio de obra."+ extrae_extencion(verdoc3) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc3+'"  download="Inicio de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

          }else{

            if ( extrae_extencion(verdoc3) == "doc" || extrae_extencion(verdoc3) == "docx" ) {

              $("#verdoc3").html('<img src="../dist/svg/docx.svg" alt="" width="auto" height="206" >');
              $("#verdoc3_nombre").html("Inicio de obra."+ extrae_extencion(verdoc3) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc3+'"  download="Inicio de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

            }else{

              if ( extrae_extencion(verdoc3) == "pdf" ) {

                $("#verdoc3").html('<iframe src="../dist/pdf/'+verdoc3+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');
                $("#verdoc3_nombre").html("Inicio de obra."+ extrae_extencion(verdoc3) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc3+'"  download="Inicio de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block " href="../dist/pdf/'+ verdoc3 +'" target="_blank"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }else{

                $("#verdoc3").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="206" >');
                $("#verdoc3_nombre").html("Inicio de obra."+ extrae_extencion(verdoc3) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc3+'"  download="Inicio de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }
            }
          }
        }
      }
    }
  }  

  if (verdoc4 == "") {

    $('#verdoc4').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc4_nombre").html("Presupuesto"+
      '<div class="col-md-12 row mt-2">'+
        '<div class="col-md-6">'+
          '<a class="btn btn-warning  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            '<i class="fas fa-download"></i>'+
          '</a>'+
          '</div>'+

          '<div class="col-md-6">'+
          '<a class="btn btn-info  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            'Ver completo <i class="fas fa-expand"></i>'+
          '</a>'+
        '</div>'+
      '</div>'+
    '');

  } else {    

    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(verdoc4) == "xls") {

      $("#verdoc4").html('<img src="../dist/svg/xls.svg" alt="" width="auto" height="206" >');
      $("#verdoc4_nombre").html("Presupuesto."+ extrae_extencion(verdoc4) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc4+'"  download="Presupuesto" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );
    } else {

      if ( extrae_extencion(verdoc4) == "xlsx" ) {

        $("#verdoc4").html('<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="206" >');
        $("#verdoc4_nombre").html("Presupuesto."+ extrae_extencion(verdoc4) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc4+'"  download="Presupuesto" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

      }else{

        if ( extrae_extencion(verdoc4) == "csv" ) {

          $("#verdoc4").html('<img src="../dist/svg/csv.svg" alt="" width="auto" height="206" >');
          $("#verdoc4_nombre").html("Presupuesto."+ extrae_extencion(verdoc4) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc4+'"  download="Presupuesto" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

        }else{

          if ( extrae_extencion(verdoc4) == "xlsm" ) {

            $("#verdoc4").html('<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="206" >');
            $("#verdoc4_nombre").html("Presupuesto."+ extrae_extencion(verdoc4) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc4+'"  download="Presupuesto" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

          }else{

            if ( extrae_extencion(verdoc4) == "doc" || extrae_extencion(verdoc4) == "docx" ) {

              $("#verdoc4").html('<img src="../dist/svg/docx.svg" alt="" width="auto" height="206" >');
              $("#verdoc4_nombre").html("Presupuesto."+ extrae_extencion(verdoc4) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc4+'"  download="Presupuesto" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

            }else{

              if ( extrae_extencion(verdoc4) == "pdf" ) {

                $("#verdoc4").html('<iframe src="../dist/pdf/'+verdoc4+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');
                $("#verdoc4_nombre").html("Presupuesto."+ extrae_extencion(verdoc4) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc4+'"  download="Presupuesto" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block " href="../dist/pdf/'+ verdoc4 +'" target="_blank"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }else{

                $("#verdoc4").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="206" >');
                $("#verdoc4_nombre").html("Presupuesto."+ extrae_extencion(verdoc4) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc4+'"  download="Presupuesto" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }
            }
          }
        }
      }
    }
  }

  if (verdoc5 == "") {

    $('#verdoc5').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc5_nombre").html("Analisis de costos unitarios"+
      '<div class="col-md-12 row mt-2">'+
        '<div class="col-md-6">'+
          '<a class="btn btn-warning  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            '<i class="fas fa-download"></i>'+
          '</a>'+
          '</div>'+

          '<div class="col-md-6">'+
          '<a class="btn btn-info  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            'Ver completo <i class="fas fa-expand"></i>'+
          '</a>'+
        '</div>'+
      '</div>'+
    '');

  } else {

    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(verdoc5) == "xls") {

      $("#verdoc5").html('<img src="../dist/svg/xls.svg" alt="" width="auto" height="206" >');
      $("#verdoc5_nombre").html("Analisis de costos unitarios."+ extrae_extencion(verdoc5) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc5+'"  download="Analisis de costos unitarios" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );
    } else {

      if ( extrae_extencion(verdoc5) == "xlsx" ) {

        $("#verdoc5").html('<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="206" >');
        $("#verdoc5_nombre").html("Analisis de costos unitarios."+ extrae_extencion(verdoc5) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc5+'"  download="Analisis de costos unitarios" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

      }else{

        if ( extrae_extencion(verdoc5) == "csv" ) {

          $("#verdoc5").html('<img src="../dist/svg/csv.svg" alt="" width="auto" height="206" >');
          $("#verdoc5_nombre").html("Analisis de costos unitarios."+ extrae_extencion(verdoc5) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc5+'"  download="Analisis de costos unitarios" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

        }else{

          if ( extrae_extencion(verdoc5) == "xlsm" ) {

            $("#verdoc5").html('<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="206" >');
            $("#verdoc5_nombre").html("Analisis de costos unitarios."+ extrae_extencion(verdoc5) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc5+'"  download="Analisis de costos unitarios" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

          }else{

            if ( extrae_extencion(verdoc5) == "doc" || extrae_extencion(verdoc5) == "docx" ) {

              $("#verdoc5").html('<img src="../dist/svg/docx.svg" alt="" width="auto" height="206" >');
              $("#verdoc5_nombre").html("Analisis de costos unitarios."+ extrae_extencion(verdoc5) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc5+'"  download="Analisis de costos unitarios" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

            }else{

              if ( extrae_extencion(verdoc5) == "pdf" ) {

                $("#verdoc5").html('<iframe src="../dist/pdf/'+verdoc5+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');
                $("#verdoc5_nombre").html("Analisis de costos unitarios."+ extrae_extencion(verdoc5) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc5+'"  download="Analisis de costos unitarios" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block " href="../dist/pdf/'+ verdoc5 +'" target="_blank"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }else{

                $("#verdoc5").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="206" >');
                $("#verdoc5_nombre").html("Analisis de costos unitarios."+ extrae_extencion(verdoc5) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc5+'"  download="Analisis de costos unitarios" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }
            }
          }
        }
      }
    }
  }

  if (verdoc6 == "") {

    $('#verdoc6').html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" height="206" >');

    $("#verdoc6_nombre").html("Insumos"+
      '<div class="col-md-12 row mt-2">'+
        '<div class="col-md-6">'+
          '<a class="btn btn-warning  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            '<i class="fas fa-download"></i>'+
          '</a>'+
          '</div>'+

          '<div class="col-md-6">'+
          '<a class="btn btn-info  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            'Ver completo <i class="fas fa-expand"></i>'+
          '</a>'+
        '</div>'+
      '</div>'+
    '');

  } else {  

    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(verdoc6) == "xls") {

      $("#verdoc6").html('<img src="../dist/svg/xls.svg" alt="" width="auto" height="206" >');
      $("#verdoc6_nombre").html("Insumos."+ extrae_extencion(verdoc6) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc6+'"  download="Contratro de obra" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );
    } else {

      if ( extrae_extencion(verdoc6) == "xlsx" ) {

        $("#verdoc6").html('<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="206" >');
        $("#verdoc6_nombre").html("Insumos."+ extrae_extencion(verdoc6) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc6+'"  download="Insumos" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

      }else{

        if ( extrae_extencion(verdoc6) == "csv" ) {

          $("#verdoc6").html('<img src="../dist/svg/csv.svg" alt="" width="auto" height="206" >');
          $("#verdoc6_nombre").html("Insumos."+ extrae_extencion(verdoc6) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc6+'"  download="Insumos" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

        }else{

          if ( extrae_extencion(verdoc6) == "xlsm" ) {

            $("#verdoc6").html('<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="206" >');
            $("#verdoc6_nombre").html("Insumos."+ extrae_extencion(verdoc6) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc6+'"  download="Insumos" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

          }else{

            if ( extrae_extencion(verdoc6) == "doc" || extrae_extencion(verdoc6) == "docx" ) {

              $("#verdoc6").html('<img src="../dist/svg/docx.svg" alt="" width="auto" height="206" >');
              $("#verdoc6_nombre").html("Insumos."+ extrae_extencion(verdoc6) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc6+'"  download="Insumos" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

            }else{

              if ( extrae_extencion(verdoc6) == "pdf" ) {

                $("#verdoc6").html('<iframe src="../dist/pdf/'+verdoc6+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');
                $("#verdoc6_nombre").html("Insumos."+ extrae_extencion(verdoc6) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc6+'"  download="Insumos" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block " href="../dist/pdf/'+ verdoc6 +'" target="_blank"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }else{

                $("#verdoc6").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="206" >');
                $("#verdoc6_nombre").html("Insumos."+ extrae_extencion(verdoc6) + '<div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/pdf/'+verdoc6+'"  download="Insumos" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div>  <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" data-toggle="tooltip" data-original-title="Terminar proyecto" href="#"  style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>' );

              }
            }
          }
        }
      }
    }
  }

  $(".tooltip").hide();
}

function no_pdf() {
  toastr.error("No hay DOC disponible, suba un DOC en el apartado de editar!!")
}

function dowload_pdf() {
  toastr.success("El documento se descargara en breve!!")
}

function mostrar(idproyecto) {

  $("#cargando-1-fomulario").hide();

  $("#cargando-2-fomulario").show();

  $("#modal-agregar-proyecto").modal("show")

  $.post("../ajax/proyecto.php?op=mostrar", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();
    
    $("#idproyecto").val(data.idproyecto);  
    $("#tipo_documento option[value='"+data.tipo_documento+"']").attr("selected", true);
    $("#numero_documento").val(data.numero_documento); 
    $("#empresa").val(data.empresa); 
    $("#nombre_proyecto").val(data.nombre_proyecto); $("#nombre_codigo").val(data.nombre_codigo);
    $("#ubicacion").val(data.ubicacion); 
    $("#actividad_trabajo").val(data.actividad_trabajo);  
       
    $("#plazo").val(data.plazo); 
    $("#costo").val(data.costo); 
    $("#empresa_acargo").val(data.empresa_acargo); 

    let fcha_array1 = data.fecha_inicio.split('-');
    let fcha_array2 = data.fecha_fin.split('-');
    let fff = fcha_array1['0'] + '/' +fcha_array1['1'] + '/' + fcha_array1['2'] ;
    let fii = fcha_array2['0'] + '/' +fcha_array2['1'] + '/' + fcha_array2['2'] ;  
    // console.log(fii ); console.log(fff );
    $("#fecha_inicio_fin").val(fii + ' - ' + fff);
    
    //validamoos DOC-1
    if (data.doc1_contrato_obra != ""  ) {

      $("#doc_old_1").val(data.doc1_contrato_obra); 

      $("#doc1_nombre").html('Contrato de obra.' + extrae_extencion(data.doc1_contrato_obra));

      // $("#doc1_ver").html('<iframe src="../dist/pdf/'+data.doc1_contrato_obra+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.doc1_contrato_obra) == "xls") {

        $("#doc1_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

      } else {

        if ( extrae_extencion(data.doc1_contrato_obra) == "xlsx" ) {

          $("#doc1_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

        }else{

          if ( extrae_extencion(data.doc1_contrato_obra) == "csv" ) {

            $("#doc1_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

          }else{

            if ( extrae_extencion(data.doc1_contrato_obra) == "xlsm" ) {

              $("#doc1_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

            }else{

              if ( extrae_extencion(data.doc1_contrato_obra) == "doc" || extrae_extencion(data.doc1_contrato_obra) == "docx" ) {

                $("#doc1_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
  
              }else{

                if ( extrae_extencion(data.doc1_contrato_obra) == "pdf" ) {

                  $("#doc1_ver").html('<iframe src="../dist/pdf/'+data.doc1_contrato_obra+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

                }else{

                  $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                }
              }
            }
          }
        }
      }
    } else {

      $("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');

      $("#doc1_nombre").html('');

      $("#doc_old_1").val(""); 
    }

    //validamoos DOC-2
    if (data.doc2_entrega_terreno != "" ) {

      $("#doc_old_2").val(data.doc2_entrega_terreno);

      $("#doc2_nombre").html('Entrega de terreno.' + extrae_extencion(data.doc2_entrega_terreno) );

      // $("#doc2_ver").html('<iframe src="../dist/pdf/'+data.doc2_entrega_terreno+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.doc2_entrega_terreno) == "xls") {

        $("#doc2_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

      } else {

        if ( extrae_extencion(data.doc2_entrega_terreno) == "xlsx" ) {

          $("#doc2_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

        }else{

          if ( extrae_extencion(data.doc2_entrega_terreno) == "csv" ) {

            $("#doc2_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

          }else{

            if ( extrae_extencion(data.doc2_entrega_terreno) == "xlsm" ) {

              $("#doc2_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

            }else{

              if ( extrae_extencion(data.doc2_entrega_terreno) == "doc" || extrae_extencion(data.doc2_entrega_terreno) == "docx" ) {

                $("#doc2_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
  
              }else{

                if ( extrae_extencion(data.doc2_entrega_terreno) == "pdf" ) {

                  $("#doc2_ver").html('<iframe src="../dist/pdf/'+data.doc2_entrega_terreno+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

                }else{

                  $("#doc2_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                }
              }
            }
          }
        }
      }
    } else {

      $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');

      $("#doc2_nombre").html('');

      $("#doc_old_2").val("");
    }

    //validamoos DOC-3
    if (data.doc3_inicio_obra != "" ) {

      $("#doc_old_3").val(data.doc3_inicio_obra);

      $("#doc3_nombre").html('Inicio de obra.' + extrae_extencion(data.doc3_inicio_obra));

      // $("#doc3_ver").html('<iframe src="../dist/pdf/'+data.doc3_inicio_obra+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.doc3_inicio_obra) == "xls") {

        $("#doc3_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

      } else {

        if ( extrae_extencion(data.doc3_inicio_obra) == "xlsx" ) {

          $("#doc3_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

        }else{

          if ( extrae_extencion(data.doc3_inicio_obra) == "csv" ) {

            $("#doc3_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

          }else{

            if ( extrae_extencion(data.doc3_inicio_obra) == "xlsm" ) {

              $("#doc3_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

            }else{

              if ( extrae_extencion(data.doc3_inicio_obra) == "doc" || extrae_extencion(data.doc3_inicio_obra) == "docx" ) {

                $("#doc3_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
  
              }else{

                if ( extrae_extencion(data.doc3_inicio_obra) == "pdf" ) {

                  $("#doc3_ver").html('<iframe src="../dist/pdf/'+data.doc3_inicio_obra+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

                }else{

                  $("#doc3_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                }
              }
            }
          }
        }
      }
    } else {

      $("#doc3_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');

      $("#do3_nombre").html('');

      $("#doc_old_3").val("");
    }

    //validamoos DOC-4
    if (data.doc4_presupuesto != "" ) {

      $("#doc_old_4").val(data.doc4_presupuesto);

      $("#doc4_nombre").html('Presupuesto.' + extrae_extencion(data.doc4_presupuesto));

      // $("#doc4_ver").html('<iframe src="../dist/pdf/'+data.doc4_presupuesto+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.doc4_presupuesto) == "xls") {

        $("#doc4_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

      } else {

        if ( extrae_extencion(data.doc4_presupuesto) == "xlsx" ) {

          $("#doc4_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

        }else{

          if ( extrae_extencion(data.doc4_presupuesto) == "csv" ) {

            $("#doc4_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

          }else{

            if ( extrae_extencion(data.doc4_presupuesto) == "xlsm" ) {

              $("#doc4_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

            }else{

              if ( extrae_extencion(data.doc4_presupuesto) == "doc" || extrae_extencion(data.doc4_presupuesto) == "docx" ) {

                $("#doc4_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
  
              }else{

                if ( extrae_extencion(data.doc4_presupuesto) == "pdf" ) {

                  $("#doc4_ver").html('<iframe src="../dist/pdf/'+data.doc4_presupuesto+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

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
    if (data.doc5_analisis_costos_unitarios != "" ) {

      $("#doc_old_5").val(data.doc5_analisis_costos_unitarios);

      $("#doc5_nombre").html('Analisis de costos unitarios.' + extrae_extencion(data.doc5_analisis_costos_unitarios));

      // $("#doc5_ver").html('<iframe src="../dist/pdf/'+data.doc5_analisis_costos_unitarios+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.doc5_analisis_costos_unitarios) == "xls") {

        $("#doc5_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

      } else {

        if ( extrae_extencion(data.doc5_analisis_costos_unitarios) == "xlsx" ) {

          $("#doc5_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

        }else{

          if ( extrae_extencion(data.doc5_analisis_costos_unitarios) == "csv" ) {

            $("#doc5_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

          }else{

            if ( extrae_extencion(data.doc5_analisis_costos_unitarios) == "xlsm" ) {

              $("#doc5_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

            }else{

              if ( extrae_extencion(data.doc5_analisis_costos_unitarios) == "doc" || extrae_extencion(data.doc5_analisis_costos_unitarios) == "docx" ) {

                $("#doc5_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
  
              }else{

                if ( extrae_extencion(data.doc5_analisis_costos_unitarios) == "pdf" ) {

                  $("#doc5_ver").html('<iframe src="../dist/pdf/'+data.doc5_analisis_costos_unitarios+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

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

    //validamoos DOC-6
    if (data.doc6_insumos != "" ) {

      $("#doc_old_6").val(data.doc6_insumos);

      $("#doc6_nombre").html('Insumos.' + extrae_extencion(data.doc6_insumos));

      // $("#doc5_ver").html('<iframe src="../dist/pdf/'+data.doc6_insumos+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.doc6_insumos) == "xls") {

        $("#doc6_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

      } else {

        if ( extrae_extencion(data.doc6_insumos) == "xlsx" ) {

          $("#doc6_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

        }else{

          if ( extrae_extencion(data.doc6_insumos) == "csv" ) {

            $("#doc6_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

          }else{

            if ( extrae_extencion(data.doc6_insumos) == "xlsm" ) {

              $("#doc6_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

            }else{

              if ( extrae_extencion(data.doc6_insumos) == "doc" || extrae_extencion(data.doc6_insumos) == "docx" ) {

                $("#doc6_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
  
              }else{

                if ( extrae_extencion(data.doc6_insumos) == "pdf" ) {

                  $("#doc6_ver").html('<iframe src="../dist/pdf/'+data.doc6_insumos+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

                }else{

                  $("#doc6_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                }
              }
            }
          }
        }
      }
    } else {

      $("#doc6_ver").html('<img src="../dist/svg/pdf_trasnparent_no.svg" alt="" width="50%" >');

      $("#doc6_nombre").html('');

      $("#doc_old_6").val("");
    }
     
  });
  $(".tooltip").hide();  
}

function mostrar_detalle(idproyecto) {

  $("#modal-ver-detalle").modal("show");

  $.post("../ajax/proyecto.php?op=mostrar", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $('#cargando-detalle-proyecto').html(''+
      '<div class="col-12">'+
        '<div class="card">'+
          '<div class="card-body  ">'+
            '<table class="table table-hover table-bordered">' +           
              '<tbody>'+
                '<tr data-widget="expandable-table" aria-expanded="false">'+
                  '<th>Empresa</th>'+
                  '<td>'+data.empresa+'</td>'+
                '</tr>'+
                '<tr data-widget="expandable-table" aria-expanded="false">'+
                  '<th>Documento</th>'+
                  '<td>'+data.tipo_documento+': '+data.numero_documento+'</td>'+
                '</tr>'+
                '<tr data-widget="expandable-table" aria-expanded="false">'+
                  '<th>Nombre de  proyecto</th>'+
                  '<td>'+data.nombre_proyecto +'</td>'+
                '</tr>'+
                '<tr data-widget="expandable-table" aria-expanded="false">'+
                  '<th>Ubicación</th>'+
                  '<td>'+data.ubicacion+'</td>'+
                '</tr>'+
                '<tr data-widget="expandable-table" aria-expanded="false">'+
                  '<th>Actividad del trabajo</th>'+
                  '<td>'+data.actividad_trabajo+'</td>'+
                '</tr>'+
                '<tr data-widget="expandable-table" aria-expanded="false">'+
                  '<th>Fecha inicio/fin</th>'+
                  '<td>'+data.fecha_inicio+' - ' + data.fecha_fin+'</td>'+
                '</tr>'+

                '<tr data-widget="expandable-table" aria-expanded="false">'+
                  '<th>Plazo</th>'+                                
                  '<td>'+data.plazo+'</td>'+
                '</tr>'+
                '<tr data-widget="expandable-table" aria-expanded="false">'+
                  '<th>Costo total</th>'+
                  '<td>'+data.costo+'</td>'+
                '</tr>'+
                '<tr data-widget="expandable-table" aria-expanded="false">'+
                  '<th>Empresa a cargo</th>'+
                  '<td>'+data.empresa_acargo+'</td>'+
                '</tr>'+
              '</tbody>'+
            '</table>'+         
          '</div>'+
        '</div>'+
      '</div>'+ 
    '');
    // data.doc1_contrato_obra 
    // data.doc2_entrega_terreno    
    // data.doc3_inicio_obra
     
  });

  $(".tooltip").hide();
}

function tablero() {   

  $.post("../ajax/proyecto.php?op=tablero-proyectos",  function (data, status) {

    data = JSON.parse(data);  //console.log(data);
    $("#cantidad_proyectos").html(data.cantidad_proyectos);

  });  

  $.post("../ajax/proyecto.php?op=tablero-proveedores",  function (data, status) {

    data = JSON.parse(data);  //console.log(data);
    $("#cantidad_proveedores").html(data.cantidad_proveedores);
  });

  $.post("../ajax/proyecto.php?op=tablero-trabjadores",  function (data, status) {

    data = JSON.parse(data);  //console.log(data);
    $("#cantidad_trabajadores").html(data.cantidad_trabajadores);
  });

  $.post("../ajax/proyecto.php?op=tablero-servicio",  function (data, status) {

    data = JSON.parse(data);  //console.log(data);
    $("#cantidad_servicios").html(data.cantidad_servicios);
  });
}

function abrir_proyecto(idproyecto,nombre_proyecto) {

  if ($( "#foo" ).hasClass('className')) {

    $( "#foo" ).removeClass( 'className');

  } else {
    
    $( "#foo" ).addClass( 'className');
  }

  if ( localStorage.getItem('nube_idproyecto') ) {

    $("#icon_folder_"+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder"></i>')

  }

  $("#icon_folder_"+idproyecto).html('<i class="fas fa-folder-open"></i>')

  localStorage.setItem('nube_idproyecto', idproyecto);

  localStorage.setItem('nube_nombre_proyecto', nombre_proyecto);

  
  // mostramos el nombre en el NAV
  $("#ver-proyecto").html('<i class="fas fa-tools"></i> Proyecto: ' +  nombre_proyecto);
  $("#ver-proyecto").show();
  $("#ver-otros-modulos").show();

  setTimeout(function() {
    $("#ver-otros-modulos-1").fadeOut(0);
  },0);

  setTimeout(function() {
    $("#ver-otros-modulos-2").fadeIn(150);
  },4);

  setTimeout(function() {
    $("#ver-otros-modulos-2").fadeOut(200);
  },400);

  setTimeout(function() {
    $("#ver-otros-modulos-1").fadeIn(400);
  },500);

  Swal.fire("Abierto!", "Proyecto abierto corrrectamente", "success");
  // tabla.ajax.reload();
  $(".tooltip").hide();
}

function ver_modal_docs_valorizaciones(idproyecto, documento) {

  console.log(idproyecto, extrae_extencion( documento));

  $('#verdoc7').html('<img src="../dist/svg/doc_uploads_no.svg" alt="" height="206" >');

  $('#idproyect').val(idproyecto);

  $('#doc_old_7').val(documento);

  $('#modal-agregar-valorizaciones').modal("show");

  if (documento == "") {

    $('#verdoc7').html('<img src="../dist/svg/doc_uploads_no.svg" alt="" height="206" >');

    $("#verdoc7_nombre").html("valorizaciones"+
      '<div class="col-md-12 row mt-2">'+
        '<div class="col-md-6">'+
          '<a class="btn btn-warning  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            '<i class="fas fa-download"></i>'+
          '</a>'+
          '</div>'+

          '<div class="col-md-6">'+
          '<a class="btn btn-info  btn-block" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" >'+
            'Ver completo <i class="fas fa-expand"></i>'+
          '</a>'+
        '</div>'+
      '</div>'+
    '');
  } else {
    var nombredocs = "";
    
    if (extrae_extencion( documento) == "xls") {

      nombredocs = "valorizaciones.xls";     $('#verdoc7').html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

    } else {

      if (extrae_extencion( documento) == "xlsx") {

        nombredocs = "valorizaciones.xlsx";  $('#verdoc7').html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

      } else {

        if (extrae_extencion( documento) == "xlsx") {

          nombredocs = "valorizaciones.xlsx";  $('#verdoc7').html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
  
        } else {

          if (extrae_extencion( documento) == "xlsm") {

            nombredocs = "valorizaciones.xlsm";  $('#verdoc7').html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
    
          } else {
            nombredocs = "valorizaciones";  $('#verdoc7').html('<img src="../dist/svg/logo-excel.svg" alt="" width="50%" >');
          }
        }
      }      
    }       

    $("#verdoc7_nombre").html(nombredocs +
      '<div class="col-md-12 row mt-2">'+
          '<div class="col-md-6 ">'+
            '<a  class="btn btn-warning  btn-block" href="../dist/pdf/'+documento+'"  download="Valorizaciones" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" >'+
              '<i class="fas fa-download"></i>'+
            '</a>'+
          '</div>'+
          '<div class="col-md-6 ">'+
            '<button  class="btn btn-info  btn-block " href="../dist/pdf/'+documento+'" disabled  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" >'+
              'Ver completo <i class="fas fa-expand"></i>'+
            '</button>'+
          '</div>'+
      '</div>'+
    '');
  }
}

function extrae_extencion(filename) {
  return filename.split('.').pop();
}
  