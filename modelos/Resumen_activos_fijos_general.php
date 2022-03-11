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
		$sql_1="SELECT p.idproducto,p.nombre,p.imagen,p.precio_total as precio_actual,um.nombre_medida, c.nombre_color, p.modelo
		FROM producto as p, unidad_medida as um, color as c
		WHERE p.idunidad_medida=um.idunidad_medida AND p.idcolor=c.idcolor AND p.idcategoria_insumos_af='$clacificacion'";

		$producto = ejecutarConsultaArray($sql_1);

		if (!empty($producto)) {

			foreach ($producto as $key => $value) {

				$cantidad = 0; $descuento=0; $subtotal=0; $promedio_precio = 0; $promedio_total=0;
				
				$idproducto= $value['idproducto'];
				$sql_2="SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_con_igv`) AS promedio_precio 
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

				if ($cantidad>0) {

					$data_productos[]=array(

						"idproducto"      =>$value['idproducto'],
						"nombre_producto" =>$value['nombre'],
						"modelo"          =>$value['modelo'],
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


			}

		}
		return $data_productos;

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
		pr.nombre AS nombre_producto, dc.cantidad, dc.precio_con_igv, dc.descuento, dc.subtotal, prov.razon_social AS proveedor
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
					'precio_con_igv'=>$value['precio_con_igv'],
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
				$sql_2="SELECT SUM(`cantidad`) as cantidad, SUM(`descuento`) as descuento, SUM(`subtotal`)  as subtotal,  AVG(`precio_con_igv`) AS promedio_precio 
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

		}
		return $data_totales;
	}

	//.METODOS PARA EDITAR COMPRA POR PROYECTO
	  //Listamos los productos a selecionar
	  public function listar_insumos_activo_general()
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

	//.METODOS PARA EDITAR COMPRA POR PROYECTO
	//Listamos los productos a selecionar
	  public function listar_solo_activos()
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
			WHERE um.idunidad_medida=p.idunidad_medida  AND c.idcolor=p.idcolor  AND ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af AND p.idcategoria_insumos_af!='1'
			ORDER BY p.nombre ASC";
	
		return ejecutarConsulta($sql);
	}

	
  //Implementamos un método para editar registros
  public function editar_por_proyecto(
    $idcompra_proyecto, $idproyecto, $idproveedor, $fecha_compra, $tipo_comprovante, $serie_comprovante, $descripcion, $total_venta, $subtotal_compra, $igv_compra,
    $estado_detraccion, $idproducto, $unidad_medida, $nombre_color, $cantidad, $precio_sin_igv, $precio_igv, $precio_total, $descuento, $ficha_tecnica_producto) 
	{
	
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
        $sql_detalle = "INSERT INTO detalle_compra(idcompra_proyecto, idproducto, unidad_medida, color, cantidad, precio_venta, igv,  precio_con_igv, descuento, subtotal, ficha_tecnica_producto) 
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


}

?>