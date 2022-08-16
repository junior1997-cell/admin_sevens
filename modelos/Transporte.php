<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Transporte
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	
	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$idproveedor,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$val_igv,$tipo_gravada,$comprobante,$glosa,$ruc_proveedor)
	{
		$sql_1 = "SELECT p.razon_social, p.tipo_documento, p.ruc, t.fecha_viaje, t.forma_de_pago, t.tipo_comprobante, 
		t.numero_comprobante,  t.estado, t.estado_delete
		FROM transporte as t, proveedor as p
		WHERE t.idproveedor = p.idproveedor and t.idproyecto ='$idproyecto' and p.ruc ='$ruc_proveedor' and t.tipo_comprobante ='$tipo_comprobante' and t.numero_comprobante ='$nro_comprobante';";
		$prov = ejecutarConsultaArray($sql_1);
		if ($prov['status'] == false) { return  $prov;}

		if (empty($prov['data']) || $tipo_comprobante=='Ninguno') {
				
			$sql="INSERT INTO transporte (idproyecto,idproveedor,fecha_viaje,tipo_viajero,tipo_ruta,cantidad,precio_unitario,precio_parcial,ruta,descripcion,forma_de_pago,tipo_comprobante,numero_comprobante,subtotal,igv,val_igv,tipo_gravada,comprobante,glosa) 
			VALUES ('$idproyecto','$idproveedor','$fecha_viaje','$tipo_viajero','$tipo_ruta','$cantidad','$precio_unitario','$precio_parcial','$ruta','$descripcion','$forma_pago','$tipo_comprobante','$nro_comprobante','$subtotal','$igv','$val_igv','$tipo_gravada','$comprobante','$glosa')";
			return ejecutarConsulta($sql);

		} else {

			$info_repetida = ''; 

			foreach ($prov['data'] as $key => $value) {
				$info_repetida .= '<li class="text-left font-size-13px">
				<span class="font-size-18px text-danger"><b >'.$value['tipo_comprobante'].': </b> '.$value['numero_comprobante'].'</span><br>
				<b>Razón Social: </b>'.$value['razon_social'].'<br>
				<b>'.$value['tipo_documento'].': </b>'.$value['ruc'].'<br>          
				<b>Fecha: </b>'.format_d_m_a($value['fecha_viaje']).'<br>
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
	public function editar($idtransporte,$idproyecto,$idproveedor,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$val_igv,$tipo_gravada,$comprobante,$glosa)
	{
		$sql="UPDATE transporte SET 
		idproyecto='$idproyecto',
		idproveedor='$idproveedor',
		fecha_viaje='$fecha_viaje',
		tipo_viajero='$tipo_viajero',
		tipo_ruta='$tipo_ruta',
		cantidad='$cantidad',
		precio_unitario='$precio_unitario',
		precio_parcial='$precio_parcial',
		ruta='$ruta',
		descripcion='$descripcion',
		forma_de_pago='$forma_pago',
		tipo_comprobante='$tipo_comprobante',
		numero_comprobante='$nro_comprobante',
		subtotal='$subtotal',
		igv='$igv',
		val_igv='$val_igv',
		tipo_gravada='$tipo_gravada',
		comprobante='$comprobante',
		glosa='$glosa'

		WHERE idtransporte='$idtransporte'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idtransporte )
	{
		$sql="UPDATE transporte SET estado='0' WHERE idtransporte ='$idtransporte'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idtransporte )
	{
		$sql="UPDATE transporte SET estado='1' WHERE idtransporte ='$idtransporte'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function eliminar($idtransporte )
	{
		$sql="UPDATE transporte SET estado_delete='0' WHERE idtransporte ='$idtransporte'";
		return ejecutarConsulta($sql);
	}
	

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idtransporte )
	{
		$sql="SELECT*FROM transporte WHERE idtransporte ='$idtransporte'";

		return ejecutarConsultaSimpleFila($sql);
	}

	public function verdatos($idtransporte)
	{
		$sql="SELECT t.idtransporte,t.idproyecto,t.idproveedor,t.tipo_comprobante,t.numero_comprobante,t.forma_de_pago,t.fecha_viaje,t.tipo_viajero,t.glosa,t.tipo_ruta,t.ruta,t.cantidad,t.precio_unitario,t.subtotal,t.igv,t.precio_parcial,t.descripcion,t.comprobante, p.razon_social, p.ruc
		FROM transporte as t, proveedor as p WHERE t.idtransporte='$idtransporte' AND t.idproveedor=p.idproveedor;";

		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($idproyecto)
	{
		$sql="SELECT t.idtransporte, t.idproyecto, t.idproveedor, t.tipo_comprobante, t.numero_comprobante, t.forma_de_pago, 
		t.fecha_viaje, t.tipo_viajero, t.tipo_ruta, t.ruta, t.cantidad, t.precio_unitario, t.subtotal, t.igv, t.precio_parcial, 
		t.descripcion, t.val_igv, t.tipo_gravada, t.glosa, t.estado, t.estado_delete, p.razon_social,p.tipo_documento,p.ruc,p.direccion 
		FROM transporte as t, proveedor as p
		WHERE t.idproveedor = p.idproveedor AND t.idproyecto='$idproyecto' AND t.estado='1' AND  t.estado_delete='1' ORDER BY t.fecha_viaje DESC;";
		return ejecutarConsulta($sql);		
	}

	//Select2 Proveedor
	public function select2_proveedor()
	{
	$sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE  estado=1 AND estado_delete=1";
	return ejecutarConsulta($sql);
	}
	
	//Seleccionar un comprobante
	public function ficha_tec($idtransporte)
	{
		$sql="SELECT comprobante FROM transporte WHERE idtransporte='$idtransporte'";
		return ejecutarConsulta($sql);		
	}

	//total
	public function total($idproyecto){
		$sql="SELECT SUM(precio_parcial) as precio_parcial FROM transporte WHERE idproyecto='$idproyecto' AND estado=1 AND estado_delete=1";
		return ejecutarConsultaSimpleFila($sql);
	}



}

?>