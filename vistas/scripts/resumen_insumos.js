var tabla_principal;
var tabla_factura;
var tabla_materiales;

var array_class_trabajador = [];
var cont = 0;
var detalles = 0;

//Función que se ejecuta al inicio
function init(){
	
	tbla_principal(localStorage.getItem('nube_idproyecto'));

	$("#bloc_Compras").addClass("menu-open");

	$("#mCompra").addClass("active");

	$("#lResumenInsumos").addClass("active");

  //Initialize Select2 PROVEEDOR
  $("#idproveedor").select2({
    theme: "bootstrap4",
    placeholder: "Selecione trabajador",
    allowClear: true,
  });

  //Initialize Select2 TIPO DE COMPROBANTE
  $("#tipo_comprovante").select2({
    theme: "bootstrap4",
    placeholder: "Selecione Comprobante",
    allowClear: true,
  });
}

// OCULTAR MOSTRAR - TABLAS
function table_show_hide(flag) {
  if (flag == 1) {
    $(".mensaje-tbla-principal").show();
    $("#btn-regresar").hide();
    $("#btn-regresar-todo").hide();
    $("#btn-regresar-bloque").hide();    

    $(".nombre-insumo").html("Resumen de Insumos");

    $("#tabla-principal").show();
    $("#tabla-factura").hide();
    $("#tabla-editar-factura").hide();
  } else {
    if (flag == 2) {
      $(".mensaje-tbla-principal").hide();
      $("#btn-regresar").show();
      $("#btn-regresar-todo").hide();
      $("#btn-regresar-bloque").hide();
       

      $("#tabla-principal").hide();
      $("#tabla-factura").show();
      $("#tabla-editar-factura").hide();
    }else{
      if (flag == 3) {
        $(".mensaje-tbla-principal").hide();
        $("#btn-regresar").hide();
        $("#btn-regresar-todo").show();
        $("#btn-regresar-bloque").show();         

        $("#tabla-principal").hide();
        $("#tabla-factura").hide();
        $("#tabla-editar-factura").show();        
      }
    }
  }
}

// TABLA - PRINCIPAL
function tbla_principal(id_proyecto) {
	tabla_principal=$('#tbla-resumen-insumos').dataTable({
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

// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION COMPRAS ::::::::::::::::::::::::::::::::::::::::::::::::::::

// TABLA - FACTURAS
function tbla_facuras( idproyecto, idproducto, nombre_producto, precio_promedio, subtotal_x_producto ) {

  table_show_hide(2);

  $(".nombre-insumo").html(`Producto: <b>${nombre_producto}</b>`);   
	 
  $(".precio_promedio").html(precio_promedio);
  $(".subtotal_x_producto").html(subtotal_x_producto);

	tabla_factura = $('#tbla-facura').dataTable({
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
		"iDisplayLength": 10,//Paginación
		"order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();  
}

// LIMPIAR FORM
function limpiar_form_compra() {
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

// EDITAR - PRODUCTO
function editar_detalle_compras(id) {

  table_show_hide(3);

  limpiar_form_compra();

  array_class_trabajador = [];

  cont = 0;  detalles = 0;

  tbla_materiales()

  $.post("../ajax/compra.php?op=ver_compra_editar", { 'idcompra_proyecto': id }, function (data, status) {
    
    data = JSON.parse(data);  console.log(data);

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
            img = "../dist/svg/default_producto.svg";
          } else {
            img = `../dist/docs/material/img_perfil/${element.imagen}`;
          }

          var fila = `
          <tr class="filas" id="fila${cont}">
            <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(${cont})">X</button></td>
            <td>
              <input type="hidden" name="idproducto[]" value="${element.idproducto}">
              <input type="hidden" name="ficha_tecnica_producto[]" value="${element.ficha_tecnica}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="${img}" alt="user image" onerror="this.src='../dist/svg/default_producto.svg';" onclick="ver_img_material('${element.imagen}', '${element.nombre_producto}')">
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

// AGREGAR - PRODUCTO
function agregarDetalleComprobante(idproducto, nombre, unidad_medida, nombre_color, precio_sin_igv, precio_igv, precio_total, img, ficha_tecnica_producto) {
  var stock = 5;
  var cantidad = 1;
  var descuento = 0;

  if (idproducto != "") {
    // $('.producto_'+idproducto).addClass('producto_selecionado');
    if ($(".producto_" + idproducto).hasClass("producto_selecionado")) {
      toastr.success("Material: " + nombre + " agregado !!");

      var cant_producto = $(".producto_" + idproducto).val();

      var sub_total = parseInt(cant_producto, 10) + 1;

      $(".producto_" + idproducto).val(sub_total);

      modificarSubtotales();
    } else {

      if ($("#tipo_comprovante").select2("val") == "Factura") {
        var subtotal = cantidad * precio_total;
      } else {
        var subtotal = cantidad * precio_sin_igv;      
      }    

      var img_p = "";

      if (element.imagen == "" || element.imagen == null) {
        img_p = "../dist/svg/default_producto.svg";
      } else {
        img_p = `../dist/docs/material/img_perfil/${element.imagen}`;
      }

      var fila = `
      <tr class="filas" id="fila${cont}">
        <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(${cont})">X</button></td>
        <td>
          <input type="hidden" name="idproducto[]" value="${idproducto}">
          <input type="hidden" name="ficha_tecnica_producto[]" value="${ficha_tecnica_producto}">
          <div class="user-block text-nowrap">
            <img class="profile-user-img img-responsive img-circle cursor-pointer" src="${img_p}" alt="user image" onerror="this.src='../dist/svg/default_producto.svg';" onclick="ver_img_material('${img}', '${nombre}')">
            <span class="username"><p style="margin-bottom: 0px !important;">${nombre}</p></span>
            <span class="description"><b>Color: </b>${nombre_color}</span>
          </div>
        </td>
        <td><span class="">${unidad_medida}</span> <input type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${unidad_medida}"><input type="hidden" name="nombre_color[]" id="nombre_color[]" value="${nombre_color}"></td>
        <td class="form-group"><input class="producto_${idproducto} producto_selecionado w-px-100 cantidad_${cont} form-control" type="number" name="cantidad[]" id="cantidad[]" min="1" value="${cantidad}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
        <td class="hidden"><input type="number" class="w-px-135 input-no-border precio_sin_igv_${cont}" name="precio_sin_igv[]" id="precio_sin_igv[]" value="${parseFloat(precio_sin_igv).toFixed(2)}" readonly min="0" ></td>
        <td class="hidden"><input class="w-px-135 input-no-border precio_igv_${cont}" type="number" name="precio_igv[]" id="precio_igv[]" value="${parseFloat(precio_igv).toFixed(2)}" readonly  ></td>
        <td ><input class="w-px-135 precio_con_igv_${cont}" type="number" name="precio_con_igv[]" id="precio_con_igv[]" value="${parseFloat(precio_total).toFixed(2)}" onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
        <td><input type="number" class="w-px-135 descuento_${cont}" name="descuento[]" value="${descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
        <td class="text-right"><span class="text-right subtotal_producto_${cont}" name="subtotal_producto" id="subtotal_producto">${subtotal}</span></td>
        <td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fas fa-sync"></i></button></td>
      </tr>`;

      detalles = detalles + 1;

      $("#detalles").append(fila);

      array_class_trabajador.push({ id_cont: cont });

      modificarSubtotales();

      toastr.success("Material: " + nombre + " agregado !!");

      cont++;
      evaluar();
    }
  } else {
    // alert("Error al ingresar el detalle, revisar los datos del artículo");
    toastr.error("Error al ingresar el detalle, revisar los datos del material.");
  }
}

function evaluar() {
  if (detalles > 0) {
    $("#guardar_registro_compras").show();
  } else {
    $("#guardar_registro_compras").hide();
    cont = 0;
  }
}

function modificarSubtotales() {
  console.log(array_class_trabajador);

  if ($("#tipo_comprovante").select2("val") == null) {
    $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

    $("#colspan_subtotal").attr("colspan", 5); //cambiamos el: colspan

    if (array_class_trabajador.length === 0) {
    } else {
      array_class_trabajador.forEach((element, index) => {
        var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
        var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
        var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
        var subtotal_producto = 0;

        // Calculamos: IGV
        var precio_sin_igv = precio_con_igv;
        $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

        // Calculamos: precio + IGV
        var igv = 0;
        $(`.precio_igv_${element.id_cont}`).val(igv);

        // Calculamos: Subtotal de cada producto
        subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
        $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(4)));
      });
      calcularTotalesSinIgv();
    }
  } else {
    if ($("#tipo_comprovante").select2("val") == "Factura") {
      $(".hidden").show(); //Mostramos: IGV, PRECIO SIN IGV

      $("#colspan_subtotal").attr("colspan", 7); //cambiamos el: colspan

      if (array_class_trabajador.length === 0) {
      } else {
        array_class_trabajador.forEach((element, index) => {
          var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
          var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
          var subtotal_producto = 0;

          // Calculamos: IGV
          var precio_sin_igv = (precio_con_igv / 1.18).toFixed(2);
          $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

          // Calculamos: precio + IGV
          var igv = (parseFloat(precio_con_igv) - parseFloat(precio_sin_igv)).toFixed(2);
          $(`.precio_igv_${element.id_cont}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
          $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(2)));
        });

        calcularTotalesConIgv();
      }
    } else {
      $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

      $("#colspan_subtotal").attr("colspan", 5); //cambiamos el: colspan

      if (array_class_trabajador.length === 0) {
      } else {
        array_class_trabajador.forEach((element, index) => {
          var cantidad = parseFloat($(`.cantidad_${element.id_cont}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_${element.id_cont}`).val());
          var deacuento = parseFloat($(`.descuento_${element.id_cont}`).val());
          var subtotal_producto = 0;

          // Calculamos: IGV
          var precio_sin_igv = precio_con_igv;
          $(`.precio_sin_igv_${element.id_cont}`).val(precio_sin_igv);

          // Calculamos: precio + IGV
          var igv = 0;
          $(`.precio_igv_${element.id_cont}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
          $(`.subtotal_producto_${element.id_cont}`).html(formato_miles(subtotal_producto.toFixed(4)));
        });

        calcularTotalesSinIgv();
      }
    }
  }
  toastr.success("Precio Actualizado !!!");
}

function calcularTotalesSinIgv() {
  var total = 0.0;
  var igv = 0;
  var mtotal = 0;

  if (array_class_trabajador.length === 0) {
  } else {
    array_class_trabajador.forEach((element, index) => {
      total += parseFloat(quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()));
    });

    $("#subtotal").html("S/. " + formato_miles(total));
    $("#subtotal_compra").val(redondearExp(total, 4));

    $("#igv_comp").html("S/. 0.00");
    $("#igv_compra").val(0.0);

    $("#total").html("S/. " + formato_miles(total.toFixed(2)));
    $("#total_venta").val(redondearExp(total, 4));
  }
}

function calcularTotalesConIgv() {
  var igv = 0;
  var total = 0.0;

  var subotal_sin_igv = 0;

  array_class_trabajador.forEach((element, index) => {
    total += parseFloat(quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()));
  });

  console.log(total);
  subotal_sin_igv = (parseFloat(total) / 1.18).toFixed(2);
  igv = (parseFloat(total) - parseFloat(subotal_sin_igv)).toFixed(2);

  $("#subtotal").html(`S/. ${formato_miles(subotal_sin_igv)}`);
  $("#subtotal_compra").val(redondearExp(subotal_sin_igv, 4));

  $("#igv_comp").html("S/. " + formato_miles(igv));
  $("#igv_compra").val(igv);

  $("#total").html("S/. " + formato_miles(total.toFixed(2)));
  $("#total_venta").val(redondearExp(total, 4));

  total = 0.0;
}

function ocultar_comprob() {
  if ($("#tipo_comprovante").select2("val") == "Ninguno") {
    $("#content-comprob").hide();

    $("#content-comprob").val("");

    $("#content-descrp").removeClass("col-lg-5").addClass("col-lg-7");
  } else {
    $("#content-comprob").show();

    $("#content-descrp").removeClass("col-lg-7").addClass("col-lg-5");
  }
}

function eliminarDetalle(indice) {
  $("#fila" + indice).remove();

  array_class_trabajador.forEach(function (car, index, object) {
    if (car.id_cont === indice) {
      object.splice(index, 1);
    }
  });

  modificarSubtotales();

  detalles = detalles - 1;

  evaluar();

  toastr.warning("Material removido.");
}

function l_m() {
  // limpiar();
  $("#barra_progress").css({ width: "0%" });

  $("#barra_progress").text("0%");

  $("#barra_progress2").css({ width: "0%" });

  $("#barra_progress2").text("0%");
}

// TABLA - MATERIALES
function tbla_materiales() {

  tabla_materiales = $("#tblamateriales").dataTable({
    responsive: true,
    lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
    aProcessing: true, //Activamos el procesamiento del datatables
    aServerSide: true, //Paginación y filtrado realizados por el servidor
    dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
    buttons: [],
    ajax: {
      url: "../ajax/compra.php?op=listarMaterialescompra",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    createdRow: function (row, data, ixdex) {
      // columna: sueldo mensual
      if (data[3] != '') {
        $("td", row).eq(3).addClass('text-right');
      }  
    },
    bDestroy: true,
    iDisplayLength: 10, //Paginación
    // order: [[0, "desc"]], //Ordenar (columna,orden)
  }).DataTable();
}

// ver imagen grande del producto agregado a la compra
function ver_img_material(img, nombre) {
  $("#ver_img_material").attr("src", `../dist/docs/material/img_perfil/${img}`);
  $(".nombre-img-material").html(nombre);
  $("#modal-ver-img-material").modal("show");
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION AGREGAR PRODUCTO ::::::::::::::::::::::::::::::::::::::::::::::::::::

// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION AGREGAR PROVEEDOR ::::::::::::::::::::::::::::::::::::::::::::::::::::

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

// quitamos las comas de miles de un numero
function quitar_formato_miles(numero) {
  let inVal = numero.replace(/,/g, '');
  return inVal;
}

/**Redondear */
function redondearExp(numero, digitos) {
  function toExp(numero, digitos) {
    let arr = numero.toString().split("e");
    let mantisa = arr[0],
      exponente = digitos;
    if (arr[1]) exponente = Number(arr[1]) + digitos;
    return Number(mantisa + "e" + exponente.toString());
  }
  let entero = Math.round(toExp(Math.abs(numero), digitos));
  return Math.sign(numero) * toExp(entero, -digitos);
}

init();