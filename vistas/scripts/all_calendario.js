var  calendar;  

//Función que se ejecuta al inicio
function init() {

  listar();   

  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

  $("#lAllCalendario").addClass("active");

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

  let color = $('#background_color').select2('val');

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
function listar() {

  $("#external-events").html('<div class="text-center"> <i class="fas fa-spinner fa-pulse fa-2x"></i></div>');

  $("#custom-tabs-four-home").html('<div class="text-center"> <i class="fas fa-spinner fa-pulse fa-2x"></i></div>');

  $.post("../ajax/all_calendario.php?op=listar-calendario",  function (data, status) {

    data = JSON.parse(data);   //console.log(data); 

    $("#external-events").html('');

    if (data.fechas.length != 0) {

      $.each(data.fechas, function (index, value) {
             
        $("#external-events").append('<div class="external-event" style="background: '+value.backgroundColor+' !important; color: '+value.textColor+' !important;">'+value.title+' - '+ value.start +' </div>');
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

        $('#fecha_select').html(info.dateStr);

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

        $('#fecha_select').html(year+'-' + month + '-'+dt);

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
  });

  // fechas eliminadas
  $("#external-events-eliminados").html('<div class="text-center"> <i class="fas fa-spinner fa-pulse fa-2x"></i></div>');

  $.post("../ajax/all_calendario.php?op=listar-calendario-e",  function (data, status) {

    data = JSON.parse(data);  //console.log(data); 

    $("#external-events-eliminados").html('');

    if (data.length != 0) {

      $("#f_delete").html(data.length);

      $.each(data, function (index, value) {              
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
  });
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
             
      if (datos == 'ok') {	

        Swal.fire("Correcto!", "Fecha guardada correctamente", "success");			 

	      listar();  $("#modal-agregar-calendario").modal("hide"); limpiar();        

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

      $.post("../ajax/all_calendario.php?op=desactivar", { idcalendario: idcalendario }, function (e) {

        Swal.fire("Eliminado!", "Tu fecha a sido eliminado.", "success");
    
        listar(); $("#modal-agregar-calendario").modal("hide"); limpiar();    
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
      $.post("../ajax/all_calendario.php?op=activar", { idcalendario: idcalendario }, function (e) {

        Swal.fire("Activado!", "Tu trabajador ha sido activado.", "success");

        listar(); 
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
      color: { required: true,  },
      descripcion: { minlength: 6 },
      background_color: { required: true,  },
    },
    messages: {

      background_color: {
        required: "Este campo es requerido",        
      },

      titulo: {
        required: "Este campo es requerido",
        minlength: "El color debe tener MÍNIMO 6 caracteres.",
        maxlength: "El color debe tener como MÁXIMO 30 caracteres.", 
      },

      color: {
        required: "Ingrese un color de texto",        
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
  
  var fecha_feriado = fecha.split("-");  var fecha_invertida = fecha_feriado[1] + "-" + fecha_feriado[2] + "-" + fecha_feriado[0]; //console.log(fecha_feriado);
  
  return fecha_invertida;
}

