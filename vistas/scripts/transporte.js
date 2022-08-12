var tabla;

//Función que se ejecuta al inicio
function init() {

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Viaticos").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mViatico").addClass("active bg-primary");

  $("#lTransporte").addClass("active");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));  

  listar();  

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);

  $("#guardar_registro").on("click", function (e) {$("#submit-form-transporte").submit();});

  //Initialize Select2 tipo_viajero
  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Seleccinar un proveedor", allowClear: true, });
  //Initialize Select2 tipo_viajero
  $("#tipo_viajero").select2({  theme: "bootstrap4", placeholder: "Seleccinar tipo clasificación", allowClear: true, });
  //Initialize Select2 tipo_viajero
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Seleccinar tipo comprobante", allowClear: true, });
  //Initialize Select2 tipo_viajero
  $("#tipo_ruta").select2({ theme: "bootstrap4",  placeholder: "Seleccinar tipo ruta", allowClear: true, });
  //Initialize Select2 tipo_viajero
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar forma de pago", allowClear: true, });
  
  // Formato para telefono
  $("[data-mask]").inputmask();
}
// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

//Función limpiar
function limpiar() {

 // idtransporte,fecha_inicio,fecha_fin,cantidad,unidad,precio_unitario,precio_parcial,descripcion
  $("#idtransporte").val("");
  $("#idproveedor").val("");
  $("#fecha_viaje").val(""); 
  $("#cantidad").val(""); 
 // $("#unidad").val(""); 
  $("#precio_unitario").val(""); 

  $(".precio_parcial").val(""); 
  $("#precio_parcial").val(""); 
  $("#nro_comprobante").val("");

  $(".subtotal").val("");
  $("#subtotal").val("");

  $(".igv").val("");
  $("#igv").val("");
  $("#val_igv").val(""); 
  $("#tipo_gravada").val("");  

  $("#ruta").val(""); 
  $("#descripcion").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#idproveedor").val("null").trigger("change");
  $("#tipo_viajero").val("null").trigger("change");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#tipo_ruta").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();

}

//segun tipo de comprobante
function comprob_factura() {

  var cantidad = $("#cantidad").val();
  var precio_unitario = $("#precio_unitario").val();

  var monto = cantidad*precio_unitario;

  $("#precio_parcial").val(monto);

 // console.log('monto '+ monto +' cantidad '+ cantidad +' precio_unitario '+ precio_unitario);

 if ($("#tipo_comprobante").select2("val") =="Factura") {

   $(".nro_comprobante").html("Núm. Comprobante");

    $("#tipo_gravada").val("GRAVADA"); 
    $("#subtotal").val("");
    $("#igv").val("");

    calculandototales_fact();    

  } else {

    if ($("#tipo_comprobante").select2("val") =="Ninguno") {
     
      $(".nro_comprobante").html("Núm. de Operación");
      
      $("#subtotal").val(monto.toFixed(2));

      $("#igv").val("0.00");
      $("#val_igv").val("0"); 
      $("#tipo_gravada").val("NO GRAVADA"); 
      $("#val_igv").prop("readonly",true);
    } else {

      $(".nro_comprobante").html("Núm. Comprobante");
        
      $("#subtotal").val(monto.toFixed(2));

      $("#igv").val("0.00");
      $("#val_igv").val("0"); 
      $("#tipo_gravada").val("NO GRAVADA"); 
      $("#val_igv").prop("readonly",true);
    }

  }
  
}


function validando_igv() {

  if ($("#tipo_comprobante").select2("val") == "Factura") {

    $("#val_igv").prop("readonly",false);
    $("#val_igv").val(0.18); 

  }else {

    $("#val_igv").val(0); 

  }
  
}

function calculandototales_fact() {

  var precio_parcial =  $("#precio_parcial").val();

  var val_igv = $('#val_igv').val();

  if (precio_parcial == null || precio_parcial == "") {

    $("#subtotal").val(0);
    $("#igv").val(0); 

  } else {
 
    var subtotal = 0;
    var igv = 0;

    if (val_igv == null || val_igv == "") {

      $("#subtotal").val(parseFloat(precio_parcial));
      $("#igv").val(0);

    }else{

      $("subtotal").val("");
      $("#igv").val("");

      subtotal = quitar_igv_del_precio(precio_parcial, val_igv, 'decimal');
      igv = precio_parcial - subtotal;

      $("#subtotal").val(parseFloat(subtotal).toFixed(2));
      $("#igv").val(parseFloat(igv).toFixed(2));

    }

  }  

}

function quitar_igv_del_precio(precio , igv, tipo ) {
  console.log(precio , igv, tipo);
  var precio_sin_igv = 0;

  switch (tipo) {
    case 'decimal':

      if (parseFloat(precio) != NaN && igv > 0 && igv <= 1 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( ( parseFloat(igv) * 100 ) + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;

    case 'entero':

      if (parseFloat(precio) != NaN && igv > 0 && igv <= 100 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( parseFloat(igv)  + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;
  
    default:
      $(".val_igv").html('IGV (0%)');
      toastr.success('No has difinido un tipo de calculo de IGV.')
    break;
  } 
  
  return precio_sin_igv; 
}

function selecct_glosa() {

  if ($("#tipo_viajero").select2("val") =="" || $("#tipo_viajero").select2("val") ==null ) {
   // toastr.error('debe seleccionar un tipo de clasificación');
  }else{

     $("#glosa").val("");
   
    if ($("#tipo_viajero").select2("val")=="Personal") {

      $("#glosa").val("TRANSPORTE DE PERSONAL");
     

    } else {

      $("#glosa").val(" TRANSPORTE DE MATERIAL Y/O EQUIPOS");
     
    }
    
    toastr.success('Glosa Agregada correctamente !!');
  }
  
}

//Función Listar
function listar() {
  var idproyecto=localStorage.getItem('nube_idproyecto');
  tabla=$('#tabla-hospedaje').dataTable({
    "responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/transporte.php?op=listar&idproyecto='+idproyecto,
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
        if (data[1] != "") {
          $("td", row).eq(1).addClass("text-nowrap");
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
      language: {
        lengthMenu: "Mostrar: _MENU_ registros",
        buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
        sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
      },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
  total();
}
//ver ficha tecnica
function modal_comprobante(comprobante){
  var comprobante = comprobante;
  console.log(comprobante);
var extencion = comprobante.substr(comprobante.length - 3); // => "1"
//console.log(extencion);
  $('#ver_fact_pdf').html('');
  $('#img-factura').attr("src", "");
  $('#modal-ver-comprobante').modal("show");

  if (extencion=='jpeg' || extencion=='jpg' || extencion=='png' || extencion=='webp') {
    $('#ver_fact_pdf').hide();
    $('#img-factura').show();
    $('#img-factura').attr("src", "../dist/docs/transporte/comprobante/"+comprobante);

    $("#iddescargar").attr("href","../dist/docs/transporte/comprobante/"+comprobante);

  }else{
    $('#img-factura').hide();
    
    $('#ver_fact_pdf').show();

    $('#ver_fact_pdf').html('<iframe src="../dist/docs/transporte/comprobante/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');

    $("#iddescargar").attr("href","../dist/docs/transporte/comprobante/"+comprobante);
  }


  
 $(".tooltip").removeClass("show").addClass("hidde");
}
//Función para guardar o editar

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-transporte")[0]);
 
  $.ajax({
    url: "../ajax/transporte.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla.ajax.reload(null, false);
         
				limpiar();

        $("#modal-agregar-transporte").modal("hide");
        total();

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idtransporte) {
  limpiar();
  //$("#proveedor").val("").trigger("change"); 
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-transporte").modal("show")
   //$tipo_comprobante,$nro_comprobante,$subtotal,$igv 
  $("#idproveedor").val("").trigger("change"); 
  $("#tipo_ruta").val("").trigger("change"); 
  $("#tipo_comprobante").val("").trigger("change"); 
  $("#tipo_viajero").val("").trigger("change"); 
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/transporte.php?op=mostrar", { idtransporte: idtransporte }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    precio_p=parseFloat(data.precio_parcial);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idproveedor").val(data.idproveedor).trigger("change"); 
    $("#tipo_viajero").val(data.tipo_viajero).trigger("change"); 
    $("#tipo_comprobante").val(data.tipo_comprobante).trigger("change"); 
    $("#tipo_ruta").val(data.tipo_ruta).trigger("change"); 
    $("#forma_pago").val(data.forma_de_pago).trigger("change");

    $("#idtransporte").val(data.idtransporte);
    $("#fecha_viaje").val(data.fecha_viaje); 
    $("#nro_comprobante").val(data.numero_comprobante);
    $("#glosa").val(data.glosa);

    $("#cantidad").val(data.cantidad); 
    $("#precio_unitario").val(parseFloat(data.precio_unitario).toFixed(2)); 

    $(".precio_parcial").val(precio_p.toFixed(2));
    $("#precio_parcial").val(precio_p);
  
    $(".subtotal").val(parseFloat(data.subtotal).toFixed(2));
    $("#subtotal").val(parseFloat(data.subtotal));

    $(".igv").val(parseFloat(data.igv).toFixed(2));
    $("#igv").val(data.igv);
    $("#val_igv").val(data.val_igv); 
    $("#tipo_gravada").val(data.tipo_gravada); 

    $("#ruta").val(data.ruta); 
    $("#descripcion").val(data.descripcion);
    /**-------------------------*/
    if (data.comprobante == "" || data.comprobante == null  ) {

      $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

      $("#doc1_nombre").html('');

      $("#doc_old_1").val(""); $("#doc1").val("");

    } else {

      $("#doc_old_1").val(data.comprobante); 

      $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(data.comprobante)}</i></div></div>`);
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.comprobante) == "pdf" ) {

        $("#doc1_ver").html('<iframe src="../dist/docs/transporte/comprobante/'+data.comprobante+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

      }else{
        if (
          extrae_extencion(data.comprobante) == "jpeg" || extrae_extencion(data.comprobante) == "jpg" || extrae_extencion(data.comprobante) == "jpe" ||
          extrae_extencion(data.comprobante) == "jfif" || extrae_extencion(data.comprobante) == "gif" || extrae_extencion(data.comprobante) == "png" ||
          extrae_extencion(data.comprobante) == "tiff" || extrae_extencion(data.comprobante) == "tif" || extrae_extencion(data.comprobante) == "webp" ||
          extrae_extencion(data.comprobante) == "bmp" || extrae_extencion(data.comprobante) == "svg" ) {

          $("#doc1_ver").html(`<img src="../dist/docs/transporte/comprobante/${data.comprobante}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
    }

  });
}

function ver_datos(idtransporte) {

  $("#modal-ver-transporte").modal("show")
  var comprobante=''; var btn_comprobante='';
  $.post("../ajax/transporte.php?op=verdatos", { idtransporte: idtransporte }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 

    if (data.comprobante != '') {

      if ( extrae_extencion(data.comprobante) == "pdf" ) {

        comprobante= `<iframe src="../dist/docs/transporte/comprobante/${data.comprobante}" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>`;

      }else{

        if (
          extrae_extencion(data.comprobante) == "jpeg" || extrae_extencion(data.comprobante) == "jpg" || extrae_extencion(data.comprobante) == "jpe" ||
          extrae_extencion(data.comprobante) == "jfif" || extrae_extencion(data.comprobante) == "gif" || extrae_extencion(data.comprobante) == "png" ||
          extrae_extencion(data.comprobante) == "tiff" || extrae_extencion(data.comprobante) == "tif" || extrae_extencion(data.comprobante) == "webp" ||
          extrae_extencion(data.comprobante) == "bmp" || extrae_extencion(data.comprobante) == "svg" ) {

            comprobante=`<img src="../dist/docs/transporte/comprobante/${data.comprobante}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`; 
          
        } else {
          comprobante=`<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >`;
        }  

      }

      btn_comprobante=``;
    
      btn_comprobante=`
      <div class="row">
        <div class="col-6"">
           <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/transporte/comprobante/${data.comprobante}"> <i class="fas fa-expand"></i></a>
        </div>
        <div class="col-6"">
           <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/transporte/comprobante/${data.comprobante}" download="comprobante_transporte"> <i class="fas fa-download"></i></a>
        </div>
      </div>`;


    } else {

      comprobante='Sin comprobante';
      btn_comprobante='';

    }
    
    verdatos=`                                                                            
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-hover table-bordered">        
            <tbody>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Proveedor</th>
                <td>${data.razon_social} <br>${data.ruc} </td>
                
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Descripción</th>
                <td>${data.descripcion}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Glosa</th>
                <td>${data.glosa}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Tipo clasificación</th>
                <td>${data.tipo_viajero}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Ruta</th>
                <td>${data.ruta}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Tipo ruta</th>
                  <td>${data.tipo_ruta}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Fecha</th>
                <td>${data.fecha_viaje}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Tipo pago </th>
                <td>${data.forma_de_pago}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Tipo comprobante </th>
                <td>${data.tipo_comprobante}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Cantidad</th>
                <td>${data.cantidad}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Precio unitario</th>
                <td>${parseFloat(data.precio_unitario).toFixed(2)}</td>
              </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                <th>Subtotal</th>
                <td>${parseFloat(data.subtotal).toFixed(2)}</td>
              </tr>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>IGV</th>
                <td>${parseFloat(data.igv).toFixed(2)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Total</th>
                <td>${parseFloat(data.precio_parcial).toFixed(2)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <td colspan="2" > ${comprobante} <br> ${btn_comprobante} </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>`;
  
    $("#datostransporte").html(verdatos);

  });
}

function total() {
  var idproyecto=localStorage.getItem('nube_idproyecto');
  $(".total_monto").html("");
  $.post("../ajax/transporte.php?op=total", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $(".total_monto").html('S/ '+ formato_miles(data.precio_parcial));
  });
}


//Función para desactivar registros
function desactivar(idtransporte) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar el registro?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/transporte.php?op=desactivar", { idtransporte: idtransporte }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla.ajax.reload(null, false);
        total();
      });      
    }
  });   
}

//Función para activar registros
function activar(idtransporte) {
  Swal.fire({
    title: "¿Está Seguro de  Activar el registro?",
    text: "Este proveedor tendra acceso al sistema",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/transporte.php?op=activar", { idtransporte: idtransporte }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla.ajax.reload(null, false);
        total();
      });
      
    }
  });      
}


//Función para desactivar registros
function eliminar(idtransporte) {
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
    $.post("../ajax/transporte.php?op=desactivar", { idtransporte: idtransporte }, function (e) {

      Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
  
      tabla.ajax.reload(null, false);
      total();

    });  

    }else if (result.isDenied) {

      // Eliminar
      $.post("../ajax/transporte.php?op=eliminar", { idtransporte: idtransporte }, function (e) {

        Swal.fire("Eliminado!", "Tu registro ha sido Eliminado.", "success");
    
        tabla.ajax.reload(null, false);
        total();
      });

    }

  });  
}

init();

$(function () {
  
  // Aplicando la validacion del select cada vez que cambie
  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });

  $("#form-transporte").validate({
    ignore: '.select2-input, .select2-focusser',
    rules: {
      idproveedor: { required: true },
      forma_pago: { required: true },
      tipo_comprobante: { required: true },
      fecha_viaje: { required: true },
      cantidad:{required: true},
      precio_unitario:{required: true},
      ruta:{required: true},
      descripcion:{required: true},
      val_igv: { required: true, number: true, min:0, max:1 },
      // terms: { required: true },
    },
    messages: {
      idproveedor: { required: "Por favor un proveedor", },
      forma_pago: { required: "Por favor una forma de pago", },
      tipo_comprobante: { required: "Por favor seleccionar tipo comprobante", },
      fecha_viaje: { required: "Por favor ingrese una fecha", },
      cantidad: { required: "Ingrese Cantidad.", },
      precio_unitario:  { required: "Ingresar precio unitario", },
      ruta:  { required: "Es necesario rellenar el campo ruta", },
      descripcion:  { required: "Es necesario rellenar el campo descripción", },
      val_igv: { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },

    },
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");   
    },

    submitHandler: function (e) {
      guardaryeditar(e);      
    },
  });

  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });
});






// restringimos la fecha para no elegir mañana
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
 if(dd<10){
        dd='0'+dd
    }
    if(mm<10){
        mm='0'+mm
    }
 
today = yyyy+'-'+mm+'-'+dd;
document.getElementById("fecha_viaje").setAttribute("max", today);

