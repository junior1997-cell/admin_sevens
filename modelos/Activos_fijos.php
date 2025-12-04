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
  public function insertar($nombre, $idcategoria, $unidad_medida, $marca, $descripcion, $color, $modelo, $serie, $estado_igv, $precio_unitario, $precio_sin_igv, $precio_igv, $precio_total, $ficha_tecnica, $imagen1)
  {
    $sql = "SELECT p.nombre, p.imagen, p.estado, p.estado_delete, um.nombre_medida, ciaf.nombre as nombre_categoria
		FROM producto p, unidad_medida as um, categoria_insumos_af as ciaf 
    WHERE um.idunidad_medida=p.idunidad_medida  AND ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af AND p.idcategoria_insumos_af = '$idcategoria' AND p.nombre='$nombre' AND p.idcolor = '$color' AND p.idunidad_medida = '$unidad_medida';";
    $buscando = ejecutarConsultaArray($sql); if ( $buscando['status'] == false) {return $buscando; } 

    if ($buscando['status']) {
      if ( empty($buscando['data']) ) {
        $sql = "INSERT INTO producto(nombre, idcategoria_insumos_af, idunidad_medida, descripcion, idcolor, modelo, serie,  estado_igv, precio_unitario, precio_sin_igv, precio_igv, precio_total, ficha_tecnica,  imagen, user_created) 
        VALUES ('$nombre', '$idcategoria', '$unidad_medida', '$descripcion', '$color', '$modelo', '$serie',  '$estado_igv', '$precio_unitario', '$precio_sin_igv', '$precio_igv', '$precio_total', '$ficha_tecnica', '$imagen1', '" . $_SESSION['idusuario'] . "')";
        $insertar =  ejecutarConsulta_retornarID($sql); if ($insertar['status'] == false) {  return $insertar; } 

        //add registro en nuestra bitacora
        $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','".$insertar['data']."','Nuevo activo fijo registrado','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 
        
        foreach ($marca as $key => $value) {
          //detalle de marcas
          $sql = "INSERT INTO detalle_marca (idproducto, idmarca, user_created) VALUES ('". $insertar['data'] ."', '$value','" . $_SESSION['idusuario'] . "')";
          $marcas = ejecutarConsulta($sql); if ( $marcas['status'] == false) {return $marcas; } 
        }
        return $insertar;

      } else {
        $info_repetida = ''; 

        foreach ($buscando['data'] as $key => $value) {
          $info_repetida .= '<li class="text-left font-size-13px">
            <b>Nombre: </b>'.$value['nombre'].'<br>
            <b>Clasificación: </b>'.$value['nombre_categoria'].'<br>
            <b>UM: </b>'.$value['nombre_medida'].'<br>
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
  public function editar($idproducto, $nombre, $idcategoria, $unidad_medida, $marca, $descripcion, $color, $modelo, $serie, $estado_igv, $precio_unitario, $precio_sin_igv, $precio_igv, $precio_total, $ficha_tecnica, $imagen1)
  {
    //var_dump($idproducto, $unidad_medida, $color, $idcategoria,'idhrupo_'.$idgrupo, $nombre, $modelo, $serie, $marca, $estado_igv, $precio_unitario, $precio_igv, $precio_sin_igv, $precio_total, $ficha_tecnica, $descripcion, $img_pefil);die();
    $sql = "UPDATE producto SET 
    nombre = '$nombre',
    idcategoria_insumos_af = '$idcategoria',
		idunidad_medida = '$unidad_medida',
    descripcion = '$descripcion',
		idcolor = '$color',		
		modelo = '$modelo',
		serie = '$serie',
		estado_igv = '$estado_igv',
		precio_unitario='$precio_unitario',
		precio_igv = '$precio_igv',
		precio_sin_igv = '$precio_sin_igv',
		precio_total = '$precio_total',
		ficha_tecnica = '$ficha_tecnica',		
		imagen = '$imagen1',
    user_updated= '" . $_SESSION['idusuario'] . "'
		WHERE idproducto = '$idproducto';";
    $editar =  ejecutarConsulta($sql); if ( $editar['status'] == false) {return $editar; } 

    $sql ="DELETE FROM detalle_marca WHERE idproducto= '$idproducto'";
    $delete_marca = ejecutarConsulta($sql);  if ($delete_marca['status'] == false) { return  $delete_marca;}

    foreach ($marca as $key => $value) {
      //detalle de marcas
      $sql = "INSERT INTO detalle_marca (idproducto, idmarca, user_created) VALUES ('$idproducto', '$value','" . $_SESSION['idusuario'] . "')";
      $marcas = ejecutarConsulta($sql); if ( $marcas['status'] == false) {return $marcas; } 
    }

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
    $data = []; $array_marca = []; $array_marca_name = [];

    $sql = "SELECT p.idproducto, p.idunidad_medida, p.idcolor, p.idcategoria_insumos_af, p.nombre, p.modelo, p.serie,  p.estado_igv, 
    p.precio_unitario, p.precio_igv, p.precio_sin_igv, p.precio_total, p.ficha_tecnica, p.descripcion, p.imagen, p.estado, p.created_at,
    um.nombre_medida, c.nombre_color, ciaf.nombre AS categoria
		FROM producto AS p, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf
    WHERE p.idunidad_medida = um.idunidad_medida AND p.idcolor = c.idcolor 
    AND p.idcategoria_insumos_af = ciaf.idcategoria_insumos_af AND p.idproducto = '$idproducto'";
    $activos = ejecutarConsultaSimpleFila($sql); if ($activos['status'] == false) { return  $activos;}

    $sql3 = "SELECT dm.iddetalle_marca, m.idmarca, m.nombre_marca FROM detalle_marca as dm, marca as m WHERE dm.idmarca=m.idmarca AND dm.idproducto = '$idproducto';";
    $detalle_marca = ejecutarConsultaArray($sql3); if ($detalle_marca['status'] == false) { return  $detalle_marca;}

    foreach ($detalle_marca['data'] as $key => $value) { array_push($array_marca, $value['idmarca'] ); }
    foreach ($detalle_marca['data'] as $key => $value) { array_push($array_marca_name, $value['nombre_marca'] ); }
    
    $data = [
      'idproducto'      => $activos['data']['idproducto'],
      'idunidad_medida' => $activos['data']['idunidad_medida'],
      'nombre_medida'   => $activos['data']['nombre_medida'],
      'idcolor'         => $activos['data']['idcolor'],
      'nombre_color'    => $activos['data']['nombre_color'],
      'idcategoria_insumos_af'  => $activos['data']['idcategoria_insumos_af'],
      'categoria'               => $activos['data']['categoria'],
      'nombre'          => decodeCadenaHtml($activos['data']['nombre']),
      'modelo'          => decodeCadenaHtml($activos['data']['modelo']),
      'serie'           => decodeCadenaHtml($activos['data']['serie']),
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

      'id_marca'        => $array_marca,
      'marcas'          => $array_marca_name,
    ];

    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];    
  }

  //Implementar un método para listar los registros
  public function tabla_principal( $id_categoria ) {

    $data = Array(); $tipo_categoria = '';

    if ($id_categoria == 'todos') {
      $tipo_categoria = "AND p.idcategoria_insumos_af != '1'";
    } else{
      $tipo_categoria = "AND p.idcategoria_insumos_af = '$id_categoria'";
    }
    
    $sql = "SELECT p.idproducto, p.idunidad_medida, p.nombre, p.imagen, p.ficha_tecnica, p.estado, um.nombre_medida, p.descripcion,
    -- marcas en lista HTML
    IFNULL(
      CONCAT( '<ol class=\'pl-3\' style=\'font-size: 12px;\'>',
      GROUP_CONCAT(CONCAT('<li>', m.nombre_marca, '</li>') ORDER BY m.nombre_marca SEPARATOR ''),
      '</ol>'), 
      ''
    ) AS marca,
    -- marcas para exportar
    IFNULL( GROUP_CONCAT(CONCAT('-', m.nombre_marca) ORDER BY m.nombre_marca SEPARATOR '\n'),  '' ) AS marca_export,
    -- promedio de precios
    IFNULL(AVG(dc.precio_con_igv), 0) AS promedio_precio,
    -- cantidad de compras
    COUNT(dc.idcompra_proyecto) AS cantidad_fact
    FROM producto p
    JOIN unidad_medida um          ON um.idunidad_medida = p.idunidad_medida
    LEFT JOIN detalle_marca dm     ON dm.idproducto = p.idproducto AND dm.estado='1' AND dm.estado_delete='1'
    LEFT JOIN marca m              ON m.idmarca = dm.idmarca
    LEFT JOIN ( 
      select deco.idproducto, deco.precio_con_igv, deco.idcompra_proyecto from detalle_compra deco 
      inner join compra_por_proyecto cpp  ON cpp.idcompra_proyecto = deco.idcompra_proyecto  
      where cpp.estado='1' AND cpp.estado_delete='1'
    ) as dc ON dc.idproducto = p.idproducto
    WHERE   p.estado='1'  AND p.estado_delete='1' $tipo_categoria
    GROUP BY p.idproducto
    ORDER BY p.nombre ASC;";
    $activo_fijo = ejecutarConsultaArray($sql);  
    

    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $activo_fijo['data'] ];
  }

  //Seleccionar Trabajador Select2
  public function lista_de_categorias() {
    $sql = "SELECT idcategoria_insumos_af as idcategoria , nombre 
    FROM categoria_insumos_af WHERE estado='1' AND estado_delete='1' AND idcategoria_insumos_af <>1; ";
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
