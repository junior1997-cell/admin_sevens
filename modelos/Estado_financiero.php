<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class EstadoFinanciero
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  // ══════════════════════════════════════ ESTADO FINANCIERO ══════════════════════════════════════

  //Implementamos un método para insertar registros
  public function insertar_estado_financiero($idproyecto, $caja, $garantia )  {
    $sql = "INSERT INTO estado_financiero( idproyecto, caja, garantia) VALUES ('$idproyecto', '$caja', '$garantia')";
    return ejecutarConsulta($sql);     
  }

  //Implementamos un método para editar registros
  public function editar_estado_financiero( $idestado_financiero, $idproyecto, $caja, $garantia) {
    $sql = "UPDATE estado_financiero SET idproyecto='$idproyecto', caja='$caja', garantia='$garantia'
    WHERE idestado_financiero='$idestado_financiero'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function estado_financiero($id_proyecto) {
    $data = Array();

    $sql_1 = "SELECT idestado_financiero, caja, garantia FROM estado_financiero WHERE idproyecto= '$id_proyecto'";
    $data_caja = ejecutarConsultaSimpleFila($sql_1);
    if ($data_caja['status'] == false) {  return $data_caja;  }
    $caja = (empty($data_caja['data']) ? 0 : (empty($data_caja['data']['caja']) ? 0 : floatval($data_caja['data']['caja']) ) );

    $prestamo = deuda_prestamo($id_proyecto) ;
    $credito =  deuda_credito($id_proyecto);

    $gasto_de_modulos = suma_totales_modulos($id_proyecto, '', '');

    $valorizacion_cobrada = valorizacion_cobrada($id_proyecto);
    $valorizacion_por_cobrada = valorizacion_por_cobrar($id_proyecto);

    $garantia = garantia($id_proyecto);

    $monto_de_obra = garantia_y_costo_proyecto($id_proyecto, 'costo');
     
    $data = array(
      'idestado_financiero'       => (empty($data_caja['data']) ? '' :  $data_caja['data']['idestado_financiero']  ),
      'caja'                      => $caja,
      'prestamo_y_credito'        => ($prestamo + $credito),
      'gasto_de_modulos'          => $gasto_de_modulos,
      'valorizacion_cobrada'      => $valorizacion_cobrada,
      'valorizacion_por_cobrada'  => $valorizacion_por_cobrada ,
      'garantia'                  => $garantia,
      'monto_de_obra'             => $monto_de_obra
    );
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
     
  }

  //Implementar un método para listar los registros
  public function tbla_principal() {
    $sql = "SELECT
			p.idproducto as idproducto,
			p.idunidad_medida as idunidad_medida,
			p.idcolor as idcolor,
			p.nombre as nombre,
			p.marca as marca,
			p.descripcion as descripcion,
			p.imagen as imagen,
			p.estado_igv as estado_igv,
			p.precio_unitario as precio_unitario,
			p.precio_igv as precio_igv,
			p.precio_sin_igv as precio_sin_igv,
			p.precio_total as precio_total,
			p.ficha_tecnica as ficha_tecnica,
			p.estado as estado,
			c.nombre_color as nombre_color,
			um.nombre_medida as nombre_medida
			FROM producto p, unidad_medida as um, color as c  
			WHERE um.idunidad_medida=p.idunidad_medida  AND c.idcolor=p.idcolor AND idcategoria_insumos_af = '1' 
			AND p.estado='1' AND p.estado_delete='1' ORDER BY p.nombre ASC";
    return ejecutarConsulta($sql);
  }

  // ══════════════════════════════════════ PROYECIONES ══════════════════════════════════════ 
  public function insertar_proyecciones($idproyecto_p, $fecha_p, $caja_p, $descripcion_p)  {
    $sql = "INSERT INTO proyeccion( idproyecto, fecha, caja, descripcion) 
    VALUES ('$idproyecto_p','$fecha_p','$caja_p','$descripcion_p')";
    return ejecutarConsulta($sql);     
  }

  //Implementamos un método para editar registros
  public function editar_proyecciones( $idestado_financiero, $idproyecto, $caja, $garantia) {
    $sql = "UPDATE estado_financiero SET idproyecto='$idproyecto', caja='$caja', garantia='$garantia'
    WHERE idestado_financiero='$idestado_financiero'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar($idproducto) {
    $sql = "UPDATE producto SET estado_delete='0' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idproducto) {
    $sql = "UPDATE producto SET estado='0' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar($idproducto) {
    $sql = "UPDATE producto SET estado='1' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }
}

function deuda_prestamo($id_proyecto){
  $sql_1 = "SELECT SUM(monto) AS gasto FROM prestamo 
  WHERE idproyecto = '$id_proyecto' AND estado ='1' AND estado_delete = '1';";
  $gasto = ejecutarConsultaSimpleFila($sql_1);
  if ($gasto['status'] == false) {  return $gasto;  }

  $gasto = (empty($gasto['data']) ? 0 : (empty($gasto['data']['gasto']) ? 0 : floatval($gasto['data']['gasto']) ) );

  $sql_2 = "SELECT SUM(pp.monto) AS depositos 
  FROM pago_prestamo as pp, prestamo as p  
  WHERE pp.idprestamo = p.idprestamo AND p.idproyecto ='$id_proyecto' and pp.estado = '1' AND pp.estado_delete ='1' 
  AND p.estado ='1' AND p.estado_delete='1';";
  $deposito = ejecutarConsultaSimpleFila($sql_2);
  if ($deposito['status'] == false) {  return $deposito;  }

  $deposito =(empty($deposito['data']) ? 0 : (empty($deposito['data']['depositos']) ? 0 : floatval($deposito['data']['depositos']) ) );

  $diferencia =  $gasto - $deposito;

  return $diferencia;
}

function deuda_credito($id_proyecto){
  $sql_1 = "SELECT SUM(monto) AS gasto FROM credito WHERE idproyecto ='$id_proyecto'AND estado ='1' AND estado_delete = '1';";
  $gasto = ejecutarConsultaSimpleFila($sql_1);
  if ($gasto['status'] == false) {  return $gasto;  }

  $gasto = (empty($gasto['data']) ? 0 : (empty($gasto['data']['gasto']) ? 0 : floatval($gasto['data']['gasto']) ) );

  $sql_2 = "SELECT SUM(pc.monto) AS deposito FROM pago_credito AS pc, credito as c 
  WHERE pc.idcredito = c.idcredito AND c.idproyecto ='$id_proyecto' and pc.estado = '1' AND pc.estado_delete ='1' 
  AND c.estado ='1' AND c.estado_delete='1';";
  $deposito = ejecutarConsultaSimpleFila($sql_2);
  if ($deposito['status'] == false) {  return $deposito;  }

  $deposito =(empty($deposito['data']) ? 0 : (empty($deposito['data']['deposito']) ? 0 : floatval($deposito['data']['deposito']) ) );

  $diferencia =  $gasto - $deposito;

  return $diferencia;
}

function garantia_y_costo_proyecto($id_proyecto, $tipo='garantia') {
  $sql_0 = "SELECT garantia, 	costo FROM proyecto WHERE idproyecto='$id_proyecto'";
  $proyecto = ejecutarConsultaSimpleFila($sql_0);
  if ($proyecto['status'] == false) {  return $proyecto;  }

  if ($tipo == 'garantia') {
    return $garantia = (empty($proyecto['data']) ? 0 : (empty($proyecto['data']['garantia']) ? 0 : floatval($proyecto['data']['garantia']) ) );
  }else if ($tipo == 'costo') {
    return $costo = (empty($proyecto['data']) ? 0 : (empty($proyecto['data']['costo']) ? 0 : floatval($proyecto['data']['costo']) ) );
  }else{
    return 0;
  }  
}

function valorizacion_cobrada($id_proyecto) {
   
  $garantia = garantia_y_costo_proyecto($id_proyecto, 'garantia');

  $sql_1 = "SELECT SUM(monto_programado) as monto_programado, SUM(monto_valorizado) as monto_valorizado
  FROM resumen_q_s_valorizacion WHERE idproyecto ='$id_proyecto';";
  $monto_valorizado = ejecutarConsultaSimpleFila($sql_1);
  if ($monto_valorizado['status'] == false) {  return $monto_valorizado;  }
  $monto_valorizado = (empty($monto_valorizado['data']) ? 0 : (empty($monto_valorizado['data']['monto_valorizado']) ? 0 : floatval($monto_valorizado['data']['monto_valorizado']) ) );

  $val_cobrado_95 = round(($monto_valorizado * (1 - $garantia)),2);

  $sql_2 = "SELECT COUNT(idresumen_q_s_valorizacion) as cant_val_cobrada
  FROM resumen_q_s_valorizacion 
  WHERE idproyecto ='$id_proyecto' and monto_valorizado  IS NOT NULL and monto_valorizado != 0 and monto_valorizado != '';";
  $cant_val_cobrada = ejecutarConsultaSimpleFila($sql_2);
  if ($cant_val_cobrada['status'] == false) {  return $cant_val_cobrada;  }
  $cant_val_cobrada = (empty($cant_val_cobrada['data']) ? 0 : (empty($cant_val_cobrada['data']['cant_val_cobrada']) ? 0 : floatval($cant_val_cobrada['data']['cant_val_cobrada']) ) );

  return $retorno = ['val_cobrada' => $val_cobrado_95, 'cant_val_cobrada' => $cant_val_cobrada ];
}

function valorizacion_por_cobrar($id_proyecto) {

  $garantia = garantia_y_costo_proyecto($id_proyecto, 'garantia');;

  $sql_1 = "SELECT SUM(monto_programado) as monto_programado, SUM(monto_valorizado) as monto_valorizado
  FROM resumen_q_s_valorizacion WHERE idproyecto ='$id_proyecto';";
  $montos = ejecutarConsultaSimpleFila($sql_1);
  if ($montos['status'] == false) {  return $montos;  }
  $monto_valorizado = (empty($montos['data']) ? 0 : (empty($montos['data']['monto_valorizado']) ? 0 : floatval($montos['data']['monto_valorizado']) ) );
  $monto_programado = (empty($montos['data']) ? 0 : (empty($montos['data']['monto_programado']) ? 0 : floatval($montos['data']['monto_programado']) ) );
  
  $val_95 = $monto_valorizado * (1 - $garantia);

  $val_por_cobrar =  round(($monto_programado - $val_95),2);

  $sql_2 = "SELECT COUNT(idresumen_q_s_valorizacion) as cantidad
  FROM resumen_q_s_valorizacion WHERE idproyecto ='$id_proyecto' and monto_valorizado  IS NULL or monto_valorizado = 0 or monto_valorizado = '';";
  $cant = ejecutarConsultaSimpleFila($sql_2);
  if ($cant['status'] == false) {  return $cant;  }
  $cant_val_por_cobrar = (empty($cant['data']) ? 0 : (empty($cant['data']['cantidad']) ? 0 : floatval($cant['data']['cantidad']) ) );

  return $retorno = ['val_por_cobrar' => $val_por_cobrar, 'cant_val_por_cobrar' => $cant_val_por_cobrar ];
}

function garantia($id_proyecto) {
   
  $garantia = garantia_y_costo_proyecto($id_proyecto, 'garantia');;

  $sql_1 = "SELECT SUM(monto_programado) as monto_programado, SUM(monto_valorizado) as monto_valorizado
  FROM resumen_q_s_valorizacion WHERE idproyecto ='$id_proyecto';";
  $monto_programado = ejecutarConsultaSimpleFila($sql_1);
  if ($monto_programado['status'] == false) {  return $monto_programado;  }
  $monto_programado = (empty($monto_programado['data']) ? 0 : (empty($monto_programado['data']['monto_programado']) ? 0 : floatval($monto_programado['data']['monto_programado']) ) );

  $garantia_cal = round(($monto_programado * $garantia), 2);

  return $garantia_cal;
}

?>
