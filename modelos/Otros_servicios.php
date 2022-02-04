<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Otros_servicios
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	//$idotro_servicio,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$fecha_o_s,$precio_parcial,$subtotal,$igv,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$comprobante)
	{
	
		$sql="INSERT INTO otro_servicio (idproyecto, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_o_s, costo_parcial,subtotal,igv,descripcion, comprobante) 
		VALUES ('$idproyecto','$tipo_comprobante','$nro_comprobante','$forma_pago','$fecha_o_s','$precio_parcial','$subtotal','$igv','$descripcion','$comprobante')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idotro_servicio,$idproyecto,$fecha_o_s,$precio_parcial,$subtotal,$igv,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$comprobante)
	{
		$sql="UPDATE otro_servicio SET 
		idproyecto='$idproyecto',
		fecha_o_s='$fecha_o_s',
		costo_parcial='$precio_parcial',
		subtotal='$subtotal',
		igv='$igv',
		descripcion='$descripcion',
		forma_de_pago='$forma_pago',
		tipo_comprobante='$tipo_comprobante',
		numero_comprobante='$nro_comprobante',
		comprobante='$comprobante'

		WHERE idotro_servicio='$idotro_servicio'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idotro_servicio )
	{
		$sql="UPDATE otro_servicio SET estado='0' WHERE idotro_servicio ='$idotro_servicio'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idotro_servicio )
	{
		$sql="UPDATE otro_servicio SET estado='1' WHERE idotro_servicio ='$idotro_servicio'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idotro_servicio )
	{
		$sql="SELECT*FROM otro_servicio   
		WHERE idotro_servicio ='$idotro_servicio'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($idproyecto)
	{
		$sql="SELECT*FROM otro_servicio WHERE idproyecto='$idproyecto' ORDER BY idotro_servicio DESC";
		return ejecutarConsulta($sql);		
	}

	//Seleccionar un comprobante
	public function ficha_tec($idotro_servicio)
	{
		$sql="SELECT comprobante FROM otro_servicio WHERE idotro_servicio='$idotro_servicio'";
		return ejecutarConsulta($sql);		
	}
	//total
	public function total($idproyecto){
		$sql="SELECT SUM(costo_parcial) as precio_parcial FROM otro_servicio WHERE idproyecto='$idproyecto' AND estado=1";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>