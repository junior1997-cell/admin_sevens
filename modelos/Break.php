<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Breaks
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

	///////////////////////CONSULTAS BREAK///////////////////////
	//listar_semana_botones
	public function listarsemana_botones($nube_idproyecto){
		$sql="SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin, p.plazo, p.fecha_pago_obrero, p.fecha_valorizacion FROM proyecto as p WHERE p.idproyecto='$nube_idproyecto'";
		return ejecutarConsultaSimpleFila($sql);
	}
	//ver detalle semana a semana
	public function ver_detalle_semana_dias($f1,$f2,$nube_idproyect){
		//var_dump($f1,$f2,$nube_idproyect);die();
		$sql="SELECT * FROM breaks WHERE idproyecto='$nube_idproyect' AND fecha_compra BETWEEN '$f1' AND '$f2' ";
		return ejecutarConsultaArray($sql);
	}	
	///////////////////////CONSULTAS BREAK///////////////////////

	//Implementar un método para listar los registros
	/*public function listar_totales_semana($nube_idproyecto,$array_fi_ff)
	{
		$fecha_in =""; $fecha_fi=""; $num_semana=""; $semana=""; $total_por_semana=[]; $val_total="";
		$desglese_fechas_semanas = json_decode($array_fi_ff,true);

		foreach ($desglese_fechas_semanas as $key => $value) {

			$data_array=[];
			$fecha_in =  $value['fecha_in']; 
			$fecha_fi = $value['fecha_fi']; 
			$num_semana = $value['num_semana']; 

			$sql="SELECT SUM(costo_parcial) AS total FROM breaks WHERE idproyecto='$nube_idproyecto' AND fecha_compra BETWEEN '$fecha_in' AND '$fecha_fi'";
			$semana=ejecutarConsultaSimpleFila($sql);
			if (empty($semana['total'])) {
				$val_total="0.00";
			} else {
				$val_total=$semana['total'];
			}
			
			$data_array=array(
				'total'=>$val_total,
				'fecha_in'=>$fecha_in,
				'fecha_fi'=>$fecha_fi,
				'num_semana'=>$num_semana
			);
			
			array_push($total_por_semana, $data_array);
		}
		return json_encode($total_por_semana, true);		
	}*/
	public function listar($nube_idproyecto)
	{
		$sql="SELECT * FROM semana_break WHERE idproyecto ='$nube_idproyecto' ORDER BY numero_semana DESC";
		return ejecutarConsulta($sql);
	}
	//----------------------comprobantes------------------------------
	public function insertar_comprobante($idsemana_break,$forma_pago,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$imagen2,$ruc,$razon_social,$direccion){
		//var_dump($idsemana_break,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$imagen2);die();
		$sql="INSERT INTO factura_break (idsemana_break,nro_comprobante, fecha_emision, monto, igv, subtotal,forma_de_pago, tipo_comprobante, descripcion, comprobante,ruc, razon_social, direccion) 
		VALUES ('$idsemana_break','$nro_comprobante','$fecha_emision','$monto','$igv','$subtotal','$forma_pago','$tipo_comprobante','$descripcion','$imagen2','$ruc','$razon_social','$direccion')";
		return ejecutarConsulta($sql);
	}
	// obtebnemos los DOCS para eliminar
	public function obtenerDoc($idfactura_break) {

		$sql = "SELECT comprobante FROM factura_break WHERE idfactura_break  ='$idfactura_break'";
	
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para editar registros
	public function editar_comprobante($idfactura_break,$idsemana_break,$forma_pago,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$imagen2,$ruc,$razon_social,$direccion){
		//$vaa="$idfactura,$idproyectof,$idmaquina,$codigo,$monto,$fecha_emision,$descripcion_f,$imagen2";
		$sql="UPDATE `factura_break` SET 
		
		idsemana_break='$idsemana_break',
		forma_de_pago='$forma_pago',
		nro_comprobante='$nro_comprobante',
		fecha_emision='$fecha_emision',
		monto='$monto',
		igv='$igv',
		subtotal='$subtotal',
		tipo_comprobante='$tipo_comprobante',
		descripcion='$descripcion',
		comprobante='$imagen2',
		ruc='$ruc',
		razon_social='$razon_social',
		direccion='$direccion'
		 WHERE idfactura_break='$idfactura_break';";	
		return ejecutarConsulta($sql);	
		//return $vaa;
	}

	public function listar_comprobantes($idsemana_break){

		$sql="SELECT * FROM factura_break WHERE idsemana_break  ='$idsemana_break' AND estado_delete='1' AND estado='1' ORDER BY fecha_emision DESC";
		return ejecutarConsulta($sql);
	}
	//mostrar_comprobante
	public function mostrar_comprobante($idfactura_break){
		
		$sql="SELECT * FROM factura_break WHERE idfactura_break ='$idfactura_break '";
		return ejecutarConsultaSimpleFila($sql);
	}
	//Implementamos un método para activar 
	public function desactivar_comprobante($idfactura_break){
		
		$sql="UPDATE factura_break SET estado='0' WHERE idfactura_break ='$idfactura_break '";
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para desactivar 
	public function activar_comprobante($idfactura_break){
		
		$sql="UPDATE factura_break SET estado='1' WHERE idfactura_break ='$idfactura_break '";
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para eliminar 
	public function eliminar_comprobante($idfactura_break){
	
		$sql="UPDATE factura_break SET estado_delete='0' WHERE idfactura_break ='$idfactura_break '";
		return ejecutarConsulta($sql);
	}
	public function total_monto_comp($idsemana_break){
		
		$sql="SELECT SUM(monto) as total FROM factura_break WHERE idsemana_break='$idsemana_break' AND estado_delete='1' AND estado='1'";
		return ejecutarConsultaSimpleFila($sql);

	}


}

?>