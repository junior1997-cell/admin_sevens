var  calendar;  

//Función que se ejecuta al inicio
function init() {

  listar();   

  $("#bloc_Recurso").addClass("menu-open bg-color-191f24");

  $("#mRecurso").addClass("active");

  $("#lAllCalendario").addClass("active");

  $("#guardar_registro").on("click", function (e) {  $("#submit-form-calendario").submit(); });

  $("#eliminar_registro").on("click", function (e) { desactivar()  });

  //Initialize Select2 Elements
  $("#background_color").select2({templateResult: formatState, theme: "bootstrap4",  placeholder: "Selecione tipo", allowClear: true,});

  $("#background_color").val("#FF0000").trigger("change");
}

function formatState (state) {
  console.log(state);
  if (!state.id) { return state.text; }
  var color_bg = state.id != '' ? `${state.id}`: '';   
  var $state = $(`<span ><b style="background-color: ${color_bg}; color: ${color_bg};" class="mr-2">hol</b>${state.text}</span>`);
  return $state;
};

function contraste() {

  let color = $('#background_color').select2('val');

  let color_contrst = invertColor(color, true)

  $('#text_color').val(color_contrst);
}

function color_muestra() {

  let color = $('#background_color').select2('val');

  if ( color == '#FF0000' ) {
    $('.external-event').removeClass('bg-danger bg-warning bg-success').addClass('bg-danger');
  } else if ( color == '#FFF700' ) {
    $('.external-event').removeClass('bg-danger bg-warning bg-success').addClass('bg-warning');
  } else if ( color == '#28A745' ) {
    $('.external-event').removeClass('bg-danger bg-warning bg-success').addClass('bg-success');
  }
  
}

//Función limpiar
function limpiar() {

  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');

  $('#idcalendario').val("");
  $('#fecha_feriado').val("");
  $('#text_color').val('#ffffff');
  $('#fecha_select').html("Selecione una fecha");
  $('#titulo').val('Feriado');
  $("#background_color").val("#FF0000").trigger("change");
  $('#descripcion').val('');
  $('#eliminar_registro').hide();  

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar() {

  $("#external-events").html('<div class="text-center"> <i class="fas fa-spinner fa-pulse fa-2x"></i></div>');

  $("#custom-tabs-four-home").html('<div class="text-center"> <i class="fas fa-spinner fa-pulse fa-2x"></i></div>');

  $.post("../ajax/all_calendario.php?op=listar-calendario",  function (data, status) {

    data = JSON.parse(data);   console.log(data); 

    $("#external-events").html('');

    if (data.fechas.length != 0) {

      $.each(data.fechas, function (index, value) {
             
        $("#external-events").append('<div class="external-event" style="background: '+value.backgroundColor+' !important; color: '+value.textColor+' !important;">'+value.title+' - '+ format_d_m_a(value.start) +' </div>');
      });     

    } else {       
      $("#external-events").html('<div class="external-event bg-info">No hay fechas disponibles</div>');
    }

    // Colocamos el reporte
    $("#custom-tabs-four-home").html(
      '<div class="card-body table-responsive p-0">'+
        '<table class="table table-hover text-nowrap">'+
          '<thead>'+
            '<tr>'+
              '<th>Detalle</th>'+
              '<th>Cant. Dias</th>' +                                      
            '</tr>'+
          '</thead>'+
          '<tbody>'+ 
            '<tr>'+
              '<td>Feriados activos</td>'+
              '<td>'+ data.fechas.length +'</td>'+                                            
            '</tr>'+
            '<tr>'+
              '<td>Feriados eliminados</td>'+
              '<td id="f_delete">0</td>'+                                            
            '</tr>'+
            '<tr>'+
              '<td>Cant. feriados nacional</td>'+
              '<td>'+ data.count_n +'</td>'+                                            
            '</tr>'+
            '<tr>'+
              '<td>Cant. dia no laborable</td>'+
              '<td>'+ data.count_la +'</td>'+                                            
            '</tr>'+
            '<tr>'+
              '<td>Cant. feriado local</td>'+
              '<td>'+ data.count_lo +'</td>'+                                            
            '</tr>'+
            '<tr> <td> </td> <td> </td> </tr>' +                                        
          '</tbody>'+
        '</table>'+
      '</div>'
    );
      
    
    //initialize the calendar
    var date = new Date()

    var d    = date.getDate(), m = date.getMonth() + 1, y = date.getFullYear(); //console.log(`${d} ${m} ${y}`);

    var Calendar = FullCalendar.Calendar;

    var Draggable = FullCalendar.Draggable;        
    
    var calendarEl = document.getElementById('calendar');

    // initialize the external events     

    calendar = new Calendar(calendarEl, {

      timeZone: 'local',
        
      headerToolbar: {  left: 'prev,next today', center: 'title', right: 'listYear,dayGridMonth' },

      themeSystem: 'bootstrap',

      events: data.fechas,

      // Se ejecuta cuando no hay eventos
      dateClick: function(info) {
        
        $('#idcalendario').val("");

        $('#fecha_feriado').val(info.dateStr);

        $("#fecha_invertida").val(fecha_invertida(info.dateStr));
        $('#text_color').val('#ffffff');

        $('#fecha_select').html(`${extraer_dia_semana_completo(info.dateStr)}, ${format_d_m_a(info.dateStr)}`);

        localStorage.setItem('dateStr', info.dateStr); console.log(info.dateStr); 

        $('#titulo').val('Feriado');

        // $("#background_color").val("").trigger("change");

        $('#descripcion').val('');

        $('#eliminar_registro').hide();

        $('#modal-agregar-calendario').modal('show');
      },

      // Se ejecuta cuando hay un evento
      eventClick: function(info) {
         
        date = new Date(info.event.start);  year = date.getFullYear();   month = date.getMonth()+1;  dt = date.getDate();

        if (dt < 10) { dt = '0' + dt; }

        if (month < 10) { month = '0' + month; }
        
        $('#eliminar_registro').show();

        $('#idcalendario').val(info.event.id);

        $('#fecha_feriado').val(year+'-' + month + '-'+dt);

        $('#text_color').val(info.event.textColor);

        //$('#fecha_select').html(`${year}-${month}-${dt}`);
        var fecha_dia = `${year}-${month}-${dt}`
         
        $('#fecha_select').html(`${extraer_dia_semana_completo(fecha_dia)}, ${dt}-${month}-${year}`);

        localStorage.setItem('dateStr', year+'-' + month + '-'+dt); console.log(year+'-' + month + '-'+dt);

        $('#fecha_invertida').val(info.event.extendedProps.fecha_invertida);

        $('#titulo').val(info.event.title);

        $("#background_color").val(info.event.backgroundColor).trigger("change");         

        $('#descripcion').val(info.event.extendedProps.descripcion);        

        $('#modal-agregar-calendario').modal('show');
      },       
          
      // hiddenDays:[6],       
      
      editable  : true,

      //droppable : true, // this allows things to be dropped onto the calendar !!!

      eventDrop : function(info) {
        //console.log(info);
        date = new Date(info.event.start);  year = date.getFullYear();   month = date.getMonth()+1;  dt = date.getDate();

        if (dt < 10) { dt = '0' + dt; }

        if (month < 10) { month = '0' + month; }
        
        $('#eliminar_registro').show();

        $('#idcalendario').val(info.event.id);

        $('#fecha_feriado').val(year+'-' + month + '-'+dt);

        $('#text_color').val(info.event.textColor);

        $('#fecha_select').html(year+'-' + month + '-'+dt);

        $('#fecha_invertida').val( month + '-'+ dt + '-' + year);

        $('#titulo').val(info.event.title);
         
        $("#background_color").val(info.event.backgroundColor).trigger("change");         

        $('#descripcion').val(info.event.extendedProps.descripcion);

        $("#submit-form-calendario").submit();
      }
    });

    calendar.setOption('locale', 'es');

    if ( localStorage.getItem('dateStr') ) { 
      calendar.changeView('dayGridMonth', localStorage.getItem('dateStr'));       
    }

    localStorage.setItem('dateStr', y + '-' + m + '-' + d);

    calendar.render(); 
  }).fail( function(e) { ver_errores(e); } ); 

  // fechas eliminadas
  $("#external-events-eliminados").html('<div class="text-center"> <i class="fas fa-spinner fa-pulse fa-2x"></i></div>');

  $.post("../ajax/all_calendario.php?op=listar-calendario-e",  function (data, status) {

    data = JSON.parse(data);  console.log(data); 

    if (data.status) {

      $("#external-events-eliminados").html('');

      if (data.data.length != 0) {

        $("#f_delete").html(data.data.length);

        $.each(data.data, function (index, value) {              
          $("#external-events-eliminados").append(
          '<div class="info-box shadow-lg" style="min-height: 10px !important; ">'+
            '<div class="info-box-content">  '   +                                    
              '<span class="info-box-number" > ' + value.title + '</span>'+
            '</div>'+
            '<span class="info-box-icon bg-success" style="font-size: 0.8rem !important; cursor: pointer !important; background-color: '+value.backgroundColor+' !important;" onclick="activar('+value.id+')">'+
              '<i class="fas fa-check" style="color: '+value.textColor+' !important;"></i>'+
            '</span>'+
          '</div>'
          );
        });
        
      } else {

        $("#f_delete").html('0');

        $("#external-events-eliminados").html(
          '<div class="info-box shadow-lg" style="min-height: 10px !important;">'+
            '<div class="info-box-content">  '   +                                    
              '<span class="info-box-number">No hay fechas eliminadas </span>'+
            '</div>'+
            '<span class="info-box-icon bg-success" style="font-size: 0.8rem !important;" >'+
              '<i class="far fa-grin-alt"></i>'+
            '</span>'+
          '</div>'
        );
      }
    } else {
      ver_errores(e);
    }

    
  }).fail( function(e) { ver_errores(e); } ); 
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-calendario")[0]);

  $.ajax({
    url: "../ajax/all_calendario.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      datos = JSON.parse(datos);
      if (datos.status) {	

        Swal.fire("Correcto!", "Fecha guardada correctamente", "success");			 

	      listar();  $("#modal-agregar-calendario").modal("hide"); limpiar();        

        $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');
			}else{
        ver_errores(e);
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
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
      $("#barra_progress").css({ width: "0%",  });
      $("#barra_progress").text("0%");
    },
    complete: function () {
      $("#barra_progress").css({ width: "0%", });
      $("#barra_progress").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  }); 
}

//Función para desactivar registros
function desactivar() {

  let idcalendario = $('#idcalendario').val();
  let titulo = $('#titulo').val();

  crud_simple_alerta(
    '../ajax/all_calendario.php?op=desactivar', 
    idcalendario, 
    '¿Está Seguro de Eliminar esta fecha?', 
    `<b class="text-danger"><del>${titulo}</del></b> <br> Al eliminar, estara en la apartado de fechas eliminadas.!`, 
    'Si, Empezar!',
    function(){ Swal.fire("Eliminado!", "Tu fecha a sido eliminado.", "success"); },
    function(){ listar(); $("#modal-agregar-calendario").modal("hide"); limpiar();}
  );   
}

//Función para activar registros
function activar(idcalendario) {

  crud_simple_alerta(
    '../ajax/all_calendario.php?op=activar', 
    idcalendario, 
    '¿Está Seguro de  Activar esta fecha?', 
    `Esta fecha se podra vizualizar.`, 
    'Si, Empezar!',
    function(){ Swal.fire("Activado!", "Tu fecha a sido reactivada.", "success"); },
    function(){ listar(); }
  );     
}


// Validacion FORM
$(function () {  

  $("#form-calendario").validate({
    rules: {
      titulo:           { required: true, minlength: 3, maxlength: 30 },
      color:            { required: true,  },
      descripcion:      { minlength: 6 },
      background_color: { required: true,  },
    },
    messages: {
      background_color: { required: "Este campo es requerido", },
      titulo:           { required: "Este campo es requerido", minlength: "MÍNIMO 6 caracteres.", maxlength: "MÁXIMO 30 caracteres.", },
      color:            { required: "Ingrese un color de texto", },
      descripcion:      { minlength: "MÍNIMO 4 caracteres.",  },
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
      guardaryeditar(e);
    },

  });
});

function invertColor(hex, bw) {
  if (hex == "#FF0000" || hex == "#FFF700" || hex == '#28A745' ) {   
    //console.log(hex);
    if (hex.indexOf('#') === 0) {
      hex = hex.slice(1);
    }

    // convert 3-digit hex to 6-digits.
    if (hex.length === 3) {
      hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }

    if (hex.length !== 6) {
      throw new Error('Invalid HEX color.');
    }

    var r = parseInt(hex.slice(0, 2), 16),  g = parseInt(hex.slice(2, 4), 16),  b = parseInt(hex.slice(4, 6), 16);

    if (bw) {
      // http://stackoverflow.com/a/3943023/112731
      return (r * 0.299 + g * 0.587 + b * 0.114) > 186 ? '#000000' : '#FFFFFF';
    }
    // invert color components
    r = (255 - r).toString(16);
    g = (255 - g).toString(16);
    b = (255 - b).toString(16);
    // pad each with zeros and return
    return "#" + padZero(r) + padZero(g) + padZero(b);
  } else {
    return "";
  }
}

init();

function fecha_invertida(fecha) {
  
  var fecha_feriado = fecha.split("-");  
  var fecha_invertida = `${fecha_feriado[1]}-${fecha_feriado[2]}-${fecha_feriado[0]}`; //console.log(fecha_feriado);
  
  return fecha_invertida;
}

