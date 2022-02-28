<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
Class Categoria_af
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre)
	{
		//var_dump($nombre);die();
		$sql="INSERT INTO `categoria_insumos_af`(`nombre`) VALUES ('$nombre')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idcategoria_insumos_af,$nombre)
	{
		$sql="UPDATE categoria_insumos_af SET nombre='$nombre' WHERE idcategoria_insumos_af='$idcategoria_insumos_af'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categoria_insumos_af
	public function desactivar($idcategoria_insumos_af)
	{
		$sql="UPDATE categoria_insumos_af SET estado='0' WHERE idcategoria_insumos_af='$idcategoria_insumos_af'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categoria_insumos_af
	public function activar($idcategoria_insumos_af)
	{
		$sql="UPDATE categoria_insumos_af SET estado='1' WHERE idcategoria_insumos_af='$idcategoria_insumos_af'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar
	public function delete($idcategoria_insumos_af)
	{
		$sql="UPDATE categoria_insumos_af SET estado_delete='0' WHERE idcategoria_insumos_af='$idcategoria_insumos_af'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcategoria_insumos_af)
	{
		$sql="SELECT * FROM categoria_insumos_af WHERE idcategoria_insumos_af='$idcategoria_insumos_af'; ";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM categoria_insumos_af WHERE  idcategoria_insumos_af>1 AND estado=1 AND estado_delete=1  ORDER BY nombre ASC";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM categoria_insumos_af where idcategoria_insumos_af>1 AND estado=1 AND estado_delete=1";
		return ejecutarConsulta($sql);		
	}

}
?>