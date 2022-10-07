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
  public function insertar($idcategoria, $nombre, $modelo, $serie, $marcas, $precio_unitario, $descripcion, $imagen1, $ficha_tecnica, $estado_igv, $precio_igv, $precio_sin_igv, $unidad_medida, $color, $total_precio)
  {

    // $array_marcas = json_decode($marcas, true);

    $sql = "SELECT p.nombre, p.modelo , p.serie, p.imagen, p.precio_igv,	p.precio_sin_igv, p.precio_total,	p.estado, c.nombre_color, 
    um.nombre_medida, p.estado, p.estado_delete, ttc.nombre as tipo_tierra_concreto
		FROM producto p, unidad_medida as um, color as c, tipo_tierra_concreto as ttc
    WHERE um.idunidad_medida=p.idunidad_medida AND c.idcolor=p.idcolor 
    AND idcategoria_insumos_af = '1' AND p.nombre='$nombre' AND p.idcolor = '$color' AND p.idunidad_medida = '$unidad_medida';";
    $buscando = ejecutarConsultaArray($sql); if ($buscando['status'] == false) { return $buscando; }

    if ( empty($buscando['data']) ) {

      $sql = "INSERT INTO producto (idcategoria_insumos_af, idtipo_tierra_concreto, nombre, modelo, serie, precio_unitario, descripcion, imagen, ficha_tecnica, estado_igv, precio_igv, precio_sin_igv,idunidad_medida,idcolor,precio_total,user_created) 
      VALUES ('$idcategoria','1', '$nombre', '$modelo', '$serie', '$precio_unitario','$descripcion','$imagen1','$ficha_tecnica','$estado_igv','$precio_igv','$precio_sin_igv','$unidad_medida','$color','$total_precio','" . $_SESSION['idusuario'] . "')";
     
      $intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','".$intertar['data']."','Nuevo producto registrado','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

      foreach ($marcas as $key => $value) {
        //detalle de marcas
         $sql = "INSERT INTO detalle_marca (idproducto, idmarca, user_created) VALUES ('". $intertar['data'] ."', '$value','" . $_SESSION['idusuario'] . "')";
         $marcas = ejecutarConsulta($sql); if ( $marcas['status'] == false) {return $marcas; } 
      }

     return $intertar;

    } else {
      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>'.$value['nombre'].'<br>
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
  public function editar($idproducto, $idcategoria, $nombre, $modelo, $serie, $marca, $precio_unitario, $descripcion, $imagen1, $ficha_tecnica, $estado_igv, $precio_igv, $precio_sin_igv, $unidad_medida, $color, $total_precio)
  {
   
    $sql = "UPDATE producto SET 
		idcategoria_insumos_af = '$idcategoria',
		nombre='$nombre', 
    modelo = '$modelo', 
    serie = '$serie',
		precio_unitario='$precio_unitario', 
		descripcion='$descripcion', 
		imagen='$imagen1',
		ficha_tecnica='$ficha_tecnica',
		estado_igv='$estado_igv',
		precio_igv='$precio_igv',
		precio_sin_igv='$precio_sin_igv',
		idunidad_medida='$unidad_medida',
		idcolor='1',
		precio_total='$total_precio',
    user_updated= '" . $_SESSION['idusuario'] . "'

		WHERE idproducto='$idproducto'";

    $editar =  ejecutarConsulta($sql); if ( $editar['status'] == false) {return $editar; }
    
    $sql ="DELETE FROM detalle_marca WHERE idproducto= '$idproducto'";
    $delete_marca = ejecutarConsulta($sql);  if ($delete_marca['status'] == false) { return  $delete_marca;}

    foreach ($marca as $key => $value) {
      //detalle de marcas
      $sql = "INSERT INTO detalle_marca (idproducto, idmarca, user_created) VALUES ('$idproducto', '$value','" . $_SESSION['idusuario'] . "')";
      $marcas = ejecutarConsulta($sql); if ( $marcas['status'] == false) {return $marcas; } 
    }

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
    $data = Array(); $array_marca = []; $array_marca_name = [];

    $sql = "SELECT p.idproducto, p.idunidad_medida, p.idcategoria_insumos_af, p.nombre, p.modelo, p.serie,
		p.descripcion, p.imagen, p.estado_igv, p.precio_unitario, p.precio_igv, p.precio_sin_igv, p.precio_total,
		p.ficha_tecnica, p.estado, um.nombre_medida
		FROM producto p, unidad_medida as um
		WHERE um.idunidad_medida=p.idunidad_medida AND p.idproducto ='$idproducto';";
    $producto = ejecutarConsultaSimpleFila($sql); if ($producto['status'] == false) { return $producto; }

    $sql3 = "SELECT dm.iddetalle_marca, m.nombre_marca FROM detalle_marca as dm, marca as m WHERE dm.idmarca=m.idmarca AND dm.idproducto = '$idproducto';";
    $detalle_marca = ejecutarConsultaArray($sql3); if ($detalle_marca['status'] == false) { return  $detalle_marca;}

    foreach ($detalle_marca['data'] as $key => $value) { array_push($array_marca, $value['iddetalle_marca'] ); }
    foreach ($detalle_marca['data'] as $key => $value) { array_push($array_marca_name, $value['nombre_marca'] ); }

    $data = array(
      'idproducto'      => ( empty($producto['data']['idproducto']) ? '' : $producto['data']['idproducto']),
      'idcategoria_insumos_af' => ( empty($producto['data']['idcategoria_insumos_af']) ? '' : $producto['data']['idcategoria_insumos_af']),
      'idunidad_medida' => ( empty($producto['data']['idunidad_medida']) ? '' : $producto['data']['idunidad_medida']),
      'nombre'          => ( empty($producto['data']['nombre']) ? '' :decodeCadenaHtml($producto['data']['nombre'])),
      'descripcion'     => ( empty($producto['data']['descripcion']) ? '' : decodeCadenaHtml($producto['data']['descripcion'])),
      'imagen'          => ( empty($producto['data']['imagen']) ? '' : $producto['data']['imagen']),
      'ficha_tecnica'   => ( empty($producto['data']['ficha_tecnica']) ? '' : $producto['data']['ficha_tecnica']),
      'estado'          => ( empty($producto['data']['estado']) ? '' : $producto['data']['estado']),
      'nombre_medida'   => ( empty($producto['data']['nombre_medida']) ? '' : $producto['data']['nombre_medida']),
      'detalle_marca'   => $array_marca,
      'marcas'   => $array_marca_name,
    );
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
    
  }

  //Implementar un método para listar los registros
  public function tbla_principal() {
    $data = Array();


    $sql = "SELECT p.idproducto, p.idunidad_medida, p.nombre, p.imagen, p.ficha_tecnica, p.estado,	um.nombre_medida, p.descripcion
    FROM producto p, unidad_medida as um
    WHERE um.idunidad_medida=p.idunidad_medida AND p.idcategoria_insumos_af = '1' AND p.estado='1' AND p.estado_delete='1' ORDER BY p.nombre ASC;";
    $data_tabla_p= ejecutarConsultaArray($sql);    if ($data_tabla_p['status'] == false){ return $data_tabla_p; }

    foreach ($data_tabla_p['data'] as $key => $value) {

      $id = $value['idproducto'];

      //listar detalle_marca
      $sql = "SELECT dm.iddetalle_marca, dm.idproducto, dm.idmarca, m.nombre_marca as marca 
      FROM detalle_marca as dm, marca as m 
      WHERE dm.idmarca = m.idmarca AND dm.idproducto = '$id' AND dm.estado='1' AND dm.estado_delete='1' ORDER BY dm.iddetalle_marca ASC;";
      $detalle_marca = ejecutarConsultaArray($sql);   if ($detalle_marca['status'] == false){ return $detalle_marca; }
      //sacar promedio de producto de  detalle compra
      $datalle_marcas = ""; $datalle_marcas_export = "";
      foreach ($detalle_marca['data'] as $key => $value2) {
        $datalle_marcas .=  '<li >'.$value2['marca'].'</li>';
        $datalle_marcas_export .=  '<li>  -'.$value2['marca'].'</li>';
      }

      $sql = "SELECT  AVG(precio_con_igv) AS promedio_precio FROM detalle_compra WHERE idproducto='$id';";
      $promedio_precio = ejecutarConsultaSimpleFila($sql);  if ($promedio_precio['status'] == false){ return $promedio_precio; }

      $data[] = Array(
        'idproducto'      => ( empty($value['idproducto']) ? '' : $value['idproducto']),
        'idunidad_medida' => ( empty($value['idunidad_medida']) ? '' : $value['idunidad_medida']),
        'nombre'          => ( empty($value['nombre']) ? '' : decodeCadenaHtml($value['nombre'])),
        'imagen'          => ( empty($value['imagen']) ? '' : $value['imagen']),
        'ficha_tecnica'   => ( empty($value['ficha_tecnica']) ? '' : $value['ficha_tecnica']),
        'estado'          => ( empty($value['estado']) ? '' : $value['estado']),
        'nombre_medida'   => ( empty($value['nombre_medida']) ? '' : $value['nombre_medida']),
        'detalle_marca'   => '<ol>'.$datalle_marcas. '</ol>',
        'detalle_marca_export'   => $datalle_marcas_export,
        'promedio_precio' => ( empty($promedio_precio['data']['promedio_precio']) ? '0.00' : floatval($promedio_precio['data']['promedio_precio'])),        
        'descripcion' => ( empty($value['descripcion']) ? '' : $value['descripcion'])
      );

    }
      //var_dump($data);die();
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
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
