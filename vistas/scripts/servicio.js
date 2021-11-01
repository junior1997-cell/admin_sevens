var tabla;
var tabla2;
var tabla3;

//Función que se ejecuta al inicio
function init() {
  
  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));
 //var idproyecto =localStorage.getItem('nube_idproyecto');

 listar(localStorage.getItem('nube_idproyecto'));
 
  // $("#bloc_Accesos").addClass("menu-open");
  //Mostramos los maquinariaes
  $.post("../ajax/servicio.php?op=select2_servicio", function (r) { $("#maquinaria").html(r); });

  $("#mProveedor").addClass("active");

  // $("#lproveedor").addClass("active");

  $("#guardar_registro").on("click", function (e) {
    
    //console.log('holaaaaaa');
    $("#submit-form-servicios").submit();
  });

  // Formato para telefono
  $("[data-mask]").inputmask();

  //Initialize Select2 Elements
  $("#maquinaria").select2({
    theme: "bootstrap4",
    placeholder: "Selecione maquinaria",
    allowClear: true,
  });
   //Initialize Select2 Elements
   $("#unidad_m").select2({
    theme: "bootstrap4",
    placeholder: "Selecione una unidad de medida",
    allowClear: false,
  });
  
  $("#maquinaria").val("null").trigger("change");
  $("#unidad_m").val("Hora").trigger("change");

}

function seleccion() {

  if ($("#maquinaria").select2("val") == null) {

    $("#maquinaria_validar").show(); //console.log($("#maquinaria").select2("val") + ", "+ $("#maquinaria_old").val());

  } else {

    $("#maquinaria_validar").hide();
  }
}

function capture_unidad() {
    //Hora
  if ($("#unidad_m").select2("val") =="Hora") {

    $("#dias_head").hide();
    $("#meses_head").hide();
    $("#fecha_i").show();
    $("#fecha_f").hide();
    $("#horometro_i").show();
    $("#horometro_f").show();
    $("#costo_unit").show();
    $("#horas_head").show();

    $("#dias").val("");
    $("#mes").val("");
    $("#fecha_fin").val("");
    $("#fecha_inicio").val("");
    $("#fecha-i-tutulo").html("Fecha :");
   // $("#fecha_inicio").val("");

    //Dia
  } else if($("#unidad_m").select2("val")=="Dia"){

    $("#horas_head").hide();
    $("#dias_head").hide();
    $("#meses_head").hide();
    $("#fecha_i").show();
    $("#fecha_f").hide();
    $("#horometro_i").hide();
    $("#horometro_f").hide();
    $("#costo_unit").hide();
    $("#dias").hide();
    $("#costo_parcial").removeAttr("readonly");
    /**======= */
   // $("#fecha_inicio").val("");
    $("#fecha_fi").html("");
    $("#mes").val("");
    $("#dias").val("");
    $("#horas").val("");
    $("#fecha_fin").val("");
    $("#fecha_inicio").val("");
    $("#horometro_inicial").val("");
    $("#horometro_final").val("");
    $("#costo_unitario").val("");
    $("#costo_parcial").val("");
    $("#fecha-i-tutulo").val("");

    //Mes
  }else if($("#unidad_m").select2("val")=="Mes"){

    $("#horas_head").hide();
    $("#dias_head").hide();
    $("#meses_head").hide();
    $("#costo_unit").hide();
    $("#horometro_i").hide();
    $("#horometro_f").hide();
    $("#fecha_i").show();
    $("#fecha_f").show();
    $("#costo_parcial").removeAttr("readonly").focus().val();
    $("#fecha_fin").attr('readonly',true);
    /**======= */
    $("#fecha_fi").html("Fecha Fin :");
    $("#fecha_inicio").val("");
    $("#fecha_fin").val("");
    $("#dias").val("");
    $("#horas").val("");
    $("#mes").val("");
    $("#horometro_inicial").val("");
    $("#horometro_final").val("");
    $("#costo_unitario").val("");
    $("#horas").val("");
    $("#fecha-i-tutulo").val("");
  }
}

//Calculamos costo parcial.
function costo_partcial() {
  
  var horometro_inicial = $('#horometro_inicial').val();
  var horometro_final = $('#horometro_final').val();
  var costo_unitario = $('#costo_unitario').val();


  if (horometro_final!=0) {
    var horas=(horometro_final-horometro_inicial).toFixed(1);
    var costo_parcial=(horas*costo_unitario).toFixed(1);
  }else{
    var horas=(horometro_inicial-horometro_inicial).toFixed(1);
    var costo_parcial=costo_unitario
  }
  
  $("#horas").val(horas);
  $("#costo_parcial").val(costo_parcial);
}
function calculardia() {
   
  let dias = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado","Domingo"];
  let meses = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
  
  
  console.log(x);
  if($("#fecha_inicio").val().length > 0){ 
    var x = $("#fecha_inicio").val(); // día lunes
    var y = $("#fecha_inicio").val(); // día lunes
    //var x = document.getElementById("fecha");
    let date = new Date(x.replace(/-+/g, '/'));
    let date2 = new Date(y.replace(/-+/g, '/'));
  
    let options = {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    };
    console.log(date.toLocaleDateString('es-MX', options));
   //-----------
    date2.setMonth(date2.getMonth()+1);
    var fecha2=date2.getDate();
    var mes2= date2.getMonth()+1;


    
      
    $("#fecha-i-tutulo").html('Fecha: <b style="color: red;">'+date.toLocaleDateString('es-MX', options)+'</b>');

    $("#fecha_fi").html('Fecha Fin: <b style="color: red;">'+date2.toLocaleDateString('es-MX', options)+'</b>');
    $("#fecha_fin").val(fecha2+ "/" +mes2+ "/" +date2.getFullYear()); 
  }else{
    $("#fecha-i-tutulo").html('Fecha: <b style="color: red;"> - </b>'); 
  }
}
/*idservicio
maquinaria
fecha_inicio
fecha_fin
horometro_inicial
horometro_final
horas
costo_unitario
costo_parcial*/

//Función limpiar
function limpiar() {
  //Mostramos los proveedores
  $.post("../ajax/servicio.php?op=select2_servicio", function (r) { $("#maquinaria").html(r); });

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));
  $("#idservicio").val(""); 
  $("#maquinaria").val("null").trigger("change"); 
  $("#unidad_m").val("Hora").trigger("change"); 
  $("#fecha_inicio").val("");
  $("#fecha_fin").val("");
  $("#horometro_inicial").val("");
  $("#horometro_final").val("");
  $("#horas").val("");
  $("#dias").val(""); 
  $("#mes").val(""); 
  $("#costo_unitario").val("");
  $("#costo_parcial").val("");
  $("#costo_unitario").attr('readonly',false);
  $("#sssss").val("");
  $("#nomb_maq").val("");
  $("#descripcion").val("");
  $("#sssss").show();
  $("#nomb_maq").hide();

  
}

//Función Listar
function listar( nube_idproyecto ) {

  tabla=$('#tabla-servicio').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/servicio.php?op=listar&nube_idproyecto='+nube_idproyecto,
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

//Función detalles po maquina
function listar_detalle(idmaquinaria,idproyecto) {
  console.log(idproyecto,idmaquinaria);
  $("#tabla_principal").hide();
  $("#tabla_detalles").show();
  $("#btn-agregar").hide();
  $("#btn-regresar").show();
  $("#btn-pagar").hide();

  tabla2=$('#tabla-detalle-m').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/servicio.php?op=ver_detalle_maquina&idmaquinaria='+idmaquinaria+'&idproyecto='+idproyecto,
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
  suma_horas_costoparcial(idmaquinaria,localStorage.getItem('nube_idproyecto'));
  
}
//funcion añadir pago
function listar_pagos(idmaquinaria,idproyecto) {
  console.log('::::'+idmaquinaria,idproyecto);
  $("#tabla_principal").hide();
  $("#tabla_pagos").show();
  $("#btn-agregar").hide();
  $("#btn-regresar").show();
  $("#btn-pagar").show();

  tabla3=$('#tabla-pagos').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/servicio.php?op=ver_detalle_maquina&idmaquinaria='+idmaquinaria+'&idproyecto='+idproyecto,
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
  suma_horas_costoparcial(idmaquinaria,localStorage.getItem('nube_idproyecto'));
  mostrar_datos(idmaquinaria,idproyecto);
}
//Mostrar datos
function mostrar_datos_pago(idmaquinaria,idproyecto){
  console.log('qqqqqq  '+idmaquinaria,idproyecto);
  $.post("../ajax/servicio.php?op=mostrar", { idmaquinaria: idmaquinaria }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

  });
}
//regresar_principal
function regresar_principal(){
  $("#tabla_principal").show();
  $("#btn-agregar").show();
  $("#tabla_detalles").hide();
  $("#btn-regresar").hide();
  $("#tabla_pagos").hide();
  $("#btn-pagar").hide();
}
//Función para guardar o editar
function suma_horas_costoparcial(idmaquinaria,idproyecto){
  console.log('...'+idmaquinaria,idproyecto);
    //suma
    $.post("../ajax/servicio.php?op=suma_horas_costoparcial", { idmaquinaria:idmaquinaria,idproyecto:idproyecto }, function (data, status) {
     // $("#horas-total").html(""); 
      $("#costo-parcial").html("");
      data = JSON.parse(data); 
      console.log(data);
     // tabla.ajax.reload();
     // $("#horas-total").html(data.horas); 
      $("#costo-parcial").html(data.costo_parcial);
  
    });
}

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-servicios")[0]);
 
  $.ajax({
    url: "../ajax/servicio.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('servicio registrado correctamente')				 

        
	      tabla.ajax.reload();
        

        $("#modal-agregar-servicio").modal("hide");
       // console.log(tabla2);
        tabla2.ajax.reload();
        var idmaquinaria =$("#maquinaria").val();
        if (idmaquinaria!='') {
          suma_horas_costoparcial(idmaquinaria,localStorage.getItem('nube_idproyecto'));
        }
        limpiar();
			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idservicio) {
  console.log(idservicio);
  
  $("#maquinaria").val("").trigger("change"); 
  $("#unidad_m").val("").trigger("change"); 
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-servicio").modal("show");
  $("#sssss").hide();
  $("#nomb_maq").show();
  $("#costo_unitario").attr('readonly',true);
  $.post("../ajax/servicio.php?op=mostrar", { idservicio: idservicio }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();
    $("#nomb_maq").val(data.nombre_maquina+' - '+data.codigo_maquina+' --> '+data.razon_social);
    $("#idservicio").val(data.idservicio); 
    $("#maquinaria").val(data.idmaquinaria).trigger("change"); 
    $("#unidad_m").val(data.unidad_medida).trigger("change"); 
    $("#fecha_inicio").val(data.fecha_entrega); 
    $("#fecha_fin").val(data.fecha_recojo); 
    $("#horometro_inicial").val(data.horometro_inicial); 
    $("#horometro_final").val(data.horometro_final); 
    $("#horas").val(data.horas); 
    $("#dias").val(data.dias_uso); 
    $("#mes").val(data.meses_uso); 
    $("#costo_unitario").val(data.costo_unitario); 
    $("#costo_parcial").val(data.costo_parcial); 

  });
}

//Función para desactivar registros
function desactivar(idservicio,idmaquinaria) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  el servicio?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/servicio.php?op=desactivar", { idservicio: idservicio }, function (e) {

        Swal.fire("Desactivado!", "Servicio ha sido desactivado.", "success");
        suma_horas_costoparcial(idmaquinaria,localStorage.getItem('nube_idproyecto'));
        tabla.ajax.reload();
        tabla2.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar(idservicio,idmaquinaria) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  Servicio?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/servicio.php?op=activar", { idservicio: idservicio }, function (e) {

        Swal.fire("Activado!", "Servicio ha sido activado.", "success");
        suma_horas_costoparcial(idmaquinaria,localStorage.getItem('nube_idproyecto'));
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

      if ($("#maquinaria").select2("val") == null) {
        
        $("#maquinaria_validar").show(); //console.log($("#proveedor").select2("val") + ", "+ $("#proveedor_old").val());
        console.log('holaaa""2222');
      } else {

        $("#maquinaria_validar").hide();
       

        guardaryeditar(e);
      }
    },
  });

  $("#form-servicios").validate({
    rules: {
      maquinaria: { required: true },
      fecha_inicio:{ required: true },
      fecha_fin:{minlength: 1},
      horometro_inicial:{ required: true, minlength: 1},
      horometro_final:{minlength: 1},
      costo_unitario:{minlength: 1},
      unidad_m:{ required: true},
      descripcion:{ minlength: 1}


      // terms: { required: true },
    },
    messages: {
      maquinaria: {
        required: "Por favor selecione una maquina", 
      },

      fecha_inicio: {
        required: "Por favor ingrese fecha inicial", 
      },
      horometro_inicial: {
        required: "Por favor ingrese horometro inicial", 
      },
      unidad_m: {
        required: "Por favor seleccione un tipo de unidad", 
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

      if ($("#maquinaria").select2("val")== null) {
         
        $("#maquinaria_validar").show(); //console.log($("#maquinaria").select2("val") + ", "+ $("#maquinaria_old").val());

      } else {

        $("#maquinaria_validar").hide();
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
