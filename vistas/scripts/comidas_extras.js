var tabla;

//Función que se ejecuta al inicio
function init() {

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Viaticos").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mViatico").addClass("active bg-primary");

  $("#sub_bloc_comidas").addClass("menu-open bg-color-191f24");

  $("#sub_mComidas").addClass("active bg-primary");

  $("#lComidasExtras").addClass("active");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar();  

  $("#guardar_registro").on("click", function (e) {$("#submit-form-comidas-ex").submit();});

  //Initialize Select2 Elements
  $("#tipo_comprobante").select2({  theme: "bootstrap4", placeholder: "Selecione tipo comprobante", allowClear: true, });
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Selecione forma de pago", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();
}


// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

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
  
  $("#descripcion").val("");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change"); 
  $("#nro_comprobante").val("");
  
  $("#num_documento").val("");
  $("#razon_social").val("");
  $("#direccion").val("");

  $("#subtotal").val("");

  $("#igv").val("");
  //$("#val_igv").val(""); 
  $("#tipo_gravada").val("");  

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar() {

  $("#total_monto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  var idproyecto=localStorage.getItem('nube_idproyecto');
  
  tabla=$('#tabla-comidas_extras').dataTable({
    "responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/comidas_extras.php?op=listar&idproyecto='+idproyecto,
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	ver_errores(e);
        }
      },
      createdRow: function (row, data, ixdex) {
        // columna: #
        if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
        // columna: 1
        if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap"); }
        // columna: sub total
        if (data[5] != '') { $("td", row).eq(5).addClass('text-nowrap text-right'); }
        // columna: igv
        if (data[6] != '') { $("td", row).eq(6).addClass('text-nowrap text-right'); }
        // columna: total
        if (data[7] != '') { $("td", row).eq(7).addClass('text-nowrap text-right'); }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
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

  var monto = 0;

  if ($('#precio_parcial').val()=='' || $('#precio_parcial').val()==null) { monto=0;  } else { monto = parseFloat($('#precio_parcial').val());   }

  if ($("#tipo_comprobante").select2("val") =="" || $("#tipo_comprobante").select2("val") ==null) {

    $("#subtotal").val("");
    $("#igv").val(""); 
    $("#val_igv").val("0"); 
    $("#tipo_gravada").val(""); 
    $("#val_igv").prop("readonly",true);

  } else {
  
    if ($("#tipo_comprobante").select2("val") =="Factura") {
      
        $(".nro_comprobante").html("Núm. Comprobante");

        $(".div_ruc").show(); $(".div_razon_social").show();

        $("#tipo_gravada").val("GRAVADA"); 
        calculandototales_fact();

    } else {


      if ($("#tipo_comprobante").select2("val") =="Boleta") {
              
        $(".nro_comprobante").html("Núm. Comprobante");

        $(".div_ruc").show(); $(".div_razon_social").show();

        $("#subtotal").val(monto.toFixed(2));

        $("#igv").val("0.00");
        $("#val_igv").val("0"); 
        $("#tipo_gravada").val("NO GRAVADA"); 
        $("#val_igv").prop("readonly",true);

      }else{

        if ($("#tipo_comprobante").select2("val") =="Ninguno") {
              
          $(".nro_comprobante").html("Núm. de Operación");

          $(".div_ruc").hide(); $(".div_razon_social").hide();
          
          $("#subtotal").val(monto.toFixed(2));

          $("#igv").val("0.00");
          $("#val_igv").val("0"); 
          $("#tipo_gravada").val("NO GRAVADA"); 
          $("#val_igv").prop("readonly",true);

        } else {

          $(".nro_comprobante").html("Núm. Comprobante");

          $(".div_ruc").hide(); $(".div_razon_social").hide();
                  
          $("#subtotal").val(monto.toFixed(2));

          $("#igv").val("0.00");
          $("#val_igv").val("0"); 
          $("#tipo_gravada").val("NO GRAVADA"); 
          $("#val_igv").prop("readonly",true);
          
        }
      }
    }
  } 
}


function validando_igv() {

  if ($("#tipo_comprobante").select2("val") == "Factura") {

    $("#val_igv").prop("readonly",false);
    $("#val_igv").val(0.18); 

  }else {

    $("#val_igv").val(0); 

  }
  
}

function calculandototales_fact() {

  var precio_parcial =  $("#precio_parcial").val();

  var val_igv = $('#val_igv').val();

  if (precio_parcial == null || precio_parcial == "") {

    $("#subtotal").val(0);
    $("#igv").val(0); 

  } else {
 
    var subtotal = 0;
    var igv = 0;

    if (val_igv == null || val_igv == "") {

      $("#subtotal").val(parseFloat(precio_parcial));
      $("#igv").val(0);

    }else{

      $("subtotal").val("");
      $("#igv").val("");

      subtotal = quitar_igv_del_precio(precio_parcial, val_igv, 'decimal');
      igv = precio_parcial - subtotal;

      $("#subtotal").val(parseFloat(subtotal).toFixed(2));
      $("#igv").val(parseFloat(igv).toFixed(2));

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
	      tabla.ajax.reload(null, false);         
				limpiar();
        $("#modal-agregar-comidas_ex").modal("hide");
        total();
			}else{
				toastr.error(datos)
			}
      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});
          $("#barra_progress").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  });
      $("#barra_progress").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
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

    $("#num_documento").val(data.ruc);
    $("#razon_social").val(data.razon_social);
    $("#direccion").val(data.direccion);

    $("#precio_parcial").val(precio_p.toFixed(2));

    $("#descripcion").val(data.descripcion);
  
    $("#subtotal").val(parseFloat(data.subtotal).toFixed(2));

    $("#igv").val(parseFloat(data.igv).toFixed(2));

    $("#val_igv").val(data.val_igv);
    $("#tipo_gravada").val(data.tipo_gravada);
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
  $("#total_monto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  var idproyecto=localStorage.getItem('nube_idproyecto');
  $("#total_monto").html("");
  $.post("../ajax/comidas_extras.php?op=total", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#total_monto").html('S/ '+ formato_miles(data.precio_parcial));
   // $("#cargando").hide();
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
    
        tabla.ajax.reload(null, false);
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

        tabla.ajax.reload(null, false);
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

      tabla.ajax.reload(null, false);
      total();
    }); 

    }else if (result.isDenied) {

      // Eliminar
      $.post("../ajax/comidas_extras.php?op=eliminar", { idcomida_extra : idcomida_extra  }, function (e) {

        Swal.fire("Eliminado!", "Tu registro ha sido Eliminado.", "success");
    
        tabla.ajax.reload(null, false);
        total();
      }); 

    }

  }); 

}


init();

$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });

  $("#form-comidas_ex").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      forma_pago:       { required: true },
      tipo_comprobante: { required: true },
      fecha:            { required: true },
      precio_parcial:   {required: true},
      descripcion:      {required: true},
      val_igv:          { required: true, number: true, min:0, max:1 },
      // terms: { required: true },
    },
    messages: {

      forma_pago: { required: "Por favor seleccionar una forma de pago",},
      tipo_comprobante: { required: "Por favor seleccionar tipo comprobante", },
      fecha: { required: "Por favor ingrese una fecha", },
      precio_parcial:  { required: "Ingresar precio unitario", },
      descripcion:  { required: "Es necesario rellenar el campo descripción", },
      val_igv: { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
    },
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");   
    },
    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardaryeditar(e);      
    },

  });
  
  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });

});


// restringimos la fecha para no elegir mañana

no_select_tomorrow("#fecha")


