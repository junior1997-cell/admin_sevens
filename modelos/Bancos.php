<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Bancos
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre, $alias, $formato_cta, $formato_cci, $formato_detracciones, $imagen1)
	{
		$sql="INSERT INTO bancos (nombre, alias, formato_cta, formato_cci, formato_detracciones, icono)VALUES ('$nombre', '$alias', '$formato_cta', '$formato_cci', '$formato_detracciones', '$imagen1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idbancos, $nombre, $alias, $formato_cta, $formato_cci, $formato_detracciones, $imagen1)
	{
		$sql="UPDATE bancos SET nombre='$nombre', alias ='$alias', formato_cta='$formato_cta', 
		formato_cci='$formato_cci', formato_detracciones='$formato_detracciones', icono='$imagen1'  
		WHERE idbancos='$idbancos'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar bancos
	public function desactivar($idbancos)
	{
		$sql="UPDATE bancos SET estado='0' WHERE idbancos='$idbancos'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar bancos
	public function activar($idbancos)
	{
		$sql="UPDATE bancos SET estado='1' WHERE idbancos='$idbancos'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar bancos
	public function eliminar($idbancos)
	{
		$sql="UPDATE bancos SET estado_delete='0' WHERE idbancos='$idbancos'";
		return ejecutarConsulta($sql);
	}
	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idbancos)
	{
		$sql="SELECT * FROM bancos WHERE idbancos='$idbancos'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM bancos WHERE idbancos > 1 	AND estado=1  AND estado_delete=1 ORDER BY nombre ASC";
		return ejecutarConsulta($sql);		
	}
	
	//Implementar un método para listar los registros y mostrar en el select
	public function obtenerImg($id){
		$sql="SELECT icono FROM bancos where idbancos = '$id' ";
		return ejecutarConsultaSimpleFila($sql);		
	}
}
?>