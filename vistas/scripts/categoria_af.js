var tabla_categorias_af;

//Función que se ejecuta al inicio
function init() {
  listar_c_insumos_af();

  //Guardar  
  $("#guardar_registro_categoria_af").on("click", function (e) {
    $("#submit-form-cateogrias-af").submit(); 
    console.log('kkkkkkkkkkkkkkkkkkkk');
  });

  // Formato para telefono
  $("[data-mask]").inputmask();
}
//Función limpiar 
function limpiar_c_af() {
  $("#idcategoria_insumos_af").val("");
  $("#nombre_categoria_af").val(""); 

  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
}

//Función listar_c_insumos_af 
function listar_c_insumos_af () {

  tabla_categorias_af=$('#tabla-categorias-af').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5','pdf'],
    "ajax":{
        url: '../ajax/categoria_af.php?op=listar_c_insumos_af',
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
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar

function guardaryeditar_c_insumos_af(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-categoria-af")[0]);
 
  $.ajax({
    url: "../ajax/categoria_af.php?op=guardaryeditar_c_insumos_af",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla_categorias_af.ajax.reload();
         
				limpiar_c_af();

        $("#modal-agregar-categorias-af").modal("hide");

			}else{

				toastr.error(datos);
			}
    },
  });
}

function mostrar_c_insumos_af (idcategoria_insumos_af ) {
  limpiar_c_af();
  console.log(idcategoria_insumos_af );

  $("#modal-agregar-categorias-af").modal("show")

  $.post("../ajax/categoria_af.php?op=mostrar", {idcategoria_insumos_af : idcategoria_insumos_af }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idcategoria_insumos_af").val(data.idcategoria_insumos_af );
    $("#nombre_categoria_af").val(data.nombre); 

  });

}


//Función para desactivar y eliminar registros
function eliminar_c_insumos_af(idcategoria_insumos_af ) {
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
    $.post("../ajax/categoria_af.php?op=desactivar", { idcategoria_insumos_af : idcategoria_insumos_af  }, function (e) {

      Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");

      tabla_categorias_af.ajax.reload();
    });  

  }else if (result.isDenied) {
   //op=eliminar
    $.post("../ajax/categoria_af.php?op=delete", { idcategoria_insumos_af : idcategoria_insumos_af  }, function (e) {

      Swal.fire("Eliminado!", "Tu registro ha sido Eliminado.", "success");

      tabla_categorias_af.ajax.reload();
    });  

  }

});
}


init();

$(function () {

  
  $.validator.setDefaults({

    submitHandler: function (e) {
      console.log('iiiiiiiiiiiiiiiiiiiiiiiiiii');
    guardaryeditar_c_insumos_af(e);
      
    },
  });

  $("#form-categoria-af").validate({
    rules: {    // terms: { required: true },
      nombre_categoria_af: { required: true }
    },
    messages: {

      nombre_categoria_af: {
        required: "Campo requerido", 
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

