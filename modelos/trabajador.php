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
	public function insertar($idproyecto,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$nacimiento,$tipo_trabajador,$desempenio,$c_bancaria,$email,$cargo,$banco,$tutular_cuenta,$sueldo_diario,$sueldo_mensual,$sueldo_hora,$imagen)
	{
		$sql="INSERT INTO trabajador (idproyecto,idbancos,nombres,tipo_documento,numero_documento,fecha_nacimiento,desempeno,cargo,tipo_trabajador,cuenta_bancaria,titular_cuenta,sueldo_mensual,sueldo_diario,sueldo_hora,direccion,telefono,email,imagen)
		VALUES ('$idproyecto','$banco','$nombre','$tipo_documento','$num_documento','$nacimiento','$desempenio','$cargo','$tipo_trabajador','$c_bancaria','$tutular_cuenta','$sueldo_mensual','$sueldo_diario','$sueldo_hora','$direccion','$telefono','$email','$imagen')";
		
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idtrabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$nacimiento,$tipo_trabajador,$desempenio,$c_bancaria,$email,$cargo,$banco,$tutular_cuenta,$sueldo_diario,$sueldo_mensual,$sueldo_hora,$imagen)
	{
		$sql="UPDATE trabajador SET idbancos='$banco',nombres='$nombre',tipo_documento='$tipo_documento'
		,numero_documento='$num_documento',fecha_nacimiento='$nacimiento',
		desempeno='$desempenio',cargo='$cargo',tipo_trabajador='$tipo_trabajador',
		cuenta_bancaria='$c_bancaria',titular_cuenta='$tutular_cuenta',sueldo_mensual='$sueldo_mensual',sueldo_diario='$sueldo_diario',
		sueldo_hora='$sueldo_hora',direccion='$direccion',telefono='$telefono',email='$email',imagen='$imagen' WHERE idtrabajador='$idtrabajador'";	
		
			return ejecutarConsulta($sql);
		
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

		//Implementar un método para mostrar los datos de un registro a modificar
		public function verdatos($idtrabajador)
		{
			$sql="SELECT 
			t.idbancos as idbancos, 
			t.nombres as nombres, 
			t.tipo_documento as tipo_documento, 
			t.numero_documento as numero_documento,
			t.fecha_nacimiento as fecha_nacimiento,
			t.desempeno as desempeno,
			t.cargo as cargo,
			t.tipo_trabajador as tipo_trabajador ,
			t.cuenta_bancaria as cuenta_bancaria,
			t.titular_cuenta as titular_cuenta,
			t.sueldo_mensual as sueldo_mensual,
			t.sueldo_diario as sueldo_diario,
			t.sueldo_hora as sueldo_hora,
			t.direccion as direccion,
			t.telefono as telefono,
			t.email as email,
			t.imagen as imagen,
			b.nombre as banco 
			FROM trabajador t, bancos b 
			WHERE t.idtrabajador='$idtrabajador' AND t.idbancos =b.idbancos";

			return ejecutarConsultaSimpleFila($sql);
		}
	//Implementar un método para listar los registros
	public function listar($nube_idproyecto)
	{
		$sql="SELECT * FROM trabajador WHERE idproyecto = '$nube_idproyecto'; ";
		return ejecutarConsulta($sql);		
	}

}

?>