var tabla;
var tabla_pension;
var tabla_detalle_s;
var editando=false;
var editando2=false;
////////////////////////////
var array_class=[];

var array_datosPost=[];
var array_fi_ff=[];
var f1_reload=''; var f2_reload=''; var i_reload  = ''; var cont_reload  = '';
var total_semanas=0;
var array_guardar_fi_ff = [];

var fecha_inicial_1="";
var fecha_inicial_2="";
var id_pension="";
var i_inicial="";
var cont_inial="";

//Función que se ejecuta al inicio
function init() {  

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Viaticos").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mViatico").addClass("active bg-primary");

  $("#sub_bloc_comidas").addClass("menu-open");

  $("#sub_mComidas").addClass("active bg-primary");

  $("#lPension").addClass("active");

  $("#idproyecto_p").val(localStorage.getItem('nube_idproyecto'));

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar_botoness( localStorage.getItem('nube_idproyecto') );

  listar( localStorage.getItem('nube_idproyecto'));

 

  //Mostramos los proveedor
  $.post("../ajax/pension.php?op=select_proveedor", function (r) { $("#proveedor").html(r); });
    
  //=====Guardar pension=============
  $("#guardar_registro_pension").on("click", function (e) {$("#submit-form-pension").submit();});

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
  
  //pension agregar 
  $("#proveedor").select2({
    theme: "bootstrap4",
    placeholder: "Seleccionar",
    allowClear: true,
  });

  $("#servicio_p").select2();

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
    $("#nomb_pension_head").html("");
    $("#mostrar-tabla").show();
    $("#guardar_pension").show();

    $("#tabla-registro").hide();
    $("#List_smnas_pen").hide();

    $("#card-regresar").hide();
    $("#card-editar").hide();
    $("#card-guardar").hide();

  } else {
    if (estados == 2) {
      $("#card-registrar").hide();
      $("#card-regresar").show();
      $("#card-editar").show();

      $("#List_smnas_pen").show();

      $("#guardar_pension").hide();
      $("#mostrar-tabla").hide();
      $("#tabla-registro").show();
      
     // $("#detalle_asistencia").hide();
      
    } else {
     // $("#List_smnas_pen").hide(); 

      $("#card-registrar").hide();
      $("#card-regresar").show();
      $("#card-editar").hide();
      $("#card-guardar").hide();
      $("#tabla-asistencia-trab").hide();
      $("#tabla-registro").hide();
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
  var estado_fecha_1 = true;
  //array_fi_ff=[];
  //Listar semanas(botones)
  $.post("../ajax/pension.php?op=listar_semana_botones", { nube_idproyecto: nube_idproyecto }, function (data, status) {

    data =JSON.parse(data); //console.log(data);

    // validamos la existencia de DATOS
    if (data) {

      var dia_regular = 0; var weekday_regular = extraer_dia_semana(data.fecha_inicio); var estado_regular = false;

      if (weekday_regular == "Do") { dia_regular = -1; } else { if (weekday_regular == "Lu") { dia_regular = -2; } else { if (weekday_regular == "Ma") { dia_regular = -3; } else { if (weekday_regular == "Mi") { dia_regular = -4; } else { if (weekday_regular == "Ju") { dia_regular = -5; } else { if (weekday_regular == "Vi") { dia_regular = -6; } else { if (weekday_regular == "Sa") { dia_regular = -7; } } } } } } }
      // console.log(data.fecha_inicio, dia_regular, weekday_regular);

          $('#List_smnas_pen').html('');

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

            //asignamos los datos del primer boton
            if (estado_fecha_1) { fecha_inicial_1=fecha_i; fecha_inicial_2=fecha_f;  i_inicial=i;  cont_inial=cont; estado_fecha_1=false;}

            $('#List_smnas_pen').append(` <button id="boton-${i}" type="button" class="mb-2 btn bg-gradient-info text-center" onclick="datos_semana('${fecha_i}', '${fecha_f}', '${i}', '${cont}');"><i class="far fa-calendar-alt"></i> Semana ${cont}<br>${fecha_i} // ${fecha_f}</button>`)
            
            if (val_fecha_f.getTime() >= val_fecha_proyecto.getTime()) { cal_mes = true; }else{ cal_mes = false;}

            fecha = sumaFecha(1,fecha_f);

            i++;

          } 
        
    } else {
      $('#List_smnas_pen').html(`<div class="info-box shadow-lg w-px-600"> 
        <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span> 
        <div class="info-box-content"> 
          <span class="info-box-text">Alerta</span> 
          <span class="info-box-number">No has definido los bloques de fechas del proyecto. <br>Ingresa al ESCRITORIO y EDITA tu proyecto selecionado.</span> 
        </div> 
      </div>`);
    }
    //console.log(array_fi_ff);
  });
}
//funcion para ingresar la fecha para rellenar los días de las pensiones
function ingresar_a_pension(idpension,idproyecto,razon_social) {
  $("#nomb_pension_head").html(razon_social);
  id_pension=idpension;
  mostrar_form_table(2);
  
  datos_semana(fecha_inicial_1, fecha_inicial_2, i_inicial, cont_inial, id_pen=id_pension)
}
//Función para guardar o editar

function guardaryeditar_semana_pension() {
  $("#modal-cargando").modal("show");
  var array_detalle_pen= [];
  var array_semana_pen= [];
  array_class.forEach(element => {
    var precio_x_comida =parseFloat($(`.input_precio_${element.idservicio_pension}`).val())*parseFloat($(`.input_dia_${element.idservicio_pension}_${element.fecha_asist}`).val());
    //value.adicional_descuento=='' ? adicional_descuento='0.00' : adicional_descuento=value.adicional_descuento;
    array_detalle_pen.push(
      {
        "iddetalle_pension" :element.iddetalle_pension,
        "idservicio_pension" :element.idservicio_pension,
        "fecha_pension":element.fecha_asist,
        "dia_semana":extraer_dia_semana(element.fecha_asist),
        "cantidad_platos":$(`.input_dia_${element.idservicio_pension}_${element.fecha_asist}`).val(),
        "precio_plato":$(`.input_precio_${element.idservicio_pension}`).val()=='' ?'0.00' : $(`.input_precio_${element.idservicio_pension}`).val(),
        "precio_parcial":precio_x_comida.toFixed(2)=='' ?'0.00' : precio_x_comida.toFixed(2),
      }
    );
  });
  
  array_servicio.forEach(element => {
   
    array_semana_pen.push({
      "idsemana_pension":element.idsemana_pension,
      "idservicio_pension":element.idservicio_pension,
      "fecha_inicio":format_a_m_d(f1_reload),
      "fecha_fin":format_a_m_d(f2_reload),
      "numero_semana":cont_reload,
      "precio_comida":$(`.input_precio_${element.idservicio_pension}`).val()=='' ?'0.00' : $(`.input_precio_${element.idservicio_pension}`).val(),
      "cantidad_total_platos":$(`.span_cantidad_${element.idservicio_pension}`).text(),
      "adicional_descuento":$(`.input_adicional_${element.idservicio_pension}`).val()=='' ?'0.00' : $(`.input_adicional_${element.idservicio_pension}`).val(),
      "total":$(`.span_parcial_${element.idservicio_pension}`).text()=='' ?'0.00' : quitar_formato_miles($(`.span_parcial_${element.idservicio_pension}`).text()),
      "descripcion":$(`.textarea_descrip_${element.idservicio_pension}`).val(),

    });
  });

  $.ajax({
    url: "../ajax/pension.php?op=guardaryeditar",
    type: "POST",
    data: {
      'array_detalle_pen': JSON.stringify(array_detalle_pen),
      'array_semana_pen': JSON.stringify(array_semana_pen),
    },
    // contentType: false,
    // processData: false,
    success: function (datos) {
             
      if (datos == 'ok') {

        datos_semana( f1_reload, f2_reload ,cont_reload, i_reload,id_pen=id_pension);
       
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
function datos_semana(f1, f2, i, cont,id_pen=id_pension) {

   f1_reload=f1;  f2_reload=f2;  i_reload  = i;  cont_reload  = cont;

  // ocultamos las tablas
  mostrar_form_table(2);
  $("#tabla-registro").hide();
  $('#cargando-registro-pension').show();

  $("#card-editar").show(); $("#card-guardar").hide();  

  // vaciamos el array
  array_class = []; array_servicio = []

  // pintamos el botón
  pintar_boton_selecionado(i);

  var nube_idproyect =localStorage.getItem('nube_idproyecto');  //console.log('Quicena: '+f1 + ' al ' +f2 + ' proyect-id: '+nube_idproyect);
  
  var fecha_inicial_semana = f1; var table_numero_semana = ""; var table_dia_semana = ""; 

  var dia_regular = 0; var total_pago = 0;

  var weekday_regular = extraer_dia_semana(format_a_m_d(fecha_inicial_semana));
  
  // asignamos un numero para restar y llegar al dia DOMIGO
  if (weekday_regular == "Do") { dia_regular = -0; } else { if (weekday_regular == "Lu") { dia_regular = -1; } else { if (weekday_regular == "Ma") { dia_regular = -2; } else { if (weekday_regular == "Mi") { dia_regular = -3; } else { if (weekday_regular == "Ju") { dia_regular = -4; } else { if (weekday_regular == "Vi") { dia_regular = -5; } else { if (weekday_regular == "Sa") { dia_regular = -6; } } } } } } }

  var fecha_inicial_semana_regular = sumaFecha(dia_regular, fecha_inicial_semana);

  // Asignamos: dia semana, numero semana. Para regular la semana
  for ( var j = 1; j<=dia_regular*-1; j++ ) {

    var weekday = extraer_dia_semana(format_a_m_d(fecha_inicial_semana_regular));  
    //<th class="text-center clas_pading"> ${weekday} <br> ${fecha_inicial_semana_regular.substr(0,2)} </th>
    table_dia_semana = table_dia_semana.concat(`<th class="text-center clas_pading bg-color-408c98"> ${weekday} <br> ${fecha_inicial_semana_regular.substr(0,2)} </th>`);

    // table_numero_semana = table_numero_semana.concat(`<th class="p-x-10px bg-color-acc3c7"> ${count_dias_de_asistencias} </th>`);

    // aumentamos mas un dia hasta llegar al dia "dia_regular"
    fecha_inicial_semana_regular = sumaFecha(1,fecha_inicial_semana_regular); //console.log(count_dias_de_asistencias);

    //count_dias_de_asistencias++;
  }

  // asignamos: dia semana, numero semana. Con respecto al trabajo
  for (i = 1; i <=7 + dia_regular; i++) { 

    var weekday = extraer_dia_semana(format_a_m_d(fecha_inicial_semana));  

    if (weekday != 'sa') {
      //`
      table_dia_semana = table_dia_semana.concat(`<th class="text-center clas_pading "> ${weekday} <br> ${fecha_inicial_semana.substr(0,2)} </th>`);

      // table_numero_semana = table_numero_semana.concat(`<th class="p-x-10px"> ${count_dias_de_asistencias} </th>`);

    } else {

      table_dia_semana = table_dia_semana.concat(`<th class="text-center clas_pading"> ${weekday} <br> ${fecha_inicial_semana.substr(0,2)} </th>`);
      
      // table_numero_semana = table_numero_semana.concat(`<td class="p-x-10px bg-color-acc3c7"> ${count_dias_de_asistencias} </td>`);
    }

    // aumentamos mas un dia hasta llegar al dia 15
    fecha_inicial_semana = sumaFecha(1,fecha_inicial_semana); //console.log(count_dias_de_asistencias);
    //count_dias_de_asistencias++
  } //end for

  $('#bloque_fechas').html(table_dia_semana);

  // $('.data-numero-semana').html(table_numero_semana
  var total_monto_x_semana=0;
  $.post("../ajax/pension.php?op=ver_datos_semana", {f1:format_a_m_d(f1),f2:format_a_m_d(f2),nube_idproyect:nube_idproyect,id_pen:id_pen}, function (data, status) {
        
    data =JSON.parse(data); //console.log(data);   
    console.log(data);
    $("#data_table_body").html('');   
     
    $.each(data, function (index, value) {
      if (value.total!='') {

        total_monto_x_semana+=parseFloat(value.total);

      } else {

        total_monto_x_semana='0.00';
      }
      
      count_bloque_q_s = 1;
      var count_dias_asistidos = 0; var platos_x_servicio = 0; var horas_nomr_total = 0; var horas_extr_total = 0; var sabatical = 0;
      
      var tabla_bloc_dia_3=""; var tabla_bloc_HE_asistencia_2 =""; var estado_hallando_sabado = true;

      // existe algun dia_q_comieron -------
      if (value.dias_q_comieron.length != 0) {

        var i;  var fecha = f1; //console.log("tiene data");
        
        // renellamos hasta el dia inicial
        for ( var j = 1; j<=dia_regular*-1; j++ ) {
          
          tabla_bloc_dia_3 = tabla_bloc_dia_3.concat(`<td class="text-center bg-color-acc3c7"> <span class="span_asist" >-</span> </td>`);
          
          //console.log(count_bloque_q_s);
          count_bloque_q_s++; 
        }

        for (i = 1; i <=7+dia_regular; i++) {
          //console.log('i');
          console.log(i); 
          var estado_fecha = false; var fecha_asist = "";  var platos_x_dia=0; var iddetalle_pension='';

          // buscamos las fechas asistidas
          for (let i = 0; i < value.dias_q_comieron.length; i++) {
            
            
            let split_f = format_d_m_a( value.dias_q_comieron[i]['fecha_pension'] ) ; 
             
            let fecha_semana = new Date( format_a_m_d(fecha) ); let fecha_pension = new Date(format_a_m_d(split_f));
             
            if ( fecha_semana.getTime() == fecha_pension.getTime() ) { 

              platos_x_servicio = platos_x_servicio + parseFloat(value.dias_q_comieron[i]['cantidad_platos']);

              estado_fecha = true; fecha_asist = value.dias_q_comieron[i]['fecha_pension'];  
              
              platos_x_dia = value.dias_q_comieron[i]['cantidad_platos'];

              iddetalle_pension = value.dias_q_comieron[i]['iddetalle_pension'];

              count_dias_asistidos++;                          
            }
          } //end for

          // imprimimos la fecha de asistencia: "encontrada" 
          if (estado_fecha) {

            var weekday = extraer_dia_semana(fecha_asist); //console.log(weekday);

              tabla_bloc_dia_3 = tabla_bloc_dia_3.concat(`<td> <span class="text-center span-visible">${platos_x_dia}</span> <input type="number" value="${platos_x_dia}" class="hidden input-visible w-px-45 input_dia_${value.idservicio_pension}_${i} input_dia_${value.idservicio_pension}_${fecha_asist}" onchange="calcular_platos(${value.idservicio_pension},'${fecha_asist}',${data.length})" onkeyup="calcular_platos(${value.idservicio_pension},'${fecha_asist}',${data.length})"> </td>`);
              
              array_class.push( { 
                'idservicio_pension':value.idservicio_pension, 
                'iddetalle_pension':iddetalle_pension,
                'fecha_asist':fecha_asist,

              } );

          } else { // imprimimos la fecha de asistencia: "No encontrada"

            var weekday = extraer_dia_semana(format_a_m_d(fecha)); //console.log(weekday);

            tabla_bloc_dia_3 = tabla_bloc_dia_3.concat(`<td> <span class="text-center span-visible">-</span> <input type="number" value="" class="hidden input-visible w-px-45 input_dia_${value.idservicio_pension}_${i} input_dia_${value.idservicio_pension}_${format_a_m_d(fecha)}" onchange="calcular_platos(${value.idservicio_pension},'${format_a_m_d(fecha)}',${data.length})" onkeyup="calcular_platos(${value.idservicio_pension},'${format_a_m_d(fecha)}',${data.length})"> </td>`);

              array_class.push( { 
                'idservicio_pension':value.idservicio_pension, 
                'iddetalle_pension':'',
                'fecha_asist':format_a_m_d(fecha),

              } );

          }

          // aumentamos mas un dia hasta llegar al dia 15
          fecha = sumaFecha(1,fecha);

          // console.log(count_bloque_q_s);
          count_bloque_q_s++; 
        } //end for
        // console.log('-----------------------------------------------------------');
      // no existe ninguna asistencia -------  
      } else {

        var fecha = f1; //console.log("no ninguna fecha asistida");  

        // renellamos hasta el dia inicial
        for ( var j = 1; j<=dia_regular*-1; j++ ) {

          tabla_bloc_dia_3 = tabla_bloc_dia_3.concat(`<td class="text-center bg-color-acc3c7"> <span class="span_asist" >-</span> </td>`);
          
          //.log(count_bloque_q_s);
          count_bloque_q_s++; 
        }

        for (i = 1; i <=7+dia_regular; i++) { 

          var weekday = extraer_dia_semana(format_a_m_d(fecha));

          tabla_bloc_dia_3 = tabla_bloc_dia_3.concat(`<td> <span class="text-center span-visible">-</span> <input type="number" value="" class="hidden input-visible w-px-45 input_dia_${value.idservicio_pension}_${i} input_dia_${value.idservicio_pension}_${format_a_m_d(fecha)}" onchange="calcular_platos(${value.idservicio_pension},'${format_a_m_d(fecha)}',${data.length})" onkeyup="calcular_platos(${value.idservicio_pension},'${format_a_m_d(fecha)}',${data.length})"> </td>`);
           
            array_class.push( { 
              'idservicio_pension':value.idservicio_pension, 
              'iddetalle_pension':'',
              'fecha_asist':format_a_m_d(fecha),

            } );

            // aumentamos mas un dia hasta llegar al dia 15
          fecha = sumaFecha(1,fecha);

          // console.log(count_bloque_q_s);
          count_bloque_q_s++; 
        } //end for
        // console.log('-----------------------------------------------------------');
      }
      

      // asignamos lo trabajadores a un "array"
      array_servicio.push( {
        'idservicio_pension':value.idservicio_pension, 
        'idsemana_pension':value.idsemana_pension

      } );
    
      var adicional_descuento=0;  
      value.adicional_descuento=='' ? adicional_descuento='0.00' : adicional_descuento=value.adicional_descuento;

      var total=0;
      value.total=='' ? total='0.00' : total=value.total;
      var definir_precio_actual=0;
      if (value.precio_t_semana_p=="") {
        definir_precio_actual=value.precio_t_servicio_p;
      }else{
        definir_precio_actual=value.precio_t_semana_p;
      }

      
      var tabla_bloc_descrip_comida_1 =`<td><b>${value.nombre_servicio}</b></td>`;
      var tabla_bloc_precio_2 =`<td><span class="text-center span-visible" >s/ <b>${ parseFloat(definir_precio_actual).toFixed(2)}</b></span> <input type="number" value="${parseFloat(definir_precio_actual).toFixed(2)}" onchange="calcular_precios(${value.idservicio_pension},${data.length})" onkeyup="calcular_precios(${value.idservicio_pension},${data.length})" class="hidden input-visible w-px-70 input_precio_${value.idservicio_pension}"></td>`;

     // var tabla_bloc_dia_3 =`<td> <span class="text-center span-visible">6</span> <input type="number" class="hidden input-visible w-px-30" > </td>`;
      var tabla_bloc_cantidad_4 =`<td class="text-center"> <span class="span_cantidad_${value.idservicio_pension}">${value.cantidad_total_platos}</span> </td>`;
      var tabla_bloc_adicional_5=`<td> <span class="span-visible">${parseFloat(adicional_descuento).toFixed(2)}</span> <input type="number" value="${parseFloat(adicional_descuento).toFixed(2)}" onchange="calcular_adicional(${value.idservicio_pension},${data.length})" onkeyup="calcular_adicional(${value.idservicio_pension},${data.length})" class="hidden input-visible w-px-70 input_adicional_${value.idservicio_pension}"> </td>`;
      var tabla_bloc_parcial_6 =`<td> <span class="span_parcial_${value.idservicio_pension} calcular_total_parcial_${index+1}">${formato_miles(parseFloat(total).toFixed(2))}</span></td>`;
      var tabla_bloc_descripcion_7 =`<td><textarea  class="text-center textarea-visible textarea_descrip_${value.idservicio_pension}" cols="30" rows="1" style="width: 400px;" readonly >${value.descripcion}</textarea></td>`;

      var tabla_bloc_HN_1 = `<tr>
              ${tabla_bloc_descrip_comida_1} 
              ${tabla_bloc_precio_2} 
              ${tabla_bloc_dia_3} 
              ${tabla_bloc_cantidad_4}
              ${tabla_bloc_adicional_5} 
              ${tabla_bloc_parcial_6}
              ${tabla_bloc_descripcion_7} 
            </tr>`;      
    
      //Unimos y mostramos los bloques separados
      $("#data_table_body").append(tabla_bloc_HN_1);

    }); // end foreach
    $("#parcial_total_x_semana").html(formato_miles(total_monto_x_semana));

    $("#tabla-registro").show();
    $('#cargando-registro-pension').hide();
    
  }); //end post - ver_datos_semana


  $('[data-toggle="tooltip"]').tooltip();  

  count_dias_asistidos = 0;  horas_nomr_total = 0;   horas_extr_total = 0;
}
// Calculamos las: Horas normal/extras,	Días asistidos,	Sueldo Mensual,	Jornal,	Sueldo hora,	Sabatical,	Pago parcial,	Adicional/descuento,	Pago quincenal
function calcular_platos(idservicio_pension,fecha_asist,can_servicios) {

  //variables
   var platos_x_servicio = 0; var parcial_x_servicio=0; var total_parcial=0; var adicional_descuento=0; var precio=0;

  // calcular pago quincenal
  for (let index = 1; index <= 7; index++) {

    // console.log( $(`.input_HN_${id_trabajador}_${index}`).val());    console.log( $(`.input_HE_${id_trabajador}_${index}`).val());

    if (parseFloat($(`.input_dia_${idservicio_pension}_${index}`).val()) > 0 ) {

      platos_x_servicio = platos_x_servicio + parseFloat($(`.input_dia_${idservicio_pension}_${index}`).val());

    }

  }

  // validamos el adicional descuento 
  if (parseFloat($(`.input_adicional_${idservicio_pension}`).val()) >= 0 || parseFloat($(`.input_adicional_${idservicio_pension}`).val()) <= 0 ) {

    adicional_descuento =   parseFloat($(`.input_adicional_${idservicio_pension}`).val());     

  } else {

    adicional_descuento = 0;

    toastr.error(`El dato adicional:: <h3 class=""> ${$(`.input_adicional_${idservicio_pension}`).val()} </h3> no es NUMÉRICO, ingrese un número cero o un positivo o un negativo.`);    
  }
  //capturamos el precio
  if (parseFloat($(`.input_precio_${idservicio_pension}`).val()) >= 0) {

    precio =   parseFloat($(`.input_precio_${idservicio_pension}`).val());     

  }

  parcial_x_servicio= (precio*platos_x_servicio)+adicional_descuento; 

  //  platos_x_servicio
  $(`.span_cantidad_${idservicio_pension}`).html(platos_x_servicio);

  $(`.span_parcial_${idservicio_pension}`).html(formato_miles(parcial_x_servicio.toFixed(2))); 

  for (let k = 1; k <= parseInt(can_servicios); k++) {    
    //console.log($(`.val_pago_quincenal_${k}`).text(), k); 
    total_parcial = total_parcial + parseFloat(quitar_formato_miles($(`.calcular_total_parcial_${k}`).text())); 

  }

  // console.log(suma_total_quincena);

  $(`#parcial_total_x_semana`).html(formato_miles(total_parcial.toFixed(2)));
}

function calcular_adicional(idservicio_pension,can_servicios) {

  var parcial_x_servicio = 0; var reg_precio_actual = 0; can_platos = 0; var total_parcial=0;

  // capturamos precio actual y cantidad de platos
  var reg_precio_actual  =  parseFloat( $(`.input_precio_${idservicio_pension}`).val());
  var can_platos =  parseFloat($(`.span_cantidad_${idservicio_pension}`).text());

  if (reg_precio_actual<0) {reg_precio_actual=0;}else{reg_precio_actual=parseFloat(reg_precio_actual);}

  if (parseFloat($(`.input_adicional_${idservicio_pension}`).val()) >= 0 || parseFloat($(`.input_adicional_${idservicio_pension}`).val()) <= 0 ) {

    parcial_x_servicio = (reg_precio_actual*can_platos) + parseFloat($(`.input_adicional_${idservicio_pension}`).val());

  } else {

    parcial_x_servicio = 0;

    toastr.error(`El dato adicional:: <h3 class=""> ${$(`.input_adicional_${idservicio_pension}`).val()} </h3> no es NUMÉRICO, ingrese un número cero o un positivo o un negativo.`);    
  }

  $(`.span_parcial_${idservicio_pension}`).html(parcial_x_servicio); 

  for (let k = 1; k <= parseInt(can_servicios); k++) {    
    //console.log($(`.val_pago_quincenal_${k}`).text(), k); 
    total_parcial = total_parcial + parseFloat(quitar_formato_miles($(`.calcular_total_parcial_${k}`).text())); 

  }

  $(`#parcial_total_x_semana`).html(formato_miles(total_parcial.toFixed(2)));

}

function calcular_precios(idservicio_pension,can_servicios) {

 var adicional_descuento=0; var parcial_actual=0; var total_parcial=0;
 var reg_precio_actual  = $(`.input_precio_${idservicio_pension}`).val();
 var can_platos = $(`.span_cantidad_${idservicio_pension}`).text();

  if (reg_precio_actual<0) {reg_precio_actual=0;}else{reg_precio_actual=parseFloat(reg_precio_actual);}

  if (parseFloat($(`.input_adicional_${idservicio_pension}`).val()) >= 0 || parseFloat($(`.input_adicional_${idservicio_pension}`).val()) <= 0 ) {

    adicional_descuento =   parseFloat($(`.input_adicional_${idservicio_pension}`).val());     

  } else {

    adicional_descuento = 0;

    toastr.error(`El dato adicional:: <h3 class=""> ${$(`.input_adicional_${idservicio_pension}`).val()} </h3> no es NUMÉRICO, ingrese un número cero o un positivo o un negativo.`);    
  }

  parcial_actual= (reg_precio_actual*can_platos)+adicional_descuento;


  $(`.span_parcial_${idservicio_pension}`).html(parcial_actual); 

  for (let k = 1; k <= parseInt(can_servicios); k++) {    
    //console.log($(`.val_pago_quincenal_${k}`).text(), k); 
    total_parcial = total_parcial + parseFloat(quitar_formato_miles($(`.calcular_total_parcial_${k}`).text())); 

  }

  $(`#parcial_total_x_semana`).html(formato_miles(total_parcial.toFixed(2)));




}

//----------------------Pension--------------------------------------
function limpiar_pension() {

  $("#idpension").val("");
  $("#p_desayuno").val("");
  $("#p_almuerzo").val("");
  $("#p_cena").val("");
  $("#descripcion_pension").val("");
  $("#proveedor").val("null").trigger("change"); 
  $("#servicio_p").val("null").trigger("change");

  $(".form-control").removeClass('is-valid');
  $(".is-invalid").removeClass("error is-invalid");
  $("#servicio_p-error").remove();

}

//Guardar y editar
function guardaryeditar_pension(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-agregar-pension")[0]);
 
  $.ajax({
    url: "../ajax/pension.php?op=guardaryeditar_pension",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('servicio registrado correctamente')				 

        tabla.ajax.reload();

        $("#modal-agregar-pension").modal("hide");

       limpiar_pension();
			}else{

				toastr.error(datos)
			}
    },
  });
}

//Función Listar
function listar(nube_idproyecto) {
  var sumatotal=0; var totalsaldo=0; 
 // $("#total_pension").html("");
  //$("#total_saldo").html("");
  tabla=$('#tabla-resumen-break-semanal').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/pension.php?op=listar_pensiones&nube_idproyecto='+nube_idproyecto,
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
        }
      },
      createdRow: function (row, data, ixdex) {
        // columna: #
        if (data[0] != '') {
          $("td", row).eq(0).addClass('text-center text-nowrap');
        }
        if (data[1] != '') {
          $("td", row).eq(1).addClass('text-center text-nowrap');
        }
        if (data[3]!="") {
          $("td", row).eq(4).addClass('text-right');
          sumatotal += parseFloat(data[4]);
        } else {
          sumatotal +=0;
        }
        if (data[5]) {
          $("td", row).eq(5).addClass('text-nowrap');
          $("td", row).eq(6).addClass('text-nowrap');
          
        }
        if (data[7]!="") {$("td", row).eq(7).addClass('text-right');}
        //console.log(data);
        if (quitar_formato_miles(data[7]) > 0) {
          $("td", row).eq(7).css({
            "background-color": "#ffc107",
            color: "black",
          });
          
        } else if (quitar_formato_miles(data[7]) == 0) {
          $("td", row).eq(7).css({
            "background-color": "#28a745",
            color: "white",
          });
        } else {
          $("td", row).eq(7).css({
            "background-color": "#ff5252",
            color: "white",
          });
          
        }
        if (data[7]!="") {
          var saldo=quitar_formato_miles(data[7]);
        }
        totalsaldo += parseFloat(saldo);
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

  $.post("../ajax/pension.php?op=total_pension", { idproyecto: nube_idproyecto }, function (data, status) {

    data = JSON.parse(data); //console.log(data);   
    $("#total_pension").html(formato_miles(data.total));
    $("#total_saldo").html(formato_miles(totalsaldo));
  });
  
}


//Función ver detalles Detalles
function ver_detalle_x_servicio(idpension) {
  //console.log(numero_semana,nube_idproyecto);
  $("#modal-ver-detalle-semana").modal("show");
  tabla_detalle_s=$('#tabla-detalles-semanal').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/pension.php?op=ver_detalle_x_servicio&idpension='+idpension,
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
        }
      },
      createdRow: function (row, data, ixdex) {
        // columna: #
        if (data[0] != '') {
          $("td", row).eq(0).addClass('text-center');
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

//mostrar
function mostrar_pension(idpension) {
  limpiar_pension();
  var array_datosselect=[];
  $("#modal-agregar-pension").modal("show");


  $.post("../ajax/pension.php?op=mostrar_pension", { idpension: idpension }, function (data, status) {

    data = JSON.parse(data);  
    console.log(data);   
    $("#proveedor").val(data.idproveedor).trigger("change"); 
    $("#idproyecto_p").val(data.idproyecto);
    $("#idpension").val(data.idpension);
    $("#descripcion_pension").val(data.descripcion);

    data.servicio_pension.forEach( (value, item )=> {

      console.log(value.precio, value.nombre_servicio);
      if (value.nombre_servicio=="Desayuno") {
        $("#p_desayuno").val(value.precio);
        array_datosselect.push(value.nombre_servicio);
      }
      if (value.nombre_servicio=="Almuerzo") {  
        $("#p_almuerzo").val(value.precio);
        array_datosselect.push(value.nombre_servicio);
      }

      if (value.nombre_servicio=="Cena") {     
        $("#p_cena").val(value.precio);
        array_datosselect.push(value.nombre_servicio);
      }

    });

    $("#servicio_p").val(array_datosselect).trigger("change");

  });
}

//--------------------Comprobantes----------------------------------
function ocultar() {

  $("#regresar_aprincipal").show();
  $("#Lista_breaks").hide();
  $("#mostrar-tabla").hide();
  $("#tabla-registro").hide();
  $("#tabla-comprobantes").show();
  $("#guardar").show();
  $("#guardar_pension").hide();

}

function regresar() {

  $("#regresar_aprincipal").hide();
  $("#Lista_breaks").show();
  $("#mostrar-tabla").show();
  $("#tabla-registro").hide();
  $("#tabla-comprobantes").hide();
  $("#guardar").hide();
  $("#guardar_pension").show();
}
//Función limpiar-factura
function limpiar_comprobante() {
  //idpension_f,idfactura_pension
  $("#nro_comprobante").val("");
  $(".monto").val("");
  $("#monto").val("");
  $("#idfactura_pension").val("");
  $("#fecha_emision").val("");
  $("#descripcion").val("");
  $(".subtotal").val("");
  $("#subtotal").val("");
  $(".igv").val("");
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
    url: "../ajax/pension.php?op=guardaryeditar_Comprobante",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('servicio registrado correctamente')				 

        tabla.ajax.reload();

        $("#modal-agregar-comprobante").modal("hide");
       listar_comprobantes(localStorage.getItem('idpension_f_nube'));
       total_monto(localStorage.getItem('idpension_f_nube'));
       listar( localStorage.getItem('nube_idproyecto'));
        limpiar_comprobante();
			}else{

				toastr.error(datos)
			}
    },
  });
}

function listar_comprobantes(idpension) {
  localStorage.setItem('idpension_f_nube',idpension);

  ocultar();
  $("#idpension_f").val(idpension);
  
  tabla=$('#t-comprobantes').dataTable({  
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/pension.php?op=listar_comprobantes&idpension='+idpension,
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
        }
      },
      createdRow: function (row, data, ixdex) {
        // columna: #
        if (data[0] != '') {
          $("td", row).eq(0).addClass('text-center');
        }
        // columna: sub total
        if (data[5] != '') {
          $("td", row).eq(5).addClass('text-nowrap text-right');
        }
        // columna: igv
        if (data[6] != '') {
          $("td", row).eq(6).addClass('text-nowrap text-right');
        }
        // columna: total
        if (data[7] != '') {
          $("td", row).eq(7).addClass('text-nowrap text-right');
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
  total_monto(localStorage.getItem('idpension_f_nube'));
}

function comprob_factura() {

  var monto = parseFloat($('.monto').val());
  $('#monto').val(monto);
  if ($("#tipo_comprovante").select2("val") =="Factura") {

      var subtotal=0; var igv=0;

      $(".subtotal").val("");   
      $("#subtotal").val("");
      $(".igv").val("");  
      $("#igv").val(""); 

      subtotal= monto/1.18;
      igv= monto-subtotal;

      $(".subtotal").val(subtotal.toFixed(2));
      $("#subtotal").val(subtotal.toFixed(2));
      $(".igv").val(igv.toFixed(2));
      $("#igv").val(igv.toFixed(2));

  } else {

    $(".subtotal").val(monto.toFixed(2));
    $("#subtotal").val(monto.toFixed(2));
    $("#igv").val("0.00");
    $(".igv").val(0);
  }
  
  
}

//mostrar
function mostrar_comprobante(idfactura_pension ) {
  limpiar_comprobante();
  $("#modal-agregar-comprobante").modal("show");
  $("#tipo_comprovante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/pension.php?op=mostrar_comprobante", { idfactura_pension : idfactura_pension  }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);   
    
    $("#tipo_comprovante").val(data.tipo_comprobante).trigger("change");
    $("#idfactura_pension  ").val(data.idfactura_pension);
    $("#nro_comprobante").val(data.nro_comprobante);
    $(".monto").val(parseFloat(data.monto).toFixed(2));
    $("#monto").val(data.monto);
    $("#fecha_emision").val(data.fecha_emision);
    $("#descripcion").val(data.descripcion);
    $(".subtotal").val(parseFloat(data.subtotal).toFixed(2));
    $("#subtotal").val(data.subtotal);
    $(".igv").val(parseFloat(data.igv).toFixed(2));
    $("#igv").val(parseFloat(data.igv));
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
          $('#foto2_i').attr("src", "../dist/docs/pension/comprobante/" +comprobante);

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
          $('#ver_pdf').html('<iframe src="../dist/docs/pension/comprobante/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="210"></iframe>');
          
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
function eliminar_comprobante(idfactura_pension) {

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

      //Desactivar
      $.post("../ajax/pension.php?op=desactivar_comprobante", {idfactura_pension:idfactura_pension}, function (e) {

        Swal.fire("Desactivado!", "Comprobante a ha sido desactivado.", "success");
        total_monto(localStorage.getItem('idpension_f_nube'));
        tabla.ajax.reload();
        listar( localStorage.getItem('nube_idproyecto'));
      });  


    }else if (result.isDenied) {

      // Eliminar
      $.post("../ajax/pension.php?op=eliminar_comprobante", {idfactura_pension:idfactura_pension}, function (e) {

        Swal.fire("Eliminado!", "Comprobante a ha sido Eliminado.", "success");
        total_monto(localStorage.getItem('idpension_f_nube'));
        tabla.ajax.reload();
        listar( localStorage.getItem('nube_idproyecto'));
      }); 

    }

  });

}

function activar_comprobante(idfactura_pension ) {
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
      $.post("../ajax/pension.php?op=activar_comprobante", { idfactura_pension : idfactura_pension  }, function (e) {

        Swal.fire("Activado!", "Comprobante ha sido activado.", "success");
        total_monto(localStorage.getItem('idpension_f_nube'));
        tabla.ajax.reload();
        listar(localStorage.getItem('nube_idproyecto'));
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
    $('#img-factura').attr("src", "../dist/docs/pension/comprobante/" +comprobante);

    $("#iddescargar").attr("href","../dist/docs/pension/comprobante/" +comprobante);

  }else{
    $('#img-factura').hide();
    $('#ver_fact_pdf').show();
    $('#ver_fact_pdf').html('<iframe src="../dist/docs/pension/comprobante/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');
    $("#iddescargar").attr("href","../dist/docs/pension/comprobante/" +comprobante);
  } 
 // $(".tooltip").removeClass('show');
}

//-total Pagos
function total_monto(idpension) {
  $.post("../ajax/pension.php?op=total_monto", { idpension:idpension }, function (data, status) {
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

  $.validator.setDefaults({

    submitHandler: function (e) {
      guardaryeditar_pension(e)

    },
  });

  $("#form-agregar-pension").validate({
    rules: {
      proveedor:{required: true},
      'servicio_p[]':{required: true}
    },
    messages: {
      //====================
      proveedor: {
        required: "Campo requerido", 
      },
      'servicio_p[]': {
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
  const dias = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do']; //
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

function quitar_formato_miles(numero) {
  let inVal = numero.replace(/,/g, '');
  return inVal;
}


function extrae_extencion(filename) {
  return filename.split('.').pop();
}


