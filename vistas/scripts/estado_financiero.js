var tabla_principal; 

//Función que se ejecuta al inicio
function init() {

  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#mContableFinanciero").addClass("active");

  $("#lEstadoFinanciero").addClass("active bg-primary");
  
  listar_tbla_principal(localStorage.getItem('nube_idproyecto'));

  // efectuamos SUBMIT  registro de: RECIBOS POR HONORARIOS
  $("#guardar_registro_color").on("click", function (e) { $("#submit-form-color").submit();  });

  //Initialize Select2 unidad
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar una forma de pago", allowClear: true, });

  $('#fecha_proyeccion').datepicker({ format: "dd-mm-yyyy", language: "es", autoclose: true, clearBtn: true,  weekStart: 0, orientation: "bottom auto", todayBtn: true });

  formato_miles_input('.input_ef');

  // Formato para telefono
  $("[data-mask]").inputmask();    
} 

// click input group para habilitar: datepiker
$('.click-btn-fecha-proyeccion').on('click', function (e) {$('#fecha_proyeccion').focus().select(); });

// ══════════════════════════════════════ ESTADO FINANCIERO ══════════════════════════════════════ 

function show_hide_span_input_ef(flag){

  if (flag == 1) {
    // ocultamos los span
    $(".span_ef").show();
    // mostramos los inputs
    $(".input_ef").hide();

    // ocultamos el boton editar
    $("#btn-editar-ef").show();
    // mostramos el boton guardar
    $("#btn-guardar-ef").hide();
  } else if (flag == 2) {
    
    // ocultamos los span
    $(".span_ef").hide();
    // mostramos los inputs
    $(".input_ef").show();

    // ocultamos el boton editar
    $("#btn-editar-ef").hide();
    // mostramos el boton guardar
    $("#btn-guardar-ef").show();
  }  
}

//Función limpiar
function limpiar_form_estado_financiero() {  

  $("#monto").val("");
  $("#forma_pago").val("").trigger("change"); 
  $("#descripcion").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar - tabla principal
function listar_tbla_principal(nube_idproyecto) {

  $('.sueldo_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  var total_pago_acumulado_hoy = 0, pago_total_x_proyecto = 0, saldo_total = 0;

  $.post("../ajax/pago_administrador.php?op=mostrar_total_tbla_principal", { 'nube_idproyecto': nube_idproyecto }, function (data, status) {
    data = JSON.parse(data);  console.log(data); 
    // $('.sueldo_total_tbla_principal').html(`<sup>S/</sup> <b>${formato_miles(data.sueldo_mesual_x_proyecto)}</b>`); 
  });   
}

//Función para guardar o editar
function guardar_y_editar_estado_financiero(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-pagos-x-mes")[0]);

  $.ajax({
    url: "../ajax/estado_financiero.php?op=guardar_y_editar_estado_financiero",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e); console.log(e);

        if (e.estado == true) {    

          Swal.fire("Correcto!", "Pago guardado correctamente", "success");	      
          
          show_hide_span_input_ef(flag);

        }else{
          ver_errores(e);			 
        }
      } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

      
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
    }
  });
}

// mostramos loa datos para editar: "pagos por mes"
function mostrar_pagos_x_mes(id) {

  limpiar_pago_x_mes();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  $("#modal-agregar-pago-trabajdor").modal('show');

  $.post("../ajax/pago_administrador.php?op=mostrar_pagos_x_mes", { 'idpagos_x_mes_administrador': id }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 
    
    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $('#idpagos_x_mes_administrador').val(data.idpagos_x_mes_administrador);
    $("#monto").val(data.monto);
    $("#forma_pago").val(data.forma_de_pago).trigger("change"); 
    $("#descripcion").val(data.descripcion); 

    //validamoos BAUCHER - DOC 1
    if (data.baucher == "" || data.baucher == null  ) {

      $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

      $("#doc1_nombre").html('');

      $("#doc_old_1").val(""); $("#doc1").val("");

    } else {

      $("#doc_old_1").val(data.baucher); 

      $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(data.baucher)}</i></div></div>`);
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.baucher) == "pdf" ) {

        $("#doc1_ver").html('<iframe src="../dist/pago_administrador/baucher_deposito/'+data.baucher+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

      }else{
        if (
          extrae_extencion(data.baucher) == "jpeg" || extrae_extencion(data.baucher) == "jpg" || extrae_extencion(data.baucher) == "jpe" ||
          extrae_extencion(data.baucher) == "jfif" || extrae_extencion(data.baucher) == "gif" || extrae_extencion(data.baucher) == "png" ||
          extrae_extencion(data.baucher) == "tiff" || extrae_extencion(data.baucher) == "tif" || extrae_extencion(data.baucher) == "webp" ||
          extrae_extencion(data.baucher) == "bmp" || extrae_extencion(data.baucher) == "svg" ) {

          $("#doc1_ver").html(`<img src="../dist/pago_administrador/baucher_deposito/${data.baucher}" alt="" width="50%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
    }     
  });
}

// ══════════════════════════════════════ PROYECIONES ══════════════════════════════════════ 

//Función limpiar
function limpiar_form_proyecciones() {  

  $("#monto").val("");
  $("#forma_pago").val("").trigger("change"); 
  $("#descripcion").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function desactivar_pago_x_mes(id) {

  var id_fechas_mes = $('#idfechas_mes_pagos_administrador_pxm').val();

  Swal.fire({
    title: "¿Está Seguro de ANULAR el pago?",
    text: "Al anularlo este pago, el monto NO se contara como un deposito realizado.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/pago_administrador.php?op=desactivar_pago_x_mes", { 'idpagos_x_mes_administrador': id }, function (e) {

        if (e == "ok") {
          listar_tbla_principal(localStorage.getItem('nube_idproyecto')); 
          reload_table_pagos_x_mes(id_fechas_mes);
          Swal.fire("Anulado!", "Tu registro ha sido Anulado.", "success");
        } else {
          Swal.fire("Error!", e, "error");
        }        
      });      
    }
  });  
}

function activar_pago_x_mes(id) {

  var id_fechas_mes = $('#idfechas_mes_pagos_administrador_pxm').val();

  Swal.fire({
    title: "¿Está Seguro de ReActivar el pago?",
    text: "Al ReActivarlo este pago, el monto contara como un deposito realizado.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/pago_administrador.php?op=activar_pago_x_mes", { 'idpagos_x_mes_administrador': id }, function (e) {

        if (e == "ok") {
          listar_tbla_principal(localStorage.getItem('nube_idproyecto')); 
          reload_table_pagos_x_mes(id_fechas_mes);
          Swal.fire("ReActivado!", "Tu registro ha sido ReActivado.", "success");
        } else {
          Swal.fire("Error!", e, "error");
        }        
      });      
    }
  });
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  $.validator.setDefaults({ submitHandler: function (e) { guardar_y_editar_pagos_x_mes(e); }, });

  $("#form").validate({
    rules: {
      forma_pago: { required: true},
      monto: {required: true, minlength: 1 },
      descripcion: { minlength: 4 },
    },
    messages: {
      forma_pago: {
        required: "Campo requerido."
      },
      monto: {
        required: "Campo requerido.",
        minlength: "MINIMO 1 dígito.",
      },
      descripcion: {
        minlength: "MINIMO 4 caracteres.",
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
  });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


