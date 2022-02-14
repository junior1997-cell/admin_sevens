<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Activos_fijos
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	//$idactivos_fijos,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
	//Implementamos un método para insertar registros
	public function insertar($numero_comprobante,$tipo_comprobante,$fecha_comprobante,
	$forma_de_pago,$subtotal,$igv,$total,$nombre_de_activo,$modelo_de_activo,$serie_de_activo,$marca_de_activo,$descripcion,$comprobante)
	{
	
		$sql="INSERT INTO `activos_fijos`(`numero_comprobante`, `tipo_comprobante`, `fecha_comprobante`, `forma_de_pago`, `subtotal`, `igv`, 
		`total`, `nombre_de_activo`, `modelo_de_activo`, `serie_de_activo`, `marca_de_activo`, `descripcion`, `comprobante`) 
		VALUES ('$numero_comprobante','$tipo_comprobante','$fecha_comprobante','$forma_de_pago','$subtotal','$igv',
		'$total','$nombre_de_activo','$modelo_de_activo','$serie_de_activo','$marca_de_activo','$descripcion','$comprobante')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idactivos_fijos,$numero_comprobante,$tipo_comprobante,$fecha_comprobante,
					$forma_de_pago,$subtotal,$igv,$total,$nombre_de_activo,$modelo_de_activo,$serie_de_activo,$marca_de_activo,$descripcion,$comprobante
	)
	{
		$sql="UPDATE activos_fijos SET 
		`numero_comprobante`='$numero_comprobante',
		`tipo_comprobante`='$tipo_comprobante',
		`fecha_comprobante`='$fecha_comprobante',
		`forma_de_pago`='$forma_de_pago',
		`subtotal`='$subtotal',
		`igv`='$igv',
		`total`='$total',
		`nombre_de_activo`='$nombre_de_activo',
		`modelo_de_activo`='$modelo_de_activo',
		`serie_de_activo`='$serie_de_activo',
		`marca_de_activo`='$marca_de_activo',
		`descripcion`='$descripcion',
		`comprobante`='$comprobante'

		WHERE idactivos_fijos='$idactivos_fijos'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idactivos_fijos )
	{
		$sql="UPDATE activos_fijos SET estado='0' WHERE idactivos_fijos ='$idactivos_fijos'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idactivos_fijos )
	{
		$sql="UPDATE activos_fijos SET estado='1' WHERE idactivos_fijos ='$idactivos_fijos'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idactivos_fijos )
	{
		$sql="SELECT*FROM activos_fijos   
		WHERE idactivos_fijos ='$idactivos_fijos'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($idproyecto)
	{
		$sql="SELECT*FROM activos_fijos WHERE idproyecto='$idproyecto' ORDER BY idactivos_fijos DESC";
		return ejecutarConsulta($sql);		
	}

	//Seleccionar un comprobante
	public function ficha_tec($idactivos_fijos)
	{
		$sql="SELECT comprobante FROM activos_fijos WHERE idactivos_fijos='$idactivos_fijos'";
		return ejecutarConsulta($sql);		
	}
	//total
	public function total($idproyecto){
		$sql="SELECT SUM(costo_parcial) as precio_parcial FROM activos_fijos WHERE idproyecto='$idproyecto' AND estado=1";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>