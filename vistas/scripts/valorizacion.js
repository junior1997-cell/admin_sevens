var tabla_principal;

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lValorizacion").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar_tbla_principal(localStorage.getItem('nube_idproyecto'));

  ver_quincenas(localStorage.getItem('nube_idproyecto'));  

  $("#guardar_registro").on("click", function (e) {  $("#submit-form-trabajador").submit(); });

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
    $('#tab-seleccione').hide(); 
    $('#tab-contenido').hide(); 
    $('#tab-info').show();
    $('#card-regresar').hide();
  } else {
    if (estados=='2') {
      $('#tab-seleccione').show(); 
      $('#tab-contenido').show(); 
      $('#tab-info').hide();
      $('#card-regresar').show();
    }    
  }
}

// ver las echas de quincenas
function ver_quincenas(nube_idproyecto) {

  $('#lista_quincenas').html('<i class="fas fa-spinner fa-pulse fa-2x"></i>'); //console.log(nube_idproyecto);

  $.post("../ajax/valorizacion.php?op=listarquincenas", { nube_idproyecto: nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); console.log(e);    

    $('#lista_quincenas').html('');

    // VALIDAMOS LAS FECHAS DE QUINCENA
    if (e.data) {     
        
      if (e.data.fecha_valorizacion == "quincenal") {

        $(".h1-titulo").html("Valorización - Quincenal");

        var fecha = format_d_m_a(e.data.fecha_inicio);  
        
        var fecha_i = sumaFecha(0,fecha);
  
        var cal_quincena  =e.data.plazo/15; var i=0;  var cont=0;

        while (i <= cal_quincena) {

          cont = cont+1;
    
          var fecha_inicio = fecha_i;
          
          fecha = sumaFecha(14,fecha_inicio); 
  
          let fecha_ii = format_a_m_d(fecha_inicio); let fecha_ff = format_a_m_d(fecha);
          
          $('#lista_quincenas').append(` <button id="boton-${i}" type="button" class="mb-2 btn bg-gradient-info text-center" onclick="fecha_quincena('${fecha_ii}', '${fecha_ff}', '${i}');"><i class="far fa-calendar-alt"></i> Valorización ${cont}<br>${fecha_inicio} // ${fecha}</button>`)
          
          fecha_i = sumaFecha(1,fecha);
    
          i++;
        }
      } else {

        if (e.data.fecha_valorizacion == "mensual") {

          $(".h1-titulo").html("Valorización - Mensual");

          var fecha = format_d_m_a(e.data.fecha_inicio);  var fecha_f = ""; var fecha_i = ""; //e.data.fecha_inicio

          var cal_mes  = false; var i=0;  var cont=0;

          while (cal_mes == false) {

            cont = cont+1;

            fecha_i = fecha;

            fecha_f = sumaFecha(29, fecha_i);

            let val_fecha_f = new Date( format_a_m_d(fecha_f) ); let val_fecha_proyecto = new Date(e.data.fecha_fin);
            
            // console.log(fecha_f + ' - '+e.data.fecha_fin);

            $('#lista_quincenas').append(` <button id="boton-${i}" type="button" class="mb-2 btn bg-gradient-info text-center" onclick="fecha_quincena('${format_a_m_d(fecha_i)}', '${format_a_m_d(fecha_f)}', '${i}');"><i class="far fa-calendar-alt"></i> Valorización ${cont}<br>${fecha_i} // ${fecha_f}</button>`)
            
            if (val_fecha_f.getTime() >= val_fecha_proyecto.getTime()) { cal_mes = true; }else{ cal_mes = false;}

            fecha = sumaFecha(1,fecha_f);

            i++;
          }          

        } else {

          if (e.data.fecha_valorizacion == "al finalizar") {

            $(".h1-titulo").html("Valorización - Al finalizar");

            $('#lista_quincenas').append(` <button id="boton-0" type="button" class="mb-2 btn bg-gradient-info text-center" onclick="fecha_quincena('${e.data.fecha_inicio}', '${e.data.fecha_fin}', '0');"><i class="far fa-calendar-alt"></i> Valorización 1<br>${format_d_m_a(e.data.fecha_inicio)} // ${format_d_m_a(e.data.fecha_fin)}</button>`)

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

          $("#modal-agregar-valorizacion").modal("hide");

          tabla_principal.ajax.reload(null, false);
          mostrar_form_table(2);
          // fecha_quincena(localStorage.getItem('fecha_i'), localStorage.getItem('fecha_f'), localStorage.getItem('i'))

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

//Función Listar - tabla principal
function listar_tbla_principal(nube_idproyecto) {

  tabla_principal = $('#tabla-principal').dataTable({
    responsive: true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [{ extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
    ajax:{
      url: `../ajax/valorizacion.php?op=listar_tbla_principal&nube_idproyecto=${nube_idproyecto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[0] != '') { $("td", row).eq(0).addClass('text-nowrap'); }           
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: {
        copyTitle: "Tabla Copiada",
        copySuccess: {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();  
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
      '<div class="col-lg-6">'+
      '<a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" >'+
          '<i class="fas fa-download"></i> Descargar'+
      '</a>'+
      '</div>'+
      '<div class="col-lg-6 mb-4">'+
      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
          '<i class="fas fa-expand"></i> Ver completo'+
      '</a>'+
      '</div>'+
      '<div class="col-lg-12 ">'+
        '<div class="embed-responsive" style="padding-bottom:30%" >'+
            '<div class="alert alert-warning alert-dismissible">'+
                '<button type="button" class="close" data-dismiss="Alerta" aria-hidden="true">×</button><h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>'+
                'No hay un documento para ver. Edite este registro y vuelva a intentar.'+
            '</div>'+
        '</div>'+
      '</div>'
    );
  } else {
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
      <div class="col-lg-12 "> ${tipo_doc} </div>`);    
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
    function(){ tabla_principal.ajax.reload(null, false) },
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
      nombre: {
        required: "Por favor selecione un tipo de documento", 
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

    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardaryeditar(e);
    },

  });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

// captura las fechas de quincenas y trae los datos
function fecha_quincena(fecha_i, fecha_f, i) {
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

  // traemos loa documentos por fechas de la quincena
  $.post("../ajax/valorizacion.php?op=mostrar-docs-quincena", { nube_idproyecto: nube_idproyecto, fecha_i: fecha_i, fecha_f: fecha_f }, function (e, status) {

    e =JSON.parse(e); console.log(e);  
    
    var vacio = "''";   var count_data2 = 0;

    // validamos la data total
    if (e.status == true) {
      if (e.data.data2.doc1 == "") { count_data2  = count_data2 + 0  } else { count_data2  = count_data2 + 1 }
      if (e.data.data2.doc4 == "") { count_data2  = count_data2 + 0  } else { count_data2  = count_data2 + 1 }
      if (e.data.data2.doc81 == "") { count_data2  = count_data2 + 0  } else { count_data2  = count_data2 + 1 }
      if (e.data.data2.doc82 == "") { count_data2  = count_data2 + 0  } else { count_data2  = count_data2 + 1 }
      if (e.data.data2.doc83 == "") { count_data2  = count_data2 + 0  } else { count_data2  = count_data2 + 1 }
      
      var docs_total = count_data2 + parseInt(e.data.count_data1);
      var porcent = (docs_total * 100 )/18;
      // mostramos el resumen
      $("#tabs-resumen").html(
        '<div class="info-box bg-warning">'+
          '<span class="info-box-icon"><i class="far fa-bookmark"></i></span>'+
          '<div class="info-box-content">'+
            '<span class="info-box-text">Documentos Subidos</span>'+
            '<span class="info-box-number">Total ' + docs_total + '/18</span>'+
            '<div class="progress" style="height: 10px !important;"> '+
              '<div class="progress-bar" style="width: '+porcent.toFixed(1)+'%"></div>'+
            '</div>'+
            '<span class="progress-description">'+
              'Tienes un <b> '+porcent.toFixed(1)+'%</b> de documentos subidos!!!'+
            '</span>'+
          '</div>'+
        '</div>'
      );

      // exraemos la fecha de HOY
      var tiempoTranscurrido = Date.now();
      var hoy = new Date(tiempoTranscurrido);
      var format = hoy.toLocaleDateString().split("/"); //console.log(format);
      
      // validamos la data1
      if (e.data.data1.length === 0) {
        //console.log('data 1 no existe');
        // pintamos rojos los que no tienen docs
        if ($("#tabs-2-tab").hasClass("si-doc") == false || $("#tabs-2-tab").hasClass("si-doc") == true) { $("#tabs-2-tab").addClass('no-doc').removeClass('si-doc'); }   
        if ($("#tabs-3-1-tab").hasClass("si-doc") == false || $("#tabs-3-1-tab").hasClass("si-doc") == true ) { $("#tabs-3-1-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-2-tab").hasClass("si-doc") == false || $("#tabs-3-2-tab").hasClass("si-doc") == true ) { $("#tabs-3-2-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-3-tab").hasClass("si-doc") == false || $("#tabs-3-3-tab").hasClass("si-doc") == true ) { $("#tabs-3-3-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-4-tab").hasClass("si-doc") == false || $("#tabs-3-4-tab").hasClass("si-doc") == true ) { $("#tabs-3-4-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-5-1-tab").hasClass("si-doc") == false || $("#tabs-5-1-tab").hasClass("si-doc") == true ) { $("#tabs-5-1-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-5-2-tab").hasClass("si-doc") == false || $("#tabs-5-2-tab").hasClass("si-doc") == true ) { $("#tabs-5-2-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-6-tab").hasClass("si-doc") == false || $("#tabs-6-tab").hasClass("si-doc") == true ) { $("#tabs-6-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-7-tab").hasClass("si-doc") == false || $("#tabs-7-tab").hasClass("si-doc") == true ) { $("#tabs-7-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-4-tab").hasClass("si-doc") == false || $("#tabs-8-4-tab").hasClass("si-doc") == true ) { $("#tabs-8-4-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-5-tab").hasClass("si-doc") == false || $("#tabs-8-5-tab").hasClass("si-doc") == true ) { $("#tabs-8-5-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-6-tab").hasClass("si-doc") == false || $("#tabs-8-6-tab").hasClass("si-doc") == true ) { $("#tabs-8-6-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-7-tab").hasClass("si-doc") == false || $("#tabs-8-7-tab").hasClass("si-doc") == true ) { $("#tabs-8-7-tab").addClass('no-doc').removeClass('si-doc'); }
        
        $('#documento2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento3-1').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento3-2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento3-3').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento3-4').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento5-1').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento5-2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+vacio + ','+ "'" + '5.2' + "'" + ');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:30%" > No hay documento para mostrar </div> </div>' );
        $('#documento5-2-1').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+vacio + ','+ "'" + '5.2.1' + "'" + ');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:30%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento6').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento7').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento8-4').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento8-5').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento8-6').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 
        $('#documento8-7').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); 

      } else {
        // Borradmos las clases
        if ($("#tabs-2-tab").hasClass("si-doc") == false || $("#tabs-2-tab").hasClass("si-doc") == true) { $("#tabs-2-tab").addClass('no-doc').removeClass('si-doc'); }   
        if ($("#tabs-3-1-tab").hasClass("si-doc") == false || $("#tabs-3-1-tab").hasClass("si-doc") == true ) { $("#tabs-3-1-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-2-tab").hasClass("si-doc") == false || $("#tabs-3-2-tab").hasClass("si-doc") == true ) { $("#tabs-3-2-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-3-tab").hasClass("si-doc") == false || $("#tabs-3-3-tab").hasClass("si-doc") == true ) { $("#tabs-3-3-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-3-4-tab").hasClass("si-doc") == false || $("#tabs-3-4-tab").hasClass("si-doc") == true ) { $("#tabs-3-4-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-5-1-tab").hasClass("si-doc") == false || $("#tabs-5-1-tab").hasClass("si-doc") == true ) { $("#tabs-5-1-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-5-2-tab").hasClass("si-doc") == false || $("#tabs-5-2-tab").hasClass("si-doc") == true ) { $("#tabs-5-2-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-6-tab").hasClass("si-doc") == false || $("#tabs-6-tab").hasClass("si-doc") == true ) { $("#tabs-6-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-7-tab").hasClass("si-doc") == false || $("#tabs-7-tab").hasClass("si-doc") == true ) { $("#tabs-7-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-4-tab").hasClass("si-doc") == false || $("#tabs-8-4-tab").hasClass("si-doc") == true ) { $("#tabs-8-4-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-5-tab").hasClass("si-doc") == false || $("#tabs-8-5-tab").hasClass("si-doc") == true ) { $("#tabs-8-5-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-6-tab").hasClass("si-doc") == false || $("#tabs-8-6-tab").hasClass("si-doc") == true ) { $("#tabs-8-6-tab").addClass('no-doc').removeClass('si-doc'); }
        if ($("#tabs-8-7-tab").hasClass("si-doc") == false || $("#tabs-8-7-tab").hasClass("si-doc") == true ) { $("#tabs-8-7-tab").addClass('no-doc').removeClass('si-doc'); }
        
        $.each(e.data.data1, function (index, value) {

          if (value.indice == "2") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-2-tab").hasClass("no-doc") == false || $("#tabs-2-tab").hasClass("no-doc") == true) { $("#tabs-2-tab").removeClass('no-doc').addClass("si-doc"); }          
            
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento2').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                 
                $('#documento2').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                   
                  $('#documento2').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento2').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento2').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento2').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="2 Informe tecnico - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }
                      
          }

          if (value.indice == "3.1") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-3-1-tab").hasClass("no-doc") == false || $("#tabs-3-1-tab").hasClass("no-doc") == true) { $("#tabs-3-1-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento3-1').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento3-1').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento3-1').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento3-1').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento3-1').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento3-1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento3-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento3-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-1 Planilla de metrados - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }         
          }

          if (value.indice == "3.2") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-3-2-tab").hasClass("no-doc") == false || $("#tabs-3-2-tab").hasClass("no-doc") == true) { $("#tabs-3-2-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento3-2').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento3-2').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento3-2').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento3-2').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento3-2').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento3-2').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento3-2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento3-2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-2 Valorizaciones - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }          
          }

          if (value.indice == "3.3") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-3-3-tab").hasClass("no-doc") == false || $("#tabs-3-3-tab").hasClass("no-doc") == true) { $("#tabs-3-3-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento3-3').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento3-3').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento3-3').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento3-3').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento3-3').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento3-3').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento3-3').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento3-3').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-3 Resumen de valorizacion - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }          
          }

          if (value.indice == "3.4") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-3-4-tab").hasClass("no-doc") == false || $("#tabs-3-4-tab").hasClass("no-doc") == true) { $("#tabs-3-4-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento3-4').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento3-4').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento3-4').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento3-4').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento3-4').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento3-4').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento3-4').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento3-4').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="3-4 Curva S - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }         
          }

          if (value.indice == "5.1") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-5-1-tab").hasClass("no-doc") == false || $("#tabs-5-1-tab").hasClass("no-doc") == true) { $("#tabs-5-1-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento5-1').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento5-1').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento5-1').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento5-1').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento5-1').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento5-1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento5-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento5-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-1 Ensayo de consi. del concreto - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }         
          }

          if (value.indice == "5.2") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-5-2-tab").hasClass("no-doc") == false || $("#tabs-5-2-tab").hasClass("no-doc") == true) { $("#tabs-5-2-tab").removeClass('no-doc').addClass("si-doc"); }          
             
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {
              
              $('#documento5-2').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + '5.2' + "'" + ');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {                
                
                $('#documento5-2').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + '5.2' + "'" + ');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {                  
                  
                  $('#documento5-2').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + '5.2' + "'" + ');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {
                    
                    $('#documento5-2').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + '5.2' + "'" + ');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento5-2').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + '5.2' + "'" + ');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:30%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento5-2').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + '5.2' + "'" + ');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento5-2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + '5.2' + "'" + ');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento5-2').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + '5.2' + "'" + ');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2 Ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }
          }

          if (value.indice == "5.2.1") {  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {
              
              $('#documento5-2-1').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + '5.2.1' + "'" + ');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {                
                
                $('#documento5-2-1').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + '5.2.1' + "'" + ');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {                  
                  
                  $('#documento5-2-1').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' + "'" + '5.2.1' + "'" + ');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {
                    
                    $('#documento5-2-1').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' +  "'" + '5.2.1' + "'" + ');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="#"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento5-2-1').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' +  "'" + '5.2.1' + "'" + ');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:30%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento5-2-1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' +  "'" + '5.2.1' + "'" + ');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento5-2-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' +  "'" + '5.2.1' + "'" + ');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento5-2-1').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+value.idvalorizacion+',' +  "'" + '5.2.1' + "'" + ');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="5-2-1 Respuesta de ensayo de compresión - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:30%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }              

            respuestadoc5_2 = true;
          }

          if (value.indice == "6") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-6-tab").hasClass("no-doc") == false || $("#tabs-6-tab").hasClass("no-doc") == true) { $("#tabs-6-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento6').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento6').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento6').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento6').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento6').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento6').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento6').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento6').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="6 Plan de seg y salud en el trabajo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }          
          }

          if (value.indice == "7") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-7-tab").hasClass("no-doc") == false || $("#tabs-7-tab").hasClass("no-doc") == true) { $("#tabs-7-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento7').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento7').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento7').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento7').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento7').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento7').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento7').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento7').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="7 Plan de bioseguridad COVID19 - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }          
          }

          if (value.indice == "8.4") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-8-4-tab").hasClass("no-doc") == false || $("#tabs-8-4-tab").hasClass("no-doc") == true) { $("#tabs-8-4-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento8-4').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento8-4').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento8-4').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento8-4').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento8-4').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento8-4').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento8-4').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento8-4').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-4 Planilla del personal obrero - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }          
          }

          if (value.indice == "8.5") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-8-5-tab").hasClass("no-doc") == false || $("#tabs-8-5-tab").hasClass("no-doc") == true) { $("#tabs-8-5-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento8-5').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento8-5').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento8-5').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento8-5').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento8-5').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento8-5').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento8-5').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento8-5').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-5 Copia del seguro complement contra todo riesgo - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }          
          }

          if (value.indice == "8.6") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-8-6-tab").hasClass("no-doc") == false || $("#tabs-8-6-tab").hasClass("no-doc") == true) { $("#tabs-8-6-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento8-6').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento8-6').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento8-6').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento8-6').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento8-6').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento8-6').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico  - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento8-6').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico  - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento8-6').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-6 Panel fotográfico  - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }          
          }

          if (value.indice == "8.7") {
            // pintamos rojos los que no tienen docs
            if ($("#tabs-8-7-tab").hasClass("no-doc") == false || $("#tabs-8-7-tab").hasClass("no-doc") == true) { $("#tabs-8-7-tab").removeClass('no-doc').addClass("si-doc"); }          
  
            // cargamos la imagen adecuada par el archivo
            if ( extrae_extencion(value.doc_valorizacion) == "xls") {

              $('#documento8-7').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            } else {

              if ( extrae_extencion(value.doc_valorizacion) == "xlsx" ) {
                
                $('#documento8-7').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(value.doc_valorizacion) == "csv" ) {
                  
                  $('#documento8-7').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                      '</div>'+
                    '</div>'
                  );

                }else{

                  if ( extrae_extencion(value.doc_valorizacion) == "xlsm" ) {

                    $('#documento8-7').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                          '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                        '</div>'+
                      '</div>'
                    );
    
                  }else{
    
                    if ( extrae_extencion(value.doc_valorizacion) == "pdf" ) {

                      $('#documento8-7').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'"  target="_blank"  type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                            '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+value.doc_valorizacion+'" type="application/pdf" width="100%" height="100%" />'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(value.doc_valorizacion) == "doc" ) {

                        $('#documento8-7').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );     
                      }else{
                        if ( extrae_extencion(value.doc_valorizacion) == "docx" ) {

                          $('#documento8-7').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );     
                        }else{
                          $('#documento8-7').html(
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+value.idvalorizacion+');">'+
                                '<i class="fas fa-file-upload"></i> Subir'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4">'+
                              '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+value.doc_valorizacion+'" download="8-7 Copia del cuaderno de obra - '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                                '<i class="fas fa-download"></i> Descargar'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-4 mb-4">'+
                              '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                                '<i class="fas fa-expand"></i> Ver completo'+
                              '</a>'+
                            '</div>'+
                            '<div class="col-lg-12 ">'+
                              '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                                '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                              '</div>'+
                            '</div>'
                          );
                        }
                      }
                    }
                  }
                }
              }
            }          
          }
          
        });

        // pintamos rojos los que no tienen docs
        if ($("#tabs-2-tab").hasClass("si-doc") == false) { $("#tabs-2-tab").addClass('no-doc').removeClass('si-doc'); $('#documento2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); } 
        if ($("#tabs-3-1-tab").hasClass("si-doc") == false ) { $("#tabs-3-1-tab").addClass('no-doc').removeClass('si-doc'); $('#documento3-1').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-3-2-tab").hasClass("si-doc") == false ) { $("#tabs-3-2-tab").addClass('no-doc').removeClass('si-doc'); $('#documento3-2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-3-3-tab").hasClass("si-doc") == false ) { $("#tabs-3-3-tab").addClass('no-doc').removeClass('si-doc'); $('#documento3-3').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-3-4-tab").hasClass("si-doc") == false ) { $("#tabs-3-4-tab").addClass('no-doc').removeClass('si-doc'); $('#documento3-4').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-5-1-tab").hasClass("si-doc") == false ) { $("#tabs-5-1-tab").addClass('no-doc').removeClass('si-doc'); $('#documento5-1').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-5-2-tab").hasClass("si-doc") == false ) { $("#tabs-5-2-tab").addClass('no-doc').removeClass('si-doc'); $('#documento5-2').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+vacio + ','+ "'" + '5.2' + "'" + ');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:30%" > No hay documento para mostrar </div> </div>' ); }
        if (respuestadoc5_2 == false) { $('#documento5-2-1').html('<div class="col-lg-4 "> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc_respuesta('+vacio + ','+ "'" + '5.2.1' + "'" + ');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:30%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-6-tab").hasClass("si-doc") == false ) { $("#tabs-6-tab").addClass('no-doc').removeClass('si-doc'); $('#documento6').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-7-tab").hasClass("si-doc") == false ) { $("#tabs-7-tab").addClass('no-doc').removeClass('si-doc'); $('#documento7').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-8-4-tab").hasClass("si-doc") == false ) { $("#tabs-8-4-tab").addClass('no-doc').removeClass('si-doc'); $('#documento8-4').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-8-5-tab").hasClass("si-doc") == false ) { $("#tabs-8-4-tab").addClass('no-doc').removeClass('si-doc'); $('#documento8-5').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-8-6-tab").hasClass("si-doc") == false ) { $("#tabs-8-6-tab").addClass('no-doc').removeClass('si-doc'); $('#documento8-6').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
        if ($("#tabs-8-7-tab").hasClass("si-doc") == false ) { $("#tabs-8-7-tab").addClass('no-doc').removeClass('si-doc'); $('#documento8-7').html('<div class="col-lg-4"> <a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+vacio+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning  btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a> </div> <div class="col-lg-4 mb-4"> <a  class="btn btn-info  btn-block btn-xs disabled" href="#" type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' ); }
      }

      // validamos la data2
      if (e.data.data2.length === 0) {
        console.log('data 2 no existe');
      } else {

        if (e.data.data2.doc1 != "") {

          if ($("#tabs-1-tab").hasClass("no-doc")) { $("#tabs-1-tab").removeClass('no-doc'); }          

          // cargamos la imagen adecuada par el archivo
          if ( extrae_extencion(e.data.data2.doc1) == "xls") {

            $('#documento1').html(
              '<div class="col-lg-4">'+
                '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                  '<i class="fas fa-file-upload"></i> Subir'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4">'+
                '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                  '<i class="fas fa-download"></i> Descargar'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4 mb-4">'+
                '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                  '<i class="fas fa-expand"></i> Ver completo'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-12 ">'+
                '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                  '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                '</div>'+
              '</div>'
            );

          } else {

            if ( extrae_extencion(e.data.data2.doc1) == "xlsx" ) {
              
              $('#documento1').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            }else{

              if ( extrae_extencion(e.data.data2.doc1) == "csv" ) {
                
                $('#documento1').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(e.data.data2.doc1) == "xlsm" ) {

                  $('#documento1').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                      '</div>'+
                    '</div>'
                  );
  
                }else{
  
                  if ( extrae_extencion(e.data.data2.doc1) == "pdf" ) {

                    $('#documento1').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+e.data.data2.doc1+'"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                          '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+e.data.data2.doc1+'" type="application/pdf" width="100%" height="100%" />'+
                        '</div>'+
                      '</div>'
                    );      
                  }else{
                    if ( extrae_extencion(e.data.data2.doc1) == "doc" ) {

                      $('#documento1').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                            '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(e.data.data2.doc1) == "docx" ) {

                        $('#documento1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );      
                      }else{
                        $('#documento1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc1+'" download="1 Copia del contrato -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        ); 
                      }
                    }
                  }
                }
              }
            }
          }
        
        } else {

          if ($("#tabs-1-tab").hasClass("no-doc") == false) { $("#tabs-1-tab").addClass('no-doc'); }

          $('#documento1').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (e.data.data2.doc4 != "") {

          if ($("#tabs-4-tab").hasClass("no-doc")) { $("#tabs-4-tab").removeClass('no-doc'); }           

          // cargamos la imagen adecuada par el archivo
          if ( extrae_extencion(e.data.data2.doc4) == "xls") {

            $('#documento4').html(
              '<div class="col-lg-4">'+
                '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                  '<i class="fas fa-file-upload"></i> Subir'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4">'+
                '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                  '<i class="fas fa-download"></i> Descargar'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4 mb-4">'+
                '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                  '<i class="fas fa-expand"></i> Ver completo'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-12 ">'+
                '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                  '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                '</div>'+
              '</div>'
            );

          } else {

            if ( extrae_extencion(e.data.data2.doc4) == "xlsx" ) {
              
              $('#documento4').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            }else{

              if ( extrae_extencion(e.data.data2.doc4) == "csv" ) {
                
                $('#documento4').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(e.data.data2.doc4) == "xlsm" ) {

                  $('#documento4').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                      '</div>'+
                    '</div>'
                  );
  
                }else{
  
                  if ( extrae_extencion(e.data.data2.doc4) == "pdf" ) {

                    $('#documento4').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+e.data.data2.doc4+'"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                          '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+e.data.data2.doc4+'" type="application/pdf" width="100%" height="100%" />'+
                        '</div>'+
                      '</div>'
                    );      
                  }else{
                    if ( extrae_extencion(e.data.data2.doc4) == "doc" ) {

                      $('#documento4').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                            '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(e.data.data2.doc4) == "docx" ) {

                        $('#documento4').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );      
                      }else{
                        $('#documento4').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc4+'" download="4 Cronograma de obra valorizado -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        ); 
                      }
                    }
                  }
                }
              }
            }
          }
        } else {

          if ($("#tabs-4-tab").hasClass("no-doc") == false) { $("#tabs-4-tab").addClass('no-doc'); }

          $('#documento4').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (e.data.data2.doc81 != "") {

          if ($("#tabs-8-1-tab").hasClass("no-doc")) { $("#tabs-8-1-tab").removeClass('no-doc'); }

          // cargamos la imagen adecuada par el archivo
          if ( extrae_extencion(e.data.data2.doc81) == "xls") {

            $('#documento8-1').html(
              '<div class="col-lg-4">'+
                '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                  '<i class="fas fa-file-upload"></i> Subir'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4">'+
                '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                  '<i class="fas fa-download"></i> Descargar'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4 mb-4">'+
                '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                  '<i class="fas fa-expand"></i> Ver completo'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-12 ">'+
                '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                  '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                '</div>'+
              '</div>'
            );

          } else {

            if ( extrae_extencion(e.data.data2.doc81) == "xlsx" ) {
              
              $('#documento8-1').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            }else{

              if ( extrae_extencion(e.data.data2.doc81) == "csv" ) {
                
                $('#documento8-1').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(e.data.data2.doc81) == "xlsm" ) {

                  $('#documento8-1').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                      '</div>'+
                    '</div>'
                  );
  
                }else{
  
                  if ( extrae_extencion(e.data.data2.doc81) == "pdf" ) {

                    $('#documento8-1').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+e.data.data2.doc81+'"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                          '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+e.data.data2.doc81+'" type="application/pdf" width="100%" height="100%" />'+
                        '</div>'+
                      '</div>'
                    );      
                  }else{
                    if ( extrae_extencion(e.data.data2.doc81) == "doc" ) {

                      $('#documento8-1').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                            '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(e.data.data2.doc81) == "docx" ) {

                        $('#documento8-1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );      
                      }else{
                        $('#documento8-1').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc81+'" download="8-1 Acta de entrega de terreno -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        ); 
                      }
                    }
                  }
                }
              }
            }
          }
        } else {

          if ($("#tabs-8-1-tab").hasClass("no-doc") == false) { $("#tabs-8-1-tab").addClass('no-doc'); }

          $('#documento8-1').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (e.data.data2.doc82 != "") {

          if ($("#tabs-8-2-tab").hasClass("no-doc")) { $("#tabs-8-2-tab").removeClass('no-doc'); }

          // cargamos la imagen adecuada par el archivo
          if ( extrae_extencion(e.data.data2.doc82) == "xls") {

            $('#documento8-2').html(
              '<div class="col-lg-4">'+
                '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                  '<i class="fas fa-file-upload"></i> Subir'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4">'+
                '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                  '<i class="fas fa-download"></i> Descargar'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4 mb-4">'+
                '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                  '<i class="fas fa-expand"></i> Ver completo'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-12 ">'+
                '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                  '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                '</div>'+
              '</div>'
            );

          } else {

            if ( extrae_extencion(e.data.data2.doc82) == "xlsx" ) {
              
              $('#documento8-2').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            }else{

              if ( extrae_extencion(e.data.data2.doc82) == "csv" ) {
                
                $('#documento8-2').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(e.data.data2.doc82) == "xlsm" ) {

                  $('#documento8-2').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                      '</div>'+
                    '</div>'
                  );
  
                }else{
  
                  if ( extrae_extencion(e.data.data2.doc82) == "pdf" ) {

                    $('#documento8-2').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+e.data.data2.doc82+'"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                          '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+e.data.data2.doc82+'" type="application/pdf" width="100%" height="100%" />'+
                        '</div>'+
                      '</div>'
                    );      
                  }else{
                    if ( extrae_extencion(e.data.data2.doc82) == "doc" ) {

                      $('#documento8-2').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                            '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(e.data.data2.doc82) == "docx" ) {

                        $('#documento8-2').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );      
                      }else{
                        $('#documento8-2').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc82+'" download="8-2 Acta de inicio de obra -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        ); 
                      }
                    }
                  }
                }
              }
            }
          }
        } else {

          if ($("#tabs-8-2-tab").hasClass("no-doc") == false) { $("#tabs-8-2-tab").addClass('no-doc'); }

          $('#documento8-2').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }

        if (e.data.data2.doc83 != "") {

          if ($("#tabs-8-3-tab").hasClass("no-doc")) { $("#tabs-8-3-tab").removeClass('no-doc'); }

          // cargamos la imagen adecuada par el archivo
          if ( extrae_extencion(e.data.data2.doc83) == "xls") {

            $('#documento8-3').html(
              '<div class="col-lg-4">'+
                '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                  '<i class="fas fa-file-upload"></i> Subir'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4">'+
                '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                  '<i class="fas fa-download"></i> Descargar'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-4 mb-4">'+
                '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                  '<i class="fas fa-expand"></i> Ver completo'+
                '</a>'+
              '</div>'+
              '<div class="col-lg-12 ">'+
                '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                  '<img src="../dist/svg/xls.svg" alt="" width="auto" height="300" >'+
                '</div>'+
              '</div>'
            );

          } else {

            if ( extrae_extencion(e.data.data2.doc83) == "xlsx" ) {
              
              $('#documento8-3').html(
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                    '<i class="fas fa-file-upload"></i> Subir'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4">'+
                  '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                    '<i class="fas fa-download"></i> Descargar'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-4 mb-4">'+
                  '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                    '<i class="fas fa-expand"></i> Ver completo'+
                  '</a>'+
                '</div>'+
                '<div class="col-lg-12 ">'+
                  '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                    '<img src="../dist/svg/xlsx.svg" alt="" width="auto" height="300" >'+
                  '</div>'+
                '</div>'
              );

            }else{

              if ( extrae_extencion(e.data.data2.doc83) == "csv" ) {
                
                $('#documento8-3').html(
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                      '<i class="fas fa-file-upload"></i> Subir'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4">'+
                    '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                      '<i class="fas fa-download"></i> Descargar'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-4 mb-4">'+
                    '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                      '<i class="fas fa-expand"></i> Ver completo'+
                    '</a>'+
                  '</div>'+
                  '<div class="col-lg-12 ">'+
                    '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                      '<img src="../dist/svg/csv.svg" alt="" width="auto" height="300" >'+
                    '</div>'+
                  '</div>'
                );

              }else{

                if ( extrae_extencion(e.data.data2.doc83) == "xlsm" ) {

                  $('#documento8-3').html(
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                        '<i class="fas fa-file-upload"></i> Subir'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                      '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                        '<i class="fas fa-download"></i> Descargar'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-4 mb-4">'+
                      '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                        '<i class="fas fa-expand"></i> Ver completo'+
                      '</a>'+
                    '</div>'+
                    '<div class="col-lg-12 ">'+
                      '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                        '<img src="../dist/svg/xlsm.svg" alt="" width="auto" height="300">'+
                      '</div>'+
                    '</div>'
                  );
  
                }else{
  
                  if ( extrae_extencion(e.data.data2.doc83) == "pdf" ) {

                    $('#documento8-3').html(
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                          '<i class="fas fa-file-upload"></i> Subir'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4">'+
                        '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                          '<i class="fas fa-download"></i> Descargar'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-4 mb-4">'+
                        '<a  class="btn btn-info  btn-block btn-xs" href="../dist/docs/valorizacion/'+e.data.data2.doc83+'"  target="_blank"  type="button" >'+
                          '<i class="fas fa-expand"></i> Ver completo'+
                        '</a>'+
                      '</div>'+
                      '<div class="col-lg-12 ">'+
                        '<div class="embed-responsive disenio-scroll" style="padding-bottom:90%" >'+
                          '<embed class="disenio-scroll" src="../dist/docs/valorizacion/'+e.data.data2.doc83+'" type="application/pdf" width="100%" height="100%" />'+
                        '</div>'+
                      '</div>'
                    );      
                  }else{
                    if ( extrae_extencion(e.data.data2.doc83) == "doc" ) {

                      $('#documento8-3').html(
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                            '<i class="fas fa-file-upload"></i> Subir'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4">'+
                          '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                            '<i class="fas fa-download"></i> Descargar'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-4 mb-4">'+
                          '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                            '<i class="fas fa-expand"></i> Ver completo'+
                          '</a>'+
                        '</div>'+
                        '<div class="col-lg-12 ">'+
                          '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                            '<img src="../dist/svg/doc.svg" alt="" width="auto" height="300">'+
                          '</div>'+
                        '</div>'
                      );      
                    }else{
                      if ( extrae_extencion(e.data.data2.doc83) == "docx" ) {

                        $('#documento8-3').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/docx.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        );      
                      }else{
                        $('#documento8-3').html(
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-success  btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');">'+
                              '<i class="fas fa-file-upload"></i> Subir'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4">'+
                            '<a  class="btn btn-warning  btn-block btn-xs" type="button" href="../dist/docs/valorizacion/'+e.data.data2.doc83+'" download="8-3 Certif de habil del ing resident -  '+localStorage.getItem('nube_nombre_proyecto')+' - Val'+cont_valor+' - '+format[0]+'-'+format[1]+'-'+format[2]+'" >'+
                              '<i class="fas fa-download"></i> Descargar'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-4 mb-4">'+
                            '<a  class="btn btn-info  btn-block btn-xs disabled " href="#" type="button" >'+
                              '<i class="fas fa-expand"></i> Ver completo'+
                            '</a>'+
                          '</div>'+
                          '<div class="col-lg-12 ">'+
                            '<div class="embed-responsive disenio-scroll text-center" style="padding-bottom:90%" >'+
                              '<img src="../dist/svg/doc_si_extencion.svg" alt="" width="auto" height="300">'+
                            '</div>'+
                          '</div>'
                        ); 
                      }
                    }
                  }
                }
              }
            }
          }
        } else {

          if ($("#tabs-8-3-tab").hasClass("no-doc") == false) { $("#tabs-8-3-tab").addClass('no-doc'); }

          $('#documento8-3').html('<div class="col-lg-4"> <a  class="btn btn-success btn-block btn-xs" type="button" onclick="subir_doc('+e.data.data2.idproyecto+');"> <i class="fas fa-file-upload"></i> Subir </a> </div> <div class="col-lg-4"> <a  class="btn btn-warning btn-block btn-xs disabled" type="button" href="#" > <i class="fas fa-download"></i> Descargar </a></div> <div class="col-lg-4 mb-4"><a  class="btn btn-info  btn-block btn-xs disabled" href="#"  target="_blank"  type="button" > <i class="fas fa-expand"></i> Ver completo </a> </div> <div class="col-lg-12 "> <div class="embed-responsive disenio-scroll" style="padding-bottom:90%" > No hay documento para mostrar </div> </div>' );
        }
      }

    } else {
      ver_errores(e);
    }
    // $('#lista_quincenas').html('');

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

function sumar_mes(fecha) {

  var split_fecha =  fecha.split("-");

  // var format_fecha = format_d_m_a(fecha);

  var dias_total_mes = cantDiasEnUnMes( parseInt(split_fecha[1]), parseInt(split_fecha[0]) );  

  var mes_next =  sumaFecha(dias_total_mes-1, fecha); 

  // console.log(`🚀 ${fecha} + ${dias_total_mes-1} =  fecha_f:${mes_next}`);  

  return mes_next;
}

function cantDiasEnUnMes(mes, año) {
   
  var diasMes = new Date(año, mes, 0).getDate(); // console.log('mes:' + mes+ ' cant:' + diasMes);

  return diasMes; 
}

function despintar_btn_select() {  
  if (localStorage.getItem('boton_id')) { let id = localStorage.getItem('boton_id'); $("#boton-" + id).removeClass('click-boton'); }
}
