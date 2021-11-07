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
	public function insertar($nombre,$descripcion,$foto1)
	{
		//var_dump($idproducto,$idproveedor);die();
		$sql="INSERT INTO producto (nombre,descripcion,imagen) VALUES ('$nombre','$descripcion','$foto1')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idproducto,$nombre,$descripcion,$foto1)
	{
		//var_dump($idproducto,$nombre,$descripcion,$foto1);die();
		$sql="UPDATE producto SET nombre='$nombre', descripcion='$descripcion', imagen='$foto1' WHERE idproducto='$idproducto'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idproducto )
	{
		$sql="UPDATE producto SET estado='0' WHERE idproducto ='$idproducto '";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idproducto )
	{
		$sql="UPDATE producto SET estado='1' WHERE idproducto ='$idproducto '";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproducto )
	{
		$sql="SELECT * FROM producto  WHERE idproducto ='$idproducto '";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT*FROM producto";
		return ejecutarConsulta($sql);		
	}
	//Ver datos
	public function ver_datos($idproducto ){
		//var_dump($idproducto ,$idproducto); die();
		$sql="SELECT 
		ppp.idproducto  as idproducto , 
		ppp.idproveedor as idproveedor, 
		ppp.idproducto as idproducto,
		ppp.estado as estado, 
		p.idproveedor as idproveedor, 
		p.razon_social as razon_social, 
		p.ruc as ruc, p.idbancos as idbancos,
		b.nombre as nombre_banco, 
		p.tipo_documento as tipo_documento, 
		p.direccion as direccion, 
		p.telefono as telefono, 
		p.cuenta_bancaria as cuenta_bancaria, 
		p.cuenta_detracciones as cuenta_detracciones, 
		p.titular_cuenta as titular_cuenta
		FROM proveedor as p, producto as ppp, bancos as b
        WHERE ppp.idproveedor=p.idproveedor AND p.idbancos=b.idbancos AND ppp.idproducto ='$idproducto '";
		return ejecutarConsultaSimpleFila($sql);	
	}

	//Seleccionar Trabajador Select2
	public function obtenerImg($idproducto)
	{
		$sql="SELECT imagen FROM producto WHERE idproducto='$idproducto'";
		return ejecutarConsulta($sql);		
	}

}

?>