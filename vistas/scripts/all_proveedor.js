var tabla;

//Función que se ejecuta al inicio
function init() {
  listar();

  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

   $("#lAllTrabajador").addClass("active");

  $("#guardar_registro").on("click", function (e) {

    $("#submit-form-proveedor").submit();
  });

  // Formato para telefono
  $("[data-mask]").inputmask();

}

//Función limpiar
function limpiar() {
  $("#idproveedor").val(""); 
  $("#tipo_documento option[value='RUC']").attr("selected", true);
  $("#nombre").val(""); 
  $("#num_documento").val(""); 
  $("#direccion").val(""); 
  $("#telefono").val("");  
  $("#c_bancaria").val("");  
  $("#c_detracciones").val("");  
  //$("#banco").val("");
  $("#banco option[value='BCP']").attr("selected", true);  
  $("#titular_cuenta").val("");   
  
}

//Función Listar
function listar() {

  tabla=$('#tabla-proveedores').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/all_proveedor.php?op=listar',
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
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);

  $.ajax({
    url: "../ajax/all_proveedor.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('proveedor registrado correctamente')				 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-proveedor").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idproveedor) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-proveedor").modal("show")

  $.post("../ajax/all_proveedor.php?op=mostrar", { idproveedor: idproveedor }, function (data, status) {

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
}

//Función para desactivar registros
function desactivar(idproveedor) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  el proveedor?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/all_proveedor.php?op=desactivar", { idproveedor: idproveedor }, function (e) {

        Swal.fire("Desactivado!", "Tu proveedor ha sido desactivado.", "success");
    
        tabla.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar(idproveedor) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  el proveedor?",
    text: "Este proveedor tendra acceso al sistema",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/all_proveedor.php?op=activar", { idproveedor: idproveedor }, function (e) {

        Swal.fire("Activado!", "Tu proveedor ha sido activado.", "success");

        tabla.ajax.reload();
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

  $("#form-proveedor").validate({
    rules: {
      tipo_documento: { required: true },
      num_documento: { required: true, minlength: 6, maxlength: 20 },
      nombre: { required: true, minlength: 6, maxlength: 100 },
      direccion: { minlength: 5, maxlength: 70 },
      telefono: { minlength: 8 },
      c_detracciones: { minlength: 14, maxlength: 14},
      c_bancaria: { minlength: 14, maxlength: 14},
      banco: { required: true},
      titular_cuenta: { minlength: 4},


      // terms: { required: true },
    },
    messages: {
      tipo_documento: {
        required: "Por favor selecione un tipo de documento", 
      },
      num_documento: {
        required: "Ingrese un número de documento",
        minlength: "El número documento debe tener MÍNIMO 6 caracteres.",
        maxlength: "El número documento debe tener como MÁXIMO 20 caracteres.",
      },
      nombre: {
        required: "Por favor ingrese los nombres y apellidos",
        minlength: "El número documento debe tener MÍNIMO 6 caracteres.",
        maxlength: "El número documento debe tener como MÁXIMO 100 caracteres.",
      },
      direccion: {
        minlength: "La dirección debe tener MÍNIMO 5 caracteres.",
        maxlength: "La dirección debe tener como MÁXIMO 70 caracteres.",
      },
      telefono: {
        minlength: "El teléfono debe tener  9 caracteres.",
      },
      c_detracciones: {
        minlength: "El número documento debe tener 14 caracteres.",
      },
      c_bancaria: {
        minlength: "El número documento debe tener 14 caracteres.",
      },
      banco: {
        required: "Por favor  seleccione un banco",
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