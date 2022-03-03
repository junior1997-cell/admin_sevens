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
	public function insertar($nombre_maquina,$codigo_m,$proveedor,$tipo)
	{
		$sql="INSERT INTO maquinaria (nombre,codigo_maquina,idproveedor,tipo)
		VALUES ('$nombre_maquina','$codigo_m','$proveedor','$tipo')";
		
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idmaquinaria,$nombre_maquina,$codigo_m,$proveedor,$tipo)
	{
		
		$sql="UPDATE maquinaria SET 
		nombre='$nombre_maquina',
		codigo_maquina='$codigo_m',
		idproveedor='$proveedor',
		tipo='$tipo'
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
	
	//Implementamos un método para activar categorías
	public function eliminar($idmaquinaria)
	{
		$sql="UPDATE maquinaria SET estado_delete='0' WHERE idmaquinaria='$idmaquinaria'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idmaquinaria)
	{
		$sql="SELECT 
		mq.idmaquinaria as idmaquinaria,
		mq.idproveedor as idproveedor,
		p.razon_social as razon_social,
		mq.nombre as nombre, 
		mq.codigo_maquina as modelo, 
		mq.tipo as tipo, 
		mq.estado as estado
		FROM maquinaria as mq, proveedor as p WHERE mq.idmaquinaria='$idmaquinaria' AND mq.idproveedor=p.idproveedor";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar maquinas y equipos
	public function listar($tipo)
	{
		$sql="SELECT 
		mq.idmaquinaria as idmaquinaria,
		p.razon_social as razon_social,
		mq.nombre as nombre, 
		mq.codigo_maquina as modelo, 
		mq.tipo as tipo, 
		mq.estado as estado
		
		FROM maquinaria as mq, proveedor as p WHERE mq.idproveedor=p.idproveedor AND mq.tipo='$tipo' AND mq.estado_delete='1'  AND mq.estado='1' ORDER BY  mq.nombre ASC";
		return ejecutarConsulta($sql);		
	}
	//Seleccionar Trabajador Select2
	public function select2_proveedor()
	{
		$sql="SELECT idproveedor,razon_social,ruc FROM proveedor WHERE estado='1' AND estado_delete='1'";
		return ejecutarConsulta($sql);		
	}


}

?>