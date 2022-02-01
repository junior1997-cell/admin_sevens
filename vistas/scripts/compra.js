var reload_detraccion='';
var tabla;
var tabla_comp_prov;
var tablamateriales;
var tabla_list_comp_prov;
var tabla_facturas;
var tabla_pagos1; var tabla_pagos2; var tabla_pagos3;

var array_class_trabajador = [];
//Requejo99@
//Función que se ejecuta al inicio
function init() {
    listar(localStorage.getItem("nube_idproyecto"));

    fecha_actual();
    $("#idproyecto").val(localStorage.getItem("nube_idproyecto"));

    //Cargamos los items al select cliente
    $.post("../ajax/compra.php?op=selectProveedor", function (r) {
        $("#idproveedor").html(r);
    });

    
    $("#bloc_Compras").addClass("menu-open");

    $("#mCompra").addClass("active");

    $("#lCompras").addClass("active");

    // $("#guardar_registro_compras").on("click", function (e) {
    //     Swal.fire({
    //         title: "¿Está seguro que deseas guardar esta compra?",
    //         text: "Al guardar no podrás editar!!",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#28a745",
    //         cancelButtonColor: "#d33",
    //         confirmButtonText: "Si, Guardar!",
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $("#submit-form-compras").submit();
    //         }
    //     });
    // });

    // guardar el registro de la compra
    $("#guardar_registro_compras").on("click", function (e) {
        $("#submit-form-compras").submit();
      });

    //guardar registro proveedor
    $("#guardar_registro_proveedor").on("click", function (e) {
        $("#submit-form-proveedor").submit();
        console.log("registrando");
    });
    //=====Guardar factura=============
    $("#guardar_registro_factura").on("click", function (e) {
        $("#submit-form-factura").submit();
    });
    //=====Guardar pago=============
    $("#guardar_registro_pago").on("click", function (e) {
        $("#submit-form-pago").submit();
    });
    //subir factura modal
    $("#guardar_registro_2").on("click", function (e) {
        $("#submit-form-planootro").submit();
    });

    //Initialize Select2 Elements
    $("#idproveedor").select2({
        theme: "bootstrap4",
        placeholder: "Selecione trabajador",
        allowClear: true,
    });

    //Initialize Select2 Elements
    $("#tipo_comprovante").select2({
        theme: "bootstrap4",
        placeholder: "Selecione Comprobante",
        allowClear: true,
    });
    //============pagoo================
    //Initialize Select2 Elements
    $("#forma_pago").select2({
        theme: "bootstrap4",
        placeholder: "Selecione una forma de pago",
        allowClear: true,
    });
    //Initialize Select2 Elements
    $("#tipo_pago").select2({
        theme: "bootstrap4",
        placeholder: "Selecione un tipo de pago",
        allowClear: true,
    });

    $("#idproveedor").val("null").trigger("change");
    
    //===============Pago============
    $("#forma_pago").val("null").trigger("change");
    $("#tipo_pago").val("null").trigger("change");

    //vaucher
    $("#foto1_i").click(function () {
        $("#foto1").trigger("click");
    });
    $("#foto1").change(function (e) {
        addImage(e, $("#foto1").attr("id"));
    });

    //Factura
    $("#foto2_i").click(function () {
        $("#foto2").trigger("click");
    });
    $("#foto2").change(function (e) {
        addImage(e, $("#foto2").attr("id"));
    });

    //subir factura modal
    $("#doc1_i").click(function () {
        $("#doc1").trigger("click");
    });
    $("#doc1").change(function (e) {
        addDocs(e, $("#doc1").attr("id"));
    });

    // Formato para telefono
    $("[data-mask]").inputmask();
}

/* PREVISUALIZAR LAS IMAGENES */
function addImage(e, id) {
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
                        console.log("pdf..." + result);
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

            $("#" + id + "_i").attr("src", "../dist/img/default/img_defecto.png");
        }
    } else {
        toastr.error("Seleccione una Imagen");

        $("#" + id + "_i").attr("src", "../dist/img/default/img_defecto2.png");

        $("#" + id + "_nombre").html("");
    }
}

function foto1_eliminar() {
    $("#foto1").val("");

    $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");

    $("#foto1_nombre").html("");
}

function foto2_eliminar() {
    $("#foto2").val("");
    $("#ver_pdf").html("");

    $("#foto2_i").attr("src", "../dist/img/default/img_defecto2.png");

    $("#foto2_nombre").html("");
    $("#foto2_i").show();
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

// Eliminamos el doc comprobante
function doc1_eliminar() {
    $("#doc1").val("");

    $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

    $("#doc1_nombre").html("");
}

function fecha_actual() {
    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + month + "-" + day;
    console.log(today);
    $("#fecha_compra").val(today);
}

//Función limpiar
function limpiar() {
    $(".tooltip").hide();

    //Mostramos los selectProveedor
    $.post("../ajax/compra.php?op=selectProveedor", function (r) {
        $("#idproveedor").html(r);
    });

    $("#idcompra_proyecto").val();
    $("#idproyecto").val();

    $("#idusuario").val("");
    $("#trabajador_c").html("Trabajador");
    $("#idproveedor").val("null").trigger("change");
    $("#tipo_comprovante").val("Ninguno").trigger("change");
    
    // $("#fecha_compra").val("");
    $("#serie_comprovante").val("");
    $("#descripcion").val("");

    $("#total_venta").val("");
    $(".filas").remove();
    $("#total").html("0");
    $("#subtotal").html("");
    $("#subtotal_compra").val("");

    $("#igv_comp").html("");
    $("#igv_compra").val("");

    $("#total").html("");
    $("#total_venta").val("");

    $(".form-control").removeClass('is-valid');
    $(".is-invalid").removeClass("error is-invalid");
}

//Función limpiar
function limpiardatosproveedor() {
    $(".tooltip").hide();

    $("#idproveedor").val("");
    $("#tipo_documento option[value='RUC']").attr("selected", true);
    $("#nombre").val("");
    $("#num_documento").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#c_bancaria").val("");
    $("#c_detracciones").val("");
    //$("#banco").val("");
    $("#banco option[value='BCP']").attr("selected", true);
    $("#titular_cuenta").val("");

    $(".form-control").removeClass('is-valid');
    $(".is-invalid").removeClass("error is-invalid");
}

function ver_form_add() {
    $("#tabla-compra").hide();
    $("#tabla-compra-proveedor").hide();
    $("#agregar_compras").show();
    $("#regresar").show();
    $("#btn_agregar").hide();
    $("#guardar_registro_compras").hide();
    $("#div_tabla_compra").hide();
    $("#factura_compras").hide();
    listarmateriales();
}

function regresar() {
    $("#regresar").hide();
    $("#tabla-compra").show();
    $("#tabla-compra-proveedor").show();
    $("#agregar_compras").hide();
    $("#btn_agregar").show();
    $("#div_tabla_compra").show();
    $("#div_tabla_compra_proveedor").hide();
    //----
    $("#factura_compras").hide();
    $("#btn-factura").hide();
    //-----
    $("#pago_compras").hide();
    $("#btn-pagar").hide();
    
    $("#monto_total").html("");
    $("#ttl_monto_pgs_detracc").html("");
    $("#pagos_con_detraccion").hide();
    limpiar();
    limpiardatosproveedor();
    tabla.ajax.reload();
}

//Función Listar
function listar(nube_idproyecto) {
    //console.log(idproyecto);
    tabla = $("#tabla-compra")
        .dataTable({
            responsive: true,
            lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
            aProcessing: true, //Activamos el procesamiento del datatables
            aServerSide: true, //Paginación y filtrado realizados por el servidor
            dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
            buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
            ajax: {
                url: "../ajax/compra.php?op=listar_compra&nube_idproyecto=" + nube_idproyecto,
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                },
            },
            "createdRow":function(row,data,ixdex) {
              //console.log(data);
              if (data[7]>0) {
                $('td', row).eq(7).css({
                    'background-color':'#ffc107',
                    'color':'black',
                  });
              }else if(data[7]==0){
                $('td', row).eq(7).css({
                  'background-color':'#28a745',
                  'color':'white',
                });
              }else{
                $('td', row).eq(7).css({
                    'background-color':'#ff5252',
                    'color':'white',
                  }); 
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
            iDisplayLength: 5, //Paginación
            order: [[0, "desc"]], //Ordenar (columna,orden)
            columnDefs:[
                {
                    "targets": [ 9 ],
                    "visible": false,
                    "searchable": false
                }
            ]
        })
        .DataTable();

    //console.log(idproyecto);
    tabla_comp_prov = $("#tabla-compra-proveedor")
        .dataTable({
            responsive: true,
            lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
            aProcessing: true, //Activamos el procesamiento del datatables
            aServerSide: true, //Paginación y filtrado realizados por el servidor
            dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
            buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
            ajax: {
                url: "../ajax/compra.php?op=listar_compraxporvee&nube_idproyecto=" + nube_idproyecto,
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
//facturas agrupadas por proveedor.
function listar_facuras_proveedor(idproveedor, idproyecto) {
    //console.log('idproyecto '+idproyecto, 'idproveedor '+idproveedor);
    $("#div_tabla_compra").hide();
    $("#agregar_compras").hide();
    $("#btn_agregar").hide();
    $("#regresar").show();
    $("#div_tabla_compra_proveedor").show();

    tabla_list_comp_prov = $("#detalles-tabla-compra-prov")
        .dataTable({
            responsive: true,
            lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
            aProcessing: true, //Activamos el procesamiento del datatables
            aServerSide: true, //Paginación y filtrado realizados por el servidor
            dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
            buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
            ajax: {
                url: "../ajax/compra.php?op=listar_detalle_compraxporvee&idproyecto=" + idproyecto + "&idproveedor=" + idproveedor,
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

//Función para guardar o editar - COMPRAS
function guardaryeditar_compras(e) {
    // e.preventDefault(); //No se activará la acción predeterminada del evento
    // $("#tabla-compra").hide();
    // $("#agregar_compras").show();
    var formData = new FormData($("#form-compras")[0]);
    $("#guardar_registro_compras").on("click", function (e) {
        Swal.fire({
            title: "¿Está seguro que deseas guardar esta compra?",
            text: "Al guardar no podrás editar!!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, Guardar!",
        }).then((result) => {
            if (result.isConfirmed) {
                $("#submit-form-compras").submit();
            }
        });
    });

    $.ajax({
        url: "../ajax/compra.php?op=guardaryeditarcompra",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            if (datos == "ok") {
                // toastr.success("Usuario registrado correctamente");
                Swal.fire("Correcto!", "Compra guardada correctamente", "success");

                tabla.ajax.reload();

                limpiar();
                regresar();
                cont = 0
                $("#modal-agregar-usuario").modal("hide");
                tabla_comp_prov.ajax.reload();
            } else {
                // toastr.error(datos);
                Swal.fire("Error!", datos, "error");
            }
        },
    });
}

//guardar proveedor
function guardarproveedor(e) {
    // e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#form-proveedor")[0]);

    $.ajax({
        url: "../ajax/all_proveedor.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            if (datos == "ok") {
                // toastr.success("proveedor registrado correctamente");
                Swal.fire("Correcto!", "Proveedor guardado correctamente.", "success");
                tabla.ajax.reload();

                limpiardatosproveedor();

                $("#modal-agregar-proveedor").modal("hide");

                //Cargamos los items al select cliente
                $.post("../ajax/compra.php?op=selectProveedor", function (r) {
                    $("#idproveedor").html(r);
                });
            } else {
                // toastr.error(datos);
                Swal.fire("Error!", datos, "error");
            }
        },
    });
}



//Función para desactivar registros
function anular(idcompra_proyecto) {
  Swal.fire({
    title: "¿Está Seguro de  Anular la compra?",
    text: "Anulando  compra!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Anular!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/compra.php?op=anular", { idcompra_proyecto: idcompra_proyecto }, function (e) {
        if (e == "ok") {
          Swal.fire("Desactivado!", "Tu usuario ha sido Desactivado.", "success");

          tabla.ajax.reload();
        } else {
          Swal.fire("Error!", e, "error");
        }
      });
    }
  });
}

function des_anular(idcompra_proyecto) {
  Swal.fire({
    title: "¿Está Seguro de ReActivar esta Compra?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/compra.php?op=des_anular", { idcompra_proyecto: idcompra_proyecto }, function (e) {
        Swal.fire("ReActivado!", "Compra ha sido activado.", "success"); 
        tabla.ajax.reload();
      });
    }
  });
}

//=========================================
//SECCION-facturas-compras
//=========================================

function facturas_compras(idcompra_proyecto, idproyecto) {
    total_monto_f(idcompra_proyecto, idproyecto);
    localStorage.setItem("idcompra_com_nube", idcompra_proyecto);
    localStorage.setItem("idproyecto_com_nube", idproyecto);

    $("#idcomp_proyecto").val(idcompra_proyecto);
    $("#idproyectof").val(idproyecto);

    $("#tabla-compra").hide();
    $("#tabla-compra-proveedor").hide();
    // $("#agregar_compras").show();
    $("#regresar").show();
    $("#btn_agregar").hide();
    $("#guardar_registro_compras").hide();
    $("#div_tabla_compra").hide();
    $("#factura_compras").show();
    $("#btn-factura").show();

    tabla_facturas = $("#tabla_facturas")
        .dataTable({
            responsive: true,
            lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
            aProcessing: true, //Activamos el procesamiento del datatables
            aServerSide: true, //Paginación y filtrado realizados por el servidor
            dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
            buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
            ajax: {
                url: "../ajax/compra.php?op=listar_facturas&idcompra_proyecto=" + idcompra_proyecto + "&idproyecto=" + idproyecto,
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

//Calcular Igv y subtotal
function igv_subtotal() {
    var subtotall = 0;
    var igvv = 0;
    $("#subtotal_compraa").val("");
    $("#igv_compraa").val("");

    var mont = $("#monto_compraa").val();
    var monto = parseFloat(mont);
    console.log(monto);
    // var subbbb= $('#subtotal_compra').val();
    //console.log(subbbb);

    subtotall = monto / 1.18;
    console.log("subtotal " + subtotal);

    igvv = monto - subtotall;
    console.log("igvv " + igvv);
    $("#subtotal_compraa").val(subtotall.toFixed(4));
    //console.log(subtotall.toFixed(4));
    $("#igv_compraa").val(igvv.toFixed(4));
}

//Función limpiar-factura
function limpiar_factura() {
    $("#codigo").val("");
    $("#monto_compraa").val("");
    $("#idfacturacompra").val("");
    $("#fecha_emision").val("");
    $("#descripcion_f").val("Por concepto de alquiler de maquinaria");
    $("#subtotal_compraa").val("");
    $("#igv_compraa").val("");
    $("#nota").val("");
    $("#foto2_i").attr("src", "../dist/img/default/img_defecto2.png");
    $("#foto2_i").show();
    $("#ver_pdf").html("");
    $("#foto2").val("");
    $("#foto2_actual").val("");
    $("#foto2_nombre").html("");
}

function guardaryeditar_factura(e) {
    // e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#form-agregar-factura")[0]);

    $.ajax({
        url: "../ajax/compra.php?op=guardaryeditar_factura",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            if (datos == "ok") {
                toastr.success("servicio registrado correctamente");

                tabla_facturas.ajax.reload();
                tabla.ajax.reload();

                $("#modal-agregar-factura").modal("hide");
                total_monto_f(localStorage.getItem("idcompra_com_nube"), localStorage.getItem("idproyecto_com_nube"));
                limpiar_factura();
            } else {
                toastr.error(datos);
            }
        },
    });
}

//-total montos facturas
function total_monto_f(idcompra_proyecto, idproyecto) {
    $.post("../ajax/compra.php?op=total_monto_f", { idcompra_proyecto: idcompra_proyecto, idproyecto: idproyecto }, function (data, status) {
        $("#monto_total_f").html("00.0");
        data = JSON.parse(data);

        $("#monto_total_f").html(formato_miles(data.total_mont_f));
    });
}

function ver_modal_factura(imagen) {
    var img = imagen;
    var extencion = img.substr(img.length - 3); // => "1"
    //console.log(extencion);
    $("#ver_fact_pdf").html("");
    $("#img-factura").attr("src", "");
    $("#modal-ver-factura").modal("show");

    if (extencion == "jpeg" || extencion == "jpg" || extencion == "png" || extencion == "webp") {
        $("#ver_fact_pdf").hide();
        $("#img-factura").show();
        $("#img-factura").attr("src", "../dist/img/facturas_compras/" + img);

        $("#iddescargar").attr("href", "../dist/img/facturas_compras/" + img);
    } else {
        $("#img-factura").hide();
        $("#ver_fact_pdf").show();
        $("#ver_fact_pdf").html('<iframe src="../dist/img/facturas_compras/' + img + '" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');
        $("#iddescargar").attr("href", "../dist/img/facturas_compras/" + img);
    }
}
//mostrar
function mostrar_factura(idfacturacompra) {
    $("#modal-agregar-factura").modal("show");

    $.post("../ajax/compra.php?op=mostrar_factura", { idfacturacompra: idfacturacompra }, function (data, status) {
        data = JSON.parse(data); //console.log(data);

        $("#idfacturacompra").val(data.idfacturacompra);
        $("#codigo").val(data.codigo);
        $("#monto_compraa").val(data.monto);
        $("#fecha_emision").val(data.fecha_emision);
        $("#descripcion_f").val(data.descripcion);
        $("#subtotal_compraa").val(data.subtotal);
        $("#igv_compraa").val(data.igv);
        console.log(data.imagen);

        if (data.imagen != "") {
            var img = data.imagen;

            $("#foto2_i").attr("src", "../dist/img/facturas_compras/" + data.imagen);

            var extencion = img.substr(img.length - 3); // => "1"
            // console.log(extencion);
            $("#ver_pdf").html("");
            $("#foto2_i").attr("src", "");

            if (extencion == "jpeg" || extencion == "jpg" || extencion == "png" || extencion == "webp") {
                $("#ver_pdf").hide();
                $("#foto2_i").show();
                $("#foto2_i").attr("src", "../dist/img/facturas_compras/" + img);

                $("#foto2_nombre").html(
                    "" +
                        '<div class="row">' +
                        '<div class="col-md-12">Factura</div>' +
                        '<div class="col-md-12">' +
                        '<button  class="btn btn-danger  btn-block" onclick="foto2_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>' +
                        "</div>" +
                        "</div>" +
                        ""
                );
            } else {
                $("#foto2_i").hide();
                $("#ver_pdf").show();
                $("#ver_pdf").html('<iframe src="../dist/img/facturas_compras/' + img + '" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');

                $("#foto2_nombre").html(
                    "" +
                        '<div class="row">' +
                        '<div class="col-md-12">Factura</div>' +
                        '<div class="col-md-12">' +
                        '<button  class="btn btn-danger  btn-block" onclick="foto2_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>' +
                        "</div>" +
                        "</div>" +
                        ""
                );
            }

            $("#foto2_actual").val(data.imagen);
        }
    });
}
//Función para desactivar registros
function desactivar_factura(idfacturacompra) {
    console.log(idfacturacompra);
    Swal.fire({
        title: "¿Está Seguro de  Desactivar  el servicio?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, desactivar!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/compra.php?op=desactivar_factura", { idfacturacompra: idfacturacompra }, function (e) {
                Swal.fire("Desactivado!", "Servicio ha sido desactivado.", "success");
                // total_pagos(idmaquinaria,localStorage.getItem('nube_idproyecto'));
                total_monto_f(localStorage.getItem("idcompra_com_nube"), localStorage.getItem("idproyecto_com_nube"));
                tabla_facturas.ajax.reload();
            });
        }
    });
}

function activar_factura(idfacturacompra) {
    Swal.fire({
        title: "¿Está Seguro de  Activar  Servicio?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, activar!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/compra.php?op=activar_factura", { idfacturacompra: idfacturacompra }, function (e) {
                Swal.fire("Activado!", "Servicio ha sido activado.", "success");

                //total_pagos(idmaquinaria,localStorage.getItem('nube_idproyecto'));
                total_monto_f(localStorage.getItem("idcompra_com_nube"), localStorage.getItem("idproyecto_com_nube"));
                tabla_facturas.ajax.reload();
            });
        }
    });
}

function comprobante_compras(idcompra_proyecto, doc) {
    //console.log(idcompra_proyecto,doc);
    $("#modal-comprobantes-pago").modal("show");
    $("#comprobante_c").val(idcompra_proyecto);

    if (doc != "") {
        $("#doc_old_1").val(doc);

        // cargamos la imagen adecuada par el archivo
        if (extrae_extencion(doc) == "xls") {
            $("#doc1_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
        } else {
            if (extrae_extencion(doc) == "xlsx") {
                $("#doc1_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
            } else {
                if (extrae_extencion(doc) == "csv") {
                    $("#doc1_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
                } else {
                    if (extrae_extencion(doc) == "xlsm") {
                        $("#doc1_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                    } else {
                        if (extrae_extencion(doc) == "pdf") {
                            $("#doc1_ver").html('<iframe src="../dist/comprobantes_compras/' + doc + '" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');
                        } else {
                            if (extrae_extencion(doc) == "dwg") {
                                $("#doc1_ver").html('<img src="../dist/svg/dwg.svg" alt="" width="50%" >');
                            } else {
                                if (extrae_extencion(doc) == "zip" || extrae_extencion(doc) == "rar" || extrae_extencion(doc) == "iso") {
                                    $("#doc1_ver").html('<img src="../dist/img/default/zip.png" alt="" width="50%" >');
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
                                        $("#doc1_ver").html('<img src="../dist/comprobantes_compras/' + doc + '" alt="" width="50%" >');
                                    } else {
                                        if (
                                            extrae_extencion(doc) == "docx" ||
                                            extrae_extencion(doc) == "docm" ||
                                            extrae_extencion(doc) == "dotx" ||
                                            extrae_extencion(doc) == "dotm" ||
                                            extrae_extencion(doc) == "doc" ||
                                            extrae_extencion(doc) == "dot"
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
        //ver_completo descargar comprobante subir

        $(".ver_completo").val(doc);

        //ver_completo descargar comprobante subir
        // $(".subir").show();
        $(".subir").removeClass("col-md-6").addClass("col-md-4");
        $(".comprobante").removeClass("col-md-6").addClass("col-md-4");

        $(".ver_completo").show();
        $(".ver_completo").removeClass("col-md-4").addClass("col-md-2");

        $(".descargar").show();
        $(".descargar").removeClass("col-md-4").addClass("col-md-2");

        $("#ver_completo").attr("href", "../dist/comprobantes_compras/" + doc);
        $("#descargar_comprob").attr("href", "../dist/comprobantes_compras/" + doc);
    } else {
        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        // $("#doc1_nombre").html("");

        $("#doc_old_1").val("");

        $(".ver_completo").hide();
        $(".descargar").hide();

        $(".subir").removeClass("col-md-4").addClass("col-md-6");
        $(".comprobante").removeClass("col-md-4").addClass("col-md-6");
    }
}

//=========================================
//SECCION-Pago-compras
//=========================================

function listar_pagos(idcompra_proyecto, idproyecto,monto_total, total_deposito) {
    reload_detraccion='no';
    most_datos_prov_pago(idcompra_proyecto);
    localStorage.setItem("idcompra_pago_comp_nube", idcompra_proyecto);

    localStorage.setItem('monto_total_p',monto_total);
    localStorage.setItem('monto_total_dep',total_deposito);

    $("#total_compra").html(formato_miles(monto_total));

    $("#tabla-compra").hide();
    $("#tabla-compra-proveedor").hide();
    // $("#agregar_compras").show();
    $("#regresar").show();
    $("#btn_agregar").hide();
    $("#guardar_registro_compras").hide();
    $("#div_tabla_compra").hide();

    $("#pago_compras").show();
    $("#btn-pagar").show();
    $("#pagos_con_detraccion").hide();

    tabla_pagos1 = $("#tabla-pagos-proveedor")
        .dataTable({
            responsive: true,
            lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
            aProcessing: true, //Activamos el procesamiento del datatables
            aServerSide: true, //Paginación y filtrado realizados por el servidor
            dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
            buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
            ajax: {
                url: "../ajax/compra.php?op=listar_pagos_proveedor&idcompra_proyecto=" + idcompra_proyecto,
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

    total_pagos(idcompra_proyecto);
}

function listar_pagos_detraccion(idcompra_proyecto, idproyecto,monto_total) {

    var total=0;  reload_detraccion='si';
    total_pagos_detracc(idcompra_proyecto);
    
    localStorage.setItem("idcompra_pago_detracc_nub", idcompra_proyecto);
    most_datos_prov_pago(idcompra_proyecto);
    $("#ttl_monto_pgs_detracc").html(formato_miles(monto_total));
    //mostramos los montos del 90 y 10 % 
    $("#t_proveedor").html(formato_miles(monto_total*0.90));
    $(".t_proveedor").val(formato_miles(monto_total*0.90));
    $("#t_provee_porc").html("90");
    $("#t_detaccion").html(formato_miles(monto_total*0.10));
    $(".t_detaccion").val(formato_miles(monto_total*0.10));
    $("#t_detacc_porc").html("10");
   // t_proveedor, t_provee_porc,t_detaccion, t_detacc_porc
    $("#tabla-compra").hide();
    $("#tabla-compra-proveedor").hide();
    // $("#agregar_compras").show();
    $("#regresar").show();
    $("#btn_agregar").hide();
    $("#guardar_registro_compras").hide();
    $("#div_tabla_compra").hide();

    $("#pagos_con_detraccion").show();

    $("#btn-pagar").show();

    tabla_pagos2 = $("#tbl-pgs-detrac-prov-cmprs")
    .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
        ajax: {
            url: "../ajax/compra.php?op=listar_pagos_compra_prov_con_dtracc&idcompra_proyecto=" + idcompra_proyecto,
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
    //Tabla 3 
    tabla_pagos3 = $("#tbl-pgs-detrac-detracc-cmprs")
    .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
        ajax: {
            url: "../ajax/compra.php?op=listar_pgs_detrac_detracc_cmprs&idcompra_proyecto=" + idcompra_proyecto,
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

//Función limpiar
function limpiar_c_pagos() {
    //==========PAGO SERVICIOS=====
    $("#forma_pago").val("");
    $("#tipo_pago").val("");
    $("#monto_pago").val("");
    $("#numero_op_pago").val("");
    $("#idpago_compras").val("");
    $("#cuenta_destino_pago").val("");
    $("#descripcion_pago").val("");
    $("#idpago_compra").val("");
    $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
    $("#foto1").val("");
    $("#foto1_actual").val("");
    $("#foto1_nombre").html("");
}

//mostrar datos proveedor pago
function most_datos_prov_pago(idcompra_proyecto) {
    $("#h4_mostrar_beneficiario").html("");
    $("#idproyecto_pago").val("");

    $("#banco_pago").val("").trigger("change");
    $.post("../ajax/compra.php?op=most_datos_prov_pago", { idcompra_proyecto: idcompra_proyecto }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);

        $("#idproyecto_pago").val(data.idproyecto);
        $("#idcompra_proyecto_p").val(data.idcompra_proyecto);
        $("#idproveedor_pago").val(data.idproveedor);
        $("#beneficiario_pago").val(data.razon_social);
        $("#h4_mostrar_beneficiario").html(data.razon_social);
        $("#banco_pago").val(data.idbancos).trigger("change");
        $("#titular_cuenta_pago").val(data.titular_cuenta);
        localStorage.setItem("nubecompra_c_b", data.cuenta_bancaria);
        localStorage.setItem("nubecompra_c_d", data.cuenta_detracciones);
    });
}

//captura_opicion tipopago
function captura_op() {
    cuenta_bancaria = localStorage.getItem("nubecompra_c_b");
    cuenta_detracciones = localStorage.getItem("nubecompra_c_d");
    //console.log(cuenta_bancaria,cuenta_detracciones);

    $("#cuenta_destino_pago").val("");

    if ($("#tipo_pago").select2("val") == "Proveedor") {
        $("#cuenta_destino_pago").val("");
        $("#cuenta_destino_pago").val(cuenta_bancaria);
    }

    if ($("#tipo_pago").select2("val") == "Detraccion") {
        $("#cuenta_destino_pago").val("");
        $("#cuenta_destino_pago").val(cuenta_detracciones);
    }
}

//Guardar y editar PAGOS
function guardaryeditar_pago(e) {
    // e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#form-servicios-pago")[0]);

    $.ajax({
        url: "../ajax/compra.php?op=guardaryeditar_pago",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            if (datos == "ok") {
                toastr.success("servicio registrado correctamente");

                tabla.ajax.reload();
                $("#modal-agregar-pago").modal("hide");

                if (reload_detraccion=='si') { 
                    tabla_pagos2.ajax.reload();
                    tabla_pagos3.ajax.reload();  
                }else{
                    tabla_pagos1.ajax.reload();
                }
                /**================================================== */
                total_pagos(localStorage.getItem("idcompra_pago_comp_nube"));
                total_pagos_detracc(localStorage.getItem("idcompra_pago_detracc_nub"));

                limpiar_c_pagos();
                
                
            } else {
                toastr.error(datos);
            }
        },
    });
}

//-total Pagos-sin detraccion
function total_pagos(idcompra_proyecto) {
   
        $.post("../ajax/compra.php?op=suma_total_pagos", { idcompra_proyecto: idcompra_proyecto }, function (data, status) {

            $("#monto_total").html("");

            data = JSON.parse(data);
            //console.log(data);
            $("#monto_total").html(formato_miles(data.total_monto));
        });
    
}

//-total pagos con detraccion
function total_pagos_detracc(idcompra_proyecto) {
     
    //tabla 2 proveedor
    $.post("../ajax/compra.php?op=suma_total_pagos_prov", { idcompra_proyecto: idcompra_proyecto }, function (data, status) {

        $("#monto_total_prov").html("");
        var inputValue=0;
        var x = 0;
        var x_saldo = 0;
        var diferencia = 0;
        data = JSON.parse(data);
        //console.log(data);
        inputValue = $(".t_proveedor").val();

        $("#monto_total_prov").html(formato_miles(data.total_montoo));
        x =(data.total_montoo*90)/inputValue;
        $("#porcnt_prove").html(redondearExp(x,2)+' %');

        diferencia=90-x;

        x_saldo =(diferencia*data.total_montoo)/x;

        if (x_saldo==0) {
            $('#saldo_p').html('0.00');
            $('#porcnt_sald_p').html('0.00'+' %');
        }else{
            $('#saldo_p').html(redondearExp(x_saldo, 2));
            $('#porcnt_sald_p').html(redondearExp(diferencia, 2)+' %');
        }
    });

    //tabla 2 detracion
    $.post("../ajax/compra.php?op=suma_total_pagos_detracc", { idcompra_proyecto: idcompra_proyecto }, function (data, status) {

        $("#monto_total_detracc").html("");
        var valor_tt_detrcc=0;
        var x_detrcc = 0;
        var x_saldo_detrcc = 0;
        var diferencia_detrcc = 0;

        data = JSON.parse(data); console.log(data);

        valor_tt_detrcc = $(".t_detaccion").val();

        $("#monto_total_detracc").html(formato_miles(data.total_montoo));

        x_detrcc =(data.total_montoo*10)/valor_tt_detrcc;
        $("#porcnt_detrcc").html(redondearExp(x_detrcc,2)+' %');

        diferencia_detrcc=10-x_detrcc;

        x_saldo_detrcc =(diferencia_detrcc*data.total_montoo)/x_detrcc;

        if (x_saldo_detrcc==0) {
            $('#saldo_d').html('0.00');
            $('#porcnt_sald_d').html('0.00'+' %');
        }else{
            $('#saldo_d').html(redondearExp(x_saldo_detrcc, 2));
            $('#porcnt_sald_d').html(redondearExp(diferencia_detrcc, 2)+' %');
        }

    });
}
//mostrar
function mostrar_pagos(idpago_compras) {
   // console.log("___________ " + idpago_compras);
    $("#h4_mostrar_beneficiario").html("");
    $("#idproveedor_pago").val("");
    $("#modal-agregar-pago").modal("show");
    $("#banco_pago").val("").trigger("change");
    $("#forma_pago").val("").trigger("change");
    $("#tipo_pago").val("").trigger("change");

    $.post("../ajax/compra.php?op=mostrar_pagos", { idpago_compras: idpago_compras }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);

        $("#idproveedor_pago").val(data.idproveedor);
        $("#idcompra_proyecto_p").val(data.idcompra_proyecto);
        // $("#maquinaria_pago").html(data.nombre_maquina);
        $("#beneficiario_pago").val(data.beneficiario);
        $("#h4_mostrar_beneficiario").html(data.beneficiario);
        $("#cuenta_destino_pago").val(data.cuenta_destino);
        $("#banco_pago").val(data.id_banco).trigger("change");
        $("#titular_cuenta_pago").val(data.titular_cuenta);
        $("#forma_pago").val(data.forma_pago).trigger("change");
        $("#tipo_pago").val(data.tipo_pago).trigger("change");
        $("#fecha_pago").val(data.fecha_pago);
        $("#monto_pago").val(data.monto);
        $("#numero_op_pago").val(data.numero_operacion);
        $("#descripcion_pago").val(data.descripcion);
        $("#idpago_compras").val(data.idpago_compras);

        if (data.imagen != "") {
            $("#foto1_i").attr("src", "../dist/img/vauchers_pagos/" + data.imagen);

            $("#foto1_actual").val(data.imagen);
        }
    });
}

//Función para desactivar registros
function desactivar_pagos(idpago_compras) {
    Swal.fire({
        title: "¿Está Seguro de  Desactivar el pago?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, desactivar!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/compra.php?op=desactivar_pagos", { idpago_compras: idpago_compras }, function (e) {
                Swal.fire("Desactivado!", "El pago ha sido desactivado.", "success");

                total_pagos(localStorage.getItem("idcompra_pago_comp_nube"));
                
                total_pagos_detracc(localStorage.getItem("idcompra_pago_detracc_nub"));

                if (reload_detraccion=='si') { 
                    tabla_pagos2.ajax.reload();
                    tabla_pagos3.ajax.reload();  
                }else{
                    tabla_pagos1.ajax.reload();
                }
                
               
            });
        }
    });
}

function activar_pagos(idpago_compras) {
    Swal.fire({
        title: "¿Está Seguro de  Activar  Pago?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, activar!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/compra.php?op=activar_pagos", { idpago_compras: idpago_compras }, function (e) {
                Swal.fire("Activado!", "Pago ha sido activado.", "success");

                total_pagos(localStorage.getItem("idcompra_pago_comp_nube"));
                
                total_pagos_detracc(localStorage.getItem("idcompra_pago_detracc_nub"));
                
                if (reload_detraccion=='si') { 
                    tabla_pagos2.ajax.reload();
                    tabla_pagos3.ajax.reload();  
                    
                }else{

                    tabla_pagos1.ajax.reload();
                    
                }
            });
        }
    });
}

function ver_modal_vaucher(imagen) {
    $("#img-vaucher").attr("src", "");
    $("#modal-ver-vaucher").modal("show");
    $("#img-vaucher").attr("src", "../dist/img/vauchers_pagos/" + imagen);
    $("#descargar").attr("href", "../dist/img/vauchers_pagos/" + imagen);

    // $(".tooltip").hide();
}

/**===============================
 * ======================================
 * =========
 */
//Función ListarArticulos
function listarmateriales() {
    tablamateriales = $("#tblamateriales")
        .dataTable({
            responsive: true,
            lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
            aProcessing: true, //Activamos el procesamiento del datatables
            aServerSide: true, //Paginación y filtrado realizados por el servidor
            dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
            buttons: [],
            ajax: {
                url: "../ajax/compra.php?op=listarMaterialescompra",
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                },
            },
            bDestroy: true,
            iDisplayLength: 5, //Paginación
            order: [[0, "desc"]], //Ordenar (columna,orden)
        })
        .DataTable();
}

// .......:::::::::::::::::: AGREGAR FACURAS, BOLETAS, NOTA DE VENTA, ETC ::::::::::::.......
//Declaración de variables necesarias para trabajar con las compras y sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;

function agregarDetalleComprobante(idproducto, nombre, unidad_medida, nombre_color, precio_sin_igv, precio_igv, precio_total, img, ficha_tecnica_producto) {

  var stock = 5;    var cantidad = 1;    var descuento = 0;

  if (idproducto != "") {
    // $('.producto_'+idproducto).addClass('producto_selecionado');
    if ($(".producto_" + idproducto).hasClass("producto_selecionado")) {

      toastr.success("Material: " + nombre + " agregado !!");

      var cant_producto = $(".producto_" + idproducto).val();

      var sub_total = parseInt(cant_producto, 10) + 1;

      $(".producto_" + idproducto).val(sub_total);

      modificarSubtotales();
    } else {

      if ($("#tipo_comprovante").select2("val") == "Factura") {

        var subtotal = cantidad * precio_total;
      }else{

        var subtotal = cantidad * precio_sin_igv;
      }

      var fila =`
      <tr class="filas" id="fila${cont}">
        <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(${cont})">X</button></td>
        <td>
          <input type="hidden" name="idproducto[]" value="${idproducto}">
          <input type="hidden" name="ficha_tecnica_producto[]" value="${ficha_tecnica_producto}">
          <div class="user-block w-px-300">
            <img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/img/materiales/${img}" alt="user image" onerror="this.src='../dist/img/materiales/img_material_defect.jpg';" onclick="ver_img_material('${img}', '${nombre}')">
            <span class="username"><p style="margin-bottom: 0px !important;">${nombre}</p></span>
            <span class="description"><b>Color: </b>${nombre_color}</span>
          </div>
        </td>
        <td><span class="">${unidad_medida}</span> <input type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${unidad_medida}"><input type="hidden" name="nombre_color[]" id="nombre_color[]" value="${nombre_color}"></td>
        <td class="form-group"><input class="producto_${idproducto} producto_selecionado w-px-100 cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" min="1" value="${cantidad}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
        <td class="hidden"><input type="number" class="w-px-135 input-no-border precio_sin_igv_${cont}" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${precio_sin_igv}" readonly min="0" ></td>
        <td class="hidden"><input class="w-px-135 input-no-border precio_igv_${cont}" type="number" name="precio_igv[]" id="precio_igv[]" value="${precio_igv}" readonly  ></td>
        <td ><input class="w-px-135 precio_con_igv_${cont}" type="number" name="precio_con_igv[]" id="precio_con_igv[]" value="${precio_total}" onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
        <td><input type="number" class="w-px-135 descuento_${cont}" name="descuento[]" value="${descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
        <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">${subtotal}</span></td>
        <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fas fa-sync"></i></button></td>
      </tr>`;
        
      detalles = detalles + 1;

      $("#detalles").append(fila);

      array_class_trabajador.push({'id_cont':cont});

      modificarSubtotales();

      toastr.success("Material: " + nombre + " agregado !!");            

      cont++;  evaluar();           
    }
  } else {
    // alert("Error al ingresar el detalle, revisar los datos del artículo");
    toastr.error("Error al ingresar el detalle, revisar los datos del material.");
  }
}

function evaluar() {
    if (detalles > 0) {
        $("#guardar_registro_compras").show();
    } else {
        $("#guardar_registro_compras").hide();
        cont = 0;
    }
}

function modificarSubtotales() {
    
    var cantidad = document.getElementsByName("cantidad[]");
    var precio_sin_igv = document.getElementsByName("precio_sin_igv[]");
    var precio_igv = document.getElementsByName("precio_igv[]");
    var precio_con_igv = document.getElementsByName("precio_con_igv[]");  
    var descuento = document.getElementsByName("descuento[]");
    var subtotal_producto = document.getElementsByName("subtotal_producto");    
    // var igv = document.getElementsByName("igv");
    if ($("#tipo_comprovante").select2("val") == null) { 
               
        $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

        $("#colspan_subtotal").attr("colspan",5); //cambiamos el: colspan

        for (var i = 0; i < cantidad.length; i++) {
            // console.log(cantidad[i]);
            var inpC = cantidad[i];
            var inpP = precio_sin_igv[i];
            var inpD = descuento[i];
            var inpS = subtotal_producto[i];
            
            // Calculamos: IGV
            var igv = "0";
            document.getElementsByName(`precio_igv[${i}]`).innerHTML = igv;

            // Calculamos: precio + IGV 
            var precio_con_igv = "0";
            document.getElementsByName(`precio_con_igv[${i}]`).innerHTML = precio_con_igv;

            inpS.value = inpC.value * inpP.value - inpD.value;
            document.getElementsByName("subtotal_producto")[i].innerHTML = formato_miles(inpS.value.toFixed(4));
        }
        calcularTotalesSinIgv();         
    }else{

        if ($("#tipo_comprovante").select2("val") == "Factura") {
            console.log(array_class_trabajador);
            $(".hidden").show(); //Mostramos: IGV, PRECIO CON IGV

            $("#colspan_subtotal").attr("colspan",7); //cambiamos el: colspan  

            array_class_trabajador.forEach((element,index) => {
              // console.log(element.id_cont);
              var inpC = parseFloat($(`.cantidad_${element.id_cont}`).val());
              var inpP = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
              var inpIgv = 0;
              var inpPcIgv = 0;
              var inpD = parseFloat($(`.descuento_${element.id_cont}`).val());
              var inpS = 0;
              // console.log(inpC,inpP, inpD );
              // Calculamos: IGV
              var precio_sin_igv = (inpP / 1.18).toFixed(4);
              $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);  

              // Calculamos: precio + IGV 
              var igv = ( parseFloat(inpP) - parseFloat(precio_sin_igv)).toFixed(4);
              $(`.precio_igv_${element.id_cont}`).val(igv);

              // Calculamos: Subtotal de cada producto
              inpS = (inpC * parseFloat(inpP)) - inpD;
              $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(inpS.toFixed(4)));

            }); 

             
            calcularTotalesConIgv()
        } else {             
            $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

            $("#colspan_subtotal").attr("colspan",5); //cambiamos el: colspan

            for (var i = 0; i < cantidad.length; i++) {
                // console.log(cantidad[i]);
                var inpC = cantidad[i];
                var inpP = precio_con_igv[i];
                var inpD = descuento[i];
                var inpS = subtotal_producto[i];

                // Calculamos: IGV
                var igv = "0";
                
                document.getElementsByName(`precio_igv[${i}]`).innerHTML = igv;

                // Calculamos: precio + IGV 
                var precio_sin_igv = inpP.value;
                document.getElementsByName(`precio_sin_igv[${i}]`).innerHTML = precio_sin_igv;
        
                inpS.value = inpC.value * inpP.value - inpD.value;
                document.getElementsByName(`subtotal_producto`)[i].innerHTML = formato_miles(inpS.value.toFixed(4));
            }
            calcularTotalesSinIgv();
        }         
    }    
    toastr.success("Precio Actualizado !!!");
}

function calcularTotalesSinIgv() {
    var sub = document.getElementsByName("subtotal_producto");
    var total = 0.0;
    var igv = 0;
    var mtotal = 0;

    for (var i = 0; i < sub.length; i++) { total += document.getElementsByName("subtotal_producto")[i].value; }

    $("#subtotal").html("S/. " + formato_miles(total));
    $("#subtotal_compra").val( redondearExp(total, 4));

    $("#igv_comp").html("S/. 0.00");
    $("#igv_compra").val(0.00);
    
    $("#total").html("S/. " + formato_miles(total.toFixed(2)));
    $("#total_venta").val(redondearExp(total, 4));

}

function calcularTotalesConIgv() {     
  var igv = 0;
  var total = 0.0;
  
  var subotal_sin_igv = 0;

  array_class_trabajador.forEach((element,index) => { total += parseFloat( quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()) ) });
    
  console.log(total);
  subotal_sin_igv = (parseFloat(total)/1.18).toFixed(2);
  igv = (parseFloat(total) - parseFloat(subotal_sin_igv)).toFixed(2);

  $("#subtotal").html(`S/. ${formato_miles(subotal_sin_igv)}`);
  $("#subtotal_compra").val( redondearExp(subotal_sin_igv, 4));

  $("#igv_comp").html("S/. " + formato_miles(igv));
  $("#igv_compra").val(igv);
  
  $("#total").html("S/. " + formato_miles(total.toFixed(2)));
  $("#total_venta").val(redondearExp(total, 4));

  total = 0.0;
}

function ocultar_comprob() {

    if ($("#tipo_comprovante").select2("val") == "Ninguno") {

        $("#content-comprob").hide();

        $("#content-comprob").val("");

        $("#content-descrp").removeClass("col-lg-5").addClass("col-lg-7");

    } else {

        $("#content-comprob").show();
        
        $("#content-descrp").removeClass("col-lg-7").addClass("col-lg-5");
    }
}

function eliminarDetalle(indice) {

    $("#fila" + indice).remove();

    array_class_trabajador.forEach(function(car, index, object) {
      if(car.id_cont === indice){
        object.splice(index, 1);
      }
    });

    modificarSubtotales();

    detalles = detalles - 1;

    evaluar();

    toastr.warning("Material removido.");
}

function guardaryeditar_comprobante(e) {
    // e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#form-comprobante")[0]);

    $.ajax({
        url: "../ajax/compra.php?op=guardaryeditar_comprobante",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            if (datos == "ok") {
                Swal.fire("Correcto!", "Documento guardado correctamente", "success");

                tabla.ajax.reload();

                limpiar();
                 
                $("#modal-comprobantes-pago").modal("hide");
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

//Detraccion
$("#my-switch_detracc").on("click ", function (e) { if ($("#my-switch_detracc").is(":checked")) { $("#estado_detraccion").val("1"); } else { $("#estado_detraccion").val("0"); } });

//mostramos para editar el datalle del comprobante de la compras
function editar_detalle_compras(idcompra_proyecto) { 

  limpiar(); array_class_trabajador = [];

  cont = 0; detalles = 0;
  ver_form_add();

  $.post("../ajax/compra.php?op=ver_compra_editar", { idcompra_proyecto: idcompra_proyecto }, function (data, status) {

    data = JSON.parse(data); console.log(data);

    if (data) {            

      $(".subtotal").html("");
      $(".igv_comp").html("");
      $(".total").html("");

      if (data.tipo_comprovante == "Factura") {
          $(".igv").val("0.18");
          $(".content-igv").show();
          $(".content-t-comprob").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
          $(".content-descrp").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
          $(".content-comprob").show();
      } else if (data.tipo_comprovante == "Boleta" || data.tipo_comprovante == "Nota_de_venta") {
          $(".igv").val("");
          $(".content-comprob").show();
          $(".content-igv").hide();
          $(".content-t-comprob").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");

          $(".content-descrp").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      } else if (data.tipo_comprovante == "Ninguno") {
          $(".content-comprob").hide();
          $(".content-comprob").val("");
          $(".content-igv").hide();
          $(".content-t-comprob").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
          $(".content-descrp").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
          $(".content-comprob").show();
          //$(".content-descrp").removeClass("col-lg-7").addClass("col-lg-4");
      }

      $("#idproyecto").val(data.idproyecto);
      $("#idcompra_proyecto").val(data.idcompra_x_proyecto);
      $("#idproveedor").val(data.idproveedor).trigger("change");
      $("#fecha_compra").val(data.fecha_compra);
      $("#tipo_comprovante").val(data.tipo_comprobante).trigger("change");
      $("#serie_comprovante").val(data.serie_comprobante).trigger("change");
      $("#descripcion").val(data.descripcion);

      if (data.producto) {

        data.producto.forEach((element,index) => {

          var img = "";

          if (element.imagen == "" || element.imagen == null) { img = "img_material_defect.jpg"; } else { img = element.imagen; }

          var fila =`
          <tr class="filas" id="fila${cont}">
            <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(${cont})">X</button></td>
            <td>
              <input type="hidden" name="idproducto[]" value="${element.idproducto}">
              <input type="hidden" name="ficha_tecnica_producto[]" value="${element.ficha_tecnica}">
              <div class="user-block w-px-300">
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/img/materiales/${img}" alt="user image" onerror="this.src='../dist/img/materiales/img_material_defect.jpg';" onclick="ver_img_material('${img}', '${element.nombre_producto}')">
                <span class="username"><p style="margin-bottom: 0px !important;">${element.nombre_producto}</p></span>
                <span class="description"><b>Color: </b>${element.color}</span>
              </div>
            </td>
            <td> <span class="">${element.unidad_medida}</span> <input type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${element.unidad_medida}"> <input type="hidden" name="nombre_color[]" id="nombre_color[]" value="${element.color}"></td>
            <td class="form-group"><input class="producto_${element.idproducto} producto_selecionado w-px-70 cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" min="1" value="${element.cantidad}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td><input type="number" class="w-px-135 precio_sin_igv_${cont}" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${element.precio_venta}" onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
            <td class="hidden"><input class="w-px-135 precio_igv_${cont}" type="number"  name="precio_igv[]" id="precio_igv[]" value="${element.igv}" readonly  style="border: none; text-align: center;"></td>
            <td class="hidden"><input class="w-px-135 precio_con_igv_${cont}" type="number"  name="precio_con_igv[]" id="precio_con_igv[]" value="${element.precio_igv}" readonly  style="border: none; text-align: center;"></td>
            <td><input type="number" class="w-px-135 descuento_${cont}" name="descuento[]" value="${element.descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">0.00</span></td>
            <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fas fa-sync"></i></button></td>
          </tr>`;
          
          detalles = detalles + 1;

          $("#detalles").append(fila);

          array_class_trabajador.push({'id_cont':cont});

          cont++;  evaluar();
        });

        modificarSubtotales();
      } else {
        toastr.error("<h3>Sin productos.</h3> <br> Este registro no tiene productos para mostrar");
        $(".subtotal").html("S/. 0.00");
        $(".igv_comp").html("S/. 0.00");
        $(".total").html("S/. 0.00");
      }            
    } else {
      toastr.error("<h3>Error.</h3> <br> Este registro tiene errores, o esta vacio");
    }
  });    
}
//mostramos el detalle del comprobante de la compras
function ver_detalle_compras(idcompra_proyecto) {
    $("#modal-ver-compras").modal("show");

    $.post("../ajax/compra.php?op=ver_compra", { idcompra_proyecto: idcompra_proyecto }, function (data, status) {
        data = JSON.parse(data);//  console.log(data);
        $(".idproveedor").html("");
        $(".fecha_compra").val("");
        $(".tipo_comprovante").html("");
        $(".serie_comprovante").val("");
        $(".descripcion").val("");

        $(".subtotal").html("");
        $(".igv_comp").html("");
        $(".total").html("");

        if (data.tipo_comprovante == "Factura") {
            $(".igv").val("0.18");
            $(".content-igv").show();
            $(".content-t-comprob").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
            $(".content-descrp").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
            $(".content-comprob").show();
        } else if (data.tipo_comprovante == "Boleta" || data.tipo_comprovante == "Nota_de_venta") {
            $(".igv").val("");
            $(".content-comprob").show();
            $(".content-igv").hide();
            $(".content-t-comprob").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");

            $(".content-descrp").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        } else if (data.tipo_comprovante == "Ninguno") {
            $(".content-comprob").hide();
            $(".content-comprob").val("");
            $(".content-igv").hide();
            $(".content-t-comprob").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
            $(".content-descrp").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
        } else {
            $(".content-comprob").show();
            //$(".content-descrp").removeClass("col-lg-7").addClass("col-lg-4");
        }

        //<!--idproveedor,fecha_compra,tipo_comprovante,serie_comprovante,igv,descripcion, igv_comp, total-->
        $(".idproveedor").html(data.razon_social);
        $(".fecha_compra").val(data.fecha_compra);
        $(".tipo_comprovante").html(data.tipo_comprovante);
        $(".serie_comprovante").val(data.serie_comprovante);
        //$(".igv").val(data.descripcion);
        $(".descripcion").val(data.descripcion);

        $(".subtotal").html(data.subtotal_compras);
        $(".igv_comp").html(data.igv_compras_proyect);
        $(".total").html(data.monto_total);
    });

    $.post("../ajax/compra.php?op=ver_detalle_compras&id_compra=" + idcompra_proyecto, function (r) {
        $("#detalles_compra").html(r);
    });
}

// .......:::::::::::::::::: - FIN - AGREGAR FACURAS, BOLETAS, NOTA DE VENTA, ETC ::::::::::::.......

init();

/**formato_miles */
function formato_miles(num) {
    if (!num || num == "NaN") return "-";
    if (num == "Infinity") return "&#x221e;";
    num = num.toString().replace(/\$|\,/g, "");
    if (isNaN(num)) num = "0";
    sign = num == (num = Math.abs(num));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10) cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) num = num.substring(0, num.length - (4 * i + 3)) + "," + num.substring(num.length - (4 * i + 3));
    return (sign ? "" : "-") + num + "." + cents;
}

function quitar_formato_miles(numero) {
  let inVal = numero.replace(/,/g, '');
  return inVal;
}

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

$(function () {
    // Validar formulario Compras
    // $.validator.setDefaults({
    //     submitHandler: function (e) {
    //         guardaryeditar_compras(e);
    //     },
    // });

    $("#form-compras").validate({
         
        rules: {
            idproveedor: { required: true },
            tipo_comprovante: { required: true },
            serie_comprovante: { minlength: 2 },
            descripcion: { minlength: 4 },
            fecha_compra: { required: true },             
        },
        messages: {
            idproveedor: {
                required: "Por favor debe seleccionar un proveedor.",
            },
            tipo_comprovante: {
                required: "Por favor debe seleccionar tipo de comprobante.",
            },
            serie_comprovante: {
                minlength: "mayor a 2 caracteres",
            },
            descripcion: {
                minlength: "mayor a 4 caracteres",
            },
            fecha_compra: {
                required: "Campo requerido",
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

        submitHandler: function(form) {
            guardaryeditar_compras(form);
        }
    });

    //Validar formulario PROVEEDOR
    // $.validator.setDefaults({
    //     submitHandler: function (e) {
    //         guardarproveedor(e);
    //     },
    // });

    $("#form-proveedor").validate({
        rules: {
            tipo_documento: { required: true },
            num_documento: { required: true, minlength: 6, maxlength: 20 },
            nombre: { required: true, minlength: 6, maxlength: 100 },
            direccion: { minlength: 5, maxlength: 70 },
            telefono: { minlength: 8 },
            c_detracciones: { minlength: 14, maxlength: 14 },
            c_bancaria: { minlength: 14, maxlength: 14 },
            banco: { required: true },
            titular_cuenta: { minlength: 4 },
        },
        messages: {
            tipo_documento: {
                required: "Por favor selecione un tipo de documento",
            },
            num_documento: {
                required: "Ingrese un número de documento",
                minlength: "El número documento debe tener MÍNIMO 6 caracteres.",
                maxlength: "El número documento debe tener como MÁXIMO 20 caracteres.",
            },
            nombre: {
                required: "Por favor ingrese los nombres y apellidos",
                minlength: "El número documento debe tener MÍNIMO 6 caracteres.",
                maxlength: "El número documento debe tener como MÁXIMO 100 caracteres.",
            },
            direccion: {
                minlength: "La dirección debe tener MÍNIMO 5 caracteres.",
                maxlength: "La dirección debe tener como MÁXIMO 70 caracteres.",
            },
            telefono: {
                minlength: "El teléfono debe tener  9 caracteres.",
            },
            c_detracciones: {
                minlength: "El número documento debe tener 14 caracteres.",
            },
            c_bancaria: {
                minlength: "El número documento debe tener 14 caracteres.",
            },
            banco: {
                required: "Por favor  seleccione un banco",
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

        submitHandler: function (e) {
            guardarproveedor(e);
        },
    });

    // validar formulario FACTURA
    // $.validator.setDefaults({
    //     submitHandler: function (e) {
    //         guardaryeditar_factura(e);
    //     },
    // });

    $("#form-agregar-factura").validate({
        rules: {
            codigo: { required: true },
            monto: { required: true },
            fecha_emision: { required: true },
            descripcion_f: { minlength: 1 },
            foto2_i: { required: true }
        },
        messages: {
            forma_pago: {
                codigo: "Por favor ingresar el código",
            },
            monto: {
                required: "Por favor ingresar el monto",
            },
            fecha_emision: {
                required: "Por favor ingresar la fecha de emisión",
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

        submitHandler: function (e) {
            guardaryeditar_factura(e);
        },
    });

    // Validar formulario PAGOS
    // $.validator.setDefaults({
    //     submitHandler: function (e) {
    //         guardaryeditar_pago(e);
    //     },
    // });

    $("#form-servicios-pago").validate({
        rules: {
            forma_pago: { required: true },
            tipo_pago: { required: true },
            banco_pago: { required: true },
            fecha_pago: { required: true },
            monto_pago: { required: true },
            numero_op_pago: { minlength: 1 },
            descripcion_pago: { minlength: 1 },
            titular_cuenta_pago: { minlength: 1 }
        },
        messages: {
            forma_pago: {
                required: "Por favor selecione una forma de pago",
            },
            tipo_pago: {
                required: "Por favor selecione un tipo de pago",
            },
            banco_pago: {
                required: "Por favor selecione un banco",
            },
            fecha_pago: {
                required: "Por favor ingresar una fecha",
            },
            monto_pago: {
                required: "Por favor ingresar el monto a pagar",
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

        submitHandler: function (e) {
            guardaryeditar_pago(e);
        },
    });
});

// validacion formulario COMPROBANTE
$(function () {
    // $.validator.setDefaults({
    //     submitHandler: function (e) {
    //       guardaryeditar_comprobante(e);
    //     },
    // });

    $("#form-comprobante").validate({
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

        submitHandler: function (e) {
            guardaryeditar_comprobante(e);
        },
    });
});

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
                                $("#doc1_ver").html('<iframe src="../dist/comprobantes_compras/' + dr + '" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

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
                                            $("#doc1_ver").html('<img src="../dist/comprobantes_compras/' + dr + '" alt="" width="50%" >');

                                            toastr.success("Documento vizualizado correctamente!!!");
                                        } else {
                                            if (
                                                extrae_extencion(dr) == "docx" ||
                                                extrae_extencion(dr) == "docm" ||
                                                extrae_extencion(dr) == "dotx" ||
                                                extrae_extencion(dr) == "dotm" ||
                                                extrae_extencion(dr) == "doc" ||
                                                extrae_extencion(dr) == "dot"
                                            ) {
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

function extrae_extencion(filename) {
    return filename.split(".").pop();
}

//validando excedentes
function validando_excedentes() {
  var totattotal = localStorage.getItem('monto_total_p');
  var monto_total_dep = localStorage.getItem('monto_total_dep');
  var monto_entrada = $("#monto_pago").val();
  var total_suma=parseFloat(monto_total_dep)+parseFloat(monto_entrada);
  var debe =totattotal-monto_total_dep
  console.log(typeof total_suma);
  if (total_suma>totattotal) {
    toastr.error('ERROR monto excedido al total del monto a pagar!');
  }else{
    toastr.success('Monto Aceptado.'); 
  }
}

// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
    $("#search").hide();
  
    $("#charge").show();
  
    let tipo_doc = $("#tipo_documento").val();
  
    let dni_ruc = $("#num_documento").val(); 
     
    if (tipo_doc == "DNI") {
  
      if (dni_ruc.length == "8") {
  
        $.post("../ajax/compra.php?op=reniec", { dni: dni_ruc }, function (data, status) {
  
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
          $.post("../ajax/compra.php?op=sunat", { ruc: dni_ruc }, function (data, status) {
  
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

// ver imagen grande del producto agregado a la compra
function ver_img_material(img, nombre) {
    $("#ver_img_material").attr("src", `../dist/img/materiales/${img}`); $(".nombre-img-material").html(nombre);
    $("#modal-ver-img-material").modal('show');
}