<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Resumen_general
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	public function r_compras($idproyecto){
		$pago_total=0; 
		$Arraycompras= Array(); 

		$sql="SELECT cpp.idcompra_proyecto, cpp.idproyecto, cpp.idproveedor, cpp.fecha_compra, cpp.monto_total, p.razon_social, cpp.descripcion 
		FROM compra_por_proyecto as cpp, proveedor as p
		WHERE cpp.idproyecto='$idproyecto' AND cpp.idproveedor=p.idproveedor AND cpp.estado='1' ORDER by cpp.idcompra_proyecto ASC";

		$compras=ejecutarConsultaArray($sql);

		if (!empty($compras)) {
			
			foreach ($compras as $key => $value) {

				$idcompra=$value['idcompra_proyecto'];

				$sql_2="SELECT SUM(pc.monto) as total_p FROM pago_compras as pc WHERE pc.idcompra_proyecto='$idcompra' AND pc.estado='1' GROUP BY idcompra_proyecto";
				$t_monto= ejecutarConsultaSimpleFila($sql_2);

				if (empty($t_monto)) {
					$pago_total=0;
				}else{
					$pago_total=$t_monto['total_p'];
				}

				$Arraycompras[]= array(
					"idcompra_proyecto"     => $value['idcompra_proyecto'],
					"idproyecto"     	 => $value['idproyecto'],
					"idproveedor"    => $value['idproveedor'],
					"fecha_compra"    => $value['fecha_compra'],
					"monto_total"    => $value['monto_total'],
					"proveedor"    => $value['razon_social'],
					"descripcion"    => $value['descripcion'],

					"monto_pago_total"       =>$pago_total

				);

			}

		}

		return $Arraycompras;

		
	}
	//lismatamos los detalles compras
	public function detalles_compras($id_compra)
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
	//mostrar detalles uno a uno de la factura
	public function ver_compras($idcompra_proyecto)
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

	public function r_serv_maquinaria_equipos($idproyecto,$tipo)
	{
		$serv_maquinaria= Array();
		$pago_total=0;
		$sql="SELECT s.idmaquinaria as idmaquinaria, s.idproyecto as idproyecto, m.nombre as maquina, p.razon_social as razon_social, COUNT(s.idmaquinaria) as cantidad_veces, SUM(s.costo_parcial) as costo_parcial 
		FROM servicio as s, maquinaria as m, proveedor as p 
		WHERE s.estado = 1 AND s.idproyecto='$idproyecto' AND m.tipo = '$tipo' AND s.idmaquinaria=m.idmaquinaria AND m.idproveedor=p.idproveedor 
		GROUP BY s.idmaquinaria";

		$maquinaria=ejecutarConsultaArray($sql);

		if (!empty($maquinaria)) {
			
			foreach ($maquinaria as $key => $value) {

				$idmaquinaria=$value['idmaquinaria'];

				$sql_2="SELECT SUM(ps.monto) as monto_pag_ser_maq FROM pago_servicio ps WHERE ps.idproyecto='$idproyecto' AND ps.id_maquinaria='$idmaquinaria' AND ps.estado=1 GROUP by id_maquinaria";
				$ser_maq_monto= ejecutarConsultaSimpleFila($sql_2);

				if (empty($t_monto)) {
					$pago_total=0;
				}else{
					$pago_total=$ser_maq_monto['monto_pag_ser_maq'];
				}

				$serv_maquinaria[]= array(

					"idmaquinaria"      => $value['idmaquinaria'],
					"idproyecto"      => $value['idproyecto'],
					"maquina"     	 	=> $value['maquina'],
					"cantidad_veces"    => $value['cantidad_veces'],
					"costo_parcial"     => $value['costo_parcial'],
					"proveedor"         => $value['razon_social'],

					"monto_pag_ser_maq"       =>$pago_total

				);

			}

		}

		return $serv_maquinaria;

	}

	//ver detallete por maquina-equipo
	public function ver_detalle_maq_equ($idmaquinaria,$idproyecto)
	{

		$sql="SELECT * FROM servicio as s WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' ORDER BY idservicio DESC";

		return ejecutarConsulta($sql);	

	}

	public function r_transportes($idproyecto)
	{
		$sql="SELECT t.idtransporte,t.idproyecto,t.fecha_viaje,t.descripcion,t.precio_parcial, t.comprobante 
		FROM transporte as t, proyecto as p WHERE t.idproyecto='$idproyecto' AND t.idproyecto=p.idproyecto AND t.estado='1' ORDER BY idtransporte DESC";
		return ejecutarConsultaArray($sql);
	}

	public function r_hospedajes($idproyecto)
	{
		$sql="SELECT h.idhospedaje,h.idproyecto,h.fecha_comprobante,h.descripcion,h.precio_parcial, h.comprobante 
		FROM hospedaje as h, proyecto as p WHERE h.idproyecto=p.idproyecto AND h.idproyecto='$idproyecto' AND h.estado=1 ORDER BY h.idhospedaje DESC";
		return ejecutarConsultaArray($sql);
	}
	
	public function r_comidas_extras($idproyecto)
	{
		$sql="SELECT ce.idcomida_extra, ce.idproyecto, ce.fecha_comida, ce.descripcion, ce.costo_parcial, ce.comprobante 
		FROM comida_extra as ce, proyecto as p WHERE ce.estado=1 AND ce.idproyecto=p.idproyecto AND ce.idproyecto='$idproyecto'";
		return ejecutarConsultaArray($sql);
	}
}

?>