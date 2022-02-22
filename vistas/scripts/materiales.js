var tabla;

//Función que se ejecuta al inicio
function init() {
  listar();
  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  $("#lAllMateriales").addClass("active");

  $("#guardar_registro").on("click", function (e) { $("#submit-form-materiales").submit(); });

  $("#imagen1_i").click(function () { $("#imagen1").trigger("click"); });
  $("#imagen1").change(function (e) { addImage(e, $("#imagen1").attr("id")); });

  //ficha tecnica
  $("#imagen_ficha_i").click(function () { $("#imagen_ficha").trigger("click"); });
  $("#imagen_ficha").change(function (e) { addficha(e, $("#imagen_ficha").attr("id")); });

  //Mostramos colores
  $.post("../ajax/color.php?op=selectcolor", function (r) { $("#color").html(r); });

  //Mostramos colores
  $.post("../ajax/unidades_m.php?op=selectUnidad", function (r) { $("#unid_medida").html(r); });

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

  //============unidad================
  $("#unid_medida").val("null").trigger("change");

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

function imagen1_eliminar() {
  $("#imagen1").val("");

  $("#imagen1_i").attr("src", "../dist/img/default/img_defecto_materiales.png");

  $("#imagen1_nombre").html("");
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
            $("#imagen_ficha_i").hide();
            $("#ver_pdf").html('<iframe src="' + result + '" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
          } else {
            $("#" + id + "_i").attr("src", result);
            $("#imagen_ficha_i").show();
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

function imagen_ficha_eliminar() {
  $("#imagen_ficha").val("");
  $("#ver_pdf").html("");

  $("#imagen_ficha_i").attr("src", "../dist/img/default/pdf.png");

  $("#imagen_ficha_nombre").html("");
  $("#imagen_ficha_i").show();
}

//Función limpiar
function limpiar() {
  //Mostramos los Materiales
  $("#idproducto").val("");
  $("#nombre_material").val("");
  $("#marca").val("");
  $("#descripcion_material").val("");

  $("#precio_unitario").val("");
  $("#estado_igv").val("");
  $("#monto_igv").val("");
  $("#precio_real").val("");
  $(".precio_real").val("");
  $(".total").val("");
  $(".monto_igv").val("");
  $("#unid_medida").val("");
  $("#total_precio").val("");

  $("#imagen1_i").attr("src", "../dist/img/default/img_defecto_materiales.png");
  $("#imagen1").val("");
  $("#imagen1_actual").val("");
  $("#imagen1_nombre").html("");

  $("#imagen_ficha_i").attr("src", "../dist/img/default/pdf.png");
  $("#imagen_ficha").val("");
  $("#imagen_ficha_actual").val("");
  $("#ver_pdf").val("");
  $("#imagen_ficha_nombre").html("");
  $("#imagen_ficha_i").show();
  $("#ver_pdf").hide();
  $("#unid_medida").val("null").trigger("change");
  $("#color").val("1").trigger("change");
  $("#my-switch_igv").prop("checked", true);
  $("#estado_igv").val("1");

  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

//Función Listar
function listar() {
  tabla = $("#tabla-materiales")
    .dataTable({
      responsive: true,
      lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
      ajax: {
        url: "../ajax/materiales.php?op=listar",
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
    $("#img-factura").attr("src", "../dist/ficha_tecnica_materiales/" + ficha_tec);

    $("#iddescargar").attr("href", "../dist/ficha_tecnica_materiales/" + ficha_tec);
  } else {
    $("#img-factura").hide();

    $("#ver_fact_pdf").show();

    $("#ver_fact_pdf").html('<iframe src="../dist/ficha_tecnica_materiales/' + ficha_tec + '" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');

    $("#iddescargar").attr("href", "../dist/ficha_tecnica_materiales/" + ficha_tec);
  }

  // $(".tooltip").hide();
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

    success: function (datos) {
      if (datos == "ok") {
        toastr.success("Registrado correctamente");

        tabla.ajax.reload();

        limpiar();

        $("#modal-agregar-material").modal("hide");
      } else {
        toastr.error(datos);
      }
    },
  });
}

function mostrar(idproducto) {
  limpiar(); //console.log(idproducto);

  //$("#proveedor").val("").trigger("change");
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-material").modal("show");

  $("#unid_medida").val("").trigger("change");
  $("#color").val("").trigger("change");

  $.post("../ajax/materiales.php?op=mostrar", { idproducto: idproducto }, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idproducto").val(data.idproducto);
    $("#nombre_material").val(data.nombre);
    $("#marca").val(data.marca);
    $("#precio_unitario").val(parseFloat(data.precio_unitario).toFixed(2));
    $("#descripcion_material").val(data.descripcion);

    $("#estado_igv").val(data.estado_igv);
    $("#monto_igv").val(data.precio_igv);
    $("#precio_real").val(data.precio_sin_igv);
    /**-------------------------*/
    $("#unid_medida").val(data.idunidad_medida).trigger("change");
    $("#color").val(data.idcolor).trigger("change");

    $("#total_precio").val(parseFloat(data.precio_total).toFixed(2));

    $(".precio_real").val(parseFloat(data.precio_sin_igv).toFixed(2));
    $(".total").val(parseFloat(data.precio_total).toFixed(2));
    $(".monto_igv").val(parseFloat(data.precio_igv).toFixed(2));
    //------------

    if (data.estado_igv == "1") {
      $("#my-switch_igv").prop("checked", true);
    } else {
      $("#my-switch_igv").prop("checked", false);
    }

    //----------------------
    if (data.imagen != "") {
      $("#imagen1_i").attr("src", "../dist/img/materiales/" + data.imagen);

      $("#imagen1_actual").val(data.imagen);
    }

    if (data.ficha_tecnica != "") {
      $("#imagen_ficha_actual").val(data.ficha_tecnica);
      $("#ver_pdf").html("");
      $("#imagen_ficha_i").attr("src", "");

      $("#imagen_ficha_i").hide();
      $("#ver_pdf").show();
      $("#ver_pdf").html('<iframe src="../dist/ficha_tecnica_materiales/' + data.ficha_tecnica + '" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');

      $("#imagen_ficha_nombre").html(
        "" +
          '<div class="row">' +
          '<div class="col-md-12">.</div>' +
          '<div class="col-md-12">' +
          '<button  class="btn btn-danger  btn-block" onclick="imagen_ficha_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>' +
          "</div>" +
          "</div>" +
          ""
      );
    } else {
      $("#imagen_ficha_i").show();
      $("#ver_pdf").html("");
      $("#imagen_ficha_nombre").html("");
      $("#ver_pdf").hide();
    }
  });
}

//Función para desactivar registros
function desactivar(idproducto) {
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
      $.post("../ajax/materiales.php?op=desactivar", { idproducto: idproducto }, function (e) {
        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");

        tabla.ajax.reload();
      });
    }
  });
}

//Función para activar registros
function activar(idproducto) {
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
      $.post("../ajax/materiales.php?op=activar", { idproducto: idproducto }, function (e) {
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
  precio_total = $("#precio_unitario").val();
  $("#monto_igv").val(mont_igv.toFixed(2));
  $("#precio_real").val(precio_total);

  if ($("#my-switch_igv").is(":checked")) {
    precio_base = precio_total / 1.18;
    igv = precio_total - precio_base;
    precio_re = parseFloat(precio_total) - igv;

    $("#monto_igv").val(redondearExp(igv, 4));
    $("#precio_real").val(redondearExp(precio_re, 4));

    $(".monto_igv").val(redondearExp(igv, 2));
    $(".precio_real").val(redondearExp(precio_re, 2));

    $(".total").val(redondearExp(precio_re, 2) + redondearExp(igv, 2));
    $("#total_precio").val(redondearExp(precio_re, 4) + redondearExp(igv, 4));

    $("#estado_igv").val("1");
  } else {
    precio_base = precio_total * 1.18;
    console.log("precio_base precio_con_igv " + precio_base);
    igv = precio_base - precio_total;
    precio_re = parseFloat(precio_total) - igv;

    $(".monto_igv").val(redondearExp(igv, 2));
    $("#monto_igv").val(redondearExp(igv, 4));

    $(".precio_real").val(redondearExp(precio_total, 2));
    $("#precio_real").val(redondearExp(precio_total, 4));

    $(".total").val(redondearExp(precio_base, 2));
    $("#total_precio").val(redondearExp(precio_base, 4));

    $("#estado_igv").val("0");
  }
}

$("#my-switch_igv").on("click ", function (e) {
  var precio_total = 0;
  var precio_base = 0;
  var igv = 0;
  var precio_re = 0;
  precio_total = $("#precio_unitario").val();

  $("#monto_igv").val("");
  $("#precio_real").val("");

  if ($("#my-switch_igv").is(":checked")) {
    precio_base = precio_total / 1.18;
    igv = precio_total - precio_base;
    precio_re = parseFloat(precio_total) - igv;

    $("#monto_igv").val(redondearExp(igv, 4));
    $("#precio_real").val(redondearExp(precio_re, 4));

    $(".monto_igv").val(redondearExp(igv, 2));
    $(".precio_real").val(redondearExp(precio_re, 2));

    $(".total").val(redondearExp(precio_base, 2) + redondearExp(igv, 2));
    $("#total_precio").val(redondearExp(precio_re, 4) + redondearExp(igv, 4));

    $("#estado_igv").val("1");
  } else {
    precio_base = precio_total * 1.18;
    console.log(precio_base);
    igv = precio_base - precio_total;
    precio_re = parseFloat(precio_total) + igv;

    $(".monto_igv").val(redondearExp(igv, 2));
    $("#monto_igv").val(redondearExp(igv, 4));

    $(".precio_real").val(redondearExp(precio_total, 2));
    $("#precio_real").val(redondearExp(precio_total, 4));

    $(".total").val(redondearExp(precio_re, 2));
    $("#total_precio").val(redondearExp(precio_re, 4));

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

  $("#form-materiales").validate({
    rules: {
      nombre_material: { required: true },
      descripcion_material: { minlength: 1 },
      unid_medida: { required: true },
      color: { required: true },
    },
    messages: {
      nombre_material: {
        required: "Por favor ingrese nombre",
      },
      unid_medida: {
        required: "Seleccione unidad",
      },
      color: {
        required: "Selecione un color",
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

// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_documento").val();

  let dni_ruc = $("#num_documento").val();

  if (tipo_doc == "DNI") {
    if (dni_ruc.length == "8") {
      $.post("../ajax/persona.php?op=reniec", { dni: dni_ruc }, function (data, status) {
        data = JSON.parse(data);

        console.log(data);

        if (data.success == false) {
          $("#search").show();

          $("#charge").hide();

          toastr.error("Es probable que el sistema de busqueda esta en mantenimiento o los datos no existe en la RENIEC!!!");
        } else {
          $("#search").show();

          $("#charge").hide();

          $("#nombre").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

          toastr.success("Cliente encontrado!!!!");
        }
      });
    } else {
      $("#search").show();

      $("#charge").hide();

      toastr.info("Asegurese de que el DNI tenga 8 dígitos!!!");
    }
  } else {
    if (tipo_doc == "RUC") {
      if (dni_ruc.length == "11") {
        $.post("../ajax/persona.php?op=sunat", { ruc: dni_ruc }, function (data, status) {
          data = JSON.parse(data);

          console.log(data);
          if (data.success == false) {
            $("#search").show();

            $("#charge").hide();

            toastr.error("Datos no encontrados en la SUNAT!!!");
          } else {
            if (data.estado == "ACTIVO") {
              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);

              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);
              // $("#direccion").val(data.direccion);
              toastr.success("Cliente encontrado");
            } else {
              toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);

              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);

              // $("#direccion").val(data.direccion);
            }
          }
        });
      } else {
        $("#search").show();

        $("#charge").hide();

        toastr.info("Asegurese de que el RUC tenga 11 dígitos!!!");
      }
    } else {
      if (tipo_doc == "CEDULA" || tipo_doc == "OTRO") {
        $("#search").show();

        $("#charge").hide();

        toastr.info("No necesita hacer consulta");
      } else {
        $("#tipo_doc").addClass("is-invalid");

        $("#search").show();

        $("#charge").hide();

        toastr.error("Selecione un tipo de documento");
      }
    }
  }
}

function extrae_extencion(filename) {
  return filename.split(".").pop();
}
