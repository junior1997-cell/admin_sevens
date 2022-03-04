<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Transporte
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	//$idtransporte,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$idproveedor,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$comprobante,$glosa)
	{
	
		$sql="INSERT INTO transporte (idproyecto,idproveedor,fecha_viaje,tipo_viajero,tipo_ruta,cantidad,precio_unitario,precio_parcial,ruta,descripcion,forma_de_pago,tipo_comprobante,numero_comprobante,subtotal,igv,comprobante,glosa) 
		VALUES ('$idproyecto','$idproveedor','$fecha_viaje','$tipo_viajero','$tipo_ruta','$cantidad','$precio_unitario','$precio_parcial','$ruta','$descripcion','$forma_pago','$tipo_comprobante','$nro_comprobante','$subtotal','$igv','$comprobante','$glosa')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idtransporte,$idproyecto,$idproveedor,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$comprobante,$glosa)
	{
		$sql="UPDATE transporte SET 
		idproyecto='$idproyecto',
		idproveedor='$idproveedor',
		fecha_viaje='$fecha_viaje',
		tipo_viajero='$tipo_viajero',
		tipo_ruta='$tipo_ruta',
		cantidad='$cantidad',
		precio_unitario='$precio_unitario',
		precio_parcial='$precio_parcial',
		ruta='$ruta',
		descripcion='$descripcion',
		forma_de_pago='$forma_pago',
		tipo_comprobante='$tipo_comprobante',
		numero_comprobante='$nro_comprobante',
		subtotal='$subtotal',
		igv='$igv',
		comprobante='$comprobante',
		glosa='$glosa'

		WHERE idtransporte='$idtransporte'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idtransporte )
	{
		$sql="UPDATE transporte SET estado='0' WHERE idtransporte ='$idtransporte'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idtransporte )
	{
		$sql="UPDATE transporte SET estado='1' WHERE idtransporte ='$idtransporte'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function eliminar($idtransporte )
	{
		$sql="UPDATE transporte SET estado_delete='0' WHERE idtransporte ='$idtransporte'";
		return ejecutarConsulta($sql);
	}
	

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idtransporte )
	{
		$sql="SELECT*FROM transporte WHERE idtransporte ='$idtransporte'";

		return ejecutarConsultaSimpleFila($sql);
	}

	public function verdatos($idtransporte)
	{
		$sql="SELECT t.idtransporte,t.idproyecto,t.idproveedor,t.tipo_comprobante,t.numero_comprobante,t.forma_de_pago,t.fecha_viaje,t.tipo_viajero,t.glosa,t.tipo_ruta,t.ruta,t.cantidad,t.precio_unitario,t.subtotal,t.igv,t.precio_parcial,t.descripcion,t.comprobante, p.razon_social, p.ruc
		FROM transporte as t, proveedor as p WHERE t.idtransporte='$idtransporte' AND t.idproveedor=p.idproveedor;";

		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($idproyecto)
	{
		$sql="SELECT*FROM transporte WHERE idproyecto='$idproyecto' AND estado='1' AND  estado_delete='1' ORDER BY fecha_viaje DESC";
		return ejecutarConsulta($sql);		
	}

	//Select2 Proveedor
	public function select2_proveedor()
	{
	$sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE  estado=1 AND estado_delete=1";
	return ejecutarConsulta($sql);
	}
	

	//Seleccionar un comprobante
	public function ficha_tec($idtransporte)
	{
		$sql="SELECT comprobante FROM transporte WHERE idtransporte='$idtransporte'";
		return ejecutarConsulta($sql);		
	}
	//total
	public function total($idproyecto){
		$sql="SELECT SUM(precio_parcial) as precio_parcial FROM transporte WHERE idproyecto='$idproyecto' AND estado=1 AND estado_delete=1";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>