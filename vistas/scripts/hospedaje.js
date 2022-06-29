var tabla;

//Función que se ejecuta al inicio
function init() {

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Viaticos").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mViatico").addClass("active bg-primary");

  $("#lHospedaje").addClass("active");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar();    

  $("#guardar_registro").on("click", function (e) {$("#submit-form-hospedaje").submit();});

  //Initialize Select2 unidad
  $("#unidad").select2({ theme: "bootstrap4", placeholder: "Seleccinar unidad", allowClear: true, });
  //Initialize Select2 unidad
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Seleccinar tipo comprobante", allowClear: true, });
  //Initialize Select2 unidad
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar una forma de pago", allowClear: true, });

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

function calculando_cantidad() {

  var fecha_inicio;
  var fecha_fin;
  var diferencia;

  if ($("#unidad").select2("val")!=null) {

    if ($("#unidad").select2("val")=='Día') {
         
      fecha_inicio = $("#fecha_inicio").val();  
      fecha_fin = $("#fecha_fin").val();  
      console.log('fecha_inicio '+fecha_inicio);
      console.log('fecha_fin '+fecha_fin);

      if (fecha_inicio!='' && fecha_fin!='' ) {

        fecha_inicio=fecha_inicio.replace("/","-");
        var fecha1 = moment(fecha_inicio);

        fecha_fin=fecha_fin.replace("/","-");
        var fecha2 = moment(fecha_fin);

        diferencia=fecha2.diff(fecha1, 'days');
        $("#cantidad").val(diferencia);
        $("#precio_parcial").val(diferencia*$("#precio_unitario").val());
      }

        //toastr.warning('Seleccionar una fecha.'); 
    }else{
      $("#cantidad").val("");
      $("#precio_parcial").val("");
    }

  }else{
    $("#cantidad").val("");
    $("#precio_parcial").val("");
  }
}

function habilitar_r_social(){

  if ($("#tipo_comprobante").select2("val") =="Factura" || $("#tipo_comprobante").select2("val") =="Boleta"){

    $(".nro_comprobante").html("Núm. Comprobante");

    $(".div_ruc").show(); $(".div_razon_social").show();

  }else{

    $(".nro_comprobante").html("Núm. de Operación");

    $(".div_ruc").hide(); $(".div_razon_social").hide();
  }

}

function calculando_totales() {

  var cantidad = $("#cantidad").val();

  var precio_unitario = $("#precio_unitario").val();

  $("#tipo_gravada").val("");

  var monto = cantidad*precio_unitario;

  $('#precio_parcial').val(monto);

  if ($("#tipo_comprobante").select2("val") =="" || $("#tipo_comprobante").select2("val") ==null) {

    $("#subtotal").val('0.0');

    $("#igv").val("0.00");

    $('#precio_parcial').val('0.00');
    $("#val_igv").val("0"); 
    $("#tipo_gravada").val("NO GRAVADA");  
    $("#val_igv").prop("readonly",true);

  } else {
    
    if ($("#tipo_comprobante").select2("val") =="Factura") {

      $("#tipo_gravada").val("GRAVADA"); 

      calculandototales_fact()

    }else{

      if ($("#tipo_comprobante").select2("val")!="Factura" && $("#precio_unitario").val()!='' && $("#unidad").select2("val") !="") {

        $("#subtotal").val(monto.toFixed(2));

        $("#igv").val("0.00");
    
        $('#precio_parcial').val(monto.toFixed(2));
        $("#val_igv").val("0"); 
        $("#tipo_gravada").val("NO GRAVADA");  
        $("#val_igv").prop("readonly",true);

      } else {

        $("#subtotal").val('0.0');

        $("#igv").val("0.00");
    
        $('#precio_parcial').val('0.00');
        $("#val_igv").val("0"); 
        $("#tipo_gravada").val("NO GRAVADA");  
        $("#val_igv").prop("readonly",true);
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


//Función limpiar
function limpiar() {

 // idhospedaje,fecha_inicio,fecha_fin,cantidad,unidad,precio_unitario,precio_parcial,descripcion
  $("#idhospedaje").val("");
  $("#fecha_inicio").val(""); 
  $("#fecha_fin").val(""); 
  $("#cantidad").val(""); 
 // $("#unidad").val(""); 
  $("#precio_unitario").val(""); 
  $("#descripcion").val("");

  $("#num_documento").val("");
  $("#razon_social").val("");
  $("#direccion").val("");

  $("#fecha_comprobante").val("");
  $("#nro_comprobante").val("");

  $("#precio_parcial").val("");

  $("#subtotal").val("");

  $("#igv").val("");
  
  $("#val_igv").val(""); 
  $("#tipo_gravada").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#unidad").val("null").trigger("change");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar() {
  var idproyecto=localStorage.getItem('nube_idproyecto');
  tabla=$('#tabla-hospedaje').dataTable({
    "responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
      url: '../ajax/hospedaje.php?op=listar&idproyecto='+idproyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	 ver_errores(e);
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
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
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
    $('#img-factura').attr("src", "../dist/docs/hospedaje/comprobante/"+comprobante);

    $("#iddescargar").attr("href","../dist/docs/hospedaje/comprobante/"+comprobante);

  }else{
    $('#img-factura').hide();
    
    $('#ver_fact_pdf').show();

    $('#ver_fact_pdf').html('<iframe src="../dist/docs/hospedaje/comprobante/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');

    $("#iddescargar").attr("href","../dist/docs/hospedaje/comprobante/"+comprobante);
  }
 $(".tooltip").removeClass("show").addClass("hidde");
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-hospedaje")[0]);
 
  $.ajax({
    url: "../ajax/hospedaje.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {             
      if (datos == 'ok') {
				toastr.success('Registrado correctamente')
	      tabla.ajax.reload(null, false);         
				limpiar();
        $("#modal-agregar-hospedaje").modal("hide");
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

function mostrar(idhospedaje) {
  limpiar();
  //$("#proveedor").val("").trigger("change"); 
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-hospedaje").modal("show")
    
  $("#unidad").val("").trigger("change"); 
  $("#tipo_comprobante").val("").trigger("change"); 
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/hospedaje.php?op=mostrar", { idhospedaje: idhospedaje }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();
    $("#unidad").val(data.unidad).trigger("change"); 
    $("#tipo_comprobante").val(data.tipo_comprobante).trigger("change"); 
    $("#forma_pago").val(data.forma_de_pago).trigger("change");
    $("#idhospedaje").val(data.idhospedaje);
    $("#fecha_inicio").val(data.fecha_inicio); 
    $("#fecha_fin").val(data.fecha_fin); 
    $("#cantidad").val(data.cantidad); 
    $("#precio_unitario").val(parseFloat(data.precio_unitario).toFixed(2)); 

    $("#fecha_comprobante").val(data.fecha_comprobante);
    $("#nro_comprobante").val(data.numero_comprobante);

    $("#num_documento").val(data.ruc);
    $("#razon_social").val(data.razon_social);
    $("#direccion").val(data.direccion);

    $("#precio_parcial").val(parseFloat(data.precio_parcial).toFixed(2)); 
  
    $("#subtotal").val(parseFloat(data.subtotal).toFixed(2));
 
    $("#igv").val(parseFloat(data.igv).toFixed(2));

    $("#val_igv").val(data.val_igv);
    $("#tipo_gravada").val(data.tipo_gravada);

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

      $("#doc1_ver").html('<iframe src="../dist/docs/hospedaje/comprobante/'+data.comprobante+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

    }else{
      if (
        extrae_extencion(data.comprobante) == "jpeg" || extrae_extencion(data.comprobante) == "jpg" || extrae_extencion(data.comprobante) == "jpe" ||
        extrae_extencion(data.comprobante) == "jfif" || extrae_extencion(data.comprobante) == "gif" || extrae_extencion(data.comprobante) == "png" ||
        extrae_extencion(data.comprobante) == "tiff" || extrae_extencion(data.comprobante) == "tif" || extrae_extencion(data.comprobante) == "webp" ||
        extrae_extencion(data.comprobante) == "bmp" || extrae_extencion(data.comprobante) == "svg" ) {

        $("#doc1_ver").html(`<img src="../dist/docs/hospedaje/comprobante/${data.comprobante}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
        
      } else {
        $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
      }        
    }      
  }

  });
}

function ver_datos(idhospedaje) {

  $("#modal-ver-hospedaje").modal("show")
  var comprobante=''; var btn_comprobante='';

  $.post("../ajax/hospedaje.php?op=verdatos", { idhospedaje: idhospedaje }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 

    
    if (data.comprobante != '') {

      if ( extrae_extencion(data.comprobante) == "pdf" ) {

        comprobante= `<iframe src="../dist/docs/hospedaje/comprobante/${data.comprobante}" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>`;

      }else{

        if (
          extrae_extencion(data.comprobante) == "jpeg" || extrae_extencion(data.comprobante) == "jpg" || extrae_extencion(data.comprobante) == "jpe" ||
          extrae_extencion(data.comprobante) == "jfif" || extrae_extencion(data.comprobante) == "gif" || extrae_extencion(data.comprobante) == "png" ||
          extrae_extencion(data.comprobante) == "tiff" || extrae_extencion(data.comprobante) == "tif" || extrae_extencion(data.comprobante) == "webp" ||
          extrae_extencion(data.comprobante) == "bmp" || extrae_extencion(data.comprobante) == "svg" ) {

            comprobante=`<img src="../dist/docs/hospedaje/comprobante/${data.comprobante}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`; 
          
        } else {
          comprobante=`<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >`;
        }  

      }

      btn_comprobante=``;
    
      btn_comprobante=`
      <div class="row">
        <div class="col-6"">
           <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/hospedaje/comprobante/${data.comprobante}"> <i class="fas fa-expand"></i></a>
        </div>
        <div class="col-6"">
           <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/hospedaje/comprobante/${data.comprobante}" download="comprobante_hospedaje"> <i class="fas fa-download"></i></a>
        </div>
      </div>`;


    } else {

      comprobante='Sin comprobante';
      btn_comprobante='';

    }
        
    verdatos=`                                                                            
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-hover table-bordered">        
            <tbody>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Proveedor</th>
                <td>${data.razon_social} <br>${data.ruc} </td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Descripción</th>
                <td>${data.descripcion}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Glosa</th>
                <td>${data.glosa}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Unidad</th>
                <td>${data.unidad}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Fecha inicial</th>
                <td>${data.fecha_inicio}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Fecha final</th>
                  <td>${data.fecha_fin}</td>
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
                <th>Tipo pago </th>
                <td>${data.forma_de_pago!="" || data.forma_de_pago==null ?data.forma_de_pago:''}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Tipo comprobante </th>
                <td>${data.tipo_comprobante}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Fecha comprobante</th>
                <td>${data.fecha_comprobante}</td>
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
              <tr data-widget="expandable-table" aria-expanded="false">
                <td colspan="2" > ${comprobante} <br> ${btn_comprobante} </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>`;
  
    $("#datoshospedaje").html(verdatos);

  });
}

function total() {
  var idproyecto=localStorage.getItem('nube_idproyecto');
  $("#total_monto").html("");
  $.post("../ajax/hospedaje.php?op=total", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#total_monto").html('S/ '+ formato_miles(data.precio_parcial));
  });
}


//Función para desactivar registros
function desactivar(idhospedaje) {
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
      $.post("../ajax/hospedaje.php?op=desactivar", { idhospedaje: idhospedaje }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla.ajax.reload(null, false);
        total();
      });      
    }
  });   
}

//Función para activar registros
function activar(idhospedaje) {
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
      $.post("../ajax/hospedaje.php?op=activar", { idhospedaje: idhospedaje }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla.ajax.reload(null, false);
        total();
      });
      
    }
  });      
}
//Función para Eliminar registros
function eliminar(idhospedaje) {

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
    $.post("../ajax/hospedaje.php?op=desactivar", { idhospedaje: idhospedaje }, function (e) {

      Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
  
      tabla.ajax.reload(null, false);
      total();
    });

    }else if (result.isDenied) {

      // Eliminar
      $.post("../ajax/hospedaje.php?op=eliminar", { idhospedaje: idhospedaje }, function (e) {

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

  $("#form-hospedaje").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      forma_pago:       { required: true },
      tipo_comprobante: { required: true },
      fecha_comprobante:{ required: true },
      fecha_inicio:     { required: true },
      cantidad:         {minlength: 1},
      precio_unitario:  {required: true},
      descripcion:      {required: true},
      unidad:           {required: true},
      val_igv:          { required: true, number: true, min:0, max:1 },
    },
    messages: {
      forma_pago:       { required: "Por favor seleccionar una forma de pago", },
      tipo_comprobante: { required: "Por favor seleccionar tipo comprobante", },
      fecha_comprobante:{ required: "Por favor ingrese una fecha", },
      fecha_inicio:     { required: "Por favor ingrese una fecha", },
      cantidad:         { minlength: "Cantidad.", },
      precio_unitario:  { required: "Ingresar precio unitario", },
      descripcion:      { required: "Es necesario rellenar el campo descripción", },
      unidad:           { required: "Seleccionar unidad", },
      val_igv:          { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
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
no_select_tomorrow("#fecha_inicio");

function restrigir_fecha_input() {  restrigir_fecha_ant("#fecha_fin",$("#fecha_inicio").val());}






