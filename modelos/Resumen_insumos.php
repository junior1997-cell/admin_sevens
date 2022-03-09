<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class ResumenInsumos
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	
	//Implementar un método para listar los registros
	public function tbla_principal($idproyecto){

		$sql="SELECT cpp.idproyecto, cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, um.nombre_medida, 
		c.nombre_color, pr.nombre AS nombre_producto, pr.modelo, pr.marca, pr.imagen, pr.precio_total AS precio_actual, 
		SUM(dc.cantidad) AS cantidad_total, SUM(dc.precio_con_igv) AS precio_con_igv, SUM(dc.descuento) AS descuento_total, 
		SUM(dc.subtotal) precio_total , COUNT(dc.idproducto) AS count_productos, AVG(dc.precio_con_igv) AS promedio_precio
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, unidad_medida AS um, color AS c 
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
		AND um.idunidad_medida  = pr.idunidad_medida  AND c.idcolor = pr.idcolor  AND cpp.idproyecto = '$idproyecto' 
		AND pr.idcategoria_insumos_af = '1' AND cpp.estado = '1' AND cpp.estado_delete = '1'
		GROUP BY dc.idproducto ORDER BY pr.nombre ASC;";

		return ejecutarConsulta($sql);		
	}

	public function tbla_facturas($idproyecto, $idproducto)	{

		$sql="SELECT cpp.idcompra_proyecto, cpp.fecha_compra, dc.ficha_tecnica_producto AS ficha_tecnica, 
		pr.nombre AS nombre_producto, dc.cantidad, 
		dc.precio_con_igv, dc.descuento, dc.subtotal, prov.razon_social AS proveedor
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto 
		AND dc.idproducto = pr.idproducto AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1'
		AND cpp.idproveedor = prov.idproveedor AND dc.idproducto = '$idproducto' 
		ORDER BY cpp.fecha_compra DESC;";

		return ejecutarConsulta($sql);		
	}

	public function sumas_factura_x_material($idproyecto, $idproducto)	{

		$sql="SELECT  SUM(dc.cantidad) AS cantidad, AVG(dc.precio_con_igv) AS precio_promedio, SUM(dc.descuento) AS descuento, SUM(dc.subtotal) AS subtotal
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto 
		AND dc.idproducto = pr.idproducto AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1'
		AND cpp.idproveedor = prov.idproveedor AND dc.idproducto = '$idproducto' 
		ORDER BY cpp.fecha_compra DESC;";

		return ejecutarConsultaSimpleFila($sql);		
	}

	public function suma_total_compras($idproyecto)	{

		$sql = "SELECT SUM( dc.subtotal ) AS suma_total_compras, SUM( dc.cantidad ) AS suma_total_productos
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
		AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1';";
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

	//Seleccionar 
	public function obtenerImgPerfilProducto($idproducto)
	{
	  $sql = "SELECT imagen FROM producto WHERE idproducto='$idproducto'";
	  return ejecutarConsulta($sql);
	}

}

?>