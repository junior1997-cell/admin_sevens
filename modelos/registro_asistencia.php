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
	public function insertar($idproyecto, $trabajador, $horas_trabajo_dia, $fecha)
	{
		

		$num_elementos=0;
		$sw=true;		

		while ($num_elementos < count($trabajador))
		{
			$horas_acumuladas='';
			$horas_trabajo='';
			$sabatical='';
			$pago_dia='';
			$horas_extras='';
			$pago_horas_extras='';
			$horas_desglose = substr($horas_trabajo_dia[$num_elementos], 0, 2).'.'.(floatval(substr($horas_trabajo_dia[$num_elementos], 3, 5))*100)/60;

			$sql1="SELECT tp.sueldo_hora AS sueldo_hora FROM trabajador_por_proyecto AS tp WHERE tp.idtrabajador_por_proyecto = '$trabajador[$num_elementos]' AND tp.idproyecto = '$idproyecto';";
			$sueldoxhora_trab = ejecutarConsultaSimpleFila($sql1);

			$sql2 = "SELECT sum(atr.horas_normal_dia) as horas_trabajo,sum(atr.sabatical) as sabatical
				FROM asistencia_trabajador as atr, trabajador_por_proyecto AS tp 
				WHERE tp.idtrabajador_por_proyecto = '$trabajador[$num_elementos]' AND atr.idtrabajador_por_proyecto = tp.idtrabajador_por_proyecto AND tp.idproyecto = '$idproyecto';";
			$datos = ejecutarConsultaSimpleFila($sql2);

			if ($datos==NULL) {
				if (floatval($horas_desglose)>8) {
				$horas_extras=floatval($horas_desglose)-8;
				$pago_horas_extras=$horas_extras*$sueldoxhora_trab['sueldo_hora'];
				$horas_trabajo=8;
				}else{
				$horas_extras=0;
				$pago_horas_extras=0;
				$horas_trabajo=floatval($horas_desglose);
				}
				$sabatical=0;
				$pago_dia=floatval($horas_trabajo)*$sueldoxhora_trab['sueldo_hora'];

			}else{
				$horas_acumuladas=floatval($horas_desglose)+$datos['horas_trabajo'];

				$caculamos = floatval( substr($horas_acumuladas/44, 0, 1));

				if ( $caculamos == $datos['sabatical'] && $horas_acumuladas < 44) {

				$sabatical=0;

				}else {

				if ( $caculamos == $datos['sabatical'] && $horas_acumuladas >= 44) {

					$sabatical=0;

				}else {

					$sabatical=1;
				}						 
				}

				if (floatval($horas_desglose)>8) {
				$horas_extras=floatval($horas_desglose)-8;
				$pago_horas_extras=$horas_extras*$sueldoxhora_trab['sueldo_hora'];
				$horas_trabajo=8;
				}else{
				$horas_extras=0;
				$pago_horas_extras=0;
				$horas_trabajo=floatval($horas_desglose);
				}

				$pago_dia=floatval($horas_desglose)*$sueldoxhora_trab['sueldo_hora'];
			}

			 
			$sql_detalle="INSERT INTO asistencia_trabajador (	idtrabajador_por_proyecto, 	horas_normal_dia, pago_normal_dia, horas_extras_dia, pago_horas_extras, sabatical, fecha_asistencia)
			VALUES ('$trabajador[$num_elementos]', '$horas_trabajo', '$pago_dia', '$horas_extras', '$pago_horas_extras', '$sabatical', '$fecha')";
			ejecutarConsulta($sql_detalle) or $sw = false;

				
			// echo "trabaja: $trabajador[$num_elementos], hn: $horas_trabajo, pn: $pago_dia, hx: $horas_extras, pago: $pago_horas_extras, sabat: $sabatical \n";
			$num_elementos=$num_elementos + 1;
		}
		
		return $sw;
			
	}

	//Implementamos un método para editar registros
	public function editar($idasistencia_trabajador, $trabajador, $horas_trabajo, $pago_dia, $horas_extras, $pago_horas_extras, $sabatical)
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
		$sql="SELECT t.idtrabajador AS idtrabajador, t.nombres AS nombre, t.tipo_documento as tipo_doc, t.numero_documento AS num_doc,  t.imagen_perfil AS imagen, tp.sueldo_hora AS sueldo_hora, tp.sueldo_mensual AS sueldo_mensual, 
		SUM(at.horas_normal_dia) AS total_horas_normal, SUM(at.horas_extras_dia) AS total_horas_extras, 
		SUM(at.sabatical) AS total_sabatical, at.estado as estado, p.fecha_inicio AS fecha_inicio_proyect, tp.cargo
		FROM trabajador AS t, trabajador_por_proyecto AS tp, asistencia_trabajador AS at,  proyecto AS p
		WHERE t.idtrabajador = tp.idtrabajador AND tp.idtrabajador_por_proyecto = at.idtrabajador_por_proyecto AND tp.idproyecto = p.idproyecto AND t.estado=1 AND tp.idproyecto = '$nube_idproyecto'
		GROUP BY tp.idtrabajador;";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar asistencia
	public function listar_asis_individual($trabajadorproyecto)
	{
		$sql="SELECT atra.idasistencia_trabajador, atra.idasistencia_trabajador, atra.sabatical, atra.horas_normal_dia, atra.pago_normal_dia, atra.horas_extras_dia, atra.pago_horas_extras, atra.fecha_asistencia, atra.estado, t.nombres as trabajador
		FROM asistencia_trabajador AS atra, trabajador_por_proyecto AS tp, trabajador AS t 
		WHERE atra.idtrabajador_por_proyecto = tp.idtrabajador_por_proyecto AND tp.idtrabajador = t.idtrabajador AND atra.idtrabajador_por_proyecto = '$trabajadorproyecto';";
		return ejecutarConsulta($sql);		
	}
	
	//traemos el sueldo po hora del trabajador
	public function sueldoxhora($idtrabajador, $idproyecto){
		$sql="SELECT tp.sueldo_hora AS sueldo_hora FROM trabajador_por_proyecto AS tp WHERE tp.idtrabajador=2 AND tp.idproyecto=1;";
		return ejecutarConsultaSimpleFila($sql);
	}

	//visualizar Horas y sueldo
	public function horas_acumulada($trabajador,$idproyecto){
		$sql="SELECT sum(atr.horas_trabajador) as horas_trabajo,sum(atr.sabatical) as sabatical
		FROM asistencia_trabajador as atr, trabajador as t WHERE atr.idtrabajador='$trabajador' AND atr.idtrabajador= t.idtrabajador AND t.idproyecto='$idproyecto';";
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

	//listarquincenas_b
	public function listarquincenas_b($nube_idproyecto){
		$sql="SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin, p.plazo FROM proyecto as p WHERE p.idproyecto='$nube_idproyecto'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//ver detalle quincena 
	public function ver_detalle_quincena($f1,$f2,$nube_idproyect){

		$sql="SELECT t.idtrabajador as idtrabajador, t.nombres as nombres, t.tipo_documento as tipo_doc, 
		t.numero_documento as num_doc, t.cargo as cargo , t.imagen as imagen, t.sueldo_hora as sueldo_hora, t.sueldo_diario as sueldo_diario,
		t.sueldo_mensual as sueldo_mensual, SUM(atr.horas_trabajador) as total_horas, SUM(atr.horas_extras_dia) as horas_extras, 
		SUM(atr.sabatical) as total_sabatical, atr.estado as estado, p.fecha_inicio as fecha_inicio_proyect 
		FROM asistencia_trabajador as atr, trabajador as t, proyecto as p 
		WHERE atr.idtrabajador=t.idtrabajador AND t.estado=1 AND t.idproyecto='$nube_idproyect' AND t.idproyecto=p.idproyecto AND atr.fecha_asistencia BETWEEN '$f1' AND '$f2' GROUP BY atr.idtrabajador";
		return ejecutarConsulta($sql);
	}

	//ver detalle quincena por trabador y por dìa
	public function ver_detalle_quincena_dias($f1,$f2,$nube_idproyect,$idtrabajador){

		$sql="SELECT atr.idasistencia_trabajador as idasistencia_trabajador, 
		atr.idtrabajador as idtrabajador, 
		atr.horas_trabajador as horas_trabajador,
		atr.horas_extras_dia as horas_extras_dia,
		atr.fecha_asistencia as fecha_asistencia
		FROM asistencia_trabajador as atr, trabajador as t
		WHERE atr.idtrabajador= t.idtrabajador AND t.idtrabajador='$idtrabajador' AND t.idproyecto='$nube_idproyect' AND atr.fecha_asistencia BETWEEN '$f1' AND '$f2'";
		return ejecutarConsulta($sql);
	}	

	//Seleccionar Trabajador Select2
	public function lista_trabajador($nube_idproyecto){

		$sql="SELECT tp.idtrabajador_por_proyecto, t.nombres , t.tipo_documento as documento, t.numero_documento, tp.cargo, t.imagen_perfil
		FROM trabajador AS t, trabajador_por_proyecto AS tp 
		WHERE t.idtrabajador = tp.idtrabajador AND tp.idproyecto = '$nube_idproyecto' AND tp.estado = 1;";
		
		return ejecutarConsulta($sql);		
	}

}

?>