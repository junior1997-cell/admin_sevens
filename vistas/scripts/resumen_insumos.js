var tabla;
var tabla2;

var array_class_trabajador = [];
var cont = 0;
var detalles = 0;

//Función que se ejecuta al inicio
function init(){
	
	listar_tbla_principal(localStorage.getItem('nube_idproyecto'));

	$("#bloc_Compras").addClass("menu-open");

	$("#mCompra").addClass("active");

	$("#lResumenInsumos").addClass("active");
}

//Función Listar
function listar_tbla_principal(id_proyecto)
{
	tabla=$('#tabla-resumen-insumos').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
		"ajax":	{
      url: '../ajax/resumen_insumos.php?op=listar_tbla_principal&id_proyecto='+id_proyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
		},
    createdRow: function (row, data, ixdex) {          

      // columna: Cantidad
      if (data[2] != '') {
        $("td", row).eq(2).addClass("text-center");         
      }

      // columna: Precio promedio
      if (data[3] != '') {
        $("td", row).eq(3).addClass("modal-footer justify-content-between");         
      }

      // columna: Precio actual
      if (data[4] != '') {
        $("td", row).eq(4).addClass("text-right");         
      }
      // columna: Suma Total
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-right");         
      }


    },
		"language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": { _: '%d líneas copiadas',  1: '1 línea copiada' }
      }
    },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	  "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();

  $.post("../ajax/resumen_insumos.php?op=suma_total_compras", { 'idproyecto': id_proyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data); 

    if (data.length === 0) {

      $(".suma_total_de_compras").html('<i class="far fa-frown fa-lg text-danger"></i>');

      $('.suma_total_productos').html('<i class="far fa-frown fa-lg text-danger"></i>');

    } else {
      if (data.suma_total_compras == null || data.suma_total_compras == '') {
        $(".suma_total_de_compras").html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $(".suma_total_de_compras").html( 'S/. '+ formato_miles(data.suma_total_compras));
      }

      if (data.suma_total_productos == null || data.suma_total_productos == '') {
        $('.suma_total_productos').html('<i class="far fa-frown fa-lg text-danger"></i>');
      } else {
        $('.suma_total_productos').html(data.suma_total_productos);
      }
    }    
  });
}

function ver_precios_y_mas( idproyecto, idproducto, nombre_producto, precio_promedio, subtotal_x_producto ) {

  $(".nombre-producto-modal-titel").html('Producto: <b>'+ nombre_producto +'</b>');
	$("#modal-ver-precios").modal("show");
  $(".precio_promedio").html(precio_promedio);
  $(".subtotal_x_producto").html(subtotal_x_producto);

	tabla2 = $('#tabla-precios').dataTable({
		"responsive": true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
		buttons: [	],
		"ajax":	{
      url: `../ajax/resumen_insumos.php?op=ver_precios_y_mas&idproyecto=${idproyecto}&idproducto=${idproducto}`,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
    },
    createdRow: function (row, data, ixdex) {          

      // columna: Cantidad
      if (data[3] != '') {
        $("td", row).eq(3).addClass("text-center");         
      }

      // columna: Precio promedio
      if (data[4] != '') {
        $("td", row).eq(4).addClass("text-right h5");         
      }

      // columna: Precio actual
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-right");         
      }
      
      if (data[6] != '') {
        $("td", row).eq(6).addClass("text-right");         
      }
    },
		"language": {"lengthMenu": "Mostrar : _MENU_ registros", },
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
		"order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();  
}

function limpiar_compra() {
  $(".tooltip").hide();

  //Mostramos los selectProveedor
  $.post("../ajax/compra.php?op=selectProveedor", function (r) {
    $("#idproveedor").html(r);
  });

  $("#idcompra_proyecto").val();
  $("#idproyecto").val();

  $("#idusuario").val("");
  $("#trabajador_c").html("Trabajador");
  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprovante").val("Ninguno").trigger("change");

  // $("#fecha_compra").val("");
  $("#serie_comprovante").val("");
  $("#descripcion").val("");

  $("#total_venta").val("");
  $(".filas").remove();
  $("#total").html("0");
  $("#subtotal").html("");
  $("#subtotal_compra").val("");

  $("#igv_comp").html("");
  $("#igv_compra").val("");

  $("#total").html("");
  $("#total_venta").val("");

  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

function editar_detalle_compras(id) {
  limpiar();
  array_class_trabajador = [];

  cont = 0;
  detalles = 0;
  ver_form_add();

  $.post("../ajax/compra.php?op=ver_compra_editar", { idcompra_proyecto: idcompra_proyecto }, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

    if (data) {
      $(".subtotal").html("");
      $(".igv_comp").html("");
      $(".total").html("");

      if (data.tipo_comprovante == "Factura") {
        $(".igv").val("0.18");
        $(".content-igv").show();
        $(".content-t-comprob").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descrp").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        $(".content-comprob").show();
      } else if (data.tipo_comprovante == "Boleta" || data.tipo_comprovante == "Nota_de_venta") {
        $(".igv").val("");
        $(".content-comprob").show();
        $(".content-igv").hide();
        $(".content-t-comprob").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");

        $(".content-descrp").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      } else if (data.tipo_comprovante == "Ninguno") {
        $(".content-comprob").hide();
        $(".content-comprob").val("");
        $(".content-igv").hide();
        $(".content-t-comprob").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descrp").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
        $(".content-comprob").show();
        //$(".content-descrp").removeClass("col-lg-7").addClass("col-lg-4");
      }

      $("#idproyecto").val(data.idproyecto);
      $("#idcompra_proyecto").val(data.idcompra_x_proyecto);
      $("#idproveedor").val(data.idproveedor).trigger("change");
      $("#fecha_compra").val(data.fecha_compra);
      $("#tipo_comprovante").val(data.tipo_comprobante).trigger("change");
      $("#serie_comprovante").val(data.serie_comprobante).trigger("change");
      $("#descripcion").val(data.descripcion);

      if (data.producto) {
        data.producto.forEach((element, index) => {
          var img = "";

          if (element.imagen == "" || element.imagen == null) {
            img = "img_material_defect.jpg";
          } else {
            img = element.imagen;
          }

          var fila = `
          <tr class="filas" id="fila${cont}">
            <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(${cont})">X</button></td>
            <td>
              <input type="hidden" name="idproducto[]" value="${element.idproducto}">
              <input type="hidden" name="ficha_tecnica_producto[]" value="${element.ficha_tecnica}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/img/materiales/${img}" alt="user image" onerror="this.src='../dist/svg/default_producto.svg';" onclick="ver_img_material('${img}', '${element.nombre_producto}')">
                <span class="username"><p style="margin-bottom: 0px !important;">${element.nombre_producto}</p></span>
                <span class="description"><b>Color: </b>${element.color}</span>
              </div>
            </td>
            <td> <span class="">${element.unidad_medida}</span> <input type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${element.unidad_medida}"> <input type="hidden" name="nombre_color[]" id="nombre_color[]" value="${element.color}"></td>
            <td class="form-group"><input class="producto_${element.idproducto} producto_selecionado w-px-100 cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" min="1" value="${element.cantidad}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="hidden"><input class="w-px-135 input-no-border precio_sin_igv_${cont}" type="number" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${element.precio_venta}" readonly ></td>
            <td class="hidden"><input class="w-px-135 input-no-border precio_igv_${cont}" type="number"  name="precio_igv[]" id="precio_igv[]" value="${element.igv}" readonly ></td>
            <td ><input type="number" class="w-px-135 precio_con_igv_${cont}" type="number"  name="precio_con_igv[]" id="precio_con_igv[]" value="${parseFloat(element.precio_igv).toFixed(2)}" onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
            <td><input type="number" class="w-px-135 descuento_${cont}" name="descuento[]" value="${element.descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">0.00</span></td>
            <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles").append(fila);

          array_class_trabajador.push({ id_cont: cont });

          cont++;
          evaluar();
        });

        modificarSubtotales();
      } else {
        toastr.error("<h3>Sin productos.</h3> <br> Este registro no tiene productos para mostrar");
        $(".subtotal").html("S/. 0.00");
        $(".igv_comp").html("S/. 0.00");
        $(".total").html("S/. 0.00");
      }
    } else {
      toastr.error("<h3>Error.</h3> <br> Este registro tiene errores, o esta vacio");
    }
  });
}


// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..


/**formato_miles */
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

init();