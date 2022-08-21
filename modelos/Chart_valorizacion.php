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
    $monto_valorizacion_gastado = 0;
    $monto_valorizacion_valorizado = 0;
    $monto_valorizacion_utilidad = 0;

    $cont = 1;
    // valorizaciones
    $monto_programado = Array(); $monto_valorizado = Array(); $monto_gastado = Array(); $monto_utilidad = Array();
    $monto_acumulado_programado = Array(); $monto_acumulado_valorizado = Array(); $monto_acumulado_gastado = Array(); $monto_acumulado_utilidad = Array();

    $factura_total = 0; 
    
    $total_monto_programado = 0; $total_monto_valorizado = 0;  $total_monto_gastado = 0; 
    $acumulado_monto_programado = 0; $acumulado_monto_valorizado = 0;  $acumulado_monto_gastado = 0; $acumulado_monto_utilidad = 0; 

    // resumen_modulos
    $tabla_resumen = Array();  $monto_resumen_modulos = Array();  $utilidad_resumen_modulos = Array();
    // compra_insumos
    $total_monto_compra_insumos = 0;  $total_utilidad_compra_insumos = 0;   $monto_acumulado_compra_insumos = Array();  $utilidad_acumulado_compra_insumos = Array();   $monto_compra_insumos = Array();  $utilidad_compra_insumos = Array();
    $tabla_compra_insumos = Array();
    // maquina_y_equipo
    $total_monto_maquina_y_equipo = 0;$total_utilidad_maquina_y_equipo = 0; $monto_acumulado_maquina_y_equipo = Array();$utilidad_acumulado_maquina_y_equipo = Array(); $monto_maquina_y_equipo = Array();$utilidad_maquina_y_equipo = Array();
    $tabla_maquina_y_equipo = Array();
    // subcontrato
    $total_monto_subcontrato = 0;     $total_utilidad_subcontrato = 0;      $monto_acumulado_subcontrato = Array();     $utilidad_acumulado_subcontrato = Array();      $monto_subcontrato = Array();     $utilidad_subcontrato = Array();
    $tabla_subcontrato = Array();
    // planilla_seguro
    $total_monto_planilla_seguro = 0; $total_utilidad_planilla_seguro = 0;  $monto_acumulado_planilla_seguro = Array(); $utilidad_acumulado_planilla_seguro = Array();  $monto_planilla_seguro = Array(); $utilidad_planilla_seguro = Array();
    $tabla_planilla_seguro = Array();
    // otro_gasto
    $total_monto_otro_gasto = 0;      $total_utilidad_otro_gasto = 0;       $monto_acumulado_otro_gasto = Array();      $utilidad_acumulado_otro_gasto = Array();       $monto_otro_gasto = Array();      $utilidad_otro_gasto = Array();
    $tabla_otro_gasto = Array();
    // trasnporte
    $total_monto_transporte = 0;      $total_utilidad_transporte = 0;       $monto_acumulado_transporte = Array();      $utilidad_acumulado_transporte = Array();       $monto_transporte = Array();      $utilidad_transporte = Array();
    $tabla_transporte = Array();
    // hospedaje
    $total_monto_hospedaje = 0;       $total_utilidad_hospedaje = 0;        $monto_acumulado_hospedaje = Array();       $utilidad_acumulado_hospedaje = Array();        $monto_hospedaje = Array();       $utilidad_hospedaje = Array();
    $tabla_hospedaje = Array();
    // pension
    $total_monto_pension = 0;         $total_utilidad_pension = 0;          $monto_acumulado_pension = Array();         $utilidad_acumulado_pension = Array();          $monto_pension = Array();         $utilidad_pension = Array();
    $tabla_pension = Array();
    // breack
    $total_monto_breack = 0;          $total_utilidad_breack = 0;           $monto_acumulado_breack = Array();          $utilidad_acumulado_breack = Array();           $monto_breack = Array();          $utilidad_breack = Array();
    $tabla_breack = Array();
    // comida_extra
    $total_monto_comida_extra = 0;    $total_utilidad_comida_extra = 0;     $monto_acumulado_comida_extra = Array();    $utilidad_acumulado_comida_extra = Array();     $monto_comida_extra = Array();    $utilidad_comida_extra = Array(); 
    $tabla_comida_extra = Array();
    // pago_administrador
    $total_monto_pago_administrador = 0;    $total_utilidad_pago_administrador = 0;     $monto_acumulado_pago_administrador = Array();    $utilidad_acumulado_pago_administrador = Array();     $monto_pago_administrador = Array();    $utilidad_pago_administrador = Array(); 
    $tabla_pago_administrador = Array();
    // pago_obrero
    $total_monto_pago_obrero = 0;    $total_utilidad_pago_obrero = 0;     $monto_acumulado_pago_obrero = Array();    $utilidad_acumulado_pago_obrero = Array();     $monto_pago_obrero = Array();    $utilidad_pago_obrero = Array(); 
    $tabla_pago_obrero = Array();

    // TODAS LAS VALORIZACIONES :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    if ($valorizacion_filtro == null || $valorizacion_filtro == '' || $valorizacion_filtro == '0' ) {
      // extraemos datos generales
      $sql_date = "SELECT fecha_inicio_actividad, fecha_fin_actividad, fecha_inicio, fecha_fin  FROM proyecto WHERE idproyecto = '$id_proyecto';";
      $fecha_proyecto = ejecutarConsultaSimpleFila($sql_date);
      if ($fecha_proyecto['status'] == false) { return $fecha_proyecto; } 

      $sql_val = "SELECT SUM(monto_valorizado) AS monto_valorizado FROM resumen_q_s_valorizacion WHERE idproyecto = '$id_proyecto' AND estado = '1' AND estado_delete = '1'";
      $init_valorizado = ejecutarConsultaSimpleFila($sql_val);
      if ($init_valorizado['status'] == false) { return $init_valorizado; }

      if (empty($fecha_proyecto['data']['fecha_inicio']) || empty($fecha_proyecto['data']['fecha_fin'])) {       
        return $retorno = ['status'=>'error_ing_pool', 'user' => $_SESSION['nombre'], 'mesage'=>'No ha definido las fechas de <b>INICIO</b> o <b>FIN</b> de proyecto.', 'data'=>'sin data', ]; 
      } 

      $monto_valorizacion_gastado   = suma_totales_modulos($id_proyecto, $fecha_proyecto['data']['fecha_inicio'],$fecha_proyecto['data']['fecha_fin']);
      $monto_valorizacion_valorizado = (empty($init_valorizado['data']) ? 0 : (empty($init_valorizado['data']['monto_valorizado']) ? 0 : floatval($init_valorizado['data']['monto_valorizado']) ) );
      $monto_valorizacion_utilidad = $monto_valorizacion_valorizado - $monto_valorizacion_gastado;
      // end - extraemos datos generales

      foreach (json_decode($array_fechas_valorizacion, true) as $key => $value) {
        // valorizacion --------
        $num_val  = $value['num_val'];
        $sql_1 = "SELECT numero_q_s, monto_programado, monto_valorizado FROM resumen_q_s_valorizacion 
        WHERE idproyecto = '$id_proyecto' AND numero_q_s = '$num_val' AND estado = '1' AND estado_delete = '1'";
        $valorizacion = ejecutarConsultaSimpleFila($sql_1);
        if ($valorizacion['status'] == false) { return $valorizacion; }        

        $val_monto_programado = (empty($valorizacion['data']) ? 0 : (empty($valorizacion['data']['monto_programado']) ? 0 : floatval($valorizacion['data']['monto_programado']) ) );
        $val_monto_valorizado = (empty($valorizacion['data']) ? 0 : (empty($valorizacion['data']['monto_valorizado']) ? 0 : floatval($valorizacion['data']['monto_valorizado']) ) );
        $cant_monto_gastado   = suma_totales_modulos($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_gastado += $cant_monto_gastado;
        $val_monto_utilidad   = $val_monto_valorizado - $cant_monto_gastado;

        array_push($monto_programado, round($val_monto_programado,2) );        
        array_push($monto_valorizado, round($val_monto_valorizado,2) );
        array_push($monto_gastado,    round($cant_monto_gastado,2) );
        array_push($monto_utilidad,   round($val_monto_utilidad,2) );

        $acumulado_monto_programado += $val_monto_programado;
        $acumulado_monto_valorizado += $val_monto_valorizado;
        $acumulado_monto_gastado    += $cant_monto_gastado;
        $acumulado_monto_utilidad   += $val_monto_utilidad;

        if ($val_monto_programado != 0) {array_push($monto_acumulado_programado, round($acumulado_monto_programado,2) );   } 
        if ($val_monto_valorizado != 0) {array_push($monto_acumulado_valorizado, round($acumulado_monto_valorizado,2) );   } 
        if ($cant_monto_gastado != 0) {array_push($monto_acumulado_gastado,    round($acumulado_monto_gastado,2) );   } 
        if ($val_monto_utilidad != 0) {array_push($monto_acumulado_utilidad,   round($acumulado_monto_utilidad,2) );   }         

        //  ---------------------------------------------- modulos ----------------------------------------------

        // compra_insumos
        $cant_monto_compra_insumos     = suma_totales_compra_insumos($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_compra_insumos   += $cant_monto_compra_insumos;
        $val_utilidad_compra_insumos   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_compra_insumos )/$monto_valorizacion_gastado)  ;
        $total_utilidad_compra_insumos+= $val_utilidad_compra_insumos;
        array_push($monto_acumulado_compra_insumos,   round($total_monto_compra_insumos,2) );
        array_push($utilidad_acumulado_compra_insumos,   round($total_utilidad_compra_insumos,2) ); 
        array_push($monto_compra_insumos,   round($cant_monto_compra_insumos,2) );
        array_push($utilidad_compra_insumos,   round($val_utilidad_compra_insumos,2) );  
        $tabla_compra_insumos[]= array(
          "modulo"=>'Compra de insumos',"val"=>'Val'.$cont, "gasto"=>$cant_monto_compra_insumos, "utilidad"=>$val_utilidad_compra_insumos, "gasto_t"=>$cant_monto_gastado, "utilidad_t"=>$val_utilidad_compra_insumos,  "ver_mas"=>'compra_insumos.php',
        );          
        // maquina_y_equipo
        $cant_monto_maquina_y_equipo     = suma_totales_maquina_y_equipo($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_maquina_y_equipo   += $cant_monto_maquina_y_equipo;
        $val_utilidad_maquina_y_equipo   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_maquina_y_equipo)/$monto_valorizacion_gastado);
        $total_utilidad_maquina_y_equipo+= $val_utilidad_maquina_y_equipo;
        array_push($monto_acumulado_maquina_y_equipo,   round($total_monto_maquina_y_equipo,2) );
        array_push($utilidad_acumulado_maquina_y_equipo,   round($total_utilidad_maquina_y_equipo,2) );
        array_push($monto_maquina_y_equipo,   round($cant_monto_maquina_y_equipo,2) );
        array_push($utilidad_maquina_y_equipo,   round($val_utilidad_maquina_y_equipo,2) );
        $tabla_maquina_y_equipo[]= array(
          "modulo"=>'Maquinas y Equipos',"val"=>'Val'.$cont, "gasto"=>$cant_monto_maquina_y_equipo, "utilidad"=>$val_utilidad_maquina_y_equipo, "ver_mas"=>'servicio_maquina.php',
        );
        // subcontrato
        $cant_monto_subcontrato     = suma_totales_subcontrato($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_subcontrato   += $cant_monto_subcontrato;
        $val_utilidad_subcontrato   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_subcontrato)/$monto_valorizacion_gastado);
        $total_utilidad_subcontrato+= $val_utilidad_subcontrato;
        array_push($monto_acumulado_subcontrato,   round($total_monto_subcontrato,2) );
        array_push($utilidad_acumulado_subcontrato,   round($total_utilidad_subcontrato,2) );
        array_push($monto_subcontrato,   round($cant_monto_subcontrato,2) );
        array_push($utilidad_subcontrato,   round($val_utilidad_subcontrato,2) );
        $tabla_subcontrato[]= array(
          "modulo"=>'Subcontrato',"val"=>'Val'.$cont, "gasto"=>$cant_monto_subcontrato, "utilidad"=>$val_utilidad_subcontrato, "ver_mas"=>'sub_contrato.php',
        );
        // planilla_seguro
        $cant_monto_planilla_seguro     = suma_totales_planilla_seguro($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_planilla_seguro   += $cant_monto_planilla_seguro;
        $val_utilidad_planilla_seguro   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_planilla_seguro)/$monto_valorizacion_gastado);
        $total_utilidad_planilla_seguro+= $val_utilidad_planilla_seguro;
        array_push($monto_acumulado_planilla_seguro,   round($total_monto_planilla_seguro,2) );
        array_push($utilidad_acumulado_planilla_seguro,   round($total_utilidad_planilla_seguro,2) );
        array_push($monto_planilla_seguro,   round($cant_monto_planilla_seguro,2) );
        array_push($utilidad_planilla_seguro,   round($val_utilidad_planilla_seguro,2) );
        $tabla_planilla_seguro[]= array(
          "modulo"=>'Planilla Seguro',"val"=>'Val'.$cont, "gasto"=>$cant_monto_planilla_seguro, "utilidad"=>$val_utilidad_planilla_seguro, "ver_mas"=>'planillas_seguros.php',
        );
        // otro_gasto
        $cant_monto_otro_gasto     = suma_totales_otro_gasto($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_otro_gasto   += $cant_monto_otro_gasto;
        $val_utilidad_otro_gasto   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_otro_gasto)/$monto_valorizacion_gastado);
        $total_utilidad_otro_gasto+= $val_utilidad_otro_gasto;
        array_push($monto_acumulado_otro_gasto,   round($total_monto_otro_gasto,2) );
        array_push($utilidad_acumulado_otro_gasto,   round($total_utilidad_otro_gasto,2) );
        array_push($monto_otro_gasto,   round($cant_monto_otro_gasto,2) );
        array_push($utilidad_otro_gasto,   round($val_utilidad_otro_gasto,2) );
        $tabla_otro_gasto[]= array(
          "modulo"=>'Otro Gasto',"val"=>'Val'.$cont, "gasto"=>$cant_monto_otro_gasto, "utilidad"=>$val_utilidad_otro_gasto, "ver_mas"=>'otro_gasto.php',
        );
        // transporte
        $cant_monto_transporte     = suma_totales_transporte($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_transporte   += $cant_monto_transporte;
        $val_utilidad_transporte   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_transporte)/$monto_valorizacion_gastado);
        $total_utilidad_transporte+= $val_utilidad_transporte;
        array_push($monto_acumulado_transporte,   round($total_monto_transporte,2) );
        array_push($utilidad_acumulado_transporte,   round($total_utilidad_transporte,2) );
        array_push($monto_transporte,   round($cant_monto_transporte,2) );
        array_push($utilidad_transporte,   round($val_utilidad_transporte,2) );
        $tabla_transporte[]= array(
          "modulo"=>'Transporte',"val"=>'Val'.$cont, "gasto"=>$cant_monto_transporte, "utilidad"=>$val_utilidad_transporte, "ver_mas"=>'transporte.php',
        );
        // hospedaje
        $cant_monto_hospedaje     = suma_totales_hospedaje($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_hospedaje   += $cant_monto_hospedaje;
        $val_utilidad_hospedaje   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_hospedaje)/$monto_valorizacion_gastado);
        $total_utilidad_hospedaje+= $val_utilidad_hospedaje;
        array_push($monto_acumulado_hospedaje,   round($total_monto_hospedaje,2) );
        array_push($utilidad_acumulado_hospedaje,   round($total_utilidad_hospedaje,2) );
        array_push($monto_hospedaje,   round($cant_monto_hospedaje,2) );
        array_push($utilidad_hospedaje,   round($val_utilidad_hospedaje,2) );
        $tabla_hospedaje[]= array(
          "modulo"=>'Hospedaje',"val"=>'Val'.$cont, "gasto"=>$cant_monto_hospedaje, "utilidad"=>$val_utilidad_hospedaje, "ver_mas"=>'hospedaje.php',
        );
        // pension
        $cant_monto_pension     = suma_totales_pension($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_pension   += $cant_monto_pension;
        $val_utilidad_pension   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_pension)/$monto_valorizacion_gastado);
        $total_utilidad_pension+= $val_utilidad_pension;
        array_push($monto_acumulado_pension,   round($total_monto_pension,2) );
        array_push($utilidad_acumulado_pension,   round($total_utilidad_pension,2) );
        array_push($monto_pension,   round($cant_monto_pension,2) );
        array_push($utilidad_pension,   round($val_utilidad_pension,2) );
        $tabla_pension[]= array(
          "modulo"=>'Pension',"val"=>'Val'.$cont, "gasto"=>$cant_monto_pension, "utilidad"=>$val_utilidad_pension, "ver_mas"=>'pension.php',
        );
        // breack
        $cant_monto_breack     = suma_totales_breack($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_breack   += $cant_monto_breack;
        $val_utilidad_breack   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_breack)/$monto_valorizacion_gastado);
        $total_utilidad_breack+= $val_utilidad_breack;
        array_push($monto_acumulado_breack,   round($total_monto_breack,2) );
        array_push($utilidad_acumulado_breack,   round($total_utilidad_breack,2) );
        array_push($monto_breack,   round($cant_monto_breack,2) );
        array_push($utilidad_breack,   round($val_utilidad_breack,2) );
        $tabla_breack[]= array(
          "modulo"=>'Breack',"val"=>'Val'.$cont, "gasto"=>$cant_monto_breack, "utilidad"=>$val_utilidad_breack, "ver_mas"=>'break.php',
        );
        // comida_extra
        $cant_monto_comida_extra     = suma_totales_comida_extra($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_comida_extra   += $cant_monto_comida_extra;
        $val_utilidad_comida_extra   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_comida_extra)/$monto_valorizacion_gastado);
        $total_utilidad_comida_extra+= $val_utilidad_comida_extra;
        array_push($monto_acumulado_comida_extra,   round($total_monto_comida_extra,2) );
        array_push($utilidad_acumulado_comida_extra,   round($total_utilidad_comida_extra,2) );
        array_push($monto_comida_extra,   round($cant_monto_comida_extra,2) );
        array_push($utilidad_comida_extra,   round($val_utilidad_comida_extra,2) );
        $tabla_comida_extra[]= array(
          "modulo"=>'Comida Extra',"val"=>'Val'.$cont, "gasto"=>$cant_monto_comida_extra, "utilidad"=>$val_utilidad_comida_extra, "ver_mas"=>'comidas_extras.php',
        );
        // pago_administrador
        $cant_monto_pago_administrador     = suma_totales_pago_administrador($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_pago_administrador   += $cant_monto_pago_administrador;
        $val_utilidad_pago_administrador   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_pago_administrador)/$monto_valorizacion_gastado);
        $total_utilidad_pago_administrador+= $val_utilidad_pago_administrador;
        array_push($monto_acumulado_pago_administrador,   round($total_monto_pago_administrador,2) );
        array_push($utilidad_acumulado_pago_administrador,   round($total_utilidad_pago_administrador,2) );
        array_push($monto_pago_administrador,   round($cant_monto_pago_administrador,2) );
        array_push($utilidad_pago_administrador,   round($val_utilidad_pago_administrador,2) );
        $tabla_pago_administrador[]= array(
          "modulo"=>'Pago Administrador',"val"=>'Val'.$cont, "gasto"=>$cant_monto_pago_administrador, "utilidad"=>$val_utilidad_pago_administrador, "ver_mas"=>'pago_administrador.php',
        );
        // pago_obrero
        $cant_monto_pago_obrero     = suma_totales_pago_obrero($id_proyecto, $value['fecha_i'], $value['fecha_f']);
        $total_monto_pago_obrero   += $cant_monto_pago_obrero;
        $val_utilidad_pago_obrero   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_pago_obrero)/$monto_valorizacion_gastado);
        $total_utilidad_pago_obrero+= $val_utilidad_pago_obrero;
        array_push($monto_acumulado_pago_obrero,   round($total_monto_pago_obrero,2) );
        array_push($utilidad_acumulado_pago_obrero,   round($total_utilidad_pago_obrero,2) );
        array_push($monto_pago_obrero,   round($cant_monto_pago_obrero,2) );
        array_push($utilidad_pago_obrero,   round($val_utilidad_pago_obrero,2) );
        $tabla_pago_obrero[]= array(
          "modulo"=>'Pago Obrero',"val"=>'Val'.$cont, "gasto"=>$cant_monto_pago_obrero, "utilidad"=>$val_utilidad_pago_obrero, "ver_mas"=>'pago_obrero.php',
        );
        $cont++;
      }  
      
      $sql_2 = "SELECT SUM(monto_programado) as monto_programado, SUM(monto_valorizado) as monto_valorizado FROM resumen_q_s_valorizacion 
      WHERE idproyecto = '$id_proyecto' AND estado = '1' AND estado_delete = '1';";
      $totales = ejecutarConsultaSimpleFila($sql_2);
      if ($totales['status'] == false) { return $totales; }

      $total_monto_programado = (empty($totales['data']) ? 0 : (empty($totales['data']['monto_programado']) ? 0 : floatval($totales['data']['monto_programado']) ) );
      $total_monto_valorizado = (empty($totales['data']) ? 0 : (empty($totales['data']['monto_valorizado']) ? 0 : floatval($totales['data']['monto_valorizado']) ) );
    }else{
      // extraemos datos generales
      $sql_val = "SELECT monto_valorizado AS monto_valorizado FROM resumen_q_s_valorizacion WHERE idproyecto = '$id_proyecto' AND numero_q_s = '$numero_valorizacion' AND estado = '1' AND estado_delete = '1'";
      $init_valorizado = ejecutarConsultaSimpleFila($sql_val);
      if ($init_valorizado['status'] == false) { return $init_valorizado; }

      if (empty($fecha_i) || empty($fecha_f)) {       
        return $retorno = ['status'=>'error_ing_pool', 'user' => $_SESSION['nombre'], 'mesage'=>'No ha definido las fechas de <b>INICIO</b> o <b>FIN</b> de proyecto.', 'data'=>'sin data', ]; 
      } 

      $monto_valorizacion_gastado   = suma_totales_modulos($id_proyecto, $fecha_i,$fecha_f);
      $monto_valorizacion_valorizado = (empty($init_valorizado['data']) ? 0 : (empty($init_valorizado['data']['monto_valorizado']) ? 0 : floatval($init_valorizado['data']['monto_valorizado']) ) );
      $monto_valorizacion_utilidad = $monto_valorizacion_valorizado - $monto_valorizacion_gastado;
      // end - extraemos datos generales

      // UNA SOLA VALORIZACION :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
      $sql_1 = "SELECT numero_q_s, monto_programado, monto_valorizado FROM resumen_q_s_valorizacion 
      WHERE idproyecto = '$id_proyecto' AND numero_q_s = '$numero_valorizacion' AND estado = '1' AND estado_delete = '1'";
      $valorizacion = ejecutarConsultaSimpleFila($sql_1);
      if ($valorizacion['status'] == false) { return $valorizacion; }

      $total_monto_programado = (empty($valorizacion['data']) ? 0 : (empty($valorizacion['data']['monto_programado']) ? 0 : floatval($valorizacion['data']['monto_programado']) ) );
      $total_monto_valorizado = (empty($valorizacion['data']) ? 0 : (empty($valorizacion['data']['monto_valorizado']) ? 0 : floatval($valorizacion['data']['monto_valorizado']) ) );
      
      $cantidad_de_dias = diferencia_days_months_years($fecha_i, $fecha_f, 'days');

      $val_monto_programado = $total_monto_programado/$cantidad_de_dias;
      $val_monto_valorizado = $total_monto_valorizado/$cantidad_de_dias;

      $fecha_iterativa = $fecha_i;
      $fin_while = false;

      while (true) {
        if (validar_fecha_menor_igual_que($fecha_iterativa, $fecha_f) == true) {         
          
          $cant_monto_gastado   = suma_totales_modulos($id_proyecto, $fecha_iterativa, '');
          $total_monto_gastado += $cant_monto_gastado;
          $val_monto_utilidad   = $val_monto_valorizado - $cant_monto_gastado;

          array_push($monto_programado, round($val_monto_programado, 2));          
          array_push($monto_valorizado, round($val_monto_valorizado, 2));          
          array_push($monto_gastado,    (empty($cant_monto_gastado) ? 0 :  round($cant_monto_gastado, 2)) );
          array_push($monto_utilidad,   round($val_monto_utilidad, 2));

          $acumulado_monto_programado += $val_monto_programado;
          $acumulado_monto_valorizado += $val_monto_valorizado;
          $acumulado_monto_gastado    += $cant_monto_gastado;
          $acumulado_monto_utilidad   += $val_monto_utilidad;

          array_push($monto_acumulado_programado, round($acumulado_monto_programado, 2));        
          array_push($monto_acumulado_valorizado, round($acumulado_monto_valorizado, 2));
          array_push($monto_acumulado_gastado,    round($acumulado_monto_gastado, 2));
          array_push($monto_acumulado_utilidad,   round($acumulado_monto_utilidad, 2));

          //  ---------------------------------------------- modulos ----------------------------------------------

          // compra_insumos
          $cant_monto_compra_insumos     = suma_totales_compra_insumos($id_proyecto, $fecha_iterativa, '');
          $total_monto_compra_insumos   += $cant_monto_compra_insumos;
          $val_utilidad_compra_insumos   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_compra_insumos )/$monto_valorizacion_gastado)  ;
          $total_utilidad_compra_insumos+= $val_utilidad_compra_insumos;
          array_push($monto_acumulado_compra_insumos,   round($total_monto_compra_insumos,2) );
          array_push($utilidad_acumulado_compra_insumos,   round($total_utilidad_compra_insumos,2) ); 
          array_push($monto_compra_insumos,   round($cant_monto_compra_insumos,2) );
          array_push($utilidad_compra_insumos,   round($val_utilidad_compra_insumos,2) );  
          $tabla_compra_insumos[]= array(
            "modulo"=>'Compra de insumos',"val"=>format_d_m_a($fecha_iterativa), "gasto"=>$cant_monto_compra_insumos, "utilidad"=>$val_utilidad_compra_insumos, "ver_mas"=>'compra_insumos.php',
          );  
          // maquina_y_equipo
          $cant_monto_maquina_y_equipo     = suma_totales_maquina_y_equipo($id_proyecto, $fecha_iterativa, '');
          $total_monto_maquina_y_equipo   += $cant_monto_maquina_y_equipo;
          $val_utilidad_maquina_y_equipo   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_maquina_y_equipo)/$monto_valorizacion_gastado);
          $total_utilidad_maquina_y_equipo+= $val_utilidad_maquina_y_equipo;
          array_push($monto_acumulado_maquina_y_equipo,   round($total_monto_maquina_y_equipo,2) );
          array_push($utilidad_acumulado_maquina_y_equipo,   round($total_utilidad_maquina_y_equipo,2) );
          array_push($monto_maquina_y_equipo,   round($cant_monto_maquina_y_equipo,2) );
          array_push($utilidad_maquina_y_equipo,   round($val_utilidad_maquina_y_equipo,2) );
          $tabla_maquina_y_equipo[]= array(
            "modulo"=>'Maquinas y Equipos',"val"=>format_d_m_a($fecha_iterativa), "gasto"=>$cant_monto_maquina_y_equipo, "utilidad"=>$val_utilidad_maquina_y_equipo, "ver_mas"=>'servicio_maquina.php',
          );
          // subcontrato
          $cant_monto_subcontrato     = suma_totales_subcontrato($id_proyecto, $fecha_iterativa, '');
          $total_monto_subcontrato   += $cant_monto_subcontrato;
          $val_utilidad_subcontrato   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_subcontrato)/$monto_valorizacion_gastado);
          $total_utilidad_subcontrato+= $val_utilidad_subcontrato;
          array_push($monto_acumulado_subcontrato,   round($total_monto_subcontrato,2) );
          array_push($utilidad_acumulado_subcontrato,   round($total_utilidad_subcontrato,2) );
          array_push($monto_subcontrato,   round($cant_monto_subcontrato,2) );
          array_push($utilidad_subcontrato,   round($val_utilidad_subcontrato,2) );
          $tabla_subcontrato[]= array(
            "modulo"=>'Subcontrato',"val"=>format_d_m_a($fecha_iterativa), "gasto"=>$cant_monto_subcontrato, "utilidad"=>$val_utilidad_subcontrato, "ver_mas"=>'sub_contrato.php',
          );
          // planilla_seguro
          $cant_monto_planilla_seguro     = suma_totales_planilla_seguro($id_proyecto, $fecha_iterativa, '');
          $total_monto_planilla_seguro   += $cant_monto_planilla_seguro;
          $val_utilidad_planilla_seguro   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_planilla_seguro)/$monto_valorizacion_gastado);
          $total_utilidad_planilla_seguro+= $val_utilidad_planilla_seguro;
          array_push($monto_acumulado_planilla_seguro,   round($total_monto_planilla_seguro,2) );
          array_push($utilidad_acumulado_planilla_seguro,   round($total_utilidad_planilla_seguro,2) );
          array_push($monto_planilla_seguro,   round($cant_monto_planilla_seguro,2) );
          array_push($utilidad_planilla_seguro,   round($val_utilidad_planilla_seguro,2) );
          $tabla_planilla_seguro[]= array(
            "modulo"=>'Planilla Seguro',"val"=>format_d_m_a($fecha_iterativa), "gasto"=>$cant_monto_planilla_seguro, "utilidad"=>$val_utilidad_planilla_seguro, "ver_mas"=>'planillas_seguros.php',
          );
          // otro_gasto
          $cant_monto_otro_gasto     = suma_totales_otro_gasto($id_proyecto, $fecha_iterativa, '');
          $total_monto_otro_gasto   += $cant_monto_otro_gasto;
          $val_utilidad_otro_gasto   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_otro_gasto)/$monto_valorizacion_gastado);
          $total_utilidad_otro_gasto+= $val_utilidad_otro_gasto;
          array_push($monto_acumulado_otro_gasto,   round($total_monto_otro_gasto,2) );
          array_push($utilidad_acumulado_otro_gasto,   round($total_utilidad_otro_gasto,2) );
          array_push($monto_otro_gasto,   round($cant_monto_otro_gasto,2) );
          array_push($utilidad_otro_gasto,   round($val_utilidad_otro_gasto,2) );
          $tabla_otro_gasto[]= array(
            "modulo"=>'Otro Gasto',"val"=>format_d_m_a($fecha_iterativa), "gasto"=>$cant_monto_otro_gasto, "utilidad"=>$val_utilidad_otro_gasto, "ver_mas"=>'otro_gasto.php',
          );
          // transporte
          $cant_monto_transporte     = suma_totales_transporte($id_proyecto, $fecha_iterativa, '');
          $total_monto_transporte   += $cant_monto_transporte;
          $val_utilidad_transporte   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_transporte)/$monto_valorizacion_gastado);
          $total_utilidad_transporte+= $val_utilidad_transporte;
          array_push($monto_acumulado_transporte,   round($total_monto_transporte,2) );
          array_push($utilidad_acumulado_transporte,   round($total_utilidad_transporte,2) );
          array_push($monto_transporte,   round($cant_monto_transporte,2) );
          array_push($utilidad_transporte,   round($val_utilidad_transporte,2) );
          $tabla_transporte[]= array(
            "modulo"=>'Transporte',"val"=>format_d_m_a($fecha_iterativa), "gasto"=>$cant_monto_transporte, "utilidad"=>$val_utilidad_transporte, "ver_mas"=>'transporte.php',
          );
          // hospedaje
          $cant_monto_hospedaje     = suma_totales_hospedaje($id_proyecto, $fecha_iterativa, '');
          $total_monto_hospedaje   += $cant_monto_hospedaje;
          $val_utilidad_hospedaje   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_hospedaje)/$monto_valorizacion_gastado);
          $total_utilidad_hospedaje+= $val_utilidad_hospedaje;
          array_push($monto_acumulado_hospedaje,   round($total_monto_hospedaje,2) );
          array_push($utilidad_acumulado_hospedaje,   round($total_utilidad_hospedaje,2) );
          array_push($monto_hospedaje,   round($cant_monto_hospedaje,2) );
          array_push($utilidad_hospedaje,   round($val_utilidad_hospedaje,2) );
          $tabla_hospedaje[]= array(
            "modulo"=>'Hospedaje',"val"=>format_d_m_a($fecha_iterativa), "gasto"=>$cant_monto_hospedaje, "utilidad"=>$val_utilidad_hospedaje, "ver_mas"=>'hospedaje.php',
          );
          // pension
          $cant_monto_pension     = suma_totales_pension($id_proyecto, $fecha_iterativa, '');
          $total_monto_pension   += $cant_monto_pension;
          $val_utilidad_pension   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_pension)/$monto_valorizacion_gastado);
          $total_utilidad_pension+= $val_utilidad_pension;
          array_push($monto_acumulado_pension,   round($total_monto_pension,2) );
          array_push($utilidad_acumulado_pension,   round($total_utilidad_pension,2) );
          array_push($monto_pension,   round($cant_monto_pension,2) );
          array_push($utilidad_pension,   round($val_utilidad_pension,2) );
          $tabla_pension[]= array(
            "modulo"=>'Pension',"val"=>format_d_m_a($fecha_iterativa), "gasto"=>$cant_monto_pension, "utilidad"=>$val_utilidad_pension, "ver_mas"=>'pension.php',
          );
          // breack
          $cant_monto_breack     = suma_totales_breack($id_proyecto, $fecha_iterativa, '');
          $total_monto_breack   += $cant_monto_breack;
          $val_utilidad_breack   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_breack)/$monto_valorizacion_gastado);
          $total_utilidad_breack+= $val_utilidad_breack;
          array_push($monto_acumulado_breack,   round($total_monto_breack,2) );
          array_push($utilidad_acumulado_breack,   round($total_utilidad_breack,2) );
          array_push($monto_breack,   round($cant_monto_breack,2) );
          array_push($utilidad_breack,   round($val_utilidad_breack,2) );
          $tabla_breack[]= array(
            "modulo"=>'Breack',"val"=>format_d_m_a($fecha_iterativa), "gasto"=>$cant_monto_breack, "utilidad"=>$val_utilidad_breack, "ver_mas"=>'break.php',
          );
          // comida_extra
          $cant_monto_comida_extra     = suma_totales_comida_extra($id_proyecto, $fecha_iterativa, '');
          $total_monto_comida_extra   += $cant_monto_comida_extra;
          $val_utilidad_comida_extra   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_comida_extra)/$monto_valorizacion_gastado);
          $total_utilidad_comida_extra+= $val_utilidad_comida_extra;
          array_push($monto_acumulado_comida_extra,   round($total_monto_comida_extra,2) );
          array_push($utilidad_acumulado_comida_extra,   round($total_utilidad_comida_extra,2) );
          array_push($monto_comida_extra,   round($cant_monto_comida_extra,2) );
          array_push($utilidad_comida_extra,   round($val_utilidad_comida_extra,2) );
          $tabla_comida_extra[]= array(
            "modulo"=>'Comida Extra',"val"=>format_d_m_a($fecha_iterativa), "gasto"=>$cant_monto_comida_extra, "utilidad"=>$val_utilidad_comida_extra, "ver_mas"=>'comidas_extras.php',
          );
          // pago_administrador
          $cant_monto_pago_administrador     = suma_totales_pago_administrador($id_proyecto, $fecha_iterativa, '');
          $total_monto_pago_administrador   += $cant_monto_pago_administrador;
          $val_utilidad_pago_administrador   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_pago_administrador)/$monto_valorizacion_gastado);
          $total_utilidad_pago_administrador+= $val_utilidad_pago_administrador;
          array_push($monto_acumulado_pago_administrador,   round($total_monto_pago_administrador,2) );
          array_push($utilidad_acumulado_pago_administrador,   round($total_utilidad_pago_administrador,2) );
          array_push($monto_pago_administrador,   round($cant_monto_pago_administrador,2) );
          array_push($utilidad_pago_administrador,   round($val_utilidad_pago_administrador,2) );
          $tabla_pago_administrador[]= array(
            "modulo"=>'Pago Administrador',"val"=>'Val'.$cont, "gasto"=>$cant_monto_pago_administrador, "utilidad"=>$val_utilidad_pago_administrador, "ver_mas"=>'pago_administrador.php',
          );
          // pago_obrero
          $cant_monto_pago_obrero     = suma_totales_pago_obrero($id_proyecto, $fecha_iterativa, '');
          $total_monto_pago_obrero   += $cant_monto_pago_obrero;
          $val_utilidad_pago_obrero   = ($monto_valorizacion_gastado==0 ? 0 : ($monto_valorizacion_utilidad * $cant_monto_pago_obrero)/$monto_valorizacion_gastado);
          $total_utilidad_pago_obrero+= $val_utilidad_pago_obrero;
          array_push($monto_acumulado_pago_obrero,   round($total_monto_pago_obrero,2) );
          array_push($utilidad_acumulado_pago_obrero,   round($total_utilidad_pago_obrero,2) );
          array_push($monto_pago_obrero,   round($cant_monto_pago_obrero,2) );
          array_push($utilidad_pago_obrero,   round($val_utilidad_pago_obrero,2) );
          $tabla_pago_obrero[]= array(
            "modulo"=>'Pago Obrero',"val"=>'Val'.$cont, "gasto"=>$cant_monto_pago_obrero, "utilidad"=>$val_utilidad_pago_obrero, "ver_mas"=>'pago_obrero.php',
          );
          $cont++;
        } else {
          break;
        } 
        $fecha_iterativa = sumar_dias( 1, $fecha_iterativa );       
      }
    }   
    
    // compra_insumos
    $tabla_resumen[]= array(
      "modulo"=>'Compra de insumos', "gasto"=>round($total_monto_compra_insumos,2), "utilidad"=>round($total_utilidad_compra_insumos,2), "ver_mas"=>'compra_insumos.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_compra_insumos,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_compra_insumos,2) );  
    // maquina_y_equipo
    $tabla_resumen[]= array(
      "modulo"=>'Maquinas y Equipos', "gasto"=>$total_monto_maquina_y_equipo, "utilidad"=>$total_utilidad_maquina_y_equipo, "ver_mas"=>'servicio_maquina.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_maquina_y_equipo,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_maquina_y_equipo,2) );  
    // subcontrato
    $tabla_resumen[]= array(
      "modulo"=>'Subcontrato', "gasto"=>$total_monto_subcontrato, "utilidad"=>$total_utilidad_subcontrato, "ver_mas"=>'sub_contrato.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_subcontrato,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_subcontrato,2) );  
    // planilla_seguro
    $tabla_resumen[]= array(
      "modulo"=>'Planilla Seguro', "gasto"=>$total_monto_planilla_seguro, "utilidad"=>$total_utilidad_planilla_seguro, "ver_mas"=>'planillas_seguros.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_planilla_seguro,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_planilla_seguro,2) ); 
    // otro_gasto
    $tabla_resumen[]= array(
      "modulo"=>'Otro Gasto', "gasto"=>$total_monto_otro_gasto, "utilidad"=>$total_utilidad_otro_gasto, "ver_mas"=>'otro_gasto.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_otro_gasto,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_otro_gasto,2) );
    // transporte
    $tabla_resumen[]= array(
      "modulo"=>'Transporte', "gasto"=>$total_monto_transporte, "utilidad"=>$total_utilidad_transporte, "ver_mas"=>'transporte.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_transporte,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_transporte,2) );
    // hospedaje
    $tabla_resumen[]= array(
      "modulo"=>'Hospedaje', "gasto"=>$total_monto_hospedaje, "utilidad"=>$total_utilidad_hospedaje, "ver_mas"=>'hospedaje.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_hospedaje,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_hospedaje,2) );
    // pension
    $tabla_resumen[]= array(
      "modulo"=>'Pension', "gasto"=>$total_monto_pension, "utilidad"=>$total_utilidad_pension, "ver_mas"=>'pension.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_pension,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_pension,2) );
    // breack
    $tabla_resumen[]= array(
      "modulo"=>'Breack', "gasto"=>$total_monto_breack, "utilidad"=>$total_utilidad_breack, "ver_mas"=>'break.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_breack,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_breack,2) );
    // comida_extra
    $tabla_resumen[]= array(
      "modulo"=>'Comida Extra', "gasto"=>$total_monto_comida_extra, "utilidad"=>$total_utilidad_comida_extra, "ver_mas"=>'comidas_extras.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_comida_extra,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_comida_extra,2) );
    // pago_administrador
    $tabla_resumen[]= array(
      "modulo"=>'Pago Administrador', "gasto"=>$total_monto_pago_administrador, "utilidad"=>$total_utilidad_pago_administrador, "ver_mas"=>'comidas_extras.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_pago_administrador,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_pago_administrador,2) );
    // pago_obrero
    $tabla_resumen[]= array(
      "modulo"=>'Pago Obrero', "gasto"=>$total_monto_pago_obrero, "utilidad"=>$total_utilidad_pago_obrero, "ver_mas"=>'comidas_extras.php',
    );
    array_push($monto_resumen_modulos,   round($total_monto_pago_obrero,2) );
    array_push($utilidad_resumen_modulos,   round($total_utilidad_pago_obrero,2) );
    
    return $retorno = [
      'status'=> true, 'message' => 'Salió todo ok,', 
      'data' => [
        'monto_programado'=>$monto_programado, 
        'monto_valorizado'=>$monto_valorizado, 
        'monto_gastado'   =>$monto_gastado, 
        'monto_utilidad'  => $monto_utilidad,

        'monto_acumulado_programado'=>$monto_acumulado_programado, 
        'monto_acumulado_valorizado'=>$monto_acumulado_valorizado, 
        'monto_acumulado_gastado'   =>$monto_acumulado_gastado,
        'monto_acumulado_utilidad'  =>$monto_acumulado_utilidad,
        
        'total_monto_programado'=>$total_monto_programado,
        'total_monto_valorizado'=>$total_monto_valorizado,
        'total_monto_gastado'   =>$total_monto_gastado,
        'total_utilidad'        =>$total_monto_valorizado - $total_monto_gastado,
        // compra_insumos
        'monto_acumulado_compra_insumos'  => $monto_acumulado_compra_insumos,
        'utilidad_acumulado_compra_insumos'=> $utilidad_acumulado_compra_insumos,
        'monto_compra_insumos'            => $monto_compra_insumos,
        'utilidad_compra_insumos'         => $utilidad_compra_insumos,
        'total_monto_compra_insumos'      =>$total_monto_compra_insumos,
        'total_utilidad_compra_insumos'   =>$total_utilidad_compra_insumos,
        'tabla_compra_insumos'            =>$tabla_compra_insumos,
        // maquina_y_equipo
        'monto_acumulado_maquina_y_equipo'=> $monto_acumulado_maquina_y_equipo,
        'utilidad_acumulado_maquina_y_equipo' => $utilidad_acumulado_maquina_y_equipo,
        'monto_maquina_y_equipo'          => $monto_maquina_y_equipo,
        'utilidad_maquina_y_equipo'       => $utilidad_maquina_y_equipo,
        'total_monto_maquina_y_equipo'    =>$total_monto_maquina_y_equipo,
        'total_utilidad_maquina_y_equipo' =>$total_utilidad_maquina_y_equipo,
        'tabla_maquina_y_equipo'          =>$tabla_maquina_y_equipo,
        // subcontrato
        'monto_acumulado_subcontrato'   => $monto_acumulado_subcontrato,
        'utilidad_acumulado_subcontrato'=> $utilidad_acumulado_subcontrato,
        'monto_subcontrato'             => $monto_subcontrato,
        'utilidad_subcontrato'          => $utilidad_subcontrato,
        'total_monto_subcontrato'       =>$total_monto_subcontrato,
        'total_utilidad_subcontrato'    =>$total_utilidad_subcontrato,
        'tabla_subcontrato'             =>$tabla_subcontrato,
        // planilla_seguro
        'monto_acumulado_planilla_seguro'=> $monto_acumulado_planilla_seguro,
        'utilidad_acumulado_planilla_seguro' => $utilidad_acumulado_planilla_seguro,
        'monto_planilla_seguro'         => $monto_planilla_seguro,
        'utilidad_planilla_seguro'      => $utilidad_planilla_seguro,
        'total_monto_planilla_seguro'   =>$total_monto_planilla_seguro,
        'total_utilidad_planilla_seguro'=>$total_utilidad_planilla_seguro,
        'tabla_planilla_seguro'         =>$tabla_planilla_seguro,
        // otro_gasto
        'monto_acumulado_otro_gasto'    => $monto_acumulado_otro_gasto,
        'utilidad_acumulado_otro_gasto' => $utilidad_acumulado_otro_gasto,
        'monto_otro_gasto'              => $monto_otro_gasto,
        'utilidad_otro_gasto'           => $utilidad_otro_gasto,
        'total_monto_otro_gasto'        =>$total_monto_otro_gasto,
        'total_utilidad_otro_gasto'     =>$total_utilidad_otro_gasto,
        'tabla_otro_gasto'              =>$tabla_otro_gasto,
        // transporte
        'monto_acumulado_transporte'    => $monto_acumulado_transporte,
        'utilidad_acumulado_transporte' => $utilidad_acumulado_transporte,
        'monto_transporte'              => $monto_transporte,
        'utilidad_transporte'           => $utilidad_transporte,
        'total_monto_transporte'        =>$total_monto_transporte,
        'total_utilidad_transporte'     =>$total_utilidad_transporte,
        'tabla_transporte'              =>$tabla_transporte,
        // hospedaje
        'monto_acumulado_hospedaje'   => $monto_acumulado_hospedaje,
        'utilidad_acumulado_hospedaje'=> $utilidad_acumulado_hospedaje,
        'monto_hospedaje'             => $monto_hospedaje,
        'utilidad_hospedaje'          => $utilidad_hospedaje,
        'total_monto_hospedaje'       =>$total_monto_hospedaje,
        'total_utilidad_hospedaje'    =>$total_utilidad_hospedaje,
        'tabla_hospedaje'             =>$tabla_hospedaje,
        // pension
        'monto_acumulado_pension'   => $monto_acumulado_pension,
        'utilidad_acumulado_pension'=> $utilidad_acumulado_pension,
        'monto_pension'             => $monto_pension,
        'utilidad_pension'          => $utilidad_pension,
        'total_monto_pension'       =>$total_monto_pension,
        'total_utilidad_pension'    =>$total_utilidad_pension,
        'tabla_pension'            =>$tabla_pension,
        // breack
        'monto_acumulado_breack'    => $monto_acumulado_breack,
        'utilidad_acumulado_breack' => $utilidad_acumulado_breack,
        'monto_breack'              => $monto_breack,
        'utilidad_breack'           => $utilidad_breack,
        'total_monto_breack'        =>$total_monto_breack,
        'total_utilidad_breack'     =>$total_utilidad_breack,
        'tabla_breack'              =>$tabla_breack,
        // comida_extra
        'monto_acumulado_comida_extra'    => $monto_acumulado_comida_extra,
        'utilidad_acumulado_comida_extra' => $utilidad_acumulado_comida_extra,
        'monto_comida_extra'              => $monto_comida_extra,
        'utilidad_comida_extra'           => $utilidad_comida_extra,
        'total_monto_comida_extra'        =>$total_monto_comida_extra,
        'total_utilidad_comida_extra'     =>$total_utilidad_comida_extra,
        'tabla_comida_extra'              =>$tabla_comida_extra,
        // pago_administrador
        'monto_acumulado_pago_administrador'    => $monto_acumulado_pago_administrador,
        'utilidad_acumulado_pago_administrador' => $utilidad_acumulado_pago_administrador,
        'monto_pago_administrador'              => $monto_pago_administrador,
        'utilidad_pago_administrador'           => $utilidad_pago_administrador,
        'total_monto_pago_administrador'        =>$total_monto_pago_administrador,
        'total_utilidad_pago_administrador'     =>$total_utilidad_pago_administrador,
        'tabla_pago_administrador'              =>$tabla_pago_administrador,
        // pago_obrero
        'monto_acumulado_pago_obrero'    => $monto_acumulado_pago_obrero,
        'utilidad_acumulado_pago_obrero' => $utilidad_acumulado_pago_obrero,
        'monto_pago_obrero'              => $monto_pago_obrero,
        'utilidad_pago_obrero'           => $utilidad_pago_obrero,
        'total_monto_pago_obrero'        =>$total_monto_pago_obrero,
        'total_utilidad_pago_obrero'     =>$total_utilidad_pago_obrero,
        'tabla_pago_obrero'              =>$tabla_pago_obrero,
        // resumen_modulos
        'tabla_resumen_modulos'=>$tabla_resumen,
        'monto_resumen_modulos'=>$monto_resumen_modulos,
        'utilidad_resumen_modulos'=>$utilidad_resumen_modulos,
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

// SUMAS DE MODULOS INDIVIDUALES 

function suma_totales_compra_insumos($idproyecto, $fecha_1, $fecha_2) {
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND cpp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND cpp.fecha_compra = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND cpp.fecha_compra = '$fecha_2'";          
  }    

  $sql = "SELECT SUM(cpp.total) AS total, SUM(cpp.subtotal) AS subtotal, SUM(cpp.igv) AS igv
  FROM compra_por_proyecto AS cpp, proveedor p
  WHERE cpp.idproveedor = p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1'  AND  cpp.idproyecto = $idproyecto $filtro_fecha ;";
  $compra = ejecutarConsultaSimpleFila($sql);

  if ($compra['status'] == false) { return $compra; }

  $total    = (empty($compra['data'])) ? 0 : ( empty($compra['data']['total']) ? 0 : floatval($compra['data']['total']) );
  $subtotal = (empty($compra['data'])) ? 0 : ( empty($compra['data']['subtotal']) ? 0 : floatval($compra['data']['subtotal']) );
  $igv      = (empty($compra['data'])) ? 0 : ( empty($compra['data']['igv']) ? 0 : floatval($compra['data']['igv']) );

  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}

function suma_totales_compras_activo_fijo($idproyecto, $fecha_1, $fecha_2) {
  # code...
}

function suma_totales_maquina_y_equipo($idproyecto, $fecha_1, $fecha_2) {
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND f.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND f.fecha_emision = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND f.fecha_emision = '$fecha_2'";
  }    

  $sql2 = "SELECT SUM(f.monto) AS total , SUM(f.subtotal) AS subtotal, SUM(f.igv) AS igv
  FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
  WHERE f.idmaquinaria=mq.idmaquinaria AND mq.idproveedor=prov.idproveedor AND f.idproyecto=p.idproyecto 
  AND f.estado = '1' AND f.estado_delete = '1'  AND f.idproyecto = $idproyecto $filtro_fecha;";
  $maquinaria = ejecutarConsultaSimpleFila($sql2);

  if ($maquinaria['status'] == false) { return $maquinaria; } 

  $total    = (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['total']) ? 0 : floatval($maquinaria['data']['total']) );
  $subtotal = (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['subtotal']) ? 0 : floatval($maquinaria['data']['subtotal']) );
  $igv      = (empty($maquinaria['data'])) ? 0 : ( empty($maquinaria['data']['igv']) ? 0 : floatval($maquinaria['data']['igv']) );

  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}

function suma_totales_subcontrato($idproyecto, $fecha_1, $fecha_2) {
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND s.fecha_subcontrato BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_2'";
  }    

  $sql3 = "SELECT SUM(s.subtotal) as subtotal, SUM(s.igv) as igv, SUM(s.costo_parcial) as total
  FROM subcontrato AS s, proveedor as p
  WHERE s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1'  AND  idproyecto = $idproyecto $filtro_fecha;";
  $otro_gasto = ejecutarConsultaSimpleFila($sql3);

  if ($otro_gasto['status'] == false) { return $otro_gasto; } 
  
  $total    = (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
  $subtotal = (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
  $igv      = (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );

  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}

function suma_totales_planilla_seguro($idproyecto, $fecha_1, $fecha_2) {

  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND ps.fecha_p_s BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND ps.fecha_p_s = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND ps.fecha_p_s = '$fecha_2'";
  }    

  $sql3 = "SELECT SUM(ps.subtotal) AS subtotal, SUM(ps.igv) AS igv, SUM(ps.costo_parcial) AS total
  FROM planilla_seguro as ps, proyecto as p
  WHERE ps.idproyecto = p.idproyecto and ps.estado ='1' and ps.estado_delete = '1' 
    AND  ps.idproyecto = $idproyecto $filtro_fecha;";
  $otro_gasto = ejecutarConsultaSimpleFila($sql3);

  if ($otro_gasto['status'] == false) { return $otro_gasto; } 
  
  $total    = (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
  $subtotal = (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
  $igv      = (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );
  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}

function suma_totales_otro_gasto($idproyecto, $fecha_1, $fecha_2) {

  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fecha_g BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND fecha_g = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND fecha_g = '$fecha_2'";
  }    

  $sql3 = "SELECT SUM(costo_parcial) as total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
  FROM otro_gasto  
  WHERE estado = '1' AND estado_delete = '1'  AND  idproyecto = $idproyecto $filtro_fecha;";
  $otro_gasto = ejecutarConsultaSimpleFila($sql3);

  if ($otro_gasto['status'] == false) { return $otro_gasto; } 
  
  $total    = (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['total']) ? 0 : floatval($otro_gasto['data']['total']) );
  $subtotal = (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['subtotal']) ? 0 : floatval($otro_gasto['data']['subtotal']) );
  $igv      = (empty($otro_gasto['data'])) ? 0 : ( empty($otro_gasto['data']['igv']) ? 0 : floatval($otro_gasto['data']['igv']) );

  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}

function suma_totales_transporte($idproyecto, $fecha_1, $fecha_2) {

  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND t.fecha_viaje BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND t.fecha_viaje = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND t.fecha_viaje = '$fecha_2'";
  }    

  $sql4 = "SELECT SUM(t.precio_parcial) AS total, SUM(t.subtotal) AS subtotal, SUM(t.igv) AS igv
  FROM transporte AS t, proveedor AS p
  WHERE t.idproveedor = p.idproveedor  AND t.estado = '1' AND t.estado_delete = '1' AND t.idproyecto = $idproyecto  $filtro_fecha;";
  $transporte = ejecutarConsultaSimpleFila($sql4);

  if ($transporte['status'] == false) { return $transporte; }
  
  $total    = (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['total']) ? 0 : floatval($transporte['data']['total']) );
  $subtotal = (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['subtotal']) ? 0 : floatval($transporte['data']['subtotal']) );
  $igv      = (empty($transporte['data'])) ? 0 : ( empty($transporte['data']['igv']) ? 0 : floatval($transporte['data']['igv']) );

  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}

function suma_totales_hospedaje($idproyecto, $fecha_1, $fecha_2) {

  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fecha_comprobante BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND fecha_comprobante = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND fecha_comprobante = '$fecha_2'";
  }    

  $sql5 = "SELECT SUM(precio_parcial) as total , SUM(subtotal) AS subtotal, SUM(igv) AS igv
  FROM hospedaje WHERE estado = '1' AND estado_delete = '1' AND idproyecto = $idproyecto  $filtro_fecha
  ORDER BY fecha_comprobante DESC;";
  $hospedaje = ejecutarConsultaSimpleFila($sql5);

  if ($hospedaje['status'] == false) { return $hospedaje; }
  
  $total    = (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['total']) ? 0 : floatval($hospedaje['data']['total']) );
  $subtotal = (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['subtotal']) ? 0 : floatval($hospedaje['data']['subtotal']) );
  $igv      = (empty($hospedaje['data'])) ? 0 : ( empty($hospedaje['data']['igv']) ? 0 : floatval($hospedaje['data']['igv']) );

  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}

function suma_totales_pension($idproyecto, $fecha_1, $fecha_2) {

  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND dp.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND dp.fecha_emision = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND dp.fecha_emision = '$fecha_2'";
  }    

  $sql6 = "SELECT SUM(dp.precio_parcial) AS total, SUM(dp.subtotal) AS subtotal, SUM(dp.igv) AS igv
  FROM detalle_pension as dp, pension as p, proveedor as prov
  WHERE dp.idpension = p.idpension AND prov.idproveedor = p.idproveedor  AND p.estado = '1' AND p.estado_delete = '1' AND  p.idproyecto = $idproyecto
  AND dp.estado = '1' AND dp.estado_delete = '1' $filtro_fecha ;";
  $factura_pension = ejecutarConsultaSimpleFila($sql6);

  if ($factura_pension['status'] == false) { return $factura_pension; }
  
  $total    = (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['total']) ? 0 : floatval($factura_pension['data']['total']) );
  $subtotal = (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['subtotal']) ? 0 : floatval($factura_pension['data']['subtotal']) );
  $igv      = (empty($factura_pension['data'])) ? 0 : ( empty($factura_pension['data']['igv']) ? 0 : floatval($factura_pension['data']['igv']) );

  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}

function suma_totales_breack($idproyecto, $fecha_1, $fecha_2) {

  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fb.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND fb.fecha_emision = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND fb.fecha_emision = '$fecha_2'";         
  }    

  $sql7 = "SELECT SUM(fb.monto) AS total, SUM(fb.subtotal) AS subtotal, SUM(fb.igv) AS igv
  FROM factura_break as fb, semana_break as sb
  WHERE  fb.idsemana_break = sb.idsemana_break AND fb.estado = '1' AND fb.estado_delete = '1' AND sb.estado = '1'  AND  sb.idproyecto = $idproyecto
  AND sb.estado_delete = '1' $filtro_fecha ;";
  $factura_break = ejecutarConsultaSimpleFila($sql7);

  if ($factura_break['status'] == false) { return $factura_break; }
  
  $total    = (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['total']) ? 0 : floatval($factura_break['data']['total']) );
  $subtotal = (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['subtotal']) ? 0 : floatval($factura_break['data']['subtotal']) );
  $igv      = (empty($factura_break['data'])) ? 0 : ( empty($factura_break['data']['igv']) ? 0 : floatval($factura_break['data']['igv']) );

  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}

function suma_totales_comida_extra($idproyecto, $fecha_1, $fecha_2) {

  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND fecha_comida BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND fecha_comida = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND fecha_comida = '$fecha_2'";      
  }    

  $sql8 = "SELECT SUM(costo_parcial) AS total, SUM(subtotal) AS subtotal, SUM(igv) AS igv
  FROM comida_extra
  WHERE  estado = '1' AND estado_delete = '1' AND  idproyecto = $idproyecto $filtro_fecha;";
  $comida_extra = ejecutarConsultaSimpleFila($sql8);

  if ($comida_extra['status'] == false) { return $comida_extra; }
  
  $total    = (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['total']) ? 0 : floatval($comida_extra['data']['total']) );
  $subtotal = (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['subtotal']) ? 0 : floatval($comida_extra['data']['subtotal']) );
  $igv      = (empty($comida_extra['data'])) ? 0 : ( empty($comida_extra['data']['igv']) ? 0 : floatval($comida_extra['data']['igv']) );

  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}

function suma_totales_otro_ingreso($idproyecto, $fecha_1, $fecha_2) {
  # code...
}

function suma_totales_otro_factura($idproyecto, $fecha_1, $fecha_2) {
  # code...
}

function suma_totales_pago_administrador($idproyecto, $fecha_1, $fecha_2) {
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND pxma.fecha_pago BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND pxma.fecha_pago = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND pxma.fecha_pago = '$fecha_2'";
  }   
  $sql11 = "SELECT SUM(pxma.monto) total, SUM(pxma.monto) AS subtotal
  FROM pagos_x_mes_administrador as pxma, fechas_mes_pagos_administrador as fmpa, trabajador_por_proyecto as tpp, trabajador t
  WHERE pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador AND fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto  AND tpp.idtrabajador = t.idtrabajador
  AND pxma.estado = '1' AND pxma.estado_delete = '1'  AND tpp.idproyecto = '$idproyecto' $filtro_fecha;";
  $pago_administrador = ejecutarConsultaSimpleFila($sql11);

  if ($pago_administrador['status'] == false) { return $pago_administrador; }
  
  $total    = (empty($pago_administrador['data'])) ? 0 : ( empty($pago_administrador['data']['total']) ? 0 : floatval($pago_administrador['data']['total']) );
  $subtotal = (empty($pago_administrador['data'])) ? 0 : ( empty($pago_administrador['data']['subtotal']) ? 0 : floatval($pago_administrador['data']['subtotal']) );
  $igv      = 0;

  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}

function suma_totales_pago_obrero($idproyecto, $fecha_1, $fecha_2) {
  $filtro_fecha = "";

  if ( !empty($fecha_1) && !empty($fecha_2) ) {
    $filtro_fecha = "AND pqso.fecha_pago BETWEEN '$fecha_1' AND '$fecha_2'";
  } else if (!empty($fecha_1)) {    
    $filtro_fecha = "AND pqso.fecha_pago = '$fecha_1'";
  }else if (!empty($fecha_2)) {      
    $filtro_fecha = "AND pqso.fecha_pago = '$fecha_2'";        
  }
  $sql12 = "SELECT SUM(pqso.monto_deposito) total, SUM(pqso.monto_deposito) AS subtotal
  FROM pagos_q_s_obrero as pqso, resumen_q_s_asistencia as rqsa, trabajador_por_proyecto as tpp, trabajador t
  WHERE pqso.idresumen_q_s_asistencia = rqsa.idresumen_q_s_asistencia AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idtrabajador = t.idtrabajador
  AND pqso.estado = '1' AND pqso.estado_delete = '1' AND tpp.idproyecto = '$idproyecto' $filtro_fecha;";
  $pago_obrero = ejecutarConsultaSimpleFila($sql12);

  if ($pago_obrero['status'] == false) { return $pago_obrero; }
  
  $total    = (empty($pago_obrero['data'])) ? 0 : ( empty($pago_obrero['data']['total']) ? 0 : floatval($pago_obrero['data']['total']) );
  $subtotal = (empty($pago_obrero['data'])) ? 0 : ( empty($pago_obrero['data']['subtotal']) ? 0 : floatval($pago_obrero['data']['subtotal']) );
  $igv      = 0;

  $data = array( "status"=> true, "message"=> 'todo oka', "data"=> [ "total" => $total, "subtotal" => $subtotal, "igv" => $igv, ] );
  return $total ;
}
    
?>
