var tabla;

var idproyecto_r = '', fecha_1_r = '', fecha_2_r = '', id_proveedor_r = '', comprobante_r = '';

//Función que se ejecuta al inicio
function init() {

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Viaticos").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mViatico").addClass("active bg-primary");

  $("#lHospedaje").addClass("active");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  // tabla_principal();    

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2(`../ajax/hospedaje.php?op=select2Proveedor&idproyecto=${localStorage.getItem('nube_idproyecto')}`, '#filtro_proveedor', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) {$("#submit-form-hospedaje").submit();});

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#unidad").select2({ theme: "bootstrap4", placeholder: "Seleccinar unidad", allowClear: true, });  
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Seleccinar tipo comprobante", allowClear: true, });  
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar una forma de pago", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });
  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  

  // restringimos la fecha para no elegir mañana
  no_select_tomorrow("#fecha_inicio");
  no_select_tomorrow("#fecha_comprobante");

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

function calc_cantidad() {

  if ($("#unidad").select2("val")=='Día') {
        
    var fecha_inicio    = $("#fecha_inicio").val();  
    var fecha_fin       = $("#fecha_fin").val();  
    var precio_unitario = es_numero($('#precio_unitario').val()) == true? parseFloat($('#precio_unitario').val()) : 0;

    if (fecha_inicio!='' && fecha_fin!='' ) {

      var diferencia= diferencia_de_dias(fecha_inicio, fecha_fin);
      $("#cantidad").val(diferencia);
      $("#precio_parcial").val(diferencia * precio_unitario);
    }
  }else{
    $("#cantidad").val("0.00");
    $("#precio_parcial").val("0.00");
  }
}

function calc_total() {  

  $(".nro_comprobante").html("Núm. Comprobante");
  $( "#num_documento" ).rules( "remove","required" );
  
  var cantidad      =  es_numero($('#cantidad').val()) == true? parseFloat($('#cantidad').val()) : 0;
  var precio_unit   =  es_numero($('#precio_unitario').val()) == true? parseFloat($('#precio_unitario').val()) : 0;

  var total         = cantidad * precio_unit;
  var val_igv       = es_numero($('#val_igv').val()) == true? parseFloat($('#val_igv').val()) : 0;
  var subtotal      = 0; 
  var igv           = 0;

  $('#precio_parcial').val(total);

  if ($("#tipo_comprobante").select2("val")=="" || $("#tipo_comprobante").select2("val")==null) {
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".div_ruc").hide(); $(".div_razon_social").hide();    
  }else if ($("#tipo_comprobante").select2("val") =="Ninguno") {  
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".nro_comprobante").html("Núm. de Operación");
    $(".div_ruc").hide(); $(".div_razon_social").hide();
  }else if ($("#tipo_comprobante").select2("val") =="Boleta") {  
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".div_ruc").show(); $(".div_razon_social").show();
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
    $("#num_documento").rules("add", { required: true, messages: { required: "Campo requerido" } });
    $(".div_ruc").show(); $(".div_razon_social").show();

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

//Función limpiar
function limpiar() {
  $("#idhospedaje").val("");
  $("#fecha_inicio").val(""); 
  $("#fecha_fin").val(""); 
  $("#cantidad").val(""); 
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

  $("#unidad").val("null").trigger("change");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

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
function tabla_principal(idproyecto, fecha_1, fecha_2, id_proveedor, comprobante) {
  idproyecto_r = idproyecto; fecha_1_r = fecha_1; fecha_2_r = fecha_2; id_proveedor_r = id_proveedor; comprobante_r = comprobante;
  tabla=$('#tabla-hospedaje').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 6, 10, 25, 75, 100, 200,], ["Todos", 6, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,3,9,10,11,2,12,13,14,6,15,16,17,18,19,20,21,7], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,3,9,10,11,2,12,13,14,6,15,16,17,18,19,20,21,7], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,3,9,10,11,2,12,13,14,6,15,16,17,18,19,20,21,7], }, orientation: 'landscape', pageSize: 'LEGAL',  }, 
    ],
    ajax:{
      url: `../ajax/hospedaje.php?op=tabla_principal&idproyecto=${idproyecto}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
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
      // columna: total
      if (data[7] != '') { $("td", row).eq(7).addClass('text-nowrap'); }
    },
    language: {
      decimal: '.',
      thousands: ',',
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [6], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [9,10,11,12,13,14,15,16,17,18,19,20,21], visible: false, searchable: false, },    
    ],
  }).DataTable();

  total(idproyecto, fecha_1, fecha_2, id_proveedor, comprobante);
}

function total(idproyecto, fecha_1, fecha_2, id_proveedor, comprobante) {
   
  $("#total_monto").html("");

  $.post("../ajax/hospedaje.php?op=total", { 'idproyecto': idproyecto, 'fecha_1': fecha_1, 'fecha_2': fecha_2, 'id_proveedor': id_proveedor, 'comprobante': comprobante }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  
    if (e.status ==true) {      
      // $("#total_subtotal").html(formato_miles(e.data.subtotal));
      // $("#total_igv").html(formato_miles(e.data.igv));
      $("#total_monto").html(formato_miles(e.data.precio_parcial));
      $('.cargando').hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

//ver comprobante
function modal_comprobante(comprobante, nombre){  

  $('.tile-modal-comprobante').html(nombre); 
  $('#modal-ver-comprobante').modal("show");
  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'hospedaje', 'comprobante', '100%', '550'));

  if (DocExist(`dist/docs/hospedaje/comprobante/${comprobante}`) == 200) {
    $("#iddescargar").attr("href","../dist/docs/hospedaje/comprobante/"+comprobante).attr("download", nombre).removeClass("disabled");
    $("#ver_completo").attr("href","../dist/docs/hospedaje/comprobante/"+comprobante).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }

  $('.jq_image_zoom').zoom({ on:'grab' });  
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
    success: function (e) {      
      try {
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {
          Swal.fire("Correcto!", "Hospedaje guardado correctamente", "success");
          tabla.ajax.reload(null, false); total(idproyecto_r, fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r);
          limpiar();        
          
          $("#modal-agregar-hospedaje").modal("hide");
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      
     
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
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-hospedaje").modal("show")
    
  $.post("../ajax/hospedaje.php?op=mostrar", { idhospedaje: idhospedaje }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
      $("#unidad").val(e.data.unidad).trigger("change"); 
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change"); 
      $("#forma_pago").val(e.data.forma_de_pago).trigger("change");
      $("#idhospedaje").val(e.data.idhospedaje);
      $("#fecha_inicio").val(e.data.fecha_inicio).trigger("change"); 
      $("#fecha_fin").val(e.data.fecha_fin); 
      $("#cantidad").val(e.data.cantidad); 
      $("#precio_unitario").val(redondearExp(e.data.precio_unitario)); 

      $("#fecha_comprobante").val(e.data.fecha_comprobante);
      $("#nro_comprobante").val(e.data.numero_comprobante);

      $("#num_documento").val(e.data.ruc);
      $("#razon_social").val(e.data.razon_social);
      $("#direccion").val(e.data.direccion);
      $("#descripcion").val(e.data.descripcion);

      $("#precio_parcial").val(redondearExp(e.data.precio_parcial));   
      $("#subtotal").val(redondearExp(e.data.subtotal)); 
      $("#igv").val(redondearExp(e.data.igv));
      $("#val_igv").val(e.data.val_igv).trigger("change");
    
      if (e.data.comprobante == "" || e.data.comprobante == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
        $("#doc1_nombre").html('');
        $("#doc_old_1").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_1").val(e.data.comprobante); 
        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);        
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante, 'hospedaje', 'comprobante', '100%'));
        
      }
      
      $('.jq_image_zoom').zoom({ on:'grab' });
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    } 
  }).fail( function(e) { ver_errores(e); } );
}

function ver_datos(idhospedaje) {

  $("#modal-ver-hospedaje").modal("show")
  var comprobante=''; var btn_comprobante='';

  $.post("../ajax/hospedaje.php?op=verdatos", { idhospedaje: idhospedaje }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 

    if (e.data.comprobante != '') {
        
      comprobante =  doc_view_extencion(e.data.comprobante, 'hospedaje', 'comprobante', '100%');
      
      btn_comprobante=`
      <div class="row">
        <div class="col-6"">
          <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/hospedaje/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
        </div>
        <div class="col-6"">
          <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/hospedaje/comprobante/${e.data.comprobante}" download="${encodeHtml(e.data.tipo_comprobante + ' - ' + e.data.numero_comprobante)} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
        </div>
      </div>`;
    
    } else {

      comprobante='Sin Ficha Técnica';
      btn_comprobante='';

    } 
        
    html_data=`                                                                            
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-hover table-bordered">        
            <tbody>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Proveedor</th>
                <td>${e.data.razon_social} <br> <b>Ruc:</b> ${e.data.ruc} </td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Descripción</th>
                <td><textarea cols="30" rows="2" class="textarea_datatable" readonly="">${e.data.descripcion}</textarea></td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Glosa</th>
                <td>${e.data.glosa}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Unidad</th>
                <td>${e.data.unidad}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Fecha Inicio y Fin</th>
                <td>${format_d_m_a(e.data.fecha_inicio)} | ${format_d_m_a(e.data.fecha_fin)}</td>
              </tr>              
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Cantidad</th>
                <td>${formato_miles(e.data.cantidad)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Precio unitario</th>
                <td>${formato_miles(e.data.precio_unitario)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Tipo pago </th>
                <td>${e.data.forma_de_pago!="" || e.data.forma_de_pago==null ?e.data.forma_de_pago:''}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>${e.data.tipo_comprobante}</th> 
                <td>${e.data.numero_comprobante}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Fecha comprobante</th>
                <td>${format_d_m_a(e.data.fecha_comprobante)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Subtotal</th>
                <td>${formato_miles(e.data.subtotal)}</td>
              </tr>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>IGV</th>
                <td>${formato_miles(e.data.igv)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Total</th>
                <td>${formato_miles(e.data.precio_parcial)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Comprobante</th>
                <td> ${comprobante} <br>${btn_comprobante}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>`;
  
    $("#datoshospedaje").html(html_data);
    $('.jq_image_zoom').zoom({ on:'grab' });
  }).fail( function(e) { ver_errores(e); } );
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
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {
          Swal.fire("Activado!", "Tu registro ha sido activado.", "success");
          tabla.ajax.reload(null, false);
          total(idproyecto_r, fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r);
        } else {
          ver_errores(e);
        }        
      }).fail( function(e) { ver_errores(e); } );      
    }
  });      
}

//Función para Eliminar registros
function eliminar(idhospedaje, nombre) {
  crud_eliminar_papelera(
    "../ajax/hospedaje.php?op=desactivar",
    "../ajax/hospedaje.php?op=eliminar", 
    idhospedaje, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false); total(idproyecto_r, fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r); },
    false, 
    false, 
    false,
    false
  );  
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });
  $("#unidad").on("change", function () { $(this).trigger("blur"); });

  $("#form-hospedaje").validate({
    //ignore: '.select2-input, .select2-focusser',
    rules: {
      forma_pago:       { required: true },
      tipo_comprobante: { required: true },
      fecha_comprobante:{ required: true },
      fecha_inicio:     { required: true },
      cantidad:         {minlength: 1},
      precio_unitario:  {required: true, min:'0.01',},
      descripcion:      {required: true},
      unidad:           {required: true},
      val_igv:          { required: true, number: true, min:0, max:1 },
    },
    messages: {
      forma_pago:       { required: "Campo requerido.", },
      tipo_comprobante: { required: "Campo requerido.", },
      fecha_comprobante:{ required: "Campo requerido.", },
      fecha_inicio:     { required: "Campo requerido.", },
      cantidad:         { minlength: "Cantidad.", min:"MINIMO 1" },
      precio_unitario:  { required: "Campo requerido.", min:"MINIMO 0.01"  },
      descripcion:      { required: "Es necesario rellenar el campo descripción", },
      unidad:           { required: "Campo requerido.", },
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
  $("#unidad").rules("add", { required: true, messages: { required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function restrigir_fecha_input() {  restrigir_fecha_ant("#fecha_fin",$("#fecha_inicio").val());}


function cargando_search() {
  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
}

function filtros() {  

  var fecha_1       = $("#filtro_fecha_inicio").val();
  var fecha_2       = $("#filtro_fecha_fin").val();  
  var id_proveedor  = $("#filtro_proveedor").select2('val');
  var comprobante   = $("#filtro_tipo_comprobante").select2('val');   
  
  var nombre_proveedor = $('#filtro_proveedor').find(':selected').text();
  var nombre_comprobante = ' ─ ' + $('#filtro_tipo_comprobante').find(':selected').text();

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; } else{ fecha_1 = format_a_m_d(fecha_1) == '-'? '': format_a_m_d(fecha_1);}
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; } else{ fecha_2 = format_a_m_d(fecha_2) == '-'? '': format_a_m_d(fecha_2);} 

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == 0 || id_proveedor == null) { id_proveedor = ""; nombre_proveedor = ""; }

  // filtro de trabajdor
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_proveedor} ${nombre_comprobante}...`);
  //console.log(fecha_1, fecha_2, id_proveedor, comprobante);

  tabla_principal(localStorage.getItem("nube_idproyecto"), fecha_1, fecha_2, id_proveedor, comprobante);
}



