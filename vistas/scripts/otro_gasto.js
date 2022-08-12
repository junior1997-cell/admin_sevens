var tabla;

//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lOtroGasto").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  listar();

  $("#guardar_registro").on("click", function (e) { $("#submit-form-otro_gasto").submit(); });

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
  $("#glosa").val("null").trigger("change");
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
  $("#idotro_gasto").val("");
  $("#fecha_g").val("");  
  $("#nro_comprobante").val("");
  $("#num_documento").val("");
  $("#razon_social").val("");
  $("#direccion").val("");
  $("#subtotal").val("");
  $("#igv").val("");
  $("#precio_parcial").val("");
  $("#descripcion").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");
  $("#glosa").val("null").trigger("change");

  $("#val_igv").val(""); 
  $("#tipo_gravada").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function calc_total() {

  $(".nro_comprobante").html("Núm. Comprobante");

  var total         = es_numero($('#precio_parcial').val()) == true? parseFloat($('#precio_parcial').val()) : 0;
  var val_igv       = es_numero($('#val_igv').val()) == true? parseFloat($('#val_igv').val()) : 0;
  var subtotal      = 0; 
  var igv           = 0;

  console.log(total, val_igv); console.log($('#precio_parcial').val(), $('#val_igv').val()); console.log('----------');

  if ($("#tipo_comprobante").select2("val")=="" || $("#tipo_comprobante").select2("val")==null) {
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);

    $(".div_ruc").hide(); $(".div_razon_social").hide();
    $("#num_documento").val(""); $("#razon_social").val("");
    
  }else if ($("#tipo_comprobante").select2("val") =="Ninguno") {  
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".nro_comprobante").html("Núm. de Operación");

    $(".div_ruc").hide(); $(".div_razon_social").hide();
    $("#num_documento").val(""); $("#razon_social").val("");

  }else if ($("#tipo_comprobante").select2("val") =="Factura") {  

    $("#val_igv").prop("readonly",false);    

    if (total == null || total == "") {
      $("#subtotal").val(0.00);
      $("#igv").val(0.00); 
      $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
    } else if (val_igv == null || val_igv == "") {  
      $("#subtotal").val(redondearExp(total));
      $("#igv").val(0.00);
      $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
    }else{     

      subtotal = quitar_igv_del_precio(total, val_igv, 'decimal');
      igv = total - subtotal;

      $("#subtotal").val(redondearExp(subtotal));
      $("#igv").val(redondearExp(igv));

      if (val_igv > 0 && val_igv <= 1) {
        $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)")
      } else {
        $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
      }    
    }
    $(".div_ruc").show(); $(".div_razon_social").show();

  } else {
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00");
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)");
    $("#val_igv").prop("readonly",true);
    $(".div_ruc").show(); $(".div_razon_social").show();
  }
  if (val_igv > 0 && val_igv <= 1) {
    $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)")
  } else {
    $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
  }
}

function select_comprobante() {
  if ($("#tipo_comprobante").select2("val") == "Factura") {
    $("#val_igv").prop("readonly",false);
    $("#val_igv").val(0.18); 
    $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)");
  }else {
    $("#val_igv").val(0.00); 
    $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
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
  var idproyecto = localStorage.getItem("nube_idproyecto");
  tabla = $("#tabla-otro_gasto")
    .dataTable({
      responsive: true,
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
      ajax: {
        url: "../ajax/otro_gasto.php?op=listar&idproyecto=" + idproyecto,
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
      },
      createdRow: function (row, data, ixdex) {
        // columna: #
        if (data[0] != "") { $("td", row).eq(0).addClass("text-center"); }
        // columna: sub total
        if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap");  }
        // columna: sub total
        if (data[5] != "") { $("td", row).eq(5).addClass("text-nowrap text-right"); }
        // columna: igv
        if (data[6] != "") { $("td", row).eq(6).addClass("text-nowrap text-right"); }
        // columna: total
        if (data[7] != "") { $("td", row).eq(7).addClass("text-nowrap text-right"); }
      },
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
      bDestroy: true,
      iDisplayLength: 10, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
      columnDefs: [
        { targets: [4], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
        //{ targets: [8,11],  visible: false,  searchable: false,  },
      ],
    }).DataTable();
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
          <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/otro_gasto/comprobante/${comprobante}" download="Comprobante"> <i class="fas fa-download"></i> Descargar. </a>
        </div>
        <!-- Ver grande -->
        <div class="col-md-6 text-center ver_completo" >
          <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/otro_gasto/comprobante/${comprobante}" > <i class="fas fa-expand"></i> Ver completo. </a>
        </div>
      </div>

      <div class="text-center mt-4">
        <img src="../dist/docs/otro_gasto/comprobante/${comprobante}" alt="" width="100%" >
      </div>
      <div class="text-center">Comprobante.${extrae_extencion(comprobante)}</div>`);
      
    } else { 

      if (extrae_extencion(comprobante) == "pdf") {

        $(".ver-comprobante").html(`<div class="row text-center">                          
        <!-- Dowload -->
        <div class="col-md-6 text-center descargar" >
          <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/otro_gasto/comprobante/${comprobante}" download="Comprobante"> <i class="fas fa-download"></i> Descargar. </a>
        </div>
        <!-- Ver grande -->
        <div class="col-md-6 text-center ver_completo" >
          <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/otro_gasto/comprobante/${comprobante}" > <i class="fas fa-expand"></i> Ver completo. </a>
        </div>
      </div>

      <div class="text-center mt-4">
        <iframe src="../dist/docs/otro_gasto/comprobante/${comprobante}" frameborder="0" scrolling="no" width="100%" height="510"></iframe>        
      </div>
      <div class="text-center">Comprobante.${extrae_extencion(comprobante)}</div>`);

      } else {
        $(".ver-comprobante").html(`<div class="row text-center">                          
          <!-- Dowload -->
          <div class="col-md-6 text-center descargar">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/otro_gasto/comprobante/${comprobante}" download="Comprobante"> <i class="fas fa-download"></i> Descargar. </a>
          </div>
          <!-- Ver grande -->
          <div class="col-md-6 text-center ver_completo">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/otro_gasto/comprobante/${comprobante}" > <i class="fas fa-expand"></i> Ver completo. </a>
          </div>
        </div>

        <div class="text-center mt-4">
          <iframe src="../dist/svg/doc_si_extencion.svg" frameborder="0" scrolling="no" width="100%" height="510"></iframe>        
        </div>
        <div class="text-center">Comprobante.${extrae_extencion(comprobante)}</div>`);
      }      
    }
  } 

  $(".tooltip").removeClass("show").addClass("hidde");
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-otro_gasto")[0]);

  $.ajax({
    url: "../ajax/otro_gasto.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {

          Swal.fire("Correcto!", "El registro se guardo correctamente.", "success");

          tabla.ajax.reload(null, false);
  
          limpiar();
  
          $("#modal-agregar-otro_gasto").modal("hide");
  
          total();

        }else{  
          ver_errores(e);
        } 
      } catch (err) {
        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      } 

    },
  });
}

function mostrar(idotro_gasto) {

  limpiar();
  
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-otro_gasto").modal("show");

  $.post("../ajax/otro_gasto.php?op=mostrar", { idotro_gasto: idotro_gasto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
      $("#forma_pago").val(e.data.forma_de_pago).trigger("change");
      $("#glosa").val(e.data.glosa).trigger("change");
      $("#idotro_gasto").val(e.data.idotro_gasto);
      $("#fecha_g").val(e.data.fecha_g);
      $("#nro_comprobante").val(e.data.numero_comprobante);  
      $("#num_documento").val(e.data.ruc);
      $("#razon_social").val(e.data.razon_social);
      $("#direccion").val(e.data.direccion);

      $("#subtotal").val(e.data.subtotal);
      $("#igv").val(e.data.igv);
      $("#val_igv").val(e.data.val_igv).trigger("change");
      $("#tipo_gravada").val(e.data.tipo_gravada);
      $("#precio_parcial").val(e.data.costo_parcial);
      $("#descripcion").val(e.data.descripcion);   

      if (e.data.comprobante == "" || e.data.comprobante == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#doc1_nombre").html('');

        $("#doc_old_1").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_1").val(e.data.comprobante); 

        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante,'otro_gasto', 'comprobante', '100%', '210' ));       
            
      }
      
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );

}

function ver_datos(idotro_gasto) {

  $("#modal-ver-transporte").modal("show");

  $.post("../ajax/otro_gasto.php?op=verdatos", { idotro_gasto: idotro_gasto }, function (e, status) {
    e = JSON.parse(e); console.log(e); 
    if (e.status == true) {

      verdatos = `                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo clasificación</th>
                  <td>${e.data.tipo_viajero}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Ruta</th>
                  <td>${e.data.ruta}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo ruta</th>
                    <td>${e.data.tipo_ruta}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha</th>
                  <td>${e.data.fecha_g}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo pago </th>
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante </th>
                  <td>${e.data.tipo_comprobante}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Cantidad</th>
                  <td>${e.data.cantidad}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Precio unitario</th>
                  <td>${parseFloat(e.data.precio_unitario).toFixed(2)}</td>
                </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${parseFloat(e.data.subtotal).toFixed(2)}</td>
                </tr>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${parseFloat(e.data.igv).toFixed(2)}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${parseFloat(e.data.precio_parcial).toFixed(2)}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;

      $("#datostransporte").html(verdatos);
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function total() {
  var idproyecto = localStorage.getItem("nube_idproyecto");

  $("#total_monto").html("");

  $.post("../ajax/otro_gasto.php?op=total", { idproyecto: idproyecto }, function (e, status) {
    e = JSON.parse(e); console.log(e); 
    if (e.status == true) {

      $("#total_monto").html("S/ " + formato_miles(e.data.precio_parcial));

    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function eliminar(idotro_gasto, tipo, numero) {

  crud_eliminar_papelera(
    "../ajax/otro_gasto.php?op=desactivar",
    "../ajax/otro_gasto.php?op=eliminar", 
    idotro_gasto, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del> ${tipo} N° ${numero} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false);total(); },
    false, 
    false, 
    false,
    false
  );

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
  $("#glosa").on("change", function () { $(this).trigger("blur"); });

  $("#form-otro_gasto").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      forma_pago: { required: true },
      tipo_comprobante: { required: true },
      fecha_g: { required: true },
      precio_parcial: { required: true },
      descripcion: { required: true },
      val_igv: { required: true, number: true, min:0, max:1 },
      // terms: { required: true },
    },
    messages: {
      forma_pago: { required: "Por favor una forma de pago", },
      tipo_comprobante: { required: "Por favor seleccionar tipo comprobante", },
      fecha_g: { required: "Por favor ingrese una fecha", },
      precio_parcial: { required: "Ingresar monto",},
      descripcion: { required: "Es necesario rellenar el campo descripción", },
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
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin 
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#glosa").rules("add", { required: true, messages: { required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

no_select_tomorrow("#fecha_g");