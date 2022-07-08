<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ChartCompraInsumo
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

  public function chart_linea($id_proyecto, $year_filtro, $mes_filtro, $dias_filtro) {
    $data_gasto = Array(); $data_pagos = Array();

    $factura_total = 0; $factura_aceptadas = 0; $factura_rechazadas = 0; $factura_eliminadas = 0; $factura_rechazadas_eliminadas = 0;

    if ($year_filtro == null || $year_filtro == '' || $mes_filtro == null || $mes_filtro == null) {
      for ($i=1; $i <= 12 ; $i++) { 
        $sql_1 = "SELECT idproveedor, SUM(total) as total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
        ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
        FROM compra_por_proyecto  WHERE MONTH(fecha_compra)='$i' AND   YEAR(fecha_compra) = '$year_filtro' AND idproyecto='$id_proyecto' AND estado='1' AND estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_1);
        if ($mes['status'] == false) { return $mes; }
        array_push($data_gasto, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));
  
        $sql_2 = "SELECT SUM(pg.monto) as total_deposito  
        FROM pago_compras as pg, compra_por_proyecto as cpp 
        WHERE pg.idcompra_proyecto = cpp.idcompra_proyecto AND MONTH(pg.fecha_pago)='$i' AND YEAR(pg.fecha_pago) = '$year_filtro' AND cpp.idproyecto='$id_proyecto' AND cpp.estado='1' AND cpp.estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_2);
        if ($mes['status'] == false) { return $mes; }
        array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['total_deposito']) ? 0 : floatval($mes['data']['total_deposito']) ) ));       
  
      }
      $sql_3 = "SELECT COUNT(idcompra_proyecto) as factura_total FROM compra_por_proyecto WHERE  YEAR(fecha_compra) = '$year_filtro' AND idproyecto ='$id_proyecto';";
      $factura_total = ejecutarConsultaSimpleFila($sql_3);
      if ($factura_total['status'] == false) { return $factura_total; }

      $sql_4 = "SELECT COUNT(idcompra_proyecto) as factura_aceptadas FROM compra_por_proyecto WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1' AND idproyecto ='$id_proyecto';";
      $factura_aceptadas = ejecutarConsultaSimpleFila($sql_4);
      if ($factura_aceptadas['status'] == false) { return $factura_aceptadas; }

      $sql_5 = "SELECT COUNT(idcompra_proyecto) as factura_rechazadas FROM compra_por_proyecto WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='0' AND estado_delete='1' AND idproyecto ='$id_proyecto';";
      $factura_rechazadas = ejecutarConsultaSimpleFila($sql_5);
      if ($factura_rechazadas['status'] == false) { return $factura_rechazadas; }

      $sql_6 = "SELECT COUNT(idcompra_proyecto) as factura_eliminadas FROM compra_por_proyecto WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='0' AND idproyecto ='$id_proyecto';";
      $factura_eliminadas = ejecutarConsultaSimpleFila($sql_6);
      if ($factura_eliminadas['status'] == false) { return $factura_eliminadas; }

      $sql_7 = "SELECT COUNT(idcompra_proyecto) as factura_rechazadas_eliminadas FROM compra_por_proyecto WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='0' AND estado_delete='0' AND idproyecto ='$id_proyecto';";
      $factura_rechazadas_eliminadas = ejecutarConsultaSimpleFila($sql_7);
      if ($factura_rechazadas_eliminadas['status'] == false) { return $factura_rechazadas_eliminadas; }

    }else{
      for ($i=1; $i <= $dias_filtro ; $i++) {
        $sql = "SELECT idproveedor, SUM(total) as total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
        ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
        FROM compra_por_proyecto  WHERE DAY(fecha_compra)='$i' AND MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND idproyecto='$id_proyecto' AND estado='1' AND estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql);
        if ($mes['status'] == false) { return $mes; }
        array_push($data_gasto, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));
  
        $sql = "SELECT SUM(pg.monto) as total_deposito  
        FROM pago_compras as pg, compra_por_proyecto as cpp 
        WHERE pg.idcompra_proyecto = cpp.idcompra_proyecto AND DAY(pg.fecha_pago)='$i' AND MONTH(pg.fecha_pago)='$mes_filtro' AND YEAR(pg.fecha_pago) = '$year_filtro' AND cpp.idproyecto='$id_proyecto' AND cpp.estado='1' AND cpp.estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql);
        if ($mes['status'] == false) { return $mes; }
        array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['total_deposito']) ? 0 : floatval($mes['data']['total_deposito']) ) ));
      }
    }
    
    
    return $retorno = [
      'status'=> true, 'message' => 'Salió todo ok,', 
      'data' => [
        'total_gasto'=>$data_gasto, 
        'total_deposito'=>$data_pagos, 

        'factura_total'=>(empty($factura_total['data']) ? 0 : (empty($factura_total['data']['factura_total']) ? 0 : floatval($factura_total['data']['factura_total']) ) ), 
        'factura_aceptadas'=>(empty($factura_aceptadas['data']) ? 0 : (empty($factura_aceptadas['data']['factura_aceptadas']) ? 0 : floatval($factura_aceptadas['data']['factura_aceptadas']) ) ), 
        'factura_rechazadas'=>(empty($factura_rechazadas['data']) ? 0 : (empty($factura_rechazadas['data']['factura_rechazadas']) ? 0 : floatval($factura_rechazadas['data']['factura_rechazadas']) ) ), 
        'factura_eliminadas'=>(empty($factura_eliminadas['data']) ? 0 : (empty($factura_eliminadas['data']['factura_eliminadas']) ? 0 : floatval($factura_eliminadas['data']['factura_eliminadas']) ) ),
        'factura_rechazadas_eliminadas'=>(empty($factura_rechazadas_eliminadas['data']) ? 0 : (empty($factura_rechazadas_eliminadas['data']['factura_rechazadas_eliminadas']) ? 0 : floatval($factura_rechazadas_eliminadas['data']['factura_rechazadas_eliminadas']) ) ), 
      ]  
    ];
  }

  public function anios_select2($id_proyecto) {
    $sql = "SELECT DISTINCTROW YEAR(fecha_compra) as anios FROM compra_por_proyecto WHERE idproyecto = '$id_proyecto' ORDER BY fecha_compra DESC;";
    return ejecutarConsultaArray($sql);
  }
    
}

?>
