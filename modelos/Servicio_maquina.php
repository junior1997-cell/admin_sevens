<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class ServicioMaquina
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  public function insertar($idproyecto, $maquinaria, $fecha_inicio, $fecha_fin, $horometro_inicial, $horometro_final, $horas, $costo_unitario, $costo_adicional, $costo_parcial, $unidad_m, $dias, $mes, $descripcion, $cantidad)
  {
    $sql = "INSERT INTO servicio (idproyecto,idmaquinaria,horometro_inicial,horometro_final,horas, costo_adicional, costo_parcial, costo_unitario,fecha_entrega,fecha_recojo,unidad_medida,dias_uso,meses_uso,descripcion,cantidad ) 
		VALUES ('$idproyecto','$maquinaria','$horometro_inicial','$horometro_final', '$horas', '$costo_adicional', '$costo_parcial', '$costo_unitario', '$fecha_inicio', '$fecha_fin', '$unidad_m', '$dias','$mes','$descripcion','$cantidad ')";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros AGRUPADOS
  public function listar($nube_idproyecto)
  {
    $sql = "SELECT 
		s.idmaquinaria as idmaquinaria,
		s.idproyecto as idproyecto,
		s.unidad_medida as unidad_medida,
		m.nombre as maquina,
        p.razon_social as razon_social,
		m.codigo_maquina as codigo_maquina,
		COUNT(s.idmaquinaria) as cantidad_veces, 
		SUM(s.horas) as Total_horas, 
		s.costo_unitario as costo_unitario, 
		SUM(s.costo_parcial) as costo_parcial,
		SUM(s.horas)as horas,
		s.estado as estado
		FROM servicio as s, maquinaria as m, proveedor as p
		WHERE s.estado = 1 AND  s.estado_delete= '1'
		AND s.idproyecto='$nube_idproyecto'
		AND m.tipo = 1
		AND s.idmaquinaria=m.idmaquinaria
        AND m.idproveedor=p.idproveedor
		GROUP BY s.idmaquinaria ORDER BY m.nombre ASC";
    return ejecutarConsulta($sql);
  }
  //pago servicio
  public function pago_servicio($idmaquinaria, $nube_idproyecto)
  {
    $sql = "SELECT SUM(ps.monto) as monto FROM pago_servicio as ps 
		WHERE  ps.estado_delete=1 AND ps.estado=1 AND  ps.id_maquinaria ='$idmaquinaria' AND ps.idproyecto='$nube_idproyecto'";
    return ejecutarConsultaSimpleFila($sql);
  }
  //monto facturas
  public function monto_factura($idmaquinaria, $nube_idproyecto)
  {
    $sql = "SELECT SUM(monto) as monto_factura FROM factura 
		WHERE estado=1 AND estado_delete=1 AND idproyecto='$nube_idproyecto' AND idmaquinaria='$idmaquinaria'";
    return ejecutarConsultaSimpleFila($sql);
  }

  /*===============================================
	===========SECCION FUNCIONES POR SERVICIO========
	================================================*/

  //ver detallete por maquina
  public function ver_detalle_m($idmaquinaria, $idproyecto)
  {
    $sql = "SELECT * FROM servicio as s 
		WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' AND estado = '1' AND  estado_delete= '1'
		ORDER BY s.fecha_entrega DESC";

    return ejecutarConsulta($sql);
  }
  //suma_horas_costoparcial
  public function suma_horas_costoparcial($idmaquinaria, $idproyecto)
  {
    $sql = "SELECT 
		SUM(s.horas) as horas, 
		SUM(s.costo_parcial) as costo_parcial  
		FROM servicio as s 
		WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' AND s.estado='1' AND s.estado_delete='1'";

    return ejecutarConsultaSimpleFila($sql);
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
		descripcion='$descripcion'
		 WHERE idservicio ='$idservicio'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idservicio)
  {
    $sql = "UPDATE servicio SET estado='0' WHERE idservicio ='$idservicio'";
    return ejecutarConsulta($sql);
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
    $sql = "UPDATE servicio SET estado_delete='0' WHERE idservicio ='$idservicio'";
    return ejecutarConsulta($sql);
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
  //Seleccionar Trabajador Select2
  public function select2_servicio()
  {
    $sql = "SELECT 
		mq.idmaquinaria as idmaquinaria, 
		mq.nombre as nombre, 
		mq.codigo_maquina as codigo_maquina, 
		p.razon_social as nombre_proveedor, 
		mq.idproveedor as idproveedor
		FROM maquinaria as mq, proveedor as p 
		WHERE mq.idproveedor=p.idproveedor AND mq.estado='1' AND mq.estado_delete='1' AND mq.tipo=1";
    return ejecutarConsulta($sql);
  }

  /**
   * ====================================
   *SECCION PAGO MAQUINARIA
   * ====================================
   */

  public function insertar_pago(
    $idproyecto_pago,
    $beneficiario_pago,
    $forma_pago,
    $tipo_pago,
    $cuenta_destino_pago,
    $banco_pago,
    $titular_cuenta_pago,
    $fecha_pago,
    $monto_pago,
    $numero_op_pago,
    $descripcion_pago,
    $id_maquinaria_pago,
    $imagen1
  ) {
    $sql = "INSERT INTO pago_servicio (idproyecto,beneficiario,forma_pago,tipo_pago,cuenta_destino,id_banco,titular_cuenta,fecha_pago,monto,numero_operacion,descripcion,id_maquinaria,imagen) 
		VALUES ('$idproyecto_pago','$beneficiario_pago','$forma_pago','$tipo_pago','$cuenta_destino_pago','$banco_pago','$titular_cuenta_pago','$fecha_pago','$monto_pago','$numero_op_pago','$descripcion_pago','$id_maquinaria_pago','$imagen1')";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar_pago(
    $idpago_servicio,
    $idproyecto_pago,
    $beneficiario_pago,
    $forma_pago,
    $tipo_pago,
    $cuenta_destino_pago,
    $banco_pago,
    $titular_cuenta_pago,
    $fecha_pago,
    $monto_pago,
    $numero_op_pago,
    $descripcion_pago,
    $id_maquinaria_pago,
    $imagen1
  ) {
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
		id_maquinaria='$id_maquinaria_pago'
		WHERE idpago_servicio='$idpago_servicio'";
    return ejecutarConsulta($sql);
  }

  //Listar pagos
  public function listar_pagos($idmaquinaria, $idproyecto, $tipopago)
  {
    //var_dump($idproyecto,$idmaquinaria);die();
    $sql = "SELECT
		ps.idpago_servicio as idpago_servicio,
		ps.idproyecto as idproyecto,
		ps.id_maquinaria as id_maquinaria,
		ps.forma_pago as forma_pago,
		ps.tipo_pago as tipo_pago,
		ps.beneficiario as beneficiario,
		ps.cuenta_destino as cuenta_destino,
		ps.titular_cuenta as titular_cuenta,
		ps.fecha_pago as fecha_pago,
		ps.descripcion as descripcion,
		ps.id_banco as id_banco,
		bn.nombre as banco,
		ps.numero_operacion as numero_operacion,
		ps.monto as monto,
		ps.imagen as imagen,
		ps.estado as estado
		FROM pago_servicio ps, bancos as bn 
		WHERE ps.idproyecto='$idproyecto' AND ps.id_maquinaria='$idmaquinaria' AND bn.idbancos=ps.id_banco 
    AND ps.tipo_pago='$tipopago' AND ps.estado = '1' AND  ps.estado_delete= '1' ORDER BY ps.fecha_pago DESC";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar_pagos($idpago_servicio)
  {
    //var_dump($idpago_servicio);die();
    $sql = "UPDATE pago_servicio SET estado='0' WHERE idpago_servicio ='$idpago_servicio'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar_pagos($idpago_servicio)
  {
    $sql = "UPDATE pago_servicio SET estado='1' WHERE idpago_servicio ='$idpago_servicio'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar_pagos($idpago_servicio)
  {
    //var_dump($idpago_servicio);die();
    $sql = "UPDATE pago_servicio SET estado_delete='0' WHERE idpago_servicio ='$idpago_servicio'";
    return ejecutarConsulta($sql);
  }

  //Mostrar datos para editar Pago servicio.
  public function mostrar_pagos($idpago_servicio)
  {
    $sql = "SELECT
		ps.idpago_servicio as idpago_servicio,
		ps.idproyecto as idproyecto,
		ps.id_maquinaria as id_maquinaria,
        mq.nombre as nombre_maquina,
		ps.forma_pago as forma_pago,
		ps.tipo_pago as tipo_pago,
		ps.beneficiario as beneficiario,
		ps.cuenta_destino as cuenta_destino,
		ps.titular_cuenta as titular_cuenta,
		ps.fecha_pago as fecha_pago,
		ps.descripcion as descripcion,
		ps.id_banco as id_banco,
		bn.nombre as banco,
		ps.numero_operacion as numero_operacion,
		ps.monto as monto,
		ps.imagen as imagen,
		ps.estado as estado
		FROM pago_servicio ps, bancos as bn, maquinaria as mq
		WHERE idpago_servicio='$idpago_servicio' AND ps.id_banco = bn.idbancos AND mq.idmaquinaria=ps.id_maquinaria";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function suma_total_pagos($idmaquinaria, $idproyecto, $tipopago)
  {
    $sql = "SELECT SUM(ps.monto) as total_monto
		FROM pago_servicio as ps
		WHERE ps.idproyecto ='$idproyecto' AND ps.id_maquinaria='$idmaquinaria' AND ps.estado='1' AND ps.estado_delete='1' AND ps.tipo_pago='$tipopago'";
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

  //mostrar datos del proveedor y maquina en form
  public function most_datos_prov_pago($idmaquinaria)
  {
    $sql = "SELECT * FROM maquinaria as m, proveedor as p  WHERE m.idproveedor=p.idproveedor AND m.idmaquinaria='$idmaquinaria'";
    return ejecutarConsultaSimpleFila($sql);
  }
  /**
   * ==========SECCION FACTURAS=============
   */
  public function insertar_factura($idproyectof, $idmaquina, $codigo, $monto, $fecha_emision, $descripcion_f, $imagen2, $subtotal, $igv, $nota)
  {
    //var_dump($idproyectof,$idmaquina,$codigo,$monto,$fecha_emision,$descripcion_f,$imagen2);die();
    $sql = "INSERT INTO factura (idproyecto,idmaquinaria,codigo,monto,fecha_emision,descripcion,imagen,subtotal,igv,nota) 
		VALUES ('$idproyectof','$idmaquina','$codigo','$monto','$fecha_emision','$descripcion_f','$imagen2','$subtotal','$igv','$nota')";
    return ejecutarConsulta($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function obtenerDoc($idfactura)
  {
    $sql = "SELECT imagen FROM factura WHERE idfactura ='$idfactura'";

    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar_factura($idfactura, $idproyectof, $idmaquina, $codigo, $monto, $fecha_emision, $descripcion_f, $imagen2, $subtotal, $igv, $nota)
  {
    //$vaa="$idfactura,$idproyectof,$idmaquina,$codigo,$monto,$fecha_emision,$descripcion_f,$imagen2";
    $sql = "UPDATE factura SET
		idproyecto='$idproyectof',
		idmaquinaria='$idmaquina',
		codigo='$codigo',
		monto='$monto',
		fecha_emision='$fecha_emision',
		descripcion='$descripcion_f',
		subtotal='$subtotal',
		igv='$igv',
		nota='$nota',
		imagen='$imagen2'
		WHERE idfactura ='$idfactura'";
    return ejecutarConsulta($sql);
    //return $vaa;
  }

  //Listar
  public function listar_facturas($idmaquinaria, $idproyecto)
  {
    //var_dump($idproyecto,$idmaquinaria);die();
    $sql = "SELECT *
		FROM factura WHERE idproyecto='$idproyecto' AND idmaquinaria = '$idmaquinaria' AND  estado='1' AND estado_delete='1'  ORDER BY fecha_emision DESC";
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
    //var_dump($idfactura);die();
    $sql = "UPDATE factura SET estado='0' WHERE idfactura='$idfactura'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function activar_factura($idfactura)
  {
    //var_dump($idpago_servicio);die();
    $sql = "UPDATE factura SET estado='1' WHERE idfactura='$idfactura'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para eliminar 
  public function eliminar_factura($idfactura)
  {
    $sql = "UPDATE factura SET estado_delete='0' WHERE idfactura='$idfactura'";
    return ejecutarConsulta($sql);
  }
  public function total_monto_f($idmaquinaria, $idproyecto)
  {
    $sql = "SELECT SUM(fs.monto) as total_mont_f
		FROM factura as fs
		WHERE fs.idproyecto ='$idproyecto' AND fs.idmaquinaria='$idmaquinaria' AND fs.estado='1' AND fs.estado_delete='1'";
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
