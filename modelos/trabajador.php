<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Trabajador
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
	{
		$sql="INSERT INTO trabajador (nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,password,imagen)
		VALUES ('$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$cargo','$login','$clave','$imagen')";
		//return ejecutarConsulta($sql);		
		$num_elementos=0;	$sw=true;

		if ($permisos != "" ) {

			$idtrabajadornew = ejecutarConsulta_retornarID($sql);			

			while ($num_elementos < count($permisos))
			{
				$sql_detalle = "INSERT INTO trabajador_permiso(idtrabajador, idpermiso) VALUES('$idtrabajadornew', '$permisos[$num_elementos]')";
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
	public function editar($idtrabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
	{
		$sql="UPDATE trabajador SET nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',login='$login',password='$clave',imagen='$imagen' WHERE idtrabajador='$idtrabajador'";		 	

		$num_elementos=0;	$sw=true;

		if ($permisos != "" ) {

			ejecutarConsulta($sql);

			//Eliminamos todos los permisos asignados para volverlos a registrar
			$sqldel="DELETE FROM trabajador_permiso WHERE idtrabajador='$idtrabajador'";

			ejecutarConsulta($sqldel);

			while ($num_elementos < count($permisos)){

				$sql_detalle = "INSERT INTO trabajador_permiso(idtrabajador, idpermiso) VALUES('$idtrabajador', '$permisos[$num_elementos]')";
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
	public function desactivar($idtrabajador)
	{
		$sql="UPDATE trabajador SET estado='0' WHERE idtrabajador='$idtrabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idtrabajador)
	{
		$sql="UPDATE trabajador SET estado='1' WHERE idtrabajador='$idtrabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idtrabajador)
	{
		$sql="SELECT * FROM trabajador WHERE idtrabajador='$idtrabajador'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM trabajador";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los permisos marcados
	public function listarmarcados($idtrabajador)
	{
		$sql="SELECT * FROM trabajador_permiso WHERE idtrabajador='$idtrabajador'";
		return ejecutarConsulta($sql);
	}

	//Función para verificar el acceso al sistema
	public function verificar($login,$clave)
    {
    	$sql="SELECT u.idtrabajador, t.nombres,t.tipo_documento,t.numero_documento,t.telefono,t.email,u.cargo,u.login,t.imagen,t.tipo_documento
		FROM admin_sevens.trabajador as u, admin_sevens.trabajador as t
		WHERE u.login='$login' AND u.password='$clave' AND t.estado=1 and u.estado=1 and u.idtrabajador = t.idtrabajador;"; 
    	return ejecutarConsulta($sql);  
    }
}

?>