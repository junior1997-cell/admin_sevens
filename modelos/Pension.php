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
	public function insertar_editar($array_detalle_pen,$array_semana_pen){

		$desglese_array_detalle_pen = json_decode($array_detalle_pen,true); $sw = true;
		$desglose_array_semana_pen = json_decode($array_semana_pen, true);
		// registramos o editamos los "Break por semana"
		foreach ($desglese_array_detalle_pen as $indice => $key) {
		
			if ( empty($key['iddetalle_pension'])) {

				// insertamos un nuevo registro
				$sql_2="INSERT INTO `detalle_pension`(`idservicio_pension`, `fecha_pension`, `dia_semana`, `cantidad_platos`, `precio_plato`, `precio_parcial`) 

				VALUES ('".$key['idservicio_pension']."','".$key['fecha_pension']."','".$key['dia_semana']."','".$key['cantidad_platos']."','".$key['precio_plato']."','".$key['precio_parcial']."')";
				ejecutarConsulta($sql_2) or $sw = false;

			} else {
				# editamos el registro existente
				$sql_3="UPDATE detalle_pension SET 
				idservicio_pension='".$key['idservicio_pension']."', 
				fecha_pension='".$key['fecha_pension']."', 
				dia_semana='".$key['dia_semana']."', 
				cantidad_platos='".$key['cantidad_platos']."',
				precio_plato='".$key['precio_plato']."',	
				precio_parcial='".$key['precio_parcial']."'	
				WHERE iddetalle_pension ='".$key['iddetalle_pension']."';";
				
				ejecutarConsulta($sql_3) or $sw = false;
			}
		}
		foreach ($desglose_array_semana_pen as $key => $value) {
           
			if(empty($value['idsemana_pension'])){
 				// insertamos un nuevo registro
				$sql_5 = "INSERT INTO `semana_pension`(`idservicio_pension`, `fecha_inicio`, `fecha_fin`, `numero_semana`, `precio_comida`, `cantidad_total_platos`, `adicional_descuento`, `total`, `descripcion`) 
				VALUES ('".$value['idservicio_pension']."','".$value['fecha_inicio']."','".$value['fecha_fin']."','".$value['numero_semana']."','".$value['precio_comida']."','".$value['cantidad_total_platos']."','".$value['adicional_descuento']."','".$value['total']."','".$value['descripcion']."')";
				ejecutarConsulta($sql_5) or $sw = false;
			}else{
				# editamos el registro existente
				$sql6 = " UPDATE semana_pension SET 
					idservicio_pension='".$value['idservicio_pension']."',
					fecha_inicio='".$value['fecha_inicio']."',
					fecha_fin='".$value['fecha_fin']."',
					numero_semana='".$value['numero_semana']."',
					precio_comida='".$value['precio_comida']."',
					cantidad_total_platos='".$value['cantidad_total_platos']."',
					adicional_descuento='".$value['adicional_descuento']."',
					total='".$value['total']."',
					descripcion='".$value['descripcion']."'
					WHERE  idsemana_pension='".$value['idsemana_pension']."';";
				ejecutarConsulta($sql6) or $sw = false;
			}
		}
		return $sw;	
	}

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

				$sql_2="SELECT dp.iddetalle_pension,dp.fecha_pension, dp.cantidad_platos
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
					"precio_t_servicio_p"    => $value['precio'],

					"idsemana_pension"      =>$idsemana_pension,
					"precio_t_semana_p"     =>$precio_comida,
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
	public function insertar_comprobante($idpension_f,$forma_pago,$tipo_comprovante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$imagen2)
	{
		$sql="INSERT INTO factura_pension (idpension ,nro_comprobante, fecha_emision, monto, igv, subtotal,forma_de_pago, tipo_comprobante, descripcion, comprobante) 
		VALUES ('$idpension_f','$nro_comprobante','$fecha_emision','$monto','$igv','$subtotal','$forma_pago','$tipo_comprovante','$descripcion','$imagen2')";
		return ejecutarConsulta($sql);
	}
	// obtebnemos los DOCS para eliminar
	public function obtenerDoc($idfactura_pension) 
	{

		$sql = "SELECT comprobante FROM factura_pension WHERE idfactura_pension  ='$idfactura_pension'";
	
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para editar registros
	public function editar_comprobante($idfactura_pension,$idpension_f,$forma_pago,$tipo_comprovante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$imagen2)
	{
		//$vaa="$idfactura,$idproyectof,$idmaquina,$codigo,$monto,$fecha_emision,$descripcion_f,$imagen2";
		$sql="UPDATE `factura_pension` SET 
		
		idpension ='$idpension_f',
		forma_de_pago='$forma_pago',
		nro_comprobante='$nro_comprobante',
		fecha_emision='$fecha_emision',
		monto='$monto',
		igv='$igv',
		subtotal='$subtotal',
		tipo_comprobante='$tipo_comprovante',
		descripcion='$descripcion',
		comprobante='$imagen2'
		 WHERE idfactura_pension='$idfactura_pension';";	
		return ejecutarConsulta($sql);	
		//return $vaa;
	}

	public function listar_comprobantes($idpension){

		$sql="SELECT * FROM factura_pension WHERE idpension  ='$idpension' AND estado='1' AND  estado_delete='1'";
		return ejecutarConsulta($sql);
	}
	//mostrar_comprobante
	public function mostrar_comprobante($idfactura_pension){
		$sql="SELECT * FROM factura_pension WHERE idfactura_pension ='$idfactura_pension'";
		return ejecutarConsultaSimpleFila($sql);
	}
	//Implementamos un método para activar 
	public function desactivar_comprobante($idfactura_pension){
		//var_dump($idfactura_pension);die();
		$sql="UPDATE factura_pension SET estado='0' WHERE idfactura_pension ='$idfactura_pension'";
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para desactivar 
	public function activar_comprobante($idfactura_pension){
		//var_dump($idpago_servicio);die();
		$sql="UPDATE factura_pension SET estado='1' WHERE idfactura_pension ='$idfactura_pension'";
		return ejecutarConsulta($sql);
	}
	
	public function total_monto_comp($idpension){
		$sql="SELECT SUM(monto) as total FROM factura_pension WHERE idpension='$idpension' AND estado='1' AND  estado_delete='1'";
		return ejecutarConsultaSimpleFila($sql);

	}
	
		//Implementamos un método para activar 
		public function eliminar_comprobante($idfactura_pension){
			//var_dump($idfactura_pension);die();
			$sql="UPDATE factura_pension SET estado_delete='0' WHERE idfactura_pension ='$idfactura_pension'";
			return ejecutarConsulta($sql);
		}
	//---------------------------pension-----------------------------------
	public function insertar_pension($idproyecto_p,$proveedor,$p_desayuno,$p_almuerzo,$p_cena,$descripcion_pension,$servicio_p)
	{
		$sql = "INSERT INTO pension(idproyecto, idproveedor,descripcion) VALUES ('$idproyecto_p','$proveedor','$descripcion_pension')";
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
	public function editar_pension($idproyecto_p,$idpension,$proveedor,$p_desayuno,$p_almuerzo,$p_cena,$descripcion_pension,$servicio_p)
	{
		//var_dump($idproyecto_p,$idpension,$proveedor,$p_desayuno,$p_almuerzo,$p_cena,$servicio_p); die();
		$sql = "UPDATE pension SET idproyecto='$idproyecto_p',idproveedor='$proveedor',descripcion='$descripcion_pension' WHERE idpension='$idpension'";
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
		$sql="SELECT p.idpension, p.idproyecto, p.idproveedor,p.descripcion, pr_v.razon_social, pr_v.direccion, p.estado
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
	public function total_pago_x_pension($idpension)
	{
		$sql="SELECT SUM(fp.monto) AS total_pago FROM factura_pension fp, pension as p WHERE fp.idpension=p.idpension AND fp.idpension='$idpension' AND fp.estado=1 AND  fp.estado_delete='1'";
		return ejecutarConsultaSimpleFila($sql);
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

		$sql="SELECT p.idpension,p.idproyecto,p.idproveedor, p.descripcion FROM pension as p, proyecto as py WHERE p.idpension ='$idpension'  AND py.idproyecto=p.idproyecto";
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
			"descripcion"         	=> $return_pension['descripcion'],

			"servicio_pension"       =>$servicio_pension,
			"select_s_pension"       =>$select_s_pension

		);

		return $datos_edit_pension;
		
	}
	public function total_pension($idproyecto)
	{
		$sql="SELECT SUM(sp.total) as total
		FROM semana_pension as sp, servicio_pension as se_p, pension as p, proyecto as py
		WHERE sp.idservicio_pension=se_p.idservicio_pension AND se_p.idpension=p.idpension AND p.idproyecto=py.idproyecto AND p.idproyecto='$idproyecto'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function select_proveedor()
	{
		$sql = "SELECT `idproveedor`,`razon_social`, `direccion` FROM `proveedor` WHERE estado =1 AND idproveedor>1";
		return ejecutarConsulta($sql);
	}


}

?>