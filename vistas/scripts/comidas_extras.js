var tabla;

//Función que se ejecuta al inicio
function init() {

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Viaticos").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mViatico").addClass("active bg-primary");

  $("#sub_bloc_comidas").addClass("menu-open");

  $("#sub_mComidas").addClass("active bg-primary");

  $("#lComidasExtras").addClass("active");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar();  

  $("#guardar_registro").on("click", function (e) {$("#submit-form-comidas-ex").submit();});

  //Initialize Select2 Elements
  $("#tipo_comprobante").select2({
    theme: "bootstrap4",
    placeholder: "Selecione tipo comprobante",
    allowClear: true,
  });

  //Initialize Select2 Elements
  $("#forma_pago").select2({
    theme: "bootstrap4",
    placeholder: "Selecione forma de pago",
    allowClear: true,
  });

  // Formato para telefono
  $("[data-mask]").inputmask();
}


// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addDocs(e,$("#doc1").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

//Función limpiar
function limpiar() {

 // idcomida_extra ,fecha_inicio,fecha_fin,cantidad,unidad,precio_unitario,precio_parcial,descripcion
  $("#idcomida_extra").val("");
  $("#fecha").val(""); 
  $("#precio_parcial").val("");  
  $(".precio_parcial").val("");
  
  $("#descripcion").val("");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change"); 
  $("#nro_comprobante").val("");
  
  $("#ruc").val("");
  $("#razon_social").val("");
  $("#direccion").val("");

  $(".subtotal").val("");
  $("#subtotal").val("");

  $(".igv").val("");
  $("#igv").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");
}

//Función Listar
function listar() {
  var idproyecto=localStorage.getItem('nube_idproyecto');
  tabla=$('#tabla-hospedaje').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/comidas_extras.php?op=listar&idproyecto='+idproyecto,
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
        if (data[1] != "") {
          $("td", row).eq(1).addClass("text-nowrap");
        }
        // columna: sub total
        if (data[5] != '') {
          $("td", row).eq(5).addClass('text-nowrap text-right');
        }
        // columna: igv
        if (data[6] != '') {
          $("td", row).eq(6).addClass('text-nowrap text-right');
        }
        // columna: total
        if (data[7] != '') {
          $("td", row).eq(7).addClass('text-nowrap text-right');
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
  total();
}
//ver ficha tecnica
function modal_comprobante(comprobante){
  limpiar();
  var comprobante = comprobante;

  var extencion = comprobante.substr(comprobante.length - 3); // => "1"
  //console.log(extencion);
  $('#ver_fact_pdf').html('');
  $('#img-factura').attr("src", "");
  $('#modal-ver-comprobante').modal("show");

  if (extencion=='jpeg' || extencion=='jpg' || extencion=='png' || extencion=='webp') {
    $('#ver_fact_pdf').hide();
    $('#img-factura').show();
    $('#img-factura').attr("src", "../dist/docs/comida_extra/comprobante/"+comprobante);

    $("#iddescargar").attr("href","../dist/docs/comida_extra/comprobante/"+comprobante);

  }else{
    $('#img-factura').hide();
    
    $('#ver_fact_pdf').show();

    $('#ver_fact_pdf').html('<iframe src="../dist/docs/comida_extra/comprobante/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');

    $("#iddescargar").attr("href","../dist/docs/comida_extra/comprobante/"+comprobante);
  }

}
//segun tipo de comprobante
function comprob_factura() {

  var monto = parseFloat($('.precio_parcial').val());
  $("#precio_parcial").val(monto);
 
  if ($("#tipo_comprobante").select2("val") =="Factura") {
    
      $(".nro_comprobante").html("Núm. Comprobante");

      $(".div_ruc").show(); $(".div_razon_social").show();

      var subtotal=0; var igv=0;

      $("#subtotal").val("");
      $("#igv").val(""); 

      subtotal= monto/1.18;
      igv= monto-subtotal;

      $(".subtotal").val(subtotal.toFixed(2));
      $("#subtotal").val(subtotal.toFixed(2));

      $(".igv").val(igv.toFixed(2));
      $("#igv").val(igv.toFixed(2));

  } else {

    if ($("#tipo_comprobante").select2("val") =="Boleta") {
            
      $(".nro_comprobante").html("Núm. Comprobante");

      $(".div_ruc").show(); $(".div_razon_social").show();

      $(".subtotal").val(monto.toFixed(2));
      $("#subtotal").val(monto);

      $(".igv").val("0.00");
      $("#igv").val("0.00");

    }else{

      if ($("#tipo_comprobante").select2("val") =="Ninguno") {
            
        $(".nro_comprobante").html("Núm. de Operación");

        $(".div_ruc").hide(); $(".div_razon_social").hide();
        
        $(".subtotal").val(monto.toFixed(2));
        $("#subtotal").val(monto);

        $(".igv").val("0.00");
        $("#igv").val("0.00");

      } else {

        $(".nro_comprobante").html("Núm. Comprobante");

        $(".div_ruc").hide(); $(".div_razon_social").hide();
                
        $(".subtotal").val(monto.toFixed(2));
        $("#subtotal").val(monto);

        $(".igv").val("0.00");
        $("#igv").val("0.00");
        
      }

    }
  }
  
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-comidas_ex")[0]);
 
  $.ajax({
    url: "../ajax/comidas_extras.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-comidas_ex").modal("hide");
        total();

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idcomida_extra ) {
  limpiar();
  //$("#proveedor").val("").trigger("change"); 
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-comidas_ex").modal("show")
  $("#tipo_comprobante").val("").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/comidas_extras.php?op=mostrar", { idcomida_extra : idcomida_extra  }, function (data, status) {

    data = JSON.parse(data); var precio_p=0;  console.log(data);  
    precio_p=parseFloat(data.costo_parcial);
   
    console.log( typeof (precio_p));
    console.log('precio_p '+precio_p);
    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();


    $("#idcomida_extra").val(data.idcomida_extra);  

    $("#tipo_comprobante").val(data.tipo_comprobante).trigger("change");
    $("#forma_pago").val(data.forma_de_pago).trigger("change");
    $("#nro_comprobante").val(data.numero_comprobante);
    $("#fecha").val(data.fecha_comida);

    $("#ruc").val(data.ruc);
    $("#razon_social").val(data.razon_social);
    $("#direccion").val(data.direccion);

    $(".precio_parcial").val(precio_p.toFixed(2));
    $("#precio_parcial").val(precio_p);

    $("#descripcion").val(data.descripcion);
  
    $(".subtotal").val(parseFloat(data.subtotal).toFixed(2));
    $("#subtotal").val(parseFloat(data.subtotal).toFixed(2));

    $(".igv").val(parseFloat(data.igv).toFixed(2));
    $("#igv").val(data.igv);
    /**-------------------------*/
  
    if (data.comprobante == "" || data.comprobante == null  ) {

      $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  
      $("#doc1_nombre").html('');
  
      $("#doc_old_1").val(""); $("#doc1").val("");
  
    } else {
  
      $("#doc_old_1").val(data.comprobante); 
  
      $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(data.comprobante)}</i></div></div>`);
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.comprobante) == "pdf" ) {
  
        $("#doc1_ver").html('<iframe src="../dist/docs/comida_extra/comprobante/'+data.comprobante+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');
  
      }else{
        if (
          extrae_extencion(data.comprobante) == "jpeg" || extrae_extencion(data.comprobante) == "jpg" || extrae_extencion(data.comprobante) == "jpe" ||
          extrae_extencion(data.comprobante) == "jfif" || extrae_extencion(data.comprobante) == "gif" || extrae_extencion(data.comprobante) == "png" ||
          extrae_extencion(data.comprobante) == "tiff" || extrae_extencion(data.comprobante) == "tif" || extrae_extencion(data.comprobante) == "webp" ||
          extrae_extencion(data.comprobante) == "bmp" || extrae_extencion(data.comprobante) == "svg" ) {
  
          $("#doc1_ver").html(`<img src="../dist/docs/comida_extra/comprobante/${data.comprobante}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
    }
  

  });
}

function total() {
  var idproyecto=localStorage.getItem('nube_idproyecto');
  $("#total_monto").html("");
  $.post("../ajax/comidas_extras.php?op=total", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#total_monto").html('S/. '+ formato_miles(data.precio_parcial));
  });
}


//Función para desactivar registros
function desactivar(idcomida_extra ) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar el registro?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/comidas_extras.php?op=desactivar", { idcomida_extra : idcomida_extra  }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla.ajax.reload();
        total();
      });      
    }
  });   
}

//Función para activar registros
function activar(idcomida_extra ) {
  Swal.fire({
    title: "¿Está Seguro de  Activar el registro?",
    text: "Este proveedor tendra acceso al sistema",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/comidas_extras.php?op=activar", { idcomida_extra : idcomida_extra  }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla.ajax.reload();
        total();
      });
      
    }
  });      
}

//Función para desactivar registros
function eliminar(idcomida_extra ) {
 
  Swal.fire({

    title: "!Elija una opción¡",
    html: "En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!",
    icon: "warning",
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonColor: "#17a2b8",
    denyButtonColor: "#d33",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `<i class="fas fa-times"></i> Papelera`,
    denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,

  }).then((result) => {

    if (result.isConfirmed) {

    //Desactivar
    $.post("../ajax/comidas_extras.php?op=desactivar", { idcomida_extra : idcomida_extra  }, function (e) {

      Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");

      tabla.ajax.reload();
      total();
    }); 

    }else if (result.isDenied) {

      // Eliminar
      $.post("../ajax/comidas_extras.php?op=eliminar", { idcomida_extra : idcomida_extra  }, function (e) {

        Swal.fire("Eliminado!", "Tu registro ha sido Eliminado.", "success");
    
        tabla.ajax.reload();
        total();
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

  // Aplicando la validacion del select cada vez que cambie
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });

  $("#form-comidas_ex").validate({
    rules: {
      forma_pago: { required: true },
      tipo_comprobante: { required: true },
      fecha: { required: true },
      precio_parcial:{required: true},
      descripcion:{required: true}
      // terms: { required: true },
    },
    messages: {
      forma_pago: {
        required: "Por favor seleccionar una forma de pago", 
      },
      tipo_comprobante: {
        required: "Por favor seleccionar tipo comprobante", 
      },
      fecha: {
        required: "Por favor ingrese una fecha", 
      },
      precio_parcial:  {
        required: "Ingresar precio unitario", 
      },
      descripcion:  {
        required: "Es necesario rellenar el campo descripción", 
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
  
  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });

});

function extrae_extencion(filename) {
  return filename.split('.').pop();
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
document.getElementById("fecha").setAttribute("max", today);


function formato_miles(num) {
  if (!num || num == 'NaN') return '-';
  if (num == 'Infinity') return '&#x221e;';
  num = num.toString().replace(/\$|\,/g, '');
  if (isNaN(num))
      num = "0";
  sign = (num == (num = Math.abs(num)));
  num = Math.floor(num * 100 + 0.50000000001);
  cents = num % 100;
  num = Math.floor(num / 100).toString();
  if (cents < 10)
      cents = "0" + cents;
  for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
      num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
  return (((sign) ? '' : '-') + num + '.' + cents);
}

/* PREVISUALIZAR LOS DOCUMENTOS */
function addDocs(e,id) {

  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');	console.log(id);

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

      $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >'); 

		}else{

			if (sizekiloBytes <= 40960) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;

          // cargamos la imagen adecuada par el archivo
				  if ( extrae_extencion(file.name) == "doc") {
            $("#"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
          } else {
            if ( extrae_extencion(file.name) == "docx" ) {
              $("#"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
            }else{
              if ( extrae_extencion(file.name) == "pdf" ) {
                $("#"+id+"_ver").html(`<iframe src="${result}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
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
                        if (
                          extrae_extencion(file.name) == "jpeg" || extrae_extencion(file.name) == "jpg" || extrae_extencion(file.name) == "jpe" ||
                          extrae_extencion(file.name) == "jfif" || extrae_extencion(file.name) == "gif" || extrae_extencion(file.name) == "png" ||
                          extrae_extencion(file.name) == "tiff" || extrae_extencion(file.name) == "tif" || extrae_extencion(file.name) == "webp" ||
                          extrae_extencion(file.name) == "bmp" || extrae_extencion(file.name) == "svg" ) {

                          $("#"+id+"_ver").html(`<img src="${result}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
                          
                        } else {
                          $("#"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                        }
                        
                      }
                    }
                  }
                }
              }
            }
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

        $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
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
    })
		 
    $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
		$("#"+id+"_nombre").html("");
    $("#"+id).val("");
	}	
}

// recargar un doc para ver
function re_visualizacion(id, carpeta) {

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
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/comida_extra/${carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
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
  
                      $("#doc"+id+"_ver").html(`<img src="../dist/docs/comida_extra/${carpeta}/${antiguopdf}" alt="" onerror="this.src='../dist/svg/error-404-x.svg';" width="100%" >`);
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

// Buscar  SUNAT
function buscar_sunat() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_comprobante").val();

  let ruc = $("#ruc").val(); 
   
  if (tipo_doc == "Factura" || tipo_doc == "Boleta" ) {

    if (ruc.length == "11") {
      $.post("../ajax/persona.php?op=sunat", { ruc: ruc }, function (data, status) {

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

              data.razonSocial == null ? $("#razon_social").val(data.nombreComercial) : $("#razon_social").val(data.razonSocial);

              var departamento = (data.departamento == null ? "" : data.departamento); 
              var provincia = (data.provincia == null ? "" : data.provincia);
              var distrito = (data.distrito == null ? "" : data.distrito);                

              data.direccion == null ? $("#direccion").val(`${departamento} - ${provincia} - ${distrito}`) : $("#direccion").val(data.direccion);

              toastr.success("Razón social encontrado!!");

            } else {

              toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.razonSocial == null ? $("#nombre").val(data.nombreComercial) : $("#nombre").val(data.razonSocial);

              data.razonSocial == null ? $("#razon_social").val(data.nombreComercial) : $("#razon_social").val(data.razonSocial);
              
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

      $("#search").show();

      $("#charge").hide();

      toastr.error("Asegúrese que el tipo de comprobante sea Factura!!");

  }
  
}



