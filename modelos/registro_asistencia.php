<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Asistencia_trabajador
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($trabajador,$horas_trabajo,$pago_dia,$horas_extras,$pago_horas_extras,$sabatical)
	{
		$sql="INSERT INTO asistencia_trabajador (idtrabajador,horas_trabajador,pago_dia,horas_extras_dia,pago_horas_extras,sabatical)
		VALUES ('$trabajador','$horas_trabajo','$pago_dia','$horas_extras','$pago_horas_extras','$sabatical')";
		
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idasistencia_trabajador,$trabajador,$horas_trabajo,$pago_dia,$horas_extras,$pago_horas_extras,$sabatical)
	{
		//var_dump($idasistencia_trabajador,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta);die;
		
		$sql="UPDATE asistencia_trabajador SET 
		idtrabajador='$trabajador',
		horas_trabajador='$horas_trabajo',
		pago_dia='$pago_dia',
		horas_extras_dia='$horas_extras',
		pago_horas_extras='$pago_horas_extras',
		sabatical='$sabatical'
		WHERE idasistencia_trabajador='$idasistencia_trabajador'";	
		
			return ejecutarConsulta($sql);
		
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idasistencia_trabajador)
	{
		$sql="UPDATE asistencia_trabajador SET estado='0' WHERE idasistencia_trabajador='$idasistencia_trabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idasistencia_trabajador)
	{
		$sql="UPDATE asistencia_trabajador SET estado='1' WHERE idasistencia_trabajador='$idasistencia_trabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idasistencia_trabajador)
	{
		$sql="SELECT * FROM asistencia_trabajador WHERE idasistencia_trabajador='$idasistencia_trabajador'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM asistencia_trabajador";
		return ejecutarConsulta($sql);		
	}
	//traemos el sueldo po hora del trabajador
	public function sueldoxhora($idtrabajador){
		$sql="SELECT t.sueldo_hora AS sueldo_hora FROM trabajador as t WHERE t.idtrabajador='$idtrabajador'";
		return ejecutarConsultaSimpleFila($sql);
	}
	//=========================
	public function horas_acumulada($trabajador){
		$sql="SELECT sum(atr.horas_trabajador) as horas_trabajo,sum(atr.sabatical) as sabatical
		FROM asistencia_trabajador as atr, trabajador as t WHERE atr.idtrabajador='$trabajador' AND atr.idtrabajador= t.idtrabajador";
		return ejecutarConsultaSimpleFila($sql);
		
	}
	//Seleccionar Trabajador Select2
	public function select2_trabajador()
	{
		$sql="SELECT idtrabajador as id, nombres as nombre, tipo_documento as documento, numero_documento, cargo FROM trabajador WHERE estado='1';";
		return ejecutarConsulta($sql);		
	}

}

?>