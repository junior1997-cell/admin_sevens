<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ConcretoAgregado
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  // :::::::::::::::::::::::::: S E C C I O N   I T E M S  ::::::::::::::::::::::::::

  //Implementamos un método para insertar registros
  public function insertar_item( $idproyecto, $nombre_item, $modulo, $columna_calidad, $columna_descripcion, $descripcion_item) {
    $sql = "SELECT  nombre, columna_calidad, columna_descripcion, descripcion, estado, estado_delete
    FROM tipo_tierra WHERE nombre = '$nombre_item' AND idproyecto = '$idproyecto';";
    $buscando = ejecutarConsultaArray($sql);
    if ($buscando['status'] == false) { return $buscando; }

    if ( empty($buscando['data']) ) {
      $sql = "INSERT INTO tipo_tierra ( idproyecto, nombre, modulo, columna_calidad, columna_descripcion, descripcion) 
      VALUES ('$idproyecto', '$nombre_item', '$modulo', '$columna_calidad', '$columna_descripcion', '$descripcion_item')";
      return ejecutarConsulta($sql);
    } else {
      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>'.$value['nombre'].'<br>
          <b>Columna Calidad: </b>'.($value['columna_calidad'] ? '<span class="text-center badge badge-success">Si</span>' : '<span class="text-center badge badge-danger">No</span>').'<br>
          <b>Columna Descripción: </b>'.($value['columna_descripcion'] ? '<span class="text-center badge badge-success">Si</span>' : '<span class="text-center badge badge-danger">No</span>').'<br>
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
  public function editar_item( $idproyecto, $idtipo_tierra, $nombre_item, $modulo, $columna_calidad, $columna_descripcion, $descripcion_item)  {
     
    $sql = "UPDATE tipo_tierra 
    SET  idproyecto='$idproyecto', nombre='$nombre_item', modulo='$modulo',
    columna_calidad='$columna_calidad', columna_descripcion='$columna_descripcion', descripcion='$descripcion_item' 
    WHERE idtipo_tierra='$idtipo_tierra'";
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
  public function mostrar_item($idtipo_tierra) {
    $sql = "SELECT * FROM tipo_tierra WHERE  idtipo_tierra ='$idtipo_tierra'";

    return ejecutarConsultaSimpleFila($sql);    
  }

  //Implementar un método para listar los registros
  public function tbla_principal_item($id_proyecto) {
    $sql = "SELECT * FROM tipo_tierra WHERE idproyecto = '$id_proyecto' AND estado_delete='1' AND estado='1' ORDER BY nombre ASC";
    return ejecutarConsulta($sql);
  }
  
  //Implementar un método para listar los registros
  public function lista_de_items($id_proyecto) {
    $sql = "SELECT * FROM tipo_tierra WHERE idproyecto = '$id_proyecto' AND estado_delete='1' AND estado='1' ORDER BY nombre ASC";
    return ejecutarConsultaArray($sql);
  }
  // :::::::::::::::::::::::::: S E C C I O N    C O N C R E T O    A G R E G A D O::::::::::::::::::::::::::

  //Implementamos un método para insertar registros
  public function insertar_concreto( $idtipo_tierra_c, $idproveedor, $fecha, $nombre_dia, $calidad, $cantidad, $precio_unitario, $total, $descripcion_concreto) {    
    
    $sql = "INSERT INTO concreto_agregado( idproveedor, idtipo_tierra, fecha, nombre_dia, calidad, cantidad, precio_unitario, total, detalle) 
    VALUES ('$idproveedor', '$idtipo_tierra_c', ' $fecha', '$nombre_dia', '$calidad', '$cantidad', '$precio_unitario', '$total', '$descripcion_concreto')";
    return ejecutarConsulta($sql);   
    
  }

  //Implementamos un método para editar registros
  public function editar_concreto($idconcreto_agregado, $idtipo_tierra_c, $idproveedor, $fecha, $nombre_dia, $calidad, $cantidad, $precio_unitario, $total, $descripcion_concreto)  {
     
    $sql = "UPDATE concreto_agregado 
    SET idproveedor='$idproveedor', idtipo_tierra='$idtipo_tierra_c', fecha='$fecha', nombre_dia='$nombre_dia',
    calidad='$calidad', cantidad='$cantidad', precio_unitario='$precio_unitario', total='$total', detalle='$descripcion_concreto' 
    WHERE idconcreto_agregado ='$idconcreto_agregado'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal_concreto($idtipo_tierra) {
    $sql = "SELECT p.razon_social, p.tipo_documento, p.ruc, tt.idtipo_tierra, tt.idproyecto, tt.nombre, tt.modulo, tt.columna_calidad, 
    tt.columna_descripcion, ca.idconcreto_agregado, ca.detalle, ca.nombre_dia, ca.fecha, ca.calidad, ca.cantidad, ca.precio_unitario, ca.total, ca.estado
    FROM tipo_tierra AS tt, concreto_agregado AS ca, proveedor as p
    WHERE tt.idtipo_tierra = ca.idtipo_tierra AND ca.idproveedor = p.idproveedor AND tt.idtipo_tierra = '$idtipo_tierra' and ca.estado = '1' AND ca.estado_delete ='1'
    ORDER BY ca.fecha ASC";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros
  public function total_concreto($idtipo_tierra) {
    $sql = "SELECT  SUM(ca.cantidad) AS cantidad, AVG(ca.precio_unitario) AS precio_unitario, SUM(ca.total) AS total
    FROM tipo_tierra AS tt, concreto_agregado AS ca, proveedor as p
    WHERE tt.idtipo_tierra = ca.idtipo_tierra AND ca.idproveedor = p.idproveedor AND tt.idtipo_tierra = '$idtipo_tierra' and ca.estado = '1' AND ca.estado_delete ='1'
    ORDER BY ca.fecha ASC";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar_concreto($idconcreto_agregado) {
    $sql = "SELECT * FROM concreto_agregado WHERE  idconcreto_agregado ='$idconcreto_agregado'";

    return ejecutarConsultaSimpleFila($sql);
    
  }

  // :::::::::::::::::::::::::: S E C C I O N    R E S U M E N ::::::::::::::::::::::::::
  //Implementar un método para listar los registros
  public function tbla_principal_resumen($idproyecto) {
    $sql = "SELECT  tt.nombre,  SUM(ca.cantidad) AS cantidad, AVG(ca.precio_unitario) AS precio_unitario, SUM(ca.total) AS total
    FROM tipo_tierra AS tt, concreto_agregado AS ca, proveedor as p
    WHERE tt.idtipo_tierra = ca.idtipo_tierra AND ca.idproveedor = p.idproveedor AND tt.idproyecto = '$idproyecto' and tt.modulo ='Concreto y Agregado' and ca.estado = '1' AND ca.estado_delete ='1'
    GROUP BY tt.nombre  ORDER BY tt.nombre ASC ";
    return ejecutarConsulta($sql);
  }

  public function total_resumen($idproyecto) {
    $sql = "SELECT tt.nombre, SUM(ca.cantidad) AS cantidad, AVG(ca.precio_unitario) AS precio_unitario, SUM(ca.total) AS total
    FROM tipo_tierra AS tt, concreto_agregado AS ca, proveedor as p
    WHERE tt.idtipo_tierra = ca.idtipo_tierra AND ca.idproveedor = p.idproveedor AND tt.idproyecto = '$idproyecto' and tt.modulo ='Concreto y Agregado' and ca.estado = '1' AND ca.estado_delete ='1'";
    return ejecutarConsultaSimpleFila($sql);
  }
}

?>
