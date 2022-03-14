var tabla;
var tabla2;

//Función que se ejecuta al inicio
function init() {
  listar();
  listar2();

    //Mostramos los proveedores
    $.post("../ajax/all_maquinaria.php?op=select2_proveedor", function (r) { $("#proveedor").html(r); });

  $("#bloc_Recurso").addClass("menu-open");

  $("#mRecurso").addClass("active");

   $("#lAllMaquinas").addClass("active");

  $("#guardar_registro").on("click", function (e) {

    $("#submit-form-maquinaria").submit();
  });

  // Formato para telefono
  $("[data-mask]").inputmask();
  
  //Initialize Select2 Elements
  $("#proveedor").select2({
    theme: "bootstrap4",
    placeholder: "Selecione proveedor",
    allowClear: true,
  });
  $("#tipo").select2({
    theme: "bootstrap4",
    placeholder: "Selecione tipo",
    allowClear: true,
  });

  $("#proveedor").val("null").trigger("change");
  $("#tipo").val("null").trigger("change");

}
//Función limpiar
function limpiar() {
  $("#idmaquinaria").val("");
  $("#nombre_maquina").val(""); 
  $("#codigo_m").val(""); 
  $("#proveedor").val("null").trigger("change");
  $("#tipo").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
  
}

//Función Listar
function listar() {

  tabla=$('#tabla-maquinas').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/all_maquinaria.php?op=listar_maquinas',
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
        }
      },      
      createdRow: function (row, data, ixdex) {    
  
        // columna: #
        if (data[0] != '') {
          $("td", row).eq(0).addClass("text-center");   
           
        }
        // columna: #
        if (data[1] != '') {
          $("td", row).eq(1).addClass("text-nowrap");   
            
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
    "iDisplayLength": 10,//Paginación
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}
//Función Listar22222
function listar2() {

  tabla2=$('#tabla-equipos').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/all_maquinaria.php?op=listar_equipos',
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
        }
      },
      createdRow: function (row, data, ixdex) {    
  
        // columna: #
        if (data[0] != '') {
          $("td", row).eq(0).addClass("text-center");   
           
        }
        // columna: #1
        if (data[1] != '') {
          $("td", row).eq(1).addClass("text-center");   
            
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
    "iDisplayLength": 10,//Paginación
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-maquinaria")[0]);

  $.ajax({
    url: "../ajax/all_maquinaria.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla.ajax.reload();
	      tabla2.ajax.reload();
         
				limpiar();

        $("#modal-agregar-maquinaria").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idmaquinaria) {
  listar();  
  $("#proveedor").val("").trigger("change"); 
  $("#tipo").val("").trigger("change"); 

  $("#modal-agregar-maquinaria").modal("show")

  $.post("../ajax/all_maquinaria.php?op=mostrar", { idmaquinaria: idmaquinaria }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $("#proveedor").val(data.idproveedor).trigger("change"); 
    $("#tipo").val(data.tipo).trigger("change"); 
    $("#idmaquinaria").val(data.idmaquinaria);
    $("#nombre_maquina").val(data.nombre); 
    $("#codigo_m").val(data.modelo);

  });
}

//Función para desactivar registros
function desactivar(idmaquinaria) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar Máquina o Equipo?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/all_maquinaria.php?op=desactivar", { idmaquinaria: idmaquinaria }, function (e) {

        Swal.fire("Desactivado!", "Tu máquinas o equipo ha sido desactivada.", "success");
    
        tabla.ajax.reload();
	      tabla2.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar(idmaquinaria) {
  Swal.fire({
    title: "¿Está Seguro de  Activar Máquina o Equipo?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/all_maquinaria.php?op=activar", { idmaquinaria: idmaquinaria }, function (e) {

        Swal.fire("Activado!", "Tu máquinas o equipo ha sido activada.", "success");

        tabla.ajax.reload();
	      tabla2.ajax.reload();
      });
      
    }
  });      
}

//Función para desactivar registros
function eliminar(idmaquinaria) {
   //----------------------------
 Swal.fire({

  title: "!Elija una opción¡",
  html: "En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!",
  icon: "warning",
  showCancelButton: true,
  showDenyButton: true,
  confirmButtonColor: "#17a2b8",
  denyButtonColor: "#d33",
  cancelButtonColor: "#6c757d",    
  confirmButtonText: `<i class="fas fa-times"></i> Papelera`,
  denyButtonText: `<i class="fas fa-skull-crossbones"></i> Eliminar`,

}).then((result) => {

  if (result.isConfirmed) {
   //op=desactivar
    $.post("../ajax/all_maquinaria.php?op=desactivar", { idmaquinaria: idmaquinaria }, function (e) {

      Swal.fire("Desactivado!", "Tu máquinas o equipo ha sido desactivada.", "success");

      tabla.ajax.reload();
      tabla2.ajax.reload();
    });   

  }else if (result.isDenied) {
   //op=eliminar
    $.post("../ajax/all_maquinaria.php?op=eliminar", { idmaquinaria: idmaquinaria }, function (e) {

      Swal.fire("Eliminado!", "Tu máquinas o equipo ha sido eliminado.", "success");

      tabla.ajax.reload();
      tabla2.ajax.reload();
    });  


  }

});  
}

init();

$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {

        guardaryeditar(e);
    }
  });

  $("#form-maquinaria").validate({
    rules: {
      nombre_maquina: { required: true },
      proveedor: { required: true },
      tipo: { required: true }
      // terms: { required: true },
    },
    messages: {
      nombre_maquina: {
        required: "Por favor ingrese un nombre", 
      },
      proveedor: {
        required: "Por favor selecione un proveedor", 
      },
      tipo: {
        required: "Por favor selecione máquina o equipo", 
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
