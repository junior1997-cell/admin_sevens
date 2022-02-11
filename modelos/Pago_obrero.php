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
	public function insertar_pagos_x_q_s($idresumen_q_s_asistencia, $forma_de_pago, $cuenta_deposito, $monto, $descripcion, $doc1)
	{
		$sql="INSERT INTO  pagos_q_s_obrero( idresumen_q_s_asistencia, cuenta_deposito, forma_de_pago, monto_deposito, baucher, descripcion) 
		VALUES ('$idresumen_q_s_asistencia', '$cuenta_deposito', '$forma_de_pago', '$monto', '$doc1', '$descripcion');";
		
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar_pagos_x_q_s( $idpagos_q_s_obrero, $idresumen_q_s_asistencia, $forma_pago, $cuenta_deposito, $monto, $descripcion, $doc1 )
	{
		$sql="UPDATE pagos_q_s_obrero SET idresumen_q_s_asistencia='$idresumen_q_s_asistencia', cuenta_deposito='$cuenta_deposito', 
		forma_de_pago='$forma_pago', monto_deposito='$monto', baucher='$doc1', descripcion='$descripcion'
		WHERE idpagos_q_s_obrero = '$idpagos_q_s_obrero,'";	
		
		return ejecutarConsulta($sql);
		
	}	

	//Implementamos un método para editar registros
	public function editar_recibo_x_honorario($idresumen_q_s_asistencia_rh, $doc2)
	{
		$sql="UPDATE resumen_q_s_asistencia SET recibos_x_honorarios = '$doc2' WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia_rh'";	
		
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
		$sql="SELECT tpp.sueldo_hora, rqsa.idresumen_q_s_asistencia, rqsa.idtrabajador_por_proyecto, rqsa.numero_q_s, rqsa.fecha_q_s_inicio, rqsa.fecha_q_s_fin, rqsa.total_hn, rqsa.total_he, rqsa.total_dias_asistidos, rqsa.sabatical, rqsa.sabatical_manual_1, rqsa.sabatical_manual_2, rqsa.pago_parcial_hn, rqsa.pago_parcial_he, rqsa.adicional_descuento, rqsa.descripcion_descuento, rqsa.pago_quincenal, rqsa.estado_envio_contador, rqsa.recibos_x_honorarios
		FROM resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp
		WHERE rqsa.idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' AND rqsa.estado_envio_contador = '1' AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto;";
		return ejecutarConsultaArray($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function listar_pagos_x_q_s($idresumen_q_s_asistencia)
	{
		$sql="SELECT idpagos_q_s_obrero, idresumen_q_s_asistencia, cuenta_deposito, forma_de_pago, monto_deposito, baucher, descripcion, estado 
		FROM pagos_q_s_obrero
		WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";

		return ejecutarConsulta($sql);
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

	// obtebnemos los "BAUCHER DE DEPOSITOS" para eliminar
	public function obtenerDocs($id) {

        $sql = "SELECT baucher FROM pagos_x_mes_administrador WHERE idpagos_x_mes_administrador = '$id'";

        return ejecutarConsulta($sql);
    }

	// obtebnemos los "RECIBO X HONORARIO" para eliminar
	public function obtenerDocs2($id) {

        $sql = "SELECT recibos_x_honorarios FROM resumen_q_s_asistencia WHERE idresumen_q_s_asistencia = '$id'";

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