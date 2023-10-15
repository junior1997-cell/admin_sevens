var tabla;

//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#mContableFinanciero").addClass("active");

  $("#lrecibo").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-recibo").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#tipo_documento").select2({ theme: "bootstrap4", placeholder: "Selec.", allowClear: true, });
  listar(localStorage.getItem("nube_idproyecto"));
  // Formato para telefono
  $("[data-mask]").inputmask();
}


// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

// abrimos el navegador de archivos
$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e,$("#doc2").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

// Eliminamos el doc 1
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

//Función limpiar
function limpiar() {
  $("#idrecibo_x_honorario").val("");
  $("#tipo_documento").val("");
  $("#num_documento").val("");
  $("#nombre").val("");
  $("#fecha_pago").val("");
  $("#monto").val("");
  $("#costo").val("");
  $("#servicio").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}


//Función Listar
function listar() { 

  var idproyecto = localStorage.getItem("nube_idproyecto");

  tabla = $("#tabla-recibo").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,1,2,3,4,5,6,7], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,1,2,3,4,5,6,7], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,1,2,3,4,5,6,7], }, orientation: 'landscape', pageSize: 'LEGAL',  }, 
      {extend: "colvis"} ,
    ],
    ajax: {
      url: `../ajax/recibo.php?op=listar&idproyecto=${idproyecto}`,
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
      if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap");  }
      // columna: 
      if (data[5] != "") { $("td", row).eq(5).addClass("text-nowrap text-center"); }
      // columna: 
      if (data[6] != "") { $("td", row).eq(6).addClass("text-nowrap"); }
      // columna: 
      if (data[7] != "") { $("td", row).eq(7).addClass("text-nowrap"); }
      // columna: 
      if (data[7] != "") { $("td", row).eq(7).addClass("text-nowrap"); }
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
     { targets: [3], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },

    ],
  }).DataTable();

  $(tabla).ready(function () {  $('.cargando').hide(); });
}

//ver Recibo por Honorario  ----- Voucher de Transferencia
function modal_comprobante(comprobante,nombres, carpeta) {
  var rh_vou=""; if (carpeta=="recibo") {rh_vou="Recibo por Honorario:";}else{rh_vou="Voucher de Transferencia:";}

  var dia_actual = moment().format('DD-MM-YYYY');
  $(".nombre_comprobante").html(` ${rh_vou} <span class="text-bold">${nombres}</span>`);
  $('#modal-ver-comprobante').modal("show");
  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'compra_rh', carpeta, '100%', '550'));

  if (DocExist(`dist/docs/compra_rh/${carpeta}/${comprobante}`) == 200) {
    $("#iddescargar").attr("href",`../dist/docs/compra_rh/${carpeta}/${comprobante}`).attr("download", `${rh_vou}-${nombres}  - ${dia_actual}`).removeClass("disabled");
    $("#ver_completo").attr("href",`../dist/docs/compra_rh/${carpeta}/${comprobante}`).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }

  $('.jq_image_zoom').zoom({ on:'grab' }); 

}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-recibo")[0]);

  $.ajax({
    url: "../ajax/recibo.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {
          Swal.fire("Correcto!", "El registro se guardo correctamente.", "success");
          tabla.ajax.reload(null, false);  
          limpiar();  
          $("#modal-agregar-recibo").modal("hide");  
        }else{  
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
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_recibo").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");          
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_recibo").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');      
    },
    complete: function () {
      $("#barra_progress_recibo").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');      
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idrecibo) {

  limpiar();
  
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-recibo").modal("show");

  $.post("../ajax/recibo.php?op=mostrar", { idrecibo_x_honorario: idrecibo }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {

      $("#idrecibo_x_honorario").val(e.data.idrecibo_x_honorario);
      $("#tipo_documento").val(e.data.tipo_documento).trigger("change");
      $("#num_documento").val(e.data.numero_documento);
      $("#nombre").val(e.data.nombres);
      $("#fecha_pago").val(e.data.fecha_pago);
      $("#monto").val(e.data.monto_total);
      $("#costo").val(e.data.costo_operacion);
      $("#servicio").val(e.data.servicio);

      if (e.data.recibo == "" || e.data.recibo == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#doc1_nombre").html('');

        $("#doc_old_1").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_1").val(e.data.recibo); 

        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.recibo)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.recibo,'compra_rh', 'recibo', '100%', '210' ));       
            
      }

      if (e.data.voucher == "" || e.data.voucher == null  ) {

        $("#doc2_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#doc2_nombre").html('');

        $("#doc_old_2").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_2").val(e.data.voucher); 

        $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.voucher)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc2_ver").html(doc_view_extencion(e.data.voucher,'compra_rh', 'voucher', '100%', '210' ));       
            
      }

      $('.jq_image_zoom').zoom({ on:'grab' });
      
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );

}

function eliminar(idrecibo, nombres) {

  crud_eliminar_papelera(
    "../ajax/recibo.php?op=desactivar",
    "../ajax/recibo.php?op=eliminar", 
    idrecibo, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del> ${nombres} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false);},
    false, 
    false, 
    false,
    false
  );
}


init();

$(function () {  

  $("#form-recibo").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      num_documento: { required: true },
      nombre: { required: true },
      fecha_pago: { required: true },
      monto: { required: true, min:0 },
      costo: { required: true, min:0 },
      servicio: { required: true },
    },
    messages: {
      num_documento: { required: "Ingresar número D.N.I.", },
      nombre: { required: "Ingresar nombre.", },
      fecha_pago: { required: "Ingresar fecha", },
      monto: { required: "Ingresar monto", number: 'Ingrese un número', min:'Mínimo 0'},
      costo: { required: "Ingresar costo", number: 'Ingrese un número', min:'Mínimo 0'},
      servicio: { required: "Es necesario rellenar el campo descripción"},
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


function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { }  else{    
    var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
    $('#ruc_proveedor').val(ruc);
  }
}

