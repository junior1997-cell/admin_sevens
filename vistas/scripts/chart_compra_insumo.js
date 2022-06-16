
//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

	$("#mCompra").addClass("active bg-primary");

  $("#lResumenActivosFijos").addClass("active");

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  lista_select2("../ajax/ajax_general.php?op=select2Proveedor", '#idproveedor', null);

  // ══════════════════════════════════════ INITIALIZE SELECT2 - COMPRAS ══════════════════════════════════════

  $("#idproveedor").select2({ theme: "bootstrap4", placeholder: "Selecione proveedor", allowClear: true, });

  no_select_tomorrow("#fecha_compra");

  // Formato para telefono
  $("[data-mask]").inputmask();
}

// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C H A R T :::::::::::::::::::::::::::::::::::::::::::::

//mostrar datos proveedor pago
function most_datos_prov_pago(idcompra_proyecto) {
  // limpiar_form_pago_compra();
  $("#h4_mostrar_beneficiario").html("");
  $("#idproyecto_pago").val("");

  $("#banco_pago").val("").trigger("change");

  $.post("../ajax/compra_insumos.php?op=most_datos_prov_pago", { idcompra_proyecto: idcompra_proyecto }, function (e, status) {

    e = JSON.parse(e);   //console.log(e);

    if (e.status == true) {
      $("#idproyecto_pago").val(e.data.idproyecto);
      $("#idcompra_proyecto_p").val(e.data.idcompra_proyecto);
      $("#idproveedor_pago").val(e.data.idproveedor);
      $("#beneficiario_pago").val(e.data.razon_social);
      $("#h4_mostrar_beneficiario").html(e.data.razon_social);
      $("#banco_pago").val(e.data.idbancos).trigger("change");
      $("#tipo_pago").val('Proveedor').trigger("change");
      $("#titular_cuenta_pago").val(e.data.titular_cuenta);
      localStorage.setItem("nubecompra_c_b", e.data.cuenta_bancaria);
      localStorage.setItem("nubecompra_c_d", e.data.cuenta_detracciones);

      if ($("#tipo_pago").select2("val") == "Proveedor") {$("#cuenta_destino_pago").val(e.data.cuenta_bancaria);}
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}


init();