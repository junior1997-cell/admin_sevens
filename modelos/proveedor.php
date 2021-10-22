<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Proveedor
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$idproveedor)
	{
		//var_dump($idproyecto,$idproveedor);die();
		$sql="INSERT INTO proveedor_por_proyecto (idproyecto,idproveedor) VALUES ('$idproyecto','$idproveedor')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idproveedor_proyecto,$idproveedor)
	{
		///var_dump($idproveedor_proyecto,$idproveedor);die();
		$sql="UPDATE proveedor_por_proyecto SET idproveedor='$idproveedor' WHERE idproveedor_proyecto='$idproveedor_proyecto'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idproveedor_proyecto)
	{
		$sql="UPDATE proveedor_por_proyecto SET estado='0' WHERE idproveedor_proyecto='$idproveedor_proyecto'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idproveedor_proyecto)
	{
		$sql="UPDATE proveedor_por_proyecto SET estado='1' WHERE idproveedor_proyecto='$idproveedor_proyecto'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproveedor_proyecto)
	{
		$sql="SELECT * FROM proveedor_por_proyecto  WHERE idproveedor_proyecto='$idproveedor_proyecto'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($nube_idproyecto)
	{
		$sql="SELECT 
		ppp.idproveedor_proyecto as idproveedor_proyecto, 
		ppp.idproveedor as idproveedor, 
		ppp.idproyecto as idproyecto,
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
		FROM proveedor as p, proveedor_por_proyecto as ppp, bancos as b
        WHERE ppp.idproyecto='$nube_idproyecto' AND ppp.idproveedor=p.idproveedor AND p.idbancos=b.idbancos";
		return ejecutarConsulta($sql);		
	}
	//Ver datos
	public function ver_datos($idproveedor_proyecto){
		//var_dump($idproveedor_proyecto,$idproyecto); die();
		$sql="SELECT 
		ppp.idproveedor_proyecto as idproveedor_proyecto, 
		ppp.idproveedor as idproveedor, 
		ppp.idproyecto as idproyecto,
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
		FROM proveedor as p, proveedor_por_proyecto as ppp, bancos as b
        WHERE ppp.idproveedor=p.idproveedor AND p.idbancos=b.idbancos AND ppp.idproveedor_proyecto='$idproveedor_proyecto'";
		return ejecutarConsultaSimpleFila($sql);	
	}

	//Seleccionar Trabajador Select2
	public function select2_proveedor()
	{
		$sql="SELECT idproveedor,razon_social,ruc FROM proveedor WHERE estado='1'";
		return ejecutarConsulta($sql);		
	}

}

?>