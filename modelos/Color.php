<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
Class Color
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre)
	{
		//var_dump($nombre);die();
		$sql="INSERT INTO color(nombre_color)VALUES('$nombre')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idcolor,$nombre)
	{
		$sql="UPDATE color SET nombre_color='$nombre' WHERE idcolor='$idcolor'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar color
	public function desactivar($idcolor)
	{
		$sql="UPDATE color SET estado='0' WHERE idcolor='$idcolor'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar color
	public function activar($idcolor)
	{
		$sql="UPDATE color SET estado='1' WHERE idcolor='$idcolor'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar color
	public function eliminar($idcolor)
	{
		$sql="UPDATE color SET estado_delete='0' WHERE idcolor='$idcolor'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcolor)
	{
		$sql="SELECT * FROM color WHERE idcolor='$idcolor'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM color WHERE idcolor>'1' AND estado=1  AND estado_delete=1 ORDER BY nombre_color ASC";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM color where estado=1";
		return ejecutarConsulta($sql);		
	}
}
?>