var tabla;

//Función que se ejecuta al inicio
function init() {
  
  listar_tbla_principal(localStorage.getItem('nube_idproyecto'));

  //Mostramos los trabajadores
  $.post("../ajax/usuario.php?op=select2Trabajador&id=", function (r) { $("#trabajador").html(r); });

  $("#bloc_PagosTrabajador").addClass("menu-open");

  $("#mPagosTrabajador").addClass("active");

  $("#lPagosAdministrador").addClass("active");

  $("#guardar_registro").on("click", function (e) { $("#submit-form-usuario").submit(); });

  //Initialize Select2 unidad
  $("#forma_pago").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar una forma de pago",
    allowClear: true,
  });
   
  // Formato para telefono
  $("[data-mask]").inputmask();   
} 

function table_show_hide(flag) {
  if (flag == 1) {
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();
    $("#btn-agregar").hide(); 
    $("#btn-nombre-mes").hide();

    $(".nombre-trabajador").html("Pagos de Administradores");

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

//Función limpiar
function limpiar() {  

  $("#idusuario").val("");
  $("#trabajador_c").html("Trabajador");
  $("#trabajador").val("null").trigger("change"); 
  $("#trabajador_old").val("");  
  $("#login").val("");
  $("#password").val("");
  $("#password-old").val("");  
}

//Función Listar - tabla principal
function listar_tbla_principal(nube_idproyecto) {

  tabla=$('#tabla-principal').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/pago_administrador.php?op=listar_tbla_principal&nube_idproyecto='+nube_idproyecto,
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

  
}
  
//Función para ver detalle de fechas por  trabajador
function detalle_fechas_mes_trabajador(id_tabajador_x_proyecto, nombre_trabajador, fecha_inicial, fecha_hoy, fecha_final, sueldo_mensual) {

  table_show_hide(2);

  $(".nombre-trabajador").html(`Pagos - <b> ${nombre_trabajador} </b>`);

  if (fecha_inicial == '- - -' || fecha_hoy == '- - -') {

    $('.data-fechas-mes').html(`<tr>
      <td colspan="8">
        <div class="alert alert-warning alert-dismissible text-left">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h5><i class="icon fas fa-ban"></i> Alerta!</h5>
          Las fechas: 
          <ul>
            <li> <b>Inicial</b></li>
            <li> <b>Final</b></li>
          </ul>
          No estan definidas corectamente, <b>EDITE las fechas</b> de trabajo de este trabajdor, para realizar sus pagos correctamente.
        </div>
      </td>       
    </tr>`);
    $('.monto_x_mes_total').html('<i class="far fa-frown fa-2x text-danger"></i>');
    $('.monto_x_mes_pagado_total').html('<i class="far fa-frown fa-2x text-danger"></i>');

  } else {
    $.post("../ajax/pago_administrador.php?op=mostrar_fechas_mes", { 'id_tabajador_x_proyecto': id_tabajador_x_proyecto }, function (data, status) {
    
      data = JSON.parse(data);   console.log(data);
  
    });
  }  
}

// Listar - TABLA INGRESO DE PAGOS
function listar_tbla_pagos_x_mes(nube_idproyecto) {

  table_show_hide(3);

  tabla=$('#tabla-ingreso-pagos').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/pago_administrador.php?op=listar_tbla_pagos_x_mes&nube_idproyecto='+nube_idproyecto,
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

