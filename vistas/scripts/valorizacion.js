var tabla;

//Función que se ejecuta al inicio
function init() {

  ver_quincenas(localStorage.getItem('nube_idproyecto'));

  // $("#bloc_Recurso").addClass("menu-open");

  $("#mValorizacion").addClass("active");

  // $("#lAllTrabajador").addClass("active");

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

    data =JSON.parse(data); //console.log(data);    

    $('#lista_quincenas').html('');

    // VALIDAMOS LAS FECHAS DE QUINCENA
    if (data) {

      let aFecha = data.fecha_inicio.split('-'); 

      var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];    console.log(aFecha);
      
      var fecha_i = sumaFecha(0,fecha);

      var cal_quincena  =data.plazo/15; var i=0;  var cont=0;

      while (i <= cal_quincena) {

        cont = cont+1;
  
        var fecha_inicio = fecha_i;
        
        fecha = sumaFecha(14,fecha_inicio);
  
        // console.log(fecha_inicio+'-'+fecha);
        let fecha_i = ; let fecha_f = ;
        ver_fechas_init_end = "'"+fecha_inicio+"',"+"'"+fecha+"',"+"'"+i+"'";
  
        $('#lista_quincenas').append(' <button id="boton-'+ i +'" type="button" class="btn bg-gradient-info text-center" onclick="fecha_quincena('+ver_fechas_init_end+');"><i class="far fa-calendar-alt"></i> Quincena '+cont+'<br>'+fecha_inicio+' - '+fecha+'</button>')
        
        fecha_i = sumaFecha(1,fecha);
  
        i++;
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
	// console.log(id);

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

                    // $("#"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                    $("#"+id+"_ver").html('<iframe src="'+result+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
    
                  }else{
    
                    $("#"+id+"_ver").html('<img src="../dist/svg/logo-excel.svg" alt="" width="50%" >');
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
 
  $("#c_bancaria").val("");  
  $("#banco").val("").trigger("change");
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

  console.log('id_verdatos'+idtrabajador);  
  
  $('#datostrabajador').html(''+
  '<div class="row" >'+
    '<div class="col-lg-12 text-center">'+
      '<i class="fas fa-spinner fa-pulse fa-6x"></i><br />'+
      '<br />'+
      '<h4>Cargando...</h4>'+
    '</div>'+
  '</div>');

  var verdatos=''; var imagenver='';

  $("#modal-ver-trabajador").modal("show")

  $.post("../ajax/all_trabajador.php?op=verdatos", { idtrabajador: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 

    var imagen_perfil =data.imagen_perfil != '' ? '<img src="../dist/img/usuarios/'+data.imagen_perfil+'" alt="" class="img-thumbnail">' : '<img src="../dist/svg/user_default.svg" alt="" style="width: 90px;">';
    var imagen_dni_anverso =data.imagen_dni_anverso != '' ? '<img src="../dist/img/usuarios/'+data.imagen_dni_anverso+'" alt="" class="img-thumbnail">' : 'No hay imagen';
    var imagen_dni_reverso =data.imagen_dni_reverso != '' ? '<img src="../dist/img/usuarios/'+data.imagen_dni_reverso+'" alt="" class="img-thumbnail">' : 'No hay imagen';
    
    verdatos=''+                                                                            
    '<div class="col-12">'+
      '<div class="card">'+
          '<div class="card-body ">'+
              '<table class="table table-hover table-bordered">'+          
                  '<tbody>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th rowspan="2">'+imagen_perfil+'</th>'+
                          '<td> <b>Nombre: </b> '+data.nombres+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<td> <b>DNI: </b>  '+data.numero_documento+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Dirección</th>'+
                          '<td>'+data.direccion+'</td>'+ 
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Correo</th>'+
                          '<td>'+data.email+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Teléfono</th>'+
                          '<td>'+data.telefono+'</td>'+ 
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Fecha nacimiento</th>'+
                          '<td>'+data.fecha_nacimiento+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Cuenta bancaria</th>'+
                          '<td>'+data.cuenta_bancaria+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Banco</th>'+
                          '<td>'+data.banco+'</td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>Titular cuenta </th>'+
                          '<td>'+data.titular_cuenta+'</td>'+
                      '</tr>'+

                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>DNI anverso</th>'+
                          '<td> '+imagen_dni_anverso+' </td>'+
                      '</tr>'+
                      '<tr data-widget="expandable-table" aria-expanded="false">'+
                          '<th>DNI reverso</th>'+
                          '<td> '+imagen_dni_reverso+' </td>'+
                      '</tr>'+
                  '</tbody>'+
              '</table>'+
          '</div>'+
      '</div>'+
    '</div>';
  
    $("#datostrabajador").html(verdatos);

  });
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
      c_bancaria: { minlength: 14, maxlength: 14},
      banco: { required: true},

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
        minlength: "El número documento debe tener 14 caracteres.",
        maxlength: "El número documento debe tener maximo 14 caracteres.",
      },
      banco: {
        required: "Este campo es requerido",
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

//Función para desactivar registros
function subir_doc(idtrabajador) {
  $("#modal-agregar-valorizacion").modal('show'); 
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

  let nube_idproyecto = localStorage.getItem('nube_idproyecto');

  $('#tab-seleccione').show(); $('#tab-contenido').show(); $('#tab-info').hide();

  $("#fecha_quincena").val(fecha_i);  console.log(fecha_i, fecha_f, i);

  // validamos el id para puntar el boton
  if (localStorage.getItem('boton_id')) {

    let id = localStorage.getItem('boton_id'); console.log('id-nube-boton'+id); 
    
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

    // validamos la data total
    if (data) {

      // validamos la data1
      if (data.data1.length === 0) {
        console.log('data no existe');
      } else {
        console.log('data existe');
      }

      // validamos la data2
      if (data.data2.length === 0) {
        console.log('data no existe');
      } else {
        console.log('data existe');
      }
    } else {
      
    }
    // $('#lista_quincenas').html('');

  });
}

function add_data_form(nombredoc) {
  $("#nombre").val(nombredoc);
  console.log(nombredoc);

  
}