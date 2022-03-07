<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Hospedaje
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$fecha_inicio,$fecha_fin,$cantidad,$unidad,$precio_unitario,$precio_parcial,$descripcion,$forma_pago,$tipo_comprobante,$fecha_comprobante,$nro_comprobante,$subtotal,$igv,$comprobante,$ruc,$razon_social,$direccion)
	{
	
		$sql="INSERT INTO hospedaje (idproyecto,fecha_inicio,fecha_fin,cantidad,unidad,precio_unitario,precio_parcial,descripcion,forma_de_pago,tipo_comprobante,fecha_comprobante,numero_comprobante,subtotal,igv,comprobante,ruc,razon_social,direccion) 
		VALUES ('$idproyecto','$fecha_inicio','$fecha_fin','$cantidad','$unidad','$precio_unitario','$precio_parcial','$descripcion','$forma_pago','$tipo_comprobante','$fecha_comprobante','$nro_comprobante','$subtotal','$igv','$comprobante','$ruc','$razon_social','$direccion')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idhospedaje,$idproyecto,$fecha_inicio,$fecha_fin,$cantidad,$unidad,$precio_unitario,$precio_parcial,$descripcion,$forma_pago,$tipo_comprobante,$fecha_comprobante,$nro_comprobante,$subtotal,$igv,$comprobante,$ruc,$razon_social,$direccion)
	{
		$sql="UPDATE hospedaje SET 
		idproyecto='$idproyecto',
		fecha_inicio='$fecha_inicio',
		fecha_fin='$fecha_fin',
		cantidad='$cantidad',
		unidad='$unidad',
		precio_unitario='$precio_unitario',
		precio_parcial='$precio_parcial',
		descripcion='$descripcion',
		forma_de_pago='$forma_pago',
		tipo_comprobante='$tipo_comprobante',
		fecha_comprobante='$fecha_comprobante',
		numero_comprobante='$nro_comprobante',
		subtotal='$subtotal',
		igv='$igv',
		comprobante='$comprobante',
		ruc='$ruc',
		razon_social='$razon_social',
		direccion='$direccion'

		WHERE idhospedaje='$idhospedaje'";	
		return ejecutarConsulta($sql);	
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idhospedaje )
	{
		$sql="UPDATE hospedaje SET estado='0' WHERE idhospedaje ='$idhospedaje'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idhospedaje )
	{
		$sql="UPDATE hospedaje SET estado='1' WHERE idhospedaje ='$idhospedaje'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function eliminar($idhospedaje )
	{
		$sql="UPDATE hospedaje SET estado_delete='0' WHERE idhospedaje ='$idhospedaje'";
		return ejecutarConsulta($sql);
	}
	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idhospedaje )
	{
		$sql="SELECT*FROM hospedaje   
		WHERE idhospedaje ='$idhospedaje'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($idproyecto)
	{
		$sql="SELECT*FROM hospedaje WHERE idproyecto='$idproyecto' AND estado_delete='1' AND estado='1' ORDER BY fecha_comprobante DESC";
		return ejecutarConsulta($sql);		
	}

	//Seleccionar un comprobante
	public function ficha_tec($idhospedaje)
	{
		$sql="SELECT comprobante FROM hospedaje WHERE idhospedaje='$idhospedaje'";
		return ejecutarConsulta($sql);		
	}
	//total
	public function total($idproyecto){
		$sql="SELECT SUM(precio_parcial) as precio_parcial FROM hospedaje WHERE idproyecto='$idproyecto' AND estado='1' AND estado_delete='1' ";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>