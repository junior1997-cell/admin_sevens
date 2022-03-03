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
		$sql="INSERT INTO tipo_trabajador (nombre)VALUES ('$nombre_tipo')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idtipo_trabajador,$nombre_tipo)
	{
		$sql="UPDATE tipo_trabajador SET nombre='$nombre_tipo' WHERE idtipo_trabajador='$idtipo_trabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar tipo
	public function desactivar($idtipo_trabajador)
	{
		$sql="UPDATE tipo_trabajador SET estado='0' WHERE idtipo_trabajador='$idtipo_trabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar tipo
	public function activar($idtipo_trabajador)
	{
		$sql="UPDATE tipo_trabajador SET estado='1' WHERE idtipo_trabajador='$idtipo_trabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar tipo
	public function eliminar($idtipo_trabajador)
	{
		$sql="UPDATE tipo_trabajador SET estado_delete='0' WHERE idtipo_trabajador='$idtipo_trabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idtipo_trabajador)
	{
		$sql="SELECT * FROM tipo_trabajador WHERE idtipo_trabajador='$idtipo_trabajador'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM tipo_trabajador WHERE estado=1 AND estado_delete=1 ORDER BY nombre ASC";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM tipo_trabajador where estado=1";
		return ejecutarConsulta($sql);		
	}
}
?>