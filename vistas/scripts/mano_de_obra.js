var tabla_comprobantes;
var tabla_pension;
var tabla_detalle_pension;

var nube_idproyecto_r = '', fecha_1_r = '' , fecha_2_r = '' , id_proveedor_r = '' , comprobante_r = '';

//Función que se ejecuta al inicio
function init() {  

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lManodeObra").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));


  tbla_principal( localStorage.getItem('nube_idproyecto'), fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r); 

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#filtro_proveedor', null);
    
  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_mdo").on("click", function (e) {$("#submit-form-mdo").submit();});
  
  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Seleccionar", allowClear: true, });

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });
  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  
  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $('#monto').number( true, 2 );

  // Bloquemos las fechas has hoy
  no_select_tomorrow('#fecha_inicial');
  no_select_tomorrow("#fecha_inicial");

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

// .....:::::::::::::::::::::::::::::::::::::  P E N S I O N  :::::::::::::::::::::::::::::::::::::::..
function limpiar_form_mdo() {
  $(".name-modal-header").html('Agregar Mano de Obra')
  $("#idpension").val("");
  $("#p_desayuno").val("");
  $("#p_almuerzo").val("");
  $("#p_cena").val("");
  $("#descripcion_pension").val("");
  $("#idproveedor").val("null").trigger("change"); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Guardar y editar
function guardar_y_editar_mdo(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-agregar-mdo")[0]);
 
  $.ajax({
    url: "../ajax/mano_de_obra.php?op=guardar_y_editar_mdo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {      
      try {
        e = JSON.parse(e); console.log(e); 
        if (e.status == true) {
          Swal.fire("Correcto!", "Mano de Obra guardado correctamente", "success");
          tbla_principal( nube_idproyecto_r, fecha_1_r, fecha_2_r, id_proveedor_r, comprobante_r);  
          $("#modal-agregar-mdo").modal("hide");  
          limpiar_form_mdo();
        }else{  
          ver_errores(e);
          $("#modal-agregar-mdo").modal("hide");  
          limpiar_form_mdo();
        } 
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_mdo").html('Guardar Cambios').removeClass('disabled');         
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_mdo").css({"width": percentComplete+'%'});
          $("#barra_progress_mdo").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_mdo").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_mdo").css({ width: "0%",  });
      $("#barra_progress_mdo").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_mdo").css({ width: "0%", });
      $("#barra_progress_mdo").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

//Función Listar
function tbla_principal(nube_idproyecto, fecha_1, fecha_2, id_proveedor, comprobante) {

  nube_idproyecto_r = nube_idproyecto; fecha_1_r = fecha_1; fecha_2_r = fecha_2; id_proveedor_r = id_proveedor; comprobante_r = comprobante;

  tabla_pension = $('#tabla-mdo').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,7,8,9,3,4,5,6], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,7,8,9,3,4,5,6], } }, 
      { extend: 'pdfHtml5', footer: true, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,7,8,9,3,4,5,6], } } ,
    ],
    ajax:{
      url: `../ajax/mano_de_obra.php?op=tabla_principal&nube_idproyecto=${nube_idproyecto}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center text-nowrap');  }
      if (data[3] != '') { $("td", row).eq(3).addClass('text-center text-nowrap'); }
      if (data[4] != '') {$("td", row).eq(4).addClass('text-center text-nowrap');} 
      if (data[5] != '') {$("td", row).eq(5).addClass('text-nowrap');}  
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
      { targets: [5], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      { targets: [3,4], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      { targets: [7,8,9], visible: false, searchable: false, },    
    ],
  }).DataTable();

  $.post("../ajax/mano_de_obra.php?op=total_mdo", { 'idproyecto': nube_idproyecto, 'fecha_1': fecha_1, 'fecha_2': fecha_2, 'id_proveedor': id_proveedor, 'comprobante': comprobante }, function (e, status) {
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {
      $("#total_pension").html(formato_miles(convertir_a_numero(e.data.total)));      
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );  
}

//mostrar
function mostrar_editar_mdo(idmano_de_obra) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_form_mdo();
  $(".name-modal-header").html('Editar Mano de Obra')
  $("#modal-agregar-mdo").modal("show");

  $.post("../ajax/mano_de_obra.php?op=mostrar_mdo", { idmano_de_obra: idmano_de_obra }, function (e, status) {

    e = JSON.parse(e); console.log(e);   

    if (e.status == true) {

      $("#idproveedor").val(e.data.idproveedor).trigger("change"); 
      $("#idproyecto").val(e.data.idproyecto);
      $("#idmano_de_obra").val(e.data.idmano_de_obra);
      $("#fecha_inicial").val(e.data.fecha_inicial);
      $("#fecha_final").val(e.data.fecha_final);
      $("#monto").val(e.data.monto);
      $("#descripcion").val(e.data.descripcion);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

// ver detallles del registro
function ver_detalle_mdo(idmano_de_obra){

  $(".tooltip").removeClass("show").addClass("hidde");

  $('#html_detalle_mdo').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div></div>`);

  $("#modal-ver-detalle-mdo").modal("show");

  $.post("../ajax/mano_de_obra.php?op=mostrar_mdo", { 'idmano_de_obra': idmano_de_obra }, function (e, status) {

    e = JSON.parse(e);  //console.log(e); 
    
    if (e.status == true) {

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>                          
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Proveedor</th>
                  <td> ${e.data.razon_social}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>${e.data.tipo_documento}</th>
                  <td>${e.data.ruc}</td>
                </tr>     
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha Inicial</th>
                  <td>${e.data.fecha_inicial}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha Final</th>
                    <td>${e.data.fecha_final}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Monto</th>
                  <td>${e.data.monto}</td>
                </tr>             
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td><textarea cols="30" rows="2" class="textarea_datatable" readonly="">${e.data.descripcion}</textarea></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $("#html_detalle_mdo").html(retorno_html);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").on("change", function () { $(this).trigger("blur"); });

  $("#form-agregar-mdo").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproveedor:  { required: true},
      fecha_inicial:{ required: true},
      fecha_final:  { required: true},
      monto:        { required: true},
    },
    messages: {
      idproveedor:  { required: "Campo requerido", },
      fecha_inicial:{ required: "Campo requerido",},
      fecha_final:  { required: "Campo requerido",},
      monto:        { required: "Campo requerido",},
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
      guardar_y_editar_mdo(e);
    }
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#idproveedor").rules("add", { required: true, messages: { required: "Campo requerido" } });

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

  tbla_principal(nube_idproyecto_r, fecha_1, fecha_2, id_proveedor, comprobante);
}

function restrigir_fecha_input() {  restrigir_fecha_ant("#fecha_final",$("#fecha_inicial").val());}

function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { }  else{
    
    var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
    $('#ruc_proveedor').val(ruc);
  }
}