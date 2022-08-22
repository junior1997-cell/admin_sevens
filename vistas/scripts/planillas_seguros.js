var tabla;
var idproyecto="",fecha_1="", fecha_2="", id_proveedor="", comprobante=""
//Función que se ejecuta al inicio
function init() {

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lPlanillaSeguro").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  //tbla_principal(localStorage.getItem('nube_idproyecto'));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#filtro_proveedor', null);
  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) {$("#submit-form-otro_servicio").submit();});

  // ══════════════════════════════════════ INITIALIZE SELECT2  ══════════════════════════════════════
  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Seleccinar proveedor", allowClear: true, });
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Seleccinar tipo comprobante", allowClear: true, });
  $("#forma_pago").select2({theme: "bootstrap4", placeholder: "Seleccinar forma de pago", allowClear: true, }); 
  $("#glosa").select2({theme: "bootstrap4", placeholder: "Seleccinar glosa", allowClear: true, }); 

  no_select_tomorrow('#fecha_p_s');

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });

  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });


  // Formato para telefono
  $("[data-mask]").inputmask();

}

$('.click-btn-fecha-inicio').on('click', function (e) {$('#filtro_fecha_inicio').focus().select(); });
$('.click-btn-fecha-fin').on('click', function (e) {$('#filtro_fecha_fin').focus().select(); });

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
  $("#idproveedor").val('').trigger("change");
  $("#idplanilla_seguro").val("");
  $("#fecha_p_s").val("");
  $("#precio_parcial").val(""); 
  $("#nro_comprobante").val("");
  $("#subtotal").val("");
  $("#igv").val("");
  $("#val_igv").val(""); 
  $("#tipo_gravada").val("");
  $("#glosa").val('SCTR').trigger("change"); 
  $("#descripcion").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function calc_total() {

  $(".nro_comprobante").html("Núm. Comprobante");

  var total         = es_numero($('#precio_parcial').val()) == true? parseFloat($('#precio_parcial').val()) : 0;
  var val_igv       = es_numero($('#val_igv').val()) == true? parseFloat($('#val_igv').val()) : 0;
  var subtotal      = 0; 
  var igv           = 0;

  console.log(total, val_igv); console.log($('#precio_parcial').val(), $('#val_igv').val()); console.log('----------');

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

//--------------------

function quitar_igv_del_precio(precio , igv, tipo ) {
  //console.log(precio , igv, tipo);
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


//Función Listar
function tbla_principal(idproyecto,fecha_1, fecha_2, id_proveedor, comprobante) {
  
  tabla=$('#tabla-otro_servicio').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,5,6,7,8,9], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,5,6,7,8,9], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,3,5,6,7,8,9], } }, {extend: "colvis"} ,

    ],
    ajax:{
      url: `../ajax/planillas_seguros.php?op=tbla_principal&idproyecto=${idproyecto}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [2], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      //{ targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();

  total(idproyecto,fecha_1, fecha_2, id_proveedor, comprobante);

  $(tabla).ready(function () {  $('.cargando').hide(); });
}

function total(idproyecto,fecha_1, fecha_2, id_proveedor, comprobante) {   
  $("#total_monto").html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);

  $.post("../ajax/planillas_seguros.php?op=total", { idproyecto: idproyecto,fecha_1: fecha_1,fecha_2: fecha_2,id_proveedor: id_proveedor,comprobante: comprobante }, function (e, status) {

    e = JSON.parse(e);  //console.log(e);  

    $("#total_monto").html(formato_miles(e.data.precio_parcial));
  });
}

//ver ficha tecnica
function modal_comprobante(comprobante){
  
  //console.log(extencion);
  $('#ver_fact_pdf').html('');
  
  $('#modal-ver-comprobante').modal("show");

  $("#descargar").attr("href","../dist/docs/planilla_seguro/comprobante/"+comprobante);
  $('#ver_grande').attr("href", "../dist/docs/planilla_seguro/comprobante/"+comprobante);
  $('#ver_documento').html(doc_view_extencion(comprobante, 'planilla_seguro', 'comprobante', '100%', '550'));

  $('.jq_image_zoom').zoom({ on:'grab' }); 
  $(".tooltip").removeClass("show").addClass("hidde");

}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-otro_servicio")[0]);
 
  $.ajax({
    url: "../ajax/planillas_seguros.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  //console.log(e);  

        if (e.status == true) {

          Swal.fire("Correcto!", "Panilla Seguro guardado correctamente", "success");
          tabla.ajax.reload(null, false);       
          total(localStorage.getItem('nube_idproyecto'));  
          limpiar();
          $("#modal-agregar-otro_servicio").modal("hide");       

        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

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

function mostrar(idplanilla_seguro) {
  limpiar();
  //$("#proveedor").val("").trigger("change"); 
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-otro_servicio").modal("show")
  
  $("#tipo_comprobante").val("").trigger("change"); 
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/planillas_seguros.php?op=mostrar", { idplanilla_seguro: idplanilla_seguro }, function (e, status) {

    e = JSON.parse(e);  //console.log(e); 
    if (e.status == true) {

      $("#idproveedor").val(e.data.idproveedor).trigger("change");
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change"); 
      $("#forma_pago").val(e.data.forma_de_pago).trigger("change");

      $("#idplanilla_seguro").val(e.data.idplanilla_seguro);
      $("#fecha_p_s").val(e.data.fecha_p_s); 
      $("#nro_comprobante").val(e.data.numero_comprobante);

      $("#precio_parcial").val(parseFloat(e.data.costo_parcial).toFixed(2)); 
    
      $("#subtotal").val(parseFloat(e.data.subtotal).toFixed(2));

      $("#igv").val(parseFloat(e.data.igv).toFixed(2));

      $("#tipo_gravada").val(e.data.tipo_gravada);

      $("#val_igv").val(e.data.val_igv).trigger("change");

      $("#glosa").val(e.data.glosa).trigger("change"); 

      $("#descripcion").val(e.data.descripcion);

      if (e.data.comprobante == "" || e.data.comprobante == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
        $("#doc1_nombre").html('');
        $("#doc_old_1").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_1").val(e.data.comprobante); 
        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante, 'planilla_seguro', 'comprobante', '100%'));        
      }

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide(); 

      $('.jq_image_zoom').zoom({ on:'grab' }); 
      $(".tooltip").removeClass("show").addClass("hidde");


    } else {
      ver_errores(2);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function ver_detalle(idplanilla_seguro) {
  $('#detalle_html').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div></div>`);

  $("#modal-ver-detalle").modal("show")

  var comprobante=''; var btn_comprobante = '';

  $.post("../ajax/planillas_seguros.php?op=ver_detalle", { idplanilla_seguro: idplanilla_seguro }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 

    if (e.data.comprobante != '') {
        
      comprobante =  doc_view_extencion(e.data.comprobante, 'planilla_seguro', 'comprobante', '100%');
      
      btn_comprobante=`
      <div class="row">
        <div class="col-6"">
          <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/planilla_seguro/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
        </div>
        <div class="col-6"">
          <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/planilla_seguro/comprobante/${e.data.comprobante}" download="${removeCaracterEspecial(e.data.tipo_comprobante+' '+e.data.numero_comprobante)} - ${removeCaracterEspecial(e.data.razon_social)}"> <i class="fas fa-download"></i></a>
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
                <td><span class="text-primary" >${e.data.razon_social}</span> <br> ${e.data.tipo_documento}: ${e.data.ruc}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Fecha</th>
                <td>${format_d_m_a(e.data.fecha_p_s)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>${e.data.tipo_comprobante}</th>
                <td>${e.data.numero_comprobante}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Forma de pago</th>
                  <td>${e.data.forma_de_pago}</td>
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
                <th>Val IGV</th>
                <td>${e.data.val_igv}</td>
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
                  <th>Comprob.</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>`;
  
    $("#detalle_html").html(data_html);


    $('.jq_image_zoom').zoom({ on:'grab' }); 
    $(".tooltip").removeClass("show").addClass("hidde");

  });
}


//Función para desactivar registros
function eliminar(idplanilla_seguro, nombre) {
  crud_eliminar_papelera(
    "../ajax/planillas_seguros.php?op=desactivar",
    "../ajax/planillas_seguros.php?op=eliminar", 
    idplanilla_seguro, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false); total(localStorage.getItem('nube_idproyecto')); },
    false, 
    false, 
    false,
    false
  );
}

//Función para activar registros
function activar(idplanilla_seguro) {
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
      $.post("../ajax/planillas_seguros.php?op=activar", { idplanilla_seguro: idplanilla_seguro }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla.ajax.reload(null, false);
        total(localStorage.getItem('nube_idproyecto'));
      });
      
    }
  });      
}

init();

$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").on("change", function () { $(this).trigger("blur"); });
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });
  $("#glosa").on("change", function () { $(this).trigger("blur"); });

  $("#form-otro_servicio").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      forma_pago: { required: true },
      tipo_comprobante: { required: true },
      fecha_p_s	: { required: true },
      cantidad:{required: true},
      precio_parcial:{required: true},
      descripcion:{required: true},
      val_igv: { required: true, number: true, min:0, max:1 },
      // terms: { required: true },
    },
    messages: {
      forma_pago: { required: "Por favor una forma de pago", },
      tipo_comprobante: { required: "Por favor seleccionar tipo comprobante", },
      fecha_p_s	: { required: "Por favor ingrese una fecha", },
      precio_parcial:  { required: "Ingresar monto", },
      descripcion:  { required: "Es necesario rellenar el campo descripción", },
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

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#idproveedor").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#glosa").rules("add", { required: true, messages: { required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

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

  tbla_principal(localStorage.getItem('nube_idproyecto'),fecha_1, fecha_2, id_proveedor, comprobante);
}

function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { }  else{    
    var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
    $('#ruc_proveedor').val(ruc);
  }
}