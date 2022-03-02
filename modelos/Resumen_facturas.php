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
		$sql="SELECT
		cpp.idproyecto as idproyecto,
		cpp.idcompra_proyecto as idcompra_proyecto,
		cpp.idproveedor as idproveedor,
		cpp.fecha_compra as fecha_compra,
		cpp.serie_comprovante as serie_comprovante,
		cpp.descripcion as descripcion,
		cpp.subtotal_compras_proyect as subtotal,
		cpp.igv_compras_proyect as igv,
		cpp.monto_total as monto_total,
		cpp.imagen_comprobante as imagen_comprobante,
		p.razon_social as razon_social, p.telefono,
		cpp.estado as estado
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE cpp.idproyecto='$idproyecto' AND cpp.tipo_comprovante='Factura' AND cpp.idproveedor=p.idproveedor
		ORDER BY cpp.fecha_compra DESC;";

		return ejecutarConsulta($sql);	
	}
	public function suma_total_compras()
	{
		# code...
	}
	public function facturas_maquinarias_equipos($idproyecto,$tipo)
	{
		$sql="SELECT f.idfactura, f.idproyecto, f.idmaquinaria, f.codigo, f.fecha_emision, f.monto, f.descripcion, 
			f.igv,f.subtotal, f.nota, f.imagen, mq.nombre, prov.razon_social 
			FROM factura as f, proyecto as p, maquinaria as mq, proveedor as prov
			WHERE f.idproyecto='$idproyecto' AND f.idmaquinaria=mq.idmaquinaria AND f.idproyecto=p.idproyecto AND
			mq.tipo='$tipo' AND f.estado =1 AND mq.idproveedor=prov.idproveedor ORDER BY f.fecha_emision DESC;";
		
		return ejecutarConsulta($sql);	
	}
	public function suma_total_maquinaria_equipos()
	{
		# code...
	}


}

?>