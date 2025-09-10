
var host = window.location.host == 'localhost'? `http://localhost/admin_sevens/dist/docs/valorizacion/documento/` : `${window.location.origin}/dist/docs/valorizacion/documento/` ;


let buscarv = [];
var array_fechas_q_s = [];



var cant_valorizaciones = 0;

var fecha_i_r = "", fecha_f_r = "", i_r = "";

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lConcretoValorizacion").addClass("active bg-primary");


  $("#idproyectocontrol_concreto").val(localStorage.getItem('nube_idproyecto')); 



  //$("#idproyecto").val(localStorage.getItem('nube_idproyecto')); 

  $("#idproyecto_q_s").val(localStorage.getItem('nube_idproyecto'));
  
  ver_quincenas(localStorage.getItem('nube_idproyecto'));  


  $("#guardar_registro").on("click", function (e) {  $("#submit-dosificacion-concreto").submit(); });
  $("#guardar_registro_nivel1").on("click", function (e) {  $("#submit-asignar_nivel1").submit(); });
  $("#guardar_registro_sub_nivel").on("click", function (e) {  $("#form-sub_nivel").submit(); });

  //Initialize Select2 Elements
  $("#numero_q_s_resumen").select2({ theme: "bootstrap4", placeholder: "Selecione Valorizacion", allowClear: true, });

  array_data_buscarv();
  // Formato para telefono
  $("[data-mask]").inputmask();  


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

  $.post("../ajax/valorizacion_concreto.php?op=listarquincenas", { 'nube_idproyecto': nube_idproyecto }, function (e, status) {

    e =JSON.parse(e); console.log(e);    

    $('#lista_quincenas').html('');

    // VALIDAMOS LAS FECHAS DE QUINCENA
    if (e.data) { 
        
      $(".h1-titulo").html("Concreto - semanal");
      var fechas_btn = fechas_valorizacion_semana(e.data.fecha_inicio, e.data.fecha_fin);
      
      fechas_btn.forEach((key, indice) => {
        $('#lista_quincenas').append(` <button id="boton-${key.num_q_s}" type="button" class="mb-2 btn bg-gradient-info text-center btn-sm" onclick="listar_concreto_control('${format_a_m_d(key.fecha_inicio)}', '${format_a_m_d(key.fecha_fin)}', '${key.num_q_s}');"><i class="far fa-calendar-alt"></i> Semana ${key.num_q_s}<br>${key.fecha_inicio} // ${key.fecha_fin}</button>`)
        array_fechas_q_s.push({ 'fecha_inicio':format_a_m_d(key.fecha_inicio), 'fecha_fin':format_a_m_d(key.fecha_fin), 'num_q_s': key.num_q_s, });
        cant_valorizaciones = key.num_q_s;
      });        
      
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


$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

// Eliminamos el doc 6
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

// Función para mostrar el formulario de valorización
function dosificacion_concreto() {
  $("#modal-dosificacion-concreto").modal("show");
  listar_dosificacion_concreto();

  // Pintamos el titulo
  $(".h1-titulo").html("Concreto - Dosificación");
}


let cont = 1;
let dosificaciones = [];

function add_fila_dosificacion_concreto() {
  const id = `fila_${cont}`;

  $(".tbody_tabla_dosificacion_concreto").append(`
    <tr class="delete_${id}">
      <td class="text-center ${id}">
        <button type="button" class="btn btn-danger btn-xs btn-eliminar eliminar_${id}" onclick="eliminar_fila_doc_concreto('${id}');" title="Eliminar">
          <i class="fas fa-skull-crossbones"></i>
        </button>
      </td>
      <td class="text-center ${id}"> <input type="text" class="form-control form-control-sm input-sm fila-input_new w-100 kg_cm2_${id}"  value=""> </td>
      <td class="text-center ${id}"> <input type="text" class="form-control form-control-sm input-sm fila-input_new psi_${id}"  value=""> </td>
      <td class="text-center ${id}"> <input type="text" class="form-control form-control-sm input-sm fila-input_new mpa_${id}"  value=""> </td>
      <td class="text-center ${id}"> <input type="text" class="form-control form-control-sm input-sm fila-input_new cemento_bls_${id}"  value=""> </td>
      <td class="text-center ${id}"> <input type="text" class="form-control form-control-sm input-sm fila-input_new arena_m3_${id}"  value=""> </td>
      <td class="text-center ${id}"> <input type="text" class="form-control form-control-sm input-sm fila-input_new grava_m3_${id}"  value=""> </td>
      <td class="text-center ${id}"> <input type="text" class="form-control form-control-sm input-sm fila-input_new w-100  hormigon_m3_${id}" value=""> </td>
      <td class="text-center ${id}" style="display:flex; justify-content:center; gap:5px;"> 
      <input type="text" class="form-control form-control-sm input-sm w-100  fila-input_new cant_cmt_${id}"  value=""> 
      <input type="text" class="form-control form-control-sm input-sm w-100  fila-input_new cant_ar_${id}"  value=""> 
      <input type="text" class="form-control form-control-sm input-sm w-100  fila-input_new cant_gr_${id}"  value=""> 
      </td>
    </tr>
  `);

  dosificaciones.push({ id: `${id}`  });

  cont++;
}


// Eliminar fila
function eliminar_fila_doc_concreto(id) {
  
  $(`.delete_${id}`).remove();

  const idx = dosificaciones.findIndex(obj => obj.id === id);

  if (idx > -1) { dosificaciones.splice(idx, 1);  }

}

function listar_dosificacion_concreto(){

  $(".tbody_tabla_dosificacion_concreto").empty();
  dosificaciones = [];
  $.getJSON("../ajax/concreto_control.php?op=listar_dosificacion_concreto", {}, function (e, status) {
    if (e.status == true) {

      e.data.forEach(item => {

         const iditem = `fila_${item.iddosificacion_concreto}`;

        $(".tbody_tabla_dosificacion_concreto").append(`
          <tr class="delete_${iditem}">
            <td class="text-center ${iditem}">
              <button type="button" class="btn btn-danger btn-xs btn-eliminar eliminar_${iditem}" onclick="eliminar_fila_doc_concreto('${iditem}');" title="Eliminar">
                <i class="fas fa-skull-crossbones"></i>
              </button>
            </td>
            <td class="text-center ${iditem}"> <input type="text" class="form-control form-control-sm input-sm fila-input kg_cm2_${iditem}"  value="${parseFloat(item.r_kg_cm2)}" onfocus="this.select()"> </td>
            <td class="text-center ${iditem}"> <input type="text" class="form-control form-control-sm input-sm fila-input psi_${iditem}"  value="${formato_miles(item.r_psi)}" onfocus="this.select()"> </td>
            <td class="text-center ${iditem}"> <input type="text" class="form-control form-control-sm input-sm fila-input mpa_${iditem}"  value="${parseFloat(item.r_mpa)}" onfocus="this.select()"> </td>
            <td class="text-center ${iditem}"> <input type="text" class="form-control form-control-sm input-sm fila-input cemento_bls_${iditem}"  value="${parseFloat(item.cemento)}" onfocus="this.select()"> </td>
            <td class="text-center ${iditem}"> <input type="text" class="form-control form-control-sm input-sm fila-input arena_m3_${iditem}"  value="${parseFloat(item.arena)}" onfocus="this.select()"> </td>
            <td class="text-center ${iditem}"> <input type="text" class="form-control form-control-sm input-sm fila-input grava_m3_${iditem}"  value="${parseFloat(item.grava)}" onfocus="this.select()"> </td>
            <td class="text-center ${iditem}"> <input type="text" class="form-control form-control-sm input-sm fila-input hormigon_m3_${iditem}"  value="${parseFloat(item.hormigon)}" onfocus="this.select()"> </td>
            <td class="text-center ${iditem}" style="display:flex; justify-content:center; gap:5px;">
            <input type="text" class="form-control form-control-sm input-sm fila-input cant_cmt_${iditem}"  value="${parseFloat(item.cant_cmt)}" onfocus="this.select()"> -
            <input type="text" class="form-control form-control-sm input-sm fila-input cant_ar_${iditem}"  value="${parseFloat(item.cant_ar)}" onfocus="this.select()"> -
            <input type="text" class="form-control form-control-sm input-sm fila-input cant_gr_${iditem}"  value="${parseFloat(item.cant_gr)}" onfocus="this.select()"> 
            </td>
          </tr>
        `);

        dosificaciones.push({ id: `${iditem}`  });

      });
           
    }else{
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

//Función para guardar o editar Dosificacion-Concreto
function guardar_y_editar(e) {

  // 1) Clonar y normalizar el array 'dosificaciones'
  const payloadDosif = dosificaciones.map(d => ({
    kg_cm2:        $(`.kg_cm2_${d.id}`).val(),
    psi:           quitar_formato_miles($(`.psi_${d.id}`).val()),
    mpa:           $(`.mpa_${d.id}`).val(),
    cemento_bls:   $(`.cemento_bls_${d.id}`).val(),
    arena_m3:      $(`.arena_m3_${d.id}`).val(),
    grava_m3:      $(`.grava_m3_${d.id}`).val(),
    hormigon_m3:   $(`.hormigon_m3_${d.id}`).val(),
    cant_cmt:      $(`.cant_cmt_${d.id}`).val(),
    cant_ar:       $(`.cant_ar_${d.id}`).val(),
    cant_gr:       $(`.cant_gr_${d.id}`).val(),
  })); 

  // (opcional) Validación rápida: no mandar si está vacío
  if (!payloadDosif.length) {
    Swal.fire("Aviso", "Agrega al menos una fila de dosificación.", "info");
    return;
  }

  // 2) FormData del formulario + adjuntar JSON del array
  var formData = new FormData($("#form-dosificacion-concreto")[0]);
  formData.append('dosificaciones', JSON.stringify(payloadDosif));

  // 3) AJAX
  $.ajax({
    url: "../ajax/concreto_control.php?op=guardar_y_editar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (resp) {
      try {
        const e = typeof resp === 'object' ? resp : JSON.parse(resp);
        if (e.status === true) {
          Swal.fire("Correcto!", "Documento guardado correctamente", "success");

          listar_dosificacion_concreto();

        } else {
          ver_errores(e);
        }
      } catch (err) {
        console.log('Error: ', err.message);
        toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentarlo más tarde, o comuníquese con <i><a href="tel:+51921305769">921-305-769</a></i> ─ <i><a href="tel:+51921487276">921-487-276</a></i>');
      }
      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          $("#barra_progress").css({ "width": percentComplete + '%' });
          $("#barra_progress").text(percentComplete.toFixed(2) + " %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%" });
      $("#barra_progress").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%" });
      $("#barra_progress").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}


// ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
// ═══════                                         S E C C I O N  -  A D D  N I V E L 1                                                             ═══════
// ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════


function limpiar_primer_nivel() {

  $('#idcontrol_concreto').val('');
  $('#r_cemento_usado').val('');
  $('#descripcion_concreto').val('');

  $('#cuadrilla').val('');
  $('#hora_inicio').val('');
  $('#hora_termino').val('');

}



function show_add_nivel1() { $(".datos-de-saldo").show("slow"); limpiar_primer_nivel(); }
function hide_add_nivel1() { $(".datos-de-saldo").hide("slow"); }

//Función para guardar o editar
function guardar_y_editar_nivel1(e) {


  // 2) FormData del formulario + adjuntar JSON del array
  var formData = new FormData($("#form-asignar_nivel1")[0]);

  // 3) AJAX
  $.ajax({
    url: "../ajax/concreto_control.php?op=guardar_y_editar_nivel1",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (resp) {
      try {
        const e = typeof resp === 'object' ? resp : JSON.parse(resp);
        if (e.status === true) {
          Swal.fire("Correcto!", "Documento guardado correctamente", "success");

          hide_add_nivel1();
          
          listar_concreto_control(fecha_i_r, fecha_f_r, i_r);
          limpiar_primer_nivel();
        } else {
          ver_errores(e);
        }
      } catch (err) {
        console.log('Error: ', err.message);
        toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentarlo más tarde, o comuníquese con <i><a href="tel:+51921305769">921-305-769</a></i> ─ <i><a href="tel:+51921487276">921-487-276</a></i>');
      }
      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          $("#barra_progress").css({ "width": percentComplete + '%' });
          $("#barra_progress").text(percentComplete.toFixed(2) + " %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%" });
      $("#barra_progress").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%" });
      $("#barra_progress").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

// funcion para listar los documentos de concreto control
function listar_concreto_control(fecha_i, fecha_f, i) {
pintar_btn_selecionado(i);
$(".div-docs-por-valorizacion").show(); 
$(".nombre_general").text(` - SEMANA ${i}`);
  fecha_i_r = fecha_i, fecha_f_r = fecha_f; i_r = i;
  console.log(i_r);
  
  $("#tabla_concreto_control_semana").html(`<tr> <td colspan="30" class="text-center" style="border:1px solid #ccc; background:#f9f9f9;"> <i class="fas fa-spinner fa-pulse fa-2x"></i> <h6>Cargando...</h6> </td> </tr>`);

  $.getJSON("../ajax/concreto_control.php?op=listar_concreto", {fecha_i_r:fecha_i_r,fecha_f_r:fecha_f_r}, function (e, status) {
    //e = JSON.parse(e); console.log(e);
    if (e.status == true) {
      html ="";
     
      e.data.forEach(item => {
       var  fecha_segun_nivel = '';
       var  add_segun_nivel = '';
       var  concreto_segun_nivel_col1 = '';
       var  concreto_segun_nivel_col2 = '';
       var  cemento_proyect_segun_nivel_col1 = '';
       var  cemento_proyect_segun_nivel_col2 = '';
             
       if (item.nivel =='1') {
          fecha_segun_nivel = item.drm_fecha 
          add_segun_nivel =`<button class="btn bg-gradient-info btn-xs" onclick="asignar_sub_nivel(${item.idcontrol_concreto},'${item.codigo}','${item.descripcion}','${item.drm_fecha}','${item.prefijo}','${item.idproyecto}');" data-toggle="tooltip" data-original-title="Asignar sub nivel"><i class="fa-solid fa-file-import"></i></button> `;
          concreto_segun_nivel_col1 ='';
          concreto_segun_nivel_col2 =item.e_concreto_proyectado;

          cemento_proyect_segun_nivel_col1 ='';
          cemento_proyect_segun_nivel_col2 =item.e_cemento_proyectado;

        } else {
          fecha_segun_nivel =''; add_segun_nivel =''; concreto_segun_nivel_col1 =item.e_concreto_proyectado;
          concreto_segun_nivel_col2 ='';

          cemento_proyect_segun_nivel_col1 =item.e_cemento_proyectado;
          cemento_proyect_segun_nivel_col2 ='';
        };
        html += ` <tr>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''} text-nowrap">
                    ${add_segun_nivel}
                    <button class="btn btn-warning btn-xs" onclick="mostrar_grupo_nivel(${item.idcontrol_concreto},'${item.codigo}','${item.nivel}')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button> 
                    <button class="btn btn-danger btn-xs  " onclick="eliminar_grupo_nivel(${item.idcontrol_concreto},'${item.codigo}','${item.nivel}')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>
                  </td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''} text-nowrap"> ${ fecha_segun_nivel}</td>
                  <td class=" s_general ${item.nivel == '1' ? 'mi_style_n1' : ''} text-nowrap">${item.descripcion}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.drm_cantidad}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.drm_largo}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.drm_ancho}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.drm_alto}</td>                  
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.drm_calidad}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.drm_dosificacion}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.drm_bolsas_m3}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.drm_piedra_m3}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.drm_arena}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.drm_hormigon}</td>

                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ concreto_segun_nivel_col1}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ concreto_segun_nivel_col2}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ cemento_proyect_segun_nivel_col1}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ cemento_proyect_segun_nivel_col2}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.r_concreto_usado}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.r_cemento_usado}</td>

                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${item.r_piedra_chancada}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.r_arena}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.r_hormigon} </td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.r_piedra_grande}</td>

                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''} text-nowrap" >${ item.r_cuadrilla}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.r_hora_inicio}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.r_hora_termino}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${item.r_duracion_vaciado}</td>

                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.a_desperdicio_concreto}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.a_desperdicio_cemento}</td>
                  <td class="text-center s_general ${item.nivel == '1' ? 'mi_style_n1' : ''}">${ item.a_porcentaje_desperdicio}</td>
                </tr>`;



      });
      $("#tabla_concreto_control_semana").html(html);
      $('[data-toggle="tooltip"]').tooltip();
    } else {
      ver_errores(e);
    }
  }).fail( function(e) { ver_errores(e); } );
}

function mostrar_grupo_nivel(idcontrol_concreto,codigo,nivel) {

  $.post("../ajax/concreto_control.php?op=mostrar_concreto", { idcontrol_concreto: idcontrol_concreto, nivel:nivel }, function (e, status) {
    e = JSON.parse(e);  console.log(e);
    if (e.status == true) {
      // Pintamos los datos
      if (nivel=='1') {
       
        $('#idcontrol_concreto').val(e.data.idcontrol_concreto);
        $('#idproyectocontrol_concreto').val(e.data.idproyecto);
        $('#fecha_concreto').val(e.data.drm_fecha);
        $('#r_cemento_usado').val(e.data.r_cemento_usado);
        $('#descripcion_concreto').val(e.data.descripcion);

        
        $('#cuadrilla').val(e.data.r_cuadrilla);
        $('#hora_inicio').val(e.data.r_hora_inicio);
        $('#hora_termino').val(e.data.r_hora_termino);
        $('#duracion_vaciado').val(e.data.r_duracion_vaciado);


         show_add_nivel1();
         $('#modal-agregar-sub_nivel').modal('hide');
      } else {
        hide_add_nivel1();
        limpiar_form_sub_nivel();

        $('#idcontrol_concreto_sn').val(e.data.idcontrol_concreto);
        $('#idcontrol_concreto_padre_sn').val(e.data.idcontrol_concreto_relacionado_padre);

        $('#idproyecto_sn').val(e.data.idproyecto);
        $('#prefijo_sn').val(e.data.prefijo);
        $('#codigo_padre_sn').val(e.data.codigo_padre);
        $('#codigo_hijo_sn').val(codigo);
        
        $('#fecha_sn').val(e.data.drm_fecha);        
        $('#descripcion_sn').val(e.data.descripcion);        
        $('#cantidad_sn').val(e.data.drm_cantidad);
        $('#largo_sn').val(e.data.drm_largo);
        $('#ancho_sn').val(e.data.drm_ancho);
        $('#alto_sn').val(e.data.drm_alto);
        $('#calidad_fc_kg_cm2_sn').val(parseFloat(e.data.drm_calidad));
        
        $('#bolsas_m3_sn').val(e.data.drm_bolsas_m3);
        $('#piedra_m3_sn').val(e.data.drm_piedra_m3);
        $('#arena_m3_sn').val(e.data.drm_arena);
        $('#hormigon_m3_sn').val(e.data.drm_hormigon);
        $('#dosificacion_sn').val(e.data.drm_dosificacion);
        $('#concreto_proyectado_m3_sn').val(e.data.e_concreto_proyectado);
        $('#cemento_proyectado_m3_sn').val(e.data.e_cemento_proyectado);

        let valorInput = parseFloat(e.data.drm_calidad);
  
        if (!isNaN(valorInput)) {

          let encontrado = buscarv.find(item => parseFloat(item.r_kg_cm2) === parseFloat(valorInput));

          if (encontrado) {

            $('#bolsas_m3_sn').val(encontrado.cemento); $('#piedra_m3_sn').val(encontrado.arena);  $('#arena_m3_sn').val(encontrado.grava); 
            $('#hormigon_m3_sn').val(encontrado.hormigon); $('#dosificacion_sn').val(`${parseFloat(encontrado.cant_cmt)} - ${ parseFloat(encontrado.cant_ar)} - ${parseFloat(encontrado.cant_gr)}`);

            calcularTotalcemento();

          } else {
              console.log('No encontrado');
          }
        }

        calcularTotalconcreto();
        /*calcularTotalcemento();*/

        $('#modal-agregar-sub_nivel').modal('show');
      }
      }
    
    });


}

// ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
// ═══════                                         S E C C I O N  -  S U B   N I V E L                                                              ═══════
// ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════

function limpiar_form_sub_nivel() { 
  
  $('#cantidad_sn').val('');
  $('#largo_sn').val('');
  $('#ancho_sn').val('');
  $('#alto_sn').val('');
  $('#calidad_fc_kg_cm2_sn').val('');

}

function asignar_sub_nivel(idcontrol_concreto,codigo,descripcion,drm_fecha,prefijo,idproyecto){ 

  $('#modal-agregar-sub_nivel').modal('show');
  $('#fecha_sn').val(drm_fecha);  $('#codigo_padre_sn').val(codigo);  $('#idproyecto_sn').val(idproyecto);   $('#prefijo_sn').val(prefijo);  
  $('#idcontrol_concreto_padre_sn').val(idcontrol_concreto);    $('#descripcion_sn').val(`${prefijo} - `);

}

function array_data_buscarv() { $.getJSON("../ajax/concreto_control.php?op=listar_dosificacion_concreto", {}, function (e, status) { if (e.status == true) {  buscarv = e.data; } else { ver_errores(e); } }).fail( function(e) { ver_errores(e); } ); }

// Evento keyup del input
$('#calidad_fc_kg_cm2_sn').on('keyup', function() {

  let valorInput = parseFloat($(this).val());
  
  if (!isNaN(valorInput)) {

    let encontrado = buscarv.find(item => parseFloat(item.r_kg_cm2) === parseFloat(valorInput));

    if (encontrado) {

      $('#bolsas_m3_sn').val(encontrado.cemento); $('#piedra_m3_sn').val(encontrado.arena);  $('#arena_m3_sn').val(encontrado.grava); 
      $('#hormigon_m3_sn').val(encontrado.hormigon); $('#dosificacion_sn').val(`${parseFloat(encontrado.cant_cmt)} - ${ parseFloat(encontrado.cant_ar)} - ${parseFloat(encontrado.cant_gr)}`);

      calcularTotalcemento();

    } else {
        console.log('No encontrado');
    }
  }
});

// Capturar cambios en cualquiera de los inputs
$("#cantidad_sn, #largo_sn, #ancho_sn, #alto_sn").on("input", calcularTotalconcreto);

function calcularTotalconcreto() {
  // Obtener valores y asegurarse que sean números
  let cantidad = parseFloat($("#cantidad_sn").val()) || 0;
  let largo = parseFloat($("#largo_sn").val()) || 0;
  let ancho = parseFloat($("#ancho_sn").val()) || 0;
  let alto = parseFloat($("#alto_sn").val()) || 0;

  // Multiplicación
  let total = cantidad * largo * ancho * alto;
console.log('concreto_proyectado_m3_sn');
console.log(total);

  // Redondear a 2 decimales
  total = Math.round(total * 100) / 100;

  // Poner el resultado en el input
  $("#concreto_proyectado_m3_sn").val(total);
  calcularTotalcemento();
}

function calcularTotalcemento() {
  // Obtener valores y asegurarse que sean números
  let concreto_proyectado_m3_sn = parseFloat($("#concreto_proyectado_m3_sn").val()) || 0;
  let bolsas_m3_sn = parseFloat($("#bolsas_m3_sn").val()) || 0;

  // Multiplicación
  let total = concreto_proyectado_m3_sn * bolsas_m3_sn;

  // Redondear a 2 decimales
  total = Math.round(total * 100) / 100;

  // Poner el resultado en el input
  $("#cemento_proyectado_m3_sn").val(total);
}


function guardar_y_editar_sub_nivel(e) {

  var formData = new FormData($("#form-sub_nivel")[0]);

  // 3) AJAX
  $.ajax({
    url: "../ajax/concreto_control.php?op=guardar_y_editar_subnivel",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (resp) {
      try {
        const e = typeof resp === 'object' ? resp : JSON.parse(resp);
        if (e.status === true) {
          Swal.fire("Correcto!", "Documento guardado correctamente", "success");

          $('#modal-agregar-sub_nivel').modal('hide');
          listar_concreto_control(fecha_i_r, fecha_f_r, i_r);
          limpiar_form_sub_nivel()

        } else {
          ver_errores(e);
        }
      } catch (err) {
        console.log('Error: ', err.message);
        toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentarlo más tarde, o comuníquese con <i><a href="tel:+51921305769">921-305-769</a></i> ─ <i><a href="tel:+51921487276">921-487-276</a></i>');
      }
      $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          $("#barra_progress").css({ "width": percentComplete + '%' });
          $("#barra_progress").text(percentComplete.toFixed(2) + " %");
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%" });
      $("#barra_progress").text("0%").addClass('progress-bar-striped progress-bar-animated');
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%" });
      $("#barra_progress").text("0%").removeClass('progress-bar-striped progress-bar-animated');
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}


function calcularDuracion() {
  var inicio = $('#hora_inicio').val();
  var termino = $('#hora_termino').val();

  if (!inicio || !termino) {
    $('#duracion_vaciado').val('');
    return;
  }

  var inicioParts = inicio.split(':').map(Number);
  var terminoParts = termino.split(':').map(Number);

  var fechaInicio = new Date(0,0,0,inicioParts[0],inicioParts[1]);
  var fechaTermino = new Date(0,0,0,terminoParts[0],terminoParts[1]);

  var diff = fechaTermino - fechaInicio;
  if (diff < 0) diff += 24*60*60*1000; // paso de medianoche

  var horas = Math.floor(diff / (1000*60*60));
  var minutos = Math.floor((diff % (1000*60*60)) / (1000*60));

  var duracionHHMM = horas.toString().padStart(2,'0') + ':' + minutos.toString().padStart(2,'0');
  var duracionDecimal = (horas + minutos/60).toFixed(2);

  $('#duracion_vaciado').val(duracionHHMM );
}

// Ejecutamos cuando cambien los inputs
$('#hora_inicio, #hora_termino').on('input', calcularDuracion);


function eliminar_grupo_nivel(idcontrol_concreto,codigo,nivel) {

  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Deleted!",
        text: "Your file has been deleted.",
        icon: "success"
      });
    }
  });

  
}
























//Función limpiar
function limpiar_form_fierro() {
  $("#idconcreto_por_valorizacion").val("");
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

  
init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {  

  $("#form-asignar_nivel1").validate({

    rules: {
      fecha_concreto: { required: true },
      r_cemento_usado: { required: true },
      descripcion_concreto: { required: true },
    },

    messages: {
      fecha_concreto: {  required: "Campo requerido.", }, 
      r_cemento_usado: {  required: "Campo requerido.", }, 
      descripcion_concreto: {  required: "Campo requerido.", }, 
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
      //$(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
       guardar_y_editar_nivel1(e);
    },
  });

  $("#form-dosificacion-concreto").validate({

    rules: {
      /*nombre_doc: { required: true },*/
    },

    messages: {
      /*nombre_doc: {  required: "Campo requerido.", },       */
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
      guardar_y_editar(e);
    },
  });

  $("#form-sub_nivel").validate({

    rules: {
      descripcion_sn: { required: true },
      cantidad_sn: { required: true },
      largo_sn: { required: true },
      ancho_sn: { required: true },
      alto_sn: { required: true },
      altura_vaciado_sn: { required: true },
      calidad_fc_kg_cm2_sn: { required: true },
    },

    messages: {
      descripcion_sn: {  required: "Campo requerido.", },   
      cantidad_sn: {  required: "Campo requerido.", },   
      largo_sn: {  required: "Campo requerido.", },   
      ancho_sn: {  required: "Campo requerido.", },   
      alto_sn: {  required: "Campo requerido.", },   
      altura_vaciado_sn:    {  required: "Campo requerido.", },   
      calidad_fc_kg_cm2_sn: {  required: "Campo requerido.", },   
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
      guardar_y_editar_sub_nivel(e);
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

