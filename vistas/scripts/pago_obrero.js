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

function detalle_q_s_trabajador() {

  table_show_hide(2)

  // $.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario }, function (data, status) {

  //   data = JSON.parse(data);  //console.log(data);   

     
  // });

   
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

