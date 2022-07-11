var tabla;  

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Accesos").addClass("menu-open bg-color-191f24");

  $("#mAccesos").addClass("active");

  $("#lUsuario").addClass("active");

  tbla_principal();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  

  lista_select2("../ajax/usuario.php?op=select2Trabajador", '#trabajador', null);
  lista_select2("../ajax/ajax_general.php?op=select2Banco", '#banco_trab', null);
  lista_select2("../ajax/ajax_general.php?op=select2TipoTrabajador", '#tipo_trab', null);
  lista_select2("../ajax/ajax_general.php?op=select2OcupacionTrabajador", '#ocupacion_trab', null);
  
  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════

  //$("#guardar_registro_usuario").on("click", function (e) { $("#submit-form-usuario").submit(); });

  $("#guardar_registro_trabajador").on("click", function (e) {  $("#submit-form-trabajador").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#trabajador").select2({ templateResult: formatState, theme: "bootstrap4",  placeholder: "Selecione trabajador", allowClear: true, });  

  $("#cargo").select2({ theme: "bootstrap4", placeholder: "Selecione cargo", allowClear: true, });
  $("#banco_trab").select2({ theme: "bootstrap4", placeholder: "Selecione banco", allowClear: true, });
  $("#tipo_trab").select2({ theme: "bootstrap4", placeholder: "Selecione tipo", allowClear: true, });
  $("#ocupacion_trab").select2({ theme: "bootstrap4",  placeholder: "Selecione Ocupación", allowClear: true, });

  // restringimos la fecha para no elegir mañana
  no_select_tomorrow('#nacimiento_trab')
  
  // Formato para telefono
  $("[data-mask]").inputmask();   
}

function formatState (state) {
  //console.log(state);
  if (!state.id) { return state.text; }
  var baseUrl = state.title != '' ? `../dist/docs/all_trabajador/perfil/${state.title}`: '../dist/svg/user_default.svg'; 
  var onerror = `onerror="this.src='../dist/svg/user_default.svg';"`;
  var $state = $(`<span><img src="${baseUrl}" class="img-circle mr-2 w-25px" ${onerror} />${state.text}</span>`);
  return $state;
};

// abrimos el navegador de archivos
$("#foto1_i").click(function() { $('#foto1').trigger('click'); });
$("#foto1").change(function(e) { addImage(e,$("#foto1").attr("id"), ) });

$("#foto2_i").click(function() { $('#foto2').trigger('click'); });
$("#foto2").change(function(e) { addImage(e, $("#foto2").attr("id"), "../dist/img/default/dni_anverso.webp" ) });

$("#foto3_i").click(function() { $('#foto3').trigger('click'); });
$("#foto3").change(function(e) { addImage(e,$("#foto3").attr("id"), "../dist/img/default/dni_reverso.webp") });

$("#doc4_i").click(function() {  $('#doc4').trigger('click'); });
$("#doc4").change(function(e) {  addImageApplication(e,$("#doc4").attr("id")) });

$("#doc5_i").click(function() {  $('#doc5').trigger('click'); });
$("#doc5").change(function(e) {  addImageApplication(e,$("#doc5").attr("id")) });

function foto1_eliminar() {

	$("#foto1").val("");
	$("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1_nombre").html("");
}

function foto2_eliminar() {	

  $("#foto2").val(""); 
  $("#foto2_i").attr("src", "../dist/img/default/dni_anverso.webp");	
  $("#foto2_nombre").html(""); 
}

function foto3_eliminar() {	

  $("#foto3").val(""); 
  $("#foto3_i").attr("src", "../dist/img/default/dni_reverso.webp");	
  $("#foto3_nombre").html(""); 
}

// Eliminamos el doc 4
function doc4_eliminar() { 

  $("#doc4").val(""); 
  $("#doc4_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');	
  $("#doc4_nombre").html("");
}

// Eliminamos el doc 5
function doc5_eliminar() {

	$("#doc5").val("");
	$("#doc5_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
	$("#doc5_nombre").html("");
}
 
//Función limpiar
function limpiar_form_usuario() {
  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
  // Agregamos la validacion
  $("#trabajador").rules('add', { required: true, messages: {  required: "Campo requerido" } });  
  $("#password").rules('add', { required: true, messages: {  required: "Campo requerido" } });

  //Select2 trabajador
  lista_select2("../ajax/usuario.php?op=select2Trabajador", '#trabajador', null);

  $("#idusuario").val("");
  $("#trabajador_c").html("Trabajador");   
  $("#trabajador_old").val(""); 
  $("#cargo").val("").trigger("change"); 
  $("#login").val("");
  $("#password").val("");
  $("#password-old").val(""); 
  
  $(".modal-title").html("Agregar usuario");    

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

function permisos() {
  $("#permisos").html('<i class="fas fa-spinner fa-pulse fa-2x"></i>');
  //Permiso
  $.post(`../ajax/usuario.php?op=permisos&id=`, function (r) {

    r = JSON.parse(r); //console.log(r);

    if (r.status) { $("#permisos").html(r.data); } else { ver_errores(e); }
    //$("#permiso_4").rules('add', { required: true, messages: {  required: "Campo requerido" } });
    
  }).fail( function(e) { console.log(e); ver_errores(e); } );
}

function show_hide_form(flag) {
	if (flag == 1)	{		
		$("#mostrar-tabla").show();
    $("#mostrar-form").hide();
    $(".btn-regresar").hide();
    $(".btn-agregar").show();
	}	else	{
		$("#mostrar-tabla").hide();
    $("#mostrar-form").show();
    $(".btn-regresar").show();
    $(".btn-agregar").hide();
	}
}

//Función Listar
function tbla_principal() {

  tabla = $('#tabla-usuarios').dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,3,4,5], } }, { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,2,3,4,5], } }, {extend: "colvis"} ,
    ],
    ajax:{
      url: '../ajax/usuario.php?op=tbla_principal',
      type : "get",
      dataType : "json",						
      error: function(e){        
        console.log(e.responseText); ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) {
      // columna: 0
      if (data[0] != '') { $("td", row).eq(0).addClass("text-center"); }
      // columna: 1
      if (data[1] != '') { $("td", row).eq(1).addClass("text-center"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar
function guardar_y_editar_usuario(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-usuario")[0]);

  $.ajax({
    url: "../ajax/usuario.php?op=guardar_y_editar_usuario",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) { 
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {
          tabla.ajax.reload(null, false);
          show_hide_form(1); limpiar_form_usuario(); sw_success('Correcto!', "Usuario guardado correctamente." );
          $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
        } else {
          ver_errores(d);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }             
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener( "progress", function (evt) {

        if (evt.lengthComputable) {
          var prct = (evt.loaded / evt.total) * 100;
          prct = Math.round(prct);

          $("#barra_progress_usuario").css({ width: prct + "%", });

          $("#barra_progress_usuario").text(prct + "%");

        }
      }, false );

      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#div_barra_progress_usuario").show();
      $("#barra_progress_usuario").css({ width: "0%",  });
      $("#barra_progress_usuario").text("0%");
    },
    complete: function () {
      $("#div_barra_progress_usuario").hide();
      $("#barra_progress_usuario").css({ width: "0%", });
      $("#barra_progress_usuario").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function mostrar(idusuario) {
  $(".tooltip").removeClass("show").addClass("hidde");
  $(".trabajador-name").html(`<i class="fas fa-spinner fa-pulse fa-2x"></i>`);  

  limpiar_form_usuario();  

  $(".modal-title").html("Editar usuario");
  $("#trabajador").val("").trigger("change"); 
  $("#trabajador_c").html(`Trabajador <b class="text-danger">(Selecione nuevo) </b>`);
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  // Removemos la validacion
  $("#trabajador").rules('remove', 'required');
  $("#password").rules('remove', 'required');

  show_hide_form(2);

  $("#permisos").html('<i class="fas fa-spinner fa-pulse fa-2x"></i>');

  $.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario }, function (data, status) {

    data = JSON.parse(data);  //console.log(data); 

    $(".trabajador-name").html(` <i class="fas fa-users-cog text-primary"></i> <b class="texto-parpadeante font-size-20px">${data.data.nombres}</b> `);    
    
    $("#trabajador_old").val(data.data.idtrabajador); 
    $("#cargo").val(data.data.cargo).trigger("change"); 
    $("#login").val(data.data.login);
    $("#password-old").val(data.data.password);
    $("#idusuario").val(data.data.idusuario);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();    

  }).fail( function(e) { console.log(e); ver_errores(e); } );

  //Permiso
  $.post(`../ajax/usuario.php?op=permisos&id=${idusuario}`, function (r) {

    r = JSON.parse(r); console.log(r);

    if (r.status) { $("#permisos").html(r.data); } else { ver_errores(e); }
    //$("#permiso_4").rules('add', { required: true, messages: {  required: "Campo requerido" } });
    
  }).fail( function(e) { console.log(e); ver_errores(e); } );
}

//Función para desactivar registros
function eliminar(idusuario, nombre) {
  
  crud_eliminar_papelera(
    "../ajax/usuario.php?op=desactivar",
    "../ajax/usuario.php?op=eliminar", 
    idusuario, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false) },
    false, 
    false, 
    false,
    false
  );
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   T R A B A J A D O R  ::::::::::::::::::::::::::::::::::::::::::::::::::::
function limpiar_form_trabajador() {

  $("#guardar_registro_trabajador").html('Guardar Cambios').removeClass('disabled');

  $(".tooltip").removeClass("show").addClass("hidde");

  $("#idtrabajador_trab").val("");
  $("#tipo_documento_trab option[value='DNI']").attr("selected", true);
  $("#nombre_trab").val(""); 
  $("#num_documento_trab").val(""); 
  $("#direccion_trab").val(""); 
  $("#telefono_trab").val(""); 
  $("#email_trab").val(""); 
  $("#nacimiento_trab").val(""); $("#edad_trab").val("0");  $(".edad_trab").html("0");    
  $("#c_bancaria_trab").val("");  
  $("#cci_trab").val("");  
  $("#banco_trab").val("").trigger("change");
  $("#tipo_trab").val("").trigger("change");
  $("#ocupacion_trab").val("").trigger("change");
  $("#titular_cuenta_trab").val("");

  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1").val("");
	$("#foto1_actual").val("");  
  $("#foto1_nombre").html(""); 

  $("#foto2_i").attr("src", "../dist/img/default/dni_anverso.webp");
	$("#foto2").val("");
	$("#foto2_actual").val("");  
  $("#foto2_nombre").html("");  

  $("#foto3_i").attr("src", "../dist/img/default/dni_reverso.webp");
	$("#foto3").val("");
	$("#foto3_actual").val("");  
  $("#foto3_nombre").html(""); 

  $("#doc4").val("");
  $("#doc_old_4").val("");
  $('#doc4_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc4_nombre').html("");
  
  $("#doc5").val("");
  $("#doc_old_5").val("");
  $('#doc5_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc5_nombre').html("");
  
  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función para guardar o editar
function guardar_y_editar_trabajador(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-trabajador")[0]);
  $("#div_barra_progress_trabajador").show();

  $.ajax({
    url: "../ajax/usuario.php?op=guardar_y_editar_trabajador",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) { 
      try {
        e = JSON.parse(e); console.log(e);
        if (e.status == true) {

          lista_select2("../ajax/usuario.php?op=select2Trabajador", '#trabajador', e.id_tabla);
          
          sw_success('Correcto!', "Trabajador guardado correctamente." );      

          limpiar_form_trabajador();

          $("#modal-agregar-trabajador").modal("hide");
    
          $("#guardar_registro_trabajador").html('Guardar Cambios').removeClass('disabled');
        } else {
          ver_errores(d);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }             
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener( "progress", function (evt) {

        if (evt.lengthComputable) {
          var prct = (evt.loaded / evt.total) * 100;
          prct = Math.round(prct);

          $("#barra_progress_trabajador").css({ width: prct + "%", });

          $("#barra_progress_trabajador").text(prct + "%");

        }
      }, false );

      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_trabajador").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#div_barra_progress_trabajador").show();
      $("#barra_progress_trabajador").css({ width: "0%",  });
      $("#barra_progress_trabajador").text("0%");
    },
    complete: function () {
      $("#div_barra_progress_trabajador").hide();
      $("#barra_progress_trabajador").css({ width: "0%", });
      $("#barra_progress_trabajador").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });

}

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco_trab").select2("val") == null || $("#banco_trab").select2("val") == "" || $("#banco_trab").select2("val") == '1') {

    $("#c_bancaria_trab").prop("readonly",true);   $("#cci_trab").prop("readonly",true);
  } else {
    
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>'); $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');

    $("#c_bancaria_trab").prop("readonly",false);   $("#cci_trab").prop("readonly",false);

    $.post("../ajax/ajax_general.php?op=formato_banco", { idbanco: $("#banco_trab").select2("val") }, function (e, status) {

      e = JSON.parse(e);  console.log(e); 

      if (e.status) {
        $(".chargue-format-1").html('Cuenta Bancaria'); $(".chargue-format-2").html('CCI');

        var format_cta = decifrar_format_banco(e.data.formato_cta); var format_cci = decifrar_format_banco(e.data.formato_cci);

        $("#c_bancaria_trab").inputmask(`${format_cta}`);

        $("#cci_trab").inputmask(`${format_cci}`);
      } else {
        ver_errores(e);
      }      

    }).fail( function(e) { ver_errores(e); } );   
  }  
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  $("#cargo").on('change', function() { $(this).trigger('blur'); });
  $("#trabajador").on('change', function() { $(this).trigger('blur'); });

  $("#banco_trab").on('change', function() { $(this).trigger('blur'); });
  $("#tipo_trab").on('change', function() { $(this).trigger('blur'); });
  $("#ocupacion_trab").on('change', function() { $(this).trigger('blur'); });

  $("#form-usuario").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      login:    { required: true, minlength: 3, maxlength: 20 },
      password: { required: true, minlength: 4, maxlength: 20 },
      cargo:    { required: true },
    },
    messages: {
      login:    { required: "Este campo es requerido.", minlength: "MÍNIMO 4 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      password: { equired: "Campo requerido.", minlength: "MÍNIMO 4 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      cargo:    { required: "Campo requerido." },
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
      guardar_y_editar_usuario(e);
    },
  });

  $("#form-trabajador").validate({
    //ignore: '.select2-input, .select2-focusser',
    rules: {
      tipo_documento_trab: { required: true },
      num_documento_trab:  { required: true, minlength: 6, maxlength: 20 },
      nombre_trab:         { required: true, minlength: 6, maxlength: 100 },
      email_trab:          { email: true, minlength: 10, maxlength: 50 },
      direccion_trab:      { minlength: 5, maxlength: 70 },
      telefono_trab:       { minlength: 8 },
      tipo_trabajador_trab:{ required: true},
      cargo_trab:          { required: true},
      c_bancaria_trab:     { minlength: 10,},
      banco_trab:          { required: true},
      tipo_trab:           { required: true},
      ocupacion_trab:      { required: true},
      ruc_trab:            { minlength: 11, maxlength: 11},      
    },
    messages: {
      tipo_documento_trab: { required: "Campo requerido.", },
      num_documento_trab:  { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 20 caracteres.", },
      nombre_trab:         { required: "Campo requerido.", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 100 caracteres.", },
      email_trab:          { required: "Campo requerido.", email: "Ingrese un coreo electronico válido.", minlength: "MÍNIMO 10 caracteres.", maxlength: "MÁXIMO 50 caracteres.", },
      direccion_trab:      { minlength: "MÍNIMO 5 caracteres.", maxlength: "MÁXIMO 70 caracteres.", },
      telefono_trab:       { minlength: "MÍNIMO 8 caracteres.", },
      tipo_trabajador_trab:{ required: "Campo requerido.", },
      cargo_trab:          { required: "Campo requerido.", },
      c_bancaria_trab:     { minlength: "MÍNIMO 10 caracteres.", },
      tipo_trab:           { required: "Campo requerido.", },
      ocupacion_trab:      { required: "Campo requerido.", },
      banco_trab:          { required: "Campo requerido.", },
      ruc_trab:            { minlength: "MÍNIMO 11 caracteres.", maxlength: "MÁXIMO 11 caracteres.", },
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
      guardar_y_editar_trabajador(e);
    },
  });

  $("#cargo").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#trabajador").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#banco_trab").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#tipo_trab").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $("#ocupacion_trab").rules('add', { required: true, messages: {  required: "Campo requerido" } });
  
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function marcar_todos_permiso() {
   
  if ($(`#marcar_todo`).is(':checked')) {
    $('.permiso').each(function(){ this.checked = true; });
    $('.marcar_todo').html('Desmarcar Todo');
  } else {
    $('.permiso').each(function(){ this.checked = false; });
    $('.marcar_todo').html('Marcar Todo');
  }  
}