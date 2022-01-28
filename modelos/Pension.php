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
	public function ver_detalle_semana_dias($f1,$f2,$nube_idproyect){

		$idpension=''; $idproyecto=''; $tipo_pension=''; $precio_variable='';

		$idsemana_pension=''; $precio_comida=''; $cantidad_total_platos=''; $adicional_descuento=''; $total=''; $descripcion='';

		$datos_semana= Array(); 

		$sql_1="SELECT idpension, idproyecto, tipo_pension, precio_variable FROM pension WHERE estado=1 AND idproyecto='$nube_idproyect'";
		$pension =ejecutarConsultaArray($sql_1);

		if (!empty($pension)) {

			foreach ($pension as $key => $value) {


				$idpension = $value['idpension'];

				$sql_2="SELECT dp.iddetalle_pension, dp.idpension, dp.fecha_pension, dp.cantidad_platos
				FROM detalle_pension as dp, proyecto as p, pension as pen 
				WHERE dp.estado=1 AND dp.idpension='$idpension' AND dp.idpension= pen.idpension AND pen.idproyecto=p.idproyecto  AND dp.fecha_pension BETWEEN '$f1' AND '$f2'";
				$datos_rangos_fechas= ejecutarConsultaArray($sql_2);

				$sql_3 = "SELECT idsemana_pension,precio_comida,cantidad_total_platos,adicional_descuento,total,descripcion 
				FROM semana_pension as sp, pension as p
				WHERE sp.estado AND sp.idpension='$idpension' AND sp.fecha_inicio='$f1' AND sp.idpension=p.idpension";
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
					"idpension"             => $value['idpension'],
					"idproyecto"     		=> $value['idproyecto'],
					"tipo_pension"         	=> $value['tipo_pension'],
					"precio_variable"       => $value['precio_variable'],

					"idsemana_pension"      =>$idsemana_pension,
					"precio_comida"         => $precio_comida,
					"cantidad_total_platos" =>$cantidad_total_platos,
					"adicional_descuento"   =>$adicional_descuento,
					"total"                 =>$total, 
					"descripcion"           =>$descripcion,
					"dias_q_comieron"       =>$datos_rangos_fechas

				);	
			}

		}else{

			$idpension=''; $idproyecto=''; $tipo_pension=''; $precio_variable='';

			$datos_semana[]= array(
				"idpension"             =>'',
				"idproyecto"     		=>'',
				"tipo_pension"         	=>'',
				"precio_variable"       =>'',

				"idsemana_pension"      =>'',
				"precio_comida"         =>'',
				"cantidad_total_platos" =>'',
				"adicional_descuento"   =>'',
				"total"                 =>'', 
				"descripcion"           =>'',
				"dias_q_comieron"       =>$data=[]

			);	
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
		$sql="SELECT sp.numero_semana as numero_semana,sp.fecha_inicio as fecha_inicio, sp.fecha_fin as fecha_fin,sp.total as total, p.tipo_pension, sp.precio_comida as precio_comida,sp.cantidad_total_platos as cantidad_total_platos, sp.adicional_descuento as adicional_descuento
		FROM semana_pension AS sp, pension AS p, proyecto as py
		WHERE  p.idproyecto = '$nube_idproyecto'  AND p.idpension=sp.idpension AND py.idproyecto=p.idproyecto AND numero_semana='$numero_semana'";
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


}

?>