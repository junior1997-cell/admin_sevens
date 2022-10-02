
var host = window.location.host == 'localhost'? `http://localhost/admin_sevens/dist/docs/valorizacion/documento/` : `${window.location.origin}/dist/docs/valorizacion/documento/` ;

var array_fechas_q_s = [];

var cant_valorizaciones = 0;

var fecha_i_r = "", fecha_f_r = "", i_r = "";

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lValorizacion").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));
  $("#idproyecto_q_s").val(localStorage.getItem('nube_idproyecto'));
  
  ver_quincenas(localStorage.getItem('nube_idproyecto'));  

  $("#guardar_registro").on("click", function (e) {  $("#submit-form-valorizacion").submit(); });
  $("#guardar_registro_resumen_valorizacion").on("click", function (e) {  $("#submit-form-resumen-valorizacion").submit(); });

  //Initialize Select2 Elements
  $("#numero_q_s_resumen").select2({ theme: "bootstrap4", placeholder: "Selecione Valorizacion", allowClear: true, });
  
  // Formato para telefono
  $("[data-mask]").inputmask();  

}

$("#doc7_i").click(function() {  $('#doc7').trigger('click'); });
$("#doc7").change(function(e) {  addImageApplication(e,$("#doc7").attr("id")) });

// Eliminamos el doc 6
function doc7_eliminar() {

	$("#doc7").val("");

	$("#doc7_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

	$("#doc7_nombre").html("");
}

function mostrar_form_table(estados) {
  if (estados == '1') {
    $(".div-todos-los-docs").show();
    $(".div-docs-por-valorizacion").hide();    
  } else if (estados == '2') {
    $(".div-todos-los-docs").hide();
    $(".div-docs-por-valorizacion").show();  
  }
}

// ver las echas de quincenas
function ver_quincenas(nube_idproyecto) {

  $('#lista_quincenas').html('<i class="fas fa-spinner fa-pulse fa-2x"></i>'); //console.log(nube_idproyecto);

  $.post("../ajax/valorizacion.php?op=listarquincenas", { 'nube_idproyecto': nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); console.log(e);    

    $('#lista_quincenas').html('');

    // VALIDAMOS LAS FECHAS DE QUINCENA
    if (e.data) { 
        
      if (e.data.fecha_valorizacion == "quincenal") {

        $(".h1-titulo").html("Valorización - Quincenal");
        var fechas_btn = fechas_valorizacion_quincena(e.data.fecha_inicio, e.data.fecha_fin); 
        //console.log(fechas_btn);  

        fechas_btn.forEach((key, indice) => {
          $('#lista_quincenas').append(` <button id="boton-${key.num_q_s}" type="button" class="mb-2 btn bg-gradient-info text-center btn-sm" onclick="buscar_documento('${format_a_m_d(key.fecha_inicio)}', '${format_a_m_d(key.fecha_fin)}', '${key.num_q_s}');"><i class="far fa-calendar-alt"></i> Valorización ${key.num_q_s}<br>${key.fecha_inicio} // ${key.fecha_fin}</button>`)
          array_fechas_q_s.push({ 'fecha_inicio':format_a_m_d(key.fecha_inicio), 'fecha_fin':format_a_m_d(key.fecha_fin), 'num_q_s': key.num_q_s, });
          cant_valorizaciones = key.num_q_s;
        });
        
        tbla_resumen_q_s(nube_idproyecto);
      } else {

        if (e.data.fecha_valorizacion == "mensual") {

          $(".h1-titulo").html("Valorización - Mensual");

          var fechas_btn = fechas_valorizacion_mensual(e.data.fecha_inicio, e.data.fecha_fin); 
          //console.log(fechas_btn);  

          fechas_btn.forEach((key, indice) => {
            $('#lista_quincenas').append(` <button id="boton-${key.num_q_s}" type="button" class="mb-2 btn bg-gradient-info text-center btn-sm" onclick="buscar_documento('${format_a_m_d(key.fecha_inicio)}', '${format_a_m_d(key.fecha_fin)}', '${key.num_q_s}');"><i class="far fa-calendar-alt"></i> Valorización ${key.num_q_s}<br>${key.fecha_inicio} // ${key.fecha_fin}</button>`)
            array_fechas_q_s.push({ 'fecha_inicio':format_a_m_d(key.fecha_inicio), 'fecha_fin':format_a_m_d(key.fecha_fin), 'num_q_s': key.num_q_s, });
            cant_valorizaciones = key.num_q_s;
          });

          tbla_resumen_q_s(nube_idproyecto);
        } else {

          if (e.data.fecha_valorizacion == "al finalizar") {

            $(".h1-titulo").html("Valorización - Al finalizar");

            $('#lista_quincenas').append(` <button id="boton-0" type="button" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="buscar_documento('${e.data.fecha_inicio}', '${e.data.fecha_fin}', '0');"><i class="far fa-calendar-alt"></i> Valorización 1<br>${format_d_m_a(e.data.fecha_inicio)} // ${format_d_m_a(e.data.fecha_fin)}</button>`)
            $("#numero_q_s_resumen").append(`<option value="${i+1} ${fecha_ii} ${fecha_ff}" >Valorización ${i+1}</option>`);
            array_fechas_q_s.push({'fecha_inicio':fecha_ii, 'fecha_fin':fecha_ff, 'num_q_s': i+1, });
            cant_valorizaciones = i+1;

            tbla_resumen_q_s(nube_idproyecto);
          } else {
            $('#lista_quincenas').html(`<div class="info-box shadow-lg w-600px"> 
              <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span> 
              <div class="info-box-content"> 
                <span class="info-box-text">Alerta</span> 
                <span class="info-box-number">No has definido los bloques de fechas del proyecto. <br>Ingresa al ESCRITORIO y EDITA tu proyecto selecionado.</span> 
              </div> 
            </div>`);
          }
        }
      }     

    } else {
      $('#lista_quincenas').html('<div class="info-box shadow-lg w-300px">'+
        '<span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>'+
        '<div class="info-box-content">'+
          '<span class="info-box-text">Alerta</span>'+
          '<span class="info-box-number">Las fechas del proyecto <br> es menor de 1 día.</span>'+
        '</div>'+
      '</div>');
    }    
    //console.log(fecha);
  });
}

//Función limpiar
function limpiar() {
  $("#doc7_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc7_nombre").html('');
  $("#doc_old_7").val(""); 
  $("#doc7").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-valorizacion")[0]);

  $.ajax({
    url: "../ajax/valorizacion.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {   
      try {
        e = JSON.parse(e);
        if (e.status == true) {	

          Swal.fire("Correcto!", "Documento guardado correctamente", "success");	
          limpiar();
          mostrar_form_table(2);
          buscar_documento(localStorage.getItem('fecha_i'), localStorage.getItem('fecha_f'), localStorage.getItem('i'));
          $("#modal-agregar-valorizacion").modal("hide");

        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }      
      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
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
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  });
      $("#barra_progress").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function modal_comprobante(doc_valorizacion, indice, nombre, numero_q_s,) {
  $(".nombre_documento").html("");
  // exraemos la fecha de HOY
  var tiempoTranscurrido = Date.now();
  var hoy = new Date(tiempoTranscurrido);
  var format = hoy.toLocaleDateString().split("/"); //console.log(format);
  $(".nombre_documento").html(`${indice} ${nombre} - ${localStorage.getItem('nube_nombre_proyecto')} -  valorización ${numero_q_s}`);
  $("#modal-ver-comprobante").modal("show");

  if (doc_valorizacion=='' || doc_valorizacion==null) {
    $('#ver-documento').html(
      `<div class="col-lg-6"><a class="btn btn-warning btn-block btn-xs disabled" type="button" href="#"><i class="fas fa-download"></i> Descargar</a></div>
      <div class="col-lg-6 mb-4"><a class="btn btn-info btn-block btn-xs disabled" href="#" type="button"><i class="fas fa-expand"></i> Ver completo</a></div>
      <div class="col-lg-12 ">
        <div class="embed-responsive" style="padding-bottom:30%" >
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="Alerta" aria-hidden="true">×</button><h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
            No hay un documento para ver. Edite este registro y vuelva a intentar.
          </div>
        </div>
      </div>`
    );
  } else {
    if (UrlExists(`${host}${doc_valorizacion}`) == 200) {
      var tipo_doc = doc_view_extencion(doc_valorizacion, 'valorizacion', 'documento', '100%', '400');

      $('#ver-documento').html(`
        <div class="col-lg-6">
          <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${doc_valorizacion}" download="${replace_punto_a_guion(indice)} ${nombre} - ${localStorage.getItem('nube_nombre_proyecto')} - Val${numero_q_s} - ${format[0]}-${format[1]}-${format[2]}" >
            <i class="fas fa-download"></i> Descargar
          </a>
        </div>
        <div class="col-lg-6 mb-4">
          <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${doc_valorizacion}"  target="_blank" type="button" >
            <i class="fas fa-expand"></i> Ver completo
          </a>
        </div>
        <div class="col-lg-12 text-center"> ${tipo_doc} </div>`
      );  
    } else {
      $('#ver-documento').html(
        `<div class="col-lg-6"><a class="btn btn-warning btn-block btn-xs disabled" type="button" href="#"><i class="fas fa-download"></i> Descargar</a></div>
        <div class="col-lg-6 mb-4"><a class="btn btn-info btn-block btn-xs disabled" href="#" type="button"><i class="fas fa-expand"></i> Ver completo</a></div>
        <div class="col-lg-12 ">
          <div class="embed-responsive" style="padding-bottom:30%" >
            <div class="alert alert-warning alert-dismissible">
              <button type="button" class="close" data-dismiss="Alerta" aria-hidden="true">×</button><h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
              El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar.
            </div>
          </div>
        </div>`
      );
    }      
  }
}

function editar(idtabla, indice, nombre, doc, fecha_i, fecha_f, numero_q_s) {
  limpiar();
  $('#title-modal-1').html(indice+' '+nombre);

 // $("#idproyecto").val();
  $("#idvalorizacion").val(idtabla);
  $("#indice").val(indice);
  $("#nombre").val(nombre);
  $("#fecha_inicio").val(fecha_i);
  $("#fecha_fin").val(fecha_f);
  $("#numero_q_s").val(numero_q_s);

  $("#modal-agregar-valorizacion").modal('show'); 

  if (doc != "") {

    $("#doc_old_7").val(doc);    
    // cargamos la imagen adecuada par el archivo
    $("#doc7_ver").html(doc_view_extencion(doc, 'valorizacion', 'documento', '100%', '210'));    
  }
}

function eliminar(nombre_eliminar, nombre_tabla, nombre_columna, idtabla) {
  crud_eliminar_papelera(
    `../ajax/valorizacion.php?op=desactivar&nombre_tabla=${nombre_tabla}&nombre_columna=${nombre_columna}`,
    `../ajax/valorizacion.php?op=eliminar&nombre_tabla=${nombre_tabla}&nombre_columna=${nombre_columna}`, 
    idtabla, 
    "!Elija una opción¡", 
    `<b class="text-danger">${nombre_eliminar}</b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    false,
    false, 
    false, 
    false,
    false
  );
}
  
init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {  

  $("#form-valorizacion").validate({

    rules: {
      nombre: { required: true },
    },

    messages: {
      nombre: {  required: "Por favor selecione un tipo de documento", },       
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
      guardaryeditar(e);
    },

  });

  $("#numero_q_s_resumen").on("change", function () { $(this).trigger("blur"); });

  $("#form-resumen-valorizacion").validate({

    rules: {
      numero_q_s_resumen: { required: true },
      fecha_inicial: { required: true },
      fecha_final: { required: true },
      monto_programado: {  minlength: 1, maxlength: 20 },
      monto_valorizado: {  minlength: 1, maxlength: 20 },
      //monto_gastado: { required: true, min:0 },
    },

    messages: {
      numero_q_s_resumen: {required: "Por favor selecione un tipo de documento",},       
      fecha_inicial: {required: "Campo requerido",},       
      fecha_final: {required: "Campo requerido",},       
      monto_programado: { minlength: "1 dígitos como minimo.", maxlength: "20 dígitos como máximo."},       
      monto_valorizado: {minlength: "1 dígitos como minimo.", maxlength: "20 dígitos como máximo."},       
      //monto_gastado: {required: "Campo requerido",},       
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
      guardaryeditar_resumen_q_s(e);
    },

  });

  $("#numero_q_s_resumen").rules("add", { required: true, messages: { required: "Campo requerido" } });

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

// captura las fechas de quincenas y trae los datos
function buscar_documento(fecha_i, fecha_f, i) {

  fecha_i_r = fecha_i, fecha_f_r = fecha_f; i_r = i;

  $('.icon-resumen-cargando').html('<i class="fas fa-spinner fa-pulse fa-md"></i>');

  let nube_idproyecto = localStorage.getItem('nube_idproyecto');

  var respuestadoc5_2 = false;
  mostrar_form_table(2);
 
  $("#fecha_inicio").val(fecha_i);
  $("#fecha_fin").val(fecha_f);  //console.log(fecha_i, fecha_f, i);
  $("#numero_q_s").val(cont_valor);

  // validamos el id para pintar el boton
  pintar_btn_selecionado(i);

  // traemos loa documentos por fechas de la quincena
  $.post("../ajax/valorizacion.php?op=mostrar-docs-quincena", { nube_idproyecto: nube_idproyecto, fecha_i: fecha_i, fecha_f: fecha_f }, function (e, status) {

    e =JSON.parse(e); console.log(e);  
    

  }).fail( function(e) { ver_errores(e); } );
}


function cantDiasEnUnMes(mes, año) {
   
  var diasMes = new Date(año, mes, 0).getDate(); // console.log('mes:' + mes+ ' cant:' + diasMes);

  return diasMes; 
}

function despintar_btn_select() {  
  if (localStorage.getItem('boton_id')) { let id = localStorage.getItem('boton_id'); $("#boton-" + id).removeClass('click-boton'); }
}
// ::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   A G R E G A R   R E S U M E N   Q  S ::::::::::::::::::::::::::::::::

function recoger_fecha_q_s() {

  var numero_f1_f2 = $("#numero_q_s_resumen").select2("val");

  if (numero_f1_f2 != null && numero_f1_f2 != '') {

    numero_f1_f2 = numero_f1_f2.split(" ");
    $("#numero_q_s_resumen_oculto").val(numero_f1_f2[0]);
    $("#fecha_inicial").val(numero_f1_f2[1]);
    $("#fecha_final").val(numero_f1_f2[2]);
    console.log(numero_f1_f2);
  }   
}

function export_excel_valorizacion() {
  $tabla = document.querySelector("#tbla_export_excel_valorizacion");
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: "Detalle valorizacion", //Nombre del archivo de Excel
    sheetname: "detalle", //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);
  let preferenciasDocumento = datos.tbla_export_excel_valorizacion.xlsx;
  tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

}

function pintar_btn_selecionado(i) {
  if (localStorage.getItem('boton_id')) {

    let id = localStorage.getItem('boton_id'); //console.log('id-nube-boton'+id); 
    
    $("#boton-" + id).removeClass('click-boton');

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  } else {

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  }
}
