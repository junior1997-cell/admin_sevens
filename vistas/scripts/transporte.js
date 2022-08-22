var tabla;
var fecha_1_r="", fecha_2_r="", id_proveedor_r="", comprobante_r="";
//Función que se ejecuta al inicio
function init() {

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Viaticos").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#mViatico").addClass("active bg-primary");

  $("#lTransporte").addClass("active");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));  
  console.log(localStorage.getItem('nube_idproyecto'));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#filtro_proveedor', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ════════════════════════════════
  $("#guardar_registro").on("click", function (e) {$("#submit-form-transporte").submit();});

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
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

  // ══════════════════════════════════════ INITIALIZE SELECT2 - FILTROS ══════════════════════════════════════
  $("#filtro_tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Selecione comprobante", allowClear: true, });
  $("#filtro_proveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });

  // Inicializar - Date picker  
  $('#filtro_fecha_inicio').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });
  $('#filtro_fecha_fin').datepicker({ format: "dd-mm-yyyy", clearBtn: true, language: "es", autoclose: true, weekStart: 0, orientation: "bottom auto", todayBtn: true });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

$('.click-btn-fecha-inicio').on('click', function (e) {$('#filtro_fecha_inicio').focus().select(); });
$('.click-btn-fecha-fin').on('click', function (e) {$('#filtro_fecha_fin').focus().select(); });

// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addImageApplication(e,$("#doc1").attr("id")) });

function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}


function limpiar() {

  $("#idtransporte").val("");
  $("#idproveedor").val("");
  $("#fecha_viaje").val(""); 
  $("#cantidad").val(""); 

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

function calc_total() {

  $(".nro_comprobante").html("Núm. Comprobante");
  $( "#num_documento" ).rules( "remove","required" );

  var cantidad         = es_numero($('#cantidad').val()) == true? parseFloat($('#cantidad').val()) : 0;
  var precio_unit         = es_numero($('#precio_unitario').val()) == true? parseFloat($('#precio_unitario').val()) : 0;
  console.log('cantidad '+cantidad);
  console.log('precio_unit '+precio_unit);

  var total         = cantidad*precio_unit;
  var val_igv       = es_numero($('#val_igv').val()) == true? parseFloat($('#val_igv').val()) : 0;
  var subtotal      = 0; 
  var igv           = 0;

  console.log(total, val_igv); console.log($('#precio_parcial').val(), $('#val_igv').val()); console.log('----------');

  if ($("#tipo_comprobante").select2("val")=="" || $("#tipo_comprobante").select2("val")==null) {
    $("#subtotal").val(redondearExp(total));
    $("#precio_parcial").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);

    $(".div_ruc").hide(); $(".div_razon_social").hide();
    $("#num_documento").val(""); $("#razon_social").val("");
    
  }else if ($("#tipo_comprobante").select2("val") =="Ninguno") {  

    $("#subtotal").val(redondearExp(total));
    $("#precio_parcial").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".nro_comprobante").html("Núm. de Operación");

    $(".div_ruc").hide(); $(".div_razon_social").hide();
    $("#num_documento").val(""); $("#razon_social").val("");

  }else if ($("#tipo_comprobante").select2("val") =="Boleta") {  

    $("#subtotal").val(redondearExp(total));
    $("#precio_parcial").val(redondearExp(total));
    $("#igv").val("0.00"); 
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)"); 
    $("#val_igv").prop("readonly",true);
    $(".nro_comprobante").html("Núm. de Operación");

    $(".div_ruc").show(); $(".div_razon_social").show();
    $("#num_documento").val(""); $("#razon_social").val("");
    $("#num_documento").rules("add", { required: true, messages: { required: "Campo requerido" } });


  }else if ($("#tipo_comprobante").select2("val") =="Factura") {  

    $("#val_igv").prop("readonly",false);    

    if (total == null || total == "") {
      $("#subtotal").val(0.00);
      $("#precio_parcial").val(0.00);
      $("#igv").val(0.00); 
      $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
    } else if (val_igv == null || val_igv == "") {  
      $("#subtotal").val(redondearExp(total));
      $("#precio_parcial").val(redondearExp(total));
      $("#igv").val(0.00);
      $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
    }else{     

      subtotal = quitar_igv_del_precio(total, val_igv, 'decimal');
      igv = total - subtotal;

      $("#subtotal").val(redondearExp(subtotal));
      $("#precio_parcial").val(redondearExp(total));
      $("#igv").val(redondearExp(igv));

      if (val_igv > 0 && val_igv <= 1) {
        $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)")
      } else {
        $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
      }    
    }

    $(".div_ruc").show(); $(".div_razon_social").show();

    $("#num_documento").rules("add", { required: true, messages: { required: "Campo requerido" } });

  } else {

    $("#subtotal").val(redondearExp(total));
    $("#precio_parcial").val(redondearExp(total));
    $("#igv").val("0.00");
    $("#val_igv").val("0.00"); 
    $("#tipo_gravada").val("NO GRAVADA"); $(".tipo_gravada").html("(NO GRAVADA)");
    $("#val_igv").prop("readonly",true);
    $(".div_ruc").hide(); $(".div_razon_social").hide();   

  }

  if (val_igv > 0 && val_igv <= 1) {

    $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)")
  } else {
    $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
  }

}

function select_comprobante() {
  if ($("#tipo_comprobante").select2("val") == "Factura") {
    $("#val_igv").prop("readonly",false);
    $("#val_igv").val(0.18); 
    $("#tipo_gravada").val('GRAVADA'); $(".tipo_gravada").html("(GRAVADA)");
  }else {
    $("#val_igv").val(0.00); 
    $("#tipo_gravada").val('NO GRAVADA'); $(".tipo_gravada").html("(NO GRAVADA)");
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
function listar(fecha_1, fecha_2, id_proveedor, comprobante) {
  fecha_1_r=fecha_1; fecha_2_r=fecha_2; id_proveedor_r=id_proveedor, comprobante_r=comprobante;

  var idproyecto=localStorage.getItem('nube_idproyecto');

  tabla=$('#tabla-hospedaje').dataTable({

    "responsive": true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,18,20,21,10,11,12,2,19,22,13,5,14,6,15,7,16,17,8], } }, 
      { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,18,20,21,10,11,12,2,19,22,13,5,14,6,15 ,7,16,17,8], } }, 
      { extend: 'pdfHtml5', footer: false, exportOptions: { columns: [0,18,20,12,19,22,13,5,14,6,15,7,8], }, orientation: 'landscape', pageSize: 'A3',  }, 
      {extend: "colvis"} ,
    ],
    "ajax":{
        url: `../ajax/transporte.php?op=listar&idproyecto=${idproyecto}&fecha_1=${fecha_1}&fecha_2=${fecha_2}&id_proveedor=${id_proveedor}&comprobante=${comprobante}`,
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
    "iDisplayLength": 10,//Paginación
    "order": [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs: [
      { targets: [10,11,12,13,14,15,16,17,18,19,20,21,22], visible: false, searchable: false, },    
    ],
  }).DataTable();
  total(fecha_1_r,fecha_2_r,id_proveedor_r,comprobante_r);
  $(tabla).ready(function () {  $('.cargando').hide(); });
}

function modal_comprobante(comprobante,tipo,numero_comprobante){

  var dia_actual = moment().format('DD-MM-YYYY');
  $(".nombre_comprobante").html(`${tipo}-${numero_comprobante}`);
  $('#modal-ver-comprobante').modal("show");
  $('#ver_fact_pdf').html(doc_view_extencion(comprobante, 'transporte', 'comprobante', '100%', '550'));

  if (DocExist(`dist/docs/transporte/comprobante/${comprobante}`) == 200) {
    $("#iddescargar").attr("href","../dist/docs/transporte/comprobante/"+comprobante).attr("download", `${tipo}-${numero_comprobante}  - ${dia_actual}`).removeClass("disabled");
    $("#ver_completo").attr("href","../dist/docs/transporte/comprobante/"+comprobante).removeClass("disabled");
  } else {
    $("#iddescargar").addClass("disabled");
    $("#ver_completo").addClass("disabled");
  }


  $('.jq_image_zoom').zoom({ on:'grab' }); 

  $(".tooltip").removeClass("show").addClass("hidde");
  
}

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-transporte")[0]);
 
  $.ajax({
    url: "../ajax/transporte.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e); 
        if (e.status == true) {

          Swal.fire("Correcto!", "El registro se guardo correctamente.", "success");

          tabla.ajax.reload(null, false);
           
          limpiar();
  
          $("#modal-agregar-transporte").modal("hide");
          total(fecha_1_r,fecha_2_r,id_proveedor_r,comprobante_r);

        }else{  
          ver_errores(e);
        } 
      } catch (err) {
        console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>');
      } 

    },


  });
}

function mostrar(idtransporte) {
  limpiar();

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-transporte").modal("show");
  $("#idproveedor").val("").trigger("change"); 
  $("#tipo_ruta").val("").trigger("change"); 
  $("#tipo_comprobante").val("").trigger("change"); 
  $("#tipo_viajero").val("").trigger("change"); 
  $("#forma_pago").val("null").trigger("change");

  $.post("../ajax/transporte.php?op=mostrar", { idtransporte: idtransporte }, function (e, status) {

    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {

      precio_p=parseFloat(e.data.precio_parcial);

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();

      $("#idproveedor").val(e.data.idproveedor).trigger("change"); 
      $("#tipo_viajero").val(e.data.tipo_viajero).trigger("change"); 
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change"); 
      $("#tipo_ruta").val(e.data.tipo_ruta).trigger("change"); 
      $("#forma_pago").val(e.data.forma_de_pago).trigger("change");

      $("#idtransporte").val(e.data.idtransporte);
      $("#fecha_viaje").val(e.data.fecha_viaje); 
      $("#nro_comprobante").val(e.data.numero_comprobante);
      $("#glosa").val(e.data.glosa);

      $("#cantidad").val(e.data.cantidad); 
      $("#precio_unitario").val(parseFloat(e.data.precio_unitario).toFixed(2)); 

      $(".precio_parcial").val(precio_p.toFixed(2));
      $("#precio_parcial").val(precio_p);
    
      $(".subtotal").val(parseFloat(e.data.subtotal).toFixed(2));
      $("#subtotal").val(parseFloat(e.data.subtotal));

      $(".igv").val(parseFloat(e.data.igv).toFixed(2));
      $("#igv").val(e.data.igv);
      $("#tipo_gravada").val(e.data.tipo_gravada); 
      $("#val_igv").val(e.data.val_igv).trigger("change"); 
 

      $("#ruta").val(e.data.ruta); 
      $("#descripcion").val(e.data.descripcion);

      if (e.data.comprobante == "" || e.data.comprobante == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

        $("#doc1_nombre").html('');

        $("#doc_old_1").val(""); $("#doc1").val("");

      } else {

        $("#doc_old_1").val(e.data.comprobante); 

        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante,'transporte', 'comprobante', '100%', '210' ));       
            
      }

      $('.jq_image_zoom').zoom({ on:'grab' }); 

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function ver_datos(idtransporte) {

  $("#modal-ver-transporte").modal("show")
  var comprobante=''; var btn_comprobante='';
  $.post("../ajax/transporte.php?op=verdatos", { idtransporte: idtransporte }, function (e, status) {

    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {

      if (e.data.comprobante != '') {
        
        comprobante =  doc_view_extencion(e.data.comprobante, 'transporte', 'comprobante', '100%');
        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/transporte/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/transporte/comprobante/${e.data.comprobante}" download="${removeCaracterEspecial(e.data.tipo_comprobante)} - ${removeCaracterEspecial(e.data.idproyecto )}"> <i class="fas fa-download"></i></a>
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
                  <td>${e.data.razon_social} <br>${e.data.ruc} </td>
                  
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                  <td>${e.data.glosa}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo clasificación</th>
                  <td>${e.data.tipo_viajero}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Ruta</th>
                  <td>${e.data.ruta}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo ruta</th>
                    <td>${e.data.tipo_ruta}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Fecha</th>
                  <td>${e.data.fecha_viaje}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo pago </th>
                  <td>${e.data.forma_de_pago}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante </th>
                  <td>${e.data.tipo_comprobante}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Cantidad</th>
                  <td>${e.data.cantidad}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Precio unitario</th>
                  <td>${parseFloat(e.data.precio_unitario).toFixed(2)}</td>
                </tr>
                  <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${parseFloat(e.data.subtotal).toFixed(2)}</td>
                </tr>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${parseFloat(e.data.igv).toFixed(2)}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Total</th>
                  <td>${parseFloat(e.data.precio_parcial).toFixed(2)}</td>
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
      $('.jq_image_zoom').zoom({ on:'grab' }); 

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function total(fecha_1_r,fecha_2_r,id_proveedor_r,comprobante_r) {
  var idproyecto=localStorage.getItem('nube_idproyecto');
  $(".total_monto").html("");
  $(".total_monto").html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);

  $.post("../ajax/transporte.php?op=total", { idproyecto: idproyecto, fecha_1:fecha_1_r, fecha_2:fecha_2_r, id_proveedor:id_proveedor_r, comprobante:comprobante_r }, function (e, status) {

    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {
      $(".total_monto").html('S/ '+ formato_miles(e.data.precio_parcial));
    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

function eliminar(idtransporte, tipo, numero) {

  crud_eliminar_papelera(
    "../ajax/transporte.php?op=desactivar",
    "../ajax/transporte.php?op=eliminar", 
    idtransporte, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del> ${tipo} N° ${numero} </del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false);total(); },
    false, 
    false, 
    false,
    false
  );

}

init();

$(function () {
  
  // Aplicando la validacion del select cada vez que cambie
  $("#idproveedor").on("change", function () { $(this).trigger("blur"); });
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
  $("#idproveedor").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });
});


    // .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

  function extrae_ruc() {
    if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { }  else{
      
      var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
      $('#ruc_proveedor').val(ruc);
    }
  }

  function cargando_search() {
    $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ...`);
  }
  
  function filtros() {  
  
    var fecha_1       = $("#filtro_fecha_inicio").val();
    var fecha_2       = $("#filtro_fecha_fin").val();  
    var id_proveedor  = $("#filtro_proveedor").select2('val');
    var comprobante   = $("#filtro_tipo_comprobante").select2('val');   
    
    var nombre_proveedor = $('#filtro_proveedor').find(':selected').text();
    var nombre_comprobante = ' ─ ' + $('#filtro_tipo_comprobante').find(':selected').text();
  
    // filtro de fechas
    if (fecha_1 == "" || fecha_1 == null) { fecha_1 = ""; } else{ fecha_1 = format_a_m_d(fecha_1) == '-'? '': format_a_m_d(fecha_1);}
    if (fecha_2 == "" || fecha_2 == null) { fecha_2 = ""; } else{ fecha_2 = format_a_m_d(fecha_2) == '-'? '': format_a_m_d(fecha_2);} 
  
    // filtro de proveedor
    if (id_proveedor == '' || id_proveedor == 0 || id_proveedor == null) { id_proveedor = ""; nombre_proveedor = ""; }
  
    // filtro de trabajdor
    if (comprobante == '' || comprobante == null || comprobante == 0 ) { comprobante = ""; nombre_comprobante = "" }
  
    $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_proveedor} ${nombre_comprobante}...`);
    //console.log(fecha_1, fecha_2, id_proveedor, comprobante);
  
    listar(fecha_1, fecha_2, id_proveedor, comprobante);
  }
  
  function extrae_ruc() {
    if ($('#idproveedor').select2("val") == null || $('#idproveedor').select2("val") == '') { }  else{    
      var ruc = $('#idproveedor').select2('data')[0].element.attributes.ruc.value; //console.log(ruc);
      $('#ruc_proveedor').val(ruc);
    }
  }

  no_select_tomorrow("#fecha_viaje");



