var tabla; var tabla2;

//Función que se ejecuta al inicio
function init() {

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  listar(localStorage.getItem('nube_idproyecto'));  

  // $("#bloc_Accesos").addClass("menu-open");

  $("#mAsistencia").addClass("active");

  // $("#lasistencia").addClass("active");

  $("#guardar_registro").on("click", function (e) { $("#submit-form-asistencia").submit(); });
  // $("#modal-agregar-asistencia").on("submit",function(e) { guardaryeditar(e);	})
  
  // Formato para telefono
  $("[data-mask]").inputmask();

  //Timepicker
  $('#timepicker').datetimepicker({
    // format: 'LT',
    format:'HH:mm ',
    lang:'ru'
  })

  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1; //January is 0!
  var yyyy = today.getFullYear();

  if (dd < 10) {  dd = '0' + dd; }

  if (mm < 10) {  mm = '0' + mm;  }

  today = yyyy + '-' + mm + '-' + dd;
  $("#fecha").val(today);

  
}

function mostrar_form_table(estados) {

  if (estados == 1 ) {
    $("#card-registrar").show();
    $("#tabla-asistencia-trab").show();
    $("#ver_asistencia").hide();
    $("#detalle_asistencia").hide();    
    $("#card-regresar").hide();
  } else {
    if (estados == 2) {
      $("#card-registrar").hide();
      $("#tabla-asistencia-trab").hide();
      $("#ver_asistencia").show();
      $("#detalle_asistencia").hide();
      $("#card-regresar").show();
    } else {
      $("#card-registrar").hide();
      $("#tabla-asistencia-trab").hide();
      $("#ver_asistencia").hide();
      $("#detalle_asistencia").show();
      $("#card-regresar").show();
    }
  }
}

//Función limpiar
function limpiar() {
  $("#idasistencia_trabajador").val(""); 
  $("#trabajador").val("");
  $("#horas_trabajo").val("");
  
  lista_trabajadores(localStorage.getItem('nube_idproyecto'));
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
function listar(nube_idproyecto) {

  $('#Lista_quincenas').html('<i class="fas fa-spinner fa-pulse fa-2x"></i>');

  tabla=$('#tabla-asistencia').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/registro_asistencia.php?op=listar&nube_idproyecto='+nube_idproyecto,
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

  //Listar quincenas(botones)
  $.post("../ajax/registro_asistencia.php?op=listarquincenas", { nube_idproyecto: nube_idproyecto }, function (data, status) {

    data =JSON.parse(data); //console.log(data);
   
    var fecha = data.fecha_inicio;
    // console.log(fecha);
    var fecha_i = sumaFecha(0,fecha);
    var cal_quincena  = data.plazo/15;
    var i=0;
    var cont=0
    $('#Lista_quincenas').html('');

    while (i <= cal_quincena) {

      cont=cont+1;

      var fecha_inicio = fecha_i;
      
      fecha=sumaFecha(14,fecha_inicio);

      // console.log(fecha_inicio+'-'+fecha);
      ver_asistencia="'"+fecha_inicio+"',"+"'"+fecha+"'";

      $('#Lista_quincenas').append(' <button type="button" class=" mb-2 btn bg-gradient-info text-center" onclick="datos_quincena('+ver_asistencia+');"><i class="far fa-calendar-alt"></i> Quincena '+cont+'<br>'+fecha_inicio+' // '+fecha+'</button>')
      
      fecha_i =sumaFecha(1,fecha);

      i++;
    }
    //console.log(fecha);
  });
}

// listamos los trabajadores para tomar la asistencia
function lista_trabajadores(nube_idproyecto) {

  $("#lista-de-trabajadores").html(
    '<div class="col-lg-12 text-center">'+  
      '<i class="fas fa-spinner fa-pulse fa-6x"></i><br />'+
      '<br />'+
      '<h4>Cargando...</h4>'+
    '</div>'
  );

  $.post("../ajax/registro_asistencia.php?op=lista_trabajador", { nube_idproyecto: nube_idproyecto }, function (data, status) {

    data = JSON.parse(data);  //console.log(data); 

    $("#lista-de-trabajadores").html("");

    $.each(data, function (index, value) {
      // console.log(value.idtrabajador_por_proyecto);
      var img =value.imagen_perfil != '' ? '<img src="../dist/img/usuarios/'+value.imagen_perfil+'" alt="" >' : '<img src="../dist/svg/user_default.svg" alt="" >';
      
      $("#lista-de-trabajadores").append(
        '<!-- Trabajador -->'+                         
        '<div class="col-lg-6">'+
          '<div class="user-block">'+
            img+
            '<span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'+value.nombres+'</p></span>'+
            '<span class="description">'+value.documento+': '+value.numero_documento+'</span>'+
          '</div>'+                         
          '<input type="hidden" name="trabajador[]" value="'+value.idtrabajador_por_proyecto+'" />'+
        '</div>'+

        '<!-- Horas de trabajo -->'+
        '<div class="col-lg-6 mt-2">'+
          '<div class="form-group">'+
            '<input id="horas_trabajo" name="horas_trabajo[]" type="time"   class="form-control" value="00:00" />'+             
          '</div>'+
        '</div> '+
        '<div class="col-lg-12 borde-arriba-negro borde-arriba-verde mt-1 mb-3"> </div>'
      );
    });
  });
}

function agregar_hora_all() {
  var hora_all = $("#hora_all").val();
  $('input[type=time][name="horas_trabajo[]"]').val(hora_all);
}

// listamos la data de una quincena
function datos_quincena(f1,f2) {

  var nube_idproyect =localStorage.getItem('nube_idproyecto');  console.log('----------'+f1,f2,nube_idproyect);
      
  mostrar_form_table(2)

  $.post("../ajax/registro_asistencia.php?op=ver_datos_quincena", {f1:f1,f2:f2,nube_idproyect:nube_idproyect}, function (data, status) {
        
    data =JSON.parse(data); console.log(data);
    var rowtrabajador='';
    var rowtrabajadores='';
    var horas = '<td>-</td>';     
    $(".nameappend").html('');
   

    $.each(data, function (index, value) {

      var pintar_hora_por_dia = ""; var count_dias_asistidos = 0; var horas_total = 0; var sabatical = 0;

      if (value.asistencia.length != 0) {

        var i;  var fecha = f1; console.log("tiene data");
        
        for (i = 1; i <=15; i++) {

          console.log('fecha: '+i+' - '+fecha + ' -------------------------');

          for (let i = 0; i < value.asistencia.length; i++) { 
            //console.log(data[i]['fecha_asistencia']);
            let split_f = format_d_m_a ( value.asistencia[i]['fecha_asistencia'] ) ; 
             
            let fecha_semana = new Date( fecha ); let fecha_asistencia = new Date(split_f);

            if ( fecha_semana.getTime() == fecha_asistencia.getTime() ) {

              //console.log(value.asistencia[i]['horas_normal_dia']);
              console.log("coincide: "+ split_f + ' - ' + fecha);
              pintar_hora_por_dia = pintar_hora_por_dia.concat('<td>'+value.asistencia[i]['horas_normal_dia']+'</td>');
              horas_total = horas_total + parseFloat(value.asistencia[i]['horas_normal_dia']);
              count_dias_asistidos++;
              break;
                          
            }else{
              console.log("no coincide: "+ split_f + ' - ' + fecha);
              //console.log("no coincide: "+ i);
              //console.log(fecha + ' - '+ value.asistencia[i]['fecha_asistencia']);
              //console.log(fecha_semana.getTime() + ' - '+ fecha_asistencia.getTime());
            }
          }
          fecha = sumaFecha(1,fecha);
        }

      } else {
        console.log("no inguna fecha asistida");
      }

      // validamos el sabatical
      if (horas_total >= 44 && horas_total < 88) {
        sabatical = 1;
      } else {
        if (horas_total >= 88) {
          sabatical = 2;
        }
      }
      console.log(pintar_hora_por_dia); 
      console.log('cant dias sistidos: '+count_dias_asistidos);
      rowtrabajador= '<tr>'+
        '<td>H/N</td>'+
        '<td>' + value.nombres +'</td>'+
        '<td>' + value.cargo + '</td>'+
        pintar_hora_por_dia + horas.repeat(15 - count_dias_asistidos) +
        '<td>' + horas_total + '</td>'+
        '<td>' +value.sueldo_mensual + '</td>'+
        '<td>' +value.sueldo_diario + '</td>'+
        '<td>' +value.sueldo_hora + '</td>'+
        '<td>' + sabatical + '</td>'+
        '<td>1</td>'+
        '<td>750.00</td>'+
      '</tr>'+
      '<tr>'+
        '<td>H/E</td>'+
        '<td>' + value.nombres + '</td>'+
        '<td>' + value.cargo + '</td>'+
        '<td>2</td>'+
        '<td>1</td>'+
        '<td>0</td>'+
        '<td>0</td>'+
        '<td>0</td>'+
        '<td>1</td>'+
        '<td>1</td>'+
        '<td>1</td>'+
        '<td>1</td>'+
        '<td>1</td>'+
        '<td>1</td>'+
        '<td>1</td>'+
        '<td>1</td>'+
        '<td>4</td>'+
        '<td>4</td>'+
        '<td>44</td>'+
        '<td>' + value.sueldo_mensual + '</td>'+
        '<td>' + value.sueldo_diario + '</td>'+
        '<td>' + value.sueldo_hora + '</td>'+
        '<td>0</td>'+
        '<td>53.56</td>'+
        '<td>-</td>'+
        
      '</tr>';
      $('.nameappend').append(rowtrabajador);
       
    });

    $('.nameappend').append('<tr>'+
      '<td colspan="23"></td>'+
      '<td ><b>TOTAL</b></td>'+
      '<td>803.56</td>'+
    '</tr>'
    );

    var tabla = '<div class="table-responsive">'+
      '<div class="table-responsive-lg"  style="overflow-x: scroll;">'+
         '<table class="table styletabla" style="border: black 1px solid;">'+
            '<thead>'+
                '<tr>'+
                    '<th rowspan="4" class="stile">#</th>'+
                    '<th rowspan="4" class="stile">Nombre</th>'+
                    '<th rowspan="4" class="stile">Cargo</th>'+
                    '<th colspan="7" style="text-align: center !important;border: black 1px solid; padding: 0.5rem;">Horas de trabajo por día</th>'+
                    '<th rowspan="3" class="stile">Horas normal/ extras</th>'+
                    '<th rowspan="3" class="stile">Sueldo Mensual</th>'+
                    '<th rowspan="3" class="stile">Jornal</th>'+
                    '<th rowspan="3" class="stile">Sueldo hora</th>'+
                    '<th rowspan="3" class="stile">Sabatical</th>'+
                    '<th rowspan="3" class="stile">Adicional</th>'+
                    '<th rowspan="3" class="stile">Pago quincenal</th>'+
                '</tr>'+
                '<tr class="dias">'+
                    '<th>L</th>'+
                    '<th>M</th>'+
                    '<th>M</th>'+
                    '<th>J</th>'+
                    '<th>V</th>'+
                    '<th>S</th>'+
                    '<th>D</th>'+
                '</tr>'+
                '<tr class="dias">'+
                    '<th>1</th>'+
                    '<th>2</th>'+
                    '<th>3</th>'+
                    '<th>4</th>'+
                    '<th>5</th>'+
                    '<th>6</th>'+
                    '<th>7</th>'+
                '</tr>'+
            '</thead>'+
            '<tbody class="tcuerpo nameappend">'+
                  rowtrabajadores +
                '<tr>'+
                    '<td colspan="14"></td>'+
                    '<td ><b>TOTAL</b></td>'+
                    '<td>803.56</td>'+
               '</tr>'+
            '</tbody>'+
        '</table>'+
      '</div>'+
    '</div>'


   // $("#ver_asistencia").html(tabla);

  });

  $("#cargando-1-fomulario").show();
  $("#cargando-2-fomulario").hide();
  
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-asistencia")[0]);

  $.ajax({
    url: "../ajax/registro_asistencia.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {
			 
        Swal.fire("Correcto!", "Asistencia registrada correctamente", "success");

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-asistencia").modal("hide");

			}else{

				Swal.fire("Error!", datos, "error");
			}
    },
  });
}

function mostrar(idasistencia_trabajador) {
  $('#modal-editar-asistencia').modal('show')
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();
  
  $.post("../ajax/registro_asistencia.php?op=mostrar_editar", { idasistencia_trabajador: idasistencia_trabajador }, function (data, status) {

    data = JSON.parse(data);  console.log(data);
    
    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#fecha2").val(data.fecha_asistencia);      
    var suma = (parseFloat(data.horas_normal_dia) + parseFloat(data.horas_extras_dia)).toFixed(2).toString();
    var hr_total_c =  convertir_a_hora(suma);

    console.log(hr_total_c);

    var img =data.imagen_perfil != '' ? '<img src="../dist/img/usuarios/'+data.imagen_perfil+'" alt="" >' : '<img src="../dist/svg/user_default.svg" alt="" >';
    
    $("#lista-de-trabajadores2").html(
      '<!-- Trabajador -->'+                         
      '<div class="col-lg-12">'+
        '<label >Trabajador</label> <br>'+
        '<div class="user-block">'+
          img+
          '<span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'+data.nombres+'</p></span>'+
          '<span class="description">'+data.documento+': '+data.numero_documento+'</span>'+
        '</div>'+                         
        '<input type="hidden" name="trabajador2[]" value="'+data.idtrabajador_por_proyecto+'" />'+
      '</div>'+

      '<!-- Horas de trabajo -->'+
      '<div class="col-lg-12 mt-3">'+
        '<label for="fecha">Horas</label>'+
        '<div class="form-group">'+
          '<input id="horas_trabajo" name="horas_trabajo2[]" type="time"   class="form-control" value="'+hr_total_c+'" />'+             
        '</div>'+
      '</div> '+
      '<div class="col-lg-12 borde-arriba-negro borde-arriba-verde mt-1 mb-3"> </div>'
    );

  });
}

//Función para desactivar registros
function justificar(idasistencia,horas, estado) {

  if (estado == "0") {

    Swal.fire("Activa este registro!", "Para usar esta opcion, active este registro.", "info");

  } else {

    if (horas >= 8) {

      Swal.fire("No puedes Justificar!", "Este trabajador tiene 8 horas completas, las justificación es para compensar horas perdidas.", "info");
    
    } else {
      $("#modal-justificar-asistencia").modal("show")
    }
  } 
}

// ver_asistencias
function ver_asistencias_individual(idtrabajador_por_proyecto,fecha_inicio_proyect) {

  console.log(idtrabajador_por_proyecto,fecha_inicio_proyect);
  
  mostrar_form_table(3);

  tabla2=$('#tabla-detalle-asistencia-individual').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/registro_asistencia.php?op=listar_asis_individual&idtrabajadorproyecto='+idtrabajador_por_proyecto,
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

      Swal.fire({
        title: "¿Está seguro de guardar estos registros?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, guardar!",
      }).then((result) => {

        if (result.isConfirmed) {

          guardaryeditar(e);
          
        }
      });
    },
  });  

  $("#form-asistencia").validate({    
    

    rules: {      
      idproyecto: { required: true},
    },

    messages: {
      idproyecto: {
        required: "Por favor  seleccione proyecto",
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

function convertir_a_hora(hora_n) {

  var convertido; var suma; var min; var hora; console.log('h:' + hora_n );
      
  var recortado_suma = hora_n.split('.').pop();

  min = Math.round((parseFloat(recortado_suma)*60)/100);
  
  if (hora_n >=10) {

    hora = hora_n.substr(0,2)

  } else {

    hora = '0'+hora_n.substr(0,1)

  }

  if (min >= 10) {

    convertido = hora + ':' + min;

  } else {

    convertido = hora + ':0' + min;

  }    
  
  return convertido;
}

//Función para desactivar registros
function desactivar(idasistencia_trabajador) {
  $(".tooltip").hide();
  Swal.fire({
    title: "¿Está Seguro de  Desactivar la Asistencia?",
    text: "Al desactivar, las horas de este registro no seran contado.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/registro_asistencia.php?op=desactivar", { idasistencia_trabajador: idasistencia_trabajador }, function (e) {

        Swal.fire("Desactivado!", "La asistencia ha sido desactivado.", "success");
    
        tabla.ajax.reload(); tabla2.ajax.reload();
      });      
    }
  });   
}

//Función para activar registros
function activar(idasistencia_trabajador) {
  $(".tooltip").hide();
  Swal.fire({
    title: "¿Está Seguro de  Activar  la Asistencia?",
    text: "Al activar, las horas de este registro seran contados",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/registro_asistencia.php?op=activar", { idasistencia_trabajador: idasistencia_trabajador }, function (e) {

        Swal.fire("Activado!", "La asistencia ha sido activado.", "success");

        tabla.ajax.reload(); tabla2.ajax.reload();
      });
      
    }
  });      
}

function format_d_m_a(fecha) {

  let splits = fecha.split("-"); 

  return splits[2]+'-'+splits[1]+'-'+splits[0];
}
