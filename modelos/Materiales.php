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
  public function insertar($nombre, $idcategoria, $unidad_medida, $marca, $descripcion, $color, $modelo, $serie, $estado_igv, $precio_unitario, $precio_sin_igv, $precio_igv, $precio_total, $ficha_tecnica, $imagen1)
  {

    // $array_marcas = json_decode($marcas, true);

    $sql = "SELECT p.nombre, p.imagen, p.estado, p.estado_delete, um.nombre_medida, ciaf.nombre as nombre_categoria
		FROM producto p, unidad_medida as um, categoria_insumos_af as ciaf 
    WHERE um.idunidad_medida=p.idunidad_medida  AND ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af AND p.idcategoria_insumos_af = '$idcategoria' AND p.nombre='$nombre' AND p.idcolor = '$color' AND p.idunidad_medida = '$unidad_medida';";
    $buscando = ejecutarConsultaArray($sql); if ( $buscando['status'] == false) {return $buscando; } 

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
  public function editar($idproducto, $nombre, $idcategoria, $unidad_medida, $marca, $descripcion, $color, $modelo, $serie, $estado_igv, $precio_unitario, $precio_sin_igv, $precio_igv, $precio_total, $ficha_tecnica, $imagen1)
  {
   
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
      $precio = ejecutarConsultaSimpleFila($sql);  if ($precio['status'] == false){ return $precio; }

      $data[] = Array(
        'idproducto'      =>  $value['idproducto'],
        'idunidad_medida' =>  $value['idunidad_medida'],
        'nombre'          => ( empty($value['nombre']) ? '' : decodeCadenaHtml($value['nombre'])),
        'imagen'          => ( empty($value['imagen']) ? '' : $value['imagen']),
        'ficha_tecnica'   => ( empty($value['ficha_tecnica']) ? '' : $value['ficha_tecnica']),
        'estado'          => ( empty($value['estado']) ? '' : $value['estado']),
        'nombre_medida'   => ( empty($value['nombre_medida']) ? '' : $value['nombre_medida']),
        'marca'           => '<ol class="pl-3">'.$datalle_marcas. '</ol>',
        'marca_export'    => $datalle_marcas_export,
        'promedio_precio' => ( empty($precio['data']['promedio_precio']) ? '0.00' : floatval($precio['data']['promedio_precio'])),        
        'descripcion'     => ( empty($value['descripcion']) ? '' : $value['descripcion'])
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
  //=====================================C O M P R A S  P O R   P R O D U C T O=======================================
  //=====================================C O M P R A S  P O R   P R O D U C T O=======================================
  //=====================================C O M P R A S  P O R   P R O D U C T O=======================================

  public function tbla_facturas($idproducto) {
    $data = [];
    $sql = "SELECT cpp.idproyecto,cpp.idcompra_proyecto, cpp.fecha_compra, dc.ficha_tecnica_producto AS ficha_tecnica, 
		dc.idproducto, pr.nombre AS nombre_producto, dc.cantidad, cpp.tipo_comprobante, cpp.serie_comprobante,
		dc.precio_con_igv, dc.descuento, dc.subtotal, prov.razon_social AS proveedor, p.nombre_codigo, p.nombre_proyecto
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto 
		AND dc.idproducto = pr.idproducto AND cpp.estado = '1' AND cpp.estado_delete = '1'
		AND cpp.idproveedor = prov.idproveedor AND dc.idproducto = '$idproducto' 
		ORDER BY cpp.fecha_compra DESC;";
    // return ejecutarConsulta($sql);
    $compra = ejecutarConsultaArray($sql); if ($compra['status'] == false) { return $compra; }

    foreach ($compra['data'] as $key => $value) {
      $idcompra_proyecto = $value['idcompra_proyecto'];
      $idproducto = $value['idproducto'];

      $sql3 = "SELECT COUNT(comprobante) as cant_comprobantes FROM factura_compra_insumo WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
      $cant_comprobantes = ejecutarConsultaSimpleFila($sql3); if ($cant_comprobantes['status'] == false) { return $cant_comprobantes; }

      //listar detalle_marca
      $sql = "SELECT dm.iddetalle_marca, dm.idproducto, dm.idmarca, m.nombre_marca as marca 
      FROM detalle_marca as dm, marca as m 
      WHERE dm.idmarca = m.idmarca AND dm.idproducto = '$idproducto' AND dm.estado='1' AND dm.estado_delete='1' ORDER BY dm.iddetalle_marca ASC;";
      $detalle_marca = ejecutarConsultaArray($sql);   if ($detalle_marca['status'] == false){ return $detalle_marca; }
      
      $marcas_html = ""; $datalle_marcas_export = "";
      foreach ($detalle_marca['data'] as $key => $value2) {
        $marcas_html .=  '<li >'.$value2['marca'].'</li>';
        $datalle_marcas_export .=  '<li>  -'.$value2['marca'].'</li>';
      }

      $data[] = [
        'idproyecto'        => $value['idproyecto'],
        'idcompra_proyecto' => $value['idcompra_proyecto'],
        'nombre_codigo'     => $value['nombre_codigo'],
        'nombre_proyecto'   => $value['nombre_proyecto'],
        'fecha_compra'      => $value['fecha_compra'],
        'ficha_tecnica'     => $value['ficha_tecnica'],
        'idproducto'        => $value['idproducto'],
        'nombre_producto'   => $value['nombre_producto'],
        'cantidad'          => $value['cantidad'],
        'tipo_comprobante'  => $value['tipo_comprobante'],
        'serie_comprobante' => $value['serie_comprobante'],
        'precio_con_igv'    => $value['precio_con_igv'],
        'descuento'         => $value['descuento'],
        'subtotal'          => $value['subtotal'],
        'proveedor'         => $value['proveedor'],

        'html_marca'        => '<ol class="pl-3">'.$marcas_html. '. </ol>',
        'marca_export'      => $datalle_marcas_export,
        'cant_comprobantes' => empty($cant_comprobantes['data']['cant_comprobantes']) ? 0 : floatval($cant_comprobantes['data']['cant_comprobantes']),
      ];
    }

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' => $data, 'affected_rows' => $compra['affected_rows']];
  }

  // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  :::::::::::::::::::::::::: 
  public function tbla_comprobantes($id_compra) {
    //var_dump($idfacturacompra);die();
    $sql = "SELECT fci.idfactura_compra_insumo, fci.idcompra_proyecto, fci.comprobante, fci.estado, fci.estado_delete, fci.created_at, 
    fci.updated_at, cpp.tipo_comprobante, cpp.serie_comprobante, p.razon_social, cpp.fecha_compra
    FROM factura_compra_insumo as fci, compra_por_proyecto as cpp, proveedor as p
    WHERE fci.idcompra_proyecto = cpp.idcompra_proyecto AND cpp.idproveedor = p.idproveedor AND fci.idcompra_proyecto = '$id_compra' AND fci.estado=1 AND fci.estado_delete=1;";
    return ejecutarConsulta($sql);
  }





}

?>
