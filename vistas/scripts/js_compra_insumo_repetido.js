var array_class_compra = [];

//Declaración de variables necesarias para trabajar con las compras y sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;

function agregarDetalleComprobante(idproducto) {  
  var cantidad = 1;    
  $(`.btn-add-producto-${idproducto}`).html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $.post("../ajax/ajax_general.php?op=mostrar_producto", { idproducto: idproducto }, function (e, status) {
    
    e = JSON.parse(e);  console.log(e);
    if (e.status == true) {
      if (e.data == null || e.status == '' ) {
        toastr_error("NO EXISTE!!",`El producto que esta buscando no existe, ingrese un código nuevo.`, 700);
      } else {  
        if ($(".producto_" + idproducto).hasClass("producto_selecionado")) {          

          var cant_producto = $(".producto_" + idproducto).val(); 

          sub_total = parseInt(cant_producto, 10) + 1;

          $(".producto_" + idproducto).val(sub_total);

          modificarSubtotales();
        } else {          

          var img_p = e.data.imagen == "" || e.data.imagen == null ?  `../dist/docs/material/img_perfil/producto-sin-foto.svg` : `../dist/docs/material/img_perfil/${e.data.imagen}`; 
          var marca_ = $("#marca_table_" + idproducto).val();
          var promedio_precio_ = $(".promedio_precio_" + idproducto).text();
          var fila = `
          <tr class="filas" id="fila${cont}">         
            <td class="py-1">
              <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_material(${idproducto}, ${cont})"><i class="fas fa-pencil-alt"></i></button>
              <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})"><i class="fas fa-times"></i></button>
            </td>
            <td class="py-1">         
              <input type="hidden" name="idproducto[]" value="${idproducto}">
              <input type="hidden" name="ficha_tecnica_producto[]" value="${e.data.ficha_tecnica}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img_p}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_material('${img_p}', '${encodeHtml(e.data.nombre)}')">
                <span class="username"><p class="mb-0 nombre_producto_${cont}">${e.data.nombre}</p></span>
                <span class="description clasificacion_${cont}"><b>Clasificación: </b>${e.data.categoria} | <b>Marca: </b> <select name="nombre_marca[]" id="nombre_marca_${cont}" >${e.data.marca_html_option}</select></span>
              </div>
            </td>
            <td class="py-1">
              <span class="unidad_medida_${cont}">${e.data.nombre_medida}</span> 
              <input type="hidden" name="unidad_medida[]" value="${e.data.nombre_medida}">
              <input type="hidden" name="nombre_color[]" value="${e.data.nombre_color}">              
            </td>
            <td class="py-1 form-group"><input class="w-100px producto_${idproducto} producto_selecionado cantidad_${cont} form-control" type="number" name="cantidad[]" value="${cantidad}" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="py-1 hidden"><input class="w-135px precio_sin_igv_${cont} input-no-border" type="number" name="precio_sin_igv[]" value="0" readonly min="0" ></td>
            <td class="py-1 hidden"><input class="w-135px precio_igv_${cont} input-no-border" type="number" name="precio_igv[]" value="0" readonly ></td>
            <td class="py-1 form-group"><input class="w-135px precio_con_igv_${cont} form-control" type="number" name="precio_con_igv[]" value="${(promedio_precio_)}" min="0.01" required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
            <td class="py-1 form-group"><input class="w-135px descuento_${cont} form-control" type="number" name="descuento[]" value="0" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="py-1 text-right"><span class="subtotal_producto_${cont}" >0</span></td>
            <td class="py-1"><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles tbody").append(fila);

          array_class_compra.push({ id_cont: cont });

          modificarSubtotales();

          // selecionamos la marca
          $(`#nombre_marca_${cont} option[value='${marca_}']`).prop('selected', true);
          
          // toastr_success("Agregado!!",`Material: ${nombre} agregado !!`, 700);

          cont++;
          evaluar();

          $(".orden_producto").sortable().disableSelection();
        }      
      }
    } else {
      ver_errores(e);
    }
    $(`.btn-add-producto-${idproducto}`).html(`<span class="fa fa-plus"></span>`);
    
  }).fail( function(e) { ver_errores(e); } );
}

function agregar_detalle_comprobante_x_codigo(idproducto) {  
  var cantidad = 1;    
  $(`.btn-code-producto`).html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $.post("../ajax/ajax_general.php?op=mostrar_producto", { idproducto: idproducto }, function (e, status) {
    
    e = JSON.parse(e);  console.log(e);
    if (e.status == true) {
      if (e.data == null || e.status == '' ) {
        toastr_error("NO EXISTE!!",`El producto que esta buscando no existe, ingrese un código nuevo.`, 700);
      } else {  
        if ($(".producto_" + idproducto).hasClass("producto_selecionado")) {          

          var cant_producto = $(".producto_" + idproducto).val(); 

          sub_total = parseInt(cant_producto, 10) + 1;

          $(".producto_" + idproducto).val(sub_total);

          modificarSubtotales();
        } else {          

          var img_p = e.data.imagen == "" || e.data.imagen == null ?  `../dist/docs/material/img_perfil/producto-sin-foto.svg` : `../dist/docs/material/img_perfil/${e.data.image}`; 
          var marca_ = $("#marca_table_" + idproducto).val();
          // var promedio_precio_ = $(".promedio_precio_" + idproducto).text();
          var html_option_marca = '';
          if (e.data.marcas.length === 0) {
            html_option_marca = `<option value="SIN MARCA">SIN MARCA</option>`;
          } else {
            e.data.marcas.forEach((val, key) => {
              html_option_marca = html_option_marca.concat(`<option value="${val}">${val}</option>`);
            });
          }
          
          var fila = `
          <tr class="filas" id="fila${cont}">         
            <td class="py-1">
              <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_material(${idproducto}, ${cont})"><i class="fas fa-pencil-alt"></i></button>
              <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})"><i class="fas fa-times"></i></button>
            </td>
            <td class="py-1">         
              <input type="hidden" name="idproducto[]" value="${idproducto}">
              <input type="hidden" name="ficha_tecnica_producto[]" value="${e.data.ficha_tecnica}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img_p}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_material('${img_p}', '${encodeHtml(e.data.nombre)}')">
                <span class="username"><p class="mb-0 nombre_producto_${cont}">${e.data.nombre}</p></span>
                <span class="description clasificacion_${cont}"><b>Clasificación: </b>${e.data.categoria} | <b>Marca: </b> <select name="nombre_marca[]">${html_option_marca}</select></span>
              </div>
            </td>
            <td class="py-1">
              <span class="unidad_medida_${cont}">${e.data.nombre_medida}</span> 
              <input type="hidden" name="unidad_medida[]" value="${e.data.nombre_medida}">
              <input type="hidden" name="nombre_color[]" value="${e.data.nombre_color}">              
            </td>
            <td class="py-1 form-group"><input class="w-100px producto_${idproducto} producto_selecionado cantidad_${cont} form-control" type="number" name="cantidad[]" value="${cantidad}" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="py-1 hidden"><input class="w-135px precio_sin_igv_${cont} input-no-border" type="number" name="precio_sin_igv[]" value="0" readonly min="0" ></td>
            <td class="py-1 hidden"><input class="w-135px precio_igv_${cont} input-no-border" type="number" name="precio_igv[]" value="0" readonly ></td>
            <td class="py-1 form-group"><input class="w-135px precio_con_igv_${cont} form-control" type="number" name="precio_con_igv[]" value="0" min="0.01" required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
            <td class="py-1 form-group"><input class="w-135px descuento_${cont} form-control" type="number" name="descuento[]" value="0" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="py-1 text-right"><span class="subtotal_producto_${cont}" >0</span></td>
            <td class="py-1"><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles tbody").append(fila);

          array_class_compra.push({ id_cont: cont });

          modificarSubtotales();
          
          // toastr_success("Agregado!!",`Material: ${nombre} agregado !!`, 700);

          cont++;
          evaluar();

          $(".orden_producto").sortable().disableSelection();
        }      
      }
    } else {
      ver_errores(e);
    }
    $(`.btn-code-producto`).html(`<span class="fa fa-plus"></span>`);
    
  }).fail( function(e) { ver_errores(e); } );
}

function evaluar() {
  if (detalles > 0) {
    $("#guardar_registro_compras").show();
  } else {
    $("#guardar_registro_compras").hide();
    cont = 0;
    $(".subtotal_compra").html("S/ 0.00");
    $("#subtotal_compra").val(0);

    $(".igv_compra").html("S/ 0.00");
    $("#igv_compra").val(0);

    $(".total_venta").html("S/ 0.00");
    $("#total_compra").val(0);

  }
}

function default_val_igv() { 
  if ($("#tipo_comprobante").select2("val") == "Factura" || $("#tipo_comprobante").select2("val") == "Nota de Crédito") { 
    $("#val_igv").val(0.18); } 
}

function modificarSubtotales() {  

  var val_igv = $('#val_igv').val(); //console.log(array_class_compra);

  if ($("#tipo_comprobante").select2("val") == null) {

    $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

    $("#colspan_subtotal").attr("colspan", 5); //cambiamos el: colspan

    $("#val_igv").val(0);
    $("#val_igv").prop("readonly",true);
    $(".val_igv").html('IGV (0%)');

    $("#tipo_gravada").val('NO GRAVADA');
    $(".tipo_gravada").html('NO GRAVADA');

    if (array_class_compra.length === 0) {
    } else {
      array_class_compra.forEach((key, index) => {
        var cantidad = $(`.cantidad_${key.id_cont}`).val() == '' || $(`.cantidad_${key.id_cont}`).val() == null ? 0 : parseFloat($(`.cantidad_${key.id_cont}`).val());
        var precio_con_igv = $(`.precio_con_igv_${key.id_cont}`).val() == '' || $(`.precio_con_igv_${key.id_cont}`).val() == null ? 0 : parseFloat($(`.precio_con_igv_${key.id_cont}`).val());
        var deacuento = $(`.descuento_${key.id_cont}`).val() == '' || $(`.descuento_${key.id_cont}`).val() == null ? 0 : parseFloat($(`.descuento_${key.id_cont}`).val());
        var subtotal_producto = 0;

        // Calculamos: IGV
        var precio_sin_igv = precio_con_igv;
        $(`.precio_sin_igv_${key.id_cont}`).val(precio_sin_igv);

        // Calculamos: precio + IGV
        var igv = 0;
        $(`.precio_igv_${key.id_cont}`).val(igv);

        // Calculamos: Subtotal de cada producto
        subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
        $(`.subtotal_producto_${key.id_cont}`).html(formato_miles(subtotal_producto.toFixed(4)));
      });
      calcularTotalesSinIgv();
    }
  } else {
    if ($("#tipo_comprobante").select2("val") == "Factura" || $("#tipo_comprobante").select2("val") == "Nota de Crédito") {

      $(".hidden").show(); //Mostramos: IGV, PRECIO SIN IGV

      $("#colspan_subtotal").attr("colspan", 7); //cambiamos el: colspan
      
      $("#val_igv").prop("readonly",false);

      if (array_class_compra.length === 0) {
        if (val_igv == '' || val_igv <= 0) {
          $("#tipo_gravada").val('NO GRAVADA');
          $(".tipo_gravada").html('NO GRAVADA');
          $(".val_igv").html(`IGV (0%)`);
        } else {
          $("#tipo_gravada").val('GRAVADA');
          $(".tipo_gravada").html('GRAVADA');
          $(".val_igv").html(`IGV (${(parseFloat(val_igv) * 100).toFixed(2)}%)`);
        }
        
      } else {
        // validamos el valor del igv ingresado        

        array_class_compra.forEach((key, index) => {
          var cantidad = $(`.cantidad_${key.id_cont}`).val() == '' || $(`.cantidad_${key.id_cont}`).val() == null ? 0 : parseFloat($(`.cantidad_${key.id_cont}`).val());
          var precio_con_igv = $(`.precio_con_igv_${key.id_cont}`).val() == '' || $(`.precio_con_igv_${key.id_cont}`).val() == null ? 0 : parseFloat($(`.precio_con_igv_${key.id_cont}`).val());
          var deacuento = $(`.descuento_${key.id_cont}`).val() == '' || $(`.descuento_${key.id_cont}`).val() == null ? 0 : parseFloat($(`.descuento_${key.id_cont}`).val());
          var subtotal_producto = 0;

          // Calculamos: Precio sin IGV
          var precio_sin_igv = ( quitar_igv_del_precio(precio_con_igv, val_igv, 'decimal')).toFixed(2);
          $(`.precio_sin_igv_${key.id_cont}`).val(precio_sin_igv);

          // Calculamos: IGV
          var igv = (parseFloat(precio_con_igv) - parseFloat(precio_sin_igv)).toFixed(2);
          $(`.precio_igv_${key.id_cont}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
          $(`.subtotal_producto_${key.id_cont}`).html(formato_miles(subtotal_producto.toFixed(2)));
        });

        calcularTotalesConIgv();
      }
    } else {

      $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV

      $("#colspan_subtotal").attr("colspan", 5); //cambiamos el: colspan

      $("#val_igv").val(0);
      $("#val_igv").prop("readonly",true);
      $(".val_igv").html('IGV (0%)');

      $("#tipo_gravada").val('NO GRAVADA');
      $(".tipo_gravada").html('NO GRAVADA');

      if (array_class_compra.length === 0) {
      } else {
        array_class_compra.forEach((key, index) => {
          var cantidad = $(`.cantidad_${key.id_cont}`).val() == '' || $(`.cantidad_${key.id_cont}`).val() == null ? 0 : parseFloat($(`.cantidad_${key.id_cont}`).val());
          var precio_con_igv = $(`.precio_con_igv_${key.id_cont}`).val() == '' || $(`.precio_con_igv_${key.id_cont}`).val() == null ? 0 : parseFloat($(`.precio_con_igv_${key.id_cont}`).val());
          var deacuento = $(`.descuento_${key.id_cont}`).val() == '' || $(`.descuento_${key.id_cont}`).val() == null ? 0 : parseFloat($(`.descuento_${key.id_cont}`).val());
          var subtotal_producto = 0;

          // Calculamos: IGV
          var precio_sin_igv = precio_con_igv;
          $(`.precio_sin_igv_${key.id_cont}`).val(precio_sin_igv);

          // Calculamos: precio + IGV
          var igv = 0;
          $(`.precio_igv_${key.id_cont}`).val(igv);

          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - deacuento;
          $(`.subtotal_producto_${key.id_cont}`).html(formato_miles(subtotal_producto.toFixed(4)));
        });

        calcularTotalesSinIgv();
      }
    }
  }
  toastr_success("Actualizado!!",`Precio Actualizado.`, 700);
}

function calcularTotalesSinIgv() {
  var total = 0.0;
  var igv = 0;
  var mtotal = 0;

  if (array_class_compra.length === 0) {
  } else {
    array_class_compra.forEach((element, index) => {
      total += parseFloat(quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()));
    });

    $(".subtotal_compra").html("S/ " + formato_miles(total));
    $("#subtotal_compra").val(redondearExp(total, 4));

    $(".igv_compra").html("S/ 0.00");
    $("#igv_compra").val(0.0);
    $(".val_igv").html('IGV (0%)');

    $(".total_venta").html("S/ " + formato_miles(total.toFixed(2)));
    $("#total_venta").val(redondearExp(total, 4));
  }
}

function calcularTotalesConIgv() {
  var val_igv = $('#val_igv').val();
  var igv = 0;
  var total = 0.0;

  var subotal_sin_igv = 0;

  array_class_compra.forEach((element, index) => {
    total += parseFloat(quitar_formato_miles($(`.subtotal_producto_${element.id_cont}`).text()));
  });

  //console.log(total); 

  subotal_sin_igv = quitar_igv_del_precio(total, val_igv, 'decimal').toFixed(2);
  igv = (parseFloat(total) - parseFloat(subotal_sin_igv)).toFixed(2);

  $(".subtotal_compra").html(`S/ ${formato_miles(subotal_sin_igv)}`);
  $("#subtotal_compra").val(redondearExp(subotal_sin_igv, 4));

  $(".igv_compra").html("S/ " + formato_miles(igv));
  $("#igv_compra").val(igv);

  $(".total_venta").html("S/ " + formato_miles(total.toFixed(2)));
  $("#total_venta").val(redondearExp(total, 4));

  total = 0.0;
}

function quitar_igv_del_precio(precio , igv, tipo ) {
  
  var precio_sin_igv = 0;

  switch (tipo) {
    case 'decimal':

      // validamos el valor del igv ingresado
      if (igv > 0 && igv <= 1) { 
        $("#tipo_gravada").val('GRAVADA');
        $(".tipo_gravada").html('GRAVADA');
        $(".val_igv").html(`IGV (${(parseFloat(igv) * 100).toFixed(2)}%)`); 
      } else { 
        igv = 0; 
        $(".val_igv").html('IGV (0%)'); 
        $("#tipo_gravada").val('NO GRAVADA');
        $(".tipo_gravada").html('NO GRAVADA');
      }

      if (parseFloat(precio) != NaN && igv > 0 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( ( parseFloat(igv) * 100 ) + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;

    case 'entero':
      
      // validamos el valor del igv ingresado
      if (igv > 0 && igv <= 100) { 
        $("#tipo_gravada").val('GRAVADA');
        $(".tipo_gravada").html('GRAVADA');
        $(".val_igv").html(`IGV (${parseFloat(igv)}%)`); 
      } else { 
        igv = 0; 
        $(".val_igv").html('IGV (0%)'); 
        $("#tipo_gravada").val('NO GRAVADA');
        $(".tipo_gravada").html('NO GRAVADA');
      }

      if (parseFloat(precio) != NaN && igv > 0 ) {
        precio_sin_igv = ( parseFloat(precio) * 100 ) / ( parseFloat(igv)  + 100 )
      }else{
        precio_sin_igv = precio;
      }
    break;
  
    default:
      $(".val_igv").html('IGV (0%)');
      toastr_error("Vacio!!","No has difinido un tipo de calculo de IGV", 700);
    break;
  } 
  
  return precio_sin_igv; 
}

function ocultar_comprob() {
  if ($("#tipo_comprobante").select2("val") == "Ninguno") {
    $("#content-serie-comprobante").hide();

    $("#content-serie-comprobante").val("");

    $("#content_slt2_serie_comprobante").hide();

    $("#content_slt2_serie_comprobante").val("");
    $("#slt2_serie_comprobante").val("null").trigger("change");

    $("#content-descripcion").removeClass("col-lg-5").addClass("col-lg-7");
    $("#content-tipo-comprobante").removeClass("col-lg-2").addClass("col-lg-4");
    // slt2_serie_comprobante
  } else {

    if ($("#tipo_comprobante").select2("val") == "Nota de Crédito") {
      // $("#val_igv").prop("readonly",false);
      $("#content-serie-comprobante").show();

      // content-tipo-comprobante
      $("#content-descripcion").removeClass("col-lg-7").addClass("col-lg-5");
      $("#content-tipo-comprobante").removeClass("col-lg-4").addClass("col-lg-2");
      $("#content_slt2_serie_comprobante").show();

    } else {
      $("#slt2_serie_comprobante").val("null").trigger("change");
      $("#content_slt2_serie_comprobante").hide();
      $("#content-serie-comprobante").show();

      $("#content-descripcion").removeClass("col-lg-7").addClass("col-lg-5");
      $("#content-tipo-comprobante").removeClass("col-lg-2").addClass("col-lg-4");

  
    }

  }


}

function eliminarDetalle(indice) {
  $("#fila" + indice).remove();

  array_class_compra.forEach(function (car, index, object) {
    if (car.id_cont === indice) {
      object.splice(index, 1);
    }
  });

  modificarSubtotales();

  detalles = detalles - 1;

  evaluar();

  toastr_warning("Removido!!","Producto removido", 700);
}

//mostramos para editar el datalle del comprobante de la compras
function mostrar_compra_insumo(idcompra_proyecto) {

  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  limpiar_form_compra();
  array_class_compra = [];

  cont = 0;
  detalles = 0;
  table_show_hide(2);

  $.post("../ajax/ajax_general.php?op=ver_compra_editar", { idcompra_proyecto: idcompra_proyecto }, function (e, status) {
    
    e = JSON.parse(e);  console.log(e);

    if (e.status == true) {

      if (e.data.tipo_comprobante == "Factura") {
        $(".content-igv").show();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
        $(".content-serie-comprobante").show();
      } else if (e.data.tipo_comprobante == "Boleta" || e.data.tipo_comprobante == "Nota de venta") {
        $(".content-serie-comprobante").show();
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
      } else if (e.data.tipo_comprobante == "Ninguno") {
        $(".content-serie-comprobante").hide();
        $(".content-serie-comprobante").val("");
        $(".content-igv").hide();
        $(".content-tipo-comprobante").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
        $(".content-descripcion").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
      } else {
        $(".content-serie-comprobante").show();
        //$(".content-descripcion").removeClass("col-lg-7").addClass("col-lg-4");
      }

      $("#idproyecto").val(e.data.idproyecto);
      $("#idcompra_proyecto").val(e.data.idcompra_x_proyecto);
      $("#idproveedor").val(e.data.idproveedor).trigger("change");
      $("#fecha_compra").val(e.data.fecha_compra);
      $("#tipo_comprobante").val(e.data.tipo_comprobante).trigger("change");
      $("#serie_comprobante").val(e.data.serie_comprobante).trigger("change");
      $("#slt2_serie_comprobante").val(e.data.nc_serie_comprobante).trigger("change");
      $("#val_igv").val(e.data.val_igv);
      $("#descripcion").val(e.data.descripcion);
      $("#glosa").val(e.data.glosa).trigger("change");
      console.log('Glosa : '+e.data.glosa);

      if (e.data.estado_detraccion == 0) {
        $("#estado_detraccion").val("0");
        $('#my-switch_detracc').prop('checked', false); 
      } else {
        $("#estado_detraccion").val("1");
        $('#my-switch_detracc').prop('checked', true); 
      }

      if (e.data.producto) {

        e.data.producto.forEach((val, index) => {

          var img = "";

          if (val.imagen == "" || val.imagen == null) { img = `../dist/docs/material/img_perfil/producto-sin-foto.svg`; } else { img = `../dist/docs/material/img_perfil/${val.imagen}`;  }

          var fila = `
          <tr class="filas" id="fila${cont}">
            <td class="py-1">
              <button type="button" class="btn btn-warning btn-sm" onclick="mostrar_material(${val.idproducto}, ${cont})"><i class="fas fa-pencil-alt"></i></button>
              <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${cont})"><i class="fas fa-times"></i></button></td>
            </td>
            <td class="py-1">
              <input type="hidden" name="idproducto[]" value="${val.idproducto}">
              <input type="hidden" name="ficha_tecnica_producto[]" value="${val.ficha_tecnica_producto}">
              <div class="user-block text-nowrap">
                <img class="profile-user-img img-responsive img-circle cursor-pointer img_perfil_${cont}" src="${img}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_img_material('${img}', '${encodeHtml(val.nombre_producto)}')">
                <span class="username"><p class="mb-0 nombre_producto_${cont}" >${val.nombre_producto}</p></span>
                <span class="description clasificacion_${cont}"><b>Clasificación: </b>${val.categoria} | <b>Marca: </b><select name="nombre_marca[]" id="nombre_marca_${cont}" >${val.marca_html_option}</select> </span>
              </div>
            </td>
            <td class="py-1"> 
              <span class="unidad_medida_${cont}">${val.unidad_medida}</span> 
              <input type="hidden" name="unidad_medida[]" value="${val.unidad_medida}"> 
              <input type="hidden" name="nombre_color[]" value="${val.color}">
            </td>
            <td class="py-1 form-group"><input class="w-100px form-control producto_${val.idproducto} producto_selecionado cantidad_${cont} " type="number" name="cantidad[]" value="${val.cantidad}" min="0.01" required onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="py-1 hidden"><input class="w-135px input-no-border precio_sin_igv_${cont}" type="number" name="precio_sin_igv[]" value="${val.precio_sin_igv}" readonly ></td>
            <td class="py-1 hidden"><input class="w-135px input-no-border precio_igv_${cont}" type="number" name="precio_igv[]" value="${val.igv}" readonly ></td>
            <td class="py-1 form-group"><input class="w-135px form-control precio_con_igv_${cont}" type="number" name="precio_con_igv[]" value="${parseFloat(val.precio_con_igv).toFixed(2)}" min="0.01" required onkeyup="modificarSubtotales();" onchange="modificarSubtotales();"></td>
            <td class="py-1 form-group"><input class="w-135px form-control descuento_${cont}" type="number" name="descuento[]" value="${val.descuento}" onkeyup="modificarSubtotales()" onchange="modificarSubtotales()"></td>
            <td class="py-1 text-right"><span class="subtotal_producto_${cont}" >0.00</span></td>
            <td class="py-1"><button type="button" onclick="modificarSubtotales()" class="btn btn-info btn-sm"><i class="fas fa-sync"></i></button></td>
          </tr>`;

          detalles = detalles + 1;

          $("#detalles tbody").append(fila);

          array_class_compra.push({ id_cont: cont });

          cont++;
          evaluar();

          $(".orden_producto").sortable().disableSelection();
        });

        modificarSubtotales();
      } else {  
        toastr_error("Sin productos!!","Este registro no tiene productos para mostrar", 700);     
      }

      $("#cargando-1-fomulario").show();
      $("#cargando-2-fomulario").hide();
      
    } else {
      ver_errores(e);
    }
    
  }).fail( function(e) { ver_errores(e); } );
}

//Detraccion
$("#my-switch_detracc").on("click ", function (e) {
  if ($("#my-switch_detracc").is(":checked")) {
    $("#estado_detraccion").val("1");
  } else {
    $("#estado_detraccion").val("0");
  }
});

function agregar_producto_x_codigo() {
  var codigo = $('#add_producto_x_codigo').val();
  if (codigo == '' || codigo == null || codigo === undefined) {
    toastr_error('VACIO', 'Ingrese un codigo valido para agregar'); 
  } else {
    agregar_detalle_comprobante_x_codigo(codigo);
  }  
}

function buscar_precio_x_marca(val, idproducto) {
  $(`#precio_table_${idproducto}`).html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  var marca_select = $(val).val(); console.log(marca_select);
  $.post("../ajax/ajax_general.php?op=buscar_precio_x_marca", {'idproducto':idproducto, 'marca':marca_select }, function (e, textStatus, jqXHR) {
    e = JSON.parse(e);  
    if (e.status == true) {      
      if (e.data == '' || e.data == null) {
        $(`#precio_table_${idproducto}`).html(0); 
        $(`.promedio_precio_${idproducto}`).html(0);
      } else { 
        $(`#precio_table_${idproducto}`).html(parseFloat(e.data.precio_con_igv).toFixed(2)); 
        $(`.promedio_precio_${idproducto}`).html(parseFloat(e.data.precio_con_igv).toFixed(2));
      }
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}