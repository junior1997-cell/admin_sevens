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
  public function insertar($idcategoria, $idgrupo, $nombre, $modelo, $serie, $marca, $precio_unitario, $descripcion, $imagen1, $ficha_tecnica, $estado_igv, $precio_igv, $precio_sin_igv, $unidad_medida, $color, $total_precio)
  {
    $sql = "SELECT p.nombre, p.modelo , p.serie, p.imagen, p.precio_igv,	p.precio_sin_igv, p.precio_total,	p.estado, c.nombre_color, 
    um.nombre_medida, p.estado, p.estado_delete, p.idtipo_tierra_concreto, ttc.nombre as tipo_tierra_concreto
		FROM producto p, unidad_medida as um, color as c, tipo_tierra_concreto as ttc
    WHERE um.idunidad_medida=p.idunidad_medida AND c.idcolor=p.idcolor AND ttc.idtipo_tierra_concreto = p.idtipo_tierra_concreto 
    AND idcategoria_insumos_af = '1' AND p.idtipo_tierra_concreto ='$idgrupo' AND p.nombre='$nombre' AND p.idcolor = '$color' AND p.idunidad_medida = '$unidad_medida';";
    $buscando = ejecutarConsultaArray($sql);
    if ($buscando['status'] == false) { return $buscando; }

    if ( empty($buscando['data']) ) {
      $sql = "INSERT INTO producto (idcategoria_insumos_af, idtipo_tierra_concreto, nombre, modelo, serie, idmarca, precio_unitario, descripcion, imagen, ficha_tecnica, estado_igv, precio_igv, precio_sin_igv,idunidad_medida,idcolor,precio_total,user_created) 
      VALUES ('$idcategoria', '$idgrupo', '$nombre', '$modelo', '$serie', '$marca','$precio_unitario','$descripcion','$imagen1','$ficha_tecnica','$estado_igv','$precio_igv','$precio_sin_igv','$unidad_medida','$color','$total_precio','" . $_SESSION['idusuario'] . "')";
     
      $intertar =  ejecutarConsulta_retornarID($sql); 
      if ($intertar['status'] == false) {  return $intertar; } 

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','".$intertar['data']."','Nuevo producto registrado','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

      return $intertar;

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
  public function editar($idproducto, $idcategoria, $idgrupo, $nombre, $modelo, $serie, $marca, $precio_unitario, $descripcion, $imagen1, $ficha_tecnica, $estado_igv, $precio_igv, $precio_sin_igv, $unidad_medida, $color, $total_precio)
  {
   
    $sql = "UPDATE producto SET 
		idcategoria_insumos_af = '$idcategoria',
    idtipo_tierra_concreto ='$idgrupo',
		nombre='$nombre', 
    modelo = '$modelo', 
    serie = '$serie',
		idmarca='$marca', 
		precio_unitario='$precio_unitario', 
		descripcion='$descripcion', 
		imagen='$imagen1',
		ficha_tecnica='$ficha_tecnica',
		estado_igv='$estado_igv',
		precio_igv='$precio_igv',
		precio_sin_igv='$precio_sin_igv',
		idunidad_medida='$unidad_medida',
		idcolor='$color',
		precio_total='$total_precio',
    user_updated= '" . $_SESSION['idusuario'] . "'

		WHERE idproducto='$idproducto'";

    $editar =  ejecutarConsulta($sql);
    if ( $editar['status'] == false) {return $editar; } 

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','$idproducto','Producto editado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idproducto)
  {
    $sql = "UPDATE producto SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idproducto ='$idproducto'";
    $desactivar= ejecutarConsulta($sql);

    if ($desactivar['status'] == false) {  return $desactivar; }
    
    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','".$idproducto."','Producto desactivado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
    
    return $desactivar;
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
    $sql = "UPDATE producto SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idproducto ='$idproducto'";
    $eliminar =  ejecutarConsulta($sql);
    if ( $eliminar['status'] == false) {return $eliminar; }  
    
    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','$idproducto','Producto Eliminado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
    
    return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idproducto)
  {
    $data = Array();

    $sql = "SELECT p.idproducto, p.idunidad_medida,	p.idcolor, p.idcategoria_insumos_af, p.nombre, p.modelo, p.serie,	p.idmarca,m.nombre_marca AS nombre_marca,
		p.descripcion, p.imagen, p.estado_igv, p.precio_unitario, p.precio_igv, p.precio_sin_igv, p.precio_total,
		p.ficha_tecnica, p.estado, c.nombre_color, um.nombre_medida, p.idtipo_tierra_concreto, ttc.nombre as tipo_tierra_concreto
		FROM producto p,marca as m, unidad_medida as um, color as c, tipo_tierra_concreto as ttc
		WHERE um.idunidad_medida=p.idunidad_medida AND p.idmarca= m.idmarca AND c.idcolor=p.idcolor AND ttc.idtipo_tierra_concreto = p.idtipo_tierra_concreto
    AND p.idproducto ='$idproducto'";

    $producto = ejecutarConsultaSimpleFila($sql);

    if ($producto['status']) {
      $data = array(
        'idproducto'      => ( empty($producto['data']['idproducto']) ? '' : $producto['data']['idproducto']),
        'idcategoria_insumos_af' => ( empty($producto['data']['idcategoria_insumos_af']) ? '' : $producto['data']['idcategoria_insumos_af']),
        'idtipo_tierra_concreto' => ( empty($producto['data']['idtipo_tierra_concreto']) ? '' : $producto['data']['idtipo_tierra_concreto']),
        'tipo_tierra_concreto' => ( empty($producto['data']['tipo_tierra_concreto']) ? '' : $producto['data']['tipo_tierra_concreto']),
        'idunidad_medida' => ( empty($producto['data']['idunidad_medida']) ? '' : $producto['data']['idunidad_medida']),
        'idcolor'         => ( empty($producto['data']['idcolor']) ? '' : $producto['data']['idcolor']),
        'nombre'          => ( empty($producto['data']['nombre']) ? '' :decodeCadenaHtml($producto['data']['nombre'])),
        'modelo'          => ( empty($producto['data']['modelo']) ? '' :decodeCadenaHtml($producto['data']['modelo'])),
        'serie'           => ( empty($producto['data']['serie']) ? '' :decodeCadenaHtml($producto['data']['serie'])),
        'marca'           => ( empty($producto['data']['idmarca']) ? '' : decodeCadenaHtml($producto['data']['idmarca'])),   
        'nombre_marca'    => decodeCadenaHtml($producto['data']['nombre_marca']),
        'estado_igv'      => ( empty($producto['data']['estado_igv']) ? '' : $producto['data']['estado_igv']),
        'precio_unitario' => ( empty($producto['data']['precio_unitario']) ? 0 : number_format($producto['data']['precio_unitario'], 2, '.',',') ),
        'precio_igv'      => ( empty($producto['data']['precio_igv']) ? 0 :  number_format($producto['data']['precio_igv'], 2, '.',',') ),
        'precio_sin_igv'  => ( empty($producto['data']['precio_sin_igv']) ? 0 :  number_format($producto['data']['precio_sin_igv'], 2, '.',',') ),
        'precio_total'    => ( empty($producto['data']['precio_total']) ? 0 :  number_format($producto['data']['precio_total'], 2, '.',',') ),
        'descripcion'     => ( empty($producto['data']['descripcion']) ? '' : decodeCadenaHtml($producto['data']['descripcion'])),
        'imagen'          => ( empty($producto['data']['imagen']) ? '' : $producto['data']['imagen']),
        'ficha_tecnica'   => ( empty($producto['data']['ficha_tecnica']) ? '' : $producto['data']['ficha_tecnica']),
        'estado'          => ( empty($producto['data']['estado']) ? '' : $producto['data']['estado']),
        'nombre_color'    => ( empty($producto['data']['nombre_color']) ? '' : $producto['data']['nombre_color']),
        'nombre_medida'   => ( empty($producto['data']['nombre_medida']) ? '' : $producto['data']['nombre_medida']),
      );
      return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
    } else {
      return $producto;
    }
  }

  //Implementar un método para listar los registros
  public function tbla_principal() {
    $sql = "SELECT p.idproducto, p.idunidad_medida, p.idcolor, p.nombre, p.marca, p.descripcion, p.imagen,
    p.estado_igv,	p.precio_unitario, p.precio_igv, p.precio_sin_igv, p.precio_total, p.ficha_tecnica,
    p.estado,	c.nombre_color,	um.nombre_medida, ttc.nombre as tipo_tierra_concreto
    FROM producto p, unidad_medida as um, color as c, tipo_tierra_concreto as ttc 
    WHERE um.idunidad_medida=p.idunidad_medida  AND c.idcolor=p.idcolor AND ttc.idtipo_tierra_concreto = p.idtipo_tierra_concreto 
    AND p.idcategoria_insumos_af = '1' AND p.estado='1' AND p.estado_delete='1' ORDER BY p.nombre ASC";
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
    return ejecutarConsultaSimpleFila($sql);
  }
}

?>
