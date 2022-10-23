<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ResumenActivoFijo
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //TBLA 1 ------------------------------------------------------
  public function tbla_principal($idproyecto, $id_clasificacion) {

    $resumen_producto = [];

    $tipo = $id_clasificacion == 'todos' || empty($id_clasificacion) ? 'AND pr.idcategoria_insumos_af != 1' : "AND pr.idcategoria_insumos_af = '$id_clasificacion'";

    $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, um.nombre_medida, 
		pr.nombre AS nombre_producto, pr.modelo, pr.marca, pr.imagen, pr.precio_total AS precio_actual, SUM(dc.cantidad) AS cantidad_total, 
		SUM(dc.precio_con_igv) AS precio_con_igv, SUM(dc.descuento) AS descuento_total, SUM(dc.subtotal) precio_total , 
		COUNT(dc.idproducto) AS count_productos, AVG(dc.precio_con_igv) AS promedio_precio, cg.nombre as grupo
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, unidad_medida AS um, clasificacion_grupo AS cg
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto  AND dc.idproducto = pr.idproducto 
		AND um.idunidad_medida  = pr.idunidad_medida AND dc.idclasificacion_grupo = cg.idclasificacion_grupo AND cpp.idproyecto = '$idproyecto' 
    $tipo
    AND cpp.estado = '1' AND cpp.estado_delete = '1' GROUP BY dc.idproducto ORDER BY pr.nombre ASC;";

    $producto = ejecutarConsultaArray($sql); if ($producto['status'] == false) { return $producto; }

    foreach ($producto['data'] as $key => $value) {
      $id = $value['idproducto']; 
      $sql_2 = "SELECT m.nombre_marca FROM  detalle_marca as dm, marca as m WHERE  dm.idmarca = m.idmarca AND dm.idproducto = '$id'";
      $marcas = ejecutarConsultaArray($sql_2); if ($marcas['status'] == false) { return $marcas; }
      $html_marca = "";
      foreach ($marcas['data'] as $key => $value2) { $html_marca .=  '<li >'.$value2['nombre_marca'].'. </li>'; }

      $resumen_producto[] = [
        'idproyecto'        => $value['idproyecto'],
        'idcompra_proyecto' => $value['idcompra_proyecto'],
        'iddetalle_compra'  => $value['iddetalle_compra'],
        'idproducto'        => $value['idproducto'],
        'nombre_medida'     => $value['nombre_medida'],
        'nombre_producto'   => $value['nombre_producto'],
        'modelo'            => $value['modelo'],
        'marca'             => $value['marca'],
        'grupo'             => $value['grupo'],
        'imagen'            => $value['imagen'],
        'precio_actual'     => $value['precio_actual'],
        'cantidad_total'    => $value['cantidad_total'],
        'precio_con_igv'    => $value['precio_con_igv'],
        'descuento_total'   => $value['descuento_total'],
        'precio_total'      => $value['precio_total'],
        'count_productos'   => $value['count_productos'],
        'promedio_precio'   => $value['promedio_precio'],
        
        'html_marca'        =>'<ol class="pl-3">'.$html_marca. '</ol>'
      ];
    }
    return $retorno = ['status' => true, 'data' => $resumen_producto, 'message' => 'todo bien'];
  }
  // MOSTRAR
  public function suma_total_maquinaria($idproyecto)
  {
    $sql = "SELECT SUM( dc.subtotal ) AS suma_total_compras, SUM( dc.cantidad ) AS suma_total_productos
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto 
		AND dc.idproducto = pr.idproducto AND cpp.idproyecto ='$idproyecto' AND pr.idcategoria_insumos_af = '2' AND cpp.estado = '1' AND cpp.estado_delete = '1';";
    return ejecutarConsultaSimpleFila($sql);
  }

  // TABLA FACTURA ------------------------------------------------------
  public function tbla_facturas($idproyecto, $idproducto) {
    $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, cpp.fecha_compra, dc.ficha_tecnica_producto AS ficha_tecnica, 
		pr.nombre AS nombre_producto, dc.cantidad, cpp.tipo_comprobante, cpp.serie_comprobante,
		dc.idproducto, dc.precio_con_igv, dc.descuento, dc.subtotal, prov.razon_social AS proveedor
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
    AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1'
		AND cpp.idproveedor = prov.idproveedor AND dc.idproducto = '$idproducto' 
		ORDER BY cpp.fecha_compra DESC;";

		$compra = ejecutarConsultaArray($sql);

		if ($compra['status'] == false) { return $compra; }

    foreach ($compra['data'] as $key => $value) {

      $id = $value['idcompra_proyecto'];
      $idproducto = $value['idproducto'];
    
      $sql3 = "SELECT COUNT(comprobante) as cant_comprobantes FROM factura_compra_insumo WHERE idcompra_proyecto='$id' AND estado='1' AND estado_delete='1'";
      $cant_comprobantes = ejecutarConsultaSimpleFila($sql3);
      if ($cant_comprobantes['status'] == false) { return $cant_comprobantes; }

      //listar detalle_marca
      $sql = "SELECT dm.iddetalle_marca, dm.idproducto, dm.idmarca, m.nombre_marca as marca 
      FROM detalle_marca as dm, marca as m 
      WHERE dm.idmarca = m.idmarca AND dm.idproducto = '$idproducto' AND dm.estado='1' AND dm.estado_delete='1' ORDER BY dm.iddetalle_marca ASC;";
      $detalle_marca = ejecutarConsultaArray($sql);   if ($detalle_marca['status'] == false){ return $detalle_marca; }
      
      $marcas_html = ""; $datalle_marcas_export = "";
      foreach ($detalle_marca['data'] as $key => $value2) {
        $marcas_html .=  '<li >'.$value2['marca'].'</li>';
        $datalle_marcas_export .=  '<li>  -'.$value2['marca'].'</li>';
      }
    
      $data[] = [
        'idproyecto'        => $value['idproyecto'],
        'idcompra_proyecto' => $value['idcompra_proyecto'],
        'fecha_compra'      => $value['fecha_compra'],
        'ficha_tecnica'     => $value['ficha_tecnica'],
        'idproducto'        => $value['idproducto'],
        'nombre_producto'   => $value['nombre_producto'],
        'cantidad'          => $value['cantidad'],
        'tipo_comprobante'  => $value['tipo_comprobante'],
        'serie_comprobante' => $value['serie_comprobante'],
        'precio_con_igv'    => $value['precio_con_igv'],
        'descuento'         => $value['descuento'],
        'subtotal'          => $value['subtotal'],
        'proveedor'         => $value['proveedor'],

        'html_marca'        => '<ol class="pl-3">'.$marcas_html. '. </ol>',
        'marca_export'      => $datalle_marcas_export,
        'cant_comprobantes' => (empty($cant_comprobantes['data']['cant_comprobantes']) ? 0 : floatval($cant_comprobantes['data']['cant_comprobantes']) ),
      ];
    }
    // var_dump($data);die();
    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data, 'affected_rows' =>$compra['affected_rows'],  ] ;

  }

  public function sumas_factura_x_material($idproyecto, $idproducto)  {
    $sql = "SELECT  SUM(dc.cantidad) AS cantidad, AVG(dc.precio_con_igv) AS precio_promedio, SUM(dc.descuento) AS descuento, SUM(dc.subtotal) AS subtotal
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto 
		AND dc.idproducto = pr.idproducto AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1'
		AND cpp.idproveedor = prov.idproveedor AND dc.idproducto = '$idproducto' 
		ORDER BY cpp.fecha_compra DESC;";

    return ejecutarConsultaSimpleFila($sql);
  }

  public function listar_productos() {
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
