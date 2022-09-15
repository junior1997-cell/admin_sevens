var tabla;

//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#lOtraFactura").addClass("active bg-primary");

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2EmpresaACargo", '#filtro_empresa_a_cargo', null);
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#filtro_proveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2EmpresaACargo", '#empresa_acargo', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_prov', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-otras_facturas").submit(); });

  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Seleccinar proveedor", allowClear: true, });
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Seleccinar tipo comprobante", allowClear: true, });
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar forma de pago", allowClear: true, });
  $("#glosa").select2({ theme: "bootstrap4", placeholder: "Seleccinar glosa", allowClear: true, });

  $('#empresa_acargo').select2({ templateResult: template_sleect2_empresa, theme: "bootstrap4", placeholder: "Empresa a cargo", allowClear: true});

  $("#banco_prov").select2({templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione un banco", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_empresa_a_cargo").select2({ templateResult: template_sleect2_empresa, theme: "bootstrap4", placeholder: "Selecione empresa", allowClear: true, });
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });

  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });

  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $('#subtotal').number( true, 2 );
  $('#igv').number( true, 2 );
  $('#precio_parcial').number( true, 2 );

  // Formato para telefono
  $("[data-mask]").inputmask();

  $('.empresa_a_cargo_form').html(`("${localStorage.getItem('nube_empresa_a_cargo')}")`);

  no_select_tomorrow("#fecha_emision");
}

$('.click-btn-fecha-inicio').on('click', function (e) {$('#filtro_fecha_inicio').focus().select(); });
$('.click-btn-fecha-fin').on('click', function (e) {$('#filtro_fecha_fin').focus().select(); });

function templateBanco (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/banco/logo/${state.title}`: '../dist/docs/banco/logo/logo-sin-banco.svg'; 
  var onerror = `onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

function template_sleect2_empresa (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/svg/${state.title}`: '../dist/svg/user_default.svg'; 
  var onerror = `onerror="this.src='../dist/svg/user_default.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

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

  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');

  $('#estado-edit-add-modal').html('Agregar:');
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
  $("#empresa_acargo").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function tbla_principal(empresa_a_cargo, fecha_1, fecha_2, id_proveedor, comprobante) {

  var total_monto = 0;
  $("#total_monto").html('0.00');

  tabla = $("#tabla-otras_facturas").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,3,2,12,13,4,6,7,8,9,11], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,3,2,12,13,4,6,7,8,9,11], } }, { extend: 'pdfHtml5', footer: false,  exportOptions: { columns: [0,3,2,12,13,4,6,7,8,9,11], } }, {extend: "colvis"} ,
    ],
    ajax: {
      url: `../ajax/otra_factura.php?op=tbla_principal&empresa_a_cargo=${empresa_a_cargo}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != "") { $("td", row).eq(0).addClass("text-center"); }
      // columna: sub acciones
      if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: sub fecha
      if (data[2] != "") { $("td", row).eq(2).addClass("text-nowrap"); }
      // columna: sub total
      if (data[6] != "") { $("td", row).eq(6).addClass("text-nowrap"); }
      // columna: igv
      if (data[7] != "") { $("td", row).eq(7).addClass("text-nowrap"); }
      // columna: total
      if (data[8] != "") { $("td", row).eq(8).addClass("text-nowrap"); $("#total_monto").html(formato_miles( total_monto += parseFloat(data[8]) )); }
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
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [12,13], visible: false, searchable: false, },    
      { targets: [6,7,8], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },

    ],
  }).DataTable();
  $(tabla).ready(function () {  $('.cargando').hide(); });

  //total();
}

function total() {

  $("#total_monto").html("");

  $.post("../ajax/otra_factura.php?op=total", {}, function (e, status) {
    e = JSON.parse(e);  //console.log(e);
    if (e.status == true) {
      $("#total_monto").html("S/ " + formato_miles(e.data.precio_parcial));
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

//ver ficha tecnica
function modal_comprobante(comprobante, fecha_emision) {

  var data_comprobante = ""; var url = ""; var nombre_download = "Comprobante";   

  $("#modal-ver-comprobante").modal("show");

  if (comprobante == '' || comprobante == null) {
    $(".ver-comprobante").html(`<div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
      <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
      No hay un documento para mostrar
    </div>`);
  }else{
    
    data_comprobante = doc_view_extencion(comprobante, 'otra_factura', 'comprobante', '100%', '500' );
    url = `../dist/docs/otra_factura/comprobante/${comprobante}`;
    nombre_download = `${format_d_m_a(fecha_emision)} - Comprobante`;

    $(".ver-comprobante").html(`<div class="row" >
      <div class="col-md-6 text-center">
        <a type="button" class="btn btn-warning btn-block btn-xs" href="${url}" download="${nombre_download}"> <i class="fas fa-download"></i> Descargar. </a>
      </div>
      <div class="col-md-6 text-center">
        <a type="button" class="btn btn-info btn-block btn-xs" href="${url}" target="_blank"> <i class="fas fa-expand"></i> Ver completo. </a>
      </div>
      <div class="col-md-12 text-center mt-3 "><i>${nombre_download}.${extrae_extencion(comprobante)}</i></div>
      <div class="col-md-12 mt-2">     
        ${data_comprobante}
      </div>
    </div>`);
  } 

  $('.jq_image_zoom').zoom({ on:'grab' });

  $(".tooltip").removeClass("show").addClass("hidde");
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
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);
        if (e.status == true) {

          Swal.fire("Éxito!", "El registro se guardo correctamente.", "success");
          tabla.ajax.reload(null, false);
          limpiar();
          $("#modal-agregar-otras_facturas").modal("hide");
          //total();

        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }      
      
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

function mostrar(idotra_factura) {

  limpiar();
  
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();  

  $("#tipo_comprobante").val("").trigger("change");
  $("#idproveedor").val("").trigger("change");

  $('#estado-edit-add-modal').html('Editar:');

  $("#modal-agregar-otras_facturas").modal("show");

  $.post("../ajax/otra_factura.php?op=mostrar", { idotra_factura: idotra_factura }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);   

    if (e.status) {
      $("#empresa_acargo").val(e.data.idempresa_a_cargo).trigger("change");
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
      $("#idproveedor").val(e.data.idproveedor).trigger("change");
      $("#forma_pago").val(e.data.forma_de_pago).trigger("change");
      $("#glosa").val(e.data.glosa).trigger("change");
      $("#idotra_factura").val(e.data.idotra_factura);
      $("#fecha_emision").val(e.data.fecha_emision);
      $("#nro_comprobante").val(e.data.numero_comprobante);        
      $("#descripcion").val(e.data.descripcion);

      $("#precio_parcial").val(e.data.costo_parcial);
      $("#subtotal").val(e.data.subtotal);
      $("#igv").val(e.data.igv);
      $("#val_igv").val(e.data.val_igv).trigger("change");

      if (e.data.comprobante == "" || e.data.comprobante == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');  
        $("#doc1_nombre").html('');  
        $("#doc_old_1").val(""); $("#doc1").val("");
  
      } else {
  
        $("#doc_old_1").val(e.data.comprobante); 
        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante, 'otra_factura', 'comprobante', '100%'));      
            
      }
      $('.jq_image_zoom').zoom({ on:'grab' });
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// ver detallles del registro
function verdatos(idotra_factura){

  $(".tooltip").removeClass("show").addClass("hidde");

  $('#datos-otra-factura').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div></div>`);

  var imagen_perfil =''; var btn_imagen_perfil = '';
  
  var comprobante=''; var btn_comprobante = '';

  $("#modal-ver-otra-factura").modal("show");

  $.post("../ajax/otra_factura.php?op=mostrar", { 'idotra_factura': idotra_factura }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status) {

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='Sin Ficha Técnica';
        btn_comprobante='';       
      
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'otra_factura', 'comprobante', '100%');
        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/otra_factura/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/otra_factura/comprobante/${e.data.comprobante}" download="Comprobante - ${removeCaracterEspecial(e.data.tipo_comprobante)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;      

      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th> 
                  <td>${e.data.tipo_comprobante}</td>
                </tr>
                <tr class="" data-widget="expandable-table" aria-expanded="false">
                  <th>Número Comprobante:</th> 
                  <td>${e.data.numero_comprobante}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo Gravada</th>
                  <td>${e.data.tipo_gravada}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                  <td>${e.data.glosa}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${e.data.subtotal}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${e.data.igv}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Costo Parcial  </th>
                  <td>${e.data.costo_parcial}</td>
                </tr>       
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>A cargo  </th>
                  <td><img src="../dist/svg/${e.data.ec_logo}" class="mr-2 w-25px" /> ${e.data.ec_razon_social} - ${e.data.ec_tipo_documento} ${e.data.ec_numero_documento}</td>
                </tr>                                              
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td><textarea cols="30" rows="2" class="textarea_datatable" readonly="">${e.data.descripcion}</textarea></td>
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
    
      $("#datos-otra-factura").html(retorno_html);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar(idotra_factura, nombre ) {
  console.log(idotra_factura, nombre);
  crud_eliminar_papelera(
    "../ajax/otra_factura.php?op=desactivar",
    "../ajax/otra_factura.php?op=eliminar", 
    idotra_factura, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );
}

function calc_total() {

  $(".nro_comprobante").html("Núm. Comprobante");

  var total         = es_numero($('#precio_parcial').val()) == true? parseFloat($('#precio_parcial').val()) : 0;
  var val_igv       = es_numero($('#val_igv').val()) == true? parseFloat($('#val_igv').val()) : 0;
  var subtotal      = 0; 
  var igv           = 0;

  //console.log(total, val_igv); console.log($('#precio_parcial').val(), $('#val_igv').val()); console.log('----------');

  if ($("#tipo_comprobante").select2("val")=="" || $("#tipo_comprobante").select2("val")==null) {
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
  }else if ($("#tipo_comprobante").select2("val") =="Ninguno") {  
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".nro_comprobante").html("Núm. de Operación");
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
  } else {
    $("#subtotal").val(redondearExp(total));
    $("#igv").val("0.00");
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)");
    $("#val_igv").prop("readonly",true);
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

// :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
//Función limpiar
function limpiar_form_proveedor() {
  $("#idproveedor_prov").val("");
  $("#tipo_documento_prov option[value='RUC']").attr("selected", true);
  $("#nombre_prov").val("");
  $("#num_documento_prov").val("");
  $("#direccion_prov").val("");
  $("#telefono_prov").val("");
  $("#c_bancaria_prov").val("");
  $("#cci_prov").val("");
  $("#c_detracciones_prov").val("");
  $("#banco_prov").val("").trigger("change");
  $("#titular_cuenta_prov").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();

  $(".tooltip").removeClass("show").addClass("hidde");
}

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco_prov").select2("val") == null || $("#banco_prov").select2("val") == "" || $("#banco_prov").select2("val") == "1" ) {

    $("#c_bancaria_prov").prop("readonly", true);
    $("#cci_prov").prop("readonly", true);
    $("#c_detracciones_prov").prop("readonly", true);

  } else {
    
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-3").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');    

    $.post("../ajax/ajax_general.php?op=formato_banco", { 'idbanco': $("#banco_prov").select2("val") }, function (e, status) {
      
      e = JSON.parse(e);  // console.log(e);

      if (e.status == true) {
        $(".chargue-format-1").html("Cuenta Bancaria");
        $(".chargue-format-2").html("CCI");
        $(".chargue-format-3").html("Cuenta Detracciones");

        $("#c_bancaria_prov").prop("readonly", false);
        $("#cci_prov").prop("readonly", false);
        $("#c_detracciones_prov").prop("readonly", false);

        var format_cta = decifrar_format_banco(e.data.formato_cta);
        var format_cci = decifrar_format_banco(e.data.formato_cci);
        var formato_detracciones = decifrar_format_banco(e.data.formato_detracciones);
        // console.log(format_cta, formato_detracciones);

        $("#c_bancaria_prov").inputmask(`${format_cta}`);
        $("#cci_prov").inputmask(`${format_cci}`);
        $("#c_detracciones_prov").inputmask(`${formato_detracciones}`);
      } else {
        ver_errores(e);
      }      
    }).fail( function(e) { ver_errores(e); } );
  }
}

function decifrar_format_banco(format) {

  var array_format =  format.split("-"); var format_final = "";

  array_format.forEach((item, index)=>{

    for (let index = 0; index < parseInt(item); index++) { format_final = format_final.concat("9"); }   

    if (parseInt(item) != 0) { format_final = format_final.concat("-"); }
  });

  var ultima_letra = format_final.slice(-1);
   
  if (ultima_letra == "-") { format_final = format_final.slice(0, (format_final.length-1)); }

  return format_final;
}

//guardar proveedor
function guardar_proveedor(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);

  $.ajax({
    url: "../ajax/compra_insumos.php?op=guardar_proveedor",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);
      try {
        if (e.status == true) {
          // toastr.success("proveedor registrado correctamente");
          Swal.fire("Correcto!", "Proveedor guardado correctamente.", "success");          
          limpiar_form_proveedor();
          $("#modal-agregar-proveedor").modal("hide");
          //Cargamos los items al select cliente
          lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', e.data);
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }       
      
      $("#guardar_registro_proveedor").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_proveedor").css({"width": percentComplete+'%'});
          $("#barra_progress_proveedor").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_proveedor").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_proveedor").css({ width: "0%",  });
      $("#barra_progress_proveedor").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_proveedor").css({ width: "0%", });
      $("#barra_progress_proveedor").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

init();

// .....::::::::::::::::::::::::::::::::::::: F O R M    V A L I D A T E  :::::::::::::::::::::::::::::::::::::::..

$(function () {  

  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").on("change", function () { $(this).trigger("blur"); });
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });
  $("#banco_prov").on('change', function() { $(this).trigger('blur'); });
  $("#empresa_acargo").on('change', function() { $(this).trigger('blur'); });

  $("#form-otras_facturas").validate({
    rules: {
      idproveedor:    { required: true },
      forma_pago:     { required: true },
      tipo_comprobante:{ required: true },
      fecha_emision:  { required: true },
      precio_parcial: { required: true },
      val_igv:        { required: true, number: true, min:0, max:1 },
      empresa_acargo: { required: true },
      // terms: { required: true },
    },
    messages: {
      idproveedor:    { required: "Campo requerido", },
      forma_pago:     { required: "Campo requerido", },
      tipo_comprobante:{ required: "Campo requerido", },
      fecha_emision:  { required: "Campo requerido", },
      precio_parcial: { required: "Campo requerido", }, 
      val_igv:        { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
      empresa_acargo: { required: "Campo requerido", }, 
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
      guardaryeditar(e);
    },
  });

  $("#form-proveedor").validate({
    rules: {
      tipo_documento_prov:  { required: true },
      num_documento_prov:   { required: true, minlength: 6, maxlength: 20 },
      nombre_prov:          { required: true, minlength: 3, maxlength: 100 },
      direccion_prov:       { minlength: 5, maxlength: 150 },
      telefono_prov:        { minlength: 8 },
      c_bancaria_prov:      { minlength: 6,  },
      cci_prov:             { minlength: 6,  },
      c_detracciones_prov:  { minlength: 6,  },      
      banco_prov:           { required: true },
      titular_cuenta_prov:  { minlength: 4 },
    },
    messages: {
      tipo_documento_prov:  { required: "Campo requerido.", },
      num_documento_prov:   { required: "Campo requerido.",  minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre_prov:          { required: "Campo requerido.", minlength: "MÍNIMO 3 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      direccion_prov:       { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 150 caracteres.", },
      telefono_prov:        { minlength: "MÍNIMO 9 caracteres.", },
      c_bancaria_prov:      { minlength: "MÍNIMO 6 caracteres.", },
      cci_prov:             { minlength: "MÍNIMO 6 caracteres.",  },
      c_detracciones_prov:  { minlength: "MÍNIMO 6 caracteres.", },      
      banco_prov:           { required: "Campo requerido.",  },
      titular_cuenta_prov:  { minlength: 'MÍNIMO 4 caracteres.' },
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
      guardar_proveedor(e);
    },
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin 
  $("#idproveedor").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#banco_prov").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#empresa_acargo").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


function cargando_search() {
  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
}

function filtros() {  

  var empresa_a_cargo = $("#filtro_empresa_a_cargo").select2('val');
  var fecha_1         = $("#filtro_fecha_inicio").val();
  var fecha_2         = $("#filtro_fecha_fin").val();  
  var id_proveedor    = $("#filtro_proveedor").select2('val');
  var comprobante     = $("#filtro_tipo_comprobante").select2('val');   
  
  var nombre_empresa_a_cargo= $('#filtro_empresa_a_cargo').find(':selected').text();
  var nombre_proveedor      = ' ─ ' + $('#filtro_proveedor').find(':selected').text();
  var nombre_comprobante    = ' ─ ' + $('#filtro_tipo_comprobante').find(':selected').text();

  // filtro de empresa a cargo
  if (empresa_a_cargo == '' || empresa_a_cargo == null || empresa_a_cargo == 0 ) { empresa_a_cargo = ""; nombre_empresa_a_cargo = "" }

  // filtro de fechas
  if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; } else{ fecha_1 = format_a_m_d(fecha_1) == '-'? '': format_a_m_d(fecha_1);}
  if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; } else{ fecha_2 = format_a_m_d(fecha_2) == '-'? '': format_a_m_d(fecha_2);} 

  // filtro de proveedor
  if (id_proveedor == '' || id_proveedor == 0 || id_proveedor == null) { id_proveedor = ""; nombre_proveedor = ""; }

  // filtro de trabajdor
  if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_empresa_a_cargo} ${nombre_proveedor} ${nombre_comprobante}...`);
  //console.log(fecha_1, fecha_2, id_proveedor, comprobante);

  tbla_principal(empresa_a_cargo, fecha_1, fecha_2, id_proveedor, comprobante);
}

function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { }  else{    
    var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
    $('#ruc_proveedor').val(ruc);
  }
}