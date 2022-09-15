<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Activos_fijos
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($unidad_medida, $color, $idcategoria, $idgrupo, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion, $imagen)
  {
    $sql = "SELECT p.nombre, p.modelo , p.serie, p.imagen, p.precio_igv,	p.precio_sin_igv, p.precio_total,	p.estado, c.nombre_color, 
    um.nombre_medida, p.estado, p.estado_delete, ciaf.nombre as nombre_categoria
		FROM producto p, unidad_medida as um, color as c, categoria_insumos_af as ciaf 
    WHERE um.idunidad_medida=p.idunidad_medida AND c.idcolor=p.idcolor AND ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af AND p.idcategoria_insumos_af = '$idcategoria' AND p.nombre='$nombre' AND p.idcolor = '$color' AND p.idunidad_medida = '$unidad_medida';";
    $buscando = ejecutarConsultaArray($sql);

    if ($buscando['status']) {
      if ( empty($buscando['data']) ) {
        $sql = "INSERT INTO producto(idunidad_medida, idcolor, idcategoria_insumos_af, idtipo_tierra_concreto, nombre, modelo, serie, idmarca, estado_igv, precio_unitario, precio_igv, precio_sin_igv, precio_total, ficha_tecnica, descripcion, imagen, user_created) 
        VALUES ('$unidad_medida', '$color', '$idcategoria', '$idgrupo', '$nombre', '$modelo', '$serie', '$marca', '$estado_igv', '$precio_unitario', '$precio_igv', '$precio_sin_igv', '$precio_total', '$ficha_tecnica', '$descripcion', '$imagen','" . $_SESSION['idusuario'] . "')";

        $intertar =  ejecutarConsulta_retornarID($sql); 
        if ($intertar['status'] == false) {  return $intertar; } 

        //add registro en nuestra bitacora
        $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','".$intertar['data']."','Nuevo activo fijo registrado','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

        return $intertar;

      } else {
        $info_repetida = ''; 

        foreach ($buscando['data'] as $key => $value) {
          $info_repetida .= '<li class="text-left font-size-13px">
            <b>Nombre: </b>'.$value['nombre'].'<br>
            <b>Clasificación: </b>'.$value['nombre_categoria'].'<br>
            <b>Color: </b>'.$value['nombre_color'].'<br>
            <b>UM: </b>'.$value['nombre_medida'].'<br>
            <b>Precio con IGV: </b>'.number_format( $value['precio_total'], 2, '.', ',' ).'<br>
            <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .'<br>
            <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
            <hr class="m-t-2px m-b-2px">
          </li>'; 
        }
        $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
        return $sw;
      }      
    } else {
      return $buscando;
    }
  }

  //Implementamos un método para editar registros
  public function editar($idproducto, $unidad_medida, $color, $idcategoria, $idgrupo, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion, $img_pefil)
  {
  //var_dump($idproducto, $unidad_medida, $color, $idcategoria,'idhrupo_'.$idgrupo, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion, $img_pefil);die();
    $sql = "UPDATE producto SET 
		idunidad_medida = '$unidad_medida',
		idcolor = '$color',
		idcategoria_insumos_af = '$idcategoria',
    idtipo_tierra_concreto ='$idgrupo',
		nombre = '$nombre',
		modelo = '$modelo',
		serie = '$serie',
		idmarca = '$marca',
		estado_igv = '$estado_igv',
		precio_unitario='$precio_unitario',
		precio_igv = '$precio_igv',
		precio_sin_igv = '$precio_sin_igv',
		precio_total = '$precio_total',
		ficha_tecnica = '$ficha_tecnica',
		descripcion = '$descripcion',
		imagen = '$img_pefil',
    user_updated= '" . $_SESSION['idusuario'] . "'
		WHERE idproducto = '$idproducto';";

    $editar =  ejecutarConsulta($sql);
    if ( $editar['status'] == false) {return $editar; } 

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','$idproducto','Activo fijo editado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idproducto) {
  
    $sql = "UPDATE producto SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "'  WHERE idproducto ='$idproducto'";
    $desactivar= ejecutarConsulta($sql);

    if ($desactivar['status'] == false) {  return $desactivar; }
    
    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','$idproducto','Activo fijo desactivado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
    
    return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($idproducto)  {
    $sql = "UPDATE producto SET estado='1' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para eliminar
  public function eliminar($idproducto)  {
    $sql = "UPDATE producto SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idproducto ='$idproducto'";
    $eliminar =  ejecutarConsulta($sql);
    if ( $eliminar['status'] == false) {return $eliminar; }  
    
    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','$idproducto','Activo fijo Eliminado','" . $_SESSION['idusuario'] . "')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
    
    return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idproducto) {
    $data = [];

    $sql = "SELECT p.idproducto, p.idunidad_medida, p.idcolor, p.idcategoria_insumos_af, p.nombre, p.modelo, p.serie, p.idmarca,m.nombre_marca AS nombre_marca, p.estado_igv, 
    p.precio_unitario, p.precio_igv, p.precio_sin_igv, p.precio_total, p.ficha_tecnica, p.descripcion, p.imagen, p.estado, p.created_at,
    um.nombre_medida, c.nombre_color, ciaf.nombre AS categoria, p.idtipo_tierra_concreto, ttc.nombre as tipo_tierra_concreto
		FROM producto AS p,marca as m, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf, tipo_tierra_concreto as ttc
    WHERE p.idunidad_medida = um.idunidad_medida AND p.idmarca= m.idmarca AND p.idcolor = c.idcolor AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af 
    AND ttc.idtipo_tierra_concreto = p.idtipo_tierra_concreto AND p.idproducto = '$idproducto'";
    $activos = ejecutarConsultaSimpleFila($sql);

    if ($activos['status']) {
      $data = [
        'idproducto'      => $activos['data']['idproducto'],
        'idunidad_medida' => $activos['data']['idunidad_medida'],
        'nombre_medida'   => $activos['data']['nombre_medida'],
        'idcolor'         => $activos['data']['idcolor'],
        'nombre_color'    => $activos['data']['nombre_color'],
        'idcategoria_insumos_af'  => $activos['data']['idcategoria_insumos_af'],
        'categoria'               => $activos['data']['categoria'],
        'idtipo_tierra_concreto'  => ( empty($activos['data']['idtipo_tierra_concreto']) ? '' : $activos['data']['idtipo_tierra_concreto']),
        'tipo_tierra_concreto'    => ( empty($activos['data']['tipo_tierra_concreto']) ? '' : $activos['data']['tipo_tierra_concreto']),
        'nombre'          => decodeCadenaHtml($activos['data']['nombre']),
        'modelo'          => decodeCadenaHtml($activos['data']['modelo']),
        'serie'           => decodeCadenaHtml($activos['data']['serie']),
        'marca'           => decodeCadenaHtml($activos['data']['idmarca']),
        'nombre_marca'    => decodeCadenaHtml($activos['data']['nombre_marca']),
        'estado_igv'      => (empty($activos['data']['estado_igv']) ? 0 :  $activos['data']['estado_igv']),
        'precio_unitario' => (empty($activos['data']['precio_unitario']) ? 0 : $activos['data']['precio_unitario']),
        'precio_igv'      => (empty($activos['data']['precio_igv']) ? 0 : $activos['data']['precio_igv']),
        'precio_sin_igv'  => (empty($activos['data']['precio_sin_igv']) ? 0 : $activos['data']['precio_sin_igv']),
        'precio_total'    => (empty($activos['data']['precio_total']) ? 0 : $activos['data']['precio_total']),
        'ficha_tecnica'   => $activos['data']['ficha_tecnica'],
        'descripcion'     => decodeCadenaHtml($activos['data']['descripcion']),
        'imagen'          => $activos['data']['imagen'],
        'estado'          => $activos['data']['estado'],
        'fecha'           => $activos['data']['created_at'],
      ];
  
      return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
    } else {
      return $activos;
    }
  }

  //Implementar un método para listar los registros
  public function tabla_principal( $id_categoria ) {

    $tipo_categoria = '';

    if ($id_categoria == 'todos') {
      $tipo_categoria = "AND p.idcategoria_insumos_af != '1'";
    } else{
      $tipo_categoria = "AND p.idcategoria_insumos_af = '$id_categoria'";
    }
    
    $sql = "
    SELECT
		p.idproducto AS idproducto,
		p.idunidad_medida AS idunidad_medida,
		p.idcolor AS idcolor,
    p.nombre AS nombre,
		m.nombre_marca AS marca,
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
		FROM producto p,marca as m, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf
		WHERE um.idunidad_medida=p.idunidad_medida AND p.idmarca= m.idmarca AND c.idcolor=p.idcolor AND ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af 
    $tipo_categoria AND p.estado='1' AND p.estado_delete='1' ORDER BY p.nombre ASC;";
    return ejecutarConsulta($sql);
  }

  //Seleccionar Trabajador Select2
  public function lista_de_categorias() {
    $sql = "SELECT idcategoria_insumos_af as idcategoria , nombre 
    FROM categoria_insumos_af WHERE estado='1' AND estado_delete='1' AND idcategoria_insumos_af != '1' ";
    return ejecutarConsultaArray($sql);
  }

  //Seleccionar Trabajador Select2
  public function obtenerImg($idproducto) {
    $sql = "SELECT imagen FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Seleccionar una ficha tecnica
  public function ficha_tec($idproducto)  {
    $sql = "SELECT ficha_tecnica FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }

}

?>
