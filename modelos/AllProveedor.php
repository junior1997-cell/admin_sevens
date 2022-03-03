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
	public function insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $c_bancaria, $cci, $c_detracciones, $banco, $titular_cuenta)
	{
		$sql="INSERT INTO proveedor (idbancos, razon_social, tipo_documento, ruc, direccion, telefono, cuenta_bancaria, cci, cuenta_detracciones, titular_cuenta)
		VALUES ('$banco', '$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$c_bancaria', '$cci', '$c_detracciones', '$titular_cuenta')";
		
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idproveedor, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $c_bancaria, $cci, $c_detracciones, $banco, $titular_cuenta)
	{
		//var_dump($idproveedor,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$c_bancaria,$c_detracciones,$banco,$titular_cuenta);die;
		
		$sql="UPDATE proveedor SET idbancos='$banco',
		razon_social='$nombre',
		tipo_documento='$tipo_documento', 
		ruc='$num_documento',
		direccion='$direccion',
		telefono='$telefono',
		cuenta_bancaria='$c_bancaria', cci='$cci', 
		cuenta_detracciones='$c_detracciones',
		titular_cuenta='$titular_cuenta' 
		WHERE idproveedor='$idproveedor'";	
		
		return ejecutarConsulta($sql);
		
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idproveedor)
	{
		$sql="UPDATE proveedor SET estado='0' WHERE idproveedor='$idproveedor'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idproveedor)
	{
		$sql="UPDATE proveedor SET estado='1' WHERE idproveedor='$idproveedor'";
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para eliminar
	public function eliminar($idproveedor)
	{
		$sql="UPDATE proveedor SET estado_delete='0' WHERE idproveedor='$idproveedor'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproveedor)
	{
		$sql="SELECT * FROM proveedor WHERE idproveedor='$idproveedor'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM proveedor WHERE idproveedor>1 AND estado=1 AND estado_delete=1 ORDER BY  razon_social ASC";
		return ejecutarConsulta($sql);		
	}
	public function listar_compra()
	{
		$sql="SELECT * FROM proveedor where estado=1 AND estado_delete=1";
		return ejecutarConsulta($sql);		
	}

	public function select2_banco() {
		$sql="SELECT idbancos as id, nombre, alias FROM bancos WHERE estado='1' AND estado_delete=1  ORDER BY idbancos ASC;";
		return ejecutarConsulta($sql);		
	}

	// optenesmo el formato para los bancos
	public function formato_banco($idbanco){
		$sql="SELECT nombre, formato_cta, formato_cci, formato_detracciones FROM bancos WHERE estado='1' AND idbancos = '$idbanco';";
		return ejecutarConsultaSimpleFila($sql);		
	}

}

?>