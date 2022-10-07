<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Glosa
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar_glosa($nombre_glosa, $descripcion_glosa) {
		//var_dump($nombre);die();
		$sql="INSERT INTO glosa(nombre_glosa, descripcion, user_created)VALUES('$nombre_glosa', '$descripcion_glosa','" . $_SESSION['idusuario'] . "' )";

		$insertar =  ejecutarConsulta_retornarID($sql); 
		if ($insertar['status'] == false) {  return $insertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('glosa','".$insertar['data']."','Nueva glosa registrado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $insertar;
	}

	//Implementamos un método para editar registros
	public function editar_glosa($idglosa, $nombre_glosa, $descripcion_glosa) {
		$sql="UPDATE glosa SET nombre_glosa='$nombre_glosa', descripcion ='$descripcion_glosa', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idglosa='$idglosa'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('glosa','$idglosa','Marca editada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar color
	public function desactivar_glosa($idglosa) {
		$sql="UPDATE glosa SET estado='0', user_trash= '" . $_SESSION['idusuario'] . "' WHERE idglosa='$idglosa'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('Marca','".$idglosa."','Marca desactivada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar glosa
	public function activar_glosa($idglosa) {
		$sql="UPDATE glosa SET estado='1' WHERE idglosa='$idglosa'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar glosa
	public function eliminar_glosa($idglosa) {
		$sql="UPDATE glosa SET estado_delete='0', user_delete= '" . $_SESSION['idusuario'] . "' WHERE idglosa='$idglosa'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('glosa', '$idglosa', 'Glosa Eliminada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar_glosa($idglosa) {
		$sql="SELECT * FROM glosa WHERE idglosa='$idglosa'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function tabla_principal_glosa() {
		$sql="SELECT * FROM glosa WHERE idglosa>'1' AND estado=1  AND estado_delete=1 ORDER BY nombre_glosa ASC";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function select() {
		$sql="SELECT * FROM glosa where estado=1";
		return ejecutarConsulta($sql);		
	}
}
?>