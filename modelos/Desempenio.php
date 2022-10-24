<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Desempenio
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre_desempenio)	{
		//var_dump($nombre_desempenio);die();
		$sql="INSERT INTO desempenio(nombre_desempenio, user_created)VALUES('$nombre_desempenio','" . $_SESSION['idusuario'] . "')";
		$intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('desempenio','".$intertar['data']."','Nuevo cargo trabajador registrado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($iddesempenio, $nombre_desempenio)
	{
		$sql="UPDATE desempenio SET nombre_desempenio='$nombre_desempenio', user_updated= '" . $_SESSION['idusuario'] . "' WHERE iddesempenio='$iddesempenio'";
		$editar =  ejecutarConsulta($sql); if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('desempenio','$iddesempenio','Cargo trabajador editado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar desempenio
	public function desactivar($iddesempenio) {
		$sql="UPDATE desempenio SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE iddesempenio='$iddesempenio'";
		$desactivar= ejecutarConsulta($sql); if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('desempenio','".$iddesempenio."','Cargo trabajador desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar desempenio
	public function activar($iddesempenio) {
		$sql="UPDATE desempenio SET estado='1' WHERE iddesempenio='$iddesempenio'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar
	public function eliminar($iddesempenio)	{
		$sql="UPDATE desempenio SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE iddesempenio='$iddesempenio'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('desempenio','$iddesempenio','Cargo trabajador Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}
	

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($iddesempenio)	{
		$sql="SELECT * FROM desempenio WHERE iddesempenio='$iddesempenio'; ";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar(){
		$sql="SELECT iddesempenio, nombre_desempenio, estado FROM desempenio  WHERE iddesempenio > 1 and estado=1 AND estado_delete=1";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select() {
		$sql="SELECT * FROM desempenio where estado=1";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select_tipo_trab()	{
		$sql="SELECT * FROM tipo_trabajador where estado=1 ";
		return ejecutarConsulta($sql);		
	}
}
?>