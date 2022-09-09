<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Sub_contrato
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($idproyecto, $idproveedor, $ruc_proveedor, $tipo_comprobante, $numero_comprobante, $forma_de_pago, $fecha_subcontrato, $val_igv, $subtotal, $igv, $costo_parcial, $descripcion, $comprobante, $tipo_gravada)
  {
    $sql_1 = "SELECT p.razon_social, p.tipo_documento, p.ruc, sc.fecha_subcontrato, sc.forma_de_pago, sc.tipo_comprobante, sc.numero_comprobante,  sc.estado, sc.estado_delete
    FROM subcontrato as sc, proveedor as p
    WHERE sc.idproveedor = p.idproveedor and sc.idproyecto ='$idproyecto' and p.ruc ='$ruc_proveedor' and sc.tipo_comprobante ='$tipo_comprobante' and sc.numero_comprobante ='$numero_comprobante';";
    $prov = ejecutarConsultaArray($sql_1);
    if ($prov['status'] == false) { return  $prov;}

    if (empty($prov['data']) || $tipo_comprobante == 'Ninguno') {

      $sql = "INSERT INTO subcontrato(idproyecto, idproveedor, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_subcontrato, val_igv, subtotal, igv, costo_parcial, descripcion, glosa, comprobante,tipo_gravada, user_created ) 
      VALUES ('$idproyecto', '$idproveedor', '$tipo_comprobante', '$numero_comprobante', '$forma_de_pago', '$fecha_subcontrato', '$val_igv', '$subtotal', '$igv', '$costo_parcial', '$descripcion','SUB CONTRATO','$comprobante','$tipo_gravada','" . $_SESSION['idusuario'] . "')";
      $insertar =  ejecutarConsulta_retornarID($sql); 
      if ($insertar['status'] == false) {  return $insertar; } 
      
      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('subcontrato','".$insertar['data']."','Nuevo sub contrato registrado','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

     return $insertar;

    } else {
    	$info_repetida = '';

    	foreach ($prov['data'] as $key => $value) {
    		$info_repetida .= '<li class="text-left font-size-13px">
    		<span class="font-size-18px text-danger"><b >'.$value['tipo_comprobante'].': </b> '.$value['numero_comprobante'].'</span><br>
    		<b>Razón Social: </b>'.$value['razon_social'].'<br>
    		<b>'.$value['tipo_documento'].': </b>'.$value['ruc'].'<br>
    		<b>Fecha: </b>'.format_d_m_a($value['fecha_subcontrato']).'<br>
    		<b>Forma de pago: </b>'.$value['forma_de_pago'].'<br>
    		<b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
    		<b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
    		<hr class="m-t-2px m-b-2px">
    		</li>';
    	}
    	return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );
    }
  }

  //Implementamos un método para editar registros
  public function editar($idsubcontrato, $idproyecto, $idproveedor, $tipo_comprobante, $numero_comprobante, $forma_de_pago, $fecha_subcontrato, $val_igv, $subtotal, $igv, $costo_parcial, $descripcion, $comprobante, $tipo_gravada)  {
    $sql = "UPDATE subcontrato SET 
		idsubcontrato='$idsubcontrato',
		idproyecto='$idproyecto',
		idproveedor='$idproveedor',
		tipo_comprobante='$tipo_comprobante',
		numero_comprobante='$numero_comprobante',
		forma_de_pago='$forma_de_pago',
		fecha_subcontrato='$fecha_subcontrato',
		val_igv='$val_igv',
		subtotal='$subtotal',
		igv='$igv',
		costo_parcial='$costo_parcial',
		descripcion='$descripcion',
		comprobante='$comprobante',
		tipo_gravada='$tipo_gravada',
    user_updated= '" . $_SESSION['idusuario'] . "' 
    WHERE idsubcontrato='$idsubcontrato'";
    $editar= ejecutarConsulta($sql);

    if ($editar['status'] == false) {  return $editar; }

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('subcontrato','$idsubcontrato','Sub contrato editado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

    return $editar; 
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idsubcontrato) {
    $sql = "UPDATE subcontrato SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idsubcontrato ='$idsubcontrato'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('subcontrato','".$idsubcontrato."','Sub contrato desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($idsubcontrato) {
    $sql = "UPDATE subcontrato SET estado='1' WHERE idsubcontrato ='$idsubcontrato'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idsubcontrato) {
    $sql = "UPDATE subcontrato SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idsubcontrato ='$idsubcontrato'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('subcontrato','$idsubcontrato','Sub contrato Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idsubcontrato)  {
    $sql = "SELECT*FROM subcontrato WHERE idsubcontrato ='$idsubcontrato'";

    return ejecutarConsultaSimpleFila($sql);
  }

  public function verdatos($idsubcontrato) {
    $sql = "SELECT sc.idsubcontrato, sc.idproyecto, sc.idproveedor, sc.tipo_comprobante, sc.numero_comprobante, sc.comprobante, sc.forma_de_pago,
    sc.fecha_subcontrato, sc.glosa, sc.subtotal, sc.igv, sc.costo_parcial, sc.descripcion, sc.comprobante, p.razon_social, p.tipo_documento, p.ruc
	  FROM subcontrato as sc, proveedor as p WHERE sc.idsubcontrato='$idsubcontrato' AND sc.idproveedor=p.idproveedor;";

    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tabla_principal($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {
    $list_subcontrato = [];

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND s.fecha_subcontrato BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_2'";
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND s.idproveedor = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND s.tipo_comprobante = '$comprobante'"; 
    } 


    $sql_1 = "SELECT s.idsubcontrato, s.idproyecto, s.idproveedor, s.tipo_comprobante, s.numero_comprobante, s.forma_de_pago, s.fecha_subcontrato, 
    s.val_igv, s.tipo_gravada, s.subtotal, s.igv, s.costo_parcial, s.descripcion, s.glosa, s.comprobante, s.estado, p.razon_social, 
    p.tipo_documento, p.ruc 
    FROM subcontrato as s, proveedor as p
    WHERE s.idproveedor = p.idproveedor AND s.idproyecto='$idproyecto' AND s.estado='1' AND  s.estado_delete='1' $filtro_proveedor $filtro_comprobante $filtro_fecha
    ORDER BY s.fecha_subcontrato DESC";
    $subcontrato = ejecutarConsultaArray($sql_1);
    if ($subcontrato['status'] == false) {  return $subcontrato; }

    if (!empty($subcontrato['data'])) {
      foreach ($subcontrato['data'] as $key => $value) {
        $id = $value['idsubcontrato'];

        $sql_2 = "SELECT SUM(monto) as total_deposito FROM pago_subcontrato WHERE idsubcontrato='$id' AND estado='1' AND  estado_delete='1';";
        $deposito = ejecutarConsultaSimpleFila($sql_2);
        if ($deposito['status'] == false) {  return $deposito; }

        $list_subcontrato[] = [
          "idsubcontrato"       => $value['idsubcontrato'],
          "idproyecto"          => $value['idproyecto'],
          "idproveedor"         => $value['idproveedor'],
          "tipo_comprobante"    => $value['tipo_comprobante'],
          "numero_comprobante"  => $value['numero_comprobante'],
          "forma_de_pago"       => $value['forma_de_pago'],          
          "fecha_subcontrato"   => $value['fecha_subcontrato'],
          "val_igv"             => (empty($value['val_igv']) ? 0 : floatval($value['val_igv']) ),
          "tipo_gravada"        => $value['tipo_gravada'],
          "subtotal"            => (empty($value['subtotal']) ? 0 : floatval($value['subtotal']) ),
          "igv"                 => (empty($value['igv']) ? 0 : floatval($value['igv']) ),
          "costo_parcial"       => (empty($value['costo_parcial']) ? 0 : floatval($value['costo_parcial']) ),
          "descripcion"         => $value['descripcion'],
          "glosa"               => $value['glosa'],
          "comprobante"         => $value['comprobante'],
          "estado"              => $value['estado'],

          "proveedor"           => $value['razon_social'],
          "tipo_documento"      => $value['tipo_documento'],
          "ruc"                 => $value['ruc'],

          "total_deposito" => (empty($deposito['data']) ? 0 : ( empty($deposito['data']['total_deposito']) ? 0 : $deposito['data']['total_deposito'])),
        ];
      }
    }
    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$list_subcontrato ] ;
    
  }

  //total
  public function total($idproyecto, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND s.fecha_subcontrato BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_2'";
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND s.idproveedor = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND s.tipo_comprobante = '$comprobante'"; 
    } 

    $sql = "SELECT SUM(s.costo_parcial) as total, SUM(s.subtotal) as subtotal, SUM(s.igv) as igv 
    FROM subcontrato as s , proveedor as p
    WHERE s.idproveedor = p.idproveedor AND s.idproyecto='$idproyecto' AND s.estado='1' AND s.estado_delete='1' $filtro_proveedor $filtro_comprobante $filtro_fecha";
    $gasto = ejecutarConsultaSimpleFila($sql);
    if ($gasto['status'] == false) {  return $gasto; }

    $sql_2 = "SELECT SUM(ps.monto) as monto
    FROM pago_subcontrato as ps, subcontrato as s, proveedor as p
    WHERE ps.idsubcontrato = s.idsubcontrato AND s.idproveedor = p.idproveedor AND s.idproyecto ='$idproyecto' AND ps.estado ='1' 
    AND ps.estado_delete ='1' AND s.estado ='1' AND s.estado_delete ='1' $filtro_proveedor $filtro_comprobante $filtro_fecha ;";
    $deposito = ejecutarConsultaSimpleFila($sql_2);
    if ($deposito['status'] == false) {  return $deposito; }

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 
      'data' => [
        'total_subtotal' =>(empty($gasto['data']) ? 0 : ( empty($gasto['data']['subtotal']) ? 0 : $gasto['data']['subtotal'])), 
        'total_igv' =>(empty($gasto['data']) ? 0 : ( empty($gasto['data']['igv']) ? 0 : $gasto['data']['igv'])), 
        'total_gasto' =>(empty($gasto['data']) ? 0 : ( empty($gasto['data']['total']) ? 0 : $gasto['data']['total'])), 
        'total_deposito' =>(empty($deposito['data']) ? 0 : ( empty($deposito['data']['monto']) ? 0 : $deposito['data']['monto']))
      ] 
    ];
  }

  //Seleccionar un comprobante
  public function ficha_tec($idsubcontrato) {
    $sql = "SELECT comprobante FROM subcontrato WHERE idsubcontrato='$idsubcontrato'";
    return ejecutarConsulta($sql);
  }

  

  // ::::::::::::::::::::::::::::::: S E C C C I Ó N  P A G O S :::::::::::::::::::::::::::::::

  public function datos_proveedor($idsubcontrato)  {
    $sql = "SELECT sc.idsubcontrato, p.idbancos, p.razon_social, p.cuenta_bancaria, p.cuenta_detracciones, p.titular_cuenta
	  FROM subcontrato as sc, proveedor as p WHERE sc.idsubcontrato='$idsubcontrato' AND sc.idproveedor=p.idproveedor";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function insertar_pago($idsubcontrato_pago, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1)  {
    $sql = "INSERT INTO pago_subcontrato( idsubcontrato, idbancos, forma_pago, tipo_pago, beneficiario, cuenta_destino, titular_cuenta, fecha_pago, numero_operacion, monto, descripcion, comprobante, user_created) 
		VALUES ('$idsubcontrato_pago','$banco_pago','$forma_pago','$tipo_pago','$beneficiario_pago','$cuenta_destino_pago','$titular_cuenta_pago','$fecha_pago','$numero_op_pago','$monto_pago','$descripcion_pago','$imagen1','" . $_SESSION['idusuario'] . "')";
		$insertar =  ejecutarConsulta_retornarID($sql); 
		if ($insertar['status'] == false) {  return $insertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_subcontrato','".$insertar['data']."','Nuevo pago sub contrato registrado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

    return $insertar;
  }

  public function editar_pago( $idpago_subcontrato, $idsubcontrato_pago, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 ) {
    $sql = "UPDATE pago_subcontrato SET 
    idsubcontrato    ='$idsubcontrato_pago',
    idbancos         ='$banco_pago',
    forma_pago       ='$forma_pago',
    tipo_pago        ='$tipo_pago',
    beneficiario     ='$beneficiario_pago',
    cuenta_destino   ='$cuenta_destino_pago',
    titular_cuenta   ='$titular_cuenta_pago',
    fecha_pago       ='$fecha_pago',
    numero_operacion ='$numero_op_pago',
    monto            ='$monto_pago',
    descripcion      ='$descripcion_pago',
    comprobante      ='$imagen1',
    user_updated= '" . $_SESSION['idusuario'] . "'
    WHERE idpago_subcontrato='$idpago_subcontrato'";
    $editar= ejecutarConsulta($sql);

    if ($editar['status'] == false) {  return $editar; }

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_subcontrato','$idpago_subcontrato','Pago sub contrato editado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

    return $editar;  
  }

  public function listar_pagos($idsubcontrato, $tipo)  {
    $sql = "SELECT ps.idpago_subcontrato,ps.idbancos,ps.forma_pago,ps.tipo_pago,ps.beneficiario,ps.estado,
		ps.cuenta_destino,ps.titular_cuenta,ps.fecha_pago,ps.numero_operacion,ps.monto,ps.descripcion,ps.comprobante, b.nombre as bancos
		FROM pago_subcontrato as ps, bancos as b 
		WHERE ps.idsubcontrato='$idsubcontrato' AND ps.idbancos=b.idbancos AND ps.estado=1 AND ps.estado_delete=1 AND ps.tipo_pago='$tipo'";
    return ejecutarConsulta($sql);
  }
  //------------------

  public function desactivar_pagos($idpago_subcontrato)  {
    $sql = "UPDATE pago_subcontrato SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idpago_subcontrato ='$idpago_subcontrato'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_subcontrato','".$idpago_subcontrato."','Pago sub contrato desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  public function activar_pagos($idpago_subcontrato)  {
    $sql = "UPDATE pago_subcontrato SET estado='1' WHERE idpago_subcontrato ='$idpago_subcontrato'";
    return ejecutarConsulta($sql);
  }

  public function eliminar_pagos($idpago_subcontrato)  {
    $sql = "UPDATE pago_subcontrato SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idpago_subcontrato ='$idpago_subcontrato'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_subcontrato','$idpago_subcontrato','Pago sub contrato Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  public function mostrar_pagos($idpago_subcontrato)  {
    $sql = "SELECT*FROM pago_subcontrato WHERE idpago_subcontrato ='$idpago_subcontrato'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //total
  public function total_pagos($idsubcontrato, $tipo)  {
    $sql = "SELECT SUM(monto) as monto_parcial_deposito FROM pago_subcontrato 
	  WHERE idsubcontrato='$idsubcontrato' AND estado=1 AND estado_delete=1 AND tipo_pago='$tipo';";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function obtenerImg($idpago_subcontrato)  {
    $sql = "SELECT comprobante FROM pago_subcontrato WHERE idpago_subcontrato='$idpago_subcontrato'";
    return ejecutarConsulta($sql);
  }

}

?>
