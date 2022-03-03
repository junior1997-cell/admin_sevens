<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Usuario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($trabajador, $cargo, $login, $clave, $permisos)
	{
		
		// insertamos al usuario
		$sql="INSERT INTO usuario ( idtrabajador, cargo, login, password) VALUES ('$trabajador', '$cargo', '$login', '$clave')";

		// marcamos al trabajador como usuario
		$sql2="UPDATE trabajador SET estado_usuario='1' WHERE idtrabajador='$trabajador';";	
		ejecutarConsulta($sql2);

		$num_elementos=0;	$sw=true;

		if ($permisos != "" ) {

			$idusuarionew = ejecutarConsulta_retornarID($sql);			

			while ($num_elementos < count($permisos))
			{
				$sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES('$idusuarionew', '$permisos[$num_elementos]')";
				ejecutarConsulta($sql_detalle) or $sw = false;
				$num_elementos=$num_elementos + 1;
			}
		}

		if ($permisos != "") {

			return $sw;

		} else {

			return ejecutarConsulta($sql);
		}		
	}

	//Implementamos un método para editar registros
	public function editar($idusuario, $trabajador_old, $trabajador, $cargo, $login, $clave, $permisos)
	{
		if (!empty($trabajador) ) {

			$sql="UPDATE usuario SET idtrabajador='$trabajador', cargo='$cargo', login='$login', password='$clave' WHERE idusuario='$idusuario'";
			
			// desmarcamos al trabajador old como usuario
			$sql3="UPDATE trabajador SET estado_usuario='0' WHERE idtrabajador='$trabajador_old';";
			ejecutarConsulta($sql3);
			// marcamos al trabajador new como usuario
			$sql4="UPDATE trabajador SET estado_usuario='1' WHERE idtrabajador='$trabajador';";
			ejecutarConsulta($sql4);
		} else {
			$sql="UPDATE usuario SET idtrabajador='$trabajador_old', cargo='$cargo', login='$login', password='$clave' WHERE idusuario='$idusuario'";
		}
		
		$num_elementos=0;	$sw=true;

		if ($permisos != "" ) {

			ejecutarConsulta($sql);

			//Eliminamos todos los permisos asignados para volverlos a registrar
			$sqldel="DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";

			ejecutarConsulta($sqldel);

			while ($num_elementos < count($permisos)){

				$sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES('$idusuario', '$permisos[$num_elementos]')";
				ejecutarConsulta($sql_detalle) or $sw = false;
				$num_elementos=$num_elementos + 1;
			}
		}

		if ($permisos != "") {

			return $sw;

		} else {

			return ejecutarConsulta($sql);
		}
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idusuario)
	{
		$sql="UPDATE usuario SET estado='0' WHERE idusuario='$idusuario'";

		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar :: !!sin usar ::
	public function activar($idusuario)
	{
		$sql="UPDATE usuario SET estado='1' WHERE idusuario='$idusuario'";

		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar usuario
	public function eliminar($idusuario)
	{
		$sql="UPDATE usuario SET estado_delete='0' WHERE idusuario='$idusuario'";

		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idusuario)
	{
		$sql="SELECT u.idusuario, u.idtrabajador, u.cargo, u.login, u.password, u.estado, t.nombres FROM usuario AS u, trabajador AS t WHERE u.idusuario='$idusuario' AND u.idtrabajador = t.idtrabajador;";

		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT u.idusuario, t.nombres, t.tipo_documento, t.numero_documento, t.telefono, t.email, u.cargo, u.login, t.imagen_perfil, t.tipo_documento, u.estado
		FROM usuario as u, trabajador as t
		WHERE  u.idtrabajador = t.idtrabajador  AND u.estado=1 AND u.estado_delete=1 ORDER BY t.nombres ASC;";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los permisos marcados
	public function listarmarcados($idusuario)
	{
		$sql="SELECT * FROM usuario_permiso WHERE idusuario='$idusuario' ";
		return ejecutarConsulta($sql);
	}

	//Función para verificar el acceso al sistema
	public function verificar($login,$clave)
    {
    	$sql="SELECT u.idusuario, t.nombres, t.tipo_documento, t.numero_documento, t.telefono, t.email, u.cargo, u.login, t.imagen_perfil, t.tipo_documento
		FROM usuario as u, trabajador as t
		WHERE u.login='$login' AND u.password='$clave' AND t.estado=1 and u.estado=1 and u.estado_delete=1 and u.idtrabajador = t.idtrabajador;"; 
    	return ejecutarConsulta($sql);  
    }

	//Seleccionar Trabajador Select2
	public function select2_trabajador()
	{
		$sql="SELECT idtrabajador as id, nombres as nombre, tipo_documento as documento, numero_documento 
		FROM trabajador WHERE estado='1' AND estado_delete='1' AND estado_usuario = '0' ;";
		return ejecutarConsulta($sql);		
	}
}

?>