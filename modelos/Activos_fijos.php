<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Activos_fijos
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($color,$unid_medida,$nombre,$modelo,$serie,$marca,$precio_compra, $subtotal,$igv,$total,$descripcion,$imagen1,$ficha_tecnica,$estado_igv)
	{

		$sql="INSERT INTO activos_fijos (idcolor, idunidad_medida, nombre, modelo, serie, marca, precio_compra, subtotal, igv, total, descripcion, imagen, ficha_tecnica, estado_igv) 
		VALUES ('$color','$unid_medida','$nombre','$modelo','$serie','$marca','$precio_compra', '$subtotal','$igv','$total','$descripcion','$imagen1','$ficha_tecnica','$estado_igv')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idactivos_fijos,$color,$unid_medida,$nombre,$modelo,$serie,$marca,$precio_compra, $subtotal,$igv,$total,$descripcion,$imagen1,$ficha_tecnica,$estado_igv)
	{

		$sql="UPDATE activos_fijos SET 
		idcolor='$color',
		idunidad_medida='$unid_medida',
		nombre='$nombre',
		modelo='$modelo',
		serie='$serie',
		marca='$marca',
		precio_compra='$precio_compra',
		subtotal='$subtotal',
		igv='$igv',
		total='$total',
		descripcion='$descripcion',
		imagen='$imagen1',
		ficha_tecnica='$ficha_tecnica',
		estado_igv='$estado_igv'
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
	public function mostrar($idactivos_fijos)
	{
		$sql="SELECT*FROM activos_fijos WHERE idactivos_fijos ='$idactivos_fijos'";

		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT*FROM activos_fijos ORDER BY idactivos_fijos DESC";
		return ejecutarConsulta($sql);		
	}
	//Seleccionar Trabajador Select2
	public function obtenerImg($idactivos_fijos)
	{
		$sql="SELECT imagen FROM activos_fijos WHERE idactivos_fijos='$idactivos_fijos'";
		return ejecutarConsulta($sql);		
	}
	//Seleccionar una ficha tecnica
	public function ficha_tec($idactivos_fijos)
	{
		$sql="SELECT ficha_tecnica FROM activos_fijos WHERE idactivos_fijos='$idactivos_fijos'";
		return ejecutarConsulta($sql);		
	}

}

?>