
var host = window.location.host == 'localhost'? `http://localhost/admin_sevens/dist/docs/valorizacion/documento/` : `${window.location.origin}/dist/docs/valorizacion/documento/` ;

var array_fechas_q_s = [];

var cant_valorizaciones = 0;

var fecha_i_r = "", fecha_f_r = "", i_r = "";

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lFierroValorizacion").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));
  $("#idproyecto_q_s").val(localStorage.getItem('nube_idproyecto'));
  
  ver_quincenas(localStorage.getItem('nube_idproyecto'));  
  todos_los_docs();

  $("#guardar_registro").on("click", function (e) {  $("#submit-form-fierro").submit(); });

  //Initialize Select2 Elements
  $("#numero_q_s_resumen").select2({ theme: "bootstrap4", placeholder: "Selecione Valorizacion", allowClear: true, });
  
  // Formato para telefono
  $("[data-mask]").inputmask();  

}

$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

// Eliminamos el doc 6
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
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

  $.post("../ajax/valorizacion_fierro.php?op=listarquincenas", { 'nube_idproyecto': nube_idproyecto }, function (e, status) {

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

          
        } else {

          if (e.data.fecha_valorizacion == "al finalizar") {

            $(".h1-titulo").html("Valorización - Al finalizar");

            $('#lista_quincenas').append(` <button id="boton-0" type="button" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="buscar_documento('${e.data.fecha_inicio}', '${e.data.fecha_fin}', '0');"><i class="far fa-calendar-alt"></i> Valorización 1<br>${format_d_m_a(e.data.fecha_inicio)} // ${format_d_m_a(e.data.fecha_fin)}</button>`)
            $("#numero_q_s_resumen").append(`<option value="${i+1} ${fecha_ii} ${fecha_ff}" >Valorización ${i+1}</option>`);
            array_fechas_q_s.push({'fecha_inicio':fecha_ii, 'fecha_fin':fecha_ff, 'num_q_s': i+1, });
            cant_valorizaciones = i+1;
            
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
function limpiar_form_fierro() {
  $("#nombre_doc").val("");

  $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  $("#doc1_nombre").html('');
  $("#doc_old_1").val(""); 
  $("#doc1").val(""); 

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función para guardar o editar
function guardar_y_editar_fierro(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-fierro")[0]);

  $.ajax({
    url: "../ajax/valorizacion_fierro.php?op=guardar_y_editar_fierro",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {   
      try {
        e = JSON.parse(e);
        if (e.status == true) {	
          limpiar_form_fierro();
          Swal.fire("Correcto!", "Documento guardado correctamente", "success");          
          mostrar_form_table(2);
          buscar_documento(fecha_i_r, fecha_f_r, i_r);
          $("#modal-agregar-editar-doc").modal("hide");

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

// captura las fechas de quincenas y trae los datos
function buscar_documento(fecha_i, fecha_f, i) {

  fecha_i_r = fecha_i, fecha_f_r = fecha_f; i_r = i;

  $('.div-doc-val').html(`<div class="col-12 text-center "> <i class="fas fa-spinner fa-pulse fa-6x"></i><br /> <br /> <h4>Cargando...</h4> </div>`);

  let nube_idproyecto = localStorage.getItem('nube_idproyecto');

  mostrar_form_table(2); 
  limpiar_form_fierro()
  
  // validamos el id para pintar el boton
  pintar_btn_selecionado(i);

  $("#fecha_inicial").val(fecha_i);
  $("#fecha_final").val(fecha_f);  //console.log(fecha_i, fecha_f, i);
  $("#numero_valorizacion").val(i);  

  // traemos loa documentos por fechas de la quincena
  $.post("../ajax/valorizacion_fierro.php?op=mostrar-docs-quincena", { 'nube_idproyecto': nube_idproyecto, 'fecha_i': fecha_i, 'fecha_f': fecha_f, 'numero_q_s': i }, function (e, status) {

    e =JSON.parse(e); console.log('holi'); console.log(e);  
    
    if (e.status == true) {
      var doc_url = "", disabed_dowload = "disabled", disable_ver = "disabled", doc_name = "";
      if (e.data == null) {
        $('#div-doc-val').html("vacio");
      }else{
        $("#nombre_doc").val(e.data.nombre_doc);
        $("#doc_old_1").val(e.data.documento);
        $("#idfierro_por_valorizacion").val(e.data.idfierro_por_valorizacion);
        if (e.data.documento == null || e.data.documento == "") {
          $('#div-doc-val').html("vacio");
          disable_ver = "disabled";
          disabed_dowload = "disabled";
        } else {
          if (extrae_extencion(e.data.documento) == "pdf") {
            $('#div-doc-val').html(doc_view_extencion(e.data.documento,'valorizacion_fierro','documento', '100%', '500')); 
            doc_url = `../dist/docs/valorizacion_fierro/documento/${e.data.documento}`;
            doc_name = removeCaracterEspecial(e.data.nombre_doc);
            disable_ver = "";
            disabed_dowload = "";
          } else {
            host = window.location.host == 'localhost'? `http://localhost/admin_sevens/` : `${window.location.origin}/` ;
            if (UrlExists(`${host}dist/docs/valorizacion_fierro/documento/${e.data.documento}`) == 200) {
              $('#div-doc-val').html("");           
              $("#div-doc-val").excelPreview({'doc_excel': `../dist/docs/valorizacion_fierro/documento/${e.data.documento}`});
              doc_url = `../dist/docs/valorizacion_fierro/documento/${e.data.documento}`;
              doc_name = removeCaracterEspecial(e.data.nombre_doc);
              disable_ver = "disabled";
              disabed_dowload = "";
            }else{
              $('#div-doc-val').html("vacio");
              disable_ver = "disabled";
              disabed_dowload = "disabled";
            }         
          }          
        }
      }
      

      $('.div-btn-doc').html(`<div class="col-lg-4"> 
        <a  class="btn btn-success  btn-block btn-xs" type="button" data-toggle="modal" data-target="#modal-agregar-editar-doc"> <i class="fas fa-file-upload"></i> Subir </a> 
      </div> 
      <div class="col-lg-4"> 
        <a  class="btn btn-warning  btn-block btn-xs ${disabed_dowload}" type="button" href="${doc_url}" download="${doc_name}" > <i class="fas fa-download"></i> Descargar </a> 
      </div> 
      <div class="col-lg-4 mb-4"> 
        <a  class="btn btn-info  btn-block btn-xs ${disable_ver}" href="${doc_url}" type="button" > <i class="fas fa-expand"></i> Ver completo </a> 
      </div>`);
    } else {
      ver_errores(e);
    }  
  }).fail( function(e) { ver_errores(e); } );
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

function eliminar(nombre_eliminar, nombre_tabla, nombre_columna, idtabla) {
  crud_eliminar_papelera(
    `../ajax/valorizacion_fierro.php?op=desactivar&nombre_tabla=${nombre_tabla}&nombre_columna=${nombre_columna}`,
    `../ajax/valorizacion_fierro.php?op=eliminar&nombre_tabla=${nombre_tabla}&nombre_columna=${nombre_columna}`, 
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

function todos_los_docs() {
  $.post("../ajax/valorizacion_fierro.php?op=todos_los_docs", { 'nube_idproyecto': localStorage.getItem('nube_idproyecto') }, function (e, status, ) {   
    e =JSON.parse(e); console.log(e);  
    if (e.status == true) {
      var html_docs = "";
      e.data.forEach((val, key) => {
        var doc_url = `../dist/docs/valorizacion_fierro/documento/${val.documento}`;
        var icon = doc_view_icon(val.documento);
        html_docs = html_docs.concat(
          `<div class="col-12 col-sm-6 col-md-6 col-lg-2" >     
            <li >                    
              <span class="mailbox-attachment-icon ">${icon}</span>
              <div class="mailbox-attachment-info">
                <a href="#" class="mailbox-attachment-name name_doc_1"><i class="fas fa-paperclip"></i> ${val.nombre_doc}.${extrae_extencion(val.documento)}</a> <br>
                <span class="font-size-12px">Valorizacion ${val.numero_valorizacion}</span>
                <span class="mailbox-attachment-size clearfix mt-1">
                  <a href="${doc_url}" class="btn btn-default btn-sm download_doc_1" download="" data-toggle="tooltip" data-original-title="Descargar"><i class="fas fa-cloud-download-alt"></i></a>
                  <a href="${doc_url}" class="btn btn-default btn-sm ver_doc_1" target="_blank" data-toggle="tooltip" data-original-title="Ver"><i class="far fa-eye"></i></a>
                  
                </span>
              </div>
            </li>
          </div>`
        );

      });
      $('#all-docs-valorizacion').html(html_docs);
    } else {
      ver_errores(e);
    }
  });
}
  
init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {  

  $("#form-fierro").validate({

    rules: {
      nombre_doc: { required: true },
    },

    messages: {
      nombre_doc: {  required: "Campo requerido.", },       
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
      guardar_y_editar_fierro(e);
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
