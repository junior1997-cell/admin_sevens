<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Compra
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

    $num_elementos = 0;
    $sw = true;

    if ( !empty($idventanew) ) {
    
      while ($num_elementos < count($idproducto)) {

        $subtotal_producto = floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos]) + $descuento[$num_elementos];

        $sql_detalle = "INSERT INTO detalle_compra(idcompra_proyecto, idproducto, unidad_medida, color, cantidad, precio_sin_igv, igv, precio_con_igv, descuento, subtotal, ficha_tecnica_producto) 
        VALUES ('$idventanew','$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]',  '$nombre_color[$num_elementos]', '$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_producto', '$ficha_tecnica_producto[$num_elementos]')";
        ejecutarConsulta($sql_detalle) or ($sw = false);

        $num_elementos = $num_elementos + 1;
      }
    }
    return $sw;
  }

  //Implementamos un método para editar registros
  public function editar( $idcompra_proyecto, $idproyecto, $idproveedor, $fecha_compra,  $tipo_comprobante,  $serie_comprobante, $val_igv,  
  $descripcion, $glosa, $total_venta, $subtotal_compra, $igv_compra, $estado_detraccion, $idproducto, $unidad_medida,  $nombre_color,
  $cantidad, $precio_sin_igv, $precio_igv, $precio_con_igv, $descuento, $tipo_gravada, $ficha_tecnica_producto ) {

    if ( !empty($idcompra_proyecto) ) {
      //Eliminamos todos los permisos asignados para volverlos a registrar
      $sqldel = "DELETE FROM detalle_compra WHERE idcompra_proyecto='$idcompra_proyecto';";
      ejecutarConsulta($sqldel);

      $sql = "UPDATE compra_por_proyecto SET idproyecto = '$idproyecto', idproveedor = '$idproveedor', fecha_compra = '$fecha_compra',
      tipo_comprobante = '$tipo_comprobante', serie_comprobante = '$serie_comprobante', val_igv = '$val_igv', descripcion = '$descripcion',
      glosa = '$glosa', total = '$total_venta', subtotal = '$subtotal_compra', igv = '$igv_compra', tipo_gravada = '$tipo_gravada',
      estado_detraccion = '$estado_detraccion' WHERE idcompra_proyecto = '$idcompra_proyecto'";
      ejecutarConsulta($sql);

      $num_elementos = 0;
      $sw = true;

      while ($num_elementos < count($idproducto)) {
        $subtotal_producto = floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos]) + $descuento[$num_elementos];
        $sql_detalle = "INSERT INTO detalle_compra(idcompra_proyecto, idproducto, unidad_medida, color, cantidad, precio_sin_igv, igv, precio_con_igv, descuento, subtotal, ficha_tecnica_producto) 
        VALUES ('$idcompra_proyecto', '$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]', '$nombre_color[$num_elementos]', '$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_producto', '$ficha_tecnica_producto[$num_elementos]')";
        ejecutarConsulta($sql_detalle) or ($sw = false);

        $num_elementos = $num_elementos + 1;
      }
    }

    if ( !empty($idcompra_proyecto) ) { return $sw; } else { return false; }
  }

  public function mostrar_compra_para_editar($id_compras_x_proyecto) {

    $sql = "SELECT  cpp.idcompra_proyecto, 
    cpp.idproyecto, cpp.idproveedor, cpp.fecha_compra, 
    cpp.tipo_comprobante , 
    cpp.serie_comprobante,
    cpp.val_igv, 
    cpp.descripcion ,    
    cpp.glosa , 
    cpp.subtotal, 
    cpp.igv , 
    cpp.total ,
    cpp.estado_detraccion ,
    cpp.estado
    FROM compra_por_proyecto as cpp
    WHERE idcompra_proyecto='$id_compras_x_proyecto';";

    $compra = ejecutarConsultaSimpleFila($sql);

    $sql_2 = "SELECT 	dc.idproducto as idproducto,
		dc.ficha_tecnica_producto,
		dc.cantidad,
		dc.precio_sin_igv , dc.igv, dc.precio_con_igv,
		dc.descuento ,
		p.nombre as nombre_producto, p.imagen,
    dc.unidad_medida, dc.color
		FROM detalle_compra AS dc, producto AS p, unidad_medida AS um, color AS c
		WHERE idcompra_proyecto='$id_compras_x_proyecto' AND  dc.idproducto=p.idproducto AND p.idcolor = c.idcolor 
    AND p.idunidad_medida = um.idunidad_medida;";

    $producto = ejecutarConsultaArray($sql_2);

    $results = [
      "idcompra_x_proyecto" => $compra['idcompra_proyecto'],
      "idproyecto" => $compra['idproyecto'],
      "idproveedor" => $compra['idproveedor'],
      "fecha_compra" => $compra['fecha_compra'],
      "tipo_comprobante" => $compra['tipo_comprobante'],
      "serie_comprobante" => $compra['serie_comprobante'],
      "val_igv" => $compra['val_igv'],
      "descripcion" => $compra['descripcion'],
      "glosa" => $compra['glosa'],
      "subtotal" => $compra['subtotal'],
      "igv" => $compra['igv'],
      "total" => $compra['total'],
      "estado_detraccion" => $compra['estado_detraccion'],
      "estado" => $compra['estado'],
      "producto" => $producto,
    ];

    return $results;
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
  public function listar_compra($nube_idproyecto) {
    // $idproyecto=2;
    $sql = "SELECT
		cpp.idproyecto ,
		cpp.idcompra_proyecto ,
		cpp.idproveedor ,
		cpp.fecha_compra ,
		cpp.tipo_comprobante ,
		cpp.serie_comprobante as serie_comprobante,
		cpp.descripcion as descripcion,
		cpp.total as total,
		cpp.comprobante as comprobante,
		cpp.estado_detraccion as estado_detraccion,
		p.razon_social as razon_social, p.telefono,
		cpp.estado as estado
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproyecto='$nube_idproyecto' AND cpp.idproveedor=p.idproveedor AND cpp.estado = '1' AND cpp.estado_delete = '1'
		ORDER BY cpp.fecha_compra DESC ";
    return ejecutarConsulta($sql);
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
		p.razon_social , 
		cpp.fecha_compra , 
		cpp.tipo_comprobante , 
		cpp.serie_comprobante , 
    cpp.val_igv,
		cpp.descripcion , 
    cpp.glosa,
		cpp.subtotal, 
		cpp.igv , 
		cpp.total ,
		cpp.estado 
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE idcompra_proyecto='$idcompra_proyecto'  AND cpp.idproveedor = p.idproveedor";

    return ejecutarConsultaSimpleFila($sql);
  }

  //lismatamos los detalles
  public function ver_detalle_compra($id_compra) {

    $sql = "SELECT 
		dp.idproducto as idproducto,
		dp.ficha_tecnica_producto as ficha_tecnica,
		dp.cantidad ,
    dp.unidad_medida, dp.color,
		dp.precio_sin_igv ,
    dp.igv ,
    dp.precio_con_igv ,
		dp.descuento ,
    dp.subtotal ,
		p.nombre as nombre, p.imagen
		FROM detalle_compra  dp, producto as p
		WHERE idcompra_proyecto='$id_compra' AND  dp.idproducto=p.idproducto";

    return ejecutarConsulta($sql);
  }

  //pago servicio
  public function pago_servicio($idcompra_proyecto) {

    $sql = "SELECT SUM(monto) as total_pago_compras
		FROM pago_compras 
		WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //----Comprobantes pagos-----

  public function editar_comprobante($comprobante_c, $doc_comprobante) {
    //var_dump($idfacturacompra);die();
    $sql = "UPDATE compra_por_proyecto SET comprobante='$doc_comprobante' WHERE idcompra_proyecto ='$comprobante_c'";
    return ejecutarConsulta($sql);
  }

  // obtebnemos los DOCS para eliminar
  public function obtener_comprobante($comprobante_c) {

    $sql = "SELECT comprobante FROM compra_por_proyecto WHERE idcompra_proyecto ='$comprobante_c'";

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

  //Seleccionar Trabajador Select2
	public function obtenerImgPerfilProducto($idproducto)	{

	  $sql = "SELECT imagen FROM producto WHERE idproducto='$idproducto'";
	  return ejecutarConsulta($sql);
	}

  //mostrar datos del proveedor y maquina en form
  public function most_datos_prov_pago($idcompra_proyecto) {

    $sql = " SELECT * FROM compra_por_proyecto as cpp, proveedor as p  
    WHERE cpp.idproveedor=p.idproveedor AND cpp.idcompra_proyecto='$idcompra_proyecto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Listamos los productos a selecionar
  public function listar_productos() {
    $sql = "SELECT p.idproducto AS idproducto,
    p.idunidad_medida AS idunidad_medida,
    p.idcolor AS idcolor,
    p.nombre AS nombre,
    p.marca AS marca,
    ciaf.nombre AS categoria,
    p.descripcion AS descripcion,
    p.imagen AS imagen,
    p.estado_igv AS estado_igv,
    p.precio_unitario AS precio_unitario,
    p.precio_igv AS precio_igv,
    p.precio_sin_igv AS precio_sin_igv,
    p.precio_total AS precio_total,
    p.ficha_tecnica AS ficha_tecnica,
    p.estado AS estado,
    c.nombre_color AS nombre_color,
    um.nombre_medida AS nombre_medida
    FROM producto p, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf
    WHERE um.idunidad_medida=p.idunidad_medida  AND c.idcolor=p.idcolor  AND ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af
    AND p.estado = '1' AND p.estado_delete = '1'
    ORDER BY p.nombre ASC";

    return ejecutarConsulta($sql);
  }

  // ::::::::::::::::::::::::::::::::::::::::: S E C C I O N   S E L E C T 2  ::::::::::::::::::::::::::::::::::::::::: 

  //Select2 Proveedor
  public function select2_proveedor() {
    $sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE estado='1'";
    return ejecutarConsulta($sql);
  }

  //Select2 banco
  public function select2_banco() {
    $sql = "SELECT idbancos as id, nombre, alias FROM bancos WHERE estado='1'  ORDER BY idbancos ASC;";
    return ejecutarConsulta($sql);
  }

  //Select2 color
  public function select2_color() {
    $sql = "SELECT idcolor AS id, nombre_color AS nombre FROM color WHERE estado='1' ORDER BY idcolor ASC;";
    return ejecutarConsulta($sql);
  }

  //Select2 unidad medida
  public function select2_unidad_medida() {
    $sql = "SELECT idunidad_medida AS id, nombre_medida AS nombre, abreviacion FROM unidad_medida WHERE estado='1' ORDER BY nombre_medida ASC;";
    return ejecutarConsulta($sql);
  }

  //Select2 categoria
  public function select2_categoria() {
    $sql = "SELECT idcategoria_insumos_af as id, nombre FROM categoria_insumos_af WHERE estado='1' ORDER BY idcategoria_insumos_af ASC;";
    return ejecutarConsulta($sql);
  }

}

?>
