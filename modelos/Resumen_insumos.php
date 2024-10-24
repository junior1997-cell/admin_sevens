<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ResumenInsumos
{
  //Implementamos nuestro variable global
	public $id_usr_sesion;

  //Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

  //Implementar un método para listar los registros
  public function tbla_principal($idproyecto) {

    $resumen_producto = [];
    $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, um.nombre_medida,  um.nombre_medida, um.abreviacion,
		pr.nombre AS nombre_producto, pr.modelo, pr.marca, cg.idclasificacion_grupo, cg.nombre as grupo,  pr.imagen, pr.precio_total AS precio_actual,
		SUM(dc.cantidad) AS cantidad_total, SUM(dc.precio_con_igv) AS precio_con_igv, SUM(dc.descuento) AS descuento_total, 
		SUM(dc.subtotal) precio_total , COUNT(dc.idproducto) AS count_productos, AVG(dc.precio_con_igv) AS promedio_precio
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, clasificacion_grupo AS cg, unidad_medida AS um 
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto
    AND um.idunidad_medida  = pr.idunidad_medida AND dc.idclasificacion_grupo = cg.idclasificacion_grupo
    AND cpp.idproyecto = '$idproyecto'  AND pr.idcategoria_insumos_af = '1' 
    AND cpp.estado = '1' AND cpp.estado_delete = '1' GROUP BY dc.idproducto ORDER BY pr.nombre ASC;";

    $producto = ejecutarConsultaArray($sql); if ($producto['status'] == false) { return $producto; }

    foreach ($producto['data'] as $key => $value) {
      $id = $value['idproducto']; 
      $sql_2 = "SELECT m.nombre_marca FROM  detalle_marca as dm, marca as m WHERE  dm.idmarca = m.idmarca AND dm.idproducto = '$id'";
      $marcas = ejecutarConsultaArray($sql_2); if ($marcas['status'] == false) { return $marcas; }
      $html_marca = "";
      foreach ($marcas['data'] as $key => $value2) { $html_marca .=  '<li >'.$value2['nombre_marca'].'. </li>'; }

      $sql = "SELECT  AVG(dc.precio_con_igv) AS promedio_precio, COUNT(dc.idcompra_proyecto) as cantidad 
      FROM detalle_compra as dc, compra_por_proyecto as cpp 
      WHERE dc.idcompra_proyecto = cpp.idcompra_proyecto AND dc.idproducto='$id' AND cpp.idproyecto ='$idproyecto' 
      AND cpp.estado = '1' AND cpp.estado_delete = '1';";
      $cant_fact = ejecutarConsultaSimpleFila($sql);  if ($cant_fact['status'] == false){ return $cant_fact; }

      $resumen_producto[] = [
        'idproyecto'        => $value['idproyecto'],
        'idcompra_proyecto' => $value['idcompra_proyecto'],
        'iddetalle_compra'  => $value['iddetalle_compra'],
        'idproducto'        => $value['idproducto'],
        'nombre_medida'     => $value['nombre_medida'],
        'abreviacion'     => $value['abreviacion'],
        'nombre_producto'   => $value['nombre_producto'],
        'modelo'            => $value['modelo'],
        'marca'             => $value['marca'],
        'idclasificacion_grupo'=> $value['idclasificacion_grupo'],
        'grupo'             => $value['grupo'],
        'imagen'            => $value['imagen'],
        'precio_actual'     => $value['precio_actual'],
        'cantidad_total'    => $value['cantidad_total'],
        'precio_con_igv'    => $value['precio_con_igv'],
        'descuento_total'   => $value['descuento_total'],
        'precio_total'      => $value['precio_total'],
        'count_productos'   => $value['count_productos'],
        'promedio_precio'   => $value['promedio_precio'],

        'cant_fact'         => $cant_fact['data']['cantidad'],
        
        'html_marca'        =>'<ol class="pl-3">'.$html_marca. '</ol>'
      ];
    }
    return $retorno = ['status' => true, 'data' => $resumen_producto, 'message' => 'todo bien'];
  }

  public function tbla_facturas($idproyecto, $idproducto) {
    $sql = "SELECT cpp.idproyecto,cpp.idcompra_proyecto, cpp.fecha_compra, cpp.tipo_comprobante, cpp.serie_comprobante, 
    dc.ficha_tecnica_producto AS ficha_tecnica, dc.idproducto, pr.nombre AS nombre_producto, dc.cantidad, dc.marca,
		dc.precio_con_igv, dc.descuento, dc.subtotal, prov.razon_social AS proveedor
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto 
		AND dc.idproducto = pr.idproducto AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1'
		AND cpp.idproveedor = prov.idproveedor AND dc.idproducto = '$idproducto' 
		ORDER BY cpp.fecha_compra DESC;";
    // return ejecutarConsulta($sql);
    $compra = ejecutarConsultaArray($sql); if ($compra['status'] == false) { return $compra; }

    foreach ($compra['data'] as $key => $value) {
      $idcompra_proyecto = $value['idcompra_proyecto'];
      $idproducto = $value['idproducto'];

      $sql3 = "SELECT COUNT(comprobante) as cant_comprobantes FROM factura_compra_insumo WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
      $cant_comprobantes = ejecutarConsultaSimpleFila($sql3); if ($cant_comprobantes['status'] == false) { return $cant_comprobantes; }      

      $data[] = [
        'idproyecto'        => $value['idproyecto'],
        'idcompra_proyecto' => $value['idcompra_proyecto'],
        'fecha_compra'      => $value['fecha_compra'],
        'ficha_tecnica'     => $value['ficha_tecnica'],
        'idproducto'        => $value['idproducto'],
        'nombre_producto'   => $value['nombre_producto'],
        'cantidad'          => $value['cantidad'],
        'marca'             => empty($value['marca']) ? 'SIN MARCA' : $value['marca'],
        'tipo_comprobante'  => $value['tipo_comprobante'],
        'serie_comprobante' => $value['serie_comprobante'],
        'precio_con_igv'    => $value['precio_con_igv'],
        'descuento'         => $value['descuento'],
        'subtotal'          => $value['subtotal'],
        'proveedor'         => $value['proveedor'],

        'cant_comprobantes' => empty($cant_comprobantes['data']['cant_comprobantes']) ? 0 : floatval($cant_comprobantes['data']['cant_comprobantes']),
      ];
    }

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' => $data, 'affected_rows' => $compra['affected_rows']];
  }

  public function sumas_factura_x_material($idproyecto, $idproducto)
  {
    $sql = "SELECT  SUM(dc.cantidad) AS cantidad, AVG(dc.precio_con_igv) AS precio_promedio, SUM(dc.descuento) AS descuento, SUM(dc.subtotal) AS subtotal
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto 
		AND dc.idproducto = pr.idproducto AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1'
		AND cpp.idproveedor = prov.idproveedor AND dc.idproducto = '$idproducto' 
		ORDER BY cpp.fecha_compra DESC;";

    return ejecutarConsultaSimpleFila($sql);
  }

  public function suma_total_compras($idproyecto)
  {
    $sql = "SELECT SUM( dc.subtotal ) AS suma_total_compras, SUM( dc.cantidad ) AS suma_total_productos
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
		AND pr.idcategoria_insumos_af = '1' AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1';";
    return ejecutarConsultaSimpleFila($sql);
  }

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
		AND p.estado = '1' AND p.estado_delete = '1'
        ORDER BY p.nombre ASC";

    return ejecutarConsulta($sql);
  }

  //Seleccionar
  public function obtenerImgPerfilProducto($idproducto)
  {
    $sql = "SELECT imagen FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Seleccionar
  public function actualizar_grupo($idproducto, $idgrupo, $idproyecto_grp)
  {

    //var_dump($idproducto, $idgrupo, $idproyecto_grp); die();
    $sql = "UPDATE detalle_compra SET idclasificacion_grupo='$idgrupo' WHERE idproducto = '$idproducto'";

    $upd_grupo =  ejecutarConsulta($sql);

    if ( $upd_grupo['status'] == false ) { return $upd_grupo; }
    

    if ($idgrupo==11) {
      $sql_u = "UPDATE almacen_resumen SET tipo='EPP' WHERE idproyecto='$idproyecto_grp' and idproducto='$idproducto'";
      $up_gr_tbl_almacen_r = ejecutarConsulta($sql_u);
      if ( $up_gr_tbl_almacen_r['status'] == false ) { return $up_gr_tbl_almacen_r; }
    }else {
      $sql_u = "UPDATE almacen_resumen SET tipo='PN' WHERE idproyecto='$idproyecto_grp' and idproducto='$idproducto'";
      $up_gr_tbl_almacen_r = ejecutarConsulta($sql_u);
      if ( $up_gr_tbl_almacen_r['status'] == false ) { return $up_gr_tbl_almacen_r; }
    }

    return $upd_grupo;
  }
}

?>
