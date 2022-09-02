<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Movimiento_tierra
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
  public function insertar($idproyecto,$nombre,$modulo,$descripcion)
  {
    $sql = "SELECT idproyecto, nombre, estado, estado_delete FROM tipo_tierra WHERE idproyecto='$idproyecto' AND nombre='$nombre'";
    $buscando = ejecutarConsultaArray($sql);
    if ($buscando['status'] == false) { return $buscando; }

    if ( empty($buscando['data']) ) {

      $sql = "INSERT INTO tipo_tierra(idproyecto, nombre, modulo, descripcion) VALUES ('$idproyecto','$nombre','$modulo','$descripcion')";
      return ejecutarConsulta($sql);

    } else {

      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>'.$value['nombre'].'<br>
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
  public function editar($idproyecto,$idtipo_tierra,$nombre,$modulo,$descripcion)
  {

    $sql = "UPDATE tipo_tierra SET idproyecto='$idproyecto',nombre='$nombre',modulo='$modulo',descripcion='$descripcion' WHERE idtipo_tierra='$idtipo_tierra'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idtipo_tierra_concreto)
  {
    $sql = "UPDATE tipo_tierra_concreto SET estado='0' WHERE idtipo_tierra_concreto ='$idtipo_tierra_concreto'";
    return ejecutarConsulta($sql);

  }

  //Implementamos un método para activar categorías
  public function eliminar($idtipo_tierra_concreto)
  {
    $sql = "UPDATE tipo_tierra_concreto SET estado_delete='0' WHERE idtipo_tierra_concreto ='$idtipo_tierra_concreto'";
    return ejecutarConsulta($sql);

  }

  public function mostrar($idtipo_tierra_concreto)
  {

    $sql = "SELECT * FROM tipo_tierra_concreto WHERE idtipo_tierra_concreto ='$idtipo_tierra_concreto'";

    return ejecutarConsultaSimpleFila($sql);

  }

  //Implementar un método para listar los registros
  public function tbla_principal($proyecto) {

    $sql = "SELECT * FROM tipo_tierra_concreto WHERE modulo = 'Movimiento de Tierras' AND estado='1' AND estado_delete='1' ORDER BY nombre DESC";

    return ejecutarConsulta($sql);

  }

  //-----------------------------------------------------------------------------------------
  //----------------------------------- Tabs -----------------------------------------------
  //-----------------------------------------------------------------------------------------

  public function listar_items($proyecto) {

    $sql = "SELECT * FROM tipo_tierra_concreto WHERE modulo = 'Movimiento de Tierras'  AND estado='1' AND estado_delete='1' ORDER BY nombre ASC";

    return ejecutarConsultaArray($sql);

  }

  //-----------------------------------------------------------------------------------------
  //----------------------- S E C C I O N  S E G Ú N  I T E M -------------------------------
  //-----------------------------------------------------------------------------------------

  public function insertar_detalle_item($idtipo_tierra_det,$idproveedor,$fecha,$nombre_dia,$cantidad,$precio_unitario,$total )  {
    $sql="INSERT INTO movimiento_tierra(idproveedor, idtipo_tierra, fecha, nombre_dia, cantidad, precio_unitario, total) 
    VALUES ('$idproveedor','$idtipo_tierra_det','$fecha','$nombre_dia','$cantidad','$precio_unitario','$total')";
    return ejecutarConsulta($sql);
  }

  public function editar_detalle_item($idmovimiento_tierra,$idtipo_tierra_det,$idproveedor,$fecha,$nombre_dia,$cantidad,$precio_unitario,$total ) {
    $sql ="UPDATE movimiento_tierra SET 
    idproveedor='$idproveedor',
    idtipo_tierra='$idtipo_tierra_det',
    fecha='$fecha',
    nombre_dia='$nombre_dia',
    cantidad='$cantidad',
    precio_unitario='$precio_unitario',
    total='$total' 
    WHERE idmovimiento_tierra='$idmovimiento_tierra'";
    return ejecutarConsulta($sql);
  }

  public function tbla_principal_tierra($id_proyecto,$idtipo_tierra,$fecha_1,$fecha_2,$id_proveedor,$comprobante) {
    $data =[];
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND mt.fecha BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND mt.fecha = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND mt.fecha = '$fecha_2'";
    }   
    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.idproveedor = '$id_proveedor'"; }

   // if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND mt.tipo_comprobante = '$comprobante'"; }  

    $sql="SELECT cpp.idproyecto,cpp.idcompra_proyecto, cpp.fecha_compra, dc.ficha_tecnica_producto AS ficha_tecnica, 
    pr.nombre AS nombre_producto, dc.cantidad, cpp.tipo_comprobante, cpp.serie_comprobante, cpp.estado as estado_compra,
    dc.precio_con_igv, dc.descuento, dc.subtotal, prov.razon_social AS proveedor, pr.idtipo_tierra_concreto, ttc.nombre as tipo_tierra_concreto
    FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov, tipo_tierra_concreto as ttc 
    WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto
    AND ttc.idtipo_tierra_concreto = pr.idtipo_tierra_concreto AND cpp.idproveedor = prov.idproveedor AND cpp.idproyecto ='$id_proyecto' 
    AND cpp.estado = '1' AND cpp.estado_delete = '1' AND pr.idtipo_tierra_concreto = '$idtipo_tierra' 
    ORDER BY cpp.fecha_compra DESC;";	
    $compra = ejecutarConsultaArray($sql);	

    if ($compra['status'] == false) { return $compra; }

    foreach ($compra['data'] as $key => $value) {
      $idcompra_proyecto = $value['idcompra_proyecto'];
   
      $sql3 = "SELECT COUNT(comprobante) as cant_comprobantes FROM factura_compra_insumo WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
      $cant_comprobantes = ejecutarConsultaSimpleFila($sql3);
      if ($cant_comprobantes['status'] == false) { return $cant_comprobantes; }
   
      $data[] = [
        'idproyecto'        => $value['idproyecto'],
        'idcompra_proyecto' => $value['idcompra_proyecto'],
        'fecha_compra'      => $value['fecha_compra'],
        'nombre_dia'        => nombre_dia_semana($value['fecha_compra']),
        'ficha_tecnica'     => $value['ficha_tecnica'],
        'nombre_producto'   => $value['nombre_producto'],
        'cantidad'          => $value['cantidad'],
        'tipo_comprobante'  => $value['tipo_comprobante'],
        'serie_comprobante' => $value['serie_comprobante'],
        'precio_con_igv'    => $value['precio_con_igv'],
        'descuento'         => $value['descuento'],
        'subtotal'          => $value['subtotal'],
        'proveedor'         => $value['proveedor'],
        'estado_compra'     => $value['estado_compra'],
        'cant_comprobantes' => (empty($cant_comprobantes['data']['cant_comprobantes']) ? 0 : floatval($cant_comprobantes['data']['cant_comprobantes']) ),
      ];
    } 
    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data, 'affected_rows' =>$compra['affected_rows'],  ] ;
  }

  public function mostrar_total_det_item($id_proyecto, $idtipo_tierra, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND mt.fecha BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND mt.fecha = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND mt.fecha = '$fecha_2'";
    }   

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.idproveedor = '$id_proveedor'"; }

    $sql="SELECT  SUM(dc.cantidad) AS cantidad, AVG(dc.precio_con_igv) AS precio_promedio, SUM(dc.descuento) AS descuento, SUM(dc.subtotal) AS subtotal
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov, tipo_tierra_concreto as ttc 
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
    AND ttc.idtipo_tierra_concreto = pr.idtipo_tierra_concreto AND cpp.idproyecto ='$id_proyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1'
		AND cpp.idproveedor = prov.idproveedor AND pr.idtipo_tierra_concreto = '$idtipo_tierra' 
		ORDER BY cpp.fecha_compra DESC;";

    return ejecutarConsultaSimpleFila($sql);  
  }

  //Implementamos un método para desactivar categorías
  public function desactivar_detalle_item($idmovimiento_tierra) {
    $sql = "UPDATE movimiento_tierra SET estado='0' WHERE idmovimiento_tierra ='$idmovimiento_tierra'";
    return ejecutarConsulta($sql);

  }
  
  //Implementamos un método para activar categorías
  public function eliminar_detalle_item($idmovimiento_tierra) {
    $sql = "UPDATE movimiento_tierra SET estado_delete='0' WHERE idmovimiento_tierra ='$idmovimiento_tierra'";
    return ejecutarConsulta($sql);  
  }
  
  public function mostrar_detalle_item($idmovimiento_tierra) {

    $sql = "SELECT*FROM movimiento_tierra WHERE idmovimiento_tierra ='$idmovimiento_tierra'";  
    return ejecutarConsultaSimpleFila($sql);  
  }
    
  //-----------------------------------------------------------------------------------------
  //------------------------------------- R E S U M E N  ------------------------------------
  //-----------------------------------------------------------------------------------------

  public function tbla_principal_resumen($idproyecto) {
    $sql = "SELECT cpp.idproyecto, cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, um.nombre_medida, um.abreviacion as um_abreviacion, 
		c.nombre_color, ttc.nombre as grupo, SUM(dc.cantidad) AS cantidad_total, SUM(dc.precio_con_igv) AS precio_con_igv, 
    SUM(dc.descuento) AS descuento_total, SUM(dc.subtotal) precio_total , COUNT(dc.idproducto) AS count_productos, 
    AVG(dc.precio_con_igv) AS promedio_precio
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, tipo_tierra_concreto AS ttc,
    unidad_medida AS um, color AS c
    WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
    AND um.idunidad_medida  = pr.idunidad_medida  AND c.idcolor = pr.idcolor  AND ttc.idtipo_tierra_concreto = pr.idtipo_tierra_concreto
    AND cpp.idproyecto = '$idproyecto'  AND cpp.estado = '1' AND cpp.estado_delete = '1' 
    AND pr.idtipo_tierra_concreto != '1' GROUP BY pr.idtipo_tierra_concreto ORDER BY ttc.nombre ASC;";
    return ejecutarConsulta($sql);
  }

  public function total_resumen($idproyecto) {
    $sql = "SELECT SUM( dc.subtotal ) AS total, SUM( dc.cantidad ) AS cantidad, SUM( dc.descuento ) AS descuento 
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, tipo_tierra_concreto AS ttc
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
		AND pr.idtipo_tierra_concreto=ttc.idtipo_tierra_concreto AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' 
    AND pr.idtipo_tierra_concreto != '1' AND cpp.estado_delete = '1';";
    return ejecutarConsultaSimpleFila($sql);
  }


}

?>
