var tabla;

//Función que se ejecuta al inicio
function init() {
  listar();

  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  $("#lAllProveedor").addClass("active");

  //Mostramos los BANCOS
  $.post("../ajax/all_proveedor.php?op=select2Banco", function (r) {
    $("#banco").html(r);
  });

  $("#guardar_registro").on("click", function (e) {
    $("#submit-form-proveedor").submit();
  });

  //Initialize Select2 Elements
  $("#banco").select2({
    theme: "bootstrap4",
    placeholder: "Selecione banco",
    allowClear: true,
  });

  $("#banco").val("null").trigger("change");

  // Formato para telefono
  $("[data-mask]").inputmask();
}

//Función limpiar
function limpiar() {
  $("#idproveedor").val("");
  $("#tipo_documento option[value='RUC']").attr("selected", true);
  $("#nombre").val("");
  $("#num_documento").val("");
  $("#direccion").val("");
  $("#telefono").val("");
  $("#c_bancaria").val("");
  $("#cci").val("");
  $("#c_detracciones").val("");
  //$("#banco").val("");
  // $("#banco option[value='BCP']").attr("selected", true);
  $("#banco").val("").trigger("change");
  $("#titular_cuenta").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

//Función Listar
function listar() {
  tabla = $("#tabla-proveedores")
    .dataTable({
      responsive: true,
      lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]], //mostramos el menú de registros a revisar
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf", "colvis"],
      ajax: {
        url: "../ajax/all_proveedor.php?op=listar",
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
      },
      createdRow: function (row, data, ixdex) {    
  
        // columna: #0
        if (data[0] != '') {
          $("td", row).eq(0).addClass("text-center");   
           
        }
        // columna: #0
        if (data[1] != '') {
          $("td", row).eq(1).addClass("text-nowrap");   
            
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

function guardaryeditar(e) {
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
        toastr.success("proveedor registrado correctamente");

        tabla.ajax.reload();

        limpiar();

        $("#modal-agregar-proveedor").modal("hide");
      } else {
        toastr.error(datos);
      }
    },
  });
}

function mostrar(idproveedor) {
  limpiar();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-proveedor").modal("show");

  $.post("../ajax/all_proveedor.php?op=mostrar", { idproveedor: idproveedor }, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#tipo_documento option[value='" + data.tipo_documento + "']").attr("selected", true);
    $("#nombre").val(data.razon_social);
    $("#num_documento").val(data.ruc);
    $("#direccion").val(data.direccion);
    $("#telefono").val(data.telefono);
    // $("#banco option[value='"+data.idbancos+"']").attr("selected", true);
    $("#banco").val(data.idbancos).trigger("change");
    $("#c_bancaria").val(data.cuenta_bancaria);
    $("#cci").val(data.cci);
    $("#c_detracciones").val(data.cuenta_detracciones);
    $("#titular_cuenta").val(data.titular_cuenta);
    $("#idproveedor").val(data.idproveedor);
  });
}

//Función para desactivar registros
function desactivar(idproveedor) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  el proveedor?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/all_proveedor.php?op=desactivar", { idproveedor: idproveedor }, function (e) {
        Swal.fire("Desactivado!", "Tu proveedor ha sido desactivado.", "success");

        tabla.ajax.reload();
      });
    }
  });
}

//Función para activar registros
function activar(idproveedor) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  el proveedor?",
    text: "Este proveedor tendra acceso al sistema",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/all_proveedor.php?op=activar", { idproveedor: idproveedor }, function (e) {
        Swal.fire("Activado!", "Tu proveedor ha sido activado.", "success");

        tabla.ajax.reload();
      });
    }
  });
}
//Función para elimar registros
function eliminar(idproveedor) {
   //----------------------------
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
   //op=desactivar
    $.post("../ajax/all_proveedor.php?op=desactivar", { idproveedor: idproveedor }, function (e) {
      Swal.fire("Desactivado!", "Tu proveedor ha sido desactivado.", "success");

      tabla.ajax.reload();
    });
  

  }else if (result.isDenied) {
   //op=eliminar
   $.post("../ajax/all_proveedor.php?op=eliminar", { idproveedor: idproveedor }, function (e) {
    Swal.fire("Eliminado!", "Tu proveedor ha sido eliminado.", "success");

    tabla.ajax.reload();
  });

  }

});
}
// damos formato a: Cta, CCI
function formato_banco() {
  if ($("#banco").select2("val") == null || $("#banco").select2("val") == "" || $("#banco").select2("val") == "1" ) {
    $("#c_bancaria").prop("readonly", true);
    $("#cci").prop("readonly", true);
    $("#c_detracciones").prop("readonly", true);
  } else {
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-3").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');

    

    $.post("../ajax/all_proveedor.php?op=formato_banco", { 'idbanco': $("#banco").select2("val") }, function (data, status) {
      data = JSON.parse(data);
      // console.log(data);

      $(".chargue-format-1").html("Cuenta Bancaria");
      $(".chargue-format-2").html("CCI");
      $(".chargue-format-3").html("Cuenta Detracciones");

      $("#c_bancaria").prop("readonly", false);
      $("#cci").prop("readonly", false);
      $("#c_detracciones").prop("readonly", false);

      var format_cta = decifrar_format_banco(data.formato_cta);
      var format_cci = decifrar_format_banco(data.formato_cci);
      var formato_detracciones = decifrar_format_banco(data.formato_detracciones);
      // console.log(format_cta, formato_detracciones);

      $("#c_bancaria").inputmask(`${format_cta}`);
      $("#cci").inputmask(`${format_cci}`);
      $("#c_detracciones").inputmask(`${formato_detracciones}`);
    });
  }
}

function decifrar_format_banco(format) {

  var array_format =  format.split("-"); var format_final = "";

  array_format.forEach((item, index)=>{

    for (let index = 0; index < parseInt(item); index++) { format_final = format_final.concat("9"); }   

    if (parseInt(item) != 0) { format_final = format_final.concat("-"); }
  });

  var ultima_letra = format_final.slice(-1);
   
  if (ultima_letra == "-") { format_final = format_final.slice(0, (format_final.length-1)); }

  return format_final;
}

init();

$(function () {
  $.validator.setDefaults({
    submitHandler: function (e) {
      guardaryeditar(e);
    },
  });

  $("#form-proveedor").validate({
    rules: {
      tipo_documento: { required: true },
      num_documento: { required: true, minlength: 6, maxlength: 20 },
      nombre: { required: true, minlength: 6, maxlength: 100 },
      direccion: { minlength: 5, maxlength: 150 },
      telefono: { minlength: 8 },
      c_detracciones: { minlength: 6,  },
      c_bancaria: { minlength: 6,  },
      banco: { required: true },
      titular_cuenta: { minlength: 4 },

      // terms: { required: true },
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
  });
});

// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_documento").val();

  let dni_ruc = $("#num_documento").val(); 
   
  if (tipo_doc == "DNI") {

    if (dni_ruc.length == "8") {

      $.post("../ajax/persona.php?op=reniec", { 'dni': dni_ruc }, function (data, status) {

        data = JSON.parse(data);  console.log(data);

        if (data == null) {

          $("#search").show();
  
          $("#charge").hide();
  
          toastr.error("Verifique su conexion a internet o el sistema de BUSQUEDA esta en mantenimiento.");
          
        } else {
          if (data.success == false) {

            $("#search").show();

            $("#charge").hide();

            toastr.error("Es probable que el sistema de busqueda esta en mantenimiento o los datos no existe en la RENIEC!!!");

          } else {

            $("#search").show();

            $("#charge").hide();

            $("#nombre").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);
            $("#titular_cuenta").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

            toastr.success("Persona encontrada!!!!");
          }
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
        $.post("../ajax/persona.php?op=sunat", { 'ruc': dni_ruc }, function (data, status) {

          data = JSON.parse(data);    console.log(data);

          if (data == null) {
            $("#search").show();
    
            $("#charge").hide();
    
            toastr.error("Verifique su conexion a internet o el sistema de BUSQUEDA esta en mantenimiento.");
            
          } else {

            if (data.success == false) {

              $("#search").show();

              $("#charge").hide();

              toastr.error("Datos no encontrados en la SUNAT!!!");
              
            } else {

              if (data.estado == "ACTIVO") {

                $("#search").show();

                $("#charge").hide();

                data.razonSocial == null ? $("#nombre").val(data.nombreComercial) : $("#nombre").val(data.razonSocial);

                data.razonSocial == null ? $("#titular_cuenta").val(data.nombreComercial) : $("#titular_cuenta").val(data.razonSocial);

                var departamento = (data.departamento == null ? "" : data.departamento); 
                var provincia = (data.provincia == null ? "" : data.provincia);
                var distrito = (data.distrito == null ? "" : data.distrito);                

                data.direccion == null ? $("#direccion").val(`${departamento} - ${provincia} - ${distrito}`) : $("#direccion").val(data.direccion);

                toastr.success("Persona encontrada!!");

              } else {

                toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

                $("#search").show();

                $("#charge").hide();

                $("#nombre").val(data.razonSocial);

                data.razonSocial == null ? $("#nombre").val(data.nombreComercial) : $("#nombre").val(data.razonSocial);

                data.razonSocial == null ? $("#titular_cuenta").val(data.nombreComercial) : $("#titular_cuenta").val(data.razonSocial);
                
                data.direccion == null ? $("#direccion").val(`${data.departamento} - ${data.provincia} - ${data.distrito}`) : $("#direccion").val(data.direccion);

              }
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