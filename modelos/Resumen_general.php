<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Resumen_general
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	public function r_compras($idproyecto)
	{
		$pago_total=0; 
		$Arraycompras= Array(); 

		$sql="SELECT cpp.idcompra_proyecto, cpp.idproyecto, cpp.idproveedor, cpp.fecha_compra, cpp.monto_total, p.razon_social, cpp.descripcion 
		FROM compra_por_proyecto as cpp, proveedor as p
		WHERE cpp.idproyecto='$idproyecto' AND cpp.idproveedor=p.idproveedor AND cpp.estado='1' ORDER by cpp.idcompra_proyecto ASC";

		$compras=ejecutarConsultaArray($sql);

		if (!empty($compras)) {
			
			foreach ($compras as $key => $value) {

				$idcompra=$value['idcompra_proyecto'];

				$sql_2="SELECT SUM(pc.monto) as total_p FROM pago_compras as pc WHERE pc.idcompra_proyecto='$idcompra' AND pc.estado='1' GROUP BY idcompra_proyecto";
				$t_monto= ejecutarConsultaSimpleFila($sql_2);

				if (empty($t_monto)) {
					$pago_total=0;
				}else{
					$pago_total=$t_monto['total_p'];
				}

				$Arraycompras[]= array(
					"idcompra_proyecto"     => $value['idcompra_proyecto'],
					"idproyecto"     	 => $value['idproyecto'],
					"idproveedor"    => $value['idproveedor'],
					"fecha_compra"    => $value['fecha_compra'],
					"monto_total"    => $value['monto_total'],
					"proveedor"    => $value['razon_social'],
					"descripcion"    => $value['descripcion'],

					"monto_pago_total"       =>$pago_total

				);

			}

		}

		return $Arraycompras;

		
	}
	//lismatamos los detalles compras
	public function detalles_compras($id_compra)
	{
		$sql = "SELECT 
		dp.idproducto as idproducto,
		dp.ficha_tecnica_producto as ficha_tecnica,
		dp.cantidad as cantidad,
		dp.precio_venta as precio_venta,
		dp.descuento as descuento,
		p.nombre as nombre
		FROM detalle_compra  dp, producto as p
		WHERE idcompra_proyecto='$id_compra' AND  dp.idproducto=p.idproducto";

		return ejecutarConsulta($sql);
	}
	//mostrar detalles uno a uno de la factura
	public function ver_compras($idcompra_proyecto)
	{
		$sql = "SELECT  
		cpp.idcompra_proyecto as idcompra_proyecto, 
		cpp.idproyecto as idproyecto, 
		cpp.idproveedor as idproveedor, 
		p.razon_social as razon_social, 
		cpp.fecha_compra as fecha_compra, 
		cpp.tipo_comprovante as tipo_comprovante, 
		cpp.serie_comprovante as serie_comprovante, 
		cpp.descripcion as descripcion, 
		cpp.subtotal_compras_proyect as subtotal_compras, 
		cpp.igv_compras_proyect as igv_compras_proyect, 
		cpp.monto_total as monto_total,
		cpp.fecha as fecha, 
		cpp.estado as estado
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE idcompra_proyecto='$idcompra_proyecto'  AND cpp.idproveedor = p.idproveedor";

		return ejecutarConsultaSimpleFila($sql);
	}

	public function r_serv_maquinaria_equipos($idproyecto,$tipo)
	{
		$serv_maquinaria= Array();
		$pago_total=0;
		$sql="SELECT s.idmaquinaria as idmaquinaria, s.idproyecto as idproyecto, m.nombre as maquina, p.razon_social as razon_social, COUNT(s.idmaquinaria) as cantidad_veces, SUM(s.costo_parcial) as costo_parcial 
		FROM servicio as s, maquinaria as m, proveedor as p 
		WHERE s.estado = 1 AND s.idproyecto='$idproyecto' AND m.tipo = '$tipo' AND s.idmaquinaria=m.idmaquinaria AND m.idproveedor=p.idproveedor 
		GROUP BY s.idmaquinaria";

		$maquinaria=ejecutarConsultaArray($sql);

		if (!empty($maquinaria)) {
			
			foreach ($maquinaria as $key => $value) {

				$idmaquinaria=$value['idmaquinaria'];

				$sql_2="SELECT SUM(ps.monto) as monto_pag_ser_maq FROM pago_servicio ps WHERE ps.idproyecto='$idproyecto' AND ps.id_maquinaria='$idmaquinaria' AND ps.estado=1 GROUP by id_maquinaria";
				$ser_maq_monto= ejecutarConsultaSimpleFila($sql_2);

				if (empty($t_monto)) {
					$pago_total=0;
				}else{
					$pago_total=$ser_maq_monto['monto_pag_ser_maq'];
				}

				$serv_maquinaria[]= array(

					"idmaquinaria"      => $value['idmaquinaria'],
					"idproyecto"      => $value['idproyecto'],
					"maquina"     	 	=> $value['maquina'],
					"cantidad_veces"    => $value['cantidad_veces'],
					"costo_parcial"     => $value['costo_parcial'],
					"proveedor"         => $value['razon_social'],

					"monto_pag_ser_maq"       =>$pago_total

				);

			}

		}

		return $serv_maquinaria;

	}

	//ver detallete por maquina-equipo
	public function ver_detalle_maq_equ($idmaquinaria,$idproyecto)
	{

		$sql="SELECT * FROM servicio as s WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' ORDER BY idservicio DESC";

		return ejecutarConsulta($sql);	

	}

	public function r_transportes($idproyecto)
	{
		$sql="SELECT t.idtransporte,t.idproyecto,t.fecha_viaje,t.descripcion,t.precio_parcial, t.comprobante 
		FROM transporte as t, proyecto as p WHERE t.idproyecto='$idproyecto' AND t.idproyecto=p.idproyecto AND t.estado='1' ORDER BY idtransporte DESC";
		return ejecutarConsultaArray($sql);
	}

	public function r_hospedajes($idproyecto)
	{
		$sql="SELECT h.idhospedaje,h.idproyecto,h.fecha_comprobante,h.descripcion,h.precio_parcial, h.comprobante 
		FROM hospedaje as h, proyecto as p WHERE h.idproyecto=p.idproyecto AND h.idproyecto='$idproyecto' AND h.estado=1 ORDER BY h.idhospedaje DESC";
		return ejecutarConsultaArray($sql);
	}

	public function r_comidas_extras($idproyecto)
	{
		$sql="SELECT ce.idcomida_extra, ce.idproyecto, ce.fecha_comida, ce.descripcion, ce.costo_parcial, ce.comprobante 
		FROM comida_extra as ce, proyecto as p WHERE ce.estado=1 AND ce.idproyecto=p.idproyecto AND ce.idproyecto='$idproyecto'";
		return ejecutarConsultaArray($sql);
	}

	public function r_breaks($idproyecto)
	{
		$sql="SELECT sb.idsemana_break, sb.idproyecto, sb.numero_semana, sb.fecha_inicial, sb.fecha_final, sb.total
			FROM semana_break as sb, proyecto as p
			WHERE sb.idproyecto ='$idproyecto' AND sb.estado=1 AND sb.idproyecto=p.idproyecto";
		return ejecutarConsultaArray($sql);
	}

	public function listar_comprobantes_breaks($idsemana_break)
	{

		$sql="SELECT * FROM factura_break 
		WHERE idsemana_break  ='$idsemana_break'";
		return ejecutarConsulta($sql);
	}
    //-------------------------------------------------------------------
	public function r_pensiones($idproyecto)
	{
		$serv_pension= Array();
		$pago_total=0; $monto_total=0;

		$sql="SELECT p.idpension, p.idproyecto, p.idproveedor, pr_v.razon_social, pr_v.direccion, p.estado
		FROM pension as p, proyecto as py, proveedor as pr_v
		WHERE p.estado=1 AND p.idproyecto='$idproyecto' AND p.idproyecto=py.idproyecto AND p.idproveedor=pr_v.idproveedor";
		$pension=ejecutarConsultaArray($sql);

		if (!empty($pension)) {
			
			foreach ($pension as $key => $value) {

				$idpension=$value['idpension'];

				$total_m=0;

				$sql_2="SELECT sp.idservicio_pension FROM servicio_pension As sp, pension AS p WHERE sp.idpension='$idpension' AND sp.idpension=p.idpension";
				$obt_servicio_pen=ejecutarConsulta($sql_2);

				$sql_3 = "SELECT SUM(monto) as total_pago FROM factura_pension WHERE estado=1 AND idpension='$idpension'";
				$return_pago = ejecutarConsultaSimpleFila($sql_3);

				if (empty($return_pago['total_pago'])) {
					$pago_total=0;
				}else{
					$pago_total=$return_pago['total_pago'];
				}

				foreach ($obt_servicio_pen as $key => $valor) {

					$idservicio_p= $valor['idservicio_pension'];

					$sql_4="SELECT SUM(total) as total FROM semana_pension as sp, servicio_pension as serv_p WHERE sp.idservicio_pension='$idservicio_p' AND sp.idservicio_pension=serv_p.idservicio_pension";
					$return_pension = ejecutarConsultaSimpleFila($sql_4);

					$total_m=$total_m+$return_pension['total'];
				}

				if (empty($total_m)) {
					$monto_total=0;
				}else{
					$monto_total=$total_m;
				}

				$serv_pension[]= array(

					"idpension"         => $value['idpension'],
					"idproyecto"        => $value['idproyecto'],
					"idproveedor"     	=> $value['idproveedor'],
					"proveedor"    		=> $value['razon_social'],
					"direccion"     	=> $value['direccion'],

					"monto_total_pension"       =>$monto_total,
					"pago_total_pension"       =>$pago_total

				);

			}
			
		}
		return $serv_pension;
	}

	public function ver_detalle_x_servicio($idpension)
	{
		$sql="SELECT SUM(se_p.total) as total,sp.nombre_servicio,SUM(se_p.adicional_descuento) as adicional_descuento,SUM(se_p.cantidad_total_platos) as cantidad_total_platos, sp.precio
		FROM servicio_pension as sp, pension as p, semana_pension as se_p 
		WHERE p.idpension='$idpension' AND sp.idpension=p.idpension AND se_p.idservicio_pension=sp.idservicio_pension GROUP BY se_p.idservicio_pension";
		return ejecutarConsulta($sql);
		
	
	}

	public function listar_comprobantes_pension($idpension)
	{

		$sql="SELECT * FROM factura_pension 
		WHERE idpension  ='$idpension'";
		return ejecutarConsulta($sql);
	}

	public function r_trab_administrativo($idproyecto)
	{
		$administrativo= Array(); $m_total_x_meses=0; $pago_monto_total=0;

		$sql = "SELECT tpp.idtrabajador_por_proyecto, tpp.idproyecto, t.nombres, ct.nombre as cargo 
		FROM trabajador_por_proyecto as tpp, trabajador as t, cargo_trabajador as ct, tipo_trabajador as tt 
		WHERE tpp.idproyecto='$idproyecto' AND tt.nombre !='Obrero' AND tpp.idtrabajador=t.idtrabajador AND tpp.idcargo_trabajador=ct.idcargo_trabajador AND ct.idcargo_trabajador=tpp.idcargo_trabajador AND ct.idtipo_trabjador =tt.idtipo_trabajador";
		
		$traba_adm=ejecutarConsultaArray($sql);

		if (!empty($traba_adm)) {

			foreach ($traba_adm as $key => $value) {

				$pago_monto_total=0;

				$idtrabajador_por_proyecto=$value['idtrabajador_por_proyecto'];

				$sql_2 = "SELECT idfechas_mes_pagos_administrador, monto_x_mes FROM fechas_mes_pagos_administrador WHERE idtrabajador_por_proyecto='$idtrabajador_por_proyecto'";
				$fechas_mes_pagos_administrador=ejecutarConsultaArray($sql_2);

				$sql_3 = "SELECT SUM(monto_x_mes) as total_montos_x_meses FROM fechas_mes_pagos_administrador WHERE idtrabajador_por_proyecto='$idtrabajador_por_proyecto'";
				$total_montos_x_meses=ejecutarConsultaSimpleFila($sql_3);


				foreach ($fechas_mes_pagos_administrador as $key => $valor) {

					$idfechas_mes_pagos_administrador= $valor['idfechas_mes_pagos_administrador'];

					$sql_4="SELECT SUM(monto) as total_monto_pago FROM pagos_x_mes_administrador WHERE idfechas_mes_pagos_administrador='$idfechas_mes_pagos_administrador' AND estado=1";
					
					$return_monto_pago = ejecutarConsultaSimpleFila($sql_4);

					$pago_monto_total=$pago_monto_total+$return_monto_pago['total_monto_pago'];
				}

				if (empty($total_montos_x_meses['total_montos_x_meses']) || $total_montos_x_meses['total_montos_x_meses']==null ) {

					$m_total_x_meses=0;
				}else{
					$m_total_x_meses=$total_montos_x_meses['total_montos_x_meses'];
				}

				$administrativo[]= array(

					"idtrabajador_por_proyecto"  => $value['idtrabajador_por_proyecto'],
					"idproyecto"        		 => $value['idproyecto'],
					"nombres"     	             => $value['nombres'],
					"cargo"    		             => $value['cargo'],

					"total_montos_x_meses"       =>$m_total_x_meses,

					"pago_total_adm"             =>$pago_monto_total

				);

			}
			
		}
		return $administrativo;
	
	}

	public function r_detalle_trab_administrativo($idtrabajador_por_proyecto)
	{
		$detalle_pagos_adm= Array(); $monto_total=0;

		$sql="SELECT idfechas_mes_pagos_administrador,idtrabajador_por_proyecto,fecha_inicial,fecha_final,nombre_mes,cant_dias_laborables,monto_x_mes 
				FROM fechas_mes_pagos_administrador	WHERE idtrabajador_por_proyecto='$idtrabajador_por_proyecto'";

		$fechas_mes_pagos_adm=ejecutarConsultaArray($sql);

		if(!empty($fechas_mes_pagos_adm)){

			foreach ($fechas_mes_pagos_adm as $key => $value) {
				$idfechas_mes_pagos_adm=$value['idfechas_mes_pagos_administrador'];
	
				$sql_2="SELECT SUM(monto) as monto_total_pago FROM pagos_x_mes_administrador WHERE idfechas_mes_pagos_administrador='$idfechas_mes_pagos_adm' AND estado=1";
						
				$return_monto_pago = ejecutarConsultaSimpleFila($sql_2);

				if (empty($return_monto_pago)) {
					$monto_total=0;
				}else{
					$monto_total=$return_monto_pago['monto_total_pago'];
				}
	
				$detalle_pagos_adm[]= array(
	
					"fecha_inicial"  => $value['fecha_inicial'],
					"fecha_final"  => $value['fecha_final'],
					"nombre_mes"  => $value['nombre_mes'],
					"cant_dias_laborables"  => $value['cant_dias_laborables'],
					"monto_x_mes"  => $value['monto_x_mes'],

					'return_monto_pago' =>$monto_total
				);
				
			}

		}


		return $detalle_pagos_adm;
	}

	public function select_proveedores()
	{
		$sql="SELECT idproveedor, razon_social, ruc FROM proveedor";
		return ejecutarConsulta($sql);
	}

	public function selecct_trabajadores($idproyecto)
	{
		$sql="SELECT tpp.idtrabajador_por_proyecto, t.nombres, t.numero_documento FROM trabajador_por_proyecto as tpp, trabajador as t
			WHERE tpp.idtrabajador= t.idtrabajador AND tpp.idproyecto='$idproyecto'";
		return ejecutarConsulta($sql);
	}

	public function r_trabajador_obrero($idproyecto)
	{
		$obrero= Array(); $total_deposito_obrero=0;

		$sql = "SELECT ra.idresumen_q_s_asistencia,ra.idtrabajador_por_proyecto, t.nombres, SUM(ra.pago_quincenal) as pago_quincenal 
		FROM resumen_q_s_asistencia as ra, trabajador_por_proyecto as tpp, trabajador as t 
		WHERE ra.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idproyecto ='$idproyecto' AND tpp.idtrabajador=t.idtrabajador 
		GROUP by tpp.idtrabajador_por_proyecto";
		
		$trabaj_obrero=ejecutarConsultaArray($sql);

		if (!empty($trabaj_obrero)) {

			foreach ($trabaj_obrero as $key => $value) {

				$idresumen_q_s_asistencia = $value['idresumen_q_s_asistencia'];

				$sql_2="SELECT SUM(monto_deposito) as deposito FROM pagos_q_s_obrero WHERE idresumen_q_s_asistencia='$idresumen_q_s_asistencia'";

				$total_deposito=ejecutarConsultaSimpleFila($sql_2);

				
				if (empty($total_deposito['deposito']) || $total_deposito['deposito']==null ) {

					$total_deposito_obrero=0;
				}else{
					$total_deposito_obrero=$total_deposito['deposito'];
				}

				$obrero[]= array(

					"idresumen_q_s_asistencia"   => $value['idresumen_q_s_asistencia'],
					"idtrabajador_por_proyecto"  => $value['idtrabajador_por_proyecto'],
					"nombres"     	             => $value['nombres'],
					"pago_quincenal"    		 => $value['pago_quincenal'],

					"total_deposito_obrero"       =>$total_deposito_obrero

				);
				
			}
			return $obrero;
				
		}
	}



}

?>

