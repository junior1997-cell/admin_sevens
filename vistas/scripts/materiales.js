var tabla;
idproducto_r='', nombre_producto_r='', precio_promedio_r='';
//Función que se ejecuta al inicio
function init() {
  
  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");

  $("#mRecurso").addClass("active");

  $("#lAllMateriales").addClass("active");

  tbla_principal();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/ajax_general.php?op=select2Marcas", '#marcas', null);
  lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-materiales").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#marcas").select2({placeholder: "Seleccionar marcas", });
  $("#unidad_medida").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });

  //EDITAR COMPRAS

  tbla_materiales();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_prov', null);
  lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2Categoria_all", '#categoria_insumos_af_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2Marcas", '#marcas_p', null);
  lista_select2("../ajax/ajax_general.php?op=select2ClasificacionGrupo", '#idclasificacion_grupo_g', null);
  lista_select2(`../ajax/compra_insumos.php?op=select2_serie_comprobante&idproyecto=${localStorage.getItem('nube_idproyecto')}`, '#slt2_serie_comprobante', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_compras").on("click", function (e) {  $("#submit-form-compras").submit(); });
  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });
  $("#guardar_registro_material").on("click", function (e) {  $("#submit-form-materiales").submit(); });
  $("#guardar_registro_grupos").on("click", function (e) {  $("#submit-form-grupos").submit(); });

  // ═══════════════════ SELECT2 - COMPRAS ═══════════════════
  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione trabajador", allowClear: true, });
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione Comprobante", allowClear: true, });
  $("#glosa").select2({ templateResult: templateGlosa, theme: "bootstrap4", placeholder: "Selecione Glosa", allowClear: true, });
  $("#slt2_serie_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecionar", allowClear: true, });

  // ═══════════════════ SELECT2 - PROVEEDOR ═══════════════════
  //Initialize Select2 BANCO PROVEEDOR
  $("#banco_prov").select2({ templateResult: templateBanco, theme: "bootstrap4", placeholder: "Selecione banco", allowClear: true, });

  // ═══════════════════ SELECT2 - MATERIAL ═══════════════════
  $("#categoria_insumos_af_p").select2({ theme: "bootstrap4", placeholder: "Seleccinar Clasificación", allowClear: true, });
  $("#marcas_p").select2({placeholder: "Seleccinar marcas"});
  $("#unidad_medida_p").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });  

  $("#idclasificacion_grupo_g").select2({ theme: "bootstrap4", placeholder: "Seleccinar una Grupo", allowClear: true, });

  //FIN COMPRAS

  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $('#precio_unitario').number( true, 2 );
  $('#precio_sin_igv').number( true, 2 );
  $('#precio_igv').number( true, 2 );
  $('#precio_con_igv').number( true, 2 );

  $('.jq_image_zoom').zoom({ on:'grab' });
  // Formato para telefono
  $("[data-mask]").inputmask();
}

// function templateColor (state) {
//   if (!state.id) { return state.text; }
//   var color_bg = state.title != '' ? `${state.title}`: '#ffffff00';   
//   var $state = $(`<span ><b style="background-color: ${color_bg}; color: ${color_bg};" class="mr-2"><i class="fas fa-square"></i><i class="fas fa-square"></i></b>${state.text}</span>`);
//   return $state;
// }
function templateBanco (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/banco/logo/${state.title}`: '../dist/docs/banco/logo/logo-sin-banco.svg'; 
  var onerror = `onerror="this.src='../dist/docs/banco/logo/logo-sin-banco.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

function templateGlosa (state) {
  if (!state.id) { return state.text; }  
  var $state = $(`<span ><b class="mr-2"><i class="${state.title}"></i></b>${state.text}</span>`);
  return $state;
}

// abrimos el navegador de archivos
// iamgend e perfil
$("#imagen1_i").click(function () { $("#imagen1").trigger("click"); });
$("#imagen1").change(function (e) { addImage(e, $("#imagen1").attr("id"), "../dist/img/default/img_defecto_materiales.png"); });

//ficha tecnica
$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e,$("#doc2").attr("id")) });

function imagen1_eliminar() {
  $("#imagen1").val("");

  $("#imagen1_i").attr("src", "../dist/img/default/img_defecto_materiales.png");

  $("#imagen1_nombre").html("");
}

// Eliminamos el doc 2
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

//Función limpiar
function limpiar_form_material() {

  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');

  // no usados
  $("#precio_unitario").val("0");
  $("#precio_sin_igv").val("0");
  $("#precio_igv").val("0");
  $("#precio_total").val("0");
  $("#color").val(1);
  $("#modelo").val("");
  $("#serie").val("");
  $("#estado_igv").val("1");
  $("#categoria_insumos_af").val("1");

  //input usados
  $("#idproducto").val("");  
  $("#nombre").val("");    
  $("#unidad_medida").val("").trigger("change");  
  $("#marcas").val("").trigger("change");  
  $("#descripcion").val("");

  $("#imagen1_i").attr("src", "../dist/img/default/img_defecto_materiales.png");
  $("#imagen1").val("");
  $("#imagen1_actual").val("");
  $("#imagen1_nombre").html("");

  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}
function table_show_hide(estado) { 
  if (estado==1) { //tabla principal
    $(".btn-agregar-material").show();
    $(".btn-regresar").hide();

    $("#div_materiales").show();
    $("#div_facturas").hide();
    $("#tabla-editar-factura").hide();
    $(".nombre-insumo").html(`Insumos`);

  }else if (estado==2) { // editar factura

    $(".btn-agregar-material").hide();
    $(".btn-regresar").hide();

    $("#div_materiales").hide();
    $("#div_facturas").hide();
    $("#tabla-editar-factura").show();
    $(".nombre-insumo").html(`Editar Compra`);

  } else if (estado==3) {  //listar facturas x producto
    $(".btn-agregar-material").hide();
    $(".btn-regresar").show();

    $("#div_materiales").hide();
    $("#div_facturas").show();
    $("#tabla-editar-factura").hide();    
  }
}

//Función Listar
function tbla_principal() {
  tabla = $("#tabla-materiales").dataTable({
    responsive: false,
    lengthMenu: [[ -1, 10, 25, 50, 100, 200, 500], ["Todos", 10, 25, 50, 100, 200, 500 ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-sm-12 col-md-6 col-lg-7 col-xl-8 pt-2'f><'col-sm-12 col-md-6 col-lg-7 col-xl-4 pt-2 d-flex justify-content-end align-items-center'<'length'l><'buttons'B>>>r t <'row'<'col-md-6'i><'col-md-6'p>>",
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate"></i> ', className: "buttons-reload buttons-html5 px-2 buttons-copy", action: function ( e, dt, node, config ) { if (tabla) { tabla.ajax.reload(null, false); } } },
      { text: '<i class="fa-regular fa-copy fa-lg"></i>', extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,9,4,11,6,10], } }, 
      { text: '<i class="fa-regular fa-file-excel fa-lg"></i>', extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,9,4,11,6,10], } }, 
      { text: '<i class="fa-regular fa-file-pdf fa-lg"></i>', extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,9,4,11,6,10], } },
    ],
    ajax: {
      url: "../ajax/materiales.php?op=tbla_principal",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {    
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: opciones
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center text-nowrap"); }
      // columna: code
      if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); }
      // columna: code
      if (data[3] != '') { $("td", row).eq(3).addClass("text-nowrap"); }
      // columna: code
      if (data[4] != '') { $("td", row).eq(4).addClass("text-nowrap"); }
      // columna: compra
      if (data[1] != '') { $("td", row).eq(7).addClass("text-center text-nowrap"); }

    },
    language: {
      lengthMenu: "_MENU_",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...',
      search: "",
    },
    initComplete: function () {

      var api = this.api();      
      $(api.table().container()).find('.dataTables_filter input').addClass('border border-primary bg-light m-0');// Agregar clase bg-light al input de búsqueda
    },
    bDestroy: true,
    iDisplayLength: 25, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [6], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, }, 
      { targets: [9,10,11], visible: false, searchable: false, },  
      // { targets: [7,8,9,10], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();
}

//ver ficha tecnica
function modal_ficha_tec(ficha_tecnica) {

  // ------------------------
  //$('.tile-modal-comprobante').html(nombre); 
  $("#modal-ver-ficha_tec").modal("show");
  $('#ver_fact_pdf').html(doc_view_extencion(ficha_tecnica, 'material', 'ficha_tecnica', '100%', '550'));

  if (DocExist(`dist/docs/material/ficha_tecnica/${ficha_tecnica}`) == 200) {
    $("#iddescargar").attr("href","../dist/docs/material/ficha_tecnica/"+ficha_tecnica).attr("download", 'ficha tecncia').removeClass("disabled");
    $("#ver_completo").attr("href","../dist/docs/material/ficha_tecnica/"+ficha_tecnica).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }
  $('.jq_image_zoom').zoom({ on:'grab' });
  $(".tooltip").removeClass("show").addClass("hidde");
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-materiales")[0]);

  $.ajax({
    url: "../ajax/materiales.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {         

          tabla.ajax.reload(null, false);

          limpiar_form_material();

          Swal.fire("Correcto!", "Insumo guardado correctamente", "success");
          tbla_materiales();
          $("#modal-agregar-material").modal("hide");          
          
        } else {
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
          $("#barra_progress").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  }).text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idproducto,cont) {
  limpiar_form_material(); 

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-material").modal("show");

  $.post("../ajax/materiales.php?op=mostrar", { 'idproducto': idproducto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status == true) {
      // input no usados
      $("#modelo").val(e.data.modelo);
      $("#serie").val(e.data.serie);
      $('#precio_unitario').val(e.data.precio_unitario);      
      $("#precio_sin_igv").val(e.data.precio_sin_igv);
      $("#precio_igv").val(e.data.precio_igv);
      $("#precio_total").val(e.data.precio_total);
      $("#color").val(e.data.idcolor);  
      $("#estado_igv").val(e.data.estado_igv);     
      $("#categoria_insumos_af").val(e.data.idcategoria_insumos_af); 

      // input usados
      $("#idproducto").val(e.data.idproducto);
      $("#nombre").val(e.data.nombre); 
      $("#unidad_medida").val(e.data.idunidad_medida).trigger("change");
      $("#marcas").val(e.data.id_marca).trigger("change");  
      $("#descripcion").val(e.data.descripcion);  
       
      if (e.data.imagen != "") {
        $("#imagen1_i").attr("src", "../dist/docs/material/img_perfil/" + e.data.imagen);  
        $("#imagen1_actual").val(e.data.imagen);
      }
  
      // FICHA TECNICA
      if (e.data.ficha_tecnica == "" || e.data.ficha_tecnica == null  ) {
  
        $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');  
        $("#doc2_nombre").html('');  
        $("#doc_old_2").val(""); $("#doc2").val("");
  
      } else {
  
        $("#doc_old_2").val(e.data.ficha_tecnica);   
        $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Ficha-tecnica.${extrae_extencion(e.data.ficha_tecnica)}</i></div></div>`);
        $("#doc2_ver").html(doc_view_extencion(e.data.ficha_tecnica, 'material', 'ficha_tecnica', '100%'));
              
      }
      $('.jq_image_zoom').zoom({ on:'grab' });
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

// ver detallles del registro
function verdatos(idproducto){

  $(".tooltip").removeClass("show").addClass("hidde");

  $('#datosinsumo').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div></div>`);

  var imagen_perfil =''; var btn_imagen_perfil = '';
  
  var ficha_tecnica=''; var btn_ficha_tecnica = '';

  $("#modal-ver-insumo").modal("show");

  $.post("../ajax/materiales.php?op=mostrar", { 'idproducto': idproducto }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status) {     
    
      if (e.data.imagen != '') {

        imagen_perfil=`<img src="../dist/docs/material/img_perfil/${e.data.imagen}" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`
        
        btn_imagen_perfil=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/material/img_perfil/${e.data.imagen}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/material/img_perfil/${e.data.imagen}" download="PERFIL - ${removeCaracterEspecial(e.data.nombre)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {

        imagen_perfil=`<img src="../dist/docs/material/img_perfil/producto-sin-foto.svg" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`;
        btn_imagen_perfil='';

      }     

      if (e.data.ficha_tecnica != '') {
        
        ficha_tecnica =  doc_view_extencion(e.data.ficha_tecnica, 'material', 'ficha_tecnica', '100%');
        
        btn_ficha_tecnica=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/material/ficha_tecnica/${e.data.ficha_tecnica}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/material/ficha_tecnica/${e.data.ficha_tecnica}" download="Ficha Tecnica - ${removeCaracterEspecial(e.data.nombre)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {

        ficha_tecnica='Sin Ficha Técnica';
        btn_ficha_tecnica='';

      }     

      var marca =""; cont=0;
      e.data.marcas.forEach((valor, index) => {
        cont=cont+1;
        marca = marca.concat( `<span class="username">${cont } ${valor}</span> </br>`);
      });

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th rowspan="2">${imagen_perfil}<br>${btn_imagen_perfil}</th>
                  <td> <b>Nombre: </b> ${e.data.nombre}</td>
                </tr>      
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td> <b>U.M: </b> ${e.data.nombre_medida}</td>
                </tr>                          
                   
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Marcas</th>
                  <td>${marca}</td>
                </tr>         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td><textarea cols="30" rows="2" class="textarea_datatable" readonly="">${e.data.descripcion}</textarea></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Ficha Técnica</th>
                  <td> ${ficha_tecnica} <br>${btn_ficha_tecnica}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $("#datosinsumo").html(retorno_html);
      $('.jq_image_zoom').zoom({ on:'grab' });
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function ver_perfil(file, nombre) {
  $('.foto-insumo').html(nombre);
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#modal-ver-perfil-insumo").modal("show");
  $('#perfil-insumo').html(`<span class="jq_image_zoom"><img class="img-thumbnail" src="${file}" onerror="this.src='../dist/svg/404-v2.svg';" alt="Perfil" width="100%"></span>`);
  $('.jq_image_zoom').zoom({ on:'grab' });
}

//Función para desactivar registros
function eliminar(idproducto, nombre) {

  crud_eliminar_papelera(
    "../ajax/materiales.php?op=desactivar",
    "../ajax/materiales.php?op=eliminar", 
    idproducto, 
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

$("#precio_unitario").on("keyup change", function(e) { desglosar_precio(); });
$("#precio_sin_igv").on("keyup change", function(e) { desglosar_precio(); });
$("#precio_igv").on("keyup change", function(e) { desglosar_precio(); });
$("#precio_con_igv").on("keyup change", function(e) { desglosar_precio(); });


init();
// .....::::::::::::::::::::::::::::::::::::: F A C T U R A S  :::::::::::::::::::::::::::::::::::::::..
// .....::::::::::::::::::::::::::::::::::::: F A C T U R A S  :::::::::::::::::::::::::::::::::::::::..
// .....::::::::::::::::::::::::::::::::::::: F A C T U R A S  :::::::::::::::::::::::::::::::::::::::..
// .....::::::::::::::::::::::::::::::::::::: F A C T U R A S  :::::::::::::::::::::::::::::::::::::::..

// TABLA - FACTURAS
function tbla_facuras( idproducto, nombre_producto, precio_promedio) {
  table_show_hide(3);
  idproducto_r = idproducto; nombre_producto_r = nombre_producto; precio_promedio_r = precio_promedio; 

  $(".cantidad_x_producto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.precio_promedio').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $(".descuento_x_producto").html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.subtotal_x_producto').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  $(".nombre-insumo").html(`Insumo :<b>${nombre_producto}</b>`);

	tabla_factura = $('#tbla-facura').dataTable({
		responsive: false,
		lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
		aProcessing: true,//Activamos el procesamiento del datatables
		aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-sm-12 col-md-6 col-lg-7 col-xl-8 pt-2'f><'col-sm-12 col-md-6 col-lg-7 col-xl-4 pt-2 d-flex justify-content-end align-items-center'<'length'l><'buttons'B>>>r t <'row'<'col-md-6'i><'col-md-6'p>>",
		buttons: [ 
      { text: '<i class="fa-solid fa-arrows-rotate"></i> ', className: "buttons-reload px-3", action: function ( e, dt, node, config ) { if (dt) { dt.ajax.reload(null, false); toastr_success('Actualizado', 'Tabla actualizada'); } } },
      { text: '<i class="fa-regular fa-copy fa-lg"></i>', extend: 'copyHtml5', footer: true,exportOptions: { columns: [0,2,10,11,4,5,6,8]} }, 
      { text: '<i class="fa-regular fa-file-excel fa-lg"></i>', extend: 'excelHtml5', footer: true,exportOptions: { columns: [0,2,10,11,4,5,6,8]} }, 
      { text: '<i class="fa-regular fa-file-pdf fa-lg"></i>', extend: 'pdfHtml5', footer: true,exportOptions: { columns: [0,2,10,11,4,5,6,8]}, orientation: 'landscape', pageSize: 'LEGAL', }
    ],
		ajax:	{
      url: `../ajax/materiales.php?op=tbla_facturas&idproducto=${idproducto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: Cantidad
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: Cantidad
      if (data[3] != '') { $("td", row).eq(3).addClass("text-nowrap"); }
      // columna: Cantidad
      if (data[6] != '') { $("td", row).eq(6).addClass("text-center"); }
      // columna: Precio promedio
      if (data[7] != '') { $("td", row).eq(7).addClass("text-bold h5"); }
    },
		language: {
      lengthMenu: "_MENU_",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...',
      search: "",
    },
		bDestroy: true,
    footerCallback: function( tfoot, data, start, end, display ) {
      var api = this.api(); var subtotal = api.column( 6 ).data().reduce( function ( a, b ) { return parseFloat(a) + parseFloat(b); }, 0 )
      $( api.column( 6 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(subtotal)}</span>` );

      var api = this.api(); var subtotal = api.column( 7 ).data().reduce( function ( a, b ) { return parseFloat(a) + parseFloat(b); }, 0 )
      $( api.column( 7 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(subtotal)}</span>` );
      // console.log('footer: '+total);
      var api = this.api(); var igv = api.column( 8 ).data().reduce( function ( a, b ) { return parseFloat(a) + parseFloat(b); }, 0 )
      $( api.column( 8 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(igv)}</span>` );

      var api = this.api(); var igv = api.column( 9 ).data().reduce( function ( a, b ) { return parseFloat(a) + parseFloat(b); }, 0 )
      $( api.column( 9 ).footer() ).html( ` <span class="float-left">S/</span> <span class="float-right">${formato_miles(igv)}</span>` );
    
    },
    initComplete: function () {
      var api = this.api();      
      $(api.table().container()).find('.dataTables_filter input').addClass('border border-primary bg-light m-0');// Agregar clase bg-light al input de búsqueda
    },
		iDisplayLength: 10,//Paginación
		order: [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [      
      { targets: [5], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [7,8,9], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
      { targets: [11,12], visible: false, searchable: false, },
    ],
	}).DataTable();  

}

//mostramos el detalle del comprobante de la compras
function ver_detalle_compras(idcompra_proyecto, id_insumo) {

  $("#cargando-5-fomulario").hide();
  $("#cargando-6-fomulario").show();

  $("#print_pdf_compra").addClass('disabled');
  $("#excel_compra").addClass('disabled');

  $("#modal-ver-compras").modal("show");

  $.post(`../ajax/ajax_general.php?op=detalle_compra_de_insumo&id_compra=${idcompra_proyecto}&id_insumo=${id_insumo}`, function (e) {
    e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      $(".detalle_de_compra").html(e.data); 
      $("#cargando-5-fomulario").show();
      $("#cargando-6-fomulario").hide();

      $("#print_pdf_compra").removeClass('disabled');    
      $("#excel_compra").removeClass('disabled');
      $("#print_pdf_compra").attr('href', `../reportes/pdf_compra.php?id=${idcompra_proyecto}&op=insumo` );
    } else {
      ver_errores(e);
    }   
  }).fail( function(e) { ver_errores(e); } ); 
}


function comprobante_compras(idcompra_proyecto,num_orden, num_comprobante,fecha) {
  // limpiar_form_comprobante();
  // alert(idcompra_proyecto,num_orden, num_comprobante,fecha);
  tbla_comprobantes_compras(idcompra_proyecto, num_orden);

  $('.titulo-comprobante-compra').html(`Comprobante: <b>${num_orden}. ${num_comprobante} - ${fecha}</b>`);
  $("#modal-tabla-comprobantes-compra").modal("show"); 
}

function tbla_comprobantes_compras(id_compra, num_orden) {
  tabla_comprobantes = $("#tabla-comprobantes-compra").dataTable({
    responsive: true, 
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [ ],
    ajax: {
      url: `../ajax/materiales.php?op=tbla_comprobantes_compra&id_compra=${id_compra}&num_orden=${num_orden}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    }, 
    createdRow: function (row, data, ixdex) {
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center"); }
      if (data[2] != '') { $("td", row).eq(2).addClass("text-center"); }
      if (data[3] != '') { $("td", row).eq(3).addClass("text-nowrap"); }
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
      { targets: [3], render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY hh:mm:ss a'), },
      //{ targets: [8,11],  visible: false,  searchable: false,  },
    ],
  }).DataTable();
}


// .....::::::::::::::::::::::::::::::::::::: E D I T A R   F A C T U R A S  :::::::::::::::::::::::::::::::::::::::..
// .....::::::::::::::::::::::::::::::::::::: E D I T A R   F A C T U R A S   :::::::::::::::::::::::::::::::::::::::..
// .....::::::::::::::::::::::::::::::::::::: E D I T A R   F A C T U R A S   :::::::::::::::::::::::::::::::::::::::..

// LIMPIAR FORM
function limpiar_form_compra() {
  $(".tooltip").removeClass("show").addClass("hidde");

  array_class_compra = [];

  $("#idcompra_proyecto").val();
  $("#idproyecto").val();
  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprobante").val("Ninguno").trigger("change");
  $("#glosa").val("null").trigger("change");
  $("#slt2_serie_comprobante").val("null").trigger("change");
  
  $("#serie_comprobante").val("");
  $("#val_igv").val(0);
  $("#descripcion").val("");
  
  $("#total_venta").val("");  
  $(".total_venta").html("0");

  $(".subtotal_compra").html("S/ 0.00");
  $("#subtotal_compra").val("");

  $(".igv_compra").html("S/ 0.00");
  $("#igv_compra").val("");

  $(".total_venta").html("S/ 0.00");
  $("#total_venta").val("");

  $("#estado_detraccion").val("0");
  $('#my-switch_detracc').prop('checked', false); 

  $(".filas").remove();

  cont = 0;

  // Limpiamos las validaciones
  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

//Función para guardar o editar - COMPRAS
function guardar_y_editar_compras(e) {
   
  var formData = new FormData($("#form-compras")[0]);

  Swal.fire({
    title: "¿Está seguro que deseas guardar esta compra?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
    preConfirm: (input) => {
      return fetch("../ajax/ajax_general.php?op=guardar_y_editar_compra", {
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
        Swal.fire("Correcto!", "Compra guardada correctamente", "success");
        tbla_facuras( idproducto_r, nombre_producto_r, precio_promedio_r, );
        tbla_principal();
        limpiar_form_compra();
        table_show_hide(3);  cont = 0;
      } else {
        ver_errores(result.value);
      }      
    }    
  });
}

// DETALLE DEL MATERIAL
function mostrar_detalle_material(idproducto) {  
  
  $('#datosproductos').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br /><br /><h4>Cargando...</h4></div></div>`);

  var verdatos=''; var imagenver='';

  $("#modal-ver-detalle-material-activo-fijo").modal("show")

  $.post("../ajax/resumen_insumos.php?op=mostrar_materiales", { 'idproducto_p': idproducto }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    if (e.status) {     
    
      if (e.data.imagen != '') {

        imagen_perfil=`<img src="../dist/docs/material/img_perfil/${e.data.imagen}" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`
        
        btn_imagen_perfil=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/material/img_perfil/${e.data.imagen}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/material/img_perfil/${e.data.imagen}" download="PERFIL - ${removeCaracterEspecial(e.data.nombre)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {

        imagen_perfil=`<img src="../dist/docs/material/img_perfil/producto-sin-foto.svg" onerror="this.src='../dist/svg/404-v2.svg';" alt="" class="img-thumbnail w-150px">`;
        btn_imagen_perfil='';

      }     

      if (e.data.ficha_tecnica != '') {
        
        ficha_tecnica =  doc_view_extencion(e.data.ficha_tecnica, 'material', 'ficha_tecnica', '100%');
        
        btn_ficha_tecnica=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/material/ficha_tecnica/${e.data.ficha_tecnica}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/material/ficha_tecnica/${e.data.ficha_tecnica}" download="Ficha Tecnica - ${removeCaracterEspecial(e.data.nombre)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;
      
      } else {

        ficha_tecnica='Sin Ficha Técnica';
        btn_ficha_tecnica='';

      }     

      var marca =""; cont=0;
      e.data.marcas.forEach((valor, index) => {
        cont=cont+1;
        marca = marca.concat( `<span class="username">${cont } ${valor}</span> </br>`);
      });

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th rowspan="2">${imagen_perfil}<br>${btn_imagen_perfil}</th>
                  <td> <b>Nombre: </b> ${e.data.nombre}</td>
                </tr>      
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td> <b>U.M: </b> ${e.data.nombre_medida}</td>
                </tr>    
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Clasificación</th>
                  <td>${e.data.categoria}</td>
                </tr>                       
                   
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Marcas</th>
                  <td>${marca}</td>
                </tr>         
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td><textarea cols="30" rows="2" class="textarea_datatable" readonly="">${e.data.descripcion}</textarea></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Ficha Técnica</th>
                  <td> ${ficha_tecnica} <br>${btn_ficha_tecnica}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $("#datosproductos").html(retorno_html);

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } ); 

}

function actualizar_producto() {

  var idproducto = $("#idproducto_p").val(); 
  var cont = $("#cont").val(); 

  var nombre_p        = $("#nombre_p").val();  
  var unidad_medida_p = $("#unidad_medida_p").find(':selected').text();
  var categoria       = $("#categoria_insumos_af_p").find(':selected').text();

  if (idproducto == "" || idproducto == null) {  } else {
    $(`.nombre_producto_${cont}`).html(nombre_p);     
    $(`.clasificacion_${cont}`).html(`<b>Clasificación: </b>${categoria}`);
    $(`.unidad_medida_${cont}`).html(unidad_medida_p); 
    $(`.unidad_medida_${cont}`).val(unidad_medida_p);

    if ($('#foto2').val()) {
      var src_img = $(`#foto2_i`).attr("src");
      $(`.img_perfil_${cont}`).attr("src", src_img);
    }  
  } 
  
}


// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION AGREGAR PROVEEDOR ::::::::::::::::::::::::::::::::::::::::::::::::::::
//Función limpiar
function limpiar_form_proveedor() {
  $("#idproveedor_prov").val("");
  $("#tipo_documento_prov option[value='RUC']").attr("selected", true);
  $("#nombre_prov").val("");
  $("#num_documento_prov").val("");
  $("#direccion_prov").val("");
  $("#telefono_prov").val("");
  $("#c_bancaria_prov").val("");
  $("#cci_prov").val("");
  $("#c_detracciones_prov").val("");
  $("#banco_prov").val("").trigger("change");
  $("#titular_cuenta_prov").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");

  $(".tooltip").removeClass("show").addClass("hidde");
}

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco_prov").select2("val") == null || $("#banco_prov").select2("val") == "" || $("#banco_prov").select2("val") == "1" ) {

    $("#c_bancaria_prov").prop("readonly", true);
    $("#cci_prov").prop("readonly", true);
    $("#c_detracciones_prov").prop("readonly", true);

  } else {
    
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-3").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');    

    $.post("../ajax/ajax_general.php?op=formato_banco", { 'idbanco': $("#banco_prov").select2("val") }, function (e, status) {
      
      e = JSON.parse(e);  // console.log(e);
      if (e.status == true) {
        $("#c_bancaria_prov").prop("readonly", false);
        $("#cci_prov").prop("readonly", false);
        $("#c_detracciones_prov").prop("readonly", false);

        var format_cta = decifrar_format_banco(e.data.formato_cta);
        var format_cci = decifrar_format_banco(e.data.formato_cci);
        var formato_detracciones = decifrar_format_banco(e.data.formato_detracciones);
        // console.log(format_cta, formato_detracciones);

        $("#c_bancaria_prov").inputmask(`${format_cta}`);
        $("#cci_prov").inputmask(`${format_cci}`);
        $("#c_detracciones_prov").inputmask(`${formato_detracciones}`);

        $(".chargue-format-1").html("Cuenta Bancaria");
        $(".chargue-format-2").html("CCI");
        $(".chargue-format-3").html("Cuenta Detracciones");
      } else {
        ver_errores(e);
      }
      
    }).fail( function(e) { ver_errores(e); } ); 
  }
}

//guardar proveedor
function guardar_proveedor(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);
  crud_guardar_editar_modal_select2_xhr( 
    "../ajax/resumen_insumos.php?op=guardar_proveedor", 
    formData,
    '#barra_progress_proveeedor', 
    "../ajax/ajax_general.php?op=select2Proveedor", 
    '#idproveedor',
    function(){ limpiar_form_proveedor(); $("#modal-agregar-proveedor").modal("hide"); }, 
    function(){ sw_success('Correcto!', "Proveedor guardado correctamente." ); }, 
  );
}

function mostrar_para_editar_proveedor() {
  $("#cargando-7-fomulario").hide();
  $("#cargando-8-fomulario").show();
  limpiar_form_proveedor();
  $('#modal-agregar-proveedor').modal('show');
  $(".tooltip").remove();

  $.post("../ajax/resumen_insumos.php?op=mostrar_editar_proveedor", { 'idproveedor': $('#idproveedor').select2("val") }, function (e, status) {

    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {     
      $("#idproveedor_prov").val(e.data.idproveedor);
      $("#tipo_documento_prov option[value='" + e.data.tipo_documento + "']").attr("selected", true);
      $("#nombre_prov").val(e.data.razon_social);
      $("#num_documento_prov").val(e.data.ruc);
      $("#direccion_prov").val(e.data.direccion);
      $("#telefono_prov").val(e.data.telefono);
      $("#banco_prov").val(e.data.idbancos).trigger("change");
      $("#c_bancaria_prov").val(e.data.cuenta_bancaria);
      $("#cci_prov").val(e.data.cci);
      $("#c_detracciones_prov").val(e.data.cuenta_detracciones);
      $("#titular_cuenta_prov").val(e.data.titular_cuenta);      

      $("#cargando-7-fomulario").show();
      $("#cargando-8-fomulario").hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); });
}
// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION AGREGAR PRODUCTO ::::::::::::::::::::::::::::::::::::::::::::::::::::
// TABLA - MATERIALES
function tbla_materiales() {

  tabla_materiales = $("#tblamateriales").dataTable({
    responsive: true,
    lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [],
    ajax: {
      url: "../ajax/ajax_general.php?op=tblaInsumosYActivosFijos",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[3] != '') { $("td", row).eq(3).addClass('text-right'); }  
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    // order: [[0, "desc"]], //Ordenar (columna,orden)
    columnDefs: [ 
      //{ targets: [6], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [3], render: $.fn.dataTable.render.number(',', '.', 2) },
      //{ targets: [3], visible: false, searchable: false, }, 
    ]
  }).DataTable();
}



// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {   

  $('#unidad_medida').on('change', function() { $(this).trigger('blur'); });

  $("#form-materiales").validate({
    rules: {
      nombre:         { required: true, minlength:3, maxlength:200},
      unidad_medida:  { required: true },
      marca:          { required: true },
      descripcion:    { minlength: 4 },
    },
    messages: {
      nombre:         { required: "Por favor ingrese nombre", minlength:"Minimo 3 caracteres", maxlength:"Maximo 200 caracteres" },
      unidad_medida:  { required: "Campo requerido" },
      marca:          { required: "Campo requerido" },    
      descripcion:    { minlength: "Minimo 4 caracteres" },
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

  $('#unidad_medida').rules('add', { required: true, messages: {  required: "Campo requerido" } });

  //------------------------------compras-------------------
  $("#idproveedor").on('change', function() { $(this).trigger('blur'); });
  $("#glosa").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_comprobante").on('change', function() { $(this).trigger('blur'); });
  $("#banco_prov").on('change', function() { $(this).trigger('blur'); });
  $("#unidad_medida_p").on('change', function() { $(this).trigger('blur'); });
  $("#idclasificacion_grupo_g").on('change', function() { $(this).trigger('blur'); });
  $("#marcas_p").on('change', function() { $(this).trigger('blur'); });

  $("#form-compras").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproveedor:    { required: true },
      tipo_comprobante:{ required: true },
      serie_comprobante:{ minlength: 2 },
      descripcion:    { minlength: 4 },
      fecha_compra:   { required: true },
      glosa:          { required: true },
      val_igv:        { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idproveedor:    { required: "Campo requerido", },
      tipo_comprobante:{ required: "Campo requerido", },
      serie_comprobante:{ minlength: "mayor a 2 caracteres", },
      descripcion:    { minlength: "mayor a 4 caracteres", },
      fecha_compra:   { required: "Campo requerido", },
      glosa:          { required: "Campo requerido", },
      val_igv:        { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
      'cantidad[]':     { min: "Mínimo 0.01", required: "Campo requerido"},
      'precio_con_igv[]':{ min: "Mínimo 0.01", required: "Campo requerido"}
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

    submitHandler: function (form) {
      guardar_y_editar_compras(form);
    },
  });

  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#glosa").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_comprobante").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco_prov").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#unidad_medida_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#idclasificacion_grupo_g").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#marcas_p").rules('add', { required: true, messages: {  required: "Campo requerido" } });

});


function extrae_ruc() {
  if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { 
    $('.btn-editar-proveedor').addClass('disabled').attr('data-original-title','Seleciona un proveedor');
    $('.btn-editar-proveedor').removeAttr('onclick');

  } else { 
    if ($('#idproveedor').select2("val") == 1) {
      $('.btn-editar-proveedor').addClass('disabled').attr('data-original-title','No editable');
    $('.btn-editar-proveedor').removeAttr('onclick');

      var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
      $('#ruc_proveedor').val(ruc);

    } else{
      var name_proveedor = $('#idproveedor').select2('data')[0].text;
      $('.btn-editar-proveedor').removeClass('disabled').attr('data-original-title',`Editar: ${recorte_text(name_proveedor, 15)}`);   
      var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
      $('#ruc_proveedor').val(ruc);
    $('.btn-editar-proveedor').attr('onclick', 'mostrar_para_editar_proveedor(this);');

    }
  }
  $('[data-toggle="tooltip"]').tooltip();
}


// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

