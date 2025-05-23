var tabla;

//Función que se ejecuta al inicio
function init() {
  
  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lFierro").addClass("active bg-primary");

  tbla_principal();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-materiales").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════  
  $("#unidad_medida").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });

  $('.jq_image_zoom').zoom({ on:'grab' });
  // Formato para telefono
  $("[data-mask]").inputmask();
}

// abrimos el navegador de archivos

//ficha tecnica
$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addImageApplication(e,$("#doc2").attr("id")) });

// Eliminamos el doc 2
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

//Función limpiar
function limpiar_form_material() {

  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');

  //Mostramos los Materiales
  $("#idproducto").val("");
  $("#nombre_material").val("");
  
  $("#precio_real").val("");
  $(".precio_real").val("");  
  $("#monto_igv").val("");
  $(".monto_igv").val("");
  $("#total_precio").val("");
  $(".total_precio").val("");  

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
function tbla_principal() {
  tabla = $("#tabla-materiales").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,11,12,3,4,5,6,7,8,13], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,11,12,3,4,5,6,7,8,13], } }, { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,11,12,3,4,5,6,7,8,13], } }, {extend: "colvis"} ,
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
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center text-nowrap"); }
      // columna: # 5
      if (data[5] != '') { $("td", row).eq(5).addClass("text-right text-nowrap"); }
      // columna: # 6
      if (data[6] != '') { $("td", row).eq(6).addClass("text-right text-nowrap"); }
      // columna: # 7
      if (data[7] != '') { $("td", row).eq(7).addClass("text-right text-nowrap"); }
      // columna: #8
      if (data[8] != '') { $("td", row).eq(8).addClass("text-right text-nowrap"); }
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
      { targets: [11,12,13], visible: false, searchable: false, },  
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
          Swal.fire("Correcto!", "Insumo guardado correctamente", "success");

          tabla.ajax.reload(null, false);

          limpiar_form_material();

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

function mostrar(idproducto) {
  limpiar_form_material(); //console.log(idproducto);

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-material").modal("show");

  $.post("../ajax/materiales.php?op=mostrar", { 'idproducto': idproducto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status) {
      $("#idproducto").val(e.data.idproducto);
      $("#nombre_material").val(e.data.nombre);
      $("#modelo").val(e.data.modelo);
      $("#serie").val(e.data.serie);
      $("#marca").val(e.data.marca);            
      $("#descripcion_material").val(e.data.descripcion);

      $("#precio_unitario").val(parseFloat(e.data.precio_unitario).toFixed(2));
      
      $("#precio_real").val(e.data.precio_sin_igv);    
      $("#monto_igv").val(e.data.precio_igv);
      $("#total_precio").val(parseFloat(e.data.precio_total).toFixed(2));    

      $(".precio_real").val(parseFloat(e.data.precio_sin_igv).toFixed(2));
      $(".monto_igv").val(parseFloat(e.data.precio_igv).toFixed(2));
      $(".total_precio").val(parseFloat(e.data.precio_total).toFixed(2));    
      
      $("#unidad_medida").val(e.data.idunidad_medida).trigger("change");
      $("#color").val(e.data.idcolor).trigger("change");

      if (e.data.estado_igv == "1") {
        $("#my-switch_igv").prop("checked", true);
        $("#estado_igv").val(1);
      } else {
        $("#my-switch_igv").prop("checked", false);
        $("#estado_igv").val(0);
      }     
       
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

    e = JSON.parse(e);  //console.log(e); 
    
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
                  <td> <b>Color: </b> ${e.data.nombre_color}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>U.M.</th>
                  <td>${e.data.nombre_medida}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Marca</th>
                    <td>${e.data.marca}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Con IGV</th>
                  <td>${(e.data.estado_igv==1? '<div class="myestilo-switch ml-2"><div class="switch-toggle"><input type="checkbox" id="my-switch-igv-2" checked disabled /><label for="my-switch-igv-2"></label></div></div>' : '<div class="myestilo-switch ml-3"><div class="switch-toggle"><input type="checkbox" id="my-switch-igv-2" disabled/><label for="my-switch-igv-2"></label></div></div>')}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Precio  </th>
                  <td>${e.data.precio_unitario}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Sub Total</th>
                  <td>${e.data.precio_sin_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${e.data.precio_igv}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total </th>
                  <td>${e.data.precio_total}</td>
                </tr> 
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Modelo</th>
                  <td>${e.data.modelo}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Serie</th>
                  <td>${e.data.serie}</td>
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

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {   

  $('#unidad_medida').on('change', function() { $(this).trigger('blur'); });
  $('#color').on('change', function() { $(this).trigger('blur'); });

  $("#form-materiales").validate({
    rules: {
      nombre_material:      { required: true },
      descripcion_material: { minlength: 4 },
      unidad_medida:          { required: true },
      color:                { required: true },
      precio_unitario:      { required: true },
    },
    messages: {
      nombre_material:      { required: "Campo requerido.", },
      descripcion_material: { minlength: "MINIMO 4 caracteres." },
      unidad_medida:          { required: "Campo requerido.", },
      color:                { required: "Campo requerido.", },
      precio_unitario:      { required: "Campo requerido.", },
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
  $('#color').rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

