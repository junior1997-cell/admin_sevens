var tabla_principal;
var tabla_principal_resumen_q_s;
var host = window.location.host == 'localhost'? `http://localhost/admin_sevens/dist/docs/valorizacion/documento/` : `${window.location.origin}/dist/docs/valorizacion/documento/` ;

var array_fechas_q_s = [];

var cant_valorizaciones = 0;

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
  $("#guardar_registro_fechas_ocultas").on("click", function (e) {  $("#submit-form-fechas-ocultas").submit(); });

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
  if (estados=='1') {
    $("#btn-editar").show();
    $("#btn-guardar").hide();

    $('#tab-seleccione').hide(); 
    $('#tab-contenido').hide(); 
    $('#tab-info').show();
    $('#card-regresar').hide();
  } else if (estados=='2') {
    $("#btn-editar").hide();
    $("#btn-guardar").hide();

    $('#tab-seleccione').show(); 
    $('#tab-contenido').show(); 
    $('#tab-info').hide();
    $('#card-regresar').show();      
  }
}

// ver las echas de quincenas
function ver_quincenas(nube_idproyecto) {

  $('#lista_quincenas').html('<i class="fas fa-spinner fa-pulse fa-2x"></i>'); //console.log(nube_idproyecto);

  $.post("../ajax/valorizacion.php?op=listarquincenas", { 'nube_idproyecto': nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); console.log(e);    

    $('#lista_quincenas').html('');

    e.data.fechas_val.forEach((val, key) => {

      if (val.fecha_valorizacion == "quincenal") {
        $(".h1-titulo").html("Valorización - Quincenal");
      }  else if (val.fecha_valorizacion == "mensual") {
        $(".h1-titulo").html("Valorización - Mensual");
      }  else if (val.fecha_valorizacion == "al finalizar") {
        $(".h1-titulo").html("Valorización - Al finalizar");
      }
     
      //var fechas_btn = fechas_valorizacion_quincena(val.fecha_inicio, val.fecha_fin); 
      //console.log(fechas_btn);  
       
      $('#lista_quincenas').append(` <button id="boton-${val.num_q_s}" type="button" class="mb-2 btn bg-gradient-info text-center btn-sm" onclick="fecha_quincena('${format_a_m_d(val.fecha_inicio)}', '${format_a_m_d(val.fecha_fin)}', '${val.num_q_s}');"><i class="far fa-calendar-alt"></i> Valorización ${val.num_q_s}<br>${val.fecha_inicio} // ${val.fecha_fin}</button>`)
      array_fechas_q_s.push({ 'fecha_inicio':format_a_m_d(val.fecha_inicio), 'fecha_fin':format_a_m_d(val.fecha_fin), 'num_q_s': val.num_q_s, });
      cant_valorizaciones = val.num_q_s;
       
    });

    // VALIDAMOS LAS FECHAS DE QUINCENA
    if (false) { 
        
      if (e.data.fecha_valorizacion == "quincenal") {

        $(".h1-titulo").html("Valorización - Quincenal");
        var fechas_btn = fechas_valorizacion_quincena(e.data.fecha_inicio, e.data.fecha_fin); 
        //console.log(fechas_btn);  

        fechas_btn.forEach((key, indice) => {
          $('#lista_quincenas').append(` <button id="boton-${key.num_q_s}" type="button" class="mb-2 btn bg-gradient-info text-center btn-sm" onclick="fecha_quincena('${format_a_m_d(key.fecha_inicio)}', '${format_a_m_d(key.fecha_fin)}', '${key.num_q_s}');"><i class="far fa-calendar-alt"></i> Valorización ${key.num_q_s}<br>${key.fecha_inicio} // ${key.fecha_fin}</button>`)
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
            $('#lista_quincenas').append(` <button id="boton-${key.num_q_s}" type="button" class="mb-2 btn bg-gradient-info text-center btn-sm" onclick="fecha_quincena('${format_a_m_d(key.fecha_inicio)}', '${format_a_m_d(key.fecha_fin)}', '${key.num_q_s}');"><i class="far fa-calendar-alt"></i> Valorización ${key.num_q_s}<br>${key.fecha_inicio} // ${key.fecha_fin}</button>`)
            array_fechas_q_s.push({ 'fecha_inicio':format_a_m_d(key.fecha_inicio), 'fecha_fin':format_a_m_d(key.fecha_fin), 'num_q_s': key.num_q_s, });
            cant_valorizaciones = key.num_q_s;
          });

          tbla_resumen_q_s(nube_idproyecto);
        } else {

          if (e.data.fecha_valorizacion == "al finalizar") {

            $(".h1-titulo").html("Valorización - Al finalizar");

            $('#lista_quincenas').append(` <button id="boton-0" type="button" class="mb-2 btn bg-gradient-info btn-sm text-center" onclick="fecha_quincena('${e.data.fecha_inicio}', '${e.data.fecha_fin}', '0');"><i class="far fa-calendar-alt"></i> Valorización 1<br>${format_d_m_a(e.data.fecha_inicio)} // ${format_d_m_a(e.data.fecha_fin)}</button>`)
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
          if (tabla_principal) { tabla_principal.ajax.reload(null, false); } 
          mostrar_form_table(2);
          fecha_quincena(localStorage.getItem('fecha_i'), localStorage.getItem('fecha_f'), localStorage.getItem('i'));
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

function l_m(){  
  $("#barra_progress").css({"width":'0%'});  
  $("#barra_progress").text("0%");  
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
    function(){ if (tabla_principal) { tabla_principal.ajax.reload(null, false); } },
    false, 
    false, 
    false,
    false
  );
}


// ::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   A G R E G A R   R E S U M E N   Q  S ::::::::::::::::::::::::::::::::
  function show_hide_span_input(flag){

    if (flag == 1) {
      // ocultamos los span
      $(".span_val").show();
      // mostramos los inputs
      $(".input_val").hide();

      // ocultamos el boton editar
      $("#btn-editar").show();
      // mostramos el boton guardar
      $("#btn-guardar").hide();

      estado_editar_asistencia = false;
    } else if (flag == 2) {
      
      // ocultamos los span
      $(".span_val").hide();
      // mostramos los inputs
      $(".input_val").show();

      // ocultamos el boton editar
      $("#btn-editar").hide();
      // mostramos el boton guardar
      $("#btn-guardar").show();

      estado_editar_asistencia = true;    
    }  
  }

  function limpiar_resumen_q_s() {
    $("#idresumen_q_s_valorizacion").val("");
    $("#numero_q_s_resumen_oculto").val("");
    $("#numero_q_s_resumen").val("").trigger('change');
    $("#monto_programado").val("");
    $("#monto_valorizado").val("");
    $("#fecha_inicial").val("");
    $("#fecha_final").val("");
  
  }

  //Función para guardar o editar
  function guardar_y_editar_resumen_q_s_valorizacion() {
    // e.preventDefault(); //No se activará la acción predeterminada del evento
    //var formData = new FormData($("#form-resumen-valorizacion")[0]);
    var array_resumen_q_s = [];
    for (let index = 1; index <= cant_valorizaciones; index++) {
      array_resumen_q_s.push({
        'idresumen_q_s_valorizacion':$(`.idresumen_q_s_valorizacion_${index}`).text(),
        'idproyecto':       $(`.idproyecto_${index}`).text(),        
        'fecha_inicio':     $(`.f_inicio_${index}`).text(),
        'fecha_fin':        $(`.f_fin_${index}`).text(),
        'numero_q_s':       $(`.numero_q_s_actual_${index}`).text(),
        'monto_programado': quitar_formato_miles($(`#programado_${index}`).val()),
        'monto_valorizado': quitar_formato_miles($(`#valorizado_${index}`).val()),
      });      
    }    

    if (array_resumen_q_s.length === 0) {
      toastr_error('Sin datos!', ' No hay datos para guardar', 700);
    } else {
      // abrimos el modal cargando
      $("#modal-cargando").modal("show");

      $.ajax({
        url: "../ajax/valorizacion.php?op=guardaryeditar_resumen_q_s",
        type: "POST",
        data: {
          'resumen_qs':JSON.stringify(array_resumen_q_s),
          'idproyecto': localStorage.getItem('nube_idproyecto')
        },
        // contentType: false,
        // processData: false,
        success: function (e) {   
          try {
            e = JSON.parse(e);
            if (e.status == true) {	    
              $("#icono-respuesta").html(`<div class="swal2-icon swal2-success swal2-icon-show" style="display: flex;"> <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div> <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span> <div class="swal2-success-ring"></div> <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div> <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div> </div>  <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Correcto!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">Asistencia registrada correctamente</div> </div>` );             
              $(".progress-bar").addClass("bg-success"); $("#barra_progress_cargando").text("100% Completado!");
              show_hide_span_input(1);
              tbla_resumen_q_s(localStorage.getItem('nube_idproyecto')); 
            }else{
              $("#icono-respuesta").html(`<div class="swal2-icon swal2-error swal2-icon-show" style="display: flex;"> <span class="swal2-x-mark"> <span class="swal2-x-mark-line-left"></span> <span class="swal2-x-mark-line-right"></span> </span> </div> <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Error!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">${e.data}</div> </div>`);
              $(".progress-bar").addClass("bg-danger"); $("#barra_progress_cargando").text("100% Error!");
              //ver_errores(e);
            }
          } catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      

        },
        xhr: function () {
          var xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener("progress", function (evt) {
            if (evt.lengthComputable) {
              var percentComplete = (evt.loaded / evt.total)*100;
              /*console.log(percentComplete + '%');*/
              $("#barra_progress_cargando").css({"width": percentComplete+'%'});
              $("#barra_progress_cargando").text(percentComplete.toFixed(2)+" %");
            }
          }, false);
          return xhr;
        },
        beforeSend: function () {
          $("#barra_progress_cargando").css({ width: "0%",  });
          $("#barra_progress_cargando").text("0%").addClass('progress-bar-striped progress-bar-animated');
        },
        complete: function () {
          //$("#barra_progress_cargando").css({ width: "0%", }).text("0%");
          $("#barra_progress_cargando").removeClass('progress-bar-striped progress-bar-animated');
        },
        error: function (jqXhr) { ver_errores(jqXhr); },
      });
    }
    
  }

  //Función Listar - tabla principal
  function tbla_resumen_q_s(nube_idproyecto) {   

    $('#tabla-principal').html(`<tr><td colspan="11"><div class="row" ><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-4x"></i><br/><br/><h4>Cargando...</h4></div></div></td></tr>`);
    
    var total_programado = 0, total_valorizado = 0, total_gastado = 0;
    var total_porcent_programado = 0, total_porcent_valorizado = 0, total_porcent_gastado = 0;

    // suma totales x proyecto
    $.post(`../ajax/valorizacion.php?op=tbla_resumen_q_s`, { 'idproyecto': nube_idproyecto, 'array_fechas':JSON.stringify(array_fechas_q_s) }, function (e, status) {

      e = JSON.parse(e); console.log(e); 

      if (e.status == true) {

        var html_tabla = '';

        var m_programado =0, m_valorizado =0, m_gastado =0;

        var acum_porcent_programado = 0, acum_porcent_valorizado = 0, acum_porcent_gastado = 0;

        e.data.montos.forEach((key, indice) => {
          var bg_hoy_q_s = ''; 
          var porcent_programado  = (key.monto_programado / e.data.proyecto_costo) * 100;
          var porcent_valorizado  = (key.monto_valorizado / e.data.proyecto_costo) * 100;
          var porcent_gastado     = (key.monto_gastado / e.data.proyecto_costo) * 100;

          acum_porcent_programado += (key.monto_programado / e.data.proyecto_costo) * 100;
          acum_porcent_valorizado += (key.monto_valorizado / e.data.proyecto_costo) * 100;
          acum_porcent_gastado    += (key.monto_gastado / e.data.proyecto_costo) * 100;

          total_porcent_programado += porcent_programado, 
          total_porcent_valorizado += porcent_valorizado, 
          total_porcent_gastado += porcent_gastado;

          total_programado+= key.monto_programado; 
          total_valorizado+= key.monto_valorizado; 
          total_gastado   += key.monto_gastado;

          if (validarFechaEnRango( key.fecha_inicio, key.fecha_fin, moment().format('YYYY-MM-DD')) == true) {
            bg_hoy_q_s = 'bg-color-48acc6'; //console.log(bg_hoy_q_s);
          } 

          html_tabla = html_tabla.concat(`<tr>
            <td class="pt-1 pb-1 ${bg_hoy_q_s} text-center" >
              <span class="numero_q_s_actual_${indice+1}">${indice+1}</span>
              <span class="hidden idproyecto_${indice+1}">${key.idproyecto}</span>
              <span class="hidden idresumen_q_s_valorizacion_${indice+1}">${key.idresumen_q_s_valorizacion}</span>
              <span class="hidden f_inicio_${indice+1}" >${key.fecha_inicio}</span> <span class="hidden f_fin_${indice+1}">${key.fecha_fin}</span>
            </td>
            <td class="pt-1 pb-1 celda-b-r-2px ${bg_hoy_q_s} text-center" >${format_d_m_a(key.fecha_inicio)} - ${format_d_m_a(key.fecha_fin)}</td>
            <td class="pt-1 pb-1 ${bg_hoy_q_s} text-right"  >
              <div class="formato-numero-conta span_val"><span>S/</span><span>${formato_miles(key.monto_programado)}</span></div>
              <input class="hidden w-100 input_val" type="text" id="programado_${indice+1}" value="${key.monto_programado==0?'':formato_miles(key.monto_programado)}" onkeyup="formato_miles_input('#programado_${indice+1}'); delay(function(){ calcular_procentajes_programado(${indice+1}, ${cant_valorizaciones}, ${e.data.proyecto_costo}, ${e.data.proyecto_garantia}) }, 200 );">
            </td>
            <td class="pt-1 pb-1 ${bg_hoy_q_s} text-center" ><span class="porcent_programado_${indice+1}">${redondearExp(porcent_programado)}</span>%</td>            
            <td class="pt-1 pb-1 celda-b-r-2px ${bg_hoy_q_s} text-center" ><span class="acum_porcent_programado_${indice+1}">${redondearExp(acum_porcent_programado)}</span>%</td>
            <td class="pt-1 pb-1 ${bg_hoy_q_s} text-right"  >
              <div class="formato-numero-conta span_val"><span>S/</span><span>${formato_miles(key.monto_valorizado)}</span></div>
              <input class="hidden w-100 input_val" type="text" id="valorizado_${indice+1}" value="${key.monto_valorizado==0?'':formato_miles(key.monto_valorizado)}" onkeyup="formato_miles_input('#valorizado_${indice+1}'); delay(function(){ calcular_procentajes_valorizado(${indice+1}, ${cant_valorizaciones}, ${e.data.proyecto_costo}, ${e.data.proyecto_garantia}) }, 200 );">
            </td>
            <td class="pt-1 pb-1 ${bg_hoy_q_s} text-center" ><span class="porcent_valorizado_${indice+1}">${redondearExp(porcent_valorizado)}</span>%</td>            
            <td class="pt-1 pb-1 celda-b-r-2px ${bg_hoy_q_s} text-center" ><span class="acum_porcent_valorizado_${indice+1}">${redondearExp(acum_porcent_valorizado)}</span>%</td>
            <td class="pt-1 pb-1 ${bg_hoy_q_s} text-right"  >
              <div class="formato-numero-conta"><span>S/</span><span>${formato_miles(key.monto_gastado)}</span></div>              
            </td>
            <td class="pt-1 pb-1 ${bg_hoy_q_s} text-center" >${redondearExp(porcent_gastado)}%</td>
            <td class="pt-1 pb-1 ${bg_hoy_q_s} text-center" >${redondearExp(acum_porcent_gastado)}%</td>
          </tr>`); 
        });

        $('#tabla-principal').html(html_tabla);

        $('.suma_total_monto_programado_95').html(`${formato_miles(total_programado * (1 - e.data.proyecto_garantia))}`);        
        $('.suma_total_monto_valorizado_95').html(`${formato_miles(total_valorizado * (1 - e.data.proyecto_garantia))}`);        
        $('.suma_total_monto_gastado_95').html(`${formato_miles(total_gastado * (1 - e.data.proyecto_garantia))}`); 

        $('.suma_total_monto_programado_5').html(`${formato_miles(total_programado * e.data.proyecto_garantia)}`);        
        $('.suma_total_monto_valorizado_5').html(`${formato_miles(total_valorizado * e.data.proyecto_garantia)}`);        
        $('.suma_total_monto_gastado_5').html(`${formato_miles(total_gastado * e.data.proyecto_garantia)}`);

        $('.suma_total_monto_programado').html(`<b>${formato_miles(total_programado)}</b>`);        
        $('.suma_total_monto_valorizado').html(`<b>${formato_miles(total_valorizado)}</b>`);        
        $('.suma_total_monto_gastado').html(`<b>${formato_miles(total_gastado)}</b>`); 

        $('.total_porcent_valorizado_95').html(`${redondearExp(((1 - e.data.proyecto_garantia) * 100), 2)}%`);
        $('.total_porcent_programado_95').html(`${redondearExp(((1 - e.data.proyecto_garantia) * 100), 2)}%`);
        $('.total_porcent_gastado_95').html(`${redondearExp(((1 - e.data.proyecto_garantia) * 100), 2)}%`);

        $('.total_porcent_valorizado_5').html(`${redondearExp((e.data.proyecto_garantia * 100), 2)}%`);
        $('.total_porcent_programado_5').html(`${redondearExp((e.data.proyecto_garantia * 100), 2)}%`);
        $('.total_porcent_gastado_5').html(`${redondearExp((e.data.proyecto_garantia * 100), 2)}%`);

        $('.total_porcent_valorizado').html(`<b>${redondearExp(total_porcent_valorizado, 2)}%</b>`);
        $('.total_porcent_programado').html(`<b>${redondearExp(total_porcent_programado, 2)}%</b>`);
        $('.total_porcent_gastado').html(`<b>${redondearExp(total_porcent_gastado, 2)}%</b>`);
      } else {
        ver_errores(e);
      }    
    }).fail( function(e) { ver_errores(e); } );
    
  }

  function calcular_procentajes_programado(num_val, cant_valorizaciones, costo_proyecto, proyecto_garantia) {
    var porcentaje_calculado = 0;
    if ($(`#programado_${num_val}`).val() == '-' || $(`#programado_${num_val}`).val() == null || $(`#programado_${num_val}`).val() == '' ) {  } else {
      var monto_val = $(`#programado_${num_val}`).val();
      porcentaje_calculado = (parseFloat(quitar_formato_miles(monto_val))/costo_proyecto)*100;
    }
    $(`.porcent_programado_${num_val}`).html(redondearExp(porcentaje_calculado));
    calcular_totales('.suma_total_monto_programado','#programado_', cant_valorizaciones, proyecto_garantia, '');
    calcular_totales('.total_porcent_programado','.porcent_programado_', cant_valorizaciones, proyecto_garantia, 'porcentaje');
    calcular_porcentaje_acumulado('.porcent_programado_', '.acum_porcent_programado_', cant_valorizaciones, costo_proyecto);
  }

  function calcular_procentajes_valorizado(num_val, cant_valorizaciones, costo_proyecto, proyecto_garantia) {
    var porcentaje_calculado = 0;
    if ($(`#valorizado_${num_val}`).val() == '-' || $(`#valorizado_${num_val}`).val() == null || $(`#valorizado_${num_val}`).val() == '' ) {  } else {
      var monto_val = $(`#valorizado_${num_val}`).val();
      porcentaje_calculado = (parseFloat(quitar_formato_miles(monto_val))/costo_proyecto)*100;
    }
    $(`.porcent_valorizado_${num_val}`).html(redondearExp(porcentaje_calculado));
    calcular_totales('.suma_total_monto_valorizado', '#valorizado_', cant_valorizaciones, proyecto_garantia, '');
    calcular_totales('.total_porcent_valorizado','.porcent_valorizado_', cant_valorizaciones, proyecto_garantia, 'porcentaje');
    calcular_porcentaje_acumulado('.porcent_valorizado_', '.acum_porcent_valorizado_', cant_valorizaciones, costo_proyecto);
  }

  // no se usa por ahora -----------
  function calcular_procentajes_gastado(cant_valorizaciones, costo_proyecto, proyecto_garantia) {
    calcular_totales('.total_porcent_gastado', '#gastado_', cant_valorizaciones, proyecto_garantia, '');    
  }

  function calcular_totales(name_div, name_input_span, cant_valorizaciones, proyecto_garantia, tipo) {
    //console.log('hola estamos calculado totales');
    var sum_total_programado = 0, cant_por_fecha = 0;
    for (let index = 1; index <= cant_valorizaciones; index++) {
      if (tipo == 'porcentaje') {
        cant_por_fecha = $(`${name_input_span}${index}`).text(); //console.log(`${name_input_span}_${index} - ` + cant_por_fecha);
        if (cant_por_fecha == '-' || cant_por_fecha == null || cant_por_fecha == '' ) {  } else {
          sum_total_programado +=  parseFloat(quitar_formato_miles(cant_por_fecha));
        }
      } else {
        cant_por_fecha = $(`${name_input_span}${index}`).val(); //console.log(`${name_input_span}_${index} - ` + cant_por_fecha);
        if (cant_por_fecha == '-' || cant_por_fecha == null || cant_por_fecha == '' ) {  } else {
          sum_total_programado +=  parseFloat(quitar_formato_miles(cant_por_fecha));
        }
      }
    }

    if (tipo == 'porcentaje') {
      $(name_div).html(`${formato_miles(sum_total_programado)}%`);
    } else {
      $(`${name_div}_95`).html(`${formato_miles(sum_total_programado * (1 - proyecto_garantia))}`);
      $(`${name_div}_5`).html(`${formato_miles(sum_total_programado * proyecto_garantia)}`);
      $(name_div).html(`<b>${formato_miles(sum_total_programado)}</b>`);
    }    
  }

  function calcular_porcentaje_acumulado(name_input_span, name_output_span, cant_valorizaciones, costo_proyecto) {
    var sum_total_acumulado = 0;
    for (let index = 1; index <= cant_valorizaciones; index++) {
      var cant_por_fecha = $(`${name_input_span}${index}`).text(); //console.log(`${name_input_span}_${index} - ` + cant_por_fecha);
      if (cant_por_fecha == '-' || cant_por_fecha == null || cant_por_fecha == '' ) {  } else {
        sum_total_acumulado +=  parseFloat(quitar_formato_miles(cant_por_fecha));
        $(`${name_output_span}${index}`).html(redondearExp(sum_total_acumulado));
      }
    }
  }
  

init();

// :::::::::::::::::::::::::::::::::::::::::: U P D A T E   F E C H A S  O C U L T A S :::::::::::::::::::::::::::::::

function actulizar_fechas_val() {

  $('#tabla_fechas_ocultas>tbody').html(`<tr><td colspan="4"><div class="row" ><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-4x"></i><br/><br/><h4>Cargando...</h4></div></div></td></tr>`);
  $('#modal-agregar-fechas-ocultas').modal('show');

  $.post(`../ajax/valorizacion.php?op=mostrar_para_editar_fechas`, {'idproyecto': localStorage.getItem('nube_idproyecto') }, function (e, status, jqXHR) {
    e = JSON.parse(e); console.log(e);
    if ( e.status == true ) {
      var html_tr = '';
      e.data.forEach((val, key) => {
        html_tr = html_tr.concat(
          `<tr>
            <td class="p-b-1px">${key+1}</td> 
            <td class="p-b-1px celda-b-r-2px text-center">${ format_d_m_a(val.fecha_inicio)} <br> ${format_d_m_a(val.fecha_fin)} <input type="hidden" name="idresumen_q_s_valorizacion_fo[]" value="${val.idresumen_q_s_valorizacion}"></td> 
            <td class="p-b-1px"><div class="form-group"> <input type="date" name="fecha_inicio_oculto_fo[${key}]" value="${val.fecha_inicio_oculto}" required class="form-control input_fechas_ocultas_valid" > </div></td> 
            <td class="p-b-1px"><div class="form-group"> <input type="date" name="fecha_fin_oculto_fo[${key}]" value="${val.fecha_fin_oculto}" required class="form-control input_fechas_ocultas_valid"> </div></td>
          </tr>
        `);
      });
      $('#tabla_fechas_ocultas>tbody').html(html_tr);
      $('.input_fechas_ocultas_valid').each(function(e) { $(this).rules('add', { required: true, messages: { required: 'Este campo es obligatorio' } }); });
    } else {
      ver_errores(e);      
    } 
  }).fail( function(e) { ver_errores(e); } );
}

//Función para guardar o editar
function guardar_y_editar_fecha_oculta(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-fechas-ocultas")[0]);

  $.ajax({
    url: "../ajax/valorizacion.php?op=guardar_y_editar_fecha_oculta",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {   
      try {
        e = JSON.parse(e);
        if (e.status == true) {	
          Swal.fire("Correcto!", "Documento guardado correctamente", "success");          
          if (tabla_principal) { tabla_principal.ajax.reload(null, false); }
          $("#modal-agregar-fechas-ocultas").modal("hide");
        }else{
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }      
      $("#guardar_registro_fechas_ocultas").html('Guardar Cambios').removeClass('disabled');
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
      $("#guardar_registro_fechas_ocultas").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
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


// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {  
  $("#numero_q_s_resumen").on("change", function () { $(this).trigger("blur"); });

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

  $("#form-resumen-valorizacion").validate({

    rules: {
      numero_q_s_resumen: { required: true },
      fecha_inicial:      { required: true },
      fecha_final:        { required: true },
      monto_programado:   {  minlength: 1, maxlength: 20 },
      monto_valorizado:   {  minlength: 1, maxlength: 20 },
      //monto_gastado:    { required: true, min:0 },
    },

    messages: {
      numero_q_s_resumen: {required: "Por favor selecione un tipo de documento",},       
      fecha_inicial:      {required: "Campo requerido",},       
      fecha_final:        {required: "Campo requerido",},       
      monto_programado:   { minlength: "1 dígitos como minimo.", maxlength: "20 dígitos como máximo."},       
      monto_valorizado:   {minlength: "1 dígitos como minimo.", maxlength: "20 dígitos como máximo."},       
      //monto_gastado:    {required: "Campo requerido",},       
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

  $.validator.addMethod( "mytst",  function (value, element) {
    var flag = true;  

    $("[name^=fecha_inicio_oculto_fo]").each(function (i, j) {
      $(this).parent("p").find("label.error").remove();
      $(this).parent("p").find("label.error").remove();
      if ($.trim($(this).val()) == "") {
        flag = false;  
        $(this) .parent("p") .append('<label  id="id_ct' + i + '-error" class="error">This field is required.</label>');
      }
    });
    return flag;
  },""); 

  $("#form-fechas-ocultas").validate({

    rules: {
      "fecha_inicio_oculto_fo[]": { required: true },
      "fecha_fin_oculto_fo[]": { required: true },
    },

    messages: {   
      "fecha_inicio_oculto_fo[]": { required: 'fdsfdsf' },
      "fecha_fin_oculto_fo[]": { required: 'gdsfds' },
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
      guardar_y_editar_fecha_oculta(e);
    },

  });   

  $("#numero_q_s_resumen").rules("add", { required: true, messages: { required: "Campo requerido" } });  

});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

// captura las fechas de quincenas y trae los datos
function fecha_quincena(fecha_i, fecha_f, i) {

  $('.icon-resumen-cargando').html('<i class="fas fa-spinner fa-pulse fa-md"></i>');

  var cont_valor = parseInt(i) + 1;
  //console.log(cont_valor);
  $("#nombre_titulo").html("Valorización " + cont_valor);

  localStorage.setItem('fecha_i', fecha_i);  localStorage.setItem('fecha_f', fecha_f); localStorage.setItem('i', i);

  let nube_idproyecto = localStorage.getItem('nube_idproyecto');

  var respuestadoc5_2 = false;
  mostrar_form_table(2);
 
  $("#fecha_inicio").val(fecha_i);
  $("#fecha_fin").val(fecha_f);  //console.log(fecha_i, fecha_f, i);
  $("#numero_q_s").val(cont_valor);

  // validamos el id para pintar el boton
  if (localStorage.getItem('boton_id')) {

    let id = localStorage.getItem('boton_id'); //console.log('id-nube-boton'+id); 
    
    $("#boton-" + id).removeClass('click-boton');

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  } else {

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  }

  // pintamos rojos los que no tienen docs
  $("#tabs-2-tab").addClass('no-doc').removeClass('si-doc');   
  $("#tabs-3-1-tab").addClass('no-doc').removeClass('si-doc');
  $("#tabs-3-2-tab").addClass('no-doc').removeClass('si-doc');
  $("#tabs-3-3-tab").addClass('no-doc').removeClass('si-doc');
  $("#tabs-3-4-tab").addClass('no-doc').removeClass('si-doc');
  $("#tabs-5-1-tab").addClass('no-doc').removeClass('si-doc');
  $("#tabs-5-2-tab").addClass('no-doc').removeClass('si-doc');
  $("#tabs-6-tab").addClass('no-doc').removeClass('si-doc');
  $("#tabs-7-tab").addClass('no-doc').removeClass('si-doc');
  $("#tabs-8-4-tab").addClass('no-doc').removeClass('si-doc');
  $("#tabs-8-5-tab").addClass('no-doc').removeClass('si-doc');
  $("#tabs-8-6-tab").addClass('no-doc').removeClass('si-doc');
  $("#tabs-8-7-tab").addClass('no-doc').removeClass('si-doc');

  $('#documento2').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento3-1').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento3-2').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento3-3').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento3-4').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento5-1').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento5-2').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('','5.2');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:30%" > No hay documento para mostrar </div> </div>` );
  $('#documento5-2-1').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('','5.2.1');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:30%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento6').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento7').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento8-4').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento8-5').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento8-6').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 
  $('#documento8-7').html(`<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>` ); 


  // traemos loa documentos por fechas de la quincena
  $.post("../ajax/valorizacion.php?op=mostrar-docs-quincena", { nube_idproyecto: nube_idproyecto, fecha_i: fecha_i, fecha_f: fecha_f }, function (e, status) {

    e =JSON.parse(e); console.log(e);  
    
    var vacio = "''";  var docs_total = 0; var porcent = 0;

    // validamos la data total
    if (e.status == true) {
      
      // exraemos la fecha de HOY
      var tiempoTranscurrido = Date.now();
      var hoy = new Date(tiempoTranscurrido);
      var format = hoy.toLocaleDateString().split("/"); //console.log(format);
      
      // validamos la data1
      if (e.data.data1.length === 0) { console.log('data 1 no existe'); } else {
        //console.log('data 1 existe');
        
        $.each(e.data.data1, function (index, value) {

          if (value.indice == "2" ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-2-tab").removeClass('no-doc').addClass("si-doc");      
              
              // cargamos la imagen adecuada par el archivo
              $('#documento2').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="2 Informe tecnico - ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '700')}
                </div>`
              );  
              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);
            } else {
              $('#documento2').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              ); 
            }
          }

          if (value.indice == "3.1" ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-3-1-tab").removeClass('no-doc').addClass("si-doc");         
    
              // cargamos la imagen adecuada par el archivo
              $('#documento3-1').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="3-1 Planilla de metrados -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '700')}
                </div>`
              );    
              
              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);
            } else {
              $('#documento3-1').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }            
          }

          if (value.indice == "3.2" ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-3-2-tab").removeClass('no-doc').addClass("si-doc");   
    
              // cargamos la imagen adecuada par el archivo
              $('#documento3-2').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="3-2 Valorizaciones -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '700')}
                </div>`
              ); 

              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);  
            } else {
              $('#documento3-2').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }                 
          }

          if (value.indice == "3.3" ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-3-3-tab").removeClass('no-doc').addClass("si-doc"); 
    
              // cargamos la imagen adecuada par el archivo
              $('#documento3-3').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="3-3 Resumen de valorizacion -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '700')}
                </div>`
              );  

              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);
            } else {
              $('#documento3-3').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }            
          }

          if (value.indice == "3.4" ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-3-4-tab").removeClass('no-doc').addClass("si-doc");     
    
              // cargamos la imagen adecuada par el archivo
              $('#documento3-4').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="3-4 Curva S -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '700')}
                </div>`
              );  
              
              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);
            } else {
              $('#documento3-4').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }            
          }

          if (value.indice == "5.1" ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-5-1-tab").removeClass('no-doc').addClass("si-doc");        
    
              // cargamos la imagen adecuada par el archivo
              $('#documento5-1').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="5-1 Ensayo de consi. del concreto -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '700')}
                </div>`
              );  

              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);
            } else {
              $('#documento5-1').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }            
          }

          if (value.indice == "5.2" ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-5-2-tab").removeClass('no-doc').addClass("si-doc");        
              
              // cargamos la imagen adecuada par el archivo
              $('#documento5-2').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta(${value.idvalorizacion},'5.2');">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="5-2 Ensayo de compresión -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '700')}
                </div>`
              );

              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);
            } else {
              $('#documento5-2').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }            
          }

          if (value.indice == "5.2.1" ) {  
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // cargamos la imagen adecuada par el archivo
              $('#documento5-2-1').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc_respuesta(${value.idvalorizacion}, '5.2.1');">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="5-2-1 Respuesta de ensayo de compresión -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '700')}
                </div>`
              );    

              // mostramos el resumen
              // docs_total += 1;
              // porcent = (docs_total * 100 )/18;
              // $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              // $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              // $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);               
            } else {
              $('#documento5-2-1').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }
            respuestadoc5_2 = true;
          }

          if (value.indice == "6" ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-6-tab").removeClass('no-doc').addClass("si-doc");        
    
              // cargamos la imagen adecuada par el archivo
              $('#documento6').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/${value.doc_valorizacion}" download="6 Plan de seg y salud en el trabajo -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '700')}
                </div>`
              );   
              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);      
            } else {
              $('#documento6').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }            
          }

          if (value.indice == "7"  ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-7-tab").removeClass('no-doc').addClass("si-doc");       
    
              // cargamos la imagen adecuada par el archivo
              $('#documento7').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="7 Plan de bioseguridad COVID19 -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '100%')}
                </div>`
              );   
              
              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);
            } else {
              $('#documento7').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }            
          }

          if (value.indice == "8.4" ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-8-4-tab").removeClass('no-doc').addClass("si-doc");        
    
              // cargamos la imagen adecuada par el archivo
              $('#documento8-4').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="8-4 Planilla del personal obrero -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '100%')}
                </div>`
              );  

              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);
            } else {
              $('#documento8-4').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }                   
          }

          if (value.indice == "8.5" ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-8-5-tab").removeClass('no-doc').addClass("si-doc");      
    
              // cargamos la imagen adecuada par el archivo
              $('#documento8-5').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="8-5 Copia del seguro complement contra todo riesgo -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '100%')}
                </div>`
              );   

              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);
            } else {
              $('#documento8-5').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }                 
          }

          if (value.indice == "8.6" ) {
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-8-6-tab").removeClass('no-doc').addClass("si-doc");       
    
              // cargamos la imagen adecuada par el archivo
              $('#documento8-6').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="8-6 Panel fotográfico -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '100%')}
                </div>`
              );   

              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);    
            } else {
              $('#documento8-6').html(
                `<div class="col-lg-4"> <a class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }            
          }

          if (value.indice == "8.7" ) {
            //console.log('entramos');
            if (UrlExists(`${host}${value.doc_valorizacion}`) == 200) {
              // pintamos rojos los que no tienen docs
              $("#tabs-8-7-tab").removeClass('no-doc').addClass("si-doc");     
    
              // cargamos la imagen adecuada par el archivo
              $('#documento8-7').html(
                `<div class="col-lg-4">
                  <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});">
                    <i class="fas fa-file-upload"></i> Subir
                  </a>
                </div>
                <div class="col-lg-4">
                  <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}" download="8-7 Copia del cuaderno de obra -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                    <i class="fas fa-download"></i> Descargar
                  </a>
                </div>
                <div class="col-lg-4 mb-4">
                  <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${value.doc_valorizacion}"  target="_blank"  type="button" >
                    <i class="fas fa-expand"></i> Ver completo
                  </a>
                </div>
                <div class="col-lg-12 text-center">
                  ${doc_view_extencion(value.doc_valorizacion, 'valorizacion', 'documento', '100%', '100%')}
                </div>`
              );    

              // mostramos el resumen
              docs_total += 1;
              porcent = (docs_total * 100 )/18;
              $('.total_docs_subidos').html(`Total ${docs_total}/18`);
              $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
              $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`); 
            } else {
              //console.log('entramos 2');
              $('#documento8-7').html(
                `<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${value.idvalorizacion});"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a class="btn btn-warning btn-block btn-xs" type="button" href="#" download="#"> <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs" href="#" target="_blank" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> El documento no esta disponible, porbablemente esta <b>eliminado</b> o se a <b>movido</b> a otra carpeta. Edite este registro y vuelva a intentar. </div>`
              );
            }               
          }
          
        });

        if (respuestadoc5_2 == false) { $('#documento5-2-1').html(`<div class="col-lg-4 "> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta(${vacio}, '5.2.1');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:30%" > No hay documento para mostrar </div> </div>` ); }
      }

      // validamos la data2
      if (e.data.data2.length === 0) {
        console.log('data 2 no existe');
      } else {

        if (e.data.data2.doc1 != "" && UrlExists(`${host}${e.data.data2.doc1}`) == 200) {

          if ($("#tabs-1-tab").hasClass("no-doc")) { $("#tabs-1-tab").removeClass('no-doc'); }          

          // cargamos la imagen adecuada par el archivo
          $('#documento1').html(
            `<div class="col-lg-4">
              <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${e.data.data2.idproyecto});">
                <i class="fas fa-file-upload"></i> Subir
              </a>
            </div>
            <div class="col-lg-4">
              <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${e.data.data2.doc1}" download="1 Copia del contrato -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                <i class="fas fa-download"></i> Descargar
              </a>
            </div>
            <div class="col-lg-4 mb-4">
              <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${e.data.data2.doc1}"  target="_blank"  type="button" >
                <i class="fas fa-expand"></i> Ver completo
              </a>
            </div>
            <div class="col-lg-12 text-center">
              ${doc_view_extencion(e.data.data2.doc1, 'valorizacion', 'documento', '100%', '700')}
            </div>`
          );

          // mostramos el resumen
          docs_total += 1;
          porcent = (docs_total * 100 )/18;
          $('.total_docs_subidos').html(`Total ${docs_total}/18`);
          $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
          $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);
        
        } else {

          if ($("#tabs-1-tab").hasClass("no-doc") == false) { $("#tabs-1-tab").addClass('no-doc'); }

          $('#documento1').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (e.data.data2.doc4 != "" && UrlExists(`${host}${e.data.data2.doc4}`) == 200) {

          if ($("#tabs-4-tab").hasClass("no-doc")) { $("#tabs-4-tab").removeClass('no-doc'); }           

          // cargamos la imagen adecuada par el archivo
          $('#documento4').html(
            `<div class="col-lg-4">
              <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${e.data.data2.idproyecto});">
                <i class="fas fa-file-upload"></i> Subir
              </a>
            </div>
            <div class="col-lg-4">
              <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${e.data.data2.doc4}" download="4 Cronograma de obra valorizado -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                <i class="fas fa-download"></i> Descargar
              </a>
            </div>
            <div class="col-lg-4 mb-4">
              <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${e.data.data2.doc4}"  target="_blank"  type="button" >
                <i class="fas fa-expand"></i> Ver completo
              </a>
            </div>
            <div class="col-lg-12 text-center">
            ${doc_view_extencion(e.data.data2.doc4, 'valorizacion', 'documento', '100%', '700')}
            </div>`
          );

          // mostramos el resumen
          docs_total += 1;
          porcent = (docs_total * 100 )/18;
          $('.total_docs_subidos').html(`Total ${docs_total}/18`);
          $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
          $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);

        } else {

          if ($("#tabs-4-tab").hasClass("no-doc") == false) { $("#tabs-4-tab").addClass('no-doc'); }

          $('#documento4').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (e.data.data2.doc81 != "" && UrlExists(`${host}${e.data.data2.doc81}`) == 200) {

          if ($("#tabs-8-1-tab").hasClass("no-doc")) { $("#tabs-8-1-tab").removeClass('no-doc'); }

          // cargamos la imagen adecuada par el archivo
          $('#documento8-1').html(
            `<div class="col-lg-4">
              <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${e.data.data2.idproyecto});">
                <i class="fas fa-file-upload"></i> Subir
              </a>
            </div>
            <div class="col-lg-4">
              <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${e.data.data2.doc81}" download="8-1 Acta de entrega de terreno -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                <i class="fas fa-download"></i> Descargar
              </a>
            </div>
            <div class="col-lg-4 mb-4">
              <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${e.data.data2.doc81}"  target="_blank"  type="button" >
                <i class="fas fa-expand"></i> Ver completo
              </a>
            </div>
            <div class="col-lg-12 text-center">
              ${doc_view_extencion(e.data.data2.doc81, 'valorizacion', 'documento', '100%', '700')}
            </div>`
          );

          // mostramos el resumen
          docs_total += 1;
          porcent = (docs_total * 100 )/18;
          $('.total_docs_subidos').html(`Total ${docs_total}/18`);
          $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
          $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);

        } else {

          if ($("#tabs-8-1-tab").hasClass("no-doc") == false) { $("#tabs-8-1-tab").addClass('no-doc'); }

          $('#documento8-1').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (e.data.data2.doc82 != "" && UrlExists(`${host}${e.data.data2.doc82}`) == 200) {

          if ($("#tabs-8-2-tab").hasClass("no-doc")) { $("#tabs-8-2-tab").removeClass('no-doc'); }

          // cargamos la imagen adecuada par el archivo
          $('#documento8-2').html(
            `<div class="col-lg-4">
              <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${e.data.data2.idproyecto});">
                <i class="fas fa-file-upload"></i> Subir
              </a>
            </div>
            <div class="col-lg-4">
              <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${e.data.data2.doc82}" download="8-2 Acta de inicio de obra -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                <i class="fas fa-download"></i> Descargar
              </a>
            </div>
            <div class="col-lg-4 mb-4">
              <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${e.data.data2.doc82}"  target="_blank"  type="button" >
                <i class="fas fa-expand"></i> Ver completo
              </a>
            </div>
            <div class="col-lg-12 text-center">
              ${doc_view_extencion(e.data.data2.doc82, 'valorizacion', 'documento', '100%', '700')}
            </div>`
          );

          // mostramos el resumen
          docs_total += 1;
          porcent = (docs_total * 100 )/18;
          $('.total_docs_subidos').html(`Total ${docs_total}/18`);
          $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
          $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);

        } else {

          if ($("#tabs-8-2-tab").hasClass("no-doc") == false) { $("#tabs-8-2-tab").addClass('no-doc'); }

          $('#documento8-2').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (e.data.data2.doc83 != "" && UrlExists(`${host}${e.data.data2.doc83}`) == 200) {

          if ($("#tabs-8-3-tab").hasClass("no-doc")) { $("#tabs-8-3-tab").removeClass('no-doc'); }

          // cargamos la imagen adecuada par el archivo
          $('#documento8-3').html(
            `<div class="col-lg-4">
              <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc(${e.data.data2.idproyecto});">
                <i class="fas fa-file-upload"></i> Subir
              </a>
            </div>
            <div class="col-lg-4">
              <a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/documento/${e.data.data2.doc83}" download="8-3 Certif de habil del ing resident -  ${localStorage.getItem('nube_nombre_proyecto')} - Val${cont_valor} - ${format[0]}-${format[1]}-${format[2]}" >
                <i class="fas fa-download"></i> Descargar
              </a>
            </div>
            <div class="col-lg-4 mb-4">
              <a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/documento/${e.data.data2.doc83}"  target="_blank"  type="button" >
                <i class="fas fa-expand"></i> Ver completo
              </a>
            </div>
            <div class="col-lg-12 text-center">
              ${doc_view_extencion(e.data.data2.doc83, 'valorizacion', 'documento', '100%', '700')}
            </div>`
          ); 

          // mostramos el resumen
          docs_total += 1;
          porcent = (docs_total * 100 )/18;
          $('.total_docs_subidos').html(`Total ${docs_total}/18`);
          $('.porcentaje_progress').css({'width': `${porcent.toFixed(1)}%`});
          $('.porcentaje_numero').html(`${porcent.toFixed(1)} %`);

        } else {

          if ($("#tabs-8-3-tab").hasClass("no-doc") == false) { $("#tabs-8-3-tab").addClass('no-doc'); }

          $('#documento8-3').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }
      }

    } else {
      ver_errores(e);
    }
    // $('#lista_quincenas').html('');
    $('.icon-resumen-cargando').html('<i class="far fa-bookmark"></i>');

  }).fail( function(e) { ver_errores(e); } );
}

function add_data_form(indice,nom_title) {
  $("#indice").val(indice); 
  $("#nombre").val(nom_title); 

  $('#title-modal-1').html(nom_title);

  //console.log(nombredoc);  
}

//Función para desactivar registros
function subir_doc(idvalorizacion) {

  //console.log('idvalorizacion: ' + idvalorizacion);

  $("#idvalorizacion").val(idvalorizacion);

  $("#modal-agregar-valorizacion").modal('show'); 
}

function subir_doc_respuesta(idvalorizacion, indice) {

  $("#idvalorizacion").val(idvalorizacion);

  $("#indice").val(indice);
  
  $("#modal-agregar-valorizacion").modal('show'); 
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

