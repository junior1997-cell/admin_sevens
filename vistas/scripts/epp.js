var tabla;
var tabla_epp_x_tpp;
var tabla_resumen_epp;
var tbl_detalle_epp;
var codigoHTML = '';
var miArray = [];
var i_Array = [];
var fecha_1_r = "", fecha_2_r = "", id_proveedor_r = "", comprobante_r = "", glosa_r = "";
var marca_edit ='';
//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#bloc_Tecnico").addClass("menu-open");
  $("#mTecnico").addClass("active");
  $("#lEpp").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));
  tabla_resumen_epp();
  listar_trabajdor();


  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-epp").submit(); });
  $("#guardar_registro_epp_xp").on("click", function (e) { $("#submit-form-editar-x-epp").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#select_id_insumo").select2({ theme: "bootstrap4", placeholder: "Seleccinar", allowClear: true, });

  $("#fecha_ingreso_xp").attr('min', localStorage.getItem("nube_fecha_inicial_actividad")).attr('max', localStorage.getItem("nube_fecha_final_actividad"));
  $("#fecha_g").attr('min', localStorage.getItem("nube_fecha_inicial_actividad")).attr('max', localStorage.getItem("nube_fecha_final_actividad"));

  // Formato para telefono
  $("[data-mask]").inputmask();

}
function table_show_hide(estado) {
  
  if (estado==1) {
    $(".regresar").hide();
    $(".tbl_resumen_epp_x_tpp").show();
    $(".tbl_detalle_epp").hide();
    
  }
  if (estado==2) {
    $(".regresar").show();
    $(".tbl_resumen_epp_x_tpp").hide();
    $(".tbl_detalle_epp").show();
    
  }

}


//Función limpiar
function limpiar() {

  $("#idepp").val("");
  $("#fecha_g").val("");
  $("#nro_comprobante").val("");
  $("#select_id_insumo").val("null").trigger("change");
  miArray = []; i_Array = []; 
  $('.codigoGenerado').html(`<div class="alert alert-warning alert-dismissible alerta"> <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5> NO TIENES NINGÚN EQUIPO DE PROTECCIÓN PERSONAL SELECCIONADO.  </div>`);

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();

}

//TABLA PRINCIPAL QUE SE MUESTRA AL CARGAR LOS DATOS
function tabla_resumen_epp() {

  var idproyecto = localStorage.getItem("nube_idproyecto");

  tabla_resumen_epp = $("#tabla-resumen-epp-x-tpp").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0, 1, 2,3,4,5,6], } },
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0, 1, 2,4,5,6], } },
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0, 1, 2,4,5,6], }, orientation: 'landscape', pageSize: 'LEGAL', },
      { extend: "colvis" },
    ],
    ajax: {
      url: `../ajax/epp.php?op=tabla_resumen_epp&idproyecto=${idproyecto}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != "") { $("td", row).eq(0).addClass("text-center"); }
      // columna: 
      if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap text-center"); }
      //Columna _: 
      if (data[2] != "") { $("td", row).eq(5).addClass("text-nowrap text-center"); }
      if (data[3] != "") { $("td", row).eq(3).addClass("text-nowrap text-center"); }
      if (data[4] != "") { $("td", row).eq(4).addClass("text-nowrap text-center"); }
      if (data[5] != "") { $("td", row).eq(5).addClass("text-nowrap text-center"); }
      if (data[6] != "") { $("td", row).eq(6).addClass("text-nowrap text-center"); }
      if (data[7] != "") { $("td", row).eq(7).addClass("text-nowrap text-center"); }
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

  $(tabla).ready(function () { $('.cargando').hide(); });
}

//VISTA DETALLE -LISTA DE TRABAJADORES

function listar_trabajdor() {

  var idproyecto = localStorage.getItem("nube_idproyecto");
  $('#btn_export_epp_full').attr('href', `../reportes/export_xlsx_full_control_epp.php?id_proyecto=${localStorage.getItem('nube_idproyecto')}`);

  tabla = $("#tabla-epp").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0, 1, 2], } },
      // { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0, 1, 2], } },
      // { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0, 1, 2], }, orientation: 'landscape', pageSize: 'LEGAL', },
      // { extend: "colvis" },
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
      lengthMenu: "Ver: _MENU_",
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
    if (filaSeleccionadaAnterior !== null) { filaSeleccionadaAnterior.css('background-color', ''); }

    // Aplicar el estilo a la nueva fila seleccionada
    $(this).css('background-color', '#ffe69c');
    // Guardar la referencia de la nueva fila seleccionada
    filaSeleccionadaAnterior = $(this);

    // Obtener los datos de la fila seleccionada
    var datosFila = tabla.row(this).data();
    // Hacer lo que desees con los datos de la fila
    epp_tabajador(datosFila[1], datosFila[2], datosFila[3], datosFila[4]);

  });

  $(tabla).ready(function () { $('.cargando').hide(); });
}

function epp_tabajador(nombres, t_ropa, t_zapato, id_tpp,) {

  $('.alerta_inicial').hide(); $('.tabla_epp_x_tpp').show();   $("#btn_export_eppxt").show();

  $(".nombre_epp").html(nombres); $(".tallas").html(t_ropa + ' , ' + t_zapato); $(".nombre_trab_modal").html(nombres);
  $('#btn_export_eppxt').attr('href', `../reportes/export_xlsx_control_eppxt.php?id_proyecto=${localStorage.getItem('nube_idproyecto')}&id_tpp=${id_tpp}`);


  lista_select2(`../ajax/epp.php?op=select_2_insumos_pp&idproyecto=${localStorage.getItem('nube_idproyecto')}`, '#select_id_insumo', null);

  miArray = []; i_Array = []; 

  $('.codigoGenerado').html(`<div class="alert alert-warning alert-dismissible alerta">  <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>  NO TIENES NINGÚN EQUIPO DE PROTECCIÓN PERSONAL SELECCIONADO.</div>`);

  $("#idtrabajador_por_proyecto").val(id_tpp)

  $(".btn_add_epps").show();

  tabla_epp_x_tpp = $("#tabla-epp-x-tpp").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0, 2,3,4,5,6], } },
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0, 2,3,4,5,6], } },
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0, 2,3,4,5,6], }, orientation: 'landscape', pageSize: 'LEGAL', },
      { extend: "colvis" },
    ],
    ajax: {
      url: `../ajax/epp.php?op=listar_epp_trabajdor&id_tpp=${id_tpp}&proyecto=${localStorage.getItem("nube_idproyecto")}`,
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
    columnDefs: [
      { targets: [6], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },

    ],
  }).DataTable();



}

//------------------EPP--------------------
//detalle por epp
function tabla_detalle_epp(idproducto,nombre,marca) { 
  table_show_hide(2);
  $(".nombre_produc").html('   <i class="fa-solid fa-helmet-safety"></i> '+nombre);  $(".marca_produc").html(marca);

  var idproyecto = localStorage.getItem("nube_idproyecto");

  tbl_detalle_epp = $("#tbla_detalle_epp").dataTable({
    responsive: true,
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0, 1, 2,3], } },
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0, 1, 2,3], } },
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0, 1, 2,3], }, orientation: 'landscape', pageSize: 'LEGAL', },
      { extend: "colvis" },
    ],
    ajax: {
      url: `../ajax/epp.php?op=tbla_detalle_epp&idproducto=${idproducto}&idproyecto=${idproyecto}&marca=${marca}`,
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
    columnDefs: [
      { targets: [3], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
    ],
  }).DataTable();

  $(tabla).ready(function () { $('.cargando').hide(); });
  
}  

//Función para guardar varios
function guardar_epp(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-epp")[0]);

  $.ajax({
    url: "../ajax/epp.php?op=guardar_epp",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e); console.log(e.status);
        if (e.status == true) {
          Swal.fire("Correcto!", "El registro se guardo correctamente.", "success");
          console.log('aqui');
          tabla_epp_x_tpp.ajax.reload(null, false); 
          tabla_resumen_epp.ajax.reload(null, false); 
          // tbl_detalle_epp.ajax.reload(null, false);

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

//-----------------------------------------------------
//-----------------------EDITAR------------------------
//-----------------------------------------------------
//-----------------------EDITAR------------------------
function limpiar_edit_epp() {
 // <!-- idalmacen_x_proyecto_xp, idtrabajador_xp, id_producto_xp, fecha_ingreso_xp, marca_xp, cantidad_xp  -->
 $("#fecha_ingreso_xp").val("");
 $("#cantidad_xp").val("");
 $("#marca_xp").val("null").trigger("change");
}

function mostrar(idepp) {
  limpiar_edit_epp();
  $("#producto_xp").select2({ theme: "bootstrap4", placeholder: "Seleccinar E.P.P", allowClear: true, });

  lista_select2(`../ajax/epp.php?op=select_2_insumos_pp&idproyecto=${localStorage.getItem('nube_idproyecto')}`, '#producto_xp', null);

  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();

  $("#modal-ver-editar-epp").modal("show");

  $.post("../ajax/epp.php?op=mostrar", { idepp: idepp }, function (e, status) {
    // p.nombre,um.abreviacion, ad.idalmacen_detalle, ad.idalmacen_resumen, ad.idproyecto_destino,
    // ad.idtrabajador_por_proyecto, ad.tipo_mov,ad.marca, ad.fecha, ad.cantidad
    e = JSON.parse(e); //console.log('laaaaaaaaaa'); console.log(e);
    if (e.status == true) {
      $("#idalmacen_x_proyecto_xp").val(e.data.idepp_x_proyecto);
      $("#idtrabajador_xp").val(e.data.idtrabajador_por_proyecto);
      $("#idalmacen_resumen_xp").val(e.data.idalmacen_resumen);
      $("#epp_xp").val(`${e.data.nombre} -  ${e.data.nombre}`);
      $("#unidad_m").val(e.data.abreviacion);
      $("#id_producto_xp").val(e.data.idproducto);
      // id_product_edit = e.data.idproducto;
      select_marcas_edit(e.data.idproducto,e.data.marca);
      $("#fecha_ingreso_xp").val(e.data.fecha_ingreso);
      $("#cantidad_xp").val(e.data.cantidad);

      // $("#marca_xp").val(e.data.marca).trigger('change');

      $("#cargando-3-fomulario").show();
      $("#cargando-4-fomulario").hide();


    } else {
      ver_errores(e);
    }

  }).fail(function (e) { ver_errores(e); });

}

function select_producto_edit(el) { 
   var id_insumo = $('#producto_xp').val();
  $("#id_producto_xp").val($('#producto_xp').val());

  var nombre = "", marca = "", modelo = "", abreviacion="",  idProduc_almacen_resumen="";

  marca = $('option:selected', el).attr('data-marca');
  //abreviacion = $('option:selected', el).attr('data-abreviacion');
  console.log($('option:selected', el).attr('data-idProduc_almacen_resu'));
  $("#idalmacen_resumen_xp").val( $('option:selected', el).attr('data-idProduc_almacen_resu'));
  $("#epp_xp").val( $('#producto_xp').val()==null ? '': $('#producto_xp').select2('data')[0].text );
  $("#unidad_m").val( $('option:selected', el).attr('data-abreviacion'));

  select_marcas_edit(id_insumo,marca);


}

function select_marcas_edit(idproducto,marca) {

  var idpro = localStorage.getItem("nube_idproyecto");

  $.post("../ajax/epp.php?op=marcas_x_insumo", { id_insumo: idproducto, idproyecto: idpro }, function (ee, status) {

    $(`#marca_xp`).html("");

    ee = JSON.parse(ee); console.log(ee);

    if (ee.status == true) { 
      
      ee.data.forEach(item => { 

        if (item.marca ==marca) {
          $(`#marca_xp`).append(`<option selected value="${item.marca}">${item.marca}</option>`); 
          
        }else{

          $(`#marca_xp`).append(`<option value="${item.marca}">${item.marca}</option>`); 
        }      
      
      });

    } else {
      ver_errores(e);
    }

  }).fail(function (e) { ver_errores(e); });



}

function editar_epp(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-editar-x-epp")[0]);

  $.ajax({
    url: "../ajax/epp.php?op=editar_epp",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {
          Swal.fire("Correcto!", "El registro se guardo correctamente.", "success");
          tabla_epp_x_tpp.ajax.reload(null, false); tabla_resumen_epp.ajax.reload(null, false); //tbl_detalle_epp.ajax.reload(null, false);

          limpiar_edit_epp();
          $("#modal-ver-editar-epp").modal("hide");

        } else {
          ver_errores(e);
        }
      } catch (err) {
        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      }
      $("#guardar_registro_epp_xp").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_epp_xp").css({ "width": percentComplete + '%' }).text(percentComplete.toFixed(2) + " %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_epp_xp").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_epp_xp").css({ width: "0%", }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_epp_xp").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

//Función para ANULAR registros
function eliminar_detalle(idalmacen_detalle,idalmacen_resumen,cantidad, producto) {

  Swal.fire({
    title: "¿Está Seguro de  Eliminar el registro?",
    html:`<b class="text-danger"><del> ${producto} </del></b>`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Eliminar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/epp.php?op=eliminar", { id_tabla: idalmacen_detalle,idalmacen_resumen: idalmacen_resumen,cantidad:cantidad }, function (e) {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {
          Swal.fire("Eliminado!", "El registro ha sido Eliminado.", "success");

          tabla_epp_x_tpp.ajax.reload(null, false);
        } else {
          Swal.fire("Error!", e, "error");
        }
      });
    }
  });
}

///add varios elementos para guardar 

function add_row(el) {
  var idproyec = localStorage.getItem("nube_idproyecto");
  codigoHTML = '';
  var id_insumo = $('#select_id_insumo').val();

  var nombre = "", idProduc_almacen_resumen = "", modelo = "";


  nombre      = $('#select_id_insumo').val()==null ? '':$('#select_id_insumo').select2('data')[0].text ;
  idProduc_almacen_resumen = $('option:selected', el).attr('data-idProduc_almacen_resu');
  modelo      = $('option:selected', el).attr('data-modelo');
  abreviacion = $('option:selected', el).attr('data-abreviacion');
  stok = $('option:selected', el).attr('data-total_stok');
  
  

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

        codigoHTML = ` <div class="row id_${i}" >

                      <!-- Nombre Producto -->
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                        <!--<label for="">Nombre Producto</label>-->
                        <input type="hidden" name="id_insumo[]" class="form-control" id="id_insumo" value="${id_insumo}"/>
                        <input type="hidden" name="idProduc_almacen_resumen[]" class="form-control" id="idProduc_almacen_resumen" value="${idProduc_almacen_resumen}"/>
                          <span class="form-control-mejorado"> ${nombre} - Stock ${stok} </span>  
                        </div>
                      </div>
                      <!-- unidad -->
                      <div class="col-12 col-sm-12 col-md-6 col-lg-1">
                        <div class="form-group">
                        <!--<label for="">U.M</label>-->
                          <span class="form-control-mejorado"> ${abreviacion} </span>  
                        </div>
                      </div> 
                      <!-- MARCA -->
                      <div class="col-12 col-sm-12 col-md-6 col-lg-2">
                        <div class="form-group">
                         <!-- <label for="">Marca</label> -->
                          <select name="marca[]" id="marca_select_${id_insumo}" class="form-control"></select>
                        </div>
                      </div> 

                      <!-- cantidad -->
                      <div class="col-12 col-sm-12 col-md-6 col-lg-2">
                        <div class="form-group">
                          <!--<label for="">Cantidad</label>-->
                          <input type="number" name="cantidad_epp${idProduc_almacen_resumen}" class="form-control" id="cantidad_epp${idProduc_almacen_resumen}" onkeyup="replicar_cantidad(${idProduc_almacen_resumen})" placeholder="Cantidad" required min="0" step="0.01" max="${stok}"/>
                          <input type="hidden" name="cantidad[]" class="form-control" id="cantidad_epp_env${idProduc_almacen_resumen}"/>
                        </div>
                      </div> 

                      <div class="col-12 col-sm-12 col-md-1 col-lg-1">
                        <div class="form-group"> 
                        <!--<label class="text-white">.</label> <br>-->
                          <button type="button" class="btn bg-gradient-danger cursor-pointer" aria-hidden="true" data-toggle="tooltip" data-original-title="Eliminar" onclick="eliminar_item(${i});"><i class="far fa-trash-alt"></i></button>
                        </div>
                      </div>

                  </div>`;
                  $(`#cantidad_epp${idProduc_almacen_resumen}`).rules("add", { required: true, min: 0, messages: { required: `Campo requerido.`, min: "Mínimo 0", max: " Stock Máximo {0}" } });

      }

      if (i_Array.length === 0) { $(".alerta").show(); } else { $(".alerta").hide(); }

    }

    $('.codigoGenerado').append(codigoHTML); // Agregar el contenido 

    $.post("../ajax/epp.php?op=marcas_x_insumo", { id_insumo: id_insumo, idproyecto: idproyec }, function (e, status) {

      e = JSON.parse(e); console.log(e);
      if (e.status == true) {

        // Iterar sobre los datos y agregar las opciones al select utilizando jQuery
        e.data.forEach(item => {
          $(`#marca_select_${id_insumo}`).append(`<option value="${item.marca}">${item.marca}</option>`);
        });


      } else {
        ver_errores(e);
      }

    }).fail(function (e) { ver_errores(e); });


  }

  // console.log(miArray);
}

//--------------------------------
function replicar_cantidad(id) { $(`#cantidad_epp_env${id}`).val($(`#cantidad_epp${id}`).val()); }

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
      // "cantidad[]": { required: true, positiveNumber: true },
      // cantidad: { required: true, pattern: /^[1-9]\d*$/},
    },
    messages: {
      fecha_g: { required: "Por favor ingresar fecha" },
      // "cantidad[]": { required: "Por favor ingresar la cantidad", }

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
      guardar_epp(e);
    },
  });


  $("#form-editar-x-epp").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {

      fecha_ingreso_xp: { required: true },
      // marca_xp: { required: true },
      cantidad_xp: { required: true },
    },
    messages: {

      fecha_ingreso_xp: { required: "Por favor ingresar fecha" },
      // marca_xp: { required: "Por favor ingresar fecha" },
      cantidad_xp: { required: "Por favor ingresar cantidad" },

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
      editar_epp(e);
    },
  });



});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

// no_select_tomorrow("#fecha_g");


function cargando_search() {
  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
}


