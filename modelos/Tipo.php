<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Tipo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre_tipo)
	{
		$sql="INSERT INTO tipo_trabajador (nombre, user_created)VALUES ('$nombre_tipo','" . $_SESSION['idusuario'] . "')";
		$intertar =  ejecutarConsulta_retornarID($sql); 
		if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('tipo_trabajador','".$intertar['data']."','Nuevo tipo trabajador registrado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idtipo_trabajador,$nombre_tipo)
	{
		$sql="UPDATE tipo_trabajador SET nombre='$nombre_tipo',user_updated= '" . $_SESSION['idusuario'] . "' WHERE idtipo_trabajador='$idtipo_trabajador'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('tipo_trabajador','$idtipo_trabajador','Tipo trabajador editado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar tipo
	public function desactivar($idtipo_trabajador)
	{
		$sql="UPDATE tipo_trabajador SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idtipo_trabajador='$idtipo_trabajador'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('tipo_trabajador','".$idtipo_trabajador."','Tipo trabajador desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
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
		$sql="UPDATE tipo_trabajador SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idtipo_trabajador='$idtipo_trabajador'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('tipo_trabajador','$idtipo_trabajador','Tipo trabajador Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
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