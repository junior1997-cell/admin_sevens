<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class PagoObrero
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora)
	{
		$sql="INSERT INTO trabajador_por_proyecto (idproyecto, idtrabajador, tipo_trabajador, cargo, desempenio, sueldo_mensual, sueldo_diario, sueldo_hora)
		VALUES ('$idproyecto', '$trabajador', '$tipo_trabajador', '$cargo', '$desempenio', '$sueldo_mensual', '$sueldo_diario', '$sueldo_hora')";
		
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar( $idtrabajador_por_proyecto,$trabajador, $tipo_trabajador, $cargo, $desempenio, $sueldo_mensual, $sueldo_diario, $sueldo_hora )
	{
		$sql="UPDATE trabajador_por_proyecto SET  idtrabajador='$trabajador', 
    tipo_trabajador='$tipo_trabajador', cargo='$cargo', desempenio='$desempenio', sueldo_mensual='$sueldo_mensual', 
    sueldo_diario='$sueldo_diario',	sueldo_hora='$sueldo_hora' WHERE idtrabajador_por_proyecto='$idtrabajador_por_proyecto'";	
		
		return ejecutarConsulta($sql);
		
	}

	//Implementar un método para listar los registros
	public function listar_tbla_principal($nube_idproyecto)
	{
		$sql="SELECT t.nombres AS nombres_trabajador, p.fecha_pago_obrero, t.telefono, t.imagen_perfil, t.tipo_documento, t.numero_documento, tt.nombre AS nombre_tipo, 
		ct.nombre AS nombre_cargo, tpp.idtrabajador_por_proyecto, tpp.fecha_inicio, tpp.fecha_fin,  tpp.sueldo_mensual,   SUM(rqsa.total_hn) AS total_hn, SUM(rqsa.total_he) AS total_he, 
		SUM(rqsa.total_dias_asistidos) AS total_dias_asistidos, SUM(rqsa.sabatical) AS sabatical, SUM(rqsa.sabatical_manual_1) AS sabatical_manual_1, 
		SUM(rqsa.sabatical_manual_2) AS sabatical_manual_2, SUM(rqsa.pago_parcial_hn) AS pago_parcial_hn, SUM(rqsa.pago_parcial_he) AS pago_parcial_he, 
		SUM(rqsa.adicional_descuento) AS adicional_descuento,  SUM(rqsa.pago_quincenal) AS pago_quincenal, 
		SUM(rqsa.estado_envio_contador) AS sum_estado_envio_contador
		FROM resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp, proyecto AS p, trabajador AS t, tipo_trabajador AS tt, cargo_trabajador AS ct
		WHERE rqsa.estado_envio_contador = '1' AND tpp.idproyecto = '$nube_idproyecto' AND  rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idtrabajador = t.idtrabajador AND tpp.idcargo_trabajador = ct.idcargo_trabajador AND ct.idtipo_trabjador = tt.idtipo_trabajador  AND p.idproyecto = tpp.idproyecto
		GROUP BY rqsa.idtrabajador_por_proyecto;";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar_q_s($idtrabajador_x_proyecto)
	{
		$sql="SELECT tpp.sueldo_hora, rqsa.idresumen_q_s_asistencia, rqsa.idtrabajador_por_proyecto, rqsa.numero_q_s, rqsa.fecha_q_s_inicio, rqsa.fecha_q_s_fin, rqsa.total_hn, rqsa.total_he, rqsa.total_dias_asistidos, rqsa.sabatical, rqsa.sabatical_manual_1, rqsa.sabatical_manual_2, rqsa.pago_parcial_hn, rqsa.pago_parcial_he, rqsa.adicional_descuento, rqsa.descripcion_descuento, rqsa.pago_quincenal, rqsa.estado_envio_contador
		FROM resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp
		WHERE rqsa.idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' AND rqsa.estado_envio_contador = '1' AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto;";
		return ejecutarConsultaArray($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idtrabajador)
	{
		$sql="UPDATE trabajador_por_proyecto SET estado='0' WHERE idtrabajador_por_proyecto='$idtrabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idtrabajador)
	{
		$sql="UPDATE trabajador_por_proyecto SET estado='1' WHERE idtrabajador_por_proyecto='$idtrabajador'";
		return ejecutarConsulta($sql);
	}

  	//Seleccionar Trabajador Select2
	public function select2_trabajador()
	{
		$sql="SELECT idtrabajador as id, nombres as nombre, tipo_documento as documento, numero_documento FROM trabajador WHERE estado='1';";
		return ejecutarConsulta($sql);		
	}

}

?>