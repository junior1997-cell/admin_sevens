<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
Class Cargo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idtipo_trabjador,$nombre)
	{
		//var_dump($nombre);die();
		$sql="INSERT INTO cargo_trabajador(idtipo_trabjador,nombre)VALUES('$idtipo_trabjador','$nombre')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idcargo_trabajador,$idtipo_trabjador,$nombre)
	{
		$sql="UPDATE cargo_trabajador SET idtipo_trabjador='$idtipo_trabjador',nombre='$nombre' WHERE idcargo_trabajador='$idcargo_trabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar cargo_trabajador
	public function desactivar($idcargo_trabajador)
	{
		$sql="UPDATE cargo_trabajador SET estado='0' WHERE idcargo_trabajador='$idcargo_trabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar cargo_trabajador
	public function activar($idcargo_trabajador)
	{
		$sql="UPDATE cargo_trabajador SET estado='1' WHERE idcargo_trabajador='$idcargo_trabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar
	public function eliminar($idcargo_trabajador)
	{
		$sql="UPDATE cargo_trabajador SET estado_delete='0' WHERE idcargo_trabajador='$idcargo_trabajador'";
		return ejecutarConsulta($sql);
	}
	

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcargo_trabajador)
	{
		$sql="SELECT * FROM cargo_trabajador WHERE idcargo_trabajador='$idcargo_trabajador'; ";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT 
		ct.idcargo_trabajador as idcargo_trabajador,
		ct.idtipo_trabjador as idtipo_trabjador,
		ct.nombre as nombre,
		tt.nombre as nombre_tipo_t,
		ct.estado as estado
		FROM cargo_trabajador as ct, tipo_trabajador as tt
		WHERE ct.idtipo_trabjador=tt.idtipo_trabajador AND ct.estado=1 AND ct.estado_delete=1";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM cargo_trabajador where estado=1";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select_tipo_trab()
	{
		$sql="SELECT * FROM tipo_trabajador where estado=1";
		return ejecutarConsulta($sql);		
	}
}
?>