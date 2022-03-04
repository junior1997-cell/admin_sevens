<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
Class Ocupacion
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre_ocupacion)
	{
		$sql="INSERT INTO ocupacion (nombre_ocupacion)VALUES ('$nombre_ocupacion')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idocupacion,$nombre_ocupacion)
	{
		$sql="UPDATE ocupacion SET nombre_ocupacion='$nombre_ocupacion' WHERE idocupacion='$idocupacion'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar ocupacion
	public function desactivar($idocupacion)
	{
		$sql="UPDATE ocupacion SET estado='0' WHERE idocupacion='$idocupacion'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar ocupacion
	public function activar($idocupacion)
	{
		$sql="UPDATE ocupacion SET estado='1' WHERE idocupacion='$idocupacion'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar ocupacion
	public function eliminar($idocupacion)
	{
		$sql="UPDATE ocupacion SET estado_delete='0' WHERE idocupacion='$idocupacion'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idocupacion)
	{
		$sql="SELECT * FROM ocupacion WHERE idocupacion='$idocupacion'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM ocupacion 	WHERE estado=1  AND estado_delete=1  ORDER BY nombre_ocupacion ASC";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM ocupacion where estado=1";
		return ejecutarConsulta($sql);		
	}
}
?>