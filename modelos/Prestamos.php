<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Prestamos
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($id_proyecto_prestamo,$entidad_prestamo,$fecha_inicio_prestamo,$fecha_fin_prestamo,$monto_prestamo,$descripcion_prestamo)
  {
    $sql = "INSERT INTO prestamo(idproyecto, entidad, fecha_inicio, fecha_fin, monto, descripcion) 
             VALUES ('$id_proyecto_prestamo','$entidad_prestamo','$fecha_inicio_prestamo','$fecha_fin_prestamo','$monto_prestamo','$descripcion_prestamo')";
     return ejecutarConsulta($sql);

  }

  //Implementamos un método para editar registros
  public function editar($idprestamo,$id_proyecto_prestamo,$entidad_prestamo,$fecha_inicio_prestamo,$fecha_fin_prestamo,$monto_prestamo,$descripcion_prestamo)
  {
    //var_dump($idproducto,$nombre,$marca,$precio_unitario,$descripcion,$imagen1,$ficha_tecnica,$estado_igv,$monto_igv,$precio_real,$unid_medida,$total_precio);die();
    $sql = "UPDATE prestamo SET 
            idproyecto='$id_proyecto_prestamo',
            entidad='$entidad_prestamo',
            fecha_inicio='$fecha_inicio_prestamo',
            fecha_fin='$fecha_fin_prestamo',
            monto='$monto_prestamo',
            descripcion='$descripcion_prestamo' 
            WHERE idprestamo='$idprestamo'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar_prestamo($idprestamo)
  {
    $sql = "UPDATE prestamo SET estado='0' WHERE idprestamo ='$idprestamo'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar_prestamo($idprestamo)
  {
    $sql = "UPDATE prestamo SET estado_delete='0' WHERE idprestamo ='$idprestamo'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar_prestamo($idprestamo)
  {
    $sql = "SELECT * FROM prestamo WHERE idprestamo='$idprestamo';";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_prestamos($idproyecto) {
    $data = Array();

    $sql_1 = "SELECT * FROM prestamo WHERE idproyecto='$idproyecto'  AND estado = 1 AND estado_delete=1 ORDER BY idprestamo ASC;";
    $prestamo =  ejecutarConsultaArray($sql_1);

    if ($prestamo['status'] == false) { return $prestamo; }

    foreach ($prestamo['data'] as $key => $value) {
      $idprestamo = $value['idprestamo'];

      $sql_2 = "SELECT SUM(monto) as total_pagos FROM pago_prestamo WHERE idprestamo=$idprestamo AND estado = 1 AND estado_delete=1;";
      $pago_prestamo = ejecutarConsultaSimpleFila($sql_2);
      if ($pago_prestamo['status'] == false) { return $pago_prestamo; }

      $data[] = [
        'idproyecto'         => $value['idproyecto'],
        'idprestamo'        => $value['idprestamo'],
        'entidad'            => $value['entidad'],
        'fecha_inicio'       => $value['fecha_inicio'],
        'fecha_fin'          => $value['fecha_fin'],
        'monto'              => $value['monto'],
        'descripcion'        => $value['descripcion'],
        'estado'             => $value['estado'],
        'total_pago_compras' => (empty($pago_prestamo['data']) ? 0 : (empty($pago_prestamo['data']['total_pagos']) ? 0 : floatval($pago_prestamo['data']['total_pagos'] ))),
      ];
    }

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data, 'affected_rows' =>$prestamo['affected_rows'],  ] ;

  }
  

  public function mostrar_total_tbla_prestamo($idproyecto ) {
    $sql = "SELECT SUM(monto) as total_monto_prestamos FROM prestamo WHERE idproyecto = '$idproyecto' AND estado = 1 AND estado_delete=1";
    $monto_1 = ejecutarConsultaSimpleFila($sql);
    if ($monto_1['status'] == false) { return $monto_1; }

    $sql2 = "SELECT SUM(pp.monto) as total_pagos_prestamos FROM pago_prestamo as pp, prestamo as p 
    WHERE pp.idprestamo = p.idprestamo AND p.idproyecto ='$idproyecto' AND pp.estado = '1' AND pp.estado_delete='1' AND p.estado ='1' AND p.estado_delete='1';";     
    $monto_2 = ejecutarConsultaSimpleFila($sql2);
    if ( $monto_2['status'] == false) { return $monto_2; }

    $total_monto = (empty($monto_1['data']) ? 0 : (empty($monto_1['data']['total_monto_prestamos']) ? 0 : floatval($monto_1['data']['total_monto_prestamos'] )));
    $gasto_monto = (empty($monto_2['data']) ? 0 : (empty($monto_2['data']['total_pagos_prestamos']) ? 0 : floatval($monto_2['data']['total_pagos_prestamos'] )));

      $deuda =$total_monto-$gasto_monto;

    return $retorno = [
      'status' => true, 
      'message' => 'todo oka ps', 
      'data' => [
                  'total_monto_prestamos'=>$total_monto, 
                  'total_pagos_prestamos'=>$gasto_monto,
                  'deuda'=>$deuda,
                ],
    ];
    
  }

  // ========= ============= ================== ============
  //:::: S E C C I Ó N  P A G O   P R É S T A M O S ::::::
  // ========= ============= ================== ============

  public function listar_pagos_prestamos($idprestamo)
  {
    $sql = "SELECT * FROM pago_prestamo WHERE idprestamo=$idprestamo AND estado='1' AND estado_delete='1';";
   return ejecutarConsultaArray($sql);
  }

  public function insertar_pago_prestamo($idprestamo_p,$fecha_pago_p,$monto_pago_p,$descripcion_pago_p,$imagen1)
  {
    $sql="INSERT INTO pago_prestamo(idprestamo, fecha, monto, descripcion, comprobante) VALUES ('$idprestamo_p','$fecha_pago_p','$monto_pago_p','$descripcion_pago_p','$imagen1')";
    return ejecutarConsulta($sql);
  }
  public function editar_pago_prestamo($idpago_prestamo,$idprestamo_p,$fecha_pago_p,$monto_pago_p,$descripcion_pago_p,$imagen1)
  {
    $sql="UPDATE pago_prestamo SET idprestamo='$idprestamo_p', fecha='$fecha_pago_p', monto='$monto_pago_p', descripcion='$descripcion_pago_p', comprobante='$imagen1' WHERE  idpago_prestamo='$idpago_prestamo'";
    return ejecutarConsulta($sql);
  }

    //Implementamos un método para desactivar categorías
    public function desactivar_pago_prestamo($idpago_prestamo)
    {
      $sql = "UPDATE pago_prestamo SET estado='0' WHERE idpago_prestamo ='$idpago_prestamo'";
      return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar categorías
    public function eliminar_pago_prestamo($idpago_prestamo)
    {
      $sql = "UPDATE pago_prestamo SET estado_delete='0' WHERE idpago_prestamo ='$idpago_prestamo'";
      return ejecutarConsulta($sql);
    }


  public function obtenerImg_pago_prestamo($idpago_prestamo)
  {
    $sql="SELECT comprobante FROM pago_prestamo WHERE idpago_prestamo=$idpago_prestamo";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function mostrar_total_tbla_pago_prestamo($idprestamo)
  {
    $sql="SELECT SUM(pp.monto) as pago_total_prestamo FROM pago_prestamo as pp, prestamo as p  
    WHERE pp.idprestamo = p.idprestamo AND pp.idprestamo='$idprestamo' AND pp.estado='1' AND pp.estado_delete='1' AND p.estado ='1' AND p.estado_delete='1';";
    return ejecutarConsultaSimpleFila($sql);
  }

  //--------------------------------------------------------------------------------------------------------
  //--------------------------------------------------------------------------------------------------------
  //--------------------------------------------------------------------------------------------------------
  //--------------------------------------------------------------------------------------------------------

  //Implementamos un método para insertar registros
  public function insertar_credito($id_proyecto_credito,$entidad_credito,$fecha_inicio_credito,$fecha_fin_credito,$monto_credito,$descripcion_credito)
  {
    $sql = "INSERT INTO credito(idproyecto, entidad, fecha_inicio, fecha_fin, monto, descripcion) 
             VALUES ('$id_proyecto_credito','$entidad_credito','$fecha_inicio_credito','$fecha_fin_credito','$monto_credito','$descripcion_credito')";
     return ejecutarConsulta($sql);

  }

  //Implementamos un método para editar registros
  public function editar_credito($idcredito,$id_proyecto_credito,$entidad_credito,$fecha_inicio_credito,$fecha_fin_credito,$monto_credito,$descripcion_credito)
  {
    //var_dump($idproducto,$nombre,$marca,$precio_unitario,$descripcion,$imagen1,$ficha_tecnica,$estado_igv,$monto_igv,$precio_real,$unid_medida,$total_precio);die();
    $sql = "UPDATE credito SET 
            idproyecto='$id_proyecto_credito',
            entidad='$entidad_credito',
            fecha_inicio='$fecha_inicio_credito',
            fecha_fin='$fecha_fin_credito',
            monto='$monto_credito',
            descripcion='$descripcion_credito' 
            WHERE idcredito='$idcredito'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar_credito($idcredito)
  {
    $sql = "UPDATE credito SET estado='0' WHERE idcredito ='$idcredito'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar_credito($idcredito)
  {
    $sql = "UPDATE credito SET estado_delete='0' WHERE idcredito ='$idcredito'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar_credito($idcredito)
  {
    $sql = "SELECT * FROM credito WHERE idcredito='$idcredito';";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_creditos($idproyecto) {
    $data = Array();

    $sql_1 = "SELECT * FROM credito WHERE idproyecto='$idproyecto'  AND estado = 1 AND estado_delete=1 ORDER BY idcredito ASC;";
    $credito =  ejecutarConsultaArray($sql_1);

    if ($credito['status'] == false) { return $credito; }

    foreach ($credito['data'] as $key => $value) {
      $idcredito = $value['idcredito'];

      $sql_2 = "SELECT SUM(monto) as total_pagos FROM pago_credito WHERE idcredito=$idcredito AND estado = 1 AND estado_delete=1;";
      $pago_credito = ejecutarConsultaSimpleFila($sql_2);
      if ($pago_credito['status'] == false) { return $pago_credito; }

      $data[] = [
        'idproyecto'         => $value['idproyecto'],
        'idcredito'        => $value['idcredito'],
        'entidad'            => $value['entidad'],
        'fecha_inicio'       => $value['fecha_inicio'],
        'fecha_fin'          => $value['fecha_fin'],
        'monto'              => $value['monto'],
        'descripcion'        => $value['descripcion'],
        'estado'             => $value['estado'],
        'total_pago_credito' => (empty($pago_credito['data']) ? 0 : (empty($pago_credito['data']['total_pagos']) ? 0 : floatval($pago_credito['data']['total_pagos'] ))),
      ];
    }

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data, 'affected_rows' =>$credito['affected_rows'],  ] ;

  }

  public function mostrar_total_tbla_credito($idproyecto ) {
    $sql = "SELECT SUM(monto) as total_monto_creditos FROM credito WHERE idproyecto = '$idproyecto' AND estado = 1 AND estado_delete=1";
    $monto_1 = ejecutarConsultaSimpleFila($sql);
    if ($monto_1['status'] == false) { return $monto_1; }

    $sql2 = "SELECT SUM(pp.monto) as total_pagos_creditos 
    FROM pago_credito as pp, credito as c 
    WHERE pp.idcredito = c.idcredito AND c.idproyecto ='$idproyecto' AND pp.estado = '1' AND pp.estado_delete='1 ' AND c.estado ='1' AND c.estado_delete='1';";     
    $monto_2 = ejecutarConsultaSimpleFila($sql2);
    if ( $monto_2['status'] == false) { return $monto_2; }

    $total_monto = (empty($monto_1['data']) ? 0 : (empty($monto_1['data']['total_monto_creditos']) ? 0 : floatval($monto_1['data']['total_monto_creditos'] )));
    $gasto_monto = (empty($monto_2['data']) ? 0 : (empty($monto_2['data']['total_pagos_creditos']) ? 0 : floatval($monto_2['data']['total_pagos_creditos'] )));

      $deuda =$total_monto-$gasto_monto;

    return $retorno = [
      'status' => true, 
      'message' => 'todo oka ps', 
      'data' => [
                  'total_monto_creditos'=>$total_monto, 
                  'total_pagos_creditos'=>$gasto_monto,
                  'deuda'=>$deuda,
                ],
    ];
    
  }

  //------------------------pago prestamos------------------------
  public function listar_pagos_creditos($idcredito)
  {
    $sql = "SELECT * FROM pago_credito WHERE idcredito=$idcredito AND estado='1' AND estado_delete='1';";
  return ejecutarConsultaArray($sql);
  }

  public function insertar_pago_credito($idcredito_c,$fecha_pago_c,$monto_pago_c,$descripcion_pago_c,$imagen2)
  {
    $sql="INSERT INTO pago_credito(idcredito, fecha, monto, descripcion, comprobante) VALUES ('$idcredito_c','$fecha_pago_c','$monto_pago_c','$descripcion_pago_c','$imagen2')";
    return ejecutarConsulta($sql);
  }
  public function editar_pago_credito($idpago_credito,$idcredito_c,$fecha_pago_c,$monto_pago_c,$descripcion_pago_c,$imagen2)
  {
    $sql="UPDATE pago_credito SET idcredito='$idcredito_c', fecha='$fecha_pago_c', monto='$monto_pago_c', descripcion='$descripcion_pago_c', comprobante='$imagen2' WHERE  idpago_credito='$idpago_credito'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar_pago_credito($idpago_credito)
  {
    $sql = "UPDATE pago_credito SET estado='0' WHERE idpago_credito ='$idpago_credito'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar_pago_credito($idpago_credito)
  {
    $sql = "UPDATE pago_credito SET estado_delete='0' WHERE idpago_credito ='$idpago_credito'";
    return ejecutarConsulta($sql);
  }

  public function obtenerImg_pago_credito($idpago_credito)
  {
    $sql="SELECT comprobante FROM pago_credito WHERE idpago_credito=$idpago_credito";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function mostrar_total_tbla_pago_credito($idcredito)
  {
    $sql="SELECT SUM(pc.monto) as pago_total_credito FROM pago_credito as pc, credito as c 
    WHERE pc.idcredito = c.idcredito AND pc.idcredito='$idcredito' AND pc.estado='1' AND pc.estado_delete='1' AND c.estado ='1' AND c.estado_delete='1';";
    return ejecutarConsultaSimpleFila($sql);
  }

  //--------------------------------------------------------------------------------------------------------
  //--------------------------------------------------------------------------------------------------------

  public function tbla_resumen_prest_credit($id_proyecto)
  {
   
    $sql_1 = "SELECT SUM(monto) AS gasto FROM prestamo WHERE idproyecto = '$id_proyecto' AND estado ='1' AND estado_delete = '1';";
    $gasto_prestamo = ejecutarConsultaSimpleFila($sql_1);    
    if ($gasto_prestamo['status'] == false) {  return $gasto_prestamo;  }

    $gasto_prestamo = (empty($gasto_prestamo['data']) ? 0 : (empty($gasto_prestamo['data']['gasto']) ? 0 : floatval($gasto_prestamo['data']['gasto']) ) );

    $sql_2 = "SELECT SUM(pp.monto) AS deposito FROM pago_prestamo as pp, prestamo as p  
    WHERE pp.idprestamo = p.idprestamo AND p.idproyecto ='$id_proyecto' and pp.estado = '1' AND pp.estado_delete ='1' AND p.estado ='1' AND p.estado_delete='1';";
    $deposito_prestamo = ejecutarConsultaSimpleFila($sql_2);
    if ($deposito_prestamo['status'] == false) {  return $deposito_prestamo;  }

    $deposito_prestamo =(empty($deposito_prestamo['data']) ? 0 : (empty($deposito_prestamo['data']['deposito']) ? 0 : floatval($deposito_prestamo['data']['deposito']) ) );

    $deuda_prestamo =  $gasto_prestamo - $deposito_prestamo;

    // //------------------------------------------------------

    $sql_3 = "SELECT SUM(monto) AS gasto FROM credito WHERE idproyecto ='$id_proyecto' AND estado ='1' AND estado_delete = '1';";
    $gasto_credito = ejecutarConsultaSimpleFila($sql_3);
    if ($gasto_credito['status'] == false) {  return $gasto_credito;  }

    $gasto_credito = (empty($gasto_credito['data']) ? 0 : (empty($gasto_credito['data']['gasto']) ? 0 : floatval($gasto_credito['data']['gasto']) ) );

    $sql_4 = "SELECT SUM(pc.monto) AS deposito FROM pago_credito AS pc, credito as c 
    WHERE pc.idcredito = c.idcredito AND c.idproyecto ='$id_proyecto' and pc.estado = '1' AND pc.estado_delete ='1' 
    AND c.estado ='1' AND c.estado_delete='1';";
    $deposito_credito = ejecutarConsultaSimpleFila($sql_4);
    if ($deposito_credito['status'] == false) {  return $deposito_credito;  }

    $deposito_credito =(empty($deposito_credito['data']) ? 0 : (empty($deposito_credito['data']['deposito']) ? 0 : floatval($deposito_credito['data']['deposito']) ) );

    $deuda_credito =  $gasto_credito - $deposito_credito;

    $data= [

      'total_prestamo'             => $gasto_prestamo,
      'total_deposito_prestamo'    => $deposito_prestamo,
      'deuda_prestamo'             => $deuda_prestamo,

      'total_credito'              => $gasto_credito,
      'total_deposito_credito'     => $deposito_credito,
      'deuda_credito'              => $deuda_credito,

      'monto_total_prestamo_credito'   => $gasto_prestamo+$gasto_credito,
      'monto_total_deposito'           =>  $deposito_prestamo+$deposito_credito,
      'monto_total_deuda'              => $deuda_prestamo+$deuda_credito,

    ];

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data, 'affected_rows' =>'3',  ] ;
  }

}

?>
