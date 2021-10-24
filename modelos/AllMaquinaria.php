<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Allmaquinarias
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre_maquina,$codigo_m)
	{
		$sql="INSERT INTO maquinaria (nombre,codigo_maquina)
		VALUES ('$nombre_maquina', '$codigo_m')";
		
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idmaquinaria,$nombre_maquina,$codigo_m)
	{
		
		$sql="UPDATE maquinaria SET 
		nombre='$nombre_maquina',
		codigo_maquina='$codigo_m'
		WHERE idmaquinaria ='$idmaquinaria'";	
		
		return ejecutarConsulta($sql);
		
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idmaquinaria)
	{
		$sql="UPDATE maquinaria SET estado='0' WHERE idmaquinaria='$idmaquinaria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idmaquinaria)
	{
		$sql="UPDATE maquinaria SET estado='1' WHERE idmaquinaria='$idmaquinaria'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idmaquinaria)
	{
		$sql="SELECT * FROM maquinaria WHERE idmaquinaria='$idmaquinaria'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM maquinaria";
		return ejecutarConsulta($sql);		
	}

}

?>