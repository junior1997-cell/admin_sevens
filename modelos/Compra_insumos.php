<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Compra_insumos
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  // ::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::::::::::::::::: 

  //Implementamos un método para insertar registros
  public function insertar( $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante,  $serie_comprobante, $val_igv,  $descripcion, $glosa,
    $total_compra, $subtotal_compra, $igv_compra, $estado_detraccion, $idproducto, $unidad_medida,  $nombre_color,
    $cantidad, $precio_sin_igv, $precio_igv, $precio_con_igv, $descuento, $tipo_gravada, $ficha_tecnica_producto ) {
    
    $sql = "INSERT INTO compra_por_proyecto(idproyecto, idproveedor, fecha_compra, tipo_comprobante, serie_comprobante, val_igv, descripcion, glosa, total, subtotal, igv, tipo_gravada, estado_detraccion)
		VALUES ('$idproyecto', '$idproveedor', '$fecha_compra', '$tipo_comprobante', '$serie_comprobante', '$val_igv', '$descripcion', '$glosa', '$total_compra', '$subtotal_compra', '$igv_compra', '$tipo_gravada', '$estado_detraccion')";
     
    $idventanew = ejecutarConsulta_retornarID($sql);
    if ($idventanew['status'] == false) { return  $idventanew;}

    $num_elementos = 0;
    $compra_new = "";

    if ( !empty($idventanew['data']) ) {
    
      while ($num_elementos < count($idproducto)) {
        $id = $idventanew['data'];
        $subtotal_producto = floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos]) + $descuento[$num_elementos];

        $sql_detalle = "INSERT INTO detalle_compra(idcompra_proyecto, idproducto, unidad_medida, color, cantidad, precio_sin_igv, igv, precio_con_igv, descuento, subtotal, ficha_tecnica_producto) 
        VALUES ('$id','$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]',  '$nombre_color[$num_elementos]', '$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_producto', '$ficha_tecnica_producto[$num_elementos]')";
        $compra_new =  ejecutarConsulta($sql_detalle);
        if ($compra_new['status'] == false) { return  $compra_new;}

        $num_elementos = $num_elementos + 1;
      }
    }
    return $compra_new;
  }

  //Implementamos un método para editar registros
  public function editar( $idcompra_proyecto, $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante,  $serie_comprobante, $val_igv,  
  $descripcion, $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $idproducto, $unidad_medida,  $nombre_color,
  $cantidad, $precio_sin_igv, $precio_igv, $precio_con_igv, $descuento, $tipo_gravada, $ficha_tecnica_producto ) {

    if ( !empty($idcompra_proyecto) ) {
      //Eliminamos todos los permisos asignados para volverlos a registrar
      $sqldel = "DELETE FROM detalle_compra WHERE idcompra_proyecto='$idcompra_proyecto';";
      $delete_compra = ejecutarConsulta($sqldel);
      if ($delete_compra['status'] == false) { return $delete_compra; }

      $sql = "UPDATE compra_por_proyecto SET idproyecto = '$idproyecto', idproveedor = '$idproveedor', fecha_compra = '$fecha_compra',
      tipo_comprobante = '$tipo_comprobante', serie_comprobante = '$serie_comprobante', val_igv = '$val_igv', descripcion = '$descripcion',
      glosa = '$glosa', total = '$total_venta', subtotal = '$subtotal_compra', igv = '$igv_compra', tipo_gravada = '$tipo_gravada',
      estado_detraccion = '$estado_detraccion' WHERE idcompra_proyecto = '$idcompra_proyecto'";
      $update_compra = ejecutarConsulta($sql);
      if ($update_compra['status'] == false) { return $update_compra; }

      $num_elementos = 0; $detalle_compra = "";

      while ($num_elementos < count($idproducto)) {
        $subtotal_producto = floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos]) + $descuento[$num_elementos];
        $sql_detalle = "INSERT INTO detalle_compra(idcompra_proyecto, idproducto, unidad_medida, color, cantidad, precio_sin_igv, igv, precio_con_igv, descuento, subtotal, ficha_tecnica_producto) 
        VALUES ('$idcompra_proyecto', '$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]', '$nombre_color[$num_elementos]', '$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_producto', '$ficha_tecnica_producto[$num_elementos]')";
        $detalle_compra = ejecutarConsulta($sql_detalle);
        if ($detalle_compra['status'] == false) { return $detalle_compra; }

        $num_elementos = $num_elementos + 1;
      }
      return $detalle_compra; 
    } else { 
      return $retorno = ['status'=>false, 'mesage'=>'no hay nada', 'data'=>'sin data', ]; 
    }
  }

  public function mostrar_compra_para_editar($id_compras_x_proyecto) {

    $sql = "SELECT  cpp.idcompra_proyecto, cpp.idproyecto, cpp.idproveedor, cpp.fecha_compra, cpp.tipo_comprobante, cpp.serie_comprobante, cpp.val_igv, 
    cpp.descripcion, cpp.glosa, cpp.subtotal, cpp.igv, cpp.total, cpp.estado_detraccion, cpp.estado
    FROM compra_por_proyecto as cpp
    WHERE idcompra_proyecto='$id_compras_x_proyecto';";

    $compra = ejecutarConsultaSimpleFila($sql);
    if ($compra['status'] == false) { return $compra; }

    $sql_2 = "SELECT 	dc.idproducto, dc.ficha_tecnica_producto, dc.cantidad, dc.precio_sin_igv, dc.igv, dc.precio_con_igv,
		dc.descuento,	p.nombre as nombre_producto, p.imagen, dc.unidad_medida, dc.color
		FROM detalle_compra AS dc, producto AS p, unidad_medida AS um, color AS c
		WHERE idcompra_proyecto='$id_compras_x_proyecto' AND  dc.idproducto=p.idproducto AND p.idcolor = c.idcolor 
    AND p.idunidad_medida = um.idunidad_medida;";

    $producto = ejecutarConsultaArray($sql_2);
    if ($producto['status'] == false) { return $producto;  }

    $results = [
      "idcompra_x_proyecto" => $compra['data']['idcompra_proyecto'],      
      "idproyecto" => $compra['data']['idproyecto'],
      "idproveedor" => $compra['data']['idproveedor'],
      "fecha_compra" => $compra['data']['fecha_compra'],
      "tipo_comprobante" => $compra['data']['tipo_comprobante'],
      "serie_comprobante" => $compra['data']['serie_comprobante'],
      "val_igv" => $compra['data']['val_igv'],
      "descripcion" => $compra['data']['descripcion'],
      "glosa" => $compra['data']['glosa'],
      "subtotal" => $compra['data']['subtotal'],
      "igv" => $compra['data']['igv'],
      "total" => $compra['data']['total'],
      "estado_detraccion" => $compra['data']['estado_detraccion'],
      "estado" => $compra['data']['estado'],
      "producto" => $producto['data'],
    ];

    return $retorno = ["status" => true, "message" => 'todo oka', "data" => $results] ;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idcompra_proyecto) {
    $sql = "UPDATE compra_por_proyecto SET estado='0' WHERE idcompra_proyecto='$idcompra_proyecto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar($idcompra_por_proyecto) {
    $sql = "UPDATE compra_por_proyecto SET estado='1' WHERE idcompra_proyecto='$idcompra_por_proyecto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar($idcompra_por_proyecto) {
    $sql = "UPDATE compra_por_proyecto SET estado_delete='0' WHERE idcompra_proyecto='$idcompra_por_proyecto'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idcompra_por_proyecto) {
    $sql = "SELECT * FROM compra_por_proyecto WHERE idcompra_por_proyecto='$idcompra_por_proyecto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal($nube_idproyecto) {
    $data = Array();
    $scheme_host=  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_sevens/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

    $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, cpp.idproveedor, cpp.fecha_compra, cpp.tipo_comprobante, cpp.serie_comprobante,	
    cpp.descripcion, cpp.total, cpp.comprobante, cpp.estado_detraccion, p.razon_social, p.telefono,	cpp.estado 
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproyecto='$nube_idproyecto' AND cpp.idproveedor=p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1'
		ORDER BY cpp.fecha_compra DESC ";
    $compra = ejecutarConsultaArray($sql);
    if ($compra['status'] == false) { return $compra; }

    foreach ($compra['data'] as $key => $value) {
      $idcompra_proyecto = $value['idcompra_proyecto'];
      $sql2 = "SELECT SUM(monto) as total_pago_compras FROM pago_compras WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
      $pagos = ejecutarConsultaSimpleFila($sql2);
      if ($pagos['status'] == false) { return $pagos; }

      $sql3 = "SELECT COUNT(comprobante) as cant_comprobantes FROM factura_compra_insumo WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
      $cant_comprobantes = ejecutarConsultaSimpleFila($sql3);
      if ($cant_comprobantes['status'] == false) { return $cant_comprobantes; }

      $data[] = [
        'idproyecto' => $value['idproyecto'],
        'idcompra_proyecto' => $value['idcompra_proyecto'],
        'idproveedor' => $value['idproveedor'],
        'fecha_compra' => $value['fecha_compra'],
        'tipo_comprobante' => $value['tipo_comprobante'],
        'serie_comprobante' => $value['serie_comprobante'],
        'descripcion' => $value['descripcion'],
        'total' => $value['total'],
        'comprobante' => $value['comprobante'],
        'estado_detraccion' => $value['estado_detraccion'],
        'razon_social' => $value['razon_social'],
        'telefono' => $value['telefono'],
        'estado'  => $value['estado'],
        'total_pago_compras' => (empty($pagos['data']['total_pago_compras']) ? 0 : floatval($pagos['data']['total_pago_compras']) ),
        'cant_comprobantes' => (empty($cant_comprobantes['data']['cant_comprobantes']) ? 0 : floatval($cant_comprobantes['data']['cant_comprobantes']) ),
      ];
    }

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data, 'affected_rows' =>$compra['affected_rows'],  ] ;
  }

  //pago servicio
  public function pago_servicio($idcompra_proyecto) {

    $sql = "SELECT SUM(monto) as total_pago_compras
		FROM pago_compras 
		WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros x proveedor
  public function listar_compraxporvee($nube_idproyecto) {
    // $idproyecto=2;
    $sql = "SELECT cpp.idproyecto as idproyecto, COUNT(cpp.idcompra_proyecto) as cantidad, SUM(cpp.total) as total, 
    p.idproveedor as idproveedor, p.razon_social as razon_social, p.telefono
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproyecto='$nube_idproyecto' AND cpp.idproveedor=p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1'
    GROUP BY cpp.idproveedor ORDER BY p.razon_social ASC";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros x proveedor
  public function listar_detalle_comprax_provee($idproyecto, $idproveedor) {

    $sql = "SELECT * FROM compra_por_proyecto WHERE idproyecto='$idproyecto' AND idproveedor='$idproveedor' AND estado = '1' AND estado_delete = '1'";

    return ejecutarConsulta($sql);
  }

  //mostrar detalles uno a uno de la factura
  public function ver_compra($idcompra_proyecto) {

    $sql = "SELECT cpp.idcompra_proyecto as idcompra_proyecto, 
		cpp.idproyecto , 
		cpp.idproveedor , 
		p.razon_social , p.tipo_documento, p.ruc, p.direccion, p.telefono, 
		cpp.fecha_compra , 
		cpp.tipo_comprobante , 
		cpp.serie_comprobante , 
    cpp.val_igv,
		cpp.descripcion , 
    cpp.glosa,
		cpp.subtotal, 
		cpp.igv , 
		cpp.total ,
    cpp.tipo_gravada ,
		cpp.estado 
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE idcompra_proyecto='$idcompra_proyecto'  AND cpp.idproveedor = p.idproveedor";

    return ejecutarConsultaSimpleFila($sql);
  }

  //lismatamos los detalles
  public function ver_detalle_compra($id_compra) {

    $sql = "SELECT 
		dp.idproducto as idproducto,
		dp.ficha_tecnica_producto  as ficha_tecnica_old, p.ficha_tecnica as ficha_tecnica_new,
		dp.cantidad ,
    dp.unidad_medida, dp.color,
		dp.precio_sin_igv ,
    dp.igv ,
    dp.precio_con_igv ,
		dp.descuento ,
    dp.subtotal ,
		p.nombre as nombre, p.imagen, um.abreviacion
		FROM detalle_compra  dp, producto as p, unidad_medida as um
		WHERE p.idunidad_medida = um.idunidad_medida AND idcompra_proyecto='$id_compra' AND  dp.idproducto=p.idproducto";

    return ejecutarConsulta($sql);
  }

  // ::::::::::::::::::::::::::::::::::::::::: S E C C I O N   P A G O S ::::::::::::::::::::::::::::::::::::::::: 

  public function insertar_pago( $idcompra_proyecto_p,  $idproveedor_pago, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago,
    $banco_pago, $titular_cuenta_pago, $fecha_pago,  $monto_pago,  $numero_op_pago,  $descripcion_pago, $imagen1  ) {
    // var_dump($idcompra_proyecto_p,$idproveedor_pago,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,$banco_pago, $titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$imagen1);die();
    $sql = "INSERT INTO pago_compras (idcompra_proyecto, idproveedor, beneficiario, forma_pago, tipo_pago, cuenta_destino, idbancos, titular_cuenta, fecha_pago, monto, numero_operacion, descripcion, imagen) 
		VALUES ('$idcompra_proyecto_p',	'$idproveedor_pago', '$beneficiario_pago', '$forma_pago', '$tipo_pago', '$cuenta_destino_pago',
		'$banco_pago', '$titular_cuenta_pago', '$fecha_pago', '$monto_pago', '$numero_op_pago',	'$descripcion_pago', '$imagen1')";
    return ejecutarConsulta($sql);
  }
  
  //Implementamos un método para editar registros
  public function editar_pago( $idpago_compras, $idcompra_proyecto_p, $idproveedor_pago, $beneficiario_pago, $forma_pago, $tipo_pago,
    $cuenta_destino_pago, $banco_pago, $titular_cuenta_pago, $fecha_pago, $monto_pago, $numero_op_pago, $descripcion_pago, $imagen1 ) {
    // var_dump($idcompra_proyecto_p,$idproveedor_pago,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,$banco_pago, $titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$imagen1);die();
    
    $sql = "UPDATE pago_compras SET
		idcompra_proyecto ='$idcompra_proyecto_p',
		idproveedor='$idproveedor_pago',
		beneficiario='$beneficiario_pago',
		forma_pago='$forma_pago',
		tipo_pago='$tipo_pago',
		cuenta_destino='$cuenta_destino_pago',
		idbancos='$banco_pago',
		titular_cuenta='$titular_cuenta_pago',
		fecha_pago='$fecha_pago',
		monto='$monto_pago',
		numero_operacion='$numero_op_pago',
		descripcion='$descripcion_pago',
		imagen='$imagen1'
		WHERE idpago_compras='$idpago_compras'";
    return ejecutarConsulta($sql);
  }

  //Listar pagos-normal
  public function listar_pagos($idcompra_proyecto) {
    //var_dump($idproyecto,$idmaquinaria);die();
    $sql = "SELECT
		ps.idpago_compras  as idpago_compras,
		ps.forma_pago as forma_pago,
		ps.tipo_pago as tipo_pago,
		ps.beneficiario as beneficiario,
		ps.cuenta_destino as cuenta_destino,
		ps.titular_cuenta as titular_cuenta,
		ps.fecha_pago as fecha_pago,
		ps.descripcion as descripcion,
		ps.idbancos as id_banco,
		bn.nombre as banco,
		ps.numero_operacion as numero_operacion,
		ps.monto as monto,
		ps.imagen as imagen,
		ps.estado as estado
		FROM pago_compras ps, bancos as bn 
		WHERE ps.idcompra_proyecto='$idcompra_proyecto' AND bn.idbancos=ps.idbancos AND ps.estado = '1' AND ps.estado_delete = '1'
    ORDER BY ps.fecha_pago DESC";
    return ejecutarConsulta($sql);
  }

  //Listar pagos1-con detraccion --tabla Proveedor
  public function listar_pagos_compra_prov_con_dtracc($idcompra_proyecto, $tipo_pago) {
    //var_dump($idproyecto,$idmaquinaria);die();
    $sql = "SELECT ps.idpago_compras  as idpago_compras,
    ps.forma_pago as forma_pago,
    ps.tipo_pago as tipo_pago,
    ps.beneficiario as beneficiario,
    ps.cuenta_destino as cuenta_destino,
    ps.titular_cuenta as titular_cuenta,
    ps.fecha_pago as fecha_pago,
    ps.descripcion as descripcion,
    ps.idbancos as id_banco,
    bn.nombre as banco,
    ps.numero_operacion as numero_operacion,
    ps.monto as monto,
    ps.imagen as imagen,
    ps.estado as estado
    FROM pago_compras ps, bancos as bn 
    WHERE ps.idcompra_proyecto='$idcompra_proyecto' AND bn.idbancos=ps.idbancos AND ps.tipo_pago='$tipo_pago' AND ps.estado ='1' AND ps.estado_delete ='1'
    ORDER BY ps.fecha_pago DESC";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar_pagos($idpago_compras) {
    //var_dump($idpago_compras);die();
    $sql = "UPDATE pago_compras SET estado='0' WHERE idpago_compras ='$idpago_compras'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar_pagos($idpago_compras) {
    $sql = "UPDATE pago_compras SET estado='1' WHERE idpago_compras ='$idpago_compras'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar_pagos($idpago_compras) {
    $sql = "UPDATE pago_compras SET estado_delete='0' WHERE idpago_compras ='$idpago_compras'";
    return ejecutarConsulta($sql);
  }

  //Mostrar datos para editar Pago servicio.
  public function mostrar_pagos($idpago_compras) {

    $sql = "SELECT
		ps.idpago_compras as idpago_compras,
		ps.idcompra_proyecto as idcompra_proyecto,
		ps.idproveedor as idproveedor,
		ps.forma_pago as forma_pago,
		ps.tipo_pago as tipo_pago,
		ps.beneficiario as beneficiario,
		ps.cuenta_destino as cuenta_destino,
		ps.titular_cuenta as titular_cuenta,
		ps.fecha_pago as fecha_pago,
		ps.descripcion as descripcion,
		ps.idbancos as id_banco,
		bn.nombre as banco,
		ps.numero_operacion as numero_operacion,
		ps.monto as monto,
		ps.imagen as imagen,
		ps.estado as estado
		FROM pago_compras ps, bancos as bn
		WHERE idpago_compras='$idpago_compras' AND ps.idbancos = bn.idbancos";
    return ejecutarConsultaSimpleFila($sql);
  }

  // consulta para totales sin detracion
  public function suma_total_pagos($idcompra_proyecto) {

    $sql = "SELECT SUM(ps.monto) as total_monto
		FROM pago_compras as ps
		WHERE  ps.idcompra_proyecto='$idcompra_proyecto' AND ps.estado='1' AND ps.estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //consultas para totales con detracion
  public function suma_total_pagos_detraccion($idcompra_proyecto, $tipopago) {

    $sql = "SELECT SUM(ps.monto) as total_montoo
		FROM pago_compras as ps
		WHERE  ps.idcompra_proyecto='$idcompra_proyecto' AND ps.tipo_pago='$tipopago' AND ps.estado='1' AND ps.estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function total_costo_parcial_pago($idmaquinaria, $idproyecto) {

    $sql = "SELECT SUM(s.costo_parcial) as costo_parcial  
		FROM servicio as s 
		WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' AND s.estado='1'";

    return ejecutarConsultaSimpleFila($sql);
  }
  
  // obtebnemos los DOCS para eliminar
  public function obtenerComprobanteCompra($idpago_compras) {

    $sql = "SELECT imagen FROM pago_compras WHERE idpago_compras='$idpago_compras'";

    return ejecutarConsulta($sql);
  }

  //mostrar datos del proveedor y maquina en form
  public function most_datos_prov_pago($idcompra_proyecto) {

    $sql = " SELECT * FROM compra_por_proyecto as cpp, proveedor as p  
    WHERE cpp.idproveedor=p.idproveedor AND cpp.idcompra_proyecto='$idcompra_proyecto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  :::::::::::::::::::::::::: 
  public function tbla_comprobantes($id_compra) {
    //var_dump($idfacturacompra);die();
    $sql = "SELECT fci.idfactura_compra_insumo, fci.idcompra_proyecto, fci.comprobante, fci.estado, fci.estado_delete, fci.created_at, 
    fci.updated_at, cpp.tipo_comprobante, cpp.serie_comprobante, p.razon_social, cpp.fecha_compra
    FROM factura_compra_insumo as fci, compra_por_proyecto as cpp, proveedor as p
    WHERE fci.idcompra_proyecto = cpp.idcompra_proyecto AND cpp.idproveedor = p.idproveedor AND fci.idcompra_proyecto = '$id_compra' AND fci.estado=1 AND fci.estado_delete=1;";
    return ejecutarConsulta($sql);
  }

  public function agregar_comprobante( $id_compra_proyecto, $doc_comprobante ) {
    //var_dump($idfacturacompra);die();
    $sql = "INSERT INTO factura_compra_insumo ( idcompra_proyecto, comprobante ) 
    VALUES ( '$id_compra_proyecto', '$doc_comprobante')";
    return ejecutarConsulta($sql);
  }

  public function editar_comprobante($idfactura_compra_insumo, $doc_comprobante) {
    //var_dump($idfacturacompra);die();
    $sql = "UPDATE factura_compra_insumo SET comprobante='$doc_comprobante'
    WHERE idfactura_compra_insumo ='$idfactura_compra_insumo'";
    return ejecutarConsulta($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function comprobantes_compra($id_compra) {
    $sql = "SELECT idfactura_compra_insumo, idcompra_proyecto, comprobante
    FROM factura_compra_insumo WHERE estado=1 AND estado_delete=1 AND idcompra_proyecto ='$id_compra'";
    return ejecutarConsultaArray($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function obtener_comprobante($idfactura_compra_insumo) {
    $sql = "SELECT comprobante FROM factura_compra_insumo WHERE idfactura_compra_insumo ='$idfactura_compra_insumo'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar_comprobante($idpago_compras) {
    $sql = "UPDATE factura_compra_insumo SET estado_delete='0' WHERE idfactura_compra_insumo ='$idpago_compras'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function desactivar_comprobante($idpago_compras) {
    $sql = "UPDATE factura_compra_insumo SET estado_delete='0' WHERE idfactura_compra_insumo ='$idpago_compras'";
    return ejecutarConsulta($sql);
  }

  // :::::::::::::::::::::::::: S E C C I O N   M A T E R I A L E S ::::::::::::::::::::::::::


  // ::::::::::::::::::::::::::::::::::::::::: S I N C R O N I Z A R  ::::::::::::::::::::::::::::::::::::::::: 
  public function sincronizar_comprobante() {
    $sql = "SELECT idcompra_proyecto, comprobante FROM compra_por_proyecto WHERE comprobante != 'null' AND comprobante != '';";
    $comprobantes = ejecutarConsultaArray($sql);
    if ($comprobantes == false) {  return $comprobantes; }

    foreach ($comprobantes['data'] as $key => $value) {
      $id_compra = $value['idcompra_proyecto']; $comprobante = $value['comprobante'];
      $sql2 = "INSERT INTO factura_compra_insumo ( idcompra_proyecto, comprobante ) VALUES ( '$id_compra', '$comprobante')";
      $factura_compra = ejecutarConsulta($sql2);
      if ($factura_compra == false) {  return $factura_compra; }
    }

    $sql3 = "SELECT	idcompra_proyecto, comprobante FROM factura_compra_insumo ;";
    $factura_compras = ejecutarConsultaArray($sql3);
    if ($factura_compras == false) {  return $factura_compras; }

    return $retorno = ['status'=>true, 'message'=>'todo oka', 'data'=>['comprobante'=>$comprobantes['data'],'factura_compras'=>$factura_compras['data'],], ];
  }

}

?>
