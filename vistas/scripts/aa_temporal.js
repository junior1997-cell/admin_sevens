//Función ListarArticulos
function listaractivos_p() {
    tablaactivos2 = $("#tblaactivos_proyecto")
      .dataTable({
        responsive: true,
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: [],
        ajax: {
          url: "../ajax/all_activos_fijos.php?op=listarActivoscompra2",
          type: "get",
          dataType: "json",
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
        iDisplayLength: 5, //Paginación
        order: [[0, "desc"]], //Ordenar (columna,orden)
      })
      .DataTable();
}
function agregarDetalleCompraActivos_p(idactivos_fijos, nombre, unidad_medida, nombre_color, precio_sin_igv, precio_igv, precio_total, img, ficha_tecnica_activo) {
    var stock = 5;
    var cantidad = 1;
    var descuento = 0;
  
    if (idactivos_fijos != "") {
      // $('.producto_'+idactivos_fijos).addClass('producto_selecionado');
      if ($(".producto_" + idactivos_fijos).hasClass("producto_selecionado")) {
        toastr.success("Activo: " + nombre + " agregado !!");
  
        var cant_producto = $(".producto_" + idactivos_fijos).val();
  
        var sub_total = parseInt(cant_producto, 10) + 1;
  
        $(".producto_" + idactivos_fijos).val(sub_total);
  
        modificarSubtotales();
      } else {

        var fila = `
        <tr class="filas" id="fila${cont_p}">
          <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle_p(${cont_p})">X</button></td>
          <td>
            <input type="hidden" name="idactivos_fijos_p[]" value="${element.idactivos_fijos}">
            <input type="hidden" name="ficha_tecnica_activo_p[]" value="${element.ficha_tecnica}">
            <div class="user-block text-nowrap">
              <img class="profile-user-img img-responsive img-circle cursor-pointer" src="${img}" alt="user image" onerror="this.src='../dist/img/materiales/img_material_defect.jpg';" onclick="ver_img_activo('${img}', '${element.nombre_activo}')">
              <span class="username"><p style="margin-bottom: 0px !important;">${element.nombre_activo}</p></span>
              <span class="description"><b>Color: </b>${element.color}</span>
            </div>
          </td>
          <td> 
            <span >${element.unidad_medida}</span> 
            <input type="hidden" name="unidad_medida_p[]" id="unidad_medida_p[]" value="${element.unidad_medida}"> 
            <input type="hidden" name="nombre_color_p[]" id="nombre_color_p[]" value="${element.color}">
          </td>
          <td class="form-group">
            <input class="producto_p_${element.idactivos_fijos} producto_selecionado w-px-100 cantidad_p_${cont_p} form-control " type="number" name="cantidad_p[]" id="cantidad_p[]" min="1" value="${element.cantidad}" onkeyup="modificarSubtotales_p()" onchange="modificarSubtotales_p()">
          /td>
          <td class="hidden">
            <input class="w-px-135 input-no-border precio_sin_igv_p_${cont_p}" type="number" name="precio_sin_igv_p[]" id="precio_sin_igv_p[]" value="${element.precio_sin_igv}" readonly >
          </td>
            <td class="hidden"><input class="w-px-135 input-no-border precio_igv_p_${cont_p}" type="number"  name="precio_igv_p[]" id="precio_igv_p[]" value="${element.igv}" readonly >
          </td>
          <td >
            <input type="number" class="w-px-135 precio_con_igv_p_${cont_p}" type="number"  name="precio_con_igv_p[]" id="precio_con_igv_p[]" value="${parseFloat(element.precio_con_igv).toFixed(2)}" onkeyup="modificarSubtotales_p();" onchange="modificarSubtotales_p();">
          </td>
          <td>
            <input type="number" class="w-px-135 descuento_p_${cont_p}" name="descuento_p[]" value="${element.descuento}" onkeyup="modificarSubtotales_p()" onchange="modificarSubtotales_p()">
          </td>
          <td class="text-right">
            <span class="text-right subtotal_producto_p_${cont_p}" name="subtotal_producto_p" id="subtotal_producto_p">0.00</span>
          </td>
          <td>
            <button type="button" onclick="modificarSubtotales_p()" class="btn btn-info"><i class="fas fa-sync"></i></button>
            </td>
        </tr>`;

        detalles_p = detalles_p + 1;

        $("#detalles_af_proyecto").append(fila);

        array_class_trabajador_p.push({ id_cont_p: cont_p });

        cont_p++;
        evaluar();
      }
    } else {
      // alert("Error al ingresar el detalle, revisar los datos del artículo");
      toastr.error("Error al ingresar el detalle, revisar los datos del material.");
    }
}
function editar_detalle_compras_af_p(idcompra_af_proyecto) {
    limpiar();
    //$('#agregar_compras').hide();
    //$('#agregar_compras_proyecto').show();
    array_class_trabajador_p = [];
  
    cont_p = 0;
    detalles_p = 0;

    ver_form_add_p();
  
    $.post("../ajax/all_activos_fijos.php?op=ver_compra_editar_af_p", { idcompra_af_proyecto: idcompra_af_proyecto }, function (data, status) {
      data = JSON.parse(data);
      console.log(data);
  
      if (data) {
        $(".subtotal_proy").html("");
        $(".igv_comp_proy").html("");
        $(".total_proy").html("");
        $("#idproveedor_proy").val("").trigger("change");
  
        if (data.tipo_comprobante == "Factura") {
          $("#igv_proy").val("0.18");
          $("#content-igv-p").show();
          $("#content-t-comprob-p").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
          $("#content-descrp-p").removeClass("col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
          $("#content-comprob-p").show();
        } else if (data.tipo_comprobante == "Boleta" || data.tipo_comprobante == "Nota_de_venta") {
          $("#igv_proy").val("");
          $("#content-comprob-p").show();
          $("#content-igv-p").hide();
          $("#content-t-comprob-p").removeClass("col-lg-4 col-lg-5").addClass("col-lg-5");
  
          $("#content-descrp-p").removeClass(" col-lg-4 col-lg-5 col-lg-7 col-lg-8").addClass("col-lg-5");
  
        } else if (data.tipo_comprobante == "Ninguno") {
          $("#igv_proy").val("");
          $("#content-comprob-p").hide();
          $("#content-comprob-p").val("");
          $("#content-igv-p").hide();
          $("#content-t-comprob-p").removeClass("col-lg-5 col-lg-4").addClass("col-lg-4");
          $("#content-descrp-p").removeClass(" col-lg-4 col-lg-5 col-lg-7").addClass("col-lg-8");
        } else {
          $("#content-comprob-p").show();
          //$(".content-descrp").removeClass("col-lg-7").addClass("col-lg-4");
        }
  
        $("#idproyecto_proy").val(data.idproyecto);
        $("#idcompra_af_proy").val(data.idcompra_af_proyecto);
        $("#idproveedor_proy").val(data.idproveedor).trigger("change");
        $("#fecha_compra_proy").val(data.fecha_compra);
        $("#tipo_comprobante_proy").val(data.tipo_comprobante).trigger("change");
        $("#serie_comprobante_proy").val(data.serie_comprobante);
        $("#descripcion_proy").val(data.descripcion);
  
        if (data.activos) {
          data.activos.forEach((element, index) => {
            var img = "";
  
            if (element.imagen == "" || element.imagen == null) {
              img = `../dist/img/default/default_activos_fijos_empresa.png`;
            } else {
              img =`../dist/docs/activos_fijos_general/img_activos_fijos/${element.imagen}`;
            }
  
            var fila = `
            <tr class="filas" id="fila${cont_p}">
              <td><button type="button" class="btn btn-danger" onclick="eliminarDetalle_p(${cont_p})">X</button></td>
              <td>
                <input type="hidden" name="idactivos_fijos_p[]" value="${element.idactivos_fijos}">
                <input type="hidden" name="ficha_tecnica_activo_p[]" value="${element.ficha_tecnica}">
                <div class="user-block text-nowrap">
                  <img class="profile-user-img img-responsive img-circle cursor-pointer" src="${img}" alt="user image" onerror="this.src='../dist/img/materiales/img_material_defect.jpg';" onclick="ver_img_activo('${img}', '${element.nombre_activo}')">
                  <span class="username"><p style="margin-bottom: 0px !important;">${element.nombre_activo}</p></span>
                  <span class="description"><b>Color: </b>${element.color}</span>
                </div>
              </td>
              <td> 
                <span >${element.unidad_medida}</span> 
                <input type="hidden" name="unidad_medida_p[]" id="unidad_medida_p[]" value="${element.unidad_medida}"> 
                <input type="hidden" name="nombre_color_p[]" id="nombre_color_p[]" value="${element.color}">
              </td>
              <td class="form-group">
                <input class="producto_p_${element.idactivos_fijos} producto_selecionado w-px-100 cantidad_p_${cont_p} form-control " type="number" name="cantidad_p[]" id="cantidad_p[]" min="1" value="${element.cantidad}" onkeyup="modificarSubtotales_p()" onchange="modificarSubtotales_p()">
              /td>
              <td class="hidden">
                <input class="w-px-135 input-no-border precio_sin_igv_p_${cont_p}" type="number" name="precio_sin_igv_p[]" id="precio_sin_igv_p[]" value="${element.precio_sin_igv}" readonly >
              </td>
                <td class="hidden"><input class="w-px-135 input-no-border precio_igv_p_${cont_p}" type="number"  name="precio_igv_p[]" id="precio_igv_p[]" value="${element.igv}" readonly >
              </td>
              <td >
                <input type="number" class="w-px-135 precio_con_igv_p_${cont_p}" type="number"  name="precio_con_igv_p[]" id="precio_con_igv_p[]" value="${parseFloat(element.precio_con_igv).toFixed(2)}" onkeyup="modificarSubtotales_p();" onchange="modificarSubtotales_p();">
              </td>
              <td>
                <input type="number" class="w-px-135 descuento_p_${cont_p}" name="descuento_p[]" value="${element.descuento}" onkeyup="modificarSubtotales_p()" onchange="modificarSubtotales_p()">
              </td>
              <td class="text-right">
                <span class="text-right subtotal_producto_p_${cont_p}" name="subtotal_producto_p" id="subtotal_producto_p">0.00</span>
              </td>
              <td>
                <button type="button" onclick="modificarSubtotales_p()" class="btn btn-info"><i class="fas fa-sync"></i></button>
                </td>
            </tr>`;
  
            detalles_p = detalles_p + 1;
  
            $("#detalles_af_proyecto").append(fila);
  
            array_class_trabajador_p.push({ id_cont_p: cont_p });
  
            cont_p++;
            evaluar();
          });
  
          modificarSubtotales_p();
        } else {
          toastr.error("<h3>Sin Activos.</h3> <br> Este registro no tiene Activos para mostrar");
          $("#igv_comp_proy").html("S/. 0.00");
          $("#igv_comp_proy").html("S/. 0.00");
          $("#total_proy").html("S/. 0.00");
        }
      } else {
        toastr.error("<h3>Error.</h3> <br> Este registro tiene errores, o esta vacio");
      }
    });

}
function modificarSubtotales_p() {

    if ($("#tipo_comprobante_proy").select2("val") == null) {
      $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV
  
      $("#colspan_subtotal_p").attr("colspan", 5); //cambiamos el: colspan
  
      if (array_class_trabajador_p.length === 0) {
      } else {
        array_class_trabajador_p.forEach((element, index) => {
          var cantidad = parseFloat($(`.cantidad_p_${element.id_cont_p}`).val());
          var precio_con_igv = parseFloat($(`.precio_con_igv_p_${element.id_cont_p}`).val());
          var descuento = parseFloat($(`.descuento_p_${element.id_cont_p}`).val());
          var subtotal_producto = 0;
  
          // Calculamos: IGV
          var precio_sin_igv = precio_con_igv;
          $(`.precio_sin_igv_p_${element.id_cont_p}`).val(precio_sin_igv);
  
          // Calculamos: precio + IGV
          var igv = 0;
          $(`.precio_igv_p_${element.id_cont_p}`).val(igv);
  
          // Calculamos: Subtotal de cada producto
          subtotal_producto = cantidad * parseFloat(precio_con_igv) - descuento;
          $(`.subtotal_producto_p_${element.id_cont_p}`).html(formato_miles(subtotal_producto.toFixed(4)));
        });
        calcularTotalesSinIgv_p();
      }
    } else {
      if ($("#tipo_comprobante_proy").select2("val") == "Factura") {
        $(".hidden").show(); //Mostramos: IGV, PRECIO SIN IGV
  
        $("#colspan_subtotal_p").attr("colspan", 7); //cambiamos el: colspan
  
        if (array_class_trabajador_p.length === 0) {
        } else {
            array_class_trabajador_p.forEach((element, index) => {

            var cantidad = parseFloat($(`.cantidad_p_${element.id_cont_p}`).val());
            var precio_con_igv = parseFloat($(`.precio_con_igv_p_${element.id_cont_p}`).val());
            var descuento = parseFloat($(`.descuento_p_${element.id_cont_p}`).val());
            var subtotal_producto = 0;
  
            // Calculamos: IGV
            var precio_sin_igv = (precio_con_igv / 1.18).toFixed(2);
            $(`.precio_sin_igv_p_${element.id_cont_p}`).val(precio_sin_igv);
  
            // Calculamos: precio + IGV
            var igv = (parseFloat(precio_con_igv) - parseFloat(precio_sin_igv)).toFixed(2);
            $(`.precio_igv_p_${element.id_cont_p}`).val(igv);
  
            // Calculamos: Subtotal de cada producto
            subtotal_producto = cantidad * parseFloat(precio_con_igv) - descuento;
            $(`.subtotal_producto_p_${element.id_cont_p}`).html(formato_miles(subtotal_producto.toFixed(2)));
          });
  
          calcularTotalesConIgv_p();
        }
      } else {
        $(".hidden").hide(); //Ocultamos: IGV, PRECIO CON IGV
  
        $("#colspan_subtotal_p").attr("colspan", 5); //cambiamos el: colspan
  
        if (array_class_trabajador_p.length === 0) {
        } else {
            array_class_trabajador_p.forEach((element, index) => {

            var cantidad = parseFloat($(`.cantidad_p_${element.id_cont_p}`).val());
            var precio_con_igv = parseFloat($(`.precio_con_igv_p_${element.id_cont_p}`).val());
            var descuento = parseFloat($(`.descuento_p_${element.id_cont_p}`).val());
            var subtotal_producto = 0;
  
            // Calculamos: IGV
            var precio_sin_igv = precio_con_igv;
            $(`.precio_sin_igv_p_${element.id_cont_p}`).val(precio_sin_igv);
  
            // Calculamos: precio + IGV
            var igv = 0;
            $(`.precio_igv_p_${element.id_cont_p}`).val(igv);
  
            // Calculamos: Subtotal de cada producto
            subtotal_producto = cantidad * parseFloat(precio_con_igv) - descuento;
            $(`.subtotal_producto_p_${element.id_cont_p}`).html(formato_miles(subtotal_producto.toFixed(4)));
          });
  
          calcularTotalesSinIgv();
        }
      }
    }
    toastr.success("Precio Actualizado !!!");
}
function calcularTotalesSinIgv_p() {
    var total = 0.0;
    var igv = 0;
    var mtotal = 0;
  
    if (array_class_trabajador_p.length === 0) {
    } else {
      array_class_trabajador_p.forEach((element, index) => {
        total += parseFloat(quitar_formato_miles($(`.subtotal_producto_p_${element.id_cont_p}`).text()));
      });
  
      $("#subtotal_proy").html("S/. " + formato_miles(total));
      $("#subtotal_compra_proy").val(redondearExp(total, 4));
  
      $("#igv_comp_proy").html("S/. 0.00");
      $("#igv_compra_proy").val(0.0);
  
      $("#total_proy").html("S/. " + formato_miles(total.toFixed(2)));
      $("#total_compra_af_proy").val(redondearExp(total, 2));
    }
} 
function calcularTotalesConIgv_p() {
var igv = 0;
var total = 0.0;

var subotal_sin_igv = 0;

array_class_trabajador.forEach((element, index) => {
    total += parseFloat(quitar_formato_miles($(`.subtotal_producto_p_${element.id_cont_p}`).text()));
});

console.log(total);
subotal_sin_igv = (parseFloat(total) / 1.18).toFixed(2);
igv = (parseFloat(total) - parseFloat(subotal_sin_igv)).toFixed(2);

$("#subtotal_proy").html(`S/. ${formato_miles(subotal_sin_igv)}`);
$("#subtotal_compra_proy").val(redondearExp(subotal_sin_igv, 4));

$("#igv_comp_proy").html("S/. " + formato_miles(igv));
$("#igv_compra_proy").val(igv);

$("#total_proy").html("S/. " + formato_miles(total.toFixed(2)));
$("#total_compra_af_proy").val(redondearExp(total, 2));

total = 0.0;
}
function eliminarDetalle_p(indice) {
    $("#fila" + indice).remove();
  
    array_class_trabajador_p.forEach(function (car, index, object) {
      if (car.cont_p === indice) {
        object.splice(index, 1);
      }
    });
  
    modificarSubtotales_p();
  
    detalles_p = detalles_p - 1;
  
    evaluar();
  
    toastr.warning("Activo removido.");
}
function guardaryeditar_compras_af_p(e) {

    var formData = new FormData($("#form-compra-activos-p")[0]);
  
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
          url: "../ajax/all_activos_fijos.php?op=guardaryeditarcompraactivo",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
  
          success: function (datos) {
            if (datos == "ok") {
              // toastr.success("Usuario registrado correctamente");
              Swal.fire("Correcto!", "Compra guardada correctamente", "success");
  
              tabla.ajax.reload();
  
              limpiar();
              regresar();
              cont = 0;
              tabla_comp_prov.ajax.reload();
            } else {
              // toastr.error(datos);
              Swal.fire("Error!", datos, "error");
            }
          },
        });
      }
    });
}