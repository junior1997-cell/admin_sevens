<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Planillas_seguros
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	//$idplanilla_seguro,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$fecha_p_s,$precio_parcial,$subtotal,$igv,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$comprobante)
	{
	
		$sql="INSERT INTO planilla_seguro (idproyecto, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_p_s, costo_parcial, subtotal, igv, descripcion, comprobante) 
		VALUES ('$idproyecto','$tipo_comprobante','$nro_comprobante','$forma_pago','$fecha_p_s','$precio_parcial','$subtotal','$igv','$descripcion','$comprobante')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idplanilla_seguro,$idproyecto,$fecha_p_s,$precio_parcial,$subtotal,$igv,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$comprobante)
	{
		$sql="UPDATE planilla_seguro SET 
		idproyecto='$idproyecto',
		fecha_p_s='$fecha_p_s',
		costo_parcial='$precio_parcial',
		subtotal='$subtotal',
		igv='$igv',
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

	//Implementar un método para listar los registros
	public function listar($idproyecto)
	{
		$sql="SELECT*FROM planilla_seguro WHERE idproyecto='$idproyecto' AND estado_delete='1' AND estado='1' ORDER BY idplanilla_seguro DESC";
		return ejecutarConsulta($sql);		
	}

	//Seleccionar un comprobante
	public function ficha_tec($idplanilla_seguro)
	{
		$sql="SELECT comprobante FROM planilla_seguro WHERE idplanilla_seguro='$idplanilla_seguro'";
		return ejecutarConsulta($sql);		
	}
	//total
	public function total($idproyecto){
		$sql="SELECT SUM(costo_parcial) as precio_parcial FROM planilla_seguro WHERE idproyecto='$idproyecto' AND estado_delete='1' AND estado='1'";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>