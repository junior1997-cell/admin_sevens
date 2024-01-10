var tabla_almacen_resumen;
var tabla_almacen_detalle;

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");
  $("#mRecurso").addClass("active");
  $("#lAlmacenGeneral").addClass("active");

  lista_de_items();
  tabla_principal('todos');

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/almacen_general.php?op=select2_proyect", '#proyecto_ag', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_almacen").on("click", function (e) { $("#submit-form-almacen-general").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════ 
  $("#proyecto_ag").select2({ theme: "bootstrap4", placeholder: "Seleccinar proyecto", allowClear: true, });
  // $("#producto_ag").select2({theme: "bootstrap4", placeholder: "Selecione producto", allowClear: true, });
  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  // $('#precio_unitario').number( true, 2 );
}

function templateColor(state) {
  if (!state.id) { return state.text; }
  var color_bg = state.title != '' ? `${state.title}` : '#ffffff00';
  var $state = $(`<span ><b style="background-color: ${color_bg}; color: ${color_bg};" class="mr-2"><i class="fas fa-square"></i><i class="fas fa-square"></i></b>${state.text}</span>`);
  return $state;
}

function reload_proyect_ag(pry) {
  var idproyecto = $(pry).select2('val');
  // console.log(proyecto_ag);

  if (idproyecto == null || idproyecto == '') {
    $('.select_init_recurso').show();
    $('.select_recurso').hide();
  } else {
    $('.select_init_recurso').hide();
    $('.select_recurso').show();

    lista_select2(`../ajax/almacen_general.php?op=select2ProductosComprados&idproyecto=${idproyecto}`, '#producto_ag', null, '.cargando_productos_oa');

    $("#producto_ag").select2({ theme: "bootstrap4", placeholder: "Selecione producto", allowClear: true, });

  }


}

//Función limpiar
function limpiar() {

  $("#guardar_registro_almacen").html('Guardar Cambios').removeClass('disabled');
  // no usados
  $("#precio_unitario").val("0");
  $("#precio_sin_igv").val("0");
  $("#precio_igv").val("0");
  $("#precio_total").val("0");
  $("#color").val(1);
  $("#modelo").val("");
  $("#serie").val("");
  $("#estado_igv").val("1");

  //input usados
  $("#idproducto").val("");
  $("#nombre").val("");
  $("#categoria_insumos_af").val("").trigger("change");
  $("#unidad_medida").val("").trigger("change");
  $("#marcas").val("").trigger("change");
  $("#descripcion").val("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto_activo_fijo.png");
  $("#foto1").val("");
  $("#foto1_actual").val("");
  $("#foto1_nombre").html("");

  $("#doc_old_2").val("");
  $("#doc2").val("");
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

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
          <a class="nav-link" onclick="delay(function(){tabla_detalle('${val.idcategoria}')}, 50 );" id="tabs-for-detalle-tab" data-toggle="pill" href="#tabs-for-detalle" role="tab" aria-controls="tabs-for-detalle" aria-selected="false">${val.nombre}</a>
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

//Función Listar
function tabla_principal(id_categoria) {
  $('.btn_add_almacen').show();
  $('.btn_add_prod_almacen').hide();
  tabla_almacen_resumen = $("#tabla-almacen").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0, 2, 10, 4, 5, 11, 7, 8], } },
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0, 2, 10, 4, 5, 11, 7, 8], } },
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0, 2, 10, 4, 5, 11, 7, 8], } },
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

function tabla_detalle(id_categoria) {
  $('.btn_add_almacen').hide();
  $('.btn_add_prod_almacen').show();
  tabla_almacen_detalle = $("#tabla-detalle-almacen").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0, 2, 10, 4, 5, 11, 7, 8], } },
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0, 2, 10, 4, 5, 11, 7, 8], } },
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0, 2, 10, 4, 5, 11, 7, 8], } },
    ],
    ajax: {
      url: `../ajax/almacen_general.php?op=tabla_detalle&id_almacen=${id_categoria}&id_proyecto=${localStorage.getItem("nube_idproyecto")}`,
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

//ver ficha tecnica
function modal_ficha_tec(ficha_tecnica) {

  $(".tooltip").remove();
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
//------------------------------------------------------------------

function limpiar_form_otro_almacen() {

  $('#producto_ag').val('').trigger("change");;
  $('#fecha_ingreso_ag').val('');
  $('#html_producto_ag').html(`<div class="col-12 delete_multiple_alerta_oa">
    <div class="alert alert-warning alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
      NO TIENES NINGÚN PRODUCTO SELECCIONADO.
    </div>
  </div>`);

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
    url: "../ajax/almacen.php?op=guardar_y_editar_almacen_general",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {
          reload_producto_comprados_ag()
          tbla_resumen.ajax.reload(null, false);
          limpiar_form_otro_almacen();
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

  var idproducto = $(data).select2('val'); console.log('goooooooooooooooo');
  
  $('.delete_multiple_alerta_oa').remove();

  if (idproducto == null || idproducto == '' || idproducto === undefined) { } else {

    var textproducto = $('#producto_ag').select2('data')[0].text;
    var unidad_medida = $('#producto_ag').select2('data')[0].element.attributes.unidad_medida.value
    var id_ar = $('#producto_ag').select2('data')[0].element.attributes.id_ar.value

    if ($(`#html_producto_ag div`).hasClass(`delete_multiple_${idproducto}`)) { // validamos si exte el producto agregado

      toastr_error('Existe!!', `<u>${textproducto}</u>, Este producto ya ha sido agregado`);

    } else {

      $('#html_producto_ag').append(`<div class="col-lg-12 borde-arriba-0000001a mt-2 mb-2 delete_multiple_${idproducto}"></div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 delete_multiple_${idproducto}" >
        <input type="hidden" name="idproducto_ag[]" value="${idproducto}" />        
        <input type="hidden" name="id_ar_ag[]" value="${id_ar}" />        
        <div class="form-group">
        <!--<label for="fecha_ingreso">Nombre Producto</label>-->
          <span class="form-control-mejorado"> ${textproducto} </span>                                  
        </div>
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 delete_multiple_${idproducto}">
        <div class="form-group">
        <!--<label for="almacen_general_${idproducto}">Almacen general <span class="cargando-almacen-${idproducto}"><i class="fas fa-spinner fa-pulse fa-lg text-danger"></i></span></label>-->
          <select name="almacen_general_ag[]" id="almacen_general_${idproducto}" class="form-control" placeholder="Almacen general"> </select>
        </div>      
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 delete_multiple_${idproducto}"">
        <div class="form-group">
          <!--<label for="cantidad_${idproducto}">Cantidad</label>-->
          <input type="number" name="cantidad_ag[]" class="form-control" id="cantidad_${idproducto}" placeholder="cantidad" required min="0" />
        </div>      
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-1 delete_multiple_${idproducto}">      
      <!--<label class="text-white">.</label> <br>-->
        <button type="button" class="btn bg-gradient-danger btn-sm"  onclick="remove_producto_ag(${idproducto});"><i class="far fa-trash-alt"></i></button>      
      </div> `);
      $(`#cantidad_${idproducto}`).rules("add", { required: true, min: 0, messages: { required: `Campo requerido.`, min: "Mínimo 0", } });

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

function remove_producto_ag(id) {
  $(`.delete_multiple_${id}`).remove();
  if ($("#html_producto_ag").children().length == 0) {
    $('#html_producto_ag').html(`<div class="col-12 delete_multiple_alerta_oa">
      <div class="alert alert-warning alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5> NO TIENES NINGÚN PRODUCTO SELECCIONADO. </div>
    </div>`);
  }
}



init();

$(function () {

  //$('#unidad_medida').on('change', function() { $(this).trigger('blur'); });

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

  //$('#unidad_medida').rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..
function reload_producto_comprados_ag() { $('.comprado_todos_ag').html(`(comprado)`); lista_select2(`../ajax/almacen.php?op=select2ProductosComprados&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#producto_ag', null, '.cargando_productos_ag'); }

