<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class PagoAdministrador
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar_pagos_x_mes( $idfechas_mes_pagos_administrador_pxm, $id_tabajador_x_proyecto_pxm, $fecha_inicial_pxm, $fecha_final_pxm, $mes_nombre_pxm, $dias_mes_pxm, $dias_regular_pxm, $sueldo_mensual_pxm, $monto_x_mes_pxm, 
	$forma_pago, $cuenta_deposito, $monto, $descripcion, $doc1 ){	
		$id_fecha_mes = "";

		if ( empty($idfechas_mes_pagos_administrador_pxm) ) {
			$sql_1 = "INSERT INTO fechas_mes_pagos_administrador (idfechas_mes_pagos_administrador, idtrabajador_por_proyecto, fecha_inicial, fecha_final, nombre_mes, cant_dias_mes, cant_dias_laborables, sueldo_mensual, monto_x_mes, recibos_x_honorarios)
			VALUES ('$idfechas_mes_pagos_administrador_pxm', '$id_tabajador_x_proyecto_pxm', '$fecha_inicial_pxm', '$fecha_final_pxm', '$mes_nombre_pxm', '$dias_mes_pxm', '$dias_regular_pxm', '$sueldo_mensual_pxm', '$monto_x_mes_pxm', '' )";
	
			$id_fecha_mes = ejecutarConsulta_retornarID($sql_1);
		} else {
			$id_fecha_mes = $idfechas_mes_pagos_administrador_pxm ;
		}		

		$sql_2="INSERT INTO pagos_x_mes_administrador ( idfechas_mes_pagos_administrador, cuenta_deposito, forma_de_pago, monto, baucher, descripcion)
		VALUES ('$id_fecha_mes', '$cuenta_deposito', '$forma_pago', '$monto', '$doc1', '$descripcion')";
		
		$pagos_x_mes = ejecutarConsulta($sql_2);

		$validar = array("estado" => $pagos_x_mes ,"id_tabla" => $id_fecha_mes );
		
		return json_encode($validar, true);			
	}

	//Implementamos un método para editar registros
	public function editar_pagos_x_mes($idpagos_x_mes_administrador, $idfechas_mes_pagos_administrador_pxm, $id_tabajador_x_proyecto_pxm, $fecha_inicial_pxm, $fecha_final_pxm, $mes_nombre_pxm, $dias_mes_pxm, 
	$dias_regular_pxm, $sueldo_mensual_pxm, $monto_x_mes_pxm, $forma_pago, $cuenta_deposito, $monto, $descripcion, $doc1) {
		$id_fecha_mes = "";

		if ( empty($idfechas_mes_pagos_administrador_pxm) ) {
			$sql_1 = "INSERT INTO fechas_mes_pagos_administrador (idfechas_mes_pagos_administrador, idtrabajador_por_proyecto, fecha_inicial, fecha_final, nombre_mes, cant_dias_mes, cant_dias_laborables, sueldo_mensual, monto_x_mes, recibos_x_honorarios)
			VALUES ('$idfechas_mes_pagos_administrador_pxm', '$id_tabajador_x_proyecto_pxm', '$fecha_inicial_pxm', '$fecha_final_pxm', '$mes_nombre_pxm', '$dias_mes_pxm', '$dias_regular_pxm', '$sueldo_mensual_pxm', '$monto_x_mes_pxm', '' )";
	
			$id_fecha_mes = ejecutarConsulta_retornarID($sql_1);
		} else {
			$id_fecha_mes = $idfechas_mes_pagos_administrador_pxm ;
		}	

		$sql_2="UPDATE pagos_x_mes_administrador SET  idfechas_mes_pagos_administrador='$id_fecha_mes', cuenta_deposito='$cuenta_deposito', 
		forma_de_pago='$forma_pago', monto='$monto', baucher='$doc1', descripcion='$descripcion'
		WHERE idpagos_x_mes_administrador='$idpagos_x_mes_administrador'";	
		$pagos_x_mes = ejecutarConsulta($sql_2);

		$validar = array("estado" => $pagos_x_mes ,"id_tabla" => $id_fecha_mes );
		
		return json_encode($validar, true);			
	}

	//Implementamos un método para insertar registros
	public function insertar_recibo_x_honorario($id_tabajador_x_proyecto_rh, $fecha_inicial_rh, $fecha_final_rh, $mes_nombre_rh, $dias_mes_rh, $dias_regular_rh, $sueldo_mensual_rh, $monto_x_mes_rh, $doc2)
	{
		$sql="INSERT INTO fechas_mes_pagos_administrador (idtrabajador_por_proyecto, fecha_inicial, fecha_final, nombre_mes, cant_dias_mes, cant_dias_laborables, sueldo_mensual, monto_x_mes, recibos_x_honorarios)
		VALUES ('$id_tabajador_x_proyecto_rh', '$fecha_inicial_rh', '$fecha_final_rh', '$mes_nombre_rh', '$dias_mes_rh', '$dias_regular_rh', '$sueldo_mensual_rh', '$monto_x_mes_rh', '$doc2')";
		
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar_recibo_x_honorario($idfechas_mes_pagos_administrador_rh, $id_tabajador_x_proyecto_rh, $fecha_inicial_rh, $fecha_final_rh, $mes_nombre_rh, $dias_mes_rh, $dias_regular_rh, $sueldo_mensual_rh, $monto_x_mes_rh, $doc2)
	{
		$sql="UPDATE fechas_mes_pagos_administrador SET idtrabajador_por_proyecto='$id_tabajador_x_proyecto_rh', fecha_inicial='$fecha_inicial_rh', fecha_final='$fecha_final_rh', nombre_mes='$mes_nombre_rh', cant_dias_mes='$dias_mes_rh',
		 cant_dias_laborables='$dias_regular_rh', sueldo_mensual='$sueldo_mensual_rh',	monto_x_mes='$monto_x_mes_rh', recibos_x_honorarios='$doc2' 
		WHERE idfechas_mes_pagos_administrador='$idfechas_mes_pagos_administrador_rh'";	
		
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros
	public function listar_tbla_principal($nube_idproyecto)
	{
		$data = Array(); $data_fechas = Array();

		$sql_1="SELECT t.idtrabajador, t.nombres, t.tipo_documento, t.numero_documento, t.cuenta_bancaria_format AS cuenta_bancaria, t.cci, t.imagen_perfil, t.telefono,
		tpp.desempenio, tpp.sueldo_mensual, tpp.sueldo_diario, tpp.sueldo_hora, tpp.idtrabajador_por_proyecto, tpp.estado, 
		tpp.fecha_inicio, tpp.fecha_fin, tpp.cantidad_dias, tpp.cantidad_dias, b.nombre as banco, ct.nombre AS cargo, tt.nombre AS tipo
		FROM trabajador_por_proyecto as tpp, cargo_trabajador AS ct, tipo_trabajador AS tt, trabajador as t, proyecto AS p, bancos AS b
		WHERE tpp.idproyecto = p.idproyecto AND tpp.idproyecto = '$nube_idproyecto'   AND tpp.idtrabajador = t.idtrabajador AND 
		t.idbancos = b.idbancos AND tpp.idcargo_trabajador = ct.idcargo_trabajador AND ct.idtipo_trabjador = tt.idtipo_trabajador 
		AND tt.nombre != 'Obrero' ORDER BY t.nombres ASC ;";
		$trabajador = ejecutarConsultaArray($sql_1);

		if ( !empty($trabajador) ) {

			foreach ($trabajador as $key => $value) {

				$id_trabajdor = $value['idtrabajador_por_proyecto'];

				$sql_2 = "SELECT fmpg.idtrabajador_por_proyecto, SUM(pxma.monto) deposito_por_trabajdor
				FROM fechas_mes_pagos_administrador AS fmpg, pagos_x_mes_administrador AS pxma
				WHERE fmpg.idtrabajador_por_proyecto = '$id_trabajdor' AND fmpg.idfechas_mes_pagos_administrador = pxma.idfechas_mes_pagos_administrador AND pxma.estado = '1';";

				$depositos =  ejecutarConsultaSimpleFila($sql_2); $cant_depo = 0;
				if ( !empty($depositos) ) {
					$cant_depo = $depositos['deposito_por_trabajdor'];
				} 

				$sql_3="SELECT idfechas_mes_pagos_administrador, idtrabajador_por_proyecto, fecha_inicial, fecha_final, nombre_mes, cant_dias_mes, cant_dias_laborables, sueldo_mensual, monto_x_mes, recibos_x_honorarios, estado
				FROM fechas_mes_pagos_administrador WHERE idtrabajador_por_proyecto = '$id_trabajdor' ;";
				$fechas_mes = ejecutarConsultaArray($sql_3);

				if (!empty($fechas_mes)) {

					foreach ($fechas_mes as $key => $element) {

						$id = $element['idfechas_mes_pagos_administrador'];

						$sql_4 = "SELECT SUM(monto) AS suma_monto_depositado FROM pagos_x_mes_administrador WHERE idfechas_mes_pagos_administrador ='$id' AND estado = '1';";
						$pagos_x_mes = ejecutarConsultaSimpleFila($sql_4);

						$data_fechas[] = array(
							"idfechas_mes_pagos_administrador"   => $element['idfechas_mes_pagos_administrador'],
							"idtrabajador_por_proyecto"   => $element['idtrabajador_por_proyecto'],
							"fecha_inicial" => $element['fecha_inicial'],
							"fecha_final"   => $element['fecha_final'],
							"nombre_mes"   	=> $element['nombre_mes'],
							"cant_dias_mes" => $element['cant_dias_mes'],
							"cant_dias_laborables"  => $element['cant_dias_laborables'],
							"sueldo_mensual"	=> $element['sueldo_mensual'],
							"monto_x_mes"   	=> $element['monto_x_mes'],
							"recibos_x_honorarios"  => $element['recibos_x_honorarios'],
							"estado"   				=> $element['estado'],
							"suma_monto_depositado"	=> $retVal = (!empty($pagos_x_mes['suma_monto_depositado'])) ? $pagos_x_mes['suma_monto_depositado'] : 0 ,
						);
					}
				}
				
				$data[] = array(
					'idtrabajador' 	=> $value['idtrabajador'], 
					'nombres' 		=> $value['nombres'], 
					'tipo_documento'=> $value['tipo_documento'], 
					'numero_documento'=> $value['numero_documento'], 
					'cuenta_bancaria' => $value['cuenta_bancaria'], 
					'cci' 			=> $value['cci'], 
					'imagen_perfil' => $value['imagen_perfil'], 
					'telefono' 		=> $value['telefono'],		
					'desempenio' 	=> $value['desempenio'], 
					'sueldo_mensual'=> $value['sueldo_mensual'], 
					'sueldo_diario' => $value['sueldo_diario'], 
					'sueldo_hora' 	=> $value['sueldo_hora'], 
					'estado' 		=> $value['estado'], 
					'idtrabajador_por_proyecto' => $value['idtrabajador_por_proyecto'],
					'fecha_inicio' 	=> $value['fecha_inicio'], 
					'fecha_fin' 	=> $value['fecha_fin'], 
					'cantidad_dias' => $value['cantidad_dias'], 
					'cantidad_dias' => $value['cantidad_dias'], 
					'banco' => $value['banco'], 
					'cargo' => $value['cargo'], 
					'tipo' 	=> $value['tipo'],

					'cantidad_deposito' => $cant_depo,

					'data_fechas' => $data_fechas
				);
			}
		}		
		
		return json_encode($data, true);		
	}

	// Obtenemos los totales
	public function mostrar_total_tbla_principal($id)
	{
		$sql_1 = "SELECT  SUM(pxma.monto) AS monto_total_depositado_x_proyecto
		FROM trabajador_por_proyecto AS tpp, fechas_mes_pagos_administrador AS fmpa, pagos_x_mes_administrador AS pxma
		WHERE tpp.idproyecto = '$id' AND fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND fmpa.idfechas_mes_pagos_administrador = pxma.idfechas_mes_pagos_administrador AND pxma.estado = '1';";
		$monto_1 = ejecutarConsultaSimpleFila($sql_1);
		
		$sql_1 = "SELECT SUM(tpp.sueldo_mensual) AS sueldo_mesual_x_proyecto
		FROM trabajador_por_proyecto AS tpp,  cargo_trabajador AS ct, tipo_trabajador AS tt
        WHERE tpp.idproyecto = '$id' AND  tpp.idcargo_trabajador = ct.idcargo_trabajador AND ct.idtipo_trabjador = tt.idtipo_trabajador AND tt.nombre != 'Obrero';";
		$monto_2 = ejecutarConsultaSimpleFila($sql_1);

		$data = array(
			'monto_total_depositado_x_proyecto' => $n1 = (empty($monto_1)) ? 0 : $monto_1['monto_total_depositado_x_proyecto'] ,
			'sueldo_mesual_x_proyecto' => $n2 = (empty($monto_2)) ? 0 : $monto_2['sueldo_mesual_x_proyecto']
		);

		return $data ;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar_fechas_mes($idtrabajador_x_proyecto)
	{
		$data_array = Array();

		$sql="SELECT idfechas_mes_pagos_administrador, idtrabajador_por_proyecto, fecha_inicial, fecha_final, nombre_mes, cant_dias_mes, cant_dias_laborables, sueldo_mensual, monto_x_mes, recibos_x_honorarios, estado
		FROM fechas_mes_pagos_administrador WHERE idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' ;";
		$fechas_mes = ejecutarConsultaArray($sql);

		if (!empty($fechas_mes)) {

			foreach ($fechas_mes as $key => $value) {

				$id = $value['idfechas_mes_pagos_administrador'];

				$sql_2 = "SELECT SUM(monto) AS suma_monto_depositado FROM pagos_x_mes_administrador WHERE idfechas_mes_pagos_administrador ='$id' AND estado = '1';";
				$pagos_x_mes = ejecutarConsultaSimpleFila($sql_2);

				$data_array[] = array(
					"idfechas_mes_pagos_administrador"   => $value['idfechas_mes_pagos_administrador'],
					"idtrabajador_por_proyecto"   => $value['idtrabajador_por_proyecto'],
					"fecha_inicial" => $value['fecha_inicial'],
					"fecha_final"   => $value['fecha_final'],
					"nombre_mes"   	=> $value['nombre_mes'],
					"cant_dias_mes" => $value['cant_dias_mes'],
					"cant_dias_laborables"  => $value['cant_dias_laborables'],
					"sueldo_mensual"	=> $value['sueldo_mensual'],
					"monto_x_mes"   	=> $value['monto_x_mes'],
					"recibos_x_honorarios"  => $value['recibos_x_honorarios'],
					"estado"   				=> $value['estado'],
					"suma_monto_depositado"	=> $retVal = (!empty($pagos_x_mes['suma_monto_depositado'])) ? $pagos_x_mes['suma_monto_depositado'] : 0 ,
				);
			}
		}		

		return $data_array;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function listar_pagos_x_mes($idfechas_mes_pagos)
	{
		$sql="SELECT idpagos_x_mes_administrador, idfechas_mes_pagos_administrador, cuenta_deposito, forma_de_pago, monto, baucher, descripcion, estado
		FROM pagos_x_mes_administrador WHERE idfechas_mes_pagos_administrador = '$idfechas_mes_pagos' ";

		return ejecutarConsulta($sql);
	}

	// Mostramos datos: pagos por mes, para editar
	public function mostrar_pagos_x_mes($id) {

        $sql = "SELECT idpagos_x_mes_administrador, idfechas_mes_pagos_administrador, cuenta_deposito, forma_de_pago, monto, baucher, descripcion 
		FROM pagos_x_mes_administrador WHERE idpagos_x_mes_administrador = '$id';";

        return ejecutarConsultaSimpleFila($sql);
    }

	//Implementamos un método para desactivar
	public function desactivar_pago_x_mes($id)
	{
		$sql="UPDATE pagos_x_mes_administrador SET estado='0' WHERE idpagos_x_mes_administrador='$id'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar 
	public function activar_pago_x_mes($id)
	{
		$sql="UPDATE pagos_x_mes_administrador SET estado='1' WHERE idpagos_x_mes_administrador='$id'";
		return ejecutarConsulta($sql);
	}

	// obtebnemos los "BAUCHER DE DEPOSITOS" para eliminar
	public function obtenerDocs($id) {

        $sql = "SELECT baucher FROM pagos_x_mes_administrador WHERE idpagos_x_mes_administrador = '$id'";

        return ejecutarConsulta($sql);
    }

	// obtebnemos los "RECIBO X HONORARIO" para eliminar
	public function obtenerDocs2($id) {

        $sql = "SELECT recibos_x_honorarios FROM fechas_mes_pagos_administrador WHERE idfechas_mes_pagos_administrador='$id'";

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