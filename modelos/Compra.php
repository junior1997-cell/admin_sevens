<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Compra
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //Implementamos un método para insertar registros
  public function insertar(
    $idproyecto,
    $idproveedor,
    $fecha_compra,
    $tipo_comprovante,
    $serie_comprovante,
    $descripcion,
    $total_venta,
    $subtotal_compra,
    $igv_compra,
    $estado_detraccion,
    $idproducto,
    $unidad_medida,
    $nombre_color,
    $cantidad,
    $precio_sin_igv,
    $precio_igv,
    $precio_total,
    $descuento,
    $ficha_tecnica_producto
  ) {
    //var_dump($idproyecto,$idproveedor,$fecha_compra,$tipo_comprovante,$serie_comprovante,$descripcion,$total_venta,$idproducto,$cantidad, $precio_unitario,$descuento,$ficha_tecnica_producto);die();
    $sql = "INSERT INTO compra_por_proyecto(idproyecto,idproveedor,fecha_compra,tipo_comprovante,serie_comprovante,descripcion,monto_total,subtotal_compras_proyect,igv_compras_proyect,estado_detraccion)
		VALUES ('$idproyecto','$idproveedor','$fecha_compra','$tipo_comprovante','$serie_comprovante','$descripcion','$total_venta','$subtotal_compra','$igv_compra','$estado_detraccion')";
    //return ejecutarConsulta($sql);
    $idventanew = ejecutarConsulta_retornarID($sql);

    $num_elementos = 0;
    $sw = true;

    while ($num_elementos < count($idproducto)) {
      $subtotal_producto = floatval($cantidad[$num_elementos]) * floatval($precio_total[$num_elementos]) + $descuento[$num_elementos];
      $sql_detalle = "INSERT INTO detalle_compra(idcompra_proyecto, idproducto, unidad_medida, color, cantidad, precio_venta, igv, precio_igv, descuento, subtotal, ficha_tecnica_producto) 
			VALUES ('$idventanew','$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]',  '$nombre_color[$num_elementos]', '$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_total[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_producto', '$ficha_tecnica_producto[$num_elementos]')";
      ejecutarConsulta($sql_detalle) or ($sw = false);

      $num_elementos = $num_elementos + 1;
    }

    return $sw;
  }

  //Implementamos un método para editar registros
  public function editar(
    $idcompra_proyecto,
    $idproyecto,
    $idproveedor,
    $fecha_compra,
    $tipo_comprovante,
    $serie_comprovante,
    $descripcion,
    $total_venta,
    $subtotal_compra,
    $igv_compra,
    $estado_detraccion,
    $idproducto,
    $unidad_medida,
    $nombre_color,
    $cantidad,
    $precio_sin_igv,
    $precio_igv,
    $precio_total,
    $descuento,
    $ficha_tecnica_producto
  ) {
    if ($idcompra_proyecto != "") {
      //Eliminamos todos los permisos asignados para volverlos a registrar
      $sqldel = "DELETE FROM detalle_compra WHERE idcompra_proyecto='$idcompra_proyecto';";
      ejecutarConsulta($sqldel);

      $sql = "UPDATE compra_por_proyecto SET idproyecto = '$idproyecto', idproveedor = '$idproveedor', fecha_compra = '$fecha_compra',
            tipo_comprovante = '$tipo_comprovante', serie_comprovante = '$serie_comprovante', descripcion = '$descripcion',
            monto_total = '$total_venta', subtotal_compras_proyect = '$subtotal_compra', igv_compras_proyect = '$igv_compra', 
            estado_detraccion = '$estado_detraccion' WHERE idcompra_proyecto = '$idcompra_proyecto'";
      ejecutarConsulta($sql);

      $num_elementos = 0;
      $sw = true;

      while ($num_elementos < count($idproducto)) {
        $subtotal_producto = floatval($cantidad[$num_elementos]) * floatval($precio_total[$num_elementos]) + $descuento[$num_elementos];
        $sql_detalle = "INSERT INTO detalle_compra(idcompra_proyecto, idproducto, unidad_medida, color, cantidad, precio_venta, igv,precio_igv, descuento, subtotal, ficha_tecnica_producto) 
                VALUES ('$idcompra_proyecto', '$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]', '$nombre_color[$num_elementos]', '$cantidad[$num_elementos]', '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_total[$num_elementos]', '$descuento[$num_elementos]', '$subtotal_producto', '$ficha_tecnica_producto[$num_elementos]')";
        ejecutarConsulta($sql_detalle) or ($sw = false);

        $num_elementos = $num_elementos + 1;
      }
    }

    if ($idcompra_proyecto != "") {
      return $sw;
    } else {
      return false;
    }
  }

  public function mostrar_compra_para_editar($id_compras_x_proyecto)
  {
    $sql = "SELECT  cpp.idcompra_proyecto as idcompra_proyecto, 
         cpp.idproyecto, cpp.idproveedor, cpp.fecha_compra, 
         cpp.tipo_comprovante as tipo_comprobante, 
         cpp.serie_comprovante as serie_comprobante, 
         cpp.descripcion as descripcion, 
         cpp.subtotal_compras_proyect as subtotal_compras, 
         cpp.igv_compras_proyect as igv_compras_proyect, 
         cpp.monto_total as monto_total,
         cpp.estado as estado
         FROM compra_por_proyecto as cpp
         WHERE idcompra_proyecto='$id_compras_x_proyecto';";

    $compra = ejecutarConsultaSimpleFila($sql);

    $sql_2 = "SELECT 	dc.idproducto as idproducto,
		dc.ficha_tecnica_producto as ficha_tecnica,
		dc.cantidad as cantidad,
		dc.precio_venta as precio_venta, dc.igv, dc.precio_igv,
		dc.descuento as descuento,
		p.nombre as nombre_producto, p.imagen,
        dc.unidad_medida, dc.color
		FROM detalle_compra AS dc, producto AS p, unidad_medida AS um, color AS c
		WHERE idcompra_proyecto='$id_compras_x_proyecto' AND  dc.idproducto=p.idproducto AND p.idcolor = c.idcolor AND p.idunidad_medida = um.idunidad_medida;";

    $producto = ejecutarConsultaArray($sql_2);

    $results = [
      "idcompra_x_proyecto" => $compra['idcompra_proyecto'],
      "idproyecto" => $compra['idproyecto'],
      "idproveedor" => $compra['idproveedor'],
      "fecha_compra" => $compra['fecha_compra'],
      "tipo_comprobante" => $compra['tipo_comprobante'],
      "serie_comprobante" => $compra['serie_comprobante'],
      "descripcion" => $compra['descripcion'],
      "subtotal_compras" => $compra['subtotal_compras'],
      "igv_compras_proyect" => $compra['igv_compras_proyect'],
      "monto_total" => $compra['monto_total'],
      "estado" => $compra['estado'],
      "producto" => $producto,
    ];

    return $results;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idcompra_proyecto)
  {
    $sql = "UPDATE compra_por_proyecto SET estado='0' WHERE idcompra_proyecto='$idcompra_proyecto'";

    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar($idcompra_por_proyecto)
  {
    $sql = "UPDATE compra_por_proyecto SET estado='1' WHERE idcompra_proyecto='$idcompra_por_proyecto'";

    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idcompra_por_proyecto)
  {
    $sql = "SELECT * FROM compra_por_proyecto WHERE idcompra_por_proyecto='$idcompra_por_proyecto'";

    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function listar_compra($nube_idproyecto)
  {
    // $idproyecto=2;
    $sql = "SELECT
		cpp.idproyecto as idproyecto,
		cpp.idcompra_proyecto as idcompra_proyecto,
		cpp.idproveedor as idproveedor,
		cpp.fecha_compra as fecha_compra,
		cpp.tipo_comprovante as tipo_comprovante,
		cpp.serie_comprovante as serie_comprovante,
		cpp.descripcion as descripcion,
		cpp.monto_total as monto_total,
		cpp.imagen_comprobante as imagen_comprobante,
		cpp.estado_detraccion as estado_detraccion,
		p.razon_social as razon_social, p.telefono,
		cpp.estado as estado
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproyecto='$nube_idproyecto' AND cpp.idproveedor=p.idproveedor
		ORDER BY cpp.fecha_compra DESC ";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros x proveedor
  public function listar_compraxporvee($nube_idproyecto)
  {
    // $idproyecto=2;
    $sql = "SELECT
		cpp.idproyecto as idproyecto,
        COUNT(cpp.idcompra_proyecto) as cantidad,
        SUM(cpp.monto_total) as total,
        p.idproveedor as idproveedor,
        p.razon_social as razon_social
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproyecto='$nube_idproyecto' AND cpp.idproveedor=p.idproveedor GROUP BY cpp.idproveedor";
    return ejecutarConsulta($sql);
  }
  //Implementar un método para listar los registros x proveedor
  public function listar_detalle_comprax_provee($idproyecto, $idproveedor)
  {
    //var_dump($idproyecto,$idproveedor);die();
    // $idproyecto=2;
    $sql = "SELECT* FROM compra_por_proyecto WHERE idproyecto='$idproyecto' AND idproveedor='$idproveedor'";
    return ejecutarConsulta($sql);
  }
  //mostrar detalles uno a uno de la factura
  public function ver_compra($idcompra_proyecto)
  {
    $sql = "SELECT  
		cpp.idcompra_proyecto as idcompra_proyecto, 
		cpp.idproyecto as idproyecto, 
		cpp.idproveedor as idproveedor, 
		p.razon_social as razon_social, 
		cpp.fecha_compra as fecha_compra, 
		cpp.tipo_comprovante as tipo_comprovante, 
		cpp.serie_comprovante as serie_comprovante, 
		cpp.descripcion as descripcion, 
		cpp.subtotal_compras_proyect as subtotal_compras, 
		cpp.igv_compras_proyect as igv_compras_proyect, 
		cpp.monto_total as monto_total,
		cpp.fecha as fecha, 
		cpp.estado as estado
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE idcompra_proyecto='$idcompra_proyecto'  AND cpp.idproveedor = p.idproveedor";

    return ejecutarConsultaSimpleFila($sql);
  }
  //lismatamos los detalles
  public function listarDetalle($id_compra)
  {
    $sql = "SELECT 
		dp.idproducto as idproducto,
		dp.ficha_tecnica_producto as ficha_tecnica,
		dp.cantidad as cantidad,
		dp.precio_venta as precio_venta,
		dp.descuento as descuento,
		p.nombre as nombre
		FROM detalle_compra  dp, producto as p
		WHERE idcompra_proyecto='$id_compra' AND  dp.idproducto=p.idproducto";

    return ejecutarConsulta($sql);
  }

  //pago servicio
  public function pago_servicio($idcompra_proyecto)
  {
    $sql = "SELECT SUM(monto) as total_pago_compras
		FROM pago_compras 
		WHERE idcompra_proyecto='$idcompra_proyecto' AND estado=1";
    return ejecutarConsultaSimpleFila($sql);
  }

  //----Comprobantes pagos-----

  public function editar_comprobante($comprobante_c, $doc_comprobante)
  {
    //var_dump($idfacturacompra);die();
    $sql = "UPDATE compra_por_proyecto SET imagen_comprobante='$doc_comprobante' WHERE idcompra_proyecto ='$comprobante_c'";
    return ejecutarConsulta($sql);
  }
  // obtebnemos los DOCS para eliminar
  public function obtener_comprobante($comprobante_c)
  {
    $sql = "SELECT imagen_comprobante FROM compra_por_proyecto WHERE idcompra_proyecto ='$comprobante_c'";

    return ejecutarConsulta($sql);
  }

  /**=========================== */
  //SECCION PAGOS
  /**=========================== */
  public function insertar_pago( $idcompra_proyecto_p,  $idproveedor_pago, $beneficiario_pago, $forma_pago, $tipo_pago, $cuenta_destino_pago,
    $banco_pago, $titular_cuenta_pago, $fecha_pago,  $monto_pago,  $numero_op_pago,  $descripcion_pago, $imagen1  ) {
    // var_dump($idcompra_proyecto_p,$idproveedor_pago,$beneficiario_pago,$forma_pago,$tipo_pago,$cuenta_destino_pago,$banco_pago, $titular_cuenta_pago,$fecha_pago,$monto_pago,$numero_op_pago,$descripcion_pago,$imagen1);die();
    ///idproyecto
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
  public function listar_pagos($idcompra_proyecto)
  {
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
		WHERE ps.idcompra_proyecto='$idcompra_proyecto' AND bn.idbancos=ps.idbancos";
    return ejecutarConsulta($sql);
  }
  //Listar pagos1-con detraccion --tabla Proveedor
  public function listar_pagos_compra_prov_con_dtracc($idcompra_proyecto, $tipo_pago)
  {
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
        WHERE ps.idcompra_proyecto='$idcompra_proyecto' AND bn.idbancos=ps.idbancos AND ps.tipo_pago='$tipo_pago'";
    return ejecutarConsulta($sql);
  }
  //Implementamos un método para desactivar categorías
  public function desactivar_pagos($idpago_compras)
  {
    //var_dump($idpago_compras);die();
    $sql = "UPDATE pago_compras SET estado='0' WHERE idpago_compras ='$idpago_compras'";
    return ejecutarConsulta($sql);
  }
  //Implementamos un método para activar categorías
  public function activar_pagos($idpago_compras)
  {
    $sql = "UPDATE pago_compras SET estado='1' WHERE idpago_compras ='$idpago_compras'";
    return ejecutarConsulta($sql);
  }
  //Mostrar datos para editar Pago servicio.
  public function mostrar_pagos($idpago_compras)
  {
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
  public function suma_total_pagos($idcompra_proyecto)
  {
    $sql = "SELECT SUM(ps.monto) as total_monto
		FROM pago_compras as ps
		WHERE  ps.idcompra_proyecto='$idcompra_proyecto' AND ps.estado='1'";
    return ejecutarConsultaSimpleFila($sql);
  }
  //consultas para totales con detracion
  public function suma_total_pagos_detraccion($idcompra_proyecto, $tipopago)
  {
    $sql = "SELECT SUM(ps.monto) as total_montoo
		FROM pago_compras as ps
		WHERE  ps.idcompra_proyecto='$idcompra_proyecto' AND ps.tipo_pago='$tipopago' AND ps.estado='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function total_costo_parcial_pago($idmaquinaria, $idproyecto)
  {
    $sql = "SELECT
		SUM(s.costo_parcial) as costo_parcial  
		FROM servicio as s 
		WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' AND s.estado='1'";

    return ejecutarConsultaSimpleFila($sql);
  }
  // obtebnemos los DOCS para eliminar
  public function obtenerImg($idpago_compras)
  {
    $sql = "SELECT imagen FROM pago_compras WHERE idpago_compras='$idpago_compras'";

    return ejecutarConsulta($sql);
  }
  //mostrar datos del proveedor y maquina en form
  public function most_datos_prov_pago($idcompra_proyecto)
  {
    $sql = " SELECT * FROM compra_por_proyecto as cpp, proveedor as p  WHERE cpp.idproveedor=p.idproveedor AND cpp.idcompra_proyecto='$idcompra_proyecto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Listamos los productos a selecionar
  public function listar_productos()
  {
    $sql = "SELECT
            p.idproducto AS idproducto,
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
        ORDER BY p.nombre ASC";

    return ejecutarConsulta($sql);
  }

  	//Implementamos un método para insertar registros
	public function insertar_material($unidad_medida, $color, $idcategoria, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion,  $imagen)
	{
		// var_dump($unidad_medida, $color, $idcategoria, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion,  $imagen); die;
		$sql = "INSERT INTO producto(idunidad_medida, idcolor, idcategoria_insumos_af, nombre, modelo, serie, marca, estado_igv, precio_unitario, precio_igv, precio_sin_igv, precio_total, ficha_tecnica, descripcion, imagen) 
		VALUES ('$unidad_medida', '$color', '$idcategoria', '$nombre', '$modelo', '$serie', '$marca', '$estado_igv', '$precio_unitario', '$precio_igv', '$precio_sin_igv', '$precio_total', '$ficha_tecnica', '$descripcion', '$imagen')";
    	return ejecutarConsulta($sql);
			
	}

  //Select2 Proveedor
  public function select2_proveedor()
  {
    $sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE estado='1'";
    return ejecutarConsulta($sql);
  }

  //Select2 banco
  public function select2_banco()
  {
    $sql = "SELECT idbancos as id, nombre, alias FROM bancos WHERE estado='1'  ORDER BY idbancos ASC;";
    return ejecutarConsulta($sql);
  }

  //Select2 color
  public function select2_color()
  {
    $sql = "SELECT idcolor AS id, nombre_color AS nombre FROM color WHERE estado='1' ORDER BY idcolor ASC;";
    return ejecutarConsulta($sql);
  }

  //Select2 unidad medida
  public function select2_unidad_medida()
  {
    $sql = "SELECT idunidad_medida AS id, nombre_medida AS nombre, abreviacion FROM unidad_medida WHERE estado='1' ORDER BY nombre_medida ASC;";
    return ejecutarConsulta($sql);
  }

  //Select2 categoria
  public function select2_categoria()
  {
    $sql = "SELECT idcategoria_insumos_af as id, nombre FROM categoria_insumos_af WHERE estado='1' ORDER BY idcategoria_insumos_af ASC;";
    return ejecutarConsulta($sql);
  }

  //CAPTURAR PERSONA  DE RENIEC
  public function datos_reniec($dni)
  {
    $url = "https://dniruc.apisperu.com/api/v1/dni/" . $dni . "?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
    //  Iniciamos curl
    $curl = curl_init();
    // Desactivamos verificación SSL
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    // Devuelve respuesta aunque sea falsa
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // Especificamo los MIME-Type que son aceptables para la respuesta.
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    // Establecemos la URL
    curl_setopt($curl, CURLOPT_URL, $url);
    // Ejecutmos curl
    $json = curl_exec($curl);
    // Cerramos curl
    curl_close($curl);

    $respuestas = json_decode($json, true);

    return $respuestas;
  }

  //CAPTURAR PERSONA  DE RENIEC
  public function datos_sunat($ruc)
  {
    $url = "https://dniruc.apisperu.com/api/v1/ruc/" . $ruc . "?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
    //  Iniciamos curl
    $curl = curl_init();
    // Desactivamos verificación SSL
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    // Devuelve respuesta aunque sea falsa
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // Especificamo los MIME-Type que son aceptables para la respuesta.
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    // Establecemos la URL
    curl_setopt($curl, CURLOPT_URL, $url);
    // Ejecutmos curl
    $json = curl_exec($curl);
    // Cerramos curl
    curl_close($curl);

    $respuestas = json_decode($json, true);

    return $respuestas;
  }
}

?>
