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

sumaFecha = function(d, fecha)
{
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
    var cal_quincena  =data.plazo/15;
    var i=0;
    var cont=0
    $('#Lista_quincenas').html('');

    while (i <= cal_quincena) {
      cont=cont+1;
      var fecha_inicio = fecha_i;
      
      fecha=sumaFecha(14,fecha_inicio);

      // console.log(fecha_inicio+'-'+fecha);
      ver_asistencia="'"+fecha_inicio+"',"+"'"+fecha+"'";
      $('#Lista_quincenas').append(' <button type="button" class="btn bg-gradient-info text-center" onclick="datos_quincena('+ver_asistencia+');"><i class="far fa-calendar-alt"></i> Quincena '+cont+'<br>'+fecha_inicio+'-'+fecha+'</button>')
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

function datos_quincena(f1,f2) {
  var nube_idproyect =localStorage.getItem('nube_idproyecto');
  console.log('----------'+f1,f2,nube_idproyect);
      
  mostrar_form_table(2)
  $.post("../ajax/registro_asistencia.php?op=ver_datos_quincena", {f1:f1,f2:f2,nube_idproyect:nube_idproyect}, function (data, status) {
        
    data =JSON.parse(data);
    //console.log(data);
    var rowtrabajador='';
    var rowtrabajadores='';
    var horas = '<td>N</td>';
    var celda = document.createElement("td");
    var textoCelda='';
    $(".nameappend").html('');

    $.each(data, function (index, value) {      


      $.post("../ajax/registro_asistencia.php?op=ver_datos_quincena_xdia", {f1:f1,f2:f2,nube_idproyect:nube_idproyect,idtrabajador:value.idtrabajador}, function (data, status) {
                
          data =JSON.parse(data);
         // console.log(data);
          var fecha = f1;
          var i; //defines i
          for (i = 1; i <=15; i++) { //starts loop
            // console.log("The Number Is: " + i); //What ever you want
            console.log(value.nombres);
            console.log('f.'+i+'-'+fecha);
            for (let i = 0; i < data.length; i++) { 
              //console.log(data[i]['fecha_asistencia']);

              if (fecha==data[i]['fecha_asistencia']) {
                console.log(data[i]['horas_trabajador']);
                horas = '<td>'+data[i]['horas_trabajador']+'</td>';
                textoCelda = document.createTextNode("celda en la hilera "+i+", columna ");
                
              }
            }
            
           //console.log(celda.appendChild(textoCelda));
            var fecha = sumaFecha(1,fecha);
            // console.log(fecha);
          };

      });
      rowtrabajador= '<tr>'+
          '<td>H/N</td>'+
          '<td>'+index +'</td>'+
          '<td>'+value.cargo +'</td>'+
          celda.appendChild(horas);
          '<td>0</td>'+
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
          '<td>'+value.horas_trabajador +'</td>'+
          '<td>'+value.sueldo_mensual +'</td>'+
          '<td>'+value.sueldo_diario +'</td>'+
          '<td>'+value.sueldo_hora +'</td>'+
          '<td>'+value.sabatical +'</td>'+
          '<td>1</td>'+
          '<td>750.00</td>'+

      '</tr>'+
      '<tr>'+
          '<td>H/E</td>'+
          '<td>'+value.nombres +'</td>'+
          '<td>'+value.cargo +'</td>'+
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
          '<td>300</td>'+
          '<td>107.00</td>'+
          '<td>13.39</td>'+
          '<td>0</td>'+
          '<td>0</td>'+
          '<td>53.56</td>'+

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

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $.post("../ajax/registro_asistencia.php?op=mostrar_editar", { idasistencia_trabajador: idasistencia_trabajador }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);
    
    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#lista-de-trabajadores2").html("");

    $.each(data, function (index, value) {
      // console.log(value.idtrabajador_por_proyecto);
      var img =value.imagen_perfil != '' ? '<img src="../dist/img/usuarios/'+value.imagen_perfil+'" alt="" >' : '<img src="../dist/svg/user_default.svg" alt="" >';
      
      $("#lista-de-trabajadores2").append(
        '<!-- Trabajador -->'+                         
        '<div class="col-lg-6">'+
          '<div class="user-block">'+
            img+
            '<span class="username"><p class="text-primary"style="margin-bottom: 0.2rem !important"; >'+value.nombres+'</p></span>'+
            '<span class="description">'+value.documento+': '+value.numero_documento+'</span>'+
          '</div>'+                         
          '<input type="hidden" name="trabajador2[]" value="'+value.idtrabajador_por_proyecto+'" />'+
        '</div>'+

        '<!-- Horas de trabajo -->'+
        '<div class="col-lg-6 mt-2">'+
          '<div class="form-group">'+
            '<input id="horas_trabajo" name="horas_trabajo2[]" type="time"   class="form-control" value="00:00" />'+             
          '</div>'+
        '</div> '+
        '<div class="col-lg-12 borde-arriba-negro borde-arriba-verde mt-1 mb-3"> </div>'
      );
    });
  });
}

//Función para desactivar registros
function justificar(idasistencia) {
  console.log('holaaaaa');
 
}

// ver_asistencias
function ver_asistencias_individual(idtrabajadorproyecto,fecha_inicio_proyect) {

  console.log(idtrabajadorproyecto,fecha_inicio_proyect);
  
  mostrar_form_table(3);

  tabla2=$('#tabla-detalle-asistencia-individual').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/registro_asistencia.php?op=listar_asis_individual&idtrabajadorproyecto='+idtrabajadorproyecto,
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
