var tabla_almacen_resumen;
var tabla_almacen_detalle;

//Función que se ejecuta al inicio
function init() {  
  
  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");
  $("#mRecurso").addClass("active");
  $("#lAlmacenGeneral").addClass("active");

  lista_de_items();
  tabla_principal('todos');

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  // lista_select2("../ajax/ajax_general.php?op=select2Marcas", '#marcas', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_almacen").on("click", function (e) {  $("#submit-form-almacen-general").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════ 
  // $("#unidad_medida").select2({ theme: "bootstrap4", placeholder: "Seleccinar una unidad", allowClear: true, });

  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  // $('#precio_unitario').number( true, 2 );
}

function templateColor (state) {
  if (!state.id) { return state.text; }
  var color_bg = state.title != '' ? `${state.title}`: '#ffffff00';   
  var $state = $(`<span ><b style="background-color: ${color_bg}; color: ${color_bg};" class="mr-2"><i class="fas fa-square"></i><i class="fas fa-square"></i></b>${state.text}</span>`);
  return $state;
}

//Función limpiar
function limpiar() {
  
  $("#guardar_registro_almacen").html('Guardar Cambios').removeClass('disabled');  
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

  $.post("../ajax/almacen_general.php?op=lista_de_categorias", function (e, status) {
    
    e = JSON.parse(e); console.log(e);
    // e.data.idtipo_tierra
    if (e.status == true) {
      var data_html = '';

      e.data.forEach((val, index) => {
        data_html = data_html.concat(`
        <li class="nav-item">
          <a class="nav-link" onclick="delay(function(){tabla_detalle('${val.idcategoria}')}, 50 );" id="tabs-for-detalle-tab" data-toggle="pill" href="#tabs-for-detalle" role="tab" aria-controls="tabs-for-detalle" aria-selected="false">${val.nombre}</a>
        </li>`);
      });

      $(".lista-items").html(`
        <li class="nav-item">
          <a class="nav-link active" onclick="delay(function(){tabla_principal('todos')}, 50 );" id="tabs-for-almacen-tab" data-toggle="pill" href="#tabs-for-almacen" role="tab" aria-controls="tabs-for-almacen" aria-selected="true">Todos</a>
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
  tabla_almacen_resumen = $("#tabla-almacen").dataTable({
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
      url: `../ajax/almacen_general.php?op=tabla_principal&id_categoria=${id_categoria}`,
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
      // { targets: [10,11], visible: false, searchable: false, },
      // { targets: [7], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();
}

function tabla_detalle(id_categoria) {
  tabla_almacen_detalle = $("#tabla-detalle-almacen").dataTable({
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
      url: `../ajax/almacen_general.php?op=tabla_detalle&id_almacen=${id_categoria}&id_proyecto=${localStorage.getItem("nube_idproyecto")}`,
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
      // { targets: [10,11], visible: false, searchable: false, },
      // { targets: [7], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },
    ],
  }).DataTable();
}

//ver ficha tecnica
function modal_ficha_tec(ficha_tecnica) {

  $(".tooltip").remove();
}
//Función para guardar o editar

function guardar_y_editar_almacen(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-almacen-general")[0]);

  $.ajax({
    url: "../ajax/almacen_general.php?op=guardar_y_editar_almacen",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {         
          Swal.fire("Correcto!", "Trabajador guardado correctamente", "success");
          tabla_almacen_resumen.ajax.reload(null, false); lista_de_items();
          limpiar();
          $("#modal-agregar-almacen-general").modal("hide");          
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
          $("#barra_progress").css({"width": percentComplete+'%'}).text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_almacen").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  }).text("0%");
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", }).text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idalmacen_general) {
  limpiar();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-almacen-general").modal("show");

  $.post("../ajax/almacen_general.php?op=mostrar", { 'idalmacen_general': idalmacen_general }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);

    if (e.status == true) {
      // input no usados
      $("#idalmacen_general").val(e.data.idalmacen_general);
      $("#nombre_almacen").val(e.data.nombre_almacen);
      $('#descripcion').val(e.data.descripcion);   
  
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

  var imagen_perfil =''; var btn_imagen_perfil = '';
  
  var ficha_tecnica=''; var btn_ficha_tecnica = '';

  $("#modal-ver-activos-fijos").modal("show");

  $.post("../ajax/almacen_general.php?op=mostrar", { 'idproducto': idproducto }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status == true) {     
    
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

      var retorno_html=`                                                                            
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
    
      $("#datos-activos-fjos").html(retorno_html);

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}


//Función para desactivar registros
function eliminar(idproducto, nombre) {
  //----------------------------

  crud_eliminar_papelera(
    "../ajax/almacen_general.php?op=desactivar",
    "../ajax/almacen_general.php?op=eliminar", 
    idproducto, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla_almacen_resumen.ajax.reload(null, false) },
    false, 
    false, 
    false,
    false
  );

}


init();

$(function () {

  //$('#unidad_medida').on('change', function() { $(this).trigger('blur'); });

  $("#form-almacen-general").validate({
    rules: {
      nombre_almacen: { required: true, minlength:3, maxlength:100},
      descripcion:    { required: true },      
    },
    messages: {
      nombre_almacen: { required: "Por favor ingrese nombre", minlength:"Minimo 3 caracteres", maxlength:"Maximo 100 caracteres" },
      descripcion:    { required: "Campo requerido", },      
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
      guardar_y_editar_almacen(e);
    },
  });

  //$('#unidad_medida').rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


