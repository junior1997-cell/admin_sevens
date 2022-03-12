var tabla;

//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#lOtraFactura").addClass("active bg-primary");

  listar();
  //Mostramos los proveedores
  $.post("../ajax/otra_factura.php?op=select2Proveedor", function (r) { $("#idproveedor").html(r); });

  $("#guardar_registro").on("click", function (e) { $("#submit-form-otras_facturas").submit(); });

  //Initialize Select2 proveedor
  $("#idproveedor").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar proveedor",
    allowClear: true,
  });

  //Initialize Select2 tipo_viajero
  $("#tipo_comprobante").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar tipo comprobante",
    allowClear: true,
  });

  //Initialize Select2 tipo_viajero
  $("#forma_pago").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar forma de pago",
    allowClear: true,
  });

  //Initialize Select2 glosa
  $("#glosa").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar glosa",
    allowClear: true,
  });

  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");
  $("#glosa").val("null").trigger("change");

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
  $("#idotra_factura").val("");
  $("#fecha_emision").val("");  
  $("#nro_comprobante").val("");
  $("#direccion").val("");
  $("#subtotal").val("");
  $("#igv").val("");
  $("#precio_parcial").val("");
  $("#descripcion").val("");
   $("#tipo_gravada").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");
  $("#glosa").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

//segun tipo de comprobante
function comprob_factura() {

  var precio_parcial = $("#precio_parcial").val();  

  if ($("#tipo_comprobante").select2("val") == "" || $("#tipo_comprobante").select2("val") == null) {

    $(".nro_comprobante").html("Núm. Comprobante");

    $("#val_igv").val(0);
    $("#val_igv").prop("readonly",true);

    if (precio_parcial == null || precio_parcial == "") {
      $("#subtotal").val(0);
      $("#igv").val(0); 
      $("#tipo_gravada").val('NO GRAVADA');   
    } else {
      $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
      $("#igv").val(0);    
      $("#tipo_gravada").val('NO GRAVADA');
    }   

  } else {

    if ($("#tipo_comprobante").select2("val") == "Ninguno") { 

      $("#val_igv").val(0);
      $("#val_igv").prop("readonly",true);

      if (precio_parcial == null || precio_parcial == "") {
        $("#subtotal").val(0);
        $("#igv").val(0);  
        $("#tipo_gravada").val('NO GRAVADA');  
      } else {
        $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
        $("#igv").val(0);  
        $("#tipo_gravada").val('NO GRAVADA');  
      }   

    } else {

      if ($("#tipo_comprobante").select2("val") == "Factura") {

        calculandototales_fact();   
    
      } else { 
        
        if ($("#tipo_comprobante").select2("val") == "Boleta") {

          $(".nro_comprobante").html("Núm. Comprobante");

          $("#val_igv").val(0);
          $("#val_igv").prop("readonly",true);

          if (precio_parcial == null || precio_parcial == "") {
            $("#subtotal").val(0);
            $("#igv").val(0); 
            $("#tipo_gravada").val('NO GRAVADA');   
          } else {
            var subtotal = 0;
            var igv = 0;
        
            $("#subtotal").val("");
            $("#igv").val("");

            $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
            $("#igv").val(0);   
            $("#tipo_gravada").val('NO GRAVADA');  
          } 
            
        } else {
                 
        $(".nro_comprobante").html("Núm. Comprobante");

        if (precio_parcial == null || precio_parcial == "") {
          $("#subtotal").val(0);
          $("#igv").val(0);    
        } else {
          $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
          $("#igv").val(0);    
        } 
          
        }

      }
    }
  } 
}
function validando_igv() {

  if ($("#tipo_comprobante").select2("val") == "Factura") {

    $("#val_igv").val(0.18); 

  }else {

    $("#val_igv").val(0); 

  }
  
}

function calculandototales_fact() {
          
  $(".nro_comprobante").html("Núm. Comprobante");

  precio_parcial=$("#precio_parcial").val();

  var val_igv = $('#val_igv').val();

  $("#val_igv").prop("readonly",false);

  if (precio_parcial == null || precio_parcial == "") {

    $("#subtotal").val(0);
    $("#igv").val(0); 

  } else {

    var subtotal = 0;
    var igv = 0;

    if (val_igv == null || val_igv == "") {
      
      $("#subtotal").val(precio_parcial);
      $("#igv").val(0);
      $("#tipo_gravada").val('NO GRAVADA');
    }else{

      $("#subtotal").val("");
      $("#igv").val("");

      subtotal = quitar_igv_del_precio(precio_parcial, val_igv, 'decimal');
       //precio_parcial /(parseFloat(val_igv)+1);
      igv = precio_parcial - subtotal;

      $("#subtotal").val(subtotal.toFixed(2));
      $("#igv").val(igv.toFixed(2));

      $("#tipo_gravada").val('GRAVADA');

    }

  }  

}

function quitar_igv_del_precio(precio , igv, tipo ) {
  console.log(precio , igv, tipo);
  var precio_sin_igv = 0;

  switch (tipo) {
    case 'decimal':

      if (parseFloat(precio) != NaN && igv > 0 && igv <= 1 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( ( parseFloat(igv) * 100 ) + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;

    case 'entero':

      if (parseFloat(precio) != NaN && igv > 0 && igv <= 100 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( parseFloat(igv)  + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;
  
    default:
      $(".val_igv").html('IGV (0%)');
      toastr.success('No has difinido un tipo de calculo de IGV.')
    break;
  } 
  
  return precio_sin_igv; 
}

//Función Listar
function listar() {
  tabla = $("#tabla-otras_facturas")
    .dataTable({
      responsive: true,
      lengthMenu: [
        [5, 10, 25, 75, 100, 200, -1],
        [5, 10, 25, 75, 100, 200, "Todos"],
      ], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
      ajax: {
        url: "../ajax/otra_factura.php?op=listar",
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
      },
      createdRow: function (row, data, ixdex) {
        // columna: #
        if (data[0] != "") {
          $("td", row).eq(0).addClass("text-center");
        }
        // columna: sub total
        if (data[1] != "") {
          $("td", row).eq(1).addClass("text-nowrap");
        }
        // columna: sub total
        if (data[5] != "") {
          $("td", row).eq(5).addClass("text-nowrap text-right");
        }
        // columna: igv
        if (data[6] != "") {
          $("td", row).eq(6).addClass("text-nowrap text-right");
        }
        // columna: total
        if (data[7] != "") {
          $("td", row).eq(7).addClass("text-nowrap text-right");
        }
      },
      language: {
        lengthMenu: "Mostrar : _MENU_ registros",
        buttons: {
          copyTitle: "Tabla Copiada",
          copySuccess: {
            _: "%d líneas copiadas",
            1: "1 línea copiada",
          },
        },
      },
      bDestroy: true,
      iDisplayLength: 10, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    })
    .DataTable();
  total();
}

//ver ficha tecnica
function modal_comprobante(comprobante) {

  var comprobante = comprobante;
   
  var extencion = comprobante.substr(comprobante.length - 3); // => "1"
  //console.log(extencion);
  $("#ver_fact_pdf").html("");
  $("#img-factura").attr("src", "");
  $("#modal-ver-comprobante").modal("show");

  if (comprobante == '' || comprobante == null) {
    $(".ver-comprobante").html(`<div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
      <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
      No hay un documento para mostrar
    </div>`);
  }else{

    if ( extrae_extencion(comprobante) == "jpeg" || extrae_extencion(comprobante) == "jpg" || extrae_extencion(comprobante) == "jpe" ||
      extrae_extencion(comprobante) == "jfif" || extrae_extencion(comprobante) == "gif" || extrae_extencion(comprobante) == "png" ||
      extrae_extencion(comprobante) == "tiff" || extrae_extencion(comprobante) == "tif" || extrae_extencion(comprobante) == "webp" ||
      extrae_extencion(comprobante) == "bmp" || extrae_extencion(comprobante) == "svg" ) {

      $(".ver-comprobante").html(`<div class="row text-center">                          
        <!-- Dowload -->
        <div class="col-md-6 text-center descargar" >
          <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/otra_factura/comprobante/${comprobante}" download="Comprobante"> <i class="fas fa-download"></i> Descargar. </a>
        </div>
        <!-- Ver grande -->
        <div class="col-md-6 text-center ver_completo" >
          <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/otra_factura/comprobante/${comprobante}" > <i class="fas fa-expand"></i> Ver completo. </a>
        </div>
      </div>

      <div class="text-center mt-4">
        <img src="../dist/docs/otra_factura/comprobante/${comprobante}" alt="" width="100%" >
      </div>
      <div class="text-center">Comprobante.${extrae_extencion(comprobante)}</div>`);
      
    } else { 

      if (extrae_extencion(comprobante) == "pdf") {

        $(".ver-comprobante").html(`<div class="row text-center">                          
        <!-- Dowload -->
        <div class="col-md-6 text-center descargar" >
          <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/otra_factura/comprobante/${comprobante}" download="Comprobante"> <i class="fas fa-download"></i> Descargar. </a>
        </div>
        <!-- Ver grande -->
        <div class="col-md-6 text-center ver_completo" >
          <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/otra_factura/comprobante/${comprobante}" > <i class="fas fa-expand"></i> Ver completo. </a>
        </div>
      </div>

      <div class="text-center mt-4">
        <iframe src="../dist/docs/otra_factura/comprobante/${comprobante}" frameborder="0" scrolling="no" width="100%" height="510"></iframe>        
      </div>
      <div class="text-center">Comprobante.${extrae_extencion(comprobante)}</div>`);

      } else {
        $(".ver-comprobante").html(`<div class="row text-center">                          
          <!-- Dowload -->
          <div class="col-md-6 text-center descargar">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/otra_factura/comprobante/${comprobante}" download="Comprobante"> <i class="fas fa-download"></i> Descargar. </a>
          </div>
          <!-- Ver grande -->
          <div class="col-md-6 text-center ver_completo">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/otra_factura/comprobante/${comprobante}" > <i class="fas fa-expand"></i> Ver completo. </a>
          </div>
        </div>

        <div class="text-center mt-4">
          <iframe src="../dist/svg/doc_si_extencion.svg" frameborder="0" scrolling="no" width="100%" height="510"></iframe>        
        </div>
        <div class="text-center">Comprobante.${extrae_extencion(comprobante)}</div>`);
      }      
    }
  } 

  // $(".tooltip").hide();
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-otras_facturas")[0]);

  $.ajax({
    url: "../ajax/otra_factura.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      if (datos == "ok") {

        Swal.fire("Éxito!", "El registro se guardo correctamente.", "success");

        tabla.ajax.reload();

        limpiar();

        $("#modal-agregar-otras_facturas").modal("hide");

        total();

      } else {
        toastr.error(datos);
      }
    },
  });
}

function mostrar(idotra_factura) {

  limpiar();
  
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-otras_facturas").modal("show");

  $("#tipo_comprobante").val("").trigger("change");
  $("#idproveedor").val("").trigger("change");

  $.post("../ajax/otra_factura.php?op=mostrar", { idotra_factura: idotra_factura }, function (data, status) {
    
    data = JSON.parse(data); console.log(data);    

    $("#tipo_comprobante").val(data.tipo_comprobante).trigger("change");
    $("#idproveedor").val(data.idproveedor).trigger("change");
    $("#forma_pago").val(data.forma_de_pago).trigger("change");
    $("#glosa").val(data.glosa).trigger("change");
    $("#idotra_factura").val(data.idotra_factura);
    $("#fecha_emision").val(data.fecha_emision);
    $("#nro_comprobante").val(data.numero_comprobante);  

    $("#subtotal").val(data.subtotal);
    $("#igv").val(data.igv);
    $("#val_igv").val(data.val_igv);
    $("#tipo_gravada").val(data.tipo_gravada);
    $("#precio_parcial").val(data.costo_parcial);
    $("#descripcion").val(data.descripcion);    

    if (data.comprobante == "" || data.comprobante == null  ) {

      $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

      $("#doc1_nombre").html('');

      $("#doc_old_1").val(""); $("#doc1").val("");

    } else {

      $("#doc_old_1").val(data.comprobante); 

      $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(data.comprobante)}</i></div></div>`);
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.comprobante) == "pdf" ) {

        $("#doc1_ver").html('<iframe src="../dist/docs/otra_factura/comprobante/'+data.comprobante+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

      }else{
        if (
          extrae_extencion(data.comprobante) == "jpeg" || extrae_extencion(data.comprobante) == "jpg" || extrae_extencion(data.comprobante) == "jpe" ||
          extrae_extencion(data.comprobante) == "jfif" || extrae_extencion(data.comprobante) == "gif" || extrae_extencion(data.comprobante) == "png" ||
          extrae_extencion(data.comprobante) == "tiff" || extrae_extencion(data.comprobante) == "tif" || extrae_extencion(data.comprobante) == "webp" ||
          extrae_extencion(data.comprobante) == "bmp" || extrae_extencion(data.comprobante) == "svg" ) {

          $("#doc1_ver").html(`<img src="../dist/docs/otra_factura/comprobante/${data.comprobante}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
    }

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();
  });
}

function total() {

  $("#total_monto").html("");

  $.post("../ajax/otra_factura.php?op=total", {}, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

    $("#total_monto").html("S/. " + formato_miles(data.precio_parcial));
  });
}

//Función para desactivar registros
function eliminar(idotra_factura) {
  
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
      $.post("../ajax/otra_factura.php?op=desactivar", { idotra_factura: idotra_factura }, function (e) {
        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");

        tabla.ajax.reload();
        total();
      });

    }else if (result.isDenied) {

      // Eliminar
      $.post("../ajax/otra_factura.php?op=eliminar", { idotra_factura: idotra_factura }, function (e) {
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

  $("#idproveedor").on("change", function () { $(this).trigger("blur"); });
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });

  $("#form-otras_facturas").validate({
    rules: {
      idproveedor: { required: true },
      forma_pago: { required: true },
      tipo_comprobante: { required: true },
      fecha_emision: { required: true },
      precio_parcial: { required: true },
      val_igv: { required: true, number: true, min:0, max:1 },
      // terms: { required: true },
    },
    messages: {
      idproveedor: { required: "Por favor seleccionar una proveedor", },
      forma_pago: { required: "Por favor una forma de pago", },
      tipo_comprobante: { required: "Por favor seleccionar tipo comprobante", },
      fecha_emision: {        required: "Por favor ingrese una fecha", },
      precio_parcial: { required: "Ingresar monto", }, 
      val_igv: { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
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
  $("#idproveedor").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


function extrae_extencion(filename) {
  return filename.split(".").pop();
}

// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {

  var format = "";

  if (fecha == '' || fecha == null || fecha == '0000-00-00') {
    format = "-";
  } else {
    let splits = fecha.split("-"); //console.log(splits);
    format = splits[2]+'-'+splits[1]+'-'+splits[0];
  } 

  return format;
}

// convierte de una fecha(aa-mm-dd): 23-12-2021 a una fecha(dd-mm-aa): 2021-12-23
function format_a_m_d(fecha) {

  var format = "";

  if (fecha == '' || fecha == null || fecha == '00-00-0000') {
    format = "-";
  } else {
    let splits = fecha.split("-"); //console.log(splits);
    format = splits[2]+'-'+splits[1]+'-'+splits[0];
  } 

  return format;
}

// restringimos la fecha para no elegir mañana
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth() + 1; //January is 0!
var yyyy = today.getFullYear();
if (dd < 10) { dd = "0" + dd; }
if (mm < 10) { mm = "0" + mm; }

today = yyyy + "-" + mm + "-" + dd;
document.getElementById("fecha_emision").setAttribute("max", today);

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
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/otra_factura/${carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
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
  
                      $("#doc"+id+"_ver").html(`<img src="../dist/docs/otra_factura/${carpeta}/${antiguopdf}" alt="" onerror="this.src='../dist/svg/error-404-x.svg';" width="100%" >`);
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
