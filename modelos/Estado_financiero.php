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

  // ══════════════════════════════════════ PROYECIONES ══════════════════════════════════════ 
  public function insertar_proyecciones($idproyecto_p, $fecha_p, $caja_p, $descripcion_p)  {
    $sql = "INSERT INTO proyeccion( idproyecto, fecha, caja, descripcion) 
    VALUES ('$idproyecto_p','$fecha_p','$caja_p','$descripcion_p');";
    return ejecutarConsulta($sql);     
  }

  //Implementamos un método para editar registros
  public function editar_proyecciones($idproyeccion_p, $idproyecto_p, $fecha_p, $caja_p, $descripcion_p) {
    $sql = "UPDATE proyeccion SET idproyecto='$idproyecto_p', fecha='$fecha_p', caja='$caja_p', descripcion='$descripcion_p' 
    WHERE idproyeccion ='$idproyeccion_p';";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function listar_fechas_proyeccion($idproyecto) {
    $sql = "SELECT * FROM proyeccion WHERE idproyecto ='$idproyecto' AND estado='1' AND estado_delete='1' ORDER BY fecha ASC ";
    return ejecutarConsultaArray($sql);
  }

  //Implementamos un método para activar categorías
  public function tbla_principal_fecha_proyeccion($idproyecto) {
    $sql = "SELECT * FROM proyeccion WHERE idproyecto ='$idproyecto' AND estado='1' AND estado_delete='1' ORDER BY fecha ASC ";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function mostrar_fecha_proyeccion($idproyecto) {
    $sql = "SELECT * FROM proyeccion WHERE idproyeccion ='$idproyecto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar_fechas_proyeccion($idproyeccion) {
    $sql = "UPDATE proyeccion SET estado_delete='0' WHERE idproyeccion ='$idproyeccion'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar_fechas_proyeccion($idproyeccion) {
    $sql = "UPDATE proyeccion SET estado='0' WHERE idproyeccion ='$idproyeccion'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar($idproyeccion) {
    $sql = "UPDATE proyeccion SET estado='1' WHERE idproyeccion ='$idproyeccion'";
    return ejecutarConsulta($sql);
  }

  // ══════════════════════════════════════ D E T A L L E   P R O Y E C I O N E S ══════════════════════════════════════ 
  //Implementamos un método para activar categorías
  public function tbla_principal_detalle_proyeccion($id_proyecto, $idproyeccion) {

    $data = []; $data_detalle = [];

    $sql_1 = "SELECT * FROM proyeccion WHERE idproyeccion ='$idproyeccion'";
    $proyeccion = ejecutarConsultaSimpleFila($sql_1);
    if ($proyeccion['status'] == false) { return $proyeccion; }

    $sql_2 = "SELECT dp.iddetalle_proyeccion, dp.idproyeccion, dp.nombre, dp.monto
    FROM detalle_proyeccion as dp , proyeccion as p
    WHERE dp.idproyeccion = p.idproyeccion AND dp.idproyeccion = '$idproyeccion' AND dp.estado ='1' AND dp.estado_delete ='1';";
    $detalle = ejecutarConsultaArray($sql_2);
    if ($detalle['status'] == false) { return $detalle; }

    foreach ($detalle['data'] as $key => $value) {
      $id = $value['iddetalle_proyeccion'];
      $sql_3 = "SELECT idsub_detalle_proyeccion, iddetalle_proyeccion, nombre, monto FROM sub_detalle_proyeccion WHERE iddetalle_proyeccion = '$id' AND estado ='1' AND estado_delete ='1'";
      $sub_detalle = ejecutarConsultaArray($sql_3);
      if ($sub_detalle['status'] == false) { return $sub_detalle; }

      $data_detalle[] = array(
        'iddetalle_proyeccion'=> $value['iddetalle_proyeccion'],
        'idproyeccion'        => $value['idproyeccion'],
        'nombre_proyeccion'   => $value['nombre'],
        'monto'               =>  (empty($value['monto']) ? 0 :  floatval($value['monto']) ),
        'sub_detalle'         => $sub_detalle['data'],
      );
    }

    // data estado financiero
    $sql_4 = "SELECT idestado_financiero, caja, garantia FROM estado_financiero WHERE idproyecto= '$id_proyecto'";
    $data_caja = ejecutarConsultaSimpleFila($sql_4);
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
      'idproyeccion'              => $proyeccion['data']['idproyeccion'],
      'idproyecto'                => $proyeccion['data']['idproyecto'],      
      'fecha'                     => $proyeccion['data']['fecha'],
      'caja'                      => $caja,
      'descripcion'               => $proyeccion['data']['descripcion'],
      
      'detalle'                   => $data_detalle,

      'prestamo_y_credito'        => ($prestamo + $credito),
      'gasto_de_modulos'          => $gasto_de_modulos,
      'valorizacion_cobrada'      => $valorizacion_cobrada,
      'valorizacion_por_cobrada'  => $valorizacion_por_cobrada ,
      'garantia'                  => $garantia,
      'monto_de_obra'             => $monto_de_obra
    );

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data  ] ;

  }

  // ══════════════════════════════════════ S U B   D E T A L L E   P R O Y E C I O N E S ══════════════════════════════════════
  public function guardar_y_editar_detalle_proyecciones( $data_array )  {
    $sd_pry =''; $d_pry ='';
    $idproyeccion     = $data_array['idproyeccion'];
    $caja             = $data_array['caja'];
    $gasto_proyectado = $data_array['gasto_proyectado'];

    // actualizamos la tabla PROYECCION
    $sql_1 = "UPDATE proyeccion SET caja='$caja', total_gasto='$gasto_proyectado' WHERE idproyeccion ='$idproyeccion';";
    $pry = ejecutarConsulta($sql_1);   
    if ($pry['status'] == false) {  return $pry;  }

    // desactivamos TODOS los registros de: DETALLE PROYECCION
    $sql_0 = "UPDATE detalle_proyeccion SET estado='0' WHERE idproyeccion ='$idproyeccion';";
    $desactivar_d_pry = ejecutarConsulta($sql_0);  
    if ($desactivar_d_pry['status'] == false) {  return $desactivar_d_pry;  }

    foreach ($data_array['detalle'] as $key => $value_det) {

      $iddetalle_proyeccion = $value_det['iddetalle_proyeccion'];
      $nombre_det           = $value_det['nombre'];
      $total_det            = $value_det['total'];      

      // Agregamos o Editamos la tabla DETALLE PROYECCION
      if (empty($value_det['iddetalle_proyeccion'])) {
        $sql_2 = "INSERT INTO detalle_proyeccion( idproyeccion, nombre, monto) VALUES ('$idproyeccion', '$nombre_det', '$total_det');";
        $d_pry = ejecutarConsulta_retornarID($sql_2);  
        if ($d_pry['status'] == false) {  return $d_pry;  } 

        $id_detalle_proyeccion = $d_pry['data'];

        foreach ($value_det['subdetalle'] as $key => $value_sub_det) {
          $idsub_detalle_proyeccion = $value_sub_det['idsub_detalle_proyeccion'];
          $nombre_sub_det           = $value_sub_det['nombre'];
          $total_sub_det            = $value_sub_det['total'];
  
          // Agregamos o Editamos la tabla SUB-DETALLE PROYECCION
          if ( empty($value_sub_det['idsub_detalle_proyeccion']) ) {
            $sql_3 = "INSERT INTO sub_detalle_proyeccion( iddetalle_proyeccion, nombre, monto) VALUES ('$id_detalle_proyeccion','$nombre_sub_det','$total_sub_det');";
            $sd_pry = ejecutarConsulta($sql_3);
            if ($sd_pry['status'] == false) {  return $sd_pry;  } 
          } else {           
            return $retorno = ['status'=> 'error_ing_pool', 'user' => $_SESSION['nombre'], 'message' => 'Porfavor no modifique el codigo, sus datos personales como: IP, MAC sera registrados por su seguridad.', 'data' => [] ];
          }        
        }

      } else {
        // desactivamos TODOS los registros de: SUB-DETALLE PROYECCION
        $sql_0 = "UPDATE sub_detalle_proyeccion SET estado='0'  WHERE iddetalle_proyeccion ='$iddetalle_proyeccion';";
        $desactivar_sd_pry = ejecutarConsulta($sql_0);  
        if ($desactivar_sd_pry['status'] == false) {  return $desactivar_sd_pry;  }

        // actualizamos los detalles
        $sql_2 = "UPDATE detalle_proyeccion SET idproyeccion='$idproyeccion', nombre='$nombre_det', monto='$total_det', estado='1'
        WHERE iddetalle_proyeccion ='$iddetalle_proyeccion';";
        $d_pry = ejecutarConsulta($sql_2);
        if ($d_pry['status'] == false) {  return $d_pry;  } 

        foreach ($value_det['subdetalle'] as $key => $value_sub_det) {
          $idsub_detalle_proyeccion = $value_sub_det['idsub_detalle_proyeccion'];
          $nombre_sub_det           = $value_sub_det['nombre'];
          $total_sub_det            = $value_sub_det['total'];
  
          // Agregamos o Editamos la tabla SUB-DETALLE PROYECCION
          if ( empty($value_sub_det['idsub_detalle_proyeccion']) ) {
            $sql_3 = "INSERT INTO sub_detalle_proyeccion( iddetalle_proyeccion, nombre, monto) VALUES ('$iddetalle_proyeccion','$nombre_sub_det','$total_sub_det');";
            $sd_pry = ejecutarConsulta($sql_3);
            if ($sd_pry['status'] == false) {  return $sd_pry;  } 
          } else {
            $sql_3 = "UPDATE sub_detalle_proyeccion SET iddetalle_proyeccion='$iddetalle_proyeccion', nombre='$nombre_sub_det', monto='$total_sub_det', estado='1' 
            WHERE idsub_detalle_proyeccion ='$idsub_detalle_proyeccion';";
            $sd_pry = ejecutarConsulta($sql_3);
            if ($sd_pry['status'] == false) {  return $sd_pry;  } 
          }        
        }
      }  
      
       
    }  
    return  (empty($sd_pry) ? $d_pry : $sd_pry );  
  }

  # .class
}

// ══════════════════════════════════════ SUMAS ══════════════════════════════════════

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
