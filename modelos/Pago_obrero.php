<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class PagoObrero
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//INSERTAR - DEPOSTOS
	public function insertar_pagos_x_q_s($idresumen_q_s_asistencia, $forma_de_pago, $cuenta_deposito, $monto, $descripcion, $doc1)
	{
		$sql="INSERT INTO  pagos_q_s_obrero( idresumen_q_s_asistencia, cuenta_deposito, forma_de_pago, monto_deposito, baucher, descripcion) 
		VALUES ('$idresumen_q_s_asistencia', '$cuenta_deposito', '$forma_de_pago', '$monto', '$doc1', '$descripcion');";
		
		return ejecutarConsulta($sql);
			
	}

	//EDITAR - DEPOSTOS
	public function editar_pagos_x_q_s( $idpagos_q_s_obrero, $idresumen_q_s_asistencia, $forma_pago, $cuenta_deposito, $monto, $descripcion, $doc1 )
	{
		$sql="UPDATE pagos_q_s_obrero SET idresumen_q_s_asistencia='$idresumen_q_s_asistencia', cuenta_deposito='$cuenta_deposito', 
		forma_de_pago='$forma_pago', monto_deposito='$monto', baucher='$doc1', descripcion='$descripcion'
		WHERE idpagos_q_s_obrero = '$idpagos_q_s_obrero,'";	
		
		return ejecutarConsulta($sql);
		
	}	

	//EDITAR - RECIBO X HONORARIO
	public function editar_recibo_x_honorario($idresumen_q_s_asistencia_rh, $doc2)
	{
		$sql="UPDATE resumen_q_s_asistencia SET recibos_x_honorarios = '$doc2' WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia_rh'";	
		
		return ejecutarConsulta($sql);		
	}

	//TABLA PRINCIPAL
	public function listar_tbla_principal($nube_idproyecto)
	{
		$data = Array();

		$sql_1="SELECT t.nombres AS nombres_trabajador, p.fecha_pago_obrero, t.telefono, t.imagen_perfil, t.tipo_documento, t.numero_documento, t.cuenta_bancaria_format AS cuenta_bancaria, 
		tt.nombre AS nombre_tipo, ct.nombre AS nombre_cargo, tpp.idtrabajador_por_proyecto, tpp.fecha_inicio, tpp.fecha_fin,  tpp.sueldo_mensual,   
		SUM(rqsa.total_hn) AS total_hn, SUM(rqsa.total_he) AS total_he, SUM(rqsa.total_dias_asistidos) AS total_dias_asistidos, SUM(rqsa.sabatical) AS sabatical, 
		SUM(rqsa.sabatical_manual_1) AS sabatical_manual_1, SUM(rqsa.sabatical_manual_2) AS sabatical_manual_2, SUM(rqsa.pago_parcial_hn) AS pago_parcial_hn, 
		SUM(rqsa.pago_parcial_he) AS pago_parcial_he, SUM(rqsa.adicional_descuento) AS adicional_descuento,  SUM(rqsa.pago_quincenal) AS pago_quincenal, 
		SUM(rqsa.estado_envio_contador) AS sum_estado_envio_contador
		FROM resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp, proyecto AS p, trabajador AS t, tipo_trabajador AS tt, cargo_trabajador AS ct
		WHERE rqsa.estado_envio_contador = '1' AND tpp.idproyecto = '$nube_idproyecto' AND  rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idtrabajador = t.idtrabajador AND tpp.idcargo_trabajador = ct.idcargo_trabajador AND ct.idtipo_trabjador = tt.idtipo_trabajador  AND p.idproyecto = tpp.idproyecto
		GROUP BY rqsa.idtrabajador_por_proyecto ORDER BY t.nombres;";
		$trabajdor = ejecutarConsultaArray($sql_1);

		if ( !empty($trabajdor) ) {

			foreach ($trabajdor as $key => $value) {

				$id = $value['idtrabajador_por_proyecto'];

				$sql_2 = "SELECT SUM(pqso.monto_deposito) AS total_deposito
				FROM trabajador_por_proyecto AS tpp, resumen_q_s_asistencia AS rqsa, pagos_q_s_obrero  AS pqso 
				WHERE tpp.idtrabajador_por_proyecto = rqsa.idtrabajador_por_proyecto AND rqsa.idresumen_q_s_asistencia = pqso.idresumen_q_s_asistencia AND pqso.estado = '1' AND tpp.idtrabajador_por_proyecto = '$id';";
				$depositos = ejecutarConsultaSimpleFila($sql_2);

				$data[] = array(
					'nombres_trabajador'=> $value['nombres_trabajador'],
					'fecha_pago_obrero' => $value['fecha_pago_obrero'],
					'telefono' 			=> $value['telefono'],
					'imagen_perfil' 	=> $value['imagen_perfil'],
					'tipo_documento' 	=> $value['tipo_documento'],
					'numero_documento' 	=> $value['numero_documento'],
					'cuenta_bancaria' 	=> $value['cuenta_bancaria'],
					'nombre_tipo' 		=> $value['nombre_tipo'],
					'nombre_cargo' 		=> $value['nombre_cargo'],
					'idtrabajador_por_proyecto' => $value['idtrabajador_por_proyecto'],
					'fecha_inicio' 		=> $value['fecha_inicio'],
					'fecha_fin' 		=> $value['fecha_fin'],
					'sueldo_mensual' 	=> $retVal_1 = (empty($value['sueldo_mensual'])) ? 0 : $value['sueldo_mensual'],
					'total_hn' 			=> $retVal_2 = (empty($value['total_hn'])) ? 0 : $value['total_hn'],
					'total_he' 			=> $retVal_3 = (empty($value['total_he'])) ? 0 : $value['total_he'],
					'total_dias_asistidos' => $retVal_4 = (empty($value['total_dias_asistidos'])) ? 0 : $value['total_dias_asistidos'],
					'sabatical' 		=> $retVal_5 = (empty($value['sabatical'])) ? 0 : $value['sabatical'],
					'sabatical_manual_1'=> $value['sabatical_manual_1'],
					'sabatical_manual_2'=> $value['sabatical_manual_2'],
					'pago_parcial_hn' 	=> $retVal_7 = (empty($value['pago_parcial_hn'])) ? 0 : $value['pago_parcial_hn'],
					'pago_parcial_he' 	=> $retVal_8 = (empty($value['pago_parcial_he'])) ? 0 : $value['pago_parcial_he'],
					'adicional_descuento' => $retVal_9 = (empty($value['adicional_descuento'])) ? 0 : $value['adicional_descuento'],
					'pago_quincenal' 	=> $retVal_10 = (empty($value['pago_quincenal'])) ? 0 : $value['pago_quincenal'],
					'sum_estado_envio_contador' => $retVal_11 = (empty($value['sum_estado_envio_contador'])) ? 0 : $value['sum_estado_envio_contador'],

					'total_deposito' 	=> $retVal_12 = (empty($depositos)) ? 0 : $retVal_12 = (empty($depositos['total_deposito'])) ? 0 : $depositos['total_deposito']
				);
			}
			
		}		

		return json_encode($data, true);		
	}

	// Obtenemos los totales - TABLA PRINCIPAL
	public function mostrar_total_tbla_principal($id)
	{
		$sql_1 = "SELECT SUM(pqso.monto_deposito) AS total_deposito_x_proyecto
		FROM trabajador_por_proyecto AS tpp, resumen_q_s_asistencia AS rqsa, pagos_q_s_obrero  AS pqso 
		WHERE tpp.idtrabajador_por_proyecto = rqsa.idtrabajador_por_proyecto AND rqsa.idresumen_q_s_asistencia = pqso.idresumen_q_s_asistencia AND pqso.estado = '1' AND tpp.idproyecto = '$id';";
		$monto_1 = ejecutarConsultaSimpleFila($sql_1);
		
		$sql_2 = "SELECT SUM(tpp.sueldo_mensual) AS sueldo_mesual_x_proyecto
		FROM trabajador_por_proyecto AS tpp,  cargo_trabajador AS ct, tipo_trabajador AS tt
        WHERE tpp.idproyecto = '$id' AND  tpp.idcargo_trabajador = ct.idcargo_trabajador AND ct.idtipo_trabjador = tt.idtipo_trabajador AND tt.nombre = 'Obrero';";
		$monto_2 = ejecutarConsultaSimpleFila($sql_2);

		$data = array(
			'total_deposito_x_proyecto' => $n1 = (empty($monto_1)) ? 0 : $retVal_1 = (empty($monto_1['total_deposito_x_proyecto'])) ? 0 : $monto_1['total_deposito_x_proyecto'] ,
			'sueldo_mesual_x_proyecto' => $n2 = (empty($monto_2)) ? 0 : $retVal_2 = (empty($monto_2['sueldo_mesual_x_proyecto'])) ? 0 : $monto_2['sueldo_mesual_x_proyecto'] 
		);

		return $data ;
	}

	//TABLA de quincenas enviadas al CONTADOR
	public function listar_tbla_q_s($idtrabajador_x_proyecto)
	{
		$data = Array();

		$sql_1="SELECT tpp.sueldo_hora, rqsa.idresumen_q_s_asistencia, rqsa.idtrabajador_por_proyecto, rqsa.numero_q_s, rqsa.fecha_q_s_inicio, rqsa.fecha_q_s_fin, 
		rqsa.total_hn, rqsa.total_he, rqsa.total_dias_asistidos, rqsa.sabatical, rqsa.sabatical_manual_1, rqsa.sabatical_manual_2, 
		rqsa.pago_parcial_hn, rqsa.pago_parcial_he, rqsa.adicional_descuento, rqsa.descripcion_descuento, rqsa.pago_quincenal, 
		rqsa.estado_envio_contador, rqsa.recibos_x_honorarios
		FROM resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp
		WHERE rqsa.idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' AND rqsa.estado_envio_contador = '1' AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto;";
		$q_s = ejecutarConsultaArray($sql_1);

		if ( !empty($q_s) ) {

			foreach ($q_s as $key => $q_s) {
				
				$id = $q_s['idresumen_q_s_asistencia'];

				$sql_2 = "SELECT SUM(monto_deposito) AS deposito  FROM pagos_q_s_obrero WHERE estado = '1' AND idresumen_q_s_asistencia = '$id';";
				$depositos = ejecutarConsultaSimpleFila($sql_2);

				$data[] = array(
					'sueldo_hora' => $retVal_1 = (empty($q_s['sueldo_hora'])) ? 0 : $q_s['sueldo_hora'],
					'idresumen_q_s_asistencia' => $q_s['idresumen_q_s_asistencia'],
					'idtrabajador_por_proyecto' => $q_s['idtrabajador_por_proyecto'],
					'numero_q_s' => $retVal_2 = (empty($q_s['numero_q_s'])) ? 0 : $q_s['numero_q_s'],
					'fecha_q_s_inicio' => $q_s['fecha_q_s_inicio'],
					'fecha_q_s_fin' => $q_s['fecha_q_s_fin'],
					'total_hn' => $retVal_3 = (empty($q_s['total_hn'])) ? 0 : $q_s['total_hn'],
					'total_he' => $retVal_4 = (empty($q_s['total_he'])) ? 0 : $q_s['total_he'],
					'total_dias_asistidos' => $retVal_5 = (empty($q_s['total_dias_asistidos'])) ? 0 : $q_s['total_dias_asistidos'],
					'sabatical' => $retVal_6 = (empty($q_s['sabatical'])) ? 0 : $q_s['sabatical'],
					'sabatical_manual_1' => $q_s['sabatical_manual_1'],
					'sabatical_manual_2' => $q_s['sabatical_manual_2'],
					'pago_parcial_hn' => $retVal_7 = (empty($q_s['pago_parcial_hn'])) ? 0 : $q_s['pago_parcial_hn'] ,
					'pago_parcial_he' => $retVal_8 = (empty($q_s['pago_parcial_he'])) ? 0 : $q_s['pago_parcial_he'],
					'adicional_descuento' => $retVal_9 = (empty($q_s['adicional_descuento'])) ? 0 : $q_s['adicional_descuento'],
					'descripcion_descuento' => $q_s['descripcion_descuento'],
					'pago_quincenal' => $retVal_10 = (empty($q_s['pago_quincenal'])) ? 0 : $q_s['pago_quincenal'],
					'estado_envio_contador' => $q_s['estado_envio_contador'],
					'recibos_x_honorarios' => $q_s['recibos_x_honorarios'],

					'deposito' => $retVal_11 = (empty($depositos)) ? 0 : $retVal_12 = (empty($depositos['deposito'])) ? 0 : $depositos['deposito'] 
				);				
			}
		}
		
		return $data;
	}

	//TABLA DE PAGOS
	public function listar_tbla_pagos_x_q_s($idresumen_q_s_asistencia)
	{
		$sql="SELECT idpagos_q_s_obrero, idresumen_q_s_asistencia, cuenta_deposito, forma_de_pago, monto_deposito, baucher, descripcion, estado 
		FROM pagos_q_s_obrero
		WHERE idresumen_q_s_asistencia = '$idresumen_q_s_asistencia';";

		return ejecutarConsulta($sql);
	}

	//MOSTRAR para editar
	public function mostrar_pagos_x_mes($idpagos_q_s_obrero)
	{
		$sql="SELECT idpagos_q_s_obrero, idresumen_q_s_asistencia, cuenta_deposito, forma_de_pago, monto_deposito, baucher, descripcion
		FROM pagos_q_s_obrero WHERE idpagos_q_s_obrero = '$idpagos_q_s_obrero';";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Desactivar DEPOSITO
	public function desactivar_pago_q_s($idtrabajador)
	{
		$sql="UPDATE pagos_q_s_obrero SET estado='0' WHERE idpagos_q_s_obrero='$idtrabajador'";
		return ejecutarConsulta($sql);
	}

	//Activar DEPOSITO
	public function activar_pago_q_s($idtrabajador)
	{
		$sql="UPDATE pagos_q_s_obrero SET estado='1' WHERE idpagos_q_s_obrero='$idtrabajador'";
		return ejecutarConsulta($sql);
	}

	// obtebnemos los "BAUCHER DE DEPOSITOS" para eliminar
	public function obtenerDocs($id) {

        $sql = "SELECT baucher FROM pagos_q_s_obrero WHERE idpagos_q_s_obrero = '$id'";

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