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
	public function insertar($nombre,$marca,$precio_unitario,$descripcion,$imagen1,$ficha_tecnica,$estado_igv,$monto_igv,$precio_real,$unid_medida,$color,$total_precio)
	{
		//var_dump($idproducto,$idproveedor);die();
		$sql="INSERT INTO producto (nombre,marca,precio_unitario,descripcion,imagen,ficha_tecnica,estado_igv,precio_igv,precio_sin_igv,idunidad_medida,idcolor,precio_total) 
		VALUES ('$nombre','$marca','$precio_unitario','$descripcion','$imagen1','$ficha_tecnica','$estado_igv','$monto_igv','$precio_real','$unid_medida','$color','$total_precio')";
		return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idproducto,$nombre,$marca,$precio_unitario,$descripcion,$imagen1,$ficha_tecnica,$estado_igv,$monto_igv,$precio_real,$unid_medida,$color,$total_precio)
	{
		//var_dump($idproducto,$nombre,$marca,$precio_unitario,$descripcion,$imagen1,$ficha_tecnica,$estado_igv,$monto_igv,$precio_real,$unid_medida,$total_precio);die();
		$sql="UPDATE producto SET 
		nombre='$nombre', 
		marca='$marca', 
		precio_unitario='$precio_unitario', 
		descripcion='$descripcion', 
		imagen='$imagen1',
		ficha_tecnica='$ficha_tecnica',
		estado_igv='$estado_igv',
		precio_igv='$monto_igv',
		precio_sin_igv='$precio_real',
		idunidad_medida='$unid_medida',
		idcolor='$color',
		precio_total='$total_precio'
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
		$sql="SELECT 
		p.idproducto as idproducto,
		p.idunidad_medida as idunidad_medida,
		p.idcolor as idcolor,
		p.nombre as nombre,
		p.marca as marca,
		p.descripcion as descripcion,
		p.imagen as imagen,
		p.estado_igv as estado_igv,
		p.precio_unitario as precio_unitario,
		p.precio_igv as precio_igv,
		p.precio_sin_igv as precio_sin_igv,
		p.precio_total as precio_total,
		p.ficha_tecnica as ficha_tecnica,
		p.estado as estado,
		c.nombre_color as nombre_color,
		um.nombre_medida as nombre_medida
		FROM producto p, unidad_medida as um, color as c  
		WHERE p.idproducto ='$idproducto' AND um.idunidad_medida=p.idunidad_medida AND c.idcolor=p.idcolor";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT
			p.idproducto as idproducto,
			p.idunidad_medida as idunidad_medida,
			p.idcolor as idcolor,
			p.nombre as nombre,
			p.marca as marca,
			p.descripcion as descripcion,
			p.imagen as imagen,
			p.estado_igv as estado_igv,
			p.precio_unitario as precio_unitario,
			p.precio_igv as precio_igv,
			p.precio_sin_igv as precio_sin_igv,
			p.precio_total as precio_total,
			p.ficha_tecnica as ficha_tecnica,
			p.estado as estado,
			c.nombre_color as nombre_color,
			um.nombre_medida as nombre_medida
			FROM producto p, unidad_medida as um, color as c  
			WHERE um.idunidad_medida=p.idunidad_medida  AND c.idcolor=p.idcolor
			ORDER BY idproducto DESC";
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