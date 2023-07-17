var tabla;
var tabla_epp_x_tpp;
var codigoHTML = '';
var miArray = [];
var i_Array = [];
var fecha_1_r = "", fecha_2_r = "", id_proveedor_r = "", comprobante_r = "", glosa_r = "";
//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#bloc_Tecnico").addClass("menu-open");
  $("#mTecnico").addClass("active");
  $("#lEpp").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));
  listar_trabajdor();


  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-epp").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#select_id_insumo").select2({ theme: "bootstrap4", placeholder: "Seleccinar", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();

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

//Función Listar
function listar_trabajdor() {

  var idproyecto = localStorage.getItem("nube_idproyecto");

  tabla = $("#tabla-epp").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0, 1, 2], } },
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0, 1, 2], } },
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0, 1, 2], }, orientation: 'landscape', pageSize: 'LEGAL', },
      { extend: "colvis" },
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
    $(this).css('background-color', '#ffe69c');
    // Guardar la referencia de la nueva fila seleccionada
    filaSeleccionadaAnterior = $(this);

    // Obtener los datos de la fila seleccionada
    var datosFila = tabla.row(this).data();
    // Hacer lo que desees con los datos de la fila
    filaSelecc_tabajador(datosFila[1], datosFila[2], datosFila[3], datosFila[4]);

  });

  $(tabla).ready(function () { $('.cargando').hide(); });
}

function filaSelecc_tabajador(nombres, t_ropa, t_zapato, id_tpp,) {
  $('.alerta_inicial').hide();  $('.tabla_epp_x_tpp').show();
  $(".nombre_epp").html(nombres); $(".tallas").html(t_ropa + ' , ' + t_zapato); $(".nombre_trab_modal").html(nombres);
  lista_select2(`../ajax/epp.php?op=select_2_insumos_pp&idproyecto=${localStorage.getItem('nube_idproyecto')}`, '#select_id_insumo', null);
  miArray = []; i_Array = []; $('.codigoGenerado').html("");
  $("#idtrabajador_por_proyecto").val(id_tpp)
  $(".btn_add_epps").show();

  tabla_epp_x_tpp = $("#tabla-epp-x-tpp").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0, 1, 2], } },
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0, 1, 2], } },
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0, 1, 2], }, orientation: 'landscape', pageSize: 'LEGAL', },
      { extend: "colvis" },
    ],
    ajax: {
      url: `../ajax/epp.php?op=listar_epp_trabajdor&id_tpp=${id_tpp}`,
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



}


//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-epp")[0]);

  $.ajax({
    url: "../ajax/epp.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {
          Swal.fire("Correcto!", "El registro se guardo correctamente.", "success");
          tabla_epp_x_tpp.ajax.reload(null, false);
          limpiar();
          $("#modal-agregar-epp").modal("hide");

        } else {
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
          var percentComplete = (evt.loaded / evt.total) * 100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_otro_gasto").css({ "width": percentComplete + '%' }).text(percentComplete.toFixed(2) + " %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_otro_gasto").css({ width: "0%", }).text("0%").addClass('progress-bar-striped progress-bar-animated');
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

  $("#modal-agregar-epp").modal("show");

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


      if (e.data.comprobante == "" || e.data.comprobante == null) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#doc1_nombre").html('');

        $("#doc_old_1").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_1").val(e.data.comprobante);

        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante, 'otro_gasto', 'comprobante', '100%', '210'));

      }
      $('.jq_image_zoom').zoom({ on: 'grab' });

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();


    } else {
      ver_errores(e);
    }

  }).fail(function (e) { ver_errores(e); });

}

function eliminar(idotro_gasto, tipo, numero) {

  crud_eliminar_papelera(
    "../ajax/epp.php?op=desactivar",
    "../ajax/epp.php?op=eliminar",
    idotro_gasto,
    "!Elija una opción¡",
    `<b class="text-danger"><del> ${tipo} N° ${numero} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`,
    function () { sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado.") },
    function () { sw_success('Eliminado!', 'Tu registro ha sido Eliminado.') },
    function () { tabla.ajax.reload(null, false); total(fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r); },
    false,
    false,
    false,
    false
  );
}
//--------------ADD ROW 

//::::::::::::::::::::::::::::::::::::::::::::::::::::::Capturar JQUERRY:::::::::::::::::::::::::::::::::::::::::::::

function add_row(el) {
  codigoHTML = '';
  var id_insumo = $('#select_id_insumo').val();

  var nombre = "", marca = "", modelo = "";

  nombre = $('option:selected', el).attr('data-nombre');
  marca = $('option:selected', el).attr('data-marca');
  modelo = $('option:selected', el).attr('data-modelo');

  if (id_insumo == null) {
  } else {
    // Utilizando el método includes()
    if (miArray.includes(id_insumo)) {
      // "El valor existe en el array
      toastr.warning("NO ES POSIBLE AGREGAR !!");
    } else {
      $(".alerta").hide();

      agregarElemento(id_insumo);

      toastr.success("AGREGADO CORRECTAMENTE !!");

      for (var i = 0; i < miArray.length; i++) {

        if (!i_Array.includes(i)) { i_Array.push(i); }

        codigoHTML = `<hr class="id_${i}">
                  <div class="row id_${i}" >
                    <div class="col-12 col-sm-12 col-md-2 col-lg-1">
                        <div class="form-group"> 
                          <button type="button" class="btn bg-gradient-danger cursor-pointer" aria-hidden="true" data-toggle="tooltip" data-original-title="Eliminar" onclick="eliminar_item(${i});"><i class="fas fa-plus-circle"></i></button>
                        </div>
                      </div>
                      <!-- Nombre Producto -->
                      <div class="col-12 col-sm-12 col-md-7 col-lg-8">
                        <div class="form-group">

                          <input type="hidden" name="id_insumo[]" class="form-control" id="id_insumo" value="${id_insumo}"/>

                          <p class="mb-0"><strong> Nombre:</strong>${nombre}</p> 
                          <input type="hidden" name="marca[]" class="form-control" id="marca" value="${marca}"/>
                          <span><strong> Marca:</strong> ${marca}  <strong>| Modelo :</strong> ${modelo}</span>

                        </div>
                      </div>
                      <!-- cantidad -->
                      <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="form-group">
                          <input type="text" name="cantidad[]" class="form-control" id="cantidad" placeholder="Cantidad"/>
                        </div>
                      </div> 
                      </div>`;


      }

      if (i_Array.length === 0) { $(".alerta").show(); } else { $(".alerta").hide(); }

    }

    $('.codigoGenerado').append(codigoHTML); // Agregar el contenido 
  }

  // console.log(miArray);
}

function eliminar_item(id) {

  var index = i_Array.indexOf(id);

  if (index !== -1) {

    i_Array.splice(index, 1);

    $(`.id_${id}`).remove();

    if (i_Array.length === 0) {

      miArray = [];

      $(".alerta").show();

    } else {

      $(".alerta").hide();

    }

  }

}

function agregarElemento(id) {
  if (!miArray.includes(id)) {
    miArray.push(id);
  } else {
    toastr.warning("NO ES POSIBLE AGREGAR NUEVAMENTE !!");
  }
}






init();

$(function () {

  // Regla de validación para aceptar solo números positivos
  $.validator.addMethod("positiveNumber", function (value, element) {
    return this.optional(element) || /^[1-9]\d*$/.test(value);
  }, "Ingrese un número positivo válido.");

  $("#form-epp").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      fecha_g: { required: true },
      "cantidad[]": { required: true, positiveNumber: true },
      // cantidad: { required: true, pattern: /^[1-9]\d*$/},
    },
    messages: {
      fecha_g: { required: "Por favor ingresar fecha" },
      "cantidad[]": { required: "Por favor ingresar la cantidad", }

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

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

no_select_tomorrow("#fecha_g");


function cargando_search() {
  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
}


