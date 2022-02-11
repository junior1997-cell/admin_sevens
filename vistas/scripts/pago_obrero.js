var tabla;

          
//Función que se ejecuta al inicio
function init() {
  
  listar_tbla_principal(localStorage.getItem('nube_idproyecto'));

  //Activamos el "aside"
  $("#bloc_PagosTrabajador").addClass("menu-open");

  $("#mPagosTrabajador").addClass("active");

  $("#lPagosObrero").addClass("active");

  // ejecutamos el "FORM"
  $("#guardar_registro").on("click", function (e) { $("#submit-form-usuario").submit(); });

  //Initialize Select2 Elements
  $("#trabajadores").select2({
    theme: "bootstrap4",
    placeholder: "Selecione trabajador",
    allowClear: true,
  });

  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

//Función limpiar
function limpiar() {

  $("#idusuario").val("");
  $("#trabajador_c").html("Trabajador");
  $("#trabajador").val("null").trigger("change"); 
  $("#trabajador_old").val(""); 
  $("#cargo").val("Administrador").trigger("change"); 
  $("#login").val("");
  $("#password").val("");
  $("#password-old").val("");  
}

function table_show_hide(flag) {
  if (flag == 1) {
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();
    $("#btn-agregar").hide(); 
    $("#btn-nombre-mes").hide();

    $(".nombre-trabajador").html("Pagos de Obreros");

    $("#tbl-principal").show();
    $("#tbl-fechas").hide();
    $("#tbl-ingreso-pagos").hide();
  } else {
    if (flag == 2) {
      $("#btn-regresar").show();
      $("#btn-regresar-todo").hide();
      $("#btn-regresar-bloque").hide();
      $("#btn-agregar").hide();
      $("#btn-nombre-mes").hide();

      $("#tbl-principal").hide();
      $("#tbl-fechas").show();
      $("#tbl-ingreso-pagos").hide();
    }else{
      if (flag == 3) {
        $("#btn-regresar").hide();
        $("#btn-regresar-todo").show();
        $("#btn-regresar-bloque").show();
        $("#btn-agregar").show();
        $("#btn-nombre-mes").show();

        $("#tbl-principal").hide();
        $("#tbl-fechas").hide();
        $("#tbl-ingreso-pagos").show();
        
      }
    }
  }
}

// ver detalle de todos los pagos de un trabajador
function listar_tbla_principal(id_proyecto) {

  table_show_hide(1);

  tabla=$('#tabla-principal').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
      url: `../ajax/pago_obrero.php?op=listar_tbla_principal&nube_idproyecto=${id_proyecto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {          

      // columna: pago total
      if (data[4] != '') {
        $("td", row).eq(4).css({
          "text-align": "center"
        });         
      }   
      
      // columna: sueldo mensual
      if (data[5] != '') {
        $("td", row).eq(5).css({
          "text-align": "center"
        });
      }

      // columna: sueldo mensual
      if (data[6] != '') {
        $("td", row).eq(6).css({
          "text-align": "right"
        });
      }
      // columna: sueldo mensual
      if (data[7] != '') {
        $("td", row).eq(7).css({
          "text-align": "center"
        });
      }

      // columna: sueldo mensual
      if (data[8] != '') {
        $("td", row).eq(8).css({
          "text-align": "right"
        });
      }

      // columna: sueldo mensual
      if (data[9] != '') {
        $("td", row).eq(9).css({
          "text-align": "right"
        });
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
}



function detalle_q_s_trabajador(id_trabajdor_x_proyecto, tipo_pago, nombre_trabajador) {

  $('.data-q-s').html(`<tr>
    <td colspan="10" >
      <div class="row">
        <div class="col-lg-12 text-center">
          <i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>
          <h4>Cargando...</h4>
        </div>
      </div>
    </td>                                   
  </tr>`);

  $(".nombre-trabajador").html(`Pagos - <b> ${nombre_trabajador} </b>`);

  if (tipo_pago == "quincenal") {
    $(".nombre-bloque-asistencia").html(`<b> Quincena </b>`);
  } else {
    if (tipo_pago == "semanal") {
      $(".nombre-bloque-asistencia").html(`<b> Semana </b>`);
    }
  }
  
  table_show_hide(2);

  $.post("../ajax/pago_obrero.php?op=mostrar_q_s", { 'id_trabajdor_x_proyecto': id_trabajdor_x_proyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);

    if (data.length === 0) {
      
    } else {

      var data_s_q = ""; var total_hn = 0; var total_he = 0; var total_monto_hn = 0; var total_monto_he = 0; var total_descuento = 0;
      var total_quincena = 0; var total_saldo = 0; var total_deposito = 0;

      data.forEach((element, indice) => {
        var saldo = 0;
        data_s_q = data_s_q.concat(`<tr>
          <td>${indice + 1}</td>
          <td> ${element.numero_q_s}</td>
          <td>${element.fecha_q_s_inicio}</td>
          <td>${element.fecha_q_s_fin}</td>
          <td><sup>S/. </sup>${element.sueldo_hora}</td>
          <td>${formato_miles(element.total_hn)}<b> / </b>${formato_miles(element.total_he)}</td>          
          <td><sup>S/. </sup>${formato_miles(element.pago_parcial_hn)}<b> / </b><sup>S/. </sup>${formato_miles(element.pago_parcial_he)}</td>
          <td><sup>S/. </sup>${formato_miles(element.adicional_descuento)}</td>
          <td><sup>S/. </sup>${formato_miles(element.pago_quincenal)}</td>
          <td>
            <button class="btn btn-info btn-sm" onclick="listar_tbla_pagos_x_q_s(1);"><i class="fas fa-dollar-sign"></i> Pagar</button>
            <button style="font-size: 14px;" class="btn btn-danger btn-sm">S/. 900.00</button></div>
          </td>
          <td><sup>S/. </sup>${formato_miles(saldo)}</td>
        </tr>`);
        
        total_hn += parseFloat(element.total_hn);
        total_he += parseFloat(element.total_he);

        total_monto_hn += parseFloat(element.pago_parcial_hn);
        total_monto_he += parseFloat(element.pago_parcial_he);
        total_descuento += parseFloat(element.adicional_descuento);
        total_quincena += parseFloat(element.pago_quincenal);
      });

      $('.data-q-s').html(data_s_q);
      $('.total_hn_he').html(`${formato_miles(total_hn)} / ${formato_miles(total_he)}`);
      $('.total_monto_hn_he').html(`${formato_miles(total_monto_hn)} / ${formato_miles(total_monto_he)}`);
      $('.total_descuento').html(`${formato_miles(total_descuento)}`);
      $('.total_quincena').html(`${formato_miles(total_quincena)}`);
      $('.total_deposito').html(`${formato_miles(total_deposito)}`); 
      $('.total_saldo').html(`${formato_miles(total_saldo)}`); 
    }     
  });   
}

function listar_tbla_pagos_x_q_s(params) {
  table_show_hide(3);
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-usuario")[0]);

  $.ajax({
    url: "../ajax/usuario.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Usuario registrado correctamente')				 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-usuario").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

//Función para desactivar registros
function desactivar(idusuario) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  el Usuario?",
    text: "Este usuario no podrá ingresar al sistema!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/usuario.php?op=desactivar", { idusuario: idusuario }, function (e) {
        if (e == 'ok') {

          Swal.fire("Desactivado!", "Tu usuario ha sido Desactivado.", "success");		 
  
          tabla.ajax.reload();
          
        }else{
  
          Swal.fire("Error!", e, "error");
        }
      });      
    }
  });   
}

//Función para activar registros
function activar(idusuario) {

  Swal.fire({

    title: "¿Está Seguro de  Activar  el Usuario?",
    text: "Este usuario tendra acceso al sistema",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",

  }).then((result) => {

    if (result.isConfirmed) {

      $.post("../ajax/usuario.php?op=activar", { idusuario: idusuario }, function (e) {

        if (e == 'ok') {

          Swal.fire("Activado!", "Tu usuario ha sido activado.", "success");		 
  
          tabla.ajax.reload();
          
        }else{
  
          Swal.fire("Error!", e, "error");
        }
      });      
    }
  });      
}


init();

$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {     

      guardaryeditar(e);
    },
  });

  $("#form-usuario").validate({
    rules: {
      login: { required: true, minlength: 3, maxlength: 20 },
      password: {minlength: 4, maxlength: 20 },
      // terms: { required: true },
    },
    messages: {
      login: {
        required: "Por favor ingrese un login.",
        minlength: "El login debe tener MÍNIMO 4 caracteres.",
        maxlength: "El login debe tener como MÁXIMO 20 caracteres.",
      },
      password: {
        minlength: "La contraseña debe tener MÍNIMO 4 caracteres.",
        maxlength: "La contraseña debe tener como MÁXIMO 20 caracteres.",
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

      if ($("#trabajador").select2("val") == null && $("#trabajador_old").val() == "") {
         
        $("#trabajador_validar").show(); //console.log($("#trabajador").select2("val") + ", "+ $("#trabajador_old").val());

      } else {

        $("#trabajador_validar").hide();
      }       
    },
  });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


// Función que suma o resta días a la fecha indicada
sumaFecha = function(d, fecha){
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

// quitamos las comas de miles de un numero
function quitar_formato_miles(numero) {
  let inVal = numero.replace(/,/g, '');
  return inVal;
}

// damos formato de miles a un numero
function formato_miles(num) {
  if (!num || num == "NaN") return "0.00";
  if (num == "Infinity") return "&#x221e;";
  num = num.toString().replace(/\$|\,/g, "");
  if (isNaN(num)) num = "0";
  sign = num == (num = Math.abs(num));
  num = Math.floor(num * 100 + 0.50000000001);
  cents = num % 100;
  num = Math.floor(num / 100).toString();
  if (cents < 10) cents = "0" + cents;
  for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) num = num.substring(0, num.length - (4 * i + 3)) + "," + num.substring(num.length - (4 * i + 3));
  return (sign ? "" : "-") + num + "." + cents;
}