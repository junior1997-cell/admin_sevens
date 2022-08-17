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
  public function desactivar($idtipo_tierra)
  {
    $sql = "UPDATE tipo_tierra SET estado='0' WHERE idtipo_tierra ='$idtipo_tierra'";
    return ejecutarConsulta($sql);

  }

  //Implementamos un método para activar categorías
  public function eliminar($idtipo_tierra)
  {
    $sql = "UPDATE tipo_tierra SET estado_delete='0' WHERE idtipo_tierra ='$idtipo_tierra'";
    return ejecutarConsulta($sql);

  }

  public function mostrar($idtipo_tierra)
  {

    $sql = "SELECT*FROM tipo_tierra WHERE idtipo_tierra ='$idtipo_tierra'";

    return ejecutarConsultaSimpleFila($sql);

  }

  //Implementar un método para listar los registros
  public function tbla_principal($proyecto) {

    $sql = "SELECT*FROM tipo_tierra WHERE idproyecto = '$proyecto'  AND modulo = 'Movimiento de Tierras' AND estado='1' AND estado_delete='1' ORDER BY idtipo_tierra DESC";

    return ejecutarConsulta($sql);

  }

  //-----------------------------------------------------------------------------------------
  //----------------------------------- Tabs -----------------------------------------------
  //-----------------------------------------------------------------------------------------

  public function listar_items($proyecto) {

    $sql = "SELECT*FROM tipo_tierra WHERE idproyecto = '$proyecto' AND modulo = 'Movimiento de Tierras'  AND estado='1' AND estado_delete='1' ORDER BY idtipo_tierra ASC";

    return ejecutarConsultaArray($sql);

  }

  //-----------------------------------------------------------------------------------------
  //----------------------- S E C C I O N  S E G Ú N  I T E M -------------------------------
  //-----------------------------------------------------------------------------------------

  public function insertar_detalle_item($idtipo_tierra_det,$idproveedor,$fecha,$nombre_dia,$cantidad,$precio_unitario,$total )
  {
    $sql="INSERT INTO movimiento_tierra(idproveedor, idtipo_tierra, fecha, nombre_dia, cantidad, precio_unitario, total) 
    VALUES ('$idproveedor','$idtipo_tierra_det','$fecha','$nombre_dia','$cantidad','$precio_unitario','$total')";
    return ejecutarConsulta($sql);
  }

  public function editar_detalle_item($idmovimiento_tierra,$idtipo_tierra_det,$idproveedor,$fecha,$nombre_dia,$cantidad,$precio_unitario,$total )
  {
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

  public function tbla_principal_tierra($d_proyecto,$idtipo_tierra,$fecha_1,$fecha_2,$id_proveedor,$comprobante) {

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

    $sql = "SELECT p.razon_social, p.tipo_documento, p.ruc, tt.idtipo_tierra, tt.idproyecto, tt.nombre, tt.modulo, mt.idmovimiento_tierra , mt.nombre_dia, mt.fecha, mt.cantidad, mt.precio_unitario, mt.total, mt.estado
    FROM tipo_tierra AS tt, movimiento_tierra AS mt, proveedor as p
    WHERE tt.idtipo_tierra = mt.idtipo_tierra AND mt.idproveedor = p.idproveedor AND tt.idtipo_tierra = '$idtipo_tierra' and mt.estado = '1' AND mt.estado_delete ='1' $filtro_fecha $filtro_proveedor ORDER BY mt.fecha ASC;";
    return ejecutarConsulta($sql);
  }

    //Implementamos un método para desactivar categorías
    public function desactivar_detalle_item($idmovimiento_tierra)
    {
      $sql = "UPDATE movimiento_tierra SET estado='0' WHERE idmovimiento_tierra ='$idmovimiento_tierra'";
      return ejecutarConsulta($sql);
  
    }
  
    //Implementamos un método para activar categorías
    public function eliminar_detalle_item($idmovimiento_tierra)
    {
      $sql = "UPDATE movimiento_tierra SET estado_delete='0' WHERE idmovimiento_tierra ='$idmovimiento_tierra'";
      return ejecutarConsulta($sql);
  
    }
  
    public function mostrar_detalle_item($idmovimiento_tierra)
    {
  
      $sql = "SELECT*FROM movimiento_tierra WHERE idmovimiento_tierra ='$idmovimiento_tierra'";
  
      return ejecutarConsultaSimpleFila($sql);
  
    }
    public function mostrar_total_det_item($idtipo_tierra,$fecha_1,$fecha_2,$id_proveedor,$comprobante)
    {
  
      $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

      if ( !empty($fecha_1) && !empty($fecha_2) ) {
        $filtro_fecha = "AND mt.fecha BETWEEN '$fecha_1' AND '$fecha_2'";
      } else if (!empty($fecha_1)) {      
        $filtro_fecha = "AND mt.fecha = '$fecha_1'";
      }else if (!empty($fecha_2)) {        
        $filtro_fecha = "AND mt.fecha = '$fecha_2'";
      }   

      if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.idproveedor = '$id_proveedor'"; }

      $sql = "SELECT SUM(mt.cantidad) as t_cantidad, SUM(mt.total) as total  
      FROM movimiento_tierra as mt, proveedor as p
      WHERE  mt.idproveedor = p.idproveedor AND mt.idtipo_tierra ='$idtipo_tierra'  AND mt.estado=1 AND mt.estado_delete=1 $filtro_fecha $filtro_proveedor;";
  
      return ejecutarConsultaSimpleFila($sql);
  
    }


}

?>
