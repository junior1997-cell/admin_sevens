var tabla;

//Función que se ejecuta al inicio
function init() {
  listar();
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  $("#lActivosfijos").addClass("active");

  //$("#lAllMateriales").addClass("active");

  $("#guardar_registro").on("click", function (e) {
    $("#submit-form-activos-fijos").submit();
  });

  $("#foto1_i").click(function () {
    $("#foto1").trigger("click");
  });
  $("#foto1").change(function (e) {
    addImage(e, $("#foto1").attr("id"));
  });
  //ficha tecnica
  $("#foto2_i").click(function () {
    $("#foto2").trigger("click");
  });
  $("#foto2").change(function (e) {
    addficha(e, $("#foto2").attr("id"));
  });

  //Mostramos colores
  $.post("../ajax/color.php?op=selectcolor", function (r) {
    $("#color").html(r);
  });
  //Mostramos unidades
  $.post("../ajax/unidades_m.php?op=selectUnidad", function (r) {
    $("#unid_medida").html(r);
  });

  //Initialize Select2 color
  $("#color").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar color",
    allowClear: true,
  });
  //Initialize Select2 unidad
  $("#unid_medida").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar una unidad",
    allowClear: true,
  });
  //============unidad y color================
  $("#unid_medida").val(4).trigger("change");
  $("#color").val(1).trigger("change");

  // Formato para telefono
  $("[data-mask]").inputmask();
}

/* PREVISUALIZAR LAS IMAGENES */
function addImage(e, id) {
  // colocamos cargando hasta que se vizualice
  $("#" + id + "_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

  console.log(id);

  var file = e.target.files[0],
    imageType = /image.*/;

  if (e.target.files[0]) {
    var sizeByte = file.size;

    var sizekiloBytes = parseInt(sizeByte / 1024);

    var sizemegaBytes = sizeByte / 1000000;
    // alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

    if (!file.type.match(imageType)) {
      // return;
      toastr.error("Este tipo de ARCHIVO no esta permitido <br> elija formato: <b>.png .jpeg .jpg .webp etc... </b>");

      $("#" + id + "_i").attr("src", "../dist/img/default/img_defecto_materiales.png");
    } else {
      if (sizekiloBytes <= 10240) {
        var reader = new FileReader();

        reader.onload = fileOnload;

        function fileOnload(e) {
          var result = e.target.result;

          $("#" + id + "_i").attr("src", result);

          $("#" + id + "_nombre").html(
            "" +
              '<div class="row">' +
              '<div class="col-md-4"></div>' +
              '<div class="col-md-4">' +
              "</br>" +
              '<button  class="btn btn-danger  btn-block" onclick="' +
              id +
              '_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>' +
              "</div>" +
              '<div class="col-md-4"></div>' +
              "</div>" +
              ""
          );

          toastr.success("Imagen aceptada.");
        }

        reader.readAsDataURL(file);
      } else {
        toastr.warning("La imagen: " + file.name.toUpperCase() + " es muy pesada. Tamaño máximo 10mb");

        $("#" + id + "_i").attr("src", "../dist/img/default/img_error.png");

        $("#" + id).val("");
      }
    }
  } else {
    toastr.error("Seleccione una Imagen");

    $("#" + id + "_i").attr("src", "../dist/img/default/img_defecto_materiales.png");

    $("#" + id + "_nombre").html("");
  }
}

function foto1_eliminar() {
  $("#foto1").val("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto_materiales.png");

  $("#foto1_nombre").html("");
}
/* PREVISUALIZAR LOS PDF */
function addficha(e, id) {
  // colocamos cargando hasta que se vizualice
  $("#" + id + "_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

  console.log(id);

  var file = e.target.files[0],
    imageType = /application.*/;

  if (e.target.files[0]) {
    var sizeByte = file.size;

    var sizekiloBytes = parseInt(sizeByte / 1024);

    var sizemegaBytes = sizeByte / 1000000;
    // alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

    if (extrae_extencion(file.name) == "pdf" || extrae_extencion(file.name) == "jpeg" || extrae_extencion(file.name) == "jpg" || extrae_extencion(file.name) == "png" || extrae_extencion(file.name) == "webp") {
      if (sizekiloBytes <= 10240) {
        var reader = new FileReader();

        reader.onload = fileOnload;

        function fileOnload(e) {
          var result = e.target.result;
          if (extrae_extencion(file.name) == "pdf") {
            $("#foto2_i").hide();
            $("#ver_pdf").html('<iframe src="' + result + '" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
          } else {
            $("#" + id + "_i").attr("src", result);
            $("#foto2_i").show();
          }

          $("#" + id + "_nombre").html(
            "" +
              '<div class="row">' +
              '<div class="col-md-12">' +
              file.name +
              "</div>" +
              '<div class="col-md-12">' +
              '<button  class="btn btn-danger  btn-block" onclick="' +
              id +
              '_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>' +
              "</div>" +
              "</div>" +
              ""
          );

          toastr.success("Imagen aceptada.");
        }

        reader.readAsDataURL(file);
      } else {
        toastr.warning("La imagen: " + file.name.toUpperCase() + " es muy pesada. Tamaño máximo 10mb");

        $("#" + id + "_i").attr("src", "../dist/img/default/img_error.png");

        $("#" + id).val("");
      }
    } else {
      // return;
      toastr.error("Este tipo de ARCHIVO no esta permitido <br> elija formato: <b> .pdf .png .jpeg .jpg .webp etc... </b>");

      $("#" + id + "_i").attr("src", "../dist/img/default/pdf.png");
    }
  } else {
    toastr.error("Seleccione una Imagen");

    $("#" + id + "_i").attr("src", "../dist/img/default/pdf.png");

    $("#" + id + "_nombre").html("");
  }
}

function foto2_eliminar() {
  $("#foto2").val("");
  $("#ver_pdf").html("");

  $("#foto2_i").attr("src", "../dist/img/default/pdf.png");

  $("#foto2_nombre").html("");
  $("#foto2_i").show();
}

//Función limpiar
function limpiar() {
  //Mostramos los Materiales
  $("#idactivos_fijos").val("");
  $("#nombre").val("");
  $("#modelo").val("");
  $("#serie").val("");
  $("#marca").val("");
  $("#descripcion").val("");

  $("#precio_compra").val("");
  $("#subtotal").val("");
  $("#igv").val("");
  $("#total").val("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto_materiales.png");
  $("#foto1").val("");
  $("#foto1_actual").val("");
  $("#foto1_nombre").html("");

  $("#foto2_i").attr("src", "../dist/img/default/pdf.png");
  $("#foto2").val("");
  $("#foto2_actual").val("");
  $("#ver_pdf").val("");
  $("#foto2_nombre").html("");
  $("#foto2_i").show();
  $("#ver_pdf").hide();

  $("#unid_medida").val(4).trigger("change");
  $("#color").val(1).trigger("change");

  $("#my-switch_igv").prop("checked", true);
  $("#estado_igv").val("1");

  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

//Función Listar
function listar() {
  tabla = $("#tabla-activos")
    .dataTable({
      responsive: true,
      lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
      ajax: {
        url: "../ajax/activos_fijos.php?op=listar",
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
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
      iDisplayLength: 5, //Paginación
      order: [[0, "desc"]], //Ordenar (columna,orden)
    })
    .DataTable();
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

  // $(".tooltip").hide();
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

    success: function (datos) {
      if (datos == "ok") {
        toastr.success("Registrado correctamente");

        tabla.ajax.reload();

        limpiar();

        $("#modal-agregar-activos-fijos").modal("hide");
      } else {
        toastr.error(datos);
      }
    },
  });
}

function mostrar(idactivos_fijos) {
  limpiar();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-activos-fijos").modal("show");

  $("#unid_medida").val("").trigger("change");
  $("#color").val("").trigger("change");

  $.post("../ajax/activos_fijos.php?op=mostrar", { idactivos_fijos: idactivos_fijos }, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idactivos_fijos").val(data.idactivos_fijos);
    $("#nombre").val(data.nombre);
    $("#modelo").val(data.modelo);
    $("#serie").val(data.serie);
    $("#marca").val(data.marca);
    $("#precio_compra").val(parseFloat(data.precio_compra).toFixed(2));
    $("#descripcion").val(data.descripcion);

    $("#estado_igv").val(data.estado_igv);
    $("#igv").val(data.igv);
    $("#subtotal").val(data.subtotal);
    /**-------------------------*/
    $("#unid_medida").val(data.idunidad_medida).trigger("change");
    $("#color").val(data.idcolor).trigger("change");

    $("#total").val(parseFloat(data.total).toFixed(2));

    if (data.estado_igv == "1") {
      $("#my-switch_igv").prop("checked", true);
    } else {
      $("#my-switch_igv").prop("checked", false);
    }

    //----------------------
    if (data.imagen != "") {
      $("#foto1_i").attr("src", "../dist/docs/activos_fijos_general/img_activos_fijos/" + data.imagen);

      $("#foto1_actual").val(data.imagen);
    }

    if (data.ficha_tecnica != "") {
      $("#foto2_actual").val(data.ficha_tecnica);
      $("#ver_pdf").html("");
      $("#foto2_i").attr("src", "");

      $("#foto2_i").hide();
      $("#ver_pdf").show();
      $("#ver_pdf").html('<iframe src="../dist/docs/activos_fijos_general/ficha_tecnica_activos_fijos/' + data.ficha_tecnica + '" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');

      $("#foto2_nombre").html(
        "" +
          '<div class="row">' +
          '<div class="col-md-12">.</div>' +
          '<div class="col-md-12">' +
          '<button  class="btn btn-danger  btn-block" onclick="foto2_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>' +
          "</div>" +
          "</div>" +
          ""
      );
    } else {
      $("#foto2_i").show();
      $("#ver_pdf").html("");
      $("#foto2_nombre").html("");
      $("#ver_pdf").hide();
    }
  });
}

//Función para desactivar registros
function desactivar(idactivos_fijos) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar el registro?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/activos_fijos.php?op=desactivar", { idactivos_fijos: idactivos_fijos }, function (e) {
        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");

        tabla.ajax.reload();
      });
    }
  });
}

//Función para activar registros
function activar(idactivos_fijos) {
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
      $.post("../ajax/activos_fijos.php?op=activar", { idactivos_fijos: idactivos_fijos }, function (e) {
        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla.ajax.reload();
      });
    }
  });
}

function precio_con_igv() {
  var precio_total = 0;
  var mont_igv = 0.0;

  var precio_base = 0;
  var igv = 0;
  var precio_re = 0;

  //var precio_r=0;
  precio_total = $("#precio_compra").val();

  $("#igv").val(mont_igv.toFixed(2));
  $("#subtotal").val(precio_total);

  if ($("#my-switch_igv").is(":checked")) {
    precio_base = precio_total / 1.18;
    igv = precio_total - precio_base;
    precio_re = parseFloat(precio_total) - igv;
    
    $("#igv").val(igv.toFixed(2));
    $("#subtotal").val(precio_re.toFixed(2));
    $("#total").val((precio_re + igv).toFixed(2));

    $("#estado_igv").val("1");
  } else {
    precio_base = precio_total * 1.18;

    igv = precio_base - precio_total;
    precio_re = parseFloat(precio_total) - igv;

    $("#igv").val(igv.toFixed(2));
    $("#subtotal").val( parseFloat(precio_total).toFixed(2));
    $("#total").val(precio_base.toFixed(2));

    $("#estado_igv").val("0");
  }
}

$("#my-switch_igv").on("click ", function (e) {
  var precio_total = 0;
  var precio_base = 0;
  var igv = 0;
  var precio_re = 0;
  precio_total = $("#precio_compra").val();

  $("#igv").val("");
  $("#subtotal").val("");

  if ($("#my-switch_igv").is(":checked")) {
    precio_base = precio_total / 1.18;
    igv = precio_total - precio_base;
    precio_re = parseFloat(precio_total) - igv;

    $("#igv").val(igv.toFixed(2));
    $("#subtotal").val(precio_re.toFixed(2));
    $("#total").val((precio_re + igv).toFixed(2));

    $("#estado_igv").val("1");
  } else {
    precio_base = precio_total * 1.18;
    console.log(precio_base);
    igv = precio_base - precio_total;
    precio_re = parseFloat(precio_total) + igv;


    $("#igv").val(igv.toFixed(2));
    $("#subtotal").val( parseFloat(precio_total).toFixed(2));
    $("#total").val(precio_re.toFixed(2));

    $("#estado_igv").val("0");
  }
});

init();

$(function () {
  $.validator.setDefaults({
    submitHandler: function (e) {
      guardaryeditar(e);
    },
  });

  $("#form-materiales-activos-fijos").validate({
    rules: {
      nombre: { required: true },
      modelo: { required: true },
      precio_compra: { required: true },
      descripcion: { minlength: 1 },
    },
    messages: {
      nombre: {
        required: "Por favor ingrese nombre",
      },
      modelo: {
        required: "Por favor ingrese modelo",
      },
      precio_compra: {
        required: "Ingresar precio compra",
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

/**Redondear */
function redondearExp(numero, digitos) {
  function toExp(numero, digitos) {
    let arr = numero.toString().split("e");
    let mantisa = arr[0],
      exponente = digitos;
    if (arr[1]) exponente = Number(arr[1]) + digitos;
    return Number(mantisa + "e" + exponente.toString());
  }
  let entero = Math.round(toExp(Math.abs(numero), digitos));
  return Math.sign(numero) * toExp(entero, -digitos);
}

function extrae_extencion(filename) {
  return filename.split(".").pop();
}
