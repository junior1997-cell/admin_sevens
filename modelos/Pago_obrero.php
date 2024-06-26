<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class PagoObrero
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //TABLA PRINCIPAL
  public function listar_tbla_principal($nube_idproyecto) {
    $data = [];

    $sql_1 = "SELECT sqa.ids_q_asistencia,rqs.numero_q_s, sqa.fecha_q_s_inicio, sqa.fecha_q_s_fin, (SUM( rqs.pago_quincenal_hn)+SUM( rqs.pago_quincenal_he)) as total_pago  
    FROM resumen_q_s_asistencia as rqs
    inner join s_q_asistencia  as  sqa on rqs.ids_q_asistencia =sqa.ids_q_asistencia
    WHERE sqa.idproyecto='$nube_idproyecto' and rqs.estado_envio_contador='1' GROUP by rqs.numero_q_s;";

    $pago_programado = ejecutarConsultaArray($sql_1);  if ($pago_programado['status'] == false) { return $pago_programado; }
     
    foreach ($pago_programado['data'] as $key => $value) {

      $ids_q_asistencia = $value['ids_q_asistencia'];

      $sql_2 = "SELECT SUM( pqs.monto_deposito) as total_deposito  
      FROM pagos_q_s_obrero pqs
      inner JOIN resumen_q_s_asistencia rqs on pqs.idresumen_q_s_asistencia= rqs.idresumen_q_s_asistencia
      where rqs.ids_q_asistencia='$ids_q_asistencia' and pqs.estado='1' and pqs.estado_delete = '1';";

      $depositos = ejecutarConsultaSimpleFila($sql_2);  if ($depositos['status'] == false) { return $depositos; }

      $saldo = (empty($value['total_pago']) ? 0 : ( empty($value['total_pago']) ? 0 : floatval($value['total_pago']) ))-(empty($depositos['data']) ? 0 : ( empty($depositos['data']['total_deposito']) ? 0 : floatval($depositos['data']['total_deposito']) ));

      $data[] = [
        'ids_q_asistencia' => $value['ids_q_asistencia'],
        'numero_q_s'       => $value['numero_q_s'],
        'fecha_q_s_inicio' => $value['fecha_q_s_inicio'],
        'fecha_q_s_fin'    => $value['fecha_q_s_fin'],
        'total_programado' => (empty($value['total_pago']) ? 0 : ( empty($value['total_pago']) ? 0 : floatval($value['total_pago']) )),     
        'total_deposito'   => (empty($depositos['data']) ? 0 : ( empty($depositos['data']['total_deposito']) ? 0 : floatval($depositos['data']['total_deposito']) )),
        'saldo'            =>$saldo
      ];
    }   

    return $retorno = ['status'=> true, 'message'=> 'todo oka bro', 'data'=> $data,] ;
  }

  // Obtenemos los totales - TABLA PRINCIPAL
  public function mostrar_total_tbla_principal($id) {
    $sql_1 = "SELECT SUM(pqso.monto_deposito) AS total_deposito
		FROM trabajador_por_proyecto AS tpp, resumen_q_s_asistencia AS rqsa, pagos_q_s_obrero  AS pqso 
		WHERE tpp.idtrabajador_por_proyecto = rqsa.idtrabajador_por_proyecto AND rqsa.idresumen_q_s_asistencia = pqso.idresumen_q_s_asistencia 
		AND rqsa.estado = '1' AND rqsa.estado_delete = '1' AND pqso.estado = '1' AND pqso.estado_delete = '1' AND tpp.idproyecto = '$id';";
    $monto_1 = ejecutarConsultaSimpleFila($sql_1); if ($monto_1['status'] == false) { return $monto_1; }

    $sql_2 = "SELECT SUM(rqsa.total_hn) as total_hn, SUM(rqsa.total_he) as total_he, SUM(rqsa.total_dias_asistidos) as total_dias_asistidos, 
    SUM(rqsa.sabatical) as sabatical,  SUM(rqsa.pago_parcial_hn) as pago_parcial_hn, SUM(rqsa.pago_parcial_he) as pago_parcial_he, 
    SUM(rqsa.adicional_descuento) as adicional_descuento, SUM(rqsa.pago_quincenal) as pago_quincenal, 
    SUM(rqsa.estado_envio_contador) as estado_envio_contador
    FROM resumen_q_s_asistencia as rqsa, trabajador_por_proyecto as tpp
    WHERE rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idproyecto ='1' AND  rqsa.estado_envio_contador = '1' 
    AND rqsa.estado = '1' AND rqsa.estado_delete = '1';";
    $monto_2 = ejecutarConsultaSimpleFila($sql_2); if ($monto_2['status'] == false) { return $monto_2; }

    $data = [
      'status'=> true, 
      'message'=> 'todo oka bro',
      'data'=>[
        'total_deposito'  => (empty($monto_1['data']) ? 0 : ( empty($monto_1['data']['total_deposito']) ? 0 : floatval($monto_1['data']['total_deposito']) )),
        'total_hn'        => (empty($monto_2['data']) ? 0 : ( empty($monto_2['data']['total_hn']) ? 0 : floatval($monto_2['data']['total_hn']) )),
        'total_he'        => (empty($monto_2['data']) ? 0 : ( empty($monto_2['data']['total_he']) ? 0 : floatval($monto_2['data']['total_he']) )),        
        'total_sabatical'       => (empty($monto_2['data']) ? 0 : ( empty($monto_2['data']['sabatical']) ? 0 : floatval($monto_2['data']['sabatical']) )),
        'total_pago_parcial_hn' => (empty($monto_2['data']) ? 0 : ( empty($monto_2['data']['pago_parcial_hn']) ? 0 : floatval($monto_2['data']['pago_parcial_hn']) )),
        'total_pago_parcial_he' => (empty($monto_2['data']) ? 0 : ( empty($monto_2['data']['pago_parcial_he']) ? 0 : floatval($monto_2['data']['pago_parcial_he']) )),
        'total_pago_quincenal'  => (empty($monto_2['data']) ? 0 : ( empty($monto_2['data']['pago_quincenal']) ? 0 : floatval($monto_2['data']['pago_quincenal']) )),
        'total_adicional_descuento'   => (empty($monto_2['data']) ? 0 : ( empty($monto_2['data']['adicional_descuento']) ? 0 : floatval($monto_2['data']['adicional_descuento']) )),        
        'total_envio_contador' => (empty($monto_2['data']) ? 0 : ( empty($monto_2['data']['estado_envio_contador']) ? 0 : floatval($monto_2['data']['estado_envio_contador']) )),
        'total_dias_asistidos'  => (empty($monto_2['data']) ? 0 : ( empty($monto_2['data']['total_dias_asistidos']) ? 0 : floatval($monto_2['data']['total_dias_asistidos']) )),
      ]
    ];

    return $data;
  }

  // ::::::::::::::::::::::::::::::::::::::::::::: R E C I B O S   P O R   H O N O R A R I O ::::::::::::::::::::::::::::::::::::::::::::::
  //EDITAR - RECIBO X HONORARIO
  public function editar_recibo_x_honorario($idresumen_q_s_asistencia_rh, $numero_comprobante_rh, $doc2) {
    $sql = "UPDATE resumen_q_s_asistencia SET numero_comprobante = '$numero_comprobante_rh', recibos_x_honorarios = '$doc2' WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia_rh'";

    return ejecutarConsulta($sql);
  }
  

  // ::::::::::::::::::::::::::::::::::::::::::::: P A G O S  U N   S O L O   O B R E R O S ::::::::::::::::::::::::::::::::::::::::::::::

  //INSERTAR - DEPOSTOS
  public function insertar_pagos_x_q_s($idresumen_q_s_asistencia, $forma_de_pago, $cuenta_deposito, $monto, $fecha_pago, $descripcion, $numero_comprobante, $doc1, $doc2) {
    $sql = "INSERT INTO  pagos_q_s_obrero( idresumen_q_s_asistencia, cuenta_deposito, forma_de_pago, monto_deposito, fecha_pago, numero_comprobante, baucher, recibos_x_honorarios, descripcion) 
		VALUES ('$idresumen_q_s_asistencia', '$cuenta_deposito', '$forma_de_pago', '$monto', '$fecha_pago', '$numero_comprobante', '$doc1', '$doc2', '$descripcion');";

    return ejecutarConsulta($sql);
  }

  //EDITAR - DEPOSTOS
  public function editar_pagos_x_q_s($idpagos_q_s_obrero, $idresumen_q_s_asistencia, $forma_pago, $cuenta_deposito, $monto, $fecha_pago, $descripcion, $numero_comprobante, $doc1, $doc2) {
    $sql = "UPDATE pagos_q_s_obrero SET idresumen_q_s_asistencia='$idresumen_q_s_asistencia', cuenta_deposito='$cuenta_deposito', 
		forma_de_pago='$forma_pago', monto_deposito='$monto', fecha_pago='$fecha_pago', numero_comprobante='$numero_comprobante', baucher='$doc1', recibos_x_honorarios='$doc2', descripcion='$descripcion'
		WHERE idpagos_q_s_obrero = '$idpagos_q_s_obrero,'";

    return ejecutarConsulta($sql);
  }

  // obtebnemos los "BAUCHER DE DEPOSITOS - RECIBO X HONORARIO" para eliminar
  public function obtenerDocs($id) {
    $sql = "SELECT baucher, recibos_x_honorarios FROM pagos_q_s_obrero WHERE idpagos_q_s_obrero = '$id'";

    return ejecutarConsulta($sql);
  }

  //TABLA de quincenas enviadas al CONTADOR
  public function listar_tbla_q_s($idtrabajador_x_proyecto) {
    $data = [];

    $sql_1 = "SELECT tpp.sueldo_hora, rqsa.idresumen_q_s_asistencia, rqsa.idtrabajador_por_proyecto, rqsa.numero_q_s, rqsa.fecha_q_s_inicio, rqsa.fecha_q_s_fin, 
		rqsa.total_hn, rqsa.total_he, (rqsa.total_dias_asistidos_hn + rqsa.total_dias_asistidos_he) as total_dias_asistidos, rqsa.sabatical, rqsa.sabatical_manual_1, rqsa.sabatical_manual_2, 
		rqsa.pago_parcial_hn, rqsa.pago_parcial_he, (rqsa.adicional_descuento_hn + rqsa.adicional_descuento_he) as adicional_descuento, rqsa.descripcion_descuento_hn, rqsa.descripcion_descuento_he, 
    (rqsa.pago_quincenal_hn + rqsa.pago_quincenal_he) as pago_quincenal, 
		rqsa.estado_envio_contador
		FROM resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp
		WHERE  rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND rqsa.idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' 
		AND rqsa.estado_envio_contador = '1' AND rqsa.estado = '1' AND rqsa.estado_delete = '1' ;";
    $q_s = ejecutarConsultaArray($sql_1); if ($q_s['status'] == false) { return $q_s; }

    if (!empty($q_s)) {
      foreach ($q_s['data'] as $key => $q_s) {
        $id = $q_s['idresumen_q_s_asistencia'];

        $sql_2 = "SELECT SUM(monto_deposito) AS deposito  FROM pagos_q_s_obrero WHERE estado = '1' AND estado_delete = '1' AND idresumen_q_s_asistencia = '$id';";
        $depositos = ejecutarConsultaSimpleFila($sql_2);
        if ($depositos['status'] == false) { return $depositos; }

        $sql_3 = "SELECT COUNT(recibos_x_honorarios) as cant_rh  FROM pagos_q_s_obrero WHERE estado = '1' AND estado_delete = '1' AND idresumen_q_s_asistencia = '$id' AND recibos_x_honorarios IS NOT NULL AND recibos_x_honorarios != '';";
        $cant_rh = ejecutarConsultaSimpleFila($sql_3);
        if ($cant_rh['status'] == false) { return $cant_rh; }

        $data[] = [
          'sueldo_hora'               => ( empty($q_s['sueldo_hora']) ? 0 : $q_s['sueldo_hora']),
          'idresumen_q_s_asistencia'  => $q_s['idresumen_q_s_asistencia'],
          'idtrabajador_por_proyecto' => $q_s['idtrabajador_por_proyecto'],
          'numero_q_s'                => ( empty($q_s['numero_q_s']) ? 0 : $q_s['numero_q_s']),
          'fecha_q_s_inicio'          => $q_s['fecha_q_s_inicio'],
          'fecha_q_s_fin'             => $q_s['fecha_q_s_fin'],
          'total_hn'                  => (empty($q_s['total_hn']) ? 0 : $q_s['total_hn']),
          'total_he'                  => (empty($q_s['total_he']) ? 0 : $q_s['total_he']),
          'total_dias_asistidos'      => (empty($q_s['total_dias_asistidos']) ? 0 : $q_s['total_dias_asistidos']),
          'sabatical'                 => (empty($q_s['sabatical']) ? 0 : $q_s['sabatical']),
          'sabatical_manual_1'        => $q_s['sabatical_manual_1'],
          'sabatical_manual_2'        => $q_s['sabatical_manual_2'],
          'pago_parcial_hn'           => (empty($q_s['pago_parcial_hn']) ? 0 : $q_s['pago_parcial_hn']),
          'pago_parcial_he'           => (empty($q_s['pago_parcial_he']) ? 0 : $q_s['pago_parcial_he']),
          'adicional_descuento'       => ( empty($q_s['adicional_descuento']) ? 0 : floatval($q_s['adicional_descuento'])),
          'descripcion_descuento_hn'  => $q_s['descripcion_descuento_hn'],
          'descripcion_descuento_he'  => $q_s['descripcion_descuento_he'],
          'pago_quincenal'            => ( empty($q_s['pago_quincenal']) ? 0 : $q_s['pago_quincenal']),
          'estado_envio_contador'     => $q_s['estado_envio_contador'],
          'cant_rh'                   => (empty($cant_rh['data']) ? 0 : ( empty($cant_rh['data']['cant_rh']) ? 0 : floatval($cant_rh['data']['cant_rh']))),

          'deposito'                  => (empty($depositos['data']) ? 0 : ( empty($depositos['data']['deposito']) ? 0 : floatval($depositos['data']['deposito']))),
        ];
      }
    }

    return $retorno = ['status'=> true, 'message'=> 'todo oka bro', 'data'=> $data,];
  }

  //TABLA DE PAGOS
  public function listar_tbla_pagos_x_q_s($idresumen_q_s_asistencia) {
    $sql = "SELECT idpagos_q_s_obrero, idresumen_q_s_asistencia, cuenta_deposito, forma_de_pago, monto_deposito, fecha_pago, numero_comprobante, recibos_x_honorarios, baucher, descripcion, estado 
		FROM pagos_q_s_obrero
		WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia' AND estado = '1' AND estado_delete = '1';";

    return ejecutarConsulta($sql);
  }

  //MOSTRAR para editar
  public function mostrar_pagos_x_mes($idpagos_q_s_obrero) {
    $sql = "SELECT idpagos_q_s_obrero, idresumen_q_s_asistencia, cuenta_deposito, forma_de_pago, monto_deposito, fecha_pago, numero_comprobante, recibos_x_honorarios, baucher, descripcion
		FROM pagos_q_s_obrero WHERE idpagos_q_s_obrero = '$idpagos_q_s_obrero';";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Desactivar DEPOSITO
  public function desactivar_pago_q_s($idtrabajador) {
    $sql = "UPDATE pagos_q_s_obrero SET estado='0' WHERE idpagos_q_s_obrero='$idtrabajador'";
    return ejecutarConsulta($sql);
  }

  //Activar DEPOSITO
  public function activar_pago_q_s($idtrabajador) {
    $sql = "UPDATE pagos_q_s_obrero SET estado='1' WHERE idpagos_q_s_obrero='$idtrabajador'";
    return ejecutarConsulta($sql);
  }

  //Activar DEPOSITO
  public function tabla_recibo_por_honorario($id_q_s) {
    $sql = "SELECT monto_deposito, fecha_pago, tipo_comprobante, numero_comprobante, recibos_x_honorarios, baucher, descripcion
    FROM pagos_q_s_obrero WHERE idresumen_q_s_asistencia = '$id_q_s' AND estado = '1' AND estado_delete = '1'";
    return ejecutarConsulta($sql);
  }

  // ::::::::::::::::::::::::::::::::::::::::::::: P A G O S  M U L T P L E S   O B R E R O S ::::::::::::::::::::::::::::::::::::::::::::::
  //listar botones de la quincena o semana
  public function listarquincenas_botones($nube_idproyecto) {
    $sql = "SELECT sqa.ids_q_asistencia, sqa.idproyecto, sqa.numero_q_s, sqa.fecha_q_s_inicio, sqa.fecha_q_s_fin
		FROM s_q_asistencia as sqa WHERE sqa.estado = '1' AND sqa.estado_delete = '1' AND sqa.idproyecto='$nube_idproyecto'";
    $btn_asistencia =  ejecutarConsultaArray($sql); if ($btn_asistencia['status'] == false) {  return $btn_asistencia; }

    $sql_2 = "SELECT fecha_inicio_actividad, fecha_fin_actividad FROM proyecto WHERE idproyecto = '$nube_idproyecto'";
    $proyecto =  ejecutarConsultaSimpleFila($sql_2); if ($proyecto['status'] == false) {  return $proyecto; }
    return $retorno = ['status' => true, 'message' => 'todo oka ps', 'data' =>['btn_asistencia' =>$btn_asistencia['data'],'proyecto' => $proyecto['data'] ], ];
  }

  public function tabla_obreros_pago($nube_idproyecto, $num_quincena) {
    $data = [];
    $sql = "SELECT  rqsa.idresumen_q_s_asistencia, rqsa.idtrabajador_por_proyecto,  rqsa.numero_q_s, rqsa.fecha_q_s_inicio, rqsa.fecha_q_s_fin, 
    rqsa.total_hn, rqsa.total_he, (rqsa.total_dias_asistidos_hn + rqsa.total_dias_asistidos_he) as total_dias_asistidos, rqsa.pago_parcial_hn, rqsa.pago_parcial_he, 
    (rqsa.adicional_descuento_hn + rqsa.adicional_descuento_he ) as adicional_descuento, rqsa.descripcion_descuento_hn, rqsa.descripcion_descuento_he, 
    (rqsa.pago_quincenal_hn + rqsa.pago_quincenal_he) as pago_quincenal, rqsa.numero_comprobante, rqsa.recibos_x_honorarios, t.idtrabajador, t.nombres as trabajador, t.tipo_documento, t.numero_documento, t.imagen_perfil, 
     o.nombre_ocupacion , tt.nombre as tipo_trabajador
    FROM resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp, trabajador as t, ocupacion AS o, tipo_trabajador as tt
    WHERE rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto  AND tpp.idtrabajador = t.idtrabajador  
    AND t.idocupacion = o.idocupacion AND t.idtipo_trabajador = tt.idtipo_trabajador
    AND rqsa.estado = '1' AND rqsa.estado_delete = '1' AND rqsa.estado_envio_contador = '1' 
    AND rqsa.numero_q_s = '$num_quincena' AND tpp.idproyecto ='$nube_idproyecto' ORDER BY t.nombres ASC";
    $trabajador = ejecutarConsultaArray($sql); if ($trabajador['status'] == false) { return $trabajador; }

    if (!empty($trabajador)) {
      foreach ($trabajador['data'] as $key => $trabajador) {
        $id = $trabajador['idresumen_q_s_asistencia'];

        $sql_2 = "SELECT SUM(monto_deposito) AS deposito  FROM pagos_q_s_obrero WHERE estado = '1'  AND estado_delete = '1' AND idresumen_q_s_asistencia = '$id';";
        $depositos = ejecutarConsultaSimpleFila($sql_2);  if ($depositos['status'] == false) { return $depositos; }

        $sql_3 = "SELECT COUNT(recibos_x_honorarios) as cant_rh  FROM pagos_q_s_obrero WHERE estado = '1' AND estado_delete = '1' AND idresumen_q_s_asistencia = '$id' AND recibos_x_honorarios IS NOT NULL AND recibos_x_honorarios != '';";
        $cant_rh = ejecutarConsultaSimpleFila($sql_3); if ($cant_rh['status'] == false) { return $cant_rh; }

        $idtrabajador = $trabajador['idtrabajador'];
        $sql_4 = "SELECT cbt.idcuenta_banco_trabajador, cbt.idtrabajador, cbt.idbancos, cbt.cuenta_bancaria, cbt.cci, cbt.banco_seleccionado, b.nombre as banco
        FROM cuenta_banco_trabajador as cbt, bancos as b
        WHERE cbt.idbancos = b.idbancos AND cbt.banco_seleccionado ='1' AND cbt.idtrabajador='$idtrabajador' ;";
        $bancos = ejecutarConsultaSimpleFila($sql_4); if ($bancos['status'] == false) { return  $bancos;}

        $data[] = [
          'idresumen_q_s_asistencia' => $trabajador['idresumen_q_s_asistencia'],
          'idtrabajador_por_proyecto'=> $trabajador['idtrabajador_por_proyecto'],
          'numero_q_s'               => ( empty($trabajador['numero_q_s']) ? 0 : $trabajador['numero_q_s']),
          'fecha_q_s_inicio'         => $trabajador['fecha_q_s_inicio'],
          'fecha_q_s_fin'            => $trabajador['fecha_q_s_fin'],
          'total_hn'                 => (empty($trabajador['total_hn']) ? 0 : $trabajador['total_hn']),
          'total_he'                 => (empty($trabajador['total_he']) ? 0 : $trabajador['total_he']),
          'total_dias_asistidos'    => (empty($trabajador['total_dias_asistidos']) ? 0 : $trabajador['total_dias_asistidos']),
          'pago_parcial_hn'         => (empty($trabajador['pago_parcial_hn']) ? 0 : $trabajador['pago_parcial_hn']),
          'pago_parcial_he'         => (empty($trabajador['pago_parcial_he']) ? 0 : $trabajador['pago_parcial_he']),
          'adicional_descuento'     => ( empty($trabajador['adicional_descuento']) ? 0 : $trabajador['adicional_descuento']),
          'descripcion_descuento_hn'=> $trabajador['descripcion_descuento_hn'],
          'descripcion_descuento_he'=> $trabajador['descripcion_descuento_he'],
          'pago_quincenal'          => ( empty($trabajador['pago_quincenal']) ? 0 : $trabajador['pago_quincenal']),
          'cant_rh'                 => (empty($cant_rh['data']) ? 0 : ( empty($cant_rh['data']['cant_rh']) ? 0 : floatval($cant_rh['data']['cant_rh']))),
          'trabajador'              => $trabajador['trabajador'],
          'tipo_documento'          => $trabajador['tipo_documento'],
          'numero_documento'        => $trabajador['numero_documento'],
          'imagen_perfil'           => $trabajador['imagen_perfil'],

          'banco'                   => (empty($bancos['data']) ? "": $bancos['data']['banco']), 
          'cuenta_bancaria'         => (empty($bancos['data']) ? "" : $bancos['data']['cuenta_bancaria']), 
          'cci'                     => (empty($bancos['data']) ? "" : $bancos['data']['cci']), 

          'nombre_ocupacion'        => $trabajador['nombre_ocupacion'],
          'tipo_trabajador'         => $trabajador['tipo_trabajador'],

          'deposito'                => (empty($depositos['data']) ? 0 : ( empty($depositos['data']['deposito']) ? 0 : $depositos['data']['deposito'])),
        ];
      }
    }

    return $retorno = ['status'=> true, 'message'=> 'todo oka bro', 'data'=> $data,];
  }
/**==================================================================================================
 *                              PAGOS EN BLOQUE DE A LOS OBREROS 
 * =================================================================================================*/

 public function insertar_vocuher_pagos_q_s($ids_q_asistencia_vou,$monto_vou,$fecha_pago_vou,$descripcion_vou,$doc3){
    $sql="INSERT INTO voucher_pago_s_q(ids_q_asistencia, fecha, monto, descripcion, voucher_pago_q_s,user_created) 
    VALUES ('$ids_q_asistencia_vou','$fecha_pago_vou','$monto_vou','$descripcion_vou','$doc3','" . $_SESSION['idusuario'] . "')";
    return ejecutarConsulta($sql);
  }

  public function editar_vocuher_pagos_q_s($idvoucher_pago_s_q,$ids_q_asistencia_vou,$monto_vou,$fecha_pago_vou,$descripcion_vou,$doc3){
    $sql="UPDATE voucher_pago_s_q 
    SET ids_q_asistencia='$ids_q_asistencia_vou',fecha='$fecha_pago_vou',monto='$monto_vou',
    descripcion='$descripcion_vou',voucher_pago_q_s='$doc3',user_updated='" . $_SESSION['idusuario'] . "' 
    WHERE idvoucher_pago_s_q='$idvoucher_pago_s_q';";
    return ejecutarConsulta($sql);
  }

  public function mostrar_vocuher_pagos_q_s($idvoucher_pago_s_q){
    $sql="SELECT idvoucher_pago_s_q, ids_q_asistencia, fecha, monto, descripcion, voucher_pago_q_s FROM voucher_pago_s_q WHERE idvoucher_pago_s_q='$idvoucher_pago_s_q';";
    return ejecutarConsultaSimpleFila($sql);    
  }

  public function desactivar_vocuher_pagos_q_s($idvoucher_pago_s_q) {
    $sql = "UPDATE voucher_pago_s_q SET estado='0' WHERE idvoucher_pago_s_q='$idvoucher_pago_s_q'";
    return ejecutarConsulta($sql);
  }

  public function tabla_vocuher_pagos_q_s($ids_q_asistencia) {
    $sql = "SELECT idvoucher_pago_s_q, ids_q_asistencia, fecha, monto, descripcion, voucher_pago_q_s, estado
    FROM voucher_pago_s_q WHERE ids_q_asistencia = '$ids_q_asistencia' AND estado = '1' AND estado_delete = '1';";
    
    return ejecutarConsultaArray($sql);

  }

    // obtebnemos los "BAUCHER DE DEPOSITOS - RECIBO X HONORARIO" para eliminar
    public function obtenerVoucher($id) {
      $sql = "SELECT voucher_pago_q_s FROM voucher_pago_s_q WHERE ids_q_asistencia = '$id'";
  
      return ejecutarConsulta($sql);
    }
  

    
}

?>
