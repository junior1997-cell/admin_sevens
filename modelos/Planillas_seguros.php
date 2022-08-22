<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Planillas_seguros
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	
	//Implementamos un método para insertar registros
	public function insertar($idproyecto, $idproveedor, $ruc_proveedor, $fecha_p_s, $precio_parcial, $subtotal, $igv, $val_igv, $tipo_gravada,$glosa, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante)
	{
		$sql_1 = "SELECT p.razon_social, p.tipo_documento, p.ruc, ps.fecha_p_s, ps.forma_de_pago, ps.tipo_comprobante, ps.numero_comprobante,  ps.estado, ps.estado_delete
		FROM planilla_seguro as ps, proveedor as p 
		WHERE ps.idproveedor = p.idproveedor and ps.idproyecto ='$idproyecto' and p.ruc ='$ruc_proveedor' and ps.tipo_comprobante ='$tipo_comprobante' and ps.numero_comprobante ='$nro_comprobante';";
		$prov = ejecutarConsultaArray($sql_1);
		if ($prov['status'] == false) { return  $prov;}

		if (empty($prov['data']) || $tipo_comprobante=='Ninguno') {
			
			$sql="INSERT INTO planilla_seguro (idproyecto, idproveedor, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_p_s, costo_parcial, subtotal, igv, val_igv, tipo_gravada, glosa, descripcion, comprobante) 
			VALUES ('$idproyecto', '$idproveedor','$tipo_comprobante','$nro_comprobante','$forma_pago','$fecha_p_s','$precio_parcial','$subtotal','$igv', '$val_igv', '$tipo_gravada', '$glosa', '$descripcion','$comprobante')";
			return ejecutarConsulta($sql);

		} else {

			$info_repetida = ''; 

			foreach ($prov['data'] as $key => $value) {
				$info_repetida .= '<li class="text-left font-size-13px">
				<span class="font-size-18px text-danger"><b >'.$value['tipo_comprobante'].': </b> '.$value['numero_comprobante'].'</span><br>
				<b>Razón Social: </b>'.$value['razon_social'].'<br>
				<b>'.$value['tipo_documento'].': </b>'.$value['ruc'].'<br>          
				<b>Fecha: </b>'.format_d_m_a($value['fecha_p_s']).'<br>
				<b>Forma de pago: </b>'.$value['forma_de_pago'].'<br>
				<b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b> 
				<b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
				<hr class="m-t-2px m-b-2px">
				</li>'; 
			}
			return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );
		}			
	}

	//Implementamos un método para editar registros
	public function editar($idplanilla_seguro, $idproyecto, $idproveedor, $fecha_p_s, $precio_parcial, $subtotal, $igv, $val_igv, $tipo_gravada, $glosa, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante)
	{
		//if ($tipo_comprobante =='Factura' || $tipo_comprobante =='Boleta' ) { } else { $ruc =''; $razon_social =''; $direccion =''; }

		$sql="UPDATE planilla_seguro SET 
		idproyecto='$idproyecto',
		idproveedor='$idproveedor',
		fecha_p_s='$fecha_p_s',
		costo_parcial='$precio_parcial',
		subtotal='$subtotal',
		igv='$igv',
		val_igv='$val_igv',
		tipo_gravada='$tipo_gravada',
		glosa='$glosa',
		descripcion='$descripcion',
		forma_de_pago='$forma_pago',
		tipo_comprobante='$tipo_comprobante',
		numero_comprobante='$nro_comprobante',
		comprobante='$comprobante'

		WHERE idplanilla_seguro='$idplanilla_seguro'";	
		
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar 
	public function desactivar($idplanilla_seguro )
	{
		$sql="UPDATE planilla_seguro SET estado='0' WHERE idplanilla_seguro ='$idplanilla_seguro'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar 
	public function activar($idplanilla_seguro )
	{
		$sql="UPDATE planilla_seguro SET estado='1' WHERE idplanilla_seguro ='$idplanilla_seguro'";
		return ejecutarConsulta($sql);
	}

	
	//Implementamos un método para eliminar 
	public function eliminar($idplanilla_seguro )
	{
		$sql="UPDATE planilla_seguro SET estado_delete='0' WHERE idplanilla_seguro ='$idplanilla_seguro'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idplanilla_seguro )
	{
		$sql="SELECT*FROM planilla_seguro WHERE idplanilla_seguro ='$idplanilla_seguro'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function ver_detalle($idplanilla_seguro )
	{
		$sql="SELECT ps.idplanilla_seguro, ps.idproyecto, ps.idproveedor, ps.tipo_comprobante, ps.numero_comprobante, ps.forma_de_pago, 
		ps.fecha_p_s, ps.subtotal, ps.igv, ps.costo_parcial, ps.descripcion, ps.val_igv, ps.tipo_gravada, ps.glosa, ps.comprobante, 
		p.razon_social, p.tipo_documento, p.ruc
		FROM planilla_seguro as ps, proveedor as p
		WHERE ps.idproveedor = p.idproveedor and ps.idplanilla_seguro  ='$idplanilla_seguro'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($idproyecto,$fecha_1,$fecha_2,$id_proveedor,$comprobante)
	{
		$filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

		if ( !empty($fecha_1) && !empty($fecha_2) ) {
		  $filtro_fecha = "AND ps.fecha_p_s BETWEEN '$fecha_1' AND '$fecha_2'";
		} else if (!empty($fecha_1)) {      
		  $filtro_fecha = "AND ps.fecha_p_s = '$fecha_1'";
		}else if (!empty($fecha_2)) {        
		  $filtro_fecha = "AND ps.fecha_p_s = '$fecha_2'";
		}   
		if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ps.idproveedor = '$id_proveedor'"; }
	
		if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND ps.tipo_comprobante = '$comprobante'"; }  
	

		$sql="SELECT ps.idplanilla_seguro, ps.idproyecto, ps.idproveedor, ps.tipo_comprobante, ps.numero_comprobante, ps.forma_de_pago, ps.fecha_p_s, ps.subtotal, ps.igv, ps.costo_parcial, ps.descripcion, ps.val_igv, ps.tipo_gravada, ps.glosa, ps.comprobante,
		p.razon_social, p.tipo_documento, p.ruc, ps.estado
		FROM planilla_seguro as ps, proveedor as p 
		WHERE ps.idproveedor = p.idproveedor and ps.idproyecto='$idproyecto' AND ps.estado_delete='1' AND ps.estado='1' $filtro_proveedor $filtro_fecha $filtro_comprobante   ORDER BY ps.idplanilla_seguro DESC";
		return ejecutarConsulta($sql);		
	}

	//Seleccionar un comprobante
	public function ficha_tec($idplanilla_seguro)
	{
		$sql="SELECT comprobante FROM planilla_seguro WHERE idplanilla_seguro='$idplanilla_seguro'";
		return ejecutarConsulta($sql);		
	}
	//total
	public function total($idproyecto,$fecha_1,$fecha_2,$id_proveedor,$comprobante){

		$filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

		if ( !empty($fecha_1) && !empty($fecha_2) ) {
		  $filtro_fecha = "AND ps.fecha_p_s BETWEEN '$fecha_1' AND '$fecha_2'";
		} else if (!empty($fecha_1)) {      
		  $filtro_fecha = "AND ps.fecha_p_s = '$fecha_1'";
		}else if (!empty($fecha_2)) {        
		  $filtro_fecha = "AND ps.fecha_p_s = '$fecha_2'";
		}   
		if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND ps.idproveedor = '$id_proveedor'"; }
	
		if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND ps.tipo_comprobante = '$comprobante'"; }  

		$sql="SELECT SUM(ps.costo_parcial) as precio_parcial 
		FROM planilla_seguro as ps, proveedor as p 
		WHERE ps.idproveedor = p.idproveedor and ps.idproyecto='$idproyecto' AND ps.estado_delete='1' AND ps.estado='1' $filtro_proveedor $filtro_fecha $filtro_comprobante   ORDER BY ps.idplanilla_seguro DESC";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>