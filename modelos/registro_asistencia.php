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

	//Implementar un método para listar asistencia
	public function listar($nube_idproyecto)
	{
		$sql="SELECT t.idtrabajador as idtrabajador, t.nombres as nombre, t.tipo_documento as tipo_doc, 
		t.numero_documento as num_doc, t.cargo as cargo , t.imagen as imagen, t.sueldo_hora as sueldo_hora,
		t.sueldo_mensual as sueldo_mensual, SUM(atr.horas_trabajador) as total_horas, SUM(atr.horas_extras_dia) as horas_extras, 
		SUM(atr.sabatical) as total_sabatical, atr.estado as estado, p.fecha_inicio as fecha_inicio_proyect 
		FROM asistencia_trabajador as atr, trabajador as t, proyecto as p 
		WHERE atr.idtrabajador=t.idtrabajador AND t.estado=1 AND t.idproyecto='$nube_idproyecto' AND t.idproyecto=p.idproyecto GROUP BY atr.idtrabajador";
		return ejecutarConsulta($sql);		
	}
	
	//traemos el sueldo po hora del trabajador
	public function sueldoxhora($idtrabajador,$idproyecto){
		$sql="SELECT t.sueldo_hora AS sueldo_hora FROM trabajador as t WHERE t.idtrabajador='$idtrabajador' AND t.idproyecto='$idproyecto';";
		return ejecutarConsultaSimpleFila($sql);
	}
	//visualizar registro asistencia por dìa
	public function registro_asist_trab($id_trabajador){
		$sql="SELECT atr.idasistencia_trabajador as idasistencia, atr.horas_trabajador as horas_trabajador, 
		atr.horas_extras_dia as horas_extras_dia, atr.sabatical as sabatical, atr.fecha as fecha, t.nombres as nombres, 
		t.numero_documento as numero_documento, p.fecha_inicio as fecha_inicio_p FROM asistencia_trabajador as atr, trabajador as t, proyecto as p WHERE 
		atr.idtrabajador='$id_trabajador' AND atr.idtrabajador=t.idtrabajador AND t.idproyecto=1 AND t.idproyecto=p.idproyecto";
		return ejecutarConsultaSimpleFila($sql);
	}
	//=========================
		//visualizar Horas y sueldo
	public function horas_acumulada($trabajador,$idproyecto){
		$sql="SELECT sum(atr.horas_trabajador) as horas_trabajo,sum(atr.sabatical) as sabatical
		FROM asistencia_trabajador as atr, trabajador as t WHERE atr.idtrabajador='$trabajador' AND atr.idtrabajador= t.idtrabajador AND t.idproyecto='$idproyecto';";
		return ejecutarConsultaSimpleFila($sql);
		
	}
	//Seleccionar Trabajador Select2
	public function select2_trabajador($nube_idproyecto)
	{
		$sql="SELECT idtrabajador as id, nombres as nombre, tipo_documento as documento, numero_documento, cargo FROM trabajador WHERE estado='1' AND idproyecto='$nube_idproyecto';";
		return ejecutarConsulta($sql);		
	}

}

?>