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
	public function insertar($unid_medida, $color, $idcategoria, $nombre, $modelo, $serie, $marca, $precio_unitario, $subtotal, $precio_igv, $total, $descripcion, $imagen1, $ficha_tecnica, $estado_igv)
	{

		$sql = "INSERT INTO producto(idunidad_medida, idcolor, idcategoria_insumos_af, nombre, modelo, serie, marca, estado_igv, precio_unitario, precio_igv, precio_sin_igv, precio_total, ficha_tecnica, descripcion, imagen) 
		VALUES ('$unid_medida', '$color', '$marca', '$idcategoria', '$nombre', '$modelo', '$serie', '$estado_igv', '$precio_unitario', '$subtotal', '$precio_igv', '$color', '$total')";
    	return ejecutarConsulta($sql);
			
	}

	//Implementamos un método para editar registros
	public function editar($idproducto, $idcategoria, $color, $unid_medida, $nombre, $modelo, $serie, $marca, $precio_compra, $subtotal, $igv, $total, $descripcion, $img_pefil, $ficha_tecnica, $estado_igv)
	{ 

		$sql = "UPDATE producto SET 
		idunidad_medida = '$unid_medida',
		idcolor = '$color',
		idcategoria_insumos_af = '$idcategoria',
		nombre = '$nombre',
		modelo = '$modelo',
		serie = '$serie',
		marca = '$marca',
		estado_igv = '$estado_igv',
		precio_unitario='$precio_compra',
		precio_igv = '$igv',
		precio_sin_igv = '$subtotal',
		precio_total = '$total',
		ficha_tecnica = '$ficha_tecnica',
		descripcion = '$descripcion',
		imagen = '$img_pefil'
		WHERE idproducto = '$idproducto'; ";
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
		WHERE um.idunidad_medida=p.idunidad_medida  AND c.idcolor=p.idcolor AND idcategoria_insumos_af != '1' 
		ORDER BY idproducto DESC";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros
	public function lista_activos_para_compras()
	{
		$sql="SELECT p.idproducto,p.idcategoria_insumos_af, p.nombre, p.modelo, p.serie, p.marca,p.precio_unitario, p.precio_igv as igv, 
		p.precio_sin_igv, p.precio_total as precio_con_igv, p.ficha_tecnica, p.descripcion, p.imagen, um.nombre_medida, c.nombre_color
		FROM producto as p, unidad_medida as um, color as c
		WHERE p.idcategoria_insumos_af!='1' AND p.estado=1 AND p.idunidad_medida= um.idunidad_medida AND p.idcolor=c.idcolor 
		ORDER BY p.idproducto ASC";
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