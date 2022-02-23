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
	public function listar_tbla_principal($idproyecto)
	{
		$sql="SELECT cpp.idproyecto, cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, um.nombre_medida, c.nombre_color, pr.nombre AS nombre_producto, pr.imagen, pr.precio_total AS precio_actual, SUM(dc.cantidad) AS cantidad_total, SUM(dc.precio_igv) AS precio_con_igv, SUM(dc.descuento) AS descuento_total, SUM(dc.subtotal) precio_total , COUNT(dc.idproducto) AS count_productos, AVG(dc.precio_igv) AS promedio_precio
    FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, unidad_medida AS um, color AS c 
    WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto AND um.idunidad_medida  = pr.idunidad_medida  AND c.idcolor = pr.idcolor  AND cpp.idproyecto = '$idproyecto' AND cpp.estado = '1'
    GROUP BY dc.idproducto;";

		return ejecutarConsulta($sql);		
	}

	public function ver_precios_y_mas($idproyecto, $idproducto)
	{
		$sql="SELECT cpp.idcompra_proyecto, cpp.fecha_compra, dc.ficha_tecnica_producto AS ficha_tecnica, 
		pr.nombre AS nombre_producto, dc.cantidad, 
		dc.precio_igv, dc.descuento, dc.subtotal, prov.razon_social AS proveedor
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto 
		AND dc.idproducto = pr.idproducto AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' 
		AND cpp.idproveedor = prov.idproveedor AND dc.idproducto = '$idproducto' 
		ORDER BY cpp.fecha_compra DESC;";

		return ejecutarConsulta($sql);		
	}

	public function suma_total_compras($idproyecto)	{

		$sql = "SELECT SUM( dc.subtotal ) AS suma_total_compras, SUM( dc.cantidad ) AS suma_total_productos
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1';";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>