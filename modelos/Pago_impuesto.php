<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Pago_impuesto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //----------------------------GASTOS-----------------------------------
  public function suma_totales($idproyecto) {

    $data = Array(); $total = 0; $subtotal = 0; $igv = 0;

    //----------------------------Venta-----------------------------------
    $sql0 = "SELECT idproyecto,costo FROM proyecto WHERE idproyecto='$idproyecto';";
    $venta = ejecutarConsultaSimpleFila($sql0); if ($venta['status'] == false) { return $venta; }

    $presupuesto  = (empty($venta['data']) ? 0 : ( empty($venta['data']['costo']) ? 0 : floatval($venta['data']['costo'])));

    $subTotal_venta  = $presupuesto/1.18;
    $igv_venta=$presupuesto-$subTotal_venta;

    // SUMAS TOTALES - COMPRA --------------------------------------------------------------------------------
    $filtro_comprobante1 = "AND cpp.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";

      $sql = "SELECT SUM(cpp.total) AS total, SUM(cpp.subtotal) AS subtotal, SUM(cpp.igv) AS igv
      FROM compra_por_proyecto AS cpp, proveedor p
      WHERE cpp.idproveedor = p.idproveedor AND cpp.idproyecto  = '$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1' $filtro_comprobante1;";
      $compra = ejecutarConsultaSimpleFila($sql);

      if ($compra['status'] == false) { return $compra; }

      $total    += (empty($compra['data'])) ? 0 : ( empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']) );
      $subtotal += (empty($compra['data'])) ? 0 : ( empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']) );
      $igv      += (empty($compra['data'])) ? 0 : ( empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']) );

    // SUMAS TOTALES - MAQUINARIA --------------------------------------------------------------------------------

      $filtro_comprobante2 = "AND f.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";

      $sql2 = "SELECT SUM(f.monto) AS total , SUM(f.subtotal) AS subtotal, SUM(f.igv) AS igv
      FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
      WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
      AND p.idproyecto  = '$idproyecto' AND  f.estado = '1' AND f.estado_delete = '1' AND mq.tipo = '1' $filtro_comprobante2;";
      $maquinaria = ejecutarConsultaSimpleFila($sql2);

      if ($maquinaria['status'] == false) { return $maquinaria; } 

      $total    += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['total']) ? 0 : floatval($maquinaria['data']['total']) );
      $subtotal += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['subtotal']) ? 0 : floatval($maquinaria['data']['subtotal']) );
      $igv      += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['igv']) ? 0 : floatval($maquinaria['data']['igv']) );


    // SUMAS TOTALES - MAQUINARIA --------------------------------------------------------------------------------
      $filtro_comprobante3 = "AND f.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";

      $sql12 = "SELECT SUM(f.monto) AS total , SUM(f.subtotal) AS subtotal, SUM(f.igv) AS igv
      FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
      WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
      AND p.idproyecto  = '$idproyecto' AND f.estado = '1' AND f.estado_delete = '1' AND mq.tipo = '1' $filtro_comprobante3; ";
      $maquinaria = ejecutarConsultaSimpleFila($sql12);

      if ($maquinaria['status'] == false) { return $maquinaria; } 

      $total    += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['total']) ? 0 : floatval($maquinaria['data']['total']) );
      $subtotal += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['subtotal']) ? 0 : floatval($maquinaria['data']['subtotal']) );
      $igv      += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['igv']) ? 0 : floatval($maquinaria['data']['igv']) );

    // SUMAS TOTALES - SUB CONTRATO --------------------------------------------------------------------------------

      $filtro_comprobante4 = "AND s.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')"; 

      $sql11 = "SELECT SUM(s.subtotal) as subtotal, SUM(s.igv) as igv, SUM(s.costo_parcial) as total
      FROM subcontrato AS s, proveedor as p
      WHERE s.idproyecto  = '$idproyecto' AND  s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1'
      $filtro_comprobante4;";
      $otro_gasto = ejecutarConsultaSimpleFila($sql11);

      if ($otro_gasto['status'] == false) { return $otro_gasto; } 
      
      $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
      $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
      $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );



    // SUMAS TOTALES - PLANILLA SEGURO --------------------------------------------------------------------------------

      $filtro_comprobante5 = "AND ps.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')"; 

      $sql10 = "SELECT SUM(ps.subtotal) AS subtotal, SUM(ps.igv) AS igv, SUM(ps.costo_parcial) AS total
      FROM planilla_seguro as ps, proyecto as p, proveedor as prov 
      WHERE ps.idproyecto = p.idproyecto and ps.idproyecto  = '$idproyecto' and ps.idproveedor = prov.idproveedor and ps.estado ='1' and ps.estado_delete = '1'
      $filtro_comprobante5;";
      $otro_gasto = ejecutarConsultaSimpleFila($sql10);

      if ($otro_gasto['status'] == false) { return $otro_gasto; } 
      
      $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
      $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
      $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );
    

    // SUMAS TOTALES - OTRO SERVICIO --------------------------------------------------------------------------------

      $filtro_comprobante6 = "AND tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')"; 

      $sql3 = "SELECT SUM(costo_parcial) as total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
      FROM otro_gasto  
      WHERE idproyecto  = '$idproyecto' AND estado = '1' AND estado_delete = '1'$filtro_comprobante6;";
      $otro_gasto = ejecutarConsultaSimpleFila($sql3);

      if ($otro_gasto['status'] == false) { return $otro_gasto; } 
      
      $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
      $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
      $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );
    
    
    // SUMAS TOTALES - TRASNPORTE --------------------------------------------------------------------------------

      $filtro_comprobante7 = "AND t.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')"; 

      $sql4 = "SELECT SUM(t.precio_parcial) AS total, SUM(t.subtotal) AS subtotal, SUM(t.igv) AS igv
      FROM transporte AS t, proveedor AS p
      WHERE t.idproyecto  = '$idproyecto' AND t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1'  $filtro_comprobante7;";
      $transporte = ejecutarConsultaSimpleFila($sql4);

      if ($transporte['status'] == false) { return $transporte; }
      
      $total    += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['total']) ? 0 : floatval($transporte['data']['total']) );
      $subtotal += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['subtotal']) ? 0 : floatval($transporte['data']['subtotal']) );
      $igv      += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['igv']) ? 0 : floatval($transporte['data']['igv']) );  

    // SUMAS TOTALES - HOSPEDAJE --------------------------------------------------------------------------------

      $filtro_comprobante8 = "AND tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')"; 

      $sql5 = "SELECT SUM(precio_parcial) as total , SUM(subtotal) AS subtotal, SUM(igv) AS igv
      FROM hospedaje WHERE idproyecto  = '$idproyecto' AND estado = '1' AND estado_delete = '1' $filtro_comprobante8
      ORDER BY fecha_comprobante DESC;";
      $hospedaje = ejecutarConsultaSimpleFila($sql5);

      if ($hospedaje['status'] == false) { return $hospedaje; }
      
      $total    += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['total']) ? 0 : floatval($hospedaje['data']['total']) );
      $subtotal += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['subtotal']) ? 0 : floatval($hospedaje['data']['subtotal']) );
      $igv      += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['igv']) ? 0 : floatval($hospedaje['data']['igv']) );

    // SUMAS TOTALES - FACTURA PENSION --------------------------------------------------------------------------------

      $filtro_comprobante9 = "AND dp.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      
      $sql6 = "SELECT SUM(dp.precio_parcial) AS total, SUM(dp.subtotal) AS subtotal, SUM(dp.igv) AS igv
      FROM detalle_pension as dp, pension as p, proveedor as prov
      WHERE p.idproyecto  = '$idproyecto' AND dp.idpension = p.idpension AND prov.idproveedor = p.idproveedor  AND p.estado = '1' AND p.estado_delete = '1' 
      AND dp.estado = '1' AND dp.estado_delete = '1' $filtro_comprobante9;";
      $factura_pension = ejecutarConsultaSimpleFila($sql6);

      if ($factura_pension['status'] == false) { return $factura_pension; }
      
      $total    += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['total']) ? 0 : floatval($factura_pension['data']['total']) );
      $subtotal += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['subtotal']) ? 0 : floatval($factura_pension['data']['subtotal']) );
      $igv      += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['igv']) ? 0 : floatval($factura_pension['data']['igv']) );
    
    // SUMAS TOTALES - FACTURA BREACK --------------------------------------------------------------------------------

      $filtro_comprobante10 = "AND fb.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')";
      
      $sql7 = "SELECT SUM(fb.monto) AS total, SUM(fb.subtotal) AS subtotal, SUM(fb.igv) AS igv
      FROM factura_break as fb, semana_break as sb
      WHERE sb.idproyecto  = '$idproyecto' AND fb.idsemana_break = sb.idsemana_break AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1' 
      AND sb.estado_delete = '1' $filtro_comprobante10;";
      $factura_break = ejecutarConsultaSimpleFila($sql7);

      if ($factura_break['status'] == false) { return $factura_break; }
      
      $total    += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['total']) ? 0 : floatval($factura_break['data']['total']) );
      $subtotal += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['subtotal']) ? 0 : floatval($factura_break['data']['subtotal']) );
      $igv      += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['igv']) ? 0 : floatval($factura_break['data']['igv']) );
    
    // SUMAS TOTALES - COMIDA EXTRA --------------------------------------------------------------------------------

      $filtro_comprobante11 = "AND tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')"; 
      
      $sql8 = "SELECT SUM(costo_parcial) AS total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
      FROM comida_extra
      WHERE  idproyecto  = '$idproyecto' AND estado = '1' AND estado_delete = '1' $filtro_comprobante11;";
      $comida_extra = ejecutarConsultaSimpleFila($sql8);

      if ($comida_extra['status'] == false) { return $comida_extra; }
      
      $total    += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['total']) ? 0 : floatval($comida_extra['data']['total']) );
      $subtotal += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['subtotal']) ? 0 : floatval($comida_extra['data']['subtotal']) );
      $igv      += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['igv']) ? 0 : floatval($comida_extra['data']['igv']) );
      
    // SUMAS TOTALES - OTRA FACTURA --------------------------------------------------------------------------------
        $filtro_comprobante12 = "AND of.tipo_comprobante IN ('Factura','Boleta','Nota de Crédito')"; 
      
      $sql9 = "SELECT SUM(of.costo_parcial) AS total, SUM(of.subtotal) AS subtotal, SUM(of.igv) AS igv
      FROM otra_factura_proyecto AS of
      WHERE of.idproyecto  = '$idproyecto' AND of.estado = '1' AND of.estado_delete = '1' $filtro_comprobante12";
      $otra_factura = ejecutarConsultaSimpleFila($sql9);

      if ($otra_factura['status'] == false) { return $otra_factura; } 
      
      $total    += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['total']) ? 0 : floatval($otra_factura['data']['total']) );
      $subtotal += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['subtotal']) ? 0 : floatval($otra_factura['data']['subtotal']) );
      $igv      += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['igv']) ? 0 : floatval($otra_factura['data']['igv']) );
    
    // SUMAS TOTALES - RECIBOS POR HONORARRIOS --------------------------------------------------------------------------
    $sql_rh="SELECT SUM(monto_total) monto_total FROM recibo_x_honorario WHERE idproyecto='$idproyecto' and estado='1' and estado_delete='1';";
    $tsql_rh = ejecutarConsultaSimpleFila($sql_rh); if ($tsql_rh['status'] == false) { return $tsql_rh; }
    
    $total_rh  = (empty($tsql_rh['data']) ? 0 : ( empty($tsql_rh['data']['monto_total']) ? 0 : floatval($tsql_rh['data']['monto_total'])));

    $sub_Total  = $total/1.18;
    $igv_Total=$total-$sub_Total; 

    $data = array( 
      "status"=> true,
      "message"=> 'todo oka',
      "data"=> [
        "subTotal_venta" => $subTotal_venta, 
        "igv_venta" => $igv_venta, 
        "total_venta" => $presupuesto, 

        "total" => $total, 
        "subtotal" => $sub_Total, 
        "igv" => $igv_Total,

        "total_rh" => $total_rh

      ]      
    );

    return $data ;
  }  

    //----------------------------RECIBOS-------------------

    //----------------------------IMPUESTOS A PAGAR------------------------
    //----------------------------UTILIDAD REAL----------------------------








}

?>
