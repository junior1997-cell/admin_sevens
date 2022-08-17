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

  //Implementar un método para listar los registros
  public function tbla_principal_concreto($idtipo_tierra) {
    $sql = "SELECT p.razon_social, p.tipo_documento, p.ruc, tt.idtipo_tierra, tt.idproyecto, tt.nombre, tt.modulo, tt.columna_calidad, 
    tt.columna_descripcion, ca.idconcreto_agregado, ca.detalle, ca.nombre_dia, ca.fecha, ca.calidad, ca.cantidad, ca.precio_unitario, ca.total, ca.estado
    FROM tipo_tierra AS tt, concreto_agregado AS ca, proveedor as p
    WHERE tt.idtipo_tierra = ca.idtipo_tierra AND ca.idproveedor = p.idproveedor AND tt.idtipo_tierra = '$idtipo_tierra' and ca.estado = '1' AND ca.estado_delete ='1'
    ORDER BY ca.fecha ASC";
    return ejecutarConsulta($sql);
  }

  // :::::::::::::::::::::::::: S E C C I O N    R E S U M E N ::::::::::::::::::::::::::
  //Implementar un método para listar los registros
  public function tbla_principal_resumen($idproyecto) {
    $sql = "SELECT p.razon_social, p.tipo_documento, p.ruc, tt.idtipo_tierra, tt.idproyecto, tt.nombre, tt.modulo, tt.columna_calidad, 
    tt.columna_descripcion, ca.idconcreto_agregado, ca.detalle, ca.nombre_dia, ca.fecha, ca.calidad, ca.cantidad, ca.precio_unitario, ca.total, ca.estado
    FROM tipo_tierra AS tt, concreto_agregado AS ca, proveedor as p
    WHERE tt.idtipo_tierra = ca.idtipo_tierra AND ca.idproveedor = p.idproveedor AND tt.idproyecto = '$idproyecto' and ca.estado = '1' AND ca.estado_delete ='1'
    ORDER BY ca.fecha ASC";
    return ejecutarConsulta($sql);
  }
}

?>
