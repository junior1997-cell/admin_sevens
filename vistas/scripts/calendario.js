var tabla;

//Función que se ejecuta al inicio
function init() {

  listar( );

  // $("#bloc_Calendario").addClass("menu-open");

  $("#mCalendario").addClass("active");

  // $("#lCalendario").addClass("active");

  $("#guardar_registro").on("click", function (e) {  $("#submit-form-calendario").submit(); });

}

function contraste() {

  let color = $('#background_color').val();

  let color_contrst = invertColor(color, true)
  
  $('#text_color').val(color_contrst);
}

//Función limpiar
function limpiar() {
  $("#idtrabajador").val("");
  $("#nombre").val(""); 
  $("#num_documento").val(""); 
  $("#direccion").val(""); 
  $("#telefono").val(""); 
  $("#email").val(""); 
  $("#nacimiento").val("");
  $("#edad").val("0");     
  $("#c_bancaria").val("");  
  $("#banco").val("").trigger("change");
  $("#titular_cuenta").val("");

  
}

//Función Listar
function listar() {

  
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-calendario")[0]);

  $.ajax({
    url: "../ajax/all_trabajador.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {	

        Swal.fire("Correcto!", "Trabajador guardado correctamente", "success");			 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-trabajador").modal("hide");

			}else{

        Swal.fire("Error!", datos, "error");

			}
    },
  });
}

// mostramos los datos para editar
function mostrar(idtrabajador) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-trabajador").modal("show")

  $.post("../ajax/all_trabajador.php?op=mostrar", { idtrabajador: idtrabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();


    $("#tipo_documento option[value='"+data.tipo_documento+"']").attr("selected", true);
    $("#nombre").val(data.nombres);
    $("#num_documento").val(data.numero_documento);
    $("#direccion").val(data.direccion);
    $("#telefono").val(data.telefono);
    $("#email").val(data.email);
    $("#nacimiento").val(data.fecha_nacimiento);
    $("#c_bancaria").val(data.cuenta_bancaria);
    $("#banco").val(data.idbancos).trigger("change");
    $("#titular_cuenta").val(data.titular_cuenta);
    $("#idtrabajador").val(data.idtrabajador);

    if (data.imagen_perfil != "") {

			$("#foto1_i").attr("src", "../dist/img/usuarios/" + data.imagen_perfil);

			$("#foto1_actual").val(data.imagen_perfil);
		}

    if (data.imagen_dni_anverso != "") {

			$("#foto2_i").attr("src", "../dist/img/usuarios/" + data.imagen_dni_anverso);

			$("#foto2_actual").val(data.imagen_dni_anverso);
		}

    if (data.imagen_dni_reverso != "") {

			$("#foto3_i").attr("src", "../dist/img/usuarios/" + data.imagen_dni_reverso);

			$("#foto3_actual").val(data.imagen_dni_reverso);
		}

    edades();
  });
}


init();

$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {

      guardaryeditar(e);

    },
  });

  $("#form-calendario").validate({
    rules: {
      titulo: { required: true, minlength: 3, maxlength: 20 },
      color: { required: true,  },
      descripcion: { minlength: 6 },
    },
    messages: {

      titulo: {
        required: "Por favor selecione un tipo de documento",
        minlength: "El color debe tener MÍNIMO 6 caracteres.",
        maxlength: "El color debe tener como MÁXIMO 20 caracteres.", 
      },

      color: {
        required: "Ingrese un número de documento",        
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

// Full Calendar
$(function () {       

  /* initialize the calendar
  -----------------------------------------------------------------*/
  //Date for the calendar events (dummy data)
  var date = new Date()
  var d    = date.getDate(), m = date.getMonth(), y = date.getFullYear();

  var Calendar = FullCalendar.Calendar;
  var Draggable = FullCalendar.Draggable;        
  
  var calendarEl = document.getElementById('calendar');

  // initialize the external events
  // -----------------------------------------------------------------        

  var calendar = new Calendar(calendarEl, {
    headerToolbar: {
      left  : 'prev,next today',
      center: 'title',
      right : 'listYear,dayGridMonth'
    },
    themeSystem: 'bootstrap',

    //Random default events
    events: [
      {
        title           : 'All Day Event',
        description     : 'dsdsdsddd',
        start           : new Date(y, m, 1),
        backgroundColor : '#fff', //red
        borderColor     : '#fff', //red
        textColor       : '#212529',
        allDay          : true
      },

      
    ],
    dateClick: function(info) {
      // alert('Clicked on: ' + info.dateStr);
      // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
      // alert('Current view: ' + info.view.type);
      // change the day's background color just for fun
      // info.dayEl.style.backgroundColor = 'red';
      $('#modal-agregar-calendario').modal('show');
    },
     
    editable  : true,
    droppable : true, // this allows things to be dropped onto the calendar !!!
    drop      : function(info) {
      // is the "remove after drop" checkbox checked?
      if (checkbox.checked) {
        // if so, remove the element from the "Draggable Events" list
        info.draggedEl.parentNode.removeChild(info.draggedEl);
      }
    }
  });
  calendar.setOption('locale', 'es');
  calendar.render();
  // $('#calendar').fullCalendar()       
   
})


function invertColor(hex, bw) {
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
  var r = parseInt(hex.slice(0, 2), 16),
      g = parseInt(hex.slice(2, 4), 16),
      b = parseInt(hex.slice(4, 6), 16);
  if (bw) {
      // http://stackoverflow.com/a/3943023/112731
      return (r * 0.299 + g * 0.587 + b * 0.114) > 186
          ? '#000000'
          : '#FFFFFF';
  }
  // invert color components
  r = (255 - r).toString(16);
  g = (255 - g).toString(16);
  b = (255 - b).toString(16);
  // pad each with zeros and return
  return "#" + padZero(r) + padZero(g) + padZero(b);
}



