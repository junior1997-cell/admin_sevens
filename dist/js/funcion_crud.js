var errores_list = [];

/*  ══════════════════════════════════════════ - C R U D - ══════════════════════════════════════════ */

function crud_listar_tabla(url, nombre_modulo) {
  tabla = $("#tabla_" + nombre_modulo).DataTable({
    responsive: true,
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "rtip", //Definimos los elementos del control de tabla
    ajax: {
      url: url,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    language: {
      responsive: true,
      url: "/recursos/datable.rs/js/idioma.json",
    },
    // fixedHeader: true
  });
  /* Data table FullResponsive */
  new $.fn.dataTable.FixedHeader(tabla);

  return tabla;
}

function lista_select2(url, nombre_input, id_tabla) {

  $.get(url, function (e, status) {

    e = JSON.parse(e);   //console.log(e);

    if (e.status) {

      $(nombre_input).html(e.data); 

      if ( !id_tabla || id_tabla == "NaN" || id_tabla == "" || id_tabla == null || id_tabla == "Infinity" || id_tabla === undefined) {

        $(nombre_input).val(null).trigger("change");

      } else {

        $(nombre_input).val(id_tabla).trigger("change");  

      }

    } else {
      ver_errores(e);
    }   

  }).fail( function(e) { ver_errores(e); } );
}


function crud_guardar_editar_card_xhr( url, formData, callback_limpiar, callback_true, name_progress, table_reload_1, table_reload_2 = false, table_reload_3 = false, table_reload_4 = false, table_reload_5 = false, table_reload_6 = false, table_reload_7 = false, table_reload_8 = false, table_reload_9 = false) {
  //event.preventDefault();
  $("#div_barra_progress_" + name_progress).show();

  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {

      datos = JSON.parse(datos); console.log(datos);

      if (datos.status) { 

        if (callback_limpiar) { callback_true();  }
        if (callback_true)    { callback_true();  }

        if (table_reload_1) { table_reload_1(); }
        if (table_reload_2) { table_reload_2(); }
        if (table_reload_3) { table_reload_3(); }
        if (table_reload_4) { table_reload_4(); }
        if (table_reload_5) { table_reload_5(); }
        if (table_reload_6) { table_reload_6(); }
        if (table_reload_7) { table_reload_7(); }
        if (table_reload_8) { table_reload_8(); }
        if (table_reload_9) { table_reload_9(); }

      } else {
        ver_errores(datos); 
      }
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener( "progress", function (evt) {

        if (evt.lengthComputable) {
          var prct = (evt.loaded / evt.total) * 100;
          prct = Math.round(prct);

          $("#barra_progress_" + name_progress).css({ width: prct + "%", });

          $("#barra_progress_" + name_progress).text(prct + "%");

        }
      }, false );

      return xhr;
    },
    beforeSend: function () {
      $("#div_barra_progress_" + name_progress).show();
      $("#barra_progress_" + name_progress).css({ width: "0%",  });
      $("#barra_progress_" + name_progress).text("0%");
    },
    complete: function () {
      $("#div_barra_progress_" + name_progress).hide();
      $("#barra_progress_" + name_progress).css({ width: "0%", });
      $("#barra_progress_" + name_progress).text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function crud_guardar_editar_card( url, formData, callback_limpiar, callback_true, table_reload_1, table_reload_2 = false, table_reload_3 = false, table_reload_4 = false, table_reload_5 = false, table_reload_6 = false, table_reload_7 = false, table_reload_8 = false, table_reload_9 = false) {
  //event.preventDefault();

  $("#div_barra_progress_" + nombre_modulo).show();

  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {

      datos = JSON.parse(datos);

      if (datos.status) { 
              
        if (callback_limpiar) { callback_true();  }
        if (callback_true)    { callback_true();  }

        if (table_reload_1) { table_reload_1(); }
        if (table_reload_2) { table_reload_2(); }
        if (table_reload_3) { table_reload_3(); }
        if (table_reload_4) { table_reload_4(); }
        if (table_reload_5) { table_reload_5(); }
        if (table_reload_6) { table_reload_6(); }
        if (table_reload_7) { table_reload_7(); }
        if (table_reload_8) { table_reload_8(); }
        if (table_reload_9) { table_reload_9(); }

      } else {         
        ver_errores(datos);
      }
    },    
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function crud_guardar_editar_modal_xhr( url, formData, nombre_modal, callback_limpiar, callback_true, name_progress, table_reload_1, table_reload_2 = false, table_reload_3 = false, table_reload_4 = false, table_reload_5 = false, table_reload_6 = false, table_reload_7 = false, table_reload_8 = false, table_reload_9 = false) {
  //event.preventDefault();

  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {

      datos = JSON.parse(datos); // console.log(datos.inputt);

      if (datos.status) { 
              
        if (callback_limpiar) { callback_true();  }
        if (callback_true)    { callback_true();  }       

        if (table_reload_1) { table_reload_1(); }
        if (table_reload_2) { table_reload_2(); }
        if (table_reload_3) { table_reload_3(); }
        if (table_reload_4) { table_reload_4(); }
        if (table_reload_5) { table_reload_5(); }
        if (table_reload_6) { table_reload_6(); }
        if (table_reload_7) { table_reload_7(); }
        if (table_reload_8) { table_reload_8(); }
        if (table_reload_9) { table_reload_9(); }

        $(nombre_modal).modal('hide');

      } else {
        ver_errores(datos);
      }
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener( "progress", function (evt) {

        if (evt.lengthComputable) {
          var prct = (evt.loaded / evt.total) * 100;
          prct = Math.round(prct);

          $(`#barra_progress_${name_progress}`).css({ width: prct + "%", });

          $(`#barra_progress_${name_progress}`).text(prct + "%");

        }
      }, false );

      return xhr;
    },
    beforeSend: function () {
      $(`#div_barra_progress_${name_progress}`).show();
      $(`#barra_progress_${name_progress}`).css({ width: "0%", });
      $(`#barra_progress_${name_progress}`).text("0%");
    },
    complete: function () {
      $(`#div_barra_progress_${name_progress}`).hide();
      $(`#barra_progress_${name_progress}`).css({ width: "0%", });
      $(`#barra_progress_${name_progress}`).text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function crud_guardar_editar_modal( url, formData, nombre_modal, callback_limpiar, callback_true, table_reload_1, table_reload_2 = false, table_reload_3 = false, table_reload_4 = false, table_reload_5 = false, table_reload_6 = false, table_reload_7 = false, table_reload_8 = false, table_reload_9 = false) {
  //event.preventDefault();

  $("#div_barra_progress_" + nombre_modulo).show();

  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {

      datos = JSON.parse(datos);

      if (datos.status) { 
              
        if (callback_limpiar) { callback_true();  }
        if (callback_true)    { callback_true();  }
        if (select2_reload)   { select2_reload(); }

        if (table_reload_1) { table_reload_1(); }
        if (table_reload_2) { table_reload_2(); }
        if (table_reload_3) { table_reload_3(); }
        if (table_reload_4) { table_reload_4(); }
        if (table_reload_5) { table_reload_5(); }
        if (table_reload_6) { table_reload_6(); }
        if (table_reload_7) { table_reload_7(); }
        if (table_reload_8) { table_reload_8(); }
        if (table_reload_9) { table_reload_9(); }

        $(nombre_modal).modal('hide');
      } else {
        ver_errores(datos);
      }
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}

function crud_guardar_editar_modal_select2_xhr( url, formData, nombre_modal, callback_limpiar, callback_true, name_progress, url_select2, input_select2, table_reload_1 = false, table_reload_2 = false, table_reload_3 = false, table_reload_4 = false, table_reload_5 = false, table_reload_6 = false, table_reload_7 = false, table_reload_8 = false, table_reload_9 = false) {
  //event.preventDefault();

  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {

      datos = JSON.parse(datos); // console.log(datos.inputt);

      if (datos.status) { 
              
        if (callback_limpiar) { callback_true();  }
        if (callback_true)    { callback_true();  }   
        if (url_select2 && input_select2) { lista_select2(url_select2, input_select2, datos.data); }    

        if (table_reload_1) { table_reload_1(); }
        if (table_reload_2) { table_reload_2(); }
        if (table_reload_3) { table_reload_3(); }
        if (table_reload_4) { table_reload_4(); }
        if (table_reload_5) { table_reload_5(); }
        if (table_reload_6) { table_reload_6(); }
        if (table_reload_7) { table_reload_7(); }
        if (table_reload_8) { table_reload_8(); }
        if (table_reload_9) { table_reload_9(); }

        $(nombre_modal).modal('hide');

      } else {
        ver_errores(datos);
      }
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener( "progress", function (evt) {

        if (evt.lengthComputable) {
          var prct = (evt.loaded / evt.total) * 100;
          prct = Math.round(prct);

          $(`#barra_progress_${name_progress}`).css({ width: prct + "%", });

          $(`#barra_progress_${name_progress}`).text(prct + "%");

        }
      }, false );

      return xhr;
    },
    beforeSend: function () {
      $(`#div_barra_progress_${name_progress}`).show();
      $(`#barra_progress_${name_progress}`).css({ width: "0%", });
      $(`#barra_progress_${name_progress}`).text("0%");
    },
    complete: function () {
      $(`#div_barra_progress_${name_progress}`).hide();
      $(`#barra_progress_${name_progress}`).css({ width: "0%", });
      $(`#barra_progress_${name_progress}`).text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });
}


function crud_desactivar(url, id_tabla, title, mensaje, callback_true, table_reload_1, table_reload_2, table_reload_3, table_reload_4, table_reload_5) {

  Swal.fire({
    title: title,
    html: mensaje,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {

    if (result.isConfirmed) {

      $.post( url, { 'id_tabla': id_tabla }, function (e) {

        if (e.status) {

          if (callback_true) { callback_true(); } 
          
          if (table_reload_1) { table_reload_1(); }
          if (table_reload_2) { table_reload_2(); }
          if (table_reload_3) { table_reload_3(); }
          if (table_reload_4) { table_reload_4(); }
          if (table_reload_5) { table_reload_5(); }

          $(".tooltip").removeClass("show").addClass("hidde");
          
        }else{
  
          ver_errores(e);
        }
      }).fail( function(e) { console.log(e); ver_errores(e); } );      
    }
  });
}

function crud_activar(url, id_tabla, title, mensaje, callback_true, table_reload_1, table_reload_2, table_reload_3, table_reload_4,table_reload_5) {

  Swal.fire({
    title: title,
    html: mensaje,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {

    if (result.isConfirmed) {

      $.post( url, { 'id_tabla': id_tabla }, function (e) {

        if (e.status) {

          if (callback_true) { callback_true(); } 
          
          if (table_reload_1) { table_reload_1(); }
          if (table_reload_2) { table_reload_2(); }
          if (table_reload_3) { table_reload_3(); }
          if (table_reload_4) { table_reload_4(); }
          if (table_reload_5) { table_reload_5(); }

          $(".tooltip").removeClass("show").addClass("hidde");
          
        }else{
  
          ver_errores(e);
        }
      }).fail( function(e) { console.log(e); ver_errores(e); } );      
    }
  });
}

function crud_eliminar(url, callback_true, callback_false) {
  Swal.fire({
    title: title,
    text: mensaje,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Eliminar!",
  }).then((result) => {

    if (result.isConfirmed) {

      $.post( url, { 'id_tabla': id_tabla }, function (e) {

        if (e.status) {

          if (callback_true) { callback_true(); } 
          
          if (table_reload_1) { table_reload_1(); }
          if (table_reload_2) { table_reload_2(); }
          if (table_reload_3) { table_reload_3(); }
          if (table_reload_4) { table_reload_4(); }
          if (table_reload_5) { table_reload_5(); }

          $(".tooltip").removeClass("show").addClass("hidde");
          
        }else{
  
          ver_errores(e);
        }
      }).fail( function(e) { console.log(e); ver_errores(e); } );      
    }
  });

  
}

function crud_eliminar_papelera(url_papelera, url_eliminar, id_tabla, title, mensaje, callback_true_papelera, callback_true_eliminar, table_reload_1=false, table_reload_2=false, table_reload_3=false, table_reload_4=false,table_reload_5=false) {
  
  Swal.fire({
    title: title,
    html: mensaje,
    icon: "warning",
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonColor: "#17a2b8",
    denyButtonColor: "#d33",
    cancelButtonColor: "#6c757d",    
    confirmButtonText: `<i class="fas fa-times"></i> Papelera`,
    denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,
    showLoaderOnConfirm: true,
    preConfirm: (input) => {       
      return fetch(`${url_papelera}&id_tabla=${id_tabla}`).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); })
    },
    showLoaderOnDeny: true,
    preDeny: (input) => {       
      return fetch(`${url_eliminar}&id_tabla=${id_tabla}`).then(response => {
        //console.log(response);
        if (!response.ok) { throw new Error(response.statusText) }
        return response.json();
      }).catch(error => { Swal.showValidationMessage(`<b>Solicitud fallida:</b> ${error}`); })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    console.log(result);
    if (result.isConfirmed) {
      if (result.value.status) {
        if (callback_true_papelera) { callback_true_papelera(); }
        if (table_reload_1) { table_reload_1(); }
        if (table_reload_2) { table_reload_2(); }
        if (table_reload_3) { table_reload_3(); }
        if (table_reload_4) { table_reload_4(); }
        if (table_reload_5) { table_reload_5(); }
        $(".tooltip").removeClass("show").addClass("hidde");
      }else{
        ver_errores(result.value);
      }
    }else if (result.isDenied) {
      if (result.value.status) {
        if (callback_true_eliminar) { callback_true_eliminar(); }
        if (table_reload_1) { table_reload_1(); }
        if (table_reload_2) { table_reload_2(); }
        if (table_reload_3) { table_reload_3(); }
        if (table_reload_4) { table_reload_4(); }
        if (table_reload_5) { table_reload_5(); }
        $(".tooltip").removeClass("show").addClass("hidde");
      }else{
        ver_errores(result.value);
      }
    }
  });  
}

/*  ══════════════════════════════════════════ - A L E R T A S - ══════════════════════════════════════════ */

function sw_cancelar(title='Cancelado!', txt = "Acción cancelada.", timer = 3000) {
  Swal.fire({
    title: title,
    html: txt,
    timer: timer,
    icon: "info",
  });
}

function sw_error(title='Error!', txt = "Acción con error.", timer = 3000) {
  Swal.fire({
    title: title,
    html: txt,
    timer: timer,
    icon: "error",
  });
}

function sw_success(title='Exito!', txt = "Acción ejecutada con éxito", timer = 3000) {
  Swal.fire({
    title: title,
    html: txt,
    timer: timer,
    icon: "success",
  });
}

function confirmar_formulario(flat, callback) {
  if (flat) {
    Swal.fire({
      title: "Exito",
      timer: 2000,
      icon: "success",
    });

    if (callback) {
      callback();
    }
  } else {
    Swal.fire({
      title: "Error " + datos,
      timer: 2000,
      icon: "error",
    });
  }
}

/*  ══════════════════════════════════════════ - E R R O R E S - ══════════════════════════════════════════ */

function ver_errores(e) {
  
  if (e.status == 404) {
    console.group("Error"); console.warn('Error 404 -------------'); console.log(e); console.groupEnd();
    Swal.fire(`Error 404 😅!`, `<h5>Archivo no encontrado</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
    
  } else if(e.status == 500) {
    console.group("Error"); console.warn('Error 404 -------------'); console.log(e); console.groupEnd();
    Swal.fire(`Error 500 😅!`, `<h5>Error Interno del Servidor</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");

  }else if (e.status == false) {
    console.group("Error"); console.warn('Error BD -------------'); console.log(e); console.groupEnd();
    Swal.fire(`Error en la Base de Datos 😅!`, `Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
  
  }else if (e.status == 'duplicado') {
    console.group("Error"); console.warn('Duplicado Error BD -------------'); console.log(e); console.groupEnd();
    Swal.fire(`Estos datos ya existen 😅!`, e.data, "error");   
  
  } else {
    console.group("Error"); console.warn('Error Grave -------------'); console.log(e); console.groupEnd();
    Swal.fire(`Error Grave 😱!`, `Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
  }
}

function alert_danger(html) {
  return (
    '<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_error_cliente">' +
    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
    '<span class="font-weight-medium">¡ERROR!</span>' +
    "<ul> " +
    html +
    "</ul>" +
    "</div>"
  );
}

/*************************************************************/

function limpiar_form(nombre_modulo, callback) {
  $("#modal_" + nombre_modulo).modal("hide");

  if (callback) {
    callback();
  }

  /* Reiniciamos la barra */
  // reniciar_barra(nombre_modulo);
  /* Limpiamos posibles errores*/
  limpiar_errores(nombre_modulo);
}

function reniciar_barra(nombre_modulo) {
  $("#div_barra_progress_" + nombre_modulo).hide();
  $("#barra_progress_" + nombre_modulo).css({
    width: "0%",
  });
  $("#barra_progress_" + nombre_modulo).text("0%");
}

