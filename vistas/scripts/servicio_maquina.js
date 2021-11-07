var tabla;
var tabla2;
var tabla3;
var idmaquina;

//Función que se ejecuta al inicio
function init() {
  
  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));
 //var idproyecto =localStorage.getItem('nube_idproyecto');

 listar(localStorage.getItem('nube_idproyecto'));
 
  // $("#bloc_Accesos").addClass("menu-open");
  //Mostramos los maquinariaes
  $.post("../ajax/servicio_maquina.php?op=select2_servicio", function (r) { $("#maquinaria").html(r); });

  $("#mProveedor").addClass("active");

  // $("#lproveedor").addClass("active");
 //=====Guardar Servicio=============
  $("#guardar_registro").on("click", function (e) {
    
    //console.log('holaaaaaa');
    $("#submit-form-servicios").submit();
  });
  //=====Guardar pago=============
  $("#guardar_registro_pago").on("click", function (e) {
    
    console.log('holaaaaaa baby');
    $("#submit-form-pago").submit();
  });

  $("#foto1_i").click(function() { $('#foto1').trigger('click'); });
  $("#foto1").change(function(e) { addImage(e,$("#foto1").attr("id")) });

  // Formato para telefono
  $("[data-mask]").inputmask();
  //============SERVICIO================
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
  //============pagoo================
  //Initialize Select2 Elements
  $("#forma_pago").select2({
    theme: "bootstrap4",
    placeholder: "Selecione una forma de pago",
    allowClear: true,
  });
    //Initialize Select2 Elements
  $("#tipo_pago").select2({
    theme: "bootstrap4",
    placeholder: "Selecione un tipo de pago",
    allowClear: true,
  });
  //Initialize Select2 Elements
  $("#banco_pago").select2({
    theme: "bootstrap4",
    placeholder: "Selecione un banco",
    allowClear: true,
  });

  //============SERVICIO================
  $("#maquinaria").val("null").trigger("change");
  $("#unidad_m").val("Hora").trigger("change");
  //===============Pago============
  $("#forma_pago").val("null").trigger("change");
  $("#tipo_pago").val("null").trigger("change");
  $("#banco_pago").val("null").trigger("change");

}

/* PREVISUALIZAR LAS IMAGENES */
function addImage(e,id) {
  // colocamos cargando hasta que se vizualice
  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

	console.log(id);

	var file = e.target.files[0], imageType = /image.*/;
	
	if (e.target.files[0]) {

		var sizeByte = file.size;

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(imageType)){
			// return;
			toastr.error('Este tipo de ARCHIVO no esta permitido <br> elija formato: <b>.png .jpeg .jpg .webp etc... </b>');

        $("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");

		}else{

			if (sizekiloBytes <= 10240) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;

					$("#"+id+"_i").attr("src", result);

					$("#"+id+"_nombre").html(''+
						'<div class="row">'+
              '<div class="col-md-12">'+
              file.name +
              '</div>'+
              '<div class="col-md-12">'+
              '<button  class="btn btn-danger  btn-block" onclick="'+id+'_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>'+
              '</div>'+
            '</div>'+
					'');

					toastr.success('Imagen aceptada.')
				}

				reader.readAsDataURL(file);

			} else {

				toastr.warning('La imagen: '+file.name.toUpperCase()+' es muy pesada. Tamaño máximo 10mb')

				$("#"+id+"_i").attr("src", "../dist/img/default/img_error.png");

				$("#"+id).val("");
			}
		}

	}else{

		toastr.error('Seleccione una Imagen');


      $("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");
   
		$("#"+id+"_nombre").html("");
	}
}

function foto1_eliminar() {

	$("#foto1").val("");

	$("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");

	$("#foto1_nombre").html("");
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
    $("#fecha-i-tutulo").html('Fecha: <b style="color: red;"> - </b>'); 
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
    $("#fecha_fin").attr('readonly',true);
    /**======= */

    $("#fecha_fi").html("Fecha Fin :");
    $("#mes").val("");
    $("#dias").val("");
    $("#horas").val("");
    $("#fecha_fin").val("");
    $("#fecha_inicio").val("");
    $("#horometro_inicial").val("");
    $("#horometro_final").val("");
    $("#costo_unitario").val("");
    $("#costo_parcial").val("");
    $("#fecha-i-tutulo").html('Fecha: <b style="color: red;"> - </b>'); 
    //costo_partcial();

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

    $("#dias").val("");
    $("#horas").val("");
    $("#mes").val("");
    $("#horometro_inicial").val("");
    $("#horometro_final").val("");
    $("#costo_unitario").val("");
    $("#horas").val("");
    $("#fecha-i-tutulo").html('Fecha: <b style="color: red;"> - </b>'); 
   // costo_partcial();
    $("#fecha_inicio").val("");
    $("#fecha_fin").val("");
   
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

  if($("#fecha_inicio").val().length > 0){ 

    if ($("#unidad_m").select2("val") =="Hora" || $("#unidad_m").select2("val")=="Dia") {

      $("#fecha_fin").val("");
      $("#fecha_fi").html("Fecha final")
      var x = $("#fecha_inicio").val(); // día lunes
      //var x = document.getElementById("fecha");
      let date = new Date(x.replace(/-+/g, '/'));
    
      let options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      };
      $("#fecha-i-tutulo").html('Fecha: <b style="color: red;">'+date.toLocaleDateString('es-MX', options)+'</b>');

    } else {

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
      
      $("#fecha_fin").val(date2.getFullYear()+"-"+mes2+"-"+fecha2); 
      //console.log(fecha2+"-"+mes2+"-"+date2.getFullYear());
    }
  }else{
    $("#fecha-i-tutulo").html('Fecha: <b style="color: red;"> - </b>'); 
  }
}
//Función limpiar
function limpiar() {
  //Mostramos los proveedores
  $.post("../ajax/servicio_maquina.php?op=select2_servicio", function (r) { $("#maquinaria").html(r); });

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
//Función limpiar
function limpiar_c_pagos() {
  //==========PAGO SERVICIOS=====
  $("#forma_pago").val("");
  $("#tipo_pago").val("");
  $("#monto_pago").val("");
  $("#numero_op_pago").val("");
  $("#descripcion_pago").val("");
  $("#idpago_servicio").val("");
  $("#foto1_i").attr("src", "../dist/img/default/img_defecto.png");
	$("#foto1").val("");
	$("#foto1_actual").val("");  
  $("#foto1_nombre").html(""); 

}
//regresar_principal
function regresar_principal(){
  $("#tabla_principal").show();
  $("#btn-agregar").show();
  $("#tabla_detalles").hide();
  $("#btn-regresar").hide();
  $("#tabla_pagos").hide();
  $("#btn-pagar").hide();
  limpiar();
}
/**
 * ================================================
                  SECCION SERVICIOS 
  =================================================
 */
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
        url: '../ajax/servicio_maquina.php?op=listar&nube_idproyecto='+nube_idproyecto,
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
function listar_detalle(idmaquinaria,idproyecto,unidad_medida) {
  var hideen_colums;
  //console.log('lis_deta '+idproyecto,idmaquinaria,unidad_medida);
  $("#tabla_principal").hide();
  $("#tabla_detalles").show();
  $("#btn-agregar").hide();
  $("#btn-regresar").show();
  $("#btn-pagar").hide();
  if (unidad_medida=='Hora') {
    hideen_colums=[];
    
  }else{
    hideen_colums=[
      {
          "targets": [ 2 ],
          "visible": false,
          "searchable": false
      },
      {
          "targets": [ 3 ],
          "visible": false,
          "searchable": false
      },
      {
          "targets": [ 4 ],
          "visible": false,
          "searchable": false
      },
      {
          "targets": [ 5 ],
          "visible": false,
          "searchable": false
      }
  ]

  }
 // console.log(hideen_colums);
  tabla2=$('#tabla-detalle-m').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/servicio_maquina.php?op=ver_detalle_maquina&idmaquinaria='+idmaquinaria+'&idproyecto='+idproyecto,
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
    "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
    "columnDefs": hideen_colums
  }).DataTable();
  suma_horas_costoparcial(idmaquinaria,localStorage.getItem('nube_idproyecto'));
  
}
//Mostrar datos
function mostrar_datos_pago(idmaquinaria,idproyecto){
 // console.log('qqqqqq  '+idmaquinaria,idproyecto);
  $.post("../ajax/servicio_maquina.php?op=mostrar", { idmaquinaria: idmaquinaria }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   

  });
}
//Función para guardar o editar
function suma_horas_costoparcial(idmaquinaria,idproyecto){
  console.log('...'+idmaquinaria,idproyecto);
    //suma
    $.post("../ajax/servicio_maquina.php?op=suma_horas_costoparcial", { idmaquinaria:idmaquinaria,idproyecto:idproyecto }, function (data, status) {
     // $("#horas-total").html(""); 
      $("#costo-parcial").html("");
      data = JSON.parse(data); 
      console.log(data);
     // tabla.ajax.reload();
     // $("#horas-total").html(data.horas); 
      $("#costo-parcial").html(data.costo_parcial);
  
    });
}
//Guardar y editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-servicios")[0]);
 
  $.ajax({
    url: "../ajax/servicio_maquina.php?op=guardaryeditar",
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
//mostrar
function mostrar(idservicio) {
  console.log(idservicio);
  
  $("#maquinaria").val("").trigger("change"); 
  $("#unidad_m").val("").trigger("change"); 
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-servicio").modal("show");
  $("#sssss").hide();
  $("#nomb_maq").show();
  $("#costo_unitario").attr('readonly',false);
  $.post("../ajax/servicio_maquina.php?op=mostrar", { idservicio: idservicio }, function (data, status) {

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
    $("#descripcion").val(data.descripcion); 
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
      $.post("../ajax/servicio_maquina.php?op=desactivar", { idservicio: idservicio }, function (e) {

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
      $.post("../ajax/servicio_maquina.php?op=activar", { idservicio: idservicio }, function (e) {

        Swal.fire("Activado!", "Servicio ha sido activado.", "success");
        suma_horas_costoparcial(idmaquinaria,localStorage.getItem('nube_idproyecto'));
        tabla.ajax.reload();
        tabla2.ajax.reload();
      });
      
    }
  });      
}
/**
 * ================================================
            SECCION PAGOS SERVICIOS
  =================================================
 */
//Guardar y editar
function guardaryeditar_pago(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-servicios-pago")[0]);
 
  $.ajax({
    url: "../ajax/servicio_maquina.php?op=guardaryeditar_pago",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('servicio registrado correctamente')				 

        
	      tabla.ajax.reload();
        

        $("#modal-agregar-pago").modal("hide");
       // console.log(tabla2);
        //tabla2.ajax.reload();
        tabla3.ajax.reload();
        /**================================================== */
        total_pagos(localStorage.getItem('nubeidmaquinaria'),localStorage.getItem('nube_idproyecto'));
        limpiar_c_pagos();
			}else{

				toastr.error(datos)
			}
    },
  });
}
//Listar pagos.
function listar_pagos(idmaquinaria,idproyecto) {
  //console.log('::::->'+idmaquinaria,idproyecto);
  //most_datos_prov_pago(idmaquinaria);
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
        url: '../ajax/servicio_maquina.php?op=listar_pagos&idmaquinaria='+idmaquinaria+'&idproyecto='+idproyecto,
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
        }
       /* success:function(data){
          console.log(data);	
        },*/
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

  total_pagos(idmaquinaria,idproyecto);
  most_datos_prov_pago(idmaquinaria,idproyecto);
}
//-total Pagos
function total_pagos(idmaquinaria,idproyecto) {
  $.post("../ajax/servicio_maquina.php?op=suma_total_pagos", { idmaquinaria:idmaquinaria,idproyecto:idproyecto }, function (data, status) {
    $("#monto_total").html("00.0");
    data = JSON.parse(data); 
    console.log(data);
    $("#monto_total").html(data.total_monto);

  });
}
//mostrar datos proveedor pago
function most_datos_prov_pago(idmaquinaria,idproyecto) {
 // console.log(idmaquinaria,idproyecto);
  localStorage.setItem('nubeidmaquinaria',idmaquinaria );

  $("#h4_mostrar_beneficiario").html("");
  $("#id_maquinaria_pago").html("");
  $("#idproyecto_pago").val("");

  $("#banco_pago").val("").trigger("change"); 
  $.post("../ajax/servicio_maquina.php?op=most_datos_prov_pago", { idmaquinaria: idmaquinaria }, function (data, status) {

    data = JSON.parse(data);      console.log(data); 
    
    $("#idproyecto_pago").val(idproyecto);
    $("#id_maquinaria_pago").val(data.idmaquinaria);
    $("#maquinaria_pago").html(data.nombre);
    $("#beneficiario_pago").val(data.razon_social);
    $("#h4_mostrar_beneficiario").html(data.razon_social);
    $("#cuenta_destino_pago").val(data.cuenta_bancaria);
    $("#banco_pago").val(data.idbancos).trigger("change"); 
    $("#titular_cuenta_pago").val(data.titular_cuenta);

    
    /*
    $("#forma_pago").val(data.nombre);
    $("#tipo_pago").val(data.nombre);
    $("#fecha_pago").val(data.nombre);
    $("#monto_pago").val(data.nombre);
    $("#numero_op_pago").val(data.nombre);
    $("#descripcion_pago").val(data.nombre);*/

  });
  

}
//mostrar
function mostrar_pagos(idpago_servicio,id_maquinaria) {
  $("#h4_mostrar_beneficiario").html("");
  $("#id_maquinaria_pago").html("");
  $("#maquinaria_pago").html("");
  $("#idproyecto_pago").val("");
  $("#modal-agregar-pago").modal("show");
  $("#banco_pago").val("").trigger("change");   
  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");

  $.post("../ajax/servicio_maquina.php?op=mostrar_pagos", { idpago_servicio: idpago_servicio }, function (data, status) {

    data = JSON.parse(data);  console.log(data);   
      
    $("#idproyecto_pago").val(data.idproyecto);
    $("#id_maquinaria_pago").val(data.id_maquinaria);
    $("#maquinaria_pago").html(data.nombre_maquina);
    $("#beneficiario_pago").val(data.beneficiario);
    $("#h4_mostrar_beneficiario").html(data.beneficiario);
    $("#cuenta_destino_pago").val(data.cuenta_destino);
    $("#banco_pago").val(data.id_banco).trigger("change"); 
    $("#titular_cuenta_pago").val(data.titular_cuenta);   
    $("#forma_pago").val(data.forma_pago).trigger("change");
    $("#tipo_pago").val(data.tipo_pago).trigger("change");
    $("#fecha_pago").val(data.fecha_pago);
    $("#monto_pago").val(data.monto);
    $("#numero_op_pago").val(data.numero_operacion);
    $("#descripcion_pago").val(data.descripcion);
    $("#idpago_servicio").val(data.idpago_servicio);

    if (data.imagen != "") {

			$("#foto1_i").attr("src", "../dist/img/vauchers_pagos/" + data.imagen);

			$("#foto1_actual").val(data.imagen);
		}

  });
}
//Función para desactivar registros
function desactivar_pagos(idpago_servicio,idmaquinaria) {
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
      $.post("../ajax/servicio_maquina.php?op=desactivar_pagos", { idpago_servicio: idpago_servicio }, function (e) {

        Swal.fire("Desactivado!", "Servicio ha sido desactivado.", "success");
        suma_horas_costoparcial(idmaquinaria,localStorage.getItem('nube_idproyecto'));
        //Función para activar registros
        total_pagos(idmaquinaria,localStorage.getItem('nube_idproyecto'));   
        tabla.ajax.reload();
        tabla3.ajax.reload();
      });      
    }
  });  

}

function activar_pagos(idpago_servicio,idmaquinaria) {
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
      $.post("../ajax/servicio_maquina.php?op=activar_pagos", { idpago_servicio: idpago_servicio }, function (e) {

        Swal.fire("Activado!", "Servicio ha sido activado.", "success");
        suma_horas_costoparcial(idmaquinaria,localStorage.getItem('nube_idproyecto'));
        //Función para activar registros
        total_pagos(idmaquinaria,localStorage.getItem('nube_idproyecto')); 
        tabla.ajax.reload();
        tabla3.ajax.reload();
      });
      
    }
  }); 
 
}

function ver_modal_vaucher(imagen){
  $('#img-vaucher').attr("src", "");
  $('#modal-ver-vaucher').modal("show");
  $('#img-vaucher').attr("src", "../dist/img/vauchers_pagos/" +imagen);

  
 // $(".tooltip").hide();
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
      descripcion:{ minlength: 1},
      //=======SECCION PAGO=========
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
  /**=======pagos */
  $.validator.setDefaults({

    submitHandler: function (e) {

      guardaryeditar_pago(e);

    },
  });

  $("#form-servicios-pago").validate({
    rules: {
      //=======SECCION PAGO=========
      forma_pago:{required: true},
      tipo_pago:{required: true},
      banco_pago:{required: true},
      fecha_pago:{required: true},
      monto_pago:{required: true},
      numero_op_pago:{minlength: 1},
      descripcion_pago:{minlength: 1},
      titular_cuenta_pago:{minlength: 1},


      // terms: { required: true },
    },
    messages: {
      //====================
      forma_pago: {
        required: "Por favor selecione una forma de pago", 
      },
      tipo_pago: {
        required: "Por favor selecione un tipo de pago", 
      },
      banco_pago: {
        required: "Por favor selecione un banco", 
      },
      fecha_pago: {
        required: "Por favor ingresar una fecha", 
      },
      monto_pago: {
        required: "Por favor ingresar el monto a pagar", 
      }

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


  });});



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
