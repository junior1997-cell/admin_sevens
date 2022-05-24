var tabla;

//Función que se ejecuta al inicio
function init() {
  //Activamos el "aside"
  $("#lOtraFactura").addClass("active bg-primary");

  tbla_principal();

  no_select_tomorrow("#fecha_emision");

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════  
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro").on("click", function (e) { $("#submit-form-otras_facturas").submit(); });

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════
  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Seleccinar proveedor", allowClear: true, });
  $("#tipo_comprobante").select2({ theme: "bootstrap4", placeholder: "Seleccinar tipo comprobante", allowClear: true, });
  $("#forma_pago").select2({ theme: "bootstrap4", placeholder: "Seleccinar forma de pago", allowClear: true, });
  $("#glosa").select2({ theme: "bootstrap4", placeholder: "Seleccinar glosa", allowClear: true, });

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

  $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');

  $('#estado-edit-add-modal').html('Agregar:');
  $("#idotra_factura").val("");
  $("#fecha_emision").val("");  
  $("#nro_comprobante").val("");
  $("#direccion").val("");
  $("#subtotal").val("");
  $("#igv").val("");
  $("#precio_parcial").val("");
  $("#descripcion").val("");
  $("#tipo_gravada").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_pago").val("null").trigger("change");
  $("#glosa").val("null").trigger("change");

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function tbla_principal() {
  tabla = $("#tabla-otras_facturas").dataTable({
    responsive: true,
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [
      { extend: 'copyHtml5', footer: true, exportOptions: { columns: [0,2,11,12,4,5,6,7,8], } }, { extend: 'excelHtml5', footer: true, exportOptions: { columns: [0,2,11,12,4,5,6,7,8], } }, { extend: 'pdfHtml5', footer: false,  exportOptions: { columns: [0,2,11,12,4,5,6,7,8], } }, {extend: "colvis"} ,
    ],
    ajax: {
      url: "../ajax/otra_factura.php?op=tbla_principal",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: #
      if (data[0] != "") { $("td", row).eq(0).addClass("text-center"); }
      // columna: sub total
      if (data[1] != "") { $("td", row).eq(1).addClass("text-nowrap"); }
      // columna: sub total
      if (data[5] != "") { $("td", row).eq(5).addClass("text-nowrap text-right"); }
      // columna: igv
      if (data[6] != "") { $("td", row).eq(6).addClass("text-nowrap text-right"); }
      // columna: total
      if (data[7] != "") { $("td", row).eq(7).addClass("text-nowrap text-right"); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: {
        copyTitle: "Tabla Copiada",
        copySuccess: {
          _: "%d líneas copiadas",
          1: "1 línea copiada",
        },
      },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    order: [[0, "asc"]], //Ordenar (columna,orden)
    columnDefs: [
      { targets: [4], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD-MM-YYYY'), },
      { targets: [11,12], visible: false, searchable: false, },    
    ],
  }).DataTable();

  total();
}

function total() {

  $("#total_monto").html("");

  $.post("../ajax/otra_factura.php?op=total", {}, function (e, status) {
    e = JSON.parse(e);  //console.log(e);
    if (e.status == true) {
      $("#total_monto").html("S/ " + formato_miles(e.data.precio_parcial));
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

//ver ficha tecnica
function modal_comprobante(comprobante, fecha_emision) {

  var data_comprobante = ""; var url = ""; var nombre_download = "Comprobante";   

  $("#modal-ver-comprobante").modal("show");

  if (comprobante == '' || comprobante == null) {
    $(".ver-comprobante").html(`<div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times text-white"></i></button>
      <h3><i class="icon fas fa-exclamation-triangle"></i> Alert!</h3>
      No hay un documento para mostrar
    </div>`);
  }else{
    
    data_comprobante = doc_view_extencion(comprobante, 'otra_factura', 'comprobante', width='100%' );
    url = `../dist/docs/otra_factura/comprobante/${comprobante}`;
    nombre_download = `${format_d_m_a(fecha_emision)} - Comprobante`;

    $(".ver-comprobante").html(`<div class="row" >
      <div class="col-md-6 text-center">
        <a type="button" class="btn btn-warning btn-block btn-xs" href="${url}" download="${nombre_download}"> <i class="fas fa-download"></i> Descargar. </a>
      </div>
      <div class="col-md-6 text-center">
        <a type="button" class="btn btn-info btn-block btn-xs" href="${url}" target="_blank" <i class="fas fa-expand"></i> Ver completo. </a>
      </div>
      <div class="col-md-12 text-center mt-3 "><i>${nombre_download}.${extrae_extencion(comprobante)}</i></div>
      <div class="col-md-12 mt-2">     
        ${data_comprobante}
      </div>
    </div>`);
  } 

  $(".tooltip").removeClass("show").addClass("hidde");
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-otras_facturas")[0]);

  $.ajax({
    url: "../ajax/otra_factura.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);  console.log(e);
        if (e.status == true) {

          Swal.fire("Éxito!", "El registro se guardo correctamente.", "success");

          tabla.ajax.reload(null, false);

          limpiar();

          $("#modal-agregar-otras_facturas").modal("hide");

          total();

          $("#guardar_registro").html('Guardar Cambios').removeClass('disabled');

        } else {
          ver_errores(e);
        }
      } catch (err) { console.log('Error: ', err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }      
    },
    beforeSend: function () {
      $("#guardar_registro").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
    },
  });
}

function mostrar(idotra_factura) {

  limpiar();
  
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();  

  $("#tipo_comprobante").val("").trigger("change");
  $("#idproveedor").val("").trigger("change");

  $('#estado-edit-add-modal').html('Editar:');

  $("#modal-agregar-otras_facturas").modal("show");

  $.post("../ajax/otra_factura.php?op=mostrar", { idotra_factura: idotra_factura }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);   

    if (e.status) {
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
      $("#idproveedor").val(e.data.idproveedor).trigger("change");
      $("#forma_pago").val(e.data.forma_de_pago).trigger("change");
      $("#glosa").val(e.data.glosa).trigger("change");
      $("#idotra_factura").val(e.data.idotra_factura);
      $("#fecha_emision").val(e.data.fecha_emision);
      $("#nro_comprobante").val(e.data.numero_comprobante);  

      $("#subtotal").val(e.data.subtotal);
      $("#igv").val(e.data.igv);
      $("#val_igv").val(e.data.val_igv);
      $("#tipo_gravada").val(e.data.tipo_gravada);
      $("#precio_parcial").val(e.data.costo_parcial);
      $("#descripcion").val(e.data.descripcion);
      if (e.data.comprobante == "" || e.data.comprobante == null  ) {

        $("#doc1_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
  
        $("#doc1_nombre").html('');
  
        $("#doc_old_1").val(""); $("#doc1").val("");
  
      } else {
  
        $("#doc_old_1").val(e.data.comprobante); 
        $("#doc1_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(e.data.comprobante)}</i></div></div>`);
        // cargamos la imagen adecuada par el archivo
        $("#doc1_ver").html(doc_view_extencion(e.data.comprobante, 'otra_factura', 'comprobante', '100%'));      
            
      }
  
      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

// ver detallles del registro
function verdatos(idotra_factura){

  $(".tooltip").removeClass("show").addClass("hidde");

  $('#datos-otra-factura').html(`<div class="row"><div class="col-lg-12 text-center"><i class="fas fa-spinner fa-pulse fa-6x"></i><br/><br/><h4>Cargando...</h4></div></div>`);

  var imagen_perfil =''; var btn_imagen_perfil = '';
  
  var comprobante=''; var btn_comprobante = '';

  $("#modal-ver-otra-factura").modal("show");

  $.post("../ajax/otra_factura.php?op=mostrar", { 'idotra_factura': idotra_factura }, function (e, status) {

    e = JSON.parse(e);  console.log(e); 
    
    if (e.status) {

      if (e.data.comprobante == '' || e.data.comprobante == null ) {
        comprobante='Sin Ficha Técnica';
        btn_comprobante='';       
      
      } else {
        comprobante =  doc_view_extencion(e.data.comprobante, 'otra_factura', 'comprobante', '100%');
        
        btn_comprobante=`
        <div class="row">
          <div class="col-6"">
            <a type="button" class="btn btn-info btn-block btn-xs" target="_blank" href="../dist/docs/otra_factura/comprobante/${e.data.comprobante}"> <i class="fas fa-expand"></i></a>
          </div>
          <div class="col-6"">
            <a type="button" class="btn btn-warning btn-block btn-xs" href="../dist/docs/otra_factura/comprobante/${e.data.comprobante}" download="Comprobante - ${removeCaracterEspecial(e.data.tipo_comprobante)}"> <i class="fas fa-download"></i></a>
          </div>
        </div>`;      

      }     

      var retorno_html=`                                                                            
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-hover table-bordered">        
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo comprobante:</th> 
                  <td>${e.data.tipo_comprobante}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Número Comprobante:</th> 
                  <td>${e.data.numero_comprobante}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Tipo Gravada</th>
                  <td>${e.data.tipo_gravada}</td>
                </tr>                
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Glosa</th>
                  <td>${e.data.glosa}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Subtotal</th>
                  <td>${e.data.subtotal}</td>
                </tr>  
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>IGV</th>
                  <td>${e.data.igv}</td>
                </tr>            
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Costo Parcial  </th>
                  <td>${e.data.costo_parcial}</td>
                </tr>                                               
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Descripción</th>
                  <td>${e.data.descripcion}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <th>Comprobante</th>
                  <td> ${comprobante} <br>${btn_comprobante}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>`;
    
      $("#datos-otra-factura").html(retorno_html);

    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );
}

//Función para desactivar registros
function eliminar(idotra_factura, nombre ) {
  crud_eliminar_papelera(
    "../ajax/otra_factura.php?op=desactivar",
    "../ajax/otra_factura.php?op=eliminar", 
    idotra_factura, 
    "!Elija una opción¡", 
    `<b class="text-danger"><del>${nombre}</del></b> <br> En <b>papelera</b> encontrará este registro! <br> Al <b>eliminar</b> no tendrá acceso a recuperar este registro!`, 
    function(){ sw_success('♻️ Papelera! ♻️', "Tu registro ha sido reciclado." ) }, 
    function(){ sw_success('Eliminado!', 'Tu registro ha sido Eliminado.' ) }, 
    function(){ tabla.ajax.reload(null, false); total(); },
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

  $("#form-otras_facturas").validate({
    rules: {
      idproveedor:    { required: true },
      forma_pago:     { required: true },
      tipo_comprobante:{ required: true },
      fecha_emision:  { required: true },
      precio_parcial: { required: true },
      val_igv:        { required: true, number: true, min:0, max:1 },
      // terms: { required: true },
    },
    messages: {
      idproveedor:    { required: "Campo requerido", },
      forma_pago:     { required: "Campo requerido", },
      tipo_comprobante:{ required: "Campo requerido", },
      fecha_emision:  { required: "Campo requerido", },
      precio_parcial: { required: "Campo requerido", }, 
      val_igv:        { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },
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

//segun tipo de comprobante
function comprob_factura() {

  var precio_parcial = $("#precio_parcial").val();  

  if ($("#tipo_comprobante").select2("val") == "" || $("#tipo_comprobante").select2("val") == null) {

    $(".nro_comprobante").html("Núm. Comprobante");

    $("#val_igv").val(0);
    $("#val_igv").prop("readonly",true);

    if (precio_parcial == null || precio_parcial == "") {
      $("#subtotal").val(0);
      $("#igv").val(0); 
      $("#tipo_gravada").val('NO GRAVADA');   
    } else {
      $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
      $("#igv").val(0);    
      $("#tipo_gravada").val('NO GRAVADA');
    }   

  } else {

    if ($("#tipo_comprobante").select2("val") == "Ninguno") { 

      $("#val_igv").val(0);
      $("#val_igv").prop("readonly",true);

      if (precio_parcial == null || precio_parcial == "") {
        $("#subtotal").val(0);
        $("#igv").val(0);  
        $("#tipo_gravada").val('NO GRAVADA');  
      } else {
        $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
        $("#igv").val(0);  
        $("#tipo_gravada").val('NO GRAVADA');  
      }   

    } else {

      if ($("#tipo_comprobante").select2("val") == "Factura") {

        calculandototales_fact();   
    
      } else { 
        
        if ($("#tipo_comprobante").select2("val") == "Boleta") {

          $(".nro_comprobante").html("Núm. Comprobante");

          $("#val_igv").val(0);
          $("#val_igv").prop("readonly",true);

          if (precio_parcial == null || precio_parcial == "") {
            $("#subtotal").val(0);
            $("#igv").val(0); 
            $("#tipo_gravada").val('NO GRAVADA');   
          } else {
            var subtotal = 0;
            var igv = 0;
        
            $("#subtotal").val("");
            $("#igv").val("");

            $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
            $("#igv").val(0);   
            $("#tipo_gravada").val('NO GRAVADA');  
          } 
            
        } else {
                 
        $(".nro_comprobante").html("Núm. Comprobante");

        if (precio_parcial == null || precio_parcial == "") {
          $("#subtotal").val(0);
          $("#igv").val(0);    
        } else {
          $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
          $("#igv").val(0);    
        } 
          
        }

      }
    }
  } 
}
function validando_igv() {

  if ($("#tipo_comprobante").select2("val") == "Factura") {

    $("#val_igv").val(0.18); 

  }else {

    $("#val_igv").val(0); 

  }
  
}

function calculandototales_fact() {
          
  $(".nro_comprobante").html("Núm. Comprobante");

  precio_parcial=$("#precio_parcial").val();

  var val_igv = $('#val_igv').val();

  $("#val_igv").prop("readonly",false);

  if (precio_parcial == null || precio_parcial == "") {

    $("#subtotal").val(0);
    $("#igv").val(0); 

  } else {

    var subtotal = 0;
    var igv = 0;

    if (val_igv == null || val_igv == "") {
      
      $("#subtotal").val(precio_parcial);
      $("#igv").val(0);
      $("#tipo_gravada").val('NO GRAVADA');
    }else{

      $("#subtotal").val("");
      $("#igv").val("");

      subtotal = quitar_igv_del_precio(precio_parcial, val_igv, 'decimal');
       //precio_parcial /(parseFloat(val_igv)+1);
      igv = precio_parcial - subtotal;

      $("#subtotal").val(subtotal.toFixed(2));
      $("#igv").val(igv.toFixed(2));

      $("#tipo_gravada").val('GRAVADA');

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