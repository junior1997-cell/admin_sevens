<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Materiales
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$marca,$precio_unitario,$descripcion,$imagen1,$ficha_tecnica,$estado_igv,$monto_igv,$precio_real)
	{
		//var_dump($idproducto,$idproveedor);die();
		$sql="INSERT INTO producto (nombre,marca,precio_unitario,descripcion,imagen,ficha_tecnica,estado_igv,precio_igv,precio_sin_igv) 
		VALUES ('$nombre','$marca','$precio_unitario','$descripcion','$imagen1','$ficha_tecnica','$estado_igv','$monto_igv','$precio_real')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idproducto,$nombre,$marca,$precio_unitario,$descripcion,$imagen1,$ficha_tecnica,$estado_igv,$monto_igv,$precio_real)
	{
		//var_dump($idproducto,$nombre,$marca,$precio_unitario,$descripcion,$imagen1,$ficha_tecnica,$estado_igv,$monto_igv,$precio_real);die();
		$sql="UPDATE producto SET 
		nombre='$nombre', 
		marca='$marca', 
		precio_unitario='$precio_unitario', 
		descripcion='$descripcion', 
		imagen='$imagen1',
		ficha_tecnica='$ficha_tecnica',
		estado_igv='$estado_igv',
		precio_igv='$monto_igv',
		precio_sin_igv='$precio_real'
		WHERE idproducto='$idproducto'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idproducto )
	{
		$sql="UPDATE producto SET estado='0' WHERE idproducto ='$idproducto'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idproducto )
	{
		$sql="UPDATE producto SET estado='1' WHERE idproducto ='$idproducto'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproducto )
	{
		$sql="SELECT * FROM producto  WHERE idproducto ='$idproducto'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT*FROM producto  ORDER BY idproducto DESC ";
		return ejecutarConsulta($sql);		
	}
	//Seleccionar Trabajador Select2
	public function obtenerImg($idproducto)
	{
		$sql="SELECT imagen FROM producto WHERE idproducto='$idproducto'";
		return ejecutarConsulta($sql);		
	}
	//Seleccionar una ficha tecnica
	public function ficha_tec($idproducto)
	{
		$sql="SELECT ficha_tecnica FROM producto WHERE idproducto='$idproducto'";
		return ejecutarConsulta($sql);		
	}

}

?>