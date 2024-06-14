var tabla_almacen_resumen;
var tabla_almacen_detalle;
var idproyecto = "";
var textproyecto = "";
var nombre_almacen_transf = "";
var id_almacen_transf = "";
var tabla_detalle_almacen_general = "";


function init() {

  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");
  $("#mRecurso").addClass("active");
  $("#lAlmacenGeneral").addClass("active");

  lista_de_items();
  tabla_principal('todos');

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════   Otro_Almacen
  lista_select2(`../ajax/almacen_general.php?op=select2_proyect_almacen&tipo_transf=Proyecto&id_almacen_g=0`, '#proyecto_ag', null);
  lista_select2(`../ajax/almacen_general.php?op=select2_proyect_almacen&tipo_transf=Otro_Almacen&id_almacen_g=0`, '#almacen_tup', null);
  lista_select2(`../ajax/almacen_general.php?op=select2Productos`, '#producto_tup', null, '.cargando_producto_tup');

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_almacen").on("click", function (e) { $("#submit-form-almacen-general").submit(); }); //CREATE UN ALMACEN GENERAL
  $("#guardar_registro_otro_almacen").on("click", function (e) { $("#submit-form-otro-almacen").submit(); }); //ADD INFO AL ALMACEN GENERAL
  $("#guardar_registro_proyecto_almacen").on("click", function (e) { $("#submit-form-proyecto_almacen").submit(); }); //ADD TRANFERENCIAS
  $("#guardar_registro_almacen_tup").on("click", function (e) { if ($(this).hasClass('send-data') == false) { $("#submit-form-almacen-tup").submit(); } });  //INGRESO DIRECTO

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════ 
  $("#proyecto_ag").select2({ theme: "bootstrap4", placeholder: "Seleccinar proyecto", allowClear: true, });
  $("#producto_tup").select2({ theme: "bootstrap4", placeholder: "Seleccinar Insumo", allowClear: true, });
  $("#almacen_tup").select2({ theme: "bootstrap4", placeholder: "Seleccinar Insumo", allowClear: true, });
  $("#tranferencia").select2({ theme: "bootstrap4", placeholder: "Selec.", allowClear: true, });
  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  // $('#precio_unitario').number( true, 2 );
}

//select Productos Comprados ADD EN ALMACEN GENERAL
function reload_proyect_ag(pry) {

  idproyecto = $(pry).select2('val');

  if (idproyecto == null || idproyecto == '') {

    $('.select_init_recurso').show();
    $('.select_recurso').hide();

  } else {

    $('.select_init_recurso').hide();
    $('.select_recurso').show();

    lista_select2(`../ajax/almacen_general.php?op=select2ProductosComprados&idproyecto=${idproyecto}`, '#producto_ag', null, '.cargando_productos_ag');

    $("#producto_ag").select2({ theme: "bootstrap4", placeholder: "Selecione producto", allowClear: true, });

  }

}

//Función limpiar inputs AL add general
function limpiar() {

  $("#guardar_registro_almacen").html('Guardar Cambios').removeClass('disabled');

  $("#idalmacen_general").val("");
  $("#nombre_almacen").val("");
  $("#descripcion").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//LISTAR  ALAMANCENES GENERALES
function lista_de_items() {

  $(".lista-items").html(`<li class="nav-item"><a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a></li>`);

  $.post("../ajax/almacen_general.php?op=lista_de_categorias", function (e, status) {

    e = JSON.parse(e); console.log(e);
    // e.data.idtipo_tierra
    if (e.status == true) {
      var data_html = '';

      e.data.forEach((val, index) => {
        data_html = data_html.concat(`
        <li class="nav-item">
          <a class="nav-link" onclick="delay(function(){tabla_detalle('${val.idcategoria}','${val.nombre}')}, 50 );" id="tabs-for-detalle-tab" data-toggle="pill" href="#tabs-for-detalle" role="tab" aria-controls="tabs-for-detalle" aria-selected="false">${val.nombre}</a>
        </li>`);
      });

      $(".lista-items").html(`
        <li class="nav-item">
          <a class="nav-link active" onclick="delay(function(){tabla_principal('todos')}, 50 );" id="tabs-for-almacen-tab" data-toggle="pill" href="#tabs-for-almacen" role="tab" aria-controls="tabs-for-almacen" aria-selected="true">Todos</a>
        </li>
        ${data_html}
      `);
    } else {
      ver_errores(e);
    }
  }).fail(function (e) { ver_errores(e); });
}

//LISTAR ALAMACENES.
function tabla_principal(id_categoria) {

  $('.btn_add_almacen').show(); $('.btn_add_prod_almacen').hide();

  tabla_almacen_resumen = $("#tabla-almacen").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0, 2, 3], } },
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0, 2, 3], } },
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0, 2, 3], } },
    ],
    ajax: {
      url: `../ajax/almacen_general.php?op=tabla_principal&id_categoria=${id_categoria}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: op
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: code
      if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); }
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
      // { targets: [10,11], visible: false, searchable: false, },
      // { targets: [7], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();

}

//Función para guardar o editar
function guardar_y_editar_almacen(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-almacen-general")[0]);

  $.ajax({
    url: "../ajax/almacen_general.php?op=guardar_y_editar_almacen",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {
          Swal.fire("Correcto!", "Trabajador guardado correctamente", "success");
          tabla_almacen_resumen.ajax.reload(null, false); lista_de_items();
          limpiar();
          $("#modal-agregar-almacen-general").modal("hide");
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!", 'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }

      $("#guardar_registro_almacen").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({ "width": percentComplete + '%' }).text(percentComplete.toFixed(2) + " %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_almacen").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%", }).text("0%");
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", }).text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idalmacen_general) {
  limpiar();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-almacen-general").modal("show");

  $.post("../ajax/almacen_general.php?op=mostrar", { 'idalmacen_general': idalmacen_general }, function (e, status) {

    e = JSON.parse(e); console.log(e);

    if (e.status == true) {
      // input no usados
      $("#idalmacen_general").val(e.data.idalmacen_general);
      $("#nombre_almacen").val(e.data.nombre_almacen);
      $('#descripcion').val(e.data.descripcion);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail(function (e) { ver_errores(e); });
}

//Función para desactivar registros
function eliminar(idproducto, nombre) {
  //----------------------------

  crud_eliminar_papelera(
    "../ajax/almacen_general.php?op=desactivar",
    "../ajax/almacen_general.php?op=eliminar",
    idproducto,
    "!Elija una opción¡",
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`,
    function () { sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado.") },
    function () { sw_success('Eliminado!', 'Tu registro ha sido Eliminado.') },
    function () { tabla_almacen_resumen.ajax.reload(null, false) },
    false,
    false,
    false,
    false
  );

}

//================================================================
//--------------------INICIO ALMACEN GENERAL----------------------
//================================================================
// LISTAR TABLA ALMACEN POR ALMACEN
function tabla_detalle(id_categoria, nombre) {
  $('.tabla_detalle_almacen_g').hide(); $('.alerta_inicial').show();
  nombre_almacen_transf = nombre; id_almacen_transf = id_categoria;

  $('#idalmacen_general_ag').val(id_categoria); $('.nombre_almacen_g').html(nombre);

  $('.btn_add_almacen').hide(); $('.btn_add_prod_almacen').show();

  tabla_almacen_detalle = $("#tabla-detalle-almacen").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla    
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate"></i> ', className: "buttons-reload px-3 btn btn-sm btn-outline-info", action: function (e, dt, node, config) { if (tabla_almacen_detalle) { tabla_almacen_detalle.ajax.reload(null, false); toastr_success('Actualizado', 'Tabla actualizada'); } } },
      { extend: 'copy', exportOptions: { columns: [0, 1, 2, 3, 4], }, text: `<i class="fas fa-copy" ></i>`, className: "px-3 btn btn-sm btn-outline-dark", footer: true, },
      { extend: 'excel', exportOptions: { columns: [0, 1, 2, 3, 4], }, title: `LISTA DE PRODUCTOS ${nombre_almacen_transf}`, text: `<i class="far fa-file-excel fa-lg" ></i>`, className: "px-3 btn btn-sm btn-outline-success", footer: true, },
    ],
    ajax: {
      url: `../ajax/almacen_general.php?op=tabla_detalle&id_almacen=${id_categoria}&id_proyecto=${localStorage.getItem("nube_idproyecto")}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
      complete: function () {
        $(".buttons-reload").attr('data-toggle', 'tooltip').attr('data-original-title', 'Recargar');
        $(".buttons-copy").attr('data-toggle', 'tooltip').attr('data-original-title', 'Copiar');
        $(".buttons-excel").attr('data-toggle', 'tooltip').attr('data-original-title', 'Excel');
        $(".buttons-pdf").attr('data-toggle', 'tooltip').attr('data-original-title', 'PDF');
        $(".buttons-colvis").attr('data-toggle', 'tooltip').attr('data-original-title', 'Columnas');
        $('[data-toggle="tooltip"]').tooltip();
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: op
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: op
      if (data[2] != '') { $("td", row).eq(2).addClass("text-center"); }
      // columna: op
      if (data[3] != '') { $("td", row).eq(3).addClass("text-center"); }
      // columna: op
      if (data[4] != '') { $("td", row).eq(4).addClass("text-center"); }
      // columna: op
      if (data[5] != '') { $("td", row).eq(5).addClass("text-center"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      // { targets: [5], visible: false, searchable: false, },
    ],
  }).DataTable();
  // $('.tabla_detalle_almacen_g').show();  $('.alerta_inicial').hide();
}

function detalle_almacen_general(id_almacen_transf, idalmacen_general_resumen, nombre_producto) {

  tabla_detalle_almacen_general = $("#tabla_detalle_almacen_general").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate"></i> ', className: "buttons-reload px-3 btn btn-sm btn-outline-info", action: function (e, dt, node, config) { if (tabla_detalle_almacen_general) { tabla_detalle_almacen_general.ajax.reload(null, false); toastr_success('Actualizado', 'Tabla actualizada'); } } },
      { extend: 'copy', exportOptions: { columns: [0, 1, 2, 3, 4], }, text: `<i class="fas fa-copy" ></i>`, className: "px-3 btn btn-sm btn-outline-dark", footer: true, },
      { extend: 'excel', exportOptions: { columns: [0, 1, 2, 3, 4], }, title: `MOVIMIENTOS DEL PRODUCTO ${nombre_producto}`, text: `<i class="far fa-file-excel fa-lg" ></i>`, className: "px-3 btn btn-sm btn-outline-success", footer: true, },
    ],
    ajax: {
      url: `../ajax/almacen_general.php?op=tabla_detalle_almacen_general&id_almacen_transf=${id_almacen_transf}&idalmacen_general_resumen=${idalmacen_general_resumen}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
      complete: function () {
        $(".buttons-reload").attr('data-toggle', 'tooltip').attr('data-original-title', 'Recargar');
        $(".buttons-copy").attr('data-toggle', 'tooltip').attr('data-original-title', 'Copiar');
        $(".buttons-excel").attr('data-toggle', 'tooltip').attr('data-original-title', 'Excel');
        $(".buttons-pdf").attr('data-toggle', 'tooltip').attr('data-original-title', 'PDF');
        $(".buttons-colvis").attr('data-toggle', 'tooltip').attr('data-original-title', 'Columnas');
        $('[data-toggle="tooltip"]').tooltip();
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: op
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
      if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); }
      if (data[3] != '') { $("td", row).eq(3).addClass("text-center"); }

    },
    language: {
      lengthMenu: "Mostrar: _MENU_",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      // { targets: [5], visible: false, searchable: false, },
    ],
  }).DataTable();



  $('.alerta_inicial').hide(); $('.tabla_detalle_almacen_g').show();


}


function limpiar_form_otro_almacen() {

  $('#producto_ag').val('').trigger("change");
  $('#proyecto_ag').val('').trigger("change");
  $('#fecha_ingreso_ag').val('');
  $('#html_producto_ag').html(`<div class="col-12 html_mensaje">
    <div class="alert alert-warning alert-dismissible mb-0">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
      NO TIENES NINGÚN PRODUCTO SELECCIONADO.
    </div>
  </div>`);
  $('.head_list').hide();
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función para guardar o editar
function guardar_y_editar_almacen_general(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-otro-almacen")[0]);

  $.ajax({
    url: "../ajax/almacen_general.php?op=guardar_y_editar_almacen_general",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {

          if (tabla_almacen_detalle) { tabla_almacen_detalle.ajax.reload(null, false); }
          if (tabla_detalle_almacen_general) { tabla_detalle_almacen_general.ajax.reload(null, false); }
          // lista_de_items();
          $("#modal-agregar-otro-almacen").modal("hide");
          limpiar_form_otro_almacen();
          // reload_producto_comprados_ag()

          Swal.fire("Correcto!", "Almacen General guardado correctamente", "success");

        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!", 'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }

      $("#guardar_registro_otro_almacen").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_otro_almacen").css({ "width": percentComplete + '%' }).text(percentComplete.toFixed(2) + " %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_otro_almacen").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_otro_almacen").css({ width: "0%", }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_otro_almacen").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function add_producto_ag(data) {

  var idproducto = $(data).select2('val');
  textproyecto = $('#proyecto_ag').select2('data')[0].text;


  if (idproducto == null || idproducto == '' || idproducto === undefined) { } else {
    $('.html_mensaje').remove();
    var textproducto = $('#producto_ag').select2('data')[0].text;
    var unidad_medida = $('#producto_ag').select2('data')[0].element.attributes.unidad_medida.value
    var id_ar = $('#producto_ag').select2('data')[0].element.attributes.id_ar.value
    var stok = $('#producto_ag').select2('data')[0].element.attributes.stok.value
    var t_egreso = $('#producto_ag').select2('data')[0].element.attributes.t_egreso.value
    var t_ingreso = $('#producto_ag').select2('data')[0].element.attributes.t_ingreso.value
    var tipo_mov = $('#producto_ag').select2('data')[0].element.attributes.tipo_mov.value
    // t_egreso
    // t_ingreso

    if ($(`#html_producto_ag div`).hasClass(`delete_multiple_${idproducto}_${idproyecto}`)) { // validamos si exte el producto agregado

      toastr_error('Existe!!', `<u>${textproducto}</u>, Este producto ya ha sido agregado`);
      // borde-arriba-0000001a mt-2 mb-2
    } else {
      $('.head_list').show();
      $('#html_producto_ag').append(`<div class="col-lg-12 delete_multiple_${idproducto}_${idproyecto}"></div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 delete_multiple_${idproducto}_${idproyecto}" >
        <input type="hidden" name="idproducto_ag[]" value="${idproducto}" />        
        <input type="hidden" name="idproyecto_ag[]" value="${idproyecto}" />        
        <input type="hidden" name="id_ar_ag[]" value="${id_ar}" /> 

        <input type="hidden" name="stok[]" value="${stok}" />        
        <input type="hidden" name="t_egreso[]" value="${t_egreso}" />        
        <input type="hidden" name="t_ingreso[]" value="${t_ingreso}" />  
        <input type="hidden" name="tipo_mov[]" value="${tipo_mov}" />  

        <div class="form-group">
        <!--<label for="fecha_ingreso">Nombre Producto</label>-->
          <textarea class="form-control textarea_datatable" rows="1"> ${textproducto} </textarea>                                  
        </div>
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 delete_multiple_${idproducto}_${idproyecto}">
        <div class="form-group">
        <!--<label for="almacen_general_${idproducto}">Almacen general <span class="cargando-almacen-${idproducto}"><i class="fas fa-spinner fa-pulse fa-lg text-danger"></i></span></label>
          <select name="almacen_general_ag[]" id="almacen_general_${idproducto}" class="form-control" placeholder="Almacen general"> </select>-->
           <input type="hidden" name="proyecto_ag[]" class="form-control" id="proyecto_${idproducto}" value="${idproyecto}"  placeholder="Proyecto" required min="0" />
           <!--<span class="form-control-mejorado"> ${textproyecto} </span>-->
          <textarea class="form-control textarea_datatable" rows="1"> ${textproyecto} </textarea>                        

        </div>      
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 delete_multiple_${idproducto}_${idproyecto}">
        <div class="form-group">
          <!--<label for="cantidad_${idproducto}">Cantidad</label>-->
          <input type="number" name="cantidad_ag_${idproducto}" class="form-control" id="cantidad_ag_${idproducto}" onkeyup="replicar_cantidad(${idproducto})" placeholder="cantidad" required min="0" step="0.01" max="${stok}"/>
          <input type="hidden" name="cantidad_ag[]" id="cantidad_${idproducto}"/>
        </div>      
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-1 delete_multiple_${idproducto}_${idproyecto}">      
      <!--<label class="text-white">.</label> <br>-->
        <button type="button" class="btn bg-gradient-danger btn-sm"  onclick="remove_producto_ag(${idproducto},${idproyecto});"><i class="far fa-trash-alt"></i></button>      
      </div> `);
      $(`#cantidad_ag_${idproducto}`).rules("add", { required: true, min: 0, messages: { required: `Campo requerido.`, min: "Mínimo 0", max: " Stock Máximo {0}" } });

      $.post(`../ajax/almacen.php?op=otros_almacenes`, function (e, status, jqXHR) {
        e = JSON.parse(e);   //console.log(e);
        if (e.status == true) {
          e.data.forEach((val, key) => {
            $(`#almacen_general_${idproducto}`).append(`<option value="${val.idalmacen_general}">${val.nombre_almacen}</option>`);
          });
          $(`.cargando-almacen-${idproducto}`).html('');
        } else {
          ver_errores(e);
        }
      }).fail(function (e) { ver_errores(e); });
    }
  }
}

function replicar_cantidad(id) { $(`#cantidad_${id}`).val($(`#cantidad_ag_${id}`).val()); }

function remove_producto_ag(id, idproy) {
  $(`.delete_multiple_${id}_${idproy}`).remove();
  if ($("#html_producto_ag").children().length == 0) {
    $('#html_producto_ag').html(`<div class="col-12 html_mensaje">
      <div class="alert alert-warning alert-dismissible mb-0"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5> NO TIENES NINGÚN PRODUCTO SELECCIONADO. </div>
    </div>`);
    $('.head_list').hide();
  }
}

//--------------------FIN ALMACEN GENERAL----------------------

//------------------------------------------------------------------
//-----TRANsFERENCIA DE PRODUCTOS ENTRE ALMACENES GENERALES----------
//------------------------------------------------------------------
var array_id_a_g_r = [];

function select_tipo_transferencia(tipo) {

  $("#modal-transferencia_aproyecto").modal("show");

  tipo_transf = $(tipo).select2('val');
  var isResumido = (tipo_transf === 'Otro_Almacen') ? "Seleccione Almacen" : "Seleccione Proyecto";

  if (tipo_transf == null || tipo_transf == 0) {

    $(".init_select").show(); $(".select_proy_alm").hide();

  } else {

    $(".init_select").hide(); $(".select_proy_alm").show();

    lista_select2(`../ajax/almacen_general.php?op=select2_proyect_almacen&tipo_transf=${tipo_transf}&id_almacen_g=${id_almacen_transf}`, '#name_alm_proyecto', null);
    $("#name_alm_proyecto").select2({ theme: "bootstrap4", placeholder: `${isResumido}`, allowClear: true, });

  }
}

function listar_productos_transferencia() {
  // id_almacen_transferencia=id_almacen_transf
  $("#modal-transferencia_aproyecto").modal("show");

  $.post(`../ajax/almacen_general.php?op=transferencia_a_proy_almacen&id_almacen=${id_almacen_transf}`, function (e, status) {

    $('#html_producto_transf').html("");

    e = JSON.parse(e); //console.log(e);

    if (e.status == true) {

      $('.head_list').show();

      e.data.forEach((val, index) => {

        array_id_a_g_r.push(val.idalmacen_general_resumen);

        //     agr.idalmacen_general_resumen,--------------
        // p.idproducto,--------------
        // agr.tipo,------------------
        // agr.total_stok,
        // agr.total_ingreso,
        // agr.total_egreso,
        // ag.idalmacen_general,
        // p.nombre as nombre_producto,
        // um.nombre_medida as unidad_medida,
        // um.abreviacion,

        $('#html_producto_transf').append(`
        <div class="col-lg-12"></div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6" >
          <input type="hidden" name="idalmacen_general_trns[]"  id="${val.idalmacen_general}" value="${val.idalmacen_general}"/>
          <input type="hidden" name="idalmacen_general_origen"  value="${id_almacen_transf}"/>
          <input type="hidden" name="idproducto_trns[]" id="${val.idproducto}" value="${val.idproducto}"/>
          <input type="hidden" name="idalmacen_general_resumen_trns[]" id="${val.idalmacen_general_resumen}" value="${val.idalmacen_general_resumen}"/>
          <input type="hidden" name="tipo_trns[]" id="${val.tipo}" value="${val.tipo}"/>
          <input type="hidden" name="categoria_trns[]" id="${val.categoria}" value="${val.categoria}"/>
          <div class="form-group">
            <textarea class="form-control textarea_datatable" rows="1"> ${val.nombre_producto} ${val.abreviacion}</textarea>                                  
          </div>
        </div> 
        <div class="col-12 col-sm-12 col-md-6 col-lg-3">
          <div class="form-group">
            <textarea class="form-control textarea_datatable" rows="1"> ${val.total_stok} </textarea>                                  
          </div>      
        </div> 
        <div class="col-12 col-sm-12 col-md-6 col-lg-2">
          <div class="form-group">
            <input type="number" class="form-control cant_g" name="cantidad_tr${val.idalmacen_general_resumen}" id="cantidad__trns${val.idalmacen_general_resumen}" onkeyup="replicar_cantidad_a_r(${val.idalmacen_general_resumen})" readonly placeholder="cantidad"  min="0" step="0.01" max="${val.total_stok}"/>
            <input type="hidden" name="cantidad_trns[]" class="form-control" id="cantidad__trns_env${val.idalmacen_general_resumen}"/>
          </div>      
        </div> 
        <div class="col-12 col-sm-12 col-md-6 col-lg-1"> 
          <div class="custom-control custom-switch">
            <input class="custom-control-input checked_all" type="checkbox" id="customCheckbox${val.idalmacen_general_resumen}" onchange="update_valueChec(${val.idalmacen_general_resumen})" >
            <input type="hidden" class="estadochecked_all" name="ValorCheck_trns[]" id="ValorCheck${val.idalmacen_general_resumen}" value="0">
            <label for="customCheckbox${val.idalmacen_general_resumen}" class="custom-control-label"></label>
          </div>         
        </div> `);

      });

    } else {
      ver_errores(e);
    }
  }).fail(function (e) { ver_errores(e); });

}

function replicar_cantidad_a_r(id) { $(`#cantidad__trns_env${id}`).val($(`#cantidad__trns${id}`).val()); }

function update_valueChec(id) {

  if ($(`#customCheckbox${id}`).is(':checked')) {

    $(`#ValorCheck${id}`).val(1);
    $(`#cantidad__trns${id}`).rules("add", { required: true, min: 0, messages: { required: `Campo requerido.`, min: "Mínimo 0", max: " Stock Máximo {0}" } });
    $(`#cantidad__trns${id}`).removeAttr('readonly', true);
    // $('.btn_g_proy_alm').removeAttr('disabled').attr('id', 'guardar_registro_proyecto_almacen');
    $("#form_proyecto_almacen").valid();

  } else {

    $(`#ValorCheck${id}`).val(0);
    $(`#cantidad__trns${id}`).rules("remove", "required");
    $(`#cantidad__trns${id}`).attr('readonly', true);
    // $('.btn_g_proy_alm').attr('disabled', 'disabled').removeAttr('id');

    $(`#cantidad__trns_env${id}`).val(0);
    $(`#cantidad__trns${id}`).val(0);

    $("#form_proyecto_almacen").valid();
  }

}

function Activar_masivo() {

  if ($(`#marcar_todo`).is(':checked')) {

    $('.checked_all').each(function () { this.checked = true; });
    $('.estadochecked_all').val(1);

    array_id_a_g_r.forEach((val, key) => {
      $(`#cantidad__trns${val}`).rules("add", { required: true, min: 0, messages: { required: `Campo requerido.`, min: "Mínimo 0", max: " Stock Máximo {0}" } });
      $(`#cantidad__trns${val}`).removeAttr('readonly', true);
    });

    // $('.btn_g_proy_alm').removeAttr('disabled').attr('id', 'guardar_registro_proyecto_almacen');

    $("#form_proyecto_almacen").valid();

  } else {

    $('.checked_all').each(function () { this.checked = false; });
    $('.estadochecked_all').val(0);

    array_id_a_g_r.forEach((val, key) => {
      $(`#cantidad__trns${val}`).rules("remove", "required");
      $(`#cantidad__trns${val}`).attr('readonly', true);
      $(`#cantidad__trns_env${val}`).val(0);
      $(`#cantidad__trns${val}`).val(0);

    });

    // $('.btn_g_proy_alm').attr('disabled', 'disabled').removeAttr('id');

    $("#form_proyecto_almacen").valid();


  }
}

function limpiar_Transferencia() {
  $("#fecha_transf_proy_alm").val("");
  $("#cantidad_alm_trans").val("");
  $("#name_alm_destino").val("").trigger("change");
  $("#tranferencia").val("").trigger("change");
}

//Función para guardar o editar
function guardar_tranf_almacenes_generales(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form_proyecto_almacen")[0]);

  $.ajax({
    url: "../ajax/almacen_general.php?op=guardar_transf_almacen_proyecto",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {

          if (tabla_almacen_detalle) { tabla_almacen_detalle.ajax.reload(null, false); }
          if (tabla_detalle_almacen_general) { tabla_detalle_almacen_general.ajax.reload(null, false); }
          $("#modal-transferencia_aproyecto").modal("hide");
          limpiar_Transferencia();

          Swal.fire("Correcto!", "Transferencia guardado correctamente", "success");

        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!", 'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }

      $("#guardar_registro_trans_almacen").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_trans_almacen").css({ "width": percentComplete + '%' }).text(percentComplete.toFixed(2) + " %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#barra_progress_trans_almacen").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_trans_almacen").css({ width: "0%", }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_trans_almacen").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

/**------------------------------------------------------------------
 * ------------------------------------------------------------------
 * --------------------I N I T  I N G R E S O  D I R E C T O---------
 * ------------------------------------------------------------------
 */
function limpiar_ing_di() {

  $('#producto_tup').val('').trigger("change");
  $('#fecha_tup').val('');
  $('#almacen_tup').val('');
  $(".titulo-add-producto-tup").hide();
  $('#html_producto_tup').html(`<div class="col-12 delete_multiple_alerta_tup">
    <div class="alert alert-warning alert-dismissible mb-0">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
      NO TIENES NINGÚN PRODUCTO SELECCIONADO.
    </div>
  </div>`);
  lista_select2(`../ajax/almacen_general.php?op=select2Productos`, '#producto_tup', null, '.cargando_producto_tup');

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function add_producto_tup(data) {
  var idproducto = $(data).select2('val');

  if (idproducto == null || idproducto == '' || idproducto === undefined) { } else {
    $('.delete_multiple_alerta_tup').remove(); // Eliminado el mensaje de vacio
    $(".titulo-add-producto-tup").show();     // mostramos los titulos del producto

    var textproducto = $('#producto_tup').select2('data')[0].text;
    var unidad_medida = $('#producto_tup').select2('data')[0].element.attributes.unidad_medida.value

    if ($(`#html_producto_tup div`).hasClass(`delete_multiple_${idproducto}`)) { // validamos si exte el producto agregado
      toastr_error('Existe!!', `<u>${textproducto}</u>, Este producto ya ha sido agregado`);
    } else {
      $('#html_producto_tup').append(`
      <div class="col-12 col-sm-12 col-md-6 col-lg-5 delete_multiple_${idproducto}" >
        <input type="hidden" name="idproducto_tup[]" value="${idproducto}" />     
        <div class="form-group">          
          <textarea class="form-control" name="" id="" cols="30" rows="1"> ${textproducto} </textarea>                             
        </div>
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 delete_multiple_${idproducto}">
        <div class="form-group">          
          <span class="form-control-mejorado">${unidad_medida} </span>
        </div>      
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 delete_multiple_${idproducto}">
        <div class="form-group">
          <span class="cargando-marca-tup-${idproducto}"><i class="fas fa-spinner fa-pulse fa-lg text-danger"></i></span>
          <select name="marca_tup[]" id="marca_tup_${idproducto}" class="form-control" placeholder="Marca"> </select>
        </div>      
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 delete_multiple_${idproducto}"">
        <div class="form-group">
          <input type="number" name="cantidad_tup_view_${idproducto}" class="form-control" id="cantidad_tup_view_${idproducto}" placeholder="cantidad" required min="0"  step="0.01" onkeyup="replicar_data_input(${idproducto})" />
          <input type="hidden" name="cantidad_tup[]" class="form-control" id="cantidad_tup_${idproducto}" placeholder="cantidad"  />
        </div>      
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-1 delete_multiple_${idproducto}">        
        <button type="button" class="btn bg-gradient-danger btn-sm"  onclick="remove_producto_tup(${idproducto});"><i class="far fa-trash-alt"></i></button>      
      </div> <div class="col-lg-12 borde-arriba-0000001a mt-0 mb-3 delete_multiple_${idproducto}"></div>`);

      $(`#cantidad_tup_view_${idproducto}`).rules("add", { required: true, min: 0, messages: { required: `Campo requerido.`, min: "Mínimo {0}", step: "Maximo 2 decimales" } });
      
      $.post(`../ajax/almacen_general.php?op=marcas_x_producto`, { 'id_producto': idproducto}, function (e, status, jqXHR) {
        e = JSON.parse(e);  console.log('000000');  console.log(e);
        if (e.status == true) {
          e.data.forEach((val, key) => {
            $(`#marca_tup_${idproducto}`).append(`<option value="${val.marca}">${val.marca}</option>`);
          });
          $(`.cargando-marca-tup-${idproducto}`).html('');
        } else {
          ver_errores(e);
        }
      }).fail(function (e) { ver_errores(e); });
    }
  }
}

function remove_producto_tup(id) {
  $(`.delete_multiple_${id}`).remove();
  $(`.tooltip`).remove();
  if ($("#html_producto_tup").children().length == 0) {
    $(".titulo-add-producto-tup").hide();
    $('#html_producto_tup').html(`<div class="col-12 delete_multiple_alerta_tup">
      <div class="alert alert-warning alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5> NO TIENES NINGÚN PRODUCTO SELECCIONADO. </div>
    </div>`);
  }
}

function replicar_data_input(id) { $(`#cantidad_tup_${id}`).val($(`#cantidad_tup_view_${id}`).val()); }

//Función para guardar o editar
function guardar_y_prod_id_tup(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-almacen-tup")[0]);

  $.ajax({
    url: "../ajax/almacen_general.php?op=guardar_y_prod_id_tup",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {      
          $("#modal-ingreso-directo").modal("hide");         
          Swal.fire("Correcto!", "Se realizo el ingreso Directo a almacen general correctamente", "success");  
          if (tabla_almacen_detalle) { tabla_almacen_detalle.ajax.reload(null, false); }
          if (tabla_detalle_almacen_general) { tabla_detalle_almacen_general.ajax.reload(null, false); }     
          lista_select2(`../ajax/almacen_general.php?op=select2Productos`, '#producto_tup', null, '.cargando_producto_tup');      
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_almacen_tup").html('Guardar Cambios').removeClass('disabled send-data');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_tup").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_almacen_tup").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
      $("#barra_progress_tup").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_tup").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

init();

$(function () {

  //$('#unidad_medida').on('change', function() { $(this).trigger('blur'); });
  $('#tranferencia').on('change', function () { $(this).trigger('blur'); });
  $('#name_alm_proyecto').on('change', function () { $(this).trigger('blur'); });

  $("#form-almacen-general").validate({
    rules: {
      nombre_almacen: { required: true, minlength: 3, maxlength: 100 },
      descripcion: { required: true },
    },
    messages: {
      nombre_almacen: { required: "Por favor ingrese nombre", minlength: "Minimo 3 caracteres", maxlength: "Maximo 100 caracteres" },
      descripcion: { required: "Campo requerido", },
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
      guardar_y_editar_almacen(e);
    },
  });

  $("#form-otro-almacen").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      fecha_ingreso_ag: { required: true, },
    },
    messages: {
      fecha_ingreso_ag: { required: "Campo requerido.", },
      // 'cantidad[]':   { min: "Mínimo 0", required: "Campo requerido"},  
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
      guardar_y_editar_almacen_general(e);
    },
  });

  $("#form_proyecto_almacen").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      tranferencia: { required: true, },
      name_alm_proyecto: { required: true, },
      fecha_transf_proy_alm: { required: true, },
    },
    messages: {
      tranferencia: { required: "Campo requerido.", },
      name_alm_proyecto: { required: "Campo requerido.", },
      fecha_transf_proy_alm: { required: "Campo requerido.", },
      // 'cantidad[]':   { min: "Mínimo 0", required: "Campo requerido"},  
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
      guardar_tranf_almacenes_generales(e);
    },
  });

  $("#form-almacen-tup").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      fecha_tup:  { required: true,  },      
    },
    messages: {
      fecha_tup:  { required: "Campo requerido.", },    
      // 'cantidad[]':   { min: "Mínimo 0", required: "Campo requerido"},  
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
      guardar_y_prod_id_tup(e);
    },
  });

  //$('#unidad_medida').rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $('#tranferencia').rules('add', { required: true, messages: { required: "Campo requerido" } });
  $('#name_alm_proyecto').rules('add', { required: true, messages: { required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..
function reload_producto_comprados_ag() { $('.comprado_todos_ag').html(`(comprado)`); lista_select2(`../ajax/almacen_general.php?op=select2ProductosComprados&idproyecto=${idproyecto}`, '#producto_ag', null, '.cargando_productos_ag'); }

function obtener_dia_ingreso(datos) { $('#dia_ingreso_ag').val(extraer_dia_semana_completo($(datos).val())); }

function reload_producto_tup(){ lista_select2(`../ajax/almacen_general.php?op=select2Productos`, '#producto_tup', null, '.cargando_producto_tup'); }
