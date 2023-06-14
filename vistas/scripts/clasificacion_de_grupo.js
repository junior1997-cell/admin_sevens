var tabla_item;
var tabla_concreto;
var tabla_resumen;

var id_proyecto_r = '', idclasificacion_grupo_r = '', columna_bombeado_r = '', nombre_grupo_r = '', fecha_1_r = '', fecha_2_r = '', id_proveedor_r = '', comprobante_r = '';

//Función que se ejecuta al inicio
function init() {
  
  //Activamos el "aside"
  $("#bloc_Tecnico").addClass("menu-open");  

  $("#mTecnico").addClass("active");

  $("#lConcretoAgregado").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  tbla_principal_grupo(localStorage.getItem('nube_idproyecto'));
  lista_de_grupo(localStorage.getItem('nube_idproyecto'));
  //tbla_principal_resumen(localStorage.getItem('nube_idproyecto'));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#filtro_proveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_grupo").on("click", function (e) { $("#submit-form-grupo").submit(); });
  $("#guardar_registro_concreto").on("click", function (e) { $("#submit-form-concreto").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });

  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  

  $('.jq_image_zoom').zoom({ on:'grab' });
  // Formato para telefono
  $("[data-mask]").inputmask();
}

$('.click-btn-fecha-inicio').on('click', function (e) {$('#filtro_fecha_inicio').focus().select(); });
$('.click-btn-fecha-fin').on('click', function (e) {$('#filtro_fecha_fin').focus().select(); });

// abrimos el navegador de archivos
//ficha tecnica
$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e,$("#doc2").attr("id")) });

// Eliminamos el doc 2
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

// :::::::::::::::::::::::::: S E C C I O N   G R U P O ::::::::::::::::::::::::::
//Función limpiar
function limpiar_form_grupo() {

  $("#guardar_registro_grupo").html('Guardar Cambios').removeClass('disabled');
  
  $("#idclasificacion_grupo").val("");  
  $("#nombre_grupo").val("");
  $("#descripcion_grupo").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function tbla_principal_grupo(id_proyecto) {
  tabla_item = $("#tabla-grupo").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3], }, title: 'Grupos - Concreto y Agregado' },      
    ],
    ajax: {
      url: `../ajax/clasificacion_de_grupo.php?op=tbla_principal_grupo&id_proyecto=${id_proyecto}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {    
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: opciones
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center text-nowrap"); }
      // columna: nombre
      if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); }
      // columna: columan servicio
      if (data[3] != '') { $("td", row).eq(3).addClass("text-center text-nowrap"); }
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
      //{ targets: [5], visible: false, searchable: false, },  
    ],
  }).DataTable();
}

//Función para guardar o editar
function guardar_y_editar_grupo(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-grupo")[0]);

  $.ajax({
    url: "../ajax/clasificacion_de_grupo.php?op=guardar_y_editar_grupo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {

          Swal.fire("Correcto!", "Grupo guardado correctamente", "success");
          tabla_item.ajax.reload(null, false);
          lista_de_grupo(localStorage.getItem('nube_idproyecto'));
          limpiar_form_grupo();
          $("#modal-agregar-grupo").modal("hide");
          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_grupo").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_items").css({"width": percentComplete+'%'});
          $("#barra_progress_items").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_grupo").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_items").css({ width: "0%",  });
      $("#barra_progress_items").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_items").css({ width: "0%", });
      $("#barra_progress_items").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_grupo(idclasificacion_grupo) {
  limpiar_form_grupo(); //console.log(idproducto);

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-grupo").modal("show");

  $.post("../ajax/clasificacion_de_grupo.php?op=mostrar_grupo", { 'idclasificacion_grupo': idclasificacion_grupo }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status == true) {

      $("#idclasificacion_grupo").val(e.data.idclasificacion_grupo);  
      $("#nombre_grupo").val(e.data.nombre);      
      $("#descripcion_grupo").val(e.data.descripcion);     

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}


//Función para desactivar registros
function eliminar_grupo(idclasificacion_grupo, nombre) {

  crud_eliminar_papelera(
    "../ajax/clasificacion_de_grupo.php?op=desactivar_grupo",
    "../ajax/clasificacion_de_grupo.php?op=eliminar_grupo", 
    idclasificacion_grupo, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_item.ajax.reload(null, false); lista_de_grupo(localStorage.getItem('nube_idproyecto')); },
    false, 
    false, 
    false,
    false
  );
}

// :::::::::::::::::::::::::: S E C C I O N   C O M P R A   Y   S U B C O N T R A T O ::::::::::::::::::::::::::

function lista_de_grupo(idproyecto) { 

  $(".lista-items").html(`<li class="nav-item"><a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a></li>`); 

  $.post("../ajax/clasificacion_de_grupo.php?op=lista_de_grupo", { 'idproyecto': idproyecto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);
    // e.data.idclasificacion_grupo
    if (e.status) {
      var data_html = '';

      e.data.forEach((val, index) => {
        data_html = data_html.concat(`
        <li class="nav-item">
          <a class="nav-link" onclick="delay(function(){tbla_principal_compra_subcontrato('${idproyecto}', '${val.idclasificacion_grupo}', '${val.columna_servicio_bombeado}', '${val.nombre}', '', '', '', '');}, 50 ); show_hide_filtro();" id="tabs-for-concreto-tab" data-toggle="pill" href="#tabs-for-concreto" role="tab" aria-controls="tabs-for-concreto" aria-selected="false">${val.nombre} <small class="text-dark">(${val.cant_por_producto})</small></a>
        </li>`);
      });

      $(".lista-items").html(`
        <li class="nav-item">
          <a class="nav-link" id="tabs-for-resumen-tab" data-toggle="pill" href="#tabs-for-resumen" role="tab" aria-controls="tabs-for-resumen" aria-selected="true" onclick="tbla_principal_resumen(${localStorage.getItem('nube_idproyecto')})">Resumen</a>
        </li>
        ${data_html}
      `);  
      $('#tabs-for-resumen-tab').click();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función Listar
function tbla_principal_compra_subcontrato(id_proyecto, idclasificacion_grupo, columna_bombeado, nombre_grupo, fecha_1, fecha_2, id_proveedor, comprobante) {

  id_proyecto_r = id_proyecto; idclasificacion_grupo_r = idclasificacion_grupo; columna_bombeado_r = columna_bombeado;  nombre_grupo_r = nombre_grupo; 
  fecha_1_r = fecha_1; fecha_2_r = fecha_2; id_proveedor_r = id_proveedor; comprobante_r = comprobante;
    
  $('.modal-title-detalle-items').html(nombre_grupo);

  var cantidad = 0, subtotal = 0, bombeado = 0, descuento = 0, total_compra = 0;

  $(".total_concreto_cantidad").html('0.00');  
  $(".total_concreto_subtotal").html('0.00');  
  $(".total_concreto_bombeado").html('0.00');
  $(".total_concreto_descuento").html('0.00');
  $(".total_concreto").html('0.00');

  tabla_concreto = $("#tabla-concreto").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,7,8,9,10], }, title: removeCaracterEspecial(nombre_grupo), }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,7,8,9,10], }, title: removeCaracterEspecial(nombre_grupo), }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3,4,5,6,7,8,9,10], }, title: removeCaracterEspecial(nombre_grupo), orientation: 'landscape', pageSize: 'LEGAL', },       
    ],
    ajax: {
      url: `../ajax/clasificacion_de_grupo.php?op=tbla_principal_compra_subcontrato&id_proyecto=${id_proyecto}&idclasificacion_grupo=${idclasificacion_grupo}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {    
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center text-nowrap"); }
      // columna: cantidad 
      if (data[5] != '' || data[5] == 0) { $("td", row).eq(5).addClass("text-center"); $(".total_concreto_cantidad").html( formato_miles( cantidad += parseFloat(data[5]) ) ); }
      // columna: precio
      //if (data[6] != '') { $(".total_concreto_subtotal").html(formato_miles( subtotal += parseFloat(data[6]) ) ); }
      // columna: descuento
      if (data[7] != '') { $(".total_concreto_descuento").html(formato_miles( descuento += parseFloat(data[7]) ) ); }
      // columna: subtotal
      if (data[8] != '') { $(".total_concreto_subtotal").html(formato_miles( subtotal += parseFloat(data[8]) )); }   
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
      { targets: [6,7,8], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      //{ targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();

  $(tabla_concreto).ready(function () { 
    $('.cargando_concreto').hide(); 
    // var elementsArray = document.getElementById("reload-all");
    // elementsArray.style.display = 'none'; 
  });
  
}

// nos se usa
function total_compra_subcontrato(id_proyecto, idclasificacion_grupo, fecha_1, fecha_2, id_proveedor, comprobante) {

  // $(".total_cantidad_concreto").html('<i class="fas fa-spinner fa-pulse"></i>');  
  // $(".total_precio_unitario_concreto").html('<i class="fas fa-spinner fa-pulse"></i>');      
  // $(".total_concreto").html('<i class="fas fa-spinner fa-pulse"></i>');  

  $.post("../ajax/clasificacion_de_grupo.php?op=total_compra_subcontrato", { 'id_proyecto':id_proyecto, 'idclasificacion_grupo': idclasificacion_grupo,'fecha_1': fecha_1,'fecha_2': fecha_2,'id_proveedor': id_proveedor,'comprobante': comprobante }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {

      // $(".total_concreto_cantidad").html( formato_miles(e.data.cantidad));  
      // $(".total_concreto_subtotal").html(formato_miles(e.data.subtotal));  
      // $(".total_concreto_descuento").html(formato_miles(e.data.descuento));         
      // $(".total_concreto").html(formato_miles(e.data.total_compra));    

      $('.cargando_concreto').hide();

    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

// :::::::::::::::::::::::::: S E C C I O N   C O M P R A ::::::::::::::::::::::::::

function ver_detalle_compras(idcompra_proyecto, id_insumo) {

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');
  $(".tooltip").remove();
  $("#modal-ver-compras").modal("show");

  $.post(`../ajax/ajax_general.php?op=detalle_compra_de_insumo&id_compra=${idcompra_proyecto}&id_insumo=${id_insumo}`, function (e) {
    e = JSON.parse(e);
    if (e.status == true) {
      $(".detalle_de_compra").html(e.data); 
      $("#cargando-5-fomulario").show();
      $("#cargando-6-fomulario").hide();

      $("#print_pdf_compra").removeClass('disabled');
      $("#print_pdf_compra").attr('href', `../reportes/pdf_compra.php?id=${idcompra_proyecto}&op=insumo` );
      $("#excel_compra").removeClass('disabled');
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// ver imagen grande del producto agregado a la compra
function ver_img_material(img, nombre) {
  $("#ver_img_insumo_o_activo_fijo").html(doc_view_extencion(img, '', '', '100%'));
  $(".nombre-img-material").html(nombre);
  $('.jq_image_zoom').zoom({ on:'grab' });
  $("#modal-ver-img-material").modal("show");
}

function export_excel_detalle_factura() {
  $tabla = document.querySelector("#tabla_detalle_compra_de_insumo");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Detalle comprobante", //Nombre del archivo de Excel
    sheetname: "detalle factura", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.tabla_detalle_compra_de_insumo.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}

// :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  C O M P R A ::::::::::::::::::::::::::

function comprobante_compras( num_orden, idcompra_proyecto, num_comprobante, proveedor, fecha) {
   
  tbla_comprobantes_compras(idcompra_proyecto, num_orden);   

  $('.titulo-comprobante-compra').html(`Comprobante: <b>${num_orden}. ${num_comprobante} - ${format_d_m_a(fecha)}</b>`);
  $("#modal-tabla-comprobantes-compra").modal("show"); 
}

function tbla_comprobantes_compras(id_compra, num_orden) {
  tabla_comprobantes = $("#tabla-comprobantes-compra").dataTable({
    responsive: true, 
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [ ],
    ajax: {
      url: `../ajax/clasificacion_de_grupo.php?op=tbla_comprobantes_compra&id_compra=${id_compra}&num_orden=${num_orden}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    }, 
    createdRow: function (row, data, ixdex) {
      // columna: 1
      if (data[3] != '') { $("td", row).eq(3).addClass("text-nowrap"); }
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
      { targets: [3], render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY hh:mm:ss a'), },
      //{ targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();
}

// :::::::::::::::::::::::::: S E C C I O N   S U B C O N T R A T O ::::::::::::::::::::::::::
function comprobante_subcontrato(cont, file, file_name) {
  $('.tile-modal-comprobante-subontrato').html(file_name); 
  $('#modal-ver-comprobante-subontrato').modal("show");
  $('.html-comprobante-subcontrato').html(doc_view_download_expand(file, 'dist/docs/sub_contrato/comprobante_subcontrato', removeCaracterEspecial(file_name), '100%', '500'));

  $('.jq_image_zoom').zoom({ on:'grab' });  
}

function ver_detalle_subcontrato(id) {
  $("#modal-ver-datos-sub-contrato").modal("show");

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/clasificacion_de_grupo.php?op=ver_datos_subcontrato", { idsubcontrato: id }, function (e , status) {

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
                  <td>${format_d_m_a(e.data.fecha_subcontrato)}</td>
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
                  <td>${formato_miles(e.data.subtotal)}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${formato_miles(e.data.igv)}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${formato_miles(e.data.costo_parcial)}</td>
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
// :::::::::::::::::::::::::: S E C C I O N    R E S U M E N ::::::::::::::::::::::::::
//Función Listar
function tbla_principal_resumen(idproyecto) {

  $('.filtros-inputs').hide(); 

  tabla_resumen = $("#tabla-resumen").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,1,2,3], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,1,2,3], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,1,2,3], }, orientation: 'landscape', pageSize: 'LEGAL', },       
    ],
    ajax: {
      url: `../ajax/clasificacion_de_grupo.php?op=tbla_principal_resumen&idproyecto=${idproyecto}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {    
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }      
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 2 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api1.column( 2 ).footer() ).html( `<span class="float-left">S/</span> <span class="float-right">${formato_miles(total1)}</span>` );
      var api2 = this.api(); var total2 = api2.column( 3 ).data().reduce( function ( a, b ) { return  (parseFloat(a) + parseFloat( b)) ; }, 0 )
      $( api2.column( 3 ).footer() ).html( `<span class="float-left">S/</span> <span class="float-right">${formato_miles(total2)}</span>` );      
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      // { targets: [3], render: $.fn.dataTable.render.number( ',', '.', 2) },
      { targets: [2,3], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      //{ targets: [11,12,13], visible: false, searchable: false, },  
    ],
  }).DataTable();

}


init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {   

  $('#idproveedor').on('change', function() { $(this).trigger('blur'); });

  $("#form-grupo").validate({
    rules: {
      nombre_grupo:      { required: true, minlength: 3, maxlength:100, },
      idclasificacion_grupo: { minlength: 3, maxlength:150, },
    },
    messages: {
      nombre_grupo:      { required: "Campo requerido.", minlength: "MÍNIMO 3 caracteres.",maxlength: "MÁXIMO 100 caracteres." },
      idclasificacion_grupo: { required: "Campo requerido.", minlength: "MÍNIMO 3 caracteres.", maxlength: "MÁXIMO 150 caracteres." },
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
      guardar_y_editar_grupo(e);
    },
  });

  $("#form-concreto").validate({
    rules: {
      idproveedor:          { required: true,  },
      fecha:                {required: true,   },
      nombre_dia:           {required: true, minlength: 4, maxlength:15,},
      calidad:              { min:0.01},
      cantidad:             {required: true, min:0.01},
      precio_unitario:      {required: true, min:0.01},
      total:                {required: true, min:0.01},
      descripcion_concreto: { minlength: 3,},
    },
    messages: {
      idproveedor:          { required: "Campo requerido.",  },
      fecha:                { required: "Campo requerido.",  },
      nombre_dia:           { required: "Campo requerido.", minlength: "MÍNIMO 4 caracteres.", maxlength: "MÁXIMO 15 caracteres." },
      calidad:              { min: "MÍNIMO 0.01",  },
      cantidad:             { required: "Campo requerido.", min: "MÍNIMO 0.01",  },
      precio_unitario:      { required: "Campo requerido.", min: "MÍNIMO 0.01",  },
      total:                { required: "Campo requerido.", min: "MÍNIMO 0.01",  },
      descripcion_concreto: {  minlength: "MÍNIMO 3 caracteres.",  },
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
      guardar_y_editar_concreto(e);
    },
  });

  $('#idproveedor').rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function cargando_search() {
  // var elementsArray = document.getElementById("reload-all");
  // elementsArray.style.display = '';
  $('.cargando_concreto').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
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

  $('.cargando_concreto').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_proveedor} ${nombre_comprobante}...`);
  //console.log(fecha_1, fecha_2, id_proveedor, comprobante);
   
  tbla_principal_compra_subcontrato(id_proyecto_r, idclasificacion_grupo_r, columna_bombeado_r, nombre_grupo_r, fecha_1, fecha_2, id_proveedor, comprobante);
}

function show_hide_filtro() {
  $('.filtros-inputs').show();
}