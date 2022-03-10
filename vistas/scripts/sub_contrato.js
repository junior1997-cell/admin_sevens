var tabla;
var tabla_pagos_proveedor;
var tabla_pagos_detraccion;

var cuenta_bancaria;
var cuenta_detracciones;
var totattotal;
var monto_total_dep;
var id_subcontrato;

//Función que se ejecuta al inicio
function init() {

  //Activamos el "aside"
  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

  $("#lSubContrato").addClass("active bg-primary");

  $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));

  //Mostramos los proveedores
  $.post("../ajax/sub_contrato.php?op=select2Proveedor", function (r) { $("#idproveedor").html(r); });

  listar();

  $("#guardar_registro").on("click", function (e) {$("#submit-form-agregar-sub-contrato").submit();});

  //Initialize Select2 idproveedor
  $("#idproveedor").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar un proveedor",
    allowClear: true,
  });

  //Initialize Select2 forma_de_pago
  $("#forma_de_pago").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar forma de pago",
    allowClear: true,
  });

  //Initialize Select2 tipo_comprobante
  $("#tipo_comprobante").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar tipo comprobante",
    allowClear: true,
  });

  $("#idproveedor").val("null").trigger("change");
  $("#forma_de_pago").val("null").trigger("change");
  $("#tipo_comprobante").val("null").trigger("change");

  //---::::::::::::::P A G O S ::::::::::::::::
  //Mostramos los BANCOS
  $.post("../ajax/sub_contrato.php?op=select2Banco", function (r) { $("#banco_pago").html(r); });

  $("#guardar_registro_pago").on("click", function (e) {$("#submit-form-pago").submit();});
  //Initialize Select2 forma_pago
  $("#forma_pago").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar forma de pago",
    allowClear: true,
  });tipo_pago

  //Initialize Select2 tipo_pago
  $("#tipo_pago").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar forma de pago",
    allowClear: true,
  });


  // Formato para telefono
  $("[data-mask]").inputmask();


}
function regresar(estado) {

  if (estado==1) {

    $('#tbl-facturas').hide();
    $('#add_agregar_facturas').hide();
    $('#tbl-pagos').hide();
    $('#regresar').hide();
    $('#add_agregar_pago').hide();

    $('#add_sub_contrato').show();
    $('#tbl-principal').show();
    
  } else {

    if (estado==2) {

    $('#add_sub_contrato').hide();
    $('#tbl-principal').hide();
    $('#add_agregar_facturas').hide();
    $('#tbl-facturas').hide();

    $('#tbl-pagos').show();
    $('#regresar').show();
    $('#add_agregar_pago').show();

    }
    
  }

  
}
// abrimos el navegador de archivos
$("#doc1_i").click(function() {  $('#doc1').trigger('click'); });
$("#doc1").change(function(e) {  addDocs(e,$("#doc1").attr("id")) });

// abrimos el navegador de archivos
$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addDocs(e,$("#doc2").attr("id")) });

// Eliminamos el doc 1
function doc1_eliminar() {

	$("#doc1").val("");

	$("#doc1_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc1_nombre").html("");
}

// Eliminamos el doc 2
function doc2_eliminar() {

	$("#doc2").val("");

	$("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

	$("#doc2_nombre").html("");
}

//:::::::::::...CRUD SUB CONTRATO....::::::::::::..:::::::::::

function limpiar() {

  $("#idsubcontrato").val("");
  $("#fecha_subcontrato").val(""); 
  $("#numero_comprobante").val("");

  $("#subtotal").val("");
  $("#igv").val("");
  $("#val_igv").val("");
  $("#costo_parcial").val("");

  $("#descripcion").val("");

  $("#doc_old_1").val("");
  $("#doc1").val("");  
  $('#doc1_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc1_nombre').html("");

  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprobante").val("null").trigger("change");
  $("#forma_de_pago").val("null").trigger("change");

}

function comprob_factura() {

  var precio_parcial = $("#costo_parcial").val();  

  if ($("#tipo_comprobante").select2("val") == "" || $("#tipo_comprobante").select2("val") == null) {

    $(".nro_comprobante").html("Núm. Comprobante");

    $("#val_igv").val(0);
    $("#val_igv").prop("readonly",true);

    if (precio_parcial == null || precio_parcial == "") {

      $("#subtotal").val(0);
      $("#igv").val(0);   

    } else {

      $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
      $("#igv").val(0);  

    }   

  } else {

    if ($("#tipo_comprobante").select2("val") == "Ninguno") { 

      $(".nro_comprobante").html("Núm. de Operación");

      $("#val_igv").val(0);
      $("#val_igv").prop("readonly",true);

      if (precio_parcial == null || precio_parcial == "") {

        $("#subtotal").val(0);
        $("#igv").val(0);    

      } else {

        $("#subtotal").val(parseFloat(precio_parcial).toFixed(2));
        $("#igv").val(0);  
         
      }   

    } else {

      if ($("#tipo_comprobante").select2("val") == "Factura") {

        
        calculandototales_fact();      
    
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

function validando_igv() {

  if ($("#tipo_comprobante").select2("val") == "Factura") {

    $("#val_igv").val(0.18); 

  }else {

    $("#val_igv").val(0); 

  }
  
}

function calculandototales_fact() {
          
  $(".nro_comprobante").html("Núm. Comprobante");

  precio_parcial=$("#costo_parcial").val();

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

    }else{

      $("#subtotal").val("");
      $("#igv").val("");

      subtotal = quitar_igv_del_precio(precio_parcial, val_igv, 'decimal');
       //precio_parcial /(parseFloat(val_igv)+1);
      igv = precio_parcial - subtotal;

      $("#subtotal").val(subtotal.toFixed(2));
      $("#igv").val(igv.toFixed(2));

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

function listar() {
  var idproyecto=localStorage.getItem('nube_idproyecto');
  tabla=$('#tabla-sub-contratos').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/sub_contrato.php?op=listar&idproyecto='+idproyecto,
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
        if (data[4] != '') {
          $("td", row).eq(4).addClass('text-nowrap text-right');
        }
        // columna: igv
        if (data[5] != '') {
          $("td", row).eq(5).addClass('text-nowrap text-right');
        }
        // columna: total
        if (data[6] != '') {
          $("td", row).eq(6).addClass('text-nowrap text-right');
        }

        if (data[8] != "") {

          var num = parseFloat(quitar_formato_miles(data[10])); console.log(num);
  
          if (num > 0) {
            $("td", row).eq(8).addClass('bg-warning text-right');
          } else if (num == 0) {
            $("td", row).eq(8).addClass('bg-success text-right');            
          } else if (num < 0) {
            $("td", row).eq(8).addClass('bg-danger text-right');
          }
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
    "order": [[ 0, "asc" ]],//Ordenar (columna,orden)
    columnDefs:[
      { targets: [2], visible: false, searchable: false, },
      { targets: [3], visible: false, searchable: false, }
    ],

  }).DataTable();
  total();
}

function modal_comprobante(comprobante){
  var comprobante = comprobante;
   
  var extencion = comprobante.substr(comprobante.length - 3); // => "1"
  //console.log(extencion);
  $('#ver_fact_pdf').html('');
  $('#img-factura').attr("src", "");
  $('#modal-ver-comprobante').modal("show");

  if (extencion=='jpeg' || extencion=='jpg' || extencion=='png' || extencion=='webp') {
    $('#ver_fact_pdf').hide();
    $('#img-factura').show();
    $('#img-factura').attr("src", "../dist/docs/sub_contrato/comprobante_subcontrato/"+comprobante);

    $("#iddescargar").attr("href","../dist/docs/sub_contrato/comprobante_subcontrato/"+comprobante);

  }else{
    $('#img-factura').hide();
    
    $('#ver_fact_pdf').show();

    $('#ver_fact_pdf').html('<iframe src="../dist/docs/sub_contrato/comprobante_subcontrato/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');

    $("#iddescargar").attr("href","../dist/docs/sub_contrato/comprobante_subcontrato/"+comprobante);
  }

}

function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-agregar-sub-contrato")[0]);
 
  $.ajax({
    url: "../ajax/sub_contrato.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Registrado correctamente')				 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-sub-contrato").modal("hide");
        total();

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idsubcontrato) {
  limpiar();
  //$("#proveedor").val("").trigger("change"); 
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-sub-contrato").modal("show")

  $("#idproveedor").val("").trigger("change"); 
  $("#tipo_comprobante").val("").trigger("change"); 
  $("#forma_de_pago").val("null").trigger("change");

  $.post("../ajax/sub_contrato.php?op=mostrar", { idsubcontrato: idsubcontrato }, function (data, status) {

    data = JSON.parse(data); //console.log('..........'); console.log(data);  

    precio_p=parseFloat(data.precio_parcial);

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

    $("#idproyecto").val(data.idproyecto).trigger("change"); 
    $("#idproveedor").val(data.idproveedor).trigger("change"); 
    $("#tipo_comprobante").val(data.tipo_comprobante).trigger("change"); 
    $("#forma_de_pago").val(data.forma_de_pago).trigger("change");

    $("#idsubcontrato").val(data.idsubcontrato);
    $("#fecha_subcontrato").val(data.fecha_subcontrato); 
    $("#numero_comprobante").val(data.numero_comprobante);

    $("#costo_parcial").val(data.costo_parcial);
    $("#subtotal").val(parseFloat(data.subtotal));
    $("#igv").val(data.igv);
    $("#val_igv").val(data.val_igv);
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

        $("#doc1_ver").html('<iframe src="../dist/docs/sub_contrato/comprobante_subcontrato/'+data.comprobante+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

      }else{
        if (
          extrae_extencion(data.comprobante) == "jpeg" || extrae_extencion(data.comprobante) == "jpg" || extrae_extencion(data.comprobante) == "jpe" ||
          extrae_extencion(data.comprobante) == "jfif" || extrae_extencion(data.comprobante) == "gif" || extrae_extencion(data.comprobante) == "png" ||
          extrae_extencion(data.comprobante) == "tiff" || extrae_extencion(data.comprobante) == "tif" || extrae_extencion(data.comprobante) == "webp" ||
          extrae_extencion(data.comprobante) == "bmp" || extrae_extencion(data.comprobante) == "svg" ) {

          $("#doc1_ver").html(`<img src="../dist/docs/sub_contrato/comprobante_subcontrato/${data.comprobante}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc1_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
    }

  });
}

function ver_datos(idsubcontrato) {

  $("#modal-ver-datos-sub-contrato").modal("show")

  $.post("../ajax/sub_contrato.php?op=verdatos", { idsubcontrato: idsubcontrato }, function (data, status) {

    data = JSON.parse(data); 

    var img_doc = "";  var comprobante=data.comprobante;
       
    var extencion = comprobante.substr(comprobante.length - 3); // => "1"

    if (extencion=='jpeg' || extencion=='jpg' || extencion=='png' || extencion=='webp') {

      img_doc='<img onerror="this.src="../dist/img/default/img_defecto.png";" src="../dist/docs/sub_contrato/comprobante_subcontrato/'+comprobante+'" class="img-thumbnail" style="cursor: pointer !important;" width="auto"/>';
               
    } else {
      
      img_doc='<iframe src="../dist/docs/sub_contrato/comprobante_subcontrato/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="350"></iframe>'
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
                <th>Fecha</th>
                <td>${data.fecha_subcontrato}</td>
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
                <th>Subtotal</th>
                <td>${parseFloat(data.subtotal).toFixed(2)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>IGV</th>
                <td>${parseFloat(data.igv).toFixed(2)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Total</th>
                <td>${parseFloat(data.costo_parcial).toFixed(2)}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <td colspan="2" >${img_doc}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>`;
  
    $("#datos-sub-contrato").html(verdatos);

  });
}

function total() {
  var idproyecto=localStorage.getItem('nube_idproyecto');
  $(".total_monto").html("");
  $.post("../ajax/sub_contrato.php?op=total", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    $(".total_monto").html('S/. '+ formato_miles(data.precio_parcial));
  });
}

function desactivar(idsubcontrato) {
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
      $.post("../ajax/sub_contrato.php?op=desactivar", { idsubcontrato: idsubcontrato }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla.ajax.reload();
        total();
      });      
    }
  });   
}

function activar(idsubcontrato) {
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
      $.post("../ajax/sub_contrato.php?op=activar", { idsubcontrato: idsubcontrato }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla.ajax.reload();
        total();
      });
      
    }
  });      
}

function eliminar(idsubcontrato) {

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

      $.post("../ajax/sub_contrato.php?op=desactivar", { idsubcontrato: idsubcontrato }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla.ajax.reload();
        total();
      }); 

    }else if (result.isDenied) {


      $.post("../ajax/sub_contrato.php?op=eliminar", { idsubcontrato: idsubcontrato }, function (e) {

        Swal.fire("Eliminado!", "Tu registro ha sido Eliminado.", "success");
    
        tabla.ajax.reload();
        total();
      });

    }

  });
}

//:::::::::::... FIN CRUD SUB CONTRATO....::::::::::::..:::::::::::... FIN CRUD SUB CONTRATO....::::::::::::..

//:::::::::::...  CRUD PAGOS ....::::::::::::..:::::::::::...

function listar_pagos(idsubcontrato, total_pago, total_deposito) {
 
  regresar(2);

  id_subcontrato=idsubcontrato;

  totattotal=total_pago; monto_total_dep=total_deposito;

  $('#total_apagar').html('S/. '+formato_miles(total_pago));

  tabla_pagos_proveedor=$('#tabla-pagos-proveedor').dataTable({
   "responsive": true,
   lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
   "aProcessing": true,//Activamos el procesamiento del datatables
   "aServerSide": true,//Paginación y filtrado realizados por el servidor
   dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
   buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
   "ajax":{
       url: '../ajax/sub_contrato.php?op=listar_pagos_proveedor&idsubcontrato='+idsubcontrato,
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
   "iDisplayLength": 5,//Paginación
   "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();
 
  tabla_pagos_detraccion=$('#tabla-pagos-detraccion').dataTable({
    "responsive": true,
    lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/sub_contrato.php?op=listar_pagos_detraccion&idsubcontrato='+idsubcontrato,
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
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();

  datos_proveedor(idsubcontrato);
  total_pagos_proveedor(id_subcontrato);
  total_pagos_detraccion(id_subcontrato);
}

function datos_proveedor(idsubcontrato) {

  $("#h4_mostrar_beneficiario").html("");

  $("#banco_pago").val("").trigger("change");
  $("#tipo_pago").val('Proveedor').trigger("change");

  $.post("../ajax/sub_contrato.php?op=datos_proveedor", { idsubcontrato: idsubcontrato }, function (data, status) {
    data = JSON.parse(data); console.log(data);

    $("#idsubcontrato_pago").val(data.idsubcontrato );
    $("#beneficiario_pago").val(data.razon_social);
    $("#h4_mostrar_beneficiario").html(data.razon_social);
    $("#banco_pago").val(data.idbancos).trigger("change");
    $("#tipo_pago").val('Proveedor').trigger("change");
    $("#titular_cuenta_pago").val(data.titular_cuenta);

     cuenta_bancaria=data.cuenta_bancaria;
     cuenta_detracciones=data.cuenta_detracciones;

    if ($("#tipo_pago").select2("val") == "Proveedor") {$("#cuenta_destino_pago").val(""); $("#cuenta_destino_pago").val(cuenta_bancaria); }
  
  });

}

//captura_opicion tipopago
function captura_op() {

  $("#cuenta_destino_pago").val("");

  if ($("#tipo_pago").select2("val") == "Proveedor") {
    $("#cuenta_destino_pago").val("");
    $("#cuenta_destino_pago").val(cuenta_bancaria);
  }

  if ($("#tipo_pago").select2("val") == "Detraccion") {
    $("#cuenta_destino_pago").val("");
    $("#cuenta_destino_pago").val(cuenta_detracciones);
  }

}

//validando excedentes
function validando_excedentes() {

  var monto_entrada = $("#monto_pago").val();

  var total_suma = parseFloat(monto_total_dep) + parseFloat(monto_entrada);

  if (total_suma > totattotal) { 

    toastr.error("ERROR monto excedido al total del monto a pagar!"); 

  } else {

    toastr.success("Monto Aceptado.");

  }

}

//Función limpiar pagos
function limpiar_pagos() {
  $("#idpago_subcontrato").val("");
  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");
  $("#monto_pago").val("");
  $("#numero_op_pago").val("");
  $("#cuenta_destino_pago").val("");
  $("#descripcion_pago").val("");
  $("#fecha_pago").val("");
  $("#numero_op_pago").val("");


  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");

  // Limpiamos las validaciones
  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

//Guardar y editar
function guardaryeditar_pago(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-add-pago-subcontrato")[0]);

  $.ajax({
    url: "../ajax/sub_contrato.php?op=guardaryeditar_pago",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      if (datos == "ok") {
        toastr.success("servicio registrado correctamente");

        $("#modal-agregar-pago").modal("hide");

        tabla.ajax.reload();
        tabla_pagos_proveedor.ajax.reload();
        tabla_pagos_detraccion.ajax.reload();
        limpiar_pagos();
        total_pagos_proveedor(id_subcontrato);
        total_pagos_detraccion(id_subcontrato);

      } else {
        toastr.error(datos);
      }
    },
  });
}

function mostrar_pagos(idpago_subcontrato) {

  limpiar_pagos();

  $("#modal-agregar-pago").modal("show");

  $("#h4_mostrar_beneficiario").html("");
  $("#beneficiario_pago").val("");
  $("#idpago_subcontrato").val("");
  $("#idsubcontrato_pago").val("");

  $("#banco_pago").val("").trigger("change");
  $("#forma_pago").val("").trigger("change");
  $("#tipo_pago").val("").trigger("change");

  $.post("../ajax/sub_contrato.php?op=mostrar_pagos", { idpago_subcontrato: idpago_subcontrato }, function (data, status) {

    data = JSON.parse(data); //console.log('..........'); console.log(data);  

    precio_p=parseFloat(data.precio_parcial);

    $("#forma_pago").val(data.forma_pago).trigger("change");
    $("#tipo_pago").val(data.tipo_pago).trigger("change");
    $("#banco_pago").val(data.idbancos).trigger("change");

    $("#idpago_subcontrato").val(data.idpago_subcontrato);
    $("#idsubcontrato_pago").val(data.idsubcontrato);
    $("#beneficiario_pago").val(data.beneficiario);
    $("#h4_mostrar_beneficiario").html(data.beneficiario);
    $("#cuenta_destino_pago").val(data.cuenta_destino);
    $("#titular_cuenta_pago").val(data.titular_cuenta);
    $("#fecha_pago").val(data.fecha_pago);
    $("#monto_pago").val(data.monto);
    $("#numero_op_pago").val(data.numero_operacion);
    $("#descripcion_pago").val(data.descripcion);

    /**-------------------------*/
    if (data.comprobante == "" || data.comprobante == null  ) {

      $("#doc2_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

      $("#doc2_nombre").html('');

      $("#doc_old_2").val(""); $("#doc1").val("");

    } else {

      $("#doc_old_2").val(data.comprobante); 

      $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Baucher.${extrae_extencion(data.comprobante)}</i></div></div>`);
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.comprobante) == "pdf" ) {

        $("#doc2_ver").html('<iframe src="../dist/docs/sub_contrato/comprobante_pago/'+data.comprobante+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

      }else{
        if (
          extrae_extencion(data.comprobante) == "jpeg" || extrae_extencion(data.comprobante) == "jpg" || extrae_extencion(data.comprobante) == "jpe" ||
          extrae_extencion(data.comprobante) == "jfif" || extrae_extencion(data.comprobante) == "gif" || extrae_extencion(data.comprobante) == "png" ||
          extrae_extencion(data.comprobante) == "tiff" || extrae_extencion(data.comprobante) == "tif" || extrae_extencion(data.comprobante) == "webp" ||
          extrae_extencion(data.comprobante) == "bmp" || extrae_extencion(data.comprobante) == "svg" ) {

          $("#doc2_ver").html(`<img src="../dist/docs/sub_contrato/comprobante_pago/${data.comprobante}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc2_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
    }

  });
}

function total_pagos_proveedor(id_subcontrato) {
  //limpiamos
  $("#t_proveedor").html("");
  $("#t_provee_porc").html("");

  var monto_pagar_prov= ((totattotal * 90) / 100);

  $("#t_proveedor").html(formato_miles(monto_pagar_prov.toFixed(2)));
  $("#t_provee_porc").html(90);

  var  porcentaj_saldo=0; var porcentaj_deposito_a_la_fecha=0; var total_saldo=0;

  $(".monto_total_deposito_prov").html("");
  $(".porcnt_deposito_prov").html("");
  $("#saldo_prov").html("");
  $("#porcnt_sald_prov").html("");

  $.post("../ajax/sub_contrato.php?op=total_pagos_prov", { idsubcontrato: id_subcontrato }, function (data, status) {

    data = JSON.parse(data);  //console.log(data); 
    
    if (data.monto_parcial_deposito==null || data.monto_parcial_deposito=="" || data.monto_parcial_deposito==0 ) {

      $(".monto_total_deposito_prov").html('S/. 0.00');

      $(".porcnt_deposito_prov").html("0 %");
      
      $("#saldo_prov").html('S/. '+formato_miles(monto_pagar_prov.toFixed(2)));

      $("#porcnt_sald_prov").html('100 %');
      
    } else {
      
    $(".monto_total_deposito_prov").html('S/. '+ formato_miles(data.monto_parcial_deposito));

    $(".porcnt_deposito_prov").html(((data.monto_parcial_deposito * 100) / monto_pagar_prov).toFixed(2) + " %");

    porcentaj_deposito_a_la_fecha= ((data.monto_parcial_deposito * 100) / monto_pagar_prov).toFixed(4);

    total_saldo=(parseFloat(monto_pagar_prov)- parseFloat(data.monto_parcial_deposito));

    porcentaj_saldo=((total_saldo*porcentaj_deposito_a_la_fecha)/data.monto_parcial_deposito);

    $("#saldo_prov").html('S/. '+total_saldo.toFixed(2));

    $("#porcnt_sald_prov").html(porcentaj_saldo.toFixed(2)+' %');
      
    }

    //calculando el porcentaje segun lo depositado


  });

}

function total_pagos_detraccion(id_subcontrato) {

  $("#t_detaccion").html("");
  $("#t_detacc_porc").html("");

  var monto_pagar_detracc= ((totattotal * 10) / 100);

  $("#t_detaccion").html(formato_miles(monto_pagar_detracc.toFixed(2)));
  $("#t_detacc_porc").html(10);

  var  porcentaj_saldo=0; var porcentaj_deposito_a_la_fecha=0; var total_saldo=0;

  $(".monto_total_deposito_detracc").html("");
  $(".porcent_detracc").html("");
  $("#saldo_detracc").html("");
  $("#porcnt_saldo_detracc").html("");


  $.post("../ajax/sub_contrato.php?op=total_pagos_detrac", { idsubcontrato: id_subcontrato }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  
    
    if (data.monto_parcial_deposito==null || data.monto_parcial_deposito==""  || data.monto_parcial_deposito==0) {

      $(".monto_total_deposito_detracc").html('S/. 0.00');

      $(".porcent_detracc").html("0 %");
      
      $("#saldo_detracc").html('S/. '+formato_miles(monto_pagar_detracc.toFixed(2)));

      $("#porcnt_saldo_detracc").html('100 %');
      
    } else {
      
    $(".monto_total_deposito_detracc").html('S/. '+ formato_miles(data.monto_parcial_deposito));

    $(".porcent_detracc").html(((data.monto_parcial_deposito * 100) / monto_pagar_detracc).toFixed(2) + " %");

    porcentaj_deposito_a_la_fecha= ((data.monto_parcial_deposito * 100) / monto_pagar_detracc).toFixed(4);

    total_saldo=(parseFloat(monto_pagar_detracc)- parseFloat(data.monto_parcial_deposito));

    porcentaj_saldo=((total_saldo*porcentaj_deposito_a_la_fecha)/data.monto_parcial_deposito);

    $("#saldo_detracc").html('S/. '+total_saldo.toFixed(2));

    $("#porcnt_saldo_detracc").html(porcentaj_saldo.toFixed(2)+' %');
      
    }

  });

}

function desactivar_pagos(idpago_subcontrato) {
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
      $.post("../ajax/sub_contrato.php?op=desactivar_pagos", { idpago_subcontrato: idpago_subcontrato }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");
    
        tabla.ajax.reload();        
        tabla_pagos_proveedor.ajax.reload();
        tabla_pagos_detraccion.ajax.reload();
        total_pagos_proveedor(id_subcontrato);
        total_pagos_detraccion(id_subcontrato);
      });      
    }
  });   
}

function activar_pagos(idpago_subcontrato) {
  Swal.fire({
    title: "¿Está Seguro de  Activar el registro?",
    text: "Registro  activado",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/sub_contrato.php?op=activar_pagos", { idpago_subcontrato: idpago_subcontrato }, function (e) {

        Swal.fire("Activado!", "Tu registro ha sido activado.", "success");

        tabla.ajax.reload();
        tabla_pagos_proveedor.ajax.reload();
        tabla_pagos_detraccion.ajax.reload();
        total_pagos_proveedor(id_subcontrato);
        total_pagos_detraccion(id_subcontrato);
      });
      
    }
  });      
}

function eliminar_pagos(idpago_subcontrato) {
  
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

      $.post("../ajax/sub_contrato.php?op=desactivar_pagos", { idpago_subcontrato: idpago_subcontrato }, function (e) {

        Swal.fire("Desactivado!", "Tu registro ha sido desactivado.", "success");

        tabla.ajax.reload();
        tabla_pagos_proveedor.ajax.reload();
        tabla_pagos_detraccion.ajax.reload();
        total_pagos_proveedor(id_subcontrato);
        total_pagos_detraccion(id_subcontrato);

      });

    }else if (result.isDenied) {

      $.post("../ajax/sub_contrato.php?op=eliminar_pagos", { idpago_subcontrato: idpago_subcontrato }, function (e) {

        Swal.fire("Eliminado!", "Tu registro ha sido Eliminado.", "success");

        tabla.ajax.reload();
        tabla_pagos_proveedor.ajax.reload();
        tabla_pagos_detraccion.ajax.reload();
        total_pagos_proveedor(id_subcontrato);
        total_pagos_detraccion(id_subcontrato);

      });  

    }
    
  });
}

function ver_modal_vaucher_pagos(comprobante){
  var comprobante = comprobante;
  console.log(comprobante);
var extencion = comprobante.substr(comprobante.length - 3); // => "1"
//console.log(extencion);
  $('#ver_fact_pdf').html('');
  $('#img-factura').attr("src", "");
  $('#modal-ver-comprobante').modal("show");

  if (extencion=='jpeg' || extencion=='jpg' || extencion=='png' || extencion=='webp') {

    $('#ver_fact_pdf').hide();    $('#img-factura').show();

    $('#img-factura').attr("src", "../dist/docs/sub_contrato/comprobante_pago/"+comprobante);

    $("#iddescargar").attr("href","../dist/docs/sub_contrato/comprobante_pago/"+comprobante);

  }else{

    $('#img-factura').hide();  $('#ver_fact_pdf').show();

    $('#ver_fact_pdf').html('<iframe src="../dist/docs/sub_contrato/comprobante_pago/'+comprobante+'" frameborder="0" scrolling="no" width="100%" height="350"></iframe>');

    $("#iddescargar").attr("href","../dist/docs/sub_contrato/comprobante_pago/"+comprobante);
  }

}

//:::::::::::... FIN CRUD PAGOS....::::::::::::..:::::::::::

//:::::::::::...  CRUD FACTURAS....::::::::::::..:::::::::::
function listar_facturas() {
  regresar(3);
  
}
//:::::::::::... FIN CRUD FACTURAS....::::::::::::..:::::::::::
init();
// funcion para validar antes de guardar sub contrato
$(function () {
  
  $.validator.setDefaults({

    submitHandler: function (e) {
        guardaryeditar(e);
      
    },
  });
  
  // Aplicando la validacion del select cada vez que cambie

  $("#idproveedor").on("change", function () { $(this).trigger("blur"); });
  $("#forma_de_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_comprobante").on("change", function () { $(this).trigger("blur"); });

  $("#form-agregar-sub-contrato").validate({
    rules: {
      idproveedor: { required: true },
      forma_de_pago: { required: true },
      tipo_comprobante: { required: true },
      fecha_subcontrato: { required: true },
      costo_parcial:{required: true},
      val_igv: { required: true, number: true, min:0, max:1 },
    },
    messages: {
      idproveedor: {
        required: "Por favor un proveedor", 
      },
      forma_de_pago: {
        required: "Por favor una forma de pago", 
      },
      tipo_comprobante: {
        required: "Por favor seleccionar tipo comprobante", 
      },
      fecha_subcontrato: {
        required: "Por favor ingrese una fecha", 
      },
      costo_parcial: {
        required: "Ingrese costo_parcial.",
      },
      val_igv: { required: "Campo requerido", number: 'Ingrese un número', min:'Mínimo 0', max:'Maximo 1' },

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
  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#idproveedor").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#forma_de_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_comprobante").rules("add", { required: true, messages: { required: "Campo requerido" } });
  
});
//funcion para validar antes de guardar pago
$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {

      guardaryeditar_pago(e);

    },

  });
  
  // Aplicando la validacion del select cada vez que cambie

  $("#forma_pago").on("change", function () { $(this).trigger("blur"); });
  $("#tipo_pago").on("change", function () { $(this).trigger("blur"); });
  $("#banco_pago").on("change", function () { $(this).trigger("blur"); });

  $("#form-add-pago-subcontrato").validate({
    rules: {

      forma_pago: { required: true },
      tipo_pago: { required: true },
      banco_pago: { required: true },
      fecha_pago: { required: true },
      monto_pago: { required: true },
      numero_op_pago: { minlength: 1 },
      descripcion_pago: { minlength: 1 },
      titular_cuenta_pago: { minlength: 1 },
    },
    messages: {

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
  //agregando la validacion del select  ya que no tiene un atributo name el plugin
  $("#forma_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#tipo_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  $("#banco_pago").rules("add", { required: true, messages: { required: "Campo requerido" } });
  
});

//:::::::::::... FUNCIONES GENERALES....::::::::::::..
function extrae_extencion(filename) {
  return filename.split('.').pop();
}

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
document.getElementById("fecha_subcontrato").setAttribute("max", today);

//formato miles
function formato_miles(num) {
  if (!num || num == 'NaN') return '0.00';
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
  return (((sign) ? '' : '-') + num + '.' + cents);
}

//quietar formato
function quitar_formato_miles(numero) {
  let inVal = numero.replace(/,/g, "");
  return inVal;
}

/* PREVISUALIZAR LOS DOCUMENTOS */
function addDocs(e,id) {

  $("#"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');	console.log(id);

	var file = e.target.files[0], archivoType = /image.*|application.*/;
	
	if (e.target.files[0]) {
    
		var sizeByte = file.size; console.log(file.type);

		var sizekiloBytes = parseInt(sizeByte / 1024);

		var sizemegaBytes = (sizeByte / 1000000);
		// alert("KILO: "+sizekiloBytes+" MEGA: "+sizemegaBytes)

		if (!file.type.match(archivoType) ){
			// return;
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Este tipo de ARCHIVO no esta permitido elija formato: .pdf, .png. .jpeg, .jpg, .jpe, .webp, .svg',
        showConfirmButton: false,
        timer: 1500
      });

      $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >'); 

		}else{

			if (sizekiloBytes <= 40960) {

				var reader = new FileReader();

				reader.onload = fileOnload;

				function fileOnload(e) {

					var result = e.target.result;

          // cargamos la imagen adecuada par el archivo
				  if ( extrae_extencion(file.name) == "doc") {
            $("#"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
          } else {
            if ( extrae_extencion(file.name) == "docx" ) {
              $("#"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
            }else{
              if ( extrae_extencion(file.name) == "pdf" ) {
                $("#"+id+"_ver").html(`<iframe src="${result}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
              }else{
                if ( extrae_extencion(file.name) == "csv" ) {
                  $("#"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
                } else {
                  if ( extrae_extencion(file.name) == "xls" ) {
                    $("#"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
                  } else {
                    if ( extrae_extencion(file.name) == "xlsx" ) {
                      $("#"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                    } else {
                      if ( extrae_extencion(file.name) == "xlsm" ) {
                        $("#"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                      } else {
                        if (
                          extrae_extencion(file.name) == "jpeg" || extrae_extencion(file.name) == "jpg" || extrae_extencion(file.name) == "jpe" ||
                          extrae_extencion(file.name) == "jfif" || extrae_extencion(file.name) == "gif" || extrae_extencion(file.name) == "png" ||
                          extrae_extencion(file.name) == "tiff" || extrae_extencion(file.name) == "tif" || extrae_extencion(file.name) == "webp" ||
                          extrae_extencion(file.name) == "bmp" || extrae_extencion(file.name) == "svg" ) {

                          $("#"+id+"_ver").html(`<img src="${result}" alt="" width="100%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
                          
                        } else {
                          $("#"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                        }
                        
                      }
                    }
                  }
                }
              }
            }
          } 
					$("#"+id+"_nombre").html(`<div class="row">
            <div class="col-md-12">
              <i> ${file.name} </i>
            </div>
            <div class="col-md-12">
              <button class="btn btn-danger btn-block btn-xs" onclick="${id}_eliminar();" type="button" ><i class="far fa-trash-alt"></i></button>
            </div>
          </div>`);

          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `El documento: ${file.name.toUpperCase()} es aceptado.`,
            showConfirmButton: false,
            timer: 1500
          });
				}

				reader.readAsDataURL(file);

			} else {
        Swal.fire({
          position: 'top-end',
          icon: 'warning',
          title: `El documento: ${file.name.toUpperCase()} es muy pesado.`,
          showConfirmButton: false,
          timer: 1500
        })

        $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
        $("#"+id+"_nombre").html("");
				$("#"+id).val("");
			}
		}
	}else{
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Seleccione un documento',
      showConfirmButton: false,
      timer: 1500
    })
		 
    $("#"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');
		$("#"+id+"_nombre").html("");
    $("#"+id).val("");
	}	
}

// recargar un doc para ver
function re_visualizacion(id, carpeta) {

  $("#doc"+id+"_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>'); console.log(id);

  pdffile     = document.getElementById("doc"+id+"").files[0];

  var antiguopdf  = $("#doc_old_"+id+"").val();

  if(pdffile === undefined){

    if (antiguopdf == "") {

      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Seleccione un documento',
        showConfirmButton: false,
        timer: 1500
      })

      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_uploads.svg" alt="" width="50%" >');

		  $("#doc"+id+"_nombre").html("");

    } else {
      if ( extrae_extencion(antiguopdf) == "doc") {
        $("#doc"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
        toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
      } else {
        if ( extrae_extencion(antiguopdf) == "docx" ) {
          $("#doc"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
          toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
        } else {
          if ( extrae_extencion(antiguopdf) == "pdf" ) { 
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/sub_contrato/${carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
            toastr.success('Documento vizualizado correctamente!!!')
          } else {
            if ( extrae_extencion(antiguopdf) == "csv" ) {
              $("#doc"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
              toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
            } else {
              if ( extrae_extencion(antiguopdf) == "xls" ) {
                $("#doc"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
                toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
              } else {
                if ( extrae_extencion(antiguopdf) == "xlsx" ) {
                  $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                  toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                } else {
                  if ( extrae_extencion(antiguopdf) == "xlsm" ) {
                    $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                    toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                  } else {
                    if (
                      extrae_extencion(antiguopdf) == "jpeg" || extrae_extencion(antiguopdf) == "jpg" || extrae_extencion(antiguopdf) == "jpe" ||
                      extrae_extencion(antiguopdf) == "jfif" || extrae_extencion(antiguopdf) == "gif" || extrae_extencion(antiguopdf) == "png" ||
                      extrae_extencion(antiguopdf) == "tiff" || extrae_extencion(antiguopdf) == "tif" || extrae_extencion(antiguopdf) == "webp" ||
                      extrae_extencion(antiguopdf) == "bmp" || extrae_extencion(antiguopdf) == "svg" ) {
  
                      $("#doc"+id+"_ver").html(`<img src="../dist/docs/sub_contrato/${carpeta}/${antiguopdf}" alt="" onerror="this.src='../dist/svg/error-404-x.svg';" width="100%" >`);
                      toastr.success('Documento vizualizado correctamente!!!');
                    } else {
                      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                      toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
                    }                    
                  }
                }
              }
            }
          }
        }
      }      
    }
    // console.log('hola'+dr);
  }else{

    pdffile_url=URL.createObjectURL(pdffile);

    // cargamos la imagen adecuada par el archivo
    if ( extrae_extencion(pdffile.name) == "doc") {
      $("#doc"+id+"_ver").html('<img src="../dist/svg/doc.svg" alt="" width="50%" >');
      toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
    } else {
      if ( extrae_extencion(pdffile.name) == "docx" ) {
        $("#doc"+id+"_ver").html('<img src="../dist/svg/docx.svg" alt="" width="50%" >');
        toastr.error('Documento NO TIENE PREVIZUALIZACION!!!')
      }else{
        if ( extrae_extencion(pdffile.name) == "pdf" ) {
          $("#doc"+id+"_ver").html('<iframe src="'+pdffile_url+'" frameborder="0" scrolling="no" width="100%" height="310"> </iframe>');
          toastr.success('Documento vizualizado correctamente!!!');
        }else{
          if ( extrae_extencion(pdffile.name) == "csv" ) {
            $("#doc"+id+"_ver").html('<img src="../dist/svg/csv.svg" alt="" width="50%" >');
            toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
          } else {
            if ( extrae_extencion(pdffile.name) == "xls" ) {
              $("#doc"+id+"_ver").html('<img src="../dist/svg/xls.svg" alt="" width="50%" >');
              toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
            } else {
              if ( extrae_extencion(pdffile.name) == "xlsx" ) {
                $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsx.svg" alt="" width="50%" >');
                toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
              } else {
                if ( extrae_extencion(pdffile.name) == "xlsm" ) {
                  $("#doc"+id+"_ver").html('<img src="../dist/svg/xlsm.svg" alt="" width="50%" >');
                  toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
                } else {
                  if (
                    extrae_extencion(pdffile.name) == "jpeg" || extrae_extencion(pdffile.name) == "jpg" || extrae_extencion(pdffile.name) == "jpe" ||
                    extrae_extencion(pdffile.name) == "jfif" || extrae_extencion(pdffile.name) == "gif" || extrae_extencion(pdffile.name) == "png" ||
                    extrae_extencion(pdffile.name) == "tiff" || extrae_extencion(pdffile.name) == "tif" || extrae_extencion(pdffile.name) == "webp" ||
                    extrae_extencion(pdffile.name) == "bmp" || extrae_extencion(pdffile.name) == "svg" ) {

                    $("#doc"+id+"_ver").html(`<img src="${pdffile_url}" alt="" width="100%" >`);
                    toastr.success('Documento vizualizado correctamente!!!');
                  } else {
                    $("#doc"+id+"_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
                    toastr.error('Documento NO TIENE PREVIZUALIZACION!!!');
                  }                  
                }
              }
            }
          }
        }
      }
    }     	
    console.log(pdffile);
  }
}






