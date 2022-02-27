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
  $("#forma_pago").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar una forma de pago",
    allowClear: true,
  });
   
  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

//Función limpiar
function limpiar_pago_x_mes() {  

  $("#monto").val("");
  $("#forma_pago").val("").trigger("change"); 
  $("#descripcion").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

//Función Listar - tabla principal
function listar_tbla_principal(nube_idproyecto) {

  $('.sueldo_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');
  $('.deposito_total_tbla_principal').html('<i class="fas fa-spinner fa-pulse fa-sm"></i>');

  var total_pago_acumulado_hoy = 0, pago_total_x_proyecto = 0, saldo_total = 0;

  tabla_principal=$('#tabla-principal').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[4] != '') {
        $("td", row).eq(4).css({
          "text-align": "center"
        });
      }     

      // columna: sueldo mensual
      if (data[5] != '') {
        $("td", row).eq(5).css({
          "text-align": "right"
        });
      }      
    },
    "language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      }
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();

  $.post("../ajax/pago_administrador.php?op=mostrar_total_tbla_principal", { 'nube_idproyecto': nube_idproyecto }, function (data, status) {
    data = JSON.parse(data);  console.log(data); 
    // $('.sueldo_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(data.sueldo_mesual_x_proyecto)}</b>`);
    // $('.pago_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(pago_total_x_proyecto)}</b>`);
    // $('.pago_hoy_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(total_pago_acumulado_hoy)}</b>`);
    // $('.deposito_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(data.monto_total_depositado_x_proyecto)}</b>`);  
    // $('.saldo_total_tbla_principal').html(`<sup>S/.</sup> <b>${formato_miles(saldo_total)}</b>`);   
  }); 

  
}

//Función para guardar o editar
function guardar_y_editar_pagos_x_mes(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-pagos-x-mes")[0]);

  $.ajax({
    url: "../ajax/pago_administrador.php?op=guardar_y_editar_pagos_x_mes",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      datos = JSON.parse(datos); console.log(datos);

      if (datos.estado) {    

        Swal.fire("Correcto!", "Pago guardado correctamente", "success");	      
         
				limpiar_pago_x_mes();

        $("#modal").modal("hide");        

			}else{

        Swal.fire("Error!", datos, "error");				 
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

          if (percentComplete === 100) {

            setTimeout(l_m, 600);
          }
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


function l_m(){
  
  // limpiar();
  $("#barra_progress").css({"width":'0%'});
  $("#barra_progress").text("0%"); 
}

init();

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

      $(element).addClass("is-invalid");
    },

    unhighlight: function (element, errorClass, validClass) {

      $(element).removeClass("is-invalid").addClass("is-valid");
             
    },
  });
});


// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..



// quitamos las comas de miles de un numero
function quitar_formato_miles(numero) {
  let inVal = numero.replace(/,/g, '');
  return inVal;
}

// damos formato de miles a un numero
function formato_miles(num) {
  if (!num || num == "NaN") return "0.00";
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


// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {

  let splits = fecha.split("-"); //console.log(splits);

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}

// convierte de una fecha(aa-mm-dd): 23-12-2021 a una fecha(dd-mm-aa): 2021-12-23
function format_a_m_d(fecha) {

  let splits = fecha.split("-"); //console.log(splits);

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}


