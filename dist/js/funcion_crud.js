var errores_list = [];

/*  ══════════════════════════════════════════ - C R U D - ══════════════════════════════════════════ */

function crud_listar(url, nombre_modulo) {
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

function lista_select2(url, nombre_modulo, id) {
  $.get(url, function (data, status) {
    data = JSON.parse(data);

    $("#select_modal_" + nombre_modulo).html("");

    $.each(data, function (i, item) {
      // console.log(item);
      var option = '<option  value="' + item.id + '">' + item.nombre + "</option>";
      $("#select_modal_" + nombre_modulo).append(option);
    });

    if (id) {
      $("#select_modal_" + nombre_modulo)
        .val(id)
        .trigger("change");
    } else {
      $("#select_modal_" + nombre_modulo)
        .val(null)
        .trigger("change");
    }
  });
}

function crud_listar_select(url, select, select2 = false) {
  $.get(url, function (data, status) {
    data = JSON.parse(data);

    $("#" + select).html("");

    $.each(data, function (key, value) {
      $("#" + select).append('<option value="' + value.id + '">' + value.nombre + "</option>");
    });

    if (select2) {
      $("#" + select)
        .val(null)
        .trigger("change");
    }
  });
}

function crud_guardar_editar(event, url, nombre_modulo, callback_limpiar, callback_true, callback_false) {
  event.preventDefault();

  $("#div_barra_progress_" + nombre_modulo).show();

  var formData = new FormData($("#formulario_" + nombre_modulo)[0]);

  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      datos = JSON.parse(datos);

      if (datos.status) {
        sw_success(datos.message);
        limpiar_form(nombre_modulo, callback_limpiar);
        if (callback_true) {
          callback_true();
        }
      } else {
        sw_error(datos.message);
        if (callback_false) {
          callback_false();
        }
      }
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener(
        "progress",
        function (evt) {
          if (evt.lengthComputable) {
            var prct = (evt.loaded / evt.total) * 100;
            prct = Math.round(prct);

            $("#barra_progress_" + nombre_modulo).css({
              width: prct + "%",
            });

            $("#barra_progress_" + nombre_modulo).text(prct + "%");

            // if (prct === 100) {
            //     setTimeout(function(){ reniciar_barra(nombre_modulo) }, 600);
            // }
          }
        },
        false
      );
      return xhr;
    },
    beforeSend: function () {
      $("#div_barra_progress_" + nombre_modulo).show();
      $("#barra_progress_" + nombre_modulo).css({
        width: "0%",
      });
      $("#barra_progress_" + nombre_modulo).text("0%");
    },
    complete: function () {
      $("#div_barra_progress_" + nombre_modulo).hide();
      $("#barra_progress_" + nombre_modulo).css({
        width: "0%",
      });
      $("#barra_progress_" + nombre_modulo).text("0%");
    },
    error: function (jqXhr) {
      comprobar_errores(jqXhr, nombre_modulo);
    },
  });
}

function crud_guardar_editar_sm(event, url, nombre_modulo, callback_limpiar, callback_true, callback_false) {
  event.preventDefault();

  $("#div_barra_progress_" + nombre_modulo).show();

  var formData = new FormData($("#formulario_" + nombre_modulo)[0]);

  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      datos = JSON.parse(datos);

      if (datos.status) {
        sw_success(datos.message);
        limpiar_form_sm(nombre_modulo, callback_limpiar);
        if (callback_true) {
          callback_true();
        }
      } else {
        sw_error(datos.message);
        if (callback_false) {
          callback_false();
        }
      }
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener(
        "progress",
        function (evt) {
          if (evt.lengthComputable) {
            var prct = (evt.loaded / evt.total) * 100;
            prct = Math.round(prct);

            $("#barra_progress_" + nombre_modulo).css({
              width: prct + "%",
            });

            $("#barra_progress_" + nombre_modulo).text(prct + "%");

            // if (prct === 100) {
            //     setTimeout(function(){ reniciar_barra(nombre_modulo) }, 600);
            // }
          }
        },
        false
      );
      return xhr;
    },
    beforeSend: function () {
      $("#div_barra_progress_" + nombre_modulo).show();
      $("#barra_progress_" + nombre_modulo).css({
        width: "0%",
      });
      $("#barra_progress_" + nombre_modulo).text("0%");
    },
    complete: function () {
      $("#div_barra_progress_" + nombre_modulo).hide();
      $("#barra_progress_" + nombre_modulo).css({
        width: "0%",
      });
      $("#barra_progress_" + nombre_modulo).text("0%");
    },
    error: function (jqXhr) {
      comprobar_errores(jqXhr, nombre_modulo);
    },
  });
}

function crud_guardar_modal(event, url, nombre_modulo, callback_limpiar, callback_true, callback_false) {
  event.preventDefault();

  $("#div_barra_progress_" + nombre_modulo).show();

  var formData = new FormData($("#formulario_" + nombre_modulo)[0]);

  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      datos = JSON.parse(datos);
      // console.log(datos.inputt);
      if (datos.status) {
        sw_success(datos.message);

        limpiar_form(nombre_modulo, callback_limpiar);

        console.log("IDDDDDDDD" + datos.id);

        lista_select2("/admin/" + nombre_modulo + "/listar/select", nombre_modulo, datos.id);

        if (callback_true) {
          callback_true();

          // cerramos el modal agregar rubro
          $("#modal_agregar_rubro").modal("hide");
          // cerramos el modal agregar preguntas
          $("#modal_agregar_preguntas").modal("hide");
          // cerramos el modal agregar talla
          $("#modal_agregar_talla").modal("hide");
          // cerramos el modal agregar talla
          $("#modal_agregar_color").modal("hide");
        }
      } else {
        sw_error(datos.message);

        if (callback_false) {
          callback_false();
        }
      }
    },
    xhr: function () {
      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener(
        "progress",
        function (evt) {
          if (evt.lengthComputable) {
            var prct = (evt.loaded / evt.total) * 100;
            prct = Math.round(prct);

            $("#barra_progress_" + nombre_modulo).css({
              width: prct + "%",
            });

            $("#barra_progress_" + nombre_modulo).text(prct + "%");

            // if (prct === 100) {
            //     setTimeout(function(){ reniciar_barra(nombre_modulo) }, 600);
            // }
          }
        },
        false
      );

      return xhr;
    },

    beforeSend: function () {
      $("#div_barra_progress_" + nombre_modulo).show();
      $("#barra_progress_" + nombre_modulo).css({
        width: "0%",
      });
      $("#barra_progress_" + nombre_modulo).text("0%");
    },
    complete: function () {
      $("#div_barra_progress_" + nombre_modulo).hide();
      $("#barra_progress_" + nombre_modulo).css({
        width: "0%",
      });
      $("#barra_progress_" + nombre_modulo).text("0%");
    },
    error: function (jqXhr) {
      comprobar_errores(jqXhr, nombre_modulo);
    },
  });
}


function crud_desactivar(url, id_tabla, title, mensaje, callback_true, callback_false) {
  Swal.fire({
    title: "¿Deseas desactivar este registro?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#f34770",
    confirmButtonText: "Si, desactivar",
    cancelButtonText: "No, cancelar",
    closeOnConfirm: false,
    closeOnCancel: false,
  }).then((result) => {
    if (result.value) {
      $.get(url, function (data, status) {
        data = JSON.parse(data);
        if (data.status) {
          sw_success("Se desactivó");
          if (callback_true) {
            callback_true();
            // REMOVEMOS EL TOOTIP.......................................................
            $(".tooltip").removeClass("show").addClass("hidde");
          }
        } else {
          sw_error("Error en desactivar");
          if (callback_false) {
            callback_false();
            // REMOVEMOS EL TOOTIP.......................................................
            $(".tooltip").removeClass("show").addClass("hidde");
          }
        }
      });
    } else if (result.dismiss) {
      sw_cancelar();
      // REMOVEMOS EL TOOTIP.......................................................
      $(".tooltip").removeClass("show").addClass("hidde");
    }
    // REMOVEMOS EL TOOTIP.......................................................
    $(".tooltip").removeClass("show").addClass("hidde");
  });
}

function crud_activar(url, id_tabla, title, mensaje, callback_true, callback_false) {
  Swal.fire({
    title: "¿Deseas activar este registro?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#42d697",
    // cancelButtonColor: "#f34770",
    confirmButtonText: "Si, activar",
    cancelButtonText: "No, cancelar",
    closeOnConfirm: false,
    closeOnCancel: false,
  }).then((result) => {
    if (result.value) {
      $.get(url, function (data, status) {
        data = JSON.parse(data);
        if (data.status) {
          sw_success("Se desactivó");
          if (callback_true) {
            callback_true();
            // REMOVEMOS EL TOOTIP.......................................................
            $(".tooltip").removeClass("show").addClass("hidde");
          }
        } else {
          sw_error("Error en desactivar");
          if (callback_false) {
            callback_false();
            // REMOVEMOS EL TOOTIP.......................................................
            $(".tooltip").removeClass("show").addClass("hidde");
          }
        }
      });
    } else if (result.dismiss) {
      sw_cancelar();
      // REMOVEMOS EL TOOTIP.......................................................
      $(".tooltip").removeClass("show").addClass("hidde");
    }
    // REMOVEMOS EL TOOTIP.......................................................
    $(".tooltip").removeClass("show").addClass("hidde");
  });

  Swal.fire({
    title: title,
    text: mensaje,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {

    if (result.isConfirmed) {

      $.post( url, { 'id_tabla': id_tabla }, function (e) {

        if (data.status) {

          if (callback_true) {
            callback_true();
          }
          Swal.fire("Activado!", "Tu usuario ha sido activado.", "success");		 
  
          tabla.ajax.reload();

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
    title: "¿Deseas eliminar permanetemente este registro?",
    text: "¡No se podra recuperar!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#f34770",
    confirmButtonText: "Si, eliminar",
    cancelButtonText: "No, cancelar",
    closeOnConfirm: false,
    closeOnCancel: false,
  }).then((result) => {
    if (result.value) {
      $.get(url, function (data, status) {
        data = JSON.parse(data);
        if (data.status) {
          sw_success("Se eliminó");
          if (callback_true) {
            callback_true();
          }
        } else {
          sw_error(data.message);
          if (callback_false) {
            callback_false();
          }
        }
      });
    } else if (result.dismiss) {
      sw_cancelar();
    }
  });
}

function crud_eliminar_papelera(url, id) {
  Swal.fire({
    title: "¿Deseas eliminar permanetemente este registro?",
    text: "¡No se podra recuperar!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#f34770",
    confirmButtonText: "Si, eliminar",
    cancelButtonText: "No, cancelar",
    closeOnConfirm: false,
    closeOnCancel: false,
  }).then((result) => {
    if (result.value) {
      $.get(url, function (data, status) {
        data = JSON.parse(data);
        if (data.status) {
          $("#tr_" + id).remove();
          sw_success("Se eliminó");
        } else {
          sw_error("Error en eliminar");
        }
      });
    } else if (result.dismiss) {
      sw_cancelar();
    }
  });
}

/*  ══════════════════════════════════════════ - A L E R T A S - ══════════════════════════════════════════ */

function sw_cancelar(txt = "Se canceló", timer = 3000) {
  Swal.fire({
    title: txt,
    // text: txt,
    timer: timer,
    icon: "info",
  });
}

function sw_error(txt = "Error", timer = 3000) {
  Swal.fire({
    title: "Error",
    text: txt,
    timer: timer,
    icon: "error",
  });
}

function sw_success(txt = "Exito", timer = 1000) {
  Swal.fire({
    title: "Exito",
    text: txt,
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

  if (e.status == 400) {

    Swal.fire(`Error 404 😅!`, `<h5>Archivo no encontrado</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
    
  } else if(e.status == 500) {

    Swal.fire(`Error 500 😅!`, `<h5>Error Interno del Servidor</h5> Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");

    if (e.status == false) {

      Swal.fire(`Error en la Base de Datos 😅!`, `Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
       
    } else {
      Swal.fire(`Error Grave 😱!`, `Contacte al <b>Ing. de Sistemas</b> 📞 <br> <i>921-305-769</i> ─ <i>921-487-276</i>`, "error");
    }
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

