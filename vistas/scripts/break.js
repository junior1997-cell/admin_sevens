var tabla;
var editando=false;
var editando2=false;
////////////////////////////
var array_class=[];
var array_datosPost=[];
var array_fi_ff=[];
var f1_reload=''; var f2_reload=''; var i_reload  = '';
var total_semanas=0;
var array_guardar_fi_ff = [];

//Función que se ejecuta al inicio
function init() {  

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar_botoness( localStorage.getItem('nube_idproyecto') );
  listar( localStorage.getItem('nube_idproyecto'));

  //Activamos el "aside"
  $("#bloc_Viaticos").addClass("menu-open");
  $("#mViatico").addClass("active");
  $("#sub_bloc_comidas").addClass("active");

  $("#lbreak").addClass("active");

  // $("#ltrabajador").addClass("active"); 
  //=====Guardar factura=============
  $("#guardar_registro_comprobaante").on("click", function (e) {$("#submit-form-comprobante").submit();});
  //Factura
  $("#foto2_i").click(function() { $('#foto2').trigger('click'); });
  $("#foto2").change(function(e) { addImage(e,$("#foto2").attr("id")) });
  //Initialize Select2 Elements
  $("#tipo_comprovante").select2({
    theme: "bootstrap4",
    placeholder: "Selecione tipo comprobante",
    allowClear: true,
  });
  $("#forma_pago").select2({
    theme: "bootstrap4",
    placeholder: "Selecione una forma de pago",
    allowClear: true,
  });

  //============SERVICIO================
  $("#tipo_comprovante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  // Formato para telefono
  $("[data-mask]").inputmask();
  
}
/* PREVISUALIZAR LAS IMAGENES */
function addImage(e,id) {
  // colocamos cargando hasta que se vizualice
  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

	console.log(id);

	var file = e.target.files[0], imageType = /application.*/;
	
	if (e.target.files[0]) {

		var sizeByte = file.size;

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (extrae_extencion(file.name)=='pdf' || extrae_extencion(file.name)=='jpeg'|| extrae_extencion(file.name)=='jpg'|| extrae_extencion(file.name)=='png'|| extrae_extencion(file.name)=='webp'){
      
			if (sizekiloBytes <= 10240) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;
          if (extrae_extencion(file.name) =='pdf') {
            $('#foto2_i').hide();
           $('#ver_pdf').html('<iframe src="'+result+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
          }else{
					$("#"+id+"_i").attr("src", result);
          $('#foto2_i').show();
          }

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

		}else{
      // return;
			toastr.error('Este tipo de ARCHIVO no esta permitido <br> elija formato: <b> .pdf .png .jpeg .jpg .webp etc... </b>');

      $("#"+id+"_i").attr("src", "../dist/img/default/img_defecto.png");

		}

	}else{

		toastr.error('Seleccione una Imagen');


      $("#"+id+"_i").attr("src", "../dist/img/default/img_defecto2.png");
   
		$("#"+id+"_nombre").html("");
	}
}

function foto2_eliminar() {

	$("#foto2").val("");
	$("#ver_pdf").html("");

	$("#foto2_i").attr("src", "../dist/img/default/img_defecto2.png");

	$("#foto2_nombre").html("");
  $('#foto2_i').show();
}

function mostrar_form_table(estados) {

  if (estados == 1 ) {
    $("#mostrar-tabla").show();

    $("#tabla-registro").hide();

    $("#card-regresar").hide();
    $("#card-editar").hide();
    $("#card-guardar").hide();

  } else {
    if (estados == 2) {
      $("#card-registrar").hide();
      $("#card-regresar").show();
      $("#card-editar").show();

      $("#mostrar-tabla").hide();
      $("#tabla-registro").show();
      
     // $("#detalle_asistencia").hide();
      
    } else {
      $("#card-registrar").hide();
      $("#card-regresar").show();
      $("#card-editar").hide();
      $("#card-guardar").hide();
      $("#tabla-asistencia-trab").hide();
      $("#ver_asistencia").hide();
      $("#detalle_asistencia").show();
      $("#tabla-comprobantes").hide();
      
    }
  }
}
function editarbreak() {
   // ocultamos los span
   $(".span-visible").hide();
   // mostramos los inputs
   $(".input-visible").show();
   $(".textarea-visible").attr("readonly", false);
 
   $("#card-editar").hide();
   $("#card-guardar").show();


  
}

//Función Listar
function listar(nube_idproyecto) {

  tabla=$('#tabla-resumen-break-semanal').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/break.php?op=listar_totales_semana&nube_idproyecto='+nube_idproyecto,
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
//Función Listar
function listar_botoness( nube_idproyecto ) {
  //array_fi_ff=[];
  //Listar semanas(botones)
  $.post("../ajax/break.php?op=listar_semana_botones", { nube_idproyecto: nube_idproyecto }, function (data, status) {

    data =JSON.parse(data); //console.log(data);

    // validamos la existencia de DATOS
    if (data) {

      var dia_regular = 0; var weekday_regular = extraer_dia_semana(data.fecha_inicio); var estado_regular = false;

      if (weekday_regular == "Domingo") { dia_regular = -1; } else { if (weekday_regular == "Lunes") { dia_regular = -2; } else { if (weekday_regular == "Martes") { dia_regular = -3; } else { if (weekday_regular == "Miercoles") { dia_regular = -4; } else { if (weekday_regular == "Jueves") { dia_regular = -5; } else { if (weekday_regular == "Viernes") { dia_regular = -6; } else { if (weekday_regular == "Sábado") { dia_regular = -7; } } } } } } }
      // console.log(data.fecha_inicio, dia_regular, weekday_regular);

          $('#Lista_breaks').html('');

          var fecha = format_d_m_a(data.fecha_inicio);  var fecha_f = ""; var fecha_i = ""; //data.fecha_inicio

          var cal_mes  = false; var i=0;  var cont=0;

          while (cal_mes == false) {
  
            cont = cont+1; fecha_i = fecha;

            if (estado_regular) {

              fecha_f = sumaFecha(6, fecha_i);

            } else {

              fecha_f = sumaFecha(7+dia_regular, fecha_i); estado_regular = true;
            }            

            let val_fecha_f = new Date( format_a_m_d(fecha_f) ); let val_fecha_proyecto = new Date(data.fecha_fin);
            
            // console.log(fecha_f + ' - '+data.fecha_fin);
            array_fi_ff.push({'fecha_in':format_a_m_d(fecha_i),'fecha_fi':format_a_m_d(fecha_f), 'num_semana':cont });
            //array_data_fi_ff.push()

            $('#Lista_breaks').append(` <button id="boton-${i}" type="button" class="mb-2 btn bg-gradient-info text-center" onclick="datos_semana('${fecha_i}', '${fecha_f}', '${i}', '${cont}');"><i class="far fa-calendar-alt"></i> Semana ${cont}<br>${fecha_i} // ${fecha_f}</button>`)
            
            if (val_fecha_f.getTime() >= val_fecha_proyecto.getTime()) { cal_mes = true; }else{ cal_mes = false;}

            fecha = sumaFecha(1,fecha_f);

            i++;

          } 
        
    } else {
      $('#Lista_breaks').html(`<div class="info-box shadow-lg w-px-600"> 
        <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span> 
        <div class="info-box-content"> 
          <span class="info-box-text">Alerta</span> 
          <span class="info-box-number">No has definido los bloques de fechas del proyecto. <br>Ingresa al ESCRITORIO y EDITA tu proyecto selecionado.</span> 
        </div> 
      </div>`);
    }
    console.log(array_fi_ff);
    //Listamos la tabla principal agrupos por semana
    //------------------------------------------------
   /*$.ajax({
      url: "../ajax/break.php?op=listar_totales_semana",
      type: "POST",
      data: {
        'array_fi_ff': JSON.stringify(array_fi_ff),
        'idproyecto': localStorage.getItem('nube_idproyecto'),
      },
      success: function (datos) {
        //Swal.fire("Desactivado!",datos, "success");
        console.log(datos);
        datos=JSON.parse(datos);
        // console.log(datos);
        tabla=$('#tabla-resumen-break-semanal').dataTable({
          "responsive": true,
          "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
          "aProcessing": true,//Activamos el procesamiento del datatables
          "aServerSide": true,//Paginación y filtrado realizados por el servidor
          dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
          buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
          data: datos,
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
      },
    });*/
    //console.log(fecha);
  });
}
//Función para guardar o editar

function guardaryeditar_semana_break() {
  $("#modal-cargando").modal("show");
  $.ajax({
    url: "../ajax/break.php?op=guardaryeditar",
    type: "POST",
    data: {
      'array_break': JSON.stringify(array_datosPost),
      'fechas_semanas_btn': JSON.stringify(array_guardar_fi_ff),
      'idproyecto': localStorage.getItem('nube_idproyecto'),
    },
    // contentType: false,
    // processData: false,
    success: function (datos) {
             
      if (datos == 'ok') {

        datos_semana( f1_reload, f2_reload , i_reload);
        listar( localStorage.getItem('nube_idproyecto'));
        
        $("#icono-respuesta").html(`<div class="swal2-icon swal2-success swal2-icon-show" style="display: flex;"> <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div> <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span> <div class="swal2-success-ring"></div> <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div> <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div> </div>  <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Correcto!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">Asistencia registrada correctamente</div> </div>` );

        // Swal.fire("Correcto!", "Asistencia registrada correctamente", "success");
        
       $(".progress-bar").addClass("bg-success"); $("#barra_progress").text("100% Completado!");
            
      }else{

            $("#icono-respuesta").html(`<div class="swal2-icon swal2-error swal2-icon-show" style="display: flex;"> <span class="swal2-x-mark"> <span class="swal2-x-mark-line-left"></span> <span class="swal2-x-mark-line-right"></span> </span> </div> <div  class="text-center"> <h2 class="swal2-title" id="swal2-title" >Error!</h2> <div id="swal2-content" class="swal2-html-container" style="display: block;">${datos}</div> </div>`);

            $(".progress-bar").addClass("bg-danger"); $("#barra_progress").text("100% Error!");

        // Swal.fire("Error!", datos, "error");
      }
    },
    xhr: function () {

      var xhr = new window.XMLHttpRequest();

      xhr.upload.addEventListener("progress", function (evt) {

        if (evt.lengthComputable) {

          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress").css({"width": percentComplete+'%'});

          $("#barra_progress").text(percentComplete.toFixed(2)+" %");

          if (percentComplete === 100) {

            setTimeout(l_m, 600);
          }
        }
      }, false);
      return xhr;
    }
  });
}
function l_m(){ $(".progress-bar").removeClass("progress-bar-striped")}

function cerrar_modal() {
  $("#modal-cargando").modal("hide");
  $(".progress-bar").removeClass("bg-success bg-danger");
  $(".progress-bar").addClass("progress-bar-striped");
}

////////////////////////////datos_semana////////////////////////////////////////////////
// listamos la data de una quincena selecionada
function datos_semana(f1, f2, i,cont) {
  console.log('i'+i);
  array_guardar_fi_ff=[];
  array_guardar_fi_ff.push({'fecha_in_btn':format_a_m_d(f1),'fecha_fi_btn':format_a_m_d(f2),'num_semana':cont});
console.log(array_guardar_fi_ff);
  var tabla_bloc_dia_1=''; var tabla_bloc_cantidad_2=''; var tabla_bloc_precio_3=''; var tabla_bloc_descripcion_4='';
  var tabla_bloc_semana='';
  f1_reload = f1; f2_reload = f2; i_reload  = i;

  $("#card-editar").show();
  $("#card-guardar").hide();  

  // vaciamos el array
  array_datosPost=[];

  // pintamos el botón
  pintar_boton_selecionado(i);

  //capturamos el id del proyecto.
  var nube_idproyect =localStorage.getItem('nube_idproyecto');  //console.log('Quicena: '+f1 + ' al ' +f2 + ' proyect-id: '+nube_idproyect);
  
  var fecha_inicial_semana = f1; var count_numero_dia =1;

  var dia_regular = 0;  var total_pago = 0;

  var weekday_regular = extraer_dia_semana(format_a_m_d(fecha_inicial_semana));
  //console.log(weekday_regular);
  // asignamos un numero para restar y llegar al dia DOMIGO
  if (weekday_regular == "Domingo") { dia_regular = -0; } else { if (weekday_regular == "Lunes") { dia_regular = -1; } else { if (weekday_regular == "Martes") { dia_regular = -2; } else { if (weekday_regular == "Miercoles") { dia_regular = -3; } else { if (weekday_regular == "Jueves") { dia_regular = -4; } else { if (weekday_regular == "Viernes") { dia_regular = -5; } else { if (weekday_regular == "Sábado") { dia_regular = -6; } } } } } } }

  var fecha_inicial_semana_regular = sumaFecha(dia_regular, fecha_inicial_semana);
  //Regulamos los días hasta el inicio del dia del inicio del proyecto
  for ( var j = 1; j<=dia_regular*-1; j++ ) {

    var weekday = extraer_dia_semana(format_a_m_d(fecha_inicial_semana_regular));

    
    tabla_bloc_dia_1 =`<td class="bg-color-b4bdbe47"> <b>${count_numero_dia}. ${weekday} : </b> ${fecha_inicial_semana_regular}</td>`;

    tabla_bloc_cantidad_2 =`<td class="bg-color-b4bdbe47"><span> - </span></td>`;

    tabla_bloc_precio_3 =`<td class="bg-color-b4bdbe47"><span> - </span></td>`;

    tabla_bloc_descripcion_4 =`<td class="bg-color-b4bdbe47"><textarea class="bg-color-b4bdbe47" cols="30" rows="1" readonly style="width: 430px;"></textarea></td>`;

    //fila
    tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>${tabla_bloc_dia_1}${tabla_bloc_cantidad_2}${tabla_bloc_precio_3}${tabla_bloc_descripcion_4}</tr>`);

    // aumentamos mas un dia hasta llegar al dia "dia_regular"
    fecha_inicial_semana_regular = sumaFecha(1,fecha_inicial_semana_regular);


    count_numero_dia++;
  }
  // ocultamos las tablas
  mostrar_form_table(2)

  $.post("../ajax/break.php?op=ver_datos_semana", {f1:format_a_m_d(f1),f2:format_a_m_d(f2),nube_idproyect:nube_idproyect}, function (data, status) {
        
    data =JSON.parse(data); console.log(data);   
     
      // existe alguna asistencia -------
      if (data.length!= 0) {

        var i;  var fecha = f1; //console.log("tiene data");

        for (i = 1; i <=7+dia_regular; i++) {

          var estado_fecha = false; var fecha_compra_encontrado = ""; var costo_parcial_encontrado=0; var descripcion_encontrado=''; var cantidad_encontrado=0; var idbreak="";

          // buscamos las fechas 
          for (let i = 0; i < data.length; i++) { 
            
            let split_f = data[i]['fecha_compra']; 
             
            let fecha_semana = new Date( format_a_m_d(fecha) ); let fecha_asistencia = new Date(split_f);
             
            if ( fecha_semana.getTime() == fecha_asistencia.getTime() ) { 

              total_pago = total_pago + parseFloat(data[i]['costo_parcial']);

              fecha_compra_encontrado = data[i]['fecha_compra'];
              costo_parcial_encontrado=data[i]['costo_parcial'];
              descripcion_encontrado=data[i]['descripcion'];
              cantidad_encontrado=data[i]['cantidad'];
              idbreak=data[i]['idbreak'];
              estado_fecha = true;                       
            }
          } //end for

          // imprimimos la fecha compra encontrada 
          if (estado_fecha) {

            var weekday = extraer_dia_semana(fecha_compra_encontrado); //console.log(weekday);

            if (weekday != 'Sábado') {

             //-------------------------------------------------------------
              tabla_bloc_dia_1 =  `<td> <b>${count_numero_dia}. ${weekday}:</b>  ${format_d_m_a(fecha_compra_encontrado)} <input type="hidden" class="fecha_compra_${count_numero_dia}" value="${fecha_compra_encontrado}"><input type="hidden" class="idbreak_${count_numero_dia}" value="${idbreak}"> <input type="hidden" class="dia_semana_${count_numero_dia}" value="${weekday}"> </td>`;

              tabla_bloc_cantidad_2 = `<td><span class="span-visible">${cantidad_encontrado}</span><input type="number" value="${cantidad_encontrado}" class="cantidad_compra_${count_numero_dia} hidden input-visible" onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();"></td>`;
  
              tabla_bloc_precio_3 =  `<td><span class="span-visible">${costo_parcial_encontrado}</span><input type="number" value="${costo_parcial_encontrado}" class="precio_compra_${count_numero_dia} hidden input-visible"  onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();" ></td>`;
  
              tabla_bloc_descripcion_4 = `<td><textarea cols="30" rows="1" readonly class="textarea-visible descripcion_compra_${count_numero_dia}" onkeyup="obtener_datos_semana();" value="${descripcion_encontrado}" style="width: 430px;">${descripcion_encontrado}</textarea></td>`;
  
              tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>${tabla_bloc_dia_1}${tabla_bloc_cantidad_2}${tabla_bloc_precio_3}${tabla_bloc_descripcion_4}</tr>`);
                //

            } else {

              tabla_bloc_dia_1 =`<td class="bg-color-b4bdbe47"> <b>${count_numero_dia}. ${weekday} : </b> ${format_d_m_a(fecha_compra_encontrado)}</td>`;

              tabla_bloc_cantidad_2 =`<td class="bg-color-b4bdbe47"><span> - </span></td>`;
          
              tabla_bloc_precio_3 =`<td class="bg-color-b4bdbe47"><span> - </span></td>`;
          
              tabla_bloc_descripcion_4 =`<td class="bg-color-b4bdbe47"><textarea class="bg-color-b4bdbe47" cols="30" rows="1" readonly style="width: 430px;"></textarea></td>`;
          
              //fila
              tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>
                    ${tabla_bloc_dia_1}
                    ${tabla_bloc_cantidad_2}
                    ${tabla_bloc_precio_3}
                    ${tabla_bloc_descripcion_4}
                </tr>`);
            }
             
          } else {

            var weekday = extraer_dia_semana(format_a_m_d(fecha)); //console.log(weekday);

            if (weekday != 'Sábado') {

                tabla_bloc_dia_1 =  `<td> <b>${count_numero_dia}. ${weekday}:</b>  ${fecha} <input type="hidden" class="fecha_compra_${count_numero_dia}" value="${format_a_m_d(fecha)}"> <input type="hidden" class="dia_semana_${count_numero_dia}" value="${weekday}"> </td>`;

                tabla_bloc_cantidad_2 = `<td><span class="span-visible">-</span><input type="number" value="" class=" cantidad_compra_${count_numero_dia} hidden input-visible" onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();"></td>`;
    
                tabla_bloc_precio_3 =  `<td><span class="span-visible">-</span><input type="number" value="" class=" precio_compra_${count_numero_dia} hidden input-visible"  onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();" ></td>`;
    
                tabla_bloc_descripcion_4 = `<td><textarea cols="30" rows="1" readonly class="textarea-visible descripcion_compra_${count_numero_dia}" onkeyup="obtener_datos_semana();" value="" style="width: 430px;"></textarea></td>`;
    
                tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>${tabla_bloc_dia_1}${tabla_bloc_cantidad_2}${tabla_bloc_precio_3}${tabla_bloc_descripcion_4}</tr>`);
                  //

            } else {

              tabla_bloc_dia_1 =`<td class="bg-color-b4bdbe47"> <b>${count_numero_dia}. ${weekday} : </b> ${fecha}</td>`;

              tabla_bloc_cantidad_2 =`<td class="bg-color-b4bdbe47"><span> - </span></td>`;
          
              tabla_bloc_precio_3 =`<td class="bg-color-b4bdbe47"><span> - </span></td>`;
          
              tabla_bloc_descripcion_4 =`<td class="bg-color-b4bdbe47"><textarea class="bg-color-b4bdbe47" cols="30" rows="1" readonly style="width: 430px;"></textarea></td>`;
          
              //fila
              tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>
                    ${tabla_bloc_dia_1}
                    ${tabla_bloc_cantidad_2}
                    ${tabla_bloc_precio_3}
                    ${tabla_bloc_descripcion_4}
                </tr>`);

            }
          }
          //aumentamos el número de días
          count_numero_dia++;
          // aumentamos mas un dia hasta llegar al dia 15
          fecha = sumaFecha(1,fecha);
        } //end for
      
      // no existe ninguna asistencia ------- 
      } else {

        var fecha = f1; //console.log("no ninguna fecha asistida");  

        for (i = 1; i <=7+dia_regular; i++) { 

          var weekday = extraer_dia_semana(format_a_m_d(fecha));

          if (weekday != 'Sábado') {

            tabla_bloc_dia_1 =  `<td> <b>${count_numero_dia}. ${weekday}:</b>  ${fecha} <input type="hidden" class="fecha_compra_${count_numero_dia}" value="${format_a_m_d(fecha)}"> <input type="hidden" class="dia_semana_${count_numero_dia}" value="${weekday}"> </td>`;

            tabla_bloc_cantidad_2 = `<td><span class="span-visible">-</span><input type="number" value="" class="cantidad_compra_${count_numero_dia} hidden input-visible" onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();"></td>`;

            tabla_bloc_precio_3 =  `<td><span class="span-visible">-</span><input type="number" value="" class="precio_compra_${count_numero_dia} hidden input-visible"  onkeyup="obtener_datos_semana();" onchange="obtener_datos_semana();" ></td>`;

            tabla_bloc_descripcion_4 = `<td><textarea cols="30" rows="1" readonly class="textarea-visible descripcion_compra_${count_numero_dia}" onkeyup="obtener_datos_semana();" value="" style="width: 430px;"></textarea></td>`;

            tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>${tabla_bloc_dia_1}${tabla_bloc_cantidad_2}${tabla_bloc_precio_3}${tabla_bloc_descripcion_4}</tr>`);
              //
            

          } else {
            
            tabla_bloc_dia_1 =`<td class="bg-color-b4bdbe47"> <b>${count_numero_dia}. ${weekday} : </b> ${fecha}</td>`;

            tabla_bloc_cantidad_2 =`<td class="bg-color-b4bdbe47"><span> - </span></td>`;
        
            tabla_bloc_precio_3 =`<td class="bg-color-b4bdbe47"><span> - </span></td>`;
        
            tabla_bloc_descripcion_4 =`<td class="bg-color-b4bdbe47"><textarea class="bg-color-b4bdbe47" cols="30" rows="1" readonly style="width: 430px;"></textarea></td>`;
        
            //fila
            tabla_bloc_semana = tabla_bloc_semana.concat(`<tr>
                  ${tabla_bloc_dia_1}
                  ${tabla_bloc_cantidad_2}
                  ${tabla_bloc_precio_3}
                  ${tabla_bloc_descripcion_4}
              </tr>`);


          }
          //contamos el número del día 
          count_numero_dia++;
          // aumentamos mas un dia hasta llegar al dia 15
          fecha = sumaFecha(1,fecha);
        } //end for
      }
      $("#monto_total").html(formato_miles(total_pago.toFixed(2)));
      $("#data_table_body").html(tabla_bloc_semana);

  }); //end post - ver_datos_semana

  $("#cargando-1-fomulario").show();
  $("#cargando-2-fomulario").hide();
  $('[data-toggle="tooltip"]').tooltip();  

  count_dias_asistidos = 0;  horas_nomr_total = 0;   horas_extr_total = 0;
}

function obtener_datos_semana () {

  var fecha_compra=''; var dia_semana=''; var cantidad_compra=0; var precio_compra=0;  var descripcion_compra=''; var monto_total=0; var idbreak="";

  array_datosPost=[];

  for (let j = 1; j <= 7; j++) {
    
    //console.log(j);
    fecha_compra       =  $(`.fecha_compra_${j}`).val();
    dia_semana         =  $(`.dia_semana_${j}`).val();
    cantidad_compra    =  $(`.cantidad_compra_${j}`).val();
    precio_compra      =  $(`.precio_compra_${j}`).val();
    descripcion_compra =  $(`.descripcion_compra_${j}`).val();
   

   

    if ($(`.idbreak_${j}`).val()!=undefined){idbreak=$(`.idbreak_${j}`).val();}

    if (cantidad_compra!=undefined) {

      if (precio_compra>=0 && precio_compra!='') {
        monto_total=monto_total+parseFloat(precio_compra);
      } else {
        monto_total += 0;
      }
      
      array_datosPost.push(
        {
          "fecha_compra":fecha_compra,
          "dia_semana":dia_semana,
          "cantidad_compra":cantidad_compra,
          "precio_compra":precio_compra,
          "descripcion_compra":descripcion_compra,
          "idbreak":idbreak
        }
      ); 
    }

  }
  console.log(array_datosPost);
  $("#monto_total").html(formato_miles(monto_total.toFixed(2)));
  //$("#monto_total").html('100.00');
  
}

//--------------------Comprobantes----------------------------------
function ocultar() {

  $("#regresar_aprincipal").show();
  $("#Lista_breaks").hide();
  $("#mostrar-tabla").hide();
  $("#tabla-registro").hide();
  $("#tabla-comprobantes").show();
  $("#guardar").show();

}

function regresar() {

  $("#regresar_aprincipal").hide();
  $("#Lista_breaks").show();
  $("#mostrar-tabla").show();
  $("#tabla-registro").hide();
  $("#tabla-comprobantes").hide();
  $("#guardar").hide();
}
//Función limpiar-factura
function limpiar_comprobante() {
  $("#nro_comprobante").val("");
  $("#monto").val("");
  $("#idfactura_break").val("");
  $("#fecha_emision").val("");
  $("#descripcion").val("");
  $("#subtotal").val("");
  $("#igv").val("");
  $("#tipo_comprovante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");
  $("#foto2_i").attr("src", "../dist/img/default/img_defecto2.png");
  $('#foto2_i').show();
  $('#ver_pdf').html('');
	$("#foto2").val("");
	$("#foto2_actual").val("");  
  $("#foto2_nombre").html(""); 

}
//Guardar y editar
function guardaryeditar_factura(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-agregar-comprobante")[0]);
 
  $.ajax({
    url: "../ajax/break.php?op=guardaryeditar_Comprobante",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('servicio registrado correctamente')				 

        tabla.ajax.reload();

        $("#modal-agregar-comprobante").modal("hide");
       listar_comprobantes(localStorage.getItem('idsemana_break_nube'))
       total_monto(localStorage.getItem('idsemana_break_nube'))
        limpiar_comprobante();
			}else{

				toastr.error(datos)
			}
    },
  });
}

function listar_comprobantes(idsemana_break) {
  localStorage.setItem('idsemana_break_nube',idsemana_break);

  ocultar();
  $("#idsemana_break").val(idsemana_break);

  
  tabla=$('#t-comprobantes').dataTable({  
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/break.php?op=listar_comprobantes&idsemana_break='+idsemana_break,
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
  total_monto(localStorage.getItem('idsemana_break_nube'));
}

function comprob_factura() {

  var monto = parseFloat($('#monto').val());

  if ($("#tipo_comprovante").select2("val") =="Factura") {

      var subtotal=0; var igv=0;

      $("#subtotal").val("");
      $("#igv").val(""); 

      subtotal= monto/1.18;
      igv= monto-subtotal;

      $("#subtotal").val(subtotal.toFixed(4));
      $("#igv").val(igv.toFixed(4));

  } else {

    $("#subtotal").val(monto);
    $("#igv").val("0.00");
  }
  
  
}

//mostrar
function mostrar_comprobante(idfactura_break) {

  $("#modal-agregar-comprobante").modal("show");
  $("#tipo_comprovante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/break.php?op=mostrar_comprobante", { idfactura_break: idfactura_break }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);   
      
    $("#idfactura_break ").val(data.idfactura_break );
    $("#nro_comprobante").val(data.nro_comprobante);
    $("#monto").val(data.monto);
    $("#fecha_emision").val(data.fecha_emision);
    $("#descripcion").val(data.descripcion);
    $("#subtotal").val(data.subtotal);
    $("#igv").val(data.igv);
    $("#tipo_comprovante").val(data.tipo_comprobante).trigger("change");
    $("#forma_pago").val(data.forma_de_pago).trigger("change");

    if (data.comprobante != "") {
      var comprobante = data.comprobante;

			$("#foto2_i").attr("src", "../dist/img/vauchers_pagos/" + data.comprobante);

      var extencion = comprobante.substr(comprobante.length - 3); // => "1"
      console.log(extencion);
        $('#ver_pdf').html('');
        $('#foto2_i').attr("src", "");

        if (extencion=='jpeg' || extencion=='jpg' || extencion=='png' || extencion=='webp') {
          $('#ver_pdf').hide();
          $('#foto2_i').show();
          $('#foto2_i').attr("src", "../dist/img/comrob_breaks/" +comprobante);

          $("#foto2_nombre").html(''+
          '<div class="row">'+
            '<div class="col-md-12">Factura</div>'+
            '<div class="col-md-12">'+
            '<button  class="btn btn-danger  btn-block" onclick="foto2_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>'+
            '</div>'+
          '</div>'+
        '');

        }else{
          $('#foto2_i').hide();
          $('#ver_pdf').show();
          $('#ver_pdf').html('<iframe src="../dist/img/comrob_breaks/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
          
          $("#foto2_nombre").html(''+
          '<div class="row">'+
            '<div class="col-md-12">Factura</div>'+
            '<div class="col-md-12">'+
            '<button  class="btn btn-danger  btn-block" onclick="foto2_eliminar();" style="padding:0px 12px 0px 12px !important;" type="button" ><i class="far fa-trash-alt"></i></button>'+
            '</div>'+
          '</div>'+
        '');

        }

			$("#foto2_actual").val(data.comprobante);
		}

  });
}
//Función para desactivar registros
function desactivar_comprobante(idfactura_break) {

  Swal.fire({
    title: "¿Está Seguro de  Desactivar  el comprobante?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/break.php?op=desactivar_comprobante", { idfactura_break: idfactura_break }, function (e) {

        Swal.fire("Desactivado!", "Comprobante a ha sido desactivado.", "success");
        total_monto(localStorage.getItem('idsemana_break_nube'));
        tabla.ajax.reload();
      });      
    }
  });  

}

function activar_comprobante(idfactura_break) {
  Swal.fire({
    title: "¿Está Seguro de  Activar  comprobante?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/break.php?op=activar_comprobante", { idfactura_break: idfactura_break }, function (e) {

        Swal.fire("Activado!", "Comprobante ha sido activado.", "success");
        total_monto(localStorage.getItem('idsemana_break_nube'));
        tabla.ajax.reload();
      });
      
    }
  }); 
 
}

function ver_modal_comprobante(comprobante){
  var comprobante = comprobante;
var extencion = comprobante.substr(comprobante.length - 3); // => "1"
//console.log(extencion);
  $('#ver_fact_pdf').html('');
  $('#img-factura').attr("src", "");
  $('#modal-ver-comprobante').modal("show");

  if (extencion=='jpeg' || extencion=='jpg' || extencion=='png' || extencion=='webp') {
    $('#ver_fact_pdf').hide();
    $('#img-factura').show();
    $('#img-factura').attr("src", "../dist/img/comrob_breaks/" +comprobante);

    $("#iddescargar").attr("href","../dist/img/comrob_breaks/" +comprobante);

  }else{
    $('#img-factura').hide();
    $('#ver_fact_pdf').show();
    $('#ver_fact_pdf').html('<iframe src="../dist/img/comrob_breaks/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');
    $("#iddescargar").attr("href","../dist/img/comrob_breaks/" +comprobante);
  } 
 // $(".tooltip").hide();
}

//-total Pagos
function total_monto(idsemana_break) {
  $.post("../ajax/break.php?op=total_monto", { idsemana_break:idsemana_break }, function (data, status) {
    $("#monto_total_f").html("00.0");
    data = JSON.parse(data); 
   console.log(data);
   num= data.total;
    if (!num || num == 'NaN') return '-';
    if (num == 'Infinity') return '&#x221e;';
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
        num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
        total_mont_f= (((sign) ? '' : '-') + num + '.' + cents);

    $("#monto_total_f").html(total_mont_f);

  });
}


init();

$(function () {

  
  $.validator.setDefaults({

    submitHandler: function (e) {
      guardaryeditar_factura(e)
      
    },
  });

  $("#form-agregar-comprobante").validate({
    rules: {
      forma_pago:{required: true},
      tipo_comprovante:{required: true},
      monto:{required: true},
      fecha_emision:{required: true},
      descripcion:{minlength: 1},
      foto2_i:{required: true}
  
      // terms: { required: true },
    },
    messages: {
      //====================
      forma_pago: {
        required: "Seleccionar una forma de pago", 
      },
      tipo_comprovante: {
        required: "Seleccionar un tipo de comprobante", 
      },
      monto: {
        required: "Por favor ingresar el monto", 
      },
      fecha_emision: {
        required: "Por favor ingresar la fecha de emisión", 
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


  });
});

// convierte de una fecha(aa-mm-dd): 2021-12-23 a una fecha(dd-mm-aa): 23-12-2021
function format_d_m_a(fecha) {

  let splits = fecha.split("-"); //console.log(splits);

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}
// convierte de una fecha(aa-mm-dd): 23-12-2021 a una fecha(dd-mm-aa): 2021-12-23
function format_a_m_d(fecha) {

  let splits = fecha.split("-"); //console.log(splits);

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}
//extraer_dia_semana
function extraer_dia_semana(fecha) {
  const fechaComoCadena = fecha; // día fecha
  const dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']; //
  const numeroDia = new Date(fechaComoCadena).getDay();
  const nombreDia = dias[numeroDia];
  return nombreDia;
}
function pintar_boton_selecionado(i) {
  localStorage.setItem('i', i); //enviamos el ID-BOTON al localStorage
  // validamos el id para pintar el boton
  if (localStorage.getItem('boton_id')) {

    let id = localStorage.getItem('boton_id'); //console.log('id-nube-boton '+id); 
    
    $("#boton-" + id).removeClass('click-boton');

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  } else {

    localStorage.setItem('boton_id', i);

    $("#boton-"+i).addClass('click-boton');
  }
}
//despintar_btn_select
function despintar_btn_select() {  
  if (localStorage.getItem('boton_id')) { let id = localStorage.getItem('boton_id'); $("#boton-" + id).removeClass('click-boton'); }
}
//coma por miles
function formato_miles(num) {
  if (!num || num == "NaN") return "-";
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


function extrae_extencion(filename) {
  return filename.split('.').pop();
}


