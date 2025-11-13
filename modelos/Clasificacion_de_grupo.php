<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Clasificacion_de_grupo
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  // :::::::::::::::::::::::::: S E C C I O N   G R U P O  ::::::::::::::::::::::::::

  //Implementamos un método para insertar registros
  public function insertar_grupo( $idproyecto, $nombre,  $descripcion) {
    $sql = "SELECT  nombre,  descripcion, estado, estado_delete
    FROM clasificacion_grupo WHERE nombre = '$nombre' ;";
    $buscando = ejecutarConsultaArray($sql);  if ($buscando['status'] == false) { return $buscando; }

    if ( empty($buscando['data']) ) {
      $sql = "INSERT INTO clasificacion_grupo (nombre, descripcion) 
      VALUES ('$nombre', '$descripcion')";
      return ejecutarConsulta($sql);
    } else {
      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>'.$value['nombre'].'<br>
          <b>Descripción: </b>'.'<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $value['descripcion'] . '</textarea>'.'<br>
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
  public function editar_grupo( $idproyecto, $idclasificacion_grupo, $nombre, $descripcion)  {
     
    $sql = "UPDATE clasificacion_grupo SET nombre='$nombre', descripcion='$descripcion' 
    WHERE idclasificacion_grupo='$idclasificacion_grupo'";
    return ejecutarConsulta($sql);
  }

  public function desactivar_grupo($idclasificacion_grupo) {
    $sql = "UPDATE clasificacion_grupo SET estado='0' WHERE idclasificacion_grupo ='$idclasificacion_grupo'";
    return ejecutarConsulta($sql);
  }

  public function activar_grupo($idclasificacion_grupo)  {
    $sql = "UPDATE clasificacion_grupo SET estado='1' WHERE idclasificacion_grupo ='$idclasificacion_grupo'";
    return ejecutarConsulta($sql);
  }

  public function eliminar_grupo($idclasificacion_grupo) {
    $sql = "UPDATE clasificacion_grupo SET estado_delete='0' WHERE idclasificacion_grupo ='$idclasificacion_grupo'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar_grupo($idclasificacion_grupo) {
    $sql = "SELECT * FROM clasificacion_grupo WHERE  idclasificacion_grupo ='$idclasificacion_grupo'";

    return ejecutarConsultaSimpleFila($sql);    
  }

  //Implementar un método para listar los registros
  public function tbla_principal_grupo($id_proyecto) {
    $sql = "SELECT dpcg.idproyecto, cg.* 
    FROM clasificacion_grupo as cg
    LEFT JOIN detalle_p_cg as dpcg on dpcg.idclasificacion_grupo=cg.idclasificacion_grupo and dpcg.idproyecto = '$id_proyecto'
    WHERE cg.estado_delete='1' ORDER BY cg.estado DESC, cg.nombre ASC";
    return ejecutarConsulta($sql);
  }
  
  //Implementar un método para listar los registros
  public function lista_de_grupo($id_proyecto) {
    $data_array = [];
    $sql = "SELECT cg.*
    FROM clasificacion_grupo as cg
    INNER JOIN detalle_p_cg as dpcg ON dpcg.idclasificacion_grupo = cg.idclasificacion_grupo
    WHERE dpcg.idproyecto = '$id_proyecto' AND cg.estado_delete='1' AND cg.estado='1' ORDER BY cg.nombre ASC";
    $grupo =  ejecutarConsultaArray($sql); if ($grupo['status'] == false) { return $grupo; }

    foreach ($grupo['data'] as $key => $value) {
      $id = $value['idclasificacion_grupo'];
      $sql_2 = "SELECT COUNT(dc.iddetalle_compra) as cant_por_producto FROM detalle_compra as dc, compra_por_proyecto as cpp 
      WHERE dc.idcompra_proyecto = cpp.idcompra_proyecto and cpp.idproyecto = '$id_proyecto' and  dc.idclasificacion_grupo = '$id' 
      and cpp.estado = '1' and cpp.estado_delete = '1';";
      $cant =  ejecutarConsultaSimpleFila($sql_2); if ($cant['status'] == false) { return $cant; }
      $cant_compra = empty($cant['data']) ? 0 : ( empty($cant['data']['cant_por_producto']) ? 0 : floatval($cant['data']['cant_por_producto']));

      $sql_3 = "SELECT COUNT(idsubcontrato) AS cant_sub_contrato FROM subcontrato 
      WHERE idproyecto = '$id_proyecto' and  idclasificacion_grupo = '$id' and estado = '1' and estado_delete = '1';";
      $cant2 =  ejecutarConsultaSimpleFila($sql_3); if ($cant2['status'] == false) { return $cant2; }
      $cant_sub = empty($cant2['data']) ? 0 : ( empty($cant2['data']['cant_sub_contrato']) ? 0 : floatval($cant2['data']['cant_sub_contrato']));

      $data_array[] = [
        'idclasificacion_grupo' => $value['idclasificacion_grupo'],
        'nombre' => $value['nombre'],
        'descripcion' => $value['descripcion'],
        'cant_por_producto' => $cant_compra + $cant_sub,
      ];
    }

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data_array] ;   
  }

  //Implementar un método para listar los registros
  public function proyectos_y_grupos($id) {    
    $sql = "SELECT dpcg.* 
    FROM detalle_p_cg as dpcg
    INNER JOIN proyecto as p ON p.idproyecto = dpcg.idproyecto
    INNER JOIN clasificacion_grupo as cg ON cg.idclasificacion_grupo = dpcg.idclasificacion_grupo
    WHERE dpcg.estado = '1' AND dpcg.estado_delete = '1' AND dpcg.idclasificacion_grupo = '$id'; ";
    return  ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros
  public function lista_de_proyectos() {    
    $sql = "SELECT * FROM proyecto ORDER BY estado DESC";
    return  ejecutarConsultaArray($sql);
  }

  //Implementar un método para listar los registros
  public function asigar_grupo_a_proyecto($idgrupo, $proyecto) {    
    $sql = "DELETE FROM detalle_p_cg WHERE idclasificacion_grupo = '$idgrupo'";
    $delete = ejecutarConsulta($sql);

    $ii = 0;
    if ( !empty($proyecto) ) {
      while ($ii < count($proyecto)) {
        
        $sql_detalle = "INSERT INTO detalle_p_cg(idproyecto, idclasificacion_grupo) VALUES ('$proyecto[$ii]', '$idgrupo')";
        $new_asig =  ejecutarConsulta_retornarID($sql_detalle); if ($new_asig['status'] == false) { return  $new_asig;}      

        $ii = $ii + 1;
      }
    }    

    return $delete;
  }
  

  public function asignar_proyecto_grupo($idclasificacion_grupo,$id_proyecto) {    
    $sql = "INSERT INTO detalle_p_cg(idproyecto, idclasificacion_grupo) VALUES ( '$id_proyecto', '$idclasificacion_grupo')";
    return  ejecutarConsulta($sql);
  }

  public function remover_proyecto_grupo($idclasificacion_grupo,$id_proyecto) {    
    $sql = "DELETE FROM detalle_p_cg WHERE idproyecto = '$id_proyecto' AND idclasificacion_grupo = '$idclasificacion_grupo'";
    return  ejecutarConsulta($sql);
  }

  // :::::::::::::::::::::::::: S E C C I O N   C O M P R A   Y   S U B C O N T R A T O ::::::::::::::::::::::::::

  //Implementar un método para listar los registros
  public function tbla_principal_compra_subcontrato($id_proyecto, $idclasificacion_grupo, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {
    $data = [];
    $filtro_proveedor_s = "";   $filtro_proveedor_dc = ""; 
    $filtro_fecha_s = "";       $filtro_fecha_dc = ""; 
    $filtro_comprobante_s = ""; $filtro_comprobante_dc = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha_s = "AND s.fecha_subcontrato BETWEEN '$fecha_1' AND '$fecha_2'";
      $filtro_fecha_dc = "AND cpp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha_s = "AND s.fecha_subcontrato = '$fecha_1'";
      $filtro_fecha_dc = "AND cpp.fecha_compra = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha_s = "AND s.fecha_subcontrato = '$fecha_2'";
      $filtro_fecha_dc = "AND cpp.fecha_compra = '$fecha_2'";
    }   
    if (empty($id_proveedor) ) {   } else { $filtro_proveedor_s = "AND s.idproveedor = '$id_proveedor'"; $filtro_proveedor_dc = "AND cpp.idproveedor = '$id_proveedor'"; }
    if (empty($comprobante) ) {   } else { $filtro_comprobante_s = "AND s.tipo_comprobante = '$comprobante'"; $filtro_comprobante_dc = "AND cpp.tipo_comprobante = '$comprobante'"; }

    $sql_1 = "SELECT s.idsubcontrato, s.idproyecto, s.idproveedor, s.idclasificacion_grupo, s.tipo_comprobante, s.numero_comprobante, 
    s.forma_de_pago, s.fecha_subcontrato, s.val_igv, s.tipo_gravada, s.subtotal, s.igv, s.costo_parcial, s.descripcion, s.glosa, s.comprobante,
    prov.razon_social, prov.tipo_documento, prov.ruc
    FROM subcontrato as s, clasificacion_grupo as cg, proveedor as prov
    WHERE s.idclasificacion_grupo = cg.idclasificacion_grupo AND  s.idproveedor = prov.idproveedor 
    AND s.idproyecto = '$id_proyecto' AND s.idclasificacion_grupo = '$idclasificacion_grupo' AND s.estado ='1' and s.estado_delete = '1' 
    $filtro_proveedor_s $filtro_fecha_s ORDER BY s.fecha_subcontrato DESC;";
    $subcontrato = ejecutarConsultaArray($sql_1);	if ($subcontrato['status'] == false) { return $subcontrato; }

    foreach ($subcontrato['data'] as $key => $value1) {
      $data[] = [
        'idproyecto'            => $value1['idproyecto'],
        'idcompra_proyecto'     => $value1['idsubcontrato'],
        'idproducto'            => $value1['idsubcontrato'],
        'fecha_compra'          => $value1['fecha_subcontrato'],
        'nombre_dia'            => nombre_dia_semana($value1['fecha_subcontrato']),        
        'nombre_producto'       => $value1['descripcion'],        
        'tipo_comprobante'      => $value1['tipo_comprobante'],
        'serie_comprobante'     => $value1['numero_comprobante'],
        'cantidad'              => 0,
        'precio_con_igv'        => floatval($value1['costo_parcial']),
        'descuento'             => 0,
        'subtotal'              => floatval($value1['costo_parcial']),
        'proveedor'             => $value1['razon_social'],
        'tipo_documento'        => $value1['tipo_documento'],
        'ruc'                   => $value1['ruc'],  
        'cant_comprobantes'     => 0,
        'comprobante'           => $value1['comprobante'],  
        'modulo'                => 'subcontrato',
      ];
    }

    // extraemos las compras segun: GRUPO
    $sql_2="SELECT cpp.idproyecto, cpp.idcompra_proyecto, cpp.fecha_compra, cpp.tipo_comprobante, cpp.serie_comprobante, 
    dc.idproducto, dc.cantidad, dc.precio_sin_igv, dc.igv, dc.precio_con_igv, dc.descuento, dc.subtotal, p.nombre as nombre_producto, p.imagen,
    um.nombre_medida,
    prov.razon_social, prov.tipo_documento, prov.ruc
    FROM detalle_compra as dc, compra_por_proyecto as cpp, proveedor as prov, producto as p, unidad_medida AS um 
    WHERE dc.idcompra_proyecto = cpp.idcompra_proyecto AND  cpp.idproveedor = prov.idproveedor and dc.idproducto = p.idproducto AND p.idunidad_medida = um.idunidad_medida 
     AND cpp.idproyecto = '$id_proyecto' AND dc.idclasificacion_grupo = '$idclasificacion_grupo' AND cpp.estado ='1' AND cpp.estado_delete = '1' 
    $filtro_proveedor_dc $filtro_fecha_dc ORDER BY cpp.fecha_compra DESC;";	
		$compra = ejecutarConsultaArray($sql_2);	if ($compra['status'] == false) { return $compra; }
      
    foreach ($compra['data'] as $key => $value) {
      $idcompra_proyecto = $value['idcompra_proyecto']; 

      $sql_3 = "SELECT COUNT(comprobante) as cant_comprobantes FROM factura_compra_insumo WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
      $cant_c = ejecutarConsultaSimpleFila($sql_3); if ($cant_c['status'] == false) { return $cant_c; }

      $data[] = [
        'idproyecto'            => $value['idproyecto'],
        'idcompra_proyecto'     => $value['idcompra_proyecto'],
        'idproducto'            => $value['idproducto'],
        'fecha_compra'          => $value['fecha_compra'],
        'nombre_dia'            => nombre_dia_semana($value['fecha_compra']),        
        'nombre_producto'       => $value['nombre_producto'],        
        'tipo_comprobante'      => $value['tipo_comprobante'],
        'serie_comprobante'     => $value['serie_comprobante'],
        'cantidad'              => floatval($value['cantidad']),
        'precio_con_igv'        => floatval($value['precio_con_igv']),
        'descuento'             => floatval($value['descuento']),
        'subtotal'              => floatval($value['subtotal']),
        'proveedor'             => $value['razon_social'],
        'tipo_documento'        => $value['tipo_documento'],
        'ruc'                   => $value['ruc'],  
        'cant_comprobantes'     => (empty($cant_c['data']['cant_comprobantes']) ? 0 : floatval($cant_c['data']['cant_comprobantes']) ),
        'comprobante'           => '', 
        'modulo'                => 'compra_insumos',
      ];          
    }
  
    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data  ] ;
  }

  //Implementar un método para listar los registros
  public function total_compra_subcontrato($id_proyecto, $idclasificacion_grupo, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND ca.fecha BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND ca.fecha = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND ca.fecha = '$fecha_2'";
    }   
    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.idproveedor = '$id_proveedor'"; }

    $sql="SELECT  SUM(dc.cantidad) AS cantidad, AVG(dc.precio_con_igv) AS precio_promedio, SUM(dc.descuento) AS descuento, SUM(dc.subtotal) AS subtotal, SUM(cpp.total) as total_compra
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov, clasificacion_grupo as ttc 
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
    AND ttc.idclasificacion_grupo = pr.idclasificacion_grupo AND cpp.idproyecto ='$id_proyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1'
		AND cpp.idproveedor = prov.idproveedor AND pr.idclasificacion_grupo = '$idclasificacion_grupo' 
		ORDER BY cpp.fecha_compra DESC;";
    
    return ejecutarConsultaSimpleFila($sql);
  }

  // :::::::::::::::::::::::::: S E C C I O N    R E S U M E N ::::::::::::::::::::::::::
  //Implementar un método para listar los registros
  public function tbla_principal_resumen($idproyecto) {

    $data = [];

    $sql_0 = "SELECT cg.idclasificacion_grupo, cg.nombre, cg.descripcion 
    FROM clasificacion_grupo as cg
    INNER JOIN detalle_p_cg as dpcg ON dpcg.idclasificacion_grupo = cg.idclasificacion_grupo
    WHERE dpcg.idproyecto = '$idproyecto' AND cg.estado = '1' and cg.estado_delete = '1' ORDER BY cg.nombre ASC;";
    $grupo = ejecutarConsultaArray($sql_0); if ($grupo['status'] == false) { return $grupo; }

    foreach ($grupo['data'] as $key => $value) {
      $id = $value['idclasificacion_grupo'];
      $sql="SELECT cpp.idproyecto, cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, um.nombre_medida, um.abreviacion as um_abreviacion, 
		  cg.nombre as grupo, SUM(dc.cantidad) AS cantidad_total, SUM(dc.precio_con_igv) AS precio_con_igv, 
      SUM(dc.descuento) AS descuento_total, SUM(dc.subtotal) precio_total , COUNT(dc.idproducto) AS count_productos, 
      AVG(dc.precio_con_igv) AS precio_promedio
      FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, clasificacion_grupo AS cg,
      unidad_medida AS um
      WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
      AND um.idunidad_medida  = pr.idunidad_medida  AND cg.idclasificacion_grupo = dc.idclasificacion_grupo
      AND cpp.idproyecto = '$idproyecto'  AND dc.idclasificacion_grupo = '$id'  AND cpp.estado = '1' AND cpp.estado_delete = '1'  AND cg.estado = '1' AND cg.estado_delete = '1' 
       ORDER BY cg.nombre ASC;";
      $compra = ejecutarConsultaSimpleFila($sql); if ($compra['status'] == false) { return $compra; }

      $precio_con_igv_c = empty($compra['data']) ? 0 :( empty($compra['data']['precio_con_igv']) ? 0 : floatval($compra['data']['precio_con_igv']));
      $precio_total_c = empty($compra['data']) ? 0 :( empty($compra['data']['precio_total']) ? 0 : floatval($compra['data']['precio_total']));

      $sql_2 = "SELECT SUM(s.costo_parcial) AS costo_parcial FROM subcontrato as s
      WHERE  s.idproyecto = '$idproyecto' AND s.idclasificacion_grupo = '$id' and estado = '1' and estado_delete = '1';";
      $subcontrato = ejecutarConsultaSimpleFila($sql_2); if ($subcontrato['status'] == false) { return $subcontrato; }
      $costo_parcial = empty($subcontrato['data']) ? 0 :( empty($subcontrato['data']['costo_parcial']) ? 0 : floatval($subcontrato['data']['costo_parcial']));

      $data[] = [
        'idclasificacion_grupo' => $id,
        'grupo'                 => $value['nombre'],
        'um_abreviacion'        => empty($compra['data']) ? '' :( empty($compra['data']['um_abreviacion']) ? ' - ' : $compra['data']['um_abreviacion']),
        'cantidad_total'        => empty($compra['data']) ? 0 :( empty($compra['data']['cantidad_total']) ? 0 : $compra['data']['cantidad_total']),
        'precio_promedio'       => empty($compra['data']) ? 0 :( empty($compra['data']['precio_promedio']) ? 0 : $compra['data']['precio_promedio']),
        'descuento_total'       => empty($compra['data']) ? 0 :( empty($compra['data']['descuento_total']) ? 0 : $compra['data']['descuento_total']),
        'precio_total'          => $precio_total_c + $costo_parcial,      
        
      ];
    }
    return ['status' => true, 'data' => $data, 'message' => 'Todo oka.'];
    
  }

  public function total_resumen($idproyecto) {
    $sql = "SELECT SUM( dc.subtotal ) AS total, SUM( dc.cantidad ) AS cantidad, SUM( dc.descuento ) AS descuento 
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, clasificacion_grupo AS ttc
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
		AND pr.idclasificacion_grupo=cg.idclasificacion_grupo AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' 
    AND pr.idclasificacion_grupo != '1' AND cpp.estado_delete = '1';";
    return ejecutarConsultaSimpleFila($sql);
  }
}

?>
