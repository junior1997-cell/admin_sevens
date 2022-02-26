var  calendar;  

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lCalendario").addClass("active bg-primary");

  listar(localStorage.getItem('nube_idproyecto') );  

  $("#guardar_registro").on("click", function (e) {  $("#submit-form-calendario").submit(); });

  $("#eliminar_registro").on("click", function (e) { desactivar()  });

  //Initialize Select2 Elements
  $("#background_color").select2({
    theme: "bootstrap4",
    placeholder: "Selecione tipo",
    allowClear: true,
  });

  $("#background_color").val("#FF0000").trigger("change");
}

function contraste() {

  let color = $('#background_color').select2('val'); //console.log(color);

  let color_contrst = invertColor(color, true)

  $('#text_color').val(color_contrst);
}

//Función limpiar
function limpiar() {
  $('#idcalendario').val("");
  $('#fecha_feriado').val("");
  $('#text_color').val('#ffffff');
  $('#fecha_select').html("Selecione una fecha");
  $('#titulo').val('Feriado');
  $("#background_color").val("#FF0000").trigger("change");
  $('#descripcion').val('');
  $('#eliminar_registro').hide();  
}

//Función Listar
function listar(idproyecto) {

  $("#external-events").html('<div class="text-center"> <i class="fas fa-spinner fa-pulse fa-2x"></i></div>');

  $("#custom-tabs-four-home").html('<div class="text-center"> <i class="fas fa-spinner fa-pulse fa-2x"></i></div>');

  // Detalle de dias Proyecto
  $.post("../ajax/calendario.php?op=detalle_dias_proyecto", { idproyecto: idproyecto },  function (data, status) {

    data = JSON.parse(data); //console.log(data);  

    var dia_c = ''; var dia_l = ''; var dia_t = ''; var fecha_p = ''; var count = 0;

    if (data) {
      // Validamos data 1
      if (data.data2.length === 0) { dia_c = 'fechas no definas'; dia_l = 'fechas no definas'; } else {

        let fecha_inicio = new Date(data.data2.fecha_inicio); let fecha_fin = new Date(data.data2.fecha_fin); 

        dia_c = parseInt(data.data2.plazo);  fecha_p = format_d_m_a(data.data2.fecha_inicio) +' - ' + format_d_m_a(data.data2.fecha_fin);

        // validamos data 2
        if (data.data1.length === 0) { dia_l = 'sin feriados'; dia_t = 'sin feriados'; } else {

          $.each(data.data1, function (index, value) {

            let fecha_feriado = new Date(value.fecha_feriado);

            if ( fecha_feriado.getTime() >= fecha_inicio.getTime() && fecha_feriado.getTime() <= fecha_fin.getTime()) {
              count++;
            } 
          });
          // console.log('total: ' + count);
          dia_l = parseInt(data.data2.plazo) - parseInt(count);

          // var dia_t = parseInt(data.data1.cant_feriados);
        }
      }

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
                '<td>Fecha proyecto</td>'+
                '<td>'+ fecha_p +'</td>'+                                            
              '</tr>'+                                      
              '<tr>'+
                '<td>Dias calendario</td>'+
                '<td>'+ dia_c +'</td>'+                                            
              '</tr>'+
              '<tr>'+
                '<td>Dias laborables</td>'+
                '<td>'+ dia_l +'</td>'   +                                         
              '</tr>' +
              '<tr>'+
                '<td>Feriado proyecto</td>'+
                '<td>'+ count +'</td>'   +                                         
              '</tr>' +  
              '<tr> <td> </td> <td> </td> </tr>' +                                        
            '</tbody>'+
          '</table>'+
        '</div>'
      );
      
    } else {
      $("#custom-tabs-four-home").html(
        '<div class="info-box shadow-lg" style="min-height: 10px !important;">'+
          '<div class="info-box-content">  '   +                                    
            '<span class="info-box-number">Las fechas de tu proyecto no estan bien definidas</span>'+
          '</div>'+
          '<span class="info-box-icon bg-success" style="font-size: 0.8rem !important;" >'+
            '<i class="far fa-grin-alt"></i>'+
          '</span>'+
        '</div>'
      );       
    }
  });

  // Domingo no laborable
  $.post("../ajax/calendario.php?op=estado_domingo", { idproyecto: idproyecto },  function (data, status) {

    data = JSON.parse(data);  //console.log(data);

    localStorage.setItem('estado_domingo', data.feriado_domingo);

    if (data.feriado_domingo == "true") {

      $(".fc-day-sun").addClass("fc-day-disabled") 

      $("#my-domingo").prop("checked", true);
      
    } else {

      $("#my-domingo").prop("checked", false);

      $(".fc-day-sun").removeClass("fc-day-disabled")
    }    
     
  });   

  // calendario
  $.post("../ajax/calendario.php?op=listar-calendario", { idproyecto: idproyecto },  function (data, status) {

    data = JSON.parse(data);  //console.log(data); 

    $("#external-events").html('');

    if (data.data1.length != 0) {

      $.each(data.data1, function (index, value) {
             
        $("#external-events").append('<div class="external-event" style="background: '+value.backgroundColor+' !important; color: '+value.textColor+' !important;">'+value.title+'</div>');
      });

    } else {

      $("#external-events").html('<div class="external-event bg-info">No hay fechas disponibles</div>');
    }      
    
    //initialize the calendar
    var date = new Date()

    var d    = date.getDate(), m = date.getMonth(), y = date.getFullYear();

    var Calendar = FullCalendar.Calendar;

    var Draggable = FullCalendar.Draggable;        
    
    var calendarEl = document.getElementById('calendar');

    // initialize the external events     

    calendar = new Calendar(calendarEl, {

      timeZone: 'local',
        
      headerToolbar: {  left: 'prev,next today', center: 'title', right: 'listYear,dayGridMonth' },

      themeSystem: 'bootstrap',
      
      events: data.data1,
       
      // Se ejecuta cuando no hay eventos
      dateClick: function(info) {
        
        $('#idcalendario').val("");

        $('#idproyecto').val(localStorage.getItem('nube_idproyecto'));

        $('#fecha_feriado').val(info.dateStr);

        $('#text_color').val('#ffffff');

        $('#fecha_select').html(info.dateStr);

        $('#titulo').val('Feriado');

        $("#background_color").val("#FF0000").trigger("change");

        $('#descripcion').val('');

        $('#eliminar_registro').hide(); $('#guardar_registro').show();

        $('#modal-agregar-calendario').modal('show');
        
      },

      // Se ejecuta cuando hay un evento
      eventClick: function(info) {

        date = new Date(info.event.start);  year = date.getFullYear();   month = date.getMonth()+1;  dt = date.getDate();

        if (dt < 10) { dt = '0' + dt; }

        if (month < 10) { month = '0' + month; }
        
         //console.log(info.event);

        $('#idcalendario').val(info.event.id);

        if (info.event.extendedProps.idproyecto) {

          $('#idproyecto').val(info.event.extendedProps.idproyecto);

          $('#eliminar_registro').show(); $('#guardar_registro').show();

        } else {

          $('#eliminar_registro').hide(); $('#guardar_registro').hide();
        }
        

        $('#fecha_feriado').val(year+'-' + month + '-'+dt);

        $('#text_color').val(info.event.textColor);

        $('#fecha_select').html(year+'-' + month + '-'+dt);

        $('#titulo').val(info.event.title);

        $("#background_color").val(info.event.backgroundColor).trigger("change");

        $('#descripcion').val(info.event.extendedProps.descripcion);

        $('#modal-agregar-calendario').modal('show');
      },       
          
    //hiddenDays:[6],       
      
      editable  : true,

      //validRange: { start: data.data2.fecha_inicio,  end: data.data2.fecha_fin },
      //droppable : true, // this allows things to be dropped onto the calendar !!!

      eventDrop : function(info) {

        date = new Date(info.event.start);  year = date.getFullYear();   month = date.getMonth()+1;  dt = date.getDate();

        if (dt < 10) { dt = '0' + dt; }

        if (month < 10) { month = '0' + month; }
        
        $('#eliminar_registro').show();

        $('#idcalendario').val(info.event.id);

        $('#fecha_feriado').val(year+'-' + month + '-'+dt);

        $('#text_color').val(info.event.textColor);

        $('#fecha_select').html(year+'-' + month + '-'+dt);

        $('#titulo').val(info.event.title);

        $("#background_color").val(info.event.backgroundColor).trigger("change");

        $('#descripcion').val(info.event.extendedProps.descripcion);

        if (info.event.extendedProps.idproyecto) {

          $('#idproyecto').val(info.event.extendedProps.idproyecto);

          $("#submit-form-calendario").submit();

        } else {

          Swal.fire("Usted no puede mover!", 'Para editar esta fecha, usted nesecita ir a Fechas Globales', "error");
          
          info.revert();
        }
        
      },

      // eventResize: function(info) {
      //   alert(info.event.title + " end is now " + info.event.end.toISOString());
    
      //   if (!confirm("is this okay?")) {
      //     info.revert();
      //   }
      // }
      
    });

    calendar.setOption('locale', 'es');

    if (data.data2.length != 0) { 
      calendar.changeView('dayGridMonth', data.data2.fecha_inicio);       
    } 
    
    calendar.render(); 
     
  });

  // fechas eliminadas
  $("#external-events-eliminados").html('<div class="text-center"> <i class="fas fa-spinner fa-pulse fa-2x"></i></div>');

  $.post("../ajax/calendario.php?op=listar-calendario-e", { idproyecto: idproyecto },  function (data, status) {

    data = JSON.parse(data);  //console.log(data); 

    $("#external-events-eliminados").html('');

    if (data.length != 0) {

      $.each(data, function (index, value) {
              
        $("#external-events-eliminados").append(
        '<div class="info-box shadow-lg" style="min-height: 10px !important; ">'+
          '<div class="info-box-content">  '   +                                    
            '<span class="info-box-number" >' + value.title+ '</span>'+
          '</div>'+
          '<span class="info-box-icon bg-success" style="font-size: 0.8rem !important; cursor: pointer !important; background-color: '+value.backgroundColor+' !important;" onclick="activar('+value.id+')">'+
            '<i class="fas fa-check" style="color: '+value.textColor+' !important;"></i>'+
          '</span>'+
        '</div>'
        );
      });
      
    } else {

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
  }); 
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-calendario")[0]);

  $.ajax({
    url: "../ajax/calendario.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {	

        Swal.fire("Correcto!", "Fecha guardado correctamente", "success");			 

	      listar(localStorage.getItem('nube_idproyecto'));  $("#modal-agregar-calendario").modal("hide"); limpiar();        

			}else{

        Swal.fire("Error!", datos, "error");

			}
    },
  });
}

//Función para desactivar registros
function desactivar() {

  let idcalendario = $('#idcalendario').val();

  Swal.fire({
    title: "¿Está Seguro de Eliminar esta fecha?",
    text: "Al eliminar no podra recuperarlo",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {

    if (result.isConfirmed) {

      $.post("../ajax/calendario.php?op=desactivar", { idcalendario: idcalendario }, function (e) {

        Swal.fire("Eliminado!", "Tu Fecha a sido eliminado.", "success");
    
        listar(localStorage.getItem('nube_idproyecto')); $("#modal-agregar-calendario").modal("hide"); limpiar();    
      });      
    }
  });   
}

//Función para activar registros
function activar(idcalendario) {
  Swal.fire({
    title: "¿Está Seguro de  Activar esta fecha?",
    text: "Esta fecha se podra vizualizar.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/calendario.php?op=activar", { idcalendario: idcalendario }, function (e) {

        Swal.fire("Activado!", "Tu Fecha ha sido activado.", "success");

        listar(localStorage.getItem('nube_idproyecto')); 
      });
      
    }
  });      
}

// Validacion FORM
$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {

      guardaryeditar(e);

    },
  });

  $("#form-calendario").validate({
    rules: {
      titulo: { required: true, minlength: 3, maxlength: 30 },
      background_color: { required: true,  },
      descripcion: { minlength: 6 },
    },
    messages: {

      titulo: {
        required: "Este campo es requerido",
        minlength: "El titulo debe tener MÍNIMO 6 caracteres.",
        maxlength: "El titulo debe tener como MÁXIMO 30 caracteres.", 
      },

      background_color: {
        required: "Este campo es requerido",        
      },

      descripcion: {
        minlength: "La descripcion debe tener MÍNIMO 4 caracteres.",
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

function invertColor(hex, bw) {
  if (hex == "#FF0000" || hex == "#FFF700" || hex == '#28A745') {
    
  
    console.log(hex);
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

$("#my-domingo").on('click ', function(e){
  
  if( $('#my-domingo').is(':checked') ) {    
    
    $(".fc-day-sun").addClass("fc-day-disabled")  

    $.post("../ajax/calendario.php?op=activar_domingo", { idproyecto: localStorage.getItem('nube_idproyecto') }, function (e) {      

      Swal.fire("Activado!", "Tu DOMINGO no es laborable.", "success");      

      listar(localStorage.getItem('nube_idproyecto')); 
    });
    
  }else{

    $(".fc-day-sun").removeClass("fc-day-disabled") 

    $.post("../ajax/calendario.php?op=desactivar_domingo", { idproyecto: localStorage.getItem('nube_idproyecto') }, function (e) {
      
      Swal.fire("Desactivado!", "Tu DOMINGO es laborable", "success");

      listar(localStorage.getItem('nube_idproyecto')); 
    });    
  }
});

init();

function format_d_m_a(fecha) {

  let splits = fecha.split("-"); 

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}




 





