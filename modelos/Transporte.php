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
	public function insertar($idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$comprobante)
	{
	
		$sql="INSERT INTO transporte (idproyecto,fecha_viaje,tipo_viajero,tipo_ruta,cantidad,precio_unitario,precio_parcial,ruta,descripcion,forma_de_pago,tipo_comprobante,numero_comprobante,subtotal,igv,comprobante) 
		VALUES ('$idproyecto','$fecha_viaje','$tipo_viajero','$tipo_ruta','$cantidad','$precio_unitario','$precio_parcial','$ruta','$descripcion','$forma_pago','$tipo_comprobante','$nro_comprobante','$subtotal','$igv','$comprobante')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idtransporte,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$comprobante)
	{
		$sql="UPDATE transporte SET 
		idproyecto='$idproyecto',
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
		comprobante='$comprobante'

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

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idtransporte )
	{
		$sql="SELECT*FROM transporte   
		WHERE idtransporte ='$idtransporte'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($idproyecto)
	{
		$sql="SELECT*FROM transporte WHERE idproyecto='$idproyecto' ORDER BY idtransporte DESC";
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
		$sql="SELECT SUM(precio_parcial) as precio_parcial FROM transporte WHERE idproyecto='$idproyecto' AND estado=1";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>