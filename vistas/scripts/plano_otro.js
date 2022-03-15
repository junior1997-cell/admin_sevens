var tabla_carpeta;
var tabla_plano;

//Función que se ejecuta al inicio
function init() {
  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lPlanoOtro").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

  listar_carpeta(localStorage.getItem("nube_idproyecto"));

  $("#guardar_registro").on("click", function (e) {
    $("#submit-form-carpeta").submit();
  });

  $("#guardar_registro_2").on("click", function (e) {
    $("#submit-form-planootro").submit();
  });

  $("#doc1_i").click(function () {
    $("#doc1").trigger("click");
  });

  $("#doc1").change(function (e) {
    addDocs(e, $("#doc1").attr("id"));
  });
}

/* PREVISUALIZAR LAS DOCS */
function addDocs(e, id) {
  $("#" + id + "_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');
  // console.log(id);

  var file = e.target.files[0],
    imageType = false;

  if (e.target.files[0]) {
    // console.log(extrae_extencion(file.name));
    var sizeByte = file.size;

    var sizekiloBytes = parseInt(sizeByte / 1024);

    var sizemegaBytes = sizeByte / 10000;
    // alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

    if (imageType) {
      // return;
      Swal.fire({
        position: "top-end",
        icon: "error",
        title: "Este tipo de ARCHIVO no esta permitido elija formato: mi-documento.xlsx",
        showConfirmButton: false,
        timer: 1500,
      });

      $("#" + id + "_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

      $("#" + id + "_i").attr("src", "../dist/img/default/img_defecto.png");
    } else {
      if (sizekiloBytes <= 262144) {
        var reader = new FileReader();

        reader.onload = fileOnload;

        function fileOnload(e) {
          var result = e.target.result;

          // cargamos la imagen adecuada par el archivo
          if (extrae_extencion(file.name) == "xls") {
            $("#" + id + "_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
          } else {
            if (extrae_extencion(file.name) == "xlsx") {
              $("#" + id + "_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
            } else {
              if (extrae_extencion(file.name) == "csv") {
                $("#" + id + "_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
              } else {
                if (extrae_extencion(file.name) == "xlsm") {
                  $("#" + id + "_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                } else {
                  if (extrae_extencion(file.name) == "pdf") {
                    // $("#"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                    $("#" + id + "_ver").html('<iframe src="' + result + '" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
                  } else {
                    if (extrae_extencion(file.name) == "dwg") {
                      $("#" + id + "_ver").html('<img src="../dist/svg/dwg.svg" alt="" width="50%" >');
                    } else {
                      if (extrae_extencion(file.name) == "zip" || extrae_extencion(file.name) == "rar" || extrae_extencion(file.name) == "iso") {
                        $("#" + id + "_ver").html('<img src="../dist/img/default/zip.png" alt="" width="50%" >');
                      } else {
                        if (
                          extrae_extencion(file.name) == "jpeg" ||
                          extrae_extencion(file.name) == "jpg" ||
                          extrae_extencion(file.name) == "jpe" ||
                          extrae_extencion(file.name) == "jfif" ||
                          extrae_extencion(file.name) == "gif" ||
                          extrae_extencion(file.name) == "png" ||
                          extrae_extencion(file.name) == "tiff" ||
                          extrae_extencion(file.name) == "tif" ||
                          extrae_extencion(file.name) == "webp" ||
                          extrae_extencion(file.name) == "bmp"
                        ) {
                          $("#" + id + "_ver").html('<img src="' + result + '" alt="" width="50%" >');
                        } else {
                          if (
                            extrae_extencion(file.name) == "docx" ||
                            extrae_extencion(file.name) == "docm" ||
                            extrae_extencion(file.name) == "dotx" ||
                            extrae_extencion(file.name) == "dotm" ||
                            extrae_extencion(file.name) == "doc" ||
                            extrae_extencion(file.name) == "dot"
                          ) {
                            $("#" + id + "_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
                          } else {
                            $("#" + id + "_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }

          $("#" + id + "_nombre").html(
            "" +
              '<div class="row">' +
              '<div class="col-md-12">' +
              "<i>" +
              file.name +
              "</i>" +
              "</div>" +
              '<div class="col-md-12">' +
              '<button  class="btn btn-danger  btn-block" onclick="' +
              id +
              '_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>' +
              "</div>" +
              "</div>" +
              ""
          );

          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "El documento: " + file.name.toUpperCase() + " es aceptado.",
            showConfirmButton: false,
            timer: 1500,
          });
        }

        reader.readAsDataURL(file);
      } else {
        Swal.fire({
          position: "top-end",
          icon: "warning",
          title: "El documento: " + file.name.toUpperCase() + " es muy pesado. Tamaño máximo 150mb",
          showConfirmButton: false,
          timer: 1500,
        });

        $("#" + id + "_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#" + id + "_i").attr("src", "../dist/img/default/img_error.png");

        $("#" + id).val("");
      }
    }
  } else {
    Swal.fire({
      position: "top-end",
      icon: "error",
      title: "Seleccione un documento",
      showConfirmButton: false,
      timer: 1500,
    });

    $("#" + id + "_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

    $("#" + id + "_nombre").html("");
  }
}

// Eliminamos el doc 6
function doc1_eliminar() {
  $("#doc1").val("");

  $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

  $("#doc1_nombre").html("");
}

//Función limpiar
function limpiar() {
  $("#nombre").val("");
  $("#descripcion").val("");
  $("#idplano_otro").val("");

  $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc1_nombre").html("");
  $("#doc_old_1").val("");
}

//Función Listar
function listar_carpeta(nube_idproyecto) {
  tabla_carpeta = $("#tabla-carpeta")
    .dataTable({
      responsive: true,
      lengthMenu: [
        [5, 10, 25, 75, 100, 200, -1],
        [5, 10, 25, 75, 100, 200, "Todos"],
      ], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
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
        lengthMenu: "Mostrar : _MENU_ registros",
        buttons: {
          copyTitle: "Tabla Copiada",
          copySuccess: {
            _: "%d líneas copiadas",
            1: "1 línea copiada",
          },
        },
      },
      bDestroy: true,
      iDisplayLength: 10, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    })
    .DataTable();
}

//Función Listar
function listar_plano(nombre, id_carpeta) {
  $("#id_carpeta").val(id_carpeta);

  $("#ver-tabla-carpeta").hide();
  $("#ver-tabla-plano").show();
  console.log(nombre, id_carpeta);
  $("#title-1").hide();
  $("#title-2").show();

  tabla_plano = $("#tabla-planos-otros")
    .dataTable({
      responsive: true,
      lengthMenu: [
        [5, 10, 25, 75, 100, 200, -1],
        [5, 10, 25, 75, 100, 200, "Todos"],
      ], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
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
        lengthMenu: "Mostrar : _MENU_ registros",
        buttons: {
          copyTitle: "Tabla Copiada",
          copySuccess: {
            _: "%d líneas copiadas",
            1: "1 línea copiada",
          },
        },
      },
      bDestroy: true,
      iDisplayLength: 10, //Paginación
      order: [[0, "asc"]], //Ordenar (columna,orden)
    })
    .DataTable();
}
//Función para guardar o editar

function guardaryeditar_carpeta(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-carpeta")[0]);

  $.ajax({
    url: "../ajax/plano_otro.php?op=guardaryeditar_carpeta",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      if (datos == "ok") {
        Swal.fire("Correcto!", "Carpeta guardado correctamente", "success");

        tabla_carpeta.ajax.reload();

        limpiar();

        $("#modal-agregar-carpeta").modal("hide");
      } else {
        Swal.fire("Error!", datos, "error");
      }
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener(
        "progress",
        function (evt) {
          if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            /*console.log(percentComplete + '%');*/
            $("#barra_progress").css({ width: percentComplete + "%" });

            $("#barra_progress").text(percentComplete.toFixed(2) + " %");

            if (percentComplete === 100) {
              l_m();
            }
          }
        },
        false
      );
      return xhr;
    },
  });
}

function guardaryeditar_plano(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-plano-otro")[0]);

  $.ajax({
    url: "../ajax/plano_otro.php?op=guardaryeditar_plano",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      if (datos == "ok") {
        Swal.fire("Correcto!", "Documento guardado correctamente", "success");

        tabla_plano.ajax.reload();

        limpiar();

        $("#modal-agregar-planootros").modal("hide");
      } else {
        Swal.fire("Error!", datos, "error");
      }
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener(
        "progress",
        function (evt) {
          if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            /*console.log(percentComplete + '%');*/
            $("#barra_progress2").css({ width: percentComplete + "%" });

            $("#barra_progress2").text(percentComplete.toFixed(2) + " %");

            if (percentComplete === 100) {
              l_m();
            }
          }
        },
        false
      );
      return xhr;
    },
  });
}

function l_m() {
  // limpiar();
  $("#barra_progress").css({ width: "0%" });

  $("#barra_progress").text("0%");

  $("#barra_progress2").css({ width: "0%" });

  $("#barra_progress2").text("0%");
}


// mostramos los datos para editar
function mostrar_carpeta(idplano_otro) {
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-carpeta").modal("show");

  $.post("../ajax/plano_otro.php?op=mostrar_carpeta", { idplano_otro: idplano_otro }, function (data, status) {
    data = JSON.parse(data); //console.log(data);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#nombre_carpeta").val(data.nombre);
    $("#descripcion_carpeta").val(data.descripcion);
    $("#idproyecto").val(data.idproyecto);
    $("#idcarpeta").val(data.idcarpeta);
  });
}

// mostramos los datos para editar
function mostrar_plano(idplano_otro) {
  limpiar();
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-planootros").modal("show");

  $.post("../ajax/plano_otro.php?op=mostrar_plano", { idplano_otro: idplano_otro }, function (data, status) {
    data = JSON.parse(data); //console.log(data);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#nombre").val(data.nombre);
    $("#descripcion").val(data.descripcion);
    $("#idproyecto").val(data.idproyecto);
    $("#idplano_otro").val(data.idplano_otro);

    if (data.doc != "") {
      $("#doc_old_1").val(data.doc);

      $("#doc1_nombre").html(data.nombre);

      // cargamos la imagen adecuada par el archivo
      if (extrae_extencion(data.doc) == "xls") {
        $("#doc1_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
      } else {
        if (extrae_extencion(data.doc) == "xlsx") {
          $("#doc1_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
        } else {
          if (extrae_extencion(data.doc) == "csv") {
            $("#doc1_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
          } else {
            if (extrae_extencion(data.doc) == "xlsm") {
              $("#doc1_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
            } else {
              if (extrae_extencion(data.doc) == "pdf") {
                $("#doc1_ver").html('<iframe src="../dist/docs/plano_otro/archivos/' + data.doc + '" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');
              } else {
                if (extrae_extencion(data.doc) == "dwg") {
                  $("#doc1_ver").html('<img src="../dist/svg/dwg.svg" alt="" width="50%" >');
                } else {
                  if (extrae_extencion(data.doc) == "zip" || extrae_extencion(data.doc) == "rar" || extrae_extencion(data.doc) == "iso") {
                    $("#doc1_ver").html('<img src="../dist/img/default/zip.png" alt="" width="50%" >');
                  } else {
                    if (
                      extrae_extencion(data.doc) == "jpeg" ||
                      extrae_extencion(data.doc) == "jpg" ||
                      extrae_extencion(data.doc) == "jpe" ||
                      extrae_extencion(data.doc) == "jfif" ||
                      extrae_extencion(data.doc) == "gif" ||
                      extrae_extencion(data.doc) == "png" ||
                      extrae_extencion(data.doc) == "tiff" ||
                      extrae_extencion(data.doc) == "tif" ||
                      extrae_extencion(data.doc) == "webp" ||
                      extrae_extencion(data.doc) == "bmp"
                    ) {
                      $("#doc1_ver").html('<img src="../dist/docs/plano_otro/archivos/' + data.doc + '" alt="" width="50%" >');
                    } else {
                      if (
                        extrae_extencion(data.doc) == "docx" ||
                        extrae_extencion(data.doc) == "docm" ||
                        extrae_extencion(data.doc) == "dotx" ||
                        extrae_extencion(data.doc) == "dotm" ||
                        extrae_extencion(data.doc) == "doc" ||
                        extrae_extencion(data.doc) == "dot"
                      ) {
                        $("#doc1_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
                      } else {
                        $("#doc1_ver").html('<img src="../dist/svg/doc_default.svg" alt="" width="50%" >');
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    } else {
      $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

      $("#doc1_nombre").html("");

      $("#doc_old_1").val("");
    }
  });
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

        tabla_carpeta.ajax.reload();
      });
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

        tabla_carpeta.ajax.reload();
      });
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

        tabla_carpeta.ajax.reload();
      });

    }else if (result.isDenied) {

      $.post("../ajax/plano_otro.php?op=eliminar_carpeta", { idplano_otro: idplano_otro }, function (e) {
        Swal.fire("Eliminado!", "Tu Documento ha sido Eliminado.", "success");

        tabla_carpeta.ajax.reload();
      });

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

        tabla_plano.ajax.reload();
      });
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

        tabla_plano.ajax.reload();
      });
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

        tabla_plano.ajax.reload();
      });

    }else if (result.isDenied) {

      $.post("../ajax/plano_otro.php?op=eliminar_plano", { idplano_otro: idplano_otro }, function (e) {
        Swal.fire("Desactivado!", "Tu Documento ha sido desactivado.", "success");

        tabla_plano.ajax.reload();
      });

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
    // cargamos la imagen adecuada par el archivo
    if (extrae_extencion(doc) == "xls") {
      $("#verdoc1").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

      $("#verdoc1_nombre").html(
        '<div class="col-md-12 text-left"><b>Nombre: <br> </b>' +
          nombre +
          '.xls</div> <div class="col-md-12 mt-2 mb-2 text-left"><b>Descripcion: <br> </b>' +
          descripcion +
          '</div> <div class="borde-arriba-naranja mb-2" > </div> <div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/docs/plano_otro/archivos/' +
          doc +
          '"  download="' +
          nombre +
          '" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" href="#"  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>'
      );
    } else {
      if (extrae_extencion(doc) == "xlsx") {
        $("#verdoc1").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

        $("#verdoc1_nombre").html(
          '<div class="col-md-12 text-left"><b>Nombre: <br> </b>' +
            nombre +
            '.xlsx</div> <div class="col-md-12 mt-2 mb-2 text-left"><b>Descripcion: <br> </b>' +
            descripcion +
            '</div> <div class="borde-arriba-naranja mb-2" > </div> <div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/docs/plano_otro/archivos/' +
            doc +
            '"  download="' +
            nombre +
            '" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" href="#"  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>'
        );
      } else {
        if (extrae_extencion(doc) == "csv") {
          $("#verdoc1").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

          $("#verdoc1_nombre").html(
            '<div class="col-md-12 text-left"><b>Nombre: <br> </b>' +
              nombre +
              '.csv</div> <div class="col-md-12 mt-2 mb-2 text-left"><b>Descripcion: <br> </b>' +
              descripcion +
              '</div> <div class="borde-arriba-naranja mb-2" > </div> <div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/docs/plano_otro/archivos/' +
              doc +
              '"  download="' +
              nombre +
              '" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" href="#"  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div> '
          );
        } else {
          if (extrae_extencion(doc) == "xlsm") {
            $("#verdoc1").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

            $("#verdoc1_nombre").html(
              '<div class="col-md-12 text-left"><b>Nombre: <br> </b>' +
                nombre +
                '.xlsm</div> <div class="col-md-12 mt-2 mb-2 text-left"><b>Descripcion: <br> </b>' +
                descripcion +
                '</div> <div class="borde-arriba-naranja mb-2" > </div> <div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/docs/plano_otro/archivos/' +
                doc +
                '"  download="' +
                nombre +
                '" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" href="#"  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div> '
            );
          } else {
            if (extrae_extencion(doc) == "pdf") {
              $("#verdoc1").html('<iframe src="../dist/docs/plano_otro/archivos/' + doc + '" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

              $("#verdoc1_nombre").html(
                '<div class="col-md-12 text-left"><b>Nombre: <br> </b>' +
                  nombre +
                  '.pdf</div> <div class="col-md-12 mt-2 mb-2 text-left"><b>Descripcion: <br> </b>' +
                  descripcion +
                  '</div> <div class="borde-arriba-naranja mb-2" > </div> <div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/docs/plano_otro/archivos/' +
                  doc +
                  '"  download="' +
                  nombre +
                  '" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-md-6 "> <a  class="btn btn-info  btn-block" href="../dist/docs/plano_otro/archivos/' +
                  doc +
                  '"  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div>'
              );
            } else {
              if (extrae_extencion(doc) == "dwg") {
                $("#verdoc1").html('<img src="../dist/svg/dwg.svg" alt="" width="50%" >');

                $("#verdoc1_nombre").html(
                  '<div class="col-md-12 text-left"><b>Nombre: <br> </b>' +
                    nombre +
                    '.dwg</div> <div class="col-md-12 mt-2 mb-2 text-left"><b>Descripcion: <br> </b>' +
                    descripcion +
                    '</div> <div class="borde-arriba-naranja mb-2" > </div> <div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/docs/plano_otro/archivos/' +
                    doc +
                    '"  download="' +
                    nombre +
                    '" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" href="#"  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div> '
                );
              } else {
                if (extrae_extencion(doc) == "zip" || extrae_extencion(doc) == "rar" || extrae_extencion(doc) == "iso") {
                  $("#verdoc1").html('<img src="../dist/img/default/zip.png" alt="" width="50%" >');

                  $("#verdoc1_nombre").html(
                    '<div class="col-md-12 text-left"><b>Nombre: <br> </b>' +
                      nombre +
                      "." +
                      extrae_extencion(doc) +
                      '</div> <div class="col-md-12 mt-2 mb-2 text-left"><b>Descripcion: <br> </b>' +
                      descripcion +
                      '</div> <div class="borde-arriba-naranja mb-2" > </div> <div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/docs/plano_otro/archivos/' +
                      doc +
                      '"  download="' +
                      nombre +
                      '" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" href="#"  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div> '
                  );
                } else {
                  if (
                    extrae_extencion(doc) == "jpeg" ||
                    extrae_extencion(doc) == "jpg" ||
                    extrae_extencion(doc) == "jpe" ||
                    extrae_extencion(doc) == "jfif" ||
                    extrae_extencion(doc) == "gif" ||
                    extrae_extencion(doc) == "png" ||
                    extrae_extencion(doc) == "tiff" ||
                    extrae_extencion(doc) == "tif" ||
                    extrae_extencion(doc) == "webp" ||
                    extrae_extencion(doc) == "bmp"
                  ) {
                    $("#verdoc1").html('<img src="../dist/docs/plano_otro/archivos/' + doc + '" alt="" width="50%" >');

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
                  } else {
                    if (extrae_extencion(doc) == "docx" || extrae_extencion(doc) == "docm" || extrae_extencion(doc) == "dotx" || extrae_extencion(doc) == "dotm" || extrae_extencion(doc) == "doc" || extrae_extencion(doc) == "dot") {
                      $("#verdoc1").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');

                      $("#verdoc1_nombre").html(
                        '<div class="col-md-12 text-left"><b>Nombre: <br> </b>' +
                          nombre +
                          "." +
                          extrae_extencion(doc) +
                          '</div> <div class="col-md-12 mt-2 mb-2 text-left"><b>Descripcion: <br> </b>' +
                          descripcion +
                          '</div> <div class="borde-arriba-naranja mb-2" > </div> <div class="col-md-12 row mt-2"> <div class="col-md-6 "> <a  class="btn btn-warning  btn-block" href="../dist/docs/plano_otro/archivos/' +
                          doc +
                          '"  download="' +
                          nombre +
                          '" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" > <i class="fas fa-download"></i> </a> </div> <div class="col-md-6 "> <a  class="btn btn-info  btn-block disabled" href="#"  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" > Ver completo <i class="fas fa-expand"></i> </a> </div> </div> '
                      );
                    } else {
                      $("#verdoc1").html('<img src="../dist/svg/doc_default.svg" alt="" width="50%" >');

                      $("#verdoc1_nombre").html(
                        '<div class="col-md-12 text-left"><b>Nombre: <br> </b>' +
                          nombre +
                          "." +
                          extrae_extencion(doc) +
                          "</div>" +
                          '<div class="col-md-12 mt-2 mb-2 text-left"><b>Descripcion: <br> </b>' +
                          descripcion +
                          "</div>" +
                          '<div class="borde-arriba-naranja mb-2" > </div>' +
                          '<div class="col-md-12 row mt-2">' +
                          '<div class="col-md-6 ">' +
                          '<a  class="btn btn-warning  btn-block" href="../dist/docs/plano_otro/archivos/' +
                          doc +
                          '"  download="' +
                          nombre +
                          '" onclick="dowload_pdf();" style="padding:0px 6px 0px 12px !important;" type="button" >' +
                          '<i class="fas fa-download"></i>' +
                          "</a>" +
                          "</div>" +
                          '<div class="col-md-6 ">' +
                          '<a  class="btn btn-info  btn-block disabled" href="#"  target="_blank" style="padding:0px 12px 0px 12px !important;" type="button" >' +
                          'Ver completo <i class="fas fa-expand"></i>' +
                          "</a>" +
                          "</div>" +
                          "</div>"
                      );
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    // $('#verdoc1').html('<embed src="../dist/pdf/'+doc+'" type="application/pdf" width="100%" height="200px" />');
  }

  $(".tooltip").hide();
}

init();

// validacion fomr 2
$(function () {
  $.validator.setDefaults({
    submitHandler: function (e) {
      guardaryeditar_carpeta(e);
    },
  });

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
      $(element).addClass("is-invalid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
    },
  });
});

// validacion form
$(function () {
  $.validator.setDefaults({
    submitHandler: function (e) {
      guardaryeditar_plano(e);
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
      $(element).addClass("is-invalid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
    },
  });
});

// EXTRAEMOS LA EXTENCIONS
function extrae_extencion(filename) {
  // console.log(filename.split('.').pop());
  return filename.split(".").pop();
}

// recargar un doc para ver
function re_visualizacion() {
  $("#doc1_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

  pdffile = document.getElementById("doc1").files[0];

  antiguopdf = $("#doc_old_1").val();

  if (pdffile === undefined) {
    var dr = antiguopdf;

    if (dr == "") {
      Swal.fire({
        position: "top-end",
        icon: "error",
        title: "Seleccione un documento",
        showConfirmButton: false,
        timer: 1500,
      });

      $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

      $("#doc1_nombre").html("");
    } else {
      // cargamos la imagen adecuada par el archivo
      if (extrae_extencion(dr) == "xls") {
        $("#doc1_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

        toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
      } else {
        if (extrae_extencion(dr) == "xlsx") {
          $("#doc1_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

          toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
        } else {
          if (extrae_extencion(dr) == "csv") {
            $("#doc1_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

            toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
          } else {
            if (extrae_extencion(dr) == "xlsm") {
              $("#doc1_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

              toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
            } else {
              if (extrae_extencion(dr) == "pdf") {
                $("#doc1_ver").html('<iframe src="../dist/docs/plano_otro/archivos/' + dr + '" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

                toastr.success("Documento vizualizado correctamente!!!");
              } else {
                if (extrae_extencion(dr) == "dwg") {
                  $("#doc1_ver").html('<img src="../dist/svg/dwg.svg" alt="" width="50%" >');

                  toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
                } else {
                  if (extrae_extencion(dr) == "zip" || extrae_extencion(dr) == "rar" || extrae_extencion(dr) == "iso") {
                    $("#doc1_ver").html('<img src="../dist/img/default/zip.png" alt="" width="50%" >');

                    toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
                  } else {
                    if (
                      extrae_extencion(dr) == "jpeg" ||
                      extrae_extencion(dr) == "jpg" ||
                      extrae_extencion(dr) == "jpe" ||
                      extrae_extencion(dr) == "jfif" ||
                      extrae_extencion(dr) == "gif" ||
                      extrae_extencion(dr) == "png" ||
                      extrae_extencion(dr) == "tiff" ||
                      extrae_extencion(dr) == "tif" ||
                      extrae_extencion(dr) == "webp" ||
                      extrae_extencion(dr) == "bmp"
                    ) {
                      $("#doc1_ver").html('<img src="../dist/docs/plano_otro/archivos/' + dr + '" alt="" width="50%" >');

                      toastr.success("Documento vizualizado correctamente!!!");
                    } else {
                      if (extrae_extencion(dr) == "docx" || extrae_extencion(dr) == "docm" || extrae_extencion(dr) == "dotx" || extrae_extencion(dr) == "dotm" || extrae_extencion(dr) == "doc" || extrae_extencion(dr) == "dot") {
                        $("#doc1_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');

                        toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
                      } else {
                        $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');

                        toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    // console.log('hola'+dr);
  } else {
    pdffile_url = URL.createObjectURL(pdffile);

    // cargamos la imagen adecuada par el archivo
    if (extrae_extencion(pdffile.name) == "xls") {
      $("#doc1_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');

      toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
    } else {
      if (extrae_extencion(pdffile.name) == "xlsx") {
        $("#doc1_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');

        toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
      } else {
        if (extrae_extencion(pdffile.name) == "csv") {
          $("#doc1_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');

          toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
        } else {
          if (extrae_extencion(pdffile.name) == "xlsm") {
            $("#doc1_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');

            toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
          } else {
            if (extrae_extencion(pdffile.name) == "pdf") {
              $("#doc1_ver").html('<iframe src="' + pdffile_url + '" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

              toastr.success("Documento vizualizado correctamente!!!");
            } else {
              if (extrae_extencion(pdffile.name) == "dwg") {
                $("#doc1_ver").html('<img src="../dist/svg/dwg.svg" alt="" width="50%" >');

                toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
              } else {
                if (extrae_extencion(pdffile.name) == "zip" || extrae_extencion(pdffile.name) == "rar" || extrae_extencion(pdffile.name) == "iso") {
                  $("#doc1_ver").html('<img src="../dist/img/default/zip.png" alt="" width="50%" >');

                  toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
                } else {
                  if (
                    extrae_extencion(pdffile.name) == "jpeg" ||
                    extrae_extencion(pdffile.name) == "jpg" ||
                    extrae_extencion(pdffile.name) == "jpe" ||
                    extrae_extencion(pdffile.name) == "jfif" ||
                    extrae_extencion(pdffile.name) == "gif" ||
                    extrae_extencion(pdffile.name) == "png" ||
                    extrae_extencion(pdffile.name) == "tiff" ||
                    extrae_extencion(pdffile.name) == "tif" ||
                    extrae_extencion(pdffile.name) == "webp" ||
                    extrae_extencion(pdffile.name) == "bmp"
                  ) {
                    $("#doc1_ver").html('<img src="' + pdffile_url + '" alt="" width="50%" >');

                    toastr.success("Documento vizualizado correctamente!!!");
                  } else {
                    if (
                      extrae_extencion(pdffile.name) == "docx" ||
                      extrae_extencion(pdffile.name) == "docm" ||
                      extrae_extencion(pdffile.name) == "dotx" ||
                      extrae_extencion(pdffile.name) == "dotm" ||
                      extrae_extencion(pdffile.name) == "doc" ||
                      extrae_extencion(pdffile.name) == "dot"
                    ) {
                      $("#doc1_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');

                      toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
                    } else {
                      $("#doc1_ver").html('<img src="../dist/svg/doc_default.svg" alt="" width="50%" >');

                      toastr.error("Documento NO TIENE PREVIZUALIZACION!!!");
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    console.log(pdffile);
  }
}

function dowload_pdf() {
  toastr.success("El documento se descargara en breve!!");
}

function regresar() {
  $("#ver-tabla-carpeta").show();
  $("#ver-tabla-plano").hide();

  $("#title-1").show();
  $("#title-2").hide();
}
