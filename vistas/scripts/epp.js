var tabla;
var fecha_1_r="", fecha_2_r="", id_proveedor_r="", comprobante_r="", glosa_r="";
//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#bloc_Tecnico").addClass("menu-open");
  $("#mTecnico").addClass("active");
  $("#lEpp").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));
  listar_trabajdor();

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-otro_gasto").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });
  $("#filtro_glosa").select2({ theme: "bootstrap4", placeholder: "Selecione glosa", allowClear: true, });

  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Seleccinar tipo comprobante", allowClear: true, });
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar forma de pago", allowClear: true, });
  $("#glosa").select2({ theme: "bootstrap4", placeholder: "Seleccinar glosa", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

$('.click-btn-fecha-inicio').on('click', function (e) {$('#filtro_fecha_inicio').focus().select(); });
$('.click-btn-fecha-fin').on('click', function (e) {$('#filtro_fecha_fin').focus().select(); });

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
  $( "#num_documento" ).rules( "remove","required" );

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

  }else if ($("#tipo_comprobante").select2("val") =="Boleta") {  
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".nro_comprobante").html("Núm. de Operación");

    $(".div_ruc").show(); $(".div_razon_social").show();
    // $("#num_documento").val(""); $("#razon_social").val("");
    $("#num_documento").rules("add", { required: true, messages: { required: "Campo requerido" } });


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

    $("#num_documento").rules("add", { required: true, messages: { required: "Campo requerido" } });

  } else {
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00");
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)");
    $("#val_igv").prop("readonly",true);
    $(".div_ruc").hide(); $(".div_razon_social").hide();   
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
function listar_trabajdor() { 

  var idproyecto = localStorage.getItem("nube_idproyecto");

  tabla = $("#tabla-epp").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,1,2], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,1,2], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,1,2], }, orientation: 'landscape', pageSize: 'LEGAL',  }, 
      {extend: "colvis"} ,
    ],
    ajax: {
      url: `../ajax/epp.php?op=listar_trabajdor&idproyecto=${idproyecto}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != "") { $("td", row).eq(0).addClass("text-center"); }
      // columna: fecha
      if (data[1] != "") { $("td", row).eq(5).addClass("text-nowrap text-center"); }
      //Columna _: talla
      if (data[2] != "") { $("td", row).eq(5).addClass("text-nowrap text-center"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();

  // Referencia a la fila previamente seleccionada
  var filaSeleccionadaAnterior = null;

  // Agregar el evento onclick a las filas de la tabla
  $('#tabla-epp tbody').on('mouseenter', 'tr', function () {
    $(this).css('cursor', 'pointer');
    }).on('mouseleave', 'tr', function () {
        $(this).css('cursor', 'default');
    }).on('click', 'tr', function () {
      // Eliminar el estilo de fila-seleccionada de la fila anterior
      if (filaSeleccionadaAnterior !== null) {
        filaSeleccionadaAnterior.css('background-color', '');
      }

      // Aplicar el estilo a la nueva fila seleccionada
      $(this).css('background-color', '#ffc107');
      // Guardar la referencia de la nueva fila seleccionada
      filaSeleccionadaAnterior = $(this);

      // Obtener los datos de la fila seleccionada
      var datosFila = tabla.row(this).data();
      // Hacer lo que desees con los datos de la fila
      filaSelecc_tabajador(datosFila[1],datosFila[2],datosFila[3],datosFila[4]);
  });

  $(tabla).ready(function () {  $('.cargando').hide(); });
}

function filaSelecc_tabajador(nombres,t_ropa,t_zapato,id_tpp,) {
  //console.log('nombres: '+nombres+' t_ropa: '+t_ropa+' t_zapato: '+t_zapato+ ' id_tpp: '+id_tpp);
  $(".nombre_epp").html(nombres); $(".tallas").html(t_ropa+' , '+t_zapato); 

  
}


function total(fecha_1, fecha_2, id_proveedor, comprobante, glosa) {

  var idproyecto = localStorage.getItem("nube_idproyecto");

  $("#total_monto").html("");

  $.post("../ajax/epp.php?op=total", { 'idproyecto': idproyecto, 'fecha_1':fecha_1, 'fecha_2':fecha_2, 'id_proveedor':id_proveedor, 'comprobante': comprobante, 'glosa': glosa }, function (e, status) {
    e = JSON.parse(e); console.log(e); 
    if (e.status == true) {

      $("#total_monto").html(formato_miles(e.data.precio_parcial));

    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//ver ficha tecnica
function modal_comprobante(comprobante,tipo,numero_comprobante) {

  var dia_actual = moment().format('DD-MM-YYYY');
  $(".nombre_comprobante").html(`${tipo}-${numero_comprobante}`);
  $('#modal-ver-comprobante').modal("show");
  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'otro_gasto', 'comprobante', '100%', '550'));

  if (DocExist(`dist/docs/otro_gasto/comprobante/${comprobante}`) == 200) {
    $("#iddescargar").attr("href","../dist/docs/otro_gasto/comprobante/"+comprobante).attr("download", `${tipo}-${numero_comprobante}  - ${dia_actual}`).removeClass("disabled");
    $("#ver_completo").attr("href","../dist/docs/otro_gasto/comprobante/"+comprobante).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }

  $('.jq_image_zoom').zoom({ on:'grab' }); 

}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-otro_gasto")[0]);

  $.ajax({
    url: "../ajax/epp.php?op=guardaryeditar",
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
          total(fecha_1_r,fecha_2_r,id_proveedor_r,comprobante_r);
        }else{  
          ver_errores(e);
        } 
      } catch (err) {
        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      } 
      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_otro_gasto").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");          
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_otro_gasto").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');      
    },
    complete: function () {
      $("#barra_progress_otro_gasto").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');      
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idotro_gasto) {

  limpiar();
  
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-otro_gasto").modal("show");

  $.post("../ajax/epp.php?op=mostrar", { idotro_gasto: idotro_gasto }, function (e, status) {
    
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
      $("#tipo_gravada").val(e.data.tipo_gravada);
      $("#precio_parcial").val(e.data.costo_parcial);
      $("#descripcion").val(e.data.descripcion); 
      
      $("#val_igv").val(e.data.val_igv).trigger("change");
  

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
      $('.jq_image_zoom').zoom({ on:'grab' });
      
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();


    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );

}

function ver_datos(idotro_gasto) {

  $("#modal-ver-otro_gasto").modal("show");

  $.post("../ajax/epp.php?op=verdatos", { idotro_gasto: idotro_gasto }, function (e, status) {
    e = JSON.parse(e); console.log(e); 
    if (e.status == true) {

      if (e.data.comprobante != '') {
        
        comprobante =  doc_view_extencion(e.data.comprobante, 'otro_gasto', 'comprobante', '100%');
        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/otro_gasto/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/otro_gasto/comprobante/${e.data.comprobante}" download="${removeCaracterEspecial(e.data.tipo_comprobante+' '+e.data.numero_comprobante)} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {

        comprobante='Sin comprobante';
        btn_comprobante='';

      }

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
                  <th>Tipo comprobante</th>
                  <td>${e.data.tipo_comprobante}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Número comprobante</th>
                  <td>${(e.data.numero_comprobante ? e.data.numero_comprobante : "-")}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo gravada</th>
                    <td>${e.data.tipo_gravada}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                    <td>${e.data.glosa}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>R.U.C</th>
                  <td>${(e.data.ruc ? e.data.ruc : "-")}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Razón social </th>
                  <td>${(e.data.razon_social ? e.data.razon_social : "-")}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha emisión</th>
                  <td>${ format_d_m_a(e.data.fecha_g)}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Forma de pago </th>
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>S/ ${formato_miles(parseFloat(e.data.subtotal).toFixed(2))}</td>
                </tr>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>S/ ${ formato_miles( parseFloat(e.data.igv).toFixed(2))}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>S/ ${formato_miles(parseFloat(e.data.costo_parcial).toFixed(2))}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Comprob.</th>
                    <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;

      $("#datosotro_gasto").html(verdatos);

      $('.jq_image_zoom').zoom({ on:'grab' }); 

      $(".tooltip").removeClass("show").addClass("hidde");

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function eliminar(idotro_gasto, tipo, numero) {

  crud_eliminar_papelera(
    "../ajax/epp.php?op=desactivar",
    "../ajax/epp.php?op=eliminar", 
    idotro_gasto, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del> ${tipo} N° ${numero} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false);total(fecha_1_r,fecha_2_r,id_proveedor_r,comprobante_r); },
    false, 
    false, 
    false,
    false
  );
}


init();

$(function () {  

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
    
    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardaryeditar(e);
    },
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin 
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#glosa").rules("add", { required: true, messages: { required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

no_select_tomorrow("#fecha_g");


function cargando_search() {
  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
}

function filtros() {  

  var fecha_1       = $("#filtro_fecha_inicio").val();
  var fecha_2       = $("#filtro_fecha_fin").val();  
  var id_proveedor  = $("#filtro_proveedor").select2('val');
  var comprobante   = $("#filtro_tipo_comprobante").select2('val');   
  var glosa         = $("#filtro_glosa").select2('val');   
  
  var nombre_proveedor    = $('#filtro_proveedor').find(':selected').text();
  var nombre_comprobante  = ' ─ ' + $('#filtro_tipo_comprobante').find(':selected').text();
  var nombre_glosa        = ' ─ ' + $('#filtro_tipo_comprobante').find(':selected').text();

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; } else{ fecha_1 = format_a_m_d(fecha_1) == '-'? '': format_a_m_d(fecha_1);}
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; } else{ fecha_2 = format_a_m_d(fecha_2) == '-'? '': format_a_m_d(fecha_2);} 

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == 0 || id_proveedor == null) { id_proveedor = ""; nombre_proveedor = ""; }

  // filtro de comprobante
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }

  // filtro de glosa
  if (glosa == '' || glosa == null || glosa == 0 ) { glosa = ""; nombre_glosa = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_proveedor} ${nombre_comprobante} ${glosa}...`);
  //console.log(fecha_1, fecha_2, id_proveedor, comprobante);

  listar(fecha_1, fecha_2, id_proveedor, comprobante, glosa);
}

function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { }  else{    
    var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
    $('#ruc_proveedor').val(ruc);
  }
}

