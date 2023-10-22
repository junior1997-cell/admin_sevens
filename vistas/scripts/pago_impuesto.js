var uit=4950;
var v_quince_uits=uit*15;
var t_utilidad_sunat=0;
var igv_renta =0;
var renta=0;
var t_renta=0;
var total_gasto=0;

//Función que se ejecuta al inicio
function init() {

  //Activamos el "aside"
  $("#bloc_ContableFinanciero").addClass("menu-open");

  $("#mContableFinanciero").addClass("active");

  $("#lrecibo").addClass("active bg-primary");

  pago_impuesto(localStorage.getItem("nube_idproyecto"));

  // Formato para telefono
  $("[data-mask]").inputmask();
}


function pago_impuesto(nube_idproyecto) {
  var gasto_fact=0;
  renta=0;
  $.post("../ajax/pago_impuesto.php?op=mostrar", { idproyecto: nube_idproyecto }, function (e, status) {
    
    e = JSON.parse(e); console.log(e);   
    if (e.status == true) {
      /**
        igv_venta: 23338.983050847455
        subTotal_venta: 129661.01694915254
        total_venta: 153000

        igv: 1659.82
        subtotal: 9521.31
        total: 11181.13

        total_rh: 62000
        
        compra_gasto igv_gasto Subtotal_gasto
        rh_total gasto_fact
        */
        $(".subtotal_venta").html(`S/. ${formato_miles(e.data.subTotal_venta)}`);
        $(".igv_venta").html(`S/. ${formato_miles(e.data.igv_venta)}`);
        $(".total_venta").html(`S/. ${formato_miles(e.data.total_venta)}`);

        //----------Segunda tabla------------------------------------
        $(".Subtotal_gasto").html(`S/. ${formato_miles(e.data.subtotal)}`);
        $(".igv_gasto").html(`S/. ${formato_miles(e.data.igv)}`);
        $(".compra_gasto").html(`S/. ${formato_miles(e.data.total)}`);

        $(".gasto_fact").html(`S/. ${formato_miles(gasto_fact)}`);

        $(".rh_total").html(`S/. ${formato_miles(e.data.total_rh)}`);

        $(".total_gasto").html(`S/. ${formato_miles(e.data.total_rh + e.data.total)}`);
        total_gasto=e.data.total_rh + e.data.total;
        //----------tercera tabla------------------------------------

        $(".utilidad_sunat").html(`S/. ${formato_miles(e.data.total_venta - e.data.total_rh-e.data.total)}`);

        //----------cuarta tabla------------------------------------

        t_utilidad_sunat=e.data.total_venta - e.data.total_rh-e.data.total;

        igv_renta=e.data.igv_venta-e.data.igv;

        if(t_utilidad_sunat<v_quince_uits){

          //(74250)*0.1
          renta=t_utilidad_sunat*0.10;

          $(".igv_renta").html(`S/. ${formato_miles(igv_renta)}`);
          $(".renta").html(`S/. ${formato_miles(renta)}`);
          $(".total_renta").html(`S/. ${formato_miles(renta+igv_renta)}`);

        }else{

          //=+SI(J16>74250;(74250)*0.1+(J16-74250)*0.295)
          renta=(v_quince_uits*0.10)+((t_utilidad_sunat-v_quince_uits)*0.295);

          $(".igv_renta").html(`S/. ${formato_miles(igv_renta)}`);
          $(".renta").html(`S/. ${formato_miles(renta)}`);
          $(".total_renta").html(`S/. ${formato_miles(renta+igv_renta)}`);

        }
        //-------------quinta tabla----------------------------------------
        $(".utilidad_neta").html(`S/. ${formato_miles(e.data.total_venta-total_gasto-(renta+igv_renta))}`);





    } else {
      ver_errores(e);
    }

  }).fail( function(e) { ver_errores(e); } );

}



init();
