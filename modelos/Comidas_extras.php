<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Comidas_extras
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	//$idcomida_extra,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
	//Implementamos un método para insertar registros
	public function insertar($idproyecto,$fecha,$precio_parcial,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$comprobante,$ruc,$razon_social,$direccion)
	{
	
		$sql="INSERT INTO comida_extra (idproyecto,fecha_comida,costo_parcial,descripcion,forma_de_pago,tipo_comprobante,numero_comprobante,subtotal,igv,comprobante,ruc,razon_social,direccion) 
		VALUES ('$idproyecto','$fecha','$precio_parcial','$descripcion','$forma_pago','$tipo_comprobante','$nro_comprobante','$subtotal','$igv','$comprobante','$ruc','$razon_social','$direccion')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idcomida_extra,$idproyecto,$fecha,$precio_parcial,$descripcion,$forma_pago,$tipo_comprobante,$nro_comprobante,$subtotal,$igv,$comprobante,$ruc,$razon_social,$direccion)
	{
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
	public function listar($idproyecto)
	{
		$sql="SELECT*FROM comida_extra WHERE idproyecto='$idproyecto' AND estado_delete='1' AND  estado='1' ORDER BY idcomida_extra DESC";
		return ejecutarConsulta($sql);		
	}

	//Seleccionar un comprobante
	public function ficha_tec($idcomida_extra)
	{
		$sql="SELECT comprobante FROM comida_extra WHERE idcomida_extra='$idcomida_extra'";
		return ejecutarConsulta($sql);		
	}
	//total
	public function total($idproyecto){
		$sql="SELECT SUM(costo_parcial) as precio_parcial FROM comida_extra WHERE idproyecto='$idproyecto' AND estado='1' AND estado_delete='1'";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>