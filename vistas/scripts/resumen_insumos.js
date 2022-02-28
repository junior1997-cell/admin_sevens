var tabla_principal;
var tabla_factura;
var tabla_materiales;

var array_class_trabajador = [];
var cont = 0;
var detalles = 0;

//Función que se ejecuta al inicio
function init(){

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open");

  $("#mLogisticaAdquisiciones").addClass("active");

	$("#mCompra").addClass("active bg-primary");

	$("#lResumenInsumos").addClass("active");
	
	tbla_principal(localStorage.getItem('nube_idproyecto'));	

  //MOSTRAMOS - los proveedores
  $.post("../ajax/resumen_insumos.php?op=select2Proveedor", function (r) { $("#idproveedor").html(r); });
  
  // Mostra,ps los bancos
  $.post("../ajax/resumen_insumos.php?op=select2Banco", function (r) {  $("#banco_pago").html(r); $("#banco_prov").html(r); });
  
  //MOSTRAMOS - colores
  $.post("../ajax/resumen_insumos.php?op=select2Color", function (r) { $("#color_p").html(r); });

  //MOSTRAMOS - las unidades de medida
  $.post("../ajax/resumen_insumos.php?op=select2UnidaMedida", function (r) { $("#unidad_medida_p").html(r); });

  //MOSTRAMOS - las categorias del producto
  $.post("../ajax/resumen_insumos.php?op=select2Categoria", function (r) { $("#categoria_insumos_af_p").html(r); });

  // GUARDAR COMPRAS
  $("#guardar_registro_compras").on("click", function (e) {  $("#submit-form-compras").submit(); });

  //GUARDAR PROVEEDOR
  $("#guardar_registro_proveedor").on("click", function (e) { $("#submit-form-proveedor").submit(); });

  //GUARDAR MATERIAL
  $("#guardar_registro_material").on("click", function (e) {  $("#submit-form-materiales").submit(); });

  //Initialize Select2 BANCO PROVEEDOR
  $("#banco_prov").select2({
    theme: "bootstrap4",
    placeholder: "Selecione banco",
    allowClear: true,
  });

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

  //Initialize Select2 CATEGORIA
  $("#categoria_insumos_af_p").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar color",
    allowClear: true,
  });

  //Initialize Select2 COLOR
  $("#color_p").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar color",
    allowClear: true,
  });

  //Initialize Select2 UNIDAD DE MEDIDA
  $("#unidad_medida_p").select2({
    theme: "bootstrap4",
    placeholder: "Seleccinar una unidad",
    allowClear: true,
  });

  // Formato para telefono
  $("[data-mask]").inputmask();
}

// Perfil material
$("#foto2_i").click(function () {  $("#foto2").trigger("click"); });
$("#foto2").change(function (e) { addImage(e, $("#foto2").attr("id")); });

//ficha tecnica
$("#doc2_i").click(function() {  $('#doc2').trigger('click'); });
$("#doc2").change(function(e) {  addDocs(e,$("#doc2").attr("id")) });

// Eliminamos el doc FOTO PERFIL MATERIAL
function foto2_eliminar() {
  $("#foto2").val("");
  $("#ver_pdf").html("");

  $("#foto2_i").attr("src", "../dist/img/default/img_defecto_activo_fijo_material.png");

  $("#foto2_nombre").html("");
  $("#foto2_i").show();
}

// Eliminamos el doc FICHA TECNICA
function doc2_eliminar() {
  $("#doc2").val("");

  $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

  $("#doc2_nombre").html("");
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
		lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	  "aServerSide": true,//Paginación y filtrado realizados por el servidor
	  dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	  buttons: [ { extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }, "colvis"],
		"ajax":	{
      url: '../ajax/resumen_insumos.php?op=tbla_principal&id_proyecto='+id_proyecto,
      type : "get",
      dataType : "json",						
      error: function(e){
        console.log(e.responseText);	
      }
		},
    createdRow: function (row, data, ixdex) {  
      // columna: #
      if (data[0] != '') {
        $("td", row).eq(0).addClass("text-center");         
      }

      // columna: op
      if (data[1] != '') {
        $("td", row).eq(1).addClass("text-nowrap");         
      }

      // columna: UM
      if (data[5] != '') {
        $("td", row).eq(5).addClass("text-center");         
      }

      // columna: Compra
      if (data[6] != '') {
        $("td", row).eq(6).addClass("text-center");         
      }

      // columna: Precio promedio
      if (data[7] != '') {
        $("td", row).eq(7).addClass("text-right");         
      }

      // columna: Precio actual
      if (data[8] != '') {
        $("td", row).eq(8).addClass("text-right");         
      }
      // columna: Suma Total
      if (data[9] != '') {
        $("td", row).eq(9).addClass("text-right");         
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
	  //"order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    "columnDefs":[ { "targets": [ 3 ], "visible": false, "searchable": false }, ]
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
		lengthMenu: [[5, 10, 25, 75, 100, 200, -1], [5, 10, 25, 75, 100, 200, "Todos"]],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
		buttons: [ { extend: 'copyHtml5', footer: true }, { extend: 'excelHtml5', footer: true }, { extend: 'pdfHtml5', footer: true }],
		"ajax":	{
      url: `../ajax/resumen_insumos.php?op=tbla_facturas&idproyecto=${idproyecto}&idproducto=${idproducto}`,
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
  $.post("../ajax/resumen_insumos.php?op=select2Proveedor", function (r) { $("#idproveedor").html(r); });

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

// EDITAR - DETALLE DE COMPRA
function editar_detalle_compras(id) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  table_show_hide(3);

  limpiar_form_compra();

  array_class_trabajador = [];

  cont = 0;  detalles = 0;

  tbla_materiales()

  $.post("../ajax/resumen_insumos.php?op=ver_compra_editar", { 'idcompra_proyecto': id }, function (data, status) {
    
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

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();

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

      if (img == "" || img == null) {
        img_p = "../dist/svg/default_producto.svg";
      } else {
        img_p = `../dist/docs/material/img_perfil/${img}`;
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

// ver imagen grande del producto agregado a la compra
function ver_img_material(img, nombre) {
  $("#ver_img_material").attr("src", `../dist/docs/material/img_perfil/${img}`);
  $(".nombre-img-material").html(nombre);
  $("#modal-ver-img-material").modal("show");
}

//Función para guardar o editar - COMPRAS
function guardar_y_editar_compras(e) {
   
  var formData = new FormData($("#form-compras")[0]);

  Swal.fire({
    title: "¿Está seguro que deseas guardar esta compra?",
    html: "Verifica que todos lo <b>campos</b>  esten <b>conformes</b>!!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Guardar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../ajax/resumen_insumos.php?op=guardar_y_editar_compra",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
          if (datos == "ok") {
            // toastr.success("Usuario registrado correctamente");
            Swal.fire("Correcto!", "Compra guardada correctamente", "success");

            tabla_principal.ajax.reload();

            limpiar_form_compra();

            table_show_hide(2);  cont = 0;

          } else {
            // toastr.error(datos);
            Swal.fire("Error!", datos, "error");
          }
        },
      });
    }
  });
}

// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION AGREGAR PRODUCTO ::::::::::::::::::::::::::::::::::::::::::::::::::::
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
      url: "../ajax/resumen_insumos.php?op=listarMaterialescompra",
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

//Función limpiar
function limpiar_materiales() {
  $("#idproducto_p").val("");  
  $("#nombre_p").val("");
  $("#modelo_p").val("");
  $("#serie_p").val("");
  $("#marca_p").val("");
  $("#descripcion_p").val("");

  $("#precio_unitario_p").val("");
  $("#precio_sin_igv_p").val("");
  $("#precio_igv_p").val("");
  $("#precio_total_p").val("");

  $("#foto2_i").attr("src", "../dist/img/default/img_defecto_activo_fijo_material.png");
  $("#foto2").val("");
  $("#foto2_actual").val("");
  $("#foto2_nombre").html("");   

  $("#doc_old_2").val("");
  $("#doc2").val("");  
  $('#doc2_ver').html(`<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >`);
  $('#doc2_nombre').html("");

  $("#unid_medida_p").val(4).trigger("change");
  $("#color_p").val(1).trigger("change");
  $("#categoria_insumos_af_p").val("").trigger("change");

  $("#my-switch_igv").prop("checked", true);
  $("#estado_igv_p").val("1");

  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");
}

//Función para guardar o editar
function guardar_materiales(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-materiales")[0]);

  $.ajax({
    url: "../ajax/resumen_insumos.php?op=guardar_materiales",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      if (datos == "ok") {

        Swal.fire("Correcto!", "Producto creado correctamente", "success");        
         
        tabla_principal.ajax.reload();

        if (tabla_materiales) { tabla_materiales.ajax.reload(); }

        limpiar_materiales();

        $("#modal-agregar-material-activos-fijos").modal("hide");
      } else {
        Swal.fire("Error!", datos, "error");
      }
    },
  });
}

// MOSTRAR PARA EDITAR
function mostrar_material(idproducto) { 

  $("#cargando-3-fomulario").hide();
  $("#cargando-4-fomulario").show();
  
  limpiar_materiales();  

  $("#modal-agregar-material-activos-fijos").modal("show");

  $.post("../ajax/resumen_insumos.php?op=mostrar_materiales", { 'idproducto_p': idproducto }, function (data, status) {
    
    data = JSON.parse(data); console.log(data);    

    $("#idproducto_p").val(data.idproducto);
    $("#cont").val(cont);

    $("#nombre_p").val(data.nombre);
    $("#modelo_p").val(data.modelo);
    $("#serie_p").val(data.serie);
    $("#marca_p").val(data.marca);
    $("#descripcion_p").val(data.descripcion);

    $('#precio_unitario_p').val(parseFloat(data.precio_unitario).toFixed(2));
    $("#estado_igv_p").val(parseFloat(data.estado_igv).toFixed(2));
    $("#precio_sin_igv_p").val(parseFloat(data.precio_sin_igv).toFixed(2));
    $("#precio_igv_p").val(parseFloat(data.precio_igv).toFixed(2));
    $("#precio_total_p").val(parseFloat(data.precio_total).toFixed(2));
     
    $("#unid_medida_p").val(data.idunidad_medida).trigger("change");
    $("#color_p").val(data.idcolor).trigger("change");  
    $("#categoria_insumos_af_p").val(data.idcategoria_insumos_af).trigger("change");    

    if (data.estado_igv == "1") {
      $("#my-switch_igv").prop("checked", true);
    } else {
      $("#my-switch_igv").prop("checked", false);
    }
     
    if (data.imagen != "") {
      
      $("#foto2_i").attr("src", "../dist/docs/material/img_perfil/" + data.imagen);

      $("#foto2_actual").val(data.imagen);
    }

    // FICHA TECNICA
    if (data.ficha_tecnica == "" || data.ficha_tecnica == null  ) {

      $("#doc2_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

      $("#doc2_nombre").html('');

      $("#doc_old_2").val(""); $("#doc2").val("");

    } else {

      $("#doc_old_2").val(data.ficha_tecnica); 

      $("#doc2_nombre").html(`<div class="row"> <div class="col-md-12"><i>Ficha-tecnica.${extrae_extencion(data.ficha_tecnica)}</i></div></div>`);
      
      // cargamos la imagen adecuada par el archivo
      if ( extrae_extencion(data.ficha_tecnica) == "pdf" ) {

        $("#doc2_ver").html('<iframe src="../dist/docs/material/ficha_tecnica/'+data.ficha_tecnica+'" frameborder="0" scrolling="no" width="100%" height="210"> </iframe>');

      }else{
        if (
          extrae_extencion(data.ficha_tecnica) == "jpeg" || extrae_extencion(data.ficha_tecnica) == "jpg" || extrae_extencion(data.ficha_tecnica) == "jpe" ||
          extrae_extencion(data.ficha_tecnica) == "jfif" || extrae_extencion(data.ficha_tecnica) == "gif" || extrae_extencion(data.ficha_tecnica) == "png" ||
          extrae_extencion(data.ficha_tecnica) == "tiff" || extrae_extencion(data.ficha_tecnica) == "tif" || extrae_extencion(data.ficha_tecnica) == "webp" ||
          extrae_extencion(data.ficha_tecnica) == "bmp" || extrae_extencion(data.ficha_tecnica) == "svg" ) {

          $("#doc2_ver").html(`<img src="../dist/docs/material/ficha_tecnica/${data.ficha_tecnica}" alt="" width="50%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
          
        } else {
          $("#doc2_ver").html('<img src="../dist/svg/doc_si_extencion.svg" alt="" width="50%" >');
        }        
      }      
    } 

    $("#cargando-3-fomulario").show();
    $("#cargando-4-fomulario").hide();

  });
}

// DETALLE DEL MATERIAL
function mostrar_detalle_material(idproducto) {  
  
  $('#datosproductos').html(''+
  '<div class="row" >'+
    '<div class="col-lg-12 text-center">'+
      '<i class="fas fa-spinner fa-pulse fa-6x"></i><br />'+
      '<br />'+
      '<h4>Cargando...</h4>'+
    '</div>'+
  '</div>');

  var verdatos=''; var imagenver='';

  $("#modal-ver-detalle-material-activo-fijo").modal("show")

  $.post("../ajax/resumen_insumos.php?op=mostrar_materiales", { 'idproducto_p': idproducto }, function (data, status) {

    data = JSON.parse(data);  //console.log(data); 

    var imagen_perfil =data.imagen == '' || data.imagen == null ? '<img src="../dist/svg/default_producto.svg" alt="" width="90px">' : `<img src="../dist/docs/material/img_perfil/${data.imagen}" alt="" class="img-thumbnail" width="150px">`;
    var ficha_tecnica =data.ficha_tecnica == '' || data.ficha_tecnica == null ? '<center><i class="far fa-file-pdf fa-2x text-gray-50"></i></center>' : `<center><a target="_blank" href="../dist/docs/material/ficha_tecnica/${data.ficha_tecnica}"><i class="far fa-file-pdf fa-2x text-danger" ></i></a></center>`;

    verdatos=`                                                                            
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-hover table-bordered">        
            <tbody>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th rowspan="2">${imagen_perfil}</th>
                <td> <b>Nombre: </b> ${data.nombre}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <td> <b>Color: </b>  ${data.nombre_color}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Unidad Medida</th>
                <td>${data.nombre_medida}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Clasificación</th>
                <td>${data.categoria}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Modelo</th>
                <td>${data.modelo}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Serie</th>
                  <td>${data.serie}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Marca</th>
                <td>${data.marca}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Precio Unitario</th>
                <td>${data.precio_unitario}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>IGV</th>
                <td>${data.precio_igv}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Precio sin IGV</th>
                <td>${data.precio_sin_igv}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Precio con IGV</th>
                <td>${data.precio_total}</td>
              </tr>
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Descripción</th>
                <td><textarea cols="30" rows="1" class="text_area_clss" readonly >${data.descripcion}</textarea></td>
              </tr>              
              <tr data-widget="expandable-table" aria-expanded="false">
                <th>Ficha tecnica</th>
                <td> ${ficha_tecnica} </td>
              </tr>               
            </tbody>
          </table>
        </div>
      </div>
    </div>`;
  
    $("#datosproductos").html(verdatos);

  });

}

function precio_con_igv() {
  var precio_total = 0;
  var mont_igv = 0.0;

  var precio_base = 0;
  var igv = 0;
  var precio_re = 0;

  //var precio_r=0;
  precio_total = $("#precio_unitario_p").val();

  $("#precio_igv_p").val(mont_igv.toFixed(2));
  $("#precio_sin_igv_p").val(precio_total);

  if ($("#my-switch_igv").is(":checked")) {
    precio_base = precio_total / 1.18;
    igv = precio_total - precio_base;
    precio_re = parseFloat(precio_total) - igv;
    
    $("#precio_igv_p").val(igv.toFixed(2));
    $("#precio_sin_igv_p").val(precio_re.toFixed(2));
    $("#precio_total_p").val((precio_re + igv).toFixed(2));

    $("#estado_igv_p").val("1");
  } else {
    precio_base = precio_total * 1.18;

    igv = precio_base - precio_total;
    precio_re = parseFloat(precio_total) - igv;

    $("#precio_igv_p").val(igv.toFixed(2));
    $("#precio_sin_igv_p").val( parseFloat(precio_total).toFixed(2));
    $("#precio_total_p").val(precio_base.toFixed(2));

    $("#estado_igv_p").val("0");
  }
}

$("#my-switch_igv").on("click ", function (e) {

  var precio_ingresado = 0;
  var precio_sin_igv = 0;
  var igv = 0;
  var precio_total = 0;

  precio_ingresado = $("#precio_unitario_p").val(); 

  if ($("#my-switch_igv").is(":checked")) {
    precio_sin_igv = precio_ingresado / 1.18;
    igv = precio_ingresado - precio_sin_igv;
    precio_total = parseFloat(precio_sin_igv) + igv;   
    console.log(precio_sin_igv, igv, precio_total);
    $("#precio_sin_igv_p").val(redondearExp(precio_sin_igv, 2));

    $("#precio_igv_p").val(redondearExp(igv, 2));   

    $("#precio_total_p").val(redondearExp(precio_total, 2)) ;

    $("#estado_igv_p").val("1");
  } else {
    precio_sin_igv = precio_ingresado * 1.18;     
    igv = precio_sin_igv - precio_ingresado;
    precio_total = parseFloat(precio_ingresado) + igv;    
    console.log(precio_sin_igv, igv, precio_total);
    $("#precio_sin_igv_p").val(redondearExp(precio_ingresado, 2));

    $("#precio_igv_p").val(redondearExp(igv, 2));

    $("#precio_total_p").val(redondearExp(precio_total, 2) );

    $("#estado_igv_p").val("0");
  }
});

// :::::::::::::::::::::::::::::::::::::::::::::::::::: SECCION AGREGAR PROVEEDOR ::::::::::::::::::::::::::::::::::::::::::::::::::::
//Función limpiar
function limpiar_form_proveedor() {
  $("#idproveedor_prov").val("");
  $("#tipo_documento_prov option[value='RUC']").attr("selected", true);
  $("#nombre_prov").val("");
  $("#num_documento_prov").val("");
  $("#direccion_prov").val("");
  $("#telefono_prov").val("");
  $("#c_bancaria_prov").val("");
  $("#cci_prov").val("");
  $("#c_detracciones_prov").val("");
  $("#banco_prov").val("").trigger("change");
  $("#titular_cuenta_prov").val("");

  // Limpiamos las validaciones
  $(".form-control").removeClass("is-valid");
  $(".is-invalid").removeClass("error is-invalid");

  $(".tooltip").removeClass('show');
}

// damos formato a: Cta, CCI
function formato_banco() {

  if ($("#banco_prov").select2("val") == null || $("#banco_prov").select2("val") == "" || $("#banco_prov").select2("val") == "1" ) {

    $("#c_bancaria_prov").prop("readonly", true);
    $("#cci_prov").prop("readonly", true);
    $("#c_detracciones_prov").prop("readonly", true);

  } else {
    
    $(".chargue-format-1").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-2").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');
    $(".chargue-format-3").html('<i class="fas fa-spinner fa-pulse fa-lg text-danger"></i>');    

    $.post("../ajax/resumen_insumos.php?op=formato_banco", { 'idbanco': $("#banco_prov").select2("val") }, function (data, status) {
      
      data = JSON.parse(data);  // console.log(data);

      $(".chargue-format-1").html("Cuenta Bancaria");
      $(".chargue-format-2").html("CCI");
      $(".chargue-format-3").html("Cuenta Detracciones");

      $("#c_bancaria_prov").prop("readonly", false);
      $("#cci_prov").prop("readonly", false);
      $("#c_detracciones_prov").prop("readonly", false);

      var format_cta = decifrar_format_banco(data.formato_cta);
      var format_cci = decifrar_format_banco(data.formato_cci);
      var formato_detracciones = decifrar_format_banco(data.formato_detracciones);
      // console.log(format_cta, formato_detracciones);

      $("#c_bancaria_prov").inputmask(`${format_cta}`);
      $("#cci_prov").inputmask(`${format_cci}`);
      $("#c_detracciones_prov").inputmask(`${formato_detracciones}`);
    });
  }
}

function decifrar_format_banco(format) {

  var array_format =  format.split("-"); var format_final = "";

  array_format.forEach((item, index)=>{

    for (let index = 0; index < parseInt(item); index++) { format_final = format_final.concat("9"); }   

    if (parseInt(item) != 0) { format_final = format_final.concat("-"); }
  });

  var ultima_letra = format_final.slice(-1);
   
  if (ultima_letra == "-") { format_final = format_final.slice(0, (format_final.length-1)); }

  return format_final;
}

//guardar proveedor
function guardar_proveedor(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#form-proveedor")[0]);

  $.ajax({
    url: "../ajax/resumen_insumos.php?op=guardar_proveedor",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      if (datos == "ok") {
        // toastr.success("proveedor registrado correctamente");
        Swal.fire("Correcto!", "Proveedor guardado correctamente.", "success");
         
        limpiar_form_proveedor();

        $("#modal-agregar-proveedor").modal("hide");

        //Cargamos los items al select cliente
        $.post("../ajax/compra.php?op=selectProveedor", function (r) {  $("#idproveedor").html(r); });

      } else {
        // toastr.error(datos);
        Swal.fire("Error!", datos, "error");
      }
    },
  });
}

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {
  $("#form-compras").validate({
    rules: {
      idproveedor: { required: true },
      tipo_comprovante: { required: true },
      serie_comprovante: { minlength: 2 },
      descripcion: { minlength: 4 },
      fecha_compra: { required: true },
    },
    messages: {
      idproveedor: {
        required: "Por favor debe seleccionar un proveedor.",
      },
      tipo_comprovante: {
        required: "Por favor debe seleccionar tipo de comprobante.",
      },
      serie_comprovante: {
        minlength: "mayor a 2 caracteres",
      },
      descripcion: {
        minlength: "mayor a 4 caracteres",
      },
      fecha_compra: {
        required: "Campo requerido",
      },
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

    submitHandler: function (form) {
      guardar_y_editar_compras(form);
    },
  });

  $("#form-proveedor").validate({
    rules: {
      tipo_documento_prov: { required: true },
      num_documento_prov: { required: true, minlength: 6, maxlength: 20 },
      nombre_prov: { required: true, minlength: 6, maxlength: 100 },
      direccion_prov: { minlength: 5, maxlength: 150 },
      telefono_prov: { minlength: 8 },
      c_bancaria_prov: { minlength: 6,  },
      cci_prov: { minlength: 6,  },
      c_detracciones_prov: { minlength: 6,  },      
      banco_prov: { required: true },
      titular_cuenta_prov: { minlength: 4 },
    },
    messages: {
      tipo_documento_prov: { required: "Por favor selecione un tipo de documento", },
      num_documento_prov: {
        required: "Ingrese un número de documento",
        minlength: "Ingrese como MÍNIMO 6 caracteres.",
        maxlength: "Ingrese como MÁXIMO 20 caracteres.",
      },
      nombre_prov: {
        required: "Por favor ingrese los nombres y apellidos",
        minlength: "Ingrese como MÍNIMO 6 caracteres.",
        maxlength: "Ingrese como MÁXIMO 100 caracteres.",
      },
      direccion_prov: {
        minlength: "Ingrese como MÍNIMO 5 caracteres.",
        maxlength: "Ingrese como MÁXIMO 150 caracteres.",
      },
      telefono_prov: { minlength: "Ingrese como MÍNIMO 9 caracteres.", },
      c_bancaria_prov: { minlength: "Ingrese como MÍNIMO 6 caracteres.", },
      cci_prov: { minlength: "Ingrese como MÍNIMO 6 caracteres.",  },
      c_detracciones_prov: { minlength: "Ingrese como MÍNIMO 6 caracteres.", },      
      banco_prov: { required: "Por favor  seleccione un banco",  },
      titular_cuenta_prov: { minlength: 'Ingrese como MÍNIMO 4 caracteres.' },
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

    submitHandler: function (e) {
      guardar_proveedor(e);
    },
  });   

  $("#form-materiales").validate({
    rules: {
      nombre_p: { required: true, minlength:3, maxlength:200},
      categoria_insumos_af_p: { required: true },
      color_p: { required: true },
      unid_medida_p: { required: true },
      modelo_p: { required: true },
      precio_unitario_p: { required: true },
      descripcion_p: { minlength: 3 },
    },
    messages: {
      nombre_p: { required: "Por favor ingrese nombre", minlength:"Minimo 3 caracteres", maxlength:"Maximo 200 caracteres" },
      categoria_insumos_af_p: { required: "Campo requerido", },
      color_p: { required: "Campo requerido" },
      unid_medida_p: { required: "Campo requerido" },
      modelo_p: { required: "Por favor ingrese modelo", },
      precio_unitario_p: { required: "Ingresar precio compra", },      
      descripcion_p: { minlength: "Minimo 3 caracteres" },
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

    submitHandler: function (e) {
      guardar_materiales(e);
    },
  });
});
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

/* PREVISUALIZAR LAS IMAGENES */
function addImage(e, id) {
  // colocamos cargando hasta que se vizualice
  $("#" + id + "_ver").html('<i class="fas fa-spinner fa-pulse fa-6x"></i><br><br>');

  console.log(id);

  var file = e.target.files[0], imageType = /image.*/;

  if (e.target.files[0]) {
    var sizeByte = file.size;

    var sizekiloBytes = parseInt(sizeByte / 1024);

    var sizemegaBytes = sizeByte / 1000000; 

    if (!file.type.match(imageType)) {
       
      // toastr.error("Este tipo de ARCHIVO no esta permitido <br> elija formato: <b>.png .jpeg .jpg .webp etc... </b>");
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Este tipo de ARCHIVO no esta permitido elija formato: .png .jpeg .jpg .webp etc...',
        showConfirmButton: false,
        timer: 1500
      });

      $("#" + id + "_i").attr("src", "../dist/img/default/img_defecto_activo_fijo_material.png");

    } else {

      if (sizekiloBytes <= 10240) {

        var reader = new FileReader();

        reader.onload = fileOnload;

        function fileOnload(e) {

          var result = e.target.result;

          $(`#${id}_i`).attr("src", result);

          $(`#${id}_nombre`).html(
            
            `<div class="row">
              <div class="col-md-12"> <i> ${file.name} </i></div>
              <div class="col-md-12">                
                <button class="btn btn-danger btn-block btn-xs" onclick="${id}_eliminar();" type="button" >
                  <i class="far fa-trash-alt"></i>
                </button>
              </div>               
            </div>`               
          );

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
          title: `El documento: ${file.name.toUpperCase()} es muy pesado. Tamaño máximo 10mb`,
          showConfirmButton: false,
          timer: 1500
        })
        $("#" + id + "_i").attr("src", "../dist/img/default/img_error.png");

        $("#" + id).val("");
      }
    }
  } else {
    Swal.fire({
      position: 'top-end',
      icon: 'error',
      title: 'Seleccione un documento',
      showConfirmButton: false,
      timer: 1500
    })

    $("#" + id + "_i").attr("src", "../dist/img/default/img_defecto_activo_fijo_material.png");

    $("#" + id + "_nombre").html("");
  }
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

      $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >'); 

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

                          $("#"+id+"_ver").html(`<img src="${result}" alt="" width="50%" onerror="this.src='../dist/svg/error-404-x.svg';" >`); 
                          
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
        });

        $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
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
    });
		 
    $("#"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');
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

      $("#doc"+id+"_ver").html('<img src="../dist/svg/pdf_trasnparent.svg" alt="" width="50%" >');

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
            $("#doc"+id+"_ver").html(`<iframe src="../dist/docs/compra/${carpeta}/${antiguopdf}" frameborder="0" scrolling="no" width="100%" height="310"></iframe>`);
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
  
                      $("#doc"+id+"_ver").html(`<img src="../dist/docs/compra/${carpeta}/${antiguopdf}" alt="" onerror="this.src='../dist/svg/error-404-x.svg';" width="50%" >`);
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

                    $("#doc"+id+"_ver").html(`<img src="${pdffile_url}" alt="" width="50%" >`);
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

function dowload_pdf() {
  toastr.success("El documento se descargara en breve!!");
}

function extrae_extencion(filename) {
  return filename.split(".").pop();
}

// Buscar Reniec SUNAT
function buscar_sunat_reniec() {
  $("#search").hide();

  $("#charge").show();

  let tipo_doc = $("#tipo_documento_prov").val();

  let dni_ruc = $("#num_documento_prov").val(); 
   
  if (tipo_doc == "DNI") {

    if (dni_ruc.length == "8") {

      $.post("../ajax/persona.php?op=reniec", { dni: dni_ruc }, function (data, status) {

        data = JSON.parse(data);  console.log(data);

        if (data == null) {

          $("#search").show();
  
          $("#charge").hide();
  
          toastr.error("Verifique su conexion a internet o el sistema de BUSQUEDA esta en mantenimiento.");
          
        } else {
          if (data.success == false) {

            $("#search").show();

            $("#charge").hide();

            toastr.error("Es probable que el sistema de busqueda esta en mantenimiento o los datos no existe en la RENIEC!!!");

          } else {

            $("#search").show();

            $("#charge").hide();

            $("#nombre_prov").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);
            $("#titular_cuenta_prov").val(data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno);

            toastr.success("Persona encontrada!!!!");
          }
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

          data = JSON.parse(data);    console.log(data);

          if (data == null) {
            $("#search").show();
    
            $("#charge").hide();
    
            toastr.error("Verifique su conexion a internet o el sistema de BUSQUEDA esta en mantenimiento.");
            
          } else {

            if (data.success == false) {

              $("#search").show();

              $("#charge").hide();

              toastr.error("Datos no encontrados en la SUNAT!!!");
              
            } else {

              if (data.estado == "ACTIVO") {

                $("#search").show();

                $("#charge").hide();

                data.razonSocial == null ? $("#nombre_prov").val(data.nombreComercial) : $("#nombre_prov").val(data.razonSocial);

                data.razonSocial == null ? $("#titular_cuenta_prov").val(data.nombreComercial) : $("#titular_cuenta_prov").val(data.razonSocial);

                var departamento = (data.departamento == null ? "" : data.departamento); 
                var provincia = (data.provincia == null ? "" : data.provincia);
                var distrito = (data.distrito == null ? "" : data.distrito);                

                data.direccion == null ? $("#direccion_prov").val(`${departamento} - ${provincia} - ${distrito}`) : $("#direccion_prov").val(data.direccion);

                toastr.success("Persona encontrada!!");

              } else {

                toastr.info("Se recomienda NO generar FACTURAS ó BOLETAS!!!");

                $("#search").show();

                $("#charge").hide();

                data.razonSocial == null ? $("#nombre_prov").val(data.nombreComercial) : $("#nombre_prov").val(data.razonSocial);

                data.razonSocial == null ? $("#titular_cuenta_prov").val(data.nombreComercial) : $("#titular_cuenta_prov").val(data.razonSocial);
                
                var departamento = (data.departamento == null ? "" : data.departamento); 
                var provincia = (data.provincia == null ? "" : data.provincia);
                var distrito = (data.distrito == null ? "" : data.distrito);

                data.direccion == null ? $("#direccion_prov").val(`${data.departamento} - ${data.provincia} - ${data.distrito}`) : $("#direccion_prov").val(data.direccion);

              }
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

init();