var tabla;

//Función que se ejecuta al inicio
function init() {

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar(localStorage.getItem('nube_idproyecto'));

  //Mostramos los trabajadores
  $.post("../ajax/registro_asistencia.php?op=select2Trabajador&nube_idproyecto="+localStorage.getItem('nube_idproyecto'), function (r) { $("#trabajador").html(r); });

  // $("#bloc_Accesos").addClass("menu-open");

  $("#mAsistencia").addClass("active");

  // $("#lasistencia").addClass("active");

  $("#guardar_registro").on("click", function (e) {

    $("#submit-form-asistencia").submit();
  });

    //Initialize Select2 Elements
    $("#trabajador").select2({
      theme: "bootstrap4",
      placeholder: "Selecione trabajador",
      allowClear: true,
    });
    $("#trabajador").val("null").trigger("change");

  // Formato para telefono
  $("[data-mask]").inputmask();

  // $('.timepicker').datetimepicker({
  //   timeFormat: 'h:mm p',
  //   interval: 60,
  //   minTime: '10',
  //   maxTime: '6:00pm',
  //   defaultTime: '11',
  //   startTime: '10:00',
  //   dynamic: false,
  //   dropdown: true,
  //   scrollbar: true
  // });
  //Timepicker
  $('#timepicker').datetimepicker({
    // format: 'LT',
    format:'HH:mm ',
    lang:'ru'
  })

}
function seleccion() {

  if ($("#trabajador").select2("val") == null) {

    $("#trabajador_validar").show();
    console.log($("#trabajador").select2("val"));

  } else {

    $("#trabajador_validar").hide();
    console.log($("#trabajador").select2("val"));
  }
}


/**
 idasistencia
 trabajador
 horas_tabajo
 */


//Función limpiar
function limpiar() {
  $("#idasistencia").val(""); 
  $("#trabajador").val("null").trigger("change");
  $("#horas_tabajo").val("");
}
// Función que suma o resta días a la fecha indicada

sumaFecha = function(d, fecha)
{
 var Fecha = new Date();
 var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
 var sep = sFecha.indexOf('/') != -1 ? '/' : '-';
 var aFecha = sFecha.split(sep);
 var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];
 fecha= new Date(fecha);
 fecha.setDate(fecha.getDate()+parseInt(d));
 var anno=fecha.getFullYear();
 var mes= fecha.getMonth()+1;
 var dia= fecha.getDate();
 mes = (mes < 10) ? ("0" + mes) : mes;
 dia = (dia < 10) ? ("0" + dia) : dia;
 var fechaFinal = dia+sep+mes+sep+anno;
 return (fechaFinal);
 }

//Función Listar
function listar(nube_idproyecto) {

  tabla=$('#tabla-asistencia').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/registro_asistencia.php?op=listar&nube_idproyecto='+nube_idproyecto,
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
        }
      },
    "language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      }
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();
  //Listar quincenas(botones)


  $.post("../ajax/registro_asistencia.php?op=listarquincenas", { nube_idproyecto: nube_idproyecto }, function (data, status) {

    
    data =JSON.parse(data);
    console.log(data);
   
    var fecha = data.fecha_inicio;
    console.log(fecha);
    var fecha_i = sumaFecha(0,fecha);
    var cal_quincena  =data.plazo/15;
    var i=0;
    var cont=0
    $('#Lista_quincenas').html('');

    while (i <= cal_quincena) {
      cont=cont+1;
      var fecha_inicio = fecha_i;
      
      fecha=sumaFecha(14,fecha_inicio);

      console.log(fecha_inicio+'-'+fecha);
      ver_asistencia="'"+fecha_inicio+"',"+"'"+fecha+"'";
      $('#Lista_quincenas').append(' <button type="button" class="btn bg-gradient-info text-center" onclick="datos_quincena('+ver_asistencia+');"><i class="far fa-calendar-alt"></i> Quincena '+cont+'<br>'+fecha_inicio+'-'+fecha+'</button>')
      fecha_i =sumaFecha(1,fecha);
      i++;
    }
    //console.log(fecha);


  });
}
function datos_quincena(f1,f2) {
  console.log('----------'+f1,f2);
      
  $("#cargando-1-fomulario").hide();
  $("#tabla-asistencia-trab").hide();
  $("#card-titulo-registrar").hide();
  $("#cargando-2-fomulario").show();
  $("#ver_asistencia").show();
  $("#card-titulo").show();

  $("#cargando-1-fomulario").show();
  $("#cargando-2-fomulario").hide();
  
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-asistencia")[0]);

  $.ajax({
    url: "../ajax/registro_asistencia.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('asistencia registrado correctamente')				 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-asistencia").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idasistencia) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-asistencia").modal("show")

  $.post("../ajax/asistencia.php?op=mostrar", { idasistencia: idasistencia }, function (data, status) {

  });
}

//Función para desactivar registros
function justificar(idasistencia) {
  console.log('holaaaaa');
 
}
// ver_asistencias
function ver_asistencias(idtrabajador,fecha_inicio_proyect) {
  console.log(idtrabajador,fecha_inicio_proyect);
  
  $("#cargando-1-fomulario").hide();
  $("#tabla-asistencia-trab").hide();
  $("#card-titulo-registrar").hide();
  $("#cargando-2-fomulario").show();
  $("#ver_asistencia").show();
  $("#card-titulo").show();

  $.post("../ajax/registro_asistencia.php?op=ver_asistencia_trab", { idtrabajador: idtrabajador,fecha_inicio_proyect:fecha_inicio_proyect  }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

     $("#tipo_documento option[value='"+data.tipo_documento+"']").attr("selected", true);
     $("#nombre").val(data.razon_social);
     $("#num_documento").val(data.ruc);
     $("#direccion").val(data.direccion);
     $("#telefono").val(data.telefono);
     $("#banco option[value='"+data.idbancos+"']").attr("selected", true);
     $("#c_bancaria").val(data.cuenta_bancaria);
     $("#c_detracciones").val(data.cuenta_detracciones);
     $("#titular_cuenta").val(data.titular_cuenta);
     $("#idproveedor").val(data.idproveedor);

  });

 // $("#modal-ver-asistencia").modal("show")

 
}
function regresar_principal(){
  $("#cargando-1-fomulario").show();
  $("#tabla-asistencia-trab").show();
  $("#card-titulo-registrar").show();
  $("#cargando-2-fomulario").hide();
  $("#ver_asistencia").hide();
  $("#card-titulo").hide();
}

init();

$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {

      if ($("#trabajador").select2("val") == null) {
        
        $("#trabajador_validar").show(); //console.log($("#trabajador").select2("val") + ", "+ $("#trabajador_old").val());

      } else {

        $("#trabajador_validar").hide();

        guardaryeditar(e);
      }
    },
  });

  $("#form-asistencia").validate({
    rules: {

      horas_tabajo: { required: true},


      // terms: { required: true },
    },
    messages: {
      horas_tabajo: {
        required: "Por favor  seleccione la hora",
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

      
      if ($("#trabajador").select2("val") == null) {
         
        $("#trabajador_validar").show(); //console.log($("#trabajador").select2("val") + ", "+ $("#trabajador_old").val());

      } else {

        $("#trabajador_validar").hide();
      }  

    },



  });
});





// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_documento").val();

  let dni_ruc = $("#num_documento").val(); 
   
  if (tipo_doc == "DNI") {

    if (dni_ruc.length == "8") {

      $.post("../ajax/persona.php?op=reniec", { dni: dni_ruc }, function (data, status) {

        data = JSON.parse(data);

        console.log(data);

        if (data.success == false) {

          $("#search").show();

          $("#charge").hide();

          toastr.error("Es probable que el sistema de busqueda esta en mantenimiento o los datos no existe en la RENIEC!!!");

        } else {

          $("#search").show();

          $("#charge").hide();

          $("#nombre").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

          toastr.success("Cliente encontrado!!!!");
        }
      });
    } else {

      $("#search").show();

      $("#charge").hide();

      toastr.info("Asegurese de que el DNI tenga 8 dígitos!!!");
    }
  } else {
    if (tipo_doc == "RUC") {

      if (dni_ruc.length == "11") {
        $.post("../ajax/persona.php?op=sunat", { ruc: dni_ruc }, function (data, status) {

          data = JSON.parse(data);

          console.log(data);
          if (data.success == false) {

            $("#search").show();

            $("#charge").hide();

            toastr.error("Datos no encontrados en la SUNAT!!!");
            
          } else {

            if (data.estado == "ACTIVO") {

              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);
              
              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);
              // $("#direccion").val(data.direccion);
              toastr.success("Cliente encontrado");
            } else {

              toastr.info("Se recomienda no generar BOLETAS o Facturas!!!");

              $("#search").show();

              $("#charge").hide();

              $("#nombre").val(data.razonSocial);

              data.nombreComercial == null ? $("#apellidos_nombre_comercial").val("-") : $("#apellidos_nombre_comercial").val(data.nombreComercial);
              
              data.direccion == null ? $("#direccion").val("-") : $("#direccion").val(data.direccion);

              // $("#direccion").val(data.direccion);
            }
          }
        });
      } else {
        $("#search").show();

        $("#charge").hide();

        toastr.info("Asegurese de que el RUC tenga 11 dígitos!!!");
      }
    } else {
      if (tipo_doc == "CEDULA" || tipo_doc == "OTRO") {

        $("#search").show();

        $("#charge").hide();

        toastr.info("No necesita hacer consulta");

      } else {

        $("#tipo_doc").addClass("is-invalid");

        $("#search").show();

        $("#charge").hide();

        toastr.error("Selecione un tipo de documento");
      }
    }
  }
}
