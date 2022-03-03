<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Materiales
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($idcategoria, $nombre, $modelo, $serie, $marca, $precio_unitario, $descripcion, $imagen1, $ficha_tecnica, $estado_igv, $monto_igv, $precio_real, $unid_medida, $color, $total_precio)
  {
    //var_dump($idproducto,$idproveedor);die();
    $sql = "INSERT INTO producto (idcategoria_insumos_af, nombre, modelo, serie, marca, precio_unitario, descripcion, imagen, ficha_tecnica, estado_igv, precio_igv, precio_sin_igv,idunidad_medida,idcolor,precio_total) 
		VALUES ('$idcategoria','$nombre', '$modelo', '$serie', '$marca','$precio_unitario','$descripcion','$imagen1','$ficha_tecnica','$estado_igv','$monto_igv','$precio_real','$unid_medida','$color','$total_precio')";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar($idproducto, $idcategoria, $nombre, $modelo, $serie, $marca, $precio_unitario, $descripcion, $imagen1, $ficha_tecnica, $estado_igv, $monto_igv, $precio_real, $unid_medida, $color, $total_precio)
  {
    //var_dump($idproducto,$nombre,$marca,$precio_unitario,$descripcion,$imagen1,$ficha_tecnica,$estado_igv,$monto_igv,$precio_real,$unid_medida,$total_precio);die();
    $sql = "UPDATE producto SET 
		idcategoria_insumos_af = '$idcategoria',
		nombre='$nombre', 
    modelo = '$modelo', 
    serie = '$serie',
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
  public function desactivar($idproducto)
  {
    $sql = "UPDATE producto SET estado='0' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function activar($idproducto)
  {
    $sql = "UPDATE producto SET estado='1' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar($idproducto)
  {
    $sql = "UPDATE producto SET estado_delete='0' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idproducto)
  {
    $data = Array();

    $sql = "SELECT 
		p.idproducto as idproducto,
		p.idunidad_medida as idunidad_medida,
		p.idcolor as idcolor,
		p.nombre as nombre,
    p.modelo as modelo,
    p.serie as serie,
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

    $producto = ejecutarConsultaSimpleFila($sql);

    $data = array(
      'idproducto'  => ($retVal_1 = empty($producto['idproducto']) ? '' : $producto['idproducto']),
      'idunidad_medida' => ($retVal_2 = empty($producto['idunidad_medida']) ? '' : $producto['idunidad_medida']),
      'idcolor'     => ($retVal_3 = empty($producto['idcolor']) ? '' : $producto['idcolor']),
      'nombre'      => ($retVal_4 = empty($producto['nombre']) ? '' :decodeCadenaHtml($producto['nombre'])),
      'modelo'      => ($retVal_4 = empty($producto['modelo']) ? '' :decodeCadenaHtml($producto['modelo'])),
      'serie'      => ($retVal_4 = empty($producto['serie']) ? '' :decodeCadenaHtml($producto['serie'])),
      'marca'       => ($retVal_5 = empty($producto['marca']) ? '' : decodeCadenaHtml($producto['marca'])),
      'descripcion' => ($retVal_6 = empty($producto['descripcion']) ? '' : decodeCadenaHtml($producto['descripcion'])),
      'imagen'      => ($retVal_7 = empty($producto['imagen']) ? '' : $producto['imagen']),
      'estado_igv'  => ($retVal_8 = empty($producto['estado_igv']) ? '' : $producto['estado_igv']),
      'precio_unitario' => ($retVal_9 = empty($producto['precio_unitario']) ? '' : $producto['precio_unitario']),
      'precio_igv'  => ($retVal_10 = empty($producto['precio_igv']) ? '' : $producto['precio_igv']),
      'precio_sin_igv'=> ($retVal_11 = empty($producto['precio_sin_igv']) ? '' : $producto['precio_sin_igv']),
      'precio_total' => ($retVal_12 = empty($producto['precio_total']) ? '' : $producto['precio_total']),
      'ficha_tecnica'=> ($retVal_13 = empty($producto['ficha_tecnica']) ? '' : $producto['ficha_tecnica']),
      'estado'      => ($retVal_14 = empty($producto['estado']) ? '' : $producto['estado']),
      'nombre_color'=> ($retVal_15 = empty($producto['nombre_color']) ? '' : $producto['nombre_color']),
      'nombre_medida'=> ($retVal_16 = empty($producto['nombre_medida']) ? '' : $producto['nombre_medida']),
    );
    return $data;
  }

  //Implementar un método para listar los registros
  public function listar() {
    $sql = "SELECT
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
			WHERE um.idunidad_medida=p.idunidad_medida  AND c.idcolor=p.idcolor AND idcategoria_insumos_af = '1' 
			AND p.estado='1' AND p.estado_delete='1' ORDER BY p.nombre ASC";
    return ejecutarConsulta($sql);
  }
  
  //Seleccionar Trabajador Select2
  public function obtenerImg($idproducto)
  {
    $sql = "SELECT imagen FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }
  
  //Seleccionar una ficha tecnica
  public function ficha_tec($idproducto)
  {
    $sql = "SELECT ficha_tecnica FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }
}

?>
