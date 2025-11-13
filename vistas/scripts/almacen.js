var tbla_resumen, tabla_x_dia, tabla_saldo_anterior;
var array_doc = [];
var id_almacen_s_r = '';
var idproyecto_r = '', fip_r = '' ; ffp_r = '' ; fpo_r  = '';
//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");
  $("#mTecnico").addClass("active");
  $("#lAlmacen").addClass("active bg-primary");

  var idproyecto =  localStorage.getItem("nube_idproyecto");
  listar_botones_q_s(idproyecto);
  tabla_resumen();
  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2(`../ajax/almacen.php?op=select2Productos&idproyecto=${idproyecto}`, '#producto_tup', null, '.cargando_producto_tup');         // transferencia uso de obra
  lista_select2(`../ajax/almacen.php?op=select2ProductosMasEPP&idproyecto=${idproyecto}`, '#producto_tep', null, '.cargando_producto_tep');   // transferencia entre proyectos
  lista_select2(`../ajax/almacen.php?op=select2ProductosMasEPP&idproyecto=${idproyecto}`, '#producto_tag', null, '.cargando_productos_tag');  // transferencia almacen general
  lista_select2(`../ajax/almacen.php?op=select2Proyecto`, '#proyecto_tep', null, '.cargando_proyecto_tep');

  lista_select2(`../ajax/almacen.php?op=select2UnidadMedida`, '#filtro_tm_unidad_medida', null, '.cargando_proyecto_tep');
  lista_select2(`../ajax/almacen.php?op=select2Categoria`, '#filtro_tm_categoria', null, '.cargando_proyecto_tep');
  
  // lista_select2(`../ajax/almacen.php?op=select2ProductosTodos&idproyecto=${idproyecto}`, '#producto_xp', null, '.cargando_productos');

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_almacen_tup").on("click", function (e) { if ($(this).hasClass('send-data') == false) { $("#submit-form-almacen-tup").submit(); } });  
  $("#guardar_registro_almacen_tep").on("click", function (e) { if ($(this).hasClass('send-data') == false) { $("#submit-form-almacen-tep").submit(); } });
  $("#guardar_registro_almacen_tag").on("click", function (e) { if ($(this).hasClass('send-data') == false) { $("#submit-form-almacen-tag").submit(); } });
  $("#guardar_registro_almacen_x_dia").on("click", function (e) { $("#submit-form-almacen-x-dia").submit(); });
  $(".btn_guardar_s").on("click", function (e) { $("#submit-form-almacen-sa").submit(); });

  $(".btn-guardar-tm").on("click", function (e) { if ($(this).hasClass('send-data') == false) { $("#submit-form-almacen-tm").submit(); } });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#producto_tup").select2({theme: "bootstrap4", placeholder: "Selecione producto", allowClear: true, });                                   // tranferencia uso de obra  
  $("#producto_tep").select2({theme: "bootstrap4", placeholder: "Selecione producto", allowClear: true, });                                   // tranferencia entre proyectos
  $("#proyecto_tep").select2({templateResult: templateProyecto, theme: "bootstrap4", placeholder: "Selecione proyecto", allowClear: true, }); // tranferencia entre proyectos
  $("#producto_tag").select2({theme: "bootstrap4", placeholder: "Selecione producto", allowClear: true, });                                   // transferencia almacen general
  $("#producto_xp").select2({theme: "bootstrap4", placeholder: "Selecione producto", allowClear: true, });

  $("#filtro_tm_unidad_medida").select2({theme: "bootstrap4", placeholder: "Unidad Medida", allowClear: true, });
  $("#filtro_tm_categoria").select2({theme: "bootstrap4", placeholder: "Categoria", allowClear: true, });
  $("#filtro_tm_es_epp").select2({theme: "bootstrap4", placeholder: "EPP", allowClear: true, });
  

  $("#idproyecto_xp").val(localStorage.getItem("nube_idproyecto"));

  $("#fecha_tup").attr('min', localStorage.getItem("nube_fecha_inicial_actividad")).attr('max', localStorage.getItem("nube_fecha_final_actividad"));
  $("#fecha_tep").attr('min', localStorage.getItem("nube_fecha_inicial_actividad")).attr('max', localStorage.getItem("nube_fecha_final_actividad"));
  $("#fecha_tag").attr('min', localStorage.getItem("nube_fecha_inicial_actividad")).attr('max', localStorage.getItem("nube_fecha_final_actividad"));
  $("#fecha_tm").attr('min', localStorage.getItem("nube_fecha_inicial_actividad")).attr('max', localStorage.getItem("nube_fecha_final_actividad"));
  // Formato para telefono
  $("[data-mask]").inputmask();

}

function templateProyecto (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var estado = state.element.attributes.estado.value == '' || state.element.attributes.estado.value == null ? '' : state.element.attributes.estado.value ;
  var class_color = (estado == 'Terminado' ? 'badge-success' : (estado == 'En proceso' ? 'badge-warning' : 'badge-danger' ) );
  var estado_span = `<span class="text-center badge ${class_color} font-size-10px">${estado}</span>`;   
  var $state = $(`<span>${state.text} ${estado_span}</span>`);
  return $state;
};

function show_hide_tablas(flag) {

  if (flag == 1) {                    // TAABLA PRINCIPAL
    $(".card-almacen-1").show();
    $(".card-almacen-2").hide();
    $("#div_tabla_principal").show();
    $("#div_tabla_almacen").hide();$("#div_tabla_almacen_search").hide();
    $("#cargando-table-almacen").hide();
    $(".btn-regresar").hide();
  } else if (flag == 2) {             // TAABLA LISTA
    $(".card-almacen-1").show();
    $(".card-almacen-2").hide();
    $("#div_tabla_principal").hide();
    $("#div_tabla_almacen").hide(); $("#div_tabla_almacen_search").show();
    $("#cargando-table-almacen").show();
    $(".btn-regresar").show();
  } else if (flag == 3) {             // TAABLA TRANFERENCIA MASIVA
    $(".card-almacen-1").hide();
    $(".card-almacen-2").show();   
  }
}

function listar_botones_q_s(nube_idproyecto) {

  $('#lista_quincenas').html('<div class="my-3" ><i class="fas fa-spinner fa-pulse fa-2x"></i>&nbsp;&nbsp;&nbsp;Cargando...</div>');

  //Listar quincenas(botones)
  $.post("../ajax/asistencia_obrero.php?op=listar_s_q_botones", { nube_idproyecto: nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); console.log(e);
    var id_proyecto = localStorage.getItem('nube_idproyecto');
    var nube_fecha_pago_obrero = localStorage.getItem('nube_fecha_pago_obrero');

    var q_s_btn = "", q_s_dias = '' ;
    if (nube_fecha_pago_obrero == "quincenal") { q_s_btn = 'Quincena'; q_s_dias ='14'; } else if (nube_fecha_pago_obrero == "semanal") {  q_s_btn = 'Semana'; q_s_dias ='7' }

    if (e.status == true) {      
      
      if ( id_proyecto == null || id_proyecto == '' || id_proyecto == '0' ) { // validamos si abrio el proyecto
        $('#lista_quincenas').html(`<div class="alert alert-danger alert-dismissible w-450px">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
          <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
          Lo mas probable es que no hayas selecionado un proyecto. <br>Clic en el <span class="bg-color-8eff27 p-1 rounded-lg text-dark"> <i class="fa-solid fa-screwdriver-wrench"></i> boton verde</span> para seleccionar alguno.
        </div>`);        
      } else {
        var fecha_inicio = e.data.proyecto.fecha_inicio_actividad;  
        var fecha_fin    = e.data.proyecto.fecha_fin_actividad;
        if ( fecha_inicio == null || fecha_inicio == '' ||  fecha_fin == null || fecha_fin == '') {  // validamos si tiene las fechas
          $('#lista_quincenas').html(`<div class="alert alert-danger alert-dismissible w-450px">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
            <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
            No has definido las de <b>fechas de actividad</b> d del proyecto. <br>Clic en el <span class="bg-green p-1 rounded-lg"> <i class="far fa-calendar-alt"></i> boton verde</span> para actualizar las fechas de actividad.
          </div>`);
        } else {       
           
          var htm_btn = '';          
          e.data.btn_asistencia.forEach((val, key) => {
            htm_btn = htm_btn.concat(` <button type="button" id="boton-${(key+1)}" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="por_fecha('${val.ids_q_asistencia}', '${val.fecha_q_s_inicio}', '${val.fecha_q_s_fin}', '${(key+1)}', ${q_s_dias});"><i class="far fa-calendar-alt"></i> ${q_s_btn} ${val.numero_q_s}<br>${format_d_m_a(val.fecha_q_s_inicio)} // ${format_d_m_a(val.fecha_q_s_fin)}</button>`);             
          });   

          $('#lista_quincenas').html(`<button type="button" id="boton-0" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="todos_almacen();"><i class="far fa-calendar-alt"></i><br> Todos</button> 
          ${htm_btn}`);  
          // todos_almacen();   
        }
      }        
    } else {
      ver_errores(e);
    }
    
    //console.log(fecha);
  }).fail( function(e) { ver_errores(e); } );
}

// .....:::::::::::::::::::::::::::::::::::::  A L M A C E N   R E S U M E N   :::::::::::::::::::::::::::::::::::::::..

function tabla_resumen() {

  var idproyecto =  localStorage.getItem("nube_idproyecto");
  var fip =  localStorage.getItem("nube_fecha_inicial_actividad");
  var ffp =  localStorage.getItem("nube_fecha_final_actividad");
  var fpo =  localStorage.getItem("nube_fecha_pago_obrero");

  tbla_resumen = $('#tabla-almacen-resumen').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    // buttons: [
    //   { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,9,10,11,3,4,12,13,14,15,16,5,], } }, 
    //   { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,9,10,11,3,4,12,13,14,15,16,5,], } }, 
    //   { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,9,10,11,3,4,12,13,14,15,16,5,], } }, 
    // ],
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate"></i> ', className: "buttons-reload px-3 btn btn-sm btn-outline-info", action: function ( e, dt, node, config ) { if (tbla_resumen) { tbla_resumen.ajax.reload(null, false); toastr_success('Actualizado', 'Tabla actualizada'); } } },
      { extend: 'copy', exportOptions: { columns: [0,2,3,4,5,6,7], }, text: `<i class="fas fa-copy" ></i>`, className: "px-3 btn btn-sm btn-outline-dark", footer: true,  }, 
      { extend: 'excel', exportOptions: { columns: [0,2,3,4,5,6,7], }, title: 'Lista de Almacen', text: `<i class="far fa-file-excel fa-lg" ></i>`, className: "px-3 btn btn-sm btn-outline-success", footer: true,  }, 
      { extend: 'pdf', exportOptions: { columns: [0,2,3,4,5,6,7], }, title: 'Lista de Almacen', text: `<i class="far fa-file-pdf fa-lg"></i>`, className: "px-3 btn btn-sm btn-outline-danger", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "px-3 btn btn-sm btn-outline-primary", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    ajax:{
      url: `../ajax/almacen.php?op=tabla-almacen-resumen&id_proyecto=${idproyecto}&fip=${fip}&ffp=${ffp}&fpo=${fpo}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);  ver_errores(e);
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
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); } 
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap text-center'); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 25,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      // { targets: [9, 10, 11, 12, 13, 14, 15, 16,17], visible: false, searchable: false, }, 
    ],
  }).DataTable();
}

// .....::::::::::::::::::::::::::::::::::::: A L M A C E N   P R O Y E C T O   D E T A L L E S  :::::::::::::::::::::::::::::::::::::::..

function todos_almacen() {
  $('.data_tbody_almacen').html(''); $('#div_tabla_almacen').css({'height':`auto`});
  pintar_boton_selecionado(0); show_hide_tablas(2);
  var idproyecto =  localStorage.getItem("nube_idproyecto");
  var fip =  localStorage.getItem("nube_fecha_inicial_actividad");
  var ffp =  localStorage.getItem("nube_fecha_final_actividad");
  var fpo =  localStorage.getItem("nube_fecha_pago_obrero");
  
  idproyecto_r = idproyecto ; fip_r = fip ; ffp_r = ffp ; fpo_r  = fpo;

  $('#cargando-table-almacen').html(`<div class="col-12 text-center"><span class="spinner-border spinner-border-xl"></span> <br> <span class="text-olas-mar-letra">Cargando...</span></div>`);
  const text = document.querySelector('.text-olas-mar-letra');  text.innerHTML = text.textContent.split('').map((char, i) => `<span style="--i:${i}">${char}</span>` ).join('');        

  
  $.post("../ajax/almacen.php?op=tabla_almacen", { 'id_proyecto': idproyecto, 'nombre_insumo': '', 'fip': fip, 'ffp':ffp, 'fpo': fpo }, function (e, status) {

    // e = JSON.parse(e); console.log(e);

    $('.tabla_almacen').html(e);        

    $('#div_tabla_almacen').show(); 
    $('#cargando-table-almacen').hide();
    $('[data-toggle="tooltip"]').tooltip();
    scroll_tabla_asistencia();
   
  }).fail(function (e) { ver_errores(e); });
  
}

function por_fecha(ids_q_asistencia, fecha_q_s_inicio, fecha_q_s_fin, i, q_s_dias ) {
  $('.data_tbody_almacen').html(''); $('#div_tabla_almacen').css({'height':`auto`});
  pintar_boton_selecionado(i); show_hide_tablas(2);
  var idproyecto =  localStorage.getItem("nube_idproyecto");
  var fip =  fecha_q_s_inicio
  var ffp =  fecha_q_s_fin
  var fpo =  localStorage.getItem("nube_fecha_pago_obrero");
  idproyecto_r = idproyecto ; fip_r = fip ; ffp_r = ffp ; fpo_r  = fpo;

  $('#div_tabla_almacen').hide();
  $('#cargando-table-almacen').html(`<div class="col-12 text-center"><span class="spinner-border spinner-border-xl"></span> <br> <span class="text-olas-mar-letra">Cargando...</span></div>`);
  const text = document.querySelector('.text-olas-mar-letra');  text.innerHTML = text.textContent.split('').map((char, i) => `<span style="--i:${i}">${char}</span>` ).join('');        

  
  $.post("../ajax/almacen.php?op=tabla_almacen", { 'id_proyecto': idproyecto, 'nombre_insumo': '', 'fip': fip, 'ffp':ffp, 'fpo': fpo }, function (e, status) {

    // e = JSON.parse(e); console.log(e);
    $('.tabla_almacen').html(e); 

    $('#div_tabla_almacen').show(); 
    $('#cargando-table-almacen').hide();
    $('[data-toggle="tooltip"]').tooltip();
    scroll_tabla_asistencia();

  }).fail(function (e) { ver_errores(e); });
}

function calcular_saldo(input, id) {
  var input_sa = $(input).val() == '' || $(input).val() == null ? 0 :  quitar_formato_miles($(input).val() ) ;
  var input_et = $(`.entrada_total_${id}`).text() == '' || $(`.entrada_total_${id}`).text() == null ? 0 : quitar_formato_miles($(`.entrada_total_${id}`).text()) ;
  var input_st = $(`.salida_total_${id}`).text() == '' || $(`.salida_total_${id}`).text() == null ? 0 : quitar_formato_miles($(`.salida_total_${id}`).text()) ;
 
  var calculo  = (input_sa + input_et) - input_st ; //console.log(`${calculo}  = (${input_sa} + ${input_et}) - ${input_st}`);
  $(`.saldo_total_${id}`).html( formato_miles(calculo) );
  // data_input.each(function(val, key) { 
  //   hora +=  $(this).val() == null || $(this).val() == '' || $(this).val() == 0 ? 0 : parseFloat($(this).val());    
  //   console.log( hora );     
  // });
}


function buscar_producto() {
  

  var nombre_insumo = $('#buscar_insumo').val() == '' || $('#buscar_insumo').val() == null ? '' : $('#buscar_insumo').val() ;
  console.log(nombre_insumo);

  show_hide_tablas(2);
  $('#div_tabla_almacen').hide();
  $('#cargando-table-almacen').html(`<div class="col-12 text-center"><span class="spinner-border spinner-border-xl"></span> <br> <span class="text-olas-mar-letra">Cargando...</span></div>`);
  const text = document.querySelector('.text-olas-mar-letra');  text.innerHTML = text.textContent.split('').map((char, i) => `<span style="--i:${i}">${char}</span>` ).join('');        


  delay(function(){ // Retrasamos la busqueda  
    

    $.post("../ajax/almacen.php?op=tabla_almacen", { 'id_proyecto': idproyecto_r, 'nombre_insumo': nombre_insumo, 'fip': fip_r, 'ffp':ffp_r, 'fpo': fpo_r }, function (e, status) {

      $('.tabla_almacen').html(e); 

      $('#div_tabla_almacen').show(); 
      $('#cargando-table-almacen').hide();
      $('[data-toggle="tooltip"]').tooltip();
      scroll_tabla_asistencia();

    }).fail(function (e) { ver_errores(e); });

  }, 40 );
}

// .....::::::::::::::::::::::::::::::::::::: E D I T A R   P R O Y E C T O   A L M A C E N  :::::::::::::::::::::::::::::::::::::::..
function limpiar_form_almacen_x_dia() {

  $('#idalmacen_x_proyecto_xp').val('');
  $('#producto_xp').val('').trigger("change");  
  $('#fecha_ingreso_xp').val('');  
  $('#dia_ingreso_xp').val('');  
  $('#cantidad_xp').val('');  
  $('#marca_xp').html('');  

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function show_hide_form_x_dia(flag) {
  if (flag == 1) { // tabla principal
    $('.div-tabla-ver-almacen-x-dia').show();
    $('#form-almacen-x-dia').hide();

    $('.btn-regresar-x-dia').hide();
    $('#guardar_registro_almacen_x_dia').hide();
  } else if (flag == 2) { // formulario
    $('.div-tabla-ver-almacen-x-dia').hide();
    $('#form-almacen-x-dia').show();

    $('.btn-regresar-x-dia').show();
    $('#guardar_registro_almacen_x_dia').show();
  }
}

function modal_ver_almacen(fecha, idalmacen_resumen, tipo_mov = '') {
  
  $('#modal-ver-almacen').modal('show');

  tabla_x_dia = $('#tabla-ver-almacen').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate"></i> ', className: "buttons-reload px-3 btn btn-sm btn-outline-info", action: function ( e, dt, node, config ) { if (tabla_x_dia) { tabla_x_dia.ajax.reload(null, false); toastr_success('Actualizado', 'Tabla actualizada'); } } },
      { extend: 'copy', exportOptions: { columns: [0,2,3,4,5,6], }, text: `<i class="fas fa-copy" ></i>`, className: "px-3 btn btn-sm btn-outline-dark", footer: true,  }, 
      { extend: 'excel', exportOptions: { columns: [0,2,3,4,5,6], }, title: 'Lista de Almacen', text: `<i class="far fa-file-excel fa-lg" ></i>`, className: "px-3 btn btn-sm btn-outline-success", footer: true,  }, 
      // { extend: 'pdf', exportOptions: { columns: [0,2,3,4,5,6], }, title: 'Lista de Almacen', text: `<i class="far fa-file-pdf fa-lg"></i>`, className: "px-3 btn btn-sm btn-outline-danger", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      // { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "px-3 btn btn-sm btn-outline-primary", exportOptions: { columns: "th:not(:last-child)", }, },
    ],    
    ajax:{
      url: `../ajax/almacen.php?op=tbla-ver-almacen-detalle&idalmacen_resumen=${idalmacen_resumen}&fecha=${fecha}&tipo_mov=${tipo_mov}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);  ver_errores(e);
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
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); }      
      // columna: 3
      if (data[2] != '') { $("td", row).eq(2).addClass('text-nowrap'); }
      
    },
    language: {
      lengthMenu: "Mostrar: _MENU_",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    footerCallback: function( tfoot, data, start, end, display ) {
      var api1 = this.api(); var total1 = api1.column( 4 ).data().reduce( function ( a, b ) { return parseFloat(a) + parseFloat(b); }, 0 );      
      $( api1.column( 4 ).footer() ).html( `<span class="text-center">${formato_miles(total1)}</span>` );      
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      // { targets: [9, 10, 11, 12, 13, 14, 15, 16,17], visible: false, searchable: false, }, 
      { targets: [4], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="${color}">${number}</span>`; } return number; }, },
      { targets: [1], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
    ],
  }).DataTable();
}

function ver_editar_almacen_x_dia(id_almacen_s, idproducto) {
  id_almacen_s_r = id_almacen_s;
  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();
  $('.chargue_edit_marca').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`); $('#marca_xp').html('');
  show_hide_form_x_dia(2); limpiar_form_almacen_x_dia();
  $.post(`../ajax/almacen.php?op=ver_almacen`, {'id_proyecto': localStorage.getItem("nube_idproyecto"), 'id_almacen_s': id_almacen_s, 'id_producto': idproducto }, function (e, textStatus, jqXHR) {
    e = JSON.parse(e);   console.log(e);
    if (e.status == true) {
      $('#idalmacen_salida_xp').val(e.data.idalmacen_salida);
      $('#idalmacen_resumen_xp').val(e.data.idalmacen_resumen);
      $('#producto_xp').val(e.data.idproducto).trigger('change');
      $('#fecha_ingreso_xp').val(e.data.fecha_ingreso);
      $('#dia_ingreso_xp').val(e.data.dia_ingreso);
      $('#cantidad_xp').val(e.data.cantidad);
      
      e.data.marca_array.forEach((val, key) => {
        if (val.marca == e.data.marca ) {
          $('#marca_xp').append(`<option selected value="${val.marca}">${val.marca}</option>`);
        } else {
          $('#marca_xp').append(`<option value="${val.marca}">${val.marca}</option>`);          
        }        
      });
      $('.chargue_edit_marca').html('');
      $("#cargando-7-fomulario").show();
      $("#cargando-8-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); });
}

function cambiar_producto_salida(val_input) {
  var idproducto = $(val_input).val();
  $('.chargue_edit_marca').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`); $('#marca_xp').html('');
  if (idproducto == '' || idproducto == null ) { } else {
    $.post(`../ajax/almacen.php?op=ver_almacen`, {'id_proyecto': localStorage.getItem("nube_idproyecto"), 'id_almacen_s': id_almacen_s_r, 'id_producto': idproducto }, function (e, textStatus, jqXHR) {
      e = JSON.parse(e);   console.log(e);
      if (e.status == true) {          
        e.data.marca_array.forEach((val, key) => {
          if (val.marca == e.data.marca ) {
            $('#marca_xp').append(`<option selected value="${val.marca}">${val.marca}</option>`);
          } else {
            $('#marca_xp').append(`<option value="${val.marca}">${val.marca}</option>`);          
          }        
        });
        $('.chargue_edit_marca').html('');
        
      } else {
        ver_errores(e);
      }
    }).fail( function(e) { ver_errores(e); });
  }  
}

//Función para guardar o editar
function guardar_y_editar_almacen_x_dia(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-almacen-x-dia")[0]);

  $.ajax({
    url: "../ajax/almacen.php?op=guardar_y_editar_almacen_x_dia",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) { 
          tabla_x_dia.ajax.reload(null, false);   
          limpiar_form_almacen_x_dia();
          Swal.fire("Correcto!", "Almacen guardado correctamente", "success");          
          show_hide_form(1);
          todos_almacen();
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_almacen_x_dia").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_almacen_x_dia").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_almacen_x_dia").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_almacen_x_dia").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_almacen_x_dia").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function eliminar_x_dia(idalmacen, cant, nombre) {

  crud_eliminar_papelera(
    "../ajax/almacen.php?op=desactivar_x_dia",
    "../ajax/almacen.php?op=eliminar_x_dia", 
    idalmacen, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del> ${nombre} <br> ${cant} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_x_dia.ajax.reload(null, false); todos_almacen(); },
    false, 
    false, 
    false,
    false
  );
}

// .....::::::::::::::::::::::::::::::::::::: T R A S N F E R E N C I A   U S O   D E   P R O Y E C T O  :::::::::::::::::::::::::::::::::::::::..

function show_hide_input(flag) {
  if (flag==1) {
    $('.span_s').show();
    $('.input_s').hide();

    $('.btn_guardar_s').hide();
    $('.btn_editar_s').show();
  } else if (flag==2) {
    $('.span_s').hide();
    $('.input_s').show();

    $('.btn_guardar_s').show();
    $('.btn_editar_s').hide();
  }
}

function limpiar_form_tup() {

  $('#producto_tup').val('').trigger("change");
  $('#fecha_tup').val('');
  $('#descripcion_tup').val('');
  $(".titulo-add-producto-tup").hide();
  $('#html_producto_tup').html(`<div class="col-12 delete_multiple_alerta_tup">
    <div class="alert alert-warning alert-dismissible mb-0">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
      NO TIENES NINGÚN PRODUCTO SELECCIONADO.
    </div>
  </div>`);
  lista_select2(`../ajax/almacen.php?op=select2Productos&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#producto_tup', null, '.cargando_producto_tup');

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

    var textproducto  = $('#producto_tup').select2('data')[0].text;
    var unidad_medida = $('#producto_tup').select2('data')[0].element.attributes.unidad_medida.value
    var saldo         = $('#producto_tup').select2('data')[0].element.attributes.saldo.value
    var tipo          = $('#producto_tup').select2('data')[0].element.attributes.tipo.value

    if ($(`#html_producto_tup div`).hasClass(`delete_multiple_${idproducto}`)) { // validamos si exte el producto agregado
      toastr_error('Existe!!', `<u>${textproducto}</u>, Este producto ya ha sido agregado`);
    } else {      
      $('#html_producto_tup').append(`
      <div class="col-12 col-sm-12 col-md-6 col-lg-5 delete_multiple_${idproducto}" >
        <input type="hidden" name="idproducto_tup[]" value="${idproducto}" />        
        <input type="hidden" name="tipo_prod_tup[]" value="${tipo}" /> 
        <input type="hidden" name="idproyecto_destino_tup[]" value="${localStorage.getItem("nube_idproyecto")}" />       
        <input type="hidden" name="idalmacen_general_tup[]" value="NULL" />       
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
          <input type="number" name="cantidad_tup_view_${idproducto}" class="form-control" id="cantidad_tup_view_${idproducto}" placeholder="cantidad" required min="0" max="${saldo}" step="0.01" onkeyup="replicar_data_input('#cantidad_tup_view_${idproducto}', '#cantidad_tup_${idproducto}')" />
          <input type="hidden" name="cantidad_tup[]" class="form-control" id="cantidad_tup_${idproducto}" placeholder="cantidad"  />
        </div>      
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-1 delete_multiple_${idproducto}">        
        <button type="button" class="btn bg-gradient-danger btn-sm"  onclick="remove_producto_tup(${idproducto});"><i class="far fa-trash-alt"></i></button>      
      </div> <div class="col-lg-12 borde-arriba-0000001a mt-0 mb-3 delete_multiple_${idproducto}"></div>`);

      $(`#cantidad_tup_view_${idproducto}`).rules("add", { required: true, min: 0, messages: { required: `Campo requerido.`, min: "Mínimo {0}", max: "Máximo {0}", step: "Maximo 2 decimales" } });  

      $.post(`../ajax/almacen.php?op=marcas_x_producto`, {'id_producto':idproducto, 'id_proyecto': localStorage.getItem("nube_idproyecto") }, function (e, status, jqXHR) {
        e = JSON.parse(e);   //console.log(e);
        if (e.status == true) {
          e.data.forEach((val, key) => {
            $(`#marca_tup_${idproducto}`).append(`<option value="${val.marca}">${val.marca}</option>`);
          });
          $(`.cargando-marca-tup-${idproducto}`).html('');
        } else {
          ver_errores(e);
        }
      }).fail( function(e) { ver_errores(e); });
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

//Función para guardar o editar
function guardar_y_editar_tup(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-almacen-tup")[0]);

  $.ajax({
    url: "../ajax/almacen.php?op=guardar_y_editar_tup",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {      
          $("#modal-transferencia-uso-proyecto").modal("hide");         
          Swal.fire("Correcto!", "Enviado a uso de Obra correctamente", "success");  
          tbla_resumen.ajax.reload(null, false);           
          lista_select2(`../ajax/almacen.php?op=select2Productos&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#producto_tup', null, '.cargando_producto_tup');      
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

// .....::::::::::::::::::::::::::::::::::::: T R A S N F E R E N C I A   E N T R E   P R O Y E C T O S  :::::::::::::::::::::::::::::::::::::::..

function limpiar_form_tep() {
  $('#proyecto_tep').val('').trigger("change");;
  $('#producto_tep').val('').trigger("change");;
  $('#fecha_tep').val('');
  $('#descripcion_tep').val('');
  $(".titulo-add-producto-tep").hide();
  $('#html_producto_tep').html(`<div class="col-12 delete_multiple_alerta_tep">
    <div class="alert alert-warning alert-dismissible mb-0">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
      NO TIENES NINGÚN PRODUCTO SELECCIONADO.
    </div>
  </div>`);
  lista_select2(`../ajax/almacen.php?op=select2Productos&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#producto_tep', null, '.cargando_producto_tep');
   
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

var arrary_idproyecto_destino = [];

function add_producto_tep(data) {
  var idproducto = $(data).select2('val');  // Capturamos los datos del producto 
  if (idproducto == null || idproducto == '' || idproducto === undefined) { } else {
    $('.delete_multiple_alerta_tep').remove(); // Eliminado el mensaje de vacio
    $(".titulo-add-producto-tep").show();     // mostramos los titulos del producto

    var textproducto  = $('#producto_tep').select2('data')[0].text;
    var unidad_medida = $('#producto_tep').select2('data')[0].element.attributes.unidad_medida.value
    var saldo         = $('#producto_tep').select2('data')[0].element.attributes.saldo.value
    var tipo          = $('#producto_tep').select2('data')[0].element.attributes.tipo.value
    var idproy_des    = $('#proyecto_tep').val() == '' || $('#proyecto_tep').val() == null ? '' : $('#proyecto_tep').val();

    if ($(`#html_producto_tep div`).hasClass(`delete_multiple_${idproducto}`)) { // validamos si exte el producto agregado
      toastr_error('Existe!!', `<u>${textproducto}</u>, Este producto ya ha sido agregado`);
    } else {      
      $('#html_producto_tep').append(`
      <div class="col-12 col-sm-12 col-md-6 col-lg-5 delete_multiple_${idproducto}" >
        <input type="hidden" name="idproducto_tep[]" value="${idproducto}" /> 
        <input type="hidden" name="tipo_prod_tep[]" value="${tipo}" /> 
        <input type="hidden" name="idproyecto_destino_tep[]" id="idproyecto_destino_tep_${idproducto}" value="${idproy_des}" />       
        <input type="hidden" name="idalmacen_general_tep[]" value="NULL" />       
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
          <span class="cargando-marca-tep-${idproducto}"><i class="fas fa-spinner fa-pulse fa-lg text-danger"></i></span>
          <select name="marca_tep[]" id="marca_tep_${idproducto}" class="form-control" placeholder="Marca"> </select>
        </div>      
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 delete_multiple_${idproducto}"">
        <div class="form-group">          
          <input type="number" name="cantidad_tep_view_${idproducto}" class="form-control" id="cantidad_tep_view_${idproducto}" placeholder="Cantidad" required min="0" max="${saldo}" step="0.01" onkeyup="replicar_data_input('#cantidad_tep_view_${idproducto}', '#cantidad_tep_${idproducto}')" />
          <input type="hidden" name="cantidad_tep[]" class="form-control" id="cantidad_tep_${idproducto}" placeholder="Cantidad"  />
        </div>      
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-1 text-center delete_multiple_${idproducto}">        
        <button type="button" class="btn bg-gradient-danger btn-sm"  onclick="remove_producto_tep(${idproducto});" data-toggle="tooltip" data-original-title="Eliminar" ><i class="far fa-trash-alt"></i></button>      
      </div> <div class="col-lg-12 borde-arriba-0000001a mt-0 mb-3 delete_multiple_${idproducto}"></div>`);

      $(`#cantidad_tep_view_${idproducto}`).rules("add", { required: true, min: 0, messages: { required: `Campo requerido.`, min: "Mínimo {0}", max: "Máximo {0}", step: "Maximo 2 decimales" } });  
      $('[data-toggle="tooltip"]').tooltip();
      arrary_idproyecto_destino.push(idproducto);

      $.post(`../ajax/almacen.php?op=marcas_x_producto`, {'id_producto':idproducto, 'id_proyecto': localStorage.getItem("nube_idproyecto") }, function (e, status, jqXHR) {
        e = JSON.parse(e);   //console.log(e);
        if (e.status == true) {
          e.data.forEach((val, key) => {
            $(`#marca_tep_${idproducto}`).append(`<option value="${val.marca}">${val.marca}</option>`);
          });
          $(`.cargando-marca-tep-${idproducto}`).html('');
        } else {
          ver_errores(e);
        }
      }).fail( function(e) { ver_errores(e); });
    }
  }
}

function replicar_proyecto_destino() {
  var idproy_des    = $('#proyecto_tep').val() == '' || $('#proyecto_tep').val() == null ? '' : $('#proyecto_tep').val();
  arrary_idproyecto_destino.forEach((val, key) => {
    $(`#idproyecto_destino_tep_${val}`).val(idproy_des);
  });
}

function remove_producto_tep(id) {
  $(`.delete_multiple_${id}`).remove(); 
  $(`.tooltip`).remove();
  if ($("#html_producto_tep").children().length == 0) {
    $(".titulo-add-producto-tep").hide();
    $('#html_producto_tep').html(`<div class="col-12 delete_multiple_alerta_tep">
      <div class="alert alert-warning alert-dismissible mb-0"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5> NO TIENES NINGÚN PRODUCTO SELECCIONADO. </div>
    </div>`);
  }   

}

//Función para guardar o editar
function guardar_y_editar_tep(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-almacen-tep")[0]);

  $.ajax({
    url: "../ajax/almacen.php?op=guardar_y_editar_tep",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          $('#modal-transferencia-entre-proyecto').modal('hide');
          Swal.fire("Correcto!", "Transferencia enviada correctamente", "success");     
          tbla_resumen.ajax.reload(null, false);
          lista_select2(`../ajax/almacen.php?op=select2Productos&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#producto_tep', null, '.cargando_producto_tep');

        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_almacen_tep").html('Guardar Cambios').removeClass('disabled send-data');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_tep").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_almacen_tep").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
      $("#barra_progress_tep").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_tep").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// .....::::::::::::::::::::::::::::::::::::: T R A S N F E R E N C I A   A L M A C E N   G E N E R A L   :::::::::::::::::::::::::::::::::::::::..

function limpiar_form_tag() {

  $('#producto_tag').val('').trigger("change");;
  $('#fecha_tag').val('');
  $('#descripcion_tag').val('');
  $(".titulo-add-producto-tag").hide();
  $('#html_producto_tag').html(`<div class="col-12 delete_multiple_alerta_tag">
    <div class="alert alert-warning alert-dismissible mb-0">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
      NO TIENES NINGÚN PRODUCTO SELECCIONADO.
    </div>
  </div>`);
  lista_select2(`../ajax/almacen.php?op=select2Productos&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#producto_tag', null, '.cargando_productos_tag');

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function add_producto_tag(data) {
  var idproducto = $(data).select2('val');

  if (idproducto == null || idproducto == '' || idproducto === undefined) { } else {

    $('.delete_multiple_alerta_tag').remove();  // Eliminado el mensaje de vacio
    $(".titulo-add-producto-tag").show();       // mostramos los titulos del producto

    var textproducto  = $('#producto_tag').select2('data')[0].text;
    var unidad_medida = $('#producto_tag').select2('data')[0].element.attributes.unidad_medida.value
    var saldo         = $('#producto_tag').select2('data')[0].element.attributes.saldo.value
    var tipo          = $('#producto_tag').select2('data')[0].element.attributes.tipo.value

    if ($(`#html_producto_tag div`).hasClass(`delete_multiple_${idproducto}`)) { // validamos si exte el producto agregado
      toastr_error('Existe!!', `<u>${textproducto}</u>, Este producto ya ha sido agregado`);
    } else {      
      $('#html_producto_tag').append(`
      <div class="col-12 col-sm-12 col-md-6 col-lg-5 delete_multiple_${idproducto}" >
        <input type="hidden" name="idproducto_tag[]" value="${idproducto}" />        
        <input type="hidden" name="tipo_prod_tag[]" value="${tipo}" /> 
        <input type="hidden" name="idproyecto_destino_tag[]"  value="NULL" />         
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
          <span class="cargando-almacen-tag-${idproducto}"><i class="fas fa-spinner fa-pulse fa-lg text-danger"></i></span>          
          <select name="idalmacen_general_tag[]" id="idalmacen_general_tag_${idproducto}" class="form-control" placeholder="Almacen general"> </select>
        </div>      
      </div>      
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 delete_multiple_${idproducto}"">
        <div class="form-group">         
          <input type="number" name="cantidad_tag_view_${idproducto}" class="form-control" id="cantidad_tag_view_${idproducto}" placeholder="Cantidad" required min="0" max="${saldo}" step="0.01" onkeyup="replicar_data_input('#cantidad_tag_view_${idproducto}', '#cantidad_tag_${idproducto}')" />
          <input type="hidden" name="cantidad_tag[]" class="form-control" id="cantidad_tag_${idproducto}" placeholder="Cantidad"  />
        </div>      
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-1 delete_multiple_${idproducto}">              
        <button type="button" class="btn bg-gradient-danger btn-sm" onclick="remove_producto_tag(${idproducto});" data-toggle="tooltip" data-original-title="Eliminar"><i class="far fa-trash-alt"></i></button> 
        <button type="button" class="btn bg-gradient-info btn-sm" onclick="mas_opciones_tag(${idproducto}, this);" estado_op_tag="false" data-toggle="tooltip" data-original-title="Ver mas campos"><i class="fa-solid fa-gear"></i></button>     
      </div> 
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 class_mas_opciones_tag_${idproducto} delete_multiple_${idproducto}" style="display: none !important;" >
        <div class="form-group">
          <span class="cargando-marca-tag-${idproducto}"><i class="fas fa-spinner fa-pulse fa-lg text-danger"></i></span>
          <select name="marca_tag[]" id="marca_tag_${idproducto}" class="form-control" placeholder="Marca"> </select>
        </div>      
      </div> 
      <div class="col-lg-12 borde-arriba-0000001a mt-0 mb-3 delete_multiple_${idproducto}"></div>`);

      $(`#cantidad_tag_view_${idproducto}`).rules("add", { required: true, min: 0, messages: { required: `Campo requerido.`, min: "Mínimo {0}", max: "Máximo {0}", step: "Maximo 2 decimales" } });  
      $('[data-toggle="tooltip"]').tooltip();

      $.post(`../ajax/almacen.php?op=otros_almacenes`, function (e, status, jqXHR) {
        e = JSON.parse(e);   //console.log(e);
        if (e.status == true) {
          e.data.forEach((val, key) => {
            $(`#idalmacen_general_tag_${idproducto}`).append(`<option value="${val.idalmacen_general}">${val.nombre_almacen}</option>`);
          });
          $(`.cargando-almacen-tag-${idproducto}`).html('');
        } else {
          ver_errores(e);
        }
      }).fail( function(e) { ver_errores(e); });

      $.post(`../ajax/almacen.php?op=marcas_x_producto`, {'id_producto':idproducto, 'id_proyecto': localStorage.getItem("nube_idproyecto") }, function (e, status, jqXHR) {
        e = JSON.parse(e);   //console.log(e);
        if (e.status == true) {
          e.data.forEach((val, key) => {
            $(`#marca_tag_${idproducto}`).append(`<option value="${val.marca}">${val.marca}</option>`);
          });
          $(`.cargando-marca-tag-${idproducto}`).html('');
        } else {
          ver_errores(e);
        }
      }).fail( function(e) { ver_errores(e); });
    }
  }
}

function remove_producto_tag(id) {
  $(`.delete_multiple_${id}`).remove(); 
  $(`.tooltip`).remove();
  if ($("#html_producto_tag").children().length == 0) {
    $(".titulo-add-producto-tag").hide();
    $('#html_producto_tag').html(`<div class="col-12 delete_multiple_alerta_tag">
      <div class="alert alert-warning alert-dismissible mb-0"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5> NO TIENES NINGÚN PRODUCTO SELECCIONADO. </div>
    </div>`);
  }   
}

function mas_opciones_tag(id, btn) {
  var view_estado_tags = $(btn).attr("estado_op_tag");
  if (view_estado_tags == 'false') {
    $(btn).attr("estado_op_tag", 'true');
    $(btn).html(`<i class="fa-solid fa-gears"></i>`);
    $(`.class_mas_opciones_tag_${id}`).show("slow");
  } else if (view_estado_tags == 'true') {
    $(btn).attr("estado_op_tag", 'false');
    $(btn).html(`<i class="fa-solid fa-gear"></i>`);
    $(`.class_mas_opciones_tag_${id}`).hide("slow");
  }
  $(`.tooltip`).remove();
}

//Función para guardar o editar
function guardar_y_editar_tag(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-almacen-tag")[0]);

  $.ajax({
    url: "../ajax/almacen.php?op=guardar_y_editar_tag",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {          
          $('#modal-transferencia-almacen-general').modal('hide'); 
          Swal.fire("Correcto!", "Enviado Almacen General correctamente", "success");
          tbla_resumen.ajax.reload(null, false);          
          lista_select2(`../ajax/almacen.php?op=select2Productos&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#producto_tag', null, '.cargando_productos_tag');
         
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_otro_almacen").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_tag").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_otro_almacen").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_tag").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_tag").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// .....::::::::::::::::::::::::::::::::::::: T R A S N F E R E N C I A  MASIVA  A L M A C E N   G E N E R A L   :::::::::::::::::::::::::::::::::::::::..
function guardar_y_editar_tm(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-almacen-tm")[0]); 

  var catidad_valid = 0;
  $('.cant_all_tm').each(function (key, val) { catidad_valid +=  $(this).val() == 0 || $(this).val() == '' || $(this).val() == null || $(this).val() === undefined ? 0 : parseFloat($(this).val());  });
  if ( catidad_valid == 0 ) { sw_cancelar('No hay productos', 'Asigne una cantidad para transferir.', 5000); return; }

  Swal.fire({
    title: "¿Está seguro de Transferir?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, enviar!",
    preConfirm: (input) => {
      return fetch("../ajax/almacen.php?op=guardar_y_editar_tm", {
        method: 'POST', // or 'PUT'
        body: formData, // data can be `string` or {object}!        
      }).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); });
    },
    showLoaderOnConfirm: true,
  }).then((result) => {
    if (result.isConfirmed) {
      if (result.value.status == true){      

        Swal.fire("Correcto!", "Transferencia guardada correctamente", "success");
        tbla_resumen.ajax.reload(null, false);          
        show_hide_tablas(1);        
          
      } else {
        ver_errores(result.value);
      }      
    }
  });  
}

var mostrar_masivo = false;
function tranferencia_masiva(unidad_medida, categoria, es_epp) {
  
  $('#html-transferencia-masiva').html(`<div class="col-12 text-center"><span class="spinner-border spinner-border-xl"></span> <br> <span class="text-olas-mar-letra">Cargando...</span></div>`);
  const text = document.querySelector('.text-olas-mar-letra');  text.innerHTML = text.textContent.split('').map((char, i) => `<span style="--i:${i}">${char}</span>` ).join('');        
  show_hide_tablas(3);
  var idproyecto =  localStorage.getItem("nube_idproyecto"); 
  mostrar_masivo = true;
  $.get(`../ajax/almacen.php?op=transferencia-masiva-almacen`, {id_proyecto: idproyecto, unidad_medida: unidad_medida, categoria:categoria, es_epp:es_epp},  function (e, textStatus, jqXHR) {
    $('#html-transferencia-masiva').html(e);
    $('[data-toggle="tooltip"]').tooltip();   
  });
   
}

function update_valueChec(id) {

  if ($(`#customCheckbox${id}`).is(':checked')) {

    $(`#cantidad__trns${id}`).rules("add", { required: true, min: 0.01, messages: { required: `Campo requerido.`, min: "Mínimo {0}", max: " Stock Máximo {0}" } });
    
    $(`#cantidad__trns${id}`).prop("disabled", false);
    $(`#cantidad__trns_env${id}`).prop("disabled", false);
    $(`#idproducto_tm${id}`).prop("disabled", false);
    $(`#tipo_prod_tm${id}`).prop("disabled", false);
    $(`#idproyecto_destino_tm${id}`).prop("disabled", false);
    $(`#marca_tm${id}`).prop("disabled", false);
    $(`#almacen_destino_tm${id}`).prop("disabled", false);
    
    $("#form-almacen-tm").valid();

  } else {    
    $(`#cantidad__trns${id}`).rules("remove", "required");

    $(`#cantidad__trns${id}`).prop("disabled", true);
    $(`#cantidad__trns_env${id}`).prop("disabled", true);
    $(`#idproducto_tm${id}`).prop("disabled", true);
    $(`#tipo_prod_tm${id}`).prop("disabled", true);
    $(`#idproyecto_destino_tm${id}`).prop("disabled", true);
    $(`#marca_tm${id}`).prop("disabled", true);
    $(`#almacen_destino_tm${id}`).prop("disabled", true);

    $(`#cantidad__trns_env${id}`).val(0);
    $(`#cantidad__trns${id}`).val(0);    

    $("#form-almacen-tm").valid();
  }
}

function Activar_masivo() {
  $('.card-almacen-2 div:nth-of-type(1) .card-body').after(`<div class="overlay dark" style="align-items: flex-start !important;" ><div class="text-center mt-5" ><span class="spinner-border spinner-border-xl"></span><br><span class="text-olas-mar-letra text-white text-bold">Procesando...</span></div></div>`);
  
  setTimeout(function() {  console.log($(`#marcar_todo`).is(':checked'));
  

    if ($(`#marcar_todo`).is(':checked')) {

      $('.checked_all').each(function (key, val) { this.checked = true; });

      $('.cant_all_tm').each(function (key, val) {  
        $(this).rules("add", { required: true, min: 0.01, messages: { required: `Campo requerido.`, min: "Mínimo {0}", max: " Stock Máximo {0}" } });      
        $(`#cantidad__trns${key+1}`).prop("disabled", false);
        $(`#cantidad__trns_env${key+1}`).prop("disabled", false);
        $(`#idproducto_tm${key+1}`).prop("disabled", false);
        $(`#tipo_prod_tm${key+1}`).prop("disabled", false);
        $(`#idproyecto_destino_tm${key+1}`).prop("disabled", false);
        $(`#marca_tm${key+1}`).prop("disabled", false);
        $(`#almacen_destino_tm${key+1}`).prop("disabled", false);

        $(`#cantidad__trns_env${key+1}`).val(0);
        $(`#cantidad__trns${key+1}`).val(0);
      });
      
      $('.estadochecked_all').val(1);   
      $("#form-almacen-tm").valid();

    } else {

      $('.checked_all').each(function () { this.checked = false; });    

      $('.cant_all_tm').each(function (key, val) {        
        $(this).rules("remove", "required");
        $(`#cantidad__trns${key+1}`).prop("disabled", true);
        $(`#cantidad__trns_env${key+1}`).prop("disabled", true);
        $(`#idproducto_tm${key+1}`).prop("disabled", true);
        $(`#tipo_prod_tm${key+1}`).prop("disabled", true);
        $(`#idproyecto_destino_tm${key+1}`).prop("disabled", true);
        $(`#marca_tm${key+1}`).prop("disabled", true);
        $(`#almacen_destino_tm${key+1}`).prop("disabled", true);

        $(`#cantidad__trns_env${key+1}`).val(0);
        $(`#cantidad__trns${key+1}`).val(0);
      });
      $(`#enviar_todo_tm`).val("");
      $('.estadochecked_all').val(0);
      $("#form-almacen-tm").valid();
    }

    $('.card-almacen-2 div:nth-of-type(1) .card-body').next('.overlay').remove();

  }, 1000);
}

function enviar_todo_stok(input) { console.log($(input).val());

  $('.card-almacen-2 div:nth-of-type(1) .card-body').after(`<div class="overlay dark" style="align-items: flex-start !important;" ><div class="text-center mt-5" ><span class="spinner-border spinner-border-xl"></span><br><span class="text-olas-mar-letra text-white text-bold">Procesando...</span></div></div>`);
  
  setTimeout(function() {  

    if ($(input).val() == '0') {
      $('.cant_all_tm').each(function (key, val) {       
        $(`#cantidad__trns_env${key+1}`).val(0);
        $(`#cantidad__trns${key+1}`).val(0);
      });
      $("#form-almacen-tm").valid();
    }else if ($(input).val() == '1') {
      $('#marcar_todo').prop('checked', true);
     
      $('.cant_all_tm').each(function (key, val) {  
        $(this).val( $(`#total_stok_tm_${key+1}`).text() ); 
        $(`#cantidad__trns_env${key+1}`).val($(`#total_stok_tm_${key+1}`).text()); 
      });

      $('.checked_all').each(function (key, val) { this.checked = true; });
      $('.cant_all_tm').each(function (key, val) {  
        $(this).rules("add", { required: true, min: 0.01, messages: { required: `Campo requerido.`, min: "Mínimo {0}", max: " Stock Máximo {0}" } });      
        $(`#cantidad__trns${key+1}`).prop("disabled", false);
        $(`#cantidad__trns_env${key+1}`).prop("disabled", false);
        $(`#idproducto_tm${key+1}`).prop("disabled", false);
        $(`#tipo_prod_tm${key+1}`).prop("disabled", false);
        $(`#idproyecto_destino_tm${key+1}`).prop("disabled", false);
        $(`#marca_tm${key+1}`).prop("disabled", false);
        $(`#almacen_destino_tm${key+1}`).prop("disabled", false);
        
      });
      $('.estadochecked_all').val(1);

      $("#form-almacen-tm").valid();
    }

    $('.card-almacen-2 div:nth-of-type(1) .card-body').next('.overlay').remove();

  }, 1000);
}

function cambiar_de_almacen(input) {
  $('.card-almacen-2 div:nth-of-type(1) .card-body').after(`<div class="overlay dark" style="align-items: flex-start !important;" ><div class="text-center mt-5" ><span class="spinner-border spinner-border-xl"></span><br><span class="text-olas-mar-letra text-white text-bold">Procesando...</span></div></div>`);
  
  setTimeout(function() {
    $('.almacen_destino_all_tm').each(function (key, val) {  $(this).val( $(input).val() ); });
    $("#form-almacen-tm").valid();
    $('.card-almacen-2 div:nth-of-type(1) .card-body').next('.overlay').remove();

  }, 500);
}


// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION AGREGAR GRUPOS ::::::::::::::::::::::::::::::::::::::::::::::::::::

function limpiar_form_grupos() {
  $("#idclasificacion_grupo_g").val('').trigger('change');
}

function agregar_grupos(id_producto, id_grupo) {
  $("#idproducto_g").val(id_producto);
  $("#idproyecto_grp").val(localStorage.getItem('nube_idproyecto'));
  $("#idclasificacion_grupo_g").val(id_grupo).trigger('change');
  $('#modal-agregar-grupos').modal('show');
}

//Función para guardar o editar
function guardar_grupos(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-grupos")[0]);

  $.ajax({
    url: "../ajax/resumen_insumos.php?op=actualizar_grupo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Grupo guardado correctamente", "success");   
          if (dt_tabla_principal) { dt_tabla_principal.ajax.reload(null, false); }  
          $("#modal-agregar-grupos").modal("hide");
        } else {
         ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }      

      $("#guardar_registro_grupos").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_grupos").css({"width": percentComplete+'%'});
          $("#barra_progress_grupos").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_grupos").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_grupos").css({ width: "0%",  });
      $("#barra_progress_grupos").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_grupos").css({ width: "0%", });
      $("#barra_progress_grupos").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..


init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {  
  $("#producto_xp").on('change', function() { $(this).trigger('blur'); });
  $("#proyecto_tep").on('change', function() { $(this).trigger('blur'); });

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
      guardar_y_editar_tup(e);
    },
  });

  $("#form-almacen-tep").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      fecha_tep:  { required: true,  },      
    },
    messages: {
      fecha_tep:  { required: "Campo requerido.", },    
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
      guardar_y_editar_tep(e);
    },
  });

  $("#form-almacen-tag").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      fecha_tag:  { required: true,  },      
    },
    messages: {
      fecha_tag:  { required: "Campo requerido.", },    
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
      guardar_y_editar_tag(e);
    },
  });

  $("#form-almacen-x-dia").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      producto_xp:      { required: true,  },      
      fecha_ingreso_xp: { required: true,  },      
      marca_xp:         { required: true,  },      
      cantidad_xp:      { required: true,  },      
    },
    messages: {
      producto_xp:      { required: "Campo requerido.", },    
      fecha_ingreso_xp: { required: "Campo requerido.", },    
      marca_xp:         { required: "Campo requerido.", },    
      cantidad_xp:      { required: "Campo requerido.", },     
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
      guardar_y_editar_almacen_x_dia(e);
    },
  });

  $("#form-almacen-saldo-anterior").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      saldo_anterior: { min: 0,  },        
    },
    messages: {
      saldo_anterior: { min: "Minimo 0", },      
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
      guardar_y_editar_saldo_anterior(e);
    },
  });

  $("#form-almacen-tm").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproyecto_origen_tm: { required: true,  },      
      fecha_tm:             { required: true,  },      
      descripcion_tm:       { minlength:4, maxlength:500  },      
    },
    messages: {
      idproyecto_origen_tm:  { required: "Campo requerido.", },    
      fecha_tm:             { required: "Campo requerido.",  },      
      descripcion_tm:       { minlength: "Mínimo {0}", maxlength: "Maximo {0}" },      
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
      guardar_y_editar_tm(e);
    },
  });

  no_select_tomorrow("#fecha_ingreso");
  no_select_tomorrow("#fecha_ingreso_xp");
  no_select_tomorrow("#fecha_tep");
  no_select_tomorrow("#fecha_tup");
  no_select_tomorrow("#fecha_tag");
  $("#producto_xp").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#proyecto_tep").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: O T R A S   F U N C I O N E S  :::::::::::::::::::::::::::::::::::::::..

function filtros_tm() {  

  var unidad_medida = $("#filtro_tm_unidad_medida").val();
  var categoria     = $("#filtro_tm_categoria").val();  
  var es_epp        = $("#filtro_tm_es_epp").select2('val'); 
  
  // var nombre_proveedor = $('#filtro_proveedor').find(':selected').text();
  // var nombre_comprobante = ' ─ ' + $('#filtro_tipo_comprobante').find(':selected').text();
  if (mostrar_masivo == true) {
     tranferencia_masiva(unidad_medida, categoria, es_epp);
  }
 
}

function scroll_tabla_asistencia() {
  var height_tabla = $('#div_tabla_almacen').height(); //console.log('Alto pantalla: '+height_tabla);
  var width_tabla = $('#div_tabla_almacen').width(); console.log('Ancho pantalla: '+width_tabla);
  if (width_tabla <= 600) {
    $('#div_tabla_almacen').css({'height':`${redondearExp((width_tabla*2),0)}px`});
  } else {
    var alto_real = (width_tabla/2) - 100; //console.log('Result pantalla: '+alto_real);
    $('#div_tabla_almacen').css({'height':`${redondearExp(alto_real,0)}px`});
  }
}

function pintar_boton_selecionado(i) {
  localStorage.setItem('i', i); //enviamos el ID-BOTON al localStorage
  // validamos el id para pintar el boton
  if (localStorage.getItem('boton_id')) {
    let id = localStorage.getItem('boton_id'); //console.log('id-nube-boton '+id);     
    $("#boton-" + id).removeClass('click-boton');
    localStorage.setItem('boton_id', i);
    $("#boton-"+i).addClass('click-boton');
  } else {
    localStorage.setItem('boton_id', i);
    $("#boton-"+i).addClass('click-boton');
  }
}

function obtener_dia_ingreso(datos) {
  $('#dia_ingreso').val( extraer_dia_semana_completo($(datos).val()) ); 
  $('#dia_ingreso_ag').val( extraer_dia_semana_completo($(datos).val()) ); 
}

function reload_producto_todos(){ $('.comprado_todos').html(`(todos)`); lista_select2(`../ajax/almacen.php?op=select2ProductosTodos&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#producto', null, '.cargando_productos'); }
function reload_producto_tup(){ lista_select2(`../ajax/almacen.php?op=select2Productos&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#producto_tup', null, '.cargando_producto_tup'); }

function reload_proyecto_tep(){ lista_select2(`../ajax/almacen.php?op=select2Proyecto`, '#proyecto_tep', null, '.cargando_proyecto_tep'); }
function reload_producto_tep(){ lista_select2(`../ajax/almacen.php?op=select2ProductosMasEPP&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#producto_tep', null, '.cargando_producto_tep'); }

function reload_producto_tag(){ lista_select2(`../ajax/almacen.php?op=select2Productos&idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#producto_tag', null, '.cargando_productos_tag'); }
