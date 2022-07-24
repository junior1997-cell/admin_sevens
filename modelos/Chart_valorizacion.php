<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ChartValorizacion
{
  //Implementamos nuestro constructor
  public function __construct() { }  

  //Implementar un método para mostrar los datos de un registro a modificar
  public function box_content_reporte($id_proyecto) {
    $data = Array();

    $sql_1 = "SELECT COUNT(idproveedor) as cant_proveedores FROM compra_por_proyecto WHERE estado='1' AND estado_delete='1' AND idproyecto = '$id_proyecto' GROUP BY idproveedor";
    $cant_proveedores = ejecutarConsultaArray($sql_1);
    if ($cant_proveedores['status'] == false) { return $cant_proveedores; }

    $sql_2 = "SELECT COUNT(dc.idproducto) AS cant_producto 
    FROM detalle_compra AS dc, compra_por_proyecto AS cpp WHERE dc.idcompra_proyecto = cpp.idcompra_proyecto  AND dc.estado ='1' AND dc.estado_delete = '1' AND cpp.estado = '1'  AND cpp.estado_delete = '1'  AND cpp.idproyecto = '$id_proyecto'  GROUP BY dc.idproducto;";
    $cant_producto = ejecutarConsultaArray($sql_2);
    if ($cant_producto['status'] == false) { return $cant_producto; }

    $sql_3 = "SELECT COUNT(dc.idproducto) AS cant_insumo
    FROM detalle_compra AS dc, compra_por_proyecto AS cpp, producto as p WHERE dc.idcompra_proyecto = cpp.idcompra_proyecto AND dc.idproducto = p.idproducto AND dc.estado ='1' AND dc.estado_delete = '1' AND cpp.estado = '1' AND cpp.estado_delete = '1' AND p.idcategoria_insumos_af ='1' AND cpp.idproyecto = '$id_proyecto'  GROUP BY dc.idproducto";
    $cant_insumo = ejecutarConsultaArray($sql_3);
    if ($cant_insumo['status'] == false) { return $cant_insumo; }

    $sql_4 = "SELECT COUNT(dc.idproducto) AS cant_activo_fijo FROM detalle_compra AS dc, compra_por_proyecto AS cpp, producto as p WHERE dc.idcompra_proyecto = cpp.idcompra_proyecto AND dc.idproducto = p.idproducto AND dc.estado ='1' AND dc.estado_delete = '1' AND cpp.estado = '1' AND cpp.estado_delete = '1' AND p.idcategoria_insumos_af >'1' AND cpp.idproyecto = '$id_proyecto'  GROUP BY dc.idproducto";
    $cant_activo_fijo = ejecutarConsultaArray($sql_4);
    if ($cant_activo_fijo['status'] == false) { return $cant_activo_fijo; }

    $data = array(
      'cant_proveedores'=> ( empty($cant_proveedores['data']) ? 0 : count($cant_proveedores['data'])),
      'cant_producto'   => (empty($cant_producto['data']) ? 0 : count($cant_producto['data'])),
      'cant_insumo'     => (empty($cant_insumo['data']) ? 0 : count($cant_insumo['data'])),
      'cant_activo_fijo'=> (empty($cant_activo_fijo['data']) ? 0 : count($cant_activo_fijo['data'])),
      
    );
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
    
  }

  public function chart_linea($id_proyecto, $valorizacion_filtro, $array_fechas_valorizacion, $numero_valorizacion, $fecha_i, $fecha_f, $cant_valorizacion) {
    
    $monto_programado = Array(); $monto_valorizado = Array(); $monto_gastado = Array(); $monto_utilidad = Array();

    $factura_total = 0; 
    
    $total_monto_programado = 0; $total_monto_valorizado = 0;  $total_monto_gastado = 0; 

    if ($valorizacion_filtro == null || $valorizacion_filtro == '' || $valorizacion_filtro == '0' ) {

      foreach (json_decode($array_fechas_valorizacion, true) as $key => $value) {
        $num_val  = $value['num_val'];
        $sql_1 = "SELECT numero_q_s, monto_programado, monto_valorizado FROM resumen_q_s_valorizacion 
        WHERE idproyecto = '$id_proyecto' AND numero_q_s = '$num_val' AND estado = '1' AND estado_delete = '1'";
        $valorizacion = ejecutarConsultaSimpleFila($sql_1);
        if ($valorizacion['status'] == false) { return $valorizacion; }

        $cant_monto_gastado = suma_totales($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_gastado = $total_monto_gastado + $cant_monto_gastado;

        array_push($monto_programado, (empty($valorizacion['data']) ? 0 : (empty($valorizacion['data']['monto_programado']) ? 0 : floatval($valorizacion['data']['monto_programado']) ) ));
        $val_monto_valorizado = (empty($valorizacion['data']) ? 0 : (empty($valorizacion['data']['monto_valorizado']) ? 0 : floatval($valorizacion['data']['monto_valorizado']) ) );
        array_push($monto_valorizado, $val_monto_valorizado);
        array_push($monto_gastado, (empty($cant_monto_gastado) ? 0 :  floatval($cant_monto_gastado)) );
        array_push($monto_utilidad, ($val_monto_valorizado - $cant_monto_gastado) );
      }  
      
      $sql_2 = "SELECT SUM(monto_programado) as monto_programado, SUM(monto_valorizado) as monto_valorizado FROM resumen_q_s_valorizacion 
      WHERE idproyecto = '$id_proyecto' AND estado = '1' AND estado_delete = '1';";
      $totales = ejecutarConsultaSimpleFila($sql_2);
      if ($totales['status'] == false) { return $totales; }

      $total_monto_programado = (empty($totales['data']) ? 0 : (empty($totales['data']['monto_programado']) ? 0 : floatval($totales['data']['monto_programado']) ) );
      $total_monto_valorizado = (empty($totales['data']) ? 0 : (empty($totales['data']['monto_valorizado']) ? 0 : floatval($totales['data']['monto_valorizado']) ) );
    }else{
      $sql_1 = "SELECT numero_q_s, monto_programado, monto_valorizado FROM resumen_q_s_valorizacion 
      WHERE idproyecto = '$id_proyecto' AND numero_q_s = '$numero_valorizacion' AND estado = '1' AND estado_delete = '1'";
      $valorizacion = ejecutarConsultaSimpleFila($sql_1);
      if ($valorizacion['status'] == false) { return $valorizacion; }

      $total_monto_programado = (empty($valorizacion['data']) ? 0 : (empty($valorizacion['data']['monto_programado']) ? 0 : floatval($valorizacion['data']['monto_programado']) ) );
      $total_monto_valorizado = (empty($valorizacion['data']) ? 0 : (empty($valorizacion['data']['monto_valorizado']) ? 0 : floatval($valorizacion['data']['monto_valorizado']) ) );

      $fecha_iterativa = $fecha_i;
      $fin_while = false;
      while (true) {
        if (validar_fecha_menor_igual_que($fecha_iterativa, $fecha_f) == true) {

          $cant_monto_gastado = suma_totales($id_proyecto, $fecha_iterativa, '');
          $total_monto_gastado = $total_monto_gastado + $cant_monto_gastado;

          array_push($monto_programado, (empty($valorizacion['data']) ? 0 : (empty($valorizacion['data']['monto_programado']) ? 0 : floatval($valorizacion['data']['monto_programado']) ) ));
          $val_monto_valorizado = (empty($valorizacion['data']) ? 0 : (empty($valorizacion['data']['monto_valorizado']) ? 0 : floatval($valorizacion['data']['monto_valorizado']) ) );
          array_push($monto_valorizado, $val_monto_valorizado);          
          array_push($monto_gastado, (empty($cant_monto_gastado) ? 0 :  floatval($cant_monto_gastado)) );
          array_push($monto_utilidad, ($val_monto_valorizado - $cant_monto_gastado) );
        } else {
          break;
        } 
        $fecha_iterativa = sumar_dias( 1, $fecha_iterativa );       
      }
    }    
    
    return $retorno = [
      'status'=> true, 'message' => 'Salió todo ok,', 
      'data' => [
        'monto_programado'=>$monto_programado, 
        'monto_valorizado'=>$monto_valorizado, 
        'monto_gastado'=>$monto_gastado, 
        'monto_utilidad'=> $monto_utilidad,
        
        'total_monto_programado'=>$total_monto_programado,
        'total_monto_valorizado'=>$total_monto_valorizado,
        'total_monto_gastado'=>$total_monto_gastado,
        'total_utilidad'=>$total_monto_valorizado - $total_monto_gastado,
      ]  
    ];
  }

  // Data para listar lo bototnes por quincena
  public function listar_btn_q_s($nube_idproyecto) {
    $sql = "SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin, p.plazo, p.fecha_pago_obrero, p.fecha_valorizacion 
		FROM proyecto as p 
		WHERE p.idproyecto = '$nube_idproyecto' AND p.fecha_inicio != p.fecha_fin";

    return ejecutarConsultaSimpleFila($sql);
  }
}

function suma_totales($idproyecto, $fecha_1, $fecha_2) {

  $data = Array(); $total = 0; $subtotal = 0; $igv = 0;

  // SUMAS TOTALES - COMPRA INSUMO --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND cpp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND cpp.fecha_compra = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND cpp.fecha_compra = '$fecha_2'";
      }     
    }      
  }    

  $sql = "SELECT SUM(cpp.total) AS total, SUM(cpp.subtotal) AS subtotal, SUM(cpp.igv) AS igv
  FROM compra_por_proyecto AS cpp, proveedor p
  WHERE cpp.idproveedor = p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1'  AND  cpp.idproyecto = $idproyecto $filtro_fecha ;";
  $compra = ejecutarConsultaSimpleFila($sql);

  if ($compra['status'] == false) { return $compra; }

  $total    += (empty($compra['data'])) ? 0 : ( empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']) );
  $subtotal += (empty($compra['data'])) ? 0 : ( empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']) );
  $igv      += (empty($compra['data'])) ? 0 : ( empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']) );

  // SUMAS TOTALES - COMPRAS DE ACTIVO FIJO --------------------------------------------------------------------------------
  // $filtro_fecha = "";

  // if ( !empty($fecha_1) && !empty($fecha_2) ) {
  //   $filtro_fecha = "AND cafg.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
  // } else {
  //   if (!empty($fecha_1)) {
  //     $filtro_fecha = "AND cafg.fecha_compra = '$fecha_1'";
  //   }else{
  //     if (!empty($fecha_2)) {
  //       $filtro_fecha = "AND cafg.fecha_compra = '$fecha_2'";
  //     }     
  //   }      
  // }    

  // $sql = "SELECT SUM(cafg.total) AS total, SUM(cafg.subtotal) AS subtotal, SUM(cafg.igv) AS igv
  // FROM compra_af_general  AS cafg, proveedor p
  // WHERE cafg.idproveedor = p.idproveedor AND cafg.estado = '1' AND cafg.estado_delete = '1' $filtro_fecha ;";
  // $compra = ejecutarConsultaSimpleFila($sql);

  // if ($compra['status'] == false) { return $compra; }

  // $total    += (empty($compra['data'])) ? 0 : ( empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']) );
  // $subtotal += (empty($compra['data'])) ? 0 : ( empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']) );
  // $igv      += (empty($compra['data'])) ? 0 : ( empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']) );
    
  // SUMAS TOTALES - MAQUINARIA EQUIPO --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND f.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND f.fecha_emision = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND f.fecha_emision = '$fecha_2'";
      }     
    }      
  }    

  $sql2 = "SELECT SUM(f.monto) AS total , SUM(f.subtotal) AS subtotal, SUM(f.igv) AS igv
  FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
  WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
  AND f.estado = '1' AND f.estado_delete = '1'  AND f.idproyecto = $idproyecto $filtro_fecha;";
  $maquinaria = ejecutarConsultaSimpleFila($sql2);

  if ($maquinaria['status'] == false) { return $maquinaria; } 

  $total    += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['total']) ? 0 : floatval($maquinaria['data']['total']) );
  $subtotal += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['subtotal']) ? 0 : floatval($maquinaria['data']['subtotal']) );
  $igv      += (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['igv']) ? 0 : floatval($maquinaria['data']['igv']) );

  // SUMAS TOTALES - SUB CONTRATO --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND s.fecha_subcontrato BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_2'";
      }     
    }      
  }    

  $sql3 = "SELECT SUM(s.subtotal) as subtotal, SUM(s.igv) as igv, SUM(s.costo_parcial) as total
  FROM subcontrato AS s, proveedor as p
  WHERE s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1'  AND  idproyecto = $idproyecto $filtro_fecha;";
  $otro_gasto = ejecutarConsultaSimpleFila($sql3);

  if ($otro_gasto['status'] == false) { return $otro_gasto; } 
  
  $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
  $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
  $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );

  // SUMAS TOTALES - PLANILLA SEGURO --------------------------------------------------------------------------------

  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND ps.fecha_p_s BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND ps.fecha_p_s = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND ps.fecha_p_s = '$fecha_2'";
      }     
    }      
  }    

  $sql3 = "SELECT SUM(ps.subtotal) AS subtotal, SUM(ps.igv) AS igv, SUM(ps.costo_parcial) AS total
  FROM planilla_seguro as ps, proyecto as p
  WHERE ps.idproyecto = p.idproyecto and ps.estado ='1' and ps.estado_delete = '1' 
    AND  ps.idproyecto = $idproyecto $filtro_fecha;";
  $otro_gasto = ejecutarConsultaSimpleFila($sql3);

  if ($otro_gasto['status'] == false) { return $otro_gasto; } 
  
  $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
  $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
  $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );

  // SUMAS TOTALES - OTRO GASTO --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fecha_g BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND fecha_g = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND fecha_g = '$fecha_2'";
      }     
    }      
  }    

  $sql3 = "SELECT SUM(costo_parcial) as total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
  FROM otro_gasto  
  WHERE estado = '1' AND estado_delete = '1'  AND  idproyecto = $idproyecto $filtro_fecha;";
  $otro_gasto = ejecutarConsultaSimpleFila($sql3);

  if ($otro_gasto['status'] == false) { return $otro_gasto; } 
  
  $total    += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
  $subtotal += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
  $igv      += (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );

  // SUMAS TOTALES - TRASNPORTE --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND t.fecha_viaje BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND t.fecha_viaje = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND t.fecha_viaje = '$fecha_2'";
      }     
    }      
  }    

  $sql4 = "SELECT SUM(t.precio_parcial) AS total, SUM(t.subtotal) AS subtotal, SUM(t.igv) AS igv
  FROM transporte AS t, proveedor AS p
  WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' AND t.idproyecto = $idproyecto  $filtro_fecha;";
  $transporte = ejecutarConsultaSimpleFila($sql4);

  if ($transporte['status'] == false) { return $transporte; }
  
  $total    += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['total']) ? 0 : floatval($transporte['data']['total']) );
  $subtotal += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['subtotal']) ? 0 : floatval($transporte['data']['subtotal']) );
  $igv      += (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['igv']) ? 0 : floatval($transporte['data']['igv']) );

  // SUMAS TOTALES - HOSPEDAJE --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fecha_comprobante BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND fecha_comprobante = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND fecha_comprobante = '$fecha_2'";
      }     
    }      
  }    

  $sql5 = "SELECT SUM(precio_parcial) as total , SUM(subtotal) AS subtotal, SUM(igv) AS igv
  FROM hospedaje WHERE estado = '1' AND estado_delete = '1' AND idproyecto = $idproyecto  $filtro_fecha
  ORDER BY fecha_comprobante DESC;";
  $hospedaje = ejecutarConsultaSimpleFila($sql5);

  if ($hospedaje['status'] == false) { return $hospedaje; }
  
  $total    += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['total']) ? 0 : floatval($hospedaje['data']['total']) );
  $subtotal += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['subtotal']) ? 0 : floatval($hospedaje['data']['subtotal']) );
  $igv      += (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['igv']) ? 0 : floatval($hospedaje['data']['igv']) );

  // SUMAS TOTALES - FACTURA PENSION --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND dp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND dp.fecha_emision = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND dp.fecha_emision = '$fecha_2'";
      }     
    }      
  }    

  $sql6 = "SELECT SUM(dp.precio_parcial) AS total, SUM(dp.subtotal) AS subtotal, SUM(dp.igv) AS igv
  FROM detalle_pension as dp, pension as p, proveedor as prov
  WHERE dp.idpension = p.idpension AND prov.idproveedor = p.idproveedor  AND p.estado = '1' AND p.estado_delete = '1' AND  p.idproyecto = $idproyecto
  AND dp.estado = '1' AND dp.estado_delete = '1' $filtro_fecha ;";
  $factura_pension = ejecutarConsultaSimpleFila($sql6);

  if ($factura_pension['status'] == false) { return $factura_pension; }
  
  $total    += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['total']) ? 0 : floatval($factura_pension['data']['total']) );
  $subtotal += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['subtotal']) ? 0 : floatval($factura_pension['data']['subtotal']) );
  $igv      += (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['igv']) ? 0 : floatval($factura_pension['data']['igv']) );

  // SUMAS TOTALES - FACTURA BREACK --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fb.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND fb.fecha_emision = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND fb.fecha_emision = '$fecha_2'";
      }     
    }      
  }    

  $sql7 = "SELECT SUM(fb.monto) AS total, SUM(fb.subtotal) AS subtotal, SUM(fb.igv) AS igv
  FROM factura_break as fb, semana_break as sb
  WHERE  fb.idsemana_break = sb.idsemana_break AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1'  AND  sb.idproyecto = $idproyecto
  AND sb.estado_delete = '1' $filtro_fecha ;";
  $factura_break = ejecutarConsultaSimpleFila($sql7);

  if ($factura_break['status'] == false) { return $factura_break; }
  
  $total    += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['total']) ? 0 : floatval($factura_break['data']['total']) );
  $subtotal += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['subtotal']) ? 0 : floatval($factura_break['data']['subtotal']) );
  $igv      += (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['igv']) ? 0 : floatval($factura_break['data']['igv']) );

  // SUMAS TOTALES - COMIDA EXTRA --------------------------------------------------------------------------------
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fecha_comida BETWEEN '$fecha_1' AND '$fecha_2'";
  } else {
    if (!empty($fecha_1)) {
      $filtro_fecha = "AND fecha_comida = '$fecha_1'";
    }else{
      if (!empty($fecha_2)) {
        $filtro_fecha = "AND fecha_comida = '$fecha_2'";
      }     
    }      
  }    

  $sql8 = "SELECT SUM(costo_parcial) AS total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
  FROM comida_extra
  WHERE  estado = '1' AND estado_delete = '1' AND  idproyecto = $idproyecto $filtro_fecha;";
  $comida_extra = ejecutarConsultaSimpleFila($sql8);

  if ($comida_extra['status'] == false) { return $comida_extra; }
  
  $total    += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['total']) ? 0 : floatval($comida_extra['data']['total']) );
  $subtotal += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['subtotal']) ? 0 : floatval($comida_extra['data']['subtotal']) );
  $igv      += (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['igv']) ? 0 : floatval($comida_extra['data']['igv']) );

  // SUMAS TOTALES - OTRO INGRESO --------------------------------------------------------------------------------

  // $filtro_fecha = "";

  // if ( !empty($fecha_1) && !empty($fecha_2) ) {
  //   $filtro_fecha = "AND oi.fecha_i BETWEEN '$fecha_1' AND '$fecha_2'";
  // } else {
  //   if (!empty($fecha_1)) {
  //     $filtro_fecha = "AND oi.fecha_i = '$fecha_1'";
  //   }else{
  //     if (!empty($fecha_2)) {
  //       $filtro_fecha = "AND oi.fecha_i = '$fecha_2'";
  //     }     
  //   }      
  // }    

  // $sql9 = "SELECT SUM(oi.subtotal) as subtotal, SUM(oi.igv) as igv, SUM(oi.costo_parcial) as total
  // FROM otro_ingreso as oi, proyecto as p
  // WHERE oi.idproyecto = p.idproyecto AND oi.estado = '1' AND oi.estado_delete = '1' AND  oi.idproyecto = $idproyecto $filtro_fecha";
  // $otra_factura = ejecutarConsultaSimpleFila($sql9);

  // if ($otra_factura['status'] == false) { return $otra_factura; } 
  
  // $total    += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['total']) ? 0 : floatval($otra_factura['data']['total']) );
  // $subtotal += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['subtotal']) ? 0 : floatval($otra_factura['data']['subtotal']) );
  // $igv      += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['igv']) ? 0 : floatval($otra_factura['data']['igv']) );

  // SUMAS TOTALES - OTRA FACTURA --------------------------------------------------------------------------------
  // $filtro_fecha = "";

  // if ( !empty($fecha_1) && !empty($fecha_2) ) {
  //   $filtro_fecha = "AND of.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
  // } else {
  //   if (!empty($fecha_1)) {
  //     $filtro_fecha = "AND of.fecha_emision = '$fecha_1'";
  //   }else{
  //     if (!empty($fecha_2)) {
  //       $filtro_fecha = "AND of.fecha_emision = '$fecha_2'";
  //     }     
  //   }      
  // }    

  // $sql9 = "SELECT SUM(of.costo_parcial) AS total, SUM(of.subtotal) AS subtotal, SUM(of.igv) AS igv
  // FROM otra_factura AS of, proveedor p
  // WHERE of.idproveedor = p.idproveedor AND of.estado = '1' AND of.estado_delete = '1' $filtro_fecha";
  // $otra_factura = ejecutarConsultaSimpleFila($sql9);

  // if ($otra_factura['status'] == false) { return $otra_factura; } 
  
  // $total    += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['total']) ? 0 : floatval($otra_factura['data']['total']) );
  // $subtotal += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['subtotal']) ? 0 : floatval($otra_factura['data']['subtotal']) );
  // $igv      += (empty($otra_factura['data'])) ? 0 : ( empty($otra_factura['data']['igv']) ? 0 : floatval($otra_factura['data']['igv']) );


  $data = array( 
    "status"=> true,
    "message"=> 'todo oka',
    "data"=> [
      "total" => $total, 
      "subtotal" => $subtotal, 
      "igv" => $igv,  
    ]      
  );

  return $total ;
}  
    
?>
