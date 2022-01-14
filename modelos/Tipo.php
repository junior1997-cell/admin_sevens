<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
Class Tipo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre_tipo)
	{
		$sql="INSERT INTO tipo (nombre_tipo)VALUES ('$nombre_tipo')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idtipo,$nombre_tipo)
	{
		$sql="UPDATE tipo SET nombre_tipo='$nombre_tipo' WHERE idtipo='$idtipo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar tipo
	public function desactivar($idtipo)
	{
		$sql="UPDATE tipo SET estado='0' WHERE idtipo='$idtipo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar tipo
	public function activar($idtipo)
	{
		$sql="UPDATE tipo SET estado='1' WHERE idtipo='$idtipo'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idtipo)
	{
		$sql="SELECT * FROM tipo WHERE idtipo='$idtipo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM tipo";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM tipo where estado=1";
		return ejecutarConsulta($sql);		
	}
}
?>