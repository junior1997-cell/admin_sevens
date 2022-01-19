<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Hospedaje
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$fecha_inicio,$fecha_fin,$cantidad,$unidad,$precio_unitario,$precio_parcial,$descripcion,$comprobante)
	{
	
		$sql="INSERT INTO hospedaje (idproyecto,fecha_inicio,fecha_fin,cantidad,unidad,precio_unitario,precio_parcial,descripcion,comprobante) 
		VALUES ('$idproyecto','$fecha_inicio','$fecha_fin','$cantidad','$unidad','$precio_unitario','$precio_parcial','$descripcion','$comprobante')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idhospedaje,$idproyecto,$fecha_inicio,$fecha_fin,$cantidad,$unidad,$precio_unitario,$precio_parcial,$descripcion,$comprobante)
	{
		$sql="UPDATE hospedaje SET 
		idproyecto='$idproyecto',
		fecha_inicio='$fecha_inicio',
		fecha_fin='$fecha_fin',
		cantidad='$cantidad',
		unidad='$unidad',
		precio_unitario='$precio_unitario',
		precio_parcial='$precio_parcial',
		descripcion='$descripcion',
		comprobante='$comprobante'

		WHERE idhospedaje='$idhospedaje'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idhospedaje )
	{
		$sql="UPDATE hospedaje SET estado='0' WHERE idhospedaje ='$idhospedaje'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idhospedaje )
	{
		$sql="UPDATE hospedaje SET estado='1' WHERE idhospedaje ='$idhospedaje'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idhospedaje )
	{
		$sql="SELECT*FROM hospedaje   
		WHERE idhospedaje ='$idhospedaje'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($idproyecto)
	{
		$sql="SELECT*FROM hospedaje WHERE idproyecto='$idproyecto' ORDER BY idhospedaje DESC";
		return ejecutarConsulta($sql);		
	}

	//Seleccionar un comprobante
	public function ficha_tec($idhospedaje)
	{
		$sql="SELECT comprobante FROM hospedaje WHERE idhospedaje='$idhospedaje'";
		return ejecutarConsulta($sql);		
	}
	//total
	public function total($idproyecto){
		$sql="SELECT SUM(precio_parcial) as precio_parcial FROM hospedaje WHERE idproyecto='$idproyecto' AND estado=1";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>