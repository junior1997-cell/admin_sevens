
var array_doc = [];

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");
  $("#mTecnico").addClass("active");
  $("#lAlmacen").addClass("active bg-primary");

  var idproyecto =  localStorage.getItem("nube_idproyecto");
  listar_botones_q_s(idproyecto);

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2(`../ajax/almacen.php?op=select2Productos&idproyecto=${idproyecto}`, '#producto', null, '.cargando_productos');

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_almacen").on("click", function (e) { $("#submit-form-almacen").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#producto").select2({theme: "bootstrap4", placeholder: "Selecione producto", allowClear: true, });

  // Formato para telefono
  $("[data-mask]").inputmask();

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
            htm_btn = htm_btn.concat(` <button type="button" id="boton-${(key+1)}" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="por_fecha('${val.ids_q_asistencia}', '${format_d_m_a(val.fecha_q_s_inicio)}', '${format_d_m_a(val.fecha_q_s_fin)}', '${(key+1)}', ${q_s_dias});"><i class="far fa-calendar-alt"></i> ${q_s_btn} ${val.numero_q_s}<br>${format_d_m_a(val.fecha_q_s_inicio)} // ${format_d_m_a(val.fecha_q_s_fin)}</button>`);             
          });   

          $('#lista_quincenas').html(`<button type="button" id="boton-0" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="todos_almacen();"><i class="far fa-calendar-alt"></i><br> Todos</button> 
          ${htm_btn}`);  
          todos_almacen();   
        }
      }        
    } else {
      ver_errores(e);
    }
    
    //console.log(fecha);
  }).fail( function(e) { ver_errores(e); } );
}

function todos_almacen() {
  $('.data_tbody_almacen').html('');
  pintar_boton_selecionado(0);
  var idproyecto =  localStorage.getItem("nube_idproyecto");
  var fip =  localStorage.getItem("nube_fecha_inicial_actividad");
  var ffp =  localStorage.getItem("nube_fecha_final_actividad");
  var fpo =  localStorage.getItem("nube_fecha_pago_obrero");

  $('#div_tabla_almacen').hide();
  $('#cargando-table-almacen').show().html(`<div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div>`);
  
  $.post("../ajax/almacen.php?op=tabla_almacen", { 'id_proyecto': idproyecto, 'fip': fip, 'ffp':ffp, 'fpo': fpo }, function (e, status) {

    e = JSON.parse(e); console.log(e);

    if (e.status == true) {
      var codigoHTMLbody="", codigoHTMLbodyProducto ='', codigoHTMLbodyDias="", html_dias = ''; 
      var codigoHTMLhead1="", codigoHTMLhead2="", codigoHTMLhead3="", codigoHTMLhead4="" ;
      var fpo =  localStorage.getItem("nube_fecha_pago_obrero"), nombre_sq = '', cant_sq = ''; 

      e.data.fechas.forEach((val1, key1) => {
        codigoHTMLhead1 = codigoHTMLhead1.concat(`<th colspan="${val1.cantidad_dias}">${extraer_nombre_mes(val1.mes)}</th>`);
        val1.dia.forEach((val2, key2) => {
          codigoHTMLhead2 = codigoHTMLhead2.concat(`<th class="style-head">${extraer_dia_mes(val2)}</th>`);
          codigoHTMLhead4 = codigoHTMLhead4.concat(`<th class="style-head">${extraer_dia_semana(val2)}</th>`);          
        });        
      });
      e.data.data_sq.forEach((val1, key1) => {
        codigoHTMLhead3 = codigoHTMLhead3.concat(`<th colspan="${val1.colspan}">${val1.nombre_sq} ${val1.num_sq}</th>`);
      });      

      $('.thead-f1').html(`<th rowspan="4">#</th> <th rowspan="4">Code</th> <th rowspan="4">Producto</th>
      <th rowspan="4">UND</th> <th rowspan="4">SALDO <br> ANTERIOR</th> ${codigoHTMLhead1}
      <th rowspan="4">INGRESO /<br> SALIDA</th> <th rowspan="4">SALDO</th>`);

      $('.thead-f2').html(`${codigoHTMLhead2}`);
      $('.thead-f3').html(`${codigoHTMLhead3}`);
      $('.thead-f4').html(`${codigoHTMLhead4}`);

      e.data.producto.forEach((val1, key1) => {
        
        var html_dias = '', html_dias_sum = '';
        val1.almacen.forEach((val2, key2) => {
          var numeros = '', acumulado = 0;
          if (val2.data.length === 0) { numeros='0'; } else { val2.data.forEach((val3, key3) => { key3 == 0 ? numeros = parseFloat(val3.cantidad) : numeros = numeros + ', ' + parseFloat(val3.cantidad); acumulado += parseFloat(val3.cantidad); }); } 
          html_dias = html_dias.concat(`<td>${numeros}</td>`);
          html_dias_sum = html_dias_sum.concat(`<td>${acumulado} <span class="badge badge-info float-right cursor-pointer shadow-1px06rem09rem-rgb-52-174-193-77" data-toggle="tooltip" data-original-title="Por descuento" onclick="modal_ver_almacen('${val2.fecha}', '${val1.idproducto}');"><i class="far fa-eye"></i></span></td>`);
        });

        codigoHTMLbodyProducto = `
        <tr class="text-nowrap">
          <td rowspan="2">${(key1 +1)}</td>
          <td rowspan="2">${val1.idproducto}</td>
          <td class="text_producto text-nowrap" rowspan="2">${val1.nombre_producto} <br> <small><b>Clasf:</b> ${val1.clasificacion} </small></td>
          <td rowspan="2">${val1.abreviacion}</td>
          <td rowspan="2">${formato_miles(val1.cantidad)}</td>
          ${html_dias}
          <td rowspan="2">100</td>
          <td rowspan="2">200</td>
        </tr>`;

        $('.data_tbody_almacen').append(`${codigoHTMLbodyProducto} <tr>${html_dias_sum} </tr>`); // Agregar el contenido
        html_dias ='';
      });
       

      $('#div_tabla_almacen').show();
      $('#cargando-table-almacen').hide();
      scroll_tabla_asistencia();
    } else {
      ver_errores(e);
    }
  }).fail(function (e) { ver_errores(e); });
  
}

function por_fecha(ids_q_asistencia, fecha_q_s_inicio, fecha_q_s_fin, i, q_s_dias ) {
  $('.data_tbody_almacen').html('');
  pintar_boton_selecionado(i);
  var idproyecto =  localStorage.getItem("nube_idproyecto");
  var fip =  fecha_q_s_inicio
  var ffp =  fecha_q_s_fin
  var fpo =  localStorage.getItem("nube_fecha_pago_obrero");

  $('#div_tabla_almacen').hide();

  $('#cargando-table-almacen').show().html(`<div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div>`);
  
  $.post("../ajax/almacen.php?op=tabla_almacen", { 'id_proyecto': idproyecto, 'fip': fip, 'ffp':ffp, 'fpo': fpo }, function (e, status) {

    e = JSON.parse(e); console.log(e);

    if (e.status == true) {
      var codigoHTMLbody="", codigoHTMLbodyProducto ='', codigoHTMLbodyDias=""; 
      var codigoHTMLhead1="", codigoHTMLhead2="", codigoHTMLhead3="", codigoHTMLhead4="" ;
      

      e.data.fechas.forEach((val1, key1) => {
        codigoHTMLhead1 = codigoHTMLhead1.concat(`<th colspan="${val1.cantidad_dias}">${extraer_nombre_mes(val1.mes)}</th>`);
        val1.dia.forEach((val2, key2) => {
          codigoHTMLhead2 = codigoHTMLhead2.concat(`<th class="style-head">${extraer_dia_mes(val2)}</th>`);
          codigoHTMLhead4 = codigoHTMLhead4.concat(`<th class="style-head">${extraer_dia_semana(val2)}</th>`);          
        });        
      });
      e.data.data_sq.forEach((val1, key1) => {
        codigoHTMLhead3 = codigoHTMLhead3.concat(`<th colspan="${e.data.cant_dias}">${val1.nombre_sq} ${i}</th>`);
      });      

      $('.thead-f1').html(`<th rowspan="4">#</th> <th rowspan="4">Code</th> <th rowspan="4">Producto</th>
      <th rowspan="4">UND</th> <th rowspan="4">SALDO <br> ANTERIOR</th> ${codigoHTMLhead1}
      <th rowspan="4">INGRESO /<br> SALIDA</th> <th rowspan="4">SALDO</th>`);

      $('.thead-f2').html(`${codigoHTMLhead2}`);
      $('.thead-f3').html(`${codigoHTMLhead3}`);
      $('.thead-f4').html(`${codigoHTMLhead4}`);

      e.data.producto.forEach((val1, key1) => {
        var html_dias = '', html_dias_sum = '';
        val1.almacen.forEach((val2, key2) => {
          var numeros = '', acumulado = 0;
          if (val2.data.length === 0) { numeros='0'; } else { val2.data.forEach((val3, key3) => { key3 == 0 ? numeros = parseFloat(val3.cantidad) : numeros = numeros + ', ' + parseFloat(val3.cantidad); acumulado += parseFloat(val3.cantidad); }); } 
          html_dias = html_dias.concat(`<td>${numeros}</td>`);
          html_dias_sum = html_dias_sum.concat(`<td>${acumulado} <span class="badge badge-info float-right cursor-pointer shadow-1px06rem09rem-rgb-52-174-193-77" data-toggle="tooltip" data-original-title="Por descuento" onclick="modal_ver_almacen('${val2.fecha}', '${val1.idproducto}');"><i class="far fa-eye"></i></span></td>`);
        });

        codigoHTMLbodyProducto = `
        <tr>
          <td rowspan="2">${(key1 +1)}</td>
          <td rowspan="2">${val1.idproducto}</td>
          <td class="text_producto text-nowrap" rowspan="2">${val1.nombre_producto} <br> <small><b>Clasf:</b> ${val1.clasificacion} </small></td>
          <td rowspan="2">${val1.abreviacion}</td>
          <td rowspan="2">${formato_miles(val1.cantidad)}</td>
          ${html_dias}
          <td rowspan="2">100</td>
          <td rowspan="2">200</td>
        </tr>`;

        $('.data_tbody_almacen').append(`${codigoHTMLbodyProducto} <tr>${html_dias_sum} </tr>`); // Agregar el contenido
        
      });
       

      $('#div_tabla_almacen').show();
      $('#cargando-table-almacen').hide();
      scroll_tabla_asistencia();
    } else {
      ver_errores(e);
    }
  }).fail(function (e) { ver_errores(e); });
}

function modal_ver_almacen(fecha, id_producto) {

  $('#modal-ver-almacen').modal('show');

  tabla=$('#tabla-ver-almacen').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,9,10,11,3,4,12,13,14,15,16,5,], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,9,10,11,3,4,12,13,14,15,16,5,], } },  
    ],
    ajax:{
      url: `../ajax/almacen.php?op=tbla-ver-almacen&fecha=${fecha}&id_producto=${id_producto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);  ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass('text-center'); } 
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass('text-nowrap'); }
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
      // { targets: [9, 10, 11, 12, 13, 14, 15, 16,17], visible: false, searchable: false, }, 
    ],
  }).DataTable();
}

// .....::::::::::::::::::::::::::::::::::::: A G R E G A R   A L M A C E N  :::::::::::::::::::::::::::::::::::::::..

function limpiar_form_almacen() {

  $('#producto').val('').trigger("change");;
  $('#fecha_ingreso').val('');
  $('#html_producto').html('');

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función para guardar o editar
function guardar_y_editar_almacen(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-almacen")[0]);

  $.ajax({
    url: "../ajax/almacen.php?op=guardar_y_editar_almacen",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) { 
          todos_almacen();
          limpiar_form_almacen();
          Swal.fire("Correcto!", "Almacen guardado correctamente", "success");          
          $("#modal-agregar-almacen").modal("hide");          
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      $("#guardar_registro_almacen").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_almacen").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_almacen").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_almacen").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_almacen").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function add_producto(data) {
  var idproducto = $(data).select2('val');
  

  if (idproducto == null || idproducto == '' || idproducto === undefined) { } else {
    var textproducto = $('#producto').select2('data')[0].text;
    $('#html_producto').append(`<div class="col-12 col-sm-12 col-md-6 col-lg-7 delete_multiple_${idproducto}" >
      <input type="hidden" name="idproducto[]" value="${idproducto}" />
      
      <div class="form-group">
        <label for="fecha_ingreso">Nombre Producto</label>
        <span class="form-control-mejorado"> ${textproducto} </span>                                  
      </div>
    </div> 
    <div class="col-12 col-sm-12 col-md-6 col-lg-4 delete_multiple_${idproducto}"">
      <div class="form-group">
        <label for="fecha_ingreso">Cantidad</label>
        <input type="number" name="cantidad[]" class="form-control" id="cantidad_${idproducto}" placeholder="cantidad" required min="0" />
      </div>      
    </div> 
    <div class="col-12 col-sm-12 col-md-6 col-lg-1 delete_multiple_${idproducto}">      
      <label class="text-white">.</label> <br>
      <button type="button" class="btn bg-gradient-danger btn-sm"  onclick="remove_producto(${idproducto});"><i class="far fa-trash-alt"></i></button>      
    </div> `);
    $(`#cantidad_${idproducto}`).rules("add", { required: true, min: 0, messages: { required: `Campo requerido.`, min: "Mínimo 0", } });
  }
}

function remove_producto(id) { $(`.delete_multiple_${id}`).remove(); }


init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {  
  // $("#banco").on('change', function() { $(this).trigger('blur'); });

  $("#form-almacen").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      fecha_ingreso:  { required: true,  },      
    },
    messages: {
      fecha_ingreso:  { required: "Campo requerido.", },    
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
      guardar_y_editar_almacen(e);
    },
  });

  no_select_tomorrow("#fecha_ingreso");
  // $("#banco").rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: O T R A S   F U N C I O N E S  :::::::::::::::::::::::::::::::::::::::..

function scroll_tabla_asistencia() {
  var height_tabla = $('#div_tabla_almacen').height(); console.log('Alto pantalla: '+height_tabla);
  var width_tabla = $('#div_tabla_almacen').width(); console.log('Ancho pantalla: '+width_tabla);
  if (height_tabla <= 600) {
    $('#div_tabla_almacen').css({'height':`${redondearExp((height_tabla+50),0)}px`});
  } else {
    var alto_real = (width_tabla/2) - 100; console.log('Result pantalla: '+alto_real);
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
}