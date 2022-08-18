var tabla_item;
var tabla_concreto;
var tabla_resumen;

var id_proyecto_r = '', idtipo_tierra_r = '', columna_calidad_r = '', columna_descripcion_r = '', nombre_item_r = '', fecha_1_r = '', fecha_2_r = '', id_proveedor_r = '', comprobante_r = '';

//Función que se ejecuta al inicio
function init() {
  
  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");  

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lConcretoAgregado").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  tbla_principal_item(localStorage.getItem('nube_idproyecto'));
  lista_de_items(localStorage.getItem('nube_idproyecto'));
  tbla_principal_resumen(localStorage.getItem('nube_idproyecto'));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#filtro_proveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);


  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_items").on("click", function (e) { $("#submit-form-items").submit(); });
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

// :::::::::::::::::::::::::: S E C C I O N    I T E M  ::::::::::::::::::::::::::
//Función limpiar
function limpiar_form_item() {

  $("#guardar_registro_items").html('Guardar Cambios').removeClass('disabled');
  
  $("#idtipo_tierra").val("");  
  $("#nombre_item").val("");
  $('#columna_calidad').prop('checked', false);
  $("#columna_descripcion").prop("checked", false);
  $("#descripcion_item").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function tbla_principal_item(id_proyecto) {
  tabla_item = $("#tabla-items").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3], }, orientation: 'landscape', pageSize: 'LEGAL', },       
    ],
    ajax: {
      url: `../ajax/concreto_agregado.php?op=tbla_principal_item&id_proyecto=${id_proyecto}`,
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
      //{ targets: [11,12,13], visible: false, searchable: false, },  
    ],
  }).DataTable();
}

//Función para guardar o editar
function guardar_y_editar_items(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-items")[0]);

  $.ajax({
    url: "../ajax/concreto_agregado.php?op=guardar_y_editar_items",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Item guardado correctamente", "success");

          tabla_item.ajax.reload(null, false);
          lista_de_items(localStorage.getItem('nube_idproyecto'));

          limpiar_form_item();

          $("#modal-agregar-items").modal("hide");
          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_items").html('Guardar Cambios').removeClass('disabled');
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
      $("#guardar_registro_items").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
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

function mostrar_item(idtipo_tierra) {
  limpiar_form_item(); //console.log(idproducto);

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-items").modal("show");

  $.post("../ajax/concreto_agregado.php?op=mostrar_item", { 'idtipo_tierra': idtipo_tierra }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {

      $("#idtipo_tierra").val(e.data.idtipo_tierra);  
      $("#nombre_item").val(e.data.nombre);      
      $("#descripcion_item").val(e.data.descripcion);     

      if (e.data.columna_calidad == "1") { $("#columna_calidad").prop("checked", true); } else { $("#columna_calidad").prop("checked", false); }       
      
      if (e.data.columna_descripcion == "1") { $("#columna_descripcion").prop("checked", true); } else { $("#columna_descripcion").prop("checked", false); }      
      
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

// ver detallles del registro
function verdatos_item(idproducto){

  $(".tooltip").removeClass("show").addClass("hidde");

  $('#datosinsumo').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div></div>`);

  var imagen_perfil =''; var btn_imagen_perfil = '';
  
  var ficha_tecnica=''; var btn_ficha_tecnica = '';

  $("#modal-ver-insumo").modal("show");

  $.post("../ajax/concreto_agregado.php?op=mostrar", { 'idproducto': idproducto }, function (e, status) {

    e = JSON.parse(e);  //console.log(e); 
    
    if (e.status) {     
    
      if (e.data.imagen != '') {

        imagen_perfil=`<img src="../dist/docs/material/img_perfil/${e.data.imagen}" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`
        
        btn_imagen_perfil=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/material/img_perfil/${e.data.imagen}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/material/img_perfil/${e.data.imagen}" download="PERFIL - ${removeCaracterEspecial(e.data.nombre)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {

        imagen_perfil=`<img src="../dist/docs/material/img_perfil/producto-sin-foto.svg" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`;
        btn_imagen_perfil='';

      }     

      if (e.data.ficha_tecnica != '') {
        
        ficha_tecnica =  doc_view_extencion(e.data.ficha_tecnica, 'material', 'ficha_tecnica', '100%');
        
        btn_ficha_tecnica=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/material/ficha_tecnica/${e.data.ficha_tecnica}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/material/ficha_tecnica/${e.data.ficha_tecnica}" download="Ficha Tecnica - ${removeCaracterEspecial(e.data.nombre)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {

        ficha_tecnica='Sin Ficha Técnica';
        btn_ficha_tecnica='';

      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th rowspan="2">${imagen_perfil}<br>${btn_imagen_perfil}</th>
                  <td> <b>Nombre: </b> ${e.data.nombre}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td> <b>Color: </b> ${e.data.nombre_color}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>U.M.</th>
                  <td>${e.data.nombre_medida}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Marca</th>
                    <td>${e.data.marca}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Con IGV</th>
                  <td>${(e.data.estado_igv==1? '<div class="myestilo-switch ml-2"><div class="switch-toggle"><input type="checkbox" id="my-switch-igv-2" checked disabled /><label for="my-switch-igv-2"></label></div></div>' : '<div class="myestilo-switch ml-3"><div class="switch-toggle"><input type="checkbox" id="my-switch-igv-2" disabled/><label for="my-switch-igv-2"></label></div></div>')}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Precio  </th>
                  <td>${e.data.precio_unitario}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Sub Total</th>
                  <td>${e.data.precio_sin_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${e.data.precio_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total </th>
                  <td>${e.data.precio_total}</td>
                </tr> 
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Modelo</th>
                  <td>${e.data.modelo}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Serie</th>
                  <td>${e.data.serie}</td>
                </tr>               
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td><textarea cols="30" rows="2" class="textarea_datatable" readonly="">${e.data.descripcion}</textarea></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Ficha Técnica</th>
                  <td> ${ficha_tecnica} <br>${btn_ficha_tecnica}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $("#datosinsumo").html(retorno_html);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar_item(idproducto, nombre) {

  crud_eliminar_papelera(
    "../ajax/concreto_agregado.php?op=desactivar_item",
    "../ajax/concreto_agregado.php?op=eliminar_item", 
    idproducto, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_item.ajax.reload(null, false); lista_de_items(localStorage.getItem('nube_idproyecto')); },
    false, 
    false, 
    false,
    false
  );
}

// :::::::::::::::::::::::::: S E C C I O N    C O N C R E T O    A G R E G A D O::::::::::::::::::::::::::
//Función limpiar
function limpiar_form_concreto() {

  $("#guardar_registro_items").html('Guardar Cambios').removeClass('disabled');
  
  $("#idconcreto_agregado").val("");  
  $("#idproveedor").val("").val("null").trigger("change");
  $('#fecha').val("");
  $("#nombre_dia").val("");
  $("#calidad").val("");
  $("#cantidad").val("");
  $("#precio_unitario").val("");
  $("#total").val("");
  $("#descripcion_concreto").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function lista_de_items(idproyecto) { 

  $(".lista-items").html(`<li class="nav-item"><a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a></li>`); 

  $.post("../ajax/concreto_agregado.php?op=lista_de_items", { 'idproyecto': idproyecto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);
    // e.data.idtipo_tierra
    if (e.status) {
      var data_html = '';

      e.data.forEach((val, index) => {
        data_html = data_html.concat(`
        <li class="nav-item">
          <a class="nav-link" onclick="tbla_principal_concreto('${val.idproyecto}', '${val.idtipo_tierra}', '${val.columna_calidad}', '${val.columna_descripcion}', '${val.nombre}', '', '', '', ''); show_hide_filtro();" id="tabs-for-concreto-tab" data-toggle="pill" href="#tabs-for-concreto" role="tab" aria-controls="tabs-for-concreto" aria-selected="false">${val.nombre}</a>
        </li>`);
      });

      $(".lista-items").html(`
        <li class="nav-item">
          <a class="nav-link" id="tabs-for-resumen-tab" data-toggle="pill" href="#tabs-for-resumen" role="tab" aria-controls="tabs-for-resumen" aria-selected="true" onclick="tbla_principal_resumen(${localStorage.getItem('nube_idproyecto')})">Resumen</a>
        </li>
        ${data_html}
      `); 
      //delay(function(){$('#tabs-for-resumen-tab').click();}, 100 );
      
      $('#tabs-for-resumen-tab').click();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función Listar
function tbla_principal_concreto(id_proyecto, idtipo_tierra, columna_calidad, columna_descripcion, nombre_item, fecha_1, fecha_2, id_proveedor, comprobante) {

  id_proyecto_r = id_proyecto; idtipo_tierra_r = idtipo_tierra; columna_calidad_r = columna_calidad; columna_descripcion_r = columna_descripcion; nombre_item_r = nombre_item; 
  fecha_1_r = fecha_1; fecha_2_r = fecha_2; id_proveedor_r = id_proveedor; comprobante_r = comprobante;
  
  $('.modal-title-detalle-items').html(nombre_item);

  $("#idtipo_tierra_c").val(idtipo_tierra);
  limpiar_form_concreto();

  var calidad_column = columna_calidad=='1' ?  { targets: [5], visible: true, searchable: true, }: { targets: [5], visible: false, searchable: false, } ;
  var descripcion_column = columna_descripcion=='1' ?  { targets: [2], visible: true, searchable: true, }: { targets: [2], visible: false, searchable: false, } ;
  
  var calidad = columna_calidad=='1' ?  $('.calidad').show():$('.calidad').hide() ;
  var descripcion = columna_descripcion=='1' ? $('.descripcion_concreto').show(): $('.descripcion_concreto').hide() ;

  // var export

  tabla_concreto = $("#tabla-concreto").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,7,8,9], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5,6,7,8,9], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3,4,5,6,7,8,9], }, orientation: 'landscape', pageSize: 'LEGAL', },       
    ],
    ajax: {
      url: `../ajax/concreto_agregado.php?op=tbla_principal_concreto&id_proyecto=${id_proyecto}&idtipo_tierra=${idtipo_tierra}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
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
      //{ targets: [11,12,13], visible: false, searchable: false, },  
      { targets: [4], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      { targets: [6,7,8], render: $.fn.dataTable.render.number( ',', '.', 2, '<div class="formato-numero-conta"><span>S/</span>' ) },
      calidad_column,
      descripcion_column,
    ],
  }).DataTable();

  total_concreto(id_proyecto, idtipo_tierra, columna_calidad, columna_descripcion, nombre_item, fecha_1, fecha_2, id_proveedor, comprobante);
}

function total_concreto(id_proyecto, idtipo_tierra, columna_calidad, columna_descripcion, nombre_item, fecha_1, fecha_2, id_proveedor, comprobante) {

  $(".total_cantidad_concreto").html('<i class="fas fa-spinner fa-pulse"></i>');  
  $(".total_precio_unitario_concreto").html('<i class="fas fa-spinner fa-pulse"></i>');      
  $(".total_concreto").html('<i class="fas fa-spinner fa-pulse"></i>');  

  $.post("../ajax/concreto_agregado.php?op=total_concreto", { 'id_proyecto':id_proyecto, 'idtipo_tierra': idtipo_tierra,'fecha_1': fecha_1,'fecha_2': fecha_2,'id_proveedor': id_proveedor,'comprobante': comprobante }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {

      $(".total_concreto_cantidad").html( formato_miles(e.data.cantidad));  
      $(".total_concreto_precio_unitario").html(formato_miles(e.data.precio_unitario));      
      $(".total_concreto").html(formato_miles(e.data.total));    

      $('.cargando_concreto').hide();

    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función para guardar o editar
function guardar_y_editar_concreto(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-concreto")[0]);

  $.ajax({
    url: "../ajax/concreto_agregado.php?op=guardar_y_editar_concreto",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Registro guardado correctamente", "success");
          tabla_concreto.ajax.reload(null, false); 
          limpiar_form_concreto();
          $("#modal-agregar-concreto").modal("hide");          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_concreto").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_concreto").css({"width": percentComplete+'%'});
          $("#barra_progress_concreto").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_concreto").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_concreto").css({ width: "0%",  });
      $("#barra_progress_concreto").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_concreto").css({ width: "0%", });
      $("#barra_progress_concreto").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar_concreto(idconcreto_agregado) {
  limpiar_form_item(); //console.log(idproducto);

  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();

  $("#modal-agregar-concreto").modal("show");

  $.post("../ajax/concreto_agregado.php?op=mostrar_concreto", { 'idconcreto_agregado': idconcreto_agregado }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {

      $("#idtipo_tierra_c").val(e.data.idtipo_tierra);  
      $("#idconcreto_agregado").val(e.data.idconcreto_agregado);      
      $("#idproveedor").val(e.data.idproveedor).trigger("change"); 
      $("#fecha").val(e.data.fecha);  
      $("#nombre_dia").val(e.data.nombre_dia);      
      $("#calidad").val(e.data.calidad); 
      $("#cantidad").val(e.data.cantidad);  
      $("#precio_unitario").val(e.data.precio_unitario);      
      $("#total").val(e.data.total); 
      $("#descripcion_concreto").val(e.data.detalle); 
      
      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function optener_dia_de_semana() {
  var fecha = $("#fecha").val();
  $("#nombre_dia").val(extraer_dia_semana_completo(fecha)); 
}

function calcular_total() {
  var cantidad        = es_numero($('#cantidad').val()) == true? parseFloat($('#cantidad').val()) : 0;
  var precio_unitario = es_numero($('#precio_unitario').val()) == true? parseFloat($('#precio_unitario').val()) : 0;
  
  $('#total').val(cantidad*precio_unitario);
}

//Función para desactivar registros
function eliminar_concreto(idproducto, nombre) {

  crud_eliminar_papelera(
    "../ajax/concreto_agregado.php?op=desactivar_concreto",
    "../ajax/concreto_agregado.php?op=eliminar_concreto", 
    idproducto, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_concreto.ajax.reload(null, false); tabla_resumen.ajax.reload(null, false); },
    false, 
    false, 
    false,
    false
  );
}

//ver ficha tecnica
function modal_ficha_tec(ficha_tecnica) {

  // ------------------------
  //$('.tile-modal-comprobante').html(nombre); 
  $("#modal-ver-ficha_tec").modal("show");
  $('#ver_fact_pdf').html(doc_view_extencion(ficha_tecnica, 'material', 'ficha_tecnica', '100%', '550'));

  if (DocExist(`dist/docs/material/ficha_tecnica/${ficha_tecnica}`) == 200) {
    $("#iddescargar").attr("href","../dist/docs/material/ficha_tecnica/"+ficha_tecnica).attr("download", 'ficha tecncia').removeClass("disabled");
    $("#ver_completo").attr("href","../dist/docs/material/ficha_tecnica/"+ficha_tecnica).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }
  $('.jq_image_zoom').zoom({ on:'grab' });
  $(".tooltip").removeClass("show").addClass("hidde");
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
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,1,2,3,4,5], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,1,2,3,4,5], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,1,2,3,4,5], }, orientation: 'landscape', pageSize: 'LEGAL', },       
    ],
    ajax: {
      url: `../ajax/concreto_agregado.php?op=tbla_principal_resumen&idproyecto=${idproyecto}`,
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
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [3,4,5], render: $.fn.dataTable.render.number( ',', '.', 2, '<div class="formato-numero-conta"><span>S/</span>' ) },
      //{ targets: [11,12,13], visible: false, searchable: false, },  
    ],
  }).DataTable();

  total_concreto_resumen(idproyecto);
}

function total_concreto_resumen(idproyecto) {

  $(".total_cantidad_resumen").html('<i class="fas fa-spinner fa-pulse"></i>');  
  $(".total_precio_unitario_resumen").html('<i class="fas fa-spinner fa-pulse"></i>');      
  $(".total_resumen").html('<i class="fas fa-spinner fa-pulse"></i>');  

  $.post("../ajax/concreto_agregado.php?op=total_resumen", { 'idproyecto': idproyecto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {

      $(".total_resumen_cantidad").html( formato_miles(e.data.cantidad));  
      $(".total_resumen_precio_unitario").html(formato_miles(e.data.precio_unitario));      
      $(".total_resumen").html(formato_miles(e.data.total));    

    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {   

  $('#idproveedor').on('change', function() { $(this).trigger('blur'); });

  $("#form-items").validate({
    rules: {
      nombre_item:      { required: true, minlength: 3, maxlength:100, },
      descripcion_item: { minlength: 3, maxlength:150, },
    },
    messages: {
      nombre_item:      { required: "Campo requerido.", minlength: "MÍNIMO 3 caracteres.",maxlength: "MÁXIMO 100 caracteres." },
      descripcion_item: { required: "Campo requerido.", minlength: "MÍNIMO 3 caracteres.", maxlength: "MÁXIMO 150 caracteres." },
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
      guardar_y_editar_items(e);
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
   
  tbla_principal_concreto(id_proyecto_r, idtipo_tierra_r, columna_calidad_r, columna_descripcion_r, nombre_item_r, fecha_1, fecha_2, id_proveedor, comprobante);
}

function show_hide_filtro() {
  $('.filtros-inputs').show();
}