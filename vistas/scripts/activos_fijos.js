var tabla;

//Función que se ejecuta al inicio
function init() {  
  
  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");
  $("#bloc_ActivoFijo").addClass("menu-open bg-color-191f24");

  $("#mRecurso").addClass("active");
  $("#mActivoFijo").addClass("active bg-primary");

  $("#lActivosfijos").addClass("active ");

  lista_de_items();
  tabla_principal('todos');

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/ajax_general.php?op=select2Marcas", '#marcas', null);
  lista_select2("../ajax/ajax_general.php?op=select2UnidaMedida", '#unidad_medida', null);
  lista_select2("../ajax/ajax_general.php?op=select2Categoria", '#categoria_insumos_af', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) {  $("#submit-form-activos-fijos").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#marcas").select2({placeholder: "Seleccinar marcas", });
  $("#unidad_medida").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });
  $("#categoria_insumos_af").select2({ theme: "bootstrap4", placeholder: "Seleccinar una categoria", allowClear: true, });

  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $('#precio_unitario').number( true, 2 );
  $('#precio_sin_igv').number( true, 2 );
  $('#precio_igv').number( true, 2 );
  $('#precio_total').number( true, 2 );
}

function templateColor (state) {
  if (!state.id) { return state.text; }
  var color_bg = state.title != '' ? `${state.title}`: '#ffffff00';   
  var $state = $(`<span ><b style="background-color: ${color_bg}; color: ${color_bg};" class="mr-2"><i class="fas fa-square"></i><i class="fas fa-square"></i></b>${state.text}</span>`);
  return $state;
}

// abrimos el navegador de archivos
$("#foto1_i").click(function () { $("#foto1").trigger("click"); });
$("#foto1").change(function (e) { addImage(e, $("#foto1").attr("id"), "../dist/img/default/img_defecto_activo_fijo.png"); });

//ficha tecnica
$("#doc2_i").click(function() { $('#doc2').trigger('click'); });
$("#doc2").change(function(e) { addImageApplication(e, $("#doc2").attr("id") ); });

function foto1_eliminar() {
  $("#foto1").val("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto_activo_fijo.png");

  $("#foto1_nombre").html("");
}

// Eliminamos el doc 2
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

//Función limpiar
function limpiar() {
  
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

  //input usados
  $("#idproducto").val("");  
  $("#nombre").val("");  
  $("#categoria_insumos_af").val("").trigger("change");
  $("#unidad_medida").val("").trigger("change");  
  $("#marcas").val("").trigger("change");  
  $("#descripcion").val("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto_activo_fijo.png");
  $("#foto1").val("");
  $("#foto1_actual").val("");
  $("#foto1_nombre").html("");   

  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function lista_de_items() { 

  $(".lista-items").html(`<li class="nav-item"><a class="nav-link active" role="tab" ><i class="fas fa-spinner fa-pulse fa-sm"></i></a></li>`); 

  $.post("../ajax/activos_fijos.php?op=lista_de_categorias", function (e, status) {
    
    e = JSON.parse(e); console.log(e);
    // e.data.idtipo_tierra
    if (e.status) {
      var data_html = '';

      e.data.forEach((val, index) => {
        data_html = data_html.concat(`
        <li class="nav-item">
          <a class="nav-link" onclick="delay(function(){tabla_principal('${val.idcategoria}')}, 50 );" id="tabs-for-activo-fijo-tab" data-toggle="pill" href="#tabs-for-activo-fijo" role="tab" aria-controls="tabs-for-activo-fijo" aria-selected="false">${val.nombre}</a>
        </li>`);
      });

      $(".lista-items").html(`
        <li class="nav-item">
          <a class="nav-link active" onclick="delay(function(){tabla_principal('todos')}, 50 );" id="tabs-for-activo-fijo-tab" data-toggle="pill" href="#tabs-for-activo-fijo" role="tab" aria-controls="tabs-for-activo-fijo" aria-selected="true">Todos</a>
        </li>
        ${data_html}
      `); 
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función Listar
function tabla_principal(id_categoria) {
  tabla = $("#tabla-activos").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,10,4,5,11,7,8], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,10,4,5,11,7,8], } }, 
      { extend: 'pdfHtml5', footer: false, orientation: 'landscape', pageSize: 'LEGAL', exportOptions: { columns: [0,2,10,4,5,11,7,8], } },      
    ],
    ajax: {
      url: `../ajax/activos_fijos.php?op=tabla_principal&id_categoria=${id_categoria}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {         
      // columna: #
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); } 
      // columna: op
      if (data[1] != '') { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: code
      if (data[2] != '') { $("td", row).eq(2).addClass("text-nowrap"); }
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
      { targets: [10,11], visible: false, searchable: false, },
      { targets: [7], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();
}

//ver ficha tecnica
function modal_ficha_tec(ficha_tecnica) {
  var ficha_tec = ficha_tecnica;
  console.log(ficha_tec);
  var extencion = ficha_tec.substr(ficha_tec.length - 3); // => "1"
  //console.log(extencion);
  $("#ver_fact_pdf").html("");
  $("#img-factura").attr("src", "");
  $("#modal-ver-ficha_tec").modal("show");

  if (extencion == "jpeg" || extencion == "jpg" || extencion == "png" || extencion == "webp") {
    $("#ver_fact_pdf").hide();
    $("#img-factura").show();
    $("#img-factura").attr("src", "../dist/docs/activos_fijos_general/ficha_tecnica_activos_fijos/" + ficha_tec);

    $("#iddescargar").attr("href", "../dist/docs/activos_fijos_general/ficha_tecnica_activos_fijos/" + ficha_tec);
  } else {
    $("#img-factura").hide();

    $("#ver_fact_pdf").show();

    $("#ver_fact_pdf").html('<iframe src="../dist/docs/activos_fijos_general/ficha_tecnica_activos_fijos/' + ficha_tec + '" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');

    $("#iddescargar").attr("href", "../dist/docs/activos_fijos_general/ficha_tecnica_activos_fijos/" + ficha_tec);
  }

  $(".tooltip").removeClass("show").addClass("hidde");
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-materiales-activos-fijos")[0]);

  $.ajax({
    url: "../ajax/activos_fijos.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      e = JSON.parse(e);  console.log(e);  
      if (e.status == true) {         
        Swal.fire("Correcto!", "Trabajador guardado correctamente", "success");
        tabla.ajax.reload(null, false);
        limpiar();
        $("#modal-agregar-activos-fijos").modal("hide");
        $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
      } else {         
        ver_errores(e);
      }
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
      $("#barra_progress").text("0%");
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idproducto) {
  limpiar();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-activos-fijos").modal("show");

  $.post("../ajax/activos_fijos.php?op=mostrar", { 'idproducto': idproducto }, function (e, status) {
    
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

      // input usados
      $("#idproducto").val(e.data.idproducto);
      $("#nombre").val(e.data.nombre);      
      $("#categoria_insumos_af").val(e.data.idcategoria_insumos_af).trigger("change");
      $("#unidad_medida").val(e.data.idunidad_medida).trigger("change");
      $("#marcas").val(e.data.id_marca).trigger("change");  
      $("#descripcion").val(e.data.descripcion);  
       
      if (e.data.imagen != "") {        
        $("#foto1_i").attr("src", "../dist/docs/material/img_perfil/" + e.data.imagen);  
        $("#foto1_actual").val(e.data.imagen);
      }
  
      // FICHA TECNICA
      if (e.data.ficha_tecnica == "" || e.data.ficha_tecnica == null  ) {
  
        $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
  
        $("#doc1_nombre").html('');
  
        $("#doc_old_2").val(""); $("#doc1").val("");
  
      } else {
  
        $("#doc_old_2").val(e.data.ficha_tecnica); 
  
        $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Ficha-tecnica.${extrae_extencion(e.data.ficha_tecnica)}</i></div></div>`);
       
        $("#doc2_ver").html(doc_view_extencion(e.data.ficha_tecnica, 'material', 'ficha_tecnica', '100%'));           
      } 
  
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

  $('#datos-activos-fjos').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div></div>`);

  var verdatos=''; 

  var imagen_perfil =''; var btn_imagen_perfil = '';
  
  var ficha_tecnica=''; var btn_ficha_tecnica = '';

  $("#modal-ver-activos-fijos").modal("show");

  $.post("../ajax/activos_fijos.php?op=mostrar", { 'idproducto': idproducto }, function (e, status) {

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

      var marca =""; 
      e.data.marcas.forEach((valor, index) => { marca = marca.concat( `<span class="username">${index + 1 } ${valor}</span> </br>`);  });   

      verdatos=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th >${imagen_perfil}<br>${btn_imagen_perfil}</th>
                  <td> <b>Nombre: </b> ${e.data.nombre}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Clasificación</th>
                  <td>${e.data.categoria}</td>
                </tr> 
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>U.M.</th>
                  <td>${e.data.nombre_medida}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Marca</th>
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
    
      $("#datos-activos-fjos").html(verdatos);

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function ver_perfil(file, nombre) {
  $('.foto-insumo').html(nombre);
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#modal-ver-perfil-activo-fijo").modal("show");
  $('#perfil-insumo').html(`<center><img src="${file}" onerror="this.src='../dist/svg/404-v2.svg';" class="img-thumbnail" alt="Perfil" width="100%"></center>`);
}

//Función para desactivar registros
function eliminar(idproducto, nombre) {
  //----------------------------

  crud_eliminar_papelera(
    "../ajax/activos_fijos.php?op=desactivar",
    "../ajax/activos_fijos.php?op=eliminar", 
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

$(function () {

  $('#unidad_medida').on('change', function() { $(this).trigger('blur'); });
  $('#marca').on('change', function() { $(this).trigger('blur'); });
  $('#categoria_insumos_af').on('change', function() { $(this).trigger('blur'); });

  $("#form-materiales-activos-fijos").validate({
    rules: {
      nombre:         { required: true, minlength:3, maxlength:200},
      categoria_insumos_af: { required: true },      
      unidad_medida:  { required: true },
      marca:          { required: true },
      descripcion:    { minlength: 4 },
    },
    messages: {
      nombre:         { required: "Por favor ingrese nombre", minlength:"Minimo 3 caracteres", maxlength:"Maximo 200 caracteres" },
      categoria_insumos_af: { required: "Campo requerido", },      
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
  $('#marca').rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $('#categoria_insumos_af').rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


