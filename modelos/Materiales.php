<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Materiales
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($idcategoria, $nombre, $modelo, $serie, $marca, $precio_unitario, $descripcion, $imagen1, $ficha_tecnica, $estado_igv, $monto_igv, $precio_real, $unid_medida, $color, $total_precio)
  {
    $sql = "SELECT p.nombre, p.modelo , p.serie, p.marca, p.imagen, p.precio_igv,	p.precio_sin_igv, p.precio_total,	p.estado, c.nombre_color, 
    um.nombre_medida, p.estado, p.estado_delete
		FROM producto p, unidad_medida as um, color as c 
    WHERE um.idunidad_medida=p.idunidad_medida AND c.idcolor=p.idcolor AND idcategoria_insumos_af = '1' AND p.nombre='$nombre' AND p.idcolor = '$color' AND p.idunidad_medida = '$unid_medida';";
    $buscando = ejecutarConsultaArray($sql);
    if ($buscando['status'] == false) { return $buscando; }

    if ( empty($buscando['data']) ) {
      $sql = "INSERT INTO producto (idcategoria_insumos_af, nombre, modelo, serie, marca, precio_unitario, descripcion, imagen, ficha_tecnica, estado_igv, precio_igv, precio_sin_igv,idunidad_medida,idcolor,precio_total) 
      VALUES ('$idcategoria','$nombre', '$modelo', '$serie', '$marca','$precio_unitario','$descripcion','$imagen1','$ficha_tecnica','$estado_igv','$monto_igv','$precio_real','$unid_medida','$color','$total_precio')";
      return ejecutarConsulta($sql);
    } else {
      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>'.$value['nombre'].'<br>
          <b>Color: </b>'.$value['nombre_color'].'<br>
          <b>UM: </b>'.$value['nombre_medida'].'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .'<br>
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
      return $sw;
    }      
    
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
		WHERE um.idunidad_medida=p.idunidad_medida AND c.idcolor=p.idcolor AND p.idproducto ='$idproducto'";

    $producto = ejecutarConsultaSimpleFila($sql);

    if ($producto['status']) {
      $data = array(
        'idproducto'      => ($retVal_1 = empty($producto['data']['idproducto']) ? '' : $producto['data']['idproducto']),
        'idunidad_medida' => ($retVal_2 = empty($producto['data']['idunidad_medida']) ? '' : $producto['data']['idunidad_medida']),
        'idcolor'         => ($retVal_3 = empty($producto['data']['idcolor']) ? '' : $producto['data']['idcolor']),
        'nombre'          => ($retVal_4 = empty($producto['data']['nombre']) ? '' :decodeCadenaHtml($producto['data']['nombre'])),
        'modelo'          => ($retVal_4 = empty($producto['data']['modelo']) ? '' :decodeCadenaHtml($producto['data']['modelo'])),
        'serie'           => ($retVal_4 = empty($producto['data']['serie']) ? '' :decodeCadenaHtml($producto['data']['serie'])),
        'marca'           => ($retVal_5 = empty($producto['data']['marca']) ? '' : decodeCadenaHtml($producto['data']['marca'])),
        'descripcion'     => ($retVal_6 = empty($producto['data']['descripcion']) ? '' : decodeCadenaHtml($producto['data']['descripcion'])),
        'imagen'          => ($retVal_7 = empty($producto['data']['imagen']) ? '' : $producto['data']['imagen']),
        'estado_igv'      => ($retVal_8 = empty($producto['data']['estado_igv']) ? '' : $producto['data']['estado_igv']),
        'precio_unitario' => ($retVal_9 = empty($producto['data']['precio_unitario']) ? 0 : number_format($producto['data']['precio_unitario'], 2, '.',',') ),
        'precio_igv'      => ($retVal_10 = empty($producto['data']['precio_igv']) ? 0 :  number_format($producto['data']['precio_igv'], 2, '.',',') ),
        'precio_sin_igv'  => ($retVal_11 = empty($producto['data']['precio_sin_igv']) ? 0 :  number_format($producto['data']['precio_sin_igv'], 2, '.',',') ),
        'precio_total'    => ($retVal_12 = empty($producto['data']['precio_total']) ? 0 :  number_format($producto['data']['precio_total'], 2, '.',',') ),
        'ficha_tecnica'   => ($retVal_13 = empty($producto['data']['ficha_tecnica']) ? '' : $producto['data']['ficha_tecnica']),
        'estado'          => ($retVal_14 = empty($producto['data']['estado']) ? '' : $producto['data']['estado']),
        'nombre_color'    => ($retVal_15 = empty($producto['data']['nombre_color']) ? '' : $producto['data']['nombre_color']),
        'nombre_medida'   => ($retVal_16 = empty($producto['data']['nombre_medida']) ? '' : $producto['data']['nombre_medida']),
      );
      return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
    } else {
      return $producto;
    }
  }

  //Implementar un método para listar los registros
  public function tbla_principal() {
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
    return ejecutarConsultaSimpleFila($sql);
  }
  
  //Seleccionar una ficha tecnica
  public function ficha_tec($idproducto)
  {
    $sql = "SELECT ficha_tecnica FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }
}

?>
