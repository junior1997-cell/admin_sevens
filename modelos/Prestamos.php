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
  public function desactivar($idproducto)
  {
    $sql = "UPDATE producto SET estado='0' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar($idproducto)
  {
    $sql = "UPDATE producto SET estado='1' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar($idproducto)
  {
    $sql = "UPDATE producto SET estado_delete='0' WHERE idproducto ='$idproducto'";
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

    $sql_1 = "SELECT * FROM prestamo WHERE idproyecto='$idproyecto' ORDER BY idprestamo ASC";
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

    $sql2 = "SELECT SUM(pp.monto) as total_pagos_prestamos FROM pago_prestamo as pp, prestamo as p WHERE pp.idprestamo = p.idprestamo AND p.idproyecto ='$idproyecto' AND pp.estado = 1 AND pp.estado_delete=1";     
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
    $sql="SELECT SUM(monto) as pago_total FROM pago_prestamo WHERE idprestamo='$idprestamo' AND estado='1' AND estado_delete='1';";
    return ejecutarConsultaSimpleFila($sql);
  }
}

?>
