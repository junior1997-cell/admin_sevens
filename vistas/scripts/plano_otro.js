var tabla_carpeta;
var tabla_plano;

//Función que se ejecuta al inicio
function init() {
  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lPlanoOtro").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  listar_carpeta(localStorage.getItem("nube_idproyecto"));

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════

  $("#guardar_registro_carpeta").on("click", function (e) {  $("#submit-form-carpeta").submit(); });
  $("#guardar_registro_plano").on("click", function (e) { $("#submit-form-planootro").submit(); });
  
}

$("#doc1_i").click(function () {  $("#doc1").trigger("click");  });
$("#doc1").change(function (e) { addImageApplication(e, $("#doc1").attr("id")); });

// Eliminamos el doc 6
function doc1_eliminar() {
  $("#doc1").val("");
  $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc1_nombre").html("");
}

//Función limpiar
function limpiar_carpeta() {
  $("#idcarpeta").val("");
  $("#nombre_carpeta").val("");
  $("#descripcion_carpeta").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function limpiar_plano() {
  $("#idplano_otro").val("");
  $("#nombre").val("");
  $("#descripcion").val("");

  $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc1_nombre").html("");
  $("#doc_old_1").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar_carpeta(nube_idproyecto) {
  tabla_carpeta = $("#tabla-carpeta").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "pdf"],
    ajax: {
      url: "../ajax/plano_otro.php?op=listar_carpeta&nube_idproyecto=" + nube_idproyecto,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: opciones
      if (data[0] != "") {
        $("td", row).eq(0).addClass("text-center");
      }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();
}

//Función Listar
function listar_plano(nombre, id_carpeta) {
  $("#id_carpeta").val(id_carpeta);

  $("#ver-tabla-carpeta").hide();
  $("#ver-tabla-plano").show();
  console.log(nombre, id_carpeta);
  $("#title-1").hide();
  $("#title-2").show();

  tabla_plano = $("#tabla-planos-otros").dataTable({
    responsive: true,
    lengthMenu: [ [5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"], ], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "pdf"],
    ajax: {
      url: "../ajax/plano_otro.php?op=listar_plano&id_carpeta=" + id_carpeta,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: opciones
      if (data[0] != "") {
        $("td", row).eq(0).addClass("text-center");
      }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar
function guardar_y_editar_carpeta(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-carpeta")[0]);

  $.ajax({
    url: "../ajax/plano_otro.php?op=guardar_y_editar_carpeta",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  //console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Carpeta guardado correctamente", "success");
          tabla_carpeta.ajax.reload(null, false);
          limpiar_carpeta();
          $("#modal-agregar-carpeta").modal("hide");
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); } 
      $("#guardar_registro_carpeta").html('Guardar Cambios').removeClass('disabled');
      
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_carpeta").css({"width": percentComplete+'%'});
          $("#barra_progress_carpeta").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_carpeta").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_carpeta").css({ width: "0%",  });
      $("#barra_progress_carpeta").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_carpeta").css({ width: "0%", });
      $("#barra_progress_carpeta").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function guardar_y_editar_plano(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-plano-otro")[0]);

  $.ajax({
    url: "../ajax/plano_otro.php?op=guardar_y_editar_plano",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  //console.log(e);  
        if (e.status == true) {
          Swal.fire("Correcto!", "Documento guardado correctamente", "success");
          tabla_plano.ajax.reload(null, false);
          limpiar_plano();
          $("#modal-agregar-planootros").modal("hide");
        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); } 
      $("#guardar_registro_plano").html('Guardar Cambios').removeClass('disabled');
      
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_plano").css({"width": percentComplete+'%'});
          $("#barra_progress_plano").text(percentComplete.toFixed(2)+" %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_plano").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress_plano").css({ width: "0%",  });
      $("#barra_progress_plano").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress_plano").css({ width: "0%", });
      $("#barra_progress_plano").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// mostramos los datos para editar
function mostrar_carpeta(idplano_otro) {
  limpiar_carpeta();
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-carpeta").modal("show");

  $.post("../ajax/plano_otro.php?op=mostrar_carpeta", { idplano_otro: idplano_otro }, function (e, status) {
    e = JSON.parse(e); //console.log(e);    
    if (e.status == true) {
      $("#nombre_carpeta").val(e.data.nombre);
      $("#descripcion_carpeta").val(e.data.descripcion);      
      $("#idcarpeta").val(e.data.idcarpeta);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }    

  }).fail( function(e) { ver_errores(e); } );
}

// mostramos los datos para editar
function mostrar_plano(idplano_otro) {
  limpiar_plano();
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-planootros").modal("show");

  $.post("../ajax/plano_otro.php?op=mostrar_plano", { idplano_otro: idplano_otro }, function (e, status) {
    e = JSON.parse(e); //console.log(e);    

    if (e.status == true) {
      $("#nombre").val(e.data.nombre);
      $("#descripcion").val(e.data.descripcion);      
      $("#idplano_otro").val(e.data.idplano_otro);

      if (e.data.doc != "") {

        $("#doc_old_1").val(e.data.doc);
        $("#doc1_nombre").html(e.data.nombre);
        $("#doc1_ver").html(doc_view_extencion(e.data.doc, 'plano_otro', 'archivos', '100%', '206'));
        
      } else {
        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
        $("#doc1_nombre").html("");
        $("#doc_old_1").val("");
      }

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function desactivar_carpeta(idplano_otro) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  esta carpeta?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/plano_otro.php?op=desactivar_carpeta", { idplano_otro: idplano_otro }, function (e) {
        Swal.fire("Desactivado!", "Tu Documento ha sido desactivado.", "success");

        tabla_carpeta.ajax.reload(null, false);
      }).fail( function(e) { ver_errores(e); } );
    }
  });
}

//Función para activar registros
function activar_carpeta(idplano_otro) {
  Swal.fire({
    title: "¿Está Seguro de  Activar esta carpeta?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/plano_otro.php?op=activar_carpeta", { idplano_otro: idplano_otro }, function (e) {
        Swal.fire("Activado!", "Tu Documento ha sido activado.", "success");

        tabla_carpeta.ajax.reload(null, false);
      }).fail( function(e) { ver_errores(e); } );
    }
  });
}

//Función para desactivar registros
function eliminar_carpeta(idplano_otro) {
    
  Swal.fire({

    title: "!Elija una opción¡",
    html: "En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!",
    icon: "warning",
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonColor: "#17a2b8",
    denyButtonColor: "#d33",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `<i class="fas fa-times"></i> Papelera`,
    denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,

  }).then((result) => {

    if (result.isConfirmed) {

      $.post("../ajax/plano_otro.php?op=desactivar_carpeta", { idplano_otro: idplano_otro }, function (e) {
        Swal.fire("Desactivado!", "Tu Documento ha sido desactivado.", "success");

        tabla_carpeta.ajax.reload(null, false);
      }).fail( function(e) { ver_errores(e); } );

    }else if (result.isDenied) {

      $.post("../ajax/plano_otro.php?op=eliminar_carpeta", { idplano_otro: idplano_otro }, function (e) {
        Swal.fire("Eliminado!", "Tu Documento ha sido Eliminado.", "success");

        tabla_carpeta.ajax.reload(null, false);
      }).fail( function(e) { ver_errores(e); } );

    }

  });
}

//Función para desactivar registros
function desactivar_plano(idplano_otro) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  este Documento?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/plano_otro.php?op=desactivar", { idplano_otro: idplano_otro }, function (e) {
        Swal.fire("Desactivado!", "Tu Documento ha sido desactivado.", "success");

        tabla_plano.ajax.reload(null, false);
      }).fail( function(e) { ver_errores(e); } );
    }
  });
}

//Función para activar registros
function activar_plano(idplano_otro) {
  Swal.fire({
    title: "¿Está Seguro de  Activar este Documento?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/plano_otro.php?op=activar", { idplano_otro: idplano_otro }, function (e) {
        Swal.fire("Activado!", "Tu Documento ha sido activado.", "success");

        tabla_plano.ajax.reload(null, false);
      }).fail( function(e) { ver_errores(e); } );
    }
  });
}

//Función para desactivar registros
function eliminar_plano(idplano_otro) {

  Swal.fire({

    title: "!Elija una opción¡",
    html: "En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!",
    icon: "warning",
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonColor: "#17a2b8",
    denyButtonColor: "#d33",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `<i class="fas fa-times"></i> Papelera`,
    denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,

  }).then((result) => {

    if (result.isConfirmed) {

      $.post("../ajax/plano_otro.php?op=desactivar_plano", { idplano_otro: idplano_otro }, function (e) {
        Swal.fire("Desactivado!", "Tu Documento ha sido desactivado.", "success");

        tabla_plano.ajax.reload(null, false);
      }).fail( function(e) { ver_errores(e); } );

    }else if (result.isDenied) {

      $.post("../ajax/plano_otro.php?op=eliminar_plano", { idplano_otro: idplano_otro }, function (e) {
        Swal.fire("Desactivado!", "Tu Documento ha sido desactivado.", "success");

        tabla_plano.ajax.reload(null, false);
      }).fail( function(e) { ver_errores(e); } );

    }

  });
}

function ver_modal_docs(nombre, descripcion, doc) {
  // console.log(nombre, descripcion, doc);
  $("#modal-ver-docs").modal("show");

  if (doc == "") {
    $("#verdoc1").html('<img src="../dist/svg/doc_uploads_no.svg" alt="" height="206" >');

    $("#verdoc1_nombre").html(
      '<div class="col-md-12 text-left"><b>Nombre: <br> </b>' +
        nombre +
        '</div> <div class="col-md-12 mt-2 mb-2 text-left"><b>Descripcion: <br> </b>' +
        descripcion +
        '</div> <div class="col-md-12 row mt-2"> <div class="col-md-6"> <a class="btn btn-warning  btn-block disabled" href="#"   onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-md-6"> <a class="btn btn-info  btn-block disabled" href="#"  onclick="no_pdf();"style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>'
    );
  } else {
    $("#verdoc1").html( doc_view_extencion(doc, 'plano_otro', 'archivos', '100%', '206') );

    $("#verdoc1_nombre").html(
      '<div class="col-md-12 text-left"><b>Nombre: <br> </b>' +
        nombre +
        '</div> <div class="col-md-12 mt-2 mb-2 text-left"><b>Descripcion: <br> </b>' +
        descripcion +
        '</div> <div class="borde-arriba-naranja mb-2" > </div> <div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/docs/plano_otro/archivos/' +
        doc +
        '"  download="' +
        nombre +
        '" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-md-6 "> <a  class="btn btn-info  btn-block" href="../dist/docs/plano_otro/archivos/' +
        doc +
        '"  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div> '
    );
  }

  $(".tooltip").removeClass("show").addClass("hidde");
}

init();

// validacion fomr 2
$(function () {

  $("#form-carpeta").validate({
    rules: {
      nombre_carpeta: { required: true },
    },

    messages: {
      nombre_carpeta: {
        required: "Este campo es requerido",
      },
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
      guardar_y_editar_carpeta(e);
    },
  });

  $("#form-plano-otro").validate({
    rules: {
      nombre: { required: true },
    },

    messages: {
      nombre: {
        required: "Este campo es requerido",
      },
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
      guardar_y_editar_plano(e);
    },
  });
});

function regresar() {
  $("#ver-tabla-carpeta").show();
  $("#ver-tabla-plano").hide();

  $("#title-1").show();
  $("#title-2").hide();
}
