<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Resumenfacturas
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function facturas_compras($idproyecto)
	{
		$sql="SELECT cpp.idproyecto as idproyecto, cpp.idcompra_proyecto as idcompra_proyecto, cpp.fecha_compra as fecha_compra,
		cpp.serie_comprovante as serie_comprovante, cpp.descripcion as descripcion, cpp.subtotal_compras_proyect as subtotal,
		cpp.igv_compras_proyect as igv, cpp.monto_total as monto_total, p.razon_social as razon_social
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproyecto='$idproyecto' AND  cpp.estado=1 AND cpp.tipo_comprovante='Factura' AND cpp.idproveedor=p.idproveedor
		ORDER BY cpp.fecha_compra DESC;";

		return ejecutarConsulta($sql);	
	}

	public function suma_total_compras($idproyecto)
	{
		$sql="SELECT SUM(monto_total) as monto_total FROM compra_por_proyecto WHERE idproyecto='$idproyecto' AND estado=1;";

		return ejecutarConsultaSimpleFila($sql);
	}

	public function facturas_maquinarias_equipos($idproyecto,$tipo)
	{
		$sql="SELECT f.idfactura, f.idproyecto, f.codigo, f.fecha_emision, f.monto, f.descripcion, 
			f.igv,f.subtotal, f.nota, mq.nombre, prov.razon_social 
			FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
			WHERE f.idproyecto='$idproyecto' AND f.idmaquinaria=mq.idmaquinaria AND f.idproyecto=p.idproyecto AND
			mq.tipo='$tipo' AND f.estado =1 AND mq.idproveedor=prov.idproveedor ORDER BY f.fecha_emision DESC;";
		
		return ejecutarConsulta($sql);	
	}

	public function suma_total_maquinaria_equipos($idproyecto,$tipo)
	{
		$sql="SELECT  SUM(f.monto) as monto_total FROM factura as f, maquinaria as mq
		WHERE f.idproyecto='$idproyecto' AND mq.tipo='$tipo' AND  f.estado =1  AND f.idmaquinaria=mq.idmaquinaria;";

		return ejecutarConsultaSimpleFila($sql);
	}

	public function facturas_otros_gastos($idproyecto)
	{
		$sql="SELECT*FROM otro_servicio WHERE idproyecto='$idproyecto' AND estado=1 AND tipo_comprobante='Factura' ORDER BY fecha_o_s DESC;";

		return ejecutarConsulta($sql);	
	}

	public function suma_total_otros_gastos($idproyecto)
	{
		$sql="SELECT SUM(costo_parcial) as monto_total FROM otro_servicio  WHERE idproyecto='$idproyecto' AND estado=1 AND tipo_comprobante='Factura';";

		return ejecutarConsultaSimpleFila($sql);
	}

	public function facturas_transporte($idproyecto)
	{
		$sql="SELECT*FROM transporte WHERE idproyecto='$idproyecto' AND tipo_comprobante='Factura' AND estado=1 ORDER BY fecha_viaje DESC;";
		return ejecutarConsulta($sql);	
	}

	public function suma_total_transporte($idproyecto)
	{
		$sql="SELECT SUM(precio_parcial) as monto_total FROM transporte WHERE idproyecto='$idproyecto' AND tipo_comprobante='Factura' AND estado=1;";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function facturas_hospedaje($idproyecto)
	{
		$sql="SELECT*FROM hospedaje WHERE idproyecto='$idproyecto' AND tipo_comprobante='Factura' AND estado=1 ORDER BY fecha_comprobante DESC;";
		return ejecutarConsulta($sql);	
	}

	public function suma_total_hospedaje($idproyecto)
	{
		$sql="SELECT SUM(precio_parcial) as monto_total FROM hospedaje WHERE idproyecto='$idproyecto' AND tipo_comprobante='Factura' AND estado=1 ORDER BY fecha_comprobante DESC;";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function facturas_pension($idproyecto)
	{
		$sql="SELECT fp.idfactura_pension,fp.tipo_comprobante, fp.nro_comprobante, fp.forma_de_pago, fp.fecha_emision, fp.monto, fp.igv, fp.subtotal, prov.razon_social,fp.estado
		FROM factura_pension as fp, pension as p, proveedor as prov
		WHERE fp.idpension=p.idpension AND prov.idproveedor=p.idproveedor AND p.idproyecto='$idproyecto' AND fp.estado=1 AND fp.tipo_comprobante='Factura'  ORDER BY fecha_emision DESC;";

		return ejecutarConsulta($sql);
	}

	public function suma_total_pension($idproyecto)
	{
		$sql="SELECT SUM(fp.monto) as monto_total
		FROM factura_pension as fp, pension as p 
		WHERE fp.idpension=p.idpension  AND p.idproyecto='$idproyecto' AND fp.estado=1 AND fp.tipo_comprobante='Factura';";

		return ejecutarConsultaSimpleFila($sql);
	}

	public function facturas_break($idproyecto)
	{
		$sql="SELECT fb.idfactura_break,fb.idsemana_break, fb.nro_comprobante, fb.fecha_emision, fb.monto, fb.igv, fb.subtotal, fb.tipo_comprobante, fb.descripcion, sb.numero_semana
		FROM factura_break as fb, semana_break as sb
		WHERE  fb.idsemana_break=sb.idsemana_break AND fb.tipo_comprobante='Factura' AND fb.estado=1 AND sb.idproyecto='$idproyecto'  ORDER BY fecha_emision DESC;";

		return ejecutarConsulta($sql);
	}

	public function suma_total_break($idproyecto)
	{
		$sql="SELECT SUM(fb.monto) as monto_total
		FROM factura_break as fb, semana_break as sb
		WHERE  fb.idsemana_break=sb.idsemana_break AND fb.tipo_comprobante='Factura' AND fb.estado=1 AND sb.idproyecto='$idproyecto';";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function facturas_comida_extra($idproyecto)
	{
		$sql="SELECT idcomida_extra, numero_comprobante, fecha_comida, subtotal, igv, costo_parcial, estado
		FROM comida_extra
		WHERE tipo_comprobante='Factura' AND idproyecto='$idproyecto' AND estado=1  ORDER BY fecha_comida DESC;";

		return ejecutarConsulta($sql);
	}

	public function suma_total_comida_extra($idproyecto)
	{
		$sql="SELECT SUM(costo_parcial) as monto_total
		FROM comida_extra
		WHERE tipo_comprobante='Factura' AND idproyecto='$idproyecto' AND estado=1;";

		return ejecutarConsultaSimpleFila($sql);
	}


}

?>