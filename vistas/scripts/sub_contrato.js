var tabla;
var tabla_pagos_proveedor;
var tabla_pagos_detraccion;

var cuenta_bancaria;
var cuenta_detracciones;
var totattotal;
var monto_total_dep;
var id_subcontrato;
var idproyecto_r='', fecha_1_r='', fecha_2_r='', id_proveedor_r='', comprobante_r='';

//Función que se ejecuta al inicio
function init() {

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lSubContrato").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));  

  //tabla_principal(localStorage.getItem('nube_idproyecto'));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_pago', null);
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#filtro_proveedor', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_subcontrato").on("click", function (e) {$("#submit-form-agregar-sub-contrato").submit();});
  $("#guardar_registro_pago_subcontrato").on("click", function (e) {$("#submit-form-pago-subcontrato").submit();});

  // ══════════════════════════════════════ INITIALIZE SELECT2 - SUBCONTRATO ══════════════════════════════════════
  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Seleccinar un proveedor", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - PAGOS ══════════════════════════════════════
  $("#forma_de_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar forma de pago", allowClear: true, });
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Seleccinar tipo comprobante", allowClear: true, });
  $("#banco_pago").select2({ templateResult: templateBanco, theme: "bootstrap4", placeholder: "Seleccinar banco", allowClear: true, });
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar forma de pago", allowClear: true, });
  $("#tipo_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar forma de pago", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });
  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  
  no_select_tomorrow("#fecha_subcontrato");
  no_select_tomorrow("#fecha_pago");  

  // Formato para telefono
  $("[data-mask]").inputmask();
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

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")); });

// abrimos el navegador de archivos
$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e,$("#doc2").attr("id")); });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

// Eliminamos el doc 2
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

function table_show_hide(estado) {

  if (estado==1) {

    $('#tbl-facturas').hide();
    $('#add_agregar_facturas').hide();
    $('#tbl-pagos').hide();
    $('#regresar').hide();
    $('#add_agregar_pago').hide();

    $('#add_sub_contrato').show();
    $('#tbl-principal').show();

    $('.filtros-inputs').show();
    
  } else if (estado==2) {

    $('#add_sub_contrato').hide();
    $('#tbl-principal').hide();
    $('#add_agregar_facturas').hide();
    $('#tbl-facturas').hide();

    $('#tbl-pagos').show();
    $('#regresar').show();
    $('#add_agregar_pago').show();    
    $('.filtros-inputs').hide();
  }  
}

// :::::::::::::::::::::::::::::::::::::::::::: S E C T I O N  -  S U B   C O N T R A T O  ::::::::::::::::::::::::::::::::::::::::::::

function limpiar() {

  $("#idsubcontrato").val("");
  $("#fecha_subcontrato").val(""); 
  $("#numero_comprobante").val("");

  $("#subtotal").val("");
  $("#igv").val("");
  $("#val_igv").val("");
  $("#costo_parcial").val("");

  $("#descripcion").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_de_pago").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function tabla_principal(idproyecto, fecha_1, fecha_2, id_proveedor, comprobante) {

  idproyecto_r = idproyecto, fecha_1_r = fecha_1, fecha_2_r = fecha_2, id_proveedor_r = id_proveedor, comprobante = comprobante;  

  tabla=$('#tabla-sub-contratos').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,3,10,11,12,13,2,14,15,16,17,6,18,8,19,20,5], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,3,10,11,12,13,2,14,15,16,17,6,18,8,19,20,5], } }, 
      { extend: 'pdfHtml5', footer: true,  exportOptions: { columns: [0,3,10,11,12,13,2,14,15,16,17,6,18,8,19,20,5], }, orientation: 'landscape', pageSize: 'LEGAL', }, 
    ],
    ajax:{
      url: `../ajax/sub_contrato.php?op=tabla_principal&idproyecto=${idproyecto}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') {  $("td", row).eq(0).addClass('text-center');  }
      // columna: op
      if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap");  }
      // columna: sub total
      if (data[5] != '') { $("td", row).eq(5).addClass('text-nowrap text-right');  }
      // columna: igv
      if (data[6] != '') { $("td", row).eq(6).addClass('text-nowrap text-right');  }
      // columna: total
      if (data[7] != '') { $("td", row).eq(7).addClass('text-nowrap text-right');  }
      if (data[8] != "") {
        var num = parseFloat(quitar_formato_miles(data[8])); //console.log(num);
        if (num > 0) {
          $("td", row).eq(8).addClass('bg-warning text-right');
        } else if (num == 0) {
          $("td", row).eq(8).addClass('bg-success text-right');            
        } else if (num < 0) {
          $("td", row).eq(8).addClass('bg-danger text-right');
        }
      }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs:[      
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [10,11,12,13,14,15,16,17,18,19,20], visible: false, searchable: false, },
      { targets: [6], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();

  total(idproyecto, fecha_1, fecha_2, id_proveedor, comprobante);
}

function total(idproyecto, fecha_1, fecha_2, id_proveedor, comprobante) {
  $(".total_subtotal").html(`<i class="fas fa-spinner fa-pulse"></i>`);
  $(".total_igv").html(`<i class="fas fa-spinner fa-pulse"></i>`);
  $(".total_gasto").html(`<i class="fas fa-spinner fa-pulse"></i>`);
  $(".total_deposito").html(`<i class="fas fa-spinner fa-pulse"></i>`);
  $(".total_saldo").html(`<i class="fas fa-spinner fa-pulse"></i>`);
  $.post("../ajax/sub_contrato.php?op=total", { 'idproyecto': idproyecto, 'fecha_1': fecha_1, 'fecha_2': fecha_2, 'id_proveedor': id_proveedor, 'comprobante': comprobante }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    if (e.status == true) {
      // $(".total_subtotal").html(formato_miles(e.data.total_subtotal));
      // $(".total_igv").html(formato_miles(e.data.total_igv));
      $(".total_gasto").html(formato_miles(e.data.total_gasto));
      $(".total_deposito").html(formato_miles(e.data.total_deposito));
      $(".total_saldo").html(formato_miles(e.data.total_gasto - e.data.total_deposito));
      $('.cargando').hide();
    } else {
      ver_errores(e);
    }     
  }).fail( function(e) { ver_errores(e); } );
}

function modal_comprobante(comprobante, nombre){
  $('.tile-modal-comprobante').html(nombre); 
  $('#modal-ver-comprobante').modal("show");
  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'sub_contrato', 'comprobante_subcontrato', '100%', '550'));

  if (DocExist(`dist/docs/sub_contrato/comprobante_subcontrato/${comprobante}`) == 200) {
    $("#iddescargar").attr("href","../dist/docs/sub_contrato/comprobante_subcontrato/"+comprobante).attr("download", nombre).removeClass("disabled");
    $("#ver_completo").attr("href","../dist/docs/sub_contrato/comprobante_subcontrato/"+comprobante).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }

  $('.jq_image_zoom').zoom({ on:'grab' });  
}

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-agregar-sub-contrato")[0]);
 
  $.ajax({
    url: "../ajax/sub_contrato.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {

          Swal.fire("Correcto!", "Subcontrato guardado correctamente", "success");
          tabla.ajax.reload(null, false);    
          total(idproyecto_r, fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r)
          limpiar();
  
          $("#modal-agregar-sub-contrato").modal("hide");       
  
        }else{
          ver_errores(e);
        }        
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_subcontrato").html('Guardar Cambios').removeClass('disabled');      
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_subcontrato").css({"width": percentComplete+'%'});
          $("#barra_progress_subcontrato").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_subcontrato").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_subcontrato").css({ width: "0%",  });
      $("#barra_progress_subcontrato").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_subcontrato").css({ width: "0%", });
      $("#barra_progress_subcontrato").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idsubcontrato) {
  limpiar();
  //$("#proveedor").val("").trigger("change"); 
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-sub-contrato").modal("show")

  $.post("../ajax/sub_contrato.php?op=mostrar", { idsubcontrato: idsubcontrato }, function (e, status) {

    e = JSON.parse(e); // console.log(e);  
    if (e.status == true) {
      $("#idproyecto").val(e.data.idproyecto).trigger("change"); 
      $("#idproveedor").val(e.data.idproveedor).trigger("change"); 
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change"); 
      $("#forma_de_pago").val(e.data.forma_de_pago).trigger("change");

      $("#idsubcontrato").val(e.data.idsubcontrato);
      $("#fecha_subcontrato").val(e.data.fecha_subcontrato); 
      $("#numero_comprobante").val(e.data.numero_comprobante);
      $("#descripcion").val(e.data.descripcion);

      $("#costo_parcial").val(e.data.costo_parcial);
      $("#subtotal").val(parseFloat(e.data.subtotal));
      $("#igv").val(e.data.igv);
      $("#val_igv").val(e.data.val_igv).trigger("change");
      
      /**-------------------------*/
      if (e.data.comprobante == "" || e.data.comprobante == null  ) {
        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
        $("#doc1_nombre").html('');
        $("#doc_old_1").val(""); $("#doc1").val("");
      } else {
        $("#doc_old_1").val(e.data.comprobante); 
        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);      
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante, 'sub_contrato', 'comprobante_subcontrato', '100%'));          
      }

      $('.jq_image_zoom').zoom({ on:'grab' });

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

function ver_datos(idsubcontrato) {

  $("#modal-ver-datos-sub-contrato").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/sub_contrato.php?op=verdatos", { idsubcontrato: idsubcontrato }, function (e , status) {

    e = JSON.parse(e); console.log(e);

    if (e.status == true) {
      if (e.data.comprobante != '') {
          
          comprobante =  doc_view_extencion(e.data.comprobante, 'sub_contrato', 'comprobante_subcontrato', '100%');
          
          btn_comprobante=`
          <div class="row">
            <div class="col-6"">
              <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/sub_contrato/comprobante_subcontrato/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
            </div>
            <div class="col-6"">
              <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/sub_contrato/comprobante_subcontrato/${e.data.comprobante}" download="${removeCaracterEspecial(e.data.tipo_comprobante +' - '+ e.data.numero_comprobante)}"> <i class="fas fa-download"></i></a>
            </div>
          </div>`;
        
        } else {
          comprobante='Sin Ficha Técnica';
          btn_comprobante='';
        }
      
      data_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Proveedor</th>
                  <td>${e.data.razon_social} <br> <b>${e.data.tipo_documento}:</b> ${e.data.ruc} </td>
                  
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
                  <th>Fecha</th>
                  <td>${e.data.fecha_subcontrato}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo pago </th>
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>${e.data.tipo_comprobante}</th> 
                  <td>${e.data.numero_comprobante}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${redondearExp(e.data.subtotal)}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${redondearExp(e.data.igv)}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${redondearExp(e.data.costo_parcial)}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td >${comprobante} <br> ${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $("#datos-sub-contrato").html(data_html);
      $('.jq_image_zoom').zoom({ on:'grab' }); 
    } else {
      ver_errores(e);
    }    

  }).fail( function(e) { ver_errores(e); } );
}

function activar(idsubcontrato) {
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
      $.post("../ajax/sub_contrato.php?op=activar", { idsubcontrato: idsubcontrato }, function (e) {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {
          Swal.fire("Activado!", "Tu registro ha sido activado.", "success");
          tabla.ajax.reload(null, false);
          total(idproyecto_r, fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r)
        } else {
          ver_errores(e);
        }        
      }).fail( function(e) { ver_errores(e); } );      
    }
  });      
}

function eliminar(idsubcontrato, nombre) {
  crud_eliminar_papelera(
    "../ajax/sub_contrato.php?op=desactivar",
    "../ajax/sub_contrato.php?op=eliminar", 
    idsubcontrato, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false); },
    function(){ total(idproyecto_r, fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r) },
    false, 
    false,
    false
  );  
}

// calcular totales
function calc_total() {

  $(".nro_comprobante").html("Núm. Comprobante");

  var total         = es_numero($('#costo_parcial').val()) == true? parseFloat($('#costo_parcial').val()) : 0;
  var val_igv       = es_numero($('#val_igv').val()) == true? parseFloat($('#val_igv').val()) : 0;
  var subtotal      = 0; 
  var igv           = 0;

 // console.log(total, val_igv); console.log($('#costo_parcial').val(), $('#val_igv').val()); console.log('----------');

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

// :::::::::::::::::::::::::::::::::::: C R U D   P A G O S  ::::::::::::::::::::::::::::::::::::

function listar_pagos(idsubcontrato, total_pago, total_deposito) {
 
  table_show_hide(2);

  id_subcontrato=idsubcontrato;

  totattotal=total_pago; monto_total_dep=total_deposito;

  $('#total_apagar').html(formato_miles(total_pago));

  tabla_pagos_proveedor=$('#tabla-pagos-proveedor').dataTable({
    responsive: false,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,7], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,7], }  }, 
      { extend: 'pdfHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,7], }, orientation: 'landscape', pageSize: 'LEGAL',  }
    ],
    ajax:{
      url: '../ajax/sub_contrato.php?op=listar_pagos_proveedor&idsubcontrato='+idsubcontrato,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
      // columna: sub total
      if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: total
      if (data[7] != '') { $("td", row).eq(7).addClass('text-nowrap text-right'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
 
  tabla_pagos_detraccion=$('#tabla-pagos-detraccion').dataTable({
    responsive: false,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,7], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,7], } }, { extend: 'pdfHtml5', footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,4,5,6,7], } }],
    ajax:{
      url: '../ajax/sub_contrato.php?op=listar_pagos_detraccion&idsubcontrato='+idsubcontrato,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
      // columna: sub total
      if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: total
      if (data[7] != '') { $("td", row).eq(7).addClass('text-nowrap text-right'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 5,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();

  datos_proveedor(idsubcontrato);
  total_pagos_proveedor(id_subcontrato);
  total_pagos_detraccion(id_subcontrato);
}

function datos_proveedor(idsubcontrato) {

  $("#h4_mostrar_beneficiario").html("");

  $("#banco_pago").val("").trigger("change");
  $("#tipo_pago").val('Proveedor').trigger("change");

  $.post("../ajax/sub_contrato.php?op=datos_proveedor", { idsubcontrato: idsubcontrato }, function (e, status) {
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      $("#idsubcontrato_pago").val(e.data.idsubcontrato );
      $("#beneficiario_pago").val(e.data.razon_social);
      $("#h4_mostrar_beneficiario").html(e.data.razon_social);
      $("#banco_pago").val(e.data.idbancos).trigger("change");
      $("#tipo_pago").val('Proveedor').trigger("change");
      $("#titular_cuenta_pago").val(e.data.titular_cuenta);

      cuenta_bancaria=e.data.cuenta_bancaria;
      cuenta_detracciones=e.data.cuenta_detracciones;

      if ($("#tipo_pago").select2("val") == "Proveedor") {$("#cuenta_destino_pago").val(""); $("#cuenta_destino_pago").val(cuenta_bancaria); }
    } else {
      ver_errores(e);
    }  
  }).fail( function(e) { ver_errores(e); } );
}

function total_pagos_proveedor(id_subcontrato) {
  //limpiamos
  $("#t_proveedor").html("");
  $("#t_provee_porc").html("");

  var monto_pagar_prov= ((totattotal * 90) / 100);

  $("#t_proveedor").html(formato_miles(monto_pagar_prov));
  $("#t_provee_porc").html(90);

  var  porcentaj_saldo=0; var porcentaj_deposito_a_la_fecha=0; var total_saldo=0;

  $(".monto_total_deposito_prov").html("");
  $(".porcnt_deposito_prov").html("");
  $("#saldo_prov").html("");
  $("#porcnt_sald_prov").html("");

  $.post("../ajax/sub_contrato.php?op=total_pagos_prov", { idsubcontrato: id_subcontrato }, function (e, status) {

    e = JSON.parse(e);  //console.log(e); 
    
    if (e.data.monto_parcial_deposito==null || e.data.monto_parcial_deposito=="" || e.data.monto_parcial_deposito==0 ) {

      $(".monto_total_deposito_prov").html('0.00');

      $(".porcnt_deposito_prov").html("0.00");
      
      $("#saldo_prov").html(formato_miles(monto_pagar_prov));

      $("#porcnt_sald_prov").html('100.00');
      
    } else {
      
      $(".monto_total_deposito_prov").html(formato_miles(e.data.monto_parcial_deposito));

      $(".porcnt_deposito_prov").html(redondearExp((e.data.monto_parcial_deposito * 100) / monto_pagar_prov));

      porcentaj_deposito_a_la_fecha= ((e.data.monto_parcial_deposito * 100) / monto_pagar_prov).toFixed(4);

      total_saldo=(parseFloat(monto_pagar_prov)- parseFloat(e.data.monto_parcial_deposito));

      porcentaj_saldo=((total_saldo*porcentaj_deposito_a_la_fecha)/e.data.monto_parcial_deposito);

      $("#saldo_prov").html(redondearExp(total_saldo));

      $("#porcnt_sald_prov").html(redondearExp(porcentaj_saldo));
      
    }
  }).fail( function(e) { ver_errores(e); } );
}

function total_pagos_detraccion(id_subcontrato) {

  $("#t_detaccion").html("");
  $("#t_detacc_porc").html("");

  var monto_pagar_detracc= ((totattotal * 10) / 100);

  $("#t_detaccion").html(formato_miles(monto_pagar_detracc));
  $("#t_detacc_porc").html(10);

  var  porcentaj_saldo=0; var porcentaj_deposito_a_la_fecha=0; var total_saldo=0;

  $(".monto_total_deposito_detracc").html("");
  $(".porcent_detracc").html("");
  $("#saldo_detracc").html("");
  $("#porcnt_saldo_detracc").html("");


  $.post("../ajax/sub_contrato.php?op=total_pagos_detrac", { idsubcontrato: id_subcontrato }, function (e, status) {

    e = JSON.parse(e);  console.log(e);  

    if (e.status == true) {
      if (e.data.monto_parcial_deposito==null || e.data.monto_parcial_deposito==""  || e.data.monto_parcial_deposito==0) {

        $(".monto_total_deposito_detracc").html('0.00');

        $(".porcent_detracc").html("0.00");
        
        $("#saldo_detracc").html(formato_miles(monto_pagar_detracc));

        $("#porcnt_saldo_detracc").html('100.00');
        
      } else {
        
        $(".monto_total_deposito_detracc").html(formato_miles(e.data.monto_parcial_deposito));

        $(".porcent_detracc").html(redondearExp((e.data.monto_parcial_deposito * 100) / monto_pagar_detracc));

        porcentaj_deposito_a_la_fecha= ((e.data.monto_parcial_deposito * 100) / monto_pagar_detracc).toFixed(4);

        total_saldo=(parseFloat(monto_pagar_detracc)- parseFloat(e.data.monto_parcial_deposito));

        porcentaj_saldo=((total_saldo*porcentaj_deposito_a_la_fecha)/e.data.monto_parcial_deposito);

        $("#saldo_detracc").html(redondearExp(total_saldo));

        $("#porcnt_saldo_detracc").html(redondearExp(porcentaj_saldo));
        
      }
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//captura_opicion tipopago
function captura_op() {

  $("#cuenta_destino_pago").val("");

  if ($("#tipo_pago").select2("val") == "Proveedor") {
    $("#cuenta_destino_pago").val("");
    $("#cuenta_destino_pago").val(cuenta_bancaria);
  }

  if ($("#tipo_pago").select2("val") == "Detraccion") {
    $("#cuenta_destino_pago").val("");
    $("#cuenta_destino_pago").val(cuenta_detracciones);
  }

}

//validando excedentes
function validando_excedentes() {
  
  var monto_entrada             = parseFloat($("#monto_pago").val());    
  var total_deposito_provedor   =  parseFloat(quitar_formato_miles($('.monto_total_deposito_prov').text()));
  var total_deposito_detraccion =  parseFloat(quitar_formato_miles($('.monto_total_deposito_detracc').text()));

  var total_apagar              =  parseFloat(quitar_formato_miles($("#total_apagar").text()));  

  var total_suma =  total_deposito_provedor + total_deposito_detraccion + monto_entrada;

  if (total_suma > total_apagar) { 
    toastr_error('Excedido!!','ERROR monto excedido al total del monto a pagar.',700);  
  } else {
    toastr_success('OK!!','Monto Aceptado.',700);
  }
}

//Función limpiar pagos
function limpiar_pagos() {
  $("#idpago_subcontrato").val("");
  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");
  $("#monto_pago").val("");
  $("#numero_op_pago").val("");
  $("#cuenta_destino_pago").val("");
  $("#descripcion_pago").val("");
  $("#fecha_pago").val("");
  $("#numero_op_pago").val("");
  $("#banco_pago").val("").trigger("change");

  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Guardar y editar
function guardaryeditar_pago(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-add-pago-subcontrato")[0]);

  $.ajax({
    url: "../ajax/sub_contrato.php?op=guardaryeditar_pago",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          toastr.success("servicio registrado correctamente");

          $("#modal-agregar-pago").modal("hide");

          tabla.ajax.reload(null, false);
          tabla_pagos_proveedor.ajax.reload(null, false);
          tabla_pagos_detraccion.ajax.reload(null, false);
          limpiar_pagos();
          total_pagos_proveedor(id_subcontrato);
          total_pagos_detraccion(id_subcontrato);

        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_pago_subcontrato").html('Guardar Cambios').removeClass('disabled');      
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_pago_subcontrato").css({"width": percentComplete+'%'});
          $("#barra_progress_pago_subcontrato").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_pago_subcontrato").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_pago_subcontrato").css({ width: "0%",  });
      $("#barra_progress_pago_subcontrato").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_pago_subcontrato").css({ width: "0%", });
      $("#barra_progress_pago_subcontrato").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_pagos(idpago_subcontrato) {
  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();
  limpiar_pagos();

  $("#modal-agregar-pago").modal("show");

  $("#h4_mostrar_beneficiario").html("");
  $("#beneficiario_pago").val("");
  $("#idpago_subcontrato").val("");
  $("#idsubcontrato_pago").val("");

  $("#banco_pago").val("").trigger("change");
  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");

  $.post("../ajax/sub_contrato.php?op=mostrar_pagos", { idpago_subcontrato: idpago_subcontrato }, function (e, status) {

    e = JSON.parse(e); //console.log('..........'); console.log(e);  

    if (e.status == true) {
      $("#forma_pago").val(e.data.forma_pago).trigger("change");
      $("#tipo_pago").val(e.data.tipo_pago).trigger("change");
      $("#banco_pago").val(e.data.idbancos).trigger("change");

      $("#idpago_subcontrato").val(e.data.idpago_subcontrato);
      $("#idsubcontrato_pago").val(e.data.idsubcontrato);
      $("#beneficiario_pago").val(e.data.beneficiario);
      $("#h4_mostrar_beneficiario").html(e.data.beneficiario);
      $("#cuenta_destino_pago").val(e.data.cuenta_destino);
      $("#titular_cuenta_pago").val(e.data.titular_cuenta);
      $("#fecha_pago").val(e.data.fecha_pago);
      $("#monto_pago").val(e.data.monto);
      $("#numero_op_pago").val(e.data.numero_operacion);
      $("#descripcion_pago").val(e.data.descripcion);

      /**-------------------------*/
      if (e.data.comprobante == "" || e.data.comprobante == null  ) {

        $("#doc2_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
        $("#doc2_nombre").html('');
        $("#doc_old_2").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_2").val(e.data.comprobante); 
        $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);      
        // cargamos la imagen adecuada par el archivo
        $("#doc2_ver").html(doc_view_extencion(e.data.comprobante, 'sub_contrato', 'comprobante_pago', '100%'));
          
      }
      
      $('.jq_image_zoom').zoom({ on:'grab' }); 

      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide(); 
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function activar_pagos(idpago_subcontrato) {
  Swal.fire({
    title: "¿Está Seguro de  Activar el registro?",
    text: "Registro  activado",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/sub_contrato.php?op=activar_pagos", { idpago_subcontrato: idpago_subcontrato }, function (e) {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Activado!", "Tu registro ha sido activado.", "success");
          if (tabla) { tabla.ajax.reload(null, false); }
          if (tabla_pagos_proveedor) { tabla_pagos_proveedor.ajax.reload(null, false); }
          if (tabla_pagos_detraccion) { tabla_pagos_detraccion.ajax.reload(null, false); }
          total_pagos_proveedor(id_subcontrato); 
          total_pagos_detraccion(id_subcontrato);
        } else {
          ver_errores(e);
        }        
      }).fail( function(e) { ver_errores(e); } );      
    }
  });      
}

function eliminar_pagos(idpago_subcontrato, nombre) {

  crud_eliminar_papelera(
    "../ajax/sub_contrato.php?op=desactivar_pagos",
    "../ajax/sub_contrato.php?op=eliminar_pagos", 
    idpago_subcontrato, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false); tabla_pagos_proveedor.ajax.reload(null, false); tabla_pagos_detraccion.ajax.reload(null, false); },
    function(){ total_pagos_proveedor(id_subcontrato); total_pagos_detraccion(id_subcontrato); },
    false, 
    false,
    false
  );  
}

function ver_modal_vaucher_pagos(comprobante, nombre){
  $('.tile-modal-comprobante').html(nombre); 
  $('#modal-ver-comprobante').modal("show");
  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'sub_contrato', 'comprobante_pago', '100%', '550'));

  if (DocExist(`dist/docs/sub_contrato/comprobante_pago/${comprobante}`) == 200) {
    $("#iddescargar").attr("href","../dist/docs/sub_contrato/comprobante_pago/"+comprobante).attr("download", nombre).removeClass("disabled");
    $("#ver_completo").attr("href","../dist/docs/sub_contrato/comprobante_pago/"+comprobante).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }

  $('.jq_image_zoom').zoom({ on:'grab' }); 
}

// cuando seleciona FOrMA DE PAGO
function select_forma_pago() {
  var forma_pago = $("#forma_pago").select2("val");  
  if (forma_pago == "Efectivo") {
    $(".validar_fp").hide();
    $("#tipo_pago").val("Proveedor").trigger("change");
    $("#banco_pago").val("1").trigger("change");    
  } else {
    $(".validar_fp").show();
  }  
}

// .....:::::::::::::::::::::::::::::::::::::  C R U D   F A C T U R A S  .....:::::::::::::::::::::::::::::::::::::


init();

// .....::::::::::::::::::::::::::::::::::::: F O R M    V A L I D A T E  :::::::::::::::::::::::::::::::::::::::..

// funcion para validar antes de guardar sub contrato
$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_pago").on("change", function () { $(this).trigger("blur"); });
  $("#banco_pago").on("change", function () { $(this).trigger("blur"); });

  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").on("change", function () { $(this).trigger("blur"); });
  $("#forma_de_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });

  $("#form-agregar-sub-contrato").validate({
    rules: {
      idproveedor: { required: true },
      forma_de_pago: { required: true },
      tipo_comprobante: { required: true },
      fecha_subcontrato: { required: true },
      costo_parcial:{required: true},
      val_igv: { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idproveedor: {
        required: "Por favor un proveedor", 
      },
      forma_de_pago: {
        required: "Por favor una forma de pago", 
      },
      tipo_comprobante: {
        required: "Por favor seleccionar tipo comprobante", 
      },
      fecha_subcontrato: {
        required: "Por favor ingrese una fecha", 
      },
      costo_parcial: {
        required: "Ingrese costo_parcial.",
      },
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
      guardaryeditar(e);      
    },

  });  

  $("#form-add-pago-subcontrato").validate({
    rules: {

      forma_pago: { required: true },
      tipo_pago: { required: true },
      banco_pago: { required: true },
      fecha_pago: { required: true },
      monto_pago: { required: true },
      numero_op_pago: { minlength: 1 },
      descripcion_pago: { minlength: 1 },
      titular_cuenta_pago: { minlength: 1 },
    },
    messages: {

      forma_pago: {
        required: "Por favor selecione una forma de pago",
      },
      tipo_pago: {
        required: "Por favor selecione un tipo de pago",
      },
      banco_pago: {
        required: "Por favor selecione un banco",
      },
      fecha_pago: {
        required: "Por favor ingresar una fecha",
      },
      monto_pago: {
        required: "Por favor ingresar el monto a pagar",
      },
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
      guardaryeditar_pago(e);
    },
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#idproveedor").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#forma_de_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#banco_pago").rules("add", { required: true, messages: { required: "Campo requerido" } }); 
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { }  else{    
    var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; console.log(ruc);
    $('#ruc_proveedor').val(ruc);
  }
}

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

