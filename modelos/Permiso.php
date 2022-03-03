<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Permiso
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	
	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM permiso ORDER BY nombre ASC";

		return ejecutarConsulta($sql);		
	}

	public function ver_usuarios($id_permiso)
	{
		$sql = "SELECT t.nombres, t.tipo_documento, t.numero_documento, t.imagen_perfil,  u.cargo , u.created_at
		FROM permiso as p, usuario_permiso as up, usuario as u, trabajador as t
		WHERE p.idpermiso = up.idpermiso and up.idusuario = u.idusuario and u.idtrabajador = t.idtrabajador and u.estado='1' AND u.estado_delete='1' and p.idpermiso = '$id_permiso';";

		return ejecutarConsulta($sql);	
	}

}

?>