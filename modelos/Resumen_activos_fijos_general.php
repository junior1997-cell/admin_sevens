<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Resumen_activos_fijos_general
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	
	//Implementar un método para listar los registros
	public function listar_tbla_principal_general($clacificacion)
	{
		$data_productos = array(); 
		$sql_1="SELECT p.idproducto,p.nombre,p.imagen,p.precio_total as precio_actual,um.nombre_medida, c.nombre_color
		FROM producto as p, unidad_medida as um, color as c
		WHERE p.idunidad_medida=um.idunidad_medida AND p.idcolor=c.idcolor AND p.idcategoria_insumos_af='$clacificacion'";

		$producto = ejecutarConsultaArray($sql_1);

		if (!empty($producto)) {

			foreach ($producto as $key => $value) {

				$cantidad = 0; $descuento=0; $subtotal=0; $promedio_precio = 0; $promedio_total=0;
				
				$idproducto= $value['idproducto'];
				$sql_2="SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_igv`) AS promedio_precio 
				FROM `detalle_compra` WHERE idproducto=$idproducto";
				$compra_p = ejecutarConsultaSimpleFila($sql_2);

				$cantidad += (empty($compra_p['cantidad'])) ? 0:floatval($compra_p['cantidad']) ;
				$descuento += (empty($compra_p['descuento'])) ? 0: floatval($compra_p['descuento']);
				$subtotal += (empty($compra_p['subtotal'])) ? 0: floatval($compra_p['subtotal']);
				$promedio_precio += (empty($compra_p['promedio_precio'])) ? 0: floatval($compra_p['promedio_precio']) ;

				$sql_3 ="SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_con_igv`) AS promedio_precio 
				FROM `detalle_compra_af_g` WHERE idproducto=$idproducto";
				$compra_af_g= ejecutarConsultaSimpleFila($sql_3);

				$cantidad += (empty($compra_af_g['cantidad'])) ? 0: floatval($compra_af_g['cantidad']);
				$descuento += (empty($compra_af_g['descuento'])) ? 0: floatval($compra_af_g['descuento']) ;
				$subtotal += (empty($compra_af_g['subtotal'])) ? 0: floatval($compra_af_g['subtotal']) ;
				$promedio_precio += (empty($compra_af_g['promedio_precio'])) ? 0: floatval($compra_af_g['promedio_precio']) ;

				if ($compra_p['promedio_precio']!=0 && $compra_af_g['promedio_precio'] ) {
					$promedio_total=$promedio_precio/2;
				}else{
					$promedio_total=$promedio_precio;
				}

				$data_productos[]=array(

					"idproducto"      =>$value['idproducto'],
					"nombre_producto" =>$value['nombre'],
					"imagen"          =>$value['imagen'],
					"precio_actual"   =>$value['precio_actual'],
					"nombre_medida"   =>$value['nombre_medida'],
					"nombre_color"    =>$value['nombre_color'],
					"cantidad"        =>$cantidad,
					"descuento"       =>$descuento,
					"subtotal"        =>$subtotal,
					"promedio_precio" =>$promedio_total
		
				);

			}

			return $data_productos;
		}

	}

	public function ver_precios_y_mas($idproducto)
	{
		$a = array(); $b = array(); 
		$sql_1="SELECT  cafg.idcompra_af_general, cafg.fecha_compra, dcafg.ficha_tecnica_producto AS ficha_tecnica, 
		pr.nombre AS nombre_producto, dcafg.cantidad, dcafg.precio_con_igv , dcafg.descuento , dcafg.subtotal, prov.razon_social AS proveedor
		FROM compra_af_general AS cafg, detalle_compra_af_g AS dcafg, producto AS pr,proveedor AS prov
		WHERE cafg.idcompra_af_general = dcafg.idcompra_af_general AND dcafg.idproducto = pr.idproducto AND cafg.estado = '1' AND cafg.idproveedor = prov.idproveedor 
		AND dcafg.idproducto = '$idproducto' ORDER BY cafg.fecha_compra DESC";

        $compra_af_general =  ejecutarConsultaArray($sql_1);

		if (!empty($compra_af_general)) {

			foreach ($compra_af_general as $key => $value) {

				$a[]=array(
					'idproyecto'=>'',
					'idcompra'=>$value['idcompra_af_general'],
					'fecha_compra'=>$value['fecha_compra'],
					'ficha_tecnica'=>$value['ficha_tecnica'],
					'nombre_producto'=>$value['nombre_producto'],
					'cantidad'=>$value['cantidad'],
					'precio_con_igv'=>$value['precio_con_igv'],
					'descuento'=>$value['descuento'],
					'subtotal'=>$value['subtotal'],
					'proveedor'=>$value['proveedor']
				);
			}
		}

		$sql_2="SELECT cpp.idproyecto,cpp.idcompra_proyecto, cpp.fecha_compra, dc.ficha_tecnica_producto AS ficha_tecnica, 
		pr.nombre AS nombre_producto, dc.cantidad, dc.precio_igv, dc.descuento, dc.subtotal, prov.razon_social AS proveedor
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto 
		AND dc.idproducto = pr.idproducto AND cpp.estado = '1' AND cpp.idproveedor = prov.idproveedor 
		AND dc.idproducto = '$idproducto' ORDER BY cpp.fecha_compra DESC";

		$compras_proyecto =  ejecutarConsultaArray($sql_2);

		if (!empty($compras_proyecto)) {

			foreach ($compras_proyecto as $key => $value) {

				$b[]=array(
					'idproyecto'=>$value['idproyecto'],
					'idcompra'=>$value['idcompra_proyecto'],
					'fecha_compra'=>$value['fecha_compra'],
					'ficha_tecnica'=>$value['ficha_tecnica'],
					'nombre_producto'=>$value['nombre_producto'],
					'cantidad'=>$value['cantidad'],
					'precio_con_igv'=>$value['precio_igv'],
					'descuento'=>$value['descuento'],
					'subtotal'=>$value['subtotal'],
					'proveedor'=>$value['proveedor']
				);
			}
		}
		
        $data = array_merge($a,$b);
        return $data;

	}

	public function suma_total_compras($clacificacion)	{

		$data_totales = array(); $cantidad = 0;  $subtotal=0; 

		$sql_1="SELECT p.idproducto,p.nombre,p.imagen,p.precio_total as precio_actual,um.nombre_medida, c.nombre_color
		FROM producto as p, unidad_medida as um, color as c
		WHERE p.idunidad_medida=um.idunidad_medida AND p.idcolor=c.idcolor AND p.idcategoria_insumos_af='$clacificacion'";

		$producto = ejecutarConsultaArray($sql_1);

		if (!empty($producto)) {

			foreach ($producto as $key => $value) {
				
				$idproducto= $value['idproducto'];
				$sql_2="SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_igv`) AS promedio_precio 
				FROM `detalle_compra` WHERE idproducto=$idproducto";
				$compra_p = ejecutarConsultaSimpleFila($sql_2);

				$cantidad += (empty($compra_p['cantidad'])) ? 0:floatval($compra_p['cantidad']) ;
				$subtotal += (empty($compra_p['subtotal'])) ? 0: floatval($compra_p['subtotal']);

				$sql_3 ="SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_con_igv`) AS promedio_precio 
				FROM `detalle_compra_af_g` WHERE idproducto=$idproducto";
				$compra_af_g= ejecutarConsultaSimpleFila($sql_3);

				$cantidad += (empty($compra_af_g['cantidad'])) ? 0: floatval($compra_af_g['cantidad']);
				$subtotal += (empty($compra_af_g['subtotal'])) ? 0: floatval($compra_af_g['subtotal']) ;

			}
			
			$data_totales =array(

				"total_cantidad" =>$cantidad,
				"total_monto"    =>$subtotal
	
			);

			return $data_totales;
		}
	}

}

?>