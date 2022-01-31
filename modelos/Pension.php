<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Pension
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	
	//Implementamos un método para insertar registros
	public function insertar_editar($array_break,$fechas_semanas_btn,$idproyecto){
		$total  = 0;
		$desglese_break = json_decode($array_break,true); $sw = true;
		$fechas_semanas_btn = json_decode($fechas_semanas_btn, true);
		// registramos o editamos los "Break por semana"
		foreach ($desglese_break as $indice => $key) {
		
			if ( empty($key['idbreak'])) {

				// insertamos un nuevo registro
				$sql_2="INSERT INTO breaks (idproyecto, fecha_compra, dia_semana, cantidad, costo_parcial, descripcion)
				VALUES ('$idproyecto', '".$key['fecha_compra']."', '".$key['dia_semana']."', '".$key['cantidad_compra']."', '".$key['precio_compra']."', '".$key['descripcion_compra']."')";

				ejecutarConsulta($sql_2) or $sw = false;

			} else {
				# editamos el registro existente
				$sql_3="UPDATE breaks SET idproyecto='$idproyecto', 
				fecha_compra='".$key['fecha_compra']."', 
				dia_semana='".$key['dia_semana']."', 
				cantidad='".$key['cantidad_compra']."', 
				costo_parcial='".$key['precio_compra']."',
				descripcion='".$key['descripcion_compra']."'	
				WHERE idbreak='".$key['idbreak']."';";
				
				ejecutarConsulta($sql_3) or $sw = false;
			}
			$total = $total+ floatval($key['precio_compra']); 


		}
		foreach ($fechas_semanas_btn as $key => $value) {

			$sql_4 = "SELECT idsemana_break FROM semana_break WHERE idproyecto='$idproyecto' AND fecha_inicial = '".$value['fecha_in_btn']."' AND  fecha_final = '".$value['fecha_fi_btn']."' ";
			
			$buscar_idbreak = ejecutarConsultaSimpleFila($sql_4);

			if(empty($buscar_idbreak['idsemana_break'])){
				$sql5 = "INSERT INTO semana_break(idproyecto, numero_semana, fecha_inicial, fecha_final, total) 
				VALUES ('$idproyecto','".$value['num_semana']."','".$value['fecha_in_btn']."','".$value['fecha_fi_btn']."','$total')";
				ejecutarConsulta($sql5) or $sw = false;
			}else{
				$sql6 = " UPDATE semana_break SET 
					idproyecto='$idproyecto',
					numero_semana='".$value['num_semana']."',
					fecha_inicial='".$value['fecha_in_btn']."',
					fecha_final='".$value['fecha_fi_btn']."',
					total='$total'
					WHERE  idsemana_break='".$buscar_idbreak['idsemana_break']."';";
				ejecutarConsulta($sql6) or $sw = false;
			}
		}
		return $sw;	
	}

	///////////////////////CONSULTAS pension///////////////////////
	//listar_semana_botones
	public function listarsemana_botones($nube_idproyecto){
		$sql="SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin FROM proyecto as p WHERE p.idproyecto='$nube_idproyecto'";
		return ejecutarConsultaSimpleFila($sql);
	}
	//ver detalle semana a semana
	public function ver_detalle_semana_dias($f1,$f2,$nube_idproyect,$id_pen){

		$idpension='';

		$idsemana_pension=''; $precio_comida=''; $cantidad_total_platos=''; $adicional_descuento=''; $total=''; $descripcion='';

		$datos_semana= Array(); 

		$sql_1="SELECT sp.idservicio_pension, sp.nombre_servicio, sp.precio FROM servicio_pension As sp, pension AS p WHERE sp.idpension='$id_pen' AND sp.idpension=p.idpension";
		$servicio_pension =ejecutarConsultaArray($sql_1);

		if (!empty($servicio_pension)) {

			foreach ($servicio_pension as $key => $value) {


				$idpension = $value['idservicio_pension'];

				$sql_2="SELECT dp.fecha_pension, dp.cantidad_platos
				FROM detalle_pension as dp, servicio_pension as sp 
				WHERE dp.estado='1' AND dp.idservicio_pension='$idpension' AND  dp.idservicio_pension=sp.idservicio_pension AND dp.fecha_pension BETWEEN '$f1' AND '$f2'";
				$datos_rangos_fechas= ejecutarConsultaArray($sql_2);

				$sql_3 = "SELECT idsemana_pension,precio_comida,cantidad_total_platos,adicional_descuento,total,descripcion 
				FROM semana_pension as sp, servicio_pension as ser_p 
				WHERE sp.estado='1' AND sp.idservicio_pension='$idpension' AND sp.fecha_inicio ='$f1' AND sp.idservicio_pension=ser_p.idservicio_pension";
				$rango_fecha_semana= ejecutarConsultaSimpleFila($sql_3);

				if (empty($rango_fecha_semana)) {

					$idsemana_pension=''; $precio_comida=''; $cantidad_total_platos=''; $adicional_descuento=''; $total=''; $descripcion='';	

				} else {

					$idsemana_pension      =$rango_fecha_semana['idsemana_pension']; 
					$precio_comida         =$rango_fecha_semana['precio_comida']; 
					$cantidad_total_platos =$rango_fecha_semana['cantidad_total_platos'];  
					$adicional_descuento   =$rango_fecha_semana['adicional_descuento']; 
					$total                 =$rango_fecha_semana['total']; 
					$descripcion           =$rango_fecha_semana['descripcion']; 
				}
				
				$datos_semana[]= array(
					"idservicio_pension"     => $value['idservicio_pension'],
					"nombre_servicio"     	 => $value['nombre_servicio'],
					"precio"         	     => $value['precio'],

					"idsemana_pension"      =>$idsemana_pension,
					"precio_comida"         => $precio_comida,
					"cantidad_total_platos" =>$cantidad_total_platos,
					"adicional_descuento"   =>$adicional_descuento,
					"total"                 =>$total, 
					"descripcion"           =>$descripcion,

					"dias_q_comieron"       =>$datos_rangos_fechas

				);	
			}

		}

		return $datos_semana;
		
	}	
	///////////////////////CONSULTAS BREAK///////////////////////

	public function listar($nube_idproyecto)
	{
		$sql="SELECT sp.numero_semana as numero_semana,sp.fecha_inicio as fecha_inicio, sp.fecha_fin as fecha_fin,SUM(sp.total) as total, p.idproyecto as idproyecto
		FROM semana_pension AS sp, pension AS p, proyecto as py
		WHERE  p.idproyecto = '$nube_idproyecto'  AND p.idpension=sp.idpension AND py.idproyecto=p.idproyecto
		GROUP BY numero_semana";
		return ejecutarConsulta($sql);
	}
	public function ver_detalle_semana($numero_semana,$nube_idproyecto)
	{
		$sql="SELECT sp.idpension, sp.numero_semana as numero_semana,sp.fecha_inicio as fecha_inicio, sp.fecha_fin as fecha_fin,sp.total as total, p.tipo_pension, sp.precio_comida as precio_comida,sp.cantidad_total_platos as cantidad_total_platos, sp.adicional_descuento as adicional_descuento
		FROM semana_pension AS sp, pension AS p, proyecto as py
		WHERE  p.idproyecto = '$nube_idproyecto'  AND p.idpension=sp.idpension AND py.idproyecto=p.idproyecto AND numero_semana='$numero_semana' ORDER BY sp.idpension ASC";
		return ejecutarConsulta($sql);
	}
	//----------------------comprobantes------------------------------
	public function insertar_comprobante($idsemana_break,$forma_pago,$tipo_comprovante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$imagen2){
		//var_dump($idsemana_break,$tipo_comprovante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$imagen2);die();
		$sql="INSERT INTO factura_break (idsemana_break,nro_comprobante, fecha_emision, monto, igv, subtotal,forma_de_pago, tipo_comprobante, descripcion, comprobante) 
		VALUES ('$idsemana_break','$nro_comprobante','$fecha_emision','$monto','$igv','$subtotal','$forma_pago','$tipo_comprovante','$descripcion','$imagen2')";
		return ejecutarConsulta($sql);
	}
	// obtebnemos los DOCS para eliminar
	public function obtenerDoc($idfactura_break) {

		$sql = "SELECT comprobante FROM factura_break WHERE idfactura_break  ='$idfactura_break'";
	
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para editar registros
	public function editar_comprobante($idfactura_break,$idsemana_break,$forma_pago,$tipo_comprovante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$imagen2){
		//$vaa="$idfactura,$idproyectof,$idmaquina,$codigo,$monto,$fecha_emision,$descripcion_f,$imagen2";
		$sql="UPDATE `factura_break` SET 
		
		idsemana_break='$idsemana_break',
		forma_de_pago='$forma_pago',
		nro_comprobante='$nro_comprobante',
		fecha_emision='$fecha_emision',
		monto='$monto',
		igv='$igv',
		subtotal='$subtotal',
		tipo_comprobante='$tipo_comprovante',
		descripcion='$descripcion',
		comprobante='$imagen2'
		 WHERE idfactura_break='$idfactura_break';";	
		return ejecutarConsulta($sql);	
		//return $vaa;
	}

	public function listar_comprobantes($idsemana_break){

		$sql="SELECT * FROM factura_break 
		WHERE idsemana_break  ='$idsemana_break'";
		return ejecutarConsulta($sql);
	}
	//mostrar_comprobante
	public function mostrar_comprobante($idfactura_break){
		$sql="SELECT * FROM factura_break WHERE idfactura_break ='$idfactura_break '";
		return ejecutarConsultaSimpleFila($sql);
	}
	//Implementamos un método para activar 
	public function desactivar_comprobante($idfactura_break){
		//var_dump($idfactura);die();
		$sql="UPDATE factura_break SET estado='0' WHERE idfactura_break ='$idfactura_break '";
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para desactivar 
	public function activar_comprobante($idfactura_break){
		//var_dump($idpago_servicio);die();
		$sql="UPDATE factura_break SET estado='1' WHERE idfactura_break ='$idfactura_break '";
		return ejecutarConsulta($sql);
	}
	
	public function total_monto_comp($idsemana_break){
		$sql="SELECT SUM(subtotal) as total FROM factura_break WHERE idsemana_break='$idsemana_break' AND estado='1'";
		return ejecutarConsultaSimpleFila($sql);

	}
	//---------------------------pension-----------------------------------
	public function insertar_pension($idproyecto_p,$proveedor,$p_desayuno,$p_almuerzo,$p_cena,$servicio_p)
	{
		$sql = "INSERT INTO pension(idproyecto, idproveedor) VALUES ('$idproyecto_p','$proveedor')";
		$idpensionnew = ejecutarConsulta_retornarID($sql);
		
        $num_elementos = 0;
        $sw = true;

        while ($num_elementos < count($servicio_p)) {

			if ($servicio_p[$num_elementos]=='Desayuno') {
				$sql_servicio = "INSERT INTO servicio_pension(idpension,precio,nombre_servicio) VALUES ('$idpensionnew','$p_desayuno','$servicio_p[$num_elementos]')";
				ejecutarConsulta($sql_servicio) or ($sw = false);
			}

			if($servicio_p[$num_elementos]=='Almuerzo'){
				$sql_servicio = "INSERT INTO servicio_pension(idpension,precio,nombre_servicio) VALUES ('$idpensionnew','$p_almuerzo','$servicio_p[$num_elementos]')";
				ejecutarConsulta($sql_servicio) or ($sw = false);
			}
			
			if($servicio_p[$num_elementos]=='Cena'){
				$sql_servicio = "INSERT INTO servicio_pension(idpension,precio,nombre_servicio) VALUES ('$idpensionnew','$p_cena','$servicio_p[$num_elementos]')";
				ejecutarConsulta($sql_servicio) or ($sw = false);
			}			

            $num_elementos = $num_elementos + 1;
        }

        return $sw;
	}
	public function editar_pension($idproyecto_p,$idpension,$proveedor,$p_desayuno,$p_almuerzo,$p_cena,$servicio_p)
	{
		//var_dump($idproyecto_p,$idpension,$proveedor,$p_desayuno,$p_almuerzo,$p_cena,$servicio_p); die();
		$sql = "UPDATE pension SET idproyecto='$idproyecto_p',idproveedor='$proveedor' WHERE idpension='$idpension'";
		 ejecutarConsulta($sql);
		
        $num_elementos = 0;
        $sw = true;

        while ($num_elementos < count($servicio_p)) {

			if ($servicio_p[$num_elementos]=='Desayuno') {

				$buscando_serv="SELECT idservicio_pension FROM servicio_pension WHERE idpension='$idpension' AND nombre_servicio='$servicio_p[$num_elementos]'";
				$idbuscando_serv=ejecutarConsultaSimpleFila($buscando_serv);

				if (empty($idbuscando_serv['idservicio_pension'])) {

					$sql_servicio = "INSERT INTO servicio_pension(idpension,precio,nombre_servicio) VALUES ('$idpension','$p_desayuno','Desayuno')";
					ejecutarConsulta($sql_servicio) or ($sw = false);
				}else{

					$sql_servicio = "UPDATE servicio_pension SET precio='$p_desayuno' WHERE idservicio_pension='".$idbuscando_serv['idservicio_pension']."' ";
					ejecutarConsulta($sql_servicio) or ($sw = false);
				}

			}

			if($servicio_p[$num_elementos]=='Almuerzo'){

				$buscando_serv="SELECT idservicio_pension FROM servicio_pension WHERE idpension='$idpension' AND nombre_servicio='$servicio_p[$num_elementos]'";
				$idbuscando_serv=ejecutarConsultaSimpleFila($buscando_serv);

				if (empty($idbuscando_serv['idservicio_pension'])) {

					$sql_servicio = "INSERT INTO servicio_pension(idpension,precio,nombre_servicio) VALUES ('$idpension','$p_almuerzo','Almuerzo')";
					ejecutarConsulta($sql_servicio) or ($sw = false);
				}else{

					$sql_servicio = "UPDATE servicio_pension SET precio='$p_almuerzo' WHERE idservicio_pension='".$idbuscando_serv['idservicio_pension']."' ";
					ejecutarConsulta($sql_servicio) or ($sw = false);
				}

			}
			
			if($servicio_p[$num_elementos]=='Cena'){

				$buscando_serv="SELECT idservicio_pension FROM servicio_pension WHERE idpension='$idpension' AND nombre_servicio='$servicio_p[$num_elementos]'";
				$idbuscando_serv=ejecutarConsultaSimpleFila($buscando_serv);

				if (empty($idbuscando_serv['idservicio_pension'])) {

					$sql_servicio = "INSERT INTO servicio_pension(idpension,precio,nombre_servicio) VALUES ('$idpension','$p_cena','Cena')";
					ejecutarConsulta($sql_servicio) or ($sw = false);
				}else{

					$sql_servicio = "UPDATE servicio_pension SET precio='$p_cena' WHERE idservicio_pension='".$idbuscando_serv['idservicio_pension']."' ";
					ejecutarConsulta($sql_servicio) or ($sw = false);
				}

			}	

            $num_elementos = $num_elementos + 1;
        }

        return $sw;
	}
	public function listar_pensiones($nube_idproyecto)
	{
		$sql="SELECT p.idpension, p.idproyecto, p.idproveedor, pr_v.razon_social, pr_v.direccion, p.estado
		FROM pension as p, proyecto as py, proveedor as pr_v
		WHERE p.estado=1 AND p.idproyecto='$nube_idproyecto' AND p.idproyecto=py.idproyecto AND p.idproveedor=pr_v.idproveedor";
		return ejecutarConsulta($sql);
	}
	public function total_x_pension($idpension)
	{
		$total_m=0;

		$sql="SELECT sp.idservicio_pension FROM servicio_pension As sp, pension AS p WHERE sp.idpension='$idpension' AND sp.idpension=p.idpension";
		$obt_servicio_pen=ejecutarConsulta($sql);

		foreach ($obt_servicio_pen as $key => $value) {

			$idservicio_p= $value['idservicio_pension'];

			$sql_2="SELECT SUM(total) as total FROM semana_pension as sp, servicio_pension as serv_p WHERE sp.idservicio_pension='$idservicio_p' AND sp.idservicio_pension=serv_p.idservicio_pension";
			$return_pension = ejecutarConsultaSimpleFila($sql_2);

			$total_m=$total_m+$return_pension['total'];
		}

		return $total_m;
	}
	public function ver_detalle_x_servicio($idpension)
	{
		$sql="SELECT SUM(se_p.total) as total,sp.nombre_servicio,SUM(se_p.adicional_descuento) as adicional_descuento,SUM(se_p.cantidad_total_platos) as cantidad_total_platos, sp.precio
		FROM servicio_pension as sp, pension as p, semana_pension as se_p 
		WHERE p.idpension='$idpension' AND sp.idpension=p.idpension AND se_p.idservicio_pension=sp.idservicio_pension GROUP BY se_p.idservicio_pension";
		return ejecutarConsulta($sql);
		
	
	}
	public function mostrar_pension($idpension)
	{
		$datos_edit_pension= Array(); 

		$sql="SELECT p.idpension,p.idproyecto,p.idproveedor FROM pension as p, proyecto as py WHERE p.idpension ='$idpension'  AND py.idproyecto=p.idproyecto";
		$return_pension = ejecutarConsultaSimpleFila($sql);

		$sql_2="SELECT sp.idservicio_pension,sp.nombre_servicio,sp.precio FROM servicio_pension AS sp, pension as p 
				WHERE sp.idpension='$idpension' AND sp.idpension=p.idpension";

		$servicio_pension = ejecutarConsultaArray($sql_2);	

		$sql_3="SELECT sp.nombre_servicio FROM servicio_pension AS sp, pension as p 
		WHERE sp.idpension='$idpension' AND sp.idpension=p.idpension";
		
		$select_s_pension = ejecutarConsultaArray($sql_3);	

		$datos_edit_pension= array(
			"idpension"             =>$return_pension['idpension'],
			"idproyecto"     		=>$return_pension['idproyecto'],
			"idproveedor"         	=> $return_pension['idproveedor'],

			"servicio_pension"       =>$servicio_pension,
			"select_s_pension"       =>$select_s_pension

		);

		return $datos_edit_pension;
		
	}
	public function select_proveedor()
	{
		$sql = "SELECT `idproveedor`,`razon_social`, `direccion` FROM `proveedor` WHERE estado =1 AND idproveedor>1";
		return ejecutarConsulta($sql);
	}


}

?>