var tabla;

//Función que se ejecuta al inicio
function init() {
  tbla_principal();

  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");

  $("#mRecurso").addClass("active");

  $("#lAllProveedor").addClass("active");

  //Mostramos los BANCOS
  $.post("../ajax/all_proveedor.php?op=select2Banco", function (r) { if (r.status) { $("#banco").html(r); } else { console.log(r.responseJSON);}  });

  $("#guardar_registro").on("click", function (e) { $("#submit-form-proveedor").submit(); });

  //Initialize Select2 Elements
  $("#banco").select2({  theme: "bootstrap4", placeholder: "Selecione banco", allowClear: true, });

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
  $("#banco").val("").trigger("change");
  $("#titular_cuenta").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function tbla_principal() {
  tabla = $("#tabla-proveedores").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: ["copyHtml5", "excelHtml5", "pdf", "colvis"],
    ajax: {
      url: "../ajax/all_proveedor.php?op=tbla_principal",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
        // Swal.fire(`Error!`, `<div class="text-left">${e.responseText}</div>`, "error");
        Swal.fire(`Error en la Base de Datos 😅!`, `Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
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
      lengthMenu: "Mostrar: _MENU_ registros",
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
  }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);

  $.ajax({
    url: "../ajax/all_proveeedor.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      e = JSON.parse(e); console.log(e);
      if (datos == "ok") {
        toastr.success("proveedor registrado correctamente");

        tabla.ajax.reload();

        limpiar();

        $("#modal-agregar-proveedor").modal("hide");
      } else {
        toastr.error(datos);
      }
    },
    error: function (jqXhr) {
      console.log(jqXhr);
      if (jqXhr.status == 404) {
        Swal.fire(`Error 404 😅!`, `<h5>Archivo no encontrado</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
      } else if(jqXhr.status == 500) {
        Swal.fire(`Error 500 😅!`, `<h5>Error Interno del Servidor</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
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
    data = JSON.parse(data);  console.log(data);

    if (data.status) {
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $("#tipo_documento option[value='" + data.data.tipo_documento + "']").attr("selected", true);
      $("#nombre").val(data.data.razon_social);
      $("#num_documento").val(data.data.ruc);
      $("#direccion").val(data.data.direccion);
      $("#telefono").val(data.data.telefono);
      $("#banco").val(data.data.idbancos).trigger("change");
      $("#c_bancaria").val(data.data.cuenta_bancaria);
      $("#cci").val(data.data.cci);
      $("#c_detracciones").val(data.data.cuenta_detracciones);
      $("#titular_cuenta").val(data.data.titular_cuenta);
      $("#idproveedor").val(data.data.idproveedor);
    } else {
      //Swal.fire(`Error en la Base de Datos 😅!`, `Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
      Swal.fire(`Error ${data.code_error}!`, `<div class="text-left">${data.message}  ${data.data} </div>`, "error");
      console.log('Error brutal: 👇');console.log(data);
    }    
  }).fail(
    function(e) { 
      console.log(e);
      if (e.status == 404) {
        Swal.fire(`Error 404 😅!`, `<h5>Archivo no encontrado</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
      } else if(e.status == 500) {
        Swal.fire(`Error 500 😅!`, `<h5>Error Interno del Servidor</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
      }       
    }
  );
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
      }).fail(
        function(e) { 
          console.log(e);    
          if (e.status == 404) {
            Swal.fire(`Error 404 😅!`, `<h5>Archivo no encontrado</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
          } else if(e.status == 500) {
            Swal.fire(`Error 500 😅!`, `<h5>Error Interno del Servidor</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
          }         
        }
      );
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
      }).fail(
        function(e) { 
          console.log(e);    
          if (e.status == 404) {
            Swal.fire(`Error 404 😅!`, `<h5>Archivo no encontrado</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
          } else if(e.status == 500) {
            Swal.fire(`Error 500 😅!`, `<h5>Error Interno del Servidor</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
          }
        }
      );
    }
  });
}

//Función para elimar registros
function eliminar(idproveedor) {
  
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
      }).fail(
        function(e) { 
          console.log(e);    
          if (e.status == 404) {
            Swal.fire(`Error 404 😅!`, `<h5>Archivo no encontrado</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
          } else if(e.status == 500) {
            Swal.fire(`Error 500 😅!`, `<h5>Error Interno del Servidor</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
          }         
        }
      );   

    }else if (result.isDenied) {
      //op=eliminar
      $.post("../ajax/all_proveedor.php?op=eliminar", { idproveedor: idproveedor }, function (e) {
        Swal.fire("Eliminado!", "Tu proveedor ha sido eliminado.", "success");
        tabla.ajax.reload();
      }).fail(
        function(e) { 
          console.log(e);    
          if (e.status == 404) {
            Swal.fire(`Error 404 😅!`, `<h5>Archivo no encontrado</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
          } else if(e.status == 500) {
            Swal.fire(`Error 500 😅!`, `<h5>Error Interno del Servidor</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
          }         
        }
      );
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
      data = JSON.parse(data); // console.log(data);

      if (data.status) {
        $(".chargue-format-1").html("Cuenta Bancaria");
        $(".chargue-format-2").html("CCI");
        $(".chargue-format-3").html("Cuenta Detracciones");

        $("#c_bancaria").prop("readonly", false);
        $("#cci").prop("readonly", false);
        $("#c_detracciones").prop("readonly", false);

        var format_cta = decifrar_format_banco(data.data.formato_cta);
        var format_cci = decifrar_format_banco(data.data.formato_cci);
        var formato_detracciones = decifrar_format_banco(data.data.formato_detracciones);

        $("#c_bancaria").inputmask(`${format_cta}`);
        $("#cci").inputmask(`${format_cci}`);
        $("#c_detracciones").inputmask(`${formato_detracciones}`);
      } else {
        Swal.fire(`Error en la Base de Datos 😅!`, `Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
        //Swal.fire(`Error ${data.code_error}!`, `<div class="text-left">${data.message}  ${data.data} </div>`, "error");
        console.log('Error brutal: 👇');console.log(data);
      }      
    }).fail(
      function(e) { 
        console.log(e);    
        if (e.status == 404) {
          Swal.fire(`Error 404 😅!`, `<h5>Archivo no encontrado</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
        } else if(e.status == 500) {
          Swal.fire(`Error 500 😅!`, `<h5>Error Interno del Servidor</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
        }         
      }
    );
  }
}


init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M S  :::::::::::::::::::::::::::::::::::::::..

$(function () {
  $.validator.setDefaults({
    submitHandler: function (e) {
      guardaryeditar(e);
    },
  });

  $("#form-proveedor").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      tipo_documento: { required: true },
      num_documento:  { required: true, minlength: 6, maxlength: 20 },
      nombre:         { required: true, minlength: 6, maxlength: 100 },
      direccion:      { minlength: 5, maxlength: 150 },
      telefono:       { minlength: 8 },
      c_detracciones: { minlength: 6,  },
      c_bancaria:     { minlength: 6,  },
      cci:            { minlength: 6,  },
      banco:          { required: true },
      titular_cuenta: { minlength: 4 },
    },
    messages: {
      tipo_documento: { required: "Campo requerido.",  },
      num_documento:  { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre:         {required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "como MÁXIMO 100 caracteres.", },
      direccion:      { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 150 caracteres.", },
      telefono:       { minlength: "MÍNIMO 9 caracteres.", },
      c_detracciones: { minlength: "MÍNIMO 6 caracteres", },
      c_bancaria:     { minlength: "MÍNIMO 6 caracteres", },
      cci:            { minlength: "MÍNIMO 6 caracteres", },
      banco:          { required: "Campo requerido.", },
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
  });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

 
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