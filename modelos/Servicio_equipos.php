<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ServicioEquipos
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //---------------------------------------------------------------------------------
  //----------------------T A B L A   P R I N C I P A L------------------------------
  //---------------------------------------------------------------------------------

  //Implementar un método para listar los registros AGRUPADOS
  public function listar($nube_idproyecto)
  {
    $data = Array();

    $sql = "SELECT s.idmaquinaria as idmaquinaria, s.idproyecto as idproyecto, s.unidad_medida as unidad_medida, m.nombre as maquina,
        p.razon_social as razon_social, m.codigo_maquina as codigo_maquina, COUNT(s.idmaquinaria) as cantidad_veces, SUM(s.horas) as total_horas, 
    s.costo_unitario as costo_unitario, SUM(s.costo_parcial) as costo_parcial, SUM(s.horas)as horas, s.estado as estado		
    FROM servicio as s, maquinaria as m, proveedor as p
    WHERE s.estado = 1 AND  s.estado_delete= '1' AND s.idproyecto='$nube_idproyecto' AND m.tipo = 2 AND s.idmaquinaria=m.idmaquinaria AND m.idproveedor=p.idproveedor
    GROUP BY s.idmaquinaria ORDER BY m.nombre ASC";

     $tabla_group =  ejecutarConsultaArray($sql);

    if ($tabla_group['status'] == false) { return $tabla_group; }

    foreach ($tabla_group['data'] as $key => $value) {

      $idmaquinaria = $value['idmaquinaria'];

      $sql_2 = "SELECT SUM(ps.monto) as total_pago_compras FROM pago_servicio as ps 
      WHERE  ps.estado_delete=1 AND ps.estado=1 AND  ps.id_maquinaria ='$idmaquinaria' AND ps.idproyecto='$nube_idproyecto'";
      $pagos = ejecutarConsultaSimpleFila($sql_2);

      if ($pagos['status'] == false) {  return $pagos;  }

      $total_pagos =(empty($pagos['data']) ? 0 : (empty($pagos['data']['total_pago_compras']) ? 0 : floatval($pagos['data']['total_pago_compras']) ) );


      $sql_3 = "SELECT SUM(monto) as monto_factura FROM factura 
      WHERE estado=1 AND estado_delete=1 AND idproyecto='$nube_idproyecto' AND idmaquinaria='$idmaquinaria'";
      $total_comprobantes = ejecutarConsultaSimpleFila($sql_3);

      if ($total_comprobantes['status'] == false) {  return $total_comprobantes;  }

      $total_comprob_fact =(empty($total_comprobantes['data']) ? 0 : (empty($total_comprobantes['data']['monto_factura']) ? 0 : floatval($total_comprobantes['data']['monto_factura']) ) );
      
      $costo_parcial= empty($value['costo_parcial']) ? 0 : floatval($value['costo_parcial']);

      $data[] = [
        'idproyecto'     => $value['idproyecto'],
        'idmaquinaria'   => $value['idmaquinaria'],
        'unidad_medida'  => $value['unidad_medida'],
        'maquina'        => $value['maquina'],
        'razon_social'   => $value['razon_social'],
        'codigo_maquina' => $value['codigo_maquina'],
        'cantidad_veces' => $value['cantidad_veces'],
        'total_horas'    => $value['total_horas'],
        'costo_unitario' => $value['costo_unitario'],
        'costo_parcial'  => $value['costo_parcial'],
        'horas'          => $value['horas'],
        'estado'         => $value['estado'],
        'total_pagos'    =>$total_pagos,
        'total_comprob_fact' => $total_comprob_fact,
        'saldo'          =>$costo_parcial-$total_pagos,
        'saldo_factura'  =>$costo_parcial-$total_comprob_fact,
      ];
    }

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data, 'affected_rows' =>$tabla_group['affected_rows'],  ] ;
  }

  //-------------------------------------------------------------------------------
  //----------------------S E C C   F U N C I O N E S  P O R  S E R V--------------
  //-------------------------------------------------------------------------------

  public function insertar($idproyecto, $maquinaria, $fecha_inicio, $fecha_fin, $horometro_inicial, $horometro_final, $horas, $costo_unitario, $costo_adicional, $costo_parcial, $unidad_m, $dias, $mes, $descripcion, $cantidad)
  {
    $sql = "INSERT INTO servicio (idproyecto,idmaquinaria,horometro_inicial,horometro_final,horas, costo_adicional, costo_parcial, costo_unitario,fecha_entrega,fecha_recojo,unidad_medida,dias_uso,meses_uso,descripcion,cantidad, user_created ) 
		VALUES ('$idproyecto','$maquinaria','$horometro_inicial','$horometro_final', '$horas', '$costo_adicional', '$costo_parcial', '$costo_unitario', '$fecha_inicio', '$fecha_fin', '$unidad_m', '$dias','$mes','$descripcion','$cantidad','" . $_SESSION['idusuario'] . "')";
		$insertar =  ejecutarConsulta_retornarID($sql); 
		if ($insertar['status'] == false) {  return $insertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('servicio','".$insertar['data']."','Nuevo registro de servicio equipo','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

    return $insertar;
  }

  //Implementamos un método para editar registros
  public function editar($idservicio, $idproyecto, $maquinaria, $fecha_inicio, $fecha_fin, $horometro_inicial, $horometro_final, $horas, $costo_unitario, $costo_adicional, $costo_parcial, $unidad_m, $dias, $mes, $descripcion, $cantidad)
  {
    $sql = "UPDATE servicio SET 
		idproyecto='$idproyecto',
		idmaquinaria='$maquinaria',
		horometro_inicial='$horometro_inicial',
		horometro_final='$horometro_final',
		horas='$horas',
		costo_adicional = '$costo_adicional',
		costo_parcial='$costo_parcial',
		costo_unitario='$costo_unitario',
		cantidad='$cantidad',
		fecha_entrega='$fecha_inicio',
		fecha_recojo='$fecha_fin',
		unidad_medida='$unidad_m',
		dias_uso='$dias',
		meses_uso='$mes',
		descripcion='$descripcion',
    user_updated= '" . $_SESSION['idusuario'] . "'
		WHERE idservicio ='$idservicio'";

    $editar= ejecutarConsulta($sql);

    if ($editar['status'] == false) {  return $editar; }

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('servicio','$idservicio','Servicio equipo editado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

    return $editar;
  }

  //ver detallete por maquina $_GET["idmaquinaria"], $_GET["idproyecto"],$_GET["fecha_i"],$_GET["fecha_f"],$_GET["proveedor"],$_GET["comprobante"]
  public function ver_detalle_m($idmaquinaria, $idproyecto,$fecha_1,$fecha_2)
  {
      
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND s.fecha_entrega BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND s.fecha_entrega = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND s.fecha_entrega = '$fecha_2'";
    }   

   // if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.idproveedor = '$id_proveedor'"; }
    $sql = "SELECT * FROM servicio as s 
		WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' AND estado = '1' AND  estado_delete= '1' $filtro_fecha
		ORDER BY s.fecha_entrega DESC";

    return ejecutarConsulta($sql);
  }

  //total_costo_parcial_detalle
  public function total_costo_parcial_detalle($idmaquinaria, $idproyecto,$fecha_1,$fecha_2 )
  {
     $filtro_fecha = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND s.fecha_entrega BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND s.fecha_entrega = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND s.fecha_entrega = '$fecha_2'";
    }   

    $sql = "SELECT SUM(s.horas) as horas, SUM(s.costo_parcial) as costo_parcial  		
    FROM servicio as s 
		WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' AND s.estado='1' AND s.estado_delete='1' $filtro_fecha";

    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idservicio)
  {
    $sql = "UPDATE servicio SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idservicio ='$idservicio'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('servicio','".$idservicio."','Servicio equipo desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($idservicio)
  {
    $sql = "UPDATE servicio SET estado='1' WHERE idservicio='$idservicio'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idservicio)
  {
    $sql = "UPDATE servicio SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idservicio ='$idservicio'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('servicio','$idservicio','Servicio equipo Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idservicio)
  {
    $sql = "SELECT
		s.idservicio as idservicio,
		s.idproyecto as idproyecto,
		s.idmaquinaria as idmaquinaria,
		s.horometro_inicial as horometro_inicial,
		s.horometro_final as horometro_final,
		s.horas as horas,
		s.costo_adicional ,
		s.costo_parcial as costo_parcial,
		s.cantidad as cantidad,
		s.costo_unitario as costo_unitario,
		s.fecha_entrega as fecha_entrega,
		s.fecha_recojo as fecha_recojo,
		s.unidad_medida as unidad_medida,
		s.dias_uso as dias_uso,
		s.meses_uso as meses_uso,
		s.descripcion as descripcion,
		m.nombre as nombre_maquina,
		m.codigo_maquina as codigo_maquina,
		p.razon_social as razon_social
		FROM servicio as s, maquinaria as m, proveedor as p 
		WHERE s.idservicio ='$idservicio' AND s.idmaquinaria = m.idmaquinaria AND m.idproveedor=p.idproveedor";
    return ejecutarConsultaSimpleFila($sql);
  }

  //-------------------------------------------------------------------------------
  //----------------------S E C C   P A G O  P O R  S E R V------------------------
  //-------------------------------------------------------------------------------

  public function insertar_pago( $idproyecto_pago, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago,
    $monto_pago, $numero_op_pago, $descripcion_pago, $id_maquinaria_pago, $imagen1 ) 
  {

    $sql_1 = "SELECT forma_pago, tipo_pago, beneficiario, cuenta_destino, titular_cuenta, fecha_pago, numero_operacion,estado,estado_delete FROM pago_servicio WHERE numero_operacion='$numero_op_pago' AND id_maquinaria='$id_maquinaria_pago';";
    $prov = ejecutarConsultaArray($sql_1);

    if ($prov['status'] == false) { return  $prov;}

    if (empty($prov['data']) || $forma_pago =='Efectivo') {

      $sql = "INSERT INTO pago_servicio (idproyecto,beneficiario,forma_pago,tipo_pago,cuenta_destino,id_banco,titular_cuenta,fecha_pago,monto,numero_operacion,descripcion,id_maquinaria,imagen, user_created) 
      VALUES ('$idproyecto_pago','$beneficiario_pago','$forma_pago','$tipo_pago','$cuenta_destino_pago','$banco_pago','$titular_cuenta_pago','$fecha_pago','$monto_pago','$numero_op_pago','$descripcion_pago','$id_maquinaria_pago','$imagen1','" . $_SESSION['idusuario'] . "')";
      $insertar =  ejecutarConsulta_retornarID($sql); 
      if ($insertar['status'] == false) {  return $insertar; } 
      
      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_servicio','".$insertar['data']."','Nuevo pago servicio equipo registrado','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

      return $insertar;

    } else {
      $info_repetida = '';

      foreach ($prov['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
        <span class="font-size-18px text-danger"><b >N° comprobante : </b> '.$value['numero_operacion'].'</span><br>
        <b>Beneficiario: </b>'.$value['beneficiario'].'<br>
        <b>Titular cuenta: </b>'.$value['titular_cuenta'].'<br>
        <b>Fecha: </b>'.format_d_m_a($value['fecha_pago']).'<br>
        <b>Forma de pago: </b>'.$value['forma_pago'].'<br>
        <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
        <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
        <hr class="m-t-2px m-b-2px">
        </li>';
      }
      return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );
    }
    
  }

  public function editar_pago($idpago_servicio, $idproyecto_pago, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago,
    $monto_pago, $numero_op_pago, $descripcion_pago, $id_maquinaria_pago, $imagen1 ) 
  {
    $sql = "UPDATE pago_servicio SET
		idproyecto='$idproyecto_pago',
		beneficiario='$beneficiario_pago',
		forma_pago='$forma_pago',
		tipo_pago='$tipo_pago',
		cuenta_destino='$cuenta_destino_pago',
		id_banco='$banco_pago',
		titular_cuenta='$titular_cuenta_pago',
		fecha_pago='$fecha_pago',
		monto='$monto_pago',
		numero_operacion='$numero_op_pago',
		descripcion='$descripcion_pago',
		imagen='$imagen1',
		id_maquinaria='$id_maquinaria_pago',
    user_updated= '" . $_SESSION['idusuario'] . "'
		WHERE idpago_servicio='$idpago_servicio'";
    $editar= ejecutarConsulta($sql);

    if ($editar['status'] == false) {  return $editar; }

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_servicio','$idpago_servicio','Pago servicio equipo editado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

    return $editar;
  }

  public function listar_pagos($idmaquinaria, $idproyecto, $tipopago,$fecha_1,$fecha_2)
  {
    $filtro_fecha = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND ps.fecha_pago BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND ps.fecha_pago = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND ps.fecha_pago = '$fecha_2'";
    }   

    $sql = "SELECT ps.idpago_servicio as idpago_servicio,ps.idproyecto as idproyecto, ps.id_maquinaria as id_maquinaria, ps.forma_pago as forma_pago, ps.tipo_pago as tipo_pago,
		ps.beneficiario as beneficiario,ps.cuenta_destino as cuenta_destino, ps.titular_cuenta as titular_cuenta, ps.fecha_pago as fecha_pago, ps.descripcion as descripcion,
		ps.id_banco as id_banco, bn.nombre as banco, ps.numero_operacion as numero_operacion, ps.monto as monto, ps.imagen as imagen, ps.estado as estado
		FROM pago_servicio ps, bancos as bn 
		WHERE ps.idproyecto='$idproyecto' AND ps.id_maquinaria='$idmaquinaria' AND bn.idbancos=ps.id_banco 
    AND ps.tipo_pago='$tipopago' AND ps.estado = '1' AND  ps.estado_delete= '1'  $filtro_fecha ORDER BY ps.fecha_pago DESC";
    return ejecutarConsulta($sql);
  }

  public function desactivar_pagos($idpago_servicio)
  {
    $sql = "UPDATE pago_servicio SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idpago_servicio ='$idpago_servicio'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_servicio','".$idpago_servicio."','Pago servicio equipo desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  public function activar_pagos($idpago_servicio)
  {
    $sql = "UPDATE pago_servicio SET estado='1' WHERE idpago_servicio ='$idpago_servicio'";
    return ejecutarConsulta($sql);
  }

  public function eliminar_pagos($idpago_servicio)
  {

    $sql = "UPDATE pago_servicio SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "'  WHERE idpago_servicio ='$idpago_servicio'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_servicio','$idpago_servicio','Pago servicio equipo Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  public function mostrar_pagos($idpago_servicio)
  {
    $sql = "SELECT ps.idpago_servicio as idpago_servicio, ps.idproyecto as idproyecto, ps.id_maquinaria as id_maquinaria,
        mq.nombre as nombre_maquina, ps.forma_pago as forma_pago, ps.tipo_pago as tipo_pago, ps.beneficiario as beneficiario,
		ps.cuenta_destino as cuenta_destino, ps.titular_cuenta as titular_cuenta, ps.fecha_pago as fecha_pago, ps.descripcion as descripcion,
		ps.id_banco as id_banco, bn.nombre as banco, ps.numero_operacion as numero_operacion,
		ps.monto as monto, ps.imagen as imagen, ps.estado as estado		
    FROM pago_servicio ps, bancos as bn, maquinaria as mq
		WHERE idpago_servicio='$idpago_servicio' AND ps.id_banco = bn.idbancos AND mq.idmaquinaria=ps.id_maquinaria";

    return ejecutarConsultaSimpleFila($sql);

  }

  public function suma_total_pagos($idmaquinaria, $idproyecto, $tipopago,$fecha_1,$fecha_2)
  {
    $filtro_fecha = "";  //,$fecha_1,$fecha_2

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND ps.fecha_pago BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND ps.fecha_pago = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND ps.fecha_pago = '$fecha_2'";
    }   

    $sql = "SELECT SUM(ps.monto) as total_monto
		FROM pago_servicio as ps
		WHERE ps.idproyecto ='$idproyecto' AND ps.id_maquinaria='$idmaquinaria' AND ps.estado='1' AND ps.estado_delete='1' AND ps.tipo_pago='$tipopago' $filtro_fecha";
    return ejecutarConsultaSimpleFila($sql);
  }
  
  public function total_costo_parcial_pago($idmaquinaria, $idproyecto)
  {

    $sql = "SELECT
		SUM(s.costo_parcial) as costo_parcial  
		FROM servicio as s 
		WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' AND s.estado='1' AND s.estado_delete='1'";

    return ejecutarConsultaSimpleFila($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function obtenerImg($idpago_servicio)
  {
    $sql = "SELECT imagen FROM pago_servicio WHERE idpago_servicio='$idpago_servicio'";

    return ejecutarConsulta($sql);
  }

  public function most_datos_prov_pago($idmaquinaria)
  {

    $sql = "SELECT m.idmaquinaria,m.nombre,p.razon_social,p.titular_cuenta,p.idbancos, p.cuenta_bancaria, p.cuenta_detracciones
    FROM maquinaria as m, proveedor as p  WHERE m.idproveedor=p.idproveedor AND m.idmaquinaria='$idmaquinaria'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //-------------------------------------------------------------------------------
  //----------------------S E C C   F A C T U R A S--------------------------------
  //-------------------------------------------------------------------------------

  public function insertar_factura($idproyectof, $idmaquina, $codigo, $monto, $fecha_emision, $descripcion_f, $imagen2, $subtotal, $igv, $val_igv, $tipo_gravada, $nota)
  {

    
    $sql_1 = "SELECT tipo_comprobante, codigo, fecha_emision, monto, estado, estado_delete, created_at, updated_at FROM factura WHERE  codigo='$codigo' AND idmaquinaria='$idmaquina';";
    $prov = ejecutarConsultaArray($sql_1);

    if ($prov['status'] == false) { return  $prov;}

    if (empty($prov['data'])) {


      $sql = "INSERT INTO factura (idproyecto,idmaquinaria,codigo,monto,fecha_emision,descripcion,imagen,subtotal,igv,val_igv,tipo_gravada,nota, user_created) 
      VALUES ('$idproyectof','$idmaquina','$codigo','$monto','$fecha_emision','$descripcion_f','$imagen2','$subtotal','$igv', '$val_igv', '$tipo_gravada','$nota','" . $_SESSION['idusuario'] . "')";
      $insertar =  ejecutarConsulta_retornarID($sql); 
      if ($insertar['status'] == false) {  return $insertar; } 
      
      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('factura','".$insertar['data']."','Nueva factura de servicio equipo registrada','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

      return $insertar;

    } else {
      $info_repetida = '';

      foreach ($prov['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
        <span class="font-size-18px text-danger"><b >N° Factura : </b> '.$value['codigo'].'</span><br>
        <b>Fecha de creación: </b>'.extr_fecha_creacion($value['created_at']).'<br>
        <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
        <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
        <hr class="m-t-2px m-b-2px">
        </li>';
      }
      return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );
    }
  }

  // obtebnemos los DOCS para eliminar
  public function obtenerDoc($idfactura)
  {
    $sql = "SELECT imagen FROM factura WHERE idfactura ='$idfactura'";

    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar_factura($idfactura, $idproyectof, $idmaquina, $codigo, $monto, $fecha_emision, $descripcion_f, $imagen2, $subtotal, $igv, $val_igv, $tipo_gravada, $nota)
  {

    $sql = "UPDATE factura SET
		idproyecto='$idproyectof',
		idmaquinaria='$idmaquina',
		codigo='$codigo',
		monto='$monto',
		fecha_emision='$fecha_emision',
		descripcion='$descripcion_f',
		subtotal='$subtotal',
		igv='$igv',
		val_igv='$val_igv',
		tipo_gravada='$tipo_gravada',
		nota='$nota',
		imagen='$imagen2',
    user_updated= '" . $_SESSION['idusuario'] . "'
		WHERE idfactura ='$idfactura'";
    $editar= ejecutarConsulta($sql);

    if ($editar['status'] == false) {  return $editar; }

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('factura','$idfactura','Factura de servicio equipo editada','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

    return $editar;
  }

  //Listar
  public function listar_facturas($idmaquinaria, $idproyecto,$fecha_1,$fecha_2)
  {
    $filtro_fecha = "";  //,$fecha_1,$fecha_2

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND fecha_emision = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND fecha_emision = '$fecha_2'";
    }   

    $sql = "SELECT *
		FROM factura WHERE idproyecto='$idproyecto' AND idmaquinaria = '$idmaquinaria' AND  estado='1' AND estado_delete='1' $filtro_fecha  ORDER BY fecha_emision DESC";
    return ejecutarConsulta($sql);
  }
  
  //mostrar_factura
  public function mostrar_factura($idfactura)
  {
    $sql = "SELECT * FROM factura WHERE idfactura='$idfactura'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementamos un método para activar categorías
  public function desactivar_factura($idfactura)
  {

    $sql = "UPDATE factura SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idfactura='$idfactura'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('factura','".$idfactura."','Factura de servicio equipo desactivada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para desactivar categorías
  public function activar_factura($idfactura)
  {

    $sql = "UPDATE factura SET estado='1' WHERE idfactura='$idfactura'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para eliminar 
  public function eliminar_factura($idfactura)
  {
    $sql = "UPDATE factura SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "'  WHERE idfactura='$idfactura'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('factura','$idfactura','Factura de servicio equipo Eliminada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  public function total_monto_f($idmaquinaria, $idproyecto,$fecha_1,$fecha_2)
  {
    $filtro_fecha = "";  //,$fecha_1,$fecha_2

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fs.fecha_emision BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND fs.fecha_emision = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND fs.fecha_emision = '$fecha_2'";
    }   

    $sql = "SELECT SUM(fs.monto) as total_mont_f
		FROM factura as fs
		WHERE fs.idproyecto ='$idproyecto' AND fs.idmaquinaria='$idmaquinaria' AND fs.estado='1'  AND fs.estado_delete='1' $filtro_fecha";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function total_costo_parcial($idmaquinaria, $idproyecto)
  {
    $sql = "SELECT
		SUM(s.costo_parcial) as costo_parcial  
		FROM servicio as s 
		WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' AND s.estado='1' AND s.estado_delete='1'";

    return ejecutarConsultaSimpleFila($sql);
  }

  public function select2_banco()
  {
    $sql = "SELECT idbancos as id, nombre, alias FROM bancos WHERE estado='1' AND estado_delete='1' ORDER BY idbancos ASC;";
    return ejecutarConsulta($sql);
  }

  // optenesmo el formato para los bancos
  public function formato_banco($idbanco)
  {
    $sql = "SELECT nombre, formato_cta, formato_cci, formato_detracciones FROM bancos WHERE estado='1' estado_delete='1' AND idbancos = '$idbanco';";
    return ejecutarConsultaSimpleFila($sql);
  }

}

?>
