var tabla;

//Función que se ejecuta al inicio
function init() {
  listar();
  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  //Activamos el "aside"
  $("#mOtroServicio").addClass("active");

  $("#guardar_registro").on("click", function (e) {$("#submit-form-otro_servicio").submit();});

  //ficha tecnica
  $("#foto2_i").click(function() { $('#foto2').trigger('click'); });
  $("#foto2").change(function(e) { addficha(e,$("#foto2").attr("id")) });

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

  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");
  

  // Formato para telefono
  $("[data-mask]").inputmask();


}
/* PREVISUALIZAR LOS PDF */
function addficha(e,id) {
  // colocamos cargando hasta que se vizualice
  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

	console.log(id);

	var file = e.target.files[0], imageType = /application.*/;
	
	if (e.target.files[0]) {

		var sizeByte = file.size;

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (extrae_extencion(file.name)=='pdf' || extrae_extencion(file.name)=='jpeg'|| extrae_extencion(file.name)=='jpg'|| extrae_extencion(file.name)=='png'|| extrae_extencion(file.name)=='webp'){
      
			if (sizekiloBytes <= 10240) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;
          if (extrae_extencion(file.name) =='pdf') {
            $('#foto2_i').hide();
           $('#ver_pdf').html('<iframe src="'+result+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
          }else{
					$("#"+id+"_i").attr("src", result);
          $('#foto2_i').show();
          }

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

		}else{
      // return;
			toastr.error('Este tipo de ARCHIVO no esta permitido <br> elija formato: <b> .pdf .png .jpeg .jpg .webp etc... </b>');

      $("#"+id+"_i").attr("src", "../dist/img/default/pdf.png");

		}

	}else{

		toastr.error('Seleccione una Imagen');


      $("#"+id+"_i").attr("src", "../dist/img/default/pdf.png");
   
		$("#"+id+"_nombre").html("");
	}
}

function foto2_eliminar() {

	$("#foto2").val("");

	$("#ver_pdf").html("");

	$("#foto2_i").attr("src", "../dist/img/default/pdf.png");

	$("#foto2_nombre").html("");
  $('#foto2_i').show();
}

//Función limpiar
function limpiar() {

  $("#idplanilla_seguro").val("");
  $("#fecha_p_s").val("");

  $(".precio_parcial").val(""); 
  $("#precio_parcial").val(""); 

  $("#nro_comprobante").val("");

 /* $(".subtotal").val("");
  $("#subtotal").val("");

  $(".igv").val("");
  $("#igv").val("");*/

  $("#descripcion").val("");

  $("#foto2_i").attr("src", "../dist/img/default/pdf.png");
  $("#foto2").val("");
	$("#foto2_actual").val("");  
	$("#ver_pdf").val("");  
  $("#foto2_nombre").html("");
  $('#foto2_i').show();
  $('#ver_pdf').hide();

  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

}

//segun tipo de comprobante
function comprob_factura() {

  var precio_parcial = $(".precio_parcial").val();

  $("#precio_parcial").val(precio_parcial);

 // console.log('monto '+ monto +' cantidad '+ cantidad +' precio_unitario '+ precio_unitario);

 /*if ($("#tipo_comprobante").select2("val") =="Factura" && $("#cantidad").val()!='' && $("#precio_unitario").val()!='' ) {

    var subtotal=0; var igv=0;

    $("#subtotal").val("");
    $("#igv").val(""); 

    subtotal= monto/1.18;
    igv= monto-subtotal;

    $(".subtotal").val(subtotal.toFixed(2));
    $("#subtotal").val(subtotal.toFixed(4));

    $(".igv").val(igv.toFixed(2));
    $("#igv").val(igv.toFixed(4));

  } else {

    $(".subtotal").val(monto.toFixed(2));
    $("#subtotal").val(monto);

    $(".igv").val("0.00");
    $("#igv").val("0.00");
  }*/
  
  
}

//Función Listar
function listar() {
  var idproyecto=localStorage.getItem('nube_idproyecto');
  tabla=$('#tabla-otro_servicio').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/planillas_seguros.php?op=listar&idproyecto='+idproyecto,
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
  total();
}
//ver ficha tecnica
function modal_comprobante(comprobante){
  var comprobante = comprobante;
  console.log(comprobante);
var extencion = comprobante.substr(comprobante.length - 3); // => "1"
//console.log(extencion);
  $('#ver_fact_pdf').html('');
  $('#img-factura').attr("src", "");
  $('#modal-ver-comprobante').modal("show");

  if (extencion=='jpeg' || extencion=='jpg' || extencion=='png' || extencion=='webp') {
    $('#ver_fact_pdf').hide();
    $('#img-factura').show();
    $('#img-factura').attr("src", "../dist/img/comprob_planillas_seguros/"+comprobante);

    $("#iddescargar").attr("href","../dist/img/comprob_planillas_seguros/"+comprobante);

  }else{
    $('#img-factura').hide();
    
    $('#ver_fact_pdf').show();

    $('#ver_fact_pdf').html('<iframe src="../dist/img/comprob_planillas_seguros/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');

    $("#iddescargar").attr("href","../dist/img/comprob_planillas_seguros/"+comprobante);
  }


  
 // $(".tooltip").hide();
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-otro_servicio")[0]);
 
  $.ajax({
    url: "../ajax/planillas_seguros.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-otro_servicio").modal("hide");
        total();

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idplanilla_seguro) {
  limpiar();
  //$("#proveedor").val("").trigger("change"); 
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-otro_servicio").modal("show")
  
  $("#tipo_comprobante").val("").trigger("change"); 
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/planillas_seguros.php?op=mostrar", { idplanilla_seguro: idplanilla_seguro }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    precio_p=parseFloat(data.precio_parcial);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#tipo_comprobante").val(data.tipo_comprobante).trigger("change"); 
    $("#forma_pago").val(data.forma_de_pago).trigger("change");

    $("#idplanilla_seguro").val(data.idplanilla_seguro);
    $("#fecha_p_s").val(data.fecha_p_s); 
    $("#nro_comprobante").val(data.numero_comprobante);

    $("#precio_parcial").val(parseFloat(data.costo).toFixed(2)); 
    $(".precio_parcial").val(data.costo); 

    /*$(".precio_parcial").val(precio_p.toFixed(2));
    $("#precio_parcial").val(precio_p);
  
    $(".subtotal").val(parseFloat(data.subtotal).toFixed(2));
    $("#subtotal").val(parseFloat(data.subtotal));

    $(".igv").val(parseFloat(data.igv).toFixed(2));
    $("#igv").val(data.igv);*/
    $("#descripcion").val(data.descripcion);
    /**-------------------------*/
  if (data.comprobante != "") {
    $("#foto2_actual").val(data.comprobante);
    $('#ver_pdf').html('');
    $('#foto2_i').attr("src", "");

    $('#foto2_i').hide();
    $('#ver_pdf').show();
    $('#ver_pdf').html('<iframe src="../dist/img/comprob_planillas_seguros/'+data.comprobante+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
    
    $("#foto2_nombre").html(''+
    '<div class="row">'+
      '<div class="col-md-12">.</div>'+
      '<div class="col-md-12">'+
      '<button  class="btn btn-danger  btn-block" onclick="foto2_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>'+
      '</div>'+
    '</div>'+
  '');
  }else{
    $('#foto2_i').show();
    $('#ver_pdf').html('');
    $("#foto2_nombre").html('');
    $('#ver_pdf').hide();
  }
  });
}

function ver_datos(idplanilla_seguro) {

  $("#modal-ver-transporte").modal("show")

  $.post("../ajax/planillas_seguros.php?op=verdatos", { idplanilla_seguro: idplanilla_seguro }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 
    
    verdatos=`                                                                            
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-hover table-bordered">        
            <tbody>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Descripción</th>
                <td>${data.descripcion}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Tipo clasificación</th>
                <td>${data.tipo_viajero}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Ruta</th>
                <td>${data.ruta}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Tipo ruta</th>
                  <td>${data.tipo_ruta}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Fecha</th>
                <td>${data.fecha_p_s}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Tipo pago </th>
                <td>${data.forma_de_pago}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Tipo comprobante </th>
                <td>${data.tipo_comprobante}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Cantidad</th>
                <td>${data.cantidad}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Precio unitario</th>
                <td>${parseFloat(data.precio_unitario).toFixed(2)}</td>
              </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                <th>Subtotal</th>
                <td>${parseFloat(data.subtotal).toFixed(2)}</td>
              </tr>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>IGV</th>
                <td>${parseFloat(data.igv).toFixed(2)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Total</th>
                <td>${parseFloat(data.precio_parcial).toFixed(2)}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>`;
  
    $("#datostransporte").html(verdatos);

  });
}

function total() {
  var idproyecto=localStorage.getItem('nube_idproyecto');
  $("#total_monto").html("");
  $.post("../ajax/planillas_seguros.php?op=total", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#total_monto").html('S/. '+ formato_miles(data.precio_parcial));
  });
}


//Función para desactivar registros
function desactivar(idplanilla_seguro) {
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
      $.post("../ajax/planillas_seguros.php?op=desactivar", { idplanilla_seguro: idplanilla_seguro }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla.ajax.reload();
        total();
      });      
    }
  });   
}

//Función para activar registros
function activar(idplanilla_seguro) {
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
      $.post("../ajax/planillas_seguros.php?op=activar", { idplanilla_seguro: idplanilla_seguro }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

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
  
  $("#form-otro_servicio").validate({
    rules: {
      forma_pago: { required: true },
      tipo_comprobante: { required: true },
      fecha_p_s	: { required: true },
      cantidad:{required: true},
      precio_parcial:{required: true},
      descripcion:{required: true}
      // terms: { required: true },
    },
    messages: {
      forma_pago: {
        required: "Por favor una forma de pago", 
      },
      tipo_comprobante: {
        required: "Por favor seleccionar tipo comprobante", 
      },
      fecha_p_s	: {
        required: "Por favor ingrese una fecha", 
      },
      precio_parcial:  {
        required: "Ingresar precio", 
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
document.getElementById("fecha_p_s").setAttribute("max", today);


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


