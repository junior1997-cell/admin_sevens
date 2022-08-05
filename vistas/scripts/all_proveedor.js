var tabla;

//Función que se ejecuta al inicio
function init() {  

  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");

  $("#mRecurso").addClass("active");

  $("#lAllProveedor").addClass("active");

  tbla_principal();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-proveedor").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#banco").select2({ templateResult: formatState, theme: "bootstrap4", placeholder: "Selecione banco", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

function formatState (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/banco/logo/${state.title}`: '../dist/docs/banco/logo/logo-sin-banco.svg'; 
  var onerror = `onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

//Función limpiar
function limpiar() {
  
  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');

  $("#idproveedor").val("");
  $("#tipo_documento option[value='RUC']").attr("selected", true);
  $("#nombre").val("");
  $("#num_documento").val("");
  $("#direccion").val("");
  $("#telefono").val("");
  $("#c_bancaria").val("");
  $("#cci").val("");
  $("#c_detracciones").val("");
  $("#banco").val("").trigger("change");
  $("#titular_cuenta").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function tbla_principal() {
  tabla = $("#tabla-proveedores").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,6,7,8,9,10,11,12,13,14,15], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,6,7,8,9,10,11,12,13,14,15], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,6,7,8,9,10,11,12,13,14,15], } }, {extend: "colvis"} ,
    ],
    ajax: {
      url: "../ajax/all_proveedor.php?op=tbla_principal",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #0
      if (data[0] != '') {  $("td", row).eq(0).addClass("text-center"); }
      // columna: #0
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
      { targets: [6,7,8,9,10,11,12,13,14,15], visible: false, searchable: false, },  
    ],
  }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);

  $.ajax({
    url: "../ajax/all_proveedor.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {

          Swal.fire("Correcto!", "Proveedor guardado correctamente", "success");
          tabla.ajax.reload(null, false);
          limpiar();
          $("#modal-agregar-proveedor").modal("hide");
          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idproveedor) {
  limpiar();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-proveedor").modal("show");

  $.post("../ajax/all_proveedor.php?op=mostrar", { idproveedor: idproveedor }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status) {     

      $("#tipo_documento option[value='" + e.data.tipo_documento + "']").attr("selected", true);
      $("#nombre").val(e.data.razon_social);
      $("#num_documento").val(e.data.ruc);
      $("#direccion").val(e.data.direccion);
      $("#telefono").val(e.data.telefono);
      $("#banco").val(e.data.idbancos).trigger("change");
      $("#c_bancaria").val(e.data.cuenta_bancaria);
      $("#cci").val(e.data.cci);
      $("#c_detracciones").val(e.data.cuenta_detracciones);
      $("#titular_cuenta").val(e.data.titular_cuenta);
      $("#idproveedor").val(e.data.idproveedor);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); });
}

function ver_mas_detalles(idproveedor) {

  $("#ver_mas_detalles_trabajador").html(`<div class="row">
    <div class="col-lg-12 text-center">
      <i class="fas fa-spinner fa-pulse fa-6x"></i><br />
      <br />
      <h4>Cargando...</h4>
    </div>
  </div>`);

  $("#modal-ver-mas-detalles-trabajador").modal("show");

  $.post("../ajax/all_proveedor.php?op=mostrar_mas_detalle", { idproveedor: idproveedor }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status) {    

      $("#ver_mas_detalles_trabajador").html(`
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-hover table-bordered">         
                <tbody>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Nombre</th>
                    <td>${e.data.razon_social}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>${e.data.tipo_documento}</th>
                    <td>${e.data.ruc}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Banco</th>
                    <td>
                      <div class="user-block float-none">
                        <img class="img-circle float-left w-25px h-auto mr-1" src="../dist/docs/banco/logo/${e.data.icono_banco}" alt="Banco" onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';">
                        <span class="username ml-2" ><p class="text-primary m-b-02rem">${e.data.nombre_banco}</p></span>
                      </div>
                    </td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Cta. Cte.</th>
                    <td>${e.data.cuenta_bancaria}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>CCI</th>
                    <td>${e.data.cci}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Cta. Detraccion</th>
                    <td>${e.data.cuenta_detracciones}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Direccion</th>
                    <td>${e.data.direccion}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Titular</th>                            
                    <td>${e.data.titular_cuenta}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Celular</th>
                    <td>${e.data.telefono}</td>
                  </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                    <th>Actualizacion</th>
                    <td>${extraer_dia_semana_completo(e.data.updated_at)}, ${moment(e.data.updated_at).format('DD-MM-YYYY hh:mm a')}</td>
                  </tr>
                </tbody>
              </table>       
            </div>
          </div>
      </div>`);

    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); });
}

//Función para desactivar registros
function eliminar(idproveedor, nombre) {
  
  crud_eliminar_papelera(
    "../ajax/all_proveedor.php?op=desactivar",
    "../ajax/all_proveedor.php?op=eliminar", 
    idproveedor, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false) },
    false, 
    false, 
    false,
    false
  );
}

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco").select2("val") == null || $("#banco").select2("val") == "" || $("#banco").select2("val") == "1" ) {
    $("#c_bancaria").prop("readonly", true);
    $("#cci").prop("readonly", true);
    $("#c_detracciones").prop("readonly", true);
  } else {
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-3").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');   

    $.post("../ajax/ajax_general.php?op=formato_banco", { 'idbanco': $("#banco").select2("val") }, function (e, status) {
      e = JSON.parse(e); // console.log(e);

      if (e.status) {
        $(".chargue-format-1").html("Cuenta Bancaria");
        $(".chargue-format-2").html("CCI");
        $(".chargue-format-3").html("Cuenta Detracciones");

        $("#c_bancaria").prop("readonly", false);
        $("#cci").prop("readonly", false);
        $("#c_detracciones").prop("readonly", false);

        var format_cta = decifrar_format_banco(e.data.formato_cta);
        var format_cci = decifrar_format_banco(e.data.formato_cci);
        var formato_detracciones = decifrar_format_banco(e.data.formato_detracciones);

        $("#c_bancaria").inputmask(`${format_cta}`);
        $("#cci").inputmask(`${format_cci}`);
        $("#c_detracciones").inputmask(`${formato_detracciones}`);
      } else {
        ver_errores(e);
      }      
    }).fail( function(e) { ver_errores(e); });
  }
}


init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M S  :::::::::::::::::::::::::::::::::::::::..

$(function () {  
  $("#banco").on('change', function() { $(this).trigger('blur'); });

  $("#form-proveedor").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      tipo_documento: { required: true },
      num_documento:  { required: true, minlength: 6, maxlength: 20 },
      nombre:         { required: true, minlength: 3, maxlength: 100 },
      direccion:      { minlength: 5, maxlength: 150 },
      telefono:       { minlength: 8 },
      c_detracciones: { minlength: 6,  },
      c_bancaria:     { minlength: 6,  },
      cci:            { minlength: 6,  },
      banco:          { required: true },
      titular_cuenta: { minlength: 4 },
    },
    messages: {
      tipo_documento: { required: "Campo requerido.",  },
      num_documento:  { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre:         {required: "Campo requerido.", minlength: "MÍNIMO 3 caracteres.", maxlength: "como MÁXIMO 100 caracteres.", },
      direccion:      { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 150 caracteres.", },
      telefono:       { minlength: "MÍNIMO 9 caracteres.", },
      c_detracciones: { minlength: "MÍNIMO 6 caracteres", },
      c_bancaria:     { minlength: "MÍNIMO 6 caracteres", },
      cci:            { minlength: "MÍNIMO 6 caracteres", },
      banco:          { required: "Campo requerido.", },
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

  $("#banco").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

