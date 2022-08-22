<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Comidas_extras
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function insertar($idproyecto,$fecha,$precio_parcial,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$val_igv,$tipo_gravada,$comprobante,$ruc,$razon_social,$direccion)
	{
		if ($tipo_comprobante =='Factura' || $tipo_comprobante =='Boleta' ) { } else {
			$ruc =''; $razon_social =''; $direccion ='';
		}	
				
		$sql_1 = "SELECT ruc, razon_social, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_comida, subtotal, costo_parcial,estado, estado_delete 
		FROM comida_extra WHERE idproyecto ='$idproyecto' AND ruc='$ruc' AND tipo_comprobante ='$tipo_comprobante' AND numero_comprobante ='$nro_comprobante';";
		$val_compr = ejecutarConsultaArray($sql_1);

		if ($val_compr['status'] == false) { return  $val_compr;}

		if (empty($val_compr['data']) || $tipo_comprobante=='Ninguno') {

			$sql="INSERT INTO comida_extra (idproyecto,fecha_comida,costo_parcial,descripcion,forma_de_pago,tipo_comprobante,numero_comprobante,subtotal,igv,val_igv,tipo_gravada,comprobante,ruc,razon_social,direccion) 
			VALUES ('$idproyecto','$fecha','$precio_parcial','$descripcion','$forma_pago','$tipo_comprobante','$nro_comprobante','$subtotal','$igv','$val_igv','$tipo_gravada','$comprobante','$ruc','$razon_social','$direccion')";
			return ejecutarConsulta($sql);

		} else {
			$info_repetida = '';
	
			foreach ($val_compr['data'] as $key => $value) {
			$info_repetida .= '<li class="text-left font-size-13px">
			<span class="font-size-18px text-danger"><b >'.$value['tipo_comprobante'].': </b> '.$value['numero_comprobante'].'</span><br>
			<b>Razón Social: </b>'.$value['razon_social'].'<br>
			<b>Ruc: </b>'.$value['ruc'].'<br>
			<b>Fecha: </b>'.format_d_m_a($value['fecha_comida']).'<br>
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
	public function editar($idcomida_extra,$idproyecto,$fecha,$precio_parcial,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$val_igv,$tipo_gravada,$comprobante,$ruc,$razon_social,$direccion)
	{
		if ($tipo_comprobante =='Factura' || $tipo_comprobante =='Boleta' ) { } else {
			$ruc =''; $razon_social =''; $direccion ='';
		}
		
		$sql="UPDATE comida_extra SET 
		idproyecto='$idproyecto',
		fecha_comida='$fecha',
		costo_parcial='$precio_parcial',
		descripcion='$descripcion',
		comprobante='$comprobante',
		forma_de_pago='$forma_pago',
		tipo_comprobante='$tipo_comprobante',
		numero_comprobante='$nro_comprobante',
		subtotal='$subtotal',
		igv='$igv',
		val_igv='$val_igv',
		tipo_gravada='$tipo_gravada',
		ruc='$ruc',
		razon_social='$razon_social',
		direccion='$direccion'
		
		WHERE idcomida_extra ='$idcomida_extra'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idcomida_extra )
	{
		$sql="UPDATE comida_extra SET estado='0' WHERE idcomida_extra ='$idcomida_extra'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idcomida_extra )
	{

		$sql="UPDATE comida_extra SET estado='1' WHERE idcomida_extra ='$idcomida_extra'";
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para desactivar categorías
	public function eliminar($idcomida_extra )
	{
		$sql="UPDATE comida_extra SET estado_delete='0' WHERE idcomida_extra ='$idcomida_extra'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcomida_extra )
	{
		$sql="SELECT*FROM comida_extra WHERE idcomida_extra ='$idcomida_extra'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($idproyecto,$fecha_1,$fecha_2,$id_proveedor,$comprobante)
	{
		$filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

		if ( !empty($fecha_1) && !empty($fecha_2) ) {
		  $filtro_fecha = "AND fecha_comida BETWEEN '$fecha_1' AND '$fecha_2'";
		} else if (!empty($fecha_1)) {      
		  $filtro_fecha = "AND fecha_comida = '$fecha_1'";
		}else if (!empty($fecha_2)) {        
		  $filtro_fecha = "AND fecha_comida = '$fecha_2'";
		}   
	
		if (empty($id_proveedor)) {
		  $filtro_proveedor = "";
		} else if ( $id_proveedor=='vacio' ) { 
		  $filtro_proveedor = "AND ruc IN ('',NULL)";
		} else { 
		  $filtro_proveedor = "AND ruc = '$id_proveedor'"; 
		}
	
		if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; } 
		
		$sql="SELECT*FROM comida_extra WHERE idproyecto='$idproyecto' AND estado_delete='1' AND  estado='1' $filtro_proveedor $filtro_fecha $filtro_comprobante ORDER BY idcomida_extra DESC";
		return ejecutarConsulta($sql);		
	}

	//Seleccionar un comprobante
	public function ficha_tec($idcomida_extra)
	{
		$sql="SELECT comprobante FROM comida_extra WHERE idcomida_extra='$idcomida_extra'";
		return ejecutarConsulta($sql);		
	}
	//total
	public function total($idproyecto,$fecha_1,$fecha_2,$id_proveedor,$comprobante){

		$filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

		if ( !empty($fecha_1) && !empty($fecha_2) ) {
		  $filtro_fecha = "AND fecha_comida BETWEEN '$fecha_1' AND '$fecha_2'";
		} else if (!empty($fecha_1)) {      
		  $filtro_fecha = "AND fecha_comida = '$fecha_1'";
		}else if (!empty($fecha_2)) {        
		  $filtro_fecha = "AND fecha_comida = '$fecha_2'";
		}   
	
		if (empty($id_proveedor)) {
		  $filtro_proveedor = "";
		} else if ( $id_proveedor=='vacio' ) { 
		  $filtro_proveedor = "AND ruc IN ('',NULL)";
		} else { 
		  $filtro_proveedor = "AND ruc = '$id_proveedor'"; 
		}
	
		if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; } 

		$sql="SELECT SUM(costo_parcial) as precio_parcial FROM comida_extra WHERE idproyecto='$idproyecto' AND estado='1' AND estado_delete='1' $filtro_proveedor $filtro_fecha $filtro_comprobante";
		return ejecutarConsultaSimpleFila($sql);
	}

	
	//seelect2  - proveedores
	public function selecct_provedor_comidas_ex($idproyecto)
	{
		$sql = "SELECT ruc,razon_social,direccion FROM comida_extra WHERE ruc!='' AND ruc!='null' AND idproyecto = '$idproyecto'  GROUP BY ruc;";
		return ejecutarConsultaArray($sql);
	}

}

?>