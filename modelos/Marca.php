<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Marca
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre_marca, $descripcion_marca) {
		//var_dump($nombre);die();
		$sql="INSERT INTO marca(nombre_marca, descripcion, user_created)VALUES('$nombre_marca', '$descripcion_marca','" . $_SESSION['idusuario'] . "' )";

		$insertar =  ejecutarConsulta_retornarID($sql); 
		if ($insertar['status'] == false) {  return $insertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('marca','".$insertar['data']."','Nueva marca registrado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $insertar;
	}

	//Implementamos un método para editar registros
	public function editar($idmarca, $nombre_marca, $descripcion_marca) {
		$sql="UPDATE marca SET nombre_marca='$nombre_marca', descripcion ='$descripcion_marca', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idmarca='$idmarca'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('marca','$idmarca','Marca editada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar color
	public function desactivar($idmarca) {
		$sql="UPDATE marca SET estado='0', user_trash= '" . $_SESSION['idusuario'] . "' WHERE idmarca='$idmarca'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('Marca','".$idmarca."','Marca desactivada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar marca
	public function activar($idmarca) {
		$sql="UPDATE marca SET estado='1' WHERE idmarca='$idmarca'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar marca
	public function eliminar($idmarca) {
		$sql="UPDATE marca SET estado_delete='0', user_delete= '" . $_SESSION['idusuario'] . "' WHERE idmarca='$idmarca'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('marca', '$idmarca', 'Marca Eliminada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idmarca) {
		$sql="SELECT * FROM marca WHERE idmarca='$idmarca'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function tabla_principal_marca() {
		$sql="SELECT * FROM marca WHERE idmarca>'1' AND estado=1  AND estado_delete=1 ORDER BY nombre_marca ASC";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function select() {
		$sql="SELECT * FROM marca where estado=1";
		return ejecutarConsulta($sql);		
	}
}
?>