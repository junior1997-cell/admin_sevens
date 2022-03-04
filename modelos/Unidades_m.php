<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
Class Unidades_m
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$abreviacion)
	{
		$sql="INSERT INTO unidad_medida (nombre_medida,abreviacion)VALUES ('$nombre','$abreviacion')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idunidad_medida,$nombre,$abreviacion)
	{
		$sql="UPDATE unidad_medida SET nombre_medida='$nombre',abreviacion='$abreviacion' WHERE idunidad_medida='$idunidad_medida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar unidad_medida
	public function desactivar($idunidad_medida)
	{
		$sql="UPDATE unidad_medida SET estado='0' WHERE idunidad_medida='$idunidad_medida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar unidad_medida
	public function activar($idunidad_medida)
	{
		$sql="UPDATE unidad_medida SET estado='1' WHERE idunidad_medida='$idunidad_medida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar unidad_medida
	public function eliminar($idunidad_medida)
	{
		$sql="UPDATE unidad_medida SET estado_delete='0' WHERE idunidad_medida='$idunidad_medida'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idunidad_medida)
	{
		$sql="SELECT * FROM unidad_medida WHERE idunidad_medida='$idunidad_medida'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM unidad_medida WHERE estado=1  AND estado_delete=1  ORDER BY nombre_medida ASC";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM unidad_medida where estado=1";
		return ejecutarConsulta($sql);		
	}
}
?>