<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Valorizacion
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idproyecto, $trabajador, $horas_trabajo_dia, $fecha)
	{
		 
		
		return $idproyecto;
			
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
		$sql="SELECT tp.idtrabajador_por_proyecto, t.nombres , t.tipo_documento as documento, t.numero_documento, tp.cargo, t.imagen_perfil, atr.fecha_asistencia, atr.horas_normal_dia, atr.horas_extras_dia 
		FROM trabajador AS t, trabajador_por_proyecto AS tp, asistencia_trabajador AS atr 
		WHERE t.idtrabajador = tp.idtrabajador AND tp.idtrabajador_por_proyecto = atr.idtrabajador_por_proyecto AND atr.idasistencia_trabajador = '$idasistencia_trabajador';";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar asistencia
	public function listar($nube_idproyecto)
	{
		$sql="SELECT at.idtrabajador_por_proyecto, t.idtrabajador AS idtrabajador, t.nombres AS nombre, t.tipo_documento as tipo_doc, t.numero_documento AS num_doc,  t.imagen_perfil AS imagen, tp.sueldo_hora AS sueldo_hora, tp.sueldo_mensual AS sueldo_mensual, 
		SUM(at.horas_normal_dia) AS total_horas_normal, SUM(at.horas_extras_dia) AS total_horas_extras, 
		SUM(at.sabatical) AS total_sabatical, at.estado as estado, p.fecha_inicio AS fecha_inicio_proyect, tp.cargo
		FROM trabajador AS t, trabajador_por_proyecto AS tp, asistencia_trabajador AS at,  proyecto AS p
		WHERE t.idtrabajador = tp.idtrabajador AND tp.idtrabajador_por_proyecto = at.idtrabajador_por_proyecto AND tp.idproyecto = p.idproyecto AND at.estado=1 AND tp.idproyecto = '$nube_idproyecto'
		GROUP BY tp.idtrabajador;";
		return ejecutarConsulta($sql);		
	}
	
	// Data para listar lo bototnes por quincena
	public function listarquincenas($nube_idproyecto){

		$sql="SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin, p.plazo FROM proyecto as p WHERE p.idproyecto='$nube_idproyecto' AND p.fecha_inicio != p.fecha_fin";
        
		return ejecutarConsultaSimpleFila($sql);
	}

	//ver detalle quincena (cuando presiono el boton de cada quincena)
	public function ver_detalle_quincena($f1, $f2, $nube_idproyect){

		$sql="SELECT v.idvalorizacion, v.idproyecto, v.nombre, v.doc_valorizacion, v.fecha_quincena, v.estado
		FROM valorizacion as v
		WHERE v.idproyecto = '$nube_idproyect' BETWEEN '$f1' AND '$f2';";
		$data1 = ejecutarConsultaArray($sql);

		$sql2 = "SELECT p.doc1_contrato_obra, p.doc2_entrega_terreno, p.doc3_inicio_obra FROM proyecto as p WHERE p.idproyecto = '$nube_idproyect';";
		$data2 = ejecutarConsultaSimpleFila($sql2);

		$results = array(
			"data1" => $data1,
			"data2" => $data2
		);

		return $results ;
	}

		

}

?>