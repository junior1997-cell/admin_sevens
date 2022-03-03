<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Activos_fijos
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($unidad_medida, $color, $idcategoria, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion, $imagen)
  {
    // var_dump($unidad_medida, $color, $idcategoria, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion,  $imagen); die;
    $sql = "INSERT INTO producto(idunidad_medida, idcolor, idcategoria_insumos_af, nombre, modelo, serie, marca, estado_igv, precio_unitario, precio_igv, precio_sin_igv, precio_total, ficha_tecnica, descripcion, imagen) 
		VALUES ('$unidad_medida', '$color', '$idcategoria', '$nombre', '$modelo', '$serie', '$marca', '$estado_igv', '$precio_unitario', '$precio_igv', '$precio_sin_igv', '$precio_total', '$ficha_tecnica', '$descripcion', '$imagen')";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para editar registros
  public function editar($idproducto, $unidad_medida, $color, $idcategoria, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion, $img_pefil)
  {
   // var_dump($idproducto, $unidad_medida, $color, $idcategoria, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion, $img_pefil);die();
    $sql = "UPDATE producto SET 
		idunidad_medida = '$unidad_medida',
		idcolor = '$color',
		idcategoria_insumos_af = '$idcategoria',
		nombre = '$nombre',
		modelo = '$modelo',
		serie = '$serie',
		marca = '$marca',
		estado_igv = '$estado_igv',
		precio_unitario='$precio_unitario',
		precio_igv = '$precio_igv',
		precio_sin_igv = '$precio_sin_igv',
		precio_total = '$precio_total',
		ficha_tecnica = '$ficha_tecnica',
		descripcion = '$descripcion',
		imagen = '$img_pefil'
		WHERE idproducto = '$idproducto';";

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

  //Implementamos un método para eliminar
  public function eliminar($idproducto)
  {
    $sql = "UPDATE producto SET estado_delete='0' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idproducto)
  {
    $data = [];

    $sql = "SELECT p.idproducto, p.idunidad_medida, p.idcolor, p.idcategoria_insumos_af, p.nombre, p.modelo, p.serie, p.marca, p.estado_igv, 
    p.precio_unitario, p.precio_igv, p.precio_sin_igv, p.precio_total, p.ficha_tecnica, p.descripcion, p.imagen, p.estado, p.created_at,
    um.nombre_medida, c.nombre_color, ciaf.nombre AS categoria
		FROM producto AS p, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf
    WHERE p.idunidad_medida = um.idunidad_medida AND p.idcolor = c.idcolor AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af 
    AND p.idproducto = '$idproducto'";
    $activos = ejecutarConsultaSimpleFila($sql);

    $data = [
      'idproducto' => $activos['idproducto'],
      'idunidad_medida' => $activos['idunidad_medida'],
      'nombre_medida' => $activos['nombre_medida'],
      'idcolor' => $activos['idcolor'],
      'nombre_color' => $activos['nombre_color'],
      'idcategoria_insumos_af' => $activos['idcategoria_insumos_af'],
      'categoria' => $activos['categoria'],
      'nombre' => decodeCadenaHtml($activos['nombre']),
      'modelo' => decodeCadenaHtml($activos['modelo']),
      'serie' => decodeCadenaHtml($activos['serie']),
      'marca' => decodeCadenaHtml($activos['marca']),
      'estado_igv' => $activos['estado_igv'],
      'precio_unitario' => $activos['precio_unitario'],
      'precio_igv' => $activos['precio_igv'],
      'precio_sin_igv' => $activos['precio_sin_igv'],
      'precio_total' => $activos['precio_total'],
      'ficha_tecnica' => $activos['ficha_tecnica'],
      'descripcion' => decodeCadenaHtml($activos['descripcion']),
      'imagen' => $activos['imagen'],
      'estado' => $activos['estado'],
      'fecha' => $activos['created_at'],
    ];

    return $data;
  }

  //Implementar un método para listar los registros
  public function listar()
  {
    $sql = "SELECT
		p.idproducto AS idproducto,
		p.idunidad_medida AS idunidad_medida,
		p.idcolor AS idcolor,
		p.nombre AS nombre,
		p.marca AS marca,
		ciaf.nombre AS categoria,
		p.descripcion AS descripcion,
		p.imagen AS imagen,
		p.estado_igv AS estado_igv,
		p.precio_unitario AS precio_unitario,
		p.precio_igv AS precio_igv,
		p.precio_sin_igv AS precio_sin_igv,
		p.precio_total AS precio_total,
		p.ficha_tecnica AS ficha_tecnica,
		p.estado AS estado,
		c.nombre_color AS nombre_color,
		um.nombre_medida AS nombre_medida
		FROM producto p, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf
		WHERE um.idunidad_medida=p.idunidad_medida  AND c.idcolor=p.idcolor AND p.idcategoria_insumos_af != '1' AND ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af
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

  public function select2_color()
  {
    $sql = "SELECT idcolor AS id, nombre_color AS nombre FROM color WHERE estado='1' ORDER BY idcolor ASC;";
    return ejecutarConsulta($sql);
  }

  public function select2_unidad_medida()
  {
    $sql = "SELECT idunidad_medida AS id, nombre_medida AS nombre, abreviacion FROM unidad_medida WHERE estado='1' ORDER BY nombre_medida ASC;";
    return ejecutarConsulta($sql);
  }

  public function select2_categoria()
  {
    $sql = "SELECT idcategoria_insumos_af as id, nombre FROM categoria_insumos_af WHERE estado='1' AND idcategoria_insumos_af > 1 ORDER BY nombre ASC;";
    return ejecutarConsulta($sql);
  }
}

?>
